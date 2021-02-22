console.log("JS of 'Simple Forms'");
jQuery(document).ready(function(){

	jQuery("#dtBox").DateTimePicker({
		animationDuration: 200
	});

	jQuery(document).on('click', '#wpsf_closePopup, .wpsf_overlay', function(){
		jQuery('body .wpsf_popup').fadeOut('fast');
		setTimeout(function(){
			jQuery('body .wpsf_popup').remove();
		}, 100);
	});

	jQuery(document).on('change', '#offDate', function(){
		if(jQuery(this).val().length>0){
			jQuery(this).closest('form').find('[type="submit"]').removeAttr('disabled');
		}
		else {
			jQuery(this).closest('form').find('[type="submit"]').attr('disabled','disabled');
		}
	});

	if(jQuery('#below18').is(':checked')) {
		jQuery('.teenFields').slideDown();
		jQuery('.teenFields input').removeAttr('disabled');
	}
	if(jQuery('#scheduleNow').is(':checked')) {
		jQuery('.scheduleFields').slideDown();
		jQuery('.scheduleFields input').removeAttr('disabled');
	}
	jQuery(document).on('change', '.wpsf_form input[name="s_class"]', function(){
		var regChecked = jQuery("#scheduleNow").prop("checked");
		if(regChecked == true){
			jQuery('.scheduleFields').slideDown();
			jQuery('.scheduleFields input').removeAttr('disabled');
		}
		else {
			jQuery('.scheduleFields').slideUp();
			jQuery('.scheduleFields input').attr('disabled','disabled');
		}
	});
	jQuery(document).on('change', '.wpsf_form input[name="s_age"]', function(){
		var ageChecked = jQuery("#above18").prop("checked");
		if(ageChecked == true){
			jQuery('.teenFields').slideUp();
			jQuery('.teenFields input').attr('disabled','disabled');
		}
		else {
			jQuery('.teenFields').slideDown();
			jQuery('.teenFields input').removeAttr('disabled');
		}
	});

	jQuery(document).on('click', '.wpsf_forms_table .list-action.btn-delete', function(){
		var action = '';
		var showPopup = false;
		if(jQuery(this).data('page') && jQuery(this).data('action') && jQuery(this).data('form_id')) {
			showPopup = true;
			action = '<a href="?page='+jQuery(this).data('page')+'&action='+jQuery(this).data('action')+'&form_id='+jQuery(this).data('form_id')+'"  class="wpsf_btn wpsf_secondary">Yes</a>';
		}
		if(jQuery(this).data('page') && jQuery(this).data('action') && jQuery(this).data('date_id')) {
			showPopup = true;
			action = '<a href="?page='+jQuery(this).data('page')+'&action='+jQuery(this).data('action')+'&date_id='+jQuery(this).data('date_id')+'"  class="wpsf_btn wpsf_secondary">Yes</a>';
		}
		if(jQuery(this).data('page') && jQuery(this).data('action') && jQuery(this).data('mail_id')) {
			showPopup = true;
			action = '<a href="?page='+jQuery(this).data('page')+'&action='+jQuery(this).data('action')+'&mail_id='+jQuery(this).data('mail_id')+'"  class="wpsf_btn wpsf_secondary">Yes</a>';
		}
		if(showPopup){
			var wpsf_Popup = '<div class="wpsf_popup">';
			wpsf_Popup += '<div class="wpsf_overlay"></div>';
			wpsf_Popup += '<div class="wpsf_popupOuter">';
			wpsf_Popup += '<div class="wpsf_popupMiddle">';
			wpsf_Popup += '<div class="wpsf_popupInner">';
			wpsf_Popup += '<div class="wpsf_popupContent">';
			wpsf_Popup += '<h2>Are you sure you want to delete this form?</h2>';
			wpsf_Popup += '<div class="wpsf_confirmChoice">';
			wpsf_Popup += '<button type="button" class="wpsf_btn wpsf_primary" id="wpsf_closePopup">No</button>';
			wpsf_Popup += action;
			wpsf_Popup += '</div>';
			wpsf_Popup += '</div>';
			wpsf_Popup += '</div>';
			wpsf_Popup += '</div>';
			wpsf_Popup += '</div>';
			wpsf_Popup += '</div>';
			if(jQuery('body .wpsf_popup').length < 1){
				jQuery('body').append(wpsf_Popup);
				jQuery('body .wpsf_popup').fadeIn('fast');
			}
		}
	});
	
});
// jQuery.fn.selectText = function(){
//     this.find('input').each(function() {
//         if(jQuery(this).prev().length == 0 || !jQuery(this).prev().hasClass('p_copy')) { 
//             jQuery('<p class="p_copy" style="position: absolute; z-index: -1;"></p>').insertBefore(jQuery(this));
//         }
//         jQuery(this).prev().html(jQuery(this).val());
//     });
//     var doc = document;
//     var element = this[0];
//     if (doc.body.createTextRange) {
//         var range = document.body.createTextRange();
//         range.moveToElementText(element);
//         range.select();
//     } else if (window.getSelection) {
//         var selection = window.getSelection();        
//         var range = document.createRange();
//         range.selectNodeContents(element);
//         selection.removeAllRanges();
//         selection.addRange(range);
//     }
// };
// jQuery(document).on('click', '.wpsf_forms_table .shortcode.column-shortcode', function(e){
//     jQuery(this).selectText();
//     e.preventDefault();
// });

// function makeid(length) {
//     var result           = '';
//     var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
//     var charactersLength = characters.length;
//     for ( var i = 0; i < length; i++ ) {
//         result += characters.charAt(Math.floor(Math.random() * charactersLength));
//     }
//     return result;
// }

// var sqlval = '<textarea>INSERT INTO eagle_wpsf (title, shortcode) VALUES ';
// for (let i=1; i<45; i++){
//     var title = 'Title '+ makeid(5)+'';
//     var shortcode = '[wpsf id=&quot;'+i+'&quot; title=&quot;'+title+'&quot;]';
//     console.log(shortcode);
//     sqlval += "('"+i+" "+title+"', '"+window.btoa(shortcode)+"'),";
// }
// sqlval += ';</textarea>';

// $('body').append(sqlval);

// var fld = "<input type='text' onfocus='this.select();' readonly value='".base64_decode($shortcodeColumn['shortcode'])."' />";