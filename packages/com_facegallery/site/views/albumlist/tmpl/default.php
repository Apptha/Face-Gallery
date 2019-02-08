<?php
/*
 **********************************************************************
 * @name        	Face Gallery
 * @version			1.0: views/albumlist/default.php$
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

// Initialization
$imgHgt = $imgWdth = $newWdth = $newHgt = $total = $limit = $start = 0;
$pagination = $content = $type = NULL;
$inc = 1;

$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::Base() . 'components/com_facegallery/css/main.css');

$albumlist = $this->albumlist;

if(!empty($albumlist[1])) {
	$total = $albumlist[1]['total'];
	$limit = $albumlist[1]['limit'];
	$start = $albumlist[1]['start'];
}

$sortBy = JRequest::getString('album');

$settings = $this->albumSettings;

if(!empty($settings)) {
	
	$imgHgt 	= $settings->album_height;
	$imgWdth 	= $settings->album_width;
}

//START PAGINATION
$pagination = new JPagination($total, $start, $limit);

if($sortBy == 'popular') {
    $type = JText::_('COM_FACEGALLERY_POPULAR_ALBUMS'); }
else if($sortBy == 'recent') {
    $type = JText::_('COM_FACEGALLERY_RECENT_ALBUMS'); }
else if($sortBy == 'featured') {
    $type = JText::_('COM_FACEGALLERY_FEATURED_ALBUMS'); }
?>

<!-- Display popular albums  START -->
<div class="album_container popular_albumss">
    <h4><i></i><span><?php echo $type; ?></span></h4>
    <span class="up_arrow"></span>
    <div class="album_image_container">
        <ul>
            <?php
            //Code to display recent, featured, popular albums list by sorting 
            if(!empty($albumlist[0])) {
            	
	            foreach ($albumlist[0] as $album) {
	            	            
		            $img = JURI::Base() . 'images/facegallery/thumb_image/' . $album->thumb_image;
				          
					list($width, $height) = getimagesize($img);
					
					if( $width < $imgWdth) {
						
						$newWdth = $imgWdth - $width;
						$width = $width + $newWdth;
						$newWdth=" width:".$width."px;";
						$newHgt='';
					}
					else if( $height < $imgHgt) {

						$newHgt = $imgHgt - $height;
						$height = $height + $newHgt;
						$newHgt=" height:".$height."px;";
						$newWdth='';
					}
		            
		            
		            if(($inc%4) == 0) {
				         $style = 'remove-margin';
				    }
				    else {
				         $style = '';
				    }

				    $total = $album->imagecount;
		            $content .= '<li class="'.$style.'">';
		            $content .= '<a href="' . JRoute::_("index.php?option=com_facegallery&view=images&aid=" . $album->albumid) . '" class="album_cover">';
		            if($album->cover_image) {
		                	$content .= '<span><img src="' . $img . '" style="' . $newWdth. ' ' .$newHgt. ' max-width:none !important;" /></span>';
		        	}
		        	else {
		        			$content .= '<span><img src="' . JURI::Base() . 'components/com_facegallery/images/default.jpg' . '" /></span>';
		        	}
		            $content .= '</a><span class="drop_shadow"></span>';
		            $content .= '<p class="album_title">' . ucfirst($album->album_name) . '</p>';
		            $content .= '<p class="images_count"><i></i><span>' . $total . ' ' . JText::_('COM_FACEGALLERY_IMAGES') . ' </span></p>';
		            $content .= '</li>';
		            
		            if ($inc % $limit == 0 && count($albumlist) != $inc){
		                    $content .= "</ul><ul>";
	            	}
	            	
		            $inc++;
	            } // End foreach
            } // End If
            
            //print album result
            echo $content;            
            ?>
        </ul>
    </div>
     <?php if(!empty($pagination)) { echo $pagination->getListFooter(); } ?>       
        <a href='<?php echo JRoute::_('index.php?option=com_facegallery&view=home'); ?>'>Back to Home</a>
</div>