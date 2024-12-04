$(document).ready(function () {
	showExhibitorsTable();

	$("#image").fileinput({
		overwriteInitial: true,
		maxFileSize: 2500,
		showClose: false,
		showCaption: false,
		browseLabel: '',
		removeLabel: '',
		browseIcon: '<i class="glyphicon glyphicon-folder-open"></i> Upload from computer',
		removeIcon: '<i class="glyphicon glyphicon-remove"></i> Delete image',
		removeTitle: 'Cancel or reset changes',
		elErrorContainer: '#kv-avatar-errors-1',
		msgErrorClass: 'alert alert-block alert-danger',
		defaultPreviewContent: '<img src="../../../../img/photo_default.png" alt="Event banner" style="width:100%;">',
		layoutTemplates: { main2: '{preview} {remove} {browse}' },
		allowedFileExtensions: ["jpg", "png", "gif", "JPG", "PNG", "GIF"]
	});

	//Add exhibitor
	$('#addExhibitorForm').submit(function () {
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
					case 'email':
						if (!emailExp.test(i.val())) {
							ferror = ierror = true;
						}
						break;
				}
				i.next('.validate').html((ierror ? (i.attr('data-msg') !== undefined ? i.attr('data-msg') : 'wrong Input') : '')).show('blind');
			}
		});
		f.children('select').each(function () { // run all inputs
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
		var formData = new FormData(this);

		$("#addExhibitorButton").button('loading');

		$.ajax({
			type: "POST",
			url: action,
			data: formData,
			dataType: 'json',
			cache: false,
			contentType: false,
			processData: false,
			success: function (response) {
				if (response.success == true) {
					$("#addExhibitorButton").button('reset');
					$("#addExhibitorForm")[0].reset();
					showExhibitorsTable();
					$('#add-exhibitor-messages').html('<div class="sent-message">' + response.messages + '</div>');
					this_form.find('.sent-message').slideDown().html(response.messages);
					$(".sent-message").delay(500).show(10, function () {
						$(this).delay(3000).hide(10, function () {
							$(this).remove();
							$('#addPartnerModal').modal('hide');
						});
					});
				} else {
					$("#addExhibitorButton").button('reset');
					$('#add-exhibitor-messages').html('<div class="error-message">' + response.messages + '</div>');
					this_form.find('.error-message').slideDown().html(response.messages);
					$(".error-message").delay(500).show(10, function () {

					});
				}
			}
		});
		return false;
	});

	// Edit exhibitor
	$(document).on('click', '.edit_exhibitor', function () {
		var exhibitorId = $(this).data('id');
		var name = $('#eName' + exhibitorId).text();
		var countr = $('#eCountr' + exhibitorId).text();
		var cit = $('#eCit' + exhibitorId).text();
		var indus = $('#eIndus' + exhibitorId).text();
		var order = $('#eOrder' + exhibitorId).text();
		var email = $('#eEmail' + exhibitorId).text();
		var website = $('#eWeb' + exhibitorId).text();
		$('#editExhibitorModal').modal('show');
		$('#ename').val(name);
		$('#ecity').val(cit);
		$('#ecountry').val(countr);
		$('#eindustry').val(indus);
		$('#eorder').val(order);
		$('#eemail').val(email);
		$('#ewebsite').val(website);
		$('#editExhibitorButton').val(exhibitorId);
		$("#editExhibitorImage").fileinput({ allowedFileExtensions: ["jpg", "png", "gif", "JPG", "PNG", "GIF"] });

		$(".editExhibitorFooter").append('<input type="hidden" name="exhibitorId" id="exhibitorId" value="' + exhibitorId + '" />');
		$(".editExhibitorImageFooter").append('<input type="hidden" name="exhibitorId" id="exhibitorId" value="' + exhibitorId + '" />');

		//Edit event details
		$('#editExhibitorForm').submit(function () {
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
						case 'email':
							if (!emailExp.test(i.val())) {
								ferror = ierror = true;
							}
							break;
					}
					i.next('.validate').html((ierror ? (i.attr('data-msg') !== undefined ? i.attr('data-msg') : 'wrong Input') : '')).show('blind');
				}
			});
			if (ferror) return false;
			else var str = $(this).serialize();

			var this_form = $(this);
			var action = $(this).attr('action');

			$("#editExhibitorButton").button('loading');

			$.ajax({
				type: "POST",
				url: action,
				data: str,
				dataType: 'json',
				success: function (response) {
					if (response.success == true) {
						$("#editExhibitorButton").button('reset');
						$("#editExhibitorForm")[0].reset();
						showExhibitorsTable();
						$('#edit-exhibitor-messages').html('<div class="sent-message">' + response.messages + '</div>');
						this_form.find('.sent-message').slideDown().html(response.messages);
						$(".sent-message").delay(500).show(10, function () {
							$(this).delay(3000).hide(10, function () {
								$(this).remove();
								$('#editExhibitorModal').modal('hide');
							});
						});
					} else {
						$("#editExhibitorButton").button('reset');
						$('#edit-exhibitor-messages').html('<div class="error-message">' + response.messages + '</div>');
						this_form.find('.error-message').slideDown().html(response.messages);
						$(".error-message").delay(500).show(10, function () { });
					}
				}
			});
			return false;
		});

		//Edit partner logo
		$('#editExhibitorImageForm').unbind('submit').bind('submit', function () {
			var speakerImage = $("#editExhibitorImage").val();
			if (speakerImage == "") {
				$("#exhibitorImage").after('<p class="text-danger">Exhibitor picture is required</p>');
				$('#exhibitorImage').closest('.form-group').addClass('has-error');
			} else {
				$("#exhibitorImage").find('.text-danger').remove();
				$("#exhibitorImage").closest('.form-group').addClass('has-success');
			}

			if (speakerImage) {
				var this_form = $(this);
				var action = $(this).attr('action');
				var formData = new FormData(this);
				$.ajax({
					type: 'POST',
					url: action,
					data: formData,
					dataType: 'json',
					cache: false,
					contentType: false,
					processData: false,
					success: function (response) {
						if (response.success == true) {
							$("#editExhibitorImageForm")[0].reset();
							showExhibitorsTable();
							$('#edit-exhibitorImage-messages').html('<div class="sent-message">' + response.messages + '</div>');
							this_form.find('.sent-message').slideDown().html(response.messages);
							$(".sent-message").delay(500).show(10, function () {
								$(this).delay(3000).hide(10, function () {
									$(this).remove();
									$(".fileinput-remove-button").click();
									$('#editExhibitorModal').modal('hide');
								});
							});
						} else {
							$('#edit-exhibitorImage-messages').html('<div class="error-message">' + response.messages + '</div>');
							this_form.find('.error-message').slideDown().html(response.messages);
							$(".error-message").delay(500).show(10, function () { });
						}
					}
				});
			}
			return false;
		});

	});

	//status
	$(document).on('click', '.block_exhibitor', function () {
		var exhibitorId = $(this).data('id');
		var request = $(this).data('request');
		$.ajax({
			type: 'POST',
			url: linkto,
			data: { exhibitorId: exhibitorId, request: request },
			dataType: 'json',
			success: function (response) {
				if (response.success == true) {
					showExhibitorsTable();
				}
			}
		});
	});
});

function showExhibitorsTable() {
	$.ajax({
		type: 'POST',
		url: linkto,
		data: { eventId: eventId, request: "fetchExhibitors" },
		success: function (data) {
			$('#exhibitor-table').html(data);
		}
	});
}