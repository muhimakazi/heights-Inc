<?php
class EmailControllerWorldBank
{
  /** - 1 - Send Email - Participant - After Fully Completed Registration And Successful Payment - NEED TO PAY -  */
  public static function sendEmailToParticipantAfterSuccessfulRegistration($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;

    $_Email_ = $email;
    $_Subject_ = 'Registration for the WASH Leadership Summit';
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
  .footercopy a, .bodycopy a {color:#dc5f02;text-decoration:none;}
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
          <td bgcolor='#2b2e4b' class='header'>
            <table width='' align='left' border='0' cellpadding='0' cellspacing='0'>  
              <tr>
                <td height='60' style=''>
                  <h1 style='font-size:20px;color:#fff;'><img src='https://washleadershipsummit.com/img/logo.png' width='250px'></h1>
                </td>
              </tr>
            </table>
           
          </td>
        </tr>
        <tr>
          <td class='innerpadding borderbottom' bgcolor='#f9f9f9' style='background:#f9f9f9;'>
            <table width='100%' border='0' cellspacing='0' cellpadding='0'>
              <tr>
                <td class='h2'>Dear $firstname,</td>
              </tr>
              <tr>
                <td class='bodycopy'>
                  Thank you for registering to attend the WASH Leadership Summit that will be held from 14 – 15 November 2023 in Addis Ababa, Ethiopia.<br><br>
                  As an invited guest, you will receive a separate email with logistical information. <br><br>
                  If you do not receive this email within 72 hours, please email <a href='mailto:info@washleadershipsummit.com'>info@washleadershipsummit.com</a><br><br>
                  Regards,<br>
                  WASH Leadership Summit Team
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class='footer' bgcolor='#cececd' style='border-bottom:4px solid #dc5f02;'>
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
        'from' => 'washleadershipsummit@torusguru.com',
        'namefrom' => 'WASH',
        'attachement' => ''
      );
    $User->sendMail($data);
  }


  /** - 8 - Send Email - Participant - After Fully Completed Registration And Successful  - Media application -  */
  public static function sendEmailToParticipantAfterSuccessfulMediaRegistration($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;

    $_Email_ = $email;
    $_Subject_ = 'Registration for the WASH Leadership Summit';
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
  .footercopy a, .bodycopy a {color:#dc5f02;text-decoration:none;}
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
          <td bgcolor='#2b2e4b' class='header'>
            <table width='' align='left' border='0' cellpadding='0' cellspacing='0'>  
              <tr>
                <td height='60' style=''>
                  <h1 style='font-size:20px;color:#fff;'><img src='https://washleadershipsummit.com/img/logo.png' width='250px'></h1>
                </td>
              </tr>
            </table>
           
          </td>
        </tr>
        <tr>
          <td class='innerpadding borderbottom' bgcolor='#f9f9f9' style='background:#f9f9f9;'>
            <table width='100%' border='0' cellspacing='0' cellpadding='0'>
              <tr>
                <td class='h2'>Dear $firstname,</td>
              </tr>
              <tr>
                <td class='bodycopy'>
                  Thank you for applying to attend the WASH Leadership Summit that will be held from 14 – 15 November 2023 in Addis Ababa, Ethiopia.<br><br>
                  Your registration application will be reviewed, and a response will be received within 3 working days.<br><br>
                  Regards,<br>
                  WASH Leadership Summit Team
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class='footer' bgcolor='#cececd' style='border-bottom:4px solid #dc5f02;'>
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
        'from' => 'washleadershipsummit@torusguru.com',
        'namefrom' => 'WASH',
        'attachement' => ''
      );
    $User->sendMail($data);
  }



