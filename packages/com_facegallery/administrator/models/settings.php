<?php
/*
 ********************************************************************
 * @name            Face Gallery
 * @version         1.0: models/settings.php$
 * @since           Joomla 3.0
 * @package         apptha
 * @subpackage      com_facegallery
 * @author          Apptha - http://www.apptha.com
 * @copyright       Copyright (C) 2013 Powered by Apptha
 * @license 		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @creationDate    March 13 2013
 *********************************************************************
 */

// No direct access.
defined('_JEXEC') or die("restricted access");

jimport('joomla.application.component.modeladmin');

// Facegallery model.
class FacegalleryModelSettings extends JModelAdmin
{
    protected $text_prefix = 'COM_FACEGALLERY';

    // Returns a reference to the a Table object, always creating it.
    public function getTable($type = 'Settings', $prefix = 'FacegalleryTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    // Method to get the record form.
    public function getForm($data = array(), $loadData = true)
    {
        // Initialise variables.
        $app = JFactory::getApplication();

        // Get the form.
        $form = $this->loadForm('com_facegallery.settings', 'settings', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form))
        {
            return false;
        }
        return $form;
    }

    public function update($data)
    {	
		$res = $data['jform'];
         $db = JFactory::getDbo();
        // Update new settings information into settings table
        $db->setQuery('UPDATE #__facegallery_settings SET '
                . 'image_height= ' . (int) $res['image_height'] . ', '
                . 'image_width = ' . (int) $res['image_width'] . ','
                . 'large_image_height = ' . (int) $res['large_image_height'] . ', '
                . 'large_image_width = ' . (int) $res['large_image_width'] . ', '
                . 'album_height = ' . (int) $res['album_height'] . ', '
                . 'album_width = ' . (int) $res['album_width'] . ', '
                . 'album_row = ' . (int) $res['album_row'] . ', '
                . 'album_column = ' . (int) $res['album_column'] . ', '
                . 'image_pagination = ' . (int) $res['images_pagination'] . ', '
                . 'album_pagination = ' . (int) $res['albums_pagination'] . ', '
                . 'display_views = ' . (int) $res['display_views'] . ', '                
                . 'moderate_comments = ' . (int) $res['moderate_comments'] . ', '
                . 'sharing = ' . (int) $res['sharing'] . ', '
                . 'flickr_key = "' . $res['flickr_key'] . '", '
                . 'flickr_secret = "' .  $res['flickr_secret'] . '", '
                . 'flickr_user_id = "' .  $res['flickr_user_id'] . '", '
                . 'picasa_user_name= "' .  $res['picasa_user_name'] . '", '
                . 'facebook_api = "' . $res['facebook_api'] . '", '
                . 'license= "' .  $res['license'] . '", '
                . 'download =  ' . (int) $res['download'] . ' '
                . 'WHERE id= 1');
        $db->query();
    }

    // Method to get the data that should be injected in the form.
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_facegallery.edit.settings.data', array());

        if (empty($data))
        {
            $data = $this->getItem();
        }
        return $data;
    }

    public static function getSettings()
    {
        // Get database connection
        $db = JFactory::getDbo();

        // Get the settings value from the settings table
        $db->setQuery("SELECT  `id`, `image_height`, `image_width`,
                                `large_image_height`, `large_image_width`,
                                `album_height`, `album_width`,
                                `album_row`, `album_column`,`image_pagination`,
                                `created_on`, `updated_on`,
                                `display_views`, `album_pagination`,
                                `moderate_comments`,`sharing`, `download`,
                                `flickr_key`, `flickr_secret`, `flickr_user_id`,
                                `picasa_user_name`,`facebook_api`,`license`
                       FROM  `#__facegallery_settings`
                       WHERE 1");

        // Store result as array
        $data = $db->loadObject();
      //  print_r($data);
        return $data;
    } 
}