<?php
/**
 * SimpleDownload entry point file for SimpleDownload Component
 * 
 * @package    Joomla.Joelrowley.Com
 * @subpackage Components
 * @link http://joomla.joelrowley.com/
 * @license		GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Require the base controller
require_once (JPATH_COMPONENT.DS.'controller.php');

/*$controller = addslashes(JRequest::getVar('controller'));

// Require specific controller if requested
if($controller) {
	require_once (JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php');
}

// Create the controller
$classname	= 'SimpleDownloadController'.$controller;
$controller = new $classname();*/

$controller = new SimpleDownloadController();

// Perform the Request task
$controller->execute( JRequest::getVar('task'));

// Redirect if set by the controller
$controller->redirect();

?>
