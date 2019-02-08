<?php
/*
 **********************************************************************
 * @name            Face Gallery
 * @version         1.0: controllers/social.php$
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
defined('_JEXEC') or die("restricted access");

jimport('joomla.application.component.controllerform');

// Social controller class.
class FacegalleryControllerSocial extends JControllerAdmin
{
    public function getModel($name = 'Social', $prefix = 'FacegalleryModel',$config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		
		return $model;
	}

}