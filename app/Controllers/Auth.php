<?php

namespace App\Controllers;

use App\Models\AdminModal;
use App\Models\UserAccountModal;
use Google_Client;

class Auth extends BaseController
{
    public function __construct()
    {
        $session = session();
        if ($session->get('admin_id') != "") {
            $auth_url = getenv('app.baseURL') . '/Admin';
            header("Location: $auth_url");
            die();
        }
        if ($session->get('username') != "") {
            $auth_url = getenv('app.baseURL') . '/Dashboard';
            header("Location: $auth_url");
            exit;
        }
    }
    public function index()
    {
        $data['page_title'] = "Please Login";
        return view('Users/login', $data);
    }
    
    public function Host()
    {
        $data['page_title'] = "Please Login";
        return view('Users/host_login', $data);
    }
    
    public function Login()
    {
        $data['page_title'] = "Please Login";
        $session = session();
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user_modal = new UserAccountModal();
            $user_data = $user_modal->where('email', $email)->first();
            if (!empty($user_data)) {
                if ($user_data->password == ($password)) {
                    $newdata = [
                        'username'  => $user_data->id,
                        'email' => $user_data->email,
                        'logged_in' => TRUE
                    ];
                    $session->set($newdata);
                    $session->setFlashdata('flash_response', 'Login Successful');
                    return redirect()->to(getenv('app.baseURL') . '/Dashboard');
                } else {
                    $session->setFlashdata('flash_response', 'Wrong Password');
                    return view('Users/email_login', $data);
                }
            } else {
                $email = $_POST['email'];
                $password = $_POST['password'];
                $picture = "https://kintechrenewables.com/wp-content/uploads/2018/10/sample.png";
                $new_user_data['email'] = ($email);
                $new_user_data['name'] = ("");
                $new_user_data['createdOn'] = date("Y-m-d H:i:s");
                $new_user_data['modifiedOn'] = date("Y-m-d H:i:s");
                $new_user_data['password'] = ($password);
                $new_user_data['isActive'] = 1;
                $new_user_data['sharingID'] = uniqid("P");
                $new_user_data['verified'] = 0;
                $new_user_data['picture'] = $picture;
                if ($user_modal->save($new_user_data)) {
                    $user_id = $user_modal->where('email', $email)->first()->id;
                    $newdata = [
                        'username'  => $user_id,
                        'email' => $email,
                        'logged_in' => TRUE
                    ];
                    $session->set($newdata);
                    $session->setFlashdata('flash_response', 'Signup Successful');
                    return redirect()->to(getenv('app.baseURL') . '/Dashboard');
                } else {
                    $session->setFlashdata('flash_response', 'Something Went Wrong from Our End ');
                }
            }
        }
        return view('Users/email_login', $data);
    }
    public function GoogleLogin()
    {
        $session = session();
        if (isset($_POST['id_token'])) {
            $id_token = $_POST['id_token'];
            $client = new Google_Client(['client_id' => getenv('googleClientID')]);
            $payload = $client->verifyIdToken($id_token);
            if ($payload) {
                $user_modal = new UserAccountModal();
                $email = $payload['email'];
                $user_data = $user_modal->where('email', $email)->first();
                if (!empty($user_data)) {
                    $newdata = [
                        'username'  => $user_data->id,
                        'email' => $user_data->email,
                        'logged_in' => TRUE
                    ];
                    $session->set($newdata);
                    // $session->setFlashdata('flash_response', 'Signed in via Google');
                    return redirect()->to(getenv('app.baseURL') . '/Dashboard');
                } else {
                    $email = $payload['email'];
                    $name = $payload['name'];
                    $picture = $payload['picture'];
                    $new_user_data['email'] = ($email);
                    $new_user_data['name'] = ($name);
                    $new_user_data['createdOn'] = date("Y-m-d H:i:s");
                    $new_user_data['modifiedOn'] = date("Y-m-d H:i:s");
                    $new_user_data['isActive'] = 1;
                    $new_user_data['sharingID'] = uniqid("P");
                    if (isset($payload['hd'])) {
                        if ($payload['hd'] == "nitjsr.ac.in") {
                            $new_user_data['verified'] = 1;
                            $new_user_data['roll'] = str_replace("@nitjsr.ac.in", "", $email);
                        }
                    } else {
                        $new_user_data['verified'] = 0;
                    }
                    $new_user_data['picture'] = $picture;
                    if ($user_modal->save($new_user_data)) {
                        $user_id = $user_modal->where('email', $email)->first()->id;
                        $newdata = [
                            'username'  => $user_id,
                            'email' => $email,
                            'logged_in' => TRUE
                        ];
                        $session->set($newdata);
                        // $session->setFlashdata('flash_response', 'Signed in via Google');
                        return redirect()->to(getenv('app.baseURL') . '/Dashboard');
                    } else {
                        $session->setFlashdata('flash_response', 'Something Went Wrong from Our End ');
                    }
                }
            } else {
                $session->setFlashdata('flash_response', "Google Login Failed Please Try Again");
            }
        }
        return redirect()->to(getenv('app.baseURL') . '/Auth');
    }
    public function HostLoginHandler()
    {
        $session = session();
        if (isset($_POST['id_token'])) {
            $id_token = $_POST['id_token'];
            $client = new Google_Client(['client_id' => getenv('googleClientID')]);
            $payload = $client->verifyIdToken($id_token);
            if ($payload) {
                if (isset($payload['hd'])) {
                    if ($payload['hd'] == "nitjsr.ac.in") {
                        $admin_modal = new AdminModal();
                        $email = $payload['email'];
                        $admin_data = $admin_modal->where('email', $email)->first();
                        if (!empty($admin_data)) {
                            $newdata = [
                                'admin_id'  => $admin_data->id,
                                'email' => $email,
                                'logged_in' => TRUE
                            ];
                            $session->set($newdata);
                            // $session->setFlashdata('flash_response', 'Signed in via NIT Mail');
                            return redirect()->to(getenv('app.baseURL') . '/Admin');
                        } else {
                            $email = $payload['email'];
                            $name = $payload['name'];
                            $picture = $payload['picture'];
                            $new_user_data['email'] = ($email);
                            $new_user_data['name'] = ($name);
                            $new_user_data['createdOn'] = date("Y-m-d H:i:s");
                            $new_user_data['verified'] = getenv('default_isVerified');
                            $new_user_data['picture'] = $picture;
                            if ($admin_modal->save($new_user_data)) {
                                $admin_id = $admin_modal->where('email', $email)->first()->id;
                                $newdata = [
                                    'admin_id'  => $admin_id,
                                    'email' => $email,
                                    'logged_in' => TRUE
                                ];
                                $session->set($newdata);
                                // $session->setFlashdata('flash_response', 'Signed in via NIT Mail');
                                return redirect()->to(getenv('app.baseURL') . '/Admin');
                            } else {
                                $session->setFlashdata('flash_response', 'Something Went Wrong from Our End ');
                            }
                        }
                    } else {
                        $session->setFlashdata('flash_response', "Login Via NIT Mail");
                    }
                } else {
                    $session->setFlashdata('flash_response', "Login Via NIT Mail");
                }
            } else {
                $session->setFlashdata('flash_response', "Google Login Failed Please Try Again");
            }
        }
        return redirect()->to(getenv('app.baseURL') . '/Auth/Host');
    }
}
