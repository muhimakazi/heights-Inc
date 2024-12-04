<?php
class EmailControllerRICA
{
  
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
    $_Subject_ = 'Registration confirmation for the RICA’s 2023 Commencement Ceremony';
    $_Message_ = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
  <title>TORUS</title>
  <link rel='stylesheet' type='text/css' href='//fonts.googleapis.com/css?family=Ubuntu' />
  <style type='text/css'>
  body {margin: 0; padding: 0; min-width: 100%!important; font-family: sans-serif;}
  img {height: auto;}
  .content {width: 100%; max-width: 600px;}
  .header {padding: 0 30px;}
  .innerpadding {padding: 30px 30px 30px 30px;}
  .borderbottom {border-bottom: 1px solid #f2eeed;}
  .subhead {font-size: 15px; color: #ffffff; font-family: Ubuntu; letter-spacing: 10px;}
  .h1 {color: #ffffff; font-family: Ubuntu;}
  .h2, .bodycopy {color: #3d4042; font-family: Ubuntu;}
  .h1 {font-size: 30px; line-height: 38px; font-weight: bold;}
  .h2 {padding: 0 0 15px 0; font-size: 15px; line-height: 28px;}
  .bodycopy {font-size: 14px; line-height: 22px;}
  .button {text-align: center; font-size: 18px; font-family: Ubuntu; font-weight: bold; padding: 0 30px 0 30px;}
  .button a {color: #ffffff; text-decoration: none;}
  .footercopy {font-family: Ubuntu; font-size: 14px; color: #ffffff;}
  .footercopy a {color: #ffffff; text-decoration: underline;}
  @media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
  body[yahoo] .hide {display: none!important;}
  body[yahoo] .buttonwrapper {background-color: transparent!important;}
  body[yahoo] .button {padding: 0px!important;}
  body[yahoo] .button a {background-color: #ffffff; padding: 15px 15px 13px!important;}
}
  </style>
</head>
<body yahoo bgcolor='#f4f4f4' style='font-family:colfax, helvetica neue, arial, verdana, sans-serif;'>
  <table width='100%' bgcolor='#f4f4f4' border='0' cellpadding='0' cellspacing='0'>
  <tr>
    <td>
      <table bgcolor='#edf3f5' class='content' align='center' cellpadding='0' cellspacing='0' border='0'>
        <tr>
          <td bgcolor='#ffffff' class='header'>
            <table width='' align='left' border='0' cellpadding='0' cellspacing='0'>  
              <tr>
                <td height='60' style=''>
                  <h1 style='font-size:20px; color:#fff;font-family: sans-serif;'><img src='https://rica.torusguru.com/img/logo.png' width='250px'></h1>
                </td>
              </tr>
            </table>
           
          </td>
        </tr>
        <tr>
          <td class='innerpadding borderbottom' bgcolor='#edf3f5' style='background:#edf3f5;'>
            <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                      <td class='h2' style='font-family: sans-serif;'>Dear $firstname</td>
                    </tr>
                    <tr>
                      <td class='bodycopy' style='font-family: sans-serif;'>
                        Thank you for registering to attend the Rwanda Institute for Conservation Agriculture’s 2023 Commencement Ceremony that will be held on August 8, 2023, at the RICA campus in Bugesera District. Our campus is located on Kagasa-Rweru Rd, off RN 15; approximately a 1-hour and 30-minute drive from Kigali.<br><br> 
                        Your registration is hereby confirmed.<br><br> 
                        Regards,<br>
                        RICA organizing team
                      </td>
                    </tr>
                  </table>
          </td>
        </tr>
        <tr>
          <td class='footer' bgcolor='#cececd' style='border-bottom: 4px solid #1479a3;'>
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


  public static function sendCommunique($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $invitation_letter_link = $_data_->invitation_letter_link;
    $approval_status = $_data_->approval_status;
    $participant_code = $_data_->participant_code;

    $_Email_ = $email;
    $_Subject_ = 'RICA graduation day security and arrival information';
    $_Message_ = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
  <title>TORUS</title>
  <link rel='stylesheet' type='text/css' href='//fonts.googleapis.com/css?family=Ubuntu' />
  <style type='text/css'>
  body {margin: 0; padding: 0; min-width: 100%!important; font-family: sans-serif;}
  img {height: auto;}
  .content {width: 100%; max-width: 600px;}
  .header {padding: 0 30px;}
  .innerpadding {padding: 30px 30px 30px 30px;}
  .borderbottom {border-bottom: 1px solid #f2eeed;}
  .subhead {font-size: 15px; color: #ffffff; font-family: Ubuntu; letter-spacing: 10px;}
  .h1 {color: #ffffff; font-family: Ubuntu;}
  .h2, .bodycopy {color: #3d4042; font-family: Ubuntu;}
  .h1 {font-size: 30px; line-height: 38px; font-weight: bold;}
  .h2 {padding: 0 0 15px 0; font-size: 15px; line-height: 28px;}
  .bodycopy {font-size: 14px; line-height: 22px;}
  .button {text-align: center; font-size: 18px; font-family: Ubuntu; font-weight: bold; padding: 0 30px 0 30px;}
  .button a {color: #ffffff; text-decoration: none;}
  .footercopy {font-family: Ubuntu; font-size: 14px; color: #ffffff;}
  .footercopy a, .bodycopy a {color: #1479a3; text-decoration: none;}
  @media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
  body[yahoo] .hide {display: none!important;}
  body[yahoo] .buttonwrapper {background-color: transparent!important;}
  body[yahoo] .button {padding: 0px!important;}
  body[yahoo] .button a {background-color: #ffffff; padding: 15px 15px 13px!important;}
}
  </style>
</head>
<body yahoo bgcolor='#f4f4f4' style='font-family:colfax, helvetica neue, arial, verdana, sans-serif;'>
  <table width='100%' bgcolor='#f4f4f4' border='0' cellpadding='0' cellspacing='0'>
  <tr>
    <td>
      <table bgcolor='#edf3f5' class='content' align='center' cellpadding='0' cellspacing='0' border='0'>
        <tr>
          <td bgcolor='#ffffff' class='header'>
            <table width='' align='left' border='0' cellpadding='0' cellspacing='0'>  
              <tr>
                <td height='60' style=''>
                  <h1 style='font-size:20px; color:#fff;font-family: sans-serif;'><img src='https://rica.torusguru.com/img/logo.png' width='250px'></h1>
                </td>
              </tr>
            </table>
           
          </td>
        </tr>
        <tr>
          <td class='innerpadding borderbottom' bgcolor='#edf3f5' style='background:#edf3f5;'>
            <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                      <td class='h2' style='font-family: sans-serif;'>Dear RICA Staff, Faculty, Guests, and Students,</td>
                    </tr>
                    <tr>
                      <td class='bodycopy' style='font-family: sans-serif;'>
                        We are preparing for RICA’s first graduation ceremony which takes place Tuesday, 8th August 2023, at 11:00 a.m. Here is key information you need to know if you will be on campus that day:<br> 
                        <ol>
                          <li>Please arrive at a campus security gate at or before 8:00 a.m. sharp on Tuesday, August 8.</li>
                          <li>Please be prepared to show your National ID or Passport and/or staff/student ID card for security clearance.</li>
                          <li>All invited graduation attendees must be in their seats inside the graduation tent at the football pitch before 9:45 AM.</li>
                        </ol>
                        <ul>
                          <li>All guests and staff who plan to <b>arrive by private cars</b> must park at the hay field by the upper roundabout:</li>
                          <ul>
                            <li>After parking in the hayfield, please pass through the security checkpoint at the roundabout gate.</li>
                            <li>After passing through security you will board a shuttle bus which will take you to the graduation tent on the football pitch.</li>
                            <li>We will have limited coffee and tea available for visitors outside the M&I garage from 8:00 - 9:45 AM.</li>
                          </ul>
                          <li>Students and staff who <b>spend Monday night in a campus residence</b> will walk to a security checkpoint on the Spine Road near Ihema House.</li>
                          <ul>
                            <li>After clearing security, student and staff residents may go to the cafeteria for breakfast from 6:30 – 9:00 AM.</li>
                            <li>All students and unauthorized staff must leave the campus center before 9:15 AM.</li>
                            <li>Residents eating in the cafeteria will take a shuttle or walk to the graduation tent on the football pitch.</li>
                          </ul>
                          <li><b>Staff arriving by shuttle from Kigali:</b></li>
                          <ul>
                            <li>All RICA shuttles on Tuesday will leave Kigali 30 minutes early to arrive at the campus gate by 8:00 AM to clear security.</li>
                            <li>After passing through security you will board a shuttle bus which will take you to the campus center for breakfast which ends at 9:00 AM.</li>
                            <li>All unauthorized staff must leave campus center before 9:15 AM.</li>
                            <li>Staff who plan to attend graduation after eating in the cafeteria will take a shuttle or walk to the graduation tent on the football pitch.</li>
                            <li>All invited graduation attendees must be in their seats inside the tent by 9:45 AM.</li>
                            <li><b>Staff not attending the graduation event</b> will pass through RICA security at the roundabout when they arrive at 8 AM, clear security and take a shuttle to campus center for breakfast which ends at 9:00 AM.</li>
                            <li>If you usually work at a desk in the Howard G. Buffett Campus Center, you will need to move your workstation to Rufunzo for the day. All unauthorized staff must leave campus center before 9:15 AM.</li>
                            <li>The graduation ceremony will be livestreamed to Rufunzo so you will be able to observe graduation activities from your workstation.</li>
                          </ul>
                        </ul>
                        At 1 PM on Tuesday, all members of the campus community are encouraged to attend the luncheon at the Campus Center to celebrate with students, parents and other visitors. We look forward to spending this special day with you. Thank you to everyone who's done so much to prepare us for the event.<br><br>

                        If you have any questions or need assistance, don’t hesitate to contact Mr. Bayingana Emmanuel on <a href='tel:0788517649'>0788517649</a> or RICA Stakeholder Relations Team on <a href='tel:0788284489'>0788284489</a>.<br><br>

                        Regards,<br>
                        RICA organizing team
                      </td>
                    </tr>
                  </table>
          </td>
        </tr>
        <tr>
          <td class='footer' bgcolor='#cececd' style='border-bottom: 4px solid #1479a3;'>
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