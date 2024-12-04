<?php
    require_once "../../core/init.php"; 
    if(!$user->isLoggedIn()) {
        Redirect::to('login');
    }
    if (!$user->hasPermission('admin')) {
        Redirect::to('index');
    }

    $page = "accounts";
    $link = "clients_list";

    //Quick clients numbers
    $total = $active = $blocked = $activePercentage = $blockedPercentage = 0;
    $_filter_condition_ = "";
    $total = ReportController::getTotalClients($_filter_condition_);
    if ($total > 0) {
        $_filter_condition_ = " AND status = 'ACTIVE'";
        $active = ReportController::getTotalClients($_filter_condition_);

        $_filter_condition_ = "AND status = 'BLOCKED'";
        $blocked = ReportController::getTotalClients($_filter_condition_);

        $activePercentage = ($active*100)/$total;
        $blockedPercentage = ($blocked*100)/$total;
    }

?>

<!DOCTYPE html>
<html>

<head>
    <?php include $INC_DIR . "head.php"; ?>
    <script src="<?=DN?>/js/jquery-2.1.1.js"></script>
    <style>
        .DTTT_container {display: none;}
        input[type="search"] { width: 300px !important;}
    </style>
</head>

<body>
    <div id="wrapper">

        <?php include $INC_DIR . "nav.php"; ?>
        
        <div class="wrapper wrapper-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-info pull-right">All</span>
                                <h5>Total clients</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?=$total?></h1>
                                <div class="stat-percent font-bold text-default">100% <i class="fa fa-bolt"></i></div>
                                <small>Clients</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-success pull-right">Active</span>
                                <h5>Active clients</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?=$active?></h1>
                                <div class="stat-percent font-bold text-success"><?=number_format($activePercentage, 2)?>% <i class="fa fa-level-up"></i></div>
                                <small>Clients</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-danger pull-right">Blocked</span>
                                <h5>Blocked clients</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?=$blocked?></h1>
                                <div class="stat-percent font-bold text-danger"><?=number_format($blockedPercentage, 2)?>% <i class="fa fa-level-up"></i></div>
                                <small>Clients</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <a href="<?=DN?>/pages/accounts/new_client" class="btn btn-md btn-primary pull-right"><i class="fa fa-plus-circle"></i> Add client</a>
                                <h5>Clients List</h5>
                            </div>

                            <div class="ibox-content">
                                <table id="clientTable" class='table table-hover customTable'>
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>First name</th>
                                        <th>Last name</th>
                                        <th>Email</th>
                                        <th>Telephone</th>
                                        <th>Organisation</th>
                                        <th>Job title</th>
                                        <th>City</th>
                                        <th>Country</th>
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


        <div class="modal inmodal fade" id="editClientModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">Edit client</h4>
                    </div>
                    <form action="<?php linkto("pages/accounts/accounts_action.php"); ?>" method="post" class="formCustom modal-form" id="editClientForm">
                        <div class="modal-body">
                            <div id="edit-client-messages"></div>
                            <p>All <small class="red-color">*</small> fields are mandatory</p>
                            <div class="row">
                                <div class="col-sm-6 b-r" style="padding: 0;">
                                    <div class="col-sm-12">
                                        <h4>Main contact details</h4>
                                        <hr class="separator-line">
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Main contact first name<small class="red-color">*</small></label>
                                            <input type="text" name="firstname" id="firstname" placeholder="Main contact first name" class="form-control" data-rule="required" data-msg="Please enter main contact first name"/>
                                            <div class="validate"></div>
                                        </div>
                                        <div class="form-group">
                                            <label>Main contact email address<small class="red-color">*</small></label>
                                            <input type="email" name="email" id="email" placeholder="Main contact email" class="form-control" data-rule="email" data-msg="Please enter a valid email"/>
                                            <div class="validate"></div>
                                        </div>
                                        <div class="form-group">
                                            <label>Main contact organisation<small class="red-color">*</small></label>
                                            <input type="text" name="organisation" id="organisation" placeholder=" Main contact organisation" class="form-control" data-rule="required" data-msg="Please enter main contact organisation"/>
                                            <div class="validate"></div>
                                        </div>
                                        <div class="form-group">
                                            <label>Main contact industry<small class="red-color">*</small></label>
                                            <select class="form-control" name="industry" id="industry" data-rule="required" data-msg="Please select main contact industry" onchange="Other(this,'#industry1');">
                                                <option value="" selected="">[--Select industry--]</option>
                                                <?php $controller->industry();?>
                                            </select>
                                            <div class="validate"></div>
                                        </div>
                                        <div class="form-group">
                                            <label>Main contact job title<small class="red-color">*</small></label>
                                            <input type="text" name="job_title" id="job_title" placeholder="Main contact job title" class="form-control" data-rule="required" data-msg="Please enter main contact job title"/>
                                            <div class="validate"></div>
                                        </div>
                                        <div class="form-group">
                                            <label>Main contact city<small class="red-color">*</small></label>
                                            <input type="text" name="city" id="city" placeholder="Main contact city" class="form-control" data-rule="required" data-msg="Please enter main contact city">
                                            <div class="validate"></div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Main contact second name<small class="red-color">*</small></label>
                                            <input type="text" name="lastname" id="lastname" placeholder="Main contact second name" class="form-control" data-rule="required" data-msg="Please enter main contact second name"/>
                                            <div class="validate"></div>
                                        </div>
                                        <div class="form-group">
                                            <label>Main contact telephone<small class="red-color">*</small></label>
                                            <input type="text" name="telephone" id="telephone" placeholder="Main contact telephone" class="form-control" data-rule="required" data-msg="Please enter main contact telephone"/>
                                            <div class="validate"></div>
                                        </div>
                                        <div class="form-group">
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
                                        <div class="form-group">
                                            <label style="visibility: hidden;">Main contact industry</label>
                                            <input type="text" id="industry1" class="form-control" name="industry1" placeholder="For other - please specify" 
                                            <?php if(escape(Input::get('industry')) != 'Other'){?> disabled="disabled" <?php }?> maxlength="30">
                                        </div>
                                        <div class="form-group">
                                            <label>Main contact website</label>
                                            <input type="text" name="website" id="website" placeholder="Main contact website" class="form-control"/>
                                        </div>
                                        <div class="form-group">
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
                                            <label>Secondary contact second name<small class="red-color">*</small></label>
                                            <input type="text" name="lastname2" id="lastname2" placeholder="Secondary contact second name" class="form-control" data-rule="required" data-msg="Please enter secondary contact second name"/>
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
                            </div>
                        </div>
                        <div class="modal-footer editClientFooter">
                            <input type="hidden" name="request" value="editClient" /> 
                            <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                            <button type="submit" id="editClientButton" class="btn btn-primary" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-check-circle"></i> Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal inmodal fade" id="viewClientModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">Client details</h4>
                    </div>
                    <div class="modal-body" style="background: #eee;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="dealer-widget">
                                    <div class="dealer-content">
                                        <div class="inner-wrapper">
                                            <div class="dealer-section-space">
                                                <span>Main contact details</span>
                                            </div>
                                            <div class="clear">
                                                <div class="col-xs-4 col-sm-4 dealer-face">
                                                    <img src="<?php linkto('img/noprofile.png');?>" class="img img-responsive img-circle">
                                                </div>
                                                <div class="col-xs-8 col-sm-8 ">
                                                    <h3 class="dealer-name">
                                                        <a id="vname">John Doe</a><br>
                                                        <span id="vorganisation">Cube communications</span>        
                                                    </h3>
                                                    <ul class="dealer-contacts">                                
                                                        <li><i class="fa fa-envelope"></i> <span id="vemail">email@yourcompany.com</span></li>
                                                        <li><i class="fa fa-phone"></i> <span id="vtelephone">+1 908 967 5906</span></li>
                                                        <li><i class="fa fa-map-marker"></i> <span id="vlocation">Kigali, Rwanda</span></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="clear">
                                                <div class="section additional-details">
                                                    <h4 class="s-property-title"></h4>
                                                    <ul class="additional-details-list clearfix">
                                                        <li>
                                                            <span class="col-lg-6 add-d-title">Industry</span>
                                                            <span class="col-lg-6 add-d-entry" id="vindustry">Lorem Ipsum</span>
                                                        </li>
                                                        <li>
                                                            <span class="col-lg-6 add-d-title">Number of Employees</span>
                                                            <span class="col-lg-6 add-d-entry" id="vemployees">Lorem Ipsum</span>
                                                        </li>
                                                        <li>
                                                            <span class="col-lg-6 add-d-title">Job title</span>
                                                            <span class="col-lg-6 add-d-entry" id="vjob_title">Lorem Ipsum</span>
                                                        </li>
                                                        <li>
                                                            <span class="col-lg-6 add-d-title">Website</span>
                                                            <span class="col-lg-6 add-d-entry" id="vwebsite">Lorem Ipsum</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="dealer-widget">
                                    <div class="dealer-content">
                                        <div class="inner-wrapper">
                                            <div class="dealer-section-space">
                                                <span>Secondary contact details</span>
                                            </div>
                                            <div class="clear">
                                                <div class="col-xs-4 col-sm-4 dealer-face">
                                                    <img src="<?php linkto('img/noprofile.png');?>" class="img img-responsive img-circle">
                                                </div>
                                                <div class="col-xs-8 col-sm-8 ">
                                                    <h3 class="dealer-name">
                                                        <a id="vname2">John Doe</a><br>
                                                        <span id="vorganisation2">Cube communications</span>        
                                                    </h3>
                                                    <ul class="dealer-contacts">                                
                                                        <li><i class="fa fa-envelope"></i> <span id="vemail2">email@yourcompany.com</span></li>
                                                        <li><i class="fa fa-phone"></i> <span id="vtelephone2">+1 908 967 5906</span></li>
                                                        <li><i class="fa fa-map-marker"></i> <span id="vlocation2">Kigali, Rwanda</span></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="clear">
                                                <div class="section additional-details">
                                                    <h4 class="s-property-title"></h4>
                                                    <ul class="additional-details-list clearfix">
                                                        <li>
                                                            <span class="col-lg-6 add-d-title">Industry</span>
                                                            <span class="col-lg-6 add-d-entry" id="vindustry2">Lorem Ipsum</span>
                                                        </li>
                                                        <li>
                                                            <span class="col-lg-6 add-d-title">Number of Employees</span>
                                                            <span class="col-lg-6 add-d-entry" id="vemployees2">Lorem Ipsum</span>
                                                        </li>
                                                        <li>
                                                            <span class="col-lg-6 add-d-title">Job title</span>
                                                            <span class="col-lg-6 add-d-entry" id="vjob_title2">Lorem Ipsum</span>
                                                        </li>
                                                        <li>
                                                            <span class="col-lg-6 add-d-title">Website</span>
                                                            <span class="col-lg-6 add-d-entry" id="vwebsite2">Lorem Ipsum</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12" style="margin-top: 30px;">
                                <div class="dealer-widget">
                                    <div class="dealer-content">
                                        <div class="inner-wrapper">
                                            <div class="dealer-section-space">
                                                <span>Invoicing details</span>
                                            </div>
                                            <div class="clear">
                                                <div class="section additional-details">
                                                    <div class="col-lg-6">
                                                        <ul class="additional-details-list clearfix">
                                                            <li>
                                                                <span class="col-lg-6 add-d-title">Organisation name to invoice</span>
                                                                <span class="col-lg-6 add-d-entry" id="vinvoice_organisation">Lorem Ipsum</span>
                                                            </li>
                                                            <li>
                                                                <span class="col-lg-6 add-d-title">Invoicing contact first name</span>
                                                                <span class="col-lg-6 add-d-entry" id="vfirstname3">Lorem Ipsum</span>
                                                            </li>
                                                            <li>
                                                                <span class="col-lg-6 add-d-title">Invoicing contact surname</span>
                                                                <span class="col-lg-6 add-d-entry" id="vlastname3">Lorem Ipsum</span>
                                                            </li>
                                                            <li>
                                                                <span class="col-lg-6 add-d-title">Invoicing email address</span>
                                                                <span class="col-lg-6 add-d-entry" id="vemail3">Lorem Ipsum</span>
                                                            </li>
                                                            <li>
                                                                <span class="col-lg-6 add-d-title">Invoicing telephone number</span>
                                                                <span class="col-lg-6 add-d-entry" id="vtelephone3">Lorem Ipsum</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <ul class="additional-details-list clearfix">
                                                            <li>
                                                                <span class="col-lg-6 add-d-title">Address line 1</span>
                                                                <span class="col-lg-6 add-d-entry" id="vinvoice_line_one">Lorem Ipsum</span>
                                                            </li>
                                                            <li>
                                                                <span class="col-lg-6 add-d-title">Address line 2</span>
                                                                <span class="col-lg-6 add-d-entry" id="vinvoice_line_two">Lorem Ipsum</span>
                                                            </li>
                                                            <li>
                                                                <span class="col-lg-6 add-d-title">City</span>
                                                                <span class="col-lg-6 add-d-entry" id="vinvoice_city">Lorem Ipsum</span>
                                                            </li>
                                                            <li>
                                                                <span class="col-lg-6 add-d-title">Country</span>
                                                                <span class="col-lg-6 add-d-entry" id="vinvoice_country">Lorem Ipsum</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            var linkto = '<?=DN?>/pages/accounts/accounts_action.php';
        </script>

        <script src="<?=DN?>/pages/accounts/clients.js?v=<?=date('Y-m-d H:i:s')?>"></script>
        
        <?php include $INC_DIR . "footer.php"; ?>

        </div>
        </div>
</body>

</html>
