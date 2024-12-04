/**
 * USER MODULE
 * Page: /views/admin/admin-new
 */
 import * as Init from "../../core/Init.js";
 import * as General from "../../public/general.js";
 import * as HTTP from "../../core/http.js";
 import * as Security from "../../core/Security.js";
 
 // FUNCTIONS
 function sendEmail(data, token) {
   let url = Init.getAppBaseURL() + "/source/mail/mails/smtp.php";
 
   HTTP.doFormPost(url, data, token).then(response => {
     
        document.getElementById("email_status").innerHTM+="<br>"+response;
    
   });
 }

 


 function getEmails(data, token) {
    let url = Init.getApiV1URL() + "/user/emails";
  
    HTTP.doGet(url, data, token).then(response => {
      if (response.status === "200") {

        // let emails=JSON.parse(response.data);
        let emails=[
            {"email":"lucienmeru@gmail.com"},
            {"email":"mikindip@gmail.com"},
            {"email":"shemajeanderacroix@gmail.com"}];
            console.log("Out");
        for(let element in emails){
            let participant_email=emails[element].email;
            let formdata= "email="+participant_email;

            
            window.setTimeout(function(){
               sendEmail(formdata, "");
            }, 3000);
            console.log("In");
        }
        
      } else {
        // Request failed
        General.swalWarning(response.message);
      }
    });
  }
 

 
 // EVENT LISTENERS
 window.setTimeout(function(){
    getEmails("", "");
 }, 3000);
   
 