$(document).ready(function () {

  fetchAttendanceByCountry()

  showRegistrationByCountry();

  showRegistrationByDay();

  $("#filterForm").submit(function () {
    var this_form = $("#filterForm");
    var str = $(this_form).serialize();
    $.ajax({
      type: "POST",
      url: linkto,
      data: str,
      success: function (data) {
        $("#country-data").html(data);
      },
    });
    return false;
  });

});

function showRegistrationByDay() {
  $.ajax({
    type: "POST",
    url: linkto,
    data: {
      type: "",
      subtype: "",
      request: "fetchRegistrationByDay",
    },
    success: function (data) {
      $("#participants-table").html(data);
    },
  });
}

function showRegistrationByCountry() {
  $.ajax({
    type: "POST",
    url: linkto,
    data: {
      request: "fetchRegistrationByCountry",
    },
    success: function (data) {
      $("#country-data").html(data);
    },
  });
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

function fetchAttendanceByCountry() {
  $.ajax({
    type: "POST",
    url: linkto,
    data: {
      type: "",
      subtype: "",
      request: "fetchAttendanceByCountry",
    },
    success: function (data) {
      $("#attendance-table").html(data);
    },
  });
}
