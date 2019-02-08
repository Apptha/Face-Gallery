<?php
/*
 ********************************************************************
 * @name        	Face Gallery
 * @version			1.0:views/albumlist/view.html.php$
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

class FacegalleryViewalbumlist extends JViewLegacy
{
	function display($tpl = null)
	{
		// Assign data to the view
		$model= $this->getModel('albumlist');

        $ordering =  JRequest::getString('album');
		
        //assigning popular albums 
		$popular = $model->getAlbums($ordering);
	    $this->assignRef('albumlist', $popular);
	    
	    $settings = $model->getSettings();
	    $this->assignRef('albumSettings', $settings);

		// Display the view
		parent::display($tpl);
	}
}
?>


