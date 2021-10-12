<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Russo+One&family=Black+Han+Sans&Roboto+Condensed:ital@1&family=Yanone+Kaffeesatz:wght@700&display=swap" rel="stylesheet">
    <?php require 'Includes/head.php'; ?>
    <style>
        .disabled {
            pointer-events: none
        }

        .sidenav {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 1;
            top: 0;
            right: 0;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 40px;
        }

        .sidenav .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }
    </style>
    <style>
        #server_response {
            height: 50px;
            position: fixed;
            bottom: 10px;
            width: 350px;
            background-color: #155724;
            opacity: 1;
            z-index: 1;
            left: 10%;
            display: none;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div id="server_response" onclick="$('#server_response').hide()" class="text-center py-2 text-white px-4 mx-auto"><span id="server_message">Answer Saved</span><i class="fas fa-times float-right mr-2 mt-1"></i></div>
        <div class="row">
            <button class="btn btn-outline-primary btn-light mt-3 float-right mr-3" style="
            position: fixed;
            z-index: 1;
            top: 0;
            display:none;
            right: 0;" id="dashboard_opening_button" onclick="openNav()"><i class="far fa-2x fa-bars"></i></button>
            <div id="mySidenav" class="sidenav bg-light">
                <button class="float-left ml-2" onclick="$.alert($('#instruction_div').html())"><i class="fad fa-info"></i></button>
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                <h3 class="text-primary text-center" style="font-family: 'Abril Fatface', cursive;">Dashboard</h3>
                <center> <button class="btn btn-danger text-white" style="display:none" id="submit_test" onclick="SubmitTest()">End Test</button></center>

                <div id="accordion_dashboard">
                    <?php
                    $sl = 1;
                    $question_no = 1;
                    foreach ($section_data as $section) {
                        $section_name = $section->section;
                        $new_name = $section_name . '_questions';
                        $array = $$new_name;
                    ?>

                        <div class="card mt-2 ">
                            <div class="card-header" id="heading_dashboard<?= $sl ?>">
                                <h5 class="mb-0">
                                    <button class="btn btn-link container-fluid <?php if ($sl != 1) {
                                                                                    echo " collapsed";
                                                                                } ?>" data-toggle="collapse" data-target="#collapse_dashboard<?= $sl ?>" aria-expanded="
                                    <?php if ($sl == 1) {
                                        echo "true";
                                    } else {
                                        echo "false";
                                    } ?>" aria-controls="collapse_dashboard<?= $sl ?>">
                                        <span style="font-family: 'Russo One', sans-serif;" class="text-dark"><?= $section_name ?></span>
                                    </button>
                                </h5>
                            </div>

                            <div id="collapse_dashboard<?= $sl ?>" class="collapse <?php if (1) {
                                                                                        echo " show";
                                                                                    } ?>" aria-labelledby="heading_dashboard<?= $sl ?>">
                                <div class="card-body">
                                    <?php
                                    foreach ($array as $question) { ?>
                                        <button class="btn btn-primary mt-1 fas  fa-eye-slash button_<?= $question_no ?>" id="button_<?= $question->question_id ?>" style="width: 75px;" onclick="next('<?= ($question_no) ?>','<?= $question->question_id ?>')"> <?= $question_no++ ?></button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php $sl++;
                    }
                    $total_questions = $question_no - 1; //-1 is for last increment   
                    ?>
                </div>
            </div>

            <script>
                function openNav() {
                    document.getElementById("mySidenav").style.width = "70%";
                }

                function closeNav() {
                    document.getElementById("mySidenav").style.width = "0";
                }
            </script>
            <div class="container-fluid align-self-end p-3">
                <span class="mb-5" style="font-size: 20px;">
                    <button class="float-left badge" id="timer_button" style="font-size: 10px;display:none" onclick="$('#time_span').toggle();$('#timer_button').toggle()">Show Timer</button>
                    <span id="time_span" onclick="$('#time_span').toggle();$('#timer_button').toggle()">
                        <i class="fad fa-alarm-clock"></i> <b id="time" style="color: red;"> <?= round($enrolled_test_data->time_left / 60) ?></b> <small>Minutes</small></span>
                </span>
                <br>
                <br>
                <!-- <center>
                    <button class="btn btn-danger text-white" style="display:none" id="submit_test" onclick="SubmitTest()" style="width: 150px;">End Test</button>
                </center> -->
                <div id="accordion" onclick="closeNav()">
                    <div id="collapseInstruction" class="collapse show" aria-expanded="false" data-parent="#accordion">
                        <div class="card-body text-left col-lg-6 mx-auto">
                            <div id="instruction_div">
                                <span class="my-3" style="font-family: 'Abril Fatface', cursive;">Test Name:<?= getTestName($test_data->test_id) ?> </span>

                                <ul class="mt-5">
                                    <strong>Marking Scheme</strong><br>
                                    <li>Questions are in bold text with sky blue background</li>
                                    <li>You will be awarded with +4 marks for each right answer & -1 for each wrong answer</li>
                                </ul>

                                <ul>
                                    <strong>Navigation's & Features:</strong><br>
                                    <li> Click on question number to jump on a particular question.</li>
                                    <li> Click on next button to view next question. & previous button to view previous question.</li>
                                    <li> Timer is running on top of the page.</li>
                                    <li> Click On <i class="fas fa-lightbulb-on"></i> to mark for review.</li>
                                    <li> Click On <i class="fas fa-lightbulb-slash"></i> to un-mark for review.</li>
                                    <li> Click On <i class="fas fa-times"></i> to clear a response</li>
                                    <li> Answers are automatically saving.<br>
                                    <li> You must have to be connect with internet while having test</li>
                                    <li> <b>End Test</b> button is available at top of Page.</li>
                                    <li> You can see instructions anytime by clicking on <i class="fad fa-info"></i> on top right corner of screen </li>
                                    <li> If you are disturbed by timer. Click On Timer To Hide/show it </li>
                                </ul>

                                <ul>
                                    <strong>Understanding Icons & Color</strong><br>
                                    <li> You are allowed to give test in Fullscreen only</li>
                                    <li> Questions with <i class="fas fa-eye-slash"></i> (in blue color) are not viewed</li>
                                    <li> Questions with in yellow background are viewed but not answered</li>
                                    <li> Questions with <i class="fas fa-check-circle"></i> (in green colour) are answered questions</li>
                                    <li> Questions with <i class="fas fa-flag"></i> (in yellow color) are marked for review</li>
                                    <li> Questions with <i class="fas fa-flag"></i> ( green color) are answered but marked for review.</li>
                                </ul>
                            </div>
                            <center><button type="button" class="btn btn-success" onclick="next(1);$('#dashboard_opening_button').show();$('#submit_test').show()" style="font-family: 'Black Han Sans';">Start Solving</button></center>
                        </div>

                    </div>
                    <?php
                    // $sl=1;
                    $question_no = 1;
                    foreach ($section_data as $section) {
                        $section_name = $section->section;
                        $new_name = $section_name . '_questions';
                        $array = $$new_name;
                    ?>
                        <?php
                        foreach ($array as $new_question) {
                        ?>
                            <div id="collapse<?= $question_no; ?>" class="collapse" aria-expanded="false" data-parent="#accordion">
                                <div class="">

                                    <table class="table table-borderless ">

                                        <tr>
                                            <td>
                                                <center>
                                                    <button type="button" class="btn btn-info fas fa-lightbulb-on  mx-2" id="flag_button_<?= $question_no; ?>" title="Flag question" onclick="flag('<?= $question_no; ?>')"><span style="font-family: 'Roboto Condensed', sans-serif;" id="flag_span_<?= $question_no; ?>">Flag</span></button>
                                                    <button type="button" class="btn btn-info text-white mx-2 fas fa-times disabled" id="clear_response_<?= $new_question->question_id; ?>" title="Clear Response" onclick="resetOption('<?= $new_question->question_id; ?>','<?= $question_no; ?>')"><span style="font-family: 'Roboto Condensed', sans-serif;"> Clear Response</span></button>
                                                </center>
                                                <br><br>
                                                <button type="button" class="btn" title="Previous" onclick="previous('<?= ($question_no - 1) ?>','<?= $new_question->question_id; ?>')" <?php if ($question_no == 1) {
                                                                                                                                                                                            echo "disabled";
                                                                                                                                                                                        } ?> style="background-color:#9FF841"> <i class="fas fa-chevron-circle-left"></i> Previous</button>
                                                <?php if ($question_no < $total_questions) { ?>
                                                    <button type="button" class="float-right btn" title="Next" onclick="next('<?= ($question_no + 1) ?>','<?= $new_question->question_id ?>')" style="background-color:#9FF841"> <i class="fas fa-chevron-circle-right"></i> Next</button>
                                                <?php } else { ?>
                                                    <button type="button" class="float-right btn disabled" title="Next" style="background-color:#9FF841"> <i class="fas fa-chevron-circle-right"></i> Next</button>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <tbody>

                                            <tr>
                                                <th>
                                                    <small>Q.<?= $question_no ?></small>
                                                </th>
                                            </tr>
                                            <tr style="background-color:#D2FAF6">
                                                <td>
                                                    <?= nl2br($new_question->question) ?>
                                                </td>
                                            </tr>

                                            <tr style="background-color:#D2FAF6">
                                                <th>
                                                    <?
                                                    if($new_question->image!=""){
                                                       echo "<img src='$new_question->image' style='max-width:100%;'>";
                                                    }
                                                    ?>
                                                </th>
                                            </tr>

                                            <tr>
                                                <th>
                                                    <small class="text-muted">Options</small>
                                                </th>
                                            </tr>
                                            <?php
                                            $options = array('a', 'b', 'c', 'd');
                                            shuffle($options);
                                            $l = 0;
                                            foreach ($options as $option) :
                                            ?>
                                                <tr>
                                                    <td>
                                                        <label>
                                                            <input type="radio" class="question_<?= $new_question->question_id ?>" id="question_<?= $new_question->question_id . "_" . $option ?>" value="<?= $option ?>" name="<?= $new_question->question_id ?>" onchange="save_response('<?= $option ?>','<?= $new_question->question_id ?>',`<?= $question_no ?>`)">
                                                            <?php $option_name = "option_" . $option;
                                                            echo $new_question->$option_name ?>
                                                        </label>
                                                    </td>
                                                </tr>

                                            <?php

                                                $l++;
                                            endforeach ?>

                                        </tbody>

                                    </table>

                                </div>
                            </div>
                    <?php $question_no++;
                        }
                    }

                    ?>
                </div>

            </div>
        </div>
    </div>
    <script>
        var total_flag = 0;
        var timeLeft = <?= $enrolled_test_data->time_left; ?>;
        var tabChangeCount = <?= $enrolled_test_data->tabchange ?>;
        var test_id = `<?= $test_data->test_id ?>`;
        var base_url = `<?= getenv('app.baseURL') ?>`
        var start_started = 0;

        function checkInternetConnection() {
            var status = navigator.onLine;
            if (status) {
                // console.log('Internet Available !!');
                setTimeout(function() {
                    checkInternetConnection();
                }, 3000);
            } else {
                var connection_alert = $.alert("Please Check Your Internet Connection")
                setTimeout(function() {
                    var status = navigator.onLine;
                    if (!status) {
                        deleteAll();
                        startCheckingForConnection()
                    } else {
                        connection_alert.close()
                        setTimeout(function() {
                            checkInternetConnection();
                        }, 3000);
                    }
                }, 5000);
            }
        }

        function startCheckingForConnection() {
            var status = navigator.onLine;
            if (status) {
                history.go();
            } else {
                setTimeout(() => {
                    startCheckingForConnection();
                }, 1000);
            }
        }

        function deleteAll() {
            document.body.innerHTML = "<h3 class='text-center mt-5 text-danger'>Please Check Your Internet Connection And refresh the page</h3><br><br><center>Your Solved questions are still saved</center>";
        }

        function SubmitTest() {
            message = " You have " + Math.round(timeLeft / 60) + " Minutes left"
            if (total_flag > 0) {
                message = message + " And " + total_flag + " question(s) marked for review."
            }

            message = message + "<br>Do you still Want to submit test?"
            $.confirm({
                title: 'Please Confirm!',
                content: message,
                buttons: {
                    Yes: function() {
                        submitNow();
                    },
                    No: function() {}
                }
            });
        }

        $(document).ready(function() {
            checkInternetConnection();
            $(window).blur(function() {
                updateTabChange();
            });
        });


        function startSolving() {
            // console.log("Stage 1")
            openFullscreen()
            updateTime();
            startTest();
            var start_started = 1;
        }

        function startTest() {
            var minute = Math.floor(timeLeft / 60);
            var second = timeLeft % 60;
            if (timeLeft <= 0) {
                clearTimeout(tm);
            } else {
                document.getElementById("time").innerHTML = minute + ":" + second;
            }
            timeLeft--;
            var tm = setTimeout(function() {
                startTest()
            }, 1000);
            // console.log(timeLeft)
            if (timeLeft == 0) {
                $.alert("Time Over");
                submitNow()
            }
        }
    </script>

    <? include 'Includes/test_file.php'?>
</body>

</html>