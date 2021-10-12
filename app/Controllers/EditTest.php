<?php

namespace App\Controllers;

use App\Models\EnrolledModal;
use App\Models\QuestionModal;
use App\Models\ResponseModal;
use App\Models\TestModal;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;

class EditTest extends BaseController
{

    public function __construct()
    {
        $session = session();
        if ($session->get('admin_id') == "") {
            $auth_url = getenv('app.baseURL') . '/Auth/Host';
            header("Location: $auth_url");
            exit;
        }
    }
    public function index()
    {
        $data['page_title'] = "All Test";
        $test = new TestModal();
        $data['test_data'] = $test->orderby('sl DESC')->find();
        return view('Admin/test', $data);
    }
    function view($id)
    {
        $test = new TestModal();
        $data['page_title'] = "Edit Test";
        $db = db_connect();
        $question = new QuestionModal();
        helper('master_helper');

        if (!isTestExist($id)) {
            $data['error_message'] = "Test Doesn't Exist";
            $data['page_title'] = "Error";
            return view('Admin/manual_error', $data);
        }
        $session = session();
        if (getTestAdmin($id) ==  $session->get('admin_id')) {
            $test_data = $test->where(["test_id" => $id])->first();;
            $data['test_data'] = $test_data;
            $sections = $db->query("SELECT DISTINCT section FROM questions WHERE test_id='$id'")->getResult();      //Sections in question paper such as physics/chemistry & Bio
            $data['section_data'] = $sections;
            foreach ($sections as $section) {
                $section_name = $section->section;
                $data[$section_name . '_questions'] = $question->where('test_id', $id)->where('section', $section_name)->find();
            }
            $data['test_data'] = $test->where('test_id', $id)->first();
            $data['question_data'] = $question->where('test_id', $id)->find();
            $data['test_data'] = $test->where('test_id', $id)->orderby('sl DESC')->first();
            $db->close();
            return view('Admin/editTest', $data);
        } else {
            $data['error_message'] = "Seems You are not the admin of this test";
            $data['page_title'] = "Error";
            return view('Admin/manual_error', $data);
        }
    }
    function viewCount($id)
    {
        $this->response->setContentType('Content-Type: application/json');
        $db = db_connect();
        $query = "SELECT count(*) FROM `enrolled_test` WHERE `test_id`='$id'";
        $query2 = "SELECT count(*) FROM `enrolled_test` WHERE `test_id`='$id' AND `attendance`=1";
        $query3 = "SELECT count(*) FROM `enrolled_test` WHERE `test_id`='$id' AND submitted=1";
        $count = $db->query($query)->getResultArray();
        $count2 = $db->query($query2)->getResultArray();
        $count3 = $db->query($query3)->getResultArray();
        $data['enrolled'] = $count[0]['count(*)'];
        $data['started'] = $count2[0]['count(*)'];
        $data['submission'] = $count3[0]['count(*)'];
        echo json_encode($data);
        $db->close();
    }
    public function EditSetting($id)
    {
        helper('form');
        helper('master_helper');
        $session = session();
        $data['page_title'] = "Edit Test";
        $test = new TestModal();
        $enrolled = new EnrolledModal();
        if (getTestAdmin($id) ==  $session->get('admin_id')) {
            if (isset($_POST['update_test_setting'])) {
                $test_name = esc($_POST['test_name']);
                $test_from = strtotime($_POST['test_from']);
                $test_to = strtotime($_POST['test_to']);
                // $solution = esc($_POST['solution_link']);
                $duration = $_POST['duration'];
                $test_status = $_POST['test_status'];
                $show_result = $_POST['show_result'];
                if (isset($_POST['isPublic'])) {
                    $public = 1;
                } else {
                    $public = 0;
                }
                if (isset($_POST['nitOnly'])) {
                    $nitOnly = 1;
                } else {
                    $nitOnly = 0;
                }
                $new_test_data['isPublic'] = $public;
                $new_test_data['nitOnly'] = $nitOnly;
                $new_test_data['password'] = $_POST['password'];
                $new_test_data['test_name'] = $test_name;
                $new_test_data['test_duration'] = $duration;
                $new_test_data['sdatetime'] = $test_from;
                $new_test_data['edatetime'] = $test_to;
                // $new_test_data['solution'] = $solution;
                $new_test_data['show_result'] = $show_result;
                $new_test_data['isActive'] = $test_status;

                $enrolled_update['endtime'] = $test_to;
                $enrolled_update['start_time'] = $test_to;
                $enrolled->where(['test_id' => $id])->set($enrolled_update)->update();
                if ($test->where(['test_id' => $id])->set($new_test_data)->update()) {
                    $session->setFlashdata('flash_response', 'Test Setting Updated');
                }

                cache()->delete("test_detail_$id");
            }
        } else {
            $session->setFlashdata('flash_response', 'You Are Not the admin of this test');
        }
        $data['test_data'] = $test->where('test_id', $id)->orderby('sl DESC')->first();
        return redirect()->to(getenv('app.baseURL') . '/EditTest/view/' . $id);
    }
    public function AddQuestion($id)
    {
        helper('form');
        $session = session();
        $data['page_title'] = "Edit Test";
        helper('master_helper');
        $question_modal = new QuestionModal();
        if (getTestAdmin($id) ==  $session->get('admin_id')) {
            if (isset($_POST['add_question'])) {
                $file = $this->request->getFile('question_image');
                if ($file->isValid()) {
                    $new_file_name = uniqid("image") . '.' . $file->getClientExtension();
                    $image_link = getenv('app.baseURL') . "/images/$id/" . $new_file_name;
                    $file->move('./images/' . $id . '/', $new_file_name);
                } else {
                    $image_link = "";
                }
                $question_id = esc($_POST['question_id']);
                $question = esc($_POST['question']);
                $option_a = esc($_POST['option_a']);
                $option_b = esc($_POST['option_b']);
                $option_c = esc($_POST['option_c']);
                $option_d = esc($_POST['option_d']);
                $section = esc($_POST['section']);

                $negativeMarking = esc($_POST['negativeMarking']);
                $positiveMarking = esc($_POST['positiveMarking']);

                $correct_answer = esc($_POST['correct_answer']);
                $question_data['question_id'] = $question_id;
                $question_data['test_id'] = $id;
                $question_data['question'] = $question;
                $question_data['option_a'] = $option_a;
                $question_data['option_b'] = $option_b;
                $question_data['option_c'] = $option_c;
                $question_data['option_d'] = $option_d;
                $question_data['section'] = $section;
                $question_data['positiveMarking'] = $positiveMarking;
                $question_data['negativeMarking'] = $negativeMarking;
                $question_data['image'] = $image_link;
                $question_data['answer'] = $correct_answer;
                if ($question_modal->save($question_data)) {
                    $session->setFlashdata('flash_response', 'Question Added ');
                } else {
                    $session->setFlashdata('flash_response', 'Failed');
                }
            }
            $data['test_data'] = loadTestDetail($id);
            return redirect()->to(getenv('app.baseURL') . '/EditTest/view/' . $id);
        } else {
            $data['error_message'] = "Seems You are not the admin of this test";
            $data['page_title'] = "Error";
            return view('Admin/manual_error', $data);
        }
    }

