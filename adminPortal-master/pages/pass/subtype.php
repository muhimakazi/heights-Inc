<?php
    require_once "../../core/init.php"; 
    if(!$user->isLoggedIn()) {
        Redirect::to('login');
    }

    $page = "pass";
    $link = "subtype";
?>

<!DOCTYPE html>
<html>

<head>
    <?php include $INC_DIR . "head.php"; ?>
    <style>
        .DTTT_container {
            display: none;
        }
        input[type="search"]  {
            width: 300px!important;
        }
    </style>
</head>

<body>
    <div id="wrapper">

        <?php include $INC_DIR . "nav.php"; ?>
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Events</h2>
                <ol class="breadcrumb">
                    <li><a href="<?php linkto(''); ?>">Home</a></li>
                    <li><a href="<?php linkto('pages/events/events_list'); ?>">Events</a></li>
                    <li><a>Website content</a></li>
                    <li class="active"><strong>Participation Subtype</strong></li>
                </ol>
            </div>
            <div class="col-lg-2">
                <!-- <button class="btn btn-xs btn-primary pull-right" data-toggle="modal" data-target="#addPartnerModal" id="addClient" style="margin-top: 50px;"><i class="fa fa fa-external-link"></i> Generate pivite link</button> -->
                <!-- type  modal -->
                
                <div class="modal inmodal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <h4 class="modal-title">Register Pass Subtype</h4>
                            </div>
                            <form method="post" class="formCustom modal-form" id="addForm">
                                <div class="modal-body">
                                    <div id="add-messages"></div>
                                    <p>All <small class="red-color">*</small> fields are mandatory</p>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group col-md-6">
                                                <label>Pass Type<small class="red-color">*</small></label>
                                                <select class="form-control" name="participation_type" id="participation_type" data-rule="required" data-msg="Please select  participation type"> 
                                                    <option value="" selected="">[--Select--]</option>
<?php
$_LIST_DATA_ = FutureEventController::getPacipationTypeyByEventID($eventId);
if($_LIST_DATA_): $count_ = 0;
    foreach($_LIST_DATA_  As $_participation_): $count_++;
  ?>    
                                                     <option value="<?=Hash::encryptAuthToken($_participation_->id)?>"><?=$_participation_->name?></option>
<?php
    endforeach;
endif;
?>
                                                    <div class="validate"></div>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Participation Subtype Name<small class="red-color">*</small></label>
                                                <input type="text" name="name" id="name" placeholder="subtype name" class="form-control" data-rule="required" data-msg="Please enter sub type name"/>
                                                <div class="validate"></div>
                                            </div>
                                            
                                            <div class="form-group col-md-6">
                                                <label>Category<small class="red-color">*</small></label>
                                                <select class="form-control" name="category" id="category" data-rule="required" data-msg="Please select category"> 
                                                    <option value="" selected="">[--Select--]</option>
                                                    <option value="INPERSON">In-person</option>
                                                    <option value="VIRTUAL">Virtual</option>
                                                </select>
                                                <div class="validate"></div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Payment status<small class="red-color">*</small></label>
                                                <select class="form-control" name="payment_state" id="payment_state" data-rule="required" data-msg="Please select  payment state"> 
                                                    <option value="" selected="">[--Select--]</option>
                                                    <option value="PAYABLE">Payable</option>
                                                    <option value="FREE">Free</option>
                                                </select>
                                                <div class="validate"></div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Price<small class="red-color">*</small></label>
                                                <input type="text" name="price" id="price" placeholder="Price" class="form-control" data-rule="required" data-msg="Please enter  price"/>
                                                <div class="validate"></div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Currency<small class="red-color">*</small></label>
                                                <select class="form-control" name="currency" id="currency" data-rule="required" data-msg="Please select  carrency"> 
                                                    <!-- <option value="" selected="">[--Select--]</option> -->
                                                    <option value="USD">USD</option>
                                                    <option value="RWF">RWF</option>
                                                </select>
                                                <div class="validate"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input type="hidden" name="request" value="registerPassSubType"/> 
                                    <input type="hidden" name="eventId" value="<?=Hash::encryptAuthToken($eventId) ?>"/>
                                    <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                                    <button type="submit" id="addButton" class="btn btn-primary" data-loading-text="Loading..." autocomplete="off"><i class="fa fa fa-external-link"></i> Register</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
           
            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row-flex" id="partners-list"></div>
            <div class="col-lg-4" style="margin-bottom: 10px;">
            </div>
            <div class="col-md-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <button class="btn btn-md btn-primary pull-right" data-toggle="modal" data-target="#addModal" ><i class="fa fa-plus-circle"></i> Register New Participation Subtype</button>
                        <h5>Participation Subtype List</h5>
                    </div>
                    <div class="ibox-content" id="list-pass-sub-types">
                        
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Link Modal  -->
        <div class="modal inmodal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">Edit Pass Subtype</h4>
                    </div>
                    <form method="post" class="formCustom modal-form" id="editForm">
                        <div class="modal-body">
                            <div id="edit-messages"></div>
                            <p>All <small class="red-color">*</small> fields are mandatory</p>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group col-md-6">
                                        <label>Pass Type<small class="red-color">*</small></label>
                                        <select class="form-control" name="participation_type" id="eparticipation_type" data-rule="required" data-msg="Please select  participation type"> 
                                            <option value="" selected="">[--Select--]</option>
