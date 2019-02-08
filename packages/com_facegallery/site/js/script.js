/*
 *************************************************************************
 * @name            Face Gallery
 * @version         1.0: js/script.js$
 * @since           Joomla 3.0
 * @package         apptha
 * @subpackage      com_facegallery
 * @author          Apptha - http://www.apptha.com
 * @copyright       Copyright (C) 2011 Powered by Apptha
 * @license 		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @creationDate    March 13 2013
 **************************************************************************
 */

// close image preview block
function closePhotoPreview() {
	jQuery('#image_preview').hide();
	jQuery('#image_preview .pleft').html('empty');
	jQuery('#image_preview .pright').html('empty');
};
// display image preview block
var dHeight = window.innerHeight;

function getPhotoPreviewAjx(id) {
	jQuery.post(FgalleryImageView, {
		action : '',
		id : id
	}, function(data) {
		
		jQuery('#image_preview .pleft').html(data.data1);
		jQuery('#image_preview .pright').html(data.data2);	
		jQuery('#image_preview').show();
		
		jQuery('.image_wrp').css("height", dHeight-50);
		jQuery('.pleft').css("height", dHeight-50);
        jQuery('#img').css("max-height", dHeight-50);
		jQuery('.pright').css("height", dHeight-50);                
		jQuery('.image_wrp').css("width", jQuery('.image_wrp').width());
		
        jQuery('.pleft').css("line-height", dHeight-50+'px');
                
		//FB.XFBML.parse();
		// alert(height);

	}, "json");

	

};

// submit comment
function submitComment(id) {
	var sName = jQuery('#name').val();
	var sText = jQuery('#text').val();

	if ((sName == "")) {
		alert("Please login to post your comment...");
		var loginURL = document.getElementById("returnURL").value;
		window.location = loginURL;
	}

	if (sName && sText) {
		jQuery.post(FgalleryImageView, {
			action : 'addComment',
			name : sName,
			text : sText,
			id : id,
			success : function(response) {
				var m_names = new Array("January", "February", "March",
						"April", "May", "June", "July", "August", "September",
						"October", "November", "December");

				var d = new Date();
				var curr_date = d.getDate();
				var curr_month = d.getMonth();
				var curr_year = d.getFullYear();
				var curr_hour = d.getHours();
				var curr_min = d.getMinutes();
				var length = sText.length;
				var letters = /^[A-Za-z]+$/;

				if (length < 100) {

					jQuery('#al').append(
							'<div class="comment" id="' + id + '"><p>' + sText
									+ '</p><b> ' + sName + '</b> <span>'
									+ m_names[curr_month] + " " + curr_date
									+ "," + curr_year + '</span></div>');
					document.getElementById("no_comments").innerHTML="";

				} else {
					alert('Comments must be in 100 characters');

				}

			}
		}, function(data) {
			if (data != '1') {

				jQuery('#comments_list').fadeOut(1000, function() {
					// $('.comment').append($('addComment'));
					jQuery(this).html(data);
					jQuery(this).fadeIn(1000);

				});
			} else {
				jQuery('#comments_warning2').fadeIn(1000, function() {
					jQuery(this).fadeOut(1000);
				});
			}
		});
		
	} else {
		jQuery('#comments_warning1').fadeIn(1000, function() {
			jQuery(this).fadeOut(1000);
		});
	}
	$("#text").val('');

};
// init
function showHide(shID) {
	if (document.getElementById(shID)) {
		if (document.getElementById(shID + '-show').style.display != 'none') {
			document.getElementById(shID + '-show').style.display = 'none';
			document.getElementById(shID).style.display = 'block';
		} else {
			document.getElementById(shID + '-show').style.display = 'inline';
			document.getElementById(shID).style.display = 'none';
		}
	}
}


function downloadImage(downloadURL,target){		
	window.open(downloadURL,target);
}

jQuery(function() {

	// onclick event handlers
	jQuery('#image_preview .image_wrp').click(function(event) {
		event.preventDefault();
		return false;
	});

	jQuery('#image_preview').click(function(event) {
		closePhotoPreview();
	});
	jQuery('#image_preview img.close').click(function(event) {
		closePhotoPreview();
	});

	// display image preview ajaxy
	jQuery('.image_view_container .image a').click(function(event) {
		if (event.preventDefault)
			event.preventDefault();
		getPhotoPreviewAjx(jQuery(this).attr('id'));
	});
})

	