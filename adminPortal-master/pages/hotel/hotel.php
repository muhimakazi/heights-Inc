<?php
    require_once "../../core/init.php"; 
    if(!$user->isLoggedIn()) {
        Redirect::to('login');
    }

    $page = "hotel";
    $link = "hotel";

    //Quick Hotels numbers
    $total = $active = $deactive = $activePercentage = $deactivePercentage = 0;
    $_filter_condition_ = "";
    $total = HotelController::getHotelCount($_filter_condition_);
    if ($total > 0) {
        $_filter_condition_ = " AND status = 'ACTIVE'";
        $active = HotelController::getHotelCount($_filter_condition_);

        $_filter_condition_ = "AND status = 'DEACTIVE'";
        $deactive = HotelController::getHotelCount($_filter_condition_);

        $activePercentage = ($active*100)/$total;
        $deactivePercentage = ($deactive*100)/$total;
    }
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="<?=DN?>/css/select2.min.css">
    <link rel="stylesheet" href="<?=DN?>/css/flag-icon.min.css">
    <link rel="stylesheet" href="<?=DN?>/build/css/intlTelInput.css">
    <?php include $INC_DIR . "head.php"; ?>
    <style>
        .DTTT_container {
            display: none;
        }
        input[type="search"]  {
            width: 300px!important;
        }
        .form-group { margin-bottom: 0px;}
        .btn-group {width: 100%;}
        .btn-group button {border-radius: 3px!important}
        .btn-group button:first-child {margin-right: 10px}
        .text-green {color: green;}
        .text-red {color: red;}
    </style>
</head>

