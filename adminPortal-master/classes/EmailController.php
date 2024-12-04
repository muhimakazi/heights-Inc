<?php
class EmailController
{
  // ============================================ TORUS ADMIN EMAILS ============================================

  public static function sendEmailToAdminOnCreateAccount($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $password = $_data_->password;
    $urlPortal = DN;
    $_Email_ = $email;
    $_Subject_ = 'TORUS - Admin account';
    $_Message_ = self::emailSectionHeaderLayoutTorus() . "
                   <tr>
                      <td class='innerpadding borderbottom' bgcolor='#ffffff' style='background:#ffffff;'>
                        <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                          <tr>
                            <td class='h2' style='font-family: sans-serif;'>Dear $firstname</td>
                          </tr>
                          <tr>
                            <td class='bodycopy' style='font-family: sans-serif;'>
                              This is to notify you that your TORUS account has been created. Below are your creadentials: <br><br>

                              <span>Username: <strong style='margin-left: 10px;'> $email </strong> <span><br>
                              <span>Password: <strong style='margin-left: 14px;'> $password </strong>  <span><br>
                              <span>Link:  <strong style='margin-left: 52px;'> <a href='$urlPortal' style='text-decoration: none;' > Click here to login</strong></a> <span><br> 
                            </td>
                          </tr>
                          
                        </table>
                      </td>
                    </tr>
          " . self::emailLayoutSectionFooterTorus();
    $User = new User();     
    $User->send_mail_torus($_Email_, $_Message_, $_Subject_);
  }

  public static function sendEmailToAdminOnResetPassword($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $reset_link = $_data_->reset_link;

    $_Email_ = $email;
    $_Subject_ = 'TORUS - Reset password';
    $_Message_ = self::emailSectionHeaderLayoutTorus() . "
                   <tr>
                      <td class='innerpadding borderbottom' bgcolor='#ffffff' style='background:#ffffff;'>
                        <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                                <tr>
                                  <td class='h2' style='font-family: sans-serif;'>Hello,</td>
                                </tr>
                                <tr>
                                  <td class='bodycopy' style='font-family: sans-serif;'>
                                    You recently requested a password reset for TORUS. To update your login information, click on the link below. <br><br>
                                    <a href='$reset_link'  style='color:#fff; background-color:#0e3635; text-decoration:none; padding: 10px; width:150px;'>Reset password</a><br><br> 
                                     PLEASE NOTE: If you do not want to update your login, you may ignore this email and nothing will be changed. If you believe you received this email in error,<a href='https://cube.rw' style='text-decoration: none; color:#428bca;' target='_blank'> contact us.</a>
                                  </td>
                                </tr>
                              </table>
                      </td>
                    </tr>
                    
          " . self::emailLayoutSectionFooterTorus();
    $User = new User();     
    $User->send_mail_torus($_Email_, $_Message_, $_Subject_);
  }

  public static function eventContactForm($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $name = $_data_->name;
    $organisation = $_data_->organisation;
    $message = $_data_->message;
    $from = $_data_->from;
    $namefrom = $_data_->namefrom;
    $send_date = $_data_->send_date;
    $_Subject_ = $_data_->subject;

    $_Email_ = $from;
    $_Message_ = self::emailSectionHeaderLayoutTorus() . "
        <tr>
          <td class='innerpadding borderbottom' bgcolor='#ffffff' style='background:#ffffff;'>
            <table width='100%' border='0' cellspacing='0' cellpadding='0'>
              <tr>
                <td class='bodycopy' style='font-family: sans-serif;'>
                  <b class='h2' style='color:#0e3635;'>CONTACT FORM</b><br><br>
                  <b class='h2' style='color:#f7ad18;'>Event:</b> $namefrom<br>
                  <b class='h2' style='color:#f7ad18;'>Name:</b> $name<br>
                  <b class='h2' style='color:#f7ad18;'>Email:</b> $send_email<br>
                  <b class='h2' style='color:#f7ad18;'>Organisation:</b> $organisation<br>
                  <b class='h2' style='color:#f7ad18;'>Message:</b> $message<br>
                  <b class='h2' style='color:#f7ad18;'>Date:</b> $send_date<br>
                </td>
              </tr>
          </table>
        </td>
      </tr>
      " . self::emailLayoutSectionFooterTorus();
      $User = new User();
      $data = array(
        'email' => $_Email_,
        'message' => $_Message_,
        'subject' => $_Subject_,
        'from' => $from,
        'namefrom' => $namefrom,
        'attachement' => ''
      );
    $User->sendMail($data);
  }

