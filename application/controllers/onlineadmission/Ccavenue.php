<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Ccavenue extends OnlineAdmission_Controller
    {
    
        public $pay_method = "";
        public $amount = 0;
    
        function __construct() {
            parent::__construct();
            $this->pay_method = $this->paymentsetting_model->getActiveMethod();
            $this->setting = $this->setting_model->getSetting();
            $this->amount = $this->setting->online_admission_amount;
            $this->load->library(array('Ccavenue_crypto','mailsmsconf'));
            $this->load->model('onlinestudent_model');
        }
    public function index($hash_code) {
        $result=$this->onlinestudent_model->get_online_record($hash_code);
        $reference = $this->session->userdata('reference');
        $data['setting'] = $this->setting;
        $total = $this->amount;
        $data['amount'] = $result['amount'];
        $data['code'] = $hash_code;
        $this->load->view('onlineadmission/ccavenue/index', $data);
    } 
    public function pay()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->session->set_userdata('payment_amount',$this->amount);
            $hash_code = $this->input->post('code');
            $result=$this->onlinestudent_model->get_online_record($hash_code);
            $student_session_id=$result['student_session_id'];
            $st_session = $this->db->query("select student_id from student_session where id = '$student_session_id' ")->row_array();
            $student_id = $st_session['student_id'];
            $student = $this->db->query("select * from students where id = '$student_id' ")->row_array();

            $amount                  = $result['amount'];
            $details['tid']          = $result['id'];
            $details['merchant_id']  = $this->pay_method->api_secret_key;
            $details['order_id']     = $result['id'];
            $details['amount']       = $result['amount'];
            $details['currency']     = $this->setting->currency;
            $details['redirect_url'] = base_url('onlineadmission/ccavenue/success');
            $details['cancel_url']   = base_url('onlineadmission/ccavenue/cancel');
            $details['language']     = "EN";
            $details['billing_name'] = $student['father_name'];
            $details['billing_address'] = $student['current_address'];
            $details['billing_city'] = 'Mumbai';
            $details['billing_state'] = 'Maharastra';
            $details['billing_zip'] = '410206';
            $details['billing_country'] = 'India';
            $details['billing_tel'] = $student['mother_phone'];
            $details['billing_email'] = $student['email'];
            
            $merchant_data           = "";
            foreach ($details as $key => $value) {
                $merchant_data .= $key . '=' . $value . '&';
            }

            $data['encRequest']  = $this->ccavenue_crypto->encrypt($merchant_data, $this->pay_method->salt);
            $data['access_code'] = $this->pay_method->api_publishable_key;
            $this->load->view('onlineadmission/ccavenue/pay', $data);
        } else {
            redirect(base_url('onlineadmission/checkout'));
        }
    }

    public function success()
    { 
        $status     = array();
        // $rcvdString = "";
        // $total_amount   = $this->amount;
        // $reference  = $this->session->userdata('reference');
        // $online_data = $this->onlinestudent_model->getAdmissionData($reference);
 
        // $apply_date=date("Y-m-d H:i:s");
        // $date         = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($apply_date));

        // if (!empty($total_amount)) {
            $encResponse = $_POST["encResp"];
            $rcvdString  = $this->ccavenue_crypto->decrypt($encResponse, $this->pay_method->salt);
         
            if ($rcvdString !== '') {

                $decryptValues = explode('&', $rcvdString);
                $dataSize      = sizeof($decryptValues);
                for ($i = 0; $i < $dataSize; $i++) {
                    $information             = explode('=', $decryptValues[$i]);
                    $status[$information[0]] = $information[1];
                }
            }
            $order_id= $status['order_id'];
            $trnrefno= $status['tracking_id'];
            $responsecode= $status['response_code'];
            $status_desc = $status['order_status'];
            $card_details = $status['bank_ref_no'];
            $statuscode = $status['response_code'];
            if ($status['order_status'] == "Success"){$trn_status = "passed";}
            else
            {$trn_status = "failed";}
            $array_data = array(
                "trnrefno" => $trnrefno,
                "orderid" => $order_id,
                "responsecode" => $responsecode,
                "status_desc" => $status_desc,
                "card_details" => $card_details,
                "status_code" => $statuscode,
            );
            $this->db->where('id',$order_id);
            $this->db->update('online_transaction',$array_data);
            if (!empty($status)) {
                if ($status['order_status'] == "Success") {
                    $order_id = $status['order_id'];
                    $result=$this->onlinestudent_model->get_online_record_by_id($order_id);
                    // if($result['source']=='parent') {
                    //     $newsess= (array) json_decode($result['session_data']);
                    //     $student_dt= (array)$newsess['student'];
                    //     $lang_array=(array)$student_dt['language'];
                    //     $student_dt['language'] = $lang_array;
                    //     $this->session->set_userdata('student',$student_dt);
                    //     $this->session->set_userdata('current_class',(array)$newsess['current_class']);
                    //     $this->session->set_userdata('top_menu',$newsess['top_menu']);
                    //     redirect('user/user/addfeegrp_submit/'.$result['hash_code']);
                    // }elseif ($result['source']=='counter-main') {
                    //     $newsess= (array) json_decode($result['session_data']);
                    //     $admin_dt= (array)$newsess['admin'];
                    //     $lang_array=(array)$admin_dt['language'];
                    //     $role_array=(array)$admin_dt['roles'];
                    //     $admin_dt['language'] = $lang_array;
                    //     $admin_dt['roles'] = $role_array;
                    //     $this->session->set_userdata('admin',$admin_dt);
                    //     if(isset($newsess['top_menu'])){$this->session->set_userdata('top_menu',$newsess['top_menu']);}
                    //     if(isset($newsess['sub_menu'])){$this->session->set_userdata('sub_menu',$newsess['sub_menu']);}
                    //     redirect('studentfee/addfeegrp_gateway_submit/'.$result['hash_code']);
                    // }elseif ($result['source']=='counter-others') {
                    //     $newsess= (array) json_decode($result['session_data']);
                    //     $admin_dt= (array)$newsess['admin'];
                    //     $lang_array=(array)$admin_dt['language'];
                    //     $role_array=(array)$admin_dt['roles'];
                    //     $admin_dt['language'] = $lang_array;
                    //     $admin_dt['roles'] = $role_array;
                    //     $this->session->set_userdata('admin',$admin_dt);
                    //     if(isset($newsess['top_menu'])){$this->session->set_userdata('top_menu',$newsess['top_menu']);}
                    //     if(isset($newsess['sub_menu'])){$this->session->set_userdata('sub_menu',$newsess['sub_menu']);}
                    //     redirect('studentfee/addfeegrp_gateway_submit/'.$result['hash_code']);   
                    // }elseif ($result['source']=='counter-previous') {
                    //     $newsess= (array) json_decode($result['session_data']);
                    //     $admin_dt= (array)$newsess['admin'];
                    //     $lang_array=(array)$admin_dt['language'];
                    //     $role_array=(array)$admin_dt['roles'];
                    //     $admin_dt['language'] = $lang_array;
                    //     $admin_dt['roles'] = $role_array;
                    //     $this->session->set_userdata('admin',$admin_dt);
                    //     if(isset($newsess['top_menu']))
                    //     {
                    //     $this->session->set_userdata('top_menu',$newsess['top_menu']);
                    //     }
                    //     if(isset($newsess['sub_menu']))
                    //     {
                    //     $this->session->set_userdata('sub_menu',$newsess['sub_menu']);
                    //     }
                    //     redirect('studentfee/addfeegrp_gateway_submit_previous/'.$result['hash_code']);
                    // }
                    if($result['source']=='parent') {
                        $newsess= (array) json_decode($result['session_data']);
                        $student_dt= (array)$newsess['student'];
                        $lang_array=(array)$student_dt['language'];
                        $student_dt['language'] = $lang_array;
                        $this->session->set_userdata('student',$student_dt);
                        $this->session->set_userdata('current_class',(array)$newsess['current_class']);
                        $this->session->set_userdata('top_menu',$newsess['top_menu']);
                        redirect('user/user/addfeegrp_submit/'.$result['hash_code']);
                    }elseif ($result['source']=='counter-main') {
                        $newsess= (array) json_decode($result['session_data']);
                        $admin_dt= (array)$newsess['admin'];
                        $lang_array=(array)$admin_dt['language'];
                        $role_array=(array)$admin_dt['roles'];
                        $admin_dt['language'] = $lang_array;
                        $admin_dt['roles'] = $role_array;
                        $this->session->set_userdata('admin',$admin_dt);
                        if(isset($newsess['top_menu'])){$this->session->set_userdata('top_menu',$newsess['top_menu']);}
                        if(isset($newsess['sub_menu'])){$this->session->set_userdata('sub_menu',$newsess['sub_menu']);}
                        redirect('studentfee/addfeegrp_gateway_submit/'.$result['hash_code']);
                    }elseif ($result['source']=='counter-others') {
                        $newsess= (array) json_decode($result['session_data']);
                        $admin_dt= (array)$newsess['admin'];
                        $lang_array=(array)$admin_dt['language'];
                        $role_array=(array)$admin_dt['roles'];
                        $admin_dt['language'] = $lang_array;
                        $admin_dt['roles'] = $role_array;
                        $this->session->set_userdata('admin',$admin_dt);
                        if(isset($newsess['top_menu'])){$this->session->set_userdata('top_menu',$newsess['top_menu']);}
                        if(isset($newsess['sub_menu'])){$this->session->set_userdata('sub_menu',$newsess['sub_menu']);}
                        redirect('studentfee/addfeegrp_gateway_submit/'.$result['hash_code']);                
                    }elseif ($result['source']=='counter-previous') {
                        $newsess= (array) json_decode($result['session_data']);
                        $admin_dt= (array)$newsess['admin'];
                        $lang_array=(array)$admin_dt['language'];
                        $role_array=(array)$admin_dt['roles'];
                        $admin_dt['language'] = $lang_array;
                        $admin_dt['roles'] = $role_array;
                        $this->session->set_userdata('admin',$admin_dt);
                        if(isset($newsess['top_menu']))
                        {
                        $this->session->set_userdata('top_menu',$newsess['top_menu']);
                        }
                        if(isset($newsess['sub_menu']))
                        {
                        $this->session->set_userdata('sub_menu',$newsess['sub_menu']);
                        }
                        redirect('studentfee/addfeegrp_gateway_submit_previous/'.$result['hash_code']);
                    }elseif ($result['source']=='counter-general') {
                        $newsess= (array) json_decode($result['session_data']);
                        $admin_dt= (array)$newsess['admin'];
                        $lang_array=(array)$admin_dt['language'];
                        $role_array=(array)$admin_dt['roles'];
                        $admin_dt['language'] = $lang_array;
                        $admin_dt['roles'] = $role_array;
                        $this->session->set_userdata('admin',$admin_dt);
                        if(isset($newsess['top_menu']))
                        {
                        $this->session->set_userdata('top_menu',$newsess['top_menu']);
                        }
                        if(isset($newsess['sub_menu']))
                        {
                        $this->session->set_userdata('sub_menu',$newsess['sub_menu']);
                        }
                        redirect('studentfee/addgeneral_gateway_submit/'.$result['hash_code']);
                    }

                } else {
                    $orderid = $status['order_id'];                
                    $result=$this->onlinestudent_model->get_online_record_by_id($orderid);
                    $array_data = array(
                        "trn_status" => "failed",
                    );
                    $this->db->where('id',$order_id);
                    $this->db->update('online_transaction',$array_data);                    
                    $source=$result['source'];
                    redirect('site/payment_failure/'.$source);
                    exit(0);                    

                }

        } else {
	        redirect(base_url("onlineadmission/checkout/paymentfailed/".$online_data->reference_no));
        }
    }

    public function cancel()
    {
        $status     = array();
        $encResponse = $_POST["encResp"];   
        $rcvdString  = $this->ccavenue_crypto->decrypt($encResponse, $this->pay_method->salt);             
        if ($rcvdString !== '') {
            $decryptValues = explode('&', $rcvdString);
            $dataSize      = sizeof($decryptValues);
            for ($i = 0; $i < $dataSize; $i++) {
                $information             = explode('=', $decryptValues[$i]);
                $status[$information[0]] = $information[1];
            }
        }        
        $orderid = $status['order_id'];  
        $array_data = array(
            "trn_status" => "failed",
        );
        $this->db->where('id',$orderid);
        $this->db->update('online_transaction',$array_data);   
        $data['return_url'] = base_url('studentfee');
        $this->load->view('onlineadmission/payment_failed',$data);

        /*
        $source=$result['source'];        
        $reference  = $this->session->userdata('reference');
        $online_data = $this->onlinestudent_model->getAdmissionData($reference);
		redirect(base_url("onlineadmission/checkout/paymentfailed/".$online_data->reference_no));
        */
    }

}
