<?php
require_once "../../core/init.php"; 
if(!$user->isLoggedIn()) {
    Redirect::to('login');
}

$page = "payments";
$link = "list";

$_filter_condition_ = "";
$totalPayment = ReportController::getPaymentStatsRegistrationCount($eventId, $_filter_condition_);
if ($totalPayment > 0) {
    $exportBtn = '';
} else {
    $exportBtn = 'disabled';
}

/** Get Payment Stats  - ONLINE PAYMENT -*/
$_PAYMENT_STATS_['ONLINE_PAYMENT']['COUNT_REGISTRATION']      = number_format(PaymentController::getPaymentStatsRegistrationByPaymentChannelCount($eventId, "ONLINE_PAYMENT", ""));
$_PAYMENT_STATS_['ONLINE_PAYMENT']['COUNT_COMPLETED_PAYMENT'] = number_format(PaymentController::getPaymentStatsRegistrationByPaymentChannelCount($eventId, "ONLINE_PAYMENT", "COMPLETED"));
$_PAYMENT_STATS_['ONLINE_PAYMENT']['AMOUNT_PAID']             = number_format(PaymentController::getPaymentStatsRegistrationByPaymentChannelAmount($eventId, "ONLINE_PAYMENT", ""));

/** Get Payment Stats  - ONLINE PAYMENT -*/
$_PAYMENT_STATS_['BANK_TRANSFER']['COUNT_REGISTRATION']      = number_format(PaymentController::getPaymentStatsRegistrationByPaymentChannelCount($eventId, "BANK_TRANSFER", ""));
$_PAYMENT_STATS_['BANK_TRANSFER']['COUNT_COMPLETED_PAYMENT'] = number_format(PaymentController::getPaymentStatsRegistrationByPaymentChannelCount($eventId, "BANK_TRANSFER", "COMPLETED"));
$_PAYMENT_STATS_['BANK_TRANSFER']['AMOUNT_PAID']             = number_format(PaymentController::getPaymentStatsRegistrationByPaymentChannelAmount($eventId, "BANK_TRANSFER", ""));

/** Event Payment Currency */
$_EVENT_PAYMENT_CURRENCY_ = PaymentController::getEventPaymentCurrency($eventId);
?>

<!DOCTYPE html>
<html>

<head>
    <?php include $INC_DIR . "head.php"; ?>
    <script src="<?php linkto('js/jquery-2.1.1.js'); ?>"></script>
    <style>
        .label-dark {
            background-color: #2c4897;
            color: #FFFFFF;
        }
        .DTTT_container {
            display: none;
        }
        input[type="search"]  {
            width: 300px!important;
/*            height: 40px;*/
        }
    </style>
</head>

