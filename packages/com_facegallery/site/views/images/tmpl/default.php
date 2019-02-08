<?php
/*
 *********************************************************************
 * @name        	Face Gallery
 * @version			1.0: views/images/default.php$
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

// Import joomla libraries
jimport('joomla.html.pagination');

// Initialization
$imageId = $pagination = $albumName = $image = $img = NULL;
$commentCount = $total = $limit = $start = 0;
$i = $width = $height = $newWdth = $newHgt = 0;
$inc = 1;

//INCLUDING JS AND CSS FILES
$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::Base() . 'components/com_facegallery/css/main.css');
$doc->addStyleSheet(JURI::Base() . 'components/com_facegallery/css/scroll.css');

$albumId 	= JRequest::getInt('aid');
$imageView 	= JRoute::_('index.php?option=com_facegallery&view=imagesview&aid=' . $albumId . '&tmpl=component');
$doc->addScriptDeclaration('FgalleryImageView = "' . $imageView . '"');

$album 		= $this->album;
$settings 	= $this->settings;

$allowViews = $settings->display_views;
$imgHgt 	= $settings->image_height;
$imgWdth 	= $settings->image_width;

//CODE TO SET META KEYWORDS, DESCRIPTION OF ALBUM
if(!empty($album[0][0])) {
	
	$albumName 	= $album[0][0]->album_name;
	
	if( $album[0][0]->meta_keywords != "" )
	$doc->setMetaData("keywords", $album[0][0]->meta_keywords);
	
	if( $album[0][0]->meta_description != "" )
	$doc->setDescription(strip_tags($album[0][0]->meta_description));
}

//FOR PAGINATION
if(!empty($album[1])) {
	
	$total = $album[1]['total'];	
	$limit = $album[1]['limit'];
	$start = $album[1]['start'];	
	$commentCount 	= $album[1]['totalCount'];
	
	//START PAGINATION
	$pagination 	= new JPagination($total, $start, $limit);
}

?>

<?php if (!empty($album)) { ?>
    <!-- INCLUDING SCRIPT FOR POPUP -->
    <!--<div id="loader"></div>
    <script type="text/javascript">
        if (typeof jQuery == 'undefined') {
            var script  = document.createElement('script'); script.async = true;
            script.type = "text/javascript";
            script.src  = "<?php //echo JURI::Base(); ?>media/jui/js/jquery.min.js";
            document.getElementById('loader').appendChild(script);
            jQuery.noConflict();
        }
    </script>-->

    <script src="<?php echo JURI::base() . 'components/com_facegallery/js/script.js' ?>" type="text/javascript"></script>
    <script src="<?php echo JURI::base() . 'components/com_facegallery/js/scroll.js' ?>" type="text/javascript"></script>
	<script src="<?php echo JURI::base() . 'components/com_facegallery/js/jquery-1.7.2.min.js' ?>" type="text/javascript"></script>
    <script type="text/javascript">
	    var dom = jQuery.noConflict(true);
	    dom(document).ready(function(){
		    dom(".image_item").hover(function(){
		    	dom(this).find(".interact").fadeIn("fast");
		    }, function(){
		    	dom(this).find(".interact").fadeOut("fast");
		  	});
		});
	</script>   
   
    <!-- IMAGES VIEW DIVISION  -->
    <div class="images_container image_view">
        <h4> <span> <?php echo ucfirst($albumName); ?> </span> </h4>
       
        <div class="image_view_container">
        	<ul>
            <?php              
            if(!empty($album[0])) { 

            	  foreach ($album[0] as $image) { 

            		$img = JURI::Base(). 'images/facegallery/medium_image/' . $image->medium_image;
				          
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
		
				    if(($inc%3) == 0) {
				         $style = 'no-margin';
				    }
				    else {
				         $style = '';
				    }
            ?>
                <li class="image_item  <?php echo $style;?>">
                    <div class="image" id="image_grid">
                    	<div style="width: 206px; height: 206px; overflow: hidden;">
                        	<img style="max-width:none !important; <?php echo $newWdth. ' ' .$newHgt; ?>" alt="<?php echo $image->medium_image; ?>" src="<?php echo $img; ?>" />
                        </div>
                        <a class="interact" style="display: none;" id="<?php echo $image->id; ?>">
                        <p class="comments_count"><i></i><span><?php if(!empty($commentCount)){ echo $commentCount[$i]->count; } ?> </span></p>
                            <?php if($allowViews) { ?><p id="views_count<?php echo $image->id; ?>" class="views_count"><?php echo $image->views; ?><?php echo ' ' . JText::_('COM_FACEGALLERY_VIEW'); ?></p> <?php }?>
                            <p class="image_name"><?php echo $image->image; ?></p>
                            
                        </a>
                    </div>
                </li>
                
            <?php if ($inc % 3 == 0 && count($album[0]) != $inc) { ?>
                </ul><ul>
            <?php } ?>
            <?php $inc++; $i++; ?>
            <?php } // End foreach
				 } // End If?>
                </ul>
            </div>
        </div>
<?php } // End If ?>

      <!-- PAGINATION FOOTER STARTS HERE -->
      <div>
    	<?php if(!empty($pagination)) { echo $pagination->getListFooter(); } ?>
      </div>
	  <!-- PAGINATION FOOTER ENDS HERE -->
      
<script>$=jQuery.noConflict();</script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/jquery-ui.min.js"></script>
<script src="<?php echo JURI::base() . 'components/com_facegallery/js/jquery.slimscroll.js' ?>" type="text/javascript"></script>
                    
                <!-- HIDDEN PREVIEW BLOCK -->
                <div id="image_preview" style="display: none">
                    <div class="image_wrp">
                        <img alt="close" class="close" 
                             src="<?php echo JURI::Base() . 'components/com_facegallery/images/close.gif' ?>" />
                        <div style="clear: both"></div>
                        <div class="pleft" ><?php echo JText::_('COM_FACEGALLERY_PREV'); ?></div>
                        <div class="pright"><?php echo JText::_('COM_FACEGALLERY_NEXT'); ?></div>
        <div style="clear: both"></div>
    </div>
</div>
<?php $user =JFactory::getUser(); ?>
<?php $imageId = JRequest::getInt('imgid'); ?>
<?php if($imageId != '' && $user->username !=''){?>
<script>
window.onload = function(){
	getPhotoPreviewAjx('<?php echo $imageId; ?>');
}
</script>
<?php }?>