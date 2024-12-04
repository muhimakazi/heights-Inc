<?php
require_once "../../core/init.php"; 
if(!$user->isLoggedIn()) 
    Redirect::to('login');
  
$valid['success'] = array('success' => false, 'messages' => array());


    // $_POST = array(
    //     'Id'              => "383232323232323231",
    //     'eventId'    => "386452724a46643151372f64686f714e5a4a496d6454644f5231566e6376455446732f724c424363563741",

    //     'request' => 'denyParticipantRegistration',
    // );


/**  Generate and send link for registration */
if(Input::get('request') && Input::get('request') == 'sendGroupPrivateLink') {
        $_form_ = FutureEventController::createGroupParticipantPrivateLink();
        if($_form_->ERRORS == false):
            $valid['success']  = true;
            $valid['messages'] = "Successfully generated and sent to {$_form_->EMAIL}";    
        else:
            $valid['success']  = false;
            $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
        endif;
        echo json_encode($valid);
}


/** Load all participants table */ 
if(Input::checkInput('request', 'post', 1)):
	$_post_request_ = Input::get('request', 'post');
	switch($_post_request_):

		/** Get participant category - */
      	case 'getPartCategory':
	        $_DATA_PARTICIPATION_CATEGORY_  = FutureEventController::getAllActivePacipationCategoryByEventID($activeEventId);
	        ?>
			<option value="">Select</option>
			<?php
            foreach($_DATA_PARTICIPATION_CATEGORY_ As $_event_participation_category_ ):
              	$part_name = $_event_participation_category_->name;
              	$part_id   = $_event_participation_category_->id;
              	$payment_state = $_event_participation_category_->payment_state;
              	if ($part_id != 38 AND $part_id != 40 AND $part_id != 41 AND $payment_state != 'PAYABLE'):
        	?>
					<option value="<?=$part_id?>"><?=$part_name?></option>
			<?php
          		endif;
      		endforeach;
      	break;

      	/** Get participant sub category - */
      	case 'getPartSubCategory':
	        $part_cat_id = Input::get('part_cat_id');
	        $_DATA_PARTICIPATION_CATEGORY_  = FutureEventController::getActivePacipationSubCategoryByPartcipationTypeIDGroup($part_cat_id);
	        ?>
			<option value="">Select</option>
			<?php
	            foreach($_DATA_PARTICIPATION_CATEGORY_ As $_event_participation_category_ ):
	              	$part_name     = $_event_participation_category_->name;
	              	$part_id       = $_event_participation_category_->id;
	              	$part_category = $_event_participation_category_->category;
	              	if ($part_id != 113 AND $part_id != 114):
	        ?>
				<option value="<?=$part_id?>"><?=$part_name?></option>
			<?php
			 		endif;
	         	endforeach;
      	break;

      	/** Get participant sub category price - */
      	case 'getParticipantPrice':
	        $part_sub_cat_id = Input::get('part_sub_cat_id');
	        $_PRICE_  = FutureEventController::getPacipationCategoryPrice($part_sub_cat_id);
	        echo $_PRICE_->price;
      	break;

      	/** Submission Of The Group Registration - */
      	case 'registrationGroup':
	        $_form_ = FutureEventGroupController::requestEventRegistrationGroup();
	        if($_form_->ERRORS == false):
	            $response['status']    = 101;
	            $response['message']   = 'Group created successfully';
	            $response['authToken'] = $_form_->AUTHTOKEN;
	        else:
	         	 $response['status'] = 400;
	          	$response['message']= $_form_->ERRORS_STRING;
	        endif;
	        echo json_encode($response);
     	 break;

		/** Action - Approve the participant Registration */
		case 'approveGroupRegistrationRequest':
			$_form_ = FutureEventGroupController::approvalGroupRegistration('ACCEPTED');
            if($_form_->ERRORS == false):
                $valid['success']  = true;
                $valid['messages'] = "Successfully, group registration request accepted"; 
            else:
                $valid['success']  = false;
                $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
            endif;
            echo json_encode($valid);
		break;
		
		/** Action - Deny the participant Registration */
		case 'declineGroupRegistrationRequest':
			$_form_ = FutureEventGroupController::approvalGroupRegistration('DECLINED');
            if($_form_->ERRORS == false):
                $valid['success']  = true;
                $valid['messages'] = "Successfully, group registration request declined";    
            else:
                $valid['success']  = false;
                $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
            endif;
            echo json_encode($valid);
		break;

		/** Table - Display the list of Participant Registered */
		case 'fetchGroupsList':
			/** Filter Condition */
			$_filter_condition_ = "";

			/** Local CBO Code  */
			$_CBO_CODE_ = 'C0015';

			/** Filter By Participation Type */
			// $_PARTICIPATION_TYPE_TOKEN_    = Input::get('type', 'post');
			// $_PARTICIPATION_SUBTYPE_TOKEN_ = Input::get('subtype', 'post');
			$_APPROVAL_STATUS_ 			   = Input::get('status', 'post');

			if($_APPROVAL_STATUS_ != ''):
				$_filter_condition_    .= " AND future_group_registration.status = '$_APPROVAL_STATUS_' ";
			endif;

			// if($_PARTICIPATION_TYPE_TOKEN_ != '' ):
			// 	$_TYPE_ID_	 				= Hash::decryptToken($_PARTICIPATION_TYPE_TOKEN_);
			// 	if(is_integer($_TYPE_ID_))
			// 		$_filter_condition_    .= " AND future_participants.participation_type_id = $_TYPE_ID_ ";
			// endif;
			
			// if($_PARTICIPATION_SUBTYPE_TOKEN_ != '' ):
			// 	$_SUBTYPE_ID_   	 	    = Hash::decryptToken($_PARTICIPATION_SUBTYPE_TOKEN_);
			// 	if(is_integer($_SUBTYPE_ID_))
			// 		$_filter_condition_    .= " AND future_participants.participation_sub_type_id = $_SUBTYPE_ID_ ";
			// endif;

			echo $_filter_condition_;

			$_LIST_DATA_ = FutureEventGroupController::getGroupList($eventId, $_filter_condition_);

			if (!$_LIST_DATA_):
				Danger("No group recorded");
			else: 
	?>
				<table class="table dataTables-example customTable">
					<thead>
						<tr>
							<th>#ID</th>
							<th>Group ID</th>
							<th>Group Name</th>
							<th>Maximum Delegates</th>
							<th>Registered Delegates</th>
							<!-- <th>Payment State</th> -->
							<th>Group Admin</th>
							<th>Email</th>
							<th>Telephone</th>
							<th>Creation Date</th>
							<th>Status</th>
							<!-- <th class="text-center">Action</th> -->
						</tr>
					</thead>
					<tbody>
