$(document).ready(function () {
	showParticipantsList();

	$("#filterForm").submit(function () {
    showParticipantsList();
    return false;
  });

  $('#filterForm').on('keyup change paste', 'input, select, textarea', function(){
      showParticipantsList();
      return false;
  });

});

function showParticipantsList() {
    var type = $("#type").val();
    var subtype = $("#subtype").val();
    var added_date = $("#added_date").val();
    var country = $("#country").val();

    $('#attendanceTable').DataTable().clear().destroy();

    $('#attendanceTable').DataTable({
        // responsive: true,
        // order: [[1, 'desc']],
        'pageLength': 400,
        'dom': 'T<"clear">lfrtip',
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url':linkto,
            'data': {type:type,subtype:subtype,country:country,added_date:added_date,request:"fetchParticitants"}
        },
        'columns': [
            { data: 'id' },
            { data: 'firstname' },
            { data: 'pass_type' },
            { data: 'pass_sub_type' },
            { data: 'job_title' },
            { data: 'organisation_name' },
            { data: 'residence_country' },
            { data: 'location' },
            { data: 'added_time' }
        ],
        'oLanguage': {sProcessing: "<div class='loadingGifDiv'><div class='v2-loader'></div></div>"}
    });
}

// CSV Export
$(document).on("click", ".exportBtn", function () {
    $.ajax({
        type: "POST",
        url: linkto,
        data: {request: "exportAttendanceData"},
        success: function (data) {
          // Download the exported data
          if (data.trim() !== "") {
            downloadFile(data, "attendance_report.csv");
          } else {

          }
        },
    });
});

function downloadFile(data, filename) {
    var blob = new Blob([data], { type: "text/csv;charset=utf-8;" });
    if (navigator.msSaveBlob) {
      // For IE and Edge
      navigator.msSaveBlob(blob, filename);
    } else {
      var link = document.createElement("a");
      if (link.download !== undefined) {
          // For other browsers
          var url = URL.createObjectURL(blob);
          link.setAttribute("href", url);
          link.setAttribute("download", filename);
          link.style.visibility = "hidden";
          document.body.appendChild(link);
          link.click();
          document.body.removeChild(link);
      }
    }
}

function filterOptionsSubtype(type_input) {
  var type = $(type_input).val();
  $.ajax({
    type: "POST",
    url: linkto,
    data: {
      type: type,
      request: "filterParticipationSubType",
    },
    success: function (data) {
      $("#subtype").html(data);
    },
  });
}

