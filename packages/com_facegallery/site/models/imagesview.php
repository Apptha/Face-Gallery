<?php
/*
 ********************************************************************
 * @name        	Face Gallery
 * @version			1.0: models/imagesview.php$
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

class facegalleryModelimagesview extends JModelList 
{
    //Method to get all image details
    public function getImageDetails($id, $albumId) 
    {
        $db = JFactory::getDbo();
        // Select image details from images tables
        $sql = "SELECT * "
                . " FROM #__facegallery_images"
                . " WHERE id = " . $db->escape($id)
                . " AND state = 1"
                . " AND albumid = " . $db->escape($albumId);

        // Reset the query using our newly populated query object.
        $db->setQuery($sql);

        // Load the results as a list of stdClass objects.
        $results = $db->loadObject();
        
        $settings = $this->getSettingsComments();
        $allowViews = $settings[0]->display_views;
        
        if($allowViews)
        {
	        if($db->query())
		    {
				$sql = "UPDATE #__facegallery_images "
					. " SET views = views + 1"
			        . " WHERE id  = " . $id;
				$db->setQuery($sql);
	    		$db->query();
	        }
	    }
        return $results;
    }

    //Method to get previous Image
    function getPrevImage($id, $albumId) 
    {
        $db = JFactory::getDbo();

        // Select prev images from images tables
        $sql = "SELECT id FROM #__facegallery_images"
                . " WHERE id < " . $db->escape($id)
                . " AND albumid = " . $db->escape($albumId)
                . " AND state = 1"
                . " ORDER BY id DESC LIMIT 1";

        $db->setQuery($sql);
        $results = $db->loadResult();
        return $results;
    }

    //Method to get next Image
    function getNextImage($id, $albumId) 
    {
        $db = JFactory::getDbo();

        // Select next images from images tables
        $sql = "SELECT id FROM #__facegallery_images"
                . " WHERE id > " . $db->escape($id)
                . " AND albumid = " . $db->escape($albumId)
                . " AND state = 1"
                . " ORDER BY id ASC LIMIT 1";

        $db->setQuery($sql);
        $results = $db->loadResult();
        return $results;
    }

    //Method to get Comments
    function getComments($id) 
    {
        $db = JFactory::getDbo();

        //Select image comments from comments table
        $sql = "SELECT * FROM #__facegallery_comments"
                . " WHERE comment_item_id = " . $db->escape($id)
                . " AND state = 1"
                . " ORDER BY id DESC";

        $db->setQuery($sql);
        $results = $db->loadObjectList();
  

        return $results;
    }

    //Method to get Comment Settings
    function getSettingsComments() 
    {
        $db = JFactory::getDbo();

        //Select comment settings
        $sql = "SELECT * "
                . " FROM #__facegallery_settings";
        $db->setQuery($sql);
        $results = $db->loadObjectList();
   
        return $results;
    }

    //Method to Add Comments
    function addComment($imageId) 
    {
        $db = JFactory::getDbo();

        $commentName    = JRequest::getString('name');
        $commentName    = $db->escape($commentName);

        $commentText    = JRequest::getString('text');
        $commentText    = $db->escape($commentText);

        $now            = JFactory::getDate();
        $userIP         = $this->getUserIP();

        $displayComment = $this->getSettingsComments();
        
        $state = ($displayComment[0]->moderate_comments == 1) ? '1' : '0';

        //Add comment
        $sql = "INSERT INTO #__facegallery_comments SET
	        `comment_item_id` = '$imageId',
                `comment_ip` 	  = '$userIP',
		`state`           = '$state',
                `created_on` 	  = '$now',
		`comment_name` 	  = '$commentName',
		`comment_text`    = '$commentText'";

           $db->setQuery($sql);

           if ($db->query()) {
            $sql  = "UPDATE #__facegallery_comments
                     SET `state` = $state
                     WHERE `id`  =" . $imageId;
            $db->setQuery($sql);
        }

        return;
    }

    public function getAlbumName($imageId) 
    {
        $db = JFactory::getDbo();

        $sql = "SELECT count(a.id)"
                . " FROM #__facegallery_albums a,"
                . " #__facegallery_images  b"
                . " WHERE a.id = b.albumid"
                . " AND b.albumid = " . $db->escape($imageId);
        $db->setQuery($sql);
        $total = $db->loadResult();
    }

    //Getting User IP Address
    function getUserIP() 
    {
        //To get user IP address
        $ip = $_SERVER['REMOTE_ADDR'];
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
            $ip = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
        }
        return $ip;
    }
}

?>