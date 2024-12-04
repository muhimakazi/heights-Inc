$(document).ready(function(){
	showExhibitorVideoSection();

	$(document).on('click', '#addExhibitorVideo', function() {
		$('#addExhibitorVideoModal').modal('show');
		$("#addExhibitorVideoForm")[0].reset();		
		$(".text-danger").remove();
		$(".form-group").removeClass('has-error').removeClass('has-success');

		$("#video").fileinput({
	       overwriteInitial: true,
		    maxFileSize: 2500,
		    showClose: false,
		    showCaption: false,
		    browseLabel: '',
		    removeLabel: '',
		    browseIcon: '<i class="glyphicon glyphicon-folder-open"></i>',
		    removeIcon: '<i class="glyphicon glyphicon-remove"></i>',
		    removeTitle: 'Cancel or reset changes',
		    elErrorContainer: '#kv-avatar-errors-1',
		    msgErrorClass: 'alert alert-block alert-danger',
		    defaultPreviewContent: '<img src="../../../img/photo_default.png" alt="Exhibitor logo" style="width:100%;">',
		    layoutTemplates: {main2: '{preview} {remove} {browse}'},								    
	  		allowedFileExtensions: ["mp4"]
		});

		var exhibitorId = $(this).data('id');
		$(".addExhibitorVideoFooter").append('<input type="hidden" name="exhibitorId" id="exhibitorId" value="'+exhibitorId+'" />');

		// submit product form
		$("#addExhibitorVideoForm").unbind('submit').bind('submit', function() {
			var video = $("#video").val();
			var description = $("#description").val();
			if(video == "") {
				$("#video").closest('.center-block').after('<p class="text-danger">Exhibitor video is required</p>');
				$('#video').closest('.form-group').addClass('has-error');
			}	else {
				$("#video").find('.text-danger').remove();
				$("#video").closest('.form-group').addClass('has-success');	  	
			}
			if(description == "") {
				$("#description").after('<p class="text-danger">Exhibitor description is required</p>');
				$('#description').closest('.form-group').addClass('has-error');
			}	else {
				$("#description").find('.text-danger').remove();
				$("#description").closest('.form-group').addClass('has-success');	  	
			}

			if(video && description) {
				$("#addExhibitorVideoButton").button('loading');
				var form = $(this);
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
							$("#addExhibitorVideoButton").button('reset');
							$("#addExhibitorVideoForm")[0].reset();
							showExhibitorVideoSection();
							$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
							$('#add-exhibitor-video-messages').html('<div class="alert alert-success">'+
				            '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
				            '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> '+ response.messages +
				            '</div>');
		          			$(".alert-success").delay(500).show(10, function() {
								$(this).delay(3000).hide(10, function() {
									$(this).remove();
								});
							});
							$(".text-danger").remove();
							$(".form-group").removeClass('has-error').removeClass('has-success');
						} else {
							$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
							$('#add-exhibitor-video-messages').html('<div class="alert alert-danger">'+
					        '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
					        '<strong><i class="fa fa-times-circle icon"></i></strong> '+ response.messages +
					        '</div>');
					        $(".alert-danger").delay(500).show(10, function() {
								
							});
						}
					}
				});
			}					
			return false;
		});
	});

	//edit exhibitor video
	$(document).on('click', '#exhibitorVideo', function(){
		var exhibitorId = $(this).data('id');
		var descr = $('#eDescr'+exhibitorId).text();
		$('#editExhibitorVideoModal').modal('show');
		$('#edescription').val(descr);
		$('#editExhibitorVideoButton').val(exhibitorId);
		$("#editExhibitorVideo").fileinput({allowedFileExtensions: ["mp4"]});

		$(".editExhibitorVideoFooter").append('<input type="hidden" name="exhibitorId" id="exhibitorId" value="'+exhibitorId+'" />');
		$(".editExhibitorAboutFooter").append('<input type="hidden" name="exhibitorId" id="exhibitorId" value="'+exhibitorId+'" />');

		$('#editExhibitorAboutForm').unbind('submit').bind('submit', function() {
			var description = $("#edescription").val();
			if(description == "") {
				$("#edescription").after('<p class="text-danger">Exhibitor description is required</p>');
				$('#edescription').closest('.form-group').addClass('has-error');
			}	else {
				$("#edescription").find('.text-danger').remove();
				$("#edescription").closest('.form-group').addClass('has-success');	  	
			}

			if(description) {
				$("#editExhibitorAboutButton").button('loading');
				var form = $(this);
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
							$("#editExhibitorAboutButton").button('reset');
							$("#editExhibitorAboutForm")[0].reset();
							showExhibitorVideoSection();
							$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
							$('#edit-exhibitorAbout-messages').html('<div class="alert alert-success">'+
					        '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
					        '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> '+ response.messages +
					        '</div>');
		          			$(".alert-success").delay(500).show(10, function() {
								$(this).delay(3000).hide(10, function() {
									$(this).remove();
									$('#editExhibitorVideoModal').modal('hide');
								});
							});
						} else {
							$("#editExhibitorAboutButton").button('reset');
							$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
							$('#edit-exhibitorAbout-messages').html('<div class="alert alert-danger">'+
					        '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
					        '<strong><i class="fa fa-times-circle icon"></i></strong> '+ response.messages +
					        '</div>');
					        $(".alert-danger").delay(500).show(10, function() {
								
							});
						}
					}
				});
			}
			return false;
		});

		$('#editExhibitorVideoForm').unbind('submit').bind('submit', function() {
			var exhibitorVideo = $("#editExhibitorVideo").val();
			if(exhibitorVideo == "") {
				$("#editExhibitorVideo").after('<p class="text-danger">Exhibitor video required</p>');
				$('#editExhibitorVideo').closest('.form-group').addClass('has-error');
			}	else {
				$("#editExhibitorVideo").find('.text-danger').remove();
				$("#editExhibitorVideo").closest('.form-group').addClass('has-success');	  	
			}

			if(exhibitorVideo) {
				var form = $(this);
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
							$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
							$('#edit-exhibitorVideo-messages').html('<div class="alert alert-success">'+
					        '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
					        '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> '+ response.messages +
					        '</div>');
		          			$(".alert-success").delay(500).show(10, function() {
								$(this).delay(3000).hide(10, function() {
									$(this).remove();
									// $('#EditExhibitorModal').modal('hide');
								});
							});
							showExhibitorVideoSection();
							$(".fileinput-remove-button").click();
							// $.ajax({
							// 	url: 'exhibitors_action.php?videoUrl='+exhibitorId,
							// 	type: 'post',
							// 	success:function(response) {
							// 	$("#getExhibitorVideo").attr('src', response);		
							// 	}
							// });																		
							$(".text-danger").remove();
							$(".form-group").removeClass('has-error').removeClass('has-success');
						} else {
							$("#editExhibitorButton").button('reset');
							$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
							$('#edit-exhibitorVideo-messages').html('<div class="alert alert-danger">'+
					        '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
					        '<strong><i class="fa fa-times-circle icon"></i></strong> '+ response.messages +
					        '</div>');
					        $(".alert-danger").delay(500).show(10, function() {
								
							});
						}
					}
				});
			}
			return false;
		});
	});

	//Exhibitor mission
	$(document).on('click', '#exhibitorMission', function(){
		showExhibitorMissionSection();

		$(document).on('click', '#addExhibitorMission', function() {
			$('#addExhibitorMissionModal').modal('show');
			$("#addExhibitorMissionForm")[0].reset();		
			$(".text-danger").remove();
			$(".form-group").removeClass('has-error').removeClass('has-success');

			var exhibitorId = $(this).data('id');
			$(".addExhibitorMissionFooter").append('<input type="hidden" name="exhibitorId" id="exhibitorId" value="'+exhibitorId+'" />');

			$('#addExhibitorMissionForm').unbind('submit').bind('submit', function() {
				var mission = $("#mission").val();
				if(mission == "") {
					$("#mission").after('<p class="text-danger">Exhibitor mission is required</p>');
					$('#mission').closest('.form-group').addClass('has-error');
				}	else {
					$("#mission").find('.text-danger').remove();
					$("#mission").closest('.form-group').addClass('has-success');	  	
				}

				if(mission) {
					$("#addExhibitorMissionButton").button('loading');
					var form = $(this);
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
								$("#addExhibitorMissionButton").button('reset');
								$("#addExhibitorMissionForm")[0].reset();
								showExhibitorMissionSection();
								$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
								$('#add-exhibitorMission-messages').html('<div class="alert alert-success">'+
						        '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
						        '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> '+ response.messages +
						        '</div>');
			          			$(".alert-success").delay(500).show(10, function() {
									$(this).delay(3000).hide(10, function() {
										$(this).remove();
										$('#addExhibitorMissionModal').modal('hide');
									});
								});
							} else {
								$("#addExhibitorMissionButton").button('reset');
								$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
								$('#add-exhibitorMission-messages').html('<div class="alert alert-danger">'+
						        '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
						        '<strong><i class="fa fa-times-circle icon"></i></strong> '+ response.messages +
						        '</div>');
						        $(".alert-danger").delay(500).show(10, function() {
									
								});
							}
						}
					});
				}
				return false;
			});
		});

		$(document).on('click', '#editExhibitorMission', function() {
			var exhibitorId = $(this).data('id');
			var miss = $('#eMiss'+exhibitorId).text();
			$('#editExhibitorMissionModal').modal('show');
			$('#emission').val(miss);

			$(".editExhibitorMissionFooter").append('<input type="hidden" name="exhibitorId" id="exhibitorId" value="'+exhibitorId+'" />');

			$('#editExhibitorMissionForm').unbind('submit').bind('submit', function() {
				var mission = $("#emission").val();
				if(mission == "") {
					$("#emission").after('<p class="text-danger">Exhibitor mission is required</p>');
					$('#emission').closest('.form-group').addClass('has-error');
				}	else {
					$("#emission").find('.text-danger').remove();
					$("#emission").closest('.form-group').addClass('has-success');	  	
				}

				if(mission) {
					$("#editExhibitorMissionButton").button('loading');
					var form = $(this);
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
								$("#editExhibitorMissionButton").button('reset');
								$("#editExhibitorMissionForm")[0].reset();
								showExhibitorMissionSection();
								$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
								$('#edit-exhibitorMission-messages').html('<div class="alert alert-success">'+
						        '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
						        '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> '+ response.messages +
						        '</div>');
			          			$(".alert-success").delay(500).show(10, function() {
									$(this).delay(3000).hide(10, function() {
										$(this).remove();
										$('#editExhibitorMissionModal').modal('hide');
									});
								});
							} else {
								$("#editExhibitorMissionButton").button('reset');
								$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
								$('#edit-exhibitorMission-messages').html('<div class="alert alert-danger">'+
						        '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
						        '<strong><i class="fa fa-times-circle icon"></i></strong> '+ response.messages +
						        '</div>');
						        $(".alert-danger").delay(500).show(10, function() {
									
								});
							}
						}
					});
				}
				return false;
			});
		});
	});

	//Exhibitor products
	$(document).on('click', '#exhibitorProduct', function(){
		showExhibitorProductSection();
	});

	//Add product
	$('#addProductForm').submit(function() {
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
	    if (ferror) return false;
	    else var str = $(this).serialize();

	    var this_form = $(this);
	    var action = $(this).attr('action');

	    $("#addProductButton").button('loading');
	    
	    $.ajax({
	      	type: "POST",
	      	url: action,
	      	data: str,
	      	dataType: 'json',
	      	success:function(response) {
				if(response.success == true) {
					$("#addProductButton").button('reset');
					$("#addProductForm")[0].reset();
					showExhibitorProductSection();
					$('#add-product-messages').html('<div class="sent-message">'+ response.messages + '</div>');
		          	this_form.find('.sent-message').slideDown().html(response.messages);
          			$(".sent-message").delay(500).show(10, function() {
						$(this).delay(3000).hide(10, function() {
							$(this).remove();
							$('#addProductModal').modal('hide');
						});
					});
		        } else {
		        	$("#addProductButton").button('reset');
					$('#add-product-messages').html('<div class="error-message">'+ response.messages + '</div>');
		          	this_form.find('.error-message').slideDown().html(response.messages);
          			$(".error-message").delay(500).show(10, function() {
						
					});
		        }
	      	}
	    });
	    return false;
	});

	// Edit session
	$(document).on('click', '.edit_product', function(){
		var productId = $(this).data('id');
		var name  = $('#eName'+productId).text();
		
		$('#editProductModal').modal('show');
		$('#eproduct_name').val(name);
		$('#editProductButton').val(productId);

		$(".editProductFooter").append('<input type="hidden" name="productId" id="productId" value="'+productId+'" />');

		//Edit event details
		$('#editProductForm').submit(function() {
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

		    $("#editProductButton").button('loading');
		    
		    $.ajax({
		      	type: "POST",
		      	url: action,
		      	data: str,
		      	dataType: 'json',
		      	success:function(response) {
					if(response.success == true) {
						$("#editProductButton").button('reset');
						$("#editProductForm")[0].reset();
						showExhibitorProductSection();
						$('#edit-product-messages').html('<div class="sent-message">'+ response.messages + '</div>');
			          	this_form.find('.sent-message').slideDown().html(response.messages);
	          			$(".sent-message").delay(500).show(10, function() {
							$(this).delay(3000).hide(10, function() {
								$(this).remove();
								$('#editProductModal').modal('hide');
							});
						});
			        } else {
			        	$("#editProductButton").button('reset');
						$('#edit-product-messages').html('<div class="error-message">'+ response.messages + '</div>');
			          	this_form.find('.error-message').slideDown().html(response.messages);
	          			$(".error-message").delay(500).show(10, function() {});
			        }
		      	}
		    });
		    return false;
		});
	});

	//Exhibitor products
	$(document).on('click', '#exhibitorBrochure', function(){
		showExhibitorBrochureSection();
	});

	// Edit exhibitor
	$(document).on('click', '.add_brochure', function(){
		$('#addBrochureModal').modal('show');

		$("#addBrochure").fileinput({allowedFileExtensions: ["pdf", "PDF"]});

		//Edit partner logo
		$('#addBrochureForm').unbind('submit').bind('submit', function() {
			var brochure = $("#addBrochure").val();
			if(brochure == "") {
				$("#addBrochure").after('<p class="text-danger">Brochure picture is required</p>');
				$('#addBrochure').closest('.form-group').addClass('has-error');
			}	else {
				$("#addBrochure").find('.text-danger').remove();
				$("#addBrochure").closest('.form-group').addClass('has-success');	  	
			}

			if(brochure) {
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
							$("#addBrochureForm")[0].reset();
							showExhibitorBrochureSection();
							$('#add-brochure-messages').html('<div class="sent-message">'+ response.messages + '</div>');
				          	this_form.find('.sent-message').slideDown().html(response.messages);
		          			$(".sent-message").delay(500).show(10, function() {
								$(this).delay(3000).hide(10, function() {
									$(this).remove();
									$(".fileinput-remove-button").click();
									$('#addBrochureModal').modal('hide');
								});
							});
						} else {
							$('#add-brochure-messages').html('<div class="error-message">'+ response.messages + '</div>');
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

function showExhibitorVideoSection(){
	var request = "fetchVideoSection";
	$.ajax({
		type: 'POST',
		url: linkto,
		data: {exhibitorId: exhibitorId, request: request},
		success:function(data){
			$('#tab-1').html(data);
		}
	});
}

function showExhibitorMissionSection(){
	var request = "fetchMissionSection";
	$.ajax({
		type: 'POST',
		url: linkto,
		data: {exhibitorId: exhibitorId, request: request},
		success:function(data){
			$('#tab-2').html(data);
		}
	});
}

function showExhibitorProductSection(){
	var request = "fetchProductSection";
	$.ajax({
		type: 'POST',
		url: linkto,
		data: {exhibitorId: exhibitorId, request: request},
		success:function(data){
			$('#products_list').html(data);
		}
	});
}

function showExhibitorBrochureSection(){
	var request = "fetchBrochureSection";
	$.ajax({
		type: 'POST',
		url: linkto,
		data: {exhibitorId: exhibitorId, request: request},
		success:function(data){
			$('#exh_brochure').html(data);
		}
	});
}