<?php
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header("Content-Type:application/json");

require_once("../core/Init.php");
require_once("../entities/User.php");
require_once("../entities/Attendance.php");

$response = array();
$response['status'] = BAD_REQUEST;
$response['message'] = "Bad Request";

if (Input::checkInput('resource', 'get', '1')) {
    $request = Input::get('resource', 'get');

    switch ($request) {

        case 'new':
            /*
            * METHOD: POST
            */
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                $id="5423";

                $encrypted=Hash::encryptAuthToken($id, "");


                $response['status'] = SUCCESS;
                $response['plain'] = $id;
                $response['cypher'] = $encrypted;
                $response['decyphered']= Hash::decryptAuthToken($encrypted);
                
            } else {
                $response['status'] = BAD_REQUEST_METHOD;
                $response['message'] = "Method Not Allowed";
            }

            break;

        default:

            $response['status'] = BAD_REQUEST;
            $response['message'] = "Bad Request";
            break;
    }
}


echo json_encode($response);
?>