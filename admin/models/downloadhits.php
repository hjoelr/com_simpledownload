<?php
/**
 * SimpleDownload model for the the download hits view.
 * 
 * @package    Joomla.Joelrowley.Com
 * @subpackage Components
 * @link http://joomla.joelrowley.com/
 * @license		GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.model' );
jimport( 'joomla.error.error' );

/**
 * SimpleDownload Model
 *
 * @package    Joomla.Joelrowley.Com
 * @subpackage Components
 */
class SimpleDownloadModelDownloadHits extends JModel
{
/**
	 * 
	 * @param $where is a SQL string to add as the WHERE in this query.  This
	 * is generally taken from the getWhere() function.
	 * @return a number indicating the total number of file records.
	 */
	function getTotalRecordNumber($where)
	{
		$db =& JFactory::getDBO();
		
		$query = 'SELECT COUNT(*)'
			. ' FROM #__simpledownload_hits AS h'
			. $where
			;
		$db->setQuery( $query );
		$total = $db->loadResult();
		
		if ($db->getErrorNum()) {
			return JError::raiseError( 500, $db->stderr() );
		}
		
		return $total;
	}
	
	function getWhereAny($search, $filter_status)
	{
		if ( $search ) {
			$db =& JFactory::getDBO();
			
			$searchCleaned = $db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
			
			$where[] = '(LOWER( h.fileid ) LIKE '.$searchCleaned
						. ' OR LOWER( h.referrer ) LIKE '.$searchCleaned
						. ' OR LOWER( h.filepath ) LIKE '.$searchCleaned
						. ' OR LOWER( h.name ) LIKE '.$searchCleaned
						. ' OR LOWER( h.username ) LIKE '.$searchCleaned.')';
		}
		if ( $filter_status && $filter_status != 'ALL' ) {
			switch ($filter_status) {
				case 'DL':
					$where[] = 'h.downloadstatus = \'DL\'';
					break;
				case 'ATT':
					$where[] = 'h.downloadstatus = \'ATT\'';
					break;
				case 'FNF':
					$where[] = 'h.downloadstatus = \'FNF\'';
					break;
				case 'CE':
					$where[] = 'h.downloadstatus = \'CE\'';
					break;
			}
		}

		$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
		
		return $where;
	}
	
	function getWhereFilepath($search)
	{
		if ( $search ) {
			$db =& JFactory::getDBO();
			
			$searchCleaned = $db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
			
			$where[] = '(LOWER( h.filepath ) LIKE '.$searchCleaned.')';
		}

		$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
		
		return $where;
	}
	
	function getWhereFileid($search)
	{
		if ( $search ) {
			$db =& JFactory::getDBO();
			
			$searchCleaned = $db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
			
			$where[] = '(LOWER( h.fileid ) LIKE '.$searchCleaned.')';
		}

		$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
		
		return $where;
	}
	
	function getOrderBy($filter_order, $filter_order_Dir)
	{
		$orderby = ' ORDER BY '. $filter_order . ' '. $filter_order_Dir;
		
		return $orderby;
	}
	
	function getAvailableRecords($where, $orderby, $pagination)
	{
		$db =& JFactory::getDBO();
		
		$query = 'SELECT h.*'
			. ' FROM #__simpledownload_hits as h'
			. $where
			. ' GROUP BY h.id'
			. $orderby
			;
		
		$db->setQuery( $query, $pagination->limitstart, $pagination->limit );
		$rows = $db->loadObjectList();
		if ($db->getErrorNum()) {
			return JError::raiseError( 500, $db->stderr() );
		}
		
		return $rows;
	}
}