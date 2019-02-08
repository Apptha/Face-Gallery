/**
********************************************************************
 * @name            Face Gallery
 * @version         1.0: sql/install.mysql.utf8.sql$
 * @package         apptha
 * @subpackage      com_facegallery
 * @author          Apptha - http://www.apptha.com
 * @copyright       Copyright (C) 2013 Powered by Apptha
 * @license 		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @creationDate    March 13 2013
 ********************************************************************
*/

CREATE TABLE IF NOT EXISTS `#__facegallery_albums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `album_name` varchar(256) NOT NULL,
  `alias_name` varchar(256) NOT NULL,
  `album_description` text NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `state` tinyint(4) NOT NULL,
  `featured` tinyint(4) NOT NULL,
  `access` varchar(255) NOT NULL,
  `album_views` int(11) NOT NULL,
  `language` char(8) NOT NULL,
  `ordering` int(11) NOT NULL,
  `meta_keywords` text NOT NULL,
  `meta_description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=44 ;


CREATE TABLE IF NOT EXISTS `#__facegallery_images` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `albumid` int(11) unsigned NOT NULL,
  `image` varchar(256) NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `image_description` text NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `created_on` datetime NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `thumb_image` varchar(255) NOT NULL,
  `medium_image` varchar(255) NOT NULL,
  `slider_image` varchar(255) NOT NULL,
  `original_image` varchar(255) NOT NULL,
  `watermark_image` varchar(255) NOT NULL,
  `cover_image` tinyint(1) NOT NULL,  
  `views` int(11) NOT NULL,
  `featured` tinyint(1) NOT NULL,
  `access` int(11) NOT NULL,
  `language` char(8) NOT NULL,
  `upload_option` varchar(255) NOT NULL,
  `download` tinyint(1) NOT NULL,
  `ordering` int(11) NOT NULL,
  `meta_keywords` text NOT NULL,
  `meta_description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `albumid` (`albumid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=318 ;

CREATE TABLE IF NOT EXISTS `#__facegallery_settings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `image_height` int(11) NOT NULL,
  `image_width` int(11) NOT NULL,
  `large_image_height` int(11) NOT NULL,
  `large_image_width` int(11) NOT NULL,
  `album_height` int(11) NOT NULL,
  `album_width` int(11) NOT NULL,
  `album_row` int(11) NOT NULL,
  `album_column` int(11) NOT NULL,
  `image_pagination` int(11) NOT NULL,
  `album_pagination` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `display_views` tinyint(1) NOT NULL,  
  `moderate_comments` tinyint(1) NOT NULL,
  `flickr_key` varchar(255) NOT NULL,
  `flickr_secret` varchar(255) NOT NULL,
  `flickr_user_id` varchar(255) NOT NULL,
  `picasa_user_name` varchar(255) NOT NULL,
  `facebook_api` varchar(255) NOT NULL,
  `license` varchar(255) NOT NULL,
  `sharing` tinyint(1) NOT NULL,
  `download` tinyint(1) NOT NULL,  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;


INSERT INTO `#__facegallery_settings` (`id`, `image_height`, `image_width`, `large_image_height`, `large_image_width`, `album_height`, `album_width`, `album_row`, `album_column`, `image_pagination`, `album_pagination`, `created_on`, `updated_on`, `display_views`, `moderate_comments`, `flickr_key`, `flickr_secret`, `flickr_user_id`, `picasa_user_name`, `facebook_api`, `license`, `sharing`, `download`) VALUES
(1, 206, 206, 300, 700, 130, 170, 4, 4, 8, 8, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 1, '', '', '', '', '', '', 1, 1);


CREATE TABLE IF NOT EXISTS `#__facegallery_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_item_id` int(12) NOT NULL DEFAULT '0',
  `state` tinyint(1) NOT NULL,
  `comment_ip` varchar(20) DEFAULT NULL,
  `comment_name` varchar(64) DEFAULT '',
  `comment_text` text NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `comment_item_id` (`comment_item_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=297 ;

