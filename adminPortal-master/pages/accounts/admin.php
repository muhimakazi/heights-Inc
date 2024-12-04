<?php
    require_once "../../core/init.php"; 
    if(!$user->isLoggedIn()) {
        Redirect::to('login');
    }
    if (!$user->hasPermission('admin')) {
        Redirect::to('index');
    }

    $page = "accounts";
    $link = "admin";

    //Quick users numbers
    $total = $active = $blocked = $activePercentage = $blockedPercentage = 0;
    $_filter_condition_ = "";
    $total = ReportController::getTotalUsers($_filter_condition_);
    if ($total > 0) {
        $_filter_condition_ = " AND status = 'ACTIVE'";
        $active = ReportController::getTotalUsers($_filter_condition_);

        $_filter_condition_ = "AND status = 'BLOCKED'";
        $blocked = ReportController::getTotalUsers($_filter_condition_);

        $activePercentage = ($active*100)/$total;
        $blockedPercentage = ($blocked*100)/$total;
    }

?>

<!DOCTYPE html>
<html>

<head>
    <?php include $INC_DIR . "head.php"; ?>
    <script src="<?php linkto('js/jquery-2.1.1.js'); ?>"></script>
    <style>
        .form-group { margin-bottom: 0px;}
        .btn-group {width: 100%;}
        .btn-group button:first-child {width: 40%;}
        .btn-group button:nth-child(2) {width: 60%;}
        .text-green {color: green;}
        .text-red {color: red;}
        .DTTT_container {display: none;}
        input[type="search"] { width: 300px !important;}
    </style>
</head>

