<?php
/**
* @version $Id: view.html.php 10711 2008-08-21 10:09:03Z eddieajau $
* @package Joomla
* @subpackage Poll
* @copyright Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

/**
* HTML View class for the Poll component
*
* @static
* @package Joomla
* @subpackage Poll
* @since 1.0
*/
class DemoRegisterViewThankYou extends DRView
{
    public function display($tpl = null)
    {
        $article = array();

        $aid = JComponentHelper::getParams('com_demoregister')->get('thankyou_aid');
        $engine = JComponentHelper::getParams('com_demoregister')->get('thankyou_engine');

        if (intval($aid) && intval($engine)) {
            $tmpContent = JTable::getInstance('content');
            $tmpContent->load($aid);

            if ($tmpContent->id) {
                $article = $tmpContent;
            }
        }

        $this->assignRef('article', $article);

        return parent::display($tpl);
    }
}