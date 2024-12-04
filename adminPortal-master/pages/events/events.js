$(document).ready(function(){
	showEventsList();

	//Add event
	$('#addEventForm').submit(function() {
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
	    var startDate = new Date($('#start_date').val());
		var endDate = new Date($('#end_date').val());
		if (startDate > endDate){
			ferror = ierror = true;
			alert("Invalid date Range. Check start date and end date");
		}
	    if (ferror) return false;
	    else var str = $(this).serialize();

	    var this_form = $(this);
	    var action = $(this).attr('action');

	    $("#addEventButton").button('loading');
	    
	    $.ajax({
	      	type: "POST",
	      	url: linkto,
	      	data: str,
	      	dataType: 'json',
	      	success:function(response) {
				if(response.success == true) {
					$("#addEventButton").button('reset');
					$("#addEventForm")[0].reset();
					$("html, body, div.ibox-content, div#addEventForm").animate({scrollTop: '0'}, 100);
					$('#add-event-messages').html('<div class="sent-message">'+ response.messages + '</div>');
		          	this_form.find('.sent-message').slideDown().html(response.messages);
          			$(".sent-message").delay(500).show(10, function() {
						$(this).delay(3000).hide(10, function() {
							// $(this).remove();
							// $('#addClientModal').modal('hide');
						});
					});
		        } else {
		        	$("#addEventButton").button('reset');
		          	$("html, body, div.ibox-content, div#addEventForm").animate({scrollTop: '0'}, 100);
					$('#add-event-messages').html('<div class="error-message">'+ response.messages + '</div>');
		          	this_form.find('.error-message').slideDown().html(response.messages);
          			$(".error-message").delay(500).show(10, function() {
						
					});
		        }
	      	}
	    });
	    return false;
	});	

	// Edit event
	$(document).on('click', '.edit_event', function(){
		var eventId = $(this).data('id');
		var name    = $('#eName'+eventId).text();
		var code    = $('#eCode'+eventId).text();
		var type    = $('#eType'+eventId).text();
		var ticket  = $('#eTicket'+eventId).text();
		var client  = $('#eClient'+eventId).text();
		var start   = $('#eStart'+eventId).text();
		var end     = $('#eEnd'+eventId).text();
		var venue   = $('#eVenue'+eventId).text();
		var primaryC   = $('#ePrimC'+eventId).text();
		var secondC   = $('#eSeC'+eventId).text();
		$('#editEventModal').modal('show');
		$('#event_code').val(code);
		$('#event_name').val(name);
		$('#event_type').val(type);
		$('#ticket_type').val(ticket);
		$('#event_code').val(code);
		$('#client').val(client);
		$('#start_date').val(start);
		$('#end_date').val(end);
		$('#event_venue').val(venue);
		$('#primary_color').val(primaryC);
		$('#secondary_color').val(secondC);
		$('#editEventButton').val(eventId);
		$('#eventId').val(eventId);
	});

	//Edit event details
	$('#editEventForm').submit(function() {
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
	    var startDate = new Date($('#start_date').val());
		var endDate = new Date($('#end_date').val());
		if (startDate > endDate){
			ferror = ierror = true;
			alert("Invalid date Range. Check start date and end date");
		}
	    if (ferror) return false;
	    else var str = $(this).serialize();

	    var this_form = $(this);
	    var action = $(this).attr('action');

	    $("#editEventButton").button('loading');
	    
	    $.ajax({
	      	type: "POST",
	      	url: linkto,
	      	data: str,
	      	dataType: 'json',
	      	success:function(response) {
				if(response.success == true) {
					$("#editEventButton").button('reset');
					$("#editEventForm")[0].reset();
					showEventsList();
					$('#edit-event-messages').html('<div class="sent-message">'+ response.messages + '</div>');
		          	this_form.find('.sent-message').slideDown().html(response.messages);
          			$(".sent-message").delay(500).show(10, function() {
						$(this).delay(3000).hide(10, function() {
							$(this).remove();
							$('#editEventModal').modal('hide');
						});
					});
		        } else {
		        	$("#editEventButton").button('reset');
					$('#edit-event-messages').html('<div class="error-message">'+ response.messages + '</div>');
		          	this_form.find('.error-message').slideDown().html(response.messages);
          			$(".error-message").delay(500).show(10, function() {});
		        }
	      	}
	    });
	    return false;
	});

	// view event
	$(document).on('click', '.view_event', function(){
		var eventId = $(this).data('id');
		var name    = $('#eName'+eventId).text();
		var code    = $('#eCode'+eventId).text();
		var type    = $('#eType'+eventId).text();
		var ticket  = $('#eTicket'+eventId).text();
		var client  = $('#eClientName'+eventId).text();
		var start   = $('#eStart'+eventId).text();
		var end     = $('#eEnd'+eventId).text();
		var venue   = $('#eVenue'+eventId).text();
		var banner  = $('#eBanner'+eventId).text();
		$('#viewEventModal').modal('show');
		$('#vevent_name').text(name);
		$('#vevent_type').text(type);
		$('#vticket_type').text(ticket);
		$('#vevent_code').text(code);
		$('#vclient').text(client);
		$('#vstart_date').text(start);
		$('#vend_date').text(end);
		$('#vevent_venue').text(venue);
		$('#vbanner').attr("src", banner);
	});

	//Set event id session
	$(document).on('click', '.event_session', function() {
		let eventToken = $(this).data('id');
		let eventName = $(this).data('name');
		let url = $(this).data('url');
		$.ajax({
			type: 'POST',
			url: linkto,
			data: {eventToken: eventToken, eventName: eventName, action: linkto, request: "eventSession"},
			dataType: 'json',
			success:function(response) {
				if(response.success == true) {
					if (url === 'dashboard') {
						window.location.href = linktodashboard;
					} else if (url === 'participants') {
						window.location.href = linktoparticipants;
					}
				}
			}
		});
	});
});

function showEventsList(){
	$('#eventTable').DataTable().clear().destroy();

    $('#eventTable').DataTable({
    	'pageLength': 50,
        'dom': 'T<"clear">lfrtip',
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url':linkto,
            'data': { request:"fetchEvents"}
        },
        'columns': [
            { data: 'id' }
        ],
        'oLanguage': {sProcessing: "<div class='loadingGifDiv'><div class='v2-loader'></div></div>"}
    });
}