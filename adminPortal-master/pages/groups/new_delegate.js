$(document).ready(function () {
	// Delegate registration
	UncheckAll();
	// $('#registerButton').prop('disabled', true);


	// Load captcha
	$("#reloadCaptcha").click(function () {
		var captchaImage = $('#captcha').attr('src');
		captchaImage = captchaImage.substring(0, captchaImage.lastIndexOf("?"));
		captchaImage = captchaImage + "?rand=" + Math.random() * 1000;
		$('#captcha').attr('src', captchaImage);
	});

	//Accept terms
	$("#privacy").change(function () {
		if (this.checked) {
			$('#registerButton').prop('disabled', false);
		} else {
			$('#registerButton').prop('disabled', true);
		}
	});


	/** Add link */
	$('#addLinkForm').submit(function () {
		var f = $(this).find('.form-group'),
			ferror = false,
			emailExp = /^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i;

		f.children('input').each(function () { // run all inputs
			var i = $(this); // current input
			var rule = i.attr('data-rule');
			if (rule !== undefined) {
				var ierror = false; // error flag for current input
				var pos = rule.indexOf(':', 0);
				if (pos >= 0) {
					var exp = rule.substr(pos + 1, rule.length);
					rule = rule.substr(0, pos);
				} else {
					rule = rule.substr(pos + 1, rule.length);
				}
				switch (rule) {
					case 'required':
						if (i.val() === '') {
							ferror = ierror = true;
						}
						break;
				}
				i.next('.validate').html((ierror ? (i.attr('data-msg') !== undefined ? i.attr('data-msg') : 'wrong Input') : '')).show('blind');
			}
		});
		f.children('textarea').each(function () { // run all inputs
			var i = $(this); // current input
			var rule = i.attr('data-rule');
			if (rule !== undefined) {
				var ierror = false; // error flag for current input
				var pos = rule.indexOf(':', 0);
				if (pos >= 0) {
					var exp = rule.substr(pos + 1, rule.length);
					rule = rule.substr(0, pos);
				} else {
					rule = rule.substr(pos + 1, rule.length);
				}
				switch (rule) {
					case 'required':
						if (i.val() === '') {
							ferror = ierror = true;
						}
						break;
				}
				i.next('.validate').html((ierror ? (i.attr('data-msg') != undefined ? i.attr('data-msg') : 'wrong Input') : '')).show('blind');
			}
		});

		if (ferror) return false;
		else var str = $(this).serialize();



		var this_form = $(this);
		var action = $(this).attr('action');

		$("#addLinkButton").button('loading');


		$.ajax({
			type: "POST",
			url: action,
			data: str,
			cache: false,
			success: function (dataResponse) {

				var response = JSON.parse(dataResponse);
				if (response.success == true) {
					$("#addLinkButton").button('reset');
					$("#addLinkForm")[0].reset();
					$('#add-link-messages').html('<div class="sent-message">' + response.messages + '</div>');
					this_form.find('.sent-message').slideDown().html(response.messages);
					$(".sent-message").delay(500).show(20, function () {
						$(this).delay(12000).hide(10, function () { });
						$('#generateLinkModal .close').click();
					});
				} else {
					$("#addLinkButton").button('reset');
					$('#add-link-messages').html('<div class="error-message">' + response.messages + '</div>');
					this_form.find('.error-message').slideDown().html(response.messages);
					$(".error-message").delay(500).show(10, function () { });
				}
			}
		});
		return false;
	});

});

function Other(field, field1) {
	var value = $(field).val();
	var name = $(field).attr('name');
	var name1 = $(field1).attr('name');
	if (value == "Other") {
		if (!$(field).hasClass('swapped')) {
			$(field).addClass('swapped');
			$(field1).prop('disabled', false);
			var input = $(field1);
			input[0].selectionStart = input[0].selectionEnd = input.val().length;
		}
	} else {
		if ($(field).hasClass('swapped')) {
			$(field1).val("");
			$(field).removeClass('swapped');
			$(field1).prop('disabled', true);
		} else {
			$(field1).val("");
			$(field1).prop('disabled', true);
		}
	}
	$('#' + name + '_error').text("");
}




function validateEmail(email) {
	var re = /\S+@\S+\.\S+/;
	return re.test(email);
}


