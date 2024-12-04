<?php
    header('Access-Control-Allow-Origin:*');
    header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
    header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');
    header("Content-Type:application/json");

    require_once "../../core/init.php"; 
    
    $response['status'] = array('status' => "403", 'message' => 'Bad Request');
    $data = array();
    $authToken = Httprequests::getBearerToken();

    /** TEMPLATE APIs */ 
    if(Input::checkInput('request', 'post', 1)):
        $_post_request_ = Input::get('request', 'post');
        switch($_post_request_):

            /** LOAD HOTEL ROOMS */
            case 'loadHotelRooms':
                if (!empty($authToken)) {
                    if ($authToken == $templateAuthToken) {
                        $hotelId = Hash::decryptToken(Input::get('hotelToken', 'post'));
                        $_filter_condition_ = " AND hotel_room.hotel_id = $hotelId";
                        $order = " ORDER BY hotel_room.id ASC";
                        $roomRecords = HotelController::getRooms($_filter_condition_, $order);

                        if ($roomRecords) {
                            $count_ = 0;
                            foreach($roomRecords as $room_) {
                                $data[] = array(
                                    "edit_key" => Hash::encryptToken($room_->id),
                                    "hotel_name" => $room_->hotel_name,
                                    "room_type" => $room_->room_type,
                                    "room_price" => $room_->room_price,
                                    "currency" => $room_->currency
                                );
                            }
                            $response['status'] = "200";
                            $response['message'] = "Operation completed successfully";
                            $response['data'] = $data;
                        } else {
                            $response['status'] = "400";
                            $response['message'] = "No room recorded";
                        }

                    } else {
                        $response['status'] = "400";
                        $response['message'] = "Invalid or Expired API Key";
                    }
                } else {
                    $response['status'] = "403";
                    $response['message'] = "API Key missing";
                }
                echo json_encode($response);
            break;

            /** LOAD HOTELs */
            case 'loadHotels':
                if (!empty($authToken)) {
                    if ($authToken == $templateAuthToken) {
                        $_filter_condition_ = "";
                        $order = " ORDER BY id ASC";
                        $hotelRecords = HotelController::getHotels($_filter_condition_, $order);

                        if ($hotelRecords) {
                            $count_ = 0;
                            foreach($hotelRecords as $hotel_) {
                                $data[] = array(
                                    "edit_key" => Hash::encryptToken($hotel_->id),
                                    "hotel_name" => $hotel_->name
                                );
                            }
                            $response['status'] = "200";
                            $response['message'] = "Operation completed successfully";
                            $response['data'] = $data;
                        } else {
                            $response['status'] = "400";
                            $response['message'] = "No hotel recorded";
                        }

                    } else {
                        $response['status'] = "400";
                        $response['message'] = "Invalid or Expired API Key";
                    }
                } else {
                    $response['status'] = "403";
                    $response['message'] = "API Key missing";
                }
                echo json_encode($response);
            break;

        endswitch;
    endif;      
    
?>


