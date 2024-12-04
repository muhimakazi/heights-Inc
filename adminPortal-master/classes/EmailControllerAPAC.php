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
    $urlPortal = DNADMIN;

    $_Email_ = $email;
    $_Subject_ = 'Account - Hanga Pitchfest Admin';
    $_Message_ = self::emailSectionHeaderLayout() . "
                   <tr>
                      <td class='innerpadding borderbottom'>
                        <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                          <tr>
                            <td class='h2' style='font-family: sans-serif;'>Dear $firstname!</td>
                          </tr>
                          <tr>
                            <td class='bodycopy' style='font-family: sans-serif;'>
                              This is to notice you that your Hanga Admin Account has been created. Below are your creadentials: <br><br>

                              <span>Username: <strong style='margin-left: 10px;'> $email </strong> <span><br>
                              <span>Password: <strong style='margin-left: 14px;'> $password </strong>  <span><br>
                              <span>Link:  <strong style='margin-left: 52px;'> <a href='$urlPortal' style='text-decoration: none;' > $urlPortal </strong>   </a> <span><br> 
                            </td>
                          </tr>
                          
                        </table>
                      </td>
                    </tr>
                    
          " . self::emailLayoutSectionFooter();

    // Email::sendEmail($_Email_, $_Subject_, $_Message_);     
    $User = new User();     
    $User->send_mail($_Email_, $_Message_, $_Subject_);
  }

  
  /** - 1 - Send Email - Participant - After CBO Application - NEED TO PAY -  */
  public static function sendEmailToParticipantAfterCBOApllication($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;

    $_Email_ = $email;
    $_Subject_ = 'IUCN Africa Protected Areas Congress (APAC) CBO Application';
    $_Message_ = self::emailSectionHeaderLayout() . "
                  <tr>
                    <td class='innerpadding borderbottom'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                          <td class='h2' style='font-family: calibri; padding: 15px 0px 30px 0px; '>Dear <b>$firstname</b>,</td>
                        </tr>
                        <tr>
                          <td class='bodycopy' style='font-family: calibri;'>
                          Thank you for registering your interest to attend the <br><b>IUCN Africa Protected Areas Congress (APAC)</b> as a CBO.<br><br>
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
    $_Subject_ = 'Payment information - IUCN Africa Protected Areas Congress (APAC) CBO Application';
    $_Message_ = self::emailSectionHeaderLayout() . "
                  <tr>
                    <td class='innerpadding borderbottom'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                          <td class='h2' style='font-family: calibri; padding: 15px 0px 30px 0px; '>Dear <b>$firstname</b>,</td>
                        </tr>
                        <tr>
                          <td class='bodycopy' style='font-family: calibri;'>
                          We are pleased to inform you that your CBO application to attend the <b>IUCN Africa Protected Areas Congress (APAC),</b> that will be held in Kigali from 18 - 23 July 2022, has been accepted. <br><br>
                          Please proceed to the following link to complete your payment <a href='$payment_link'> $payment_link </a>
                          </td>
                          <tr><td class='h2' style='font-family: calibri;padding:0;'><b>Programme </b> </td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri;'>
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
    $_Subject_ = 'Confirmation - IUCN Africa Protected Areas Congress (APAC) Registration';
    $_Message_ = self::emailSectionHeaderLayout() . "
                 <tr>
                    <td class='innerpadding borderbottom'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                          <td class='h2' style='font-family: calibri; padding: 15px 0px 30px 0px; '>Dear <b>$firstname</b>,</td>
                        </tr>
                        <tr>
                          <td class='bodycopy' style='font-family: calibri;'>
                          Thank you for registering to attend the IUCN Africa Protected Areas Congress (APAC) that will be held in Kigali from 18 - 23 July 2022.</td>
                          <tr><td class='h2' style='font-family: calibri;padding:0;'>Your receipt is attached to this email.</td></tr>

                          <tr><td class='h2' style='font-family: calibri;padding:0;'><b>Invitation To The Africa Protected Areas Congress</b> </td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri;'>
                              <ul>
                                <li><a href='$invitation_letter_link' target='_blank'>Click here</a> to download your invitation letter   </li>
                              </ul>
                              
                            </td>
                          </tr>
                          
                          <tr><td class='h2' style='font-family: calibri;padding: 15px 0;'><b>Badge collection</b> </td></tr>
                          <tr><td class='h2' style='font-family: calibri;'>You will receive information on how and when to collect your badge before the event. We kindly require that you bring the identification document you used in your registration process when collecting your badge. </td></tr>
                          <tr><td class='h2' style='font-family: calibri;padding:0;'><b>Programme </b> </td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri;'>
                                <ul>
                                  <li>You can view the congress programme at <a href='https://apacongress.africa/programme/'>apacongress.africa/programme</a></li>
                                </ul>
                            </td>
                          </tr>
                          <tr><td class='h2' style='font-family: calibri;padding:0; color:#37af47;text-transform:uppercase'><b>Important information for international delegates </b> </td></tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 5px 0;'><b>Accommodation</b> </td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri; padding: 5px 0;'>
                            A quota of rooms has been reserved for the IUCN APAC 2022 participants in hotels in the city centre and close to the conference venue. 
                            The reservations will be made on “first come, first served” basis. The hotel quotas are valid until one months before the conference. 
                            After that, the COC cannot guarantee the availability of the hotel rooms but will assist you in finding accommodation. The hotel fees 
                            include breakfast, service and VAT. All hotel fees are payable directly to the hotels. <br> 
                              <ul style='margin: 5px 0;'>
                                <li><a href='https://www.travelzuri.com/B2C/Admin/GTC/EventInfoCart.aspx?Ref_Type=HTL&CID=87&CityCode=KGL&EventName=Africa%20Protected%20Area%20Congress%20&SSr=EVTHL#' target='_blank'>Click here</a> to book your accommodation for your stay in Kigali   </li>
                              </ul>
                              
                            </td>
                          </tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 5px 0;'><b>Visa information:</b> </td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri;'>
                                <ul>
                                  <li>Visas are issued on arrival for all countries </li>
                                  <li>Visa fees for citizens of African Union, Commonwealth and La Francophonie member states are waived for a visit of up to 30 days</li>
                                  <li>Citizens of East African Community Member States (Burundi, Kenya, Uganda, United Republic of Tanzania and South Sudan) shall be issued a pass/entry visa free of charge upon arrival to stay for the period of six months.  </li>
                                  <li>Citizens of all countries that are not visa exempt, have the option to submit an online visa application. Payment can be done online or upon arrival. </li>
                                  <li><a href='https://www.migration.gov.rw/anounce/anounce/online-visa/' target='_blank'>Click here</a> for more information on Rwanda’s open visa policy  </li>
                                </ul>
                            </td>
                          </tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 5px 0;'><b>Destination information</b> </td></tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 5px 0;'>Rwanda’s stunning scenery and warm, friendly people offer unique experiences in one of the most remarkable countries in the world. It is blessed with extraordinary biodiversity, with incredible wildlife living throughout its volcanoes, montane rainforest and sweeping plains.</td></tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 15px 0;'>Go to <a href='https://www.visitrwanda.com/' target='_blank'>www.visitrwanda.com</a> for more information on activities and excursions you can experience during your time in Rwanda.</td></tr>


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
    $_Subject_ = 'Confirmation - IUCN Africa Protected Areas Congress (APAC) Registration';
    $_Message_ = self::emailSectionHeaderLayout() . "
                 <tr>
                    <td class='innerpadding borderbottom'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                          <td class='h2' style='font-family: calibri; padding: 15px 0px 30px 0px; '>Dear <b>$firstname</b>,</td>
                        </tr>
                        <tr>
                        <tr>
                          <tr><td class='h2' style='font-family: calibri;'>We have received your request to pay by bank transfer.<br><a href='$payment_invoice_link'>Click here</a> to download the payment details & invoice. </td></tr>
                          <tr><td class='h2' style='font-family: calibri;'>Please ensure that you follow the instructions to ensure your payment is tracked and credited to your registration.</td></tr>
                          <tr><td class='h2' style='font-family: calibri;'>You will be notified when we receive your payment.</td></tr>
                          <tr><td class='h2' style='font-family: calibri;padding:0;color:#37af47;text-transform:uppercase'><b>Important information for international delegates </b> </td></tr>

                          <tr>
                            <td class='h2' style='font-family: calibri;'>
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
    $_Subject_ = 'Payment confirmation - IUCN Africa Protected Areas Congress (APAC) Registration';
    $_Message_ = self::emailSectionHeaderLayout() . "
                 <tr>
                    <td class='innerpadding borderbottom'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                          <td class='h2' style='font-family: calibri; padding: 15px 0px 30px 0px; '>Dear <b>$firstname</b>,</td>
                        </tr>
                        <tr>
                        <tr>
                          <tr><td class='h2' style='font-family: calibri;'>Thank you for registering to attend the <b>IUCN Africa Protected Areas Congress (APAC)</b>  that will be held in Kigali from 18 - 23 July 2022. </tr>
                          <tr><td class='h2' style='font-family: calibri;'>We have received your payment by bank transfer.<br>Your receipt is attached to this email. </td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri;'>
                                <ul>
                                  <li><a href='$payment_receipt_link' target='_blank'>Click here</a> to download your receipt.  </li>
                                </ul>
                              </td>
                            </tr>
                          </tr>
                          <tr><td class='h2' style='font-family: calibri;padding:0;'><b>Invitation To The Africa Protected Areas Congress</b> </td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri;'>
                              <ul>
                                <li><a href='$invitation_letter_link' target='_blank'>Click here</a> to download your invitation letter   </li>
                              </ul>
                            </td>
                          </tr>

                          <tr><td class='h2' style='font-family: calibri;padding: 15px 0;'><b>Badge collection</b> </td></tr>
                          <tr><td class='h2' style='font-family: calibri;'>You will receive information on how and when to collect your badge before the event. We kindly require that you bring the identification document you used in your registration process when collecting your badge. </td></tr>
                          <tr><td class='h2' style='font-family: calibri;padding:0;'><b>Programme </b> </td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri;'>
                                <ul>
                                  <li>You can view the congress programme at <a href='https://apacongress.africa/programme/'>apacongress.africa/programme</a></li>
                                </ul>
                            </td>
                          </tr>
                          <tr><td class='h2' style='font-family: calibri;padding:0; color:#37af47;text-transform:uppercase'><b>Important information for international delegates </b> </td></tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 5px 0;'><b>Accommodation</b> </td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri; padding: 5px 0;'>
                            A quota of rooms has been reserved for the IUCN APAC 2022 participants in hotels in the city centre and close to the conference venue. 
                            The reservations will be made on “first come, first served” basis. The hotel quotas are valid until one months before the conference. 
                            After that, the COC cannot guarantee the availability of the hotel rooms but will assist you in finding accommodation. The hotel fees 
                            include breakfast, service and VAT. All hotel fees are payable directly to the hotels. <br> 
                              <ul style='margin: 5px 0;'>
                                <li><a href='https://www.travelzuri.com/B2C/Admin/GTC/EventInfoCart.aspx?Ref_Type=HTL&CID=87&CityCode=KGL&EventName=Africa%20Protected%20Area%20Congress%20&SSr=EVTHL#' target='_blank'>Click here</a> to book your accommodation for your stay in Kigali   </li>
                              </ul>
                              
                            </td>
                          </tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 5px 0;'><b>Visa information:</b> </td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri;'>
                                <ul>
                                  <li>Visas are issued on arrival for all countries </li>
                                  <li>Visa fees for citizens of African Union, Commonwealth and La Francophonie member states are waived for a visit of up to 30 days</li>
                                  <li>Citizens of East African Community Member States (Burundi, Kenya, Uganda, United Republic of Tanzania and South Sudan) shall be issued a pass/entry visa free of charge upon arrival to stay for the period of six months.  </li>
                                  <li>Citizens of all countries that are not visa exempt, have the option to submit an online visa application. Payment can be done online or upon arrival. </li>
                                  <li><a href='https://www.migration.gov.rw/anounce/anounce/online-visa/' target='_blank'>Click here</a> for more information on Rwanda’s open visa policy  </li>
                                </ul>
                            </td>
                          </tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 5px 0;'><b>Destination information</b> </td></tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 5px 0;'>Rwanda’s stunning scenery and warm, friendly people offer unique experiences in one of the most remarkable countries in the world. It is blessed with extraordinary biodiversity, with incredible wildlife living throughout its volcanoes, montane rainforest and sweeping plains.</td></tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 15px 0;'>Go to <a href='https://www.visitrwanda.com/' target='_blank'>www.visitrwanda.com</a> for more information on activities and excursions you can experience during your time in Rwanda.</td></tr>



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
    $_Subject_ = 'Payment error - IUCN Africa Protected Areas Congress (APAC) Registration';
    $_Message_ = self::emailSectionHeaderLayout() . "
                  <tr>
                    <td class='innerpadding borderbottom'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                          <td class='h2' style='font-family: calibri; padding: 15px 0px 30px 0px; '>Dear <b>$firstname</b>,</td>
                        </tr>
                        
                        <tr><td class='h2' style='font-family: calibri;padding:0;'>It appears that your card payment has not gone through. Kindly follow this link to go through the payment process again <a href='$payment_link'> $payment_link </a></td></tr>
                        <tr><td class='h2' style='font-family: calibri;padding:0;'>If you are still experiencing challenges, please email <a href='mailto:ianangwe@awf.org'>IAnangwe@awf.org</a> with your name & telephone number and our team will get back to you to assist.</td></tr>
                      </table>
                    </td>
                  </tr>

    " . self::emailLayoutSectionFooter2();

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
    $_Subject_ = 'Payment query - IUCN Africa Protected Areas Congress (APAC) Registration';
    $_Message_ = self::emailSectionHeaderLayout() . "
                    <tr>
                      <td class='innerpadding borderbottom'>
                        <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                          <tr>
                          <td class='h2' style='font-family: calibri; padding: 15px 0px 30px 0px; '>Dear <b>$firstname</b>,</td>
                          </tr>
                          
                          <tr><td class='h2' style='font-family: calibri;padding:0;'>It appears that your bank transfer payment has not been received to date. 
                            Please send us the proof of transfer to help us trace it if you have already made the payment. 
                            If you have not made the transfer yet, please let us know when you intend to so we may trace the payment & confirmation your attendance to the congress. <br>
                            Please contact us using the details below if you have any queries on your payment process. 
                          </td></tr>
                        
                        </table>
                      </td>
                    </tr>

      " . self::emailLayoutSectionFooter2();

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
    $_Subject_ = 'Confirmation - IUCN Africa Protected Areas Congress (APAC) Registration';
    $_Message_ = self::emailSectionHeaderLayout() . "
                  <tr>
                    <td class='innerpadding borderbottom'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                        <td class='h2' style='font-family: calibri; padding: 15px 0px 30px 0px; '>Dear <b>$firstname</b>,</td>
                        </tr>
                        <tr>
                        <tr>
                          <tr><td class='h2' style='font-family: calibri;'>Thank you for registering to attend the <b>IUCN Africa Protected Areas Congress (APAC)</b>  that will be held in Kigali, Rwanda, from 18 - 23 July 2022.  </tr>
            
                          <tr><td class='h2' style='font-family: calibri;padding: 15px 0;'><b>Badge collection</b> </td></tr>
                          <tr><td class='h2' style='font-family: calibri;'>You will receive information on how and when to collect your badge before the event. We kindly require that you bring the identification document you used in your registration process when collecting your badge. </td></tr>
                          <tr><td class='h2' style='font-family: calibri;padding:0;'><b>Programme </b> </td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri;'>
                                <ul>
                                  <li>You can view the congress programme at <a href='https://apacongress.africa/programme/'>apacongress.africa/programme</a></li>
                                </ul>
                            </td>
                          </tr>
                          <tr><td class='h2' style='font-family: calibri;padding:0; color:#37af47;text-transform:uppercase'><b>Important information for international delegates </b> </td></tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 5px 0;'><b>Accommodation</b> </td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri; padding: 5px 0;'>
                            A quota of rooms has been reserved for the IUCN APAC 2022 participants in hotels in the city centre and close to the conference venue. 
                            The reservations will be made on “first come, first served” basis. The hotel quotas are valid until one months before the conference. 
                            After that, the COC cannot guarantee the availability of the hotel rooms but will assist you in finding accommodation. The hotel fees 
                            include breakfast, service and VAT. All hotel fees are payable directly to the hotels. <br> 
                              <ul style='margin: 5px 0;'>
                                <li><a href='https://www.travelzuri.com/B2C/Admin/GTC/EventInfoCart.aspx?Ref_Type=HTL&CID=87&CityCode=KGL&EventName=Africa%20Protected%20Area%20Congress%20&SSr=EVTHL#' target='_blank'>Click here</a> to book your accommodation for your stay in Kigali   </li>
                              </ul>
                              
                            </td>
                          </tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 5px 0;'><b>Visa information:</b> </td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri;'>
                                <ul>
                                  <li>Visas are issued on arrival for all countries </li>
                                  <li>Visa fees for citizens of African Union, Commonwealth and La Francophonie member states are waived for a visit of up to 30 days</li>
                                  <li>Citizens of East African Community Member States (Burundi, Kenya, Uganda, United Republic of Tanzania and South Sudan) shall be issued a pass/entry visa free of charge upon arrival to stay for the period of six months.  </li>
                                  <li>Citizens of all countries that are not visa exempt, have the option to submit an online visa application. Payment can be done online or upon arrival. </li>
                                  <li><a href='https://www.migration.gov.rw/anounce/anounce/online-visa/' target='_blank'>Click here</a> for more information on Rwanda’s open visa policy  </li>
                                </ul>
                            </td>
                          </tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 5px 0;'><b>Destination information</b> </td></tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 5px 0;'>Rwanda’s stunning scenery and warm, friendly people offer unique experiences in one of the most remarkable countries in the world. It is blessed with extraordinary biodiversity, with incredible wildlife living throughout its volcanoes, montane rainforest and sweeping plains.</td></tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 15px 0;'>Go to <a href='https://www.visitrwanda.com/' target='_blank'>www.visitrwanda.com</a> for more information on activities and excursions you can experience during your time in Rwanda.</td></tr>



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

    $_Email_ = $email;
    $_Subject_ = 'Confirmation - IUCN Africa Protected Areas Congress (APAC) Registration';
    $_Message_ = self::emailSectionHeaderLayout() . "
                  <tr>
                    <td class='innerpadding borderbottom'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                        <td class='h2' style='font-family: calibri; padding: 15px 0px 30px 0px; '>Dear <b>$firstname</b>,</td>
                        </tr>
                        <tr>
                        <tr>
                          <tr><td class='h2' style='font-family: calibri;'>Thank you for registering to attend the <b>IUCN Africa Protected Areas Congress (APAC)</b>  that will be held in Kigali, Rwanda, from 18 - 23 July 2022.  
                        </tr>
                        <tr><td class='h2' style='font-family: calibri;'>We have approved your registration. </td></tr>
                         
                          <tr><td class='h2' style='font-family: calibri;padding:0;'><b>Invitation To The Africa Protected Areas Congress</b> </td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri;'>
                              <ul>
                                <li><a href='$invitation_letter_link' target='_blank'>Click here</a> to download your invitation letter   </li>
                              </ul>
                            </td>
                          </tr>
                         
                          <tr><td class='h2' style='font-family: calibri;padding: 15px 0;'><b>Badge collection</b> </td></tr>
                          <tr><td class='h2' style='font-family: calibri;'>You will receive information on how and when to collect your badge before the event. We kindly require that you bring the identification document you used in your registration process when collecting your badge. </td></tr>
                          <tr><td class='h2' style='font-family: calibri;padding:0;'><b>Programme </b> </td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri;'>
                                <ul>
                                  <li>You can view the congress programme at <a href='https://apacongress.africa/programme/'>apacongress.africa/programme</a></li>
                                </ul>
                            </td>
                          </tr>
                          <tr><td class='h2' style='font-family: calibri;padding:0; color:#37af47;text-transform:uppercase'><b>Important information for international delegates </b> </td></tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 5px 0;'><b>Accommodation</b> </td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri; padding: 5px 0;'>
                            A quota of rooms has been reserved for the IUCN APAC 2022 participants in hotels in the city centre and close to the conference venue. 
                            The reservations will be made on “first come, first served” basis. The hotel quotas are valid until one months before the conference. 
                            After that, the COC cannot guarantee the availability of the hotel rooms but will assist you in finding accommodation. The hotel fees 
                            include breakfast, service and VAT. All hotel fees are payable directly to the hotels. <br> 
                              <ul style='margin: 5px 0;'>
                                <li><a href='https://www.travelzuri.com/B2C/Admin/GTC/EventInfoCart.aspx?Ref_Type=HTL&CID=87&CityCode=KGL&EventName=Africa%20Protected%20Area%20Congress%20&SSr=EVTHL#' target='_blank'>Click here</a> to book your accommodation for your stay in Kigali   </li>
                              </ul>
                              
                            </td>
                          </tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 5px 0;'><b>Visa information:</b> </td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri;'>
                                <ul>
                                  <li>Visas are issued on arrival for all countries </li>
                                  <li>Visa fees for citizens of African Union, Commonwealth and La Francophonie member states are waived for a visit of up to 30 days</li>
                                  <li>Citizens of East African Community Member States (Burundi, Kenya, Uganda, United Republic of Tanzania and South Sudan) shall be issued a pass/entry visa free of charge upon arrival to stay for the period of six months.  </li>
                                  <li>Citizens of all countries that are not visa exempt, have the option to submit an online visa application. Payment can be done online or upon arrival. </li>
                                  <li><a href='https://www.migration.gov.rw/anounce/anounce/online-visa/' target='_blank'>Click here</a> for more information on Rwanda’s open visa policy  </li>
                                </ul>
                            </td>
                          </tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 5px 0;'><b>Destination information</b> </td></tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 5px 0;'>Rwanda’s stunning scenery and warm, friendly people offer unique experiences in one of the most remarkable countries in the world. It is blessed with extraordinary biodiversity, with incredible wildlife living throughout its volcanoes, montane rainforest and sweeping plains.</td></tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 15px 0;'>Go to <a href='https://www.visitrwanda.com/' target='_blank'>www.visitrwanda.com</a> for more information on activities and excursions you can experience during your time in Rwanda.</td></tr>



                        </tr>
                      </table>
                    </td>
                  </tr>

      " . self::emailLayoutSectionFooter();

    // Email::send($_Email_, $_Subject_, $_Message_);
      $User = new User();
    $User->send_mail($_Email_, $_Message_, $_Subject_);
  }



  public static function sendEmailToParticipantAfterApplicationDecline($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $invitation_letter_link = $_data_->invitation_letter_link;
    $approval_status = $_data_->approval_status;

    $_Email_ = $email;
    $_Subject_ = 'Application to Register as a Local CBO to the - IUCN Africa Protected Areas Congress (APAC)';
    $_Message_ = self::emailSectionHeaderLayout() . "
                  <tr>
                    <td class='innerpadding borderbottom'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                        <td class='h2' style='font-family: calibri; padding: 15px 0px 30px 0px; '>Dear <b>$firstname</b>,</td>
                        </tr>
                        <tr>
                        <tr>
                          <tr><td class='h2' style='font-family: calibri;'>Thank you for registering your interest to attend the <b>IUCN Africa Protected Areas Congress (APAC)</b> that will be held in Kigali from 18 – 23 July as a Local CBO. We have reviewed your application and unfortunately, are not in a position to grant your request as you do not qualify as a local CBO. Please note that to qualify for this category, one must be a member of a Rwanda-based community organization or group.
  
                        </tr>
                        <tr><td class='h2' style='font-family: calibri;'>We encourage you to apply for participation under any of the other categories available as fits you.</td></tr>
                        
                        <tr><td class='h2' style='font-family: calibri;padding:0; color:#37af47;text-transform:uppercase'><b>Important information for international delegates </b> </td></tr>

                          <tr><td class='h2' style='font-family: calibri;padding: 15px 0;'><b>Badge collection</b> </td></tr>
                          <tr><td class='h2' style='font-family: calibri;'>You will receive information on how and when to collect your badge before the event. We kindly require that you bring the identification document you used in your registration process when collecting your badge. </td></tr>
                          <tr><td class='h2' style='font-family: calibri;padding:0;'><b>Programme </b> </td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri;'>
                                <ul>
                                  <li>You can view the congress programme at <a href='https://apacongress.africa/programme/'>apacongress.africa/programme</a></li>
                                </ul>
                            </td>
                          </tr>
                          
                          <tr><td class='h2' style='font-family: calibri;padding: 5px 0;'><b>Accommodation</b> </td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri; padding: 5px 0;'>
                            A quota of rooms has been reserved for the IUCN APAC 2022 participants in hotels in the city centre and close to the conference venue. 
                            The reservations will be made on “first come, first served” basis. The hotel quotas are valid until one months before the conference. 
                            After that, the COC cannot guarantee the availability of the hotel rooms but will assist you in finding accommodation. The hotel fees 
                            include breakfast, service and VAT. All hotel fees are payable directly to the hotels. <br> 
                              <ul style='margin: 5px 0;'>
                                <li><a href='https://www.travelzuri.com/B2C/Admin/GTC/EventInfoCart.aspx?Ref_Type=HTL&CID=87&CityCode=KGL&EventName=Africa%20Protected%20Area%20Congress%20&SSr=EVTHL#' target='_blank'>Click here</a> to book your accommodation for your stay in Kigali   </li>
                              </ul>
                              
                            </td>
                          </tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 5px 0;'><b>Visa information:</b> </td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri;'>
                                <ul>
                                  <li>Visas are issued on arrival for all countries </li>
                                  <li>Visa fees for citizens of African Union, Commonwealth and La Francophonie member states are waived for a visit of up to 30 days</li>
                                  <li>Citizens of East African Community Member States (Burundi, Kenya, Uganda, United Republic of Tanzania and South Sudan) shall be issued a pass/entry visa free of charge upon arrival to stay for the period of six months.  </li>
                                  <li>Citizens of all countries that are not visa exempt, have the option to submit an online visa application. Payment can be done online or upon arrival. </li>
                                  <li><a href='https://www.migration.gov.rw/anounce/anounce/online-visa/' target='_blank'>Click here</a> for more information on Rwanda’s open visa policy  </li>
                                </ul>
                            </td>
                          </tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 5px 0;'><b>Destination information</b> </td></tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 5px 0;'>Rwanda’s stunning scenery and warm, friendly people offer unique experiences in one of the most remarkable countries in the world. It is blessed with extraordinary biodiversity, with incredible wildlife living throughout its volcanoes, montane rainforest and sweeping plains.</td></tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 15px 0;'>Go to <a href='https://www.visitrwanda.com/' target='_blank'>www.visitrwanda.com</a> for more information on activities and excursions you can experience during your time in Rwanda.</td></tr>



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
    $_Subject_ = 'Refund of IUCN APAC Registration Fees';
    $_Message_ = self::emailSectionHeaderLayout() . "
                  <tr>
                    <td class='innerpadding borderbottom'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                        <td class='h2' style='font-family: calibri; padding: 15px 0px 30px 0px; '>Dear <b>$firstname</b>,</td>
                        </tr>
                        <tr>
                          <tr>
                            <td class='h2' style='font-family: calibri;'>We are glad to inform you that your request for refund of registration fees has been successfully processed in line with the <a href='https://apacongress.africa/download/cancellation-and-refund-policy/' target='_blank'>IUCN APAC Cancellation And Refund Policy</a>. Depending on your bank, you should receive the same within the next three working days.<br><br>

                            We thank you for your interest in <a href='https://apacongress.africa/' target='_blank'>IUCN APAC</a> and hope to continue engaging with you as we work to position conservation at the heart of Africa’s sustainable development agenda.
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>

      " . self::emailLayoutSectionFooterRefund();

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
    $_Subject_ = 'Information Sharing Consent Form - IUCN Africa Protected Areas Congress (APAC)';
    $_Message_ = self::emailSectionHeaderLayout() . "
                  <tr>
                    <td class='innerpadding borderbottom'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr><td class='h2' style='font-family: calibri;padding: 15px 0; text-align: center; text-transform: uppercase;'><b>Information Sharing Consent Form </b> </td></tr>
                        <tr><td class='h2' style='font-family: calibri;padding:0;'>I $firstname of $organisation_name hereby give my permission for IUCN to share my personal information with other delegates and IUCN APAC Sponsors for the purpose of networking and advancing the conversations at the congress and after. I understand that (the host organisations) may hold information gathered about me from the various agencies and as such my rights under the Data Protection Act will not be affected.
                        </td></tr>

                          <tr><td class='h2' style='font-family: calibri;padding: 5px 0;'><b>Statement of Consent:</b> </td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri;'>
                                <ul>
                                  <li>I understand that personal information is held about me.</li>
                                  <li>I have had the opportunity to consider the implications of sharing or not sharing information about me.</li>
                                  <li><b>I agree that personal information about me gathered while registering for the IUCN APAC Congress may be shared:</b></li>
                                  <ul>
                                    <li>IUCN APAC Sponsors and Donors</li>
                                    <li>Fellow delegates</li>
                                  </ul>
                                </ul>
                            </td>
                          </tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 15px 0;'><a href='$userLink' target='_blank'><input type='checkbox'> I agree to my information being shared with fellow delegates, donors and IUCN APAC Sponsors</a></td></tr>
                        
                      </table>
                    </td>
                  </tr>

                  <tr>
                    <td class='footer' bgcolor='#fff'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        
                        <tr>
                          <td>
                            <table border='0' cellspacing='0' cellpadding='0'>
                              <td class='h2' style='font-family: calibri;padding:0; padding: 15px 0; color:#37af47;text-transform:uppercase;'><b>Stay connected</b></td>
                            </table>
                            <span class='alignment'></span>
                            <table border='0' cellspacing='0' cellpadding='0'>
                              <tr><td class='h2' style='font-family: calibri; padding:0;'><b>Facebook:</b> <a href='https://www.facebook.com/APACongress' target='_blank'>@APACongress</a></td></tr>
                              <tr><td class='h2' style='font-family: calibri; padding:0;'><b>Twitter:</b> <a href='https://twitter.com/APA_Congress?s=20' target='_blank'>@APA_Congress</a></td></tr>
                              <tr><td class='h2' style='font-family: calibri;padding:0;'><b>Connect with our official tag: </b><a href='#'>#APAC2022 </a></td></tr>
                              
                              <tr><td class='h2' style='font-family: calibri;padding:15px 0;'>For more information, please refer to the IUCN APAC delegate registration information, <a href='https://apacongress.torusguru.com/terms' target='_blank'>general terms and conditions</a>. We look forward to meeting you in Kigali, Rwanda.  </td></tr>
                              <tr><td class='h2' style='font-family: calibri;padding:0;'><b>Best Wishes,</b> </td></tr>
                              <tr><td class='h2' style='font-family: calibri;'> IUCN APAC Secretariat </td></tr>
                            </table>
                          </td>
                        </tr>
                        <tr>
                              <td>
                                <table border='0' cellspacing='0' cellpadding='0'>
                                  <tr><td class='h2' style='font-family: calibri;padding: 0px 0 0 0;'><b>Disclaimer</b> </td></tr>
                                  <tr><td class='h2' style='font-family: calibri; text-align: justify; font-size: 13px;'> The information contained in or accompanying this email and any attachment thereto, is intended solely for the use of the stated recipient(s) and may contain information that is confidential and/or privileged. Any dissemination, distribution or copying of this email and any attachment by anyone who is not a stated recipient is strictly prohibited. If you receive this message in error, please notify the sender immediately and delete the message and any attachment from your system without retaining a copy. Thank you for your cooperation and understanding. </td></tr>
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
    $_Subject_ = 'Confirmation - IUCN Africa Protected Areas Congress (APAC) Registrations';
    $_Message_ = self::emailSectionHeaderLayout() . "
                  <tr>
                    <td class='innerpadding borderbottom'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                        <td class='h2' style='font-family: calibri; padding: 15px 0px 30px 0px; '>Dear <b>$firstname</b>,</td>
                        </tr>
                        
                        <tr><td class='h2' style='font-family: calibri;padding:0;'>Thank you for applying for media accreditation to attend the IUCN Africa Protected Areas Congress (APAC) that will be held in Kigali, Rwanda, from 18 - 23 July 2022. 
                        </td></tr>

                        <tr><td class='h2' style='font-family: calibri;padding:0;'>The APAC Event Secretariat will review your application and revert within the next 5 working days. 
                        </td></tr>

                        <tr><td class='h2' style='font-family: calibri;padding:0;'><b>International media</b> </td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri;'>
                                <ul>
                                  <li>Please do not book travel or accommodation until you have received confirmation to attend as accredited media. </li>
                                </ul>
                            </td>
                          </tr>
                       
                      </table>
                    </td>
                  </tr>

    " . self::emailLayoutSectionFooter2();

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
    $_Subject_ = 'Application to Register as a Media to the - IUCN Africa Protected Areas Congress (APAC)';
    $_Message_ = self::emailSectionHeaderLayout() . "
                  <tr>
                    <td class='innerpadding borderbottom'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                          <td class='h2' style='font-family: calibri; padding: 15px 0px 30px 0px; '>Dear <b>$firstname</b>,</td>
                        </tr>
                        <tr><td class='h2' style='font-family: calibri;padding:0;'>Thank you for registering your interest to attend <b>the IUCN Africa Protected Areas Congress (APAC)</b> that will be held in Kigali from 18 – 23rd July as a Media. We have reviewed your application and unfortunately, your application does not qualify for media accreditation. We encourage you to apply for participation under any of the other categories available as fits you. Alternatively, please reach out to the registration team at <a href='mailto:Registration@apacongress.africa'>Registration@apacongress.africa</a> with your request to change your participation type. <br><br>
                        Please note that media accreditation is strictly reserved for members of the press (print, photo, radio, television, film, news agencies and online media) who represent a bona fide media organization.<br><br>
                        Accreditation is only be given on proof of a track record of reporting for media organizations on local or international affairs. Media accreditation is not accorded to the information outlets or advocacy publications of non-governmental organizations. The IUCN APAC PR and Communications team may require supplementary documentation to evaluate the request. <br><br>
                        Applications are considered on a case-by-case basis and the decisions of the IUCN APAC PR and Communications team are final. The IUCN APAC PR and Communications team reserves the right to deny or withdraw accreditation of journalists from media organizations whose activities run counter to the objectives of IUCN APAC, or who abuse the privileges so extended or put the accreditation to improper use or act in a way not consistent with the principles of the Congress.
                        </td></tr>

                        <tr><td class='h2' style='font-family: calibri;padding:0; color:#37af47;text-transform:uppercase'><b><br><br>Important Information for Delegates: </b> </td></tr>

                        <tr>
                          <td class='h2' style='font-family: calibri;padding: 15px 0;'><b>Badge collection</b> </td></tr>
                          <tr><td class='h2' style='font-family: calibri;'>Badges will be ready for collection from 16th July 2022. You will receive information about the pick-up location by 15th July 2022. Please carry the identification document you used during registration when collecting your badge.</td></tr>

                          <tr><td class='h2' style='font-family: calibri;padding:0;'><b>Social Media Toolkit </b> </td></tr>
                          <tr><td class='h2' style='font-family: calibri;'>We have updated your profile with the IUCN APAC Social Media Toolkit. Please feel free to share and use widely. The media tool kit is also available on our website <a href='https://apacongress.africa/apacongresstool-kit/' target='_blank'>here.</a></td></tr>

                          <tr><td class='h2' style='font-family: calibri;padding:0;'><b>Programme </b> </td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri;'>
                                <ul>
                                  <li>You can view the Congress Agenda and Daily Calendar of Events <a href='https://apacongress.africa/programme/'>here.</a></li>
                                </ul>
                            </td>
                          </tr>

                          <tr><td class='h2' style='font-family: calibri;padding:0;'><b>Covid-19 Update</b> </td></tr>
                          <tr><td class='h2' style='font-family: calibri;'>The Government of Rwanda and the IUCN Co-Conveners continue to closely monitor the COVID-19 situation and will implement the necessary measures to ensure maximum safety for participants, in compliance with applicable regulations. To stay updated on Covid-19 guidelines in Rwanda, please visit the Rwanda Biometrical Center website <a href='https://www.rbc.gov.rw/index.php?id=188' target='_blank'>here.</a></td></tr>

                          <tr><td class='h2' style='font-family: calibri;padding: 5px 0;'><b>Accommodation</b> </td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri; padding: 5px 0;'>
                            A quota of rooms has been reserved for the IUCN APAC 2022 participants in hotels in the city Centre and close to the conference venue. The reservations will be made on a “first-come, first-served” basis. The hotel quotas are valid until one months before the conference. After that, the COC cannot guarantee the availability of the hotel rooms but will assist you in finding accommodation. The hotel fees include breakfast, service, and VAT. All hotel fees are payable directly to the hotels.<br> 
                              <ul style='margin: 5px 0;'>
                                <li><a href='https://www.travelzuri.com/B2C/Admin/GTC/EventInfoCart.aspx?Ref_Type=HTL&CID=87&CityCode=KGL&EventName=Africa%20Protected%20Area%20Congress%20&SSr=EVTHL#' target='_blank'>Click here</a> to book your accommodation and travel during your stay in Kigali</li>
                              </ul>
                              
                            </td>
                          </tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 5px 0;'><b>Visa information:</b> </td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri;'>
                                <ul>
                                  <li>Visas are issued on arrival for all countries </li>
                                  <li>Visa fees for citizens of African Union, Commonwealth and La Francophonie member states are waived for a visit of up to 30 days</li>
                                  <li>Citizens of East African Community Member States (Burundi, Kenya, Uganda, United Republic of Tanzania and South Sudan) shall be issued a pass/entry visa free of charge upon arrival to stay for the period of six months.  </li>
                                  <li>Citizens of all countries that are not visa exempt, have the option to submit an online visa application. Payment can be done online or upon arrival. </li>
                                  <li><a href='https://www.migration.gov.rw/anounce/anounce/online-visa/' target='_blank'>Click here</a> for more information on Rwanda’s open visa policy  </li>
                                </ul>
                            </td>
                          </tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 5px 0;'><b>Destination information</b> </td></tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 5px 0;'>Rwanda’s stunning scenery and warm, friendly people offer unique experiences in one of the most remarkable countries in the world. It is blessed with extraordinary biodiversity, with incredible wildlife living throughout its volcanoes, montane rainforest and sweeping plains.</td></tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 15px 0;'>Go to <a href='https://www.visitrwanda.com/' target='_blank'>www.visitrwanda.com</a> for more information on activities and excursions you can experience during your time in Rwanda.</td></tr>
                        
                      </table>
                    </td>
                  </tr>

                  <tr>
                    <td class='footer' bgcolor='#fff'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        
                        <tr>
                          <td>
                            <table border='0' cellspacing='0' cellpadding='0'>
                              <td class='h2' style='font-family: calibri;padding:0; padding: 15px 0; color:#37af47;text-transform:uppercase;'><b>Stay connected</b></td>
                            </table>
                            <span class='alignment'></span>
                            <table border='0' cellspacing='0' cellpadding='0'>
                              <tr><td class='h2' style='font-family: calibri; padding:0;'><b>Facebook:</b> <a href='https://www.facebook.com/APACongress' target='_blank'>@APACongress</a></td></tr>
                              <tr><td class='h2' style='font-family: calibri; padding:0;'><b>Twitter:</b> <a href='https://twitter.com/APA_Congress?s=20' target='_blank'>@APA_Congress</a></td></tr>
                              <tr><td class='h2' style='font-family: calibri;padding:0;'><b>Connect with our official tag: </b><a href='#'>#APAC2022 </a></td></tr>
                              
                              <tr><td class='h2' style='font-family: calibri;padding:15px 0;'>For more information, please refer to the IUCN APAC delegate registration information, <a href='https://apacongress.torusguru.com/terms' target='_blank'>general terms and conditions</a>. We look forward to meeting you in Kigali, Rwanda.  </td></tr>
                              <tr><td class='h2' style='font-family: calibri;padding:0;'><b>Best Wishes,</b> </td></tr>
                              <tr><td class='h2' style='font-family: calibri;'> IUCN APAC Secretariat </td></tr>
                            </table>
                          </td>
                        </tr>
                        <tr>
                              <td>
                                <table border='0' cellspacing='0' cellpadding='0'>
                                  <tr><td class='h2' style='font-family: calibri;padding: 0px 0 0 0;'><b>Disclaimer</b> </td></tr>
                                  <tr><td class='h2' style='font-family: calibri; text-align: justify; font-size: 13px;'> The information contained in or accompanying this email and any attachment thereto, is intended solely for the use of the stated recipient(s) and may contain information that is confidential and/or privileged. Any dissemination, distribution or copying of this email and any attachment by anyone who is not a stated recipient is strictly prohibited. If you receive this message in error, please notify the sender immediately and delete the message and any attachment from your system without retaining a copy. Thank you for your cooperation and understanding. </td></tr>
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


  /** - 10 - Send Email - Participant - Email received after media application -  */
  public static function sendEmailToParticipantWhenBankTransferNotAppearedInIUCNAccount7DaysAfterRegistration($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;

    $_Email_ = $email;
    $_Subject_ = 'Payment of IUCN APAC Registration Fees';
    $_Message_ = self::emailSectionHeaderLayout() . "
                  <tr>
                    <td class='innerpadding borderbottom'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                          <td class='h2' style='font-family: calibri; padding: 15px 0px 30px 0px; '>Dear <b>$firstname</b>,</td>
                        </tr>
                        <tr>
                          <td class='bodycopy' style='font-family: calibri;'>
                         Thank you for your interest in  attending the inaugural <a href='https://apacongress.africa/' target='_blank'>IUCN Africa Protected Areas Congress</a> scheduled take place in Kigali, Rwanda from <b>July 18th to 23rd 2022</b> at <a href='https://www.kcc.rw/' target='_blank'>Kigali Convention Center</a>. This is a polite reminder that bank payments will be open until 10th July 2022 after which payments must be done by card or mobile money. If you have made payment and your registration is yet to be approved, please contact us at <a href='mailto:Registration@apacongress.africa'>Registration@apacongress.africa</a> and we shall assist you accordingly.<br><br>

                         We look forward to engaging with you as we work towards shaping Africa’s agenda for protected and conserved areas to deliver benefits for people and nature.
                          </td>
                        </tr>

                        <tr><td class='h2' style='font-family: calibri;padding:0; color:#37af47;text-transform:uppercase'><b><br><br>Important Information for Delegates: </b> </td></tr>

                        <tr>
                          <td class='h2' style='font-family: calibri;padding: 15px 0;'><b>Badge collection</b> </td></tr>
                          <tr><td class='h2' style='font-family: calibri;'>Badges will be ready for collection from 16th July 2022. You will receive information about the pick-up location by 15th July 2022. Please carry the identification document you used during registration when collecting your badge.</td></tr>

                          <tr><td class='h2' style='font-family: calibri;padding:0;'><b>Social Media Toolkit </b> </td></tr>
                          <tr><td class='h2' style='font-family: calibri;'>We have updated your profile with the IUCN APAC Social Media Toolkit. Please feel free to share and use widely. The media tool kit is also available on our website <a href='https://apacongress.africa/apacongresstool-kit/' target='_blank'>here.</a></td></tr>

                          <tr><td class='h2' style='font-family: calibri;padding:0;'><b>Programme </b> </td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri;'>
                                <ul>
                                  <li>You can view the Congress Agenda and Daily Calendar of Events <a href='https://apacongress.africa/programme/'>here.</a></li>
                                </ul>
                            </td>
                          </tr>

                          <tr><td class='h2' style='font-family: calibri;padding:0;'><b>Covid-19 Update</b> </td></tr>
                          <tr><td class='h2' style='font-family: calibri;'>The Government of Rwanda and the IUCN Co-Conveners continue to closely monitor the COVID-19 situation and will implement the necessary measures to ensure maximum safety for participants, in compliance with applicable regulations. To stay updated on Covid-19 guidelines in Rwanda, please visit the Rwanda Biometrical Center website <a href='https://www.rbc.gov.rw/index.php?id=188' target='_blank'>here.</a></td></tr>

                          <tr><td class='h2' style='font-family: calibri;padding: 5px 0;'><b>Accommodation</b> </td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri; padding: 5px 0;'>
                            A quota of rooms has been reserved for the IUCN APAC 2022 participants in hotels in the city Centre and close to the conference venue. The reservations will be made on a “first-come, first-served” basis. The hotel quotas are valid until one months before the conference. After that, the COC cannot guarantee the availability of the hotel rooms but will assist you in finding accommodation. The hotel fees include breakfast, service, and VAT. All hotel fees are payable directly to the hotels.<br> 
                              <ul style='margin: 5px 0;'>
                                <li><a href='https://www.travelzuri.com/B2C/Admin/GTC/EventInfoCart.aspx?Ref_Type=HTL&CID=87&CityCode=KGL&EventName=Africa%20Protected%20Area%20Congress%20&SSr=EVTHL#' target='_blank'>Click here</a> to book your accommodation and travel during your stay in Kigali</li>
                              </ul>
                              
                            </td>
                          </tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 5px 0;'><b>Visa information:</b> </td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri;'>
                                <ul>
                                  <li>Visas are issued on arrival for all countries </li>
                                  <li>Visa fees for citizens of African Union, Commonwealth and La Francophonie member states are waived for a visit of up to 30 days</li>
                                  <li>Citizens of East African Community Member States (Burundi, Kenya, Uganda, United Republic of Tanzania and South Sudan) shall be issued a pass/entry visa free of charge upon arrival to stay for the period of six months.  </li>
                                  <li>Citizens of all countries that are not visa exempt, have the option to submit an online visa application. Payment can be done online or upon arrival. </li>
                                  <li><a href='https://www.migration.gov.rw/anounce/anounce/online-visa/' target='_blank'>Click here</a> for more information on Rwanda’s open visa policy  </li>
                                </ul>
                            </td>
                          </tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 5px 0;'><b>Destination information</b> </td></tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 5px 0;'>Rwanda’s stunning scenery and warm, friendly people offer unique experiences in one of the most remarkable countries in the world. It is blessed with extraordinary biodiversity, with incredible wildlife living throughout its volcanoes, montane rainforest and sweeping plains.</td></tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 15px 0;'>Go to <a href='https://www.visitrwanda.com/' target='_blank'>www.visitrwanda.com</a> for more information on activities and excursions you can experience during your time in Rwanda.</td></tr>
                        
                      </table>
                    </td>
                  </tr>

                  <tr>
                    <td class='footer' bgcolor='#fff'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        
                        <tr>
                          <td>
                            <table border='0' cellspacing='0' cellpadding='0'>
                              <td class='h2' style='font-family: calibri;padding:0; padding: 15px 0; color:#37af47;text-transform:uppercase;'><b>Stay connected</b></td>
                            </table>
                            <span class='alignment'></span>
                            <table border='0' cellspacing='0' cellpadding='0'>
                              <tr><td class='h2' style='font-family: calibri; padding:0;'><b>Facebook:</b> <a href='https://www.facebook.com/APACongress' target='_blank'>@APACongress</a></td></tr>
                              <tr><td class='h2' style='font-family: calibri; padding:0;'><b>Twitter:</b> <a href='https://twitter.com/APA_Congress?s=20' target='_blank'>@APA_Congress</a></td></tr>
                              <tr><td class='h2' style='font-family: calibri;padding:0;'><b>Connect with our official tag: </b><a href='#'>#APAC2022 </a></td></tr>
                              
                              <tr><td class='h2' style='font-family: calibri;padding:15px 0;'>For more information, please refer to the IUCN APAC delegate registration information, <a href='https://apacongress.torusguru.com/terms' target='_blank'>general terms and conditions</a>. We look forward to meeting you in Kigali, Rwanda.  </td></tr>
                              <tr><td class='h2' style='font-family: calibri;padding:0;'><b>Best Wishes,</b> </td></tr>
                              <tr><td class='h2' style='font-family: calibri;'> IUCN APAC Secretariat </td></tr>
                            </table>
                          </td>
                        </tr>
                        <tr>
                              <td>
                                <table border='0' cellspacing='0' cellpadding='0'>
                                  <tr><td class='h2' style='font-family: calibri;padding: 0px 0 0 0;'><b>Disclaimer</b> </td></tr>
                                  <tr><td class='h2' style='font-family: calibri; text-align: justify; font-size: 13px;'> The information contained in or accompanying this email and any attachment thereto, is intended solely for the use of the stated recipient(s) and may contain information that is confidential and/or privileged. Any dissemination, distribution or copying of this email and any attachment by anyone who is not a stated recipient is strictly prohibited. If you receive this message in error, please notify the sender immediately and delete the message and any attachment from your system without retaining a copy. Thank you for your cooperation and understanding. </td></tr>
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


  /** - 11 - 	Email to be sent to Group Admin When the APAC Administration accept his Group Registration Request and an email with the registration link sent to that group admin.  -  */
  public static function sendEmailToGroupAdminOnGroupRegistrationRequestAccepted($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;
    $generated_link = $_data_->generated_link;
    $password = $_data_->password;
    $system_link = $_data_->system_link;

    $_Email_ = $email;
    $_Subject_ = 'Confirmation - IUCN Africa Protected Areas Congress (APAC) Group Registration Accepted';
    $_Message_ = self::emailSectionHeaderLayout() . "
                  <tr>
                    <td class='innerpadding borderbottom'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                        <td class='h2' style='font-family: calibri; padding: 15px 0px 30px 0px; '>Dear <b>$firstname</b>,</td>
                        </tr>
                        
                        <tr><td class='h2' style='font-family: calibri;padding:0;'>Thank you for applying for media accreditation to attend the IUCN Africa Protected Areas Congress (APAC) that will be held in Kigali, Rwanda, from 18 - 23 July 2022. 
                        </td></tr>

                        <tr><td class='h2' style='font-family: calibri;padding: 15px 0;'>We are glad to inform you that your request for group registration has been reviewed and accepted.
                        </td></tr>

                        <tr><td class='h2' style='font-family: calibri;padding: 0;'> Please complete your registration and the payment for your group <br>
                        <a href='$generated_link'> Click here</a>  to proceed.
                        </td></tr>

                        <tr><td class='h2' style='font-family: calibri;padding: 5px 0; padding-top: 15px;'><b>Your login credentials are as follows:</b> </td></tr> <br>
                          <tr>
                            <td class='h2' style='font-family: calibri;'>
                                <ul class='list'>
                                  <li>User Name: <strong>$email</strong> </li>
                                  <li>Password: <strong>$password</strong> </li>
                                  <li>System Link: <strong><a href='$system_link'> Group Administration System Portal  </a></strong> </li>
                                </ul>
                            </td>
                          </tr>

                        <tr><td class='h2' style='font-family: calibri;padding: 0; padding-bottom: 15px;'>Should you have any challenges and questions regarding your group registration please do not hesitate to contact the registration support team on <a href='mailto:registration@apacongress.africa'>registration@apacongress.africa</a>.
                        </td></tr>
                      
                      </table>
                    </td>
                  </tr>

    " . self::emailLayoutSectionFooter3();

    // Email::send($_Email_, $_Subject_, $_Message_);
    $User = new User();
    $User->send_mail($_Email_, $_Message_, $_Subject_);

  }


 /** - 13 -	Email to be sent to Group Admin When s/he registers and completes the payment for his group - with the confirmation message and the Apac invitation letter - with credentials to access his group admin account in order to send private link to his group delegates  -  */
 public static function sendEmailToGroupAdminOnGroupRegistrationPaymentCompleted($_data_)
 {
   $_data_ = (object)$_data_;
   $email = $_data_->email;
   $firstname = $_data_->firstname;
   $payment_receipt_link = $_data_->payment_receipt_link;
   $invitation_letter_link = $_data_->invitation_letter_link;

   $_Email_ = $email;
   $_Subject_ = 'Confirmation - IUCN Africa Protected Areas Congress (APAC) 2022 Registration ';
   $_Message_ = self::emailSectionHeaderLayout() . "
                 <tr>
                    <td class='innerpadding borderbottom'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                        <td class='h2' style='font-family: calibri; padding: 15px 0px 30px 0px; '>Dear <b>$firstname</b>,</td>
                        </tr>
                        
                        <tr><td class='h2' style='font-family: calibri;padding:0;'>Thank you for registering your group to attend the IUCN Africa Protected Areas Congress (APAC) that will be held in Kigali from 18 - 23 July 2022. 
                        </td></tr>

                        <tr><td class='h2' style='font-family: calibri;padding: 15px 0;'>Additionally, we are pleased to inform you that your registration and your group registration has been completed and approved.
                        </td></tr>

                        <tr>
                          <td class='h2' style='font-family: calibri;padding:0;'><b>Your receipt is attached to this email</b> </td>
                        </tr>
                          <tr>
                            <td class='h2' style='font-family: calibri;'>
                              <ul>
                                  <li><a href='$payment_receipt_link' target='_blank'>Click here</a> to download your receipt.  </li>
                              </ul>
                            </td>
                        </tr>

                        <tr>
                          <td class='h2' style='font-family: calibri;padding:0;'><b>Invitation To The Africa Protected Areas Congress</b> </td>
                        </tr>
                          <tr>
                            <td class='h2' style='font-family: calibri;'>
                              <ul>
                                <li><a href='$invitation_letter_link' target='_blank'>Click here</a> to download your invitation letter   </li>
                              </ul>
                            </td>
                        </tr>

                        <tr><td class='h2' style='font-family: calibri;padding: 0;'> Sign into your Group Administration System with your valid credentials to send private registration links to your group delegates. <br>
                        

                        <tr><td class='h2' style='font-family: calibri;padding: 15px 0;'><b>Badge collection</b> </td></tr>
                          <tr><td class='h2' style='font-family: calibri;'>You will receive information on how and when to collect your badge before the event. We kindly require that you bring the identification document you used in your registration process when collecting your badge. </td></tr>
                          <tr><td class='h2' style='font-family: calibri;padding:0;'><b>Programme </b> </td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri;'>
                                <ul>
                                  <li>You can view the congress programme at <a href='https://apacongress.africa/programme/'>apacongress.africa/programme</a></li>
                                </ul>
                            </td>
                          </tr>
                          <tr><td class='h2' style='font-family: calibri;padding:0; color:#37af47;text-transform:uppercase'><b>Important information for international delegates </b> </td></tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 5px 0;'><b>Accommodation</b> </td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri; padding: 5px 0;'>
                            A quota of rooms has been reserved for the IUCN APAC 2022 participants in hotels in the city centre and close to the conference venue. 
                            The reservations will be made on “first come, first served” basis. The hotel quotas are valid until one months before the conference. 
                            After that, the COC cannot guarantee the availability of the hotel rooms but will assist you in finding accommodation. The hotel fees 
                            include breakfast, service and VAT. All hotel fees are payable directly to the hotels. <br> 
                              <ul style='margin: 5px 0;'>
                                <li><a href='https://www.travelzuri.com/B2C/Admin/GTC/EventInfoCart.aspx?Ref_Type=HTL&CID=87&CityCode=KGL&EventName=Africa%20Protected%20Area%20Congress%20&SSr=EVTHL#' target='_blank'>Click here</a> to book your accommodation for your stay in Kigali   </li>
                              </ul>
                              
                            </td>
                          </tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 5px 0;'><b>Visa information:</b> </td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri;'>
                                <ul>
                                  <li>Visas are issued on arrival for all countries </li>
                                  <li>Visa fees for citizens of African Union, Commonwealth and La Francophonie member states are waived for a visit of up to 30 days</li>
                                  <li>Citizens of East African Community Member States (Burundi, Kenya, Uganda, United Republic of Tanzania and South Sudan) shall be issued a pass/entry visa free of charge upon arrival to stay for the period of six months.  </li>
                                  <li>Citizens of all countries that are not visa exempt, have the option to submit an online visa application. Payment can be done online or upon arrival. </li>
                                  <li><a href='https://www.migration.gov.rw/anounce/anounce/online-visa/' target='_blank'>Click here</a> for more information on Rwanda’s open visa policy  </li>
                                </ul>
                            </td>
                          </tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 5px 0;'><b>Destination information</b> </td></tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 5px 0;'>Rwanda’s stunning scenery and warm, friendly people offer unique experiences in one of the most remarkable countries in the world. It is blessed with extraordinary biodiversity, with incredible wildlife living throughout its volcanoes, montane rainforest and sweeping plains.</td></tr>
                          <tr><td class='h2' style='font-family: calibri;padding: 15px 0;'>Go to <a href='https://www.visitrwanda.com/' target='_blank'>www.visitrwanda.com</a> for more information on activities and excursions you can experience during your time in Rwanda.</td></tr>


                       
                      </table>
                    </td>
                  </tr> 

   " . self::emailLayoutSectionFooter3();

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
    $_Subject_ = 'Invitation - IUCN Africa Protected Areas Congress (APAC) Registration';
    $_Message_ = self::emailSectionHeaderLayout() . "
                  <tr>
                    <td class='innerpadding borderbottom'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                        <td class='h2' style='font-family: calibri; padding: 15px 0px 30px 0px; '>Dear <b>$firstname</b>,</td>
                        </tr>
                        
                        <tr><td class='h2' style='font-family: calibri;padding:0;'>We are pleased to inform you that your invitation to attend the IUCN Africa Protected Areas Congress (APAC), that will be held in Kigali from 18th to 23rd July 2022, has been created. 
                        </td></tr>

                        <tr><td class='h2' style='font-family: calibri;padding: 15px 0;'>Please proceed to the following link to register  <a href='$generated_link'> Click here</a>. 
                        </td></tr>
                        
                        <tr><td class='h2' style='font-family: calibri;padding:  0;'><b>Programme </b> </td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri;'>
                                <ul>
                                  <li>You can view the congress programme at <a href='https://apacongress.africa/programme/'>apacongress.africa/programme</a></li>
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
                <td class='innerpadding borderbottom'>
                  <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                    <td class='h2' style='font-family: calibri; padding: 15px 0px 30px 0px; '>Dear <b>$fullname</b>,</td>
                    </tr>
                    <tr>
                      <td class='bodycopy' style='font-family: calibri;'>
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
                  <td class='innerpadding borderbottom'>
                    <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                      <tr>
                      <td class='h2' style='font-family: calibri; padding: 15px 0px 30px 0px; '>Dear <b>$fullname</b>,</td>
                      </tr>
                      <tr>
                        <td class='bodycopy' style='font-family: calibri;'>
                          Thank you for registering to our $event_type event: $event. We will process your application and get back to you very soon.<br><br>
                        </td>
                      </tr>
                      <tr>
                        <td class='bodycopy' style='font-family: calibri;'>
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


  public static function emailSectionHeaderLayout()
  {
    $_HeaderLayout_ = "
      <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
        <html xmlns='http://www.w3.org/1999/xhtml'>
        <head>
          <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
          <title>The Future Summit</title>
          <link rel='stylesheet' type='text/css' href='//fonts.googleapis.com/css?family=Ubuntu' />
          <style type='text/css'>
          body {margin: 0; padding: 0; min-width: 100%!important; font-family: calibri;}
          img {height: auto;}
          .content {width: 100%; max-width: 600px;border:1px solid #f2f2f2;}
          .header {padding: 15px 30px 15px 30px;}
          .innerpadding {padding: 30px 30px 10px 30px;}
          .borderbottom { background-color:#f6f6f6;}
          .subhead {font-size: 15px; color: #ffffff; font-family: calibri; letter-spacing: 10px;}
          .h1 {color: #ffffff; font-family: calibri;}
          .h2, .bodycopy {color: #2c2a2a; font-family: calibri;}
          .h1 {font-size: 30px; line-height: 38px; font-weight: bold;}
          .h2 {padding: 0 0 15px 0; font-size: 14px; line-height: 24px;}
          .h3 {padding: 0 0 5px 0; font-size: 14px; line-height: 28px; text-transform:uppercase}
          .bodycopy {font-size: 14px; line-height: 22px;}
          .button {text-align: center; font-size: 18px; font-family: calibri; font-weight: bold; padding: 0 30px 0 30px;}
          .button a {color: #ffffff; text-decoration: none;}
          .footer {padding: 10px 30px 10px 30px; border-bottom:10px solid #37af47;background: #fff;}
          .footer td a {color: #2a98c7; text-decoration:none}
          .footercopy {font-family: calibri; font-size: 14px; color: #ffffff;}
          .footercopy a {color: #ffffff; text-decoration: none;}
          ul{margin:0;}
          a {color: #2a98c7 !important;}
          td .list li strong {color: black !important; font-weight: 400; }
          .alignment{display: inline-block;background: #37af47;width: 100px;height: 2px;-webkit-border-radius: 2px;-moz-border-radius: 2px;border-radius: 2px;margin-bottom: 4px;}

          @media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
          body[yahoo] .hide {display: none!important;}
          body[yahoo] .buttonwrapper {background-color: transparent!important;}
          body[yahoo] .button {padding: 0px!important;}
          body[yahoo] .button a {background-color: #f47e20; padding: 15px 15px 13px!important;}

         }
          </style>
        </head>

        <body yahoo bgcolor='#fff'>
        
          <table width='100%' bgcolor='#fff' border='0' cellpadding='0' cellspacing='0'>
            <tr>
              <td>
                <table bgcolor='#ffffff' class='content' align='center' cellpadding='0' cellspacing='0' border='0'>
                  <tr>
                    <td bgcolor='#fff' class='header'>
                      <table width='60' align='left' border='0' cellpadding='0' cellspacing='0'>  
                        <tr>
                          <td>
                            <img class='fix' src='http://apacongress.torusguru.com/img/apac-web-logo.png' width='120' height='60' border='0' alt='' />
                          </td>
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
                            <td>
                              <table border='0' cellspacing='0' cellpadding='0'>
                                <td class='h2' style='font-family: calibri; padding: 10px 0;  color:#37af47;text-transform:uppercase;'><b>Stay connected</b></td>
                              </table>
                              <span class='alignment'></span>
                              <table border='0' cellspacing='0' cellpadding='0'>
                                <tr><td class='h2' style='font-family: calibri; padding:0;'><b>Facebook:</b> <a href='https://www.facebook.com/APACongress' target='_blank'>@APACongress</a></td></tr>
                                <tr><td class='h2' style='font-family: calibri; padding:0;'><b>Twitter:</b> <a href='https://twitter.com/APA_Congress?s=20' target='_blank'>@APA_Congress</a></td></tr>
                                <tr><td class='h2' style='font-family: calibri;padding:0;'><b>Connect with our official tag: </b><a href='#'>#APAC2022 </a></td></tr>
                                <tr><td class='h2' style='font-family: calibri;padding:15px 0;'>We look forward to meeting you in Kigali, Rwanda. </td></tr>
                                <tr><td class='h2' style='font-family: calibri;padding:0;'><b>Yours Faithfully,</b> </td></tr>
                                <tr><td class='h2' style='font-family: calibri;'> The APAC Secretariat </td></tr>
                              </table>
                            </td>
                          </tr>
                          <tr>
                              <td>
                                <table border='0' cellspacing='0' cellpadding='0'>
                                <tr><td class='h2' style='font-family: calibri;padding: 35px 0 0 0;'><b>Disclaimer</b> </td></tr>
                                  <tr><td class='h2' style='font-family: calibri; text-align: justify; font-size: 13px;'> The information contained in or accompanying this email and any attachment thereto, is intended solely for the use of the stated recipient(s) and may contain information that is confidential and/or privileged. Any dissemination, distribution or copying of this email and any attachment by anyone who is not a stated recipient is strictly prohibited. If you receive this message in error, please notify the sender immediately and delete the message and any attachment from your system without retaining a copy. Thank you for your cooperation and understanding. </td></tr>
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


  public static function emailLayoutSectionFooterRefund()
  {
    $_FooterLayout_ = "
                  <tr>
                      <td class='footer' bgcolor='#fff'>
                        <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                          <tr>
                            <td>
                              <table border='0' cellspacing='0' cellpadding='0'>
                                <td class='h2' style='font-family: calibri; padding: 10px 0;  color:#37af47;text-transform:uppercase;'><b>Stay connected</b></td>
                              </table>
                              <span class='alignment'></span>
                              <table border='0' cellspacing='0' cellpadding='0'>
                                <tr><td class='h2' style='font-family: calibri; padding:0;'><b>Facebook:</b> <a href='https://www.facebook.com/APACongress' target='_blank'>@APACongress</a></td></tr>
                                <tr><td class='h2' style='font-family: calibri; padding:0;'><b>Twitter:</b> <a href='https://twitter.com/APA_Congress?s=20' target='_blank'>@APA_Congress</a></td></tr>
                                <tr><td class='h2' style='font-family: calibri;padding:0;'><b>Connect with our official tag: </b><a href='#'>#APAC2022 </a></td></tr>
                                
                                <tr><td class='h2' style='font-family: calibri;padding:0;'><b><br><br>Best Wishes,</b> </td></tr>
                                <tr><td class='h2' style='font-family: calibri;'> The APAC Secretariat </td></tr>
                              </table>
                            </td>
                          </tr>
                          <tr>
                              <td>
                                <table border='0' cellspacing='0' cellpadding='0'>
                                <tr><td class='h2' style='font-family: calibri;padding: 35px 0 0 0;'><b>Disclaimer</b> </td></tr>
                                  <tr><td class='h2' style='font-family: calibri; text-align: justify; font-size: 13px;'> The information contained in or accompanying this email and any attachment thereto, is intended solely for the use of the stated recipient(s) and may contain information that is confidential and/or privileged. Any dissemination, distribution or copying of this email and any attachment by anyone who is not a stated recipient is strictly prohibited. If you receive this message in error, please notify the sender immediately and delete the message and any attachment from your system without retaining a copy. Thank you for your cooperation and understanding. </td></tr>
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


  public static function emailLayoutSectionFooter2()
  {
    $_FooterLayout_ = "
                   <tr>
                      <td class='footer' bgcolor='#f6f6f6'>
                        <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                          <tr>
                            <td>
                              <table border='0' cellspacing='0' cellpadding='0'>
                                <tr><td class='h2' style='font-family: calibri;padding: 15px 0 0 0;'><b>Yours Faithfully,</b> </td></tr>
                                <tr><td class='h2' style='font-family: calibri;'> The APAC Secretariat </td></tr>
                              </table>
                            </td>
                          </tr>
                          <tr>
                                <td>
                                    <table border='0' cellspacing='0' cellpadding='0'>
                                    <tr><td class='h2' style='font-family: calibri;padding: 35px 0 0 0;'><b>Disclaimer</b> </td></tr>
                                      <tr><td class='h2' style='font-family: calibri; text-align: justify; font-size: 13px;'> The information contained in or accompanying this email and any attachment thereto, is intended solely for the use of the stated recipient(s) and may contain information that is confidential and/or privileged. Any dissemination, distribution or copying of this email and any attachment by anyone who is not a stated recipient is strictly prohibited. If you receive this message in error, please notify the sender immediately and delete the message and any attachment from your system without retaining a copy. Thank you for your cooperation and understanding. </td></tr>
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


  public static function emailLayoutSectionFooter3()
  {
    $_FooterLayout_ = "
                  <tr>
                    <td class='footer' bgcolor='#fff'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        
                        <tr>
                          <td>
                            <table border='0' cellspacing='0' cellpadding='0'>
                              <td class='h2' style='font-family: calibri;padding:0; padding: 10px 0; color:#37af47;text-transform:uppercase;'><b>Stay connected</b></td>
                            </table>
                            <span class='alignment'></span>
                            <table border='0' cellspacing='0' cellpadding='0'>
                              <tr><td class='h2' style='font-family: calibri; padding:0;'><b>Facebook:</b> <a href='https://www.facebook.com/APACongress' target='_blank'>@APACongress</a></td></tr>
                              <tr><td class='h2' style='font-family: calibri; padding:0;'><b>Twitter:</b> <a href='https://twitter.com/APA_Congress?s=20' target='_blank'>@APA_Congress</a></td></tr>
                              <tr><td class='h2' style='font-family: calibri;padding:0;'><b>Connect with our official tag: </b><a href='#'>#APAC2022 </a></td></tr>
                              
                              <tr><td class='h2' style='font-family: calibri;padding:15px 0;'>For more information, please refer to the IUCN APAC delegate registration information, <a href='https://apacongress.torusguru.com/terms' target='_blank'>general terms and conditions</a>. We look forward to meeting you in Kigali, Rwanda.  </td></tr>
                              <tr><td class='h2' style='font-family: calibri;padding:0;'><b>Yours Faithfully,</b> </td></tr>
                              <tr><td class='h2' style='font-family: calibri;'> The APAC Secretariat </td></tr>
                            </table>
                          </td>
                        </tr>
                        <tr>
                              <td>
                                <table border='0' cellspacing='0' cellpadding='0'>
                                <tr><td class='h2' style='font-family: calibri;padding: 35px 0 0 0;'><b>Disclaimer</b> </td></tr>
                                  <tr><td class='h2' style='font-family: calibri; text-align: justify; font-size: 13px;'> The information contained in or accompanying this email and any attachment thereto, is intended solely for the use of the stated recipient(s) and may contain information that is confidential and/or privileged. Any dissemination, distribution or copying of this email and any attachment by anyone who is not a stated recipient is strictly prohibited. If you receive this message in error, please notify the sender immediately and delete the message and any attachment from your system without retaining a copy. Thank you for your cooperation and understanding. </td></tr>
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























































  /** - Event ID :: 9 - THE COALITION APRF- Send Email - Participant - Email received after application approved -  */
  public static function sendEmailToParticipantAfterApplication0001($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;

    $_Email_ = $email;
    $_Subject_ = 'Registration confirmation: Africa Private Sector Refugee Forum 2021';
    $_Message_ = self::emailSectionHeaderLayout0001() . "
                   <tr>
                    <td class='innerpadding borderbottom'>
                      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                          <td class='h2' style='font-family: calibri;'>Dear $firstname</td>
                        </tr>
                        <tr>
                        <tr>
                          <tr><td class='h2' style='font-family: calibri;'>Thank you for registering to attend the inaugural African Private Sector Refugee Forum on Forced Displacement that will be held in Kigali, Rwanda, from 30 November to 2 December 2021 at the Kigali Convention Center. </tr>
                         
                          <tr><td class='h2' style='font-family: calibri;padding:0;'><b>Your registration is hereby confirmed. </b> </td></tr>
                          <tr><td class='h2' style='font-family: calibri;padding:20px 0; color:#002060; text-decoration:underline;'><b>Quick Practical Information:</b> </td></tr>
                          <tr><td class='h2' style='font-family: calibri;padding:0;'>For the most updated program and on-going discussion, please connect and engage with us here:</td></tr>
                          <tr>
                          <td  style='padding: 10px 0 0 0;'>
                            <table border='0' cellspacing='0' cellpadding='0'>
                              <tr class='social-image'>
                              <td width='30' style='text-align: center;padding: 0 0px 0 0px;'>
                                <a href='https://www.arf.thecoalitionafrica.com' target='_blank'>
                                  <img src='http://aprf2021.torusguru.com/img/globe.png' width='37' height='37' alt='Facebook' border='0'/>
                                </a>
                              </td>
                              <td width='30' style='text-align: center; padding: 0 5px 0 10px;'>
                                  <a href='https://twitter.com/africaforum2021' target='_blank'>
                                    <img src='http://aprf2021.torusguru.com/img/twitter.png' width='37' height='37' alt='Twitter' border='0'/>
                                  </a>
                                </td>
                                <td width='30' style='text-align: center; padding: 0 5px 0 10px; font-family: calibri;'>
                                  <a href='https://www.linkedin.com/company/amahoro-coalition/' target='_blank'>
                                    <img src='http://aprf2021.torusguru.com/img/linkedin.png' width='37' height='37' alt='Instagram' border='0'/>
                                  </a>
                                </td>
                                <td width='30' style='text-align: center; padding: 0 5px 0 10px; font-family: calibri;'>
                                  <a href='https://www.instagram.com/africaforum2021/' target='_blank'>
                                    <img src='http://aprf2021.torusguru.com/img/instagram.png' width='37' height='37' alt='Instagram' border='0'/>
                                  </a>
                                </td>
                                
                              </tr>
                              
                            </table>
                          </td>
                        </tr>
                        <tr>
                          <td  class='h2' style='font-family: calibri;padding:15px 0 0 0;'><b>Connect with our official tag: </b> <a href='https://www.arf.thecoalitionafrica.com' target='_bank'>#36MillionSolutions</a></a></td>
                        </tr>
                          <tr><td class='h2' style='font-family: calibri;padding:20px 0; color:#002060; text-decoration:underline;'><b>International Travelers: </b></td> </tr>
                          <tr><td class='h2' style='font-family: calibri;padding:0;'><b>COVID Protocols:</b></td> </tr>
                          <tr><td class='h2' style='font-family: calibri;padding:0 0 20px 0;'>Please find the latest information on traveling to Rwanda and precautions in place due to COVID: <a href='https://travel.rbc.gov.rw/travel/'> https://travel.rbc.gov.rw/travel/</a> </td> </tr>
                          <tr><td class='h2' style='font-family: calibri;padding:0;'>Some protocols to note include: </td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri; padding:0;'>
                                <ul>
                                  <li>COVID-19 PCR test required for all travelers - must be less than 72 hours</li>
                                  <li>Fully vaccinated travelers are not required to quarantine upon arrival</li>
                                  <li>	Unvaccinated travelers will take PCR test upon arrival and will need to quarantine for up to 6 hours until the test results are in. </li>
                                </ul>
                            </td>
                          </tr>
                          <tr><td class='h2' style='font-family: calibri; padding:20px 0;'><i>Please note that the COVID protocols may change as we get closer to the event. We will share updates on the situation in Rwanda.</i> </td></tr>
                          <tr><td class='h2' style='font-family: calibri;padding:0;'><b>Flights to Kigali:</b></td></tr>
                          <tr><td class='h2' style='font-family: calibri;padding:0;'>RwandAir is pleased to provide you with a 15% discount on all serviced routes. Please use code <b>RBC20</b> when booking on <a href='www.rwandair.com' target='_bank'>www.rwandair.com</a> </td></tr>
                          <tr><td class='h2' style='font-family: calibri;padding:20px 0 0 0;'><b>Visa information</b></td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri;padding:0 0 20px 0;'>
                                <ul>
                                  <li>Visas are issued on arrival for all countries </li>
                                  <li>Visa fees for citizens of African Union, Commonwealth and La Francophonie member states are waived for a visit of up to 30 days </li>
                                  <li> For more information on Rwanda's open visa policy, please <a href='https://www.migration.gov.rw/our-services/visa/' target='_blank'>click here</a> </li>
                                </ul>
                            </td>
                          </tr>

                          <tr><td class='h2' style='font-family: calibri;padding:0;'><b>Accommodation: </b></td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri; padding:0;'>
                              We have negotiated preferred conference rates with two hotels.  Please book by November 15, 2021 to access the preferential rates. 
                              <ul>
                                <li>Radisson : <a href='https://www.radissonhotels.com/en-us/booking/room-display?checkInDate=2021-11-29&checkOutDate=2021-12-03&adults%5B%5D=1&children%5B%5D=0&searchType=pac&promotionCode=AC2021&brandFirst=rdb&hotelCode=KGLZH' target='_blank'>click here</a></li>
                                <li>Marriott : <a href='https://www.marriott.com/event-reservations/reservation-link.mi?id=1635228903300&key=GRP&app=resvlink' target='_blank'>click here</a></li>
                              </ul>
                            </td>
                          </tr>
                          
                          <tr><td class='h2' style='font-family: calibri;padding:20px 0 0 0;'><b>Transport: </b></td></tr>
                          <tr>
                            <td class='h2' style='font-family: calibri; padding:0 0 20px 0;'>
                              <ul>
                                <li>We will provide you with shuttle service from the airport to each hotel.</li>
                                <li>We will provide you with shuttle service between each hotel and all forum related activities. </li>
                                <li>Please note that we will not be providing shuttle services from any other hotels in Kigali</li>
                              </ul>
                            </td>
                          </tr>
                          
                          <tr><td class='h2' style='font-family: calibri;padding:0;'>We can not wait to connect with you in Kigali! </td></tr>
                          <tr><td class='h2' style='font-family: calibri;'>If you have any inquiries feel free to contact us at <a href='mailto:forum@thecoalitionafrica.com'>forum@thecoalitionafrica.com  </a> </td></tr>
                          <tr>
                            <td  style='padding: 10px 0 0 0;'>
                              <table border='0' cellspacing='0' cellpadding='0'>
                                <tr class=''>
                                <td width='150' style='text-align: center;padding: 0 0px 0 0px;'>
                                  <a href='https://www.arf.thecoalitionafrica.com' target='_blank'>
                                    <img src='http://aprf2021.torusguru.com/img/partners/part1.png' width='100%'  alt='Facebook' border='0'/>
                                  </a>
                                </td>
                                <td width='150' style='text-align: center; padding: 0 5px 0 10px;'>
                                    <a href='https://www.unhcr.org/afr/' target='_blank'>
                                      <img src='http://aprf2021.torusguru.com/img/partners/part2.png' width='100%'  alt='Twitter' border='0'/>
                                    </a>
                                  </td>
                                  <td width='150' style='text-align: center; padding: 0 5px 0 10px; font-family: calibri;'>
                                    <a href='https://www.minema.gov.rw/' target='_blank'>
                                      <img src='http://aprf2021.torusguru.com/img/partners/part3.png' width='100%'  alt='Instagram' border='0'/>
                                    </a>
                                  </td>
                                  <td width='150' style='text-align: center; padding: 0 5px 0 10px; font-family: calibri;'>
                                    <a href='https://africanentrepreneurcollective.org/' target='_blank'>
                                      <img src='http://aprf2021.torusguru.com/img/partners/part4.png' width='100%' alt='Instagram' border='0'/>
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




  /** - Event ID :: 9 - THE COALITION APRF- Send Email - Participant - Email received after application Denied -  */
  public static function sendEmailToParticipantAfterApplication0001Decline($_data_)
  {
    $_data_ = (object)$_data_;
    $email = $_data_->email;
    $firstname = $_data_->firstname;

    $_Email_ = $email;
    $_Subject_ = 'Virtual Registration: Africa Private Sector Refugee Forum 2021';
    $_Message_ = self::emailSectionHeaderLayout0001() . "
    <tr>
      <td class='innerpadding borderbottom'>
        <table width='100%' border='0' cellspacing='0' cellpadding='0'>
          <tr>
            <td class='h2' style='font-family: calibri;'>Dear $firstname</td>
          </tr>
          <tr>
          <tr>
            <tr><td class='h2' style='font-family: calibri;'>Thank you for registering to attend the inaugural African Private Sector Refugee Forum on Forced Displacement that will be held in Kigali, Rwanda, from 30 November to 2 December 2021.</tr>
          
            <tr><td class='h2' style='font-family: calibri;padding:0;'><b>Your registration is confirmed to join the event VIRTUALLY.   We will share the necessary links for your attendance no later than November 29th, 2021.</b> </td></tr>
          
            <tr><td class='h2' style='font-family: calibri;padding:15px 0 0 5px;'>Please stay connected and engaged with us on the following:</td></tr>
            <tr>
              <td  style='padding: 10px 0 0 0;'>
                <table border='0' cellspacing='0' cellpadding='0'>
                  <tr class='social-image'>
                  <td width='30' style='text-align: center;padding: 0 0px 0 0px;'>
                    <a href='https://www.arf.thecoalitionafrica.com' target='_blank'>
                      <img src='http://aprf2021.torusguru.com/img/globe.png' width='37' height='37' alt='Facebook' border='0'/>
                    </a>
                  </td>
                  <td width='30' style='text-align: center; padding: 0 5px 0 10px;'>
                      <a href='https://twitter.com/africaforum2021' target='_blank'>
                        <img src='http://aprf2021.torusguru.com/img/twitter.png' width='37' height='37' alt='Twitter' border='0'/>
                      </a>
                    </td>
                    <td width='30' style='text-align: center; padding: 0 5px 0 10px; font-family: calibri;'>
                      <a href='https://www.linkedin.com/company/amahoro-coalition/' target='_blank'>
                        <img src='http://aprf2021.torusguru.com/img/linkedin.png' width='37' height='37' alt='Instagram' border='0'/>
                      </a>
                    </td>
                    <td width='30' style='text-align: center; padding: 0 5px 0 10px; font-family: calibri;'>
                      <a href='https://www.instagram.com/africaforum2021/' target='_blank'>
                        <img src='http://aprf2021.torusguru.com/img/instagram.png' width='37' height='37' alt='Instagram' border='0'/>
                      </a>
                    </td>
                    
                  </tr>
                  
                </table>
              </td>
            </tr>
            <tr>
              <td  class='h2' style='font-family: calibri;padding:15px 0 0 0;'><b>Connect with our official tag: </b> <a href='https://www.arf.thecoalitionafrica.com' target='_bank'>#36MillionSolutions</a></a></td>
            </tr>

            <tr><td class='h2' style='font-family: calibri;'>If you have any inquiries feel free to contact us at <a href='mailto:forum@thecoalitionafrica.com'>forum@thecoalitionafrica.com  </a> </td></tr>
            <tr>
              <td  style='padding: 10px 0 0 0;'>
                <table border='0' cellspacing='0' cellpadding='0'>
                  <tr class=''>
                  <td width='150' style='text-align: center;padding: 0 0px 0 0px;'>
                    <a href='https://www.arf.thecoalitionafrica.com' target='_blank'>
                      <img src='http://aprf2021.torusguru.com/img/partners/part1.png' width='100%'  alt='Facebook' border='0'/>
                    </a>
                  </td>
                  <td width='150' style='text-align: center; padding: 0 5px 0 10px;'>
                      <a href='https://www.unhcr.org/afr/' target='_blank'>
                        <img src='http://aprf2021.torusguru.com/img/partners/part2.png' width='100%'  alt='Twitter' border='0'/>
                      </a>
                    </td>
                    <td width='150' style='text-align: center; padding: 0 5px 0 10px; font-family: calibri;'>
                      <a href='https://www.minema.gov.rw/' target='_blank'>
                        <img src='http://aprf2021.torusguru.com/img/partners/part3.png' width='100%'  alt='Instagram' border='0'/>
                      </a>
                    </td>
                    <td width='150' style='text-align: center; padding: 0 5px 0 10px; font-family: calibri;'>
                      <a href='https://africanentrepreneurcollective.org/' target='_blank'>
                        <img src='http://aprf2021.torusguru.com/img/partners/part4.png' width='100%' alt='Instagram' border='0'/>
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
    <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
        <html xmlns='http://www.w3.org/1999/xhtml'>
        <head>
          <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
          <title>The Future Summit</title>
          <link rel='stylesheet' type='text/css' href='//fonts.googleapis.com/css?family=Ubuntu' />
          <style type='text/css'>
          body {margin: 0; padding: 0; min-width: 100%!important; font-family: calibri;}
          img {height: auto;}
          .content {width: 100%; max-width: 700px;border:1px solid #f2f2f2;}
          /* .header {padding: 15px 30px 15px 30px;} */
          .innerpadding {padding: 50px 30px 50px 30px;}
          .borderbottom { background-color:#f6f6f6;}
          .subhead {font-size: 15px; color: #ffffff; font-family: calibri; letter-spacing: 10px;}
          .h1 {color: #ffffff; font-family: calibri;}
          .h2, .bodycopy {color: #002060; font-family: calibri;}
          .h1 {font-size: 30px; line-height: 38px; font-weight: bold;}
          .h2 {padding: 0 0 15px 0; font-size: 14px; line-height: 22px;}
          .h3 {padding: 0 0 5px 0; font-size: 14px; line-height: 28px; text-transform:uppercase}
          .bodycopy {font-size: 14px; line-height: 22px;}
          .button {text-align: center; font-size: 18px; font-family: calibri; font-weight: bold; padding: 0 30px 0 30px;}
          .button a {color: #ffffff; text-decoration: none;}
          .footer {padding: 10px 30px 10px 30px; border-bottom:10px solid #002060;background: #e9e9e9;}
          .footer td a {color: #002060; text-decoration:none}
          .footercopy {font-family: calibri; font-size: 14px; color: #ffffff;}
          .footercopy a {color: #ffffff; text-decoration: none;}
          .social-image img{width:30px;}
          ul{margin:0;}
          a {color: #002060 !important;}
          .alignment{display: inline-block;background: #002060;width: 100px;height: 2px;-webkit-border-radius: 2px;-moz-border-radius: 2px;border-radius: 2px;margin-bottom: 4px;}

          @media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
          body[yahoo] .hide {display: none!important;}
          body[yahoo] .buttonwrapper {background-color: transparent!important;}
          body[yahoo] .button {padding: 0px!important;}
          body[yahoo] .button a {background-color: #f47e20; padding: 15px 15px 13px!important;}

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
                            <img class='fix' src='http://aprf2021.torusguru.com/img/banner_v2.png' style='width: 100%;' border='0' alt='' />
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
                               
                               <tr><td class='h2' style='font-family: calibri;padding:0;'><b>Regards,</b> </td></tr>
                               <tr><td class='h2' style='font-family: calibri;'>The Amahoro Coalition </td></tr>
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