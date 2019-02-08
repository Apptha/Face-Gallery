<?php
/*
 ********************************************************************
 * @name            Face Gallery
 * @version         1.0: tables/album.php$
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
defined('_JEXEC') or die('Restricted access');

// Album Table class
class FacegalleryTableAlbum extends JTable
{
		var $id =null;
	 	var $album_name=null;
        var $alias_name=null;
        var $album_description=null;
        var $created_on=null;
        var $updated_on=null;
        var $total_images=null;
        var $featured=null;
        var $access=null;
        var $ordering=null;
        var $language=null;
        var $state=null;
    public function __construct(&$db)
    {
        parent::__construct('#__facegallery_albums', 'id', $db);
        $date = JFactory::getDate();
        $this->created_on = $date->toSql();
    }

    // This function convert an array of JAccessRule objects into an rules array.
    private function JAccessRulestoArray($jaccessrules)
    {
        $rules = array();
        foreach($jaccessrules as $action => $jaccess)
        {
            $actions = array();
            foreach($jaccess->getData() as $group => $allow)
            {
                $actions[$group] = ((bool)$allow);
            }
            $rules[$action] = $actions;
        }
        return $rules;
    }

    // Overloaded check function
    public function check()
    {
    	// Set Album name
		$this->album_name = htmlspecialchars_decode($this->album_name, ENT_QUOTES);
	
		// Set Alias name
		$this->alias_name = JApplication::stringURLSafe($this->alias);
		if (empty($this->alias_name))
		{
			$this->alias_name = JApplication::stringURLSafe($this->album_name);
		}
	
	    // Set ordering
		if ($this->state < 0)
		{
			// Set ordering to 0 if state is archived or trashed
			$this->ordering = 0;
		}
	    elseif (empty($this->ordering))
		{
			// Set ordering to last if ordering was 0
			$this->ordering = self::getNextOrder($this->_db->quoteName('id').'=' . $this->_db->Quote($this->id).' AND state>=0');
		}
        return parent::check();
    }

    // Method to set the publishing state for a row or list of rows in the database table.
    public function publish($pks = null, $state = 1, $userId = 0)
    {
        // Initialise variables.
        $k = $this->_tbl_key;

        // Sanitize input.
        JArrayHelper::toInteger($pks);
        $userId = (int) $userId;
        $state = (int) $state;

        // If there are no primary keys set check to see if the instance key is set.
        if (empty($pks))
        {
            if ($this->$k)
            {
                $pks = array($this->$k);
            }
            // Nothing to set publishing state on, return false.
            else
            {
                $this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
                return false;
            }
        }

        // Build the WHERE clause for the primary keys.
        $where = $k . '=' . implode(' OR ' . $k . '=', $pks);

        // Determine if there is checkin support for the table.
        if (!property_exists($this, 'checked_out') || !property_exists($this, 'checked_out_time')) {
            $checkin = '';
        } else {
            $checkin = ' AND (checked_out = 0 OR checked_out = ' . (int) $userId . ')';
        }

        // Update the publishing state for rows with the given primary keys.
        $this->_db->setQuery(
                'UPDATE `' . $this->_tbl . '`' .
                ' SET `state` = ' . (int) $state .
                ' WHERE (' . $where . ')' .
                $checkin
        );
        $this->_db->query();

        // Check for a database error.
        if ($this->_db->getErrorNum())
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        // If checkin is supported and all rows were adjusted, check them in.
        if ($checkin && (count($pks) == $this->_db->getAffectedRows()))
        {
            // Checkin each row.
            foreach ($pks as $pk) {
                $this->checkin($pk);
            }
        }

        // If the JTable instance value is in the list of primary keys that were set, set the instance.
        if (in_array($this->$k, $pks))
        {
            $this->state = $state;
        }

        $this->setError('');
        return true;
    }
}
