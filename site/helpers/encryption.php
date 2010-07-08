<?php
/**
 * SimpleDownload default encryption functions.  These are really only intended
 * for educational purposes and for areas where security is not highly important.
 * 
 * @package    Joomla.Joelrowley.Com
 * @subpackage Components
 * @link http://joomla.joelrowley.com/
 * @license		GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

function jcrypt($str, $offset=3) {
    $max = strlen($str);
    for($i = 0; $i < $max; $i++){
        //if the letter is upper case, keep it uppercase
        if(ord($str[$i]) >= 65 && ord($str[$i]) <= 90){
            if((ord($str[$i])+$offset) > 90) {
                $crypt .= chr(65+((ord($str[$i])+$offset)-91));
            } else if((ord($str[$i])+$offset) < 65) {
                $crypt .= chr(90+((ord($str[$i])+$offset)-64));
            } else {
                $crypt .= chr(ord($str[$i])+$offset);
            }
        }
        
        //if the letter is lower case, keep it lower case
        else if(ord($str[$i]) >= 97 && ord($str[$i]) <= 122){
            if((ord($str[$i])+$offset) > 122) {
                $crypt .= chr(97+((ord($str[$i])+$offset)-123));
            } else if((ord($str[$i])+$offset) < 97) {
                $crypt .= chr(122+((ord($str[$i])+$offset)-96));
            } else {
                $crypt .= chr(ord($str[$i])+$offset);
            }
        }
        
    	else if(ord($str[$i]) >= 48 && ord($str[$i]) <= 57){
            if((ord($str[$i])+$offset) > 57) {
                $crypt .= chr(48+((ord($str[$i])+$offset)-58));
            } else {
                $crypt .= chr(ord($str[$i])+$offset);
            }
        }
        
        else {
        	$crypt .= chr(ord($str[$i])+$offset);
        	// in case of path separator
        	/*if (ord($str[$i]) == 47) {
        		$crypt .= '#';
        	// in case of period
        	} else if (ord($str[$i]) == 46) {
        		$crypt .= '*';
        	// in case of dash (-)
        	} else if (ord($str[$i]) == 45) {
        		$crypt .= '!';
        	} else {
        		echo "<- " . $i . " - " . ord($str[$i]) . " ->";
        	}*/
            //die("You can only use letters.");
            //$crypt .= chr(ord($str[$i])+$offset);
        }
    }
    //$crypt = strtoupper($crypt);
    return $crypt;
}

function jdecrypt($str) {
	return jcrypt($str, -3);
}

function jcrypt64($str) {
	return base64_encode($str);
}

function jdecrypt64($str) {
	return base64_decode($str);
}

?> 