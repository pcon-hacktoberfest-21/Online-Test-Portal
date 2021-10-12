<?php

use App\Controllers\Test;
use App\Models\AdminModal;
use App\Models\EnrolledModal;
use App\Models\QuestionModal;
use App\Models\ResponseModal;
use App\Models\TestModal;
use App\Models\UserAccountModal;

if (!function_exists('isUserAlreadyEnrolled')) {
	function isUserAlreadyEnrolled($user_id, $test_id)
	{
		$response = loadUserEnrollment($user_id, $test_id);
		if (empty($response)) {
			return false;
		}
		return true;;
	}
}

if (!function_exists('isUserVerified')) {
	function isUserVerified($user_id)
	{
		$response = getUserData($user_id);
		if (empty($response)) {
			return false;
		}
		$vellication = $response->verified;
		if ($vellication) {
			return true;
		} else
			return false;
	}
}


if (!function_exists('loadUserEnrollment')) {
	function loadUserEnrollment($user_id, $test_id)
	{
		if (!$enrollment = cache("$user_id._enrollment_$test_id")) {
			$enroll = new EnrolledModal();
			$response = $enroll->where('user_id', $user_id)->where('test_id', $test_id)->first();
			cache()->save("$user_id._enrollment_$test_id", $response, 20);
			return $response;
		}
		return $enrollment;
	}
}


