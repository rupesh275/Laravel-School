<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Subject_model extends MY_Model {

    public $current_session;

    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function get($id = null) {

        $subject_condition = 0;
        $userdata = $this->customlib->getUserData();

        $role_id = $userdata["role_id"];


        if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if ($userdata["class_teacher"] == 'yes') {



                $my_classes = $this->teacher_model->my_classes($userdata['id']);


                if (!empty($my_classes)) {
                    $subject_condition = 0;
                } else {
                    $subject_condition = 1;
                    $my_subjects = $this->teacher_model->get_examsubjects($userdata['id']);
                }
            }
        }
        $this->db->select()->from('subjects');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            if ($subject_condition == 1) {
                $this->db->where_in('subjects.id', $my_subjects);
            }
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }
    public function get_two($id) {

        $subject_condition = 0;
        $userdata = $this->customlib->getUserData();

        $role_id = $userdata["role_id"];


        if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if ($userdata["class_teacher"] == 'yes') {



                $my_classes = $this->teacher_model->my_classes($userdata['id']);


                if (!empty($my_classes)) {
                    $subject_condition = 0;
                } else {
                    $subject_condition = 1;
                    $my_subjects = $this->teacher_model->get_examsubjects($userdata['id']);
                }
            }
        }
        $this->db->select()->from('subjects');
        $this->db->where('id', $id);
        if ($subject_condition == 1) {
            $this->db->where_in('subjects.id', $my_subjects);
        }
        $this->db->order_by('id');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_three($id)
    {
        $this->db->select('*');   
        $this->db->where('id',$id);   
        return $this->db->get('subjects')->row_array();
    }

    public function remove($id) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('subjects');
        $message = DELETE_RECORD_CONSTANT . " On subjects id " . $id;
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

    public function add($data) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('subjects', $data);
            $message = UPDATE_RECORD_CONSTANT . " On subjects id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
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
        } else {
            $this->db->insert('subjects', $data);
            $id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On subjects id " . $id;
            $action = "Insert";
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
            return $id;
        }
    }

    function check_data_exists($data) {
        $this->db->where('name', $data['name']);
        $query = $this->db->get('subjects');
        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function check_code_exists($data) {
        $this->db->where('code', $data['code']);
        $query = $this->db->get('subjects');
        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }


    function getsubject() {
        $this->db->where('parent_id is null');
        $this->db->select()->from('subjects');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }

    function getSubsubject($subid) {
        if($subid!=''){
            $this->db->where('parent_id',$subid);
        } else{
            $this->db->where('parent_id','');
        }
        $query = $this->db->get('subjects');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }

    function get_parent($id)
    {
        $this->db->select('parent_id');
        $this->db->where('id',$id);
        $row = $this->db->get('subjects')->row_array();
        if(!empty($row)){
            return $row['parent_id'];
        }
    }

    public function get_batch($id)
    {
        $this->db->where('parent_id',$id);
        $row = $this->db->get('subjects')->result_array();
        if(!empty($row)){
            return $row;
        }
    }
    public function getSubjectByID($id)
    {
        $this->db->select()->from('subjects');
        if ($id != null) {
            $this->db->where('id', $id);
        }
        $query = $this->db->get();
        return $query->row_array();
    }
    public function getSubjectByID3($id)
    {
        $this->db->select()->from('subjects');
        if ($id != null) {
            $this->db->where('id', $id);
        }
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getsubjectbyidgroup($exam_id)
    {
        $this->db->select('*');
        $this->db->where('exam_group_class_batch_exams_id', $exam_id);
        $this->db->group_by('main_sub');
       return $this->db->get('exam_group_class_batch_exam_subjects');
        
    }
    public function getallsub($subjects_idsArray)
    {
        $this->db->select('*');
        $this->db->where_in('id', $subjects_idsArray);
        $this->db->group_by('parent_id');
        
        return $this->db->get('subjects');
        
        // echo $this->db->last_query();
    }
    public function getsubjectbyid2($exam_id)
    {
        $this->db->select('*');
        $this->db->where_in('exam_group_class_batch_exams_id', $exam_id);
       return $this->db->get('exam_group_class_batch_exam_subjects');
        
    }
    public function getsubjectbyexamid($exam_id,$main_sub)
    {
        $this->db->select('*');
        $this->db->where('exam_group_class_batch_exams_id', $exam_id);
        $this->db->where('main_sub', $main_sub);
       return $this->db->get('exam_group_class_batch_exam_subjects');
        
    }

    public function get_parent_subject($id)
    {
        $this->db->select('*');
        $this->db->where('parent_id',$id);
        return $this->db->get('subjects');
    }

    public function getTeacherSubject($class_id, $section_id)
    {
        $this->db->where('class_id', $class_id);
        $this->db->where('section_id', $section_id);
        $classSection = $this->db->get('class_sections')->row_array();

        $this->db->select('sch_section_id');
        $this->db->where('id', $class_id);
        $sch_section = $this->db->get('classes')->row_array();
        
        
        $userdata = $this->customlib->getUserData();
      
        if ($userdata['role_id'] == 18 && $sch_section['sch_section_id'] != 1) {
            $this->db->select('subjects.name,subject_id,subjects.id');
            $this->db->join('subjects', 'subjects.id = teacher_subjects.subject_id');
            $this->db->where('teacher_subjects.class_section_id', $classSection['id']);
            $this->db->where('teacher_subjects.teacher_id', $userdata['id']);
            $this->db->where('teacher_subjects.session_id', $this->current_session);
            return $this->db->get('teacher_subjects')->result_array();
        } else {
            $this->db->where('parent_id is null');
            $this->db->select()->from('subjects');
            $query = $this->db->get();
            return $query->result_array();
        }
        
    }
}
