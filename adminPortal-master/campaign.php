<?php
require_once 'core/init.php';
if (!$user->isLoggedIn()) {
    Redirect::to('login');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include'includes/head.php';?>
</head>

<body style="background: white; color: black;">
  
    <div class="service_area about_event"  id="register_area">
      <div class="container">
        <div class="row">
            <div class="col-lg-2"></div>
            <div class="col-lg-8">
              <form action="" method="post" role="form" class="register-form" id="registerForm">
                <label>All <span>*</span> fields are mandatory</label>
                <hr class="separator-line"> 
                <div class="mb-3">
                  <div class="loading">Loading</div>
                  <div class="error-message"></div>
                  <div class="sent-message">SUCCESS</div>
                </div>
                <div class="row">
                  
                </div>
                  
                <div class="text-right">
                  <button type="submit" id="registerButton" name="submit">Submit</button>
                </div>
              </form>
            </div>
            <div class="col-lg-12" style="background: #fff;">
<?php

if (isset($_POST['submit'])) {
  

  $_filter_condition_ = "";
  $_EVENT_ID_         = 23;
  $limit              = '';
  $_filter_condition_ .= " AND status = 'APPROVED'";
  $_filter_condition_ .= " AND urgent_status = 0";
  // $_filter_condition_ .= " AND issue_badge_status = 1";
  // $_filter_condition_ .= " AND participation_sub_type_id != 138 AND participation_sub_type_id != 139 AND participation_sub_type_id != 140";
  $_filter_condition_ .= " AND email != ''";

  // EmailCampaignController::processEmailQueue($_EVENT_ID_, $_filter_condition_, $limit);

  //PAYMENT
  // EmailCampaignController::processEmailQueuePaymentReminder($_EVENT_ID_, $_filter_condition_);

  // for ($i=0; $i <= 10; $i++) {
  //     echo $i.'<br>';
  //     sleep(6); 
  // }



  // CUSTOM CAMPAIGN
  $guests = DB::getInstance()->query("SELECT * FROM guest_list_bal_in_abidjan_csv WHERE email != '' AND urgent_status = 0");
  if (!$guests->count()) {
    Danger("No guest in the database");
  } else { 
    $count_ = 0;
    foreach ($guests->results() as $tguest) {
      $count_++;
      $guestID = $tguest->id;
      $firstname = $tguest->firstname;
      $lastname = $tguest->lastname;
      $email = $tguest->email;
      $_data_ = array(
        'email' => $email,
        'firstname' => $firstname,
      );
      // EmailControllerBAL::sendEmailToParticipantInvitation($_data_);
      // DB::getInstance()->query("UPDATE guest_list_bal_in_abidjan_csv SET urgent_status = 1 WHERE id = $guestID");
      // echo $count_.' '.$email . "<br>";
      // sleep(3);
    }
  }

}
?>
            </div>
          </div>
        </div>
      </div>
    <?php include 'includes/footer.php';?>

</body>

</html>