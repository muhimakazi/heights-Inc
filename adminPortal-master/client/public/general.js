function fadeIn(element) {
  var op = 0.1;
  element.style.display = "block";
  var timer = setInterval(function() {
    if (op >= 1) {
      clearInterval(timer);
    }
    element.style.opacity = op;
    element.style.filter = "alpha(opacity=" + op * 100 + ")";
    op += op * 0.1;
  }, 50);
}

function fadeOut(element) {
  var op = 1;
  var timer = setInterval(function() {
    if (op <= 0.1) {
      clearInterval(timer);
      element.style.display = "none";
    }
    element.style.opacity = op;
    element.style.filter = "alpha(opacity=" + op * 100 + ")";
    op -= op * 0.1;
  }, 50);
}

export function load() {
  document.getElementbyId("load").removeAttribute("hidden");
}

export function stopLoad(delay) {
  if (parseInt(delay) > 0) {
    window.setTimeout(function() {
      document.getElementbyId("load").setAttribute("hidden", "");
    }, delay);
  } else {
    document.getElementbyId("load").setAttribute("hidden", "");
  }
}

export function authLoad() {
  document.getElementById("pending-message").removeAttribute("hidden");
  fadeIn(document.getElementById("pending-message"));
}

export function stopAuthLoad(delay) {
  if (parseInt(delay) > 0) {
    window.setTimeout(function() {
      fadeOut(document.getElementById("pending-message"));
    }, delay);
  } else {
    fadeOut(document.getElementById("pending-message"));
  }
}

export function showAuthSuccess() {
  document.getElementById("success-message").removeAttribute("hidden");
  fadeIn(document.getElementById("success-message"));
}
export function hideAuthSuccess() {
  fadeOut(document.getElementById("success-message"));
}

export function showAuthFailure() {
  document.getElementById("failure-message").removeAttribute("hidden");
  fadeIn(document.getElementById("failure-message"));
}
export function hideAuthFailure() {
  fadeOut(document.getElementById("failure-message"));
}

export function showNotValidated(delay) {
  if (parseInt(delay) > 0) {
    window.setTimeout(function() {
      document
        .getElementById("not-validated-message")
        .removeAttribute("hidden");
      fadeIn(document.getElementById("not-validated-message"));
    }, delay);
  } else {
    document.getElementById("not-validated-message").removeAttribute("hidden");
    fadeIn(document.getElementById("not-validated-message"));
  }
}
export function hideNotValidated() {
  fadeOut(document.getElementById("not-validated-message"));
}

export function hideAllAuthMessage() {
  hideNotValidated();
  stopAuthLoad("0");
  hideAuthSuccess("0");
  hideAuthFailure("0");
}

export function swalSuccess(message) {
  swal.fire({
    title: "Success",
    text: message,
    type: "success",
    icon: "success",
    showCancelButton: false,
    confirmButtonClass: "btn-info",
    confirmButtonText: "OK",
    closeOnConfirm: true
  });
}

export function swalWarning(message) {
  swal.fire({
    title: "Failure",
    text: message,
    icon: "warning",
    type: "warning",
    showCancelButton: false,
    confirmButtonClass: "btn-info",
    confirmButtonText: "Retry",
    closeOnConfirm: true
  });
}

export function startLoader() {
  $("#load").removeAttr("hidden");
}

export function stopLoader(delay) {
  if (parseInt(delay) > 0) {
    window.setTimeout(function() {
      $("#load").attr("hidden", "");
    }, delay);
  } else {
    $("#load").attr("hidden", "");
  }
}

export function getSimpleTextEncoding(text) {
  let message = text;
  let enc = new TextEncoder();
  return enc.encode(message);
}

export function disableInput(id, class_update, cursor) {
  $("#" + id).attr("disabled", "");
  $("#" + id).attr("class", class_update);
  $("#" + id).css("cursor", cursor);
  $("#" + id + "_req").html("");
}

export function enableInput(id, class_update, cursor) {
  $("#" + id).removeAttr("disabled");
  $("#" + id).attr("class", class_update);
  $("#" + id).css("cursor", cursor);
  $("#" + id + "_req").html("*");
}

function toBase64(event, elt, target_elt) {
  // Displaying the image
  var image = document.getElementById(elt);
  image.src = URL.createObjectURL(event.target.files[0]);

  // converting the image to base64 and initializing the input
  var fileReader = new FileReader();

  fileReader.addEventListener("load", function(e) {
    var base64 = e.target.result;
    var imageBase64 = base64.replace("data:image/jpeg;base64,", "");
    imageBase64 = imageBase64.replace("data:image/png;base64,", "");
    imageBase64 = imageBase64.replace("data:image/jpg;base64,", "");
    imageBase64 = imageBase64.replace("data:application/pdf;base64,", "");

    document.getElementById(target_elt).value = imageBase64;
  });

  fileReader.readAsDataURL(event.target.files[0]);
}

