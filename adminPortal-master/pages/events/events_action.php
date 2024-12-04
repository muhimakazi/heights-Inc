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

            /** Action - Create New Account */
            case 'addNewEvent':
                $_form_ = FutureEventController::createEvent();
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $valid['messages'] = "Successfully Created";    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = $_form_->ERRORS_STRING;
                endif;
                echo json_encode($valid);
            break;

            /** Action - Edit User Account */
            case 'editEvent':
                $_form_ = FutureEventController::updateEvent();
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $valid['messages'] = "Successfully Updated";    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = $_form_->ERRORS_STRING;
                endif;
                echo json_encode($valid);
            break;

            /** Table - Display the list of users */
            case 'fetchEvents':
                /** Filter Condition */
                $_filter_condition_ = "";

                if (!$user->hasPermission('admin')) {
                    $clientId = $user->data()->client_id;
                    $_filter_condition_ .= " AND future_event.client_id = $clientId";
                }

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
                    $_filter_condition_ .= " AND (future_event.event_name LIKE '%".$searchValue."%' OR future_event.ticket_type LIKE '%".$searchValue."%' 
                    OR future_event.event_type LIKE'%".$searchValue."%' OR future_client.organisation LIKE'%".$searchValue."%')";
                }

                ## Total number of records without filtering
                $totalRecords = FutureEventController::getEventsCount();

                ## Total number of records with filtering
                $totalRecordwithFilter = FutureEventController::getEventsCount($_filter_condition_);

                ## Fetch records
                $order = "ORDER BY future_event.".$columnName." DESC LIMIT ".$row.",".$rowperpage;
                $eventRecords = FutureEventController::getEvents($_filter_condition_, $order);

                if ($eventRecords) {
                    $count_ = 0;
                    foreach($eventRecords as $event_) {
                        $count_++;

                        // STATUS
                        $_status_ = $event_->status;
                        $_status_label_ = 'badge-warning';
                        if($_status_ == 'ACTIVE')
                            $_status_label_ = 'badge-success';
                        if($_status_ == 'BLOCKED')
                            $_status_label_ = 'badge-danger';

                        // ACTION
                        $edit_key = Hash::encryptToken($event_->id);

                        if ($event_->banner != "") {
                            $imageUrl = DN.'/data_system/img/banner/'.$event_->logo;
                        } else {
                            $imageUrl = DN.'/data_system/img/banner-placeholder.jpg';
                        }

                        $event_name = $event_->event_name;
                        $start_date = date("j F Y", strtotime(dateFormat($event_->start_date)));
                        $end_date = date("j F Y", strtotime(dateFormat($event_->end_date)));
                        $event_website = DN_EVENT.'/home/'.Hash::encryptAuthToken($event_->id);

                        $general_style = $event_->general_style;
                        $styleArray = json_decode($general_style, true);
                        $style = (object)$styleArray;

                        $adminPermission = !$user->hasPermission('admin')?'none' :''; // FOR ADMIN

                        $action = "
                        <div class='ibox-tools' style='display: $adminPermission;'>
                            <a class='btn-white btn btn-xs dropdown-toggle' data-toggle='dropdown' href='#' style='color: #3c8dbc;'><i class='fa fa-cog'></i></a>
                            <ul class='dropdown-menu dropdown-user popover-menu-list'>";
                                $action .= "<li><a class='menu event_session' data-id='$edit_key' data-name='$event_name' data-url='dashboard'><i class='fa fa-th-large'></i> Dashbord</a></li>"; // SET SESSION
                                $action .= "<li><a class='menu event_session' data-id='$edit_key' data-name='$event_name' data-url='participants'><i class='fa fa-users'></i> Participants</a></li>"; // SET SESSION
                                $action .= "<li><a class='menu' href='$event_website' target='_blank'><i class='fa fa-globe'></i> Website</a></li>"; // WEBSITE
                                $action .= "<li>
                                <div style='display: none'>
                                    <span id='eName$edit_key'>$event_name</span>
                                    <span id='eType$edit_key'>$event_->event_type</span>
                                    <span id='eTicket$edit_key'>$event_->ticket_type</span>
                                    <span id='eClient$edit_key'>$event_->client_id</span>
                                    <span id='eCode$edit_key'>$event_->event_code</span>
                                    <span id='eStart$edit_key'>$event_->start_date</span>
                                    <span id='eEnd$edit_key'>$event_->end_date</span>
                                    <span id='eVenue$edit_key'>$event_->venue</span>
                                    <span id='eBanner$edit_key'>$imageUrl </span>
                                    <span id='ePrimC$edit_key'>$style->primaryColor </span>
                                    <span id='eSeC$edit_key'>$style->secondaryColor </span>
                                </div>
                                <a class='menu edit_event' data-id='$edit_key'><i class='fa fa-pencil'></i> Edit</a></li>"; // SET SESSION
                            $action .= 
                            "</ul>
                        </div>";

                        $card = "
                        <div class='event-card'>
                            <div class='card-img'>
                                <a class='event_session' data-id='$edit_key' data-name='$event_name' data-url='dashboard'><img src='$imageUrl' class='img img-responsive'></a>
                            </div>
                            <div class='event-card-text'>
                                $action
                                <h4><a class='event_session' data-id='$edit_key' data-name='$event_name' data-url='dashboard'>$event_->event_name</a></h4>
                                <small class='block text-muted'><i class='fa fa-calendar'></i> $start_date To $end_date</small>
                            </div>
                        </div>
                        ";

                        $data[] = array(
                            "id" => $card
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

            // Set event session
            case 'eventSession':
                $eventId = Hash::decryptToken(Input::get('eventToken', 'post'));
                $eventName = Input::get('eventName', 'post');
                Session::put('eventId', $eventId);
                Session::put('eventName', $eventName);
                $response['success'] = true;
                $response['messages'] = "Successfully";
                echo json_encode($response);    
            break;


         endswitch;
    endif;      
    
?>


