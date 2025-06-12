<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Stuattendence extends Admin_Controller {

    function __construct() {
        parent::__construct();

        $this->config->load("mailsms");
        $this->load->library('mailsmsconf');
        $this->config_attendance = $this->config->item('attendence');
        $this->load->model("classteacher_model");
         $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    function index() {
        if (!$this->rbac->hasPrivilege('student_attendance', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Attendance');
        $this->session->set_userdata('sub_menu', 'stuattendence/index');
        $data['title'] = 'Add Fees Type';
        $data['title_list'] = 'Fees Type List';
        $sch_setting = $this->setting_model->getSchoolDetail();
        $data['sch_setting'] = $this->sch_setting_detail;
        $userdata = $this->customlib->getUserData();
        $data['custom_date'] = $this->stuattendence_model->getCurrentSessionDates();
        $classlistarray = '';
        $sectionlistarray = '';
        if ($userdata['role_id']==2) {
            $teacherRow = $this->teacher_model->getTecherClass($userdata['id']);
            if (!empty($teacherRow)) {
                $classlistarray = $this->class_model->getClassTeacherArray($teacherRow['class_id']);
                $sectionlistarray = $this->class_model->getsectionTeacherArray($teacherRow['section_id']);
                
            }
            
        } 

        $data['classArray'] = $classlistarray;
        $data['sectionArray'] = $sectionlistarray;
        $class = $this->class_model->get('', $classteacher = 'yes');
        $carray = array();

        if (!empty($data["classlist"])) {
            foreach ($data["classlist"] as $ckey => $cvalue) {

                $carray[] = $cvalue["id"];
            }
        }

        $userdata = $this->customlib->getUserData();
        $role_id = $userdata["role_id"];
        if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if ($userdata["class_teacher"] == 'yes') {
                $carray = array();
                $class = array();
                $class = $this->teacher_model->get_daywiseattendanceclass($userdata["id"]);
            }
        }

        $data['classlist'] = $class;
        $data['class_id'] = "";
        $data['section_id'] = "";
        $data['date'] = "";
        if (!empty($classlistarray) && $userdata['role_id']==2) {
            $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|xss_clean');
            $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|xss_clean');
        } else {
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        }
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/stuattendence/attendenceList', $data);
            $this->load->view('layout/footer', $data);
        } else {
            // $class = $this->input->post('class_id');
            // $section = $this->input->post('section_id');
            if (!empty($classlistarray) && $userdata['role_id']==2) {
                $class = $this->input->post('class_id1');
                $section = $this->input->post('section_id1');
            } else {
                $class = $this->input->post('class_id');
                $section = $this->input->post('section_id');
            }
            
            $date = $this->input->post('date');
            // $student_list = $this->stuattendence_model->get();
            // $data['studentlist'] = $student_list;
            $data['class_id'] = $class;
            $data['section_id'] = $section;
            $data['date'] = $date;
            $search = $this->input->post('search');
            $holiday = $this->input->post('holiday');
            // ini_set('display_errors', 1);
            if ($search == "saveattendence") {
                $session_ary = $this->input->post('student_session');
                $absent_student_list = array();
                if(!empty($session_ary)){
                    foreach ($session_ary as $key => $value) {
                        $checkForUpdate = $this->input->post('attendendence_id' . $value);
                        $student = $this->stuattendence_model->getAttendenceByDate($value);
                        //if (!empty($student) && date('Y-m-d', strtotime($student['admission_date'])) < date('Y-m-d', strtotime($date))) {
                        // if (!empty($student) && date('Y-m-d', strtotime($student['admission_date'])) < date('Y-m-d', strtotime($date))) {
                        if ($checkForUpdate != 0) {
                            if (isset($holiday)) {
                                $arr = array(
                                    'id' => $checkForUpdate,
                                    'student_session_id' => $value,
                                    'attendence_type_id' => 5,
                                    'remark' => $this->input->post("remark" . $value),
                                    'date' => date('Y-m-d', $this->customlib->datetostrtotime($date))
                                );
                            } else {
                                $arr = array(
                                    'id' => $checkForUpdate,
                                    'student_session_id' => $value,
                                    'attendence_type_id' => $this->input->post('attendencetype' . $value),
                                    'remark' => $this->input->post("remark" . $value),
                                    'date' => date('Y-m-d', $this->customlib->datetostrtotime($date))
                                );
                            }
                            $insert_id = $this->stuattendence_model->add($arr);
                        } else {
                            if (isset($holiday)) {
                                $arr = array(
                                    'student_session_id' => $value,
                                    'attendence_type_id' => 5,
                                    'remark' => $this->input->post("remark" . $value),
                                    'date' => date('Y-m-d', $this->customlib->datetostrtotime($date))
                                );
                            } else {


                                $arr = array(
                                    'student_session_id' => $value,
                                    'attendence_type_id' => $this->input->post('attendencetype' . $value),
                                    'remark' => $this->input->post("remark" . $value),
                                    'date' => date('Y-m-d', $this->customlib->datetostrtotime($date))
                                );
                            }
                            
                            $insert_id = $this->stuattendence_model->add($arr);
                            $absent_config = $this->config_attendance['absent'];
                            if ($arr['attendence_type_id'] == $absent_config) {
                                $absent_student_list[] = $value;
                            }
                            }
                        // }
                    }
                    $absent_config = $this->config_attendance['absent'];
                    if (!empty($absent_student_list)) {

                        $this->mailsmsconf->mailsms('absent_attendence', $absent_student_list, $date);
                    }

                    $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
                    redirect('admin/stuattendence/index', 'refresh');
                }
            }
            $attendencetypes = $this->attendencetype_model->get();
            $data['attendencetypeslist'] = $attendencetypes;
            $resultlist = $this->stuattendence_model->searchAttendenceClassSection($class, $section, date('Y-m-d', $this->customlib->datetostrtotime($date)));
            $data['resultlist'] = $resultlist;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/stuattendence/attendenceList', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    function attendencereport() {

        if (!$this->rbac->hasPrivilege('attendance_by_date', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Attendance');
        $this->session->set_userdata('sub_menu', 'stuattendence/attendenceReport');
        $data['title'] = 'Add Fees Type';
        $data['title_list'] = 'Fees Type List';
        $class = $this->class_model->get();
        $userdata = $this->customlib->getUserData();

        $role_id = $userdata["role_id"];


        if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if ($userdata["class_teacher"] == 'yes') {
                $carray = array();
                $class = array();
                $class = $this->teacher_model->get_daywiseattendanceclass($userdata["id"]);
            }
        }
        $data['classlist'] = $class;
        $data['class_id'] = "";
        $data['section_id'] = "";
        $data['date'] = "";
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {

            $this->load->view('layout/header', $data);
            $this->load->view('admin/stuattendence/attendencereport', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $date = $this->input->post('date');
            $student_list = $this->stuattendence_model->get();
            $data['studentlist'] = $student_list;
            $data['class_id'] = $class;
            $data['section_id'] = $section;
            $data['date'] = $date;
            $search = $this->input->post('search');
            if ($search == "saveattendence") {
                $session_ary = $this->input->post('student_session');
                foreach ($session_ary as $key => $value) {
                    $checkForUpdate = $this->input->post('attendendence_id' . $value);
                    if ($checkForUpdate != 0) {
                        $arr = array(
                            'id' => $checkForUpdate,
                            'student_session_id' => $value,
                            'attendence_type_id' => $this->input->post('attendencetype' . $value),
                            'date' => date('Y-m-d', $this->customlib->datetostrtotime($date))
                        );
                        $insert_id = $this->stuattendence_model->add($arr);
                    } else {
                        $arr = array(
                            'student_session_id' => $value,
                            'attendence_type_id' => $this->input->post('attendencetype' . $value),
                            'date' => date('Y-m-d', $this->customlib->datetostrtotime($date))
                        );
                        $insert_id = $this->stuattendence_model->add($arr);
                    }
                }
            }
            $attendencetypes = $this->attendencetype_model->get();
            $data['attendencetypeslist'] = $attendencetypes;
            $resultlist = $this->stuattendence_model->searchAttendenceClassSectionPrepare($class, $section, date('Y-m-d', $this->customlib->datetostrtotime($date)));

            $data['resultlist'] = $resultlist;
            $data['sch_setting'] =$this->sch_setting_detail;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/stuattendence/attendencereport', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    function classattendencereport() {

        if (!$this->rbac->hasPrivilege('attendance_report', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/attendance');
        $this->session->set_userdata('subsub_menu', 'Reports/attendance/attendance_report');
        $attendencetypes = $this->attendencetype_model->getAttType();
        $data['attendencetypeslist'] = $attendencetypes;
        $data['title'] = 'Add Fees Type';
        $data['title_list'] = 'Fees Type List';
        $class = $this->class_model->get();
        $userdata = $this->customlib->getUserData();
        // ini_set('display_errors', 1);
        $role_id = $userdata["role_id"];


        if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if ($userdata["class_teacher"] == 'yes') {
                $carray = array();
                $class = array();
                $class = $this->teacher_model->get_daywiseattendanceclass($userdata["id"]);
            }
        }
        $data['classlist'] = $class;
        $userdata = $this->customlib->getUserData();
        $data['userdata'] = $userdata;

        $data['monthlist'] = $this->customlib->getMonthDropdown();
        $data['yearlist'] = $this->stuattendence_model->attendanceYearCount();
        $data['class_id'] = "";
        $data['section_id'] = "";
        $data['date'] = "";
        $data['month_selected'] = "";
        $data['year_selected'] = "";
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('month', $this->lang->line('month'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/stuattendence/classattendencereport', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $resultlist = array();
            $class = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $month = $this->input->post('month');
            $data['class_id'] = $class;
            $data['section_id'] = $section;
            $data['month_selected'] = $month;
            $studentlist = $this->student_model->searchByClassSection($class, $section);
            $session_current = $this->setting_model->getCurrentSessionName();
            $startMonth = $this->setting_model->getStartMonth();
            $centenary = substr($session_current, 0, 2); //2017-18 to 2017
            $year_first_substring = substr($session_current, 2, 2); //2017-18 to 2017
            $year_second_substring = substr($session_current, 5, 2); //2017-18 to 18
            $month_number = date("m", strtotime($month));
            $year = $this->input->post('year');
            $data['year_selected'] = $year;
            if (!empty($year)) {

                $year = $this->input->post("year");
            } else {

                if ($month_number >= $startMonth && $month_number <= 12) {
                    $year = $centenary . $year_first_substring;
                } else {
                    $year = $centenary . $year_second_substring;
                }
            }


            $num_of_days = cal_days_in_month(CAL_GREGORIAN, $month_number, $year);
            $attr_result = array();
            $attendence_array = array();
            $student_result = array();
            $data['no_of_days'] = $num_of_days;
            $date_result = array();
            for ($i = 1; $i <= $num_of_days; $i++) {
                $att_date = $year . "-" . $month_number . "-" . sprintf("%02d", $i);
                $attendence_array[] = $att_date;

                $res = $this->stuattendence_model->searchAttendenceReport($class, $section, $att_date);
                $student_result = $res;
                $s = array();
                foreach ($res as $result_k => $result_v) {
                    $s[$result_v['student_session_id']] = $result_v;
                }
                $date_result[$att_date] = $s;
            }

            $monthAttendance = array();
            foreach ($res as $result_k => $result_v) {


                $date = $year . "-" . $month;
                $newdate = date('Y-m-d', strtotime($date));
                $monthAttendance[] = $this->monthAttendance($newdate, 1, $result_v['student_session_id']);
            }
            $data['monthAttendance'] = $monthAttendance;
            // echo "<pre>";
            // print_r($monthAttendance);
            // echo "</pre>";
            // die;

            $data['resultlist'] = $date_result;
            $data['attendence_array'] = $attendence_array;
            $data['student_array'] = $student_result;
            $data['sunday_count'] = $this->countSundays($month_number, $year);

            $this->load->view('layout/header', $data);
            $this->load->view('admin/stuattendence/classattendencereport', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    function monthAttendance($st_month, $no_of_months, $student_id) {

        $record = array();

        $r = array();
        $month = date('m', strtotime($st_month));
        $year = date('Y', strtotime($st_month));

        foreach ($this->config_attendance as $att_key => $att_value) {

            $s = $this->stuattendence_model->count_attendance_obj($month, $year, $student_id, $att_value);
            

            $attendance_key = $att_key;


            $r[$attendance_key] = $s;
        }
        $record[$student_id] = $r;

        return $record;
    }

    function countSundays($month, $year) {
        // Initialize a counter for Sundays
        $sundayCount = 0;
    
        // Get the number of days in the given month and year
        $num_of_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    
        // Loop through each day of the month
        for ($day = 1; $day <= $num_of_days; $day++) {
            // Get the timestamp for the current day
            $date = strtotime("$year-$month-$day");
    
            // Check if the day is a Sunday (Sunday = 0 in PHP)
            if (date('w', $date) == 0) {
                // Increment the Sunday counter
                $sundayCount++;
            }
        }
    
        // Return the number of Sundays
        return $sundayCount;
    }

    public function common_holiday($id ="")
    {
        if (!$this->rbac->hasPrivilege('common_holiday', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'Academics/common_holiday');
        $data['title'] = 'Exam Pattern List';
        if ($id != "") {
            $data['update']      = $this->stuattendence_model->getcommon_holiday($id);
        }

        $data['resultlist']   = $this->stuattendence_model->getcommon_holiday();

        $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', 'Date', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/stuattendence/common_holiday', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $current_session = $this->setting_model->getCurrentSession();
            $data = array(
                'id' => $this->input->post('id'),
                'name' => $this->input->post('name'),
                'date' => date('Y-m-d',strtotime($this->input->post('date'))),
                'description' => $this->input->post('description'),
                'session_id' => $current_session,
            );
            
            $insert_id =$this->stuattendence_model->add_common_holiday($data);

            if (empty($this->input->post('id'))) {
               $allStudents = $this->student_model->getAllStudentBySession($current_session);
               $allStudentsArray = [];
               if (!empty($allStudents)) {
                foreach ($allStudents as  $value) {
                    $allStudentsArray[] = [
                     'holiday_id' => $insert_id,
                     'student_session_id' => $value['id'],
                     'attendence_type_id' => 5,
                     'remark' => $this->input->post("description"),
                     'date' => date('Y-m-d',strtotime($this->input->post('date')))
                    ];
                 
                }
                
                $this->stuattendence_model->add_holidays($allStudentsArray);
               }

               
               
            }
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/stuattendence/common_holiday');
        }
    }

    public function delete_row($id)
    {
        $this->stuattendence_model->remove($id);
        redirect('admin/stuattendence/common_holiday');
    }

    public function holiday_attendence_marking()
    {
        if (!$this->rbac->hasPrivilege('holiday_attendence_marking', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Attendance');
        $this->session->set_userdata('sub_menu', 'Attendance/holiday_attendence_marking');
        $data['title'] = 'Holiday Attendence Marking';
        $data['title_list'] = 'Holiday Attendence Marking';
        $sch_section_result      = $this->examgroup_model->getsch_section();
        $data['sch_section_result'] = $sch_section_result;
        $data['date'] = $date = $this->input->post('date');
        $data['sch_section_id'] = $sch_section = $this->input->post('sch_section_id');

        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('sch_section_id[]', 'school sections', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header');
            $this->load->view('admin/stuattendence/holiday_attendence_marking', $data);
            $this->load->view('layout/footer');
        } else {
            ini_set('display_errors', 1);

            
            $schIds = implode(',', $sch_section);
            
            $allClassesArr = $this->class_model->getclassBySchsection($schIds);
            $allClasses = array_column($allClassesArr, 'id');
            $studentsList = $this->stuattendence_model->getstudentsByClasses($allClasses); 
            // echo "<pre>";
            // print_r ($studentsList);die;
            // echo "</pre>";

            $mainArr = [];
            if (!empty($studentsList)) {
                foreach ($studentsList as $key => $value) {
                    $mainArr = array(
                        'student_session_id' => $value['id'],
                        'attendence_type_id' => 5,
                        'remark' => "holiday",
                        'date' => date('Y-m-d', $this->customlib->datetostrtotime($date))
                    );
                    
                    $this->stuattendence_model->add($mainArr);
                }
                
                
            }
            
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/stuattendence/holiday_attendence_marking');
        }

        // $this->load->view('layout/header');
        // $this->load->view('admin/stuattendence/holiday_attendence_marking', $data);
        // $this->load->view('layout/footer');
    }

    public function check_attendence()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);        
        // $this->db->select('id');
        // $this->db->where('is_active', 'yes');
        // $this->db->where('class_id >=', 4);
        // $this->db->where('class_id <=', 8);
        // $this->db->from('student_session');
        $result = $this->db->query('SELECT date,student_session_id as id,count(*) FROM `student_attendences` where student_session_id in (select id from student_session where student_session.session_id = 20 and student_session.class_id > 8 ) group by date,student_session_id having count(*) > 1 order by date,student_session_id');
        ///$student_session_id = $this->db->get()->result_array();
        //$student_session_id = $this->db->get()->result_array();
        $student_session_id = $result->result_array();
        // echo "<pre>";
        // print_r($student_session_id);die();
        $del_arrays = array();
        foreach ($student_session_id as $key => $value) {
            $del_arrays = array();
            $this->db->select('id,date,student_session_id');
            $this->db->where('student_session_id', $value['id']);
            // $this->db->where('MONTH(date)', '09');
            // $this->db->where('YEAR(date)', '2024');
            // $this->db->where('attendence_type_id', 5);
            $query = $this->db->get('student_attendences');

            if ($query->num_rows() > 1) {
                foreach ($query->result() as $row) {
                    $this->db->select('id,date,student_session_id,attendence_type_id');
                    $this->db->where('student_session_id', $row->student_session_id);
                    $this->db->where('date', $row->date);
                    $this->db->order_by('attendence_type_id');
                    $query3 = $this->db->get('student_attendences');
                    
                    if ($query3->num_rows() > 1) {
                        $old_type_id =0;
                        
                        $first_row = array();
                        foreach ($query3->result() as $row3) {
                            if ($old_type_id == 0) {
                                $old_type_id = $row3->attendence_type_id;
                                $first_row = $row3;
                            }
                            elseif($old_type_id == $row3->attendence_type_id)
                            {
                                $del_arrays[] = $row3->id;
                            }
                            elseif(($old_type_id == 1) &&  ($row3->attendence_type_id == 5))
                            {
                                $del_arrays[] = $row3->id;
                            }
                            elseif(($old_type_id == 4) &&  ($row3->attendence_type_id == 5))
                            {
                                $del_arrays[] = $row3->id;
                            }
                             
                        }

                    }

                }
            }
            echo "<pre>";print_r($del_arrays);
            if(!empty($del_arrays))
            {
            $this->db->where_in('id', $del_arrays);
            $this->db->delete('student_attendences');
            }
        }

        echo "Success";
    }

    public function delete_attendence($id)
    {
        $this->stuattendence_model->remove_attendence($id);
        redirect('admin/stuattendence/index');
    }

}

?>