$('.registerFormSubmit').on('click', function () {
	// location.href = "#" + "registerForm";
	$('.field-validate .validate').text('');

	var f = $("#registerForm").find('.field-validate'),
		ferror = false,
		emailExp = /^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i;

	f.children('input').each(function () { // run all inputs
		var i = $(this); // current input
		var rule = i.attr('data-rule');
		if (rule !== undefined) {
			var ierror = false; // error flag for current input
			var pos = rule.indexOf(':', 0);
			if (pos >= 0) {
				var exp = rule.substr(pos + 1, rule.length);
				rule = rule.substr(0, pos);
			} else {
				rule = rule.substr(pos + 1, rule.length);
			}
			switch (rule) {
				case 'required':
					if (i.val() === '') {
						ferror = ierror = true;
					}
					break;
				case 'email':
					if (!emailExp.test(i.val())) {
						ferror = ierror = true;
					}
					break;
			}
			i.next('.validate').html((ierror ? (i.attr('data-msg') !== undefined ? i.attr('data-msg') : 'wrong Input') : '')).show('blind');
		}
	});
	f.children('select').each(function () { // run all inputs
		var i = $(this); // current input
		var rule = i.attr('data-rule');
		if (rule !== undefined) {
			var ierror = false; // error flag for current input
			var pos = rule.indexOf(':', 0);
			if (pos >= 0) {
				var exp = rule.substr(pos + 1, rule.length);
				rule = rule.substr(0, pos);
			} else {
				rule = rule.substr(pos + 1, rule.length);
			}
			switch (rule) {
				case 'required':
					if (i.val() === '') {
						ferror = ierror = true;
					}
					break;
			}
			i.next('.validate').html((ierror ? (i.attr('data-msg') != undefined ? i.attr('data-msg') : 'wrong Input') : '')).show('blind');
		}
	});
	f.children('textarea').each(function () { // run all inputs
		var i = $(this); // current input
		var rule = i.attr('data-rule');
		if (rule !== undefined) {
			var ierror = false; // error flag for current input
			var pos = rule.indexOf(':', 0);
			if (pos >= 0) {
				var exp = rule.substr(pos + 1, rule.length);
				rule = rule.substr(0, pos);
			} else {
				rule = rule.substr(pos + 1, rule.length);
			}
			switch (rule) {
				case 'required':
					if (i.val() === '') {
						ferror = ierror = true;
					}
					break;
			}
			i.next('.validate').html((ierror ? (i.attr('data-msg') != undefined ? i.attr('data-msg') : 'wrong Input') : '')).show('blind');
		}
	});

	var eventCode_ = $('#_EvCode_').val();
	var eventParticiaptionCode_ = $('#_EvPCode_').val();
	var birthday = $('#birthday').val();

	if (eventCode_ == 'INP001') {
		if ($('#image').val().length == 0 && $('#image').attr('data-rule') == 'required') {
			ferror = ierror = true;
			$('#image_error').text($('#image').attr('data-msg'));
		}
	}

	if (eventParticiaptionCode_ == "STYR003") {
		if (birthday == 'dd/mm/yyyy' || birthday == 'dd/mm/yyyy') {
			ferror = ierror = true;
			$('#birthday_error').text($('#birthday').attr('data-msg'));
		}
		else {
			birthday = convertJSDateFormat(birthday);
			if (!checkAgeAtDateOfEvent(birthday, 10, 35)) {
				ferror = ierror = true;
				$('#birthday_error').text($('#birthday').attr('data-msgc'));
			}
		}
	}

	else {
		if (birthday != 'dd/mm/yyyy' || birthday != 'dd/mm/yyyy') {
			birthday = convertJSDateFormat(birthday);
			if (!checkAgeAtDateOfEvent(birthday, 10, 150)) {
				ferror = ierror = true;
				$('#birthday_error').text($('#birthday').attr('data-msgc'));
			}
		}
	}


	/** Validation For African Based - Organization Section -  */
	if (eventParticiaptionCode_ == 'AFBR004' || eventParticiaptionCode_ == 'CBOR005') {
		if ($('#african_country').val().length === 0) {
			ferror = ierror = true;
			$('#organisation_country_error').text($('#african_country').attr('data-msg'));
		}
	}

	/** Validation For Non African Based - Organization Section -  */
	else if (eventParticiaptionCode_ == 'NAFBR002') {
		if ($('#non_african_country').val().length === 0) {
			ferror = ierror = true;
			$('#organisation_country_error').text($('#non_african_country').attr('data-msg'));
		}
	}

	/** Validation For Other - organization_country -  */
	else {
		if ($('#organisation_country').val().length === 0) {
			ferror = ierror = true;
			$('#organisation_country_error').text($('#organisation_country').attr('data-msg'));
		}
	}

	if ($('#organisation_city').val().length === 0) {
		ferror = ierror = true;
		$('#organisation_city_error').text($('#organisation_city').attr('data-msg'));
	}

	var request_ = $("input[name='request'").val();

	resgistrationFormValidation(eventCode_, eventParticiaptionCode_);

	if (request_ != 'registrationUpdate') {
		if ($('#password').val().length === 0) {
			ferror = ierror = true;
			$('#password_error').text($('#password').attr('data-msg'));
		}
		if ($('#password').val().length < 6) {
			ferror = ierror = true;
			$('#password_error').text($('#password').attr('data-msg'));
		}
		if ($('#confirm_password').val().length === 0) {
			ferror = ierror = true;
			$('#confirm_password_error').text($('#confirm_password').attr('data-msg'));
		}
		if ($('#password').val() != $('#confirm_password').val()) {
			ferror = ierror = true;
			$('#confirm_password_error').text($('#confirm_password').attr('data-msg'));
		}
	}
	else {
		if ($('#password').val().length != 0 || $('#confirm_password').val().length != 0) {
			if ($('#password').val().length === 0) {
				ferror = ierror = true;
				$('#password_error').text($('#password').attr('data-msg'));
			}
			if ($('#password').val().length < 6) {
				ferror = ierror = true;
				$('#password_error').text($('#password').attr('data-msg'));
			}
			if ($('#confirm_password').val().length === 0) {
				ferror = ierror = true;
				$('#confirm_password_error').text($('#confirm_password').attr('data-msg'));
			}
			if ($('#password').val() != $('#confirm_password').val()) {
				ferror = ierror = true;
				$('#confirm_password_error').text($('#confirm_password').attr('data-msg'));
			}
		}
	}

	if ($('#telephone').val().length > 0) {
		var full_telephone = phone_number.getNumber(intlTelInputUtils.numberFormat.E164);
		$("input[name='full_telephone'").val(full_telephone);
	}

	if ($('#telephone_2').val().length > 0) {
		var full_telephone_2 = phone_number_2.getNumber(intlTelInputUtils.numberFormat.E164);
		$("input[name='full_telephone_2'").val(full_telephone_2);
	}

	if ($('#emergency_telephone').val().length > 0) {
		var full_telephone_3 = phone_number_emergency.getNumber(intlTelInputUtils.numberFormat.E164);
		$("input[name='emergency_full_telephone'").val(full_telephone_3);
	}

	/* ######### Loader ########## */
	$("#load").removeAttr("hidden");
	var str = "";
	if (ferror) {
		location.href = "#" + "registerForm";

		/* ######### Loader ########## */
		window.setTimeout(function () {
			$("#load").attr("hidden", "");
		}, 1000);

		return false;
	}
	str = $('#registerForm').serialize();

	var this_form = $('#registerForm');
	var action = $('.host').attr('link') + "/registration";
	var remote = $('.host').attr('link2') + "/registration";
	var inputCaptcha = document.getElementById("securityCode").value.trim();

	$('#registerButton').prop('disabled', true);

	$.ajax({
		type: "POST",
		url: action,
		data: { request: "captchaSession" },
		cache: false,
		success: function (responseData) {
			var responseCaptcha = JSON.parse(responseData);

			if (responseCaptcha.messages == inputCaptcha) {

				var form = $('#registerForm')[0];
				var formData = new FormData(form);
				// event.preventDefault();

				$.ajax({
					type: "POST",
					url: remote,
					data: formData,
					cache: false,
					processData: false,
					contentType: false,
					success: function (dataResponse) {

						// /* ######### Loader ########## */
						// window.setTimeout(function () {
						// 	$("#load").attr("hidden", "");
						// }, 1000);

						var response = JSON.parse(dataResponse);
						if (response.status == 101) {
							$('#registerButton').prop('disabled', false);
							$(form)[0].reset();
							$(" #editLinkButton").button('reset');
							
							var FormKey = 'div';
							
							$("html, body, div#register_area, div#registerForm").animate({ scrollTop: '0' }, 100);
							$(FormKey + ' #register-messages').html('<div class="sent-message">' + response.message + '</div>');
							this_form.find(' .sent-message').slideDown().html(response.message);
							$(FormKey + " .sent-message").delay(400).show(20, function () {
								$(this).delay(7000).hide(50, function () { });
							});
		
							// setTimeout(function () {
							// 	$(FormKey + ' .close').delay(1200).click();
							// }, 5000);
						}
						else {
							/* ######### Loader ########## */
							window.setTimeout(function () {
								$("#load").attr("hidden", "");
							}, 1000);

							$('#registerButton').prop('disabled', false);
							$("html, body, div#register_area, div#registerForm").animate({ scrollTop: '0' }, 100);
							$('#register-messages').html('<div class="error-message">' + response.message + '</div>');
							this_form.find('.error-message').slideDown().html(response.message);
							$(".error-message").delay(500).show(10, function () {

							});
						}
					}
				});
			} else {
				
				$('#registerButton').prop('disabled', false);
				$('#securityCode_error').text("Invalid security code");
			}
			/* ######### Loader ########## */
			window.setTimeout(function () {
				$("#load").attr("hidden", "");
			}, 1000);
		}
	});
	return false;

});