if (!function_exists('isSubmitted')) {
	function isSubmitted($user_id, $test_id)
	{
		$enrolled_data = loadUserEnrollment($user_id, $test_id);
		if (!empty($enrolled_data))
			return $enrolled_data->submitted;
	}
}
if (!function_exists('hasUserTime')) {
	function hasUserTime($user_id, $test_id)
	{
		$enrolled_data = loadUserEnrollment($user_id, $test_id);
		if (!empty($enrolled_data)) {
			if (($enrolled_data->time_left) > (-30)) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}
if (!function_exists('getUsersLastSeen')) {
	function getUsersLastSeen($user_id, $test_id)
	{
		$response = new ResponseModal();
		$response_data = $response->where('user_id', $user_id)->where('test_id', $test_id)->orderby('timestamp DESC')->first();
		if (!empty($response_data))
			return $response_data->timestamp;
	}
}

if (!function_exists('fetchTabChange')) {
	function fetchTabChange($user_id, $test_id)
	{
		$enrolled_data = loadUserEnrollment($user_id, $test_id);
		if (!empty($enrolled_data))
			return $enrolled_data->tabchange;
	}
}

// check for user enrolled And test is live and test window is open and user have not submitted the test
if (!function_exists('isUserEligibleForSolving')) {
	function isUserEligibleForTest($user_id, $test_id)
	{
		if (isUserAlreadyEnrolled($user_id, $test_id)) {
			if (isTestWindowOpen($test_id)) {
				if (!isSubmitted($user_id, $test_id)) {
					return true;
				} else {
					false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}


if (!function_exists('loadTestDetail')) {
	function loadTestDetail($test_id)
	{
		if (!$test_detail = cache("test_detail_$test_id")) {
			$test = new TestModal();
			$response = $test->where('test_id', $test_id)->first();
			cache()->save("test_detail_$test_id", $response, 60);
			return $response;
		}
		return $test_detail;
	}
}
if (!function_exists('isTestExist')) {
	function isTestExist($test_id)
	{
		if (!empty(loadTestDetail($test_id))) {
			return true;
		} else {
			return false;
		}
	}
}
if (!function_exists('getTestName')) {
	function getTestName($test_id)
	{
		return loadTestDetail($test_id)->test_name;
	}
}
if (!function_exists('isResultAllowed')) {
	function isResultAllowed($test_id)
	{
		return loadTestDetail($test_id)->show_result;
	}
}
if (!function_exists('getTestDuration')) {
	function getTestDuration($test_id)
	{
		return loadTestDetail($test_id)->test_duration;
	}
}

if (!function_exists('getTestSubject')) {
	function getTestSubject($test_id)
	{
		return loadTestDetail($test_id)->subject;
	}
}

if (!function_exists('getTestAdmin')) {
	function getTestAdmin($test_id)
	{
		return loadTestDetail($test_id)->admin;
	}
}

if (!function_exists('getTestAdminDetail')) {
	function getTestAdminDetail($test_id)
	{
		$admin_id = getTestAdmin($test_id);
		$admin_modal = new AdminModal();
		return $admin_modal->where('id', $admin_id)->first();
	}
}



if (!function_exists('isTestWindowOpen')) {
	function isTestWindowOpen($test_id)
	{
		$starttime = loadTestDetail($test_id)->sdatetime;
		$endtime = loadTestDetail($test_id)->edatetime;
		if ($endtime > time() && $starttime < time()) {
			return true;
		} else {
			return false;
		}
	}
}

if (!function_exists('isTestExpire')) {
	function isTestExpire($test_id)
	{
		$endtime = loadTestDetail($test_id)->edatetime;
		if ($endtime < time()) {
			return true;
		} else {
			return false;
		}
	}
}

if (!function_exists('isEnrolled')) {

	function isEnrolled($user_id, $test_id)
	{
		$enrolled_data = loadUserEnrollment($user_id, $test_id);
		if (empty($enrolled_data))
			return false;
		else
			return true;
	}
}

if (!function_exists('getQuestionDetail')) {

	function getQuestionDetail($question_id)
	{
		if (!$question_data = cache("question_data_$question_id")) {
			$question = new QuestionModal();
			$question_data = $question->where('question_id', $question_id)->first();
			if (!empty($question_data)) {
				cache()->save("question_data_$question_id", $question_data, 86400);
			} else {
				return '';
			}
		}
		return $question_data;
	}
}

if (!function_exists('getAnswer')) {

	function getAnswer($question_id)
	{
		$question_data = getQuestionDetail($question_id);
		if (!empty($question_data))
			return  strtolower($question_data->answer);
		else
			return "";
	}
}

if (!function_exists('getCorrectMarksOfQuestion')) {

	function getCorrectMarksOfQuestion($question_id)
	{
		$question_data = getQuestionDetail($question_id);
		if (!empty($question_data))
			return ($question_data->positiveMarking);
		else
			return 0;
	}
}
if (!function_exists('getIncorrectMarksOfQuestion')) {

	function getIncorrectMarksOfQuestion($question_id)
	{
		$question_data = getQuestionDetail($question_id);
		if (!empty($question_data))
			return  strtolower($question_data->negativeMarking);
		else
			return 0;
	}
}

if (!function_exists('count_all_questions')) {
	function count_all_questions($test_id)
	{
		if (!$count = cache("question_in_$test_id")) {
			$question_modal = new QuestionModal();
			$data = $question_modal->where('test_id', $test_id)->find();
			$count = count($data);
			cache()->save("question_in_$test_id", count($data), 86400);
		}
		return $count;
	}
}








if (!function_exists('isQuestionSolved')) {

	function isQuestionSolved($user_id, $question_id)
	{
		$response_modal = new ResponseModal();
		$data = $response_modal->where(["question_id" => $question_id, "user_id" => $user_id])->find();
		if (empty($data)) {
			return false;
		} else {
			return true;
		}
	}
}

if (!function_exists('isQuestionCorrect')) {

	function isQuestionCorrect($user_id, $question_id)
	{
		$response_modal = new ResponseModal();
		$data = $response_modal->where(["question_id" => $question_id, "user_id" => $user_id])->first();
		if (empty($data)) {
			return false;
		} else {
			return $data->status;
		}
	}
}
if (!function_exists('questionSolvingStatus')) {

	function questionSolvingStatus($user_id, $question_id)
	{
		if (isQuestionSolved($user_id, $question_id)) {
			if (isQuestionCorrect($user_id, $question_id)) {
				echo "<span class='p-1 rounded-circle text-white bg-success'><i class='far fa-check-circle'></i></span>";
			} else {
				echo "<span class='p-1 rounded-circle text-white bg-danger'><i class='far fa-times-circle'></i></span>";
			}
		} else {
			echo "<span class='px-2'><!--<i class='far fa-dot-circle'></i>--></span>";
		}
	}
}




if (!function_exists('count_total_solved_question')) {

	function count_total_solved_question($user_id, $test_id)
	{
		$response_modal = new ResponseModal();
		return count($response_modal->where(["test_id" => $test_id, "user_id" => $user_id])->find());
	}
}

if (!function_exists('count_total_left_question')) {

	function count_total_left_question($user_id, $test_id)
	{
		return count_all_questions($test_id) - count_total_solved_question($user_id, $test_id);
	}
}


if (!function_exists('count_total_correct')) {

	function count_total_correct($user_id, $test_id)
	{
		$response_modal = new ResponseModal();
		$data = $response_modal->where(["test_id" => $test_id, "user_id" => $user_id, "status" => 1])->find();
		return count($data);
	}
}

if (!function_exists('count_total_incorrect')) {
	function count_total_incorrect($user_id, $test_id)
	{
		$response_modal = new ResponseModal();
		$data = $response_modal->where(["test_id" => $test_id, "user_id" => $user_id, "status" => 0])->find();
		return count($data);
	}
}

if (!function_exists('count_final_score')) {
	function count_final_score($test_id, $user_id)
	{
		$db = db_connect();
		$marks = $db->query("SELECT SUM(`marks`) as score FROM `test_response` WHERE `test_id` ='$test_id' AND `user_id`='$user_id' GROUP BY user_id")->getResult();
		$db->close();
		if (!empty($marks))
			return $marks[0]->score;
	}
}

if (!function_exists('count_positive_score')) {
	function count_positive_score($test_id, $user_id)
	{
		$db = db_connect();
		$marks = $db->query("SELECT SUM(CASE WHEN marks > 0 THEN marks ELSE 0 END) as score FROM `test_response` WHERE `test_id` ='$test_id' AND `user_id`='$user_id' GROUP BY user_id")->getResult();
		$db->close();
		if (!empty($marks))
			return $marks[0]->score;
	}
}

if (!function_exists('count_negative_score')) {
	function count_negative_score($test_id, $user_id)
	{
		$db = db_connect();
		$marks = $db->query("SELECT SUM(CASE WHEN marks < 0 THEN marks ELSE 0 END) as score FROM `test_response` WHERE `test_id` ='$test_id' AND `user_id`='$user_id' GROUP BY user_id")->getResult();
		$db->close();
		if (!empty($marks))
			return $marks[0]->score;
	}
}

if (!function_exists('fetch_all_response')) {
	function fetch_all_response($user_id, $test_id)
	{
		if (!$response = cache("$user_id._complete_response_$test_id")) {
			$response_modal = new ResponseModal();
			$response = $response_modal->where(["user_id" => $user_id, "test_id" => $test_id])->find();
			cache()->save("$user_id._complete_response_$test_id", $response, 300);
		}
		return $response;
		// print_r($response);
	}
}

if (!function_exists('fetch_previous_response')) {
	function fetch_previous_response($user_id, $test_id, $question_id)
	{
		// $response_modal = new ResponseModal();
		// $data = $response_modal->where(["user_id" => $user_id, "question_id" => $question_id])->first();
		$response = fetch_all_response($user_id, $test_id);
		foreach ($response as $single_response) {
			if ($single_response->question_id == $question_id) {
				return $single_response->response;
				break;
			}
		}
		if (!empty($data)) {
			return $data->response;
		} else
			return '';
	}
}

if (!function_exists('getUserData')) {
	function getUserData($user_id)
	{
		if (!$user_detail = cache("user_detail_$user_id")) {
			$user_modal = new UserAccountModal();
			$user_detail = $user_modal->where('id', $user_id)->first();
			cache()->save("user_detail_$user_id", $user_detail, 1800);
		}
		return $user_detail;
	}
}

if (!function_exists('getUserID')) {
	function getUserID($user_email)
	{
		$db = db_connect();
		$user = $db->query("select * from user_account WHERE email='$user_email'")->getResult();
		$db->close();
		if (!empty($user))
			return $user[0]->id;
	}
}

if (!function_exists('generate_id')) {
	function GenerateTestID()
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
		$randomString = '';
		$id_length = 5;
		for ($i = 0; $i < $id_length; $i++) {
			$index = rand(0, strlen($characters) - 1);
			$randomString .= $characters[$index];
		}
		$property = new TestModal();
		if (empty($property->where('test_id', $randomString)->findAll())) {
			return $randomString;
		} else {
			GenerateTestID();
		}
	}
}

if (!function_exists('getBranchNameWithID')) {
	function getBranchNameWithID($id)
	{
		$branch_name = ['', 'CE', 'CSE', 'ECE', 'EEE', 'ME', 'MME', 'PIE'];
		return $branch_name[$id];
	}
}
