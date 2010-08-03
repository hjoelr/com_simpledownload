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
		$params	=& JComponentHelper::getParams( 'com_simpledownload' );
		
		$errorCode = JRequest::getVar( 'err', '0' );
		
		switch ($errorCode) {
			case '404':
				$document->setTitle( $params->get('title_filenotfound') );
				$this->assign( 'msg', $params->get('msg_filenotfound') );
				break;
			case '412':
				$document->setTitle( JText::_('INVALID_COMPONENT_CONFIGURATION_PAGE_TITLE') );
				$this->assign( 'msg', JText::_('INVALID_COMPONENT_CONFIGURATION_MESSAGE') );
				break;
			case '500':
				$document->setTitle( 'Component Error' );
				$this->assign( 'msg', 'An unknown error occurred.  Sorry for the inconvenience.' );
				break;
			default:
				$document->setTitle( 'SimpleDownload' );
				$this->assign( 'msg', 'You have most likely gotten to this page in error.  Please try going <a href="/">to the home page</a>.' );
				break;
		}

		parent::display($tpl);
	}
}
?>
