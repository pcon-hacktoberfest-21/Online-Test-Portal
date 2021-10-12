<!DOCTYPE html>
<html lang="en">

<head>
    <?php require 'Includes/head.php'; ?>

</head>

<body style="background-color: #d5d8db;">
    <?php require 'Includes/nav.php'; ?>

    <?if(isUserVerified($login_user_id)){
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
    <div class="col-lg-6 jumbotron mx-auto">
        <form onsubmit="return searchTest()">
            <div class="col-lg-6 mx-auto mt-3">
                <div class="input-group">
                    <input type="text" autocomplete="off" class="form-control" id="test_id_field" placeholder="Search Test with Test ID">
                    <div class="input-group-append">
                        <button class="btn btn-secondary" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>



        <div class="mt-5">
            <div id="active_test" class="responsive-table">
                <table class="table" style="max-width: 100%;">
                    <thead>
                        <tr>
                            <th class="text-muted">Test Name</th>
                            <th class="text-center text-muted">Starts On</th>
                            <th class="text-center text-muted">Starts Ends</th>
                            <th class="text-center text-muted">#</th>
                        </tr>
                    </thead>
                    <tbody id="upcoming_test_tbody">
                        <?
                            $total_upcoming_test=0;
                            foreach($upcoming_test_data as $test){
                            $total_upcoming_test++;
                                ?>
                        <tr>
                            <td><b><?= (getTestName($test->test_id)) ?></b></td>
                            <td class="text-center"><small><?= date('d M h:i A', $test->sdatetime) ?></small></td>
                            <td class="text-center"><small><?= date('d M h:i A', $test->edatetime) ?></small></td>
                            <td class="text-center">
                                <a class="btn btn-primary btn-sm mt-1" href="<?= getenv('app.baseURL') ?>/Exam/<?= $test->test_id ?>">Test Detail</a>
                                <? if(isUserAlreadyEnrolled($login_user_id,$test->test_id)){?>
                                <?if(isTestWindowOpen($test->test_id)){
                                        if(isSubmitted($login_user_id, $test->test_id)){
                                            echo "<button class='btn btn-warning disabled mt-1 btn-sm' onclick='$.alert(`Already Attempted`)'>Attempted</button>";
                                        }else{
                                        ?>
                                <button class="btn btn-success btn-sm mt-1" onclick="goToTestPage(`<?= $test->test_id ?>`)">Start Test</button>
                                <?}}else{?>
                                <button class="btn btn-warning btn-sm disabled mt-1" onclick="$.alert('Test will start on <?= date('d/m/Y h:i A', $test->starttime) ?> IST')">Start Test</button>
                                <?}}?>

                            </td>
                        </tr>
                        <?
                            }
                            ?>
                    </tbody>
                </table>
            </div>
        </div>


        <div class="card-body">
            <h3 class="text-center mt-5"><strong>Past Test</strong></h3>

            <div id="active_test" class="well text-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-muted">Test Name</th>
                            <th class="text-center text-muted">Ends on</th>
                            <th class="text-center text-muted">Marks</th>
                            <th class="text-center text-muted">#</th>
                        </tr>
                    </thead>
                    <tbody id="past_test_tbody">
                        <?
                            $total_past_test=0;
                            foreach($enrolled_test_data as $enroll_test){
                                if($enroll_test->endtime<time()){
                                    $total_past_test++;
                                ?>
                        <tr>
                            <td><?= (getTestName($enroll_test->test_id)) ?><br><small><b>(<?= getTestDuration($enroll_test->test_id) ?> Minutes)</b></small></td>
                            <td class="text-center"><small><?= date('d M h:i A', $enroll_test->endtime) ?></small></td>
                            <td class="text-center">
                                <?= $enroll_test->total_marks; ?>
                            </td>
                            <td class="text-center">
                                <?
                                    if(isResultAllowed($enroll_test->test_id)){
                                    ?>
                                <a class="btn btn-info" href="<?= getenv('app.baseURL') ?>/Test/Analysis/<?= $enroll_test->test_id ?>"><i class="fas fa-file-chart-line"></i> View Result</a>
                                <?}else{
                                        echo "<btn class='btn btn-info btn-sm disabled'>Not allowed to see result</btn>";
                                    }?>
                            </td>
                        </tr>
                        <?
                            }}
                            ?>
                    </tbody>
                </table>
            </div>

        </div>
        
    </div>
    
    <center>
        <a href="http://linkedin.com/in/abhishekjnvk" target="_blank">
            <img src="<?= getenv('app.baseURL') ?>/public/pcon.svg" width="50px"><br>
            A PCON product by Abhishek
        </a>
    </center>
    </div>

    <script>
        $(document).ready(function() {
            <?php if ($total_past_test == 0) {
                echo "$('#past_test_tbody').append(`<tr><td colspan='4' class='text-center'>No Test Available</td><tr>`)\n\n";
            } ?>
            <?php if ($total_upcoming_test == 0) {
                echo "$('#upcoming_test_tbody').append(`<tr><td colspan='4' class='text-center'>No Test Available</td><tr>`)\n\n";
            } ?>
        });
    </script>
    <? include 'Includes/dashboardJS.php';?>
    <?}else{?>
    <div class='bg-danger text-light' style="height:94vh;padding-top:30vh" role='alert'>
        <strong style="font-size:25px">
            <center>Please Update your profile <br><br><a class="btn btn-sm btn-primary" href="<?= getenv('app.baseURL') ?>/Dashboard/Profile">Go To Profile</a></center>
        </strong>
    </div>

    <?}?>
</body>

</html>