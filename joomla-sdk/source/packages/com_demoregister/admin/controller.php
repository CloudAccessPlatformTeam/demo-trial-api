<?php
/**
* @package Cloud Panel Component for Joomla!
* @author CloudAccess.net LCC
* @copyright (C) 2010 - CloudAccess.net LCC
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined( '_JEXEC' ) or die('Restricted access');

/**
 * Component Base Controller
 *
 * @package		Joomla.Administrator
 * @subpackage	com_demoregister
 */
class DemoRegisterController extends DRController
{
	public function apply()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		// Initialise variables.
		$app	= JFactory::getApplication();
		$model	= $this->getModel();
		$form	= $model->getForm();
		$data	= JRequest::getVar('jform', array(), 'post', 'array');
		$id		= JRequest::getInt('id');
		$option	= JRequest::getCmd('component');
		
		// Set FTP credentials, if given.
		JClientHelper::setCredentialsFromRequest('ftp');

		// Check if the user is authorized to do this.
		if (!JFactory::getUser()->authorise('core.admin', $option))
		{
			JFactory::getApplication()->redirect('index.php', JText::_('JERROR_ALERTNOAUTHOR'));
			return;
		}

		// Validate the posted data.
		$return = $model->validate($form, $data);

		// Check for validation errors.
		if ($return === false) {
			// Get the validation messages.
			$errors	= $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if ($errors[$i] instanceof Exception) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				} else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			// Save the data in the session.
			$app->setUserState('com_demoregister.config.global.data', $data);

			// Redirect back to the edit screen.
			$this->setRedirect(JRoute::_('index.php?option=demoregister', false));
			return false;
		}

		// Attempt to save the configuration.
		$data	= array(
					'params'	=> $return,
					'id'		=> $id,
					'option'	=> $option
					);
		$return = $model->save($data);

		// Check the return value.
		if ($return === false)
		{
			// Save the data in the session.
			$app->setUserState('com_demoregister.config.global.data', $data);

			// Save failed, go back to the screen and display a notice.
			$message = JText::sprintf('JERROR_SAVE_FAILED', $model->getError());
			$this->setRedirect('index.php?option=com_demoregister', $message, 'error');
			return false;
		}

		// Set the redirect based on the task.
		switch ($this->getTask())
		{
			case 'save':
			case 'apply':
				$message = JText::_('COM_DEMOREGISTER_SAVE_SUCCESS');
				$this->setRedirect('index.php?option=com_demoregister', $message);
				break;
		}

		return true;
	}
}