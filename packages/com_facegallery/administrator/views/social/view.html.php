<?php
/*
 *********************************************************************
 * @name            Face Gallery
 * @version         1.0: view/social/view.html.php$
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
class FacegalleryViewSocial extends JViewLegacy
{
    public function display($tpl = null)
    {
        // Check for errors.
        $this->state = $this->get('State');
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');

        // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            throw new Exception(implode("\n", $errors));
        }

        // Add toolbar and sidebar
        FacegalleryHelper::addSubmenu('social');
        $this->addToolbar();
        $this->sidebar = JHtmlSidebar::render();

        $fileoption = JRequest::getVar('task');
        $model = $this->getModel();        
        if ($fileoption == 'Flickr')
        {
            // Get settings infromation for flickr
            $flickr_res = $model->getApiSettings();
            $flickrApi = $flickr_res->flickr_key;
            $flickerUserId = $flickr_res->flickr_user_id;

            if ($flickrApi != null && $flickerUserId != null)
            {
                $model->getFlickrPhotosDownload($flickrApi, $flickerUserId);
            }
            else
            {
                $application = JFactory::getApplication();
                /** Alternatively you may use chaining */
                JFactory::getApplication()->enqueueMessage(JText::_('COM_FACEGALLERY_FORM_ERROR_FLICKR'), 'error');
            }
        }
        else if ($fileoption == 'Google')
        {
            $picasa_res = $model->getApiSettings();

            $picasa_user_name = $picasa_res->picasa_user_name;
            if ($picasa_user_name != null)
            {
                $model->getPicasaPhotosById($picasa_user_name);
            }
            else
            {
                $application = JFactory::getApplication();

                // Alternatively you may use chaining 
                JFactory::getApplication()->enqueueMessage(JText::_('COM_FACEGALLERY_FORM_ERROR_GOOGLE'), 'error');
            }
        }

        parent::display($tpl);
    }

    // Add the page title and toolbar.
    protected function addToolbar()
    {
        require_once JPATH_COMPONENT . '/helpers/facegallery.php';
        JToolBarHelper::title(JText::_('COM_FACEGALLERY_MANAGER_SOCIAL'), 'social.png');
        JFactory::getApplication()->input->set('hidemainmenu', false);
    }
}
