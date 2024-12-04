<?php
class FormController

{
	public static function createForm()
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'form-';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}

		$validation = $validate->check($_EDIT, array(

		));

		if ($validate->passed()) {
			$controller = new \Controller();

			$str = new \Str();

			$form_name = $str->data_in($_EDIT['form_name']);
			$publish_type = $str->data_in($_EDIT['publish_type']);
			$form_order = $str->data_in($_EDIT['form_order']);
			$form_note = $str->data_in($_EDIT['form_note']);
			$registration_email_message = $str->data_in($_EDIT['registration_email_message']);
			$registration_email_subject = $str->data_in($_EDIT['registration_email_subject']);
			$approval_email_message = $str->data_in($_EDIT['approval_email_message']);
			$approval_email_subject = $str->data_in($_EDIT['approval_email_subject']);
			$eventId = Hash::decryptAuthToken($str->data_in($_EDIT['eventId']));

			/** Check If Room Exitst  */
			if (self::checkIfFormExists($form_name)) :
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "This form is already registered",
					'ERRORS_STRING' => "This form is already registered"
				];
			endif;

			$created_by = Session::get(Config::get('sessions/session_name'));

			$_ID = self::getLastID('form_list') + 1;

			if ($diagnoArray[0] == 'NO_ERRORS') {

				$_fields = array(
	                'event_id'     => $eventId,
	                'form_name'    => $form_name,
	                'publish_type' => $publish_type,
	                'form_order'   => $form_order,
	                'form_note'    => $form_note,
	                'registration_email_message'=> $registration_email_message,
	                'registration_email_subject' => $registration_email_subject,
	                'approval_email_message' => $approval_email_message,
	                'approval_email_subject' => $approval_email_subject,
	                'created_by'   => $created_by,
	                'created_date' => date('Y-m-d H:i:s')
	            );

				try {
					$controller->create("form_list", $_fields);
					$controller = new \Controller();
					$controller->create("form_content", array('form_id' => $_ID));
					// UPDATING LOGS
					$operation = 'NEW FORM';
					$comment = "FORM ID: ".$_ID;
					Logs::newLog($operation, $comment, $created_by);
				}
				catch (Exception $e) {
					$diagnoArray[0] = "ERRORS_FOUND";
					$diagnoArray[] = $e;
				}
			}
		} else {
			foreach ($validate->errors() as $error) {
                $diagnoArray[] = $error . "<br>";
            }
            $diagnoArray[0] = 'ERRORS_FOUND';
		}
		if ($diagnoArray[0] == 'ERRORS_FOUND') {
			return (object)[
				'ERRORS' => true,
				'ERRORS_SCRIPT' => $validate->getErrorLocation(),
				'ERRORS_STRING' => json_encode($diagnoArray),
			];
		} else {
			return (object)[
				'ERRORS' => false,
				'SUCCESS' => true,
				'ERRORS_SCRIPT' => "",
				'SUCCESS_STRING' => "Form successfully created",
				'ERRORS_STRING' => ""
			];
		}
	}

	public static function updateForm() // EDIT FORM
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'form-';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}

		$validation = $validate->check($_EDIT, array(

		));

		if ($validate->passed()) {
			$controller = new \Controller();

			$str = new \Str();

			$_ID = Hash::decryptToken($str->data_in($_EDIT['Id']));
			$form_name = $str->data_in($_EDIT['form_name']);
			$publish_type = $str->data_in($_EDIT['publish_type']);
			$form_order = $str->data_in($_EDIT['form_order']);
			$form_note = $str->data_in($_EDIT['form_note']);
			$registration_email_message = $str->data_in($_EDIT['registration_email_message']);
			$registration_email_subject = $str->data_in($_EDIT['registration_email_subject']);
			$approval_email_message = $str->data_in($_EDIT['approval_email_message']);
			$approval_email_subject = $str->data_in($_EDIT['approval_email_subject']);

			$created_by = Session::get(Config::get('sessions/session_name'));

			if ($diagnoArray[0] == 'NO_ERRORS') {

				$_fields = array(
	                'form_name'    => $form_name,
	                'publish_type' => $publish_type,
	                'form_order'   => $form_order,
	                'form_note'    => $form_note,
	                'registration_email_message'=> $registration_email_message,
	                'registration_email_subject' => $registration_email_subject,
	                'approval_email_message' => $approval_email_message,
	                'approval_email_subject' => $approval_email_subject,
	                'updated_by'   => $created_by,
	            );

				try {
					$controller->update("form_list", $_fields, $_ID);
					// UPDATING LOGS
					$operation = 'UPDATE_FROM';
					$comment = "FORM ID: ".$_ID;
					Logs::newLog($operation, $comment, $created_by);
				}
				catch (Exception $e) {
					$diagnoArray[0] = "ERRORS_FOUND";
					$diagnoArray[] = $e;
				}
			}
		} else {
			foreach ($validate->errors() as $error) {
                $diagnoArray[] = $error . "<br>";
            }
            $diagnoArray[0] = 'ERRORS_FOUND';
		}
		if ($diagnoArray[0] == 'ERRORS_FOUND') {
			return (object)[
				'ERRORS' => true,
				'ERRORS_SCRIPT' => $validate->getErrorLocation(),
				'ERRORS_STRING' => json_encode($diagnoArray),
			];
		} else {
			return (object)[
				'ERRORS' => false,
				'SUCCESS' => true,
				'ERRORS_SCRIPT' => "",
				'SUCCESS_STRING' => "Form successfully updated",
				'ERRORS_STRING' => ""
			];
		}
	}

	public static function changeFormStatus($status = 'PUBLISH')
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'form-';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}

		$validation = $validate->check($_EDIT, array(

		));

		$created_by = Session::get(Config::get('sessions/session_name'));

		if ($validate->passed()) {

			$controller = new \Controller();

			$str = new \Str();

			/** Get Id */
			$_ID_ = Hash::decryptToken($str->data_in($_EDIT['Id']));

			/** Check If Valid $_PID_ And Exists In Participant Table */
			if (!is_integer($_ID_)):
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid Data",
					'ERRORS_STRING' => "Invalid Data"
				];
			endif;

			$_RETURNED_MESSAGE_ = "Successfully published";

			if ($diagnoArray[0] == 'NO_ERRORS') {
				if ($status == 'PUBLISH'):
					$_STATUS = 1;
				else:
					$_STATUS = 0;
				endif;

				$_fields = array(
					'publish_status' => $_STATUS
				);

				try {
					$controller->update("form_list", $_fields, $_ID_);

					// UPDATING LOGS
					$operation = $status.' FORM';
					$comment = "FORM ID: ".$_ID_;
					Logs::newLog($operation, $comment, $created_by);
				}
				catch (Exeption $e) {
					$diagnoArray[0] = "ERRORS_FOUND";
					$diagnoArray[] = $e->getMessage();
				}
			}
		}
		else {
			$diagnoArray[0] = 'ERRORS_FOUND';
			$error_msg = ul_array($validation->errors());
		}
		if ($diagnoArray[0] == 'ERRORS_FOUND') {
			return (object)[
				'ERRORS' => true,
				'ERRORS_SCRIPT' => $validate->getErrorLocation(),
				'ERRORS_STRING' => ""
			];
		}
		else {
			return (object)[
				'ERRORS' => false,
				'SUCCESS' => true,
				'ERRORS_SCRIPT' => "",
				'RETURNEDMESSAGE' => $_RETURNED_MESSAGE_,
				// 'PARTICIPATIONPAYMENTTYPE'=> $_PARTICIPATION_PAYMENT_TYPE_,
				'ERRORS_STRING' => ""
			];
		}
	}


	public static function checkIfFormExists($form_name, $ID = null)
	{
		$SQL_Condition_ = ($ID == null) ? '' : " AND id != {$ID} ";
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT id FROM form_list WHERE form_name = '$form_name'  $SQL_Condition_ ORDER BY id DESC LIMIT 1");
		if ($FutureEventTable->count())
			return true;
		return false;
	}

	public static function getLastID($_table_, $key = 'id')
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT $key FROM {$_table_} ORDER BY $key DESC LIMIT 1 ");
		if ($FutureEventTable->count())
			return $FutureEventTable->first()->id;
		return false;
	}

	public static function getForms($eventID, $_filter_condition_, $order = "") 
	{
		$FutureEventTable = new \FutureEvent();
		$FutureEventTable->selectQuery("SELECT * FROM form_list WHERE event_id = {$eventID} $_filter_condition_ $order");
		if ($FutureEventTable->count())
			return $FutureEventTable->data();
		return false;
	}

	public static function getFormsCount($eventID, $_filter_condition_ = "")
	{
		$FutureEventTable = new \FutureEvent();
		$FutureEventTable->selectQuery("SELECT COUNT(id) as total_count FROM form_list WHERE event_id = {$eventID} $_filter_condition_");
		if ($FutureEventTable->count())
			return $FutureEventTable->first()->total_count;
		return false;
	}

	public static function getFormDetails($ID)
	{
		$FutureEventTable = new \FutureEvent();
		$FutureEventTable->selectQuery("SELECT form_list.*, form_content.id as contentId FROM form_list INNER JOIN form_content ON form_list.id = form_content.form_id WHERE form_list.id = {$ID} ORDER BY form_list.id DESC LIMIT 1");
		if ($FutureEventTable->count())
			return $FutureEventTable->first();
		return false;
	}

