<?php
/*
 ********************************************************************
 * @name            Face Gallery
 * @version         1.0: models/images.php$
 * @since           Joomla 3.0
 * @package         apptha
 * @subpackage      com_facegallery
 * @author          Apptha - http://www.apptha.com
 * @copyright       Copyright (C) 2013 Powered by Apptha
 * @license 		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @creationDate    March 13 2013
 *********************************************************************
 */

//No direct access
defined('_JEXEC') or die("restricted access");

jimport('joomla.application.component.modellist');

// Methods supporting a list of facegallery records.
class FacegalleryModelimages extends JModelList 
{
    // Constructor.
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) 
        {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'albumid','a.albumid',
                'image','a.image',
                'image_name', 'a.image_name',
                'album_name','b.album_name',
            	'upload_option','a.upload_option',
                'image_description', 'a.image_description',
                'created_on', 'a.created_on',
                'updated_on', 'a.updated_on',
                'cover_image', 'a.cover_image',
                'thumb_image', 'a.thumb_image',
                'slider_image','a.slider_image',
            	'watermark_image','a.watermark_image',
                'featured', 'a.featured',
                'access', 'a.access',
                'language', 'a.language',
                'views', 'a.views',
                'rate_count', 'a.rate_count',
                'rated_users', 'a.rated_users',
                'state', 'a.state',
                'ordering', 'a.ordering'                
            );
        }
        parent::__construct($config);
    }

    // Method to auto-populate the model state.
    protected function populateState($ordering = null, $direction = null)
    {
        // Initialise variables.
        $app = JFactory::getApplication('administrator');

        // Load the filter state.
        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
        $this->setState('filter.state', $published);

        // Load the parameters.
        $params = JComponentHelper::getParams('com_facegallery');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.id', 'asc');
    }

    // Method to get a store id based on model configuration state.
    protected function getStoreId($id = '')
    {
        // Compile the store id.
        $id.= ':' . $this->getState('filter.search');
        $id.= ':' . $this->getState('filter.state');
        return parent::getStoreId($id);
    }

    // Build an SQL query to load the list data.
    protected function getListQuery()
    {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
                    $this->getState('list.select',
                            'a.id AS id, a.albumid AS albumid, a.image AS image,'.
                            'a.image_name As image_name, a.image_description AS image_description,'.
                            'a.state AS state, a.created_on AS created_on, a.thumb_image AS thumb_image,'.
                            'a.slider_image AS slider_image, a.watermark_image AS watermark_image, a.cover_image AS cover_image,'.
                            'a.featured AS featured, a.access AS access,'.
                            'a.language AS language, a.upload_option AS upload_option,'.
                            'a.download AS download, a.ordering As ordering,'.
                            'a.meta_keywords AS meta_keywords, a.meta_description AS meta_description'
                             )   
                     );
         
        $query->from('`#__facegallery_images` AS a');

        //select album name from albums table
        $query->select('b.album_name AS album_name');
		$query->join('LEFT', $db->quoteName('#__facegallery_albums').' AS b ON a.albumid = b.id');

        $query->where('(b.state IN (0, 1))');
        
        // Filter by published state
        $published = $this->getState('filter.state');
        if (is_numeric($published))
        {
            $query->where('a.state = ' . (int) $published);
        } 
        else if ($published === '')
        {
            $query->where('(a.state IN (0, 1))');
        }

        // Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(a.image_name LIKE '.$search.' )');
			}
		}

        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');
        if ($orderCol && $orderDirn) {
            $query->order($db->escape($orderCol . ' ' . $orderDirn));
        }
        return $query; 
    }

    // Triggered when album cover image is set
     function setImage()
     {
        $cid = JRequest::getVar('cid', array(0), 'get', 'array');
        $aid = JRequest::getVar('albumid', array(0), 'get', 'int');
        $set = JRequest::getVar('set', array(0), 'get', 'int');
        $db =  JFactory::getDBO();

        if ($set == 1)
        {
            $query1 = "update  #__facegallery_images set cover_image=0 WHERE albumid='$aid'";
            $query = "update #__facegallery_images set cover_image=1 WHERE id = '$cid[0]' and albumid='$aid'";

            $db->setQuery($query1);
            $db->query();

            $db->setQuery($query);
            $db->query();
        }

        else if ($set == 0)
        {
            $albumid = JRequest::getVar('albumid');
            $albumquery = "SELECT MIN(id) as id FROM #__facegallery_images WHERE albumid =".$albumid;
            $db->setQuery($albumquery);
            $result = $db->loadResult();

            $query = "update  #__facegallery_images set cover_image=0 WHERE id = '$cid[0]' and albumid='$aid'";
            $query1 = "update  #__facegallery_images set cover_image=1 WHERE id = '$result' and albumid='$albumid'";
            $db->setQuery($query);
            $db->query();
            $db->setQuery($query1);
            $db->query();
        }
        $app = JFactory::getApplication();
        $app->redirect('index.php?option=com_facegallery&view=images');
    }

     // Triggered when featured image is set
     function setFeatured()
     {
        $cid = JRequest::getVar('cid', array(0), 'get', 'array');        
        $fset = JRequest::getVar('fset', array(0), 'get', 'int');
        $db =  JFactory::getDBO();

        //To set selected image as unfeatured
        if ($fset == 0)
        {
            $query = "update #__facegallery_images set featured=0 WHERE id = '$cid[0]'";
            $db->setQuery($query);
            $db->query();
        }

        //To set selected image as featured
        else if ($fset == 1)
        {
            $query = "update #__facegallery_images set featured=1 WHERE id = '$cid[0]'";
            $db->setQuery($query);
            $db->query();
        }
        
        $app = JFactory::getApplication();
        $app->redirect('index.php?option=com_facegallery&view=images');
    }
}
