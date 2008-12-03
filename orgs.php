<?php
  require_once('inc/init.php');
  ldap_login();

  if ($conf['userlogreq'] && !isset($_SESSION['ldapab']['username'])){
      header('Location: login.php');
      exit();
  }

  //prepare templates
  tpl_std();
  tpl_orgs();
  //display templates
  header('Content-Type: text/html; charset=utf-8');
  $smarty->display('orgs.tpl');
?>
