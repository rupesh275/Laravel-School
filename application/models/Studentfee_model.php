<?php

use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Month;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Studentfee_model extends MY_Model
{
    public $current_session;
    public $current_date;
    public $current_ay_session;

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
        $this->current_date = $this->setting_model->getDateYmd();
        $settings              = $this->setting_model->getSetting();
        $this->current_ay_session = $settings->session_id;          
    }

    public function getStudentFeesArray($student_session_id, $ids = null)
    {
        $query = "SELECT feemasters.id as feemastersid, feemasters.amount as amount,IFNULL(student_fees.id, 'xxx') as invoiceno,IFNULL(student_fees.payment_mode, 'xxx') as payment_mode,IFNULL(student_fees.amount_discount, 'xxx') as discount,IFNULL(student_fees.amount_fine, 'xxx') as fine, IFNULL(student_fees.date, 'xxx') as date,feetype.type ,feecategory.category FROM feemasters LEFT JOIN (select student_fees.id,student_fees.payment_mode,student_fees.feemaster_id,student_fees.amount_fine,student_fees.amount_discount,student_fees.date,student_fees.student_session_id from student_fees , student_session where student_fees.student_session_id=student_session.id and student_session.id=" . $this->db->escape($student_session_id) . ") as student_fees ON student_fees.feemaster_id=feemasters.id LEFT JOIN feetype ON feemasters.feetype_id = feetype.id LEFT JOIN feecategory ON feetype.feecategory_id = feecategory.id where feemasters.id IN (" . $ids . ")";

        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function getCollectBy()
    {
        return $this->db->select('id,name,surname')->from('staff')->get()->result_array();
    }

    public function getTotalCollectionBydate($date)
    {
        $sql = "SELECT sum(amount) as `amount`, SUM(amount_discount) as `amount_discount` ,SUM(amount_fine) as `amount_fine` FROM `student_fees` where date=" . $this->db->escape($date);
        $query = $this->db->query($sql);
        return $query->row();
    }

    public function getStudentFees($id = null)
    {
        $this->db->select('feecategory.category,student_fees.id as `invoiceno`,student_fees.date,student_fees.id,student_fees.amount,student_fees.amount_discount,student_fees.amount_fine,student_fees.created_at,feetype.type')->from('student_fees');
        $this->db->join('student_session', 'student_session.id = student_fees.student_session_id');
        $this->db->join('feemasters', 'feemasters.id = student_fees.feemaster_id');
        $this->db->join('feetype', 'feetype.id = feemasters.feetype_id');
        $this->db->join('feecategory', 'feetype.feecategory_id = feecategory.id');
        $this->db->where('student_session.student_id', $id);
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->order_by('student_fees.id');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getFeeByInvoice($id = null)
    {
        $this->db->select('feecategory.category,student_fees.date,student_fees.payment_mode,student_fees.id as `student_fee_id`,student_fees.amount,student_fees.amount_discount,student_fees.amount_fine,student_fees.created_at,classes.class,sections.section,feetype.type,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,students.dob ,students.current_address,    students.permanent_address,students.category_id,    students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name, students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.rte')->from('student_fees');
        $this->db->join('student_session', 'student_session.id = student_fees.student_session_id');
        $this->db->join('feemasters', 'feemasters.id = student_fees.feemaster_id');
        $this->db->join('feetype', 'feetype.id = feemasters.feetype_id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('feecategory', 'feetype.feecategory_id = feecategory.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('students', 'students.id = student_session.student_id');
        $this->db->where('student_fees.id', $id);
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->order_by('student_fees.id');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getReceipt($id,$sessionid=0)
    {
        if($sessionid==0)
        {$tb_name = 'fee_receipt_no_' . $this->current_session;}
        else
        {$tb_name = 'fee_receipt_no_' . $sessionid;}
        $receipt = $this->db->query("select * from ".$tb_name." where id = '$id'")->row();
        return $receipt;
    }
    public function getReceipt_ay($id,$sessionid=0)
    {
        if($sessionid==0)
        {$tb_name = 'fee_receipt_no_' . $this->current_ay_session;}
        else
        {$tb_name = 'fee_receipt_no_' . $sessionid;}
        $receipt = $this->db->query("select * from ".$tb_name." where id = '$id'")->row();
        return $receipt;
    }

    public function getTodayStudentFees()
    {
        $this->db->select('student_fees.date,student_fees.id,student_fees.amount,student_fees.amount_discount,student_fees.amount_fine,student_fees.created_at,classes.class,sections.section,students.firstname,students.middlename,students.lastname,students.admission_no,student_session.roll_no,students.dob,students.guardian_name,feetype.type')->from('student_fees');
        $this->db->join('student_session', 'student_session.id = student_fees.student_session_id');
        $this->db->join('feemasters', 'feemasters.id = student_fees.feemaster_id');
        $this->db->join('feetype', 'feetype.id = feemasters.feetype_id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('students', 'students.id = student_session.student_id');
        $this->db->where('student_fees.date', $this->current_date);
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->order_by('student_fees.id');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getPaidFees($student_session_id)
    {
        $result=$this->db->query("select * from student_fees_deposite where student_fees_master_id in (select id from student_fees_master where student_session_id = '$student_session_id' )")->result_array();
        
        $paid_amt=0;
        if(!empty($result))
        {
            foreach($result as $res)
            {
                $paid_fees = json_decode($res['amount_detail']);
                if(!empty($paid_fees))
                {
                    foreach($paid_fees as $fee)
                    {
                        $receipt_id = $fee->inv_no;
                        $fee_detail = $this->db->query("select * from student_fees where id = '$receipt_id'")->row();
                        $paid_amt += $fee->amount;
                    }
                }
            }
        }
        return $paid_amt;
    }
    public function getPaidFees_Main($student_session_id)
    {
        $result=$this->db->query("select * from student_fees_deposite where student_fees_master_id in (select id from student_fees_master where student_session_id = '$student_session_id' and fee_session_group_id in (SELECT id FROM `fee_session_groups` where fee_groups_id in (SELECT id FROM `fee_groups` where fees_type = 'm')) )")->result_array();
        
        $paid_amt=0;
        if(!empty($result))
        {
            foreach($result as $res)
            {
                $paid_fees = json_decode($res['amount_detail']);
                if(!empty($paid_fees))
                {
                    foreach($paid_fees as $fee)
                    {
                        $receipt_id = $fee->inv_no;
                        $fee_detail = $this->db->query("select * from student_fees where id = '$receipt_id'")->row();
                        $paid_amt += $fee->amount;
                    }
                }
            }
        }
        return $paid_amt;
    }    
    public function get_student_receipts($student_session_id)
    {
        //ini_set ('display_errors', 1); ini_set ('display_startup_errors', 1); error_reporting (E_ALL);
        $tb_name = "fee_receipt_no_".$this->current_session;
        $receipts_res = $this->db->query("select sum(gross_amount) as gross,sum(discount) as discount_amt, sum(net_amt) as net_amt from " . $tb_name . " where student_session_id = '" . $student_session_id . "' and status = 'Active'");
        // echo $this->db->last_query();
         //echo "<pre>";
         
        if(!empty($receipts_res))
        {
            $receipts = $receipts_res->row_array();
            
            if(!empty($receipts))
            { return $receipts;}
            else
            { return array();}
        }
        else
        { return array();}
    }
    public function get_previous_student_fees($student_session_id)
    {
        $student_id = "";
        $sts = $this->db->query("select student_id from student_session where id = '$student_session_id' ")->row_array();
        if(!empty($sts))
        {
            $student_id = $sts['student_id'];
            $session_id = $this->setting_model->getCurrentSession() - 1;
            $sts = $this->db->query("select id from student_session where student_id = '$student_id' and session_id = '$session_id' ")->row_array();
            if(!empty($sts))
            {
                return $this->get_student_fees($sts['id']);
            }
            else
            { return array();}
        }
        else
        {return array();}
    }
    public function get_student_fees($student_session_id)
	{
        $pay_amount = 0;
        $paid_amt = 0;
        $student_due_fee = $this->studentfeemaster_model->getStudentFees($student_session_id);
        foreach ($student_due_fee as $key => $fees) {
            if($fees->fees_type == "m")
            {
                foreach($fees->fees as $key1 => $fee_details)
                {
                    $pay_amount += $fee_details->amount;
                    $paid_fees = json_decode($fee_details->amount_detail);
                    if(!empty($paid_fees))
                    {
                        foreach($paid_fees as $fee)
                        {
                            $paid_amt += ($fee->amount + $fee->amount_discount);

                        }
                    }                    
                }
            }
        }
        $result = array(
            "pay_amount" => $pay_amount,
            "paid_amount" => $paid_amt,
        );
        return $result;
    }

    public function remove($id, $sub_invoice)
    {
        $tb_name = 'fee_receipt_no_' . $this->current_session;
        $receipt = $this->db->query("select * from ".$tb_name." where id = '$sub_invoice'")->row();
        $student_session_id = $receipt->student_session_id;
        if($student_session_id!='') {
            $this->db->trans_start(); # Starting Transaction
            $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
            //=======================Code Start===========================
            $q = $this->db->query("select * FROM `student_fees_deposite` WHERE JSON_EXTRACT(amount_detail,'$.*.inv_no') LIKE '%" . $sub_invoice . "%'")->result();
            if (count($q) > 0) {
                $resultarr = $q;
                foreach ($resultarr as  $result) {
                    $a = json_decode($result->amount_detail, true);
                        if(isset($a[$sub_invoice]['inv_no']))
                        {
                                if ($a[$sub_invoice]['inv_no'] == $sub_invoice && $a[$sub_invoice]['student_session_id']==$student_session_id ) {
                                unset($a[$sub_invoice]);
                                if (!empty($a)) {
                                    $data['amount_detail'] = json_encode($a);
                                    $this->db->where('id', $result->id);
                                    $this->db->update('student_fees_deposite', $data);
                                    $message = UPDATE_RECORD_CONSTANT . " On student fees deposite id " . $id;
                                    $action = "Update";
                                    $record_id = $id;
                                    $this->log($message, $record_id, $action);
                                } else {
                                    $this->db->where('id', $result->id);
                                    $this->db->delete('student_fees_deposite');
                                    $message = DELETE_RECORD_CONSTANT . " On student fees deposite id " . $id;
                                    $action = "Delete record";
                                    $record_id = $id;
                                    $this->log($message, $record_id, $action);
                                }
                            }
                        }
                }
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
        else
        {return false;}
    }
    public function remove_session($id, $sub_invoice,$session_id,$student_session_id)
    {
        // $tb_name = 'fee_receipt_no_' . $session_id;
        // $receipt = $this->db->query("select * from ".$tb_name." where id = '$sub_invoice'")->row();
        // echo $this->db->last_query();
        // $student_session_id = $receipt->student_session_id;
        // echo $student_session_id;
        if($student_session_id!='') {
            $this->db->trans_start(); # Starting Transaction
            $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
            //=======================Code Start===========================
            $q = $this->db->query("select * FROM `student_fees_deposite` WHERE JSON_EXTRACT(amount_detail,'$.*.inv_no') LIKE '%" . $sub_invoice . "%'")->result();
            if (count($q) > 0) {
                $resultarr = $q;
                foreach ($resultarr as  $result) {
                    $a = json_decode($result->amount_detail, true);
                        if(isset($a[$sub_invoice]['inv_no']))
                        {
                                if ($a[$sub_invoice]['inv_no'] == $sub_invoice && $a[$sub_invoice]['student_session_id']==$student_session_id ) {
                                    
                                unset($a[$sub_invoice]);
                                if (!empty($a)) {
                                    $data['amount_detail'] = json_encode($a);
                                    $this->db->where('id', $result->id);
                                    $this->db->update('student_fees_deposite', $data);
                                    $message = UPDATE_RECORD_CONSTANT . " On student fees deposite id " . $id;
                                    $action = "Update";
                                    $record_id = $id;
                                    $this->log($message, $record_id, $action);
                                } else {
                                    $this->db->where('id', $result->id);
                                    $this->db->delete('student_fees_deposite');
                                    $message = DELETE_RECORD_CONSTANT . " On student fees deposite id " . $id;
                                    $action = "Delete record";
                                    $record_id = $id;
                                    $this->log($message, $record_id, $action);
                                }
                            }
                        }
                }
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
        else
        {return false;}
    }

    public function remove_full($id, $sub_invoice)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================

        $this->db->where('receipt_id', $sub_invoice);
        $this->db->delete('student_fees_deposite');
        $message = DELETE_RECORD_CONSTANT . " On student fees deposite id " . $id;
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
    public function update_student_fees_paid($sub_invoice,$payment_mode,$note,$session_id,$arrayUpdate)
    {
        $tb_name = 'fee_receipt_no_' . $session_id;
        $receipt = $this->db->query("select * from ".$tb_name." where id = '$sub_invoice'")->row();
        if(empty($receipt))
        {
            return false;
        }
        else
        {
            if($receipt->receipt_type == 'fees') {
                $student_session_id = $receipt->student_session_id;
                if($student_session_id!='') {
                    $this->db->trans_start(); # Starting Transaction
                    $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
                    //=======================Code Start===========================
                    $q = $this->db->query("select * FROM `student_fees_deposite` WHERE JSON_EXTRACT(amount_detail,'$.*.inv_no') LIKE '%" . $sub_invoice . "%'")->result();
                    if (count($q) > 0) {
                        $resultarr = $q;
                        foreach ($resultarr as  $result) {
                            $a = json_decode($result->amount_detail, true);
                                if(isset($a[$sub_invoice]['inv_no']))
                                {
                                        if ($a[$sub_invoice]['inv_no'] == $sub_invoice && $a[$sub_invoice]['student_session_id']==$student_session_id ) {
                                        if (!empty($a)) {
                                            $a[$sub_invoice]['payment_mode'] = $payment_mode;
                                            $a[$sub_invoice]['description'] = $note;
                                            $data['amount_detail'] = json_encode($a);
                                            $this->db->where('id', $result->id);
                                            $this->db->update('student_fees_deposite', $data);
                                            $message = UPDATE_RECORD_CONSTANT . " On student fees deposite id " . $sub_invoice;
                                            $action = "Update";
                                            $record_id = $sub_invoice;
                                            $this->log($message, $record_id, $action);
                                        }
                                    }
                                }
                        }
                    }
                    
                }
                else
                {return false;}
            }
            $this->db->where('id', $sub_invoice);
            $this->db->update($tb_name, $arrayUpdate);            
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
    }
    public function update_receipt_status($data,$session_id)
    {
        if($session_id=="")
        {
            return false;
        }
        else 
        {
            $tb_name = 'fee_receipt_no_' . $session_id;
            $this->db->trans_start(); # Starting Transaction
            $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
            //=======================Code Start===========================
            if (isset($data['id'])) {
                $this->db->where('id', $data['id']);
                $this->db->update($tb_name, $data);
                $message = UPDATE_RECORD_CONSTANT . " On  fee_receipt id " . $data['id'];
                $action = "Update";
                $record_id = $id = $data['id'];
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
    }
    public function add($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('student_fees', $data);
            $message = UPDATE_RECORD_CONSTANT . " On  student fees id " . $data['id'];
            $action = "Update";
            $record_id = $id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('student_fees', $data);
            $id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On student fees id " . $id;
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

    public function getDueStudentFees($feegroup_id = null, $fee_groups_feetype_id = null, $class_id = NULL, $section_id = NULL)
    {
        $where_condition = array();
        if ($class_id != NULL) {
            $where_condition[] = " AND student_session.class_id=" . $class_id;
        }
        if ($section_id != NULL) {
            $where_condition[] = "student_session.section_id=" . $section_id;
        }

        $where_condition_string = implode(" AND ", $where_condition);

        $query = "SELECT IFNULL(student_fees_deposite.id, 0) as student_fees_deposite_id, IFNULL(student_fees_deposite.fee_groups_feetype_id, 0) as fee_groups_feetype_id, IFNULL(student_fees_deposite.amount_detail, 0) as amount_detail, student_fees_master.id as `fee_master_id`,fee_groups_feetype.feetype_id ,fee_groups_feetype.amount,fee_groups_feetype.due_date, `classes`.`id` AS `class_id`, `student_session`.`id` as `student_session_id`, `students`.`id`, `classes`.`class`, `sections`.`id` AS `section_id`, `sections`.`section`, `students`.`id`, `students`.`admission_no`, `student_session`.`roll_no`, `students`.`admission_date`, `students`.`firstname`,`students`.`middlename`, `students`.`lastname`, `students`.`image`, `students`.`mobileno`, `students`.`email`, `students`.`state`, `students`.`city`, `students`.`pincode`, `students`.`religion`, `students`.`dob`, `students`.`current_address`, `students`.`permanent_address`, IFNULL(students.category_id, 0) as `category_id`, IFNULL(categories.category, '') as `category`, `students`.`adhar_no`, `students`.`samagra_id`, `students`.`bank_account_no`, `students`.`bank_name`, `students`.`ifsc_code`, `students`.`guardian_name`, `students`.`guardian_relation`, `students`.`guardian_phone`, `students`.`guardian_address`, `students`.`is_active`, `students`.`created_at`, `students`.`updated_at`, `students`.`father_name`, `students`.`rte`, `students`.`gender` FROM `students` JOIN `student_session` ON `student_session`.`student_id` = `students`.`id` JOIN `classes` ON `student_session`.`class_id` = `classes`.`id` JOIN `sections` ON `sections`.`id` = `student_session`.`section_id` LEFT JOIN `categories` ON `students`.`category_id` = `categories`.`id` INNER JOIN student_fees_master on student_fees_master.student_session_id=student_session.id and student_fees_master.fee_session_group_id=" . $this->db->escape($feegroup_id) . " LEFT JOIN student_fees_deposite on student_fees_deposite.student_fees_master_id=student_fees_master.id and student_fees_deposite.fee_groups_feetype_id=" . $this->db->escape($fee_groups_feetype_id) . "  INNER JOIN fee_groups_feetype on fee_groups_feetype.id = " . $this->db->escape($fee_groups_feetype_id) . " WHERE `student_session`.`session_id` = " . $this->current_session . " AND 
            `students`.`is_active` = 'yes' " . $where_condition_string . " ORDER BY `students`.`id`";
        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function getDueFeeBystudent($class_id = null, $section_id = null, $student_id = null)
    {
        $query = "SELECT feemasters.id as feemastersid, feemasters.amount as amount,IFNULL(student_fees.id, 'xxx') as invoiceno,IFNULL(student_fees.amount_discount, 'xxx') as discount,IFNULL(student_fees.amount_fine, 'xxx') as fine,IFNULL(student_fees.payment_mode, 'xxx') as payment_mode,IFNULL(student_fees.date, 'xxx') as date,feetype.type ,feecategory.category,student_fees.description FROM feemasters LEFT JOIN (select student_fees.id,student_fees.feemaster_id,student_fees.payment_mode,student_fees.amount_fine,student_fees.amount_discount,student_fees.date,student_fees.student_session_id,student_fees.description  from student_fees , student_session where student_fees.student_session_id=student_session.id and student_session.student_id=" . $this->db->escape($student_id) . " and student_session.class_id=" . $this->db->escape($class_id) . " and student_session.section_id=" . $this->db->escape($section_id) . ") as student_fees ON student_fees.feemaster_id=feemasters.id JOIN feetype ON feemasters.feetype_id = feetype.id JOIN feecategory ON feetype.feecategory_id = feecategory.id  where  feemasters.class_id=" . $this->db->escape($class_id) . " and feemasters.session_id=" . $this->db->escape($this->current_session);
        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function getDueFeeBystudentSection($class_id = null, $section_id = null, $student_session_id = null)
    {
        $query = "SELECT feemasters.id as feemastersid, feemasters.amount as amount,IFNULL(student_fees.id, 'xxx') as invoiceno,IFNULL(student_fees.amount_discount, 'xxx') as discount,IFNULL(student_fees.amount_fine, 'xxx') as fine, IFNULL(student_fees.date, 'xxx') as date,feetype.type ,feecategory.category FROM feemasters LEFT JOIN (select student_fees.id,student_fees.feemaster_id,student_fees.amount_fine,student_fees.amount_discount,student_fees.date,student_fees.student_session_id from student_fees , student_session where student_fees.student_session_id=student_session.id and student_session.id=" . $this->db->escape($student_session_id) . " ) as student_fees ON student_fees.feemaster_id=feemasters.id LEFT JOIN feetype ON feemasters.feetype_id = feetype.id LEFT JOIN feecategory ON feetype.feecategory_id = feecategory.id  where  feemasters.class_id=" . $this->db->escape($class_id) . " and feemasters.session_id=" . $this->db->escape($this->current_session);
        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function getFeesByClass($class_id = null, $section_id = null, $student_id = null)
    {
        $query = "SELECT feemasters.id as feemastersid, feemasters.amount as amount,IFNULL(student_fees.id, 'xxx') as invoiceno,IFNULL(student_fees.amount_discount, 'xxx') as discount,IFNULL(student_fees.amount_fine, 'xxx') as fine, IFNULL(student_fees.date, 'xxx') as date,feetype.type ,feecategory.category FROM feemasters LEFT JOIN (select student_fees.id,student_fees.feemaster_id,student_fees.amount_fine,student_fees.amount_discount,student_fees.date,student_fees.student_session_id from student_fees , student_session where student_fees.student_session_id=student_session.id and student_session.student_id=" . $this->db->escape($student_id) . " and student_session.class_id=" . $this->db->escape($class_id) . " and student_session.section_id=" . $this->db->escape($section_id) . ") as student_fees ON student_fees.feemaster_id=feemasters.id LEFT JOIN feetype ON feemasters.feetype_id = feetype.id LEFT JOIN feecategory ON feetype.feecategory_id = feecategory.id  where  feemasters.class_id=" . $this->db->escape($class_id) . " and feemasters.session_id=" . $this->db->escape($this->current_session);
        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function getFeeBetweenDate($start_date, $end_date)
    {

        $this->db->select('student_fees.date,student_fees.id,student_fees.amount,student_fees.amount_discount,student_fees.amount_fine,student_fees.created_at,students.rte,classes.class,sections.section,students.firstname,students.middlename,students.lastname,students.admission_no,student_session.roll_no,students.dob,students.guardian_name,feetype.type')->from('student_fees');
        $this->db->join('student_session', 'student_session.id = student_fees.student_session_id');
        $this->db->join('feemasters', 'feemasters.id = student_fees.feemaster_id');
        $this->db->join('feetype', 'feetype.id = feemasters.feetype_id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('students', 'students.id = student_session.student_id');
        $this->db->where('student_fees.date >=', $start_date);
        $this->db->where('student_fees.date <=', $end_date);
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->order_by('student_fees.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getStudentTotalFee($class_id, $student_session_id)
    {
        $query = "SELECT a.totalfee,b.fee_deposit,b.payment_mode  FROM ( SELECT COALESCE(sum(amount),0) as totalfee FROM `feemasters` WHERE session_id =$this->current_session and class_id=" . $this->db->escape($class_id) . ") as a, (select COALESCE(sum(amount),0) as fee_deposit,payment_mode from student_fees WHERE student_session_id =" . $this->db->escape($student_session_id) . ") as b";
        $query = $this->db->query($query);
        return $query->row();
    }

    public function remove_discount($student_session_id, $student_fees_master_id)
    {
        $this->db->where('student_session_id', $student_session_id);
        $this->db->where('id', $student_fees_master_id);
        $this->db->delete('student_fees_master');
    }

    public function getpenaltylist($id = null)
    {
        $this->db->select('*')->from('student_penalty');
        $this->db->join('students', 'student_penalty.student_id = students.id');
        $this->db->join('student_session', 'student_session.id = student_penalty.student_session_id');

        if ($id != null) {
            $this->db->where('student_session.id', $id);
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function add_penalty($insert_array)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('student_session_id', $insert_array['student_session_id']);
        $q = $this->db->get('student_penalty')->num_rows();
        if ($q > 0) {
            $this->db->where('student_session_id', $insert_array['student_session_id']);
            $this->db->update('student_penalty', $insert_array);
            $message   = UPDATE_RECORD_CONSTANT . " On  Student Penalty " . $insert_array['student_session_id'];
            $action    = "Update";
            $record_id = $insert_id = $insert_array['student_session_id'];
            $this->log($message, $record_id, $action);
        } else {

            $this->db->insert('student_penalty', $insert_array);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On Student Penalty " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);

            //return $insert_id;
        }
        //======================Code End==============================

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

    public function remove_penalty($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================

        $this->db->where('student_session_id', $id);
        $this->db->delete('student_penalty');
        $message = DELETE_RECORD_CONSTANT . " On student Penalty " . $id;
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
    public function add_online_cart($data)
    {
        $this->db->insert('online_transaction', $data);            
        $id = $this->db->insert_id();
        $hash_code = sha1($id);
        $data_hash = array(
            'hash_code' => $hash_code
        );
        $this->db->where('id', $id);
        $this->db->update('online_transaction', $data_hash);
        $return_data = array(
            'id' => $id,
            'hash_code' => $hash_code,
        );
        return $return_data;
    }        

    public function getCancelledReportReceipt($start_date, $end_date, $feetype_id = null, $received_by = null, $group = null,$payment_mode  = null,$session_id  = null)
    {
        $tb_name = 'fee_receipt_no_' . $this->current_session;

        $this->db->select('*,'.$tb_name.'.id as fee_receipt_id');
        $this->db->join('student_session', 'student_session.id= '.$tb_name.'.student_session_id');
        $this->db->join('classes', 'classes.id= student_session.class_id');
        $this->db->join('sections', 'sections.id= student_session.section_id');
        $this->db->join('students', 'students.id=student_session.student_id');
        $this->db->join('sessions', 'sessions.id=student_session.session_id');
        $this->db->order_by($tb_name.".cancelled_at", 'desc');
        $this->db->where($tb_name.'.status', 'Cancelled');
        if (!empty($received_by)) {
            $this->db->where($tb_name.'.created_by', $received_by);
        }
        if (!empty($start_date)) {
            $this->db->where($tb_name.'.receipt_date >=', $start_date);
        }
        if (!empty($end_date)) {
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
    ////full updations
    ///this function will update every deposite table record with session_id filed in JSON Record
    public function update_fee_deposite()
    {
        //echo "<pre>";
        $tb_name = 'fee_receipt_no_' . $this->current_session;
        $receipt = $this->db->query("select * from ".$tb_name." where id > 1 order by id desc")->result();
        $count = 0;

        foreach($receipt as $receipt_rec)
        {
            $student_session_id = $receipt_rec->student_session_id;
            $sub_invoice = $receipt_rec->id;
            if($student_session_id!='') {
                $this->db->trans_start(); # Starting Transaction
                $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
                //=======================Code Start===========================
                $q = $this->db->query("select * FROM `student_fees_deposite` WHERE student_fees_master_id in (select id from student_fees_master where student_session_id = " . $student_session_id . " ) and  JSON_EXTRACT(amount_detail,'$.*.inv_no') LIKE '%" . $sub_invoice . "%'")->result();
                if (count($q) > 0) {
                    $resultarr = $q;
                    foreach ($resultarr as  $result) {
                        $a = json_decode($result->amount_detail, true);
                            if(isset($a[$sub_invoice]['inv_no']))
                            {
                                    if ($a[$sub_invoice]['inv_no'] == $sub_invoice && $a[$sub_invoice]['student_session_id']==$student_session_id ) {
                                    if (!empty($a)) {
                                        ++$count;
                                        $a[$sub_invoice]['session_id'] = $this->current_session;
                                        $data['amount_detail'] = json_encode($a);
                                        $this->db->where('id', $result->id);
                                        $this->db->update('student_fees_deposite', $data);

                                    }
                                }
                            }
                    }
                }
                //======================Code End==============================
                $this->db->trans_complete(); # Completing transaction
                /* Optional */
                if ($this->db->trans_status() === false) {
                    # Something went wrong.
                    $this->db->trans_rollback();
                    //return false;
                } else {
                    //return $return_value;
                }
                echo "Total Count : ".$count;
            }
        }
    }
    public function create_fee_receipt_from_deposite($id)
    {
        ini_set ('display_errors', 1); 
        ini_set ('display_startup_errors', 1); 
        error_reporting (E_ALL);
        echo "<pre>";
        $tb_name = 'fee_receipt_no_' . $this->current_session;
        
        $receipt = $this->db->query("select * from " . $tb_name . " where id > 1 and  status = 'Active' and id = '".$id."' order by id")->result_array();

        foreach($receipt as $rec)
        {    
        if($rec['receipt_date'] == null && $rec['status']=='Active')
        {
            $sub_invoice = $id;
            $flag = 0;
            $gross_amt = 0;
            $disc_total = 0;
            $net_total = 0;
            $q = $this->db->query("select * FROM `student_fees_deposite` WHERE  JSON_EXTRACT(amount_detail,'$.*.inv_no') LIKE '%" . $sub_invoice . "%'")->result();

            if (count($q) > 0) {
                $resultarr = $q;
                foreach ($resultarr as  $result) {
                    $a = json_decode($result->amount_detail, true);
                        if(isset($a[$sub_invoice]['inv_no']))
                        {
                            $fee_master = $this->db->query("select * from student_fees_master where id = ".$result->student_fees_master_id)->row_array();
                            
                            
                            $fees_session_id  = $fee_master['session_id'];
                            
                            if ($a[$sub_invoice]['inv_no'] == $sub_invoice && $fees_session_id == $this->current_session) {
                                $student_session_id  = $fee_master['student_session_id'];
                                if($flag == 0) {
                                    $rec_date = $a[$sub_invoice]['date'];
                                    $payment_mode  = $a[$sub_invoice]['payment_mode'];
                                    $flag=1;
                                    $student   = $this->studentsession_model->searchStudentsBySession($student_session_id);
                                    //print_r($student);
                                }
                                $gross_amt = $gross_amt + ($a[$sub_invoice]['amount'] + $a[$sub_invoice]['amount_discount']);
                                $net_total = $net_total + $a[$sub_invoice]['amount'];
                                $disc_total = $disc_total +  $a[$sub_invoice]['amount_discount'];
                            }
                        }
                    }
                }
                $arrayUpdate = array(
                    'id'                        => $sub_invoice,
                    'receipt_date'              => $rec_date,
                    'gross_amount'              => $gross_amt,
                    'discount'                  => $disc_total,
                    'fine'                      => 0,
                    'net_amt'                   => $net_total,
                    'total_balance'             => 0,
                    'prev_balance'              => 0,                            
                    'student_session_id'        => $student_session_id,
                    'session_id'                => $student['session_id'],
                    'created_by'                => 'billing',
                    'created_id'                => 44,
                    'note'                      => '',
                    'status'                    => 'Active',
                    'payment_mode'              => $payment_mode,
                );             
                echo "<br>". $sub_invoice . "---" . $rec_date;
                print_r($arrayUpdate);
                $this->db->where('id', $sub_invoice);
                $this->db->update($tb_name, $arrayUpdate);
        }
        }
    }

    public function addCollectFee($array)
    {
        $table = 'fee_receipt_sub_'.$this->current_ay_session;
        
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (!empty($array['id'])) {
            $this->db->where('id', $array['id']);
            $this->db->update($table, $array);
            $message = UPDATE_RECORD_CONSTANT . " On  add collectfee id " . $array["id"];
            $action = "Update";
            $record_id = $array["id"];
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
            $this->db->insert($table, $array);
            $id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On add collectfee id " . $id;
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

    public function getCollectFee($student_session_id)
    {
        $table = 'fee_receipt_sub_'.$this->current_session;
       $this->db->select('*')->where('student_session_id', $student_session_id);
       $res=$this->db->get($table);
       if (!empty($res))
       {
            return $res->result_array();
       }
       else
       {
        return array();
       }
       
    }

    public function removeCollectfee($del_id_arr)
    {
        $table = 'fee_receipt_sub_'.$this->current_session;
        $this->db->where_in('id', $del_id_arr);
        $this->db->delete($table);
        
    }

    public function getStudentFeesReceiptSub($receipt_id)
    {
        $table = 'fee_receipt_sub_'.$this->current_session;
        $this->db->select('*');
        $this->db->join('feetype', 'feetype.id = '.$table.'.fees_type_id');
        $this->db->where('receipt_id', $receipt_id);
        $res=$this->db->get($table);
        if(!empty($res))
        {return $res->result_array();}
        else
        {return array();}
    }    
    public function addreceipt($array)
    {
        
        $table = 'fee_receipt_no_'.$this->current_ay_session;
        $this->db->insert($table,$array);
        return $this->db->insert_id();
       
    }
    public function get_receipt_type($id)
    {
//         ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
        $table = 'fee_receipt_no_'.$this->current_ay_session;
        $table2 = 'fee_receipt_sub_'.$this->current_ay_session;
        
        // echo  $table2, $table2.'.receipt_id = '.$table.'.id' .'<br>';
        // echo 'feetype', 'feetype.id = '.$table2.'.fees_type_id';die();
        $this->db->select($table2.'.*,feetype.type');
        $this->db->join($table2,$table2.'.receipt_id ='.$table.'.id');
        $this->db->join('feetype', 'feetype.id = '.$table2.'.fees_type_id');
        $this->db->where($table2.'.student_session_id',$id);
         $this->db->where($table.'.receipt_type','general');
         $this->db->where($table.'.status','Active');
       $recipt_array =  $this->db->get($table)->result_array();
    //    print_r($recipt_array);die();
        // echo $this->db->last_query();die();
        return $recipt_array;

    }
    public function get_receipt_fees_main($id)
    {
//         ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
        $table = 'fee_receipt_no_'.$this->current_ay_session;
        $table2 = 'fee_receipt_sub_'.$this->current_ay_session;
        
        // echo  $table2, $table2.'.receipt_id = '.$table.'.id' .'<br>';
        // echo 'feetype', 'feetype.id = '.$table2.'.fees_type_id';die();
        $this->db->select($table.'.*');
        $this->db->where($table.'.student_session_id',$id);
         $this->db->where($table.'.receipt_type','fees');
         $this->db->where($table.'.status','Active');
       $recipt_array =  $this->db->get($table)->result_array();
    //    print_r($recipt_array);die();
        // echo $this->db->last_query();die();
        return $recipt_array;

    }    
    public function get_receipt_fees_main_session($id)
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        $result = $this->db->query("select session_id from student_session where id = '".$id."'")->row_array();
        if(!empty($result))
        {

            $session_id = $result['session_id'];

        }
        else
        {
            $session_id = $this->current_session;
        }
        
        $table = 'fee_receipt_no_'.$session_id;
        //$table2 = 'fee_receipt_sub_'.$session_id;
        // echo  $table2, $table2.'.receipt_id = '.$table.'.id' .'<br>';
        // echo 'feetype', 'feetype.id = '.$table2.'.fees_type_id';die();
        $this->db->select($table.'.*');
        $this->db->where($table.'.student_session_id',$id);
         $this->db->where($table.'.receipt_type','fees');
         $this->db->where($table.'.status','Active');
       $recipt_array =  $this->db->get($table)->result_array();
    //    print_r($recipt_array);die();
        // echo $this->db->last_query();die();
        return $recipt_array;

    }      
    public function add_student_fine_collection($data)
    {
        $this->db->insert('student_fine_collection',$data);
    }
    public function cancel_student_fine_collection($sub_invoice,$session_id,$student_session_id)
    {    
        $data = array(
            'status' => 'Cancelled',
        );
        $this->db->where('receipt_id', $sub_invoice);
        $this->db->where('receipt_session', $session_id);
        $this->db->where('student_session_id', $student_session_id);
        $this->db->update('student_fine_collection', $data);
    }

    public function display_all_non_session_json()
    {
        ini_set ('display_errors', 1); ini_set ('display_startup_errors', 1); error_reporting (E_ALL);         
        $this->load->helper('file');
        //echo "<pre>";
        $tb_name = 'fee_receipt_no_' . $this->current_session;
                //=======================Code Start===========================
                echo "<pre>";
                $q = $this->db->query("select * from student_fees_deposite where student_fees_deposite.student_fees_master_id in (select id from student_fees_master where student_fees_master.student_session_id in (select id from student_session where session_id = '" . $this->current_session . "'))")->result();
                if (count($q) > 0) {
                    $resultarr = $q;
                    foreach ($resultarr as  $result) {
                        $a = json_decode($result->amount_detail, true);
                        foreach ($a as $a1)
                        {

                            if($a1['inv_no']==1)
                            {   
                               echo "select * from student_fee_master where id = '" . $result->student_fees_master_id . "'";
                               $fee_master = $this->db->query("select * from student_fees_master where id = '" . $result->student_fees_master_id . "'" )->row_array();
                               $student=$this->studentsession_model->searchStudentsBySession($fee_master['student_session_id']);
                               
                               $st=$student['class'] . "[" . $student['section'] . "]" . $student['roll_no'] . "-" . $student['firstname']." ".$student['middlename'];
                               echo "<br>".$st;
                               
                                                    
                            }
                        }
                        /* if(isset($a[$sub_invoice]['inv_no']))
                            {

                                    if ($a[$sub_invoice]['inv_no'] == $sub_invoice && $a[$sub_invoice]['student_session_id']==$student_session_id ) {
                                    if (!empty($a)) {
                                        ++$count;
                                        $a[$sub_invoice]['session_id'] = $this->current_session;
                                        $data['amount_detail'] = json_encode($a);
                                        $this->db->where('id', $result->id);
                                        $this->db->update('student_fees_deposite', $data);

                                    }
                                }
                            }
                        */
                    
                }
            }
    }
    public function get_current_month_fine_collection($st_session_id,$month_date)
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        $tb_name = 'fee_receipt_no_' . $this->current_ay_session;
        $tb_name_sub = 'fee_receipt_sub_' . $this->current_ay_session;
        //echo "select * from ".$tb_name." where month(receipt_date) = '" . date('m',strtotime($month_date)) . "' and year(receipt_date) =  = '" . date('Y',strtotime($month_date)) . "' and fine > 0 and student_session_id = '" . $st_session_id . "' order by id desc";
        $receipt = $this->db->query("select * from ".$tb_name." where status = 'Active' and month(receipt_date) = '" . date('m',strtotime($month_date)) . "' and year(receipt_date)   = '" . date('Y',strtotime($month_date)) . "' and fine > 0 and student_session_id = '" . $st_session_id . "' order by id desc");
        if(!empty($receipt) && $receipt->num_rows() > 0)
        {

            return 1;
        }
        else
        {
            //echo "select * from ".$tb_name_sub." where fees_type_id = 21 and receipt_id in (select id from ".$tb_name." where  month(receipt_date) = '" . date('m',strtotime($month_date)) . "' and year(receipt_date) =  = '" . date('Y',strtotime($month_date)) . "'  and student_session_id = '" . $st_session_id . "')";
            $receipt = $this->db->query("select * from ".$tb_name_sub." where fees_type_id = 21 and receipt_id in (select id from ".$tb_name." where status = 'Active' and month(receipt_date) = '" . date('m',strtotime($month_date)) . "' and year(receipt_date)  = '" . date('Y',strtotime($month_date)) . "'  and student_session_id = '" . $st_session_id . "')");
            if(!empty($receipt) && $receipt->num_rows() > 0)
            {
                return 1;
            }
            else
            {
                return 0;
            }

        }
    }
    public function get_current_month_fine_collection_amount($st_session_id,$month_date)
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $tb_name = 'fee_receipt_no_' . $this->current_ay_session;
        $tb_name_sub = 'fee_receipt_sub_' . $this->current_ay_session;
        //echo "select sum(fine) from ".$tb_name." where month(receipt_date) = '" . date('m',strtotime($month_date)) . "' and year(receipt_date) =  = '" . date('Y',strtotime($month_date)) . "' and fine > 0 and student_session_id = '" . $st_session_id . "'";
        $receipt = $this->db->query("select sum(fine) as fine from ".$tb_name." where month(receipt_date) = '" . date('m',strtotime($month_date)) . "' and year(receipt_date)   = '" . date('Y',strtotime($month_date)) . "' and fine > 0 and student_session_id = '" . $st_session_id . "' and status = 'Active'");
        
        if(!empty($receipt) && $receipt->num_rows() > 0)
        {
            // echo "<pre>";
            // echo $receipt->result_array()[0]['fine'];die();
            return 1;
        }
        else
        {
            //echo "select * from ".$tb_name_sub." where fees_type_id = 21 and receipt_id in (select id from ".$tb_name." where  month(receipt_date) = '" . date('m',strtotime($month_date)) . "' and year(receipt_date) =  = '" . date('Y',strtotime($month_date)) . "'  and student_session_id = '" . $st_session_id . "')";
            $receipt = $this->db->query("select * from ".$tb_name_sub." where fees_type_id = 21 and receipt_id in (select id from ".$tb_name." where  month(receipt_date) = '" . date('m',strtotime($month_date)) . "' and year(receipt_date)  = '" . date('Y',strtotime($month_date)) . "'  and student_session_id = '" . $st_session_id . "')");
            //echo "<pre>";print_r($receipt);die();
            if(!empty($receipt) && $receipt->num_rows() > 0)
            {
                return 1;
            }
            else
            {
                return 0;
            }

        }
    }
    public function get_previous_month_fine_collection($st_session_id,$last_month_date)
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $tb_name = 'fee_receipt_no_' . $this->current_ay_session;
        $tb_name_sub = 'fee_receipt_sub_' . $this->current_ay_session;
        $receipt = $this->db->query("select sum(fine) as fine from ".$tb_name." where receipt_date <= '" . $last_month_date . "' and fine > 0 and student_session_id = '" . $st_session_id . "'");
        $return_value = 0;
        if(!empty($receipt) && $receipt->num_rows() > 0)
        {
            $return_value = $receipt->result_array()[0]['fine'];
        }
        else
        {
            $receipt = $this->db->query("select sum(amt) as fine from ".$tb_name_sub." where fees_type_id = 21 and receipt_id in (select id from ".$tb_name." where   receipt_date <= '" . $last_month_date . "'   and student_session_id = '" . $st_session_id . "')");
            if(!empty($receipt) && $receipt->num_rows() > 0)
            {
                $return_value = $receipt->result_array()[0]['fine'];
            }
            else
            {
                $return_value = 0;
            }
        }
        return $return_value;
    }
    public function getMonthsDifference($startDate, $endDate) {
        $start = DateTime::createFromFormat('m-Y', $startDate);
        
        $end = DateTime::createFromFormat('m-Y', $endDate);
    
        if (!$start || !$end) {
            return "Invalid date format. Please use MM-YYYY.";
        }
    
        // Calculate the difference
        $difference = $start->diff($end);
    
        // Get the total months (years converted to months + remaining months)
        $totalMonths = ($difference->y * 12) + $difference->m;
    
        return $totalMonths;
    }
    function getPreviousMonthLastDay($specificDate) {
        // Convert the input date into a DateTime object
        $date = new DateTime($specificDate);
        
        // Subtract one month and set the day to the last day of the previous month
        $date->modify('first day of last month'); 
        $date->modify('last day of this month');
        
        // Return the date in the desired format (Y-m-d)
        return $date->format('Y-m-d');
    } 
    public function get_all_fine_settings()
    {
        $fine_result = array();
        $fine_rec = $this->db->query("select * from fees_fine_all where session_id = '" . $this->setting_model->getCurrentSession() . "'");
        if($fine_rec->num_rows() > 0)
        {
            $fine_result = $fine_rec->result_array()[0];
        }
        return $fine_result;

    }
}