<?php 
				$count_ = 0;
				foreach( $_LIST_DATA_ as $group_): $count_++;

				
					$_status_ = $group_->status;
					$_status_label_ = 'badge-warning';
			
					if($_status_ == 'COMPLETED' || $_status_ == 'USED')
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
								<span style="color: #3c8dbc; border-left: 2px solid #3c8dbc; padding: 3px; font-size: 12px;"> <?= $group_->id ?> </span>
							</td>
							<td><?= $group_->group_code ?> </td>
							<td><?= $group_->group_name ?> 		</td>
							<td><?=FutureEventGroupController::getStatsTotalMaxDelegateSlots($eventId, $group_->id)?></td>
							<td><?=FutureEventGroupController::getStatsTotalDelegateRegistered($eventId, $group_->id, 'ALL')?></td>
							<td><?= $group_->firstname ?> <?=$group_->lastname?> </td>
							<td><?= $group_->email ?> </td>

							<td><?= $group_->telephone ?> </td>
							<td><?= date('d/m/Y h:i A', $group_->request_datetime) ?> </td>
							<td><span class="badge <?= $_status_label_ ?>" style="display: block;"><?=$_status_ ?></span></td>
							<!-- <td>
								<div class="ibox-tools">
									<a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color: #3c8dbc;">More</a>
									<ul class="dropdown-menu dropdown-user popover-menu-list">

