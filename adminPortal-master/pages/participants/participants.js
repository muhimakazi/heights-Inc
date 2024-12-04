$(document).ready(function () {
	showParticipantsList();

	$("#filterForm").submit(function () {
	    showParticipantsList();
	    return false;
  	});

  	$('#filterForm').on('keyup change paste', 'input, select, textarea', function(){
	    showParticipantsList();
	    return false;
	});


	// APPROVE OR REJECT REGISTRATION
	$(document).on('click', '.update_delegate_status', function() {
		var partId   = $(this).data('id');
		var action   = $(this).data('action');
		var partName = $(this).data('name');

		$('#approveModal').modal('show');
		$('#approveId').val(partId);
		$('#approve-modal-title').html(action+' Participant Registration');
		$('#approve-modal-confirm').html('Do you really want to ' +action+' the registration of <strong>'+partName+'?</strong>');

		if (action == "Approve") {
			$('#approveRequest').val('approveParticipantRegistration');
		} else if (action == "Reject") {
			$('#approveRequest').val('denyParticipantRegistration');
		} else if (action == "Delete") {
			$('#approveRequest').val('deleteParticipantRegistration');
		} else if (action == "AcceptRegistrationInvite") {
			$('#approveRequest').val('acceptRegistrationInvite');
		}
	});

	$("#approveForm input").jqBootstrapValidation({
        preventSubmit: true,
        submitError: function ($form, event, errors) {
        },
        submitSuccess: function ($form, event) {
            event.preventDefault();
            var str = $('#approveForm').serialize();
            var this_form = $('#approveForm');
            $("#approveButton").button('loading');
            $.ajax({
                type: "POST",
                url: linkto,
                data: str,
                success: function (dataResponse) {
                    var response = JSON.parse(dataResponse);
                    if (response.success == true) {
                        $("#approveButton").button('reset');
						$("#approveForm")[0].reset();
						showParticipantsList();
						$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
						$('#approve-messages').html('<div class="sent-message">'+ response.messages + '</div>');
			          	this_form.find('.sent-message').slideDown().html(response.messages);
						$(".sent-message").delay(500).show(10, function() {
							$(this).delay(3000).hide(10, function() {
								$(this).remove();
								$('#approveModal').modal('hide');
							});
						});
                    } else {
                        $("#approveButton").button('reset');
			          	$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
						$('#approve-messages').html('<div class="error-message">'+ response.messages + '</div>');
			          	this_form.find('.error-message').slideDown().html(response.messages);
	          			$(".error-message").delay(500).show(10, function() {
							
						});
                    }
                }
            });
            return false;
        },
    });


    // UPDATE PARTICIPANT PROFILE
	$(document).on('click', '.edit_profile', function() {
		var partId   = $(this).data('id');
		var partName = $(this).data('name');
		var title = $('#title' + partId).text();
		var firstname = $('#firstname' + partId).text();
		var lastname = $('#lastname' + partId).text();
		var email = $('#email' + partId).text();
		var telephone = $('#telephone' + partId).text();
		var organisation_name = $('#organisation_name' + partId).text();
		var job_title = $('#job_title' + partId).text();
		var residence_country = $('#residence_country' + partId).text();
		var id_type = $('#id_type' + partId).text();
		var id_number = $('#id_number' + partId).text();
		$('#editProfileModal').modal('show');
		$('#userToken').val(partId);
		$('#title').val(title);
		$('#firstname').val(firstname);
		$('#lastname').val(lastname);
		$('#email').val(email);
		$('#telephone').val(telephone);
		$('#organisation_name').val(organisation_name);
		$('#job_title').val(job_title);
		$('#residence_country').val(residence_country);
		$('#id_type').val(id_type);
		$('#id_number').val(id_number);
		$('#edit-profile-confirm').html('Participant: <strong>'+partName+'</strong>');
	});

	$("#updateProfileForm input, #updateProfileForm select").jqBootstrapValidation({
        preventSubmit: true,
        submitError: function ($form, event, errors) {
        },
        submitSuccess: function ($form, event) {
            event.preventDefault();
            var str = $('#updateProfileForm').serialize();
            var this_form = $('#updateProfileForm');
            $("#updateProfileButton").button('loading');
            var form = $("#updateProfileForm")[0];
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
                        $("#updateProfileButton").button('reset');
						$("#updateProfileForm")[0].reset();
						showParticipantsList();
						$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
						$('#update-profile-message').html('<div class="sent-message">'+ response.messages + '</div>');
			          	this_form.find('.sent-message').slideDown().html(response.messages);
						$(".sent-message").delay(500).show(10, function() {
							$(this).delay(3000).hide(10, function() {
								$(this).remove();
								$('#editProfileModal').modal('hide');
							});
						});
                    } else {
                        $("#updateProfileButton").button('reset');
			          	$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
						$('#update-profile-message').html('<div class="error-message">'+ response.messages + '</div>');
			          	this_form.find('.error-message').slideDown().html(response.messages);
	          			$(".error-message").delay(500).show(10, function() {
							
						});
                    }
                }
            });
            return false;
        },
    });

	// UPDATE PARTICIPANT CATEGORY
	$(document).on('click', '.update_category', function() {
		var partId   = $(this).data('id');
		var partName = $(this).data('name');

		$('#categoryModal').modal('show');
		$('#updateCategoryPartId').val(partId);
		$('#category-modal-confirm').html('Participant: <strong>'+partName+'</strong>');
	});

	$('#categoryForm').submit(function () {
		var f = $(this).find('.control-group'),
			ferror = false,
			emailExp = /^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i;

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

        var str = $('#categoryForm').serialize();
        var this_form = $('#categoryForm');
        $("#categoryButton").button('loading');
        $.ajax({
            type: "POST",
            url: linkto,
            data: str,
            success: function (dataResponse) {
                var response = JSON.parse(dataResponse);
                if (response.success == true) {
                    $("#categoryButton").button('reset');
					$("#categoryForm")[0].reset();
					showParticipantsList();
					$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
					$('#category-messages').html('<div class="sent-message">'+ response.messages + '</div>');
		          	this_form.find('.sent-message').slideDown().html(response.messages);
					$(".sent-message").delay(500).show(10, function() {
						$(this).delay(3000).hide(10, function() {
							$(this).remove();
							$('#categoryModal').modal('hide');
						});
					});
                } else {
                    $("#categoryButton").button('reset');
		          	$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
					$('#category-messages').html('<div class="error-message">'+ response.messages + '</div>');
		          	this_form.find('.error-message').slideDown().html(response.messages);
          			$(".error-message").delay(500).show(10, function() {
						
					});
                }
            }
        });
        return false;
    });

	// FOR CMPD
	$(document).on('click', '.update_cmpd', function() {
		var partId   = $(this).data('id');

		$('#cmpdModal').modal('show');

		$('#updatecmpdPartId').val(partId);

		$('#cmpdForm').submit(function () {
			var f = $(this).find('.control-group'),
				ferror = false,
				emailExp = /^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i;

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

            var str = $('#cmpdForm').serialize();
            var this_form = $('#cmpdForm');
            $("#cmpdButton").button('loading');
            $.ajax({
                type: "POST",
                url: linkto,
                data: str,
                success: function (dataResponse) {
                    var response = JSON.parse(dataResponse);
                    if (response.success == true) {
                        $("#cmpdButton").button('reset');
						$("#cmpdForm")[0].reset();
						showParticipantsList();
						$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
						$('#cmpd-messages').html('<div class="sent-message">'+ response.messages + '</div>');
			          	this_form.find('.sent-message').slideDown().html(response.messages);
						$(".sent-message").delay(500).show(10, function() {
							$(this).delay(3000).hide(10, function() {
								$(this).remove();
								$('#cmpdModal').modal('hide');
							});
						});
                    } else {
                        $("#cmpdButton").button('reset');
			          	$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
						$('#cmpd-messages').html('<div class="error-message">'+ response.messages + '</div>');
			          	this_form.find('.error-message').slideDown().html(response.messages);
	          			$(".error-message").delay(500).show(10, function() {
							
						});
                    }
                }
            });
            return false;
	    });
	});

	//status
	$(document).on('click', '.delete_user', function() {
		var partId  = $(this).data('id');
		var action = $(this).data('action');
		$.ajax({
			type: 'POST',
			url: linkto,
			data: {partId: partId, action: action, request: "deleteParticipant"},
			dataType: 'json',
			success:function(response) {
				if(response.success == true) {
					showParticipantsList();
				}
			}
		});
	});
});

