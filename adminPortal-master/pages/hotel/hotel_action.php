<?php
    require_once "../../core/init.php"; 
    if(!$user->isLoggedIn()) {
        Redirect::to('login');
    }

    $valid['success'] = array('success' => false, 'messages' => array());


    /** Load all participants table */ 
    if(Input::checkInput('request', 'post', 1)):
        $_post_request_ = Input::get('request', 'post');
        switch($_post_request_):

            /** NEW HOTEL */
            case 'addHotel':
                $_form_ = HotelController::addHotel();
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $valid['messages'] = "Successfully Created";    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = $_form_->ERRORS_STRING;
                endif;
                echo json_encode($valid);
            break;

            /** EDIT HOTEL */
            case 'editHotel':
                $_form_ = HotelController::updateHotel();
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $valid['messages'] = "Successfully Updated";    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = $_form_->ERRORS_STRING;
                endif;
                echo json_encode($valid);
            break;

            /** ACTIVATE HOTEL */
            case 'activateHotel':
                $_form_ = HotelController::changeHotelStatus('ACTIVE');
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $valid['messages'] = $_form_->RETURNEDMESSAGE;    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = $_form_->ERRORS_STRING;
                endif;
                echo json_encode($valid);
            break;
            
            /** DEACTIVATE HOTEL */
            case 'deactivateHotel':
                $_form_ = HotelController::changeHotelStatus('DEACTIVE');
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $valid['messages'] = "Successfully deactivated";    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = $_form_->ERRORS_STRING;
                endif;
                echo json_encode($valid);
            break;

            /** HOTEL LIST */
            case 'fetchHotels':
                /** Filter Condition */
                $_filter_condition_ = "";

                ## Read value
                $draw            = Input::get('draw', 'post');
                $row             = Input::get('start', 'post');
                $rowperpage      = Input::get('length', 'post'); // Rows display per page
                $columnIndex     = Input::get('order', 'post')[0]['column']; // Column index
                $columnName      = Input::get('columns', 'post')[$columnIndex]['data']; // Column name
                $columnSortOrder = Input::get('order', 'post')[0]['dir']; // asc or desc
                $searchValue     = Input::get('search', 'post')['value']; // Search value

                ## Search 
                if($searchValue != ''){
                    $_filter_condition_ .= " AND (name LIKE '%".$searchValue."%' OR rate LIKE '%".$searchValue."%' OR country LIKE '%".$searchValue."%' OR city LIKE '%".$searchValue."%')";
                }

                ## Total number of records without filtering
                $totalRecords = HotelController::getHotelCount();

                ## Total number of records with filtering
                $totalRecordwithFilter = HotelController::getHotelCount($_filter_condition_);

                ## Fetch records
                $order = "ORDER BY ".$columnName." DESC LIMIT ".$row.",".$rowperpage;
                $partRecords = HotelController::getHotels($_filter_condition_, $order);

                if ($partRecords) {
                    $count_ = 0;
                    foreach($partRecords as $hotel_) {
                        $count_++;
                        // STATUS
                        $_status_ = $hotel_->status;
                        $_status_label_ = 'badge-warning';
                        if($_status_ == 'ACTIVE')
                            $_status_label_ = 'badge-success';
                        if($_status_ == 'DEACTIVE')
                            $_status_label_ = 'badge-danger';

                        if ($hotel_->photo != "") {
                            $imageUrl = DN.'/data_system/img/hotel/'.$hotel_->photo;
                        } else {
                            $imageUrl = DN.'/data_system/img/banner-placeholder.jpg';
                        }

                        // ACTION
                        $edit_key = Hash::encryptToken($hotel_->id);
                        $action = "
                        <div class='btn-group text-right'>";
                            $action .= "<button class='btn-white btn btn-xs edit_hotel' data-id='$edit_key'><i class='fa fa-pencil'></i></button>";
                            if($hotel_->status != 'DEACTIVE'):
                                $action .= "<button class='btn-white btn btn-xs confirm_modal text-red' data-id='$edit_key' data-request='deactivateHotel'><i class='fa fa-times-circle'></i></button>";
                            else:
                                $action .= "<button class='btn-white btn btn-xs confirm_modal text-green' data-id='$edit_key' data-request='activateHotel'><i class='fa fa-check-circle'></i></button>";
                            endif;
                            $action .= "
                        </div>";

                        $data[] = array(
                            "id" => $count_,
                            "photo" => "<span id='ePhoto$edit_key' class='hotel_img'><img src='$imageUrl' class='img img-responsive'></a></span>",
                            "name" => "<span id='eName$edit_key'>{$hotel_->name}</span>",
                            "email" => "<span id='eEmail$edit_key'>{$hotel_->email}</span>",
                            "telephone" => "<span id='eTel$edit_key'>{$hotel_->telephone}</span>",
                            "country" => "<span id='eCountry$edit_key'>{$hotel_->country}</span>",
                            "city" => "<span id='eCity$edit_key'>{$hotel_->city}</span>",
                            "rate" => "<span id='eRate$edit_key'>{$hotel_->rate}</span>",
                            "address" => "<span id='eAddress$edit_key'>{$hotel_->address}</span>",
                            "status"  => "<span class='badge {$_status_label_}' style='display: block;'>{$_status_}</span>",
                            "action"  => $action
                        );
                    }
                } else {
                     $data = array();
                }

                ## Response
                $response = array(
                    "draw" => intval($draw),
                    "iTotalRecords" => $totalRecords,
                    "iTotalDisplayRecords" => $totalRecordwithFilter,
                    "aaData" => $data
                );

                echo json_encode($response);    
            break;

            /** NEW ROOM */
            case 'addRoom':
                $_form_ = HotelController::addRoom();
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $valid['messages'] = "Successfully Created";    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = $_form_->ERRORS_STRING;
                endif;
                echo json_encode($valid);
            break;

            /** EDIT ROOM */
            case 'editRoom':
                $_form_ = HotelController::updateRoom();
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $valid['messages'] = "Successfully Updated";    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = $_form_->ERRORS_STRING;
                endif;
                echo json_encode($valid);
            break;

            /** ROOM LIST */
            case 'fetchRooms':
                /** Filter Condition */
                $_filter_condition_ = "";

                ## Read value
                $draw            = Input::get('draw', 'post');
                $row             = Input::get('start', 'post');
                $rowperpage      = Input::get('length', 'post'); // Rows display per page
                $columnIndex     = Input::get('order', 'post')[0]['column']; // Column index
                $columnName      = Input::get('columns', 'post')[$columnIndex]['data']; // Column name
                $columnSortOrder = Input::get('order', 'post')[0]['dir']; // asc or desc
                $searchValue     = Input::get('search', 'post')['value']; // Search value

                ## Search 
                if($searchValue != ''){
                    $_filter_condition_ .= " AND (hotel_room.room_type LIKE '%".$searchValue."%' OR hotel.name LIKE '%".$searchValue."%')";
                }

                ## Total number of records without filtering
                $totalRecords = HotelController::getRoomsCount();

                ## Total number of records with filtering
                $totalRecordwithFilter = HotelController::getRoomsCount($_filter_condition_);

                ## Fetch records
                $order = "ORDER BY ".$columnName." DESC LIMIT ".$row.",".$rowperpage;
                $partRecords = HotelController::getRooms($_filter_condition_, $order);

                if ($partRecords) {
                    $count_ = 0;
                    foreach($partRecords as $hotel_room_) {
                        $count_++;
                        // STATUS
                        $_status_ = $hotel_room_->status;
                        $_status_label_ = 'badge-warning';
                        if($_status_ == 'ACTIVE')
                            $_status_label_ = 'badge-success';
                        if($_status_ == 'DEACTIVE')
                            $_status_label_ = 'badge-danger';

                        if ($hotel_room_->room_photo != "") {
                            $imageUrl = DN.'/data_system/img/hotel/room/'.$hotel_room_->room_photo;
                        } else {
                            $imageUrl = DN.'/data_system/img/banner-placeholder.jpg';
                        }

                        // ACTION
                        $edit_key = Hash::encryptToken($hotel_room_->id);
                        $hotel_id = Hash::encryptToken($hotel_room_->hotel_id);
                        $action = "
                        <div class='btn-group text-right'>";
                            $action .= "<button class='btn-white btn btn-xs edit_room' data-id='$edit_key'><i class='fa fa-pencil'></i></button>";
                            if($hotel_room_->status != 'DEACTIVE'):
                                $action .= "<button class='btn-white btn btn-xs confirm_modal text-red' data-id='$edit_key' data-request='deactivateRoom'><i class='fa fa-times-circle'></i></button>";
                            else:
                                $action .= "<button class='btn-white btn btn-xs confirm_modal text-green' data-id='$edit_key' data-request='activateRoom'><i class='fa fa-check-circle'></i></button>";
                            endif;
                            $action .= "
                        </div>";

                        $data[] = array(
                            "id" => $count_,
                            "room_photo" => "<span id='ePhoto$edit_key' class='hotel_img'><img src='$imageUrl' class='img img-responsive'></a></span>",
                            "name" => "<span id='eName$edit_key'>{$hotel_room_->hotel_name}</span>
                            <span id='eHotelID$edit_key' style='display: none;'>{$hotel_id}</span>",
                            "room_type" => "<span id='eType$edit_key'>{$hotel_room_->room_type}</span>",
                            "room_occupancy" => "<span id='eOcc$edit_key'>{$hotel_room_->room_occupancy}</span>",
                            "adults" => "<span id='eAdul$edit_key'>{$hotel_room_->adults}</span>",
                            "children" => "<span id='eChil$edit_key'>{$hotel_room_->children}</span>",
                            "room_price" => "<span id='ePrice$edit_key'>{$hotel_room_->room_price}</span> <span id='eCurr$edit_key'>{$hotel_room_->currency}</span>",
                            "bed_type" => "<span id='eBed$edit_key'>{$hotel_room_->bed_type}</span>
                            <span id='eDesc$edit_key' style='display: none;'>{$hotel_room_->room_description}</span>",
                            "status"  => "<span class='badge {$_status_label_}' style='display: block;'>{$_status_}</span>",
                            "action"  => $action
                        );
                    }
                } else {
                     $data = array();
                }

                ## Response
                $response = array(
                    "draw" => intval($draw),
                    "iTotalRecords" => $totalRecords,
                    "iTotalDisplayRecords" => $totalRecordwithFilter,
                    "aaData" => $data
                );

                echo json_encode($response);    
            break;

            /** EDIT ROOM */
            case 'editRoom':
                $_form_ = HotelController::updateRoom();
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $valid['messages'] = "Successfully Updated";    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = $_form_->ERRORS_STRING;
                endif;
                echo json_encode($valid);
            break;

            /** ACTIVATE ROOM */
            case 'activateRoom':
                $_form_ = HotelController::changeRoomStatus('ACTIVE');
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $valid['messages'] = $_form_->RETURNEDMESSAGE;    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = $_form_->ERRORS_STRING;
                endif;
                echo json_encode($valid);
            break;
            
            /** DEACTIVATE ROOM */
            case 'deactivateRoom':
                $_form_ = HotelController::changeRoomStatus('DEACTIVE');
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $valid['messages'] = "Successfully deactivated";    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = $_form_->ERRORS_STRING;
                endif;
                echo json_encode($valid);
            break;


            /** Table - Display the list of Participant Registered */
        case 'fetchBookings':
            /** Filter Condition */
            $_filter_condition_ = "";

            /** Filter By Participation Type */
            $_PARTICIPATION_TYPE_TOKEN_    = Input::get('type', 'post');
            $_PARTICIPATION_SUBTYPE_TOKEN_ = Input::get('subtype', 'post');
            
            $_PAYMENT_TRANSACTION_STATUS_  = Input::get('status', 'post');
            $_PAYMENT_CHANNEL_             = Input::get('payment_channel', 'post');

            $_COUNTRY_                     = Input::get('country', 'post');
            
            $_DATE_1_  = Input::get('datefrom', 'post');
            $_DATE_2_  = Input::get('dateto', 'post');

            $_DATETIME_1_ = $_DATE_1_ == ''?0:strtotime($_DATE_1_);
            $_DATETIME_2_ = $_DATE_2_ == ''?0:strtotime($_DATE_2_.' 11:59 pm');

            if($_DATE_1_ != '' OR $_DATE_2_ != ''):
                if($_DATE_1_ != '' AND $_DATE_2_ != ''):
                    $_filter_condition_    .= " AND hotel_booking_transaction.transaction_time BETWEEN $_DATETIME_1_ AND  $_DATETIME_2_ ";

                elseif($_DATE_1_ != '' AND $_DATE_2_ == ''):
                    $_filter_condition_    .= " AND hotel_booking_transaction.transaction_time >=  $_DATETIME_1_ ";
                
                elseif($_DATE_1_ == '' AND $_DATE_2_ != ''):
                    $_filter_condition_    .= " AND hotel_booking_transaction.transaction_time <=  $_DATETIME_1_ ";
                endif;
            endif;


            if($_PARTICIPATION_TYPE_TOKEN_ != '' ):
                $_HOTEL_ID_                  = Hash::decryptToken($_PARTICIPATION_TYPE_TOKEN_);
                if(is_integer($_HOTEL_ID_))
                    $_filter_condition_    .= " AND future_participants.hotel_id = $_HOTEL_ID_ ";
            endif;
            
            if($_PARTICIPATION_SUBTYPE_TOKEN_ != '' ):
                $_SUBTYPE_ID_               = Hash::decryptToken($_PARTICIPATION_SUBTYPE_TOKEN_);
                if(is_integer($_SUBTYPE_ID_))
                    $_filter_condition_    .= " AND future_participants.room_id = $_SUBTYPE_ID_ ";
            endif;

            if($_PAYMENT_TRANSACTION_STATUS_ != ''):
                $_filter_condition_    .= " AND hotel_booking_transaction.transaction_status = '$_PAYMENT_TRANSACTION_STATUS_' ";
            endif;

            if($_PAYMENT_CHANNEL_ != ''):
                if($_PAYMENT_CHANNEL_ == 'ONLINE_PAYMENT')
                    $_filter_condition_    .= " AND hotel_booking_transaction.payment_method != 'BANK_TRANSFER' ";
                if($_PAYMENT_CHANNEL_ == 'BANK_TRANSFER')
                    $_filter_condition_    .= " AND hotel_booking_transaction.payment_method = 'BANK_TRANSFER' ";
            endif;

            if($_COUNTRY_ != '' ):
                if ($_COUNTRY_ == 'Local') {
                    $_filter_condition_    .= " AND future_participants.residence_country = 'Rwanda' ";
                } else{
                    $_filter_condition_    .= " AND future_participants.residence_country != 'Rwanda' ";
                }
            endif;

            ## Read value
            $draw            = Input::get('draw', 'post');
            $row             = Input::get('start', 'post');
            $rowperpage      = Input::get('length', 'post'); // Rows display per page
            $columnIndex     = Input::get('order', 'post')[0]['column']; // Column index
            $columnName      = Input::get('columns', 'post')[$columnIndex]['data']; // Column name
            $columnSortOrder = Input::get('order', 'post')[0]['dir']; // asc or desc
            $searchValue     = Input::get('search', 'post')['value']; // Search value

            ## Search 
            if($searchValue != ''){
                $_filter_condition_ .= " AND (future_participants.firstname LIKE '%".$searchValue."%' 
                OR future_participants.lastname LIKE '%".$searchValue."%' 
                OR future_participants.organisation_name LIKE'%".$searchValue."%' 
                OR future_participation_type.name LIKE'%".$searchValue."%' 
                OR future_participation_sub_type.name LIKE'%".$searchValue."%')";
            }

            ## Total number of records without filtering
            $totalRecords = HotelController::getBookingParticipantsCounterByEventID($eventId);

            ## Total number of records with filtering
            $totalRecordwithFilter = HotelController::getBookingParticipantsCounterByEventID($eventId, $_filter_condition_);

            ## Fetch records
            $order = "ORDER BY hotel_booking_transaction.".$columnName." DESC LIMIT ".$row.",".$rowperpage;
            $partRecords = HotelController::getBookingParticipantsByEventID($eventId, $_filter_condition_, $order);

            ## Fetch records for data export
            $order = "ORDER BY hotel_booking_transaction.".$columnName." DESC";
            $exportRecords = HotelController::getBookingParticipantsByEventID($eventId, $_filter_condition_, $order);
            Session::put('paymentExportData', $exportRecords);

            if ($partRecords) {
                $count_ = 0;
                foreach($partRecords as $payment_transaction_) {
                    $count_++;

                    // STATUS
                    $_status_ = $payment_transaction_->transaction_status;
                    $_status_label_ = 'badge-warning';
            
                    if($_status_ == 'COMPLETED' || $_status_ == 'USED')
                        $_status_label_ = 'badge-success';
                    if($_status_ == 'APPROVED')
                        $_status_label_ = 'badge-success';
                    if($_status_ == 'ACCEPTED')
                        $_status_label_ = 'badge-info';
                    if($_status_ == 'DENIED' || $_status_ == 'REFUNDED')
                        $_status_label_ = 'badge-danger';
                    if($_status_ == 'EXPIRED')
                        $_status_label_ = 'badge-default';

                    $payment_method = $payment_transaction_->payment_method;

                    $now = time();
                    $your_date = strtotime(date('Y-m-d', $payment_transaction_->transaction_time));
                    $datediff = $now - $your_date;

                    $days =  round($datediff / (60 * 60 * 24)). ' days';

                    $partProfile = DN.'/participants/profile/'.Hash::encryptToken($payment_transaction_->participant_ID);

                    // ACTION
                    $noGuestPermission = $user->hasPermission('guest')?'none' :''; // FOR GUEST
                    $edit_key = Hash::encryptToken($payment_transaction_->id);
                    $part_name = $payment_transaction_->participant_firstname .' '. $payment_transaction_->participant_lastname;
                    $action = "
                    <div class='ibox-tools'>
                        <a class='dropdown-toggle' data-toggle='dropdown' href='#' style='color: #3c8dbc;'>More</a>
                        <ul class='dropdown-menu dropdown-user popover-menu-list' style='display: $noGuestPermission;'>";
                            $action .= "<li><a class='menu edit_client' href='$partProfile' target='_blank'><i class='fa fa-eye icon'></i> Profile</a></li>"; // PROFILE

                        $action .= 
                        "</ul>
                    </div>";

                    $data[] = array(
                        "id" => $count_,
                        "transaction_id" => $payment_transaction_->transaction_id == ''?'-':$payment_transaction_->transaction_id,
                        "receipt_id"     => $payment_transaction_->receipt_id == ''?'-':$payment_transaction_->receipt_id,
                        "firstname" => '<a href="'.$partProfile.'" target="_blank">'.$part_name.'</a>',
                        "hotel_name" => $payment_transaction_->hotel_name,
                        "room_type" => $payment_transaction_->room_type,
                        "job_title" => $payment_transaction_->participant_job_title,
                        "organisation_name"  => $payment_transaction_->participant_organization_name,
                        "channel"  => $payment_transaction_->payment_method,
                        "amount"  => $payment_transaction_->transaction_amount. '<small>'.$payment_transaction_->transaction_currency.'</small>',
                        "datetime"  => $days,
                        "status"  => "<span class='badge {$_status_label_}' style='display: block;'>{$_status_}</span>",
                    );
                }
            } else {
                 $data = array();
            }

            ## Response
            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalRecordwithFilter,
                "aaData" => $data
            );

            echo json_encode($response);    
            break;

            /** Action - Export Payment data */
            case 'exportBookingData':
                try{
                    FutureDataExport::paymentCSVReport(Session::get('bookingExportData'));
                } catch(Exception $e){
                    throw new Exception("An error occured while exporting this report");
                }
            break;

            /** Filter Subtype By Type */
            case 'filterBookingRoom':
                $_HOTEL_ID_   = Hash::decryptToken(Input::get('hotel', 'post'));
                $order = " ORDER BY hotel_room.id DESC";
                $_filter_condition_ = " AND hotel_id = $_HOTEL_ID_";
                $_HOTEL_DATA_ = HotelController::getRooms($_filter_condition_, $order);
    ?>
                <option value="">Select room</option>
                <option value="">All</option>
    <?php
                if($_HOTEL_DATA_):
                    foreach($_HOTEL_DATA_ As $room_):
    ?>  
                <option value="<?=Hash::encryptToken($room_->id)?>"><?=$room_->room_type?></option>
    <?php
                    endforeach;
                endif;

            break;


         endswitch;
    endif;      
    
?>


