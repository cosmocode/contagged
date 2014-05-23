<?php
/**
 * Do the login/logout process
 */

require_once('inc/init.php');

$msg = $lang['msg_login'];
if(!empty($_REQUEST['username'])){
    if (empty($_REQUEST['password'])) { $_REQUEST['password']=''; }
    if (do_ldap_bind($_REQUEST['username'],$_REQUEST['password'])){

        //create private address book if simple enough
        if(!ldap_read($LDAP_CON,$_SESSION['ldapab']['privatedn'],'')){
          $ou = '';
          if($conf['privatebook_absolute'] && preg_match('/^ou=%u,/',$conf['privatebook_absolute'])){
            $ou = $_SESSION['ldapab']['username'];
          }else if(!$conf['privatebook_absolute'] && preg_match('/ou=([^,]+)$/',$conf['privatebook'],$match)){
            $ou = $match[1];
          }
          if(!empty($ou)) {
            ldap_add($LDAP_CON,$_SESSION['ldapab']['privatedn'],
                     array('objectClass' => array ('organizationalUnit','top'),
                           'ou' => $ou));
          }
        }

        //forward to next page
        if(!empty($_SESSION['ldapab']['lastlocation'])){
            header('Location: '.$_SESSION['ldapab']['lastlocation']);
        }else{
            header('Location: index.php');
        }
        exit;
    }else{
        $msg = $lang['msg_loginfail'];;
    }
}else{
    //logout
    unset($_SESSION['ldapab']);
}

//prepare templates
tpl_std();
$smarty->assign('msg',$msg);
//display templates
header('Content-Type: text/html; charset=utf-8');
$smarty->display('login.tpl');

