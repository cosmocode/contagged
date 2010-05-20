<?php
  require_once(dirname(__FILE__).'/config.php');
  require_once(dirname(__FILE__).'/fields.php');
  require_once(dirname(__FILE__).'/lang/en.php');
  require_once(dirname(__FILE__).'/lang/'.$conf['lang'].'.php');
  require_once(dirname(__FILE__).'/functions.php');
  require_once(dirname(__FILE__).'/template.php');
  require_once(dirname(__FILE__).'/smarty/Smarty.class.php');

  define('NL',"\n");

  //init session
  @ini_set('arg_separator.output', '&amp;');
  session_name("ldapab");
  session_start();

  //kill magic quotes
  if (get_magic_quotes_gpc()) {
    if (!empty($_GET))    remove_magic_quotes($_GET);
    if (!empty($_POST))   remove_magic_quotes($_POST);
    if (!empty($_COOKIE)) remove_magic_quotes($_COOKIE);
    if (!empty($_REQUEST)) remove_magic_quotes($_REQUEST);
    if (!empty($_SESSION)) remove_magic_quotes($_SESSION);
    ini_set('magic_quotes_gpc', 0);
  }
  @set_magic_quotes_runtime(0);

  function remove_magic_quotes(&$array) {
    foreach (array_keys($array) as $key) {
      if (is_array($array[$key])) {
        remove_magic_quotes($array[$key]);
      }else {
        $array[$key] = stripslashes($array[$key]);
      }
    }
  }

  //prepare SMARTY object
  $smarty = new Smarty;
  $smarty->compile_dir   = dirname(__FILE__).'/../cache';
  $smarty->use_sub_dirs  = 0;
  $smarty->template_dir  = dirname(__FILE__).'/../templates';
  $smarty->force_compile = $conf['smartycompile'];

  // select the correct google api key
  $conf['gmapkey'] = $conf['gmaps'][$_SERVER['HTTP_HOST']];

?>
