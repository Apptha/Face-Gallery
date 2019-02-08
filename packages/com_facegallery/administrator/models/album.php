<?php
/*
 ********************************************************************
 * @name            Face Gallery
 * @version         1.0: models/album.php$
 * @since           Joomla 3.0
 * @package         apptha
 * @subpackage      com_facegallery
 * @author          Apptha - http://www.apptha.com
 * @copyright       Copyright (C) 2013 Powered by Apptha
 * @license 		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @creationDate    March 13 2013
 ********************************************************************
 */

// No direct access.
defined('_JEXEC') or die("restricted access");

jimport('joomla.application.component.modeladmin');

// Facegallery model.
class FacegalleryModelAlbum extends JModelAdmin
{
    protected $text_prefix = 'COM_FACEGALLERY';

    // Returns a reference to the a Table object, always creating it.
    public function getTable($type = 'album', $prefix = 'FacegalleryTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    // Method to get the record form.
    public function getForm($data = array(), $loadData = true)
    {
        // Initialise variables.
        $app = JFactory::getApplication();

        // Get the form.
        $form = $this->loadForm('com_facegallery.album', 'album', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form))
        {
            return false;
        }

        // Determine correct permissions to check.
        if ($this->getState('album.id'))
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
        $data = JFactory::getApplication()->getUserState('com_facegallery.edit.album.data', array());

        if (empty($data))
        {
            $data = $this->getItem();
        }
        return $data;
    }

    // Prepare and sanitise the table prior to saving.
    protected function prepareTable($table)
    {
        jimport('joomla.filter.output');
        $date = JFactory::getDate();
        if (empty($table->id))
        {
            // Set the values
            $table->created_on	= $date->toSql();

            // Set ordering to the last item if not set
            if (@$table->ordering == '')
            {
                $db = JFactory::getDbo();
                $db->setQuery('SELECT MAX(ordering) FROM #__facegallery_albums');
                $max = $db->loadResult();
                $table->ordering = $max + 1;
            }
            else
            {
                $table->updated_on = $date->toSql();
            }
        }
    }

    // Method to save new album record
    public function save($data)
    {
        // To save new album details into albums table        
        if($data['id']==0)
        {
        	$db = JFactory::getDBO();
            $album_name = $db->escape($data['album_name']);

            // To check whether the album name is already exists or not
            $query = "SELECT COUNT(album_name) FROM #__facegallery_albums WHERE album_name = '$album_name' ";
            $db->setQuery($query);
            $count = $db->loadResult();
            if($count == 0)
            {
                return parent::save($data);
            }
            else
            {
                //To display error message if album name is already exists
                $this->setError(JText::sprintf('COM_FACEGALLERY_FORM_ERROR_ALREADY_EXIST', JText::_('COM_FACEGALLERY_FORM_LBL_ALBUM_ALBUM_NAME')));
                return false;
            }
        }

        // To update album details into albums table
        else
        {
            return parent::save($data);
        }
    }   
}