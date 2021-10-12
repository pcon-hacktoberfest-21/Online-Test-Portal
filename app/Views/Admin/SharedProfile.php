<!DOCTYPE html>
<html lang="en">

<head>
    <?include 'Include/head.php';?>
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Russo+One&family=Black+Han+Sans&Roboto+Condensed:ital@1&family=Yanone+Kaffeesatz:wght@700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="col-md-11 mx-auto" id="printableArea">
        <div class="col-lg-6  mx-auto mt-5">
            <div class="col-lg-6 mx-auto mt-5">
                <div class="card mt-3">
                    <div class="card-body">
                        <center>
                            <img src="<?= $user_data->picture ?>" style="border-radius: 50%;" /><br><br>
                            <strong style="font-family:' Black Han';"><?= $user_data->name ?></strong>
                            <br>Branch: <?= getBranchNameWithID($user_data->branch) ?>
                            <br>Roll: <?= $user_data->roll ?>
                            <br>Email: <?= $user_data->email ?>
                            <br>Member since: <?= $user_data->createdOn ?>
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>