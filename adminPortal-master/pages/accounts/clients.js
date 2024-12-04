$(document).ready(function(){
	showClientTable();

	//Add client
	$('#addClientForm').submit(function() {
	    var f = $(this).find('.field-validate'),
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
	          	case 'minlen':
		            if (i.val().length < parseInt(exp)) {
		              ferror = ierror = true;
		            }
		            break;
	          	case 'email':
		            if (!emailExp.test(i.val())) {
		              ferror = ierror = true;
		            }
		            break;
		        case 'matches':
		            if (i.val() !== $("#password").val()) {
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
	    if (ferror) return false;
	    else var str = $(this).serialize();

	    var this_form = $(this);
	    var action = $(this).attr('action');

	    $("#addClientButton").button('loading');
	    
	    $.ajax({
	      	type: "POST",
	      	url: linkto,
	      	data: str,
	      	dataType: 'json',
	      	success:function(response) {
				if(response.success == true) {
					$("#addClientButton").button('reset');
					$("#addClientForm")[0].reset();
					$("html, body, div.ibox-content, div#addClientForm").animate({scrollTop: '0'}, 100);
					$('#add-client-messages').html('<div class="sent-message">'+ response.messages + '</div>');
		          	this_form.find('.sent-message').slideDown().html(response.messages);
          			$(".sent-message").delay(500).show(10, function() {
						$(this).delay(3000).hide(10, function() {
							// $(this).remove();
							// $('#addClientModal').modal('hide');
						});
					});
		        } else {
		        	$("#addClientButton").button('reset');
		          	$("html, body, div.ibox-content, div#addClientForm").animate({scrollTop: '0'}, 100);
					$('#add-client-messages').html('<div class="error-message">'+ response.messages + '</div>');
		          	this_form.find('.error-message').slideDown().html(response.messages);
          			$(".error-message").delay(500).show(10, function() {
						
					});
		        }
	      	}
	    });
	    return false;
	});

	//edit
	$(document).on('click', '.edit_client', function() {
		var clientId   = $(this).data('id');
		var first      = $('#eFirst'+clientId).text();
		var last       = $('#eLast'+clientId).text();
		var tel        = $('#eTel'+clientId).text();
		var email      = $('#eEmail'+clientId).text();
		var org        = $('#eOrg'+clientId).text();
		var job        = $('#eJob'+clientId).text();
		var city       = $('#eCity'+clientId).text();
		var country    = $('#eCountry'+clientId).text();
		var indus      = $('#eIndus'+clientId).text();
		var emp        = $('#eEmp'+clientId).text();
		var web        = $('#eWeb'+clientId).text();
		var first2     = $('#eFirst2'+clientId).text();
		var last2      = $('#eLast2'+clientId).text();
		var tel2       = $('#eTel2'+clientId).text();
		var email2     = $('#eEmail2'+clientId).text();
		var org2       = $('#eOrg2'+clientId).text();
		var job2       = $('#eJob2'+clientId).text();
		var city2      = $('#eCity2'+clientId).text();
		var country2   = $('#eCountry2'+clientId).text();
		var indus2     = $('#eIndus2'+clientId).text();
		var emp2       = $('#eEmp2'+clientId).text();
		var web2       = $('#eWeb2'+clientId).text();
		var l1         = $('#eL1'+clientId).text();
		var l2         = $('#eL2'+clientId).text();
		var invCity    = $('#eInvCity'+clientId).text();
		var invOrga    = $('#eInvOrga'+clientId).text();
		var clientId   = $(this).data('id');
		var first3      = $('#eFirst3'+clientId).text();
		var last3       = $('#eLast3'+clientId).text();
		var tel3        = $('#eTel3'+clientId).text();
		var email3      = $('#eEmail3'+clientId).text();
		var invCountry = $('#eInvCountry'+clientId).text();

		$('#editClientModal').modal('show');
		$('#firstname').val(first);
		$('#lastname').val(last);
		$('#email').val(email);
		$('#telephone').val(tel);
		$('#organisation').val(org);
		$('#job_title').val(job);
		$('#city').val(city);
		$('#country').val(country);
		$('#employees').val(emp);
		$('#website').val(web);
		$('#firstname2').val(first2);
		$('#lastname2').val(last2);
		$('#email2').val(email2);
		$('#telephone2').val(tel2);
		$('#organisation2').val(org2);
		$('#job_title2').val(job2);
		$('#city2').val(city2);
		$('#country2').val(country2);
		$('#employees2').val(emp2);
		$('#website2').val(web2);
		$('#invoice_line_one').val(l1);
		$('#invoice_line_two').val(l2);
		$('#invoice_organisation').val(invOrga);
		$('#firstname3').val(first3);
		$('#lastname3').val(last3);
		$('#email3').val(email3);
		$('#telephone3').val(tel3);
		$('#invoice_city').val(invCity);
		$('#invoice_country').val(invCountry);

		if (!$('#industry option[value="' +indus+ '"]').prop("selected", true).length) {
        	$('#industry').val("Other");
			$('#industry1').val(indus);
			$('#industry1').removeAttr('disabled');
    	} else {
    		$('#industry').val(indus);
    	}
    	if (!$('#industry2 option[value="' +indus2+ '"]').prop("selected", true).length) {
        	$('#industry2').val("Other");
			$('#industry21').val(indus2);
			$('#industry21').removeAttr('disabled');
    	} else {
    		$('#industry2').val(indus2);
    	}

		$('#editClientButton').val(clientId);

		$(".editClientFooter").append('<input type="hidden" name="clientId" id="clientId" value="'+clientId+'" />');

		$('#editClientForm').submit(function() {
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
		          	case 'minlen':
			            if (i.val().length < parseInt(exp)) {
			              ferror = ierror = true;
			            }
			            break;
		          	case 'email':
			            if (!emailExp.test(i.val())) {
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
		    if (ferror) return false;
		    else var str = $(this).serialize();

		    var this_form = $(this);
		    var action = $(this).attr('action');

		    $("#editClientButton").button('loading');
		    
		    $.ajax({
		      	type: "POST",
		      	url: linkto,
		      	data: str,
		      	dataType: 'json',
		      	success:function(response) {
					if(response.success == true) {
						$("#editClientButton").button('reset');
						$("#editClientForm")[0].reset();
						showClientTable();
						$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
						$('#edit-client-messages').html('<div class="sent-message">'+ response.messages + '</div>');
			          	this_form.find('.sent-message').slideDown().html(response.messages);
	          			$(".sent-message").delay(500).show(10, function() {
							$(this).delay(3000).hide(10, function() {
								$(this).remove();
								$('#editClientModal').modal('hide');
							});
						});
			        } else {
			        	$("#editClientButton").button('reset');
			          	$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
						$('#edit-client-messages').html('<div class="error-message">'+ response.messages + '</div>');
			          	this_form.find('.error-message').slideDown().html(response.messages);
	          			$(".error-message").delay(500).show(10, function() {
							
						});
			        }
		      	}
		    });
		    return false;
		});
	});

	//status
	$(document).on('click', '.block_user', function() {
		var clientId  = $(this).data('id');
		var action = $(this).data('action');
		$.ajax({
			type: 'POST',
			url: linkto,
			data: {clientId: clientId, action: action, request: "activateClient"},
			dataType: 'json',
			success:function(response) {
				if(response.success == true) {
					showClientTable();
				}
			}
		});
	});

	//view client
	$(document).on('click', '.view_client', function() {
		var clientId   = $(this).data('id');
		var first      = $('#eFirst'+clientId).text();
		var last       = $('#eLast'+clientId).text();
		var tel        = $('#eTel'+clientId).text();
		var email      = $('#eEmail'+clientId).text();
		var org        = $('#eOrg'+clientId).text();
		var job        = $('#eJob'+clientId).text();
		var city       = $('#eCity'+clientId).text();
		var country    = $('#eCountry'+clientId).text();
		var indus      = $('#eIndus'+clientId).text();
		var emp        = $('#eEmp'+clientId).text();
		var web        = $('#eWeb'+clientId).text();
		var first2     = $('#eFirst2'+clientId).text();
		var last2      = $('#eLast2'+clientId).text();
		var tel2       = $('#eTel2'+clientId).text();
		var email2     = $('#eEmail2'+clientId).text();
		var org2       = $('#eOrg2'+clientId).text();
		var job2       = $('#eJob2'+clientId).text();
		var city2      = $('#eCity2'+clientId).text();
		var country2   = $('#eCountry2'+clientId).text();
		var indus2     = $('#eIndus2'+clientId).text();
		var emp2       = $('#eEmp2'+clientId).text();
		var web2       = $('#eWeb2'+clientId).text();
		var l1         = $('#eL1'+clientId).text();
		var l2         = $('#eL2'+clientId).text();
		var invCity    = $('#eInvCity'+clientId).text();
		var invOrga    = $('#eInvOrga'+clientId).text();
		var clientId   = $(this).data('id');
		var first3      = $('#eFirst3'+clientId).text();
		var last3       = $('#eLast3'+clientId).text();
		var tel3        = $('#eTel3'+clientId).text();
		var email3      = $('#eEmail3'+clientId).text();
		var invCountry = $('#eInvCountry'+clientId).text();

		$('#viewClientModal').modal('show');
		$('#vname').text(first.concat(" "+last));
		$('#vemail').text(email);
		$('#vtelephone').text(tel);
		$('#vorganisation').text(org);
		$('#vindustry').text(indus);
		$('#vjob_title').text(job);
		$('#vlocation').text(city.concat(", "+country));
		$('#vemployees').text(emp);
		$('#vwebsite').text(web);
		$('#vname2').text(first2.concat(" "+last2));
		$('#vemail2').text(email2);
		$('#vtelephone2').text(tel2);
		$('#vorganisation2').text(org2);
		$('#vindustry2').text(indus2);
		$('#vjob_title2').text(job2);
		$('#vlocation').text(city2.concat(", "+country2));
		$('#vemployees2').text(emp2);
		$('#vwebsite2').text(web2);
		$('#vinvoice_line_one').text(l1);
		$('#vinvoice_line_two').text(l2);
		$('#vinvoice_organisation').text(invOrga);
		$('#vfirstname3').text(first3);
		$('#vlastname3').text(last3);
		$('#vemail3').text(email3);
		$('#vtelephone3').text(tel3);
		$('#vinvoice_city').text(invCity);
		$('#vinvoice_country').text(invCountry);
	});

	//status
	$(document).on('click', '.block_user', function() {
		var clientId  = $(this).data('id');
		var action = $(this).data('action');
		$.ajax({
			type: 'POST',
			url: linkto,
			data: {clientId: clientId, action: action, request: "activateClient"},
			dataType: 'json',
			success:function(response) {
				if(response.success == true) {
					showClientTable();
				}
			}
		});
	});

});

function showClientTable() {
    $('#clientTable').DataTable().clear().destroy();

    $('#clientTable').DataTable({
        'dom': 'T<"clear">lfrtip',
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url':linkto,
            'data': { request:"fetchClients"}
        },
        'columns': [
            { data: 'id' },
            { data: 'firstname' },
            { data: 'lastname' },
            { data: 'email' },
            { data: 'telephone' },
            { data: 'organisation' },
            { data: 'job_title' },
            { data: 'city' },
            { data: 'country' },
            { data: 'status' }
        ],
        'oLanguage': {sProcessing: "<div class='loadingGifDiv'><div class='v2-loader'></div></div>"}
    });
}

function Other(field,field1) {
  	var value = $(field).val();
    var name = $(field).attr('name');
    var name1 = $(field1).attr('name');
    if(value == "Other"){
        if(!$(field).hasClass('swapped')) {
            $(field).addClass('swapped');
            $(field1).prop('disabled', false);
            var input = $(field1);
            input[0].selectionStart = input[0].selectionEnd = input.val().length;
        }
    } else {
        if($(field).hasClass('swapped')) {
            $(field1).val("");
            $(field).removeClass('swapped');
            $(field1).prop('disabled', true);
        } else{
            $(field1).val("");
            $(field1).prop('disabled', true);
        }
    }
	}