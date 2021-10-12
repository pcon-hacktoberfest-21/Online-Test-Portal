<?php

namespace App\Controllers;

use App\Models\EnrolledModal;
use App\Models\QuestionModal;
use App\Models\ResponseModal;
use CodeIgniter\HTTP\Response;

class Test extends BaseController
{
    public function __construct()
    {
        $session = session();
        if ($session->get('username') == "") {
            $auth_url = getenv('app.baseURL') . '/Auth';
            header("Location: $auth_url");
            exit;
        }
    }

    // public function index()
    // {
    //     return redirect()->to(getenv('app.baseURL') . '/Dashboard');
    // }

    public function solve($id)
    {
        $session = session();
        helper('master_helper');
        $user_id = $session->get('username');
        if (isUserEligibleForTest($user_id, $id) && hasUserTime($user_id, $id)) {
            $db = db_connect();
            $db->query("UPDATE `enrolled_test` SET login_attempt=login_attempt+1,attendance='1' ,device='Laptop' WHERE `user_id`='$user_id' AND `test_id`='$id'");
            $test_data = loadTestDetail($id);
            $data['page_title'] = $test_data->test_name . " || Test Page";
            $data['login_user_id'] = $user_id;
            $data['enrolled_test_data'] = loadUserEnrollment($user_id, $id);
            $data['test_data'] = $test_data;
            if (!$sections = cache("sections_in_$id")) {
                $sections = $db->query("SELECT DISTINCT section FROM questions WHERE test_id='$id'")->getResult();      //Sections in question paper such as physics/chemistry & Bio
                cache()->save("sections_in_$id", $sections, 300);
            }
            $data['section_data'] = $sections;
            foreach ($sections as $section) {
                $section_name = $section->section;
                if (!$questions = cache("question_in_$id._$section_name")) {
                    $question = new QuestionModal();
                    $questions = $question->where('test_id', $id)->where('section', $section_name)->find();
                    cache()->save("question_in_$id._$section_name", $questions, 300);
                }
                $data[$section_name . '_questions'] = $questions;
            }
            $db->close();
            return view('Users/solve_test', $data);
        } else {
            $data['error_message'] = "You are not allowed to appear in this test. Following may be reason <ol><li>you already submitted</li><li>you haven't Enrolled</li><li>Your Time Ends</li></ol>";
            $data['page_title'] = "Error";
            return view('Users/manual_error', $data);
        }
    }

    public function MSolve($id)             //mobile view of test page
    {
        $session = session();
        helper('master_helper');
        $user_id = $session->get('username');
        if (isUserEligibleForTest($user_id, $id) && hasUserTime($user_id, $id)) {
            $db = db_connect();
            $db->query("UPDATE `enrolled_test` SET login_attempt=login_attempt+1,attendance='1',device='Mobile' WHERE `user_id`='$user_id' AND `test_id`='$id'");
            $test_data = loadTestDetail($id);
            $data['page_title'] = $test_data->test_name . " || Test Page";
            $data['login_user_id'] = $user_id;
            $data['enrolled_test_data'] = loadUserEnrollment($user_id, $id);
            $data['test_data'] = $test_data;
            if (!$sections = cache("sections_in_$id")) {
                $sections = $db->query("SELECT DISTINCT section FROM questions WHERE test_id='$id'")->getResult();      //Sections in question paper such as physics/chemistry & Bio
                cache()->save("sections_in_$id", $sections, 86400);
            }
            $data['section_data'] = $sections;
            foreach ($sections as $section) {
                $section_name = $section->section;
                if (!$questions = cache("question_in_$id._$section_name")) {
                    $question = new QuestionModal();
                    $questions = $question->where('test_id', $id)->where('section', $section_name)->find();
                    cache()->save("question_in_$id._$section_name", $questions, 86400);
                }
                $data[$section_name . '_questions'] = $questions;
            }
            $db->close();
            return view('Users/mobile_test_page', $data);
        } else {
            $data['error_message'] = "You are not allowed to appear in this test or you already submitted";
            $data['page_title'] = "Error";
            return view('Users/manual_error', $data);
            // echo "You are not allowed to appear in this test or you already submitted";
        }
    }