  /** - 10 - Send Email - Participant - Email received after successful registration for those that do not pay (Speakers, Invited guests, Secratariat, Staff etc) - On Approval  */
  public static function sendEmailToParticipantAfterSuccessfulRegistrationFreeOnApprovalWash($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $invitation_letter_link = $_data_->invitation_letter_link;
    $approval_status = $_data_->approval_status;
    $participant_code = $_data_->participant_code;

    $_Email_ = $email;
    $_Subject_ = 'Registration for the WASH Leadership Summit';
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
  .footercopy a, .bodycopy a {color:#dc5f02;text-decoration:none;}
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
          <td bgcolor='#2b2e4b' class='header'>
            <table width='' align='left' border='0' cellpadding='0' cellspacing='0'>  
              <tr>
                <td height='60' style=''>
                  <h1 style='font-size:20px;color:#fff;'><img src='https://washleadershipsummit.com/img/logo.png' width='250px'></h1>
                </td>
              </tr>
            </table>
           
          </td>
        </tr>
        <tr>
          <td class='innerpadding borderbottom' bgcolor='#f9f9f9' style='background:#f9f9f9;'>
            <table width='100%' border='0' cellspacing='0' cellpadding='0'>
              <tr>
                <td class='h2'>Dear $firstname,</td>
              </tr>
              <tr>
                <td class='bodycopy'>
                  Thank you for registering to attend the WASH Leadership Summit that will be held from 14 – 15 November 2023 in Addis Ababa, Ethiopia.<br><br>
                  We look forward to welcoming you to Addis Ababa, Ethiopia.<br><br>
                  We have compiled some important information to help you make the most of your time at the Summit:<br>
                  <b class='h2' style='color:#f7ad18!important;'>ACCOMMODATION & TRANSPORT</b>
                  <ul>
                    <li>Please note that the shuttle services will be offered from the airport to the Sheraton Hotel from 12 November to 13 November 2023</li>
                    <li>A shuttle service will also operate between 15  November to 16 November 2023 from Sheraton Hotel to the airport. The schedule will be available from the concierge desk at the Sheraton Hotel.</li>
                  </ul>

                  <b class='h2' style='color:#f7ad18!important;'>BADGE COLLECTION</b><br>
                  Badges will be collected from the Sheraton Hotel as follows:<br>
                  <ul>
                    <li>13 November 2023</li>
                    <ul>
                      <li>8am – 9pm</li>
                    </ul>
                    <li>14 November 2023</li>
                    <ul>
                      <li>8am – 1pm</li>
                    </ul>
                    <li>Please ensure you bring the ID you used in registration to facilitate badge collection.</li>
                  </ul>

                  <b class='h2' style='color:#f7ad18!important;'>VISA</b>
                  <ul>
                    <li>Kenya and Djibouti passport holders:</li>
                    <ul>
                      <li>You will not need to apply for a visa to travel to Ethiopia.</li>
                    </ul>
                    <li>All other passports holders:</li>
                    <ul>
                      <li>Please apply for your visa online through this <a href='https://www.evisa.gov.et/information'>link</a></li>
                    </ul>
                  </ul>

                  <b class='h2' style='color:#f7ad18!important;'>USEFUL LINKS</b><br>
                  <a href='https://www.evisa.gov.et/information'>Visa information </a><br>
                  <a href='https://washleadershipsummit.com/#programme'>Latest program</a><br><br>

                  Regards,<br>
                  WASH Leadership Summit Team
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class='footer' bgcolor='#cececd' style='border-bottom:4px solid #dc5f02;'>
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
        'from' => 'washleadershipsummit@torusguru.com',
        'namefrom' => 'WASH',
        'attachement' => ''
      );
    $User->sendMail($data);
  }


