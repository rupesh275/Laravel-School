<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Verification extends Admin_Controller
{
    public $exam_type = array();
    public function __construct()
    {
        parent::__construct();
        $this->exam_type = $this->config->item('exam_type');
        $this->attendence_exam = $this->config->item('attendence_exam');
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->sch_current_session = $this->setting_model->getCurrentSession();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('marks_verification', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'Examinations/verification');
        $this->session->set_userdata('subsub_menu', 'Examinations/examinations/verification');
        $examgroup_result = $this->examgroup_model->get();
        $data['examgrouplist'] = $examgroup_result;

        $marksheet_result = $this->marksheet_model->get();
        $data['marksheetlist'] = $marksheet_result;

        $subjectlist = $this->subject_model->getsubject();
        $data['subjects']  = $subjectlist;

        $class = $this->class_model->get();
        $data['title'] = 'Add Batch';
        $data['title_list'] = 'Recent Batch';
        $data['examType'] = $this->exam_type;
        $data['classlist'] = $class;
        $session = $this->session_model->get();
        $data['sessionlist'] = $session;
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('session_id', $this->lang->line('session'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('exam_group_id', $this->lang->line('exam') . " " . $this->lang->line('group'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('exam_id', $this->lang->line('exam'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
        } else {
            $exam_group_id = $this->input->post('exam_group_id');
            $exam_id = $this->input->post('exam_id');
            $session_id = $this->input->post('session_id');
            $data['class_id'] =  $class_id = $this->input->post('class_id');
            $data['section_id'] = $section_id = $this->input->post('section_id');
            $main_sub = $this->input->post('main_sub');

            $userdata = $this->customlib->getUserData();
            $role_id  = $userdata["role_id"];
            $class_teacher = $this->classteacher_model->checkclassteacher($class_id, $section_id, $userdata['id']);

            if (isset($role_id) && $userdata["role_id"] == 2 && $class_teacher == 0) {
                redirect('admin/unauthorized');
            } else {

                $marksheet_template = $this->input->post('marksheet');
                $data['marksheet_template'] = $marksheet_template;
                $exam_details = $this->examgroup_model->getExamByID($exam_id);

                $studentList = $this->examgroupstudent_model->searchExamStudents($exam_group_id, $exam_id, $class_id, $section_id, $session_id, $main_sub);
                $exam_subjects = $this->batchsubject_model->getExamSubjects($exam_id, $main_sub);
                $data['subjectList'] = $exam_subjects;

                if (!empty($studentList)) {
                    foreach ($studentList as $student_key => $student_value) {
                        $studentList[$student_key]->subject_results = $this->examresult_model->getStudentResultByExam($exam_id, $student_value->exam_group_class_batch_exam_student_id);
                        // $examresult = array_column($studentList[$student_key]->subject_results,'exam_group_exam_results_id');
                        // print_r($studentList[$student_key]->subject_results);die;

                    }
                }

                $data['studentList'] = $studentList;
                //echo "<pre>";
                //print_r($studentList); die();

                $exam_grades = $this->grade_model->getByExamType($exam_details->exam_group_type);
                $data['exam_grades'] = $exam_grades;
                $data['main_sub'] = $main_sub;

                $data['exam_details'] = $exam_details;
                $data['exam_id'] = $exam_id;
                $data['exam_group_id'] = $exam_group_id;
            }
        }
        // echo "<pre>";
        // print_r($studentList);
        $userdata = $this->customlib->getUserData();
        $data['userdata'] = $userdata;
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/mark/verification', $data);
        $this->load->view('layout/footer', $data);
    }
    public function update_verified()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);        
        $id = $this->input->post('id');
        $check = $this->input->post('check');
        $exam_id = $this->input->post('exam_id');
        $main_sub = $this->input->post('main_sub');
        $result_ids = $this->input->post('result_ids');

        $no_of_rows = $this->examgroupstudent_model->update_mark_verify($check, $result_ids);
        //$this->examgroupstudent_model->update_mark_verify($id, $check, $exam_id, $main_sub);
        if($no_of_rows>0)
        {$json = ['success' => true];}
        else
        {$json = ['error' => true];}
        
        echo json_encode($json);
    }


    public function updatelock_status()
    {
        $status = $this->input->post('status'); // 1,0
        $result_ids = $this->input->post('result_id');
        $i=0;
        foreach($result_ids as $row){
            $this->examgroupstudent_model->update_mark_verify($status,$row);
            $i++;
        }


        $json = ['success' => true];
        echo json_encode($json);
    }
}
