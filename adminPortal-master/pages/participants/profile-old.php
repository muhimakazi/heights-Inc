<?php
require_once "../../core/init.php"; 
if(!$user->isLoggedIn()) 
    Redirect::to('login');
    
if(!Input::checkInput('participantToken', 'get', 1))
    Redirect::to('');

$page = "profile";
$link = "profile";

$_participant_token_ = Input::get('participantToken', 'get');
$_participant_id_    = Hash::decryptToken( $_participant_token_);
$_participant_data_  = FutureEventController::getParticipantByID($_participant_id_);

$_data_ = $_participant_data_;

$_event_id = $eventId =$_participant_data_->event_id;

$event_token = $encodedEventId = Hash::encryptToken($_event_id);

$_status_ = $_participant_data_->status;
$_status_color_ = 'label-warning';

if($_status_ == 'COMPLETED' || $_status_ == 'APPROVED')
    $_status_color_ = '#5cb85c';
if($_status_ == 'ACTIVE')
    $_status_color_ = '#1a7bb9';
if($_status_ == 'DENIED')
    $_status_color_ = '#c13c5a';
if($_status_ == 'EXPIRED')
    $_status_color_ = '#9f9597';

/** Local CBO Code  */
$_CBO_CODE_ = 'C0015';

/** Payment Information */
$_payment_entry_data_ = PaymentController::getPaymentTransactionEntryDataByParticipantID($_event_id, $_participant_id_);

$_PAYMENT_METHOD_                      = '';
$_PAYMENT_TRANSACTION_AMOUNT_CURRENCY_ = '';
$_PAYMENT_STATUS_                      = '';
$_PAYMENT_RECEIPT_                     = '';
$_PAYMENT_TRANSACTION_ID_              = '';
$_EXTERNAL_TRANSACTION_ID_             = '';
$_PAYMENT_TRANSACTION_TIME_            = '';
$_PAYMENT_APPROVAL_TIME_               = '';
$_PAYMENT_APPROVAL_COMMENT_            = '';

/** Get Participant Last Transaction   */
if($_payment_entry_data_):
    $_PAYMENT_METHOD_                      = $_payment_entry_data_->payment_method;
    $_PAYMENT_TRANSACTION_AMOUNT_CURRENCY_ = $_payment_entry_data_->amount.' '.$_payment_entry_data_->currency;
    $_PAYMENT_STATUS_                      = $_payment_entry_data_->transaction_status;
    $_PAYMENT_RECEIPT_                     = $_payment_entry_data_->receipt_id == ''?'-':$_payment_entry_data_->receipt_id;
    $_PAYMENT_TRANSACTION_ID_              = $_payment_entry_data_->transaction_id;
    $_EXTERNAL_TRANSACTION_ID_             = $_payment_entry_data_->external_transaction_id;
    $_PAYMENT_TRANSACTION_TIME_            = (strlen($_payment_entry_data_->transaction_time) <= 1)?'-':date('D d-M-Y h:i:a', $_payment_entry_data_->transaction_time);
    $_PAYMENT_APPROVAL_TIME_               = (strlen($_payment_entry_data_->approval_time) <= 1)?'-':date('D d-M-Y h:i:a', $_payment_entry_data_->approval_time);
    $_PAYMENT_APPROVAL_COMMENT_            = $_payment_entry_data_->approval_comment;

    if($_payment_entry_data_->payment_method != 'BANK_TRANSFER')
            $_PAYMENT_APPROVAL_TIME_              = (strlen($_payment_entry_data_->callback_time) <= 1)?'-':date('D d-M-Y h:i:a', $_payment_entry_data_->callback_time);

    if($_payment_entry_data_->payment_method == '')
        $_PAYMENT_METHOD_ = 'ONLINE_PAYMENT';

endif;