    public function Analysis($id)
    {
        $session = session();
        $db = db_connect();
        helper('master_helper');

        if (isResultAllowed($id)) {
            $user_id = $session->get('username');
            if (isTestWindowOpen($id)) {
                $data['error_message'] = "Please Wait for test window to close";
                $data['page_title'] = "Error";
                return view('Users/manual_error', $data);
            }
            $data['page_title'] = "Test Analysis";
            $data['login_user_id'] = $user_id;
            $test_data = loadTestDetail($id);
            $data['test_data'] = $test_data;

            $enrolled_modal = new EnrolledModal();
            $enrolled_data = $enrolled_modal->where(['test_id' => $id, 'user_id' => $user_id])->first();
            $data['enrolled_data'] = $enrolled_data;

            if (!$sections = cache("sections_in_$id")) {
                $sections = $db->query("SELECT DISTINCT section FROM questions WHERE test_id='$id'")->getResult();      //Sections in question paper such as physics/chemistry & Bio
                cache()->save("sections_in_$id", $sections, 86400);
            }
            $data['section_data'] = $sections;
            foreach ($sections as $section) {
                $section_name = $section->section;
                if (!$questions = cache("question_in_$id._$section_name")) {
                    $question = new QuestionModal();
                    $questions = $question->where('test_id', $id)->where('section', $section_name)->find();
                    cache()->save("question_in_$id._$section_name", $questions, 86400);
                }
                $data[$section_name . '_questions'] = $questions;
            }
            $db->close();
            return view('Users/analysis', $data);
        } else {
            $data['error_message'] = "Result is blocked by admin ";
            $data['page_title'] = "Error";
            return view('Users/manual_error', $data);
        }
    }

    public function Submit($id)
    {
        $session = session();
        helper('master_helper');
        $user_id = $session->get('username');
        if (!isSubmitted($user_id, $id)) {
            $data['login_user_id'] = $user_id;
            $test_data = loadTestDetail($id);
            $data['test_data'] = $test_data;
            if (isset($_POST['submit_form'])) {
                if (isset($_POST['total_answers'])) {
                    $all_response = json_decode(urldecode($_POST['total_answers']));
                    $email = $session->get('email');
                    if (!empty($all_response)) {
                        foreach ($all_response as $question => $response) {
                            $correct_answer = getAnswer($question);
                            if ($correct_answer == strtolower($response)) {
                                $status = 1;
                                $marks = getCorrectMarksOfQuestion($question);
                            } else {
                                $status = 0;
                                $marks = getIncorrectMarksOfQuestion($question);
                            }
                            $new_question['answer'] = $correct_answer;
                            $new_question['question_id'] = $question;
                            $new_question['my_response'] = $response;
                            $new_question['status'] = $status;
                            $new_question['marks'] = $marks;
                            $complete_response["$question"] = $new_question;
                        }
                        cache()->save("answers_$id.$email.response", $complete_response, 432000);             //for 5 days
                    }
                }
                $enroll = new EnrolledModal();
                $data['page_title'] = "Test Submitted";
                $submit_test_data['submitted'] = 1;
                $enroll->where(["user_id" => $user_id, "test_id" => $id])->set($submit_test_data)->update();
                cache()->delete("$user_id._enrollment_$id");
                $data['message'] = "Test Submitted Successfully";
                $data['success'] = "Test Submitted Successfully";
            } else {
				return redirect()->to(getenv('app.baseURL') . '/Dashboard');
            }
        } else {
            $data['message'] = "You Already Submitted the test";
            $data['page_title'] = "Test Already Submitted";
        }
        return view('Users/submit_test', $data);
    }

