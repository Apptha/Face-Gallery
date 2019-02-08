<?php
/*
 *********************************************************************
 * @name            Face Gallery
 * @version         1.0: view/image/tmpl/edit.php$
 * @since           Joomla 3.0
 * @package         apptha
 * @subpackage      com_facegallery
 * @author          Apptha - http://www.apptha.com
 * @copyright       Copyright (C) 2013 Powered by Apptha
 * @license 		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @creationDate    March 13 2013
 *********************************************************************
 */

// No direct access
defined('_JEXEC') or die("restricted access");

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_facegallery/assets/css/facegallery.css');

// Get facegallery component path
$baseurl    = JURI::base();
$path       = JURI::Base() . 'components/com_facegallery';
$editor     = JFactory::getEditor();
$k = 0;

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

<script type="text/javascript" src="<?php echo $path . '/js/swfupload/swfupload.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $path . '/js/jquery.swfupload.js'; ?>"></script>
<script type="text/javascript">
    Joomla.submitbutton = function(task)
    {
        if (task == 'image.cancel' || document.formvalidator.isValid(document.id('image-form'))) {
            Joomla.submitform(task, document.getElementById('image-form'));
        }
        else {
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
        }
    }
</script>

<?php
$user = JFactory::getUser();
//base path
$this->comPath = JURI::base();
//path for upload php
$filepath = urlencode(JPATH_SITE . "/images/facegallery/");

// Get settings information for images
$setting            = $this->settings;
$imageHeight        = $setting->image_height;
$imageWidth         = $setting->image_width;
$albumHeight        = $setting->album_height;
$albumWidth         = $setting->album_width;
$largeImageHeight   = $setting->large_image_height;
$largeImageWidth    = $setting->large_image_width;

// Passing parameters to uploadFile.php to upload images
$this->uploadfilePath = $this->comPath . 'components/com_facegallery/helpers/uploadFile.php?jpath=' . $filepath . '&th=' . $albumHeight . '&tw=' . $albumWidth . '&mh=' . $imageHeight . '&mw=' . $imageWidth . '&fh=' . $largeImageHeight . '&fw=' . $largeImageWidth;
$conf = JFactory::getConfig();
$secret = $conf->get('secret');

// Get album information to store images
$albumval = $this->albums;
$albumtot = count($albumval);

// Get images information for edit options
if ((JRequest::getVar('layout') == 'edit')  && ( JRequest::getVar('id')))
{
    $editId=(int)JRequest::getVar('id');
    $resultImages = $this->resultImage;
 }
?>

