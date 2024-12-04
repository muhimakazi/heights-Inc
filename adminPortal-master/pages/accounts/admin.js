$(document).ready(function () {
	showAdminTable();

	// add new user
	$('#account_type').on('change', function() {
	  if (this.value != 31323230313739353734 && this.value != 32343134303531363333) {
	  	$("#client_id").parent().show();
        $("#client_id").parent().addClass('form-group');
	  } else {
	  	$("#client_id").parent().removeClass('form-group');
	  	$("#client_id").parent().hide();
	  }
	});

	$('#eaccount_type').on('change', function() {
	  if (this.value != 31323230313739353734 && this.value != 32343134303531363333) {
	  	$("#eclient_id").parent().show();
        $("#eclient_id").parent().addClass('form-group');
	  } else {
	  	$("#eclient_id").parent().removeClass('form-group');
	  	$("#eclient_id").parent().hide();
	  }
	});

	$('#addUserForm').submit(function () {
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
					case 'email':
						if (!emailExp.test(i.val())) {
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
	        i.next(".validate")
	          .html(
	            ierror
	              ? i.attr("data-msg") != undefined
	                ? i.attr("data-msg")
	                : "wrong Input"
	              : ""
	          )
	          .show("blind");
	      }
	    });
		
		if (ferror) return false;
		else var str = $(this).serialize();
		
		// $("#addUserButton").button('loading');
		$.ajax({
			type: 'POST',
			url: linkto,
			data: str,
			dataType: 'json',
			success: function (response) {

				if (response.success == true) {
					$("#addUserButton").button('reset');
					$("#addUserForm")[0].reset();
					showAdminTable();
					$("html, body, div.modal, div.modal-content, div.modal-body").animate({ scrollTop: '0' }, 100);
					$('#add-user-messages').html('<div class="alert alert-success">' +
						'<button type="button" class="close" data-dismiss="alert">&times;</button>' +
						'<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> ' + response.messages +
						'</div>');
					$(".alert-success").delay(500).show(10, function () {
						$(this).delay(3000).hide(10, function () {
							$(this).remove();
							$('#addUserModal').modal('hide');
						});
					});
				} else {
					$("#addUserButton").button('reset');
					$("html, body, div.modal, div.modal-content, div.modal-body").animate({ scrollTop: '0' }, 100);
					$('#add-user-messages').html('<div class="alert alert-danger">' +
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

	//EDIT USER
	$(document).on('click', '.edit_user', function () {
		var userId = $(this).data('id');
		var user = $('#eUser' + userId).text();
		var first = $('#eFirst' + userId).text();
		var last = $('#eLast' + userId).text();
		var ecat = $('#eCat' + userId).text();
		var eclient = $('#eClient' + userId).text();
		var eclientID = $('#eClientID' + userId).text();
		
		$('#editUserModal').modal('show');
		$('#eusername').val(user);
		$('#efirstname').val(first);
		$('#elastname').val(last);
		$('#eaccount_type').val(ecat);
		$('#userId').val(userId);
		$('#editUserButton').val(userId);

		if (eclientID != 0) {
			$('#eclient_id').val(eclient);
			$("#eclient_id").parent().show();
        	$("#eclient_id").parent().addClass('form-group');
		}
	});


	$('#editUserForm').submit(function () {
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
					case 'email':
						if (!emailExp.test(i.val())) {
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
	        i.next(".validate")
	          .html(
	            ierror
	              ? i.attr("data-msg") != undefined
	                ? i.attr("data-msg")
	                : "wrong Input"
	              : ""
	          )
	          .show("blind");
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
					$("#editUserButton").button('reset');
					$("#editUserForm")[0].reset();
					showAdminTable();
					$("html, body, div.modal, div.modal-content, div.modal-body").animate({ scrollTop: '0' }, 100);
					$('#edit-user-messages').html('<div class="alert alert-success">' +
						'<button type="button" class="close" data-dismiss="alert">&times;</button>' +
						'<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> ' + response.messages +
						'</div>');
					$(".alert-success").delay(500).show(10, function () {
						$(this).delay(3000).hide(10, function () {
							$(this).remove();
							$('#editUserModal').modal('hide');
						});
					});
				} else {
					$("#editUserButton").button('reset');
					$("html, body, div.modal, div.modal-content, div.modal-body").animate({ scrollTop: '0' }, 100);
					$('#edit-user-messages').html('<div class="alert alert-danger">' +
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

	//CHANGE ACOUNT STATUS
	$(document).on('click', '.confirm_modal', function() {
		var userId   = $(this).data('id');
		var firstname = $('#eFirst' + userId).text();
		var request   = $(this).data('request');

		$('#confirmModal').modal('show');

		$('#confirmId').val(userId);
		$('#request').val(request);

		if (request == "activateAdminAccount") {
			$('#confirmTitle').html("Activate user account");
			$('#confirmQuestion').html('Do you really want to activate <strong>'+ firstname +'</strong> account?');
		} else if (request == "blockAdminAccount") {
			$('#confirmTitle').html("Block user account");
			$('#confirmQuestion').html('Do you really want to block <strong>'+ firstname +'</strong> account?');
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
						showAdminTable();
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

function showAdminTable() {
    $('#userTable').DataTable().clear().destroy();

    $('#userTable').DataTable({
        'dom': 'T<"clear">lfrtip',
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url':linkto,
            'data': { request:"fetchAccounts"}
        },
        'columns': [
            { data: 'id' },
            { data: 'joined' },
            { data: 'firstname' },
            { data: 'lastname' },
            { data: 'username' },
            { data: 'group' },
            { data: 'organisation' },
            { data: 'status' },
            { data: 'action' }
        ],
        'oLanguage': {sProcessing: "<div class='loadingGifDiv'><div class='v2-loader'></div></div>"}
    });
}