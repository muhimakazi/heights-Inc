<?php
class FutureAccountController
{
	public static function createAccount()
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'account-';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}
		// $_EDIT = $_POST;

		$validation = $validate->check($_EDIT, array(
			'username' => array(
				'name' => 'Email',
				'required' => true,
				// 'unique' => 'users'
			),
			'firstname' => array(
				'name' => 'First name',
				'required' => true
			),
			'lastname' => array(
				'name' => 'Last name',
				'required' => true
			),
			'account_type' => array(
				'name' => 'User category',
				'required' => true
			)
		));

		if ($validate->passed()) {
			$FutureAccountTable = new \FutureAccount();

			$str = new \Str();

			/** Contact Information */
			$firstname = $str->data_in($_EDIT['firstname']);
			$lastname = $str->data_in($_EDIT['lastname']);
			$email = $str->data_in($_EDIT['username']);
			$telephone = !Input::checkInput('telephone', 'post', 1)?'':$str->data_in($_EDIT['telephone']);
			$client_id = !Input::checkInput('client_id', 'post', 1)?0:Hash::decryptToken($str->data_in($_EDIT['client_id']));
			$account_type_id = Hash::decryptToken($str->data_in($_EDIT['account_type']));

			/** Check If Valid $_PID_ And Exists In  Table */
			if (!is_integer($account_type_id)):
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid Data",
					'ERRORS_STRING' => "Invalid Data"
				];
			endif;

			/** Check If Email Account Exists  */
			if (self::checkIfAccountExists($email)):
    			return (object)[
    				'ERRORS' => true,
    				'ERRORS_SCRIPT' => "This email is already exists",
    				'ERRORS_STRING' => "This email is already exists"
    			];
			endif;

			/** Password Handler */
			$_salt = Hash::salt(32);
			// $_generate_password = Hash::randomPassword(8);
			$_generate_password = md5(uniqid(rand()));
			$_password = Hash::make($_generate_password, $_salt);
			$_status = 'ACTIVE';

			$created_by = Session::get(Config::get('sessions/session_name'));

			$_ID = self::getLastID() + 1;
			$user_code = self::generateCode($_ID);

			if ($diagnoArray[0] == 'NO_ERRORS') {

				$_fields = array(
					'code' => $user_code,
					'client_id'  => $client_id,
					'username' => $email,
					'firstname' => $firstname,
					'lastname' => $lastname,
					'telephone' => $telephone,
					'password' => $_password,
					'salt' => $_salt,
					'group' => $account_type_id,
					'permission' => 'Admin',
					'token' => $_generate_password,
					'status' => $_status,
					'created_by' => $created_by,
					'joined'     => date('Y-m-d H:i:s')
				);

				try {
					$FutureAccountTable->insert($_fields);

					// UPDATING LOGS
					$operation = 'NEW ADMIN ACCOUNT';
					$comment = "ADMIN ID: ".$_ID;
					Logs::newLog($operation, $comment, $created_by);
    				
    				/** Send Email To Admin */
					$_data_ = array(
						'email' => $email,
						'firstname' => $firstname,
						'password' => $_generate_password,
					);
					
	                EmailController::sendEmailToAdminOnCreateAccount($_data_);
				}
				catch (Exception $e) {
					$diagnoArray[0] = "ERRORS_FOUND";
					$diagnoArray[] = $e;
				}
			}
		}
		else {
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
		}
		else {
			return (object)[
				'ERRORS' => false,
				'SUCCESS' => true,
				'ADMIN_ID' => $_ID,
				'PASSWORD_TEXT' => $_generate_password,
				'ERRORS_SCRIPT' => "",
				'SUCCESS_STRING' => "Account successfully created",
				'ERRORS_STRING' => ""
			];
		}
	}


	// UPDATE
	public static function updateAccount()
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'account-';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}
		// $_EDIT = $_POST;

		$validation = $validate->check($_EDIT, array(
			'username' => array(
				'name' => 'Email',
				'required' => true,
				// 'unique' => 'users'
			),
			'firstname' => array(
				'name' => 'First name',
				'required' => true
			),
			'lastname' => array(
				'name' => 'Last name',
				'required' => true
			),
			'account_type' => array(
				'name' => 'User category',
				'required' => true
			)
		));

		if ($validate->passed()) {
			$FutureAccountTable = new \FutureAccount();

			$str = new \Str();

			/** Contact Information */
			$_ID = Hash::decryptToken($str->data_in($_EDIT['userId']));
			$firstname = $str->data_in($_EDIT['firstname']);
			$lastname = $str->data_in($_EDIT['lastname']);
			$email = $str->data_in($_EDIT['username']);
			$telephone = !Input::checkInput('telephone', 'post', 1)?'':$str->data_in($_EDIT['telephone']);
			$client_id = !Input::checkInput('client_id', 'post', 1)? 0:Hash::decryptToken($str->data_in($_EDIT['client_id']));
			$account_type_id = Hash::decryptToken($str->data_in($_EDIT['account_type']));

			/** Check If Valid $_PID_ And Exists In  Table */
			if (!is_integer($account_type_id)):
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid Data",
					'ERRORS_STRING' => "Invalid Data"
				];
			endif;

			/** Check If Email Account Exists  */
			if (self::checkIfAccountExists($email, $_ID)):
    			return (object)[
    				'ERRORS' => true,
    				'ERRORS_SCRIPT' => "This email is already exists",
    				'ERRORS_STRING' => "This email is already exists"
    			];
			endif;

			$created_by = Session::get(Config::get('sessions/session_name'));

			if ($diagnoArray[0] == 'NO_ERRORS') {

				$_fields = array(
					'client_id'  => $client_id,
					'username' => $email,
					'firstname' => $firstname,
					'lastname' => $lastname,
					'telephone' => $telephone,
					'`group`' => $account_type_id,
					'created_by' => $created_by,
				);

				try {
					$FutureAccountTable->update($_fields, $_ID);

					// UPDATING LOGS
					$operation = 'Update ADMIN ACCOUNT';
					$comment = "ADMIN ID: ".$_ID;
					Logs::newLog($operation, $comment, $created_by);
				}
				catch (Exception $e) {
					$diagnoArray[0] = "ERRORS_FOUND";
					$diagnoArray[] = $e;
				}
			}
		}
		else {
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
		}
		else {
			return (object)[
				'ERRORS' => false,
				'SUCCESS' => true,
				'ADMIN_ID' => $_ID,
				'ERRORS_SCRIPT' => "",
				'SUCCESS_STRING' => "Account successfully updated",
				'ERRORS_STRING' => ""
			];
		}
	}

	public static function changeAdminStatus($status = 'ACTIVE')
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'participant-';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}
		// $_EDIT = $_POST;

		$validation = $validate->check($_EDIT, array(

		));

		$created_by = Session::get(Config::get('sessions/session_name'));

		if ($validate->passed()) {

			$FutureAccountTable = new \FutureAccount();

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

			$_RETURNED_MESSAGE_ = "Successfully activated";

			if ($diagnoArray[0] == 'NO_ERRORS') {

				$_fields = array(
					'status' => $status,
				);

				try {
					$FutureAccountTable->update($_fields, $_ID_);

					// UPDATING LOGS
					$operation = $status.' ADMIN ACCOUNT';
					$comment = "ADMIN ID: ".$_ID_;
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

	// CHANGE USER PASSWORD
	public static function changeUserPassword()
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'account-';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}
		// $_EDIT = $_POST;

		$validation = $validate->check($_EDIT, array(
			'old_password' => array(
                'name' => 'Old Password',
                'required' => true,
            ),
            'password' => array(
                'name' => 'Password',
                'required' => true,
                'min' => 6
            ),
            'password_again' => array(
                'name' => 'Confirm Password',
                'required' => true,
                'matches' => 'password'
            )
		));

		if ($validate->passed()) {
			$FutureAccountTable = new \FutureAccount();
			$user = new \User();

			$str = new \Str();

			/** Contact Information */
			$password = $str->data_in($_EDIT['password']);
			$old_password = $str->data_in($_EDIT['old_password']);

			// CHECK IF OLD PASSWORD MATCHES
			if(Hash::make($old_password, $user->data()->salt) !== $user->data()->password) {
                return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Your old password doesn't match",
					'ERRORS_STRING' => "Your old password doesn't match"
				];
	        }

			$created_by = $_ID = Session::get(Config::get('sessions/session_name'));

			if ($diagnoArray[0] == 'NO_ERRORS') {

				$salt = Hash::salt(32);
				$_fields = array(
					'password' => Hash::make($password, $salt),
                    'salt' => $salt
				);

				try {
					$FutureAccountTable->update($_fields, $_ID);
					
					// UPDATING LOGS
					$operation = 'CHANGE PASSWORD';
					$comment = "ADMIN ID: ".$_ID;
					Logs::newLog($operation, $comment, $created_by);
				}
				catch (Exception $e) {
					$diagnoArray[0] = "ERRORS_FOUND";
					$diagnoArray[] = $_fields;
				}
			}
		}
		else {
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
		}
		else {
			return (object)[
				'ERRORS' => false,
				'SUCCESS' => true,
				'ADMIN_ID' => $_ID,
				'ERRORS_SCRIPT' => "",
				'SUCCESS_STRING' => "Your password has been changed",
				'ERRORS_STRING' => ""
			];
		}
	}

	public static function getAccounts($_filter_condition_, $order = "")
	{
		$FutureAccountTable = new \FutureAccount();
		$FutureAccountTable->selectQuery("SELECT users.*, groups.name as account_group_name, future_client.organisation as organisation FROM users INNER JOIN groups ON users.group = groups.id LEFT JOIN future_client ON users.client_id = future_client.id WHERE (future_client.id IS NULL OR future_client.id  IS NOT NULL) $_filter_condition_ $order");
		if ($FutureAccountTable->count())
			return $FutureAccountTable->data();
		return false;
	}

	public static function getAccountCount($_filter_condition_ = "")
	{
		$FutureAccountTable = new \FutureAccount();
		$FutureAccountTable->selectQuery("SELECT COUNT(users.id) as total_count FROM users INNER JOIN groups ON users.group = groups.id LEFT JOIN future_client ON users.client_id = future_client.id WHERE (future_client.id IS NULL OR future_client.id IS NOT NULL) $_filter_condition_ ORDER BY users.id DESC");
		if ($FutureAccountTable->count())
			return $FutureAccountTable->first()->total_count;
		return false;
	}

	public static function getAccountByID($ID)
	{
		$FutureAccountTable = new \FutureAccount();
		$FutureAccountTable->selectQuery("SELECT users.profile, users.username, users.firstname, users.lastname, users.email, users.account_session, users.token, users.status, users.creation_by, users.creation_datetime, users.*, user_groups.name as account_group_name, user_groups.description as account_group_description, app_account_type.name as account_type_name, app_account_type.description as account_type_description, users.status as account_status FROM users INNER JOIN app_account_type ON users.account_type_id = app_account_type.id INNER JOIN user_groups ON users.group = user_groups.id WHERE users.status != 'DELETED' AND users.id = {$ID}  ORDER BY users.id DESC LIMIT 1");
		if ($FutureAccountTable->count())
			return $FutureAccountTable->first();
		return false;
	}
	
	public static function getAccountInfoByID($ID)
	{
		$FutureAccountTable = new \FutureAccount();
		$FutureAccountTable->selectQuery("SELECT firstname, lastname, email FROM users  WHERE id = $ID  ORDER BY id DESC LIMIT 1");
		if ($FutureAccountTable->count())
			return $FutureAccountTable->first();
		return false;
	}

	public static function getAccountPermission($groupID, $permissionName)
	{
		$FutureAccountTable = new \FutureAccount();
		$FutureAccountTable->selectQuery("SELECT * FROM user_groups WHERE user_groups.id = {$groupID}  ORDER BY user_groups.id DESC LIMIT 1");
		if ($FutureAccountTable->count())
			return $FutureAccountTable->first();
		return false;
	}

	public static function getAccountPermissions()
	{
		$FutureAccountTable = new \FutureAccount();
		$FutureAccountTable->selectQuery("SELECT * FROM user_groups  ORDER BY user_groups.id DESC");
		if ($FutureAccountTable->count())
			return $FutureAccountTable->data();
		return false;
	}

	public static function generateCode($TYPE = 1)
	{
		return 'TRS' . rand(10, 90) . date('s') . '0' . ($TYPE == 1 ? 'A' : 'L') . date('d');
	}

	public static function generateToken($STR)
	{
		$seconds = time();
		$token_hash = md5($seconds . sha1($STR));
		return $token_hash;
	}

	public static function checkIfAccountExists($email, $participantID = null)
	{
		$SQL_Condition_ = ($participantID == null) ? "" : " AND id != $participantID ";
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT id from users WHERE username =? $SQL_Condition_ ORDER BY id DESC LIMIT 1 ", array($email));
		if ($FutureEventTable->count())
			return $FutureEventTable->first()->id > 0?true:false;
		return false;
	}
	
	public static function getLastID($_table_ = 'users', $key = 'id')
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT $key FROM {$_table_} ORDER BY $key DESC LIMIT 1 ");
		if ($FutureEventTable->count())
			return $FutureEventTable->first()->id;
		return false;
	}


	// CREATE NEW CLIENT
	public static function createClientAccount()
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'account-';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}
		// $_EDIT = $_POST;

		$validation = $validate->check($_EDIT, array(
			'email' => array(
				'name' => 'Email',
				'required' => true,
				'unique' => 'future_client'
			),
			'firstname' => array(
				'name' => 'First name',
				'required' => true
			),
			'lastname' => array(
				'name' => 'Last name',
				'required' => true
			),
			'organisation' => array(
				'name' => 'Organisation',
				'required' => true
			)
		));

		if ($validate->passed()) {
			$FutureAccountTable = new \FutureAccount();

			$str = new \Str();

			/** Contact Information */
			$firstname = $str->data_in($_EDIT['firstname']);
			$lastname = $str->data_in($_EDIT['lastname']);
			$email = $str->data_in($_EDIT['email']);
			$telephone = $str->data_in($_EDIT['telephone']);
			$organisation = $str->data_in($_EDIT['organisation']);

			$employees = !Input::checkInput('employees', 'post', 1)?'':$str->data_in($_EDIT['employees']);
			$industry = !Input::checkInput('industry', 'post', 1)?'':$str->data_in($_EDIT['industry']);
			$job_title = !Input::checkInput('job_title', 'post', 1)?'':$str->data_in($_EDIT['job_title']);
			$city = !Input::checkInput('city', 'post', 1)?'':$str->data_in($_EDIT['city']);
			$country = !Input::checkInput('country', 'post', 1)?'':$str->data_in($_EDIT['country']);
			$website = !Input::checkInput('website', 'post', 1)?'':$str->data_in($_EDIT['website']);
			$firstname2 = !Input::checkInput('firstname2', 'post', 1)?'':$str->data_in($_EDIT['firstname2']);
			$lastname2 = !Input::checkInput('lastname2', 'post', 1)?'':$str->data_in($_EDIT['lastname2']);
			$email2 = !Input::checkInput('email2', 'post', 1)?'':$str->data_in($_EDIT['email2']);
			$telephone2 = !Input::checkInput('telephone2', 'post', 1)?'':$str->data_in($_EDIT['telephone2']);
			$organisation2 = !Input::checkInput('organisation2', 'post', 1)?'':$str->data_in($_EDIT['organisation2']);
			$employees2 = !Input::checkInput('employees2', 'post', 1)?'':$str->data_in($_EDIT['employees2']);
			$industry2 = !Input::checkInput('industry2', 'post', 1)?'':$str->data_in($_EDIT['industry2']);
			$job_title2 = !Input::checkInput('job_title2', 'post', 1)?'':$str->data_in($_EDIT['job_title2']);
			$city2 = !Input::checkInput('city2', 'post', 1)?'':$str->data_in($_EDIT['city2']);
			$country2 = !Input::checkInput('country2', 'post', 1)?'':$str->data_in($_EDIT['country2']);
			$website2 = !Input::checkInput('website2', 'post', 1)?'':$str->data_in($_EDIT['website2']);
			$invoice_line_one = !Input::checkInput('invoice_line_one', 'post', 1)?'':$str->data_in($_EDIT['invoice_line_one']);
			$invoice_line_two = !Input::checkInput('invoice_line_two', 'post', 1)?'':$str->data_in($_EDIT['invoice_line_two']);
			$invoice_organisation = !Input::checkInput('invoice_organisation', 'post', 1)?'':$str->data_in($_EDIT['invoice_organisation']);
			$firstname3 = !Input::checkInput('firstname3', 'post', 1)?'':$str->data_in($_EDIT['firstname3']);
			$lastname3 = !Input::checkInput('lastname3', 'post', 1)?'':$str->data_in($_EDIT['lastname3']);
			$email3 = !Input::checkInput('email3', 'post', 1)?'':$str->data_in($_EDIT['email3']);
			$telephone3 = !Input::checkInput('telephone3', 'post', 1)?'':$str->data_in($_EDIT['telephone3']);
			$invoice_city = !Input::checkInput('invoice_city', 'post', 1)?'':$str->data_in($_EDIT['invoice_city']);
			$invoice_country = !Input::checkInput('invoice_country', 'post', 1)?'':$str->data_in($_EDIT['invoice_country']);

			$status = 'ACTIVE';

			$created_by = Session::get(Config::get('sessions/session_name'));

			if ($diagnoArray[0] == 'NO_ERRORS') {

				$_fields = array(
					'firstname'            => $firstname,
	                'lastname'             => $lastname,
	                'email'                => $email,
	                'telephone'            => $telephone,
	                'organisation'         => $organisation,
	                'employees'            => $employees,
	                'industry'             => $industry,
	                'job_title'            => $job_title,
	                'city'                 => $city,
	                'country'              => $country,
	                'website'              => $website,
	                'firstname2'           => $firstname2,
	                'lastname2'            => $lastname2,
	                'email2'               => $email2,
	                'telephone2'           => $telephone2,
	                'organisation2'        => $organisation2,
	                'employees2'           => $employees2,
	                'industry2'            => $industry2,
	                'job_title2'           => $job_title2,
	                'city2'                => $city2,
	                'country2'             => $country2,
	                'website2'             => $website2,
	                'invoice_line_one'     => $invoice_line_one,
	                'invoice_line_two'     => $invoice_line_two,
	                'invoice_organisation' => $invoice_organisation,
	                'firstname3'           => $firstname3,
	                'lastname3'            => $lastname3,
	                'email3'               => $email3,
	                'telephone3'           => $telephone3,
	                'invoice_city'         => $invoice_city,
	                'invoice_country'      => $invoice_country,
	                'status'               => $status,
					'creation_date'        => date('Y-m-d H:i:s')
				);

				try {
					$controller = new \Controller();

					$controller->create("future_client", $_fields);
					
					// UPDATING LOGS
					$operation = 'NEW CLIENT ACCOUNT';
					$comment = "ORGANISATION : ".$organisation;
					Logs::newLog($operation, $comment, $created_by);
				}
				catch (Exception $e) {
					$diagnoArray[0] = "ERRORS_FOUND";
					$diagnoArray[] = $_fields;
				}
			}
		}
		else {
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
		}
		else {
			return (object)[
				'ERRORS' => false,
				'SUCCESS' => true,
				'ERRORS_SCRIPT' => "",
				'SUCCESS_STRING' => "Account successfully created",
				'ERRORS_STRING' => ""
			];
		}
	}

	public static function getClients($_filter_condition_, $order = "")
	{
		$FutureAccountTable = new \FutureAccount();
		$FutureAccountTable->selectQuery("SELECT * FROM future_client WHERE status != 'DELETED' $_filter_condition_ $order");
		if ($FutureAccountTable->count())
			return $FutureAccountTable->data();
		return false;
	}

	public static function getClientCount($_filter_condition_ = "")
	{
		$FutureAccountTable = new \FutureAccount();
		$FutureAccountTable->selectQuery("SELECT COUNT(id) as total_count FROM future_client WHERE status != 'DELETED' $_filter_condition_ ORDER BY id DESC");
		if ($FutureAccountTable->count())
			return $FutureAccountTable->first()->total_count;
		return false;
	}

}