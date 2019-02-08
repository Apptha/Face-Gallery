<?php
/*
 ********************************************************************
 * @name            Face Gallery
 * @version         1.0: controllers/image.php$
 * @since           Joomla 3.0
 * @package         apptha
 * @subpackage      com_facegallery
 * @author          Apptha - http://www.apptha.com
 * @copyright       Copyright (C) 2013 Powered by Apptha
 * @license 		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @creationDate    March 13 2013
 ********************************************************************
 */

// No direct access
defined('_JEXEC') or die("restricted access");

jimport('joomla.application.component.controllerform');

// Image controller class.
class FacegalleryControllerImage extends JControllerForm
{
    public function display($cachable = false, $urlparams = false) 
    {
        $this->view_list = 'images';
        
        parent::display();
    }

    //To save and update image details
    function save($data=null, $key=null)
    {
            // Get image details
            $data = JRequest::get('POST');

            if($data['albumid'] != 0)
            {
                // Get model and save new images
                $model = $this->getModel('image');
                
                $model->saveImage($data);

                // Check the task and redirect to corresponding view
                if($data['task'] == 'image.save')
                {
                    $this->setRedirect('index.php?option=com_facegallery&view=images', 'Item successfully saved!');
                }
                if($data['task'] == 'image.save2new')
                {
                    $this->setRedirect('index.php?option=com_facegallery&view=image&layout=edit', 'Item successfully saved!');
                }
            }
            else
            {
                $this->setRedirect('index.php?option=com_facegallery&view=image&layout=edit');
                $application = JFactory::getApplication();
                
                JFactory::getApplication()->enqueueMessage(JText::_('COM_FACEGALLERY_FORM_ERROR_SELECT_ALBUM'), 'error');                
            }
    }
}