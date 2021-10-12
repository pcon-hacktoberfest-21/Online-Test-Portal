<!DOCTYPE html>
<html lang="en">

<head>
    <? include 'Includes/head.php'?>
</head>

<body style="background-color: #d5d8db;">
    <? include 'Includes/nav.php'?>
    <?
            $session = session();
            $flash_response=$session->getFlashdata('flash_response');
            if($flash_response!=""){
                echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                <strong><center>$flash_response</center></strong>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                  <span aria-hidden='true'>&times;</span>
                </button>
              </div>";
            } ?>

    <?
    $my_data=getUserData($login_user_id);
    ?>
    <div class="card jumbotron mx-auto mt-1" style="width: 38rem;max-width:100%">
        <center>
            <img src="<?= $my_data->picture ?>" style="border-radius: 50%;" />
        </center>
        <form method="post">
            <div class="card-body">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" value="<?= $my_data->email ?>" disabled readonly>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Name</label>
                    <input type="text" name="name" class="form-control" value="<?= $my_data->name ?>">
                </div>
                <div class="form-group">
                    <label>Branch</label>
                    <select class="form-control" name="branch" required id="branch_selected">
                        <option value="" selected disabled></option>
                        <?
                        for($i=1;$i<=7;$i++){
                        ?>
                        <option value="<?= $i ?>"><?= getBranchNameWithID($i) ?></option>
                        <?}?>
                    </select>
                </div>
                <script>
                    $("#branch_selected").val(<?= $my_data->branch ?>)
                </script>
                <div class="form-group">
                    <label>Registration No.</label>
                    <input type="text" name="roll" class="form-control" autocomplete="off" value="<?= $my_data->roll ?>">
                </div>
                <button class="btn btn-success float-right" name="update_user">Update</button>
            </div>
        </form>
    </div>
    <br/><br/>
    <center>
        <a href="http://linkedin.com/in/abhishekjnvk" target="_blank">
            <img src="<?= getenv('app.baseURL') ?>/public/pcon.svg" width="50px"><br>
            A PCON product by Abhishek
        </a>
    </center>
</body>

</html>