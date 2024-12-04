<?php
/**
 * Email
 * @author Ezechiel Kalengya Ezpk [ezechielkalengya@gmail.com]
 * Software Developer */
class EmailController
{
  /** - 2 - Send Email - Admin - After Create Account -  -  */
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

    // Email::sendEmail($_Email_, $_Subject_, $_Message_);     
    $User = new User();     
    $User->send_mail_torus($_Email_, $_Message_, $_Subject_);
  }


  /** - 2.2 - Send Email - Admin - Reset password -  -  */
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

    // Email::sendEmail($_Email_, $_Subject_, $_Message_);     
    $User = new User();     
    $User->send_mail_torus($_Email_, $_Message_, $_Subject_);
  }


  /** - 1 - Send Email - Participant - After CBO Application - NEED TO PAY -  */
  public static function sendEmailToParticipantAfterCBOApllication($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;

    $_Email_ = $email;
    $_Subject_ = 'Inclusive Fintech Forum 2023 CBO Application';
    $_Message_ = self::emailSectionHeaderLayout() . "
                  <tr>
                    <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                          <td class='h2' style=' padding: 15px 0px 30px 0px; '>Dear $firstname,</td>
                        </tr>
                        <tr>
                          <td class='bodycopy'>
                          Thank you for registering your interest to attend the <br><b>Inclusive Fintech Forum 2023</b> as a CBO.<br><br>
                          The APAC Event Secretariat will review your application and revert within the next <br>5 working days.
                          </td>
                        </tr>
                        
                      </table>
                    </td>
                  </tr>

        " . self::emailLayoutSectionFooter();

    // Email::send($_Email_, $_Subject_, $_Message_);
        $User = new User();
    $User->send_mail($_Email_, $_Message_, $_Subject_);
  }


  /** - 2 - Send Email - Participant - After CBO Acceptance with Request for Payment - NEED TO PAY -  */
  public static function sendEmailToParticipantAfterCBOAcceptanceWithRequestForPayment($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $payment_link = $_data_->payment_link;

    $_Email_ = $email;
    $_Subject_ = 'Payment information - Inclusive Fintech Forum 2023 CBO Application';
    $_Message_ = self::emailSectionHeaderLayout() . "
                  <tr>
                    <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                          <td class='h2' style=' padding: 15px 0px 30px 0px; '>Dear $firstname,</td>
                        </tr>
                        <tr>
                          <td class='bodycopy'>
                          We are pleased to inform you that your CBO application to attend the <b>Inclusive Fintech Forum 2023,</b> that will be held in Kigali from 20 - 22 June 2023, has been accepted. <br><br>
                          Please proceed to the following link to complete your payment <a href='$payment_link'> $payment_link </a>
                          </td>
                          <tr><td class='h2' style='padding:0;'><b>Programme </b> </td></tr>
                          <tr>
                            <td class='h2'>
                                <ul>
                                  <li>You can view the congress programme at <a href='https://apacongress.africa/programme/'>apacongress.africa/programme</a></li>
                                </ul>
                            </td>
                          </tr>
                        </tr>
                        
                      </table>
                    </td>
                  </tr>

        " . self::emailLayoutSectionFooter();

    // Email::send($_Email_, $_Subject_, $_Message_);
        $User = new User();
    $User->send_mail($_Email_, $_Message_, $_Subject_);
  }


  /** - 3 - Send Email - Participant - After Fully Completed Registration And Successful Payment - NEED TO PAY -  */
  public static function sendEmailToParticipantAfterFullyCompletedRegistrationAndSuccessfulPayment($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $payment_receipt_link = $_data_->payment_receipt_link;
    $invitation_letter_link = $_data_->invitation_letter_link;

    $_Email_ = $email;
    $_Subject_ = 'Confirmation - Inclusive Fintech Forum 2023 Registration';
    $_Message_ = self::emailSectionHeaderLayout() . "
                 <tr>
                    <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                          <td class='h2' style=' padding: 15px 0px 30px 0px; '>Dear $firstname,</td>
                        </tr>
                        <tr>
                          <td class='bodycopy'>
                          Thank you for registering to attend the Inclusive Fintech Forum 2023 that will be held in Kigali from 20 - 22 June 2023.</td>
                          <tr><td class='h2' style='padding:0;'>Your receipt is attached to this email.</td></tr>

                          <tr><td class='h2' style='padding:0;'><b>Invitation To The Africa Protected Areas Congress</b> </td></tr>
                          <tr>
                            <td class='h2'>
                              <ul>
                                <li><a href='$invitation_letter_link' target='_blank'>Click here</a> to download your invitation letter   </li>
                              </ul>
                              
                            </td>
                          </tr>
                          
                          " . self::emailLayoutSectionFooterInfo() . "


                        </tr>
                        
                      </table>
                    </td>
                  </tr>

        " . self::emailLayoutSectionFooter();

    // Email::send($_Email_, $_Subject_, $_Message_);
        $User = new User();
    $User->send_mail($_Email_, $_Message_, $_Subject_);
  }


  /** - 4 - Send Email - Participant - Email received for those who successfully complete registration and choose to pay by bank transfer or direct deposit - NEED TO PAY -  */
  public static function sendEmailToParticipantOnRequestToPayByBankTransferOrDirectDeposit($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $payment_invoice_link = $_data_->payment_invoice_link;

    $_Email_ = $email;
    $_Subject_ = 'Confirmation - Inclusive Fintech Forum 2023 Registration';
    $_Message_ = self::emailSectionHeaderLayout() . "
                 <tr>
                    <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                          <td class='h2' style=' padding: 15px 0px 30px 0px; '>Dear $firstname,</td>
                        </tr>
                        <tr>
                        <tr>
                          <tr><td class='h2'>We have received your request to pay by bank transfer.<br><a href='$payment_invoice_link'>Click here</a> to download the payment details & invoice. </td></tr>
                          <tr><td class='h2'>Please ensure that you follow the instructions to ensure your payment is tracked and credited to your registration.</td></tr>
                          <tr><td class='h2'>You will be notified when we receive your payment.</td></tr>
                          <tr><td class='h2' style='padding:0;color:#37af47;text-transform:uppercase'><b>Important information for international delegates </b> </td></tr>

                          <tr>
                            <td class='h2'>
                                <ul>
                                  <li>You will receive travel and accommodation information with the official APAC hotels after confirmation of your registration & receipt of payment.  </li>
                                  <li>Please do not book your travel or accommodation until you have received our notification confirming receipt of payment.</li>

                                </ul>
                            </td>
                          </tr>
                        </tr>
                      </table>
                    </td>
                  </tr>

        " . self::emailLayoutSectionFooter();

    // Email::send($_Email_, $_Subject_, $_Message_);
        $User = new User();
    $User->send_mail($_Email_, $_Message_, $_Subject_);
  }


  /** - 5 - Send Email - Participant - Email received after bank transfer or direct deposit is received - NEED TO PAY -  */
  public static function sendEmailToParticipantAfterBankTransferOrDirectDepositReceived($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $payment_receipt_link = $_data_->payment_receipt_link;
    $invitation_letter_link = $_data_->invitation_letter_link;

    $_Email_ = $email;
    $_Subject_ = 'Payment confirmation - Inclusive Fintech Forum 2023 Registration';
    $_Message_ = self::emailSectionHeaderLayout() . "
                 <tr>
                    <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                          <td class='h2' style=' padding: 15px 0px 30px 0px; '>Dear $firstname,</td>
                        </tr>
                        <tr>
                        <tr>
                          <tr><td class='h2'>Thank you for registering to attend the <b>Inclusive Fintech Forum 2023</b>  that will be held in Kigali from 20 - 22 June 2023. </tr>
                          <tr><td class='h2'>We have received your payment by bank transfer.<br>Your receipt is attached to this email. </td></tr>
                          <tr>
                            <td class='h2'>
                                <ul>
                                  <li><a href='$payment_receipt_link' target='_blank'>Click here</a> to download your receipt.  </li>
                                </ul>
                              </td>
                            </tr>
                          </tr>
                          <tr><td class='h2' style='padding:0;'><b>Invitation To The Africa Protected Areas Congress</b> </td></tr>
                          <tr>
                            <td class='h2'>
                              <ul>
                                <li><a href='$invitation_letter_link' target='_blank'>Click here</a> to download your invitation letter   </li>
                              </ul>
                            </td>
                          </tr>

                          " . self::emailLayoutSectionFooterInfo() . "



                        </tr>
                      </table>
                    </td>
                  </tr>

        " . self::emailLayoutSectionFooter();

    // Email::send($_Email_, $_Subject_, $_Message_);
        $User = new User();
    $User->send_mail($_Email_, $_Message_, $_Subject_);
  }


  /** - 6 - Send Email - Participant - Email received when credit card payment fails  - NEED TO PAY -  */
  public static function sendEmailToParticipantWhenCreditCardPaymentFails($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $payment_link = $_data_->payment_link;

    $_Email_ = $email;
    $_Subject_ = 'Payment error - Inclusive Fintech Forum 2023 Registration';
    $_Message_ = self::emailSectionHeaderLayout() . "
                  <tr>
                    <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                          <td class='h2' style=' padding: 15px 0px 30px 0px; '>Dear $firstname,</td>
                        </tr>
                        
                        <tr><td class='h2' style='padding:0;'>It appears that your card payment has not gone through. Kindly follow this link to go through the payment process again <a href='$payment_link'> $payment_link </a></td></tr>
                        <tr><td class='h2' style='padding:0;'>If you are still experiencing challenges, please email <a href='mailto:ianangwe@awf.org'>IAnangwe@awf.org</a> with your name & telephone number and our team will get back to you to assist.</td></tr>
                      </table>
                    </td>
                  </tr>

    " . self::emailLayoutSectionFooter();

    // Email::send($_Email_, $_Subject_, $_Message_);
    $User = new User();
    $User->send_mail($_Email_, $_Message_, $_Subject_);
  }


  /** - 7 - Send Email - Participant - Email received when bank transfer has not appeared in the IUCN account 7 days after registration -  */
  public static function sendEmailToParticipantWhenBankTransafertNotAppearedInIUCNAccount7DaysAfterRegistration($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $payment_receipt_link = $_data_->payment_receipt_link;

    $_Email_ = $email;
    $_Subject_ = 'Payment query - Inclusive Fintech Forum 2023 Registration';
    $_Message_ = self::emailSectionHeaderLayout() . "
                    <tr>
                      <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
                        <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                          <tr>
                          <td class='h2' style=' padding: 15px 0px 30px 0px; '>Dear $firstname,</td>
                          </tr>
                          
                          <tr><td class='h2' style='padding:0;'>It appears that your bank transfer payment has not been received to date. 
                            Please send us the proof of transfer to help us trace it if you have already made the payment. 
                            If you have not made the transfer yet, please let us know when you intend to so we may trace the payment & confirmation your attendance to the congress. <br>
                            Please contact us using the details below if you have any queries on your payment process. 
                          </td></tr>
                        
                        </table>
                      </td>
                    </tr>

      " . self::emailLayoutSectionFooter();

    // Email::send($_Email_, $_Subject_, $_Message_);
      $User = new User();
    $User->send_mail($_Email_, $_Message_, $_Subject_);
  }



  /** - 8 - Send Email - Participant - Email received after successful registration for those that do not pay (Speakers, Invited guests, Secratariat, Staff etc) -  */
  public static function sendEmailToParticipantAfterSuccessfulRegistrationFree($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;

    $_Email_ = $email;
    $_Subject_ = 'Confirmation - Inclusive Fintech Forum 2023 Registration';
    $_Message_ = self::emailSectionHeaderLayout() . "
                  <tr>
                    <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                        <td class='h2' style=' padding: 15px 0px 30px 0px; '>Dear $firstname,</td>
                        </tr>
                        <tr>
                        <tr>
                          <tr><td class='h2'>Thank you for registering to attend the <b>Inclusive Fintech Forum 2023</b>  that will be held in Kigali, Rwanda, from 20 - 22 June 2023.  </tr>
            
                          " . self::emailLayoutSectionFooterInfo() . "



                        </tr>
                      </table>
                    </td>
                  </tr>

      " . self::emailLayoutSectionFooter();

    // Email::send($_Email_, $_Subject_, $_Message_);
      $User = new User();
    $User->send_mail($_Email_, $_Message_, $_Subject_);
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
    $_Subject_ = 'Registration confirmation for the Inclusive FinTech Forum 2023';
    $_Message_ = self::emailSectionHeaderLayout() . "
                  <tr>
                    <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
                        <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                            <tr>
                                <td class='h2' style=' padding: 15px 0px 30px 0px; '>Dear $firstname,</td>
                            </tr>
                            <tr>
                                <td class='bodycopy'> Thank you for applying to attend the Inclusive FinTech Forum that will be
                                    held from 20 - 22 June 2023 in Kigali, Rwanda. Your application has been approved. <br><br> </td>
                            </tr>
                            <tr>
                                <td class='bodycopy'> Your registration number is <b>$participant_code</b> <br><br> </td>
                            </tr>
                            <tr>
                                <td class='h2' style='padding:0; color:#d90368;text-transform:uppercase'><b>BADGE COLLECTION </b> </td>
                            </tr>
                            <tr>
                                <td class='h2'>You will receive information on how and when to collect your badge from the
                                    Accreditation site before the event. Please bring the identification document you used in your
                                    registration to collect your badge.</td>
                            </tr>
                            <tr>
                                <td class='h2' style='padding:0; color:#d90368;text-transform:uppercase'><b>PROGRAMME</b> </td>
                            </tr>
                            <tr>
                                <td class='h2'>You can view the Forum programme <a
                                        href='https://www.inclusiveFinTechforum.com/programme/agenda' target='_blank'>here</a>.</td>
                            </tr> <!-- Important info -->
                            <tr>
                                <td class='h2' style='padding:0; color:#d90368;text-transform:uppercase'><b>Important Information for
                                        Delegates: </b> </td>
                            </tr>
                            <tr>
                                <td class='h2' style='padding: 5px 0;'><b>Accommodation</b> </td>
                            </tr>
                            <tr>
                                <td class='h2' style=' padding: 5px 0;'>
                                      <ul>
                                          <li><b>Radisson Blu Kigali </b></li>
                                          <ul>
                                            <li>The venue is directly attached to the Radisson Blu Kigali. You may book a room at a negotiated rate for our delegates through this <a href='https://www.radissonhotels.com/en-us/booking/room-display?checkInDate=2023-06-19&checkOutDate=2023-06-20&adults%5B%5D=1&children%5B%5D=0&searchType=pac&promotionCode=FIN2023&voucher=&brandFirst=rdb&hotelCode=RWKGLCCR' target='_blank'>link</a></li>
                                          </ul>
                                          <li><b>Other hotels in Kigali</b></li>
                                          <ul>
                                            <li>You may also book accommodation at other hotels through this <a href='https://www.travelzuri.com/B2C/Admin/GTC/EventInfoCart.aspx?Ref_Type=HTL&CID=390&CityCode=KGL&EventName=Inclusive%20Fintech%20Forum&SSr=EVTHL' target='_blank'>link</a></li>
                                          </ul>
                                      </ul>

                                </td>
                            </tr>
                            <tr>
                                <td class='h2' style='padding: 5px 0;'><b>Travelling to Rwanda</b> </td>
                            </tr>
                            <tr>
                                <td class='h2'>
                                    <ul>
                                        <li>Citizens of countries who are members of the Commonwealth, African Union and La
                                            Francophonie, obtain a visa upon arrival. Visa fees are waived for a visit of 30 days.</li>
                                        <li>More information on travel to Rwanda can be found <a
                                                href='https://migration.gov.rw/visa-on-arrival' target='_blank'>here</a></li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td class='h2' style='padding: 5px 0;'><b>Health Travel guidelines</b> </td>
                            </tr>
                            <tr>
                                <td class='h2'>
                                    <ul>
                                        <li><b>Covid-19</b>
                                            <ul>
                                                <li>Rwanda’s response to the Covid-19 pandemic has been swift and robust. Measures are
                                                    in place to ensure that your experience is safe and productive. Please click here <a
                                                        href='https://www.rbc.gov.rw/index.php?id=188' target='_blank'>here</a> to view
                                                    current travel guidelines to Rwanda. Please note that these will be updated on a
                                                    regular basis. </li>
                                            </ul>
                                        </li>
                                        <li><b>Yellow Fever</b>
                                            <ul>
                                                <li>Yellow Fever vaccination certificate <b>is not required</b> for travellers coming
                                                    from Yellow Fever <b>non-endemic countries.</b></li>
                                                <li>Yellow Fever vaccination certificate <b>is mandatory</b> for travellers coming from
                                                    Yellow Fever <b>endemic countries.</b> </li>
                                                <li>A traveller coming from Yellow Fever <b>endemic country and does not have a yellow
                                                        fever certificate will be vaccinated upon entry.</b></li>
                                            </ul>
                                        </li>
                                        <li><b>Additional information</b>
                                            <ul>
                                                <li>Travellers should also follow their country’s health guidelines to facilitate their
                                                    travel to and from Rwanda. </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

        " . self::emailLayoutSectionFooter();

    // Email::send($_Email_, $_Subject_, $_Message_);
      $User = new User();
    $User->send_mail($_Email_, $_Message_, $_Subject_);
  }


  public static function sendEmailToParticipantAfterSuccessfulRegistrationFreeOnApprovalCompIndustry($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $invitation_letter_link = $_data_->invitation_letter_link;
    $approval_status = $_data_->approval_status;
    $participant_code = $_data_->participant_code;

    $_Email_ = $email;
    $_Subject_ = 'Registration confirmation for the Inclusive FinTech Forum 2023';
    $_Message_ = self::emailSectionHeaderLayout() . "
                  <tr>
                    <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
                        <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                            <tr>
                                <td class='h2' style=' padding: 15px 0px 30px 0px; '>Dear $firstname,</td>
                            </tr>
                            <tr>
                                <td class='bodycopy'> Thank you for applying to attend the Inclusive FinTech Forum that will be
                                    held from 20 - 22 June 2023 in Kigali, Rwanda. Your application has been approved. <br><br> </td>
                            </tr>
                            <tr>
                                <td class='bodycopy'> Your registration number is <b>$participant_code</b> <br><br> </td>
                            </tr>
                            <tr>
                                <td class='bodycopy'>Share with your network, colleagues, and business partners that you will be attending the Inclusive FinTech Forum! Pick a graphic and share it via Twitter, LinkedIn, Whatsapp and more: <a
                                        href='https://app.gleanin.com/share/campaigns/11411/variants' target='_blank'>Click here</a></b> <br><br> </td>
                            </tr>
                            <tr>
                                <td class='h2' style='padding:0; color:#d90368;text-transform:uppercase'><b>BADGE COLLECTION </b> </td>
                            </tr>
                            <tr>
                                <td class='h2'>You will receive information on how and when to collect your badge from the
                                    Accreditation site before the event. Please bring the identification document you used in your
                                    registration to collect your badge.</td>
                            </tr>
                            <tr>
                                <td class='h2' style='padding:0; color:#d90368;text-transform:uppercase'><b>PROGRAMME</b> </td>
                            </tr>
                            <tr>
                                <td class='h2'>You can view the Forum programme <a
                                        href='https://www.inclusiveFinTechforum.com/programme/agenda' target='_blank'>here</a>.</td>
                            </tr> <!-- Important info -->
                            <tr>
                                <td class='h2' style='padding:0; color:#d90368;text-transform:uppercase'><b>Important Information for
                                        Delegates: </b> </td>
                            </tr>
                            <tr>
                                <td class='h2' style='padding: 5px 0;'><b>Accommodation</b> </td>
                            </tr>
                            <tr>
                                <td class='h2' style=' padding: 5px 0;'>
                                      <ul>
                                          <li><b>Radisson Blu Kigali </b></li>
                                          <ul>
                                            <li>The venue is directly attached to the Radisson Blu Kigali. You may book a room at a negotiated rate for our delegates through this <a href='https://www.radissonhotels.com/en-us/booking/room-display?checkInDate=2023-06-19&checkOutDate=2023-06-20&adults%5B%5D=1&children%5B%5D=0&searchType=pac&promotionCode=FIN2023&voucher=&brandFirst=rdb&hotelCode=RWKGLCCR' target='_blank'>link</a></li>
                                          </ul>
                                          <li><b>Other hotels in Kigali</b></li>
                                          <ul>
                                            <li>You may also book accommodation at other hotels through this <a href='https://www.travelzuri.com/B2C/Admin/GTC/EventInfoCart.aspx?Ref_Type=HTL&CID=390&CityCode=KGL&EventName=Inclusive%20Fintech%20Forum&SSr=EVTHL' target='_blank'>link</a></li>
                                          </ul>
                                      </ul>

                                </td>
                            </tr>
                            <tr>
                                <td class='h2' style='padding: 5px 0;'><b>Travelling to Rwanda</b> </td>
                            </tr>
                            <tr>
                                <td class='h2'>
                                    <ul>
                                        <li>Citizens of countries who are members of the Commonwealth, African Union and La
                                            Francophonie, obtain a visa upon arrival. Visa fees are waived for a visit of 30 days.</li>
                                        <li>More information on travel to Rwanda can be found <a
                                                href='https://migration.gov.rw/visa-on-arrival' target='_blank'>here</a></li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td class='h2' style='padding: 5px 0;'><b>Health Travel guidelines</b> </td>
                            </tr>
                            <tr>
                                <td class='h2'>
                                    <ul>
                                        <li><b>Covid-19</b>
                                            <ul>
                                                <li>Rwanda’s response to the Covid-19 pandemic has been swift and robust. Measures are
                                                    in place to ensure that your experience is safe and productive. Please click here <a
                                                        href='https://www.rbc.gov.rw/index.php?id=188' target='_blank'>here</a> to view
                                                    current travel guidelines to Rwanda. Please note that these will be updated on a
                                                    regular basis. </li>
                                            </ul>
                                        </li>
                                        <li><b>Yellow Fever</b>
                                            <ul>
                                                <li>Yellow Fever vaccination certificate <b>is not required</b> for travellers coming
                                                    from Yellow Fever <b>non-endemic countries.</b></li>
                                                <li>Yellow Fever vaccination certificate <b>is mandatory</b> for travellers coming from
                                                    Yellow Fever <b>endemic countries.</b> </li>
                                                <li>A traveller coming from Yellow Fever <b>endemic country and does not have a yellow
                                                        fever certificate will be vaccinated upon entry.</b></li>
                                            </ul>
                                        </li>
                                        <li><b>Additional information</b>
                                            <ul>
                                                <li>Travellers should also follow their country’s health guidelines to facilitate their
                                                    travel to and from Rwanda. </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

        " . self::emailLayoutSectionFooter();

    // Email::send($_Email_, $_Subject_, $_Message_);
      $User = new User();
    $User->send_mail($_Email_, $_Message_, $_Subject_);
  }


  public static function sendEmailToParticipantForAccreditation($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $accommodation_link = $_data_->accommodation_link;

    $_Email_ = $email;
    $_Subject_ = 'Important information about Inclusive FinTech Forum';
    $_Message_ = self::emailSectionHeaderLayout() . "
                  <tr>
                    <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
                        <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                            <tr>
                                <td class='h2' style=' padding: 15px 0px 30px 0px; '>Dear $firstname,</td>
                            </tr>
                            <tr>
                                <td class='bodycopy'>Thank you for registering to attend the Inclusive FinTech Forum, we look forward to welcoming you to Kigali, Rwanda from 20-22 June 2023.<br><br> </td>
                            </tr>
                            <tr>
                                <td class='bodycopy'> We have compiled some important information to help you make the most of your time at the Forum:<br><br> </td>
                            </tr>
                            <tr>
                                <td class='h2' style='padding:0; color:#d90368;text-transform:uppercase'><b>ACCOMMODATION & TRANSPORT</b></td>
                            </tr>
                            <tr>
                              <td class='h2'>
                                <ul>
                                    <li><b>IMPORTANT: </b>We require arrival & accommodation details to ensure your transport from the Hotel to the Kigali Convention Center is booked. Please fill in the information <a href='$accommodation_link' target='_blank'>here</a> </li>
                                    <li>Please note that the shuttle services will be offered for the hotels listed <a href='https://iff.torusguru.com/hotels' target='_blank'>here</a></li>
                                    <li>A shuttle service will operate between 20 – 22 June 2023 from the official forum hotels to the Kigali Convention Center in the morning & back to the hotel in the evening. Timings will be available from the forum’s help desk at the Kigali Convention Center. </li>
                                </ul>
                              </td>
                            </tr> 
                            <tr>
                                <td class='h2' style='padding:0; color:#d90368;text-transform:uppercase'><b>BADGE COLLECTION </b> </td>
                            </tr>
                            <tr>
                                <td class='h2' style='padding:0;'>Badges will be collected from the Kigali Convention Center. Please bring the identification you used during the registration process.</td>
                            </tr>
                            <tr>
                                <td class='h2' style=' padding: 0px;'>
                                  <ul>
                                    <li><b>Residents in Rwanda</b>
                                      <ul>
                                        <li>18 June 2023</li>
                                        <ul>
                                          <li>10am – 9pm </li>
                                        </ul>
                                        <li>19 June 2023</li>
                                        <ul>
                                          <li>10am – 9pm </li>
                                        </ul>
                                      </ul>
                                    </li>
                                      
                                    <li><b>International Delegates </b>
                                      <ul>
                                        <li>18 June 2023</li>
                                        <ul>
                                          <li>10am – 9pm </li>
                                        </ul>
                                        <li>19 June 2023</li>
                                        <ul>
                                          <li>10am – 9pm </li>
                                        </ul>
                                        <li>20 June 2023</li>
                                        <ul>
                                          <li>7am – 9pm </li>
                                        </ul>
                                        <li>21 June 2023</li>
                                        <ul>
                                          <li>7am – 9pm </li>
                                        </ul>
                                      </ul>
                                    </li>
                                  </ul>

                                </td>
                              </tr>
                              <tr>
                                <td class='h2' style='padding:0;'><b>*NOTE: Please ensure you bring your badge and ID to the Forum to facilitate entry.</b></td>
                              </tr>

                            <tr>
                                <td class='h2' style='padding:0; color:#d90368;text-transform:uppercase'><b>AIRPORT TRANSPORT INFORMATION</b></td>
                            </tr>
                            <tr>
                              <td class='h2'>
                                <ul>
                                    <li><b>Delegates</b></li>
                                    <ul>
                                      <li>There will a welcome tent outside arrivals where you may book your airport transfer. Cash and card payments are accepted.</li>
                                      <li>You may also book an airport pick-up with your hotel.</li>
                                    </ul>
                                </ul>
                              </td>
                            </tr> 

                            <tr>
                                <td class='h2' style='padding:0; color:#d90368;text-transform:uppercase'><b>USEFUL LINKS </b> </td>
                            </tr>
                            <tr>
                                <td class='h2' style='padding: 5px 0;'>
                                  <a href='https://www.migration.gov.rw/our-services/visa-issued-under-special-arrangement' target='_blank'>Visa information </a><br>
                                  <a href='https://www.inclusivefintechforum.com/programme' target='_blank'>Latest programme </a><br>
                                  <a href='https://www.inclusivefintechforum.com/hospitality' target='_blank'>Planning your visit</a><br>
                                  <a href='https://rbc.gov.rw/index.php?id=745' target='_blank'>Health information</a><br>
                                </td>
                            </tr>
                        
                        </table>
                    </td>
                </tr>

        " . self::emailLayoutSectionFooter();

    // Email::send($_Email_, $_Subject_, $_Message_);
      $User = new User();
    $User->send_mail($_Email_, $_Message_, $_Subject_);
  }


  public static function sendEmailToParticipantForEventOpen($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $_Email_ = $email;
    $_Subject_ = 'Inclusive FinTech Forum Opens - Preview of the First 2 Days';
    $_Message_ = self::emailSectionHeaderLayout() . "
                  <tr>
                    <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
                        <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                          <tr>
                                <td class='h2' style='padding:0; color:#d90368; font-size: 25px; text-align: center;'><b>Welcome to Kigali: Here’s what you can expect<br><br> </b></td>
                            </tr>
                            <tr>
                                <td class='bodycopy'>We’ll open the doors to the inaugural Inclusive FinTech Forum shortly, and we can’t wait to welcome you!<br><br> 
                                Here are just some of the highlights you can look forward to:
                                </td>
                            </tr>
                            <tr>
                              <td class='h2'>
                                <ol>
                                    <li><b style='padding:0; color:#d90368; font-size:18px;'>Tuesday: FinTech Without Borders:</b><br>Catch sessions discussing how to take finance and technology across borders from 1:50pm to 5:30pm on Tuesday, 20 June. Sessions such as “When Money Gets REALLY Smart: Tales from Capital Providers with Purpose” (Tues, 4:30pm) will be taking place at MH1.</li><br>

                                    <li><b style='padding:0; color:#d90368; font-size:18px;'>Wednesday: Deep Dive Workshops</b><br>
                                    Learn from real-world applications of tech in finance. Here are the workshops taking place on Wednesday, 21 June:</li><br>
                                    9:00am - 10:00am <b>The Future of FinTechs through Partnerships: OpenAPI, Payments, and Remittance</b><br>
                                    <i>Hosted by Momo MTN</i><br><br>

                                    9:30am - 10:30am <b>India Story on Digital Payments. Creating New Waves Consistently</b><br>
                                    <i>Hosted by NPCI International Payments (NIPL)</i><br><br>

                                    11:00am - 12:00pm <b>Revolutionising Financial Access in South Africa: The SASSA - Tyme Success Story</b><br>
                                    <i>Hosted by Tyme</i><br><br>

                                    12:00pm - 1:00pm <b>Building a Digital Workforce in Africa</b><br>
                                    <i>Hosted by CFTE</i><br><br>

                                    12:15pm - 1:00pm <b>Inclusive Insurance through Insurtech Ecosystems</b><br>
                                    <i>Hosted by ZEP-RE</i><br><br>

                                    1:15pm - 2:00pm <b>Conversational AI in Digital first Fintech companies</b><br>
                                    <i>Hosted by Gupshup</i><br><br>

                                    <li><b style='padding:0; color:#d90368; font-size:18px;'>Network: Build Partnerships, Make Deals, Connect with Talent</b><br>
                                    Policymakers, entrepreneurs, investors, corporates, and foundations will gather for this global dialogue!<br><br>
                                    2,500+ attendees across 70 countries</li><br>

                                    - Careers Forum Networking Mixer
                                    <ul>
                                      <li>Tuesday, 20 June, 4:35pm - 6pm</li>
                                      <li>Kigali Convention Centre, Outside AD10</li>
                                      Meet with Africa's youngest and brightest talents at the Careers Forum networking mixer. Inspire the next generation to pursue careers in Finance and FinTech.
                                    </ul>
                                    - Industry Networking Party
                                    <ul>
                                      <li>Wednesday, 21 June 5:30pm</li>
                                      <li>Featuring: Chipper Cash Rwanda Launch Drone LightShow</li>
                                    </ul>
                                    - Download the App: Arrange 1-to-1 Meetings
                                    <ul>
                                      <li><a href='https://next.brella.io/events' target='_blank'>Download</a></li>
                                    </ul><br>

                                    <li><b style='padding:0; color:#d90368; font-size:18px;'>Spotlight Sessions</b><br>
                                    <b>Capital Markets Infrastructure</b>
                                    <ul>
                                      <li>The Big Talk: Financing Emerging Markets Development Against Global Headwinds, June 21 | Forum Stage</li>
                                      <li>The Big Talk: State of Technology Readiness to Address Financial Inclusion Goals, June 22 | Forum Stage</li>
                                    </ul>
                                    <b>Digital Lending and SMEs</b>
                                    <ul>
                                      <li>Scaling digital access to lending, 21 June  | Forum Stage</li>
                                      <li>Digital lending, funding, pricing and data, 21 June | Knowledge Dialogue Stage</li>
                                      <li>Alternative Trusted Credentials for new digital lending and financial empowerment for MSMEs & FIs, 21 June | Deep-dive Roundtable hosted by UNDP and MAS</li>
                                    </ul>
                                    <b>Digital Public Goods</b>
                                    <ul>
                                      <li>Spotlight on payment system interoperability, 21 June | Forum Stage</li>
                                      <li>Development of digital public goods & infrastructure, 22 June | Forum Stage</li>
                                      <li>Digital Public Goods in Africa - Open Source vs Proprietary, 22 June | Elevandi Insights Forum</li>
                                    </ul><br>

                                    <li><b style='padding:0; color:#d90368; font-size:18px;'>Prepare For your Visit</b><br>
                                    Pick up your badge at the entrance of the Kigali Convention Centre when you arrive. <br><br>
                                    Remember to bring your ID each day you visit the Inclusive FinTech Forum for admission into the venue.<br><br>
                                    <a href='https://next.brella.io/events' target='_blank'>Download</a> the APP to bookmark sessions that you don't want to miss and start networking now!</li>
                                    </li>


                                </ol>
                              </td>
                            </tr> 
                        </table>
                    </td>
                </tr>

        " . self::emailLayoutSectionFooter();

    // Email::send($_Email_, $_Subject_, $_Message_);
      $User = new User();
    $User->send_mail($_Email_, $_Message_, $_Subject_);
  }


  public static function sendEmailToParticipantForThankYou($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $_Email_ = $email;
    $_Subject_ = 'Thank you for being a part of the Inclusive FinTech Forum - see you next year!';
    $_Message_ = self::emailSectionHeaderLayout() . "
                  <tr>
                    <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
                        <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                            <tr>
                                <td class='bodycopy'>Dear $firstname,<br><br>
                               Thank you for taking part in the inaugural Inclusive FinTech Forum 2023, we hope you found the Forum to be a valuable platform for networking, forming partnerships, and engaging in discussions to advance Financial Inclusion.<br><br>
                               <img class='fix' src='https://iff.torusguru.com/img/iff2023_typhotocollage_1.jpeg' width='100%' border='0' alt='' /><br><br>
                                Your active participation helped make the first run of the Forum a success. As we aim to enhance future editions, your insights are highly valuable to us. We invite you to share your feedback using the link below:<br><br>
                                <a href='https://survey.hsforms.com/1_sYryemcTySoOKlAb87LXAd9orj' target='_bank'>Share your feedback</a><br><br>
                                As we begin preparations for the Inclusive FinTech Forum 2024, we encourage you to register your interest early. Please follow the link below to stay informed about next year's event:<br><br>
                                <a href='https://www.inclusivefintechforum.com/register-your-interest' target='_bank'>Register Your Interest</a><br><br>
                                Thank you once again for your contribution to the Inclusive FinTech Forum, and for your continued support. We look forward to seeing you next year.
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
        " . self::emailLayoutSectionFooter();

    // Email::send($_Email_, $_Subject_, $_Message_);
      $User = new User();
    $User->send_mail($_Email_, $_Message_, $_Subject_);
  }


  public static function sendEmailToParticipantForPostForumSurvey($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $_Email_ = $email;
    $_Subject_ = 'Your Feedback Matters! Complete the Post-Forum Survey';
    $_Message_ = self::emailSectionHeaderLayout() . "
                  <tr>
                    <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
                        <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                            <tr>
                                <td class='bodycopy'>Dear $firstname,<br><br>
                                Thank you for attending the recent Inclusive FinTech Forum! We appreciate your participation and support in making the event a success.<br><br>
                                Your feedback is important to us as we strive to improve and enhance future editions. We kindly request a few minutes of your time to complete our post-forum survey. Your insights will help us better understand what worked well and identify areas for improvement.<br><br>
                                <b>Please click on the following link to access the survey:</b><br>
                                
                                <a href='https://survey.hsforms.com/1_sYryemcTySoOKlAb87LXAd9orj' target='_bank'>https://survey.hsforms.com/1_sYryemcTySoOKlAb87LXAd9orj</a><br><br>
                                Your responses will be anonymous and treated with the utmost confidentiality. We value your honest feedback and any suggestions you may have.<br><br>
                                Thank you for joining us the inaugural edition of the Forum, we greatly appreciate your support.
                                </td>
                            </tr>
                            
                        </table>
                    </td>
                </tr>
        " . self::emailLayoutSectionFooter();

    // Email::send($_Email_, $_Subject_, $_Message_);
      $User = new User();
    $User->send_mail($_Email_, $_Message_, $_Subject_);
  }


  public static function sendEmailToParticipantForWorkshops($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $_Email_ = $email;
    $_Subject_ = 'Don’t Miss These Day 3 Highlights';
    $_Message_ = self::emailSectionHeaderLayout() . "
                  <tr>
                    <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
                        <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                            <tr>
                                <td class='bodycopy'>Join us at the third and final day of the Inclusive FinTech Forum. Make the most of this global gathering of policymakers, investors, and industry leaders.<br><br> 
                                Here are the top 5 highlights for Thursday:
                                </td>
                            </tr>
                            <tr>
                              <td class='h2'>
                                <ol>
                                    <li><b style='padding:0; color:#d90368; font-size:18px;'>Must-Attend Workshops</b><br>The role of interoperable payment infrastructure in enabling digital payment innovation by Fintechs: The case of Rwanda National Digital Payment System (RNDPS)</li><br>

                                    9:00am - 9:45am | AD11 | Hosted by Access to Finance Rwanda<br><br>

                                    Tokenisation of Real World Assets - A Comprehensive Solution Leveraging ERC-3525 <br>
                                    10:15am - 11:15am | AD11 | Hosted by Solv Foundation<br><br>

                                    Digital Infrastructure: The Importance of National Sovereignty in Payments<br>
                                    11:30am - 12:30pm | AD11 | Hosted by Mojaloop<br><br>

                                    Switching & Security in the FinTech era<br>
                                    11:45am - 12:30pm | AD7 | Hosted by QT Group<br><br>

                                    Switching & Security in the FinTech era<br>
                                    11:45am - 12:30pm | AD7 | Hosted by QT Group<br><br>

                                    Achieving Sovereignty in Digital Public Infrastructure: Mojaloop Case Studies<br>
                                    12:30pm - 1:30pm | AD11 | Hosted by Mojaloop<br><br>

                                    Moment and Multichoice - Building the Payments Platform for All of Africa<br>
                                    2:00pm - 2:30pm | AD11 | Hosted by Moment<br><br>

                                    Sustainability Disclosure Simplified – Scalable, Affordable and Accessible<br>
                                    3:00pm - 3:45pm | AD11 | Hosted by UNDP, GLEIF and MAS<br><br>

                                    Role of Fintech in Driving a Cashless Economy<br>
                                    4:00pm - 4:45pm | AD11 | Hosted by Flutterwave<br><br>

                                    <li><b style='padding:0; color:#d90368; font-size:18px;'>Spotlight on: ESG Financing at the Forum Stage</b></li>
                                    Sustainable Finance & Digital Banking<br>
                                    11:30am - 12:30pm<br><br>

                                    Launch of Project Savannah: Digital ESG Credentials for MSMEs to Access Sustainable Financing and Global Supply Chains<br>
                                    1:30pm - 2:10pm<br><br>

                                    ESG Infrastructure & Product Development for the Emerging Markets<br>
                                    2:10pm - 2:55pm<br><br>

                                    <li><b style='padding:0; color:#d90368; font-size:18px;'>Spotlight On: Insurance & Pensions at FinTech Without Borders (MH1)</b><br>
                                    The Fortune at the Bottom of the Pyramid: A Demand-Led Retail Finance Strategy<br>
                                    1:55pm - 2:55pm<br><br>

                                    Tailoring Insurance for Emerging Markets: War Stories of Success and Residual Challenges<br>
                                    2:55pm - 3:55pm<br><br>

                                    Templatising Digital Micro-Pension and Micro-Wealth Solutions for Emerging Markets<br>
                                    3:55pm - 4:55pm <br><br>


                                    <li><b style='padding:0; color:#d90368; font-size:18px;'>Roundtable Discussions at the Elevandi Insights Forum (AD10)</b><br>
                                    Chatham House Rules apply<br><br>

                                    Capital Providers Roundtable: FinTech Investment<br>
                                    1:30pm - 2:45pm <br><br>

                                    Digital Public Goods in Africa - Open Source Vs Proprietary<br>
                                    4pm - 5:15pm<br><br>

                                    <li><b style='padding:0; color:#d90368; font-size:18px;'>Roundtable Discussions at the Elevandi Insights Forum (AD10)</b><br>
                                    Exhibition: Concourse and MH2 & MH3, 10am onwards<br><br>
                                    Lunch Food & Beverage points will be located in the exhibition space! Discover new partnerships and solutions FinTech showcase.<br><br>

                                    Opportunity Stage: Concourse, outside MH4<br><br>
                                    From 1pm onwards, get inspired by FinTech leaders showcasing case studies, the latest tech, and stories from across the globe!<br><br><br>





                                    Mark your calendar, bookmark sessions in <a href='https://next.brella.io/events' target='_blank'>the App,</a> and join us for the final day packed with insights, expertise, and networking opportunities.<br><br>

                                    See you there!<br>
                                    The Inclusive FinTech Forum organisers 


                                  </li>


                                </ol>
                              </td>
                            </tr> 
                        </table>
                    </td>
                </tr>

        " . self::emailLayoutSectionFooter();

    // Email::send($_Email_, $_Subject_, $_Message_);
      $User = new User();
    $User->send_mail($_Email_, $_Message_, $_Subject_);
  }


  public static function sendEmailToParticipantCareersForum($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $_Email_ = $email;
    $_Subject_ = 'The Most Inspirational Sessions at IFF: Africa Chapter of the Iconic Founders Peak';
    $_Message_ = self::emailSectionHeaderLayout() . "
                  <tr>
                    <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
                        <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                            <tr>
                                <td class='bodycopy'>Dear $firstname,<br><br>

                                For one final day, our first cohort of carefully selected founders in Africa will be taking to the yellow circle onstage at the Founders Peak!<br><br>

                                The Founders Peak<br>
                                1:30pm - 4:40pm<br>
                                Kigali Convention Centre, MH4<br><br>

                                The Founders Peak is a platform for global entrepreneurial wisdom. An initiative designed to celebrate Founders, their journey, and experiences - a cathartic experience in which speakers have the opportunity to share a purposeful story with the world and one that we hope will inspire the next wave of entrepreneurs, leaders and society at large.<br><br>

                                Hand-picked inspiring founders will share their stories of grit and determination, perseverance and achieving or failing fast and learning.<br><br>

                                Gain life lessons and inspiration to last a lifetime.

                                </td>
                            </tr>
                            
                        </table>
                    </td>
                </tr>

        " . self::emailLayoutSectionFooter();

    // Email::send($_Email_, $_Subject_, $_Message_);
      $User = new User();
    $User->send_mail($_Email_, $_Message_, $_Subject_);
  }



  public static function sendEmailToParticipantAfterCategoryUpdatedCMPD($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $participant_code = $_data_->participant_code;
    $cmpd_invite_link = $_data_->cmpd_invite_link;
    $_Email_ = $email;
    $_Subject_ = 'Registration Update: Your Pass Has Been Upgraded ';
    $_Message_ = self::emailSectionHeaderLayoutCMPD() . "
    <tr>
                      <td class='innerpadding borderbottom' bgcolor='#010508' style='background:#010508;'>
                        <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                            <tr>
                                <td class='h2' style=' padding: 15px 0px 30px 0px; '>Dear $firstname,</td>
                            </tr>
                            <tr>
                                <td class='bodycopy'>
                                    We hope you are looking forward to attending the inaugural Inclusive FinTech Forum. We are glad to inform you that your pass has been upgraded to include access to The Capital Meets Policy Dialogue, which is happening in conjunction with the forum, details of the Dialogue are below. <br><br>


                                    <b>Date and Time:</b> Tuesday, 20 June, 9:00-13:30 (Registration commences at 9:00)<br>
                                    <b>Location:</b> Kigali Convention Centre (KCC), Kigali, Rwanda<br>
                                    <b>Attire:</b> Business Formal<br>
                                    <b>Kindly find attached calendar invite to add to your schedule</b>
                                    <br><br>
                                    The Capital Meets Policy Dialogue is a unique opportunity for policymakers and investors to come together and share their perspectives on FinTech regulations and sectoral investment 
outlook, as well as discuss emerging risks and opportunities. Above all, the dialogue is an ask for both parties to share expectations more openly.
<br><br>
We hope to see you at The Capital Meets Policy Dialogue - Where policymakers, capital providers and think tanks converge to shape the future of financial technology.<br><br>

You may find more information on The Capital Meets Policy Dialogue, Africa 2023 <a href='https://www.inclusivefintechforum.com/cmpd' target='_blank'>here</a></b>.<br>

                                </td>
                            </tr>

                            <tr>
                                <td class='bodycopy' style=''><br><a href='$cmpd_invite_link' style='background-color: #d90368; border: 1px solid #d90368; padding: 5px 6px 5px 6px; color: #ffffff!important; margin-top: 10px; text-decoration: none;'>Accept the upgrade</a><br><br> </td>
                            </tr>

                            
                            </table>
                          </td>
                        </tr>
                        <tr>

        " . self::emailLayoutSectionFooterCMPD();

    // Email::send($_Email_, $_Subject_, $_Message_);
      $User = new User();

      // Adding the calendar invite to the email
      $calendarAttachment="/opt/lampp/htdocs/thefuture/iff/img/calendar-invite.ics";

    $User->send_mail($_Email_, $_Message_, $_Subject_, $calendarAttachment);
  }



  public static function sendEmailToParticipantAfterApplicationDecline($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $invitation_letter_link = $_data_->invitation_letter_link;
    $approval_status = $_data_->approval_status;

    $_Email_ = $email;
    $_Subject_ = 'Application to Register as a Local CBO to the - Inclusive Fintech Forum 2023';
    $_Message_ = self::emailSectionHeaderLayout() . "
                  <tr>
                    <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                        <td class='h2' style=' padding: 15px 0px 30px 0px; '>Dear $firstname,</td>
                        </tr>
                        <tr>
                        <tr>
                          <tr><td class='h2'>Thank you for registering your interest to attend the <b>Inclusive Fintech Forum 2023</b> that will be held in Kigali from 18 – 23 July as a Local CBO. We have reviewed your application and unfortunately, are not in a position to grant your request as you do not qualify as a local CBO. Please note that to qualify for this category, one must be a member of a Rwanda-based community organization or group.
  
                        </tr>
                        <tr><td class='h2'>We encourage you to apply for participation under any of the other categories available as fits you.</td></tr>
                        
                        " . self::emailLayoutSectionFooterInfo() . "

                        </tr>
                      </table>
                    </td>
                  </tr>

      " . self::emailLayoutSectionFooter();

    // Email::send($_Email_, $_Subject_, $_Message_);
      $User = new User();
    $User->send_mail($_Email_, $_Message_, $_Subject_);
  }



  public static function sendEmailToParticipantAfterPaymentRefunded($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;

    $_Email_ = $email;
    $_Subject_ = 'Refund of Inclusive Fintech Forum 2023 Registration Fees';
    $_Message_ = self::emailSectionHeaderLayout() . "
                  <tr>
                    <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                        <td class='h2' style=' padding: 15px 0px 30px 0px; '>Dear $firstname,</td>
                        </tr>
                        <tr>
                          <tr>
                            <td class='h2'>We are glad to inform you that your request for refund of registration fees has been successfully processed in line with the <a href='https://apacongress.africa/download/cancellation-and-refund-policy/' target='_blank'>Inclusive Fintech Forum 2023 Cancellation And Refund Policy</a>. Depending on your bank, you should receive the same within the next three working days.<br><br>

                            We thank you for your interest in <a href='https://apacongress.africa/' target='_blank'>Inclusive Fintech Forum 2023</a> and hope to continue engaging with you as we work to position conservation at the heart of Africa’s sustainable development agenda.
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>

      " . self::emailLayoutSectionFooter();

    // Email::send($_Email_, $_Subject_, $_Message_);
      $User = new User();
    $User->send_mail($_Email_, $_Message_, $_Subject_);
  }



  /** - 11 - Send urgent email  */
  public static function sendUrgentEmail($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $userLink = $_data_->userLink;
    $organisation_name = $_data_->organisation_name;

    $_Email_ = $email;
    $_Subject_ = 'Information Sharing Consent Form - Inclusive Fintech Forum 2023';
    $_Message_ = self::emailSectionHeaderLayout() . "
                  <tr>
                    <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr><td class='h2' style='padding: 15px 0; text-align: center; text-transform: uppercase;'><b>Information Sharing Consent Form </b> </td></tr>
                        <tr><td class='h2' style='padding:0;'>I $firstname of $organisation_name hereby give my permission for IUCN to share my personal information with other delegates and Inclusive Fintech Forum 2023 Sponsors for the purpose of networking and advancing the conversations at the congress and after. I understand that (the host organisations) may hold information gathered about me from the various agencies and as such my rights under the Data Protection Act will not be affected.
                        </td></tr>

                          <tr><td class='h2' style='padding: 5px 0;'><b>Statement of Consent:</b> </td></tr>
                          <tr>
                            <td class='h2'>
                                <ul>
                                  <li>I understand that personal information is held about me.</li>
                                  <li>I have had the opportunity to consider the implications of sharing or not sharing information about me.</li>
                                  <li><b>I agree that personal information about me gathered while registering for the Inclusive Fintech Forum 2023 may be shared:</b></li>
                                  <ul>
                                    <li>Inclusive Fintech Forum 2023 Sponsors and Donors</li>
                                    <li>Fellow delegates</li>
                                  </ul>
                                </ul>
                            </td>
                          </tr>
                          <tr><td class='h2' style='padding: 15px 0;'><a href='$userLink' target='_blank'><input type='checkbox'> I agree to my information being shared with fellow delegates, donors and Inclusive Fintech Forum 2023 Sponsors</a></td></tr>
                        
                      </table>
                    </td>
                  </tr>

                  <tr>
                    <td class='footer' bgcolor='#fff'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        
                        <tr>
                          <td>
                            <table border='0' cellspacing='0' cellpadding='0'>
                              <td class='h2' style='padding:0; padding: 15px 0; color:#37af47;text-transform:uppercase;'><b>Stay connected</b></td>
                            </table>
                            <span class='alignment'></span>
                            <table border='0' cellspacing='0' cellpadding='0'>
                              <tr><td class='h2' style=' padding:0;'><b>Facebook:</b> <a href='https://www.facebook.com/APACongress' target='_blank'>@APACongress</a></td></tr>
                              <tr><td class='h2' style=' padding:0;'><b>Twitter:</b> <a href='https://twitter.com/APA_Congress?s=20' target='_blank'>@APA_Congress</a></td></tr>
                              <tr><td class='h2' style='padding:0;'><b>Connect with our official tag: </b><a href='#'>#APAC2022 </a></td></tr>
                              
                              <tr><td class='h2' style='padding:15px 0;'>For more information, please refer to the Inclusive Fintech Forum 2023 delegate registration information, <a href='https://apacongress.torusguru.com/terms' target='_blank'>general terms and conditions</a>. We look forward to meeting you in Kigali, Rwanda.  </td></tr>
                              <tr><td class='h2' style='padding:0;'><b>Best Wishes,</b> </td></tr>
                              <tr><td class='h2'> Inclusive Fintech Forum 2023 Secretariat </td></tr>
                            </table>
                          </td>
                        </tr>
                        <tr>
                              <td>
                                <table border='0' cellspacing='0' cellpadding='0'>
                                  <tr><td class='h2' style='padding: 0px 0 0 0;'><b>Disclaimer</b> </td></tr>
                                  <tr><td class='h2' style=' text-align: justify; font-size: 13px;'> The information contained in or accompanying this email and any attachment thereto, is intended solely for the use of the stated recipient(s) and may contain information that is confidential and/or privileged. Any dissemination, distribution or copying of this email and any attachment by anyone who is not a stated recipient is strictly prohibited. If you receive this message in error, please notify the sender immediately and delete the message and any attachment from your system without retaining a copy. Thank you for your cooperation and understanding. </td></tr>
                                </table>
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

      ";

    // Email::send($_Email_, $_Subject_, $_Message_);
      $User = new User();
    $User->send_mail($_Email_, $_Message_, $_Subject_);
  }



  /** - 9 - Send Email - Participant - Email received after media application -  */
  public static function sendEmailToParticipantAfterMediaApplication($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;

    $_Email_ = $email;
    $_Subject_ = 'Confirmation - Inclusive Fintech Forum 2023 Registrations';
    $_Message_ = self::emailSectionHeaderLayout() . "
                  <tr>
                    <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                        <td class='h2' style=' padding: 15px 0px 30px 0px; '>Dear $firstname,</td>
                        </tr>
                        
                        <tr><td class='h2' style='padding:0;'>Thank you for applying for media accreditation to attend the Inclusive Fintech Forum 2023 that will be held in Kigali, Rwanda, from 20 - 22 June 2023. 
                        </td></tr>

                        <tr><td class='h2' style='padding:0;'>The APAC Event Secretariat will review your application and revert within the next 5 working days. 
                        </td></tr>

                        <tr><td class='h2' style='padding:0;'><b>International media</b> </td></tr>
                          <tr>
                            <td class='h2'>
                                <ul>
                                  <li>Please do not book travel or accommodation until you have received confirmation to attend as accredited media. </li>
                                </ul>
                            </td>
                          </tr>
                       
                      </table>
                    </td>
                  </tr>

    " . self::emailLayoutSectionFooter();

    // Email::send($_Email_, $_Subject_, $_Message_);
    $User = new User();
    $User->send_mail($_Email_, $_Message_, $_Subject_);
  }


  /** - 9 - Send Email - Participant - Email received after media application -  */
  public static function declineMediaApplication($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;

    $_Email_ = $email;
    $_Subject_ = 'Application to Register as a Media to the - Inclusive Fintech Forum 2023';
    $_Message_ = self::emailSectionHeaderLayout() . "
                  <tr>
                    <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                          <td class='h2' style=' padding: 15px 0px 30px 0px; '>Dear $firstname,</td>
                        </tr>
                        <tr><td class='h2' style='padding:0;'>Thank you for registering your interest to attend <b>the Inclusive Fintech Forum 2023</b> that will be held in Kigali from 18 – 23rd July as a Media. We have reviewed your application and unfortunately, your application does not qualify for media accreditation. We encourage you to apply for participation under any of the other categories available as fits you. Alternatively, please reach out to the registration team at <a href='mailto:Registration@apacongress.africa'>Registration@apacongress.africa</a> with your request to change your participation type. <br><br>
                        Please note that media accreditation is strictly reserved for members of the press (print, photo, radio, television, film, news agencies and online media) who represent a bona fide media organization.<br><br>
                        Accreditation is only be given on proof of a track record of reporting for media organizations on local or international affairs. Media accreditation is not accorded to the information outlets or advocacy publications of non-governmental organizations. The Inclusive Fintech Forum 2023 PR and Communications team may require supplementary documentation to evaluate the request. <br><br>
                        Applications are considered on a case-by-case basis and the decisions of the Inclusive Fintech Forum 2023 PR and Communications team are final. The Inclusive Fintech Forum 2023 PR and Communications team reserves the right to deny or withdraw accreditation of journalists from media organizations whose activities run counter to the objectives of Inclusive Fintech Forum 2023, or who abuse the privileges so extended or put the accreditation to improper use or act in a way not consistent with the principles of the Congress.
                        </td></tr>

                        " . self::emailLayoutSectionFooterInfo() . "
                        
                      </table>
                    </td>
                  </tr>

                  " . self::emailLayoutSectionFooter();

    // Email::send($_Email_, $_Subject_, $_Message_);
    $User = new User();
    $User->send_mail($_Email_, $_Message_, $_Subject_);
  }


  /** - 9 - Send Email - Participant - Email received after media application -  */
  public static function rejectParticipantApplication($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $category = $_data_->category;
    $event_url = $_data_->event_url;

    $_Email_ = $email;
    $_Subject_ = 'Update on your application to attend the Inclusive Fintech Forum';
    $_Message_ = self::emailSectionHeaderLayout() . "
                <tr>
                      <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
                          <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                              <tr>
                                  <td class='h2' style=' padding: 15px 0px 30px 0px; '>Dear $firstname,</td>
                              </tr>
                              <tr>
                                  <td class='bodycopy'>We regret to inform you that your application to attend the Inclusive FinTech Forum on a complimentary basis has not been approved.<br><br>
                                  </td>
                              </tr>
                              <tr>
                                  <td class='bodycopy'>You may however register for an Industry Pass to attend the Forum via this <a href='$event_url'>link</a>.
                                      <br><br> </td>
                              </tr>
                          </table>
                      </td>
                  </tr>

                 <tr>
                        <td class='footer' bgcolor='#fff'>
                            <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                                <tr>
                                    <td class='bodycopy' style='padding: 3px 30px 10px 30px; color: #ffffff;'>We hope to see you there!<br><br><b>Regards,</b><br>Inclusive FinTech Forum organisers<br><a
                                            href='mailto:hello@inclusivefintechforum.com'>hello@inclusivefintechforum.com</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td> <span class='alignment'></span>
                                        <table border='0' cellspacing='0' cellpadding='0'>
                                            <tr>
                                                <td><img class='fix'
                                                        src='https://iff.torusguru.com/img/footerlogo.png'
                                                        width='100%' border='0' alt='' /></td>
                                            </tr>
                                            <tr class='social'>
                                                <td><a href='https://twitter.com/inclusIFF' target='_blank'><img
                                                            class='fix'
                                                            src='https://iff.torusguru.com/img/socialmediafooter.png'
                                                            width='100%' border='0' alt='' /></a></td>
                                            </tr>
                                            <tr align='center'>
                                                <td class='privacy'>View our privacy policy <a
                                                        href='https://admin.torusguru.com/privacy'
                                                        target='_blank'>here</a>. | <a target='_blank'
                                                        href='mailto:hello@inclusivefintechforum.com'>Contact
                                                        Us</a><br>Copyright &#169; 2023 Elevandi, all rights reserved
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>";

    // Email::send($_Email_, $_Subject_, $_Message_);
    $User = new User();
    $User->send_mail($_Email_, $_Message_, $_Subject_);
  }

  /** - 9.1 - Send Email - Participant - Email received after media application -  */
  public static function rejectParticipantApplicationCMPD($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $category = $_data_->category;
    $event_url = $_data_->event_url;

    $_Email_ = $email;
    $_Subject_ = 'Registration Update: The Capital Meets Policy Dialogue, Africa 2023 ';
    $_Message_ = self::emailSectionHeaderLayoutCMPD() . "
                <tr>
                    <td class='innerpadding borderbottom' bgcolor='#010508' style='background:#010508;'>
                        <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                            <tr>
                                <td class='h2' style=' padding: 15px 0px 30px 0px; '>Dear $firstname,</td>
                            </tr>
                            <tr>
                                <td class='bodycopy'> Thank you for registering for The Capital Meets Policy Dialogue, Africa 2023, happening on 20 June at Kigali Convention Centre, Rwanda.  <br><br>
                                Due to limited seating, we are unfortunately not able to grant you a pass for The Capital Meets Policy Dialogue, Africa 2023. <br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class='bodycopy'> Should you wish to attend the Inclusive FinTech Forum happening from 21 - 22 June 2023 at the Kigali Convention Centre, please submit your registration <a href='$event_url'>here</a>.
                                    <br><br> </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                        <td class='footer' bgcolor='#fff'>
                            <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                                <tr>
                                    <td class='bodycopy' style='padding: 3px 30px 10px 30px; color: #ffffff;'>We look forward to welcoming you to the event. <br><br><b>Kind Regards,</b><br> The Capital Meets Policy Dialogue team
                                    </td>
                                </tr>
                                <tr>
                                    <td> <span class='alignment'></span>
                                        <table border='0' cellspacing='0' cellpadding='0'>
                                            <tr><td><img src='https://iff.torusguru.com/img/CMPD_Footer_FA.jpeg' width='100%' border='0' alt=''/></td></tr>
                                            <tr class='es-mobile-hidden' style='border-collapse:collapse;'>
                                              <td style='padding:0;Margin:0'><!--CTA socials-->
                                               <table align='center' border='0' cellpadding='0' width='100%' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                                                 <tr style='border-collapse:collapse; background: linear-gradient(45deg, #091934 0%, #020810 100%)!important;'>
                                                  <td style='padding:0;Margin:0;width:100%' align='center'><map id='ImgMap0' name='ImgMap0'><area shape='rect' coords='400, 27, 420, 75' href='https://www.facebook.com/people/Inclusive-FinTech-Forum/100088992566132/' target='_blank' alt><area shape='rect' coords='450, 27, 500, 75' href='https://www.linkedin.com/company/inclusive-fintech-forum/' target='_blank' alt><area shape='rect' coords='420, 27, 450, 75' href='https://twitter.com/inclusIFF' target='_blank' alt></map><img usemap='#ImgMap0' src='https://iff.torusguru.com/img/CMPD_Footer_FA1.jpeg' alt width='520' style='display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic'></td>
                                                 </tr>
                                               </table></td>
                                             </tr>
                                            <tr align='center'>
                                                <td class='privacy'>View our privacy policy <a
                                                        href='https://admin.torusguru.com/privacy'
                                                        target='_blank'>here</a>. | <a target='_blank'
                                                        href='mailto:hello@inclusivefintechforum.com'>Contact
                                                        Us</a><br>Copyright &#169; 2023 Elevandi, all rights reserved
                                                </td>
                                            </tr>
                                        </table>
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
    $User->send_mail($_Email_, $_Message_, $_Subject_);
  }


  /** - 10 - Send Email - Participant - Email received after media application -  */
  public static function sendEmailToParticipantWhenBankTransferNotAppearedInIUCNAccount7DaysAfterRegistration($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;

    $_Email_ = $email;
    $_Subject_ = 'Payment of Inclusive Fintech Forum 2023 Registration Fees';
    $_Message_ = self::emailSectionHeaderLayout() . "
                  <tr>
                    <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                          <td class='h2' style=' padding: 15px 0px 30px 0px; '>Dear $firstname,</td>
                        </tr>
                        <tr>
                          <td class='bodycopy'>
                         Thank you for your interest in  attending the inaugural <a href='https://apacongress.africa/' target='_blank'>IUCN Africa Protected Areas Congress</a> scheduled take place in Kigali, Rwanda from <b>July 18th to 23rd 2022</b> at <a href='https://www.kcc.rw/' target='_blank'>Kigali Convention Center</a>. This is a polite reminder that bank payments will be open until 10th July 2022 after which payments must be done by card or mobile money. If you have made payment and your registration is yet to be approved, please contact us at <a href='mailto:Registration@apacongress.africa'>Registration@apacongress.africa</a> and we shall assist you accordingly.<br><br>

                         We look forward to engaging with you as we work towards shaping Africa’s agenda for protected and conserved areas to deliver benefits for people and nature.
                          </td>
                        </tr>

                        " . self::emailLayoutSectionFooterInfo() . "
                        
                      </table>
                    </td>
                  </tr>

                  " . self::emailLayoutSectionFooter();

    // Email::send($_Email_, $_Subject_, $_Message_);
      $User = new User();
    $User->send_mail($_Email_, $_Message_, $_Subject_);
  }


  /** - 11 -  Email to be sent to Group Admin When the APAC Administration accept his Group Registration Request and an email with the registration link sent to that group admin.  -  */
  public static function sendEmailToGroupAdminOnGroupRegistrationRequestAccepted($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $generated_link = $_data_->generated_link;
    $password = $_data_->password;
    $system_link = $_data_->system_link;

    $_Email_ = $email;
    $_Subject_ = 'Confirmation - Inclusive Fintech Forum 2023 Group Registration Accepted';
    $_Message_ = self::emailSectionHeaderLayout() . "
                  <tr>
                    <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                        <td class='h2' style=' padding: 15px 0px 30px 0px; '>Dear $firstname,</td>
                        </tr>
                        
                        <tr><td class='h2' style='padding:0;'>Thank you for applying for media accreditation to attend the Inclusive Fintech Forum 2023 that will be held in Kigali, Rwanda, from 20 - 22 June 2023. 
                        </td></tr>

                        <tr><td class='h2' style='padding: 15px 0;'>We are glad to inform you that your request for group registration has been reviewed and accepted.
                        </td></tr>

                        <tr><td class='h2' style='padding: 0;'> Please complete your registration and the payment for your group <br>
                        <a href='$generated_link'> Click here</a>  to proceed.
                        </td></tr>

                        <tr><td class='h2' style='padding: 5px 0; padding-top: 15px;'><b>Your login credentials are as follows:</b> </td></tr> <br>
                          <tr>
                            <td class='h2'>
                                <ul class='list'>
                                  <li>User Name: <strong>$email</strong> </li>
                                  <li>Password: <strong>$password</strong> </li>
                                  <li>System Link: <strong><a href='$system_link'> Group Administration System Portal  </a></strong> </li>
                                </ul>
                            </td>
                          </tr>

                        <tr><td class='h2' style='padding: 0; padding-bottom: 15px;'>Should you have any challenges and questions regarding your group registration please do not hesitate to contact the registration support team on <a href='mailto:registration@apacongress.africa'>registration@apacongress.africa</a>.
                        </td></tr>
                      
                      </table>
                    </td>
                  </tr>

    " . self::emailLayoutSectionFooter();

    // Email::send($_Email_, $_Subject_, $_Message_);
    $User = new User();
    $User->send_mail($_Email_, $_Message_, $_Subject_);

  }

  /** - 11 -  Email to be sent to Group Admin when complimentary group is created  -  */
  public static function sendEmailToGroupAdminOnSubmitGroupRegistrationFree($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $password = $_data_->password;
    $system_link = $_data_->system_link;

    $_Email_ = $email;
    $_Subject_ = 'Invitation to register your group to the Inclusive FinTech Forum';
    $_Message_ = self::emailSectionHeaderLayout() . "
              <tr>
                <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
                    <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                            <td class='h2' style=' padding: 15px 0px 30px 0px; '>Dear $firstname,</td>
                        </tr>
                        <tr>
                            <td class='bodycopy'> We are pleased to invite you to register your group to attend the Inclusive FinTech Forum that will be held from 20 - 22 June 2023 in Kigali, Rwanda. <br><br> </td>
                        </tr>
                        <tr>
                            <td class='bodycopy'> You are now able to extend complimentary passes to all members of your group to register for the Forum. </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
                    <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                            <td class='bodycopy' style=' padding-bottom: 0px; color: #d90368 !important'><b> INSTRUCTIONS ON HOW TO
                                    REGISTER YOUR GROUP </b></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td bgcolor='#101010' style='background:#101010;'>
                    <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                            <td class='bodycopy' style='  padding-bottom: 0px; padding-left: 15px; padding-right: 15px;'>
                                <ol>
                                    <li style='color: #fff;'>Click through to the link below and input your username and
                                        password. </li>
                                    <li style='color: #fff;'>Click on ‘Add Group Member’ and input the details of your group
                                        member. You should also add yourself if you are attending the Forum. </li>
                                    <li style='color: #fff;'>Each group member you add will receive an email to complete their
                                        registration.</li>
                                    <li style='color: #fff;'>You will be able to view their registration status by logging in
                                        to the registration platform using the same username and password.</li>
                                </ol>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
                    <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                            <td class='bodycopy' style=' padding-left: 15px; padding-right: 15px;'> Username: $email </td>
                        </tr>
                        <tr>
                            <td class='bodycopy' style=' padding-left: 15px; padding-right: 15px;'> Password: $password </td>
                        </tr>
                        <tr>
                            <td class='bodycopy' style=' padding-left: 15px; padding-right: 15px;'> <a href='$system_link'>Click here
                                    to login</a> <br><br></td>
                        </tr>
                    </table>
                </td>
            </tr>

    " . self::emailLayoutSectionFooter();

    // Email::send($_Email_, $_Subject_, $_Message_);
    $User = new User();
    $User->send_mail($_Email_, $_Message_, $_Subject_);
  }

  /** - 12 -  Email to be sent to Group Admin when payable group is created  -  */
  public static function sendEmailToGroupAdminOnSubmitGroupRegistrationPayable($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $pass_details = $_data_->pass_details;
    $payment_link = $_data_->payment_link;

    $_Email_ = $email;
    $_Subject_ = 'Payment details for your group to attend the Inclusive FinTech Forum';
    $_Message_ = self::emailSectionHeaderLayout() . "
              <tr>
                <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
                    <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                            <td class='h2' style=' padding: 15px 0px 30px 0px; '>Dear $firstname,</td>
                        </tr>
                        <tr>
                            <td class='bodycopy'>Thank you for your interest to attend the Inclusive FinTech Forum that
                                will be held from 20 - 22 June 2023 in Kigali, Rwanda. <br><br></td>
                        </tr>
                        <tr>
                            <td class='bodycopy'>You have requested for: </td>
                        </tr>
                        <tr>
                            <td class='bodycopy'>$pass_details</td>
                        </tr>
                        <tr>
                            <td class='bodycopy'><br>Please proceed to this <a href='$payment_link'>link</a> to pay for the passes.
                            </td>
                        </tr>
                        <tr>
                            <td class='bodycopy'><br>You will receive an email confirming receipt of your payment and
                                instructions on how to register your group. </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td bgcolor='#101010' style='background:#101010;'> </td>
            </tr>
            <tr>
                <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'> </td>
            </tr>

    " . self::emailLayoutSectionFooter();

    // Email::send($_Email_, $_Subject_, $_Message_);
    $User = new User();
    $User->send_mail($_Email_, $_Message_, $_Subject_);
  }


 /** - 13 - Email to be sent to Group Admin When s/he registers and completes the payment for his group - with the confirmation message and the Apac invitation letter - with credentials to access his group admin account in order to send private link to his group delegates  -  */
 public static function sendEmailToGroupAdminOnGroupRegistrationPaymentCompleted($_data_)
 {
   $_data_ = (object)$_data_;
   $email = $_data_->email;
   $firstname = $_data_->firstname;
   $payment_receipt_link = $_data_->payment_receipt_link;
   $invitation_letter_link = $_data_->invitation_letter_link;

   $_Email_ = $email;
   $_Subject_ = 'Confirmation - Inclusive Fintech Forum 2023 2022 Registration ';
   $_Message_ = self::emailSectionHeaderLayout() . "
                 <tr>
                    <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                        <td class='h2' style=' padding: 15px 0px 30px 0px; '>Dear $firstname,</td>
                        </tr>
                        
                        <tr><td class='h2' style='padding:0;'>Thank you for registering your group to attend the Inclusive Fintech Forum 2023 that will be held in Kigali from 20 - 22 June 2023. 
                        </td></tr>

                        <tr><td class='h2' style='padding: 15px 0;'>Additionally, we are pleased to inform you that your registration and your group registration has been completed and approved.
                        </td></tr>

                        <tr>
                          <td class='h2' style='padding:0;'><b>Your receipt is attached to this email</b> </td>
                        </tr>
                          <tr>
                            <td class='h2'>
                              <ul>
                                  <li><a href='$payment_receipt_link' target='_blank'>Click here</a> to download your receipt.  </li>
                              </ul>
                            </td>
                        </tr>

                        <tr>
                          <td class='h2' style='padding:0;'><b>Invitation To The Africa Protected Areas Congress</b> </td>
                        </tr>
                          <tr>
                            <td class='h2'>
                              <ul>
                                <li><a href='$invitation_letter_link' target='_blank'>Click here</a> to download your invitation letter   </li>
                              </ul>
                            </td>
                        </tr>

                        <tr><td class='h2' style='padding: 0;'> Sign into your Group Administration System with your valid credentials to send private registration links to your group delegates. <br>
                        

                        " . self::emailLayoutSectionFooterInfo() . "


                       
                      </table>
                    </td>
                  </tr> 

   " . self::emailLayoutSectionFooter();

   // Email::send($_Email_, $_Subject_, $_Message_);
   $User = new User();
    $User->send_mail($_Email_, $_Message_, $_Subject_);

 }


  /** Send Email - Participant - When register Participant Registration Link  */
  public static function sendEmailToParticipantOnLinkGenerated($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;

    $generated_link = $_data_->generated_link;

    $_Email_ = $email;
    $_Subject_ = 'Invitation to register & attend the Inclusive FinTech Forum';
    $_Message_ = self::emailSectionHeaderLayout() . "
                  <tr>
                    <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                        <td class='h2' style=' padding: 15px 0px 30px 0px; '>Dear $firstname,</td>
                        </tr>
                        
                        <tr><td class='h2' style='padding:0;'>We are pleased to invite you to register to attend the Inclusive FinTech Forum that will be held from 20 – 22 June 2023 in Kigali, Rwanda.
                        </td></tr>

                        <tr><td class='h2' style='padding: 15px 0;'>Please proceed to complete your registration through this <a href='$generated_link'> link</a>. 
                        </td></tr>
                        
                        <tr><td class='h2' style='padding:0; color:#d90368;text-transform:uppercase'><b><br>BADGE COLLECTION </b> </td></tr>
                        <tr><td class='h2'>You will receive information on how and when to collect your badge from the Accreditation site before the event. Please bring the identification document you used in your registration to collect your badge.</td></tr>

                        <tr><td class='h2' style='padding:0; color:#d90368;text-transform:uppercase'><b>PROGRAMME</b> </td></tr>
                        <tr><td class='h2'>You can view the forum programme <a href='https://www.inclusivefintechforum.com/programme/agenda' target='_blank'>here.</a></td></tr>

                      </table>
                    </td>
                  </tr>

      " . self::emailLayoutSectionFooter();
    // Email::send($_Email_, $_Subject_, $_Message_);
      $User = new User();
    $User->send_mail($_Email_, $_Message_, $_Subject_);
  }


  /** Send Email - Participant - When register Participant Registration Link  */
  public static function sendEmailToGroupMemberOnLinkGenerated($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $group_name = $_data_->group_name;
    $group_admin_name = $_data_->group_admin_name;
    $generated_link = $_data_->generated_link;

    $_Email_ = $email;
    $_Subject_ = 'Invitation to register & attend the Inclusive FinTech Forum';
    $_Message_ = self::emailSectionHeaderLayout() . "
                  <tr>
                    <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
                        <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                            <tr>
                                <td class='h2' style=' padding: 15px 0px 30px 0px; '>Dear $firstname,</td>
                            </tr>
                            <tr>
                                <td class='bodycopy'>You have been invited by <b>$group_admin_name</b> from <b>$group_name</b> to register for your
                                    pass to attend the Inclusive FinTech Forum that will be held from 20 – 22 June 2023 in Kigali,
                                    Rwanda. <br><br> </td>
                            </tr>
                            <tr>
                                <td class='bodycopy'>Please proceed to complete your registration through this <a
                                        href='$generated_link'>link</a>. </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td bgcolor='#101010' style='background:#101010; padding: 20px 30px 10px 30px;'>
                        <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                            <tr>
                                <td class='bodycopy' style='color: #d90368; padding-bottom: 0px; '><b> BADGE COLLECTION AFTER RECEIVING
                                        REGISTRATION NUMBER <b></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td bgcolor='#101010' style='background:#101010;'>
                        <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                            <tr>
                                <td class='bodycopy' style='  padding-bottom: 0px;'>
                                <td class='bodycopy' style='padding-left: 30px; padding-right: 25px;'>You will receive information on
                                    how and when to collect your badge from the Accreditation site before the event. Please bring the
                                    identification document you used in your registration to collect your badge. <br><br> </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td bgcolor='#101010' style='background:#101010; padding-left: 30px;'>
                        <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                            <tr>
                                <td class='h2' style='padding:0; color:#d90368;text-transform:uppercase'><b>PROGRAMME</b> </td>
                            </tr>
                            <tr>
                                <td class='h2'>You can view the Forum programme <a
                                        href='https://www.inclusiveFinTechforum.com/programme/agenda' target='_blank'>here</a>.<br><br>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

      " . self::emailLayoutSectionFooter();
    // Email::send($_Email_, $_Subject_, $_Message_);
      $User = new User();
    $User->send_mail($_Email_, $_Message_, $_Subject_);
  }


  /** Send Email - Participant - When register Participant  */
  public static function sendEmailToParticipantOnLinkStatusChanged($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $fullname = $_data_->fullname;

    $event = $_data_->event;
    $event_type = $_data_->event_type;
    $participation_type = $_data_->participation_type;
    $participation_subtype = $_data_->participation_subtype;
    $price = $_data_->price;
    $currency = $_data_->currency;

    $status = $_data_->status;

    $_Email_ = $email;
    $_Subject_ = 'Event Invitation Link ' . $status;
    $_Message_ = self::emailSectionHeaderLayout() . "
              <tr>
                <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
                  <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                    <td class='h2' style=' padding: 15px 0px 30px 0px; '>Dear <b>$fullname</b>,</td>
                    </tr>
                    <tr>
                      <td class='bodycopy'>
                      Hope this email finds you well, this is to inform you that your invitation link to register for our  $event_type event: $event, has  been $status.<br><br>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              
    " . self::emailLayoutSectionFooter();

    // Email::send($_Email_, $_Subject_, $_Message_);
    $User = new User();
    $User->send_mail($_Email_, $_Message_, $_Subject_);
  }


  /** Send Email - Payment success -  */
  public static function sendEmailToParticipantOnPaymentSuccess($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $fullname = $_data_->fullname;

    $event = $_data_->event;
    $event_type = $_data_->event_type;
    $participation_type = $_data_->participation_type;
    $participation_subtype = $_data_->participation_subtype;
    $price = $_data_->price;
    $currency = $_data_->currency;

    $_Email_ = $email;
    $_Subject_ = 'Event Registration';
    $_Message_ = self::emailSectionHeaderLayout() . "
                <tr>
                  <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
                    <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                      <tr>
                      <td class='h2' style=' padding: 15px 0px 30px 0px; '>Dear <b>$fullname</b>,</td>
                      </tr>
                      <tr>
                        <td class='bodycopy'>
                          Thank you for registering to our $event_type event: $event. We will process your application and get back to you very soon.<br><br>
                        </td>
                      </tr>
                      <tr>
                        <td class='bodycopy'>
                          Also, kindly take a minute to browse our website for latest updates and follow us on our social media accounts.
                        </td>
                      </tr>
                      <tr>
                        <td style='padding: 20px 0 0 0;' align='center'>
                          <table class='buttonwrapper' bgcolor='#f47e20' border='0' cellspacing='0' cellpadding='0'>
                            <tr>
                              <td class='button' height='45'>
                                <a href='http://torusguru.com/thefuture' target='_blank'>Visit our website</a>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                
      " . self::emailLayoutSectionFooter();

    // Email::send($_Email_, $_Subject_, $_Message_);
      $User = new User();
    $User->send_mail($_Email_, $_Message_, $_Subject_);
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
  .subhead {font-size: 15px; color: #ffffff; font-family: Ubuntu; letter-spacing: 10px;}
  .h1 {color: #ffffff; font-family: Ubuntu;}
  .h2, .bodycopy {color: #7A838B; font-family: Ubuntu;}
  .h1 {font-size: 30px; line-height: 38px; font-weight: bold;}
  .h2 {padding: 0 0 15px 0; font-size: 15px; line-height: 28px;}
  .bodycopy {font-size: 14px; line-height: 22px;}
  .button {text-align: center; font-size: 18px; font-family: Ubuntu; font-weight: bold; padding: 0 30px 0 30px;}
  .button a {color: #ffffff; text-decoration: none;}
  .footer {padding: 20px 30px 15px 30px;}
  .footercopy {font-family: Ubuntu; font-size: 14px; color: #ffffff;}
  .footercopy a {color: #ffffff; text-decoration: underline;}

  @media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
  body[yahoo] .hide {display: none!important;}
  body[yahoo] .buttonwrapper {background-color: transparent!important;}
  body[yahoo] .button {padding: 0px!important;}
  body[yahoo] .button a {background-color: #0e3635; padding: 15px 15px 13px!important;}
 }
  </style>
</head>

<body yahoo bgcolor='#f4f4f4' style='font-family:colfax, helvetica neue, arial, verdana, sans-serif;'>
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
          <td class='footer' bgcolor='#cececd' style='border-bottom: 4px solid #0e3635;'>
            <table width='100%' border='0' cellspacing='0' cellpadding='0'>
              <tr>
                <td align='center' class='footercopy'>
                &copy; <?php echo date('Y'); ?>
<a href='#' class='unsubscribe' style='font-family: sans-serif;'>
    <font color='#ffffff'><strong>TORUS</strong></font>
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

</html>
";
return $_FooterLayout_;
}




public static function emailSectionHeaderLayout()
{
$_HeaderLayout_ = "
<!DOCTYPE html
    PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>

<head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
    <title></title>
    <link rel='stylesheet' type='text/css' href='//fonts.googleapis.com/css?family=Ubuntu' />
    <style type='text/css'>
    body {
        margin: 0;
        padding: 0;
        min-width: 100% !important;
    }

    img {
        height: auto;
    }

    .content {
        width: 100%;
        max-width: 600px;
        border: 1px solid #f2f2f2;
    }

    .header {
        padding: 0;
    }

    .innerpadding {
        padding: 30px 30px 10px 30px;
    }

    .borderbottom {
        background-color: #101010 !important;
    }

    .subhead {
        font-size: 15px;
        color: #ffffff;
        letter-spacing: 10px;
    }

    .h1 {
        color: #ffffff;
    }

    .h2,
    .bodycopy {
        color: #ffffff;
    }

    .privacy {
        color: #ffffff;
        font-size: 11px;
        padding: 5px 0;
    }

    .h1 {
        font-size: 30px;
        line-height: 38px;
        font-weight: bold;
    }

    .h2 {
        padding: 0 0 15px 0;
        font-size: 14px;
        line-height: 24px;
    }

    .h3 {
        padding: 0 0 5px 0;
        font-size: 14px;
        line-height: 28px;
        text-transform: uppercase
    }

    .bodycopy {
        font-size: 14px;
        line-height: 22px;
    }

    .button {
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        padding: 0 30px 0 30px;
    }

    .button a {
        color: #ffffff;
        text-decoration: none;
    }

    .footer {
        padding: 0;
        background: #101010 !important;
    }

    .footer td a {
        color: #d90368;
        text-decoration: none
    }

    .footercopy {
        font-size: 14px;
        color: #ffffff;
    }

    .footercopy a {
        color: #ffffff;
        text-decoration: none;
    }

    ul {
        margin: 0;
    }

    a {
        color: #d90368 !important;
    }

    td .list li strong {
        color: black !important;
        font-weight: 400;
    }

    .alignment {
        display: inline-block;
        background: #ffffff;
        width: 100%;
        height: 2px;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
        margin-bottom: 4px;
    }

    .action-btn {
        background-color: rgb(36, 121, 52);
        padding-top: 1%;
        padding-bottom: 1%;
        color: #fff;
        width: 100%;
    }

    .social {
        background: linear-gradient(45deg, rgba(71, 102, 255, 1.0) 0%, rgba(217, 3, 104, 1.0) 100%) !important;
    }

    @media only screen and (max-width: 550px),
    screen and (max-device-width: 550px) {
        body[yahoo] .hide {
            display: none !important;
        }

        body[yahoo] .buttonwrapper {
            background-color: transparent !important;
        }

        body[yahoo] .button {
            padding: 0px !important;
        }

        body[yahoo] .button a {
            background-color: #f47e20;
            padding: 15px 15px 13px !important;
        }

        .es-mobile-hidden,
        .es-hidden {
            display: none !important
        }
    }
    </style>
</head>

<body yahoo bgcolor='#fff' style='font-family:colfax, helvetica neue, arial, verdana, sans-serif;'>
    <table width='100%' bgcolor='#fff' border='0' cellpadding='0' cellspacing='0'>
        <tr>
            <td>
                <table bgcolor='#ffffff' class='content' align='center' cellpadding='0' cellspacing='0' border='0'>
                    <tr>
                        <td bgcolor='#101010' class='header'>
                            <table width='100%' align='left' border='0' cellpadding='0' cellspacing='0'>
                                <tr>
                                    <td> <img class='fix' src='https://iff.torusguru.com/img/headerbanner.png'
                                            width='100%' border='0' alt='' /> </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    ";
                    return $_HeaderLayout_;
                    }


                    public static function emailLayoutSectionFooter()
                    {
                    $_FooterLayout_ = "
                    <tr>
                        <td class='footer' bgcolor='#fff'>
                            <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                                <tr>
                                    <td class='bodycopy' style='padding: 3px 30px 10px 30px; color: #ffffff;'><b>Regards,</b><br>The Inclusive FinTech Forum Team<br><a
                                            href='mailto:hello@inclusivefintechforum.com'>hello@inclusivefintechforum.com</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td> <span class='alignment'></span>
                                        <table border='0' cellspacing='0' cellpadding='0'>
                                            <tr>
                                                <td><img class='fix' src='https://iff.torusguru.com/img/footerlogo.png'
                                                        width='100%' border='0' alt='' /></td>
                                            </tr>
                                            <tr class='es-mobile-hidden' style='border-collapse:collapse'>
                                                <td style='padding:0;Margin:0'>
                                                    <!--CTA socials-->
                                                    <table align='center' border='0' cellpadding='0' width='100%'
                                                        role='presentation'
                                                        style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                                                        <tr
                                                            style='border-collapse:collapse; background: linear-gradient(45deg, rgba(71, 102, 255, 1.0) 0%, rgba(217, 3, 104, 1.0) 100%)!important;'>
                                                            <td style='padding:0;Margin:0;width:100%' align='center'>
                                                                <map id='ImgMap0' name='ImgMap0'><area shape='rect'
                                                                        coords='285, 27, 305, 75'
                                                                        href='https://www.facebook.com/people/Inclusive-FinTech-Forum/100088992566132/'
                                                                        target='_blank' alt><area shape='rect'
                                                                        coords='340, 27, 375, 75'
                                                                        href='https://www.linkedin.com/company/inclusive-fintech-forum/'
                                                                        target='_blank' alt><area shape='rect'
                                                                        coords='314, 27, 340, 75'
                                                                        href='https://twitter.com/inclusIFF'
                                                                        target='_blank' alt></map><img usemap='#ImgMap0'
                                                                    src='https://iff.torusguru.com/img/socialmediafooter.png'
                                                                    alt width='520'
                                                                    style='display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic'>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr align='center'>
                                                <td class='privacy'>View our privacy policy <a
                                                        href='https://admin.torusguru.com/privacy'
                                                        target='_blank'>here</a>. | <a target='_blank'
                                                        href='mailto:hello@inclusivefintechforum.com'>Contact
                                                        Us</a><br>Copyright &#169; 2023 Elevandi, all rights reserved
                                                </td>
                                            </tr>
                                        </table>
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
";
return $_FooterLayout_;
}


public static function emailLayoutSectionFooterInfo()
{
$_FooterLayout_ = "
<tr>
    <td class='h2' style='padding:0; color:#d90368;text-transform:uppercase'><b><br>Important
            Information for Delegates: </b> </td>
</tr>

<tr>
    <td class='h2' style='padding: 5px 0;'><b>Accommodation</b> </td>
</tr>
<tr>
    <td class='h2' style=' padding: 5px 0;'>
          <ul>
              <li><b>Radisson Blu Kigali </b></li>
              <ul>
                <li>The venue is directly attached to the Radisson Blu Kigali. You may book a room at a negotiated rate for our delegates through this <a href='https://www.radissonhotels.com/en-us/booking/room-display?checkInDate=2023-06-19&checkOutDate=2023-06-20&adults%5B%5D=1&children%5B%5D=0&searchType=pac&promotionCode=FIN2023&voucher=&brandFirst=rdb&hotelCode=RWKGLCCR' target='_blank'>link</a></li>
              </ul>
              <li><b>Other hotels in Kigali</b></li>
              <ul>
                <li>You may also book accommodation at other hotels through this <a href='https://www.travelzuri.com/B2C/Admin/GTC/EventInfoCart.aspx?Ref_Type=HTL&CID=390&CityCode=KGL&EventName=Inclusive%20Fintech%20Forum&SSr=EVTHL' target='_blank'>link</a></li>
              </ul>
          </ul>

    </td>
</tr>
<tr>
    <td class='h2' style='padding: 5px 0;'><b>Travelling to Rwanda</b> </td>
</tr>
<tr>
    <td class='h2'>
        <ul>
            <li>Citizens of countries who are members Commonwealth, African Union and La Francophonie get visa upon
                arrival. Visa fees are waived for a visit of 30 days.</li>
            <li>More information on travel to Rwanda can be found <a href='https://migration.gov.rw/visa-on-arrival'
                    target='_blank'>here</a></li>
        </ul>
    </td>
</tr>
<tr>
    <td class='h2' style='padding:0;'><b>Health Travel guidelines</b> </td>
</tr>
<tr>
    <td class='h2'>
        <ul>
            <li><b>Covid-19</b></li>
            <ul>
                <li>Rwanda’s response to the Covid-19 pandemic has been swift & robust. Measures are in place to ensure
                    that your experience is safe & productive. <br>
                    Please click here <a href='https://www.rbc.gov.rw/index.php?id=188' target='_blank'>here</a> to view
                    current travel guidelines to Rwanda. Please note that these will be updated on a regular basis.
                </li>
            </ul>
            <li><b>Yellow Fever</b></li>
            <ul>
                <li>Yellow Fever vaccination certificate is not required for travelers coming from Yellow Fever
                    non-endemic countries.
                </li>
                <li>Yellow Fever vaccination certificate is mandatory for travelers coming from Yellow Fever endemic
                    countries.</li>
                <li>A traveler coming from Yellow Fever endemic country and does not have a yellow fever certificate
                    will be vaccinated upon entry.</li>
            </ul>
            <li><b>Additional information</b></li>
            <ul>
                <li>Travelers should also follow their country’s health guidelines to facilitate their travel to and
                    from Rwanda.
                </li>
            </ul>
        </ul>
    </td>
</tr>
";
return $_FooterLayout_;
}


public static function emailSectionHeaderLayoutCMPD()
{
$_HeaderLayout_ = "
<!DOCTYPE html
    PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>

<head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
    <title></title>
    <link rel='stylesheet' type='text/css' href='//fonts.googleapis.com/css?family=Ubuntu' />
    <style type='text/css'>
    body {
        margin: 0;
        padding: 0;
        min-width: 100% !important;
    }

    img {
        height: auto;
    }

    .content {
        width: 100%;
        max-width: 600px;
        border: 1px solid #f2f2f2;
    }

    .header {
        padding: 0;
    }

    .innerpadding {
        padding: 30px 30px 10px 30px;
    }

    .borderbottom {
        background-color: #010508 !important;
    }

    .subhead {
        font-size: 15px;
        color: #ffffff;
        letter-spacing: 10px;
    }

    .h1 {
        color: #ffffff;
    }

    .h2,
    .bodycopy {
        color: #ffffff;
    }

    .privacy {
        color: #ffffff;
        font-size: 11px;
        padding: 5px 0;
    }

    .h1 {
        font-size: 30px;
        line-height: 38px;
        font-weight: bold;
    }

    .h2 {
        padding: 0 0 15px 0;
        font-size: 14px;
        line-height: 24px;
    }

    .h3 {
        padding: 0 0 5px 0;
        font-size: 14px;
        line-height: 28px;
        text-transform: uppercase
    }

    .bodycopy {
        font-size: 14px;
        line-height: 22px;
    }

    .button {
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        padding: 0 30px 0 30px;
    }

    .button a {
        color: #ffffff;
        text-decoration: none;
    }

    .footer {
        padding: 0;
        background: #101010 !important;
    }

    .footer td a {
        color: #d90368;
        text-decoration: none
    }

    .footercopy {
        font-size: 14px;
        color: #ffffff;
    }

    .footercopy a {
        color: #ffffff;
        text-decoration: none;
    }

    ul {
        margin: 0;
    }

    a {
        color: #d90368 !important;
    }

    td .list li strong {
        color: black !important;
        font-weight: 400;
    }

    .alignment {
        display: inline-block;
        background: #ffffff;
        width: 100%;
        height: 2px;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
        margin-bottom: 4px;
    }

    .action-btn {
        background-color: rgb(36, 121, 52);
        padding-top: 1%;
        padding-bottom: 1%;
        color: #fff;
        width: 100%;
    }

    .social {
        background: linear-gradient(45deg, rgba(71, 102, 255, 1.0) 0%, rgba(217, 3, 104, 1.0) 100%) !important;
    }

    @media only screen and (max-width: 550px),
    screen and (max-device-width: 550px) {
        body[yahoo] .hide {
            display: none !important;
        }

        body[yahoo] .buttonwrapper {
            background-color: transparent !important;
        }

        body[yahoo] .button {
            padding: 0px !important;
        }

        body[yahoo] .button a {
            background-color: #f47e20;
            padding: 15px 15px 13px !important;
        }

        .es-mobile-hidden,
        .es-hidden {
            display: none !important
        }
    }
    </style>
</head>

<body yahoo bgcolor='#fff' style='font-family:colfax, helvetica neue, arial, verdana, sans-serif;'>
    <table width='100%' bgcolor='#fff' border='0' cellpadding='0' cellspacing='0'>
        <tr>
            <td>
                <table bgcolor='#ffffff' class='content' align='center' cellpadding='0' cellspacing='0' border='0'>
                    <tr>
                        <td bgcolor='#101010' class='header'>
                            <table width='100%' align='left' border='0' cellpadding='0' cellspacing='0'>
                                <tr>
                                    <td> <img class='fix' src='https://iff.torusguru.com/img/CMPD_Email_Banner_FA.jpeg'
                                            width='100%' border='0' alt='' /> </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    ";
                    return $_HeaderLayout_;
                    }


                    public static function emailLayoutSectionFooterCMPD()
                    {
                    $_FooterLayout_ = "
                    <tr>
                        <td class='footer' bgcolor='#fff'>
                            <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                                <tr>
                                    <td class='bodycopy' style='padding: 3px 30px 10px 30px; color: #ffffff;'>If you
                                        have any questions, please contact us at <a
                                            href='mailto:hello@inclusivefintechforum.com'>hello@inclusivefintechforum.com</a><br><br>Kind
                                        Regards,<br>The Capital Meets Policy Dialogue team
                                    </td>
                                </tr>
                                <tr>
                                    <td> <span class='alignment'></span>
                                        <table border='0' cellspacing='0' cellpadding='0'>
                                            <tr>
                                                <td><img src='https://iff.torusguru.com/img/CMPD_Footer_FA.jpeg'
                                                        width='100%' border='0' alt='' /></td>
                                            </tr>
                                            <tr class='es-mobile-hidden' style='border-collapse:collapse;'>
                                                <td style='padding:0;Margin:0'>
                                                    <!--CTA socials-->
                                                    <table align='center' border='0' cellpadding='0' width='100%'
                                                        role='presentation'
                                                        style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                                                        <tr
                                                            style='border-collapse:collapse; background: linear-gradient(45deg, #091934 0%, #020810 100%)!important;'>
                                                            <td style='padding:0;Margin:0;width:100%' align='center'>
                                                                <map id='ImgMap0' name='ImgMap0'><area shape='rect'
                                                                        coords='400, 27, 420, 75'
                                                                        href='https://www.facebook.com/people/Inclusive-FinTech-Forum/100088992566132/'
                                                                        target='_blank' alt><area shape='rect'
                                                                        coords='450, 27, 500, 75'
                                                                        href='https://www.linkedin.com/company/inclusive-fintech-forum/'
                                                                        target='_blank' alt><area shape='rect'
                                                                        coords='420, 27, 450, 75'
                                                                        href='https://twitter.com/inclusIFF'
                                                                        target='_blank' alt></map><img usemap='#ImgMap0'
                                                                    src='https://iff.torusguru.com/img/CMPD_Footer_FA1.jpeg'
                                                                    alt width='520'
                                                                    style='display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic'>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr align='center'>
                                                <td class='privacy'>View our privacy policy <a
                                                        href='https://admin.torusguru.com/privacy'
                                                        target='_blank'>here</a>. | <a target='_blank'
                                                        href='mailto:hello@inclusivefintechforum.com'>Contact
                                                        Us</a><br>Copyright &#169; 2023 Elevandi, all rights reserved
                                                </td>
                                            </tr>
                                        </table>
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
";
return $_FooterLayout_;
}



/** - Send Payment reminder email to paid participants who have not completed their payment yet (Abandoned cart)- */
public static function sendIFFPaymentReminder($_data_)
{
$_data_ = (object)$_data_;
$email = $_data_->email;
$firstname = $_data_->firstname;
$payment_link = $_data_->payment_link;

$_Email_ = $email;
$_Subject_ = 'Inclusive FinTech Forum 2023 Registration';
$_Message_ = self::emailSectionHeaderLayout() . "
<tr>
    <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
        <table width='100%' border='0' cellspacing='0' cellpadding='0'>
            <tr>
                <td class='h2' style=' padding: 15px 0px 30px 0px; '>Dear $firstname,</td>
            </tr>
            <tr>
                <td class='bodycopy' style=''>We noticed that you recently submitted your registration for the Inclusive
                    FinTech Forum 2023 but have not yet completed it. <br><br>Do note that your registration is not
                    confirmed until you make payment to complete your registration, and receive your registration
                    number.
                    <br><br>
                </td>
            </tr>

            <tr>
                <td class='bodycopy' style=''><a href='$payment_link'><button
                            style='background-color: #d90368; border: 1px solid #d90368; padding: 5px 6px 5px 6px; color: #ffffff;'>Complete
                            My Registration</button></a><br><br> </td>
            </tr>
            <tr>
                <td class='bodycopy' style=''> For the latest agenda and event programme updates, please visit: <a
                        href='https://www.inclusivefintechforum.com/'>www.inclusivefintechforum.com</a><br><br> </td>
            </tr>
            <tr>
                <td class='bodycopy' style=''> If you have any questions about the Forum, or need assistance with the
                    registration process, please <a href='mailto:hello@inclusivefintechforum.com'>get in touch</a> with
                    us.<br><br> </td>
            </tr>
        </table>
    </td>
</tr>" . self::emailLayoutSectionFooter();

// Email::send($_Email_, $_Subject_, $_Message_);
$User = new User();
$User->send_mail($_Email_, $_Message_, $_Subject_);
}

































































/** - Event ID :: 9 - THE COALITION APRF- Send Email - Participant - Email received after application approved - */
public static function sendEmailToParticipantAfterApplication0001($_data_)
{
$_data_ = (object)$_data_;
$email = $_data_->email;
$firstname = $_data_->firstname;

$_Email_ = $email;
$_Subject_ = 'Registration confirmation: Africa Private Sector Refugee Forum 2021';
$_Message_ = self::emailSectionHeaderLayout0001() . "
<tr>
    <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
        <table width='100%' border='0' cellspacing='0' cellpadding='0'>
            <tr>
                <td class='h2'>Dear $firstname</td>
            </tr>
            <tr>
            <tr>
            <tr>
                <td class='h2'>Thank you for registering to attend the inaugural African
                    Private Sector Refugee Forum on Forced Displacement that will be held in Kigali, Rwanda, from 30
                    November to 2 December 2021 at the Kigali Convention Center.
            </tr>

            <tr>
                <td class='h2' style='padding:0;'><b>Your registration is hereby confirmed. </b>
                </td>
            </tr>
            <tr>
                <td class='h2' style='padding:20px 0; color:#002060; text-decoration:underline;'>
                    <b>Quick Practical Information:</b>
                </td>
            </tr>
            <tr>
                <td class='h2' style='padding:0;'>For the most updated program and on-going
                    discussion, please connect and engage with us here:</td>
            </tr>
            <tr>
                <td style='padding: 10px 0 0 0;'>
                    <table border='0' cellspacing='0' cellpadding='0'>
                        <tr class='social-image'>
                            <td width='30' style='text-align: center;padding: 0 0px 0 0px;'>
                                <a href='https://www.arf.thecoalitionafrica.com' target='_blank'>
                                    <img src='http://aprf2021.torusguru.com/img/globe.png' width='37' height='37'
                                        alt='Facebook' border='0' />
                                </a>
                            </td>
                            <td width='30' style='text-align: center; padding: 0 5px 0 10px;'>
                                <a href='https://twitter.com/africaforum2021' target='_blank'>
                                    <img src='http://aprf2021.torusguru.com/img/twitter.png' width='37' height='37'
                                        alt='Twitter' border='0' />
                                </a>
                            </td>
                            <td width='30' style='text-align: center; padding: 0 5px 0 10px; '>
                                <a href='https://www.linkedin.com/company/amahoro-coalition/' target='_blank'>
                                    <img src='http://aprf2021.torusguru.com/img/linkedin.png' width='37' height='37'
                                        alt='Instagram' border='0' />
                                </a>
                            </td>
                            <td width='30' style='text-align: center; padding: 0 5px 0 10px; '>
                                <a href='https://www.instagram.com/africaforum2021/' target='_blank'>
                                    <img src='http://aprf2021.torusguru.com/img/instagram.png' width='37' height='37'
                                        alt='Instagram' border='0' />
                                </a>
                            </td>

                        </tr>

                    </table>
                </td>
            </tr>
            <tr>
                <td class='h2' style='padding:15px 0 0 0;'><b>Connect with our official tag: </b>
                    <a href='https://www.arf.thecoalitionafrica.com' target='_bank'>#36MillionSolutions</a></a>
                </td>
            </tr>
            <tr>
                <td class='h2' style='padding:20px 0; color:#002060; text-decoration:underline;'>
                    <b>International Travelers: </b>
                </td>
            </tr>
            <tr>
                <td class='h2' style='padding:0;'><b>COVID Protocols:</b></td>
            </tr>
            <tr>
                <td class='h2' style='padding:0 0 20px 0;'>Please find the latest information on
                    traveling to Rwanda and precautions in place due to COVID: <a
                        href='https://travel.rbc.gov.rw/travel/'> https://travel.rbc.gov.rw/travel/</a> </td>
            </tr>
            <tr>
                <td class='h2' style='padding:0;'>Some protocols to note include: </td>
            </tr>
            <tr>
                <td class='h2' style=' padding:0;'>
                    <ul>
                        <li>COVID-19 PCR test required for all travelers - must be less than 72 hours</li>
                        <li>Fully vaccinated travelers are not required to quarantine upon arrival</li>
                        <li> Unvaccinated travelers will take PCR test upon arrival and will need to quarantine for up
                            to 6 hours until the test results are in. </li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td class='h2' style=' padding:20px 0;'><i>Please note that the COVID protocols may
                        change as we get closer to the event. We will share updates on the situation in Rwanda.</i>
                </td>
            </tr>
            <tr>
                <td class='h2' style='padding:0;'><b>Flights to Kigali:</b></td>
            </tr>
            <tr>
                <td class='h2' style='padding:0;'>RwandAir is pleased to provide you with a 15%
                    discount on all serviced routes. Please use code <b>RBC20</b> when booking on <a
                        href='www.rwandair.com' target='_bank'>www.rwandair.com</a> </td>
            </tr>
            <tr>
                <td class='h2' style='padding:20px 0 0 0;'><b>Visa information</b></td>
            </tr>
            <tr>
                <td class='h2' style='padding:0 0 20px 0;'>
                    <ul>
                        <li>Visas are issued on arrival for all countries </li>
                        <li>Visa fees for citizens of African Union, Commonwealth and La Francophonie member states are
                            waived for a visit of up to 30 days </li>
                        <li> For more information on Rwanda's open visa policy, please <a
                                href='https://www.migration.gov.rw/our-services/visa/' target='_blank'>click here</a>
                        </li>
                    </ul>
                </td>
            </tr>

            <tr>
                <td class='h2' style='padding:0;'><b>Accommodation: </b></td>
            </tr>
            <tr>
                <td class='h2' style=' padding:0;'>
                    We have negotiated preferred conference rates with two hotels. Please book by November 15, 2021 to
                    access the preferential rates.
                    <ul>
                        <li>Radisson : <a
                                href='https://www.radissonhotels.com/en-us/booking/room-display?checkInDate=2021-11-29&checkOutDate=2021-12-03&adults%5B%5D=1&children%5B%5D=0&searchType=pac&promotionCode=AC2021&brandFirst=rdb&hotelCode=KGLZH'
                                target='_blank'>click here</a></li>
                        <li>Marriott : <a
                                href='https://www.marriott.com/event-reservations/reservation-link.mi?id=1635228903300&key=GRP&app=resvlink'
                                target='_blank'>click here</a></li>
                    </ul>
                </td>
            </tr>

            <tr>
                <td class='h2' style='padding:20px 0 0 0;'><b>Transport: </b></td>
            </tr>
            <tr>
                <td class='h2' style=' padding:0 0 20px 0;'>
                    <ul>
                        <li>We will provide you with shuttle service from the airport to each hotel.</li>
                        <li>We will provide you with shuttle service between each hotel and all forum related
                            activities. </li>
                        <li>Please note that we will not be providing shuttle services from any other hotels in Kigali
                        </li>
                    </ul>
                </td>
            </tr>

            <tr>
                <td class='h2' style='padding:0;'>We can not wait to connect with you in Kigali!
                </td>
            </tr>
            <tr>
                <td class='h2'>If you have any inquiries feel free to contact us at <a
                        href='mailto:forum@thecoalitionafrica.com'>forum@thecoalitionafrica.com </a> </td>
            </tr>
            <tr>
                <td style='padding: 10px 0 0 0;'>
                    <table border='0' cellspacing='0' cellpadding='0'>
                        <tr class=''>
                            <td width='150' style='text-align: center;padding: 0 0px 0 0px;'>
                                <a href='https://www.arf.thecoalitionafrica.com' target='_blank'>
                                    <img src='http://aprf2021.torusguru.com/img/partners/part1.png' width='100%'
                                        alt='Facebook' border='0' />
                                </a>
                            </td>
                            <td width='150' style='text-align: center; padding: 0 5px 0 10px;'>
                                <a href='https://www.unhcr.org/afr/' target='_blank'>
                                    <img src='http://aprf2021.torusguru.com/img/partners/part2.png' width='100%'
                                        alt='Twitter' border='0' />
                                </a>
                            </td>
                            <td width='150' style='text-align: center; padding: 0 5px 0 10px; '>
                                <a href='https://www.minema.gov.rw/' target='_blank'>
                                    <img src='http://aprf2021.torusguru.com/img/partners/part3.png' width='100%'
                                        alt='Instagram' border='0' />
                                </a>
                            </td>
                            <td width='150' style='text-align: center; padding: 0 5px 0 10px; '>
                                <a href='https://africanentrepreneurcollective.org/' target='_blank'>
                                    <img src='http://aprf2021.torusguru.com/img/partners/part4.png' width='100%'
                                        alt='Instagram' border='0' />
                                </a>
                            </td>

                        </tr>

                    </table>
                </td>
            </tr>
</tr>
</table>
</td>
</tr>

" . self::emailLayoutSectionFooter0001();

Email::send($_Email_, $_Subject_, $_Message_, 9);
}




/** - Event ID :: 9 - THE COALITION APRF- Send Email - Participant - Email received after application Denied - */
public static function sendEmailToParticipantAfterApplication0001Decline($_data_)
{
$_data_ = (object)$_data_;
$email = $_data_->email;
$firstname = $_data_->firstname;

$_Email_ = $email;
$_Subject_ = 'Virtual Registration: Africa Private Sector Refugee Forum 2021';
$_Message_ = self::emailSectionHeaderLayout0001() . "
<tr>
    <td class='innerpadding borderbottom' bgcolor='#101010' style='background:#101010;'>
        <table width='100%' border='0' cellspacing='0' cellpadding='0'>
            <tr>
                <td class='h2'>Dear $firstname</td>
            </tr>
            <tr>
            <tr>
            <tr>
                <td class='h2'>Thank you for registering to attend the inaugural African
                    Private Sector Refugee Forum on Forced Displacement that will be held in Kigali, Rwanda, from 30
                    November to 2 December 2021.
            </tr>

            <tr>
                <td class='h2' style='padding:0;'><b>Your registration is confirmed to join the
                        event VIRTUALLY. We will share the necessary links for your attendance no later than November
                        29th, 2021.</b> </td>
            </tr>

            <tr>
                <td class='h2' style='padding:15px 0 0 5px;'>Please stay connected and engaged with
                    us on the following:</td>
            </tr>
            <tr>
                <td style='padding: 10px 0 0 0;'>
                    <table border='0' cellspacing='0' cellpadding='0'>
                        <tr class='social-image'>
                            <td width='30' style='text-align: center;padding: 0 0px 0 0px;'>
                                <a href='https://www.arf.thecoalitionafrica.com' target='_blank'>
                                    <img src='http://aprf2021.torusguru.com/img/globe.png' width='37' height='37'
                                        alt='Facebook' border='0' />
                                </a>
                            </td>
                            <td width='30' style='text-align: center; padding: 0 5px 0 10px;'>
                                <a href='https://twitter.com/africaforum2021' target='_blank'>
                                    <img src='http://aprf2021.torusguru.com/img/twitter.png' width='37' height='37'
                                        alt='Twitter' border='0' />
                                </a>
                            </td>
                            <td width='30' style='text-align: center; padding: 0 5px 0 10px; '>
                                <a href='https://www.linkedin.com/company/amahoro-coalition/' target='_blank'>
                                    <img src='http://aprf2021.torusguru.com/img/linkedin.png' width='37' height='37'
                                        alt='Instagram' border='0' />
                                </a>
                            </td>
                            <td width='30' style='text-align: center; padding: 0 5px 0 10px; '>
                                <a href='https://www.instagram.com/africaforum2021/' target='_blank'>
                                    <img src='http://aprf2021.torusguru.com/img/instagram.png' width='37' height='37'
                                        alt='Instagram' border='0' />
                                </a>
                            </td>

                        </tr>

                    </table>
                </td>
            </tr>
            <tr>
                <td class='h2' style='padding:15px 0 0 0;'><b>Connect with our official tag: </b>
                    <a href='https://www.arf.thecoalitionafrica.com' target='_bank'>#36MillionSolutions</a></a>
                </td>
            </tr>

            <tr>
                <td class='h2'>If you have any inquiries feel free to contact us at <a
                        href='mailto:forum@thecoalitionafrica.com'>forum@thecoalitionafrica.com </a> </td>
            </tr>
            <tr>
                <td style='padding: 10px 0 0 0;'>
                    <table border='0' cellspacing='0' cellpadding='0'>
                        <tr class=''>
                            <td width='150' style='text-align: center;padding: 0 0px 0 0px;'>
                                <a href='https://www.arf.thecoalitionafrica.com' target='_blank'>
                                    <img src='http://aprf2021.torusguru.com/img/partners/part1.png' width='100%'
                                        alt='Facebook' border='0' />
                                </a>
                            </td>
                            <td width='150' style='text-align: center; padding: 0 5px 0 10px;'>
                                <a href='https://www.unhcr.org/afr/' target='_blank'>
                                    <img src='http://aprf2021.torusguru.com/img/partners/part2.png' width='100%'
                                        alt='Twitter' border='0' />
                                </a>
                            </td>
                            <td width='150' style='text-align: center; padding: 0 5px 0 10px; '>
                                <a href='https://www.minema.gov.rw/' target='_blank'>
                                    <img src='http://aprf2021.torusguru.com/img/partners/part3.png' width='100%'
                                        alt='Instagram' border='0' />
                                </a>
                            </td>
                            <td width='150' style='text-align: center; padding: 0 5px 0 10px; '>
                                <a href='https://africanentrepreneurcollective.org/' target='_blank'>
                                    <img src='http://aprf2021.torusguru.com/img/partners/part4.png' width='100%'
                                        alt='Instagram' border='0' />
                                </a>
                            </td>

                        </tr>

                    </table>
                </td>
            </tr>
</tr>
</table>
</td>
</tr>

" . self::emailLayoutSectionFooter0001();

Email::send($_Email_, $_Subject_, $_Message_, 9);
}




public static function emailSectionHeaderLayout0001()
{
$_HeaderLayout_ = "
<!DOCTYPE html
    PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>

<head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
    <title>The TORUS</title>
    <link rel='stylesheet' type='text/css' href='//fonts.googleapis.com/css?family=Ubuntu' />
    <style type='text/css'>
    body {
        margin: 0;
        padding: 0;
        min-width: 100% !important;

    }

    img {
        height: auto;
    }

    .content {
        width: 100%;
        max-width: 700px;
        border: 1px solid #f2f2f2;
    }

    /* .header {padding: 15px 30px 15px 30px;} */
    .innerpadding {
        padding: 50px 30px 50px 30px;
    }

    .borderbottom {
        background-color: #f6f6f6;
    }

    .subhead {
        font-size: 15px;
        color: #ffffff;

        letter-spacing: 10px;
    }

    .h1 {
        color: #ffffff;

    }

    .h2,
    .bodycopy {
        color: #002060;

    }

    .h1 {
        font-size: 30px;
        line-height: 38px;
        font-weight: bold;
    }

    .h2 {
        padding: 0 0 15px 0;
        font-size: 14px;
        line-height: 22px;
    }

    .h3 {
        padding: 0 0 5px 0;
        font-size: 14px;
        line-height: 28px;
        text-transform: uppercase
    }

    .bodycopy {
        font-size: 14px;
        line-height: 22px;
    }

    .button {
        text-align: center;
        font-size: 18px;

        font-weight: bold;
        padding: 0 30px 0 30px;
    }

    .button a {
        color: #ffffff;
        text-decoration: none;
    }

    .footer {
        padding: 10px 30px 10px 30px;
        border-bottom: 10px solid #002060;
        background: #e9e9e9;
    }

    .footer td a {
        color: #002060;
        text-decoration: none
    }

    .footercopy {

        font-size: 14px;
        color: #ffffff;
    }

    .footercopy a {
        color: #ffffff;
        text-decoration: none;
    }

    .social-image img {
        width: 30px;
    }

    ul {
        margin: 0;
    }

    a {
        color: #002060 !important;
    }

    .alignment {
        display: inline-block;
        background: #002060;
        width: 100px;
        height: 2px;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
        margin-bottom: 4px;
    }

    @media only screen and (max-width: 550px),
    screen and (max-device-width: 550px) {
        body[yahoo] .hide {
            display: none !important;
        }

        body[yahoo] .buttonwrapper {
            background-color: transparent !important;
        }

        body[yahoo] .button {
            padding: 0px !important;
        }

        body[yahoo] .button a {
            background-color: #f47e20;
            padding: 15px 15px 13px !important;
        }

    }
    </style>
</head>

<body yahoo bgcolor='#f4f4f4'>
    <table width='100%' bgcolor='#fff' border='0' cellpadding='0' cellspacing='0'>
        <tr>
            <td>
                <table bgcolor='#ffffff' class='content' align='center' cellpadding='0' cellspacing='0' border='0'>
                    <tr>
                        <td bgcolor='#fff' class='header'>
                            <table align='left' border='0' cellpadding='0' cellspacing='0'>
                                <tr>
                                    <td>
                                        <img class='fix' src='http://aprf2021.torusguru.com/img/banner_v2.png'
                                            style='width: 100%;' border='0' alt='' />
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    ";
                    return $_HeaderLayout_;
                    }


                    public static function emailLayoutSectionFooter0001()
                    {
                    $_FooterLayout_ = "
                    <tr>
                        <td class='footer' bgcolor='#f6f6f6'>
                            <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                                <tr>
                                    <td>
                                        <table border='0' cellspacing='0' cellpadding='0'>

                                            <tr>
                                                <td class='h2' style='padding:0;'><b>Regards,</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class='h2'>The Amahoro Coalition </td>
                                            </tr>
                                        </table>
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

";
return $_FooterLayout_;
}




}



?>