    public function ViewAttendance($id)
    {
        $data['page_title'] = "Attendance";
        helper('master_helper');
        $data['test_data'] = loadTestDetail($id);
        $session = session();
        if (getTestAdmin($id) ==  $session->get('admin_id')) {
            $db = db_connect();
            if (isset($_GET['email'])) {
                $user_id = getUserID(trim($_GET['email']));
                $enrolled_data = $db->query("SELECT * from enrolled_test LEFT JOIN (SELECT user_id, SUM(marks) as total,SUM(CASE WHEN marks > 0 THEN marks ELSE 0 END) as positive_marks FROM test_response WHERE test_id ='$id' AND `user_id`='$user_id' GROUP BY user_id) as mark ON mark.user_id=enrolled_test.user_id WHERE  test_id ='$id' AND enrolled_test.user_id='$user_id'")->getResult();
            } else {
                $enrolled_data = $db->query("SELECT * from enrolled_test LEFT JOIN (SELECT user_id, SUM(marks) as total,SUM(CASE WHEN marks > 0 THEN marks ELSE 0 END) as positive_marks FROM test_response WHERE test_id ='$id' GROUP BY user_id) as mark ON mark.user_id=enrolled_test.user_id WHERE  test_id ='$id'")->getResult();
            }
            $data['enrolled_test_data'] = $enrolled_data;
            $db->close();
            return view('Admin/view_attendance', $data);
        } else {
            $data['error_message'] = "Seems You are not the admin of this test";
            $data['page_title'] = "Error";
            return view('Admin/manual_error', $data);
        }
    }