// Getting Full details
$fullDetails=array();

    @$fullDetails= html_entity_decode($_participant_data_->full_details);
    
    if(json_decode($fullDetails)!=null){
        
        $fullDetails=json_decode($fullDetails);

    } else{
        // Not a valid JSON Object
    }


    $organisationType=$_participant_data_->organisation_type;
    // Getting Organisation subType for each Type
    $organisationSubType="";
    
    if(trim(strtolower($organisationType))=="fintech"){
        // If Organisation type is Fintech, get Fintech subtype
        $organisationSubType=property_exists($fullDetails, "finetech_firm_type")?$fullDetails->finetech_firm_type:"";

    } else if(trim(strtolower($organisationType))=="financial institution"){
        // If Organisation type is Financial Institution, get Financial Institution subtype
        $organisationSubType=property_exists($fullDetails, "financial_institution_type")?$fullDetails->financial_institution_type:"";

    } else if(trim(strtolower($organisationType))=="investor"){
        // If Organisation type is Investor, get Investor subtype
        $organisationSubType=property_exists($fullDetails, "investor_type")?$fullDetails->investor_type:"";

    } else if(trim(strtolower($organisationType))=="corporate (non-ninance / technology industries)"){
        // If Organisation type is Corporate and Vertical Industry, get equivalent subtype
        $organisationSubType=property_exists($fullDetails, "corporate_type")?$fullDetails->corporate_type:"";

    } else if(trim(strtolower($organisationType))=="technology"){
        // If Organisation type is Technology, get equivalent subtype
        $organisationSubType=property_exists($fullDetails, "technology_type")?$fullDetails->technology_type:"";

    } else if(trim(strtolower($organisationType))=="digitalaAssets & blockchain"){
        // If Organisation type is Digital Assets and Blockchain, get equivalent subtype
        $organisationSubType=property_exists($fullDetails, "assets_type")?$fullDetails->assets_type:"";
        
    }



?>

<!DOCTYPE html>
<html>

<head>
    <?php include $INC_DIR . "head.php"; ?>
    <script src="<?php linkto('js/jquery-2.1.1.js'); ?>"></script>

    <style>
        b {
            font-family: Lato-Regular;
        }
    </style>
</head>

<body>
    <div id="wrapper">

        <?php include $INC_DIR . "nav.php"; ?>
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Participant Profile</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php linkto(''); ?>">Home</a>
                    </li>
                    <li>
                        <a>Participants</a>
                    </li>
                    <li class="active">
                        <strong>Profile</strong>
                    </li>
                </ol>
            </div>

            <div class="col-lg-2"></div>
        </div>

        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <!-- <div class="col-lg-2"></div> -->
                <div class="col-lg-12">
                    <div class="ibox float-e-margins" id="card-profile">
                        <div class="ibox-title" style="height: auto;">

                        </div>
                        <div class="ibox-content">
                            <div class="row gutters-sm">
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex flex-column align-items-center text-center">
                                                <img src="<?=$_participant_data_->profile != null? VIEW_PROFILE.$_participant_data_->profile: linkto('data_system/img/noprofile.png')?>"
                                                    alt="Admin" class="rounded-circle" width="150">
                                                <div class="mt-3">
                                                    <h4><?= $_participant_data_->firstname .' '. $_participant_data_->lastname ?>
                                                    </h4>
                                                    <p class="text-secondary mb-1 display_status_"
                                                        style="color: <?=$_status_color_?>;">
                                                        <?=Functions::getStatus($_status_)?> <i
                                                            class="fa fa-times-circle"></i></p>
                                                    <?php
if($_participant_data_->participation_subtype_category == 'INPERSON' AND $_participant_data_->qrCode != ''):
    ?>
                                                    <!-- <a class="btn btn-xs btn-primary " target="_blank"
                                                        href="<?php linkto('ebadge/'.$_participant_data_->qrCode); ?>">
                                                        <i class="fa fa-user" aria-hidden="true"></i> Generate Badge</a> -->
                                                    <?php
