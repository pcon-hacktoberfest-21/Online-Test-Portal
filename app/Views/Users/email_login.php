<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
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
    <meta name="google-signin-client_id" content="<?= getenv('googleClientID') ?>">
    <style>
        .hide {
            display: none;
        }
    </style>

    <style>
        .loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid blue;
            border-bottom: 16px solid blue;
            width: 120px;
            height: 120px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
        }

        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <script>
        window.onbeforeunload = function(e) {
            gapi.auth2.getAuthInstance().signOut();
        };
    </script>
</head>

<body>

    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <form class="login100-form validate-form" method="post">
                    <span class="login100-form-title p-b-48 text-primary">
                        <?= getenv('app.siteName') ?>
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
                        <input class="input100" name="email" value="<?if(isset($_POST['email'])){echo $_POST['email'];}?>">
                        <span class="focus-input100" data-placeholder="Email"></span>
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
                    <center class="my-2">
                        <br>
                        <br>
                        <a href="<?= getenv('app.baseURL') ?>/Auth" class="text-muted">Google Login</a><br><br>
                    </center>
                </form>
            </div>
        </div>
    </div>


    <script src="<?= getenv('app.baseURL') ?>/public/vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="<?= getenv('app.baseURL') ?>/public/vendor/animsition/js/animsition.min.js"></script>
    <script src="<?= getenv('app.baseURL') ?>/public/vendor/bootstrap/js/popper.js"></script>
    <script src="<?= getenv('app.baseURL') ?>/public/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?= getenv('app.baseURL') ?>/public/vendor/select2/select2.min.js"></script>
    <script src="<?= getenv('app.baseURL') ?>/public/vendor/daterangepicker/moment.min.js"></script>
    <script src="<?= getenv('app.baseURL') ?>/public/vendor/daterangepicker/daterangepicker.js"></script>
    <script src="<?= getenv('app.baseURL') ?>/public/vendor/countdowntime/countdowntime.js"></script>
    <script src="<?= getenv('app.baseURL') ?>/public/js/main.js"></script>


    <script>
        function onSignIn(googleUser) {
            console.log(googleUser)
            $("#my-signin2").hide();
            $("#other_login").hide();
            $(".loader").show();
            var profile = googleUser.getBasicProfile();
            email = profile.getEmail();
            name = profile.getName();
            var id_token = googleUser.getAuthResponse().id_token;
            console.log(id_token);
            if (id_token) {
                $("body").append(`
                <form method="post" id="google_login" action="<?= getenv('app.baseURL') ?>/Auth/GoogleLogin">
                <input type="hidden" value="${id_token}" name="id_token">
                </form>
                `)
                $("#google_login").submit()
            }

        }

        function signOut() {
            auth2.signOut();
        }


        function onFailure(err) {
            $.alert("Google Login Failed")
            console.log(err)
        }

        function renderButton() {
            gapi.signin2.render('my-signin2', {
                'scope': 'email',
                'width': 200,
                'height': 40,
                'longtitle': true,
                'theme': 'dark',
                'onsuccess': onSignIn,
                'onfailure': onFailure,
                ux_mode: 'redirect',
            });
        }
    </script>


    </script>
    <script src="https://apis.google.com/js/platform.js?onload=renderButton" async defer></script>



</body>

</html>