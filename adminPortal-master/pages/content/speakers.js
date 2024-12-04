$(document).ready(function(){
	showSpeakersList();

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

	//Add speaker
	$('#addSpeakerForm').submit(function() {
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
	    var formData = new FormData(this);

	    $("#addSpeakerButton").button('loading');
	    
	    $.ajax({
	      	type: "POST",
	      	url: linkto,
	      	data: formData,
	      	dataType: 'json',
	      	cache: false,
			contentType: false,
			processData: false,
	      	success:function(response) {
				if(response.success == true) {
					$("#addSpeakerButton").button('reset');
					$("#addSpeakerForm")[0].reset();
					showSpeakersList();
					$('#add-speaker-messages').html('<div class="sent-message">'+ response.messages + '</div>');
		          	this_form.find('.sent-message').slideDown().html(response.messages);
          			$(".sent-message").delay(500).show(10, function() {
						$(this).delay(3000).hide(10, function() {
							$(this).remove();
							$('#addSpeakerModal').modal('hide');
						});
					});
		        } else {
		        	$("#addSpeakerButton").button('reset');
					$('#add-speaker-messages').html('<div class="error-message">'+ response.messages + '</div>');
		          	this_form.find('.error-message').slideDown().html(response.messages);
          			$(".error-message").delay(500).show(10, function() {
						
					});
		        }
	      	}
	    });
	    return false;
	});	

	// Edit speaker
	$(document).on('click', '.edit_speaker', function(){
		var speakerId = $(this).data('id');
		var name      = $('#eName'+speakerId).text();
		var org       = $('#eOrga'+speakerId).text();
		var job       = $('#eJob'+speakerId).text();
		$('#editSpeakerModal').modal('show');
		$('#ename').val(name);
		$('#eorganisation').val(org);
		$('#ejob_title').val(job);
		$('#editSpeakerButton').val(speakerId);
		$("#editSpeakerImage").fileinput({allowedFileExtensions: ["jpg", "png", "gif", "JPG", "PNG", "GIF"]});

		$(".editSpeakerFooter").append('<input type="hidden" name="speakerId" id="speakerId" value="'+speakerId+'" />');				
		$(".editSpeakerImageFooter").append('<input type="hidden" name="speakerId" id="speakerId" value="'+speakerId+'" />');

		//Edit event details
		$('#editSpeakerForm').submit(function() {
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

		    $("#editSpeakerButton").button('loading');
		    
		    $.ajax({
		      	type: "POST",
		      	url: linkto,
		      	data: str,
		      	dataType: 'json',
		      	success:function(response) {
					if(response.success == true) {
						$("#editSpeakerButton").button('reset');
						$("#editSpeakerForm")[0].reset();
						showSpeakersList();
						$('#edit-speaker-messages').html('<div class="sent-message">'+ response.messages + '</div>');
			          	this_form.find('.sent-message').slideDown().html(response.messages);
	          			$(".sent-message").delay(500).show(10, function() {
							$(this).delay(3000).hide(10, function() {
								$(this).remove();
								$('#editSpeakerModal').modal('hide');
							});
						});
			        } else {
			        	$("#editSpeakerButton").button('reset');
						$('#edit-speaker-messages').html('<div class="error-message">'+ response.messages + '</div>');
			          	this_form.find('.error-message').slideDown().html(response.messages);
	          			$(".error-message").delay(500).show(10, function() {});
			        }
		      	}
		    });
		    return false;
		});

		//Edit speaker picture
		$('#editSpeakerImageForm').unbind('submit').bind('submit', function() {
			var speakerImage = $("#editSpeakerImage").val();
			if(speakerImage == "") {
				$("#speakerImage").after('<p class="text-danger">Speaker picture is required</p>');
				$('#speakerImage').closest('.form-group').addClass('has-error');
			}	else {
				$("#speakerImage").find('.text-danger').remove();
				$("#speakerImage").closest('.form-group').addClass('has-success');	  	
			}

			if(speakerImage) {
				var this_form = $(this);
				var formData = new FormData(this);
				$.ajax({
					type: 'POST',
					url: linkto,
					data: formData,
					dataType: 'json',
					cache: false,
					contentType: false,
					processData: false,
					success:function(response) {
						if(response.success == true) {
							$("#editSpeakerImageForm")[0].reset();
							showSpeakersList();
							$('#edit-speakerImage-messages').html('<div class="sent-message">'+ response.messages + '</div>');
				          	this_form.find('.sent-message').slideDown().html(response.messages);
		          			$(".sent-message").delay(500).show(10, function() {
								$(this).delay(3000).hide(10, function() {
									$(this).remove();
									$(".fileinput-remove-button").click();
									$('#editSpeakerModal').modal('hide');
								});
							});
						} else {
							$('#edit-speakerImage-messages').html('<div class="error-message">'+ response.messages + '</div>');
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

function showSpeakersList() {
	$.ajax({
		type: 'POST',
		url: linkto,
		data: {request: "fetchSpeakers"},
		success:function(data){
			$('#speakers-list').html(data);
		}
	});
}