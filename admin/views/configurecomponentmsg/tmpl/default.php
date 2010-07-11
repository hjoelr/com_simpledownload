<?php
defined('_JEXEC') or die('Restricted access');

JToolBarHelper::title( JText::_( 'SimpleDownload' ), 'simpledownload.png' );
JToolBarHelper::preferences( 'com_simpledownload', '500' );

$link = JRoute::_('index.php?option=com_simpledownload');
?>
<h1><?php echo JText::_('COMPONENT_NOT_CONFIGURED_TITLE');?></h1>
<?php echo JText::_('COMPONENT_NOT_CONFIGURED_MESSAGE'); ?>
<p><a href="<?php echo $link; ?>"><?php echo JText::_('COMPONENT_NOT_CONFIGURED_LINK_MESSAGE'); ?></a></p>