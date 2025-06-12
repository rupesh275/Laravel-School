<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Examresult extends Admin_Controller
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

    public function printCard()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('admitcard_template', $this->lang->line('template'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('post_exam_id', $this->lang->line('exam'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('post_exam_group_id', $this->lang->line('exam') . " " . $this->lang->line('group'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('exam_group_class_batch_exam_student_id[]', $this->lang->line('students'), 'required|trim|xss_clean');
        $data = array();

        if ($this->form_validation->run() == false) {
            $data = array(
                'admitcard_template' => form_error('admitcard_template'),
                'post_exam_id' => form_error('post_exam_id'),
                'post_exam_group_id' => form_error('post_exam_group_id'),
                'exam_group_class_batch_exam_student_id' => form_error('exam_group_class_batch_exam_student_id'),
            );
            $array = array('status' => 0, 'error' => $data);
            echo json_encode($array);
        } else {
            $post_exam_id = $this->input->post('post_exam_id');
            $post_exam_group_id = $this->input->post('post_exam_group_id');
            $students_array = $this->input->post('exam_group_class_batch_exam_student_id');
            $exam = $this->examgroup_model->getExamByID($post_exam_id);
            $data['exam'] = $exam;
            $exam_grades = $this->grade_model->getByExamType($exam->exam_group_type);
            $data['exam_grades'] = $exam_grades;
            $data['admitcard'] = $this->admitcard_model->get($this->input->post('admitcard_template'));
            $data['exam_subjects'] = $this->batchsubject_model->getExamSubjects($post_exam_id);
            $data['student_details'] = $this->examstudent_model->getStudentsAdmitCardByExamAndStudentID($students_array, $post_exam_id);
            $data['sch_setting'] = $this->sch_setting_detail;
            $student_admit_cards = $this->load->view('admin/admitcard/_printadmitcard', $data, true);
            $array = array('status' => '1', 'error' => '', 'page' => $student_admit_cards);
            echo json_encode($array);
        }
    }

    public function admitcard()
    {
        if (!$this->rbac->hasPrivilege('print_admit_card', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'Examinations/examresult/admitcard');
        $examgroup_result = $this->examgroup_model->get();
        $data['examgrouplist'] = $examgroup_result;

        $admitcard_result = $this->admitcard_model->get();
        $data['admitcardlist'] = $admitcard_result;
        $class = $this->class_model->get();
        $data['title'] = 'Add Batch';
        $data['title_list'] = 'Recent Batch';
        $data['examType'] = $this->exam_type;
        $data['classlist'] = $class;
        $session = $this->session_model->get();
        $data['sessionlist'] = $session;
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('session_id', $this->lang->line('student'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('exam_group_id', $this->lang->line('exam') . " " . $this->lang->line('group'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('exam_id', $this->lang->line('exam'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('admitcard', $this->lang->line('admit') . " " . $this->lang->line('card') . " " . $this->lang->line('template'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
        } else {
            $exam_group_id = $this->input->post('exam_group_id');
            $exam_id = $this->input->post('exam_id');
            $session_id = $this->input->post('session_id');
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $admitcard_template = $this->input->post('admitcard');
            $data['admitcard_template'] = $admitcard_template;

            $data['studentList'] = $this->examgroupstudent_model->searchExamStudents($exam_group_id, $exam_id, $class_id, $section_id, $session_id);

            $data['examList'] = $this->examgroup_model->getExamByExamGroup($exam_group_id, true);

            $data['exam_id'] = $exam_id;
            $data['exam_group_id'] = $exam_group_id;
        }
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/examresult/admitcard', $data);
        $this->load->view('layout/footer', $data);
    }

    public function marksheet()
    {
        if (!$this->rbac->hasPrivilege('print_marksheet', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'Examinations/examresult/marksheet');
        $examgroup_result = $this->examgroup_model->get();
        $data['examgrouplist'] = $examgroup_result;

        $marksheet_result = $this->marksheet_model->get();
        $data['marksheetlist'] = $marksheet_result;

        $class = $this->class_model->get();
        $data['title'] = 'Add Batch';
        $data['title_list'] = 'Recent Batch';
        $data['examType'] = $this->exam_type;
        $data['classlist'] = $class;
        $session = $this->session_model->get();
        $data['sessionlist'] = $session;
        $this->form_validation->set_rules('marksheet', $this->lang->line('marksheet'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('session_id', $this->lang->line('student'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('exam_group_id', $this->lang->line('exam') . " " . $this->lang->line('group'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('exam_id', $this->lang->line('exam'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
        } else {
            $exam_group_id = $this->input->post('exam_group_id');
            $exam_id = $this->input->post('exam_id');
            $session_id = $this->input->post('session_id');
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');

            $marksheet_template = $this->input->post('marksheet');
            $data['marksheet_template'] = $marksheet_template;

            $data['studentList'] = $this->examgroupstudent_model->searchExamStudents($exam_group_id, $exam_id, $class_id, $section_id, $session_id);

            $data['examList'] = $this->examgroup_model->getExamByExamGroup($exam_group_id, true);

            $data['exam_id'] = $exam_id;
            $data['exam_group_id'] = $exam_group_id;
        }
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/examresult/marksheet', $data);
        $this->load->view('layout/footer', $data);
    }

    public function printmarksheet()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('post_exam_id', $this->lang->line('exam'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('post_exam_group_id', $this->lang->line('exam') . " " . $this->lang->line('group'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('exam_group_class_batch_exam_student_id[]', $this->lang->line('students'), 'required|trim|xss_clean');
        $data = array();

        if ($this->form_validation->run() == false) {
            $data = array(
                'post_exam_id' => form_error('post_exam_id'),
                'post_exam_group_id' => form_error('post_exam_group_id'),
                'exam_group_class_batch_exam_student_id' => form_error('exam_group_class_batch_exam_student_id'),
            );
            $array = array('status' => 0, 'error' => $data);
            echo json_encode($array);
        } else {
            $data['template'] = $this->marksheet_model->get($this->input->post('marksheet_template'));
            $post_exam_id = $this->input->post('post_exam_id');
            $post_exam_group_id = $this->input->post('post_exam_group_id');
            $students_array = $this->input->post('exam_group_class_batch_exam_student_id');
            $exam = $this->examgroup_model->getExamByID($post_exam_id);
            $data['exam'] = $exam;

            $exam_grades = $this->grade_model->getByExamType($exam->exam_group_type);
            $data['exam_grades'] = $exam_grades;
            $data['marksheet'] = $this->examresult_model->getExamResults($post_exam_id, $post_exam_group_id, $students_array);
            $data['sch_setting'] = $this->sch_setting_detail;
            $student_exam_page = $this->load->view('admin/examresult/_printmarksheet', $data, true);
            $array = array('status' => '1', 'error' => '', 'page' => $student_exam_page);
            echo json_encode($array);
        }
    }
    /*  public function finalresultreport() {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('exam_group_class_batch_exam_student_id[]', $this->lang->line('students'), 'required|trim|xss_clean');
        $data = array();

        if ($this->form_validation->run() == false) {
            $data = array(
                'exam_group_class_batch_exam_student_id' => form_error('exam_group_class_batch_exam_student_id'),
            );
            $array = array('status' => 0, 'error' => $data);
            echo json_encode($array);
        } else {
            $students_array = $this->input->post('exam_group_class_batch_exam_student_id');
            // print_r($students_array);die;

            $examids = $this->examgroup_model->getstudentexamid($students_array);
            $examstudentIds = array_column($examids,'id');

            $resultmarks = $this->examgroup_model->getresultmarks($examstudentIds);
            $data['resultmarks'] = $resultmarks;            

            // $exam_grades = $this->grade_model->getByExamType($exam->exam_group_type);
            // $data['exam_grades'] = $exam_grades;
            // $data['marksheet'] = $this->examresult_model->getExamResults($post_exam_id, $post_exam_group_id, $students_array);
            // $data['sch_setting'] = $this->sch_setting_detail;
            $this->load->view('admin/examresult/finalResult', $data); 
            // $array = array('status' => '1', 'error' => '', 'page' => $student_exam_page);
            // echo json_encode($array);
        }
    } */

    public function index()
    {
        if (!$this->rbac->hasPrivilege('exam_result', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'Examinations/Examresult');
        $examgroup_result = $this->examgroup_model->get();
        $data['examgrouplist'] = $examgroup_result;

        $marksheet_result = $this->marksheet_model->get();
        $data['marksheetlist'] = $marksheet_result;

        $class = $this->class_model->get();
        $data['title'] = 'Add Batch';
        $data['title_list'] = 'Recent Batch';
        $data['examType'] = $this->exam_type;
        $data['classlist'] = $class;
        $session = $this->session_model->get();
        $data['sessionlist'] = $session;
        $data['current_session'] = $this->sch_current_session;
        // print_r($data['current_session']);die;
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
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            // print_r($_POST);
            $marksheet_template = $this->input->post('marksheet');
            $data['marksheet_template'] = $marksheet_template;
            $exam_details = $this->examgroup_model->getExamByID($exam_id);

            $studentList = $this->examgroupstudent_model->searchExamStudents($exam_group_id, $exam_id, $class_id, $section_id, $session_id);

            $exam_subjects = $this->batchsubject_model->getExamSubjects($exam_id);
            $data['subjectList'] = $exam_subjects;

            if (!empty($studentList)) {
                foreach ($studentList as $student_key => $student_value) {
                    $studentList[$student_key]->subject_results = $this->examresult_model->getStudentResultByExam($exam_id, $student_value->exam_group_class_batch_exam_student_id);
                }
            }


            $data['studentList'] = $studentList;

            $exam_grades = $this->grade_model->getByExamType($exam_details->exam_group_type);
            $data['exam_grades'] = $exam_grades;
            $data['exam_details'] = $exam_details;
            $data['exam_id'] = $exam_id;
            $data['exam_group_id'] = $exam_group_id;
        }
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/examresult/index', $data);
        $this->load->view('layout/footer', $data);
    }

    public function getStudentByClassBatch()
    {
        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        $session_id = $this->input->post('session_id');
        $data['studentList'] = $this->examgroupstudent_model->searchStudentByClassSectionSession($class_id, $section_id, $session_id);
        echo json_encode($data);
    }

    public function getExamGroupByStudent()
    {
        $student_id = $this->input->post('student_id');

        $data['examgrouplist'] = $this->examgroup_model->getExamGroupByStudent($student_id);
        echo json_encode($data);
    }

    public function studentresult()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('exam_group_id', 'exam_group_id', 'required|trim|xss_clean');
        $this->form_validation->set_rules('student_id', 'student_id', 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'exam_group_id' => form_error('exam_group_id'),
                'student_id' => form_error('student_id'),
            );
            $array = array('status' => 0, 'error' => $data);
            echo json_encode($array);
        } else {

            $student_id = $this->input->post('student_id');
            $exam_group_id = $this->input->post('exam_group_id');
            $exam_group_exam_id = $this->input->post('exam_id');

            $examresult = array();
            $exam_grades = array();
            if ($exam_group_exam_id != "") {
                $examresult = $this->examgroup_model->getExamResultDetailStudent($exam_group_exam_id, $exam_group_id, $student_id);

                $data['examresult'] = $examresult;
                $exam_grades = $this->grade_model->getByExamType($examresult->exam_type);
                $data['exam_grades'] = $exam_grades;
                $examresult = $this->load->view('admin/examresult/_getExam', $data, true);
            } else {
                $exam_group = $this->examgroup_model->get($exam_group_id);
                $data['exam_group'] = $exam_group;

                $exam_grades = $this->grade_model->getByExamType($exam_group->exam_type);
                $data['exam_grades'] = $exam_grades;

                $exam_result = $this->examgroup_model->getExamGroupExamsResultByStudentID($exam_group_id, $student_id);
                $data['examresult'] = $exam_result;
                $exam_connections = $this->examgroup_model->getExamGroupConnection($exam_group_id);
                $data['exam_connections'] = $exam_connections;
                $examresult = $this->load->view('admin/examresult/_getExamGroupResult', $data, true);
            }

            $data['exam_grades'] = $exam_grades;

            $array = array('status' => '1', 'result' => $examresult, 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }

    public function getStudentCurrentResult()
    {
        $this->form_validation->set_rules('student_session_id', $this->lang->line('student') . " " . $this->lang->line('id'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {

            $msg = array(
                'student_session_id' => form_error('student_session_id'),
            );

            $array = array('status' => 0, 'error' => $msg);
        } else {
            $student_session_id = $this->input->post('student_session_id');
            $data['exam_grades'] = $this->grade_model->get();
            $exam_groups_attempt = $this->examgroup_model->getExamGroupByStudentSession($student_session_id);

            $data['exam_groups_attempt'] = $exam_groups_attempt;
            $examresult = $this->load->view('admin/examresult/_getExamGroupResult', $data, true);
            $array = array('status' => 1, 'error' => '', 'result' => $examresult);
        }
        echo json_encode($array);
    }

    public function generatemarksheet()
    {
        $this->form_validation->set_rules('exam_id', $this->lang->line('exam') . " " . $this->lang->line('id'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('check[]', $this->lang->line('students'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {

            $msg = array(
                'exam_id' => form_error('exam_id'),
                'check' => form_error('check'),
            );

            $array = array('status' => 0, 'error' => $msg);
        } else {
            echo "<pre/>";
            $exam_id = $this->input->post('exam_id');
            $students = $this->input->post('check');
            $exam = $this->examgroup_model->getExamByID($exam_id);
            $exam_id = $exam->id;
            $students_result = array();
            if (!empty($students)) {
                foreach ($students as $student_key => $student_value) {
                    print_r($student_value);
                    exit();

                    $students_result[] = $this->examresult_model->getStudentExamResult($exam_id, $student_value);
                }
            }
            print_r($students_result);
            exit();

            exit();
        }
        echo json_encode($array);
    }
    public function rankreport()
    {
        if (!$this->rbac->hasPrivilege('rank_report', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/examinations');
        $this->session->set_userdata('subsub_menu', 'Reports/examinations/rankreport');
        $examgroup_result = $this->examgroup_model->get();
        $data['examgrouplist'] = $examgroup_result;

        $marksheet_result = $this->marksheet_model->get();
        $data['marksheetlist'] = $marksheet_result;

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
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        if ($this->form_validation->run() == false) {
        } else {
            $exam_group_id = $this->input->post('exam_group_id');
            $exam_id = $this->input->post('exam_id');
            $session_id = $this->input->post('session_id');
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $main_sub = $this->input->post('main_sub');
            $marks_type = $this->input->post('marks_type');
            ///new
            ///end
            $marksheet_template = $this->input->post('marksheet');
            $data['marksheet_template'] = $marksheet_template;
            $exam_details = $this->examgroup_model->getExamByID($exam_id);

            $studentList = $this->examgroupstudent_model->searchExamStudents($exam_group_id, $exam_id, $class_id, $section_id, $session_id);
            $exam_subjects = $this->batchsubject_model->getExamSubjects($exam_id,$main_sub);
            $data['subjectList'] = $exam_subjects;
            //echo "<pre>";print_r($data['subjectList']);die;
            if (!empty($studentList)) {
                foreach ($studentList as $student_key => $student_value) {
                    $studentList[$student_key]->subject_results = $this->examresult_model->getStudentResultByExam($exam_id, $student_value->exam_group_class_batch_exam_student_id);
                    $max_mark=0;
                    $total_mark=0;
                    foreach($studentList[$student_key]->subject_results as $marklist)
                    {
                        $mark = $marklist->get_marks;
                        if($mark>0)
                        {
                            $total_mark = $total_mark + $mark;
                        }
                        $max_mark = $max_mark + $marklist->max_marks;
                    }
                }
            }
            $data['studentList'] = $studentList;
            //$exam_grades = $this->grade_model->getByExamType($exam_details->exam_group_type,"5P");
            $exam_grades = $this->class_model->get_class_grade_table($class_id);
            $data['exam_grades'] = $exam_grades;
            $data['exam_details'] = $exam_details;
            $data['exam_id'] = $exam_id;
            $data['exam_group_id'] = $exam_group_id;
            $data['marks_type']         = $marks_type;
        }
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/examresult/rankreport', $data);
        $this->load->view('layout/footer', $data);
    }
    public function markreport()
    {
        if (!$this->rbac->hasPrivilege('markreport', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/examinations');
        $this->session->set_userdata('subsub_menu', 'Reports/examinations/markreport');

        $examlist = $this->examgroup_model->getexamlist();     
        $data['examlist']   = $examlist;
        $data['class_section_list'] = $this->classsection_model->getClassSectionStudentCount();

        $class               = $this->class_model->get('', $classteacher = 'yes');
        $data['classlist']   = $class;
        $data['class_id']    = $class_id    = $this->input->post('class_id');
        $data['section_id']  = $section_id  = $this->input->post('section_id');
        $data['session_id']  = $this->sch_current_session;
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
        } else {
            $data['section_list'] = $this->section_model->getClassBySection($this->input->post('class_id'));

            $exams = $this->examgroup_model->getexam($class_id, $section_id);
           
            $data['exams'] = $exams;
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/examresult/markreport', $data);
        $this->load->view('layout/footer', $data);
    }

    public function classwisereport()
    {
        if (!$this->rbac->hasPrivilege('classwisereport', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/examinations');
        $this->session->set_userdata('subsub_menu', 'Reports/examinations/classwisereport');

        $examlist = $this->examgroup_model->getexamlist();
        $data['examlist']   = $examlist;
        // $data['class_section_list'] = $this->classsection_model->getClassSectionStudentCount();

        $class               = $this->class_model->get('', $classteacher = 'yes');
        $data['classlist']   = $class;
        $data['class_id']    = $class_id    = $this->input->post('class_id');
        $data['section_id']  = $section_id  = $this->input->post('section_id');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|xss_clean');
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
            $exams = $this->examgroup_model->getexamwithgroup($class_id, $section_id);
            $data['exams'] = $exams;
        }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/examresult/classwisereport', $data);
        $this->load->view('layout/footer', $data);
    }

    public function getSubjectslist()
    {
        $exam_id    =  $data['exam_id']       = $this->input->post('exam_id');
        $class_id   =  $data['class_id']      = $this->input->post('class_id');
        $section_id =  $data['section_id']    = $this->input->post('section_id');

        $dt = $this->classsection_model->getDetailbyClassSection($class_id, $section_id);
        $data['teacherlist'] = $this->teachersubject_model->getDetailByclassAndSection($dt['id']);
        
        $data['subjectlists'] = $this->examgroup_model->getstudentSubjectslist($exam_id);
        

        $student_exam_page       = $this->load->view('admin/examresult/subjectlist', $data, true);
        $data = array('status' => '1', 'error' => '', 'page' => $student_exam_page);
        echo json_encode($data);
    }

    public function classwisegraphreport()
    {
        if (!$this->rbac->hasPrivilege('classwisegraphreport', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/examinations');
        $this->session->set_userdata('subsub_menu', 'Reports/examinations/classwisegraphreport');


        // $data['class_section_list'] = $this->classsection_model->getClassSectionStudentCount();
        $class_section_list = $this->classsection_model->getClassSectionStudentCount();
        $class = [];
        $status = [];
        foreach ($class_section_list as $classRow) {
            // echo "<pre>";
            $exams = $this->examgroupstudent_model->getClassExams($classRow->class_id, $classRow->section_id);
            $exam_array = array_column($exams, 'exam_id');
            if (!empty($exam_array)) {
                $class_status = $this->examgroupstudent_model->getClassExamStatusFull($classRow->class_id, $classRow->section_id, $exam_array);
                $class[] = $classRow->class . ' ' . $classRow->section;
                $status[] = $class_status;
            }
        }
        $data['class'] = "'" . implode("', '", $class) . "'";
        $data['status'] = "'" . implode("', '", $status) . "'";
        // print_r($status); die();

        $class               = $this->class_model->get('', $classteacher = 'yes');
        $data['classlist']   = $class;


        $this->load->view('layout/header', $data);
        $this->load->view('admin/examresult/classwisegraphreport', $data);
        $this->load->view('layout/footer', $data);
    }
    public function subjectwisereport()
    {
        if (!$this->rbac->hasPrivilege('subjectwisereport', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/examinations');
        $this->session->set_userdata('subsub_menu', 'Reports/examinations/subjectwisereport');

        $class               = $this->class_model->get('', $classteacher = 'yes');
        $data['classlist']   = $class;
        $data['class_id']    = $class_id    = $this->input->post('class_id');
        $data['section_id']  = $section_id  = $this->input->post('section_id');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'required|trim|xss_clean');
        if ($this->form_validation->run() == false) {
        } else {
            $data['section_list'] = $this->section_model->getClassBySection($this->input->post('class_id'));
            $data['class_section_list'] = $this->classsection_model->getDetailbyClassSectionrResult($class_id, $section_id);

            $exams = $this->examgroupstudent_model->getClassExamsList($class_id, $section_id);
            // echo "<pre>";
            // print_r($exams);
            // die;
            $data['exams'] = $exams;
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/examresult/subjectwisegraphreport', $data);
        $this->load->view('layout/footer', $data);
    }

    public function teacherReport()
    {
        if (!$this->rbac->hasPrivilege('teacherReport', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/examinations');
        $this->session->set_userdata('subsub_menu', 'Reports/examinations/teacherReport');

        $teacher = $this->staff_model->getStaffbyrole(2);
        $data['teacherlist'] = $teacher;
        $data['current_session'] = $this->sch_current_session;


        $this->load->view('layout/header', $data);
        $this->load->view('admin/examresult/teacherReport', $data);
        $this->load->view('layout/footer', $data);
    }
    public function test()
    {
        $array      = array();
        $exam_array = array(46, 47);
        $mark_result = 71;
        $class_id = 4;
        $section_id = 2;
        $session_id = $this->setting_model->getCurrentSession();
        $studentList = $this->examgroupstudent_model->searchExamStudents("", $mark_result, $class_id, $section_id, $session_id);
        $exam_subjects = $this->batchsubject_model->getExamSubjects($mark_result);
        $exam_details = $this->examgroup_model->getExamByID($mark_result);
        $exam_grades = $this->grade_model->getByExamType($exam_details->exam_group_type);
        foreach ($studentList as $student) {
            $exam_student_1 = $this->examgroupstudent_model->searchExamStudentsByStudentSession($exam_array[0], $class_id, $section_id, $session_id, $student->student_session_id);
            $exam_student_2 = $this->examgroupstudent_model->searchExamStudentsByStudentSession($exam_array[1], $class_id, $section_id, $session_id, $student->student_session_id);
            $exam_mark1 = array();
            $exam_mark2 = array();
            if (!empty($exam_student_1)) {
                $exam_mark1 = $this->examresult_model->getStudentResultByExam($exam_array[0], $exam_student_1[0]->exam_group_class_batch_exam_student_id);
            }
            if (!empty($exam_student_2)) {
                $exam_mark2 = $this->examresult_model->getStudentResultByExam($exam_array[1], $exam_student_2[0]->exam_group_class_batch_exam_student_id);
            }
            // echo "<pre>";
            foreach ($exam_subjects as $subjects) {


                $mark_1 = $this->getsubjectmark($subjects->subject_id, $exam_mark1);
                $mark_2 = $this->getsubjectmark($subjects->subject_id, $exam_mark2);
                $main_subject = $this->subject_model->getSubjectByID($subjects->main_sub);

                $subjectType = $main_subject['SubjectType'];

                $attendance = "";
                $get_marks = 0;
                $final = "";
                $max_marks = 0;
                if ($mark_1['exist'] && $mark_2['exist']) {
                    if ($mark_1['attendance'] == "present" && $mark_2['attendance'] == "present") {
                        $attendance = "present";
                        $get_marks  = $mark_1['get_marks'] + $mark_2['get_marks'];
                        $max_marks  = $mark_1['max_marks'] + $mark_2['max_marks'];
                    } elseif ($mark_1['attendance'] == "present" && $mark_2['attendance'] == "Absent") {
                        $attendance = "present";
                        $get_marks  = $mark_1['get_marks'];
                        $max_marks  = $mark_1['max_marks'];
                    } elseif ($mark_1['attendance'] == "Absent" && $mark_2['attendance'] == "present") {
                        $attendance = "present";
                        $get_marks  = $mark_2['get_marks'];
                        $max_marks  = $mark_2['max_marks'];
                    } else {
                        $attendance = "Absent";
                        $get_marks  = 0;
                        $max_marks = 0;
                    }
                } elseif ($mark_1['exist']) {
                    $attendance = $mark_1['attendance'];
                    $get_marks  = $mark_1['get_marks'];
                    $max_marks  = $mark_1['max_marks'];
                } elseif ($mark_2['exist']) {
                    $attendance = $mark_2['attendance'];
                    $get_marks  = $mark_2['get_marks'];
                    $max_marks  = $mark_2['max_marks'];
                }
                if ($subjectType == "Mark") {
                    $final = $get_marks;
                } elseif ($subjectType == "Grade") {
                    if ($get_marks > 0) {
                        $total_percentage = ($get_marks * 100) / $max_marks;
                    } else {
                        $total_percentage = 0;
                    }
                    $final = $this->get_ExamGrade($exam_grades, $total_percentage);
                }
                $array[] = [
                    'exam_group_class_batch_exam_subject_id' => $subjects->id,
                    'exam_group_class_batch_exam_student_id' => $student->exam_group_class_batch_exam_student_id,
                    'attendence' => $attendance,
                    'get_marks' => $get_marks,
                    'note' => '',
                    'exam_group_class_batch_exams_id' => $mark_result,
                    'subject_id' => $subjects->subject_id,
                    'main_sub' => $subjects->main_sub,
                    'final_mark' => $final,
                ];
            }
        }
        $this->examgroup_model->insertstudentmarks($array);
        echo "Success";
    }
    function get_ExamGrade($exam_grades, $percentage)
    {
        if (!empty($exam_grades)) {
            foreach ($exam_grades as $exam_grade_key => $exam_grade_value) {

                if ($exam_grade_value->mark_from >= $percentage && $exam_grade_value->mark_upto <= $percentage) {
                    return $exam_grade_value->name;
                }
            }
        }

        return "-";
    }
    function getsubjectmark($subject_id, $exam_mark1)
    {
        $entry_exist_1 = 0;
        $get_marks_1 = 0;
        $attendence_1 = "";
        $max_marks = 0;
        foreach ($exam_mark1 as $ex) {
            if ($ex->subject_id == $subject_id) {
                $get_marks_1 = $ex->get_marks;
                $attendence_1 = $ex->attendence;
                $entry_exist_1 = 1;
                $max_marks = $ex->max_marks;
                break;
            }
        }
        $result = array(
            "exist" => $entry_exist_1,
            "get_marks" => $get_marks_1,
            "max_marks" => $max_marks,
            "attendance" => $attendence_1,
        );
        return $result;
    }
    public function secondary_marksheet()
    {
        $this->load->view('admin/examresult/cbse_secondary_marksheet');        
    }

    public function exam_report()
    {
        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'Examinations/exam_report');
        $this->session->set_userdata('subsub_menu', '');
        $this->load->view('layout/header');
        $this->load->view('admin/examresult/exam_report');
        $this->load->view('layout/footer');
    }

    public function yearly_result_report()
    {

        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'Examinations/exam_report');
        $this->session->set_userdata('subsub_menu', 'Examinations/exam_report/yearly_result_report');
        $data['title']           = 'Yearly Result Report';
        // $data['searchlist']      = $this->search_type;
        $data['sch_setting']     = $this->sch_setting_detail;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $searchterm              = '';
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $data['current_session']         = $this->setting_model->getCurrentSessionName();
        $session = $this->session_model->get();
        $data['sessionlist'] = $session;

        $this->form_validation->set_rules('session_id', $this->lang->line('session'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
        } else {
            $session_id = $this->input->post('session_id');
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $session_name = $this->session_model->get($session_id);
            $data['session_name'] = $session_name;
            $data['resultlist'] = $this->examresult_model->getYearlyReport_session($session_id,$class_id,$section_id);
            // echo "<pre>";
            // print_r($data['resultlist']);die();
        }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/examresult/yearly_result_report', $data);
        $this->load->view('layout/footer', $data);

    }

    public function marksReport()
    {
        if (!$this->rbac->hasPrivilege('marksReport', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/examinations');
        $this->session->set_userdata('subsub_menu', 'Reports/examinations/marksReport');

        $examlist = $this->examgroup_model->getexamlist();
        $data['examlist']   = $examlist;
        $data['first'] = 0;
        $data['third'] = 0;
        $data['fourth']  = 0;
        $data['fifth'] = 0;

        $class               = $this->class_model->get('', $classteacher = 'yes');
        $data['classlist']   = $class;
        $data['class_id']    = $class_id    = $this->input->post('class_id');
        $data['section_id']  = $section_id  = $this->input->post('section_id');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|xss_clean');
        if ($this->form_validation->run() == false) {
        } else {
            $data['resultlist'] = $this->student_model->getStudentByClassSectionAll($class_id,$section_id);
            $exams = $this->examgroup_model->getexam($class_id, $section_id);
            $data['first'] = $this->examresult_model->get_studentsPercentage($class_id,$section_id,0,40);
            $data['third'] = $this->examresult_model->get_studentsPercentage($class_id,$section_id,40,60);
            $data['fourth'] = $this->examresult_model->get_studentsPercentage($class_id,$section_id,60,70);
            $data['fifth'] = $this->examresult_model->get_studentsPercentage($class_id,$section_id,70,100);
            $data['exams'] = $exams;
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/examresult/marksReport', $data);
        $this->load->view('layout/footer', $data);
    }
    public function sectionMarksReport()
    {
        if (!$this->rbac->hasPrivilege('sectionMarksReport', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/examinations');
        $this->session->set_userdata('subsub_menu', 'Reports/examinations/sectionMarksReport');

        $data['sectionlist'] = $this->examgroup_model->getsch_section();

        $data['sch_section']  = $sch_section  = $this->input->post('sch_section');
        $this->form_validation->set_rules('sch_section', 'Section', 'required|trim|xss_clean');
        if ($this->form_validation->run() == false) {
        } else {
            $sectionRow = $this->examgroup_model->getsch_section($sch_section);
            $data['sch_section_name'] = $sectionRow['sch_section'];
            $data['classList'] = $classList = $this->class_model->getclassBySchsection($sch_section);
            
            if (!empty($classList)) {
                $first = 0;
                $second  = 0;
                $third  = 0;
                $fourth  = 0;
                foreach ($classList as $classRow) {
                    $first  += $this->examresult_model->get_studentsPercentage($classRow['id'],'',0,40);
                    $second += $this->examresult_model->get_studentsPercentage($classRow['id'],'',40,60);
                    $third += $this->examresult_model->get_studentsPercentage($classRow['id'],'',60,70);
                    $fourth  += $this->examresult_model->get_studentsPercentage($classRow['id'],'',70,100);
                    
                }
                
                // echo "<pre>";
                // print_r ($fourth);die;
                // echo "</pre>";
                
                $data['first'] = $first;
                $data['second'] = $second;
                $data['third'] = $third;
                $data['fourth'] = $fourth;
                
            }

        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/examresult/sectionMarksReport', $data);
        $this->load->view('layout/footer', $data);
    }

    public function subjectWiseMarksReport()
    {
        if (!$this->rbac->hasPrivilege('subjectWiseMarksReport', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/examinations');
        $this->session->set_userdata('subsub_menu', 'Reports/examinations/subjectWiseMarksReport');
        $examgroup_result = $this->examgroup_model->get();
        $data['examgrouplist'] = $examgroup_result;

        $marksheet_result = $this->marksheet_model->get();
        $data['marksheetlist'] = $marksheet_result;

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
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');

            $marksheet_template = $this->input->post('marksheet');
            $data['marksheet_template'] = $marksheet_template;
            $exam_details = $this->examgroup_model->getExamByID($exam_id);

            $studentList = $this->examgroupstudent_model->searchExamStudents($exam_group_id, $exam_id, $class_id, $section_id, $session_id);
            $exam_main_subjects = $this->batchsubject_model->getExamMainSubjectsByid($exam_id);
            $exam_subjects = $this->batchsubject_model->getExamSubjects($exam_id);
            
            // echo "<pre>";
            // print_r ($exam_subjects);die;
            // echo "</pre>";
            // error_reporting(E_ALL);
            // ini_set('display_errors', 1);
            $data['mainsubjectList'] = $exam_main_subjects;
            $data['subjectList'] = $exam_subjects;
            if (!empty($studentList)) {
                foreach ($studentList as $student_key => $student_value) {
                    $studentList[$student_key]->subject_results = $this->examresult_model->getStudentResultByExam($exam_id, $student_value->exam_group_class_batch_exam_student_id);
                }
            }
            $data['studentList'] = $studentList;
            //$exam_grades = $this->grade_model->getByExamType($exam_details->exam_group_type,"5P");
            $exam_grades = $this->class_model->get_class_grade_table($class_id);
            $data['exam_grades'] = $exam_grades;
            $data['exam_details'] = $exam_details;
            $data['exam_id'] = $exam_id;
            $data['exam_group_id'] = $exam_group_id;
        }
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/examresult/subjectWiseMarksReport', $data);
        $this->load->view('layout/footer', $data);
    }
}
