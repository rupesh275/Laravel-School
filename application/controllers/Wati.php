<?php

// if (!defined('BASEPATH')) {
//     exit('No direct script access allowed');
// }

class Wati extends Admin_Controller
{
    public function index($id = "")
    {
        // echo "start<pre>";
        // $curl = curl_init();

        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => 'https://live-server-9301.wati.io/api/v1/sendSessionMessage/9605252637?messageText=hello',
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'POST',
        //     CURLOPT_POSTFIELDS => '{
        //         "messageText": "This is a test message",
        //     }',
        //     CURLOPT_HTTPHEADER => array(
        //         'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiJlZjMwZTYzMS1lZjY4LTQ3ZjUtODczNS1hZTA4YWNiZTVkOTUiLCJ1bmlxdWVfbmFtZSI6Im1hbm9qdGhhbm5pbWF0dGFtQGdtYWlsLmNvbSIsIm5hbWVpZCI6Im1hbm9qdGhhbm5pbWF0dGFtQGdtYWlsLmNvbSIsImVtYWlsIjoibWFub2p0aGFubmltYXR0YW1AZ21haWwuY29tIiwiYXV0aF90aW1lIjoiMDIvMjAvMjAyMyAxNDoxMjoyNyIsImRiX25hbWUiOiI5MzAxIiwiaHR0cDovL3NjaGVtYXMubWljcm9zb2Z0LmNvbS93cy8yMDA4LzA2L2lkZW50aXR5L2NsYWltcy9yb2xlIjoiQURNSU5JU1RSQVRPUiIsImV4cCI6MjUzNDAyMzAwODAwLCJpc3MiOiJDbGFyZV9BSSIsImF1ZCI6IkNsYXJlX0FJIn0.D70gIc4rR3BL8SbsNdQWcfnH9KIKe73tPXGe08ECno4',
        //         'Content-Type: application/json'
        //     ),
        // ));
        // // print_r($curl);
        // $response = curl_exec($curl);

        // curl_close($curl);
        // echo $response;


        // require_once('vendor/autoload.php');

        // $client = new GuzzleHttp\Client();
        // $response = $client->request('POST', 'https://app-server.wati.io/api/v1/sendSessionMessage/918828193908?messageText=hello', [
        //     'headers' => [
        //         'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiJlZjMwZTYzMS1lZjY4LTQ3ZjUtODczNS1hZTA4YWNiZTVkOTUiLCJ1bmlxdWVfbmFtZSI6Im1hbm9qdGhhbm5pbWF0dGFtQGdtYWlsLmNvbSIsIm5hbWVpZCI6Im1hbm9qdGhhbm5pbWF0dGFtQGdtYWlsLmNvbSIsImVtYWlsIjoibWFub2p0aGFubmltYXR0YW1AZ21haWwuY29tIiwiYXV0aF90aW1lIjoiMDIvMjAvMjAyMyAxNDoxMjoyNyIsImRiX25hbWUiOiI5MzAxIiwiaHR0cDovL3NjaGVtYXMubWljcm9zb2Z0LmNvbS93cy8yMDA4LzA2L2lkZW50aXR5L2NsYWltcy9yb2xlIjoiQURNSU5JU1RSQVRPUiIsImV4cCI6MjUzNDAyMzAwODAwLCJpc3MiOiJDbGFyZV9BSSIsImF1ZCI6IkNsYXJlX0FJIn0.D70gIc4rR3BL8SbsNdQWcfnH9KIKe73tPXGe08ECno4',
        //         'Content-Type' => 'application/json'
        //     ],
        // ]);

        // echo $response->getBody();



        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://live-server-111987.wati.io/api/v1/sendTemplateMessage?whatsappNumber=919605252637',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "template_name": "att_student",
                "broadcast_name": "att_student",
                "parameters": [
                    {
                        "name": "name",
                        "value": "Sajimon"
                      }                  
                ]
              }',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiJiOWYzYzhlYS1iODU1LTQwNmEtYjNlYy02MmRkZGM5YTNlZDYiLCJ1bmlxdWVfbmFtZSI6InNuZ2NlbnRyYWxzY2hvb2xAZ21haWwuY29tIiwibmFtZWlkIjoic25nY2VudHJhbHNjaG9vbEBnbWFpbC5jb20iLCJlbWFpbCI6InNuZ2NlbnRyYWxzY2hvb2xAZ21haWwuY29tIiwiYXV0aF90aW1lIjoiMDcvMDYvMjAyMyAxNTo0NTowNSIsImRiX25hbWUiOiIxMTE5ODciLCJodHRwOi8vc2NoZW1hcy5taWNyb3NvZnQuY29tL3dzLzIwMDgvMDYvaWRlbnRpdHkvY2xhaW1zL3JvbGUiOiJBRE1JTklTVFJBVE9SIiwiZXhwIjoyNTM0MDIzMDA4MDAsImlzcyI6IkNsYXJlX0FJIiwiYXVkIjoiQ2xhcmVfQUkifQ.Srj5wpzKJ4k98JqkJlJxtPZFMNYciWactuI_kSmB_wE',
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);

        curl_close($curl);

        echo $response;



        // $curl = curl_init();

        // curl_setopt_array($curl, array(
        //   CURLOPT_URL => 'https://live-server-9301.wati.io/api/v1/sendSessionMessage/918828193908',
        //   CURLOPT_RETURNTRANSFER => true,
        //   CURLOPT_ENCODING => '',
        //   CURLOPT_MAXREDIRS => 10,
        //   CURLOPT_TIMEOUT => 0,
        //   CURLOPT_FOLLOWLOCATION => true,
        //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //   CURLOPT_CUSTOMREQUEST => 'POST',
        //   CURLOPT_POSTFIELDS => array('messageText' => 'Hello Sandeep'),
        //   CURLOPT_HTTPHEADER => array(
        //     'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiI4OWU2ZDUyMC00YTA5LTQ0YTMtOTBhNC1jZTZjOGJmODdjZTEiLCJ1bmlxdWVfbmFtZSI6Imd1cHRhcnVwZXNoMjc1QGdtYWlsLmNvbSIsIm5hbWVpZCI6Imd1cHRhcnVwZXNoMjc1QGdtYWlsLmNvbSIsImVtYWlsIjoiZ3VwdGFydXBlc2gyNzVAZ21haWwuY29tIiwiYXV0aF90aW1lIjoiMDIvMjAvMjAyMyAxNToxOTowNyIsImRiX25hbWUiOiJ3YXRpX2FwcF90cmlhbCIsImh0dHA6Ly9zY2hlbWFzLm1pY3Jvc29mdC5jb20vd3MvMjAwOC8wNi9pZGVudGl0eS9jbGFpbXMvcm9sZSI6IlRSSUFMIiwiZXhwIjoxNjc3NTQyNDAwLCJpc3MiOiJDbGFyZV9BSSIsImF1ZCI6IkNsYXJlX0FJIn0.qUxRC2ktu8tvZwAA7kkWqMFkvWQh2D23O4OJDyaBihs'
        //   ),
        // ));

        // $response = curl_exec($curl);

        // curl_close($curl);
        // echo $response;
    }

    public function sendwhatsappmsg($number,$name)
    {
        $params = '{
            "template_name": "att_student",
            "broadcast_name": "att_student",
            "parameters": [
                {
                    "name": "name",
                    "value": "'.$name.'"
                  }
            ]
          }';
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://live-server-111987.wati.io/api/v1/sendTemplateMessage?whatsappNumber=919605252637',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiJlNzJmMzMzMi05ZjEwLTQ2ZGMtOTQ0Zi1hNDJjZGUzMGQ5NTkiLCJ1bmlxdWVfbmFtZSI6InNuZ2NlbnRyYWxzY2hvb2xAZ21haWwuY29tIiwibmFtZWlkIjoic25nY2VudHJhbHNjaG9vbEBnbWFpbC5jb20iLCJlbWFpbCI6InNuZ2NlbnRyYWxzY2hvb2xAZ21haWwuY29tIiwiYXV0aF90aW1lIjoiMDcvMDYvMjAyMyAxNzoyODo0NyIsImRiX25hbWUiOiIxMTE5ODciLCJodHRwOi8vc2NoZW1hcy5taWNyb3NvZnQuY29tL3dzLzIwMDgvMDYvaWRlbnRpdHkvY2xhaW1zL3JvbGUiOiJBRE1JTklTVFJBVE9SIiwiZXhwIjoyNTM0MDIzMDA4MDAsImlzcyI6IkNsYXJlX0FJIiwiYXVkIjoiQ2xhcmVfQUkifQ.ks7DcVtEQGo-LE2hpW73BUPE27gVQC747SD-SXCbWQU',
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;
    }
    public function whatsapp_msg($url,$param){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://live-server-111987.wati.io/api/v1/'.$url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $param,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiJlNzJmMzMzMi05ZjEwLTQ2ZGMtOTQ0Zi1hNDJjZGUzMGQ5NTkiLCJ1bmlxdWVfbmFtZSI6InNuZ2NlbnRyYWxzY2hvb2xAZ21haWwuY29tIiwibmFtZWlkIjoic25nY2VudHJhbHNjaG9vbEBnbWFpbC5jb20iLCJlbWFpbCI6InNuZ2NlbnRyYWxzY2hvb2xAZ21haWwuY29tIiwiYXV0aF90aW1lIjoiMDcvMDYvMjAyMyAxNzoyODo0NyIsImRiX25hbWUiOiIxMTE5ODciLCJodHRwOi8vc2NoZW1hcy5taWNyb3NvZnQuY29tL3dzLzIwMDgvMDYvaWRlbnRpdHkvY2xhaW1zL3JvbGUiOiJBRE1JTklTVFJBVE9SIiwiZXhwIjoyNTM0MDIzMDA4MDAsImlzcyI6IkNsYXJlX0FJIiwiYXVkIjoiQ2xhcmVfQUkifQ.ks7DcVtEQGo-LE2hpW73BUPE27gVQC747SD-SXCbWQU',
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $json = json_decode($response, true); 
        return $json['result'];
    }
    public function sendAttendenceMsg($name,$number)
    {
        $params = '{
            "template_name": "att_student",
            "broadcast_name": "att_student",
            "parameters": [
                {
                    "name": "name",
                    "value": "'.$name.'"
                  }
            ]
          }';
        $url = 'sendTemplateMessage?whatsappNumber=91'.$number;
        if($this->whatsapp_msg($url,$params))
        {echo true;}
        else
        {echo false;}
    }
    public function sendleave_apply($name,$number)
    {
        $params = '{
            "template_name": "leave_apply",
            "broadcast_name": "leave_apply",
            "parameters": [
                {
                    "name": "name",
                    "value": "'.$name.'"
                  }
            ]
          }';
        $url = 'sendTemplateMessage?whatsappNumber=91'.$number;
        if($this->whatsapp_msg($url,$params))
        {echo true;}
        else
        {echo false;}
    }    
    public function get_wati()
    {
        $array_data = $this->input->post();
        $insert_record = array(
            'test' => $array_data,
        );
        $this->db->insert('test1', $insert_record);
        echo "success";
    }

    public function test_mst()
    {
        $data = array(
            "mobno" => "9605252637",
            "name" => "Test Name",
            "class" => "Class I",
            "division" => "A",
            "rollno" => "10",        
            "amount" => "15000",
            "to_date" => "10-08-2024"
        );
        $this->wati_model->student_fees_dues_weekly($data);
        //$this->staff_model->get_class_teacher(1570);
    }
    public function test_cron()
    {
        // $this->load->model('test1');
        $arr = [
            'test' => date('Y-m-d H:i:s')
        ];
        $this->db->insert('test1',$arr);

    }

    public function setting()
    {
        if (!$this->rbac->hasPrivilege('wati_setting', 'can_view')) {
            access_denied();
        }
        $data                = array();
        $data['title']       = 'Wati Config List';
        $data['mailMethods'] = $this->customlib->getMailMethod();
        // $emaillist           = $this->emailconfig_model->get();
        $data['resullist']           = $this->emailconfig_model->getActiveWati();
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'wati/setting');

        $this->form_validation->set_rules('mobile_no', $this->lang->line('mobile_no'), 'required');
        $this->form_validation->set_rules('url', "URL", 'required');
        $this->form_validation->set_rules('auth_key', "Authentication Key", 'required');
        $this->form_validation->set_rules('status', "Status", 'required');

        if ($this->form_validation->run() === false) {
            $data['title'] = 'Whatsapp Config List';
            $this->load->view('layout/header', $data);
            $this->load->view('wati/index', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data['title'] = 'Whatsapp Config List';
            $data_insert   = array(
                'mobile_no'    => $this->input->post('mobile_no'),
                'url' => $this->input->post('url'),
                'auth_key' => $this->input->post('auth_key'),
                'status'   => $this->input->post('status'),
            );
            $this->emailconfig_model->addWati($data_insert);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            redirect('wati/setting');
        }
    }
}