endif;
if(($_participant_data_->payment_state == 'FREE')
    || ($_participant_data_->payment_state == 'PAYABLE' AND $_participant_data_->participation_type_code == $_CBO_CODE_)):
    ?>
                                                    <!-- <button
                                                        class="btn btn-xs btn-success disable_btn_approve <?=$_participant_data_->status=='APPROVED'?'disabled':''?>"
                                                        data-toggle="modal"
                                                        data-target="#activateModal<?=Hash::encryptToken($_participant_data_->id)?>"><i
                                                            class="fa fa-check icon"></i> Approve</button> -->
                                                    <!-- <button
                                                        class="btn btn-xs btn-outline-danger disable_btn_deny <?=$_participant_data_->status=='DENIED'?'disabled':''?>"
                                                        data-toggle="modal"
                                                        data-target="#deactivateModal<?=Hash::encryptToken($_participant_data_->id)?>"><i
                                                            class="fa fa-remove icon"></i> Deny</button> -->
                                                    <?php
endif;
?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
/** Display Organization Section - When Not Media - */
if($_participant_data_->iplc_letter != ''):
?>
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <h6 class="mb-0">Organisation Document</h6>
                                                </div>
                                                <div class="col-sm-12 text-secondary" style="overflow: hidden;">
                                                    <!-- <embed src="<?=VIEW_IPLC_LETTER.$_participant_data_->iplc_letter?>" frameborder="0" style="overflow:hidden;height:100%;width:100%" height="100%" width="100%"></embed> -->

                                                        <iframe src="<?=VIEW_IPLC_LETTER.$_participant_data_->iplc_letter?>" frameborder="0" style="overflow:hidden; width:100%; height: 80vh;" width="100%"></iframe>
                                                </div>
                                            </div>                                            
                                        </div>
                                    </div>
<?php endif; ?>
                                    <h3 class="card-sect-title">Event </h3>
                                    <div class="card mt-3">
                                        <div class="card-body side-card">
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <h6 class="mb-0">Event Name</h6>
                                                </div>
                                                <div class="col-sm-7 text-secondary">
                                                    <h6><?= $_participant_data_->event_name ?></h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <h6 class="mb-0">Event Type</h6>
                                                </div>
                                                <div class="col-sm-7 text-secondary">
                                                    <h6><?= Functions::getEventCategory($_participant_data_->participation_subtype_category) ?>
                                                    </h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <h6 class="mb-0">Participation Type</h6>
                                                </div>
                                                <div class="col-sm-7 text-secondary">
                                                    <h6><?= $_participant_data_->participation_type_name ?></h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h6 class="mb-0">Participation Subtype</h6>
                                                </div>
                                                <div class="col-sm-6 text-secondary">
                                                    <h6><?= $_participant_data_->participation_subtype_name ?></h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <?php if($_participant_data_->participation_type_name == 'CMPD') { ?>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h6 class="mb-0">Invitation Status</h6>
                                                </div>
                                                <div class="col-sm-6 text-secondary">
                                                    <h6><?=$_participant_data_->cmpd_invite_status == 1 ?"Accepted":"Not Accepted" ?></h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <?php } ?>
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <h6 class="mb-0">Payment State</h6>
                                                </div>
                                                <div class="col-sm-7 text-secondary">
                                                    <h6><?= $_participant_data_->payment_state ?></h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h6 class="mb-0">Participation Price</h6>
                                                </div>
                                                <div class="col-sm-6 text-secondary">
                                                    <h6><?= $_participant_data_->participation_subtype_price ?>
                                                        <small><?= $_participant_data_->participation_subtype_currency ?></small>
                                                    </h6>
                                                </div>
                                            </div>
                                            <hr>
                                        </div>
                                    </div>
                                    <?php
