<?php
 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Feediscount extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    function delete($id) {
        $data['title'] = 'feecategory List';
        $this->feediscount_model->remove($id);
        redirect('admin/feediscount/index');
    }

    function index() {
        if (!$this->rbac->hasPrivilege('fees_discount', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feediscount');
        $feesdiscount_result = $this->feediscount_model->get();
        $data['feediscountList'] = $feesdiscount_result;
        $this->form_validation->set_rules('code', $this->lang->line('discount_code'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/feediscount/feediscountList', $data);
            $this->load->view('layout/footer', $data);
        } else {
            if (!empty($this->input->post('date_enabled'))) {
               $date_enabled = $this->input->post('date_enabled');
            } else {
                $date_enabled = 0;
                
            }
            
            $data = array(
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                // 'amount' => $this->input->post('amount'),
                'feepercent' => $this->input->post('feepercent'),
                'fees_type' => $this->input->post('fees_type'),
                'description' => $this->input->post('description'),
                // 'date_enabled' => $date_enabled,
                // 'date_upto' => date('Y-m-d',strtotime($this->input->post('date_upto'))),
            );
            $discount_id = $this->feediscount_model->add($data);

            $data2 = array(
                'discount_id' => $discount_id,
                'amount' => $this->input->post('amount'),
                'feepercent' => $this->input->post('feepercent'),
                'date_enabled' => $date_enabled,
                'date_upto' => date('Y-m-d',strtotime($this->input->post('date_upto'))),
            );
            $this->feediscount_model->addstudentDiscount_session($data2);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/feediscount');
        }
    }

    function fees_discount_approval() {
        if (!$this->rbac->hasPrivilege('fees_discount_approval', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/fees_discount_approval');
        $feesdiscount_result = $this->feediscount_model->get();
        $data['feediscountList'] = $feesdiscount_result;
        $this->form_validation->set_rules('code', $this->lang->line('discount_code'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/feediscount/fees_discount_approval', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'amount' => $this->input->post('amount'),
                'description' => $this->input->post('description'),
            );
            $this->feediscount_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/feediscount');
        }
    }

    function approve($id) {
        if (!$this->rbac->hasPrivilege('fees_discount_approval', 'can_edit')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/fees_discount_approval');
        $feesdiscount_result = $this->feediscount_model->getdiscountlistbyid($id);
        $data['feediscountList'] = $feesdiscount_result;
        $data['title'] = 'Edit feecategory';
        $data['id'] = $id;

        $feediscount = $this->feediscount_model->getdiscountlistbyid($id);
        $data['feediscount'] = $feediscount;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/feediscount/approve', $data);
        $this->load->view('layout/footer', $data);
    }

    public function approveBy()
    {
        $id = $this->input->post('id');
        $discount_check = $this->input->post('check');
        $this->feediscount_model->update_mark_verify($id,$discount_check);
        $json = ['success' => true];
        echo json_encode($json);
    }

   
    function edit($id) {
        if (!$this->rbac->hasPrivilege('fees_discount', 'can_edit')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feediscount');
        $feesdiscount_result = $this->feediscount_model->get();
        $data['feediscountList'] = $feesdiscount_result;
        $data['title'] = 'Edit feecategory';
        $data['id'] = $id;

        $feediscount = $this->feediscount_model->get($id);
        $data['feediscount'] = $feediscount;
        $data['session_id'] = $this->setting_model->getCurrentSession();
        $this->form_validation->set_rules('name', $this->lang->line('category'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/feediscount/feediscountEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            if (!empty($this->input->post('date_enabled'))) {
                $date_enabled = $this->input->post('date_enabled');
             } else {
                 $date_enabled = 0;
                 
             }
            $data = array(
                'id' => $id,
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                // 'amount' => $this->input->post('amount'),
                // 'feepercent' => $this->input->post('feepercent'),
                'fees_type' => $this->input->post('fees_type'),
                'description' => $this->input->post('description'),
                // 'date_enabled' => $this->input->post('date_enabled'),
                // 'date_upto' => date('Y-m-d',strtotime($this->input->post('date_upto'))),
            );
            $this->feediscount_model->add($data);
            $session_id = $this->input->post('current_session');
            $sub_id = $this->feediscount_model->get_studentDiscount_session($session_id,$id);
            
            
            $data2 = array(
                'id' => $sub_id,
                'discount_id' => $id,
                'amount' => $this->input->post('amount'),
                'feepercent' => $this->input->post('feepercent'),
                // 'fees_type' => $this->input->post('fees_type'),
                'date_enabled' => $date_enabled,
                'date_upto' => date('Y-m-d',strtotime($this->input->post('date_upto'))),
            );
            
            $this->feediscount_model->addstudentDiscount_session($data2);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/feediscount/index');
        }
    }

    function assign($id) {
        if (!$this->rbac->hasPrivilege('fees_discount_assign', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feediscount');
        $data['id'] = $id;
        $data['title'] = 'student fees';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $feediscount_result = $this->feediscount_model->get($id);
        $data['feediscountList'] = $feediscount_result;

        $genderList = $this->customlib->getGender();
        $data['genderList'] = $genderList;
        $RTEstatusList = $this->customlib->getRteStatus();
        $data['RTEstatusList'] = $RTEstatusList;
        $category = $this->category_model->get();
        $data['categorylist'] = $category;

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $data['category_id'] = $this->input->post('category_id');
            $data['gender'] = $this->input->post('gender');
            $data['rte_status'] = $this->input->post('rte');
            $data['class_id'] = $this->input->post('class_id');
            $data['section_id'] = $this->input->post('section_id');
            $resultlist = $this->feediscount_model->searchAssignFeeByClassSection($data['class_id'], $data['section_id'], $id, $data['category_id'], $data['gender'], $data['rte_status']);
            $data['resultlist'] = $resultlist;
        }
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/feediscount/assign', $data);
        $this->load->view('layout/footer', $data);
    }

    function studentdiscount() {
        if (!$this->rbac->hasPrivilege('fees_discount_assign', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feediscount');
        $this->form_validation->set_rules('feediscount_id', 'Fee Discount', 'required|trim|xss_clean');


        if ($this->form_validation->run() == false) {
            $data = array(
                'feediscount_id' => form_error('feediscount_id'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {

            $session            = $this->setting_model->getCurrentSession();
            $student_list = $this->input->post('student_list');
            $feediscount_id = $this->input->post('feediscount_id');
            $student_sesssion_array = $this->input->post('student_session_id');
            if (!isset($student_sesssion_array)) {
                $student_sesssion_array = array();
            }
            $diff_aray = array_diff($student_list, $student_sesssion_array);
            $preserve_record = array();
            foreach ($student_sesssion_array as $key => $value) {

                $insert_array = array(
                    'student_session_id' => $value,
                    'fees_discount_id' => $feediscount_id,
                    'is_active' => "Yes",
                    'session_id' => $session,
                );
                $inserted_id = $this->feediscount_model->allotdiscount($insert_array);

                $preserve_record[] = $inserted_id;
            }
            if (!empty($diff_aray)) {
                $this->feediscount_model->deletedisstd($feediscount_id, $diff_aray);
            }

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }

    function applydiscount() {
        if (!$this->rbac->hasPrivilege('fees_discount_assign', 'can_view')) {
            access_denied();
        }
        $this->form_validation->set_rules('discount_payment_id', $this->lang->line('fees_payment_id'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('student_fees_discount_id', $this->lang->line('fees_discount_id'), 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'amount' => form_error('amount'),
                'discount_payment_id' => form_error('discount_payment_id'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {

            $data = array(
                'id' => $this->input->post('student_fees_discount_id'),
                'payment_id' => $this->input->post('discount_payment_id'),
                'description' => $this->input->post('dis_description'),
                'status' => 'applied'
            );

            $this->feediscount_model->updateStudentDiscount($data);
            $array = array('status' => 'success', 'error' => '');
            echo json_encode($array);
        }
    }

}

?>