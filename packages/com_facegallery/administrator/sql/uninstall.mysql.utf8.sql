/**
********************************************************************
 * @name            Face Gallery
 * @version         1.0: sql/uninstall.mysql.utf8.sql$
 * @package         apptha
 * @subpackage      com_facegallery
 * @author          Apptha - http://www.apptha.com
 * @copyright       Copyright (C) 2013 Powered by Apptha
 * @license 		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @creationDate    March 13 2013
********************************************************************
*/

DROP TABLE IF EXISTS `#__facegallery_albums_backup`;

RENAME TABLE `#__facegallery_albums` TO `#__facegallery_albums_backup`;


DROP TABLE IF EXISTS `#__facegallery_albums_backup`;

RENAME TABLE `#__facegallery_images` TO `#__facegallery_images_backup`;


DROP TABLE IF EXISTS `#__facegallery_albums_backup`;

RENAME TABLE `#__facegallery_comments` TO `#__facegallery_comments_backup`;


DROP TABLE IF EXISTS `#__facegallery_albums_backup`;

RENAME TABLE `#__facegallery_settings` TO `#__facegallery_settings_backup`;

