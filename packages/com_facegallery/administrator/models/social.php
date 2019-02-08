<?php
/*
 ********************************************************************
 * @name            Face Gallery
 * @version         1.0: models/social.php$
 * @since           Joomla 3.0
 * @package         apptha
 * @subpackage      com_facegallery
 * @author          Apptha - http://www.apptha.com
 * @copyright       Copyright (C) 2013 Powered by Apptha
 * @license 		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @creationDate    March 13 2013
 ********************************************************************
 */

// No direct acesss
defined('_JEXEC') or die("restricted access");

jimport('joomla.application.component.model');

require_once JPATH_COMPONENT_ADMINISTRATOR . "/helpers/phpFlickr-3.1/phpFlickr.php";

class FacegalleryModelSocial extends JModelLegacy
{
    public static function getApiSettings()
    {
        // Get database connection
        $db = JFactory::getDbo();

        // Get the settings value from the settings table
        $db->setQuery("SELECT  `flickr_key`, `flickr_secret`, `flickr_user_id`,`picasa_user_name`, `image_height`, `image_width`, `large_image_height`, `large_image_width`, `album_height`, `album_width`
                       FROM  `#__facegallery_settings`
                       WHERE 1");

       // Store result as array
        $data = $db->loadObject();
        return $data;
    }

    function getFlickrPhotosDownload($flickrApi, $flickerUserId)
    {
        $imageCreated_on = date("Y-m-d H:i:s");
        $imageUpdated_on = date("Y-m-d H:i:s");
        $total = 0;
        $fileoption = 'Flickr';
        

        $originalPath = JPATH_SITE . '/images/facegallery/';
        $thumbPath = JPATH_SITE . '/images/facegallery/thumb_image/';
        $sliderPath = JPATH_SITE . '/images/facegallery/slider_image/';
        $mediumPath = JPATH_SITE . '/images/facegallery/medium_image/';
        $watermarkPath = JPATH_SITE . '/images/facegallery/watermark/';

        // Select last album ordering
        $db = JFactory::getDBO();
        $db->setQuery("SELECT ordering FROM #__facegallery_albums ORDER BY id DESC LIMIT 1");
        $order = $db->loadResult();

        // Getting Flickr object
        $f = new phpFlickr($flickrApi);
        $ph_sets = $f->photosets_getList($flickerUserId);
        $totalAlbum = $ph_sets['total'];

        foreach ($ph_sets['photoset'] as $ph_set)
        {
            //Displaying the Albums
            $albumname = $ph_set['title'];
            $description = $ph_set['description'];
               $total_images = 0;
            $flickralbum = "SELECT id from #__facegallery_albums where album_name='$albumname'";
            $db->setQuery($flickralbum);
            $flickralbumaffect = $db->loadResult();
            if (empty($flickralbumaffect))
            {
                $order++;
                $query = "INSERT INTO #__facegallery_albums(album_name,alias_name,album_description,created_on,updated_on,state,featured,access,language,ordering,meta_keywords,meta_description) VALUES ('$albumname','$albumname','$description','$imageCreated_on','$imageUpdated_on','1','0','1','*','$order','$description','$description')";
                $db->setQuery($query);
                $result = $db->query();
            }

            $sqr = $db->insertid();
            $photoset_id = $ph_set['id'];

            // Get images list from flickr photosets
            $photos = $f->photosets_getPhotos($photoset_id);         
            foreach ($photos['photoset']['photo'] as $photo)
            {
                //Downloading images from flickr
                $img = array();
             
                $img['photo'] = $f->buildPhotoURL($photo, 'medium');
                foreach ($img as $fimage)
                {

                    $imageName = $photo['title'];
                    $newimage = basename($fimage);

                     // Select last image ordering
                    $db->setQuery("SELECT ordering FROM #__facegallery_images ORDER BY id DESC LIMIT 1");
                    $imageOrdering = $db->loadResult();

                    // Checking Image duplication
                    $db->setQuery("SELECT id FROM #__facegallery_images WHERE image_name='$newimage'");
                    $resultFImage = $db->loadResult();
                    if (empty($resultFImage))
                    {
                    	 if($total_images==0 && $sqr!=null)
                    	 {
                                $cover = 1;
                         }
                         else
                         {
                                $cover = 0;
                         }
                         if(empty($sqr))
                         {
                            $sqr=$flickralbumaffect;
                         }
                         
                        $total_images++;
                        $total++;
                        $imageOrdering++;
                        $query = "INSERT INTO #__facegallery_images (albumid,image,image_name,image_description,upload_option,created_on,updated_on,state,featured,language,ordering,download,meta_keywords,meta_description,cover_image) VALUES ('$sqr','$imageName','$newimage','','$fileoption','$imageCreated_on','$imageUpdated_on','1','0','*','$imageOrdering','1','','','$cover')";
                        $db->setQuery($query);
                        $result = $db->query();
                        $lastimage = $db->insertid();
                        $ext = pathinfo($newimage, PATHINFO_EXTENSION);

                        $sliderImage = $lastimage . '_slider.' . $ext;
                        $mediumImage = $lastimage . '_medium.' . $ext;
                        $thumbImage = $lastimage . '_thumb.' . $ext;
                        $watermarkImage = $lastimage . '_original.' . $ext;
                        $originalImage = $lastimage . '_original.' . $ext;
                        
                        $this->saveflickrpicasaImage($fimage, $thumbImage, $originalImage, $watermarkImage, $sliderImage, $mediumImage);
                        $query = "UPDATE #__facegallery_images SET slider_image = '$sliderImage' ,original_image = '$originalImage' , watermark_image = '$watermarkImage' , medium_image = '$mediumImage' , thumb_image = '$thumbImage' WHERE id = $lastimage ";
                        $db->setQuery($query);
                        $db->query();
                    }
                }
            }
            
        }

        $app = JFactory::getApplication();
        $redirect = 'index.php?option=com_facegallery&view=images';
        $app->redirect($redirect, $msg = $total .' Image(s) Imported from '. $totalAlbum .' Album(s)', $msgType = 'message');
    }

    // Method to get picasa albums
    function getPicasaPhotosById($picasaUserName)
    {
        $db = & JFactory::getDBO();

        // Assigning picasa username and password
        $wgauth = array(
            'username' => $picasaUserName,
            'password' => '',
            'authmode' => 'public',
            'authid' => ''
        );

        $imageCreated_on = date("Y-m-d H:i:s");
        $imageUpdated_on = date("Y-m-d H:i:s");
        $fileoption = 'Google';
        $thumbnailImageMax = 160;
        $imgmax = 400;
        $xmldata = '';

        if ($xmldata == '')
        {
            $ch = curl_init("http://picasaweb.google.com/data/feed/api/user/" . $picasaUserName . '?thumbsize=' . $thumbnailImageMax . 'c');

            if ($wgauth['authmode'] != 'public')
            {
                $header[] = 'Authorization: GoogleLogin auth=' . $wgauth['authid'];
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                curl_setopt($ch, CURLOPT_HEADER, false);
            }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $xmldata = curl_exec($ch);
            curl_close($ch);
        }

        $numPhotos = 0;
        $start = 0;
        $length = 0;

        // Get xml data for picasa
        if (preg_match("@<\?xml version='1\.0' encoding='UTF-8'\?>@", $xmldata))
        {
            $data = simplexml_load_string($xmldata);
            $namespace = $data->getDocNamespaces();

            foreach ($data->entry as $entry)
            {
                if (($numPhotos >= $start) && ($length == 0 || ($numPhotos - $start) < $length))
                {
                    // Collect for each album
                    // Gphoto namespace data from picasa API XML file
                    $ns_gphoto = $entry->children($namespace['gphoto']);

                    // Media namespace data from picasa API XML file
                    $ns_media = $entry->children($namespace['media']);

                    // Media thumbnail url attributes from picasa API XML file
                    $thumnail_url_attr = $ns_media->group->thumbnail->attributes();

                    // Media content (photo) url attributes from picasa API XML file
                    $photo_url_attr = $ns_media->group->content->attributes();

                    // Get Album details from picasa API XML file
                    $albumObject = array(
                        'albumTitle' => (string) $entry->title,
                        'albumDesc' => (string) $ns_gphoto->description,
                        'photoURL' => (string) $photo_url_attr['url'],
                        'thumbURL' => (string) $thumnail_url_attr['url'],
                        'albumID' => (string) $ns_gphoto->id
                    );
                    $albumname = $albumObject['albumTitle'];

                    // Get Ordering for previous id
                    $db =  JFactory::getDBO();
                    $db->setQuery('SELECT ordering FROM #__facegallery_albums order by id desc limit 1');
                    $order = $db->loadResult();

                    // Get Album name for avoid duplication
                    $picasaAlbum = "SELECT id from #__facegallery_albums where album_name='$albumname'";
                    $db->setQuery($picasaAlbum);
                    $picasaAlbumAffect = $db->loadResult();


                    // Insert Album details in 	#__facegallery_albums table if album name doesn't exist.
                    if (empty($picasaAlbumAffect))
                    {
                        $order++;
                        $query = "INSERT INTO #__facegallery_albums (album_name,alias_name,album_description,created_on,updated_on,state,featured,access,language,ordering,meta_keywords,meta_description) VALUES ('$albumname','$albumname','$albumname','$imageCreated_on','$imageUpdated_on','1','0','1','*','$order','','')";
                        $db->setQuery($query);
                        $result = $db->query();
                    }

                    //Get Last Insert albumId to insert in #__facegallery_Images table.
                    $lastid =  $db->insertid();
                    $albumiD = $albumObject['albumID'];

                    // always reset album cache for allowing refresh from dynamic Picasa
                    $this->saveVar('wgAlbumPhotosAlbumid', '');
                    $album=$this->getPicasaAlbumphotos($picasaUserName,$picasaAlbumAffect, $albumiD, $lastid, $data);
                    $total_images += $album ['numPhotos'];
                    $totalAlbum =count( $album ['photosList']);
                    if($totalAlbum==0 && $total_images != 0)
                    {
                      $totalAlbum++;
                    }
                }
            }
        }
        $app = JFactory::getApplication();
        $redirect = 'index.php?option=com_facegallery&view=images';
        $app->redirect($redirect, $msg = $total_images .' Public Image(s) Imported from '. $totalAlbum .' Album(s)', $msgType = 'message');
    }

    // Importing the Album Photos from Picasa
    function getPicasaAlbumphotos($pusername,$resultAlbum, $albumid, $lastid, $data, $start=0, $length=0)
    {
        // check last cached and filled
        $imageCreated_on = date("Y-m-d H:i:s");
        $imageUpdated_on = date("Y-m-d H:i:s");
        $fileoption = 'Google';

        $thumbnailimgmax = 160;
        $imgmax = 400;
        $password = '';
        $authmode = 'public';
        $authid = '';

        $incache = $this->loadVar('wgAlbumPhotosAlbumid');
        $albumcache = $this->loadVar('wgAlbumPhotos');
        if (!is_array($albumcache) || ($incache != $albumid))
        {
            $url = "http://picasaweb.google.com/data/feed/api/user/" . $pusername . '/albumid/' . $albumid . '?imgmax=' . $imgmax . '&thumbsize=' . $thumbnailimgmax . 'c';
            $ch = curl_init($url);
            if ($authmode != 'public')
            {
                $header[] = 'Authorization: GoogleLogin auth=' . $authid;
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                curl_setopt($ch, CURLOPT_HEADER, false);
            }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $xmldata = curl_exec($ch);
            curl_close($ch);
            $numPhotos = 0;
            $albumTitle = '';
            $photos = array();
            if (preg_match("@<\?xml version='1\.0' encoding='UTF-8'\?>@", $xmldata))
            {
                $data = simplexml_load_string($xmldata);
                $namespace = $data->getDocNamespaces();
                $albumTitle = (string) $data->title[0];

                foreach ($data->entry as $entry)
                {
                    // Collect for each photo; - title - url - thumbnail url - Gphoto namespace data
                    $ns_gphoto = $entry->children($namespace['gphoto']);

                    // Media namespace data
                    $ns_media = $entry->children($namespace['media']);

                    // Media thumbnail attributes
                    $thb_attr = $ns_media->group->thumbnail->attributes();

                    // Media content attributes
                    $con_attr = $ns_media->group->content->attributes();

                    // Thumbnail is same reference, except other 's<imgmax>' tag
                    $photo = array(
                        'photoTitle' => (string) $entry->title[0],
                        'photoURL' => (string) $con_attr['url'],
                        'thumbURL' => (string) $thb_attr['url']
                    );

                    $phototitle = $photo['photoTitle'];
                    $photourl = $photo['photoURL'];
                    $photou['photo'] = $photo['photoURL'];
                    foreach ($photou as $images)
                    {
                        if (getimagesize($images))
                        {
                            $imgn = basename($images);
                            $info = pathinfo($imgn);
                            $file = basename($imgn, '.' . $info['extension']);

                            $db =  JFactory::getDBO();
                            $db->setQuery("SELECT ordering FROM #__facegallery_images order by id desc limit 1");
                            $imageOrdering = $db->loadResult();

                            $db->setQuery("SELECT * from #__facegallery_images where image_name='$imgn'");
                            $resultPImage = $db->loadResult();

                              if(empty($resultPImage))
                              { 
                              	   if($numPhotos==0 && $lastid!=0)
                              	   {
                                       $cover = 1;
                                   }
                                   else
                                   {
                                       $cover = 0;
                                   }
                                   if(empty($lastid))
                                   {
                                    $lastid=$resultAlbum;
                                   }
				   				   $numPhotos += 1;
                                   $imageOrdering++;
                                   $query = "INSERT INTO #__facegallery_images (albumid,image,image_name,image_description,upload_option,created_on,state,featured,language,ordering,download,meta_keywords,meta_description,cover_image) VALUES ('$lastid','$file','$imgn','','$fileoption','$imageCreated_on','1','0','*','$imageOrdering','1','','','$cover')";
                                   $db->setQuery($query);
                                   $r =	$db->query();
                                   
                                   $lastInsertId = $db->insertid();
                                   $ext = pathinfo($imgn, PATHINFO_EXTENSION);
                                   
                                   $sliderImage = $lastInsertId . '_slider.' . $ext;
                                   $thumbImage = $lastInsertId . '_thumb.' . $ext;
                                   $mediumImage = $lastInsertId . '_medium.' . $ext;
                                   $watermarkImage = $lastInsertId . '_original.' . $ext;
                                   $originalImage = $lastInsertId . '_original.' . $ext;


                                   $this->saveflickrpicasaImage($images, $thumbImage,  $originalImage, $watermarkImage, $sliderImage, $mediumImage);
                                   $query = "UPDATE #__facegallery_images SET slider_image = '$sliderImage' , original_image = '$originalImage' , watermark_image = '$watermarkImage' , medium_image = '$mediumImage' , thumb_image = '$thumbImage' WHERE id = $lastInsertId ";
                                   $db->setQuery($query);
                                   $db->query();
                              }
                        }
                    }
                    $photo['thumbURL'] = str_replace('/d/', '/s' . $thumbnailimgmax . '-c/', $photo['thumbURL']);
                    $photos[] = $photo;                   
                }
            }
            $albumcache = array(
                'albumTitle' => $albumTitle,
                'numPhotos' => $numPhotos,
                'photosList' => $photo);
            $this->saveVar('wgAlbumPhotosAlbumid', $albumid);
            $this->saveVar('wgAlbumPhotos', $albumcache);
        }

        // Load specific page
        $photos[] = $albumcache['photosList'];
        $numPhotos = $albumcache['numPhotos'];

        if ($length == 0 || ($start + $length) > $numPhotos)
            $length = $numPhotos - $start;
        $photoList = array();
        for ($i = $start; $i < ($start + $length); $i++)
        {
            $photoList = $photos[$i];
        }

        $photoAlbum = array(
            'albumTitle' => $albumcache['albumTitle'],
            'numPhotos' => $numPhotos,
            'photosList' => $photoList);
        $finalname = $photoAlbum['albumTitle'];

        return $photoAlbum;
    }

    function saveVar($name, $value)
    {
        $session = JFactory::getSession();
        $session->set($name, $value);
    }

    function loadVar($name)
    {
        $session = JFactory::getSession();
        $value = $session->get($name);
        return $value;
    }

    function saveflickrpicasaImage($image, $thumbimage, $originalImage, $watermarkImage, $sliderImage, $mediumImage)
    {
        // Get data from flickr and picasa
        $ch = curl_init($image);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        $rawdata = curl_exec($ch);
        curl_close($ch);
        $mypath = JPATH_ROOT . '/images/facegallery/' . $originalImage;
        

        // Append images into specified folder
        $thumb_rename = JPATH_ROOT . '/images/facegallery/thumb_image/' . $thumbimage;
        $slider_rename = JPATH_ROOT . '/images/facegallery/slider_image/' . $sliderImage;
        $medium_rename = JPATH_ROOT . '/images/facegallery/medium_image/' . $mediumImage;
        $watermark_rename = JPATH_ROOT . '/images/facegallery/watermark/' . $watermarkImage;
        $original_rename = JPATH_ROOT . '/images/facegallery/' . $originalImage;
        
        $my = fopen($mypath, 'a');
        fwrite($my, $rawdata);        
        fclose($my);
        $data = $this->getApiSettings();
        

        // Get new dimensions for medium images
        list($width, $height) = getimagesize($mypath);
        $new_width = $data->image_width;
        $new_height = $data->image_height;
        
    	$x_ratio = $new_width / $width;
   			$y_ratio = $new_height / $height;

   			if(($width <= $new_width) && ($height <= $new_height)) 
   			{
		        	$tn_width = $width;		
		           	$tn_height = $height;
			} 
			elseif(($x_ratio * $height) < $new_height) 
			{
			        $tn_height = ceil($x_ratio * $height);
			        $tn_width = $new_width;
			} 
			else 
			{
			        $tn_width = ceil($y_ratio * $width);
			        $tn_height = $new_height;			
			}

        // Cropping images
        $image_p = imagecreatetruecolor($tn_width, $tn_height);
        $image = imagecreatefromjpeg($mypath);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $tn_width, $tn_height, $width, $height);        
        // Write Output in the medium path
        imagejpeg($image_p, $medium_rename, 100);

        

        // Get new dimensions for thumb images
        list($width, $height) = getimagesize($mypath);
        $new_width_thumb = $data->album_width;
        $new_height_thumb = $data->album_height;
        
    		$x_ratio = $new_width_thumb / $width;
   			$y_ratio = $new_height_thumb / $height;

   			if(($width <= $new_width_thumb) && ($height <= $new_height_thumb)) 
   			{
		        	$tn_width = $width;		
		           	$tn_height = $height;
			} 
			elseif(($x_ratio * $height) < $new_height) 
			{
			        $tn_height = ceil($x_ratio * $height);
			        $tn_width = $new_width_thumb;
			} 
			else 
			{
			        $tn_width = ceil($y_ratio * $width);
			        $tn_height = $new_height_thumb;			
			}        
        $image_p = imagecreatetruecolor($tn_width, $tn_height);
        $image = imagecreatefromjpeg($mypath);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $tn_width, $tn_height, $width, $height);
        // Write Output in the thumb path
        imagejpeg($image_p, $thumb_rename, 100);
        
        
        
