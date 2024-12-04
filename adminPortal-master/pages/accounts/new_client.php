<?php
    require_once "../../core/init.php"; 
    if(!$user->isLoggedIn()) {
        Redirect::to('login');
    }
    if (!$user->hasPermission('admin')) {
        Redirect::to('index');
    }

    $page = "accounts";
    $link = "new_client";
?>

<!DOCTYPE html>
<html>

<head>
    <?php include $INC_DIR . "head.php"; ?>
</head>

<body>
    <div id="wrapper">

        <?php include $INC_DIR . "nav.php"; ?>
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Accounts</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php linkto(''); ?>">Home</a>
                    </li>
                    <li>
                        <a>Accounts</a>
                    </li>
                    <li class="active">
                        <strong>New client</strong>
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
                            <div class="row">
                                <form action="<?php linkto("pages/accounts/accounts_action.php"); ?>" method="post" class="formCustom" id="addClientForm">
                                    <div id="add-client-messages"></div>
                                    <div class="col-sm-6 b-r" style="padding: 0;">
                                        <div class="col-sm-12">
                                            <h4>Main contact details</h4>
                                            <hr class="separator-line">
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group field-validate">
                                                <label>Main contact first name<small class="red-color">*</small></label>
                                                <input type="text" name="firstname" id="firstname" placeholder="Main contact first name" class="form-control" data-rule="required" data-msg="Please enter main contact first name"/>
                                                <div class="validate"></div>
                                            </div>
                                            <div class="form-group field-validate">
                                                <label>Main contact email address<small class="red-color">*</small></label>
                                                <input type="email" name="email" id="email" placeholder="Main contact email" class="form-control" data-rule="email" data-msg="Please enter a valid email"/>
                                                <div class="validate"></div>
                                            </div>
                                            <div class="form-group field-validate">
                                                <label>Main contact organisation<small class="red-color">*</small></label>
                                                <input type="text" name="organisation" id="organisation" placeholder=" Main contact organisation" class="form-control" data-rule="required" data-msg="Please enter main contact organisation"/>
                                                <div class="validate"></div>
                                            </div>
                                            <div class="form-group field-validate">
                                                <label>Main contact industry<small class="red-color">*</small></label>
                                                <select class="form-control" name="industry" id="industry" data-rule="required" data-msg="Please select main contact industry" onchange="Other(this,'#industry1');">
                                                    <option value="" selected="">[--Select industry--]</option>
                                                    <?php $controller->industry();?>
                                                </select>
                                                <div class="validate"></div>
                                            </div>
                                            <div class="form-group field-validate">
                                                <label>Main contact job title<small class="red-color">*</small></label>
                                                <input type="text" name="job_title" id="job_title" placeholder="Main contact job title" class="form-control" data-rule="required" data-msg="Please enter main contact job title"/>
                                                <div class="validate"></div>
                                            </div>
                                            <div class="form-group field-validate">
                                                <label>Main contact city<small class="red-color">*</small></label>
                                                <input type="text" name="city" id="city" placeholder="Main contact city" class="form-control" data-rule="required" data-msg="Please enter main contact city">
                                                <div class="validate"></div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group field-validate">
                                                <label>Main contact surname<small class="red-color">*</small></label>
                                                <input type="text" name="lastname" id="lastname" placeholder="Main contact surname" class="form-control" data-rule="required" data-msg="Please enter main contact surname"/>
                                                <div class="validate"></div>
                                            </div>
                                            <div class="form-group field-validate">
                                                <label>Main contact telephone<small class="red-color">*</small></label>
                                                <input type="text" name="telephone" id="telephone" placeholder="Main contact telephone" class="form-control" data-rule="required" data-msg="Please enter main contact telephone"/>
                                                <div class="validate"></div>
                                            </div>
                                            <div class="form-group field-validate">
                                                <label>Main contact number of Employees<small class="red-color">*</small></label>
                                                <select class="form-control" name="employees" id="employees" data-rule="required" data-msg="Please select number of employees">
                                                    <option value="" selected="">[--Number of Employees--]</option>
                                                    <option value="1 - 20">1 - 20</option>
                                                    <option value="20 - 50">20 - 50</option>
                                                    <option value="50 - 100">50 - 100</option>
                                                    <option value="100+">100+</option>
                                                </select>
                                                <div class="validate"></div>
                                            </div>
                                            <div class="form-group field-validate">
                                                <label style="visibility: hidden;">Main contact industry</label>
                                                <input type="text" id="industry1" class="form-control" name="industry1" placeholder="For other - please specify" 
                                                <?php if(escape(Input::get('industry')) != 'Other'){?> disabled="disabled" <?php }?> maxlength="30">
                                            </div>
                                            <div class="form-group">
                                                <label>Main contact website</label>
                                                <input type="text" name="website" id="website" placeholder="Main contact website" class="form-control"/>
                                            </div>
                                            <div class="form-group field-validate">
                                                <label>Main contact country<small class="red-color">*</small></label>
                                                <select class="form-control" name="country" id="country" data-rule="required" data-msg="Please select main contact country">
                                                    <option value="" selected="">[--Select country--]</option>
                                                    <?php $controller->country();?>
                                                </select>
                                                <div class="validate"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6" style="padding: 0;">
                                        <div class="col-sm-12">
                                            <h4>Secondary contact details</h4>
                                            <hr class="separator-line">
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Secondary contact first name<small class="red-color">*</small></label>
                                                <input type="text" name="firstname2" id="firstname2" placeholder="Secondary contact first name" class="form-control" data-rule="required" data-msg="Please enter secondary contact first name"/>
                                                <div class="validate"></div>
                                            </div>
                                            <div class="form-group">
                                                <label>Secondary contact email address<small class="red-color">*</small></label>
                                                <input type="email" name="email2" id="email2" placeholder="Secondary contact email" class="form-control" data-rule="email" data-msg="Please enter a valid email"/>
                                                <div class="validate"></div>
                                            </div>
                                            <div class="form-group">
                                                <label>Secondary contact organisation<small class="red-color">*</small></label>
                                                <input type="text" name="organisation2" id="organisation2" placeholder=" secondary contact organisation" class="form-control" data-rule="required" data-msg="Please enter secondary contact organisation"/>
                                                <div class="validate"></div>
                                            </div>
                                            <div class="form-group">
                                                <label>Secondary contact industry<small class="red-color">*</small></label>
                                                <select class="form-control" name="industry2" id="industry2" data-rule="required" data-msg="Please select secondary contact industry" onchange="Other(this,'#industry21');">
                                                    <option value="" selected="">[--Select industry--]</option>
                                                    <?php $controller->industry();?>
                                                </select>
                                                <div class="validate"></div>
                                            </div>
                                            <div class="form-group">
                                                <label>Secondary contact job title<small class="red-color">*</small></label>
                                                <input type="text" name="job_title2" id="job_title2" placeholder="Secondary contact job title" class="form-control" data-rule="required" data-msg="Please enter secondary contact job title"/>
                                                <div class="validate"></div>
                                            </div>
                                            <div class="form-group">
                                                <label>Secondary contact city<small class="red-color">*</small></label>
                                                <input type="text" name="city2" id="city2" placeholder="Secondary contact city" class="form-control" data-rule="required" data-msg="Please enter secondary contact city">
                                                <div class="validate"></div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Secondary contact surname<small class="red-color">*</small></label>
                                                <input type="text" name="lastname2" id="lastname2" placeholder="Secondary contact surname" class="form-control" data-rule="required" data-msg="Please enter secondary contact surname"/>
                                                <div class="validate"></div>
                                            </div>
                                            <div class="form-group">
                                                <label>Secondary contact telephone<small class="red-color">*</small></label>
                                                <input type="text" name="telephone2" id="telephone2" placeholder="Secondary contact telephone" class="form-control" data-rule="required" data-msg="Please enter secondary contact telephone"/>
                                                <div class="validate"></div>
                                            </div>
                                            <div class="form-group">
                                                <label>Secondary contact number of Employees<small class="red-color">*</small></label>
                                                <select class="form-control" name="employees2" id="employees2" data-rule="required" data-msg="Please select number of employees">
                                                    <option value="" selected="">[--Number of Employees--]</option>
                                                    <option value="1 - 20">1 - 20</option>
                                                    <option value="20 - 50">20 - 50</option>
                                                    <option value="50 - 100">50 - 100</option>
                                                    <option value="100+">100+</option>
                                                </select>
                                                <div class="validate"></div>
                                            </div>
                                            <div class="form-group">
                                                <label style="visibility: hidden;">secondary contact industry</label>
                                                <input type="text" id="industry21" class="form-control" name="industry21" placeholder="For other - please specify" 
                                                <?php if(escape(Input::get('industry2')) != 'Other'){?> disabled="disabled" <?php }?> maxlength="30">
                                            </div>
                                            <div class="form-group">
                                                <label>Secondary contact website</label>
                                                <input type="text" name="website2" id="website2" placeholder="Secondary contact website" class="form-control"/>
                                            </div>
                                            <div class="form-group">
                                                <label>Secondary contact country<small class="red-color">*</small></label>
                                                <select class="form-control" name="country2" id="country2" data-rule="required" data-msg="Please select secondary contact country">
                                                    <option value="" selected="">[--Select country--]</option>
                                                    <?php $controller->country();?>
                                                </select>
                                                <div class="validate"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12" style="padding: 0;">
                                        <div class="col-sm-12">
                                            <h4>Invoicing details</h4>
                                            <hr class="separator-line">
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Organisation name to invoice<small class="red-color">*</small></label>
                                                <input type="text" name="invoice_organisation" id="invoice_organisation" placeholder="Organisation name to invoice" class="form-control" data-rule="required" data-msg="Please enter organisation name"/>
                                                <div class="validate"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Invoicing contact first name<small class="red-color">*</small></label>
                                                <input type="text" name="firstname3" id="firstname3" placeholder="Invoicing contact first name" class="form-control" data-rule="required" data-msg="Please enter invoicing contact first name"/>
                                                <div class="validate"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Invoicing contact surname<small class="red-color">*</small></label>
                                                <input type="text" name="lastname3" id="lastname3" placeholder="Invoicing contact surname" class="form-control" data-rule="required" data-msg="Please enter invoicing contact surname"/>
                                                <div class="validate"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Invoicing email address<small class="red-color">*</small></label>
                                                <input type="email" name="email3" id="email3" placeholder="Invoicing email address" class="form-control" data-rule="required" data-msg="Please enter a valid email address"/>
                                                <div class="validate"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Invoicing telephone number<small class="red-color">*</small></label>
                                                <input type="text" name="telephone3" id="telephone3" placeholder="Invoicing telephone number" class="form-control" data-rule="required" data-msg="Please enter invoicing telephone number"/>
                                                <div class="validate"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Address line 1<small class="red-color">*</small></label>
                                                <input type="text" name="invoice_line_one" id="invoice_line_one" placeholder="Address line 1" class="form-control" data-rule="required" data-msg="Please enter address line 1"/>
                                                <div class="validate"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Address line 2<small class="red-color">*</small></label>
                                                <input type="text" name="invoice_line_two" id="invoice_line_two" placeholder="Address line 2" class="form-control" data-rule="required" data-msg="Please enter address line 2"/>
                                                <div class="validate"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>City<small class="red-color">*</small></label>
                                                <input type="text" name="invoice_city" id="invoice_city" placeholder="City" class="form-control" data-rule="required" data-msg="Please enter city"/>
                                                <div class="validate"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Country<small class="red-color">*</small></label>
                                                <select class="form-control" name="invoice_country" id="invoice_country" data-rule="required" data-msg="Please select country">
                                                    <option value="" selected="">[--Select country--]</option>
                                                    <?php $controller->country();?>
                                                </select>
                                                <div class="validate"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <input type="hidden" name="request" value="addNewClient" /> 
                                        <button type="submit" id="addClientButton" class="btn btn-primary pull-right" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-check-circle"></i> Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-lg-2"></div> -->
            </div>
        </div>

        <script type="text/javascript">
            var linkto = '<?php linkto("pages/accounts/accounts_action.php"); ?>';
        </script>

        <script src="<?php linkto('pages/accounts/clients.js?v=1.1'); ?>"></script>
        
        <?php include $INC_DIR . "footer.php"; ?>
        
        </div>
        </div>
</body>

</html>
