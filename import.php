<?
require_once('inc/init.php');
require_once('inc/Contact_Vcard_Parse.php');
ldap_login();

if(! $_SESSION['ldapab']['username'] ){
  header("Location: login.php");
  exit;
}

$error = '';
if(isset($_FILES['userfile'])){
  if (is_uploaded_file($_FILES['userfile']['tmp_name'])){
    if(preg_match('/\.vcf$/i', $_FILES['userfile']['name'])){
      //parse VCF
      $vcfparser = new Contact_Vcard_Parse();
      $vcards    = $vcfparser->fromFile($_FILES['userfile']['tmp_name']);
    }else{
        $error = "Only *.vcf accepted";
    }
  }else{
    switch($_FILES['userfile']['error']){
     case 0: //no error; possible file attack!
       $error = "There was a problem with your upload.";
       break;
     case 1: //uploaded file exceeds the upload_max_filesize directive in php.ini
       $error = "The file you are trying to upload is too big.";
       break;
     case 2: //uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form
       $error = "The file you are trying to upload is too big.";
       break;
     case 3: //uploaded file was only partially uploaded
       $error = "The file you are trying upload was only partially uploaded.";
       break;
     case 4: //no file was uploaded
       $error = "You must select a VCF file for upload.";
       break;
     default: //a default error, just in case!  :)
       $error = "There was a problem with your upload.";
       break;
    }
  }
}

//prepare templates for all found entries
$list = '';
if(count($vcards)){
  foreach ($vcards as $vcard){
    $entry = vcard_entry($vcard);
    $smarty->clear_all_assign();
    tpl_std();
    $smarty->assign('entry',$entry);
    $list .= $smarty->fetch('import_entry.tpl');
  }
}

//prepare templates
tpl_std();
tpl_orgs();
tpl_markers();
$smarty->assign('error',$error);
$smarty->assign('list',$list);
//display templates
$smarty->display('header.tpl');
$smarty->display('import.tpl');
$smarty->display('footer.tpl');


function vcard_entry($vcf){
    $entry = array();

    $entry['name']         = $vcf['N'][0]['value'][0][0];
    $entry['givenname']    = trim($vcf['N'][0]['value'][1][0].' '.$vcf['N'][0]['value'][2][0]);
    $entry['title']        = $vcf['N'][0]['value'][3][0];
    $entry['organization'] = $vcf['ORG'][0]['value'][0][0];
    $entry['office']       = $vcf['ORG'][0]['value'][1][0];
    $entry['note']         = $vcf['NOTE'][0]['value'][0][0];
    $entry['url']          = $vcf['URL'][0]['value'][0][0];
    $bday                  = $vcf['BDAY'][0]['value'][0][0];
    $entry['birthday']     = substr($bday,0,4).'-'.substr($bday,4,2).'-'.substr($bday,6,2);

    foreach((array) $vcf['TEL'] as $tel){
      if(   empty($entry['phone']) &&
            array_search('WORK',(array) $tel['param']['TYPE']) !== false &&
            array_search('VOICE',(array) $tel['param']['TYPE']) !== false){
        // Work phone
        $entry['phone'] = $tel['value'][0][0];
      }elseif(empty($entry['fax']) &&
              array_search('FAX',(array) $tel['param']['TYPE']) !== false){
        $entry['fax'] = $tel['value'][0][0];
      }elseif(empty($entry['mobile']) &&
              array_search('CELL',(array) $tel['param']['TYPE']) !== false){
        $entry['mobile'] = $tel['value'][0][0];
      }elseif(empty($entry['pager']) &&
              array_search('PAGER',(array) $tel['param']['TYPE']) !== false){
        $entry['pager'] = $tel['value'][0][0];
      }elseif(empty($entry['homephone']) &&
              array_search('HOME',(array) $tel['param']['TYPE']) !== false &&
              array_search('VOICE',(array) $tel['param']['TYPE']) !== false){
        $entry['homephone'] = $tel['value'][0][0];
      }
    }
    foreach((array) $vcf['EMAIL'] as $mail){
      $entry['mail'][] = $mail['value'][0][0];
    }
    foreach((array) $vcf['ADR'] as $adr){
      if(array_search('HOME',(array)$adr['param']['TYPE']) !== false){
        $entry['homestreet'] = $adr['value'][2][0]."\n". //str
                               $adr['value'][5][0]." ". //plz
                               $adr['value'][3][0];      //ort

      }elseif((array) array_search('WORK',(array)$adr['param']['TYPE']) !== false){
        $entry['street']   = $adr['value'][2][0];
        $entry['location'] = $adr['value'][3][0];
        $entry['zip']      = $adr['value'][5][0];
        $entry['country']  = $adr['value'][6][0];
        $entry['state']    = $adr['value'][4][0];
      }
    }

    return $entry;
}


?>