if($_participant_data_->payment_state == 'PAYABLE'):
    ?>

                                    <h3 class="card-sect-title">Payment </h3>
                                    <div class="card mt-3">
                                        <div class="card-body side-card">
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <h6 class="mb-0">Payment Method</h6>
                                                </div>
                                                <div class="col-sm-7 text-secondary">
                                                    <h6><?=$_PAYMENT_METHOD_?></h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <h6 class="mb-0">Amount paid</h6>
                                                </div>
                                                <div class="col-sm-7 text-secondary">
                                                    <h6><?=$_PAYMENT_TRANSACTION_AMOUNT_CURRENCY_?></h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <h6 class="mb-0">Transaction Status</h6>
                                                </div>
                                                <div class="col-sm-7 text-secondary">
                                                    <h6><?=$_PAYMENT_STATUS_?></h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <h6 class="mb-0">Payment Receipt</h6>
                                                </div>
                                                <div class="col-sm-7 text-secondary">
                                                    <h6><?=$_PAYMENT_RECEIPT_?></h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <h6 class="mb-0">Transaction ID</h6>
                                                </div>
                                                <div class="col-sm-7 text-secondary">
                                                    <h6><?=$_PAYMENT_TRANSACTION_ID_?></h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <h6 class="mb-0">External Transaction ID</h6>
                                                </div>
                                                <div class="col-sm-7 text-secondary">
                                                    <h6><?=$_EXTERNAL_TRANSACTION_ID_?></h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h6 class="mb-0">Payment Transaction Time</h6>
                                                </div>
                                                <div class="col-sm-6 text-secondary">
                                                    <h6><?=$_PAYMENT_TRANSACTION_TIME_?></h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h6 class="mb-0">Payment Approval Time</h6>
                                                </div>
                                                <div class="col-sm-6 text-secondary">
                                                    <h6><?=$_PAYMENT_APPROVAL_TIME_?></h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <h6 class="mb-0">Payment Approval Comment</h6>
                                                </div>
                                                <div class="col-sm-12 text-secondary">
                                                    <h6><?=$_PAYMENT_APPROVAL_COMMENT_?></h6>
                                                </div>
                                            </div>
                                            <!-- <hr> -->
                                        </div>
                                    </div>


                                    <?php
endif;
    ?>
                                </div>

                                <div class="col-md-8">

                                    <h3 class="card-sect-title">CONTACT INFORMATION </h3>
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Full Name</h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <h6><?= $_participant_data_->firstname .' '. $_participant_data_->lastname ?>
                                                    </h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Email address</h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <h6><a href="mailto:email"><?= $_participant_data_->email ?></a>
                                                    </h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Telephone number </h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <h6><a href="tel:phone"><?= $_participant_data_->telephone ?></a>
                                                    </h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Country</h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <h6><?= $_participant_data_->residence_country ?></h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">City</h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <h6><?= $_participant_data_->organisation_city ?></h6>
                                                </div>
                                            </div>
                                            <!-- <hr>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Telephone number 2</h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <h6><a href="tel:phone"><?= $_participant_data_->telephone_2 ?></a>
                                                    </h6>
                                                </div>
                                            </div> -->
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Job title</h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <h6><?= $_participant_data_->job_title ?></h6>
                                                </div>
                                            </div>

                                            <hr>

                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Job level</h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <h6><?= property_exists($fullDetails, "job_level1")?$fullDetails->job_level1:""?>
                                                    </h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Job function</h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <h6><?= property_exists($fullDetails, "job_function1")?$fullDetails->job_function1:""?>
                                                    </h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Referral</h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <h6><?= property_exists($fullDetails, "referral")?$fullDetails->referral:""?>
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
/** Display Organization Section - When Not Media - */
if( $_participant_data_->participation_type_name != 'Media' && $_participant_data_->student_state == 0  ):
?>
                                    <h3 class="card-sect-title">ORGANIZATION</h3>
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Organization name</h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <h6><?= $_participant_data_->organisation_name ?></h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Organization type</h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <h6><?= $organisationType ?></h6>
                                                </div>
                                            </div>
                                            <hr>

                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Organisation sub type</h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <h6><?= $organisationSubType ?>
                                                    </h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Organization website</h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <h6><a
                                                            href="<?= $_participant_data_->website == ''?'#': str_replace('http:// http://', 'http://', 'http://'.$_participant_data_->website)  ?>"><?= $_participant_data_->website ?></a>
                                                    </h6>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <?php
