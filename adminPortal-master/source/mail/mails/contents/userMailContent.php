<?php 

    // User registration
    $user_registration_email=
    '
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
     
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>The Future Summit</title>
      <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Ubuntu" />
      <style type="text/css">
      body {margin: 0; padding: 0; min-width: 100%!important;}
      img {height: auto;}
      .content {width: 100%; max-width: 600px;}
      .header {padding: 20px 30px 20px 30px;}
      .innerpadding {padding: 30px 30px 30px 30px;}
      .borderbottom {border-bottom: 1px solid #f2eeed;}
      .subhead {font-size: 15px; color: #ffffff; font-family: Ubuntu; letter-spacing: 10px;}
      .h1 {color: #ffffff; font-family: Ubuntu;}
      .h2, .bodycopy {color: #000000; font-family: Ubuntu;}
      .h1 {font-size: 30px; line-height: 38px; font-weight: bold;}
      .h2 {padding: 0 0 15px 0; font-size: 24px; line-height: 28px; font-weight: bold;}
      .bodycopy {font-size: 16px; line-height: 22px;}
      .button {text-align: center; font-size: 18px; font-family: Ubuntu; font-weight: bold; padding: 0 30px 0 30px;}
      .button a {color: #ffffff; text-decoration: none;}
      .footer {padding: 20px 30px 15px 30px;}
      .footercopy {font-family: Ubuntu; font-size: 14px; color: #ffffff;}
      .footercopy a {color: #ffffff; text-decoration: underline;}
    
      @media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
      body[yahoo] .hide {display: none!important;}
      body[yahoo] .buttonwrapper {background-color: transparent!important;}
      body[yahoo] .button {padding: 0px!important;}
      body[yahoo] .button a {background-color: #f47e20; padding: 15px 15px 13px!important;}
     }
      </style>
    </head>
    
    <body yahoo bgcolor="#f4f4f4">
      <table width="100%" bgcolor="#f4f4f4" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td>
          <table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td bgcolor="#2075bc" class="header">
                <table width="60" align="left" border="0" cellpadding="0" cellspacing="0">  
                  <tr>
                    <td height="60" style="padding: 10px 10px 10px 0;">
                      
                      <!-- <img class="fix" src="http://torusguru.com/future/img/logo.png" width="60" height="60" border="0" alt="" /> -->
                    </td>
                  </tr>
                </table>
                
                <table class="col425" align="left" border="0" cellpadding="0" cellspacing="0" style="width: auto; max-width: 425px;">
                  <tr>
                    <td height="70">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td class="h1" style="padding: 5px 0 0 0; text-align: center;">CJX - ADMINISTRATION MANAGEMENT SYSTEM</td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td class="innerpadding borderbottom">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="h2">Dear {name},</td>
                  </tr>
                  <tr>
                    <td class="bodycopy">
                      Your CJX-AMS {type} account has been successfully created. You can now access your account by connecting to the CJX-AMS. Below are your default credentials:<br><br>

                      <span>Username: {username}</span><br>
                      <span>Password: {password}</span><br><br>

                      <span>Remember to keep you credentials private! </span>
                      <br><br>
    
                       Also, remember to follow the COVID19 prevention measures by applying social distancing and wearing a face mask.
                    </td>
                  </tr>
                  
                </table>
              </td>
            </tr>
            
            <tr>
              <td class="footer" bgcolor="#2075bc">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align="center" class="footercopy">
                    Copyright &copy; 2021
                      <a href="#" class="unsubscribe"><font color="#ffffff"><strong>CJX-AMS - CONGO JIA XIN - Administration Management System</strong></font></a>
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
    </html>
    
    ';



    // User password reset
    $user_token_email=
    '
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
     
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>The Future Summit</title>
      <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Ubuntu" />
      <style type="text/css">
      body {margin: 0; padding: 0; min-width: 100%!important;}
      img {height: auto;}
      .content {width: 100%; max-width: 600px;}
      .header {padding: 20px 30px 20px 30px;}
      .innerpadding {padding: 30px 30px 30px 30px;}
      .borderbottom {border-bottom: 1px solid #f2eeed;}
      .subhead {font-size: 15px; color: #ffffff; font-family: Ubuntu; letter-spacing: 10px;}
      .h1 {color: #ffffff; font-family: Ubuntu;}
      .h2, .bodycopy {color: #000000; font-family: Ubuntu;}
      .h1 {font-size: 30px; line-height: 38px; font-weight: bold;}
      .h2 {padding: 0 0 15px 0; font-size: 24px; line-height: 28px; font-weight: bold;}
      .bodycopy {font-size: 16px; line-height: 22px;}
      .button {text-align: center; font-size: 18px; font-family: Ubuntu; font-weight: bold; padding: 0 30px 0 30px;}
      .button a {color: #ffffff; text-decoration: none;}
      .footer {padding: 20px 30px 15px 30px;}
      .footercopy {font-family: Ubuntu; font-size: 14px; color: #ffffff;}
      .footercopy a {color: #ffffff; text-decoration: underline;}
    
      @media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
      body[yahoo] .hide {display: none!important;}
      body[yahoo] .buttonwrapper {background-color: transparent!important;}
      body[yahoo] .button {padding: 0px!important;}
      body[yahoo] .button a {background-color: #f47e20; padding: 15px 15px 13px!important;}
     }
      </style>
    </head>
    
    <body yahoo bgcolor="#f4f4f4">
      <table width="100%" bgcolor="#f4f4f4" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td>
          <table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td bgcolor="#2075bc" class="header">
                <table width="60" align="left" border="0" cellpadding="0" cellspacing="0">  
                  <tr>
                    <td height="60" style="padding: 10px 10px 10px 0;">
                      
                      <!-- <img class="fix" src="http://torusguru.com/future/img/logo.png" width="60" height="60" border="0" alt="" /> -->
                    </td>
                  </tr>
                </table>
                
                <table class="col425" align="left" border="0" cellpadding="0" cellspacing="0" style="width: auto; max-width: 425px;">
                  <tr>
                    <td height="70">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td class="h1" style="padding: 5px 0 0 0; text-align: center;">CJX - ADMINISTRATION MANAGEMENT SYSTEM</td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td class="innerpadding borderbottom">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="h2">Dear {name},</td>
                  </tr>
                  <tr>
                    <td class="bodycopy">
                      You have recently requested to reset your password. Click the link below to reset your password. <br><br>
                     <span style="color: #27966d; width: 100%;">{token}</span>
                      <br><br>
    
                       If you did not initiate this request, you can just ignore this email.

                       <br><br>

                       Also, remember to follow the COVID19 prevention measures by applying social distancing and wearing a face mask.

                    </td>
                  </tr>
                  
                </table>
              </td>
            </tr>
            
            <tr>
              <td class="footer" bgcolor="#2075bc">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align="center" class="footercopy">
                    Copyright &copy; 2021
                      <a href="#" class="unsubscribe"><font color="#ffffff"><strong>CJX-AMS - CONGO JIA XIN - Administration Management System</strong></font></a>
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
    </html>
    
    ';


?>



















