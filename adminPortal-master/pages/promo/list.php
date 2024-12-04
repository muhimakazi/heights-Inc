<?php
    require_once "../../core/init.php"; 
    if(!$user->isLoggedIn()) {
        Redirect::to('login');
    }

    $page = "promo";
    $link = "list";
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
                <h2>Promo code</h2>
                <ol class="breadcrumb">
                    <li><a href="<?php linkto(''); ?>">Home</a></li>
                    <li><a>Promo code</a></li>
                    <li class="active"><strong>List</strong></li>
                </ol>
            </div>
            <div class="col-lg-2">
                <!-- <button class="btn btn-xs btn-primary pull-right" data-toggle="modal" data-target="#addPartnerModal" id="addClient" style="margin-top: 50px;"><i class="fa fa fa-external-link"></i> Generate pivite link</button> -->
                <!-- Generate Link modal -->
                <div class="modal inmodal fade" id="addPromoCodeModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <h4 class="modal-title">Add promo code</h4>
                            </div>
                            <form  action="<?php linkto("pages/promo/promo_action.php"); ?>" method="post" class="formCustom modal-form" id="addPromoCodeForm">
                                <div class="modal-body">
                                    <div id="add-messages"></div>
                                    <p>All <small class="red-color">*</small> fields are mandatory</p>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group col-md-6">
                                                <label>Participation Type<small class="red-color">*</small></label>
                                                <select class="form-control" name="participation_type" id="participation_type" data-rule="required" data-msg="Please select  category"> 
                                                    <option value="" selected="">[--Select--]</option>
<?php
$_CATEGORIES_DATA_ = FutureEventController::getPacipationTypeyByEventID($eventId, "PAYABLE");
if($_CATEGORIES_DATA_):
    foreach($_CATEGORIES_DATA_ As $_type_ ):
?>
                                                    <option value="<?=Hash::encryptAuthToken($_type_->id)?>"><?=$_type_->name?></option>
<?php
    endforeach;
endif;
?>      
                                                </select>
                                                <div class="validate"></div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Discount(%)<small class="red-color">*</small></label>
                                                <input type="number" name="discount" id="discount" class="form-control" data-rule="required" min="0" value="0" data-msg="Please enter discount rate"/>
                                                <div class="validate"></div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Promo code<small class="red-color">*</small></label>
                                                <input type="text" name="promo_code" id="promo_code" placeholder="Enter promo code" class="form-control" data-rule="required" data-msg="Please enter promo code"/>
                                                <div class="validate"></div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Total Registrations<small class="red-color">*</small></label>
                                                <input type="number" name="maximum_delegates" id="maximum_delegates" class="form-control" min="1" value="1" data-rule="required" data-msg="Please enter total"/>
                                                <div class="validate"></div>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>Organisation<small class="red-color">*</small></label>
                                                <input type="text" name="organisation" id="organisation" placeholder="Enter organisation name" class="form-control" data-rule="required" data-msg="Please enter organisation"/>
                                                <div class="validate"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input type="hidden" name="request" value="registerPromoCode"/> 
                                    <input type="hidden" name="eventId" value="<?=Hash::encryptAuthToken($eventId) ?>"/>
                                    <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                                    <button type="submit" id="addPromoCodeButton" class="btn btn-primary" data-loading-text="Loading..." autocomplete="off"><i class="fa fa fa-external-link"></i> Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row-flex" id="partners-list"></div>
            <div class="col-md-12">
                <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <?php if ($user->hasPermission('admin') || $user->hasPermission('client')) { ?>
                            <button class="btn btn-sm btn-primary pull-right" data-toggle="modal" data-target="#addPromoCodeModal" ><i class="fa fa-plus-circle"></i> Add promo code</button>
                            <?php } ?>
                        </div>
                    <div class="ibox-content" id="list-promo-codes">
                
                    </div>
                </div>
            </div>
        </div>

        <!-- EDIT PROMO CODE  -->
        <div class="modal inmodal fade" id="editPromoModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">Edit promo code</h4>
                    </div>
                    <form method="post" class="formCustom modal-form" id="editPromoForm" novalidate="novalidate">
                        <div class="modal-body">
                            <div id="edit-messages"></div>
                            <p>All <small class="red-color">*</small> fields are mandatory</p>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group col-md-6">
                                        <label>Participation Type<small class="red-color">*</small></label>
                                        <select class="form-control" name="participation_type" id="edit_participation_type" data-rule="required" data-msg="Please select  category"> 
                                            <option value="" selected="">[--Select--]</option>
