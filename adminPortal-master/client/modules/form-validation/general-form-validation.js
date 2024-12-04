function basicValidation() {
  var isValidated = "true";

  $(".form_element").each(function() {
    if ($(this).val() === "") {
      isValidated = "false";

      $(this)
        .next(".error_message")
        .html("Ce champ est requis");
    } else {
      $(this)
        .next(".error_message")
        .html(" ");
    }
  });

  if (isValidated === "true") {
    return true;
  } else {
    return false;
  }
}

function advancedValidation(ids) {
  var isValidated = "true";

  for (var i = 0; i < ids.length; i++) {
    var element = document.getElementById(ids[i]).value.trim();

    if (element === "") {
      isValidated = "false";

      var error_elt = ids[i] + "_error";

      $("#" + error_elt).html("Ce champ est requis");
    }
  }

  if (isValidated === "true") {
    return true;
  } else {
    return false;
  }
}

function Remove(id) {
  // Reinitializing values and disabling removed inputs
  $("#" + id).attr("class", "disabled");

  // Hiding the removed input
  $("#" + id).hide("slow");
  $("#" + id).attr("hidden", "");

  // Update page status here
}
