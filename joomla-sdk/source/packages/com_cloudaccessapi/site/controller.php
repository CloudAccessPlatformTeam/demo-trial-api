<?php
/**
 * @package 	Cloud Panel Component for Joomla!
 * @author 		CloudAccess.net LCC
 * @copyright 	(C) 2010 - CloudAccess.net LCC
 * @license 	GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined( '_JEXEC' ) or die('Restricted access');


class CloudaccessApiController extends DRController
{
    public function save()
    {
        $url = $this->getModel()->save() ? 'index.php?option=com_cloudaccessapi&view=thankyou' : 'index.php' ;
        $this->setRedirect($url);
    }

    public function activate()
    {
        $result = $this->getModel()->activate();
        JFactory::getSession()->set('ca_activate_result', $result);
        $this->setRedirect('index.php?option=com_cloudaccessapi&view=activation');
        $this->redirect();
    }

    public function cron()
    {
        $this->getModel()->cron();
    }
}
