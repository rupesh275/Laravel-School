<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Studentfeemaster_model extends MY_Model
{
    protected $balance_group;
    protected $balance_type;
    public $current_session;
    public $current_ay_session;

    public function __construct()
    {
        parent::__construct();
        $this->load->config('ci-blog');
        $this->balance_group   = $this->config->item('ci_balance_group');
        $this->balance_type    = $this->config->item('ci_balance_type');
        $this->current_session = $this->setting_model->getCurrentSession();
        $settings              = $this->setting_model->getSetting();
        $this->current_ay_session = $settings->session_id;        
    }

    public function searchAssignFeeByClassSection($class_id = null, $section_id = null, $fee_session_group_id = null, $category = null, $gender = null, $rte = null, $vehroute_id = null)
    {
        $sql = "SELECT IFNULL(`student_fees_master`.`id`, '0') as `student_fees_master_id`,`classes`.`id` AS `class_id`,"
            . " `student_session`.`id` as `student_session_id`, `students`.`id`, "
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
            . "ON `student_session`.`class_id` = `classes`.`id` JOIN `sections` "
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
        if ($category != null) {
            $sql .= " AND `students`.`category_id` =" . $this->db->escape($category);
        }
        if ($gender != null) {
            $sql .= " AND `students`.`gender` =" . $this->db->escape($gender);
        }
        if ($rte != null) {
            $sql .= " AND `students`.`rte` =" . $this->db->escape($rte);
        }
        if ($vehroute_id != null) {
            $sql .= " AND `students`.`vehroute_id` =" . $this->db->escape($vehroute_id);
        }
        $sql .= " ORDER BY `student_session`.`class_id`, `student_session`.`section_id`, `student_session`.`roll_no`";

        $query = $this->db->query($sql);
        return $query->result_array();
    }
    public function add_direct($data)
    {
            $this->db->insert('student_fees_master', $data);
            return $this->db->insert_id();
    }
    public function add($data)
    {

        $this->db->where('student_session_id', $data['student_session_id']);
        $this->db->where('fee_session_group_id', $data['fee_session_group_id']);
        $q = $this->db->get('student_fees_master');

        if ($q->num_rows() > 0) {
            return $q->row()->id;
        } else {
            $this->db->insert('student_fees_master', $data);
            return $this->db->insert_id();

            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On student_fees_master " . $insert_id;
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

    public function addPreviousBal($student_data, $due_date)
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);
        $fee_group_exists = $this->feegroup_model->checkGroupExistsByName($this->balance_group);
        $fee_type_exists  = $this->feetype_model->checkFeetypeByName($this->balance_type);
        $fee_group_id     = 0;
        $fee_type_id      = 0;
        if (!$fee_group_exists) {
            $this->db->insert('fee_groups', array('name' => $this->balance_group, 'is_system' => 1));
            $fee_group_id = $this->db->insert_id();
        } else {
            $fee_group_id = $fee_group_exists->id;
        }

        if (!$fee_type_exists) {
            $this->db->insert('feetype', array('type' => $this->balance_type, 'code' => $this->balance_type, 'is_system' => 1));
            $fee_type_id = $this->db->insert_id();
        } else {
            $fee_type_id = $fee_type_exists->id;
        }
        $to_be_insert = array(
            'session_id'           => $this->current_session,
            'fee_groups_id'        => $fee_group_id,
            'feetype_id'           => $fee_type_id,
            'fee_session_group_id' => 0,
            'due_date'             => $due_date,
        );
        $parentid = $this->feesessiongroup_model->group_exists($to_be_insert['fee_groups_id']);

        $to_be_insert['fee_session_group_id'] = $parentid;

        $session_group_exists = $this->feesessiongroup_model->checkExists($to_be_insert);
        if (!$session_group_exists) {
            $this->db->insert('fee_groups_feetype', $to_be_insert);
        } else {
            $this->db->where('id', $session_group_exists);
            $this->db->update('fee_groups_feetype', $to_be_insert);
        }
        $student_list = array();
        if (isset($student_data) && !empty($student_data)) {

            $total_rec = count($student_data);
            for ($i = 0; $i < $total_rec; $i++) {
                $student_list[]                           = $student_data[$i]['student_session_id'];
                $student_data[$i]['id']                   = 0;
                $student_data[$i]['fee_session_group_id'] = $parentid;
            }
            $check_insert_feemaster = $this->selectInArray($parentid, $student_list);
            if (!empty($check_insert_feemaster)) {
                $insert_new_student = array();
                foreach ($student_data as $student_key => $student_value) {
                    $student_data[$student_key]['id'] = $this->findValueExists($check_insert_feemaster, $student_value['student_session_id']);
                    if ($student_data[$student_key]['id'] == 0) {
                        $insert_new_student[] = $student_data[$student_key];
                        unset($student_data[$student_key]);
                    }
                }

                if (!empty($insert_new_student)) {
                    $this->db->insert_batch('student_fees_master', $insert_new_student);
                }
                $this->db->update_batch('student_fees_master', $student_data, 'id');
            } else {
                $this->db->insert_batch('student_fees_master', $student_data);
            }
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function findValueExists($array, $find)
    {
        $id = 0;
        foreach ($array as $x => $x_value) {
            if ($x_value->student_session_id == $find) {
                return $x_value->id;
            }
        }
        return $id;
    }

    public function selectInArray($fee_session_groups, $student_session_array)
    {

        $this->db->where('fee_session_group_id', $fee_session_groups);
        $this->db->where_in('student_session_id', $student_session_array);
        $q      = $this->db->get('student_fees_master');
        $result = $q->result();
        return $result;
    }

    public function delete($fee_session_groups, $array)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('fee_session_group_id', $fee_session_groups);
        $this->db->where_in('student_session_id', $array);
        $this->db->delete('student_fees_master');
        $message   = DELETE_RECORD_CONSTANT . " On  student fees master " . $fee_session_groups;
        $action    = "Delete";
        $record_id = $fee_session_groups;
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

    public function getBalanceMasterRecord($group_name, $student_session_array)
    {
        $sql = "select * from student_fees_master where student_session_id in $student_session_array and fee_session_group_id=(SELECT id FROM `fee_session_groups` where fee_groups_id=(SELECT id FROM `fee_groups` WHERE name=" . "'" . $group_name . "'" . ") and session_id=$this->current_session)";

        $query  = $this->db->query($sql);
        $result = $query->result();
        return $result;
    }

    public function getStudentFeesByClassSectionStudent($class_id = NULL, $section_id = NULL, $student_id = NULL)
    {
        $where_condition = array();
        if ($class_id != NULL) {
            $where_condition[] = " and student_session.class_id=" . $class_id;
        }
        if ($section_id != NULL) {
            $where_condition[] = " and student_session.section_id=" . $section_id;
        }
        if ($student_id != NULL) {
            $where_condition[] = " and student_session.student_id=" . $student_id;
        }

        $where_condition_string = implode(" ", $where_condition);


        $sql = "SELECT student_fees_master.*,student_session.id as `student_session_id`,students.firstname,students.middlename,students.lastname,student_session.class_id,classes.class,sections.section,students.category_id,students.image,students.father_name,students.admission_no,students.mobileno,student_session.roll_no,students.rte, IFNULL(categories.category, '') as `category` FROM `student_fees_master` INNER JOIN student_session on student_session.id=student_fees_master.student_session_id INNER JOIN students on students.id=student_session.student_id INNER JOIN classes on classes.id =student_session.class_id left join  categories on students.category_id = categories.id INNER join sections on sections.id= student_session.section_id  WHERE student_session.session_id=" . $this->db->escape($this->current_session) . $where_condition_string;

        $query        = $this->db->query($sql);
        $result       = $query->result();
        $student_fees = array();
        if (!empty($result)) {

            foreach ($result as $result_key => $result_value) {
                $fee_session_group_id   = $result_value->fee_session_group_id;
                $student_fees_master_id = $result_value->id;
                $result_value->fees     = $this->getDueFeeByFeeSessionGroup($fee_session_group_id, $student_fees_master_id);

                if ($result_value->is_system != 0) {
                    $result_value->fees[0]->amount = $result_value->amount;
                }

                if (!array_key_exists($result_value->student_session_id, $student_fees)) {

                    $student_fees[$result_value->student_session_id] = array(
                        'student_session_id' => $result_value->student_session_id,
                        'firstname' => $result_value->firstname,
                        'middlename' => $result_value->middlename,
                        'lastname' => $result_value->lastname,
                        'class_id' => $result_value->class_id,
                        'class' => $result_value->class,
                        'section' => $result_value->section,
                        'father_name' => $result_value->father_name,
                        'admission_no' => $result_value->admission_no,
                        'mobileno' => $result_value->mobileno,
                        'roll_no' => $result_value->roll_no,
                        'category_id' => $result_value->category_id,
                        'category' => $result_value->category,
                        'rte' => $result_value->rte,
                        'image' => $result_value->image
                    ); //the magic
                    $student_fees[$result_value->student_session_id]['student_discount_fee'] = $this->feediscount_model->getStudentFeesDiscount($result_value->student_session_id);
                }

                $student_fees[$result_value->student_session_id]['fees'][] = $result_value->fees;
            }
        }

        return $student_fees;
    }

    public function getStudentFees($student_session_id)
    {
        $sql    = "SELECT `student_fees_master`.*,fee_groups.name,fee_groups.dis_name,fee_groups.fees_type FROM `student_fees_master` INNER JOIN fee_session_groups on student_fees_master.fee_session_group_id=fee_session_groups.id INNER JOIN fee_groups on fee_groups.id=fee_session_groups.fee_groups_id  WHERE `student_session_id` = " . $student_session_id . " ORDER BY `student_fees_master`.`id`";
        $query  = $this->db->query($sql);
        $result = $query->result();
        
        
        if (!empty($result)) {
            foreach ($result as $result_key => $result_value) {

                $fee_session_group_id   = $result_value->fee_session_group_id;
                $student_fees_master_id = $result_value->id;
                $result_value->fees     = $this->getDueFeeByFeeSessionGroup($fee_session_group_id, $student_fees_master_id);

                if ($result_value->is_system != 0) {
                    $result_value->fees[0]->amount = $result_value->amount;
                }
            }
        }
   

        return $result;
    }

    public function getStudentFees_main($student_session_id)
    {
        $sql    = "SELECT `student_fees_master`.*,fee_groups.name,fee_groups.dis_name,fee_groups.fees_type FROM `student_fees_master` INNER JOIN fee_session_groups on student_fees_master.fee_session_group_id=fee_session_groups.id INNER JOIN fee_groups on fee_groups.id=fee_session_groups.fee_groups_id  WHERE `student_session_id` = " . $student_session_id . " And fee_groups.fees_type='m' ORDER BY `student_fees_master`.`id`";
        $query  = $this->db->query($sql);
        $result = $query->result();
        
        
        if (!empty($result)) {
            foreach ($result as $result_key => $result_value) {

                $fee_session_group_id   = $result_value->fee_session_group_id;
                $student_fees_master_id = $result_value->id;
                $result_value->fees     = $this->getDueFeeByFeeSessionGroup($fee_session_group_id, $student_fees_master_id);

                if ($result_value->is_system != 0) {
                    $result_value->fees[0]->amount = $result_value->amount;
                }
            }
        }
   

        return $result;
    }
    public function get_total_fees($st_fees_array)
    {
        ini_set ('display_errors', 1); ini_set ('display_startup_errors', 1); error_reporting (E_ALL);        
        $fees_total = 0;$fees_disc = 0;$pay_amt=0;
        foreach ($st_fees_array as $student_total_fees_key => $student_total_fees_value) {
            foreach ($student_total_fees_value as $ff) {
                $fees = $ff->fees;
                foreach ($fees as $key => $each_fee_value) {
                    $pay_amt += $each_fee_value->amount;
                    $amount_detail = json_decode($each_fee_value->amount_detail);
                    if (is_object($amount_detail)) {
                        foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                            $fees_total += $amount_detail_value->amount;
                            $fees_disc += $amount_detail_value->amount_discount;
                        }
                    }
                }                
            }
        }
        if($fees_total + $fees_disc > 0)
        { 
            $data = array(
                "pay_amt" => $pay_amt,
                "fees" => $fees_total,
                "disc" => $fees_disc,
            );
        }
        else
        {
            $data = array();
        }
        return $data;
    }
    public function getStudentFees_others($student_session_id)
    {
        $sql    = "SELECT `student_fees_master`.*,fee_groups.name,fee_groups.dis_name,fee_groups.fees_type FROM `student_fees_master` INNER JOIN fee_session_groups on student_fees_master.fee_session_group_id=fee_session_groups.id INNER JOIN fee_groups on fee_groups.id=fee_session_groups.fee_groups_id  WHERE student_fees_master.session_id = '".$this->current_session."' and  `student_session_id` = " . $student_session_id . " And fee_groups.fees_type='o' ORDER BY `student_fees_master`.`id`";
        $query  = $this->db->query($sql);
        $result = $query->result();
        if (!empty($result)) {
            foreach ($result as $result_key => $result_value) {
                $fee_session_group_id   = $result_value->fee_session_group_id;
                $student_fees_master_id = $result_value->id;
                $result_value->fees     = $this->getDueFeeByFeeSessionGroup($fee_session_group_id, $student_fees_master_id);
                if ($result_value->is_system != 0) {
                    $result_value->fees[0]->amount = $result_value->amount;
                }
            }
        }
        return $result;
    }
    public function getStudentFees_others_nosession($student_session_id)
    {
        $sql    = "SELECT `student_fees_master`.*,fee_groups.name,fee_groups.dis_name,fee_groups.fees_type FROM `student_fees_master` INNER JOIN fee_session_groups on student_fees_master.fee_session_group_id=fee_session_groups.id INNER JOIN fee_groups on fee_groups.id=fee_session_groups.fee_groups_id  WHERE `student_session_id` = " . $student_session_id . " And fee_groups.fees_type='o' ORDER BY `student_fees_master`.`id`";
        $query  = $this->db->query($sql);
        $result = $query->result();
        if (!empty($result)) {
            foreach ($result as $result_key => $result_value) {
                $fee_session_group_id   = $result_value->fee_session_group_id;
                $student_fees_master_id = $result_value->id;
                $result_value->fees     = $this->getDueFeeByFeeSessionGroup($fee_session_group_id, $student_fees_master_id);
                if ($result_value->is_system != 0) {
                    $result_value->fees[0]->amount = $result_value->amount;
                }
            }
        }
        return $result;
    }    
    public function getStudentFeesarray($student_session_id)
    {
        $sql    = "SELECT `student_fees_master`.*,fee_groups.name,fee_groups.fees_type FROM `student_fees_master` INNER JOIN fee_session_groups on student_fees_master.fee_session_group_id=fee_session_groups.id INNER JOIN fee_groups on fee_groups.id=fee_session_groups.fee_groups_id  WHERE `student_session_id` IN (" . $student_session_id . ") ORDER BY `student_fees_master`.`id`";
        $query  = $this->db->query($sql);
        $result = $query->result();
        if (!empty($result)) {
            foreach ($result as $result_key => $result_value) {

                $fee_session_group_id   = $result_value->fee_session_group_id;
                $student_fees_master_id = $result_value->id;
                $result_value->fees     = $this->getDueFeeByFeeSessionGroup($fee_session_group_id, $student_fees_master_id);

                if ($result_value->is_system != 0) {
                    $result_value->fees[0]->amount = $result_value->amount;
                }
            }
        }

        return $result;
    }

    public function getDueFeeByFeeSessionGroup($fee_session_groups_id, $student_fees_master_id)
    {
        $sql = "SELECT student_fees_master.*,fee_groups_feetype.id as `fee_groups_feetype_id`,fee_groups_feetype.amount,fee_groups_feetype.due_date,fee_groups_feetype.fine_amount,fee_groups_feetype.fee_groups_id,fee_groups.name,fee_groups_feetype.feetype_id,feetype.code,feetype.type, IFNULL(student_fees_deposite.id,0) as `student_fees_deposite_id`, IFNULL(student_fees_deposite.amount_detail,0) as `amount_detail` FROM `student_fees_master` INNER JOIN fee_session_groups on fee_session_groups.id = student_fees_master.fee_session_group_id INNER JOIN fee_groups_feetype on  fee_groups_feetype.fee_session_group_id = fee_session_groups.id  INNER JOIN fee_groups on fee_groups.id=fee_groups_feetype.fee_groups_id INNER JOIN feetype on feetype.id=fee_groups_feetype.feetype_id LEFT JOIN student_fees_deposite on student_fees_deposite.student_fees_master_id=student_fees_master.id and student_fees_deposite.fee_groups_feetype_id=fee_groups_feetype.id WHERE student_fees_master.fee_session_group_id =" . $fee_session_groups_id . " and student_fees_master.id=" . $student_fees_master_id . " order by fee_groups_feetype.due_date ASC";

        $query = $this->db->query($sql);
        return $query->result();
    }
    public function getDueFeeByFeeSessionGroup_for_dues($fee_session_groups_id, $student_fees_master_id)
    {
        $sql = "SELECT student_fees_master.*,fee_groups_feetype.id as `fee_groups_feetype_id`,fee_groups_feetype.amount,fee_groups_feetype.due_date,fee_groups_feetype.fine_amount,fee_groups_feetype.fee_groups_id,fee_groups.name,fee_groups_feetype.feetype_id,feetype.code,feetype.type, IFNULL(student_fees_deposite.id,0) as `student_fees_deposite_id`, IFNULL(student_fees_deposite.amount_detail,0) as `amount_detail` FROM `student_fees_master` INNER JOIN fee_session_groups on fee_session_groups.id = student_fees_master.fee_session_group_id INNER JOIN fee_groups_feetype on  fee_groups_feetype.fee_session_group_id = fee_session_groups.id  INNER JOIN fee_groups on fee_groups.id=fee_groups_feetype.fee_groups_id INNER JOIN feetype on feetype.id=fee_groups_feetype.feetype_id LEFT JOIN student_fees_deposite on student_fees_deposite.student_fees_master_id=student_fees_master.id and student_fees_deposite.fee_groups_feetype_id=fee_groups_feetype.id WHERE feetype.code != 'APF' AND student_fees_master.fee_session_group_id =" . $fee_session_groups_id . " and student_fees_master.id=" . $student_fees_master_id . " order by fee_groups_feetype.due_date ASC";

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getDueFeeByFeeSessionGroupFeetype($fee_session_groups_id, $student_fees_master_id, $fee_groups_feetype_id)
    {
        $sql = "SELECT student_fees_master.id,student_fees_master.session_id,student_fees_master.is_system,student_fees_master.student_session_id,student_fees_master.fee_session_group_id,student_fees_master.amount as `student_fees_master_amount`,fee_groups_feetype.id as `fee_groups_feetype_id`,students.id as student_id,students.firstname,students.middlename,students.admission_no,students.lastname,student_session.class_id,classes.class,sections.section,students.guardian_name,students.father_name,student_session.section_id,student_session.student_id,fee_groups_feetype.amount,fee_groups_feetype.due_date,fee_groups_feetype.fine_amount,fee_groups_feetype.fee_groups_id,fee_groups.name,fee_groups_feetype.feetype_id,feetype.code,feetype.type, IFNULL(student_fees_deposite.id,0) as `student_fees_deposite_id`, IFNULL(student_fees_deposite.amount_detail,0) as `amount_detail` FROM `student_fees_master` INNER JOIN fee_session_groups on fee_session_groups.id = student_fees_master.fee_session_group_id INNER JOIN fee_groups_feetype on  fee_groups_feetype.fee_session_group_id = fee_session_groups.id  INNER JOIN fee_groups on fee_groups.id=fee_groups_feetype.fee_groups_id INNER JOIN feetype on feetype.id=fee_groups_feetype.feetype_id LEFT JOIN student_fees_deposite on student_fees_deposite.student_fees_master_id=student_fees_master.id and student_fees_deposite.fee_groups_feetype_id=fee_groups_feetype.id INNER JOIN student_session on student_session.id= student_fees_master.student_session_id INNER JOIN classes on classes.id= student_session.class_id INNER JOIN sections on sections.id= student_session.section_id INNER JOIN students on students.id=student_session.student_id  WHERE student_fees_master.fee_session_group_id =" . $fee_session_groups_id . " and student_fees_master.id=" . $student_fees_master_id . " and fee_groups_feetype.id= " . $fee_groups_feetype_id;
        $query = $this->db->query($sql);
        return $query->row();
    }
        
    public function create_receipt()
    {
        //$tb_name = 'fee_receipt_no_' . $this->current_session;
        $tb_name = 'fee_receipt_no_' . $this->current_ay_session;
        $dt = array(
            'payment' => 1
        );
        $this->db->insert($tb_name, $dt);
        return $this->db->insert_id();
    }
    public function fee_deposit_bulk($bulk_data, $student_fees_discount_id = null)
    {
        if (!empty($bulk_data)) {
            $collected_fees = array();
            $receipt_id = $this->create_receipt();
            $this->db->trans_start();
            foreach ($bulk_data as $fee_key => $fee_data) {
                $this->db->where('student_fees_master_id', $fee_data['student_fees_master_id']);
                $this->db->where('fee_groups_feetype_id', $fee_data['fee_groups_feetype_id']);
                $q = $this->db->get('student_fees_deposite');
                if ($q->num_rows() > 0) {
                    $desc = $fee_data['amount_detail']['description'];
                    $data[$fee_key]['receipt_id']              = $receipt_id;
                    $row  = $q->row();
                    $this->db->where('id', $row->id);
                    $a                                   = json_decode($row->amount_detail, true);
                    $inv_no                              = max(array_keys($a)) + 1;
                    $fee_data['amount_detail']['inv_no'] = $inv_no;
                    $a[$inv_no]                          = $fee_data['amount_detail'];
                    $fee_data['amount_detail']           = json_encode($a);
                    $this->db->update('student_fees_deposite', $fee_data);
                } else {
                    $fee_data['amount_detail']['inv_no'] = $receipt_id;
                    $desc                                = $fee_data['amount_detail']['description'];
                    $fee_data['amount_detail']           = json_encode(array($receipt_id => $fee_data['amount_detail']));

                    $this->db->insert('student_fees_deposite', $fee_data);
                    $inserted_id = $this->db->insert_id();
                    $message = INSERT_RECORD_CONSTANT . " On student fees deposite id " . $inserted_id;
                    $action = "Insert";
                    $record_id = $inserted_id;
                    $this->log($message, $record_id, $action);
                }
            }
            $this->db->trans_complete();
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                return false;
            } else {
                $this->db->trans_commit();
                return true;
            }
        }
    }
    public function fee_deposit($data, $send_to, $student_fees_discount_id = null, $fee_type = null)
    {
        $this->db->where('student_fees_master_id', $data['student_fees_master_id']);
        $this->db->where('fee_groups_feetype_id', $data['fee_groups_feetype_id']);
        $q = $this->db->get('student_fees_deposite');
        if ($q->num_rows() > 0) {
            $receipt_id      = $this->create_receipt();
            $desc = $data['amount_detail']['description'];
            $this->db->trans_start(); // Query will be rolled back
            $row = $q->row();
            $this->db->where('id', $row->id);
            $a                               = json_decode($row->amount_detail, true);
            $inv_no                          = $receipt_id; //max(array_keys($a)) + 1;
            $data['amount_detail']['inv_no'] = $inv_no;
            $a[$inv_no]                      = $data['amount_detail'];
            $data['amount_detail']           = json_encode($a);
            $this->db->update('student_fees_deposite', $data);

            if ($student_fees_discount_id != null) {
                $this->db->where('id', $student_fees_discount_id);
                $this->db->update('student_fees_discounts', array('status' => 'applied', 'description' => $desc, 'payment_id' => $receipt_id, 'session_id' => $this->current_session));

                $message = UPDATE_RECORD_CONSTANT . " On  student fees discounts id " . $student_fees_discount_id;
                $action = "Update";
                $record_id = $student_fees_discount_id;
                $this->log($message, $record_id, $action);
            }

            $this->db->trans_complete();
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();

                return false;
            } else {
                $this->db->trans_commit();
                return json_encode(array('invoice_id' => $row->id, 'sub_invoice_id' => $inv_no));
            }
        } else {
            $this->db->trans_start(); // Query will be rolled back
            $collected_fees = array();
            $receipt_id = $this->create_receipt();
            $data['amount_detail']['inv_no'] = $receipt_id;
            $desc                            = $data['amount_detail']['description'];
            $data['amount_detail']           = json_encode(array($receipt_id => $data['amount_detail']));
            $data['receipt_id']              = $receipt_id;
            $this->db->insert('student_fees_deposite', $data);
            $inserted_id = $this->db->insert_id();
            // $this->db->set('receipt_type',$fee_type);
            // $this->db->where('id',$receipt_id);
            // $this->db->update('fee_receipt_no_'.$this->current_session);
            if ($student_fees_discount_id != null) {
                $this->db->where('id', $student_fees_discount_id);
                $this->db->update('student_fees_discounts', array('status' => 'applied', 'description' => $desc, 'payment_id' => $receipt_id, 'session_id' => $this->current_session));
            }
            $this->db->trans_complete(); # Completing transaction

            if ($this->db->trans_status() === false) {

                $this->db->trans_rollback();
                return false;
            } else {
                $this->db->trans_commit();
                return json_encode(array('invoice_id' => $inserted_id, 'sub_invoice_id' => $receipt_id));
            }
        }
    }



    public function get_feesreceived_by()
    {
        $result = $this->db->select('CONCAT_WS(" ",staff.name,staff.surname) as name, staff.employee_id,staff.id')->from('staff')->join('staff_roles', 'staff.id=staff_roles.staff_id')->where('staff.is_active', '1')->get()->result_array();
        foreach ($result as $key => $value) {
            $data[$value['id']] = $value['name'] . " (" . $value['employee_id'] . ")";
        }
        return $data;
    }
    public function get_feesreceived_by2()
    {
        $result = $this->db->select('CONCAT_WS(" ",staff.name,staff.surname) as name, staff.employee_id,staff.id')->from('staff')->join('staff_roles', 'staff.id=staff_roles.staff_id')->where('staff.is_active', '1')->get()->result_array();
        foreach ($result as $key => $value) {
            $data[$value['id']] = $value['name'];
        }
        return $data;
    }

    public function getFeeCollectionReport($start_date, $end_date, $feetype_id = null, $received_by = null, $group = null)
    {

        $this->db->select('`student_fees_deposite`.*,students.firstname,students.middlename,students.lastname,student_session.class_id,classes.class,sections.section,student_session.section_id,student_session.student_id,`fee_groups`.`name`, `feetype`.`type`, `feetype`.`code`,student_fees_master.student_session_id,students.admission_no')->from('student_fees_deposite');
        $this->db->join('fee_groups_feetype', 'fee_groups_feetype.id = student_fees_deposite.fee_groups_feetype_id');
        $this->db->join('fee_groups', 'fee_groups.id = fee_groups_feetype.fee_groups_id');
        $this->db->join('feetype', 'feetype.id = fee_groups_feetype.feetype_id');
        $this->db->join('student_fees_master', 'student_fees_master.id=student_fees_deposite.student_fees_master_id');
        $this->db->join('student_session', 'student_session.id= student_fees_master.student_session_id', 'left');
        $this->db->join('classes', 'classes.id= student_session.class_id');
        $this->db->join('sections', 'sections.id= student_session.section_id');
        $this->db->join('students', 'students.id=student_session.student_id');
        if ($feetype_id != null) {
            $this->db->where('fee_groups_feetype.feetype_id', $feetype_id);
        }
        $this->db->order_by('student_fees_deposite.id');

        $query        = $this->db->get();
        $result_value = $query->result();
        $return_array = array();
        if (!empty($result_value)) {
            $st_date = strtotime($start_date);
            $ed_date = strtotime($end_date);
            foreach ($result_value as $key => $value) {
                if ($received_by != null) {

                    $return = $this->findObjectByCollectId($value, $st_date, $ed_date, $received_by);
                } else {

                    $return = $this->findObjectById($value, $st_date, $ed_date);
                }

                if (!empty($return)) {

                    foreach ($return as $r_key => $r_value) {

                        $a['id']                     = $value->id;
                        $a['student_fees_master_id'] = $value->student_fees_master_id;
                        $a['fee_groups_feetype_id']  = $value->fee_groups_feetype_id;
                        $a['admission_no']           = $value->admission_no;
                        $a['firstname']              = $value->firstname;
                        $a['middlename']             = $value->middlename;
                        $a['lastname']               = $value->lastname;
                        $a['class_id']               = $value->class_id;
                        $a['class']                  = $value->class;
                        $a['section']                = $value->section;
                        $a['student_id']             = $value->student_id;
                        $a['name']                   = $value->name;
                        $a['type']                   = $value->type;
                        $a['code']                   = $value->code;
                        $a['student_session_id']     = $value->student_session_id;
                        $a['amount']                 = $r_value->amount;
                        $a['date']                   = $r_value->date;
                        $a['amount_discount']        = $r_value->amount_discount;
                        $a['amount_fine']            = $r_value->amount_fine;
                        $a['description']            = $r_value->description;
                        $a['payment_mode']           = $r_value->payment_mode;
                        $a['inv_no']                 = $r_value->inv_no;
                        $a['received_by']            = $r_value->received_by;
                        if (isset($r_value->received_by)) {

                            $a['received_by']     = $r_value->received_by;
                            $a['received_byname'] = $this->staff_model->get_StaffNameById($r_value->received_by);
                        } else {

                            $a['received_by']     = '';
                            $a['received_byname'] = array('name' => '', 'employee_id' => '', 'id' => '');
                        }

                        $return_array[] = $a;
                    }
                }
            }
        }

        return $return_array;
    }

    public function getFeeBetweenDate($start_date, $end_date)
    {
        $this->db->select('`student_fees_deposite`.*,students.firstname,students.middlename,students.lastname,student_session.class_id,classes.class,sections.section,student_session.section_id,student_session.student_id,`fee_groups`.`name`, `feetype`.`type`, `feetype`.`code`,student_fees_master.student_session_id')->from('student_fees_deposite');
        $this->db->join('fee_groups_feetype', 'fee_groups_feetype.id = student_fees_deposite.fee_groups_feetype_id');
        $this->db->join('fee_groups', 'fee_groups.id = fee_groups_feetype.fee_groups_id');
        $this->db->join('feetype', 'feetype.id = fee_groups_feetype.feetype_id');
        $this->db->join('student_fees_master', 'student_fees_master.id=student_fees_deposite.student_fees_master_id');
        $this->db->join('student_session', 'student_session.id= student_fees_master.student_session_id');
        $this->db->join('classes', 'classes.id= student_session.class_id');
        $this->db->join('sections', 'sections.id= student_session.section_id');
        $this->db->join('students', 'students.id=student_session.student_id');
        $this->db->order_by('student_fees_deposite.id');
        $query        = $this->db->get();
        $result_value = $query->result();
        $return_array = array();
        if (!empty($result_value)) {
            $st_date = strtotime($start_date);
            $ed_date = strtotime($end_date);
            foreach ($result_value as $key => $value) {
                $return = $this->findObjectById($value, $st_date, $ed_date);
                if (!empty($return)) {
                    foreach ($return as $r_key => $r_value) {
                        $a['id']                     = $value->id;
                        $a['student_fees_master_id'] = $value->student_fees_master_id;
                        $a['fee_groups_feetype_id']  = $value->fee_groups_feetype_id;
                        $a['firstname']              = $value->firstname;
                        $a['lastname']               = $value->lastname;
                        $a['class_id']               = $value->class_id;
                        $a['class']                  = $value->class;
                        $a['section']                = $value->section;
                        $a['section_id']             = $value->section_id;
                        $a['student_id']             = $value->student_id;
                        $a['name']                   = $value->name;
                        $a['type']                   = $value->type;
                        $a['code']                   = $value->code;
                        $a['student_session_id']     = $value->student_session_id;
                        $a['amount']                 = $r_value->amount;
                        $a['date']                   = $r_value->date;
                        $a['amount_discount']        = $r_value->amount_discount;
                        $a['amount_fine']            = $r_value->amount_fine;
                        $a['description']            = $r_value->description;
                        $a['payment_mode']           = $r_value->payment_mode;
                        $a['inv_no']                 = $r_value->inv_no;

                        $return_array[] = $a;
                    }
                }
            }
        }

        return $return_array;
    }

    public function getDepositAmountBetweenDate($start_date, $end_date)
    {

        $this->db->select('`student_fees_deposite`.*')->from('student_fees_deposite');
        $this->db->order_by('student_fees_deposite.id');
        $query        = $this->db->get();
        $result_value = $query->result();
        $return_array = array();
        if (!empty($result_value)) {
            $st_date = strtotime($start_date);
            $ed_date = strtotime($end_date);
            foreach ($result_value as $key => $value) {
                $return = $this->findObjectById($value, $st_date, $ed_date);

                if (!empty($return)) {
                    foreach ($return as $r_key => $r_value) {
                        $a                    = array();
                        $a['amount']          = $r_value->amount;
                        $a['date']            = $r_value->date;
                        $a['amount_discount'] = $r_value->amount_discount;
                        $a['amount_fine']     = $r_value->amount_fine;
                        $a['description']     = $r_value->description;
                        $a['payment_mode']    = $r_value->payment_mode;
                        $a['inv_no']          = $r_value->inv_no;
                        $return_array[]       = $a;
                    }
                }
            }
        }

        return $return_array;
    }

    public function findObjectAmount($array, $st_date, $ed_date)
    {

        $ar     = json_decode($array->amount_detail);
        $array  = array();
        $amount = 0;
        for ($i = $st_date; $i <= $ed_date; $i += 86400) {
            $find = date('Y-m-d', $i);
            foreach ($ar as $row_key => $row_value) {
                if ($row_value->date == $find) {

                    $array[] = $row_value;
                }
            }
        }
        return $array;
    }

    public function findObjectById($array, $st_date, $ed_date)
    {

        $ar = json_decode($array->amount_detail);

        $array = array();
        for ($i = $st_date; $i <= $ed_date; $i += 86400) {
            $find = date('Y-m-d', $i);
            foreach ($ar as $row_key => $row_value) {
                if ($row_value->date == $find) {
                    $array[] = $row_value;
                }
            }
        }

        return $array;
    }

    public function findObjectByCollectId($array, $st_date, $ed_date, $receivedBy)
    {

        $ar = json_decode($array->amount_detail);

        $array = array();
        for ($i = $st_date; $i <= $ed_date; $i += 86400) {
            $find = date('Y-m-d', $i);
            foreach ($ar as $row_key => $row_value) {
                if (isset($row_value->received_by)) {
                    if ($row_value->date == $find && $row_value->received_by == $receivedBy) {
                        $array[] = $row_value;
                    }
                }
            }
        }

        return $array;
    }

    public function getFeeByInvoice($invoice_id, $sub_invoice_id)
    {
        $this->db->select('`student_fees_deposite`.*,students.id as std_id,students.firstname,students.middlename,students.lastname,students.admission_no,student_session.class_id,classes.class,sections.section,student_session.section_id,student_session.student_id,`fee_groups`.`name`, `feetype`.`type`, `feetype`.`code`,student_fees_master.student_session_id')->from('student_fees_deposite');
        $this->db->join('fee_groups_feetype', 'fee_groups_feetype.id = student_fees_deposite.fee_groups_feetype_id');
        $this->db->join('fee_groups', 'fee_groups.id = fee_groups_feetype.fee_groups_id');
        $this->db->join('feetype', 'feetype.id = fee_groups_feetype.feetype_id');
        $this->db->join('student_fees_master', 'student_fees_master.id=student_fees_deposite.student_fees_master_id');
        $this->db->join('student_session', 'student_session.id= student_fees_master.student_session_id');
        $this->db->join('classes', 'classes.id= student_session.class_id');
        $this->db->join('sections', 'sections.id= student_session.section_id');
        $this->db->join('students', 'students.id=student_session.student_id');
        $this->db->where('student_fees_deposite.id', $invoice_id);
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            $result = $q->row();

            $res    = json_decode($result->amount_detail);
            $a      = (array) $res;
            foreach ($a as $value) {
                if (!empty($value)) {
                    foreach ($value as $valRow) {

                        if ($value->inv_no == $sub_invoice_id) {
                            return $result;
                        }
                    }
                }
            }
        }

        return false;
    }

    public function getamountdetails($sub_invoice_id)
    {
        return $q = $this->db->query("select * FROM `student_fees_deposite` WHERE JSON_EXTRACT(amount_detail,'$.*.inv_no') LIKE '%" . $sub_invoice_id . "%'")->result();
    }

    public function getFeeByInvoicereturn($invoice_id, $sub_invoice_id, $student_session_id)
    {
        $this->db->select('`student_fees_deposite`.*,students.id as std_id,students.firstname,students.middlename,students.lastname,students.admission_no,student_session.class_id,classes.class,sections.section,student_session.section_id,student_session.student_id,`fee_groups`.`name`,`dis_name`, `feetype`.`type`, `feetype`.`code`,student_fees_master.student_session_id,fee_groups_feetype.amount as main_amount')->from('student_fees_deposite');
        $this->db->join('fee_groups_feetype', 'fee_groups_feetype.id = student_fees_deposite.fee_groups_feetype_id');
        $this->db->join('fee_groups', 'fee_groups.id = fee_groups_feetype.fee_groups_id');
        $this->db->join('feetype', 'feetype.id = fee_groups_feetype.feetype_id');
        $this->db->join('student_fees_master', 'student_fees_master.id=student_fees_deposite.student_fees_master_id');
        $this->db->join('student_session', 'student_session.id= student_fees_master.student_session_id');
        $this->db->join('classes', 'classes.id= student_session.class_id');
        $this->db->join('sections', 'sections.id= student_session.section_id');
        $this->db->join('students', 'students.id=student_session.student_id');
        $this->db->like("JSON_EXTRACT(student_fees_deposite.amount_detail,'$.*.inv_no')", $sub_invoice_id);
        $this->db->like("JSON_EXTRACT(student_fees_deposite.amount_detail,'$.*.student_session_id')", $student_session_id);

        // $q =$this->db->query("SELECT `student_fees_deposite`.*, `students`.`id` as `std_id`, `students`.`firstname`, `students`.`middlename`, `students`.`lastname`, `students`.`admission_no`, `student_session`.`class_id`, `classes`.`class`, `sections`.`section`, `student_session`.`section_id`, `student_session`.`student_id`, `fee_groups`.`name`, `feetype`.`type`, `feetype`.`code`, `student_fees_master`.`student_session_id` FROM `student_fees_deposite` JOIN `fee_groups_feetype` ON `fee_groups_feetype`.`id` = `student_fees_deposite`.`fee_groups_feetype_id` JOIN `fee_groups` ON `fee_groups`.`id` = `fee_groups_feetype`.`fee_groups_id` JOIN `feetype` ON `feetype`.`id` = `fee_groups_feetype`.`feetype_id` JOIN `student_fees_master` ON `student_fees_master`.`id`=`student_fees_deposite`.`student_fees_master_id` JOIN `student_session` ON `student_session`.`id`= `student_fees_master`.`student_session_id` JOIN `classes` ON `classes`.`id`= `student_session`.`class_id` JOIN `sections` ON `sections`.`id`= `student_session`.`section_id` JOIN `students` ON `students`.`id`=`student_session`.`student_id` WHERE student_fees_deposite.JSON_EXTRACT(amount_detail,'$.*.inv_no') LIKE '%229%' ")->result();
        // echo $sub_invoice_id."<br>".$student_session_id;die;
        return $q = $this->db->get()->result();
        // echo $this->db->last_query();die;


    }

    public function studentDeposit($data)
    {
        $sql = "SELECT fee_groups.is_system,student_fees_master.amount as `student_fees_master_amount`, fee_groups.name as `fee_group_name`,feetype.code as `fee_type_code`,fee_groups_feetype.amount,fee_groups_feetype.fine_percentage,fee_groups_feetype.fine_amount,fee_groups_feetype.due_date,IFNULL(student_fees_deposite.amount_detail,0) as `amount_detail` from student_fees_master
               INNER JOIN fee_session_groups on fee_session_groups.id=student_fees_master.fee_session_group_id
              INNER JOIN fee_groups_feetype on fee_groups_feetype.fee_groups_id=fee_session_groups.fee_groups_id
              INNER JOIN fee_groups on fee_groups.id=fee_groups_feetype.fee_groups_id
              INNER JOIN feetype on fee_groups_feetype.feetype_id=feetype.id
         LEFT JOIN student_fees_deposite on student_fees_deposite.student_fees_master_id=student_fees_master.id and student_fees_deposite.fee_groups_feetype_id=fee_groups_feetype.id WHERE student_fees_master.id =" . $data['student_fees_master_id'] . " and fee_groups_feetype.id =" . $data['fee_groups_feetype_id'];
        $query = $this->db->query($sql);

        return $query->row();
    }

    public function getPreviousStudentFees($student_session_id)
    {
        $sql    = "SELECT `student_fees_master`.*,fee_groups.name FROM `student_fees_master` INNER JOIN fee_session_groups on student_fees_master.fee_session_group_id=fee_session_groups.id INNER JOIN fee_groups on fee_groups.id=fee_session_groups.fee_groups_id  WHERE `student_session_id` = " . $student_session_id . " ORDER BY `student_fees_master`.`id`";
        $query  = $this->db->query($sql);
        $result = $query->result();
        if (!empty($result)) {
            foreach ($result as $result_key => $result_value) {
                $fee_session_group_id   = $result_value->fee_session_group_id;
                $student_fees_master_id = $result_value->id;
                $result_value->fees     = $this->getDueFeeByFeeSessionGroup($fee_session_group_id, $student_fees_master_id);

                if ($result_value->is_system != 0) {
                    $result_value->fees[0]->amount = $result_value->amount;
                }
            }
        }

        return $result;
    }
    public function check_and_add_one_time_disc($discount_id,$session_id,$student_session_id,$amount)
    {
        $student_disc_id=0;
        $rec=$this->db->query("select * from student_fees_discounts where student_session_id = '$student_session_id' and fees_discount_id = '$discount_id' and session_id = '$session_id' ")->getRow();
        if(empty($rec))
        {
            $insert_array = array(
                'session_id' => $session,
                'student_session_id' => $student_session_id,
                'fees_discount_id' => $discount_id,
                'is_active'  => 'Yes',
                'amount'     =>  $amount,
            );
            $sudent_disc_id = $this->feediscount_model->allotdiscount($insert_array);
        }
        else
        {
            $sudent_disc_id = $rec->id;
        }
        return $student_disc_id;
    }
    public function fee_deposit_collections($data, $student_fees_discount_id)
    {
        if (!empty($data)) {
            $collected_fees = array();
            $receipt_id = $this->create_receipt();
            foreach ($data as $d_key => $d_value) {
                $this->db->where('student_fees_master_id', $data[$d_key]['student_fees_master_id']);
                $this->db->where('fee_groups_feetype_id', $data[$d_key]['fee_groups_feetype_id']);
                $q = $this->db->get('student_fees_deposite');
                if ($q->num_rows() > 0) {
                    $desc = $data[$d_key]['amount_detail']['description'];
                    $data[$d_key]['receipt_id']              = $receipt_id;
                    $row  = $q->row();
                    $this->db->where('id', $row->id);
                    $a                                       = json_decode($row->amount_detail, true);
                    $inv_no                                  = $receipt_id; //max(array_keys($a)) + 1;
                    $data[$d_key]['amount_detail']['inv_no'] = $inv_no;
                    $a[$inv_no]                              = $data[$d_key]['amount_detail'];
                    $data[$d_key]['amount_detail']           = json_encode($a);
                    $this->db->update('student_fees_deposite', $data[$d_key]);
                    $collected_fees[] = array('invoice_id' => $row->id, 'sub_invoice_id' => $inv_no);

                   
                } else {
                    $data[$d_key]['amount_detail']['inv_no'] = $receipt_id;
                    $data[$d_key]['receipt_id']              = $receipt_id;
                    $desc                                    = $data[$d_key]['amount_detail']['description'];
                    $data[$d_key]['amount_detail']           = json_encode(array($receipt_id => $data[$d_key]['amount_detail']));
                    $this->db->insert('student_fees_deposite', $data[$d_key]);
                    $inserted_id      = $this->db->insert_id();
                    //$collected_fees[] = array('invoice_id' => $inserted_id, 'sub_invoice_id' => 1);
                    $collected_fees[] = array('invoice_id' => $inserted_id, 'sub_invoice_id' => $receipt_id);
                    // if ($student_fees_discount_id != null) {
                    //     $this->db->where('id', $student_fees_discount_id);
                    //     $this->db->update('student_fees_discounts', array('status' => 'applied', 'description' => $desc, 'payment_id' => $receipt_id, 'session_id' => $this->current_session));
                    // }
                }
            }
            if (!empty($student_fees_discount_id)) {
                $this->db->where_in('id', $student_fees_discount_id);
                $this->db->update('student_fees_discounts', array('status' => 'applied', 'description' => $desc, 'payment_id' => $receipt_id));
                $message = UPDATE_RECORD_CONSTANT . " On  student fees discounts id ";
                $action = "Update";
                $record_id = 0;
                $this->log($message, $record_id, $action);
            }
            return json_encode($collected_fees);
        }
    }
    public function fee_deposit_collections_previous($data, $student_fees_discount_id)
    {
        if (!empty($data)) {
            $collected_fees = array();
            $receipt_id = $this->create_receipt();
            foreach ($data as $d_key => $d_value) {
                $this->db->where('student_fees_master_id', $data[$d_key]['student_fees_master_id']);
                $this->db->where('fee_groups_feetype_id', $data[$d_key]['fee_groups_feetype_id']);
                $q = $this->db->get('student_fees_deposite');
                if ($q->num_rows() > 0) {
                    $desc = $data[$d_key]['amount_detail']['description'];
                    $data[$d_key]['receipt_id']              = $receipt_id;
                    $row  = $q->row();
                    $this->db->where('id', $row->id);
                    $a                                       = json_decode($row->amount_detail, true);
                    $inv_no                                  = $receipt_id; //max(array_keys($a)) + 1;
                    $data[$d_key]['amount_detail']['inv_no'] = $inv_no;
                    $a[$inv_no]                              = $data[$d_key]['amount_detail'];
                    $data[$d_key]['amount_detail']           = json_encode($a);
                    $this->db->update('student_fees_deposite', $data[$d_key]);
                    $collected_fees[] = array('invoice_id' => $row->id, 'sub_invoice_id' => $inv_no);

                   
                } else {
                    $data[$d_key]['amount_detail']['inv_no'] = $receipt_id;
                    $data[$d_key]['receipt_id']              = $receipt_id;
                    $desc                                    = $data[$d_key]['amount_detail']['description'];
                    $data[$d_key]['amount_detail']           = json_encode(array($receipt_id => $data[$d_key]['amount_detail']));
                    $this->db->insert('student_fees_deposite', $data[$d_key]);
                    $inserted_id      = $this->db->insert_id();
                    //$collected_fees[] = array('invoice_id' => $inserted_id, 'sub_invoice_id' => 1);
                    $collected_fees[] = array('invoice_id' => $inserted_id, 'sub_invoice_id' => $receipt_id);
                    // if ($student_fees_discount_id != null) {
                    //     $this->db->where('id', $student_fees_discount_id);
                    //     $this->db->update('student_fees_discounts', array('status' => 'applied', 'description' => $desc, 'payment_id' => $receipt_id, 'session_id' => $this->current_session));
                    // }
                }
            }
            if (!empty($student_fees_discount_id)) {
                $this->db->where_in('id', $student_fees_discount_id);
                $this->db->update('student_fees_discounts', array('status' => 'applied', 'description' => $desc, 'payment_id' => $receipt_id));
                $message = UPDATE_RECORD_CONSTANT . " On  student fees discounts id ";
                $action = "Update";
                $record_id = 0;
                $this->log($message, $record_id, $action);
            }
            return json_encode($collected_fees);
        }
    }

    public function findOnlineObjectById($array, $st_date, $ed_date)
    {
        $ar    = json_decode($array->amount_detail);
        $mode  = array('Cheque', 'Cash', 'DD');
        $array = array();
        for ($i = $st_date; $i <= $ed_date; $i += 86400) {
            $find = date('Y-m-d', $i);
            foreach ($ar as $row_key => $row_value) {
                if ($row_value->date == $find) {
                    if (!in_array($row_value->payment_mode, $mode, true)) {
                        $array[] = $row_value;
                    }
                }
            }
        }
        return $array;
    }

    public function getOnlineFeeCollectionReport($start_date, $end_date)
    {
        $this->db->select('`student_fees_deposite`.*,students.firstname,students.middlename,students.lastname,student_session.class_id,classes.class,sections.section,student_session.section_id,student_session.student_id,`fee_groups`.`name`, `feetype`.`type`, `feetype`.`code`,student_fees_master.student_session_id,students.admission_no')->from('student_fees_deposite');
        $this->db->join('fee_groups_feetype', 'fee_groups_feetype.id = student_fees_deposite.fee_groups_feetype_id');
        $this->db->join('fee_groups', 'fee_groups.id = fee_groups_feetype.fee_groups_id');
        $this->db->join('feetype', 'feetype.id = fee_groups_feetype.feetype_id');
        $this->db->join('student_fees_master', 'student_fees_master.id=student_fees_deposite.student_fees_master_id');
        $this->db->join('student_session', 'student_session.id= student_fees_master.student_session_id');
        $this->db->join('classes', 'classes.id= student_session.class_id');
        $this->db->join('sections', 'sections.id= student_session.section_id');
        $this->db->join('students', 'students.id=student_session.student_id');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->order_by('student_fees_deposite.id');

        $query        = $this->db->get();
        $result_value = $query->result();

        $return_array = array();
        if (!empty($result_value)) {
            $st_date = strtotime($start_date);
            $ed_date = strtotime($end_date);
            foreach ($result_value as $key => $value) {
                $return = $this->findOnlineObjectById($value, $st_date, $ed_date);
                if (!empty($return)) {

                    foreach ($return as $r_key => $r_value) {
                        $a['id']                     = $value->id;
                        $a['student_fees_master_id'] = $value->student_fees_master_id;
                        $a['fee_groups_feetype_id']  = $value->fee_groups_feetype_id;
                        $a['admission_no']           = $value->admission_no;
                        $a['firstname']              = $value->firstname;
                        $a['middlename']             = $value->middlename;
                        $a['lastname']               = $value->lastname;
                        $a['class_id']               = $value->class_id;
                        $a['class']                  = $value->class;
                        $a['section']                = $value->section;
                        $a['section_id']             = $value->section_id;
                        $a['student_id']             = $value->student_id;
                        $a['name']                   = $value->name;
                        $a['type']                   = $value->type;
                        $a['code']                   = $value->code;
                        $a['student_session_id']     = $value->student_session_id;
                        $a['amount']                 = $r_value->amount;
                        $a['date']                   = $r_value->date;
                        $a['amount_discount']        = $r_value->amount_discount;
                        $a['amount_fine']            = $r_value->amount_fine;
                        $a['description']            = $r_value->description;
                        $a['payment_mode']           = $r_value->payment_mode;
                        $a['inv_no']                 = $r_value->inv_no;
                        $a['received_by']            = $r_value->received_by;
                        $a['received_byname']        = $this->staff_model->get_StaffNameById($r_value->received_by);
                        $return_array[]              = $a;
                    }
                }
            }
        }

        return $return_array;
    }

    public function getFeesAwaiting($start_date, $end_date)
    {
        $sql    = "SELECT student_fees_master.*,fee_session_groups.fee_groups_id,fee_session_groups.session_id,fee_groups.name,fee_groups.is_system,fee_groups_feetype.amount as `fee_amount`,fee_groups_feetype.id as fee_groups_feetype_id,student_fees_deposite.amount_detail,students.firstname,students.middlename,students.is_active FROM `student_fees_master` INNER JOIN fee_session_groups on fee_session_groups.id=student_fees_master.fee_session_group_id INNER JOIN student_session on student_session.id=student_fees_master.student_session_id INNER JOIN students on students.id=student_session.student_id inner join fee_groups on fee_groups.id=fee_session_groups.fee_groups_id INNER JOIN fee_groups_feetype on fee_groups.id=fee_groups_feetype.fee_groups_id LEFT JOIN student_fees_deposite on student_fees_deposite.student_fees_master_id=student_fees_master.id and student_fees_deposite.fee_groups_feetype_id=fee_groups_feetype.id WHERE student_session.session_id='" . $this->current_session . "' and  fee_session_groups.session_id='" . $this->current_session . "' and fee_groups_feetype.due_date BETWEEN '" . $start_date . "' and '" . $end_date . "' and students.is_active='yes' order by fee_groups_feetype.due_date asc";
        $query  = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }

    public function getCurrentSessionStudentFees()
    {
        $sql = "SELECT student_fees_master.*,fee_session_groups.fee_groups_id,fee_session_groups.session_id,fee_groups.name,fee_groups.is_system,fee_groups_feetype.amount as `fee_amount`,fee_groups_feetype.id as fee_groups_feetype_id,student_fees_deposite.id as `student_fees_deposite_id`,student_fees_deposite.amount_detail,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.father_name,students.image, students.mobileno, students.email ,students.state ,   students.city , students.pincode ,students.is_active,classes.class,sections.section FROM `student_fees_master` INNER JOIN fee_session_groups on fee_session_groups.id=student_fees_master.fee_session_group_id INNER JOIN student_session on student_session.id=student_fees_master.student_session_id INNER JOIN students on students.id=student_session.student_id inner join classes on student_session.class_id=classes.id INNER JOIN sections on sections.id=student_session.section_id inner join fee_groups on fee_groups.id=fee_session_groups.fee_groups_id INNER JOIN fee_groups_feetype on fee_groups.id=fee_groups_feetype.fee_groups_id LEFT JOIN student_fees_deposite on student_fees_deposite.student_fees_master_id=student_fees_master.id and student_fees_deposite.fee_groups_feetype_id=fee_groups_feetype.id WHERE student_session.session_id='" . $this->current_session . "' and  fee_session_groups.session_id='" . $this->current_session . "'";
        $query  = $this->db->query($sql);
        $result = $query->result();
        return $result;
    }
    public function getFeesDepositeByIdArray($id_array = array())
    {
        $id_implode = $imp = "'" . implode("','", $id_array) . "'";
        $sql = "SELECT student_fees_master.*,fee_session_groups.fee_groups_id,fee_session_groups.session_id,fee_groups.name,fee_groups.is_system,fee_groups_feetype.amount as `fee_amount`,fee_groups_feetype.id as fee_groups_feetype_id,student_fees_deposite.id as `student_fees_deposite_id`,student_fees_deposite.amount_detail,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.father_name,students.image, students.mobileno, students.email ,students.state ,   students.city , students.pincode ,students.is_active,classes.class,sections.section FROM `student_fees_master` INNER JOIN fee_session_groups on fee_session_groups.id=student_fees_master.fee_session_group_id INNER JOIN student_session on student_session.id=student_fees_master.student_session_id INNER JOIN students on students.id=student_session.student_id inner join classes on student_session.class_id=classes.id INNER JOIN sections on sections.id=student_session.section_id inner join fee_groups on fee_groups.id=fee_session_groups.fee_groups_id INNER JOIN fee_groups_feetype on fee_groups.id=fee_groups_feetype.fee_groups_id  JOIN student_fees_deposite on student_fees_deposite.student_fees_master_id=student_fees_master.id and student_fees_deposite.fee_groups_feetype_id=fee_groups_feetype.id WHERE student_session.session_id='" . $this->current_session . "' and  fee_session_groups.session_id='" . $this->current_session . "' and student_fees_deposite.id in (" . $id_implode . ")";
        $query  = $this->db->query($sql);
        $result = $query->result();
        return $result;
    }
    public function getStudentDueFeeTypesByDate($date, $class_id = null, $section_id = null)
    {
        $where_condition = array();
        if ($class_id != null) {
            $where_condition[] = " AND student_session.class_id=" . $class_id;
        }
        if ($section_id != null) {
            $where_condition[] = "student_session.section_id=" . $section_id;
        }
        $where_condition_string = implode(" AND ", $where_condition);

        $sql = "SELECT student_fees_master.*,fee_session_groups.fee_groups_id,fee_session_groups.session_id,fee_groups.name,fee_groups.is_system,fee_groups_feetype.amount as `fee_amount`,fee_groups_feetype.id as fee_groups_feetype_id,student_fees_deposite.amount_detail,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.father_name,students.image, students.mobileno, students.email ,students.state ,   students.city , students.pincode ,students.is_active,classes.class,sections.section FROM `student_fees_master` INNER JOIN fee_session_groups on fee_session_groups.id=student_fees_master.fee_session_group_id INNER JOIN student_session on student_session.id=student_fees_master.student_session_id INNER JOIN students on students.id=student_session.student_id inner join classes on student_session.class_id=classes.id INNER JOIN sections on sections.id=student_session.section_id inner join fee_groups on fee_groups.id=fee_session_groups.fee_groups_id INNER JOIN fee_groups_feetype on fee_groups.id=fee_groups_feetype.fee_groups_id LEFT JOIN student_fees_deposite on student_fees_deposite.student_fees_master_id=student_fees_master.id and student_fees_deposite.fee_groups_feetype_id=fee_groups_feetype.id WHERE student_session.session_id='" . $this->current_session . "' and  fee_session_groups.session_id='" . $this->current_session . "' and fee_groups_feetype.due_date <=" . $this->db->escape($date) . $where_condition_string;

        $query  = $this->db->query($sql);
        $result = $query->result();
        return $result;
    }

    public function getStudentDueFeeTypesByDateAll($date, $class_id = null, $section_id = null)
    {
        $where_condition = array();
        if ($class_id != null) {
            $where_condition[] = " AND student_session.class_id=" . $class_id;
        }
        if ($section_id != null) {
            $where_condition[] = "student_session.section_id=" . $section_id;
        }
        $where_condition_string = implode(" AND ", $where_condition);

        $sql = "SELECT student_fees_master.*,fee_session_groups.fee_groups_id,fee_session_groups.session_id,fee_groups.name,fee_groups.is_system,fee_groups_feetype.amount as `fee_amount`,fee_groups_feetype.id as fee_groups_feetype_id,student_fees_deposite.amount_detail,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.father_name,students.image, students.mobileno, students.email ,students.state ,   students.city , students.pincode ,students.is_active,classes.class,sections.section FROM `student_fees_master` INNER JOIN fee_session_groups on fee_session_groups.id=student_fees_master.fee_session_group_id INNER JOIN student_session on student_session.id=student_fees_master.student_session_id INNER JOIN students on students.id=student_session.student_id inner join classes on student_session.class_id=classes.id INNER JOIN sections on sections.id=student_session.section_id inner join fee_groups on fee_groups.id=fee_session_groups.fee_groups_id INNER JOIN fee_groups_feetype on fee_groups.id=fee_groups_feetype.fee_groups_id LEFT JOIN student_fees_deposite on student_fees_deposite.student_fees_master_id=student_fees_master.id and student_fees_deposite.fee_groups_feetype_id=fee_groups_feetype.id WHERE student_session.session_id='" . $this->current_session . "' and  fee_session_groups.session_id='" . $this->current_session . "' and fee_groups_feetype.due_date <=" . $this->db->escape($date) . $where_condition_string;

        $query  = $this->db->query($sql);
        $result = $query->result();
        return $result;
    }

    public function studentDepositByFeeGroupFeeTypeArray($student_session_id, $fee_type_array)
    {
        $fee_groups_feetype_ids = implode(', ', $fee_type_array);
        $sql = "SELECT fee_groups_feetype.*,student_fees_master.student_session_id,student_fees_master.amount as `previous_amount`,student_fees_master.is_system,student_fees_master.id as student_fees_master_id,feetype.code,feetype.type, IFNULL(student_fees_deposite.id,0) as `student_fees_deposite_id`,student_fees_deposite.amount_detail,fee_groups.name as `fee_group_name` FROM `fee_groups_feetype` INNER join student_fees_master on student_fees_master.fee_session_group_id=fee_groups_feetype.fee_session_group_id INNER JOIN feetype on feetype.id=fee_groups_feetype.feetype_id INNER JOIN fee_groups on fee_groups.id=fee_groups_feetype.fee_groups_id LEFT JOIN student_fees_deposite on student_fees_deposite.student_fees_master_id=student_fees_master.id and student_fees_deposite.fee_groups_feetype_id=fee_groups_feetype.id WHERE fee_groups_feetype.id in (" . $fee_groups_feetype_ids . ") and student_fees_master.student_session_id=" . $this->db->escape($student_session_id) . "  order by fee_groups_feetype.due_date asc";
        $query                  = $this->db->query($sql);
        return $query->result();
    }

    public function update_receipt($data,$rec_session_id)
    {
        if($session_id = "")
        { return false; }
        else
        {
            $this->db->trans_start(); # Starting Transaction
            $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
            //=======================Code Start===========================
            $tb_name = 'fee_receipt_no_' . $rec_session_id;
            if (!empty($data)) {
                $this->db->where('id', $data['id']);
                $this->db->update($tb_name, $data);
                $message = UPDATE_RECORD_CONSTANT . " On  Fee receipt " . $data['id'];
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
    }
    public function getCollectionReportReceipt($start_date, $end_date, $feetype_id = null, $received_by = null, $group = null,$payment_mode  = null,$session_id  = null)
    {
        $tb_name = 'fee_receipt_no_' . $this->current_session;
        $this->db->select('*,'.$tb_name.'.id as fee_receipt_id,'.$tb_name.'.status as fee_receipt_status,'.$tb_name.'.payment_mode,'.$tb_name.'.note,'.$tb_name.'.pass_status as passStatus,'.$tb_name.'.session_id as receipt_session_id');
        $this->db->join('student_session', 'student_session.id= '.$tb_name.'.student_session_id');
        $this->db->join('classes', 'classes.id= student_session.class_id');
        $this->db->join('sections', 'sections.id= student_session.section_id');
        $this->db->join('students', 'students.id=student_session.student_id');
        $this->db->join('sessions', 'sessions.id=student_session.session_id');
        $this->db->where($tb_name.'.oc_type =', 'C');
        $this->db->order_by($tb_name.".id", 'desc');
        // $this->db->where($tb_name.'.status', 'Active');
        if (!empty($received_by)) {
            $this->db->where($tb_name.'.created_by', $received_by);
        }
        if (!empty($start_date)) {
            $start_date = $start_date." 00:00:00";
            $this->db->where($tb_name.'.receipt_date >=', $start_date);
        }
        if (!empty($end_date)) {
            $end_date = $end_date." 23:59:00";
            $this->db->where($tb_name.'.receipt_date <=', $end_date);
        }
        if (!empty($payment_mode)) {
            if($payment_mode == 'bank_transfer')
            {
                $options = array('bank_transfer','cheque','upi','card','DD');
                $this->db->where_In($tb_name.'.payment_mode ', $options);
            }
            else
            {$this->db->where($tb_name.'.payment_mode =', $payment_mode);}
        }
        if (!empty($session_id)) {
            $this->db->where($tb_name.'.session_id =', $session_id);
        }
        $query        = $this->db->get($tb_name);
        if(empty($query))
        {return $result_value = array();}
        else
        {return $result_value = $query->result();}
    }
    public function getCollectionReportReceipt_student($student_session_id)
    {
        $tb_name = 'fee_receipt_no_' . $this->current_session;
        $this->db->select('*,'.$tb_name.'.id as fee_receipt_id,'.$tb_name.'.status as fee_receipt_status,'.$tb_name.'.payment_mode,'.$tb_name.'.note,'.$tb_name.'.pass_status as passStatus,'.$tb_name.'.session_id as receipt_session_id');
        $this->db->join('student_session', 'student_session.id= '.$tb_name.'.student_session_id');
        $this->db->join('classes', 'classes.id= student_session.class_id');
        $this->db->join('sections', 'sections.id= student_session.section_id');
        $this->db->join('students', 'students.id=student_session.student_id');
        $this->db->join('sessions', 'sessions.id=student_session.session_id');
        $this->db->where($tb_name.'.oc_type =', 'C');
        $this->db->where($tb_name.'.student_session_id =', $student_session_id);
        $this->db->order_by($tb_name.".id", 'desc');
        $query        = $this->db->get($tb_name);
        return $result_value = $query->result();
    }

    public function getCollectionReportReceipt_old($start_date, $end_date, $feetype_id = null, $received_by = null, $group = null,$payment_mode  = null,$session_id  = null)
    {
        $tb_name = 'fee_receipt_no_' . $this->current_session;
        $this->db->select('*,'.$tb_name.'.id as fee_receipt_id,'.$tb_name.'.status as fee_receipt_status,'.$tb_name.'.payment_mode,'.$tb_name.'.note,'.$tb_name.'.pass_status as passStatus,'.$tb_name.'.session_id as receipt_session_id');
        $this->db->join('student_session', 'student_session.id= '.$tb_name.'.student_session_id');
        $this->db->join('classes', 'classes.id= student_session.class_id');
        $this->db->join('sections', 'sections.id= student_session.section_id');
        $this->db->join('students', 'students.id=student_session.student_id');
        $this->db->join('sessions', 'sessions.id=student_session.session_id');
        $this->db->where($tb_name.'.oc_type =', 'O');
        $this->db->order_by($tb_name.".id", 'desc');
        // $this->db->where($tb_name.'.status', 'Active');
        if (!empty($received_by)) {
            $this->db->where($tb_name.'.created_by', $received_by);
        }
        if (!empty($start_date)) {
            $start_date = $start_date." 00:00:00";
            $this->db->where($tb_name.'.receipt_date >=', $start_date);
        }
        if (!empty($end_date)) {
            $end_date = $end_date." 23:59:00";
            $this->db->where($tb_name.'.receipt_date <=', $end_date);
        }
        if (!empty($payment_mode)) {
            $this->db->where($tb_name.'.payment_mode =', $payment_mode);
        }
        if (!empty($session_id)) {
            $this->db->where($tb_name.'.session_id =', $session_id);
        }
        $query        = $this->db->get($tb_name);
        return $result_value = $query->result();
    }

    public function getReconcileReceipt($start_date, $end_date, $feetype_id = null, $received_by = null, $group = null,$payment_mode  = null,$session_id  = null,$list_type = null)
    {
        $tb_name = 'fee_receipt_no_' . $this->current_session;
        $this->db->select('*,'.$tb_name.'.id as fee_receipt_id,'.$tb_name.'.status as fee_receipt_status,'.$tb_name.'.payment_mode,'.$tb_name.'.note,'.$tb_name.'.pass_status as passStatus,'.$tb_name.'.session_id as receipt_session_id,'.$tb_name.'.pass_date');
        $this->db->join('student_session', 'student_session.id= '.$tb_name.'.student_session_id');
        $this->db->join('classes', 'classes.id= student_session.class_id');
        $this->db->join('sections', 'sections.id= student_session.section_id');
        $this->db->join('students', 'students.id=student_session.student_id');
        $this->db->join('sessions', 'sessions.id=student_session.session_id');
        $this->db->order_by($tb_name.".id", 'desc');
        $this->db->where($tb_name.'.status', 'Active');
        if($list_type == 'All')
        { echo "all";}
        else
        {$this->db->where($tb_name.'.reconciled_to = ', null);}
        if (!empty($received_by)) {
            $this->db->where($tb_name.'.created_by', $received_by);
        }
        if (!empty($start_date)) {
            $start_date = $start_date." 00:00:00";
            $this->db->where($tb_name.'.receipt_date >=', $start_date);
        }
        if (!empty($end_date)) {
            $end_date = $end_date." 23:59:00";
            $this->db->where($tb_name.'.receipt_date <=', $end_date);
        }
        if (!empty($payment_mode)) {
            $this->db->where($tb_name.'.payment_mode =', $payment_mode);
        }
        if (!empty($session_id)) {
            $this->db->where($tb_name.'.session_id =', $session_id);
        }
        $query        = $this->db->get($tb_name);
        
        if (!empty($query)) {
            
            return $result_value = $query->result();
        }else {
            return array();
        }
    }
    public function getreconsileReport($start_date, $end_date, $feetype_id = null, $pass_status = null, $group = null,$payment_mode  = null,$session_id  = null)
    {
        $tb_name = 'fee_receipt_no_' . $this->current_session;
        $this->db->select('*,'.$tb_name.'.id as fee_receipt_id,'.$tb_name.'.status as fee_receipt_status,'.$tb_name.'.payment_mode,'.$tb_name.'.note,'.$tb_name.'.pass_status as passStatus');
        $this->db->join('student_session', 'student_session.id= '.$tb_name.'.student_session_id');
        $this->db->join('classes', 'classes.id= student_session.class_id');
        $this->db->join('sections', 'sections.id= student_session.section_id');
        $this->db->join('students', 'students.id=student_session.student_id');
        $this->db->join('sessions', 'sessions.id=student_session.session_id');
        //$this->db->order_by($tb_name.".id", 'desc');
        //$this->db->where($tb_name.'.status', 'Active');
        //$this->db->where($tb_name.'.reconciled_to >', 0);
        if (!empty($pass_status)) {
            $this->db->where($tb_name.'.pass_status', $pass_status);
        }
        if (!empty($start_date)) {
            $start_date = $start_date." 00:00:00";
            $this->db->where($tb_name.'.receipt_date >=', $start_date);
        }
        if (!empty($end_date)) {
            $end_date = $end_date." 23:59:00";
            $this->db->where($tb_name.'.receipt_date <=', $end_date);
        }
        if (!empty($payment_mode)) {
            $this->db->where($tb_name.'.payment_mode =', $payment_mode);
        }
        if (!empty($session_id)) {
            $this->db->where($tb_name.'.session_id =', $session_id);
        }
        $query        = $this->db->get($tb_name);
        // print_r($query->result());die();
        return $result_value = $query->result();
    }

    public function getCollectionReportEntry($start_date, $end_date, $feetype_id = null, $received_by = null, $group = null,$payment_mode  = null,$session_id  = null)
    {
        $tb_name = 'fee_receipt_no_' . $this->current_session;

        $this->db->select('*,'.$tb_name.'.id as fee_receipt_id,'.$tb_name.'.status as fee_receipt_status');
        $this->db->join('student_session', 'student_session.id= '.$tb_name.'.student_session_id');
        $this->db->join('classes', 'classes.id= student_session.class_id');
        $this->db->join('sections', 'sections.id= student_session.section_id');
        $this->db->join('students', 'students.id=student_session.student_id');
        $this->db->join('sessions', 'sessions.id=student_session.session_id');
        $this->db->where($tb_name.'.oc_type =', 'C');
        $this->db->order_by($tb_name.".id", 'desc');
        // $this->db->where($tb_name.'.status', 'Active');
        if (!empty($received_by)) {
            $this->db->where($tb_name.'.created_by', $received_by);
        }
        if (!empty($payment_mode)) {
            if($payment_mode == 'bank_transfer')
            {
                $options = array('bank_transfer','cheque','upi','card','DD');
                $this->db->where_In($tb_name.'.payment_mode ', $options);
            }
            else
            {$this->db->where($tb_name.'.payment_mode =', $payment_mode);}            
        }
        if (!empty($session_id)) {
            $this->db->where($tb_name.'.session_id =', $session_id);
        }
        $this->db->group_start();
        $this->db->group_start();
        if (!empty($start_date)) {
            $start_date = $start_date." 00:00:00";
            $this->db->where($tb_name.'.created_at >=', $start_date);
        }
        //where ((created_at >= '' and created_at <= '') or (last_updated_at >= '' and last_updated_at <= ''))
        if (!empty($end_date)) {
            $end_date = $end_date." 23:59:00";
            $this->db->where($tb_name.'.created_at <=', $end_date);
        }  
        $this->db->group_end();  
        $this->db->or_group_start();
        if (!empty($start_date)) {
            $this->db->where($tb_name.'.last_updated_at >=', $start_date);
        }
        if (!empty($end_date)) {
            $this->db->where($tb_name.'.last_updated_at <=', $end_date);
        }    
        $this->db->group_end(); 
        $this->db->or_group_start();
        if (!empty($start_date)) {
            $this->db->where($tb_name.'.cancelled_at >=', $start_date);
        }
        if (!empty($end_date)) {
            $this->db->where($tb_name.'.cancelled_at <=', $end_date);
        }    
        $this->db->group_end();         
        $this->db->group_end();           
        $query        = $this->db->get($tb_name);
        
        return $result_value = $query->result();
        // $result_value = $query->result();
        // echo "<pre>";
        // //print_r($result_value);die();
        // echo $this->db->last_query();die();
        
    }

    public function addRefund($arrayData)
    {
        $this->db->insert('student_refund',$arrayData);
       return $this->db->insert_id();
       $insert_id = $this->db->insert_id();
       $message   = INSERT_RECORD_CONSTANT . " On student_refund " . $insert_id;
       $action    = "Insert";
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
    }
    public function check_receipt_date($collection_dt)
    {
        //$tb_name = 'fee_receipt_no_' . $this->current_session;
        $tb_name = 'fee_receipt_no_' . $this->current_ay_session;
        $this->db->select('*,'.$tb_name.'.id as fee_receipt_id');
        $this->db->order_by($tb_name.".id", 'desc');
        $this->db->where($tb_name.'.status', 'Active');
        if (!empty($collection_dt)) {
            $this->db->where($tb_name.'.receipt_date >', $collection_dt);
        }        
        $query        = $this->db->get($tb_name);
        return $query->num_rows();
    } 

    public function getCollectionReportPassDate($start_date, $end_date, $feetype_id = null, $received_by = null, $group = null,$payment_mode  = null,$session_id  = null)
    {
        $tb_name = 'fee_receipt_no_' . $this->current_session;

        $this->db->select('*,'.$tb_name.'.id as fee_receipt_id,'.$tb_name.'.status as fee_receipt_status,'.$tb_name.'.pass_date,bank_master.bank_head');
        $this->db->join('student_session', 'student_session.id= '.$tb_name.'.student_session_id');
        $this->db->join('classes', 'classes.id= student_session.class_id');
        $this->db->join('sections', 'sections.id= student_session.section_id');
        $this->db->join('students', 'students.id=student_session.student_id');
        $this->db->join('sessions', 'sessions.id=student_session.session_id');
        $this->db->join('bank_master', 'bank_master.id='.$tb_name.'.reconciled_to','left');
        $this->db->order_by($tb_name.".id", 'desc');
        // $this->db->where($tb_name.'.status', 'Active');
        if (!empty($received_by)) {
            $this->db->where($tb_name.'.created_by', $received_by);
        }
        if (!empty($start_date)) {
            $start_date = $start_date." 00:00:00";
            $this->db->where($tb_name.'.pass_date >=', $start_date);
        }
        if (!empty($end_date)) {
            $end_date = $end_date." 23:59:00";
            $this->db->where($tb_name.'.pass_date <=', $end_date);
        }
        if (!empty($payment_mode)) {
            if($payment_mode == 'bank_transfer')
            {
                $options = array('bank_transfer','cheque','upi','card','DD');
                $this->db->where_In($tb_name.'.payment_mode ', $options);
            }
            else
            {$this->db->where($tb_name.'.payment_mode =', $payment_mode);}            
        }
        if (!empty($session_id)) {
            $this->db->where($tb_name.'.session_id =', $session_id);
        }
        
        $query        = $this->db->get($tb_name);
        
        return $result_value = $query->result();
        
    }

    public function getStudentFeesAll($student_session_id)
    {
        $sql    = "SELECT `student_fees_master`.*,fee_groups.name,fee_groups.dis_name,fee_groups.fees_type FROM `student_fees_master` INNER JOIN fee_session_groups on student_fees_master.fee_session_group_id=fee_session_groups.id INNER JOIN fee_groups on fee_groups.id=fee_session_groups.fee_groups_id  WHERE  `student_session_id` = " . $student_session_id . " ORDER BY `student_fees_master`.`id`";
        $query  = $this->db->query($sql);
        $result = $query->result();
        if (!empty($result)) {
            foreach ($result as $result_key => $result_value) {

                $fee_session_group_id   = $result_value->fee_session_group_id;
                $student_fees_master_id = $result_value->id;
                $result_value->fees     = $this->getDueFeeByFeeSessionGroup($fee_session_group_id, $student_fees_master_id);

                if ($result_value->is_system != 0) {
                    $result_value->fees[0]->amount = $result_value->amount;
                }
            }
   
        }
        return $result;
    }
    public function getStudentFeesAll_for_dues($student_session_id)
    {
        $sql    = "SELECT `student_fees_master`.*,fee_groups.name,fee_groups.dis_name,fee_groups.fees_type FROM `student_fees_master` INNER JOIN fee_session_groups on student_fees_master.fee_session_group_id=fee_session_groups.id INNER JOIN fee_groups on fee_groups.id=fee_session_groups.fee_groups_id  WHERE  `student_session_id` = " . $student_session_id . " ORDER BY `student_fees_master`.`id`";
        $query  = $this->db->query($sql);
        $result = $query->result();
        if (!empty($result)) {
            foreach ($result as $result_key => $result_value) {

                $fee_session_group_id   = $result_value->fee_session_group_id;
                $student_fees_master_id = $result_value->id;
                $result_value->fees     = $this->getDueFeeByFeeSessionGroup_for_dues($fee_session_group_id, $student_fees_master_id);

                if ($result_value->is_system != 0) {
                    $result_value->fees[0]->amount = $result_value->amount;
                }
            }
        }

        return $result;
    } 
    
    public function get_by_student_session_id($student_session_id)
    {
        $student_total_fees = array();
        $student_total_fees[] = $this->getStudentFees_main($student_session_id);
        // echo "<pre>";
        // print_r($student_total_fees);die();
        $today_date = date('Y-m-d');
        if (!empty($student_total_fees)) {
            $inst_balamount = array(0, 0, 0);
            $rw = 0;
            foreach ($student_total_fees as $student_total_fees_key => $student_total_fees_value) {
                $inst_balamount[$rw] = 0;
                foreach ($student_total_fees_value as $ff) {
                    $fees = $ff->fees;
                    if (!empty($fees)) {
                        $late_adm_disc = 0;
                        foreach ($fees as $key => $each_fee_value) {
                            $amount_detail = json_decode($each_fee_value->amount_detail);
                            if ($each_fee_value->due_date < $today_date) {
                                if (is_object($amount_detail)) {
                                    $paid_amt_loop = 0;
                                    $paid_disc=0;
                                    $late_adm_disc = 0;
                                    foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                                        $paid_amt_loop += $amount_detail_value->amount;
                                        if(isset($amount_detail_value->discount_id))
                                        {
                                            if($amount_detail_value->discount_id==7)
                                            {
                                                $late_adm_disc = $amount_detail_value->amount_discount;
                                            }
                                            else
                                            {
                                                $paid_disc += $amount_detail_value->amount_discount;

                                            }
                                        }else{
                                            $paid_disc += $amount_detail_value->amount_discount;
                                        }
                                    }
                                    $inst_balamount[$rw] += ($each_fee_value->amount - ($paid_amt_loop + $paid_disc + $late_adm_disc));
                                }else{
                                    $inst_balamount[$rw] += ($each_fee_value->amount);
                                }
                            
                            }
                        }
                    }
                    // if ($ff->is_active == "no") {
                    //     $inst_balamount[$rw] = 0;
                    // }
                    ++$rw;
                }
            }
            
            return $inst_balamount;
        } else {
            return array();
        }

    }
    public function get_student_balance_up_recid($student_session_id,$recid)
    {
        $student_total_fees = array();
        $student_total_fees[] = $this->getStudentFees_main($student_session_id);
        
        $query = $this->db->query("select session_id from student_session where id = '" . $student_session_id . "'");
        $result = $query->result();
        if(!empty($result))
        {
            $st_session_id = $result[0]->session_id;
        }
        
        

        $today_date = date('Y-m-d');
        if (!empty($student_total_fees)) {
            $inst_balamount = array(0, 0, 0,0);
            $rw = 0;
            foreach ($student_total_fees as $student_total_fees_key => $student_total_fees_value) {
                $inst_balamount[$rw] = 0;
                foreach ($student_total_fees_value as $ff) {
                    $fees = $ff->fees;
                    if (!empty($fees)) {
                        $late_adm_disc = 0;
                        foreach ($fees as $key => $each_fee_value) {
                            $amount_detail = json_decode($each_fee_value->amount_detail);
                            
                                if (is_object($amount_detail)) {
                                    $paid_amt_loop = 0;
                                    $paid_disc=0;
                                    $late_adm_disc = 0;
                                    foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                                        if($amount_detail_value->inv_no <= $recid || $amount_detail_value->session_id < $st_session_id ) {
                                        $paid_amt_loop += $amount_detail_value->amount;
                                        if(isset($amount_detail_value->discount_id))
                                        {
                                            if($amount_detail_value->discount_id==7)
                                            {
                                                $late_adm_disc = $amount_detail_value->amount_discount;
                                            }
                                            else
                                            {
                                                $paid_disc += $amount_detail_value->amount_discount;

                                            }
                                        }else{
                                            $paid_disc += $amount_detail_value->amount_discount;
                                        }
                                        } 
                                    }
                                    
                                    $inst_balamount[$rw] += ($each_fee_value->amount - ($paid_amt_loop + $paid_disc + $late_adm_disc));
                                }else{
                                    $inst_balamount[$rw] += ($each_fee_value->amount);
                                }
                            
                            
                        }
                    }
                    // if ($ff->is_active == "no") {
                    //     $inst_balamount[$rw] = 0;
                    // }
                    ++$rw;
                }
            }
            return $inst_balamount[0] + $inst_balamount[1] + $inst_balamount[2];
        } else {
            return array();
        }

    }
    public function get_student_balance_up_recid1($student_session_id,$recid,$recdate)
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);        
        
        $student_total_fees = array();
        $student_total_fees[] = $this->getStudentFees_main($student_session_id);
        
        $query = $this->db->query("select session_id from student_session where id = '" . $student_session_id . "'");
        $result = $query->result();
        if(!empty($result))
        {
            $st_session_id = $result[0]->session_id;
        }
        
        

        $today_date = date('Y-m-d');
        if (!empty($student_total_fees)) {
            $inst_balamount = array(0, 0, 0,0);
            $rw = 0;
            foreach ($student_total_fees as $student_total_fees_key => $student_total_fees_value) {
                $inst_balamount[$rw] = 0;
                foreach ($student_total_fees_value as $ff) {
                    $fees = $ff->fees;
                    if (!empty($fees)) {
                        $late_adm_disc = 0;
                        foreach ($fees as $key => $each_fee_value) {
                            $amount_detail = json_decode($each_fee_value->amount_detail);
                                if (is_object($amount_detail)) {
                                    $paid_amt_loop = 0;
                                    $paid_disc=0;
                                    $late_adm_disc = 0;
                                    foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                                        if($amount_detail_value->date <= $recdate ) {
                                        $paid_amt_loop += $amount_detail_value->amount;
                                        if(isset($amount_detail_value->discount_id))
                                        {
                                            if($amount_detail_value->discount_id==7)
                                            {
                                                $late_adm_disc = $amount_detail_value->amount_discount;
                                            }
                                            else
                                            {
                                                $paid_disc += $amount_detail_value->amount_discount;

                                            }
                                        }else{
                                            $paid_disc += $amount_detail_value->amount_discount;
                                        }
                                        } 
                                    }
                                    $inst_balamount[$rw] += ($each_fee_value->amount - ($paid_amt_loop + $paid_disc + $late_adm_disc));
                                }else{
                                    $inst_balamount[$rw] += ($each_fee_value->amount);
                                }
                        }
                    }
                    ++$rw;
                }
            }
            return $inst_balamount[0] + $inst_balamount[1] + $inst_balamount[2];
        } else {
            return array();
        }

    }
    public function get_student_previous_up_recid1($student_session_id,$recid,$recdate)
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);        
        
        $student_total_fees = array();
        $student_total_fees[] = $this->getStudentFees_main($student_session_id);
        
        $query = $this->db->query("select session_id from student_session where id = '" . $student_session_id . "'");
        $result = $query->result();
        if(!empty($result))
        {
            $st_session_id = $result[0]->session_id;
        }
        
        

        $today_date = date('Y-m-d');
        if (!empty($student_total_fees)) {
            $inst_balamount = array(0, 0, 0,0);
            $rw = 0;
            foreach ($student_total_fees as $student_total_fees_key => $student_total_fees_value) {
                $inst_balamount[$rw] = 0;
                foreach ($student_total_fees_value as $ff) {
                    $fees = $ff->fees;                
                    if (!empty($fees)) {
                        $late_adm_disc = 0;

                        foreach ($fees as $key => $each_fee_value) {
                            
                            $amount_detail = json_decode($each_fee_value->amount_detail);
                                if (is_object($amount_detail)) {
                                    $paid_amt_loop = 0;
                                    $paid_disc=0;
                                    $late_adm_disc = 0;
                                    foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                                        if($amount_detail_value->date < $recdate ) {
                                        $paid_amt_loop += $amount_detail_value->amount;
                                        if(isset($amount_detail_value->discount_id))
                                        {
                                            if($amount_detail_value->discount_id==7)
                                            {
                                                $late_adm_disc = $amount_detail_value->amount_discount;
                                            }
                                            else
                                            {
                                                $paid_disc += $amount_detail_value->amount_discount;

                                            }
                                        }else{
                                            $paid_disc += $amount_detail_value->amount_discount;
                                        }
                                        } 
                                    }
                                    $inst_balamount[$rw] +=  ($paid_amt_loop + $paid_disc + $late_adm_disc);
                                }else{
                                    
                                }
                        }
                    }
                    ++$rw;
                }
            }
            return $inst_balamount[0] + $inst_balamount[1] + $inst_balamount[2];
        } else {
            return array();
        }

    }

    public function get_student_balance_ason_date($student_session_id,$to_date)
    {
        $student_total_fees = array();
        
        $student_total_fees[] = $this->getStudentFees_main($student_session_id);
        if (!empty($student_total_fees)) {
            $inst_balamount = array(0, 0, 0);
            $rw = 0;
            foreach ($student_total_fees as $student_total_fees_key => $student_total_fees_value) {
                $inst_balamount[$rw] = 0;
                foreach ($student_total_fees_value as $ff) {
                    $fees = $ff->fees;
                    if (!empty($fees)) {
                        $late_adm_disc = 0;
                        foreach ($fees as $key => $each_fee_value) {
                            $amount_detail = json_decode($each_fee_value->amount_detail);
                            // echo "<pre>";
                            // print_r($amount_detail);
                                if (is_object($amount_detail)) {
                                    $paid_amt_loop = 0;
                                    $paid_disc=0;
                                    $late_adm_disc = 0;
                                    
                                        

                                    foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                                        if($amount_detail_value->date <= $to_date )
                                        {                                        
                                        $paid_amt_loop += $amount_detail_value->amount;
                                        if(isset($amount_detail_value->discount_id))
                                        {
                                            if($amount_detail_value->discount_id==7)
                                            {
                                                $late_adm_disc = $amount_detail_value->amount_discount;
                                            }
                                            else
                                            {
                                                $paid_disc += $amount_detail_value->amount_discount;

                                            }
                                        }else{
                                            $paid_disc += $amount_detail_value->amount_discount;
                                        }
                                        
                                    }
                                    }
                                    $inst_balamount[$rw] += ($each_fee_value->amount - ($paid_amt_loop + $paid_disc + $late_adm_disc));
                                }else{
                                    $inst_balamount[$rw] += ($each_fee_value->amount);
                                }
                            
                            
                        }
                    }
                    // if ($ff->is_active == "no") {
                    //     $inst_balamount[$rw] = 0;
                    // }
                    ++$rw;
                }
            }
            
            return $inst_balamount[0] + $inst_balamount[1] + $inst_balamount[2];
        } else {
            return array();
        }

    }

}
