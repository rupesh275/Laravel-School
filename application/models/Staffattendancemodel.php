<?php

class Staffattendancemodel extends MY_Model {

    public $current_session;
    public $current_date;

    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
        $this->current_date = $this->setting_model->getDateYmd();
    }

    public function get($id = null) {
        $this->db->select()->join("staff", "staff.id = staff_attendance.staff_id")->from('staff_attendance');
        $this->db->where("staff.is_active", 1);
        if ($id != null) {
            $this->db->where('staff_attendance.id', $id);
        } else {
            $this->db->order_by('staff_attendance.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getUserType() {

        $query = $this->db->query("select distinct user_type from staff where is_active = 1");

        return $query->result_array();
    }

    public function searchAttendenceUserType($user_type, $date) {

        if ($user_type == "select") {

            $query = $this->db->query("select staff_attendance.id, staff_attendance.staff_attendance_type_id,staff_attendance.remark,staff.name,staff.surname,staff.employee_id,staff.contact_no,staff.email,roles.name as user_type,IFNULL(staff_attendance.date, 'xxx') as date,staff.id as staff_id from staff left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id left join staff_attendance on (staff.id = staff_attendance.staff_id) and staff_attendance.date = " . $this->db->escape($date) . " where staff.is_active = 1");
        } else {

            $query = $this->db->query("select staff_attendance.staff_attendance_type_id,staff_attendance.remark,staff.name,staff.surname,staff.employee_id,staff.contact_no,staff.email,roles.name as user_type,IFNULL(staff_attendance.date, 'xxx') as date, IFNULL(staff_attendance.id, 0) as id, staff.id as staff_id from staff left join staff_roles on (staff.id = staff_roles.staff_id) left join roles on (roles.id = staff_roles.role_id) left join staff_attendance on (staff.id = staff_attendance.staff_id) and staff_attendance.date = " . $this->db->escape($date) . " where roles.name = " . $this->db->escape($user_type) . " and staff.is_active = 1");
        }
        return $query->result_array();
    }
    public function searchAttendenceUser($user_type) {

        $query = $this->db->query("select staff_attendance.staff_attendance_type_id,staff_attendance.remark,staff.name,staff.surname,staff.employee_id,staff.contact_no,staff.email,roles.name as user_type,IFNULL(staff_attendance.date, 'xxx') as date, IFNULL(staff_attendance.id, 0) as id, staff.id as staff_id from staff left join staff_roles on (staff.id = staff_roles.staff_id) left join roles on (roles.id = staff_roles.role_id) left join staff_attendance on (staff.id = staff_attendance.staff_id)  where roles.name = " . $this->db->escape($user_type) . " and staff.is_active = 1");
       
        return $query->result_array();
    }
    public function searchAttendencemonthly($user_type = null) {

        if ($user_type != null) {
            $query = $this->db->query("select staff_attendance.staff_attendance_type_id,staff_attendance.remark,staff.name,staff.surname,staff.employee_id,staff.payroll_category_id,staff.contact_no,staff.email,roles.name as user_type,IFNULL(staff_attendance.date, 'xxx') as date, IFNULL(staff_attendance.id, 0) as id, staff.id as staff_id from staff left join staff_roles on (staff.id = staff_roles.staff_id) left join roles on (roles.id = staff_roles.role_id) left join staff_attendance on (staff.id = staff_attendance.staff_id)  where roles.name = " . $this->db->escape($user_type) . " and staff.is_active = 1 and staff.record_type = 1");
        }else {
            $query = $this->db->query("select staff_attendance.staff_attendance_type_id,staff_attendance.remark,staff.name,staff.surname,staff.employee_id,staff.payroll_category_id,staff.contact_no,staff.email,roles.name as user_type,IFNULL(staff_attendance.date, 'xxx') as date, IFNULL(staff_attendance.id, 0) as id, staff.id as staff_id from staff left join staff_roles on (staff.id = staff_roles.staff_id) left join roles on (roles.id = staff_roles.role_id) left join staff_attendance on (staff.id = staff_attendance.staff_id)  where staff.id !=1 and  staff.is_active = 1 and staff.record_type = 1");
        }
       
        return $query->result_array();
    }

    public function add($data) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('staff_attendance', $data);
            $message = UPDATE_RECORD_CONSTANT . " On staff attendance id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('staff_attendance', $data);
            $id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On staff attendance id " . $id;
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

    public function getStaffAttendanceType() {

        $query = $this->db->select('*')->where("is_active", 'yes')->get("staff_attendance_type");

        return $query->result_array();
    }

    public function searchAttendanceReport($user_type, $date) {

        if ($user_type == "select") {

            $query = $this->db->query("select staff_attendance.staff_attendance_type_id,staff_attendance_type.type as `att_type`,staff_attendance_type.key_value as `key`,staff_attendance.remark,staff.name,staff.surname,staff.employee_id,staff.contact_no,staff.email,roles.name as user_type,IFNULL(staff_attendance.date, 'xxx') as date, IFNULL(staff_attendance.id, 0) as attendence_id, staff.id as id from staff left join staff_attendance on (staff.id = staff_attendance.staff_id) and staff_attendance.date = " . $this->db->escape($date) . " left join staff_attendance_type on staff_attendance_type.id = staff_attendance.staff_attendance_type_id left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id where staff.is_active = 1");
        } else {

            $query = $this->db->query("select staff_attendance.staff_attendance_type_id,staff_attendance_type.type as `att_type`,staff_attendance_type.key_value as `key`,staff_attendance.remark,staff.name,staff.surname,staff.employee_id,staff.contact_no,staff.email,roles.name as user_type,IFNULL(staff_attendance.date, 'xxx') as date, IFNULL(staff_attendance.id, 0) as attendence_id, staff.id as id from staff  left join staff_roles on (staff.id = staff_roles.staff_id) left join roles on (roles.id = staff_roles.role_id) left join staff_attendance on (staff.id = staff_attendance.staff_id) and staff_attendance.date = " . $this->db->escape($date) . " left join staff_attendance_type on staff_attendance_type.id = staff_attendance.staff_attendance_type_id  where roles.name = '" . $user_type . "' and staff.is_active = 1 ");
        }

        return $query->result_array();
    }

    public function attendanceYearCount() {

        $query = $this->db->select("distinct year(date) as year")->get("staff_attendance");

        return $query->result_array();
    }

    public function searchStaffattendance($date, $staff_id, $active_staff = true) {

        $sql = "select staff_attendance.staff_attendance_type_id,staff_attendance_type.type as `att_type`,staff_attendance_type.key_value as `key`,staff_attendance.remark,staff.name,staff.surname,staff.contact_no,staff.email,roles.name as user_type,IFNULL(staff_attendance.date, 'xxx') as date, IFNULL(staff_attendance.id, 0) as attendence_id, staff.id as id from staff left join staff_attendance on (staff.id = staff_attendance.staff_id) and staff_attendance.date = " . $this->db->escape($date) . " left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id left join staff_attendance_type on staff_attendance_type.id = staff_attendance.staff_attendance_type_id where staff.id = " . $this->db->escape($staff_id);
        if ($active_staff || !isset($active_staff)) {
            $sql .= " and staff.is_active = 1";
        }
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function addmonthattendence($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('staff_id', $data['staff_id']);
        $this->db->where('month', $data['month']);
        $this->db->where('year', $data['year']);
        $this->db->where('session_id', $data['session_id']);
        $this->db->order_by('id', 'desc');
        $q = $this->db->get('staff_att_montly');
        
        if ($q->num_rows() > 0) {
            $row = $q->row_array();
            $this->db->where('id', $row['id']);
            $this->db->where('month', $row['month']);
            $this->db->where('session_id', $row['session_id']);
            $this->db->update('staff_att_montly', $data);
            $message = UPDATE_RECORD_CONSTANT . " On   staff_att_montly " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('staff_att_montly', $data);
            $id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On  staff_att_montly " . $id;
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

    public function getMonthAttendence($staff_id,$selectedmonth,$selectedyear)
    {
        $this->db->select()->from('staff_att_montly');
        $this->db->where('staff_id', $staff_id);
        $this->db->where('month', $selectedmonth);
        $this->db->where('year', $selectedyear);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        return $query->row_array();
    }

    // function insert into staff_att_montly
    public function addmonthlyattendence($data)
    {
        $this->db->where('staff_id', $data['staff_id']);
        $this->db->where('month', $data['month']);
        $this->db->where('year', $data['year']);
        $monthlyRow = $this->db->get('staff_att_montly')->num_rows();

        if ($monthlyRow > 0) {
            $data['session_id'] = $this->current_session;
            $this->db->where('staff_id', $data['staff_id']);
            $this->db->where('month', $data['month']);
            $this->db->where('year', $data['year']);
            $this->db->update('staff_att_montly', $data);
        } else {
            $data['session_id'] = $this->current_session;
            $this->db->insert('staff_att_montly', $data);
        }
        
        
    }

    public function getMonthlyDays($selectedmonth)
    {
        
        $this->db->where('month', $selectedmonth);
        $this->db->where('session_id', $this->current_session);
        $query = $this->db->get('monthly_work_days_mst');
        
        return $query->row_array();
        
        
    }

    public function getStaffLeaveByCategoryId($payroll_category_id,$leave_type)
    {
        $this->db->select('qty');
        $this->db->where('leave_type', $leave_type);
        $this->db->where('payroll_category', $payroll_category_id);
        $this->db->where('status', 'active');
        $query =$this->db->get('leave_type_category_mst');
        $result = $query->row_array();

        // If no record is found or qty is explicitly 0, return 0
        if (empty($result) || $result['qty'] == 0) {
            return 0;
        } else {
            return $result['qty'];
        }
    }

    public function searchAttendencemonthlyByDepartment($department = null, $month_year = null) {
        ini_set('display_errors', 1);
        if ($department != null) {
            if($department == 5){
                $query = $this->db->query("SELECT 
                                sa.staff_attendance_type_id,
                                sa.remark,
                                s.name,
                                s.surname,
                                s.employee_id,
                                s.payroll_category_id,
                                s.contact_no,
                                s.email,
                                d.department_name AS user_type,
                                IFNULL(sa.date, 'xxx') AS date,
                                IFNULL(sa.id, 0) AS id,
                                s.id AS staff_id,
                                s.contract_type
                            FROM 
                                staff s
                            LEFT JOIN 
                                staff_roles sr ON s.id = sr.staff_id
                            LEFT JOIN 
                                department d ON d.id = s.department
                            LEFT JOIN 
                                staff_attendance sa ON s.id = sa.staff_id
                            WHERE 
                                d.id = 4
                                AND s.contract_type = 'dailyWages'
                                AND s.record_type = 1;");
            }else{
            $query = $this->db->query("SELECT 
                                sa.staff_attendance_type_id,
                                sa.remark,
                                s.name,
                                s.surname,
                                s.employee_id,
                                s.payroll_category_id,
                                s.contact_no,
                                s.email,
                                d.department_name AS user_type,
                                IFNULL(sa.date, 'xxx') AS date,
                                IFNULL(sa.id, 0) AS id,
                                s.id AS staff_id,
                                s.contract_type
                            FROM 
                                staff s
                            LEFT JOIN 
                                staff_roles sr ON s.id = sr.staff_id
                            LEFT JOIN 
                                department d ON d.id = s.department
                            LEFT JOIN 
                                staff_attendance sa ON s.id = sa.staff_id
                            WHERE 
                                d.id = $department
                                -- AND s.is_active = 1
                            --    and (s.is_active = 1 or (s.is_active=0 and s.salary_upto_month >= '" . $month_year . "'))
                                AND s.record_type = 1;");
            // $query = $this->db->query("select staff_attendance.staff_attendance_type_id,staff_attendance.remark,staff.name,staff.surname,staff.employee_id,staff.payroll_category_id,staff.contact_no,staff.email,department.department_name as user_type,IFNULL(staff_attendance.date, 'xxx') as date, IFNULL(staff_attendance.id, 0) as id, staff.id as staff_id from staff left join staff_roles on (staff.id = staff_roles.staff_id) left join department on (department.id = staff.department) left join staff_attendance on (staff.id = staff_attendance.staff_id)  where department.id = " . $this->db->escape($department) . " and staff.is_active = 1 and staff.record_type = 1");
            }
        }elseif($month_year != null){
            $query = $this->db->query("SELECT 
                                sa.staff_attendance_type_id,
                                sa.remark,
                                s.name,
                                s.surname,
                                s.employee_id,
                                s.payroll_category_id,
                                s.contact_no,
                                s.email,
                                d.department_name AS user_type,
                                IFNULL(sa.date, 'xxx') AS date,
                                IFNULL(sa.id, 0) AS id,
                                s.id AS staff_id,
                                s.contract_type
                            FROM 
                                staff s
                            LEFT JOIN 
                                staff_roles sr ON s.id = sr.staff_id
                            LEFT JOIN 
                                department d ON d.id = s.department
                            LEFT JOIN 
                                staff_attendance sa ON s.id = sa.staff_id
                            WHERE 
                                d.id = $department
                                -- AND s.is_active = 1
                               and (s.is_active = 1 or (s.is_active=0 and s.salary_upto_month >= '" . $month_year . "'))
                                AND s.record_type = 1;");
        }else {
            $query = $this->db->query("select staff_attendance.staff_attendance_type_id,staff_attendance.remark,staff.name,staff.surname,staff.employee_id,staff.payroll_category_id,staff.contact_no,staff.email,staff.contract_type,roles.name as user_type,IFNULL(staff_attendance.date, 'xxx') as date, IFNULL(staff_attendance.id, 0) as id, staff.id as staff_id from staff left join staff_roles on (staff.id = staff_roles.staff_id) left join roles on (roles.id = staff_roles.role_id) left join staff_attendance on (staff.id = staff_attendance.staff_id)  where staff.id !=1 and  staff.is_active = 1 and staff.record_type = 1");
        }
        if (!empty($query)) {
            
            return $query->result_array();
        }else {
            return array();
        }
        // echo $this->db->last_query();die;
    }

    public function getStaffLeaveRequestApproved($staff_id,$month,$year,$leave_type)
    {
        // echo "staff id : " . $staff_id . " month : " . $month . " year : " . $year . " leave type : " . $leave_type;die();
        $this->db->select('sum(days) as days');
        $this->db->join('staff_leave_request_sub', 'staff_leave_request_sub.leave_request_id = staff_leave_request.id');
        $this->db->where('staff_leave_request.staff_id', $staff_id);
        $this->db->where('MONTH(staff_leave_request_sub.from_date)', date('m',strtotime($month)));
        $this->db->where('YEAR(staff_leave_request_sub.from_date)',$year);
        $this->db->where('staff_leave_request_sub.leave_type_id', $leave_type);
        $this->db->where('staff_leave_request.status', 'approve');
        $query = $this->db->get('staff_leave_request')->row_array();
        // $this->db->select('sum(days) as days');
        // $this->db->where('approved_staff_leave_trn.staff_id', $staff_id);
        // $this->db->where('month', $month);
        // $this->db->where('year',$year);
        // $this->db->where('approved_staff_leave_trn.leave_type_id', $leave_type);
        // $query = $this->db->get('approved_staff_leave_trn')->row_array();
       
        if (empty($query['days'])) {
            return 0;
        } else {
            return $query['days'];
        }
    }



    public function getMonthAttendenceByStaffId($staff_id)
    {
        $this->db->select(
            'staff_att_montly.id,sum(staff_att_montly.ml) as ml,sum(staff_att_montly.cl) as cl,sum(staff_att_montly.el) as el,sum(staff_att_montly.lwp) as lwp,
            sum(staff_att_montly.sl) as sl,sum(staff_att_montly.comp_off) as comp_off'
        )->from('staff_att_montly');
        $this->db->where('staff_id', $staff_id);
        $this->db->where('session_id', $this->current_session);
        $this->db->order_by('id');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getStaffLeavebalanceByLeave($staff_id,$leave_type_id,$leave=0)
    {
        $this->db->select('payroll_category_id');
        $this->db->where('id', $staff_id);
        $staffRow = $this->db->get('staff')->row_array();
        if($leave == 0){
            $this->db->select('IFNULL(qty, 0) AS qty');
            $this->db->where('leave_type', $leave_type_id);
            $this->db->where('payroll_category', $staffRow['payroll_category_id']);
            $this->db->where('status', 'active');
            $leaveRow = $this->db->get('leave_type_category_mst')->row_array();
        }

        if (!empty($leaveRow)) {
            $qty = $leaveRow['qty'];
        }else {
            $qty = 0;
        }
        return $qty;
    }

    public function delete_monthly_attendence($month, $year)
    {
        $this->db->where('month', $month);
        $this->db->where('year', $year);
        $this->db->where('session_id', $this->current_session);
        $this->db->delete('staff_att_montly');
    }

    public function getstaffleaverequest($staff_id,$month,$year)
    {
        $this->db->select('staff_leave_request_sub.leave_type_id,leave_types.type ,SUM(staff_leave_request_sub.days) as total_days');
        $this->db->from('staff_leave_request');
        $this->db->join('staff_leave_request_sub', 'staff_leave_request_sub.leave_request_id = staff_leave_request.id');
        $this->db->join('leave_types', 'leave_types.id = staff_leave_request_sub.leave_type_id');
        $this->db->where('staff_leave_request.staff_id', $staff_id);
        $this->db->where('MONTH(staff_leave_request_sub.from_date)', date('m', strtotime($month)));
        $this->db->where('YEAR(staff_leave_request_sub.from_date)', $year);
        $this->db->where('staff_leave_request.status', 'approve');
        $this->db->group_by('staff_leave_request_sub.leave_type_id');
        $query = $this->db->get()->result_array();
        return $query;
    }
    public function getstaffleaverequest_new($staff_id,$month,$year)
    {
        $this->db->select('staff_leave_request_sub.from_date,staff_leave_request_sub.to_date,staff_leave_request_sub.leave_type_id,leave_types.type ,staff_leave_request_sub.days as total_days');
        $this->db->from('staff_leave_request');
        $this->db->join('staff_leave_request_sub', 'staff_leave_request_sub.leave_request_id = staff_leave_request.id','left');
        $this->db->join('leave_types', 'leave_types.id = staff_leave_request_sub.leave_type_id','left');
        $this->db->where('staff_leave_request.staff_id', $staff_id);
        $this->db->where('MONTH(staff_leave_request_sub.from_date)', date('m', strtotime($month)));
        $this->db->where('YEAR(staff_leave_request_sub.from_date)', $year);
        $this->db->where('staff_leave_request_sub.leave_type_id', 3);
        $this->db->where('staff_leave_request.status', 'approve');
        $this->db->group_by('staff_leave_request_sub.leave_type_id');
        $query = $this->db->get()->result_array();
        $lwp = 0;
        if (!empty($query)) {
            
            // echo "<pre>";
            // print_r ($query);
            // echo "</pre>";
            
            foreach ($query as $key => $value) {
                if ((date('Y-m', strtotime($value['from_date'])) == $year."-".date('m', strtotime($month))) && (date('Y-m', strtotime($value['to_date'])) == $year."-".date('m', strtotime($month)))) {
                    $lwp += $value['total_days'];
                }else if ((date('Y-m', strtotime($value['from_date'])) == $year."-".date('m', strtotime($month))) && (date('Y-m', strtotime($value['to_date'])) != $year."-".date('m', strtotime($month)))) {
                    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, date('m',  strtotime($value['from_date'])), $year);
                    // echo ($daysInMonth -  date('d',  strtotime($value['from_date']))) + 1;

                    $lwp += ($daysInMonth -  date('d',  strtotime($value['from_date']))) + 1;
                }else if ((date('Y-m', strtotime($value['from_date'])) != $year."-".date('m', strtotime($month))) && (date('Y-m', strtotime($value['to_date'])) == $year."-".date('m', strtotime($month)))) {
                    $days = date('d', strtotime($value['to_date']));
                    $lwp += $days;
                }
            }
        }
        $mainarray = array();
        $mainarray[]['total_days'] = $data['total_days'] = $lwp;
        
        return $mainarray;
    }

    public function addpercentdays($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (!empty($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('staff_percent_days', $data);
            $message = UPDATE_RECORD_CONSTANT . " On staff attendance id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('staff_percent_days', $data);
            $id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On staff attendance id " . $id;
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

    public function getStaff_percent_days($staff_id,$selectedmonth,$selectedyear)
    {
        $this->db->select()->from('staff_percent_days');
        $this->db->where('staff_id', $staff_id);
        $this->db->where('month', $selectedmonth);
        $this->db->where('year', $selectedyear);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function add_opening($arr)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        
       
        
        foreach ($arr as $key => $item) {
            if (empty($item['opening_id'])) {
                // Insert new record
                $insert_data = [
                    'staff_id' => $item['staff_id'],
                    'opening' => $item['opening'],
                    'session_id' => $item['session_id'],
                    'leave_type_id' => $item['leave_type_id']
                ];
                $this->db->insert('staff_leave_opening', $insert_data); // Insert into the table (adjust table name accordingly)
            } else {
                // Update existing record
                $update_data = [
                    'opening' => $item['opening'],
                    'session_id' => $item['session_id'],
                    'leave_type_id' => $item['leave_type_id']
                ];
                $this->db->where('id', $item['opening_id']);
                $this->db->update('staff_leave_opening', $update_data); // Update the record with the matching opening_id
                
            }
        }
        // echo "<pre>";
        // print_r ($arr);die;
        // echo "</pre>";

        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
    }
    
    public function getStaffLeaveOpening($staff_id,$leave_type_id)
    {
       $this->db->select('staff_leave_opening.id,staff_leave_opening.opening');
       $this->db->from('staff_leave_opening');
       $this->db->where('staff_id', $staff_id);
       $this->db->where('leave_type_id', $leave_type_id);
       $this->db->where('session_id', $this->current_session);
       $query = $this->db->get()->row_array();
       return $query;
    }
    
}