function showParticipantsList() {
	var type = $("#type").val();
    var subtype = $("#subtype").val();
    var formtype = $("#formtype").val();
    var status = $("#status").val();
    var datefrom = $("#datefrom").val();
    var dateto = $("#dateto").val();
    var country = $("#country").val();

    $('#participantsTable').DataTable().clear().destroy();

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
            'data': {type:type,subtype:subtype,formtype:formtype,status:status,country:country,datefrom:datefrom,dateto:dateto,request:"fetchParticitants"}
        },
        'columns': [
            { data: 'id' },
            { data: 'reg_date' },
            { data: 'firstname' },
            // { data: 'emergency_contact_firstname' },
            { data: 'participation_sub_type_id' },
            { data: 'job_title' },
            { data: 'organisation_name' },
            { data: 'organisation_type' },
            { data: 'residence_country' },
            { data: 'status' },
            { data: 'action' }
        ],
        'oLanguage': {sProcessing: "<div class='loadingGifDiv'><div class='v2-loader'></div></div>"}
    });
}

// CSV Export
$(document).on("click", ".exportBtn", function () {
    $.ajax({
      	type: "POST",
      	url: linkto,
      	data: {request: "exportRegistrationData"},
      	success: function (data) {
	        // Download the exported data
	        if (data.trim() !== "") {
	          downloadFile(data, "registration_report.csv");
	        } else {

	        }
      	},
    });
});

function downloadFile(data, filename) {
  	var blob = new Blob([data], { type: "text/csv;charset=utf-8;" });
  	if (navigator.msSaveBlob) {
    	// For IE and Edge
    	navigator.msSaveBlob(blob, filename);
  	} else {
    	var link = document.createElement("a");
	    if (link.download !== undefined) {
	      	// For other browsers
	      	var url = URL.createObjectURL(blob);
	      	link.setAttribute("href", url);
	      	link.setAttribute("download", filename);
	      	link.style.visibility = "hidden";
	      	document.body.appendChild(link);
	      	link.click();
	      	document.body.removeChild(link);
	    }
  	}
}

function filterOptionsSubtype(type_input) {
	showParticipantsList();
	var type = $(type_input).val();
	$.ajax({
		type: 'POST',
		url: linkto,
		data: {type: type, request: "filterParticipationSubType" },
		success: function (data) {
			$('#subtype').html(data);
		}
	});
}

function getOptionsSubtype(type_input) {
	var type = $(type_input).val();
	// if (type == '3135393434363031363335') {
	// 	$("#update_subtype").parent().parent().show();
    //   	$("#update_subtype").parent().addClass("field-validate");

		$.ajax({
			type: 'POST',
			url: linkto,
			data: {type: type, request: "updateParticipationSubType" },
			success: function (data) {
				$('#update_subtype').html(data);
			}
		});
	// } else {
    //   	$("#update_subtype").parent().removeClass("control-group");
    //   	$("#update_subtype").parent().parent().hide();
    // }
}