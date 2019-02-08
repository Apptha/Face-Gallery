<?php
/*
 **********************************************************************
 * @name        	Face Gallery
 * @version			1.0:models/albumlist.php$
 * @since       	Joomla 3.0
 * @package			apptha
 * @subpackage		com_facegallery
 * @author          Apptha - http://www.apptha.com
 * @copyright   	Copyright (C) 2013 powered by Apptha
 * @license			http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @creationDate    March 13 2013
 **********************************************************************
 */

//No direct access
defined('_JEXEC') or die("restricted access");

class facegalleryModelalbumlist extends JModelList {

    //Method to get all album details
    public function getAlbums($ordering) {

        $db = JFactory::getDbo();
        $start = $limitStart = $limit = 0;
        
        //Getting limitstart
        $limitStart = JRequest::getInt('limitstart');
       
        if (!empty($limitStart))
            $start 	= $limitStart;

        //Pagination limit
        $settings 	= $this->getSettings();
        if(!empty($settings)) {
        	$limit 		= $settings->album_pagination;
        }

        $where = $orderby = NULL;
        switch ($ordering) {
            case 'popular':
            	$where 		= 'AND alb.album_views != 0 ';
                $orderby 	= 'ORDER BY alb.album_views DESC ';
                break;
            case 'featured':
                $where 		= 'AND alb.featured = 1 ';
                $orderby 	= 'ORDER BY alb.featured DESC';
                break;
            case 'recent':           
                $orderby 	= 'ORDER BY alb.id DESC ';
                break;
        }
        
		if(!empty($orderby)) {
	       	$sql = 'SELECT alb.album_name, alb.id as albumid, img.id,img.views,img.image_name,img.image, img.thumb_image, img.cover_image, '
	                . '(SELECT COUNT(*) FROM #__facegallery_images temp WHERE temp.albumid = alb.id AND temp.state=1) AS imagecount '
	                . 'FROM #__facegallery_albums AS alb '
	                . 'LEFT JOIN #__facegallery_images AS img '
	                . 'ON img.albumid=alb.id '
	                . 'WHERE alb.state = 1 '                
	                . 'AND img.state = 1 '
	                . $where
	                . 'GROUP BY alb.id '
	                . $orderby;
	
	        $db->setQuery($sql);
	        $result = $db->loadObjectList();
	        $total 	= count($result);
	
	        //Select all records from the images,albums table
	        $sql = 'SELECT alb.album_name, alb.id as albumid, img.id,img.views,img.image_name,img.image, img.thumb_image, img.cover_image, '
	                . '(SELECT COUNT(*) FROM #__facegallery_images temp WHERE temp.albumid = alb.id AND temp.state=1) AS imagecount '
	                . 'FROM #__facegallery_albums AS alb '
	                . 'LEFT JOIN #__facegallery_images AS img '
	                . 'ON img.albumid=alb.id '
	                . 'WHERE alb.state = 1 '                
	                . 'AND img.state = 1 '
	                . $where
	                . 'GROUP BY alb.id '
	                . $orderby
	                . ' LIMIT ' . (int) $start . "," . $limit;
	        //. 'ORDER BY alb.views DESC '
	        //Reset the query using our newly populated query object.
	        $db->setQuery($sql);
	
	        //Load the results as a list of stdClass objects.
	        $results 	= $db->loadObjectList();
	        $pagination = array();
	
	        $pagination['limit'] = $limit;
	        $pagination['total'] = $total;
	        $pagination['start'] = $limitStart;
	
	        return array($results, $pagination);
		}
		else {
			return '';
		}
    }

    public function getSettings() {

        $db 	= JFactory::getDbo();
        $sql 	= "SELECT album_pagination, album_height, album_width"
                . " FROM #__facegallery_settings"
                . " LIMIT 1";
        $db->setQuery($sql);
        $result = $db->loadObject();

        return $result;
    }

}