    public function DeleteUser($id)
    {
        $enroll = new EnrolledModal();
        $response = new ResponseModal();
        $session = session();
        helper('master_helper');
        if (getTestAdmin($id) ==  $session->get('admin_id')) {
            $user_id = $_GET['user'];
            if ($enroll->where('test_id', $id)->where('user_id', $user_id)->delete()) {
                $response->where('test_id', $id)->where('user_id', $user_id)->delete();
                $session->setFlashdata('flash_response', 'User Deleted');
            } else {
                $session->setFlashdata('flash_response', 'Failed');
            }
            cache()->delete("$user_id._enrollment_$id");
            return redirect()->to(getenv('app.baseURL') . '/EditTest/ViewAttendance/' . $id);
        } else {
            $data['error_message'] = "Seems You are not the admin of this test";
            $data['page_title'] = "Error";
            return view('Admin/manual_error', $data);
        }
    }

    public function EditQuestion($id)
    {
        helper('form');
        $session = session();
        helper('master_helper');
        if (getTestAdmin($id) ==  $session->get('admin_id')) {
            $question_modal = new QuestionModal();
            if (isset($_POST['update_question'])) {
                $file = $this->request->getFile('question_image');
                if ($file->isValid()) {
                    $new_file_name = uniqid("image") . '.' . $file->getClientExtension();
                    $image_link = getenv('app.baseURL') . "/images/$id/" . $new_file_name;
                    $file->move('./images/' . $id . '/', $new_file_name);
                } else {
                    $image_link = "";
                }
                $question_id = esc($_POST['question_id']);
                $question = esc($_POST['question']);
                $option_a = esc($_POST['option_a']);
                $option_b = esc($_POST['option_b']);
                $option_c = esc($_POST['option_c']);
                $negativeMarking = esc($_POST['negativeMarking']);
                $positiveMarking = esc($_POST['positiveMarking']);
                $option_d = esc($_POST['option_d']);
                $section = esc($_POST['section']);
                $correct_answer = esc($_POST['correct_answer']);
                $question_data['question'] = $question;
                $question_data['option_a'] = $option_a;
                $question_data['option_b'] = $option_b;
                $question_data['option_c'] = $option_c;
                $question_data['option_d'] = $option_d;
                $question_data['image'] = $image_link;
                $question_data['answer'] = $correct_answer;
                $question_data['section'] = $section;
                $question_data['positiveMarking'] = $positiveMarking;
                $question_data['negativeMarking'] = $negativeMarking;
                cache()->delete("question_data_$question_id");
                $response_modal = new ResponseModal();
                // marks
                $response_modal->where('question_id', $question_id)->set(['status' => '0', 'marks' => $negativeMarking])->update();
                $response_modal->where(['question_id' => $question_id, 'response' => $correct_answer])->set(['status' => '1', 'marks' => $positiveMarking])->update();

                if ($question_modal->where("question_id", $question_id)->set($question_data)->update()) {
                    $session->setFlashdata('flash_response', 'Question Edited ');
                } else {
                    $session->setFlashdata('flash_response', 'Failed');
                }
                return redirect()->to(getenv('app.baseURL') . '/EditTest/view/' . $id);
            } {
                $session->setFlashdata('flash_response', 'Missing Required Fields ');
                return redirect()->to(getenv('app.baseURL') . '/EditTest/view/' . $id);
            }
        } else {
            $data['error_message'] = "Seems You are not the admin of this test";
            $data['page_title'] = "Error";
            return view('Admin/manual_error', $data);
        }
    }

