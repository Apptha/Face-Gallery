<?php
/*
 ********************************************************************
 * @name        	Face Gallery
 * @version			1.0: views/images/default.php$
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

jimport('joomla.application.component.view');

class FacegalleryViewImages extends JViewLegacy {

    function display($tpl = null) {
        
        //Getting images model value
        $model = $this->getModel('images');
        $album = $model->getAlbumId();
        
        //Assign the album image details
        $this->assignRef('album', $album);

        $settings = $model->getSettings();
        $this->assignRef('settings', $settings);
        
        // Display the view
        parent::display($tpl);
    }
}
?>