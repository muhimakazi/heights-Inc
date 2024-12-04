<?php
    require_once "../../core/init.php"; 
    if(!$user->isLoggedIn()) {
        Redirect::to('admin/login');
    }

    $valid['success'] = array('success' => false, 'messages' => array());

    $eventId = Input::get('eventId');

    //Load exhibitors
    if(Input::get('request') && Input::get('request') == 'fetchExhibitors') {
        $eventId = Input::get('eventId');
        $controller->get('future_exhibitors', '*', NULL, "`event_id` = '$eventId'", 'exh_order ASC');
        $i = 0;
        if (!$controller->count()) {
            Danger("No exhibitor recorded");
        } else {
        ?>
        <table class="table">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Logo</th>
                    <th>Name</th>
                    <th>Country</th>
                    <th>City</th>
                    <th>Industry</th>
                    <th>Status</th>
                    <th>Details</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                foreach($controller->data() as $resExh) {
                    $i++;
                    $exhibitorId = base64_encode($resExh->id);
                    $imageUrl = "img/exhibitors/".$resExh->logo;
                ?>
                
                <tr class="gradeX" <?php if($resExh->status =='Blocked'){?>style="color: #aaa; border-bottom:1px solid #f4f4f4;"<?php } else {?> style="border-bottom: 1px solid #f4f4f4;"<?php }?>>
                    <div style="display: none;">
                        <span id="eName<?=$resExh->id?>"><?php echo $resExh->name; ?></span>
                        <span id="eCountr<?=$resExh->id?>"><?php echo $resExh->country; ?></span>
                        <span id="eCit<?=$resExh->id?>"><?php echo $resExh->city; ?></span>
                        <span id="eIndus<?=$resExh->id?>"><?php echo $resExh->industry; ?></span>
                        <span id="eOrder<?=$resExh->id?>"><?php echo $resExh->exh_order; ?></span>
                        <span id="eEmail<?=$resExh->id?>"><?php echo $resExh->email; ?></span>
                        <span id="eWeb<?=$resExh->id?>"><?php echo $resExh->website; ?></span>
                    </div>
                    <td><?php echo $i;?></td>
                    <td><span id="eImg<?=$resExh->id?>"><img src="<?php linkto($imageUrl); ?>" class="img-responsive" alt="logo" style="width:100px;"></span></</td>
                    <td><?php echo $resExh->name; ?></td>
                    <td><?php echo countryCodeToCountry($resExh->country); ?></td>
                    <td><?php echo $resExh->city; ?></td>
                    <td><?php echo $resExh->industry; ?></td>
                    <td><span class="label label-<?php echo $resExh->status == 'Active'? 'success' : 'danger'; ?>" style="display: block;"><?php echo $resExh->status; ?></span></td>
                    <td><a href="<?php linkto("admin/pages/exhibitors/exhibitor/$exhibitorId"); ?>" class="btn btn-xs btn-success pull-right" style="line-height: 1; font-size: 11px;">View details</a></td>
                    <td>
                        <div class="ibox-tools">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color: #3c8dbc;">More</a>
                            <ul class="dropdown-menu dropdown-user popover-menu-list">
                                <?php
                                $user = new User(); 
                                if($user->hasPermission('admin')) {
                                ?>
                                    <li><a class="menu edit_exhibitor" data-id="<?php echo $resExh->id;?>"><i class="fa fa-pencil icon"></i> Edit</a></li>
                                    <?php if($resExh->status != 'Block'){?>
                                    <li><a class="menu block_exhibitor" data-id="<?php echo $resExh->id;?>" data-request="Block"><i class="fa fa-times-circle icon"></i> Block</a></li>
                                    <?php }?>
                                    <?php if($resExh->status != 'Active'){?>
                                    <li><a class="menu block_exhibitor" data-id="<?php echo $resExh->id;?>" data-request="Active"><i class="fa fa-check-circle"></i> Activate</a></li>
                                    <?php }?>
                                <?php 
                                }
                                ?>
                            </ul>
                        </div>
                    </td>
                </tr>
                <?php } } ?>
            </tbody>
        </table>
    <?php
    }

    // Add exhibitor
    if(Input::get('request') && Input::get('request') == 'addExhibitor') {
        $type     = explode('.', $_FILES['image']['name']);
        $type     = $type[count($type)-1];  
        $fileName = uniqid(rand()).'.'.$type;
        $direct   = root("img/exhibitors/"); 
        $url      = $direct . $fileName; 

        if(in_array($type, array('gif', 'jpg', 'jpeg', 'png', 'JPG', 'GIF', 'JPEG', 'PNG'))) {
            if(is_uploaded_file($_FILES['image']['tmp_name'])) {            
                if(move_uploaded_file($_FILES['image']['tmp_name'], $url)) {
                    try {
                        $controller->create("future_exhibitors", array(
                            'event_id'      => escape(Input::get('eventId')),
                            'exh_order'     => escape(Input::get('order')),
                            'name'          => escape(Input::get('name')),
                            'country'       => escape(Input::get('country')),
                            'city'          => escape(Input::get('city')),
                            'industry'      => escape(Input::get('industry')),
                            'logo'          => $fileName,
                            'email'         => escape(Input::get('email')),
                            'website'       => escape(Input::get('website')),
                            'video'         => "",
                            'about'         => "",
                            'mission'       => "",
                            'brochure'      => "",
                            'status'        => "Active",
                            'creation_date' => date('Y-m-d')
                        ));
                        $valid['success']  = true;
                        $valid['messages'] = "Successfully created";    
                    } catch(Exception $error) {
                        $valid['success']  = false;
                        $valid['messages'] = "Error while creating partner";
                    }
                }
            }
        } else {
            $valid['success']  = false;
            $valid['messages'] = "Upload a valid image";
        }
        echo json_encode($valid);
    }

    // Edit exhibitor details
    if(Input::get('request') && Input::get('request') == 'editExhibitor') {
        try {
            $controller->update("future_exhibitors", array(
                'exh_order' => escape(Input::get('eorder')),
                'name'      => escape(Input::get('ename')),
                'country'   => escape(Input::get('ecountry')),
                'city'      => escape(Input::get('ecity')),
                'industry'  => escape(Input::get('eindustry')),
                'email'     => escape(Input::get('eemail')),
                'website'   => escape(Input::get('ewebsite'))
            ),Input::get('exhibitorId'));
            $valid['success']  = true;
            $valid['messages'] = "Successfully Updated";    
        } catch(Exception $error) {
            $valid['success']  = false;
            $valid['messages'] = "Error while updating partner";
        }
        echo json_encode($valid);
    }

    // Edit exhibitor logo
    if(Input::get('request') && Input::get('request') == 'editExhibitorImage') {
        $type = explode('.', $_FILES['editExhibitorImage']['name']);
        $type = $type[count($type)-1];  
        $fileName = uniqid(rand()).'.'.$type;
        $direct = root("img/exhibitors/"); 
        $url = $direct . $fileName; 

        if(in_array($type, array('gif', 'jpg', 'jpeg', 'png', 'JPG', 'GIF', 'JPEG', 'PNG'))) {
            if(is_uploaded_file($_FILES['editExhibitorImage']['tmp_name'])) {           
                if(move_uploaded_file($_FILES['editExhibitorImage']['tmp_name'], $url)) {
                    try {
                        $controller->update("future_exhibitors", array('logo' => $fileName), Input::get('exhibitorId'));
                        $valid['success']  = true;
                        $valid['messages'] = "Successfully uploaded";   
                    } catch(Exception $error) {
                        $valid['success']  = false;
                        $valid['messages'] = "Error while updating logo";
                    }
                }
            }
        } else {
            $valid['success']  = false;
            $valid['messages'] = "Invalid file";
        }
        echo json_encode($valid);
    }

    //Block and Activate exhibitor
    if(Input::get('request') && Input::get('request') == 'Active' || Input::get('request') && Input::get('request') == 'Block') {
        try {
            $controller->update("future_exhibitors", array('status' => Input::get('request')), Input::get('exhibitorId'));
            $valid['success']  = true;
            $valid['messages'] = "Successfully updated";    
        } catch(Exception $error) {
            $valid['success']  = false;
            $valid['messages'] = "Error while updating";
        }
        echo json_encode($valid);
    }


    // ---- Manage Exhibitor page sections ----- //

    //Load video section
    if(Input::get('request') && Input::get('request') == 'fetchVideoSection') {
        $exhibitor_id = Input::get('exhibitorId');
        $getContent   = DB::getInstance()->get('future_exhibitors', array('id', '=', $exhibitor_id));
        if (empty($getContent->first()->video)) {
        ?>
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="file-manager">
                    <div class="ibox-title" style="border: none; padding: 0;">
                        <button class="btn btn-xs btn-primary pull-right" id="addExhibitorVideo" data-id="<?php echo $exhibitor_id;?>"><i class="fa fa-plus-circle"></i> Add Exhibitor Video</button>
                    </div>
                    <?php Danger("No exhibitor video");?>
                </div>
            </div>
        </div>
        <?php
        } else {
            $videoUrl = "img/exhibitors/".$getContent->first()->video;
        ?>
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="file-manager">
                    <button class="btn btn-xs btn-primary pull-right" id="exhibitorVideo" data-id="<?php echo $exhibitor_id;?>"><i class="fa fa-pencil"></i> Edit</button>
                    <h5>Video Section</h5>
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="exhibitor_video_wrapper">
                                <video class="exhibitor_video">
                                    <source src="<?php linkto($videoUrl); ?>" />
                                </video>
                                <div class="exhibitor_video_playpause"></div>
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <div class="section-title">
                                <h2>About us</h2>
                            </div>
                            <hr style="border-top: 1px solid #f0f0f0; margin:10px 0 !important;">
                            <div class="section-content">
                                <p id="eDescr<?=$exhibitor_id?>"><?php echo nl2br($getContent->first()->about); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
        <?php
        }
    }

    //add exhibitor video
    if(Input::get('request') && Input::get('request') == 'addExhibitorVideo') {
        $type = explode('.', $_FILES['video']['name']);
        $type = $type[count($type)-1];  
        $fileName = uniqid(rand()).'.'.$type;
        $direct = root("img/exhibitors/"); 
        $url = $direct . $fileName; 

        if(in_array($type, array('mp4'))) {
            if(is_uploaded_file($_FILES['video']['tmp_name'])) {            
                if(move_uploaded_file($_FILES['video']['tmp_name'], $url)) {
                    try {
                        $controller->update("future_exhibitors", array(
                            'video' => $fileName,
                            'about' => Input::get('description')
                        ), Input::get('exhibitorId'));
                        $valid['success']  = true;
                        $valid['messages'] = "Successfully created";    
                    } catch(Exception $error) {
                        $valid['success']  = false;
                        $valid['messages'] = "Error while uploading data";
                    }
                }
            }
        } else {
            $valid['success']  = false;
            $valid['messages'] = "Invalid file";
        }
        echo json_encode($valid);
    }

    //edit exhibitor video
    if(Input::get('request') && Input::get('request') == 'editExhibitorAbout') {
        try {
            $controller->update("future_exhibitors", array('about' => Input::get('edescription')), Input::get('exhibitorId'));
            $valid['success']  = true;
            $valid['messages'] = "Successfully updated";    
        } catch(Exception $error) {
            $valid['success']  = false;
            $valid['messages'] = "Error while updating";
        }
        echo json_encode($valid);
    }

    if(Input::get('request') && Input::get('request') == 'editExhibitorVideo') {
        $type = explode('.', $_FILES['editExhibitorVideo']['name']);
        $type = $type[count($type)-1];  
        $fileName = uniqid(rand()).'.'.$type;
        $direct = root("img/exhibitors/"); 
        $url = $direct . $fileName; 
        $exhibitorId = Input::get('exhibitorId');

        if(in_array($type, array('mp4'))) {
            if(is_uploaded_file($_FILES['editExhibitorVideo']['tmp_name'])) {           
                if(move_uploaded_file($_FILES['editExhibitorVideo']['tmp_name'], $url)) {
                    try {
                        $controller->update("future_exhibitors", array('video' => $fileName), Input::get('exhibitorId'));
                        $valid['success']  = true;
                        $valid['messages'] = "Successfully updated";    
                    } catch(Exception $error) {
                        $valid['success']  = false;
                        $valid['messages'] = "Error while updating";
                    }
                }
            }
        } else {
            $valid['success']  = false;
            $valid['messages'] = "Invalid file";
        }
        echo json_encode($valid);
    }

    if (Input::get('videoUrl')) {
        $exhibitorId = Input::get('videoUrl');
        $findUrl     = DB::getInstance()->get('future_exhibitors', array('id', '=', $exhibitorId));
        $videoUrl    = "img/exhibitors/".$findUrl->first()->video;
        echo linkto($videoUrl);
    }

    //Load mission section
    if(Input::get('request') && Input::get('request') == 'fetchMissionSection') {
        $exhibitor_id = Input::get('exhibitorId');
        $getContent   = DB::getInstance()->get('future_exhibitors', array('id', '=', $exhibitor_id));
        if (empty($getContent->first()->mission)) {
        ?>
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="file-manager">
                    <div class="ibox-title" style="border: none; padding: 0;">
                        <button class="btn btn-xs btn-primary pull-right" id="addExhibitorMission" data-id="<?php echo $exhibitor_id;?>"><i class="fa fa-plus-circle"></i> Add Mission and Values</button>
                    </div>
                    <?php Danger("No exhibitor mission and values");?>
                </div>
            </div>
        </div>
        <?php
        } else {
        ?>
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="file-manager">
                    <button class="btn btn-xs btn-primary pull-right" id="editExhibitorMission" data-id="<?php echo $exhibitor_id;?>"><i class="fa fa-pencil"></i> Edit</button>
                     <h5>Mission and values</h5>
                    <div class="row">
                        <div class="col-lg-12">
                            <p id="eMiss<?=$exhibitor_id?>"><?php echo nl2br($getContent->first()->mission); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        }
    }

    //add exhibitor mission
    if(Input::get('request') && Input::get('request') == 'addExhibitorMission') {
        try {
            $controller->update("future_exhibitors", array('mission' => escape(Input::get('mission'))), Input::get('exhibitorId'));
            $valid['success']  = true;
            $valid['messages'] = "Successfully created";    
        } catch(Exception $error) {
            $valid['success']  = false;
            $valid['messages'] = "Error while uploading";
        }
        echo json_encode($valid);
    }

    //edit exhibitor mission
    if(Input::get('request') && Input::get('request') == 'editExhibitorMission') {
        try {
            $controller->update("future_exhibitors", array('mission' => escape(Input::get('emission'))), Input::get('exhibitorId'));
            $valid['success']  = true;
            $valid['messages'] = "Successfully updated";    
        } catch(Exception $error) {
            $valid['success']  = false;
            $valid['messages'] = "Error while updating exhibitor";
        }
        echo json_encode($valid);
    }


    //Load product section
    if(Input::get('request') && Input::get('request') == 'fetchProductSection') {
        $exhibitorId = Input::get('exhibitorId');
        $controller->get('future_exhibitor_product', '*', NULL, "`exhibitor_id` = '$exhibitorId'");
        if (!$controller->count()) {
            // Danger("No product recorded");
        } else {
            foreach($controller->data() as $resProd) {
        ?>
        <li class="col-md-4"><span id="eName<?=$resProd->id?>"><?php echo $resProd->name;?></span>
            <button class="btn btn-xs btn-primary pull-right edit_product" data-id="<?php echo $resProd->id;?>" style="margin-right: 20px;"><i class="fa fa-pencil"></i></button>
        </li>
        <?php
            }
        }
    }

    //add exhibitor product
    if(Input::get('request') && Input::get('request') == 'addProduct') {
        try {
            $controller->create("future_exhibitor_product", array(
                'exhibitor_id' => escape(Input::get('exhibitorId')),
                'name'         => escape(Input::get('product_name'))
            ));
            $valid['success']  = true;
            $valid['messages'] = "Successfully created";    
        } catch(Exception $error) {
            $valid['success']  = false;
            $valid['messages'] = "Error while uploading";
        }
        echo json_encode($valid);
    }

    //edit exhibitor product
    if(Input::get('request') && Input::get('request') == 'editProduct') {
        try {
            $controller->update("future_exhibitor_product",array('name'=>escape(Input::get('eproduct_name'))),Input::get('productId'));
            $valid['success']  = true;
            $valid['messages'] = "Successfully updated";    
        } catch(Exception $error) {
            $valid['success']  = false;
            $valid['messages'] = "Error while updating exhibitor";
        }
        echo json_encode($valid);
    }


    //Load brochure section
    if(Input::get('request') && Input::get('request') == 'fetchBrochureSection') {
        $exhibitorId = Input::get('exhibitorId');
        $getContent  = DB::getInstance()->get('future_exhibitors', array('id', '=', $exhibitorId));
        if (empty($getContent->first()->brochure)) {

        } else {
            $docUrl = "img/exhibitors/".$getContent->first()->brochure;
        ?>
        <embed src="<?php linkto($docUrl); ?>" width="60%" height="500px;"/>
        <?php
        }
    }

    // add brochure
    if(Input::get('request') && Input::get('request') == 'addBrochure') {
        $type = explode('.', $_FILES['addBrochure']['name']);
        $type = $type[count($type)-1];  
        $fileName = uniqid(rand()).'.'.$type;
        $direct = root("img/exhibitors/"); 
        $url = $direct . $fileName; 

        if(in_array($type, array('pdf', 'PDF'))) {
            if(is_uploaded_file($_FILES['addBrochure']['tmp_name'])) {           
                if(move_uploaded_file($_FILES['addBrochure']['tmp_name'], $url)) {
                    try {
                        $controller->update("future_exhibitors", array('brochure' => $fileName), Input::get('exhibitorId'));
                        $valid['success']  = true;
                        $valid['messages'] = "Successfully uploaded";   
                    } catch(Exception $error) {
                        $valid['success']  = false;
                        $valid['messages'] = "Error while updating document";
                    }
                }
            }
        } else {
            $valid['success']  = false;
            $valid['messages'] = "Invalid file";
        }
        echo json_encode($valid);
    }
?>