    public function SaveResponse($id)
    {
        $session = session();
        helper('master_helper');
        $user_id = $session->get('username');
        $email = $session->get('email');
        if (isset($_POST['response'])) {
            if (isUserAlreadyEnrolled($user_id, $id)) {
                if (isTestWindowOpen($id)) {
                    if (!isSubmitted($user_id, $id)) {
                        if (hasUserTime($user_id, $id)) {
                            $response = $_POST['response'];
                            $response_object = json_decode($response);
                            $query = "";
                            foreach ($response_object as $question => $answer) {
                                $correct_answer = getAnswer($question);
                                $time = time();
                                if ($correct_answer == strtolower($answer)) {
                                    $status = 1;
                                    $marks = getCorrectMarksOfQuestion($question);
                                } else {
                                    $status = 0;
                                    $marks = getIncorrectMarksOfQuestion($question);
                                }
                                $query = $query . ",('$id','$user_id','$question','$answer','$status','$time','$marks')";
                            }
                            $query = ltrim($query, ',');
                            $db_query2 = "INSERT INTO `test_response`(`test_id`, `user_id`, `question_id`, `response`, `status`, `timestamp`, `marks`) VALUES $query";
                            $db = db_connect();
                            if ($db->query($db_query2)) {
                                echo 1;
                            } else {
                                echo 0;
                            }
                            $db->close();
                        }
                    } else {
                        // echo "You Already Submitted this test";
                        echo 2;
                    }
                } else {
                    echo 3;
                    // echo "Test Window Closed";
                }
            } else {
                echo 4;
                // echo "You hadn't enrolled this test";
            }
        }
    }
    public function ClearResponse($id)
    {
        $session = session();
        helper('master_helper');
        $user_id = $session->get('username');
        if (isset($_POST['question'])) {
            $question_id = $_POST['question'];
            // $previous_response = cache("answers_$id.$email.response");
            // unset($previous_response["$question_id"]);
            // cache()->save("answers_$id.$email.response", $previous_response, 432000);             //for 5 days
            $response_modal = new ResponseModal();
            if ($response_modal->where(['question_id' => $question_id, 'user_id' => $user_id])->delete()) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }

    public function Update($id)
    {
        $session = session();
        helper('master_helper');
        $db = db_connect();
        $user_id = $session->get('username');
        if (hasUserTime($user_id, $id)) {
            if (isset($_POST['tabChange'])) {
                if ($db->query("UPDATE `enrolled_test` SET tabchange=tabchange+1  WHERE `user_id`='$user_id' AND `test_id`='$id'")) {
                    echo  "Tab Change Reported";
                }
            }
            if (isset($_POST['update_time'])) {
                if ($db->query("UPDATE `enrolled_test` SET time_left=time_left-10  WHERE `user_id`='$user_id' AND `test_id`='$id'")) {
                    echo  "Time Updated";
                }
            }
        } else {
            echo 101;
        }
        $db->close();
        return false;
    }

    public function ViewResult($id)
    {
        $session = session();
        $user_id = $session->get('username');
        helper('master_helper');
        helper('master_helper');
        if (isTestWindowOpen($id)) {
            return "Please Wait for test window to close";
        }
        if (!isEnrolled($user_id, $id)) {
            return "You haven't appeared in this test";
        }
        $data['page_title'] = "Complete Rank List";
        if (!$enrolled = cache("final_result_$id")) {
            $enroll_modal = new EnrolledModal();
            $enrolled = $enroll_modal->where('test_id', $id)->orderBy('total_marks DESC')->orderBy('time_left DESC')->find();
            cache()->save("final_result_$id", $enrolled, 172800);
        }
        $data['enroll_test_data'] = $enrolled;
        $data['login_user_id'] = $user_id;
        $data['test_id'] = $id;
        return view('Users/rank_list', $data);
    }


}
