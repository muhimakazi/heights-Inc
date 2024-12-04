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
            case 'creationAdminAccount':
                $_form_ = FutureAccountController::createAccount();
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
            case 'editAdminAccount':
                $_form_ = FutureAccountController::updateAccount();
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $valid['messages'] = "Successfully Updated";    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = $_form_->ERRORS_STRING;
                endif;
                echo json_encode($valid);
            break;

            /** Action - Activate user account */
            case 'activateAdminAccount':
                $_form_ = FutureAccountController::changeAdminStatus('ACTIVE');
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $valid['messages'] = $_form_->RETURNEDMESSAGE;    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = $_form_->ERRORS_STRING;
                endif;
                echo json_encode($valid);
            break;
            
            /** Action - Block user account  */
            case 'blockAdminAccount':
                $_form_ = FutureAccountController::changeAdminStatus('BLOCKED');
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $valid['messages'] = "Successfully blocked";    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = $_form_->ERRORS_STRING;
                endif;
                echo json_encode($valid);
            break;

            // Delete participant
            case 'deleteParticipant':
                try {
                    $controller->delete('future_participants', array('id', '=', Input::get('partId', 'post')));
                    $valid['success'] = true;
                    $valid['messages'] = "Successfully deleted";    
                } catch(Exception $error) {
                    $valid['success'] = false;
                    $valid['messages'] = "Error while deleting";
                }
                echo json_encode($valid);   
            break;

            // CHANGE PASSWORD
            case 'changePassword':
                $_form_ = FutureAccountController::changeUserPassword();
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $valid['messages'] = "Your password has been changed";    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = $_form_->ERRORS_STRING;
                endif;
                echo json_encode($valid);
                break;

            /** Action - Create New Client */
            case 'addNewClient':
                $_form_ = FutureAccountController::createClientAccount();
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $valid['messages'] = "Successfully Created";    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = $_form_->ERRORS_STRING;
                endif;
                echo json_encode($valid);
            break;

            /** Table - Display the list of users */
            case 'fetchAccounts':
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
                    $_filter_condition_ .= " AND (users.firstname LIKE '%".$searchValue."%' OR users.lastname LIKE '%".$searchValue."%' 
                    OR groups.name LIKE'%".$searchValue."%' OR future_client.organisation LIKE'%".$searchValue."%' OR users.status LIKE'%".$searchValue."%')";
                }

                ## Total number of records without filtering
                $totalRecords = FutureAccountController::getAccountCount();

                ## Total number of records with filtering
                $totalRecordwithFilter = FutureAccountController::getAccountCount($_filter_condition_);

                ## Fetch records
                $order = "ORDER BY users.".$columnName." DESC LIMIT ".$row.",".$rowperpage;
                $partRecords = FutureAccountController::getAccounts($_filter_condition_, $order);

                if ($partRecords) {
                    $count_ = 0;
                    foreach($partRecords as $account_) {
                        $count_++;

                        // STATUS
                        $_status_ = $account_->status;
                        $_status_label_ = 'badge-warning';
                        if($_status_ == 'ACTIVE')
                            $_status_label_ = 'badge-success';
                        if($_status_ == 'BLOCKED')
                            $_status_label_ = 'badge-danger';

                        // ACTION
                        $edit_key = Hash::encryptToken($account_->id);
                        $user_name = $account_->firstname .' '. $account_->lastname;
                        $action = "
                        <div class='btn-group pull-right'>";
                            $action .= "<button class='btn-white btn btn-xs edit_user' data-id='$edit_key'><i class='fa fa-pencil'></i> Edit</button>";
                            if($account_->status != 'BLOCKED'):
                                $action .= "<button class='btn-white btn btn-xs confirm_modal text-red' data-id='$edit_key' data-request='blockAdminAccount'><i class='fa fa-times-circle'></i> Block</button>";
                            else:
                                $action .= "<button class='btn-white btn btn-xs confirm_modal text-green' data-id='$edit_key' data-request='activateAdminAccount'><i class='fa fa-check-circle'></i> Activate</button>";
                            endif;
                            $action .= "
                        </div>";

                        $data[] = array(
                            "id" => $count_,
                            "firstname" => "<span id='eFirst$edit_key'>{$account_->firstname}</span>",
                            "lastname" => "<span id='eLast$edit_key'>{$account_->lastname}</span>",
                            "username" => "<span id='eUser$edit_key'>{$account_->username}</span>",
                            "group" => "<span id='eCat$edit_key' style='display:none'>".Hash::encryptToken($account_->group)."</span>".$account_->account_group_name,
                            "organisation" => "<span id='eClient$edit_key' style='display:none'>".Hash::encryptToken($account_->client_id)."</span>
                            <span id='eClientID$edit_key' style='display:none'>{$account_->client_id}</span>".$account_->organisation,
                            "joined" => date("j F Y", strtotime($account_->joined)),
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


            /** Table - Display the list of clients */
            case 'fetchClients':
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
                    $_filter_condition_ .= " AND (firstname LIKE '%".$searchValue."%' OR lastname LIKE '%".$searchValue."%' 
                     OR organisation LIKE'%".$searchValue."%')";
                }

                ## Total number of records without filtering
                $totalRecords = FutureAccountController::getClientCount();

                ## Total number of records with filtering
                $totalRecordwithFilter = FutureAccountController::getClientCount($_filter_condition_);

                ## Fetch records
                $order = "ORDER BY ".$columnName." DESC LIMIT ".$row.",".$rowperpage;
                $partRecords = FutureAccountController::getClients($_filter_condition_, $order);

                if ($partRecords) {
                    $count_ = 0;
                    foreach($partRecords as $account_) {
                        $count_++;

                        // STATUS
                        $_status_ = $account_->status;
                        $_status_label_ = 'badge-warning';
                        if($_status_ == 'ACTIVE')
                            $_status_label_ = 'badge-success';
                        if($_status_ == 'BLOCKED')
                            $_status_label_ = 'badge-danger';

                        // ACTION
                        $edit_key = Hash::encryptToken($account_->id);
                        $country = countryCodeToCountry($account_->country);

                        $data[] = array(
                            "id" => $count_,
                            "firstname" => "<span id='eFirst$edit_key'>{$account_->firstname}</span>",
                            "lastname" => "<span id='eLast$edit_key'>{$account_->lastname}</span>",
                            "email" => "<span id='eEmail$edit_key'>{$account_->email}</span>",
                            "telephone" => "<span id='eTel$edit_key'>{$account_->telephone}</span>",
                            "organisation" => "<span id='eOrg$edit_key'>{$account_->organisation}</span>",
                            "job_title" => "<span id='eJob$edit_key'>{$account_->job_title}</span>",
                            "city" => "<span id='eCity$edit_key'>{$account_->city}</span>",
                            "country" => "<span id='eCountry$edit_key'>{$country}</span>",
                            "status"  => "<span class='badge {$_status_label_}' style='display: block;'>{$_status_}</span>"                        );
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
        endswitch;
    endif;      
    
?>


