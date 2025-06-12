<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Examresult_model extends CI_Model
{

    public $current_session;

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function get($id = null)
    {
        $this->db->select()->from('exam_results');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function remove($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('exam_results');
    }

    /**
     * This function will take the post data passed from the controller
     * If id is present, then it will do an update
     * else an insert. One function doing both add and edit.
     * @param $data
     */
    public function add($data)
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('exam_results', $data);
        } else {
            $this->db->insert('exam_results', $data);
            return $this->db->insert_id();
        }
    }

    /* get subject  */
    public function getsubject($main_sub)
    {
        if (!empty($main_sub)) {
            $this->db->where('id', $main_sub);
            $query = $this->db->get('subjects');
            if ($query->num_rows() > 0) {
                return $query->row_array();
            } else {
                return false;
            }
        }
    }

    /* closing get subject */
    public function add_exam_result($data)
    {
        $this->db->where('exam_schedule_id', $data['exam_schedule_id']);
        $this->db->where('student_id', $data['student_id']);
        $q = $this->db->get('exam_results');
        $result = $q->row();
        if ($q->num_rows() > 0) {
            $this->db->where('id', $result->id);
            $this->db->update('exam_results', $data);
            if ($result->get_marks != $data['get_marks']) {
                return $result->id;
            }
        } else {
            $this->db->insert('exam_results', $data);
            $insert_id = $this->db->insert_id();
            return $insert_id;
        }
        return false;
    }

    public function get_exam_result($exam_schedule_id = null, $student_id = null)
    {
        $this->db->select()->from('exam_results');
        $this->db->where('exam_schedule_id', $exam_schedule_id);
        $this->db->where('student_id', $student_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            $obj = new stdClass();
            $obj->attendence = 'pre';
            $obj->get_marks = "0.00";
            return $obj;
        }
    }

    public function get_result($exam_schedule_id = null, $student_id = null)
    {
        $this->db->select()->from('exam_results');
        $this->db->where('exam_schedule_id', $exam_schedule_id);
        $this->db->where('student_id', $student_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
        }
    }

    public function checkexamresultpreparebyexam($exam_id, $class_id, $section_id)
    {
        $query = $this->db->query("SELECT count(*) `counter` FROM `exam_results`,exam_schedules,student_session WHERE exam_results.exam_schedule_id=exam_schedules.id and student_session.student_id=exam_results.student_id and student_session.class_id=" . $this->db->escape($class_id) . " and student_session.section_id=" . $this->db->escape($section_id) . " and exam_schedules.session_id=" . $this->db->escape($this->current_session) . " and exam_schedules.exam_id=" . $this->db->escape($exam_id));
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
        return $query->result_array();
    }

    public function getStudentExamResultByStudent($exam_id, $student_id, $exam_schedule)
    {
        $sql = "SELECT exam_schedules.id as `exam_schedules_id`,exam_results.id as `exam_results_id`,exam_schedules.exam_id,exam_schedules.date_of_exam,exam_schedules.full_marks,exam_schedules.passing_marks,exam_results.student_id,exam_results.get_marks,students.firstname,students.middlename,students.lastname,students.guardian_phone,students.email ,exams.name as `exam_name` FROM `exam_schedules` INNER JOIN exams on exams.id=exam_schedules.exam_id INNER JOIN exam_results ON exam_results.exam_schedule_id=exam_schedules.id INNER JOIN students on students.id=exam_results.student_id WHERE exam_schedules.session_id =" . $this->db->escape($this->current_session) . " and exam_schedules.exam_id =" . $this->db->escape($exam_id) . " and exam_results.student_id =" . $this->db->escape($student_id) . " and exam_schedules.id in (" . $exam_schedule . ") ORDER BY `exam_results`.`id` ASC";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getExamResults($exam_id, $post_exam_group_id, $students)
    {

        $result = array('exam_connection' => 0, 'students' => array(), 'exams' => array(), 'exam_connection_list' => array());
        $exam_connection = false;
        $exam_connections = $this->examgroup_model->getExamGroupConnectionList($post_exam_group_id);
        if (!empty($exam_connections)) {
            $lastkey = key(array_slice($exam_connections, -1, 1, true));
            if ($exam_connections[$lastkey]->exam_group_class_batch_exams_id == $exam_id) {
                $exam_connection = true;
                $result['exam_connection'] = 1;
            }
        }
        $result['exam_connection_list'] = $exam_connections;

        foreach ($students as $student_key => $student_value) {

            $student = $this->examstudent_model->getExamStudentByID($student_value);

            $student['exam_result'] = array();
            if ($exam_connection) {
                foreach ($exam_connections as $exam_connection_key => $exam_connection_value) {
                    $exam_group_class_batch_exam_student = $this->examstudent_model->getStudentByExamAndStudentID($student_value, $exam_connection_value->exam_group_class_batch_exams_id);

                    $exam = $this->examgroup_model->getExamByID($exam_connection_value->exam_group_class_batch_exams_id);

                    $student['exam_result']['exam_roll_no_' . $exam_connection_value->exam_group_class_batch_exams_id] =  $student['roll_no'];


                    $student['exam_result']['exam_result_' . $exam_connection_value->exam_group_class_batch_exams_id] = $this->getStudentResultByExam($exam_id, $student['id']);


                    $result['exams']['exam_' . $exam_connection_value->exam_group_class_batch_exams_id] = $exam;
                }
                $result['students'][] = $student;
            } else {
                $student['exam_roll_no'] = $student['roll_no'];
                $student['exam_result'] = $this->getStudentResultByExam($exam_id, $student['id']);
                $result['students'][] = $student;
            }
        }

        return $result;
    }

    public function getStudentResultByExam($exam_id, $student_id)
    {
        $sql = "SELECT exam_group_class_batch_exam_subjects.*,exam_group_exam_results.id as `exam_group_exam_results_id`,exam_group_exam_results.id as `result_id`,exam_group_exam_results.attendence,exam_group_exam_results.get_marks,exam_group_exam_results.note,exam_group_exam_results.final_mark,subjects.name,subjects.code,exam_group_exam_results.admin_lock_status,exam_group_exam_results.head_lock_status,exam_group_exam_results.get_grade FROM `exam_group_class_batch_exam_subjects` inner JOIN exam_group_exam_results on exam_group_exam_results.exam_group_class_batch_exam_subject_id=exam_group_class_batch_exam_subjects.id INNER JOIN exam_group_class_batch_exam_students on exam_group_exam_results.exam_group_class_batch_exam_student_id=exam_group_class_batch_exam_students.id and exam_group_class_batch_exam_students.id=" . $this->db->escape($student_id) . " INNER JOIN subjects on subjects.id=exam_group_class_batch_exam_subjects.subject_id WHERE exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id=" . $this->db->escape($exam_id);
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function getStudentWorksheetGrade($student_id)
    {
        $sql = "select * from student_worksheet_marks where student_session_id = " . $this->db->escape($student_id) . " order by id";
        $query = $this->db->query($sql);
        if(!empty($query))
        { return $query->result(); }
        else
        { return array(); }
        
    }
    public function getexamSubjectRow($exam_id,$student_id)
    {
        $this->db->select('exam_group_exam_results.*');
        $this->db->join('exam_group_exam_results', 'exam_group_exam_results.exam_group_class_batch_exam_subject_id=exam_group_class_batch_exam_subjects.id');
        $this->db->join('exam_group_class_batch_exam_students', 'exam_group_exam_results.exam_group_class_batch_exam_student_id=exam_group_class_batch_exam_students.id');
        $this->db->join('subjects', 'subjects.id=exam_group_class_batch_exam_subjects.subject_id');
        $this->db->where('exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id', $exam_id);
        $this->db->where('exam_group_class_batch_exam_students.id', $student_id);
        return $this->db->get('exam_group_class_batch_exam_subjects')->result_array();
        
    }

    public function getStudentExamResults($exam_id, $post_exam_group_id, $exam_group_class_batch_exam_student_id, $student_id)
    {

        $result = array('exam_connection' => 0, 'result' => array(), 'exams' => array(), 'exam_connection_list' => array());
        $exam_connection = false;
        $exam_connections = $this->examgroup_model->getExamGroupConnectionList($post_exam_group_id);
        if (!empty($exam_connections)) {
            $lastkey = key(array_slice($exam_connections, -1, 1, true));
            if ($exam_connections[$lastkey]->exam_group_class_batch_exams_id == $exam_id) {
                $exam_connection = true;
                $result['exam_connection'] = 1;
            }
        }
        $result['exam_connection_list'] = $exam_connections;
        if ($exam_connection) {
            $new_array = array();

            foreach ($exam_connections as $exam_connection_key => $exam_connection_value) {

                $exam_group_class_batch_exam_student = $this->examstudent_model->getStudentByExamAndStudentID($student_id, $exam_connection_value->exam_group_class_batch_exams_id);

                $exam = $this->examgroup_model->getExamByID($exam_connection_value->exam_group_class_batch_exams_id);
                if (!empty($exam_group_class_batch_exam_student->id)) {
                    $result['exam_result']['exam_result_' . $exam_connection_value->exam_group_class_batch_exams_id]
                        = $this->getStudentResultByExam($exam_connection_value->exam_group_class_batch_exams_id, $exam_group_class_batch_exam_student->id);
                }
                $result['exams']['exam_' . $exam_connection_value->exam_group_class_batch_exams_id] = $exam;
            }
        } else {

            $result['exam_connection_list'] = $exam_connections;

            $result['result'] = $this->getStudentResultByExam($exam_id, $exam_group_class_batch_exam_student_id);
        }

        return $result;
    }
    public function getCountSubject($id)
    {
        $this->db->where('parent_id', $id);
        $data = $this->db->get('subjects')->result_array();
        $count = 1;
        foreach ($data as $row) { // 3 Loop
            $this->db->where('parent_id', $row['id']);
            $count += $this->db->get('subjects')->num_rows();
            
        }
       
        return $count;
    }

    public function getCountSubjectTwo($id)
    {
        $this->db->where('parent_id', $id);
        $count = $this->db->get('subjects');
        return $count;
    }

    public function getCountSubjectThree($id,$exam_id)
    {
        $this->db->where(['exam_group_class_batch_exams_id'=>$exam_id,'main_sub'=>$id]);
        $count = $this->db->get('exam_group_class_batch_exam_subjects')->num_rows();
        return $count;
    }

    public function check_subject($one,$two)
    {
        $this->db->where('exam_group_class_batch_exams_id',$one);
        $this->db->where('subject_id',$two);
        return $this->db->get('exam_group_class_batch_exam_subjects');
        
    }
    public function getquery($id)
    {
        $this->db->select('parent_id');
        $this->db->where('parent_id',$id);
        $query = $this->db->get('subjects');
        return $query->result_array();
    }

    public function check_exam_sub($id)
    { 
        $this->db->where('subject_id',$id);
        return $this->db->get('exam_group_class_batch_exam_subjects');        
    }

    public function get_subsublist($exam_id)
    {
        $this->db->where('exam_group_class_batch_exams_id',$exam_id);
        return $this->db->get('exam_group_class_batch_exam_subjects'); 
    }
     
    public function getsubjectMarks($exam_id,$exam_subjectid,$subject_id,$main_sub,$exam_studentid)
    {
        $this->db->select('*');
        // $this->db->where('exam_group_class_batch_exams_id', $exam_id);
        $this->db->where('exam_group_class_batch_exam_student_id', $exam_studentid);
        $this->db->where('exam_group_class_batch_exam_subject_id', $exam_subjectid);
        // $this->db->where('subject_id', $subject_id);
        // $this->db->where('main_sub', $main_sub);
       return $this->db->get('exam_group_exam_results')->row_array();
        
    }
    
    public function checkrowMarks($examsubject_id,$exam_group_student_id)
    {
       $this->db->select('*');
       $this->db->where('exam_group_class_batch_exam_subject_id', $examsubject_id);
       $this->db->where('exam_group_class_batch_exam_student_id', $exam_group_student_id);
       return $this->db->get('exam_group_exam_results')->num_rows();
    }
    //manoj added
    public function getFinalMark($student_session_id,$exam_id,$subject_id)
    {
        $exam_subject=$this->db->query("select * from exam_group_class_batch_exam_subjects where exam_group_class_batch_exams_id = '$exam_id' and subject_id = '$subject_id' ")->result_array();
        if(!empty($exam_subject)){
        $exam_student=$this->db->query("select * from exam_group_class_batch_exam_students where exam_group_class_batch_exam_id = '$exam_id' and student_session_id = '$student_session_id' ")->result_array();
        $this->db->select('*');
        $this->db->where('exam_group_class_batch_exam_subject_id', $exam_subject[0]['id']);
        $this->db->where('exam_group_class_batch_exam_student_id', $exam_student[0]['id']);
        // $this->db->where('exam_group_class_batch_exams_id', $exam_id);
        return $this->db->get('exam_group_exam_results')->row_array();
        }
    }
    public function getSumFinalMark($student_session_id,$exam_id,$main_subject_id)
    {
        $note_status = array("NEW", "LEFT","NA");
        $ab_status = array("ABSENT");

        $exam_subject=$this->db->query("select * from exam_group_class_batch_exam_subjects where exam_group_class_batch_exams_id = '$exam_id' and main_sub = '$main_subject_id' ")->result_array();
        if(!empty($exam_subject)){
            $mark=0;
            $max_mark=0;
            $att="";
            foreach($exam_subject as $sub)
            {
                $exam_student=$this->db->query("select * from exam_group_class_batch_exam_students where exam_group_class_batch_exam_id = '$exam_id' and student_session_id = '$student_session_id' ")->result_array();
                $this->db->select('*');
                $this->db->where('exam_group_class_batch_exam_subject_id', $sub['id']);
                $this->db->where('exam_group_class_batch_exam_student_id', $exam_student[0]['id']);
                $this->db->where('exam_group_class_batch_exams_id', $exam_id);
                $result = $this->db->get('exam_group_exam_results')->row_array();
                if(!empty($result))
                {
                    if(in_array(trim(strtoupper($result['note'])), $note_status)) 
                    {
                              $mark = 0;
                              $att= "-";
                    }
                    elseif(trim(strtoupper($result['attendence']))=="ABSENT" || $result['note']=="FD")
                    {
                        $mark = 0;
                        $att= "Ab";
                    }                     

                    else
                    {
                        $mark+=$result['get_marks'];
                        $max_mark+=$sub['max_marks'];
                    }
                }

            }
            if($mark>0)
            {
                $res= array(
                    "mark" => $mark,
                    "max_mark" => $max_mark,
                    "att" => "",
                );
            }
            else
            {
                $res= array(
                    "mark" => 0,
                    "max_mark" => 0,
                    "att" => $att,
                );
            }
            
            
        }
        else
        {
            $res= array(
                "mark" => 0,
                "max_mark" => 0,
                "att" => "-",
            );            
        }
        return $res;
    }
    public function getSumFinalMarkGrade($student_session_id,$exam_id,$main_subject_id)
    {
        $note_status = array("NEW", "LEFT","NA");
        $ab_status = array("ABSENT");
        $grace_mark=0;
        $exam_subject=$this->db->query("select * from exam_group_class_batch_exam_subjects where exam_group_class_batch_exams_id = '$exam_id' and main_sub = '$main_subject_id' ")->result_array();
        if(!empty($exam_subject)){
            $mark=0;
            $max_mark=0;
            $att="";
            $grace_mark=0;
            foreach($exam_subject as $sub)
            {
                $exam_student=$this->db->query("select * from exam_group_class_batch_exam_students where exam_group_class_batch_exam_id = '$exam_id' and student_session_id = '$student_session_id' ")->result_array();
                $this->db->select('*');
                $this->db->where('exam_group_class_batch_exam_subject_id', $sub['id']);
                $this->db->where('exam_group_class_batch_exam_student_id', $exam_student[0]['id']);
                $this->db->where('exam_group_class_batch_exams_id', $exam_id);
                $result = $this->db->get('exam_group_exam_results')->row_array();
                if(!empty($result))
                {
                    if(in_array(trim(strtoupper($result['note'])), $note_status)) 
                    {
                              $mark = 0;
                              $att= "-";
                    }
                    elseif(trim(strtoupper($result['attendence']))=="ABSENT" || $result['note']=="FD")
                    {
                        $mark = 0;
                        $att= "Ab";
                    }                     

                    else
                    {
                        $mark+=$result['get_marks'];
                        $max_mark+=$sub['max_marks'];
                        $grace_mark+=$result['grace_mark'];
                    }
                }
            }
            if($mark>0)
            {
                $res= array(
                    "mark" => $mark,
                    "max_mark" => $max_mark,
                    "grace_mark" => $grace_mark,
                    "att" => "",
                );
            }
            else
            {
                $res= array(
                    "mark" => 0,
                    "max_mark" => 0,
                    "grace_mark" => 0,
                    "att" => $att,
                );
            }
            
            
        }
        else
        {
            $res= array(
                "mark" => 0,
                "max_mark" => 0,
                "grace_mark" => 0,
                "att" => "-",
            );            
        }
        return $res;
    }
    public function getSumFinalMarkAIP($student_session_id,$exam_id,$subject_id)
    {
        $note_status = array("NEW", "LEFT","NA");
        $ab_status = array("ABSENT");
        $grace_mark=0;
        $exam_subject=$this->db->query("select * from exam_group_class_batch_exam_subjects where exam_group_class_batch_exams_id = '$exam_id' and subject_id = '$subject_id' ")->result_array();
        if(!empty($exam_subject)){
            $mark=0;
            $max_mark=0;
            $att="";
            $grace_mark=0;
            foreach($exam_subject as $sub)
            {
                $exam_student=$this->db->query("select * from exam_group_class_batch_exam_students where exam_group_class_batch_exam_id = '$exam_id' and student_session_id = '$student_session_id' ")->result_array();
                $this->db->select('*');
                $this->db->where('exam_group_class_batch_exam_subject_id', $sub['id']);
                $this->db->where('exam_group_class_batch_exam_student_id', $exam_student[0]['id']);
                $this->db->where('exam_group_class_batch_exams_id', $exam_id);
                $result = $this->db->get('exam_group_exam_results')->row_array();
                if(!empty($result))
                {
                    if(in_array(trim(strtoupper($result['note'])), $note_status)) 
                    {
                              $mark = 0;
                              $att= "-";
                    }
                    elseif(trim(strtoupper($result['attendence']))=="ABSENT" || $result['note']=="FD")
                    {
                        $mark = 0;
                        $att= "Ab";
                    }                     

                    else
                    {
                        $mark+=$result['get_marks'];
                        $max_mark+=$sub['max_marks'];
                        $grace_mark+=$result['grace_mark'];
                    }
                }
            }
            if($mark>0)
            {
                $res= array(
                    "mark" => $mark,
                    "max_mark" => $max_mark,
                    "grace_mark" => $grace_mark,
                    "att" => "",
                );
            }
            else
            {
                $res= array(
                    "mark" => 0,
                    "max_mark" => 0,
                    "grace_mark" => 0,
                    "att" => $att,
                );
            }
            
            
        }
        else
        {
            $res= array(
                "mark" => 0,
                "max_mark" => 0,
                "grace_mark" => 0,
                "att" => "-",
            );            
        }
        return $res;
    }

    public function term_grade_subject_process($main_subject,$examList,$student)
    {   
        $note_status = array("NEW", "LEFT","NA");
        $ab_status = array("ABSENT");

        $exam_details = $this->examgroup_model->getExamByID($examList[2]->exam_group_class_batch_exam_id);

        $exam_grades = $this->grade_model->getByExamType($exam_details->exam_group_type,"5PGS"); 
        $sa1 = $this->examresult_model->getSumFinalMarkGrade($student['student_session_id'], $examList[2]->exam_group_class_batch_exam_id, $main_subject->id);

        $sa2 = $this->examresult_model->getSumFinalMarkGrade($student['student_session_id'], $examList[5]->exam_group_class_batch_exam_id, $main_subject->id);
        $t1_grade="";$t2_grade="";$grade="";$grace_mark=0;
        $t1_grace_mark = 0;$t2_grace_mark = 0;
        if(!empty($sa1))
        {
            if($sa1['att']=="-") {   
                $t1_grade="-";
            }
            elseif($sa1['att']=="Ab")
            {   
                $t1_grade="Ab";
            }          
            elseif($sa1['mark'] > 0 )
            {
                if($sa1['grace_mark']>0)
                {
                    $grace_mark=$sa1['grace_mark'];
                    $t1_grace_mark = $grace_mark;
                }
                else
                {$grace_mark=0;$t1_grace_mark = 0;}                
                $total_percentage = (($sa1['mark'] + $grace_mark) * 100) / $sa1['max_mark'];
                $t1_grade = $this->get_ExamGrade($exam_grades, $total_percentage);     
            }
        }
        else
        {$t1_grade="";}
        $t2_grace_mark = 0;
        if(!empty($sa2))
        {
            if($sa2['att']=="-") {   
                $t2_grade="-";
            }
            elseif($sa2['att']=="Ab")
            {   
                $t2_grade="Ab";
            }          
            elseif($sa2['mark'] > 0 )
            {
                if($sa2['grace_mark']>0)
                {
                    $grace_mark=$sa2['grace_mark'];
                    $t2_grace_mark = $grace_mark;
                }
                else
                {$grace_mark=0;}    

                $total_percentage = (($sa2['mark'] + $grace_mark) * 100) / $sa2['max_mark'];
                $t2_grade = $this->get_ExamGrade($exam_grades, $total_percentage);            
            }
        }
        else
        {$t2_grade="";}

        $res = array(
            "t1" => $t1_grade,
            "t1_grace_mark" => $t1_grace_mark,
            "t2" => $t2_grade,
            "t2_grace_mark" => $t2_grace_mark,
        );
        return $res;
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
    public function preprimary_subject_process($subjects_all,$examList,$student,$exam_grades)
    {
        $v_mode=0;
        $max_mark=0;
        if($this->input->post('verification_mode')!='')
        {
            if($this->input->post('verification_mode')==1)
            {
                $v_mode=1;
            }
        }
        foreach($examList as $exam)
        {
        $exam_details = $this->examgroup_model->getExamByID($exam->exam_group_class_batch_exam_id);
        $subcnt=0;
        foreach($subjects_all as $subjects)
        {
            //$note_status = array("NEW", "LEFT","NA");
            $note_status = array("NEW", "LEFT");
            $ab_status = array("ABSENT");
            $fa1_sub=$this->batchsubject_model->getExamSubjectsBySubjectId($exam->exam_group_class_batch_exam_id,$subjects->subject_id);                    
            $fa1 = $this->getFinalMark($student['student_session_id'], $exam->exam_group_class_batch_exam_id, $subjects->subject_id);
            // if($student['student_session_id'] == 3879)
            // {
            //     echo "<pre>";print_r($fa1_sub);print_r($fa1);
            // }
            if(!empty($fa1))
            {
                $input_type = $fa1_sub->input_type;
                if(in_array(trim(strtoupper($fa1['note'])), $note_status)) {   
                    $att = "-";
                    $mark = 0;
                    $final = "-";
                }
                elseif(strtoupper($fa1['attendence'])=="ABSENT" || $fa1['note']=="FD")
                {   
                    $att = "AB";
                    $mark = 0;
                    $final = "Ab";
                }
                elseif(trim(strtoupper($fa1['note']))=="NA" && $fa1_sub->input_type != "Marks" && $fa1['get_grade']!='')
                {   
                    $final=$fa1['get_grade'];
                    $total_percentage = 0;
                    $max_mark=$fa1_sub->max_marks;
                }
                elseif(trim(strtoupper($fa1['note'])) && $fa1_sub->input_type != "Marks" && $fa1['get_grade']=='')
                {   
                    $att = "-";
                    $mark = 0;
                    $final = "-";
                }   
                elseif(trim(strtoupper($fa1['note'])) && $fa1_sub->input_type == "Marks" && ($fa1['get_marks']=='' || $fa1['get_marks']==0.00  || (int)$fa1['get_marks']==0 ) )
                {   
                    $att = "-";
                    $mark = 0;
                    $final = "-";
                }                
                elseif(trim(strtoupper($fa1['note'])) && $fa1_sub->input_type == "Marks" && $fa1['get_marks'] > 0)
                {

                    $att = "";
                    if($fa1['grace_mark']>0)
                    {
                        $grace_mark=$fa1['grace_mark'];
                        $fa1_grace=$grace_mark;
                    }
                    else
                    {$grace_mark=0;}
                    if($fa1_sub->convertTo>0)
                    {
                        $mark = round((($fa1['get_marks']+$grace_mark)/$fa1_sub->max_marks) * $fa1_sub->convertTo);
                        $max_mark=$fa1_sub->convertTo;
                    }
                    else
                    {$mark = round($fa1['get_marks']+$grace_mark);
                    $max_mark=$fa1_sub->max_marks;}
                    $final=$mark;
                    if ($mark > 0) {
                        $total_percentage = ($mark * 100) / $max_mark;
                    } else {
                        $total_percentage = 0;
                    }  
                    if($v_mode==1)
                    {$final = $mark."/".$max_mark."(".$this->grade_model->get_Grade_New($exam_grades,1, $total_percentage).")";}
                    else
                    {$final = $this->grade_model->get_Grade_New($exam_grades,1, $total_percentage);}

                }         
                else
                {
                    $att = "";
                    if($fa1_sub->input_type == "Marks")
                    {
                        if($fa1['grace_mark']>0)
                        {
                            $grace_mark=$fa1['grace_mark'];
                            $fa1_grace=$grace_mark;
                        }
                        else
                        {$grace_mark=0;}
                        if($fa1_sub->convertTo>0)
                        {
                            $mark = round((($fa1['get_marks']+$grace_mark)/$fa1_sub->max_marks) * $fa1_sub->convertTo);
                            $max_mark=$fa1_sub->convertTo;
                        }
                        else
                        {$mark = round($fa1['get_marks']+$grace_mark);
                        $max_mark=$fa1_sub->max_marks;}
                        $final=$mark;
                        if ($mark > 0) {
                            $total_percentage = ($mark * 100) / $max_mark;
                        } else {
                            $total_percentage = 0;
                        }  
                        if($v_mode==1)
                        {$final = $mark."/".$max_mark."(".$this->grade_model->get_Grade_New($exam_grades,1, $total_percentage).")";}
                        else
                        {$final = $this->grade_model->get_Grade_New($exam_grades,1, $total_percentage);}
            
                    }
                    else
                    {
                        $final=$fa1['get_grade'];
                        $total_percentage = 0;
                        $max_mark=$fa1_sub->max_marks;
                    }
                }
                $data = array(
                    'final_mark' => $final
                );
                if(!empty($data))
                {
                     $this->db->where('id', $fa1['id']);
                     $this->db->update('exam_group_exam_results', $data);
                    ++$subcnt;
                }
            }
        
        }
        }
    }
    public function term_core_subject_process($subjects,$examList,$student)
    {
        // echo "<pre>";
        // print_r($subjects);
        //echo $student['roll_no']."-";
        $exam_details = $this->examgroup_model->getExamByID($examList[0]->exam_group_class_batch_exam_id);
        $exam_grades = $this->grade_model->getByExamType($exam_details->exam_group_type,"10P");
        $t1_grade="";$t2_grade="";$t1_max_mark=0;$t2_max_mark=0;$grade="";$net_max_mark=0;
        $fa1_grace=0;$fa2_grace=0;$sa1_grace=0;
        $fa3_grace=0;$fa4_grace=0;$sa2_grace=0;
        $fa1_final="";$fa2_final="";$sa1_final="";
        $fa3_final="";$fa4_final="";$sa2_final="";
        $grace_mark=0;$total_grace=0;

        //start fa1
        $t1_max_mark=0;
        $note_status = array("NEW", "LEFT","NA");
        $ab_status = array("ABSENT");

        $fa1_sub=$this->batchsubject_model->getExamSubjectsBySubjectId($examList[0]->exam_group_class_batch_exam_id,$subjects->subject_id);        
        $fa1 = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[0]->exam_group_class_batch_exam_id, $subjects->subject_id);
        if(!empty($fa1))
        {
            if(in_array(trim(strtoupper($fa1['note'])), $note_status)) {   
                $fa1_att = "-";
                $fa1_mark = 0;
                $fa1_final = "-";
            }
            elseif($fa1['attendence']=="absent" || $fa1['note']=="FD")
            {   
                $fa1_att = "AB";
                $fa1_mark = 0;
                $fa1_final = "Ab";
            }
            else
            {
                $fa1_att = "";
                if($fa1['grace_mark']>0)
                {
                    $grace_mark=$fa1['grace_mark'];
                    $fa1_grace=$grace_mark;
                }
                else
                {$grace_mark=0;}
                if($fa1_sub->convertTo>0)
                {
                    $fa1_mark = round((($fa1['get_marks']+$grace_mark)/$fa1_sub->max_marks) * $fa1_sub->convertTo);
                    $t1_max_mark+=$fa1_sub->convertTo;
                }
                else
                {$fa1_mark = round($fa1['get_marks']+$grace_mark);
                $t1_max_mark+=$fa1_sub->max_marks;}
                $fa1_final=$fa1_mark;
            }
        }
        else
        { $fa1_mark=0;$fa1_att="";$fa1_final= "-";}
        //end fa1
        //start fa2
        $fa2_sub=$this->batchsubject_model->getExamstudentSubjectsBYMainSubject($examList[1]->exam_group_class_batch_exam_id,$subjects->main_sub);
        $fa2 = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[1]->exam_group_class_batch_exam_id, $fa2_sub[0]->subject_id);
        if(!empty($fa2))
        {
            if (in_array(trim(strtoupper($fa2['note'])), $note_status)) {
                $fa2_att = "-";
                $fa2_mark = 0;
                $fa2_final= "-";
            }
            elseif($fa2['attendence']=="absent" || $fa2['note']=="FD")
            {
                $fa2_att = "AB";
                $fa2_mark = 0;
                $fa2_final= "Ab";
            }
            else
            {
                $fa2_att = "";
                if($fa2['grace_mark']>0)
                {
                    $grace_mark=$fa2['grace_mark'];
                    $fa2_grace=$grace_mark;
                }
                else
                {$grace_mark=0;}                
                if($fa2_sub[0]->convertTo>0)
                {
                    $fa2_mark = round((($fa2['get_marks']+$grace_mark)/$fa2_sub[0]->max_marks) * $fa2_sub[0]->convertTo);
                    $t1_max_mark+=$fa2_sub[0]->convertTo;
                }
                else
                {$fa2_mark = round($fa2['get_marks']+$grace_mark);
                
                $t1_max_mark+=$fa2_sub[0]->max_marks;}
                $fa2_final= $fa2_mark;
            }
        }
        else
        {$fa2_mark=0;$fa2_att="";$fa2_final= "-";}
        //end fa2        
        //start sa1
        $sa1_sub=$this->batchsubject_model->getExamSubjectsBySubjectId($examList[2]->exam_group_class_batch_exam_id,$subjects->subject_id);        
        $sa1 = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[2]->exam_group_class_batch_exam_id, $subjects->subject_id);
        if(!empty($sa1))
        {
            if (in_array(trim(strtoupper($sa1['note'])), $note_status)) {
                $sa1_att = "-";
                $sa1_mark = 0;
                $sa1_final="-";
            }
            elseif($sa1['attendence']=="absent" || $sa1['note']=="FD")
            {
                $sa1_att = "AB";
                $sa1_mark = 0;
                $sa1_final="Ab";
            }
            else
            {
                $sa1_att = "";
                if($sa1['grace_mark']>0)
                {
                    $grace_mark=$sa1['grace_mark'];
                    $sa1_grace=$grace_mark;
                }
                else
                {$grace_mark=0;}                 
                if($sa1_sub->convertTo>0)
                {
                    //echo $sa1['get_marks'];
                    $sa1_mark = round((($sa1['get_marks']+$grace_mark)/$sa1_sub->max_marks) * $sa1_sub->convertTo);
                    $t1_max_mark+=$sa1_sub->convertTo;
                }
                else
                {$sa1_mark = round($sa1['get_marks']+$grace_mark);
                $t1_max_mark+=$sa1_sub->max_marks;}
                $sa1_final=$sa1_mark;
            }
        }
        else
        {$sa1_mark=0;$sa1_att="";$sa1_final="-";}

        $t1_total = round($fa1_mark+$fa2_mark+$sa1_mark,2);
        if($t1_total>0)
        {
        $percent = round(($t1_total/$t1_max_mark)*100,2);
        $t1_grade = $this->grade_model->get_Grade($exam_grades, $percent);
        }

        //start term 2
        //start fa1
        $t2_max_mark=0;
        $fa3_sub=$this->batchsubject_model->getExamSubjectsBySubjectId($examList[3]->exam_group_class_batch_exam_id,$subjects->subject_id);        
        $fa3 = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[3]->exam_group_class_batch_exam_id, $subjects->subject_id);
        
        if(!empty($fa3))
        {
            if (in_array(trim(strtoupper($fa3['note'])), $note_status)) {
                $fa3_att = "-";
                $fa3_mark = 0;
                $fa3_final="-";
            }
            elseif($fa3['attendence']=="absent" || $fa3['note']=="FD")
            {
                $fa3_att = "AB";
                $fa3_mark = 0;
                $fa3_final="Ab";
            }
            else
            {
                $fa3_att = "";
                if($fa3['grace_mark']>0)
                {
                    $grace_mark=$fa3['grace_mark'];
                    $fa3_grace=$grace_mark;
                }
                else
                {$grace_mark=0;}                 
                if($fa3_sub->convertTo>0)
                {
                    $fa3_mark = round((($fa3['get_marks']+$grace_mark)/$fa3_sub->max_marks) * $fa3_sub->convertTo);
                    $t2_max_mark+=$fa3_sub->convertTo;
                }
                else
                {$fa3_mark = round($fa3['get_marks']+$grace_mark);
                
                $t2_max_mark+=$fa3_sub->max_marks;}
                $fa3_final=$fa3_mark;
            }
        }
        else
        {$fa3_mark=0;$fa3_att="";$fa3_final="-";}
        
        //end fa3
        //start fa4
        $fa4_sub=$this->batchsubject_model->getExamstudentSubjectsBYMainSubject($examList[4]->exam_group_class_batch_exam_id,$subjects->main_sub);
        $fa4 = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[4]->exam_group_class_batch_exam_id, $fa4_sub[0]->subject_id);
        if(!empty($fa4))
        {
            if (in_array(trim(strtoupper($fa4['note'])), $note_status)) {
                $fa4_att = "-";
                $fa4_mark = 0;
                $fa4_final="-";
            }
            elseif($fa4['attendence']=="absent" || $fa4['note']=="FD")
            {
                $fa4_att = "AB";
                $fa4_mark = 0;
                $fa4_final="Ab";
            }
            else
            {
                $fa4_att = "";
                if($fa4['grace_mark']>0)
                {
                    $grace_mark=$fa4['grace_mark'];
                    $fa4_grace=$grace_mark;
                }
                else
                {$grace_mark=0;}                 

                if($fa4_sub[0]->convertTo>0)
                {
                    $fa4_mark = round((($fa4['get_marks']+$grace_mark)/$fa4_sub[0]->max_marks) * $fa4_sub[0]->convertTo);
                    $t2_max_mark+=$fa4_sub[0]->convertTo;
                }
                else
                {$fa4_mark = round($fa4['get_marks']+$grace_mark);
                
                $t2_max_mark+=$fa4_sub[0]->max_marks;}
                $fa4_final=$fa4_mark;
            }
        }
        else
        {$fa4_mark=0;$fa4_att="";$fa4_final="-";}

        //end fa4        
        //start sa2

        $sa2_sub=$this->batchsubject_model->getExamSubjectsBySubjectId($examList[5]->exam_group_class_batch_exam_id,$subjects->subject_id);        
        $sa2 = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[5]->exam_group_class_batch_exam_id, $subjects->subject_id);
        if(!empty($sa2))
        {
            if (in_array(trim(strtoupper($sa2['note'])), $note_status)) {
                $sa2_att = "-";
                $sa2_mark = 0;
                $sa2_final="-";
            }
            elseif($sa2['attendence']=="absent" || $sa2['note']=="FD")
            {
                $sa2_att = "AB";
                $sa2_mark = 0;
                $sa2_final="Ab";
            }
            else
            {
                $sa2_att = "";
                if($sa2['grace_mark']>0)
                {
                    $grace_mark=$sa2['grace_mark'];
                    $sa2_grace=$grace_mark;
                }
                else
                {$grace_mark=0;}                   
                if($sa2_sub->convertTo>0)
                {
                    //echo $sa1['get_marks'];
                    $sa2_mark = round((($sa2['get_marks']+$grace_mark)/$sa2_sub->max_marks) * $sa2_sub->convertTo);
                    $t2_max_mark+=$sa2_sub->convertTo;
                }
                else
                {$sa2_mark = round($sa2['get_marks']+$grace_mark);
                
                $t2_max_mark+=$sa2_sub->max_marks;}
                $sa2_final=$sa2_mark;
            }
        }
        else
        {$sa2_mark=0;$sa2_att="";$sa2_final="-";}

        $t2_total = round($fa3_mark+$fa4_mark+$sa2_mark,2);
        $total = round($t1_total + $t2_total);

        if($t2_total>0)
        {
        $percent = round(($t2_total/$t2_max_mark)*100,2);
        $t2_grade = $this->grade_model->get_Grade($exam_grades, $percent);
        }
        if($total>0)
        {
        $net_max_mark = $t1_max_mark+$t2_max_mark;
        $percent = round(($total/$net_max_mark)*100,2);
        $grade = $this->grade_model->get_Grade($exam_grades, $percent);
        }
        $total_grace=$fa1_grace+$fa2_grace+$sa1_grace+$fa3_grace+$fa4_grace + $sa1_grace + $sa2_grace;
        if($fa1_final=="Ab" && $fa2_final=="Ab" && $sa1_final=="Ab" )
        {
            $t1_total="Ab";$t1_grade="Ab";
        }
        elseif($fa1_final=="-" && $fa2_final=="-" && $sa1_final=="-")
        {
            $t1_total="-";$t1_grade="-";
        }        
        if($fa3_final=="Ab" && $fa4_final=="Ab" && $sa2_final=="Ab" )
        {
            $t2_total="Ab";$t2_grade="Ab";
        }
        elseif($fa3_final=="-" && $fa4_final=="-" && $sa2_final=="-")
        {
            $t2_total="-";$t2_grade="-";
        }        
        if($t1_total=="Ab" && $t2_total =="Ab")
        {
            $total="Ab";$grade="Ab";
        }
        elseif($t1_total=="-" && $t2_total =="-")
        {
            $total="-";$grade="-";
        }        

        $marks = array (
            "fa1" => $fa1_mark,
            "fa2" => $fa2_mark,
            "sa1" => $sa1_mark,
            "t1" => $t1_total,
            "t1grade" => $t1_grade,
            "fa3" => $fa3_mark,
            "fa4" => $fa4_mark,
            "sa2" => $sa2_mark,
            "t2" => $t2_total,
            "total" => $total,
            "max_mark" => $net_max_mark,
            "t2grade" => $t2_grade,
            "fa1_att" => $fa1_att,            
            "fa2_att" => $fa2_att,            
            "sa1_att" => $sa1_att,            
            "fa3_att" => $fa3_att,            
            "fa4_att" => $fa4_att,            
            "sa2_att" => $sa2_att,   
            "fa1_final" => $fa1_final,
            "fa2_final" => $fa2_final,
            "fa3_final" => $fa3_final,
            "fa4_final" => $fa4_final,
            "sa1_final" => $sa1_final,
            "sa2_final" => $sa2_final,
            "fa1_grace" => $fa1_grace,            
            "fa2_grace" => $fa2_grace,            
            "sa1_grace" => $sa1_grace,            
            "fa3_grace" => $fa3_grace,            
            "fa4_grace" => $fa4_grace,            
            "sa2_grace" => $sa2_grace,   
            "total_grace" => $total_grace,   
            "grade" => $grade,
        );
        return $marks;
    }
    //end here

    public function getYearlyReport($session_id,$class_id,$section_id)
    {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,total_mark,max_mark,student_session.grade,percentage,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no, students.guardian_name , students.guardian_relation,students.guardian_phone,students.is_active ,students.father_name,students.gender')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $session_id);
        $this->db->where('students.is_active', "yes");
        $this->db->order_by('student_session.roll_no', 'asc');
        
        if ($class_id != null) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->db->where('student_session.section_id', $section_id);
        }

        $query = $this->db->get();
        return $query->result_array();
    }
    public function getYearlyReport_session($session_id,$class_id,$section_id)
    {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,total_mark,max_mark,student_session.grade,percentage,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no, students.guardian_name , students.guardian_relation,students.guardian_phone,students.is_active ,students.father_name,students.gender')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $session_id);
        //$this->db->where('student_session.is_active', "yes");
        $this->db->order_by('student_session.roll_no', 'asc');
        
        if ($class_id != null) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->db->where('student_session.section_id', $section_id);
        }

        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_studentsPercentage($class_id,$section_id,$start,$end)
    {
        $this->db->where('class_id', $class_id);
        if (!empty($section_id)) {
            $this->db->where('section_id', $section_id);
        }
        $this->db->where('session_id', $this->current_session);
        $this->db->where('is_active', 'yes');
        $this->db->where('percentage >', $start);
        $this->db->where('percentage <', $end);
        
        $query = $this->db->get('student_session');
        
        return $query->num_rows();
        
        
        
    }
}
