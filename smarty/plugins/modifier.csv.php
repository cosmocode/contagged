<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty csv modifier plugin
 *
 * Type:     modifier<br>
 * Name:     csv<br>
 * Purpose:  format string for CSV output
 * @param string
 * @return string
 */
function smarty_modifier_csv($string)
{
	return '"'.strtr(trim($string),'"','""').'"';
}

?>
