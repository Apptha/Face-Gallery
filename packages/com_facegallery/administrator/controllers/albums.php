<?php
/*
 ********************************************************************
 * @name            Face Gallery
 * @version         1.0: controllers/albums.php$
 * @since           Joomla 3.0
 * @package         apptha
 * @subpackage      com_facegallery
 * @author          Apptha - http://www.apptha.com
 * @copyright       Copyright (C) 2013 Powered by Apptha
 * @license 		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @creationDate    March 13 2013
 ********************************************************************
 */

// No direct access.
defined('_JEXEC') or die("restricted access");

jimport('joomla.application.component.controlleradmin');

// Albums Grid view controller class.
class FacegalleryControllerAlbums extends JControllerAdmin
{
	// Proxy for getModel.
	public function getModel($name = 'Album', $prefix = 'FacegalleryModel',$config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
        
	// Method to save the submitted ordering values for records via AJAX.
	public function saveOrderAjax()
	{
		// Get the input
		$input  = JFactory::getApplication()->input;
		$pks    = $input->post->get('cid', array(), 'array');
		$order  = $input->post->get('order', array(), 'array');

		// Sanitize the input
		JArrayHelper::toInteger($pks);
		JArrayHelper::toInteger($order);

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveorder($pks, $order);

		if ($return)
		{
			echo "1";
		}

		// Close the application
		JFactory::getApplication()->close();
	}
}