<?php
/*
 *********************************************************************
 * @name            Face Gallery
 * @version         1.0: helpers/facegallery.php$
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

// Facegallery helper.
class FacegalleryHelper
{
	// Configure the Linkbar.
	public static function addSubmenu($vName = '')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_FACEGALLERY_TITLE_ALBUMS'),
			'index.php?option=com_facegallery&view=albums',
			$vName == 'albums'
		);
                JHtmlSidebar::addEntry(
			JText::_('COM_FACEGALLERY_TITLE_IMAGES'),
			'index.php?option=com_facegallery&view=images',
			$vName == 'images'
		);
                JHtmlSidebar::addEntry(
			JText::_('COM_FACEGALLERY_TITLE_SOCIAL'),
			'index.php?option=com_facegallery&view=social',
			$vName == 'social'
		);
                JHtmlSidebar::addEntry(
			JText::_('COM_FACEGALLERY_TITLE_COMMENTS'),
			'index.php?option=com_facegallery&view=comments',
			$vName == 'comments'
		);                
                JHtmlSidebar::addEntry(
			JText::_('COM_FACEGALLERY_TITLE_SETTINGS'),
			'index.php?option=com_facegallery&view=settings',
			$vName == 'settings'
		);                
	}

	// Gets a list of the actions that can be performed.
	public static function getActions()
	{
		$user       = JFactory::getUser();
		$result     = new JObject;
		$assetName  = 'com_facegallery';
		$actions    = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);
		foreach ($actions as $action)
                {
			$result->set($action, $user->authorise($action, $assetName));
		}
		return $result;
	}

    public static function getClientOptions()
	{
		$options = array();

		$db	= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('id As value, album_name As text');
		$query->from('#__facegallery_albums AS a');
		$query->order('a.album_name');
		$query->where('a.state=1');

		// Get the options.
		$db->setQuery($query);
		try
		{
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}

		array_unshift($options, JHtml::_('select.option', '0', JText::_('COM_FACEGALLERY_NO_CLIENT')));
		return $options;
	}
}
