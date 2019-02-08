<?php
/*
 *********************************************************************
 * @name        	Face Gallery
 * @version			1.0: models/comment.php$
 * @since       	Joomla 3.0
 * @package			apptha
 * @subpackage		com_facegallery
 * @author      	Apptha - http://www.apptha.com
 * @copyright   	Copyright (C) 2013 Powered by Apptha
 * @license 		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @creationDate    March 13 2013
 *********************************************************************
 */

// No direct access.
defined('_JEXEC') or die("restricted access");

jimport('joomla.application.component.modeladmin');

// Facegallery model.
class FacegalleryModelComment extends JModelAdmin
{
    protected $text_prefix = 'COM_FACEGALLERY';

    // Returns a reference to the a Table object, always creating it.
    public function getTable($type = 'Comment', $prefix = 'FacegalleryTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    // Method to get the record form.
    public function getForm($data = array(), $loadData = true)
    {
        // Initialise variables.
        $app = JFactory::getApplication();

        // Get the form.
        $form = $this->loadForm('com_facegallery.comment', 'comment', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form))
        {
            return false;
        }

        // Determine correct permissions to check.
        if ($this->getState('comment.id'))
		{
			// Existing record. Can only edit in selected albums.
			$form->setFieldAttribute('id', 'action', 'core.edit');
		}
		else
		{
			// New record. Can only create in selected albums.
			$form->setFieldAttribute('id', 'action', 'core.create');
		}
	
		// Modify the form based on access controls.
		if (!$this->canEditState((object) $data))
		{
			// Disable fields for display.
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('publish_up', 'disabled', 'true');
			$form->setFieldAttribute('publish_down', 'disabled', 'true');
			$form->setFieldAttribute('state', 'disabled', 'true');
	
	
	                // Disable fields while saving. The controller has already verified this is a record you can edit.
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('publish_up', 'filter', 'unset');
			$form->setFieldAttribute('publish_down', 'filter', 'unset');
			$form->setFieldAttribute('state', 'filter', 'unset');
		}
        return $form;
    }

    // Method to get the data that should be injected in the form.
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_facegallery.edit.comment.data', array());

        if (empty($data))
        {
            $data = $this->getItem();
        }
        return $data;
    }
}