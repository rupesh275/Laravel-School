<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Studentfee extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');
        $this->search_type        = $this->config->item('search_type');
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->load->model("leaverequest_model");
        $settings              = $this->setting_model->getSetting();
        $this->current_ay_session = $settings->session_id;          
    }
    public function index()
    {
        if (!$this->rbac->hasPrivilege('collect_fees', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', $this->lang->line('fees_collection'));
        $this->session->set_userdata('sub_menu', 'studentfee/index');
        $data['sch_setting'] = $this->sch_setting_detail;
        $data['title']       = 'student fees';
        $class               = $this->class_model->get();
        $data['classlist']   = $class;
        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/studentfeeSearch', $data);
        $this->load->view('layout/footer', $data);
    }
    public function collection_report()
    {
        if (!$this->rbac->hasPrivilege('collect_fees', 'can_view')) {
            access_denied();
        }

        $data['collect_by'] = $this->studentfeemaster_model->get_feesreceived_by();
        $data['searchlist'] = $this->customlib->get_searchtype();
        $data['group_by']   = $this->customlib->get_groupby();
        $feetype = $this->feetype_model->get();
        $data['feetypeList'] = $feetype;
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/collection_report');
        $subtotal = false;
        if (isset($_POST['search_type']) && $_POST['search_type'] != '') {
            $dates               = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $_POST['search_type'];
        } else {
            $dates               = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = '';
        }

        if (isset($_POST['collect_by']) && $_POST['collect_by'] != '') {
            $data['received_by'] = $received_by = $_POST['collect_by'];
        } else {
            $data['received_by'] = $received_by = '';
        }

        if (isset($_POST['feetype_id']) && $_POST['feetype_id'] != '') {
            $feetype_id = $_POST['feetype_id'];
        } else {
            $feetype_id = "";
        }

        if (isset($_POST['group']) && $_POST['group'] != '') {
            $data['group_byid'] = $group = $_POST['group'];
            $subtotal = true;
        } else {
            $data['group_byid'] = $group = '';
        }

        $collect_by          = array();
        $collection          = array();
        $start_date          = date('Y-m-d', strtotime($dates['from_date']));
        $end_date            = date('Y-m-d', strtotime($dates['to_date']));
        $data['start_date']          = $dates['from_date'];
        $data['end_date']            = $dates['to_date'];
        $data['rec_session_id'] = $this->current_ay_session;
        $this->form_validation->set_rules('search_type', $this->lang->line('search') . " " . $this->lang->line('type'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $data['results'] = array();
        } else {
            $data['results'] = $this->studentfeemaster_model->getFeeCollectionReport($start_date, $end_date, $feetype_id, $received_by, $group);

            if ($group != '') {

                if ($group == 'class') {

                    $group_by = 'class_id';
                } elseif ($group == 'collection') {

                    $group_by = 'received_by';
                } elseif ($group == 'mode') {

                    $group_by = 'payment_mode';
                }

                foreach ($data['results'] as $key => $value) {

                    $collection[$value[$group_by]][] = $value;
                }
            } else {

                $s = 0;
                foreach ($data['results'] as $key => $value) {

                    $collection[$s++] = array($value);
                }
            }

            $data['results'] = $collection;
        }
        $data['subtotal'] = $subtotal;
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/collection_report', $data);
        $this->load->view('layout/footer', $data);
    }

    public function pdf()
    {
        $this->load->helper('pdf_helper');
    }

    public function search()
    {
        $search_type = $this->input->post('search_type');
        if ($search_type == "class_search") {
            $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'required|trim|xss_clean');
        } elseif ($search_type == "keyword_search") {
            $this->form_validation->set_rules('search_text', 'keyword --r', 'required|trim|xss_clean');
            $data = array('search_text' => 'dummy');

            $this->form_validation->set_data($data);
        }
        if ($this->form_validation->run() == false) {
            $error = array();
            if ($search_type == "class_search") {
                $error['class_id'] = form_error('class_id');
            } elseif ($search_type == "keyword_search") {
                $error['search_text'] = form_error('search_text');
            }

            $array = array('status' => 0, 'error' => $error);
            echo json_encode($array);
        } else {
            $search_type = $this->input->post('search_type');
            $search_text = $this->input->post('search_text');
            $class_id    = $this->input->post('class_id');
            $section_id  = $this->input->post('section_id');
            $params      = array('class_id' => $class_id, 'section_id' => $section_id, 'search_type' => $search_type, 'search_text' => $search_text);
            $array       = array('status' => 1, 'error' => '', 'params' => $params);
            echo json_encode($array);
        }
    }

    public function ajaxSearch()
    {
        $class       = $this->input->post('class_id');
        $section     = $this->input->post('section_id');
        $search_text = $this->input->post('search_text');
        $search_type = $this->input->post('search_type');
        if ($search_type == "class_search") {
            $students = $this->student_model->getDatatableByClassSectionForFees($class, $section);
        } elseif ($search_type == "keyword_search") {
            $students = $this->student_model->getDatatableByFullTextSearchForFees($search_text);
        }
        $sch_setting = $this->sch_setting_detail;
        $students = json_decode($students);
        $dt_data  = array();
        if (!empty($students->data)) {
            foreach ($students->data as $student_key => $student) {
                $row   = array();
                $row[] = $student->class;
                $row[] = $student->section;
                $row[] = $student->roll_no;
                $row[] = $student->admission_no;
                $row[] = "<a target='_blank'  href='" . base_url() . "student/view/" . $student->id . "'>" . $this->customlib->getFullName($student->firstname, $student->middlename, $student->lastname, $sch_setting->middlename, $sch_setting->lastname) . "</a>";
                $sch_setting = $this->sch_setting_detail;
                if ($sch_setting->father_name) {
                    $row[] = $student->father_name;
                }
                // $row[] = $this->customlib->dateformat($student->dob);
                $row[] = $student->guardian_phone;
                $row[] = "<a target='_blank' href=" . site_url('studentfee/addfee/' . $student->student_session_id) . "  class='btn btn-info btn-xs'>" . $this->lang->line('collect_fees') . "</a>";

                $dt_data[] = $row;
            }
        }
        $json_data = array(
            "draw"            => intval($students->draw),
            "recordsTotal"    => intval($students->recordsTotal),
            "recordsFiltered" => intval($students->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }
    public function feesearch()
    {
        if (!$this->rbac->hasPrivilege('search_due_fees', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'studentfee/feesearch');
        $data['title']       = 'student fees';
        $class               = $this->class_model->get();
        $data['classlist']   = $class;
        $data['sch_setting'] = $this->sch_setting_detail;
        $feesessiongroup     = $this->feesessiongroup_model->getFeesByGroup();

        $data['feesessiongrouplist'] = $feesessiongroup;
        $data['fees_group']          = "";
        if (isset($_POST['feegroup_id']) && $_POST['feegroup_id'] != '') {
            $data['fees_group'] = $_POST['feegroup_id'];
        }

        $this->form_validation->set_rules('feegroup_id', $this->lang->line('fee_group'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('studentfee/studentSearchFee', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data['student_due_fee'] = array();
            $feegroup_id             = $this->input->post('feegroup_id');
            $feegroup                = explode("-", $feegroup_id);
            $feegroup_id             = $feegroup[0];
            $fee_groups_feetype_id   = $feegroup[1];
            $class_id                = $this->input->post('class_id');
            $section_id              = $this->input->post('section_id');
            $student_due_fee         = $this->studentfee_model->getDueStudentFees($feegroup_id, $fee_groups_feetype_id, $class_id, $section_id);
            if (!empty($student_due_fee)) {
                foreach ($student_due_fee as $student_due_fee_key => $student_due_fee_value) {
                    $amt_due                                                  = $student_due_fee_value['amount'];
                    $student_due_fee[$student_due_fee_key]['amount_discount'] = 0;
                    $student_due_fee[$student_due_fee_key]['amount_fine']     = 0;
                    $a                                                        = json_decode($student_due_fee_value['amount_detail']);
                    if (!empty($a)) {
                        $amount          = 0;
                        $amount_discount = 0;
                        $amount_fine     = 0;

                        foreach ($a as $a_key => $a_value) {
                            $amount          = $amount + $a_value->amount;
                            $amount_discount = $amount_discount + $a_value->amount_discount;
                            $amount_fine     = $amount_fine + $a_value->amount_fine;
                        }
                        if ($amt_due <= $amount) {
                            unset($student_due_fee[$student_due_fee_key]);
                        } else {

                            $student_due_fee[$student_due_fee_key]['amount_detail']   = $amount;
                            $student_due_fee[$student_due_fee_key]['amount_discount'] = $amount_discount;
                            $student_due_fee[$student_due_fee_key]['amount_fine']     = $amount_fine;
                        }
                    }
                }
            }

            $data['student_due_fee'] = $student_due_fee;
            $this->load->view('layout/header', $data);
            $this->load->view('studentfee/studentSearchFee', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function reportbyname()
    {
        if (!$this->rbac->hasPrivilege('fees_statement', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/reportbyname');
        $data['title']       = 'student fees';
        $data['title']       = 'student fees';
        $class               = $this->class_model->get();
        $data['classlist']   = $class;
        $data['sch_setting'] = $this->sch_setting_detail;

        if ($this->input->server('REQUEST_METHOD') == "GET") {

            $this->load->view('layout/header', $data);
            $this->load->view('studentfee/reportByName', $data);
            $this->load->view('layout/footer', $data);
        } else { {

                $data['student_due_fee'] = array();
                $class_id                = $this->input->post('class_id');
                $section_id              = $this->input->post('section_id');
                $student_id              = $this->input->post('student_id');
                $student_due_fee              = $this->studentfeemaster_model->getStudentFeesByClassSectionStudent($class_id, $section_id, $student_id);
                $data['student_due_fee']      = $student_due_fee;
                $data['class_id']             = $class_id;
                $data['section_id']           = $section_id;
                $data['student_id']           = $student_id;
                $category                     = $this->category_model->get();
                $data['categorylist']         = $category;
                $this->load->view('layout/header', $data);
                $this->load->view('studentfee/reportByName', $data);
                $this->load->view('layout/footer', $data);
            }
        }
    }

    public function reportbyclass()
    {
        $data['title']     = 'student fees';
        $data['title']     = 'student fees';
        $class             = $this->class_model->get();
        $data['classlist'] = $class;
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('studentfee/reportByClass', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $student_fees_array      = array();
            $class_id                = $this->input->post('class_id');
            $section_id              = $this->input->post('section_id');
            $student_result          = $this->student_model->searchByClassSection($class_id, $section_id);
            $data['student_due_fee'] = array();
            if (!empty($student_result)) {
                foreach ($student_result as $key => $student) {
                    $student_array                      = array();
                    $student_array['student_detail']    = $student;
                    $student_session_id                 = $student['student_session_id'];
                    $student_id                         = $student['id'];
                    $student_due_fee                    = $this->studentfee_model->getDueFeeBystudentSection($class_id, $section_id, $student_session_id);
                    $student_array['fee_detail']        = $student_due_fee;
                    $student_fees_array[$student['id']] = $student_array;
                }
            }
            $data['class_id']           = $class_id;
            $data['section_id']         = $section_id;
            $data['student_fees_array'] = $student_fees_array;
            $this->load->view('layout/header', $data);
            $this->load->view('studentfee/reportByClass', $data);
            $this->load->view('layout/footer', $data);
        }
    }
    public function view($id)
    {
        if (!$this->rbac->hasPrivilege('collect_fees', 'can_view')) {
            access_denied();
        }
        $data['title']      = 'studentfee List';
        $studentfee         = $this->studentfee_model->get($id);
        $data['studentfee'] = $studentfee;
        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/studentfeeShow', $data);
        $this->load->view('layout/footer', $data);
    }
    public function deleteFee_session()
    {
        if (!$this->rbac->hasPrivilege('collect_fees', 'can_delete')) {
            access_denied();
        }
        $invoice_id  = $this->input->post('main_invoice');
        $sub_invoice = $this->input->post('sub_invoice');
        $session_id  = $this->input->post('session_id');
        $reason      = $this->input->post('reason');

        if($session_id=='')
        {
            $array = array('status' => 'error', 'result' => 'Invalid Session');
        }
        else
        {
            if (!empty($invoice_id)) {
                $receipt = $this->studentfee_model->getReceipt($invoice_id,$session_id);
                if($receipt->receipt_type == 'fees')
                {
                    $this->studentfee_model->remove_session($invoice_id, $sub_invoice,$receipt->session_id,$receipt->student_session_id);
                    $data = array('status' => 'assigned', 'payment_id' => null, 'description' => null);
                    $this->feediscount_model->updateStudentDiscountbypayment_session($data, $sub_invoice,$receipt->session_id,$receipt->student_session_id);
                    $receipt                        = $this->studentfee_model->getReceipt($sub_invoice,$session_id);
                    if($receipt->chequeid > 0)
                    {
                        $this->feemaster_model->removeCheque($receipt->chequeid);
                    }            
                    $arrayUpdate = array(
                        'id'                  => $sub_invoice,
                        'status'              => 'Cancelled',
                        'cancelled_at'        => date('Y-m-d h:m:s'),
                        'cancel_reason'       => $reason,
                        'canceled_by'         => $this->customlib->getAdminSessionUserName()
                    );
                    $this->studentfeemaster_model->update_receipt($arrayUpdate,$session_id);

                    $student_session_id = $receipt->student_session_id;
                    $net_amt = $receipt->net_amt;
                    $student                = $this->studentsession_model->searchStudentsBySession($student_session_id);
                    $st_name = strtoupper($student['firstname']." ".$student['middlename']." ".$student['lastname']);
                    $class_div = $student['code']."-".$student['section'];
                    $mobno = $this->staff_model->get_class_teacher($student_session_id);
                    if($mobno)
                    {
                        $data_msg1 = array(
                            "mobno" => $mobno,
                            "name" => $st_name,
                            "class" => $class_div,
                            "amount" => $net_amt,
                            "rec_no" => $sub_invoice,
                            "cancel_date" => date('d-m-Y h:m:s')
                        );
                        $this->wati_model->send_receipt_cancel_to_class_teacher($data_msg1);                
                    }            
                }
                elseif($receipt->receipt_type == 'general')
                {
                    $student_session_id = $receipt->student_session_id;
                    $this->studentfee_model->cancel_student_fine_collection($sub_invoice,$session_id,$student_session_id);                
                    $arrayUpdate = array(
                        'id'                        => $sub_invoice,
                        'status'              => 'Cancelled',
                        'cancelled_at'        => date('Y-m-d h:m:s'),
                        'cancel_reason'       => $reason,
                        'canceled_by'       => $this->customlib->getAdminSessionUserName()
                    );
                    $this->studentfeemaster_model->update_receipt($arrayUpdate,$session_id);  
                    
                }
                $array = array('status' => 'success', 'result' => 'success');    
            }
            else
            {
                $array = array('status' => 'error', 'result' => 'Invalid Receipt id');
            }
        }
        echo json_encode($array);
    }
    // public function deleteFee()
    // {
    //     if (!$this->rbac->hasPrivilege('collect_fees', 'can_delete')) {
    //         access_denied();
    //     }
    //     $invoice_id  = $this->input->post('main_invoice');
    //     $sub_invoice = $this->input->post('sub_invoice');
    //     $reason = $this->input->post('reason');
    //     if (!empty($invoice_id)) {
    //         $this->studentfee_model->remove($invoice_id, $sub_invoice);
    //         $data = array('status' => 'assigned', 'payment_id' => null, 'description' => null);
    //         $this->feediscount_model->updateStudentDiscountbypayment($data, $sub_invoice);
    //         $receipt                        = $this->studentfee_model->getReceipt($sub_invoice);
    //         if($receipt->chequeid > 0)
    //         {
    //             $this->feemaster_model->removeCheque($receipt->chequeid);
    //         }
    //         $arrayUpdate = array(
    //             'id'                        => $sub_invoice,
    //             'status'              => 'Cancelled',
    //             'cancelled_at'        => date('Y-m-d h:m:s'),
    //             'cancel_reason'       => $reason,
    //             'canceled_by'       => $this->customlib->getAdminSessionUserName()
    //         );
    //         $this->studentfeemaster_model->update_receipt($arrayUpdate);
    //         $student_session_id = $receipt->student_session_id;
    //         $net_amt = $receipt->net_amt;
    //         $student                = $this->studentsession_model->searchStudentsBySession($student_session_id);
    //         $st_name = strtoupper($student['firstname']." ".$student['middlename']." ".$student['lastname']);
    //         $class_div = $student['code']."-".$student['section'];
    //         $mobno = $this->staff_model->get_class_teacher($student_session_id);
    //         if($mobno)
    //         {
    //             $data_msg1 = array(
    //                 "mobno" => $mobno,
    //                 "name" => $st_name,
    //                 "class" => $class_div,
    //                 "amount" => $net_amt,
    //                 "rec_no" => $sub_invoice,
    //                 "cancel_date" => date('d-m-Y h:m:s')
    //             );
    //             $this->wati_model->send_receipt_cancel_to_class_teacher($data_msg1);                
    //         }
    //     }
    //     $array = array('status' => 'success', 'result' => 'success');
    //     echo json_encode($array);
    // }
    public function deleteStudentDiscount()
    {

        $discount_id = $this->input->post('discount_id');
        if (!empty($discount_id)) {
            $data = array('id' => $discount_id, 'status' => 'assigned', 'payment_id' => null, 'description' => null);
            $this->feediscount_model->updateStudentDiscount($data);
        }
        $array = array('status' => 'success', 'result' => 'success');
        echo json_encode($array);
    }
    public function updateReceipt()
    {
//        ini_set ('display_errors', 1); ini_set ('display_startup_errors', 1); error_reporting (E_ALL);        
        if (!$this->rbac->hasPrivilege('collect_fees', 'can_delete')) {
            access_denied();
        }
        $sub_invoice = $this->input->post('sub_invoice');
        $payment_mode = $this->input->post('payment_mode');
        $note = $this->input->post('note');
        $session_id = $this->input->post('session_id');
        if($session_id == "")
        {
            $array = array('status' => 'error', 'result' => 'Invalid Receipt Session.. or empty..');
        }
        else
        {
            if (!empty($sub_invoice)) {                
                $arrayUpdate = array(
                    'id'                        => $sub_invoice,
                    'payment_mode'              => $payment_mode,
                    'note'        => $note,
                    'last_updated_at' => date('Y-m-d h:m:s'),
                    'last_updated_by' => $this->customlib->getAdminSessionUserName(),
                );
                $this->studentfee_model->update_student_fees_paid($sub_invoice,$payment_mode,$note,$session_id,$arrayUpdate);
            }
            $array = array('status' => 'success', 'result' => 'success');
        }
        echo json_encode($array);
    }

    public function changeoctype()
    {
//        ini_set ('display_errors', 1); ini_set ('display_startup_errors', 1); error_reporting (E_ALL);        
        if (!$this->rbac->hasPrivilege('collect_fees', 'can_delete')) {
            access_denied();
        }
        $sub_invoice = $this->input->post('sub_invoice');
        $session_id = $this->input->post('session_id');
        $oc_type = $this->input->post('oc_type');
        $tb_name = 'fee_receipt_no_' . $session_id;
        if($session_id == "")
        {
            $array = array('status' => 'error', 'result' => 'Invalid Receipt Session.. or empty..');
        }
        else
        {
            $arrayUpdate = array(
                'oc_type'       => $oc_type,
            );            
            $this->db->where('id', $sub_invoice);
            $this->db->update($tb_name, $arrayUpdate);
            $array = array('status' => 'success', 'result' => 'success',"id"=>$sub_invoice);
        }
        echo json_encode($array);
    }

    public function getcollectfee()
    {
        $setting_result                  = $this->setting_model->get();
        $data['settinglist']             = $setting_result;
        $data['student_session_id']      = $this->input->post('student_session_id');
        $data['total_main_fees']         = $this->input->post('total_main_fees');
        $data['total_other_fees']        = $this->input->post('total_other_fees');
        $data['total_balance_fees']      = $this->input->post('total_balance_fees');
        $data['enable_auto_disc']        = $this->input->post('enable_auto_disc');
        $fine_selection          = $this->input->post('fine_selection');
        $data['prev_paid']               = $this->input->post('prev_paid');
        $record                          = $this->input->post('data');
        $record_array                    = json_decode($record);
        $fees_array = array();
        $selected_amt = 0;
        foreach ($record_array as $key => $value) {
            $fee_groups_feetype_id = $value->fee_groups_feetype_id;
            $fee_master_id         = $value->fee_master_id;
            $fee_session_group_id  = $value->fee_session_group_id;
            $feeList               = $this->studentfeemaster_model->getDueFeeByFeeSessionGroupFeetype($fee_session_group_id, $fee_master_id, $fee_groups_feetype_id);
            $fees_array[]          = $feeList;
            $selected_amt += $feeList->amount;
        }
        if(!empty($fees_array))
        {
            foreach($fees_array as $fees)
            {
                $st_session_ids[] = $fees->student_session_id;
                $fee_deposits = json_decode(($fees->amount_detail));
                if(!empty($fee_deposits))
                {
                    foreach($fee_deposits as $fee)
                    {
                        $selected_amt = $selected_amt - ($fee->amount + $fee->amount_discount);
                    }
                }
            }
        }
        
        if($selected_amt == $data['total_balance_fees'])
        {$data['ot_enabled']=1;}
        else
        {$data['ot_enabled']=0;}
        //fine calculation start here
        $fine_result = $this->studentfee_model->get_all_fine_settings();
        // echo "<pre>";
        // print_r($fine_result);die();
        // $fine_result = array();
        // $fine_rec = $this->db->query("select * from fees_fine_all where session_id = '" . $this->setting_model->getCurrentSession() . "'");
        // if($fine_rec->num_rows() > 0)
        // {
        //     $fine_result = $fine_rec->result_array()[0];
        // }
        if(empty($fine_result) || $fine_selection == 0 )
        {
            $data['student_fine_status'] = 1;
            $data['student_fine_amt'] = 0;
        }
        else
        {
            
            $due_date = date('m-Y',strtotime($fine_result['due_date']));
            $data['student_fine_status'] = $this->studentfee_model->get_current_month_fine_collection($data['student_session_id'],date('d-m-Y'));
            $fine_amt = 0;
            if($selected_amt > 0 && $data['student_fine_status'] == 0)
            {
                $diff_month = $this->studentfee_model->getMonthsDifference($due_date,date('m-Y'));
                if($diff_month > 0)
                {
                    $fine_amt = $diff_month * $fine_result['fine_amount'];
                }
            }        
            $data['student_fine_amt'] = $fine_amt;
        }
        //fine calculation end here

        $data['feearray'] = $fees_array;
        $data['selected_amt'] = $selected_amt;
        if (!empty($fees_array)) {
            
            $data['discount_not_applied']   = $this->feediscount_model->getDiscountNotApplieddropdown($fees_array[0]->student_session_id);
        }
        $data['ot_disc']   = $this->feediscount_model->getOneTimeDiscount();
        $result           = array(
            'view' => $this->load->view('studentfee/getcollectfee', $data, true),
        );
        $this->output->set_output(json_encode($result));
    }
    public function addfee($id)
    {
        // error_reporting(E_ALL);
        // ini_set('display_errors',1);
        if (!$this->rbac->hasPrivilege('collect_fees', 'can_add')) {
            access_denied();
        }
        $data['userdata']             = $this->customlib->getUserData();
       
        $data['sch_setting']  = $this->sch_setting_detail;
        $data['title']        = 'Student Detail';
        $student              = $this->student_model->getByStudentSession($id);
        $data['student']      = $student;

        $student_sessionlist  = $this->student_model->get_studentsessionlist_current($student['id']);
    
        $data['prev_paid']    = $this->studentfee_model->getPaidFees_Main($id);
        $student_due_fee = [];
        $student_discount_fee = [];
        $student_session_id = [];
        foreach ($student_sessionlist as $key => $value) {
            $id =  $value['id'];
            $student_session_id[] = $id;
            $student_due_fee[]          = $this->studentfeemaster_model->getStudentFees($id);
            $student_discount_fee[]     = $this->feediscount_model->getStudentFeesDiscount($id);
        }
        
        $data['student_discount_fee'] = $student_discount_fee;
        $data['student_due_fee']      = $student_due_fee;
        $data['student_session_id']   = $student_session_id;

        $data['current_session']      = $this->setting_model->getCurrentSession();
        $category                     = $this->category_model->get();
        $data['categorylist']         = $category;
        $class_section                = $this->student_model->getClassSection($student["class_id"]);
        $data["class_section"]        = $class_section;
        $session                      = $this->setting_model->getCurrentSession();
        $studentlistbysection         = $this->student_model->getStudentClassSection($student["class_id"], $session);
        $data["studentlistbysection"] = $studentlistbysection;
        
        //echo "<pre>";print_r($data['userdata']);die();
        $data['class_teacher']        = $this->staff_model->get_class_teacher_data($id);
        $data['receipt_type']         = $this->studentfee_model->get_receipt_type($id);
        $data['main_fees']            = $this->studentfee_model->get_receipt_fees_main_session($id);

        //  print_r($data['receipt_type']);die();
        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/studentAddfee', $data);
        $this->load->view('layout/footer', $data);
    }

    public function previousfee($id)
    {
        if (!$this->rbac->hasPrivilege('collect_fees', 'can_add')) {
            access_denied();
        }
        $data['sch_setting']  = $this->sch_setting_detail;
        $data['title']        = 'Student Detail';
        $student              = $this->student_model->getByStudentSession($id);
        $data['student']      = $student;

        $student_sessionlist  = $this->student_model->get_studentsessionlist_previous($student['id']);
        $data['prev_paid']    = $this->studentfee_model->getPaidFees($student_sessionlist[0]['id']);
        
        $student_due_fee = [];
        $student_discount_fee = [];
        $student_session_id = [];
        foreach ($student_sessionlist as $key => $value) {
            $id =  $value['id'];
            $student_session_id[] = $id;
            $student_due_fee[]          = $this->studentfeemaster_model->getStudentFees($id);
            $student_discount_fee[]     = $this->feediscount_model->getStudentFeesDiscount_previous($id,$value['session_id']);
        }
        $data['student_discount_fee'] = $student_discount_fee;
        $data['student_due_fee']      = $student_due_fee;
        $data['student_session_id']   = $student_session_id;

        $data['current_session']      = $this->setting_model->getCurrentSession();
        $category                     = $this->category_model->get();
        $data['categorylist']         = $category;
        $class_section                = $this->student_model->getClassSection($student["class_id"]);
        $data["class_section"]        = $class_section;
        $session                      = $this->setting_model->getCurrentSession();
        $studentlistbysection         = $this->student_model->getStudentClassSection($student["class_id"], $session);
        $data["studentlistbysection"] = $studentlistbysection;
        $data['userdata']             = $this->customlib->getUserData();
        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/studentAddfee_previous', $data);
        $this->load->view('layout/footer', $data);
    }
    public function getcollectfee_previous()
    {
        $setting_result                  = $this->setting_model->get();
        $data['settinglist']             = $setting_result;
        $data['student_session_id']              = $this->input->post('student_session_id');
        $data['total_main_fees']              = $this->input->post('total_main_fees');
        $data['total_other_fees']              = $this->input->post('total_other_fees');
        $data['total_balance_fees']              = $this->input->post('total_balance_fees');
        $data['prev_paid']              = $this->input->post('prev_paid');
        $record                          = $this->input->post('data');
        $record_array                    = json_decode($record);
        $fees_array = array();
        $selected_amt = 0;
        foreach ($record_array as $key => $value) {
            $fee_groups_feetype_id = $value->fee_groups_feetype_id;
            $fee_master_id         = $value->fee_master_id;
            $fee_session_group_id  = $value->fee_session_group_id;
            $feeList               = $this->studentfeemaster_model->getDueFeeByFeeSessionGroupFeetype($fee_session_group_id, $fee_master_id, $fee_groups_feetype_id);
            $fees_array[]          = $feeList;
            $selected_amt += $feeList->amount;
        }
        $session_id = $fees_array[0]->session_id;
        $data['session_id'] = $session_id;
        if(!empty($fees_array))
        {
            foreach($fees_array as $fees)
            {
                $st_session_ids[] = $fees->student_session_id;
                $fee_deposits = json_decode(($fees->amount_detail));
                if(!empty($fee_deposits))
                {
                    foreach($fee_deposits as $fee)
                    {
                        $selected_amt = $selected_amt - ($fee->amount + $fee->amount_discount);
                    }
                }
            }
        }        
        if($selected_amt == $data['total_balance_fees'])
        {$data['ot_enabled']=1;}
        else
        {$data['ot_enabled']=0;}

        $data['feearray'] = $fees_array;
        $data['selected_amt'] = $selected_amt;
        if (!empty($fees_array)) {
            $data['discount_not_applied']   = $this->feediscount_model->getDiscountNotApplieddropdown_previous($fees_array[0]->student_session_id,$fees_array[0]->session_id);
            
        }
        $data['ot_disc']   = $this->feediscount_model->getOneTimeDiscount_previous($session_id);
        $result           = array(
            'view' => $this->load->view('studentfee/getcollectfee_previous', $data, true),
        );
        $this->output->set_output(json_encode($result));
    }

    public function deleteTransportFee()
    {
        $id = $this->input->post('feeid');
        $this->studenttransportfee_model->remove($id);
        $array = array('status' => 'success', 'result' => 'success');
        echo json_encode($array);
    }

    public function delete($id)
    {
        $data['title'] = 'studentfee List';
        $this->studentfee_model->remove($id);
        redirect('studentfee/index');
    }

    public function create()
    {
        if (!$this->rbac->hasPrivilege('collect_fees', 'can_view')) {
            access_denied();
        }
        $data['title'] = 'Add studentfee';
        $this->form_validation->set_rules('category', $this->lang->line('category'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('studentfee/studentfeeCreate', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'category' => $this->input->post('category'),
            );
            $this->studentfee_model->add($data);
            $this->session->set_flashdata('msg', '<div studentfee="alert alert-success text-center">' . $this->lang->line('success_message') . '</div>');
            redirect('studentfee/index');
        }
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('collect_fees', 'can_edit')) {
            access_denied();
        }
        $data['title']      = 'Edit studentfees';
        $data['id']         = $id;
        $studentfee         = $this->studentfee_model->get($id);
        $data['studentfee'] = $studentfee;
        $this->form_validation->set_rules('category', $this->lang->line('category'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('studentfee/studentfeeEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id'       => $id,
                'category' => $this->input->post('category'),
            );
            $this->studentfee_model->add($data);
            $this->session->set_flashdata('msg', '<div studentfee="alert alert-success text-center">' . $this->lang->line('update_message') . '</div>');
            redirect('studentfee/index');
        }
    }
    public function addstudentfee()
    {
        $this->form_validation->set_rules('student_fees_master_id', $this->lang->line('fee_master'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('fee_groups_feetype_id', $this->lang->line('student'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'required|trim|xss_clean|numeric|callback_check_deposit');
        $this->form_validation->set_rules('amount_discount', $this->lang->line('discount'), 'required|numeric|trim|xss_clean');
        $this->form_validation->set_rules('amount_fine', $this->lang->line('fine'), 'required|trim|numeric|xss_clean');
        $this->form_validation->set_rules('payment_mode', $this->lang->line('payment_mode'), 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'amount'                 => form_error('amount'),
                'student_fees_master_id' => form_error('student_fees_master_id'),
                'fee_groups_feetype_id'  => form_error('fee_groups_feetype_id'),
                'amount_discount'        => form_error('amount_discount'),
                'amount_fine'            => form_error('amount_fine'),
                'payment_mode'           => form_error('payment_mode'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $staff_record = $this->staff_model->get($this->customlib->getStaffID());

            $this->db->where('id', $this->input->post('student_fees_master_id'));
            $student_fees_master = $this->db->get('student_fees_master')->row_array();


            $collected_by             = $this->customlib->getAdminSessionUserName() . "(" . $staff_record['employee_id'] . ")";
            $student_fees_discount_id = $this->input->post('student_fees_discount_id');
            $json_array               = array(
                'amount'          => $this->input->post('amount'),
                'date'            => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                'amount_discount' => $this->input->post('amount_discount'),
                'amount_fine'     => $this->input->post('amount_fine'),
                'description'     => $this->input->post('description'),
                'collected_by'    => $collected_by,
                'payment_mode'    => $this->input->post('payment_mode'),
                'received_by'     => $staff_record['id'],
                'student_session_id'     => $student_fees_master['student_session_id'],
            );
            $data = array(
                'student_fees_master_id' => $this->input->post('student_fees_master_id'),
                'fee_groups_feetype_id'  => $this->input->post('fee_groups_feetype_id'),
                'amount_detail'          => $json_array,
            );

            $action             = $this->input->post('action');
            $send_to            = $this->input->post('guardian_phone');
            $email              = $this->input->post('guardian_email');
            $parent_app_key     = $this->input->post('parent_app_key');
            $student_session_id = $this->input->post('student_session_id');
            $inserted_id        = $this->studentfeemaster_model->fee_deposit($data, $send_to, $student_fees_discount_id);
            $mailsms_array      = $this->feegrouptype_model->getFeeGroupByID($this->input->post('fee_groups_feetype_id'));
            $print_record       = array();

            $receipt_data             = json_decode($inserted_id);
            $invoice_id = $receipt_data->invoice_id;
            $data['invo_no'] = $sub_invoice_id = $receipt_data->sub_invoice_id;
            $data['payment_id'] = $sub_invoice_id;
            $data['student_session_id'] = $student_session_id;
            $fee_groups_feetype_id = $this->input->post('fee_groups_feetype_id');
            $fee_master_id         = $this->input->post('student_fees_master_id');

            $data['paidamt']        = $this->input->post('amount');
            $setting_result         = $this->setting_model->get();
            $data['settinglist']    = $setting_result[0];

            $student_session_id     = $student_fees_master['student_session_id'];

            $student                = $this->studentsession_model->searchStudentsBySession($student_session_id);
            $fee_record             = $this->studentfeemaster_model->getFeeByInvoicereturn($invoice_id, $sub_invoice_id, $student_session_id);

            $data['fees_array']             = $this->studentfeemaster_model->getStudentFees($student_session_id);
            $data['previousfees']             = $this->studentfeemaster_model->getPreviousStudentFees($student_session_id);

            $data['student']        = $student;
            $data['sub_invoice_id'] = $sub_invoice_id;
            $data['feeList']        = $fee_record;
            $data['sch_setting']    = $this->sch_setting_detail;
            $remain_amount_object   = $this->getStuFeetypeBalance($fee_groups_feetype_id, $fee_master_id);
            $data['remain_amount']          = json_decode($remain_amount_object)->balance;
            // $fee_record             = $this->studentfeemaster_model->getFeeByInvoice($receipt_data->invoice_id, $receipt_data->sub_invoice_id);

            // $data['student']        = $student;
            // $data['sub_invoice_id'] = $receipt_data->sub_invoice_id;
            // $data['feeList']        = $fee_record;
            $data['total_amt']              = $this->input->post('total_amt');
            if ($this->input->post('total_paid') == 0) {
                $data['total_paid']             = $this->input->post('total_paid');
            } else {

                $data['total_paid']             = $this->input->post('total_paid') + $this->input->post('amount');
            }
            $data['total_balance']          = $this->input->post('total_balance') - $this->input->post('amount_discount') - $this->input->post('amount');
            $data['discount_amt']           = $this->input->post('amount_discount');
            $data['session']                = $this->setting_model->getCurrentSession();
            $data['billing_session']        = $this->session_model->get($this->setting_model->getCurrentSession());             
            if ($action == "print") {
                $print_record           = $this->load->view('print/printFeesByNameNew', $data, true);
                $body           = $this->load->view('print/mail_invoice', $data, true);
            } else {
                $body           = $this->load->view('print/mail_invoice', $data, true);
            }
            $mailsms_array->invoice        = $inserted_id;
            $mailsms_array->contact_no     = $send_to;
            $mailsms_array->email          = $email;
            $mailsms_array->parent_app_key = $parent_app_key;

            // $this->mailsmsconf->mailsms('fee_submission', $mailsms_array);
            $gross_amt = $data['total_paid'] + $this->input->post('amount_discount') - $this->input->post('amount_fine');

            $arrayUpdate = array(
                'id'                        => $sub_invoice_id,
                'receipt_date'              => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                'gross_amount'              => $gross_amt,
                'discount'                  => $this->input->post('amount_discount'),
                'fine'                      => $this->input->post('amount_fine'),
                'net_amt'                   => $this->input->post('amount'),
                'student_session_id'        => $student_fees_master['student_session_id'],
                'session_id'                => $this->setting_model->getCurrentSession(),
                'created_by'                => $collected_by,
                'payment_mode'              => $this->input->post('payment_mode'),
            );

            $this->studentfeemaster_model->update_receipt($arrayUpdate,$this->current_ay_session);

            if (!empty($email)) {
                //$this->send_mail($email, 'Fee Submission', $body);
            }


            $array = array('status' => 'success', 'error' => '', 'print' => $print_record);
            echo json_encode($array);
        }
    }
    public function testmail()
    {
        $this->send_mail("manojthannimattam@gmail.com","My Subject","Bidy Text");
        echo "finished";
    }
    public function send_mail($toemail, $subject, $body, $cc = "", $FILES = array())
    {
        // $toemail = 'supriyaprabhu123@gmail.com';
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
    public function printFeesByName()
    {
        $data                           = array('payment' => "0");
        $record                         = $this->input->post('data');
        $invoice_id                     = $this->input->post('main_invoice');
        $data['invo_no'] = $sub_invoice_id = $this->input->post('sub_invoice');
        $data['student_session_id'] = $student_session_id             = $this->input->post('student_session_id');
        $fee_master_id                  = $this->input->post('fee_master_id');
        $fee_groups_feetype_id          = $this->input->post('fee_groups_feetype_id');
        $data['paidamt']                = $this->input->post('paidamt');
        $data['total_amt']              = $this->input->post('total_amt');
        $data['total_paid']             = $this->input->post('total_paid');
        $data['total_balance']          = $this->input->post('total_balance');
        $data['discount_amt']           = $this->input->post('discount_amt');
        $data['payment_id']             = $this->input->post('payment_id');
        $setting_result                 = $this->setting_model->get();
        $data['settinglist']            = $setting_result[0];
        $student                        = $this->studentsession_model->searchStudentsBySession($student_session_id);
        $receipt                        = $this->studentfee_model->getReceipt($sub_invoice_id);

        $data['receipt']                = $receipt;
        if(isset($receipt->chequeid))
        {
            if($receipt->chequeid>0)
            {$data['cheque'] = $this->feemaster_model->getCheque_only($receipt->chequeid);}
            else
            {$data['cheque'] = array();}
        }
        else
        {$data['cheque'] = array();}
        if(!empty($receipt->session_id))
        {$data['receipt_session']        = $this->session_model->get($receipt->session_id);}
        else
        {$data['receipt_session']        = $this->session_model->get($this->setting_model->getCurrentSession()); }

        $fee_record                     = $this->studentfeemaster_model->getFeeByInvoicereturn($invoice_id, $sub_invoice_id, $student_session_id);
        $data['fees_array']             = $this->studentfeemaster_model->getStudentFees($student_session_id);
        $data['previousfees']           = $this->studentfeemaster_model->getPreviousStudentFees($student_session_id);
        $data['amount_details']         = $this->studentfeemaster_model->getamountdetails($sub_invoice_id);
        $data['student']                = $student;
        $data['sub_invoice_id']         = $sub_invoice_id;
        $data['feeList']                = $fee_record;
        $data['sch_setting']            = $this->sch_setting_detail;
        $remain_amount_object           = $this->getStuFeetypeBalance($fee_groups_feetype_id, $fee_master_id);
        $data['remain_amount']          = json_decode($remain_amount_object)->balance;
        $data['session']                = $this->setting_model->getCurrentSession();
        $data['billing_session']        = $this->session_model->get($this->setting_model->getCurrentSession()); 
        $this->load->view('print/printFeesByNameNew', $data);
    }
    public function printFeesByName_previous()
    {
        $data                           = array('payment' => "0");
        $record                         = $this->input->post('data');
        $invoice_id                     = $this->input->post('main_invoice');
        $data['invo_no'] = $sub_invoice_id = $this->input->post('sub_invoice');
        $data['student_session_id']     = $student_session_id             = $this->input->post('student_session_id');
        $fee_master_id                  = $this->input->post('fee_master_id');
        $fee_groups_feetype_id          = $this->input->post('fee_groups_feetype_id');
        $data['paidamt']                = $this->input->post('paidamt');
        $data['total_amt']              = $this->input->post('total_amt');
        $data['total_paid']             = $this->input->post('total_paid');
        $data['total_balance']          = $this->input->post('total_balance');
        $data['discount_amt']           = $this->input->post('discount_amt');
        $data['payment_id']             = $this->input->post('payment_id');
        $setting_result                 = $this->setting_model->get();
        $data['settinglist']            = $setting_result[0];
        $student                        = $this->studentsession_model->searchStudentsBySession($student_session_id);
        $receipt                        = $this->studentfee_model->getReceipt($sub_invoice_id);
        $data['receipt_session_id']     = $receipt->session_id;
        $data['receipt_session']        = $this->session_model->get($receipt->session_id);
        if($receipt->chequeid>0)
        {$data['cheque'] = $this->feemaster_model->getCheque_only($receipt->chequeid);}
        else
        {$data['cheque'] = array();}           
        $data['receipt']                = $receipt;
        $data['receipt_session']        = $this->session_model->get($receipt->session_id);
        $fee_record                     = $this->studentfeemaster_model->getFeeByInvoicereturn($invoice_id, $sub_invoice_id, $student_session_id);
        $data['fees_array']             = $this->studentfeemaster_model->getStudentFees($student_session_id);
        $data['previousfees']           = $this->studentfeemaster_model->getPreviousStudentFees($student_session_id);
        $data['amount_details']         = $this->studentfeemaster_model->getamountdetails($sub_invoice_id);
        $data['student']                = $student;
        $data['sub_invoice_id']         = $sub_invoice_id;
        $data['feeList']                = $fee_record;
        $data['sch_setting']            = $this->sch_setting_detail;
        $remain_amount_object           = $this->getStuFeetypeBalance($fee_groups_feetype_id, $fee_master_id);
        $data['remain_amount']          = json_decode($remain_amount_object)->balance;
        $data['session']                = $this->setting_model->getCurrentSession();
        $data['st_session']             = $data['receipt_session']['session'];
        $data['billing_session']        = $this->session_model->get($this->setting_model->getCurrentSession());        
        $this->load->view('print/printFeesByNameNew_previous', $data);
    }
    public function printFeesByGroup()
    {
        $fee_groups_feetype_id = $this->input->post('fee_groups_feetype_id');
        $fee_master_id         = $this->input->post('fee_master_id');
        $fee_session_group_id  = $this->input->post('fee_session_group_id');

        $setting_result        = $this->setting_model->get();
        $data['settinglist']   = $setting_result[0];
        $data['feeList']       = $this->studentfeemaster_model->getDueFeeByFeeSessionGroupFeetype($fee_session_group_id, $fee_master_id, $fee_groups_feetype_id);
        $data['sch_setting']   = $this->sch_setting_detail;

        $student_session_id = $data['feeList']->student_session_id;
        $student                = $this->studentsession_model->searchStudentsBySession($student_session_id);
        $data['student']        = $student;

        $this->load->view('print/printFeesByGroupNew', $data);
    }

    public function printFeesByGroupArray()
    {
        $setting_result        = $this->setting_model->get();
        $data['settinglist']   = $setting_result[0];
        $data['sch_setting'] = $this->sch_setting_detail;
        $record              = $this->input->post('data');
        $record_array        = json_decode($record);
        $fees_array          = array();
        foreach ($record_array as $key => $value) {
            $fee_groups_feetype_id = $value->fee_groups_feetype_id;
            $fee_master_id         = $value->fee_master_id;
            $fee_session_group_id  = $value->fee_session_group_id;
            $feeList               = $this->studentfeemaster_model->getDueFeeByFeeSessionGroupFeetype($fee_session_group_id, $fee_master_id, $fee_groups_feetype_id);
            $fees_array[]          = $feeList;
        }
        $data['feearray'] = $fees_array;
        $this->load->view('print/printFeesByGroupArrayNew', $data);
        // $this->load->view('print/printFeesByGroupArray', $data);
    }

    public function searchpayment()
    {
        if (!$this->rbac->hasPrivilege('search_fees_payment', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'studentfee/searchpayment');
        $data['title'] = 'Edit studentfees';

        $this->form_validation->set_rules('paymentid', $this->lang->line('payment_id'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
        } else {
            $paymentid = $this->input->post('paymentid');
            $invoice   = explode("/", $paymentid);

            if (array_key_exists(0, $invoice) && array_key_exists(1, $invoice)) {
                $invoice_id             = $invoice[0];
                $sub_invoice_id         = $invoice[1];
                $feeList                = $this->studentfeemaster_model->getFeeByInvoice($invoice_id, $sub_invoice_id);
                $data['feeList']        = $feeList;
                $data['sub_invoice_id'] = $sub_invoice_id;
            } else {
                $data['feeList'] = array();
            }
        }
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/searchpayment', $data);
        $this->load->view('layout/footer', $data);
    }

    public function addfeegroup()
    {
        $this->form_validation->set_rules('fee_session_groups', $this->lang->line('fee_group'), 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'fee_session_groups' => form_error('fee_session_groups'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $student_session_id     = $this->input->post('student_session_id');
            $fee_session_groups     = $this->input->post('fee_session_groups');
            $student_sesssion_array = isset($student_session_id) ? $student_session_id : array();
            $student_ids            = $this->input->post('student_ids');
            $delete_student         = array_diff($student_ids, $student_sesssion_array);
            $current_session        = $this->setting_model->getCurrentSession();
            $preserve_record = array();
            if (!empty($student_sesssion_array)) {
                foreach ($student_sesssion_array as $key => $value) {
                    $insert_array = array(
                        'student_session_id'   => $value,
                        'fee_session_group_id' => $fee_session_groups,
                        'session_id' => $current_session,
                    );
                    $inserted_id = $this->studentfeemaster_model->add($insert_array);

                    $preserve_record[] = $inserted_id;
                }
            }
            if (!empty($delete_student)) {
                $this->studentfeemaster_model->delete($fee_session_groups, $delete_student);
            }

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }
    
    public function addfeestudentgroup()
    {
        
        $student_session_id     = $this->input->post('student_session_id');
        $fee_session_groups     = $this->input->post('fee_session_groups');
        $student_sesssion_array = isset($student_session_id) ? $student_session_id : array();
        $student_ids            = $this->input->post('student_ids');
        $delete_student         = array_diff($student_ids, $student_sesssion_array);
        $current_session        = $this->setting_model->getCurrentSession();

        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        // $feelist = $this->feegrouptype_model->getclassfess($class_id)->result_array();
        $feelist = $this->input->post('fees_group_id');
        $preserve_record = array();

        if (!empty($feelist)) {
            foreach ($feelist as  $fee_group_id) {
                if (!empty($student_sesssion_array)) {
                    foreach ($student_sesssion_array as $key => $value) {
                        $insert_array = array(
                            'student_session_id'   => $value,
                            'fee_session_group_id' => $fee_group_id,
                            'session_id' => $current_session,
                        );
                        
                        $inserted_id = $this->studentfeemaster_model->add($insert_array);
                        // $preserve_record[] = $inserted_id;
                    }
                }
                if (!empty($delete_student)) {
                    $this->studentfeemaster_model->delete($fee_group_id, $delete_student);
                }
            }

        }
        $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        echo json_encode($array);
    }

    public function geBalanceFee()
    {
        $this->form_validation->set_rules('fee_groups_feetype_id', $this->lang->line('fee_groups_feetype_id'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('student_fees_master_id', 'student_fees_master_id', 'required|trim|xss_clean');
        $this->form_validation->set_rules('student_session_id', 'student_session_id', 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'fee_groups_feetype_id'  => form_error('fee_groups_feetype_id'),
                'student_fees_master_id' => form_error('student_fees_master_id'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $data                   = array();
            $student_session_id     = $this->input->post('student_session_id');
            $fee_groups_feetype_id  = $this->input->post('fee_groups_feetype_id');
            $student_fees_master_id = $this->input->post('student_fees_master_id');
            $remain_amount_object   = $this->getStuFeetypeBalance($fee_groups_feetype_id, $student_fees_master_id);
            $discount_not_applied   = $this->feediscount_model->getDiscountNotApplieddropdown($student_session_id);
            $remain_amount          = json_decode($remain_amount_object)->balance;
            $remain_amount_fine     = json_decode($remain_amount_object)->fine_amount;

            $array = array('status' => 'success', 'error' => '', 'balance' => $remain_amount, 'discount_not_applied' => $discount_not_applied, 'remain_amount_fine' => $remain_amount_fine);
            echo json_encode($array);
        }
    }

    public function getStuFeetypeBalance($fee_groups_feetype_id, $student_fees_master_id)
    {
        $data                           = array();
        $data['fee_groups_feetype_id']  = $fee_groups_feetype_id;
        $data['student_fees_master_id'] = $student_fees_master_id;
        $result                         = $this->studentfeemaster_model->studentDeposit($data);
        $amount_balance                 = 0;
        $amount                         = 0;
        $amount_fine                    = 0;
        $amount_discount                = 0;
        $fine_amount                    = 0;
        $fee_fine_amount                = 0;
        $due_amt                        = $result->amount;
        if (strtotime($result->due_date) < strtotime(date('Y-m-d'))) {
            $fee_fine_amount = $result->fine_amount;
        }

        if ($result->is_system) {
            $due_amt = $result->student_fees_master_amount;
        }

        $amount_detail = json_decode($result->amount_detail);
        if (is_object($amount_detail)) {

            foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                $amount          = $amount + $amount_detail_value->amount;
                $amount_discount = $amount_discount + $amount_detail_value->amount_discount;
                $amount_fine     = $amount_fine + $amount_detail_value->amount_fine;
            }
        }

        $amount_balance = $due_amt - ($amount + $amount_discount);
        $fine_amount    = abs($amount_fine - $fee_fine_amount);
        $array          = array('status' => 'success', 'error' => '', 'balance' => $amount_balance, 'fine_amount' => $fine_amount);
        return json_encode($array);
    }

    public function check_deposit($amount)
    {
        if (is_numeric($this->input->post('amount')) && is_numeric($this->input->post('amount_discount'))) {
            if ($this->input->post('amount') != "" && $this->input->post('amount_discount') != "") {
                if ($this->input->post('amount') < 0) {
                    $this->form_validation->set_message('check_deposit', $this->lang->line('deposit_amount_can_not_be_less_than_zero'));
                    return false;
                } else {
                    $student_fees_master_id = $this->input->post('student_fees_master_id');
                    $fee_groups_feetype_id  = $this->input->post('fee_groups_feetype_id');
                    $deposit_amount         = $this->input->post('amount') + $this->input->post('amount_discount');
                    $remain_amount          = $this->getStuFeetypeBalance($fee_groups_feetype_id, $student_fees_master_id);
                    $remain_amount          = json_decode($remain_amount)->balance;
                    if ($remain_amount < $deposit_amount) {
                        $this->form_validation->set_message('check_deposit', $this->lang->line('deposit_amount_can_not_be_greater_than_remaining'));
                        return false;
                    } else {
                        return true;
                    }
                }
                return true;
            }
        } elseif (!is_numeric($this->input->post('amount'))) {

            $this->form_validation->set_message('check_deposit', $this->lang->line('amount') . " " . $this->lang->line('field_must_contain_only_numbers'));

            return false;
        } elseif (!is_numeric($this->input->post('amount_discount'))) {

            return true;
        }

        return true;
    }

    public function getNotAppliedDiscount($student_session_id)
    {
        return $this->feediscount_model->getDiscountNotApplied($student_session_id);
    }
    public function test_user()
    {
        $staff_record = $this->session->userdata('admin');
        $staff = $this->staff_model->get($staff_record['id']);
        print_r($staff['user_type']);die();
    }
    public function addfeegrp()
    {
        // ini_set ('display_errors', 1); ini_set ('display_startup_errors', 1); error_reporting (E_ALL);        
        $staff_record = $this->session->userdata('admin');
        $staff = $this->staff_model->get($staff_record['id']);
        $rolename="";
        if(!empty($staff))
        {
            $rolename = strtoupper($staff['user_type']);
        }
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('row_counter[]', $this->lang->line('fees_list'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('collected_date', $this->lang->line('date'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('valid_amount', $this->lang->line('amount'), 'required|callback_greater_than_or[' . $this->input->post('valid_amount') . ']|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'row_counter'    => form_error('row_counter'),
                'collected_date' => form_error('collected_date'),
                'valid_amount' => form_error('valid_amount'),
            );
            $array = array('status' => 0, 'error' => $data);
            echo json_encode($array);
        } else {
            $collection_dt = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('collected_date')));
            $res=$this->studentfeemaster_model->check_receipt_date($collection_dt);

            if($rolename=="ACCOUNTANT")
            {
                $res=0;
            }
            if($res > 0)            
            {
                $data = array(
                    'row_counter'    => form_error('row_counter'),
                    'collected_date' => form_error('collected_date'),
                    'valid_amount' => form_error('valid_amount'),
                );
                $array = array('status' => 0, 'error' => $data,'msg' => 'Receipt exist on forward date.');
                echo json_encode($array);
            }
            else
            {
                $src = $this->input->post('source');
                if(!empty($src))
                {$source='counter-others';}
                else
                {$source='counter-main';}
                if($this->input->post('payment_mode_fee')=="gateway")
                {
                    $post_array = $this->input->post();
                    $post_array_json = json_encode($post_array);
                    $array_data = array(
                        'session_id' => $this->current_ay_session,
                        'student_session_id' => $this->input->post('student_session_id'),
                        'amount' => $this->input->post('paid_amount'),
                        'trn_data' => $post_array_json,
                        'session_data' => json_encode($this->session->userdata()),
                        'trn_status' => 'pending',
                        "source" => $source,
                        'note' => $this->input->post('fee_gupcollected_note'),
                    );
                    $result=$this->studentfee_model->add_online_cart($array_data);
                    if(!empty($result))
                    {
                        $array = array('status' => 1, 'error' => '', 'mode' => 'gateway', 'hash_code' => $result['hash_code'] );
                        echo json_encode($array);
                    }
                }
                elseif($this->input->post('payment_mode_fee')=="Cheque")
                {
                    $userdata           = $this->customlib->getUserData();
                    $st_session_id = $this->input->post('student_session_id');
                    $class_details = $this->db->query("select class_id,section_id from student_session where id = '$st_session_id'")->row_array();
                    $insert_array = array(
                        'class_id' => $class_details['class_id'],
                        'section_id' => $class_details['section_id'],
                        'student_session_id' => $this->input->post('student_session_id'),
                        'session_id' => $this->current_ay_session,
                        'chq_type' => "student",
                        'chq_no' => $this->input->post('cheque_no'),
                        'chq_date' => date('Y-m-d', strtotime($this->input->post('cheque_date'))),
                        'chq_bank' => $this->input->post('cheque_bank'),
                        'chq_branch' => '',
                        'chq_amt' => $this->input->post('paid_amount'),
                        'chq_status' => "collected",
                        'contact_no' => '',
                        'remarks' => $this->input->post('fee_gupcollected_note'),
                        'created_by' =>  $userdata['name'],
                    );   
                    $record_id = $this->feemaster_model->addCheque($insert_array);
                    $post_array = $this->input->post();
                    $post_array_json = json_encode($post_array);
                    $chqnote = "Chq.No.-".$this->input->post('cheque_no')."- Dt: ".$this->input->post('cheque_date')." Bank : ".$this->input->post('cheque_bank');
                    $array_data = array(
                        'session_id' => $this->current_ay_session,
                        'student_session_id' => $this->input->post('student_session_id'),
                        'amount' => $this->input->post('paid_amount'),
                        'trn_data' => $post_array_json,
                        'session_data' => json_encode($this->session->userdata()),
                        'trn_status' => 'pending',
                        "source" => 'counter-main',
                        "payment_mode" => 'cheque',
                        "chequeid" => $record_id,
                        'note' => $chqnote . " " . $this->input->post('fee_gupcollected_note'),
                    );
                    $result=$this->studentfee_model->add_online_cart($array_data);
                    $student_session_id=$this->input->post('student_session_id');
                    $data['student']                = $this->studentsession_model->searchStudentsBySession($student_session_id);                
                    $st_session_id = $data['student']['session_id'];
                    $data['student_session']        = $this->session_model->get($st_session_id);
                    $data['chq'] = $insert_array;
                    $setting_result         = $this->setting_model->get();
                    $data['settinglist']    = $setting_result[0];
                    $data['sch_setting']    = $this->sch_setting_detail;                
                    $data['session']        = $this->setting_model->getCurrentSession();    
                    $data['created_by']     =$this->customlib->getAdminSessionUserName();
                    $data['created_at']     = date('d-m-Y h:m:s');
                    $data['created_date']     = date('d-m-Y');
                    $data['source']         = "direct";
                    
                    $print_record  = $this->load->view('print/cheque_receipt', $data, true);                
                    if(!empty($result))
                    {
                        $array = array('status' => 1, 'error' => '', 'mode' => 'cheque', 'print' => $print_record );
                        echo json_encode($array);
                    }
                }    
                else
                {                
                    $collected_array = array();
                    $student_fees_discount_id = array();
                    $collected_by       = " Collected By: " . $this->customlib->getAdminSessionUserName();
                    $send_to            = $this->input->post('guardian_phone');
                    $email              = $this->input->post('guardian_email');
                    $parent_app_key     = $this->input->post('parent_app_key');
                    $paid_amount        = $this->input->post('paid_amount');
                    $paid_fine_amount   = $this->input->post('tot_fine_amount');
                    $paid_net_fine      = $this->input->post('net_fine');                    
                    $prev_paid          = $this->input->post('previous_paid');
                    $paid_fine_amount   = $paid_fine_amount + $paid_net_fine;
                    $data['student_session_id'] = $student_session_id = $this->input->post('student_session_id');
                    //$student_fees_discount_id = $this->input->post('discount_amt');
                    $total_row = $this->input->post('row_counter');
                    $total_amount = 0;
                    $total_discount = 0;
                    $total_fine = 0;
                    $net_total = 0;
                    foreach ($total_row as $total_row_key => $total_row_value) {
                        $this->input->post('student_fees_master_id_' . $total_row_value);
                        $this->input->post('fee_groups_feetype_id_' . $total_row_value);
                        $discount_amt = $this->input->post('amount_discount_' . $total_row_value);
                        if ($discount_amt > 0) {
                            $amt = $this->input->post('fee_amount_' . $total_row_value) - $discount_amt;
                            //$amt = $this->input->post('fee_amount_' . $total_row_value);
                        } else {
                            $amt = $this->input->post('fee_amount_' . $total_row_value);
                        }
                        $this->db->where('id', $this->input->post('student_fees_master_id_' . $total_row_value));
                        $student_fees_master = $this->db->get('student_fees_master')->row_array();

                        $json_array = array(
                            'amount'          => $amt,
                            'date'            => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('collected_date'))),
                            'description'     => $this->input->post('fee_gupcollected_note') . $collected_by,
                            'amount_discount' => $this->input->post('amount_discount_' . $total_row_value),
                            'amount_fine'     => $this->input->post('fee_groups_feetype_fine_amount_' . $total_row_value),
                            'payment_mode'    => $this->input->post('payment_mode_fee'),
                            'received_by'     => $staff_record['id'],
                            'student_session_id'     => $student_fees_master['student_session_id'],
                            'discount_id'     => $this->input->post('disc_code_' . $total_row_value),
                            'session_id'     => $this->current_ay_session,
                        );
                        if(!empty($this->input->post('disc_code_' . $total_row_value)))
                        {
                            $current_session_id = $this->setting_model->getCurrentSession();
                            $this->db->where('student_session_id',$student_fees_master['student_session_id']);
                            $this->db->where('fees_discount_id',$this->input->post('disc_code_' . $total_row_value));
                            $this->db->where('session_id',$current_session_id);
                            $disc_rows = $this->db->get('student_fees_discounts')->row_array();
                            if(empty($disc_rows))
                            {
                                $insert_array = array(
                                    'student_session_id' => $student_fees_master['student_session_id'],
                                    'fees_discount_id' => $this->input->post('disc_code_' . $total_row_value),
                                    'is_active' => "Yes",
                                    'session_id' => $current_session_id,
                                );
                                $student_fees_discount_id[] = $this->feediscount_model->allotdiscount($insert_array);
                            }
                            else
                            {
                                $student_fees_discount_id[] = $disc_rows['id'];
                            }
                        }
                        $collected_array[] = array(
                            'student_fees_master_id' => $this->input->post('student_fees_master_id_' . $total_row_value),
                            'fee_groups_feetype_id'  => $this->input->post('fee_groups_feetype_id_' . $total_row_value),
                            'amount_detail'          => $json_array,
                        );
                        $total_amount += $amt;
                        $total_discount += $this->input->post('amount_discount_' . $total_row_value);
                        $total_fine += $this->input->post('fee_groups_feetype_fine_amount_' . $total_row_value);
                    }

                    $deposited_fees = $this->studentfeemaster_model->fee_deposit_collections($collected_array, $student_fees_discount_id);
                    $fees_record    = json_decode($deposited_fees);

                    foreach ($fees_record as  $feeRow) {

                        $print = $this->input->post('print');
                        $print_record       = array();


                        $invoice_id = $feeRow->invoice_id;
                        $data['invo_no'] = $sub_invoice_id = $feeRow->sub_invoice_id;
                        $data['payment_id'] = $sub_invoice_id;
                        $fee_groups_feetype_id = $this->input->post('fee_groups_feetype_id_' . $total_row_value);
                        $fee_master_id         = $this->input->post('student_fees_master_id_' . $total_row_value);

                        $setting_result        = $this->setting_model->get();
                        $data['settinglist']   = $setting_result[0];
                        $data['sch_setting'] = $this->sch_setting_detail;
                        foreach ($total_row as $total_row_key => $total_row_value) {
                            $this->db->where('id', $this->input->post('student_fees_master_id_' . $total_row_value));
                            $student_fees_master = $this->db->get('student_fees_master')->row_array();
                            $student_session_id = $student_fees_master['student_session_id'];
                            $student                = $this->studentsession_model->searchStudentsBySession($student_session_id);
                            $fee_record             = $this->studentfeemaster_model->getFeeByInvoicereturn($invoice_id, $sub_invoice_id, $student_session_id);

                            $data['fees_array']             = $this->studentfeemaster_model->getStudentFees($student_session_id);
                            $data['previousfees']             = $this->studentfeemaster_model->getPreviousStudentFees($student_session_id);
                        }
                        $data['student']        = $student;
                        $data['sub_invoice_id'] = $sub_invoice_id;
                        $data['feeList']        = $fee_record;
                        $data['sch_setting']    = $this->sch_setting_detail;
                        $remain_amount_object   = $this->getStuFeetypeBalance($fee_groups_feetype_id, $fee_master_id);
                        $data['remain_amount']          = json_decode($remain_amount_object)->balance;

                        $data['total_paid']             = $this->input->post('total_paid') +  $total_amount;
                        $data['total_balance']          = $this->input->post('total_balance') - $total_amount - $total_discount;
                        $data['discount_amt']           = $total_discount;
                        $data['session']                = $this->setting_model->getCurrentSession();
                        $total_balance          = $this->input->post('total_balance') - $total_amount - $total_discount;
                        $gross_amt = $paid_amount + $total_discount - $paid_fine_amount;
                        $net_total = $paid_amount;
                        $arrayUpdate = array(
                            'id'                        => $sub_invoice_id,
                            'receipt_date'              => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('collected_date'))),
                            'gross_amount'              => $gross_amt,
                            'discount'                  => $total_discount,
                            'fine'                      => $paid_fine_amount,
                            'net_amt'                   => $net_total,
                            'total_balance'             => $total_balance,
                            'prev_balance'              => $prev_paid,                            
                            'student_session_id'        => $student_session_id,
                            'session_id'                => $student['session_id'],
                            'created_by'                => $this->customlib->getAdminSessionUserName(),
                            'created_id'                => $staff_record['id'],
                            'note'                      => $this->input->post('fee_gupcollected_note'),
                            'payment_mode'              => $this->input->post('payment_mode_fee'),
                        );
                        $this->studentfeemaster_model->update_receipt($arrayUpdate,$this->current_ay_session);
                        $data['created_by'] = $this->customlib->getAdminSessionUserName();
                        $st_name = strtoupper($data['student']['firstname']." ".$data['student']['middlename']." ".$data['student']['lastname']);
                        $class_div = $data['student']['code']."-".$data['student']['section'];
                        $mobno = $this->staff_model->get_class_teacher($student_session_id);
                        $email = $student['email'];
                        $data['total_balance'] = $this->studentfeemaster_model->get_student_balance_up_recid($student_session_id,$sub_invoice_id);                         
                        $communication = 1;
                        if($mobno)
                        {
                            $data_msg1 = array(
                                "mobno" => $mobno,
                                "name" => $st_name,
                                "class" => $class_div,
                                "amount" => $net_total,
                                "rec_no" => $sub_invoice_id,
                                "rec_date" => date('d-m-Y', $this->customlib->datetostrtotime($this->input->post('collected_date')))
                            );
                            if($communication==1) {$this->wati_model->send_receipt_to_class_teacher($data_msg1);}
                        }
                        if($student['mobileno']!='')
                        {
                            $data_msg = array(
                                "mobno" => $student['mobileno'],
                                "name" => $st_name,
                                "class" => $class_div,
                                "amount" => $net_total,
                                "rec_no" => $sub_invoice_id,
                                "rec_date" => date('d-m-Y', $this->customlib->datetostrtotime($this->input->post('collected_date')))
                            );
                            //$this->wati_model->send_receipt_to_parent($data_msg);              
                        }
                        $data['note']=$this->input->post('fee_gupcollected_note');
                        $receipt                        = $this->studentfee_model->getReceipt_ay($sub_invoice_id);
                        $data['receipt']                = $receipt;   
                        $data['receipt_session']        = $this->session_model->get($student['session_id']);
                        if($receipt->chequeid>0)
                        {$data['cheque'] = $this->feemaster_model->getCheque_only($receipt->chequeid);}
                        else
                        {$data['cheque'] = array();}   
                        $data['billing_session']        = $this->session_model->get($this->current_ay_session); 
                        if ($print == 1) {
                            $body           = $this->load->view('print/mail_invoice_new', $data, true);
                            // $print_record  = $this->load->view('print/printFeesByNameNew', $data, true);
                            ob_start();
                            $view_content = $this->printFeesByName_previous_id($sub_invoice_id,$this->current_ay_session);
                            $print_record = ob_get_clean();
                        } else {
                            $body           = $this->load->view('print/mail_invoice_new', $data, true);
                        }
                        if (!empty($email) && $communication==1) { 
                            $this->send_mail($email, 'Fee Submission', $body);
                        }
                    }
                    foreach ($total_row as $total_row_key => $total_row_value) {
                        $mailsms_array                 = $this->feegrouptype_model->getFeeGroupByID($this->input->post('fee_groups_feetype_id_' . $total_row_value));
                        $mailsms_array->invoice        = json_encode($fees_record[$total_row_key]);
                        $mailsms_array->contact_no     = $send_to;
                        $mailsms_array->email          = $email;
                        $mailsms_array->parent_app_key = $parent_app_key;
                        // $this->mailsmsconf->mailsms('fee_submission', $mailsms_array);
                    }
                    $array = array('status' => 1, 'error' => '','mode' => 'direct', 'print' => $print_record);
                    echo json_encode($array);
                    }
                }
        }
    }
    public function print_cheque_receipt($chqid)
    {
        $chq_result = $this->db->query("select * from cheque_inword where id = '$chqid' ")->row_array();
        if(!empty($chq_result))
        {
            $student_session_id     = $chq_result['student_session_id'];
            $data['student']        = $this->studentsession_model->searchStudentsBySession($student_session_id);
            $st_session_id          = $data['student']['session_id'];
            $data['student_session']= $this->session_model->get($st_session_id);
            $data['chq']            = $chq_result;
            $setting_result         = $this->setting_model->get();
            $data['settinglist']    = $setting_result[0];
            $data['sch_setting']    = $this->sch_setting_detail;                
            $data['session']        = $this->setting_model->getCurrentSession();    
                     
            $this->load->view('print/cheque_receipt', $data);              
        }               
        else
        {
            echo "Invalid Cheque details";
        }
    }

    public function addfeegrp_gateway_submit($hash_code)
    {
        // ini_set ('display_errors', 1); ini_set ('display_startup_errors', 1); error_reporting (E_ALL);        

        $staff_record = $this->session->userdata('admin');
        $result=$this->onlinestudent_model->get_online_record($hash_code);
        if(!empty($result))
        {
            $chequeid = $result['chequeid'];
            if($result['trn_status']=='pending')
            {
                $online_id = $result['id'];
                $post_array = (array) json_decode($result['trn_data'],true);
            }
            elseif($result['receipt_id']!='')
            {
                redirect('studentfee/printFeesByName_previous_id/'.$result['receipt_id']);
            }
        }
        else
        {$online_id = -1;}
        if($result['payment_mode']=='cheque')
        {}  
        else
        {$chequeid = -1;$cheqDate = date('Y-m-d');}

        $cheqDate = date('Y-m-d'); 
        $post_array['collected_date'] = $cheqDate;
        $collected_array = array();
        $student_fees_discount_id = array();
        $collected_by       = " Collected By: " . $this->customlib->getAdminSessionUserName();
        $data['student_session_id'] = $student_session_id = $post_array['student_session_id'];
        $student_rec                = $this->studentsession_model->searchStudentsBySession($student_session_id);
        $send_to            = $student_rec['mobileno'];
        $email              = $student_rec['email'];//$post_array['guardian_email'];
        $parent_app_key     = $post_array['parent_app_key'];
        $paid_amount        = $post_array['paid_amount'];
        $paid_fine_amount   = $post_array['tot_fine_amount'];
        $paid_fine_amount   = $post_array['tot_fine_amount'];
        $net_fine           = $post_array['net_fine'];        
        $prev_paid          = $post_array['previous_paid'];
        $paid_fine_amount   = $paid_fine_amount + $net_fine;
        //$student_fees_discount_id = $this->input->post('discount_amt');
        $total_row = $post_array['row_counter'];
        $total_amount = 0;
        $total_discount = 0;
        $total_fine = 0;
        $net_total = 0;
        foreach ($total_row as $total_row_key => $total_row_value) {
            $post_array['student_fees_master_id_' . $total_row_value];
            $post_array['fee_groups_feetype_id_' . $total_row_value];
            $discount_amt = $post_array['amount_discount_' . $total_row_value];
            if ($discount_amt > 0) {
                $amt = $post_array['fee_amount_' . $total_row_value] - $discount_amt;
                //$amt = $this->input->post('fee_amount_' . $total_row_value);
            } else {
                $amt = $post_array['fee_amount_' . $total_row_value];
            }
            $this->db->where('id', $post_array['student_fees_master_id_' . $total_row_value]);
            $student_fees_master = $this->db->get('student_fees_master')->row_array();
            
            $json_array = array(
                'amount'          => $amt,
                'date'            => date('Y-m-d', strtotime($post_array['collected_date'])),
                'description'     => $post_array['fee_gupcollected_note'] . $collected_by,
                'amount_discount' => $post_array['amount_discount_' . $total_row_value],
                'amount_fine'     => $post_array['fee_groups_feetype_fine_amount_' . $total_row_value],
                'payment_mode'    => $post_array['payment_mode_fee'],
                'received_by'     => $staff_record['id'],
                'student_session_id'     => $student_fees_master['student_session_id'],
                'discount_id'     => $post_array['disc_code_' . $total_row_value],
                'session_id'     => $this->current_ay_session,
            );
            if(!empty($post_array['disc_code_' . $total_row_value]))
            {
                $current_session_id = $this->setting_model->getCurrentSession();
                $this->db->where('student_session_id',$student_fees_master['student_session_id']);
                $this->db->where('fees_discount_id',$post_array['disc_code_' . $total_row_value]);
                $this->db->where('session_id',$current_session_id);
                $disc_rows = $this->db->get('student_fees_discounts')->row_array();
                if(empty($disc_rows))
                {
                    $insert_array = array(
                        'student_session_id' => $student_fees_master['student_session_id'],
                        'fees_discount_id' => $post_array['disc_code_' . $total_row_value],
                        'is_active' => "Yes",
                        'session_id' => $current_session_id,
                    );
                    $student_fees_discount_id[] = $this->feediscount_model->allotdiscount($insert_array);
                }
                else
                {
                    $student_fees_discount_id[] = $disc_rows['id'];
                }
            }
            $collected_array[] = array(
                'student_fees_master_id' => $post_array['student_fees_master_id_' . $total_row_value],
                'fee_groups_feetype_id'  => $post_array['fee_groups_feetype_id_' . $total_row_value],
                'amount_detail'          => $json_array,
            );
            $total_amount += $amt;
            $total_discount += $post_array['amount_discount_' . $total_row_value];
            $total_fine += $post_array['fee_groups_feetype_fine_amount_' . $total_row_value];
        }
        $deposited_fees = $this->studentfeemaster_model->fee_deposit_collections($collected_array, $student_fees_discount_id);
        $fees_record    = json_decode($deposited_fees);
        foreach ($fees_record as  $feeRow) {
            $print = $post_array['print'];
            $print_record       = array();
            $invoice_id = $feeRow->invoice_id;
            $data['invo_no'] = $sub_invoice_id = $feeRow->sub_invoice_id;
            $data['payment_id'] = $sub_invoice_id;
            $fee_groups_feetype_id = $post_array['fee_groups_feetype_id_' . $total_row_value];
            $fee_master_id         = $post_array['student_fees_master_id_' . $total_row_value];
            $setting_result        = $this->setting_model->get();
            $data['settinglist']   = $setting_result[0];
            $data['sch_setting'] = $this->sch_setting_detail;
            foreach ($total_row as $total_row_key => $total_row_value) {
                $this->db->where('id', $post_array['student_fees_master_id_' . $total_row_value]);
                $student_fees_master = $this->db->get('student_fees_master')->row_array();
                $student_session_id = $student_fees_master['student_session_id'];
                $student                = $this->studentsession_model->searchStudentsBySession($student_session_id);
                $fee_record             = $this->studentfeemaster_model->getFeeByInvoicereturn($invoice_id, $sub_invoice_id, $student_session_id);
                $data['fees_array']             = $this->studentfeemaster_model->getStudentFees($student_session_id);
                $data['previousfees']             = $this->studentfeemaster_model->getPreviousStudentFees($student_session_id);
            }
            $data['student']        = $student;
            $data['sub_invoice_id'] = $sub_invoice_id;
            $data['feeList']        = $fee_record;
            $data['sch_setting']    = $this->sch_setting_detail;
            $remain_amount_object   = $this->getStuFeetypeBalance($fee_groups_feetype_id, $fee_master_id);
            $data['remain_amount']          = json_decode($remain_amount_object)->balance;
            $data['total_paid']             = $post_array['total_paid'] +  $total_amount;
            $data['total_balance']          = $post_array['total_balance'] - $total_amount - $total_discount;
            $data['discount_amt']           = $total_discount;
            $data['session']                = $this->setting_model->getCurrentSession();
            $gross_amt = $paid_amount + $total_discount - $paid_fine_amount;
            $net_total = $paid_amount;
            $arrayUpdate = array(
                'id'                        => $sub_invoice_id,
                'receipt_date'              => date('Y-m-d', strtotime($post_array['collected_date'])),
                'gross_amount'              => $gross_amt,
                'discount'                  => $total_discount,
                'fine'                      => $paid_fine_amount,
                'net_amt'                   => $net_total,
                'total_balance'             => $data['total_balance'],
                'prev_balance'              => $prev_paid,                 
                'student_session_id'        => $student_session_id,
                'session_id'                => $student['session_id'],
                'created_by'                => $this->customlib->getAdminSessionUserName(),
                'created_id'                => $staff_record['id'],
                'note'                      => $result['note'],
                'payment_mode'              => $post_array['payment_mode_fee'],
                'chequeid'                  => $chequeid,
            );
            $this->studentfeemaster_model->update_receipt($arrayUpdate,$this->current_ay_session);
            $st_name = strtoupper($data['student']['firstname']." ".$data['student']['middlename']." ".$data['student']['lastname']);
            $class_div = $data['student']['code']."-".$data['student']['section'];
            $mobno = $this->staff_model->get_class_teacher($student_session_id);
            $data['total_balance'] = $this->studentfeemaster_model->get_student_balance_up_recid($student_session_id,$sub_invoice_id);                         
            if($mobno)
            {
                $data_msg1 = array(
                    "mobno" => $mobno,
                    //"mobno" => "9605252637",
                    "name" => $st_name,
                    "class" => $class_div,
                    "amount" => $net_total,
                    "rec_no" => $sub_invoice_id,
                    "rec_date" => date('d-m-Y', strtotime($post_array['collected_date']))
                );
                $this->wati_model->send_receipt_to_class_teacher($data_msg1);                
            }
            if($student['mobileno']!='')
            {
                $data_msg = array(
                    "mobno" => $student['mobileno'],
                    //"mobno" => "9605252637",
                    "name" => $st_name,
                    "class" => $class_div,
                    "amount" => $net_total,
                    "rec_no" => $sub_invoice_id,
                    "rec_date" => date('d-m-Y', strtotime($post_array['collected_date']))
                );
                //$this->wati_model->send_receipt_to_parent($data_msg);              
            }
            $data['note']=$this->input->post('fee_gupcollected_note');
            $receipt                        = $this->studentfee_model->getReceipt_ay($sub_invoice_id);
            $data['receipt']                = $receipt; 
            $data['receipt_session']        = $this->session_model->get($student['session_id']); 
            if($receipt->chequeid>0)
            {$data['cheque'] = $this->feemaster_model->getCheque_only($receipt->chequeid);}
            else
            {$data['cheque'] = array();}           
            $data_rec = array(
                "receipt_id" => $sub_invoice_id,
                'pass_date' => date('Y-m-d', strtotime($cheqDate)),
                'trn_status' => 'passed',                
            );
            $this->db->where('id',$online_id);
            $this->db->update('online_transaction',$data_rec);
            $data['billing_session']        = $this->session_model->get($this->current_ay_session); 
            if ($print == 1) {
                $body           = $this->load->view('print/mail_invoice_new', $data, true);
                // $print_record  = $this->load->view('print/printFeesByNameNew', $data, true);
                ob_start();
                $view_content = $this->printFeesByName_previous_id($sub_invoice_id,$this->current_ay_session);
                $print_record = ob_get_clean();
            } else {
                $body           = $this->load->view('print/mail_invoice_new', $data, true);
            }
            if (!empty($email)) {
                $this->send_mail($email, 'Fee Submission', $body);
            }
        }
        foreach ($total_row as $total_row_key => $total_row_value) {
            $mailsms_array                 = $this->feegrouptype_model->getFeeGroupByID($post_array['fee_groups_feetype_id_' . $total_row_value]);
            $mailsms_array->invoice        = json_encode($fees_record[$total_row_key]);
            $mailsms_array->contact_no     = $send_to;
            $mailsms_array->email          = $email;
            $mailsms_array->parent_app_key = $parent_app_key;
            // $this->mailsmsconf->mailsms('fee_submission', $mailsms_array);
        }
        $data['billing_session']        = $this->session_model->get($this->setting_model->getCurrentSession());         
        $print_record  = $this->load->view('print/printFeesByNameNew', $data, true);
        $data['invoice'] = $print_record;
        $data['source'] = 'counter-main';
        $data['st_session_id']=$student_session_id;
        $data['mode']=$post_array['payment_mode_fee'];        
        $this->load->view('print/FeesPrint', $data);
    }

    public function addfeegrp_previous()
    {
        $staff_record = $this->session->userdata('admin');
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('row_counter[]', $this->lang->line('fees_list'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('collected_date', $this->lang->line('date'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('valid_amount', $this->lang->line('amount'), 'required|callback_greater_than_or[' . $this->input->post('valid_amount') . ']|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'row_counter'    => form_error('row_counter'),
                'collected_date' => form_error('collected_date'),
                'valid_amount' => form_error('valid_amount'),
            );
            $array = array('status' => 0, 'error' => $data);
            echo json_encode($array);
        } else {
            if($this->input->post('payment_mode_fee')=="gateway")
            {
                $post_array = $this->input->post();
                $post_array_json = json_encode($post_array);
                $array_data = array(
                    'session_id' => $this->setting_model->getCurrentSession(),
                    'student_session_id' => $this->input->post('student_session_id'),
                    'amount' => $this->input->post('paid_amount'),
                    'trn_data' => $post_array_json,
                    'session_data' => json_encode($this->session->userdata()),
                    'trn_status' => 'pending',
                    "source" => 'counter-previous',
                );
                $result=$this->studentfee_model->add_online_cart($array_data);
                if(!empty($result))
                {
                    $array = array('status' => 1, 'error' => '', 'mode' => 'gateway', 'hash_code' => $result['hash_code'] );
                    echo json_encode($array);
                }
            }
            elseif($this->input->post('payment_mode_fee')=="Cheque")
            {
                $userdata           = $this->customlib->getUserData();
                $st_session_id = $this->input->post('student_session_id');
                $class_details = $this->db->query("select class_id,section_id from student_session where id = '$st_session_id'")->row_array();
                $insert_array = array(
                    'class_id' => $class_details['class_id'],
                    'section_id' => $class_details['section_id'],
                    'student_session_id' => $this->input->post('student_session_id'),
                    'session_id' => $this->current_ay_session,
                    'chq_type' => "student",
                    'chq_no' => $this->input->post('cheque_no'),
                    'chq_date' => date('Y-m-d', strtotime($this->input->post('cheque_date'))),
                    'chq_bank' => $this->input->post('cheque_bank'),
                    'chq_branch' => '',
                    'chq_amt' => $this->input->post('paid_amount'),
                    'chq_status' => "collected",
                    'contact_no' => '',
                    'remarks' => $this->input->post('fee_gupcollected_note'),
                    'created_by' =>  $userdata['name'],
                    
                );    
                $record_id = $this->feemaster_model->addCheque($insert_array);
                $post_array = $this->input->post();
                $post_array_json = json_encode($post_array);
                $array_data = array(
                    'session_id' => $this->current_ay_session,
                    'student_session_id' => $this->input->post('student_session_id'),
                    'amount' => $this->input->post('paid_amount'),
                    'trn_data' => $post_array_json,
                    'session_data' => json_encode($this->session->userdata()),
                    'trn_status' => 'pending',
                    "source" => 'counter-previous',
                    "payment_mode" => 'cheque',
                    "chequeid" => $record_id,
                    'note' => $chqnote . " " . $this->input->post('fee_gupcollected_note'),
                );
                $result=$this->studentfee_model->add_online_cart($array_data);
                $student_session_id=$this->input->post('student_session_id');
                $data['student']                = $this->studentsession_model->searchStudentsBySession($student_session_id);                
                $data['chq'] = $insert_array;
                $setting_result        = $this->setting_model->get();
                $data['settinglist']   = $setting_result[0];
                $data['sch_setting'] = $this->sch_setting_detail;                
                $data['session']        = $this->setting_model->getCurrentSession();    
                $data['created_by']     =$this->customlib->getAdminSessionUserName();
                $data['created_at']     = date('d-m-Y h:m:s');
                $data['created_date']     = date('d-m-Y');              
                $print_record  = $this->load->view('print/cheque_receipt', $data, true);                
                if(!empty($result))
                {
                    $array = array('status' => 1, 'error' => '', 'mode' => 'cheque', 'print' => $print_record );
                    echo json_encode($array);
                }
            }    
            else
            {
                $collected_array = array();
                $student_fees_discount_id = array();
                $collected_by       = " Collected By: " . $this->customlib->getAdminSessionUserName();
                $send_to            = $this->input->post('guardian_phone');
                $email              = "manojthannimattam@gmail.com"; //$this->input->post('guardian_email');
                $parent_app_key     = $this->input->post('parent_app_key');
                $paid_amount        = $this->input->post('paid_amount');
                $paid_fine_amount   = $this->input->post('tot_fine_amount');
                $prev_paid          = $this->input->post('prev_paid');
                $session_id         = $this->input->post('session_id');
                $data['student_session_id'] = $student_session_id = $this->input->post('student_session_id');
                //$student_fees_discount_id = $this->input->post('discount_amt');
                $total_row = $this->input->post('row_counter');
                $total_amount = 0;
                $total_discount = 0;
                $total_fine = 0;
                $net_total = 0;
                foreach ($total_row as $total_row_key => $total_row_value) {
                    $this->input->post('student_fees_master_id_' . $total_row_value);
                    $this->input->post('fee_groups_feetype_id_' . $total_row_value);
                    $discount_amt = $this->input->post('amount_discount_' . $total_row_value);
                    if ($discount_amt > 0) {
                        $amt = $this->input->post('fee_amount_' . $total_row_value) - $discount_amt;
                        //$amt = $this->input->post('fee_amount_' . $total_row_value);
                    } else {
                        $amt = $this->input->post('fee_amount_' . $total_row_value);
                    }
                    $this->db->where('id', $this->input->post('student_fees_master_id_' . $total_row_value));
                    $student_fees_master = $this->db->get('student_fees_master')->row_array();

                    $json_array = array(
                        'amount'          => $amt,
                        'date'            => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('collected_date'))),
                        'description'     => $this->input->post('fee_gupcollected_note') . $collected_by,
                        'amount_discount' => $this->input->post('amount_discount_' . $total_row_value),
                        'amount_fine'     => $this->input->post('fee_groups_feetype_fine_amount_' . $total_row_value),
                        'payment_mode'    => $this->input->post('payment_mode_fee'),
                        'received_by'     => $staff_record['id'],
                        'student_session_id'     => $student_fees_master['student_session_id'],
                        'discount_id'     => $this->input->post('disc_code_' . $total_row_value),
                        'session_id'      => $this->current_ay_session,
                    );
                    if(!empty($this->input->post('disc_code_' . $total_row_value)))
                    {
                        $current_session_id = $this->setting_model->getCurrentSession();
                        $this->db->where('student_session_id',$student_fees_master['student_session_id']);
                        $this->db->where('fees_discount_id',$this->input->post('disc_code_' . $total_row_value));
                        $this->db->where('session_id',$session_id);
                        $disc_rows = $this->db->get('student_fees_discounts')->row_array();
                        if(empty($disc_rows))
                        {
                            $insert_array = array(
                                'student_session_id' => $student_fees_master['student_session_id'],
                                'fees_discount_id' => $this->input->post('disc_code_' . $total_row_value),
                                'is_active' => "Yes",
                                'session_id' => $session_id,
                            );
                            $student_fees_discount_id[] = $this->feediscount_model->allotdiscount($insert_array);
                        }
                        else
                        {
                            $student_fees_discount_id[] = $disc_rows['id'];
                        }
                    }
                    $collected_array[] = array(
                        'student_fees_master_id' => $this->input->post('student_fees_master_id_' . $total_row_value),
                        'fee_groups_feetype_id'  => $this->input->post('fee_groups_feetype_id_' . $total_row_value),
                        'amount_detail'          => $json_array,
                    );
                    $total_amount += $amt;
                    $total_discount += $this->input->post('amount_discount_' . $total_row_value);
                    $total_fine += $this->input->post('fee_groups_feetype_fine_amount_' . $total_row_value);
                }
                $deposited_fees = $this->studentfeemaster_model->fee_deposit_collections($collected_array, $student_fees_discount_id);
                $fees_record    = json_decode($deposited_fees);
                foreach ($fees_record as  $feeRow) {
                    $print = $this->input->post('print');
                    $print_record       = array();
                    $invoice_id = $feeRow->invoice_id;
                    $data['inv_no'] = $data['invo_no'] = $sub_invoice_id = $feeRow->sub_invoice_id;
                    $data['payment_id'] = $sub_invoice_id;
                    $fee_groups_feetype_id = $this->input->post('fee_groups_feetype_id_' . $total_row_value);
                    $fee_master_id         = $this->input->post('student_fees_master_id_' . $total_row_value);
                    $setting_result        = $this->setting_model->get();
                    $data['settinglist']   = $setting_result[0];
                    $data['sch_setting'] = $this->sch_setting_detail;
                    foreach ($total_row as $total_row_key => $total_row_value) {
                        $this->db->where('id', $this->input->post('student_fees_master_id_' . $total_row_value));
                        $student_fees_master = $this->db->get('student_fees_master')->row_array();
                        $student_session_id = $student_fees_master['student_session_id'];
                        $student                = $this->studentsession_model->searchStudentsBySession($student_session_id);
                        $fee_record             = $this->studentfeemaster_model->getFeeByInvoicereturn($invoice_id, $sub_invoice_id, $student_session_id);
                        $data['fees_array']             = $this->studentfeemaster_model->getStudentFees($student_session_id);
                        $data['previousfees']             = $this->studentfeemaster_model->getPreviousStudentFees($student_session_id);
                    }
                    $data['receipt_session']        = $this->session_model->get($student['session_id']);
                    $data['receipt_session_id']     = $student['session_id'];
                    $data['student']        = $student;
                    $data['sub_invoice_id'] = $sub_invoice_id;
                    $data['feeList']        = $fee_record;
                    $data['sch_setting']    = $this->sch_setting_detail;
                    $data['session']                = $this->setting_model->getCurrentSession();
                    $total_balance          = $this->input->post('total_balance') - $total_amount - $total_discount;
                    $gross_amt = $paid_amount + $total_discount - $paid_fine_amount;
                    $net_total = $paid_amount;
                    $data['paidamt']                = $gross_amt;
                    $data['total_amt']              = $net_total;
                    $data['total_paid']             = $this->input->post('total_paid');
                    $data['total_balance']          = $total_balance;
                    $data['discount_amt']           = $total_discount;
                    $arrayUpdate = array(
                        'id'                        => $sub_invoice_id,
                        'receipt_date'              => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('collected_date'))),
                        'gross_amount'              => $gross_amt,
                        'discount'                  => $total_discount,
                        'fine'                      => $paid_fine_amount,
                        'net_amt'                   => $net_total,
                        'total_balance'             => $total_balance,
                        'prev_balance'              => $prev_paid,
                        'student_session_id'        => $student_session_id,
                        'session_id'                => $student['session_id'],
                        'created_by'                => $this->customlib->getAdminSessionUserName(),
                        'created_id'                => $staff_record['id'],
                        'note'                      => $this->input->post('fee_gupcollected_note'),
                        'payment_mode'              => $this->input->post('payment_mode_fee'),
                    );
                    $this->studentfeemaster_model->update_receipt($arrayUpdate,$this->current_ay_session);
                    $data['receipt_date'] = date('d-m-Y', $this->customlib->datetostrtotime($this->input->post('collected_date')));
                    $st_session = $this->session_model->get($session_id);
                    $data['st_session'] = $st_session['session'];
                    $mobno = $this->staff_model->get_class_teacher($data['student_session_id']);
                    $st_name = strtoupper($data['student']['firstname']." ".$data['student']['middlename']." ".$data['student']['lastname']);
                    $class_div = $data['student']['code']."-".$data['student']['section']."[".$st_session['session']."]";
                    if($mobno)
                    {
                        $data_msg1 = array(
                            "mobno" => $mobno,
                            "name" => $st_name,
                            "class" => $class_div,
                            "amount" => $net_total,
                            "rec_no" => $sub_invoice_id,
                            "rec_date" => date('d-m-Y', $this->customlib->datetostrtotime($this->input->post('collected_date')))
                        );
                        $this->wati_model->send_receipt_to_class_teacher($data_msg1);                
                    }
                    if($student['mobileno']!='')
                    {
                        $data_msg = array(
                            "mobno" => $student['mobileno'],
                            "name" => $st_name,
                            "class" => $class_div,
                            "amount" => $net_total,
                            "rec_no" => $sub_invoice_id,
                            "rec_date" => date('d-m-Y', $this->customlib->datetostrtotime($this->input->post('collected_date')))
                        );
                        //$this->wati_model->send_receipt_to_parent($data_msg);              
                    }
                    $receipt                        = $this->studentfee_model->getReceipt_ay($sub_invoice_id);
                    $data['receipt']                = $receipt;    
                    if($receipt->chequeid>0)
                    {$data['cheque'] = $this->feemaster_model->getCheque_only($receipt->chequeid);}
                    else
                    {$data['cheque'] = array();}                                   
                    $data['id'] = $sub_invoice_id;
                    $data['billing_session']        = $this->session_model->get($this->current_ay_session);
                    if ($print == 1) {
                        $body           = $this->load->view('print/mail_invoice_previous_new', $data, true);
                        $print_record  = $this->load->view('print/printFeesByNameNew_previous', $data, true);
                    } else {
                        $body           = $this->load->view('print/mail_invoice_previous_new', $data, true);
                    }
                    if (!empty($email)) {
                        $this->send_mail($email, 'Fee Submission', $body);
                    }
                }
                foreach ($total_row as $total_row_key => $total_row_value) {
                    $mailsms_array                 = $this->feegrouptype_model->getFeeGroupByID($this->input->post('fee_groups_feetype_id_' . $total_row_value));
                    $mailsms_array->invoice        = json_encode($fees_record[$total_row_key]);
                    $mailsms_array->contact_no     = $send_to;
                    $mailsms_array->email          = $email;
                    $mailsms_array->parent_app_key = $parent_app_key;
                    // $this->mailsmsconf->mailsms('fee_submission', $mailsms_array);
                }
                $array = array('status' => 1, 'error' => '', 'print' => $print_record);
                echo json_encode($array);
            }
        }
    }
    public function addfeegrp_gateway_submit_previous($hash_code)
    {
        $staff_record = $this->session->userdata('admin');
        $result=$this->onlinestudent_model->get_online_record($hash_code);
        if(!empty($result))
        {
            $chequeid = $result['chequeid'];
            if($result['trn_status']=='pending')
            {
                $online_id = $result['id'];
                $post_array = (array) json_decode($result['trn_data'],true);
            }
            elseif($result['receipt_id']!='')
            {
                redirect('studentfee/printFeesByName_previous_id/'.$result['receipt_id']);
            }
        }
        else
        {
            $online_id = -1;
        }
        if($result['payment_mode']=='cheque')
        {
            
        }  
        else
        {
            $cheqDate = date('Y-m-d');
            $chequeid = -1;
        }
        $cheqDate = date('Y-m-d'); 
        $collected_array = array();
        $student_fees_discount_id = array();
        $post_array['collected_date'] = $cheqDate;
        $collected_by    = " Collected By: " . $this->customlib->getAdminSessionUserName();

        $send_to            = $post_array['guardian_phone'];
        $email              = "manojthannimattam@gmail.com";//$post_array['guardian_email'];
        $parent_app_key     = $post_array['parent_app_key'];
        $paid_amount        = $post_array['paid_amount'];
        $paid_fine_amount   = $post_array['tot_fine_amount'];
        $prev_paid          = $post_array['prev_paid'];
        $session_id         = $post_array['session_id'];
        $data['student_session_id'] = $student_session_id = $post_array['student_session_id'];
        //$student_fees_discount_id = $post_array['discount_amt'];
        $total_row = $post_array['row_counter'];

        $total_amount = 0;
        $total_discount = 0;
        $total_fine = 0;
        $net_total = 0;

        foreach ($total_row as $total_row_key => $total_row_value) {

            $post_array['student_fees_master_id_' . $total_row_value];
            $post_array['fee_groups_feetype_id_' . $total_row_value];
            $discount_amt = $post_array['amount_discount_' . $total_row_value];
            if ($discount_amt > 0) {
                $amt = $post_array['fee_amount_' . $total_row_value] - $discount_amt;
                //$amt = $post_array['fee_amount_' . $total_row_value];
            } else {
                $amt = $post_array['fee_amount_' . $total_row_value];
            }
            $this->db->where('id', $post_array['student_fees_master_id_' . $total_row_value]);
            $student_fees_master = $this->db->get('student_fees_master')->row_array();

            $json_array = array(
                'amount'          => $amt,
                'date'            => date('Y-m-d', $this->customlib->datetostrtotime($post_array['collected_date'])),
                'description'     => $post_array['fee_gupcollected_note'] . $collected_by,
                'amount_discount' => $post_array['amount_discount_' . $total_row_value],
                'amount_fine'     => $post_array['fee_groups_feetype_fine_amount_' . $total_row_value],
                'payment_mode'    => $post_array['payment_mode_fee'],
                'received_by'     => $staff_record['id'],
                'student_session_id'     => $student_fees_master['student_session_id'],
                'discount_id'     => $post_array['disc_code_' . $total_row_value],
                'session_id'     => $this->current_ay_session,
            );
            if(!empty($post_array['disc_code_' . $total_row_value]))
            {
                $current_session_id = $this->setting_model->getCurrentSession();
                $this->db->where('student_session_id',$student_fees_master['student_session_id']);
                $this->db->where('fees_discount_id',$post_array['disc_code_' . $total_row_value]);
                $this->db->where('session_id',$session_id);
                $disc_rows = $this->db->get('student_fees_discounts')->row_array();
                if(empty($disc_rows))
                {
                    $insert_array = array(
                        'student_session_id' => $student_fees_master['student_session_id'],
                        'fees_discount_id' => $post_array['disc_code_' . $total_row_value],
                        'is_active' => "Yes",
                        'session_id' => $session_id,
                    );
                    $student_fees_discount_id[] = $this->feediscount_model->allotdiscount($insert_array);
                }
                else
                {
                    $student_fees_discount_id[] = $disc_rows['id'];
                }
            }
            $collected_array[] = array(
                'student_fees_master_id' => $post_array['student_fees_master_id_' . $total_row_value],
                'fee_groups_feetype_id'  => $post_array['fee_groups_feetype_id_' . $total_row_value],
                'amount_detail'          => $json_array,
            );
            $total_amount += $amt;
            $total_discount += $post_array['amount_discount_' . $total_row_value];
            $total_fine += $post_array['fee_groups_feetype_fine_amount_' . $total_row_value];
        }
        $deposited_fees = $this->studentfeemaster_model->fee_deposit_collections($collected_array, $student_fees_discount_id);
        $fees_record    = json_decode($deposited_fees);
        foreach ($fees_record as  $feeRow) {
            $print = $post_array['print'];
            $print_record       = array();
            $invoice_id = $feeRow->invoice_id;
            $data['inv_no'] = $data['invo_no'] = $sub_invoice_id = $feeRow->sub_invoice_id;
            $data['payment_id'] = $sub_invoice_id;
            $fee_groups_feetype_id = $post_array['fee_groups_feetype_id_' . $total_row_value];
            $fee_master_id         = $post_array['student_fees_master_id_' . $total_row_value];

            $setting_result        = $this->setting_model->get();
            $data['settinglist']   = $setting_result[0];
            $data['sch_setting'] = $this->sch_setting_detail;
            foreach ($total_row as $total_row_key => $total_row_value) {
                $this->db->where('id', $post_array['student_fees_master_id_' . $total_row_value]);
                $student_fees_master = $this->db->get('student_fees_master')->row_array();
                $student_session_id = $student_fees_master['student_session_id'];
                $student                = $this->studentsession_model->searchStudentsBySession($student_session_id);
                $fee_record             = $this->studentfeemaster_model->getFeeByInvoicereturn($invoice_id, $sub_invoice_id, $student_session_id);
                $data['fees_array']             = $this->studentfeemaster_model->getStudentFees($student_session_id);
                $data['previousfees']             = $this->studentfeemaster_model->getPreviousStudentFees($student_session_id);
            }
            $data['receipt_session']        = $this->session_model->get($session_id);
            $data['receipt_session_id']        = $session_id;
            $data['student']        = $student;
            $data['sub_invoice_id'] = $sub_invoice_id;
            $data['feeList']        = $fee_record;
            $data['sch_setting']    = $this->sch_setting_detail;
            $data['session']                = $this->setting_model->getCurrentSession();
            
            $total_balance          = $post_array['total_balance'] - $total_amount - $total_discount;
            $gross_amt = $paid_amount + $total_discount - $paid_fine_amount;
            $net_total = $paid_amount;
            $data['paidamt']                = $gross_amt;
            $data['total_amt']              = $net_total;
            $data['total_paid']             = $post_array['total_paid'];
            $data['total_balance']          = $total_balance;
            $data['discount_amt']           = $total_discount;
            $arrayUpdate = array(
                'id'                        => $sub_invoice_id,
                'receipt_date'              => date('Y-m-d', $this->customlib->datetostrtotime($post_array['collected_date'])),
                'gross_amount'              => $gross_amt,
                'discount'                  => $total_discount,
                'fine'                      => $paid_fine_amount,
                'net_amt'                   => $net_total,
                'total_balance'             => $total_balance,
                'prev_balance'              => $prev_paid,
                'student_session_id'        => $student_session_id,
                'session_id'                => $session_id,
                'created_by'                => $this->customlib->getAdminSessionUserName(),
                'created_id'                => $staff_record['id'],
                'note'                      => $post_array['fee_gupcollected_note'],
                'payment_mode'              => $post_array['payment_mode_fee'],
                'chequeid'                  => $chequeid,
            );
            $this->studentfeemaster_model->update_receipt($arrayUpdate,$this->current_ay_session);
            $st_session = $this->session_model->get($session_id);
            $data['st_session'] = $st_session['session'];
            $mobno = $this->staff_model->get_class_teacher($data['student_session_id']);
            $st_name = strtoupper($data['student']['firstname']." ".$data['student']['middlename']." ".$data['student']['lastname']);
            $class_div = $data['student']['code']."-".$data['student']['section']."[".$st_session['session']."]";
            if($mobno)
            {
                $data_msg1 = array(
                    "mobno" => $mobno,
                    "name" => $st_name,
                    "class" => $class_div,
                    "amount" => $net_total,
                    "rec_no" => $sub_invoice_id,
                    "rec_date" => date('d-m-Y', $this->customlib->datetostrtotime($post_array['collected_date']))
                );
                $this->wati_model->send_receipt_to_class_teacher($data_msg1);                
            }

            if($student['mobileno']!='')
            {
                $data_msg = array(
                    "mobno" => $student['mobileno'],
                    "name" => $st_name,
                    "class" => $class_div,
                    "amount" => $net_total,
                    "rec_no" => $sub_invoice_id,
                    "rec_date" => date('d-m-Y', $this->customlib->datetostrtotime($post_array['collected_date']))
                );
                //$this->wati_model->send_receipt_to_parent($data_msg);              
            }
            $receipt                        = $this->studentfee_model->getReceipt_ay($sub_invoice_id);
            if($receipt->chequeid>0)
            {$data['cheque'] = $this->feemaster_model->getCheque_only($receipt->chequeid);}
            else
            {$data['cheque'] = array();}               
            $data['receipt']                = $receipt;   
            $data['receipt_session_id']     = $receipt->session_id;
            $data['receipt_session']        = $this->session_model->get($receipt->session_id);                         
            $data['id'] = $sub_invoice_id;
            $data['billing_session']        = $this->session_model->get($this->setting_model->getCurrentSession());
            $data_rec = array(
                "receipt_id" => $sub_invoice_id,
                'pass_date' => date('Y-m-d', strtotime($cheqDate)),
                'trn_status' => 'passed',                
            );
            $this->db->where('id',$online_id);
            $this->db->update('online_transaction',$data_rec);            
            if ($print == 1) {
                $body           = $this->load->view('print/mail_invoice_previous_new', $data, true);
                $print_record  = $this->load->view('print/printFeesByNameNew_previous', $data, true);
            } else {
                $body           = $this->load->view('print/mail_invoice_previous_new', $data, true);
            }
            if (!empty($email)) {
                $this->send_mail($email, 'Fee Submission', $body);
            }
        }
        foreach ($total_row as $total_row_key => $total_row_value) {
            $mailsms_array                 = $this->feegrouptype_model->getFeeGroupByID($post_array['fee_groups_feetype_id_' . $total_row_value]);
            $mailsms_array->invoice        = json_encode($fees_record[$total_row_key]);
            $mailsms_array->contact_no     = $send_to;
            $mailsms_array->email          = $email;
            $mailsms_array->parent_app_key = $parent_app_key;
            // $this->mailsmsconf->mailsms('fee_submission', $mailsms_array);
        }

        $data['billing_session']  = $this->session_model->get($this->setting_model->getCurrentSession()); 
        $print_record  = $this->load->view('print/printFeesByNameNew_previous', $data, true);
        $data['invoice'] = $print_record;
        $data['source'] = 'counter-previous';
        $data['st_session_id']=$student_session_id;
        $data['mode']=$post_array['payment_mode_fee'];        
        $this->load->view('print/FeesPrint', $data);
    }
    public function printFeesByName_previous_id($ids="",$recsessionid=0)
    {
        // ini_set ('display_errors', 1); 
        // ini_set ('display_startup_errors', 1);
        // error_reporting (E_ALL);         
        $data                           = array('payment' => "0");
        if($ids=='')
        { 
          $id                             = $this->input->post('id');
          $rec_session_id                 = $this->input->post('recsessionid');
        }
        else
        {$id=$ids;$rec_session_id=$recsessionid;}
        $receipt                        = $this->studentfee_model->getReceipt($id,$rec_session_id);
        if($receipt->chequeid>0)
        {$data['cheque'] = $this->feemaster_model->getCheque_only($receipt->chequeid);}
        else
        {$data['cheque'] = array();}           
        $data['receipt']                = $receipt;
        if($receipt->chequeid>0)
        {
            $data['cheque'] = $this->feemaster_model->getCheque_only($receipt->chequeid);
        }
        else
        {$data['cheque'] = array();}

        $invoice_id                     = $id;
        $data['inv_no']    = $data['invo_no']            = $sub_invoice_id = $id;
        $data['student_session_id']     = $student_session_id             = $receipt->student_session_id;
        $userdata                       = $this->customlib->getUserData();
        $fee_balance = $this->studentfeemaster_model->get_student_balance_up_recid1($student_session_id,$id,$receipt->receipt_date);
        $fee_balance_previous = $this->studentfeemaster_model->get_student_previous_up_recid1($student_session_id,$id,$receipt->receipt_date);
        
        
        // if ($userdata["id"] == 1) {
            
        // }
        // else
        // {
        //     $fee_balance = $this->studentfeemaster_model->get_student_balance_up_recid($student_session_id,$id);
        // }

        $data['receipt_session_id']     = $receipt->session_id;
        $data['receipt_date']           = date('d-m-Y', strtotime($receipt->receipt_date));
        $data['receipt_session']        = $this->session_model->get($receipt->session_id);
        //$fee_master_id                  = $this->input->post('fee_master_id');
        //$fee_groups_feetype_id          = $this->input->post('fee_groups_feetype_id');
        $data['paidamt']                = $receipt->gross_amount;
        $data['total_amt']              = $receipt->net_amt;
        $data['total_paid']             = $this->input->post('total_paid');
        $data['total_balance']          = $fee_balance; //$receipt->total_balance;
        $data['total_prev_balance']     = $fee_balance_previous; //$receipt->total_balance;
        $data['discount_amt']           = $this->input->post('discount_amt');
        $data['payment_id']             = $id;

        $setting_result                 = $this->setting_model->get();
        $data['settinglist']            = $setting_result[0];
        $student                        = $this->studentsession_model->searchStudentsBySession($student_session_id);
        //echo "<pre>";
        //print_r($data);die();
        $data['receipt_session']        = $this->session_model->get($student['session_id']);

        
        
        $fee_record                     = $this->studentfeemaster_model->getFeeByInvoicereturn($invoice_id, $sub_invoice_id, $student_session_id);

        $data['fees_array']             = $this->studentfeemaster_model->getStudentFees($student_session_id);
        $data['previousfees']           = $this->studentfeemaster_model->getPreviousStudentFees($student_session_id);

        $data['amount_details']         = $this->studentfeemaster_model->getamountdetails($sub_invoice_id);
        $data['student']                = $student;
        $data['sub_invoice_id']         = $sub_invoice_id;
        $data['feeList']                = $fee_record;
        $data['sch_setting']            = $this->sch_setting_detail;
        //$remain_amount_object           = $this->getStuFeetypeBalance($fee_groups_feetype_id, $fee_master_id);
        //$data['remain_amount']          = json_decode($remain_amount_object)->balance;
        $data['session']                = $this->setting_model->getCurrentSession();
        if($rec_session_id > 0)
        {$data['billing_session']        = $this->session_model->get($rec_session_id);}
        else
        {$data['billing_session']        = $this->session_model->get($this->setting_model->getCurrentSession());}

        $data['st_session']             = $data['receipt_session']['session'];

        if($ids=='')
        {$this->load->view('print/printFeesByNameNew_previous', $data);}    
        else
        {
            $data['billing_session']        = $this->session_model->get($this->setting_model->getCurrentSession()); 
            $print_record  = $this->load->view('print/printFeesByNameNew_previous', $data, true);
            $data['invoice'] = $print_record;
            $data['source'] = 'parent';
            $this->load->view('print/FeesPrint', $data);            
        }
    }


    public function general_receipt($student_session_id)
    {
        // ini_set ('display_errors', 1); ini_set ('display_startup_errors', 1); error_reporting (E_ALL);        
        $data['sch_setting']  = $this->sch_setting_detail;
        $data['student_session_id']  = $student_session_id;
        $data['title']        = 'Student General Receipt';
        $student              = $this->student_model->getByStudentSession($student_session_id);
        $data['student']      = $student;
        
        $data['resultlist']   = $this->studentfee_model->getCollectFee($student_session_id);
        $student_sessionlist  = $this->student_model->get_studentsessionlist($student['id']);

        $data['current_session']      = $this->setting_model->getCurrentSession();
        $category                     = $this->category_model->get();
        $data['categorylist']         = $category;

        $class_section                = $this->student_model->getClassSection($student["class_id"]);
        $data["class_section"]        = $class_section;
        $session                      = $this->setting_model->getCurrentSession();
        $studentlistbysection         = $this->student_model->getStudentClassSection($student["class_id"], $session);
        $data["studentlistbysection"] = $studentlistbysection;
        $data['userdata']             = $this->customlib->getUserData();
        $data['class_teacher']        = $this->staff_model->get_class_teacher_data($student_session_id);
        $feetype = $this->feetype_model->get();
        $data['feestypeList'] = $feetype;
        
        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/general_receipt', $data);
        $this->load->view('layout/footer', $data);
    }

    public function general_receipt_valid()
    {
        $current_session                      = $this->setting_model->getCurrentSession();
        $staff_record = $this->session->userdata('admin');
        $this->form_validation->set_rules('date', 'date', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $msg = array(
                'date' => form_error('date'),
            );
            $jsonarray = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            
            
            $fees_type_id = $this->input->post('fees_type_id');
            if (!empty($fees_type_id)) {
                $source = 'counter-general';
                $j=0;
                $totalAmt = 0;
                foreach ($fees_type_id as  $value) {
                    $totalAmt += $this->input->post('amt')[$j];
                    $j++;
                }                
                if($this->input->post('payment_mode_fee')=="gateway")
                {
                    $post_array = $this->input->post();
                    $post_array_json = json_encode($post_array);
                    $array_data = array(
                        'session_id' => $this->current_ay_session,
                        'student_session_id' => $this->input->post('student_session_id'),
                        'amount' => $totalAmt,
                        'trn_data' => $post_array_json,
                        'session_data' => json_encode($this->session->userdata()),
                        'trn_status' => 'pending',
                        "source" => $source,
                        'note' => $this->input->post('fee_gupcollected_note'),
                    );
                    $result=$this->studentfee_model->add_online_cart($array_data);
                    if(!empty($result))
                    {
                        $jsonarray = array('status' => 'success' , 'error' => '', 'mode' => 'gateway', 'hash_code' => $result['hash_code'] );
                    }                
                }elseif($this->input->post('payment_mode_fee')=="Cheque")
                {
                    $userdata           = $this->customlib->getUserData();
                    $st_session_id = $this->input->post('student_session_id');
                    $class_details = $this->db->query("select class_id,section_id from student_session where id = '$st_session_id'")->row_array();
                    $insert_array = array(
                        'class_id' => $class_details['class_id'],
                        'section_id' => $class_details['section_id'],
                        'student_session_id' => $this->input->post('student_session_id'),
                        'session_id' => $this->current_ay_session,
                        'chq_type' => "student",
                        'chq_no' => $this->input->post('cheque_no'),
                        'chq_date' => date('Y-m-d', strtotime($this->input->post('cheque_date'))),
                        'chq_bank' => $this->input->post('cheque_bank'),
                        'chq_branch' => '',
                        'chq_amt' => $totalAmt,
                        'chq_status' => "collected",
                        'contact_no' => '',
                        'remarks' => $this->input->post('fee_gupcollected_note'),
                        'created_by' =>  $userdata['name'],
                    );   
                    $record_id = $this->feemaster_model->addCheque($insert_array);
                    $post_array = $this->input->post();
                    $post_array_json = json_encode($post_array);
                    $chqnote = "Chq.No.-".$this->input->post('cheque_no')."- Dt: ".$this->input->post('cheque_date')." Bank : ".$this->input->post('cheque_bank');
                    $array_data = array(
                        'session_id' => $this->current_ay_session,
                        'student_session_id' => $this->input->post('student_session_id'),
                        'amount' => $totalAmt,
                        'trn_data' => $post_array_json,
                        'session_data' => json_encode($this->session->userdata()),
                        'trn_status' => 'pending',
                        "source" => 'counter-general',
                        "payment_mode" => 'cheque',
                        "chequeid" => $record_id,
                        'note' => $chqnote . " " . $this->input->post('fee_gupcollected_note'),
                    );
                    $result=$this->studentfee_model->add_online_cart($array_data);
                    $student_session_id=$this->input->post('student_session_id');
                    $data['student']                = $this->studentsession_model->searchStudentsBySession($student_session_id);                
                    $st_session_id = $data['student']['session_id'];
                    $data['student_session']        = $this->session_model->get($st_session_id);
                    $data['chq'] = $insert_array;
                    $setting_result         = $this->setting_model->get();
                    $data['settinglist']    = $setting_result[0];
                    $data['sch_setting']    = $this->sch_setting_detail;                
                    $data['session']        = $this->setting_model->getCurrentSession();    
                    $data['created_by']     =$this->customlib->getAdminSessionUserName();
                    $data['created_at']     = date('d-m-Y h:m:s');
                    $data['created_date']     = date('d-m-Y');
                    $data['source']         = "direct";
                    
                    $print_record  = $this->load->view('print/cheque_receipt', $data, true);                
                    if(!empty($result))
                    {
                        $jsonarray = array('status' => 'success', 'error' => '', 'mode' => 'cheque', 'print' => $print_record );
                    }
                }
                else
                {
                
                $student_session_id = $this->input->post('student_session_id');
                $student                = $this->studentsession_model->searchStudentsBySession($student_session_id);
                $arrayUpdate = array(
                    'receipt_date'              => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                    'gross_amount'              => $totalAmt,
                    'discount'                  => 0,
                    'fine'                      => 0,
                    'net_amt'                   => $totalAmt,
                    'total_balance'             => 0,
                    'prev_balance'              => 0,                            
                    'student_session_id'        => $student_session_id,
                    'session_id'                => $student['session_id'],
                    'created_by'                => $this->customlib->getAdminSessionUserName(),
                    'created_id'                => $staff_record['id'],
                    'note'                      => $this->input->post('fee_gupcollected_note'),
                    'payment_mode'              => $this->input->post('payment_mode_fee'),
                    'receipt_type'              => 'general',
                );
                
                $receipt_id = $this->studentfee_model->addreceipt($arrayUpdate);
                
                $i=0;
                foreach ($fees_type_id as  $value) {
                    $fee_type = $value;
                    $array = [
                        'id' => $this->input->post('id')[$i],
                        'date' => date('Y-m-d',strtotime($this->input->post('date'))),
                        'receipt_id' => $receipt_id,
                        'session_id' => $current_session,
                        'student_session_id' => $student_session_id,
                        'fees_type_id' => $value,
                        'amt' => $this->input->post('amt')[$i],
                        'description' => $this->input->post('description')[$i],
                        'payment_mode' => $this->input->post('payment_mode_fee'),
                        //'remarks'=>$this->input->post('remarks'),
                    ];
                    $this->studentfee_model->addCollectFee($array);
                    $i++;
                }
            
                $arrayUpdate = array(
                    'rec_date'                => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                    'student_session_id'      => $student_session_id,
                    'receipt_id'              => $receipt_id,
                    'receipt_session'         => $this->current_ay_session,
                    'fee_type'                => $fee_type,
                    'description'             => $this->input->post('fee_gupcollected_note'),
                    'amount'                  => $totalAmt,
                );
                $this->studentfee_model->add_student_fine_collection($arrayUpdate);
                $del_id = rtrim($this->input->post('all_sub_id'),',');
                if($del_id!=''){
                    $del_id_arr = explode(',',$del_id);
                    $this->studentfee_model->removeCollectfee($del_id_arr);
                }
                $setting_result         = $this->setting_model->get();
                $data['settinglist']    = $setting_result[0];
                $student_session_id = $this->input->post('student_session_id');
                $data['receipt_data']           = $this->studentfee_model->getReceipt($receipt_id,$this->current_ay_session);
                $data['receipt_session']        = $this->session_model->get($this->current_ay_session);
                if($data['receipt_data']->chequeid>0)
                {$data['cheque'] = $this->feemaster_model->getCheque_only($data['receipt_data']->chequeid);}
                else
                {$data['cheque'] = array();}  
                $data['student']  = $student    = $this->studentsession_model->searchStudentsBySession($student_session_id);
                $data['fees_receipt_array']     = $this->studentfee_model->getStudentFeesReceiptSub($receipt_id);
                $data['sch_setting']            = $this->sch_setting_detail;
                $data['session']                = $this->setting_model->getCurrentSession();
                $print_record  = $this->load->view('print/printFeesGeneral', $data, true);
                $msg   = $this->lang->line('success_message');
                $jsonarray = array('status' => 'success', 'error' => '', 'message' => $msg ,'print' => $print_record);
                }
            }
        }
        echo json_encode($jsonarray);
    }

    public function printFeesGeneral($receipt_id=0,$session_id="")
    {
        //ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);         
        if($receipt_id==0)
        {
            $receipt_id = $_POST['id'];
            $session_id = $_POST['recsessionid'];
        }
        if($session_id=="")
        {   
            $table_name = "fee_receipt_no_".$this->setting_model->getCurrentSession();
            $session_id = $this->setting_model->getCurrentSession();
        }
        else
        {$table_name = "fee_receipt_no_".$session_id;}
        $data['receipt_data'] = $this->db->query("select * from ".$table_name." where id = '".$receipt_id."'")->row_array();

        if(!empty($data['receipt_data']))
        {
            if($data['receipt_data']['receipt_type']=='general')
            {
                $student_ac_yr_session_id = $data['receipt_data']['session_id'];
                $setting_result         = $this->setting_model->get();
                $data['settinglist']    = $setting_result[0];
                $data['sch_setting']    = $this->sch_setting_detail;
                $data['session']        = $this->setting_model->getCurrentSession();
                $data['fees_receipt_array']  = $fees_receipt_array            =  $this->studentfee_model->getStudentFeesReceiptSub($receipt_id);
                $student_session_id          = $fees_receipt_array[0]['student_session_id'];
                $data['student']             = $student    = $this->studentsession_model->searchStudentsBySession($student_session_id);
                $data['receipt_data']           = $this->studentfee_model->getReceipt($receipt_id,$session_id);
                $data['receipt_session']        = $this->session_model->get($session_id);
                $data['student_session']        = $this->session_model->get($student_ac_yr_session_id);
                if($data['receipt_data']->chequeid>0)
                {$data['cheque'] = $this->feemaster_model->getCheque_only($data['receipt_data']->chequeid);}
                else
                {$data['cheque'] = array();}  

                $print_record = $this->load->view('print/printFeesGeneral', $data,true);   
                $data['invoice'] = $print_record;
                $data['source'] = 'parent';
                $this->load->view('print/FeesPrint', $data);                 
            }
            else
            {}
        }
        else
        {

        }
    }
    public function addgeneral_gateway_submit($hash_code)
    {
        $staff_record = $this->session->userdata('admin');
        $result=$this->onlinestudent_model->get_online_record($hash_code);
        if(!empty($result))
        {
            $chequeid = $result['chequeid'];
            if($result['trn_status']=='pending')
            {
                $online_id = $result['id'];
                $post_array = (array) json_decode($result['trn_data'],true);
            }
            elseif($result['receipt_id']!='')
            {
                redirect('studentfee/printFeesGeneral/'.$result['receipt_id']);
            }
        }
        else
        {$online_id = -1;}
        if($result['payment_mode']=='cheque')
        {}  
        else
        {$chequeid = -1;$cheqDate = date('Y-m-d');}
        $cheqDate = date('Y-m-d'); 
        $post_array['collected_date'] = $cheqDate;   
        $fees_type_id = $post_array['fees_type_id'];
        $j=0;
        $totalAmt = 0;
        foreach ($fees_type_id as  $value) {
            $totalAmt += $post_array['amt'][$j];
            $j++;
        }                
        $student_session_id = $post_array['student_session_id'];
        $student                = $this->studentsession_model->searchStudentsBySession($student_session_id);
        $arrayUpdate = array(
            'receipt_date'              => date('Y-m-d', $this->customlib->datetostrtotime($post_array['collected_date'])),
            'gross_amount'              => $totalAmt,
            'discount'                  => 0,
            'fine'                      => 0,
            'net_amt'                   => $totalAmt,
            'total_balance'             => 0,
            'prev_balance'              => 0,                            
            'student_session_id'        => $student_session_id,
            'session_id'                => $student['session_id'],
            'created_by'                => $this->customlib->getAdminSessionUserName(),
            'created_id'                => $staff_record['id'],
            'note'                      => $post_array['fee_gupcollected_note'],
            'payment_mode'              => $post_array['payment_mode_fee'],
            'receipt_type'              => 'general',
        );
        
        $receipt_id = $this->studentfee_model->addreceipt($arrayUpdate);
        
        $i=0;
        foreach ($fees_type_id as  $value) {
            $fee_type = $value;
            $array = [
                'id'=> $post_array['id'][$i],
                'date'=> date('Y-m-d',strtotime($post_array['collected_date'])),
                'receipt_id'=> $receipt_id,
                'session_id'=>$current_session,
                'student_session_id'=>$student_session_id,
                'fees_type_id'=>$value,
                'amt'=>$post_array['amt'][$i],
                'description'=>$post_array['description'][$i],
                'payment_mode'=>$post_array['payment_mode_fee'],
                //'remarks'=>$this->input->post('remarks'),
            ];
            $this->studentfee_model->addCollectFee($array);
            $i++;
        }
    
        $arrayUpdate = array(
            'rec_date'                => date('Y-m-d', $this->customlib->datetostrtotime($post_array['collected_date'])),
            'student_session_id'      => $student_session_id,
            'receipt_id'              => $receipt_id,
            'receipt_session'         => $this->current_ay_session,
            'fee_type'                => $fee_type,
            'description'             => $post_array['fee_gupcollected_note'],
            'amount'                  => $totalAmt,
        );
        $this->studentfee_model->add_student_fine_collection($arrayUpdate);
        $del_id = rtrim($post_array['all_sub_id'],',');
        if($del_id!=''){
            $del_id_arr = explode(',',$del_id);
            $this->studentfee_model->removeCollectfee($del_id_arr);
        }
        $setting_result         = $this->setting_model->get();
        $data['settinglist']    = $setting_result[0];
        $student_session_id = $post_array['student_session_id'];
        $data['receipt_data']           = $this->studentfee_model->getReceipt($receipt_id,$this->current_ay_session);
        $data['receipt_session']        = $this->session_model->get($this->current_ay_session);
        if($data['receipt_data']->chequeid>0)
        {$data['cheque'] = $this->feemaster_model->getCheque_only($data['receipt_data']->chequeid);}
        else
        {$data['cheque'] = array();}  
        $data['student']  = $student    = $this->studentsession_model->searchStudentsBySession($student_session_id);
        $data['fees_receipt_array']     = $this->studentfee_model->getStudentFeesReceiptSub($receipt_id);
        $data['sch_setting']            = $this->sch_setting_detail;
        $data['session']                = $this->setting_model->getCurrentSession();
        $data_rec = array(
            "receipt_id" => $receipt_id,
            'pass_date' => date('Y-m-d', strtotime($cheqDate)),
            'trn_status' => 'passed',                
        );
        $this->db->where('id',$online_id);
        $this->db->update('online_transaction',$data_rec);     
        ///whatsapp section
        $st_session = $this->session_model->get($data['session']);
        $data['st_session'] = $st_session['session'];        
        $mobno = $this->staff_model->get_class_teacher($student_session_id);
        $st_name = strtoupper($data['student']['firstname']." ".$data['student']['middlename']." ".$data['student']['lastname']);
        $class_div = $data['student']['code']."-".$data['student']['section']."[".$st_session['session']."]";
        if($mobno)
        {
            $data_msg1 = array(
                "mobno" => $mobno,
                "name" => $st_name,
                "class" => $class_div,
                "amount" => $totalAmt,
                "rec_no" => $receipt_id,
                "rec_date" => date('d-m-Y', $this->customlib->datetostrtotime($post_array['collected_date']))
            );
            $this->wati_model->send_receipt_to_class_teacher($data_msg1);                
        }
        if($student['mobileno']!='')
        {
            $data_msg = array(
                "mobno" => $student['mobileno'],
                "name" => $st_name,
                "class" => $class_div,
                "amount" => $totalAmt,
                "rec_no" => $receipt_id,
                "rec_date" => date('d-m-Y', $this->customlib->datetostrtotime($post_array['collected_date']))
            );
            //$this->wati_model->send_receipt_to_parent($data_msg);              
        }    
        ////
        $print_record  = $this->load->view('print/printFeesGeneral', $data, true);
        $this->load->view('print/printFeesGeneral', $data);
    }
    public function greater_than_or($amount)
    {
        if (!is_numeric($amount) || $amount < 0) {
            $this->form_validation->set_message('greater_than_or', 'Amount Must Be greater than Zero');
            return false;
        }
        return true;
    }

    public function reportdailycollection()
    {
        if (!$this->rbac->hasPrivilege('fees_statement', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/reportdailycollection');
        $data          = array();
        $data['title'] = 'Daily Collection Report';
        $this->form_validation->set_rules('date_from', $this->lang->line('date_from'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('date_to', $this->lang->line('date_to'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == true) {

            $date_from      = $data['date_from']    = $this->input->post('date_from');
            $date_to        = $data['date_to']    = $this->input->post('date_to');
            $formated_date_from = strtotime($this->customlib->dateFormatToYYYYMMDD($date_from));
            $formated_date_to   = strtotime($this->customlib->dateFormatToYYYYMMDD($date_to));
            $st_fees            = $this->studentfeemaster_model->getCurrentSessionStudentFees();
            $fees_data = array();

            for ($i = $formated_date_from; $i <= $formated_date_to; $i += 86400) {
                $fees_data[$i]['amt'] = 0;
                $fees_data[$i]['count'] = 0;
                $fees_data[$i]['student_fees_deposite_ids'] = array();
            }

            if (!empty($st_fees)) {
                foreach ($st_fees as $fee_key => $fee_value) {
                    if (isJSON($fee_value->amount_detail)) {

                        $fees_details = (json_decode($fee_value->amount_detail));
                        if (!empty($fees_details)) {
                            foreach ($fees_details as $fees_detail_key => $fees_detail_value) {
                                $date = strtotime($fees_detail_value->date);
                                if ($date >= $formated_date_from && $date <= $formated_date_to) {
                                    if (array_key_exists($date, $fees_data)) {
                                        $fees_data[$date]['amt'] += $fees_detail_value->amount + $fees_detail_value->amount_fine;
                                        $fees_data[$date]['count'] += 1;
                                        $fees_data[$date]['student_fees_deposite_ids'][] = $fee_value->student_fees_deposite_id;
                                    } else {
                                        $fees_data[$date]['amt'] = $fees_detail_value->amount + $fees_detail_value->amount_fine;
                                        $fees_data[$date]['count'] = 1;
                                        $fees_data[$date]['student_fees_deposite_ids'][] = $fee_value->student_fees_deposite_id;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $data['fees_data'] = $fees_data;
        }

        $this->load->view('layout/header', $data);
        $this->load->view('reports/reportdailycollection', $data);
        $this->load->view('layout/footer', $data);
    }

    public function reportduefees()
    {
        if (!$this->rbac->hasPrivilege('fees_statement', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/reportduefees');
        $data                = array();
        $data['title']       = 'student fees';
        $class               = $this->class_model->get();
        $data['classlist']   = $class;
        $data['sch_setting'] = $this->sch_setting_detail;
        if ($this->input->server('REQUEST_METHOD') == "POST") {
            $prev_due           = $this->input->post('prev_due');
            $data['prev_due']   = $prev_due;

            $date               = date('Y-m-d');
            $class_id           = $this->input->post('class_id');
            $section_id         = $this->input->post('section_id');
            $data['class_id']   = $class_id;
            $data['section_id'] = $section_id;
            $fees_dues          = $this->studentfeemaster_model->getStudentDueFeeTypesByDate($date, $class_id, $section_id);

            $students_list      = array();



            if (!empty($fees_dues)) {
                foreach ($fees_dues as $fee_due_key => $fee_due_value) {

                    $amount_paid = 0;

                    if (isJSON($fee_due_value->amount_detail)) {
                        $student_fees_array = json_decode($fee_due_value->amount_detail);
                        foreach ($student_fees_array as $fee_paid_key => $fee_paid_value) {
                            $amount_paid += ($fee_paid_value->amount + $fee_paid_value->amount_discount);
                        }
                    }
                    if ($amount_paid < $fee_due_value->fee_amount || ($amount_paid < $fee_due_value->amount && $fee_due_value->is_system)) {
                        $students_list[$fee_due_value->student_session_id]['admission_no']             = $fee_due_value->admission_no;
                        $students_list[$fee_due_value->student_session_id]['roll_no']                  = $fee_due_value->roll_no;
                        $students_list[$fee_due_value->student_session_id]['admission_date']           = $fee_due_value->admission_date;
                        $students_list[$fee_due_value->student_session_id]['firstname']                = $fee_due_value->firstname;
                        $students_list[$fee_due_value->student_session_id]['middlename']               = $fee_due_value->middlename;
                        $students_list[$fee_due_value->student_session_id]['lastname']                 = $fee_due_value->lastname;
                        $students_list[$fee_due_value->student_session_id]['father_name']              = $fee_due_value->father_name;
                        $students_list[$fee_due_value->student_session_id]['image']                    = $fee_due_value->image;
                        $students_list[$fee_due_value->student_session_id]['mobileno']                 = $fee_due_value->mobileno;
                        $students_list[$fee_due_value->student_session_id]['email']                    = $fee_due_value->email;
                        $students_list[$fee_due_value->student_session_id]['state']                    = $fee_due_value->state;
                        $students_list[$fee_due_value->student_session_id]['city']                     = $fee_due_value->city;
                        $students_list[$fee_due_value->student_session_id]['pincode']                  = $fee_due_value->pincode;
                        $students_list[$fee_due_value->student_session_id]['class']                    = $fee_due_value->class;
                        $students_list[$fee_due_value->student_session_id]['section']                  = $fee_due_value->section;
                        $students_list[$fee_due_value->student_session_id]['fee_groups_feetype_ids'][] = $fee_due_value->fee_groups_feetype_id;
                    }
                }
                // exit();
            }

            if (!empty($students_list)) {
                $student              = $this->student_model->getByStudentSession($fee_due_value->student_session_id);
                $student_sessionlist  = $this->student_model->get_studentsessionlist($student['id']);
                // echo "<pre>";
                // print_r($students_list);
                foreach ($students_list as $student_key => $student_value) {

                    $students_list[$student_key]['fees_list'] = $this->studentfeemaster_model->studentDepositByFeeGroupFeeTypeArray($student_key, $student_value['fee_groups_feetype_ids']);
                }
            }

            $data['student_due_fee'] = $students_list;
        }

        $this->load->view('layout/header', $data);
        $this->load->view('reports/reportduefees', $data);
        $this->load->view('layout/footer', $data);
    }

    public function printreportduefees()
    {

        $data                = array();
        $data['title']       = 'student fees';
        $class               = $this->class_model->get();
        $data['classlist']   = $class;
        $data['sch_setting'] = $this->sch_setting_detail;
        $date                = date('Y-m-d');
        $class_id            = $this->input->post('class_id');
        $section_id          = $this->input->post('section_id');
        $data['class_id']    = $class_id;
        $data['section_id']  = $section_id;
        $fees_dues           = $this->studentfeemaster_model->getStudentDueFeeTypesByDate($date, $class_id, $section_id);
        $students_list       = array();

        if (!empty($fees_dues)) {
            foreach ($fees_dues as $fee_due_key => $fee_due_value) {
                $amount_paid = 0;

                if (isJSON($fee_due_value->amount_detail)) {
                    $student_fees_array = json_decode($fee_due_value->amount_detail);
                    foreach ($student_fees_array as $fee_paid_key => $fee_paid_value) {
                        $amount_paid += ($fee_paid_value->amount + $fee_paid_value->amount_discount);
                    }
                }
                if ($amount_paid < $fee_due_value->fee_amount) {
                    $students_list[$fee_due_value->student_session_id]['admission_no']             = $fee_due_value->admission_no;
                    $students_list[$fee_due_value->student_session_id]['roll_no']                  = $fee_due_value->roll_no;
                    $students_list[$fee_due_value->student_session_id]['admission_date']           = $fee_due_value->admission_date;
                    $students_list[$fee_due_value->student_session_id]['firstname']                = $fee_due_value->firstname;
                    $students_list[$fee_due_value->student_session_id]['middlename']               = $fee_due_value->middlename;
                    $students_list[$fee_due_value->student_session_id]['lastname']                 = $fee_due_value->lastname;
                    $students_list[$fee_due_value->student_session_id]['father_name']              = $fee_due_value->father_name;
                    $students_list[$fee_due_value->student_session_id]['image']                    = $fee_due_value->image;
                    $students_list[$fee_due_value->student_session_id]['mobileno']                 = $fee_due_value->mobileno;
                    $students_list[$fee_due_value->student_session_id]['email']                    = $fee_due_value->email;
                    $students_list[$fee_due_value->student_session_id]['state']                    = $fee_due_value->state;
                    $students_list[$fee_due_value->student_session_id]['city']                     = $fee_due_value->city;
                    $students_list[$fee_due_value->student_session_id]['pincode']                  = $fee_due_value->pincode;
                    $students_list[$fee_due_value->student_session_id]['class']                    = $fee_due_value->class;
                    $students_list[$fee_due_value->student_session_id]['section']                  = $fee_due_value->section;
                    $students_list[$fee_due_value->student_session_id]['fee_groups_feetype_ids'][] = $fee_due_value->fee_groups_feetype_id;
                }
            }
        }

        if (!empty($students_list)) {
            foreach ($students_list as $student_key => $student_value) {
                $students_list[$student_key]['fees_list'] = $this->studentfeemaster_model->studentDepositByFeeGroupFeeTypeArray($student_key, $student_value['fee_groups_feetype_ids']);
            }
        }
        $data['student_due_fee'] = $students_list;
        $page                    = $this->load->view('reports/_printreportduefees', $data, true);
        echo json_encode(array('status' => 1, 'page' => $page));
    }

    public function feeCollectionStudentDeposit()
    {

        $data = array();
        $date = $this->input->post('date');
        $fees_id = $this->input->post('fees_id');
        $fees_id_array = explode(',', $fees_id);
        $fees_list = $this->studentfeemaster_model->getFeesDepositeByIdArray($fees_id_array);
        $data['student_list'] = $fees_list;
        $data['date'] = $date;
        $data['sch_setting']  = $this->sch_setting_detail;
        $page = $this->load->view('reports/_feeCollectionStudentDeposit', $data, true);
        echo json_encode(array('status' => 1, 'page' => $page));
    }

    public function other_fees()
    {
        if (!$this->rbac->hasPrivilege('other_fees', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', $this->lang->line('fees_collection'));
        $this->session->set_userdata('sub_menu', 'studentfee/other_fees');
        $data['sch_setting'] = $this->sch_setting_detail;
        $data['title']       = 'student fees';
        $class               = $this->class_model->get();
        $data['classlist']   = $class;
        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/student_otherfeeSearch', $data);
        $this->load->view('layout/footer', $data);
    }

    public function ajaxSearchfees()
    {
        $class       = $this->input->post('class_id');
        $section     = $this->input->post('section_id');
        $search_text = $this->input->post('search_text');
        $search_type = $this->input->post('search_type');
        if ($search_type == "class_search") {
            $students = $this->student_model->getDatatableByClassSection($class, $section);
        } elseif ($search_type == "keyword_search") {
            $students = $this->student_model->getDatatableByFullTextSearch($search_text);
        }
        $sch_setting = $this->sch_setting_detail;
        $students = json_decode($students);
        $dt_data  = array();
        if (!empty($students->data)) {
            foreach ($students->data as $student_key => $student) {
                $row   = array();
                $row[] = $student->class;
                $row[] = $student->section;
                $row[] = $student->roll_no;
                $row[] = $student->admission_no;
                $row[] = "<a href='" . base_url() . "student/view/" . $student->id . "'>" . $this->customlib->getFullName($student->firstname, $student->middlename, $student->lastname, $sch_setting->middlename, $sch_setting->lastname) . "</a>";
                $sch_setting = $this->sch_setting_detail;
                if ($sch_setting->father_name) {
                    $row[] = $student->father_name;
                }
                // $row[] = $this->customlib->dateformat($student->dob);
                $row[] = $student->guardian_phone;
                $row[] = "<a href=" . site_url('studentfee/addotherfee/' . $student->student_session_id) . "  class='btn btn-info btn-xs'>" . $this->lang->line('collect_fees') . "</a>";

                $dt_data[] = $row;
            }
        }
        $json_data = array(
            "draw"            => intval($students->draw),
            "recordsTotal"    => intval($students->recordsTotal),
            "recordsFiltered" => intval($students->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    public function addotherfee($id)
    {
        if (!$this->rbac->hasPrivilege('other_fees', 'can_add')) {
            access_denied();
        }
        $data['sch_setting']  = $this->sch_setting_detail;
        $data['title']        = 'Student Detail';
        $student              = $this->student_model->getByStudentSession($id);
        $data['student']      = $student;

        $student_sessionlist  = $this->student_model->get_studentsessionlist($student['id']);
        $student_due_fee = [];
        $student_discount_fee = [];
        $student_session_id = [];
        foreach ($student_sessionlist as $key => $value) {

            $id =  $value['id'];

            $student_session_id[] = $id;
            $student_due_fee[]      = $this->studentfeemaster_model->getStudentFees($id);
            $student_discount_fee[] = $this->feediscount_model->getStudentFeesDiscount($id);
        }
        $data['student_discount_fee'] = $student_discount_fee;
        $data['student_due_fee']      = $student_due_fee;
        $data['student_session_id']   = $student_session_id;

        $data['current_session']      = $this->setting_model->getCurrentSession();
        $category                     = $this->category_model->get();
        $data['categorylist']         = $category;
        $class_section                = $this->student_model->getClassSection($student["class_id"]);
        $data["class_section"]        = $class_section;
        $session                      = $this->setting_model->getCurrentSession();
        $studentlistbysection         = $this->student_model->getStudentClassSection($student["class_id"], $session);
        $data["studentlistbysection"] = $studentlistbysection;
        $data['userdata'] = $this->customlib->getUserData();
        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/studentAddotherfee', $data);
        $this->load->view('layout/footer', $data);
    }

    public function bus_fees()
    {
        if (!$this->rbac->hasPrivilege('bus_fees', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', $this->lang->line('fees_collection'));
        $this->session->set_userdata('sub_menu', 'studentfee/bus_fees');
        $data['sch_setting'] = $this->sch_setting_detail;
        $data['title']       = 'student fees';
        $class               = $this->class_model->get();
        $data['classlist']   = $class;
        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/student_busfeeSearch', $data);
        $this->load->view('layout/footer', $data);
    }

    public function ajaxBusfees()
    {
        $class       = $this->input->post('class_id');
        $section     = $this->input->post('section_id');
        $search_text = $this->input->post('search_text');
        $search_type = $this->input->post('search_type');
        if ($search_type == "class_search") {
            $students = $this->student_model->getDatatableByClassSection($class, $section);
        } elseif ($search_type == "keyword_search") {
            $students = $this->student_model->getDatatableByFullTextSearch($search_text);
        }
        $sch_setting = $this->sch_setting_detail;
        $students = json_decode($students);
        $dt_data  = array();
        if (!empty($students->data)) {
            foreach ($students->data as $student_key => $student) {
                $row   = array();
                $row[] = $student->class;
                $row[] = $student->section;
                $row[] = $student->roll_no;
                $row[] = $student->admission_no;
                $row[] = "<a href='" . base_url() . "student/view/" . $student->id . "'>" . $this->customlib->getFullName($student->firstname, $student->middlename, $student->lastname, $sch_setting->middlename, $sch_setting->lastname) . "</a>";
                $sch_setting = $this->sch_setting_detail;
                if ($sch_setting->father_name) {
                    $row[] = $student->father_name;
                }
                // $row[] = $this->customlib->dateformat($student->dob);
                $row[] = $student->guardian_phone;
                $row[] = "<a href=" . site_url('studentfee/addBusfee/' . $student->student_session_id) . "  class='btn btn-info btn-xs'>" . $this->lang->line('collect_fees') . "</a>";

                $dt_data[] = $row;
            }
        }
        $json_data = array(
            "draw"            => intval($students->draw),
            "recordsTotal"    => intval($students->recordsTotal),
            "recordsFiltered" => intval($students->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    public function addBusfee($id)
    {
        if (!$this->rbac->hasPrivilege('bus_fees', 'can_add')) {
            access_denied();
        }
        $data['sch_setting']  = $this->sch_setting_detail;
        $data['title']        = 'Student Detail';
        $student              = $this->student_model->getByStudentSession($id);
        $data['student']      = $student;

        $student_sessionlist  = $this->student_model->get_studentsessionlist($student['id']);
        $student_due_fee = [];
        $student_discount_fee = [];
        $student_session_id = [];
        foreach ($student_sessionlist as $key => $value) {

            $id =  $value['id'];

            $student_session_id[] = $id;
            $student_due_fee[]      = $this->studentfeemaster_model->getStudentFees($id);
            $student_discount_fee[] = $this->feediscount_model->getStudentFeesDiscount($id);
        }
        $data['student_discount_fee'] = $student_discount_fee;
        $data['student_due_fee']      = $student_due_fee;
        $data['student_session_id'] = $student_session_id;

        $data['current_session']                      = $this->setting_model->getCurrentSession();
        $category                     = $this->category_model->get();
        $data['categorylist']         = $category;
        $class_section                = $this->student_model->getClassSection($student["class_id"]);
        $data["class_section"]        = $class_section;
        $session                      = $this->setting_model->getCurrentSession();
        $studentlistbysection         = $this->student_model->getStudentClassSection($student["class_id"], $session);
        $data["studentlistbysection"] = $studentlistbysection;
        $data['userdata'] = $this->customlib->getUserData();

        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/studentAddBusfee', $data);
        $this->load->view('layout/footer', $data);
    }

    public function class_wise_fees()
    {
        if (!$this->rbac->hasPrivilege('class_wise_fees', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'studentfee/class_wise_fees');
        $data['title']       = 'student fees';
        $class               = $this->class_model->get();
        $data['classlist']   = $class;
        $data['sch_setting'] = $this->sch_setting_detail;
        $feesessiongroup     = $this->feesessiongroup_model->getFeesByGroup();
        $data['feesessiongrouplist'] = $feesessiongroup;
        $data['class_section_list'] = $this->classsection_model->getClassSectionStudentCountall();
        $data['fees_group']          = "";
        if (isset($_POST['feegroup_id']) && $_POST['feegroup_id'] != '') {
            $data['fees_group'] = $_POST['feegroup_id'];
        }

        $this->form_validation->set_rules('feegroup_id', $this->lang->line('fee_group'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('studentfee/class_wise_fees', $data);
            $this->load->view('layout/footer', $data);
        } else {



            $data['student_due_fee'] = $student_due_fee;
            $this->load->view('layout/header', $data);
            $this->load->view('studentfee/class_wise_fees', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function deleteassignfee()
    {

        if (!$this->rbac->hasPrivilege('deleteassignfees', 'can_delete')) {
            access_denied();
        }
        $student_session_id  = $this->input->post('student_session_id');
        $student_fees_master_id = $this->input->post('student_fees_master_id');

        if (!empty($student_session_id)) {

            $this->studentfee_model->remove_discount($student_session_id, $student_fees_master_id);
        }
        $array = array('status' => 'success', 'result' => 'success');
        echo json_encode($array);
    }

    public function classfees($class_id, $section_id =null)
    {
        if (!$this->rbac->hasPrivilege('class_wise_fees', 'can_add')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'studentfee/class_wise_fees');
        $data['sch_setting']        = $this->sch_setting_detail;
        $data['title']              = 'Student Detail';
        $data['class_id']           = $class_id;
        $data['section_id']         = $section_id;
        $data['current_session']    = $this->setting_model->getCurrentSession();

        $data['fees_array']         = $this->feegrouptype_model->getclassfess($class_id)->result_array();

        $session                    = $this->setting_model->getCurrentSession();
        $data['userdata']           = $this->customlib->getUserData();

        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/classfees', $data);
        $this->load->view('layout/footer', $data);
    }

    public function getfeemaster()
    {

        $data['feegroup_id']                = $this->input->post('feegroup_id');
        $data['class_id']                   = $this->input->post('class_id');
        $data['section_id']                 = $this->input->post('section_id');

        $feegroup_result = $this->feesessiongroup_model->getFeesByGroup();
        $data['feemasterList'] = $feegroup_result;

        $result   = $this->load->view('studentfee/getfeemaster', $data, true);

        $data = array('status' => '1', 'error' => '', 'page' => $result);
        echo json_encode($data);
    }

    public function classfeevalid()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('fees_group_id', 'fees group', 'trim|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'fees_group_id' => form_error('fees_group_id'),
            );
            $array = array('status' => 0, 'error' => $data);
            echo json_encode($array);
        } else {
            $session                      = $this->setting_model->getCurrentSession();
            $fees_group_id = $this->input->post('fees_group_id[]');

            if (!empty($fees_group_id)) {
                $i = 0;
                foreach ($fees_group_id as $key => $fees_group_value) {

                    $array = array(
                        'class_id'                               => $this->input->post('class_id'),
                        // 'section_id'                             => $this->input->post('section_id'),
                        'fees_group_id'                          => $fees_group_value,
                        'session_id'                             => $session,
                    );


                    // echo "<pre>";
                    // print_r ($array);
                    // echo "</pre>";

                    $i++;

                    $this->feegrouptype_model->add_class_fees($array);
                }
            }

            $array = array('status' => '1', 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }

    function removeclassfees($class_id, $feegroup_id)
    {
        $section_id ="";
        if (!$this->rbac->hasPrivilege('fees_master', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Fees Master List';
        $this->feegrouptype_model->removeclassfeesmst($class_id, $section_id, $feegroup_id);
        redirect('studentfee/class_wise_fees');
    }
    public function collection_report_receipt_student($student_session_id)
    {
        if (!$this->rbac->hasPrivilege('collection_report', 'can_view')) {
            access_denied();
        }
        $data['collect_by'] = $this->studentfeemaster_model->get_feesreceived_by2();
        $data['searchlist'] = $this->customlib->get_searchtype();
        $data['group_by']   = $this->customlib->get_groupby();
        $feetype = $this->feetype_model->get();
        $data['feetypeList'] = $feetype;
        $data['sessionlist'] = $this->session_model->get();
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/collection_report_receipt');
        $subtotal = false;

        $collect_by          = array();
        $collection          = array();
        $start_date          = date('Y-m-d', strtotime($dates['from_date']));
        $end_date            = date('Y-m-d', strtotime($dates['to_date']));
        $data['start_date']          = $dates['from_date'];
        $data['end_date']            = $dates['to_date'];
        $data['rec_session_id'] = $this->setting_model->getCurrentSession();
        $data['results'] = $this->studentfeemaster_model->getCollectionReportReceipt_student($student_session_id);
        $data['subtotal'] = 0;
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/collection_report_receipt_student', $data);
        $this->load->view('layout/footer', $data);        
                
    }
    public function collection_report_receipt()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);        
        if (!$this->rbac->hasPrivilege('collection_report', 'can_view')) {
            access_denied();
        }
        $data['collect_by'] = $this->studentfeemaster_model->get_feesreceived_by2();
        $data['searchlist'] = $this->customlib->get_searchtype();
        $data['group_by']   = $this->customlib->get_groupby();
        $feetype = $this->feetype_model->get();
        $data['feetypeList'] = $feetype;
        $data['sessionlist'] = $this->session_model->get();
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/collection_report_receipt');
        $subtotal = false;
        if (isset($_POST['search_type']) && $_POST['search_type'] != '') {
            $dates               = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $_POST['search_type'];
        } else {
            $dates               = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = '';
        }

        if (isset($_POST['collect_by']) && $_POST['collect_by'] != '') {
            $data['received_by'] = $received_by = $_POST['collect_by'];
        } else {
            $data['received_by'] = $received_by = '';
        }

        if (isset($_POST['feetype_id']) && $_POST['feetype_id'] != '') {
            $feetype_id = $_POST['feetype_id'];
        } else {
            $feetype_id = "";
        }

        if (isset($_POST['group']) && $_POST['group'] != '') {
            $data['group_byid'] = $group = $_POST['group'];
            $subtotal = true;
        } else {
            $data['group_byid'] = $group = '';
        }
        if (isset($_POST['payment_mode']) && $_POST['payment_mode'] != '') {
            $data['payment_mode'] = $payment_mode = $_POST['payment_mode'];
        } else {
            $data['payment_mode'] = $payment_mode = '';
        }
        if (isset($_POST['session_id']) && $_POST['session_id'] != '') {
            $data['session_id'] = $session_id = $_POST['session_id'];
        } else {
            $data['session_id'] = $session_id = '';
        }

        $collect_by          = array();
        $collection          = array();
        $start_date          = date('Y-m-d', strtotime($dates['from_date']));
        $end_date            = date('Y-m-d', strtotime($dates['to_date']));
        $data['start_date']          = $dates['from_date'];
        $data['end_date']            = $dates['to_date'];
        $data['rec_session_id'] = $this->setting_model->getCurrentSession();
        $this->form_validation->set_rules('search_type', $this->lang->line('search') . " " . $this->lang->line('type'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $data['results'] = array();
        } else {

           
            $data['results'] = $result = $this->studentfeemaster_model->getCollectionReportReceipt($start_date, $end_date, $feetype_id, $received_by, $group,$payment_mode,$session_id);

            if ($this->input->post('search')=='export') {
                
                $this->collection_report_export($result);
               } 

            // if ($group != '') {

            //     if ($group == 'class') {

            //         $group_by = 'class_id';
            //     } elseif ($group == 'collection') {

            //         $group_by = 'received_by';
            //     } elseif ($group == 'mode') {

            //         $group_by = 'payment_mode';
            //     }

            //     foreach ($data['results'] as $key => $value) {

            //         $collection[$value[$group_by]][] = $value;
            //     }
            // } else {

            //     $s = 0;
            //     foreach ($data['results'] as $key => $value) {

            //         $collection[$s++] = array($value);
            //     }
            // }

            // $data['results'] = $collection;
        }
        $data['subtotal'] = $subtotal;
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/collection_report_receipt', $data);
        $this->load->view('layout/footer', $data);
    }
    public function collectionReportReceipt()
    {
        if (!$this->rbac->hasPrivilege('collectionReportReceipt', 'can_view')) {
            access_denied();
        }
        $data['collect_by'] = $this->studentfeemaster_model->get_feesreceived_by2();
        $data['searchlist'] = $this->customlib->get_searchtype();
        $data['group_by']   = $this->customlib->get_groupby();
        $feetype = $this->feetype_model->get();
        $data['feetypeList'] = $feetype;
        $data['sessionlist'] = $this->session_model->get();
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/collectionReportReceipt');
        $subtotal = false;
        if (isset($_POST['search_type']) && $_POST['search_type'] != '') {
            $dates               = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $_POST['search_type'];
        } else {
            $dates               = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = '';
        }

        if (isset($_POST['collect_by']) && $_POST['collect_by'] != '') {
            $data['received_by'] = $received_by = $_POST['collect_by'];
        } else {
            $data['received_by'] = $received_by = '';
        }

        if (isset($_POST['feetype_id']) && $_POST['feetype_id'] != '') {
            $feetype_id = $_POST['feetype_id'];
        } else {
            $feetype_id = "";
        }

        if (isset($_POST['group']) && $_POST['group'] != '') {
            $data['group_byid'] = $group = $_POST['group'];
            $subtotal = true;
        } else {
            $data['group_byid'] = $group = '';
        }
        if (isset($_POST['payment_mode']) && $_POST['payment_mode'] != '') {
            $data['payment_mode'] = $payment_mode = $_POST['payment_mode'];
        } else {
            $data['payment_mode'] = $payment_mode = '';
        }
        if (isset($_POST['session_id']) && $_POST['session_id'] != '') {
            $data['session_id'] = $session_id = $_POST['session_id'];
        } else {
            $data['session_id'] = $session_id = '';
        }

        $collect_by          = array();
        $collection          = array();
        $start_date          = date('Y-m-d', strtotime($dates['from_date']));
        $end_date            = date('Y-m-d', strtotime($dates['to_date']));
        $data['start_date']          = $dates['from_date'];
        $data['end_date']            = $dates['to_date'];
        $data['rec_session_id'] = $this->setting_model->getCurrentSession();

        $this->form_validation->set_rules('search_type', $this->lang->line('search') . " " . $this->lang->line('type'), 'trim|required|xss_clean');


        if ($this->form_validation->run() == false) {
            $data['results'] = array();
        } else {

            $data['results'] = $this->studentfeemaster_model->getCollectionReportEntry($start_date, $end_date, $feetype_id, $received_by, $group,$payment_mode,$session_id);

            
        }
        $data['subtotal'] = $subtotal;
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/collectionReportReceipt', $data);
        $this->load->view('layout/footer', $data);
    }

    public function collection_report_old()
    {
        if (!$this->rbac->hasPrivilege('collection_report', 'can_view')) {
            access_denied();
        }

        $data['collect_by'] = $this->studentfeemaster_model->get_feesreceived_by2();
        $data['searchlist'] = $this->customlib->get_searchtype();
        $data['group_by']   = $this->customlib->get_groupby();
        $feetype = $this->feetype_model->get();
        $data['feetypeList'] = $feetype;
        $data['sessionlist'] = $this->session_model->get();
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/collection_report_old');
        $subtotal = false;
        if (isset($_POST['search_type']) && $_POST['search_type'] != '') {
            $dates               = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $_POST['search_type'];
        } else {
            $dates               = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = '';
        }

        if (isset($_POST['collect_by']) && $_POST['collect_by'] != '') {
            $data['received_by'] = $received_by = $_POST['collect_by'];
        } else {
            $data['received_by'] = $received_by = '';
        }

        if (isset($_POST['feetype_id']) && $_POST['feetype_id'] != '') {
            $feetype_id = $_POST['feetype_id'];
        } else {
            $feetype_id = "";
        }

        if (isset($_POST['group']) && $_POST['group'] != '') {
            $data['group_byid'] = $group = $_POST['group'];
            $subtotal = true;
        } else {
            $data['group_byid'] = $group = '';
        }
        if (isset($_POST['payment_mode']) && $_POST['payment_mode'] != '') {
            $data['payment_mode'] = $payment_mode = $_POST['payment_mode'];
        } else {
            $data['payment_mode'] = $payment_mode = '';
        }
        if (isset($_POST['session_id']) && $_POST['session_id'] != '') {
            $data['session_id'] = $session_id = $_POST['session_id'];
        } else {
            $data['session_id'] = $session_id = '';
        }

        $collect_by          = array();
        $collection          = array();
        $start_date          = date('Y-m-d', strtotime($dates['from_date']));
        $end_date            = date('Y-m-d', strtotime($dates['to_date']));
        $data['start_date']          = $dates['from_date'];
        $data['end_date']            = $dates['to_date'];
        $data['rec_session_id'] = $this->setting_model->getCurrentSession();
        $this->form_validation->set_rules('search_type', $this->lang->line('search') . " " . $this->lang->line('type'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $data['results'] = array();
        } else {
            $data['results'] = $this->studentfeemaster_model->getCollectionReportReceipt_old($start_date, $end_date, $feetype_id, $received_by, $group,$payment_mode,$session_id);

            // if ($group != '') {

            //     if ($group == 'class') {

            //         $group_by = 'class_id';
            //     } elseif ($group == 'collection') {

            //         $group_by = 'received_by';
            //     } elseif ($group == 'mode') {

            //         $group_by = 'payment_mode';
            //     }

            //     foreach ($data['results'] as $key => $value) {

            //         $collection[$value[$group_by]][] = $value;
            //     }
            // } else {

            //     $s = 0;
            //     foreach ($data['results'] as $key => $value) {

            //         $collection[$s++] = array($value);
            //     }
            // }

            // $data['results'] = $collection;
        }
        $data['subtotal'] = $subtotal;
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/collection_report_old', $data);
        $this->load->view('layout/footer', $data);
    }

    public function student_penalty()
    {

        if (!$this->rbac->hasPrivilege('student_penalty', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'studentfee/student_penalty');
        $userdata              = $this->customlib->getUserData();
        $data["staff_id"]      = $userdata["id"];
        // $leave_request         = $this->leaverequest_model->user_leave_request($userdata["id"]);
        // $data["leave_request"] = $leave_request;
        // $LeaveTypes            = $this->leaverequest_model->allotedLeaveType($userdata["id"]);
        // $data["leavetype"]     = $LeaveTypes;
        $staffRole             = $this->staff_model->getStaffRole();
        $data["staffrole"]     = $staffRole;
        $student_penaltylist             = $this->studentfee_model->getpenaltylist();
        $data["student_penaltylist"]     = $student_penaltylist;
        // $data["status"]        = $this->status;

        $this->load->view("layout/header", $data);
        $this->load->view("studentfee/student_penalty", $data);
        $this->load->view("layout/footer", $data);
    }

    public function add_penalty()
    {
        if (!$this->rbac->hasPrivilege('add_penalty', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', $this->lang->line('fees_collection'));
        $this->session->set_userdata('sub_menu', 'studentfee/student_penalty');
        $data['sch_setting'] = $this->sch_setting_detail;
        $data['title']       = 'student fees';
        $class               = $this->class_model->get();
        $data['classlist']   = $class;
        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/add_penalty', $data);
        $this->load->view('layout/footer', $data);
    }

    public function ajaxpenalty()
    {
        $class       = $this->input->post('class_id');
        $section     = $this->input->post('section_id');
        $search_text = $this->input->post('search_text');
        $search_type = $this->input->post('search_type');
        if ($search_type == "class_search") {
            $students = $this->student_model->getDatatableByClassSection($class, $section);
        } elseif ($search_type == "keyword_search") {
            $students = $this->student_model->getDatatableByFullTextSearch($search_text);
        }
        $sch_setting = $this->sch_setting_detail;
        $students = json_decode($students);
        $dt_data  = array();
        if (!empty($students->data)) {
            foreach ($students->data as $student_key => $student) {
                $row   = array();
                $row[] = $student->class;
                $row[] = $student->section;
                $row[] = $student->roll_no;
                $row[] = $student->admission_no;
                $row[] = "<a href='" . base_url() . "student/view/" . $student->id . "'>" . $this->customlib->getFullName($student->firstname, $student->middlename, $student->lastname, $sch_setting->middlename, $sch_setting->lastname) . "</a>";
                $sch_setting = $this->sch_setting_detail;
                if ($sch_setting->father_name) {
                    $row[] = $student->father_name;
                }
                // $row[] = $this->customlib->dateformat($student->dob);
                $row[] = $student->guardian_phone;
                $row[] = "<a href=" . site_url('studentfee/create_penalty/' . $student->student_session_id) . "  class='btn btn-info btn-xs'>" . $this->lang->line('add') . " Penalty</a>";

                $dt_data[] = $row;
            }
        }
        $json_data = array(
            "draw"            => intval($students->draw),
            "recordsTotal"    => intval($students->recordsTotal),
            "recordsFiltered" => intval($students->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    public function create_penalty($student_session_id)
    {
        $student              = $this->student_model->getByStudentSession($student_session_id);
        $data['student']      = $student;
        $section_result      = $this->section_model->get();
        $data['sectionlist'] = $section_result;
        $data['student_session_id'] = $student_session_id;

        $data['current_session'] = $session_id    = $this->setting_model->getCurrentSession();
        $category                     = $this->category_model->get();
        $data['categorylist']         = $category;
        $data['sch_setting']  = $this->sch_setting_detail;
        $data['userdata'] = $userdata          = $this->customlib->getUserData();

        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/create_penalty', $data);
        $this->load->view('layout/footer', $data);
    }

    public function penalty_update()
    {
        $student_session_id = $this->input->post('student_session_id');
        $student              = $this->student_model->getByStudentSession($student_session_id);
        $data['student']      = $student;
        $section_result      = $this->section_model->get();
        $data['sectionlist'] = $section_result;
        $data['student_session_id'] = $student_session_id;

        $data['current_session'] = $session_id    = $this->setting_model->getCurrentSession();
        $category                     = $this->category_model->get();
        $data['categorylist']         = $category;
        $data['sch_setting']  = $this->sch_setting_detail;
        $data['userdata'] = $userdata          = $this->customlib->getUserData();

        $this->form_validation->set_rules('fee_date', 'Fee Date', 'required|trim|xss_clean');
        $this->form_validation->set_rules('fee_type', 'Fee Type', 'required|trim|xss_clean');
        if ($this->form_validation->run() == false) {

            $json = array(
                "error" => true,
                'fee_date' => form_error('fee_date', '<p>', '</p>'),
                'fee_type' => form_error('fee_type', '<p>', '</p>'),
            );

            // $this->load->view('layout/header', $data);
            // $this->load->view('studentfee/create_penalty', $data);
            // $this->load->view('layout/footer', $data);
        } else {
        $student_session = $this->studentsession_model->getSessionById($student_session_id);

        $receipt_id = $this->studentfeemaster_model->create_receipt();

        $dataArray = array(
            'student_id' => $student_session->student_id,
            'student_session_id' => $student_session_id,
            'session_id' => $student_session->session_id,
            'fee_date' => date('Y-m-d',strtotime($this->input->post('fee_date'))),
            'fee_type' => $this->input->post('fee_type'),
            'subject' => $this->input->post('subject'),
            'description' => $this->input->post('description'),
            'fine' => $this->input->post('fine'),
            'created_at' => date('Y-m-d h:i:s'),
            'created_by' => $userdata['name'],
            'receipt_id' => $receipt_id,
        );

        $this->studentfee_model->add_penalty($dataArray);

        $setting_result        = $this->setting_model->get();
        $data['settinglist']   = $setting_result[0];
        $data['sch_setting']   = $this->sch_setting_detail;
        $data['session'] = $this->setting_model->getCurrentSession();

        $data['student'] = $this->student_model->getByStudentSession($student_session_id);
        $student_penaltylist              = $this->studentfee_model->getpenaltylist($student_session_id);
        $data['penaltylist']      = $student_penaltylist;

        $dataview = $this->load->view('studentfee/penalty_print', $data,TRUE);

        $json = array(
            "success" => "Data Updates Successfully!!!!",
            "response" => $dataview 
        );
        // $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
        // redirect('studentfee/student_penalty');
        }
        echo json_encode($json);
    }

    public function edit_penalty($student_session_id)
    {
        $student_penaltylist              = $this->studentfee_model->getpenaltylist($student_session_id);
        $data['penaltylist']      = $student_penaltylist;
        $student              = $this->student_model->getByStudentSession($student_session_id);
        $data['student']      = $student;
        $section_result      = $this->section_model->get();
        $data['sectionlist'] = $section_result;
        $data['student_session_id'] = $student_session_id;

        $data['current_session'] = $session_id    = $this->setting_model->getCurrentSession();
        $category                     = $this->category_model->get();
        $data['categorylist']         = $category;
        $data['sch_setting']  = $this->sch_setting_detail;
        $data['userdata'] = $userdata          = $this->customlib->getUserData();

        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/edit_penalty', $data);
        $this->load->view('layout/footer', $data);
    }

    public function delete_penalty($id)
    {
        $this->studentfee_model->remove_penalty($id);
        redirect('studentfee/student_penalty');
    }

    public function print_penaltyfee()
    {
        $data['student_session_id'] = $student_session_id = $this->input->post('student_session_id');
        $setting_result        = $this->setting_model->get();
        $data['settinglist']   = $setting_result[0];
        $data['sch_setting']   = $this->sch_setting_detail;
        $data['session'] = $this->setting_model->getCurrentSession();

        $data['student'] = $this->student_model->getByStudentSession($student_session_id);
        $student_penaltylist              = $this->studentfee_model->getpenaltylist($student_session_id);
        $data['penaltylist']      = $student_penaltylist;

        // echo "<pre>";
        // print_r($data['session']);

        $this->load->view('studentfee/penalty_print', $data);
    }
    public function update_fees_master_session_id()
    {
        $query=$this->db->query("select * from student_fees_master")->result_array();
        foreach($query as $fees)
        {
            $fee_session_id = $fees['session_id'];
            $fees_id=$fees['id'];
            $st_s_id = $fees['student_session_id'];

            
            $res = $this->db->query("select session_id from student_session where id = '$st_s_id'")->row_array();
            if(!empty($res))
            {
                $student_session_id = $res['session_id'];
                if($student_session_id!=$fee_session_id)
                {
                    if($student_session_id>0)
                    {
                        echo "<br>".$fee_session_id."--".$st_s_id."--".$student_session_id;            
                        $this->db->query("update student_fees_master set session_id = '$student_session_id' where id = '$fees_id' ");
                    }
                }
            }
        }
    }

    public function student_refund()
    {
        if (!$this->rbac->hasPrivilege('student_refund', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', $this->lang->line('fees_collection'));
        $this->session->set_userdata('sub_menu', 'studentfee/student_refund');
        $data['sch_setting'] = $this->sch_setting_detail;
        $data['title']       = 'student fees';
        $class               = $this->class_model->get();
        $data['classlist']   = $class;
        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/studentrefundSearch', $data);
        $this->load->view('layout/footer', $data);
    }

    public function ajaxrefundSearch()
    {
        $class       = $this->input->post('class_id');
        $section     = $this->input->post('section_id');
        $search_text = $this->input->post('search_text');
        $search_type = $this->input->post('search_type');
        if ($search_type == "class_search") {
            $students = $this->student_model->getDatatableByClassSection($class, $section);
        } elseif ($search_type == "keyword_search") {
            $students = $this->student_model->getDatatableByFullTextSearch($search_text);
        }
        $sch_setting = $this->sch_setting_detail;
        $students = json_decode($students);
        $dt_data  = array();
        if (!empty($students->data)) {
            foreach ($students->data as $student_key => $student) {
                $row   = array();
                $row[] = $student->class;
                $row[] = $student->section;
                $row[] = $student->roll_no;
                $row[] = $student->admission_no;
                $row[] = "<a target='_blank'  href='" . base_url() . "student/view/" . $student->id . "'>" . $this->customlib->getFullName($student->firstname, $student->middlename, $student->lastname, $sch_setting->middlename, $sch_setting->lastname) . "</a>";
                $sch_setting = $this->sch_setting_detail;
                if ($sch_setting->father_name) {
                    $row[] = $student->father_name;
                }
                // $row[] = $this->customlib->dateformat($student->dob);
                $row[] = $student->guardian_phone;
                $row[] = "<a target='_blank' href=" . site_url('studentfee/refundfee/' . $student->student_session_id) . "  class='btn btn-info btn-xs'>Collect Refund</a>";

                $dt_data[] = $row;
            }
        }
        $json_data = array(
            "draw"            => intval($students->draw),
            "recordsTotal"    => intval($students->recordsTotal),
            "recordsFiltered" => intval($students->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    public function refundfee($id)
    {
        if (!$this->rbac->hasPrivilege('refundfee', 'can_add')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', $this->lang->line('fees_collection'));
        $this->session->set_userdata('sub_menu', 'studentfee/student_refund');
        $data['sch_setting']  = $this->sch_setting_detail;
        $data['title']        = 'Student Detail';
        $student              = $this->student_model->getByStudentSession($id);
        $data['student']      = $student;

        $student_sessionlist  = $this->student_model->get_studentsessionlist($student['id']);
        $student_due_fee = [];
        $student_discount_fee = [];
        $student_session_id = [];
        
        foreach ($student_sessionlist as $key => $value) {
            $id =  $value['id'];
            $student_session_id[] = $id;
            $student_due_fee[]          = $this->studentfeemaster_model->getStudentFees($id);
            
            $student_discount_fee[]     = $this->feediscount_model->getStudentFeesDiscount($id);
        }
        
     
        
        $data['student_discount_fee'] = $student_discount_fee;
        $data['student_due_fee']      = $student_due_fee;
        $data['student_session_id']   = $student_session_id;

        $data['current_session']      = $this->setting_model->getCurrentSession();
        $category                     = $this->category_model->get();
        $data['categorylist']         = $category;
        $class_section                = $this->student_model->getClassSection($student["class_id"]);
        $data["class_section"]        = $class_section;
        $session                      = $this->setting_model->getCurrentSession();
        $studentlistbysection         = $this->student_model->getStudentClassSection($student["class_id"], $session);
        $data["studentlistbysection"] = $studentlistbysection;
        $data['userdata']             = $this->customlib->getUserData();
        
        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/studentAddRefundfee', $data);
        $this->load->view('layout/footer', $data);
    }

    public function getcollectfeeRefund()
    {
        $setting_result                  = $this->setting_model->get();
        $data['settinglist']             = $setting_result;
        $data['student_session_id']      = $this->input->post('student_session_id');
        $data['total_main_fees']         = $this->input->post('total_main_fees');
        $data['total_other_fees']        = $this->input->post('total_other_fees');
        $data['total_balance_fees']      = $this->input->post('total_balance_fees');
        $data['total_paid']              = $this->input->post('total_paid');
        $data['enable_auto_disc']        = $this->input->post('enable_auto_disc');
        $record                          = $this->input->post('data');
        $record_array                    = json_decode($record);
        $fees_array = array();
        $selected_amt = 0;
        foreach ($record_array as $key => $value) {
            $fee_groups_feetype_id = $value->fee_groups_feetype_id;
            $fee_master_id         = $value->fee_master_id;
            $fee_session_group_id  = $value->fee_session_group_id;
            $feeList               = $this->studentfeemaster_model->getDueFeeByFeeSessionGroupFeetype($fee_session_group_id, $fee_master_id, $fee_groups_feetype_id);
            $fees_array[]          = $feeList;
            $selected_amt += $feeList->amount;
        }
        if(!empty($fees_array))
        {
            foreach($fees_array as $fees)
            {
                $st_session_ids[] = $fees->student_session_id;
                $fee_deposits = json_decode(($fees->amount_detail));
                if(!empty($fee_deposits))
                {
                    foreach($fee_deposits as $fee)
                    {
                        $selected_amt = $selected_amt - ($fee->amount + $fee->amount_discount);
                    }
                }
            }
        }
        
        if($selected_amt == $data['total_balance_fees'])
        {$data['ot_enabled']=1;}
        else
        {$data['ot_enabled']=0;}

        $data['feearray'] = $fees_array;
        $data['selected_amt'] = $selected_amt;
        if (!empty($fees_array)) {
            $data['discount_not_applied']   = $this->feediscount_model->getDiscountNotApplieddropdown($fees_array[0]->student_session_id);
        }
        $data['ot_disc']   = $this->feediscount_model->getOneTimeDiscount();
        $result           = array(
            'view' => $this->load->view('studentfee/getcollectfeeRefund', $data, true),
        );
        $this->output->set_output(json_encode($result));
    }

    public function refundSave()
    {
        $staff_record = $this->session->userdata('admin');
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('collected_date', $this->lang->line('date'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('total_paid_fees', "total_paid_fees", 'required|trim|xss_clean');
        $this->form_validation->set_rules('refund_amt', "Refund Amount", 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'collected_date' => form_error('collected_date'),
                'total_paid_fees' => form_error('total_paid_fees'),
                'refund_amt' => form_error('refund_amt'),
            );
            $array = array('status' => 0, 'error' => $data);
            echo json_encode($array);
        } else {
            $session_id = $this->setting_model->getCurrentSession();
            $arrayData = array(
                'session_id'            => $session_id,
                'student_seesion_id'    => $this->input->post('student_session_id'),
                'date'                  => date('Y-m-d',strtotime($this->input->post('collected_date'))),
                'total_collected_amt'   => $this->input->post('total_paid_fees'),
                'refund_amt'            => $this->input->post('refund_amt'),
                'remarks'               => $this->input->post('remarks'),
                'status'                => 1,
            );
            
            $this->studentfeemaster_model->addRefund($arrayData);

            $array = array('status' => 1, 'error' => '');
            echo json_encode($array);
            
        }
    }
    public function test_now()
    {
        echo "<pre>11";
        print_r($this->studentfee_model->get_previous_student_fees(1569));
    }
    public function getdues($student_session_id)
    {
        $netpay = 0;
        $netdeposit = 0;
        $netdisc = 0;
        $netbalance = 0;
        $total_Payinstall1 = 0;
        $total_Payinstall2 = 0;
        $total_Payinstall3 = 0;
        $total_Paidinstall1 = 0;
        $total_Paidinstall2 = 0;
        $total_Paidinstall3 = 0;
        $total_disc1 = 0;
        $total_disc2 = 0;
        $total_disc3 = 0;
        $total_Balinstall1 = 0;
        $total_Balinstall2 = 0;
        $total_Balinstall3 = 0;
        $student_cnt=0;

                $student_total_fees = array();
                $student_total_fees[] = $this->studentfeemaster_model->getStudentFees_main($student_session_id);
                echo "<pre>";
                print_r($student_total_fees);die();
                if (!empty($student_total_fees)) {
                    $deposit = 0;
                    $discount = 0;
                    $balance = 0;
                    $fine = 0;

                    $inst_payamount = array(0, 0, 0, 0);
                    $inst_paidamount = array(0, 0, 0, 0);
                    $inst_discamount = array(0, 0, 0, 0);
                    $inst_fineamount = array(0, 0, 0, 0);
                    $inst_balamount = array(0, 0, 0, 0);
                    $rw = 0;
                    $totalfee = 0;
                    $deposit = 0;
                    $balance = 0;
                    $discount = 0;
                    //echo "<pre>";
                    //print_r($student_total_fees);

                    foreach ($student_total_fees as $student_total_fees_key => $student_total_fees_value) {
                        $inst_payamount[$rw] = 0;
                        $inst_paidamount[$rw] = 0;
                        $inst_balamount[$rw] = 0;
                        $inst_discamount[$rw] = 0;

                        foreach ($student_total_fees_value as $ff) {
                            $fees = $ff->fees;

                            //print_r($fees);

                            if (!empty($fees)) {
                                $late_adm_disc = 0;
                                foreach ($fees as $key => $each_fee_value) {
                                    //print_r($each_fee_value);
                                    //echo $each_fee_value->amount."--";
                                    $inst_payamount[$rw] += $each_fee_value->amount;
                                    $amount_detail = json_decode($each_fee_value->amount_detail);
                                    if (is_object($amount_detail)) {
                                        $paid_amt_loop = 0;
                                        $paid_disc=0;
                                        $late_adm_disc = 0;
                                        foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                                            if($amount_detail_value->date <= $asondate) {
                                            $inst_paidamount[$rw] +=  $amount_detail_value->amount;
                                            $paid_amt_loop += $amount_detail_value->amount;
                                            $inst_fineamount[$rw] +=  $amount_detail_value->amount_fine;
                                            if(isset($amount_detail_value->discount_id))
                                            {
                                                if($amount_detail_value->discount_id==7)
                                                {
                                                    $late_adm_disc = $amount_detail_value->amount_discount;
                                                }
                                                else
                                                {
                                                    $inst_discamount[$rw] +=  $amount_detail_value->amount_discount;
                                                    $paid_disc += $amount_detail_value->amount_discount;    
                                                }
                                            }
                                            else
                                            {
                                                $inst_discamount[$rw] +=  $amount_detail_value->amount_discount;
                                                $paid_disc += $amount_detail_value->amount_discount;
                                            }
                                            }
                                        }

                                        $inst_balamount[$rw] += ($each_fee_value->amount - ($paid_amt_loop + $paid_disc + $late_adm_disc));
                                        //$inst_discamount[$rw] += $paid_disc;
                                        $totalfee += ($each_fee_value->amount-$late_adm_disc);
                                        $deposit += ($paid_amt_loop + $paid_disc );
                                        $discount += $paid_disc;
                                    } else {
                                        //echo "<br>".$inst_balamount[$rw]."-".$inst_payamount[$rw];
                                        $inst_balamount[$rw] += ($each_fee_value->amount);
                                        $totalfee += $each_fee_value->amount;
                                        //$deposit += 0;
                                        //$balance = $totalfee - $deposit;

                                    }
                                    $inst_payamount[$rw] -= $late_adm_disc;
                                    
                                    //echo $inst_balamount[$rw] . "-";



                                }
                            }
                            if ($student['session_is_active']  == "no") {
                                $inst_payamount[$rw]= $inst_paidamount[$rw];
                                $inst_balamount[$rw]=0;
                            }
                            ++$rw;
                        }
                        if ($student['session_is_active']  == "no") {
                        $balance=0;
                        }
                        else
                        {$balance = $totalfee - $deposit;}
                    }
                    $total_Payinstall1  += $inst_payamount[0];
                    $total_Payinstall2  += $inst_payamount[1];
                    $total_Payinstall3  += $inst_payamount[2];
                    $total_Paidinstall1 += !empty($inst_paidamount[0]) ? $inst_paidamount[0] : "0";
                    $total_Paidinstall2 += !empty($inst_paidamount[1]) ? $inst_paidamount[1] : "0";
                    $total_Paidinstall3 += !empty($inst_paidamount[2]) ? $inst_paidamount[2] : "0";

                    $total_disc1 += !empty($inst_discamount[0]) ? $inst_discamount[0] : "0";
                    $total_disc2 += !empty($inst_discamount[1]) ? $inst_discamount[1] : "0";
                    $total_disc3 += !empty($inst_discamount[2]) ? $inst_discamount[2] : "0";


                    $total_Balinstall1  += !empty($inst_balamount[0]) ? $inst_balamount[0] : "0";
                    $total_Balinstall2  += !empty($inst_balamount[1]) ? $inst_balamount[1] : "0";
                    $total_Balinstall3  += !empty($inst_balamount[2]) ? $inst_balamount[2] : "0";

                    $totalfee   =  ( $inst_payamount[0]+ $inst_payamount[1]+ $inst_payamount[2]);
                    $deposit    =  ($inst_paidamount[0] + $inst_paidamount[1] + $inst_paidamount[2]);
                    $discount   =   ($inst_discamount[0]+$inst_discamount[1]+$inst_discamount[2]);
                    $balance    =   ($inst_balamount[0]+$inst_balamount[1]+$inst_balamount[2]);

                    $netpay += ( $inst_payamount[0]+ $inst_payamount[1]+ $inst_payamount[2]);
                    $netdeposit += ($inst_paidamount[0] + $inst_paidamount[1] + $inst_paidamount[2]);
                    $netdisc += ($inst_discamount[0]+$inst_discamount[1]+$inst_discamount[2]);
                    $netbalance += ($inst_balamount[0]+$inst_balamount[1]+$inst_balamount[2]);
                }

    }
    public function update_fee_deposite($id)
    {
        //echo "hhhh";
        // ini_set ('display_errors', 1); 
        // ini_set ('display_startup_errors', 1); 
        // error_reporting (E_ALL);        
        //$this->studentfee_model->update_fee_deposite();
        //$this->studentfee_model->display_all_non_session_json();
        $this->studentfee_model->create_fee_receipt_from_deposite($id);
        //$this->studentfee_model->display_all_non_session_json();

    }  
    public function test()
    {
        echo "Test";
    }

    public function collectionReportPassDate()
    {
        if (!$this->rbac->hasPrivilege('collectionReportPassDate', 'can_view')) {
            access_denied();
        }

        $data['collect_by'] = $this->studentfeemaster_model->get_feesreceived_by2();
        $data['searchlist'] = $this->customlib->get_searchtype();
        $data['group_by']   = $this->customlib->get_groupby();
        $feetype = $this->feetype_model->get();
        $data['feetypeList'] = $feetype;
        $data['sessionlist'] = $this->session_model->get();
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/collectionReportPassDate');
        $subtotal = false;
        if (isset($_POST['search_type']) && $_POST['search_type'] != '') {
            $dates               = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $_POST['search_type'];
        } else {
            $dates               = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = '';
        }

        if (isset($_POST['collect_by']) && $_POST['collect_by'] != '') {
            $data['received_by'] = $received_by = $_POST['collect_by'];
        } else {
            $data['received_by'] = $received_by = '';
        }

        if (isset($_POST['feetype_id']) && $_POST['feetype_id'] != '') {
            $feetype_id = $_POST['feetype_id'];
        } else {
            $feetype_id = "";
        }

        if (isset($_POST['group']) && $_POST['group'] != '') {
            $data['group_byid'] = $group = $_POST['group'];
            $subtotal = true;
        } else {
            $data['group_byid'] = $group = '';
        }
        if (isset($_POST['payment_mode']) && $_POST['payment_mode'] != '') {
            $data['payment_mode'] = $payment_mode = $_POST['payment_mode'];
        } else {
            $data['payment_mode'] = $payment_mode = '';
        }
        if (isset($_POST['session_id']) && $_POST['session_id'] != '') {
            $data['session_id'] = $session_id = $_POST['session_id'];
        } else {
            $data['session_id'] = $session_id = '';
        }

        $collect_by          = array();
        $collection          = array();
        $start_date          = date('Y-m-d', strtotime($dates['from_date']));
        $end_date            = date('Y-m-d', strtotime($dates['to_date']));
        $data['start_date']          = $dates['from_date'];
        $data['end_date']            = $dates['to_date'];


        $this->form_validation->set_rules('search_type', $this->lang->line('search') . " " . $this->lang->line('type'), 'trim|required|xss_clean');


        if ($this->form_validation->run() == false) {
            $data['results'] = array();
        } else {

            $data['results'] = $this->studentfeemaster_model->getCollectionReportPassDate($start_date, $end_date, $feetype_id, $received_by, $group,$payment_mode,$session_id);

            
        }
        $data['subtotal'] = $subtotal;
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/collectionReportPassDate', $data);
        $this->load->view('layout/footer', $data);
    }

    public function collection_report_export($result)
    {
        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Receipt Id');
		$sheet->setCellValue('B1', 'Date');
		$sheet->setCellValue('C1', 'Name');
		$sheet->setCellValue('D1', 'Class');
		$sheet->setCellValue('E1', 'Fees Type');
		$sheet->setCellValue('F1', 'Collect By');
		$sheet->setCellValue('G1', 'Mode');
		$sheet->setCellValue('H1', 'Status');
		$sheet->setCellValue('I1', 'Gross (Rs.)');
		$sheet->setCellValue('J1', 'Discount (Rs.)');
		$sheet->setCellValue('K1', 'Fine (Rs.)');
		$sheet->setCellValue('L1', 'Net Total (Rs.)');

        
        $cashtotal = 0;
        $banktotal = 0;
        $netcashtotal=0;
        $netbanktotal=0;
        $netcashdisc=0;
        $netbankdisc=0;
        $netcanceltotal=0;

        $gateway_total = 0;
        $net_gateway_total = 0;
        $net_gateway_total_disc = 0;
        $rows = 2;
        if (!empty($result)) {
            $count = 1;
            $grdamountTotal = 0;
            $grddiscountTotal = 0;
            $grdfineTotal = 0;
            $grdTotalTotal = 0;
            $canceltotal = 0;
            foreach ($result as $key => $value) {
                $status ='';

                if ($value->fee_receipt_status == "Cancelled") { $status = " ".$value->cancel_reason; }
                if ($value->fee_receipt_status == "Cancelled") {
                    $canceltotal += $value->gross_amount;
                    $netcanceltotal += $value->net_amt;
                }else{
                    if ($value->payment_mode == 'Cash') {
                        $cashtotal += $value->gross_amount;
                        $netcashtotal += $value->net_amt;
                        $netcashdisc +=$value->discount;
                    } elseif($value->payment_mode == 'gateway')
                    {
                        $gateway_total += $value->gross_amount;
                        $net_gateway_total += $value->net_amt;
                        $net_gateway_total_disc +=$value->discount;                                                        
                    } else {
                        $banktotal += $value->gross_amount;
                        $netbanktotal += $value->net_amt;
                        $netbankdisc +=$value->discount;
                    }                                                    
                    $grdamountTotal += $value->net_amt;
                    $grddiscountTotal += $value->discount;
                    $grdfineTotal += $value->fine;
                    $grdTotalTotal += $value->gross_amount;
                }

                $sheet->setCellValue('A' . $rows, $value->fee_receipt_id);
                $sheet->setCellValue('B' . $rows, date('d-m-Y',strtotime($value->receipt_date)));
                $sheet->setCellValue('C' . $rows, $value->firstname . " " . $value->lastname);
                $sheet->setCellValue('D' . $rows, $value->class . " " . $value->section ."-".$value->roll_no);
                $sheet->setCellValue('E' . $rows, "[" . $value->session . "]");
                $sheet->setCellValue('F' . $rows, $value->created_by);
                $sheet->setCellValue('G' . $rows, $value->payment_mode."  Note: ".$value->note);
                $sheet->setCellValue('H' . $rows, $value->fee_receipt_status.$status);
                
                $sheet->setCellValue('I' . $rows, $value->gross_amount);
                $sheet->setCellValue('J' . $rows, $value->discount);
                $sheet->setCellValue('K' . $rows, $value->fine);
                $sheet->setCellValue('L' . $rows, $value->net_amt);

                $rows++;
            }

            $rows += 3;
            $sheet->setCellValue('G' . $rows, "Grand Total");
            $sheet->setCellValue('I' . $rows, $grdTotalTotal);
            $sheet->setCellValue('J' . $rows, $grddiscountTotal);
            $sheet->setCellValue('K' . $rows, $grdfineTotal);
            $sheet->setCellValue('L' . $rows, $grdamountTotal);
            $rows += 1;
            $sheet->setCellValue('G' . $rows, "Cash Total");
            $sheet->setCellValue('I' . $rows, $cashtotal);
            $sheet->setCellValue('J' . $rows, $netcashdisc);
            $sheet->setCellValue('L' . $rows, $netcashtotal);
            $rows += 1;
            $sheet->setCellValue('G' . $rows, "Gateway Total");
            $sheet->setCellValue('I' . $rows, $gateway_total);
            $sheet->setCellValue('J' . $rows, $net_gateway_total_disc);
            $sheet->setCellValue('L' . $rows, $net_gateway_total);
            $rows += 1;
            $sheet->setCellValue('G' . $rows, "Bank Total");
            $sheet->setCellValue('I' . $rows, $banktotal);
            $sheet->setCellValue('J' . $rows, $netbankdisc);
            $sheet->setCellValue('L' . $rows, $netbanktotal);
            $rows += 1;
            $sheet->setCellValue('G' . $rows, "Cancelled Total");
            $sheet->setCellValue('L' . $rows, $netcanceltotal);
        }

        $writer = new Xlsx($spreadsheet);

		$filename = 'Student Collection Report';

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		
		$writer->save('php://output'); // download file 
    }

    public function assign_students($class_id,$section_id =null)
    {
        //ini_set('display_errors', 1);
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/class_wise_fees');
        $data['title'] = 'student fees';
        $data['sch_setting'] = $this->sch_setting_detail;
        $data['class_id'] = $class_id;
        $data['section_id'] = $section_id;
        $resultlist = $this->studentfeemaster_model->searchAssignFeeByClassSection($class_id, $section_id, "",null, null, null, null);
        $data['resultlist'] = $resultlist;
        $data['fees_array']         = $this->feegrouptype_model->getclassfess($class_id)->result_array();
        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/assign_students', $data);
        $this->load->view('layout/footer', $data);
    }
public function testmsg()
{
        
        $fee_balance = $this->studentfeemaster_model->get_student_balance_up_recid1(2707,2770);     
        echo "Balance : " . $fee_balance;die();
    // $query="SELECT distinct class_id FROM class_fees_mst";
    // $result=$this->db->query($query)->result_array();
    // foreach($result as $row)
    // {
    //     $class_id=$row['class_id'];
    //     $qry="delete from class_fees_mst where class_id = '$class_id' and section_id > 1";
    //     $this->db->query($qry);
    // }
    //     echo "success";
    // $query="alter table class_fees_mst drop column section_id";
    // $this->db->query($query);   
    // echo "Deleted Column Success";    
    // $data_msg1 = array(
    //     "mobno" => '9605252637',
    //     "name" => 'manoj t m',
    //     "class" => 'XA',
    //     "amount" => '10000',
    //     "rec_no" => '128',
    //     "cancel_date" => '22-06-2024'
    // );
    // if($this->wati_model->send_receipt_cancel_to_class_teacher($data_msg1))
    // //if($this->wati_model->send('sss','9605252637') )
    // {
    //     echo "success";
    // } 
    // else
    // {
    //     echo "failed";
    // }
    // $client = new \GuzzleHttp\Client();
    // $response = $client->request('POST', 'https://live-mt-server.wati.io/111987/api/v1/sendTemplateMessage?whatsappNumber=9605252637', [
    //   'body' => '{"template_name":"att_student","broadcast_name":"att_student","parameters":[{"name":"name","value":"manoj"}]}',
    //   'headers' => [
    //     'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiJjZDJjMzc5MS1mMTAwLTRhOGEtOGUwMi1jNGQ5MmE0NGU2YTUiLCJ1bmlxdWVfbmFtZSI6InNuZ2NlbnRyYWxzY2hvb2xAZ21haWwuY29tIiwibmFtZWlkIjoic25nY2VudHJhbHNjaG9vbEBnbWFpbC5jb20iLCJlbWFpbCI6InNuZ2NlbnRyYWxzY2hvb2xAZ21haWwuY29tIiwiYXV0aF90aW1lIjoiMDYvMTIvMjAyNCAwNTo0NjozMSIsImRiX25hbWUiOiJtdC1wcm9kLVRlbmFudHMiLCJ0ZW5hbnRfaWQiOiIxMTE5ODciLCJodHRwOi8vc2NoZW1hcy5taWNyb3NvZnQuY29tL3dzLzIwMDgvMDYvaWRlbnRpdHkvY2xhaW1zL3JvbGUiOiJBRE1JTklTVFJBVE9SIiwiZXhwIjoyNTM0MDIzMDA4MDAsImlzcyI6IkNsYXJlX0FJIiwiYXVkIjoiQ2xhcmVfQUkifQ.4LwJGFe1PEV38uCc22gfvtWdTNxOi58j9xXvZ4uE-rY',
    //     'content-type' => 'application/json-patch+json',
    //   ],
    // ]);
    // echo $response->getBody();    
}
public function msg_test()
{
    echo $this->studentfee_model->get_current_month_fine_collection_amount(2986,date('d-m-Y'));
    
}

}
