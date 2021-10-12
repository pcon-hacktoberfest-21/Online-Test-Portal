<?php

namespace App\Controllers;

use App\Models\EnrolledModal;
use App\Models\QuestionModal;
use App\Models\ResponseModal;
use App\Models\UserAccountModal;
use CodeIgniter\HTTP\Response;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;

class Mail extends BaseController
{
    public function index()
    {
        $mail = new PHPMailer(true);
        try {
            //Server settings
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = getenv('smtp_host');                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = getenv('smtp_user');                     // SMTP username
            $mail->Password   = getenv('smtp_pass');                               // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = getenv('smtp_port');                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
            //Recipients
            $mail->setFrom(getenv('smtp_from'));
            $mail->addAddress('abhishekkumarjnvk@gmail.com');               // Name is optional
            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Here is the subject';
            $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
    public function Extract()
    {
        ini_set('memory_limit', '-1');
        $file = $this->request->getFile('excel_file');
        $reader = new ReaderXlsx();
        $spreadsheet = $reader->load($file);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        print_r($sheetData);
    }


    public function ExtractForm()
    {
        return view('Admin/ExtractEmail',);
    }
    
    public function ViewResult()
    {
        helper('master_helper');
        echo fetch_previous_response('1022', 'TEST5f56f42d9160e','Ques5f56f4b8edfc5a');
        // fetch_all_response('1022','TEST5f56f42d9160e');
    }



}
