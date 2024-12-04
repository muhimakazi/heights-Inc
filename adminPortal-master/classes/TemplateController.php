<?php
class TemplateController

// ===============================================================================================
// FORMS

{
	public static function getForms($eventID, $_filter_condition_, $order = "") 
	{
		$TemplateTable = new \Template();
		$TemplateTable->selectQuery("SELECT * FROM form_list WHERE event_id = {$eventID} $_filter_condition_ $order");
		if ($TemplateTable->count())
			return $TemplateTable->data();
		return false;
	}

	public static function formDetails($formID, $_filter_condition_, $order = "") 
	{
		$TemplateTable = new \Template();
		$TemplateTable->selectQuery("SELECT * FROM form_list WHERE id = {$formID} $_filter_condition_ $order");
		if ($TemplateTable->count())
			return $TemplateTable->first();
		return false;
	}

	public static function contactForm() 
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'contact-';
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

			$full_name = $str->data_in($_EDIT['full_name']);
			$email = $str->data_in($_EDIT['send_email']);
			$organisation_name = $str->data_in($_EDIT['organisation_name']);
			$message = $str->data_in($_EDIT['message']);
			$subject = $str->data_in($_EDIT['subject']);
			$from = $str->data_in($_EDIT['from']);
			$namefrom = $str->data_in($_EDIT['namefrom']);
			$send_date = date('Y-m-d');

			if ($diagnoArray[0] == 'NO_ERRORS') {

				$_data_ = array(
					'email' => $send_email,
					'name' => $full_name,
					'organisation' => $organisation_name,
					'message' => $message,
					'subject' => $subject,
					'from' => $from,
					'namefrom' => $namefrom,
					'send_date' => $send_date
				);

				try {
					EmailController::eventContactForm($_data_);
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
				'SUCCESS_STRING' => "Event successfully created",
				'ERRORS_STRING' => ""
			];
		}
	}


// ===============================================================================================
// WEBSITE

	public static function getEventDetailsByID($eventID)
	{
		$TemplateTable = new Template();
		$TemplateTable->selectQuery("SELECT * FROM future_event WHERE id = {$eventID}");
		if ($TemplateTable->count())
			return $TemplateTable->first();
		return false;
	}

	public static function getAboutSection($eventID)
	{
		$TemplateTable = new Template();
		$TemplateTable->selectQuery("SELECT * FROM future_homepage_about WHERE event_id = {$eventID}");
		if ($TemplateTable->count())
			return $TemplateTable->first();
		return false;
	}

	public static function getCountDown($eventID)
	{
		$TemplateTable = new Template();
		$TemplateTable->selectQuery("SELECT * FROM future_countdown WHERE event_id = {$eventID}");
		if ($TemplateTable->count())
			return $TemplateTable->first();
		return false;
	}

	public static function getQuote($eventID)
	{
		$TemplateTable = new Template();
		$TemplateTable->selectQuery("SELECT * FROM future_homepage_quote WHERE event_id = {$eventID}");
		if ($TemplateTable->count())
			return $TemplateTable->first();
		return false;
	}

	public static function getWhyAttend($eventID)
	{
		$TemplateTable = new Template();
		$TemplateTable->selectQuery("SELECT * FROM future_homepage_whyattend WHERE event_id = {$eventID}");
		if ($TemplateTable->count())
			return $TemplateTable->first();
		return false;
	}

	public static function getOutcome($eventID)
	{
		$TemplateTable = new Template();
		$TemplateTable->selectQuery("SELECT * FROM future_homepage_outcome WHERE event_id = {$eventID}");
		if ($TemplateTable->count())
			return $TemplateTable->first();
		return false;
	}

	public static function getSpeaker($eventID)
	{
		$TemplateTable = new Template();
		$TemplateTable->selectQuery("SELECT * FROM future_homepage_speakers WHERE event_id = {$eventID}");
		if ($TemplateTable->count())
			return $TemplateTable->data();
		return false;
	}

	public static function getSpeakerStyle($eventID)
	{
		$TemplateTable = new Template();
		$TemplateTable->selectQuery("SELECT * FROM future_homepage_speakers WHERE event_id = {$eventID} ORDER BY id ASC LIMIT 1");
		if ($TemplateTable->count())
			return $TemplateTable->first();
		return false;
	}

}
?>