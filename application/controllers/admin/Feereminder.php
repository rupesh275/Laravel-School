<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Feereminder extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function setting()
    {

        if (!$this->rbac->hasPrivilege('fees_reminder', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'feereminder/setting');
        $data          = array();
        $data['title'] = 'Email Config List';

        $feereminderlist         = $this->feereminder_model->get();
        $data['feereminderlist'] = $feereminderlist;

        $this->form_validation->set_rules('email_type', $this->lang->line('email_type'), 'required');
        if ($this->input->server('REQUEST_METHOD') == "POST") {

            $ids          = $this->input->post('ids');
            $update_array = array();
            foreach ($ids as $id_key => $id_value) {
                $array = array(
                    'id'        => $id_value,
                    'is_active' => 0,
                    'day'       => $this->input->post('days' . $id_value),
                );
                $is_active = $this->input->post('isactive_' . $id_value);

                if (isset($is_active)) {
                    $array['is_active'] = $is_active;
                }

                $update_array[] = $array;
            }

            $this->feereminder_model->updatebatch($update_array);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/feereminder/setting');
        }

        $data['title'] = 'Email Config List';
        $this->load->view('layout/header', $data);
        $this->load->view('admin/feereminder/setting', $data);
        $this->load->view('layout/footer', $data);
    }

    public function fees_dues_reminder()
    {
        if (!$this->rbac->hasPrivilege('fees_dues_reminder', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Communicate');
        $this->session->set_userdata('sub_menu', 'feereminder/fees_dues_reminder');
        $data          = array();
        $data['classlist'] = $this->class_model->get();
        $data['sch_setting'] = $this->sch_setting;
        $sch_section_result      = $this->examgroup_model->getsch_section();
        $data['sch_section_result'] = $sch_section_result;
        
        $data['title'] = 'Fees Dues Reminder';
        $this->load->view('layout/header', $data);
        $this->load->view('admin/feereminder/fees_dues_reminder', $data);
        $this->load->view('layout/footer', $data);
    }

    public function send_fees_dues_reminder()
    {
        // ini_set('display_errors', 1);
        $class_section = $this->input->post('class_section');
        $this->form_validation->set_rules('sch_section', 'Section', 'required');
        if ($this->input->server('REQUEST_METHOD') == "POST") {
            if (!empty($class_section)) {

                $class_sectionArr =  $this->classsection_model->getclass_sectionbyclasssection($class_section);
                foreach ($class_sectionArr as $key => $class_section_row) {
                    
                    $studentRow = $this->student_model->getStudentByClassSectionID($class_section_row->class_id, $class_section_row->section_id);

                    foreach ($studentRow as $key => $student_row) {
                        
                        $name = $student_row['firstname'] . " " . $student_row['lastname'];
                        $number = $student_row['guardian_phone'];
                        $student_fees_master = $this->studentfeemaster_model->get_by_student_session_id($student_row['student_session_id']);
                        $first_installment = $student_fees_master[0]; 
                        $second_installment = $student_fees_master[1];
                        $third_installment = $student_fees_master[2];

                        if($first_installment > 0 || $second_installment > 0 || $third_installment > 0){
                            // echo "<pre>";
                            // print_r ($student_fees_master);
                            // echo "</pre>";
                            $total = $first_installment + $second_installment + $third_installment;
                            // $this->wati_model->send($name, $number, $total);
                        // $this->wati_model->send($name, $number);
                        }

                    }
                
                
                }

                $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            }else {

                $this->session->set_flashdata('msg', '<div class="alert alert-danger">' . $this->lang->line('error_message') . '</div>');
            }
            redirect('admin/feereminder/fees_dues_reminder');
        }
        // $name = "Alex Paswan";
        // $number = "8286006099";
        // $this->wati_model->send($name,$number);


    }
    public function test($id,$recid)
    {
        $student_fees_master = $this->studentfeemaster_model->get_student_balance_ason_date($id,$recid);
        echo "<pre>";
        print_r($student_fees_master);die();
    }
}
