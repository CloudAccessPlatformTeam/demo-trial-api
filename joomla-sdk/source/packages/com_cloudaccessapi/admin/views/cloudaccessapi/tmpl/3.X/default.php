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
$template = $app->getTemplate();

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
?>
<script type="text/javascript">
    Joomla.submitbutton = function(task)
    {
        if (document.formvalidator.isValid(document.id('component-form'))) {
            Joomla.submitform(task, document.getElementById('component-form'));
        }
    }
</script>
<form action="<?php echo JRoute::_('index.php?option=com_cloudaccessapi');?>" id="component-form" method="post" name="adminForm" autocomplete="off" class="form-validate form-horizontal">
    <div class="row-fluid">
        <div class="span10">
            <p><?php echo JText::_('MOD_CREATECLOUDACCESSDEMO_CONTENT_DESCRIPTION'); ?></p>
            <ul class="nav nav-tabs" id="configTabs">
                <?php
                $fieldSets = $this->form->getFieldsets();
                foreach ($fieldSets as $name => $fieldSet) :
                    $label = empty($fieldSet->label) ? 'COM_CONFIG_'.$name.'_FIELDSET_LABEL' : $fieldSet->label;
                    ?>
                    <li><a href="#<?php echo $name;?>" data-toggle="tab"><?php echo  JText::_($label);?></a></li>
                <?php
                endforeach;
                ?>
            </ul>
            <div class="tab-content">
                <?php
                foreach ($fieldSets as $name => $fieldSet) :
                    ?>
                    <div class="tab-pane" id="<?php echo $name;?>">
                        <?php
                        if (isset($fieldSet->description) && !empty($fieldSet->description)) :
                            echo '<p class="tab-description">'.JText::_($fieldSet->description).'</p>';
                        endif;
                        foreach ($this->form->getFieldset($name) as $field):
                            ?>
                            <div class="control-group">
                                <?php if (!$field->hidden && $name != "permissions") : ?>
                                    <div class="control-label">
                                        <?php echo $field->label; ?>
                                    </div>
                                <?php endif; ?>
                                <div class="<?php if ($name != "permissions") : ?>controls<?php endif; ?>">
                                    <?php echo $field->input; ?>
                                </div>
                            </div>
                        <?php
                        endforeach;
                        ?>

                    </div>
                <?php
                endforeach;
                ?>
            </div>
        </div>
    </div>
    <div>
        <input type="hidden" name="id" value="<?php echo $this->component->id;?>" />
        <input type="hidden" name="component" value="<?php echo $this->component->option;?>" />
        <input type="hidden" name="return" value="<?php echo $this->return; ?>" />
        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>
<script type="text/javascript">
    jQuery('#configTabs a:first').tab('show'); // Select first tab
    jQuery('#thankyou').find('.control-group:last').css('display','none');
    jQuery('#jform_thankyou_engine').change(function(){
        if (this.value == 1) {
            jQuery('#thankyou').find('.control-group:last').css('display','block');
        } else {
            jQuery('#thankyou').find('.control-group:last').css('display','none');
        }
    });
    jQuery('#template').find('.control-group:nth-child(5)').css('display','none');
    jQuery('#jform_template_engine').change(function(){
        if (this.value == 1) {
            jQuery('#template').find('.control-group:last').css('display','none');
            jQuery('#template').find('.control-group:nth-child(5)').css('display','block');
        } else {
            jQuery('#template').find('.control-group:last').css('display','block');
            jQuery('#template').find('.control-group:nth-child(5)').css('display','none');
        }
    });
    if (jQuery.isFunction(jQuery.fn.fire)) {
        jQuery('#jform_thankyou_engine').fire('click');
        jQuery('#jform_template_engine').fire('click');
    } else if (jQuery.isFunction(jQuery.fn.trigger)) {
        jQuery('#jform_thankyou_engine').trigger('click');
        jQuery('#jform_thankyou_engine').trigger('click');
    } else {
        alert('Please update your jQuery!');
    }
</script>
