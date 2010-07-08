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

/**
 * Hello World Component Controller
 *
 * @package		HelloWorld
 */
class SimpleDownloadController extends JController
{
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
	 * Method to create the table for logging downloads if it doesn't already exist
	 *
	 * @access	public
	 */
	function createLogTable()
	{
		$db =& JFactory::getDBO();

		$query = "CREATE TABLE IF NOT EXISTS `#__simple_download_hits` (
             `id`       int(11) unsigned NOT NULL auto_increment
          ,  `url`      varchar(255)     NOT NULL default ''
          ,  `userid`   int(11) unsigned NOT NULL default 0
          ,  `name`     varchar(255)     NOT NULL default ''
          ,  `username` varchar(255)     NOT NULL default ''
          ,  `filepath` varchar(255)     NOT NULL default ''
          ,  `ip`       varchar(15)      NOT NULL default ''
          ,  `hit_date` datetime         NOT NULL default '0000-00-00 00:00:00'
          ,  PRIMARY KEY  (`id`)
          ,  KEY idx_url      (`url`     )
          ,  KEY idx_userid   (`userid`  )
          ,  KEY idx_name     (`name`    )
          ,  KEY idx_username (`username`)
          ,  KEY idx_filepath (`filepath`)
          ,  KEY idx_ip       (`ip`      )
          ,  KEY idx_hit_date (`hit_date`)
          ) TYPE=MyISAM; "
          ;

		$db->setQuery($query);

		if (!$db->query()) {
			return false;
		} else {
			return true;
		}
	}
	
