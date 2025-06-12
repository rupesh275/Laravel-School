<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class School extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function show_404() {
        $this->load->view('errors/error_message');
    }

    public function send_email() {
        // Load the email library
        $this->load->library('email');

        // Email configuration
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'mail.sngcentralschool.org';
        $config['smtp_user'] = 'info@sngcentralschool.org';
        $config['smtp_pass'] = 'SYYI6KBQCTQQ';
        $config['smtp_port'] = 465; // or 587
        $config['smtp_crypto'] = 'ssl'; // or 'tls'
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['wordwrap'] = TRUE;
        // Initialize email config
        $this->email->initialize($config);

        // Set email parameters
        $this->email->from('info@sngcentralschool.org', 'SNG Central School');
        // $this->email->to('alextezitservices@gmail.com');
        $this->email->to(['guptarupesh275@gmail.com', 'tezitservices@gmail.com']);
        $this->email->subject('Test Email');
        $this->email->message('This is a test email sent from CodeIgniter.');

        // Send the email
        if ($this->email->send()) {
            echo "Email sent successfully!";
        } else {
            echo "Failed to send email.";
            echo $this->email->print_debugger(); // Debugging the error
        }
    }

}
