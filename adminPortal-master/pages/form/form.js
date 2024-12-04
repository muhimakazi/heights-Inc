$(document).ready(function () {
	showFormList();

    // $('#addModal').modal('show');

	/** Add  */
	$("#addForm").find('input,select,textarea').not('[type=submit]').jqBootstrapValidation({
        preventSubmit: true,
        submitError: function ($form, event, errors) {
            $("html, body, div, div#addForm").animate({ scrollTop: "0" }, "slow");
            /* ######### Loader ########## */
            window.setTimeout(function () {
              $("#load").attr("hidden", "");
            }, 1000);
        },
        submitSuccess: function ($form, event) {
            event.preventDefault();
            
            $("#addButton").button('loading');

            var this_form = $('#addForm');
            var form = $("#addForm")[0];
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
                        $("#addButton").button('reset');
						$("#addForm")[0].reset();
						showFormList();
						$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
						$('#add-messages').html('<div class="sent-message">'+ response.messages + '</div>');
			          	this_form.find('.sent-message').slideDown().html(response.messages);
						$(".sent-message").delay(500).show(10, function() {
							$(this).delay(3000).hide(10, function() {
								$(this).remove();
								$('#addModal').modal('hide');
							});
						});
                    } else {
                        $("#addButton").button('reset');
			          	$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
						$('#add-messages').html('<div class="error-message">'+ response.messages + '</div>');
			          	this_form.find('.error-message').slideDown().html(response.messages);
	          			$(".error-message").delay(500).show(10, function() {
							
						});
                    }
                }
            });
            return false;
        },
    });

	/** Edit  */

	$(document).on('click', '.edit_form', function() {
		var formID = $(this).attr("data-id");
		var name = $('#eName' + formID).text();
		var epublish_type = $('#ePubType' + formID).text();
		var eform_order = $('#eOrder' + formID).text();
        var eform_note = $('#eNote' + formID).text();
        var registration_email_subject = $('#eRegEmailSubj' + formID).text();
        var registration_email_message = $('#eRegEmailMess' + formID).text();
        var approval_email_subject = $('#eApprEmailSubj' + formID).text();
		var approval_email_message = $('#eApprEmailMess' + formID).text();
		
		$('#editModal').modal('show');
		$('#eform_name').val(name);
		$('#epublish_type').val(epublish_type);
		$('#eform_order').val(eform_order);
        $('#eform_note').val(eform_note);
        $('#eregistration_email_subject').val(registration_email_subject);
        $('#eapproval_email_subject').val(approval_email_subject);
        $("div#eregistration_email_message div.note-editable").html(registration_email_message);
        $("div#eapproval_email_message div.note-editable").html(approval_email_message);
		$('#editId').val(formID);

		$('#editModal').modal('show'); 
	});

    $('#editForm').find('input,select,textarea').not('[type=submit]').jqBootstrapValidation({
        preventSubmit: true,
        submitError: function ($form, event, errors) {
            $("html, body, div, div#editForm").animate({ scrollTop: "0" }, "slow");
            /* ######### Loader ########## */
            window.setTimeout(function () {
              $("#load").attr("hidden", "");
            }, 1000);
        },
        submitSuccess: function ($form, event) {
            event.preventDefault();

            $("#editButton").button('loading');

            var this_form = $('#editForm');
            var form = $("#editForm")[0];
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
                        $("#editButton").button('reset');
                     $("#editForm")[0].reset();
                     showFormList();
                     $("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
                     $('#edit-messages').html('<div class="sent-message">'+ response.messages + '</div>');
                         this_form.find('.sent-message').slideDown().html(response.messages);
                     $(".sent-message").delay(500).show(10, function() {
                         $(this).delay(3000).hide(10, function() {
                             $(this).remove();
                             $('#editModal').modal('hide');
                         });
                     });
                    } else {
                        $("#editButton").button('reset');
                         $("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
                     $('#edit-messages').html('<div class="error-message">'+ response.messages + '</div>');
                         this_form.find('.error-message').slideDown().html(response.messages);
                         $(".error-message").delay(500).show(10, function() {
                            
                     });
                    }
                }
            });
            return false;
        },
    });

    //CHANGE FORM STATUS
	$(document).on('click', '.confirm_modal', function() {
		var formId   = $(this).data('id');
		var name = $('#eName' + formId).text();
		var request   = $(this).data('request');

		$('#confirmModal').modal('show');

		$('#confirmId').val(formId);
		$('#request').val(request);

		if (request == "publishForm") {
			$('#confirmTitle').html("Publish Form");
			$('#confirmQuestion').html('Do you really want to publish <strong>'+ name +'</strong> form?');
		} else if (request == "unpublishForm") {
			$('#confirmTitle').html("Unpublish Form");
			$('#confirmQuestion').html('Do you really want to unpublish <strong>'+ name +'</strong> form?');
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
						showFormList();
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

function showFormList() {
    $('#formTable').DataTable().clear().destroy();

    $('#formTable').DataTable({
        'dom': 'T<"clear">lfrtip',
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url':linkto,
            'data': { request:"fetchForms"}
        },
        'columns': [
            { data: 'id' },
            { data: 'form_name' },
            { data: 'publish_type' },
            { data: 'order' },
            { data: 'status' },
            { data: 'action' }
        ],
        'columnDefs': [
            {
                "targets": 5,
                "className": "text-center",
                "width": "25%"
           }
        ],
        'oLanguage': {sProcessing: "<div class='loadingGifDiv'><div class='v2-loader'></div></div>"}
    });
}