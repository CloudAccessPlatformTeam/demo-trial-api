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

/**
 * View class for delete token
 *
 * @package     Joomla.Administrator
 * @subpackage  com_demoregister
 * @since       3.0
 */
class DemoRegisterViewActivationcodes extends DRView
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
        	$basePath = __DIR__ . DIRECTORY_SEPARATOR . 'tmpl' . DIRECTORY_SEPARATOR . $jversion;
            // family generic. 3.X, 2.X
            $defaultPath = __DIR__ . DIRECTORY_SEPARATOR . 'tmpl' . DIRECTORY_SEPARATOR . substr($jversion,0,1) . '.X';
            $this->addTemplatePath($defaultPath);
            if (JFolder::exists($basePath)) {
                $this->addTemplatePath($basePath);
            }
			
            JToolBarHelper::custom('configuration','','','Configuration',false);
			JToolBarHelper::deleteList();
			
	        $items		= $this->get('Items');
            $pagination = $this->get('Pagination');
	
			// Check for errors.
			if (count($errors = $this->get('Errors'))) {
				JError::raiseError(500, implode("\n", $errors));
				return false;
			}
			
			$this->assignRef('items',		$items);
            $this->assignRef('pagination',  $pagination);
        }
        
        parent::display($tpl);
    }
}