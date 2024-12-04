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


	/** Edit link */
	$('.editButtonDynamic').on('click', function () {
		var Key = $(this).attr('data-key');
		var FormKey = "#editLinkModal" + Key;

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

		var this_form = $(FormKey + " #editForm")

		if (ferror) return false;
		else var str = $(this_form).serialize();

		var action = $(this_form).attr('action');

		$(FormKey + " #editLinkButton").button('loading');

		$.ajax({
			type: "POST",
			url: action,
			data: str,
			cache: false,
			success: function (dataResponse) {

				var response = JSON.parse(dataResponse);
				if (response.success == true) {
					$(FormKey + " #editLinkButton").button('reset');
					$(FormKey + " #editLinkForm")[0].reset();
					showGeneratedLinksContent();
					$(FormKey + ' #edit-link-messages').html('<div class="sent-message">' + response.messages + '</div>');
					this_form.find('.sent-message').slideDown().html(response.messages);
					$(".sent-message").delay(500).show(20, function () {
						$(this).delay(12000).hide(10, function () { });
						$(FormKey + ' #generateLinkModal .close').click();
					});
				} else {
					$(FormKey + " #editLinkButton").button('reset');
					$(FormKey + ' #edit-link-messages').html('<div class="error-message">' + response.messages + '</div>');
					this_form.find('.error-message').slideDown().html(response.messages);
					$(".error-message").delay(500).show(10, function () { });
				}
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

		var action = $(this_form).attr('action');

		$(FormKey + " #activateButton").button('loading');

		$.ajax({
			type: "POST",
			url: action,
			data: str,
			cache: false,
			success: function (dataResponse) {
				var response = JSON.parse(dataResponse);

				if (response.success == true) {
					$(FormKey + " #activateButton").button('reset');
					$(FormKey + " #activateForm")[0].reset();
					showParticipationSubTypesContent();
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

		var this_form = $(FormKey + " #deactivateForm")

		if (ferror) return false;
		else var str = $(this_form).serialize();

		var action = $(this_form).attr('action');

		$(FormKey + " #deactivateButton").button('loading');

		$.ajax({
			type: "POST",
			url: action,
			data: str,
			cache: false,
			success: function (dataResponse) {
				var response = JSON.parse(dataResponse);

				if (response.success == true) {
					$(FormKey + " #deactivateButton").button('reset');
					$(FormKey + " #deactivateForm")[0].reset();
					showParticipationSubTypesContent();
					$(FormKey + ' #deactivate-messages').html('<div class="sent-message">' + response.messages + '</div>');
					this_form.find(FormKey + ' .sent-message').slideDown().html(response.messages);
					$(FormKey + " .sent-message").delay(30).show(10, function () {
						$(this).delay(7000).hide(40, function () { });
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



});



function showGeneratedLinksContent() {
	$.ajax({
		type: 'POST',
		url: linkto,
		data: { eventId: eventId, request: "fetchGeneratedLinks" },
		success: function (data) {
			$('#list-generated-links').html(data);
		}
	});
}