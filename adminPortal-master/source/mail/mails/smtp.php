<?php
require_once("../../core/init.php");


// $sql = "SELECT id, email FROM future_participants WHERE status='APPROVED' AND event_id=8";

// $delegates = DB::getInstance()->query($sql);

// $emails = array("lucienmeru@gmail.com", "mikindip@gmail.com", "moiselukundika@gmail.com");

$subject = "Invitation to Living with Big Cats Initiative";
$email = trim($_POST['email']);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

date_default_timezone_set('Etc/UTC');

require '../src/PHPMailer.php';
require '../src/SMTP.php';

$mail = new PHPMailer;
$mail->isSMTP();
$mail->SMTPDebug = 0;
$mail->SMTPAuth = true;
$mail->SMTPSecure = "ssl";
$mail->Host = 'rwandaexpo2020.rw';
$mail->Port = 465;
$mail->Username = 'registration@rwandaexpo2020.rw';
$mail->Password = 'register@2022';
$mail->setFrom('info@apacongress.torusguru.com', 'APAC Congress');
$mail->addReplyTo('info@apacongress.torusguru.com', 'APAC Congress');

$failedEmails = "Failed Emails: ";

$mail->addAddress($email, "");
$mail->Subject = $subject;
$mail->msgHTML(file_get_contents('contents.php'), __DIR__);
$mail->CharSet = 'UTF-8';

if (!$mail->send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;

    $failedEmails .= $delegate->email;
} else {
    echo 'Message sent!';
}


// function setInterval($f, $milliseconds)
// {
//     $seconds = (int)$milliseconds / 1000;
//     while (true) {
//         $f();
//         sleep($seconds);
//     }
// }