<?php
$_LIST_DATA_ = FutureEventController::getPacipationTypeyByEventID($eventId);
if($_LIST_DATA_): $count_ = 0;
foreach($_LIST_DATA_  As $_participation_): $count_++;
?>    
                                             <option value="<?=Hash::encryptAuthToken($_participation_->id)?>"><?=$_participation_->name?></option>
<?php
endforeach;
endif;
?>
                                            <div class="validate"></div>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Pass Subtype Name<small class="red-color">*</small></label>
                                        <input type="text" name="name" id="ename" placeholder="subtype name" class="form-control" data-rule="required" data-msg="Please enter sub type name"/>
                                        <div class="validate"></div>
                                    </div>
                                    
                                    <div class="form-group col-md-6">
                                        <label>Category<small class="red-color">*</small></label>
                                        <select class="form-control" name="category" id="ecategory" data-rule="required" data-msg="Please select category"> 
                                            <!-- <option value="" selected="">[--Select--]</option> -->
                                            <option value="INPERSON">In-person</option>
                                            <option value="VIRTUAL">Virtual</option>
                                        </select>
                                        <div class="validate"></div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Payment status<small class="red-color">*</small></label>
                                        <select class="form-control" name="payment_state" id="epayment_state" data-rule="required" data-msg="Please select  payment state"> 
                                            <!-- <option value="" selected="">[--Select--]</option> -->
                                            <option value="PAYABLE">Payable</option>
                                            <option value="FREE">Free</option>
                                        </select>
                                        <div class="validate"></div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Price<small class="red-color">*</small></label>
                                        <input type="text" name="price" id="eprice" placeholder="Price" class="form-control" data-rule="required" data-msg="Please enter  price"/>
                                        <div class="validate"></div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Currency<small class="red-color">*</small></label>
                                        <select class="form-control" name="currency" id="ecurrency" data-rule="required" data-msg="Please select  carrency"> 
                                            <!-- <option value="" selected="">[--Select--]</option> -->
                                            <option value="USD">USD</option>
                                            <option value="RWF">RWF</option>
                                        </select>
                                        <div class="validate"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer"> 
                            <input type="hidden" name="request" value="editPassSubType"/> 
                            <input type="hidden" name="eventId" value="<?=Hash::encryptAuthToken($eventId) ?>"/>
                            <input type="hidden" id="passId" name="Id" value=""/>
                            <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                            <button type="submit" id="editButton" class="btn btn-primary editButton" data-loading-text="Loading..."  autocomplete="off"><i class="fa fa fa-external-link"></i> Save Modifications</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Confirm Modal  -->
        <div class="modal inmodal fade" id="confirmModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="confirmTitle"></h4>
                    </div>
                    <form method="post" class="formCustom modal-form" id="confirmForm" novalidate="novalidate">
                        <div class="modal-body">
                            <div id="confirm-messages"></div>
                            <p class="text-center" id="confirmQuestion"></p>
                            <div class="row">

                            </div>
                        </div>
                        <div class="modal-footer confirmFooter">
                            <input type="hidden" id="request" name="request" value="" />
                            <input type="hidden" name="Id" id="confirmId" value="" />
                            <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                            <button type="submit" id="confirmButton" class="btn btn-primary confirmButtonDynamic" data-loading-text="Loading..." autocomplete="off"><i class="fa fa fa-external-link"></i> Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            var linkto  = '<?=DN?>/pages/pass/pass_action.php';
        </script>
        <script src="<?=DN?>/js/jqBootstrapValidation.min.js"></script>
        <script src="<?=DN?>/pages/pass/subtype.js?v=<?=date('Y-m-d H:i:s')?>"></script>
        
        <?php include $INC_DIR . "footer.php"; ?>
        
        </div>
        </div>
</body>

</html>
