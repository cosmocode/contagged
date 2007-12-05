<?php
require_once('inc/init.php');
ldap_login();

if ($conf['userlogreq'] && $user == ''){
  header("HTTP/1.0 401 Access Denied");
  echo '<h1>Access Denied</h1>';
  exit();
}

$dn = $_REQUEST['dn'];

$sr = ldap_search($LDAP_CON,$dn,'(objectClass=inetOrgPerson)',array($FIELDS['photo']));
if(!ldap_count_entries($LDAP_CON,$sr)){
  header("HTTP/1.0 404 Not Found");
  echo '<h1>Not Found</h1>';
  exit;
}
$result = ldap_get_binentries($LDAP_CON, $sr);
$entry  = $result[0];

header("Content-type: image/jpeg");
print $entry[$FIELDS['photo']][0];
?>
