<?php
/*
 ********************************************************************
 * @name            Face Gallery
 * @version         1.0: facegallery.php$
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

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_facegallery'))
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');
$controller = JControllerLegacy::getInstance('Facegallery');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
?>