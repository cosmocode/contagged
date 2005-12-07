<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty escape modifier plugin
 *
 * Type:     modifier<br>
 * Name:     escape<br>
 * Purpose:  Escape the string according to escapement type
 * @link http://smarty.php.net/manual/en/language.modifier.escape.php
 *          escape (Smarty online manual)
 * @param string
 * @param html|htmlall|url|quotes|hex|hexentity|javascript
 * @return string
 */
function smarty_modifier_escape($string, $esc_type = 'html')
{
    switch ($esc_type) {
        case 'html':
            return htmlspecialchars($string, ENT_QUOTES);

        case 'htmlall':
            return htmlentities($string, ENT_QUOTES);

        case 'url':
            return urlencode($string);

        case 'quotes':
            // escape unescaped single quotes
            return preg_replace("%(?<!\\\\)'%", "\\'", $string);

        case 'qp':
            return smarty_modifier_escape_qp_enc($string);

		case 'hex':
			// escape every character into hex
			$return = '';
			for ($x=0; $x < strlen($string); $x++) {
				$return .= '%' . bin2hex($string[$x]);
			}
			return $return;
            
		case 'hexentity':
			$return = '';
			for ($x=0; $x < strlen($string); $x++) {
				$return .= '&#x' . bin2hex($string[$x]) . ';';
			}
			return $return;

        case 'javascript':
            // escape quotes and backslashes and newlines
            return strtr($string, array('\\'=>'\\\\',"'"=>"\\'",'"'=>'\\"',"\r"=>'\\r',"\n"=>'\\n'));

        default:
            return $string;
    }
}

function smarty_modifier_escape_qp_enc( $input = "") {
   $hex = array('0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F');
   $escape  = "=";
   $linlen  = strlen($input);
   $newline = '';
   for($i = 0; $i < $linlen; $i++) {
     $c = substr( $input, $i, 1 );
     $dec = ord( $c );
     if( ($dec == 61) || ($dec < 32 ) || ($dec > 126) ){
       $h2 = floor($dec/16);
       $h1 = floor($dec%16);
       $c = $escape.$hex["$h2"].$hex["$h1"];
     }
     $newline .= $c;
   }
   return $newline;
}

/* vim: set expandtab: */

?>
