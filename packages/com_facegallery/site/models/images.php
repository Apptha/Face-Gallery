<?php
/*
 ********************************************************************
 * @name        	Face Gallery
 * @version			1.0: models/images.php$
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

class FacegalleryModelImages extends JModelList 
{
    //Getting images for pagination
    public function getAlbumId() 
    {
        $db = JFactory::getDbo();
        $aid = JRequest::getInt('aid');
        if (empty($aid))
            return;
     
        $sql = "SELECT count(id)"
                . " FROM #__facegallery_images"
                . " WHERE albumid = " . $db->escape($aid)
                . " AND state=1";
        $db->setQuery($sql);
        $total = $db->loadResult();

        //Getting limistart
        $start = 0;
        $limitStart = JRequest::getInt('limitstart');
        
        if (!empty($limitStart))
            $start = $limitStart;
            
        $settings = $this->getSettings();
        $limit = $settings->image_pagination;
        $allowViews = $settings->display_views;
        
        //Fetching data from images and album data with start and limit
        $sql = "SELECT a.album_name,a.meta_keywords,a.meta_description,b.id,b.medium_image,b.views,b.image"
                . " FROM #__facegallery_albums a,"
                . " #__facegallery_images  b"
                . " WHERE a.id = b.albumid AND b.state=1"
                . " AND b.albumid = " . $db->escape($aid) 
                . " LIMIT " . (int) $start . "," . $limit;
        
             $db->setQuery($sql);
             $result = $db->loadObjectList();
             
            //Calculating total comments count
            $sql = " SELECT  ( "
                    . " SELECT COUNT(c.id ) "
                    . " FROM #__facegallery_comments c "
                    . " WHERE c.comment_item_id = b.id "
                    . " AND c.state=1) as count "
                    . " FROM #__facegallery_images b, #__facegallery_albums a "
                    . " WHERE a.id = " .  $db->escape($aid)
                    . " AND a.id = b.albumid ";

                    $db->setQuery($sql);
                      $totalCount = $db->loadObjectList();
                     

        //Assigning  limit,total,page values  to pagination array

                    $pagination = array();
                    $pagination['limit'] = $limit;
                    $pagination['total'] = $total;
                    $pagination['start'] = $limitStart;

        //Assigning comment total to total Count

        $pagination['totalCount']= $totalCount;

       //Updating album views
        if($allowViews)
        { 
           if($db->query())
	       {
		                   $sql = "UPDATE #__facegallery_albums "
		                      	. " SET album_views = album_views + 1"
		                        . " WHERE id  = " . $aid;
		                      	$db->setQuery($sql);
                               $db->query();
           }
        } 
        return array($result, $pagination);
    }

    //Getting images Settings
    public function getSettings() 
    {
        $db = JFactory::getDbo();
        $sql = "SELECT image_height,image_width,image_pagination,display_views"
                . " FROM #__facegallery_settings"
                . " LIMIT 1";
        $db->setQuery($sql);
        $result = $db->loadObject();

        return $result;
    }

}