  public static function emailSectionHeaderLayoutTorus()
  {
    $_HeaderLayout_ = "
      <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
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
      .subhead {font-size: 15px; color: #ffffff; letter-spacing: 10px;}
      .h1 {color: #ffffff;}
      .h2, .bodycopy {color: #7A838B;}
      .h1 {font-size: 30px; line-height: 38px; font-weight: bold;}
      .h2 {padding: 0 0 15px 0; font-size: 15px; line-height: 28px;}
      .bodycopy {font-size: 14px; line-height: 22px;}
      .button {text-align: center; font-size: 18px; font-weight: bold; padding: 0 30px 0 30px;}
      .button a {color: #ffffff; text-decoration: none;}
      .footercopy {font-size: 14px; color: #ffffff;}
      .footercopy a {color: #ffffff; text-decoration: underline;}

      @media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
      body[yahoo] .hide {display: none!important;}
      body[yahoo] .buttonwrapper {background-color: transparent!important;}
      body[yahoo] .button {padding: 0px!important;}
      body[yahoo] .button a {background-color: #0e3635; padding: 15px 15px 13px!important;}
     }
      </style>
    </head>

    <body yahoo bgcolor='#f4f4f4' style='font-family:Arial, sans-serif;'>
      <table width='100%' bgcolor='#f4f4f4' border='0' cellpadding='0' cellspacing='0'>
      <tr>
        <td>
          <table bgcolor='#ffffff' class='content' align='center' cellpadding='0' cellspacing='0' border='0'>
            <tr>
              <td bgcolor='#0e3635' class='header'>
                <table width='' align='left' border='0' cellpadding='0' cellspacing='0'>  
                  <tr>
                    <td height='60'>
                      <h1 style='font-size:20px; color:#fff;font-family: sans-serif;'><img src='https://admin.torusguru.com/data_system/img/logo-white.png' width='150px'></h1>
                    </td>
                  </tr>
                </table>
               
              </td>
            </tr>
          ";
    return $_HeaderLayout_;
  }

  public static function emailLayoutSectionFooterTorus()
  {
    $_FooterLayout_ = "
                      <tr>
              <td class='footer' bgcolor='#cececd' style='border-bottom:4px solid #0e3635;'>
              </td>
            </tr>
    </table>
    </td>
    </tr>
    </table>
    </body>

    </html>
    ";
    return $_FooterLayout_;
  }




  // ============================================ EVENT EMAILS ============================================

  public static function templateEmail($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $message = $_data_->message;
    $subject = $_data_->subject;
    $from = $_data_->from;
    $namefrom = $_data_->namefrom;
    $attachement = $_data_->attachement;
    $webUrl = $_data_->webUrl;
    $primaryColor = $_data_->primaryColor;
    $logo = $_data_->logo;
    $adminUrl = $_data_->adminUrl;
    $message = "
      <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
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
                    <td bgcolor='$primaryColor' class='header'>
                      <table width='' align='left' border='0' cellpadding='0' cellspacing='0'>  
                        <tr>
                          <td height='60' style=''>
                            <h1 style='font-size:20px;color:#fff;'><img src='$adminUrl/data_system/img/banner/$logo' style='height: 70px'></h1>
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
                            $message
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td class='footer' bgcolor='#cececd' style='border-bottom:4px solid $primaryColor'>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </body>
      </html>";

      $User = new User();
      $data = array(
        'email' => $email,
        'message' => $message,
        'subject' => $subject,
        'from' => $from,
        'namefrom' => $namefrom,
        'attachement' => $attachement
      );
    $User->sendMail($data);
  }



}



?>