<?php
/**
 * @package Demo Register Component for Joomla!
 * @author CloudAccess.net LCC
 * @copyright (C) 2010 - CloudAccess.net LCC
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');
jimport( 'joomla.filesystem.folder' );

JLoader::import('helpers.api',JPATH_COMPONENT);

/**
 * View class for demo register
 *
 * @package     Joomla.Administrator
 * @subpackage  com_demoapi
 * @since       3.0
 */
class DemoApiViewDemoApi extends DRView
{
    /**
     * Display the view
     *
     * @return  void
     * 
     * @since   3.0
     */
    public function display($tpl = null)
    {
        JToolBarHelper::title(JText::_('COM_DEMOAPI_TITLE'));
        
        $this->loadHelper('toolbar');
        $canDo = DemoApiHelperToolbar::getActions();
        if ($canDo->get('core.admin'))
        {
            $jversion = substr(JVERSION, 0, 3);
        	$basePath = __DIR__ . DIRECTORY_SEPARATOR . 'tmpl' . DIRECTORY_SEPARATOR . $jversion;
            // family generic. 3.X, 2.X
            $defaultPath = __DIR__ . DIRECTORY_SEPARATOR . 'tmpl' . DIRECTORY_SEPARATOR . substr($jversion,0,1) . '.X';
            $this->addTemplatePath($defaultPath);
            if (JFolder::exists($basePath)) {
                $this->addTemplatePath($basePath);
            }

            $actModel = DRModel::getInstance('Activationcodes','DemoapiModel');
            $codes = $actModel->getItems();
			
            if (count($codes)) JToolBarHelper::custom('activationcodes','trash','trash',sprintf('(%s) Activation Codes',count($codes)),false);
			JToolBarHelper::apply('apply');
			
	        $form		= $this->get('Form');
			$component	= $this->get('Component');
	
			// Check for errors.
			if (count($errors = $this->get('Errors'))) {
				JError::raiseError(500, implode("\n", $errors));
				return false;
			}
			
			JFactory::getlanguage()->load('com_content');
			
			$this->assignRef('form',		$form);
			$this->assignRef('component',	$component);

            $this->assignRef('list',		$list);

            JFactory::getLanguage()->load('mod_createdemo', JPATH_SITE);
        }
        
        parent::display($tpl);
    }
}