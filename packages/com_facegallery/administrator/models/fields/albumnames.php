<?php
/*
 *********************************************************************
 * @name            Face Gallery
 * @version         1.0: models/fields/albumnames.php$
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

JFormHelper::loadFieldClass('list');

require_once __DIR__ . '/../../helpers/facegallery.php';
class JFormFieldAlbumNames extends JFormFieldList
{
	// The form field type.
	protected $type = 'AlbumNames';

	// Method to get the field options.
	public function getOptions()
	{
		return FacegalleryHelper::getClientOptions();
	}
}
