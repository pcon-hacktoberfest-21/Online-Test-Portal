<?php

namespace App\Controllers;

class Logout extends BaseController
{
    public function index()
    {
        session();
        unset($_SESSION['username'],
        $_SESSION['email'],
        $_SESSION['logged_in']);
        if (isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach ($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                if ($name != "ci_session") {
                    setcookie($name, '', time() - 1000);
                    setcookie($name, '', time() - 1000, '/');
                }
            }
        }
        setcookie("session_id", "", time() - 3600);
        $data['page_title'] = "Logout";
        return view('Users/logout', $data);
    }
}
