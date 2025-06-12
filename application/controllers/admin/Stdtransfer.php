<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Stdtransfer extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("classteacher_model");
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('promote_student', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'stdtransfer/index');
        $data['title']           = 'Exam Schedule';
        $class                   = $this->class_model->get('', $classteacher = 'yes');
        $data['classlist']       = $class;
        $userdata                = $this->customlib->getUserData();
        $data['sch_setting']     = $this->sch_setting_detail;
        $feecategory             = $this->feecategory_model->get();
        $data['feecategorylist'] = $feecategory;
        $session_result          = $this->session_model->get();
        $data['sessionlist']     = $session_result;
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_promote_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_promote_id', $this->lang->line('session'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('session_id', $this->lang->line('session'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == true) {
            $class                         = $this->input->post('class_id');
            $section                       = $this->input->post('section_id');
            $session                       = $this->input->post('session_id');
            $class_promote                 = $this->input->post('class_promote_id');
            $section_promote               = $this->input->post('section_promote_id');
            $data['class_post']            = $class;
            $data['section_post']          = $section;
            $data['class_promoted_post']   = $class_promote;
            $data['section_promoted_post'] = $section_promote;
            $data['session_promoted_post'] = $session;

            $resultlist = $this->student_model->searchNonPromotedStudents($class, $section, $session, $class_promote, $section_promote);

            $data['resultlist'] = $resultlist;

        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/stdtransfer/stdtransfer', $data);
        $this->load->view('layout/footer', $data);
    }

    public function promote()
    {
        
        $this->form_validation->set_rules('session_id', $this->lang->line('session'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('class_promote_id', $this->lang->line('class'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('section_promote_id', $this->lang->line('section'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('student_list[]', $this->lang->line('student'), 'required|trim|xss_clean');
        if ($this->form_validation->run() == false) {
           
        } else {
            $student_list = $this->input->post('student_list');

            if (!empty($student_list) && isset($student_list)) {
                $student_session_ids = [];
                $i= 0;
                foreach ($student_list as $key => $value) {
                    $student_id     = $value;
                    $result         = $this->input->post('result_' . $value);
                    $session_status = $this->input->post('next_working_' . $value);
                    $roll_no        = $this->input->post('roll_no_'.$value);
                    if ($result == "pass" && $session_status == "countinue") {
                        $i = 1;
                        $promoted_class   = $this->input->post('class_promote_id');
                        $promoted_section = $this->input->post('section_promote_id');
                        $promoted_session = $this->input->post('session_id');
                        $data_new         = array(
                            'student_id'     => $student_id,
                            'class_id'       => $promoted_class,
                            'section_id'     => $promoted_section,
                            'session_id'     => $promoted_session,
                            'promoted_status' => 'P',
                            'transport_fees' => 0,
                            'fees_discount'  => 0,
                            'roll_no'        => $roll_no,
                        );
                        $student_session_ids[] = $this->student_model->add_student_session($data_new);
                    } elseif ($result == "fail" && $session_status == "countinue") {
                        $promoted_session = $this->input->post('session_id');
                        $class_post       = $this->input->post('class_post');
                        $section_post     = $this->input->post('section_post');
                        $data_new         = array(
                            'student_id'     => $student_id,
                            'class_id'       => $class_post,
                            'section_id'     => $section_post,
                            'session_id'     => $promoted_session,
                            'promoted_status' => 'P',
                            'transport_fees' => 0,
                            'fees_discount'  => 0,
                        );
                        $this->student_model->add_student_session($data_new);
                    } elseif ($session_status == "leave") {

                        $alumni_data = array(
                            'student_id' => $student_id,
                            'is_alumni'  => 1,
                        );
                        $this->student_model->alumni_student_status($alumni_data);
                    }
                }
                if ($i == 1) {
                    $this->add_student_fees($promoted_class,$promoted_section,$promoted_session,$student_session_ids);
                }

            }
        }
        
    }

    public function add_student_fees($promoted_class,$promoted_section,$promoted_session,$student_session_ids)
    {
        
        $data['student_session_ids'] = implode(",",$student_session_ids);

        $data['promoted_session'] = $promoted_session;
        $data['class_id'] = $promoted_class;
        $data['section_id'] = $promoted_section;
        $data['session_id'] = $promoted_session;
        error_reporting( E_ALL );
        ini_set('display_errors', '1');

        $data['fees_array']         = $this->feegrouptype_model->getclassfess($promoted_class,$promoted_session)->result_array();
        // echo "<pre>";print_r($data['fees_array']);

        $session                    = $this->setting_model->getCurrentSession();
        $data['userdata']           = $this->customlib->getUserData();
        $feesdiscount_result = $this->feediscount_model->get();
        $data['feediscountList'] = $feesdiscount_result;

        $category                     = $this->category_model->get();
        $data['categorylist']         = $category;
        $data['sch_setting']  = $this->sch_setting_detail;
        // $student              = $this->student_model->getByStudentSession($student_session_id,$promoted_session);
        
        // $data['student']      = $student;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/stdtransfer/studentclassfeesShow', $data);
        $this->load->view('layout/footer', $data);
    }

    public function feesubmit()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('student_session_id', 'student_session_id', 'trim|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'student_session_id' => form_error('student_session_id'),
            );
            $array = array('status' => 0, 'error' => $data);
            echo json_encode($array);
        } else {
            $current_session    = $this->input->post('session_id');
            $student_session_id = explode(',',$this->input->post('student_session_id'));

            $fee_group_id = $this->input->post('fees_group_id[]');
            $discount_id = $this->input->post('discount_id[]');
            foreach ($student_session_id as $key => $student_session_id) {
                
                if (!empty($discount_id)) {
                    foreach ($discount_id as $key => $discount) {
    
                        $insert_array = array(
                            'session_id' => $current_session,
                            'student_session_id' => $student_session_id,
                            'fees_discount_id' => $discount,
                            'is_active'  => 'Yes',
                        );
    
                        $this->feediscount_model->allotdiscount($insert_array);
                    }
                }

                if (!empty($fee_group_id)) {
                    foreach ($fee_group_id as  $feegroup) {
    
                        $array_fees = array(
                            'student_session_id' => $student_session_id,
                            'fee_session_group_id' => $feegroup,
                            'session_id' => $current_session,
                        );
    
    
                        $inserted_id = $this->studentfeemaster_model->add($array_fees);
                    }
                }
            }

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/stdtransfer');
        }
    }

    public function depromote_student()
    {
        if (!$this->rbac->hasPrivilege('promote_student', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'stdtransfer/depromote_student');
        $data['title']           = 'Exam Schedule';
        $class                   = $this->class_model->get('', $classteacher = 'yes');
        $data['classlist']       = $class;
        $userdata                = $this->customlib->getUserData();
        $data['sch_setting']     = $this->sch_setting_detail;
        $feecategory             = $this->feecategory_model->get();
        $data['feecategorylist'] = $feecategory;
        $session_result          = $this->session_model->get();
        $data['sessionlist']     = $session_result;
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        // $this->form_validation->set_rules('class_promote_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        // $this->form_validation->set_rules('section_promote_id', $this->lang->line('session'), 'trim|required|xss_clean');
        // $this->form_validation->set_rules('session_id', $this->lang->line('session'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == true) {
            $class                         = $this->input->post('class_id');
            $section                       = $this->input->post('section_id');
            $session                       = $this->input->post('session_id');
            $class_promote                 = $this->input->post('class_promote_id');
            $section_promote               = $this->input->post('section_promote_id');
            $data['class_post']            = $class;
            $data['section_post']          = $section;
            $data['class_promoted_post']   = $class_promote;
            $data['section_promoted_post'] = $section_promote;
            $data['session_promoted_post'] = $session;

            // $resultlist = $this->student_model->searchNonPromotedStudents($class, $section, $session, $class_promote, $section_promote);
            $resultlist = $this->student_model->searchByClassSectionWithSession($class, $section);
            $data['resultlist'] = $resultlist;

        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/stdtransfer/depromote_student', $data);
        $this->load->view('layout/footer', $data);
    }

    public function depromote_validation()
    {
        $this->form_validation->set_rules('student_list[]', $this->lang->line('student'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {

        }else {
            $student_list = $this->input->post('student_list');
            
            // echo "<pre>";
            // print_r ($student_list);
            // echo "</pre>";
            

            if (!empty($student_list) && isset($student_list)) {
                // $i= 0;
                foreach ($student_list as $key => $value) {
                   $data = $this->student_model->checkstudent_for_depromote($value);
                    if ($data == 0) {
                        $this->student_model->depromote_student($value);
                    }
                }
            }

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/stdtransfer/depromote_student', 'refresh');
        }
    }



}