<?php 
							if($group_->status == 'PENDING'):
	?>
												<li><a class="menu block_user" data-toggle="modal" data-target="#activateModal<?=Hash::encryptToken($group_->id)?>" ><i class="fa fa-check icon"></i> Accept</a></li>

<?php 			
							endif;
	
							if($group_->status == 'PENDING'):
							
	?>
												<li><a class="menu block_user"  data-toggle="modal" data-target="#deactivateModal<?=Hash::encryptToken($group_->id)?>" ><i class="fa fa-remove icon"></i> Deny</a></li>
<?php 
							endif;
			
	?>

									</ul>
								</div>
							</td> -->
						</tr>
<?php 
				endforeach;
	?>
					</tbody>
				</table>
<?php 
			endif;
	?>
				
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
		break;


		
		/** Table - Display the list of Participant Registered */
		case 'fetchGroupDelegates':
			/** Filter Condition */
			$_filter_condition_ = " AND future_participants.group_id > 0 ";

			/** Local CBO Code  */
			$_CBO_CODE_ = 'C0015';

			/** Filter By Participation Type */
			$_PARTICIPATION_TYPE_TOKEN_    = Input::get('type', 'post');
			$_PARTICIPATION_SUBTYPE_TOKEN_ = Input::get('subtype', 'post');
			$_APPROVAL_STATUS_ 			   = Input::get('status', 'post');

			if($_APPROVAL_STATUS_ != ''):
				$_filter_condition_    .= " AND future_participants.status = '$_APPROVAL_STATUS_' ";
			endif;

			if($_PARTICIPATION_TYPE_TOKEN_ != '' ):
				$_TYPE_ID_	 				= Hash::decryptToken($_PARTICIPATION_TYPE_TOKEN_);
				if(is_integer($_TYPE_ID_))
					$_filter_condition_    .= " AND future_participants.participation_type_id = $_TYPE_ID_ ";
			endif;
			
			if($_PARTICIPATION_SUBTYPE_TOKEN_ != '' ):
				$_SUBTYPE_ID_   	 	    = Hash::decryptToken($_PARTICIPATION_SUBTYPE_TOKEN_);
				if(is_integer($_SUBTYPE_ID_))
					$_filter_condition_    .= " AND future_participants.participation_sub_type_id = $_SUBTYPE_ID_ ";
			endif;

			$_LIST_DATA_ = FutureEventController::getParticipantsByEventID($eventId, $_filter_condition_);

			if (!$_LIST_DATA_):
				Danger("No group delegate recorded");
			else: 
	?>
				<table class="table dataTables-example customTable">
					<thead>
						<tr>
							<th>Reg. Number</th>
							<th>Full name</th>
							<th>Type</th>
							<th>Subtype</th>
							<!-- <th>Category</th> -->
							<th>Job Title</th>
							<th>Country</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
<?php 
				$count_ = 0;
				foreach( $_LIST_DATA_ as $participant_): $count_++;

					$_accommodation_ 	   = $participant_->need_accommodation_state?'Yes':'No';
					$_accommodation_label_ = 'label-default';

					if($participant_->need_accommodation_state == 1)
						$_accommodation_label_ = 'label-dark';
				
					$_status_ = $participant_->status;
					$_status_label_ = 'badge-warning';
			
					if($_status_ == 'COMPLETED' || $_status_ == 'USED')
						$_status_label_ = 'badge-success';
					if($_status_ == 'APPROVED')
						$_status_label_ = 'badge-success';
					if($_status_ == 'ACCEPTED')
						$_status_label_ = 'badge-info';
					if($_status_ == 'DENIED')
						$_status_label_ = 'badge-danger';
					if($_status_ == 'EXPIRED')
						$_status_label_ = 'badge-default';

					/** Get Group Name */
					$_group_name_ = FutureEventGroupController::getGroupName($participant_->event_id, $participant_->group_id);
	?>
						
						<tr class="gradeX">
							<td>
								<span style="color: #3c8dbc; border-left: 2px solid #3c8dbc; padding: 3px; font-size: 12px;"> <?= $participant_->participant_code?> </span>
							</td>
							<td><?= $participant_->firstname .' '. $participant_->lastname ?> </td>
							<td><?= $participant_->participation_type_name ?> </td>
							<td><?= $participant_->participation_subtype_name ?> </td>
							<!-- <td><?= $participant_->participation_subtype_category ?> </td> -->
