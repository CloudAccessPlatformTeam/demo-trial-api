<?php
/**
 * @package 	Cloud Panel Component for Joomla!
 * @author 		CloudAccess.net LCC
 * @copyright 	(C) 2010 - CloudAccess.net LCC
 * @license 	GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined( '_JEXEC' ) or die('Restricted access');


class DemoApiController extends DRController
{
    public function save()
    {
        $url = $this->getModel()->save() ? 'index.php?option=com_demoapi&view=thankyou' : 'index.php' ;
        $this->setRedirect($url);
    }

    public function activate()
    {
        $this->getModel()->activate();
        $this->setRedirect('index.php?option=com_demoapi&view=activation');
        $this->redirect();
    }

    public function cron()
    {
        $this->getModel()->cron();
    }
}