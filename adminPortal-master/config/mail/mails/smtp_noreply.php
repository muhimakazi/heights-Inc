<?php


$message = $_POST['message'] = "This is a test";
$email = $_POST['email'] = "ezechielkalengya@gmail.com";
$subject = $_POST['subject'] = "Test email";
/**
 * This example shows making an SMTP connection with authentication.
 */

//Import the PHPMailer class into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('Etc/UTC');

require '../src/PHPMailer.php';
require '../src/SMTP.php';


//Create a new PHPMailer instance
$mail = new PHPMailer;
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// SMTP::DEBUG_OFF = off (for production use)
// SMTP::DEBUG_CLIENT = client messages
// SMTP::DEBUG_SERVER = client and server messages
// $mail->SMTPDebug = SMTP::DEBUG_SERVER;
//Set the hostname of the mail server
$mail->Host = 'n3plcpnl0129.prod.ams3.secureserver.net';
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = 587;
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
// $mail->SMTPSecure = "";
//Username to use for SMTP authentication
$mail->Username = 'admin@torusguru.com';
//Password to use for SMTP authentication
$mail->Password = ')=j~e1gMjZRG';
//Set who the message is to be sent from
$mail->setFrom('forum@thecoalitionafrica.com', 'The Coalition Africa');
//Set an alternative reply-to address
$mail->addReplyTo('forum@thecoalitionafrica.com', 'The Coalition Africa');
//Set who the message is to be sent to
$mail->addAddress($email, '');
//Set the subject line
$mail->Subject = $subject;
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->msgHTML($message);
//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';
//Attach an image file
//$mail->addAttachment('images/phpmailer_mini.png');

//send the message, check for errors
if (!$mail->send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
    return false;
}
else {
    echo 'Message sent!';
    return true;
}
