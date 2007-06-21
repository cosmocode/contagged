<?php
require_once('inc/init.php');
ldap_login();

$dn = $_REQUEST['dn'];

$sr = ldap_search($LDAP_CON,$dn,'(objectClass=inetOrgPerson)',array($FIELDS['photo']));
if(!ldap_count_entries($LDAP_CON,$sr)){
  exit;
}
$result = ldap_get_binentries($LDAP_CON, $sr);
$entry  = $result[0];

header("Content-type: image/jpeg");
print $entry[$FIELDS['photo']][0];
?>
