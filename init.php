<?
  require_once('config.php');
  require_once('fields.php');
  require_once('lang/'.$conf['lang'].'.php');
  require_once('functions.php');
  require_once('template.php');
  require_once('smarty/Smarty.class.php');

  //init session
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
  set_magic_quotes_runtime(0);

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
  $smarty->compile_dir   = './cache';
  $smarty->use_sub_dirs  = 0;
  $smarty->template_dir  = './templates';
  $smarty->force_compile = $conf['smartycompile'];
  $smarty->default_modifiers = 'escape:"htmlall":"UTF-8"';
?>
