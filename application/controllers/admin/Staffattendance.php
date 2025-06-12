<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Staffattendance extends Admin_Controller {

    function __construct() {

        parent::__construct();
        $this->load->helper('file');

        $this->config->load("mailsms");
        $this->config->load("payroll");
        $this->load->library('mailsmsconf');
        $this->config_attendance = $this->config->item('attendence');
        $this->staff_attendance = $this->config->item('staffattendance');
        $this->load->model("staffattendancemodel");
        $this->load->model("staff_model");
        $this->load->model("payroll_model");
    }

    function index() {

        if (!($this->rbac->hasPrivilege('staff_attendance', 'can_view') )) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/staffattendance');
        $data['title'] = 'Staff Attendance List';
        $data['title_list'] = 'Staff Attendance List';
        $user_type = $this->staff_model->getStaffRole();
        $data['classlist'] = $user_type;
        $data['class_id'] = "";
        $data['section_id'] = "";
        $data['date'] = "";
        $user_type_id = $this->input->post('user_id');
        $data["user_type_id"] = $user_type_id;
        if (!(isset($user_type_id))) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/staffattendance/staffattendancelist', $data);
            $this->load->view('layout/footer', $data);
        } else {

            $user_type = $this->input->post('user_id');
            $date = $this->input->post('date');
            $user_list = $this->staffattendancemodel->get();
            $data['userlist'] = $user_list;
            $data['class_id'] = $user_list;
            $data['user_type_id'] = $user_type_id;
            $data['section_id'] = "";
            $data['date'] = $date;
            $search = $this->input->post('search');
            $holiday = $this->input->post('holiday');
            $this->session->set_flashdata('msg', '');
            if ($search == "saveattendence") {
                $user_type_ary = $this->input->post('student_session');
                $absent_student_list = array();
                foreach ($user_type_ary as $key => $value) {
                    $checkForUpdate = $this->input->post('attendendence_id' . $value);
                    if ($checkForUpdate != 0) {
                        if (isset($holiday)) {
                            $arr = array(
                                'id' => $checkForUpdate,
                                'staff_id' => $value,
                                'staff_attendance_type_id' => 5,
                                'remark' => $this->input->post("remark" . $value),
                                'date' => date('Y-m-d', $this->customlib->datetostrtotime($date))
                            );
                        } else {
                            $arr = array(
                                'id' => $checkForUpdate,
                                'staff_id' => $value,
                                'staff_attendance_type_id' => $this->input->post('attendencetype' . $value),
                                'remark' => $this->input->post("remark" . $value),
                                'date' => date('Y-m-d', $this->customlib->datetostrtotime($date))
                            );
                        }

                        $insert_id = $this->staffattendancemodel->add($arr);
                    } else {
                        if (isset($holiday)) {
                            $arr = array(
                                'staff_id' => $value,
                                'staff_attendance_type_id' => 5,
                                'date' => date('Y-m-d', $this->customlib->datetostrtotime($date)),
                                'remark' => ''
                            );
                        } else {
                            $arr = array(
                                'staff_id' => $value,
                                'staff_attendance_type_id' => $this->input->post('attendencetype' . $value),
                                'date' => date('Y-m-d', $this->customlib->datetostrtotime($date)),
                                'remark' => $this->input->post("remark" . $value),
                            );
                        }
                        $insert_id = $this->staffattendancemodel->add($arr);
                        $absent_config = $this->config_attendance['absent'];
                        if ($arr['staff_attendance_type_id'] == $absent_config) {
                            $absent_student_list[] = $value;
                        }
                    }
                }

                $absent_config = $this->config_attendance['absent'];
                if (!empty($absent_student_list)) {

                    $this->mailsmsconf->mailsms('absent_attendence', $absent_student_list, $date);
                }
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
                redirect('admin/staffattendance/index');
            }

            $attendencetypes = $this->attendencetype_model->getStaffAttendanceType();
            $data['attendencetypeslist'] = $attendencetypes;
            $resultlist = $this->staffattendancemodel->searchAttendenceUserType($user_type, date('Y-m-d', $this->customlib->datetostrtotime($date)));
            $data['resultlist'] = $resultlist;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/staffattendance/staffattendancelist', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    function attendancereport() {

        if (!$this->rbac->hasPrivilege('staff_attendance_report', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/attendance');
        $this->session->set_userdata('subsub_menu', 'Reports/attendance/staff_attendance_report');
        $attendencetypes = $this->staffattendancemodel->getStaffAttendanceType();
        $data['attendencetypeslist'] = $attendencetypes;
        $staffRole = $this->staff_model->getStaffRole();
        $data["role"] = $staffRole;
        $data['title'] = 'Attendance Report';
        $data['title_list'] = 'Attendance';
        $data['monthlist'] = $this->customlib->getMonthDropdown();
        $data['yearlist'] = $this->staffattendancemodel->attendanceYearCount();
        $data['date'] = "";
        $data['month_selected'] = "";
        $data["role_selected"] = "";
        $role = $this->input->post("role");
        $this->form_validation->set_rules('month', $this->lang->line('month'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('year', $this->lang->line('year'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {

            $this->load->view('layout/header', $data);
            $this->load->view('admin/staffattendance/attendancereport', $data);
            $this->load->view('layout/footer', $data);
        } else {

            $resultlist = array();
            $month = $this->input->post('month');
            $searchyear = $this->input->post('year');
            $data['month_selected'] = $month;
            $data["role_selected"] = $role;
            $stafflist = $this->staff_model->getEmployee($role);
            $session_current = $this->setting_model->getCurrentSessionName();
            $startMonth = $this->setting_model->getStartMonth();
            $centenary = substr($session_current, 0, 2); //2017-18 to 2017
            $year_first_substring = substr($session_current, 2, 2); //2017-18 to 2017
            $year_second_substring = substr($session_current, 5, 2); //2017-18 to 18
            $month_number = date("m", strtotime($month));

            if ($month_number >= $startMonth && $month_number <= 12) {
                $year = $centenary . $year_first_substring;
            } else {
                $year = $centenary . $year_second_substring;
            }

            $num_of_days = cal_days_in_month(CAL_GREGORIAN, $month_number, $searchyear);
            $attr_result = array();
            $attendence_array = array();
            $student_result = array();
            $data['no_of_days'] = $num_of_days;
            $date_result = array();
            $monthAttendance = array();

            for ($i = 1; $i <= $num_of_days; $i++) {
                $att_date = $searchyear . "-" . $month_number . "-" . sprintf("%02d", $i);
                $attendence_array[] = $att_date;

                $res = $this->staffattendancemodel->searchAttendanceReport($role, $att_date);


                $student_result = $res;
                $s = array();

                foreach ($res as $result_k => $result_v) {

                    $date = $searchyear . "-" . $month;
                    $newdate = date('Y-m-d', strtotime($date));

                    $s[$result_v['id']] = $result_v;
                }

                $date_result[$att_date] = $s;
            }

            foreach ($res as $result_k => $result_v) {

                $date = $searchyear . "-" . $month;
                $newdate = date('Y-m-d', strtotime($date));
                $monthAttendance[] = $this->monthAttendance($newdate, 1, $result_v['id']);
            }

            $data['monthAttendance'] = $monthAttendance;
            $data['resultlist'] = $date_result;
            if (!empty($searchyear)) {
                $data['attendence_array'] = $attendence_array;
                $data['student_array'] = $student_result;
            } else {

                $data['attendence_array'] = array();
                $data['student_array'] = array();
            }
 
            $this->load->view('layout/header', $data);
            $this->load->view('admin/staffattendance/attendancereport', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    function monthAttendance($st_month, $no_of_months, $emp) {

        $this->load->model("payroll_model");
        $record = array();

        $r = array();
        $month = date('m', strtotime($st_month));
        $year = date('Y', strtotime($st_month));

        foreach ($this->staff_attendance as $att_key => $att_value) {

            $s = $this->payroll_model->count_attendance_obj($month, $year, $emp, $att_value);

            $r[$att_key] = $s;
        }

        $record[$emp] = $r;

        return $record;
    }

    function profileattendance() {

        $monthlist = $this->customlib->getMonthDropdown();
        $startMonth = $this->setting_model->getStartMonth();
        $data["monthlist"] = $monthlist;
        $data['yearlist'] = $this->staffattendancemodel->attendanceYearCount();
        $staffRole = $this->staff_model->getStaffRole();
        $data["role"] = $staffRole;
        $data["role_selected"] = "";
        $j = 0;
        for ($i = 1; $i <= 31; $i++) {

            $att_date = sprintf("%02d", $i);

            $attendence_array[] = $att_date;

            foreach ($monthlist as $key => $value) {

                $datemonth = date("m", strtotime($value));
                $att_dates = date("Y") . "-" . $datemonth . "-" . sprintf("%02d", $i);
                $date_array[] = $att_dates;
                $res[$att_dates] = $this->staffattendancemodel->searchStaffattendance($att_dates, $staff_id = 8);
            }

            $j++;
        }

        $data["resultlist"] = $res;
        $data["attendence_array"] = $attendence_array;
        $data["date_array"] = $date_array;

        $this->load->view("layout/header");
        $this->load->view("admin/staff/staffattendance", $data);
        $this->load->view("layout/footer");
    }

    function staff_monthly_attendance() {

        if (!($this->rbac->hasPrivilege('staff_monthly_attendance', 'can_view') )) {
            access_denied();
        }
        // ini_set('display_errors', 1);
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/staffattendance/staff_monthly_attendance');
        $data['title'] = 'Staff Attendance List';
        $data['title_list'] = 'Staff Attendance List';
        $user_type = $this->staff_model->getStaffRole();
        $data['stafflist'] = $user_type;
        $department                  = $this->staff_model->getDepartment();
        $data["department"]          = $department;
        $data['monthlist']           = $this->customlib->getMonthDropdown();
        $data['class_id'] = "";
        $data['section_id'] = "";
        $data['date'] = "";
        $data["year"]                = date("Y");
        $data['Payroll_set'] = $Payroll_set = $this->db->where('status', 'active')->get('payroll_settings')->row_array();
        $user_type_id = $this->input->post('role');
        $data["user_type_id"] = $user_type_id;
        $role = $this->input->post('role');
        if (!(isset($user_type_id))) {
            $data["month"]               = date("F", strtotime("-1 month"));
            // $this->load->view('layout/header', $data);
            // $this->load->view('admin/staffattendance/staff_monthly_attendance', $data);
            // $this->load->view('layout/footer', $data);
        } else {

            $user_type = $this->input->post('role');
            $data['selectedmonth'] = $selectMonth = $month = $this->input->post('month');
            $data['selectedyear'] = $year = $this->input->post('year');
            $staff_id = $this->input->post('staff_id');
            $previous_month = date('F', strtotime('-1 month', strtotime($month)));
            $data['previous_month'] = $previous_month;

            if (!empty($staff_id)) {
                $i= 0;
                foreach ($staff_id as  $value) {
                //    $left_cl = $this->input->post('left_cl')[$i] - $this->input->post('cl')[$i];
                //    $left_el = $this->input->post('left_el')[$i] - $this->input->post('el')[$i];
                //    $left_sl = $this->input->post('left_sl')[$i] - $this->input->post('sl')[$i];
                //    $left_ml = $this->input->post('left_ml')[$i] - $this->input->post('ml')[$i];
                //    $left_lwp = $this->input->post('left_lwp')[$i] - $this->input->post('lwp')[$i];

                    $arrayData = array(
                        // 'id'=> $this->input->post('id')[$i],
                        'staff_id'=> $value,
                        'month'=> $this->input->post('selectedmonth'),
                        'year'=> $this->input->post('selectedyear'),
                        'total_working_days'=> $this->input->post('total_working_days')[$i],
                        'total_attendence'=> $this->input->post('total_attendence')[$i],
                        'cl'=> $this->input->post('cl')[$i],
                        'el'=> $this->input->post('el')[$i],
                        'sl'=> $this->input->post('sl')[$i],
                        'ml'=> $this->input->post('ml')[$i],
                        'lwp'=> $this->input->post('lwp')[$i],
                        'comp_off'=> $this->input->post('comp_off')[$i],
                        'session_id'=>  $this->setting_model->getCurrentSession(),
                        // 'left_cl'=> $left_cl,
                        // 'left_el'=> $left_el,
                        // 'left_sl'=> $left_sl,
                        // 'left_ml'=> $left_ml,
                        // 'left_lwp'=> $left_lwp
                    );
                   
                    $this->staffattendancemodel->addmonthattendence($arrayData);

                    $i++;
                }
            }
            
            $this->session->set_flashdata('msg', '<div class="alert alert-success">Attendance added successfully</div>');
            
        }

        $attendencetypes = $this->attendencetype_model->getStaffAttendanceType();
        $data['attendencetypeslist'] = $attendencetypes;
        // $resultlist = $this->staffattendancemodel->searchAttendencemonthly($user_type_id);
        $resultlist = $this->staffattendancemodel->searchAttendencemonthlyByDepartment($role);

        $data['resultlist'] = $resultlist;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/staffattendance/staff_monthly_attendance', $data);
        $this->load->view('layout/footer', $data);
    }

    public function exportformat()
    {
        $this->load->helper('download');
        $filepath = "./backend/import/import_staff_attendance_sample_file.csv";
        $data     = file_get_contents($filepath);
        $name     = 'import_staff_attendance_sample_file.csv';

        force_download($name, $data);
    }

    public function excel()
    {
        if (!$this->rbac->hasPrivilege('excel', 'can_view')) {
            access_denied();
        }
        $data['title']      = 'Excel Upload';
        $data['title_list'] = 'Excel Upload';
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
            $this->load->view('admin/staffattendance/excel', $data);
            $this->load->view('layout/footer', $data);
        } else {

            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                if ($ext == 'csv') {
                    $file = $_FILES['file']['tmp_name'];
                    $this->load->library('CSVReader');
                    $result = $this->csvreader->parse_file($file);
                    
                    // echo "<pre>";
                    // print_r ($result);die;
                    // echo "</pre>";
                    
                    if (!empty($result)) {
                        $i = 0;
                        // $attendence_data = array();
                        foreach ($result as $key => $value) {
                            
                            // echo "<pre>";
                            // print_r ($value);
                            // echo "</pre>";
                            

                            $staffRow = $this->staff_model->getStaffIdByBiometricId($value['biometric_id']);
                            if (!empty($staffRow)) {
                                $attendence_data = [
                                    "staff_id" => $staffRow['id'],
                                    "month" => $value['month'],
                                    "year" => $value['year'],
                                    "ml" => $value['ml'],
                                    "cl" => $value['cl'],
                                    "el" => $value['el'],
                                    "lwp" => $value['lwp'],
                                ];
                                $this->staffattendancemodel->addmonthlyattendence($attendence_data);
                                // echo "<pre>";
                                // print_r ($attendence_data);
                                // echo "</pre>";
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

            redirect('admin/staffattendance/excel');
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

    public function leave_outstanding()
    {
        if (!$this->rbac->hasPrivilege('leave_outstanding', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'HR/staffattendence/leave_outstanding');
        $data['title'] = $this->lang->line('leave_outstanding');

        $data['title_list'] = 'Staff Attendance List';
        $user_type = $this->staff_model->getStaffRole();
        $data['stafflist'] = $user_type;
        $department                  = $this->staff_model->getDepartment();
        $data["department"]          = $department;
        $user_type_id = $this->input->post('role');
        $data["user_type_id"] = $user_type_id;
        $role = $this->input->post('role');
        if (!(isset($user_type_id))) {
            $data["month"]               = date("F", strtotime("-1 month"));
            // $this->load->view('layout/header', $data);
            // $this->load->view('admin/staffattendance/staff_monthly_attendance', $data);
            // $this->load->view('layout/footer', $data);
        } else {

            $resultlist = $this->staffattendancemodel->searchAttendencemonthlyByDepartment($role);
            $data['resultlist'] = $resultlist;
            
            
            
        }

        $attendencetypes = $this->attendencetype_model->getStaffAttendanceType();
        $data['attendencetypeslist'] = $attendencetypes;
        // $resultlist = $this->staffattendancemodel->searchAttendencemonthly($user_type_id);
        $this->load->view('layout/header', $data);
        $this->load->view('admin/staffattendance/leave_outstanding', $data);
        $this->load->view('layout/footer', $data);
    }

    public function delete_monthly_attendence()
    {
        $month = $this->input->post('month');
        $year = $this->input->post('year');
        $this->staffattendancemodel->delete_monthly_attendence($month, $year);

        $json = array(
            "success" => "Data Deleted Successfully!!!!",
        );

        echo json_encode($json);
    }

    public function staff_percent_days()
    {
        if (!$this->rbac->hasPrivilege('staff_percent_days', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'HR/staffattendence/staff_percent_days');
        $data['title'] = "Staff List";
        $data['title_list'] = 'Staff List';

        $user_type = $this->staff_model->getStaffRole();
        $data['stafflist'] = $user_type;
        $department                  = $this->staff_model->getDepartment();
        $data["department"]          = $department;
        $data['monthlist']           = $this->customlib->getMonthDropdown();
        $data['class_id'] = "";
        $data['section_id'] = "";
        $data['date'] = "";
        $data["year"]                = date("Y");
        $data['Payroll_set'] = $Payroll_set = $this->db->where('status', 'active')->get('payroll_settings')->row_array();
        $user_type_id = $this->input->post('role');
        $data["user_type_id"] = $user_type_id;
        $role = $this->input->post('role');
        if (!(isset($user_type_id))) {
            $data["month"]               = date("F", strtotime("-1 month"));
            // $this->load->view('layout/header', $data);
            // $this->load->view('admin/staffattendance/staff_monthly_attendance', $data);
            // $this->load->view('layout/footer', $data);
        } else {
            $user_type = $this->input->post('role');
            $data['selectedmonth'] = $selectMonth = $month = $this->input->post('month');
            $data['selectedyear'] = $year = $this->input->post('year');
            $staff_id = $this->input->post('staff_id');
            $previous_month = date('F', strtotime('-1 month', strtotime($month)));
            $data['previous_month'] = $previous_month;

            
            if (!empty($staff_id)) {
                $i= 0;
                foreach ($staff_id as  $value) {
                    $arrayData = array(
                        'staff_id' => $value,
                        'month' => $this->input->post('selectedmonth'),
                        'year' => $this->input->post('selectedyear'),
                        'percent_days' => $this->input->post('percent_days')[$i],
                    );
                    // echo "<pre>";
                    // print_r($arrayData);die;
                    // echo "</pre>";
                    $this->staffattendancemodel->addpercentdays($arrayData);
                    $i++;
                }
            }

        }
        $attendencetypes = $this->attendencetype_model->getStaffAttendanceType();
        $data['attendencetypeslist'] = $attendencetypes;
        $month_year = date('m', strtotime($this->input->post('month'))) . '-' . $this->input->post('year');
        // echo $this->input->post('month');die;
        // $resultlist = $this->staffattendancemodel->searchAttendencemonthly($user_type_id);
        $resultlist = $this->staffattendancemodel->searchAttendencemonthlyByDepartment($role,$month_year);

        $data['resultlist'] = $resultlist;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/staffattendance/staff_percent_days', $data);
        $this->load->view('layout/footer', $data);
    }

    public function staff_leave_opening()
    {
        if (!($this->rbac->hasPrivilege('staff_leave_opening', 'can_view') )) {
            access_denied();
        }
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/staffattendance/staff_leave_opening');
        $data['title'] = "Staff Leave Opening";
        $data['title_list'] = "Staff Leave Opening";
        $department                  = $this->staff_model->getDepartment();
        $data["department"]          = $department;
        $role = $this->input->post('role');
        $data["role"] = $role;
        $submit = $this->input->post('search');

        if ($submit == 'search') {
            $resultlist = $this->staffattendancemodel->searchAttendencemonthlyByDepartment($role);
            $data['resultlist'] = $resultlist;
        }elseif($submit == 'saveattendence'){
           
            $staff_ids = $this->input->post('staff_id');
            $opening = $this->input->post('opening');
            $leave_type_id = $this->input->post('leave_type_id');
            $opening_id = $this->input->post('opening_id');
            $session_current = $this->setting_model->getCurrentSession();
            $arr = array();
            if (!empty($staff_ids)) {
                foreach ($staff_ids as $key => $value) {
                    $staff_id = $value;
                    foreach ($opening[$staff_id] as $openkey => $openval) {
                        $arr[] = array(
                            "staff_id" => $staff_id,
                            "opening" => $openval,
                            "session_id" => $session_current,
                            "leave_type_id" => $leave_type_id[$staff_id][$openkey],
                            "opening_id" => $opening_id[$staff_id][$openkey],
                        );
                    }
                }

                $this->staffattendancemodel->add_opening($arr);
                $this->session->set_flashdata('msg', '<div class="alert alert-success">Opening added successfully</div>');
                // echo "<pre>";
                // print_r ($arr);
                // echo "</pre>";
                
            }

            
        }
        
        
        $LeaveTypes = $this->leavetypes_model->getLeaveTypeActive();
        $data["leavetype"] = $LeaveTypes;
        
        // echo "<pre>";
        // print_r ($resultlist);
        // echo "</pre>";
        
        $this->load->view('layout/header', $data);
        $this->load->view('admin/staffattendance/staff_leave_opening', $data);
        $this->load->view('layout/footer', $data);
    }

}

?>