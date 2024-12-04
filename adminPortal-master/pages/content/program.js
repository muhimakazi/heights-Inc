$(document).ready(function(){
	showSessionsList();

	//Add session
	$('#addSessionForm').submit(function() {
	    var f = $(this).find('.form-group'),
	    	g = $(this).find('.input-group'),
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
		            if (i.val() === '') {
		              ferror = ierror = true;
		            }
		            break;
	        }
	        i.next('.validate').html((ierror ? (i.attr('data-msg') !== undefined ? i.attr('data-msg') : 'wrong Input') : '')).show('blind');
	    	}
	    });
	    g.children('input').each(function() { // run all inputs
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
	    f.children('select').each(function() { // run all inputs
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
	    f.children('textarea').each(function() { // run all inputs
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
			        case 'maxlen':
			            if (i.val().length > parseInt(exp)) {
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

	    $("#addSessionButton").button('loading');
	    
	    $.ajax({
	      	type: "POST",
	      	url: action,
	      	data: str,
	      	dataType: 'json',
	      	success:function(response) {
				if(response.success == true) {
					$("#addSessionButton").button('reset');
					$("#addSessionForm")[0].reset();
					showSessionsList();
					$('#add-session-messages').html('<div class="sent-message">'+ response.messages + '</div>');
		          	this_form.find('.sent-message').slideDown().html(response.messages);
          			$(".sent-message").delay(500).show(10, function() {
						$(this).delay(3000).hide(10, function() {
							$(this).remove();
							$('#addSessionModal').modal('hide');
						});
					});
		        } else {
		        	$("#addSessionButton").button('reset');
					$('#add-session-messages').html('<div class="error-message">'+ response.messages + '</div>');
		          	this_form.find('.error-message').slideDown().html(response.messages);
          			$(".error-message").delay(500).show(10, function() {
						
					});
		        }
	      	}
	    });
	    return false;
	});	

	// Edit session
	$(document).on('click', '.edit_session', function(){
		var sessionId = $(this).data('id');
		var name  = $('#eName'+sessionId).text();
		var type  = $('#eType'+sessionId).text();
		var att   = $('#eAtt'+sessionId).text();
		var start = $('#eStart'+sessionId).text();
		var end   = $('#eEnd'+sessionId).text();
		var descr = $('#eDescr'+sessionId).text();
		var vid   = $('#eVid'+sessionId).text();
		var room  = $('#eRoom'+sessionId).text();
		
		$('#editSessionModal').modal('show');
		$('#esession_name').val(name);
		$('#esession_type').val(type);
		$('#eattendance').val(att);
		$('#estart_time').val(start);
		$('#eend_time').val(end);
		$('.note-editable').html(descr);
		$('#evideo').val(vid);
		$('#eroom').val(room);
		$('#editSessionButton').val(sessionId);

		$(".editSessionFooter").append('<input type="hidden" name="sessionId" id="sessionId" value="'+sessionId+'" />');

		//Edit event details
		$('#editSessionForm').submit(function() {
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
			            if (i.val() === '') {
			              ferror = ierror = true;
			            }
			            break;
		        }
		        i.next('.validate').html((ierror ? (i.attr('data-msg') !== undefined ? i.attr('data-msg') : 'wrong Input') : '')).show('blind');
		    	}
		    });
		    f.children('select').each(function() { // run all inputs
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
		    f.children('textarea').each(function() { // run all inputs
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
				        case 'maxlen':
				            if (i.val().length > parseInt(exp)) {
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

		    $("#editSessionButton").button('loading');
		    
		    $.ajax({
		      	type: "POST",
		      	url: action,
		      	data: str,
		      	dataType: 'json',
		      	success:function(response) {
					if(response.success == true) {
						$("#editSessionButton").button('reset');
						$("#editSessionForm")[0].reset();
						showSessionsList();
						$('#edit-session-messages').html('<div class="sent-message">'+ response.messages + '</div>');
			          	this_form.find('.sent-message').slideDown().html(response.messages);
	          			$(".sent-message").delay(500).show(10, function() {
							$(this).delay(3000).hide(10, function() {
								$(this).remove();
								$('#editSessionModal').modal('hide');
								$('.note-editable').text("");
							});
						});
			        } else {
			        	$("#editSessionButton").button('reset');
						$('#edit-session-messages').html('<div class="error-message">'+ response.messages + '</div>');
			          	this_form.find('.error-message').slideDown().html(response.messages);
	          			$(".error-message").delay(500).show(10, function() {});
			        }
		      	}
		    });
		    return false;
		});
	});
});

function showSessionsList() {
	$.ajax({
		type: 'POST',
		url: linkto,
		data: {eventId: eventId, day: day, request: "fetchSessions"},
		success:function(data){
			$('#sessions-list').html(data);
		}
	});
}