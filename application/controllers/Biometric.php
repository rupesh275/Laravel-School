<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Biometric extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('json_output');
        $this->load->model('setting_model');
        $this->load->model('student_model');
        $this->load->model('stuattendence_model');
    }

    public function index()
    {
        $method = $this->input->server('REQUEST_METHOD');
        if ($method != 'POST') {
            json_output(400, array('status' => 400, 'message' => 'Bad request.'));
        } else {

            $attendence_param = file_get_contents('php://input');
            $params           = json_decode(file_get_contents('php://input'), true);
            $settings         = $this->setting_model->getSchoolDetail();
            if ($settings->biometric) {
                $total_devices = array_map('trim', explode(",", $settings->biometric_device));
                if (!empty($params)) {
                    if ($params['serial_number']) {
                        if (in_array($params['serial_number'], $total_devices)) {

                            $student = $this->student_model->findByAdmission($params['user_id']);
                            if ($student) {
                                $insert_record = array(
                                    'date'                  => date('Y-m-d', strtotime($params['t'])),
                                    'student_session_id'    => $student->student_session_id,
                                    'attendence_type_id'    => 1,
                                    'biometric_attendence'  => 1,
                                    'remark'                => '',
                                    'created_at'            => $params['t'],
                                    'biometric_device_data' => $attendence_param,
                                );
                                $insert_result = $this->stuattendence_model->onlineattendence($insert_record);
                                if ($insert_result) {
                                    json_output(200, array('status' => 200, 'message' => 'Record Inserted.'));
                                } else {
                                    json_output(200, array('status' => 200, 'message' => 'Something Wrong.'));
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    public function display_chars()
    {
        $i=1;
        while($i<500)
        {
            echo "<br>".chr($i);
            ++$i;
        }
    }
    public function Download_biometric_log() {
        $this->load->helper('url'); // Load URL helper if needed

        // SOAP endpoint URL
        $url = "http://58.146.99.124:85/iclock/webapiservice.asmx?op=GetTransactionsLog";

        // SOAP XML request
        $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
        <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
            <soap:Body>
                <GetTransactionsLog xmlns="http://tempuri.org/">
                    <FromDateTime>2024/08/12 00:01</FromDateTime>
                    <ToDateTime>2024/08/12 23:59</ToDateTime>
                    <SerialNumber>ADZV211860817</SerialNumber>
                    <UserName>api</UserName>
                    <UserPassword>Snms@2024</UserPassword>
                    <strDataList></strDataList>
                </GetTransactionsLog>
            </soap:Body>
        </soap:Envelope>';

        // Set headers
        $headers = array(
            "Content-type: text/xml; charset=utf-8",
            "Content-length: " . strlen($xml_post_string),
            "SOAPAction: http://tempuri.org/GetTransactionsLog",
        );

        // Initialize cURL
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);  // Timeout settings
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        // Enable verbose output for debugging
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        $verbose = fopen('php://temp', 'w+');
        curl_setopt($ch, CURLOPT_STDERR, $verbose);

        // Execute cURL and get the response
        $response = curl_exec($ch);

        // Handle errors
        if (curl_errno($ch)) {
            $error_msg = 'cURL error: ' . curl_error($ch);
            echo $error_msg;
        } else {
            echo 'Response: ' . htmlspecialchars($response);
        }

        // Debugging output
        rewind($verbose);
        $verbose_log = stream_get_contents($verbose);
        echo "<br><br>Verbose information:<br><pre>$verbose_log</pre>";

        curl_close($ch);       
    }
    
}
