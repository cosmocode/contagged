<?php
require_once('inc/init.php');
ldap_login();

$users = get_users();

//select template to use
if (empty($_REQUEST['mode'])) { $_REQUEST['mode']='show'; }
if( $_SESSION['ldapab']['username'] &&
   ($_REQUEST['mode']=='edit' || $_REQUEST['mode']=='copy')){
  $template='entry_edit.tpl';
}elseif($_REQUEST['mode']=='vcf'){
  $template='entry_vcf.tpl';
}else{
  $template='entry_show.tpl';
}

if (empty($_REQUEST['dn'])) {
  $dn = "";
}else{
  $dn = $_REQUEST['dn'];
  #$dn = 'cn=bar foo, ou=contacts, o=cosmocode, c=de';
}

//save data if asked for
if($_SESSION['ldapab']['username'] && !empty($_REQUEST['save']) && $_REQUEST['save']){
  // prepare special data
  $_REQUEST['entry']['photo']  = _getUploadData();
  $_REQUEST['entry']['marker'] = explode(',',$_REQUEST['entry']['markers']);
  unset($_REQUEST['entry']['markers']);

  foreach(array_keys($_REQUEST['entry']) as $field){
      if($FIELDS['_'.$field]){
          // entry has to be handled as array -> clean it up (trim, unique, sort)
          $_REQUEST['entry'][$field] = array_map('trim',$_REQUEST['entry'][$field]);
          $_REQUEST['entry'][$field] = array_unique($_REQUEST['entry'][$field]);
          $_REQUEST['entry'][$field] = array_filter($_REQUEST['entry'][$field]);
          natcasesort($_REQUEST['entry'][$field]);
      }
  }
  $dn = _saveData();
}

if(empty($dn)){
  if(!$_REQUEST['mode']=='edit'){
    $smarty->assign('error','No dn was given');
    $template = 'error.tpl';
  }
}elseif(!empty($_REQUEST['del']) && $_REQUEST['del']){
  _delEntry($dn);
}elseif(!_fetchData($dn)){
  $smarty->assign('error',"The requested entry '$dn' was not found");
  $template = 'error.tpl';
}

//prepare templates
$smarty->assign('dn',$dn);
$smarty->assign('managers',$users);
tpl_std();
//display templates
if($_REQUEST['mode']=='vcf'){
  $entry = $smarty->get_template_vars('entry');
  $filename = $entry['givenname'].'_'.$entry['name'].'.vcf';
  header("Content-Disposition: attachment; filename=\"$filename\"");
  header("Content-type: text/x-vcard; name=\"$filename\"; charset=utf-8");
  $smarty->display($template);
}else{
  header('Content-Type: text/html; charset=utf-8');
  $smarty->display($template);
}

//--------------------------------------------------------------

/**
 * fetches the Data from the LDAP directory and assigns it to
 * the global smarty object using tpl_entry()
 */
function _fetchData($dn){
  global $LDAP_CON;
  global $conf;
  global $smarty;
  global $users; //contains the users for manager role

  $sr = ldap_search($LDAP_CON,$dn,'(objectClass=inetOrgPerson)');
  if(!ldap_count_entries($LDAP_CON,$sr)){
    return false;
  }
  $result = ldap_get_binentries($LDAP_CON, $sr);
  $entry  = $result[0];

  //remove dn from entry when copy
  if(!empty($_REQUEST['mode']) && $_REQUEST['mode'] == 'copy'){
    $entry['dn']='';
  }

  //assign entry to template:
  tpl_entry($entry);

/*print '<pre>';
print_r($entry);
print '</pre>';*/

  // make username from dn for manager:
  if (empty($entry['manager'])) { $entry['manager']=array(""); }
  if (empty($users[$entry['manager'][0]])) { $users[$entry['manager'][0]]=''; }
  $smarty->assign('managername',$users[$entry['manager'][0]]);
  return true;
}

/**
 * saves the data from $_REQUEST['entry'] to the LDAP directory
 *
 * returns given or constructed dn
 */
function _saveData(){
  global $LDAP_CON;
  global $conf;
  global $FIELDS;
  global $OCLASSES;

  $entry = $_REQUEST['entry'];
  $dn    = $_REQUEST['dn'];
  //construct new dn
  $now    = time();
  $newdn  = 'uid='.$now;
  if (empty($_REQUEST['type'])) { $_REQUEST['type']='public'; }
  if($_REQUEST['type'] == 'private'){
    $newdn .= ', '.$conf['privatebook'].', '.$_SESSION['ldapab']['binddn'];
  }else{
    $newdn .= ', '.$conf['publicbook'];
  }
  $entry['displayname'] = $entry['givenname'].' '.$entry['name'];;
  $entry = prepare_ldap_entry($entry);

/*
print '<pre>';
print_r($entry);
print '</pre>';
*/

  if(empty($dn)){
    //new entry
    $entry['uid'][] = $now;
    $r = ldap_add($LDAP_CON,$newdn,$entry);
    tpl_ldaperror();
    return $newdn;
  }else{
    // update the objectClasses
    ldap_store_objectclasses($dn,$OCLASSES);
    unset($entry['objectclass']);

    //modify entry attribute by attribute - this ensure we don't delete unknown stuff
    foreach (array_values($FIELDS) as $key){
      if($key == 'dn'){
        continue;
      }elseif(empty($entry[$key])){
        // field is empty -> handle deletion (except for photo unless deletion triggered)
        if (empty($_REQUEST['delphoto'])) { $_REQUEST['delphoto']=0; }
        if($key == 'jpegPhoto' && !$_REQUEST['delphoto']){
          continue;
        }
        unset($del);
        $del[$key]=array();
        $r = @ldap_mod_replace($LDAP_CON,$dn,$del);
        tpl_ldaperror("del $key");
      }else{
        unset($add);
        $add[$key]=$entry[$key];
        $r = @ldap_mod_replace($LDAP_CON,$dn,$add);
        tpl_ldaperror("mod $key");
      }
    }
    return $dn;
  }
}

/**
 * does as the name says - delete the whole entry
 */
function _delEntry($dn){
  global $LDAP_CON;
  if(ldap_full_delete($LDAP_CON,$dn,true)){
    header("Location: index.php");
    exit;
  }
}

/**
 * gets the binary data from an uploaded file
 */
function _getUploadData(){
  $file = $_FILES['photoupload'];

  if (is_uploaded_file($file['tmp_name'])) {
    if(preg_match('=image/p?jpe?g=',$file['type'])){
      $fh = fopen($file['tmp_name'],'r');
      $data = fread($fh,$file['size']);
      fclose($fh);
      unlink($file['tmp_name']);
      return $data;
    }
  }
  return '';
}

