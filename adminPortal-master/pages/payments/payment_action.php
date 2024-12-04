<?php
require_once "../../core/init.php"; 
if(!$user->isLoggedIn()) 
    Redirect::to('login');
  
$response['success'] = array('success' => false, 'messages' => array());

/** Load all participants table */ 
if(Input::checkInput('request', 'post', 1)):
	$_post_request_ = Input::get('request', 'post');
	switch($_post_request_):

		/** Action - Approve the participant Registration */
		case 'delayedPaymentReminder':
			$_form_ = PaymentController::sendPaymentReminder();
            if($_form_->ERRORS == false):
                $response['success']  = true;
                $response['messages'] = "Successfully, payment reminder sent";    
            else:
                $response['success']  = false;
                $response['messages'] = "Error {$_form_->ERRORS_STRING}";
            endif;
            echo json_encode($response);
		break;

		/** Action - Approve the participant Registration */
		case 'approveBankTransferTransaction':
			$_form_ = PaymentController::updateBankTransferPaymentTransactionEntry('APPROVED');
            if($_form_->ERRORS == false):
                $response['success']  = true;
                $response['messages'] = "Successfully, bank transfer and participant's registration approved";    
            else:
                $response['success']  = false;
                $response['messages'] = "Error {$_form_->ERRORS_STRING}";
            endif;
            echo json_encode($response);
		break;
		
		/** Action - Deny the participant Registration */
		case 'declinedBankTransferTransaction':
			$_form_ = PaymentController::updateBankTransferPaymentTransactionEntry('DENIED');
            if($_form_->ERRORS == false):
                $response['success']  = true;
                $response['messages'] = "Successfully, participant's registration denied";    
            else:
                $response['success']  = false;
                $response['messages'] = "Error {$_form_->ERRORS_STRING}";
            endif;
            echo json_encode($response);
		break;

		/** Action - refund payment */
		case 'refundPayment':
			$_form_ = PaymentController::updateBankTransferPaymentTransactionEntry('REFUNDED');
            if($_form_->ERRORS == false):
                $response['success']  = true;
                $response['messages'] = "Successfully, participant's payment refunded";    
            else:
                $response['success']  = false;
                $response['messages'] = "Error {$_form_->ERRORS_STRING}";
            endif;
            echo json_encode($response);
		break;

		/** Table - Display the list of Participant Registered */
		case 'fetchParticitants':
			/** Filter Condition */
			$_filter_condition_ = "";

			/** Filter By Participation Type */
			$_PARTICIPATION_TYPE_TOKEN_    = Input::get('type', 'post');
			$_PARTICIPATION_SUBTYPE_TOKEN_ = Input::get('subtype', 'post');
			
			$_PAYMENT_TRANSACTION_STATUS_  = Input::get('status', 'post');
			$_PAYMENT_CHANNEL_			   = Input::get('payment_channel', 'post');

			$_COUNTRY_ 			           = Input::get('country', 'post');
			
			$_DATE_1_  = Input::get('datefrom', 'post');
			$_DATE_2_  = Input::get('dateto', 'post');

			$_DATETIME_1_ = $_DATE_1_ == ''?0:strtotime($_DATE_1_);
			$_DATETIME_2_ = $_DATE_2_ == ''?0:strtotime($_DATE_2_.' 11:59 pm');

			if($_DATE_1_ != '' OR $_DATE_2_ != ''):
				if($_DATE_1_ != '' AND $_DATE_2_ != ''):
					$_filter_condition_    .= " AND future_payment_transaction_entry.transaction_time BETWEEN $_DATETIME_1_ AND  $_DATETIME_2_ ";

				elseif($_DATE_1_ != '' AND $_DATE_2_ == ''):
					$_filter_condition_    .= " AND future_payment_transaction_entry.transaction_time >=  $_DATETIME_1_ ";
				
				elseif($_DATE_1_ == '' AND $_DATE_2_ != ''):
					$_filter_condition_    .= " AND future_payment_transaction_entry.transaction_time <=  $_DATETIME_1_ ";
				endif;
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

			if($_PAYMENT_TRANSACTION_STATUS_ != ''):
				$_filter_condition_    .= " AND future_payment_transaction_entry.transaction_status = '$_PAYMENT_TRANSACTION_STATUS_' ";
			endif;

			if($_PAYMENT_CHANNEL_ != ''):
				if($_PAYMENT_CHANNEL_ == 'ONLINE_PAYMENT')
					$_filter_condition_    .= " AND future_payment_transaction_entry.payment_method != 'BANK_TRANSFER' ";
				if($_PAYMENT_CHANNEL_ == 'BANK_TRANSFER')
					$_filter_condition_    .= " AND future_payment_transaction_entry.payment_method = 'BANK_TRANSFER' ";
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
				OR future_participants.organisation_name LIKE'%".$searchValue."%' 
				OR future_participation_type.name LIKE'%".$searchValue."%' 
				OR future_participation_sub_type.name LIKE'%".$searchValue."%')";
			}

			## Total number of records without filtering
            $totalRecords = FutureEventController::getPaymentParticipantsCounterByEventID($eventId);

            ## Total number of records with filtering
            $totalRecordwithFilter = FutureEventController::getPaymentParticipantsCounterByEventID($eventId, $_filter_condition_);

            ## Fetch records
			$order = "ORDER BY future_payment_transaction_entry.".$columnName." DESC LIMIT ".$row.",".$rowperpage;
			$partRecords = FutureEventController::getPaymentParticipantsByEventID($eventId, $_filter_condition_, $order);

			## Fetch records for data export
			$order = "ORDER BY future_payment_transaction_entry.".$columnName." DESC";
			$exportRecords = FutureEventController::getPaymentParticipantsByEventID($eventId, $_filter_condition_, $order);
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
	                    "type" => $payment_transaction_->participation_type_name,
	                    "sub_type" => $payment_transaction_->participation_subtype_name,
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
		case 'exportPaymentData':
			try{
                FutureDataExport::paymentCSVReport(Session::get('paymentExportData'));
            } catch(Exception $e){
                throw new Exception("An error occured while exporting this report");
            }
		break;

		/** Filter Subtype By Type */
		case 'filterParticipationSubType':
			$_TYPE_ID_   = Hash::decryptToken(Input::get('type', 'post'));
			$_TYPE_DATA_ = FutureEventController::getPacipationSubType($eventId, $_TYPE_ID_, 'PAYABLE');
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
	endswitch;
endif;		
	
?>


