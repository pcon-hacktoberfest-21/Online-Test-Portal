<?php

namespace App\Controllers;

use CodeIgniter\Encryption\Exceptions\EncryptionException as ExceptionsEncryptionException;
use Encryption\Encryption;
use Encryption\Exception\EncryptionException;


class Home extends BaseController
{
    public function index()
    {
        session();
        return redirect()->to(getenv('app.baseURL') . '/Dashboard');
    }
}
