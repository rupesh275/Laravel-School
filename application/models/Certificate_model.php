<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Certificate_model extends MY_Model {

    public $current_session;

    function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function addcertificate($data) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('certificates', $data);
            $message = UPDATE_RECORD_CONSTANT . " On  certificates id " . $data['id'];
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
            $this->db->insert('certificates', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On certificates id " . $insert_id;
            $action = "Insert";
            $record_id = $insert_id;
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
            return $insert_id;
        }
    }

    public function certificateList() {
        $this->db->select('*');
        $this->db->from('certificates');
        $this->db->where('status = 1');
        $this->db->where('created_for = 2');
        $query = $this->db->get();
        return $query->result();
    }

    public function get($id) {
        $this->db->select('*');
        $this->db->from('certificates');
        $this->db->where('status = 1');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    public function remove($id) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('certificates');
        $message = DELETE_RECORD_CONSTANT . " On certificates id " . $id;
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

    public function remove_tc($id) {
        $array = array(
            'status' => 'Cancelled',
        );        
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->update('student_info', $array);        
        $message = DELETE_RECORD_CONSTANT . " On student_info id " . $id;
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
    public function remove_bonafide($id) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('bonafide_trn');
        $message = DELETE_RECORD_CONSTANT . " On bonafide_trn id " . $id;
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
    public function remove_fees_certicate($id) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('fees_certicate_trn');

        $this->db->where('fees_trn_id', $id);
        $this->db->delete('fees_certicate_sub');
        $message = DELETE_RECORD_CONSTANT . " On fees_certicate_trn id " . $id;
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

    public function getstudentcertificate() {
        $this->db->select('*');
        $this->db->from('certificates');
        $this->db->where('created_for = 2');
        $query = $this->db->get();
        return $query->result();
    }

    public function certifiatebyid($id) {
        $this->db->select('*');
        $this->db->from('certificates');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function addBonafide($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('bonafide_trn', $data);
            $message = UPDATE_RECORD_CONSTANT . " On   bonafide_trn " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('bonafide_trn', $data);
            $id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On  bonafide_trn " . $id;
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
            return $record_id;
        }
    }
    public function addstudent_info($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('student_id', $data['student_id']);
        $this->db->where('session_id', $data['session_id']);
        $this->db->where('status', 'Active');
        $q = $this->db->get('student_info')->num_rows();
        
        if (($q > 0)) {
            $this->db->where('student_id', $data['student_id']);
            $this->db->where('session_id', $data['session_id']);
            $this->db->where('status', 'Active');
            $this->db->update('student_info', $data);
            $message = UPDATE_RECORD_CONSTANT . " On   student_info " . $data['student_id'];
            $action = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('student_info', $data);
            $id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On  student_info " . $id;
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
            return $record_id;
        }
    }

    public function addstudent_info2($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        
            $this->db->insert('student_info', $data);
            $id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On  student_info " . $id;
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
            return $record_id;
        }
    }

    public function getstudent_info($id = null) {
        $this->db->select('student_info.*,student_info.remark as student_remarks,students.aadhar_name,students.firstname,lastname,admission_no,classes.class,sections.section,cl2.class as promoted_class,cl2.class_text,sessions.session as session_name')->from('student_info');
        $this->db->join('students', 'students.id = student_info.student_id');
        $this->db->join('student_session', 'student_session.id = student_info.student_session_id','left');
        $this->db->join('classes', 'classes.id = student_session.class_id','left');
        $this->db->join('sections', 'sections.id = student_session.section_id','left');
        $this->db->join('classes as cl2', 'cl2.id = student_info.passed_promoted','left');
        $this->db->join('sessions', 'sessions.id = student_session.session_id','left');
        if ($id != null) {
            $this->db->where('student_info.id', $id);
        } else {
            
        }
        $this->db->order_by('student_info.tc_certificate_no Desc');
        $this->db->where('student_info.status',  'Active');
        $this->db->where('student_info.tc_session_id',  $this->current_session);
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
        
    }

    public function getBonafide($id = null) {
        $this->db->select('bonafide_trn.*,student_session.roll_no,students.id as student_id,students.firstname,lastname,gender,dob,father_name,admission_no,classes.class,sections.section')->from('bonafide_trn');
        $this->db->join('student_session', 'student_session.id = bonafide_trn.student_session_id','left');
        $this->db->join('students', 'students.id = student_session.student_id');
        $this->db->join('classes', 'classes.id = student_session.class_id','left');
        $this->db->join('sections', 'sections.id = student_session.section_id','left');
        
        
        if ($id != null) {
            $this->db->where('bonafide_trn.id', $id);
        } else {
            
        }
        $this->db->order_by('bonafide_trn.id Desc');
        $this->db->where('bonafide_trn.session_id',  $this->current_session);
        
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
        
    }
    public function getfeecertificate($id = null) {
        $this->db->select('fees_certicate_trn.*,student_session.class_id,section_id,student_session.roll_no,students.id as student_id,students.firstname,lastname,gender,dob,father_name,admission_no,classes.class,classes.code,sections.section')->from('fees_certicate_trn');
        $this->db->join('student_session', 'student_session.id = fees_certicate_trn.student_session_id','left');
        $this->db->join('students', 'students.id = student_session.student_id');
        $this->db->join('classes', 'classes.id = student_session.class_id','left');
        $this->db->join('sections', 'sections.id = student_session.section_id','left');
        
        
        if ($id != null) {
            $this->db->where('fees_certicate_trn.id', $id);
        } else {
            
        }
        $this->db->order_by('fees_certicate_trn.id Desc');
        $this->db->where('fees_certicate_trn.session_id',  $this->current_session);
        $this->db->where('fees_certicate_trn.status',  0);
        
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
        
    }

    public function getstudent_info_count() {
        $this->db->select()->from('student_info')->order_by('id Desc');
        $this->db->where('status','Active');
        $query = $this->db->get();
        return $query->row_array();

    }

    public function get_reasons()
    {
        $this->db->distinct();
        $this->db->select('reason_leave');
        $query = $this->db->get('student_info');
        return $query->result_array();
        
    }

    public function get_conductlist()
    {
        $this->db->distinct();
        $this->db->select('general_conduct');
        $query = $this->db->get('student_info');
        return $query->result_array();
        
    }

    public function addfeecertificate_trn($data) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('fees_certicate_trn', $data);
            $message = UPDATE_RECORD_CONSTANT . " On  fees_certicate_trn id " . $data['id'];
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
                return $record_id;
            }
        } else {
            $this->db->insert('fees_certicate_trn', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On fees_certicate_trn id " . $insert_id;
            $action = "Insert";
            $record_id = $insert_id;
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
            return $insert_id;
        }
    }
    public function addfeecertificate_sub($data) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (!empty($data['ids'])) {

            $this->db->where('id', $data['id']);
            $this->db->delete('fees_certicate_sub', $data);
            $message = UPDATE_RECORD_CONSTANT . " On  fees_certicate_sub id " . $data['id'];
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
            $this->db->insert('fees_certicate_sub', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On fees_certicate_sub id " . $insert_id;
            $action = "Insert";
            $record_id = $insert_id;
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
            return $insert_id;
        }
    }

    public function getfeesData($feestrn_id)
    {
        $this->db->where('fees_trn_id', $feestrn_id);
        $q = $this->db->get('fees_certicate_sub');
        
        return $q->result_array();
    }
    public function getfees_trnRow()
    {
        $this->db->order_by('id', 'desc');
        $q = $this->db->get('fees_certicate_trn');
        
        return $q->row_array();
    }
    public function getbonafide_trnRow()
    {
        $this->db->order_by('srno', 'desc');
        $q = $this->db->get('bonafide_trn');

        return $q->row_array();
    }    
    public function getfeesTrn($feestrn_id)
    {
        $this->db->where('id', $feestrn_id);
        $q = $this->db->get('fees_certicate_trn');
        
        return $q->row_array();
    }

    public function update_fees_certicate($id,$array)
    {
        $this->db->where('id', $id);
        $this->db->update('fees_certicate_trn',$array);
    }

    public function getCertificateCount($student_id)
    {
        $this->db->where('student_id', $student_id);
        $this->db->where('status', 'Active');
        $query = $this->db->get('student_info')->num_rows();
        return $query;
        
    }
    public function getByStudentId($student_id)
    {
        $this->db->where('student_id', $student_id);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('student_info')->row_array();
        return $query;
        
    }
}

?>