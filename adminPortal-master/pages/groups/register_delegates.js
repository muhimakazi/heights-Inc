$(document).ready(function () {
	showfetchGroupsList();



	//status
	$(document).on('click', '.delete_user', function () {
		var partId = $(this).data('id');
		var action = $(this).data('action');
		$.ajax({
			type: 'POST',
			url: linkto,
			data: { partId: partId, action: action, request: "deleteParticipant" },
			dataType: 'json',
			success: function (response) {
				if (response.success == true) {
					showfetchGroupsList();
				}
			}
		});
	});
});

function showfetchGroupsList() {
	$.ajax({
		type: 'POST',
		url: linkto,
		data: {type: "", subtype: "", request: "fetchGroupsSlotsList" },
		success: function (data) {
			$('#participants-table').html(data);
		}
	});
}