function resgistrationFormValidation(eventCode, eventParticiaptionCode) {

}

function validate(input) {
	var input_value = $(input).val();
	var input_id = $(input).attr('id');
	var error_validate_id = '#' + $(input).attr('id') + '_error';
	var error_validate_msg = '';

	if (input_value.length <= 1)
		error_validate_msg = 'Please fill this field';

	if (input_id == 'email')
		if (!validateEmail(input_value) || input_value.length <= 5)
			error_validate_msg = 'Please fill this field with valid email';

	if (input_id == 'confirm_email')
		if (!validateEmail(input_value) || input_value.length <= 5)
			error_validate_msg = 'Please fill this field with valid email';

	// if (input_id == 'birthday')
	// 	if (!checkAgeAtDateOfEvent(input_value, 10, 35))
	// 		error_validate_msg = 'Only people with age between 10 and 35 can register to this event';

	// if (input_id == 'telephone')
	// 	alert("Telephone Entered :: " + input_value);

	$(error_validate_id).text(error_validate_msg);
}

function convertToDate(str) {
	var arr = str.split("-"); // split string at slashes to make an array
	var yyyy = arr[0] - 0; // subtraction converts a string to a number
	var jsmm = arr[1] - 1; // subtract 1 because stupid JavaScript month numbering
	var dd = arr[2] - 0; // subtraction converts a string to a number 
	return new Date(yyyy, jsmm, dd); // this gets you your date
}

