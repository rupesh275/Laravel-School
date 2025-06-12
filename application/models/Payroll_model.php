<?php

/**
 *
 */
class Payroll_model extends MY_Model
{

    public $current_session;
    public $current_date;

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
        $this->current_date    = $this->setting_model->getDateYmd();
    }

    public function searchEmployee($month, $year, $emp_name, $role)
    {

        $date_month = date("m", strtotime($year));
        if (!empty($role) && !empty($emp_name)) {

            $query = $this->db->query("select staff_payslip.status,
        IFNULL(staff_payslip.id, 0) as payslip_id ,staff.* ,roles.name as user_type ,staff_designation.designation as designation,department.department_name as department from staff left join staff_payslip on staff.id = staff_payslip.staff_id and month = " . $this->db->escape($month) . " and year = " . $this->db->escape($year) . " left join department on department.id = staff.department left join staff_designation on staff_designation.id = staff.designation left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id where roles.name = " . $this->db->escape($role) . " and name = " . $this->db->escape($emp_name) . " and staff.is_active = 1 ");
        } else if (!empty($role)) {

            $query = $this->db->query("select staff_payslip.status,
        IFNULL(staff_payslip.id, 0) as payslip_id ,staff.*,staff_designation.designation as designation,department.department_name as department ,roles.name as user_type from staff left join staff_payslip on staff.id = staff_payslip.staff_id and month = " . $this->db->escape($month) . " and year = " . $this->db->escape($year) . " left join department on department.id = staff.department left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id left join staff_designation on staff_designation.id = staff.designation where roles.name = " . $this->db->escape($role) . " and staff.is_active = 1 ");
        } else {

            $query = $this->db->query("select staff_payslip.status,
        IFNULL(staff_payslip.id, 0) as payslip_id ,staff.* ,roles.name as user_type ,staff_designation.designation as designation,department.department_name as department  from staff left join staff_payslip on staff.id = staff_payslip.staff_id and month = " . $this->db->escape($month) . " and year = " . $this->db->escape($year) . " left join department on department.id = staff.department left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id left join staff_designation on staff_designation.id = staff.designation where staff.is_active = 1 ");
        }

        return $query->result_array();
    }
    public function searchEmployeeForGroup($month, $year, $emp_name, $role)
    {

        $date_month = date("m", strtotime($year));
        if (!empty($role) && !empty($emp_name)) {

            $query = $this->db->query("select staff_session.*, staff_payslip.status,
        IFNULL(staff_payslip.id, 0) as payslip_id ,staff.* ,roles.name as user_type ,staff_designation.designation as designation,department.department_name as department from staff left join staff_session on staff.id = staff_session.staff_id left join staff_payslip on staff.id = staff_payslip.staff_id and month = " . $this->db->escape($month) . " and year = " . $this->db->escape($year) . " left join department on department.id = staff.department left join staff_designation on staff_designation.id = staff.designation left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id where roles.name = " . $this->db->escape($role) . " and name = " . $this->db->escape($emp_name) . " and staff.is_active = 1 ");
        } else if (!empty($role)) {

            $query = $this->db->query("select staff_session.*,staff_payslip.status,
        IFNULL(staff_payslip.id, 0) as payslip_id ,staff.*,staff_designation.designation as designation,department.department_name as department ,roles.name as user_type from staff left join staff_session on staff.id = staff_session.staff_id left join staff_payslip on staff.id = staff_payslip.staff_id and month = " . $this->db->escape($month) . " and year = " . $this->db->escape($year) . " left join department on department.id = staff.department left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id left join staff_designation on staff_designation.id = staff.designation where roles.name = " . $this->db->escape($role) . " and staff.is_active = 1 ");
        } else {

            $query = $this->db->query("select staff_session.*,staff_payslip.status,
        IFNULL(staff_payslip.id, 0) as payslip_id ,staff.* ,roles.name as user_type ,staff_designation.designation as designation,department.department_name as department  from staff left join staff_session on staff.id = staff_session.staff_id left join staff_payslip on staff.id = staff_payslip.staff_id and month = " . $this->db->escape($month) . " and year = " . $this->db->escape($year) . " left join department on department.id = staff.department left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id left join staff_designation on staff_designation.id = staff.designation where staff.is_active = 1 ");
        }

        return $query->result_array();
    }
    public function searchEmployeeForGroupDepart($month, $year, $emp_name, $role)
    {

        $date_month = date("m", strtotime($year));
        if (!empty($role) && !empty($emp_name)) {

            $query = $this->db->query("select staff_session.*, staff_payslip.status,
        IFNULL(staff_payslip.id, 0) as payslip_id ,staff.* ,roles.name as user_type ,staff_designation.designation as designation,department.department_name as department from staff left join staff_session on staff.id = staff_session.staff_id left join staff_payslip on staff.id = staff_payslip.staff_id and month = " . $this->db->escape($month) . " and year = " . $this->db->escape($year) . " left join department on department.id = staff.department left join staff_designation on staff_designation.id = staff.designation left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id where department.id = " . $this->db->escape($role) . " and name = " . $this->db->escape($emp_name) . " and staff.is_active = 1 and staff.record_type = 1 order by basic_salary desc,date_of_joining");
        } else if (!empty($role)) {

            $query = $this->db->query("select staff_session.*,staff_payslip.status,
        IFNULL(staff_payslip.id, 0) as payslip_id ,staff.*,staff_designation.designation as designation,department.department_name as department ,roles.name as user_type from staff left join staff_session on staff.id = staff_session.staff_id left join staff_payslip on staff.id = staff_payslip.staff_id and month = " . $this->db->escape($month) . " and year = " . $this->db->escape($year) . " left join department on department.id = staff.department left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id left join staff_designation on staff_designation.id = staff.designation where department.id = " . $this->db->escape($role) . " and staff.is_active = 1  and staff.record_type = 1 order by basic_salary desc,date_of_joining");
        } else {

            $query = $this->db->query("select staff_session.*,staff_payslip.status,
        IFNULL(staff_payslip.id, 0) as payslip_id ,staff.* ,roles.name as user_type ,staff_designation.designation as designation,department.department_name as department  from staff left join staff_session on staff.id = staff_session.staff_id left join staff_payslip on staff.id = staff_payslip.staff_id and month = " . $this->db->escape($month) . " and year = " . $this->db->escape($year) . " left join department on department.id = staff.department left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id left join staff_designation on staff_designation.id = staff.designation where staff.is_active = 1 and staff.record_type = 1 order by basic_salary desc,date_of_joining");
        }
        //echo $this->db->last_query();die();
        return $query->result_array();
    }
    public function searchEmployeeForGroupDepart_special($month, $year, $emp_name, $role)
    {
        $month_year = date("m", strtotime($month)) . "-" . date("Y", strtotime($year));
        $date_month = date("m", strtotime($year));
        if (!empty($role) && !empty($emp_name)) {

            $query = $this->db->query("select staff_session.*, staff_payslip.status,
        IFNULL(staff_payslip.id, 0) as payslip_id ,staff.* ,roles.name as user_type ,staff_designation.designation as designation,department.department_name as department from staff left join staff_session on staff.id = staff_session.staff_id left join staff_payslip on staff.id = staff_payslip.staff_id and month = " . $this->db->escape($month) . " and year = " . $this->db->escape($year) . " left join department on department.id = staff.department left join staff_designation on staff_designation.id = staff.designation left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id where department.id = " . $this->db->escape($role) . " and name = " . $this->db->escape($emp_name) . " and (staff.is_active = 1 or (staff.is_active=0 and staff.salary_upto_month >= '" . $month_year . "')) and staff.record_type = 1 order by basic_salary desc,date_of_joining");
        } else if (!empty($role)) {
            if($role<4)
            {
                $query = $this->db->query("select staff_session.*,staff_payslip.status,
                IFNULL(staff_payslip.id, 0) as payslip_id ,staff.*,staff_designation.designation as designation,department.department_name as department ,roles.name as user_type from staff left join staff_session on staff.id = staff_session.staff_id left join staff_payslip on staff.id = staff_payslip.staff_id and month = " . $this->db->escape($month) . " and year = " . $this->db->escape($year) . " left join department on department.id = staff.department left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id left join staff_designation on staff_designation.id = staff.designation where department.id = " . $this->db->escape($role) . " and (staff.is_active = 1 or (staff.is_active=0 and staff.salary_upto_month >= '" . $month_year . "'))  and staff.record_type = 1 order by basic_salary desc,date_of_joining");                
            }
            elseif($role==4)
            {
                $query = $this->db->query("select staff_session.*,staff_payslip.status,
                IFNULL(staff_payslip.id, 0) as payslip_id ,staff.*,staff_designation.designation as designation,department.department_name as department ,roles.name as user_type from staff left join staff_session on staff.id = staff_session.staff_id left join staff_payslip on staff.id = staff_payslip.staff_id and month = " . $this->db->escape($month) . " and year = " . $this->db->escape($year) . " left join department on department.id = staff.department left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id left join staff_designation on staff_designation.id = staff.designation where department.id = " . $this->db->escape($role) . " and staff.contract_type != 'dailyWages' and (staff.is_active = 1 or (staff.is_active=0 and staff.salary_upto_month >= '" . $month_year . "'))  and staff.record_type = 1 order by basic_salary desc,date_of_joining");                
            }
            elseif($role==5)
            {
                //echo "select staff_session.*,staff_payslip.status,
                //IFNULL(staff_payslip.id, 0) as payslip_id ,staff.*,staff_designation.designation as designation,department.department_name as department ,roles.name as user_type from staff left join staff_session on staff.id = staff_session.staff_id left join staff_payslip on staff.id = staff_payslip.staff_id left join department on department.id = staff.department left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id left join staff_designation on staff_designation.id = staff.designation where department.id = 4 and staff.contract_type = 'dailyWages' and staff.is_active = 1  and staff.record_type = 1 order by basic_salary desc,date_of_joining";die();
                $query = $this->db->query("select staff_session.*,staff_payslip.status,
                IFNULL(staff_payslip.id, 0) as payslip_id ,staff.*,staff_designation.designation as designation,department.department_name as department ,roles.name as user_type from staff left join staff_session on staff.id = staff_session.staff_id left join staff_payslip on staff.id = staff_payslip.staff_id left join department on department.id = staff.department left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id left join staff_designation on staff_designation.id = staff.designation where department.id = 4 and staff.contract_type = 'dailyWages' and (staff.is_active = 1 or (staff.is_active=0 and staff.salary_upto_month <= " . $month_year . "))  and staff.record_type = 1 order by basic_salary desc,date_of_joining");                
            }
            
        } else {

            $query = $this->db->query("select staff_session.*,staff_payslip.status,
        IFNULL(staff_payslip.id, 0) as payslip_id ,staff.* ,roles.name as user_type ,staff_designation.designation as designation,department.department_name as department  from staff left join staff_session on staff.id = staff_session.staff_id left join staff_payslip on staff.id = staff_payslip.staff_id and month = " . $this->db->escape($month) . " and year = " . $this->db->escape($year) . " left join department on department.id = staff.department left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id left join staff_designation on staff_designation.id = staff.designation where (staff.is_active = 1 or (staff.is_active=0 and staff.salary_upto_month <= " . $month_year . ")) and staff.record_type = 1 order by basic_salary desc,date_of_joining");
        }
        // echo $this->db->last_query();die();
        if (!empty($query)) {
            return $query->result_array();
        }else {
            
            return array();
        }
    }   
    function countSundays($year, $month) {
        $sundays = 0;
        $totalDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    
        for ($day = 1; $day <= $totalDays; $day++) {
            // mktime(hour, minute, second, month, day, year)
            $timestamp = mktime(0, 0, 0, $month, $day, $year);
            if (date('N', $timestamp) == 7) { // 'N' returns day of the week (1 = Monday, ..., 7 = Sunday)
                $sundays++;
            }
        }
    
        return $sundays;
    }     
    public function createPayslip($data)
    {

        if (isset($data['id'])) {
            $this->db->trans_start(); # Starting Transaction
            $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
            //=======================Code Start===========================
            $this->db->where('id', $data['id']);
            $this->db->update('staff_payslip', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On staff payslip id " . $data['id'];
            $action    = "Update";
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
            $this->db->trans_start(); # Starting Transaction
            $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
            //=======================Code Start===========================
            $this->db->insert('staff_payslip', $data);
            $id = $this->db->insert_id();

            $message   = INSERT_RECORD_CONSTANT . " On staff payslip id " . $id;
            $action    = "Insert";
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
                return $id;
            }
        }
    }

    public function checkPayslip($month, $year, $staff_id)
    {

        $query = $this->db->where(array('month' => $month, 'year' => $year, 'staff_id' => $staff_id))->get("staff_payslip");

        if ($query->num_rows() > 0) {
            return false;
        } else {

            return true;
        }
    }

    public function add_allowance($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('payslip_allowance', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On payslip allowance id " . $data['id'];
            $action    = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('payslip_allowance', $data);
            $id = $this->db->insert_id();

            $message   = INSERT_RECORD_CONSTANT . " On payslip allowance id " . $id;
            $action    = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);
        }

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

    public function add_allowance_old($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('payslip_allowance', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On payslip allowance id " . $data['id'];
            $action    = "Update";
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
            $this->db->insert('payslip_allowance', $data);
            return $id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On payslip allowance id " . $id;
            $action    = "Insert";
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
    }

    public function searchPaylist($name, $month, $year)
    {

        $query = $this->db->select('staff.*,staff_designation.designation as desg,department.department_name as department')->where(array('staff.name' => $name, 'staff_payslip.month' => $month, 'staff_payslip.year' => $year))->join("staff_payslip", "staff.id = staff_payslip.staff_id")->join("staff_designation", "staff.designation = staff_designation.id")->join("department", "staff.department = department.id")->get("staff");

        return $query->result_array();
    }

    public function count_attendance($month, $year, $staff_id, $attendance_type = 1)
    {

        $date_month = date("m", strtotime($month));
        $query      = $this->db->select('count(*) as att')->where(array('staff_id' => $staff_id, 'month(date)' => $month, 'year(date)' => $year, 'staff_attendance_type_id' => $attendance_type))->get("staff_attendance");
        return $query->result_array();
    }

    public function count_attendance_obj($month, $year, $staff_id, $attendance_type = 1)
    {

        $query = $this->db->select('count(*) as attendence')->where(array('staff_id' => $staff_id, 'month(date)' => $month, 'year(date)' => $year, 'staff_attendance_type_id' => $attendance_type))->get("staff_attendance");

        return $query->row()->attendence;
    }

    public function updatePaymentStatus($status, $id)
    {

        $data = array('status' => $status);
        $this->db->where("id", $id)->update("staff_payslip", $data);
    }

    public function searchEmployeeById($id)
    {

        $query = $this->db->select('staff.*,roles.name as user_type ,staff_designation.designation,department.department_name as department')->join("staff_designation", "staff_designation.id = staff.designation", "left")->join("department", "department.id = staff.department", "left")->join("staff_roles", "staff_roles.staff_id = staff.id", "left")->join("roles", "staff_roles.role_id = roles.id", "left")->where("staff.id", $id)->get("staff");

        return $query->row_array();
    }

    public function searchPayment($id, $month, $year)
    {

        $query = $this->db->select('staff.name,staff.surname,staff.employee_id,staff.basic_salary,staff_payslip.*')->where(array('staff_payslip.month' => $month, 'staff_payslip.year' => $year, 'staff_payslip.staff_id' => $id))->join("staff_payslip", "staff.id = staff_payslip.staff_id")->get("staff");
        return $query->row_array();
    }

    public function paymentSuccess($data, $payslipid)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where("id", $payslipid)->update("staff_payslip", $data);
        $message   = UPDATE_RECORD_CONSTANT . " On staff payslip id " . $payslipid;
        $action    = "Update";
        $record_id = $payslipid;
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

    public function getPayslip($id)
    {

        $query = $this->db->select("staff.name,staff.surname,department.department_name as department,staff_designation.designation,staff.employee_id,staff_payslip.*")->join("staff", "staff.id = staff_payslip.staff_id")->join("staff_designation", "staff.designation = staff_designation.id", "left")->join("department", "staff.department = department.id", "left")->where("staff_payslip.id", $id)->get("staff_payslip");

        return $query->row_array();
    }

    public function getPayslipNew($staff_id,$month,$year)
    {

        $query = $this->db->select("staff.name,staff_sub.uan_no,staff.surname,staff.bank_account_no,department.department_name as department,staff_designation.designation,staff.employee_id,payroll.*")
        ->join("staff", "staff.id = payroll.staff_id")
        ->join("staff_sub", "staff_sub.staff_id = staff.id",'left')
        ->join("staff_designation", "staff.designation = staff_designation.id", "left")
        ->join("department", "staff.department = department.id", "left")
        ->where("payroll.staff_id", $staff_id)
        ->where("payroll.month", $month)
        ->where("payroll.year", $year)
        ->get("payroll");

        return $query->row_array();
    }

    public function getAllowance($id, $type = null)
    {

        if (!empty($type)) {

            $query = $this->db->select("allowance_type,amount,cal_type")->where(array('payslip_id' => $id, 'cal_type' => $type))->get("payslip_allowance");
        } else {

            $query = $this->db->select("allowance_type,amount,cal_type")->where("payslip_id", $id)->get("payslip_allowance");
        }

        return $query->result_array();
    }

    public function getSalaryDetails($id)
    {

        $query = $this->db->select("sum(net_salary) as net_salary, sum(total_allowance) as earnings, sum(total_deduction) as deduction, sum(basic) as basic_salary, sum(tax) as tax")->where(array('staff_id' => $id, 'status' => 'paid'))->get("staff_payslip");
        return $query->row_array();
    }

    public function getpayrollReport($month, $year, $role)
    {

        if ($role == "select" && $month != "") {
            $data = array('staff_payslip.month' => $month, 'staff_payslip.year' => $year, 'staff_payslip.status' => 'paid');
        } else if ($role == "select" && $month == "") {

            $data = array('staff_payslip.year' => $year, 'staff_payslip.status' => 'paid');
        } else if ($role != "select" && $month == "") {

            $data = array('staff_payslip.year' => $year, 'department.id' => $role, 'staff_payslip.status' => 'paid');
        } else {

            $data = array('staff_payslip.month' => $month, 'staff_payslip.year' => $year, 'department.id' => $role, 'staff_payslip.status' => 'paid');
        }
        $data['staff.is_active'] = 1;

        $query = $this->db->select('staff.id,staff.employee_id,staff.name,roles.name as user_type,staff.surname,staff_designation.designation,department.department_name as department,staff_payslip.*')->join("staff_payslip", "staff_payslip.staff_id = staff.id", "inner")->join("staff_designation", "staff.designation = staff_designation.id", "left")->join("department", "staff.department = department.id", "left")->join("staff_roles", "staff_roles.staff_id = staff.id", "left")->join("roles", "staff_roles.role_id = roles.id", "left")->where($data)->get("staff");

        return $query->result_array();
    }

    public function deletePayslip($payslipid)
    {

        $this->db->where("id", $payslipid)->delete("staff_payslip");
        $this->db->where("payslip_id", $payslipid)->delete("payslip_allowance");
    }

    public function revertPayslipStatus($payslipid)
    {

        $data = array('status' => "generated");

        $this->db->where("id", $payslipid)->update("staff_payslip", $data);
    }
    public function payrollYearCount()
    {
        $query = $this->db->select("distinct(year) as year")->get("staff_payslip");
        return $query->result_array();
    }
    public function getbetweenpayrollReport($start_date, $end_date)
    {
        $condition = "date_format(staff_payslip.payment_date,'%Y-%m-%d') between '" . $start_date . "' and '" . $end_date . "'";
        $query     = $this->db->select('staff.id,staff.employee_id,staff.name,roles.name as user_type,staff.surname,staff_designation.designation,department.department_name as department,staff_payslip.*')->join("staff_payslip", "staff_payslip.staff_id = staff.id", "inner")->join("staff_designation", "staff.designation = staff_designation.id", "left")->join("department", "staff.department = department.id", "left")->join("staff_roles", "staff_roles.staff_id = staff.id", "left")->join("roles", "staff_roles.role_id = roles.id", "left")->where($condition)->get("staff");
        return $query->result_array();
    }
    public function removeSalary($staff_id,$month,$year)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where("staff_id", $staff_id);
        $this->db->where("month", $month);
        $this->db->where("year", $year);
        $this->db->delete('payroll');
        
        $message   = DELETE_RECORD_CONSTANT . " On Payroll id " . $staff_id;
        $action    = "Delete";
        $record_id = $staff_id;
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

    public function cancelpayroll($month,$year,$session_id,$department_id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where("month", $month);
        $this->db->where("year", $year);
        $this->db->where("session_id", $session_id);
        $this->db->where("department_id", $department_id);
        $this->db->delete('payroll');
        $message   = DELETE_RECORD_CONSTANT . " On Payroll id " . $session_id;
        $action    = "Delete";
        $record_id = $session_id;
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

    public function getPayrollCategory($id = null) {
        $this->db->select()->from('payroll_category');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }
        $this->db->where('status', 'active');
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function addPayrollCategory($data) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $userdata           = $this->customlib->getUserData();
        if (!empty($data['id'])) {
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['updated_by'] = $userdata['id'];
            $this->db->where('id', $data['id']);
            $this->db->update('payroll_category', $data);
            $message = UPDATE_RECORD_CONSTANT . " On  payroll category   id " . $data['id'];
            $action = "Update";
            $record_id = $return_value = $data['id'];
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = $userdata['id'];
            $this->db->insert('payroll_category', $data);
            $return_value = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On  payroll category   id " . $return_value;
            $action = "Insert";
            $record_id = $return_value;
        }
        $this->log($message, $record_id, $action);

        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {


            return $return_value;
        }
    }

    public function remove_payroll_category($id)
    {
        $userdata           = $this->customlib->getUserData();
        $delArr = array(
            'status' => 'deleted',
            'deleted_at' => date('Y-m-d H:i:s'),
            'deleted_by' => $userdata['id'],
        );
        $this->db->where("id", $id);
        $this->db->update('payroll_category', $delArr);
       
        $message   = DELETE_RECORD_CONSTANT . " On Payroll category id " . $id;
        $action    = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        // //======================Code End==============================

    }

    public function getpayrollPFReport($month, $year)
    {
        $this->db->select('staff.name,staff.surname,staff_designation.designation,staff.employee_id,payroll.*,staff_session.scale_of_pay,');
        $this->db->join("payroll", "staff.id = payroll.staff_id");
        $this->db->join("staff_designation", "staff.designation = staff_designation.id", "left");
        $this->db->join('staff_session', 'staff_session.staff_id = staff.id', 'left');
        $this->db->where('payroll.month', $month);
        $this->db->where('payroll.year', $year);
        $this->db->where('payroll.session_id', $this->current_session);
        $query = $this->db->get('staff');

        return $query->result_array();
        
    }

    public function getBankList($id = null)
    {
        $this->db->select()->from('bank_master');
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

    public function getSalaryCheque($id = null)
    {
        $this->db->select()->from('salary_cheque_trn');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }  
        $this->db->where('status', 'Active');
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function add_salary_cheque($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $userdata           = $this->customlib->getUserData();
        if (!empty($data['id'])) {
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['updated_by'] = $userdata['id'];
            $this->db->where('id', $data['id']);
            $this->db->update('salary_cheque_trn', $data);
            $message = UPDATE_RECORD_CONSTANT . " On  Salary Cheque   id " . $data['id'];
            $action = "Update";
            $record_id = $return_value = $data['id'];
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = $userdata['id'];
            $this->db->insert('salary_cheque_trn', $data);
            $return_value = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On  Salary Cheque   id " . $return_value;
            $action = "Insert";
            $record_id = $return_value;
        }
        $this->log($message, $record_id, $action);

        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {


            return $return_value;
        }
    }

    public function remove_salary_cheque($array)
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);

        $this->db->where('id', $array['id']);
        $this->db->update('salary_cheque_trn',$array);

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {

            $this->db->trans_rollback();
            return false;
        } else {

            $this->db->trans_commit();
            return true;
        }
    }

    public function getSalaryEmpArr($month, $year,$type)
    {
        
        $this->db->select('staff.id as staff_id,staff.name,staff.surname,staff.bank_account_no,
        payroll.nett_salary,payroll.pf,payroll.pf_earning,payroll.profession_tax');
        $this->db->from('payroll');
        $this->db->join('staff', 'staff.id = payroll.staff_id', 'left');
        if ($type == 'SALARY') {
            $this->db->where('payroll.contract_type', 'Salary');
        }
        if ($type == 'DailyWages') {
            $this->db->where('payroll.contract_type', 'dailyWages');
        }
        $this->db->where('payroll.status', 1);
        $this->db->where('month', $month);
        $this->db->where('year', $year);
        $this->db->order_by('payroll.gross_salary', 'desc');
        $this->db->order_by('staff.date_of_joining');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getPayrollSetting()
    {
        $builder = $this->db->from('payroll_settings');
        $builder->select('*');
        $builder->where('status', 'active');
        $query = $builder->get();
        return $query->result_array();
    }

    public function getPayrollSettingById($id)
    {
        $builder = $this->db->from('payroll_settings');
        $builder->select('*');
        $builder->where('id', $id);
        $query = $builder->get();
        return $query->row_array();
    }
    public function getPayrollSettingupdate($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $userdata           = $this->customlib->getUserData();
        if (!empty($data['id'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = $userdata['id'];
            $data['start_date'] = date('Y-m-d');
            $status = ['status' => 'inactive',
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $userdata['id'],
             ];
            $this->db->where('id', $data['id']);
            $this->db->update('payroll_settings', $status);
            $data['id'] = '';
            $this->db->insert('payroll_settings', $data);
            $message = UPDATE_RECORD_CONSTANT . " On  payroll setting  id " . $data['id'];
            $action = "Update";
            $record_id = $return_value = $data['id'];
        } else {
            
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = $userdata['id'];
            $data['start_date'] = date('Y-m-d');
            $data['status'] = 'active';
            $this->db->insert('payroll_settings', $data);
            $return_value = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On  payroll setting  id " . $return_value;
            $action = "Insert";
            $record_id = $return_value;

        }
        $this->log($message, $record_id, $action);

        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {


            return $return_value;
        }


    }

    public function get_amount($type,$mon,$year)
    {
        if($type=='SALARY')
        {
        $result = $this->db->query("select sum(nett_salary) as total from payroll where contract_type = 'Salary' and month = '".$mon."' and year = '".$year."' and status = '1' ");
        }
        elseif($type=='DailyWages')
        {
        $result = $this->db->query("select sum(nett_salary) as total from payroll where contract_type = 'dailyWages' and month = '".$mon."' and year = '".$year."' and status = '1' ");
        }
        elseif($type=='PF')
        {
        $result = $this->db->query("select sum(pf) as total from payroll where month = '".$mon."' and year = '".$year."' and status = '1' ");
        }
        elseif($type=='PT')
        {
        $result = $this->db->query("select sum(profession_tax) as total from payroll where month = '".$mon."' and year = '".$year."' and status = '1' ");
        }

        return $result->row();
    }
    public function get_payroll_setting()
    {
        $this->db->where('status','active');
        $query = $this->db->get('payroll_settings');
        return $query->row_array();
    }

    public function getPtDetail($month, $year)
    {
        // SELECT staff.gender, payroll.profession_tax,count(*) FROM payroll inner join staff on payroll.staff_id = staff.id where payroll.month = 'July' and year = '2024' group by staff.gender, payroll.profession_tax;
        $this->db->select('staff.gender, sum(profession_tax) as total, count(*) as count');
        $this->db->from('payroll as p');
        $this->db->join('staff', 'p.staff_id = staff.id');
        $this->db->where('p.month', $month);
        $this->db->where('p.year', $year);
        $this->db->group_by('staff.gender, p.profession_tax');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getPayslipByHash($hash_id)
    {
        $this->db->where('hash_id', $hash_id);
        $query = $this->db->get('payroll');
        return $query->row_array();
        
    }

    public function getPayrollStatus()
    {
        $lastMonth = date("F", strtotime("-1 month"));
        $year = date("Y");

        $this->db->where('month', $lastMonth);
        $this->db->where('year', $year);
        $query = $this->db->get('payroll');
        return $query->row_array();
        
    }

    public function getBank_mst($id = null)
    {
        $this->db->select()->from('bank_master');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }  
        $this->db->where('status', 'active');
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function searchEmployeeForGroupDepart_special_report($month, $year, $emp_name, $role, $bank_id)
    {

        $date_month = date("m", strtotime($year));
        if (!empty($role) && !empty($emp_name)) {

            $query = $this->db->query("select staff_session.*, staff_payslip.status,
        IFNULL(staff_payslip.id, 0) as payslip_id ,staff.* ,roles.name as user_type ,staff_designation.designation as designation,department.department_name as department from staff left join staff_session on staff.id = staff_session.staff_id left join staff_payslip on staff.id = staff_payslip.staff_id and month = " . $this->db->escape($month) . " and year = " . $this->db->escape($year) . " left join department on department.id = staff.department left join staff_designation on staff_designation.id = staff.designation left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id where department.id = " . $this->db->escape($role) . " and name = " . $this->db->escape($emp_name) . " and staff_session.session_id = " . $this->db->escape($this->setting_model->getCurrentSession()) . " and staff.salary_to_bank = " . $this->db->escape($bank_id) . " and staff.is_active = 1 and staff.record_type = 1 order by basic_salary desc,date_of_joining");
        } else if (!empty($role)) {
            if($role<4)
            {
                $query = $this->db->query("select staff_session.*,staff_payslip.status,
                IFNULL(staff_payslip.id, 0) as payslip_id ,staff.*,staff_designation.designation as designation,department.department_name as department ,roles.name as user_type from staff left join staff_session on staff.id = staff_session.staff_id left join staff_payslip on staff.id = staff_payslip.staff_id and month = " . $this->db->escape($month) . " and year = " . $this->db->escape($year) . " left join department on department.id = staff.department left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id left join staff_designation on staff_designation.id = staff.designation where department.id = " . $this->db->escape($role) . " and staff_session.session_id = " . $this->db->escape($this->setting_model->getCurrentSession()) . " and staff.salary_to_bank = " . $this->db->escape($bank_id) . " and staff.is_active = 1  and staff.record_type = 1 order by basic_salary desc,date_of_joining");                
            }
            elseif($role==4)
            {
                $query = $this->db->query("select staff_session.*,staff_payslip.status,
                IFNULL(staff_payslip.id, 0) as payslip_id ,staff.*,staff_designation.designation as designation,department.department_name as department ,roles.name as user_type from staff left join staff_session on staff.id = staff_session.staff_id left join staff_payslip on staff.id = staff_payslip.staff_id and month = " . $this->db->escape($month) . " and year = " . $this->db->escape($year) . " left join department on department.id = staff.department left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id left join staff_designation on staff_designation.id = staff.designation where department.id = " . $this->db->escape($role) . " and staff_session.session_id = " . $this->db->escape($this->setting_model->getCurrentSession()) . " and staff.salary_to_bank = " . $this->db->escape($bank_id) . " and staff.contract_type != 'dailyWages' and staff.is_active = 1  and staff.record_type = 1 order by basic_salary desc,date_of_joining");                
            }
            elseif($role==5)
            {
                //echo "select staff_session.*,staff_payslip.status,
                //IFNULL(staff_payslip.id, 0) as payslip_id ,staff.*,staff_designation.designation as designation,department.department_name as department ,roles.name as user_type from staff left join staff_session on staff.id = staff_session.staff_id left join staff_payslip on staff.id = staff_payslip.staff_id left join department on department.id = staff.department left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id left join staff_designation on staff_designation.id = staff.designation where department.id = 4 and staff.contract_type = 'dailyWages' and staff.is_active = 1  and staff.record_type = 1 order by basic_salary desc,date_of_joining";die();
                $query = $this->db->query("select staff_session.*,staff_payslip.status,
                IFNULL(staff_payslip.id, 0) as payslip_id ,staff.*,staff_designation.designation as designation,department.department_name as department ,roles.name as user_type from staff left join staff_session on staff.id = staff_session.staff_id left join staff_payslip on staff.id = staff_payslip.staff_id left join department on department.id = staff.department left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id left join staff_designation on staff_designation.id = staff.designation where department.id = 4 and staff.contract_type = 'dailyWages' and staff.is_active = 1 and staff_session.session_id = " . $this->db->escape($this->setting_model->getCurrentSession()) . " and staff.salary_to_bank = " . $this->db->escape($bank_id) . "  and staff.record_type = 1 order by basic_salary desc,date_of_joining");                
            }
            
        } else {

            $query = $this->db->query("select staff_session.*,staff_payslip.status,
        IFNULL(staff_payslip.id, 0) as payslip_id ,staff.* ,roles.name as user_type ,staff_designation.designation as designation,department.department_name as department  from staff left join staff_session on staff.id = staff_session.staff_id left join staff_payslip on staff.id = staff_payslip.staff_id and month = " . $this->db->escape($month) . " and year = " . $this->db->escape($year) . " left join department on department.id = staff.department left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id left join staff_designation on staff_designation.id = staff.designation where staff.is_active = 1 and staff.record_type = 1 and staff_session.session_id = " . $this->db->escape($this->setting_model->getCurrentSession()) . " and staff.salary_to_bank = " . $this->db->escape($bank_id) . " order by basic_salary desc,date_of_joining");
        }
        //echo $this->db->last_query();die();
        return $query->result_array();
    } 

    public function searchStaff($department, $payroll_category_id, $contract_type)
    {
        $this->db->select('staff.id,staff.name,staff.surname,staff.employee_id,
        staff.bank_account_no,
        staff_designation.designation,department.department_name,
        staff.contract_type,staff.date_of_joining,
        payroll_category.category_name,staff_session.scale_of_pay,
        staff.basic_salary,staff_session.basic_pay,staff_session.gp,staff_session.ta');
        $this->db->from('staff');
        $this->db->join('staff_designation', 'staff.designation = staff_designation.id');
        $this->db->join('department', 'staff.department = department.id');
        $this->db->join('staff_session', 'staff.id = staff_session.staff_id');
        $this->db->join('payroll_category', 'staff.payroll_category_id = payroll_category.id');
        $this->db->where('staff_session.session_id', $this->current_session);
        
        if ($department != "") {
            $this->db->where('staff.department', $department);
        }
        if ($payroll_category_id != "") {
            $this->db->where('staff.payroll_category_id', $payroll_category_id);
        }
        if ($contract_type != "") {
            $this->db->where('staff.contract_type', $contract_type);
        }
        $query = $this->db->get();
        return $query->result_array();
        
    }

    public function getStaffDetails($month, $year, $role = null, $emp_name = null,$payroll_category_id = null)
{
    $this->db->select('staff_session.*, staff_payslip.status, IFNULL(staff_payslip.id, 0) as payslip_id, staff.*, roles.name as user_type, staff_designation.designation as designation, department.department_name as department');
    $this->db->from('staff');
    $this->db->join('staff_session', 'staff.id = staff_session.staff_id', 'left');
    $this->db->join('staff_payslip', 'staff.id = staff_payslip.staff_id AND month = ' . $this->db->escape($month) . ' AND year = ' . $this->db->escape($year), 'left');
    $this->db->join('department', 'department.id = staff.department', 'left');
    $this->db->join('staff_designation', 'staff_designation.id = staff.designation', 'left');
    $this->db->join('staff_roles', 'staff_roles.staff_id = staff.id', 'left');
    $this->db->join('roles', 'staff_roles.role_id = roles.id', 'left');

    if (!empty($role)) {
        $this->db->where('department.id', $role);

        if ($role == 4) {
            $this->db->where('staff.contract_type !=', 'dailyWages');
        } elseif ($role == 5) {
            $this->db->where('staff.contract_type', 'dailyWages');
        }
    }

    if (!empty($emp_name)) {
        $this->db->where('staff.name', $emp_name);
    }
    if (!empty($payroll_category_id)) {
        if($payroll_category_id == "teaching-non-teaching")
        {
            $this->db->where('staff.payroll_category_id', 4);
            $this->db->or_where('staff.payroll_category_id', 5);
        }
        else{
            $this->db->where('staff.payroll_category_id', $payroll_category_id);
        }
    }

    // $this->db->where('staff.is_active', 1);
    $this->db->where('staff.record_type', 1);
    $this->db->order_by('staff.payroll_category_id', 'asc');
    $this->db->order_by('basic_salary', 'desc');
    $this->db->order_by('date_of_joining', 'desc');

    $query = $this->db->get();
    return $query->result_array();
}

}
