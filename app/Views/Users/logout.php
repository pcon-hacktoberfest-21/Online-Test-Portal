<!DOCTYPE html>
<html lang="en">

<head>
    <?include 'Includes/head.php';?>
    <meta name="google-signin-client_id" content="<?= getenv('googleClientID') ?>">
    
</head>

<body class="jumbotron" onload="signOut()">
   <div class="jumbotron text-center">
   <br />
	   <div id="gSignIn" style="display: none;"></div>
	   <center><b><h3>Logged out</h3></b></center>
   <br />
   <br />
 
        <p class="lead" style="margin-top:10vh">
            <a class="btn btn-primary btn-md" href="<?= getenv('app.baseURL') ?>/Auth" role="button"><i class="fad fa-arrow-left"></i> Login Again</a>
        </p>
    <br/><br/>
    <br/><br/>
    <center>
        <a href="http://linkedin.com/in/abhishekjnvk" target="_blank">
            <img src="<?= getenv('app.baseURL') ?>/public/pcon.svg" width="250px"><br>
            A PCON product by Abhishek
        </a>
    </center>
    </div>
</body>
</html>