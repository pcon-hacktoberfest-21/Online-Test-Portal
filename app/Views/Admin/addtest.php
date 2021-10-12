<!DOCTYPE html>
<html lang="en">

<head>
    <? include 'Include/head.php'?>
</head>

<body>
    <? include 'Include/nav.php'?>
    <div id="search-users" class="col-lg-4 mx-auto rounded p-5 jumbotron">
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
        <form method="post" action="">
            <div class="form-group">
                Test Name
                <input class="form-control" autocomplete="off" type="text" name="test_name" placeholder="Test Name" value="<?if(isset($_POST['test_name'])){echo $_POST['test_name'];}?>" required="">
            </div>

            <div class=" form-group">
                <label for="start_date">Start Date and Time</label>
                <input class="form-control" autocomplete="off" type="datetime-local" id="start_date" name="start_date" value="<?if(isset($_POST['start_date'])){echo $_POST['start_date'];}?>" required="">
            </div>
            <div class="form-group">
                <label for="end_date">End Date and Time</label>
                <input class="form-control" autocomplete="off" type="datetime-local" id="end_date" name="end_date" value="<?if(isset($_POST['end_date'])){echo $_POST['end_date'];}?>" required="">
            </div>
            <div class="form-group">
                <label for="test_duration">Duration In Minutes</label>
                <input class="form-control" type="number" autocomplete="off" name="test_duration" id="test_duration" placeholder="Duration in Minutes" value="<?if(isset($_POST['test_duration'])){echo $_POST['test_duration'];}?>" required="">
            </div>
            <div class="form-group">
                <label for="password">Test Password (Optional)</label>
                <input type="text" name="password" autocomplete="off" class="form-control" id="password" placeholder="Test Password" value="<?if(isset($_POST['password'])){echo $_POST['password'];}?>">
            </div>
            <center>
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" name="isPublic" id="isPublic">
                    <label class="custom-control-label" for="isPublic">Visible To All Users</label>
                </div>
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" name="nitOnly" id="nitOnly">
                    <label class="custom-control-label" for="nitOnly">For NIT Users Only</label>
                </div>
                <br>
                <input type="submit" name="add_test" value="Create Test" class="btn btn-danger" style="height: 40px; width:300px;">
            </center>
        </form><br>
        <br>
        <p class="text-center">* Note: After creating test go to Tests to add questions.</p>

    </div>
</body>

</html>