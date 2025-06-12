<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Feediscount_model extends MY_Model
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
    public function get($id = null, $order = "desc")
    {
        $this->db->select('fees_discounts.id,fees_discounts.name,fees_discounts.code,fees_discounts.fees_type,fees_discounts.description');
        if ($id != null) {
            $this->db->where('fees_discounts.id',$id);
        }
        $result = $this->db->get('fees_discounts')->result_array();
        $rw=0;
        foreach($result as $r)
        {
            $this->db->select('student_discount_session.amount,student_discount_session.feepercent,student_discount_session.date_enabled,student_discount_session.date_upto');
            $this->db->where('student_discount_session.session_id',$this->current_session);
            $this->db->where('student_discount_session.discount_id',$r['id']);
            $ses_rec=$this->db->get('student_discount_session')->row_array();
            if(!empty($ses_rec))
            {
                $result[$rw]['amount'] = $ses_rec['amount'];
                $result[$rw]['feepercent'] = $ses_rec['feepercent'];
                $result[$rw]['date_enabled'] = $ses_rec['date_enabled'];
                $result[$rw]['date_upto'] = $ses_rec['date_upto'];
            }
            else
            {
                $result[$rw]['amount'] = '';
                $result[$rw]['feepercent'] = '';
                $result[$rw]['date_enabled'] = '';
                $result[$rw]['date_upto'] = '';
            }
            ++$rw;
        }
        if($id != null) {
            return $result[0];
        }
        else
        {
            return $result;
        }
    }    
    public function getold($id = null, $order = "desc")
    {
        $this->db->select()->from('fees_discounts');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {

            $this->db->order_by('id ' . $order);
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getbyasc($id = null)
    {
        $this->db->select()->from('fees_discounts');
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
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('fees_discounts');
        $message = DELETE_RECORD_CONSTANT . " On  fees discounts id " . $id;
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
        if (isset($data['id']) && $data['id']!='') {
            
            $this->db->where('id', $data['id']);
            $this->db->update('fees_discounts', $data);
            $message = UPDATE_RECORD_CONSTANT . " On  fees discounts id " . $data['id'];
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
            
            $data['session_id'] = $this->current_session;
            $this->db->insert('fees_discounts', $data);
            $id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On  fees discounts id " . $id;
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
    public function get_studentDiscount_session($session_id,$discount_id)
    {
        
        $this->db->where('discount_id',$discount_id);
        $this->db->where('session_id',$session_id);
        $query = $this->db->get('student_discount_session')->row_array();
       
        if (empty($query))
        {
            return '';
        }
        else
        {
            return $query['id'];
        }

    }
    public function addstudentDiscount_session($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id']) && $data['id']!= '') {
            
            $this->db->where('id', $data['id']);
            $this->db->update('student_discount_session', $data);
            $message = UPDATE_RECORD_CONSTANT . " On  student fees discounts id " . $data['id'];
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
            
            $data['session_id'] = $this->current_session;
            $this->db->insert('student_discount_session', $data);
            $id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On  student fees discounts id " . $id;
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
    public function updateStudentDiscount($data)
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('student_fees_discounts', $data);
        }

        $message   = UPDATE_RECORD_CONSTANT . " On  student_fees_discounts " . $data['id'];
        $action    = "Update";
        $record_id = $insert_id = $data['id'];
        $this->log($message, $record_id, $action);
        
        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $insert_id;
        }
    }
    public function updateStudentDiscountbypayment($data,$payment_id)
    {
        if (isset($payment_id)) {
            $this->db->where('payment_id', $payment_id);
            $this->db->where('session_id', $this->current_session);
            $this->db->update('student_fees_discounts', $data);
        }
    }
    public function updateStudentDiscountbypayment_session($data,$payment_id,$session_id,$student_session_id)
    {
        if (!empty($payment_id)) {
            $this->db->where('payment_id', $payment_id);
            $this->db->where('session_id', $session_id);
            $this->db->where('student_session_id', $student_session_id);
            $this->db->update('student_fees_discounts', $data);
        }
    }
    public function allotdiscount_exist($data)
    {
        $this->db->where('student_session_id', $data['student_session_id']);
        $this->db->where('fees_discount_id', $data['fees_discount_id']);
        $this->db->where('session_id', $data['session_id']);
        $this->db->where('is_active', 'yes');
        $q = $this->db->get('student_fees_discounts');
        if ($q->num_rows() > 0) {
            return true;
        }
        else
        {
            return false;
        }

    }
    public function allotdiscount($data)
    {
        $this->db->where('student_session_id', $data['student_session_id']);
        $this->db->where('fees_discount_id', $data['fees_discount_id']);
        $this->db->where('session_id', $data['session_id']);
        $this->db->where('is_active', 'yes');
        $q = $this->db->get('student_fees_discounts');
        
        if ($q->num_rows() > 0) {
            $rec = $q->row_array();
            $this->db->where('id', $rec['id']);
            $this->db->update('student_fees_discounts', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  student fees discountid " . $rec['id'];
            $action    = "Update";
            return $record_id = $rec['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('student_fees_discounts', $data);
            return $this->db->insert_id();

            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On Student Status " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);

            $this->db->trans_complete(); # Completing transaction
            /* Optional */
    
            if ($this->db->trans_status() === false) {
                # Something went wrong.
                $this->db->trans_rollback();
                return false;
            } else {
                return $insert_id;
            }
        }
    }

    public function searchAssignFeeByClassSection($class_id = null, $section_id = null, $fees_discount_id = null, $category = null, $gender = null, $rte = null)
    {
        $sql = "SELECT IFNULL(`student_fees_discounts`.`id`, '0') as `student_fees_discount_id`,"
            . "`student_fees_discounts`.`session_id`,"
            . "`classes`.`id` AS `class_id`, `student_session`.`id` as `student_session_id`,"
            . " `students`.`id`, `classes`.`class`, `sections`.`id` AS `section_id`,"
            . " `sections`.`section`, `students`.`id`, `students`.`admission_no`,"
            . " `student_session`.`roll_no`, `students`.`admission_date`, `students`.`firstname`,"
            . " `students`.`lastname`,`students`.`middlename`, `students`.`image`, `students`.`mobileno`,"
            . " `students`.`email`, `students`.`state`, `students`.`city`, `students`.`pincode`,"
            . " `students`.`religion`, `students`.`dob`, `students`.`current_address`,"
            . " `students`.`permanent_address`, IFNULL(students.category_id, 0) as `category_id`,"
            . " IFNULL(categories.category, '') as `category`, `students`.`adhar_no`,"
            . " `students`.`samagra_id`, `students`.`bank_account_no`, `students`.`bank_name`,"
            . " `students`.`ifsc_code`, `students`.`guardian_name`, `students`.`guardian_relation`,"
            . " `students`.`guardian_phone`, `students`.`guardian_address`, `students`.`is_active`,"
            . " `students`.`created_at`, `students`.`updated_at`, `students`.`father_name`,"
            . " `students`.`rte`, `students`.`gender` FROM `students` JOIN `student_session` ON"
            . " `student_session`.`student_id` = `students`.`id` JOIN `classes` ON"
            . " `student_session`.`class_id` = `classes`.`id` JOIN `sections` ON"
            . " `sections`.`id` = `student_session`.`section_id` LEFT JOIN `categories` ON"
            . " `students`.`category_id` = `categories`.`id` LEFT JOIN"
            . " student_fees_discounts on student_fees_discounts.student_session_id=student_session.id"
            . " AND student_fees_discounts.fees_discount_id=" . $this->db->escape($fees_discount_id) .
            " WHERE `student_session`.`session_id` = " . $this->current_session;


        if ($class_id != null) {
            $sql .= " AND `student_session`.`class_id` = " . $this->db->escape($class_id);
        }
        if ($section_id != null) {
            $sql .= " AND `student_session`.`section_id` =" . $this->db->escape($section_id);
        }
        if ($category != null) {
            $sql .= " AND `students`.`category_id` =" . $this->db->escape($category);
        }
        if ($gender != null) {
            $sql .= " AND `students`.`gender` =" . $this->db->escape($gender);
        }
        if ($rte != null) {
            $sql .= " AND `students`.`rte` =" . $this->db->escape($rte);
        }
        $sql .= " AND students.is_active='yes'";
        $sql .= " AND student_session.is_active='yes'";
        $sql .= " ORDER BY `student_session`.`roll_no`";

        $query = $this->db->query($sql);
        return $query->result_array();
    }
    public function searchAssignFeeByClassSection2($class_id = null, $section_id = null, $fees_discount_id = null)
    {
        
        $this->db->select('student_fees_discounts.session_id,classes.id as class_id,student_session.id as student_session_id,
        students.id,classes.class,sections.section,students.admission_no,student_session.roll_no,students.admission_date,students.firstname,
        students.lastname,students.middlename,student_fees_discounts.status,fees_discounts.name,fees_discounts.amount');
        $this->db->join('student_session', 'student_session.id = student_fees_discounts.student_session_id');
        $this->db->join('students', 'students.id = student_session.student_id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('classes', 'classes.id = student_session.class_id');
        $this->db->join('fees_discounts', 'fees_discounts.id = student_fees_discounts.fees_discount_id');
        if ($class_id != null) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->db->where('student_session.section_id', $section_id);
        }

        if ($fees_discount_id == 7) {
            $this->db->where('student_fees_discounts.amount >', 0);
        }
        if ($fees_discount_id != null) {
            $this->db->where('student_fees_discounts.fees_discount_id', $fees_discount_id);
        }
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->order_by('student_session.roll_no', 'asc');
        

        $query = $this->db->get('student_fees_discounts');
        return $query->result_array();
    }

    public function deletedisstd($fees_discount_id, $array)
    {
        $this->db->where('fees_discount_id', $fees_discount_id);
        $this->db->where_in('student_session_id', $array);
        $this->db->delete('student_fees_discounts');

        $message = DELETE_RECORD_CONSTANT . " On student_fees_discounts id " . $fees_discount_id;
        $action = "Delete";
        $record_id = $fees_discount_id;
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
    public function deletedisstdata($fees_discount_id, $student_session_id)
    {
        $this->db->where('fees_discount_id', $fees_discount_id);
        $this->db->where('student_session_id', $student_session_id);
        $this->db->delete('student_fees_discounts');

        $message = DELETE_RECORD_CONSTANT . " On student_fees_discounts id " . $fees_discount_id;
        $action = "Delete";
        $record_id = $fees_discount_id;
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

    public function getStudentFeesDiscount($student_session_id = null)
    {
        // $this->db->select('students.firstname,lastname,student_fees_discounts.id ,student_fees_discounts.student_session_id,student_fees_discounts.status,student_fees_discounts.payment_id,student_fees_discounts.description as `student_fees_discount_description`, student_fees_discounts.fees_discount_id, fees_discounts.name,fees_discounts.code,fees_discount_session.amount,fees_discounts.description,fees_discount_session.session_id')->from('student_fees_discounts');
        // $this->db->join('fees_discounts', 'fees_discounts.id = student_fees_discounts.fees_discount_id');
        // $this->db->join('fees_discount_session', 'fees_discount_session.discount_id = fees_discounts.id');
        // $this->db->join('student_session', 'student_session.id = student_fees_discounts.student_session_id');
        // $this->db->join('students', 'students.id = student_session.id');
        // $this->db->where('student_fees_discounts.student_session_id', $student_session_id);
        // $this->db->where('fees_discount_session.session_id',$this->current_session);
        // $this->db->order_by('student_fees_discounts.id');
        // $query = $this->db->get();
        // return $query->result_array();

        $this->db->select('students.firstname,lastname,student_fees_discounts.id ,student_fees_discounts.student_session_id,student_fees_discounts.status,student_fees_discounts.payment_id,student_fees_discounts.description as `student_fees_discount_description`, student_fees_discounts.fees_discount_id, fees_discounts.name,fees_discounts.code,student_discount_session.amount,fees_discounts.description,student_discount_session.session_id')->from('student_fees_discounts');
        $this->db->join('fees_discounts', 'fees_discounts.id = student_fees_discounts.fees_discount_id');
        $this->db->join('student_discount_session', 'student_discount_session.discount_id = fees_discounts.id');
        $this->db->join('student_session', 'student_session.id = student_fees_discounts.student_session_id');
        $this->db->join('students', 'students.id = student_session.id');
        $this->db->where('student_fees_discounts.student_session_id', $student_session_id);
        $this->db->where('student_fees_discounts.session_id', $this->current_session);
        $this->db->where('student_discount_session.session_id',$this->current_session);
        $this->db->order_by('student_fees_discounts.id');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getStudentFeesDiscount_previous($student_session_id = null,$session_id = null )
    {
        // $this->db->select('students.firstname,lastname,student_fees_discounts.id ,student_fees_discounts.student_session_id,student_fees_discounts.status,student_fees_discounts.payment_id,student_fees_discounts.description as `student_fees_discount_description`, student_fees_discounts.fees_discount_id, fees_discounts.name,fees_discounts.code,fees_discount_session.amount,fees_discounts.description,fees_discount_session.session_id')->from('student_fees_discounts');
        // $this->db->join('fees_discounts', 'fees_discounts.id = student_fees_discounts.fees_discount_id');
        // $this->db->join('fees_discount_session', 'fees_discount_session.discount_id = fees_discounts.id');
        // $this->db->join('student_session', 'student_session.id = student_fees_discounts.student_session_id');
        // $this->db->join('students', 'students.id = student_session.id');
        // $this->db->where('student_fees_discounts.student_session_id', $student_session_id);
        // $this->db->where('fees_discount_session.session_id',$this->current_session);
        // $this->db->order_by('student_fees_discounts.id');
        // $query = $this->db->get();
        // return $query->result_array();
        if(is_null($session_id))
        {$session_id = $this->current_session;}
        $this->db->select('students.firstname,lastname,student_fees_discounts.id ,student_fees_discounts.student_session_id,student_fees_discounts.status,student_fees_discounts.payment_id,student_fees_discounts.description as `student_fees_discount_description`, student_fees_discounts.fees_discount_id, fees_discounts.name,fees_discounts.code,student_discount_session.amount,fees_discounts.description,student_discount_session.session_id')->from('student_fees_discounts');
        $this->db->join('fees_discounts', 'fees_discounts.id = student_fees_discounts.fees_discount_id');
        $this->db->join('student_discount_session', 'student_discount_session.discount_id = fees_discounts.id');
        $this->db->join('student_session', 'student_session.id = student_fees_discounts.student_session_id');
        $this->db->join('students', 'students.id = student_session.id');
        $this->db->where('student_fees_discounts.student_session_id', $student_session_id);
        $this->db->where('student_discount_session.session_id',$session_id);
        $this->db->order_by('student_fees_discounts.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getDiscountNotApplied($student_session_id = null)
    {
        $query = "SELECT fees_discounts.*,student_fees_discounts.id as `student_fees_discount_id`,student_fees_discounts.status,student_fees_discounts.student_session_id,student_fees_discounts.payment_id FROM `student_fees_discounts` INNER JOIN fees_discounts on fees_discounts.id=student_fees_discounts.fees_discount_id WHERE student_session_id=$student_session_id and (student_fees_discounts.payment_id IS NULL OR student_fees_discounts.payment_id = '')";
        $query = $this->db->query($query);
        return $query->result();
    }
    public function getDiscountNotApplieddropdown_previous($student_session_id = null,$session_id=null)
    {
        $this->db->select('fees_discounts.id,fees_discounts.name,fees_discounts.code,fees_discounts.fees_type,fees_discounts.description,student_discount_session.amount,student_discount_session.feepercent,student_discount_session.date_enabled,student_discount_session.date_upto,student_fees_discounts.id as `student_fees_discount_id`,student_fees_discounts.status,student_fees_discounts.student_session_id,student_fees_discounts.payment_id,student_fees_discounts.amount as custom_amount,student_discount_session.session_id');
        $this->db->join('fees_discounts', 'fees_discounts.id=student_fees_discounts.fees_discount_id');
        $this->db->join('student_discount_session', 'fees_discounts.id=student_discount_session.discount_id');
        $this->db->where('student_session_id', $student_session_id);
        $this->db->where('student_fees_discounts.session_id', $session_id); 
        $this->db->where('student_discount_session.session_id', $session_id); 
        //$this->db->where('student_fees_discounts.is_active', 'Yes');
        $this->db->where('student_fees_discounts.payment_id is  null');
        $query = $this->db->get('student_fees_discounts');
       
        return $query->result();
    }
    public function getDiscountNotApplieddropdown($student_session_id = null)
    {
        $this->db->select('fees_discounts.id,fees_discounts.name,fees_discounts.code,fees_discounts.fees_type,fees_discounts.description,student_discount_session.amount,student_discount_session.feepercent,student_discount_session.date_enabled,student_discount_session.date_upto,student_fees_discounts.id as `student_fees_discount_id`,student_fees_discounts.status,student_fees_discounts.student_session_id,student_fees_discounts.payment_id,student_fees_discounts.amount as custom_amount,student_discount_session.session_id');
        $this->db->join('fees_discounts', 'fees_discounts.id=student_fees_discounts.fees_discount_id');
        $this->db->join('student_discount_session', 'fees_discounts.id=student_discount_session.discount_id');
        $this->db->where('student_session_id', $student_session_id);
        $this->db->where('student_fees_discounts.session_id', $this->current_session); 
        $this->db->where('student_discount_session.session_id', $this->current_session); 
        //$this->db->where('student_fees_discounts.is_active', 'Yes');
        $this->db->where('student_fees_discounts.payment_id is  null');
        $query = $this->db->get('student_fees_discounts');
       
        return $query->result();
    }
    public function getDiscountNotApplieddropdown_type($student_session_id = null,$disc_type="m")
    {
        $this->db->select('fees_discounts.id,fees_discounts.name,fees_discounts.code,fees_discounts.fees_type,fees_discounts.description,student_discount_session.amount,student_discount_session.feepercent,student_discount_session.date_enabled,student_discount_session.date_upto,student_fees_discounts.id as `student_fees_discount_id`,student_fees_discounts.status,student_fees_discounts.student_session_id,student_fees_discounts.payment_id,student_fees_discounts.amount as custom_amount,student_discount_session.session_id');
        $this->db->join('fees_discounts', 'fees_discounts.id=student_fees_discounts.fees_discount_id');
        $this->db->join('student_discount_session', 'fees_discounts.id=student_discount_session.discount_id');
        $this->db->where('fees_discounts.fees_type', $disc_type);
        $this->db->where('student_session_id', $student_session_id);
        $this->db->where('student_fees_discounts.session_id', $this->current_session); 
        $this->db->where('student_discount_session.session_id', $this->current_session); 
        //$this->db->where('student_fees_discounts.is_active', 'Yes');
        $this->db->where('student_fees_discounts.payment_id is  null');
        $query = $this->db->get('student_fees_discounts');
       
        return $query->result();
    }    
    public function getOneTimeDiscount()
    {
        $this->db->select('fees_discounts.id,fees_discounts.name,fees_discounts.code,fees_discounts.fees_type,fees_discounts.description,student_discount_session.amount,student_discount_session.feepercent,student_discount_session.date_enabled,student_discount_session.date_upto');
        $this->db->join('student_discount_session', 'fees_discounts.id=student_discount_session.discount_id');
        $this->db->where('fees_discounts.code', 'EFD');
        $this->db->where('student_discount_session.session_id', $this->current_session); 
        $query = $this->db->get('fees_discounts');       
        return $query->result();
    }
    public function getOneTimeDiscount_previous($session_id)
    {
        $this->db->select('fees_discounts.id,fees_discounts.name,fees_discounts.code,fees_discounts.fees_type,fees_discounts.description,student_discount_session.amount,student_discount_session.feepercent,student_discount_session.date_enabled,student_discount_session.date_upto');
        $this->db->join('student_discount_session', 'fees_discounts.id=student_discount_session.discount_id');
        $this->db->where('fees_discounts.code', 'EFD');
        $this->db->where('student_discount_session.session_id', $session_id); 
        $query = $this->db->get('fees_discounts');       
        return $query->result();
    }

    public function getdiscountlistbyid($id)
    {
        $this->db->select('students.firstname,lastname,student_fees_discounts.id ,student_fees_discounts.student_session_id,student_fees_discounts.status,student_fees_discounts.payment_id,student_fees_discounts.description as `student_fees_discount_description`, student_fees_discounts.fees_discount_id,student_fees_discounts.is_active, fees_discounts.name,fees_discounts.code,fees_discounts.amount,fees_discounts.description,fees_discounts.session_id')->from('student_fees_discounts');
        $this->db->join('fees_discounts', 'fees_discounts.id = student_fees_discounts.fees_discount_id');
        $this->db->join('student_session', 'student_session.id = student_fees_discounts.student_session_id');
        $this->db->join('students', 'students.id = student_session.student_id');

        $this->db->where('student_fees_discounts.fees_discount_id', $id);

        $query = $this->db->get();
        return $query->result_array();
    }

    public function update_mark_verify($id, $discount_check)
    {
        $this->db->set('is_active', $discount_check);
        $this->db->where('id', $id);
        $this->db->update('student_fees_discounts');

        $message   = UPDATE_RECORD_CONSTANT . " On  student_fees_discounts " . $id;
        $action    = "Update";
        $record_id = $insert_id = $id;
        $this->log($message, $record_id, $action);
        
        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $insert_id;
        }
    }

    public function checkused_dis($student_session_id,$discount_id)
    {
        
        $this->db->where('student_session_id', $student_session_id);
        $this->db->where('fees_discount_id', $discount_id);
        $this->db->where('is_active', 'Yes');
        
        return $this->db->get('student_fees_discounts')->row_array();
        
        
    }


    public function getdiscountbypayment_id($payment_id,$student_session_id)
    {
        $this->db->where('payment_id', $payment_id);
        $this->db->where('student_session_id', $student_session_id);
        $this->db->where('session_id', $this->current_session);
        return $this->db->get('student_fees_discounts')->result_array();
        
    }
    public function getdiscountbypayment_session_id($payment_id,$student_session_id,$session_id)
    {
        $this->db->where('payment_id', $payment_id);
        $this->db->where('student_session_id', $student_session_id);
        $this->db->where('session_id', $session_id);
        return $this->db->get('student_fees_discounts')->result_array();
        
    }
}
