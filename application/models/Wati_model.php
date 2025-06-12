<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Wati_model extends MY_Model {

    public $current_session;

    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }
    public function whatsapp_msg($url,$param){
      $query=$this->db->query('select * from wati_config')->row_array();
      if(!empty($query))
      {
        if($query['status']==1)
        { 
          $client = new \GuzzleHttp\Client();
          // $response = $client->request('POST', 'https://live-mt-server.wati.io/111987/api/v1/sendTemplateMessage?whatsappNumber=9605252637', [
          //   'body' => '{"template_name":"att_student","broadcast_name":"att_student","parameters":[{"name":"name","value":"manoj"}]}',
          //   'headers' => [
          //     'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiJjZDJjMzc5MS1mMTAwLTRhOGEtOGUwMi1jNGQ5MmE0NGU2YTUiLCJ1bmlxdWVfbmFtZSI6InNuZ2NlbnRyYWxzY2hvb2xAZ21haWwuY29tIiwibmFtZWlkIjoic25nY2VudHJhbHNjaG9vbEBnbWFpbC5jb20iLCJlbWFpbCI6InNuZ2NlbnRyYWxzY2hvb2xAZ21haWwuY29tIiwiYXV0aF90aW1lIjoiMDYvMTIvMjAyNCAwNTo0NjozMSIsImRiX25hbWUiOiJtdC1wcm9kLVRlbmFudHMiLCJ0ZW5hbnRfaWQiOiIxMTE5ODciLCJodHRwOi8vc2NoZW1hcy5taWNyb3NvZnQuY29tL3dzLzIwMDgvMDYvaWRlbnRpdHkvY2xhaW1zL3JvbGUiOiJBRE1JTklTVFJBVE9SIiwiZXhwIjoyNTM0MDIzMDA4MDAsImlzcyI6IkNsYXJlX0FJIiwiYXVkIjoiQ2xhcmVfQUkifQ.4LwJGFe1PEV38uCc22gfvtWdTNxOi58j9xXvZ4uE-rY',
          //     'content-type' => 'application/json-patch+json',
          //   ],
          // ]);
          try {
            $response = $client->request('POST', 'https://live-mt-server.wati.io/111987/api/v1/'.$url, [
              'body' => $param,
              'headers' => [
                'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiJjYWFiZDZiNy0zYmI2LTRhNmEtOGVkNi1lMjExMzNhNzk5MTUiLCJ1bmlxdWVfbmFtZSI6InNuZ2NlbnRyYWxzY2hvb2xAZ21haWwuY29tIiwibmFtZWlkIjoic25nY2VudHJhbHNjaG9vbEBnbWFpbC5jb20iLCJlbWFpbCI6InNuZ2NlbnRyYWxzY2hvb2xAZ21haWwuY29tIiwiYXV0aF90aW1lIjoiMDgvMjYvMjAyNCAxMTo1MTo0OSIsImRiX25hbWUiOiJtdC1wcm9kLVRlbmFudHMiLCJ0ZW5hbnRfaWQiOiIxMTE5ODciLCJodHRwOi8vc2NoZW1hcy5taWNyb3NvZnQuY29tL3dzLzIwMDgvMDYvaWRlbnRpdHkvY2xhaW1zL3JvbGUiOiJBRE1JTklTVFJBVE9SIiwiZXhwIjoyNTM0MDIzMDA4MDAsImlzcyI6IkNsYXJlX0FJIiwiYXVkIjoiQ2xhcmVfQUkifQ.rvdKCKDZwQysYRmLHhaVtRBHUZozX3BKEnFH7EmhMCs',
                'content-type' => 'application/json-patch+json',
              ],
            ]); 
            $respons = $response->getBody()->getContents();  
            $json = json_decode($respons, true); 
            // echo "<pre>";
            // print_r($json);die();
            //return $json['result'];            
          }
          catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            return false;
          }
          // $response = $client->request('POST', 'https://live-mt-server.wati.io/111987/api/v1/'.$url, [
          //   'body' => $param,
          //   'headers' => [
          //     'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiJjZDJjMzc5MS1mMTAwLTRhOGEtOGUwMi1jNGQ5MmE0NGU2YTUiLCJ1bmlxdWVfbmFtZSI6InNuZ2NlbnRyYWxzY2hvb2xAZ21haWwuY29tIiwibmFtZWlkIjoic25nY2VudHJhbHNjaG9vbEBnbWFpbC5jb20iLCJlbWFpbCI6InNuZ2NlbnRyYWxzY2hvb2xAZ21haWwuY29tIiwiYXV0aF90aW1lIjoiMDYvMTIvMjAyNCAwNTo0NjozMSIsImRiX25hbWUiOiJtdC1wcm9kLVRlbmFudHMiLCJ0ZW5hbnRfaWQiOiIxMTE5ODciLCJodHRwOi8vc2NoZW1hcy5taWNyb3NvZnQuY29tL3dzLzIwMDgvMDYvaWRlbnRpdHkvY2xhaW1zL3JvbGUiOiJBRE1JTklTVFJBVE9SIiwiZXhwIjoyNTM0MDIzMDA4MDAsImlzcyI6IkNsYXJlX0FJIiwiYXVkIjoiQ2xhcmVfQUkifQ.4LwJGFe1PEV38uCc22gfvtWdTNxOi58j9xXvZ4uE-rY',
          //     'content-type' => 'application/json-patch+json',
          //   ],
          // ]);          
              
            
        }
        else
        {
          return false;
        }
      }
      else
      {
        return false;
      }
    }
    function callAPI($method, $url, $header, $data){
          if(empty($header))
          {
              $header = array(
                  'Content-Type: application/json',
              );
          }
      $curl = curl_init();
      switch ($method){
        case "POST":
          curl_setopt($curl, CURLOPT_POST, 1);
          if ($data)
          curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
          break;
        case "PUT":
          curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
          if ($data)
          curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
          break;
        default:
          if ($data)
          $url = sprintf("%s?%s", $url, http_build_query($data));
      }
      // OPTIONS:
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      // EXECUTE:
      $result = curl_exec($curl);
      if(!$result){die("Connection Failure");}
      curl_close($curl);
      return $result;
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
        { return true;}
        else
        { return false;}
    }
    public function send_receipt_to_class_teacher($data)
    {
        $params = '{
            "template_name": "fees_paid_status",
            "broadcast_name": "fees_paid_status",
            "parameters": [
                {
                    "name": "name",
                    "value": "'.$data['name'].'"
                  },
                  {
                    "name": "class",
                    "value": "'.$data['class'].'"
                  },
                  {
                    "name": "amount",
                    "value": "'.$data['amount'].'"
                  },
                  {
                    "name": "rec_no",
                    "value": "'.$data['rec_no'].'"
                  },
                  {
                    "name": "rec_date",
                    "value": "'.$data['rec_date'].'"
                  }
            ]
          }';

        $url = 'sendTemplateMessage?whatsappNumber='.$data['mobno'];
        if($this->whatsapp_msg($url,$params))
        { return true; }
        else
        { return false; }
    }   
    public function send_receipt_cancel_to_class_teacher($data)
    {
        $params = '{
            "template_name": "fees_cancel_status",
            "broadcast_name": "fees_cancel_status",
            "parameters": [
                {
                    "name": "name",
                    "value": "'.$data['name'].'"
                  },
                  {
                    "name": "class",
                    "value": "'.$data['class'].'"
                  },
                  {
                    "name": "amount",
                    "value": "'.$data['amount'].'"
                  },
                  {
                    "name": "rec_no",
                    "value": "'.$data['rec_no'].'"
                  },
                  {
                    "name": "cancel_date",
                    "value": "'.$data['cancel_date'].'"
                  }
            ]
          }';
        $url = 'sendTemplateMessage?whatsappNumber='.$data['mobno'];
        if($this->whatsapp_msg($url,$params))
        { return true; }
        else
        { return false; }
    }    

    public function send_receipt_to_parent($data)
    {
        $params = '{
            "template_name": "receipt_to_parent",
            "broadcast_name": "receipt_to_parent",
            "parameters": [
                {
                    "name": "name",
                    "value": "'.$data['name'].'"
                  },
                  {
                    "name": "class",
                    "value": "'.$data['class'].'"
                  },
                  {
                    "name": "amount",
                    "value": "'.$data['amount'].'"
                  },
                  {
                    "name": "rec_no",
                    "value": "'.$data['rec_no'].'"
                  },
                  {
                    "name": "rec_date",
                    "value": "'.$data['rec_date'].'"
                  }
            ]
          }';
        $url = 'sendTemplateMessage?whatsappNumber=91'.$data['mobno'];
        if($this->whatsapp_msg($url,$params))
        { return true; }
        else
        { return false; }
    }    
    public function send_leave_apply_msg($data)
    {
        $params = '{
            "template_name": "leave_apply",
            "broadcast_name": "leave_apply",
            "parameters": [
                  {
                    "name": "name",
                    "value": "'.$data['name'].'"
                  },
                  {
                    "name": "from_date",
                    "value": "'.$data['from_date'].'"
                  },
                  {
                    "name": "to_date",
                    "value": "'.$data['to_date'].'"
                  }
            ]
          }';

        $url = 'sendTemplateMessage?whatsappNumber='.$data['mobno'];
        if($this->whatsapp_msg($url,$params))
        { return true; }
        else
        { return false; }
    } 
    public function send_leave_approve($data)
    {
        $params = '{
            "template_name": "leave_result",
            "broadcast_name": "leave_result",
            "parameters": [
                  {
                    "name": "name",
                    "value": "'.$data['name'].'"
                  },
                  {
                    "name": "start_date",
                    "value": "'.$data['from_date'].'"
                  },
                  {
                    "name": "end_date",
                    "value": "'.$data['to_date'].'"
                  }
            ]
          }';

        $url = 'sendTemplateMessage?whatsappNumber='.$data['mobno'];
        if($this->whatsapp_msg($url,$params))
        { return true; }
        else
        { return false; }
    }    
    public function student_fees_dues_weekly($data)
    {
        $params = '{
            "template_name": "reminder_fee_dues_weekly",
            "broadcast_name": "reminder_fee_dues_weekly",
            "parameters": [
                  {
                    "name": "name",
                    "value": "'.$data['name'].'"
                  },
                  {
                    "name": "class",
                    "value": "'.$data['class'].'"
                  },        
                  {
                    "name": "division",
                    "value": "'.$data['division'].'"
                  },                              
                  {
                    "name": "rollno",
                    "value": "'.$data['rollno'].'"
                  },                    
                  {
                    "name": "amount",
                    "value": "'.$data['amount'].'"
                  },
                  {
                    "name": "to_date",
                    "value": "'.$data['to_date'].'"
                  }
            ]
          }';

        $url = 'sendTemplateMessage?whatsappNumber='.$data['mobno'];
        if($this->whatsapp_msg($url,$params))
        { return true; }
        else
        { return false; }
    }       
    public function send($name,$number)
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
        $url = 'sendTemplateMessage?whatsappNumber='.$number;
        if($this->whatsapp_msg($url,$params))
        { return true;}
        else
        { return false;}
    }
}
