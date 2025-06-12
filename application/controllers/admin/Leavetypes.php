<?php


class LeaveTypes extends Admin_Controller {

    function __construct() {

        parent::__construct();

        $this->load->helper('file');
        $this->config->load("payroll");

        $this->load->model('leavetypes_model');
        $this->load->model('staff_model');
    }

    function index() {

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/leavetypes');
        $data["title"] = $this->lang->line('add') . " " . $this->lang->line('leave') . " " . $this->lang->line('type');

        $LeaveTypes = $this->leavetypes_model->getLeaveType();

        $data["leavetype"] = $LeaveTypes;
        $this->load->view("layout/header");
        $this->load->view("admin/staff/leavetypes", $data);
        $this->load->view("layout/footer");
    }

    function createLeaveType() {


        $this->form_validation->set_rules(
                'type', $this->lang->line('leave_type'), array('required',
            array('check_exists', array($this->leavetypes_model, 'valid_leave_type'))
                )
        );
        $data["title"] = $this->lang->line('add') . " " . $this->lang->line('leave') . " " . $this->lang->line('type');
        if ($this->form_validation->run()) {

            $type = $this->input->post("type");
            $description = $this->input->post("description");
            $leavetypeid = $this->input->post("leavetypeid");
            $status = $this->input->post("status");
            if (empty($leavetypeid)) {

                if (!$this->rbac->hasPrivilege('leave_types', 'can_add')) {
                    access_denied();
                }
            } else {

                if (!$this->rbac->hasPrivilege('leave_types', 'can_edit')) {
                    access_denied();
                }
            }

            if (!empty($leavetypeid)) {
                $data = array('type' => $type, 'description'=>$description, 'is_active' => 'yes', 'id' => $leavetypeid);
            } else {

                $data = array('type' => $type, 'description'=>$description, 'is_active' => 'yes');
            }

            $insert_id = $this->leavetypes_model->addLeaveType($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('success_message') . '</div>');
            redirect("admin/leavetypes");
        } else {

            $LeaveTypes = $this->leavetypes_model->getLeaveType();
            $data["leavetype"] = $LeaveTypes;
            $this->load->view("layout/header");
            $this->load->view("admin/staff/leavetypes", $data);
            $this->load->view("layout/footer");
        }
    }

    function leaveedit($id) {

        $result = $this->staff_model->getLeaveType($id);

        $data["title"] = $this->lang->line('edit') . " " . $this->lang->line('leave') . " " . $this->lang->line('type');
        $data["result"] = $result;

        $LeaveTypes = $this->leavetypes_model->getLeaveType();
        $data["leavetype"] = $LeaveTypes;
        $this->load->view("layout/header");
        $this->load->view("admin/staff/leavetypes", $data);
        $this->load->view("layout/footer");
    }

    function leavedelete($id) {

        $this->leavetypes_model->deleteLeaveType($id);
        redirect('admin/leavetypes');
    }

    public function leavetype_category($leave_type_id, $id=null)
    {
        $data["title"] = $this->lang->line('add') . " " . $this->lang->line('leave') . " " . $this->lang->line('type')." ".$this->lang->line('category');
        $data['leave_type_id'] = $leave_type_id;
        $result = $this->staff_model->getLeaveType($leave_type_id);
        $data["leave_types_name"] = $result['type'];
        $data['payroll_category'] = $this->payroll_model->getPayrollCategory();
        if ($id != "") {
            $data['update']  = $this->leavetypes_model->getLeaveTypeCategory($id);
        }
        $this->form_validation->set_rules('payroll_category', "Payroll Category", 'required|trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
        } else {
            $userdata           = $this->customlib->getUserData();
            
            $insert_array = array(
                'id' => $this->input->post('id'),
                'leave_type' => $this->input->post('leave_type_id'),
                'payroll_category' => $this->input->post('payroll_category'),
                'gender' => $this->input->post('gender'),
                'qty' => $this->input->post('qty'),
                'period_type' => $this->input->post('period_type'),
                'period' => $this->input->post('period'),
                
            );
            
            // echo "<pre>";
            // print_r ($insert_array);die;
            // echo "</pre>";
            
            $this->leavetypes_model->addLeaveTypeCategory($insert_array);

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/leavetypes/leavetype_category/' . $leave_type_id);
        }

        $data['resultlist'] = $this->leavetypes_model->getLeaveTypeCategory("",$leave_type_id);

        
        // echo "<pre>";
        // print_r ($data['resultlist']);die;
        // echo "</pre>";
        

        $this->load->view("layout/header");
        $this->load->view("admin/staff/leavetype_category", $data);
        $this->load->view("layout/footer");
    }

    public function leavetype_category_delete($id)
    {
        if (!$this->rbac->hasPrivilege('leave_types', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'leave type List';
        $this->leavetypes_model->remove_leavetype_category($id);
        redirect('admin/leavetypes/leavetype_category/'.$id);
    }

    public function leavetypecategorylist()
    {
        $data['resultlist'] = $this->leavetypes_model->getLeaveTypeCategory();
        $this->load->view("layout/header");
        $this->load->view("admin/staff/leavetype_categorylist", $data);
        $this->load->view("layout/footer");
    }

}

?>