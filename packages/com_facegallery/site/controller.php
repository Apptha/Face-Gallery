<?php
/*
 ********************************************************************
 * @name        	Face Gallery
 * @version			1.0: controller.php$
 * @since       	Joomla 3.0
 * @package			apptha
 * @subpackage		com_facegallery
 * @author          Apptha - http://www.apptha.com
 * @copyright   	Copyright (C) 2013 powered by Apptha
 * @license			http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @creationDate    March 13 2013
 *********************************************************************
 */

//RESTRICTED ACCESS
defined('_JEXEC') or die("restricted access");

jimport('joomla.application.component.controller');

class facegalleryController extends JControllerLegacy 
{
	public function display($cachable = false, $urlparams = false) 
	{
		$vName = $this->input->get('view');
		if ($vName == '') 
		{
			$this->input->set('view', 'home');
		}
		parent::display();
	}
	public function imageDownload()
	{   				
		ob_clean();		
		
		$imgId = JRequest::getInt("img_id");
		
		if(!empty($imgId)){
			$db = JFactory::getDbo();

	        // Select next images from images tables
	        $sql = "SELECT a.image_name FROM #__facegallery_images a LEFT JOIN #__facegallery_albums b ON b.id = a.albumid"
	                . " WHERE a.id = " . $db->escape($imgId)
	                . " AND a.state = 1";
	
	        $db->setQuery($sql);
	        $imgName = $db->loadResult();
			
			$file = JURI::base() . 'images/facegallery/'.$imgName ;
			
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Content-Type: application/force-download");
			header("Content-Disposition: attachment; filename=" . basename($file));
			header("Content-Description: File Transfer");
			@readfile($file);
		}
		die();
	}
}

