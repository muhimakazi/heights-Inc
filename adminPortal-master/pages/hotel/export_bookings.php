<?php

require_once "../../core/init.php"; 

if(!$user->isLoggedIn()) {
  Redirect::to('login');
} else {
  $delegates = array();

  ## Fetch records
  $_SQL_Condition_ = " AND future_participants.status != 'DENIED'";
  $delegate_data = DB::getInstance()->query("SELECT future_participants.*, hotel.name as hotel_name, hotel_room.room_type as room_type, hotel_room.room_price as room_price, hotel_room.currency as room_currency FROM future_participants INNER JOIN hotel ON future_participants.hotel_id = hotel.id INNER JOIN hotel_room ON future_participants.room_id = hotel_room.id WHERE future_participants.event_id = $eventId $_SQL_Condition_ ORDER BY future_participants.id DESC");

  if ($delegate_data->count()) {
    $i = 0;
    foreach ($delegate_data->results() as $tdelegate) {
      $telephone = $tdelegate->telephone;

      /** Handle Organization And Country */
      $participant_organization_ = $tdelegate->organisation_name;
      $participant_country_ = $tdelegate->residence_country;

      if (strlen($participant_country_) > 2) {
        $participant_country_ = $tdelegate->residence_country;
      } else{
        $participant_country_ = countryCodeToCountry($tdelegate->residence_country);
      }


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
      
      if($sub_type == 'Staff') $sub_type = 'Delegate';

      @$fullDetails= html_entity_decode($tdelegate->full_details);

      $number_of_guest = "";
      $check_in = "";
      $check_out = "";
      $message = "";

      $hotel = "";
    
      if(json_decode($fullDetails)!=null){
        $fullDetails=json_decode($fullDetails);
        $number_of_guest = property_exists($fullDetails, "number_of_guest")?$fullDetails->number_of_guest:"";
        $check_in = property_exists($fullDetails, "check_in")?$fullDetails->check_in:"";
        $check_out = property_exists($fullDetails, "check_out")?$fullDetails->check_out:"";
        $message = property_exists($fullDetails, "message")?$fullDetails->message:"";
      }

      $i++;
      $delegates[] = array(
        $i,
        $tdelegate->title,
        iconv('UTF-8', 'windows-1252', html_entity_decode($tdelegate->firstname, ENT_QUOTES, 'UTF-8')),
        iconv('UTF-8', 'windows-1252', html_entity_decode($tdelegate->lastname, ENT_QUOTES, 'UTF-8')),
        $tdelegate->email,
        $telephone,
        html_entity_decode($participant_organization_, ENT_QUOTES, 'UTF-8'),
        html_entity_decode($participant_country_, ENT_QUOTES, 'UTF-8'),
        $tdelegate->hotel_name,
        $tdelegate->room_type,
        $number_of_guest,
        date("j F Y", strtotime($check_in)),
        date("j F Y", strtotime($check_out)),
        iconv('UTF-8', 'windows-1252', html_entity_decode($message, ENT_QUOTES, 'UTF-8')),
        date("j F Y", strtotime($tdelegate->reg_date))
      );
    }
  }

  $filename = "TIME100_HOTEL_BOOKINGS_".date('Y-m-d H:i:s').".csv";

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
    'Country',
    'Hotel',
    'Type of room',
    'Number of guests',
    'Check-in',
    'Check-out',
    'Special request',
    'Date'
  ));

  if (count($delegates) > 0) {
      foreach ($delegates as $row) {
          fputcsv($output, $row);
      }
  }
}
?>