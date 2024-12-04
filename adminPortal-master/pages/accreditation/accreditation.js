$(document).ready(function () {
	showParticipantsList();

	$(document).on('click', '.confirm_modal', function() {
		var partId   = $(this).data('id');
		var delegate_name = $(this).data('name');
		var request   = $(this).data('request');

		$('#confirmModal').modal('show');

		$('#confirmId').val(partId);
		$('#request').val(request);

		if (request == "confirmPrint") {
			$('#confirmTitle').html("Confirm printed badge");
			$('#confirmQuestion').html('Do you really want to confirm <strong>'+ delegate_name +'</strong> printed badge?');
		} else if (request == "unConfirmPrint") {
			$('#confirmTitle').html("Unconfirm printed badge");
			$('#confirmQuestion').html('Do you really want to unconfirm <strong>'+ delegate_name +'</strong> printed badge?');
		} else if (request == "issueBadge") {
			$('#confirmTitle').html("Issue badge");
			$('#confirmQuestion').html('Do you really want to issue <strong>'+ delegate_name +'</strong> badge?');
		} else if (request == "unIssueBadge") {
			$('#confirmTitle').html("Unissue badge");
			$('#confirmQuestion').html('Do you really want to unissue <strong>'+ delegate_name +'</strong> badge?');
		} else if (request == "checkIn") {
			$('#confirmTitle').html("Confirm attendance");
			$('#confirmQuestion').html('Do you really want to confirm attendance for <strong>'+ delegate_name +'</strong>?');
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
						showParticipantsList();
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

    // REGISTRATION
    $('#registerForm').submit(function () {
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
	        i.next(".validate").html(ierror ? i.attr("data-msg") != undefined ? i.attr("data-msg") : "wrong Input" : "").show("blind");
	      }
	    });
		
		if (ferror) return false;
		else var str = $(this).serialize();

        var str = $('#registerForm').serialize();
        var this_form = $('#registerForm');
        $("#registerButton").button('loading');

        var form = $("#registerForm")[0];
        var formData = new FormData(form);

        let obj = {};

        formData.forEach(function (value, key) {
          obj[key] = value;
        });

        let full_details = obj;

        full_details = JSON.stringify(full_details);
        formData.append("full_details", full_details);

        $.ajax({
            type: "POST",
            url: linkto,
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function (dataResponse) {
                var response = JSON.parse(dataResponse);
                if (response.success == true) {
                    $("#registerButton").button('reset');
					$("#registerForm")[0].reset();
					showParticipantsList();
					$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
					$('#register-messages').html('<div class="sent-message">'+ response.messages + '</div>');
		          	this_form.find('.sent-message').slideDown().html(response.messages);
          			$(".sent-message").delay(500).show(10, function() {
						$(this).delay(3000).hide(10, function() {
							$(this).remove();
							$('#registerModal').modal('hide');
						});
					});
                } else {
                    $("#registerButton").button('reset');
		          	$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
					$('#register-messages').html('<div class="error-message">'+ response.messages + '</div>');
		          	this_form.find('.error-message').slideDown().html(response.messages);
          			$(".error-message").delay(500).show(10, function() {
						
					});
                }
            }
        });
        return false;
    });

});

function showParticipantsList() {
	$('#participantsTable').DataTable().clear().destroy();
	
	var type = $("#type").val();
    var subtype = $("#subtype").val();
    var status = $("#status").val();
    var datefrom = $("#datefrom").val();
    var dateto = $("#dateto").val();
    var country = $("#country").val();

    $('#participantsTable').DataTable({
        // responsive: true,
        // order: [[1, 'desc']],
        'pageLength': 500,
        'dom': 'T<"clear">lfrtip',
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url':linkto,
            'data': {request: "fetchParticitants" }
        },
        'columns': [
            { data: 'id' },
            { data: 'reg_date' },
            { data: 'firstname' },
            { data: 'pass_type' },
            { data: 'job_title' },
            { data: 'organisation_name' },
            { data: 'residence_country' },
            // { data: 'print_status' },
            // { data: 'issue_status' },
            { data: 'attend_status' },
            { data: 'action' }
        ],
        'oLanguage': {sProcessing: "<div class='loadingGifDiv'><div class='v2-loader'></div></div>"}
    });
}

