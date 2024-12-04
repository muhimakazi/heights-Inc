$(document).ready(function () {
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

		if ($('#telephone').val().length === 0) {
			ferror = ierror = true;
			$('#telephone_error').text("Please enter telephone");
		}

		var eventCode_ = $('#_EvCode_').val();
		var eventParticiaptionCode_ = $('#_EvPCode_').val();

		var request_ = $("input[name='request'").val();

		if ($('#telephone').val().length > 0) {
			var full_telephone = phone_number.getNumber(intlTelInputUtils.numberFormat.E164);
			$("input[name='full_telephone'").val(full_telephone);
		}

		/* ######### Loader ########## */
		$("#load").removeAttr("hidden");
		var str = "";
		if (ferror) {
			return false;
		}
		str = $('#registerForm').serialize();

		var this_form = $('#registerForm');

		$('#registerButton').prop('disabled', true);

		var form = $('#registerForm')[0];
		var formData = new FormData(form);
		// event.preventDefault();

		$.ajax({
			type: "POST",
			url: linkto,
			data: formData,
			cache: false,
			processData: false,
			contentType: false,
			success: function (dataResponse) {

				/* ######### Loader ########## */
				window.setTimeout(function () {
					$("#load").attr("hidden", "");
				}, 1000);

				var response = JSON.parse(dataResponse);
				if (response.status == 100 || response.status == 101 || response.status == 200) {
					$('#registerButton').prop('disabled', false);
					$("#registerForm")[0].reset();
					$("html, body, div, div#registerForm").animate({scrollTop: '0'}, 100);
					$('#register-messages').html('<div class="sent-message">'+ response.message + '</div>');
		          	this_form.find('.sent-message').slideDown().html(response.message);
					$(".sent-message").delay(500).show(10, function() {
						$(this).delay(3000).hide(10, function() {
							$(this).remove();
						});
					});
				}
				else {
					$('#registerButton').prop('disabled', false);
					$("html, body, div, div#registerForm").animate({ scrollTop: '0' }, 100);
					$('#register-messages').html('<div class="error-message">' + response.message + '</div>');
					this_form.find('.error-message').slideDown().html(response.message);
					$(".error-message").delay(500).show(10, function () {

					});
				}
			}
		});
			
		return false;

	});

	$(document).on('click', '#checkAll', function () {
		$(".itemRow").prop("checked", this.checked);
	});
	$(document).on('click', '.itemRow', function () {
		if ($('.itemRow:checked').length == $('.itemRow').length) {
			$('#checkAll').prop('checked', true);
		} else {
			$('#checkAll').prop('checked', false);
		}
	});
	var count = $(".itemRow").length;
	$(document).on('click', '#addRows', function () {
		count++;
		var htmlRows = '';

		htmlRows += '<tr>';
		htmlRows += '<td><input class="itemRow" type="checkbox"></td>';
		htmlRows += '<td><select name="participation_category[]" id="participation_category_' + count + '" onchange="getPartSubCategory(' + count + ')" class="form-control participation_category" data-rule="required" data-msg="Please select"></select><div class="validate"></div></td>';
		htmlRows += '<td><select name="participation_sub_category[]" id="participation_sub_category_' + count + '" onchange="getPrice(' + count + ')" class="form-control" data-rule="required" data-msg="Please select"></select><div class="validate"></div></td>';
		htmlRows += '<td><input type="number" name="maximum_delegates[]" id="maximum_delegates_' + count + '" class="form-control quantity" oninput="calculateTotal()" autocomplete="off"></td>';
		htmlRows += '<td><input type="text" name="price[]" id="price_' + count + '" class="form-control price" readonly></td>';
		htmlRows += '<td><input type="number" name="total[]" id="total_' + count + '" class="form-control total" readonly></td>';
		htmlRows += '</tr>';

		$('#invoiceItem').append(htmlRows);
		getPartCategory(count);
	});
	$(document).on('click', '#removeRows', function () {
		$(".itemRow:checked").each(function () {
			$(this).closest('tr').remove();
		});
		$('#checkAll').prop('checked', false);
		calculateTotal();
	});
	$(document).on('blur', "[id^=maximum_delegates_]", function () {
		calculateTotal();
	});
	$(document).on('blur', "[id^=price_]", function () {
		calculateTotal();
	});
	$(document).on('blur', "#taxRate", function () {
		calculateTotal();
	});
	$(document).on('blur', "#amountPaid", function () {
		var amountPaid = $(this).val();
		var totalAftertax = $('#totalAftertax').val();
		if (amountPaid && totalAftertax) {
			totalAftertax = totalAftertax - amountPaid;
			$('#amountDue').val(totalAftertax);
		} else {
			$('#amountDue').val(totalAftertax);
		}
	});
	$(document).on('click', '.deleteInvoice', function () {
		var id = $(this).attr("id");
		if (confirm("Are you sure you want to remove this?")) {
			$.ajax({
				url: "action.php",
				method: "POST",
				dataType: "json",
				data: { id: id, action: 'delete_invoice' },
				success: function (response) {
					if (response.status == 1) {
						$('#' + id).closest("tr").remove();
					}
				}
			});
		} else {
			return false;
		}
	});

	// Load captcha
	$("#reloadCaptcha").click(function () {
		var captchaImage = $('#captcha').attr('src');
		captchaImage = captchaImage.substring(0, captchaImage.lastIndexOf("?"));
		captchaImage = captchaImage + "?rand=" + Math.random() * 1000;
		$('#captcha').attr('src', captchaImage);
	});

	// Accept terms
	$("#privacy").change(function () {
		if (this.checked) {
			$('#registerButton').prop('disabled', false);
		} else {
			$('#registerButton').prop('disabled', true);
		}
	});
});
function calculateTotal() {
	var totalAmount = 0;
	$("[id^='price_']").each(function () {
		var id = $(this).attr('id');
		id = id.replace("price_", '');
		var price = $('#price_' + id).val();
		var quantity = $('#maximum_delegates_' + id).val();
		if (!quantity) {
			quantity = 1;
		}
		var total = price * quantity;
		$('#total_' + id).val(parseFloat(total));
		totalAmount += total;
	});
	$('#subTotal').val(parseFloat(totalAmount));
	// var taxRate = $("#taxRate").val();
	// var subTotal = $('#subTotal').val();	
	// if(subTotal) {
	// 	var taxAmount = subTotal*taxRate/100;
	// 	$('#taxAmount').val(taxAmount);
	// 	subTotal = parseFloat(subTotal)+parseFloat(taxAmount);
	// 	$('#totalAftertax').val(subTotal);		
	// 	var amountPaid = $('#amountPaid').val();
	// 	var totalAftertax = $('#totalAftertax').val();	
	// 	if(amountPaid && totalAftertax) {
	// 		totalAftertax = totalAftertax-amountPaid;			
	// 		$('#amountDue').val(totalAftertax);
	// 	} else {		
	// 		$('#amountDue').val(subTotal);
	// 	}
	// }
}

