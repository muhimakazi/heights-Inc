<?php
class EmailControllerBAL
{
  /** - 1 - Send Email - Participant - After Fully Completed Registration And Successful Payment - NEED TO PAY -  */
  public static function sendEmailToParticipantAfterSuccessfulRegistration($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;

    $_Email_ = $email;
    $_Subject_ = 'Registration: BAL in Abidjan';
    $_Message_ = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
  <meta http-equiv='Content-Type' content='text/html;charset=utf-8' />
  <title>TORUS</title>
  <style type='text/css'>
  body {margin:0;padding:0;min-width:100%!important;}
  img {height:auto;}
  .content {width:100%;max-width:600px;margin:10px auto;}
  .header {padding:0 30px;}
  .innerpadding {padding:30px 30px 30px 30px;}
  .borderbottom {border-bottom:1px solid #f2eeed;}
  .subhead {font-size:15px;color:#ffffff;letter-spacing:10px;}
  .h1 {color:#ffffff;}
  .h2, .bodycopy {color:#ffffff!important;}
  .h1 {font-size:30px;line-height:38px;font-weight:bold;}
  .h2 {padding:0 0 15px 0;font-size:15px;line-height:28px;}
  .bodycopy {font-size:14px;line-height:22px;}
  .button {text-align:center;font-size:18px;font-weight:bold;padding:0 30px 0 30px;}
  .button a {color:#ffffff;text-decoration:none;}
  .footercopy {font-size:14px;color:#ffffff;}
  .footercopy a, .bodycopy a {color:#e88c29;text-decoration:none;}
  @media only screen and (max-width:550px), screen and (max-device-width:550px) {
  body[yahoo] .hide {display:none!important;}
  body[yahoo] .buttonwrapper {background-color:transparent!important;}
  body[yahoo] .button {padding:0px!important;}
  body[yahoo] .button a {background-color:#ffffff;padding:15px 15px 13px!important;}
}
  </style>
</head>
<body yahoo bgcolor='#EAF0F6' style='font-family:Arial, sans-serif;'>
  <table width='100%' bgcolor='#EAF0F6' border='0' cellpadding='0' cellspacing='0'>
  <tr>
    <td>
      <table bgcolor='#f9f9f9' class='content' align='center' cellpadding='0' cellspacing='0' border='0'>
        <tr>
          <td bgcolor='#e88c29' class='header' style='background:#e88c29;'>
            <table width='100%' align='left' border='0' cellpadding='0' cellspacing='0'>  
              <tr>
                <td height='60'>
                  <img src='https://bal.torusguru.com/img/logo.png' width='100px' style='padding:5px 0;'>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class='innerpadding borderbottom' bgcolor='#231f20' style='background:#231f20;'>
            <table width='100%' border='0' cellspacing='0' cellpadding='0'>
              <tr>
                <td class='bodycopy'>
                  Dear $firstname,<br><br>
                  Thank you for your RSVP to the BAL IN ABIDJAN Experience.<br><br>
                  We will send you a notification with a QR code after verification of your RSVP. <br><br>
                  We look forward to hosting you!<br><br>
                  Regards,<br>
                  BAL Event Team<br>
                  <a href='mailto:WAzar@theBAL.com'>WAzar@theBAL.com</a>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      </td>
    </tr>
  </table>
</body>
</html>";

    // Email::send($_Email_, $_Subject_, $_Message_);
      $User = new User();
      $data = array(
        'email' => $_Email_,
        'message' => $_Message_,
        'subject' => $_Subject_,
        'from' => 'bal@torusguru.com',
        'namefrom' => 'BAL in Abidjan',
        'attachement' => ''
      );
    $User->sendMail($data);
  }

  /** - 10 - Send Email - Participant - Email received after successful registration for those that do not pay (Speakers, Invited guests, Secratariat, Staff etc) - On Approval  */
  public static function sendEmailToParticipantAfterSuccessfulRegistrationFreeOnApproval($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $participant_qrCode = $_data_->participant_qrCode;

    $_Email_ = $email;
    $_Subject_ = 'Confirmation: BAL in Abidjan';
    $_Message_ = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
  <meta http-equiv='Content-Type' content='text/html;charset=utf-8' />
  <title>TORUS</title>
  <style type='text/css'>
  body {margin:0;padding:0;min-width:100%!important;}
  img {height:auto;}
  .content {width:100%;max-width:600px;margin:10px auto;}
  .header {padding:0 30px;}
  .innerpadding {padding:30px 30px 30px 30px;}
  .borderbottom {border-bottom:1px solid #f2eeed;}
  .subhead {font-size:15px;color:#ffffff;letter-spacing:10px;}
  .h1 {color:#ffffff;}
  .h2, .bodycopy {color:#ffffff!important;}
  .h1 {font-size:30px;line-height:38px;font-weight:bold;}
  .h2 {padding:0 0 15px 0;font-size:15px;line-height:28px;}
  .bodycopy {font-size:14px;line-height:22px;}
  .button {text-align:center;font-size:18px;font-weight:bold;padding:0 30px 0 30px;}
  .button a {color:#ffffff;text-decoration:none;}
  .footercopy {font-size:14px;color:#ffffff;}
  .footercopy a, .bodycopy a {color:#e88c29;text-decoration:none;}
  @media only screen and (max-width:550px), screen and (max-device-width:550px) {
  body[yahoo] .hide {display:none!important;}
  body[yahoo] .buttonwrapper {background-color:transparent!important;}
  body[yahoo] .button {padding:0px!important;}
  body[yahoo] .button a {background-color:#ffffff;padding:15px 15px 13px!important;}
}
  </style>
</head>
<body yahoo bgcolor='#EAF0F6' style='font-family:Arial, sans-serif;'>
  <table width='100%' bgcolor='#EAF0F6' border='0' cellpadding='0' cellspacing='0'>
  <tr>
    <td>
      <table bgcolor='#f9f9f9' class='content' align='center' cellpadding='0' cellspacing='0' border='0'>
        <tr>
          <td bgcolor='#e88c29' class='header' style='background:#e88c29;'>
            <table width='100%' align='left' border='0' cellpadding='0' cellspacing='0'>  
              <tr>
                <td height='60'>
                  <img src='https://bal.torusguru.com/img/logo.png' width='100px' style='padding:5px 0;'>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class='innerpadding borderbottom' bgcolor='#231f20' style='background:#231f20;'>
            <table width='100%' border='0' cellspacing='0' cellpadding='0'>
              <tr>
                <td class='bodycopy'>
                  Dear $firstname,<br><br>
                  Thank you for your RSVP to the BAL IN ABIDJAN Experience.<br><br>
                  Please see below for your QR code that you will show on arrival at the venue.<br><br>
                  <img src='$participant_qrCode' width='150px' bgcolor='#ffffff' style='background:#ffffff;'><br><br>
                  <b style='color:#e88c29;'>Dress code:</b> Garden Party<br><br>
                  We look forward to hosting you!<br><br>
                  Regards,<br>
                  BAL Event Team<br>
                  <a href='mailto:WAzar@theBAL.com'>WAzar@theBAL.com</a>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      </td>
    </tr>
  </table>
</body>
</html>";

    // Email::send($_Email_, $_Subject_, $_Message_);
      $User = new User();
      $data = array(
        'email' => $_Email_,
        'message' => $_Message_,
        'subject' => $_Subject_,
        'from' => 'bal@torusguru.com',
        'namefrom' => 'BAL in Abidjan',
        'attachement' => ''
      );
    $User->sendMail($data);
  }



  /** - 10 - Send Email - Participant - Email received after successful registration for those that do not pay (Speakers, Invited guests, Secratariat, Staff etc) - On Approval  */
  public static function sendEmailToParticipantInvitation($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;

    $_Email_ = $email;
    $_Subject_ = 'Invitation: BAL in Abidjan';
    $_Message_ = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
  <meta http-equiv='Content-Type' content='text/html;charset=utf-8' />
  <title>TORUS</title>
  <style type='text/css'>
  body {margin:0;padding:0;min-width:100%!important;}
  img {height:auto;}
  .content {width:100%;max-width:600px;margin:10px auto;}
  .header {padding:0 30px;}
  .borderbottom {border-bottom:1px solid #f2eeed;}
  .subhead {font-size:15px;color:#ffffff;letter-spacing:10px;}
  .h1 {color:#ffffff;}
  .h2, .bodycopy {color:#444444!important;}
  .h1 {font-size:30px;line-height:38px;font-weight:bold;}
  .h2 {padding:0 0 15px 0;font-size:15px;line-height:28px;}
  .bodycopy {font-size:14px;line-height:22px;}
  .button {text-align:center;font-size:18px;font-weight:bold;padding:0 30px 0 30px;}
  .button a {color:#ffffff;text-decoration:none;}
  .footercopy {font-size:14px;color:#ffffff;}
  .footercopy a, .bodycopy a {color:#e88c29;text-decoration:none;}
  @media only screen and (max-width:550px), screen and (max-device-width:550px) {
  body[yahoo] .hide {display:none!important;}
  body[yahoo] .buttonwrapper {background-color:transparent!important;}
  body[yahoo] .button {padding:0px!important;}
  body[yahoo] .button a {background-color:#ffffff;padding:15px 15px 13px!important;}
}
  </style>
</head>
<body yahoo bgcolor='#EAF0F6' style='font-family:Arial, sans-serif;'>
  <table width='100%' bgcolor='#EAF0F6' border='0' cellpadding='0' cellspacing='0'>
  <tr>
    <td>
      <table bgcolor='#f9f9f9' class='content' align='center' cellpadding='0' cellspacing='0' border='0'>
        <tr>
          <td class='innerpadding borderbottom' bgcolor='#f9f9f9' style='background:#f9f9f9;'>
            <table width='100%' border='0' cellspacing='0' cellpadding='0'>
              <tr>
                <td class='bodycopy'>
                  <a href='https://bal.torusguru.com/register' target='_blank'>
                    <img src='https://bal.torusguru.com/img/bal_invitation.jpg' style='width: 100%;'>
                  </a>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      </td>
    </tr>
  </table>
</body>
</html>";

    // Email::send($_Email_, $_Subject_, $_Message_);
      $User = new User();
      $data = array(
        'email' => $_Email_,
        'message' => $_Message_,
        'subject' => $_Subject_,
        'from' => 'bal@torusguru.com',
        'namefrom' => 'BAL in Abidjan',
        'attachement' => ''
      );
    $User->sendMail($data);
  }

}


?>