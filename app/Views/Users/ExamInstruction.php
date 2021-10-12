<!DOCTYPE html>
<html lang="en">

<head>
    <?php require 'Includes/head.php'; ?>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru:wght@500&display=swap" rel="stylesheet">
</head>

<body style="background-color: #d5d8db;">
    <?php require 'Includes/nav.php'; ?>

    <div class="jumbotron col-lg-6 mx-auto">

        <?

if(!empty($test_detail)){
    if(!empty($test_detail->password)){
        $isPassword=1;
    }else{
        $isPassword=0;
    }
?>
        <?$admin=getTestAdminDetail($test_detail->test_id);?>
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title" style="font-family: 'Kiwi Maru', serif;">
                        <strong><?= $test_detail->test_name ?></strong><small>
                            (<?= $test_detail->test_duration ?> Minutes)</small>
                            
                    </h5>
                    <p class="card-text ml-2"><small><strong><?= date('d M h:i A', $test_detail->sdatetime) ?></strong> To <strong><?= date('d M h:i A', $test_detail->edatetime) ?> </strong></small><br></p>
                    <br />
                    <?
                    if(isSubmitted($login_user_id, $test_detail->test_id)){
                        echo "<span class='btn btn-warning disabled float-right'>Already Attempted</span>";
                    }else{
                    if($test_detail->edatetime>time()){
                     if(isUserAlreadyEnrolled($login_user_id,$test_detail->test_id)){?>
                    <?
                     if($test_detail->sdatetime<=time()){?>
                    <button class="btn btn-success float-right" onclick="goToTestPage(`<?= $test_detail->test_id ?>`)">Start Test</button>
                    <?}else{
                     ?>
                    <span class="btn btn-warning disabled float-right">Test will Starts on <small><?= date('d M h:i A', $test_detail->sdatetime) ?></small></span>
                    <?}}else{
                        if($test_detail->nitOnly){
                        if (strpos($login_user_email, '@nitjsr.ac.in')) { ?>
                    <button class="btn btn-success float-right" onclick="registerForTest('<?= $test_detail->test_id ?>',<?= $isPassword ?>)"><? if($isPassword){echo "<i class='far fa-lock-alt'></i>";}?> Register Now</button>
                    <?}else{?>
                    <span class="btn btn-success float-right disabled">Login Via NIT Mail</span>
                    <?}}else{?>
                    <button class="btn btn-success float-right" onclick="registerForTest('<?= $test_detail->test_id ?>',<?= $isPassword ?>)"><? if($isPassword){echo "<i class='far fa-lock-alt'></i>";}?> Register Now</button>
                    <?}?>
                    <?}}else{?>
                    <span class="btn btn-success float-right disabled">Test Ends</span>
                    <?}}?>
                    <br><br><br>
                    <div class="accordion">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h5 class="mb-0 text-center">
                                    <span type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Test Admin
                                    </span>
                                </h5>
                            </div>

                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" >
                                <div class="card-body">
                                    <center>
                                        <img src="<?= $admin->picture ?>" style="border-radius: 50%;" /><br>
                                        <?= $admin->name ?>
                                        <br>(<?= $admin->email ?>)
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br />
        <br />
        <br />
        <div class="section">
            <div class="head-container">
                <h2 class="dark weight-700 regular caps head">Instructions</h2>
            </div>
            <di class="section-desc regular dark">
                <ol>
                    <li>
                        <p>Please Make your device's notification is turned off before attempting test.</p>
                    </li>
                    <li>
                        <p>Keep You device on charge to avoid any power issue.</p>
                    </li>
                    <li>
                        <p>Increase Your device's screen timeout before attempting test (10 Minutes Recommended)</p>
                    </li>
                    <li>
                        <p>Ensure that you are attempting the test using the correct email ID.</p>
                    </li>
                    <li>
                        <p>Answers are automatically saved</p>
                    </li>
                    <li>
                        <p>Please make sure you have a good internet connection </p>
                    </li>
                    <li>
                        <p>Changing tab will lead to deduct your marks</p>
                    </li>
                    <li>
                        <p>you can hide/show time by clicking on it</p>
                    </li>
                    <li>
                        <p>Once the test has started, the timer cannot be paused. You have to complete the test in one attempt.</p>
                    </li>
                    <li>
                        <p>We recommend that you close all other windows and tabs to ensure that there are no distractions.</p>
                    </li>
                    <li>
                        <p>Do not close the browser window or tab of the test interface before you end test</p>
                    </li>
                    <li>
                        <p>Make Sure you are attending test on a single device. Attending test from multiple device will deduct your time with double speed</p>
                    </li>
                </ol>
        </div>

        <? include 'Includes/dashboardJS.php';?>
        <?}else{?>
        <h3 class="text-center">Test Not Found</h3>
        <?}?>
<br><br><hr>
    <center>
        <a href="http://linkedin.com/in/abhishekjnvk" target="_blank">
            <img src="<?= getenv('app.baseURL') ?>/public/pcon.svg" width="50px"><br>
            A PCON product by Abhishek
        </a>
    </center>
</body>

</html>