function getPartCategory(count) {
	$.ajax({
		type: "POST",
		url: linkto,
		data: { request: "getPartCategory" },
		success: function (data) {
			$("#participation_category_" + count).html(data);
		}
	});
}

function getPartSubCategory(count) {
	var part_cat_id = document.getElementById('participation_category_' + count).value;
	$.ajax({
		type: "POST",
		url: linkto,
		data: { part_cat_id: part_cat_id, request: "getPartSubCategory" },
		success: function (data) {
			$("#participation_sub_category_" + count).html(data);
		}
	});
}

function getPartSubCategory1() {
	var part_cat_id = document.getElementById('participation_category_1').value;
	$.ajax({
		type: "POST",
		url: linkto,
		data: { part_cat_id: part_cat_id, request: "getPartSubCategory" },
		success: function (data) {
			$("#participation_sub_category_1").html(data);
		}
	});
}


function getPrice(count) {
	var part_sub_cat_id = document.getElementById('participation_sub_category_' + count).value;
	$.ajax({
		type: "POST",
		url: linkto,
		data: { part_sub_cat_id: part_sub_cat_id, request: "getParticipantPrice" },
		success: function (data) {
			$("#price_" + count).val(data);
		}
	});

	calculateTotal();
}

function getPrice1() {
	var part_sub_cat_id = document.getElementById('participation_sub_category_1').value;
	$.ajax({
		type: "POST",
		url: linkto,
		data: { part_sub_cat_id: part_sub_cat_id, request: "getParticipantPrice" },
		success: function (data) {
			$("#price_1").val(data);
		}
	});

	calculateTotal();
}
