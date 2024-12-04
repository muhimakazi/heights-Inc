$(document).ready(function(){
	showPartnershipList();
});

function showPartnershipList(){
	$.ajax({
		type: 'POST',
		url: linkto,
		data: {
			fetchParticitants: 1
		},
		success:function(data){
			$('#partnership-table').html(data);
		}
	});
}