<?php

require_once "../../core/init.php";


if (!$user->isLoggedIn()) {
  Redirect::to('login');
}
else {
  $eventId = base64_decode(Input::get('eventId'));

  $delegates = array();
  $delegate_data = DB::getInstance()->query("SELECT * FROM `future_participants` WHERE `event_id` = $eventId");
  if ($delegate_data->count()) {
    $i = 0;
    foreach ($delegate_data->results() as $tdelegate) {
      $i++;
      $countryCode = "254";
      if (array_key_exists($tdelegate->organisation_country, $countryArray)) {
        $countryCode = $countryArray[$tdelegate->organisation_country]['code'];
      }
      $telephone = $tdelegate->telephone;
      if (substr($telephone, 0, 1) != '+') {
        $telephone = "+" . $countryCode . $tdelegate->telephone;
      }
      else {
        $telephone = $tdelegate->telephone;
      }

      $participation_sub_type_id = $tdelegate->participation_sub_type_id;
      $getEventParticipation = DB::getInstance()->get('future_participation_sub_type', array('id', '=', $participation_sub_type_id));
      $eventParticipation = $getEventParticipation->first()->category;

      $delegates[] = array(
        $i,
        $tdelegate->firstname,
        $tdelegate->lastname,
        $tdelegate->email,
        $telephone,
        $tdelegate->organisation_name,
        $tdelegate->job_title,
        $tdelegate->organisation_city,
        countryCodeToCountry($tdelegate->organisation_country),
        $eventParticipation,
        $tdelegate->website,
        $tdelegate->status,
        $tdelegate->reg_date
      );
    }
  }

  $filename = "website_delegate_data_" . date('Y-m-d H:i:s') . ".csv";

  header('Content-Type: text/csv; charset=utf-8');
  header("Content-Disposition: attachment; filename=\"$filename\"");
  $output = fopen('php://output', 'w');
  fputcsv($output, array(
    'No',
    'First Name',
    'Last Name',
    'Email',
    'Telephone',
    'Organisation Name',
    'Job title',
    'City',
    'Country',
    'Attendance',
    'Website Address',
    'Status',
    'Date & Time'
  ));

  if (count($delegates) > 0) {
    foreach ($delegates as $row) {
      fputcsv($output, $row);
    }
  }
}
?>