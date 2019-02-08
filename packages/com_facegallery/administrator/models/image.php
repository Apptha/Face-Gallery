<?php
/*
 *******************************************************************
 * @name            Face Gallery
 * @version         1.0: models/image.php$
 * @since           Joomla 3.0
 * @package         apptha
 * @subpackage      com_facegallery
 * @author          Apptha - http://www.apptha.com
 * @copyright       Copyright (C) 2013 Powered by Apptha
 * @license 		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @creationDate    March 13 2013
 *******************************************************************
 */

// No direct access.
defined('_JEXEC') or die("restricted access");

jimport('joomla.application.component.modeladmin');

// Facegallery model.
class FacegalleryModelImage extends JModelAdmin
{

    protected $text_prefix = 'COM_FACEGALLERY';

    public function getTable($type = 'image', $prefix = 'FacegalleryTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    // Method to get the record form.
    public function getForm($data = array(), $loadData = true)
    {
        // Initialise variables.
        $app = JFactory::getApplication();

        // Get the form.
        $form = $this->loadForm('com_facegallery.image', 'image', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form))
        {
            return false;
        }

        // Determine correct permissions to check.
        if ($this->getState('image.id'))
		{
			// Existing record. Can only edit in selected images.
			$form->setFieldAttribute('id', 'action', 'core.edit');
		}
		else
		{
			// New record. Can only create in selected images.
			$form->setFieldAttribute('id', 'action', 'core.create');
		}
	
		// Modify the form based on access controls.
		if (!$this->canEditState((object) $data))
		{
			// Disable fields for display.
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('publish_up', 'disabled', 'true');
			$form->setFieldAttribute('publish_down', 'disabled', 'true');
			$form->setFieldAttribute('state', 'disabled', 'true');
	
	
	        // Disable fields while saving. The controller has already verified this is a record you can edit.
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('publish_up', 'filter', 'unset');
			$form->setFieldAttribute('publish_down', 'filter', 'unset');
			$form->setFieldAttribute('state', 'filter', 'unset');
		}
	    return $form;
    }

    // Method to get the data that should be injected in the form.
    protected function loadFormData() 
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_facegallery.edit.image.data', array());

