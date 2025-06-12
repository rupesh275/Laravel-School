<?php

class Staff_monthly_attendence extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('file');
        $this->config->load("mailsms");
        $this->config->load("payroll");
        $this->load->library('mailsmsconf');
        $this->config_attendance = $this->config->item('attendence');
        $this->staff_attendance  = $this->config->item('staffattendance');
        $this->payment_mode      = $this->config->item('payment_mode');
        $this->contract_type      = $this->config->item('contracttype');
        $this->load->model("payroll_model");
        $this->load->model("staff_model");
        $this->load->model('staffattendancemodel');
        $this->payroll_status     = $this->config->item('payroll_status');
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    function staff_monthly_attendance_auto() {

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
        $user_type_id = $this->input->post('role');
        $data['Payroll_set'] = $Payroll_set = $this->db->where('status', 'active')->get('payroll_settings')->row_array();
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
        $month_year = date('m', strtotime($month)) . '-' . $this->input->post('year');
        $resultlist = $this->staffattendancemodel->searchAttendencemonthlyByDepartment($role, $month_year);
        $data['resultlist'] = $resultlist;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/staff_monthly/staff_monthly_attendance_auto', $data);
        $this->load->view('layout/footer', $data);
    }
}