<?
  require_once('init.php');
  ldap_login();

  //prepare templates
  tpl_std();
  tpl_orgs();
  //display templates
  $smarty->display('header.tpl');
  $smarty->display('orgs.tpl');
  $smarty->display('footer.tpl');


?>
