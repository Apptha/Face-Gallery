<?php
/*
 *********************************************************************
 * @name            Face Gallery
 * @version         1.0: view/image/tmpl/edit_metadata.php$
 * @since           Joomla 3.0
 * @package         apptha
 * @subpackage      com_facegallery
 * @author          Apptha - http://www.apptha.com
 * @copyright       Copyright (C) 2013 Powered by Apptha
 * @license 		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @creationDate    March 13 2013
 *********************************************************************
 */

//No diect access
defined('_JEXEC') or die("restricted access");

$fieldSets = $this->form->getFieldsets('meta_data');
foreach ($fieldSets as $name => $fieldSet) :
?>
	<div class="tab-pane" id="metadata-<?php echo $name;?>">
	<?php
	if (isset($fieldSet->description) && trim($fieldSet->description)) :
		echo '<p class="alert alert-info">'.$this->escape(JText::_($fieldSet->description)).'</p>';
	endif;
	?>
			<?php if ($name == 'jmetadata') : // Include the real fields in this panel.
			?>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('meta_description'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('meta_description'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('meta_keywords'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('meta_keywords'); ?></div>
				</div>				
			<?php endif; ?>			
	</div>
<?php endforeach; ?>
