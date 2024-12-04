$(document).ready(function() {
  /*
   * IMAGE UPLAD AND RESIZE - CROPPIE
   * REQUIRES THE CROPIE PLUGIN
   */
  var resize = $("#upload-demo").croppie({
    enableExif: true,
    enableOrientation: true,
    viewport: {
      // Default { width: 100, height: 100, type: 'square' }
      width: 145,
      height: 145,
      type: "square" //square
    },
    boundary: {
      width: 180,
      height: 180
    }
  });

  $("#image").on("change", function() {
    var reader = new FileReader();
    reader.onload = function(e) {
      resize
        .croppie("bind", {
          url: e.target.result
        })
        .then(function() {
          console.log("jQuery bind complete");
        });
    };
    reader.readAsDataURL(this.files[0]);
  });

  $(".btn-upload-image").on("click", function(ev) {
    resize
      .croppie("result", {
        type: "canvas",
        size: "viewport"
      })
      .then(function(img) {
        var processedImage = img.replace("data:image/png;base64,", "");

        console.log(processedImage);
        var html = '<img src="' + img + '" />';
        $("#preview-crop-image").html(html);

        if ($("#pic").length > 0) {
          document.querySelector("#pic").src = img;

          $("#isImageSelected").attr("value", "true");
          $("#edge").css("border", "8px solid #efefef");

          $("#imagebase64").attr("value", processedImage);
          $("#image_status").attr("value", "CHANGED");
        } else {
        }
      });
  });

  /**
   * WEBCAM IMAGE PROCESSING
   **/

  var video = document.querySelector("#video");

  function startCamera() {
    // Basic settings for the video to get from Webcam
    const constraints = {
      audio: false,
      video: {
        width: 175,
        height: 175
      }
    };

    // This condition will ask permission to user for Webcam access
    if (navigator.mediaDevices.getUserMedia) {
      navigator.mediaDevices
        .getUserMedia(constraints)
        .then(function(stream) {
          video.srcObject = stream;
        })
        .catch(function(err0r) {
          console.log("Something went wrong!");
        });
    }
  }

  function stop(e) {
    var stream = video.srcObject;
    var tracks = stream.getTracks();

    for (var i = 0; i < tracks.length; i++) {
      var track = tracks[i];
      track.stop();
    }
    video.srcObject = null;
  }

  var image_filename = "";

  // Below code to capture image from Video tag (Webcam streaming)
  $("#btnCapture").click(function() {
    $("#isImageSelected").attr("value", "true");
    $("#edge").css("border", "8px solid #efefef");

    var canvas = document.getElementById("canvas");

    var context = canvas.getContext("2d");

    var showProfile = document.getElementById("canva");
    var finImage = showProfile.getContext("2d");

    // Capture the image into canvas from Webcam streaming Video element
    context.drawImage(video, 0, 0);
    finImage.drawImage(video, 0, 0);

    var destinationCanvas = document.createElement("canvas");
    var destCtx = destinationCanvas.getContext("2d");

    // Final Image treatment
    destinationCanvas.height = 175;
    destinationCanvas.width = 175;

    destCtx.translate(video.videoWidth, 0);
    destCtx.scale(-1, 1);
    destCtx.drawImage(document.getElementById("canvas"), 0, 0);

    // Get base64 data to send to server for upload
    var imagebase64data = destinationCanvas.toDataURL("image/png");
    imagebase64data = imagebase64data.replace("data:image/png;base64,", "");
    console.log(imagebase64data);
    $("#imagebase64").attr("value", imagebase64data);
    $("#pic").attr("src", destinationCanvas.toDataURL("image/png"));
  });

  function uploadPic() {
    var image = $(".img-preview-custom img").attr("src");
    console.log("Image file base 64: " + image);
  }

  var readURL = function(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function(e) {
        $(".profile-pic").attr("src", e.target.result);
      };

      reader.readAsDataURL(input.files[0]);
    }
  };

  $(".file-upload").on("change", function() {
    readURL(this);
  });

  $(".upload-button").on("click", function() {
    console.log("Clicked");
    $(".file-upload").click();
  });
});
