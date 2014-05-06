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
<form action="<?php echo JRoute::_('index.php?option=com_demoregister');?>" id="component-form" method="post" name="adminForm" autocomplete="off" class="form-validate form-horizontal">
    <div class="row-fluid">
        <div class="span10">
            <ul class="nav nav-tabs" id="configTabs">
                <?php
                $fieldSets = $this->form->getFieldsets();
                if (!empty($this->list['families'])) {
                    $tab = new stdClass();
                    $tab->name = 'managedatasets';
                    $tab->label = 'Manage Datasets';
                    $fieldSets[$tab->name] = $tab;
                }
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
                unset($fieldSets[$tab->name]);
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
                if (!empty($this->list['families'])):
                    $cids = JComponentHelper::getParams('com_demoregister')->get('cid');
                    ?>
                    <div class="tab-pane" id="<?php echo $tab->name; ?>">
                        <p><?php echo JText::_('COM_DEMOREGISTER_DATASETS_DESC'); ?></p>

                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>
                                    <?php echo JHtml::_('grid.checkall'); ?>
                                </th>
                                <th>Pkg. Name</th>
                                <th>Pkg. Version</th>
                                <th>Joomla Version</th>
                                <th>Pkg. Date</th>
                                <th>ID</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (empty($this->list['datasets'])): ?>
                                <tr>
                                    <td colspan="100%">
                                        Empty List
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($this->list['datasets']['joomla'] as $key => $row): ?>
                                    <?php $checked = false; foreach ($cids as $cid) { if ($cid == sprintf('%s;%s;%s',$row['datasetid'],$row['name'],$row['app_family'])) { $checked = true; break; } } ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" id="cb<?php echo $key; ?>" <?php if ($checked): ?> checked <?php endif; ?> name="<?php echo $this->form->getFormControl(); ?>[cid][]" value="<?php echo $row['datasetid']; ?>;<?php echo $row['name']; ?>;<?php echo $row['app_family']; ?>">
                                        </td>
                                        <td>
                                            <?php echo $row['name']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['version']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['app_family']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['date_added']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['datasetid']; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
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
</script>
