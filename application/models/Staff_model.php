<?php

class Staff_model extends MY_Model
{

    public $current_session;

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function get($id = null)
    {

        $this->db->select('staff.*,languages.language,roles.name as user_type,roles.id as role_id')->from('staff')->join("staff_roles", "staff_roles.staff_id = staff.id", "left")->join("roles", "staff_roles.role_id = roles.id", "left")->join("languages", "languages.id = staff.lang_id", "left");

        if ($id != null) {
            $this->db->where('staff.id', $id);
        } else {
            $this->db->where('staff.is_active', 1);
            $this->db->order_by('staff.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }


    public function getStaffRecord($id,$fields)
    {
        if($fields=='')
        {$this->db->select('*')->from('staff');}    
        else
        {$this->db->select($fields)->from('staff');}
        $this->db->where('id',$id);
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }
    public function getrat()
    {

        $this->db->select('staff.id,staff.employee_id,CONCAT_WS(" ",staff.name,staff.surname,"(",staff.employee_id,")") as name,roles.name as user_type,roles.id as role_id,staff_rating.rate,staff_rating.status,staff_rating.comment,staff_rating.id as rate_id,CONCAT_WS(" ",students.firstname,students.middlename,students.lastname,"(",students.admission_no,")") as student_name')->from('staff')->join("staff_roles", "staff_roles.staff_id = staff.id", "left")->join("roles", "staff_roles.role_id = roles.id", "left")->join("staff_rating", "staff_rating.staff_id = staff.id", "inner")->join("users", "users.id=staff_rating.user_id", "left")->join("students", "students.id=users.user_id", "left");
        $this->db->where('staff.is_active', 1);
        $this->db->where_not_in('roles.id', 7);

        $this->db->order_by('staff.id');
        $query = $this->db->get();
        return $query->result_array();

    }

    public function getTodayDayAttendance()
    {

        $date = date('Y-m-d');

        $this->db->select('staff_id');
        $this->db->from("staff_attendance");
        $this->db->where('date = ', $date);
        $this->db->where("(staff_attendance_type_id='1' OR staff_attendance_type_id='2' OR staff_attendance_type_id='4')");
        $this->db->group_by('staff_attendance.staff_id');
        $query = $this->db->get();
        $q     = $query->result_array();
        return count($q);
    }

    public function getTotalStaff()
    {

        $this->db->select('staff.id');
        $this->db->from("staff");

        $this->db->where("staff.is_active", 1);

        $query = $this->db->get();

        $q = $query->result_array();
        return count($q);
    }

    public function user_reviewlist($id)
    {
        $this->db->select('staff_rating.rate,staff_rating.comment,staff_rating.role,students.firstname as firstname,students.middlename, students.lastname as lastname,students.guardian_name')->from('staff_rating')->join("users", "users.id = staff_rating.user_id", "inner")->join("staff", "staff_rating.staff_id = staff.id", "inner")->join("students", "students.id = staff_rating.user_id", "left");
        $this->db->where('staff.is_active', 1);
        $this->db->where('staff_rating.staff_id', $id);
        $this->db->where('staff_rating.status', 1);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getloanList($id=null)
    {
        $this->db->select('*,staff.id as staff_id');
        $this->db->join('staff_loan', 'staff.id = staff_loan.staff_id');
        if ($id != null) {
            $this->db->where('staff_loan.id', $id);
        }

        $query =$this->db->get('staff');
        
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getAdvanceList($id=null)
    {
        $this->db->select('*,staff.id as staff_id');
        $this->db->join('staff_advance', 'staff.id = staff_advance.staff_id');
        if ($id != null) {
            $this->db->where('staff_advance.id', $id);
        }

        $query =$this->db->get('staff');
        
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getAll($id = null, $is_active = null)
    {

        $this->db->select("staff.*,staff_designation.designation,department.department_name as department, roles.id as role_id, roles.name as role");
        $this->db->from('staff');
        $this->db->join('staff_designation', "staff_designation.id = staff.designation", "left");
        $this->db->join('staff_roles', "staff_roles.staff_id = staff.id", "left");
        $this->db->join('roles', "roles.id = staff_roles.role_id", "left");
        $this->db->join('department', "department.id = staff.department", "left");

        if ($id != null) {
            $this->db->where('staff.id', $id);
        } else {
            if ($is_active != null) {

                $this->db->where('staff.is_active', $is_active);
            }
            $this->db->order_by('staff.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getAll_users($id = null, $is_active = null)
    {

        $this->db->select("staff.*,staff_designation.designation,department.department_name as department, roles.id as role_id, roles.name as role");
        $this->db->from('staff');
        $this->db->join('staff_designation', "staff_designation.id = staff.designation", "left");
        $this->db->join('staff_roles', "staff_roles.staff_id = staff.id", "left");
        $this->db->join('roles', "roles.id = staff_roles.role_id", "left");
        $this->db->join('department', "department.id = staff.department", "left");

        if ($id != null) {
            $this->db->where('staff.id', $id);
        } else {
            if ($is_active != null) {

                $this->db->where('staff.is_active', $is_active);
            }
            $this->db->where('roles.id!=', 7);
            $this->db->order_by('staff.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getBirthDayStaff($dob, $is_active = 1, $email = false, $contact_no = false)
    {

        $this->db->select("staff.*,staff_designation.designation,department.department_name as department, roles.id as role_id, roles.name as role");
        $this->db->from('staff');
        $this->db->join('staff_designation', "staff_designation.id = staff.designation", "left");
        $this->db->join('staff_roles', "staff_roles.staff_id = staff.id", "left");
        $this->db->join('roles', "roles.id = staff_roles.role_id", "left");
        $this->db->join('department', "department.id = staff.department", "left");

        $this->db->where('staff.is_active', $is_active);
        $this->db->where("DATE_FORMAT(staff.dob,'%m-%d') = DATE_FORMAT('" . $dob . "','%m-%d')");
        if ($email) {
            $this->db->where('staff.email !=', "");
        }
        if ($contact_no) {
            $this->db->where('staff.contact_no !=', "");
        }
        $this->db->order_by('staff.id');

        $query = $this->db->get();

        return $query->result_array();
    }

    public function add($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('staff', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On staff id " . $data['id'];
            $action    = "Update";
            $record_id = $id = $data['id'];
            $this->log($message, $record_id, $action);

        } else {
            $this->db->insert('staff', $data);
            $id        = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On staff id " . $id;
            $action    = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);

        }
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /*Optional*/

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;

        } else {
            return $id;
        }
    }
    public function add_loan($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (!empty($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('staff_loan', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On staff_loan id " . $data['id'];
            $action    = "Update";
            $record_id = $id = $data['id'];
            $this->log($message, $record_id, $action);

        } else {
            $this->db->insert('staff_loan', $data);
            $id        = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On staff_loan id " . $id;
            $action    = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);

        }
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /*Optional*/

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;

        } else {
            return $id;
        }
    }
    public function get_salary_recovery($staff_id)
    {
        $this->db->where('staff_id', $staff_id);
        $this->db->where('loan_status', 1);
        $this->db->from('staff_loan');
        $result = $this->db->get();
        if(!empty($result))
        {
            $res_array = $result->result_array();
            $emitotal = 0;
            foreach($res_array as $res)
            {
                $emitotal += (float)$res['loan_emi'];
            }
            return $emitotal;
        }
        else
        {return false;}
    }
    public function get_salary_advance_recovery($staff_id,$month_adv,$year_adv)
    {
        $month_adv = date("m", strtotime($month_adv));
        $this->db->where('staff_id', $staff_id);
        //$this->db->where('adv_status', 1);
        $this->db->where('MONTH(adv_date)',$month_adv);
        $this->db->where('YEAR(adv_date)',$year_adv);
        $this->db->from('staff_advance');
        $result = $this->db->get();
        if(!empty($result))
        {
            $res_array = $result->result_array();
            $adv_total = 0;
            foreach($res_array as $res)
            {
                $adv_total += (float)$res['adv_amount'];
            }
            return $adv_total;
        }
        else
        {return false;}
    }    
    public function get_other_deductions($staff_id,$month_adv,$year_adv)
    {
        $whereIn = [8,9];
        $month_adv = date("m", strtotime($month_adv));
        $this->db->where('staff_id', $staff_id);
        $this->db->where('MONTH(ded_date)',$month_adv);
        $this->db->where('YEAR(ded_date)',$year_adv);
        $this->db->where('status','Active');
        $this->db->where_not_in('ded_type', $whereIn);
        $this->db->from('staff_deduction');
        $result = $this->db->get();
        if(!empty($result))
        {
            $res_array = $result->result_array();
            $ded_total = 0;
            foreach($res_array as $res)
            {
                $ded_total += (float)$res['ded_amount'];
            }
            return $ded_total;
        }
        else
        {return false;}
    }    
    public function get_other_income_tax($staff_id,$month_adv,$year_adv)
    {
        $whereIn = [9];
        $month_adv = date("m", strtotime($month_adv));
        $this->db->where('staff_id', $staff_id);
        $this->db->where('MONTH(ded_date)',$month_adv);
        $this->db->where('YEAR(ded_date)',$year_adv);
        $this->db->where('status','Active');
        $this->db->where_in('ded_type', $whereIn);
        $this->db->from('staff_deduction');
        $result = $this->db->get();
        if(!empty($result))
        {
            $res_array = $result->result_array();
            $ded_total = 0;
            foreach($res_array as $res)
            {
                $ded_total += (float)$res['ded_amount'];
            }
            return $ded_total;
        }
        else
        {return false;}
    }    
    public function get_other_salary_hold($staff_id,$month_adv,$year_adv)
    {
        $whereIn = [8];
        $month_adv = date("m", strtotime($month_adv));
        $this->db->where('staff_id', $staff_id);
        $this->db->where('MONTH(ded_date)',$month_adv);
        $this->db->where('YEAR(ded_date)',$year_adv);
        $this->db->where('status','Active');
        $this->db->where_in('ded_type', $whereIn);
        $this->db->from('staff_deduction');
        $result = $this->db->get();
        if(!empty($result))
        {
            $res_array = $result->result_array();
            $ded_total = 0;
            foreach($res_array as $res)
            {
                $ded_total += (float)$res['ded_amount'];
            }
            return $ded_total;
        }
        else
        {return false;}
    }    
    public function get_other_addition($staff_id,$month_adv,$year_adv)
    {
        $month_adv = date("m", strtotime($month_adv));
        $this->db->where('staff_id', $staff_id);
        $this->db->where('MONTH(add_date)',$month_adv);
        $this->db->where('YEAR(add_date)',$year_adv);
        $this->db->where('status','Active');
        $this->db->from('staff_addition');
        $result = $this->db->get();
        if(!empty($result))
        {
            $res_array = $result->result_array();
            $add_total = 0;
            foreach($res_array as $res)
            {
                $add_total += (float)$res['add_amount'];
            }
            return $add_total;
        }
        else
        {return false;}
    }    
    public function get_staff_pt($gender,$gross)
    {
        $this->db->where('gender',$gender);
        $this->db->order_by('upto');
        $this->db->from('pt_table');
        $result = $this->db->get();
        if(!empty($result))
        {
            $res_array = $result->result_array();
            foreach($res_array as $res)
            {
                if($gross<= $res['upto'])
                { return $res['pt']; }
            }
        }
        else
        {return 0;}
    }     
    public function add_advance($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (!empty($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('staff_advance', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On staff_advance id " . $data['id'];
            $action    = "Update";
            $record_id = $id = $data['id'];
            $this->log($message, $record_id, $action);

        } else {
            $this->db->insert('staff_advance', $data);
            $id        = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On staff_advance id " . $id;
            $action    = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);

        }
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /*Optional*/

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;

        } else {
            return $id;
        }
    }
    public function add_staff_session($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (!empty($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('staff_session', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On staff_session id " . $data['id'];
            $action    = "Update";
            $record_id = $id = $data['id'];
            $this->log($message, $record_id, $action);

        } else {
            $this->db->insert('staff_session', $data);
            $id        = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On staff_session id " . $id;
            $action    = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);

        }
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /*Optional*/

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;

        } else {
            return $id;
        }
    }

    public function getstaffExports()
    {
        $this->db->select('staff_sub.*,staff_session.*,staff.*,roles.name as user_type,staff_designation.designation,department.department_name');
        $this->db->join('staff_sub', 'staff_sub.staff_id = staff.id', 'left');
        $this->db->join('staff_roles', 'staff_roles.staff_id = staff.id', 'left');
        $this->db->join('roles', 'staff_roles.role_id = roles.id', 'left');
        $this->db->join('staff_designation', 'staff_designation.id = staff.designation', 'left');
        $this->db->join('department', "department.id = staff.department", "left");       
        $this->db->join('staff_session', "staff_session.staff_id = staff.id", "left");       
        $this->db->where('staff.record_type', 1);
        $this->db->where('staff.is_active', 1);
        $this->db->where('is_superadmin', 0);
        $query = $this->db->get('staff');
        return $query->result_array() ;
    }
    public function addStaff_sub($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (!empty($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('staff_sub', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On staff_sub id " . $data['id'];
            $action    = "Update";
            $record_id = $id = $data['id'];
            $this->log($message, $record_id, $action);

        } else {
            $this->db->insert('staff_sub', $data);
            $id        = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On staff_sub id " . $id;
            $action    = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);

        }
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /*Optional*/

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;

        } else {
            return $id;
        }
    }

    public function update($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $data['id']);
        $query     = $this->db->update('staff', $data);
        $message   = UPDATE_RECORD_CONSTANT . " On staff id " . $data['id'];
        $action    = "Update";
        $record_id = $data['id'];
        $this->log($message, $record_id, $action);
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /*Optional*/

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;

        } else {
            //return $return_value;
        }
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function getByVerificationCode($ver_code)
    {
        $condition = "verification_code =" . "'" . $ver_code . "'";
        $this->db->select('*');
        $this->db->from('staff');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function batchInsert($data, $roles = array(), $leave_array = array(), $data_setting = array())
    {

        $this->db->trans_start();
        $this->db->trans_strict(false);

        $this->db->insert('staff', $data);
        $staff_id          = $this->db->insert_id();
        $roles['staff_id'] = $staff_id;
        $this->db->insert_batch('staff_roles', array($roles));
        if (!empty($data_setting)) {
            if ($data_setting['staffid_auto_insert']) {
                if ($data_setting['staffid_update_status'] == 0) {
                    $data_setting['staffid_update_status'] = 1;
                    $this->setting_model->add($data_setting);
                }
            }
        }

        if (!empty($leave_array)) {
            foreach ($leave_array as $key => $value) {
                $leave_array[$key]['staff_id'] = $staff_id;
            }

            $this->db->insert_batch('staff_leave_details', $leave_array);
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {

            $this->db->trans_rollback();
            return false;
        } else {

            $this->db->trans_commit();
            return $staff_id;
        }
    }

    public function adddoc($data)
    {

        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('staff_documents', $data);
        } else {
            $this->db->insert('staff_documents', $data);
            return $this->db->insert_id();
        }
    }

    public function remove($id)
    {

        $this->db->trans_start();
        $this->db->trans_strict(false);
        $sql   = "DELETE FROM custom_field_values WHERE id IN (select * from (SELECT t2.id as `id` FROM `custom_fields` INNER JOIN custom_field_values as t2 on t2.custom_field_id=custom_fields.id WHERE custom_fields.belong_to='staff' and t2.belong_table_id IN (" . $id . ")) as m2)";
        $query = $this->db->query($sql);

        $this->db->where('id', $id);
        $this->db->delete('staff');

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {

            $this->db->trans_rollback();
            return false;
        } else {

            $this->db->trans_commit();
            return $staff_id;
        }

    }
    public function remove_adv($id)
    {

        $this->db->trans_start();
        $this->db->trans_strict(false);

        $this->db->where('id', $id);
        $this->db->delete('staff_advance');

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {

            $this->db->trans_rollback();
            return false;
        } else {

            $this->db->trans_commit();
            return true;
        }

    }
    public function remove_loan($id)
    {

        $this->db->trans_start();
        $this->db->trans_strict(false);

        $this->db->where('id', $id);
        $this->db->delete('staff_loan');

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {

            $this->db->trans_rollback();
            return false;
        } else {

            $this->db->trans_commit();
            return true;
        }

    }

    public function add_staff_leave_details($data2)
    {

        if (isset($data2['id'])) {
            $this->db->where('id', $data2['id']);
            $this->db->update('staff_leave_details', $data2);
        } else {
            $this->db->insert('staff_leave_details', $data2);
            return $this->db->insert_id();
        }
    }

    public function getPayroll($id = null)
    {

        $this->db->select()->from('staff_payroll');
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
    public function getstaff_session($id = null)
    {

        $this->db->select()->from('staff_session');
        if ($id != null) {
            $this->db->where('staff_id', $id);
        } else {
            $this->db->order_by('id');
        }
        $this->db->where('session_id', $this->current_session);
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }
    public function getstaff_sub($id = null)
    {

        $this->db->select()->from('staff_sub');
        if ($id != null) {
            $this->db->where('staff_id', $id);
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

    public function getLeaveType($id = null)
    {

        $this->db->select()->from('leave_types');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->where('is_active', 'yes');
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function valid_employee_id($str)
    {
        $name     = $this->input->post('name');
        $id       = $this->input->post('employee_id');
        $staff_id = $this->input->post('editid');

        if ((!isset($id))) {
            $id = 0;
        }
        if (!isset($staff_id)) {
            $staff_id = 0;
        }

        if ($this->check_data_exists($name, $id, $staff_id)) {
            $this->form_validation->set_message('username_check', 'Record already exists');
            return false;

        } else {

            return true;
        }

    }

    public function check_data_exists($name, $id, $staff_id)
    {

        if ($staff_id != 0) {
            $data  = array('id != ' => $staff_id, 'employee_id' => $id);
            $query = $this->db->where($data)->get('staff');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {

            $this->db->where('employee_id', $id);
            $query = $this->db->get('staff');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function import_check_data_exists($name, $id)
    {
        $this->db->where('employee_id', $id);
        $query = $this->db->get('staff');
        if ($query->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function import_check_email_exists($name, $email)
    {
        $this->db->where('email', $email);
        $query = $this->db->get('staff');
        if ($query->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function valid_email_id($str)
    {
        $email    = $this->input->post('email');
        $id       = $this->input->post('employee_id');
        $staff_id = $this->input->post('editid');

        if (!isset($id)) {
            $id = 0;
        }
        if (!isset($staff_id)) {
            $staff_id = 0;
        }

        if ($this->check_email_exists($email, $id, $staff_id)) {
            $this->form_validation->set_message('check_exists', 'Email already exists');
            return false;
        } else {
            return true;
        }
    }

    public function check_email_exists($email, $id, $staff_id)
    {

        if ($staff_id != 0) {
            $data  = array('id != ' => $staff_id, 'email' => $email);
            $query = $this->db->where($data)->get('staff');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {

            $this->db->where('email', $email);
            $query = $this->db->get('staff');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function getStaffRole($id = null)
    {

        $userdata = $this->customlib->getUserData();
        if ($userdata["role_id"] != 7) {
            $this->db->where("id !=", 7);
        }

        $this->db->select('roles.id,roles.name as type')->from('roles');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }
        $this->db->where("is_active", "yes");
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function count_leave($month, $year, $staff_id)
    {

        $query1 = $this->db->select('sum(leave_days) as tl')->where(array('month(date)' => $month, 'year(date)' => $year, 'staff_id' => $staff_id, 'status' => 'approve'))->get("staff_leave_request");
        return $query1->row_array();
    }

    public function alloted_leave($staff_id)
    {

        $query2 = $this->db->select('sum(alloted_leave) as alloted_leave')->where(array('staff_id' => $staff_id))->get("staff_leave_details");

        return $query2->result_array();
    }

    public function allotedLeaveType($id)
    {

        $query = $this->db->select('staff_leave_details.*,leave_types.type')->where(array('staff_id' => $id))->join("leave_types", "staff_leave_details.leave_type_id = leave_types.id")->get("staff_leave_details");

        return $query->result_array();
    }

    public function getAllotedLeave($staff_id)
    {

        $query = $this->db->select('*')->join("leave_types", "staff_leave_details.leave_type_id = leave_types.id")->where("staff_id", $staff_id)->get("staff_leave_details");

        return $query->result_array();
    }

    public function getEmployee($role, $active = 1, $class_id = null)
    {
        $i             = 1;
        $custom_fields = $this->customfield_model->get_custom_fields('staff', 1);

        $field_k_array = array();
        $join_array    = "";
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_k_array, '`table_custom_' . $i . '`.`field_value` as `' . $custom_fields_value->name . '`');
                $this->db->join('custom_field_values as ' . $tb_counter, 'staff.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');

                $i++;
            }
        }

       
        $field_var = count($field_k_array) > 0 ? "," . implode(',', $field_k_array) : "";
       
        $this->db->select("staff.*,roles.name as role,staff_designation.designation,department.department_name as department,roles.name as user_type" . $field_var)->from('staff');
        $this->db->join('staff_designation', "staff_designation.id = staff.designation", "left");
        $this->db->join('staff_roles', "staff_roles.staff_id = staff.id", "left");
        $this->db->join('roles', "roles.id = staff_roles.role_id", "left");
        $this->db->join('department', "department.id = staff.department", "left");       

        if ($class_id != "") {
            $this->db->join('class_teacher', 'staff.id=class_teacher.staff_id', 'left');
            $this->db->or_where('class_teacher.class_id', $student_current_class->class_id);
        }
        $this->db->where("staff.is_active", $active);  
        if($role != ""){
        $this->db->where("roles.id", $role);
          }   
        $query = $this->db->get();

        return $query->result_array();
    }
    
    public function getEmployeeByRoleID($role, $active = 1)
    {

        $query = $this->db->select("staff.*,staff_designation.designation,department.department_name as department, roles.id as role_id, roles.name as role")->join('staff_designation', "staff_designation.id = staff.designation", "left")->join('staff_roles', "staff_roles.staff_id = staff.id", "left")->join('roles', "roles.id = staff_roles.role_id", "left")->join('department', "department.id = staff.department", "left")->where("staff.is_active", $active)->where("roles.id", $role)->get("staff");

        return $query->result_array();
    }
    public function getStaffDesignation()
    {

        $query = $this->db->select('*')->where("is_active", "yes")->get("staff_designation");
        return $query->result_array();
    }
    public function getDepartment()
    {
        $query = $this->db->select('*')->where("is_active", "yes")->get('department');
        return $query->result_array();
    }
    public function getLeaveRecord($id)
    {

        $query = $this->db->select('leave_types.type,leave_types.id as lid,roles.id as staff_role,staff.name,staff.surname,staff.id as staff_id,roles.name as user_type,staff.employee_id,staff_leave_request.*')->join("leave_types", "leave_types.id = staff_leave_request.leave_type_id", "left")->join("staff", "staff.id = staff_leave_request.staff_id")->join("staff_roles", "staff.id = staff_roles.staff_id")->join("roles", "staff_roles.role_id = roles.id")->where("staff_leave_request.id", $id)->get("staff_leave_request");

        return $query->row();
    }
    public function getStaffId($empid)
    {

        $data  = array('employee_id' => $empid);
        $query = $this->db->select('id')->where($data)->get("staff");
        return $query->row_array();
    }
    public function getProfile($id)
    {

        $this->db->select('staff.*,staff_designation.designation as designation,staff_roles.role_id, department.department_name as department,roles.name as user_type');
        $this->db->join("staff_designation", "staff_designation.id = staff.designation", "left");
        $this->db->join("department", "department.id = staff.department", "left");
        $this->db->join("staff_roles", "staff_roles.staff_id = staff.id", "left");
        $this->db->join("roles", "staff_roles.role_id = roles.id", "left");
        $this->db->where("staff.id", $id);
        $this->db->from('staff');
        $query = $this->db->get();
        return $query->row_array();
    }
    public function searchFullText($searchterm, $active)
    {
        $i             = 1;
        $custom_fields = $this->customfield_model->get_custom_fields('staff', 1);

        $field_k_array = array();
        $join_array    = "";
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_k_array, '`table_custom_' . $i . '`.`field_value` as `' . $custom_fields_value->name . '`');               
                $join_array .= " LEFT JOIN `custom_field_values` as `" . $tb_counter . "` ON `staff`.`id` = `" . $tb_counter . "`.`belong_table_id` AND `" . $tb_counter . "`.`custom_field_id` = " . $custom_fields_value->id;

                $i++;
            }
        }
       
        $field_var = count($field_k_array) > 0 ? "," . implode(',', $field_k_array) : "";

        $query = "SELECT `staff`.*, `staff_termination`.`date_of_termination`  ,`staff_termination`.`remarks` as `termination_remarks` ,`roles`.`name` as `role` ,`staff_designation`.`designation` as `designation`, `department`.`department_name` as `department`,`roles`.`name` as user_type " . $field_var . "  FROM `staff` " . $join_array . " LEFT JOIN `staff_designation` ON `staff_designation`.`id` = `staff`.`designation` LEFT JOIN `staff_roles` ON `staff_roles`.`staff_id` = `staff`.`id` LEFT JOIN `roles` ON `staff_roles`.`role_id` = `roles`.`id` LEFT JOIN `department` ON `department`.`id` = `staff`.`department` LEFT JOIN `staff_termination` ON `staff_termination`.`staff_id` = `staff`.`id`  WHERE  `staff`.`is_active` = '$active'  and (CONCAT(`staff`.`name`,' ',`staff`.`surname`) LIKE '%".$this->db->escape_like_str($searchterm)."%' ESCAPE '!' OR `staff`.`surname` LIKE '%".$this->db->escape_like_str($searchterm)."%' ESCAPE '!' OR `staff`.`employee_id` LIKE '%".$this->db->escape_like_str($searchterm)."%' ESCAPE '!' OR `staff`.`local_address` LIKE '%".$this->db->escape_like_str($searchterm)."%' ESCAPE '!'  OR `staff`.`contact_no` LIKE '%".$this->db->escape_like_str($searchterm)."%' ESCAPE '!' OR `staff`.`email` LIKE '%".$this->db->escape_like_str($searchterm)."%' ESCAPE '!' OR `roles`.`name` LIKE '%".$this->db->escape_like_str($searchterm)."' ESCAPE '!') order by date_of_termination";

        $query = $this->db->query($query);
        return $query->result_array();
    }
    //manoj start here
    public function listForIndex()
    {
        $query = "Select name,qualification,image,note from staff where  `staff`.`is_active` = '1' and  designation = 3 order by seniority_id";
        $query = $this->db->query($query);
        return $query->result_array();
    }
    public function getPrincipal()
    {
        $query = "Select name,qualification,image,note,middle_name,surname from staff where  `staff`.`is_active` = '1' and designation = 1 order by id";
        $query = $this->db->query($query);
        return $query->result_array();
    }
    public function getDepartmentStaffAll($depid)
    {
        $query = "Select name,qualification,image,note from staff where  `staff`.`is_active` = '1' and department = '".$depid."' and designation > 2 order by seniority_id";
        $query = $this->db->query($query);
        return $query->result_array();
    }
    //manoj end here
    public function searchByEmployeeId($employee_id)
    {
        $this->db->select('*');
        $this->db->from('staff');
        $this->db->like('staff.employee_id', $employee_id);
        $this->db->like('staff.is_active', 1);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getStaffDoc($id)
    {

        $this->db->select('*');
        $this->db->from('staff_documents');
        $this->db->where('staff_id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function count_attendance($year, $staff_id, $att_type)
    {
        $query = $this->db->select('count(*) as attendence')->where(array('staff_id' => $staff_id, 'year(date)' => $year, 'staff_attendance_type_id' => $att_type))->get("staff_attendance");

        return $query->row()->attendence;
    }

    public function getStaffPayroll($id)
    {
        $this->db->select('*');
        $this->db->from('staff_payslip');
        $this->db->where('staff_id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function doc_delete($id, $doc, $file)
    {

        if ($doc == 1) {

            $data = array('resume' => '');
        } else
        if ($doc == 2) {

            $data = array('joining_letter' => '');
        } else
        if ($doc == 3) {

            $data = array('resignation_letter' => '');
        } else
        if ($doc == 4) {

            $data = array('other_document_name' => '', 'other_document_file' => '');
        }
        unlink(BASEPATH . "uploads/staff_documents/" . $file);
        $this->db->where('id', $id)->update("staff", $data);
    }

    public function getLeaveDetails($id)
    {

        $query = $this->db->select('staff_leave_details.alloted_leave,staff_leave_details.id as altid,leave_types.type,leave_types.id')->join("leave_types", "staff_leave_details.leave_type_id = leave_types.id", "inner")->where("staff_leave_details.staff_id", $id)->get("staff_leave_details");

        return $query->result_array();
    }

    public function disablestaff($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        $query = $this->db->where("id", $data['id'])->update("staff", $data);
        $message   = UPDATE_RECORD_CONSTANT . " On  disable Staff id " . $data['id'];
        $action    = "Update";
        $record_id = $insert_id = $data['id'];
        $this->log($message, $record_id, $action);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }
    public function enablestaff($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        $data = array('is_active' => 1, 'disable_at' => '');
        $query = $this->db->where("id", $id)->update("staff", $data);
        $message   = UPDATE_RECORD_CONSTANT . " On  Enable Staff id " . $id;
        $action    = "Update";
        $record_id = $insert_id = $id;
        $this->log($message, $record_id, $action);

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }
    public function getByEmail($email)
    {
        $this->db->select('staff.*,languages.language,languages.id as language_id');
        $this->db->from('staff')->join('languages', 'languages.id=staff.lang_id', 'left');
        $this->db->where('email', $email);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }
    public function checkLogin($data)
    {

        $record = $this->getByEmail($data['email']);
        if ($record) {
            $pass_verify = $this->enc_lib->passHashDyc($data['password'], $record->password);
            if ($pass_verify) {
                $roles = $this->staffroles_model->getStaffRoles($record->id);
                $record->roles = array($roles[0]->name => $roles[0]->role_id);
                return $record;
            }
        }
        return false;
    }
    public function getStaffbyrole($id)
    {
        $this->db->select('staff.*,staff_designation.designation as designation,staff_roles.role_id, department.department_name as department,roles.name as user_type');
        $this->db->join("staff_designation", "staff_designation.id = staff.designation", "left");
        $this->db->join("department", "department.id = staff.department", "left");
        $this->db->join("staff_roles", "staff_roles.staff_id = staff.id", "left");
        $this->db->join("roles", "staff_roles.role_id = roles.id", "left");
        $this->db->where("staff_roles.role_id", $id);
        $this->db->where("staff.is_active", "1");
        $this->db->from('staff');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function searchNameLike($searchterm)
    {
        $this->db->select('staff.*')->from('staff');
        $this->db->group_start();
        $this->db->like('staff.name', $searchterm);
        $this->db->group_end();
        $this->db->where("staff.is_active", "1");
        $this->db->order_by('staff.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function update_role($role_data)
    {
        $this->db->where("staff_id", $role_data["staff_id"]);
        $q = $this->db->get('staff_roles')->num_rows();
        if ($q > 0) {
            $this->db->where("staff_id", $role_data["staff_id"])->update("staff_roles", $role_data);
        } else {
            $this->db->insert('staff_roles', $role_data);
            
        }
        
        
    }

    public function check_staffid_exists($employee_id)
    {
        $this->db->where(array('employee_id' => $employee_id));
        $query = $this->db->get('staff');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }

    }

    public function lastRecord()
    {
        $last_row = $this->db->select('*')->order_by('id', "desc")->limit(1)->get('staff')->row();
        return $last_row;
    }

    public function ratingapr($id, $approve)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where("id", $id)->update("staff_rating", $approve);
        $message   = UPDATE_RECORD_CONSTANT . " On staff rating id " . $id;
        $action    = "Update";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /*Optional*/

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;

        } else {
            //return $return_value;
        }
    }

    public function rating_remove($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('staff_rating');
        $message   = DELETE_RECORD_CONSTANT . " On staff rating id " . $id;
        $action    = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /*Optional*/
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

    public function get_RatedStaffByUser($user_id)
    {
        $this->db->select('staff_rating.staff_id')->from('staff_rating');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function all_rating()
    {
        $this->db->select('sum(`rate`) as rate, count(*) as total,staff_id')->from('staff_rating');
        $this->db->where('status', '1');
        $this->db->group_by('staff_id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_ratingbyuser($user_id, $role)
    {
        $this->db->select('*')->from('staff_rating');
        $this->db->where('user_id', $user_id);
        $this->db->where('role', $role);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function staff_ratingById($id)
    {
        $this->db->select('sum(`rate`) as rate, count(*) as total')->from('staff_rating');
        $this->db->where('staff_id', $id);
        $this->db->where('status', 1);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_StaffNameById($id)
    {
        return $this->db->select("CONCAT_WS(' ',name,surname) as name,employee_id,id")->from('staff')->where('id', $id)->get()->row_array();
    }

    public function staff_report($condition)
    {
        $i             = 1;
        $custom_fields = $this->customfield_model->get_custom_fields('staff', 1);

        $field_k_array = array();
        $join_array    = "";
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_k_array, '`table_custom_' . $i . '`.`field_value` as `' . $custom_fields_value->name . '`');
                $join_array .= " LEFT JOIN `custom_field_values` as `" . $tb_counter . "` ON `staff`.`id` = `" . $tb_counter . "`.`belong_table_id` AND `" . $tb_counter . "`.`custom_field_id` = " . $custom_fields_value->id;

                $i++;
            }
        }
      
        $field_var = count($field_k_array) > 0 ? "," . implode(',', $field_k_array) : "";

        $query = "SELECT `staff`.*, `staff_designation`.`designation` as `designation`, `department`.`department_name` as `department`,`roles`.`name` as user_type " . $field_var . ",GROUP_CONCAT(leave_type_id,'@',alloted_leave) as leaves  FROM `staff` " . $join_array . " LEFT JOIN `staff_designation` ON `staff_designation`.`id` = `staff`.`designation` LEFT JOIN `staff_roles` ON `staff_roles`.`staff_id` = `staff`.`id` LEFT JOIN `roles` ON `staff_roles`.`role_id` = `roles`.`id` LEFT JOIN `department` ON `department`.`id` = `staff`.`department` left join staff_leave_details ON staff_leave_details.staff_id=staff.id WHERE 1  " . $condition . " and staff.is_active=1 and staff.id!=1 group by staff.id";

        $query = $this->db->query($query);

        return $query->result_array();
    }

    public function inventry_staff()
    {
        return $this->db->select("CONCAT_WS(' ',staff.name,staff.surname) as name,staff.employee_id")->from('staff')->where('staff.is_active', 1)->get()->result_array();

    }

    public function add_teminatedstaff($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('staff_id', $data['staff_id']);
        $q =$this->db->get('staff_termination');
        if ($q->num_rows() > 0) {
            $this->db->where('staff_id', $data['staff_id']);
            $this->db->update('staff_termination', $data);
            $row =$q->row_array();
            $message   = UPDATE_RECORD_CONSTANT . " On Staff Termination id " . $row['id'];
            $action    = "Update";
            $record_id = $id = $row['id'];
            $this->log($message, $record_id, $action);

        } else {
            $this->db->insert('staff_termination', $data);
            $id        = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On Staff Termination id " . $id;
            $action    = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);

        }
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /*Optional*/

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;

        } else {
            return $record_id;
        }
    }


    public function get_terminateRow($staff_id)
    {
        $this->db->where('staff_id', $staff_id);
        $q =$this->db->get('staff_termination')->row_array();
        return $q;
    }

    public function addmonthworkAtt($data)
    {
       $this->db->insert_batch('monthly_work_days_mst', $data);
       
    }
    public function updatemonthworkAtt($data)
    {
       $this->db->update_batch('monthly_work_days_mst', $data, 'id');
    }

    public function get_workingDays($month)
    {
        $this->db->where('month', $month);
        return $this->db->get('monthly_work_days_mst')->row_array();
        
    }

    public function get_profiledata($staff_id)
    {
        $this->db->select('*');
        $this->db->join('staff_sub', 'staff_sub.staff_id = staff.id', 'left');
        $this->db->join('staff_session', 'staff_session.staff_id = staff.id', 'left');
        $this->db->where('staff.id', $staff_id);
        $q = $this->db->get('staff');
        return $q->row_array();
        
    }

    public function addStaffLeaveOp($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (!empty($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('staff_leave_opening', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On Staff leave opening " . $data['id'];
            $action    = "Update";
            $record_id = $id = $data['id'];
            $this->log($message, $record_id, $action);

        } else {
            $this->db->insert('staff_leave_opening', $data);
            $id        = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On Staff leave opening id " . $id;
            $action    = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);

        }
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /*Optional*/

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;

        } else {
            return $record_id;
        }
    }

    public function getstaffleaveOpen($id = null,$staff_id= null)
    {

        $this->db->select()->from('staff_leave_opening');
        if ($id != null) {
            $this->db->where('leave_type_id', $id);
        } else {
            $this->db->order_by('id');
        }
        $this->db->where('staff_id', $staff_id);
        
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getAdvanceStaffMonth($staff_id,$month,$year)
    {
        $monthS = date('m',strtotime($month));
        $result= $this->db->query("SELECT * FROM staff_advance WHERE staff_id = $staff_id and YEAR(adv_date) =$year and MONTH(adv_date)= $monthS ")->row_array();
        
        return $result;
        
        
    }

    public function getStaffMonthAtt($staff_id,$month,$year)
    {
       $this->db->where('staff_id', $staff_id);
       $this->db->where('month', $month);
       $this->db->where('year', $year);
       $result = $this->db->get('staff_att_montly')->row_array();
       
        return $result;
        
        
    }

    public function getloanByStaffId($staff_id)
    {
        $this->db->where('staff_id', $staff_id);
        $this->db->where('loan_status', 1);
        $query = $this->db->get('staff_loan');
        return $query->row_array();
    }

    public function loanUpdate($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (!empty($data['staff_id'])) {
            $this->db->where('staff_id', $data['staff_id']);
            $this->db->update('staff_loan', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On Staff loan staffid " . $data['staff_id'];
            $action    = "Update";
            $record_id = $id = $data['staff_id'];
            $this->log($message, $record_id, $action);

        }
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /*Optional*/

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;

        } else {
            return $record_id;
        }
    }

    public function addStaffSalary($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('staff_id', $data['staff_id']);
        $this->db->where('month', $data['month']);
        $this->db->where('year', $data['year']);
        $this->db->where('status', 1);
        $this->db->where('session_id', $this->current_session);
        $q = $this->db->get('payroll');
        if ($q->num_rows() > 0) {
            $row = $q->row_array();
            $this->db->where('id', $row['id']);
            $this->db->update('payroll', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On payroll " . $row['id'];
            $action    = "Update";
            $record_id = $id = $data['id'];
            $this->log($message, $record_id, $action);
            $record_id=-1;

        } else {
            $this->db->insert('payroll', $data);
            $id        = $this->db->insert_id();
 
            $message   = INSERT_RECORD_CONSTANT . " On payroll id " . $id;
            $action    = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);

        }
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /*Optional*/

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;

        } else {
            return $record_id;
        }
    }
    public function getSalarypayroll($staff_id,$month,$year)
    {
        $this->db->select('payroll.*,staff.payroll_category_id,staff_sub.date_of_retirement');
        $this->db->join('staff', 'staff.id = payroll.staff_id', 'left');
        $this->db->join('staff_sub', 'staff_sub.staff_id = staff.id', 'left');
        $this->db->where('payroll.staff_id', $staff_id);
        $this->db->where('payroll.month', $month);
        $this->db->where('payroll.year', $year);
        $this->db->where('payroll.session_id', $this->current_session);
        $q =$this->db->get('payroll');
        return $q->row_array();
    }
    public function get_class_teacher($student_session_id)
    {
        $query=$this->db->query("select class_id,section_id,session_id from student_session where id = '".$student_session_id."'")->row_array();
        if(!empty($query))
        {
            $class_id=$query['class_id'];
            $section_id=$query['section_id'];
            $session_id=$query['session_id'];
            $query=$this->db->query("select staff_id from class_teacher where class_id = '$class_id'  and section_id = '$section_id' and session_id = '$session_id'")->row_array();
            if(!empty($query))
            {
                $id=$query['staff_id'];
                $this->db->select('contact_no')->from('staff');
                $this->db->where('id', $id);
                $query = $this->db->get();
                $result= $query->row_array();
                return $result['contact_no'];
            }
            else
            { return false;}
        }
    }
    public function get_class_teacher_data($student_session_id)
    {
        $query=$this->db->query("select class_id,section_id,session_id from student_session where id = '".$student_session_id."'")->row_array();
        if(!empty($query))
        {
            $class_id=$query['class_id'];
            $section_id=$query['section_id'];
            $session_id=$query['session_id'];
            $query=$this->db->query("select staff_id from class_teacher where class_id = '$class_id'  and section_id = '$section_id' and session_id = '$session_id'")->row_array();
            if(!empty($query))
            {
                $id=$query['staff_id'];
                $this->db->select('name,contact_no')->from('staff');
                $this->db->where('id', $id);
                $query = $this->db->get();
                $result= $query->row_array();
                return $result;
            }
            else
            { return false;}
        }
    }    

    public function getStaffIdByBiometricId($biometric_id)
    {
        $this->db->select('id');
        $this->db->where('biometric_id', $biometric_id);
        $q = $this->db->get('staff');
        return $q->row_array();
    }

    public function update_leave_detail($data2)
    {
        $this->db->where('leave_type_id', $data2['leave_type_id']);
        $this->db->where('staff_id', $data2['staff_id']);
        $query = $this->db->get('staff_leave_details');
        
        if ($query->num_rows() > 0) {
            // echo "update";
            $this->db->where('leave_type_id', $data2['leave_type_id']);
            $this->db->where('staff_id', $data2['staff_id']);
            $this->db->update('staff_leave_details', $data2);
        }else {
            // echo "insert";
            $this->db->insert('staff_leave_details', $data2);
            return $this->db->insert_id();
        }
    }
    public function addpayrollcategory($data2)
    {
        $this->db->where('staff_id', $data2['staff_id']);
        $this->db->where('payroll_category_id', $data2['payroll_category_id']);
        $query = $this->db->get('staff_payroll_category');
        
        if ($query->num_rows() > 0) {
            // echo "update";
            $this->db->where('payroll_category_id', $data2['payroll_category_id']);
            $this->db->where('staff_id', $data2['staff_id']);
            $this->db->update('staff_payroll_category', $data2);
        }else {
            // echo "insert";
            $this->db->insert('staff_payroll_category', $data2);
            return $this->db->insert_id();
        }
    }

    public function getStaffCategory($staff_id)
    {
        $this->db->select('staff_payroll_category.*');
        $this->db->where('staff_id', $staff_id);
        $q = $this->db->get('staff_payroll_category');
        return $q->result_array(); 
    }
    
    public function getDeductionTypeList($id=null)
    {
        $this->db->select('staff_deduction_type.*');
        if ($id != null) {
            $this->db->where('staff_deduction_type.id', $id);
        }
        $this->db->where('type', 'D');
        $this->db->where('staff_deduction_type.status', 'active');
        
        $query =$this->db->get('staff_deduction_type');
        
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }
    public function add_deduction_type($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (!empty($data['id'])) {
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['updated_by'] = $this->customlib->getStaffID();
            $this->db->where('id', $data['id']);
            $this->db->update('staff_deduction_type', $data);
           
            $message   = UPDATE_RECORD_CONSTANT . " On staff_deduction_type id " . $data['id'];
            $action    = "Update";
            $record_id = $id = $data['id'];
            $this->log($message, $record_id, $action);

        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = $this->customlib->getStaffID();
            $this->db->insert('staff_deduction_type', $data);
            $id        = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On staff_deduction_type id " . $id;
            $action    = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);
        }
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /*Optional*/

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;

        } else {
            return $id;
        }
    }
    public function remove_deduction_type($array)
    {

        $this->db->trans_start();
        $this->db->trans_strict(false);

        $this->db->where('id', $array['id']);
        $this->db->update('staff_deduction_type',$array);

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {

            $this->db->trans_rollback();
            return false;
        } else {

            $this->db->trans_commit();
            return true;
        }

    }
    public function getDeductionList($id=null,$month=null,$year=null)
    {
        $this->db->select('staff_deduction.*,staff.id as staff_id,staff.name,middle_name,surname,staff_deduction_type.name as deduction_name');
        $this->db->join('staff_deduction', 'staff.id = staff_deduction.staff_id');
        $this->db->join('staff_deduction_type', 'staff_deduction.ded_type = staff_deduction_type.id');
        if ($id != null) {
            $this->db->where('staff_deduction.id', $id);
        }
        if($month != null){
            $this->db->where('MONTH(staff_deduction.ded_date)', date("m", strtotime($month)));
        }
        if($year != null){
            $this->db->where('YEAR(staff_deduction.ded_date)', $year);
        }
        $this->db->where('staff_deduction.status', 'Active');
        

        $query =$this->db->get('staff');
        
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function add_deduction($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (!empty($data['id'])) {
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['updated_by'] = $this->customlib->getStaffID();
            $this->db->where('id', $data['id']);
            $this->db->update('staff_deduction', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On staff_deduction id " . $data['id'];
            $action    = "Update";
            $record_id = $id = $data['id'];
            $this->log($message, $record_id, $action);

        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = $this->customlib->getStaffID();
            $this->db->insert('staff_deduction', $data);
            $id        = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On staff_deduction id " . $id;
            $action    = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);

        }
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /*Optional*/

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;

        } else {
            return $id;
        }
    }

    public function remove_deduction($array)
    {

        $this->db->trans_start();
        $this->db->trans_strict(false);

        $this->db->where('id', $array['id']);
        $this->db->update('staff_deduction',$array);

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {

            $this->db->trans_rollback();
            return false;
        } else {

            $this->db->trans_commit();
            return true;
        }

    }

    public function getStaffPayrollDetails($month, $year, $department)
    {
        $this->db->select('staff.id as staff_id,staff.name,staff.middle_name,staff.surname,staff.date_of_joining,
        staff.bank_account_no,staff.gender,staff.biometric_id,payroll.total_attendence,
        payroll.attendence,staff_designation.designation,department.department_name as department,staff_session.scale_of_pay,
        payroll.basic_pay,payroll.gp,payroll.da,payroll.pp,payroll.hra,payroll.ta,
        payroll.other_allowance,payroll.gross_salary,payroll.other_allowance,payroll.other_deduction,
        payroll.pf,payroll.lwp,payroll.loan,payroll.profession_tax,payroll.total_deduction,payroll.nett_salary');
        $this->db->from('staff');
        $this->db->join('payroll', 'payroll.staff_id = staff.id', 'left');
        $this->db->join('staff_designation', 'staff_designation.id = staff.designation', 'left');
        $this->db->join('department', 'department.id = staff.department', 'left');
        $this->db->join('staff_session', 'staff_session.staff_id = staff.id', 'left');
        $this->db->where('staff.department', $department);
        if ($department == 4) { // Administration
            $this->db->where('staff.contract_type !=', "dailyWages");
        }
        if ($department == 5) { // Trust
            $this->db->where('staff.contract_type', "dailyWages");
        }
        $this->db->where('payroll.month', $month);
        $this->db->where('payroll.year', $year);
        $this->db->where('payroll.session_id', $this->current_session);
        
        $query = $this->db->get();
        return $query->result_array();
        
    }

    public function getAdditionTypeList($id=null)
    {
        $this->db->select('staff_deduction_type.*');
        if ($id != null) {
            $this->db->where('staff_deduction_type.id', $id);
        }
        $this->db->where('type', 'A');
        $this->db->where('staff_deduction_type.status', 'active');
        
        $query =$this->db->get('staff_deduction_type');
        
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getAdditionList($id=null,$month=null,$year=null)
    {
        $this->db->select('staff_addition.*,staff.id as staff_id,staff.name,middle_name,surname,staff_deduction_type.name as addition_name');
        $this->db->join('staff_addition', 'staff.id = staff_addition.staff_id');
        $this->db->join('staff_deduction_type', 'staff_addition.add_type = staff_deduction_type.id');
        if ($id != null) {
            $this->db->where('staff_addition.id', $id);
        }
        if($month != null){
            $this->db->where('MONTH(staff_addition.add_date)', date("m", strtotime($month)));
        }
        if($year != null){
            $this->db->where('YEAR(staff_addition.add_date)', $year);
        }
        $this->db->where('staff_addition.status', 'Active');
        

        $query =$this->db->get('staff');
        
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function add_addition($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (!empty($data['id'])) {
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['updated_by'] = $this->customlib->getStaffID();
            $this->db->where('id', $data['id']);
            $this->db->update('staff_addition', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On staff_addition id " . $data['id'];
            $action    = "Update";
            $record_id = $id = $data['id'];
            $this->log($message, $record_id, $action);

        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = $this->customlib->getStaffID();
            $this->db->insert('staff_addition', $data);
            $id        = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On staff_addition id " . $id;
            $action    = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);

        }
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /*Optional*/

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;

        } else {
            return $id;
        }
    }

    public function remove_addition($array)
    {

        $this->db->trans_start();
        $this->db->trans_strict(false);

        $this->db->where('id', $array['id']);
        $this->db->update('staff_addition',$array);

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {

            $this->db->trans_rollback();
            return false;
        } else {

            $this->db->trans_commit();
            return true;
        }

    }

    public function getOtherDeductionRow($staff_id, $month, $year)
    {
        $month = date('m', strtotime($month));
        $this->db->select('ded_amount');
        $this->db->from('staff_deduction');
        $this->db->where('staff_id', $staff_id);
        $this->db->where('MONTH(ded_date)',$month);
        $this->db->where('YEAR(ded_date)',$year);
        $this->db->where('status', 'Active');
        $query = $this->db->get();
        $result = $query->result_array();

        if (!empty($result)) {
            $deduct_amt = 0;
            foreach ($result as $key => $row) {
                $deduct_amt += (float)$row->ded_amount;
            }
            return $deduct_amt;
        }else {
            return 0;
        }
        
    }

    public function getstaffByASC($id = null)
    {

        $this->db->select('staff.*,languages.language,roles.name as user_type,roles.id as role_id')->from('staff')->join("staff_roles", "staff_roles.staff_id = staff.id", "left")->join("roles", "staff_roles.role_id = roles.id", "left")->join("languages", "languages.id = staff.lang_id", "left");

        if ($id != null) {
            $this->db->where('staff.id', $id);
        } else {
            $this->db->where('staff.is_active', 1);
            $this->db->order_by('staff.name', 'ASC');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getTerminationRow($staff_id)
    {
        $this->db->select('*');
        $this->db->from('staff_termination');
        $this->db->where('staff_id', $staff_id);
        $query = $this->db->get();
        return $query->row_array();
        
    }
}
