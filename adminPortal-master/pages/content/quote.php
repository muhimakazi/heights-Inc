<?php
    require_once "../../core/init.php"; 
    if(!$user->isLoggedIn()) {
        Redirect::to('login');
    }

    $page = "content";
    $link = "quote";
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
                    <li><a>Home</a></li>
                    <li><a>Events</a></li>
                    <li><a>Website content</a></li>
                    <li class="active"><strong>Quote section</strong></li>
                </ol>
            </div>
            <div class="col-lg-2"></div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row row-flex"></div>
            <div class="row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <p>All <small class="red-color">*</small> fields are mandatory</p>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-lg-12 responseMsg" id="add-quote-messages"></div>
                                <form method="post" class="formCustom" id="addQuoteForm">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2"></div>
            </div>
        </div>

        <script type="text/javascript">
            var linkto  = '<?=DN?>/pages/content/content_action.php';
        </script>
        <script src="<?=DN?>/pages/content/quote.js?v=<?=date('Y-m-d H:i:s')?>"></script>
        
        <?php include $INC_DIR . "footer.php"; ?>
        
        </div>
        </div>
</body>

</html>
