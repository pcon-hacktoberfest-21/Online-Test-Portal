<!DOCTYPE html>
<html lang="en">

<head>
    <? include 'Include/head.php'?>
</head>

<body>
    <div class="card mx-auto mt-5" style="width: 28rem;">
        <div class="card-body text-center">
            <h5 class="card-title text-center">Error</h5><br>
            <p class="card-text"><b><?= $error_message ?></b></p><br><br>
            <a href="<?= getenv('app.baseURL') ?>/Admin" class="btn btn-primary">Go to Admin Dashboard</a>
        </div>
    </div>
    <br>
    <center><small>if you think this is a mistake please contact to admin</small></center>

</body>

</html>