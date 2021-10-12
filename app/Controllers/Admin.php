<?php

namespace App\Controllers;

use App\Models\AdminModal;
use App\Models\EnrolledModal;
use App\Models\ResponseModal;
use App\Models\TestModal;
use App\Models\UserAccountModal;

class Admin extends BaseController
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
		$data['page_title'] = "Admin Dashboard";
		$session = session();
		$admin_modal= new AdminModal();
		$admin_id=$session->get('admin_id');
		$data['admin_data']=$admin_modal->where('id',$admin_id)->first();
		return view('Admin/admin', $data);
	}
	public function addTest()
	{
		$session = session();
		helper('master_helper');
		if (isset($_POST['add_test'])) {
			if (isset($_POST['test_name']) && isset($_POST['test_duration']) && isset($_POST['start_date']) && isset($_POST['end_date'])) {
				$test_id = GenerateTestID();
				$test_data['test_name'] = $_POST['test_name'];
				$test_data['test_duration'] = $_POST['test_duration'];
				$test_data['sdatetime'] = strtotime($_POST['start_date']);
				$test_data['edatetime'] = strtotime($_POST['end_date']);
				$test_data['test_id'] = $test_id;
				$test_data['admin'] = $session->get('admin_id');
				$test_data['created'] = time();
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
				$test_data['isPublic'] = $public;
				$test_data['nitOnly'] = $nitOnly;
				$test_data['password'] = $_POST['password'];
				$test = new TestModal();
				$test->save($test_data);
				$session->setFlashdata('flash_response', 'Test Added Add Some Questions');
				return redirect()->to(getenv('app.baseURL') . '/EditTest/view/' . $test_id);
			} else {
				$session->setFlashdata('flash_response', 'Missing Required field(s)');
			}
		}
		$data['page_title'] = "Add Test";
		return view('Admin/addtest', $data);
	}
	public function test()
	{
		$data['page_title'] = "All Test";
		$test = new TestModal();
		$session = session();
		$user_id = $session->get('admin_id');
		$data['test_data'] = $test->where('admin', $user_id)->orderby('sl DESC')->find();
		return view('Admin/test', $data);
	}
	public function Users()
	{
		$data['page_title'] = "All Users";
		$user = new UserAccountModal();
		helper('master_helper');
		if (isset($_GET['search'])) {
			$search = $_GET['search'];
			if ($search == 'all') {
				$data['user_data'] = $user->find();
			} else {
				$data['user_data'] = $user->where('email', $search)->orWhere('phoneNo', $search)->orLike('name', $search)->orLike('city', $search)->find();
			}
		}
		return view('Admin/view_users', $data);
	}

	public function DeleteUser()
	{
		$id = $_POST['user_id'];
		$user_modal = new UserAccountModal();
		$response_modal = new ResponseModal();
		$enroll_modal = new EnrolledModal();
		if ($user_modal->where('id', $id)->delete()) {
			$enroll_modal->where('user_id', $id)->delete();
			$response_modal->where('user_id', $id)->delete();
			cache()->delete("user_detail_$id");
			echo 1;
		} else
			echo 0;
	}
	// public function ViewUser($id)
	// {
	// 	helper('master_helper');
	// 	$data['page_title'] = "Profile";
	// 	$data['login_user_id'] = $id;
	// 	$enrolled_modal = new EnrolledModal();
	// 	$all_test = $enrolled_modal->where('user_id', $id)->findAll();

	// 	$data['enrolled_tests'] = $all_test;
	// 	return view('Admin/userDetail', $data);
	// }

	// public function SuperLogin($id)
	// {
	// 	$session = session();
	// 	$user_modal = new UserAccountModal();
	// 	$user_detail = $user_modal->where('id', $id)->first();
	// 	if (!empty($user_detail)) {
	// 		$newdata = [
	// 			'username'  => $user_detail->id,
	// 			'email' => $user_detail->email,
	// 			'logged_in' => TRUE
	// 		];
	// 		$session->set($newdata);
	// 		$session->setFlashdata('flash_response', 'Super Login Successful');
	// 		return redirect()->to(getenv('app.baseURL') . '/Dashboard');
	// 	} else {
	// 		echo "No user found";
	// 	}
	// }

	// public function VerifyUser()
	// {
	// 	$id = $_POST['user_id'];
	// 	$verification = $_POST['verification'];
	// 	$user_modal = new UserAccountModal();
	// 	cache()->delete("user_detail_$id");
	// 	echo $user_modal->where('id', $id)->set(['verified' => $verification, 'modifiedOn' => date("Y-m-d H:i:s")])->update();
	// }

	public function Logout()
	{
		unset($_SESSION['admin_id'], $_SESSION['email']);
		return redirect()->to(getenv('app.baseURL') . '/Auth/Host');
	}
}
