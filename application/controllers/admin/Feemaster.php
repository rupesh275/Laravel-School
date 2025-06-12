<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Feemaster extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->current_ay_session = $this->sch_setting_detail->session_id;
    }

    function index()
    {

        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feemaster');
        $data['title'] = 'Feemaster List';
        $feegroup = $this->feegroup_model->get();
        $data['feegroupList'] = $feegroup;
        $feetype = $this->feetype_model->get();
        $data['feetypeList'] = $feetype;
        $data['userdata']           = $this->customlib->getUserData();

        $feegroup_result = $this->feesessiongroup_model->getFeesByGroup();
        $data['feemasterList'] = $feegroup_result;

        $this->form_validation->set_rules('feetype_id', $this->lang->line('feetype'), 'required');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'required|numeric');

        $this->form_validation->set_rules(
            'fee_groups_id',
            $this->lang->line('feegroup'),
            array(
                'required',
                array('check_exists', array($this->feesessiongroup_model, 'valid_check_exists'))
            )
        );

        if (isset($_POST['account_type']) && $_POST['account_type'] == 'fix') {
            $this->form_validation->set_rules('fine_amount', $this->lang->line('fine') . " " . $this->lang->line('amount'), 'required|numeric');
            $this->form_validation->set_rules('due_date', $this->lang->line('due_date'), 'trim|required|xss_clean');
        } elseif (isset($_POST['account_type']) && ($_POST['account_type'] == 'percentage')) {
            $this->form_validation->set_rules('fine_percentage', $this->lang->line('percentage'), 'required|numeric');
            $this->form_validation->set_rules('fine_amount', $this->lang->line('fine') . " " . $this->lang->line('amount'), 'required|numeric');
            $this->form_validation->set_rules('due_date', $this->lang->line('due_date'), 'trim|required|xss_clean');
        }

        if ($this->form_validation->run() == FALSE) {
        } else {


            $insert_array = array(
                'fee_groups_id' => $this->input->post('fee_groups_id'),
                'feetype_id' => $this->input->post('feetype_id'),
                'amount' => $this->input->post('amount'),
                'due_date' => $this->customlib->dateFormatToYYYYMMDD($this->input->post('due_date')),
                'session_id' => $this->setting_model->getCurrentSession(),
                'fine_type' => $this->input->post('account_type'),
                'fine_percentage' => $this->input->post('fine_percentage'),
                'fine_amount' => $this->input->post('fine_amount'),
            );

            $feegroup_result = $this->feesessiongroup_model->add($insert_array);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/feemaster/index');
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/feemaster/feemasterList', $data);
        $this->load->view('layout/footer', $data);
    }

    function delete($id)
    {
        if (!$this->rbac->hasPrivilege('fees_master', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Fees Master List';
        $this->feegrouptype_model->remove($id);
        redirect('admin/feemaster/index');
    }

    function deletegrp($id)
    {
        $data['title'] = 'Fees Master List';
        $this->feesessiongroup_model->remove($id);
        redirect('admin/feemaster');
    }

    function edit($id)
    {
        if (!$this->rbac->hasPrivilege('fees_master', 'can_edit')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feemaster');
        $data['id'] = $id;
        $feegroup_type = $this->feegrouptype_model->get($id);
        $data['feegroup_type'] = $feegroup_type;
        $feegroup = $this->feegroup_model->get();
        $data['feegroupList'] = $feegroup;
        $feetype = $this->feetype_model->get();
        $data['feetypeList'] = $feetype;
        $feegroup_result = $this->feesessiongroup_model->getFeesByGroup();
        $data['feemasterList'] = $feegroup_result;
        $data['userdata']           = $this->customlib->getUserData();
        $this->form_validation->set_rules('feetype_id', $this->lang->line('feetype'), 'required');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'required');
        $this->form_validation->set_rules(
            'fee_groups_id',
            $this->lang->line('feegroup'),
            array(
                'required',
                array('check_exists', array($this->feesessiongroup_model, 'valid_check_exists'))
            )
        );

        if (isset($_POST['account_type']) && $_POST['account_type'] == 'fix') {
            $this->form_validation->set_rules('fine_amount', $this->lang->line('fine') . " " . $this->lang->line('amount'), 'required|numeric');
            $this->form_validation->set_rules('due_date', $this->lang->line('due_date'), 'trim|required|xss_clean');
        } elseif (isset($_POST['account_type']) && ($_POST['account_type'] == 'percentage')) {
            $this->form_validation->set_rules('fine_percentage', $this->lang->line('percentage'), 'required|numeric');
            $this->form_validation->set_rules('fine_amount', $this->lang->line('fine') . " " . $this->lang->line('amount'), 'required|numeric');
            $this->form_validation->set_rules('due_date', $this->lang->line('due_date'), 'trim|required|xss_clean');
        }
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/feemaster/feemasterEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $insert_array = array(
                'id' => $this->input->post('id'),
                'feetype_id' => $this->input->post('feetype_id'),
                'due_date' => $this->customlib->dateFormatToYYYYMMDD($this->input->post('due_date')),
                'amount' => $this->input->post('amount'),
                'fine_type' => $this->input->post('account_type'),
                'fine_percentage' => $this->input->post('fine_percentage'),
                'fine_amount' => $this->input->post('fine_amount'),
            );

            $feegroup_result = $this->feegrouptype_model->add($insert_array);

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/feemaster/index');
        }
    }

    function assign($id)
    {
        if (!$this->rbac->hasPrivilege('fees_group_assign', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feemaster');
        $data['id'] = $id;
        $data['title'] = 'student fees';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $feegroup_result = $this->feesessiongroup_model->getFeesByGroup($id);
        $data['feegroupList'] = $feegroup_result;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $data['sch_setting'] = $this->sch_setting_detail;

        $genderList = $this->customlib->getGender();
        $data['genderList'] = $genderList;
        $RTEstatusList = $this->customlib->getRteStatus();
        $data['RTEstatusList'] = $RTEstatusList;

        $vehroute_result         = $this->vehroute_model->get();
        $data['vehroutelist']    = $vehroute_result;

        $category = $this->category_model->get();
        $data['categorylist'] = $category;


        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $data['category_id'] = $this->input->post('category_id');
            $data['gender'] = $this->input->post('gender');
            $data['rte_status'] = $this->input->post('rte');
            $data['class_id'] = $this->input->post('class_id');
            $data['section_id'] = $this->input->post('section_id');
            $data['vehroute_id'] = $this->input->post('vehroute_id');


            $resultlist = $this->studentfeemaster_model->searchAssignFeeByClassSection($data['class_id'], $data['section_id'], $id, $data['category_id'], $data['gender'], $data['rte_status'], $data['vehroute_id']);
            $data['resultlist'] = $resultlist;
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/feemaster/assign', $data);
        $this->load->view('layout/footer', $data);
    }

    function assign_bus($id)
    {
        if (!$this->rbac->hasPrivilege('fees_group_assign_bus', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feemaster');
        $data['id'] = $id;
        $data['title'] = 'student fees';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $feegroup_result = $this->feesessiongroup_model->getFeesByGroup($id);
        $data['feegroupList'] = $feegroup_result;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $data['sch_setting'] = $this->sch_setting_detail;

        $genderList = $this->customlib->getGender();
        $data['genderList'] = $genderList;
        $RTEstatusList = $this->customlib->getRteStatus();
        $data['RTEstatusList'] = $RTEstatusList;

        $listroute = $this->route_model->listroute();
        $data['listroute'] = $listroute;

        $category = $this->category_model->get();
        $data['categorylist'] = $category;


        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            // $data['category_id'] = $this->input->post('category_id');
            // $data['gender'] = $this->input->post('gender');
            // $data['rte_status'] = $this->input->post('rte');
            // $data['vehroute_id'] = $this->input->post('vehroute_id');
            $data['class_id'] = $this->input->post('class_id');
            $data['section_id'] = $this->input->post('section_id');
            $data['vehroute_id'] = $this->input->post('vehroute_id');


            // $resultlist = $this->studentfeemaster_model->searchAssignFeeByClassSection($data['class_id'], $data['section_id'], $data['vehroute_id']);
            $resultlist = $this->feemaster_model->searchAssignBusFeeByClassSection($data['class_id'], $data['section_id'],$id, $data['vehroute_id']);
            $data['resultlist'] = $resultlist;
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/feemaster/assign_bus', $data);
        $this->load->view('layout/footer', $data);
    }

    function assign_class($id)
    {
        if (!$this->rbac->hasPrivilege('fees_group_assign', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feemaster');
        $data['id'] = $id;
        $data['title'] = 'student fees';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $feegroup_result = $this->feesessiongroup_model->getFeesByGroup($id);
        $data['feegroupList'] = $feegroup_result;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $data['sch_setting'] = $this->sch_setting_detail;

        $data['update'] = $this->feegrouptype_model->get_class_mst($id);
        $data['resultlist'] = $this->feegrouptype_model->get_class_mst2($id);

        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'required');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/feemaster/assign_class', $data);
            $this->load->view('layout/footer', $data);
        } else {

            $insert_array = array(
                // 'id' => $this->input->post('id'),
                'class_id' => $this->input->post('class_id'),
                'section_id' => $this->input->post('section_id'),
                'session_id' => $this->setting_model->getCurrentSession(),
                'fees_group_id' => $id,
            );

            $feegroup_result = $this->feegrouptype_model->add_class_fees($insert_array);

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/feemaster/assign_class/' . $id);
        }
    }

    public function deleteclass_mst($id, $fees_group_id)
    {
        $this->feegrouptype_model->remove_mst($id);
        redirect('admin/feemaster/assign_class/' . $fees_group_id);
    }

    public function cheque_in_word()
    {
        if (!$this->rbac->hasPrivilege('cheque_in_word', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feemaster/cheque_in_word');
        $this->data = "";

        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $session = $this->session_model->get();
        $data['sessionlist'] = $session;

        $data['resultlist'] = $this->feemaster_model->getCheque();

        $this->load->view('layout/header');
        $this->load->view('admin/feemaster/cheque_in_word', $data);
        $this->load->view('layout/footer');
    }

    public function addCheque($id = null)
    {
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feemaster/cheque_in_word');
        $this->data = "";

        if ($id != "") {
            $data['update']  = $this->feemaster_model->getCheque($id);
        }
        $this->form_validation->set_rules('student_session_id', $this->lang->line('student'), 'trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
        } else {
            $userdata           = $this->customlib->getUserData();
            $insert_array = array(
                'id' => $this->input->post('id'),
                'class_id' => $this->input->post('class_id'),
                'section_id' => $this->input->post('section_id'),
                'student_session_id' => $this->input->post('student_session_id'),
                'session_id' => $this->setting_model->getCurrentSession(),
                'chq_type' => "student",
                'chq_no' => $this->input->post('chq_no'),
                'chq_date' => date('Y-m-d', strtotime($this->input->post('chq_date'))),
                'chq_bank' => $this->input->post('chq_bank'),
                'chq_branch' => $this->input->post('chq_branch'),
                'chq_amt' => $this->input->post('chq_amt'),
                'chq_status' => "collected",
                'contact_no' => $this->input->post('contact_no'),
                'remarks' => $this->input->post('remarks'),
                'deposit_date' => date('Y-m-d', strtotime($this->input->post('deposit_date'))),
                'chq_pass_date' => date('Y-m-d', strtotime($this->input->post('pass_date'))),
                'created_by' =>  $userdata['name'],
            );

            $this->feemaster_model->addCheque($insert_array);

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/feemaster/cheque_in_word');
        }

        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $session = $this->session_model->get();
        $data['sessionlist'] = $session;



        $this->load->view('layout/header');
        $this->load->view('admin/feemaster/addCheque', $data);
        $this->load->view('layout/footer');
    }

    public function getclass_students()
    {
        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');

        $studentslist = $this->student_model->getstudentforcheque($class_id, $section_id)->result_array();
        echo json_encode($studentslist);
    }

    public function delCheque($id)
    {
        $this->feemaster_model->removeCheque($id);
        redirect('admin/feemaster/cheque_in_word');
    }

    public function chq_deposit()
    {
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feemaster/cheque_in_word');
        $this->data = "";
        $session = $this->session_model->get();
        $data['sessionlist'] = $session;
        $data['resultlist'] = $this->feemaster_model->getChequeDeposit();

        $this->form_validation->set_rules('cheque_id', $this->lang->line('student'), 'trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
        } else {
            $userdata           = $this->customlib->getUserData();
            $cheque_ids = $this->input->post('cheque_id');
            if (!empty($cheque_ids)) {
                foreach ($cheque_ids as  $cheq_id) {
                    $update  = $this->feemaster_model->getCheque($cheq_id);
                    $cheqDate = $this->input->post('date');
                    // if ($update['created_at'] > $this->input->post('date')) {
                    //     $cheqDate = $update['created_at'];
                    // } else {
                    //     $cheqDate = $this->input->post('date');
                    // }

                    $array = array(
                        'id'            => $cheq_id,
                        'chq_status'    => "deposit",
                        'deposit_date'  => date('Y-m-d', strtotime($cheqDate)),
                    );

                    $this->feemaster_model->chqPassStatus($array);
                }

                $chqIs = implode(',', $cheque_ids);
                $arrayChqProcess = array(
                    'entry_date'    => date('Y-m-d', strtotime($this->input->post('date'))),
                    'type'          => "student",
                    'check_ids'     => $chqIs,
                    'created_by'    => $userdata['name'],
                    'created_at'    => date('Y-m-d h:i:s')
                );
                $this->feemaster_model->addChqProcess($arrayChqProcess);


            }
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/feemaster/cheque_in_word');
        }
        $this->load->view('layout/header');
        $this->load->view('admin/feemaster/chq_deposit', $data);
        $this->load->view('layout/footer');
    }
    public function chq_passed()
    {
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feemaster/cheque_in_word');
        $this->data = "";
        $session = $this->session_model->get();
        $data['sessionlist'] = $session;
        $data['resultlist'] = $this->feemaster_model->getChequeBounce();

        $this->form_validation->set_rules('cheque_id[]', $this->lang->line('student'), 'trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
        } else {

            $cheque_ids = $this->input->post('cheque_id');
            if (!empty($cheque_ids)) {
                foreach ($cheque_ids as  $cheq_id) {
                    $update  = $this->feemaster_model->getCheque($cheq_id);
                    $cheqDate = $this->input->post('date');
                    // if ($update['deposit_date'] > $this->input->post('date')) {
                    //     $cheqDate = $update['deposit_date'];
                    // } else {
                    //     $cheqDate = $this->input->post('date');
                    // }
                    $array = array(
                        'id'            => $cheq_id,
                        'chq_status'    => "passed",
                        'chq_pass_date' => date('Y-m-d', strtotime($cheqDate)),
                    );
                    $this->feemaster_model->chqPassStatus($array);

                    $result=$this->db->query("select * from online_transaction  where chequeid = '$cheq_id' ")->row_array();
                    if($result['source']=='counter-main')                 
                    {
                        redirect('studentfee/addfeegrp_gateway_submit/'.$result['hash_code']);                    
                    }
                    elseif($result['source']=='counter-previous')                 
                    {
                        redirect('studentfee/addfeegrp_gateway_submit_previous/'.$result['hash_code']);                    
                    }
                    elseif ($result['source']=='counter-general')
                    {
                        redirect('studentfee/addgeneral_gateway_submit/'.$result['hash_code']);
                    }
                }
            }
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/feemaster/cheque_in_word');
        }
        $this->load->view('layout/header');
        $this->load->view('admin/feemaster/chq_passed', $data);
        $this->load->view('layout/footer');
    }
    public function chq_bounce()
    {
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feemaster/cheque_in_word');
        $this->data = "";
        $session = $this->session_model->get();
        $data['sessionlist'] = $session;
        $data['resultlist'] = $this->feemaster_model->getChequeBounce();

        $this->form_validation->set_rules('cheque_id[]', $this->lang->line('student'), 'trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
        } else {
           
            $cheque_ids = $this->input->post('cheque_id');
            $bounce_charge = $this->input->post('bounce_charge');
            if (!empty($cheque_ids)) {
                foreach ($cheque_ids as  $cheq_id) {
                    $array = array(
                        'id'            => $cheq_id,
                        'chq_status'    => "bounced",
                        'bounce_date'   => date('Y-m-d',strtotime($this->input->post('date'))),
                        'bounce_charge'   => $this->input->post('bounce_charge'),
                    );
                    $this->feemaster_model->chqPassStatus($array);
                    $chequeData = $this->feemaster_model->getCheque($cheq_id);
                    if (!empty($chequeData['student_session_id'])) {
                        $insert_array = array(
                            'student_session_id'   => $chequeData['student_session_id'],
                            'fee_session_group_id' => 54,
                            'session_id' => $this->input->post('session_id'),
                        );
                        $inserted_id = $this->studentfeemaster_model->add_direct($insert_array);
                    }
                    $result=$this->db->query("select * from online_transaction  where chequeid = '$cheq_id' ")->row_array();
                    $array_data = array(
                        'pass_date' => date('Y-m-d',strtotime($this->input->post('date'))),
                        'trn_status' => 'bounced',
                    );
                    $this->db->where('id',$result['id']);
                    $this->db->update('online_transaction',$array_data);                    
                }
            }
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/feemaster/cheque_in_word');
        }
        $this->load->view('layout/header');
        $this->load->view('admin/feemaster/chq_bounce', $data);
        $this->load->view('layout/footer');
    }
    public function common_chq()
    {
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feemaster/cheque_in_word');
        $this->data = "";
        $session = $this->session_model->get();
        $data['sessionlist'] = $session;
        $data['resultlist'] = $this->feemaster_model->getChequeBounce();

        $this->form_validation->set_rules('cheque_id[]', $this->lang->line('student'), 'trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
        } else {

            $userdata           = $this->customlib->getUserData();
            $array = array(
                'id'            => $this->input->post('id'),
                'session_id' => $this->setting_model->getCurrentSession(),
                'chq_type' => "others",
                'chq_no' => $this->input->post('chq_no'),
                'chq_date' => date('Y-m-d', strtotime($this->input->post('chq_date'))),
                'chq_bank' => $this->input->post('chq_bank'),
                'chq_branch' => $this->input->post('chq_branch'),
                'chq_amt' => $this->input->post('chq_amt'),
                'chq_status' => "collected",
                'contact_no' => $this->input->post('contact_no'),
                'remarks' => $this->input->post('remarks'),
                'deposit_date' => date('Y-m-d', strtotime($this->input->post('deposit_date'))),
                'chq_pass_date' => date('Y-m-d', strtotime($this->input->post('pass_date'))),
                'created_by' =>  $userdata['name'],
            );

            $this->feemaster_model->addCheque($array);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/feemaster/cheque_in_word');
        }

        $this->load->view('layout/header');
        $this->load->view('admin/feemaster/common_chq', $data);
        $this->load->view('layout/footer');
    }

    public function chequeReport()
    {
        if (!$this->rbac->hasPrivilege('chequeReport', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/chequeReport');
        $data['title'] = 'student fee';
        $data['title'] = 'student fee';
        $class = $this->class_model->get();
        $data['classlist'] = $class;

        $data['searchlist']  = $this->customlib->get_searchtype();
        $data['date_type']   = $this->customlib->date_type();

        if (isset($_POST['search_type']) && $_POST['search_type'] != '') {
            $dates               = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $_POST['search_type'];
        } else {
            $dates               = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = '';
        }

        if (isset($_POST['chq_status']) && $_POST['chq_status'] != '') {
            $data['chq_status'] = $chq_status = $_POST['chq_status'];
        } else {
            $data['chq_status'] = $chq_status = '';
        }

        $start_date          = date('Y-m-d', strtotime($dates['from_date']));
        $end_date            = date('Y-m-d', strtotime($dates['to_date']));
        $data['start_date']          = $dates['from_date'];
        $data['end_date']            = $dates['to_date'];

        $this->form_validation->set_rules('search_type', $this->lang->line('search') . " " . $this->lang->line('type'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $data['results'] = array();
        } else {
            $data['results'] = $this->feemaster_model->getChequeData($start_date, $end_date,$chq_status);
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/feemaster/chequeReport', $data);
        $this->load->view('layout/footer', $data);
    }

    public function onlinefailedreport()
    {
        if (!$this->rbac->hasPrivilege('onlinefailedreport', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/onlinefailedreport');
        $data['title'] = 'student fee';
        $data['title'] = 'student fee';
        $class = $this->class_model->get();
        $data['classlist'] = $class;

        $data['searchlist']  = $this->customlib->get_searchtype();
        $data['date_type']   = $this->customlib->date_type();

        if (isset($_POST['search_type']) && $_POST['search_type'] != '') {
            $dates               = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $_POST['search_type'];
        } else {
            $dates               = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = '';
        }

        $start_date          = date('Y-m-d', strtotime($dates['from_date']));
        $end_date            = date('Y-m-d', strtotime($dates['to_date']));
        $data['start_date']          = $dates['from_date'];
        $data['end_date']            = $dates['to_date'];

        $this->form_validation->set_rules('search_type', $this->lang->line('search') . " " . $this->lang->line('type'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $data['results'] = array();
        } else {
            $data['results'] = $this->feemaster_model->getOnlineResults($start_date, $end_date);
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/feemaster/onlinefailedreport', $data);
        $this->load->view('layout/footer', $data);
    }

    public function reconsilation()
    {
        if (!$this->rbac->hasPrivilege('reconsilation', 'can_view')) {
            access_denied();
        }

        $data['collect_by'] = $this->studentfeemaster_model->get_feesreceived_by2();
        $data['searchlist'] = $this->customlib->get_searchtype();
        $data['group_by']   = $this->customlib->get_groupby();
        $feetype = $this->feetype_model->get();
        $data['feetypeList'] = $feetype;
        $data['sessionlist'] = $this->session_model->get();
        $data['banklists'] = $this->incomehead_model->getBankHead();
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/reconsilation');
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
        $data['rec_session_id'] = $this->current_ay_session;
        $list_type = $data['list_type']  = $_POST['list_type'];

        $this->form_validation->set_rules('search_type', $this->lang->line('search') . " " . $this->lang->line('type'), 'trim|required|xss_clean');


        if ($this->form_validation->run() == false) {
            $data['results'] = array();
        } else {

            $data['results'] = $this->studentfeemaster_model->getReconcileReceipt($start_date, $end_date, $feetype_id, $received_by, $group,$payment_mode,$session_id,$list_type);
            // echo "<pre>";
            // print_r($data);die();
        }
        $data['subtotal'] = $subtotal;
        $data['sch_setting'] = $this->sch_setting_detail;
  
        $this->load->view('layout/header', $data);
        $this->load->view('admin/feemaster/reconsilation', $data);
        $this->load->view('layout/footer', $data);
    }

    public function updateStatus()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('reconciled_on', "Reconciled On", 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $msg = array(
                'reconciled_on' => form_error('reconciled_on'),
            );
            $array = array('status' => 0, 'error' => $msg, 'message' => '');
        }else {
       
            $receipt_ids = $this->input->post('receipt_id');
            if (!empty($receipt_ids)) {
                foreach ($receipt_ids as  $value) {
                    $array = [
                        'id'=> $value,
                        'pass_date'=> date('Y-m-d',strtotime($this->input->post('pass_date'))),
                        'reconciled_to'=> $this->input->post('reconciled_on')
                    ];
                    $this->feemaster_model->updateReceiptStatus($array);
                }
            }
            $array = array('status' => 1, 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
        
    }
}
