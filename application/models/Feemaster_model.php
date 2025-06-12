<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Feemaster_model extends MY_Model {

    public $current_session;
    public $current_ay_session;

    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
        $settings              = $this->setting_model->getSetting();
        $this->current_ay_session = $settings->session_id;          
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function get($id = null) {
        $this->db->select('feemasters.feetype_id,feemasters.id,feemasters.class_id,feemasters.session_id,feemasters.amount,feemasters.description,classes.class,feetype.type,feetype.feecategory_id')->from('feemasters');
        $this->db->join('classes', 'feemasters.class_id = classes.id');
        $this->db->join('feetype', 'feemasters.feetype_id = feetype.id');
        $this->db->where('feemasters.session_id', $this->current_session);
        if ($id != null) {
            $this->db->where('feemasters.id', $id);
        } else {
            $this->db->order_by('feemasters.id');
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
    public function remove($id) {
		$this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('feemasters');
		
		$message = DELETE_RECORD_CONSTANT . " On  fee master  id " . $id;
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

            // return $return_value;
        }
    }
   
    /**
     * This function will take the post data passed from the controller
     * If id is present, then it will do an update
     * else an insert. One function doing both add and edit.
     * @param $data
     */
    public function add($data) {
		$this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('feemasters', $data);			
			$message = UPDATE_RECORD_CONSTANT . " On  fee master  id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
           
        } else {
            $data['session_id'] = $this->current_session;
            $this->db->insert('feemasters', $data);            
			$id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On fee master id " . $id;
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
            return $id;
        }
        
    }

    public function getCheque($id = null) {
        $this->db->select('cheque_inword.*,students.firstname,lastname,classes.class,sections.section')->from('cheque_inword');
        $this->db->join('student_session', 'student_session.id = cheque_inword.student_session_id','left');
        $this->db->join('students', 'students.id = student_session.student_id','left');
        $this->db->join('classes', 'cheque_inword.class_id = classes.id','left');
        $this->db->join('sections', 'cheque_inword.section_id = sections.id','left');
        $this->db->where('cheque_inword.session_id', $this->current_session);
        $this->db->where('cheque_inword.status', 1);
        if ($id != null) {
            $this->db->where('cheque_inword.id', $id);
        } else {
            $this->db->order_by('cheque_inword.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }
    public function getCheque_only($id = null) {
        $this->db->select('cheque_inword.*')->from('cheque_inword');
        $this->db->where('cheque_inword.session_id', $this->current_session);
        if ($id != null) {
            $this->db->where('cheque_inword.id', $id);
        } else {
            $this->db->order_by('cheque_inword.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }
    public function getChequeDeposit() {
        $this->db->select('cheque_inword.*,students.firstname,lastname,classes.class,sections.section')->from('cheque_inword');
        $this->db->join('student_session', 'student_session.id = cheque_inword.student_session_id','left');
        $this->db->join('students', 'students.id = student_session.student_id','left');
        $this->db->join('classes', 'cheque_inword.class_id = classes.id','left');
        $this->db->join('sections', 'cheque_inword.section_id = sections.id','left');
        $this->db->where('cheque_inword.session_id', $this->current_session);
        $this->db->where('cheque_inword.chq_status', "collected");
        $this->db->where('cheque_inword.status', 1);
        $this->db->order_by('cheque_inword.id');
        $query = $this->db->get();
        return $query->result_array();
        
    }
    public function getChequeBounce() {
        $this->db->select('cheque_inword.*,students.firstname,lastname,classes.class,sections.section')->from('cheque_inword');
        $this->db->join('student_session', 'student_session.id = cheque_inword.student_session_id','left');
        $this->db->join('students', 'students.id = student_session.student_id','left');
        $this->db->join('classes', 'cheque_inword.class_id = classes.id','left');
        $this->db->join('sections', 'cheque_inword.section_id = sections.id','left');
        $this->db->where('cheque_inword.session_id', $this->current_session);
        $this->db->where('cheque_inword.chq_status', "deposit");
        $this->db->where('cheque_inword.status', 1);
        $this->db->order_by('cheque_inword.id');
        $query = $this->db->get();
        return $query->result_array();
        
    }

    public function removeCheque($id) {
        
		$this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $data = array(
            "status" => 0
        );
        $this->db->where('id', $id);
        $this->db->update('cheque_inword',$data);

		$message = DELETE_RECORD_CONSTANT . " On  cheque inword  id " . $id;
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

            // return $return_value;
        }
    }

    public function chqPassStatus($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (!empty($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('cheque_inword', $data);			
			$message = UPDATE_RECORD_CONSTANT . " On  cheque inword  id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
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

    public function addChqProcess($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (!empty($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('cheque_process', $data);			
			$message = UPDATE_RECORD_CONSTANT . " On  cheque process  id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
           
        } else {
            $this->db->insert('cheque_process', $data);            
			$id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On cheque process id " . $id;
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

    public function addCheque($data) {
		$this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (!empty($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('cheque_inword', $data);			
			$message = UPDATE_RECORD_CONSTANT . " On  cheque inword  id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
           
        } else {
            $data['session_id'] = $this->current_ay_session;
            $this->db->insert('cheque_inword', $data);            
			$id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On cheque inword id " . $id;
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

    public function check_Exits_group($data) {
        $this->db->select('*');
        $this->db->from('feemasters');
        $this->db->where('session_id', $this->current_session);
        $this->db->where('feetype_id', $data['feetype_id']);
        $this->db->where('class_id', $data['class_id']);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return false;
        } else {
            return true;
        }
    }

    public function getTypeByFeecategory($type, $class_id) {
        $this->db->select('feemasters.id,feemasters.session_id,feemasters.amount,feemasters.description,classes.class,feetype.type')->from('feemasters');
        $this->db->join('classes', 'feemasters.class_id = classes.id');
        $this->db->join('feetype', 'feemasters.feetype_id = feetype.id');
        $this->db->where('feemasters.class_id', $class_id);
        $this->db->where('feemasters.feetype_id', $type);
        $this->db->where('feemasters.session_id', $this->current_session);
        $this->db->order_by('feemasters.id');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getByClass($class_id) {
        $this->db->select('feemasters.id,feemasters.session_id,feemasters.amount,feemasters.description,classes.class,feetype.type')->from('feemasters');
        $this->db->join('classes', 'feemasters.class_id = classes.id');
        $this->db->join('feetype', 'feemasters.feetype_id = feetype.id');
        $this->db->where('feemasters.class_id', $class_id);
        $this->db->where('feemasters.session_id', $this->current_session);
        $this->db->order_by('feemasters.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getChequeData($start_date, $end_date,$chq_status= null)
    {
        $this->db->select('cheque_inword.*,student_session.roll_no,students.firstname,lastname,classes.class,sections.section')->from('cheque_inword');
        $this->db->join('student_session', 'student_session.id = cheque_inword.student_session_id','left');
        $this->db->join('students', 'students.id = student_session.student_id','left');
        $this->db->join('classes', 'cheque_inword.class_id = classes.id','left');
        $this->db->join('sections', 'cheque_inword.section_id = sections.id','left');
        $this->db->where('cheque_inword.session_id', $this->current_session);

        if (!empty($chq_status)) {
            $this->db->where('cheque_inword.chq_status', $chq_status);
        }
        if (!empty($start_date)) {
            $this->db->where('cheque_inword.chq_date >=', $start_date);
        }
        if (!empty($end_date)) {
            $this->db->where('cheque_inword.chq_date <=', $end_date);
        }
        
        $this->db->where('cheque_inword.status', 1);
        $this->db->order_by('cheque_inword.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function searchAssignBusFeeByClassSection($class_id = null, $section_id = null,$fee_session_group_id=null,$vehroute_id = null)
    {
        $sql = "SELECT IFNULL(`student_fees_master`.`id`, '0') as `student_fees_master_id`,`classes`.`id` AS `class_id`,"
        . " `student_session`.`id` as `student_session_id`, `students`.`id`,`transport_route`.`route_title`, "
        . "`classes`.`class`, `sections`.`id` AS `section_id`, `sections`.`section`, "
        . "`students`.`id`, `students`.`admission_no`, `student_session`.`roll_no`,"
        . " `students`.`admission_date`, `students`.`firstname`, `students`.`middlename`,`students`.`lastname`,"
        . " `students`.`image`, `students`.`mobileno`, `students`.`email`, `students`.`state`,"
        . " `students`.`city`, `students`.`pincode`, `students`.`religion`, `students`.`dob`, "
        . "`students`.`current_address`, `students`.`permanent_address`,"
        . " IFNULL(students.category_id, 0) as `category_id`,"
        . " IFNULL(categories.category, '') as `category`,"
        . " `students`.`adhar_no`, `students`.`samagra_id`,"
        . " `students`.`bank_account_no`, `students`.`bank_name`, `students`.`ifsc_code`,"
        . " `students`.`guardian_name`, `students`.`guardian_relation`, `students`.`guardian_phone`,"
        . " `students`.`guardian_address`, `students`.`is_active`, `students`.`created_at`,"
        . " `students`.`updated_at`, `students`.`father_name`, `students`.`rte`,"
        . " `students`.`gender` FROM `students` JOIN `student_session` "
        . "ON `student_session`.`student_id` = `students`.`id` JOIN `classes` "
        . "ON `student_session`.`class_id` = `classes`.`id` JOIN `transport_route` "
        . "ON `student_session`.`route` = `transport_route`.`id` JOIN `sections` "
        . "ON `sections`.`id` = `student_session`.`section_id` LEFT JOIN `categories` "
        . "ON `students`.`category_id` = `categories`.`id` LEFT JOIN student_fees_master on"
        . " student_fees_master.student_session_id=student_session.id"
        . "  AND student_fees_master.fee_session_group_id=" . $this->db->escape($fee_session_group_id)
        . "WHERE `student_session`.`session_id` =  " . $this->current_session
        . " and `students`.`is_active` =  'yes'"
        . " and `student_session`.`is_active` =  'yes'";

    if ($class_id != null) {
        $sql .= " AND `student_session`.`class_id` = " . $this->db->escape($class_id);
    }
    if ($section_id != null) {
        $sql .= " AND `student_session`.`section_id` =" . $this->db->escape($section_id);
    }
    
    if ($vehroute_id != null) {
        $sql .= " AND `student_session`.`route` =" . $this->db->escape($vehroute_id);
    }
    $sql .= " ORDER BY `student_session`.`roll_no`";

    $query = $this->db->query($sql);
    return $query->result_array();
       
    }

    public function getOnlineResults($start_date, $end_date)
    {
        $this->db->select('online_transaction.*,student_session.roll_no,students.firstname,lastname,classes.class,sections.section')->from('online_transaction');
        $this->db->join('student_session', 'student_session.id = online_transaction.student_session_id');
        $this->db->join('students', 'students.id = student_session.student_id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'student_session.section_id = sections.id');
        
        if (!empty($start_date)) {
            $this->db->where('online_transaction.trn_date >=', $start_date);
        }
        if (!empty($end_date)) {
            $this->db->where('online_transaction.trn_date <=', $end_date);
        }

        $this->db->where('online_transaction.session_id', $this->current_session);
        $this->db->order_by('online_transaction.trn_date', 'desc');
        
        $query = $this->db->get();
        return $query->result_array();
    }

    public function updateReceiptStatus($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $tb_name = 'fee_receipt_no_' . $this->current_session;

        $this->db->where('id', $data['id']);
        $this->db->update($tb_name, $data);			
        $message = UPDATE_RECORD_CONSTANT . " On  ".$tb_name."  id " . $data['id'];
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
    }
}
