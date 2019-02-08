<?php
/*
 ********************************************************************
 * @name            Face Gallery
 * @version         1.0: router.php$
 * @since       	Joomla 3.0
 * @package         apptha
 * @subpackage      com_facegallery
 * @author          Apptha - http://www.apptha.com
 * @copyright       Copyright (C) 2013  Powered by Apptha
 * @license			http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @creationDate    March 13 2013
 ********************************************************************
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

function FacegalleryBuildRoute(&$query) {
    $segments = array();//echo "<pre>";print_r($query);exit;
    if (isset($query['view'])) {
        $segments[] = $query['view'];
        unset($query['view']);
    }
    if (isset($query['aid'])) {
        $segments[] = $query['aid'];
        unset($query['aid']);
    }
    if (isset($query['album'])) {
        $segments[] = $query['album'];
        unset($query['album']);
    }  
	if (isset($query['start'])) {
        $segments[] = $query['start'];
        unset($query['start']);
    } 
	if (isset($query['view'])) {
        $segments[] = $query['view'];
        unset($query['view']);
    }
	
    return $segments;
}

function FacegalleryParseRoute($segments) {

    $vars = array();
    // view is always the first element of the array
    $count = count($segments);
    if ($count) {
        switch ($segments[0]) {
        	case 'imagesview':
                $vars['view'] = 'imagesview';
                if (isset($segments[1]))
                    $vars['aid'] = $segments[1];
                 if (isset($segments[2]))
                    $vars['limitstart'] = $segments[2];
                break;
            case 'images':
                $vars['view'] = 'images';
                if (isset($segments[1]))
                    $vars['aid'] = $segments[1];
                 if (isset($segments[2]))
                    $vars['limitstart'] = $segments[2]; 
                break;
            case 'albumlist':
                $vars['view'] = 'albumlist';               
                if (isset($segments[1]))
                    $vars['album'] = $segments[1];
                if (isset($segments[2]))
                    $vars['limitstart'] = $segments[2]; 
                break;
                
          
        }

    }
    return $vars;
}

