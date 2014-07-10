<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_config
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$app      = JFactory::getApplication();
?>
<script type="text/javascript">
    Joomla.submitbutton = function(task)
    {
    	if (task == 'remove') {
    		if (confirm(<?php echo sprintf('"%s"',JText::_('COM_DEMOAPI_DELETE_CONFIRMATION')); ?>)) {
				Joomla.submitform(task, document.getElementById('adminForm'));
    		}
    	} else {
			Joomla.submitform(task, document.getElementById('adminForm'));
    	}
        
    }
</script>
<form action="<?php echo JRoute::_('index.php?option=com_demoapi&view=activationcodes');?>" id="adminForm" method="post" name="adminForm" autocomplete="off">
<?php if (count($this->items) == 0): ?>
<div class="alert alert-success">
<h4 class="alert-heading">Message</h4>
	<p><?php echo JText::_('COM_DEMOAPI_NO_ACTIVATIONCODES_TO_DELETE'); ?></p>
</div>
<?php else: ?>
<table class="table table-striped" id="articleList">
				<thead>
					<tr>
						<th width="1%" class="hidden-phone">
							<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
						</th>
						<th>
							<?php echo JText::_('COM_DEMOAPI_HEADING_CODE'); ?>
						</th>
						<th>
							<?php echo JText::_('COM_DEMOAPI_HEADING_EMAIL'); ?>
						</th>
						<th width="10%" class="nowrap hidden-phone">
							<?php echo JText::_('COM_DEMOAPI_HEADING_CREATEAD'); ?>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="13">
							<?php echo $this->pagination->getListFooter(); ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
					<?php foreach ($this->items as $i => $item): ?>
					<tr class="row<?php echo $i % 2; ?>">
							<td class="center hidden-phone">
								<?php echo JHtml::_('grid.id', $i, $item->code); ?>
							</td>
							<td>
								<?php  echo $item->code; ?>
							</td>
							<td>
								<?php $params = unserialize($item->params); ?>
								<a href="mailto:<?php  echo $params['email']; ?>;"><?php  echo $params['email']; ?></a>
							</td>
							<td>
								<?php echo $item->created; ?>
							</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
<?php endif; ?>
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="task" value="" />
<?php echo JHtml::_('form.token'); ?>
</form>