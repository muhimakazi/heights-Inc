$(document).ready(function () {
	showParticipantsList();

	$('#filterForm').submit(function () {
	    showParticipantsList();
	    return false;
	});

	// Approve
	$(document).on('click', '.approve_bt', function() {
		var transId   = $(this).data('id');
		var partId    = $('#partId'+transId).text();
		$('#confirmModal').modal('show');
		$('#apbtParticipantId').val(partId);
		$('#confirmId').val(transId);
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
	});


	// Refund payment
	$(document).on('click', '.refund_delegate', function() {
		var transId   = $(this).data('id');
		var partId    = $('#partId'+transId).text();
		$('#refundModal').modal('show');
		$('#refParticipantId').val(partId);
		$('#refundId').val(transId);
		$("#refundForm input").jqBootstrapValidation({
	        preventSubmit: true,
	        submitError: function ($form, event, errors) {
	        },
	        submitSuccess: function ($form, event) {
	            event.preventDefault();
	            var str = $('#refundForm').serialize();
	            var this_form = $('#refundForm');
	            $("#refundButton").button('loading');
	            $.ajax({
	                type: "POST",
	                url: linkto,
	                data: str,
	                success: function (dataResponse) {
	                    var response = JSON.parse(dataResponse);
	                    if (response.success == true) {
	                        $("#refundButton").button('reset');
							$("#refundForm")[0].reset();
							showParticipantsList();
							$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
							$('#refund-messages').html('<div class="sent-message">'+ response.messages + '</div>');
				          	this_form.find('.sent-message').slideDown().html(response.messages);
							$(".sent-message").delay(500).show(10, function() {
								$(this).delay(3000).hide(10, function() {
									$(this).remove();
									$('#refundModal').modal('hide');
								});
							});
	                    } else {
	                        $("#refundButton").button('reset');
				          	$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
							$('#refund-messages').html('<div class="error-message">'+ response.messages + '</div>');
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


	/** activate link */
	$('.deactivateButtonDynamic').on('click', function () {
		var Key = $(this).attr('data-key');
		var FormKey = "#deactivateModal" + Key;

		var f = $(this).find(FormKey + ' .form-group'),
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

		var this_form = $(FormKey + " #deactivateForm");

		if (ferror) return false;
		else var str = $(this_form).serialize();

		// var action = $(this_form).attr('action');

		// alert("Act :: " + linkto);

		$(FormKey + " #deactivateButton").button('loading');

		$.ajax({
			type: "POST",
			url: linkto,
			data: str,
			cache: false,
			success: function (dataResponse) {
				var response = JSON.parse(dataResponse);

				if (response.success == true) {
					$(FormKey + " #deactivateButton").button('reset');
					$(FormKey + " #deactivateForm")[0].reset();
					if ($('#page').val() == 'profile') {
						$('.display_status_').html('Denied  <i class="fa fa-times-circle"></i>');
						$('.display_status_').css('color', '#c13c5a');
						$('.disable_btn_approve').removeClass('disabled');
						$('.disable_btn_deny').addClass('disabled');
					}
					showParticipantsList();
					$(FormKey + ' #deactivate-messages').html('<div class="sent-message">' + response.messages + '</div>');
					this_form.find(FormKey + ' .sent-message').slideDown().html(response.messages);
					$(FormKey + " .sent-message").delay(400).show(20, function () {
						$(this).delay(7000).hide(50, function () { });
					});

					setTimeout(function () {
						$(FormKey + ' .close').delay(1200).click();
					}, 5000);

				} else {
					$(FormKey + " #deactivateButton").button('reset');
					$(FormKey + ' #deactivate-messages').html('<div class="error-message">' + response.messages + '</div>');
					this_form.find(FormKey + ' .error-message').slideDown().html(response.messages);
					$(FormKey + " .error-message").delay(500).show(10, function () { });
				}
			}
		});
		return false;
	});

	/** Payment Reminder */
	$('.reminderButtonDynamic').on('click', function () {
		var Key = $(this).attr('data-key');
		var FormKey = "#reminderModal" + Key;

		var f = $(this).find(FormKey + ' .form-group'),
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

		var this_form = $(FormKey + " #reminderForm")

		if (ferror) return false;
		else var str = $(this_form).serialize();

		// var action = $(this_form).attr('action');

		$(FormKey + " #reminderButton").button('loading');

		$.ajax({
			type: "POST",
			url: linkto,
			data: str,
			cache: false,
			success: function (dataResponse) {
				var response = JSON.parse(dataResponse);

				if (response.success == true) {
					$(FormKey + " #reminderButton").button('reset');
					$(FormKey + " #reminderForm")[0].reset();
					if ($('#page').val() == 'profile') {
						$('.display_status_').html('Approved <i class="fa fa-check-circle"></i>');
						$('.display_status_').css('color', '#5cb85c');
						$('.disable_btn_approve').addClass('disabled');
						$('.disable_btn_deny').removeClass('disabled');
					}
					showParticipantsList();
					$(FormKey + ' #reminder-messages').html('<div class="sent-message">' + response.messages + '</div>');
					this_form.find(FormKey + ' .sent-message').slideDown().html(response.messages);
					$(FormKey + " .sent-message").delay(400).show(20, function () {
						$(this).delay(7000).hide(50, function () { });
					});

					setTimeout(function () {
						$(FormKey + ' .close').delay(1200).click();
					}, 5000);

				} else {
					$(FormKey + " #reminderButton").button('reset');
					$(FormKey + ' #reminder-messages').html('<div class="error-message">' + response.messages + '</div>');
					this_form.find(FormKey + ' .error-message').slideDown().html(response.messages);
					$(FormKey + " .error-message").delay(500).show(10, function () { });
				}
			}
		});
		return false;
	});
});

function showParticipantsList() {
	var type = $("#type").val();
    var subtype = $("#subtype").val();
    var status = $("#status").val();
    var payment_channel = $("#payment_channel").val();
    var datefrom = $("#datefrom").val();
    var dateto = $("#dateto").val();
    var country = $("#country").val();

    $('#bookingTable').DataTable().clear().destroy();

    $('#bookingTable').DataTable({
    	'order': [[1, 'asc']],
        'dom': 'T<"clear">lfrtip',
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url':linkto,
            'data': {type:type,subtype:subtype,status:status,payment_channel:payment_channel,country:country,datefrom:datefrom,dateto:dateto,request:"fetchBookings"}
        },
        'columns': [
            { data: 'id' },
            { data: 'transaction_id' },
            { data: 'receipt_id' },
            { data: 'firstname' },
            { data: 'hotel_name' },
            { data: 'room_type' },
            { data: 'job_title' },
            { data: 'organisation_name' },
            { data: 'channel' },
            { data: 'amount' },
            { data: 'datetime' },
            { data: 'status' }
        ],
        'oLanguage': {sProcessing: "<div class='loadingGifDiv'><div class='v2-loader'></div></div>"}
    });
}

// CSV Export
$(document).on("click", ".exportBtn", function () {
    $.ajax({
      	type: "POST",
      	url: linkto,
      	data: {request: "exportPaymentData"},
      	success: function (data) {
	        // Download the exported data
	        if (data.trim() !== "") {
	          downloadFile(data, "payment_report.csv");
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


function filterOptionsRoom(hotel_input) {
	var hotel = $(hotel_input).val();
	$.ajax({
		type: 'POST',
		url: linkto,
		data: {hotel: hotel, request: "filterBookingRoom" },
		success: function (data) {
			$('#room').html(data);
		}
	});
}