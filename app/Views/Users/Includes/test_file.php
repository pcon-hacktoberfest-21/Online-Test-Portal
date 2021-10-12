<script>
    var server_saving = 0;
    var total_questions = <?= $total_questions ?>;
    var response_interval = <?= rand(getenv('auto_save_interval') * 1000, getenv('auto_save_interval') * 1500) ?>;

    function resetOption(name, question_no) {
        let question_class = `.question_${name}`
        $(question_class).attr('disabled', false)
        let id = `.button_${question_no}`;
        let reset_buttonid = "#clear_response_" + name
        $(reset_buttonid).addClass('disabled');
        $(id).removeClass("fa-check-circle")
        $(`input[name="${name}"]`).prop('checked', false);
        $(id).css("background-color", "");
        $(id).addClass('btn-info text-dark');
        var answers = localStorage.getItem("answers_<?= $test_data->test_id ?>");
        if (answers) {
            var answers = JSON.parse(answers);
            if (name in answers) {
                delete answers[name]
                localStorage.setItem("answers_<?= $test_data->test_id ?>", JSON.stringify(answers))
                // console.log("Response Locally Found")
            }
        } else {
            var form = new FormData();
            form.append("question", name);
            var settings = {
                "url": `${base_url}/Test/ClearResponse/${test_id}`,
                "method": "POST",
                "timeout": 0,
                "processData": false,
                "mimeType": "multipart/form-data",
                "contentType": false,
                "data": form
            };
            $.ajax(settings).done(function(response) {
                // console.log(response);
            });
        }

        // removing from total viewed
        var total_answers = localStorage.getItem("total_answers_<?= $test_data->test_id ?>")
        if (total_answers) {
            total_answers = JSON.parse(total_answers)
            if (name in total_answers) {
                delete total_answers[name]
                localStorage.setItem("total_answers_<?= $test_data->test_id ?>", JSON.stringify(total_answers))
                // console.log(JSON.stringify(total_answers))
            }
        }


    }

    function save_response(response, question, question_no) {
        let id = `#button_${question}`;
        $(id).css("background-color", "green");
        $(id).removeClass("fa-eye")
        $(id).addClass("fa-check-circle")
        $(id).addClass("text-light")
        $(id).removeClass("text-dark")
        let question_class = `.question_${question}`
        $(question_class).attr('disabled', true)

        let reset_buttonid = "#clear_response_" + question
        $(reset_buttonid).removeClass('disabled');
        var answers = localStorage.getItem("answers_<?= $test_data->test_id ?>")
        var total_answers = localStorage.getItem("total_answers_<?= $test_data->test_id ?>")
        var newly_solved = `{"${question}":"${response}"}`
        if (!total_answers) {
            // console.log("First Time Addition")
            localStorage.setItem("total_answers_<?= $test_data->test_id ?>", newly_solved)
        } else {
            // console.log("adding to total answer")
            total_answers = JSON.parse(total_answers)
            localStorage.setItem("total_answers_<?= $test_data->test_id ?>", JSON.stringify(addToObject(total_answers, question, response)))
        }
        if (!answers) {
            localStorage.setItem("answers_<?= $test_data->test_id ?>", newly_solved)
        } else {
            answers = JSON.parse(answers)
            localStorage.setItem("answers_<?= $test_data->test_id ?>", JSON.stringify(addToObject(answers, question, response)));
        }
        if (server_saving == 0) {
            saveResponseOnDB();
        }
        localStorage.setItem("isChanged", 1)
    }

    function saveResponseOnDB() {
        // console.log("started Server Saving")
        var server_saving = 1;
        setInterval(() => {
            sendAnswer();
        }, response_interval);
    }

    function sendAnswer() {
        if (localStorage.getItem("isChanged") == 1) {
            solved_question = localStorage.getItem("answers_<?= $test_data->test_id ?>")
            localStorage.removeItem("answers_<?= $test_data->test_id ?>");
            localStorage.setItem("old_solved_<?= $test_data->test_id ?>", solved_question)
            localStorage.setItem("isChanged", 0)
            var form = new FormData();
            form.append("response", solved_question);
            var settings = {
                "url": `${base_url}/Test/SaveResponse/${test_id}`,
                "method": "POST",
                "timeout": 0,
                "processData": false,
                "mimeType": "multipart/form-data",
                "contentType": false,
                "data": form
            };
            $.ajax(settings).done(function(response) {
                // console.log((response))
                if (response == 1) {
                    $("#server_message").text('Answers Auto saved');
                    $("#server_response").css('background-color', 'green');
                    $("#server_response").show();
                    setTimeout(() => {
                        $("#server_response").hide();
                    }, 2000);
                    localStorage.removeItem("old_solved_<?= $test_data->test_id ?>")
                }
                if (response == 0) {
                    $("#server_message").text('Something Went Wrong From Our side. Please Report to admin');
                    $("#server_response").show();
                    $("#server_response").css('background-color', 'red');
                    setTimeout(() => {
                        $("#server_response").hide();
                    }, 8000);
                }
                if (response == 2) {
                    $("#server_message").text("You Already Submitted this test. Your response is no more saving now. Please close this tab");
                    $("#server_response").show();
                    $("#server_response").css('background-color', 'red');
                }
                if (response == 3) {

                    if (response == 2) {
                        $("#server_message").text("Test Window is closed.Your response is no more saving now. Please close this tab");
                        $("#server_response").show();
                        $("#server_response").css('background-color', 'red');
                    }
                }
            });
        }
    }



    function submitNow() {
        $("#submit_test").addClass('disabled');
        setTimeout(() => {
            $("#submit_test").removeClass('disabled');
        }, 30000);
        $("#server_message").html(`Saving Your Response Please Wait for a while`);
        $("#server_response").show();
        $("#server_response").css('background-color', '#155724');
        if (localStorage.getItem("isChanged") == 1) {
            solved_question = localStorage.getItem("answers_<?= $test_data->test_id ?>")
            if (solved_question) {
                var form = new FormData();
                form.append("response", solved_question);
                var settings = {
                    "url": `${base_url}/Test/SaveResponse/${test_id}`,
                    "method": "POST",
                    "timeout": 0,
                    "processData": false,
                    "mimeType": "multipart/form-data",
                    "contentType": false,
                    "data": form
                };
                $.ajax(settings).done(function(response) {
                    exitTestWindow();
                });
            }
        } else {
            exitTestWindow();
        }
    }

    function exitTestWindow() {
        total_answers = encodeURI(localStorage.getItem("total_answers_<?= $test_data->test_id ?>"))
        if (!total_answers) {
            total_answers = "";
        }
        let body_text = `<form method="post" id="submit_test_form" action="<?= getenv('app.baseURL') ?>/Test/Submit/<?= $test_data->test_id ?>">
                                        <input type="hidden" name="total_answers" value="${total_answers}">
                                        <input type="hidden" name="submit_form" value="yes">
                                    </form>`;
        // console.log(body_text);
        $('body').append(body_text)
        $("#submit_test_form").submit();
    }




    function next(sl) {
        if (start_started == 0) {
            start_started = 1
            startSolving();
        }
        localStorage.setItem('current_question', sl)
        let id = `#collapse${sl}`;
        let id2 = `#collapse${sl - 1}`;

        $(id).collapse('show');
        $(id2).collapse('hide');
        let button_id = `.button_${sl}`;
        $(button_id).addClass('btn-info')
        $(button_id).removeClass('btn-primary')
        if (!$(button_id).hasClass('fa-check-circle')) {
            // $(button_id).addClass('fas fa-eye');
            $(button_id).removeClass('fa-eye-slash');

        }


        // Adding Viewed question
        var total_viewed = localStorage.getItem("total_viewed_<?= $test_data->test_id ?>")
        var newly_viewed = `{"${sl}":"${sl}"}`
        if (!total_viewed) {
            localStorage.setItem("total_viewed_<?= $test_data->test_id ?>", newly_viewed)
        } else {
            total_viewed = JSON.parse(total_viewed)
            localStorage.setItem("total_viewed_<?= $test_data->test_id ?>", JSON.stringify(addToObject(total_viewed, `"${sl}"`, sl)))
        }
    }

    function previous(sl) {
        let id = `#collapse${sl}`;
        let id2 = `#collapse${sl + 1}`;
        // console.log(id);
        $(id).collapse('show');
        $(id2).collapse('hide');
        let button_id = `.button_${sl}`;
        //For highlighting current questions
        $('button').removeClass('btn-outline-warning')
        $(button_id).addClass('btn-info')
        $(button_id).removeClass('btn-primary')
        if (!$(button_id).hasClass('fa-check-circle')) {
            // $(button_id).addClass('fas fa-eye');
            $(button_id).removeClass('fa-eye-slash');
        }



        // Adding Viewed question
        var total_viewed = localStorage.getItem("total_viewed_<?= $test_data->test_id ?>")
        var newly_viewed = `{"${sl}":${sl}}`
        if (!total_viewed) {
            localStorage.setItem("total_viewed_<?= $test_data->test_id ?>", newly_viewed)
        } else {
            total_viewed = JSON.parse(total_viewed)
            localStorage.setItem("total_viewed_<?= $test_data->test_id ?>", JSON.stringify(addToObject(total_viewed, `"${sl}"`, sl)))
        }
    }



    function flag(name) {
        let id = `.button_${name}`;
        let flgid = `#flag_button_${name}`
        let textid = `#flag_span_${name}`
        if ($(flgid).hasClass("fa-lightbulb-on")) {
            $(id).addClass("fas fa-flag")
            $(flgid).addClass("fa-lightbulb-slash")
            $(flgid).removeClass("fa-lightbulb-on")
            total_flag++
            $(textid).html("UnFlag")
        } else {
            $(id).removeClass("fa-flag")
            $(flgid).removeClass("fa-lightbulb-slash")
            $(flgid).addClass("fa-lightbulb-on")

            $(textid).html("Flag")
            total_flag--
        }
    }


    function updateTabChange() {
        tabChangeCount = tabChangeCount + 1;

        $("#server_message").html(`<small>You have ${tabChangeCount} times changed the tab. Changing tab will affect your score</small>`);
        $("#server_response").show();
        $("#server_response").css('background-color', 'red');
        setTimeout(() => {
            $("#server_response").hide();
        }, 8000);
        // $.alert(`You have ${tabChangeCount} times changed the tab. Changing tab will affect your score`)
        var tabChange = new FormData();
        tabChange.append("tabChange", 'yes');
        var settings = {
            "url": `${base_url}/Test/Update/${test_id}`,
            "method": "POST",
            "processData": false,
            "contentType": false,
            "data": tabChange
        };
        $.ajax(settings).done(function(response) {
            //   console.log(response);
        });
    }

    function updateTime() {
        // console.log("updating Time");
        var time = new FormData();
        time.append("update_time", 'yes');
        var settings = {
            "url": `${base_url}/Test/Update/${test_id}`,
            "method": "POST",
            "processData": false,
            "contentType": false,
            "data": time
        };
        $.ajax(settings).done(function(response) {
            //   console.log(response);
            if (response == 101) {
                exitTestWindow();
            }
        });
        var tm2 = setTimeout(function() {
            updateTime()
        }, 10000);
    }


    var addToObject = function(obj, key, value, index) {
        // Create a temp object and index variable
        var temp = {};
        var i = 0;
        // Loop through the original object
        for (var prop in obj) {
            if (obj.hasOwnProperty(prop)) {
                // If the indexes match, add the new item
                if (i === index && key && value) {
                    temp[key] = value;
                }
                // Add the current item in the loop to the temp obj
                temp[prop] = obj[prop];
                // Increase the count
                i++;
            }
        }
        // If no index, add to the end
        if (!index && key && value) {
            temp[key] = value;
        }
        return temp;
    };

    var previous_response = localStorage.getItem("total_answers_<?= $test_data->test_id ?>");
    if (previous_response) {
        Object.entries(JSON.parse(previous_response)).forEach(([key, value]) => {
            // console.log("button_"+key + ' =>' + value)
            button_id = "#button_" + key
            $(button_id).css("background-color", "green");
            $(button_id).removeClass("fa-eye")
            $(button_id).removeClass("fa-eye-slash")
            $(button_id).addClass("fa-check-circle")
            $(button_id).addClass("text-light")
            $(button_id).removeClass("text-dark")
            input_id = "#question_" + key + "_" + value;
            $(input_id).prop('checked', true)
            let clear_response_id = `#clear_response_${key}`
            $(clear_response_id).removeClass('disabled');
            let question_class = `.question_${key}`
            $(question_class).attr('disabled', true)
        });
    }
    var previous_viewed = localStorage.getItem("total_viewed_<?= $test_data->test_id ?>");
    if (previous_viewed) {
        Object.entries(JSON.parse(previous_viewed)).forEach(([key, value]) => {
            // console.log("button_"+key + ' =>' + value)

            let button_id = `.button_${value}`;
            //For highlighting current questions
            $('button').removeClass('btn-outline-warning')
            $(button_id).addClass('btn-info')
            $(button_id).removeClass('btn-primary')
            if (!$(button_id).hasClass('fa-check-circle')) {
                // $(button_id).addClass('fas fa-eye');
                $(button_id).removeClass('fa-eye-slash');
            }


        });
    }