  /** - 10 - Send Email - Participant - Email received after successful registration for those that do not pay (Speakers, Invited guests, Secratariat, Staff etc) - On Approval  */
  public static function sendEmailToParticipantAfterSuccessfulRegistrationFreeOnApprovalWashMedia($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $invitation_letter_link = $_data_->invitation_letter_link;
    $approval_status = $_data_->approval_status;
    $participant_code = $_data_->participant_code;

    $_Email_ = $email;
    $_Subject_ = 'Registration for the WASH Leadership Summit';
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
  .footercopy a, .bodycopy a {color:#dc5f02;text-decoration:none;}
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
          <td bgcolor='#2b2e4b' class='header'>
            <table width='' align='left' border='0' cellpadding='0' cellspacing='0'>  
              <tr>
                <td height='60' style=''>
                  <h1 style='font-size:20px;color:#fff;'><img src='https://washleadershipsummit.com/img/logo.png' width='250px'></h1>
                </td>
              </tr>
            </table>
           
          </td>
        </tr>
        <tr>
          <td class='innerpadding borderbottom' bgcolor='#f9f9f9' style='background:#f9f9f9;'>
            <table width='100%' border='0' cellspacing='0' cellpadding='0'>
              <tr>
                <td class='h2'>Dear $firstname,</td>
              </tr>
              <tr>
                <td class='bodycopy'>
                  Thank you for registering to attend the WASH Leadership Summit that will be held from 14 – 15 November 2023 in Addis Ababa, Ethiopia.<br><br>
                  Your application has been approved.<br><br>
                  We look forward to welcoming you to Addis Ababa, Ethiopia.<br><br>
                  We have compiled some important information to help you make the most of your time at the Summit:<br>
                  <b class='h2' style='color:#f7ad18!important;'>ACCOMMODATION & TRANSPORT</b>
                  <ul>
                    <li>Please note that the shuttle services will be offered from the airport to the Sheraton Hotel from 12 November to 13 November 2023</li>
                    <li>A shuttle service will also operate between 15  November to 16 November 2023 from Sheraton Hotel to the airport. The schedule will be available from the concierge desk at the Sheraton Hotel.</li>
                  </ul>

                  <b class='h2' style='color:#f7ad18!important;'>BADGE COLLECTION</b><br>
                  Badges will be collected from the Sheraton Hotel as follows:<br>
                  <ul>
                    <li>13 November 2023</li>
                    <ul>
                      <li>8am – 9pm</li>
                    </ul>
                    <li>14 November 2023</li>
                    <ul>
                      <li>8am – 1pm</li>
                    </ul>
                    <li>Please ensure you bring the ID you used in registration to facilitate badge collection.</li>
                  </ul>

                  <b class='h2' style='color:#f7ad18!important;'>VISA</b>
                  <ul>
                    <li>Kenya and Djibouti passport holders:</li>
                    <ul>
                      <li>You will not need to apply for a visa to travel to Ethiopia.</li>
                    </ul>
                    <li>All other passports holders:</li>
                    <ul>
                      <li>Please apply for your visa online through this <a href='https://www.evisa.gov.et/information'>link</a></li>
                    </ul>
                  </ul>

                  <b class='h2' style='color:#f7ad18!important;'>USEFUL LINKS</b><br>
                  <a href='https://www.evisa.gov.et/information'>Visa information </a><br>
                  <a href='https://washleadershipsummit.com/#programme'>Latest program</a><br><br>

                  Regards,<br>
                  WASH Leadership Summit Team
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class='footer' bgcolor='#cececd' style='border-bottom:4px solid #dc5f02;'>
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
        'from' => 'washleadershipsummit@torusguru.com',
        'namefrom' => 'WASH',
        'attachement' => ''
      );
    $User->sendMail($data);
  }

  /** - 10 - Send Email - Participant - Email received after successful registration for those that do not pay (Speakers, Invited guests, Secratariat, Staff etc) - On Approval  */
  public static function remindVisa($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $invitation_letter_link = $_data_->invitation_letter_link;
    $approval_status = $_data_->approval_status;
    $participant_code = $_data_->participant_code;
    $link = "https://washleadershipsummit.com/";
    $blank = '_blank';

    $_Email_ = $email;
    $_Subject_ = 'Registration for the WASH Leadership Summit';
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
  .footercopy a, .bodycopy a {color:#dc5f02;text-decoration:none;}

.radio-mc {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  font-weight: 300!important;
}
.radio-mc input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
}
.checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 20px;
  width: 20px;
  background-color: hsla(15,28%,89%,.05);
  border: 1px solid #000;
  border-radius: 50%;
}
.radio-mc:hover input ~ .checkmark {
}
.radio-mc input:checked ~ .checkmark {
  background-color: #fff;
}
.checkmark:after {
  content: '';
  position: absolute;
  display: none;
}
.radio-mc input:checked ~ .checkmark:after {
  display: block;
}
.radio-mc .checkmark:after {
  background: #dc5f02;
}
.radio-mc .checkmark:focus {
  background: #dc5f02;
}
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
          <td bgcolor='#2b2e4b' class='header'>
            <table width='' align='left' border='0' cellpadding='0' cellspacing='0'>  
              <tr>
                <td height='60' style=''>
                  <h1 style='font-size:20px;color:#fff;'><img src='https://washleadershipsummit.com/img/logo.png' width='250px'></h1>
                </td>
              </tr>
            </table>
           
