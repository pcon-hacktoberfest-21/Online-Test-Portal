<?php

use CodeIgniter\HTTP\Message;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?include 'Includes/head.php';?>
</head>

<body class="pt-5">
    <div class="card mx-auto mt-5" style="width: 22rem;">
        <div class="card-body">
            <h5 class="card-title text-center"><?= $message ?></h5>
            <?if(isset($success)){?>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Solved Questions</th>
                        <th scope="col"><span id="total_solved"></span></th>
                    </tr>
                </thead>
            </table>
            <script>
                var solved = JSON.parse(localStorage.getItem('total_answers_<?= $test_data->test_id ?>'))
                console.log(solved)
                console.log('total_answers_<?= $test_data->test_id ?>')
                $("#total_solved").text(ObjectLength(solved))

                function ObjectLength(object) {
                    var length = 0;
                    for (var key in object) {
                        if (object.hasOwnProperty(key)) {
                            ++length;
                        }
                    }
                    return length;
                };
                setTimeout(() => {
                    localStorage.clear();
                }, 2000);
            </script>
            <p class="card-text text-center">
                Detailed Analysis of test will be released after <?= date('D d M Y h:i A', $test_data->edatetime) ?> (if Test Admin Permits)
            </p>
            <?}?>
            <a href="<?= getenv('app.baseURL') ?>/dashboard" class="btn btn-primary container-fluid">Go to Dashboard</a>
        </div>
    </div>
</body>

</html>