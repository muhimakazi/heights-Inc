<?php
require_once "core/init.php";

$_participant_data_ = FutureEventController::getEventPrivateLinkDataByID(8);
var_dump($_participant_data_);
echo '<hr>';
$plain_text_pwd     = 'abc350';

$_data_ = array(
    'email' => $_participant_data_->participant_email,
    'firstname' => $_participant_data_->participant_firstname,
    'fullname' => $_participant_data_->participant_lastname,
    'generated_link' => $_participant_data_->generated_link,
    'password' => $plain_text_pwd,
    'system_link' => Config::get('url/group_admin_portal')
);

echo '<br><hr>';

echo '<pre>';
print_r($_data_);
echo '</pre>';



// EmailController::sendEmailToGroupAdminOnGroupRegistrationRequestAccepted($_data_);

echo 'OK';