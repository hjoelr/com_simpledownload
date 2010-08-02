<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('behavior.tooltip'); ?>

<?php
	JToolBarHelper::title( JText::_( 'SimpleDownload' ), 'simpledownload.png' );
	JToolBarHelper::deleteList( JText::_( 'ASK_IF_SURE_TO_DELETE_ITEMS' ) );
	JToolBarHelper::preferences( 'com_simpledownload', '500' );
	
	$rows =& $this->items;

?>

<form action="index.php" method="post" name="adminForm">
<table>
	<tr>
		<td align="left" width="100%">
			<?php echo JText::_( 'FILTER' ); ?>:
			<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
			<button onclick="this.form.submit();"><?php echo JText::_( 'GO' ); ?></button>
			<button onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'RESET' ); ?></button>
		</td>
		<td nowrap="nowrap">
			<?php
				echo $this->lists['status'];
			?>
		</td>
	</tr>
</table>

<table class="adminlist">
<thead>
	<tr>
		<th width="20">
			<?php echo JText::_( 'NUMBER_ABBREVIATION' ); ?>
		</th>
		<th width="20">
			<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows );?>);" />
		</th>
		<th class="url">
			<?php echo JHTML::_('grid.sort',   'FILE_ID', 'h.fileid', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
		</th>
		<th class="referrer">
			<?php echo JHTML::_('grid.sort',   'REFERRING_PAGE', 'h.referrer', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
		</th>
		<th class="filepath">
			<?php echo JHTML::_('grid.sort',   'FILE_PATH', 'h.filepath', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
		</th>
		<th class="downloadstatus">
			<?php echo JHTML::_('grid.sort',   'DOWNLOAD_STATUS', 'h.downloadstatus', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
		</th>
		<th class="ip">
			<?php echo JHTML::_('grid.sort',   'IP_ADDRESS', 'h.ip', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
		</th>
		<th class="downloadtime" width="20">
			<?php echo JHTML::_('grid.sort',   'DOWNLOAD_TIME', 'h.hit_date', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
		</th>
	</tr>
</thead>
<tfoot>
	<tr>
		<td colspan="9">
			<?php echo $this->pagination->getListFooter(); ?>
		</td>
	</tr>
</tfoot>
<tbody>
<?php
	$k = 0;
	for ($i=0, $n=count( $rows ); $i < $n; $i++) {
	$row 	= $rows[$i];

?>
	<tr class="<?php echo "row$k"; ?>">
		<td align="right">
			<?php echo $this->pagination->getRowOffset( $i ); ?>
		</td>
		<td>
			<input type="checkbox" onclick="isChecked(this.checked);" value="<?php echo $row->id; ?>" name="cid[]" id="cb<?php echo $i; ?>">
		</td>
		<td>
			<?php echo htmlentities($row->fileid); ?>
		</td>
		<td align="center">
			<?php echo htmlentities($row->referrer);?>
		</td>
		<td align="center">
			<?php echo htmlentities($row->filepath);?>
		</td>
		<td align="center">
			<?php
			switch ($row->downloadstatus) {
				case 'DL':
					echo JText::_( 'DOWNLOADED' );
					break;
				case 'ATT':
					echo JText::_( 'ATTEMPTED_DOWNLOADED' );
					break;
				case 'FNF':
					echo JText::_( 'FILE_NOT_FOUND' );
					break;
				case 'CE':
					echo JText::_( 'COMPONENT_ERROR' );
					break;
				default:
					echo htmlentities($row->downloadstatus);
					break;
			}
			?>
		</td>
		<td align="center">
			<?php echo htmlentities($row->ip);?>
		</td>
		<td align="center">
			<?php echo htmlentities($row->hit_date);?>
		</td>
	</tr>
	<?php
		$k = 1 - $k;
	}
	?>
</tbody>
</table>

	<input type="hidden" name="option" value="com_simpledownload" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="filter_client" value="<?php echo $this->client;?>" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>