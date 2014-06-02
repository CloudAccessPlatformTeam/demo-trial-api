<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_demoregister
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

$template = JFactory::getApplication()->getTemplate();

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (document.formvalidator.isValid(document.id('component-form'))) {
			Joomla.submitform(task, document.getElementById('component-form'));
		}
	}
    window.addEvent('domready', function() {
        $('jform_thankyou_engine').getParent('li').getNext().setStyle('display','none');
        $('jform_body_content').getParent('li').setStyle('display','none');
        $('jform_body_aid_name').getParent('li').setStyle('display','none');
        $('jform_thankyou_engine').addEvent('change', function(){
            if (this.value == 1) {
                $('jform_thankyou_engine').getParent('li').getNext().setStyle('display','block');
            } else {
                $('jform_thankyou_engine').getParent('li').getNext().setStyle('display','none');
            }
        });
        $('jform_template_engine').addEvent('change', function(){
            if (this.value == 1) {
                $('jform_body_content').getParent('li').setStyle('display','none');
                $('jform_body_aid_name').getParent('li').setStyle('display','block');
            } else {
                $('jform_body_content').getParent('li').setStyle('display','block');
                $('jform_body_aid_name').getParent('li').setStyle('display','none');
            }
        })
    });
    window.onload=function(){
        $('jform_thankyou_engine').fireEvent('change');
        $('jform_template_engine').fireEvent('change');
    }
</script>
<form action="<?php echo JRoute::_('index.php?option=com_demoregister');?>" id="component-form" method="post" name="adminForm" autocomplete="off" class="form-validate">
	<?php
	echo JHtml::_('tabs.start', 'config-tabs-'.$this->component->option.'_configuration', array('useCookie'=>1));
		$fieldSets = $this->form->getFieldsets();
		foreach ($fieldSets as $name => $fieldSet) :
			$label = empty($fieldSet->label) ? 'COM_CONFIG_'.$name.'_FIELDSET_LABEL' : $fieldSet->label;
			echo JHtml::_('tabs.panel', JText::_($label), 'publishing-details');
			if (isset($fieldSet->description) && !empty($fieldSet->description)) :
				echo '<p class="tab-description">'.JText::_($fieldSet->description).'</p>';
			endif;
	?>
			<ul class="config-option-list">
			<?php
			foreach ($this->form->getFieldset($name) as $field):
			?>
				<li>
				<?php if (!$field->hidden) : ?>
				<?php echo $field->label; ?>
				<?php endif; ?>
				<?php echo $field->input; ?>
				</li>
			<?php
			endforeach;
			?>
			</ul>


	<div class="clr"></div>
	<?php
		endforeach;
	echo JHtml::_('tabs.end');
	?>
	<div>
		<input type="hidden" name="id" value="<?php echo $this->component->id;?>" />
		<input type="hidden" name="component" value="<?php echo $this->component->option;?>" />
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>