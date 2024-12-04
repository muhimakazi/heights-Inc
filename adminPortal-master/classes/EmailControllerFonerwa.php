<?php
class EmailControllerFonerwa
{
  /** - 1 - Send Email - Participant - After Fully Completed Registration And Successful Payment - NEED TO PAY -  */
  public static function sendEmailToParticipantAfterSuccessfulRegistration($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;

    $_Email_ = $email;
    $_Subject_ = 'Registration: Rwanda Green Fund Celebration';
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
  .footercopy a, .bodycopy a {color:#006a38;text-decoration:none;}
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
          <td bgcolor='#006a38' class='header'>
            <table width='' align='left' border='0' cellpadding='0' cellspacing='0'>  
              <tr>
                <td height='60' style=''>
                  <h1 style='font-size:20px;color:#fff;'><img src='https://rwandagreenfund.torusguru.com/img/logo-white.png' width='100px'></h1>
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
                  Dear $firstname<br><br>
                  Thank you for registering to attend the Rwanda Green Fund celebration.<br><br>
                  We will confirm your attendance shortly.<br><br>
                  Regards,<br>
                  Rwanda Green Fund<br>
                  <a href='mailto:ceooffice@greenfund.rw'>ceooffice@greenfund.rw</a>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class='footer' bgcolor='#cececd' style='border-bottom:4px solid #006a38;'>
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
        'from' => 'rwandagreenfund@torusguru.com',
        'namefrom' => 'Rwanda Green Fund',
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
    $invitation_letter_link = $_data_->invitation_letter_link;
    $approval_status = $_data_->approval_status;
    $participant_code = $_data_->participant_code;

    $_Email_ = $email;
    $_Subject_ = 'Registration: Rwanda Green Fund Celebration';
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
  .footercopy a, .bodycopy a {color:#006a38;text-decoration:none;}
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
          <td bgcolor='#006a38' class='header'>
            <table width='' align='left' border='0' cellpadding='0' cellspacing='0'>  
              <tr>
                <td height='60' style=''>
                  <h1 style='font-size:20px;color:#fff;'><img src='https://rwandagreenfund.torusguru.com/img/logo-white.png' width='100px'></h1>
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
                  We are pleased to confirm your RSVP to the Rwanda Green Fund Experience. <br>
                  Quick reminder on details…<br><br>
                  Date: 23 November 2023<br>
                  Time: 6pm<br>
                  Venue: Kigali Conference and Exhibition Village (also known as Camp Kigali)<br><br>
                  We are also going to provide transport from certain pick-up points around Kigali in Kigali’s latest electric buses!<br><br>
                  We’ll keep you posted. <br><br>
                  See you in a few days! <br><br>
                  Regards,<br>
                  Rwanda Green Fund<br>
                  <a href='mailto:ceooffice@greenfund.rw'>ceooffice@greenfund.rw</a>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class='footer' bgcolor='#cececd' style='border-bottom:4px solid #006a38;'>
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
        'from' => 'rwandagreenfund@torusguru.com',
        'namefrom' => 'Rwanda Green Fund',
        'attachement' => ''
      );
    $User->sendMail($data);
  }

}


?>