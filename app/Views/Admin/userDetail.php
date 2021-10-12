<!DOCTYPE html>
<html lang="en">

<head>
    <? include 'Include/head.php'?>
</head>

<body>
    <? include 'Include/nav.php'?>
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
    <div class="col-lg-10">
        <div class="row">
            <div class="col-lg-6 py-5">
                <div class="card mx-auto mt-1" style="width: 38rem;max-width:100%">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Name</label>
                            <input type="email" class="form-control" value="<?= $my_data->name ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" class="form-control" value="<?= $my_data->email ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" class="form-control" value="<?= $my_data->city ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>Mobile</label>
                            <input type="text" class="form-control" value="<?= $my_data->phoneNo ?>" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 py-5">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Test Name</th>
                            <th class="text-center">Test ID</th>
                            <th class="text-center">Subject</th>
                            <th class="text-center">Ends on</th>
                            <th class="text-center">Marks</th>
                        </tr>
                    </thead>
                    <tbody id="past_test_tbody">
                        <?
                            $total_past_test=0;
                            foreach($enrolled_tests as $enroll_test){
                                if($enroll_test->endtime<time()){
                                    $total_past_test++;
                                ?>
                        <tr>
                            <td><?= (getTestName($enroll_test->test_id)) ?><br><small><b>(<?= getTestDuration($enroll_test->test_id) ?> Minutes)</b></small></td>
                            <td class="text-center"><?= ($enroll_test->test_id) ?></td>
                            <td class="text-center"><?= getTestSubject($enroll_test->test_id) ?></td>
                            <td class="text-center"><small><?= date('d M h:i A', $enroll_test->endtime) ?></small></td>
                            <td class="text-center">
                                <?
                                if(($enroll_test->total_marks)==null){
                                    echo "<small>Result Not Published Yet</small>";
                                }else{
                                    echo $enroll_test->total_marks;
                                }
                                ?>
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
</body>

</html>