<?php

class Payroll extends Admin_Controller
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
        $this->load->model("payroll_model");
        $this->load->model("staff_model");
        $this->load->model('staffattendancemodel');
        $this->payroll_status     = $this->config->item('payroll_status');
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function index()
    {

        if (!$this->rbac->hasPrivilege('staff_payroll', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/payroll');
        $data["staff_id"]            = "";
        $data["name"]                = "";
        $data["month"]               = date("F", strtotime("-1 month"));
        $data["year"]                = date("Y");
        $data["present"]             = 0;
        $data["absent"]              = 0;
        $data["late"]                = 0;
        $data["half_day"]            = 0;
        $data["holiday"]             = 0;
        $data["leave_count"]         = 0;
        $data["alloted_leave"]       = 0;
        $data["basic"]               = 0;
        $data["payment_mode"]        = $this->payment_mode;
        $user_type                   = $this->staff_model->getStaffRole();
        $data['classlist']           = $user_type;
        $data['monthlist']           = $this->customlib->getMonthDropdown();
        $data['sch_setting']         = $this->sch_setting_detail;
        $data['staffid_auto_insert'] = $this->sch_setting_detail->staffid_auto_insert;
        $submit                      = $this->input->post("search");
        if (isset($submit) && $submit == "search") {

            $month    = $this->input->post("month");
            $year     = $this->input->post("year");
            $emp_name = $this->input->post("name");
            $role     = $this->input->post("role");

            $searchEmployee = $this->payroll_model->searchEmployee($month, $year, $emp_name, $role);

            $data["resultlist"] = $searchEmployee;
            $data["name"]       = $emp_name;
            $data["month"]      = $month;
            $data["year"]       = $year;
        }

        $data["payroll_status"] = $this->payroll_status;
        $this->load->view("layout/header", $data);
        $this->load->view("admin/payroll/stafflist", $data);
        $this->load->view("layout/footer", $data);
    }

    public function create($month, $year, $id)
    {

        $data["staff_id"]            = "";
        $data["basic"]               = "";
        $data["name"]                = "";
        $data["month"]               = "";
        $data["year"]                = "";
        $data["present"]             = 0;
        $data["absent"]              = 0;
        $data["late"]                = 0;
        $data["half_day"]            = 0;
        $data["holiday"]             = 0;
        $data["leave_count"]         = 0;
        $data["alloted_leave"]       = 0;
        $data['sch_setting']         = $this->sch_setting_detail;
        $data['staffid_auto_insert'] = $this->sch_setting_detail->staffid_auto_insert;
        $user_type                   = $this->staff_model->getStaffRole();
        $data['classlist']           = $user_type;
        $data['staff_id']           = $id;

        $date = $year . "-" . $month;

        $searchEmployee = $this->payroll_model->searchEmployeeById($id);

        $data['result'] = $searchEmployee;
        $data["month"]  = $month;
        $data["year"]   = $year;

        $alloted_leave = $this->staff_model->alloted_leave($id);

        $newdate = date('Y-m-d', strtotime($date . " +1 month"));

        $data['monthAttendance'] = $this->monthAttendance($newdate, 3, $id);
        $data['monthLeaves']     = $this->monthLeaves($newdate, 3, $id);

        $data["attendanceType"] = $this->staffattendancemodel->getStaffAttendanceType();

        $data["alloted_leave"] = $alloted_leave[0]["alloted_leave"];

        $this->load->view("layout/header", $data);
        $this->load->view("admin/payroll/create", $data);
        $this->load->view("layout/footer", $data);
    }

    public function monthAttendance($st_month, $no_of_months, $emp)
    {
        $record = array();
        for ($i = 1; $i <= $no_of_months; $i++) {

            $r     = array();
            $month = date('m', strtotime($st_month . " -$i month"));
            $year  = date('Y', strtotime($st_month . " -$i month"));

            foreach ($this->staff_attendance as $att_key => $att_value) {

                $s = $this->payroll_model->count_attendance_obj($month, $year, $emp, $att_value);

                $r[$att_key] = $s;
            }

            $record['01-' . $month . '-' . $year] = $r;
        }
        return $record;
    }

    public function monthLeaves($st_month, $no_of_months, $emp)
    {
        $record = array();
        for ($i = 1; $i <= $no_of_months; $i++) {

            $r           = array();
            $month       = date('m', strtotime($st_month . " -$i month"));
            $year        = date('Y', strtotime($st_month . " -$i month"));
            $leave_count = $this->staff_model->count_leave($month, $year, $emp);
            if (!empty($leave_count["tl"])) {
                $l = $leave_count["tl"];
            } else {
                $l = "0";
            }

            $record[$month] = $l;
        }

        return $record;
    }

    public function payslip()
    {
        if (!$this->rbac->hasPrivilege('staff_payroll', 'can_add')) {
            access_denied();
        }

        $basic           = $this->input->post("basic");
        $total_allowance = $this->input->post("total_allowance");
        $total_deduction = $this->input->post("total_deduction");
        $net_salary      = $this->input->post("net_salary");
        $status          = $this->input->post("status");
        $staff_id        = $this->input->post("staff_id");
        $month           = $this->input->post("month");
        $name            = $this->input->post("name");
        $year            = $this->input->post("year");
        $tax             = $this->input->post("tax");
        $leave_deduction = $this->input->post("leave_deduction");
        $this->form_validation->set_rules('net_salary', 'Net Salary', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {

            $this->create($month, $year, $staff_id);
        } else {

            $data = array(
                'staff_id' => $staff_id,
                'basic'                  => $basic,
                'total_allowance'        => $total_allowance,
                'total_deduction'        => $total_deduction,
                'net_salary'             => $net_salary,
                'payment_date'           => date("Y-m-d"),
                'status'                 => $status,
                'month'                  => $month,
                'year'                   => $year,
                'tax'                    => $tax,
                'leave_deduction'        => '0',
            );

            $checkForUpdate = $this->payroll_model->checkPayslip($month, $year, $staff_id);

            if ($checkForUpdate == true) {

                $insert_id        = $this->payroll_model->createPayslip($data);
                $payslipid        = $insert_id;
                $allowance_type   = $this->input->post("allowance_type");
                $deduction_type   = $this->input->post("deduction_type");
                $allowance_amount = $this->input->post("allowance_amount");
                $deduction_amount = $this->input->post("deduction_amount");
                if (!empty($allowance_type)) {

                    $i = 0;
                    foreach ($allowance_type as $key => $all) {

                        $all_data = array(
                            'payslip_id'     => $payslipid,
                            'allowance_type' => $allowance_type[$i],
                            'amount'         => $allowance_amount[$i],
                            'staff_id'       => $staff_id,
                            'cal_type'       => "positive",
                        );

                        $insert_payslip_allowance = $this->payroll_model->add_allowance($all_data);

                        $i++;
                    }
                }

                if (!empty($deduction_type)) {
                    $j = 0;
                    $loanAmt = array();
                    foreach ($deduction_type as $key => $type) {
                        $loanAmt = $deduction_amount[5];
                        $type_data = array(
                            'payslip_id' => $payslipid,
                            'allowance_type'                => $deduction_type[$j],
                            'amount'                        => $deduction_amount[$j],
                            'staff_id'                      => $staff_id,
                            'cal_type'                      => "negative",
                        );

                        $insert_payslip_allowance = $this->payroll_model->add_allowance($type_data);

                        $j++;
                    }



                    if ($loanAmt > 0) {

                        $staffloan = $this->staff_model->getloanByStaffId($staff_id);
                        $paidAmt = $staffloan['loan_paid_amount'] + $loanAmt;
                        $balanceAmt = $staffloan['loan_amount'] - $loanAmt;
                        $status = 1;
                        $arrayLoan = array(
                            'staff_id' => $staff_id,
                            'loan_paid_amount' => $paidAmt,
                            'loan_current_balance' => $balanceAmt,
                            'loan_status' => $status,
                        );

                        $this->staff_model->loanUpdate($arrayLoan);
                    }
                }

                redirect('admin/payroll');
            } else {

                $this->session->set_flashdata("msg", $this->lang->line('payslip_already_generated'));
                redirect('admin/payroll');
            }
        }
    }

    public function search($month, $year, $role = '')
    {

        $user_type         = $this->staff_model->getStaffRole();
        $data['classlist'] = $user_type;
        $data['monthlist'] = $this->customlib->getMonthDropdown();

        $searchEmployee = $this->payroll_model->searchEmployee($month, $year, $emp_name = '', $role);

        $data["resultlist"]     = $searchEmployee;
        $data["name"]           = $emp_name;
        $data["month"]          = $month;
        $data["year"]           = $year;
        $data['sch_setting']    = $this->sch_setting_detail;
        $data["payroll_status"] = $this->payroll_status;
        $data["resultlist"]     = $searchEmployee;
        $data["payment_mode"]   = $this->payment_mode;
        $this->load->view("layout/header", $data);
        $this->load->view("admin/payroll/stafflist", $data);
        $this->load->view("layout/footer", $data);
    }

    public function paymentRecord()
    {

        $month          = $this->input->get_post("month");
        $year           = $this->input->get_post("year");
        $id             = $this->input->get_post("staffid");
        $searchEmployee = $this->payroll_model->searchPayment($id, $month, $year);
        $data['result'] = $searchEmployee;
        $data["month"]  = $month;
        $data["year"]   = $year;
        echo json_encode($data);
    }

    public function paymentStatus($status)
    {

        $id          = $this->input->get('id');
        $updateStaus = $this->payroll_model->updatePaymentStatus($status, $id);
        redirect("admin/payroll");
    }

    public function paymentSuccess()
    {

        $payment_mode = $this->input->post("payment_mode");
        $date         = $this->input->post("payment_date");
        $payment_date = date('Y-m-d', strtotime($date));
        $remark       = $this->input->post("remarks");
        $status       = 'paid';
        $payslipid    = $this->input->post("paymentid");
        $this->form_validation->set_rules('payment_mode', $this->lang->line('payment') . " " . $this->lang->line('mode'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {

            $msg = array(
                'payment_mode' => form_error('payment_mode'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            $data = array('payment_mode' => $payment_mode, 'payment_date' => $payment_date, 'remark' => $remark, 'status' => $status);
            $this->payroll_model->paymentSuccess($data, $payslipid);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function payslipView()
    {
        if (!$this->rbac->hasPrivilege('staff', 'can_view')) {
            access_denied();
        }
        $data["payment_mode"] = $this->payment_mode;
        $this->load->model("setting_model");
        $setting_result      = $this->setting_model->get();
        $data['settinglist'] = $setting_result[0];
        $id                  = $this->input->post("payslipid");
        $result              = $this->payroll_model->getPayslip($id);
        $data['sch_setting'] = $this->sch_setting_detail;

        $data['staffid_auto_insert'] = $this->sch_setting_detail->staffid_auto_insert;
        if (!empty($result)) {
            $allowance                  = $this->payroll_model->getAllowance($result["id"]);
            $data["allowance"]          = $allowance;
            $positive_allowance         = $this->payroll_model->getAllowance($result["id"], "positive");
            $data["positive_allowance"] = $positive_allowance;
            $negative_allowance         = $this->payroll_model->getAllowance($result["id"], "negative");
            $data["negative_allowance"] = $negative_allowance;
            $data["result"]             = $result;
            $this->load->view("admin/payroll/payslipview", $data);
        } else {
            echo "<div class='alert alert-info'>No Record Found.</div>";
        }
    }

    public function payslippdf()
    {

        $this->load->model("setting_model");
        $setting_result             = $this->setting_model->get();
        $data['settinglist']        = $setting_result[0];
        $id                         = 15;
        $result                     = $this->payroll_model->getPayslip($id);
        $allowance                  = $this->payroll_model->getAllowance($result["id"]);
        $data["allowance"]          = $allowance;
        $positive_allowance         = $this->payroll_model->getAllowance($result["id"], "positive");
        $data["positive_allowance"] = $positive_allowance;
        $negative_allowance         = $this->payroll_model->getAllowance($result["id"], "negative");
        $data["negative_allowance"] = $negative_allowance;
        $data["result"]             = $result;
        $this->load->view("admin/payroll/payslippdf", $data);
    }

    

    public function deletepayroll($payslipid, $month, $year, $role = '')
    {
        if (!$this->rbac->hasPrivilege('staff_payroll', 'can_delete')) {
            access_denied();
        }
        if (!empty($payslipid)) {

            $this->payroll_model->deletePayslip($payslipid);
        }

        redirect('admin/payroll/search/' . $month . "/" . $year . "/" . $role);
    }

    public function revertpayroll($payslipid, $month, $year, $role = '')
    {

        if (!$this->rbac->hasPrivilege('staff_payroll', 'can_delete')) {
            access_denied();
        }
        if (!empty($payslipid)) {

            $this->payroll_model->revertPayslipStatus($payslipid);
        }
        redirect('admin/payroll/search/' . $month . "/" . $year . "/" . $role);
    }
    public function test()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);        
        echo "hello".$this->staff_model->get_salary_advance_recovery(31,"July",2024);
    }
    public function payroll_group()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        if (!$this->rbac->hasPrivilege('payroll_group', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/payroll/payroll_group');
        $data["staff_id"]            = "";
        $data["name"]                = "";
        $data["month"]               = date("F", strtotime("-1 month"));
        $data["year"]                = date("Y");
        $data["present"]             = 0;
        $data["absent"]              = 0;
        $data["late"]                = 0;
        $data["half_day"]            = 0;
        $data["holiday"]             = 0;
        $data["leave_count"]         = 0;
        $data["alloted_leave"]       = 0;
        $data["basic"]               = 0;
        $data['Payroll_set'] = $Payroll_set = $this->db->where('status', 'active')->get('payroll_settings')->row_array();
        $data["payment_mode"]        = $this->payment_mode;
        $user_type                   = $this->staff_model->getStaffRole();
        $data['classlist']           = $user_type;
        $department                  = $this->staff_model->getDepartment();
        $data["department"]          = $department;
        $data['monthlist']           = $this->customlib->getMonthDropdown();
        $data['session_id']          = $this->setting_model->getCurrentSession();
        $data['sch_setting']         = $this->sch_setting_detail;
        $data['staffid_auto_insert'] = $this->sch_setting_detail->staffid_auto_insert;
        $submit                      = $this->input->post("search");
        if (isset($submit) && $submit == "search") {
            $month    = $this->input->post("month");
            $year     = $this->input->post("year");
            $emp_name = $this->input->post("name");
            $role     = $this->input->post("role");
            $searchEmployee = $this->payroll_model->searchEmployeeForGroupDepart_special($month, $year, $emp_name, $role);
            $data['payroll_settings'] = $this->payroll_model->get_payroll_setting();
            $data["resultlist"] = $searchEmployee;
            $data["name"]       = $emp_name;
            $data["month"]      = $month;
            $data["year"]       = $year;
            $data['department_id'] = $this->input->post("role");
        }
        $data["payroll_status"] = $this->payroll_status;
        $this->load->view("layout/header", $data);
        $this->load->view("admin/payroll/paystafflist", $data);
        $this->load->view("layout/footer", $data);
    }
    public function staffPay()
    {
        // ini_set('display_errors', 1);
        // $staff_id = $this->input->post('staff_id');
        if (!empty($this->input->post('check'))) {
            $staff_id = $this->input->post('check');
        } else {
            $staff_id = $this->input->post('staff_id');
        }
        // echo "<pre>";
        // print_r ($this->input->post());
        // echo "</pre>";
        if ($this->form_validation->run() == true) {
            $json = array(
                "error" => true,
            );
        } else {
            if (!empty($staff_id)) {
                if ($this->input->post('button_type') == "generate") {
                    $type = 1;
                    $i = 0;
                    $dataview = '';
                    foreach ($staff_id as  $staff) {
                        $arraySalary = array(
                            'id' => $this->input->post('id')[$i],
                            'session_id' => $this->setting_model->getCurrentSession(),
                            'month' => $this->input->post('month'),
                            'year' => $this->input->post('year'),
                            'department_id' => $this->input->post('department_id'),
                            'contract_type' => $this->input->post('contract_type')[$i],
                            'date' => $this->input->post('date'),
                            'staff_id' => $staff,
                            'total_attendence' => $this->input->post('total_attendence')[$i],
                            'attendence' => $this->input->post('attendence')[$i],
                            'basic_pay' => $this->input->post('basic_salary')[$i],
                            'gp' => $this->input->post('gp')[$i],
                            'da' => $this->input->post('da')[$i],
                            'pp' => $this->input->post('pp')[$i],
                            'hra' => $this->input->post('hra')[$i],
                            'ta' => $this->input->post('ta')[$i],
                            'other_allowance' => $this->input->post('other_allowance')[$i],
                            'addition' => $this->input->post('addition')[$i],
                            'gross_salary' => $this->input->post('gross_salary')[$i],
                            'gross_salary_al' => $this->input->post('gross_salary_al')[$i],
                            'lwp' => $this->input->post('lwp')[$i],
                            'pf' => $this->input->post('pf')[$i],
                            'mng_pf' => $this->input->post('mng_pf')[$i],
                            'pf_earning' => $this->input->post('pf_earning')[$i],
                            'profession_tax' => $this->input->post('profession_tax')[$i],
                            'income_tax' => $this->input->post('income_tax')[$i],
                            'advance' => $this->input->post('adv_amount')[$i],
                            'loan' => $this->input->post('loan_emi')[$i],
                            'other_deduction' => $this->input->post('other_deduction')[$i],
                            'total_deduction' => $this->input->post('total_deduction')[$i],
                            'nett_salary' => $this->input->post('nett_salary')[$i],
                            'salary_hold' => $this->input->post('salary_hold')[$i],
                            'total_salary' => $this->input->post('total_salary')[$i],
                            'remarks' => $this->input->post('remarks')[$i],
                        );
                        // echo "<pre>";
                        // print_r ($arraySalary);
                        // echo "</pre>";die();
                        $result_id = $this->staff_model->addStaffSalary($arraySalary);
                        if($result_id)
                        {
                            // echo "hii";
                            // if($result_id > 0)
                            // {
                                $hash_id = md5($result_id);$up_data = array('hash_id' => $hash_id); 
                                $this->db->where('id', $result_id);
                                $this->db->update('payroll', $up_data);  
                                $data['staff_id']= $staff_id = $staff;
                                $data['month']= $month = $this->input->post('month');
                                $data['year']= $year = $this->input->post('year');
                                $data['result'] = $this->payroll_model->getPayslipNew($staff_id,$month,$year);
                                $data['sch_setting']         = $this->sch_setting_detail;
                                $data['Payroll_set'] = $Payroll_set = $this->db->where('status', 'active')->get('payroll_settings')->row_array();
                                $dataview .= $this->load->view('admin/payroll/payslipNew', $data, TRUE);
                                $staffRow = $this->staff_model->get($staff_id);
                            //}
                        }
                        $i++;
                    }
                }elseif($this->input->post('button_type') == "send_email") {
                    $type = 2;
                   $dataview = "";
                    foreach ($staff_id as  $staff) {
                        $staffRow = $this->staff_model->get($staff);
                        $email = "";//'manojthannimattam@gmail.com';//$staffRow['email'];
                        // mail send to staff with payslip
                        if (!empty($email)) {
                            $data['staffRow']= $staffRow;
                            $data['staff_id']= $staff_id = $staff;
                            $data['month']= $month = $this->input->post('month');
                            $data['year']= $year = $this->input->post('year');
                            $data['result'] = $this->payroll_model->getPayslipNew($staff_id,$month,$year);
                            $data['Payroll_set'] = $Payroll_set = $this->db->where('status', 'active')->get('payroll_settings')->row_array();
                            $data['sch_setting']         = $this->sch_setting_detail;
                            $html = $this->load->view('admin/payroll/mail_template', $data, TRUE);
                            // $this->send_mail($email, 'Payslip', $html);
                        }
                   }
                }else {
                    $type = 3;
                    $dataview = "";
                    // $staff_id = $this->input->post('staff_id');
                    foreach ($staff_id as  $staff) {
                        $staffRow = $this->staff_model->get($staff);
                        $array = array(
                            'approval_time' => date('Y-m-d H:i:s'),
                        );
                        $this->db->where('staff_id', $staff);
                        $this->db->where('month', $this->input->post('month'));
                        $this->db->where('year', $this->input->post('year'));
                        // $this->db->where('session_id', $this->setting_model->getCurrentSession());
                        $this->db->update('payroll', $array);  
                    }

                }
            }
            $json = array(
                "success" => "Data Updates Successfully!!!!",
                "response" => $dataview,
                "type" => $type,
            );
        }
        echo json_encode($json);
    }

    public function printPaySlipNew()
    {
        $data['staff_id']= $staff_id =$this->input->post('staff_id');
        $data['month']= $month = $this->input->post('month');
        $data['year']= $year = $this->input->post('year');
        $data['result'] = $this->payroll_model->getPayslipNew($staff_id,$month,$year);
        $data['sch_setting'] = $sch_setting = $this->sch_setting_detail;
        $data['affno'] = $data['sch_setting']->affilation_no;
        $data['Payroll_set'] = $Payroll_set = $this->db->where('status', 'active')->get('payroll_settings')->row_array();
        // echo '<pre>';
        // print_r();die();
        $this->load->view('admin/payroll/payslipNew', $data);
        // echo $dataview;die();
    }
    public function deleteSalary($staff_id,$month,$year)
    {
        $this->payroll_model->removeSalary($staff_id,$month,$year);
        redirect('admin/payroll/payroll_group');
    }

    public function cancelpay()
    {
        $month = $this->input->post('month');
        $year = $this->input->post('year');
        $session_id = $this->input->post('session_id');
        $department_id = $this->input->post('department_id');
        $this->payroll_model->cancelpayroll($month,$year,$session_id,$department_id);
        $json = array(
            "message" => "Cancel Successfully!!!!",
        );
        echo json_encode($json);
    }

    public function payroll_category($id="")
    {
        if (!$this->rbac->hasPrivilege('payroll_category', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'HR/staff/payroll_category');
        $this->data = "";

        if ($id != "") {
            $data['update']  = $this->payroll_model->getPayrollCategory($id);
        }
        $this->form_validation->set_rules('category_name', "Category Name", 'required|trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
        } else {
            $userdata           = $this->customlib->getUserData();
            
            $insert_array = array(
                'id' => $this->input->post('id'),
                'category_name' => $this->input->post('category_name'),
                
            );

            $this->payroll_model->addPayrollCategory($insert_array);

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/payroll/payroll_category');
        }

        $data['resultlist'] = $this->payroll_model->getPayrollCategory();



        $this->load->view('layout/header');
        $this->load->view('admin/payroll/payroll_category', $data);
        $this->load->view('layout/footer');
    }

    public function delete_payroll_category($id)
    {
        if (!$this->rbac->hasPrivilege('payroll_category', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Payroll List';
        $this->payroll_model->remove_payroll_category($id);
        redirect('admin/payroll/payroll_category');
    }

    public function send_mail($toemail, $subject, $body, $cc = "", $FILES = array())
    {

        $sch_setting = $this->setting_model->get();

        $school_name = $sch_setting[0]['name'];
        $school_email = $sch_setting[0]['email'];

        $mail_config = $this->emailconfig_model->getActiveEmail();

        if ($mail_config->email_type == "smtp") {

            $from_mail = $mail_config->smtp_username;
            $smtp_host = $mail_config->smtp_server;
            $smtp_port = $mail_config->smtp_port;
            $smtp_user = $mail_config->smtp_username;
            $smtp_pass = $mail_config->smtp_password;
            $config = array(
                'protocol' => "SMTP",
                'smtp_host' => $smtp_host,
                'smtp_port' => $smtp_port,
                'smtp_user' => $smtp_user,
                'smtp_pass' => $smtp_pass,
                'mailtype' => 'html',
                'crlf' => "\r\n",
                'newline' => "\r\n",
                'validate' => False,
                'charset' => "utf-8",
                'wordwrap' => TRUE,
            );
            $this->load->library('email');
            $this->email->initialize($config);
            $this->email->from($from_mail, $school_name);
            $this->email->to($toemail);
            if ($cc != "") {
                $this->email->cc($cc);
            }
            $this->email->subject($subject);
            $this->email->message($body);
            if (!empty($FILES)) {
                if (isset($_FILES['files']) && !empty($_FILES['files'])) {
                    $no_files = count($_FILES["files"]['name']);
                    for ($i = 0; $i < $no_files; $i++) {
                        if ($_FILES["files"]["error"][$i] > 0) {
                            echo "Error: " . $_FILES["files"]["error"][$i] . "<br>";
                        } else {
                            $file_tmp = $_FILES["files"]["tmp_name"][$i];
                            $file_name = $_FILES["files"]["name"][$i];
                            // $mail->AddAttachment($file_tmp, $file_name);
                            $this->CI->email->attach($file_name, 'attachment', $file_tmp);
                        }
                    }
                }
            }

            $abc = $this->email->send();


            if (!$abc) {
                echo $this->email->print_debugger();
            } else {
                return true;
            }
        }
    }

    public function salary_cheque_detail($id = '')
    {
        // ini_set('display_errors', '1');
        if (!$this->rbac->hasPrivilege('salary_cheque_detail', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'HR/payroll/salary_cheque_detail');
        $resultlist         = $this->staff_model->get();
        $data['resultlist'] = $resultlist;
        $stafflist          = $this->payroll_model->getSalaryCheque();
        $data['stafflist'] = $stafflist;
        $stafflist          = $this->payroll_model->getSalaryCheque($id);
        $data['update'] = $stafflist;

        $data['monthlist']    = $this->customlib->getMonthDropdown();
        $data['yearlist']     = $this->payroll_model->payrollYearCount();
        $data['banklist']     = $this->payroll_model->getBankList();
        $month                = $this->input->post("month");
        $year                 = $this->input->post("year");
        $type                 = $this->input->post("type");
        $data["month"]        = $month;
        $data["year"]         = $year;
        $data["type"]         = $type;

        $this->form_validation->set_rules('month', "Month", 'required|trim|xss_clean');
        $this->form_validation->set_rules('year', "Year", 'required|trim|xss_clean');
        $this->form_validation->set_rules('type', "Type", 'required|trim|xss_clean');
        $this->form_validation->set_rules('chq_no', "Cheque No", 'required|trim|xss_clean');
        $this->form_validation->set_rules('chq_date', "Cheque Date", 'required|trim|xss_clean');
        $this->form_validation->set_rules('chq_bank', "Cheque Bank", 'required|trim|xss_clean');
        $this->form_validation->set_rules('amount', "Amount", 'required|trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/payroll/salary_cheque_detail', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $session_current       = $this->setting_model->getCurrentSession();
            $data = array(
                'id'                    => $this->input->post('id'),
                'month'              => $this->input->post('month'),
                'year'           => $this->input->post('year'),
                'type'          => $this->input->post('type'),
                'letter_date'             => date('Y-m-d',strtotime($this->input->post('letter_date'))),
                'payment_date'             => date('Y-m-d',strtotime($this->input->post('payment_date'))),
                'chq_no '             => $this->input->post('chq_no'),
                'chq_date'             => date('Y-m-d',strtotime($this->input->post('chq_date'))),
                'chq_bank ' => $this->input->post('chq_bank'),
                'amount '   => $this->input->post('amount'),
                'remarks '   => $this->input->post('remarks'),
               
            );
            $this->payroll_model->add_salary_cheque($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/payroll/salary_cheque_detail');
        }
    }

    public function delete_cheque($id)
    {
        if (!$this->rbac->hasPrivilege('salary_cheque_detail', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'cheque ';
        $delete_array = [
            'id' => $id,
            'status' => 'deleted',
            'deleted_at' => date('Y-m-d H:i:s'),
            'deleted_by' => $this->customlib->getStaffID(),
        ];
        $this->payroll_model->remove_salary_cheque($delete_array);
        redirect('admin/payroll/salary_cheque_detail');
    }

    public function print_cheque()
    {
        // ini_set('display_errors', '1');
        $id = $this->input->post('id');

        $data['cheque'] = $cheque = $this->payroll_model->getSalaryCheque($id);
        $data['setting']   = $this->sch_setting_detail;
        $data['letter_date'] = date('d-m-Y',strtotime($cheque['letter_date']));
        $data['salaryArr']  = $this->payroll_model->getSalaryEmpArr($cheque['month'], $cheque['year'],$cheque['type']);
        $data['payroll_settings'] = $this->payroll_model->get_payroll_setting();
        $data['result_pt'] = $this->payroll_model->getPtDetail($cheque['month'], $cheque['year']);
        if ($cheque['type'] == 'PF') {
            $dataview           = $this->load->view('admin/payroll/print_cheque_pf', $data, true);
        }elseif($cheque['type'] == 'PT'){
            $dataview           = $this->load->view('admin/payroll/print_cheque_pt', $data, true);
        } else {
            $dataview           = $this->load->view('admin/payroll/print_cheque', $data, true);
        }
        $json = array(
            "success" => "Data Updates Successfully!!!!",
            "response" => $dataview
        );

        echo json_encode($json);
    }
    public function add_payroll_setting($id = null){
        // ini_set('display_errors', '1');
        $data['title'] = 'Payroll Setting';
        $data['Payroll_set'] = $Payroll_set = $this->payroll_model->getPayrollSetting();
        $data['update'] = $this->payroll_model->getPayrollSettingById($id);
        // print_r($data['update']);die();
        $this->load->view('layout/header');
        $this->load->view('admin/payroll/add_payroll_settings', $data);
        $this->load->view('layout/footer');
    }
    public function payroll_setting()
    {
        // ini_set('display_errors', '1');
        if (!$this->rbac->hasPrivilege('payroll_setting', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'HR/payroll/payroll_setting');
        $data['title'] = 'Payroll Setting';
        $data['Payroll_set'] = $Payroll_set = $this->payroll_model->getPayrollSetting();
        // print_r($Payroll_set);die();
        $this->load->view('layout/header');
        $this->load->view('admin/payroll/joining_setting', $data);
        $this->load->view('layout/footer');
    }
    public function payroll_settings_validation()
    {
        $data['title'] = 'Payroll Setting';
        $data['Payroll_set'] = $Payroll_set = $this->payroll_model->getPayrollSetting();
        $this->form_validation->set_rules('da', "da", 'required|trim');
        $this->form_validation->set_rules('pp', "pp", 'required|trim');
        $this->form_validation->set_rules('hra', "hra", 'required|trim');
        $this->form_validation->set_rules('ta', "ta", 'trim');
        $this->form_validation->set_rules('oa', "oa", 'trim');
        $this->form_validation->set_rules('pf_earning_limit', "pf_earning_limit", 'required|trim');
        $this->form_validation->set_rules('ey_pf', "ey_pf", 'required|trim');
        $this->form_validation->set_rules('er_epf', "er_epf", 'required|trim');
        $this->form_validation->set_rules('er_eps', "er_eps", 'required|trim');
        $this->form_validation->set_rules('er_edli', "er_edli", 'required|trim');
        $this->form_validation->set_rules('er_admin', "er_admin", 'required|trim');
        $this->form_validation->set_rules('dailywages_working_days', "dailywages_working_days", 'required|trim');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header');
            $this->load->view('admin/payroll/add_payroll_settings');
            $this->load->view('layout/footer');
        } else {
            $session_current       = $this->setting_model->getCurrentSession();
           
            $data = array(
                'id'                    => $this->input->post('id'),
                
                'da'           => $this->input->post('da'),
                'pp'          => $this->input->post('pp'),
                'hra'              => $this->input->post('hra'),
                'ta'             => $this->input->post('ta'),
                'oa '             => $this->input->post('oa'),
                'pf_earning_limit'             => $this->input->post('pf_earning_limit'),
                'ey_pf'             => $this->input->post('ey_pf'),
                'er_epf ' => $this->input->post('er_epf'),
                'er_eps '   => $this->input->post('er_eps'),
                'er_edli '   => $this->input->post('er_edli'),
                'er_admin '   => $this->input->post('er_admin'),
                'dailywages_working_days'   => $this->input->post('dailywages_working_days'),
                'payslip_prefix'   => $this->input->post('payslip_prefix'),
                'attendence_by'   => $this->input->post('attendence_by'),
            );
            $this->payroll_model->getPayrollSettingupdate($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/payroll/payroll_setting');
        }
    }

    public function get_amount()
    {
        $type = $this->input->post('type');
        $month = $this->input->post('month');
        $year = $this->input->post('year');
        $amount =  $this->payroll_model->get_amount($type,$month,$year);
       
        $json = array(
            "success" => "Data Updates Successfully!!!!",
            "amount" => $amount->total
        );

        echo json_encode($json);
    }
}
