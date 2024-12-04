$(document).ready(function(){
	showEventLogo();

	// Edit event
	$(document).on('click', '.edit_logo', function(){
		var eventId = $(this).data('id');
		$('#editLogoModal').modal('show');
		$("#editEventLogo").fileinput({allowedFileExtensions: ["jpg", "png", "gif", "JPG", "PNG", "GIF"]});
						
		$(".editEventLogoFooter").append('<input type="hidden" name="eventId" id="eventId" value="'+eventId+'" />');

		//Edit event logo
		$('#editEventLogoForm').unbind('submit').bind('submit', function() {
			var eventLogo = $("#editEventLogo").val();
			if(eventLogo == "") {
				$("#eventLogo").after('<p class="text-danger">Event logo is required</p>');
				$('#eventLogo').closest('.form-group').addClass('has-error');
			}	else {
				$("#eventLogo").find('.text-danger').remove();
				$("#eventLogo").closest('.form-group').addClass('has-success');	  	
			}

			if(eventLogo) {
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
							$("#editEventLogoForm")[0].reset();
							showEventLogo();
							$('#edit-eventLogo-messages').html('<div class="sent-message">'+ response.messages + '</div>');
				          	this_form.find('.sent-message').slideDown().html(response.messages);
		          			$(".sent-message").delay(500).show(10, function() {
								$(this).delay(3000).hide(10, function() {
									$(this).remove();
									$(".fileinput-remove-button").click();
									$('#editLogoModal').modal('hide');
								});
							});
						} else {
							$('#edit-eventLogo-messages').html('<div class="error-message">'+ response.messages + '</div>');
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

function showEventLogo() {
	$.ajax({
		type: 'POST',
		url: linkto,
		data: {eventId: eventId, request: "fetchLogo"},
		success:function(data){
			$('#eventLogo').html(data);
		}
	});
}