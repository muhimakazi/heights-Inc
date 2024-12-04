<?php
    require_once "../../core/init.php"; 
    if(!$user->isLoggedIn()) {
        Redirect::to('login');
    } else {
        $clients = array();
        if (Input::get('clientId') == "all") {
            $client_data = DB::getInstance()->query("SELECT * FROM `future_client`");
        } else {
            $clientId = base64_decode(Input::get('clientId'));
            $client_data = DB::getInstance()->query("SELECT * FROM `future_client` WHERE `id` = $clientId");
        }
        if ($client_data->count()) {
            $i = 0;
            foreach ($client_data->results() as $tclient) {
                $i++;
                $clients[] = array(
                    $i,
                    $tclient->firstname,
                    $tclient->lastname,
                    $tclient->email,
                    $tclient->telephone,
                    $tclient->organisation,
                    $tclient->job_title,
                    $tclient->city,
                    $tclient->country
                );
            }
        }

        $filename = "Future_summit_client" . date('Y-m-d H:i:s') . ".csv";

        header('Content-Type: text/csv; charset=utf-8');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $output = fopen('php://output', 'w');
        fputcsv($output, array(
            'No',
            'First name',
            'Last name',
            'Email',
            'Telephone',
            'Organisation',
            'Job title',
            'City',
            'Country'
        ));

        if (count($clients) > 0) {
            foreach ($clients as $row) {
                fputcsv($output, $row);
            }
        }
    }
?>