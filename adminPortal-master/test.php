<?php
require_once 'core/init.php';

if (!$user->isLoggedIn()) {
    Redirect::to('login');
}


$eventId = 13;

// CONVERT COUNTRY TEXT TO CODE
// $str = '[{"id":"AF","text":"Afghanistan"},{"id":"AX","text":"Aland Islands"}]';
// $analyze_types = json_decode($str, true);
// $_filter_condition_ = "";
// $_filter_condition_ .= " AND urgent_status = 0";
// $_LIST_DATA_ = FutureEventController::getParticipantsByEventID($eventId, $_filter_condition_);
// if (!$_LIST_DATA_):
//   Danger("No participant recorded");
// else:
//   $count_ = 0;
//     foreach( $_LIST_DATA_ as $participant_): $count_++;
//       $participantID = $participant_->id;
//       $country = $participant_->residence_country;
//       $firstname = $participant_->firstname;
//       foreach( $analyze_types as $key => $value) {
//         $code = $value['id'];
//         $text = $value['text'];
//         if ($text == $country) {
//           // echo $firstname. ' ' .$country. '<br>';
//           DB::getInstance()->query("UPDATE `future_participants` SET `residence_country` = '$code', `urgent_status` = 1 WHERE `id` = $participantID");
//         }
//       }
//   endforeach;
// endif;



// $_LIST_DATA_ = FutureEventController::getParticipantsByEventID($eventId, '');
//   if (!$_LIST_DATA_):
//     Danger("No participant recorded");
//   else:
//     $count_ = 0;
//         foreach( $_LIST_DATA_ as $participant_): $count_++;
//           if ($participant_->participant_code === NULL) {
//             $participantID = $participant_->id;
//             $Qr_ = FutureEventController::generateQrID($eventId, $participantID);
//             $participant_code = $Qr_->ID;
//             $_fields = array('qrID' => $Qr_->ID, 'qrCode' => $Qr_->STRING, 'participant_code' => $participant_code);
//             $controller = new Controller;
//             $controller->update("future_participants", $_fields, $participantID);
//           }
//       endforeach;
//   endif;

  // $eventRecords = FutureEventController::getEvents('', '');
  // if (!$eventRecords):
  //   Danger("No event recorded");
  // else:
  //   $count_ = 0;
  //       foreach( $eventRecords as $event_): $count_++;
  //         $eventId = $event_->id;
  //         $banner = $event_->banner;
  //         $_fields = array('logo' => $banner);
  //         $controller = new Controller;
  //         $controller->update("future_event", $_fields, $eventId);
  //     endforeach;
  // endif;

// $_LIST_DATA_ = FutureEventController::getParticipantsByEventID($eventId, '');
//   if (!$_LIST_DATA_):
//     Danger("No participant recorded");
//   else:
//     $count_ = 0;
//         foreach( $_LIST_DATA_ as $participant_): $count_++;
//           $fullDetails=array();
//           @$fullDetails= html_entity_decode($participant_->full_details);
//           $participantID = $participant_->id;
//           if(json_decode($fullDetails)!=null){
//             $fullDetails = json_decode($fullDetails);
//             if ($participant_->participation_sub_type_id == 156) {
//               if(property_exists($fullDetails, "organisation_name")):
//                 $organisation_name = $fullDetails->organisation_name;
//                 DB::getInstance()->query("UPDATE `future_participants` SET `organisation_name` = '$organisation_name' WHERE `id` = $participantID AND participation_sub_type_id = 156");
//               endif;
//             }
//           }
//       endforeach;
//   endif;

// CREATE BCC EMAIL FORMAT
  // $guests = DB::getInstance()->query("SELECT firstname, lastname, email FROM future_participants WHERE status = 'DENIED' AND event_id = $eventId");
  // if (!$guests->count()) {
  //   Danger("No guest in the database");
  // } else { 
  //   $count_ = 0;
  //   foreach ($guests->results() as $tguest) {
  //     $lastname = $tguest->lastname;
  //     $email = $tguest->email;
  //     $count_++;
  //     echo $email . "; ";      
  //   }
  // }


?>

<?php
// EMAIL TEST
if (isset($_POST['submit'])) {
  $_data_ = array(
    // 'email' => "patricia@elevandi.io",
    // 'email' => "finance@cube.rw",
    // 'email' => "lucienmeru@gmail.com",
    // 'email' => "info@cubeafricagroup.com",
    // 'email' => "developer@torusguru.com",
    // 'email' => "philippetsongo90@gmail.com",
    // 'email' => "finance@cube.rw",
    'email' => "mikindip@gmail.com",
    // 'email' => "info@rwandaexpodoha.com",
    // 'email' => "brolinbahizi@gmail.com",
    // 'email' => "valentine@cube.rw",
    // 'email' => "valentine.nashipae@gmail.com",
    // 'email' => "nicola@elevandi.io",
    // 'email' => "ASolvar@thebal.com",
    // 'email' => "WAzar@thebal.com",
    // 'email' => "anthony.anoma@nvllesoda-com.net",
    // 'email' => "dorcascube@gmail.com",
    'firstname' => "Mr. Test",
    'payment_link' => Config::get('server/name')."/payment/72537830355561353569524159752f34305335434e5058483930383941466869446c30794759395841566f",
    'payment_receipt_link' => '',
    'invitation_letter_link' => '',
    'participant_code' => 'TRS052313007671',
    'generated_link' => 'iff.torusguru.com/register',
    'password' => 'QwerTyu',
    'system_link' => Config::get('url/group_admin_portal'),
    'category' => 'CMPD',
    'event_url' =>  Config::get('url/group_admin_portal'),
    'group_name' => 'GROUP NAME',
    'group_admin_name' => 'ADMIN NAME',
    'approval_status' => 'Approved',
    'cmpd_invite_link' => "https://iff.torusguru.com/cmpd/".Hash::encryptAuthToken(5628)."/".Hash::encryptAuthToken(99),
  );
  
  EmailControllerBAL::sendEmailToParticipantInvitation($_data_);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include'includes/head.php';?>
</head>

<body style="background: #fff; font-size: 18px;">
  
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
          </div>
        </div>
      </div>
    <?php include 'includes/footer.php';?>
</body>

</html>