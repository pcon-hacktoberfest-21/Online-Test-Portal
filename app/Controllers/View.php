<?php

namespace App\Controllers;

use App\Models\EnrolledModal;
use App\Models\QuestionModal;
use App\Models\ResponseModal;
use App\Models\UserAccountModal;
use CodeIgniter\HTTP\Response;

class View extends BaseController
{
    function _remap($sharingID = '')
    {
        $firstCharacter = $sharingID[0];
        if ($firstCharacter == "s" || $firstCharacter == "S") {

            $db = db_connect();
            helper('master_helper');
            $enrolled_modal = new EnrolledModal();
            $enrolled_data = $enrolled_modal->where('SharingID', $sharingID)->first();
            if (!empty($enrolled_data)) {
                $id = $enrolled_data->test_id;
                $data['page_title'] = "Test Analysis";
                $data['enrolled_data'] = $enrolled_data;
                $test_data = loadTestDetail($id);
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
                return view('Admin/SharedResult', $data);
            } else {
                $data['error_message'] = "Invalid Or Expired Link";
                $data['page_title'] = "Error";
                return view('Admin/manual_error', $data);
            }
        } elseif ($firstCharacter == "p" || $firstCharacter == "P") {
            helper('master_helper');
            $modal = new UserAccountModal();
            $user_data = $modal->where('SharingID', $sharingID)->first();
            if (!empty($user_data)) {
                $data['user_data'] = $user_data;
                $data['page_title'] = "User's Profile";
                return view('Admin/SharedProfile', $data);
            } else {
                $data['error_message'] = "Invalid Or Expired Link";
                $data['page_title'] = "Error";
                return view('Admin/manual_error', $data);
            }
        } else {
            $data['error_message'] = "Invalid Or Expired Link";
            $data['page_title'] = "Error";
            return view('Admin/manual_error', $data);
        }
    }
}
