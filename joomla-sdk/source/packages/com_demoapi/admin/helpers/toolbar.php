<?php
/**
 * @package Demo Register Component for Joomla!
 * @author CloudAccess.net LCC
 * @copyright (C) 2010 - CloudAccess.net LCC
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.registry.registry');

/**
 * Demo Register component helper.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_demoapi
 * @since       3.0
 */
class DemoApiHelperToolbar
{
    /**
     * Gets a list of the actions that can be performed.
     *
     * @param   int     The category ID.
     * @param   int     The article ID.
     *
     * @return  JRegistry
     * @since   3.0
     */
    public static function getActions()
    {
        $user	= JFactory::getUser();
        $canDo	= new JRegistry;
        $assetName = 'com_demoapi';

        $actions = array(
            'core.admin',
            'core.manage',
            'core.create',
            'core.edit',
            'core.edit.state',
            'core.delete'
        );

        foreach ($actions as $action) {
            $canDo->def($action, $user->authorise($action, $assetName));
        }
        
        return $canDo;
    }
}