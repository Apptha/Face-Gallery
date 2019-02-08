<?php
/*
 *********************************************************************
 * @name            Face Gallery
 * @version         1.0: view/image/view.html.php$
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

jimport('joomla.application.component.view');

// View to edit 
class FacegalleryViewImage extends JViewLegacy
{
	protected $state;
	protected $item;
	protected $form;

	// Display the view
	public function display($tpl = null)
	{
		$this->state = $this->get('State');               
		$this->item = $this->get('Item');
		$this->form = $this->get('Form');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
                {
                     throw new Exception(implode("\n", $errors));
		}
		$this->addToolbar();

        // Get Settings table information
        $model = $this->getModel();
        $settings = $model->getSettings();
        $this->assignRef('settings', $settings);

        // Get Album table information
        $albumNames = $model->getAlbumNames();
        $this->assignRef('albums', $albumNames);

        // Get Image table information for edit options
        $id=(int)JRequest::getVar('id');
        $resultImages = $model->getimages($id);
        $this->assignRef('resultImage', $resultImages);
                
		parent::display($tpl);
	}

	// Add the page title and toolbar.
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user = JFactory::getUser();
		$isNew = ($this->item->id == 0);
        if (isset($this->item->checked_out))
        {
		    $checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
        }
        else
        {
        	$checkedOut = false;
        }

		$canDo		= facegalleryHelper::getActions();
	    JToolbarHelper::title($isNew ? JText::_('COM_FACEGALLERY_MANAGER_IMAGE_NEW') : JText::_('COM_FACEGALLERY_MANAGER_IMAGE_EDIT'), 'images.png');
		
		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit')||($canDo->get('core.create'))))
		{
			JToolBarHelper::save('image.save', 'JTOOLBAR_SAVE');
		}
		if (!$checkedOut && ($canDo->get('core.create')))
        {
			JToolBarHelper::custom('image.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
		}
		
		// If an existing item, can save to a copy.
		if (!$isNew && $canDo->get('core.create'))
        {
			JToolBarHelper::custom('image.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		}
		if (empty($this->item->id))
        {
			JToolBarHelper::cancel('image.cancel', 'JTOOLBAR_CANCEL');
		}
		else
        {
			JToolBarHelper::cancel('image.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}