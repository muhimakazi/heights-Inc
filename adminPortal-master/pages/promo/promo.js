$(document).ready(function () {
	showPromoCodesContent();

	/** Add  */
	$('#addPromoCodeForm').submit(function () {
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

		var this_form = $(this);
		var action = $(this).attr('action');

		$("#addPromoCodeButton").button('loading');

		$.ajax({
			type: "POST",
			url: action,
			data: str,
			cache: false,
			success: function (dataResponse) {

				var response = JSON.parse(dataResponse);
				if (response.success == true) {
					$("#addPromoCodeButton").button('reset');
					$("#addPromoCodeForm")[0].reset();
					showPromoCodesContent();
					$('#add-messages').html('<div class="sent-message">' + response.messages + '</div>');
					this_form.find('.sent-message').slideDown().html(response.messages);
					$(".sent-message").delay(500).show(20, function () {
						$(this).delay(12000).hide(10, function () { });
						// $('#generateLinkModal .close').click();
					});

					setTimeout(function () {
						$('#addPromoCodeModal .close').delay(1200).click();
					}, 5000);
				} else {
					$("#addPromoCodeButton").button('reset');
					$('#add-messages').html('<div class="error-message">' + response.messages + '</div>');
					this_form.find('.error-message').slideDown().html(response.messages);
					$(".error-message").delay(500).show(10, function () { });
				}
			}
		});
		return false;
	});

	/** Edit  */

	$(document).on('click', '.edit_promo', function() {
		var id = $(this).attr("data-id");
		var participation = $(this).attr("data-participation");
		var name = $(this).attr("data-promo");
		var discount = $(this).attr("data-discount");
		var total = $(this).attr("data-total");
		var organisation = $(this).attr("data-organisation");
		$('#editId').val(id);
		$('#edit_participation_type').val(participation);
		$('#edit_promo_code').val(name);
		$('#edit_discount').val(discount);
		$('#edit_total').val(total);
		$('#edit_organisation').val(organisation);

		$('#editPromoModal').modal('show'); 
	});

	$("#editPromoForm input").jqBootstrapValidation({
        preventSubmit: true,
        submitError: function ($form, event, errors) {
        },
        submitSuccess: function ($form, event) {
            event.preventDefault();
            var str = $('#editPromoForm').serialize();
            var this_form = $('#editPromoForm');
            $("#editPromoButton").button('loading');
            $.ajax({
                type: "POST",
                url: linkto,
                data: str,
                success: function (dataResponse) {
                    var response = JSON.parse(dataResponse);
                    if (response.success == true) {
                        $("#editPromoButton").button('reset');
						$("#editPromoForm")[0].reset();
						showPromoCodesContent();
						$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
						$('#edit-messages').html('<div class="sent-message">'+ response.messages + '</div>');
			          	this_form.find('.sent-message').slideDown().html(response.messages);
						$(".sent-message").delay(500).show(10, function() {
							$(this).delay(3000).hide(10, function() {
								$(this).remove();
								$('#editPromoModal').modal('hide');
							});
						});
                    } else {
                        $("#editPromoButton").button('reset');
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


	/** Activate */
	$(document).on('click', '.activate_promo', function() {
		var id = $(this).attr("data-id");
		$('#activateId').val(id);
		$('#activatePromoModal').modal('show'); 

		$("#activatePromoForm input").jqBootstrapValidation({
	        preventSubmit: true,
	        submitError: function ($form, event, errors) {
	        },
	        submitSuccess: function ($form, event) {
	            event.preventDefault();
	            var str = $('#activatePromoForm').serialize();
	            var this_form = $('#activatePromoForm');
	            $("#activatePromoButton").button('loading');
	            $.ajax({
	                type: "POST",
	                url: linkto,
	                data: str,
	                success: function (dataResponse) {
	                    var response = JSON.parse(dataResponse);
	                    if (response.success == true) {
	                        $("#activatePromoButton").button('reset');
							$("#activatePromoForm")[0].reset();
							showPromoCodesContent();
							$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
							$('#activate-messages').html('<div class="sent-message">'+ response.messages + '</div>');
				          	this_form.find('.sent-message').slideDown().html(response.messages);
							$("#activate-messages").delay(500).show(10, function() {
								$(this).delay(3000).hide(10, function() {
									$(this).remove();
									$('#activatePromoModal').modal('hide');
								});
							});
	                    } else {
	                        $("#activatePromoButton").button('reset');
				          	$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
							$('#activate-messages').html('<div class="error-message">'+ response.messages + '</div>');
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

	$(document).on('click', '.deactivate_promo', function() {
		var id = $(this).attr("data-id");
		$('#deactivateId').val(id);
		$('#deactivatePromoModal').modal('show'); 

		$("#deactivatePromoCodeForm input").jqBootstrapValidation({
	        preventSubmit: true,
	        submitError: function ($form, event, errors) {
	        },
	        submitSuccess: function ($form, event) {
	            event.preventDefault();
	            var str = $('#deactivatePromoCodeForm').serialize();
	            var this_form = $('#deactivePromoForm');
	            $("#deactivatePromoCodeButton").button('loading');
	            $.ajax({
	                type: "POST",
	                url: linkto,
	                data: str,
	                success: function (dataResponse) {
	                    var response = JSON.parse(dataResponse);
	                    if (response.success == true) {
	                        $("#deactivatePromoCodeButton").button('reset');
							$("#deactivatePromoCodeForm")[0].reset();
							showPromoCodesContent();
							$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
							$('#deactivate-messages').html('<div class="sent-message">'+ response.messages + '</div>');
				          	this_form.find('.sent-message').slideDown().html(response.messages);
							$("#deactivate-messages").delay(500).show(10, function() {
								$(this).delay(3000).hide(10, function() {
									$(this).remove();
									$('#deactivatePromoModal').modal('hide');
								});
							});
	                    } else {
	                        $("#deactivatePromoCodeButton").button('reset');
				          	$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
							$('#deactivate-messages').html('<div class="error-message">'+ response.messages + '</div>');
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


});




function showPromoCodesContent() {
	$.ajax({
		type: 'POST',
		url: linkto,
		data: {request: "fetchPromoCodes" },
		success: function (data) {
			$('#list-promo-codes').html(data);
		}
	});
}