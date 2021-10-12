<?php

use CodeIgniter\HTTP\Message;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?include 'Includes/head.php';?>
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

</head>

<body>
    <?include 'Includes/nav.php';?>
    <div class="col-lg-8 mx-auto mt-2">
        <div class="card-body">
            <div class="alert alert-primary text-center" role="alert">
                <b>Your Rank is <span id="my_rank"></span> out of <span id="total_student"></span> Students</b>
            </div>
            <table class="table table-bordered table-responsive-sm">
                <thead>
                    <tr>
                        <th class="text-center">Rank</th>
                        <th>Name</th>
                        <th class="text-center">Submitted</th>
                        <th class="text-center">Tab Change</th>
                        <th class="text-center">Marks</th>
                    </tr>
                </thead>
                <tbody>
                    <?
                    $sl=1;
                    foreach($enroll_test_data as $test_data){
                        if($test_data->user_id==$login_user_id){
                            $my_rank=$sl;
                        }

                        if ($test_data->submitted) {
                            $submit_status = "Yes";
                        } else {
                            $submit_status = "No";
                        }
                        $user_data=getUserData($test_data->user_id)
                    ?>
                    <tr>
                        <th scope="row" class="text-center"><?= $sl++ ?></th>
                        <td><?= ucwords($user_data->name) ?></td>
                        <td class="text-center"><?= $submit_status ?></td>
                        <td class="text-center"><?= $test_data->tabchange ?></td>
                        <td class="text-center"><?= $test_data->total_marks ?></td>
                    </tr>
                    <?}?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
<script>
    $(document).ready(function() {
        $("#my_rank").html(`<?= $my_rank ?>`)
        $("#total_student").html(`<?= $sl - 1 ?>`);
        $('table').DataTable({
            "paging": false,
            "ordering": false,
            // "info": false
        });
    });
</script>