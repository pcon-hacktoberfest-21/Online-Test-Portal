<!DOCTYPE html>
<html lang="en">

<head>
    <? include 'Include/head.php'?>
    <link href="https://fonts.googleapis.com/css2?family=Alata&family=Barlow+Condensed:wght@100&family=Libre+Baskerville:wght@700&family=Playfair+Display&display=swap" rel="stylesheet">

</head>

<body>
    <? include 'Include/nav.php'?>

    <div class="container-fluid">
        <?
            $session = session();
            $flash_response=$session->getFlashdata('flash_response');;
            if($flash_response!=""){
                echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                <strong><center>$flash_response</center></strong>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                  <span aria-hidden='true'>&times;</span>
                </button>
              </div>";
            } ?>
        <!-- <a href='<?= getenv('app.baseURL') ?>/EditTest/ViewAttendance/<?= $test_data->test_id; ?>' class="btn btn-primary float-left">View Attendance</a> -->

        <div class="col-lg-4 mx-auto">
            <div class="form-row">
                <div class="form-group col-md-12">
                    <center><label for="test_status" style="font-family: 'Barlow Condensed', sans-serif;">Test Link</label></center>
                    <div class="input-group">
                        <input type="text" class="form-control" value="<?= getenv('app.baseURL') . "/Exam/" . $test_data->test_id ?>" id="test_link" readonly>
                        <div class="input-group-append">
                            <span class="input-group-text btn btn-sm btn-primary" id="basic-addon2" onclick="myFunction()" onmouseout="outFunc()"> <span class="tooltiptext" id="myTooltip">Copy</span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 px-3">
                <form method="post" class="border border dark rounded p-4" action="<?= getenv('app.baseURL') ?>/EditTest/EditSetting/<?= $test_data->test_id ?>">
                    <h3 class="text-center my-3" style="font-family: 'Playfair Display', serif;">Test Setting</h3>
                    <center> <label style="font-family: 'Barlow Condensed', sans-serif;">Test Window</label></center>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="test_from" style="font-family: 'Barlow Condensed', sans-serif;">From</label><br>
                            <input type="datetime-local" class="form-control" name="test_from" id="test_from" value="<?= date("Y-m-d\TH:i:s", $test_data->sdatetime) ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="test_to" style="font-family: 'Barlow Condensed', sans-serif;">To</label><br>
                            <input type="datetime-local" class="form-control" name="test_to" id="test_to" value="<?= date("Y-m-d\TH:i:s", $test_data->edatetime) ?>" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="testname" style="font-family: 'Barlow Condensed', sans-serif;">Test Name</label>
                            <input type="text" class="form-control" name="test_name" value="<?= $test_data->test_name ?>" id="testname" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="durations" style="font-family: 'Barlow Condensed', sans-serif;">Duration</label>
                            <input type="text" class="form-control" name="duration" id="durations" value="<?= $test_data->test_duration ?>" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="test_status" style="font-family: 'Barlow Condensed', sans-serif;">Test Status</label>
                            <select id="test_status" name="test_status" class="form-control" required>
                                <option value="1">Live</option>
                                <option value="0">Hidden</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="show_result" style="font-family: 'Barlow Condensed', sans-serif;">Show Result</label>
                            <select id="show_result" name="show_result" class="form-control" required>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <script>
                            $("#test_status").val(`<?= $test_data->isActive ?>`);
                            $("#show_result").val(`<?= $test_data->show_result ?>`);
                        </script>
                    </div>

                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" name="isPublic" id="isPublic" <?if($test_data->isPublic){echo "checked";}?>>
                        <label class="custom-control-label" for="isPublic">Visible To All Users</label>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" name="nitOnly" id="nitOnly" <?if($test_data->nitOnly){echo "checked";}?>>
                        <label class="custom-control-label" for="nitOnly">NIT Users Only</label>
                    </div>
                    <br />
                    <div class="form-group">
                        <label for="password">Test Password (Optional)</label>
                        <input type="text" name="password" class="form-control" id="password" placeholder="Test Password" value="<?= $test_data->password ?>">
                    </div>
                    <!-- <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="test_status" style="font-family: 'Barlow Condensed', sans-serif;">Solution Link</label>
                            <input type="url" id="test_status" name="solution_link" value="<?= $test_data->solution ?>" class="form-control">
                        </div>
                    </div> -->
                    <center><button type="submit" name="update_test_setting" class="btn btn-primary" style="font-family: 'Libre Baskerville', serif;">Update Test Setting</button></center>

                </form>
                <div class="p-5 border mt-1">
                    <div id="accordion2">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <button class="btn text-center" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo" style="font-family: 'Alata', sans-serif;">
                                    Upload Question Via Excel File
                                </button>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion2">
                                <div class="card-body">
                                    <form enctype="multipart/form-data" action="<?= getenv('app.baseURL') ?>/EditTest/BulkUpload/<?= $test_data->test_id ?>" method="POST">
                                        <div class="form-group">
                                            <label for="exampleFormControlFile1">File <small>(only xlsx)</small></label>
                                            <input type="file" class="form-control-file" name="excel_file" id="exampleFormControlFile1" accept=".xlsx">
                                        </div>
                                        <button type="submit" class="btn btn-primary tex-light" style="font-family: 'Libre Baskerville', serif;"><i class="far fa-upload"></i> Upload</button>
                                    </form>
                                    <small style="color:red;font-family: 'Playfair Display', serif;">You have to upload excel in a particular format <br> image need to be upload manually</small>
                                    <a style="color:blue;font-family: 'Playfair Display', serif;" href="<?= getenv('app.baseURL') ?>/public/question format.xlsx" download>Download Sample Excel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="accordion">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <button class="btn text-center" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="font-family: 'Alata', sans-serif;">
                                    Danger Settings
                                </button>
                            </div>

                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body">
                                    <center>
                                        <a class="btn btn-danger" onclick="return confirm('Do You Really Want To Delete Test? All Enrollment & Response from user will be deleted immediately')" href="<?= getenv('app.baseURL') ?>/EditTest/DeleteTest/<?= $test_data->test_id ?>" style="font-family: 'Libre Baskerville', serif;"><i class="fad fa-trash-alt"></i> Delete Test</a>
                                        <a class="btn btn-danger" onclick="return confirm('Do You Really Want To Delete All Questions? All response from user will be deleted immediately')" href="<?= getenv('app.baseURL') ?>/EditTest/DeleteAllQuestion/<?= $test_data->test_id ?>" style="font-family: 'Libre Baskerville', serif;"><i class="fad fa-trash-alt"></i> Delete All Questions</a><br><br>
                                        <a class="btn btn-danger" onclick="return confirm('Do You Really Want To Delete All Enrollment')" href="<?= getenv('app.baseURL') ?>/EditTest/DeleteAllEnrollment/<?= $test_data->test_id ?>" style="font-family: 'Libre Baskerville', serif;"><i class="fad fa-trash-alt"></i> Delete All Enrollment</a>
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-lg-8 px-3">
                <form method="post" class="border border dark rounded p-4 py-3" enctype="multipart/form-data" action="<?= getenv('app.baseURL') ?>/EditTest/AddQuestion/<?= $test_data->test_id ?>">
                    <h3 class="text-center my-3" style="font-family: 'Playfair Display', serif;">Add Question</h3>

                    <input type="hidden" name="question_id" value="<?= uniqid('QU') ?>">

                    <div class="form-group">
                        <label for="question" style="font-family: 'Playfair Display', serif;">Question</label>
                        <textarea class="form-control" id="question" name="question" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="question_image" style="font-family: 'Playfair Display', serif;">Image <small>(If Any)</small></label>
                        <input type="file" class="form-control-file" name="question_image" id="question_image" accept="image/*">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="option_a" style="font-family: 'Playfair Display', serif;">Option A</label><br>
                            <input type="text" class="form-control" name="option_a" id="option_a" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="option_b" style="font-family: 'Playfair Display', serif;">Option B</label><br>
                            <input type="text" class="form-control" name="option_b" id="option_b" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="option_c" style="font-family: 'Playfair Display', serif;">Option C</label><br>
                            <input type="text" class="form-control" name="option_c" id="option_c" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="option_d" style="font-family: 'Playfair Display', serif;">Option D</label><br>
                            <input type="text" class="form-control" name="option_d" id="option_d" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-lg-4">
                            <label for="correct_answer" style="font-family: 'Playfair Display', serif;">Correct Answer</label>
                            <select class="form-control" name="correct_answer" id="correct_answer" required>
                                <option value="" selected disabled>Correct Answer</option>
                                <option value="a">A</option>
                                <option value="b">B</option>
                                <option value="c">C</option>
                                <option value="d">D</option>
                            </select>
                        </div>
                        <div class="form-row col-lg-4">
                            <div class="form-group col-md-6">
                                <label for="positiveMarking" style="font-family: 'Playfair Display', serif;">Correct Marks</label><br>
                                <input type="number" class="form-control" name="positiveMarking" id="positiveMarking" value="4" min="0" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="negativeMarking" style="font-family: 'Playfair Display', serif;">Incorrect Marks</label><br>
                                <input type="number" class="form-control" name="negativeMarking" id="negativeMarking" value="-1" max="0" required>
                            </div>
                        </div>
                        <div class="form-group col ">
                            <label for="section" style="font-family: 'Playfair Display', serif;">Section</label><br>
                            <input type="text" class="form-control" name="section" id="section" placeholder="Physics/Chemistry/Biology" required>
                        </div>
                    </div>
                    <center><button type="submit" name="add_question" class="btn btn-primary" style="font-family: 'Libre Baskerville', serif;">Add Question</button></center>
                </form>
            </div>
        </div>












        <div class="card col-lg-10 mx-auto px-3">
            <h2 class="text-center mt-2 text-primary" style="font-family: 'Abril Fatface', cursive;">All Questions</h2>
            <div class="card-body">
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
                                    <button class="btn btn-link container-fluid" data-toggle="collapse" data-target="#collapse_dashboard<?= $sl ?>" aria-controls="collapse_dashboard<?= $sl ?>">
                                        <span style="font-family: 'Playfair Display', serif;font-weight:900" class="text-dark"><?= $section_name ?></span>
                                    </button>
                                </h5>
                            </div>

                            <div id="collapse_dashboard<?= $sl ?>" class="collapse show" aria-labelledby="heading_dashboard<?= $sl ?>">
                                <div class="card-body">
                                    <?php
                                    foreach ($array as $question) { ?>
                                        <div>
                                            <div class="card mt-1">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        Q.<?= $question_no++ ?>
                                                        <?= nl2br($question->question) ?>
                                                    </h5>
                                                    <?
                                                    if($question->image!="" ){
                                                    ?>
                                                    <img src="<?= $question->image ?>" style="max-width: 100%;">
                                                    <?}?>
                                                    <p class="card-text">
                                                        <?php
                                                        $options = array('a', 'b', 'c', 'd');
                                                        $l = 0;
                                                        foreach ($options as $option) :
                                                        ?>
                                                            <label>
                                                                <?= strtoupper($options[$l]) ?>
                                                                <input type="radio" value="<?= $option ?>" name="<?= $question->question_id ?>" onclick="return false">
                                                                <?php $option_name = "option_" . $option;
                                                                echo $question->$option_name ?>
                                                            </label><br>
                                                        <?php

                                                            $l++;
                                                        endforeach ?>
                                                        <b>Correct Answer: <?= ($question->answer) ?></b>
                                                        <button onclick="deleteQuestion(`<?= $question->question_id ?>`)" class="btn float-right ml-1"><i class="fad fa-trash-alt text-danger"></i></button>
                                                        <button class="btn float-right" onclick="editQuestion(`<?= $question->question_id ?>`,`<?= $question->question ?>`,`<?= $question->option_a ?>`,`<?= $question->option_b ?>`,`<?= $question->option_c ?>`,`<?= $question->option_d ?>`,`<?= $question->section ?>`,`<?= strtolower($question->answer) ?>`,`<?= $question->positiveMarking ?>`,`<?= $question->negativeMarking ?>`)" class="float-right"><i class="fas fa-pencil text-primary"></i></button>
                                                        <br><span class="text-success">Correct Marks: <?= $question->positiveMarking ?></span>
                                                        <br><span class="text-danger">Incorrect Marks: <?= $question->negativeMarking ?></span>
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
                    $total_questions = $question_no - 1; //-1 is for last increment   
                    ?>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="editQuestionModal" tabindex="-1" role="dialog" aria-labelledby="editQuestionModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Edit Question</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" enctype="multipart/form-data" action="<?= getenv('app.baseURL') ?>/EditTest/EditQuestion/<?= $test_data->test_id ?>">
                            <input type="hidden" id="question_id_edit" name="question_id" value="">

                            <div class="form-group">
                                <label for="question_edit">Question</label>
                                <textarea class="form-control" id="question_edit" name="question" rows="3" required=""></textarea>
                            </div>
                            <div class="form-group">
                                <label for="question_image">Image <small style="color:red">(If this question contains a image you need to upload image again)</small></label>
                                <input type="file" class="form-control-file" name="question_image" id="question_image">
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="option_a_edit">Option A</label><br>
                                    <input type="text" class="form-control" name="option_a" id="option_a_edit" required="">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="option_b_edit">Option B</label><br>
                                    <input type="text" class="form-control" name="option_b" id="option_b_edit" required="">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="option_c_edit">Option C</label><br>
                                    <input type="text" class="form-control" name="option_c" id="option_c_edit" required="">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="option_d_edit">Option D</label><br>
                                    <input type="text" class="form-control" name="option_d" id="option_d_edit" required="">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="correct_answer_edit">Correct Answer</label>
                                    <select class="form-control" name="correct_answer" id="correct_answer_edit" required="">
                                        <option value="" disabled="">Correct Answer</option>
                                        <option value="a">A</option>
                                        <option value="b">B</option>
                                        <option value="c">C</option>
                                        <option value="d">D</option>
                                    </select>
                                </div>

                                <div class="form-group col">
                                    <label for="section">Section</label><br>
                                    <input type="text" class="form-control" name="section" id="section_edit" placeholder="Physics/Chemistry/Biology" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="positive_mark_edit">CorrectMarks</label>
                                    <input type="number" class="form-control" name="positiveMarking" id="positive_mark_edit" placeholder="Positive Marks" min="0" required>
                                </div>

                                <div class="form-group col">
                                    <label for="negative_mark_edit">Incorrect Marks</label><br>
                                    <input type="number" class="form-control" name="negativeMarking" id="negative_mark_edit" placeholder="Negative Marks" max="0" required>
                                </div>
                            </div>
                            <center><button type="submit" name="update_question" class="btn btn-primary">Update Question</button></center>
                        </form>
                    </div>
                </div>
            </div>
        </div>





















    </div>
</body>

</html>


<script>
    // editQuestion('123456',"Question","option a","option b","option c","option d","d");
    function editQuestion(id, question, option_a, option_b, option_c, option_d, section, answer, positive_marks, negative_marks) {
        $("#editQuestionModal").modal('show');
        $("#question_id_edit").val(id)
        $("#question_edit").val(question);
        $("#option_a_edit").val(option_a);
        $("#option_b_edit").val(option_b);
        $("#option_c_edit").val(option_c);
        $("#option_d_edit").val(option_d);
        $("#section_edit").val(section)
        $("#correct_answer_edit").val(answer);
        $("#positive_mark_edit").val(positive_marks);
        $("#negative_mark_edit").val(negative_marks);
        console.log(answer)
    }
</script>

<script>
    function deleteQuestion(id) {
        if (confirm('Do You really Want to delete')) {
            $("body").append(`
            <form id="delete_question_form" action="<?= getenv('app.baseURL') ?>/EditTest/DeleteQuestion/<?= $test_data->test_id ?>" method="post">
                <input name="question_id" value="${id}">
                <input name="delete_question" value="${id}">
            </form>
            `)
            $("#delete_question_form").submit();
        }
    }
</script>



<!-- Link Copy Style -->

<style>
    .tooltip {
        position: relative;
        display: inline-block;
    }

    .tooltip .tooltiptext {
        visibility: hidden;
        width: 140px;
        background-color: #555;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px;
        position: absolute;
        z-index: 1;
        bottom: 150%;
        left: 50%;
        margin-left: -75px;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .tooltip .tooltiptext::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: #555 transparent transparent transparent;
    }

    .tooltip:hover .tooltiptext {
        visibility: visible;
        opacity: 1;
    }
</style>

<script>
    function myFunction() {
        var copyText = document.getElementById("test_link");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");
        var tooltip = document.getElementById("myTooltip");
        tooltip.innerHTML = "Copied";
    }

    function outFunc() {
        var tooltip = document.getElementById("myTooltip");
        tooltip.innerHTML = "Copy";
    }
</script>