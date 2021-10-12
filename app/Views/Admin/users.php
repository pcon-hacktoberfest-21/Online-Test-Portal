<!DOCTYPE html>
<html lang="en">

<head>
    <? include 'Include/head.php'?>
</head>

<body>
    <? include 'Include/nav.php'?>

    <div id="tests" class="col-lg-10 mx-auto p-5 rounded mt-5">
        <h3>Tests</h3>
        <table class="table table-responsive-md">
            <thead class="text-center">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Attendance</th>
                    <th>IP</th>
                    <th>Tab Change</th>
                    <th>Marks</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php
                $sl = 1;
                foreach ($enrolled_test_data as $test) :
                    if ($test->attendance) {
                        $attendance = "Present";
                    } else {
                        $attendance = "Absent";
                    }
                    $user_data = getUserData($test->user_id)
                ?>
                    <tr>
                        <td><?= $sl++ ?></td>
                        <td><?= $user_data->name; ?>&nbsp;&nbsp;&nbsp;<button class="badge badge-primary" onclick="$.alert(`<center class='mt-3'>City:<?= $user_data->city ?><br>Phone:<?= $user_data->phoneNo ?><br>Email:<?= $user_data->email ?></center>`)">View</button></td>
                        <td><?= $attendance; ?></td>
                        <td><?= $test->ip; ?></td>
                        <td><?= $test->tabchange; ?></td>
                        <td><?= $test->total_marks; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
<script>
    $(document).ready(function() {
        $('table').dataTable();
    });
</script>