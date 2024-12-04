import * as General from "../public/general.js";

export function doPost(url, data, token = "") {
  return new Promise((resolve, reject) => {
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.open("POST", url, false);
    xmlhttp.setRequestHeader("Content-type", "application/json");
    xmlhttp.setRequestHeader("Authorization", token);

    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        let responseData = JSON.parse(this.responseText);
        resolve(responseData);
      } else {
        let errorObj = {
          status: this.status,
          message: this.responseText
        };

        reject(errorObj);
      }
    };

    xmlhttp.send(data);
  });
}

export function doFormPost(url, data, token = "") {
  return new Promise((resolve, reject) => {
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.open("POST", url, false);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.setRequestHeader("Authorization", token);

    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        let responseData = JSON.parse(this.responseText);
        resolve(responseData);
      } else {
        let errorObj = {
          status: this.status,
          message: this.responseText
        };

        reject(errorObj);
      }
    };

    xmlhttp.send(data);
  });
}

export function doGet(url, data, token = "") {
  return new Promise((resolve, reject) => {
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", url, false);
    xmlhttp.setRequestHeader("Authorization", token);

    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        let responseData = JSON.parse(this.responseText);

        resolve(responseData);
      } else {
        let errorObj = {
          status: this.status,
          message: this.responseText
        };

        reject(errorObj);
      }
    };

    xmlhttp.send(data);
  });
}

export function postForm(url, params, method) {
  method = method || "post";

  var form = document.createElement("form");
  form.setAttribute("method", method);
  form.setAttribute("action", path);

  for (var key in params) {
    if (params.hasOwnProperty(key)) {
      var hiddenField = document.createElement("input");
      hiddenField.setAttribute("type", "hidden");
      hiddenField.setAttribute("name", key);
      hiddenField.setAttribute("value", params[key]);

      form.appendChild(hiddenField);
    }
  }

  document.body.appendChild(form);
  form.submit();
}