<body>
    <div id="wrapper">

        <?php include $INC_DIR . "nav.php"; ?>

        <div class="wrapper wrapper-content" style="padding: 25px 0;">
            <div class="container-fluid">
                <div class="row">                    
                    <div class="col-lg-6">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <a href="#" class="btn btn-xs btn-primary pull-right"><i class="fa fa-eye "></i> View</a>
                                <h5>Via Payment Online Platform</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins" style="font-size: 15px;">
                                    <span>Registration: <strong><?= $_PAYMENT_STATS_['ONLINE_PAYMENT']['COUNT_REGISTRATION'] ?></strong> </span>
                                    <span class="text-default"> | </span>
                                    <!-- <span>Pending: <strong>547</strong> </span>
                                    <span class="text-default"> | </span> -->
                                    <span>Completed Payment: <strong><?= $_PAYMENT_STATS_['ONLINE_PAYMENT']['COUNT_COMPLETED_PAYMENT'] ?></strong> </span>
                                    <span class="text-default"> | </span>
                                    <span>Total Paid: <strong> <small> <strong><?= $_EVENT_PAYMENT_CURRENCY_ ?></strong> </small><?= $_PAYMENT_STATS_['ONLINE_PAYMENT']['AMOUNT_PAID'] ?></strong> </span>
                                    <!-- <span class="text-default"> | </span> -->
                                    <span class="pull-right"><i class="fa fa-bar-chart-o"></i></span>
                                </h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <a href="#" class="btn btn-xs btn-info pull-right"><i class="fa fa-eye"></i> View</a>
                                <h5>Via Bank Transfer</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins" style="font-size: 15px;">
                                    <span>Registration: <strong><?= $_PAYMENT_STATS_['BANK_TRANSFER']['COUNT_REGISTRATION'] ?></strong> </span>
                                    <span class="text-default"> | </span>
                                    <!-- <span>Pending: <strong>547</strong> </span>
                                    <span class="text-default"> | </span> -->
                                    <span>Completed Payment: <strong><?= $_PAYMENT_STATS_['BANK_TRANSFER']['COUNT_COMPLETED_PAYMENT'] ?> </strong> </span>
                                    <span class="text-default"> | </span>
                                    <span>Total Paid: <strong> <small> <strong><?= $_EVENT_PAYMENT_CURRENCY_ ?></strong> </small> <?= $_PAYMENT_STATS_['BANK_TRANSFER']['AMOUNT_PAID'] ?></strong> </span>
                                    <!-- <span class="text-default"> | </span> -->
                                    <span class="pull-right"><i class="fa fa-bar-chart-o"></i></span>
                                </h1>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            
                            <!-- <div class="iboxx-title" style="height: auto;">
                                Filter By:
                            </div> -->

                            <div class="ibox-content" style="padding: 15px 20px 0px 20px;">
                            <h4>Filter By:</h4>
                            <form action="" id="filterForm" class="row" method='post'>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select id="type" name="type" onchange="filterOptionsSubtype(this);"  class="form-control" data-rule="required" data-msg="Please select type">
                                            <option value="">- Select Participation Type -</option>
                                            <option value="">All</option>
        <?php
        $_TYPE_DATA_ = FutureEventController::getPacipationTypeyByEventID($eventId, 'PAYABLE');
        if($_TYPE_DATA_):
            foreach($_TYPE_DATA_ As $type_):
        ?>  
                                            <option value="<?=Hash::encryptToken($type_->id)?>"><?=$type_->name?></option>
        <?php
            endforeach;
        endif;
        ?>
                                        </select>
                                    </div>  
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select id="subtype" name="subtype"  class="form-control" data-rule="required" data-msg="Please select subtype">
                                            <option value="">- Select Participation Subtype -</option>
                                            <option value="">All</option>

                                        </select>
                                    </div>  
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select id="status" name="status"  class="form-control" data-rule="required" data-msg="Please select status">
                                            <option value="">- Select Payment Status -</option>
                                            <option value="">All</option>
                                            <option value="PENDING">Pending</option>
                                            <option value="COMPLETED">Completed</option>

                                        </select>
                                    </div>  
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select id="payment_channel" name="payment_channel"  class="form-control" data-rule="required" data-msg="Please select status">
                                            <option value="">- Select Payment Channel -</option>
                                            <option value="">All</option>
                                            <option value="ONLINE_PAYMENT">Online Payment</option>
                                            <option value="BANK_TRANSFER">Bank Transfer</option>

                                        </select>
                                    </div>  
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Country:</label>
                                        <select id="country" name="country" class="form-control" data-rule="required"
                                            data-msg="Please select status">
                                            <option value="">- Select Country -</option>
                                            <option value="">All</option>
                                            <option value="Local">Local</option>
                                            <option value="International">International</option>

                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">From:</label>
                                        <input type="date" class="form-control" name="datefrom" id="datefrom">
                                    </div>  
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="">To:</label>
                                            <input type="date" class="form-control"  name="dateto" id="dateto">
                                        </div>  
                                    </div>  
                                </div>
                                <!-- <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="">From:</label>
                                            <br>
                                            <button type="submit" style="border-radius: 0px;"  autocomplete="off" class="btn btn-md btn-primary col-md-2"> <i class=" fa fa-filter"></i> Filter</button>
                                        </div>  
                                    </div>  
                                </div> -->
                                <div class="col-md-1" style="padding-top: 22px;">
                                    <input type="hidden" name="eventId" id="eventId" value="<?=$eventId?>">
                                    <input type="hidden" name="request" id="request" value="fetchParticitants">
                                    <button type="submit" style="border-radius: 0px; padding: 6px 20px"  autocomplete="off" class="btn btn-md btn-primary"> <i class=" fa fa-filter"></i> Filter </button>
                                </div>
                                <?php if ($user->hasPermission('admin') || $user->hasPermission('event-admin')) { ?>
                                <div class="col-md-1">
                                    <label for="" style="visibility: hidden;">Export</label>
                                    <button class="btn btn-primary exportBtn" style="border-radius: 0px; padding: 6px 20px" <?=$exportBtn?>><i class="fa fa-download"></i> Export</button>
                                </div>
                                <?php } ?>
                            </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-content" id="participants-table">
                                <table id="paymentTable" class='table customTable'>
                                    <thead>
                                    <tr>
                                        <th> </th>
                                        <th>Transaction ID</th>
                                        <th>Receipt ID</th>
                                        <th>Participant</th>
                                        <th>Type</th>
                                        <th>Subtype</th>
                                        <th>Job title</th>
                                        <th>Organisation</th>
                                        <th>Channel</th>
                                        <th>Amount</th>
                                        <th>Datetime</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal inmodal fade" id="confirmModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">Bank Transfer Approval</h4>
                    </div>
                    <form action="" method="post" class="formCustom modal-form" id="confirmForm">
                        <div class="modal-body">
                            <div id="confirm-messages"></div>
                            <p class="text-center">Do you really want to Approve and Complete the Bank Transfer Transaction of this participant?</p>
                            <div class="row">
                                <hr>
                                <div class="col-md-1"></div>
                                <div class="form-group col-md-11">
                                    <label for="">Payment Receipt ID <span class="text-danger">*</span>  </label>
                                    <input type="text" class="form-control" name="receipt" id="receipt" placeholder="This Receipt ID is mandatory to approve this bank transfer." required="required" data-validation-required-message="Please enter Receipt ID"/>
                                    <p class="help-block"></p>
                                </div>
                                <div class="col-md-1"></div>
                                <div class="form-group col-md-11">
                                    <label for="">Payment Comment <span class="text-danger"></span>  </label>
                                    <textarea  class="form-control" name="approval_comment" id="approval_comment" placeholder="Some comment about this bank transfer."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer confirmFooter"> 
                            <input type="hidden" name="request" value="approveBankTransferTransaction"/> 
                            <input type="hidden" name="page" id="page" value="list"/>
                            <input type="hidden" name="Id" id="confirmId" value=""/>
                            <input type="hidden" name="eventId" value="<?=Hash::encryptAuthToken($eventId) ?>"/>
                            <input type="hidden" name="participant" id="apbtParticipantId" value=""/>
                            <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                            <button type="submit" id="confirmButton" class="btn btn-primary confirmButtonDynamic" data-loading-text="Loading..." autocomplete="off"><i class="fa fa fa-external-link"></i> Approve Bank Transfer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- Edit Link Modal  -->
        <div class="modal inmodal fade" id="refundModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">Refund Participant</h4>
                    </div>
                    <form action="" method="post" class="formCustom modal-form" id="refundForm">
                        <div class="modal-body">
                            <div id="refund-messages"></div>
                            <p class="text-center">Do you really want to refund this participant?</p>
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="form-group col-md-11">
                                    <label for="">Refund Comment <span class="text-danger"></span>  </label>
                                    <textarea  class="form-control" name="approval_comment" id="approval_comment" placeholder="Some comment about this bank transfer."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer"> 
                            <input type="hidden" name="request" value="refundPayment"/> 
                            <input type="hidden" name="page" id="page" value="list"/> 
                            <input type="hidden" name="eventId" value="<?=Hash::encryptAuthToken($eventId) ?>"/>
                            <input type="hidden" name="participant" id="refParticipantId" value=""/>
                            <input type="hidden" name="Id" id="refundId" value=""/>
                            <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                            <button type="submit" id="refundButton" class="btn btn-primary refundButtonDynamic" data-loading-text="Loading..." autocomplete="off"><i class="fa fa fa-external-link"></i> Refund Payment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        


        <script type="text/javascript">
            var linkto  = '<?=DN?>/pages/payments/payment_action.php';
        </script>

        <script src="<?=DN?>/js/jqBootstrapValidation.min.js"></script>
        <script src="<?=DN?>/pages/payments/payment.js?v=<?=date('Y-m-d H:i:s')?>"></script>
        
        <?php include $INC_DIR . "footer.php"; ?>

        </div>
        </div>
</body>

</html>
