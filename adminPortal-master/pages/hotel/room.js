$(document).ready(function () {
	showRoomList();

	/** Add  */
	$("#addForm input, #addForm select").jqBootstrapValidation({
        preventSubmit: true,
        submitError: function ($form, event, errors) {
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
						showRoomList();
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

	$(document).on('click', '.edit_room', function() {
		var roomID = $(this).attr("data-id");
		var hotelID = $('#eHotelID' + roomID).text();
		var name = $('#eName' + roomID).text();
		var room_type = $('#eType' + roomID).text();
		var room_occupancy = $('#eOcc' + roomID).text();
		var adults = $('#eAdul' + roomID).text();
		var children = $('#eChil' + roomID).text();
		var room_price = $('#ePrice' + roomID).text();
		var currency = $('#eCurr' + roomID).text();
		var bed_type = $('#eBed' + roomID).text();
		var description = $('#eDesc' + roomID).text();
		
		$('#editModal').modal('show');
		$('#ehotel_name').val(name);
		$('#eroom_type').val(room_type);
		$('#eroom_occupancy').val(room_occupancy);
		$('#eadults').val(adults);
		$('#echildren').val(children);
		$('#eroom_price').val(room_price);
		$('#ecurrency').val(currency);
		$('#ebed_type').val(bed_type);
		$('#eroom_description').val(description);
		$('#editId').val(roomID);
		$('#hotelId').val(hotelID);

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
						showRoomList();
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
		var roomId   = $(this).data('id');
		var room_type = $('#eType' + roomId).text();
		var request   = $(this).data('request');

		$('#confirmModal').modal('show');

		$('#confirmId').val(roomId);
		$('#request').val(request);

		if (request == "activateRoom") {
			$('#confirmTitle').html("Activate Room");
			$('#confirmQuestion').html('Do you really want to activate <strong>'+ room_type +'</strong>?');
		} else if (request == "deactivateRoom") {
			$('#confirmTitle').html("Deactivate Room");
			$('#confirmQuestion').html('Do you really want to deactivate <strong>'+ room_type +'</strong>?');
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
						showRoomList();
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

function showRoomList() {
    $('#roomTable').DataTable().clear().destroy();

    $('#roomTable').DataTable({
        'dom': 'T<"clear">lfrtip',
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url':linkto,
            'data': { request:"fetchRooms"}
        },
        'columns': [
            { data: 'id' },
            { data: 'room_photo' },
            { data: 'name' },
            { data: 'room_type' },
            { data: 'room_occupancy' },
            { data: 'adults' },
            { data: 'children' },
            { data: 'room_price' },
            { data: 'bed_type' },
            { data: 'status' },
            { data: 'action' }
        ],
        'oLanguage': {sProcessing: "<div class='loadingGifDiv'><div class='v2-loader'></div></div>"}
    });
}