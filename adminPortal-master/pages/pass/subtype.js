$(document).ready(function () {
	showPassSubTypesContent();

	/** Add  */
	$('#addForm').submit(function () {
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

		if (ferror) return false;
		else var str = $(this).serialize();

		var this_form = $(this);
		
		$("#addButton").button('loading');

		$.ajax({
			type: "POST",
			url: linkto,
			data: str,
			cache: false,
			success: function (dataResponse) {

				var response = JSON.parse(dataResponse);
				if (response.success == true) {
					$("#addButton").button('reset');
					$("#addForm")[0].reset();
					showPassSubTypesContent();
					$('#add-messages').html('<div class="sent-message">' + response.messages + '</div>');
					this_form.find('.sent-message').slideDown().html(response.messages);
					$(".sent-message").delay(500).show(20, function () {
						$(this).delay(12000).hide(10, function () { });
						// $('#generateLinkModal .close').click();
					});

					setTimeout(function () {
						$('#addModal .close').delay(1200).click();
					}, 5000);
				} else {
					$("#addButton").button('reset');
					$('#add-messages').html('<div class="error-message">' + response.messages + '</div>');
					this_form.find('.error-message').slideDown().html(response.messages);
					$(".error-message").delay(500).show(10, function () { });
				}
			}
		});
		return false;
	});

	//EDIT PASS SUB TYPE
	$(document).on('click', '.edit_subtype', function () {
		var passId = $(this).data('id');
		var type = $('#eType' + passId).text();
		var name = $('#eName' + passId).text();
		var category = $('#eCategory' + passId).text();
		var paymentStatus = $('#ePaymentStatus' + passId).text();
		var price = $('#ePrice' + passId).text();
		var currency = $('#eCurrency' + passId).text();
		
		$('#editModal').modal('show');
		$('#eparticipation_type').val(type);
		$('#ename').val(name);
		$('#ecategory').val(category);
		$('#epayment_state').val(paymentStatus);
		$('#eprice').val(price);
		$('#ecurrency').val(currency);
		$('#passId').val(passId);
	});


	$('#editForm').submit(function () {
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
					case "required":
			            if (i.val() === "") {
			              ferror = ierror = true;
			            }
			            break;
				}
				i.next('.validate').html((ierror ? (i.attr('data-msg') !== undefined ? i.attr('data-msg') : 'wrong Input') : '')).show('blind');
			}
		});
		f.children("select").each(function () {
	      // run all inputs
	      var i = $(this); // current input
	      var rule = i.attr("data-rule");
	      if (rule !== undefined) {
	        var ierror = false; // error flag for current input
	        var pos = rule.indexOf(":", 0);
	        if (pos >= 0) {
	          var exp = rule.substr(pos + 1, rule.length);
	          rule = rule.substr(0, pos);
	        } else {
	          rule = rule.substr(pos + 1, rule.length);
	        }
	        switch (rule) {
	          case "required":
	            if (i.val() === "") {
	              ferror = ierror = true;
	            }
	            break;
	        }
	        i.next(".validate").html(ierror? i.attr("data-msg") != undefined ? i.attr("data-msg") : "wrong Input" : "").show("blind");
	      }
	    });
		
		if (ferror) return false;
		else var str = $(this).serialize();
		
		// $("#editUserButton").button('loading');
		$.ajax({
			type: 'POST',
			url: linkto,
			data: str,
			dataType: 'json',
			success: function (response) {

				if (response.success == true) {
					$("#editButton").button('reset');
					$("#editForm")[0].reset();
					showPassSubTypesContent();
					$("html, body, div.modal, div.modal-content, div.modal-body").animate({ scrollTop: '0' }, 100);
					$('#edit-messages').html('<div class="alert alert-success">' +
						'<button type="button" class="close" data-dismiss="alert">&times;</button>' +
						'<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> ' + response.messages +
						'</div>');
					$(".alert-success").delay(500).show(10, function () {
						$(this).delay(3000).hide(10, function () {
							$(this).remove();
							$('#editModal').modal('hide');
						});
					});
				} else {
					$("#editButton").button('reset');
					$("html, body, div.modal, div.modal-content, div.modal-body").animate({ scrollTop: '0' }, 100);
					$('#edit-messages').html('<div class="alert alert-danger">' +
						'<button type="button" class="close" data-dismiss="alert">&times;</button>' +
						'<strong><i class="fa fa-times-circle icon"></i></strong> ' + response.messages +
						'</div>');
					$(".alert-danger").delay(500).show(10, function () {

					});
				}
			}
		});
		return false;
	});


	//CHANGE TYPE STATUS
	$(document).on('click', '.confirm_modal', function() {
		var passId   = $(this).data('id');
		var name = $('#eName' + passId).text();
		var request   = $(this).data('request');

		$('#confirmModal').modal('show');

		$('#confirmId').val(passId);
		$('#request').val(request);

		if (request == "activatePassSubType") {
			$('#confirmTitle').html("Activate pass type");
			$('#confirmQuestion').html('Do you really want to activate <strong>'+ name +'</strong> pass?');
		} else if (request == "deactivatePassSubType") {
			$('#confirmTitle').html("Deactivate pass type");
			$('#confirmQuestion').html('Do you really want to deactivate <strong>'+ name +'</strong> pass?');
		}
	});

	$("#confirmForm input").jqBootstrapValidation({
        preventSubmit: true,
        submitError: function ($form, event, errors) {
        },
        submitSuccess: function ($form, event) {
            event.preventDefault();
            var str = $('#confirmForm').serialize();
            var this_form = $('#confirmForm');
            $("#confirmButton").button('loading');
            $.ajax({
                type: "POST",
                url: linkto,
                data: str,
                success: function (dataResponse) {
                    var response = JSON.parse(dataResponse);
                    if (response.success == true) {
                        $("#confirmButton").button('reset');
						$("#confirmForm")[0].reset();
						showPassSubTypesContent();
						$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
						$('#confirm-messages').html('<div class="sent-message">'+ response.messages + '</div>');
			          	this_form.find('.sent-message').slideDown().html(response.messages);
	          			$(".sent-message").delay(500).show(10, function() {
							$(this).delay(3000).hide(10, function() {
								$(this).remove();
								$('#confirmModal').modal('hide');
							});
						});
                    } else {
                        $("#confirmButton").button('reset');
			          	$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
						$('#confirm-messages').html('<div class="error-message">'+ response.messages + '</div>');
			          	this_form.find('.error-message').slideDown().html(response.messages);
	          			$(".error-message").delay(500).show(10, function() {
							
						});
                    }
                }
            });
            return false;
        },
    });


});


function showPassSubTypesContent() {
	$.ajax({
		type: 'POST',
		url: linkto,
		data: {request: "fetchPassSubTypes" },
		success: function (data) {
			$('#list-pass-sub-types').html(data);
		}
	});
}