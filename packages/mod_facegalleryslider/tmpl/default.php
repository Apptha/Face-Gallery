<?php
/*
 * ************************************************************************
 * @name            Face Gallery Slider
 * @version         1.0: tmpl/default.php$
 * @since           Joomla 3.0
 * @package         apptha
 * @subpackage      mod_facegallery
 * @author          Apptha - http://www.apptha.com
 * @copyright       Copyright (C) 2013 Powered by Apptha
 * @license 		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @creationDate    March 13 2013
 * *************************************************************************
 */

//No Direct Access
defined('_JEXEC') or die('Restricted access');
?>
<!--Include required css and js files for Slider Transition-->
<link href="modules/mod_facegalleryslider/css/skitter.styles.css" type="text/css" media="all" rel="stylesheet" />
<script type="text/javascript">
    $= jQuery.noConflict();
</script>
<script type="text/javascript" src="http://www.skitter-slider.net/js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="http://www.skitter-slider.net/js/jquery.skitter.js"></script>

<script type="text/javascript">
    var box_skitter_large = null;
    $(document).ready(function() {
        $('.box_skitter_large').skitter({
        	animation:"<?php echo ($sliderStyle == "")?"random":$sliderStyle; ?>",
            interval: <?php echo ($sliderTime == "")?"5000":$sliderTime; ?>,
            numbers_align: "center",
            dots: true,
            preview: true,
            focus: true,
            focus_position: "leftTop",
            controls: true,
            controls_position: "leftTop",
            progressbar: false,            
            animateNumberOver: { 'backgroundColor':'#555' },
            enable_navigation_keys: true,
            onLoad: function(self) {
                if (this.thumbs) $('.border-skitter').height(350);
                box_skitter_large = self;
            }
        });
    });
</script>
<style type ="text/css">
    .box_skitter_large {width:<?php echo $sliderSize->large_image_width?>px;height:<?php echo $sliderSize->large_image_height?>px;}
    .box_skitter_small {width:400px;height:200px;}
</style>
<!-- End the Animation Slider-->
<?php


$doc = JFactory::getDocument();
?>

<!-- Get Slider Show Images-->
<div id="page">
    <div id="header"></div>

    <div id="banner_content">
        <div class="border_box">
            <div class="box_skitter box_skitter_large">
                <ul>

                    <?php
                    foreach ($bannerDetails as $bannerDetail) {
                        $description = $bannerDetail->image_description;
                        $descLength = strlen($description);
                        $string = strip_tags($description);
                        $stringCut = substr($string, 0, 25);
                    ?>

                        <li>
                            <img alt="<?php echo $bannerDetail->slider_image;  ?>" width="700"
                                 src="<?php echo JURI::Base() . 'images/facegallery/slider_image/' . $bannerDetail->slider_image; ?>"
                                 id="'<?php echo 'img_' . $bannerDetail->id ?>" />
                            <div class="label_text">
                                <p class="banner-imagetitle"><?php echo $bannerDetail->image; ?></p>
                                <?php if($description)
                                    {
                                    if($descLength > 25) 
                                        {
                                        ?>
                                    <p class="banner-imagedesc"><?php echo $stringCut . '...'; ?> </p>
                                <?php 
                                }
                                else
                                    { ?>
                                 <p class="banner-imagedesc"><?php echo $stringCut; ?> </p>
                                 <?php }

                             } ?>
                              </div>
                    </li>
                    
                    <?php }?>
                </ul>
            </div>
        </div>
    </div>
    <div style="clear: both"></div>
</div>

