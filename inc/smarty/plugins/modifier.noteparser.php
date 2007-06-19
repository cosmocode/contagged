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
 * Date:     Feb 26, 2003
 * Purpose:  Converts plaintext notes to richer HTML (very simple)
 * Example:  {$entry.note|noteparser}
 * @author   Andreas Gohr <gohr@cosmocode.de>
 * @param string
 * @return string
 */
function smarty_modifier_noteparser($string){
    $string = htmlspecialchars($string);

    $string = preg_replace('!\*\*Call\*\*!i','<img src="pix/phone.png" width="16" height="16" alt="Call" />',$string);
    $string = preg_replace('!\*\*ToDo\*\*!i','<img src="pix/arrow_right.png" width="16" height="16" alt="ToDo" />',$string);
    $string = preg_replace('!\*\*Mail\*\*!i','<img src="pix/email.png" width="16" height="16" alt="Mail" />',$string);
    $string = preg_replace('!\*\*Note\*\*!i','<img src="pix/note.png" width="16" height="16" alt="note" />',$string);

    $string = preg_replace('!\*\*(.*?)\*\*!','<b>\\1</b>',$string);
    $string = preg_replace('!__(.*?)__!','<u>\\1</u>',$string);
    $string = preg_replace('!//(.*?)//!','<i>\\1</i>',$string);

    $string = preg_replace('!(https?://[\w;/?:@&=+$\-_.\!~*\\\']+)!i',
                           '<a href="\\1">\\1</a>',$string);

    $string = preg_replace('!\n\n+!','</p><p>',$string);
    $string = nl2br($string);

    return '<p>'.$string.'</p>';
}

/* vim: set expandtab: */

?>
