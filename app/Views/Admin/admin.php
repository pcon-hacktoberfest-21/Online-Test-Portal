<!DOCTYPE html>
<html lang="en">

<head>
    <? include 'Include/head.php'?>
</head>

<body>
    <? include 'Include/nav.php'?>
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
    <div class="card mx-auto mt-5" style="width: 18rem;">
        <div class="card-body">
            <h5 class="card-title text-center">Admin Panel</h5>

            <p class="card-text text-center">Welcome <?= $admin_data->name ?></p>
            <center>
                <small><?= $admin_data->email ?></small>
            </center>
        </div>
    </div>
     <br/><br/>
    <center style="margin-top:20vh">
        <a href="http://linkedin.com/in/abhishekjnvk" target="_blank">
            <img src="<?= getenv('app.baseURL') ?>/public/pcon.svg" width="250px"><br>
            A PCON product by Abhishek
        </a>
    </center>
</body>

</html>