    public function DeleteQuestion($id)
    {
        helper('form');
        $session = session();
        helper('master_helper');
        if (getTestAdmin($id) ==  $session->get('admin_id')) {
            $question_modal = new QuestionModal();
            $response_modal = new ResponseModal();
            if (isset($_POST['delete_question'])) {
                $question_id = $_POST['question_id'];
                if ($question_modal->where('question_id', $question_id)->delete()) {
                    $response_modal->where('question_id', $question_id)->delete();
                    $session->setFlashdata('flash_response', 'Question Deleted ');
                } else {
                    $session->setFlashdata('flash_response', 'Failed');
                }
                return redirect()->to(getenv('app.baseURL') . '/EditTest/view/' . $id);
            } {
                $session->setFlashdata('flash_response', 'Missing Requires Field');
                return redirect()->to(getenv('app.baseURL') . '/EditTest/view/' . $id);
            }
        } else {
            $data['error_message'] = "Seems You are not the admin of this test";
            $data['page_title'] = "Error";
            return view('Admin/manual_error', $data);
        }
    }
    public function DeleteAllQuestion($id)
    {
        $session = session();
        helper('master_helper');
        if (getTestAdmin($id) ==  $session->get('admin_id')) {
            $question_modal = new QuestionModal();
            $response_modal = new ResponseModal();
            if ($question_modal->where('test_id', $id)->delete()) {
                $response_modal->where('test_id', $id)->delete();
                $session->setFlashdata('flash_response', 'Deleted All Questions ');
            } else {
                $session->setFlashdata('flash_response', 'Failed');
            }
            return redirect()->to(getenv('app.baseURL') . '/EditTest/view/' . $id);
        } else {
            $data['error_message'] = "Seems You are not the admin of this test";
            $data['page_title'] = "Error";
            return view('Admin/manual_error', $data);
        }
    }
    public function DeleteTest($id)
    {
        $session = session();
        helper('master_helper');
        if (getTestAdmin($id) ==  $session->get('admin_id')) {
            $session = session();
            $test_modal = new TestModal();
            $question_modal = new QuestionModal();
            $response_modal = new ResponseModal();
            $enrollment_modal = new EnrolledModal();
            if ($question_modal->where('test_id', $id)->delete()) {
                $enrollment_modal->where('test_id', $id)->delete();
                $response_modal->where('test_id', $id)->delete();
                $test_modal->where('test_id', $id)->delete();
                $session->setFlashdata('flash_response', 'Deleted All Questions ');
            } else {
                $session->setFlashdata('flash_response', 'Failed');
            }
            cache()->delete("test_detail_$id");
            return redirect()->to(getenv('app.baseURL') . '/Admin/Test');
        } else {
            $data['error_message'] = "Seems You are not the admin of this test";
            $data['page_title'] = "Error";
            return view('Admin/manual_error', $data);
        }
    }

    public function DeleteAllEnrollment($id)
    {
        $session = session();
        helper('master_helper');
        if (getTestAdmin($id) ==  $session->get('admin_id')) {
            $response_modal = new ResponseModal();
            $enrollment_modal = new EnrolledModal();
            if ($enrollment_modal->where('test_id', $id)->delete()) {
                $response_modal->where('test_id', $id)->delete();
                $session->setFlashdata('flash_response', 'Deleted All Enrollment ');
            } else {
                $session->setFlashdata('flash_response', 'Failed');
            }
            return redirect()->to(getenv('app.baseURL') . '/EditTest/view/' . $id);
        } else {
            $data['error_message'] = "Seems You are not the admin of this test";
            $data['page_title'] = "Error";
            return view('Admin/manual_error', $data);
        }
    }


