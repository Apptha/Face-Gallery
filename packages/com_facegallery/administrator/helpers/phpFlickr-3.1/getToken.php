<?php
/*
 *******************************************************************
 * @name            Face Gallery
 * @version         1.0: helpers/phpFlickr-3.1/getToken.php$
 * @since           Joomla 3.0
 * @package         apptha
 * @subpackage      com_facegallery
 * @author          Apptha - http://www.apptha.com
 * @copyright       Copyright (C) 2013 Powered by Apptha
 * @license 		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @creationDate    March 13 2013
 ********************************************************************
 */
	// No direct access
defined('_JEXEC') or die("restricted access");

    require_once("phpFlickr.php");
    $f = new phpFlickr("79dde8ee6cc29b65353c26c73c35d142", "8dfa77c801615e44");
    
    //change this to the permissions you will need
    $f->auth("read");    
    echo "Copy this token into your code: " . $_SESSION['phpFlickr_auth_token'];
    
?>