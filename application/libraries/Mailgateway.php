<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Mailgateway {

    private $_CI;
    public $sch_setting;

    public function __construct() {
        $this->_CI = &get_instance();
        $this->_CI->load->model('setting_model');
        $this->_CI->load->model('studentfeemaster_model');
        $this->_CI->load->model('student_model');
        $this->_CI->load->model('teacher_model');
        $this->_CI->load->model('librarian_model');
        $this->_CI->load->model('accountant_model');
        $this->_CI->load->library('mailer');
        $this->_CI->mailer;
        $this->sch_setting = $this->_CI->setting_model->getSetting();
    }

    public function sentMail($sender_details, $template, $subject) {
        $msg = $this->getContent($sender_details, $template);

        $send_to = $sender_details->guardian_email;
        if (!empty($this->_CI->mail_config) && $send_to != "") {

            $this->_CI->mailer->send_mail($send_to, $subject, $msg);
        }
    }

    public function sentRegisterMail($id, $send_to, $template, $subject) {

        if (!empty($this->_CI->mail_config) && $send_to != "") {

            $msg = $this->getStudentRegistrationContent($id, $template);

            $this->_CI->mailer->send_mail($send_to, $subject, $msg);
        }
    }

    public function sendLoginCredential($chk_mail_sms, $sender_details, $template, $subject) {
        $msg = $this->getLoginCredentialContent($sender_details['credential_for'], $sender_details, $template);
        
        $send_to = $sender_details['email'];
        if (!empty($this->_CI->mail_config) && $send_to != "") {
            // $this->_CI->mailer->send_mail($send_to, $subject, $msg);
            $this->_CI->mailer->send_mail($send_to, $subject, $msg);
        }
    }

    public function sentAddFeeMail($detail, $template, $subject) {
        $send_to = $detail->email;
        $msg = $this->getAddFeeContent($detail, $template);
        if (!empty($this->_CI->mail_config) && $send_to != "") {
            $this->_CI->mailer->send_mail($send_to, $subject, $msg);
        }
    }

    public function sentExamResultMail($detail, $template, $subject) {

        $msg = $this->getStudentResultContent($detail, $template);
        $send_to = $detail['guardian_email'];
        if (!empty($this->_CI->mail_config) && $send_to != "") {            
            $this->_CI->mailer->send_mail($send_to, $subject, $msg);
        }
    }

    public function sentExamResultMailStudent($detail, $template, $subject) {

        $msg = $this->getStudentResultContent($detail, $template);
        $send_to = $detail['email'];
        if (!empty($this->_CI->mail_config) && $send_to != "") {
            $this->_CI->mailer->send_mail($send_to, $subject, $msg);
        }
    }

    public function sentHomeworkStudentMail($detail, $template, $subject) {

        if (!empty($this->_CI->mail_config)) {
            foreach ($detail as $student_key => $student_value) {
                $send_to = $student_key;
                if ($send_to != "") {
                    $msg = $this->getHomeworkStudentContent($detail[$student_key], $template);
                    $this->_CI->mailer->send_mail($send_to, $subject, $msg);
                }
            }
        }
    }

     public function sentOnlineexamStudentMail($detail, $template, $subject) {

        if (!empty($this->_CI->mail_config)) {
            foreach ($detail as $student_key => $student_value) {
                $send_to = $student_key;
                if ($send_to != "") {
                    $msg = $this->getOnlineexamStudentContent($detail[$student_key], $template);
                    $this->_CI->mailer->send_mail($send_to, $subject, $msg);
                }
            }
        }
    }

    public function sentOnlineadmissionStudentMail($detail, $template, $subject) {

        if (!empty($this->_CI->mail_config)) {
            
                $send_to = $detail['email'];
                if ($send_to != "") {
                    $msg = $this->getOnlineadmissionStudentContent($detail, $template);
                    $this->_CI->mailer->send_mail($send_to, $subject, $msg);
                }
            
        }
    }

    public function getOnlineadmissionStudentContent($student_detail, $template) {

        foreach ($student_detail as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
       
        return $template;
    }
    public function sentOnlineadmissionFeesMail($detail, $template, $subject) {

        if (!empty($this->_CI->mail_config)) {
            
                $send_to = $detail['email'];
                if ($send_to != "") {
                    $msg = $this->getOnlineadmissionFeesContent($detail, $template);
                    $this->_CI->mailer->send_mail($send_to, $subject, $msg);
                }
            
        }
    }

    public function getOnlineadmissionFeesContent($student_detail, $template) {

        foreach ($student_detail as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
       
        return $template;
    }

    public function sentOnlineClassStudentMail($detail, $template) {

        if (!empty($this->_CI->mail_config)) {
            foreach ($detail as $student_key => $student_value) {
                $send_to = $student_key;
                if ($send_to != "") {
                    $msg = $this->getOnlineClassStudentContent($detail[$student_key], $template);

                    $subject = "Online Class";
                    $this->_CI->mailer->send_mail($send_to, $subject, $msg);
                }
            }
        }
    }

    public function sentOnlineMeetingStaffMail($detail, $template) {

        if (!empty($this->_CI->mail_config)) {
            foreach ($detail as $staff_key => $staff_value) {
                $send_to = $staff_key;
                if ($send_to != "") {
                    $msg = $this->getOnlineMeetingStaffContent($detail[$staff_key], $template);

                    $subject = "Online Meeting";
                    $this->_CI->mailer->send_mail($send_to, $subject, $msg);
                }
            }
        }
    }

    public function sentAbsentStudentMail($detail, $template, $subject) {

        $send_to = $detail['guardian_email'];
        $msg = $this->getAbsentStudentContent($detail, $template);

        if (!empty($this->_CI->mail_config) && $send_to != "") {
            $this->_CI->mailer->send_mail($send_to, $subject, $msg);
        }
    }
 
    public function getAddFeeContent($data, $template) {
        $setting_result         = $this->_CI->setting_model->get();
        $currency_symbol = $this->sch_setting->currency_symbol;
        $school_name = $this->sch_setting->name;
        $invoice_data = json_decode($data->invoice);
        $data->invoice_id = $invoice_data->invoice_id;
        $data->sub_invoice_id = $invoice_data->sub_invoice_id;
        //echo $data->invoice_id ." -".$data->sub_invoice_id;
        $fee = $this->_CI->studentfeemaster_model->getFeeByInvoice($data->invoice_id, $data->sub_invoice_id);
        //print_r($fee);die;
        $a = json_decode($fee->amount_detail);
        $record = $a->{$data->sub_invoice_id};
        $fee_amount = number_format((($record->amount + $record->amount_fine)), 2, '.', ',');
        $data->class = $fee->class;
        $data->section = $fee->section;
        $data->fee_amount = $currency_symbol . $fee_amount;
         $data->student_name = $this->_CI->customlib->getFullName($fee->firstname, $fee->middlename, $fee->lastname,$this->sch_setting->middlename,$this->sch_setting->lastname); 
        foreach ($data as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        $template = str_replace('{{logo}}', base_url('uploads/school_content/admin_small_logo/'.$this->_CI->setting_model->getAdminlogo1()), $template);
        $template = str_replace('{{schname}}', $school_name, $template);
        $template = str_replace('{{schemail}}', $setting_result[0]['email'], $template);
        $template = str_replace('{{schaddress}}', $setting_result[0]['address'], $template);
        $template = str_replace('{{schphone}}', $setting_result[0]['phone'], $template);
        $template = str_replace('{{schsite}}', base_url(), $template);
        $template = str_replace('{{amtinword}}', ucwords(markSheetDigitTwo($fee_amount)), $template);
        $template = str_replace('{{grno}}', $fee->admission_no, $template);
        $template = str_replace('{{receiptdate}}', date('d-m-y',strtotime($fee->created_at)), $template);
        $template = str_replace('{{fine_type}}', $data->type, $template);
        return $template;
    }

    public function getHomeworkStudentContent($student_detail, $template) {

        foreach ($student_detail as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        return $template;
    }
     public function getOnlineexamStudentContent($student_detail, $template) {

        foreach ($student_detail as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        return $template;
    }

    public function getOnlineClassStudentContent($student_detail, $template) {

        foreach ($student_detail as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        return $template;
    }

    public function getOnlineMeetingStaffContent($student_detail, $template) {

        foreach ($student_detail as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        return $template;
    }

    public function getAbsentStudentContent($student_detail, $template) {

        $session_name = $this->_CI->setting_model->getCurrentSessionName();
        $student_detail['current_session_name'] = $session_name;
        foreach ($student_detail as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }

        return $template;
    }

    public function getStudentRegistrationContent($id, $template) {

        $session_name = $this->_CI->setting_model->getCurrentSessionName();
        $student = $this->_CI->student_model->getstudent($id);
        $student['current_session_name'] = $session_name;
        $student['student_name'] = $this->_CI->customlib->getFullName($student['firstname'],$student['middlename'],$student['lastname'],$this->sch_setting->middlename,$this->sch_setting->lastname); 
        foreach ($student as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }

        return $template;
    }

    public function getLoginCredentialContent($credential_for, $sender_details, $template) {

        $setting_result         = $this->_CI->setting_model->get();
        $school_name            = $this->sch_setting->name;

        if ($credential_for == "student") {
            $student = $this->_CI->student_model->getstudent($sender_details['id']);
            $sender_details['url'] = site_url('site/userlogin');
            $sender_details['display_name'] = $this->_CI->customlib->getFullName($student['firstname'],$student['middlename'],$student['lastname'],$this->sch_setting->middlename,$this->sch_setting->lastname); 
        } elseif ($credential_for == "parent") {
            $parent = $this->_CI->student_model->getstudent($sender_details['id']);
            $sender_details['url'] = site_url('site/userlogin');
            $sender_details['display_name'] = $parent['guardian_name'];
        } elseif ($credential_for == "staff") {
            $staff = $this->_CI->staff_model->get($sender_details['id']);
            $sender_details['url'] = site_url('site/login');
            $sender_details['display_name'] = $staff['name'];
        }

        foreach ($sender_details as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        $logo = $this->_CI->setting_model->getAdminlogo1();
        $template = str_replace('{{logo}}', base_url('uploads/school_content/admin_small_logo/'.$logo), $template);
        $template = str_replace('{{schname}}',$school_name , $template);

        return $template;
    }

    public function getStudentResultContent($student_result_detail, $template) {
        foreach ($student_result_detail as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        return $template;
    }

    public function getContent($sender_details, $template) {
        foreach ($sender_details as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        return $template;
    }

    public function sentMailToAlumni($sender_details) {
        $send_to = $sender_details['email'];
        $subject = $sender_details['subject'];
        $msg = "Event From " . $sender_details['from_date'] . " To " . $sender_details['to_date'] . "<br><br>" .
                $sender_details['body'];

        if ($send_to != "") {
            $this->_CI->mailer->send_mail($send_to, $subject, $msg);
        }
    }

}
