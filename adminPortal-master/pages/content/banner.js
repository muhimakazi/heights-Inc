$(document).ready(function(){
	showEventBanner();

	// Edit event
	$(document).on('click', '.edit_banner', function(){
		var eventId = $(this).data('id');
		$('#editBannerModal').modal('show');
		$("#editEventImage").fileinput({allowedFileExtensions: ["jpg", "png", "gif", "JPG", "PNG", "GIF"]});
						
		$(".editEventImageFooter").append('<input type="hidden" name="eventId" id="eventId" value="'+eventId+'" />');

		//Edit event banner
		$('#editEventImageForm').unbind('submit').bind('submit', function() {
			var eventImage = $("#editEventImage").val();
			if(eventImage == "") {
				$("#eventImage").after('<p class="text-danger">Event banner is required</p>');
				$('#eventImage').closest('.form-group').addClass('has-error');
			}	else {
				$("#eventImage").find('.text-danger').remove();
				$("#eventImage").closest('.form-group').addClass('has-success');	  	
			}

			if(eventImage) {
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
							$("#editEventImageForm")[0].reset();
							showEventBanner();
							$('#edit-eventImage-messages').html('<div class="sent-message">'+ response.messages + '</div>');
				          	this_form.find('.sent-message').slideDown().html(response.messages);
		          			$(".sent-message").delay(500).show(10, function() {
								$(this).delay(3000).hide(10, function() {
									$(this).remove();
									$(".fileinput-remove-button").click();
									$('#editBannerModal').modal('hide');
								});
							});
						} else {
							$('#edit-eventImage-messages').html('<div class="error-message">'+ response.messages + '</div>');
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

function showEventBanner() {
	$.ajax({
		type: 'POST',
		url: linkto,
		data: {request: "fetchBanner"},
		success:function(data){
			$('#eventBanner').html(data);
		}
	});
}

function bannerTextPosition() {
	var text_position = $('#text_position').val();
	$.ajax({
		type: 'POST',
		url: linkto,
		data: {text_position:text_position, request: "bannerTextPosition"},
		success:function(response){
			showEventBanner();
		}
	});
}