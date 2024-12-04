/*
 * Function: authValidation
 * Author: @Light-M
 * Parameter: Page (login, forgot-password, new-password)
 * Returns: 0 for invalid parameter, boolean for the operations
 * HTML Elements ID RULE: #username, #password, #new_password, #confirm_password
 */
function authValidation(page) {
  switch (page) {
    case "login":
      var username = document.getElementById("username").value.trim();
      var password = document.getElementById("password").value.trim();

      if (username !== "" && password !== "") {
        document.getElementById("username").style.border =
          "1px solid lightgrey";
        document.getElementById("password").style.border =
          "1px solid lightgrey";
        return true;
      } else {
        if (username === "")
          document.getElementById("username").style.border = "1px solid red";
        else
          document.getElementById("username").style.border =
            "1px solid lightgrey";
        if (password === "")
          document.getElementById("password").style.border = "1px solid red";
        else
          document.getElementById("password").style.border =
            "1px solid lightgrey";
      }

      return false;

      break;

    case "forgot-password":
      var username = document.getElementById("email_address").value.trim();

      if (username !== "" && getEmailRegex().test(username)) {
        document.getElementById("email_address").style.border =
          "1px solid lightgrey";
        return true;
      }
      document.getElementById("email_address").style.border = "1px solid red";

      return false;

      break;

    case "new-password":
      var password = document.getElementById("password").value.trim();
      var new_password = document.getElementById("new_password").value.trim();

      if (password !== "" && new_password !== "" && password === new_password) {
        return true;
      }

      return false;

      break;

    default:
      return 0;
      break;
  }
}
