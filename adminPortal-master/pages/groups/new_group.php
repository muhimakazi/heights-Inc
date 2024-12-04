<?php
    require_once "../../core/init.php"; 
    if(!$user->isLoggedIn()) {
        Redirect::to('login');
    }
    // if (!$user->hasPermission('admin')) {
    //     Redirect::to('index');
    // }

    $page = "groups";
    $link = "new_group";
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="<?=DN?>/build/css/intlTelInput.css">
    <?php include $INC_DIR . "head.php"; ?>
</head>

<body>
    <div id="wrapper">

        <?php include $INC_DIR . "nav.php"; ?>
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Registration Group</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php linkto(''); ?>">Home</a>
                    </li>
                    <li>
                        <a> Groups</a>
                    </li>
                    <li class="active">
                        <strong>New Group</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2"></div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <!-- <div class="col-lg-2"></div> -->
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <p>All <small class="red-color">*</small> fields are mandatory</p>
                        </div>
                        <div class="ibox-content">
                            <form class="form-contact formCustom" id="registerForm" method="post">
                                <div id="register-messages"></div>
                                <h4 class="mb-0 mt-4">Contact Details</h4>
                                <div class="row">
                                    <div class="form-group col-sm-3 mb-4">
                                        <label for="firstname">First name <small class="red-color">*</small></label>
                                        <div class="field-validate">
                                            <input class="form-control" name="firstname" id="firstname" type="text" data-rule="required"
                                                data-msg="Please enter first name" />
                                            <div class="validate"></div>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-3 mb-4">
                                        <label for="lastname">Last name <small class="red-color">*</small></label>
                                        <div class="field-validate">
                                            <input class="form-control" name="lastname" id="lastname" type="text" data-rule="required"
                                                data-msg="Please enter first name" />
                                            <div class="validate"></div>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-3 mb-4">
                                        <label for="email">Email <small class="red-color">*</small></label>
                                        <div class="field-validate">
                                            <input class="form-control" name="email" id="email" type="text" data-rule="email"
                                                data-msg="Please enter a valid email" />
                                            <div class="validate"></div>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-3 mb-4">
                                        <label for="telephone">Telephone <small class="red-color">*</small></label>
                                        <div class="field-validate">
                                            <input type="hidden" id="full_telephone" name="full_telephone" value="">
                                            <input type="text" name="telephone" id="telephone" class="form-control"
                                                data-rule="required" data-msg="Please enter telephone" />
                                            <div class="validate" id="telephone_error"></div>
                                        </div>
                                    </div>
                                </div>

                                <h4 class="mb-0 mt-4">Group Info</h4>
                                <div class="row">
                                    <div class="form-group col-sm-12 mb-4">
                                        <label for="firstname">Organisation name <small class="red-color">*</small></label>
                                        <div class="field-validate">
                                            <input class="form-control" name="group_name" id="group_name" type="text" data-rule="required"
                                                data-msg="Please enter group name" />
                                            <div class="validate"></div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <table class="table table-bordered table-hover" id="invoiceItem">   
                                            <tr>
                                                <th width="2%"><input id="checkAll" class="formcontrol" type="checkbox" style="visibility: hidden;"></th>
                                                <th width="21%">Participation category</th>
                                                <th width="22%">Participation subcategory</th>
                                                <th width="15%">Number of delegates</th>
                                                <th width="15%">Price</th>                              
                                                <th width="15%">Sub Total</th>
                                            </tr>                           
                                            <tr>
                                                <td><input class="itemRow" type="checkbox" style="visibility: hidden;"></td>
                                                <td class="field-validate">
                                                    <select name="participation_category[]" id="participation_category_1" onchange="getPartSubCategory1()" class="form-control participation_category" data-rule="required" data-msg="Please select"> 
                                                    <option value="">Select</option>
                                                        <?php
                                                        $_DATA_PARTICIPATION_CATEGORY_  = FutureEventController::getAllActivePacipationCategoryByEventID($eventId);
                                                        if($_DATA_PARTICIPATION_CATEGORY_ ):
                                                            foreach($_DATA_PARTICIPATION_CATEGORY_ As $_event_participation_category_ ):
                                                                $part_name = $_event_participation_category_->name;
                                                                $part_id   = $_event_participation_category_->id;
                                                                $payment_state = $_event_participation_category_->payment_state;
                                                                if ($part_id != 38 AND $part_id != 40 AND $part_id != 41 AND $payment_state != 'PAYABLE'):
                                                        ?>                 
                                                                <option value="<?=$part_id?>"><?=$part_name?></option>
                                                        <?php
                                                                endif;
                                                            endforeach;
                                                        endif;
                                                        ?>      
                                                    </select>
                                                    <div class="validate"></div>
                                                </td>
                                                <td class="field-validate">
                                                    <select id="participation_sub_category_1" name="participation_sub_category[]" onchange="getPrice1()" class="form-control" data-rule="required" data-msg="Please select">
                                                    </select>
                                                    <div class="validate"></div>
                                                </td>            
                                                <td class="field-validate"><input type="number" name="maximum_delegates[]" id="maximum_delegates_1" class="form-control quantity" autocomplete="off" min="1" oninput="calculateTotal()"></td>
                                                <td><input type="text" name="price[]" id="price_1" class="form-control price" readonly></td>
                                                <td><input type="number" name="total[]" id="total_1" class="form-control total" readonly></td>
                                            </tr>                     
                                        </table>
                                    </div>

                                    <div class="col-lg-8"></div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>Total: &nbsp;</label>
                                            <div class="input-group">
                                                <input value="" type="number" class="form-control" name="subTotal" id="subTotal" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <button class="btn btn-danger delete btn-sm" id="removeRows" type="button">- Delete</button>
                                        <button class="btn btn-success btn-sm" id="addRows" type="button">+ Add More</button>
                                    </div>

                                    <div class="form-group col-lg-12 mt-4">
                                        <input type="hidden" name="request"  value="registrationGroup">
                                        <input type="hidden" name="eventAuth"  value="<?=Hash::encryptAuthToken($eventId)?>">
                                        <button type="button" id="registerButton" class="btn btn-primary pull-right registerFormSubmit loader-btn" data-loading-text="Loading...">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-lg-2"></div> -->
            </div>
        </div>

        <script type="text/javascript">
            var linkto = '<?php linkto("pages/groups/controller_action.php"); ?>';
        </script>

        <script src="<?=DN?>/pages/groups/register-group.js"></script>
        
        <?php include $INC_DIR . "footer.php"; ?>

        <script src="<?=DN?>/build/js/intlTelInput.js"></script>

        <script>
            var phone_number = window.intlTelInput(document.querySelector("#telephone"), {
            autoPlaceholder: "off",
            separateDialCode: true,
            initialCountry: "rw",
            hiddenInput: "full", 
            utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
            });
        </script>
        
        </div>
        </div>
</body>

</html>
