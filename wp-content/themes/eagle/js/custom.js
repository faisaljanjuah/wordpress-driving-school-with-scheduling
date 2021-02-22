jQuery(document).ready(function () {
	"use strict";

	var baseUrl = window.location.href;
	jQuery('header .menuList li a').each(function () {
		if (jQuery(this).attr('href') == baseUrl) {
			jQuery(this).parent('li').addClass('active');
		}
	});

	jQuery(document).on('click', '.hamburger', function () {
		jQuery(this).toggleClass('is-active');
		jQuery(this).parent().find('.menuList').slideToggle();
	});

	jQuery('.selectLanguage').hover(function () {
		jQuery(this).attr('size', jQuery(this).find('option').length);
	}, function () {
		jQuery(this).attr('size', 1);
	});
	jQuery(document).on('click', '.selectLanguage li', function () {
		jQuery('.selectLanguage li').removeClass('active');
		jQuery(this).addClass('active');
	});

	jQuery(document).on('click', '.faq-single label', function () {
		jQuery(this).toggleClass('expanded');
		jQuery(this).parent().find('.accordionContent').slideToggle();
	});

	jQuery(document).on('click', '.eagle_popup .eagle_overlay, .eagle_popup .btn-close', function () {
		jQuery('body .eagle_popup').fadeOut('fast');
		setTimeout(function () {
			jQuery('.eagle_popup .appendMsg').html('');
		}, 100);
	});
	function getSchDate() {
		var thisdate = jQuery('.scheduledDate');
		$.each(dates, function (i, v) {
			if (thisdate.val() == v) {
				thisdate.val('');
				thisdate.closest('form').find('[type="submit"]').attr('disabled', 'disabled');
				var htmlMsg = '<h3>' + v + '</h3><h3>On this date  there is school holiday</h3><h2>Please select other date for your schedule.</h2>';
				jQuery('.eagle_popup .appendMsg').html(htmlMsg);
				jQuery('.eagle_popup').fadeIn('fast');
				return false;
			}
		});
	}
	function btnActive(elm) {
		var inputsFields = jQuery(elm);
		var btnactive = true;
		inputsFields.closest('.scheduleFields').find('input').each(function () {
			if (jQuery(this).val().length < 1) {
				btnactive = false;
				jQuery(this).addClass('error');
			}
			else {
				jQuery(this).removeClass('error');
			}
		});
		if (btnactive) { inputsFields.closest('form').find('[type="submit"]').removeAttr('disabled'); }
		else { inputsFields.closest('form').find('[type="submit"]').attr('disabled', 'disabled'); }
	}
	jQuery("#dtBox").DateTimePicker({
		animationDuration: 200,
		afterHide: function (thisElm) {
			if (jQuery(thisElm).hasClass("scheduledDate")) {
				getSchDate();
			}
			btnActive(thisElm);
		},
		beforeShow: function (oInputElement) {
			var oDTP = this;
			if (jQuery(oInputElement).hasClass("off_next")) {
				oDTP.settings.maxDate = new Date();
				oDTP.settings.minDate = '';
			}
			else if (jQuery(oInputElement).hasClass("off_prev")) {
				oDTP.settings.minDate = new Date();
				oDTP.settings.maxDate = '';
			}
			else {
				oDTP.settings.minDate = '';
				oDTP.settings.maxDate = '';
			}
		}
	});


	if (jQuery('#below18').is(':checked')) {
		jQuery('.teenFields').slideDown();
		jQuery('.teenFields input').removeAttr('disabled');
	}
	if (jQuery('#scheduleNow').is(':checked')) {
		jQuery('.scheduleFields').slideDown();
		jQuery('#scheduleNow').closest('form').find('[type="submit"]').attr('disabled', 'disabled');
		jQuery('.scheduleFields input').removeAttr('disabled');
	}
	if (jQuery('#schDate').val() != '') {
		jQuery('#schDate').closest('form').find('[type="submit"]').removeAttr('disabled');
	}

	jQuery(document).on('change', 'input[name="s_class"]', function () {
		var regChecked = jQuery("#scheduleNow").prop("checked");
		if (regChecked == true) {
			jQuery('.scheduleFields').slideDown();
			jQuery('.scheduleFields input').removeAttr('disabled').attr('required', 'required');
			jQuery(this).closest('form').find('[type="submit"]').attr('disabled', 'disabled');
		}
		else {
			jQuery('.scheduleFields').slideUp();
			jQuery('.scheduleFields input').attr('disabled', 'disabled').removeAttr('required');
			jQuery(this).closest('form').find('[type="submit"]').removeAttr('disabled', 'disabled');
		}
	});
	jQuery(document).on('change', 'input[name="s_age"]', function () {
		var ageChecked = jQuery("#above18").prop("checked");
		if (ageChecked == true) {
			jQuery('.teenFields').slideUp();
			jQuery('.teenFields input').attr('disabled', 'disabled');
			jQuery('#t_gpa').removeAttr('required');
		}
		else {
			jQuery('.teenFields').slideDown();
			jQuery('#t_gpa').attr('required', 'required');
			jQuery('.teenFields input').removeAttr('disabled');
		}
	});


	function menuHeight() {
		var st = jQuery(window).scrollTop();
		if (jQuery(window).width() < 520) {
			if (st > 64) { jQuery('header').addClass('sticky'); }
			else { jQuery('header').removeClass('sticky'); }
		}
		else {
			if (st > 37) { jQuery('header').addClass('sticky'); }
			else { jQuery('header').removeClass('sticky'); }
		}
		if (st > 154) { jQuery('header').addClass('shrink'); }
		else { jQuery('header').removeClass('shrink'); }
	} menuHeight();

	jQuery(window).scroll(function () {
		menuHeight();
	});

	if (jQuery('.aboutSlider').length > 0) {
		jQuery('.aboutSlider').slick({
			dots: false,
			arrows: true,
			autoplay: true,
			infinite: true,
			slidesToShow: 1,
			slidesToScroll: 1,
			adaptiveHeight: true,
			prevArrow: '<span class="arrow-prev">Prev</button>',
			nextArrow: '<span class="arrow-next">Next</button>'
		});
	}

	if (jQuery('.testimonialsSlider').length > 0) {
		jQuery('.testimonialsSlider').slick({
			dots: true,
			arrows: false,
			autoplay: true,
			infinite: true,
			slidesToShow: 1,
			slidesToScroll: 1,
			adaptiveHeight: true,
			prevArrow: '<span class="arrow-prev">Prev</button>',
			nextArrow: '<span class="arrow-next">Next</button>'
		});
	}



	jQuery(document).on('change', '.page-my-schedule input[type="radio"]', function () {
		var schChecked = jQuery("#alreadyReg").prop("checked");
		if (schChecked == false) {
			jQuery('.registrationInfo').hide();
			jQuery('.hideBtn').show();
		}
		else {
			jQuery('.registrationInfo').show();
			jQuery('.hideBtn').hide();
		}
	});
	if (jQuery('.page-my-schedule #notReg').is(':checked')) {
		jQuery('.registrationInfo').hide();
		jQuery('.hideBtn').show();
	}

	// Validations Start
	jQuery(document).on('input', '.numbersOnly', function () {
		this.value = this.value.replace(/[^0-9]+/g, '');
	});
	jQuery(document).on('mouseenter paste', '.numbersOnly', function () { // Prevent Paste & Drag
		var val = jQuery(this).val();
		val = val.replace(/[^0-9]+/g, "");
		jQuery(this).val(val);
	});
	jQuery(document).on('input', '.alphabetsOnly', function () {
		this.value = this.value.replace(/[^A-Za-z ]/g, '');
	});
	jQuery(document).on('mouseenter paste', '.alphabetsOnly', function () { // Prevent Paste & Drag
		var val = jQuery(this).val();
		val = val.replace(/[^A-Za-z ]+/g, "");
		jQuery(this).val(val);
	});
	jQuery(document).on('input', '.alphanumeric', function () {
		this.value = this.value.replace(/[^A-Za-z0-9 ]/g, '');
	});
	jQuery(document).on('mouseenter paste', '.alphanumeric', function () { // Prevent Paste & Drag
		var val = jQuery(this).val();
		val = val.replace(/[^A-Za-z0-9 ]+/g, "");
		jQuery(this).val(val);
	});
	jQuery(document).on("keypress", ".allowDecimals", function (evt) {
		if (evt.key == '%') {
			return false;
		}
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode == 8 || charCode == 37) {
			return true;
		} else if (charCode == 46 && jQuery(this).val().indexOf('.') != -1) {
			return false;
		} else if (charCode > 31 && charCode != 46 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	});
	jQuery(document).on("blur mouseenter paste", ".allowDecimals", function () {
		var val = jQuery(this).val();
		val = val.replace(/[^0-9\.]+/g, "");
		jQuery(this).val(val);
	});
	jQuery(document).on('input', '.validateEmail', function () {
		this.value = this.value.replace(/[^A-Za-z0-9\.\-_@]/g, '');
	});
	jQuery(document).on('mouseenter paste', '.validateEmail', function () { // Prevent Paste & Drag
		var val = jQuery(this).val();
		val = val.replace(/[^A-Za-z0-9\.\-_@]+/g, "");
		jQuery(this).val(val);
	});
	jQuery(document).on('blur input', '.validateEmail', function () {
		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		if (!emailReg.test(jQuery(this).val())) {
			jQuery(this).addClass('error');
		} else {
			jQuery(this).removeClass('error');
		}
	});
	jQuery(document).on('blur change', '.requiredField, [required]:not([type="email"])', function () {
		if ((!jQuery(this).val().replace(/\s/g, '').length) || jQuery(this).val().length < 1 || jQuery(this).val() == undefined || jQuery(this).val() == "") {
			jQuery(this).addClass('error');
		} else {
			jQuery(this).removeClass('error');
		}
	});
	jQuery(document).on('blur change', '.requiredSelect', function () {
		var val = jQuery(this).val();
		if ($.trim(val) == "" || $.trim(val) == undefined || $.trim(val) == "0") {
			jQuery(this).addClass('error');
		}
		else {
			jQuery(this).removeClass('error success');
		}
	});
	// Fields Validation Ends

});