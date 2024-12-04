$(document).ready(function(){
	showCountdown();

	//Add countdown
	$('#countdownForm').submit(function() {
	    var f = $(this).find('.form-group'),
	     	ferror = false,
	      	emailExp = /^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i;

	    f.children('input').each(function() { // run all inputs
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
		            if (i.val() === '' || i.val() === 'dd/mm/yyyy') {
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

	    $("#countdownButton").button('loading');
	    
	    $.ajax({
	      	type: "POST",
	      	url: linkto,
	      	data: str,
	      	dataType: 'json',
	      	success:function(response) {
				if(response.success == true) {
					$("#countdownButton").button('reset');
					$("#countdownForm")[0].reset();
					showCountdown();
					$('#add-countdown-messages').html('<div class="sent-message">'+ response.messages + '</div>');
		          	this_form.find('.sent-message').slideDown().html(response.messages);
          			$(".sent-message").delay(500).show(10, function() {
						$(this).delay(3000).hide(10, function() {
							// location.reload(true);
						});
					});
		        } else {
		        	$("#countdownButton").button('reset');
					$('#add-countdown-messages').html('<div class="error-message">'+ response.messages + '</div>');
		          	this_form.find('.error-message').slideDown().html(response.messages);
          			$(".error-message").delay(500).show(10, function() {});
		        }
	      	}
	    });
	    return false;
	});

});

function showCountdown() {
	$.ajax({
		type: 'POST',
		url: linkto,
		data: {request: "fetchCountdown"},
		success:function(data){
			$('#countdownForm').html(data);
			// ACTIVATE / DEACTIVATE
			$(".countDown-status").change(function () {
			    if (this.checked) {
			    	let status = "ACTIVE";
			    	countdownStatus(status)
			    } else {
			    	let status = "DEACTIVE";
			    	countdownStatus(status)
			    }
			});
		}
	});
}

function countdownStatus(status) {
	var contentId = $('#contentId').val();
	$.ajax({
		type: 'POST',
		url: linkto,
		data: {status:status, contentId:contentId, request: "countdownStatus"},
		success:function(data){
			showCountdown();
		}
	});
}