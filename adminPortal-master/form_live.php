<?php
require_once 'core/init.php';
?>
<!DOCTYPE html>
<html>

<head>
    <?php include $INC_DIR . "head-login.php"; ?>
    <style>
        .top-navigation .navbar-brand {
          background: #fff;
          padding: 15px 25px;
        }
        .navbar-brand {
            height: auto;
        }
    </style>
</head>

<body class="top-navigation">

    <div id="wrapper">
        <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom white-bg">
        <nav class="navbar navbar-static-top" role="navigation">
            <div class="navbar-header">
                <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
                    <i class="fa fa-reorder"></i>
                </button>
                <a href="#" class="navbar-brand"><img src="<?php linkto('data_system/img/torus.png'); ?>" class="img-responsive" alt="logo"
                style="height: 60px;"></a>
            </div>
            <div class="navbar-collapse collapse" id="navbar">
                <ul class="nav navbar-top-links navbar-right">
                    <!-- <li>
                        <a href="login.html">
                            <i class="fa fa-sign-out"></i> Log out
                        </a>
                    </li> -->
                </ul>
            </div>
        </nav>
        </div>
        <div class="wrapper wrapper-content">
            <div class="container">
           

            </div>

        </div>
        <div class="footer fixed">
            <div>
                Copyright <a href="https://cube.rw/" target="_blank" style="color: #f06624;">Cube Digital</a> &copy; <?php echo date("Y"); ?> <span class="pull-right">Cube Communications</span>
            </div>
        </div>

        </div>
        </div>


    <?php include $INC_DIR . "footer-login.php"; ?>

</body>

</html>
