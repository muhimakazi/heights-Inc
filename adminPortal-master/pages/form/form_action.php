<?php
    require_once "../../core/init.php"; 
    if(!$user->isLoggedIn()) {
        Redirect::to('login');
    }

    $response['success'] = array('success' => false, 'messages' => array());


    // 1. CREATE / UPDATE FORM

    /** Load all participants table */ 
    if(Input::checkInput('request', 'post', 1)):
        $_post_request_ = Input::get('request', 'post');
        switch($_post_request_):

            /** NEW FORM */
            case 'createForm':
                $_form_ = FormController::createForm();
                if($_form_->ERRORS == false):
                    $response['success']  = true;
                    $response['messages'] = "Successfully Created";    
                else:
                    $response['success']  = false;
                    $response['messages'] = $_form_->ERRORS_STRING;
                endif;
                echo json_encode($response);
            break;

            /** EDIT FORM */
            case 'editForm':
                $_form_ = FormController::updateForm();
                if($_form_->ERRORS == false):
                    $response['success']  = true;
                    $response['messages'] = "Successfully Updated";    
                else:
                    $response['success']  = false;
                    $response['messages'] = $_form_->ERRORS_STRING;
                endif;
                echo json_encode($response);
            break;

            /** PUBLISH FORM */
            case 'publishForm':
                $_form_ = FormController::changeFormStatus('PUBLISH');
                if($_form_->ERRORS == false):
                    $response['success']  = true;
                    $response['messages'] = $_form_->RETURNEDMESSAGE;    
                else:
                    $response['success']  = false;
                    $response['messages'] = $_form_->ERRORS_STRING;
                endif;
                echo json_encode($response);
            break;
            
            /** UNPUBLISH FORM */
            case 'unpublishForm':
                $_form_ = FormController::changeFormStatus('UNPUBLISH');
                if($_form_->ERRORS == false):
                    $response['success']  = true;
                    $response['messages'] = "Successfully Unpublished";    
                else:
                    $response['success']  = false;
                    $response['messages'] = $_form_->ERRORS_STRING;
                endif;
                echo json_encode($response);
            break;

            /** FORM LIST */
            case 'fetchForms':
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
                    $_filter_condition_ .= " AND (form_name LIKE '%".$searchValue."%')";
                }

                ## Total number of records without filtering
                $totalRecords = FormController::getFormsCount($eventId);

                ## Total number of records with filtering
                $totalRecordwithFilter = FormController::getFormsCount($eventId, $_filter_condition_);

                ## Fetch records
                $order = "ORDER BY ".$columnName." DESC LIMIT ".$row.",".$rowperpage;
                $partRecords = FormController::getForms($eventId, $_filter_condition_, $order);

                if ($partRecords) {
                    $count_ = 0;
                    foreach($partRecords as $form_) {
                        $count_++;
                        // STATUS
                        $_status_ = $form_->publish_status;
                        if($_status_ == 1):
                            $_status_ = 'PUBLISHED';
                            $_status_label_ = 'badge-success';
                        else:
                            $_status_ = 'UNPUBLISHED';
                            $_status_label_ = 'badge-warning';
                        endif;

                        $_publish_type_ = $form_->publish_type;
                        if($_publish_type_ == 1):
                            $_publish_type_ = 'PUBLIC';
                        else:
                            $_publish_type_ == 'PRIVATE';
                        endif;

                        // ACTION
                        $edit_key = Hash::encryptToken($form_->id);
                        $form_name = $form_->form_name;
                        $buildForm = DN.'/form/build/'.$edit_key;

                        $action = "
                        <div class='ibox-tools'>
                            <a class='dropdown-toggle' data-toggle='dropdown' href='#' style='color: #3c8dbc;'>More</a>
                            <ul class='dropdown-menu dropdown-user popover-menu-list'>";
                                $action .= "<li><a class='menu' href='build/$edit_key'><i class='fa fa-pencil'></i> Build form</a></li>";
                                $action .= "<li><a class='menu edit_form' data-id='$edit_key'><i class='fa fa-pencil'></i> Edit form </a></li>";
                                if($form_->publish_type == 1):
                                    $action .= "<li><a class='menu confirm_modal' data-id='$edit_key' data-name='$form_name' data-request='unpublishForm' style='color:red;'><i class='fa fa-times-circle'></i> Unpublish</a></li>";
                                else:
                                    $action .= "<li><a class='menu confirm_modal' data-id='$edit_key' data-name='$form_name' data-request='publishForm' style='color:green;'><i class='fa fa-check-circle'></i> Publish</a></li>";
                                endif;
                            $action .= 
                            "</ul>
                        </div>";

                        $action = "
                        <div class='btn-group'>";
                            $action .= "<a href='build/$edit_key' class='btn-white btn btn-xs'><i class='fa fa-list'></i> Build Form</a>";
                            $action .= "<button class='btn-white btn btn-xs edit_form' data-id='$edit_key'><i class='fa fa-pencil'></i> Edit form</button>";
                            if($form_->publish_type == 1):
                                $action .= "<button class='btn-white btn btn-xs confirm_modal text-red' data-id='$edit_key' data-name='$form_name' data-request='unpublishForm'><i class='fa fa-times-circle'></i> Unpublish</button>";
                            else:
                                $action .= "<button class='btn-white btn btn-xs confirm_modal text-green' data-id='$edit_key' data-name='$form_name' data-request='publishForm'><i class='fa fa-check-circle'></i> Publish</button>";
                            endif;
                            $action .= "
                        </div>";

                        $data[] = array(
                            "id" => $count_,
                            "form_name" => "<span id='eName$edit_key'>{$form_->form_name}</span>",
                            "publish_type" => "<span>{$_publish_type_}</span><span id='ePubType$edit_key' style='display:none'>{$form_->publish_type}</span>",
                            "order" => "<span id='eOrder$edit_key'>{$form_->form_order}</span> 
                            <span id='eNote$edit_key' style='display:none'>{$form_->form_note}</span>
                            <span id='eRegEmailSubj$edit_key' style='display:none'>{$form_->registration_email_subject}</span>
                            <span id='eRegEmailMess$edit_key' style='display:none'>{$form_->registration_email_message}</span>
                            <span id='eApprEmailSubj$edit_key' style='display:none'>{$form_->approval_email_subject}</span>
                            <span id='eApprEmailMess$edit_key' style='display:none'>{$form_->approval_email_message}</span>",
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

// ===============================================================================================


            // 2. FORM BUILDER

            // SAVE FORM FIELDS
            case 'saveFormTemplate':
                $_form_ = FormController::buildForm();
                if($_form_->ERRORS == false):
                    $response['success']  = true;
                    $response['messages'] = $_form_->SUCCESS_STRING;
                else:
                    $response['success']  = false;
                    $response['messages'] = $_form_->ERRORS_STRING;
                endif;
                echo json_encode($response);
            break;

            // LOAD FORM CONTENT
            case 'formContent':
                $_form_content = FormController::loadFormContent();
                echo $_form_content;
            break;
        endswitch;
    endif;      
    
?>


