<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Busfee extends Admin_Controller
{
    public $search_type;
    public $sch_setting_detail;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');
        $this->search_type        = $this->config->item('search_type');
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->load->model("leaverequest_model");
    }

    public function busCollection()
    {
        if (!$this->rbac->hasPrivilege('busCollection', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', $this->lang->line('fees_collection'));
        $this->session->set_userdata('sub_menu', 'admin/busfee/busCollection');
        $data['sch_setting'] = $this->sch_setting_detail;
        $data['title']       = 'student fees';
        $class               = $this->class_model->get();
        $data['classlist']   = $class;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/feegroup/busCollection', $data);
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
                $row[] = "<a href=" . site_url('admin/busfee/addBusfeeColl/' . $student->student_session_id) . "  class='btn btn-info btn-xs'>" . $this->lang->line('collect_fees') . "</a>";

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

    public function addBusfeeColl($id)
    {
        if (!$this->rbac->hasPrivilege('busCollection', 'can_add')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', $this->lang->line('fees_collection'));
        $this->session->set_userdata('sub_menu', 'admin/busfee/busCollection');
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
        $data['class_teacher']        = $this->staff_model->get_class_teacher_data($id);
        $this->load->view('layout/header', $data);
        $this->load->view('admin/feemaster/busfeeadd', $data);
        $this->load->view('layout/footer', $data);
    }

    public function getcollectfeemodel()
    {
        $setting_result                  = $this->setting_model->get();
        $data['settinglist']             = $setting_result;
        $data['student_session_id']      = $this->input->post('student_session_id');
        $data['total_main_fees']         = $this->input->post('total_main_fees');
        $data['total_other_fees']        = $this->input->post('total_other_fees');
        $data['total_balance_fees']      = $this->input->post('total_balance_fees');
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
        if (!empty($fees_array)) {
            foreach ($fees_array as $fees) {
                $st_session_ids[] = $fees->student_session_id;
                $fee_deposits = json_decode(($fees->amount_detail));
                if (!empty($fee_deposits)) {
                    foreach ($fee_deposits as $fee) {
                        $selected_amt = $selected_amt - ($fee->amount + $fee->amount_discount);
                    }
                }
            }
        }

        if ($selected_amt == $data['total_balance_fees']) {
            $data['ot_enabled'] = 1;
        } else {
            $data['ot_enabled'] = 0;
        }

        $data['feearray'] = $fees_array;
        $data['selected_amt'] = $selected_amt;
        if (!empty($fees_array)) {
            $data['discount_not_applied']   = $this->feediscount_model->getDiscountNotApplieddropdown($fees_array[0]->student_session_id);
        }
        $data['ot_disc']   = $this->feediscount_model->getOneTimeDiscount();
        $result           = array(
            'view' => $this->load->view('admin/feemaster/getcollectfeemodel', $data, true),
        );
        $this->output->set_output(json_encode($result));
    }

    public function addbusfeegrp()
    {
        $this->load->library('pdf');
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
            $collected_array = array();
            $student_fees_discount_id = array();
            $collected_by    = " Collected By: " . $this->customlib->getAdminSessionUserName();

            $send_to            = $this->input->post('guardian_phone');
            $email              = $this->input->post('guardian_email');
            $parent_app_key     = $this->input->post('parent_app_key');
            $paid_amount     = $this->input->post('paid_amount');
            $paid_fine_amount     = $this->input->post('tot_fine_amount');
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
                );
                if (!empty($this->input->post('disc_code_' . $total_row_value))) {
                    $current_session_id = $this->setting_model->getCurrentSession();
                    $this->db->where('student_session_id', $student_fees_master['student_session_id']);
                    $this->db->where('fees_discount_id', $this->input->post('disc_code_' . $total_row_value));
                    $this->db->where('session_id', $current_session_id);
                    $disc_rows = $this->db->get('student_fees_discounts')->row_array();
                    if (empty($disc_rows)) {
                        $insert_array = array(
                            'student_session_id' => $student_fees_master['student_session_id'],
                            'fees_discount_id' => $this->input->post('disc_code_' . $total_row_value),
                            'is_active' => "Yes",
                            'session_id' => $current_session_id,
                        );
                        $student_fees_discount_id[] = $this->feediscount_model->allotdiscount($insert_array);
                    } else {
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

                $gross_amt = $paid_amount + $total_discount - $paid_fine_amount;
                $net_total = $paid_amount;
                $arrayUpdate = array(
                    'id'                        => $sub_invoice_id,
                    'receipt_date'              => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('collected_date'))),
                    'gross_amount'              => $gross_amt,
                    'discount'                  => $total_discount,
                    'fine'                      => $paid_fine_amount,
                    'net_amt'                   => $net_total,
                    'student_session_id'        => $student_session_id,
                    'session_id'                => $this->setting_model->getCurrentSession(),
                    'created_by'                => $this->customlib->getAdminSessionUserName(),
                    'created_id'                => $staff_record['id'],
                    'note'                      => $this->input->post('fee_gupcollected_note'),
                    'payment_mode'              => $this->input->post('payment_mode_fee'),
                );
                $this->studentfeemaster_model->update_receipt($arrayUpdate);
                $mobno = $this->staff_model->get_class_teacher($data['student_session_id']);
                if ($mobno) {
                    $st_name = strtoupper($data['student']['firstname'] . " " . $data['student']['middlename'] . " " . $data['student']['lastname']);
                    $class_div = $data['student']['code'] . "-" . $data['student']['section'];

                    $data = array(
                        "mobno" => $mobno,
                        "name" => $st_name,
                        "class" => $class_div,
                        "amount" => $net_total,
                        "rec_no" => $sub_invoice_id,
                        "rec_date" => date('d-m-Y', $this->customlib->datetostrtotime($this->input->post('collected_date')))
                    );
                    //$this->wati_model->send_receipt_to_class_teacher($data);
                }
                $data['note'] = $this->input->post('fee_gupcollected_note');
                $receipt                        = $this->studentfee_model->getReceipt($sub_invoice_id);
                $data['receipt']                = $receipt;
                if ($print == 1) {
                    $body           = $this->load->view('print/mail_invoice', $data, true);
                    $print_record  = $this->load->view('print/printFeesByNameNew', $data, true);
                } else {
                    $body           = $this->load->view('print/mail_invoice', $data, true);
                }
                if (!empty($email)) {
                    //$this->send_mail($email, 'Fee Submission', $body);
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

    public function addbusstudentfee()
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

            $this->studentfeemaster_model->update_receipt($arrayUpdate);


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
            

            // if (!empty($email)) {
            //     $this->send_mail($email, 'Fee Receipt', $body);
            // }


            $array = array('status' => 'success', 'error' => '', 'print' => $print_record);
            echo json_encode($array);
        }
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

    public function greater_than_or($amount)
    {
        if (!is_numeric($amount) || $amount < 0) {
            $this->form_validation->set_message('greater_than_or', 'Amount Must Be greater than Zero');
            return false;
        }
        return true;
    }

}