<body>
    <div id="wrapper">

        <?php include $INC_DIR . "nav.php"; ?>

        <div class="wrapper wrapper-content" style="padding: 25px 0;">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-info pull-right">All</span>
                                <h5>Total users</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?=$total?></h1>
                                <div class="stat-percent font-bold text-default">100% <i class="fa fa-bolt"></i></div>
                                <small>Users</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-success pull-right">Active</span>
                                <h5>Active users</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?=$active?></h1>
                                <div class="stat-percent font-bold text-success"><?=number_format($activePercentage, 2)?>% <i class="fa fa-level-up"></i></div>
                                <small>Users</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-danger pull-right">Blocked</span>
                                <h5>Blocked users</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?=$blocked?></h1>
                                <div class="stat-percent font-bold text-danger"><?=number_format($blockedPercentage, 2)?>% <i class="fa fa-level-up"></i></div>
                                <small>Users</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <button class="btn btn-sm btn-primary pull-right" data-toggle="modal" data-target="#addUserModal" id="addUser"><i class="fa fa-plus-circle"></i> Add new user</button>
                                <h5>Users List</h5>
                            </div>

                            <div class="ibox-content">
                                <table id="userTable" class='table table-hover customTable'>
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>Created date</th>
                                        <th>First name</th>
                                        <th>Last name</th>
                                        <th>Email</th>
                                        <th>Category</th>
                                        <th>Client</th>
                                        <th>Status</th>
                                        <th class="text-center" style="width: 12%">Action</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ADD NEW USER MODAL -->
        <div class="modal inmodal fade" id="addUserModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <i class="fa fa-user modal-icon"></i>
                        <h4 class="modal-title">Add new user</h4>
                    </div>
                    <form class="formCustom" id="addUserForm">
                        <div class="modal-body">
                            <div id="add-user-messages"></div>
                            <p>All <small class="red-color">*</small> fields are mandatory</p>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group col-md-6">
                                        <label>First name<small class="red-color">*</small></label>
                                        <input type="text" name="firstname" id="firstname"
                                            placeholder="First name" class="form-control" data-rule="required"
                                            data-msg="Please enter first name" />
                                        <div class="validate"></div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Last name<small class="red-color">*</small></label>
                                        <input type="text" name="lastname" id="lastname" placeholder="Last name"
                                            class="form-control" data-rule="required"
                                            data-msg="Please enter last name" />
                                        <div class="validate"></div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Email<small class="red-color">*</small></label>
                                        <input type="text" name="username" id="username" placeholder="Email address"
                                            class="form-control" data-rule="email" data-msg="Please enter a valid email" />
                                        <div class="validate"></div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Account Type<small class="red-color">*</small></label>
                                        <select name="account_type" id="account_type" class="form-control" data-rule="required" data-msg="Please select">
                                            <option value="" selected="">Please Select</option>
                                            <?php 
                                                $controller->get('groups', 'id,name,permissions', NULL, "", 'id DESC');
                                                foreach ($controller->data() as $resGroup) { 
                                                    echo "<option value='".Hash::encryptToken($resGroup->id)."'>".$resGroup->name."</option>";
                                                }
                                            ?>
                                        </select>
                                        <div class="validate"></div>
                                    </div>
                                    <div class="col-md-12" style="display: none;">
                                        <label>Client Name <small class="red-color">*</small></label>
                                        <select name="client_id" id="client_id" class="form-control" data-rule="required" data-msg="Please select">
                                            <option value="" selected="">Please Select</option>
                                            <?php 
                                                $controller->get('future_client', 'id, organisation', NULL, "", 'id DESC');
                                                foreach ($controller->data() as $resClient) { 
                                                    echo "<option value='".Hash::encryptToken($resClient->id)."'>".$resClient->organisation."</option>";
                                                }
                                            ?>
                                        </select>
                                        <div class="validate"></div>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="request" value="creationAdminAccount" /> 
                            <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                            <button type="submit" id="addUserButton" class="btn btn-primary" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-check-circle"></i> Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- EDIT USER MODAL -->
        <div class="modal inmodal fade" id="editUserModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <i class="fa fa-user modal-icon"></i>
                        <h4 class="modal-title">Edit user</h4>
                    </div>
                    <form class="formCustom" id="editUserForm">
                        <div class="modal-body">
                            <div id="edit-user-messages"></div>
                            <p>All <small class="red-color">*</small> fields are mandatory</p>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group col-md-6">
                                        <label>First name<small class="red-color">*</small></label>
                                        <input type="text" name="firstname" id="efirstname"
                                            placeholder="First name" class="form-control" data-rule="required"
                                            data-msg="Please enter first name" />
                                        <div class="validate"></div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Last name<small class="red-color">*</small></label>
                                        <input type="text" name="lastname" id="elastname" placeholder="Last name"
                                            class="form-control" data-rule="required"
                                            data-msg="Please enter last name" />
                                        <div class="validate"></div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Email<small class="red-color">*</small></label>
                                        <input type="text" name="username" id="eusername" placeholder="Email address"
                                            class="form-control" data-rule="email" data-msg="Please enter a valid email" />
                                        <div class="validate"></div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Account Type<small class="red-color">*</small></label>
                                        <select name="account_type" id="eaccount_type" class="form-control" data-rule="required" data-msg="Please select">
                                            <option value="" selected="">Please Select</option>
                                            <?php 
                                                $controller->get('groups', 'id,name,permissions', NULL, "", 'id DESC');
                                                foreach ($controller->data() as $resGroup) { 
                                                    echo "<option value='".Hash::encryptToken($resGroup->id)."'>".$resGroup->name."</option>";
                                                }
                                            ?>
                                        </select>
                                        <div class="validate"></div>
                                    </div>
                                    <div class="col-md-12" style="display: none;">
                                        <label>Client Name <small class="red-color">*</small></label>
                                        <select name="client_id" id="eclient_id" class="form-control" data-rule="required" data-msg="Please select">
                                            <option value="" selected="">Please Select</option>
                                            <?php 
                                                $controller->get('future_client', 'id, organisation', NULL, "", 'id DESC');
                                                foreach ($controller->data() as $resClient) { 
                                                    echo "<option value='".Hash::encryptToken($resClient->id)."'>".$resClient->organisation."</option>";
                                                }
                                            ?>
                                        </select>
                                        <div class="validate"></div>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <div class="modal-footer editUserFooter">
                            <input type="hidden" name="request" value="editAdminAccount" />
                            <input type="hidden" id="userId" name="userId" value="" />
                            <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                            <button type="submit" id="editUserButton" class="btn btn-primary" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-pencil"></i> Edit</button>
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
            var linkto = '<?=DN?>/pages/accounts/accounts_action.php';
        </script>

        <script src="<?=DN?>/js/jqBootstrapValidation.min.js"></script>
        <script src="<?=DN?>/pages/accounts/admin.js?v=<?=date('Y-m-d H:i:s')?>"></script>
        
        <?php include $INC_DIR . "footer.php"; ?>

        </div>
        </div>
</body>

</html>
