<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Principal_verify extends Admin_Controller
{

    public $exam_type            = array();
    private $sch_current_session = "";

    public function __construct()
    {
        parent::__construct();
        $this->load->library('encoding_lib');
        $this->load->library('mailsmsconf');
        $this->exam_type           = $this->config->item('exam_type');
        $this->sch_current_session = $this->setting_model->getCurrentSession();
        $this->attendence_exam     = $this->config->item('attendence_exam');
        $this->sch_setting_detail  = $this->setting_model->getSetting();
    }

    public function index()
    {
        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'Examinations/principal_verification');
        $this->session->set_userdata('subsub_menu', 'Examinations/principal_verification');
        $examgroup_result = $this->examgroup_model->get();
        $data['examgrouplist'] = $examgroup_result;

        $subjectlist = $this->subject_model->getsubject();
        $data['subjects']  = $subjectlist;

        $class = $this->class_model->get();
        $data['title'] = 'Add Batch';
        $data['title_list'] = 'Recent Batch';
        $data['examType'] = $this->exam_type;
        $data['classlist'] = $class;
        $session = $this->session_model->get();
        $data['sessionlist'] = $session;
        $data['class_id']    = $class_id    = $this->input->post('class_id');
        $data['section_id']  = $section_id  = $this->input->post('section_id');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|xss_clean');
        if ($this->form_validation->run() == false) {
        } else {
            $data['section_list'] = $this->section_model->getClassBySection($this->input->post('class_id'));

            if (empty($class_id) && empty($section_id)) {
                $data['class_section_list'] = $this->classsection_model->getAllClass();
            } elseif (!empty($section_id)) {

                $data['class_section_list'] = $this->classsection_model->getDetailbyClassSectionrResult($class_id, $section_id);
            } else {
                $data['class_section_list'] = $this->classsection_model->getDetailbyClassSection_id($class_id);
            }


            $exams = $this->examgroup_model->getexam($class_id, $section_id);
            $data['exams'] = $exams;
        }

        $userdata = $this->customlib->getUserData();
        $data['userdata'] = $userdata;
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/mark/principal_verification', $data);
        $this->load->view('layout/footer', $data);
    }

    public function update_exam_result_status()
    {
        $data['exam'] = $exam_id       = $this->input->post('exam_id');
        $data['class_id']    = $class_id    = $this->input->post('class_id');
        $data['section_id']  = $section_id  = $this->input->post('section_id');
        $session_id = $this->setting_model->getCurrentSession();
        $status        = $this->input->post('status');

        $studentsArray = $this->examgroupstudent_model->searchExamStudents($examgroup_id=null,$exam_id,$class_id,$section_id,$session_id);
        if (!empty($studentsArray)) {
            foreach ($studentsArray as  $student) {
                $this->examgroup_model->update_exam_status($exam_id,$student->exam_group_class_batch_exam_student_id,$status);
            }
            
        }
        // echo "<pre>";
        // print_r($studentsArray);
        
        $data = array('status' => '1', 'error' => '','exam_id'=>$exam_id);
        echo json_encode($data);
    }

    public function update_exam_result_admin_status()
    {
        $data['exam'] = $exam_id       = $this->input->post('exam_id');
        $data['class_id']    = $class_id    = $this->input->post('class_id');
        $data['section_id']  = $section_id  = $this->input->post('section_id');
        $session_id = $this->setting_model->getCurrentSession();
        $status        = $this->input->post('status');

        $studentsArray = $this->examgroupstudent_model->searchExamStudents($examgroup_id=null,$exam_id,$class_id,$section_id,$session_id);
        if (!empty($studentsArray)) {
            foreach ($studentsArray as  $student) {
                $this->examgroup_model->update_exam_admin_status($exam_id,$student->exam_group_class_batch_exam_student_id,$status);
            }
            
        }
        // echo "<pre>";
        // print_r($studentsArray);
        
        $data = array('status' => '1', 'error' => '','exam_id'=>$exam_id);
        echo json_encode($data);
    }
}