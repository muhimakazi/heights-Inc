<?php
require_once "../../core/init.php";

if (!$user->isLoggedIn()) {
    Redirect::to('login');
}

$page = "accreditation";
$link = "scan";
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="<?=DN?>/css/select2.min.css">
    <link rel="stylesheet" href="<?=DN?>/css/flag-icon.min.css">
    <link rel="stylesheet" href="<?=DN?>/build/css/intlTelInput.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@700&display=swap" rel="stylesheet">
    <?php include $INC_DIR . "head.php"; ?>
    <script src="<?php linkto('js/jquery-2.1.1.js'); ?>"></script>
    <style>
    .label-dark {
        background-color: #2c4897;
        color: #FFFFFF;
    }

    .DTTT_container {
        display: none;
    }

    input[type="search"] {
        width: 300px !important;
        /*            height: 40px;*/
    }
    .select2-container {
        display: inline;
    }

    .card {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #e6ebe0;
        flex-direction: row;
        flex-flow: wrap;
        padding: 40px 0 100px 0;
        border-radius: 0;
    }
    .card-profile{
    height: 500px;
    width: 350px;
    position: relative;
/*    border-radius: 10px;*/
    background-color: #ebdcd7;
    -webkit-box-shadow: 2px 2px 2px rgba(0,0,0,0.1), -1px 0 2px rgba(0,0,0,0.05);
        box-shadow: 2px 2px 2px rgba(0,0,0,0.1), -1px 0 2px rgba(0,0,0,0.05);
}

.card-header{
/*    height: 30%;*/
    width: 100%;
    position: relative;
    z-index: 5;
    padding-left: 20px;
}

.card-body{
    height: 70%;
    width: 100%;
    position: absolute;
}

.card-header img{
    /*height: 100px;
    width: 100px;
    background-color: #0e3635;
    border-radius: 10px;
    position: absolute;
    top:60px;
    left: 120px;*/
}
.card-body p{
    color: #820364;
    position: relative;
    top: 60px;
    text-align: center;
    text-transform: capitalize;
    font-weight: bold;
    font-size: 30px;
    text-emphasis: spacing;
    font-family: 'League Spartan', sans-serif!important;
}
.card-body .desi{
    font-size:20px;
    color: #000;
    font-weight: normal;
    font-family: 'League Spartan', sans-serif!important;
}
.card-body .no{
    font-size: 15px;
    font-weight: normal;
}
.card-footer .del_status {
    padding: 15px 0;
}
.card-footer .del_status h2 {
    letter-spacing: 2px;
    margin-top: 0;
    line-height: normal;
    margin-bottom: 0;
}
.card-footer {
    text-align: center;
}
.card-footer .partners {
    margin: 20px 30px;
}
.card-footer .divider {
    background: #000;
    height: 1px;
    margin-bottom: 15px;
}
.barcode img
{
    height: 65px;
    width: 65px;
    text-align: center;
    margin: 5px;
}
.barcode{
    text-align: center;
    position: relative;
    top: 70px;
}

.padding .error-message, .responseMsg .error-message {
  color: #fff;
  background: #ed3c0d;
  text-align: left;
  padding: 15px;
  font-weight: 600;
/*  margin: 25px;*/
}
.padding .error-message br + br, .responseMsg .error-message br + br {
/*  margin: 25px;*/
}
.padding .sent-message, .responseMsg .sent-message {
  color: #fff;
  background: #18d26e;
  text-align: center;
  padding: 15px;
  font-weight: 600;
/*  margin: 25px;*/
}
    </style>

    
</head>

<body>
    <div id="wrapper">

        <?php include $INC_DIR . "nav.php"; ?>
        
            <div class="wrapper wrapper-content" style="padding: 25px 0;">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="ibox float-e-margins" style="background-color: #e6ebe0;">

                                <!-- <div class="ibox-title" style="height: auto; overflow: auto;">
                                    <h5>Scan badge</h5>
                                </div> -->

                                

                                <div class="card" id="badgeCard">
                                    <div class="padding">
                                        <div id="scan-messages" style="height: 60px;"></div>
                                        <div class="card-profile">
                                            <div class="card-header">
                                                <!-- <img src="<?=DN?>/data_system/img/noprofile.png"/> -->
                                                <img src="<?=DN?>/data_system/img/banner/IFF-header.png" style="width: 100%;"/>
                                            </div>
                                            <div class="card-body">
                                                <p id="partName">Delegate Name</p>
                                                <p class="desi"><span id="orgName">Organisation</span><br>
                                                    <span id="country">Country</span><br><br>
                                                    <span id="pass_type" style="color: #4766FF;">Pass Type</span>
                                                </p>
                                                <div class="barcode">
                                                    <!-- <img src="qr sample.png"> -->
                                                </div>
                                                <!-- <br>
                                                <p class="no">+91 8980849796</p>
                                                <p class="no">part-1,89 harinadad d...sdv..sdf..sfd..sd.</p> -->
                                            </div>
                                            <div class="card-footer" style="position: absolute;bottom: 0;">
                                                <div class="del_status" id="del_div" style="background: green;">
                                                    <h2 style="color: #fff;" id="del_status">STATUS</h2>
                                                </div>
                                                <div class="partners">
                                                    <div class="divider"></div>
                                                    <img src="<?=DN?>/data_system/img/banner/IFF-footer.png" style="width: 100%;">
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
                var linkto = '<?=DN?>/pages/accreditation/scan_action.php';
            </script>

            <script src="<?=DN?>/js/jqBootstrapValidation.min.js"></script>
            <script src="<?=DN?>/pages/accreditation/scan.js?v=<?=date('Y-m-d H:i:s')?>"></script>

            <?php include $INC_DIR . "footer.php"; ?>

        </div>
    </div>
</body>

</html>