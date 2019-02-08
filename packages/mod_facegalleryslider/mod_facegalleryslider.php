<?php
/*
 *************************************************************************
 * @name            Face Gallery Slider
 * @version         1.0: facegalleryslider.php$
 * @since           Joomla 3.0
 * @package         apptha
 * @subpackage      mod_facegallery
 * @author          Apptha - http://www.apptha.com
 * @copyright       Copyright (C) 2013 Powered by Apptha
 * @license			http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @creationDate    March 13 2013
 **************************************************************************
 */
 
//No Direct Access
defined('_JEXEC') or die('Restricted access');
	
	//Includeing Helper File
	require_once __DIR__ . '/helper.php';
	$imageList      = $params->get('imageList');
        
    //Get params Values.
    $albumList       = $params->get('albumList');
	$sliderStyle     = $params->get('sliderstyle');
    $sliderTime      = $params->get('slidertiming');       
	$bannerDetails	= modFacegallerySliderHelper::getListImages($imageList,$albumList);
	$sliderSize = modFaceGallerySliderHelper::getHeightWidth();
       
    require JModuleHelper::getLayoutPath('mod_facegalleryslider', $params->get('layout', 'default'));
?>
