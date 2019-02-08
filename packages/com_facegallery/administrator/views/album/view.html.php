<?php
/*
 ********************************************************************
 * @name            Face Gallery
 * @version         1.0: view/album/view.html.php$
 * @since       	Joomla 3.0
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

jimport('joomla.application.component.view');

// View to edit 
class FacegalleryViewAlbum extends JViewLegacy
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
		parent::display($tpl);
	}

	// Add the page title and toolbar.
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user 	= JFactory::getUser();
        $userId	= $user->get('id');
		$isNew 	= ($this->item->id == 0);
        if (isset($this->item->checked_out))
        {
		    $checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
        }
        else
        {
            $checkedOut = false;
        }

		$canDo		= FacegalleryHelper::getActions();
	    JToolbarHelper::title($isNew ? JText::_('COM_FACEGALLERY_MANAGER_ALBUM_NEW') : JText::_('COM_FACEGALLERY_MANAGER_ALBUM_EDIT'), 'albums.png');

		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit')||($canDo->get('core.create'))))
		{
			JToolBarHelper::apply('album.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('album.save', 'JTOOLBAR_SAVE');
		}
		if (!$checkedOut && ($canDo->get('core.create')))
        {
			JToolBarHelper::custom('album.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
		}
		
		// If an existing item, can save to a copy.
		if (!$isNew && $canDo->get('core.create')) 
		{
			JToolBarHelper::custom('album.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		}
		if (empty($this->item->id)) 
		{
			JToolBarHelper::cancel('album.cancel', 'JTOOLBAR_CANCEL');
		}
		else 
		{
			JToolBarHelper::cancel('album.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
