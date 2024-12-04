<?php
class ReportController
{
	public static function getTotalParticipantsByEventID($eventID, $condition = "")
	{
		$_SQL_Condition_ = $condition == "" ? "" : " $condition ";
		$controller = new Controller();
		$controller->get('future_participants', 'id', NULL, "event_id = $eventID $_SQL_Condition_", '');
		return $controller->count();
	}

	public static function getAllPacipationCategoryCountByEventID($eventID)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT * FROM future_participation_type WHERE event_id = {$eventID}");
		if ($FutureEventTable->count())
			return $FutureEventTable->count();
		return false;
	}

	public static function getAllPacipationCategoryByEventID($eventID)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT * FROM future_participation_type WHERE event_id = {$eventID}");
		if ($FutureEventTable->count())
			return $FutureEventTable->data();
		return false;
	}

	public static function getOnePacipationCategoryByEventID($eventID)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT id FROM future_participation_type WHERE event_id = {$eventID}");
		if ($FutureEventTable->count())
			return $FutureEventTable->first();
		return false;
	}

	public static function getPacipationSubCategoryByID($ID)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT * FROM future_participation_sub_type WHERE participation_type_id = {$ID} ");
		if ($FutureEventTable->count())
			return $FutureEventTable->data();
		return false;
	}

	public static function getTotalParticipantsByFilter($eventID, $condition = "")
	{
		$_SQL_Condition_ = $condition == "" ? "" : " $condition ";
		$FutureEventTable = new FutureEvent();
		$SQL_ = "SELECT future_participants.*, future_participants.id as participant_ID, future_participation_type.name as participation_type_name, future_participation_type.code as participation_type_code, future_participation_sub_type.name as participation_subtype_name , future_participation_sub_type.payment_state, future_participation_sub_type.category as participation_subtype_category, future_participation_sub_type.price as participation_subtype_price, future_participation_sub_type.currency as participation_subtype_currency FROM `future_participants` INNER JOIN future_participation_type ON future_participants.participation_type_id = future_participation_type.id INNER JOIN future_participation_sub_type ON future_participants.participation_sub_type_id = future_participation_sub_type.id WHERE future_participants.event_id = {$eventID} $_SQL_Condition_  ORDER BY future_participants.id DESC";

		$numDelegates = DB::getInstance()->query($SQL_);
		return $numDelegates->count();
	}

	public static function getAllFormPassCountByEventID($eventID)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT * FROM form_list WHERE event_id = {$eventID}");
		if ($FutureEventTable->count())
			return $FutureEventTable->count();
		return false;
	}

	public static function getAllFormPassByEventID($eventID)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT * FROM form_list WHERE event_id = {$eventID}");
		if ($FutureEventTable->count())
			return $FutureEventTable->data();
		return false;
	}

	public static function getPaymentStatsRegistrationCount($eventID, $condition = '')
	{
		$SQL_CONDITION_ = " future_payment_transaction_entry.event_id = {$eventID} ";
		$SQL_CONDITION_ .= " AND future_payment_transaction_entry.transaction_status !=  'IGNORED' ";
		if ($condition != '')
			$SQL_CONDITION_ .= " $condition ";
		$PaymentTable = new Payment();
		$PaymentTable->selectQuery("SELECT COUNT(future_payment_transaction_entry.id) as total_count FROM future_payment_transaction_entry INNER JOIN future_participants ON future_participants.id = future_payment_transaction_entry.participant_id WHERE $SQL_CONDITION_ GROUP BY future_payment_transaction_entry.participant_id DESC ORDER BY future_payment_transaction_entry.id DESC ");
		if ($PaymentTable->count())
			return $PaymentTable->count();
		return 0;
	}

	public static function getPaymentStatsRegistrationAmount($eventID, $condition = '')
	{
		$SQL_CONDITION_ = " future_payment_transaction_entry.event_id = {$eventID} ";
		$SQL_CONDITION_ .= " AND future_payment_transaction_entry.transaction_status !=  'IGNORED' ";
		if ($condition != '')
			$SQL_CONDITION_ .= " $condition ";

		// echo $SQL_CONDITION_;
		$PaymentTable = new Payment();
		$PaymentTable->selectQuery("SELECT SUM(future_payment_transaction_entry.amount) as total_amount FROM future_payment_transaction_entry INNER JOIN future_participants ON future_participants.id = future_payment_transaction_entry.participant_id WHERE $SQL_CONDITION_  ORDER BY future_payment_transaction_entry.id DESC ");
		if ($PaymentTable->count())
			if($PaymentTable->first()->total_amount > 0):
				return $PaymentTable->first()->total_amount;
			else:
				return 0;
			endif;
		return 0;
	}

	public static function getTotalParticipantsByContinent($eventID, $continent)
	{
		$i = 0;
		$controller = new Controller();
		require_once "../../functions/functions.php";
		$controller->get('future_participants', '*', NULL, "event_id = $eventID", '');
		foreach ($controller->data() as $resCountries) {
			$country = $resCountries->organisation_country;
			if (country_to_continent($country) == $continent) {
				$i++;
			}
		}
		return $i;
	}

	public static function getTotalPayingParticipantsByContinent($eventID, $continent)
	{
		$i = 0;

		require_once "../../functions/functions.php";

		$sql = "SELECT future_participants.id, future_participants.organisation_country FROM future_participants INNER JOIN future_payment_transaction_entry 
		ON future_participants.id=future_payment_transaction_entry.participant_id 
		WHERE future_participants.status='APPROVED' AND future_payment_transaction_entry.transaction_status='COMPLETED' AND future_participants.event_id=" . $eventID . "";

		try {
			$delegateData = DB::getInstance()->query($sql);

			foreach ($delegateData->results() as $resPayCountries) {
				$country = $resPayCountries->organisation_country;
				if (country_to_continent($country) == $continent) {
					$i++;
				}
			}
		} catch (Exception $e) {
		}

		return $i;
	}

	public static function getTotalParticipantsByCountry($eventID, $country)
	{
		$i = 0;

		require_once "../../functions/functions.php";

		$sql = "SELECT id FROM future_participants WHERE organisation_country = '" . $country . "' AND event_id= " . $eventID . "";

		$countryData = DB::getInstance()->query($sql);


		return $countryData->count();
	}

	public static function getEventDetailsByID($eventID)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT * FROM future_event WHERE id = {$eventID}");
		if ($FutureEventTable->count())
			return $FutureEventTable->first();
		return false;
	}

	public static function getTotalUsers($condition = "")
	{
		$_SQL_Condition_ = $condition == "" ? "" : " $condition ";
		$controller = new Controller();
		$controller->get('users', 'id', NULL, "status != 'DELETED' $_SQL_Condition_", '');
		return $controller->count();
	}

	public static function getTotalClients($condition = "")
	{
		$_SQL_Condition_ = $condition == "" ? "" : " $condition ";
		$controller = new Controller();
		$controller->get('future_client', 'id', NULL, "status != 'DELETED' $_SQL_Condition_", '');
		return $controller->count();
	}
}
