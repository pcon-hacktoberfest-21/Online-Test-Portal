<?php

namespace App\Controllers;

use App\Models\EnrolledModal;
use App\Models\QuestionModal;
use App\Models\ResponseModal;
use CodeIgniter\HTTP\Response;

class Exam extends BaseController
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
    function _remap($test_id)
    {
        helper('master_helper');
        $session = session();
        $test_detail = loadTestDetail($test_id);
        $data['test_detail'] = $test_detail;
        $user_id = $session->get('username');
        $user_email = $session->get('email');
        if (!empty($test_detail))
            $data['page_title'] = $test_detail->test_name;
        else
            $data['page_title'] = "Test Not Found";
            
        $data['login_user_id'] = $user_id;
        $data['login_user_email'] = $user_email;

        return view("Users/ExamInstruction", $data);
    }
}
