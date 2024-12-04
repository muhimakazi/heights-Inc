<?php

require_once "../../core/init.php"; 

if(!$user->isLoggedIn()) {
  Redirect::to('login');
} else {
  $eventId = Hash::decryptToken(Input::get('eventId'));

  $delegates = array();

  // ALL
  // $delegate_data = DB::getInstance()->query("SELECT * FROM `future_participants` WHERE `event_id` = $eventId AND `status` != 'DENIED' AND `status` != 'REFUNDED'");

  // ACCOMODATION
  // $delegate_data = DB::getInstance()->query("SELECT * FROM `future_participants` WHERE `event_id` = $eventId AND `need_accommodation_state` = 1 AND (`status` = 'PENDING' || `status` = 'APPROVED')");

  // APPROVED
  $delegate_data = DB::getInstance()->query("SELECT * FROM `future_participants` WHERE `event_id` = $eventId AND `status` = 'APPROVED'");

  //SPEAKERS
  // $delegate_data = DB::getInstance()->query("SELECT * FROM `future_participants` WHERE `event_id` = $eventId AND `participation_sub_type_id` = 119 OR `participation_sub_type_id` = 128 ");

  //CMPD
  // $delegate_data = DB::getInstance()->query("SELECT * FROM `future_participants` WHERE `event_id` = $eventId AND `participation_type_id` = 39 AND `invitation_status` != 'ACCEPTED'");

  // $delegate_data = DB::getInstance()->query("SELECT future_participants.*, future_participants.id as participant_ID, future_participation_type.name as participation_type_name, future_participation_type.code as participation_type_code, future_participation_sub_type.name as participation_subtype_name , future_participation_sub_type.payment_state, future_participation_sub_type.category as participation_subtype_category, future_participation_sub_type.price as participation_subtype_price, future_participation_sub_type.currency as participation_subtype_currency FROM `future_participants` INNER JOIN future_participation_type ON future_participants.participation_type_id = future_participation_type.id INNER JOIN future_participation_sub_type ON future_participants.participation_sub_type_id = future_participation_sub_type.id WHERE future_participants.event_id = {$eventId} AND future_participation_sub_type.payment_state = 'PAYABLE' AND future_participants.status = 'PENDING' ORDER BY future_participants.id DESC ");

  //VIPs
  // $delegate_data = DB::getInstance()->query("SELECT future_participants.* FROM `future_participants` INNER JOIN future_participation_type ON future_participants.participation_type_id = future_participation_type.id INNER JOIN future_participation_sub_type ON future_participants.participation_sub_type_id = future_participation_sub_type.id WHERE future_participants.event_id = {$eventId} AND future_participation_sub_type.name = 'VIP' ORDER BY future_participants.id DESC ");

  if ($delegate_data->count()) {
    $i = 0;
    foreach ($delegate_data->results() as $tdelegate) {
      // $participant_id = $tdelegate->id;
      // if ($participant_id == 2115 || $participant_id == 2511 || $participant_id == 1291 || $participant_id == 2261 || $participant_id == 2295 || $participant_id == 2047 || $participant_id == 1927 || $participant_id == 2155 || $participant_id == 2384 || $participant_id == 2080 || $participant_id == 2280 || $participant_id == 2350 || $participant_id == 1983 || $participant_id == 2243 || $participant_id == 2491 || $participant_id == 2407 || $participant_id == 2435 || $participant_id == 2094 || $participant_id == 2400 || $participant_id == 2148 || $participant_id == 2559 || $participant_id == 2522 || $participant_id == 2588 || $participant_id == 2560 || $participant_id == 1861 || $participant_id == 1845 || $participant_id == 2785 || $participant_id == 2734 || $participant_id == 1622 || $participant_id == 2326 || $participant_id == 1934 || $participant_id == 1928 || $participant_id == 2693 || $participant_id == 2273 || $participant_id == 2154) {

      // $countryCode = "254";
      // if (array_key_exists($tdelegate->organisation_country, $countryArray)) {
      //   $countryCode = $countryArray[$tdelegate->organisation_country]['code'];
      // }
      // $telephone = $tdelegate->telephone;
      // if (substr($telephone, 0, 1) != '+') {
      //     $telephone = "+".$countryCode.$tdelegate->telephone;
      // } else {
      //   $telephone = $tdelegate->telephone;
      // }

      $telephone = $tdelegate->telephone;

      /** Handle Organization And Country */
      $participant_organization_ = $tdelegate->organisation_name;
      $participant_country_ = $tdelegate->residence_country;
      $organisation_type = $tdelegate->organisation_type;
      $citizenship = $tdelegate->citizenship;

      if (strlen($participant_country_) > 2) {
        $participant_country_ = $tdelegate->residence_country;
      } else{
        $participant_country_ = countryCodeToCountry($tdelegate->residence_country);
      }


      // if(@$tdelegate->student_state == 1 ):
      //   $participant_country_  = countryCodeToCountry($tdelegate->educacation_institute_country);
      //   $participant_organization_  = $tdelegate->educacation_institute_name;

      // endif;


      /** Handle Sub Type */
      $participation_sub_type_id = $tdelegate->participation_sub_type_id;
      $getEventParticipation = DB::getInstance()->get('future_participation_sub_type', array('id', '=', $participation_sub_type_id));
      $eventParticipation = $getEventParticipation->first()->category;
      $sub_type = $getEventParticipation->first()->name;

      /** Handle Sub Type */
      $participation_type_id = $tdelegate->participation_type_id;
      $getEventParticipationType = DB::getInstance()->get('future_participation_type', array('id', '=', $participation_type_id));
      
      $type = $getEventParticipationType->first()->name;

      if ($tdelegate->profile != '') {
        $profile = 'https://admin.torusguru.com/del/pic/'.$tdelegate->profile;
      } else {
        $profile = '';
      }

      @$fullDetails= html_entity_decode($tdelegate->full_details);

      $arrival_date = "";
      $flight_number_arrival = "";
      $departure_date = "";
      $flight_number_departure = "";

      $hotel = "";
    
      if(json_decode($fullDetails)!=null){
          
        $fullDetails=json_decode($fullDetails);

        $arrival_date = property_exists($fullDetails, "arrival_date")?date("j F Y", strtotime($fullDetails->arrival_date)):"";
        $arrival_time = property_exists($fullDetails, "arrival_time")?$fullDetails->arrival_time:"";
        $arrival_airline = property_exists($fullDetails, "arrival_airline")?$fullDetails->arrival_airline:"";
        $hotel_name = property_exists($fullDetails, "hotel_name")?$fullDetails->hotel_name:"";
        $hotel_name1 = property_exists($fullDetails, "hotel_name1")?$fullDetails->hotel_name1:"";
        $departure_date = property_exists($fullDetails, "departure_date")?date("j F Y", strtotime($fullDetails->departure_date)):"";
        $departure_time = property_exists($fullDetails, "arrival_time")?$fullDetails->arrival_time:"";
        $departure_airline = property_exists($fullDetails, "departure_airline")?$fullDetails->departure_airline:"";
        $telephone = property_exists($fullDetails, "telephone_full")?$fullDetails->telephone_full:"";

        if ($hotel_name1 == "Other") {
          $hotel = $hotel_name;
        } else {
          $hotel = $hotel_name1;
        }

      }

      //INDUSTRY
      if ($participation_type_id == 36 || $participation_type_id == 38 || $participation_sub_type_id == 106 || $participation_sub_type_id == 106 
        || $participation_sub_type_id == 116 || $participation_sub_type_id == 117 || $participation_sub_type_id == 118)
      {
          $cat_name  = 'Industry Pass';
      }

      //FINTECH
      elseif ($participation_type_id == 37 || $participation_sub_type_id == 107 || $participation_sub_type_id == 120)
      {
          $cat_name  = 'FinTech Pass';
          $cat_color = '#820364';
      } 

      //VIP
      elseif ($participation_sub_type_id == 110 || $participation_sub_type_id == 119 || $participation_sub_type_id == 128 || $participation_sub_type_id == 137)
      {
          $cat_name  = 'VIP';
      }

      //CMPD-INDUSTRY
      elseif ($participation_sub_type_id == 99 || $participation_sub_type_id == 114)
      {
          $cat_name  = 'Industry Pass - CMPD';
      }

      //CMPD-FINTECH
      elseif ($participation_sub_type_id == 100)
      {
          $cat_name  = 'FinTech Pass - CMPD';
      }

      //CMPD VIP
      elseif ($participation_sub_type_id == 101 || $_PART_SUB_TYPE_ID_ == 102)
      {
          $cat_bg = "#fff!important;";
          $cat_name  = 'VIP - CMPD';
      }

      //ORGANISER
      elseif ($participation_sub_type_id == 115)
      {
          $cat_name  = 'Organiser';
      }

      //MEDIA
      elseif ($participation_type_id == 40 || $participation_sub_type_id == 103)
      {
          $cat_name  = 'Media';
      }

      //CAREERS FORUM
      elseif ($participation_type_id == 47 || $participation_type_id == 46)
      {
          $cat_name  = 'Careers Forum';
      }

      //CREW - PROTOCOL
      elseif ($participation_sub_type_id == 111 || $participation_sub_type_id == 135)
      {
          $cat_name  = 'Crew';
      } else {
          $cat_name  = 'Crew';
      }

        $i++;
        $delegates[] = array(
          $i,
          $tdelegate->title,
          iconv('UTF-8', 'windows-1252', html_entity_decode($tdelegate->firstname, ENT_QUOTES, 'UTF-8')),
          iconv('UTF-8', 'windows-1252', html_entity_decode($tdelegate->lastname, ENT_QUOTES, 'UTF-8')),
          $tdelegate->email,
          $tdelegate->telephone,
          html_entity_decode($participant_organization_, ENT_QUOTES, 'UTF-8'),
          html_entity_decode($organisation_type, ENT_QUOTES, 'UTF-8'),
          html_entity_decode($tdelegate->job_title, ENT_QUOTES, 'UTF-8'),
          // html_entity_decode($tdelegate->job_category, ENT_QUOTES, 'UTF-8'),
          html_entity_decode($participant_country_, ENT_QUOTES, 'UTF-8'),
          $cat_name,
          // $sub_type,

          $citizenship,

          $arrival_date,
          $arrival_time,
          $arrival_airline,
          $hotel,
          $departure_date,
          $departure_time,
          $departure_airline,


          date("j F Y", strtotime($tdelegate->reg_date)),
          $profile,
          // $tdelegate->status,
        );
    }
  }
// }

  $filename = "IFF_DELEGATES_".date('Y-m-d H:i:s').".csv";

  $output = fopen('php://output', 'w');
  header('Content-Type: text/csv; charset=utf-8');
  header("Content-Disposition: attachment; filename=\"$filename\"");
  fputcsv($output, array(
    'No',
    'Title',
    'First Name',
    'Last Name',
    'Email',
    'Telephone',
    'Organisation Name',
    'Organisation Type',
    'Job Title',
    // 'Job Category',
    'Country',
    'Pass Type',
    // 'Pass Sub Type',

    'Citizenship',

    'Arrival date',
    'Arrival time',
    'Arrival airline',
    'Hotel name',
    'Departure date',
    'Departure time',
    'Departure airline',

    'Date',
    'Profile',
    // 'Status'
  ));

  if (count($delegates) > 0) {
      foreach ($delegates as $row) {
          fputcsv($output, $row);
      }
  }
}
?>