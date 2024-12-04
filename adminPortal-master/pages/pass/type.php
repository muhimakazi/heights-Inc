<?php
    require_once "../../core/init.php"; 
    if(!$user->isLoggedIn()) {
        Redirect::to('login');
    }

    $page = "pass";
    $link = "type";
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
                    <li><a href="<?=DN?>">Home</a></li>
                    <li><a>Events</a></li>
                    <li class="active"><strong>Pass Type</strong></li>
                </ol>
            </div>
            <div class="col-lg-2">
                <!-- <button class="btn btn-xs btn-primary pull-right" data-toggle="modal" data-target="#addPartnerModal" id="addClient" style="margin-top: 50px;"><i class="fa fa fa-external-link"></i> Generate pivite link</button> -->
                <!-- Generate Link modal -->
                
                <div class="modal inmodal fade" id="addPassTypeModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <h4 class="modal-title">Register Pass Type</h4>
                            </div>
                            <form method="post" class="formCustom modal-form" id="addPassTypeForm">
                                <div class="modal-body">
                                    <div id="add-messages"></div>
                                    <p>All <small class="red-color">*</small> fields are mandatory</p>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group col-md-12">
                                                <label>Name<small class="red-color">*</small></label>
                                                <input type="text" name="name" id="name" placeholder="Attendence name" class="form-control" data-rule="required" data-msg="Please enter name"/>
                                                <div class="validate"></div>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>Payment  status<small class="red-color">*</small></label>
                                                <select class="form-control" name="payment_state" id="payment_state" data-rule="required" data-msg="Please select  payment state"> 
                                                    <option value="" selected="">[--Select--]</option>
                                                    <option value="PAYABLE">Payable</option>
                                                    <option value="FREE">Free</option>
                                                </select>
                                                <div class="validate"></div>
                                            </div>
                                            
                                            <div class="form-group col-md-12">
                                                <label>Visibility<small class="red-color">*</small></label>
                                                <select class="form-control" name="visibility_state" id="visibility_state" data-rule="required" data-msg="Please select  visibility"> 
                                                    <option value="" selected="">[--Select--]</option>
                                                    <option value="1">PUBLIC</option>
                                                    <option value="0">PRIVATE</option>
                                                </select>
                                                <div class="validate"></div>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>Registration Form Layout Number<small class="red-color">*</small></label>
                                                <input type="number" name="form_order" id="form_order" placeholder="Registration Form Layout Number" class="form-control" data-rule="required" data-msg="Please enter form layout number"/>
                                                <div class="validate"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input type="hidden" name="request" value="registerPassType"/> 
                                    <input type="hidden" name="eventId" value="<?=Hash::encryptAuthToken($eventId) ?>"/>
                                    <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                                    <button type="submit" id="addPassTypeButton" class="btn btn-primary" data-loading-text="Loading..." autocomplete="off"><i class="fa fa fa-external-link"></i> Register</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <!-- <div class="row-flex" id="partners-list"></div> -->
            <div class="col-md-12">
                <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <button class="btn btn-md btn-primary pull-right" data-toggle="modal" data-target="#addPassTypeModal" ><i class="fa fa-plus-circle"></i> New pass type</button>
                            <h5>Pass Type List</h5>
                        </div>
                    <div class="ibox-content" id="list-pass-types">
                        
                    </div>
                </div>
            </div>
        </div>


        
          <!-- Edit pass Modal  -->
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
                                    <div class="form-group col-md-12">
                                        <label>Name<small class="red-color">*</small></label>
                                        <input type="text" name="name" value="" id="ename" placeholder="Pass Name" class="form-control" data-rule="required" data-msg="Please enter name"/>
                                        <div class="validate"></div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Payment  status<small class="red-color">*</small></label>
                                        <select class="form-control" name="payment_state" id="epayment_state" data-rule="required" data-msg="Please select  payment state"> 
                                            <option value="" selected="">[--Select--]</option>
                                            <option value="PAYABLE">Payable</option>
                                            <option value="FREE">Free</option>
                                        </select>
                                        <div class="validate"></div>
                                    </div>
                                    
                                    <div class="form-group col-md-12">
                                        <label>Visibility<small class="red-color">*</small></label>
                                        <select class="form-control" name="visibility_state" id="evisibility_state" data-rule="required" data-msg="Please select  visibility"> 
                                            <option value="" selected="">[--Select--]</option>
                                            <option value="1">PUBLIC</option>
                                            <option value="0">PRIVATE</option>
                                        </select>
                                        <div class="validate"></div>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>Registration Form Layout Number<small class="red-color">*</small></label>
                                        <input type="number" name="form_order" value=""  id="eform_order" placeholder="Registration Form Layout Number" class="form-control" data-rule="required" data-msg="Please enter form layout number"/>
                                        <div class="validate"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer"> 
                            <input type="hidden" name="request" value="editPassType"/> 
                            <input type="hidden" name="eventId" value="<?=Hash::encryptAuthToken($eventId)?>"/>
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
        <script src="<?=DN?>/pages/pass/type.js?v=<?=date('Y-m-d H:i:s')?>"></script>
        
        <?php include $INC_DIR . "footer.php"; ?>
        
        </div>
        </div>
</body>

</html>