    public function BulkUpload($id)
    {
        $file = $this->request->getFile('excel_file');
        helper('array');
        helper('form');
        helper('master_helper');
        $session = session();
        if (getTestAdmin($id) ==  $session->get('admin_id')) {
            $reader = new ReaderXlsx();
            $spreadsheet = $reader->load($file);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            $sheet_structure = $sheetData[1];
            $all_questions = json_decode(json_encode($sheetData));
            $section_key = array_search('section', $sheet_structure);
            $option_a_key = array_search('option_a', $sheet_structure);
            $option_b_key = array_search('option_b', $sheet_structure);
            $option_c_key = array_search('option_c', $sheet_structure);
            $option_d_key = array_search('option_d', $sheet_structure);
            $answer_key = array_search('answer', $sheet_structure);
            $question_key = array_search('question', $sheet_structure);
            $incorrect_marks_key = array_search('incorrect_marks', $sheet_structure);
            $correct_marks_key = array_search('correct_marks', $sheet_structure);
            if (!empty($section_key) && !empty($option_a_key) && !empty($option_b_key) && !empty($option_c_key) && !empty($option_d_key) && !empty($answer_key) && !empty($question_key) && !empty($incorrect_marks_key) && !empty($correct_marks_key)) {
                $sl = 0;
                $uploaded = 0;
                $error_message = "";
                foreach ($all_questions as $question_data) {
                    if ($sl > 0 && $sl < 300) {
                        $section = esc(trim($question_data->$section_key));
                        $question = esc($question_data->$question_key);
                        $option_a = esc($question_data->$option_a_key);
                        $option_b = esc($question_data->$option_b_key);
                        $option_c = esc($question_data->$option_c_key);
                        $option_d = esc($question_data->$option_d_key);
                        $answer = trim($question_data->$answer_key);
                        $correct_mark = trim($question_data->$correct_marks_key);
                        $incorrect_mark = trim($question_data->$incorrect_marks_key);
                        if ($incorrect_mark > 0) {
                            $incorrect_mark = 0;
                        }
                        if ($correct_mark < 0) {
                            $correct_mark = 0;
                        }
                        $answer = trim($question_data->$answer_key);
                        $new_question['question_id'] = uniqid("Ques");
                        $new_question['test_id'] = $id;
                        $new_question['question'] = ($question);
                        $new_question['option_a'] = ($option_a);
                        $new_question['option_b'] = ($option_b);
                        $new_question['option_c'] = ($option_c);
                        $new_question['option_d'] = ($option_d);
                        $new_question['answer'] = $answer;
                        $new_question['section'] = ($section);
                        $new_question['positiveMarking'] = ($correct_mark);
                        $new_question['negativeMarking'] = ($incorrect_mark);
                        $question_modal = new QuestionModal();
                        if (strpos($section, ' ') == false) {
                            if (!empty($question) && ($option_a) != "" && ($option_b) != "" && ($option_c) != "" && ($option_d) != "" && !empty($answer) && !empty($section)) {
                                if ($question_modal->save($new_question)) {
                                    $uploaded = 1;
                                };
                            } else {
                                // $error_message = $error_message . ", " . ($sl + 1);
                            }
                        } else {
                            //if section contains space delete all questions and return error
                            $session->setFlashdata('flash_response', "Some Question's section contains space! Please remove space from sections");
                            $question_modal->where('test_id', $id)->delete();
                            return redirect()->to(getenv('app.baseURL') . '/EditTest/view/' . $id);
                        }
                    }
                    $sl++;
                }
                if ($uploaded == 1) {
                    if (!empty($error_message)) {
                        // $error_message = "<br>(With Error in row " . $error_message . " Please Manually Upload these questions)";
                    }
                    $session->setFlashdata('flash_response', "Question Uploaded Successfully");
                    // $session->setFlashdata('flash_response', "Question Uploaded Successfully $error_message");
                } else {
                    $session->setFlashdata('flash_response', 'Failed To Upload ');
                }
            } else {
                $problem = "";
                if (empty($section_key)) {
                    $problem = $problem . " Section,";
                }
                if (empty($question_key)) {
                    $problem = $problem . " Question,";
                }
                if (empty($option_a_key)) {
                    $problem = $problem . " Option A,";
                }
                if (empty($option_b_key)) {
                    $problem = $problem . " Option B,";
                }
                if (empty($option_c_key)) {
                    $problem = $problem . " Option C,";
                }
                if (empty($option_d_key)) {
                    $problem = $problem . " Option D,";
                }
                if (empty($answer_key)) {
                    $problem = $problem . " Answer";
                }
                $session->setFlashdata('flash_response', "Seems file has Wrong Formate <br> Problem with:($problem)<br>Please Rewrite this field");
            }
            return redirect()->to(getenv('app.baseURL') . '/EditTest/view/' . $id);
        } else {
            $data['error_message'] = "Seems You are not the admin of this test";
            $data['page_title'] = "Error";
            return view('Admin/manual_error', $data);
        }
    }
}
