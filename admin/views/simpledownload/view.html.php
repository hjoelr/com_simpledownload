<?php
/**
 * Default backend View for the SimpleDownload Component.
 * 
 * @package    Joomla.Joelrowley.Com
 * @subpackage Components
 * @link http://joomla.joelrowley.com/
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

/**
 * SimpleDownload default backend View.
 *
 * @package    Joomla.Joelrowley.Com
 * @subpackage Components
 */
class SimpleDownloadViewSimpleDownload extends JView
{
	/**
	 * display method of Hello view
	 * @return void
	 **/
	function display($tpl = null)
	{
		JToolBarHelper::title(   JText::_( 'SimpleDownload' ) );
		JToolBarHelper::preferences( 'com_simpledownload', '500' );

		$document = & JFactory::getDocument();
		$document->setTitle(JText::_('SimpleDownload'));

		parent::display($tpl);
	}
}