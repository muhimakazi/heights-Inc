function disableInput(id, class_update, cursor) {
  $("#" + id).attr("disabled", "");
  $("#" + id).attr("class", class_update);
  $("#" + id).css("cursor", cursor);
  $("#" + id + "_req").html("");
}

function enableInput(id, class_update, cursor) {
  $("#" + id).removeAttr("disabled");
  $("#" + id).attr("class", class_update);
  $("#" + id).css("cursor", cursor);
  $("#" + id + "_req").html("*");
}

function printPageArea(elem) {
  var printArea = window.open("", "PRINT", "height=800,width=1200");

  printArea.document.write("<html><head><title>" + document.title + "</title>");
  // ADD ADDITIONAL CSS HERE
  printArea.document.write("");

  printArea.document.write("</head><body >");
  printArea.document.write(document.getElementById(elem).innerHTML);
  printArea.document.write("</body></html>");

  printArea.document.close(); // necessary for IE >= 10
  printArea.focus(); // necessary for IE >= 10*/

  // printArea.print();
  setTimeout(function() {
    printArea.print();
  }, 2000);
  // printArea.close();

  return true;
}

function getEmailRegex() {
  let regex = new RegExp("[a-z0-9]+@[a-z]+.[a-z]{2,3}");
  return regex;
}
