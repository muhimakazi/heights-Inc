<?php
require_once "../../core/init.php"; 
if(!$user->isLoggedIn()) 
    Redirect::to('login');
  
$response['success'] = array('success' => false, 'messages' => array(), 'data' => array());

/** Load all participants table */ 
if(Input::checkInput('request', 'post', 1)):
	$_post_request_ = Input::get('request', 'post');
	switch($_post_request_):

		/** UPDATE PARTICIPANT CATEGORY */
		case 'changeCategory':
			$_form_ = FutureEventController::updateParticipantCategory();
            if($_form_->ERRORS == false):
                $response['success']  = true;
                $response['messages'] = "Successfully";    
            else:
                $response['success']  = false;
                $response['messages'] = "Error {$_form_->ERRORS_STRING}";
            endif;
            echo json_encode($response);
		break;

		/** Action - Approve the participant Registration */
		case 'approveParticipantRegistration':
			$_form_ = FutureEventController::changeStatusParticipantRegistration('APPROVED');
            if($_form_->ERRORS == false):
                $response['success']  = true;
                $response['messages'] = $_form_->RETURNEDMESSAGE;    
            else:
                $response['success']  = false;
                $response['messages'] = "Error {$_form_->ERRORS_STRING}";
            endif;
            echo json_encode($response);
		break;
		
		/** Action - Deny the participant Registration */
		case 'denyParticipantRegistration':
			$_form_ = FutureEventController::changeStatusParticipantRegistration('DENIED');
            if($_form_->ERRORS == false):
                $response['success']  = true;
                $response['messages'] = "Successfully, participant's registration rejected";    
            else:
                $response['success']  = false;
                $response['messages'] = "Error {$_form_->ERRORS_STRING}";
            endif;
            echo json_encode($response);
		break;

		/** Action - Accept CMPD Invite */
		case 'acceptRegistrationInvite':
			$_form_ = FutureEventController::acceptRegistrationInvite();
            if($_form_->ERRORS == false):
                $response['success']  = true;
                $response['messages'] = "Successfully accepted";    
            else:
                $response['success']  = false;
                $response['messages'] = "Error {$_form_->ERRORS_STRING}";
            endif;
            echo json_encode($response);
		break;

		/** Submission Of The Participant Registration - update profile */
      	case 'registrationUpdate':
	        $_form_ = FutureEventController::updateEventParticipantProfile();
	        if($_form_->ERRORS == false):
	           $response['success']    = true;
	           $response['messages']   = 'Details have been updated successfully';
	        else:
	          $response['success'] = false;
	          $response['messages']= $_form_->ERRORS_STRING;
	        endif;
	        echo json_encode($response);
      	break;

		/** Table - Display the list of Participant Registered */
		case 'fetchParticitants':

			/** Filter Condition */
			$_filter_condition_ = "";

			/** Local CBO Code  */
			$_CBO_CODE_ = 'C0015';

			/** Filter By Participation Type */
			$_PARTICIPATION_TYPE_TOKEN_    = Input::get('type', 'post');
			$_PARTICIPATION_SUBTYPE_TOKEN_ = Input::get('subtype', 'post');
			$_FORM_TOKEN_ = Input::get('formtype', 'post');
			$_APPROVAL_STATUS_ 			   = Input::get('status', 'post');
			$_COUNTRY_ 			           = Input::get('country', 'post');

			$_DATE_1_  = Input::get('datefrom', 'post');
			$_DATE_2_  = Input::get('dateto', 'post');

            $_TODAY= date("Y-m-d H:i:s");

			$_DATETIME_1_ = $_DATE_1_." 00:00:00";
			$_DATETIME_2_ = $_DATE_2_." 23:59:00";

			if($_DATE_1_ != '' OR $_DATE_2_ != ''):
				if($_DATE_1_ != '' AND $_DATE_2_ != ''):
					$_filter_condition_    .= " AND future_participants.reg_date BETWEEN '$_DATETIME_1_' AND  '$_DATETIME_2_'";

				elseif($_DATE_1_ != '' AND $_DATE_2_ == ''):
					$_filter_condition_    .= " AND future_participants.reg_date BETWEEN '$_DATETIME_1_' AND '$_TODAY'";
				
				elseif($_DATE_1_ == '' AND $_DATE_2_ != ''):
					$_filter_condition_    .= " AND future_participants.reg_date BETWEEN '$_TODAY' AND '$_DATETIME_2_'";
				endif;
			endif;

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

			if($_FORM_TOKEN_ != '' ):
				$_FORM_ID_   	 	    = Hash::decryptToken($_FORM_TOKEN_);
				if(is_integer($_FORM_ID_))
					$_filter_condition_    .= " AND future_participants.form_id = $_FORM_ID_ ";
			endif;

			if($_COUNTRY_ != '' ):
				if ($_COUNTRY_ == 'Local') {
					$_filter_condition_    .= " AND (future_participants.residence_country = 'RW' OR future_participants.residence_country = 'RW')";
				} else{
					$_filter_condition_    .= " AND (future_participants.residence_country != 'RW' AND future_participants.residence_country != 'RW')";
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
				OR future_participants.emergency_contact_firstname LIKE '%".$searchValue."%' 
				OR future_participants.organisation_name LIKE'%".$searchValue."%' 
				OR future_participation_type.name LIKE'%".$searchValue."%' 
				OR future_participation_sub_type.name LIKE'%".$searchValue."%')";
			}

			## Total number of records without filtering
            $totalRecords = FutureEventController::getTotalParticipantsCounterByFilter($eventId);

            ## Total number of records with filtering
            $totalRecordwithFilter = FutureEventController::getTotalParticipantsCounterByFilter($eventId, $_filter_condition_);

            ## Fetch records
			$order = "ORDER BY future_participants.".$columnName." DESC LIMIT ".$row.",".$rowperpage;
			$partRecords = FutureEventController::getTotalParticipantsByFilter($eventId, $_filter_condition_, $order);

			// Fetch records for data export
			$order = "ORDER BY future_participants.".$columnName." DESC";
			$exportRecords = FutureEventController::getTotalParticipantsByFilter($eventId, $_filter_condition_, $order);
            Session::put('registrationExportData', $exportRecords);

			if ($partRecords) {
				$count_ = 0;
				foreach($partRecords as $participant_) {
					$count_++;

					// STATUS
					$_status_ = $participant_->status;
					$_status_label_ = 'badge-warning';
					if($_status_ == 'COMPLETED' || $_status_ == 'USED')
						$_status_label_ = 'badge-success';
					if($_status_ == 'APPROVED')
						$_status_label_ = 'badge-success';
					if($_status_ == 'ACCEPTED')
						$_status_label_ = 'badge-info';
					if($_status_ == 'DENIED' || $_status_ == 'REFUNDED' || $_status_ == 'REJECTED')
						$_status_label_ = 'badge-danger';
					if($_status_ == 'EXPIRED')
						$_status_label_ = 'badge-default';

					//INVITATION
					if($_status_ == 'PENDING' AND $participant_->invitation_status == 'PENDING') {
						$_status_label_ = 'badge-info';
						$_status_ = 'INVITED';
					}

					$partProfile = DN.'/participants/profile/'.Hash::encryptToken($participant_->id);
					$type_name = $participant_->participation_type_name;
					$subtype_name = $participant_->participation_subtype_name;

					// PASS TYPE
					if($eventId == 13) {
						if ($type_name == 'CMPD') {
							$pass_type = $type_name." ".$subtype_name;
						} elseif ($type_name == 'Industry' || $type_name == 'FinTech Start-up' || $subtype_name == 'Application' || $subtype_name == 'Research' || $subtype_name == 'The Careers Forum') {
							$pass_type = $type_name;
						} elseif ($subtype_name == 'Government Official (Organising Team)') {
							$pass_type = 'Government Official';
						} else {
							$pass_type = $subtype_name;
						}
					} else {
						$pass_type = $subtype_name;
					}

					// TEMPLATE FORM
					if (is_null($pass_type)) {
						$pass_type = $participant_->form_name;
					}

					// COUNTRY
					$participant_country_ = countryCodeToCountry($participant_->residence_country);

					// ACTION
					$noGuestPermission = $user->hasPermission('guest')?'none' :''; // FOR GUEST
					if (FutureEventController::checkIfEventHasEnded($eventId)){
						// $noGuestPermission = 'none';
					}

					$edit_key = Hash::encryptToken($participant_->id);
					$part_name = $participant_->firstname .' '. $participant_->lastname;
					$action = "
					<div class='ibox-tools'>
	                    <a class='dropdown-toggle' data-toggle='dropdown' href='#' style='color: #3c8dbc;'>More</a>
	                    <ul class='dropdown-menu dropdown-user popover-menu-list' style='display: $noGuestPermission;'>";
                        	$action .= "<li><a class='menu edit_client' href='$partProfile' target='_blank'><i class='fa fa-eye icon'></i> View Profile</a></li>"; // PROFILE
                        	$action .= "<li><a class='menu edit_profile' data-id='$edit_key' data-name='$part_name'><i class='fa fa-pencil'></i> Edit Profile</a></li>"; // PROFILE

                    		if($participant_->status != 'APPROVED' AND $participant_->status != 'ACCEPTED' AND $participant_->participation_sub_type_id != 113):
								if(($participant_->payment_state == 'FREE') || ($participant_->payment_state == 'PAYABLE' AND $participant_->participation_type_code == $_CBO_CODE_) || $participant_->group_id > 0):
  									$action .= "<li><a class='menu update_delegate_status' data-id='$edit_key' data-name='$part_name' data-action='Approve'><i class='fa fa-check icon'></i> Approve</a></li>";
  								endif;
  							endif; // Approve

  							if($participant_->status != 'DENIED'):
								if(($participant_->payment_state == 'FREE') || ($participant_->payment_state == 'PAYABLE' AND $participant_->participation_type_code == $_CBO_CODE_)):
									$action .= "<li><a class='menu update_delegate_status' data-id='$edit_key' data-name='$part_name' data-action='Reject' style='color: red;'><i class='fa fa-remove icon'></i> Reject</a></li>";
								endif;
							endif; // Reject

							if($user->hasPermission('admin')):
                        		$action .= "<li><a class='menu update_category' data-id='$edit_key' data-name='$part_name' style='color: orange;'><i class='fa fa-arrows icon'></i> Change category</a></li>";
							endif;

							if($user->data()->id == 16):
                        		$action .= "<li><a class='menu update_delegate_status' data-id='$edit_key' data-name='$part_name' data-action='Delete' style='color: red;'><i class='fa fa-trash icon'></i> Delete</a></li>";
                        		$action .= "<li><a class='menu update_delegate_status' data-id='$edit_key' data-name='$part_name' data-action='Approve'><i class='fa fa-check icon'></i> Approve</a></li>";
							endif; // Delete
  

	                	$action .= 
	                    "</ul>
                	</div>";

	                $data[] = array(
	                	"id" => $count_,
	                	"reg_date" => date("j F Y", strtotime($participant_->reg_date)),
	                    "firstname"     => '<a href="'.$partProfile.'" target="_blank">'.$part_name.'</a>',
	                    // "emergency_contact_firstname" => $participant_->emergency_contact_firstname,
	                    "participation_sub_type_id" => $pass_type,
	                    "job_title" => $participant_->job_title,
	                    "organisation_name" => $participant_->organisation_name,
	                    "organisation_type" => $participant_->job_category,
	                    "residence_country"  => $participant_country_,
	                   	"status"  => "<span class='badge {$_status_label_}' style='display: block;'>{$_status_}</span>",
	                   	"action"  => $action."
	                   	<span id='title$edit_key' style='display: none;'>{$participant_->title}</span>
	                   	<span id='firstname$edit_key' style='display: none;'>{$participant_->firstname}</span>
	                   	<span id='lastname$edit_key' style='display: none;'>{$participant_->lastname}</span>
	                   	<span id='email$edit_key' style='display: none;'>{$participant_->email}</span>
	                   	<span id='telephone$edit_key' style='display: none;'>{$participant_->telephone}</span>
	                   	<span id='organisation_name$edit_key' style='display: none;'>{$participant_->organisation_name}</span>
	                   	<span id='job_title$edit_key' style='display: none;'>{$participant_->job_title}</span>
	                   	<span id='residence_country$edit_key' style='display: none;'>{$participant_->residence_country}</span>
	                   	<span id='id_type$edit_key' style='display: none;'>{$participant_->id_type}</span>
	                   	<span id='id_number$edit_key' style='display: none;'>{$participant_->id_number}</span>
	                   	"
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

		/** Action - Export Registration data */
		case 'exportRegistrationData':
			try{
                FutureDataExport::registrationCSVReport(Session::get('registrationExportData'));
            } catch(Exception $e){
                throw new Exception("An error occured while exporting this report");
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
<!-- <option value="<?=Hash::encryptToken($type_->id)?>"><?=$type_->name.' '.($type_->name == ''?'':'/ ')?> <?=$type_->category?></option> -->
<option value="<?=Hash::encryptToken($type_->id)?>"><?=$type_->name?></option>
<?php
				endforeach;
			endif;

		break;


		/** Filter Subtype By Type */
		case 'updateParticipationSubType':
			$_TYPE_ID_   = Hash::decryptToken(Input::get('type', 'post'));
			$_TYPE_DATA_ = FutureEventController::getPacipationSubType($eventId, $_TYPE_ID_);
?>
<option value="">- Select Participation Subtype -</option>
<?php
			if($_TYPE_DATA_):
				foreach($_TYPE_DATA_ As $type_):
					if ($type_->id != 113 AND $type_->status != 'DEACTIVE'):
?>
<option value="<?=Hash::encryptToken($type_->id)?>"><?=$type_->name?></option>
<?php
					endif;
				endforeach;
			endif;

		break;

		// Delete participant
		case 'deleteParticipantRegistration':
			try {
				$_PID_ = Hash::decryptToken(Input::get('Id', 'post'));
	            $controller->delete('future_participants', array('id', '=', $_PID_));
	            $response['success'] = true;
				$response['messages'] = "Successfully deleted";	
	        } catch(Exception $error) {
	            $response['success'] = false;
				$response['messages'] = "Error while deleting";
	        }
			echo json_encode($response);	
		break;
	endswitch;
endif;		
	
?>