        if (empty($data))
        {
            $data = $this->getItem();
        }
        return $data;
    }
    
    //Prepare and sanitise the table prior to saving.
    protected function prepareTable($table)
    {
        jimport('joomla.filter.output');
        
        if (empty($table->id))
        {          
            // Set ordering to the last item if not set
            if (@$table->ordering == '')
            {
                $db = JFactory::getDbo();
                $db->setQuery('SELECT MAX(ordering) FROM #__facegallery_images');
                $max = $db->loadResult();
                $table->ordering = $max + 1;
            }            
        }
    }

    // Method to save new record form details
    public function save($data)
    {         
        return parent::save($data);
    }

    // Get settings table information
    public function getSettings()
    {
        global $option, $mainframe;
        $db = JFactory::getDBO();

        $query = "SELECT * FROM #__facegallery_settings LIMIT 1";
        $db->setQuery($query);
        $rows = $db->loadObject();

        if ($db->getErrorNum()) 
        {
            echo $db->stderr();
            return false;
        }
        return $rows;
    }

    // Getting album id, album name from albums table
    public function getAlbumNames()
    {       
        $options = array();
        $published = 1;
        $trashed = -2;
        $id = (int)JRequest::getVar('id');
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        if($id == 0)
        {
	        $query->select('id, album_name');
	        $query->from('#__facegallery_albums AS a');
	        $query->where('a.state = ' . (int) $published);
	        $query->order('a.album_name');
	    }
        else 
        {
        	$query->select('id, album_name');
	        $query->from('#__facegallery_albums AS a');
	        $query->where('a.state != ' . (int) $trashed);
	        $query->order('a.album_name');	        
        }
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

        array_unshift($options, JHtml::_('select.option', '0', JText::_('COM_FACEGALLERY_FORM_LBL_IMAGE_SELECT_ALBUM')));
        return $options;
    }


    // Get images details from images table for edit options
    function getimages($id)
    {
        global $option, $albumval, $albumtot;
        
        $id = (int)JRequest::getVar('id');

        $db = JFactory::getDBO();
        $query = "SELECT b.album_name,a.image,a.image_name,a.image_description,a.upload_option,a.albumid
                  FROM  #__facegallery_images a
                  LEFT JOIN #__facegallery_albums b on a.albumid=b.id where a.id= ".$id;
        $db->setQuery($query);
        $resultImage = $db->loadObjectList();        
        return $resultImage;
    }

    // Insert new images record and esit existing image records
    public function saveImage($data)
    {
        $albumid = $data['albumid'];
        $db = JFactory::getDBO();       
        
        // Getting New Form Input details
        if($data['jform']['id'] == "")
        {
                $imageCount = count($data['image']); 
                $imageLanguage = $data['jform']['language'];
                $imageDownload = 1;
                $imageState = $data['jform']['state'];
                $imageFeatured = $data['jform']['featured'];
                $imageState = $data['jform']['state'];
                $imageMetaKey = $data['jform']['meta_keywords'];
                $imageMetaDesc = $data['jform']['meta_description'];
                $imageCreated_on = date("Y-m-d H:i:s");

                // Get uploaded folder path
                $sliderPath = JPATH_SITE . '/images/facegallery/slider_image/';
                $mediumPath = JPATH_SITE . '/images/facegallery/medium_image/';
                $thumbPath = JPATH_SITE . '/images/facegallery/thumb_image/';
                $watermarkPath = JPATH_SITE . '/images/facegallery/watermark/';
                $originalPath = JPATH_SITE . '/images/facegallery/';

                $db->setQuery('SELECT ordering FROM #__facegallery_images ORDER BY id DESC LIMIT 1');
                $imageOrdering = $db->loadResult();

                // Insert new record into images table
                for ($inc = 0; $inc < $imageCount; $inc++)
                {
                    if(empty($imageOrdering))
                    {
                        $imageOrdering=1;
                    }
                    else
                    {
                        $imageOrdering=$imageOrdering+1;
                    }
                    $imageTitle = trim($data['image'][$inc]);
                    $imageName = trim($data['image_name'][$inc]);
                    $imageDesc = $data['image_description'][$inc];
                    $uploadOption = 'File';

                    // Get image id to set album cover
                    $query = "SELECT id FROM #__facegallery_images WHERE albumid =".$albumid;
                    $db->setQuery($query);
                    $db->query();
                    $result = $db->loadResult();
           
                    if(!count($result))
                    {
                        $db = JFactory::getDbo();
                        $query = "INSERT INTO #__facegallery_images (albumid,image,image_name,image_description,upload_option,created_on,state,featured,language,ordering,download,meta_keywords,meta_description,cover_image) VALUES ('$albumid','$imageTitle','$imageName','$imageDesc','$uploadOption','$imageCreated_on','1','$imageFeatured','$imageLanguage','$imageOrdering','$imageDownload','$imageMetaKey','$imageMetaDesc','1')";
                        $db->setQuery($query);
                        $db->query();
                    }
                    else
                    {
                        $db = JFactory::getDbo();
                        $query = "INSERT INTO #__facegallery_images (albumid,image,image_name,image_description,upload_option,created_on,state,featured,language,ordering,download,meta_keywords,meta_description) VALUES ('$albumid','$imageTitle','$imageName','$imageDesc','$uploadOption','$imageCreated_on','$imageState','$imageFeatured','$imageLanguage','$imageOrdering','$imageDownload','$imageMetaKey','$imageMetaDesc')";
                        $db->setQuery($query);
                        $db->query();
                    }

                    // Rename the thumb image and original image
                    $lastInsertId = $db->insertid();
                    echo $lastInsertId;
                    $ext = pathinfo($imageName, PATHINFO_EXTENSION);
                    $sliderImage = $lastInsertId . '_slider.' . $ext;
                    $mediumImage = $lastInsertId . '_medium.' . $ext;
                    $thumbImage = $lastInsertId . '_thumb.' . $ext;
                    $waterMarkImage = $lastInsertId . '_original.' . $ext;
                    $originalImage = $lastInsertId . '_original.' . $ext;

                    $query = "UPDATE #__facegallery_images SET slider_image = '$sliderImage' , thumb_image = '$thumbImage' , medium_image = '$mediumImage', watermark_image = '$waterMarkImage', original_image = '$originalImage' WHERE id = $lastInsertId ";
                    $db->setQuery($query);
                    $db->query();

                    // Rename the thumb and original image names into uploaded folder
                    rename($sliderPath . $imageName, $sliderPath . $sliderImage);
                    rename($mediumPath . $imageName, $mediumPath . $mediumImage);                    
                    rename($thumbPath . $imageName, $thumbPath . $thumbImage);
                    rename($watermarkPath . $imageName, $watermarkPath . $waterMarkImage);
                    rename($originalPath . $imageName, $originalPath . $originalImage);
                }            
            }

            // Update new image details during edit options
            else
            {
                $imageId = $data['jform']['id'];
                $albumid = $data['albumid'];
                $imageName = trim($data['image_name']);
                $imageTitle = trim($data['image']);
                $imageDesc = $data['jform']['image_description'];
                //$uploadOption = $data['uploadoption'];
                $imageLanguage = $data['jform']['language'];
                $imageDownload = $data['jform']['download'];
                $imageFeatured = $data['jform']['featured'];
               // $imageOrdering = $data['jform']['ordering'];
                $imageMetaKey = $data['jform']['meta_keywords'];
                $imageMetaDesc = $data['jform']['meta_description'];

                $db = JFactory::getDbo();
                $query = "update #__facegallery_images set image= '$imageTitle' , image_name = '$imageName' , image_description='$imageDesc' , albumid='$albumid' , language='$imageLanguage' , download='$imageDownload' , featured='$imageFeatured' ,  meta_keywords='$imageMetaKey' , meta_description='$imageMetaDesc' WHERE id = '$imageId'";

                $db->setQuery($query);
                $db->query();
            }
        }    
}