<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Report_card extends Admin_Controller
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
        if (!$this->rbac->hasPrivilege('print_marksheet', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'Examinations/report_card');
        $examgroup_result = $this->examgroup_model->get();
        $data['examgrouplist'] = $examgroup_result;

        $marksheet_result = $this->marksheet_model->get();
        $data['marksheetlist'] = $marksheet_result;

        $class = $this->class_model->get();
        $data['title'] = 'Add Batch';
        $data['title_list'] = 'Recent Batch';
        $data['examType'] = $this->exam_type;
        $data['classlist'] = $class;
        $data['current_session'] = $this->sch_current_session;
        $session = $this->session_model->get();
        $data['sessionlist'] = $session;
        $this->form_validation->set_rules('marksheet', $this->lang->line('marksheet'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('session_id', $this->lang->line('student'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
        } else {
            $session_id =  $data['session_id'] = $this->input->post('session_id');
            $class_id = $data['class_id'] = $this->input->post('class_id');
            $section_id = $data['section_id'] = $this->input->post('section_id');
            $marksheet_template = $this->input->post('marksheet');
            $partial_marksheet = $data['partial_marksheet'] = $this->input->post('partial_marksheet');
            $data['studentList'] = $this->examgroupstudent_model->searchStudents($class_id, $section_id, $session_id);
            $data['marksheet_template'] = $marksheet_template;
            //$data['examList'] = $this->examgroup_model->getExamByExamGroup($exam_group_id, true);
        }
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/examresult/report_card', $data);
        $this->load->view('layout/footer', $data);
    }
    public function finalresult()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('student_session_id[]', $this->lang->line('students'), 'required|trim|xss_clean');
        $data = array();
        if ($this->form_validation->run() == false) {
            $data = array(
                'student_session_id' => form_error('student_session_id'),
            );
            $array = array('status' => 0, 'error' => $data);
            echo json_encode($array);
        } else {
            $data['template'] = $this->marksheet_model->get($this->input->post('marksheet_template'));
            $student_session_ids = $this->input->post('student_session_id');
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $data['marksheet_partial'] = $marksheet_partial = $this->input->post('marksheet_partial');
            $ay_year = $this->session_model->get($this->setting_model->getCurrentSession());
            
            $pattern_result = $this->examgroup_model->get_class_exam_pattern($class_id);
            if ($pattern_result == "") {
                $data = array(
                    'msg' => "Class Exam Pattern Not Set",
                );
                $array = array('status' => 0, 'error' => $data);
                echo json_encode($array);
                return 1;
            }
            $exam_pattern = $pattern_result['name'];
            $data['ay_year']=$ay_year['session'];
            if ($exam_pattern == "Evaluation") {
                $students_array = $this->student_model->getByStudentSessionSmall($student_session_ids);
                $data['examList'] = $this->examgroupstudent_model->studentExamsResultOnly($student_session_ids[0]);
                
                $data['exam_subjects'] = $this->batchsubject_model->getExamSubjects($data['examList'][0]->exam_group_class_batch_exam_id);
                $data['main_subjects'] = $this->batchsubject_model->getExamMainSubjects($data['examList'][0]->exam_group_class_batch_exam_id);
                $main_subject_count =  sizeof($data['main_subjects']);
                $data['new_session'] = $this->examgroup_model->get_session_start_date($class_id);
                $data['result_date'] = $this->examgroup_model->get_result_date($class_id);

                $data['students_array'] = $students_array;
                $data['nextclass'] = $this->class_model->nextClass($students_array[0]['class_id'])['class'];
                $data['class_teacher'] = $this->class_model->getClassTeacherOfClass($class_id, $section_id);
                $data['principal'] = $this->staff_model->getPrincipal(1);
                $data['sch_setting'] = $this->sch_setting_detail;
                $data['working_days'] = $this->teacher_model->get_workingDays($class_id);
                if($main_subject_count==14)
                {
                    if($ay_year['id'] == 18 || $ay_year['id'] == 19){
                         $this->load->view('admin/examresult/finalresult_new', $data);
                    }else{
                        $this->load->view('admin/examresult/finalresult_new24_25', $data);
                    }
                    //$this->load->view('admin/examresult/finalresult_new_color', $data);
                } 
                else
                {
                    // print_r($ay_year);die();
                    if($ay_year['id'] == 18 || $ay_year['id'] == 19){
                        $this->load->view('admin/examresult/finalresult_15', $data);
                    }else{
                        //class 1 and 2
                        $this->load->view('admin/examresult/finalresult_15_24_25', $data);
                    }
                    //$this->load->view('admin/examresult/finalresult_15_color', $data);
                }
            }elseif ($exam_pattern == "Term") {   
                ini_set('display_errors', 1);
                ini_set('display_startup_errors', 1);
                error_reporting(E_ALL);                             
                $students_array = $this->student_model->getByStudentSessionSmall($student_session_ids);
                $rw=0;
                $data['new_session'] = $this->examgroup_model->get_session_start_date($class_id);
                
                foreach($students_array as $student)
                {
                    $examList = $this->examgroupstudent_model->studentExamsOrder($student['student_session_id']);
                    //echo "<pre>";print_r($examList);die();
                    $exam_subjects = $this->batchsubject_model->getExamSubjects($examList[0]->exam_group_class_batch_exam_id);                    
                    $core_subjects = array();
                    $subrw=0;
                    
                    foreach($exam_subjects as $subjects)
                    {
                        $main_subject=$this->subject_model->getSubjectByID($subjects->main_sub);  
                        $students_array[$rw]['main_subject'][] = $main_subject;
                        
                        $core_subjects = $this->examresult_model->term_core_subject_process($subjects,$examList,$student);
                        $students_array[$rw]['main_subject'][$subrw]['marks'] = $core_subjects;
                        ++$subrw;
                    }
                    
                    //$grade_main_subjects=$this->batchsubject_model->getExamMainGradeSubjectsOnly($examList[5]->exam_group_class_batch_exam_id);
                    $aip_exam_id = $examList[5]->exam_group_class_batch_exam_id;
                    $aip_exam_details = $this->examgroup_model->getExamByID($aip_exam_id);
                    $exam_grades = $this->grade_model->getByExamType($aip_exam_details->exam_group_type,"5PGS");
                    $aip_subjects=$this->batchsubject_model->getExamMainAIPSubjects($aip_exam_id);
                    $aip_mark_sum = 0;$aip_total = 0;
                    $last_att="";
                    foreach($aip_subjects as $aip_sub)
                    {
                        $aip_mark = $this->examresult_model->getSumFinalMarkAIP($student['student_session_id'],$aip_exam_id,$aip_sub->id);
                        $last_att = $aip_mark['att'];
                        if($aip_mark['att']=='')
                        {
                        $aip_mark_sum += $aip_mark['mark'];
                        $aip_total += $aip_mark['max_mark'];
                        }
                    }
                    if($aip_mark_sum>0)
                    {
                    $aip_percentage = round(($aip_mark_sum * 100) / $aip_total,2);
                    $aip_grade = $this->examresult_model->get_ExamGrade($exam_grades, $aip_percentage); 
                    }
                    else
                    {
                        $aip_grade = $last_att;
                    }
                    $students_array[$rw]['aip_grade'] = $aip_grade;
                    $subrw=0;
                    $grade_main_subjects=$this->batchsubject_model->getExamMainGradeSubjectsOnly($examList[2]->exam_group_class_batch_exam_id);
                    foreach($grade_main_subjects as $gmain_subject)
                    {
                        $grade_subjects = $this->examresult_model->term_grade_subject_process($gmain_subject,$examList,$student);
                        //echo "<pre>";print_r($grade_subjects);
                        $students_array[$rw]['grade_subject'][] = $gmain_subject;
                        $students_array[$rw]['grade_subject'][$subrw]->marks = $grade_subjects;
                        ++$subrw;
                    }
                    ++$rw;
                }
                
                $data['students_array'] = $students_array;
                //echo $students_array[0]['class_id'];
                $data['nextclass'] = $this->class_model->nextClass($students_array[0]['class_id']);
                $data['class_teacher'] = $this->class_model->getClassTeacherOfClass($class_id, $section_id);
                $data['principal'] = $this->staff_model->getPrincipal(1);
                $data['working_days'] = $this->teacher_model->get_workingDays($class_id);
                $data['sch_setting'] = $this->sch_setting_detail;
                $data['section_result_date'] = $this->examgroup_model->get_result_date($class_id);
                $data['result_date'] = $this->examgroup_model->get_result_date($class_id);
                if($ay_year['id'] == 18 || $ay_year['id'] == 19){
                    $this->load->view('admin/examresult/cbse_secondary_marksheet', $data);
                }else{
                    $this->load->view('admin/examresult/cbse_secondary_marksheet24_25', $data);

                }
                //$this->load->view('admin/examresult/cbse_secondary_marksheet_color', $data);
            }elseif ($exam_pattern == "EvaluationSingle") {                
                $students_array = $this->student_model->getByStudentSessionSmall($student_session_ids);
                $data['examList'] = $this->examgroupstudent_model->studentExamsResultAll($student_session_ids[0]);
                $data['exam_subjects'] = $this->batchsubject_model->getExamSubjects($data['examList'][2]->exam_group_class_batch_exam_id);
                $data['main_subjects'] = $this->batchsubject_model->getExamMainSubjectsByid($data['examList'][2]->exam_group_class_batch_exam_id);
                $main_subject_count =  sizeof($data['main_subjects']);
                $exam_details = $this->examgroup_model->getExamByID($data['examList'][0]->exam_group_class_batch_exam_id);
                $data['exam_grades'] = $this->grade_model->getByExamType($exam_details->exam_group_type, "8P");
                $data['new_session'] = $this->examgroup_model->get_session_start_date($class_id);
                $data['students_array'] = $students_array;
                $data['nextclass'] = $this->class_model->nextClass($students_array[0]['class_id'])['class'];
                $data['class_teacher'] = $this->class_model->getClassTeacherOfClass($class_id, $section_id);
                $data['principal'] = $this->staff_model->getPrincipal(1);
                $data['sch_setting'] = $this->sch_setting_detail;
                $data['working_days'] = $this->teacher_model->get_workingDays($class_id);
                $data['result_date'] = $this->examgroup_model->get_result_date($class_id);
                if($main_subject_count==9)
                {
                    if($ay_year['id'] == 18 || $ay_year['id'] == 19){
                        $this->load->view('admin/examresult/preprimary_result', $data);
                    }else{
                        $this->load->view('admin/examresult/preprimary_result24_25', $data);
                    }
                }   
                elseif($main_subject_count==10)
                {
                    if($ay_year == 18 || $ay_year == 19){
                         $this->load->view('admin/examresult/kg_result', $data);
                    }else{
                         $this->load->view('admin/examresult/kg_result_24_25', $data);
                    }
                }   

            }
        }
    }
    public function process_finalresult_primary()
    {
            $class_id = 4;$section_id=1;
            echo "<pre>";
            $data = array();
            $students=$this->student_model->getStudentByClassSectionAll($class_id,$section_id);
            $exams = $this->examgroupstudent_model->studentExamsResultOnly($students[0]['id']);
            foreach($students as $student)
            {
                $student_session_id = $student->id;
                $ev1_id = $exams[0]->id;
                $ev2_id = $exams[1]->id;
                $ev3_id = $exams[2]->id;
                $student_1 = $this->db->query("select * from exam_group_class_batch_exam_students where student_session_id = '$student_session_id' and exam_id  = '$ev1_id'")->row_array();
                $student_2 = $this->db->query("select * from exam_group_class_batch_exam_students where student_session_id = '$student_session_id' and exam_id  = '$ev2_id'")->row_array();
                $student_3 = $this->db->query("select * from exam_group_class_batch_exam_students where student_session_id = '$student_session_id' and exam_id  = '$ev3_id'")->row_array();
            }
                $data['exam_subjects'] = $this->batchsubject_model->getExamSubjects($data['examList'][0]->exam_group_class_batch_exam_id);
                $data['main_subjects'] = $this->batchsubject_model->getExamMainSubjects($data['examList'][0]->exam_group_class_batch_exam_id);
                $main_subject_count =  sizeof($data['main_subjects']);
                
                $data['students_array'] = $students_array;
                //echo "<pre>";
                $data['nextclass'] = $this->class_model->nextClass($students_array[0]['class_id'])['class'];
                $data['class_teacher'] = $this->class_model->getClassTeacherOfClass($class_id, $section_id);
                $data['principal'] = $this->staff_model->getPrincipal(1);
                //print_r($data['nextclass']);die();
                $data['sch_setting'] = $this->sch_setting_detail;
                if($main_subject_count==13)
                {$this->load->view('admin/examresult/finalresult_new', $data);} 
                else
                {$this->load->view('admin/examresult/finalresult_15', $data);}
        
    }
    public function process_finalresult_secondary()
    {
            $class_id = 9;$section_id=1;
            $data = array();
            $data = array();
            
            $students=$this->student_model->getStudentByClassSectionAll($class_id,$section_id);
            
            //print_r($students);die();
            $rw=0;
            foreach($students as $student)
            {
                $examList = $this->examgroupstudent_model->studentExams($student['id']);
                $exam_subjects = $this->batchsubject_model->getExamSubjects($examList[0]->exam_group_class_batch_exam_id);                    
                $exam_details = $this->examgroup_model->getExamByID($examList[0]->exam_group_class_batch_exam_id);
                $exam_grades = $this->grade_model->getByExamType($exam_details->exam_group_type,"10P");
                $core_subjects = array();
                $subrw=0;
                $total_mark=0;
                $max_mark=0;
                $total_percentage = 0;
                $grade = "";
                $remarks = "";
                foreach($exam_subjects as $subjects)
                {
                    $main_subject=$this->subject_model->getSubjectByID($subjects->main_sub);  
                    $students_array[$rw]['main_subject'][] = $main_subject;
                    
                    $core_subjects = $this->examresult_model->term_core_subject_process($subjects,$examList,$student);
                    
                    $total_mark+=$core_subjects['total'];
                    $max_mark+=100;
                    $students_array[$rw]['main_subject'][$subrw]['marks'] = $core_subjects;
                    ++$subrw;
                }
                if ($total_mark > 0) {
                    $total_percentage = ($total_mark * 100) / $max_mark;
                } else {
                    $total_percentage = 0;
                }
                $final_grade = $this->grade_model->get_Grade($exam_grades, $total_percentage);
                $final_remark = $this->grade_model->get_Remark($exam_grades, $final_grade);

                $data = array(
                    'id' => $student['id'],
                    'total_mark' => $total_mark,
                    'max_mark' => $max_mark,
                    'percentage' => round($total_percentage,2),
                    'grade' => $final_grade,
                    'remark' => $final_remark,
                );
                $this->studentsession_model->updateById($data);                 
            } 
            echo "Success";
    }
    public function testgradenew($gradetype,$percentage)
    {
        $grades = $this->grade_model->getByGradeType($gradetype);
        
        echo $percentage. " will get the grade : ".$this->grade_model->get_Grade_New($grades,$percentage);
    }  
    public function testgradeold($gradetype,$percentage)
    {
        $grades = $this->grade_model->getByGradeType($gradetype);
        $percentage = round($percentage);
        echo $percentage. " will get the grade : ".$this->grade_model->get_Grade($grades,$percentage);
    }   
    public function test()
    {
        echo "hai";
        $result=$this->db->query("select staff_id from class_teacher where class_id = '6' and section_id = '3' and session_id = '20' order by id desc")->row_array(); 

        echo "<pre>";print_r($result);die();
    } 
    public function update_late_admission_att()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        $result  = $this->db->query('select student_session.id,student_session.roll_no, students.admission_date from student_session inner join students on students.id = student_session.student_id where student_session.class_id > 8 and student_session.session_id = 20 order by student_session.id');
        $result_arr = $result->result_array();
        //echo "<pre>";print_r($result_arr);
        foreach($result_arr as $res)
        {
            if($res['admission_date']!='')
            {
                //echo "select * from student_attendences where student_session_id = '" . $res['id'] . "' and date < '" . $res['admission_date'] . "'";
                $att = $this->db->query("select * from student_attendences where student_session_id = '" . $res['id'] . "' and date < '" . $res['admission_date'] . "'");
                
                $att_res=$att->result_array();
                if(!empty($att_res))
                {
                    echo "<br>Roll No : ".$res['roll_no']."--".$res['admission_date'];
                    $this->db->query("delete from student_attendences where student_session_id = '" . $res['id'] . "' and date < '" . $res['admission_date'] . "'");
                }
            }
        }
        echo "success";
    }  
}