<!-- New and Edit Form Design -->
<form action="<?php echo JRoute::_('index.php?option=com_facegallery&layout=edit&id=' .(int) $this->item->id );?>" method="post" name="adminForm" id="image-form" enctype="multipart/form-data" class="form-validate">
    <div class="row-fluid">
        <div class="span10 form-horizontal">
            <fieldset class="adminform">
                <!-- Navigation Tabs -->
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#details" data-toggle="tab"><?php echo JText::_('Details'); ?></a></li>
                    <?php
                        $fieldSets = $this->form->getFieldsets('meta_data');
                        foreach ($fieldSets as $name => $fieldSet) :
                    ?>
                    <li><a href="#metadata-<?php echo $name; ?>" data-toggle="tab"><?php echo JText::_($fieldSet->label); ?></a></li>
                    <?php endforeach; ?>
                </ul>
                <?php echo $this->form->getInput('id'); ?>
                
                <!-- Form Design -->
                <div class="tab-content" id="upload_settings">
                	<div class="tab-pane active" id="details">
                        <div id="albumSelect" class="control-group">
                        	<div class="control-label"><?php echo $this->form->getLabel('albumid'); ?></div>
                            	<div class="controls">
                                	<select name="albumid" style="width: 200px" onchange="showUploader(this.value)">
                                    	<option value="0">--<?php echo JText::_('COM_FACEGALLERY_FORM_LBL_IMAGE_SELECT_ALBUM') ?>--</option>
                                           
                                            <?php  for ($i = 1; $i < $albumtot; $i++) {
                                            
                                            ?>
                                        <option value="<?php echo $albumval[$i]->id; ?>"
                                        		<?php if(isset($editId) && ($resultImages[0]->albumid == $albumval[$i]->id))
                                                		{
                                                        	echo 'selected';
                                                        }
                                                        else
                                                        {
                                                            echo '';
                                                        }
                                                 ?>
                                        >
                                        <?php echo $albumval[$i]->album_name; ?>
                                        </option>
                                        <?php } ?>
                                    </select>
                               </div>
                        </div>

                        <!-- Show upload button -->
                        <?php if (!isset($editId)){ ?>
                        <div id="swfupload-control" 
                        	<?php if(JRequest::getInt("albumid")=="") echo "style='display:none'";  ?> >
                            <div class="control-label">
                            	<?php echo $this->form->getLabel('upload_images'); ?>                                            
                            </div>
                            <div  class="controls">
                            	<p>Upload upto 20 image files(jpg, png, gif), each having maximum size of 1MB</p>
                                <input type="button" id="button" />
                                <p id="queuestatus" ></p>
                                <ol id="log"></ol>
                            </div>
                        </div>
                        <?php } ?>

                        <?php if (isset($editId))
                        		{                                        
                        ?>
                        <tr>
                        	<div class="control-group">
                            	<div class="control-label">
                                	<span class="editlinktip hasTip"
                                    		title="<?php  echo JText::_('COM_FACEGALLERY_FORM_DESC_IMAGE_IMAGE') ?>::
                                            <?php  echo JText::_('COM_FACEGALLERY_FORM_LBL_IMAGE_IMAGE') ?>">
                                            <?php  echo JText::_('COM_FACEGALLERY_FORM_LBL_IMAGE_IMAGE') ?>
                                            <font color="black">*</font>
                                    </span>
                                </div>
                                            
                                <div class="controls">
                                	<input style="width:300px" type="text" name="image" id="image"
                                    	   value="<?php if(isset($editId)) {echo $resultImages[0]->image;} ?>">
                                </div>
                           </div>
                        </tr>
                                
                        <tr>
	                        <div class="control-group">
	                        	<div class="control-label">
	                            	<span class="editlinktip hasTip"
	                                		title="<?php  echo JText::_('COM_FACEGALLERY_FORM_DESC_IMAGE_IMAGE_NAME') ?>::
	                                        <?php  echo JText::_('COM_FACEGALLERY_FORM_LBL_IMAGE_IMAGE_NAME') ?>">
	                                        <?php  echo JText::_('COM_FACEGALLERY_FORM_LBL_IMAGE_IMAGE_NAME') ?>
	                                        <font color="black">*</font>
	                                </span>
	                            </div>
	                                            
	                            <div class="controls">
	                            	<input style="width:300px" type="text" name="image_name" id="image_name"
	                                		value="<?php if(isset($editId)) {echo $resultImages[0]->image_name;} ?>" readonly="readonly">
	                            </div>
	                        </div>
	                    </tr>

                        <tr>
	                        <div class="control-group">
								<div class="control-label"><?php echo $this->form->getLabel('image_description'); ?></div>
								<div class="controls"><?php echo $this->form->getInput('image_description'); ?></div>
			 				</div>
		 				</tr>
						
					<div style="clear:both;"></div>
                    <div class="control-group" id="img_download">
                    	<div class="control-label"><?php echo $this->form->getLabel('download'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('download'); ?></div>
                    </div>
                    <?php  } ?>
                </div>
             <?php echo $this->loadTemplate('metadata'); ?>
             </fieldset>
             <input type="hidden" name="task" value="image.save();" />
             <?php echo JHtml::_('form.token'); ?>
         </div>

         <!-- Default joomla options -->
         <div class="span2">
         	<h4><?php echo JText::_('JDETAILS'); ?></h4>
            <hr />
            <fieldset class="form-vertical">
            	<div class="control-group">
                	<div class="controls">
                    	<?php echo $this->form->getValue('image'); ?>
                    </div>
                </div>
                    
                <div class="control-group">
                	<div class="control-label">
                		<?php echo $this->form->getLabel('state'); ?>
                    </div>
                    <div class="controls">
                    	<?php echo $this->form->getInput('state'); ?>
                    </div>
                </div>

                <div class="control-group">
                	<div class="control-label">
                    	<?php echo $this->form->getLabel('featured'); ?>
                    </div>
                    <div class="controls">
                    	<?php echo $this->form->getInput('featured'); ?>
                    </div>
                </div>

                <div class="control-group">
                	<div class="control-label">
                    	<?php echo $this->form->getLabel('language'); ?>
                    </div>
                    <div class="controls">
                    	<?php echo $this->form->getInput('language'); ?>
                    </div>
                </div>
            </fieldset>
        </div>
</form>

<!-- Script for image upload -->
<script type="text/javascript">
                var totalQueues;
                var QueueCountApptha = 0;

                jQuery(function(){
                    jQuery('#swfupload-control').swfupload({
                        upload_url: '<?php echo $this->uploadfilePath; ?>',
                        file_post_name: 'uploadfile',
                        file_size_limit : "1024",
                        file_types : "*.jpg;*.png;*.gif",
                        file_types_description : "Image files",
                        post_params: {"token" : "<?php echo md5($secret); ?>"},
                        file_upload_limit : 20,
                        flash_url : "<?php echo $path ?>/js/swfupload/swfupload.swf",
                        button_image_url : '<?php echo $path ?>/js/swfupload/wdp_buttons_upload_114x29.png',
                        button_width : 114,
                        button_height : 29,
                        "<?php echo $user->name; ?>":"<?php echo $user->id; ?>",
                        button_placeholder : jQuery('#button')[0],
                        debug: false
                   })

                   .bind('fileQueued', function(event, file){
                        var listitem='<li id="'+file.id+'" >'+
                            'File: <em>'+file.name+'</em> ('+Math.round(file.size/1024)+' KB) <span class="progressvalue" ></span>'+
                            '<div class="progressbar" ><div class="progress" ></div></div>'+
                            '<p class="status" >Pending</p>'+
                            '<span class="cancel" >&nbsp;</span>'+
                            '</li>';
                        jQuery('#log').append(listitem);

                        jQuery('li#'+file.id+' .cancel').bind('click', function(){
                            var swfu = jQuery.swfupload.getInstance('#swfupload-control');
                            swfu.cancelUpload(file.id);
                            jQuery('li#'+file.id).slideUp('fast');
                        });
                        // start the upload since it's queued
                        jQuery(this).swfupload('startUpload');
                   })

                   .bind('fileQueueError', function(event, file, errorCode, message){
                        alert('Size of the file '+file.name+' is greater than limit');
                   })

                   .bind('fileDialogComplete', function(event, numFilesSelected, numFilesQueued){
                        totalQueues  = numFilesQueued;
                        jQuery('#queuestatus').text('Files Selected: '+numFilesSelected+' / Queued Files: '+QueueCountApptha);
                   })

                   .bind('uploadStart', function(event, file){
                        jQuery('#log li#'+file.id).find('p.status').text('Uploading...');
                        jQuery('#log li#'+file.id).find('span.progressvalue').text('0%');
                        jQuery('#log li#'+file.id).find('span.cancel').hide();
                   })

                   .bind('uploadProgress', function(event, file, bytesLoaded){
                        //Show Progress
                        var percentage=Math.round((bytesLoaded/file.size)*100);
                        jQuery('#log li#'+file.id).find('div.progress').css('width', percentage+'%');
                        jQuery('#log li#'+file.id).find('span.progressvalue').text(percentage+'%');
                   })

                   .bind('uploadSuccess', function(event, file, serverData){
                        appendHtmlfile(serverData,file);

                        var item=jQuery('#log li#'+file.id);
                        QueueCountApptha++;
                        item.find('div.progress').css('width', '100%');
                        item.find('span.progressvalue').text('100%');
                        var pathtofile='<a href="<?php echo JURI::root() . "images/facegallery"; ?>/'+file.name+'" target="_blank" >view &raquo;</a>';
                        item.addClass('success').find('p.status').html('Done!!! ');
                        jQuery('#queuestatus').text('Files Selected: '+totalQueues+' / Queued Files: '+QueueCountApptha);

                   })

                   .bind('uploadComplete', function(event, file){
                        // upload has completed, try the next one in the queue
                        jQuery(this).swfupload('startUpload');
                   })

                });
                var fileCount = 0;
                function appendHtmlfile(serverData,file){
                    var filename =  file.name.split(".");
                    var html = "<fieldset class='section_grid' id='"+file.id+fileCount+"' ><legend>"+file.name+"</legend><div align='right' style='cursor:pointer' onclick=\"removeFieldSet('"+file.id+fileCount+"')\"><img title='Remove'  style='float:right' width='16' height='16' src='<?php echo JURI::base() . "components/com_facegallery/js/swfupload/cancel.png" ?>' /></div><table id='upload_image'><tr><td valign='middle'><label>Image Name</label></td><td valign='top'><input type='text' style='width:200px;' name='image[]' value='"+filename[0]+"'  /> <input type='hidden' name='image_name[]' value='"+serverData+"'/></td></tr>";
                    html += "<tr><td valign='middle' style='width:100px'><label>Description</label></td><td valign='top'><textarea name='image_description[]' style='height:50px;font-size:12px;width:200px'></textarea></td>";
                    html += "<td valign='bottom'><img class='thumb_img' style= 'max-width:none!important;width:50px;height:50px;' src='<?php echo JURI::root() . "images/facegallery/thumb_image/"; ?>"+serverData+"' /></td></tr>";
                    html += "</table></fieldset>";
                    jQuery("#swfupload-control").append(html);
                    fileCount ++;
                }

                function showUploader(value){
                    if(value!="0"){
                        jQuery("#swfupload-control").show();
                    }
                    else{
                        jQuery("#swfupload-control").hide();
                    }
                }

                function removeFieldSet(id){
                       jQuery("#"+id).remove();
                }
</script>

<!-- Style sheet for upload images-->
<style type="text/css" >
#swfupload-control{margin-bottom:5px;}
    #swfupload-control p{ margin:0; font-size:0.9em;  padding:0 0 5px 0}
    #log{ margin:0; padding:0; width:500px;}
    #log li{ list-style-position:inside; margin:2px; border:1px solid #ccc; padding:10px; font-size:12px;
             font-family:Arial, Helvetica, sans-serif; color:#333; background:#fff; position:relative;}
    #log li .progressbar{ border:1px solid #333; height:5px; background:#fff; }
    #log li .progress{ background:#999; width:0%; height:5px; }
    #log li p{ margin:0; line-height:18px; }
    #log li.success{ border:1px solid #339933; background:#ccf9b9; }
    #log li span.cancel{ position:absolute; top:5px; right:5px; width:20px; height:20px;
                         background:url('js/swfupload/cancel.png') no-repeat; cursor:pointer; }
    #swfupload-control table tr td{font-size: 1.091em;	}
    
    #upload_settings .control-group{margin-bottom:10px;}
</style>