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

JLoader::import('helpers.api',JPATH_COMPONENT);

/**
 * View class for demo register
 *
 * @package     Joomla.Administrator
 * @subpackage  com_demoregister
 * @since       3.0
 */
class DemoRegisterViewDemoRegister extends DRView
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
        JToolBarHelper::title(JText::_('COM_DEMOREGISTER_TITLE'));
        
        $this->loadHelper('toolbar');
        $canDo = DemoRegisterHelperToolbar::getActions();
        if ($canDo->get('core.admin'))
        {
            $jversion = substr(JVERSION, 0, 3);
        	$basePath = __DIR__. DIRECTORY_SEPARATOR . 'tmpl' . DIRECTORY_SEPARATOR . $jversion;
			$this->addTemplatePath($basePath);
			
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


            $token = HelperDemoRegisterApi::getApiKey();
            if ($token) {
                $list = HelperDemoRegisterApi::call(array('method' => 'ListDatasets', 'p_application' => 'joomla'));
            } else {
                $list = array();
            }

            $this->assignRef('list',		$list);

        }
        
        parent::display($tpl);
    }
}