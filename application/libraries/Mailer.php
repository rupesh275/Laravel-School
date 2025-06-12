<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Mailer {

    public $mail_config;
    public $CI;
    private $sch_setting;
 
    public function __construct() {
        $this->CI = &get_instance();
        $this->CI->load->model('emailconfig_model');
        $this->CI->mail_config = $this->CI->emailconfig_model->getActiveEmail();
        $this->CI->load->model('setting_model');
        $this->sch_setting = $this->CI->setting_model->get();
    }
 
    // public function send_mail($toemail, $subject, $body, $FILES = array(), $cc = "") {

    //     $mail = new PHPMailer();
    //     $mail->CharSet = 'UTF-8';
        
    //     $school_name = $this->sch_setting[0]['name'];
    //     $school_email    = $this->sch_setting[0]['email'];
    //     if ($this->CI->mail_config->email_type == "smtp") {

    //         $mail->IsSMTP();
    //         $mail->SMTPAuth   = ($this->CI->mail_config->smtp_auth != "") ? $this->CI->mail_config->smtp_auth : "";
    //         $mail->SMTPSecure = $this->CI->mail_config->ssl_tls;
    //         $mail->Host       = $this->CI->mail_config->smtp_server;
    //         $mail->Port       = $this->CI->mail_config->smtp_port;
    //         $mail->Username   = $this->CI->mail_config->smtp_username;
    //         $mail->Password   = $this->CI->mail_config->smtp_password;
    //         $mail->SetFrom($this->CI->mail_config->smtp_username, $school_name);
    //         $mail->AddReplyTo($this->CI->mail_config->smtp_username, $this->CI->mail_config->smtp_username);
    //     } else {
    //         $mail->isSMTP();
    //         $mail->Host        = 'localhost';
    //         $mail->SMTPAuth    = false;
    //         $mail->SMTPAutoTLS = false;
    //         $mail->Port        = 25;
    //         $mail->SetFrom($school_email, $school_name);
    //         $mail->AddReplyTo($school_email, $school_name);
    //     }
    //     if (!empty($FILES)) {
    //         if (isset($_FILES['files']) && !empty($_FILES['files'])) {
    //             $no_files = count($_FILES["files"]['name']);
    //             for ($i = 0; $i < $no_files; $i++) {
    //                 if ($_FILES["files"]["error"][$i] > 0) {
    //                     echo "Error: " . $_FILES["files"]["error"][$i] . "<br>";
    //                 } else {
    //                     $file_tmp = $_FILES["files"]["tmp_name"][$i];
    //                     $file_name = $_FILES["files"]["name"][$i];
    //                     $mail->AddAttachment($file_tmp, $file_name);
    //                 }
    //             }
    //         }
    //     }
        
    //     if ($cc != "") {
    //         $mail->AddCC($cc);
    //     }
    //     $mail->Subject = $subject;
    //     $mail->Body = $body;
    //     $mail->AltBody = $body;
    //     $mail->AddAddress($toemail);
    //     if ($mail->Send()) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    public function send_mail($toemail, $subject, $body, $FILES = array(), $cc = "") {

        $school_name = $this->sch_setting[0]['name'];
        $school_email = $this->sch_setting[0]['email'];

        if ($this->CI->mail_config->email_type == "smtp") {
            $from_mail = $this->CI->mail_config->smtp_username;
            $smtp_host = $this->CI->mail_config->smtp_server;
            $smtp_port = $this->CI->mail_config->smtp_port;
            $smtp_user = $this->CI->mail_config->smtp_username;
            $smtp_pass = $this->CI->mail_config->smtp_password;
            $config = array(
                'protocol' => "SMTP",
                'smtp_host' => $smtp_host,
                'smtp_port' => $smtp_port,
                'smtp_user' => $smtp_user,
                'smtp_pass' => $smtp_pass,
                'mailtype' => 'html',
                'crlf' => "\r\n",
                'newline' => "\r\n",
                'validate' => False,
                'charset' => "utf-8",
                'wordwrap' => TRUE,
            );

            $this->CI->load->library('email');
            $this->CI->email->initialize($config);
            $this->CI->email->from($from_mail, $school_name);
            $this->CI->email->to($toemail);
            if ($cc != "") {
                $this->CI->email->cc($cc);
            }
            $this->CI->email->subject($subject);
            $this->CI->email->message($body);
            if (!empty($FILES)) {
                if (isset($_FILES['files']) && !empty($_FILES['files'])) {
                    $no_files = count($_FILES["files"]['name']);
                    for ($i = 0; $i < $no_files; $i++) {
                        if ($_FILES["files"]["error"][$i] > 0) {
                            echo "Error: " . $_FILES["files"]["error"][$i] . "<br>";
                        } else {
                            $file_tmp = $_FILES["files"]["tmp_name"][$i];
                            $file_name = $_FILES["files"]["name"][$i];
                            // $mail->AddAttachment($file_tmp, $file_name);
                            $this->CI->email->attach($file_name,'attachment',$file_tmp);
                        }
                    }
                }
            }
            $abc = $this->CI->email->send();
        
        
            if (!$abc) {
                echo $this->CI->email->print_debugger();
            } else {
                return true;

            }


        }
    }



    public function send_mail_normal($toemail, $subject, $body, $FILES = array(), $cc = "") {
        $school_name = $this->sch_setting[0]['name'];
        $school_email    = $this->sch_setting[0]['email'];

        
        //$toemail = "gurusmsservices@gmail.com";
        //$subject = "This is a test HlklklklTMzxczxxzcL email";
        
        $message = "
        <html>
        <head>
        <title>This is kkkk a test HTML email</title>
        </head>
        <body>
        <div>
        <h3>SNG International School</h3>
        <p>Thank you for your enquiry, Our admission department will contact you soon..</p>
        
        
        </div>
        </body>
        </html>
        ";
        
        // It is mandatory to set the content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        
        // More headers. From is required, rest other headers are optional
        $headers .= 'From: '.$school_name.' <admission@sngintlulwe.com>' . "\r\n";
        $headers .= 'Reply-To: '.$school_name.' <admission@sngintlulwe.com>' . "\r\n";
        //$headers .= 'Cc: sales@example.com' . "\r\n";
        mail($toemail,$subject,$message,$headers);
        if(mail($school_email,$subject,$body,$headers))
            return true;
        else
            return false;
        exit(0);

        $mail = new PHPMailer();
        $mail->CharSet = 'UTF-8';
        $school_name = $this->sch_setting[0]['name'];
        $school_email    = $this->sch_setting[0]['email'];

        // It is mandatory to set the content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        
        // More headers. From is required, rest other headers are optional
        $headers .= 'From:'.$school_name.' <manojthannimattam@gmail.com>' . "\r\n";
        $headers .= 'Reply-To:'.$school_name.' <manojthannimattam@gmail.com>' . "\r\n";
        if ($cc != "") {        
            $headers .= 'Cc: '.$cc . "\r\n";  
        }
        if(mail($toemail,$subject,$message,$headers))
            return true;
        else
            return false;
        /*
        if (!empty($FILES)) {
            if (isset($_FILES['files']) && !empty($_FILES['files'])) {
                $no_files = count($_FILES["files"]['name']);
                for ($i = 0; $i < $no_files; $i++) {
                    if ($_FILES["files"]["error"][$i] > 0) {
                        echo "Error: " . $_FILES["files"]["error"][$i] . "<br>";
                    } else {
                        $file_tmp = $_FILES["files"]["tmp_name"][$i];
                        $file_name = $_FILES["files"]["name"][$i];
                        $mail->AddAttachment($file_tmp, $file_name);
                    }
                }
            }
        }
        
        if ($cc != "") {
            $mail->AddCC($cc);
        }
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = $body;
        $mail->AddAddress($toemail);
        if ($mail->Send()) {
            return true;
        } else {
            return false;
        }
        */
    }


}
