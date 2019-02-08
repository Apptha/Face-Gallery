<?php
/*
 ********************************************************************
 * @name            Face Gallery
 * @version         1.0: models/albums.php$
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
defined('_JEXEC') or die("restricted access");

jimport('joomla.application.component.modellist');

// Methods supporting a list of facegallery records.
class FacegalleryModelAlbums extends JModelList
{
    // Constructor.
    public function __construct($config = array())
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                'id', 'a.id',                
                'album_name', 'a.album_name',
                'alias_name', 'a.alias_name',
                'album_description', 'a.album_description',
                'created_on', 'a.created_on',
                'updated_on', 'a.updated_on',
                'total_images', 'total_images',
                'featured', 'a.featured',
                'access', 'a.access',
                'ordering', 'a.ordering',
                'language', 'a.language',
                'state', 'a.state',
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

        $state = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string');
        $this->setState('filter.state', $state);

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

        // Select the required fields from the albums table.
        $query->select(
                $this->getState(
                        'list.select',
                        'a.id As id, a.album_name As album_name, a.alias_name As alias_name,'.
                        'a.album_description As album_description, a.created_on As created_on,'.
                        'a.state As state,a.featured As featured,a.language As language,'.
                        'a.ordering As ordering,'.
                        '(SELECT COUNT(temp.id) FROM #__facegallery_images temp WHERE temp.albumid = a.id AND temp.state=1) AS total_images'
                )
        );

        $query->from('`#__facegallery_albums` AS a');       
        
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
			} 
			else 
			{
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(a.album_name LIKE '.$search.' OR a.alias_name LIKE '.$search.')');
			}
		}
	
	    // Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'ordering');
		$orderDirn	= $this->state->get('list.direction', 'ASC');
		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}
		return $query;
    }

    // Triggered when featured image is set
     function setFeatured()
     {        
        $cid = JRequest::getVar('cid', array(0), 'get', 'array');
        $fset = JRequest::getVar('fset', array(0), 'get', 'int');
        $db =  JFactory::getDBO();

        //To set selected album as unfeatured
        if ($fset == 0) 
        {
            $query = "update #__facegallery_albums set featured=0 WHERE id = '$cid[0]'";
            $db->setQuery($query);
            $db->query();
        }

        //To set selected album as featured
        else if ($fset == 1) 
        {
            $query = "update #__facegallery_albums set featured=1 WHERE id = '$cid[0]'";
            $db->setQuery($query);
            $db->query();
        }

        $app = JFactory::getApplication();
        $app->redirect('index.php?option=com_facegallery&view=albums');
    }
}
