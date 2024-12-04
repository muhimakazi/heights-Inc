<?php
    require_once "../../core/init.php"; 
    if(!$user->isLoggedIn()) {
        Redirect::to('login');
    }

    $page = "events";
    $link = "new"
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
                <h2>Events</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php linkto(''); ?>">Home</a>
                    </li>
                    <li>
                        <a>Events</a>
                    </li>
                    <li>
                        <a>Website content</a>
                    </li>
                    <li class="active">
                        <strong>Patners & exhibitors  section</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2"></div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <p>All <small class="red-color">*</small> fields are mandatory</p>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <form action="" method="post" class="formCustom" id="addAboutEventSection" enctype="multipart/form-data">
                                    <div class="col-lg-12" id="add-event-messages"></div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Section title <small class="red-color">*</small></label>
                                            <input type="text" name="section_title" id="section_title" class="form-control" placeholder="Enter  section title" data-rule="required" data-msg="Please enter the section title"/>
                                            <div class="validate"></div>
                                        </div>                                   
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Upload  banner<small class="red-color">*</small></label>
                                            <input type="file" name="banner" id="banner" class="form-control" placeholder="upload banner" data-rule="required" data-msg="Please upload section banner"/>
                                            <div class="validate"></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <p style="margin:0;">NOTE :</p>
                                            <p class="red-color" style="margin:0;">- Banner must be a landscap image</p>
                                            
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <input type="hidden" name="request" value="addAboutSection" /> 
                                        <button type="submit" id="addAboutSectionButton" class="btn btn-primary pull-right" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-check-circle"></i> Submit</button>
                                    </div>
                                    
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2"></div>
            </div>
        </div>

        <script src="explore.js"></script>
        
        <?php include $INC_DIR . "footer.php"; ?>
        
        </div>
        </div>
</body>

</html>
