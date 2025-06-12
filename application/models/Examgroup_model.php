<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Examgroup_model extends MY_Model
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

        $this->db->select('exam_groups.*,(select count(*) from exam_group_class_batch_exams WHERE exam_group_class_batch_exams.exam_group_id=exam_groups.id) as `counter`,exam_type.name as examname')->from('exam_groups');
        $this->db->join('exam_type', 'exam_type.id = exam_groups.exam_type_id');

        if ($id != null) {
            $this->db->where('exam_groups.id', $id);
        } else {
            $this->db->order_by('exam_groups.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row();
        } else {
            return $query->result();
        }
    }

    public function getExamByID($id = null)
    {

        $sql = "SELECT exam_groups.name as `exam_group_name`,exam_groups.exam_type as `exam_group_type`,exam_groups.id as `exam_group_id`,exam_group_class_batch_exams.*,sessions.session FROM `exam_group_class_batch_exams` INNER JOIN exam_groups on exam_groups.id= exam_group_class_batch_exams.exam_group_id INNER JOIN sessions on sessions.id = exam_group_class_batch_exams.session_id WHERE exam_group_class_batch_exams.id=" . $this->db->escape($id);
        // $this->db->select('exam_group_class_batch_exams.*')->from('exam_group_class_batch_exams');
        $query = $this->db->query($sql);

        return $query->row();
    }

    public function getcountstudents($id)
    {
        $this->db->select('id');
        $this->db->where('exam_group_class_batch_exam_id ', $id);
        $query = $this->db->get('exam_group_class_batch_exam_students');
        return $query;
    }

    public function getMarkEntry($ids, $exam_id = "")
    {

        if (!empty($ids)) {

            $this->db->select('*');
            $this->db->from('exam_group_exam_results');
            $this->db->where('exam_group_class_batch_exams_id', $exam_id);
            $this->db->where_in('exam_group_class_batch_exam_student_id', $ids);
            $this->db->group_by('exam_group_class_batch_exam_student_id');
            $query = $this->db->get();


            // $query = $this->db->query("SELECT * FROM `exam_group_exam_results` WHERE `exam_group_class_batch_exam_student_id` IN ($idsimplode) And `exam_group_class_batch_exam_id=`$exam_id GROUP BY `exam_group_class_batch_exam_subject_id`");
            return $query;
        }
    }
    public function getstudentassign($ids)
    {

        if (!empty($ids)) {
            $this->db->select('*');
            $this->db->from('exam_group_class_batch_exam_students');
            $this->db->where_in('student_session_id', $ids);
            $this->db->group_by('student_session_id');
            $query = $this->db->get();

            // $query = $this->db->query("SELECT * FROM `exam_group_class_batch_exam_students` WHERE `student_session_id` IN ($ids) GROUP BY `student_session_id`");
            // $query = $this->db->query("SELECT * FROM `exam_group_exam_results` WHERE `exam_group_class_batch_exam_student_id` IN ($idsimplode) GROUP BY `exam_group_class_batch_exam_subject_id`");
            return $query;


            // print_r($this->db->last_query());
        }
    }

    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function remove($id)
    {
        $this->db->trans_begin();
        $this->db->where('id', $id);
        $this->db->delete('exam_groups'); //class record delete.
        $message = DELETE_RECORD_CONSTANT . " On exam groups id " . $id;
        $action = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        return true;
    }

    /**
     * This function will take the post data passed from the controller
     * If id is present, then it will do an update
     * else an insert. One function doing both add and edit.
     * @param $data
     */
    public function add($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('exam_groups', $data);
            $message = UPDATE_RECORD_CONSTANT . " On  exam groups id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('exam_groups', $data);
            $id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On exam groups id " . $id;
            $action = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);
        }

        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

    public function delete_exam($id)
    {
        $this->db->trans_start();
        $this->db->where('id', $id);
        $this->db->delete('exam_group_class_batch_exams');
        $this->db->where('exam_group_class_batch_exams_id', $id);
        $this->db->delete('exam_group_exam_results');
        $this->db->where('exam_group_class_batch_exam_id', $id);
        $this->db->delete('exam_group_class_batch_exam_students');

        $message = DELETE_RECORD_CONSTANT . " On exam groups exams name id " . $id;
        $action = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            return true;
        }
    }

    public function getsubjectlist($id)
    {
        $this->db->select('main_sub,subject_id,max_marks,min_marks');
        $this->db->where('exam_group_class_batch_exams_id', $id);
        $query = $this->db->get('exam_group_class_batch_exam_subjects')->result_array();
        if (!empty($query)) {
            return $query;
        } else {
            return false;
        }
    }
    public function getsubjectlist2($id)
    {
        $this->db->select('main_sub,subject_id,SUM(max_marks) as sum_max,SUM(convertTo) as sum_convertTo');
        $this->db->where_in('exam_group_class_batch_exams_id', $id);
        $this->db->group_by('subject_id');
        $query = $this->db->get('exam_group_class_batch_exam_subjects')->result_array();
        if (!empty($query)) {
            return $query;
        } else {
            return false;
        }
    }

    public function getstudentexammarks($id, $examstudentsids = "")
    {
        $this->db->select('exam_group_class_batch_exam_student_id,exam_group_class_batch_exam_subject_id,exam_group_class_batch_exams_id,note,subject_id,main_sub,attendence,get_marks,exam_group_class_batch_exam_students.student_id,exam_group_class_batch_exam_students.id as exam_student_id,SUM(get_marks) as sum_max');
        // $this->db->from('exam_group_exam_results');
        $this->db->join('exam_group_class_batch_exam_students', 'exam_group_class_batch_exam_students.id = exam_group_exam_results.exam_group_class_batch_exam_student_id');
        // $this->db->where_in('exam_group_class_batch_exam_student_id ', $examstudentsids);
        $this->db->where_in('exam_group_class_batch_exams_id', $id);
        $this->db->group_by('student_id');
        $query = $this->db->get('exam_group_exam_results')->result_array();
        // print_r($this->db->last_query());
        if (!empty($query)) {
            return $query;
        } else {
            return false;
        }
    }

    public function getexamlist($id = "")
    {
        $this->db->select('id,exam,exam_group_id');
        $this->db->where('mark_result', '0');
        if($id!="")
        {$this->db->where('exam_group_id', $id);}
        $query = $this->db->get('exam_group_class_batch_exams')->result_array();
        if (!empty($query)) {
            return $query;
        } else {
            return false;
        }
    }

    public function add_exam($data)
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('exam_group_class_batch_exams', $data);
            $message = UPDATE_RECORD_CONSTANT . " On  exam group exams name id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->trans_start(); # Starting Transaction
            $this->db->trans_strict(false); # See Note 01. If you wish can remove as well

            $exam_group = $this->examgroup_model->get($data['exam_group_id']);
            $this->db->insert('exam_group_class_batch_exams', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On exam group exams name id " . $insert_id;
            $action = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);
            // if ($exam_group->exam_type != "coll_grade_system") {
            //     $batch_subjects = $this->batchsubject_model->getClassBatchSubjects($data['class_batch_id']);
            //     if (!empty($batch_subjects)) {
            //         $exam_subjects = array();
            //         foreach ($batch_subjects as $batch_subject_key => $batch_subject_value) {
            //             $exam_subjects[] = array(
            //                 'exam_group_class_batch_exams_id' => $insert_id,
            //                 'class_batch_subject_id'          => $batch_subject_value->id,
            //             );
            //         }
            //         if (!empty($exam_subjects)) {
            //             $this->db->insert_batch('exam_group_class_batch_exam_subjects', $exam_subjects);
            //         }
            //     }
            // }
            $this->db->trans_complete(); # Completing transaction

            /* Optional */

            if ($this->db->trans_status() === false) {
                # Something went wrong.
                $this->db->trans_rollback();
                return false;
            } else {
                # Everything is Perfect.
                # Committing data to the database.
                $this->db->trans_commit();
                return $insert_id;
            }
        }
    }

    public function insertsubject($insert_array)
    {
        if (!empty($insert_array)) {
            $this->db->insert_batch('exam_group_class_batch_exam_subjects', $insert_array);
        } else {
            return false;
        }
    }
    public function insertstudentmarks($insert_array)
    {
        if (!empty($insert_array)) {
            $this->db->insert_batch('exam_group_exam_results', $insert_array);
        } else {
            return false;
        }
    }

    public function checkassignstudent($exam_id)
    {
        $this->db->where('exam_group_class_batch_exam_id ', $exam_id);
        $dataArray = $this->db->get('exam_group_class_batch_exam_students');
        return $dataArray;
    }
    public function checkassignstudentclass($exam_id, $class_id, $section_id, $session_id)
    {

        $result = $this->db->query("select exam_group_class_batch_exam_students.id from exam_group_class_batch_exam_students  inner join student_session on exam_group_class_batch_exam_students.student_session_id = student_session.id where exam_group_class_batch_exam_students.exam_group_class_batch_exam_id = '$exam_id' and student_session.class_id = '$class_id' and student_session.section_id = '$section_id' and  student_session.session_id = '$session_id'")->result_array();

        return $result;
    }
    public function getExamByExamGroup($id, $is_active = false)
    {
        $this->db->select('exam_group_class_batch_exams.*,sessions.session,(select COUNT(*) from exam_group_class_batch_exam_subjects WHERE exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id = exam_group_class_batch_exams.id) as `total_subjects`')->from('exam_group_class_batch_exams');
        $this->db->join('sessions', 'sessions.id = exam_group_class_batch_exams.session_id');
        if ($is_active) {
            $this->db->where('exam_group_class_batch_exams.is_active', $is_active);
        }
        $this->db->where('exam_group_class_batch_exams.exam_group_id', $id);
        $this->db->order_by('exam_group_class_batch_exams.exam_group_id');

        $query = $this->db->get();

        return $query->result();
    }

    public function getBacklogExam($parent_exam_id)
    {

        $this->db->select()->from('exam_group_class_batch_exams');
        $this->db->where('parent_exam_id', $parent_exam_id);
        $query = $this->db->get();
        return $query->result();
    }

    public function getExamGroupDetailByID($id)
    {

        $this->db->select()->from('exam_groups');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            $result = $query->row();
            $result->exams = $this->getExamByExamGroup($result->id);
            return $result;
        }
        return false;
    }

    public function verifyExamConnection($exam_array)
    {

        $sql = "SELECT exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id,exam_group_class_batch_exam_subjects.subject_id,count(subject_id) as subject_count FROM `exam_group_class_batch_exam_subjects` WHERE exam_group_class_batch_exams_id in(" . implode(",", $exam_array) . ") GROUP by subject_id,exam_group_class_batch_exams_id";

        $query = $this->db->query($sql);
        $result = $query->result();

        $sub_array = array();
        $exams_array = array();
        $ex_array = array();
        $no_record = 0;
        if (!empty($result)) {
            $no_record = 1;
            foreach ($result as $result_key => $result_value) {
                $exams_array[$result_value->exam_group_class_batch_exams_id] = $result_value->exam_group_class_batch_exams_id;
                $ex_array[$result_value->exam_group_class_batch_exams_id][$result_value->subject_id] = $result_value->subject_count;
            }
        }
        return array('sub_array' => $sub_array, 'exams_array' => $exams_array, 'exam_subject_array' => $ex_array, 'no_record' => $no_record);
    }

    public function getExamByExamGroupConnection($id = null)
    {

        $this->db->select('exam_group_class_batch_exams.*,IFNULL(exam_group_exam_connections.id,0) as `exam_group_exam_connection_id`,IFNULL(exam_group_exam_connections.exam_weightage,"0.00") as exam_weightage,(select COUNT(*) from exam_group_class_batch_exam_subjects WHERE exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id = exam_group_class_batch_exams.id) as `total_subjects`')->from('exam_group_class_batch_exams');
        $this->db->join('exam_group_exam_connections', 'exam_group_exam_connections.exam_group_id = exam_group_class_batch_exams.exam_group_id and exam_group_exam_connections.exam_group_class_batch_exams_id = exam_group_class_batch_exams.id', 'left');
        $this->db->where('exam_group_class_batch_exams.exam_group_id', $id);
        $this->db->order_by('exam_group_class_batch_exams.id', 'asc');

        $query = $this->db->get();

        return $query->result();
    }
    public function getExamByExamGroupConnection1($id = null)
    {

        $this->db->select('exam_group_class_batch_exams.*,IFNULL(exam_group_exam_connections.id,0) as `exam_group_exam_connection_id`,IFNULL(exam_group_exam_connections.exam_weightage,"0.00") as exam_weightage,(select COUNT(*) from exam_group_class_batch_exam_subjects WHERE exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id = exam_group_class_batch_exams.id) as `total_subjects`')->from('exam_group_class_batch_exams');
        $this->db->join('exam_group_exam_connections', 'exam_group_exam_connections.exam_group_id = exam_group_class_batch_exams.exam_group_id and exam_group_exam_connections.exam_group_class_batch_exams_id = exam_group_class_batch_exams.id', 'left');
        $this->db->where('exam_group_class_batch_exams.exam_group_id', $id);
        $this->db->where('exam_group_class_batch_exams.mark_result !=', 1);
        $this->db->order_by('exam_group_class_batch_exams.id', 'asc');

        $query = $this->db->get();

        return $query->result();
    }
    public function getExamByExamGroupConnection2($id = null)
    {

        $this->db->select('exam_group_class_batch_exams.*,IFNULL(exam_group_exam_connections.id,0) as `exam_group_exam_connection_id`,IFNULL(exam_group_exam_connections.exam_weightage,"0.00") as exam_weightage,(select COUNT(*) from exam_group_class_batch_exam_subjects WHERE exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id = exam_group_class_batch_exams.id) as `total_subjects`')->from('exam_group_class_batch_exams');
        $this->db->join('exam_group_exam_connections', 'exam_group_exam_connections.exam_group_id = exam_group_class_batch_exams.exam_group_id and exam_group_exam_connections.exam_group_class_batch_exams_id = exam_group_class_batch_exams.id', 'left');
        $this->db->where('exam_group_class_batch_exams.exam_group_id', $id);
        $this->db->where('exam_group_class_batch_exams.mark_result', 1);
        $this->db->order_by('exam_group_class_batch_exams.id', 'asc');

        $query = $this->db->get();

        return $query->result();
    }

    public function getExamGroupConnectionList($exam_group_id = null)
    {

        $this->db->select('exam_group_exam_connections.*')->from('exam_group_exam_connections');
        $this->db->where('exam_group_exam_connections.exam_group_id', $exam_group_id);
        $this->db->order_by('exam_group_exam_connections.id', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    public function connectExam($insert_array, $exam_group_id)
    {
        $not_be_delted = array();
        if (!empty($insert_array)) {

            foreach ($insert_array as $array_key => $array_value) {
                $this->db->where('exam_group_id', $array_value['exam_group_id']);
                $this->db->where('exam_group_class_batch_exams_id', $array_value['exam_group_class_batch_exams_id']);
                $q = $this->db->get('exam_group_exam_connections');

                if ($q->num_rows() == 0) {

                    $this->db->insert('exam_group_exam_connections', $insert_array[$array_key]);
                    $not_be_delted[] = $array_value['exam_group_class_batch_exams_id'];
                } else {
                    $id = $q->row()->id;
                    $exam_group_class_batch_exams_id = $q->row()->exam_group_class_batch_exams_id;
                    $this->db->where('id', $id);
                    $this->db->update('exam_group_exam_connections', $insert_array[$array_key]);
                    $not_be_delted[] = $exam_group_class_batch_exams_id;
                }
            }
        }

        if (!empty($not_be_delted)) {

            $this->db->where('exam_group_id', $exam_group_id);
            $this->db->where_not_in('exam_group_class_batch_exams_id', $not_be_delted);
            $this->db->delete('exam_group_exam_connections');
        } else {
            $this->db->where('exam_group_id', $exam_group_id);
            $this->db->delete('exam_group_exam_connections');
        }
    }

    public function deleteExamGroupConnection($exam_group_id)
    {
        $this->db->where('exam_group_id', $exam_group_id);
        $this->db->delete('exam_group_exam_connections');
    }

    public function getExamGroupByStudent($student_id, $active = 1)
    {

        $this->db->select('exam_group_students.*,exam_groups.name,exam_groups.exam_type,exam_groups.exam_type')->from('exam_group_students');
        $this->db->join('exam_groups', 'exam_groups.id = exam_group_students.exam_group_id');
        $this->db->where('student_session_id', $student_id);
        $this->db->where('exam_groups.is_active', $active);
        $query = $this->db->get();

        return $query->result();
    }

    public function getExamGroupByStudentSession($student_session_id, $active = 1)
    {

        $this->db->select('exam_group_students.*,exam_groups.name,exam_groups.exam_type,exam_groups.exam_type')->from('exam_group_students');
        $this->db->join('exam_groups', 'exam_groups.id = exam_group_students.exam_group_id');
        $this->db->where('student_session_id', $student_session_id);
        $this->db->where('exam_groups.is_active', $active);
        $query = $this->db->get();

        $exam_groups = $query->result();
        $exam_results = array();
        if (!empty($exam_groups)) {
            foreach ($exam_groups as $exam_group_key => $exam_group_value) {
                $exam_groups[$exam_group_key]->exam_group_connection = $this->getExamGroupConnection($exam_group_value->exam_group_id);
                $exam_groups[$exam_group_key]->exam_results = $this->getExamGroupExamsResultByStudentID($exam_group_value->exam_group_id, $student_session_id);
            }
            return $exam_groups;
        }
        return false;
    }

    public function getExamResultStudent($exam_group_exam_id, $exam_group_id, $student_id)
    {

        $sql = "SELECT `exam_group_class_batch_exam_subjects`.*,IFNULL(exam_group_student.id, 0) as exam_group_exam_result_id,exam_group_student.get_marks,exam_group_student.attendence,exam_group_student.note,subjects.id as `subject_id`,subjects.`name`,subjects.`code` FROM `exam_group_class_batch_exam_subjects` LEFT join (SELECT exam_group_exam_results.* FROM `exam_group_students` INNER JOIN exam_group_exam_results on exam_group_exam_results.exam_group_student_id = exam_group_students.id WHERE exam_group_students.exam_group_id=" . $this->db->escape($exam_group_id) . " and exam_group_students.student_session_id =" . $this->db->escape($student_id) . " ORDER BY `exam_group_id`) as `exam_group_student` on exam_group_student.exam_group_class_batch_exam_subject_id =exam_group_class_batch_exam_subjects.id INNER join subjects on subjects.id= exam_group_class_batch_exam_subjects.subject_id WHERE exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id=" . $this->db->escape($exam_group_exam_id) . " ORDER BY `exam_group_class_batch_exams_id`";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getExamResultDetailStudent($exam_group_exam_id, $exam_group_id, $student_id)
    {

        $this->db->select('exam_groups.*')->from('exam_groups');

        $this->db->where('id', $exam_group_id);

        $query = $this->db->get();

        $exam_group = $query->row();
        $exam_group->exam_results = $this->getExamResultStudent($exam_group_exam_id, $exam_group_id, $student_id);
        return $exam_group;
    }

    public function getExamGroupExamsResultByStudentID($exam_group_id, $student_id)
    {
        $exam_group_exams = $this->getExamByExamGroup($exam_group_id, 1);
        if (!empty($exam_group_exams)) {
            foreach ($exam_group_exams as $exam_key => $exam_value) {
                $exam_group_exams[$exam_key]->exam_results = $this->getExamResultStudent($exam_value->id, $exam_value->exam_group_id, $student_id);
            }
        }
        return $exam_group_exams;
    }

    public function getExamGroupConnection($exam_group_id)
    {

        $result_array = array();
        $sql = "SELECT exam_group_exam_connections.*,exam_group_class_batch_exams.id as `exam_group_class_batch_exam_id`,exam_group_class_batch_exams.exam,exam_group_class_batch_exams.description FROM `exam_group_exam_connections` INNER JOIN exam_group_class_batch_exams on exam_group_class_batch_exams.id = exam_group_exam_connections.exam_group_class_batch_exams_id WHERE exam_group_exam_connections.exam_group_id=" . $exam_group_id;
        $query = $this->db->query($sql);
        $result = $query->result();
        $result_array['exam_connections'] = $result;
        if (!empty($result)) {
            $sql_inner = "SELECT exam_group_exam_connections.*,exam_group_class_batch_exam_subjects.id as exam_group_class_batch_exam_subject_id,exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id,exam_group_class_batch_exam_subjects.subject_id,exam_group_class_batch_exam_subjects.credit_hours,exam_group_class_batch_exam_subjects.date_from,exam_group_class_batch_exam_subjects.date_from,exam_group_class_batch_exam_subjects.date_to,exam_group_class_batch_exam_subjects.room_no,exam_group_class_batch_exam_subjects.max_marks,exam_group_class_batch_exam_subjects.max_marks,subjects.name,subjects.code FROM `exam_group_exam_connections`INNER JOIN exam_group_class_batch_exams on exam_group_class_batch_exams.id=exam_group_exam_connections.exam_group_class_batch_exams_id INNER JOIN exam_group_class_batch_exam_subjects on exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id = exam_group_class_batch_exams.id  INNER JOIN subjects on subjects.id=exam_group_class_batch_exam_subjects.subject_id WHERE exam_group_exam_connections.exam_group_id=" . $exam_group_id . " GROUP BY exam_group_class_batch_exam_subjects.subject_id";
            $query = $this->db->query($sql_inner);
            $result_array['connect_subjects'] = $query->result();
        }

        return $result_array;
    }

    public function getExamGroupByClassSection($class_id, $section_id, $session_id)
    {

        $result_array = array();
        $sql = "SELECT student_session.*,exam_group_students.exam_group_id,exam_groups.name FROM `student_session` INNER join exam_group_students on exam_group_students.student_id=student_session.student_id INNER JOIN exam_groups on exam_groups.id=exam_group_students.exam_group_id WHERE class_id= " . $this->db->escape($class_id) . " and section_id=" . $this->db->escape($section_id) . " and session_id=" . $this->db->escape($session_id) . " GROUP BY exam_group_students.exam_group_id";
        $query = $this->db->query($sql);

        $result = $query->result();
        return $result;
    }

    public function getTeachersubject($current_session)
    {
        $this->db->select('teacher_subjects.*,classes.class,sections.section');
        $this->db->join('class_sections', 'class_sections.id=teacher_subjects.class_section_id  ');
        $this->db->join('subjects', 'subjects.id=teacher_subjects.subject_id');
        $this->db->join('classes', 'classes.id=class_sections.class_id');
        $this->db->join('sections', 'sections.id=class_sections.section_id ');
        $this->db->group_by('class_sections.class_id,section_id');
        $this->db->where('teacher_id', $current_session);
        $dataArray = $this->db->get('teacher_subjects');
        return $dataArray;
    }
    public function getstudentscount($class_id, $section_id)
    {
        $this->db->where('class_id ', $class_id);
        $this->db->where('section_id', $section_id);
        $query = $this->db->get('student_session');
        return $query;
    }
    public function getexam($class_id, $section_id)
    {
        
        $this->db->select('exam_group_class_batch_exam_students.exam_group_class_batch_exam_id,student_session.session_id,class_id,section_id,student_session.student_id,exam_group_class_batch_exams.exam,exam_group_class_batch_exams.exam_srno');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('exam_group_class_batch_exam_students', 'exam_group_class_batch_exam_students.student_session_id = student_session.id ');
        $this->db->join('exam_group_class_batch_exams', 'exam_group_class_batch_exams.id = exam_group_class_batch_exam_students.exam_group_class_batch_exam_id ');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('student_session.class_id', $class_id);
        $this->db->where('student_session.section_id', $section_id);
        
        $this->db->group_by('exam_group_class_batch_exam_id');
        $this->db->order_by('exam_group_class_batch_exams.exam_srno');
        $query = $this->db->get();
        // print_r($this->db->last_query());die;
        return $query->result_array();
    }
    public function getexam_new($class_id, $section_id)
    {
        $this->db->select('exam_group_class_batch_exam_students.exam_group_class_batch_exam_id,student_session.session_id,class_id,section_id,student_session.student_id,exam_group_class_batch_exams.exam,exam_group_class_batch_exams.exam_srno');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('exam_group_class_batch_exam_students', 'exam_group_class_batch_exam_students.student_session_id = student_session.id ');
        $this->db->join('exam_group_class_batch_exams', 'exam_group_class_batch_exams.id = exam_group_class_batch_exam_students.exam_group_class_batch_exam_id ');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('student_session.class_id', $class_id);
        $this->db->where('student_session.section_id', $section_id);
        $this->db->where('exam_group_class_batch_exams.mark_result', '0');
        $this->db->group_by('exam_group_class_batch_exam_id');
        $this->db->order_by('exam_group_class_batch_exams.exam_srno');
        $query = $this->db->get();
        // print_r($this->db->last_query());die;
        return $query->result_array();
    }    
    public function getexamwithgroup($class_id, $section_id)
    {
        $this->db->select('exam_group_class_batch_exam_students.exam_group_class_batch_exam_id,student_session.session_id,class_id,section_id,student_session.student_id,exam_group_class_batch_exams.exam,exam_group_class_batch_exams.exam_srno,exam_groups.name ');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('exam_group_class_batch_exam_students', 'exam_group_class_batch_exam_students.student_session_id = student_session.id ');
        $this->db->join('exam_group_class_batch_exams', 'exam_group_class_batch_exams.id = exam_group_class_batch_exam_students.exam_group_class_batch_exam_id ');
        $this->db->join('exam_groups', 'exam_group_class_batch_exams.exam_group_id = exam_groups.id ');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('student_session.class_id', $class_id);
        $this->db->where('student_session.section_id', $section_id);
        $this->db->group_by('exam_group_class_batch_exam_id');
        $this->db->order_by('exam_group_class_batch_exams.exam_srno');
        $query = $this->db->get();
        // print_r($this->db->last_query());die;
        return $query->result_array();
    }
    public function getexam_onlyentry($class_id, $section_id)
    {
        $this->db->select('exam_group_class_batch_exam_students.exam_group_class_batch_exam_id,student_session.session_id,class_id,section_id,student_session.student_id,exam_group_class_batch_exams.exam,exam_group_class_batch_exams.exam_srno');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('exam_group_class_batch_exam_students', 'exam_group_class_batch_exam_students.student_session_id = student_session.id ');
        $this->db->join('exam_group_class_batch_exams', 'exam_group_class_batch_exams.id = exam_group_class_batch_exam_students.exam_group_class_batch_exam_id ');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('student_session.class_id', $class_id);
        $this->db->where('student_session.section_id', $section_id);
        $this->db->where('exam_group_class_batch_exams.mark_result', 0);
        $this->db->group_by('exam_group_class_batch_exam_id');
        $this->db->order_by('exam_group_class_batch_exams.exam_srno');
        $query = $this->db->get();
        // print_r($this->db->last_query());die;
        return $query->result_array();
    }
    public function getexam_only_result($class_id, $section_id)
    {
        $this->db->select('exam_group_class_batch_exam_students.exam_group_class_batch_exam_id,student_session.session_id,class_id,section_id,student_session.student_id,exam_group_class_batch_exams.exam,exam_group_class_batch_exams.exam_srno');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('exam_group_class_batch_exam_students', 'exam_group_class_batch_exam_students.student_session_id = student_session.id ');
        $this->db->join('exam_group_class_batch_exams', 'exam_group_class_batch_exams.id = exam_group_class_batch_exam_students.exam_group_class_batch_exam_id ');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('student_session.class_id', $class_id);
        $this->db->where('student_session.section_id', $section_id);
        $this->db->where('exam_group_class_batch_exams.mark_result', 1);
        $this->db->group_by('exam_group_class_batch_exam_id');
        $this->db->order_by('exam_group_class_batch_exams.exam_srno');
        $query = $this->db->get();
        // print_r($this->db->last_query());die;
        return $query->result_array();
    }
    public function getexam_evaluation_from_exams($exams)
    {
        $this->db->select('*');
        $this->db->from('exam_group_class_batch_exams');
        $this->db->where_in('id', $exams);
        $query = $this->db->get();
        $res = $query->result_array();
        $exam_group = array();
        foreach ($res as $r) {
            $exam_group[] = $r['exam_group_id'];
        }
        $this->db->select('id as exam_group_class_batch_exam_id');
        $this->db->from('exam_group_class_batch_exams');
        $this->db->where_in('exam_group_id', $exam_group);
        $this->db->where('mark_result', 1);
        $this->db->order_by('exam_srno');
        $query = $this->db->get();
        $res = $query->result_array();
        return $res;
    }
    public function getsubjectbyvalue($id)
    {
        $this->db->where('main_sub', $id);
        $query = $this->db->get('exam_group_exam_results');
        return $query;
    }

    public function getstudentassign_ids($exam_id)
    {
        $this->db->select('*');
        $this->db->where('exam_group_class_batch_exam_id', $exam_id);
        $query = $this->db->get('exam_group_class_batch_exam_students')->result_array();
        if (!empty($query)) {
            return $query;
        } else {
            return false;
        }
    }
    public function getstudentassign_subids($exam_id)
    {
        $this->db->select('*');
        $this->db->where('exam_group_class_batch_exams_id', $exam_id);
        $query = $this->db->get('exam_group_class_batch_exam_subjects')->result_array();
        if (!empty($query)) {
            return $query;
        } else {
            return false;
        }
    }

    public function getexamsgroup($class_id, $section_id, $session_id)
    {
        $this->db->select('exam_group_class_batch_exam_students.*');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('exam_group_class_batch_exam_students', 'student_session.id = exam_group_class_batch_exam_students.student_session_id');
        $this->db->join('exam_group_class_batch_exams', 'exam_group_class_batch_exams.id = exam_group_class_batch_exam_students.exam_group_class_batch_exam_id');
        $this->db->where('student_session.class_id', $class_id);
        $this->db->where('student_session.section_id', $section_id);
        $this->db->where('student_session.session_id', $session_id);
        $this->db->where('exam_group_class_batch_exams.mark_result', 1);
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function getstudentby($ids, $class_id, $section_id)
    {
        $this->db->select('id');
        $this->db->where_in('student_id', $ids);
        $this->db->where('class_id', $class_id);
        $this->db->where('section_id', $section_id);
        $query = $this->db->get('student_session')->result_array();
        // echo $this->db->last_query();

        return $query;
    }
    public function getidsbyexamStudents($ids)
    {
        $this->db->select('id');
        $this->db->where_in('student_session_id', $ids);
        $query = $this->db->get('exam_group_class_batch_exam_students')->result_array();

        return $query;
    }

    /*  public function getstudentexamid($student_array)
    {
        $this->db->select('exam_group_class_batch_exam_students.*');
        $this->db->join('exam_group_class_batch_exams', 'exam_group_class_batch_exam_students.exam_group_class_batch_exam_id  = exam_group_class_batch_exams.id');
        $this->db->where('student_id', $student_array);
        $this->db->where('exam_group_class_batch_exams.mark_result', 1);
        
        $query = $this->db->get('exam_group_class_batch_exam_students')->result_array();
        
        return $query;
    } */

    public function getresultmarks($examstudent_id)
    {
        $this->db->select('exam_group_exam_results.*,subjects.name');
        $this->db->join('subjects', 'subjects.id = exam_group_exam_results.subject_id');
        $this->db->where('exam_group_class_batch_exam_student_id', $examstudent_id);
        $query =  $this->db->get('exam_group_exam_results')->result_array();
        return $query;
    }

    public function get_main_subject($id)
    {
        $this->db->select('id,name');
        $this->db->where('id', $id);
        return $this->db->get('subjects')->row_array();
    }

    public function get_id($table, $where, $id)
    {
        $this->db->select('*');
        $this->db->where($where, $id);
        return $this->db->get($table);
    }

    public function getmarks($examstudent_id, $examsubject_id)
    {
        $this->db->select('*');
        $this->db->where('exam_group_class_batch_exam_student_id', $examstudent_id);
        $this->db->where('subject_id', $examsubject_id);
        return $this->db->get('exam_group_exam_results')->row_array();
    }

    public function checksubject($exam_id, $childRow)
    {
        $this->db->select('*');
        $this->db->where('exam_group_class_batch_exams_id', $exam_id);
        $this->db->where('subject_id', $childRow);
        return $this->db->get('exam_group_class_batch_exam_subjects')->row_array();
    }
    public function checksubject_one($exam_id, $childRow)
    {
        $this->db->select('*');
        $this->db->where('exam_group_class_batch_exams_id', $exam_id);
        $this->db->where('main_sub', $childRow);
        return $this->db->get('exam_group_class_batch_exam_subjects')->row_array();
    }

    public function getstudentdetail($exam_studentid)
    {
        $this->db->select('*');
        $this->db->where('id', $exam_studentid);
        return $this->db->get('exam_group_class_batch_exam_students')->row_array();
    }
    public function getstudentAlldetail($studentid)
    {
        $this->db->select('*');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->where('students.id', $studentid);
        return $this->db->get('students')->row_array();
    }

    public function getstudentSubjects($exam_id, $exam_studentid)
    {
        $this->db->select('exam_group_class_batch_exam_subjects.*,subjects.name,code');
        $this->db->join('subjects', 'subjects.id = exam_group_class_batch_exam_subjects.subject_id');
        $this->db->where('exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id', $exam_id);
        return $this->db->get('exam_group_class_batch_exam_subjects')->result_array();
    }
    public function getstudentSubjectslist($exam_id)
    {
        $this->db->select('exam_group_class_batch_exam_subjects.*,subjects.name,code');
        $this->db->join('subjects', 'subjects.id = exam_group_class_batch_exam_subjects.subject_id');
        $this->db->where('exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id', $exam_id);
        $this->db->order_by('exam_group_class_batch_exam_subjects.main_sub', 'ASC');

        return $this->db->get('exam_group_class_batch_exam_subjects')->result_array();
    }
    public function getstudentSubjectslist2($exam_id)
    {
        $this->db->select('exam_group_class_batch_exam_subjects.*,subjects.name,code');
        $this->db->join('subjects', 'subjects.id = exam_group_class_batch_exam_subjects.subject_id');
        $this->db->where('exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id', $exam_id);
        $this->db->group_by('exam_group_class_batch_exam_subjects.main_sub');

        return $this->db->get('exam_group_class_batch_exam_subjects')->result_array();
    }
    /* alex   */
    public function add_exam_type($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('exam_type', $data);
            $message = UPDATE_RECORD_CONSTANT . " On  exam type id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('exam_type', $data);
            $id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On exam type id " . $id;
            $action = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);
        }

        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }


    public function getexamType($id = null)
    {
        $this->db->select()->from('exam_type');
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

    public function remove_examType($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('exam_type');
        $message = DELETE_RECORD_CONSTANT . " On exam type id " . $id;
        $action = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

    public function getexamcategory($id = null)
    {
        $this->db->select()->from('exam_category_master');
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


    public function add_exam_categry($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('exam_category_master', $data);
            $message = UPDATE_RECORD_CONSTANT . " On  exam categry master id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('exam_category_master', $data);
            $id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On exam categry master id " . $id;
            $action = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);
        }

        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

    public function remove_examCategory($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('exam_category_master');
        $message = DELETE_RECORD_CONSTANT . " On exam category master id " . $id;
        $action = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

    public function getexamcategoryArray($id = null)
    {
        $this->db->select()->from('exam_category_master');
        if ($id != null) {
            $this->db->where('group_type_id', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();

        return $query->result_array();
    }
    public function getclass_wise_exam_pattern($id = null)
    {
        $this->db->select()->from('class_wise_exam_pattern');
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

    public function add_exam_pattern($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (!empty($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('class_wise_exam_pattern', $data);
            $message = UPDATE_RECORD_CONSTANT . " On  class wise exam pattern " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('class_wise_exam_pattern', $data);
            $id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On class wise exam pattern " . $id;
            $action = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);
        }

        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

    public function remove_exampattern($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('class_wise_exam_pattern');
        $message = DELETE_RECORD_CONSTANT . " On class wise exam pattern " . $id;
        $action = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

    public function getexam_scheme($id = null)
    {
        $this->db->select()->from('exam_scheme');
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

    public function add_exam_scheme($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if ((!empty($data['id']))) {
            $this->db->where('id', $data['id']);
            $this->db->update('exam_scheme', $data);
            $message = UPDATE_RECORD_CONSTANT . " On   exam scheme " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('exam_scheme', $data);
            $id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On  exam scheme " . $id;
            $action = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);
        }

        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

    public function remove_examscheme($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('exam_scheme');
        $message = DELETE_RECORD_CONSTANT . " On exam scheme  " . $id;
        $action = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

    public function getclass_exam_pattern($id = null)
    {
        $this->db->select('class_wise_exam_pattern.id,classes.class,exam_scheme.name')->from('class_wise_exam_pattern');
        $this->db->join('classes', 'classes.id = class_wise_exam_pattern.class_id', 'left');
        $this->db->join('exam_scheme', 'exam_scheme.id = class_wise_exam_pattern.exam_pattern');
        $this->db->where('session_id', $this->current_session);
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
    public function get_class_exam_pattern($class_id)
    {
        $this->db->select('exam_scheme.name')->from('class_wise_exam_pattern');
        $this->db->join('classes', 'classes.id = class_wise_exam_pattern.class_id', 'left');
        $this->db->join('exam_scheme', 'exam_scheme.id = class_wise_exam_pattern.exam_pattern');
        $this->db->where('class_id', $class_id);
        $this->db->where('session_id', $this->current_session);
        $query = $this->db->get();
        if (!empty($query)) {
            return $query->row_array();
        } else {
            return "";
        }
    }

    public function statusupdate($array, $student_id)
    {
        // $this->db->update_batch('student_session', $array, 'student_id');
        $this->db->where('id', $student_id);
        $this->db->update('student_session', $array);
        return $this->db->affected_rows();
    }
    public function get_session_start_date($class_id)
    {
        $result = $this->db->query("select * from newsession_start_date where session_id = '".$this->current_session."' order by sch_section_id")->result_array();
        if ($class_id <= 2) {
            return $result[0];
        }elseif ($class_id <= 8) {
            return $result[1];
        }else {
            return $result[2];
        }
    }
    public function get_result_date($class_id)
    {
        $result = $this->db->query("select * from result_date  where session_id = '".$this->current_session."' order by sch_section_id")->result_array();
        if ($class_id <= 3) {
            return $result[0];
        }elseif ($class_id <= 8) {
            return $result[1];
        }else {
            return $result[2];
        }
    }    
    public function getsubject_teacher($class_id, $section_id, $staff_id, $health_id)
    {
        $this->db->select('*');
        $this->db->join('class_sections', 'class_sections.id = teacher_subjects.class_section_id');
        $this->db->where('class_sections.class_id', $class_id);
        $this->db->where('class_sections.section_id', $section_id);
        $this->db->where('teacher_subjects.teacher_id', $staff_id);
        $this->db->where('teacher_subjects.subject_id', $health_id);
        return $this->db->get('teacher_subjects')->num_rows();
    }

    public function add_sch_section($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if ((!empty($data['id']))) {
            $this->db->where('id', $data['id']);
            $this->db->update('sch_section', $data);
            $message = UPDATE_RECORD_CONSTANT . " On   sch_section " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('sch_section', $data);
            $id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On  sch_section " . $id;
            $action = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);
        }

        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

    public function getsch_section($id = null)
    {
        $this->db->select()->from('sch_section');
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

    public function remove_sch_section($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('sch_section');
        $message = DELETE_RECORD_CONSTANT . " On sch section  " . $id;
        $action = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

    public function getnewyearsession($id = null)
    {
        $this->db->select('newsession_start_date.*,sch_section.sch_section')->from('newsession_start_date');
        $this->db->join('sch_section', 'sch_section.id = newsession_start_date.sch_section_id');
        $this->db->where('session_id',$this->current_session);
        if ($id != null) {
            $this->db->where('newsession_start_date.id', $id);
        } else {
            $this->db->order_by('newsession_start_date.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function add_newyearsession($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if ((!empty($data['id']))) {
            $this->db->where('id', $data['id']);
            $this->db->update('newsession_start_date', $data);
            $message = UPDATE_RECORD_CONSTANT . " On   newsession_start_date " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('newsession_start_date', $data);
            $id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On  newsession_start_date " . $id;
            $action = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);
        }

        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

    public function remove_newsession_start_date($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('newsession_start_date');
        $message = DELETE_RECORD_CONSTANT . " On newsession start date  " . $id;
        $action = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

    public function getexam_resultArray($exam_id, $main_sub, $examstudent_id)
    {
        $this->db->where('exam_group_class_batch_exams_id', $exam_id);
        $this->db->where('main_sub', $main_sub);
        $this->db->where('exam_group_class_batch_exam_student_id', $examstudent_id);
        return $this->db->get('exam_group_exam_results')->row_array();
    }

    public function approve_status($exam_id, $main_sub, $class_id = null, $section_id = null)
    {
        // $this->db->set('head_lock_status',1);
        // $this->db->select('exam_group_exam_results.id');
        // $this->db->join('exam_group_class_batch_exam_students', 'exam_group_exam_results.exam_group_class_batch_exam_student_id = exam_group_class_batch_exam_students.id');
        // $this->db->join('student_session', 'exam_group_class_batch_exam_students.student_session_id = student_session.id');
        // if ($class_id != null) {
        //     $this->db->where('student_session.class_id', $class_id);
        // }
        // if ($section_id != null) {
        //     $this->db->where('student_session.section_id', $section_id);
        // }
        // $this->db->where('exam_group_exam_results.exam_group_class_batch_exam_id', $exam_id);
        // $this->db->where('exam_group_exam_results.main_sub', $main_sub);
        // $arr = $this->db->get()->result_array();
        // print_r($arr);
        // $this->db->select('id');
        // if ($class_id != null) {
        //     $this->db->where('class_id', $class_id);
        // }
        // if ($section_id != null) {
        //     $this->db->where('section_id', $section_id);
        // }
        // $student_session = $this->db->get('student_session')->result_array(); // 3
        // $student_session_column = array_column($student_session,'id');

        // $this->db->select('id');
        // $this->db->where_in('student_session_id',$student_session_column);
        // $exam_group_class_batch_exam_students = $this->db->get('exam_group_class_batch_exam_students')->result_array();
        // $exam_group_class_batch_exam_students_column = array_column($exam_group_class_batch_exam_students,'id'); // 2
        // print_r($exam_group_class_batch_exam_students_column);

        // $this->db->set('head_lock_status',1);
        // if (!empty($exam_group_class_batch_exam_students_column)) {
        //     $this->db->where_in('exam_group_class_batch_exam_student_id', $exam_group_class_batch_exam_students_column);
        // }
        // $this->db->where('exam_group_class_batch_exam_id', $exam_id);
        // $this->db->where('main_sub', $main_sub);
        // $this->db->update('exam_group_exam_results');

        // echo $this->db->last_query();die;


    }
    public function sendmsg()
    {
        $no = "918286006099";
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://app-server.wati.io/api/v1/sendTemplateMessage?whatsappNumber=' . $no,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "template_name": "about_wati2",
                "broadcast_name": "testing",
                "parameters": [
                    {
                        "name": "name",
                        "value": "Sandeep"
                    }
                ]
            }',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiI4OWU2ZDUyMC00YTA5LTQ0YTMtOTBhNC1jZTZjOGJmODdjZTEiLCJ1bmlxdWVfbmFtZSI6Imd1cHRhcnVwZXNoMjc1QGdtYWlsLmNvbSIsIm5hbWVpZCI6Imd1cHRhcnVwZXNoMjc1QGdtYWlsLmNvbSIsImVtYWlsIjoiZ3VwdGFydXBlc2gyNzVAZ21haWwuY29tIiwiYXV0aF90aW1lIjoiMDIvMjAvMjAyMyAxNToxOTowNyIsImRiX25hbWUiOiJ3YXRpX2FwcF90cmlhbCIsImh0dHA6Ly9zY2hlbWFzLm1pY3Jvc29mdC5jb20vd3MvMjAwOC8wNi9pZGVudGl0eS9jbGFpbXMvcm9sZSI6IlRSSUFMIiwiZXhwIjoxNjc3NTQyNDAwLCJpc3MiOiJDbGFyZV9BSSIsImF1ZCI6IkNsYXJlX0FJIn0.qUxRC2ktu8tvZwAA7kkWqMFkvWQh2D23O4OJDyaBihs',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }
    public function sendfile()
    {


        $curl = curl_init();

        // $file should be a local path, not an external URL
        // You can first download your file and store it locally prior to this.
        $file = base_url('upload//banner//_SVS0015.JPG');

        $mime = mime_content_type($file);
        $info = pathinfo($file);
        $name = $info['basename'];
        $output = new CURLFile($file, $mime, $name);

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://app-server.wati.io/api/v1/sendSessionFile/919605252637',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('file' => $output),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiI4OWU2ZDUyMC00YTA5LTQ0YTMtOTBhNC1jZTZjOGJmODdjZTEiLCJ1bmlxdWVfbmFtZSI6Imd1cHRhcnVwZXNoMjc1QGdtYWlsLmNvbSIsIm5hbWVpZCI6Imd1cHRhcnVwZXNoMjc1QGdtYWlsLmNvbSIsImVtYWlsIjoiZ3VwdGFydXBlc2gyNzVAZ21haWwuY29tIiwiYXV0aF90aW1lIjoiMDIvMjAvMjAyMyAxNToxOTowNyIsImRiX25hbWUiOiJ3YXRpX2FwcF90cmlhbCIsImh0dHA6Ly9zY2hlbWFzLm1pY3Jvc29mdC5jb20vd3MvMjAwOC8wNi9pZGVudGl0eS9jbGFpbXMvcm9sZSI6IlRSSUFMIiwiZXhwIjoxNjc3NTQyNDAwLCJpc3MiOiJDbGFyZV9BSSIsImF1ZCI6IkNsYXJlX0FJIn0.qUxRC2ktu8tvZwAA7kkWqMFkvWQh2D23O4OJDyaBihs'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }

    public function update_exam_status($exam_id, $examstudent_id, $status)
    {
        $this->db->where('exam_group_class_batch_exam_student_id', $examstudent_id);
        $this->db->where('exam_group_class_batch_exams_id', $exam_id);
        $this->db->set('head_lock_status', $status);
        $this->db->update('exam_group_exam_results');
    }

    public function update_exam_admin_status($exam_id,$examstudent_id,$status)
    {
        $this->db->where('exam_group_class_batch_exam_student_id', $examstudent_id);
        $this->db->where('exam_group_class_batch_exams_id', $exam_id);
        $this->db->set('admin_lock_status', $status);
        $this->db->update('exam_group_exam_results');
        
    }

    public function examResultsstatusbyexam_id($exam_id, $class_id, $section_id)
    {
        $this->db->join('exam_group_class_batch_exam_students', 'exam_group_exam_results.exam_group_class_batch_exam_student_id = exam_group_class_batch_exam_students.student_session_id');
        $this->db->join('student_session', 'student_session.id = exam_group_class_batch_exam_students.id');

        $this->db->where('student_session.class_id', $class_id);
        $this->db->where('student_session.section_id', $section_id);
        $this->db->where('exam_group_exam_results.exam_group_class_batch_exams_id', $exam_id);
        return $this->db->get('exam_group_exam_results')->row_array();
    }

    public function update_main_exam_status($exam_id, $status)
    {
        $this->db->where('id', $exam_id);
        $this->db->set('lock_status', $status);
        $this->db->update('exam_group_class_batch_exams');
    }


    public function getmark_master($id = null)
    {
        $this->db->select()->from('mark_master');
        // $this->db->join('sch_section', 'sch_section.id = newsession_start_date.sch_section_id');

        if ($id != null) {
            $this->db->where('mark_master.id', $id);
        } else {
            $this->db->order_by('mark_master.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function add_mark_master($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if ((!empty($data['id']))) {
            $this->db->where('id', $data['id']);
            $this->db->update('mark_master', $data);
            $message = UPDATE_RECORD_CONSTANT . " On   mark_master " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('mark_master', $data);
            $id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On  mark_master " . $id;
            $action = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);
        }

        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

    public function remove_mark_master($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('mark_master');
        $message = DELETE_RECORD_CONSTANT . " On Mark Master  " . $id;
        $action = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

    public function getresult_date($id = null)
    {
        $this->db->select('result_date.*,sch_section.sch_section')->from('result_date');
        $this->db->join('sch_section', 'result_date.sch_section_id = sch_section.id');
        $this->db->where('session_id',$this->current_session);
        if ($id != null) {
            $this->db->where('result_date.id', $id);
        } else {
            $this->db->where('session_id', $this->current_session);
            $this->db->order_by('result_date.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function add_result_date($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if ((!empty($data['id']))) {
            $this->db->where('id', $data['id']);
            $this->db->update('result_date', $data);
            $message = UPDATE_RECORD_CONSTANT . " On   result_date " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('result_date', $data);
            $id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On  result_date " . $id;
            $action = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);
        }

        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

    public function remove_result_date($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('result_date');
        $message = DELETE_RECORD_CONSTANT . " On Result Date  " . $id;
        $action = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

    public function getSubjectListArray($teacher_id)
    {
        $this->db->select('*');
        $this->db->join('subjects', 'teacher_subjects.subject_id = subjects.id');
        $this->db->join('class_sections', 'teacher_subjects.class_section_id = class_sections.id');
        $this->db->join('classes', 'class_sections.class_id = classes.id');
        $this->db->join('sections', 'class_sections.section_id = sections.id');
        $this->db->where('teacher_id', $teacher_id);
        $this->db->where('session_id',$this->current_session);
        
        return $this->db->get('teacher_subjects')->result_array();
        
    }
    
    public function getExamListByStudent($class_id,$section_id,$student_session_id)
    {
        $this->db->select('exam_group_class_batch_exams.id as exam_id,long_remarks,exam_groups.name,exam_group_class_batch_exams.exam,exam_group_class_batch_exam_students.student_session_id,exam_group_class_batch_exam_students.id');
        $this->db->from('exam_group_class_batch_exam_students');
        $this->db->join('student_session', 'exam_group_class_batch_exam_students.student_session_id = student_session.id');
        $this->db->join('exam_group_class_batch_exams', 'exam_group_class_batch_exam_students.exam_group_class_batch_exam_id = exam_group_class_batch_exams.id');
        $this->db->join('exam_groups', 'exam_group_class_batch_exams.exam_group_id = exam_groups.id');
        $this->db->where('student_session.class_id', $class_id);
        $this->db->where('student_session.section_id', $section_id);
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('exam_group_class_batch_exams.is_active', 1);
        $this->db->where('exam_group_class_batch_exams.mark_result', 0);
        // $this->db->where('exam_group_class_batch_exams.session_id', $this->current_session);
        $this->db->where('exam_group_class_batch_exam_students.student_session_id', $student_session_id);
        $this->db->order_by('exam_group_class_batch_exam_students.id','asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function updateRemark($array,$id)
    {
        $this->db->where('id',$id);
        $this->db->update('exam_group_class_batch_exam_students',$array);
    }

    public function getAssignClassByExamgroup($exam_group,$class_id)
    {
        $this->db->select('exam_class_assign.exam_group_id,class_id');
        $this->db->from('exam_class_assign');
        $this->db->where('status','active');
        $this->db->where('class_id', $class_id);
        $this->db->where('exam_class_assign.exam_group_id', $exam_group);

        return $this->db->get()->row_array();
    }

    public function assign_class($insert_array,$exam_group_id)
    {
        $this->db->where('exam_group_id', $exam_group_id);
        $this->db->delete('exam_class_assign');

        $this->db->insert_batch('exam_class_assign',$insert_array);

        return $this->db->insert_id();
    }

    public function getclassbyexamgroup($exam_group_id)
    {
        $this->db->select('classes.id,classes.class');
        $this->db->from('exam_class_assign');
        $this->db->join('classes', 'classes.id = exam_class_assign.class_id');
        $this->db->where('exam_class_assign.exam_group_id', $exam_group_id);
        $this->db->where('exam_class_assign.status', 'active');
        $this->db->order_by('classes.class', 'asc');
        $query = $this->db->get();
        return $query->result_array();
        
    }
    public function getexamgroupbyClass($class_id)
    {
        $this->db->select('exam_groups.id,exam_groups.name');
        $this->db->from('exam_class_assign');
        $this->db->join('exam_groups', 'exam_groups.id = exam_class_assign.exam_group_id');
        $this->db->where('exam_class_assign.class_id', $class_id);
        $this->db->where('exam_class_assign.status', 'active');
        $this->db->order_by('exam_groups.name', 'asc');
        $query = $this->db->get();
        return $query->result_array();
        
    }

    public function getExamByExamGroupexamlist($id, $is_active = false)
    {
        $this->db->select('exam_group_class_batch_exams.*,sessions.session,(select COUNT(*) from exam_group_class_batch_exam_subjects WHERE exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id = exam_group_class_batch_exams.id) as `total_subjects`')->from('exam_group_class_batch_exams');
        $this->db->join('sessions', 'sessions.id = exam_group_class_batch_exams.session_id');
        if ($is_active) {
            $this->db->where('exam_group_class_batch_exams.is_active', $is_active);
        }
        $this->db->where('exam_group_class_batch_exams.mark_result', 0);
        $this->db->where('exam_group_class_batch_exams.exam_group_id', $id);
        $this->db->where('exam_group_class_batch_exams.exam_group_id', $id);
        $this->db->order_by('exam_group_class_batch_exams.exam_group_id');

        $query = $this->db->get();

        return $query->result();
    }

    public function getMainSubjectByExam($id, $is_active = false)
    {
        $this->db->select('exam_group_class_batch_exam_subjects.main_sub,subjects.name')->from('exam_group_class_batch_exam_subjects');
        $this->db->join('subjects', 'subjects.id = exam_group_class_batch_exam_subjects.main_sub');
        $this->db->where('exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id', $id);
        $this->db->order_by('exam_group_class_batch_exam_subjects.main_sub');
        $this->db->group_by('exam_group_class_batch_exam_subjects.main_sub,subjects.name');
        

        $query = $this->db->get();

        return $query->result();
    }
}
