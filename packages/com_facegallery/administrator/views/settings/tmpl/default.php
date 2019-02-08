<?php
/*
 *********************************************************************
 * @name            FaceGallery
 * @version         1.0: view/settings/default.php$
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
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_facegallery/assets/css/facegallery.css');

?>

<script type="text/javascript">
    Joomla.submitbutton = function(task)
    {
        if (task == 'settings.cancel' || document.formvalidator.isValid(document.id('settings-form'))) 
        {
            Joomla.submitform(task, document.getElementById('settings-form'));
        }
        else 
        {
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
        }
    }
</script>

<?php
if (!empty($this->extra_sidebar))
{
    $this->sidebar = $this->extra_sidebar;    
}
// Get settings information
$settings=$this->settings;
?>

<form action="<?php echo JRoute::_('index.php?option=com_facegallery&view=settings'); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="settings-form" class="form-validate">
    <?php if (!empty($this->sidebar)): ?>
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
    <?php else : ?>
       <div id="j-main-container">
    <?php endif; ?>
    
        <div class="span10 form-horizontal">
            <fieldset class="adminform">
                <?php echo $this->form->getInput('id'); ?>
                <div id="settings_table">
                
	                <!-- API Settings -->
	                <div class="col_1">
		                <h3>API Settings</h3>
		                <table cellpadding="0" cellspacing="0" border="0">
		                	<tr>
				                <td class="first_col"> <?php echo $this->form->getLabel('flickr_key'); ?></td>
				                <td><?php echo $this->form->getInput('flickr_key','', $settings->flickr_key); ?></td>
		                	</tr>	                
		                	<tr>
				                <td class="first_col"><?php echo $this->form->getLabel('flickr_secret'); ?></td>
				                <td><?php echo $this->form->getInput('flickr_secret','', $settings->flickr_secret); ?></td>
			                </tr>
			                <tr>
				                <td class="first_col"><?php echo $this->form->getLabel('flickr_user_id'); ?></td>
				                <td><?php echo $this->form->getInput('flickr_user_id','', $settings->flickr_user_id); ?></td>
			                </tr>
			                <tr>
				                <td class="first_col"><?php echo $this->form->getLabel('picasa_user_name'); ?></td>
				                <td><?php echo $this->form->getInput('picasa_user_name','', $settings->picasa_user_name); ?></td>
			                </tr>
			                <tr>
				                <td class="first_col"><?php echo $this->form->getLabel('facebook_api'); ?></td>
				                <td><?php echo $this->form->getInput('facebook_api','', $settings->facebook_api); ?></td>
			                </tr>
			            </table>
	                </div>               
                 	<!-- API Settings End-->
                 
                 
                  	<!-- Album Settings -->
                 	<div class="col_2">
	                	<h3>Album Settings</h3>
	                	<table cellpadding="0" cellspacing="0" border="0">
		                	<tr>
			                	<td class="first_col"><?php echo $this->form->getLabel('album_height'); ?></td>
			                	<td><?php echo $this->form->getInput('album_height','', $settings->album_height); ?></td>
		                	</tr>		                
			                <tr>
				                <td class="first_col"><?php echo $this->form->getLabel('album_width'); ?></td>
				                <td><?php echo $this->form->getInput('album_width','', $settings->album_width); ?></td>
			                </tr>
			                <tr>
				                <td class="first_col"><?php echo $this->form->getLabel('album_row'); ?></td>
				                <td><?php echo $this->form->getInput('album_row','', $settings->album_row); ?></td>
			                </tr>
			                <tr>
				                <td class="first_col"><?php echo $this->form->getLabel('album_column'); ?></td>
				                <td><?php echo $this->form->getInput('album_column','', $settings->album_column); ?></td>
			                </tr>
			                <tr>
				                <td class="first_col"><?php echo $this->form->getLabel('albums_pagination'); ?></td>
				                <td><?php echo $this->form->getInput('albums_pagination','', $settings->album_pagination); ?></td>
			                </tr>
		                </table>
	                </div>
                  	<!-- Album Settings End-->
                  
                 	<div style="clear:both;"></div>
                 
	                <!-- Image Settings -->
	                <div class="col_3">
		                <h3>Image Settings</h3>
		                <table cellpadding="0" cellspacing="0" border="0">
			                <tr>                
			                	<td colspan="2"><h5  class="first_child">Small Image</h5></td>                
			                </tr>
			                <tr>                
				                <td class="first_col"><?php echo $this->form->getLabel('image_height'); ?> </td>
				                <td><?php echo $this->form->getInput('image_height','', $settings->image_height); ?></td>
			                </tr>
			                <tr>
				                <td class="first_col"><?php echo $this->form->getLabel('image_width'); ?></td>
				                <td><?php echo $this->form->getInput('image_width','', $settings->image_width); ?></td>
			                </tr>		                
			                <tr>
				                <td class="first_col"><?php echo $this->form->getLabel('images_pagination'); ?></td>
				                <td><?php echo $this->form->getInput('images_pagination','', $settings->image_pagination); ?></td>
			                </tr>
			                
			                <tr>
			                	<td  colspan="2"><h5>Slider Image</h5></td>                
			                </tr>			                
			                <tr>
				                <td class="first_col"><?php echo $this->form->getLabel('large_image_height'); ?></td>
				                <td><?php echo $this->form->getInput('large_image_height','', $settings->large_image_height); ?></td>
			                </tr>			                
			                <tr>
				                <td class="first_col"><?php echo $this->form->getLabel('large_image_width'); ?></td>
				                <td><?php echo $this->form->getInput('large_image_width','', $settings->large_image_width); ?></td>
			                </tr>  
		                </table>
		             </div>
                 	 <!-- Image Settings End-->
                 	 
                 	<!-- General Settings -->
	                <div class="col_4">
		                <h3>General Settings</h3>
		                	<table cellpadding="0" cellspacing="0" border="0">
		                <tr>
			                <td class="first_col"><?php echo $this->form->getLabel('display_views'); ?></td>
			                <td><?php echo $this->form->getInput('display_views','', $settings->display_views); ?></td>
		                </tr>                
		                <tr>
			                <td class="first_col"><?php echo $this->form->getLabel('sharing'); ?></td>
			                <td><?php echo $this->form->getInput('sharing','', $settings->sharing); ?></td>
		                </tr>		              
		                <tr>
			                <td class="first_col"><?php echo $this->form->getLabel('moderate_comments'); ?></td>
			                <td><?php echo $this->form->getInput('moderate_comments','', $settings->moderate_comments); ?></td>
		                </tr>
		                <tr>
			                <td class="first_col"><?php echo $this->form->getLabel('download'); ?></td>
			                <td><?php echo $this->form->getInput('download','', $settings->download); ?></td>
		                </tr>
		                </table>
	                </div>
	                <!-- General Settings End-->                
                </div>                               
            </fieldset>
        </div>
        <input type="hidden" name="task" value="" />       
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>