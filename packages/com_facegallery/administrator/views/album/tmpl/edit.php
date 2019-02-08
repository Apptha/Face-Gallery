<?php
/*
 ********************************************************************
 * @name            Face Gallery
 * @version         1.0: view/album/tmpl/edit.php$
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
defined('_JEXEC') or die('Restricted access');

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
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
        if (task == 'album.cancel' || document.formvalidator.isValid(document.id('album-form'))) {
            Joomla.submitform(task, document.getElementById('album-form'));
        }
        else {
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_facegallery&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="album-form" class="form-validate">
    <div class="row-fluid">
        <div class="span10 form-horizontal">
           <fieldset class="adminform">

               <!-- Navigation Tabs -->
               <ul class="nav nav-tabs">
					<li class="active"><a href="#details" data-toggle="tab"><?php echo JText::_('Details');?></a></li>
                    <?php
						$fieldSets = $this->form->getFieldsets('meta_data');

						foreach ($fieldSets as $name => $fieldSet) :
					?>
					<li><a href="#metadata-<?php echo $name;?>" data-toggle="tab"><?php echo JText::_($fieldSet->label);?></a></li>
						<?php endforeach; ?>
			   </ul>

             <!-- Form Design -->
             <div class="tab-content">
                 <div class="tab-pane active" id="details">
                       	<div class="control-group">
                            <div class="control-label"><?php echo $this->form->getLabel('album_name'); ?></div>
                            <div class="controls"><?php echo $this->form->getInput('album_name'); ?></div>
                        </div>
                 		
                 		<div class="control-group">
                        	<div class="control-label"><?php echo $this->form->getLabel('alias_name'); ?></div>
                        	<div class="controls"><?php echo $this->form->getInput('alias_name'); ?></div>
                 		</div>
                 		
                 		<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('album_description'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('album_description'); ?></div>
		 				</div>
                 </div>
                 <?php echo $this->loadTemplate('metadata'); ?>
            </fieldset>
            <input type="hidden" name="task" value="" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
        
        <!-- Right Side Bar -->
        <div class="span2">
		<h4><?php echo JText::_('JDETAILS');?></h4>
		<hr />
            <!-- Display Album grid values -->
            <fieldset class="form-vertical">	
				<div class="control-group">
					<div class="controls">
						<?php echo $this->form->getValue('album_name'); ?>
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