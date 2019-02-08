<?php
/*
 ********************************************************************
 * @name            Face Gallery
 * @version         1.0: models/fields/custom_field.php$
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

jimport('joomla.html.html');
jimport('joomla.form.formfield');

// Supports an HTML select list of categories
class JFormFieldCustom_field extends JFormField
{
	// The form field type.
	protected $type = 'text';

	// Method to get the field input markup.
	protected function getInput()
	{
		// Initialize variables.
		$html = array();
		return implode($html);
	}
}