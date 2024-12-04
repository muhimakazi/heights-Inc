<?php
require_once "../../core/init.php"; 
if(!$user->isLoggedIn()) {
    Redirect::to('login');
}

$page = "group_register_delegates";
$link = "group_register_delegates";

?>

<!DOCTYPE html>
<html>

<head>
    <?php include $INC_DIR . "head.php"; ?>
    <script src="<?php linkto('js/jquery-2.1.1.js'); ?>"></script>
    <style>
        .label-dark {
            background-color: #2c4897;
            color: #FFFFFF;
        }
    </style>
</head>

<body>
    <div id="wrapper">

        <?php include $INC_DIR . "nav.php"; ?>
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Register Group Members</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="#">Home</a>
                    </li>
                    <li>
                        <a href="#">Group</a>
                    </li>
                    <li>
                        <a href="#"><?=$_GROUP_NAME_?></a>
                    </li>
                    <li class="active">
                        <strong>Slots List</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2"></div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <!-- <div class="col-lg-2"></div> -->
                
               

             

                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        
                        <div class="ibox-title" style="height: auto;">
                          
                        </div>

                        <div class="ibox-content" id="participants-tabled">
                            <?php
$_filter_condition_ = "";
$_LIST_DATA_ = FutureEventGroupController::getGroupSlotsByGroupID($_EVENT_ID_, $_GROUP_ID_, $_filter_condition_);
if (!$_LIST_DATA_):
	Danger("No group delegate recorded");
else: 
	?>
				<table class="table customTable">
					<thead>
						<tr>
							<th>#ID</th>
							<th>Participation Type</th>
							<th>Participation Subtype</th>
							<th>Payment Status</th>
							<th>Maximum Delegates</th>
							<th>Registered Delegates</th>
							<th>Unit Price</th>
							<th>Total Price</th>
                            <th>Currency</th>
							<th>Copy Link</th>
							<th>Status</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
<?php 
	$count_ = 0;
    $url_registration = FutureEventController::getEventEndPointUrlRegistation($eventId);
	foreach( $_LIST_DATA_ as $slot_): $count_++;

        $_status_ = $slot_->status;
        $_status_label_ = 'badge-warning';

        if($_status_ == 'COMPLETED' || $_status_ == 'USED' || $_status_ == 'ACTIVE')
            $_status_label_ = 'badge-success';
        if($_status_ == 'APPROVED')
            $_status_label_ = 'badge-success';
        if($_status_ == 'ACCEPTED')
            $_status_label_ = 'badge-info';
        if($_status_ == 'DENIED')
            $_status_label_ = 'badge-danger';
        if($_status_ == 'EXPIRED')
            $_status_label_ = 'badge-default';

       
	?>
						
						<tr class="gradeX">
							<td>
								<span style="color: #3c8dbc; border-left: 2px solid #3c8dbc; padding: 3px; font-size: 12px;"> <?= "". $count_;?> </span>
							</td>
							<td><?= $slot_->participation_type_name?> 		</td>
                            <!-- <td><?= $slot_->participation_sub_type_name . ' '. Functions::getEventCategory($slot_->participation_sub_type_category) ?>         </td> -->
							<td><?= $slot_->participation_sub_type_name ?> 		</td>
							<td><?= $slot_->payment_state ?> 		</td>
							<td><?= $slot_->maximum_delegates ?> 		</td>
							<td><?= $slot_->registered_delegates?> 		</td>
							<td><?= number_format($slot_->unit_price) ?> </td>
							<td><?= number_format($slot_->total_price) ?> </td>
							<td><?= $slot_->currency ?> </td>
                            <td>
                                <button class="btn-white btn-bitbucket" data-toggle="tooltip" data-placement="top"
                                    title="Copy to clipboard" onclick="copyToClipboard('#tooltip_<?=$slot_->id?>', <?=$slot_->id?>)">
                                    <i class="fa fa-copy text-info" data-link="<?=$generated_link = $url_registration.'/register/group/invitation/'.Hash::encryptAuthToken($slot_->id)?>"
                                        id="tooltip_<?=$slot_->id?>"></i>
                                        <small id="alert_<?=$slot_->id?>" style="color: #23c6c8;"></small>
                                </button>
                            </td>
							<td><span class="label <?= $_status_label_ ?>" style="display: block;"><?=$_status_ ?></span></td>
							<td class="text-center">                                
<?php
if($_GROUP_DATA_->status == 'APPROVED' && $slot_->maximum_delegates > $slot_->registered_delegates):
    $generated_link = $url_registration.'/register/group/invitation/'.Hash::encryptAuthToken($slot_->id);
    // if ($slot_->participation_type_id != 33) {
