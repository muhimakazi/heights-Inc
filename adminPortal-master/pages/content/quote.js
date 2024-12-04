$(document).ready(function () {
	showQuoteContent();

	//Add about section
	$('#addQuoteForm').submit(function () {
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

		$("#addQuoteButton").button('loading');

		$.ajax({
			type: "POST",
			url: linkto,
			data: str,
			dataType: 'json',
			success: function (response) {
				if (response.success == true) {
					$("#addQuoteButton").button('reset');
					$("#addQuoteForm")[0].reset();
					showQuoteContent();
					$('#add-quote-messages').html('<div class="sent-message">' + response.messages + '</div>');
					this_form.find('.sent-message').slideDown().html(response.messages);
					$(".sent-message").delay(500).show(10, function () {
						$(this).delay(3000).hide(10, function () { });
					});
				} else {
					$("#addQuoteButton").button('reset');
					$('#add-quote-messages').html('<div class="error-message">' + response.messages + '</div>');
					this_form.find('.error-message').slideDown().html(response.messages);
					$(".error-message").delay(500).show(10, function () { });
				}
			}
		});
		return false;
	});

});

function showQuoteContent() {
	$.ajax({
		type: 'POST',
		url: linkto,
		data: {request: "fetchQuote" },
		success: function (data) {
			$('#addQuoteForm').html(data);
			// ACTIVATE / DEACTIVATE
			$(".quote-status").change(function () {
			    if (this.checked) {
			    	let status = "ACTIVE";
			    	quoteStatus(status)
			    } else {
			    	let status = "DEACTIVE";
			    	quoteStatus(status)
			    }
			});
		}
	});
}

function quoteStatus(status) {
	var contentId = $('#contentId').val();
	$.ajax({
		type: 'POST',
		url: linkto,
		data: {status:status, contentId:contentId, request: "quoteStatus"},
		success:function(data){
			showQuoteContent();
		}
	});
}