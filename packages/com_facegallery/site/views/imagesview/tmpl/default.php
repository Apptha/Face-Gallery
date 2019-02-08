<?php
/*
 *********************************************************************
 * @name        	Face Gallery
 * @version			1.0: views/imagesview/default.php$
 * @since       	Joomla 3.0
 * @package			apptha
 * @subpackage		com_facegallery
 * @author          Apptha - http://www.apptha.com
 * @copyright   	Copyright (C) 2013 powered by Apptha
 * @license			http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @creationDate    March 13 2013
 **********************************************************************
 */

//No direct access
defined('_JEXEC') or die("restricted access");

$value = $stringCut = $comments = $noComments = $downloadLink = $shareLink = null;

$user 			= JFactory::getUser(); 
$imageId 		= JRequest::getInt('id');
$albumId 		= JRequest::getInt('aid');
$imageDetails 	= $this->imageDetails;

$settings 		  = $this->settings;
$facebookApi 	  = $settings[0]->facebook_api;
$moderateComments = $settings[0]->moderate_comments;
$imageDownload    = $imageDetails->download; 
$title 			  = $imageDetails->image;
$displayTitle 	  = ucfirst($title);
$id 			  = $imageDetails->id;
$imageDescription = $imageDetails->image_description;
$allowDownload 	  = $settings[0]->download;
$allowSharing 	  = $settings[0]->sharing;

$btnPrev 		  = ($this->imagePrev) ? '<div class="preview_prev" onclick="getPhotoPreviewAjx(\'' . $this->imagePrev . '\')"><img src="' . JURI::Base() . 'components/com_facegallery/images/prev.png" alt="prev" /></div>' : '';
$btnNext 		  = ($this->imageNext) ? '<div class="preview_next" onclick="getPhotoPreviewAjx(\'' . $this->imageNext . '\')"><img src="' . JURI::Base() . 'components/com_facegallery/images/next.png" alt="prev" /></div>' : '';


//CODE TO DISPLAY THE IMAGE COMMENTS
$imageComments 	= $this->imageComments;

if (!empty($imageComments)) {
	
    foreach ($imageComments as $i => $comment) {
    	if(($moderateComments==1)&&($comment->state==1))
           {
        $commentDate = $comment->created_on;
        $commentDate = date("F d, Y", strtotime($commentDate));
        $comments .= <<<EOF
	 <div class="comment" id="{$comment->id}">
	 <p>{$comment->comment_text}</p>
	 <p><b>{$comment->comment_name}</b>
         <span>{$commentDate}</span></p>
 	 </div>
EOF;
    }
}}
else {
	$noComments = "no comments";
}

//CODE TO DISPLAY IMAGE TITLE,DESCRIPTION

$seeAll 			= JText::_('COM_FACEGALLERY_SEE_ALL');

if (!empty($imageDescription)) {
$string = strip_tags($imageDescription);
$stringCut = substr($string, 0, 50);
}

$imglength		 = strlen($imageDescription);
$stringCutlength = strlen($stringCut);
if( ($imglength != $stringCutlength) && (!empty($imageDescription)) )
{
$value = "{$seeAll}";    
}

$imgDate		 	= $imageDetails->updated_on;
$imageDate 			= date('F d, Y ', strtotime($imgDate));
$commentDescription = JText::_('COM_FACEGALLERY_WRITE_COMMENT');
$displaycomments 	= JText::_('COM_FACEGALLERY_COMMENTS');
$postComment 		= JText::_('COM_FACEGALLERY_POST_COMMENT');
$enterName 			= JText::_('COM_FACEGALLERY_ENTER_NAME');
$enterComments 		= JText::_('COM_FACEGALLERY_ENTER_COMMENTS');

//$image_name=$imageDetails->image_name;
$imgName 			= $imageDetails->image_name;
$url				= JURI::base().'images/facegallery/'.$imgName;

