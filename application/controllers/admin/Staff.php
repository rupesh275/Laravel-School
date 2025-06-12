<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Staff extends Admin_Controller
{
    public $sch_setting_detail = array();
    public function __construct()
    {
        parent::__construct();
        $this->config->load("payroll");
        $this->config->load("app-config");
        $this->load->library('Enc_lib');
        $this->load->library('mailsmsconf');
        $this->load->model("staff_model");
        $this->load->library('encoding_lib');
        $this->load->model("leaverequest_model");
        $this->contract_type      = $this->config->item('contracttype');
        $this->marital_status     = $this->config->item('marital_status');
        $this->staff_attendance   = $this->config->item('staffattendance');
        $this->payroll_status     = $this->config->item('payroll_status');
        $this->payment_mode       = $this->config->item('payment_mode');
        $this->status             = $this->config->item('status');
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function index()
    {

        if (!$this->rbac->hasPrivilege('staff', 'can_view')) {
            access_denied();
        }

        $data['title']  = 'Staff Search';
        $data['fields'] = $this->customfield_model->get_custom_fields('staff', 1);
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'HR/staff');
        $search             = $this->input->post("search");
        $resultlist         = $this->staff_model->searchFullText("", 1);
        $data['resultlist'] = $resultlist;
        $staffRole          = $this->staff_model->getStaffRole();
        $data["role"]       = $staffRole;
        $data["role_id"]    = "";
        $search_text        = $this->input->post('search_text');
        if (isset($search)) {
            if ($search == 'search_filter') {
                $this->form_validation->set_rules('role', $this->lang->line('role'), 'trim|required|xss_clean');
                if ($this->form_validation->run() == false) {
                    $data["resultlist"] = array();
                } else {
                    $data['searchby']    = "filter";
                    $role                = $this->input->post('role');
                    $data['employee_id'] = $this->input->post('empid');
                    $data["role_id"]     = $role;
                    $data['search_text'] = $this->input->post('search_text');
                    $resultlist          = $this->staff_model->getEmployee($role, 1);
                    $data['resultlist']  = $resultlist;
                }
            } else if ($search == 'search_full') {
                $data['searchby']    = "text";
                $data['search_text'] = trim($this->input->post('search_text'));
                $resultlist          = $this->staff_model->searchFullText($search_text, 1);

                $data['resultlist'] = $resultlist;
                $data['title']      = 'Search Details: ' . $data['search_text'];
            }
        }

        $this->load->view('layout/header');
        $this->load->view('admin/staff/staffsearch', $data);
        $this->load->view('layout/footer');
    }

    public function disablestafflist()
    {

        if (!$this->rbac->hasPrivilege('disable_staff', 'can_view')) {
            access_denied();
        }

        if (isset($_POST['role']) && $_POST['role'] != '') {
            $data['search_role'] = $_POST['role'];
        } else {
            $data['search_role'] = "";
        }

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'HR/staff/disablestafflist');
        $data['title'] = 'Staff Search';
        $staffRole     = $this->staff_model->getStaffRole();

        $data["role"]       = $staffRole;
        $search             = $this->input->post("search");
        $search_text        = $this->input->post('search_text');
        $resultlist         = $this->staff_model->searchFullText($search_text, 0);
        $data['resultlist'] = $resultlist;

        if (isset($search)) {
            if ($search == 'search_filter') {
                $this->form_validation->set_rules('role', $this->lang->line('role'), 'trim|required|xss_clean');
                if ($this->form_validation->run() == false) {
                    $resultlist         = array();
                    $data['resultlist'] = $resultlist;
                } else {
                    $data['searchby']    = "filter";
                    $role                = $this->input->post('role');
                    $data['employee_id'] = $this->input->post('empid');

                    $data['search_text'] = $this->input->post('search_text');
                    $resultlist          = $this->staff_model->getEmployee($role, 0);
                    $data['resultlist']  = $resultlist;
                }
            } else if ($search == 'search_full') {
                $data['searchby']    = "text";
                $data['search_text'] = trim($this->input->post('search_text'));
                $resultlist          = $this->staff_model->searchFullText($search_text, 0);
                $data['resultlist']  = $resultlist;
                $data['title']       = 'Search Details: ' . $data['search_text'];
            }
        }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/staff/disablestaff', $data);
        $this->load->view('layout/footer', $data);
    }

    public function profile($id)
    {
        $data['enable_disable'] = 1;
        if ($this->customlib->getStaffID() == $id) {
            $data['enable_disable'] = 0;
        } else if (!$this->rbac->hasPrivilege('staff', 'can_view')) {
            access_denied();
        }

        $this->load->model("staffattendancemodel");
        $this->load->model("setting_model");
        $data["id"]    = $id;
        $data['title'] = 'Staff Details';
        $staff_info    = $this->staff_model->getProfile($id);
        $userdata      = $this->customlib->getUserData();

        $userid          = $userdata['id'];
        $timeline_status = '';

        if ($userid == $id) {
            $timeline_status = 'yes';
        }

        $timeline_list         = $this->timeline_model->getStaffTimeline($id, $timeline_status);
        $data["timeline_list"] = $timeline_list;
        $staff_payroll         = $this->staff_model->getStaffPayroll($id);
        $staff_leaves          = $this->leaverequest_model->staff_leave_request($id);
        $alloted_leavetype     = $this->staff_model->allotedLeaveType($id);
        $data['sch_setting']   = $this->sch_setting_detail;

        $data['staffid_auto_insert'] = $this->sch_setting_detail->staffid_auto_insert;
        $this->load->model("payroll_model");
        $salary                      = $this->payroll_model->getSalaryDetails($id);
        $attendencetypes             = $this->staffattendancemodel->getStaffAttendanceType();
        $data['attendencetypeslist'] = $attendencetypes;
        $i                           = 0;
        $leaveDetail                 = array();
        foreach ($alloted_leavetype as $key => $value) {
            $count_leaves[]                   = $this->leaverequest_model->countLeavesData($id, $value["leave_type_id"]);
            $leaveDetail[$i]['type']          = $value["type"];
            $leaveDetail[$i]['alloted_leave'] = $value["alloted_leave"];
            $leaveDetail[$i]['approve_leave'] = $count_leaves[$i]['approve_leave'];
            $i++;
        }
        $data["leavedetails"]  = $leaveDetail;
        $data["staff_leaves"]  = $staff_leaves;
        $data['staff_doc_id']  = $id;
        $data['staff']         = $staff_info;
        $data['staff_payroll'] = $staff_payroll;
        $data['salary']        = $salary;

        $monthlist             = $this->customlib->getMonthDropdown();
        $startMonth            = $this->setting_model->getStartMonth();
        $data["monthlist"]     = $monthlist;
        $data['yearlist']      = $this->staffattendancemodel->attendanceYearCount();
        $session_current       = $this->setting_model->getCurrentSessionName();
        $startMonth            = $this->setting_model->getStartMonth();
        $centenary             = substr($session_current, 0, 2); //2017-18 to 2017
        $year_first_substring  = substr($session_current, 2, 2); //2017-18 to 2017
        $year_second_substring = substr($session_current, 5, 2); //2017-18 to 18
        $month_number          = date("m", strtotime($startMonth));
        $data['rate_canview']  = 0;

        if ($id != '1') {
            $staff_rating = $this->staff_model->staff_ratingById($id);

            if ($staff_rating['total'] >= 3) {
                $data['rate'] = ($staff_rating['rate'] / $staff_rating['total']);

                $data['rate_canview'] = 1;
            }
            $data['reviews'] = $staff_rating['total'];
        }

        $data['reviews_comment'] = $this->staff_model->staff_ratingById($id);

        $year = date("Y");

        $staff_list              = $this->staff_model->user_reviewlist($id);
        $data['user_reviewlist'] = $staff_list;

        $attendence_count = array();
        $attendencetypes  = $this->attendencetype_model->getStaffAttendanceType();
        foreach ($attendencetypes as $att_key => $att_value) {
            $attendence_count[$att_value['type']] = array();
        }

        foreach ($monthlist as $key => $value) {
            $datemonth       = date("m", strtotime($value));
            $date_each_month = date('Y-' . $datemonth . '-01');

            $date_start = date('01', strtotime($date_each_month));
            $date_end   = date('t', strtotime($date_each_month));
            for ($n = $date_start; $n <= $date_end; $n++) {
                $att_dates        = $year . "-" . $datemonth . "-" . sprintf("%02d", $n);
                $date_array[]     = $att_dates;
                $staff_attendence = $this->staffattendancemodel->searchStaffattendance($att_dates, $id, false);
                if (!empty($staff_attendence)) {
                    if ($staff_attendence['att_type'] != "") {
                        $attendence_count[$staff_attendence['att_type']][] = 1;
                    }
                } else {
                }
                $res[$att_dates] = $staff_attendence;
            }
        }

        $session = $this->setting_model->getCurrentSessionName();

        $session_start = explode("-", $session);
        $start_year    = $session_start[0];

        $date    = $start_year . "-" . $startMonth;
        $newdate = date("Y-m-d", strtotime($date . "+1 month"));

        $data["countAttendance"] = $attendence_count;
        $data["resultlist"]       = $res;
        $data["attendence_array"] = range(01, 31);
        $data["date_array"]       = $date_array;
        $data["payroll_status"]   = $this->payroll_status;
        $data["payment_mode"]     = $this->payment_mode;
        $data["contract_type"]    = $this->contract_type;
        $data["status"]           = $this->status;
        $roles                    = $this->role_model->get();
        $data["roles"]            = $roles;
        $stafflist                = $this->staff_model->get();
        $data['stafflist']        = $stafflist;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/staff/staffprofile', $data);
        $this->load->view('layout/footer', $data);
    }

    public function countAttendance($st_month, $no_of_months, $emp)
    {

        $record = array();
        for ($i = 1; $i <= 1; $i++) {

            $r     = array();
            $month = date('m', strtotime($st_month . " -$i month"));
            $year  = date('Y', strtotime($st_month . " -$i month"));

            foreach ($this->staff_attendance as $att_key => $att_value) {

                $s = $this->staff_model->count_attendance($year, $emp, $att_value);

                $r[$att_key] = $s;
            }

            $record[$year] = $r;
        }

        return $record;
    }

    public function getSession()
    {
        $session             = $this->session_model->getAllSession();
        $data                = array();
        $session_array       = $this->session->has_userdata('session_array');
        $data['sessionData'] = array('session_id' => 0);
        if ($session_array) {
            $data['sessionData'] = $this->session->userdata('session_array');
        } else {
            $setting = $this->setting_model->get();

            $data['sessionData'] = array('session_id' => $setting[0]['session_id']);
        }
        $data['sessionList'] = $session;

        return $data;
    }

    public function getSessionMonthDropdown()
    {
        $startMonth = $this->setting_model->getStartMonth();
        $array      = array();
        for ($m = $startMonth; $m <= $startMonth + 11; $m++) {
            $month         = date('F', mktime(0, 0, 0, $m, 1, date('Y')));
            $array[$month] = $month;
        }
        return $array;
    }

    public function download($staff_id, $doc)
    {
        $this->load->helper('download');
        $filepath = "./uploads/staff_documents/$staff_id/" . urldecode($this->uri->segment(5));
        $data     = file_get_contents($filepath);
        $name     = $this->uri->segment(5);
        force_download($name, $data);
    }

    public function doc_delete($id, $doc, $file)
    {
        $this->staff_model->doc_delete($id, $doc, $file);
        $this->session->set_flashdata('msg', '<i class="fa fa-check-square-o" aria-hidden="true"></i>' . $this->lang->line('delete_message') . '');
        redirect('admin/staff/profile/' . $id);
    }

    public function ajax_attendance($id)
    {
        $this->load->model("staffattendancemodel");
        $attendencetypes             = $this->staffattendancemodel->getStaffAttendanceType();
        $data['attendencetypeslist'] = $attendencetypes;
        $year                        = $this->input->post("year");
        $data["year"]                = $year;
        if (!empty($year)) {

            $monthlist         = $this->customlib->getMonthDropdown();
            $startMonth        = $this->setting_model->getStartMonth();
            $data["monthlist"] = $monthlist;
            $data['yearlist']  = $this->staffattendancemodel->attendanceYearCount();
            $session_current   = $this->setting_model->getCurrentSessionName();
            $startMonth        = $this->setting_model->getStartMonth();

            foreach ($monthlist as $key => $value) {
                $datemonth       = date("m", strtotime($value));
                $date_each_month = date('Y-' . $datemonth . '-01');
                $date_end        = date('t', strtotime($date_each_month));
                for ($n = 1; $n <= $date_end; $n++) {
                    $att_date           = sprintf("%02d", $n);
                    $attendence_array[] = $att_date;
                    $datemonth          = date("m", strtotime($value));
                    $att_dates          = $year . "-" . $datemonth . "-" . sprintf("%02d", $n);

                    $date_array[]    = $att_dates;
                    $res[$att_dates] = $this->staffattendancemodel->searchStaffattendance($att_dates, $id);
                }
            }
            $date    = $year . "-" . $startMonth;
            $newdate = date("Y-m-d", strtotime($date . "+1 month"));

            $countAttendance          = $this->countAttendance($year, $startMonth, $id);
            $data["countAttendance"]  = $countAttendance;
            $data["id"]               = $id;
            $data["resultlist"]       = $res;
            $data["attendence_array"] = $attendence_array;
            $data["date_array"]       = $date_array;

            $this->load->view("admin/staff/ajaxattendance", $data);
        } else {

            echo "No Record Found";
        }
    }

    public function create()
    {
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/staff');
        $roles                  = $this->role_model->get();
        $data["roles"]          = $roles;
        $genderList             = $this->customlib->getGender();
        $data['genderList']     = $genderList;
        $payscaleList           = $this->staff_model->getPayroll();
        $leavetypeList          = $this->staff_model->getLeaveType();
        $data["leavetypeList"]  = $leavetypeList;
        $data["payscaleList"]   = $payscaleList;
        $designation            = $this->staff_model->getStaffDesignation();
        $data["designation"]    = $designation;
        $department             = $this->staff_model->getDepartment();
        $data["department"]     = $department;
        $marital_status         = $this->marital_status;
        $data["marital_status"] = $marital_status;

        $data['title']               = 'Add Staff';
        $data["contract_type"]       = $this->contract_type;
        $data['sch_setting']         = $this->sch_setting_detail;
        $data['staffid_auto_insert'] = $this->sch_setting_detail->staffid_auto_insert;
        $custom_fields               = $this->customfield_model->getByBelong('staff');
        foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
            if ($custom_fields_value['validation']) {
                $custom_fields_id   = $custom_fields_value['id'];
                $custom_fields_name = $custom_fields_value['name'];
                $this->form_validation->set_rules("custom_fields[staff][" . $custom_fields_id . "]", $custom_fields_name, 'trim|required');
            }
        }

        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('role', $this->lang->line('role'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('gender', $this->lang->line('gender'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('dob', $this->lang->line('date_of_birth'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_upload');
        $this->form_validation->set_rules('first_doc', $this->lang->line('image'), 'callback_handle_first_upload');
        $this->form_validation->set_rules('second_doc', $this->lang->line('image'), 'callback_handle_second_upload');
        $this->form_validation->set_rules('third_doc', $this->lang->line('image'), 'callback_handle_third_upload');
        $this->form_validation->set_rules('fourth_doc', $this->lang->line('image'), 'callback_handle_fourth_upload');
        $this->form_validation->set_rules(
            'email',
            $this->lang->line('email'),
            array(
                'required', 'valid_email',
                array('check_exists', array($this->staff_model, 'valid_email_id')),
            )
        );
        if (!$this->sch_setting_detail->staffid_auto_insert) {

            $this->form_validation->set_rules('employee_id', $this->lang->line('staff_id'), 'callback_username_check');
        }

        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_upload');

        if ($this->form_validation->run() == true) {
            

            $custom_field_post  = $this->input->post("custom_fields[staff]");
            $custom_value_array = array();
            if (!empty($custom_fields)) {
                foreach ($custom_field_post as $key => $value) {
                    $check_field_type = $this->input->post("custom_fields[staff][" . $key . "]");
                    $field_value      = is_array($check_field_type) ? implode(",", $check_field_type) : $check_field_type;
                    $array_custom     = array(
                        'belong_table_id' => 0,
                        'custom_field_id' => $key,
                        'field_value'     => $field_value,
                    );
                    $custom_value_array[] = $array_custom;
                }
            }

            $employee_id       = $this->input->post("employee_id");
            $department        = $this->input->post("department");
            $designation       = $this->input->post("designation");
            $role              = $this->input->post("role");
            $name              = $this->input->post("name");
            $gender            = $this->input->post("gender");
            $marital_status    = $this->input->post("marital_status");
            $dob               = $this->input->post("dob");
            $contact_no        = $this->input->post("contactno");
            $emergency_no      = $this->input->post("emergency_no");
            $email             = $this->input->post("email");
            $date_of_joining   = $this->input->post("date_of_joining");
            $date_of_leaving   = $this->input->post("date_of_leaving");
            $address           = $this->input->post("address");
            $qualification     = $this->input->post("qualification");
            $work_exp          = $this->input->post("work_exp");
            $basic_salary      = $this->input->post('basic_salary');
            $account_title     = $this->input->post("account_title");
            $bank_account_no   = $this->input->post("bank_account_no");
            $bank_name         = $this->input->post("bank_name");
            $ifsc_code         = $this->input->post("ifsc_code");
            $bank_branch       = $this->input->post("bank_branch");
            $contract_type     = $this->input->post("contract_type");
            $shift             = $this->input->post("shift");
            $location          = $this->input->post("location");
            $leave             = $this->input->post("leave");
            $facebook          = $this->input->post("facebook");
            $twitter           = $this->input->post("twitter");
            $linkedin          = $this->input->post("linkedin");
            $instagram         = $this->input->post("instagram");
            $permanent_address = $this->input->post("permanent_address");
            $father_name       = $this->input->post("father_name");
            $surname           = $this->input->post("surname");
            $mother_name       = $this->input->post("mother_name");
            $note              = $this->input->post("note");
            $epf_no            = $this->input->post("epf_no");
            $seniority_id      = $this->input->post("seniority_id");
            $blood_group       = $this->input->post("blood_group");
            $aadhar_no         = $this->input->post("aadhar_no");
            $pan_no            = $this->input->post("pan_no");
            $biometric_id      = $this->input->post("biometric_id");
            $middle_name       = $this->input->post("middle_name");
            $date_of_confirmation      = $this->input->post("date_of_confirmation");
            $date_of_retirement      = $this->input->post("date_of_retirement");
            $lefton             = $this->input->post("lefton");
            $left_reason        = $this->input->post("left_reason");
            $grade              = $this->input->post("grade");
            $spouse_name        = $this->input->post("spouse_name");
            $residence          = $this->input->post("residence");
            $cast               = $this->input->post("cast");
            $religion           = $this->input->post("religion");
            $subcaste           = $this->input->post("subcaste");
            $remarks            = $this->input->post("remarks");
            $current_city       = $this->input->post("current_city");
            $current_state      = $this->input->post("current_state");
            $current_country    = $this->input->post("current_country");
            $current_pincode    = $this->input->post("current_pincode");
            $permanent_city     = $this->input->post("permanent_city");
            $permanent_state    = $this->input->post("permanent_state");
            $permanent_country  = $this->input->post("permanent_country");
            $permanent_pincode  = $this->input->post("permanent_pincode");
            $mobile2            = $this->input->post("mobile2");
            $phone2             = $this->input->post("phone2");
            $uan_no             = $this->input->post("uan_no");
            $pf_exempted        = $this->input->post("pf_exempted");
            $it_scheme          = $this->input->post("it_scheme");
            $pt_exempted        = $this->input->post("pt_exempted");
            $dcps_no            = $this->input->post("dcps_no");
            $passport_no        = $this->input->post("passport_no");
            $place_of_issue     = $this->input->post("place_of_issue");
            $date_of_issue      = $this->input->post("date_of_issue");
            $date_of_expiry     = $this->input->post("date_of_expiry");
            $esi_no             = $this->input->post("esi_no");
            $esi_dispensary     = $this->input->post("esi_dispensary");
            $esi_exempted       = $this->input->post("esi_exempted");
            $lwf_applicable     = $this->input->post("lwf_applicable");
            $lwf_grade          = $this->input->post("lwf_grade");
            $gratuity_no        = $this->input->post("gratuity_no");
            $increment_month    = $this->input->post("increment_month");
            $increment_amount   = $this->input->post("increment_amount");
            $pay_group          = $this->input->post("pay_group");
            $record_type          = $this->input->post("record_type");

            // $password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
            $password = 1234;

            $data_insert = array(
                'password'        => $this->enc_lib->passHashEnc($password),
                'employee_id'     => $employee_id,
                'name'            => $name,
                'middle_name'      => $middle_name,
                'email'           => $email,
                'dob'             => date('Y-m-d', $this->customlib->datetostrtotime($dob)),
                'gender'          => $gender,
                'payscale'        => '',
                'is_active'       => 1,
                'record_type'     => $record_type,
            );

            if (isset($surname)) {

                $data_insert['surname'] = $surname;
            }
            if (isset($department)) {

                $data_insert['department'] = $department;
            }

            if (isset($designation)) {

                $data_insert['designation'] = $designation;
            }

            if (isset($mother_name)) {

                $data_insert['mother_name'] = $mother_name;
            }

            if (isset($father_name)) {

                $data_insert['father_name'] = $father_name;
            }

            if (isset($contact_no)) {

                $data_insert['contact_no'] = $contact_no;
            }

            if (isset($emergency_no)) {

                $data_insert['emergency_contact_no'] = $emergency_no;
            }

            if (isset($marital_status)) {

                $data_insert['marital_status'] = $marital_status;
            }

            if (isset($address)) {

                $data_insert['local_address'] = $address;
            }

            if (isset($permanent_address)) {

                $data_insert['permanent_address'] = $permanent_address;
            }

            if (isset($qualification)) {

                $data_insert['qualification'] = $qualification;
            }

            if (isset($work_exp)) {

                $data_insert['work_exp'] = $work_exp;
            }

            if (isset($note)) {

                $data_insert['note'] = $note;
            }

            if (isset($epf_no)) {

                $data_insert['epf_no'] = $epf_no;
            }

            if (isset($basic_salary)) {

                $data_insert['basic_salary'] = $basic_salary;
            }

            if (isset($contract_type)) {

                $data_insert['contract_type'] = $contract_type;
            }

            if (isset($shift)) {

                $data_insert['shift'] = $shift;
            }

            if (isset($location)) {

                $data_insert['location'] = $location;
            }

            if (isset($bank_account_no)) {

                $data_insert['bank_account_no'] = $bank_account_no;
            }

            if (isset($bank_name)) {

                $data_insert['bank_name'] = $bank_name;
            }

            if (isset($account_title)) {

                $data_insert['account_title'] = $account_title;
            }

            if (isset($ifsc_code)) {

                $data_insert['ifsc_code'] = $ifsc_code;
            }

            if (isset($bank_branch)) {

                $data_insert['bank_branch'] = $bank_branch;
            }

            if (isset($facebook)) {

                $data_insert['facebook'] = $facebook;
            }

            if (isset($twitter)) {

                $data_insert['twitter'] = $twitter;
            }

            if (isset($linkedin)) {

                $data_insert['linkedin'] = $linkedin;
            }

            if (isset($instagram)) {

                $data_insert['instagram'] = $instagram;
            }

            if ($date_of_leaving != "") {
                $data_insert['date_of_leaving'] = date('Y-m-d', $this->customlib->datetostrtotime($date_of_leaving));
            }
            if ($date_of_joining != "") {
                $data_insert['date_of_joining'] = date('Y-m-d', $this->customlib->datetostrtotime($date_of_joining));
            }
            if (isset($seniority_id)) {

                $data_insert['seniority_id'] = $seniority_id;
            }
            if (isset($blood_group)) {

                $data_insert['blood_group'] = $blood_group;
            }
            if (isset($aadhar_no)) {

                $data_insert['aadhar_no'] = $aadhar_no;
            }
            if (isset($pan_no)) {

                $data_insert['pan_no'] = $pan_no;
            }
            if (isset($biometric_id)) {

                $data_insert['biometric_id'] = $biometric_id;
            }
            $leave_type  = $this->input->post('leave_type');
            $leave_array = array();
            if (!empty($leave_array)) {
                foreach ($leave_type as $leave_key => $leave_value) {
                    $leave_array[] = array(
                        'staff_id'      => 0,
                        'leave_type_id' => $leave_value,
                        'alloted_leave' => $this->input->post('alloted_leave_' . $leave_value),
                    );
                }
            }
            $role_array = array('role_id' => $this->input->post('role'), 'staff_id' => 0);
            //==========================
            $insert                                = true;
            $data_setting                          = array();
            $data_setting['id']                    = $this->sch_setting_detail->id;
            $data_setting['staffid_auto_insert']   = $this->sch_setting_detail->staffid_auto_insert;
            $data_setting['staffid_update_status'] = $this->sch_setting_detail->staffid_update_status;
            $employee_id                           = 0;

            if ($this->sch_setting_detail->staffid_auto_insert) {
                if ($this->sch_setting_detail->staffid_update_status) {

                    $employee_id = $this->sch_setting_detail->staffid_prefix . $this->sch_setting_detail->staffid_start_from;

                    $last_student = $this->staff_model->lastRecord();

                    $last_admission_digit = str_replace($this->sch_setting_detail->staffid_prefix, "", $last_student->employee_id);

                    $employee_id                = $this->sch_setting_detail->staffid_prefix . sprintf("%0" . $this->sch_setting_detail->staffid_no_digit . "d", $last_admission_digit + 1);
                    $data_insert['employee_id'] = $employee_id;
                } else {
                    $employee_id                = $this->sch_setting_detail->staffid_prefix . $this->sch_setting_detail->staffid_start_from;
                    $data_insert['employee_id'] = $employee_id;
                }

                $employee_id_exists = $this->staff_model->check_staffid_exists($employee_id);
                if ($employee_id_exists) {
                    $insert = false;
                }
            } else {

                $data_insert['employee_id'] = $this->input->post('employee_id');
            }
            //==========================
            if ($insert) {

                $insert_id = $this->staff_model->batchInsert($data_insert, $role_array, $leave_array, $data_setting);

                $scale_of_pay          = $this->input->post("scale_of_pay");
                $basic_pay             = $this->input->post("basic_pay");
                $gp                    = $this->input->post("gp");
                $da                    = $this->input->post("da");
                $hra                   = $this->input->post("hra");
                $ta                    = $this->input->post("ta");
                $other_allowance       = $this->input->post("other_allowance");
                $pf                    = $this->input->post("pf");
                $profession_tax        = $this->input->post("profession_tax");
                $personal_profit        = $this->input->post("personal_profit");
                $income_tax            = $this->input->post("income_tax");
                $session_current       = $this->setting_model->getCurrentSession();
                
                $dataArray =array(
                    'staff_id'          => $insert_id,
                    'session_id'        => $session_current,
                    'scale_of_pay'      => $scale_of_pay,
                    'basic_pay'         => $basic_pay,
                    'gp'                => $gp,
                    'da'                => $da,
                    'hra'               => $hra,
                    'ta'                => $ta,
                    'other_allowance'   => $other_allowance,
                    'pf'                => $pf,
                    'profession_tax'    => $profession_tax,
                    'personal_profit'    => $personal_profit,
                    'income_tax'        => $income_tax,
                );
    
               $this->staff_model->add_staff_session($dataArray);

               $date_of_confirmation = !empty($date_of_confirmation) ? date('Y-m-d',strtotime($date_of_confirmation)) : "" ;
            $date_of_retirement = !empty($date_of_retirement) ? date('Y-m-d',strtotime($date_of_retirement)) : "" ;
            $lefton = !empty($lefton) ? date('Y-m-d',strtotime($lefton)) : "" ;
            $date_of_issue = !empty($date_of_issue) ? date('Y-m-d',strtotime($date_of_issue)) : "" ;
            $date_of_expiry = !empty($date_of_expiry) ? date('Y-m-d',strtotime($date_of_expiry)) : "" ;
               $arraySub = array(
                'staff_id'                  => $insert_id,
                'date_of_confirmation'      => $date_of_confirmation,
                'date_of_retirement'        => $date_of_retirement,
                'lefton'             => $lefton,
                'left_reason'        => $left_reason,
                'grade'              => $grade,
                'spouse_name'        => $spouse_name,
                'residence'          => $residence,
                'cast'               => $cast,
                'religion'           => $religion,
                'subcaste'           => $subcaste,
                'remarks'            => $remarks,
                'current_city'       => $current_city,
                'current_state'      => $current_state,
                'current_country'    => $current_country,
                'current_pincode'    => $current_pincode,
                'permanent_city'     => $permanent_city,
                'permanent_state'    => $permanent_state,
                'permanent_country'  => $permanent_country,
                'permanent_pincode'  => $permanent_pincode,
                'mobile2'            => $mobile2,
                'phone2'             => $phone2,
                'uan_no'             => $uan_no,
                'pf_exempted'        => $pf_exempted,
                'it_scheme'          => $it_scheme,
                'pt_exempted'        => $pt_exempted,
                'dcps_no'            => $dcps_no,
                'passport_no'        => $passport_no,
                'place_of_issue'     => $place_of_issue,
                'date_of_issue'      => $date_of_issue,
                'date_of_expiry'     => $date_of_expiry,
                'esi_no'             => $esi_no,
                'esi_dispensary'     => $esi_dispensary,
                'esi_exempted'       => $esi_exempted,
                'lwf_applicable'     => $lwf_applicable,
                'lwf_grade'          => $lwf_grade,
                'gratuity_no'        => $gratuity_no,
                'increment_month'    => $increment_month,
                'increment_amount'   => $increment_amount,
                'pay_group'          => $pay_group,
                );

            $this->staff_model->addStaff_sub($arraySub);
                $staff_id  = $insert_id;
                $data = array(
                    'member_type' => 'teacher',
                    'member_id' => $insert_id,
                    'library_card_no' => null,
                );

                $inserted_id = $this->librarymanagement_model->add($data);
                if (!empty($custom_value_array)) {
                    $this->customfield_model->insertRecord($custom_value_array, $insert_id);
                }
                if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                    $fileInfo = pathinfo($_FILES["file"]["name"]);
                    $img_name = $insert_id . '.' . $fileInfo['extension'];
                    move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/staff_images/" . $img_name);
                    $data_img = array('id' => $staff_id, 'image' => $img_name);
                    $this->staff_model->add($data_img);
                }

                if (isset($_FILES["first_doc"]) && !empty($_FILES['first_doc']['name'])) {
                    $uploaddir = './uploads/staff_documents/' . $staff_id . '/';
                    if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                        die("Error creating folder $uploaddir");
                    }
                    $fileInfo    = pathinfo($_FILES["first_doc"]["name"]);
                    $first_title = 'resume';
                    $filename    = "resume" . $staff_id . '.' . $fileInfo['extension'];
                    $img_name    = $uploaddir . $filename;
                    $resume      = $filename;
                    move_uploaded_file($_FILES["first_doc"]["tmp_name"], $img_name);
                } else {

                    $resume = "";
                }

                if (isset($_FILES["second_doc"]) && !empty($_FILES['second_doc']['name'])) {
                    $uploaddir = './uploads/staff_documents/' . $insert_id . '/';
                    if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                        die("Error creating folder $uploaddir");
                    }
                    $fileInfo       = pathinfo($_FILES["second_doc"]["name"]);
                    $first_title    = 'joining_letter';
                    $filename       = "joining_letter" . $staff_id . '.' . $fileInfo['extension'];
                    $img_name       = $uploaddir . $filename;
                    $joining_letter = $filename;
                    move_uploaded_file($_FILES["second_doc"]["tmp_name"], $img_name);
                } else {

                    $joining_letter = "";
                }

                if (isset($_FILES["third_doc"]) && !empty($_FILES['third_doc']['name'])) {
                    $uploaddir = './uploads/staff_documents/' . $insert_id . '/';
                    if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                        die("Error creating folder $uploaddir");
                    }
                    $fileInfo           = pathinfo($_FILES["third_doc"]["name"]);
                    $first_title        = 'resignation_letter';
                    $filename           = "resignation_letter" . $staff_id . '.' . $fileInfo['extension'];
                    $img_name           = $uploaddir . $filename;
                    $resignation_letter = $filename;
                    move_uploaded_file($_FILES["third_doc"]["tmp_name"], $img_name);
                } else {

                    $resignation_letter = "";
                }
                if (isset($_FILES["fourth_doc"]) && !empty($_FILES['fourth_doc']['name'])) {
                    $uploaddir = './uploads/staff_documents/' . $insert_id . '/';
                    if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                        die("Error creating folder $uploaddir");
                    }
                    $fileInfo     = pathinfo($_FILES["fourth_doc"]["name"]);
                    $fourth_title = 'uploads/staff_images/' . 'Other Doucment';
                    $fourth_doc   = "otherdocument" . $staff_id . '.' . $fileInfo['extension'];
                    $img_name     = $uploaddir . $fourth_doc;
                    move_uploaded_file($_FILES["fourth_doc"]["tmp_name"], $img_name);
                } else {
                    $fourth_title = "";
                    $fourth_doc   = "";
                }

                $data_doc = array('id' => $staff_id, 'resume' => $resume, 'joining_letter' => $joining_letter, 'resignation_letter' => $resignation_letter, 'other_document_name' => $fourth_title, 'other_document_file' => $fourth_doc);
                $this->staff_model->add($data_doc);

                //===================
                if ($staff_id) {

                    $teacher_login_detail = array('id' => $staff_id, 'credential_for' => 'staff', 'username' => $email, 'password' => $password, 'contact_no' => $contact_no, 'email' => $email);

                    $this->mailsmsconf->mailsms('login_credential', $teacher_login_detail);
                }

                //==========================

                $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('success_message') . '</div>');

                redirect('admin/staff');
            } else {
                $data['error_message'] = 'Admission No ' . $admission_no . ' already exists';
                $this->load->view('layout/header', $data);
                $this->load->view('admin/staff/staffcreate', $data);
                $this->load->view('layout/footer', $data);
            }
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/staff/staffcreate', $data);
        $this->load->view('layout/footer', $data);
    }
    function test()
    {
        $teacher_login_detail = array('id' => 50, 'credential_for' => 'staff', 'username' => 'test@gmail.com    ', 'password' => '12345', 'contact_no' => '9605252637', 'email' => 'manojthannimattam@gmail.com');

        $this->mailsmsconf->mailsms('login_credential', $teacher_login_detail);
    }


    public function handle_upload()
    {
        $image_validate = $this->config->item('image_validate');
        $result         = $this->filetype_model->get();
        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {

            $file_type = $_FILES["file"]['type'];
            $file_size = $_FILES["file"]["size"];
            $file_name = $_FILES["file"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->image_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->image_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($files = @getimagesize($_FILES['file']['tmp_name'])) {

                if (!in_array($files['mime'], $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                    return false;
                }
                if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                    return false;
                }

                if ($file_size > $result->image_size) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($image_validate['upload_size'] / 1048576, 2) . " MB");
                    return false;
                }
            } else {
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }

            return true;
        }
        return true;
    }

    public function handle_first_upload()
    {
        $file_validate = $this->config->item('file_validate');
        $result        = $this->filetype_model->get();
        if (isset($_FILES["first_doc"]) && !empty($_FILES['first_doc']['name'])) {

            $file_type = $_FILES["first_doc"]['type'];
            $file_size = $_FILES["first_doc"]["size"];
            $file_name = $_FILES["first_doc"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mtype = finfo_file($finfo, $_FILES['first_doc']['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mtype, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_first_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }

            if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_first_upload', $this->lang->line('extension_not_allowed'));
                return false;
            }
            if ($file_size > $result->file_size) {
                $this->form_validation->set_message('handle_first_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($file_validate['upload_size'] / 1048576, 2) . " MB");
                return false;
            }

            return true;
        }
        return true;
    }

    public function handle_second_upload()
    {
        $file_validate = $this->config->item('file_validate');
        $result        = $this->filetype_model->get();
        if (isset($_FILES["second_doc"]) && !empty($_FILES['second_doc']['name'])) {

            $file_type         = $_FILES["second_doc"]['type'];
            $file_size         = $_FILES["second_doc"]["size"];
            $file_name         = $_FILES["second_doc"]["name"];
            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mtype = finfo_file($finfo, $_FILES['second_doc']['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mtype, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_second_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }

            if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_second_upload', $this->lang->line('extension_not_allowed'));
                return false;
            }
            if ($file_size > $result->file_size) {
                $this->form_validation->set_message('handle_second_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($file_validate['upload_size'] / 1048576, 2) . " MB");
                return false;
            }

            return true;
        }
        return true;
    }

    public function handle_third_upload()
    {
        $file_validate = $this->config->item('file_validate');
        $result        = $this->filetype_model->get();
        if (isset($_FILES["third_doc"]) && !empty($_FILES['third_doc']['name'])) {

            $file_type = $_FILES["third_doc"]['type'];
            $file_size = $_FILES["third_doc"]["size"];
            $file_name = $_FILES["third_doc"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mtype = finfo_file($finfo, $_FILES['third_doc']['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mtype, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_third_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }

            if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_third_upload', $this->lang->line('extension_not_allowed'));
                return false;
            }
            if ($file_size > $result->file_size) {
                $this->form_validation->set_message('handle_third_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($file_validate['upload_size'] / 1048576, 2) . " MB");
                return false;
            }

            return true;
        }
        return true;
    }

    public function handle_fourth_upload()
    {
        $file_validate = $this->config->item('file_validate');
        $result        = $this->filetype_model->get();
        if (isset($_FILES["fourth_doc"]) && !empty($_FILES['fourth_doc']['name'])) {

            $file_type = $_FILES["fourth_doc"]['type'];
            $file_size = $_FILES["fourth_doc"]["size"];
            $file_name = $_FILES["fourth_doc"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mtype = finfo_file($finfo, $_FILES['fourth_doc']['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mtype, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_fourth_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }

            if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_fourth_upload', $this->lang->line('extension_not_allowed'));
                return false;
            }
            if ($file_size > $result->file_size) {
                $this->form_validation->set_message('handle_fourth_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($file_validate['upload_size'] / 1048576, 2) . " MB");
                return false;
            }

            return true;
        }
        return true;
    }

    public function username_check($str)
    {
        if (empty($str)) {
            $this->form_validation->set_message('username_check', $this->lang->line('staff_ID_field_is_required'));
            return false;
        } else {

            $result = $this->staff_model->valid_employee_id($str);
            if ($result == false) {

                return false;
            }
            return true;
        }
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('staff', 'can_edit')) {
            access_denied();
        }
        $a           = 0;
        $sessionData = $this->session->userdata('admin');
        $userdata    = $this->customlib->getUserData();

        $data['title']               = 'Edit Staff';
        $data['id']                  = $id;
        $genderList                  = $this->customlib->getGender();
        $data['genderList']          = $genderList;
        $payscaleList                = $this->staff_model->getPayroll();
        $leavetypeList               = $this->staff_model->getLeaveType();
        $data["leavetypeList"]       = $leavetypeList;
        $data["payscaleList"]        = $payscaleList;
        $staffRole                   = $this->staff_model->getStaffRole();
        $data["getStaffRole"]        = $staffRole;
        $designation                 = $this->staff_model->getStaffDesignation();
        $data["designation"]         = $designation;
        $department                  = $this->staff_model->getDepartment();
        $data["department"]          = $department;
        $marital_status              = $this->marital_status;
        $data["marital_status"]      = $marital_status;
        $data['title']               = 'Edit Staff';
        $staff                       = $this->staff_model->get($id);
        $data['staff']               = $staff;
        $payroll_category            = $this->payroll_model->getPayrollCategory();
        $data['payroll_category']    = $payroll_category;
        $data["contract_type"]       = $this->contract_type;
        $data['sch_setting']         = $this->sch_setting_detail;
        $data['staffid_auto_insert'] = $this->sch_setting_detail->staffid_auto_insert;
        $data['staff_subRow']        = $this->staff_model->getstaff_sub($id);
        $data['bank_list'] = $this->payroll_model->getBank_mst();
        if ($staff["role_id"] == 7) {
            $a = 0;
            if ($userdata["email"] == $staff["email"]) {
                $a = 1;
            }
        } else {
            $a = 1;
        }

        if ($a != 1) {
            access_denied();
        }

        $staffLeaveDetails         = $this->staff_model->getLeaveDetails($id);
        $data['staffLeaveDetails'] = $staffLeaveDetails;
        
        $resume                    = $this->input->post("resume");
        $joining_letter            = $this->input->post("joining_letter");
        $resignation_letter        = $this->input->post("resignation_letter");
        $other_document_name       = $this->input->post("other_document_name");
        $other_document_file       = $this->input->post("other_document_file");
        $custom_fields             = $this->customfield_model->getByBelong('staff');

        foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {

            if ($custom_fields_value['validation']) {
                $custom_fields_id   = $custom_fields_value['id'];
                $custom_fields_name = $custom_fields_value['name'];
                $this->form_validation->set_rules("custom_fields[staff][" . $custom_fields_id . "]", $custom_fields_name, 'trim|required');
            }
        }

        $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('role', 'Role', 'trim|required|xss_clean');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|required|xss_clean');
        $this->form_validation->set_rules('dob', 'Date of Birth', 'trim|required|xss_clean');
        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_upload');
        $this->form_validation->set_rules('first_doc', $this->lang->line('image'), 'callback_handle_first_upload');
        $this->form_validation->set_rules('second_doc', $this->lang->line('image'), 'callback_handle_second_upload');
        $this->form_validation->set_rules('third_doc', $this->lang->line('image'), 'callback_handle_third_upload');
        $this->form_validation->set_rules('fourth_doc', $this->lang->line('image'), 'callback_handle_fourth_upload');
        if (!$this->sch_setting_detail->staffid_auto_insert) {

            $this->form_validation->set_rules('employee_id', $this->lang->line('staff_id'), 'callback_username_check');
        }

        $this->form_validation->set_rules(
            'email',
            $this->lang->line('email'),
            array(
                'required', 'valid_email',
                array('check_exists', array($this->staff_model, 'valid_email_id')),
            )
        );
        if ($this->form_validation->run() == false) {

            $this->load->view('layout/header', $data);
            $this->load->view('admin/staff/staffedit', $data);
            $this->load->view('layout/footer', $data);
        } else {


            $employee_id       = $this->input->post("employee_id");
            $department        = $this->input->post("department");
            $designation       = $this->input->post("designation");
            $role              = $this->input->post("role");
            $name              = $this->input->post("name");
            $gender            = $this->input->post("gender");
            $marital_status    = $this->input->post("marital_status");
            $dob               = $this->input->post("dob");
            $contact_no        = $this->input->post("contactno");
            $emergency_no      = $this->input->post("emergency_no");
            $email             = $this->input->post("email");
            $date_of_joining   = $this->input->post("date_of_joining");
            $date_of_leaving   = $this->input->post("date_of_leaving");
            $address           = $this->input->post("address");
            $qualification     = $this->input->post("qualification");
            $work_exp          = $this->input->post("work_exp");
            $basic_salary      = $this->input->post('basic_salary');
            $account_title     = $this->input->post("account_title");
            $bank_account_no   = $this->input->post("bank_account_no");
            $bank_name         = $this->input->post("bank_name");
            $ifsc_code         = $this->input->post("ifsc_code");
            $bank_branch       = $this->input->post("bank_branch");
            $contract_type     = $this->input->post("contract_type");
            $shift             = $this->input->post("shift");
            $location          = $this->input->post("location");
            $leave             = $this->input->post("leave");
            $facebook          = $this->input->post("facebook");
            $twitter           = $this->input->post("twitter");
            $linkedin          = $this->input->post("linkedin");
            $instagram         = $this->input->post("instagram");
            $permanent_address = $this->input->post("permanent_address");
            $father_name       = $this->input->post("father_name");
            $surname           = $this->input->post("surname");
            $mother_name       = $this->input->post("mother_name");
            $note              = $this->input->post("note");
            $epf_no            = $this->input->post("epf_no");
            $seniority_id      = $this->input->post("seniority_id");
            $blood_group       = $this->input->post("blood_group");
            $aadhar_no         = $this->input->post("aadhar_no");
            $pan_no            = $this->input->post("pan_no");
            $biometric_id      = $this->input->post("biometric_id");
            $middle_name       = $this->input->post("middle_name");
            $date_of_confirmation = $this->input->post("date_of_confirmation");
            $date_of_retirement   = $this->input->post("date_of_retirement");
            $lefton               = $this->input->post("lefton");
            $left_reason        = $this->input->post("left_reason");
            $grade              = $this->input->post("grade");
            $spouse_name        = $this->input->post("spouse_name");
            $residence          = $this->input->post("residence");
            $cast               = $this->input->post("cast");
            $religion           = $this->input->post("religion");
            $subcaste           = $this->input->post("subcaste");
            $remarks            = $this->input->post("remarks");
            $current_city       = $this->input->post("current_city");
            $current_state      = $this->input->post("current_state");
            $current_country    = $this->input->post("current_country");
            $current_pincode    = $this->input->post("current_pincode");
            $permanent_city     = $this->input->post("permanent_city");
            $permanent_state    = $this->input->post("permanent_state");
            $permanent_country  = $this->input->post("permanent_country");
            $permanent_pincode  = $this->input->post("permanent_pincode");
            $mobile2            = $this->input->post("mobile2");
            $phone2             = $this->input->post("phone2");
            $uan_no             = $this->input->post("uan_no");
            $pf_exempted        = $this->input->post("pf_exempted");
            $it_scheme          = $this->input->post("it_scheme");
            $pt_exempted        = $this->input->post("pt_exempted");
            $dcps_no            = $this->input->post("dcps_no");
            $passport_no        = $this->input->post("passport_no");
            $place_of_issue     = $this->input->post("place_of_issue");
            $date_of_issue      = $this->input->post("date_of_issue");
            $date_of_expiry     = $this->input->post("date_of_expiry");
            
            $esi_no             = $this->input->post("esi_no");
            $esi_dispensary     = $this->input->post("esi_dispensary");
            $esi_exempted       = $this->input->post("esi_exempted");
            $lwf_applicable     = $this->input->post("lwf_applicable");
            $lwf_grade          = $this->input->post("lwf_grade");
            $gratuity_no        = $this->input->post("gratuity_no");
            $increment_month    = $this->input->post("increment_month");
            $increment_amount   = $this->input->post("increment_amount");
            $pay_group          = $this->input->post("pay_group");
            $record_type          = $this->input->post("record_type");
            $salary_to_bank    = $this->input->post("salary_to_bank");
            $salary_upto_month    = $this->input->post("salary_upto_month");
            $contract_status    = $this->input->post("contract_status");

            $custom_field_post = $this->input->post("custom_fields[staff]");

            $custom_value_array = array();
            if (!empty($custom_fields)) {
                foreach ($custom_field_post as $key => $value) {
                    $check_field_type = $this->input->post("custom_fields[staff][" . $key . "]");
                    $field_value      = is_array($check_field_type) ? implode(",", $check_field_type) : $check_field_type;
                    $array_custom     = array(
                        'belong_table_id' => $id,
                        'custom_field_id' => $key,
                        'field_value'     => $field_value,
                    );
                    $custom_value_array[] = $array_custom;
                }
                $this->customfield_model->updateRecord($custom_value_array, $id, 'staff');
            }

            $data1 = array(
                'id'                   => $id,
                'department'           => $department,
                'designation'          => $designation,
                'qualification'        => $qualification,
                'work_exp'             => $work_exp,
                'name'                 => $name,
                'middle_name'          => $middle_name,
                'contact_no'           => $contact_no,
                'emergency_contact_no' => $emergency_no,
                'email'                => $email,
                'dob'                  => date('Y-m-d', $this->customlib->datetostrtotime($dob)),
                'marital_status'       => $marital_status,
                'local_address'        => $address,
                'permanent_address'    => $permanent_address,
                'note'                 => $note,
                'surname'              => $surname,
                'mother_name'          => $mother_name,
                'father_name'          => $father_name,
                'gender'               => $gender,
                'account_title'        => $account_title,
                'bank_account_no'      => $bank_account_no,
                'bank_name'            => $bank_name,
                'ifsc_code'            => $ifsc_code,
                'bank_branch'          => $bank_branch,
                'payscale'             => '',
                'basic_salary'         => $basic_salary,
                'epf_no'               => $epf_no,
                'contract_type'        => $contract_type,
                'shift'                => $shift,
                'location'             => $location,
                'facebook'             => $facebook,
                'twitter'              => $twitter,
                'linkedin'             => $linkedin,
                'instagram'            => $instagram,
                'seniority_id'         => $seniority_id,
                'blood_group'          => $blood_group,
                'aadhar_no'            => $aadhar_no,
                'pan_no'               => $pan_no,
                'biometric_id'         => $biometric_id,
                'record_type'          => $record_type,
                'salary_to_bank'       => $salary_to_bank,
                'salary_upto_month' => $salary_upto_month,
                'contract_status' => $contract_status
            );

            
            if ($date_of_joining != "") {
                $data1['date_of_joining'] = date('Y-m-d', $this->customlib->datetostrtotime($date_of_joining));
            } else {
                $data1['date_of_joining'] = null;
            }
            if ($date_of_leaving != "") {
                $data1['date_of_leaving'] = date('Y-m-d', $this->customlib->datetostrtotime($date_of_leaving));
            } else {
                $data1['date_of_leaving'] = null;
            }
            if (!$this->sch_setting_detail->staffid_auto_insert) {
                $data1['employee_id'] = $employee_id;
            }
            if ($this->input->post("payroll_category_id") !="") {
                $data1['payroll_category_id'] = $this->input->post("payroll_category_id");
            }

            $insert_id = $this->staff_model->add($data1);

            $date_of_confirmation = !empty($date_of_confirmation) ? date('Y-m-d',strtotime($date_of_confirmation)) : null ;
            $date_of_retirement = !empty($date_of_retirement) ? date('Y-m-d',strtotime($date_of_retirement)) : null ;
            $lefton = !empty($lefton) ? date('Y-m-d',strtotime($lefton)) : null ;
            $date_of_issue = !empty($date_of_issue) ? date('Y-m-d',strtotime($date_of_issue)) : null ;
            $date_of_expiry = !empty($date_of_expiry) ? date('Y-m-d',strtotime($date_of_expiry)) : null ;

            $arraySub = array(
                'id'      => $this->input->post('staff_sub_id'),
                'staff_id'      => $id,
                'date_of_confirmation'      => $date_of_confirmation,
                'date_of_retirement'      => $date_of_retirement,
                'lefton'             => $lefton,
                'left_reason'        => $left_reason,
                'grade'              => $grade,
                'spouse_name'        => $spouse_name,
                'residence'          => $residence,
                'cast'               => $cast,
                'religion'           => $religion,
                'subcaste'           => $subcaste,
                'remarks'            => $remarks,
                'current_city'       => $current_city,
                'current_state'      => $current_state,
                'current_country'    => $current_country,
                'current_pincode'    => $current_pincode,
                'permanent_city'     => $permanent_city,
                'permanent_state'    => $permanent_state,
                'permanent_country'  => $permanent_country,
                'permanent_pincode'  => $permanent_pincode,
                'mobile2'            => $mobile2,
                'phone2'             => $phone2,
                'uan_no'             => $uan_no,
                'pf_exempted'        => $pf_exempted,
                'it_scheme'          => $it_scheme,
                'pt_exempted'        => $pt_exempted,
                'dcps_no'            => $dcps_no,
                'passport_no'        => $passport_no,
                'place_of_issue'     => $place_of_issue,
                'date_of_issue'      => $date_of_issue,
                'date_of_expiry'     => $date_of_expiry,
                'esi_no'             => $esi_no,
                'esi_dispensary'     => $esi_dispensary,
                'esi_exempted'       => $esi_exempted,
                'lwf_applicable'     => $lwf_applicable,
                'lwf_grade'          => $lwf_grade,
                'gratuity_no'        => $gratuity_no,
                'increment_month'    => $increment_month,
                'increment_amount'   => $increment_amount,
                'pay_group'          => $pay_group,
                );

            $this->staff_model->addStaff_sub($arraySub);
            $role_id = $this->input->post("role");

            $role_data = array('staff_id' => $id, 'role_id' => $role_id);

            $this->staff_model->update_role($role_data);

            $leave_type = $this->input->post("leave_type_id");

            $alloted_leave = $this->input->post("alloted_leave");
            $altid         = $this->input->post("altid");

            $staff_session_id      = $this->input->post("staff_session_id");
            $scale_of_pay          = $this->input->post("scale_of_pay");
            $basic_pay             = $this->input->post("basic_pay");
            $gp                    = $this->input->post("gp");
            $da                    = $this->input->post("da");
            $hra                   = $this->input->post("hra");
            $ta                    = $this->input->post("ta");
            $other_allowance       = $this->input->post("other_allowance");
            $pf                    = $this->input->post("pf");
            $profession_tax        = $this->input->post("profession_tax");
            $personal_profit        = $this->input->post("personal_profit");
            $personal_pay        = $this->input->post("personal_pay");
            $income_tax            = $this->input->post("income_tax");
            $session_current       = $this->setting_model->getCurrentSession();
            
            $dataArray =array(
                'id'                => $staff_session_id,
                'staff_id'          => $insert_id,
                'session_id'        => $session_current,
                'scale_of_pay'      => $scale_of_pay,
                'basic_pay'         => $basic_pay,
                'gp'                => $gp,
                'da'                => $da,
                'hra'               => $hra,
                'ta'                => $ta,
                'other_allowance'   => $other_allowance,
                'pf'                => $pf,
                'profession_tax'    => $profession_tax,
                'personal_profit'    => $personal_profit,
                'income_tax'        => $income_tax,
                'personal_pay'        => $personal_pay,
            );

           $this->staff_model->add_staff_session($dataArray);


            if (!empty($leave_type)) {
                $i = 0;
                foreach ($leave_type as $key => $value) {

                    if (!empty($altid[$i])) {

                        $data2 = array(
                            'staff_id' => $id,
                            'leave_type_id'           => $leave_type[$i],
                            'id'                      => $altid[$i],
                            'alloted_leave'           => $alloted_leave[$i],
                        );
                    } else {

                        $data2 = array(
                            'staff_id' => $id,
                            'leave_type_id'           => $leave_type[$i],
                            'alloted_leave'           => $alloted_leave[$i],
                        );
                    }

                    $this->staff_model->add_staff_leave_details($data2);
                    $i++;
                }
            }

            // $category = $this->input->post("category");
            // if (!empty($category)) {
            //     $j=0;
            //     foreach ($category as $key => $value) { 
            //         $data = array(
            //             'staff_id' => $id,
            //             'payroll_category_id' => $this->input->post("payroll_category_id")[$j],
            //             'category' => $value
            //         );
            //         $this->staff_model->addpayrollcategory($data); 
            //         $j++;
            //     }
            // }

            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $fileInfo = pathinfo($_FILES["file"]["name"]);
                $img_name = $id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/staff_images/" . $img_name);
                $data_img = array('id' => $id, 'image' => $img_name);
                $this->staff_model->add($data_img);
            }

            if (isset($_FILES["first_doc"]) && !empty($_FILES['first_doc']['name'])) {
                $uploaddir = './uploads/staff_documents/' . $id . '/';
                if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                    die("Error creating folder $uploaddir");
                }
                $fileInfo    = pathinfo($_FILES["first_doc"]["name"]);
                $first_title = 'resume';
                $resume_doc  = "resume" . $id . '.' . $fileInfo['extension'];
                $img_name    = $uploaddir . $resume_doc;
                move_uploaded_file($_FILES["first_doc"]["tmp_name"], $img_name);
            } else {

                $resume_doc = $resume;
            }

            if (isset($_FILES["second_doc"]) && !empty($_FILES['second_doc']['name'])) {
                $uploaddir = './uploads/staff_documents/' . $id . '/';
                if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                    die("Error creating folder $uploaddir");
                }
                $fileInfo           = pathinfo($_FILES["second_doc"]["name"]);
                $first_title        = 'joining_letter';
                $joining_letter_doc = "joining_letter" . $id . '.' . $fileInfo['extension'];
                $img_name           = $uploaddir . $joining_letter_doc;
                move_uploaded_file($_FILES["second_doc"]["tmp_name"], $img_name);
            } else {

                $joining_letter_doc = $joining_letter;
            }

            if (isset($_FILES["third_doc"]) && !empty($_FILES['third_doc']['name'])) {
                $uploaddir = './uploads/staff_documents/' . $id . '/';
                if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                    die("Error creating folder $uploaddir");
                }
                $fileInfo               = pathinfo($_FILES["third_doc"]["name"]);
                $first_title            = 'resignation_letter';
                $resignation_letter_doc = "resignation_letter" . $id . '.' . $fileInfo['extension'];
                $img_name               = $uploaddir . $resignation_letter_doc;
                move_uploaded_file($_FILES["third_doc"]["tmp_name"], $img_name);
            } else {

                $resignation_letter_doc = $resignation_letter;
            }

            if (isset($_FILES["fourth_doc"]) && !empty($_FILES['fourth_doc']['name'])) {
                $uploaddir = './uploads/staff_documents/' . $id . '/';
                if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                    die("Error creating folder $uploaddir");
                }
                $fileInfo     = pathinfo($_FILES["fourth_doc"]["name"]);
                $fourth_title = 'Other Doucment';
                $fourth_doc   = "otherdocument" . $id . '.' . $fileInfo['extension'];
                $img_name     = $uploaddir . $fourth_doc;
                move_uploaded_file($_FILES["fourth_doc"]["tmp_name"], $img_name);
            } else {
                $fourth_title = 'Other Document';
                $fourth_doc   = $other_document_file;
            }

            $data_doc = array('id' => $id, 'resume' => $resume_doc, 'joining_letter' => $joining_letter_doc, 'resignation_letter' => $resignation_letter_doc, 'other_document_name' => $fourth_title, 'other_document_file' => $fourth_doc);

            $this->staff_model->add($data_doc);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/staff');
        }
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('staff', 'can_delete')) {
            access_denied();
        }

        $a           = 0;
        $sessionData = $this->session->userdata('admin');
        $userdata    = $this->customlib->getUserData();
        $staff       = $this->staff_model->get($id);

        if ($staff['id'] == $userdata['id']) {
            $a = 1;
        } else if ($staff["role_id"] == 7) {
            $a = 1;
        }

        if ($a == 1) {
            access_denied();
        }
        $data['title'] = 'Staff List';
        $this->staff_model->remove($id);
        redirect('admin/staff');
    }
    public function delete_adv($id)
    {
        if (!$this->rbac->hasPrivilege('staff_advance', 'can_delete')) {
            access_denied();
        }

        // $a           = 0;
        // $sessionData = $this->session->userdata('admin');
        // $userdata    = $this->customlib->getUserData();
        // $staff       = $this->staff_model->get($id);

        // if ($staff['id'] == $userdata['id']) {
        //     $a = 1;
        // } else if ($staff["role_id"] == 7) {
        //     $a = 1;
        // }

        // if ($a == 1) {
        //     access_denied();
        // }
        $data['title'] = 'Staff List';
        $this->staff_model->remove_adv($id);
        redirect('admin/staff/staff_advance');
    }
    public function delete_loan($id)
    {
        if (!$this->rbac->hasPrivilege('staff_advance', 'can_delete')) {
            access_denied();
        }

        // $a           = 0;
        // $sessionData = $this->session->userdata('admin');
        // $userdata    = $this->customlib->getUserData();
        // $staff       = $this->staff_model->get($id);

        // if ($staff['id'] == $userdata['id']) {
        //     $a = 1;
        // } else if ($staff["role_id"] == 7) {
        //     $a = 1;
        // }

        // if ($a == 1) {
        //     access_denied();
        // }
        $data['title'] = 'Staff List';
        $this->staff_model->remove_loan($id);
        redirect('admin/staff/staff_loan');
    }

    public function disablestaff($id)
    {
        if (!$this->rbac->hasPrivilege('disable_staff', 'can_view')) {

            access_denied();
        }

        $a           = 0;
        $sessionData = $this->session->userdata('admin');
        $userdata    = $this->customlib->getUserData();
        $staff       = $this->staff_model->get($id);
        if ($staff["role_id"] == 7) {
            $a = 0;
            if ($userdata["email"] == $staff["email"]) {
                $a = 1;
            }
        } else {
            $a = 1;
        }

        if ($a != 1) {
            access_denied();
        }
        $data = array('id' => $id, 'disable_at' => date('Y-m-d', $this->customlib->datetostrtotime($_POST['date'])), 'is_active' => 0);
        $this->staff_model->disablestaff($data);
        $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        echo json_encode($array);
    }

    public function enablestaff($id)
    {

        $a           = 0;
        $sessionData = $this->session->userdata('admin');
        $userdata    = $this->customlib->getUserData();
        $staff       = $this->staff_model->get($id);
        if ($staff["role_id"] == 7) {
            $a = 0;
            if ($userdata["email"] == $staff["email"]) {
                $a = 1;
            }
        } else {
            $a = 1;
        }

        if ($a != 1) {
            access_denied();
        }
        $this->staff_model->enablestaff($id);
        redirect('admin/staff/profile/' . $id);
    }

    public function staffLeaveSummary()
    {

        $resultdata         = $this->staff_model->getLeaveSummary();
        $data["resultdata"] = $resultdata;

        $this->load->view("layout/header");
        $this->load->view("admin/staff/staff_leave_summary", $data);
        $this->load->view("layout/footer");
    }

    public function getEmployeeByRole()
    {

        $role = $this->input->post("role");

        $data = $this->staff_model->getEmployee($role);

        echo json_encode($data);
    }

    public function dateDifference($date_1, $date_2, $differenceFormat = '%a')
    {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);

        $interval = date_diff($datetime1, $datetime2);

        return $interval->format($differenceFormat) + 1;
    }

    public function permission($id)
    {
        $data['title']          = 'Add Role';
        $data['id']             = $id;
        $staff                  = $this->staff_model->get($id);
        $data['staff']          = $staff;
        $userpermission         = $this->userpermission_model->getUserPermission($id);
        $data['userpermission'] = $userpermission;

        if ($this->input->server('REQUEST_METHOD') == "POST") {
            $staff_id   = $this->input->post('staff_id');
            $prev_array = $this->input->post('prev_array');
            if (!isset($prev_array)) {
                $prev_array = array();
            }
            $module_perm  = $this->input->post('module_perm');
            $delete_array = array_diff($prev_array, $module_perm);
            $insert_diff  = array_diff($module_perm, $prev_array);
            $insert_array = array();
            if (!empty($insert_diff)) {

                foreach ($insert_diff as $key => $value) {
                    $insert_array[] = array(
                        'staff_id'      => $staff_id,
                        'permission_id' => $value,
                    );
                }
            }

            $this->userpermission_model->getInsertBatch($insert_array, $staff_id, $delete_array);

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/staff');
        }

        $this->load->view('layout/header');
        $this->load->view('admin/staff/permission', $data);
        $this->load->view('layout/footer');
    }

    public function leaverequest()
    {

        if (!$this->rbac->hasPrivilege('apply_leave', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/staff/leaverequest');
        $userdata              = $this->customlib->getUserData();
        $leave_request         = $this->leaverequest_model->user_leave_request($userdata["id"]);
        $data["leave_request"] = $leave_request;
        $LeaveTypes            = $this->leaverequest_model->allotedLeaveType($userdata["id"]);
        $data["staff_id"]      = $userdata["id"];
        $data["leavetype"]     = $LeaveTypes;
        $staffRole             = $this->staff_model->getStaffRole();
        $data["staffrole"]     = $staffRole;
        $data["status"]        = $this->status;

        $this->load->view("layout/header", $data);
        $this->load->view("admin/staff/leaverequest", $data);
        $this->load->view("layout/footer", $data);
    }

    public function change_password($id)
    {

        $sessionData = $this->session->userdata('admin');
        $userdata    = $this->customlib->getUserData();

        $this->form_validation->set_rules('new_pass', $this->lang->line('new_password'), 'trim|required|xss_clean|matches[confirm_pass]');
        $this->form_validation->set_rules('confirm_pass', $this->lang->line('confirm_password'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {

            $msg = array(
                'new_pass'     => form_error('new_pass'),
                'confirm_pass' => form_error('confirm_pass'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            if (!empty($id)) {
                $newdata = array(
                    'id'       => $id,
                    'password' => $this->enc_lib->passHashEnc($this->input->post('new_pass')),
                );

                $query2 = $this->admin_model->saveNewPass($newdata);
                if ($query2) {
                    $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('password_changed_successfully'));
                } else {

                    $array = array('status' => 'fail', 'error' => '', 'message' => $this->lang->line('password_not_changed'));
                }
            } else {
                $array = array('status' => 'fail', 'error' => '', 'message' => $this->lang->line('password_not_changed'));
            }
        }

        echo json_encode($array);
    }

    public function import()
    {
        $data['field'] = array(
            "staff_id"                 => "staff_id",
            "first_name"               => "first_name",
            "last_name"                => "last_name",
            "father_name"              => "father_name",
            "mother_name"              => "mother_name",
            "email_login_username"     => "email",
            "gender"                   => "gender",
            "date_of_birth"            => "date_of_birth",
            "date_of_joining"          => "date_of_joining",
            "phone"                    => "phone",
            "emergency_contact_number" => "emergency_contact_number",
            "marital_status"           => "marital_status",
            "current_address"          => "current_address",
            "permanent_address"        => "permanent_address",
            "qualification"            => "qualification",
            "work_experience"          => "work_experience",
            "note"                     => "note",
        );
        $roles               = $this->role_model->get();
        $data["roles"]       = $roles;
        $designation         = $this->staff_model->getStaffDesignation();
        $data["designation"] = $designation;
        $department          = $this->staff_model->getDepartment();
        $data["department"]  = $department;

        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_csv_upload');
        $this->form_validation->set_rules('role', $this->lang->line('role'), 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view("layout/header", $data);
            $this->load->view("admin/staff/import/import", $data);
            $this->load->view("layout/footer", $data);
        } else {

            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {

                $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                if ($ext == 'csv') {

                    $file = $_FILES['file']['tmp_name'];
                    $this->load->library('CSVReader');
                    $result = $this->csvreader->parse_file($file);

                    $rowcount = 0;

                    if (!empty($result)) {

                        foreach ($result as $r_key => $r_value) {

                            $check_exists      = $this->staff_model->import_check_data_exists($result[$r_key]['name'], $result[$r_key]['employee_id']);
                            $check_emailexists = $this->staff_model->import_check_email_exists($result[$r_key]['name'], $result[$r_key]['email']);

                            if ($check_exists == 0 && $check_emailexists == 0) {

                                $result[$r_key]['employee_id']          = $this->encoding_lib->toUTF8($result[$r_key]['employee_id']);
                                $result[$r_key]['qualification']        = $this->encoding_lib->toUTF8($result[$r_key]['qualification']);
                                $result[$r_key]['work_exp']             = $this->encoding_lib->toUTF8($result[$r_key]['work_exp']);
                                $result[$r_key]['name']                 = $this->encoding_lib->toUTF8($result[$r_key]['name']);
                                $result[$r_key]['surname']              = $this->encoding_lib->toUTF8($result[$r_key]['surname']);
                                $result[$r_key]['father_name']          = $this->encoding_lib->toUTF8($result[$r_key]['father_name']);
                                $result[$r_key]['mother_name']          = $this->encoding_lib->toUTF8($result[$r_key]['mother_name']);
                                $result[$r_key]['contact_no']           = $this->encoding_lib->toUTF8($result[$r_key]['contact_no']);
                                $result[$r_key]['emergency_contact_no'] = $this->encoding_lib->toUTF8($result[$r_key]['emergency_contact_no']);
                                $result[$r_key]['email']                = $this->encoding_lib->toUTF8($result[$r_key]['email']);
                                $result[$r_key]['dob']                  = $this->encoding_lib->toUTF8($result[$r_key]['dob']);
                                $result[$r_key]['marital_status']       = $this->encoding_lib->toUTF8($result[$r_key]['marital_status']);
                                $result[$r_key]['date_of_joining']      = $this->encoding_lib->toUTF8($result[$r_key]['date_of_joining']);
                                $result[$r_key]['date_of_leaving']      = $this->encoding_lib->toUTF8($result[$r_key]['date_of_leaving']);
                                $result[$r_key]['local_address']        = $this->encoding_lib->toUTF8($result[$r_key]['local_address']);
                                $result[$r_key]['permanent_address']    = $this->encoding_lib->toUTF8($result[$r_key]['permanent_address']);
                                $result[$r_key]['note']                 = $this->encoding_lib->toUTF8($result[$r_key]['note']);
                                $result[$r_key]['gender']               = $this->encoding_lib->toUTF8($result[$r_key]['gender']);
                                $result[$r_key]['account_title']        = $this->encoding_lib->toUTF8($result[$r_key]['account_title']);
                                $result[$r_key]['bank_account_no']      = $this->encoding_lib->toUTF8($result[$r_key]['bank_account_no']);
                                $result[$r_key]['bank_name']            = $this->encoding_lib->toUTF8($result[$r_key]['bank_name']);
                                $result[$r_key]['ifsc_code']            = $this->encoding_lib->toUTF8($result[$r_key]['ifsc_code']);
                                $result[$r_key]['payscale']             = $this->encoding_lib->toUTF8($result[$r_key]['payscale']);
                                $result[$r_key]['basic_salary']         = $this->encoding_lib->toUTF8($result[$r_key]['basic_salary']);
                                $result[$r_key]['epf_no']               = $this->encoding_lib->toUTF8($result[$r_key]['epf_no']);
                                $result[$r_key]['contract_type']        = $this->encoding_lib->toUTF8($result[$r_key]['contract_type']);
                                $result[$r_key]['shift']                = $this->encoding_lib->toUTF8($result[$r_key]['shift']);
                                $result[$r_key]['location']             = $this->encoding_lib->toUTF8($result[$r_key]['location']);
                                $result[$r_key]['facebook']             = $this->encoding_lib->toUTF8($result[$r_key]['facebook']);
                                $result[$r_key]['twitter']              = $this->encoding_lib->toUTF8($result[$r_key]['twitter']);
                                $result[$r_key]['linkedin']             = $this->encoding_lib->toUTF8($result[$r_key]['linkedin']);
                                $result[$r_key]['instagram']            = $this->encoding_lib->toUTF8($result[$r_key]['instagram']);
                                $result[$r_key]['resume']               = $this->encoding_lib->toUTF8($result[$r_key]['resume']);
                                $result[$r_key]['joining_letter']       = $this->encoding_lib->toUTF8($result[$r_key]['joining_letter']);
                                $result[$r_key]['resignation_letter']   = $this->encoding_lib->toUTF8($result[$r_key]['resignation_letter']);
                                $result[$r_key]['user_id']              = $this->input->post('role');
                                $result[$r_key]['designation']          = $this->input->post('designation');
                                $result[$r_key]['department']           = $this->input->post('department');
                                $result[$r_key]['is_active']            = 1;

                                $password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);

                                $result[$r_key]['password'] = $this->enc_lib->passHashEnc($password);

                                $role_array = array('role_id' => $this->input->post('role'), 'staff_id' => 0);

                                $insert_id = $this->staff_model->batchInsert($result[$r_key], $role_array);
                                $staff_id  = $insert_id;
                                if ($staff_id) {

                                    $teacher_login_detail = array('id' => $staff_id, 'credential_for' => 'staff', 'username' => $result[$r_key]['email'], 'password' => $password, 'contact_no' => $result[$r_key]['contact_no'], 'email' => $result[$r_key]['email']);

                                    $this->mailsmsconf->mailsms('login_credential', $teacher_login_detail);
                                }
                                $rowcount++;
                            }
                        } ///Result loop
                    } //Not emprty l

                    $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('records_found_in_CSV_file_total') . $rowcount . $this->lang->line('records_imported_successfully'));
                }
            } else {
                $msg = array(
                    'e' => $this->lang->line('the_file_field_is_required'),
                );
                $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
            }

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">' . $this->lang->line('total') . ' ' . count($result) . " " . $this->lang->line('records_found_in_CSV_file_total') . ' ' . $rowcount . ' ' . $this->lang->line('records_imported_successfully') . '</div>');
            redirect('admin/staff/import');
        }
    }

    public function handle_csv_upload()
    {
        $error = "";
        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
            $allowedExts = array('csv');
            $mimes       = array(
                'text/csv',
                'text/plain',
                'application/csv',
                'text/comma-separated-values',
                'application/excel',
                'application/vnd.ms-excel',
                'application/vnd.msexcel',
                'text/anytext',
                'application/octet-stream',
                'application/txt'
            );
            $temp      = explode(".", $_FILES["file"]["name"]);
            $extension = end($temp);
            if ($_FILES["file"]["error"] > 0) {
                $error .= "Error opening the file<br />";
            }
            if (!in_array($_FILES['file']['type'], $mimes)) {
                $error .= "Error opening the file<br />";
                $this->form_validation->set_message('handle_csv_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }
            if (!in_array($extension, $allowedExts)) {
                $error .= "Error opening the file<br />";
                $this->form_validation->set_message('handle_csv_upload', $this->lang->line('extension_not_allowed'));
                return false;
            }
            if ($error == "") {
                return true;
            }
        } else {
            $this->form_validation->set_message('handle_csv_upload', $this->lang->line('please_select_file'));
            return false;
        }
    }

    public function exportformat()
    {
        $this->load->helper('download');
        $filepath = "./backend/import/staff_csvfile.csv";
        $data     = file_get_contents($filepath);
        $name     = 'staff_csvfile.csv';

        force_download($name, $data);
    }

    public function rating()
    {

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'HR/rating');
        $this->load->view('layout/header');
        $staff_list = $this->staff_model->getrat();

        $data['resultlist'] = $staff_list;

        $this->load->view('admin/staff/rating', $data);
        $this->load->view('layout/footer');
    }

    public function ratingapr($id)
    {
        $approve['status'] = '1';
        $this->staff_model->ratingapr($id, $approve);
        redirect('admin/staff/rating');
    }

    public function delete_rateing($id)
    {
        $this->staff_model->rating_remove($id);
        redirect('admin/staff/rating');
    }

    public function staff_termination()
    {

        if (!$this->rbac->hasPrivilege('staff_termination', 'can_view')) {
            access_denied();
        }

        if (isset($_POST['role']) && $_POST['role'] != '') {
            $data['search_role'] = $_POST['role'];
        } else {
            $data['search_role'] = "";
        }

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'HR/staff/staff_termination');
        $data['title'] = 'Staff Search';
        $search             = $this->input->post("search");
        $resultlist         = $this->staff_model->searchFullText("", 1);
        $staffList           = $this->staff_model->getAll_users();
        $data['resultlist'] = $staffList;
        $staffRole          = $this->staff_model->getStaffRole();
        $data["role"]       = $staffRole;
        $data["role_id"]    = "";
        $search_text        = $this->input->post('search_text');
        if (isset($search)) {
            if ($search == 'search_filter') {
                $this->form_validation->set_rules('role', $this->lang->line('role'), 'trim|required|xss_clean');
                if ($this->form_validation->run() == false) {
                    $data["resultlist"] = array();
                } else {
                    $data['searchby']    = "filter";
                    $role                = $this->input->post('role');
                    $data['employee_id'] = $this->input->post('empid');
                    $data["role_id"]     = $role;
                    $data['search_text'] = $this->input->post('search_text');
                    $resultlist          = $this->staff_model->getEmployee($role, 1);
                    $data['resultlist']  = $resultlist;
                }
            } else if ($search == 'search_full') {
                $data['searchby']    = "text";
                $data['search_text'] = trim($this->input->post('search_text'));
                $resultlist          = $this->staff_model->searchFullText($search_text, 1);

                $data['resultlist'] = $resultlist;
                $data['title']      = 'Search Details: ' . $data['search_text'];
            }
        }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/staff/staff_termination', $data);
        $this->load->view('layout/footer', $data);
    }

    public function create_termination($staff_id)
    {
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'HR/staff/staff_termination');
        $roles                  = $this->role_model->get();
        $data["roles"]          = $roles;
        $data["staff_id"]       = $staff_id;
        $session_current       = $this->setting_model->getCurrentSession();
        $data["session_id"]       = $session_current;

        $update = $this->staff_model->get_terminateRow($staff_id);
        $data["update"]       = $update;

        // $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        // if ($this->form_validation->run() == true) {


        // } else {
        $this->load->view('layout/header', $data);
        $this->load->view('admin/staff/staffcreate_termination', $data);
        $this->load->view('layout/footer', $data);
        // }
    }

    public function submit_termination()
    {
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'HR/staff/staff_termination');
        $staff_id               = $this->input->post("staff_id");
        $data["staff_id"]       = $staff_id;
        $this->form_validation->set_rules('date_of_termination', $this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('typeof_termination', "Type Of termination", 'trim|xss_clean');
        $this->form_validation->set_rules('remarks', "Remarks", 'trim|xss_clean');
        if ($this->form_validation->run() == true) {

            $session_id       = $this->input->post("session_id");
            $typeof_termination       = $this->input->post("typeof_termination");
            $date_of_termination       = $this->input->post("date_of_termination");
            $remarks       = $this->input->post("remarks");
            $salary_upto_month       = $this->input->post("salary_upto_month");
            $last_working_date       = $this->input->post("last_working_date");
            $array = array(
                'staff_id' => $staff_id,
                'session_id' => $session_id,
                'date_of_termination' => date('Y-m-d', strtotime($date_of_termination)), // letter submission date
                'typeof_termination' => $typeof_termination,
                'remarks' => $remarks,
                'last_working_date' => $last_working_date !="" ? date('Y-m-d', strtotime($date_of_termination)) :null, 
                'status' => 0,
            );
            $this->staff_model->add_teminatedstaff($array);
            $data   = array('id' => $staff_id, 'is_active' => 0, 'salary_upto_month' => $salary_upto_month);
            $result = $this->staff_model->update($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/staff/staff_termination');
        } else {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/staff/staffcreate_termination', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function cancel_termination($staff_id)
    {
        $array = array(
            'staff_id' => $staff_id,
            'status' => 1,
        );

        $this->staff_model->add_teminatedstaff($array);

        $data   = array('id' => $staff_id, 'is_active' => 1);
        $result = $this->staff_model->update($data);

        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
        redirect('admin/staff/staff_termination');
    }

    public function staff_loan($id = "")
    {
        if (!$this->rbac->hasPrivilege('staff_loan', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'HR/staff/staff_loan');
        $resultlist         = $this->staff_model->get();
        $data['resultlist'] = $resultlist;
        $stafflist          = $this->staff_model->getloanList();
        $data['stafflist'] = $stafflist;
        $stafflist          = $this->staff_model->getloanList($id);
        $data['update'] = $stafflist;

        $this->form_validation->set_rules('staff_id', "Staff", 'required|trim|xss_clean');
        $this->form_validation->set_rules('loan_date', "Loan Date", 'required|trim|xss_clean');
        $this->form_validation->set_rules('loan_amount', "Loan Amount", 'required|trim|xss_clean');
        $this->form_validation->set_rules('loan_purpose', "Loan Purpose", 'required|trim|xss_clean');
        $this->form_validation->set_rules('loan_emi', "Loan EMI", 'required|trim|xss_clean');
        $this->form_validation->set_rules('loan_tenure_months', "Loan Tenure Month", 'required|trim|xss_clean');
        $this->form_validation->set_rules('loan_close_date', "Loan Close Date", 'required|trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/staff/staff_loan', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $session_current       = $this->setting_model->getCurrentSession();
            $data = array(
                'id'                    => $this->input->post('id'),
                'staff_id'              => $this->input->post('staff_id'),
                'session_id'            => $session_current,
                'loan_date'             => date('Y-m-d',strtotime($this->input->post('loan_date'))),
                'loan_amount'           => $this->input->post('loan_amount'),
                'loan_purpose'          => $this->input->post('loan_purpose'),
                'loan_emi '             => $this->input->post('loan_emi'),
                'loan_current_balance ' => $this->input->post('loan_amount'),
                'loan_tenure_months '   => $this->input->post('loan_tenure_months'),
                'loan_close_date'       => date('Y-m-d',strtotime($this->input->post('loan_close_date'))),
               
            );
            $this->staff_model->add_loan($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/staff/staff_loan');
        }
        
    }

    public function staff_advance($id =null)
    {
        if (!$this->rbac->hasPrivilege('staff_advance', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'HR/staff/staff_advance');
        $resultlist         = $this->staff_model->get();
        $data['resultlist'] = $resultlist;
        $stafflist          = $this->staff_model->getAdvanceList();
        $data['stafflist'] = $stafflist;
        $stafflist          = $this->staff_model->getAdvanceList($id);
        $data['update'] = $stafflist;

        $this->form_validation->set_rules('staff_id', "Staff", 'required|trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/staff/staff_advance', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $session_current            = $this->setting_model->getCurrentSession();
            $data = array(
                'id'                    => $this->input->post('id'),
                'staff_id'              => $this->input->post('staff_id'),
                'session_id'            => $session_current,
                'adv_date'              => date('Y-m-d',strtotime($this->input->post('adv_date'))),
                'adv_amount'            => $this->input->post('adv_amount'),
                'adv_remarks'           => $this->input->post('adv_remarks'),
               
            );
            $this->staff_model->add_advance($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/staff/staff_advance');
        }
       
    }

    public function monthly_working_days()
    {
        if (!$this->rbac->hasPrivilege('monthly_working_days', 'can_edit')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'Academics/monthly_working_days');
       
        $classlist = $this->class_model->get();
        $data['classlist'] = $classlist;

        $sectionlist = $this->section_model->get();
        $data['sectionlist'] = $sectionlist;

        $this->form_validation->set_rules('month', $this->lang->line('month'), 'trim|xss_clean');
        $this->form_validation->set_rules('working_days', 'Working Days', 'trim|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('working_entry/monthly_working_days', $data);
            $this->load->view('layout/footer', $data);
        } else {
            // $id = $this->input->post('id[]');
            $month = $this->input->post('month[]');
            // $working_days = $this->input->post('working_days[]');
            $i = 0;
            $dataArr = [];
            $session_current       = $this->setting_model->getCurrentSession();
            foreach ($month as $monthRow) {
                $working_days = $this->input->post('working_days[]')[$i];
                $id = $this->input->post('id[]')[$i];

                $data = array(
                    'id' => $id,
                    'session_id' => $session_current,
                    'month' => $monthRow,
                    'working_days' => $working_days,
                );
                $dataArr[] = $data;
                $i++;
            }
            if (empty($id)) {
                $this->staff_model->addmonthworkAtt($dataArr);
            }else {
                // echo "<pre>";
                // print_r($dataArr);
                // echo "else";die;
                $this->staff_model->updatemonthworkAtt($dataArr);
            }
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/staff/monthly_working_days');
        }

    }

    public function printStaff($id = null)
    {
        $id                    = $this->input->post('staff_id');
        $staff                       = $this->staff_model->getAll($id);
        $data['staff']               = $staff;
        $data['staff_profile']    = $this->staff_model->get_profiledata($id);
        $staff_leaves          = $this->leaverequest_model->staff_leave_request($id);
        $alloted_leavetype     = $this->staff_model->allotedLeaveType($id);
        $data['settinglist']   = $this->setting_model->get();
        $data['sch_setting']   = $this->sch_setting_detail;
       
        $this->load->view('admin/staff/staff_print', $data);
        
    }

    public function saveStaffleaveopening()
    {
        $this->form_validation->set_error_delimiters('<p>', '</p>');
        $this->form_validation->set_rules('opening[]', $this->lang->line('leave'), 'required|trim|numeric|xss_clean');
        if ($this->form_validation->run() == false) {
            $array = array(
                'opening'   => form_error('opening[]'),
                
            );
            $array = array('status' => 0, 'error' => $array);
            echo json_encode($array);
        } else {

            
            $leaves_type_id  = $this->input->post('leaves_type_id[]');
            $i=0;
            foreach ($leaves_type_id as $key => $leave) {
                
                $array = array(
                    'id'=> $this->input->post('id')[$i],
                    'staff_id'=> $this->input->post('staff_id'),
                    'session_id'=> $this->setting_model->getCurrentSession(),
                    'leave_type_id'=> $leave,
                    'opening'=> $this->input->post('opening')[$i],
                );
                
                $this->staff_model->addStaffLeaveOp($array);

                $i++;
            }
            $array              = array('status' => '1', 'error' => '');
            echo json_encode($array);
        }
    }

    // function for staff leave format
    public function staff_leave_format()
    {
        $this->load->helper('download');
        $filepath = "./backend/import/import_staff_leave_sample_file.csv";
        $data     = file_get_contents($filepath);
        $name     = 'import_staff_leave_sample_file.csv';

        force_download($name, $data);
    }

    public function staff_leave_upload()
    {
        if (!$this->rbac->hasPrivilege('staff_leave_upload', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'HR/staff/staff_leave_upload');
        $data['title']      = 'Staff Leave Upload';
        $data['title_list'] = 'Staff Leave Upload';
        $session            = $this->setting_model->getCurrentSession();
        $user_type = $this->staff_model->getStaffRole();
        $data['stafflist'] = $user_type;
        $data['monthlist']           = $this->customlib->getMonthDropdown();
        $userdata           = $this->customlib->getUserData();

        $data['class_id'] = "";
        $data['section_id'] = "";
        $data['date'] = "";
        $data["year"]                = date("Y");
        $attendencetypes = $this->attendencetype_model->getStaffAttendanceType();
        $data['attendencetypeslist'] = $attendencetypes;

        $user_type_id = $this->input->post('user_id');
        $data["user_type_id"] = $user_type_id;
        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_csv_upload');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/staff/import/staff_leave_upload', $data);
            $this->load->view('layout/footer', $data);
        } else {

            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                if ($ext == 'csv') {
                    $file = $_FILES['file']['tmp_name'];
                    $this->load->library('CSVReader');
                    $result = $this->csvreader->parse_file($file);
                    if (!empty($result)) {
                        $i = 0;
                        foreach ($result as $key => $value) {
                            $array_key = array_keys($value);
                            
                            $staffRow = $this->staff_model->getStaffIdByBiometricId($value['biometric_id']);
                            
                            if (!empty($staffRow)) {
                            
                                foreach ($value as $key =>  $val) {
                                    
                                    if ($key != "biometric_id") {
                                        $leave_type_id = $this->leavetypes_model->getLeavetypesBytype($key);
                                        $data2 = [
                                            "staff_id" => $staffRow['id'],
                                            "leave_type_id" => $leave_type_id,
                                            "alloted_leave" => $val,
                                        ];
                                        
                                        $this->staff_model->update_leave_detail($data2);
                                    }
                                }

                                $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">' . $this->lang->line('success_message') . '</div>');

                            }else {

                                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Biometric id not found</div>');
                            }

                            
                        }
                    } else {

                        $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">' . $this->lang->line('no_record_found') . '</div>');
                    }
                }else {

                    $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">' . $this->lang->line('please_upload_CSV_file_only') . '</div>');
                }
            }

            redirect('admin/staff/staff_leave_upload');
        }
    }

    public function staff_deduction($id = "")
    {
        if (!$this->rbac->hasPrivilege('staff_deduction', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'HR/staff/staff_deduction');
        $resultlist         = $this->staff_model->get();
        $data['resultlist'] = $resultlist;
        $stafflist          = $this->staff_model->getDeductionList();
        $data['stafflist'] = $stafflist;
        $stafflist          = $this->staff_model->getDeductionList($id);
        $data['update'] = $stafflist;
        $deductionArr   = $this->staff_model->getDeductionTypeList();
        $data['deductionArr'] = $deductionArr;
        $data["year"]                = date("Y");
        $data['monthlist']           = $this->customlib->getMonthDropdown();
        $submit                      = $this->input->post("search");
        if (isset($submit) && $submit == "search") {
            $data["month"]               = $this->input->post("month");
            $data["year"]                = $this->input->post("year");
            $stafflist          = $this->staff_model->getDeductionList(null,$data["month"], $data["year"]);
            $data['stafflist'] = $stafflist;
        }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/staff/staff_deduction', $data);
        $this->load->view('layout/footer', $data);
        // $this->form_validation->set_rules('staff_id', "Staff", 'required|trim|xss_clean');
        // $this->form_validation->set_rules('ded_date', " Date", 'required|trim|xss_clean');
        // $this->form_validation->set_rules('ded_amount', "Amount", 'required|trim|xss_clean');
        // $this->form_validation->set_rules('ded_type', "Type", 'required|trim|xss_clean');
        // $this->form_validation->set_rules('remarks', "remarks", 'trim|xss_clean');
        // if ($this->form_validation->run() == FALSE) {
        //     $this->load->view('layout/header', $data);
        //     $this->load->view('admin/staff/staff_deduction', $data);
        //     $this->load->view('layout/footer', $data);
        // } else {
        //     $session_current       = $this->setting_model->getCurrentSession();
        //     $data = array(
        //         'id'                    => $this->input->post('id'),
        //         'staff_id'              => $this->input->post('staff_id'),
        //         'ded_date'              => date('Y-m-d',strtotime($this->input->post('ded_date'))),
        //         'ded_amount'            => $this->input->post('ded_amount'),
        //         'ded_type'              => $this->input->post('ded_type'),
        //         'remarks '             => $this->input->post('remarks'),
               
        //     );
        //     $this->staff_model->add_deduction($data);
        //     $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
        //     redirect('admin/staff/staff_deduction');
        // }
        
    }
    public function staff_deduction_validation()
    {
        $this->form_validation->set_rules('staff_id', "Staff", 'required|trim|xss_clean');
        $this->form_validation->set_rules('ded_date', " Date", 'required|trim|xss_clean');
        $this->form_validation->set_rules('ded_amount', "Amount", 'required|trim|xss_clean');
        $this->form_validation->set_rules('ded_type', "Type", 'required|trim|xss_clean');
        $this->form_validation->set_rules('remarks', "remarks", 'trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $resultlist         = $this->staff_model->get();
            $data['resultlist'] = $resultlist;
            $stafflist          = $this->staff_model->getDeductionList();
            $data['stafflist'] = $stafflist;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/staff/staff_deduction', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $session_current       = $this->setting_model->getCurrentSession();
            $data = array(
                'id'                    => $this->input->post('id'),
                'staff_id'              => $this->input->post('staff_id'),
                'ded_date'              => date('Y-m-d',strtotime($this->input->post('ded_date'))),
                'ded_amount'            => $this->input->post('ded_amount'),
                'ded_type'              => $this->input->post('ded_type'),
                'remarks '             => $this->input->post('remarks'),
               
            );
            $this->staff_model->add_deduction($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/staff/staff_deduction');
        }
    }
    public function delete_deduction($id)
    {
        if (!$this->rbac->hasPrivilege('staff_deduction', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Staff Deduction ';
        $delete_array = [
            'id' => $id,
            'status' => 'Cancelled',
            'deleted_at' => date('Y-m-d H:i:s'),
            'deleted_by' => $this->customlib->getStaffID(),
        ];
        $this->staff_model->remove_deduction($delete_array);
        redirect('admin/staff/staff_deduction');
    }

    public function staff_deduction_type($id="")
    {
        ini_set('display_errors', '1');

        if (!$this->rbac->hasPrivilege('staff_deduction_type', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'HR/staff/staff_deduction_type');
        $this->data = "";

        if ($id != "") {
            $data['update']  = $this->staff_model->getDeductionTypeList($id);
        }
        $this->form_validation->set_rules('name', "Name", 'required|trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
        } else {
            $userdata           = $this->customlib->getUserData();
            
            $insert_array = array(
                'id' => $this->input->post('id'),
                'name' => $this->input->post('name'),
                'type' => 'D'
            );

            $this->staff_model->add_deduction_type($insert_array);

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/staff/staff_deduction_type');
        }

        $data['resultlist'] = $this->staff_model->getDeductionTypeList();



        $this->load->view('layout/header');
        $this->load->view('admin/staff/staff_deduction_type', $data);
        $this->load->view('layout/footer');
    }

    public function delete_staff_deduction_type($id)
    {
        if (!$this->rbac->hasPrivilege('staff_deduction_type', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Staff Deduction List';
        $array = [
            'id' => $id,
            'status' => 'Cancelled',
            'deleted_at' => date('Y-m-d H:i:s'),
            'deleted_by' => $this->customlib->getStaffID()
        ];
        $this->staff_model->remove_deduction_type($array);
        redirect('admin/staff/staff_deduction_type');
    }

    public function staff_addition_type($id="")
    {
        ini_set('display_errors', '1');

        if (!$this->rbac->hasPrivilege('staff_addition_type', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'HR/staff/staff_addition_type');
        $this->data = "";

        if ($id != "") {
            $data['update']  = $this->staff_model->getAdditionTypeList($id);
        }
        $this->form_validation->set_rules('name', "Name", 'required|trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
        } else {
            $userdata           = $this->customlib->getUserData();
            
            $insert_array = array(
                'id' => $this->input->post('id'),
                'name' => $this->input->post('name'),
                'type' => 'A'
            );

            $this->staff_model->add_deduction_type($insert_array);

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/staff/staff_addition_type');
        }

        $data['resultlist'] = $this->staff_model->getAdditionTypeList();



        $this->load->view('layout/header');
        $this->load->view('admin/staff/staff_addition_type', $data);
        $this->load->view('layout/footer');
    }

    public function delete_staff_addition_type($id)
    {
        if (!$this->rbac->hasPrivilege('staff_addition_type', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Staff Addition List';
        $array = [
            'id' => $id,
            'status' => 'Cancelled',
            'deleted_at' => date('Y-m-d H:i:s'),
            'deleted_by' => $this->customlib->getStaffID()
        ];
        $this->staff_model->remove_deduction_type($array);
        redirect('admin/staff/staff_addition_type');
    }

    public function staff_addition($id = "")
    {
        ini_set('display_errors', '1');
        if (!$this->rbac->hasPrivilege('staff_addition', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'HR/staff/staff_addition');
        $resultlist         = $this->staff_model->get();
        $data['resultlist'] = $resultlist;
        $stafflist          = $this->staff_model->getAdditionList();
        $data['stafflist'] = $stafflist;
        $stafflist          = $this->staff_model->getAdditionList($id);
        $data['update'] = $stafflist;
        $deductionArr   = $this->staff_model->getAdditionTypeList();
        $data['deductionArr'] = $deductionArr;
        $data["year"]                = date("Y");
        $data['monthlist']           = $this->customlib->getMonthDropdown();
        $submit                      = $this->input->post("search");
        if (isset($submit) && $submit == "search") {
            $data["month"]               = $this->input->post("month");
            $data["year"]                = $this->input->post("year");
            $stafflist          = $this->staff_model->getAdditionList(null,$data["month"], $data["year"]);
            $data['stafflist'] = $stafflist;
        }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/staff/staff_addition', $data);
        $this->load->view('layout/footer', $data);

        
        
    }
    public function staff_addition_validation()
    {
        $this->form_validation->set_rules('staff_id', "Staff", 'required|trim|xss_clean');
        $this->form_validation->set_rules('add_date', " Date", 'required|trim|xss_clean');
        $this->form_validation->set_rules('add_amount', "Amount", 'required|trim|xss_clean');
        $this->form_validation->set_rules('add_type', "Type", 'required|trim|xss_clean');
        $this->form_validation->set_rules('remarks', "remarks", 'trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $resultlist         = $this->staff_model->get();
            $data['resultlist'] = $resultlist;
            $stafflist          = $this->staff_model->getAdditionList();
            $data['stafflist'] = $stafflist;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/staff/staff_addition', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $session_current       = $this->setting_model->getCurrentSession();
            $data = array(
                'id'                    => $this->input->post('id'),
                'staff_id'              => $this->input->post('staff_id'),
                'add_date'              => date('Y-m-d',strtotime($this->input->post('add_date'))),
                'add_amount'            => $this->input->post('add_amount'),
                'add_type'              => $this->input->post('add_type'),
                'remarks '             => $this->input->post('remarks'),
               
            );
            $this->staff_model->add_addition($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/staff/staff_addition');
        }
        
        
    }

    public function delete_addition($id)
    {
        if (!$this->rbac->hasPrivilege('staff_addition', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Staff Addition List';
        $delete_array = [
            'id' => $id,
            'status' => 'Cancelled',
            'deleted_at' => date('Y-m-d H:i:s'),
            'deleted_by' => $this->customlib->getStaffID(),
        ];
        $this->staff_model->remove_addition($delete_array);
        redirect('admin/staff/staff_addition');
    }

    public function leaverequest_office()
    {

        if (!$this->rbac->hasPrivilege('apply_leave_office', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/staff/leaverequest_office');
        $userdata              = $this->customlib->getUserData();
        $leave_request         = $this->leaverequest_model->staff_leave_request_all();
        $data["leave_request"] = $leave_request;
        // $data["staff_id"]      = $userdata["id"];
        $staffRole             = $this->staff_model->getStaffRole();
        $data["staffrole"]     = $staffRole;
        $data["status"]        = $this->status;
        $resultlist         = $this->staff_model->getstaffByASC();
        $data['stafflist'] = $resultlist;
        $LeaveTypes = $this->leavetypes_model->getLeaveType();
        $data["leavetype"] = $LeaveTypes;
        $data["year"]                = date("Y");
        $data['monthlist']           = $this->customlib->getMonthDropdown();
        $submit                      = $this->input->post("search");
        if (isset($submit) && $submit == "search") {
            $data["month"]               = $this->input->post("month");
            $data["year"]                = $this->input->post("year");
            $leave_request         = $this->leaverequest_model->staff_leave_request_all($data["month"], $data["year"]);
            $data["leave_request"] = $leave_request;
        }
        $this->load->view("layout/header", $data);
        $this->load->view("admin/staff/leaverequest_office", $data);
        $this->load->view("layout/footer", $data);
    }
    
    // public function staff_updation()
    // {
    //     // $this->db->query("SELECT * FROM staff where id not in (select staff_id from staff_session) and is_active = 1");
    //     $result = $this->db->query("SELECT id FROM staff where id not in (select staff_id from staff_session where session_id = 21) and staff.is_active = 1");

    //     foreach($result->result_array() as $row){
    //         $staffSessionRow = $this->db->query("SELECT * FROM staff_session where staff_id = ".$row['id']." and session_id = 20")->row_array();
    //         // echo "<pre>";
    //         // print_r($staffSessionRow);

    //         if(!empty($staffSessionRow)){
    //             $existingArray = (array) $staffSessionRow;
    //             $existingArray['session_id'] = 21;
    //             unset($existingArray['id']);
    //             echo "<pre>";
    //             print_r($existingArray);
    //             $this->db->insert('staff_session', $existingArray);
    //         }
    //     }
    //     echo "done";
    // }
}
