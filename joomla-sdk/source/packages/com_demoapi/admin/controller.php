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
 * @subpackage	com_demoapi
 */
class DemoApiController extends DRController
{
	protected $default_view = 'demoapi';

	public function apply()
	{
		// Initialise variables.
		$app	= JFactory::getApplication();

		// Check for request forgeries.
		if (!JSession::checkToken())
		{
			$app->enqueueMessage(JText::_('JINVALID_TOKEN'));
			$app->redirect('index.php');
		}

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
			$app->redirect('index.php', JText::_('JERROR_ALERTNOAUTHOR'));
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
			$app->setUserState('com_demoapi.config.global.data', $data);

			// Redirect back to the edit screen.
			$app->redirect(JRoute::_('index.php?option=demoapi', false));
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
			$app->setUserState('com_demoapi.config.global.data', $data);

			// Save failed, go back to the screen and display a notice.
			$message = JText::sprintf('JERROR_SAVE_FAILED', $model->getError());
			$app->redirect(JRoute::_('index.php?option=com_demoapi', false), $message, 'error');
			return false;
		}

		// Set the redirect based on the task.
		switch ($this->getTask())
		{
			case 'save':
			case 'apply':
				$message = JText::_('COM_DEMOAPI_SAVE_SUCCESS');
				$app->redirect(JRoute::_('index.php?option=com_demoapi', false), $message);
				break;
		}
	}

	public function remove()
	{
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

		// Get items to remove from the request.
		$cid = JFactory::getApplication()->input->get('cid', array(), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JLog::add(JText::_($this->text_prefix . '_NO_ITEM_SELECTED'), JLog::WARNING, 'jerror');
		}
		else
		{
			// Get the model.
			$model = DRModel::getInstance('Activationcode','DemoapiModel');

			// Remove the items.
			if ($model->delete($cid))
			{
				$this->setMessage(JText::plural('COM_DEMOAPI_N_ITEMS_DELETED', count($cid)));
			}
			else
			{
				$this->setMessage($model->getError());
			}
		}

		$this->setRedirect(JRoute::_('index.php?option=com_demoapi&view=activationcodes', false));
	}

	public function activationcodes()
	{
		$this->setRedirect('index.php?option=com_demoapi&view=activationcodes');
	}

	public function configuration()
	{
		$this->setRedirect('index.php?option=com_demoapi');
	}
}