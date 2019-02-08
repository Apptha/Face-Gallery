<?php
/*
 *******************************************************************
 * @name            Face Gallery
 * @version         1.0: helpers/phpFlickr-3.1/auth.php$
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
	
    $api_key                 = "[your api key]";
    $api_secret              = "[your api secret]";
    $default_redirect        = "/";
    $permissions             = "read";
    $path_to_phpFlickr_class = "./";

    ob_start();
    require_once($path_to_phpFlickr_class . "phpFlickr.php");
    @unset($_SESSION['phpFlickr_auth_token']);
     
	if ( isset($_SESSION['phpFlickr_auth_redirect']) && !empty($_SESSION['phpFlickr_auth_redirect']) )
        {
		$redirect = $_SESSION['phpFlickr_auth_redirect'];
		unset($_SESSION['phpFlickr_auth_redirect']);
	}
    
    $f = new phpFlickr($api_key, $api_secret);
 
    if (empty($_GET['frob']))
    {
        $f->auth($permissions, false);
    } 
    else
    {
        $f->auth_getToken($_GET['frob']);
    }
    
    if (empty($redirect))
    {
		header("Location: " . $default_redirect);
    } 
    else
    {
		header("Location: " . $redirect);
    } 
?>