<?php
/**
 * SimpleDownload default controller
 * 
 * @package    Joomla.Joelrowley.Com
 * @subpackage Components
 * @link http://joomla.joelrowley.com/
 * @license		GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');
jimport( 'joomla.error.error' );
jimport( 'joomla.application.application' );

/**
 * SimpleDownload Component Controller
 *
 * @package		Joomla.Joelrowley.Com
 */
class SimpleDownloadController extends JController
{
	/**
	 * Custom Constructor
	 */
	function __construct( $default = array())
	{
		parent::__construct( $default );
		
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_simpledownload'.DS.'tables');

	}
	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	function display()
	{
		parent::display();
	}
	
	/**
	 * This is the function that initiates the download view.  This is access by
	 * having 'task=download' in the URL.
	 */
	function download()
	{
		$view   =& $this->getView('download', 'raw');
		$view->display();
	}

}
?>
