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
		parent::display();
	}
}