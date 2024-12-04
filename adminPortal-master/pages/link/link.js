$(document).ready(function () {
	showGeneratedLinksContent();

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
					showGeneratedLinksContent();
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


	//EDIT PASS TYPE
	$(document).on('click', '.edit_link', function () {
		var linkId = $(this).data('id');
		var firstname = $('#eFirst' + linkId).text();
		var lastname = $('#eLast' + linkId).text();
		var email = $('#eEmail' + linkId).text();
		var pass = $('#ePass' + linkId).text();
		
		$('#editLinkModal').modal('show');
		$('#efirstname').val(firstname);
		$('#elastname').val(lastname);
		$('#eemail').val(email);
		$('#epaticipant_type').val(pass);
		$('#linkId').val(linkId);
	});


	$('#editLinkForm').submit(function () {
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
					$("#editLinkButton").button('reset');
					$("#editLinkForm")[0].reset();
					showGeneratedLinksContent();
					$("html, body, div.modal, div.modal-content, div.modal-body").animate({ scrollTop: '0' }, 100);
					$('#edit-link-messages').html('<div class="alert alert-success">' +
						'<button type="button" class="close" data-dismiss="alert">&times;</button>' +
						'<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> ' + response.messages +
						'</div>');
					$(".alert-success").delay(500).show(10, function () {
						$(this).delay(3000).hide(10, function () {
							$(this).remove();
							$('#editLinkModal').modal('hide');
						});
					});
				} else {
					$("#editLinkButton").button('reset');
					$("html, body, div.modal, div.modal-content, div.modal-body").animate({ scrollTop: '0' }, 100);
					$('#edit-link-messages').html('<div class="alert alert-danger">' +
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


	//CHANGE LINK STATUS
	$(document).on('click', '.confirm_modal', function() {
		var linkId   = $(this).data('id');
		var name = $('#eFirst' + linkId).text();
		var request   = $(this).data('request');

		$('#confirmModal').modal('show');

		$('#confirmId').val(linkId);
		$('#request').val(request);

		if (request == "activatePrivate") {
			$('#confirmTitle').html("Activate private link");
			$('#confirmQuestion').html('Do you really want to activate this link for <strong>'+ name +'</strong>?');
		} else if (request == "deactivatePrivateLink") {
			$('#confirmTitle').html("Deactivate private link");
			$('#confirmQuestion').html('Do you really want to deactivate this link for <strong>'+ name +'</strong>?');
		} else if (request == "resendPrivateLink") {
			$('#confirmTitle').html("Resend private link");
			$('#confirmQuestion').html('Do you really want to resend this private link for <strong>'+ name +'</strong>?');
		} else if (request == "deletePrivateLink") {
			$('#confirmTitle').html("Delete private link");
			$('#confirmQuestion').html('Do you really want to delete this private link for <strong>'+ name +'</strong>?');
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
						showPassTypesContent();
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



function showGeneratedLinksContent() {
	$.ajax({
		type: 'POST',
		url: linkto,
		data: {request: "fetchGeneratedLinks" },
		success: function (data) {
			$('#list-generated-links').html(data);
		}
	});
}