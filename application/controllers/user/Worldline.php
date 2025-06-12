<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Worldline extends Student_Controller
{
    public $pay_method = "";
    public $amount = 0;
    function __construct() {
        parent::__construct();
        $this->setting = $this->setting_model->getSetting();
        $this->load->library('mailsmsconf');
        $this->load->model('onlinestudent_model');
    }
    public function index($hash_code) {
        $this->load->library('worldline/standard/AWLMEAPI');
        $result=$this->onlinestudent_model->get_online_record($hash_code);
        if(!empty($result))
        {
            $id=$result['id'];
            $amnt=$result["amount"];
            $orderid = "sngcs".$id;
            $amnt_ps = $amnt * 100;
            //$merchentid="WL0000000027698";//demo
            $merchentid="WL0000000031311";//live
            $response_url = 'https://sngcentralschool.org/web/site/wl_response';
            
            $obj = new AWLMEAPI();
            $reqMsgDTO = new ReqMsgDTO();
            $reqMsgDTO->setOrderId($orderid);
            $reqMsgDTO->setTrnAmt($amnt_ps); //Paisa Format
            $reqMsgDTO->setTrnCurrency("INR");
            $reqMsgDTO->setMeTransReqType("S");
            
            $reqMsgDTO->setRecurrPeriod("NA");
            $reqMsgDTO->setRecurrDay("");
            $reqMsgDTO->setNoOfRecurring("");

            $reqMsgDTO->setMid($merchentid);
            //$reqMsgDTO->setEnckey("6375b97b954b37f956966977e5753ee6");//demo
            $reqMsgDTO->setEnckey("83bb682439ffd5adb97422e7fb621993"); //live
            $reqMsgDTO->setTrnRemarks('School Fees');
            //Optional Fields
            $reqMsgDTO->setAddField1($id);
            $reqMsgDTO->setAddField2('parent');
            $reqMsgDTO->setAddField3('');
            $reqMsgDTO->setAddField4('');
            $reqMsgDTO->setAddField5('');
            $reqMsgDTO->setAddField6('');
            $reqMsgDTO->setAddField7('');
            $reqMsgDTO->setAddField8('');
            $reqMsgDTO->setResponseUrl($response_url);
            //Step 3: API call to generate the Message
            // $reqMsgDTO = $obj.generateTrnReqMsg(objReqMsgDTO);
            $merchantRequest = "";
            $reqMsgDTO = $obj->generateTrnReqMsg($reqMsgDTO);
            if ($reqMsgDTO->getStatusDesc() == "Success"){
                $merchantRequest = $reqMsgDTO->getReqMsg();
            }
            $data['merchantRequest'] = $merchantRequest;
            $data['mid']=$merchentid;
            $data['amount']=$amnt;
            $data['hash_code']=$hash_code;
            $data['setting']=$this->setting;
            $data['amount'] = $result["amount"];
            $this->load->view('onlineadmission/worldline/index', $data);
        }

    }
  
}
