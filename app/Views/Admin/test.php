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
    <!-- <div class="container mt-2">
    </div> -->
    <div id="tests" class="col-lg-10 mx-auto px-5 rounded mt-2">
        <a href="<?= getenv('app.baseURL') ?>/Admin/AddTest" class="float-right btn btn-info"><i class="fas fa-layer-plus"></i> Create Test</a>
        <h3>Tests</h3>


        <table class="table table-responsive-md">
            <thead class="text-center">
                <tr>
                    <th>#</th>
                    <th>Test ID</th>
                    <th>Test Name</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Edit/Add Questions</th>
                    <th>Attendance</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php
                $sl = 1;
                foreach ($test_data as $test) : ?>
                    <tr>
                        <td><?= $sl++ ?></td>
                        <td><?= $test->test_id; ?><br><button class="badge badge-info" onclick="viewCount(`<?= $test->test_id ?>`)"><i class="fad fa-binoculars"></i> View count</button></td>
                        <td>
                            <?= $test->test_name; ?>
                            <?
                            if(time()<$test->edatetime){
                            ?>
                            <?}?>
                        </td>
                        <td><small><?= date('d M h:i A', $test->sdatetime); ?></small></td>
                        <td><small><?= date('d M h:i A', $test->edatetime); ?></small></td>
                        <td><a href='<?= getenv('app.baseURL') ?>/EditTest/view/<?= $test->test_id; ?>' class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Edit </a></td>
                        <td>

                            <a href='<?= getenv('app.baseURL') ?>/EditTest/viewAttendance/<?= $test->test_id; ?>' class="btn btn-sm btn-info" title="Click to view attendance & marks"><i class="fad fa-users"></i> View Users</a>
                        </td>
                        <td>
                            <?
                            if(time()>$test->edatetime){
                            ?>
                            <a href='<?= getenv('app.baseURL') ?>/EditTest/CalculateRank/<?= $test->test_id; ?>' class="btn btn-primary btn-sm" onclick="return confirm('This Process may take some time. Please Don\'t use it in test time\n Do you really want to continue?')">
                                Calculate Marks
                            </a>
                            <?}else{?>
                            <span class="btn btn-primary btn-sm disabled" onclick="alert('Result can be calculated after test windows ends\n (<?= date('d M h:i A', $test->edatetime); ?>)')">
                                Calculate Marks
                            </span>
                            <?}?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>

</html>
<script>
    $(document).ready(function() {
        $('table').dataTable({
            "paging": false,
            "info": false
        });
    });


    function viewCount(id) {
        $.confirm({
            title: 'User\'s Count',
            type: 'blue',
            content: function() {
                var self = this;
                return $.ajax({
                    url: `<?= getenv('app.baseURL') ?>/EditTest/viewCount/${id}`,
                    dataType: 'json',
                    method: 'get'
                }).done(function(response) {
                    console.log(response)
                    self.setContent('<center>Total Enrolled: ' + response.enrolled + "</center>");
                    self.setContentAppend('<center>Total Present: ' + response.started + "</center>");
                    self.setContentAppend('<center>Total Absent: ' + (response.enrolled - response.started) + "</center>");
                    self.setContentAppend('<center>Submission: ' + response.submission + "</center>");
                    self.setTitle(response.name);
                }).fail(function(error) {
                    console.log(error)
                    self.setContent('Something went wrong.');
                });
            },
            buttons: {
                ok: function() {}
            }
        });
    }
</script>