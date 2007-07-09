<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty plugin
 *
 * Type:     modifier
 * Date:     2007-06-19
 * Example:  {$foo|http}
 * @author   Andreas Gohr <gohr@cosmocode.de>
 * @param string
 * @return string
 */
function smarty_modifier_http($string){
    if(!$string) return '';

    list($url,$name) = explode(' ',$string,2);


    if(!preg_match('#^\w+://#',$url)){
        $url = 'http://'.$url;
    }

    if(!$name) $name = $url;

    return '<a href="'.htmlspecialchars($url).'" target="_blank">'.htmlspecialchars($name).'</a>';
}

/* vim: set expandtab: */

?>