          </td>
        </tr>
        <tr>
          <td class='innerpadding borderbottom' bgcolor='#f9f9f9' style='background:#f9f9f9;'>
            <table width='100%' border='0' cellspacing='0' cellpadding='0'>
              <tr>
                <td class='h2'>Dear $firstname,</td>
              </tr>
              <tr>
                <td class='bodycopy'>
                  Thank you for registering to attend the WASH Leadership Summit that will be held from 14 – 15 November 2023 in Addis Ababa, Ethiopia.<br><br>
                  Please review the visa requirements based on your passport nationality:<br><br>
                  We have compiled some important information to help you make the most of your time at the Summit:<br>
                  <b>Kenya and Djibouti Passport Holders:</b> You will not need to apply for a visa to travel to Ethiopia.<br>
                  <b>All Other Passport Holders:</b> please apply for a visa online through the official <a href='https://www.evisa.gov.et/information/conferenceVisa'>e-Visa portal</a>.<br><br>
                  Please take a moment to answer the following questions:
                  <ol>
                    <li> Have you applied for a visa yet?</li>
                    <label class='radio-mc field-validate'>Yes, I am available for interviews
                        <input type='radio' name='applied' value='YES'/>
                        <a class='checkmark' href='https://www.evisa.gov.et/information/conferenceVisa' target='_blank'></a>
                    </label>
                    <label class='radio-mc field-validate'>Yes, I am available for interviews
                        <input type='radio' name='applied' value='NO'/>
                        <a class='checkmark' href='https://www.evisa.gov.et/information/conferenceVisa' target='_blank'></a>
                    </label>

                    <li>Have you received your visa?</li>
                    <label class='radio-mc field-validate'>Yes, I am available for interviews
                        <input type='radio' name='received' value='YES'/>
                        <a class='checkmark' href='https://www.evisa.gov.et/information/conferenceVisa' target='_blank'></a>
                    </label>
                    <label class='radio-mc field-validate'>Yes, I am available for interviews
                        <input type='radio' name='received' value='NO'/>
                        <a class='checkmark' href='https://www.evisa.gov.et/information/conferenceVisa' target='_blank'></a>
                    </label>
                  </ol>

                  Your participation is invaluable, and we eagerly anticipate your contributions to the success of the summit. <br><br>
                  If you have any questions or require assistance, please don't hesitate to email us at <a href='mailto:info@washleadershipsummit.com'>info@washleadershipsummit.com</a><br><br>

                  Regards,<br>
                  WASH Leadership Summit Team
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class='footer' bgcolor='#cececd' style='border-bottom:4px solid #dc5f02;'>
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
        'from' => 'washleadershipsummit@torusguru.com',
        'namefrom' => 'WASH',
        'attachement' => ''
      );
    $User->sendMail($data);
  }


  /** - 10 - Send Email - Participant - Email received after successful registration for those that do not pay (Speakers, Invited guests, Secratariat, Staff etc) - On Approval  */
  public static function sendAgenda($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $invitation_letter_link = $_data_->invitation_letter_link;
    $approval_status = $_data_->approval_status;
    $participant_code = $_data_->participant_code;
    $link = "https://washleadershipsummit.com/";
    $blank = '_blank';

    $_Email_ = $email;
    $_Subject_ = 'Agenda - WASH Leadership Summit';
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
  .footercopy a, .bodycopy a {color:#dc5f02;text-decoration:none;}
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
          <td bgcolor='#2b2e4b' class='header'>
            <table width='' align='left' border='0' cellpadding='0' cellspacing='0'>  
              <tr>
                <td height='60' style=''>
                  <h1 style='font-size:20px;color:#fff;'><img src='https://washleadershipsummit.com/img/logo.png' width='250px'></h1>
                </td>
              </tr>
            </table>
           
          </td>
        </tr>
        <tr>
          <td class='innerpadding borderbottom' bgcolor='#f9f9f9' style='background:#f9f9f9;'>
            <table width='100%' border='0' cellspacing='0' cellpadding='0'>
              <tr>
                <td class='h2'>Dear $firstname,</td>
              </tr>
              <tr>
                <td class='bodycopy'>
                  Please see attached file for the WASH Leadership Summit Agenda.<br><br>
                  
                  You may also find it, along with other information about the summit, on our website - <a href='https://washleadershipsummit.com/'>https://washleadershipsummit.com/.</a><br><br>
                  We look forward to your participation and the valuable insights you will bring to the summit.<br><br>

                  Warm regards,<br>
                  WASH Leadership Summit Team
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class='footer' bgcolor='#cececd' style='border-bottom:4px solid #dc5f02;'>
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
        'from' => 'washleadershipsummit@torusguru.com',
        'namefrom' => 'WASH',
        'attachement' => '/opt/lampp/htdocs/thefuture/adminPortal/data_system/docs/WASH_Agenda.pdf'
      );
    $User->sendMail($data);
  }


}


?>