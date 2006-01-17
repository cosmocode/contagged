<?
  require_once('init.php');
  ldap_login();

  //prepare templates
  tpl_std();
  //display templates
  header('Content-Type: text/html; charset=utf-8');
  $smarty->display('header.tpl');
  $smarty->display('help.tpl');
  $smarty->display('footer.tpl');

?>
