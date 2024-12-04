<?php
require_once 'core/init.php';

$operation = "USER LOGOUT";
$comment = "SUCCESSFUL";
if (Logs::newLog($operation, $comment, $user->data()->id)) {
	Session::delete('eventId');
	Session::delete('eventName');
	
	$user = new User();
    $user->logout();

	Redirect::to('login');
}