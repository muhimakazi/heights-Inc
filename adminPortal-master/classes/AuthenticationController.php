<?php
class AuthenticationController
{
	public static function userLogin()
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
			),
			'password' => array(
				'name' => 'Password',
				'required' => true
			)
		));

		if ($validate->passed()) {
			$user = new \User();

			$str = new \Str();

			$email = $str->data_in($_EDIT['username']);
			$password = $str->data_in($_EDIT['password']);
			
			if ($diagnoArray[0] == 'NO_ERRORS') {

				$remember = (Input::get('remember') === 'on') ? true : false;
    			$login = $user->login($email, $password, $remember);

    			if (!$login) {
    				$diagnoArray[0] = "ERRORS_FOUND";
					$diagnoArray[] = "Incorrect username or password";
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
				'SUCCESS_STRING' => "Logged in successfully",
				'ERRORS_STRING' => ""
			];
		}
	}

	// CONFIRM EMAIL
	public static function confirmEmail()
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
			),
		));

		if ($validate->passed()) {
			$user = new \User();

			$str = new \Str();

			$email = $str->data_in($_EDIT['username']);
			
			if ($diagnoArray[0] == 'NO_ERRORS') {

				// $user = DB::getInstance()->get('users', array('username', '=', $email));
				$user = DB::getInstance()->query("SELECT `id` FROM `users` WHERE `username` = '$email' AND `status` = 'ACTIVE' ORDER BY id DESC LIMIT 1");

			    if (!$user->count()) {
			      	$diagnoArray[0] = "ERRORS_FOUND";
				  	$diagnoArray[] = "Sorry! this account is not found";
			    } else {
			      	$userId = $user->first()->id;
			      	$user_token    = Hash::encryptAuthToken($userId);
			      	$user_code = md5(uniqid(rand()));
			      	$user = new User();
			      	$user->update(array('token' => $user_code), $userId);
			     
			      	/** Send Email To Admin */
			      	$_data_ = array(
			        	'email' => $email,
			        	'reset_link' => DN.'/resetpassword/'.$user_token.'/'.$user_code,
			      	);
			      
			      	EmailController::sendEmailToAdminOnResetPassword($_data_);
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
				'SUCCESS_STRING' => "Logged in successfully",
				'ERRORS_STRING' => ""
			];
		}
	}


	// RESET PASSWORD
	public static function resetPassword()
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
			'password' => array(
		      	'name' => 'Password',
		      	'required' => true,
	      		'min' => 6
		    ),
		    'password_again' => array(
		      	'required' => true,
      			'matches' => 'password'
		    )
		));

		if ($validate->passed()) {
			$user = new \User();

			$str = new \Str();

			$password = $str->data_in($_EDIT['password']);
			
			if ($diagnoArray[0] == 'NO_ERRORS') {

				$user_id   = Hash::decryptAuthToken(Input::get('user_token'));
			    $user_code = Input::get('user_code');

			    $findUser = DB::getInstance()->query("SELECT `id` FROM `users` WHERE `id` = $user_id AND `token` = '$user_code' ORDER BY id DESC LIMIT 1");

			    if ($findUser->count()) {
			      	$salt = Hash::salt(32);
			      	$user->update(array('password' => Hash::make($password, $salt), 'salt' => $salt), $user_id);
			    } else {
			    	$diagnoArray[0] = "ERRORS_FOUND";
				  	$diagnoArray[] = "No account found, try again";
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
				'SUCCESS_STRING' => "Logged in successfully",
				'ERRORS_STRING' => ""
			];
		}
	}

	

}