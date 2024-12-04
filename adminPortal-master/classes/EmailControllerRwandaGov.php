<?php
class EmailControllerRwandaGov
{
  /** - 1 - Send Email - Participant - After Fully Completed Registration And Successful Payment - NEED TO PAY -  */
  public static function sendEmailToParticipantAfterSuccessfulRegistration($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;

    $_Email_ = $email;
    $_Subject_ = 'Registration: Rwanda National Day & Rwanda Business Forum';
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
  .h2 {padding:0 0 5px 0;font-size:15px;line-height:28px;color:#135027!important}
  .bodycopy {font-size:14px;line-height:22px;}
  .button {text-align:center;font-size:18px;font-weight:bold;padding:0 30px 0 30px;}
  .button a {color:#ffffff;text-decoration:none;}
  .footercopy {font-size:14px;color:#ffffff;}
  .footercopy a, .bodycopy a {color:blue;text-decoration:none;}
  .text-yellow{color:#f7ad18}
  ul{margin-top:0}
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
          <td bgcolor='#135027' class='header'>
            <table width='' align='left' border='0' cellpadding='0' cellspacing='0'>  
              <tr>
                <td height='60' style=''>
                  <h1 style='font-size:20px;color:#fff;'><img src='https://rwandaexpodoha.com/img/doha-logo.png' width='350px'></h1>
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
                  Thank you for registering to attend the Rwanda National Day & Rwanda Business Forum that will be held on 8 February 2024 in Doha, Qatar.<br><br>
                  Please note that your registration will be confirmed when you receive an approval email which will be shared with you within 72 hours.<br><br>
                  <b class='h2' style='color:#007377;'>STAY CONNECTED</b><br>
                  <b>Twitter:</b> <a href='https://twitter.com/rwandaexpo2023'>@rwandaexpo2023</a><br>
                  <b>Instagram:</b> <a href='https://www.instagram.com/rwandainexpo2023doha/'>@rwandainexpo2023doha</a><br>
                  <b>LinkedIn:</b> <a href='https://www.linkedin.com/company/rwandaexpo2023/'>@rwandaexpo2023</a> <br>
                  <b>Connect with our official tags:</b> #EXPO2023DOHA<br><br>
                  Regards,<br>
                  Rwanda National Day & Business Forum team<br>
                  <a href='mailto:info@rwandaexpodoha.com'>info@rwandaexpodoha.com</a>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class='footer' bgcolor='#cececd' style='border-bottom:4px solid #135027;'>
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
        'from' => 'rwandaexpodoha@torusguru.com',
        'namefrom' => 'Rwanda National Day & Rwanda Business Forum',
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
    $_Subject_ = 'Rwanda National Day & Rwanda Business Forum';
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
  .h2 {padding:0 0 5px 0;font-size:15px;line-height:28px;color:#135027!important}
  .bodycopy {font-size:14px;line-height:22px;}
  .button {text-align:center;font-size:18px;font-weight:bold;padding:0 30px 0 30px;}
  .button a {color:#ffffff;text-decoration:none;}
  .footercopy {font-size:14px;color:#ffffff;}
  .footercopy a, .bodycopy a {color:blue;text-decoration:none;}
  .text-yellow{color:#f7ad18}
  ul{margin-top:0}
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
          <td bgcolor='#135027' class='header'>
            <table width='' align='left' border='0' cellpadding='0' cellspacing='0'>  
              <tr>
                <td height='60' style=''>
                  <h1 style='font-size:20px;color:#fff;'><img src='https://rwandaexpodoha.com/img/doha-logo.png' width='350px'></h1>
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
                  Thank you for applying to attend Rwanda National Day & Business Forum.<br><br>
                  Your registration is confirmed.<br><br>
                  Please see below for important information to facilitate your attendance.<br><br>
                  <b class='h2' style='color:#007377;'>VENUE & PROGRAM</b>
                  <ul>
                    <li>The event will be held at EXPO 2023 Doha which will located in Al Bidda Park, Doha </li>
                    <li>Arrival time for the event is 9am at Al Bidda Park </li>
                    <li>You can view the program <a href='https://rwandaexpodoha.com/#program'>here</a></li>
                  </ul>
                  <b class='h2' style='color:#007377;'>ACCREDITATION</b>
                  <ul>
                    <li>You will receive an email before the event on where to collect your badge</li>
                  </ul>
                  <b class='h2' style='color:#007377;'>IMPORTANT INFORMATION FOR DELEGATES TRAVELLING FROM RWANDA</b>
                  <ul>
                    <li><b class='text-yellow'>Travel Visas</b></li>
                    <ul>
                      <li>Rwanda passport holders </li>
                      <ul>
                        <li>Entry to Qatar is visa-free</li>
                        <li>The passport must be valid for at least three months from the date of arrival in Qatar.</li>
                      </ul>
                      <li>Other passport holders </li>
                      <ul>
                        <li>Qatar is one of the world's most welcoming countries for visitors. Nationalities from 101 countries can enter visa-free, and all others can apply for an e-visa using the Hayya platform online.</li>
                        <li>Please visit <a href='https://visitqatar.com/intl-en/practical-info/visas'>https://visitqatar.com/intl-en/practical-info/visas</a></li>
                      </ul><br>
                    </ul>
                    <li><b class='text-yellow'>Flying from Kigali to Doha</b></li>
                    <ul>
                      <li>The airport is named Hamad International Airport</li>
                      <li>Rwandair offers direct flights from Kigali to Doha, 6 times a week.</li>
                      <ul>
                        <li>Visit <a href='https://www.rwandair.com'>https://www.rwandair.com</a> for more details and to book your flight.</li>
                        <li>Rwandair can also be reached on</li>
                        <ul>
                          <li>+250 780 967 325 (Head Office Sales)</li>
                          <li>+250 780 967 289 (Kigali International Airport Sales)</li>
                          <li>Email: <a href='mailto:reservations@rwandair.com'>reservations@rwandair.com</a></li>
                        </ul>
                      </ul>
                    </ul><br>
                    <li><b class='text-yellow'>Travelling from Hamad International Airport to your hotel</b></li>
                    <ul>
                      <li>Taxis are available 24 hours a day at Hamad International Airport. </li>
                    </ul><br>
                    <li><b class='text-yellow'>Accommodation</b></li>
                    <ul>
                      <li><b>Recommended hotels</b></li>
                      <ul>
                        We have negotiated rates with the hotels below. Please book early to avoid disappointment.
                        <li><b>Crowne Plaza An IHG Hotel Doha West Bay</b></li>
                        <ul>
                          <li>Rates start from QAR 410 for a single standard room and QAR 460 for a double room including breakfast </li>
                          <li>Contact details </li>
                          <ul>
                            <li>Mr. Angad Singh Chatwal
                            <li><a href='mailto:AngadSingh.Chatwal@ihg.com'>AngadSingh.Chatwal@ihg.com</a></li>
                          </ul>
                        </ul>
                        <li><b>Hilton Doha</b></li>
                        <ul>
                          <li>Rates start from QAR 500 for a single standard room and QAR 550 for a double room including breakfast</li>
                          <li>Contact details </li>
                          <ul>
                            <li>Ms. Layla Yass</li>
                            <li><a href='Layal.yassin@hilton.com'>Layal.yassin@hilton.com</a></li>
                          </ul>
                        </ul>
                      </ul>
                      <li><b>Other hotels</b></li>
                      <ul>
                        <li>You may also use other services to book your accommodation such as your travel agent or your preferred online booking services.</li>
                        <li>Use ‘Al Bidda Park, Doha’ as a landmark when searching for hotels near the venue </li>
                      </ul>
                    </ul><br>
                    <li><b class='text-yellow'>Getting around Doha</b></li>
                    <ul>
                      <li>Doha has accessible and affordable public transport systems which include:</li>
                      <ul>
                        <li>Doha Metro </li>
                        <li>Bus services </li>
                        <li>Ride hailing apps (Karwa & Uber) </li>
                      </ul>
                      <li>More information can be found <a href='https://visitqatar.com/intl-en/practical-info/getting-around'>here</a></li>
                    </ul><br>
                    <li><b class='text-yellow'>Medical & Insurance information</b></li>
                    <ul>
                      <li>Visitors who are eligible to obtain a visitor visa on arrival at the border crossings are exempt from obtaining a visitor's insurance policy for the first 30 days from the date of entry to the country. However, if you wish to extend your stay for more than 30 days from the date of entry, they are required to purchase a health insurance policy.</li>
                      <li>Those who don’t already have an insurance policy are welcome to purchase one on arrival in the State of Qatar. For more information on travel insurance, click <a href='https://www.moph.gov.qa/english/derpartments/policyaffairs/hfid/Pages/Health-Insurance-Scheme.aspx'>here</a>.</li>
                      <li>Mandatory health insurance is required for visitors who are required to obtain a prior entry visa before reaching the border crossings. A visitor visa will not be granted until the visitor has purchased a visitor's insurance policy.</li>
                      <li>If you are on medication, please carry sufficient supply for the duration of your stay. The organisers are not responsible for medical costs. In this context, it is strongly recommended that you obtain international medical insurance for the entire period of participation, including travel time.</li>
                    </ul><br>
                    <li><b class='text-yellow'>Other useful information</b></li>
                    <ul>
                      <li>Please visit <a href='https://visitqatar.com'>https://visitqatar.com</a> for more information including useful tips that will help better navigate the local culture and fully enjoy your time in Qatar.</li>
                    </ul>
                  </ul>
                  <b class='h2' style='color:#007377;'>STAY CONNECTED</b><br>
                  <b>Twitter:</b> <a href='https://twitter.com/rwandaexpo2023'>@rwandaexpo2023</a><br>
                  <b>Instagram:</b> <a href='https://www.instagram.com/rwandainexpo2023doha/'>@rwandainexpo2023doha</a><br>
                  <b>LinkedIn:</b> <a href='https://www.linkedin.com/company/rwandaexpo2023/'>@rwandaexpo2023</a> <br>
                  <b>Connect with our official tags:</b> #EXPO2023DOHA<br><br>
                  Regards,<br>
                  Rwanda National Day & Business Forum team<br>
                  <a href='mailto:info@rwandaexpodoha.com'>info@rwandaexpodoha.com</a>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class='footer' bgcolor='#cececd' style='border-bottom:4px solid #135027;'>
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
        'from' => 'rwandaexpodoha@torusguru.com',
        'namefrom' => 'Rwanda National Day & Rwanda Business Forum',
        'attachement' => ''
      );
    $User->sendMail($data);
  }

}


?>