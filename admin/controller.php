<?php
/**
 * SimpleDownload default controller
 * 
 * @package    Joomla.Joelrowley.Com
 * @subpackage Components
 * @link http://joomla.joelrowley.com/
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');
jimport( 'joomla.error.error' );

/**
 * SimpleDownload Component backend Controller
 *
 * @package    Joomla.Joelrowley.Com
 * @subpackage Components
 */
class SimpleDownloadController extends JController
{
	/**
	 * Custom Constructor
	 */
	function __construct( $default = array())
	{
		parent::__construct( $default );

//		$this->registerTask( 'apply', 		'save');
//		$this->registerTask( 'unpublish', 	'publish');
//		$this->registerTask( 'edit' , 		'display' );
//		$this->registerTask( 'add' , 		'display' );
//		$this->registerTask( 'remove',		'delete');
//		$this->registerTask( 'cancel',		'cancel');
		
		
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_simpledownload'.DS.'tables');

	}
	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	function display()
	{
		$params	=& JComponentHelper::getParams( 'com_simpledownload' );

		$base_download_path	= $params->get('basedownloadpath', '');
		
		if ($base_download_path == '') { // component is not configured
			JRequest::setVar( 'view', 'configurecomponentmsg' );
		} else {
			switch($this->getTask())
			{
				default:
					JRequest::setVar( 'view', 'downloadhits' );
					break;
			}
		}
		
		parent::display();
	}
	
	function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'INVALID_TOKEN' );

		$db   =& JFactory::getDBO();
		$row  =& JTable::getInstance('simpledownloadhits');
		$task = $this->getTask();
		
		$cid     = JRequest::getVar( 'cid', array(), 'post', 'array' );
		
		if (count($cid) == 0) {
			$msg = JText::_( 'NO_ITEM_WAS_SELECTED' );
			$this->setRedirect( 'index.php?option=com_simpledownload', $msg );
			return;
		}
		
		foreach ($cid as $id) {
			if ($row->load($id)) {
				if (!$row->delete( $id ) ) {
					JError::raiseError(500, $row->getError() );
				}
			}
		}
		
		
		$msg = JText::_( 'ITEMS_SUCCESSFULLY_DELETED' );
		$this->setRedirect( 'index.php?option=com_simpledownload', $msg );
	}
}