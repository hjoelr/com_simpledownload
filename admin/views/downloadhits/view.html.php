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
 * HTML View all the SimpleDownload hits.
 *
 * @static
 * @package		Joomla.Joelrowley.Com
 * @since 1.0
 */
class SimpleDownloadViewDownloadHits extends JView
{
	function display( $tpl = null )
	{
		global $mainframe, $option;

		$db			=& JFactory::getDBO();
		$document	=& JFactory::getDocument();
		$model		=& $this->getModel();
		
		
		//add css to document
		$document->addStyleSheet('components'.DS.'com_simpledownload'.DS.'assets'.DS.'css'.DS.'style.css');

		$client = JRequest::getWord( 'filter_client', 'site' );

		$filter_order		= $mainframe->getUserStateFromRequest( "$option.$client.filter_order",		'filter_order',		'h.hit_date',		'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( "$option.$client.filter_order_Dir",	'filter_order_Dir',	'desc',				'word' );
		$filter_status		= $mainframe->getUserStateFromRequest( "$option.$client.filter_status",		'filter_status',		'',					'word' );
		//$filter_type		= $mainframe->getUserStateFromRequest( "$option.$client.filter_type", 		'filter_type',		1,					'cmd' );
		$search				= $mainframe->getUserStateFromRequest( "$option.$client.search",			'search',			'',					'string' );
		$search				= JString::strtolower( $search );

		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart	= $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );

		// get total number of records from DB
		$total = $model->getTotalRecordNumber($model->getWhereAny($search, $filter_status));

		jimport('joomla.html.pagination');
		$pagination = new JPagination( $total, $limitstart, $limit );
		
		// get the records from the DB
		$rows = $model->getAvailableRecords(
							$model->getWhereAny($search, $filter_status),
							$model->getOrderBy($filter_order, $filter_order_Dir),
							$pagination );

		$statusarray	= array (
			JHTML::_('select.option', 'ALL', '- '.JText::_('SELECT_DOWNLOAD_STATUS').' -' ),
  			JHTML::_('select.option', 'DL', JText::_('DOWNLOADED') ),
			JHTML::_('select.option', 'ATT', JText::_('ATTEMPTED_DOWNLOADED') ),
  			JHTML::_('select.option', 'FNF', JText::_('FILE_NOT_FOUND') ),
  			JHTML::_('select.option', 'CE', JText::_('COMPONENT_ERROR') )
		);
		
		$lists['status']	= JHTML::_('select.genericlist', $statusarray, 'filter_status', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $filter_status);

		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;

		// search filter
		$lists['search'] = $search;

		$this->assign('client',		$client);

		$this->assignRef('user',		JFactory::getUser());
		$this->assignRef('lists',		$lists);
		$this->assignRef('items',		$rows);
		$this->assignRef('pagination',	$pagination);

		parent::display($tpl);
	}
}