<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="<?= getenv('app.baseURL') ?>/public/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?= getenv('app.baseURL') ?>/public/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?= getenv('app.baseURL') ?>/public/fonts/iconic/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" type="text/css" href="<?= getenv('app.baseURL') ?>/public/vendor/animate/animate.css">
    <link rel="stylesheet" type="text/css" href="<?= getenv('app.baseURL') ?>/public/vendor/css-hamburgers/hamburgers.min.css">
    <link rel="stylesheet" type="text/css" href="<?= getenv('app.baseURL') ?>/public/vendor/animsition/css/animsition.min.css">
    <link rel="stylesheet" type="text/css" href="<?= getenv('app.baseURL') ?>/public/vendor/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="<?= getenv('app.baseURL') ?>/public/vendor/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" type="text/css" href="<?= getenv('app.baseURL') ?>/public/css/util.css">
    <link rel="stylesheet" type="text/css" href="<?= getenv('app.baseURL') ?>/public/css/main.css">
</head>

<body>

    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <form class="login100-form validate-form" method="post">
                    <span class="login100-form-title p-b-26">
                        Admin Portal
                    </span>


                    <?
            $session = session();
            $flash_response=$session->getFlashdata('flash_response');;
            if($flash_response!=""){
                echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                <strong><center>$flash_response</center></strong>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                  <span aria-hidden='true'>&times;</span>
                </button>
              </div>";
            } ?>


                    <div class="wrap-input100">
                        <input class="input100" name="username" value="<?if(isset($_POST['username'])){echo $_POST['username'];}?>">
                        <span class="focus-input100" data-placeholder="Username"></span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Enter password">
                        <span class="btn-show-pass">
                            <i class="zmdi zmdi-eye"></i>
                        </span>
                        <input class="input100" type="password" name="password">
                        <span class="focus-input100" data-placeholder="Password"></span>
                    </div>
                    <div class="container-login100-form-btn">
                        <div class="wrap-login100-form-btn">
                            <div class="login100-form-bgbtn"></div>
                            <button class="login100-form-btn" name="admin_login">
                                Sign In
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div id="dropDownSelect1"></div>
    <script src="<?= getenv('app.baseURL') ?>/public/vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="<?= getenv('app.baseURL') ?>/public/vendor/animsition/js/animsition.min.js"></script>
    <script src="<?= getenv('app.baseURL') ?>/public/vendor/bootstrap/js/popper.js"></script>
    <script src="<?= getenv('app.baseURL') ?>/public/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?= getenv('app.baseURL') ?>/public/vendor/select2/select2.min.js"></script>
    <script src="<?= getenv('app.baseURL') ?>/public/vendor/daterangepicker/moment.min.js"></script>
    <script src="<?= getenv('app.baseURL') ?>/public/vendor/daterangepicker/daterangepicker.js"></script>
    <script src="<?= getenv('app.baseURL') ?>/public/vendor/countdowntime/countdowntime.js"></script>
    <script src="<?= getenv('app.baseURL') ?>/public/js/main.js"></script>
</body>

</html>