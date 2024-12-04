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

$participation_type_name = $_participant_data_->participation_type_name;

if ($_participant_data_->form_id > 0) {
    $formDetails = TemplateController::formDetails($_participant_data_->form_id, "");
    $participation_type_name = $formDetails->form_name;
}

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
                        <a>Home</a>
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
                                                    <h6><?=$participation_type_name ?></h6>
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

                                    <h3 class="card-sect-title">PARTICIPANTS DETAILS </h3>
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <?php 
                                            $fullDetails=array();
                                            @$fullDetails= html_entity_decode($_participant_data_->full_details);
                                            if(json_decode($fullDetails)!=null){
                                                $fullDetails=json_decode($fullDetails);
                                                foreach($fullDetails as $key=>$value) { 
                                                    if ($key == "residence_country" || $key == "citizenship") {
                                                        $value = countryCodeToCountry($value);
                                                    }
                                                    $key = str_replace("_"," ",$key);
                                                    if(is_array($value)){
                                                        foreach($value as $value_name => $values_arr) {
                                            ?>
                                                        <div class="row">
                                                            <div class="col-sm-3">
                                                                <h6 class="mb-0" style="text-transform: capitalize;"><?=$key?></h6>
                                                            </div>
                                                            <div class="col-sm-9 text-secondary">
                                                                <h6><?=$values_arr?></h6>
                                                            </div>
                                                        </div>
                                                        <hr>
                                            <?php 
                                                        }
                                                    } else {
                                                        if ($key != 'formToken' AND $key != 'eventId' AND $key != 'eventParticipation' AND $key != 'request' AND $key != 'securityCode') {
                                            ?>
                                                        <div class="row">
                                                            <div class="col-sm-3">
                                                                <h6 class="mb-0" style="text-transform: capitalize;"><?=$key?></h6>
                                                            </div>
                                                            <div class="col-sm-9 text-secondary">
                                                                <h6><?=$value?></h6>
                                                            </div>
                                                        </div>
                                                        <hr>
                                            <?php 
                                                        }
                                                    }
                                                } 
                                            }
                                            ?>
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