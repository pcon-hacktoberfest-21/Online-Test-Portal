<?php

namespace App\Controllers;

use App\Models\EnrolledModal;
use App\Models\TestModal;
use App\Models\UserAccountModal;

class Dashboard extends BaseController
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
    public function index()
    {
        helper('master_helper');
        $session = session();
        $user_id = $session->get('username');
        $data['page_title'] = "Home Page";
        $data['login_user_id'] = $user_id;
        $test = new TestModal();
        $enroll = new EnrolledModal();
        $data['upcoming_test_data'] = $test->where('edatetime > ', time())->where(['isActive' => 1, "isPublic" => 1])->find();
        $data['enrolled_test_data'] = $enroll->where('user_id', $user_id)->find();
        return view('Users/dashboard', $data);
    }

    public function Enroll()
    {
        if (isset($_POST['test_id'])) {
            helper('master_helper');
            $test_id = $_POST['test_id'];
            if (isset($_POST['password'])) {
                $password = $_POST['password'];
            } else {
                $password = "";
            }
            $enroll = new EnrolledModal();
            $session = session();
            $user_id = $session->get('username');
            $email = $session->get('email');
            $test_data = loadTestDetail($test_id);
            $enrolled_data['test_id'] = $test_data->test_id;
            $enrolled_data['endtime'] = $test_data->edatetime;
            $enrolled_data['starttime'] = $test_data->sdatetime;
            $enrolled_data['time_left'] = getTestDuration($test_data->test_id) * 60;
            $enrolled_data['user_id'] = $user_id;
            $enrolled_data['sharingID'] = uniqid("S");
            $enrolled_data['ip'] = $_SERVER['REMOTE_ADDR'];
            $enrolled_data['enrolled_on'] = time();
            if ($test_data->password == $password) {
                if (isUserVerified($user_id)) {
                    if (!isTestExpire($test_data->test_id)) {
                        if ($test_data->nitOnly) {
                            if (strpos($email, '@nitjsr.ac.in')) {
                                if ($enroll->save($enrolled_data)) {
                                    echo "1";
                                } else {
                                    echo "Something Went Wrong";
                                }
                            } else {
                                echo "This test is only for NIT Students Login with your NIT mail";
                            }
                        } else {
                            if ($enroll->save($enrolled_data)) {
                                echo "1";
                            } else {
                                echo "Something Went Wrong";
                            }
                        }
                    } else {
                        echo "Test Window Already Closed";
                    }
                } else {
                    echo "Please Update Your Profile";
                }
            } else {
                echo "Invalid Password";
            }
        } else {
            echo "Missing Required Field";
        }
    }

    public function Profile()
    {
        helper('master_helper');
        $session = session();
        $user_id = $session->get('username');
        $data['page_title'] = "Profile";
        $data['login_user_id'] = $user_id;

        if (isset($_POST['update_user'])) {
            $name = $_POST['name'];
            $branch = $_POST['branch'];
            $roll = $_POST['roll'];
            $db_data['name'] = $name;
            $db_data['branch'] = $branch;
            $db_data['roll'] = $roll;
            $db_data['verified'] = 1;
            $profile_modal = new UserAccountModal();
            $profile_modal->update($user_id, $db_data);
            cache()->delete("user_detail_$user_id");
            // $session->setFlashdata('flash_response', 'Profile Updated');
        }

        return view('Users/profile', $data);
    }
}
