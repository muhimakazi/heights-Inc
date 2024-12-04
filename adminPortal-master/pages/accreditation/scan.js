$(document).ready(function () {
	// showParticipantsList();

	var barcode = "";
        var interval = "";
        document.addEventListener('keydown', function(evt) {
        if (interval)
            clearInterval(interval);

        if (evt.code == 'Enter') {
            if (barcode)
                showParticipantDetails(barcode);
            barcode = '';
            return;
        }
        if (evt.key != 'Shift')
            barcode += evt.key;

        interval = setInterval(() => barcode = '', 20);
    });

});

function showParticipantDetails(scanned_qrcode) {
	var participantToken = scanned_qrcode;
	var this_form = $("#badgeCard");
	// document.querySelector('#last-barcode').innerHTML = scanned_qrcode;
	$.ajax({
		type: 'POST',
		url: linkto,
		data: {request:"issueBadge", participantToken:participantToken},
		success: function (dataResponse) {
            var response = JSON.parse(dataResponse);
            if (response.success == true) {
            	$("#partName").text(response.data.name);
            	$("#orgName").text(response.data.organisation);
            	$("#country").text(response.data.country);
            	$("#pass_type").text(response.data.pass);
            	$("#del_status").text(response.data.status);
            	if (response.data.status == 'APPROVED') {
            		$("#del_div").css('background-color', 'green');
            	} else {
            		$("#del_div").css('background-color', 'red');
            	}

              	$("html, body, div.card").animate({ scrollTop: "0" }, 100);
              	$('#scan-messages').html('<div class="sent-message">'+ response.messages + '</div>');
	          	this_form.find('.sent-message').slideDown().html(response.messages);
				$(this).delay(500).show(10, function() {
					// setTimeout(function(){
						$(".sent-message").hide();
					// }, 3000);
				});
              
            } else {
              $(
                "html, body, div.card"
              ).animate({ scrollTop: "0" }, 100);
              $("#scan-messages").html(
                '<div class="error-message">' + response.messages + "</div>"
              );
              this_form
                .find(".error-message")
                .slideDown()
                .html(response.messages);
              $(".error-message")
                .delay(500)
                .show(10, function () {});
            }
         },
	});
}

// function showParticipantDetails (scanned_qrcode) {
//     document.querySelector('#last-barcode').innerHTML = scanned_qrcode;
// }