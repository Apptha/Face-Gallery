<?php
/*
 *********************************************************************
 * @name            FaceGallery
 * @version         1.0: view/settings/view.html.php$
 * @since           Joomla 3.0
 * @package         apptha
 * @subpackage      com_facegallery
 * @author          Apptha - http://www.apptha.com
 * @copyright       Copyright (C) 2013 Powered by Apptha
 * @license 		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @creationDate    March 13 2013
 *********************************************************************
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

// View to edit
class FacegalleryViewSettings extends JViewLegacy
{
    protected $state;
    protected $item;
    protected $form;

    // Display the view
    public function display($tpl = null)
    {
        $this->state = $this->get('State');
        $this->item = $this->get('Item');
        $this->form = $this->get('Form');

        // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            throw new Exception(implode("\n", $errors));
        }
        FacegalleryHelper::addSubmenu('settings');
        $this->addToolbar();
        $this->sidebar = JHtmlSidebar::render(); 

        // Get settings information from settings table
        $model = $this->getModel();
        $settingValue = $model->getSettings();
        $this->assignRef('settings', $settingValue);
        
        parent::display($tpl);
    }

    // Add the page title and toolbar.
    protected function addToolbar()
    {
        JFactory::getApplication()->input->set('hidemainmenu', false);

        $user = JFactory::getUser();
        $isNew = ($this->item->id == 0);
        if (isset($this->item->checked_out))
        {
            $checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
        } 
        else
        {
            $checkedOut = false;
        }
        $canDo = facegalleryHelper::getActions();
        JToolBarHelper::title(JText::_('COM_FACEGALLERY_MANAGER_SETTINGS'), 'settings.png');

        // If not checked out, can save the item.
        if (!$checkedOut && ($canDo->get('core.edit')))
        {
            JToolBarHelper::apply('settings.apply', 'JTOOLBAR_APPLY');
        }
        if (empty($this->item->id))
        {
            JToolBarHelper::cancel('settings.cancel', 'JTOOLBAR_CANCEL');          
        }
        else
        {
            JToolBarHelper::cancel('settings.cancel', 'JTOOLBAR_CLOSE');        
		}
    }
}