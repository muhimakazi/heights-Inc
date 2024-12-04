<?php
class EmailControllerOTP
{
  /** - 1 - Send Email - Participant - After Fully Completed Registration And Successful Payment - NEED TO PAY -  */
  public static function sendEmailToParticipantAfterSuccessfulRegistration($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;

    $_Email_ = $email;
    $_Subject_ = 'Registration: End of Year Party';
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
  .h2, .bodycopy {color:#444444!important;}
  .h1 {font-size:30px;line-height:38px;font-weight:bold;}
  .h2 {padding:0 0 15px 0;font-size:15px;line-height:28px;}
  .bodycopy {font-size:14px;line-height:22px;}
  .button {text-align:center;font-size:18px;font-weight:bold;padding:0 30px 0 30px;}
  .button a {color:#ffffff;text-decoration:none;}
  .footercopy {font-size:14px;color:#ffffff;}
  .footercopy a, .bodycopy a {color:#d4af37;text-decoration:none;}
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
          <td bgcolor='#fff' class='header'>
            <table width='100%' align='left' border='0' cellpadding='0' cellspacing='0'>  
              <tr>
                <td height='60' style='text-align: center;'>
                  <img src='https://test.torusguru.com/party/img/logo.png' width='400px'>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class='innerpadding borderbottom' bgcolor='#f9f9f9' style='background:#f9f9f9;'>
            <table width='100%' border='0' cellspacing='0' cellpadding='0'>
              <tr>
                <td class='bodycopy'>
                  Dear $firstname,<br><br>
                  Thank you for registering to attend the End of Year Party.<br><br>
                  We will confirm your attendance shortly.<br><br>
                  Regards,<br>
                  EOY Team<br>
                  <a href='mailto:info@eoy.rw'>info@eoy.rw</a>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class='footer' bgcolor='#cececd' style='border-bottom:4px solid #d4af37;'>
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
        'from' => 'invitation.eoy@torusguru.com',
        'namefrom' => 'End of Year Party',
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

    $_Email_ = $email;
    $_Subject_ = 'Registration: End of Year Party';
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
  .h2, .bodycopy {color:#444444!important;}
  .h1 {font-size:30px;line-height:38px;font-weight:bold;}
  .h2 {padding:0 0 15px 0;font-size:15px;line-height:28px;}
  .bodycopy {font-size:14px;line-height:22px;}
  .button {text-align:center;font-size:18px;font-weight:bold;padding:0 30px 0 30px;}
  .button a {color:#ffffff;text-decoration:none;}
  .footercopy {font-size:14px;color:#ffffff;}
  .footercopy a, .bodycopy a {color:#d4af37;text-decoration:none;}
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
          <td bgcolor='#fff' class='header'>
            <table width='100%' align='left' border='0' cellpadding='0' cellspacing='0'>  
              <tr>
                <td height='60' style='text-align: center;'>
                  <img src='https://test.torusguru.com/party/img/logo.png' width='400px'>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class='innerpadding borderbottom' bgcolor='#f9f9f9' style='background:#f9f9f9;'>
            <table width='100%' border='0' cellspacing='0' cellpadding='0'>
              <tr>
                <td class='bodycopy'>
                  Dear $firstname,<br><br>
                  We are pleased to confirm your RSVP to the End of Year Party.<br>
                  Quick reminder on details…<br><br>
                  Date: 30 December 2023<br>
                  Time: 8pm<br>
                  Venue: Kigali Convention Center<br><br>
                  We’ll keep you posted. <br><br>
                  See you in a few days! <br><br>
                  Regards,<br>
                  EOY Team<br>
                  <a href='mailto:info@eoy.rw'>info@eoy.rw</a>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class='footer' bgcolor='#cececd' style='border-bottom:4px solid #d4af37;'>
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
        'from' => 'invitation.eoy@torusguru.com',
        'namefrom' => 'End of Year Party',
        'attachement' => ''
      );
    $User->sendMail($data);
  }

}


?>