<?php
    require_once "../../core/init.php"; 
    if(!$user->isLoggedIn()) {
        Redirect::to('login');
    }

    $page = "hotel";
    $link = "hotel";

    //Quick Rooms numbers
    $total = $active = $deactive = $activePercentage = $deactivePercentage = 0;
    $_filter_condition_ = "";
    $total = HotelController::getRoomsCount($_filter_condition_);
    if ($total > 0) {
        $_filter_condition_ = " AND hotel_room.status = 'ACTIVE'";
        $active = HotelController::getRoomsCount($_filter_condition_);

        $_filter_condition_ = "AND hotel_room.status = 'DEACTIVE'";
        $deactive = HotelController::getRoomsCount($_filter_condition_);

        $activePercentage = ($active*100)/$total;
        $deactivePercentage = ($deactive*100)/$total;
    }
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
                            <h5>Total Rooms</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins"><?=$total?></h1>
                            <div class="stat-percent font-bold text-default">100% <i class="fa fa-bolt"></i></div>
                            <small>Rooms</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-success pull-right">Active</span>
                            <h5>Active Rooms</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins"><?=$active?></h1>
                            <div class="stat-percent font-bold text-success"><?=number_format($activePercentage, 2)?>% <i class="fa fa-level-up"></i></div>
                            <small>Rooms</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-danger pull-right">Deactive</span>
                            <h5>Deactivated Rooms</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins"><?=$deactive?></h1>
                            <div class="stat-percent font-bold text-danger"><?=number_format($deactivePercentage, 2)?>% <i class="fa fa-level-up"></i></div>
                            <small>Rooms</small>
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
                            <table id="roomTable" class='table table-stripped table-hover customTable'>
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Picture</th>
                                    <th>Hotel name</th>
                                    <th>Room type</th>
                                    <th>Occupancy</th>
                                    <th>Adults</th>
                                    <th>Children</th>
                                    <th>Price</th>
                                    <th>Bed type</th>
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

        <!-- ADD ROOM -->
        <div class="modal inmodal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">Add Room</h4>
                    </div>
                    <form method="post" class="formCustom modal-form" id="addForm" enctype="multipart/form-data" novalidate="novalidate">
                        <div class="modal-body">
                            <div id="add-messages"></div>
                            <p>All <small class="red-color">*</small> fields are mandatory</p>
                            <div class="row" style="display: flex; flex-flow: row wrap;">
                                <div class="form-group control-group col-md-4">
                                    <label>Hotel<small class="red-color">*</small></label>
                                    <select class="form-control" name="hotel_id" id="hotel_id" required="required" data-validation-required-message="Please select">
                                        <option value="" selected="">Please select</option>
                                        <?php
                                        $_filter_condition_ = " AND status = 'ACTIVE'";
                                        $_HOTEL_DATA_ = HotelController::getHotels($_filter_condition_);
                                        if($_HOTEL_DATA_):
                                            foreach($_HOTEL_DATA_ As $hotel_):
                                        ?>
                                                <option value="<?=Hash::encryptToken($hotel_->id)?>"><?=$hotel_->name?></option>
                                                <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-4">
                                    <label>Room type<small class="red-color">*</small></label>
                                    <input type="text" name="room_type" id="room_type" class="form-control" required="required" placeholder="Room type" data-validation-required-message="Please enter room type"/>
                                    <p class="help-block"></p>
                                </div>
                                <!-- <div class="form-group control-group col-md-4">
                                    <label>Room type<small class="red-color">*</small></label>
                                    <select class="form-control" name="room_type" id="room_type" required="required" data-validation-required-message="Please select">
                                        <option value="" selected="">Please select</option>
                                        <option value="Superior Room">Superior Room</option>
                                        <option value="Executive Room">Executive Room</option>
                                        <option value="Deluxe">Deluxe</option>
                                        <option value="Standard Room">Standard Room</option>
                                        <option value="Junior Suite">Junior Suite</option>
                                        <option value="Classic Room City View">Classic Room City View</option>
                                        <option value="Classic Room Garden View">Classic Room Garden View</option>
                                        <option value="Twin Bed">Twin Bed</option>
                                        <option value="Classic Room Panorama View">Classic Room Panorama View </option>
                                        <option value="Presidential Suite">Presidential Suite</option>
                                        <option value="Classic Room">Classic Room</option>
                                        <option value="Classic Terrace">Classic Terrace </option>
                                        <option value="Deluxe Room">Deluxe Room </option>
                                        <option value="Executive Suite">Executive Suite</option>
                                        <option value="Two bedroom Apartment">Two bedroom Apartment</option>
                                        <option value="Three bedroom Apartment">Three bedroom Apartment</option>
                                        <option value="Four bedroom duplex">Four bedroom duplex </option>
                                        <option value="Penhouse 1 bedroom">Penhouse 1 bedroom</option>
                                        <option value="Penhouse  5 bedroom">Penhouse 5 bedroom</option>
                                        <option value="One Bedroom apartment">One Bedroom apartment</option>
                                        <option value="Prime Room">Prime Room</option>
                                        <option value="Single Deluxe King Room">Single Deluxe King Room</option>
                                    </select>
                                    <p class="help-block"></p>
                                </div> -->
                                <div class="form-group control-group col-md-4">
                                    <label>Room count<small class="red-color">*</small></label>
                                    <input type="number" name="room_count" id="room_count" class="form-control" required="required" placeholder="Room count" min="1" value="1" data-validation-required-message="Please enter room count"/>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-4">
                                    <label>Room price<small class="red-color">*</small></label>
                                    <input type="number" name="room_price" id="room_price" class="form-control" required="required" placeholder="Room price" min="1" value="0" data-validation-required-message="Please enter room price"/>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-4">
                                    <label>Currency<small class="red-color">*</small></label>
                                    <select class="form-control" name="currency" id="currency" required="required" data-validation-required-message="Please select">
                                        <option value="USD">USD</option>
                                        <option value="RWF">RWF</option>
                                    </select>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-4">
                                    <label>Bed type</label>
                                    <input type="text" name="bed_type" id="bed_type" class="form-control" placeholder="Bed type"/>
                                    <p class="help-block"></p>
                                </div>
                                 <div class="form-group control-group col-md-4">
                                    <label>Room occupancy<small class="red-color">*</small></label>
                                    <input type="number" name="room_occupancy" id="room_occupancy" class="form-control" required="required" placeholder="Number of rooms" min="1" value="1" data-validation-required-message="Please enter room occupancy"/>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-4">
                                    <label>Adults<small class="red-color">*</small></label>
                                    <input type="number" name="adults" id="adults" class="form-control" placeholder="Adults" min="1" value="3"/>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Children<small class="red-color">*</small></label>
                                    <input type="number" name="children" id="children" class="form-control" placeholder="Children" min="1" value="1"/>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-8">
                                    <label>Room description</label>
                                    <textarea name="room_description" id="room_description" class="form-control" placeholder="Room description" style="height: 90px;"></textarea>
                                    <p class="help-block"></p>
                                </div>
                                
                                <div class="form-group col-md-3">
                                    <label>Hotel picture</label>
                                    <div id="kv-avatar-errors-1" class="center-block" style="display:none;"></div>
                                    <div class="kv-avatar center-block">                            
                                        <input type="file" class="form-control" id="image" placeholder="Room picture" name="photo" class="file-loading" data-validation-required-message="Upload room picture" style="width:auto;"/>
                                    </div>
                                    <p class="help-block"></p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="request" value="addRoom"/> 
                            <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                            <button type="submit" id="addButton" class="btn btn-primary" data-loading-text="Loading..." autocomplete="off"><i class="fa fa fa-external-link"></i> Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- EDIT ROOM -->
        <div class="modal inmodal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">Edit Room</h4>
                    </div>
                    <form method="post" class="formCustom modal-form" id="editForm" enctype="multipart/form-data" novalidate="novalidate">
                        <div class="modal-body">
                            <div id="edit-messages"></div>
                            <p>All <small class="red-color">*</small> fields are mandatory</p>
                            <div class="row" style="display: flex; flex-flow: row wrap;">
                                <div class="form-group control-group col-md-4">
                                    <label>Hotel name<small class="red-color">*</small></label>
                                    <input type="text" name="hotel_name" id="ehotel_name" class="form-control" readonly/>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-4">
                                    <label>Room type<small class="red-color">*</small></label>
                                    <input type="text" name="room_type" id="eroom_type" class="form-control" required="required" placeholder="Room type" data-validation-required-message="Please enter room type"/>
                                    <p class="help-block"></p>
                                </div>
                                <!-- <div class="form-group control-group col-md-4">
                                    <label>Room type<small class="red-color">*</small></label>
                                    <select class="form-control" name="room_type" id="eroom_type" required="required" data-validation-required-message="Please select">
                                        <option value="" selected="">Please select</option>
                                        <option value="Superior Room">Superior Room</option>
                                        <option value="Executive Room">Executive Room</option>
                                        <option value="Deluxe">Deluxe</option>
                                        <option value="Standard Room">Standard Room</option>
                                        <option value="Junior Suite">Junior Suite</option>
                                        <option value="Classic Room City View">Classic Room City View</option>
                                        <option value="Classic Room Garden View">Classic Room Garden View</option>
                                        <option value="Twin Bed">Twin Bed</option>
                                        <option value="Classic Room Panorama View">Classic Room Panorama View </option>
                                        <option value="Presidential Suite">Presidential Suite</option>
                                        <option value="Classic Room">Classic Room</option>
                                        <option value="Classic Terrace">Classic Terrace </option>
                                        <option value="Deluxe Room">Deluxe Room </option>
                                        <option value="Executive Suite">Executive Suite</option>
                                        <option value="Two bedroom Apartment">Two bedroom Apartment</option>
                                        <option value="Three bedroom Apartment">Three bedroom Apartment</option>
                                        <option value="Four bedroom duplex">Four bedroom duplex </option>
                                        <option value="Penhouse 1 bedroom">Penhouse 1 bedroom</option>
                                        <option value="Penhouse  5 bedroom">Penhouse 5 bedroom</option>
                                        <option value="One Bedroom apartment">One Bedroom apartment </option>
                                    </select>
                                    <p class="help-block"></p>
                                </div> -->
                                <div class="form-group control-group col-md-4">
                                    <label>Room count<small class="red-color">*</small></label>
                                    <input type="number" name="room_count" id="eroom_count" class="form-control" required="required" placeholder="Room count" min="1" value="1" data-validation-required-message="Please enter room count"/>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-4">
                                    <label>Room price<small class="red-color">*</small></label>
                                    <input type="number" name="room_price" id="eroom_price" class="form-control" required="required" placeholder="Room price" min="1" value="0" data-validation-required-message="Please enter room price"/>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-4">
                                    <label>Currency<small class="red-color">*</small></label>
                                    <select class="form-control" name="currency" id="ecurrency" required="required" data-validation-required-message="Please select">
                                        <option value="USD">USD</option>
                                        <option value="RWF">RWF</option>
                                    </select>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-4">
                                    <label>Bed type</label>
                                    <input type="text" name="bed_type" id="ebed_type" class="form-control" placeholder="Bed type"/>
                                    <p class="help-block"></p>
                                </div>
                                 <div class="form-group control-group col-md-4">
                                    <label>Room occupancy<small class="red-color">*</small></label>
                                    <input type="number" name="room_occupancy" id="eroom_occupancy" class="form-control" required="required" placeholder="Number of rooms" min="1" value="1" data-validation-required-message="Please enter room occupancy"/>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-4">
                                    <label>Adults<small class="red-color">*</small></label>
                                    <input type="number" name="adults" id="eadults" class="form-control" placeholder="Adults" min="1" value="3"/>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Children<small class="red-color">*</small></label>
                                    <input type="number" name="children" id="echildren" class="form-control" placeholder="Children" min="1" value="1"/>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-12">
                                    <label>Room description</label>
                                    <textarea name="room_description" id="eroom_description" class="form-control" placeholder="Room description" style="height: 90px;"></textarea>
                                    <p class="help-block"></p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="request" value="editRoom"/> 
                            <input type="hidden" name="Id" id="editId" value=""/>
                            <input type="hidden" name="hotel_id" id="hotelId" value=""/>
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
        <script src="<?=DN?>/pages/hotel/room.js?v=<?=date('Y-m-d H:i:s')?>"></script>
        
        <?php include $INC_DIR . "footer.php"; ?>

        <script>
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
