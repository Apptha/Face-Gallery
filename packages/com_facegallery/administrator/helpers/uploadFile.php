<?php
/*
 ********************************************************************
 * @name            Face Gallery
 * @version         1.0: helpers/uploadFile.php$
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
//defined('_JEXEC') or die("restricted access");
define ('_JEXEC',1);

$base  = explode("administrator",dirname(__FILE__));
define( 'JPATH_BASE', $base[0]);

require_once ( JPATH_BASE .'includes/defines.php' );
require_once ( JPATH_BASE .'includes/framework.php' );

$config = new JConfig();
$secret = md5($config->secret);
if($_POST['token']==$secret)
{
facegallery_upload();
}
exit;

function facegallery_upload()
{
        $fieldName = 'uploadfile';

        //any errors the server registered on uploading
        $fileError = $_FILES[$fieldName]['error'];
        $fileError = 0;
        if ($fileError > 0)
        {
                switch ($fileError)
                {
                    case 1:
                        trigger_error("The file is too large",E_USER_ERROR);
	                break;
                    case 2:
                        trigger_error("The file is too large",E_USER_ERROR);
	                break;
                    case 3:
                        trigger_error("Error File",E_USER_ERROR);
                        break;
                    case 4:
                        trigger_error("Error File",E_USER_ERROR);
                        break;
                }
        }

	//check for filesize
	$fileSize = $_FILES[$fieldName]['size'];
    if($fileSize > 20000000)
    {
    }
        
    // check the file extension is ok
	$fileName               = $_FILES[$fieldName]['name'];
    $uploadedFileNameParts  = explode('.',$fileName);
    $uploadedFileExtension  = array_pop($uploadedFileNameParts);
    $validFileExts          = explode(',', 'jpeg,jpg,png,gif,bmp');

    // assume the extension is false until we know its ok
    $extOk = false;

    // To check file extension
    foreach($validFileExts as $key => $value)
    {
    	if( preg_match("/$value/i", $uploadedFileExtension ) )
        {
        	$extOk = true;
        }
    }

    // Get temporary file size, name, extension and move to images folder
    for ($code_length = 5, $newcode = ''; strlen($newcode) < $code_length; $newcode .= chr(!rand(0, 2) ? rand(48, 57) : (!rand(0, 1) ? rand(65, 90) : rand(97, 122)))
    );

    $fileTemp = $_FILES[$fieldName]['tmp_name'];
    $fileParts = explode(".",trim($_FILES[$fieldName]['name']));
    $fileExtension = $fileParts[count($fileParts)-1];
    $fileName = preg_replace("[^A-Za-z0-9.]", "-", $fileName);
    $fileName = $fileParts[0]."__".$newcode.rand(1,100000).".".$fileExtension;

    // always use constants when making file paths, to avoid the possibilty of remote file inclusion
	$uploadPath = urldecode($_REQUEST["jpath"]).$fileName;
    if(! move_uploaded_file($fileTemp, $uploadPath))
    {
    	echo 'Cannot move the file' ;
    }
    echo $fileName;

    // creating thumb image
	imageToThumb($fileName,(int) $_REQUEST["th"],(int) $_REQUEST["tw"],"thumb_image");
		
	// creating medium image
	imageToThumb($fileName,(int)$_REQUEST["mh"],(int)$_REQUEST["mw"],"medium_image");

    // creating slider image
    imageToThumb($fileName,(int)$_REQUEST["fh"],(int)$_REQUEST["fw"],"slider_image");
    
	//creating watermark image
    createWatermark($fileName,(int)$_REQUEST["fh"],(int)$_REQUEST["fw"],"watermark");    
}

function imageToThumb($fname,$imgheight,$imgwidth,$foldername)
{       
		// open the directory
		$pathToImages = urldecode($_REQUEST["jpath"]);
		$pathToThumbs = urldecode($_REQUEST["jpath"]). $foldername."/";

        $dir = opendir($pathToImages);
        ini_set("memory_limit", "1000M");

        // loop through it, looking for any/all JPG files:
		if (readdir($dir))
        {
            // parse path for the extension
	    	$info = pathinfo($pathToImages . $fname);
	    	
	    	if (strtolower($info['extension']) == 'jpg')
	        {
	            // load image and get image size
		       	$img = imagecreatefromjpeg("{$pathToImages}{$fname}");		
	        }
	        else if (strtolower($info['extension']) == 'gif')
	        {
			    // load image and get image size
			    $img = imagecreatefromgif("{$pathToImages}{$fname}");
	        }
	        else if (strtolower($info['extension']) == 'png')
   			{
	        	$img = imagecreatefrompng("{$pathToImages}{$fname}");
   			}	        
        		    	
	        $width = imagesx($img);
		    $height = imagesy($img);
		    	
		    // calculate thumbnail size
		    $new_width = 270;
		    $new_height = 270;
		    	
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
	    	
			
	        if (strtolower($info['extension']) == 'jpg')
	        {
	            // load image and get image size		      	       	
				$tmp_img = imagecreatetruecolor($tn_width, $tn_height);
				imagecopyresampled($tmp_img, $img, 0, 0, 0, 0, $tn_width, $tn_height,$width, $height);
				//imageinterlace( $tmp_img, true);
           		imagejpeg($tmp_img,"{$pathToThumbs}{$fname}", 100);
		    }
	        else if (strtolower($info['extension']) == 'gif')
	        {
			    // load image and get image size			    			    
           		$tmp_img = imagecreatetruecolor($tn_width, $tn_height);
	            imagecopyresampled($tmp_img, $img, 0, 0, 0, 0, $tn_width, $tn_height,$width, $height);
           		imagegif($tmp_img,"{$pathToThumbs}{$fname}");
		    }
   			else if (strtolower($info['extension']) == 'png')
   			{	        
   				// Load the original image.		
				imagealphablending($img, true);		
           		$tmp_img = imagecreatetruecolor($tn_width, $tn_height);
           		imagesavealpha($tmp_img, true);
				imagealphablending($tmp_img, false);
				$transparent = imagecolorallocatealpha($tmp_img, 0, 0, 0, 127);
				imagefill($tmp_img, 0, 0, $transparent);
           		imagecopyresampled($tmp_img, $img, 0, 0, 0, 0, $tn_width, $tn_height,$width, $height);
           		imagepng($tmp_img,"{$pathToThumbs}{$fname}",2);
   			}  
        }
        // close the directory
		closedir($dir);
}

//watermark
function createWatermark($fname,$imgheight,$imgwidth,$foldername)
{       	
// open the directory
		$pathToImages = urldecode($_REQUEST["jpath"]);
		$pathToThumbs = urldecode($_REQUEST["jpath"]). $foldername."/";

        $dir = opendir($pathToImages);
        ini_set("memory_limit", "1000M");

        // loop through it, looking for any/all JPG files:
		if (readdir($dir))
        {
            // parse path for the extension
	    	$info = pathinfo($pathToImages . $fname);
	    	
	    	if (strtolower($info['extension']) == 'jpg')
	        {
	            // load image and get image size
		       	$img = imagecreatefromjpeg("{$pathToImages}{$fname}");		
	        }
	        else if (strtolower($info['extension']) == 'gif')
	        {
			    // load image and get image size
			    $img = imagecreatefromgif("{$pathToImages}{$fname}");
	        }
	        else if (strtolower($info['extension']) == 'png')
   			{
	        	$img = imagecreatefrompng("{$pathToImages}{$fname}");
   			}	        
        		    	
	        $width = imagesx($img);
		    $height = imagesy($img);
		    	
		    // calculate thumbnail size
		    $new_width = $width;
		    $new_height = $height;
			
	        if (strtolower($info['extension']) == 'jpg')
	        {
	            // load image and get image size		      	       	
				$tmp_img = imagecreatetruecolor($new_width, $new_height);
				imagecopyresampled($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height,$width, $height);
				//imageinterlace( $tmp_img, true);           		
		    }
	        else if (strtolower($info['extension']) == 'gif')
	        {
			    // load image and get image size			    			    
           		$tmp_img = imagecreatetruecolor($new_width, $new_height);
	            imagecopyresampled($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height,$width, $height);           		
		    }
   			else if (strtolower($info['extension']) == 'png')
   			{	        
           		// Load the original image.		
				imagealphablending($img, true);				
   				$tmp_img = imagecreatetruecolor($new_width, $new_height);
           		imagesavealpha($tmp_img, true);
				imagealphablending($tmp_img, false);
				$transparent = imagecolorallocatealpha($tmp_img, 0, 0, 0, 127);
				imagefill($tmp_img, 0, 0, $transparent);
           		imagecopyresampled($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height,$width, $height);           		
   			}  
   			
   			// Water marking       
            imagefilledrectangle($tmp_img, $new_width-128, 20, $new_width-30, 40, 0x000000);    	
            $textcolor = imagecolorallocate($tmp_img, 255, 255, 255);
            	
            // Write the string at the top left
			imagestring($tmp_img, 5, $new_width-125, 21, 'apptha.com', $textcolor); 
			
         	if (strtolower($info['extension']) == 'jpg')
            {
                // save thumbnail into a file
				imagejpeg($tmp_img, "{$pathToThumbs}{$fname}");
            }
            else if (strtolower($info['extension']) == 'png')
            {
		        // save thumbnail into a file
		        imagepng($tmp_img, "{$pathToThumbs}{$fname}",2);
	    	} 
            else if (strtolower($info['extension']) == 'gif')
            {
		        // save thumbnail into a file
		        imagegif($tmp_img, "{$pathToThumbs}{$fname}");
	    	}
        }
        // close the directory
		closedir($dir);
}    
?>