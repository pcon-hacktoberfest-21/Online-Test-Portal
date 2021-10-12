<!DOCTYPE html>
<html lang="en">

<head>
    <? include 'Include/head.php'?>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Krona+One&display=swap" rel="stylesheet">

    <script src="<?= getenv('app.baseURL') ?>/public/js/exporting_lib/tableExport.js"></script>
    <script src="<?= getenv('app.baseURL') ?>/public/js/exporting_lib/jquery.base64.js"></script>
</head>

<body>
    <? include 'Include/nav.php'?>
    <?
    $session = session();
    $flash_response=$session->getFlashdata('flash_response');;
    if($flash_response!=""){
        echo "<div class='col-lg-3 mx-auto alert alert-warning alert-dismissible fade show' role='alert'>
        <strong><center>$flash_response</center></strong>
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;</span>
        </button>
        </div>";
    } ?>

    <div class="col-lg-4 mx-auto">
        <form class="mt-4">
            <center>
                <label class="sr-only" for="email">Email</label>
                <input type="text" class="mb-2 mr-sm-2" style=" 
                padding: .375rem .75rem;
                font-size: 0.9rem;
                width:60%;
                line-height: 1.3;
                color: #495057;
                background-color: #fff;
                background-clip: padding-box;
                border: 1px solid #ced4da;
                border-radius: .25rem;" name="email" placeholder="Enter Email to search a user" required>
                <button type="submit" class="btn btn-primary mb-2 btn-sm"><i class="fa fa-search"></i> Search</button>

            </center>
        </form>
    </div>
    <div id="tests" class="col-lg-11 mx-auto px-4 rounded">

        <h3 style="font-family: 'Krona One', sans-serif;">Enrolled User</h3>
        <a href='<?= getenv('app.baseURL') ?>/EditTest/view/<?= $test_data->test_id; ?>' class="btn float-left btn-primary"><i class="fas fa-edit"></i>Edit Test</a>
        <center><button onclick="exportToExcel()" class="btn btn-sm btn-primary"><i class="fa fa-download"></i> Export to Excel</button></center>
        <table class="table table-responsive-md bordered" id="data">
            <thead class="text-center">
                <tr>
                    <th>Rank</th>
                    <th>Name</th>
                    <th>Attendance</th>
                    <th>Roll</th>
                    <th>Email</th>
                    <th>IP</th>
                    <th>Tab Change</th>
                    <th>Time Left (M.)</th>
                    <th>+ ve Marks</th>
                    <th>- ve Marks</th>
                    <th>Score</th>
                    <th>Submitted</th>
                    <th>Response</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody class="text-center" style="font-family: 'Open Sans', sans-serif;">
                <?php
                if (isset($enrolled_test_data)) {
                    if (!empty($enrolled_test_data)) {
                        $sl = 1;
                        foreach ($enrolled_test_data as $test) {
                            if ($test->attendance) {
                                $attendance = "Present";
                            } else {
                                $attendance = "Absent";
                            }
                            if ($test->submitted) {
                                $submit_status = "Yes";
                            } else {
                                $submit_status = "No";
                            }
                            $last_seen = getUsersLastSeen($test->user_id, $test->test_id);
                            $user_data = getUserData($test->user_id)
                ?>
                            <tr>
                                <td><?= $sl++ ?></td>
                                <td>
                                    <a href='<?= getenv('app.baseURL') ?>/View/<?= $user_data->sharingID; ?>' target="_blank"><?= $user_data->name; ?></a>
                                </td>
                                <td><?= $attendance; ?></td>
                                <td><?= $user_data->roll; ?></td>
                                <td><?= $user_data->email; ?></td>
                                <td><?= $test->ip; ?></td>
                                <td><?= $test->tabchange; ?></td>
                                <td><?= gmdate('H:i:s', $test->time_left); ?></td>
                                <td><?= $test->positive_marks ?></td>
                                <td><?= $test->total-$test->positive_marks ?></td>
                                <td><?= $test->total ?></td>
                                <td><?= $submit_status; ?></td>
                                <td><a href='<?= getenv('app.baseURL') ?>/View/<?= $test->sharingID; ?>' target="_blank">Response Sheet</a></td>
                                <td>
                                    <a class="btn btn-danger" onclick="return confirm('Do you really want to unroll user? All data will be deleted immediately')" href="<?= getenv('app.baseURL') ?>/EditTest/DeleteUser/<?= $test->test_id; ?>?user=<?= $test->user_id ?>"><i class="fad fa-trash-alt"></i></a>
                                </td>
                            </tr>
                <?php }
                    }
                }; ?>
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

    function exportToExcel() {
        $('#data').tableExport({
            type: 'excel',
            separator: ';',
            escape: 'false',
            htmlContent: 'true',
            ignoreColumn: [13],
            tableName: 'results'
        });
    }
</script>