?>
                                <a data-toggle="modal" data-target="#generateLinkModal" class="badge badge-info" style="text-transform: uppercase;"><i class="fa fa-paper-plane"></i> Invite member</a> or 
                                <a href="<?=$generated_link?>" class="badge badge-info" style="text-transform: uppercase;" target="_blank"> <i class="fa fa-pencil"></i> Register  Member</a>

                                <div class="modal inmodal fade" id="generateLinkModal" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                <h4 class="modal-title">Send private link</h4>
                                            </div>
                                            <form  action="<?php linkto("pages/groups/controller_action.php"); ?>" method="post" class="formCustom modal-form" id="addLinkForm">
                                                <div class="modal-body">
                                                    <div id="add-link-messages"></div>
                                                    <p>All <small class="red-color">*</small> fields are mandatory</p>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group col-md-6">
                                                                <label>First name<small class="red-color">*</small></label>
                                                                <input type="text" name="firstname" id="firstname" placeholder="First name" class="form-control" data-rule="required" data-msg="Please enter first name"/>
                                                                <div class="validate"></div>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label>Last name<small class="red-color">*</small></label>
                                                                <input type="text" name="lastname" id="lastname" placeholder="Last name" class="form-control" data-rule="required" data-msg="Please enter last name"/>
                                                                <div class="validate"></div>
                                                            </div>
                                                            <div class="form-group col-md-12">
                                                                <label>Email<small class="red-color">*</small></label>
                                                                <input type="text" name="email" id="email" placeholder="Email address" class="form-control" data-rule="required" data-msg="Please enter email address"/>
                                                                <div class="validate"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <input type="hidden" name="request" value="sendGroupPrivateLink"/> 
                                                    <input type="hidden" name="slotId" value="<?=Hash::encryptAuthToken($slot_->id) ?>"/>
                                                    <input type="hidden" name="groupId" value="<?=Hash::encryptAuthToken($_GROUP_ID_) ?>"/>
                                                    <input type="hidden" name="eventId" value="<?=Hash::encryptAuthToken($eventId) ?>"/>
                                                    <input type="hidden" name="group_name" value="<?=$_GROUP_NAME_ ?>"/>
                                                    <input type="hidden" name="group_admin_name" value="<?=$_GROUP_ADMIN_NAME_ ?>"/>
                                                    <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                                                    <button type="submit" id="addLinkButton" class="btn btn-primary" data-loading-text="Loading..." autocomplete="off"><i class="fa fa fa-external-link"></i> Send link</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
<?php
    // } else {
?>
                                <!-- <a href="https://apacongress.torusguru.com/registration/event/inperson/3236363839343530313636" class="label <?= $_status_label_ ?>" style="display: block;" target="_blank"> <i class="fa fa-user-plus"></i> Register  Delegate <i class="fa fa-arrow-right"></i> </a> -->
<?php
    // }
elseif($slot_->maximum_delegates <= $slot_->registered_delegates):
    ?>
                                <a  class="badge badge-danger" style="cursor: not-allowed; text-transform: uppercase; opacity: .5;" data-toggle="tooltip" data-placement="top" title="Maximum number of delegates under this category has already been registered"> <i class="fa fa-paper-plane"></i> Invite Member</a>
<?php
else:
    ?>
                                <a  class="badge badge-danger" style="cursor: not-allowed; text-transform: uppercase; opacity: .5;" data-toggle="tooltip" data-placement="top" title="Group Registration not yet approved"> <i class="fa fa-paper-plane"></i> Invite Member</a>
<?php
endif;
?>
                        </td>
						</tr>
<?php 
				endforeach;
	?>
					</tbody>
				</table>
<?php 
			endif;
	?>
                        
                        </div>
                        
                    </div>
                </div>
                <!-- <div class="col-lg-2"></div> -->
            </div>
        </div>

        <script type="text/javascript">
            var linkto  = '<?=DN?>/pages/groups/controller_action.php';

            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            })
        </script>

        <script>
            function copyToClipboard(element, linkID) {
                var error = $('#alert_'+linkID).empty();
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val($(element).data('link')).select();
                document.execCommand("copy");
                error.html('Copied');
                $temp.remove();

                window.setTimeout(function () {
                    error.html('');
                }, 2000);
            }
        </script>

        <script src="<?=DN?>/pages/groups/new_delegate.js?v=<?=date('Y-m-d H:i:s')?>"></script>
        
        <?php include $INC_DIR . "footer.php"; ?>
        </div>
    </div>
</body>

</html>
