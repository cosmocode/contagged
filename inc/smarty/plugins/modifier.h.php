<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Shortcut to the Smarty escape modifier plugin
 *
 * Type:     modifier<br>
 * Name:     h<br>
 */
function smarty_modifier_h($string, $esc_type = 'html', $char_set = 'ISO-8859-1')
{
    return smarty_modifier_escape($string,'html','UTF-8');
}

/* vim: set expandtab: */

?>
