/**
 * USER MODULE
 * Page: /views/admin/admin-new
 */
import * as Init from "../../core/Init.js";
import * as General from "../../public/general.js";
import * as HTTP from "../../core/http.js";
import * as Security from "../../core/Security.js";

// FUNCTIONS
function getReportData(data, token) {
  let url = Init.getApiV1URL() + "/report/figures";

  HTTP.doPost(url, data, token).then(response => {
    if (response.status === "200") {
      
      // Displaying values
      getRegistrationDetails(response.data.registration_details);

    } else {
      // Request failed
      General.swalWarning(response.message);
    }
  });
}


function getRegistrationDetails(data){
  let complementary=data.complementary;
  let paying=data.paying;
  let total=data.total;

  document.getElementById("complementary").innerHTML=complementary;
  document.getElementById("paying").innerHTML=paying;
  document.getElementById("total_registration").innerHTML=total;

}



// EVENT LISTENERS
$(document).ready(function() {
  let authToken = document.getElementById("auth_token").value.trim();
  let event_id= document.getElementById("event_id").value.trim();

  let reqParams={};

  reqParams.registration="true";
  reqParams.attendance="true";
  reqParams.payment="true";
  reqParams.revenue="true";
  reqParams.delegate="true";
  reqParams.refund="false";
  reqParams.geographical="false";
  reqParams.event_id=event_id;

  authToken="Bearer "+authToken;

  let data=JSON.stringify(reqParams);


  getReportData(data, authToken);
});