function getDateTime(str_date) {
	return convertToDate(str_date).getTime();
}

function getAgeAtDateOfEvent(inputDate) {
	var str = inputDate.split('-');
	var dateTo = getDateTime('2021-12-04'); // Date Of Participant
	var dateFrom = getDateTime(inputDate); // Date Of Event

	var dayDiff = Math.trunc(Math.ceil(dateTo - dateFrom) / (1000 * 60 * 60 * 24 * 365)); // Age At The Date Of The Event
	return dayDiff;
}

function checkAgeAtDateOfEvent(inputDate, ageLimitAuthorizedFrom, ageLimitAuthorizedTo) {
	var ageInput = getAgeAtDateOfEvent(inputDate);
	// alert("AGE :: " + ageInput + " Lim 1 :: " + ageLimitAuthorizedFrom + " Lim 2 :: " + ageLimitAuthorizedTo + " Condit :: " + ((ageInput >= ageLimitAuthorizedFrom && ageInput <= ageLimitAuthorizedTo) ? "true" : "false"));
	return (ageInput >= ageLimitAuthorizedFrom && ageInput <= ageLimitAuthorizedTo) ? true : false;
}

function multilangselect(lang) {
	var action = $('.host').attr('link') + "/language";
	$.ajax({
		type: 'POST',
		url: action,
		data: { lang: lang, request: "selectLanguage" },
		success: function (data) {
			window.location.reload(true);
		}
	});
}

function convertJSDateFormat(birthday) {
	var arr = birthday.split("/");
	var yyyy = arr[2] - 0;
	var jsmm = arr[1] - 0;
	var dd = arr[0] - 0;
	birthday = yyyy + "-" + jsmm + "-" + dd;
	return birthday;
}

function UncheckAll(){ 
  var w = document.getElementsByTagName('input'); 
  for(var i = 0; i < w.length; i++){ 
    if(w[i].type=='checkbox'){ 
      w[i].checked = false; 
    }
  }
}