/** Display Organization Section - When Not Media - Students - */
elseif( $_participant_data_->participation_type_name != 'Media' && $_participant_data_->student_state == 1  ):
?>
                                    <h3 class="card-sect-title">EDUCATION INSTITUTE</h3>
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Institute Name</h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <h6><?= $_participant_data_->educacation_institute_name ?></h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Institute Type</h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <h6><?= $_participant_data_->educacation_institute_category ?></h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Industry</h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <h6><?= $_participant_data_->educacation_institute_industry ?></h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Institute Website</h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <h6><a
                                                            href="<?= $_participant_data_->educacation_institute_website == ''?'#': $_participant_data_->educacation_institute_website  ?>"><?= $_participant_data_->educacation_institute_website ?></a>
                                                    </h6>
                                                </div>
                                            </div>
                                            <hr>

                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Institute Country/ City </h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <h6><?= countryCodeToCountry($_participant_data_->educacation_institute_country) ?>
                                                        / <?= $_participant_data_->educacation_institute_city ?></h6>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <?php
/** Display Organization Section - When  Media - */
elseif( $_participant_data_->participation_type_name == 'Media'):
?>
                                    <!-- info fo media person -->
                                    <h3 class="card-sect-title">ORGANIZATION</h3>
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Organization name</h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <h6><?= $_participant_data_->organisation_name ?></h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Media category</h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <h6><?= $_participant_data_->organisation_type ?></h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Press card number </h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <h6><?= $_participant_data_->media_card_number ?></h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Issuing authority</h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <h6><?= $_participant_data_->media_card_authority ?></h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">City / Country</h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <h6><?= countryCodeToCountry($_participant_data_->organisation_country) ?>
                                                        / <?= $_participant_data_->organisation_city ?></h6>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <h3 class="card-sect-title">TOOLS</h3>
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <h6 class="mb-0">List of equipment to be brought</h6>
                                                </div>
                                                <div class="col-sm-8 text-secondary">
                                                    <h6><?= $_participant_data_->media_equipment ?></h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <h6 class="mb-0">Special Request</h6>
                                                </div>
                                                <div class="col-sm-8 text-secondary">
                                                    <h6><?= $_participant_data_->special_request ?></h6>
                                                </div>
                                            </div>
                                            <!-- <hr> -->
                                        </div>
                                    </div>
                                    <?php
    endif;
?>
                                    <h3 class="card-sect-title">INTERESTS
                                    </h3>
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Reasons for visit</h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <h6 style="">
                                                        <?= property_exists($fullDetails, "participant_reasons_for_visit") ? implode(", ", $fullDetails->participant_reasons_for_visit):"" ?>
                                                    </h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <?php 
                                                if(strtolower($organisationType)=="fintech"){
                                                    $meetingOtherFintechs= property_exists($fullDetails, "meeting_other_fintechs")?$fullDetails->meeting_other_fintechs:"";
                                                    $meetingInvestors= property_exists($fullDetails, "meeting_an_investor")?$fullDetails->meeting_an_investor:"";

                                                    
                                            
                                            ?>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Fintech interested in </h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <h6>
                                                        <?= ($meetingInvestors=="YES"?"Meeting an investor":"").", ".($meetingOtherFintechs=="YES"?"Meeting other FinTechs":"") ?>
                                                    </h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <?php
                                                } else if(strtolower($organisationType)=="investor"){
                                                    $interestedInPreseedStartups= property_exists($fullDetails, "interested_in_preseed_startups")?$fullDetails->interested_in_preseed_startups:"";
                                                    $interestedInSeedingStageStartups= property_exists($fullDetails, "interested_in_seeding_stage_startups")?$fullDetails->interested_in_seeding_stage_startups:"";
                                                    $interestedInSeriesABC= property_exists($fullDetails, "interested_in_seriesABC_startups")?$fullDetails->interested_in_seriesABC_startups:"";
                                                    $interestedInStartupsSeekingExit= property_exists($fullDetails, "interested_in_startups_seeking_exit")?$fullDetails->interested_in_startups_seeking_exit:"";
                                            ?>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Investor interested in </h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <h6 style="text-align:left;">
                                                        <?= ($interestedInPreseedStartups=="YES"?"Pre-seed startups":"").", ".($interestedInSeedingStageStartups=="YES"?"Startups in seeding stage":"").", ".($interestedInSeriesABC=="YES"?"Startups in early stage":"").", ".($interestedInStartupsSeekingExit=="YES"?"Startups in late stage":"") ?>
                                                    </h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <?php 
                                                }
                                            ?>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h6 class="mb-0">Other global events </h6>
                                                </div>
                                                <div class="col-sm-6 text-secondary">
                                                    <h6><?= property_exists($fullDetails, "participant_event_interest") ? implode(" / ", $fullDetails->participant_event_interest):"" ?>
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
/** Only For In-person participation event */
if($_participant_data_->participation_subtype_category == 'INPERSON' && ($user->hasPermission('admin') || $user->hasPermission('security')) ):
?>
                                    <h3 class="card-sect-title">IDENTIFICATION</h3>
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Type of ID document </h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <h6><?= $_participant_data_->id_type ?></h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Document number </h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <h6><?= $_participant_data_->id_number ?></h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Country of citizenship</h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <h6><?= property_exists($fullDetails, "citizenship")?$fullDetails->citizenship:"" ?>
                                                    </h6>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <?php
