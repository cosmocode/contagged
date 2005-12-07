<?
  require_once('init.php');

  $msg = $lang[msg_login];
  if(isset($_REQUEST[username])){
    if (do_ldap_bind($_REQUEST[username],$_REQUEST[password])){
      //forward to next page
      if(!empty($_SESSION[ldapab][lastlocation])){
        header('Location: '.$_SESSION[ldapab][lastlocation]);
      }else{
        header('Location: index.php');
      }
      exit;
    }else{
      $msg = $lang[msg_loginfail];;
    }
  }

  //prepare templates
  tpl_std();
  $smarty->assign('msg',$msg);
  //display templates
  $smarty->display('header.tpl');
  $smarty->display('login.tpl');
  $smarty->display('footer.tpl');
?>
