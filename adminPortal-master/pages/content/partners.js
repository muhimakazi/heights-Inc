$(document).ready(function(){
	showPartnersList();

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
	    layoutTemplates: {main2: '{preview} {remove} {browse}'},								    
  		allowedFileExtensions: ["jpg", "png", "gif", "JPG", "PNG", "GIF"]
	});   

	//Add partner
	$('#addPartnerForm').submit(function() {
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
	    if (ferror) return false;
	    else var str = $(this).serialize();

	    var this_form = $(this);
	    var action = $(this).attr('action');
	    var formData = new FormData(this);

	    $("#addPartnerButton").button('loading');
	    
	    $.ajax({
	      	type: "POST",
	      	url: action,
	      	data: formData,
	      	dataType: 'json',
	      	cache: false,
			contentType: false,
			processData: false,
	      	success:function(response) {
				if(response.success == true) {
					$("#addPartnerButton").button('reset');
					$("#addPartnerForm")[0].reset();
					showPartnersList();
					$('#add-partner-messages').html('<div class="sent-message">'+ response.messages + '</div>');
		          	this_form.find('.sent-message').slideDown().html(response.messages);
          			$(".sent-message").delay(500).show(10, function() {
						$(this).delay(3000).hide(10, function() {
							$(this).remove();
							$('#addPartnerModal').modal('hide');
						});
					});
		        } else {
		        	$("#addPartnerButton").button('reset');
					$('#add-partner-messages').html('<div class="error-message">'+ response.messages + '</div>');
		          	this_form.find('.error-message').slideDown().html(response.messages);
          			$(".error-message").delay(500).show(10, function() {
						
					});
		        }
	      	}
	    });
	    return false;
	});	

	// Edit event
	$(document).on('click', '.edit_partner', function(){
		var partnerId = $(this).data('id');
		var name      = $('#eName'+partnerId).text();
		var order     = $('#eOrder'+partnerId).text();
		$('#editPartnerModal').modal('show');
		$('#ename').val(name);
		$('#eorder').val(order);
		$('#editPartnerButton').val(partnerId);
		$("#editPartnerImage").fileinput({allowedFileExtensions: ["jpg", "png", "gif", "JPG", "PNG", "GIF"]});

		$(".editPartnerFooter").append('<input type="hidden" name="partnerId" id="partnerId" value="'+partnerId+'" />');				
		$(".editPartnerImageFooter").append('<input type="hidden" name="partnerId" id="partnerId" value="'+partnerId+'" />');

		//Edit event details
		$('#editPartnerForm').submit(function() {
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
		    if (ferror) return false;
		    else var str = $(this).serialize();

		    var this_form = $(this);
		    var action = $(this).attr('action');

		    $("#editPartnerButton").button('loading');
		    
		    $.ajax({
		      	type: "POST",
		      	url: action,
		      	data: str,
		      	dataType: 'json',
		      	success:function(response) {
					if(response.success == true) {
						$("#editPartnerButton").button('reset');
						$("#editPartnerForm")[0].reset();
						showPartnersList();
						$('#edit-partner-messages').html('<div class="sent-message">'+ response.messages + '</div>');
			          	this_form.find('.sent-message').slideDown().html(response.messages);
	          			$(".sent-message").delay(500).show(10, function() {
							$(this).delay(3000).hide(10, function() {
								$(this).remove();
								$('#editPartnerModal').modal('hide');
							});
						});
			        } else {
			        	$("#editPartnerButton").button('reset');
						$('#edit-partner-messages').html('<div class="error-message">'+ response.messages + '</div>');
			          	this_form.find('.error-message').slideDown().html(response.messages);
	          			$(".error-message").delay(500).show(10, function() {});
			        }
		      	}
		    });
		    return false;
		});

		//Edit partner logo
		$('#editPartnerImageForm').unbind('submit').bind('submit', function() {
			var speakerImage = $("#editPartnerImage").val();
			if(speakerImage == "") {
				$("#partnerImage").after('<p class="text-danger">Partner picture is required</p>');
				$('#partnerImage').closest('.form-group').addClass('has-error');
			}	else {
				$("#partnerImage").find('.text-danger').remove();
				$("#partnerImage").closest('.form-group').addClass('has-success');	  	
			}

			if(speakerImage) {
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
					success:function(response) {
						if(response.success == true) {
							$("#editPartnerImageForm")[0].reset();
							showPartnersList();
							$('#edit-partnerImage-messages').html('<div class="sent-message">'+ response.messages + '</div>');
				          	this_form.find('.sent-message').slideDown().html(response.messages);
		          			$(".sent-message").delay(500).show(10, function() {
								$(this).delay(3000).hide(10, function() {
									$(this).remove();
									$(".fileinput-remove-button").click();
									$('#editPartnerModal').modal('hide');
								});
							});
						} else {
							$('#edit-partnerImage-messages').html('<div class="error-message">'+ response.messages + '</div>');
				          	this_form.find('.error-message').slideDown().html(response.messages);
		          			$(".error-message").delay(500).show(10, function() {});
						}
					}
				});
			}
			return false;
		});

	});
});

function showPartnersList() {
	$.ajax({
		type: 'POST',
		url: linkto,
		data: {eventId: eventId, request: "fetchPartners"},
		success:function(data){
			$('#partners-list').html(data);
		}
	});
}