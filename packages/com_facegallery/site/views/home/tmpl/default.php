<?php
/*
 ********************************************************************
 * @name        	Face Gallery
 * @version			1.0: views/home/default.php$
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

// Initialization
$albumColumn = $albumRows = $imgHgt = $imgWdth = 0;

$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::Base() . 'components/com_facegallery/css/main.css');

$albumSetting   = $this->albumsetting;
if(!empty($albumSetting)) {
	$albumColumn    = $albumSetting->album_column;
	$albumRows      = $albumSetting->album_row;
	$imgHgt 		= $albumSetting->album_height;
	$imgWdth 		= $albumSetting->album_width;
}

//Code to get the recent, featured, popular albums from modal class
$popularimages  = $this->popularimages;
$recentimages   = $this->recentimages;
$featuredimages = $this->featuredimages;
?>
<!-- Display popular albums - START -->
<div class="album_container popular_albums">
  <h4><span><a href='<?php echo JRoute::_('index.php?option=com_facegallery&view=albumlist&album='.'popular');?>'><?php echo JText::_('COM_FACEGALLERY_POPULAR_ALBUMS');?></a></span> 
   <a class='view_link' href='<?php echo JRoute::_('index.php?option=com_facegallery&view=albumlist&album='.'popular');?>'><?php echo JText::_('COM_FACEGALLERY_SEE_ALL');?></a></h4>
   
    
    <div class="album_image_container">
        <ul>
            <?php echo loadImages($popularimages, $albumRows, $albumColumn, $imgHgt, $imgWdth ); ?>
        </ul>
    </div>
</div>
<!-- Display popular albums - END -->

<!-- Display recent albums  - START -->
<div class="album_container recent_album">
    <h4><span><a href='<?php echo JRoute::_('index.php?option=com_facegallery&view=albumlist&album='.'recent');?>'><?php echo JText::_('COM_FACEGALLERY_RECENT_ALBUMS');?></a></span> 
    <a class='view_link' href='<?php echo JRoute::_('index.php?option=com_facegallery&view=albumlist&album='.'recent');?>'><?php echo JText::_('COM_FACEGALLERY_SEE_ALL');?></a></h4>
   
    
    <div class="album_image_container">
        <ul>
            <?php echo loadImages($recentimages, $albumRows, $albumColumn, $imgHgt, $imgWdth); ?>
        </ul>
    </div>
</div>
<!-- Display recent albums - END -->

<!-- Display featured albums  - START  -->
<div class="album_container featured_album">

    <h4><span><a href='<?php echo JRoute::_('index.php?option=com_facegallery&view=albumlist&album='.'featured');?>'><?php echo JText::_('COM_FACEGALLERY_FEATURED_ALBUMS');?></a></span> 
    <a class='view_link'  href='<?php echo JRoute::_('index.php?option=com_facegallery&view=albumlist&album='.'featured');?>'><?php echo JText::_('COM_FACEGALLERY_SEE_ALL');?></a></h4>
       
    
    <div class="album_image_container">
        <ul>
            <?php echo loadImages($featuredimages, $albumRows, $albumColumn, $imgHgt, $imgWdth); ?>
        </ul>
    </div>
</div>
<!-- Display featured albums - END -->

<?php
//Method to display recent, featured, popular albums
function loadImages($albums, $albumRows, $albumColumn, $imgHgt, $imgWdth) {
	$content    = $album = NULL;
	$inc        = 1;
	$width = $height = $newWdth = $newHgt = 0;
	
	if(!empty($albums)) {

	    foreach ($albums as $album) {	    
	    
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
	
	        $total    = $album->imagecount;
	        $content .= '<li>';
	        $content .= '<a href="' . JRoute::_("index.php?option=com_facegallery&view=images&aid=" . $album->albumid) . '" class="album_cover">';
	        if($album->cover_image)
	        {
	        	$content .= '<span><img alt="' . $album->thumb_image . '" src="' .$img .'" style="' . $newWdth. ' ' .$newHgt. ' max-width:none !important;"  /></span>';
	        }
	        else 
	        {        	
	        	$content .= '<span><img alt="" src="' . JURI::Base() . 'components/com_facegallery/images/default.jpg' .  '"   /></span>';
	        }
	        $content .= '</a><span class="drop_shadow"></span>';
	        $content .= '<p class="album_title">' . ucfirst($album->album_name) . '</p>';
	        $content .= '<p class="images_count"><i></i><span>' . $total .' '. JText::_('COM_FACEGALLERY_FEATURED_IMAGE'). ' </span></p>';
	        $content .= '</li>';
	
	        if ($inc % $albumColumn == 0 && count($albums) != $inc) {
	            $content .= "</ul><ul>"; 
	        }
	
	        $inc++;
    	} // End foreach
	} // End If
    return $content;
}
?>