// ===============================================================================================

	// FORM BUILDER

	public static function buildForm()
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'form-';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}

		$validation = $validate->check($_EDIT, array(

		));

		if ($validate->passed()) {
			$controller = new \Controller();

			$str = new \Str();

			$data_obj = json_decode($_POST['data']);
		    $frm_data =  $data_obj->data;

		    $data_ary = get_object_vars($frm_data);
	        $update_stt_ary = array();

	        $form_obj = $data_ary["template"];
            $formId =  Hash::decryptToken($data_ary["formToken"]);
            $contentId =  Hash::decryptToken($data_ary["contentToken"]);

			$created_by = Session::get(Config::get('sessions/session_name'));

			if ($diagnoArray[0] == 'NO_ERRORS') {
				try {
					$controller->update("form_list", array('created_by' => $created_by), $formId);
					// UPDATING LOGS
					$operation = 'BUILD FORM';
					$comment = "FORM ID: ".$formId;
					Logs::newLog($operation, $comment, $created_by);

					$controller = new \Controller();
					$submitAndLablsAry = self::getFormSubmitFields($form_obj);
					$fieldObjAry = array();
		            $fieldObjAry["form_form"] = $form_obj;
		            $fieldObjAry["submit_fields"] = $submitAndLablsAry[0];
		            $fieldObjAry["form_labels"] = $submitAndLablsAry[1];
		            foreach ($fieldObjAry as $name => $value) {
		                $_fields = array($name => $value);
		                $update_stt_ary[] = $controller->update("form_content", $_fields, $contentId);
		            }

					$controller->update("form_content", $_fields, $contentId);
				}
				catch (Exception $e) {
					$diagnoArray[0] = "ERRORS_FOUND";
					$diagnoArray[] = $e;
				}
			}
		} else {
			foreach ($validate->errors() as $error) {
                $diagnoArray[] = $error . "<br>";
            }
            $diagnoArray[0] = 'ERRORS_FOUND';
		}
		if ($diagnoArray[0] == 'ERRORS_FOUND') {
			return (object)[
				'ERRORS' => true,
				'ERRORS_SCRIPT' => $validate->getErrorLocation(),
				'ERRORS_STRING' => json_encode($diagnoArray),
			];
		} else {
			return (object)[
				'ERRORS' => false,
				'SUCCESS' => true,
				'ERRORS_SCRIPT' => "",
				'SUCCESS_STRING' => "SUCCESSFULLY CREATED",
				'ERRORS_STRING' => ""
			];
		}
	}

	public static function getFormSubmitFields($formObj){
	    $formFields = json_decode($formObj);
	    $fields_ary = array();
	    $form_labels_ary = array();
	    foreach($formFields as $field){
	        if($field->type != "paragraph" && 
	            $field->type != "header" && 
	            $field->type != "Buttons" && 
	            $field->type != "button" && $field->type != "hidden"){
	            $fieldObj = new stdClass();
	            $fieldObj->type = $field->type;
	            $fieldObj->name = $field->name;
	            if($field->type == "file"){
	                if(isset($field->multiple)){
	                    $fieldObj->multiple = $field->multiple;
	                }
	                if(isset($field->fileSize) && isset($field->sizeUnits)){
	                    $fieldObj->fileSize = $field->fileSize;
	                    $fieldObj->sizeUnits = $field->sizeUnits;
	                }
	            }
	            $fields_ary[] = $fieldObj;
	            $form_labels_ary[] = $field->label;
	        }
	    }
	    $form_labels_str = json_encode($form_labels_ary, JSON_UNESCAPED_UNICODE);
	    $fields_str = json_encode($fields_ary);
	    return [$fields_str,$form_labels_str];
	}


	public static function loadFormContent(){
	    $formToken = Input::get('formToken', 'get');
        $formID    = Hash::decryptToken($formToken);

        $controller = new \Controller();

        $echo_data = "new";
        $controller->get('form_content', '*', NULL, "`form_id` = '$formID'", 'id ASC');
        if ($controller->count()) {
            foreach($controller->data() as $form_content) {
                $echo_data = $form_content->form_form;
            }
        }
        if($echo_data != "new" && $echo_data != ""){
            $submitBtnObj = new stdClass();
            $submitBtnObj->type = "button";
            $submitBtnObj->subtype = "submit";
            $submitBtnObj->label = "Submit";
            $submitBtnObj->className = "btn-primary btn";
            $submitBtnObj->name = "button-submit-form";
            $submitBtnObj->id = "button-submit-form";
            $submitBtnObj->style = "primary";
          
            $btnsObj = new stdClass();
            $btnsObj->type = "Buttons";
            $btnsObj->label = "";
            $btnsObj->className = "buttons-container";
            $btnsObj->name = "";
            $btnsObj->submitBtnColor = "btn btn-primary";
            $btnsObj->clearBtnColor = "btn btn-danger";
            $btnsObj->btnsPos = "";
            $btnsObj->submitLabel = "Submit";
            $btnsObj->cancelLabel = "Clear";

            $hiddenObj = new stdClass();
            $hiddenObj->type = "hidden";
            $hiddenObj->name = "hidden-form-id";
            $hiddenObj->id = "hidden-form-id";
            $hiddenObj->value = $formToken;

            $form = json_decode($echo_data);
            $frm_ary = array();
            //remove button and hidden field if exists
            $isButtons = false;
            foreach($form as $fild){
                if($fild->type != "button" && $fild->type != "hidden"){
                    if($fild->name == 'title' || $fild->name == 'firstname' || $fild->name == 'lastname' || $fild->name == 'email') {
						$fild->disabledFieldButtons = ['remove','edit','copy'];
						if ($fild->type == "select") {
							$fild->placeholder = "Please select";
						}
						$frm_ary[] = $fild;
					} else {
						$frm_ary[] = $fild;
					}
                }
                if($fild->type == "Buttons"){
                    $isButtons = true;
                } 
            }
            // array_push($frm_ary,$hiddenObj);
            //array_push($frm_ary,$submitBtnObj);
            if(!$isButtons){
                // array_push($frm_ary,$btnsObj);
            }
            $echo_data = json_encode($frm_ary);
        }else{
            $frm_ary = array();

            // TITLE
            $titleObj = new stdClass();
            $titleObj->type = "select";
            $titleObj->label = "Title";
            $titleObj->className = "form-control";
            $titleObj->name = "title";
            $titleObj->placeholder = "Please select";
            $titleObj->required = "required";
            $titleObj->values = array(
                array('label'=>'Mr.','value'=>'Mr.'),
                array('label'=>'Mrs.','value'=>'Mrs.'),
                array('label'=>'H.E.','value'=>'H.E.'),
                array('label'=>'Hon.','value'=>'Hon.'),
                array('label'=>'Prof.','value'=>'Prof.'),
                array('label'=>'Dr.','value'=>'Dr.'),
                array('label'=>'Ms.','value'=>'Ms.'));
            $titleObj->disabledFieldButtons = ['remove','edit','copy'];

            // FIRST NAME
            $firstNameObj = new stdClass();
            $firstNameObj->type = "text";
            $firstNameObj->label = "First name";
            $firstNameObj->className = "form-control";
            $firstNameObj->name = "firstname";
            $firstNameObj->placeholder = "Enter your first name";
            $firstNameObj->required = "required";
            $firstNameObj->disabledFieldButtons = ['remove','edit','copy'];

            // LAST NAME
            $lastNameObj = new stdClass();
            $lastNameObj->type = "text";
            $lastNameObj->label = "Last name";
            $lastNameObj->className = "form-control";
            $lastNameObj->name = "lastname";
            $lastNameObj->placeholder = "Enter your last name";
            $lastNameObj->required = "required";
            $lastNameObj->disabledFieldButtons = ['remove','edit','copy'];

            // EMAIL
            $emailObj = new stdClass();
            $emailObj->type = "text";
            $emailObj->label = "Email";
            $emailObj->className = "form-control";
            $emailObj->name = "email";
            $emailObj->placeholder = "Enter your email";
            $emailObj->required = "required";
            $emailObj->disabledFieldButtons = ['remove','edit','copy'];

            // TELEPHONE
            // $telObj = new stdClass();
            // $telObj->type = "text";
            // $telObj->label = "Telephone";
            // $telObj->className = "form-control";
            // $telObj->name = "telephone";
            // $telObj->id = "telephone";
            // $telObj->placeholder = "Enter phone number";
            // $telObj->required = "required";
            // $telObj->disabledFieldButtons = ['remove','edit','copy'];

            // ORGANISATION
            // $orgNameObj = new stdClass();
            // $orgNameObj->type = "text";
            // $orgNameObj->label = "Organisation";
            // $orgNameObj->className = "form-control";
            // $orgNameObj->name = "organisation_name";
            // $orgNameObj->placeholder = "Organisation name";
            // $orgNameObj->required = "required";
            // $orgNameObj->disabledFieldButtons = ['remove','edit','copy'];

            // JOB TITLE
            // $jobTitleObj = new stdClass();
            // $jobTitleObj->type = "text";
            // $jobTitleObj->label = "Job title";
            // $jobTitleObj->className = "form-control";
            // $jobTitleObj->name = "job_title";
            // $jobTitleObj->placeholder = "Job title";
            // $jobTitleObj->required = "required";
            // $jobTitleObj->disabledFieldButtons = ['remove','edit','copy'];

            // COUNTRY
            // $countryObj = new stdClass();
            // $countryObj->type = "select";
            // $countryObj->label = "Country";
            // $countryObj->className = "form-control";
            // $countryObj->name = "residence_country";
            // $jobTitleObj->placeholder = "Please select";
            // $countryObj->required = "required";
            // $countryObj->values = array(
            //     array('label'=>'Afghanistan...','value'=>'Afghanistan'),
            //     array('label'=>'Zimbabwe','value'=>'Zimbabwe'));
            // $countryObj->disabledFieldButtons = ['remove','edit','copy'];

            // HIDDEN FORM TOKEN
            $hiddenObj = new stdClass();
            $hiddenObj->type = "hidden";
            $hiddenObj->name = "hidden-form-id";
            $hiddenObj->id = "hidden-form-id";
            $hiddenObj->value = $formToken;

            // SUBMIT BUTTON
            $btnsObj = new stdClass();
            $btnsObj->type = "button";
            $btnsObj->subtype = "submit";
            $btnsObj->label = "Submit";
            $btnsObj->className = "btn-primary btn";
            $btnsObj->id = "registerButton";
            $btnsObj->style = "primary";

            array_push($frm_ary,$titleObj);
            array_push($frm_ary,$firstNameObj);
            array_push($frm_ary,$lastNameObj);
            array_push($frm_ary,$emailObj);
            // array_push($frm_ary,$telObj);
            // array_push($frm_ary,$orgNameObj);
            // array_push($frm_ary,$jobTitleObj);
            // array_push($frm_ary,$countryObj);
            

            // array_push($frm_ary,$hiddenObj);
            // array_push($frm_ary,$btnsObj);
            $echo_data = json_encode($frm_ary);
        }
        return $echo_data;
	}

}
?>