<?
  require_once('init.php');
  ldap_login();

  //prepare templates
  tpl_std();
  tpl_orgs();
  //display templates
  header('Content-Type: text/html; charset=utf-8');
  $smarty->display('orgs.tpl');
?>
