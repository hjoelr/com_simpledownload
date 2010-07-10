<?php       

defined('_JEXEC') or die();

class JTableSimpleDownloadHits extends JTable
{
        var $id = 0;
        var $url = '';
        var $referrer = '';
        var $filepath = '';
        var $downloadstatus = '';
        var $userid = 0;
        var $name = '';
        var $username = '';
        var $ip = null;
        var $hit_date = '0000-00-00 00:00:00';
        
        function __construct(&$db)
        {	
			parent::__construct( '#__simpledownload_hits', 'id', $db );
        }
}
