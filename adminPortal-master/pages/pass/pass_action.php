<?php
    require_once "../../core/init.php"; 
    if(!$user->isLoggedIn()) {
        Redirect::to('login');
    }

    $valid['success'] = array('success' => false, 'messages' => array());

    if(Input::checkInput('request', 'post', 1)):
        $_post_request_ = Input::get('request', 'post');
        switch($_post_request_):
    
            /**  Create Event Participation Type */
            case 'registerPassType':
                $_form_ = FutureEventController::createEventParticipationType();
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $valid['messages'] = "Successfully registered";    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
                endif;
                echo json_encode($valid);
            break;

            /** Edit Event Participation Type */ 
            case 'editPassType':
                $_form_ = FutureEventController::updateEventParticipationType();
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $valid['messages'] = "Successfully updated";    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
                endif;
                echo json_encode($valid);
            break;
            
            /** Change Status Active Event Participation Type */ 
            case 'activatePassType':
                $_form_ = FutureEventController::changeStatusParticipationType('ACTIVE');
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $valid['messages'] = "Successfully activated";    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
                endif;
                echo json_encode($valid);
            break;

               
            /** Change Status Deactive Event Participation Type */ 
            case 'deactivatePassType':
                $_form_ = FutureEventController::changeStatusParticipationType('DEACTIVE');
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $valid['messages'] = "Successfully deactivated";    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
                endif;
                echo json_encode($valid);
            break;

            /** Load List Of Event Participation Types */
            case 'fetchPassTypes':
                $_LIST_DATA_ = FutureEventController::getPacipationTypeyByEventID($eventId);

                if (!$_LIST_DATA_) {
                    Danger("No link recorded");
                } else {
        ?>

                <table class="table dataTables-example customTable">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Type name</th>
                            <th>Payment status</th>
                            <th>Visibility</th>
                            <th>Status</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                $_LIST_DATA_ = FutureEventController::getPacipationTypeyByEventID($eventId);
                if($_LIST_DATA_): $count_ = 0;
                    foreach($_LIST_DATA_  As $pass_): $count_++;
                        
                        $_status_ = $pass_->status;
                        $_status_label_ = 'badge-warning';

                        if($_status_ == 'COMPLETED')
                            $_status_label_ = 'badge-info';
                        if($_status_ == 'ACTIVE')
                            $_status_label_ = 'badge-success';
                        if($_status_ == 'DEACTIVE')
                            $_status_label_ = 'badge-danger';
                        if($_status_ == 'EXPIRED')
                            $_status_label_ = 'badge-default';

                        $edit_key = Hash::encryptToken($pass_->id);
                ?>
                        <tr class="gradeX">
                            <div style='display: none'>
                                <span id='eName<?=$edit_key?>'><?=$pass_->name?></span>
                                <span id='ePaymentStatus<?=$edit_key?>'><?=$pass_->payment_state?></span>
                                <span id='eVisibility<?=$edit_key?>'><?=$pass_->visibility_state?></span>
                                <span id='eForm<?=$edit_key?>'><?=$pass_->form_order?></span>
                            </div>
                            <td>
                                <span style="color: #3c8dbc; border-left: 2px solid #3c8dbc; padding: 3px; font-size: 12px;">
                                    <?=$count_?>
                                </span>
                            </td>
                            <td><?=$pass_->name?></td>
                            <td><?=$pass_->payment_state?></td>
                            <td><span class="badge <?= $pass_->visibility_state == 0? 'badge-success':'badge-default'?>"
                                    style="display: block;"><?=$pass_->visibility_state == 0?'Private':'Public'?></span></td>
                            <td><span class="badge <?= $_status_label_ ?>" style="display: block;"><?=$_status_ ?></span></td>
                            <td>
                                <div class="ibox-tools">
                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color: #3c8dbc;">More</a>
                                    <ul class="dropdown-menu dropdown-user popover-menu-list">
                                        <li><a class="menu edit_type" data-id="<?=$edit_key?>"><i class="fa fa-pencil icon"></i> Edit</a></li>
                                        <li><a class="menu confirm_modal" data-id="<?=$edit_key?>" data-request="activatePassType"><i class="fa fa-check icon"></i> Activate</a></li>
                                        <li><a class="menu confirm_modal" data-id="<?=$edit_key?>" data-request="deactivatePassType"><i class="fa fa-remove icon"></i> Deactivate</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <?php
                    endforeach;
                endif;
                ?>
                    </tbody>
                </table>
                <script>
                $(document).ready(function() {
                    $('.dataTables-example').dataTable({
                        responsive: true,
                        "dom": 'T<"clear">lfrtip',
                        "tableTools": {
                            "sSwfPath": "js/plugins/dataTables/swf/copy_csv_xls_pdf.swf"
                        }
                    });
                });
                </script>
    <?php
                   
                }
            break;


            // PASS SUBTYPE
        
            /**  Create Event Pass Sub Type */
            case 'registerPassSubType':
                $_form_ = FutureEventController::createEventParticipationSubType();
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $valid['messages'] = "Successfully registered";    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
                endif;
                echo json_encode($valid);
            break;

            /** Edit Event Participation Type */ 
            case 'editPassSubType':
                    $_form_ = FutureEventController::updateEventParticipationSubType();
                    if($_form_->ERRORS == false):
                        $valid['success']  = true;
                        $valid['messages'] = "Successfully updated";    
                    else:
                        $valid['success']  = false;
                        $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
                    endif;
                    echo json_encode($valid);
            break;
        
            /** Change Status Active Event Participation Type */ 
            case 'activatePassSubType':
                $_form_ = FutureEventController::changeStatusParticipationSubType('ACTIVE');
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $valid['messages'] = "Successfully activated";    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
                endif;
                echo json_encode($valid);
            break;

           
            /** Change Status Deactive Event Participation Type */ 
            case 'deactivatePassSubType':
                $_form_ = FutureEventController::changeStatusParticipationSubType('DEACTIVE');
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $valid['messages'] = "Successfully deactivated";    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
                endif;
                echo json_encode($valid);
            break;

            /** Load List Of Event Participation Types */
            case 'fetchPassSubTypes':
                $_LIST_DATA_ = FutureEventController::getPacipationSubType($eventId);

                if (!$_LIST_DATA_) {
                    Danger("No link recorded");
                } else {
                ?>
                <table class="table dataTables-example customTable">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Type name</th>
                            <th>Sub Type name</th>
                            <th>Category</th>
                            <th>Payment</th>
                            <th>Price</th>
                            <th>Visibility</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                $_LIST_DATA_ = FutureEventController::getPacipationSubType($eventId);
                if($_LIST_DATA_): $count_ = 0;
                    foreach($_LIST_DATA_  As $pass_): $count_++;

                        $_price_currency_ = ($pass_->price == 0 || $pass_->payment_state == 'FREE')?'-': number_format($pass_->price).' <small class="pull-rightt  text-dark text-bold" >'.$pass_->currency.'<small>';    

                        $_status_ = $pass_->status;
                        $_status_label_ = 'badge-warning';

                        if($_status_ == 'COMPLETED')
                            $_status_label_ = 'badge-info';
                        if($_status_ == 'ACTIVE')
                            $_status_label_ = 'badge-success';
                        if($_status_ == 'DEACTIVE')
                            $_status_label_ = 'badge-danger';
                        if($_status_ == 'EXPIRED')
                            $_status_label_ = 'badge-default';

                        $edit_key = Hash::encryptToken($pass_->id);
                ?>
                        <tr>
                            <div style='display: none'>
                                <span id='eType<?=$edit_key?>'><?=Hash::encryptAuthToken($pass_->type_ID)?></span>
                                <span id='eName<?=$edit_key?>'><?=$pass_->name?></span>
                                <span id='eCategory<?=$edit_key?>'><?=$pass_->category?></span>
                                <span id='ePaymentStatus<?=$edit_key?>'><?=$pass_->payment_state?></span>
                                <span id='ePrice<?=$edit_key?>'><?=$pass_->price?></span>
                                <span id='eCurrency<?=$edit_key?>'><?=$pass_->currency?></span>
                            </div>
                            <td>
                                <span style="color: #3c8dbc; border-left: 2px solid #3c8dbc; padding: 3px; font-size: 12px;">
                                    <?=$count_;?>
                                </span>
                            </td>
                            <td><?=$pass_->type_name?></td>
                            <td><?=$pass_->name?></td>
                            <td><?=$pass_->category?></td>
                            <td><?=$pass_->payment_state?></td>
                            <td><?=$_price_currency_?></td>
                            <td><span class="badge <?= $pass_->type_visibility == 0? 'badge-success':'badge-default'?>"
                                    style="display: block;"><?=$pass_->type_visibility == 0?'Private':'Public'?></span></td>
                            <td><span class="badge <?= $_status_label_ ?>" style="display: block;"><?=$_status_ ?></span></td>
                            <td>
                                <div class="ibox-tools">
                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color: #3c8dbc;">More</a>
                                    <ul class="dropdown-menu dropdown-user popover-menu-list">
                                        <li><a class="menu edit_subtype" data-id="<?=$edit_key?>"><i class="fa fa-pencil icon"></i> Edit</a></li>
                                        <li><a class="menu confirm_modal" data-id="<?=$edit_key?>" data-request="activatePassSubType"><i class="fa fa-check icon"></i> Activate</a></li>
                                        <li><a class="menu confirm_modal" data-id="<?=$edit_key?>" data-request="deactivatePassSubType"><i class="fa fa-remove icon"></i> Deactivate</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <?php
                    endforeach;
                endif;
                ?>
                    </tbody>
                </table>
                <script>
                $(document).ready(function() {
                    $('.dataTables-example').dataTable({
                        responsive: true,
                        "dom": 'T<"clear">lfrtip',
                        "tableTools": {
                            "sSwfPath": "js/plugins/dataTables/swf/copy_csv_xls_pdf.swf"
                        }
                    });
                });
                </script>
                <?php
                           
                }

            break;
        endswitch;
    endif;      
?>