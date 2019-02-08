<?php
/*
 *************************************************************************
 * @name            Face Gallery Slider
 * @version         1.0: helper.php$
 * @since           Joomla 3.0
 * @package         apptha
 * @subpackage      mod_facegallery
 * @author          Apptha - http://www.apptha.com
 * @copyright       Copyright (C) 2013 Powered by Apptha
 * @license 		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @creationDate    March 13 2013
 **************************************************************************
 */

//No Direct Access
defined('_JEXEC') or die('Restricted access');

$doc =JFactory::getDocument();
class modFacegallerySliderHelper
{

	static public function getListImages($params,$list)
	{
		$db = JFactory::getDbo();
        
		// Get Popular, Recent, Featured Images
                // Get Popular Images
		if($params == 0)
        {
			$sql="ORDER BY views desc LIMIT 10 ";
		}
                // Get Recent Images
		else if($params == 1)
        {
			$sql="ORDER BY id desc LIMIT 10 ";
		}
                // Get Featured Images
		else if($params == 2)
        {
			$sql="AND featured=1 LIMIT 10";
		}
                // Get Images from Album 
        else if($params == 3)
        {
        	$sql="AND albumid = $list LIMIT 10";
        }

        $query = "SELECT *
		          FROM  #__facegallery_images 
		          WHERE state=1 $sql";
		$db->setQuery($query);		
		$results = $db->loadObjectList();
                return $results;
	}
 	static public function getHeightWidth()
	{
                 $db = JFactory::getDbo();
                 $db->setQuery("SELECT  `large_image_height`, `large_image_width`
                       FROM  `#__facegallery_settings`
                       WHERE 1");

                 // Store result as array
                 $data = $db->loadObject();
                 return $data;
     }
}
?>