<?php
/**
 * SimpleDownload view to display when an error occurs.
 * 
 * @package    Joomla.Joelrowley.Com
 * @subpackage Components
 * @link http://joomla.joelrowley.com/
 * @license		GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

/**
 * HTML View class for displaying error messages for the
 * SimpleDownload Component
 *
 * @package    Joomla.Joelrowley.Com
 * @subpackage Components
 */
class SimpleDownloadViewError extends JView
{
	function display($tpl = null)
	{	
		$document	=& JFactory::getDocument();
		$document->setTitle( $this->page_title );

		parent::display($tpl);
	}
}
?>
