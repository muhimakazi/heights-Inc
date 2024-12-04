<?php
    require_once "../../core/init.php"; 
    if(!$user->isLoggedIn()) {
        Redirect::to('login');
    }

    if(!Input::checkInput('formToken', 'get', 1))
        Redirect::to('');

    $formToken = Input::get('formToken', 'get');
    $formID    = Hash::decryptToken($formToken);
    $form_details = FormController::getFormDetails($formID);
    $contentToken = Hash::encryptToken($form_details->contentId);

    $page = "form";
    $link = "form";
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="<?=DN?>/css/select2.min.css">
    <link rel="stylesheet" href="<?=DN?>/css/flag-icon.min.css">
    <link rel="stylesheet" href="<?=DN?>/build/css/intlTelInput.css">
    
    <?php include $INC_DIR . "head.php"; ?>
    <style>
        .cb-wrap {
            inset: unset!important;
        }
        .button-clear-form {display: none!important;}
        .rendered-form .form-group {margin-bottom: 5px!important;}
        .select2-container {width: -moz-available!important;}
        .choose_fields label {
/*            margin-right: 10px;*/
        }
    </style>

    <!-- FORM BUILDER -->
    <script src="<?=DN?>/js/plugins/formbuilder/jquery-ui.min.js"></script>
    <script src="<?=DN?>/js/plugins/formbuilder/form-builder.min.js"></script>
    <script src="<?=DN?>/js/plugins/formbuilder/form-render.min.js"></script>
    <script src="<?=DN?>/js/plugins/formbuilder/control_plugins/buttons.js"></script>
    <script src="<?=DN?>/js/plugins/formbuilder/control_plugins/table.js"></script>

</head>

<body>
    <div id="wrapper">

        <?php include $INC_DIR . "nav.php"; ?>

        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-md-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <a href="<?=DN?>/form/list" class="btn btn-sm btn-primary pull-right"><i class="fa fa-list"></i> Form List</a>
                            <h1><?=$form_details->form_name?> Form</h1>
                        </div>
                        <div class="ibox-content choose_fields" style="padding-bottom: 0;">
                            <form class="formCustom">
                                <h3 style="margin-bottom: 20px;">Choose form fields</h3>
                                <label class="col-md-2 checkbox-mc">Telephone
                                    <input type="checkbox" name="field_option" value="telephone"/>
                                    <span class="geekmark"></span>
                                </label>
                                <label class="col-md-2 checkbox-mc">Organisation name
                                    <input type="checkbox" name="field_option" value="organisation_name"/>
                                    <span class="geekmark"></span>
                                </label>
                                <label class="col-md-2 checkbox-mc">Organisation type
                                    <input type="checkbox" name="field_option" value="organisation_type"/>
                                    <span class="geekmark"></span>
                                </label>
                                <label class="col-md-2 checkbox-mc">Job title
                                    <input type="checkbox" name="field_option" value="job_title"/>
                                    <span class="geekmark"></span>
                                </label>
                                <label class="col-md-2 checkbox-mc">Job category
                                    <input type="checkbox" name="field_option" value="job_category"/>
                                    <span class="geekmark"></span>
                                </label>
                                <label class="col-md-2 checkbox-mc">Country
                                    <input type="checkbox" name="field_option" value="residence_country"/>
                                    <span class="geekmark"></span>
                                </label>
                                <label class="col-md-2 checkbox-mc">Website
                                    <input type="checkbox" name="field_option" value="website"/>
                                    <span class="geekmark"></span>
                                </label><br>
                                <label class="col-md-2 checkbox-mc">Picture
                                    <input type="checkbox" name="field_option" value="image"/>
                                    <span class="geekmark"></span>
                                </label>
                                <label class="col-md-2 checkbox-mc">ID type
                                    <input type="checkbox" name="field_option" value="id_type"/>
                                    <span class="geekmark"></span>
                                </label>
                                <label class="col-md-2 checkbox-mc">ID number
                                    <input type="checkbox" name="field_option" value="id_number"/>
                                    <span class="geekmark"></span>
                                </label>
                                <label class="col-md-2 checkbox-mc">Press card number
                                    <input type="checkbox" name="field_option" value="media_card_number"/>
                                    <span class="geekmark"></span>
                                </label>
                                <label class="col-md-2 checkbox-mc">Issuing authority
                                    <input type="checkbox" name="field_option" value="media_card_authority"/>
                                    <span class="geekmark"></span>
                                </label>
                            </form>
                        </div>
                        <div class="ibox-content formCustom" id="formbuilder_content" title="Form builder">
                            <div id="add-messages" style="margin-bottom:20px"></div>
                            <input type="hidden" id="form_content_id" />
                            <input type="hidden" id="form_content_status" />
                            <h3>Custom fields</h3>
                            <div class="build-wrap form-wrapper-div" id="builder-editor"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- REGISTER NEW PARTICIPANT -->
        <div class="modal inmodal fade" id="registerModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">Form Preview</h4>
                    </div>
                    <form method="post" class="formCustom modal-form" id="registerForm" novalidate="novalidate">
                        <div class="modal-body">
                            <div id="register-messages"></div>
                            <p>All <small class="red-color">*</small> fields are mandatory</p>
                            <div  id="form-render-content">
                                
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="registerButton" class="btn btn-primary" data-loading-text="Loading..." autocomplete="off">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <script type="text/javascript">
            var DN = '<?=DN?>';
            var linkto = '<?=DN?>/pages/form/form_action.php';
            var formToken = '<?=$formToken?>';
            var contentToken = '<?=$contentToken?>';
        </script>
        <script src="<?=DN?>/js/jqBootstrapValidation.min.js"></script>
        
        <?php include $INC_DIR . "footer.php"; ?>

        <script src="<?=DN?>/js/select2.min.js"></script>
        <script src="<?=DN?>/build/js/intlTelInput.js"></script>

        <script src="<?=DN?>/pages/form/build.js?v=<?=date('Y-m-d H:i:s')?>"></script>
        
        </div>
        </div>
</body>

</html>
