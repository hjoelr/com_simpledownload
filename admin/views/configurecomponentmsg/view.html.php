<?php
/**
* @version		$Id: view.html.php 1 2010-07-10 10:30:17Z joel $
* @package		Joomla.Joelrowley.Com
* @copyright	Copyright (C) 2010 Joel Rowley. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View that displays a message saying that the component needs 
 * to be configured.
 *
 * @static
 * @package		Joomla.Joelrowley.Com
 * @since 1.0
 */
class SimpleDownloadViewConfigureComponentMsg extends JView
{
	function display( $tpl = null )
	{
		$document	=& JFactory::getDocument();
		//add css to document
		$document->addStyleSheet('components'.DS.'com_simpledownload'.DS.'assets'.DS.'css'.DS.'style.css');
		
		parent::display($tpl);
	}
}