<!--							<td><?= $participant_->payment_state ?> 		</td>-->
<!--
							<td><?= $participant_->participation_subtype_price ?> <small><?=$participant_->participation_subtype_currency?></small> </td>
							<td><?= $participant_->gender ?> </td>
-->
							<td><?= $participant_->job_title ?> </td>
							<td><?= $participant_->residence_country ?> </td>
							<td><span class="badge <?= $_status_label_ ?>" style="display: block;"><?=$_status_ ?></span></td>
						</tr>
<?php 
				endforeach;
	?>
					</tbody>
				</table>
<?php 
			endif;
	?>
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
		break;


		/** Table - Display the list of Participant Registered */
		case 'fetchGroupDelegatesForGroupAdmin':
			/** Filter Condition */
			$_filter_condition_ = " AND future_participants.group_id = $_GROUP_ID_";

			/** Local CBO Code  */
			$_CBO_CODE_ = 'C0015';

			/** Filter By Participation Type */
			// $_PARTICIPATION_TYPE_TOKEN_    = Input::get('type', 'post');
			// $_PARTICIPATION_SUBTYPE_TOKEN_ = Input::get('subtype', 'post');
			// $_APPROVAL_STATUS_ 			   = Input::get('status', 'post');

			// if($_APPROVAL_STATUS_ != ''):
			// 	$_filter_condition_    .= " AND future_participants.status = '$_APPROVAL_STATUS_' ";
			// endif;

			// if($_PARTICIPATION_TYPE_TOKEN_ != '' ):
			// 	$_TYPE_ID_	 				= Hash::decryptToken($_PARTICIPATION_TYPE_TOKEN_);
			// 	if(is_integer($_TYPE_ID_))
			// 		$_filter_condition_    .= " AND future_participants.participation_type_id = $_TYPE_ID_ ";
			// endif;
			
			// if($_PARTICIPATION_SUBTYPE_TOKEN_ != '' ):
			// 	$_SUBTYPE_ID_   	 	    = Hash::decryptToken($_PARTICIPATION_SUBTYPE_TOKEN_);
			// 	if(is_integer($_SUBTYPE_ID_))
			// 		$_filter_condition_    .= " AND future_participants.participation_sub_type_id = $_SUBTYPE_ID_ ";
			// endif;

			$_LIST_DATA_ = FutureEventController::getParticipantsByEventID($eventId, $_filter_condition_);

			if (!$_LIST_DATA_):
				Danger("No group delegate recorded");
			else: 
	?>
				<table class="table dataTables-example customTable">
					<thead>
						<tr>
							<th>#ID</th>
							<th>Group Name</th>
							<th>Full name</th>
							<th>Type</th>
							<th>Subtype</th>
							<!-- <th>Category</th> -->
							<th>Organization</th>
							<th>Job Type</th>
							<th>Country</th>
							<!-- <th>Accommodation</th> -->
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
<?php 
				$count_ = 0;
				foreach( $_LIST_DATA_ as $participant_): $count_++;

					$_accommodation_ 	   = $participant_->need_accommodation_state?'Yes':'No';
					$_accommodation_label_ = 'badge-default';

					if($participant_->need_accommodation_state == 1)
						$_accommodation_label_ = 'badge-dark';
				
					$_status_ = $participant_->status;
					$_status_label_ = 'badge-warning';
			
					if($_status_ == 'COMPLETED' || $_status_ == 'USED')
						$_status_label_ = 'badge-success';
					if($_status_ == 'APPROVED')
						$_status_label_ = 'badge-success';
					if($_status_ == 'ACCEPTED')
						$_status_label_ = 'badge-info';
					if($_status_ == 'DENIED')
						$_status_label_ = 'badge-danger';
					if($_status_ == 'EXPIRED')
						$_status_label_ = 'badge-default';

					/** Get Group Name */
					$_group_name_ = FutureEventGroupController::getGroupName($participant_->event_id, $participant_->group_id);
		

	?>
						
						<tr class="gradeX">
							<td>
								<span style="color: #3c8dbc; border-left: 2px solid #3c8dbc; padding: 3px; font-size: 12px;"> <?=$participant_->participant_code;?> </span>
							</td>
							<td><?= $_group_name_?> 		</td>
							<td><?= $participant_->firstname .' '. $participant_->lastname   ?> </td>
							<td><?= $participant_->participation_type_name ?> 		</td>
							<td><?= $participant_->participation_subtype_name ?> 		</td>
							<!-- <td><?= $participant_->participation_subtype_category ?> 		</td> -->
