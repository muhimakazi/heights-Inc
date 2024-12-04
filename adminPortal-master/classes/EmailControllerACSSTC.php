<?php
class EmailControllerACSSTC
{
  
  /** - 10 - Send Email - Participant - Email received after successful registration for those that do not pay (Speakers, Invited guests, Secratariat, Staff etc) - On Approval  */
  public static function sendEmailToParticipantAfterSuccessfulRegistrationFreeOnApproval1111($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $invitation_letter_link = $_data_->invitation_letter_link;
    $approval_status = $_data_->approval_status;
    $participant_code = $_data_->participant_code;

    $_Email_ = $email;
    $_Subject_ = 'Successful application to attend ACSSTC | Kigali | Rwanda';
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
  .h2, .bodycopy {color:#444444;}
  .h1 {font-size:30px;line-height:38px;font-weight:bold;}
  .h2 {padding:0 0 15px 0;font-size:15px;line-height:28px;}
  .bodycopy {font-size:14px;line-height:22px;}
  .button {text-align:center;font-size:18px;font-weight:bold;padding:0 30px 0 30px;}
  .button a {color:#ffffff;text-decoration:none;}
  .footercopy {font-size:14px;color:#ffffff;}
  .footercopy a, .bodycopy a {color:#eca153;text-decoration:none;}
  @media only screen and (max-width:550px), screen and (max-device-width:550px) {
  body[yahoo] .hide {display:none!important;}
  body[yahoo] .buttonwrapper {background-color:transparent!important;}
  body[yahoo] .button {padding:0px!important;}
  body[yahoo] .button a {background-color:#ffffff;padding:15px 15px 13px!important;}
}
  </style>
</head>
<body yahoo bgcolor='#f4f4f4' style='font-family:Arial, sans-serif;'>
  <table width='100%' bgcolor='#f4f4f4' border='0' cellpadding='0' cellspacing='0'>
  <tr>
    <td>
      <table bgcolor='#f9f9f9' class='content' align='center' cellpadding='0' cellspacing='0' border='0'>
        <tr>
          <td bgcolor='#ffffff' class='header'>
            <table width='' align='left' border='0' cellpadding='0' cellspacing='0'>  
              <tr>
                <td height='60' style=''>
                  <h1 style='font-size:20px;color:#fff;'><img src='https://acsstc.torusguru.com/img/logo.png' width='250px'></h1>
                </td>
              </tr>
            </table>
           
          </td>
        </tr>
        <tr>
          <td class='innerpadding borderbottom' bgcolor='#f9f9f9' style='background:#f9f9f9;'>
            <table width='100%' border='0' cellspacing='0' cellpadding='0'>
              <tr>
                <td class='h2'>Dear $firstname</td>
              </tr>
              <tr>
                <td class='bodycopy'>
                  Thank you for applying to attend the inaugural ANNUAL CONVENTION ON SOUTH-SOUTH & TRIANGULAR COOPERATION that will be held in Kigali, Rwanda, from 12 – 13 September 2023.<br><br>
                  Your application has been approved and your registration number is <b>$participant_code</b><br><br>

                  <b class='h2' style='color:#007377;'>BADGE COLLECTION</b><br>
                  You will be able to collect your badge from the Kigali Convention Center as follows:<br>
                  <ul>
                    <li>11 September 2023</li>
                    <ul>
                      <li>8am – 9pm</li>
                    </ul>
                    <li>12 September 2023</li>
                    <ul>
                      <li>8am – 5pm</li>
                    </ul>
                    <li>13 September 2023</li>
                    <ul>
                      <li>8am – 9pm</li>
                    </ul>
                  </ul>

                  <b class='h2' style='color:#007377;'>PROGRAMME</b><br>
                  You can view the forum programme at <a href='https://cooperation.rw/convention'>www.cooperation.rw/convention</a><br><br>

                  <b class='h2' style='color:#007377;'>IMPORTANT INFORMATION FOR INTERNATIONAL DELEGATES</b><br>
                  <b>Accommodation</b><br>
                  Recommended hotels<br>
                  <ul>
                    <li>5 Star</li>
                    <ul>
                      <li>Radisson Blu Kigali (next to the Convention Center)</li>
                      <ul><li><a href='https://www.radissonhotels.com/en-us/hotels/radisson-blu-convention-kigali'>Click here</a> to book</li></ul>
                      <li>Marriot Hotel Kigali</li>
                      <ul><li><a href='https://www.marriott.com/en-us/hotels/kglmc-kigali-marriott-hotel/overview/'>Click here</a> to book</li></ul>
                      <li>Serena Hotel Kigali</li>
                      <ul><li><a href='https://www.serenahotels.com/kigali'>Click here</a> to book</li></ul>
                    </ul>
                    <li>4 star </li>
                    <ul>
                      <li>Radisson Park Inn</li>
                      <ul><li><a href='https://www.radissonhotels.com/en-us/hotels/park-inn-kigali'>Click here</a> to book</li></ul>
                      <li>Four Points Sheraton</li> 
                      <ul><li><a href='https://www.marriott.com/en-us/hotels/kglfp-four-points-kigali/overview/'>Click here</a> to book</li></ul>
                    </ul>
                    <li>3 star </li>
                    <ul>
                      <li>Onomo Hotel Kigali</li> 
                      <ul><li><a href='https://www.onomohotels.com/en/hotel/onomo-hotel-kigali/'>Click here</a> to book</li></ul>
                      <li>Elevate Suites Hotel </li>
                      <ul><li><a href='https://elevatesuiteskigali.com'>Click here</a> to book</li></ul>
                    </ul>
                  </ul>

                  <b>Travelling to Rwanda</b><br>
                  <ul>
                    <li>Citizens of countries who are members Commonwealth, African Union  and La Francophonie get visa upon arrival. Visa fees are waived for a visit of 30 days.</li>
                    <li>More information on travel to Rwanda can be found <a href='https://migration.gov.rw/visa-on-arrival'>here</a></li>
                  </ul>

                  <b>Health guidelines </b>
                  <ul>
                    <li>Please <a href='https://rbc.gov.rw/index.php?id=745'>click here</a> to view current travel guidelines to Rwanda. Please note that these will be updated on a regular basis.</li>
                  </ul>

                  <b class='h2' style='color:#007377;'>STAY CONNECTED</b><br>
                  <b>Twitter:</b> <a href='https://twitter.com/Cooperation_RW'>@Cooperation_RW</a><br>
                  <b>LinkedIn:</b> <a href='https://www.linkedin.com/company/cooperation-rwanda/'>Cooperation Rwanda</a> <br>
                  <b>Connect with our official tags:</b> #ASSTC, #UNDAY4SCC<br><br>

                  Regards,<br>
                  Annual Convention on South-South & Triangular Cooperation Organising Team<br>
                  Email:<a href='mailto:events@cooperation.rw'>events@cooperation.rw</a>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class='footer' bgcolor='#cececd' style='border-bottom:4px solid #007377;'>
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
        'from' => 'registration@torusguru.com',
        'namefrom' => 'SSTC',
        'attachement' => ''
      );
    $User->sendMail($data);
  }


  public static function sendEmailToParticipantAfterSuccessfulRegistrationFreeOnApproval($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $invitation_letter_link = $_data_->invitation_letter_link;
    $approval_status = $_data_->approval_status;
    $participant_code = $_data_->participant_code;

    $_Email_ = $email;
    $_Subject_ = 'Successful application to attend UN DAY FOR SOUTH-SOUTH COOPERATION';
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
  .h2, .bodycopy {color:#444444;}
  .h1 {font-size:30px;line-height:38px;font-weight:bold;}
  .h2 {padding:0 0 15px 0;font-size:15px;line-height:28px;}
  .bodycopy {font-size:14px;line-height:22px;}
  .button {text-align:center;font-size:18px;font-weight:bold;padding:0 30px 0 30px;}
  .button a {color:#ffffff;text-decoration:none;}
  .footercopy {font-size:14px;color:#ffffff;}
  .footercopy a, .bodycopy a {color:#eca153;text-decoration:none;}
  @media only screen and (max-width:550px), screen and (max-device-width:550px) {
  body[yahoo] .hide {display:none!important;}
  body[yahoo] .buttonwrapper {background-color:transparent!important;}
  body[yahoo] .button {padding:0px!important;}
  body[yahoo] .button a {background-color:#ffffff;padding:15px 15px 13px!important;}
}
  </style>
</head>
<body yahoo bgcolor='#f4f4f4' style='font-family:Arial, sans-serif;'>
  <table width='100%' bgcolor='#f4f4f4' border='0' cellpadding='0' cellspacing='0'>
  <tr>
    <td>
      <table bgcolor='#f9f9f9' class='content' align='center' cellpadding='0' cellspacing='0' border='0'>
        <tr>
          <td bgcolor='#ffffff' class='header'>
            <table width='' align='left' border='0' cellpadding='0' cellspacing='0'>  
              <tr>
                <td height='60' style=''>
                  <h1 style='font-size:20px;color:#fff;'><img src='https://acsstc.torusguru.com/img/logo_2.png' width='250px'></h1>
                </td>
              </tr>
            </table>
           
          </td>
        </tr>
        <tr>
          <td class='innerpadding borderbottom' bgcolor='#f9f9f9' style='background:#f9f9f9;'>
            <table width='100%' border='0' cellspacing='0' cellpadding='0'>
              <tr>
                <td class='h2'>Dear $firstname</td>
              </tr>
              <tr>
                <td class='bodycopy'>
                  Thank you for applying to attend the UN DAY FOR SOUTH-SOUTH COOPERATION that will be held at the Rwanda Cooperation Governance Centre on the 12th September 2023.<br><br>
                  Your application has been approved and your registration number is <b>$participant_code</b><br><br>

                  <b class='h2' style='color:#007377;'>PROGRAMME</b><br>
                  You can view the forum programme at <a href='https://cooperation.rw/sstc#programme'>www.cooperation.rw/sstc</a><br><br>

                  <b class='h2' style='color:#007377;'>STAY CONNECTED</b><br>
                  <b>Twitter:</b> <a href='https://twitter.com/Cooperation_RW'>@Cooperation_RW</a><br>
                  <b>LinkedIn:</b> <a href='https://www.linkedin.com/company/cooperation-rwanda/'>Cooperation Rwanda</a> <br>
                  <b>Connect with our official tags:</b> #ASSTC, #UNDAY4SCC<br><br>

                  Regards,<br>
                  Annual Convention on South-South & Triangular Cooperation Organising Team<br>
                  Email:<a href='mailto:sstc@cooperation.rw'>sstc@cooperation.rw</a>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class='footer' bgcolor='#cececd' style='border-bottom:4px solid #007377;'>
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
    $User->send_mail_aapld($_Email_, $_Message_, $_Subject_);
  }


  public static function sendEmailToParticipantAfterApplicationDecline($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $invitation_letter_link = $_data_->invitation_letter_link;
    $approval_status = $_data_->approval_status;
    $participant_code = $_data_->participant_code;

    $_Email_ = $email;
    $_Subject_ = 'Application to attend ACSSTC | Kigali | Rwanda ';
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
  .h2, .bodycopy {color:#444444;}
  .h1 {font-size:30px;line-height:38px;font-weight:bold;}
  .h2 {padding:0 0 15px 0;font-size:15px;line-height:28px;}
  .bodycopy {font-size:14px;line-height:22px;}
  .button {text-align:center;font-size:18px;font-weight:bold;padding:0 30px 0 30px;}
  .button a {color:#ffffff;text-decoration:none;}
  .footercopy {font-size:14px;color:#ffffff;}
  .footercopy a, .bodycopy a {color:#eca153;text-decoration:none;}
  @media only screen and (max-width:550px), screen and (max-device-width:550px) {
  body[yahoo] .hide {display:none!important;}
  body[yahoo] .buttonwrapper {background-color:transparent!important;}
  body[yahoo] .button {padding:0px!important;}
  body[yahoo] .button a {background-color:#ffffff;padding:15px 15px 13px!important;}
}
  </style>
</head>
<body yahoo bgcolor='#f4f4f4' style='font-family:Arial, sans-serif;'>
  <table width='100%' bgcolor='#f4f4f4' border='0' cellpadding='0' cellspacing='0'>
  <tr>
    <td>
      <table bgcolor='#f9f9f9' class='content' align='center' cellpadding='0' cellspacing='0' border='0'>
        <tr>
          <td bgcolor='#ffffff' class='header'>
            <table width='' align='left' border='0' cellpadding='0' cellspacing='0'>  
              <tr>
                <td height='60' style=''>
                  <h1 style='font-size:20px;color:#fff;'><img src='https://acsstc.torusguru.com/img/logo.png' width='250px'></h1>
                </td>
              </tr>
            </table>
           
          </td>
        </tr>
        <tr>
          <td class='innerpadding borderbottom' bgcolor='#f9f9f9' style='background:#f9f9f9;'>
            <table width='100%' border='0' cellspacing='0' cellpadding='0'>
              <tr>
                <td class='h2'>Dear $firstname</td>
              </tr>
              <tr>
                <td class='bodycopy'>
                  Thank you for applying to attend the inaugural ANNUAL CONVENTION ON SOUTH-SOUTH & TRIANGULAR COOPERATION.<br><br>
                  Your application to attend the convention in person has not been successful.<br><br>
                  You will however be able to follow the main plenaries through a live stream on our Youtube channel.<br><br>

                  <b class='h2' style='color:#007377;'>STAY CONNECTED</b><br>
                  <b>Twitter:</b> <a href='https://twitter.com/Cooperation_RW'>@Cooperation_RW</a><br>
                  <b>LinkedIn:</b> <a href='https://www.linkedin.com/company/cooperation-rwanda/'>Cooperation Rwanda</a> <br>
                  <b>Connect with our official tags:</b> #ASSTC, #UNDAY4SCC<br><br>

                  Regards,<br>
                  Annual Convention on South-South & Triangular Cooperation Organising Team<br>
                  Email:<a href='mailto:events@cooperation.rw'>events@cooperation.rw</a>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class='footer' bgcolor='#cececd' style='border-bottom:4px solid #007377;'>
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
    $calendarAttachment="/opt/lampp/htdocs/thefuture/rica/img/Graduation Ceremony Agenda.pdf";
    $User->send_mail_aapld($_Email_, $_Message_, $_Subject_, $calendarAttachment);
  }


}


?>