export function frenchDateFormat(date_en) {
  var new_date = new Date(date_en);

  var datestring =
    ("0" + new_date.getDate()).slice(-2) +
    "/" +
    ("0" + (new_date.getMonth() + 1)).slice(-2) +
    "/" +
    new_date.getFullYear();

  return datestring;
}

export function redirectTo(url, timer = 0) {
  window.setTimeout(function() {
    window.location = url;
  }, timer);
}

export function getMonthText(month) {
  switch (parseInt(month)) {
    case 1:
      return "Janvier";
      break;

    case 2:
      return "Février";
      break;

    case 3:
      return "Mars";
      break;

    case 4:
      return "Avril";
      break;

    case 5:
      return "Mai";
      break;

    case 6:
      return "Juin";
      break;

    case 7:
      return "Juillet";
      break;

    case 8:
      return "Aout";
      break;

    case 9:
      return "Septembre";
      break;

    case 10:
      return "Octobre";
      break;

    case 11:
      return "Novembre";
      break;

    case 12:
      return "Decembre";
      break;

    default:
      return "";
      break;
  }
}

export function printPageArea(elem) {
  var printArea = window.open("", "PRINT", "height=720,width=1200");

  printArea.document.write("<title>" + document.title + "</title>");
  printArea.document.write('<link href="css/style.css?v=3" rel="stylesheet">');
  // ADD ADDITIONAL CSS HERE
  printArea.document.write("<style>@page {size: auto;margin: 0; padding:0;} li{text-decoration:none;} .non-printable{ display: none; }</style>");
  // printArea.document.write("<style>.card-profile-img { /*max-width: 10rem;*/ margin-bottom: 1rem; /*border-radius: 100%;*/ width: 150px !important; max-height: 200px!important; border: 0px solid #fff; /*margin: auto;*/ text-align: center; margin-top: -70px; } </style>");
  printArea.document.write("<style>body { font-family: ubuntu-light; background-color: #c2d4d79e; font-size: 13px; overflow-x: hidden; } .card-container { box-shadow: 0px 0px 0px 0px #f1f1f1; } .p-category { margin: auto; text-align: center; background: #37af47; bottom: 0; position: absolute; width: 100%; } /*MC*/ .card_profile_container { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); } .card { background-color: #fff; border: 0 solid #eee; border-radius: 0; overflow: auto; } .card { margin-bottom: 30px; -webkit-box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.1), -1px 0 2px rgba(0, 0, 0, 0.05); box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.1), -1px 0 2px rgba(0, 0, 0, 0.05); } .card-profile .card-header { height: 43rem; background-size: cover!important; background-position: center center!important; } .card-header { min-height: 200px; padding-top: 20% !important; padding-bottom: 20% !important; /*background-position: contain !important;*/} .card-profile img { width: 100%; } .partners img { margin-bottom: 30px; } .header-text h1 { color: #fff !important; font-family: gotham-bold !important; font-size: 76px !important; margin: 0 !important; margin-bottom: -10% !important; } .header-text h4 { color: #fff !important; font-family: DIN-Alternate-Light!important; font-size: 26px !important; margin: 0 !important; font-weight: 700 !important; } .header-text p { color: #fff !important; font-family: DIN-Alternate-Light!important; font-size: 18px !important; margin-top: 3% !important; font-weight: 700 !important; }</style>");

  printArea.document.write("<style>@media print {.card-header { page-break-before: always!important; } .card-body { break-after: avoid!important; } /* page-break-after works, as well */}</style>");

  printArea.document.write('<link href="css/bootstrap.min.css" rel="stylesheet">');
  // printArea.document.write('<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">');
  
  // printArea.document.write('<link rel="stylesheet" href="../css/custom.css">'); 
  // printArea.document.write('<script src="../js/jquery-2.1.1.js"></script>');
  // printArea.document.write('<script src="../js/bootstrap.min.js"></script>');
  // printArea.document.write('<link href="../font-awesome/css/font-awesome.css" rel="stylesheet">');
  // printArea.document.write('<link rel="stylesheet" href="../css/plugins/chosen/chosen.css"/>');

  document.getElementById("contain").setAttribute("style", "height: 100vh!important;");

  printArea.document.write(document.getElementById(elem).innerHTML);

  printArea.document.close(); // necessary for IE >= 10
  printArea.focus(); // necessary for IE >= 10*/

  // printArea.print();
  setTimeout(function() {
    printArea.print();
    printArea.close();
    
  }, 3000);


  return true;
}
