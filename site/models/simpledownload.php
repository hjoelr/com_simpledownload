<?php
/**
 * SimpleDownload model for the SimpleDownload Component.  This model
 * is not currently in use.
 * 
 * @package    Joomla.Joelrowley.Com
 * @subpackage Components
 * @link http://joomla.joelrowley.com/
 * @license		GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.model' );

/**
 * SimpleDownload Model
 *
 * @package    Joomla.Joelrowley.Com
 * @subpackage Components
 */
class SimpleDownloadModelSimpleDownload extends JModel
{
	/**
	 * Gets the greeting
	 * @return string The greeting to be displayed to the user
	 */
	function getGreeting()
	{
		$db =& JFactory::getDBO();

		$query = 'SELECT greeting FROM #__hello';
		$db->setQuery( $query );
		$greeting = $db->loadResult();

		return $greeting;
	}
}
