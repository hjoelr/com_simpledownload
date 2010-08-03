<?php
/**
 * @package    Joomla.Joelrowley.Com
 * @subpackage Components
 * @link http://joomla.joelrowley.com/
 * @license		GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * This function does the action of forcing the browser to download the
 * specified file as well as feeds the bits of the file to the browser.
 * 
 * @param string $filePath is the path to the file that we want to download.
 * @return int Error code if one exists.  Return of 0 indicates no error.
 */
function download_file($filePath) {
	
	$allowed_ext = array (

		  // archives
		  'zip' => 'application/zip',
		
		  // documents
		  'pdf' => 'application/pdf',
		  'doc' => 'application/msword',
		  'xls' => 'application/vnd.ms-excel',
		  'ppt' => 'application/vnd.ms-powerpoint',
		  
		  // executables
		  'exe' => 'application/octet-stream',
		
		  // images
		  'gif' => 'image/gif',
		  'png' => 'image/png',
		  'jpg' => 'image/jpeg',
		  'jpeg' => 'image/jpeg',
		
		  // audio
		  'mp3' => 'audio/mpeg',
		  'wav' => 'audio/x-wav',
		
		  // video
		  'mpeg' => 'video/mpeg',
		  'mpg' => 'video/mpeg',
		  'mpe' => 'video/mpeg',
		  'mov' => 'video/quicktime',
		  'avi' => 'video/x-msvideo'
		);
	
	$toReturn = 0;
	
	if ($filePath != "" && file_exists( $filePath )) {
		$file_extension = strtolower(substr(strrchr($filePath,"."),1));
		
		// get mime type of the file.
		$ctype = '';
		if ($allowed_ext[$file_extension] == '') {
			// mime type is not set, get from server settings
			if (function_exists('mime_content_type')) {
				$ctype = mime_content_type($file_path);
			}
			else if (function_exists('finfo_file')) {
				$finfo = finfo_open(FILEINFO_MIME); // return mime type
				$ctype = finfo_file($finfo, $file_path);
				finfo_close($finfo);  
			}
			if ($ctype == '') {
				$ctype = "application/force-download";
			}
		}
		else {
			// get mime type defined by admin
			$ctype = $allowed_ext[$file_extension];
		}
		
		$oldPath = getcwd(); // get current working directory
		
		changeDirectory(getPathArray($filePath));
		$filename = getFilename($filePath);
		
		//echo $filename;
		
		// Tell the browser the mime type of the file to be downloaded.
		header('Content-type: ' . $ctype);
	
		// Tell the browser what to call the file.
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header("Content-Length: " . filesize($filename));
		
		ob_clean();
    	flush();
		
    	$bytesSent = readfile_chunked($filename);
		
		$toReturn = 0;
		
		changeDirectory(getPathArray($oldPath)); // change back to original directory.
		
		//exit;
	} else {
		$toReturn = 404; // file not found
	}
	
	return $toReturn;
}

/**
 * A helper function that changes the current directory for the PHP engine
 * to the directory that the path indicates.
 * 
 * @param $pathArray string array containing directory names to change to in 
 * sequence from the first element in the array to the last.
 */
function changeDirectory($pathArray) {
	foreach ($pathArray as $directory) {
		chdir($directory);
	}
}

/**
 * Take a string representation of a file path and return just the filename from
 * the path.
 * 
 * @param $path string representation of a file path (eg. 'path/to/file.ext').
 * @return string representation of the filename from the path.
 */
function getFilename($path) {
	$splitPath = spliti('[/\]', $path);
	
	return $splitPath[count($splitPath)-1]; // return the filename only
}

/**
 * Takes a string representation of a file path (eg. 'path/to/file.ext') and
 * returns that path minus the filename as an array (eg. array('path', 'to') ).
 * 
 * @param $mixedPath a string representation of a file path.
 * @return array of strings representing directory paths.
 */
function getPathArray($mixedPath) {
	$toReturn = array();
	
	$matchResult = preg_match('/^[\\\\|\/]+/', $mixedPath);
	
	if ($matchResult != 0) {
		$toReturn[] = DS; // add / or \ to beginning of path if that is intended.
	}
	
	$splitPath = spliti('[/\]', $mixedPath);
	
	for ($i = 0; $i < count($splitPath)-1; ++$i) {
		$toReturn[] = $splitPath[$i]; // add all the directories minus the filename.
	}
	
	return $toReturn;
}

/**
 * Takes a file to download and 'streams' it to the browser.  This function
 * takes the place of readfile() and removes the 'memory_limit' error that was
 * happening with readfile().
 * 
 * @credits chrisputnam and flobee in the comments here: http://php.net/manual/en/function.readfile.php
 * 
 * @param $filename the name of the file to download.
 * @param $retbytes indicates whether or not to return how many bytes were delivered.
 */
function readfile_chunked($filename,$retbytes=true) {
	$chunksize = 1*(1024*1024); // how many bytes per chunk
	$buffer = '';
	$cnt =0;
	
	$handle = fopen($filename, 'rb');
	if ($handle === false) {
		return false;
	}
	while (!feof($handle)) {
		$buffer = fread($handle, $chunksize);
		echo $buffer;
		ob_flush();
		flush();
		if ($retbytes) {
			$cnt += strlen($buffer);
		}
	}
	
	$status = fclose($handle);
	
	if ($retbytes && $status) {
		return $cnt; // return num. bytes delivered like readfile() does.
	}
	
	return $status;

}
?>
