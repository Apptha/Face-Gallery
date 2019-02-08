<?php
/*
 ********************************************************************
 * @name            Face Gallery
 * @version         1.0: controller.php$
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

// Facegallery Controller
class FacegalleryController extends JControllerLegacy
{
	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT.'/helpers/facegallery.php';

        // set albums as default view of the component
		$view = JFactory::getApplication()->input->getCmd('view', 'albums');
		
        JFactory::getApplication()->input->set('view', $view);
        
		parent::display($cachable, $urlparams);		
	}
}