/**
	 * Method to initiate the download of a file.
	 *
	 * @access public
	 */
	function download()
	{
		include_once( JPATH_COMPONENT.DS.'helpers'.DS.'download.php' );

		$params	=& JComponentHelper::getParams( 'com_simpledownload' );

		$view	=& $this->getView('error', 'html');

		$base_download_path	= $params->get('basedownloadpath');
		$cipherenabled		= $params->get('cipherenabled');
		$log_downloads		= $params->get('log_downloads');
		$cipherfile			= $params->get('cipherfile');
		$decipherfunction	= $params->get('decipherfunction');

		$encryptedPath		= JRequest::getVar('fileid');
		$decryptedPath		= '';

		if ($cipherenabled == "1") {
			// text should be encrypted and needs to be decrypted
			if (!($cipherfile != "" && file_exists($cipherfile) && $decipherfunction != "")) {
				$view->assignRef('page_title', $params->get('title_badcomponentconfiguration'));
				$view->assignRef('msg', $params->get('msg_badcomponentconfiguration'));
				$view->display();
				return;
			} else {
				include_once($cipherfile);
				$decryptedPath = $decipherfunction($encryptedPath);
			}
		} else {
			// encryption is disabled, so encryptedPath should really already be decrypted.
			$decryptedPath = $encryptedPath;
		}

		$patterns[0] = '/[^[:print:]]+/'; // remove non-printable characters
		$patterns[1] = '/[ \t]+$/';  // remove whitespace at end of string
		$patterns[2] = '/^[ \t]+/';  // remove whitespace at beginning of string
		$patterns[4] = '/^[\\\\|\/]+/'; // remove leading slash if one exists
		$patterns[5] = '/^[\.\.\/|\.\.\\\\]+/'; // remove all ../ and all ..\ if any exist
												// from the beginning of the string.

		$cleanedPathOld = "";
		$cleanedPath = "";

		do {
			$cleanedPathOld = $cleanedPath;
			$cleanedPath = preg_replace($patterns, array(), $decryptedPath);
		} while (strcasecmp($cleanedPathOld, $cleanedPath)); // be sure all permutations of bad items are removed.

		if (!preg_match('/^'.$base_download_path.'/', $cleanedPath)) {
			$cleanedPath = $base_download_path.DS.$cleanedPath; // add base path if it doesn't already exist in the file path.
		}


		if ($log_downloads == "1") {
			// create table if it doesn't exist
			if ($this->createLogTable()) {
				
				//   -   -   -   -   -
				$a_user = &JFactory::getUser();

				$db = &JFactory::getDBO();

				$query =
				'INSERT INTO #__simple_download_hits
				(  url
				, userid
				, name
				, username
				, filepath
				, ip
				, hit_date
				)
				VALUES
				("' . $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"] . '"
				, '  . $a_user->id . '
				, "' . $a_user->name . '"
				, "' . $a_user->username . '"
				, "' . $cleanedPath . '"
				, "' . $_SERVER['REMOTE_ADDR'] . '"
				, now()
				)';

				$db->setQuery($query);
				if (!$db->query()) {
					// TBD:  How to handle insert error?
				}

				//   -   -   -   -   -

			} else {
				// TBD:  How to handle error if log table not created?
			}

		}
		
		$return = download_file($cleanedPath);

		if ($return != 0) { // an error occurred while downloading

			switch ($return) {
				case 404: // file not found
					$view->assignRef('page_title', $params->get('title_filenotfound'));
					$view->assignRef('msg', $params->get('msg_filenotfound'));
					$view->display();
					break;
				default:
					$view->assignRef('page_title', 'Component Error');
					$view->assignRef('msg', 'An unknown error occurred.  Sorry for the inconvenience.');
					$view->display();
					break;
			}
		}
	}
	
	/**
	 * Method to initiate the download of a file.
	 * 
	 * @access public
	 */
	/*function download()
	{
		include_once( JPATH_COMPONENT.DS.'helpers'.DS.'download.php' );
		
		$params	=& JComponentHelper::getParams( 'com_simpledownload' );
		
		$view	=& $this->getView('error', 'html');
		
		$base_download_path	= $params->get('basedownloadpath');
		$cipherenabled		= $params->get('cipherenabled');
		$cipherfile			= $params->get('cipherfile');
		$decipherfunction	= $params->get('decipherfunction');
		
		$encryptedPath		= JRequest::getVar('fileid');
		$decryptedPath		= '';
		
		if ($cipherenabled == "1") {
			// text should be encrypted and needs to be decrypted
			if (!($cipherfile != "" && file_exists($cipherfile) && $decipherfunction != "")) {
				$view->assignRef('page_title', $params->get('title_badcomponentconfiguration'));
				$view->assignRef('msg', $params->get('msg_badcomponentconfiguration'));
				$view->display();
				return;
			} else {
				include_once($cipherfile);
				$decryptedPath = $decipherfunction($encryptedPath);
			}
		} else {
			// encryption is disabled, so encryptedPath should really already be decrypted.
			$decryptedPath = $encryptedPath;
		}
		
		$patterns[0] = '/[^[:print:]]+/'; // remove non-printable characters
		$patterns[1] = '/[ \t]+$/';  // remove whitespace at end of string
		$patterns[2] = '/^[ \t]+/';  // remove whitespace at beginning of string
		$patterns[4] = '/^[\\\\|\/]+/'; // remove leading slash if one exists
		$patterns[5] = '/^[\.\.\/|\.\.\\\\]+/'; // remove all ../ and all ..\ if any exist
												// from the beginning of the string.
		
		$cleanedPathOld = "";
		$cleanedPath = "";
		
		do {
			$cleanedPathOld = $cleanedPath;
			$cleanedPath = preg_replace($patterns, array(), $decryptedPath);
		} while (strcasecmp($cleanedPathOld, $cleanedPath)); // be sure all permutations of bad items are removed.
		
		if (!preg_match('/^'.$base_download_path.'/', $cleanedPath)) {
			$cleanedPath = $base_download_path.DS.$cleanedPath; // add base path if it doesn't already exist in the file path.
		}
		
		$return = download_file($cleanedPath);
		
		if ($return != 0) { // an error occurred while downloading
			
			switch ($return) {
				case 404: // file not found
					$view->assignRef('page_title', $params->get('title_filenotfound'));
					$view->assignRef('msg', $params->get('msg_filenotfound'));
					$view->display();
					break;
				default:
					$view->assignRef('page_title', 'Component Error');
					$view->assignRef('msg', 'An unknown error occurred.  Sorry for the inconvenience.');
					$view->display();
					break;
			}
		}
	}*/

}
?>
