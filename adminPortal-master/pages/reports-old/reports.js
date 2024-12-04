$(document).ready(function () {
	showParticipantsList();

	$('#filterForm').submit(function () {
		var type = $('#type').val();
		var subtype = $('#subtype').val();
		var status = $('#status').val();
		// var payment_channel = $('#payment_channel').val();
		// var datefrom = $('#datefrom').val();
		// var dateto = $('#dateto').val();

		var this_form = $("#filterForm");
		var str = $(this_form).serialize();

		$.ajax({
			type: 'POST',
			url: linkto,
			data: str,
			success: function (data) {
				$('#participants-table').html(data);
			}
		});
		return false;
	});
	
});

function showParticipantsList() {
	$.ajax({
		type: 'POST',
		url: linkto,
		data: { eventId: eventId, type: "", subtype: "", participationTypeToken: participationTypeToken, request: "fetchParticitants" },
		success: function (data) {
			$('#participants-table').html(data);
		}
	});
}


function filterOptionsSubtype(type_input) {
	var type = $(type_input).val();
	$.ajax({
		type: 'POST',
		url: linkto,
		data: { eventId: eventId, type: type, request: "filterParticipationSubType" },
		success: function (data) {
			$('#subtype').html(data);
		}
	});
}

function getActiveRegions() {
	
	$.ajax({
		type: 'POST',
		url: linkto,
		data: { eventId: eventId, request: "fetchMapData"},
		success: function (data) {
			mapData=JSON.parse(data);
			
			$("#map-regions").html(JSON.stringify(mapData));
		}
	});
}