endif;
// if($_participant_data_->participation_subtype_name == 'Speaker Onboarding'):
if(property_exists($fullDetails, "bio")):
?>
                                    <h3 class="card-sect-title">SPEAKER DETAILS</h3>
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Timezone </h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <h6><?= property_exists($fullDetails, "timezone")?'(UTC '.$fullDetails->timezone.')':"" ?></h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h6 class="mb-0">Biography </h6>
                                                </div>
                                                <div class="col-sm-12 text-secondary">
                                                    <p><?= property_exists($fullDetails, "bio")?$fullDetails->bio:"" ?></p>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h6 class="mb-0">Alternative Contact Methods</h6>
                                                </div>
                                                <div class="col-sm-12 text-secondary">
                                                    <?php 
                                                    if (property_exists($fullDetails, "Telegram") AND $fullDetails->Telegram != "") {
                                                        echo '<p><b>Telegram</b>: '.$fullDetails->Telegram.'</p>';
                                                    }
                                                    if (property_exists($fullDetails, "WeChat") AND $fullDetails->WeChat != "") {
                                                        echo '<p><b>WeChat</b>: '.$fullDetails->WeChat.'</p>';
                                                    }
                                                    if (property_exists($fullDetails, "WhatsApp") AND $fullDetails->WhatsApp != "") {
                                                        echo '<p><b>WhatsApp</b>: '.$fullDetails->WhatsApp.'</p>';
                                                    }
                                                    if (property_exists($fullDetails, "alternative_contact") AND $fullDetails->alternative_contact != "") {
                                                        echo '<p><b>Other</b>: '.$fullDetails->alternative_contact.'</p>';
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <h6 class="mb-0">Speaker's RSVP for IFF dinner</h6>
                                                </div>
                                                <div class="col-sm-12 text-secondary">
                                                    <?php 
                                                    if (property_exists($fullDetails, "iff_dinner") AND $fullDetails->iff_dinner == "YES") {
                                                        echo '<p>Yes, I will be attending the dinner</p>';
                                                        if (property_exists($fullDetails, "dietary_restrictions") AND $fullDetails->dietary_restrictions != "") {
                                                            echo '<p><b>Dietary restrictions: </b>'.$fullDetails->dietary_restrictions.'</p>';
                                                            
                                                        }
                                                    } else {
                                                        echo '<p>No, I will not be attending the dinner</p>';
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <h6 class="mb-0">Speaker's availability for interviews before and during IFF</h6>
                                                </div>
                                                <div class="col-sm-12 text-secondary">
                                                    <?php 
                                                    if (property_exists($fullDetails, "speaker_availability") AND $fullDetails->speaker_availability == "YES") {
                                                        echo '<p><b>Speaker is open to interviews from</b>: '.$fullDetails->speaker_open_interviews.'</p>';
                                                    } else {
                                                        echo '<p>No, I am not available for interviews</p>';
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h6 class="mb-0">Speaker alternative contacts </h6>
                                                </div>
                                                <div class="col-sm-12 text-secondary">
                                                    <p><?= property_exists($fullDetails, "emergency_contact_firstname")?"<b>Name of alternative contact person: </b>".$fullDetails->emergency_contact_firstname:"" ?></p>

                                                    <p><?= property_exists($fullDetails, "emergency_contact_email")?"<b>Email address of alternative contact person: </b>".$fullDetails->emergency_contact_email:"" ?></p>

                                                    <p><?= property_exists($fullDetails, "emergency_contact_telephone")?"<b>Contact number of alternative contact person: </b>".$fullDetails->emergency_contact_telephone:"" ?></p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
<?php
endif;
?>

                                    <h3 class="card-sect-title">ACCOMMODATION</h3>
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Need accommodation </h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <h6><?=$_participant_data_->need_accommodation_state == 1 ?"YES":"NO" ?></h6>
                                                </div>
                                            </div>
                                            

                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-lg-2"></div> -->
            </div>
        </div>

        <!-- Edit Link Modal  -->
        <div class="modal inmodal fade" id="activateModal<?=Hash::encryptToken($_data_->id)?>" tabindex="-1"
            role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">Approve Participant Registration</h4>
                    </div>
                    <form action="<?php linkto("pages/content/content_action.php"); ?>" method="post"
                        class="formCustom modal-form" id="activateForm">
                        <div class="modal-body">
                            <div id="activate-messages"></div>
                            <p class="text-center">Do you really want to Approve the registration of this participant:
                                <strong> <?=$_data_->firstname ?> </strong> ?
                            </p>
                            <div class="row">

                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="request" value="approveParticipantRegistration" />
                            <input type="hidden" name="page" id="page" value="profile" />
                            <input type="hidden" name="eventId" value="<?=Hash::encryptAuthToken($eventId) ?>" />
                            <input type="hidden" name="Id" value="<?=Hash::encryptToken($_data_->id) ?>" />
                            <button type="button" class="btn btn-white" data-dismiss="modal"><i
                                    class="fa fa-times-circle"></i> Close</button>
                            <button type="button" id="activateButton" class="btn btn-primary activateButtonDynamic"
                                data-loading-text="Loading..." data-key="<?=Hash::encryptToken($_data_->id)?>"
                                autocomplete="off"><i class="fa fa fa-external-link"></i> Approve Registration</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Link Modal  -->
        <div class="modal inmodal fade" id="deactivateModal<?=Hash::encryptToken($_data_->id)?>" tabindex="-1"
            role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">Deny Participant Registration</h4>
                    </div>
                    <form action="<?php linkto("pages/content/content_action.php"); ?>" method="post"
                        class="formCustom modal-form" id="deactivateForm">
                        <div class="modal-body">
                            <div id="deactivate-messages"></div>
                            <p class="text-center">Do you really want to Deny the registration of this participant:
                                <strong> <?=$_data_->firstname ?> </strong> ?
                            </p>
                            <div class="row">

                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="request" value="denyParticipantRegistration" />
                            <input type="hidden" name="page" id="page" value="profile" />
                            <input type="hidden" name="eventId" value="<?=Hash::encryptAuthToken($eventId) ?>" />
                            <input type="hidden" name="Id" value="<?=Hash::encryptToken($_data_->id) ?>" />
                            <button type="button" class="btn btn-white" data-dismiss="modal"><i
                                    class="fa fa-times-circle"></i> Close</button>
                            <button type="button" id="deactivateButton" class="btn btn-primary deactivateButtonDynamic"
                                data-loading-text="Loading..." data-key="<?=Hash::encryptToken($_data_->id)?>"
                                autocomplete="off"><i class="fa fa fa-external-link"></i> Deny Registration</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- <script type="text/javascript">
        var eventId = '<?php echo $eventId; ?>';
        var participationTypeToken = 'all';
        var linkto = '<?php linkto("pages/participants/participants_action.php"); ?>';
        </script>
        <script src="<?php linkto('pages/participants/participants.js'); ?>"></script> -->

        <?php include $INC_DIR . "footer.php"; ?>

    </div>
    </div>
</body>

</html>