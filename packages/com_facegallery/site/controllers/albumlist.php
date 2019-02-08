<?php
/*
 ********************************************************************
 * @name        	Face Gallery
 * @version			1.0:controllers/albumlist.php$
 * @since       	Joomla 3.0
 * @package			apptha
 * @subpackage		com_facegallery
 * @author          Apptha - http://www.apptha.com
 * @copyright   	Copyright (C) 2013 powered by Apptha
 * @license			http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @creationDate    March 13 2013
 *********************************************************************
 */

//No direct access
defined('_JEXEC') or die("restricted access");

jimport('joomla.application.component.controller');

class albumlistController extends JControllerLegacy
{
	public function display($cachable = false, $urlparams = false)
	{
		// Calling display method
		parent::display($cachable,$urlparams );
	}
}