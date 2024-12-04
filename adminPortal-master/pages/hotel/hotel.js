$(document).ready(function () {
	showHotelList();

	/** Add  */
	$("#addForm input, #addForm select").jqBootstrapValidation({
        preventSubmit: true,
        submitError: function ($form, event, errors) {
        },
        submitSuccess: function ($form, event) {
            event.preventDefault();

            var full_number = phone_number.getNumber(intlTelInputUtils.numberFormat.E164);
            $("input[name='telephone_full'").val(full_number);

            
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
						showHotelList();
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

	$(document).on('click', '.edit_hotel', function() {
		var hotelID = $(this).attr("data-id");
		var name = $('#eName' + hotelID).text();
		var email = $('#eEmail' + hotelID).text();
		var telephone = $('#eTel' + hotelID).text();
		var country = $('#eCountry' + hotelID).text();
		var city = $('#eCity' + hotelID).text();
		var rate = $('#eRate' + hotelID).text();
		var address = $('#eAddress' + hotelID).text();
		
		$('#editModal').modal('show');
		$('#ename').val(name);
		$('#eemail').val(email);
		$('#etelephone').val(telephone);
		$('#ecountry').val(country);
		$('#ecity').val(city);
		$('#erate').val(rate);
		$('#eaddress').val(address);
		$('#editId').val(hotelID);

		$('#editModal').modal('show'); 
	});

	$("#editForm input").jqBootstrapValidation({
        preventSubmit: true,
        submitError: function ($form, event, errors) {
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
						showHotelList();
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


    //CHANGE HOTEL STATUS
	$(document).on('click', '.confirm_modal', function() {
		var hotelId   = $(this).data('id');
		var name = $('#eName' + hotelId).text();
		var request   = $(this).data('request');

		$('#confirmModal').modal('show');

		$('#confirmId').val(hotelId);
		$('#request').val(request);

		if (request == "activateHotel") {
			$('#confirmTitle').html("Activate Hotel");
			$('#confirmQuestion').html('Do you really want to activate <strong>'+ name +'</strong>?');
		} else if (request == "deactivateHotel") {
			$('#confirmTitle').html("Deactivate Hotel");
			$('#confirmQuestion').html('Do you really want to deactivate <strong>'+ name +'</strong>?');
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
						showHotelList();
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

function showHotelList() {
    $('#hotelTable').DataTable().clear().destroy();

    $('#hotelTable').DataTable({
        'dom': 'T<"clear">lfrtip',
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url':linkto,
            'data': { request:"fetchHotels"}
        },
        'columns': [
            { data: 'id' },
            { data: 'photo' },
            { data: 'name' },
            { data: 'email' },
            { data: 'telephone' },
            { data: 'country' },
            { data: 'city' },
            { data: 'rate' },
            { data: 'address' },
            { data: 'status' },
            { data: 'action' }
        ],
        'oLanguage': {sProcessing: "<div class='loadingGifDiv'><div class='v2-loader'></div></div>"}
    });
}