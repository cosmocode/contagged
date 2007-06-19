<?php
require_once('inc/init.php');
ldap_login();

//prepare templates
tpl_std();
//display templates
header('Content-Type: text/html; charset=utf-8');
$smarty->display('help.tpl');

?>
