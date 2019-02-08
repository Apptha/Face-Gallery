<?php
/*
 ********************************************************************
 * @name            Face Gallery
 * @version         1.0: controllers/settings.php$
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

// Settings controller class.
class FacegalleryControllerSettings extends JControllerForm
{
    public function display($cachable = false, $urlparams = false)
    {
        parent::display();
    }

    // To save settings information
    public function save($key = NULL, $urlVar = NULL)
    {
        // Get Settings for album and images
        $data = JRequest::get('POST');
        
        if ($model = $this->getModel('settings'))
        {
            $res = $model->update($data);
            $this->setRedirect('index.php?option=com_facegallery&view=settings', 'New Settings saved!');
        }
    }
}