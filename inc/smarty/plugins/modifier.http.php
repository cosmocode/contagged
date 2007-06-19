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
 * Name:     noteparser
 * Date:     2007-06-19
 * Purpose:  Add the http:// protocol if missing
 * Example:  {$foo|http}
 * @author   Andreas Gohr <gohr@cosmocode.de>
 * @param string
 * @return string
 */
function smarty_modifier_http($string){
    if(!$string) return '';

    if(!preg_match('#^\w+://#',$string)){
        $string = 'http://'.$string;
    }

    return $string;
}

/* vim: set expandtab: */

?>
