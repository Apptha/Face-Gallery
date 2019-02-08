<?php
/*
 **********************************************************************
 * @name        	Face Gallery
 * @version			1.0: models/home.php$
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

class FacegalleryModelHome extends JModelList {

    //Method to get most popular albums list
    public function mostPopularAlbums() {

        $db         = JFactory::getDbo();
        $res        = $this->getAlbumSettings();
        $row        = $res->album_row;
        $col        = $res->album_column;
        $totalcount = $row * $col;        

        //Select all records from the images,albums table
        $sql = 'SELECT alb.album_name, alb.id as albumid, img.id,img.views,img.image_name,img.image, img.thumb_image, img.cover_image, '
                . '(SELECT COUNT(*) FROM #__facegallery_images temp WHERE temp.albumid = alb.id AND temp.state=1) AS imagecount '
                . 'FROM #__facegallery_albums AS alb '
                . 'LEFT JOIN #__facegallery_images AS img '
                . 'ON img.albumid=alb.id '
                . 'WHERE alb.state =1 '                
                . 'AND img.state=1 '
                . 'GROUP BY alb.id '
                . 'ORDER BY alb.album_views DESC '
                . 'LIMIT ' . $totalcount;

        //Reset the query using our newly populated query object.
        $db->setQuery($sql);
        //Load the results as a list of stdClass objects.
        $popularResults = $db->loadObjectList();

        return $popularResults;
    }

    //Method to get recent albums list
    public function mostRecentAlbums() {

        $db         = JFactory::getDbo();
        $res        = $this->getAlbumSettings();
        $row        = $res->album_row;
        $col        = $res->album_column;
        $totalcount = $row * $col;        

        // Select records from images,albums table for recent
        $sql = 'SELECT alb.album_name, alb.id as albumid, img.id,img.views,img.image_name,img.image, img.thumb_image, img.cover_image, '
                . '(SELECT COUNT(*) FROM #__facegallery_images temp WHERE temp.albumid = alb.id AND temp.state=1) AS imagecount '
                . 'FROM #__facegallery_albums AS alb '
                . 'LEFT JOIN #__facegallery_images AS img '
                . 'ON img.albumid=alb.id '
                . 'WHERE alb.state =1 '                
                . 'AND img.state=1 '
                . 'GROUP BY alb.id '
                . 'ORDER BY alb.id DESC '
                . 'LIMIT ' . $totalcount;

        // Reset the query using our newly populated query object.
        $db->setQuery($sql);

        $recentResults = $db->loadObjectList();
        return $recentResults;
    }

    //Method to get featured albums list
    public function mostFeaturedAlbums() {

        $db         = JFactory::getDbo();
        $res        = $this->getAlbumSettings();
        $row        = $res->album_row;
        $col        = $res->album_column;
        $totalcount = $row * $col;        

        // Select records from images,albums table for featured
        $sql = 'SELECT alb.album_name, alb.id as albumid, img.id,img.views,img.image_name,img.image, img.thumb_image, img.cover_image, '
                . '(SELECT COUNT(*) FROM #__facegallery_images temp WHERE temp.albumid = alb.id AND temp.state=1) AS imagecount '
                . 'FROM #__facegallery_albums AS alb '
                . 'LEFT JOIN #__facegallery_images AS img '
                . 'ON img.albumid=alb.id '
                . 'WHERE alb.state =1 '
                . 'AND alb.featured=1 '                
                . 'AND img.state=1 '
                . 'GROUP BY alb.id '
                . 'LIMIT ' . $totalcount;

        // Reset the query using our newly populated query object.
        $db->setQuery($sql);

        // Load the results as a list of stdClass objects.
        $featuredResults = $db->loadObjectList();

        return $featuredResults;
    }

    public function getAlbumSettings() {

        $db  = JFactory::getDbo();
        // Select all records from images,albums  table
        $sql = 'SELECT id, album_row, album_column, album_height, album_width'
                . ' FROM #__facegallery_settings '
                . 'LIMIT 1';

        // Reset the query using our newly populated query object.
        $db->setQuery($sql);

        // Load the results as a list of stdClass objects.
        $results = $db->loadObject();

        return $results;
    }   

}