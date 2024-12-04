<?php
    require_once "../../core/init.php"; 
    if(!$user->isLoggedIn()) {
        Redirect::to('admin/login');
    }

    $page = "exhibitors";
    $exhibitorId = base64_decode(Input::get('exhibitorId'));
?>

<!DOCTYPE html>
<html>

<head>
    <?php include $INC_DIR . "head.php"; ?>

    <style>
        .exhibitor_video {
            width: 100%;
        }
        .exhibitor_video_wrapper{
            display:table;
            width:auto;
            position:relative;
            width:100%;
        }
        .exhibitor_video_playpause {
            background-image:url(../../../../img/facilitation-video.jpg);
            background-repeat:no-repeat;
            width:100%;
            /*height:50%;*/
            position:absolute;
            left:0%;
            right:0%;
            top:0%;
            bottom:0%;
            margin:auto;
            background-size:auto;
            background-position: center;
        }
        .tab-pane h5 {color: #f47e20;}
    </style>
</head>

<body>
    <div id="wrapper">

        <?php include $INC_DIR . "nav.php"; ?>
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Exhibitors</h2>
                <ol class="breadcrumb">
                    <li><a href="<?php linkto('admin/'); ?>">Home</a></li>
                    <li><a href="<?php linkto("admin/pages/exhibitors/list"); ?>">Exhibitors</a></li>
                    <li class="active">
                        <strong>Exhibitor Page</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2"></div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-3">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <div class="file-manager">
                                <h5>Sections</h5>
                                <ul class="folder-list" style="padding: 0">
                                    <li class="active"><a data-toggle="tab" href="#tab-1"><i class="fa fa-file-video-o"></i> Video</a></li>
                                    <li class="" id="exhibitorMission"><a data-toggle="tab" href="#tab-2"><i class="fa fa-bullseye"></i> Mission and Values</a></li>
                                    <li class="" id="exhibitorProduct"><a data-toggle="tab" href="#tab-3"><i class="fa fa-th-list"></i> Products and Services</a></li>
                                    <li class="" id="exhibitorBrochure"><a data-toggle="tab" href="#tab-4"><i class="fa fa-file"></i> Brochure</a></li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9 animated fadeInRight">
                    <div class="tab-content">
                        <div id="tab-1" class="tab-pane active">
                            <div class="modal inmodal fade" id="addExhibitorVideoModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <i class="fa fa-file-video-o modal-icon"></i>
                                            <h4 class="modal-title">Add exhibitor video</h4>
                                        </div>
                                        <form action="<?php linkto("admin/pages/exhibitors/exhibitors_action.php"); ?>" class="form-horizontal" id="addExhibitorVideoForm" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <div id="add-exhibitor-video-messages"></div>
                                                <div class="form-group">
                                                    <label for="video" class="col-lg-2 control-label">Video</label>
                                                    <div class="col-lg-6">
                                                        <div id="kv-avatar-errors-1" class="center-block" style="display:none;"></div>
                                                        <div class="kv-avatar center-block">                            
                                                            <input type="file" name="video" class="form-control" id="video" placeholder="Exhibitor Video"  class="file-loading" style="width:auto;"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4"></div>
                                                </div>                     
                                                <div class="form-group">
                                                    <label for="description" class="col-lg-2 control-label">About Us</label>
                                                    <div class="col-lg-10">
                                                        <textarea class="form-control w-100" name="description" id="description" cols="30" rows="9" placeholder="About Us" autocomplete="off"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer addExhibitorVideoFooter">
                                                <input type="hidden" name="request" value="addExhibitorVideo" /> 
                                                <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                                                <button type="submit" id="addExhibitorVideoButton" class="btn btn-primary" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-check-circle"></i> Save changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="modal inmodal fade" id="editExhibitorVideoModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <i class="fa fa-file-video-o modal-icon"></i>
                                            <h4 class="modal-title">Edit Exhibitor Video</h4>
                                        </div>
                                        <div class="panel blank-panel">
                                            <div class="panel-heading">
                                                <div class="panel-options">
                                                    <ul class="nav nav-tabs">
                                                        <li class="active"><a data-toggle="tab" href="#tabVideo-1"><i class="fa fa-file-video-o"></i> Video</a></li>
                                                        <li class=""><a data-toggle="tab" href="#tabVideo-2"><i class="fa fa-list-alt"></i> About Us</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="panel-body">
                                                <div class="tab-content">
                                                    <div id="tabVideo-1" class="tab-pane active">
                                                        <form action="<?php linkto("admin/pages/exhibitors/exhibitors_action.php"); ?>" class="form-horizontal" id="editExhibitorVideoForm" enctype="multipart/form-data">
                                                            <div class="modal-body">
                                                                <div id="edit-exhibitorVideo-messages"></div>
                                                                <!-- <div class="form-group">
                                                                    <label for="editExhibitorVideo" class="col-sm-3 control-label">Exhibitor Video: </label>
                                                                    <label class="col-sm-1 control-label">: </label>
                                                                    <div class="col-sm-8">
                                                                        <video id="getExhibitorVideo" style="width:250px;">
                                                                            <source src="" />
                                                                        </video>                                                
                                                                    </div>
                                                                </div> -->
                                                                <div class="form-group">
                                                                    <label for="editExhibitorVideo" class="col-sm-3 control-label">Select Video: </label>
                                                                    <label class="col-sm-1 control-label">: </label>
                                                                    <div class="col-sm-8">
                                                                        <div id="kv-avatar-errors-1" class="center-block" style="display:none;"></div>                          
                                                                        <div class="kv-avatar center-block">                            
                                                                            <input type="file" class="form-control" id="editExhibitorVideo" placeholder="Exhibitor Image" name="editExhibitorVideo" class="file-loading" style="width:auto;"/>
                                                                        </div>
                                                                    </div>
                                                                </div> 
                                                            </div>
                                                            <div class="modal-footer editExhibitorVideoFooter">
                                                                <input type="hidden" name="request" value="editExhibitorVideo" />
                                                                <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                                                                <!-- <button type="button" id="editExhibitorImageButton" class="btn btn-primary"><i class="fa fa-pencil"></i> Edit</button> -->
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div id="tabVideo-2" class="tab-pane">
                                                        <form action="<?php linkto("admin/pages/exhibitors/exhibitors_action.php"); ?>" class="form-horizontal" id="editExhibitorAboutForm">
                                                            <div class="modal-body">
                                                                <div id="edit-exhibitorAbout-messages"></div>
                                                                <div class="form-group">
                                                                    <label for="edescription" class="col-lg-2 control-label">About Us</label>
                                                                    <div class="col-lg-10">
                                                                        <textarea class="form-control w-100" name="edescription" id="edescription" cols="30" rows="9" placeholder="About Us" autocomplete="off"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer editExhibitorAboutFooter">
                                                                <input type="hidden" name="request" value="editExhibitorAbout" /> 
                                                                <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                                                                <button type="submit" id="editExhibitorAboutButton" class="btn btn-primary" data-loading-text="Loading..."><i class="fa fa-pencil"></i> Edit</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="tab-2" class="tab-pane">
                            <div class="modal inmodal fade" id="addExhibitorMissionModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <i class="fa fa-bullseye modal-icon"></i>
                                            <h4 class="modal-title">Add Mission and Values</h4>
                                        </div>
                                        <form action="<?php linkto("admin/pages/exhibitors/exhibitors_action.php"); ?>" class="form-horizontal" id="addExhibitorMissionForm">
                                            <div class="modal-body">
                                                <div id="add-exhibitorMission-messages"></div>
                                                <div class="form-group">
                                                    <!-- <label for="mission" class="col-lg-2 control-label">Text</label> -->
                                                    <div class="col-lg-12">
                                                        <textarea class="form-control w-100" name="mission" id="mission" cols="30" rows="9" placeholder="Mission and Values" autocomplete="off"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer addExhibitorMissionFooter">
                                                <input type="hidden" name="request" value="addExhibitorMission" /> 
                                                <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                                                <button type="submit" id="addExhibitorMissionButton" class="btn btn-primary" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-check-circle"></i> Save changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="modal inmodal fade" id="editExhibitorMissionModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <i class="fa fa-bullseye modal-icon"></i>
                                            <h4 class="modal-title">Edit Mission and Values</h4>
                                        </div>
                                        <form action="<?php linkto("admin/pages/exhibitors/exhibitors_action.php"); ?>" class="form-horizontal" id="editExhibitorMissionForm">
                                            <div class="modal-body">
                                                <div id="edit-exhibitorMission-messages"></div>
                                                <div class="form-group">
                                                    <!-- <label for="emission" class="col-lg-2 control-label">Text</label> -->
                                                    <div class="col-lg-12">
                                                        <textarea class="form-control w-100" name="emission" id="emission" cols="30" rows="9" placeholder="Mission and Values" autocomplete="off"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer editExhibitorMissionFooter">
                                                <input type="hidden" name="request" value="editExhibitorMission" /> 
                                                <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                                                <button type="submit" id="editExhibitorMissionButton" class="btn btn-primary" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-pencil"></i> Edit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="tab-3" class="tab-pane">
                            <div class="ibox float-e-margins">
                                <div class="ibox-content">
                                    <div class="file-manager">
                                        <button class="btn btn-xs btn-primary pull-right" data-toggle="modal" data-target="#addProductModal"><i class="fa fa-plus-circle"></i> Add Product</button>
                                        <div class="modal inmodal fade" id="addProductModal" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                        <h4 class="modal-title">Add products and services</h4>
                                                    </div>
                                                    <form action="<?php linkto("admin/pages/exhibitors/exhibitors_action.php"); ?>" method="post" class="formCustom modal-form" id="addProductForm">
                                                        <div class="modal-body">
                                                            <div id="add-product-messages"></div>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="form-group">
                                                                        <label>Product name</label>
                                                                        <input type="text" name="product_name" id="product_name" class="form-control" placeholder="Product name" data-rule="required" data-msg="Please enter name"/>
                                                                        <div class="validate"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer addProductFooter">
                                                            <input type="hidden" name="request" value="addProduct"/>
                                                            <input type="hidden" name="exhibitorId" value="<?php echo $exhibitorId; ?>"/> 
                                                            <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                                                            <button type="submit" id="addProductButton" class="btn btn-primary" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-check-circle"></i> Submit</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <h5>Products and services</h5>
                                        <div class="row" style="margin-top: 20px;">
                                            <ul class="products_list" id="products_list"></ul>
                                        </div>

                                        <div class="modal inmodal fade" id="editProductModal" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                        <h4 class="modal-title">Edit products and services</h4>
                                                    </div>
                                                    <form action="<?php linkto("admin/pages/exhibitors/exhibitors_action.php"); ?>" method="post" class="formCustom modal-form" id="editProductForm">
                                                        <div class="modal-body">
                                                            <div id="edit-product-messages"></div>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="form-group">
                                                                        <label>Product name</label>
                                                                        <input type="text" name="eproduct_name" id="eproduct_name" class="form-control" placeholder="Product name" data-rule="required" data-msg="Please enter name"/>
                                                                        <div class="validate"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer editProductFooter">
                                                            <input type="hidden" name="request" value="editProduct"/>
                                                            <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                                                            <button type="submit" id="editProductButton" class="btn btn-primary" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-check-circle"></i> Submit</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="tab-4" class="tab-pane">
                            <div class="ibox float-e-margins">
                                <div class="ibox-content">
                                    <div class="file-manager clients">
                                        <button class="btn btn-xs btn-primary add_brochure pull-right"><i class="fa fa-plus-circle"></i> Add brochure</button>
                                        <div class="modal inmodal fade" id="addBrochureModal" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                        <h4 class="modal-title">Add products and services</h4>
                                                    </div>
                                                    <form action="<?php linkto("admin/pages/exhibitors/exhibitors_action.php"); ?>" method="post" class="formCustom modal-form" id="addBrochureForm" enctype="multipart/form-data">
                                                        <div class="modal-body">
                                                            <div id="add-brochure-messages"></div>
                                                            <div class="row">
                                                                <div class="form-group">
                                                                    <label for="addBrochure" class="col-sm-3 control-label">Brochure<small class="red-color">*</small></label>
                                                                    <label class="col-sm-1 control-label">: </label>
                                                                    <div class="col-sm-8">
                                                                        <div id="kv-avatar-errors-1" class="center-block" style="display:none;"></div>
                                                                        <div class="kv-avatar center-block">                           
                                                                            <input type="file" class="form-control" id="addBrochure" placeholder="Brochure" name="addBrochure" class="file-loading" style="width:auto;"/>
                                                                        </div>
                                                                        <p style="color: red; margin-top: 10px;">Format: pdf</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer editBrochureFooter">
                                                            <input type="hidden" name="request" value="addBrochure" />
                                                            <input type="hidden" name="exhibitorId" value="<?php echo $exhibitorId; ?>"/> 
                                                            <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <h5>Products and services</h5>
                                        <div class="row" id="exh_brochure"></div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            var exhibitorId = '<?php echo $exhibitorId; ?>';
            var linkto      = '<?php linkto("admin/pages/exhibitors/exhibitors_action.php"); ?>';
        </script>
        <script src="<?php linkto('admin/pages/exhibitors/exhibitor.js'); ?>"></script>
        
        <?php include $INC_DIR . "footer.php"; ?>

        <script>
            $('.exhibitor_video').parent().click(function () {
              if($(this).children(".exhibitor_video").get(0).paused){        
                    $(this).children(".exhibitor_video").get(0).play();   $(this).children(".exhibitor_video_playpause").fadeOut();
                } else {
                    $(this).children(".exhibitor_video").get(0).pause();
                    $(this).children(".exhibitor_video_playpause").fadeIn();
                }
            });
        </script>
        

        </div>
        </div>
</body>

</html>