<?php
$_CATEGORIES_DATA_ = FutureEventController::getPacipationTypeyByEventID($eventId, "PAYABLE");
if($_CATEGORIES_DATA_):
foreach($_CATEGORIES_DATA_ As $_type_ ):
?>
                                            <option value="<?=Hash::encryptAuthToken($_type_->id)?>"><?=$_type_->name?></option>
<?php
endforeach;
endif;
?>      
                                        </select>
                                        <div class="validate"></div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Discount(%)<small class="red-color">*</small></label>
                                        <input type="number" name="discount" id="edit_discount" class="form-control" data-rule="required" min="0" value="0" data-msg="Please enter discount rate"/>
                                        <div class="validate"></div>
                                    </div>
                                    <div class="form-group col-md-6 control-group">
                                        <label>Promo code<small class="red-color">*</small></label>
                                        <input type="text" name="promo_code" id="edit_promo_code" placeholder="Enter promo code" class="form-control" data-rule="required" data-msg="Please enter promo code" required="required" data-validation-required-message="Please enter promo code"/>
                                        <p class="help-block"></p>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Total Registrations<small class="red-color">*</small></label>
                                        <input type="number" name="maximum_delegates" id="edit_total" class="form-control" min="1" value="1" data-rule="required" data-msg="Please enter total"/>
                                        <div class="validate"></div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Organisation<small class="red-color">*</small></label>
                                        <input type="text" name="organisation" id="edit_organisation" placeholder="Enter organisation name" class="form-control" data-rule="required" data-msg="Please enter organisation"/>
                                        <div class="validate"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer"> 
                            <input type="hidden" name="request" value="editPromoCode"/> 
                            <input type="hidden" name="eventId" value="<?=Hash::encryptAuthToken($eventId) ?>"/>
                            <input type="hidden" id="editId" name="Id" value=""/>
                            <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                            <button type="submit" id="editPromoButton" class="btn btn-primary editPromoButton" data-loading-text="Loading..." autocomplete="off"><i class="fa fa fa-external-link"></i> Save Modifications</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

                <!-- Acitivate promo Modal  -->
                <div class="modal inmodal fade" id="activatePromoModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <h4 class="modal-title">Activate  promo code</h4>
                            </div>
                            <form method="post" class="formCustom modal-form" id="activatePromoForm" novalidate="novalidate">
                                <div class="modal-body">
                                    <div id="activate-messages"></div>
                                    <p class="text-center">Do you really want to activate this promo code</p>
                                    <div class="row">
                                    </div>
                                </div>
                                <div class="modal-footer"> 
                                    <input type="hidden" name="request" value="activatePromoCode"/> 
                                    <input type="hidden" name="eventId" value="<?=Hash::encryptAuthToken($eventId) ?>"/>
                                    <input type="hidden" id="activateId" name="Id" value=""/>
                                    <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                                    <button type="submit" id="activatePromoButton" class="btn btn-primary activatePromoButton" data-loading-text="Loading..." autocomplete="off"><i class="fa fa fa-external-link"></i> Activate</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal inmodal fade" id="deactivatePromoModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <h4 class="modal-title">Deactivate promo code</h4>
                            </div>
                            <form method="post" class="formCustom modal-form" id="deactivatePromoCodeForm" novalidate="novalidate">
                                <div class="modal-body">
                                    <div id="deactivate-messages"></div>
                                    <p class="text-center">Do you really want to deactivate this promo code? </strong> ?</p>
                                    <div class="row">
                                    </div>
                                </div>
                                <div class="modal-footer"> 
                                    <input type="hidden" name="request" value="deactivatePromoCode"/> 
                                    <input type="hidden" name="eventId" value="<?=Hash::encryptAuthToken($eventId) ?>"/>
                                    <input type="hidden" id="deactivateId" name="Id" value=""/>
                                    <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                                    <button type="submit" id="deactivatePromoCodeButton" class="btn btn-primary deactivatePromoCodeButton" data-loading-text="Loading..." autocomplete="off"><i class="fa fa fa-external-link"></i> Deactivate</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        
        <script type="text/javascript">
            var linkto  = '<?=DN?>/pages/promo/promo_action.php';
        </script>
        <script src="<?=DN?>/js/jqBootstrapValidation.min.js"></script>
        <script src="<?=DN?>/pages/promo/promo.js?v=<?=date('Y-m-d H:i:s')?>"></script>
        
        <?php include $INC_DIR . "footer.php"; ?>
        
        </div>
        </div>
</body>

</html>
