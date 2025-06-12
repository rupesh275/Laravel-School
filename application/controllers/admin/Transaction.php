<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Transaction extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    function searchtransaction()
    {

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');

        $data['title'] = 'Search Expense';
        $data['searchlist'] = $this->customlib->get_searchtype();
        $data['search_type'] = $search_type = '';
        $search = $this->input->post('search_type');
        if (isset($_POST['search_type']) && $_POST['search_type'] != '') {

            $dates = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $_POST['search_type'];
        } else {

            $dates = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = $search_type = 'this_year';
        }

        $dateformat = $this->customlib->getSchoolDateFormat();

        $date_from = $dates['from_date'];
        $date_to = $dates['to_date'];

        $data['collection_title'] = $this->lang->line('collection') . " " . $this->lang->line('report') . " " . $this->lang->line('from') . " " . date($this->customlib->getSchoolDateFormat(), strtotime($date_from)) . " To " . date($this->customlib->getSchoolDateFormat(), strtotime($date_to));
        $data['income_title'] = $this->lang->line('income') . " " . $this->lang->line('report') . " " . $this->lang->line('from') . " " . date($this->customlib->getSchoolDateFormat(), strtotime($date_from)) . " To " . date($this->customlib->getSchoolDateFormat(), strtotime($date_to));
        $data['expense_title'] = $this->lang->line('expense') . " " . $this->lang->line('report') . " " . $this->lang->line('from') . " " . date($this->customlib->getSchoolDateFormat(), strtotime($date_from)) . " To " . date($this->customlib->getSchoolDateFormat(), strtotime($date_to));
        $data['payroll_title'] = $this->lang->line('payroll') . " " . $this->lang->line('report') . " " . $this->lang->line('from') . " " . date($this->customlib->getSchoolDateFormat(), strtotime($date_from)) . " To " . date($this->customlib->getSchoolDateFormat(), strtotime($date_to));
        $date_from = date('Y-m-d', strtotime($date_from));
        $date_to = date('Y-m-d', strtotime($date_to));
        $expenseList = $this->expense_model->search("", $date_from, $date_to);

        $result = $this->payroll_model->getbetweenpayrollReport($date_from, $date_to);

        $incomeList = $this->income_model->search("", $date_from, $date_to);
        $feeList = $this->studentfeemaster_model->getFeeBetweenDate($date_from, $date_to);
        $data['expenseList'] = $expenseList;
        $data['incomeList'] = $incomeList;
        $data['feeList'] = $feeList;
        $data['payrollList'] = $result;



        $this->load->view('layout/header', $data);
        $this->load->view('admin/transaction/searchtransaction', $data);
        $this->load->view('layout/footer', $data);
    }

    function studentacademicreport()
    {

        if (!$this->rbac->hasPrivilege('balance_fees_report', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/studentacademicreport');
        $data['title'] = 'student fee';
        $data['title'] = 'student fee';
        $class = $this->class_model->get();
        $data['sch_setting'] = $this->sch_setting_detail;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $data['classlist'] = $class;
        $prev_due = $this->input->post('prev_due');
        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        $feetype = $this->input->post('feetype');
        $feetype_arr = $this->input->post('feetype_arr');
        $data['section_list'] = $this->section_model->getClassBySection($this->input->post('class_id'));
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');


        if ($this->form_validation->run() == false) {
            $data['student_due_fee'] = array();
            $data['resultarray'] = array();

            $data['class_id'] = "";
            $data['section_id'] = "";
            $data['feetype'] = "";
            $data['feetype_arr'] = array();
        } else {
            $student_Array = array();

            $section = array();

            $classlist = $this->student_model->getAllClassSection($class_id, $section_id);

            foreach ($classlist as $key => $value) {
                $classid = $value['class_id'];
                $sectionid = $value['section_id'];

                $studentlist = $this->student_model->reportClassSection($classid, $sectionid);

                // echo "<pre>";
                // print_r ($studentlist);
                // echo "</pre>";


                $student_Array = array();
                if (!empty($studentlist)) {

                    foreach ($studentlist as $key => $eachstudent) {
                        $obj = new stdClass();
                        $obj->name = $this->customlib->getFullName($eachstudent['firstname'], $eachstudent['middlename'], $eachstudent['lastname'], $this->sch_setting_detail->middlename, $this->sch_setting_detail->lastname);
                        $obj->class = $eachstudent['class'];
                        $obj->section = $eachstudent['section'];
                        $obj->admission_no = $eachstudent['admission_no'];
                        $obj->roll_no = $eachstudent['roll_no'];
                        $obj->father_name = $eachstudent['father_name'];
                        $student_session_id = $eachstudent['student_session_id'];
                        $obj->student_session_id = $eachstudent['student_session_id'];

                        $student              = $this->student_model->getByStudentSession($student_session_id);
                        $student_sessionlist  = $this->student_model->get_studentsessionlist($student['id']);

                        // echo "<pre>";
                        // print_r ($student_sessionlist);die;
                        // echo "</pre>";

                        foreach ($student_sessionlist as  $studvalue) {
                            $student_total_fees[] = $this->studentfeemaster_model->getStudentFees($studvalue['id']);

                            // $previousfees           = $this->studentfeemaster_model->getPreviousStudentFees($studvalue['id']);


                        }


                        if (!empty($student_total_fees)) {

                            foreach ($student_total_fees as $student_total_fees_key => $student_total_fees_value) {


                                //  print_r ($student_total_fees_value);

                                if (!empty($student_total_fees_value)) {
                                    $totalfee = 0;
                                    $deposit = 0;
                                    $discount = 0;
                                    $balance = 0;
                                    $fine = 0;
                                    foreach ($student_total_fees_value as $fee) {

                                        foreach ($fee->fees as  $each_fee_value) {

                                            if (!empty($each_fee_value)) {
                                                $totalfee = $totalfee + $each_fee_value->amount;

                                                $amount_detail = json_decode($each_fee_value->amount_detail);

                                                if (is_object($amount_detail)) {
                                                    foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                                                        $deposit = $deposit + $amount_detail_value->amount;
                                                        $fine = $fine + $amount_detail_value->amount_fine;
                                                        $discount = $discount + $amount_detail_value->amount_discount;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            $obj->totalfee = $totalfee;
                            $obj->payment_mode = "N/A";
                            $obj->deposit = $deposit;
                            $obj->fine = $fine;
                            $obj->discount = $discount;
                            $obj->balance = $totalfee - ($deposit + $discount);
                            // echo "<br>".$obj->balance;

                        } else {

                            $obj->totalfee = 0;
                            $obj->payment_mode = 0;
                            $obj->deposit = 0;
                            $obj->fine = 0;
                            $obj->balance = 0;
                            $obj->discount = 0;
                        }
                        // echo "<pre>";
                        // print_r ($obj);
                        // echo "</pre>";

                        if ($obj->balance > 0) {
                            $student_Array[] = $obj;
                        }
                    }
                }

                $classlistdata[$value['class_id']][] = array('class_name' => $value['class'], 'section' => $value['section_id'], 'section_name' => $value['section'], 'result' => $student_Array);
            }





            $data['student_due_fee'] = $student_Array;
            $data['resultarray'] = $classlistdata;

            $data['class_id'] = $class_id;
            $data['section_id'] = $section_id;
            $data['feetype'] = $feetype;
            $data['feetype_arr'] = $feetype_arr;
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/transaction/studentAcademicReport', $data);
        $this->load->view('layout/footer', $data);
    }
    public function classfees_report()
    {
        
        if (!$this->rbac->hasPrivilege('classfees_report', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/classfees_report');
        $data['title'] = 'student fee';
        $data['title'] = 'student fee';
        $class = $this->class_model->get();
        $sch_section_result      = $this->examgroup_model->getsch_section();
        $data['sch_section_result'] = $sch_section_result;

        if ($this->input->server('REQUEST_METHOD') == "GET") {

            $data['class_section_list'] = $this->classsection_model->getClassSectionStudentCountall();
            $data['sch_setting'] = $this->sch_setting_detail;
            $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/transaction/classwisefeesReport', $data);
            $this->load->view('layout/footer', $data);
        } else {

            $sch_section_id = $this->input->post('sch_section_id');

            $data['class_section_list'] = $this->classsection_model->getClassSectionStudentall($sch_section_id);
            $data['sch_setting'] = $this->sch_setting_detail;
            $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;

            $this->load->view('layout/header', $data);
            $this->load->view('admin/transaction/classwisefeesReport', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function sectiondue_report()
    {
        if (!$this->rbac->hasPrivilege('sectiondue_report', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/sectiondue_report');
        $data['title'] = 'student fee';
        $data['title'] = 'student fee';
        $class = $this->class_model->get();
        $sch_section_result      = $this->examgroup_model->getsch_section();
        $data['sch_section_result'] = $sch_section_result;

        $data['class_section_list'] = $this->classsection_model->getClassSectionStudentCountall();
        $data['sch_setting'] = $this->sch_setting_detail;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $data['classlist'] = $class;



        $this->load->view('layout/header', $data);
        $this->load->view('admin/transaction/sectiondue_report', $data);
        $this->load->view('layout/footer', $data);
    }
    public function installwise_report_brief()
    {
        if (!$this->rbac->hasPrivilege('installwise_report', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/installwise_report_brief');
        $data['title'] = 'student brief fee Reports';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $sch_section_result      = $this->examgroup_model->getsch_section();
        $data['sch_section_result'] = $sch_section_result;

        if ($this->input->server('REQUEST_METHOD') == "GET") {

            $data['class_section_list'] = $this->classsection_model->getClassSectionStudentCountall();
            $data['sch_setting'] = $this->sch_setting_detail;
            $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/transaction/installwise_report_brief', $data);
            $this->load->view('layout/footer', $data);
        } else {

            $sch_section_id = $this->input->post('sch_section_id');
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $class_rec = $this->class_model->get($class_id);
            if(!empty($class_rec))
            {$data['class_name'] = $class_rec['code'];}
            else
            {$data['class_name'] = "";}
            $section_rec = $this->classsection_model->get_section($section_id);
            if(!empty($section_rec))
            {$data['division'] = $section_rec['section'];}
            else
            {$data['division'] = "";}
            $data['feetypeList'] = $this->feegrouptype_model->getmstbyclass_section($class_id, $section_id);
            //$data['students'] = $this->student_model->getStudentByClassSectionIDforinstall($class_id, $section_id);
            $data['students'] = $this->student_model->getStudentByClassSectionIDfordues($class_id, $section_id);
            

            $data['class_section_list'] = $this->classsection_model->getClassSectionStudentall($sch_section_id);
            $data['sch_setting'] = $this->sch_setting_detail;
            $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;

            $this->load->view('layout/header', $data);
            $this->load->view('admin/transaction/installwise_report_brief', $data);
            $this->load->view('layout/footer', $data);
        }
    }
    public function installwise_report()
    {
        if (!$this->rbac->hasPrivilege('installwise_report', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/installwise_report');
        $data['title'] = 'All Class Student Fee Reports';
        $class = $this->class_model->get();
        $data['classlist'] = $class;

        $sch_section_result      = $this->examgroup_model->getsch_section();
        $data['sch_section_result'] = $sch_section_result;
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $data['class_section_list'] = $this->classsection_model->getClassSectionStudentCountall();
            $data['sch_setting'] = $this->sch_setting_detail;
            $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/transaction/installwise_report', $data);
            $this->load->view('layout/footer', $data);
        } else {

            $sch_section_id = $this->input->post('sch_section_id');
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $data['asondate']=$this->input->post('asondate');
            $class_rec = $this->class_model->get($class_id);
            if(!empty($class_rec))
            {$data['class_name'] = $class_rec['code'];}
            else
            {$data['class_name'] = "";}
            $section_rec = $this->classsection_model->get_section($section_id);
            if(!empty($section_rec))
            {$data['division'] = $section_rec['section'];}
            else
            {$data['division'] = "";}
            $data['feetypeList'] = $this->feegrouptype_model->getmstbyclass_section($class_id, $section_id);
            $data['students'] = $this->student_model->getStudentByClassSectionIDfordues($class_id, $section_id);
            $data['class_section_list'] = $this->classsection_model->getClassSectionStudentall($sch_section_id);
            $data['sch_setting'] = $this->sch_setting_detail;
            $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/transaction/installwise_report', $data);
            $this->load->view('layout/footer', $data);
        }
    }
    public function studentWise_fees_statement()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);        
        if (!$this->rbac->hasPrivilege('studentWise_fees_statement', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/studentWise_fees_statement');
        $data['title'] = 'student fee';
        $data['title'] = 'student fee';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $sch_section_result      = $this->examgroup_model->getsch_section();
        $data['sch_section_result'] = $sch_section_result;
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $data['class_section_list'] = $this->classsection_model->getClassSectionStudentCountall();

            $data['sch_setting'] = $this->sch_setting_detail;
            $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
            $data['sessionlist'] = $this->session_model->get();
            $this->load->view('layout/header', $data);
            $this->load->view('admin/transaction/studentWise_fees_statement', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $sch_section_id = $this->input->post('sch_section_id');
            $data['asondate']= $class_id = $this->input->post('asondate');
            $data['rep_session_id']= $class_id = $this->input->post('rep_session_id');
            $data['class_id']= $class_id = $this->input->post('class_id');
            $data['section_id'] = $section_id = $this->input->post('section_id');
            $data['sessionlist'] = $this->session_model->get();
            $data['fine_result'] = $this->studentfee_model->get_all_fine_settings();
            //$data['resultlist'] = $this->student_model->getStudentByClassSectionIDforstatement($class_id, $section_id);
            //$data['resultlist'] = $this->student_model->getStudentByClassSectionIDforstatementwithinactive($class_id, $section_id,$rep_session_id);
            $data['class_section_list'] = $this->classsection_model->getClassSectionStudentall($sch_section_id);
            $data['sch_setting'] = $this->sch_setting_detail;
            $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/transaction/studentWise_fees_statement', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function installwise_report_all()
    {
        if (!$this->rbac->hasPrivilege('installwise_report', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/installwise_report_all');
        $data['title'] = 'student fee';
        $data['title'] = 'student fee';
        $class = $this->classsection_model->get_detailed();
        $data['classlist'] = $class;
        $sch_section_result      = $this->examgroup_model->getsch_section();
        $data['sch_section_result'] = $sch_section_result;
            //$data['feetypeList'] = $this->feegrouptype_model->getmstbyclass_section($class_id, $section_id);
            //$data['students'] = $this->student_model->getStudentByClassSectionIDforinstall($class_id, $section_id);
            if ($this->input->server('REQUEST_METHOD') == "GET") {
                $data['class_section_list'] = array();//$this->classsection_model->getClassSectionStudentall($sch_section_id);
                $data['sch_setting'] = $this->sch_setting_detail;
                $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
                $data['sessionlist'] = $this->session_model->get();
                $this->load->view('layout/header', $data);
                $this->load->view('admin/transaction/installwise_report_all', $data);
                $this->load->view('layout/footer', $data);
            }
            else
            {
                $data['rep_session_id']= $class_id = $this->input->post('rep_session_id');
                $data['asondate']=$this->input->post('asondate');
                $data['class_section_list'] = array();//$this->classsection_model->getClassSectionStudentall($sch_section_id);
                $data['sch_setting'] = $this->sch_setting_detail;
                $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
                $data['sessionlist'] = $this->session_model->get();
                $this->load->view('layout/header', $data);
                $this->load->view('admin/transaction/installwise_report_all', $data);
                $this->load->view('layout/footer', $data);                
            }
    }
    public function installwise_report_all_brief()
    {
        if (!$this->rbac->hasPrivilege('installwise_report', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/installwise_report_all');
        $data['title'] = 'student fee';
        $data['title'] = 'student fee';
        $class = $this->classsection_model->get_detailed();
        $data['classlist'] = $class;
        $sch_section_result      = $this->examgroup_model->getsch_section();
        $data['sch_section_result'] = $sch_section_result;
            //$data['feetypeList'] = $this->feegrouptype_model->getmstbyclass_section($class_id, $section_id);
            //$data['students'] = $this->student_model->getStudentByClassSectionIDforinstall($class_id, $section_id);
            if ($this->input->server('REQUEST_METHOD') == "GET") {
            $data['class_section_list'] = array();//$this->classsection_model->getClassSectionStudentall($sch_section_id);
            $data['sch_setting'] = $this->sch_setting_detail;
            $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;

            $this->load->view('layout/header', $data);
            $this->load->view('admin/transaction/installwise_report_all_brief', $data);
            $this->load->view('layout/footer', $data);
            }
            else
            {
                $data['asondate']=$this->input->post('asondate');
                $data['class_section_list'] = array();//$this->classsection_model->getClassSectionStudentall($sch_section_id);
                $data['sch_setting'] = $this->sch_setting_detail;
                $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
    
                $this->load->view('layout/header', $data);
                $this->load->view('admin/transaction/installwise_report_all_brief', $data);
                $this->load->view('layout/footer', $data);                
            }
    }    
    public function classwise_installreport_brief()
    {
        if (!$this->rbac->hasPrivilege('classwise_installreport', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/classwise_installreport_brief');
        $data['title'] = 'Class Wise Fees Reports';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $sch_section_result      = $this->examgroup_model->getsch_section();
        $data['sch_section_result'] = $sch_section_result;
        // if ($this->input->server('REQUEST_METHOD') == "GET") {
        $data['class_section_list'] = $this->classsection_model->getClassSectionStudentCountall();
        $data['sch_setting'] = $this->sch_setting_detail;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        // $this->load->view('layout/header', $data);
        // $this->load->view('admin/transaction/classwise_installreport', $data);
        // $this->load->view('layout/footer', $data);
        // } else {
        $sch_section_id = $this->input->post('sch_section_id');
        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        // $data['feetypeList'] = $this->feegrouptype_model->getmstbyclass_section($class_id,$section_id);
        // $data['students'] = $this->student_model->getStudentByClassSectionID($class_id,$section_id);
        $data['class_section_list'] = $this->classsection_model->getClassSectionStudentall($sch_section_id);
        $data['sch_setting'] = $this->sch_setting_detail;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/transaction/classwise_installreport_brief', $data);
        $this->load->view('layout/footer', $data);
        // }
    }    
    public function classwise_installreport()
    {
        if (!$this->rbac->hasPrivilege('classwise_installreport', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/classwise_installreport');
        $data['title'] = 'student fee';
        $data['title'] = 'student fee';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $sch_section_result      = $this->examgroup_model->getsch_section();
        $data['sch_section_result'] = $sch_section_result;
         if ($this->input->server('REQUEST_METHOD') == "GET") {
            $data['class_section_list'] = $this->classsection_model->getClassSectionStudentCountall();
            $data['sch_setting'] = $this->sch_setting_detail;
            $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/transaction/classwise_installreport', $data);
            $this->load->view('layout/footer', $data);
         } else {
            $data['asondate']=$this->input->post('asondate');
            $data['class_section_list'] = $this->classsection_model->getClassSectionStudentCountall();
            $data['sch_setting'] = $this->sch_setting_detail;
            $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/transaction/classwise_installreport', $data);
            $this->load->view('layout/footer', $data);
         }
    }
    public function classwise_installreport_detailed()
    {
        if (!$this->rbac->hasPrivilege('classwise_installreport_detailed', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/classwise_installreport_detailed');
        $data['title'] = 'student fee';
        $data['title'] = 'student fee';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $sch_section_result      = $this->examgroup_model->getsch_section();
        $data['sch_section_result'] = $sch_section_result;

        // if ($this->input->server('REQUEST_METHOD') == "GET") {

        $data['class_section_list'] = $this->classsection_model->getClassSectionStudentCountall();
        $data['sch_setting'] = $this->sch_setting_detail;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        // $this->load->view('layout/header', $data);
        // $this->load->view('admin/transaction/classwise_installreport', $data);
        // $this->load->view('layout/footer', $data);
        // } else {

        $sch_section_id = $this->input->post('sch_section_id');
        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        // $data['feetypeList'] = $this->feegrouptype_model->getmstbyclass_section($class_id,$section_id);
        // $data['students'] = $this->student_model->getStudentByClassSectionID($class_id,$section_id);


        $data['class_section_list'] = $this->classsection_model->getClassSectionStudentall($sch_section_id);
        $data['sch_setting'] = $this->sch_setting_detail;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/transaction/classwise_installreport_detailed', $data);
        $this->load->view('layout/footer', $data);
        // }
    }

    public function classwise_installreport_others()
    {
        if (!$this->rbac->hasPrivilege('classwise_installreport_others', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/classwise_installreport_others');
        $data['title'] = 'student fee';
        $data['title'] = 'student fee';
        $class = $this->classsection_model->get_detailed();
        $data['classlist'] = $class;
        $sch_section_result      = $this->examgroup_model->getsch_section();
        $data['sch_section_result'] = $sch_section_result;
            //$data['feetypeList'] = $this->feegrouptype_model->getmstbyclass_section($class_id, $section_id);
            //$data['students'] = $this->student_model->getStudentByClassSectionIDforinstall($class_id, $section_id);
            if ($this->input->server('REQUEST_METHOD') == "GET") {
            $data['class_section_list'] = array();//$this->classsection_model->getClassSectionStudentall($sch_section_id);
            $data['sch_setting'] = $this->sch_setting_detail;
            $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;

            $this->load->view('layout/header', $data);
            $this->load->view('admin/transaction/classwise_installreport_others', $data);
            $this->load->view('layout/footer', $data);
            }
            else
            {
                $data['asondate']=$this->input->post('asondate');
                $data['withinactive']=$this->input->post('withinactive');
                
                $data['class_section_list'] = array();//$this->classsection_model->getClassSectionStudentall($sch_section_id);
                $data['sch_setting'] = $this->sch_setting_detail;
                $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
                $this->load->view('layout/header', $data);
                $this->load->view('admin/transaction/classwise_installreport_others', $data);
                $this->load->view('layout/footer', $data);                
            }
    }    
    public function classwise_installreport_others_caution()
    {
        if (!$this->rbac->hasPrivilege('classwise_installreport_others', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/classwise_installreport_others');
        $data['title'] = 'student fee';
        $data['title'] = 'student fee';
        $class = $this->classsection_model->get_detailed();
        $data['classlist'] = $class;
        $sch_section_result      = $this->examgroup_model->getsch_section();
        $data['sch_section_result'] = $sch_section_result;
            //$data['feetypeList'] = $this->feegrouptype_model->getmstbyclass_section($class_id, $section_id);
            //$data['students'] = $this->student_model->getStudentByClassSectionIDforinstall($class_id, $section_id);
            if ($this->input->server('REQUEST_METHOD') == "GET") {
                $data['class_section_list'] = array();//$this->classsection_model->getClassSectionStudentall($sch_section_id);
                $data['sch_setting'] = $this->sch_setting_detail;
                $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
                $this->load->view('layout/header', $data);
                $this->load->view('admin/transaction/classwise_installreport_others_caution', $data);
                $this->load->view('layout/footer', $data);
            }
            else
            {
                $data['asondate']=$this->input->post('asondate');
                $data['withinactive']=$this->input->post('withinactive');
                $data['class_section_list'] = array();//$this->classsection_model->getClassSectionStudentall($sch_section_id);
                $data['sch_setting'] = $this->sch_setting_detail;
                $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
                $this->load->view('layout/header', $data);
                $this->load->view('admin/transaction/classwise_installreport_others_caution', $data);
                $this->load->view('layout/footer', $data);                
            }
    }    

    public function section_installreport_brief()
    {
        if (!$this->rbac->hasPrivilege('section_installreport', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/section_installreport_brief');
        $data['title'] = 'student fee';
        $data['title'] = 'student fee';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $sch_section_result      = $this->examgroup_model->getsch_section();
        $data['sch_section_result'] = $sch_section_result;


        $data['class_section_list'] = $this->classsection_model->getClassSectionStudentCountall();
        $data['sch_setting'] = $this->sch_setting_detail;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;

        $sch_section_id = $this->input->post('sch_section_id');
        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        // $data['feetypeList'] = $this->feegrouptype_model->getmstbyclass_section($class_id,$section_id);
        // $data['students'] = $this->student_model->getStudentByClassSectionID($class_id,$section_id);


        $data['class_section_list'] = $this->classsection_model->getClassSectionStudentall($sch_section_id);
        $data['sch_setting'] = $this->sch_setting_detail;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/transaction/section_installreport_brief', $data);
        $this->load->view('layout/footer', $data);
    }

    public function section_installreport()
    {
        if (!$this->rbac->hasPrivilege('section_installreport', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/section_installreport');
        $data['title'] = 'student fee';
        $data['title'] = 'student fee';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $sch_section_result      = $this->examgroup_model->getsch_section();
        $data['sch_section_result'] = $sch_section_result;


        $data['class_section_list'] = $this->classsection_model->getClassSectionStudentCountall();
        $data['sch_setting'] = $this->sch_setting_detail;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;

        $sch_section_id = $this->input->post('sch_section_id');
        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        // $data['feetypeList'] = $this->feegrouptype_model->getmstbyclass_section($class_id,$section_id);
        // $data['students'] = $this->student_model->getStudentByClassSectionID($class_id,$section_id);


        $data['class_section_list'] = $this->classsection_model->getClassSectionStudentall($sch_section_id);
        $data['sch_setting'] = $this->sch_setting_detail;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/transaction/section_installreport', $data);
        $this->load->view('layout/footer', $data);
    }

    public function studentWise_report()
    {
        if (!$this->rbac->hasPrivilege('studentWise_report', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/studentWise_report');
        $data['title'] = 'student fee';
        $data['title'] = 'student fee';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $sch_section_result      = $this->examgroup_model->getsch_section();
        $data['sch_section_result'] = $sch_section_result;
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $data['class_section_list'] = $this->classsection_model->getClassSectionStudentCountall();
            $data['sch_setting'] = $this->sch_setting_detail;
            $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
            
            $this->load->view('layout/header', $data);
            $this->load->view('admin/transaction/studentWise_report', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $sch_section_id = $this->input->post('sch_section_id');
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $class_rec = $this->class_model->get($class_id);
            if(!empty($class_rec))
            {$data['class_name'] = $class_rec['code'];}
            else
            {$data['class_name'] = "";}
            $section_rec = $this->classsection_model->get_section($section_id);
            if(!empty($section_rec))
            {$data['division'] = $section_rec['section'];}
            else
            {$data['division'] = "";}
            $data['feetypeList'] = $this->feegrouptype_model->getmstbyclass_section($class_id, $section_id);
            $data['students'] = $this->student_model->getStudentByClassSectionIDforinstall($class_id, $section_id);
            $data['class_section_list'] = $this->classsection_model->getClassSectionStudentall($sch_section_id);
            $data['sch_setting'] = $this->sch_setting_detail;
            $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
            $data['current_session'] = $this->current_session;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/transaction/studentWise_report', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function test()
    {
        ini_set('display_errors', 1);
       $student_session_id = 2985;
       $student_fees_master = $this->studentfeemaster_model->get_by_student_session_id($student_session_id);
       $first_installment = $student_fees_master[0]; 
       $second_installment = $student_fees_master[1];
       $third_installment = $student_fees_master[2];
       echo "<pre>";
       print_r($student_fees_master);
    }

}