$downloadURL 		= JROUTE::_('index.php?option=com_facegallery&task=imageDownload&img_id='.$imageId);
$currentURL 		= JURI::getInstance()->toString();

$shareReturn 		= JRoute::_('index.php?option=com_facegallery&view=images&aid='.$albumId.'&imgid='.$imageId,true,-1);

$fbRenderURL        = urlencode($shareReturn); 
$thumbURL 			= JURI::Base() . 'images/facegallery/medium_image/' . $imageDetails->medium_image;
$description		= strip_tags($imageDetails->image_description);
$fbShare 			= "http://www.facebook.com/sharer/sharer.php?s=100&p[title]= $title&p[summary]=$description&p[medium]=102&p[url]=$fbRenderURL&p[images][0]=$thumbURL";

if($allowDownload && $imageDownload)
$downloadLink 		= "<li><a onClick=\"downloadImage('{$downloadURL}','_self')\">".JText::_('Download')."</a></li>";

if($allowSharing)
$shareLink 			= "<li class=\"middle_link\"><a onClick=\"downloadImage('{$fbShare}','_blank')\" >".JText::_('Share')."</a></li>";

//$fbLike 			= "<li><iframe src=\"https://www.facebook.com/plugins/like.php?href={$fbRenderURL}&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId={$facebookApi}\" scrolling=\"no\" frameborder=\"0\" style=\"border:none; overflow:hidden; width:72px; height:21px;\" allowTransparency=\"true\"></iframe></li>";

//CODE TO DISPLAY COMMENTS
$commentsArea = <<<EOF

    	<div  id="scrolldiv">
			<div class="popup-right">
	
			<h2 class="popup_image_title">{$displayTitle}</h2>
	        <span class="added_date">{$imageDate}</span>
	       
	        <p>
	        {$stringCut}<a href="#" id="example-show" class="showLink"  onclick="showHide('example');return false;" title="See All">{$value}</a> </p>
	        <div id="example" class="more"><p>{$imageDescription}</p> </div>
        
		    <div id='fb-root'></div>
			    <script src='http://connect.facebook.net/en_US/all.js'></script>
			    <ul class='comment_grid'>    			
    				 {$shareLink}
    				 {$downloadLink}    				
    			</ul>
    			<div class='clear'></div>    
				
			<h3 class="popup_section_title">{$commentDescription}</h3>
        	<form class="comment_section"  onsubmit="return false;">
	        <span class="comment_arrow"></span>
	        <input class="input-text" type="hidden" value="" name="returnURL" id="returnURL">
	        <input class="input-text" type="hidden" value="{$user->username}" title="Please enter your name" id="name" placeholder="{$enterName}" required/>
	        </td><textarea class="comment-input" name="text" id="text" resize="none" onfocus="" placeholder="{$enterComments}" required></textarea>
	        <button class="button_submit" id="button_id" onclick="submitComment({$imageId}); return false;">{$postComment}</button>
        </form>
       
        <div id="cmnt_list">
	        <h3 class="popup_section_title_comment">{$displaycomments}</h3>
	        <div id="al"> <span class="cmt_arrow"></span> </div>
	             <div id="comments_list">
		    	    {$comments}
		    	    <div id="no_comments">{$noComments}</div>
    		     </div>
    		</div>
		</div>
	</div>

<script type="text/javascript">
    $(function(){
      $('#scrolldiv').slimScroll({
          railVisible: true,
          railOpacity: 0.5
      });
    });
</script>
EOF;

require_once(JPATH_COMPONENT .'/helpers/Services_JSON.php');
//JSON CODING TO DISPLAY IMAGES AND COMMENT AREA
$oJson = new Services_JSON();
header('Content-Type:text/javascript');
echo $oJson->encode(array(
	    'data1' => '<img class="fileUnitSpacer" src="' . JURI::Base() . 'images/facegallery/watermark/' . $imageDetails->watermark_image . '">' . $btnPrev . $btnNext,
	    'data2' => $commentsArea,
	));
exit;

?>
