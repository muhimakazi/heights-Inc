$(document).ready(function () {
	showParticipantsList();

	$('#filterForm').submit(function () {

		var type = $('#type').val();
		var subtype = $('#subtype').val();
		var status = $('#status').val();

		$.ajax({
			type: 'POST',
			url: linkto,
			data: {type: type, subtype: subtype, status: status, request: "fetchParticitants" },
			success: function (data) {
				$('#participants-table').html(data);
			}
		});
		return false;
	});

	/** Activate */
	$('.activateButtonDynamic').on('click', function () {
		var Key = $(this).attr('data-key');
		var FormKey = "#activateModal" + Key;

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

		var this_form = $(FormKey + " #activateForm")

		if (ferror) return false;
		else var str = $(this_form).serialize();

		// var action = $(this_form).attr('action');

		$(FormKey + " #activateButton").button('loading');

		$.ajax({
			type: "POST",
			url: linkto,
			data: str,
			cache: false,
			success: function (dataResponse) {
				var response = JSON.parse(dataResponse);

				if (response.success == true) {
					$(FormKey + " #activateButton").button('reset');
					$(FormKey + " #activateForm")[0].reset();
					if ($('#page').val() == 'profile') {
						$('.display_status_').html('Approved <i class="fa fa-check-circle"></i>');
						$('.display_status_').css('color', '#5cb85c');
						$('.disable_btn_approve').addClass('disabled');
						$('.disable_btn_deny').removeClass('disabled');
					}
					showParticipantsList();
					$(FormKey + ' #activate-messages').html('<div class="sent-message">' + response.messages + '</div>');
					this_form.find(FormKey + ' .sent-message').slideDown().html(response.messages);
					$(FormKey + " .sent-message").delay(400).show(20, function () {
						$(this).delay(7000).hide(50, function () { });
					});

					setTimeout(function () {
						$(FormKey + ' .close').delay(1200).click();
					}, 5000);

				} else {
					$(FormKey + " #activateButton").button('reset');
					$(FormKey + ' #activate-messages').html('<div class="error-message">' + response.messages + '</div>');
					this_form.find(FormKey + ' .error-message').slideDown().html(response.messages);
					$(FormKey + " .error-message").delay(500).show(10, function () { });
				}
			}
		});
		return false;
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

	//status
	$(document).on('click', '.delete_user', function () {
		var partId = $(this).data('id');
		var action = $(this).data('action');
		$.ajax({
			type: 'POST',
			url: linkto,
			data: { partId: partId, action: action, request: "deleteParticipant" },
			dataType: 'json',
			success: function (response) {
				if (response.success == true) {
					showParticipantsList();
				}
			}
		});
	});
});

function showParticipantsList() {
	$.ajax({
		type: 'POST',
		url: linkto,
		data: {type: "", subtype: "", request: "fetchGroupDelegatesForGroupAdmin" },
		success: function (data) {
			$('#participants-table').html(data);
		}
	});
}

function filterOptionsSubtype(type_input) {
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