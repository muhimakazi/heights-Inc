<?php
    require_once "../../core/init.php"; 
    if(!$user->isLoggedIn()) {
        Redirect::to('login');
    }

    $valid['success'] = array('success' => false, 'messages' => array());

    /**  Create Event PROMO CODE */
        if(Input::get('request') && Input::get('request') == 'registerPromoCode') {
                $_form_ = FutureEventController::createEventPromoCode();
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $valid['messages'] = "Successfully registered";    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
                endif;
                echo json_encode($valid);
        }

        /** Edit PROMO CODE */ 
        if(Input::get('request') && Input::get('request') == 'editPromoCode') {
                $_form_ = FutureEventController::updateEventPromoCode();
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $valid['messages'] = "Successfully updated";    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
                endif;
                echo json_encode($valid);
        }
        
        /** Change Status Active Event Participation Type */ 
        if(Input::get('request') && Input::get('request') == 'activatePromoCode') {
            $_form_ = FutureEventController::changeStatusPromoCode('ACTIVE');
            if($_form_->ERRORS == false):
                $valid['success']  = true;
                $valid['messages'] = "Successfully activated";    
            else:
                $valid['success']  = false;
                $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
            endif;
            echo json_encode($valid);
        }

           
        /** Change Status Deactive Event Participation Type */ 
        if(Input::get('request') && Input::get('request') == 'deactivatePromoCode') {
            $_form_ = FutureEventController::changeStatusPromoCode('DEACTIVE');
            if($_form_->ERRORS == false):
                $valid['success']  = true;
                $valid['messages'] = "Successfully deactivated";    
            else:
                $valid['success']  = false;
                $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
            endif;
            echo json_encode($valid);
        }

        /** Load List Of Event Promo codes */
        if(Input::get('request') && Input::get('request') == 'fetchPromoCodes') {
            $_LIST_DATA_ = FutureEventController::getPromocodesByEventID($eventId);

            if (!$_LIST_DATA_) {
                Danger("No link recorded");
            } else {
    ?>
        
        <table class="table dataTables-example customTable">
                            <thead>
                                <tr>
                                    <th>#ID</th>
                                    <th>Category</th>
                                    <th>Promo Code</th>
                                    <th>Discount Rate (<i class="fa fa-percent"></i>)</th>
                                    <th>Total Registered</th>
                                    <th>Maximum Registrations</th>
                                    <th>Organisation</th>
                                    <th>Created By</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
<?php
$_LIST_DATA_ = FutureEventController::getPromocodesByEventID($eventId);
if($_LIST_DATA_): $count_ = 0;
    foreach($_LIST_DATA_  As $_promo_): $count_++;
        
        $_status_ = $_promo_->status;
        $_status_label_ = 'badge-warning';

        if($_status_ == 'COMPLETED')
            $_status_label_ = 'badge-info';
        if($_status_ == 'ACTIVE')
            $_status_label_ = 'badge-success';
        if($_status_ == 'DEACTIVE')
            $_status_label_ = 'badge-danger';
        if($_status_ == 'EXPIRED')
            $_status_label_ = 'badge-default';
?>
                                <tr class="gradeX">
                                    <td>
                                        <span style="color: #3c8dbc; border-left: 2px solid #3c8dbc; padding: 3px; font-size: 12px;">
                                            <?php echo $count_;?>
                                        </span>
                                    </td>
                                    <td><?=FutureEventController::getPacipationCategoryByID($_promo_->participation_type_id)->name?></td>
                                    <td><?=$_promo_->promo_code?></td>
                                    <td><?=$_promo_->discount?></td>
                                    <td><?=FutureEventController::getTotalRegisteredByPromoCodeID($eventId, $_promo_->id)?></td>
                                    <td><?=$_promo_->maximum_delegates?></td>
                                    <td><?=$_promo_->organisation?></td>
                                    <td><?=$_promo_->user_firstname.' '.$_promo_->user_lastname?></td>
                                    <td><span class="badge <?= $_status_label_ ?>" style="display: block;"><?=$_status_ ?></span></td>
                                    <td>
                                        <?php if ($user->hasPermission('admin') || $user->hasPermission('client')) { ?>

                                        <div class="ibox-tools">
                                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color: #3c8dbc;">More</a>
                                            <ul class="dropdown-menu dropdown-user popover-menu-list">
                                                <li>
                                                    <a class="menu edit_promo" 
                                                    data-id="<?=Hash::encryptToken($_promo_->id)?>"
                                                    data-participation="<?=Hash::encryptAuthToken($_promo_->participation_type_id)?>"
                                                    data-promo="<?=$_promo_->promo_code?>"
                                                    data-discount="<?=$_promo_->discount?>"
                                                    data-total="<?=$_promo_->maximum_delegates?>"
                                                    data-organisation="<?=$_promo_->organisation?>">
                                                        <i class="fa fa-pencil icon"></i> Edit
                                                    </a>
                                                </li>
                                                <?php if($_status_ == 'DEACTIVE') {?>
                                                <li><a class="menu activate_promo" data-toggle="modal" data-id="<?=Hash::encryptToken($_promo_->id)?>" ><i class="fa fa-check icon"></i> Activate</a></li>
                                                <?php } else { ?>
                                                <li><a class="menu deactivate_promo" data-toggle="modal" data-id="<?=Hash::encryptToken($_promo_->id)?>" ><i class="fa fa-remove icon"></i> Deactivate</a></li>
                                            <?php } ?>
                                            </ul>
                                        </div>

                                        <?php } ?>
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
}

?>