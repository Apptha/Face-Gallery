<?php
/*
 **********************************************************************
 * @name            Face Gallery
 * @version         1.0: views/social/tmpl/default.php$
 * @since           Joomla 3.0
 * @package         apptha
 * @subpackage      com_facegallery
 * @author          Apptha - http://www.apptha.com
 * @copyright       Copyright (C) 2013 Powered by Apptha
 * @license 		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @creationDate    March 13 2013 
 **********************************************************************
 */

//  No direct access
defined('_JEXEC') or die('Restricted access');

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_facegallery/assets/css/facegallery.css');

// Folder creation to store images while upload
$folder         		= JPATH_ROOT . '/images/facegallery/';
$watermarkimgfolder  	= JPATH_ROOT . '/images/facegallery/watermark';
$sliderimgfolder		= JPATH_ROOT . '/images/facegallery/slider_image/';
$mediumimgfolder		= JPATH_ROOT . '/images/facegallery/medium_image/';
$thumbimgfolder 		= JPATH_ROOT . '/images/facegallery/thumb_image/';

if (!is_dir($folder))
{
    mkdir($folder);
    if(!file_exists($folder."index.html" ))
    {
        $fp = fopen($folder."index.html","w");
        $content = "<html><body></body></html>";
        fwrite($fp, $content, strlen($content));
        fclose($fp);
    }
}
if (!is_dir($watermarkimgfolder))
{
    	mkdir($watermarkimgfolder);
	    if(!file_exists($folder."index.html" ))
	    {
	        $fp = fopen($folder."index.html","w");
	        $content = "<html><body></body></html>";
	        fwrite($fp, $content, strlen($content));
	        fclose($fp);
	    }	
}
if (!is_dir($sliderimgfolder))
{
    mkdir($sliderimgfolder);
    if(!file_exists($sliderimgfolder."index.html" ))
    {
        $fp = fopen($sliderimgfolder."index.html","w");
        $content = "<html><body></body></html>";
        fwrite($fp, $content, strlen($content));
        fclose($fp);
    }
}
if (!is_dir($mediumimgfolder))
{
    mkdir($mediumimgfolder);
    if(!file_exists($mediumimgfolder."index.html" ))
    {
        $fp = fopen($mediumimgfolder."index.html","w");
        $content = "<html><body></body></html>";
        fwrite($fp, $content, strlen($content));
        fclose($fp);
    }
}
if (!is_dir($thumbimgfolder))
{
    mkdir($thumbimgfolder);
    if(!file_exists($thumbimgfolder."index.html" ))
    {
        $fp = fopen($thumbimgfolder."index.html","w");
        $content = "<html><body></body></html>";
        fwrite($fp, $content, strlen($content));
        fclose($fp);
    }
}

?>

<script type="text/javascript" src="js/prototype.js"></script>
<script type="text/javascript" src="js/scriptaculous.js?load=effects,builder"></script>

<form action="#" id="image-form" method="post" name="adminForm" id="adminForm">
	<?php if (!empty($this->sidebar)): ?>
	        <div id="j-sidebar-container" class="span2">
				<?php echo $this->sidebar; ?>
	        </div>
	        <div id="j-main-container" class="span10"/>
	<?php else : ?>
	            <div id="j-main-container"/>
	<?php endif; ?>
	
        <div class="row-fluid"/>
            <div class="span10 form-horizontal">
                <fieldset class="adminform">
                	<div class="tab-content"/>
                    	<div class="tab-pane active" id="details">
                    	
 							<!--  To display flickr import option -->                   	
                        	<div id="swfupload-control" class="control-group">
                            	<div class="control-label"> </div>
                                	<div  class="controls">
                                        <P> Import Flickr images using Flickr API Key and Flickr user ID from settings. <p/>
                                        	<span class="hasTip" title="Flickr Images Import Using Flickr API Key and Uer ID">
                                            	<a class="submit_button social-blue-btn" href="<?php echo JRoute::_('index.php?option=com_facegallery&view=social&task=Flickr'); ?>">Flickr Import</a>
                                            </span> 
                                        <p id="queuestatus" ></p>
                                            <ol id="log"></ol>
                                    </div>
                            </div>

							<!--  To display picasa import option -->  
                            <div id="swfupload-control" class="control-group" />
                            	<div class="control-label"> </div>
                                	<div  class="controls">
                                        <P> Import Google images using Google user ID in settings. <p/>
                                        	<span class="hasTip" title="Google Images Import Using Gmail User Name">
                                        		<a class="submit_button social-blue-btn" href="<?php echo JRoute::_('index.php?option=com_facegallery&view=social&task=Google'); ?>">Picasa Import</a>
                                        	</span>
                                    </div>
                                    <input type="hidden" name="fileoption" id="fileoption" value="" />
                            </div>
                   </fieldset>
                   <input type="hidden" name="task" value="social.flickrpicasa" />                    
            <?php echo JHtml::_('form.token'); ?>
           </div>
 </form>