<!--							<td><?= $participant_->payment_state ?> 		</td>-->
<!--
							<td><?= $participant_->participation_subtype_price ?> <small><?=$participant_->participation_subtype_currency?></small> </td>
							<td><?= $participant_->gender ?> </td>
-->
							<td><?= $participant_->organisation_name ?> </td>
							<td><?= $participant_->job_title ?> </td>
							<td><?= $participant_->residence_country ?> </td>
							<!-- <td><span class="badge <?= $_accommodation_label_ ?>" style="display: block;"><?=$_accommodation_ ?></span></td> -->
							<td><span class="badge <?= $_status_label_ ?>" style="display: block;"><?=$_status_ ?></span></td>
							
						</tr>
<?php 
				endforeach;
	?>
					</tbody>
				</table>
<?php 
			endif;
	?>
				
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
		break;

		case 'fetchGeneratedLinks':

        /** Load List Of Generated Links */
            $_LIST_DATA_ = FutureEventGroupController::getGroupGeneratedPrivateLinks($eventId, "");

            if (!$_LIST_DATA_) {
                Danger("No link recorded");
            } else {
    ?>
                        <table class="table dataTables-example customTable">
                            <thead>
                                <tr>
                                    <th>#ID</th>
                                    <th>Group name</th>
                                    <th>First name</th>
                                    <th>Last name</th>
                                    <th>Email</th>
                                    <th>Participation Type</th>
                                    <th>Generated time</th>
                                    <th>Registration time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
<?php
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
?>
                                <tr class="gradeX">
                                    <td>
                                        <span style="color: #3c8dbc; border-left: 2px solid #3c8dbc; padding: 3px; font-size: 12px;">
                                            <?="". $count_;?>
                                        </span>
                                    </td>
                                    <td><?=$_link_->group_name?></td>
                                    <td><?=$_link_->firstname?></td>
                                    <td><?=$_link_->lastname?></td>
                                    <td><?=$_link_->email?></td>
                                    <td><?=$_link_->type_name .'  '.$_link_->sub_type_name.' '.$_link_->sub_type_category?></td>
                                    <td><?=date('d-M-Y', $_link_->access_generated_time)?></td>
                                    <td><?=date('d-M-Y', $_link_->access_generated_time)?></td>
                                    <td><span class="badge <?= $_status_label_ ?>" style="display: block;"><?=$_status_ ?></span></td>
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


		/** Filter Subtype By Type */
		case 'filterParticipationSubType':
			$_TYPE_ID_   = Hash::decryptToken(Input::get('type', 'post'));
			$_TYPE_DATA_ = FutureEventController::getPacipationSubType($eventId, $_TYPE_ID_);
?>
			<option value="">- Select Participation Subtype -</option>
			<option value="">All</option>
<?php
			if($_TYPE_DATA_):
				foreach($_TYPE_DATA_ As $type_):
?>  
												<option value="<?=Hash::encryptToken($type_->id)?>"><?=$type_->name.' '.($type_->name == ''?'':'/ ')?> <?=$type_->category?></option>
<?php
				endforeach;
			endif;

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
	endswitch;
endif;		
	
?>


