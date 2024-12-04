<?php
    require_once "../../core/init.php"; 
    if(!$user->isLoggedIn()) {
        Redirect::to('login');
    }

    $valid['success'] = array('success' => false, 'messages' => array());

    if(Input::checkInput('request', 'post', 1)):
        $_post_request_ = Input::get('request', 'post');
        switch($_post_request_):

             /**  Generate and send link for registration */
            case 'sendPrivateLink':
                $_form_ = FutureEventController::createEventParticipantPrivateLink();
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $valid['messages'] = "Successfully generated and sent to {$_form_->EMAIL}";    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
                endif;
                echo json_encode($valid);
            break;

            /** Edit Generated Link and send email */ 
            case 'editAndSendPrivateLink':
                $_form_ = FutureEventController::updateEventParticipantPrivateLink();
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $valid['messages'] = "Successfully updated ";    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
                endif;
                echo json_encode($valid);
            break;
        
            /** Change Status Active Event Participation Type */ 
            case 'activatePrivateLink':
                $_form_ = FutureEventController::changeStatusParticipantPrivateLink('ACTIVE');
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
            case 'deactivatePrivateLink':
                $_form_ = FutureEventController::changeStatusParticipantPrivateLink('DEACTIVE');
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $valid['messages'] = "Successfully deactivated";    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
                endif;
                echo json_encode($valid);
            break;

            /** Edit Generated Link and send email */ 
            case 'resendPrivateLink':
                $_form_ = FutureEventController::resendEventParticipantPrivateLink();
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $valid['messages'] = "Successfully resent";    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
                endif;
                echo json_encode($valid);
            break;

            // Delete private link
            case 'deletePrivateLink':
                try {
                    $controller->delete('future_private_links', array('id', '=', Input::get('Id')));
                    $valid['success'] = true;
                    $valid['messages'] = "Successfully deleted";    
                } catch(Exception $error) {
                    $valid['success'] = false;
                    $valid['messages'] = "Error while deleting";
                }
                echo json_encode($valid);   
            break;

            /** Load List Of Event Participation Types */
            case 'fetchGeneratedLinks':
                $_LIST_DATA_ = FutureEventController::getGeneratedPrivateLinks($eventId);

                if (!$_LIST_DATA_) {
                    Danger("No link recorded");
                } else {
        ?>

                <table class="table dataTables-example customTable">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>First name</th>
                            <th>Last name</th>
                            <th>Email</th>
                            <th>Participation Type</th>
                            <th>Generated time</th>
                            <th>Registration time</th>
                            <th>Copy link</th>
                            <th>Status</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                // $_LIST_DATA_ = FutureEventController::getGeneratedPrivateLinks($eventId);
                if($_LIST_DATA_): $count_ = 0;
                    foreach($_LIST_DATA_  As $_link_): $count_++;

                        $_status_ = $_link_->status;
                        $_status_label_ = 'badge-warning';

                        if($_status_ == 'COMPLETED' || $_status_ == 'USED')
                            $_status_label_ = 'badge-success';
                        if($_status_ == 'ACTIVE')
                            $_status_label_ = 'badge-info';
                        if($_status_ == 'DEACTIVE')
                            $_status_label_ = 'badge-danger';
                        if($_status_ == 'EXPIRED')
                            $_status_label_ = 'badge-default';

                        $edit_key = Hash::encryptToken($_link_->id);

                        
                ?>
                        <tr class="gradeX">
                            <div style='display: none'>
                                <span id='eFirst<?=$edit_key?>'><?=$_link_->firstname?></span>
                                <span id='eLast<?=$edit_key?>'><?=$_link_->lastname?></span>
                                <span id='eEmail<?=$edit_key?>'><?=$_link_->email?></span>
                                <span id='ePass<?=$edit_key?>'><?=Hash::encryptAuthToken($_link_->participation_sub_type_id)?></span>
                            </div>
                            <td>
                                <span style="color: #3c8dbc; border-left: 2px solid #3c8dbc; padding: 3px; font-size: 12px;">
                                    <?=$count_;?>
                                </span>
                            </td>
                            <td><?=$_link_->firstname?></td>
                            <td><?=$_link_->lastname?></td>
                            <td><?=$_link_->email?></td>
                            <td><?=$_link_->type_name .'  '.$_link_->sub_type_name?></td>
                            <td><?=date('d-M-Y', $_link_->access_generated_time)?></td>
                            <td><?=date('d-M-Y', $_link_->access_generated_time)?></td>
                            <td>
                                <button class="btn-white btn-bitbucket" data-toggle="tooltip" data-placement="top"
                                    title="Copy to clipboard" onclick="copyToClipboard('#tooltip_<?=$_link_->id?>', <?=$_link_->id?>)">
                                    <i class="fa fa-copy text-info" data-link="<?=$_link_->generated_link?>"
                                        id="tooltip_<?=$_link_->id?>"></i>
                                        <small id="alert_<?=$_link_->id?>" style="color: #23c6c8;"></small>
                                </button>
                            </td>
                            <td><span class="badge <?= $_status_label_ ?>" style="display: block;"><?=$_status_ ?></span></td>
                            <td>
                                <div class="ibox-tools">
                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color: #3c8dbc;">More</a>
                                    <ul class="dropdown-menu dropdown-user popover-menu-list">
                                        <li><a class="menu edit_link" data-id="<?=$edit_key?>"><i class="fa fa-pencil icon"></i> Edit</a></li>
                                        <!-- <li><a class="menu confirm_modal" data-id="<?=$edit_key?>" data-request="activatePrivate"><i class="fa fa-check icon"></i> Activate</a></li>
                                        <li><a class="menu confirm_modal" data-id="<?=$edit_key?>" data-request="deactivatePrivateLink"><i class="fa fa-remove icon"></i> Deactivate</a></li> -->
                                        <li><a class="menu confirm_modal" data-id="<?=$edit_key?>" data-request="resendPrivateLink"><i class="fa fa-send icon"></i> Resend Link</a></li>

                                        <?php if($user->hasPermission('admin')): ?>
                                        <li><a class="menu confirm_modal" data-id="<?=$edit_key?>" data-request="deletePrivateLink"><i class="fa fa-trash icon"></i> Delete Link</a></li>
                                        <?php endif;?>
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