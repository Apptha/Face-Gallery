<?php
/*
 **********************************************************************
 * @name        	Face Gallery
 * @version			1.0: views/imagesview/view.html.php$
 * @since       	Joomla 3.0
 * @package			apptha
 * @subpackage		com_facegallery
 * @author          Apptha - http://www.apptha.com
 * @copyright   	Copyright (C) 2013 powered by Apptha
 * @license			http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @creationDate    March 13 2013
 ***********************************************************************
 */

//restricted access
defined('_JEXEC') or die("restricted access");

class FacegalleryViewImagesview extends JViewLegacy {

    function display($tpl = null) {

        // Assign data to the view
        $model      = $this->getModel('imagesview');
        
        $imageId    = JRequest::getInt('id');
        $albumId    = JRequest::getInt('aid');
        $action     = JRequest::getVar('action');
        
        if ($action == 'addComment') {
            //To add Ajax comment data
            $model  = $model->addComment($imageId);
        } else {

            //Code to get image details
            $imageDetails   = $model->getImageDetails($imageId, $albumId);
            $this->assignRef('imageDetails', $imageDetails);

            //Code to get prev image
            $imagePrev      = $model->getPrevImage($imageId, $albumId);
            $this->assignRef('imagePrev', $imagePrev);

            //Code to get next image
            $imageNext      = $model->getNextImage($imageId, $albumId);
            $this->assignRef('imageNext', $imageNext);

            //Code to get image comments
            $imageComments  = $model->getComments($imageId);            
            $this->assignRef('imageComments', $imageComments);
            
          	$settings = $model->getSettingsComments();
            $this->assignRef('settings', $settings);
            
            // Display the view
        }
        parent::display($tpl);
    }
}
?>