<?
  require_once('init.php');
  ldap_login();

  //prepare templates
  tpl_std();
  //display templates
  $smarty->display('header.tpl');
  $smarty->display('help.tpl');
  $smarty->display('footer.tpl');

?>
