<?php
/**
 * View for downloading files for the Media Syndicator Component.
 * 
 * @package    Joomla.Joelrowley.Com
 * @subpackage Components
 * @link http://joomla.joelrowley.com/
 * @license		GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');
jimport( 'joomla.application.application' );

class SimpleDownloadViewDownload extends JView
{
	function display($tpl = null)
	{
		$params	=& JComponentHelper::getParams( 'com_simpledownload' );

		$base_download_path	= $params->get('basedownloadpath', '');
		$cipherenabled		= $params->get('cipherenabled');
		$log_downloads		= $params->get('log_downloads') == '1';
		$cipherfile			= $params->get('cipherfile');
		$decipherfunction	= $params->get('decipherfunction');

		$encryptedPath		= JRequest::getVar('fileid');
		$decryptedPath		= '';
		
		if ($base_download_path == '') {
			
			JApplication::redirect(JRoute::_('index.php?option=com_simpledownload&view=error&err=' . 412));
			
			return;
		}

		if ($cipherenabled == "1") {
			// text should be encrypted and needs to be decrypted
			if (!($cipherfile != "" && file_exists($cipherfile) && $decipherfunction != "")) {
				
				JApplication::redirect(JRoute::_('index.php?option=com_simpledownload&view=error&err=' . 412));
				
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

		if (!preg_match('/^'.preg_quote($base_download_path).'/', $cleanedPath)) {
			$cleanedPath = $base_download_path.DS.$cleanedPath; // add base path if it doesn't already exist in the file path.
		}
		
		if (!preg_match('%^([\d\w\-.\\\\ /&!]+)$%', $decryptedPath)) { 	// minimal attempt to prevent
																		// invalid characters in file path
			JApplication::redirect(JRoute::_('index.php?option=com_simpledownload&view=error&err=' . 404));
		}
		
		$row =& JTable::getInstance('simpledownloadhits');
		
		if ($log_downloads) {
			
			$a_user = &JFactory::getUser();
			
			$row->fileid = JRequest::getVar('fileid');
			$row->referrer = JRequest::getVar('HTTP_REFERER', '', 'SERVER');
			$row->userid = $a_user->id;
			$row->name = $a_user->name;
			$row->username = $a_user->username;
			$row->filepath = $cleanedPath;
			$row->ip = JRequest::getVar('REMOTE_ADDR', '0.0.0.0', 'SERVER');
			$row->hit_date = date('Y-m-d H:i:s');
			$row->downloadstatus = 'ATT';
			
			if (!$row->store()) {
				return JError::raiseWarning( 500, $row->getError() );
			}

		}
		
		include_once( JPATH_COMPONENT.DS.'helpers'.DS.'download.php' );
		
		$return = download_file($cleanedPath);

		if ($return != 0) { // an error occurred while downloading

			switch ($return) {
				case 404: // file not found
					
					if ($log_downloads) {
						$row->downloadstatus = 'FNF';
						
						if (!$row->store()) {
							return JError::raiseWarning( 500, $row->getError() );
						}
					}
					
					JApplication::redirect(JRoute::_('index.php?option=com_simpledownload&view=error&err=' . 404));
					
					break;
				default:
					
					if ($log_downloads) {
						$row->downloadstatus = 'CE';
						
						if (!$row->store()) {
							return JError::raiseWarning( 500, $row->getError() );
						}
					}
					
					JApplication::redirect(JRoute::_('index.php?option=com_simpledownload&view=error&err=' . 500));
					
					break;
			}
		} else { // successful download.  This section doesn't get hit after readfile_chunked was introduced.
			if ($log_downloads) {
				
				$row->downloadstatus = 'DL';
				
				if (!$row->store()) {
					return JError::raiseWarning( 500, $row->getError() );
				}
			}
		}
	}
}