<body>
    <div id="wrapper">

        <?php include $INC_DIR . "nav.php"; ?>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-4">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-info pull-right">All</span>
                            <h5>Total Hotels</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins"><?=$total?></h1>
                            <div class="stat-percent font-bold text-default">100% <i class="fa fa-bolt"></i></div>
                            <small>Hotels</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-success pull-right">Active</span>
                            <h5>Active Hotels</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins"><?=$active?></h1>
                            <div class="stat-percent font-bold text-success"><?=number_format($activePercentage, 2)?>% <i class="fa fa-level-up"></i></div>
                            <small>Hotels</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-danger pull-right">Deactive</span>
                            <h5>Deactivated Hotels</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins"><?=$deactive?></h1>
                            <div class="stat-percent font-bold text-danger"><?=number_format($deactivePercentage, 2)?>% <i class="fa fa-level-up"></i></div>
                            <small>Hotels</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <?php if ($user->hasPermission('admin') || $user->hasPermission('client')) { ?>
                            <button class="btn btn-sm btn-primary pull-right" data-toggle="modal" data-target="#addModal" ><i class="fa fa-plus"></i> Add Hotel</button>
                            <?php } ?>
                        </div>
                        <div class="ibox-content">
                            <table id="hotelTable" class='table table-stripped table-hover customTable'>
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Picture</th>
                                    <th>Hotel name</th>
                                    <th>Email</th>
                                    <th>Telephone</th>
                                    <th>Country</th>
                                    <th>City</th>
                                    <th>Rate</th>
                                    <th>Address</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ADD HOTEL -->
        <div class="modal inmodal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">Add Hotel</h4>
                    </div>
                    <form method="post" class="formCustom modal-form" id="addForm" enctype="multipart/form-data" novalidate="novalidate">
                        <div class="modal-body">
                            <div id="add-messages"></div>
                            <p>All <small class="red-color">*</small> fields are mandatory</p>
                            <div class="row" style="display: flex; flex-flow: row wrap;">
                                <div class="form-group control-group col-md-4">
                                    <label>Hotel name<small class="red-color">*</small></label>
                                    <input type="text" name="name" id="name" class="form-control" required="required" placeholder="Hotel name" data-validation-required-message="Please enter hotel name"/>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-4">
                                    <label>Email<small class="red-color">*</small></label>
                                    <input type="text" name="email" id="email" class="form-control" required="required" placeholder="Hotel email" data-validation-required-message="Please enter hotel email"/>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-4">
                                    <label>Telephone<small class="red-color">*</small></label>
                                    <input type="hidden"  id="telephone_full" name ="telephone_full" value="">
                                    <input type="text" name="telephone" id="telephone" class="form-control" required="required" placeholder="Hotel telephone" data-validation-required-message="Please enter hotel telephone"/>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-4">
                                    <label>Country<small class="red-color">*</small></label>
                                    <select class="form-control" name="country" id="country" required="required" data-validation-required-message="Please select">
                                        <option value="" selected="">Please select</option>
                                        <?php $controller->country();?>
                                    </select>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-4">
                                    <label>City<small class="red-color">*</small></label>
                                    <input type="text" name="city" id="city" class="form-control" required="required" placeholder="Hotel city" data-validation-required-message="Please enter hotel city"/>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-4">
                                    <label>Address<small class="red-color">*</small></label>
                                    <input type="text" name="address" id="address" class="form-control" required="required" placeholder="Hotel address" data-validation-required-message="Please enter hotel address"/>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-4">
                                    <label>Rate star<small class="red-color">*</small></label>
                                    <select class="form-control" name="rate" id="rate" required="required" data-validation-required-message="Please select">
                                        <option value="" selected="">Please select</option>
                                        <option value="1">1 star</option>
                                        <option value="2">2 stars</option>
                                        <option value="3">3 stars</option>
                                        <option value="4">4 stars</option>
                                        <option value="5">5 stars</option>
                                    </select>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-3">
                                    <label>Hotel picture<small class="red-color">*</small></label>
                                    <div id="kv-avatar-errors-1" class="center-block" style="display:none;"></div>
                                    <div class="kv-avatar center-block">                            
                                        <input type="file" class="form-control" id="image" placeholder="Hotel picture" name="photo" class="file-loading" required="required" data-validation-required-message="Upload hotel picture" style="width:auto;"/>
                                    </div>
                                    <p class="help-block"></p>
                                    <!-- <p style="color: red; margin-top: 10px;">Image size: 160 × 160<br>Format: jpg or png file</p> -->
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="request" value="addHotel"/> 
                            <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                            <button type="submit" id="addButton" class="btn btn-primary" data-loading-text="Loading..." autocomplete="off"><i class="fa fa fa-external-link"></i> Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- EDIT HOTEL -->
        <div class="modal inmodal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">Edit Hotel</h4>
                    </div>
                    <form method="post" class="formCustom modal-form" id="editForm" enctype="multipart/form-data" novalidate="novalidate">
                        <div class="modal-body">
                            <div id="edit-messages"></div>
                            <p>All <small class="red-color">*</small> fields are mandatory</p>
                            <div class="row" style="display: flex; flex-flow: row wrap;">
                                <div class="form-group control-group col-md-4">
                                    <label>Hotel name<small class="red-color">*</small></label>
                                    <input type="text" name="name" id="ename" class="form-control" required="required" placeholder="Hotel name" data-validation-required-message="Please enter hotel name"/>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-4">
                                    <label>Email<small class="red-color">*</small></label>
                                    <input type="text" name="email" id="eemail" class="form-control" required="required" placeholder="Hotel email" data-validation-required-message="Please enter hotel email"/>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-4">
                                    <label>Telephone<small class="red-color">*</small></label>
                                    <input type="text" name="telephone" id="etelephone" class="form-control" required="required" placeholder="Hotel telephone" data-validation-required-message="Please enter hotel telephone"/>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-4">
                                    <label>Country<small class="red-color">*</small></label>
                                    <select class="form-control" name="country" id="ecountry" required="required" data-validation-required-message="Please select">
                                        <option value="">Please select</option>
                                        <?php $controller->country();?>
                                    </select>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-4">
                                    <label>City<small class="red-color">*</small></label>
                                    <input type="text" name="city" id="ecity" class="form-control" required="required" placeholder="Hotel city" data-validation-required-message="Please enter hotel city"/>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-4">
                                    <label>Address<small class="red-color">*</small></label>
                                    <input type="text" name="address" id="eaddress" class="form-control" required="required" placeholder="Hotel address" data-validation-required-message="Please enter hotel address"/>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-4">
                                    <label>Rate star<small class="red-color">*</small></label>
                                    <select class="form-control" name="rate" id="erate" required="required" data-validation-required-message="Please select">
                                        <option value="" selected="">Please select</option>
                                        <option value="1">1 star</option>
                                        <option value="2">2 stars</option>
                                        <option value="3">3 stars</option>
                                        <option value="4">4 stars</option>
                                        <option value="5">5 stars</option>
                                    </select>
                                    <p class="help-block"></p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="request" value="editHotel"/>
                            <input type="hidden" name="Id" id="editId" value=""/>
                            <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                            <button type="submit" id="editButton" class="btn btn-primary" data-loading-text="Loading..." autocomplete="off"><i class="fa fa fa-external-link"></i> Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- CONFIRM MODAL  -->
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
            var linkto  = '<?=DN?>/pages/hotel/hotel_action.php';
        </script>
        <script src="<?=DN?>/js/jqBootstrapValidation.min.js"></script>
        <script src="<?=DN?>/pages/hotel/hotel.js?v=<?=date('Y-m-d H:i:s')?>"></script>
        
        <?php include $INC_DIR . "footer.php"; ?>

        <script src="<?=DN?>/js/select2.min.js"></script>
        <script src="<?=DN?>/js/countries.js"></script>
        <script src="<?=DN?>/build/js/intlTelInput.js"></script>
        <script>
        var phone_number = window.intlTelInput(document.querySelector("#telephone"), {
            autoPlaceholder: "off",
            separateDialCode: true,
            preferredCountries: ["rw"],
            // initialCountry: "rw",
            hiddenInput: "full",
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
        });

        $("#image").fileinput({
            overwriteInitial: true,
            maxFileSize: 2500,
            showClose: false,
            showCaption: false,
            browseLabel: '',
            removeLabel: '',
            browseIcon: '<i class="glyphicon glyphicon-folder-open"></i> Upload',
            removeIcon: '<i class="glyphicon glyphicon-remove"></i> Delete',
            removeTitle: 'Cancel or reset changes',
            elErrorContainer: '#kv-avatar-errors-1',
            msgErrorClass: 'alert alert-block alert-danger',
            defaultPreviewContent: '<img src="<?=DN?>/img/photo_default.png" alt="Event banner" style="width:100%;">',
            layoutTemplates: {main2: '{preview} {remove} {browse}'},                                    
            allowedFileExtensions: ["jpg", "jpeg", "png", "JPG", "JPEG", "PNG"]
        });
        </script>
        
        </div>
        </div>
</body>

</html>
