<?php
/*
 *********************************************************************
 * @name        	Face Gallery
 * @version			1.0:views/home/view.html.php$
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

class FacegalleryViewHome extends JViewLegacy
{
	function display($tpl = null)
	{
		// Assign data to the view
		$model= $this->getModel('home');
		
		//To get the popular albums list
		$popularImages 	= $model->mostPopularAlbums();
		$this->assignRef('popularimages', $popularImages);
		
		//To get the recent albums list
	    $recentImages 	= $model->mostRecentAlbums();
		$this->assignRef('recentimages', $recentImages);
		
		//To get the featured albums list
		$featuredImages = $model->mostFeaturedAlbums();
		$this->assignRef('featuredimages', $featuredImages);
		
		//To get the albums setting
		$albumSetting 	= $model->getAlbumSettings();
		$this->assignRef('albumsetting', $albumSetting);

		// Display the view
		parent::display($tpl);

	}
}
?>