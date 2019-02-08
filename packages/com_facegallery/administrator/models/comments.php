<?php
/*
 *********************************************************************
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
class FacegalleryModelComments extends JModelList 
{
    // Constructor.
    public function __construct($config = array()) 
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'comment_item_id', 'a.comment_item_id',
                'thumb_image', 'b.thumb_image',
                'state', 'a.state',
                'comment_ip', 'a.comment_ip',
                'comment_name', 'a.comment_name',
                'comment_text', 'a.comment_text',                
                'created_on', 'a.created_on',                            
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

        $language = $this->getUserStateFromRequest($this->context . '.filter.language', 'filter_language', '');
        $this->setState('filter.language', $language);

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
        $id.= ':' . $this->getState('filter.access');
        $id.= ':' . $this->getState('filter.language');

        return parent::getStoreId($id);
    }

    // Build an SQL query to load the list data.
    protected function getListQuery()
    {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the comments table.
        $query->select(
                $this->getState(
                        'list.select',
                        'a.id As id,a.comment_name As comment_name,' .
                		'a.comment_item_id As comment_item_id,' .
                        'a.comment_text As comment_text, a.created_on As created_on,' .
                        'a.state As state'
                )
        );

        $query->from('`#__facegallery_comments` AS a');
        
        //select image name from albums table
        $query->select('b.thumb_image AS thumb_image');
		$query->join('LEFT', $db->quoteName('#__facegallery_images').' AS b ON a.comment_item_id = b.id');

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
                $query->where('a.id = ' . (int) substr($search, 3));
            } 
            else 
            {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                $query->where('(a.comment_name LIKE ' . $search . ')');
            }
        }

        // Filter on the language.
        if ($language = $this->getState('filter.language')) 
        {
            $query->where('a.language = ' . $db->quote($language));
        }

        // Filter by access level.
        if ($access = $this->getState('filter.access')) 
        {
            $query->where('a.access = ' . (int) $access);
        }
        
        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering', 'ordering');
        $orderDirn = $this->state->get('list.direction', 'ASC');
        if ($orderCol && $orderDirn) 
        {
            $query->order($db->escape($orderCol . ' ' . $orderDirn));
        }       
        return $query;
    }
}
