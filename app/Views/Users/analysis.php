<!DOCTYPE html>
<html lang="en">

<head>
    <?include 'Includes/head.php';?>
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Russo+One&family=Black+Han+Sans&Roboto+Condensed:ital@1&family=Yanone+Kaffeesatz:wght@700&display=swap" rel="stylesheet">
    <style>
        @media print {
            #print_button {
                display: none;
            }
        }
    </style>
</head>

<body>
    <?include 'Includes/nav.php';?>
    <?$user_data=getUserData($enrolled_data->user_id);?>
    <div class="col-md-11 mx-auto" id="printableArea">
        <div class="row col-lg-6  mx-auto">

            <div class="col-lg-10 mx-auto">
                <div class="mt-3">
                    <div class="card-body">
                        <center>
                            <img src="<?= $user_data->picture ?>" style="border-radius: 50%;" /><br>
                            <?= $user_data->name ?>
                            <br>(<?= $user_data->roll ?>)
                            <br>(<?= $user_data->email ?>)
                        </center>
                    </div>

                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <td>Solved Questions</td>
                                <td><span id="solved_questions"></span></td>
                                <td></td>
                                <td>Final Score</td>
                                <td><strong><?= count_final_score($enrolled_data->test_id, $enrolled_data->user_id) ?></strong></td>
                            </tr>
                            <tr>
                                <td>Correct Questions</td>
                                <td><span id="correct_questions"></span></td>
                                <td></td>
                                <td>Positive Marks</td>
                                <td><strong><?= count_positive_score($enrolled_data->test_id, $enrolled_data->user_id) ?></strong></td>
                            </tr>
                            <tr>
                                <td>Incorrect Questions</td>
                                <td><span id="incorrect_questions"></span></td>
                                <td></td>
                                <td>Negative Score</td>
                                <td><strong><?= count_negative_score($enrolled_data->test_id, $enrolled_data->user_id) ?></strong></td>
                            </tr>
                            <tr>
                                <td>
                                <td>Left Questions</td>
                                <td><span id="left_questions"></span></td>
                                <td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <center>
            <input type="button" id="print_button" class="btn btn-primary col-lg-3 mx-auto" onclick="printDiv('printableArea')" value="Download Result" />
        </center>
        <div class="row">

            <div class="col-lg-10 mt-3 pb-4 rounded-left mx-auto">
                <h2 class="text-center mt-2 text-primary" style="font-family: 'Abril Fatface', cursive;">Detailed Analysis</h2>
                <div>
                    <div id="accordion_dashboard">
                        <?php
                        $sl = 1;
                        $question_no = 1;
                        $correct = 0;
                        $incorrect = 0;
                        $solved = 0;
                        foreach ($section_data as $section) {
                            $section_name = $section->section;
                            $new_name = $section_name . '_questions';
                            $array = $$new_name;
                        ?>

                            <div class="mt-2 ">
                                <div class="card-header" id="heading_dashboard<?= $sl ?>">
                                    <h5 class="mb-0 text-center">
                                        <span class=" container-fluid <?php if ($sl != 1) {
                                                                            echo " collapsed";
                                                                        } ?>" data-toggle="collapse" data-target="#collapse_dashboard<?= $sl ?>" aria-expanded="
                                    <?php if ($sl == 1) {
                                        echo "true";
                                    } else {
                                        echo "false";
                                    } ?>" aria-controls="collapse_dashboard<?= $sl ?>">
                                            <span style="font-family: 'Russo One', sans-serif;" class="text-dark"><?= $section_name ?></span>
                                        </span>
                                    </h5>
                                </div>

                                <div id="collapse_dashboard<?= $sl ?>" class="collapse show" aria-labelledby="heading_dashboard<?= $sl ?>">
                                    <div>
                                        <?php

                                        foreach ($array as $question) {
                                            $my_response = fetch_previous_response($enrolled_data->user_id, $question->test_id, $question->question_id);
                                            if (empty($my_response)) {
                                                $bg_color = 'text-dark';
                                                $marks = 0;
                                            } else {
                                                $solved++;
                                                if ($question->answer == $my_response) {
                                                    $marks = getCorrectMarksOfQuestion($question->question_id);
                                                    $correct++;
                                                    $bg_color = 'text-success';
                                                } else {
                                                    $incorrect++;
                                                    $marks = getIncorrectMarksOfQuestion($question->question_id);
                                                    $bg_color = 'text-danger';
                                                }
                                            }
                                        ?>

                                            <div>
                                                <div class="card border border mt-1">
                                                    <div class="card-body">
                                                        <b><small>Q.<?= $question_no++ ?></small> <?= nl2br($question->question) ?></b><br />
                                                        <img src="<?= $question->image ?>" class="mx-5 rounded" style="max-width: 100%;">
                                                        <br>
                                                        <br>
                                                        <p class="card-text">
                                                            <?php
                                                            $options = array('a', 'b', 'c', 'd');
                                                            $l = 0;
                                                            foreach ($options as $option) :
                                                            ?>
                                                                <label>
                                                                    <?= strtoupper($options[$l]) ?>
                                                                    <?
                                                                        if($my_response==$options[$l]){
                                                                            $checked="checked";
                                                                        }else{
                                                                            $checked="";
                                                                        }
                                                                    ?>
                                                                    <input type="radio" value="<?= $option ?>" name="<?= $question->question_id ?>" onclick="return false" <?= $checked ?>>


                                                                    <?php $option_name = "option_" . $options[$l];
                                                                    echo $question->$option_name; ?>
                                                                </label><br>
                                                            <?php $l++;
                                                            endforeach ?>
                                                            <b>Correct Answer: <?= strtoupper($question->answer) ?></b><br>
                                                            <b class="<?= $bg_color ?>">Marks Awarded: <?= ($marks) ?></b>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        <?php $sl++;
                        }
                        ?>
                    </div>
                </div>
            </div>

            <script>
                $("#final_score").text('<?= $enrolled_data->total_marks ?>')
                $("#solved_questions").text('<?= $incorrect + $correct ?>')
                $("#left_questions").text('<?= $question_no - 1 - ($incorrect + $correct) ?>')
                $("#correct_questions").text('<?= $correct ?>')
                $("#incorrect_questions").text('<?= $incorrect ?>')
            </script>
        </div>
    </div>
</body>

</html>
<script>
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>