</script>


<script>
    var elem = document.documentElement;

    function openFullscreen() {
        if (elem.requestFullscreen) {
            elem.requestFullscreen();
        } else if (elem.mozRequestFullScreen) {
            /* Firefox */
            elem.mozRequestFullScreen();
        } else if (elem.webkitRequestFullscreen) {
            /* Chrome, Safari and Opera */
            elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) {
            /* IE/Edge */
            elem.msRequestFullscreen();
        }
    }

    /* Close fullscreen */
    function closeFullscreen() {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.mozCancelFullScreen) {
            /* Firefox */
            document.mozCancelFullScreen();
        } else if (document.webkitExitFullscreen) {
            /* Chrome, Safari and Opera */
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) {
            /* IE/Edge */
            document.msExitFullscreen();
        }
    }


    if (document.addEventListener) {
        document.addEventListener('fullscreenchange', exitHandler, true);
        document.addEventListener('mozfullscreenchange', exitHandler, true);
        document.addEventListener('MSFullscreenChange', exitHandler, true);
        document.addEventListener('webkitfullscreenchange', exitHandler, true);
    }

    function exitHandler() {
        if (document.webkitIsFullScreen || document.mozFullScreen || document.msFullscreenElement !== null) {
            //  $.alert("You Can't g.4?o out of full screen")
            if (window.innerHeight != screen.height) {
                $.confirm({
                    title: 'Alert!',
                    type: 'red',
                    typeAnimated: true,
                    content: 'You Can\'t Go out of full screen between test period',
                    buttons: {
                        okay: function() {
                            openFullscreen()
                        }
                    }
                });
            }
        }
    }
</script>

<style>
    input[type="radio"]:checked {
        box-shadow: 0 0 0 2px black;
    }
</style>