        // Get new dimensions for slider images
        list($width, $height) = getimagesize($mypath);
        $new_width_large = $data->large_image_width;
        $new_height_large = $data->large_image_height;
        
         $x_ratio = $new_width_large / $width;
   			$y_ratio = $new_height_large / $height;

   			if(($width <= $new_width_large) && ($height <= $new_height_large)) 
   			{
		        	$tn_width = $width;		
		           	$tn_height = $height;
			} 
			elseif(($x_ratio * $height) < $new_height) 
			{
			        $tn_height = ceil($x_ratio * $height);
			        $tn_width = $new_width_large;
			} 
			else 
			{
			        $tn_width = ceil($y_ratio * $width);
			        $tn_height = $new_height_large;			
			}        
        
        $image_p = imagecreatetruecolor($tn_width, $tn_height);
        $image = imagecreatefromjpeg($mypath);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $tn_width, $tn_height, $width, $height);
        imagejpeg($image_p, $slider_rename, 100); 
        
	        // Get new dimensions for watermark images
	        list($width, $height) = getimagesize($mypath);
	        $new_width = $width;
	        $new_height = $height;
	        
	        // Cropping images
	        $image_p = imagecreatetruecolor($new_width, $new_height);
	        $image = imagecreatefromjpeg($mypath);
	        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
	        
	        // Water marking  
	        imagefilledrectangle($image_p,$new_width-128, 20, $new_width-30, 40, 0x000000);         	
	        $textcolor = imagecolorallocate($image_p, 255, 255, 255);
	
			// Write the string at the top left
			imagestring($image_p, 5,$new_width-125, 21, 'apptha.com', $textcolor);
	
	        // Write Output in the slider image
	        imagejpeg($image_p, $watermark_rename, 100);    	    
    }
}

