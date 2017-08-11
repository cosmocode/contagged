<?php
  if(defined('E_DEPRECATED')){ // since php 5.3
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
  }else{
    error_reporting(E_ALL ^ E_NOTICE);
  }

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

  //prepare SMARTY object
  $smarty = new Smarty;
  $smarty->compile_dir   = dirname(__FILE__).'/../cache';
  $smarty->use_sub_dirs  = 0;
  $smarty->template_dir  = dirname(__FILE__).'/../templates';
  $smarty->force_compile = $conf['smartycompile'];

  // select the correct google api key
  $conf['gmapkey'] = $conf['gmaps'][$_SERVER['HTTP_HOST']];

?>
