<?
  require_once('init.php');
  require_once('Contact_Vcard_Parse.php');
  require_once('xml.php');
  ldap_login();

  if(! $_SESSION[ldapab][username] ){
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
      }elseif(preg_match('/^addressbook\.xml$/i', $_FILES['userfile']['name'])){
        //parse Zaurus Addressbook
        $contacts  = XML_unserialize(@join('',@file($_FILES['userfile']['tmp_name'])));
      }else{
          $error = "Only *.vcf or addressbook.xml are accepted";
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
  }elseif(count($contacts)){
    foreach ($contacts['AddressBook']['Contacts']['Contact'] as $contact){
      if (!is_array($contact)) continue;
      $entry = zaurus_entry($contact);
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


function zaurus_entry($cnct){
  $entry['name']         = $cnct['LastName'];
  $entry['givenname']    = trim($cnct['FirstName'].' '.$cnct['MiddleName']);
  $entry['title']        = $cnct['JobTitle'];
  $entry['organization'] = $cnct['Company'];
  $entry['office']       = $cnct['Office'];
  $entry['note']         = $cnct['Notes'];
  $entry['mail']         = split(' ',$cnct['Emails']);
  $entry['street']       = $cnct['BusinessStreet'];
  $entry['location']     = $cnct['BusinessCity'];
  $entry['zip']          = $cnct['BusinessZip'];
  $entry['homestreet']   = $cnct['HomeStreet']."\n". //str
                           $cnct['HomeZip']." ".     //plz
                           $cnct['HomeCity'];        //ort
  $entry['homephone']    = $cnct['HomePhone'];
  $entry['phone']        = $cnct['BusinessPhone'];
  $entry['fax']          = empty($cnct['BusinessFax']) ? $cnct['HomeFax'] : $cnct['BusinessFax'];
  $entry['mobile']       = empty($cnct['HomeMobile']) ? $cnct['BusinessMobile'] : $cnct['HomeMobile'];
  $entry['fax']          = empty($cnct['BusinessFax']) ? $cnct['HomeFax'] : $cnct['BusinessFax'];

#  $entry['anniversary'] = $cnt[''];

  utf8_decode_array($entry);
  return $entry;
}

function vcard_entry($vcf){
  $entry['name']         = $vcf['N'][0]['value'][0][0];
  $entry['givenname']    = trim($vcf['N'][0]['value'][1][0].' '.$vcf['N'][0]['value'][2][0]);
  $entry['title']        = $vcf['N'][0]['value'][3][0];
  $entry['organization'] = $vcf['ORG'][0]['value'][0][0];
  $entry['office']       = $vcf['ORG'][0]['value'][1][0];
  $entry['note']         = $vcf['NOTE'][0]['value'][0][0];
  $entry['url']          = $vcf['URL'][0]['value'][0][0];
  $bday                  = $vcf['BDAY'][0]['value'][0][0];
  $entry['anniversary']  = substr($bday,0,4).'-'.substr($bday,4,2).'-'.substr($bday,6,2);

  foreach($vcf['TEL'] as $tel){
    if(   empty($entry['phone']) &&
          array_search('WORK',$tel['param']['TYPE']) !== FALSE &&
          array_search('VOICE',$tel['param']['TYPE']) !== FALSE){
      // Work phone
      $entry['phone'] = $tel['value'][0][0];
    }elseif(empty($entry['fax']) &&
            array_search('FAX',$tel['param']['TYPE']) !== FALSE){
      $entry['fax'] = $tel['value'][0][0];
    }elseif(empty($entry['mobile']) &&
            array_search('CELL',$tel['param']['TYPE']) !== FALSE){
      $entry['mobile'] = $tel['value'][0][0];
    }elseif(empty($entry['pager']) &&
            array_search('PAGER',$tel['param']['TYPE']) !== FALSE){
      $entry['pager'] = $tel['value'][0][0];
    }elseif(empty($entry['homephone']) &&
            array_search('HOME',$tel['param']['TYPE']) !== FALSE &&
            array_search('VOICE',$tel['param']['TYPE']) !== FALSE){
      $entry['homephone'] = $tel['value'][0][0];
    }
  }
  foreach($vcf['EMAIL'] as $mail){
    $entry['mail'][] = $mail['value'][0][0];
  }
  foreach($vcf['ADR'] as $adr){
    if(array_search('HOME',$adr['param']['TYPE']) !== FALSE){
      $entry['homestreet'] = $adr['value'][2][0]."\n". //str
                             $adr['value'][5][0]." ". //plz
                             $adr['value'][3][0];      //ort

    }elseif(array_search('WORK',$adr['param']['TYPE']) !== FALSE){
      $entry['street']   = $adr['value'][2][0];
      $entry['location'] = $adr['value'][3][0];
      $entry['zip']      = $adr['value'][5][0];
    }
  }

/*
  print '<pre>';
  print_r($entry);
  print '</pre>';
*/

  return $entry;
}


?>
