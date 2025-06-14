<?php

class Leaverequest extends Admin_Controller {

    function __construct() {
        parent::__construct();

        $this->config->load("payroll");

        $this->load->model("staff_model");
        $this->load->model("leaverequest_model");
        $this->contract_type = $this->config->item('contracttype');
        $this->marital_status = $this->config->item('marital_status');
        $this->staff_attendance = $this->config->item('staffattendance');
        $this->payroll_status = $this->config->item('payroll_status');
        $this->payment_mode = $this->config->item('payment_mode');
        $this->status = $this->config->item('status');
    }

    function leaverequest() {
        if (!$this->rbac->hasPrivilege('approve_leave_request', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/leaverequest/leaverequest');
        $leave_request = $this->leaverequest_model->staff_leave_request();
        $data["leave_request"] = $leave_request;
        $LeaveTypes = $this->staff_model->getLeaveType();
        $userdata = $this->customlib->getUserData();
        $data["leavetype"] = $LeaveTypes;
        $staffRole = $this->staff_model->getStaffRole();
        $data["staffrole"] = $staffRole;
        $data["status"] = $this->status;
        $this->load->view("layout/header", $data);
        $this->load->view("admin/staff/staffleaverequest", $data);
        $this->load->view("layout/footer", $data);
    }
 
    function countLeave($id) {
        $lid = $this->input->post("lid");
        $alloted_leavetype = $this->leaverequest_model->allotedLeaveType($id);

        $i = 0;
        $html = "<select  name='leave_type' id='leave_type' class='form-control'><option value=''>" . $this->lang->line('select') . "</option>";
        $data = array();

        foreach ($alloted_leavetype as $key => $value) {
            $count_leaves[] = $this->leaverequest_model->countLeavesData($id, $value["leave_type_id"]);
            $data[$i]['type'] = $value["type"];
            $data[$i]['id'] = $value["leave_type_id"];
            $data[$i]['alloted_leave'] = $value["alloted_leave"];
            $data[$i]['approve_leave'] = $count_leaves[$i]['approve_leave'];


            $i++;
        }

        foreach ($data as $dkey => $dvalue) {
            if (!empty($dvalue["alloted_leave"])) {
                if ($lid == $dvalue["id"]) {
                    $a = "selected";
                } else {
                    $a = "";
                }

                if ($dvalue["alloted_leave"] == "") {

                    $available = $dvalue["approve_leave"];
                } else {
                    $available = $dvalue["alloted_leave"] - $dvalue["approve_leave"];
                }
                if ($available > 0) {

                    $html .= "<option value=" . $dvalue["id"] . " $a>" . $dvalue["type"] . " (" . $available . ")" . "</option>";
                }
            }
        }

        $html .= "</select>";
        echo $html;
    }

    function leaveStatus() {
        if ((!$this->rbac->hasPrivilege('approve_leave_request', 'can_edit'))) {
            access_denied();
        }
        $leave_request_id = $this->input->post("leave_request_id");
        $status = $this->input->post("status");
        $adminRemark = $this->input->post("detailremark");
        $data = array('status' => $status, 'admin_remark' => $adminRemark);
        $this->leaverequest_model->changeLeaveStatus($data, $leave_request_id);
        if ($status == 'approve') {
            
            $leaveRequestRow = $this->leaverequest_model->getLeaveRequestSubByLeaveRequestId($leave_request_id);

            $insert_array = [];
            $rw=-1;
            foreach ($leaveRequestRow as $key => $value) {

                $last_date = $value['from_date'];
                while (date('m',strtotime($last_date)) != date('m',strtotime($value['to_date']))) {
                    $from_date = $last_date;
                   $last_date =  $this->getLastDateOfMonth(date('Y',strtotime($from_date)), date('m',strtotime($from_date)));
                   $days = $this->dateDifference($from_date, $last_date);
                   $insert_array[] = array(
                       'leave_request_id' => $leave_request_id, 
                       'staff_id' => $value['staff_id'],
                       'month' => date("F",strtotime($from_date)),
                       'year' => date("Y",strtotime($from_date)),
                       'leave_type_id' => $value["leave_type_id"],
                       'days' => $days,
                   ); 
                   ++$rw;
                   $last_date = date('Y-m-d', strtotime($last_date. ' + 1 days'));
                   
                }
                $from_date = $last_date;
                
                $days = $this->dateDifference($from_date, $value['to_date']);
                $insert_array[] = array(
                    'leave_request_id' => $leave_request_id, 
                    'staff_id' => $value['staff_id'],
                    'month' => date("F",strtotime($from_date)),
                    'year' => date("Y",strtotime($from_date)),
                    'leave_type_id' => $value["leave_type_id"],
                    'days' => $days,
                );  
                ++$rw;  
                if(fmod($value['days'], 1) !== 0.00){
                    $insert_array[$rw]['days'] = $insert_array[$rw]['days'] + 0.5;
                }                 

            }

            if(!empty($insert_array))
            {
                $this->db->insert_batch('approved_staff_leave_trn', $insert_array);
            }
        }
        $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        echo json_encode($array);
    }

    function getLastDateOfMonth($year, $month) {
        // Create a DateTime object for the first day of the given month
        $date = new DateTime("$year-$month-01");
        
        // Modify the date to the first day of the next month
        $date->modify('first day of next month');
        
        // Subtract one day to get the last day of the current month
        $date->modify('-1 day');
        
        // Return the last day of the month in YYYY-MM-DD format
        return $date->format('Y-m-d');
    }

    function remove($id) {

        $this->leaverequest_model->leave_remove($id);
    }

    function leaveRecord() {

        $id = $this->input->post("id");
        $result = $this->staff_model->getLeaveRecord($id);
        $leave_from = date("m/d/Y", strtotime($result->leave_from));
        $result->leavefrom = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($result->leave_from));
        $result->date = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($result->date));
        $leave_to = date("m/d/Y", strtotime($result->leave_to));
        $result->leaveto = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($result->leave_to));
        $result->days = $this->dateDifference($leave_from, $leave_to);
        
        if ($result->type == "") {
            $leaveTypeRow = $this->leaverequest_model->getLeaveTypeByLeaveRequestId($id);                  
            $array  = array_column($leaveTypeRow,'type');
            $leaveType = implode(",",$array );
            $result->type = $leaveType;
        }else {
            $result->type = $result->type;
        }
        $result->leave_detail = "";
        $leaveSubRow = $this->leaverequest_model->getLeaveRequestSubByLeaveRequestId($id);
        if (!empty($leaveSubRow)) {
            foreach ($leaveSubRow as $key => $value) {
                $result->leave_detail .= date('d-m-Y',strtotime($value["from_date"]))." - ".date('d-m-Y',strtotime($value["to_date"]))." - " . $value["type"]."(".$value["days"]." Days)" . ",<br> ";
            }
        }

        
        echo json_encode($result);
    }


    function dateDifference($date_1, $date_2, $differenceFormat = '%a') {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);

        $interval = date_diff($datetime1, $datetime2);

        return $interval->format($differenceFormat) + 1;
    }

    function addLeave() {


        $role = $this->input->post("role");
        $empid = $this->input->post("empname");
        $applied_date = $this->input->post("applieddate");
        $leavetype = $this->input->post("leave_type");

        $reason = $this->input->post("reason");
        $remark = $this->input->post("remark");
        $status = $this->input->post("addstatus");
        $request_id = $this->input->post("leaverequestid");

        $this->form_validation->set_rules('role', $this->lang->line('role'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('empname', $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('applieddate', $this->lang->line('applied_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('leave_from_date', $this->lang->line('leave')." ".$this->lang->line('from')." ".$this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('leave_to_date', $this->lang->line('leave')." ".$this->lang->line('to')." ".$this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('leave_type', $this->lang->line('available') . " " . $this->lang->line('leave'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('leave_type', $this->lang->line('leave') . " " . $this->lang->line('type'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('userfile', $this->lang->line('file'), 'callback_handle_upload[userfile]');

        if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'role' => form_error('role'),
                'empname' => form_error('empname'),
                'applieddate' => form_error('applieddate'),
                'leavedates' => form_error('leavedates'),
                'leave_type' => form_error('leave_type'),
                'leave_from_date' => form_error('leave_from_date'),
                'leave_to_date' => form_error('leave_to_date'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
          

            $leavefrom = date("Y-m-d", $this->customlib->datetostrtotime($this->input->post('leave_from_date')));
            $leaveto = date("Y-m-d", $this->customlib->datetostrtotime($this->input->post('leave_to_date')));
            $applied_by = $this->customlib->getAdminSessionUserName();
            $leave_days = $this->dateDifference($leavefrom, $leaveto);
            $staff_id = $empid;
            $my_laeve = $this->leaverequest_model->myallotedLeaveType($staff_id, $leavetype);
            if ($my_laeve['alloted_leave'] >= $leave_days) {
                if (isset($_FILES["userfile"]) && !empty($_FILES['userfile']['name'])) {
                    $uploaddir = './uploads/staff_documents/' . $staff_id . '/';
                    if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                        die("Error creating folder $uploaddir");
                    }
                    $fileInfo = pathinfo($_FILES["userfile"]["name"]);
                    $document = time() . '.' . $fileInfo['extension'];

                    move_uploaded_file($_FILES["userfile"]["tmp_name"], './uploads/staff_documents/' . $staff_id . '/' . $document);
                } else {

                    $document = $this->input->post("filename");
                }



                if (!empty($request_id)) {


                    $data = array('id' => $request_id,
                        'staff_id' => $staff_id,
                        'date' => date('Y-m-d', $this->customlib->datetostrtotime($applied_date)),
                        'leave_type_id' => $leavetype,
                        'leave_days' => $leave_days,
                        'leave_from' => $leavefrom,
                        'leave_to' => $leaveto, 'employee_remark' => $reason, 'status' => $status, 'admin_remark' => $remark, 'applied_by' => $applied_by, 'document_file' => $document);
                } else {

                    $data = array('staff_id' => $staff_id, 'date' => date("Y-m-d", $this->customlib->datetostrtotime($applied_date)), 'leave_days' => $leave_days, 'leave_type_id' => $leavetype, 'leave_from' => $leavefrom, 'leave_to' => $leaveto, 'employee_remark' => $reason, 'status' => $status, 'admin_remark' => $remark, 'applied_by' => $applied_by, 'document_file' => $document,'session_id' => $this->setting_model->getCurrentSession(),);
                }



                $this->leaverequest_model->addLeaveRequest($data);
                $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            } else {
                $msg = array(
                    'applieddate' => $this->lang->line('selected') . " " . $this->lang->line('leave') . " " . $this->lang->line('days') . " > " . $this->lang->line('available') . " " . $this->lang->line('leaves'),
                );

                $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
            }
          
        }
          echo json_encode($array);
    }

    public function add_staff_leave() {

        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        $userdata = $this->customlib->getUserData();
        $applied_date = $this->input->post("applieddate");
        //$leavetype = $this->input->post("leave_type");

        $reason = $this->input->post("reason");
        $remark = '';
        $status = 'pending';
        $request_id = $this->input->post("leaverequestid");


        $this->form_validation->set_rules('applieddate', $this->lang->line('applied_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('leave_from_date', $this->lang->line('leave')." ".$this->lang->line('from')." ".$this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('leave_to_date', $this->lang->line('leave')." ".$this->lang->line('to')." ".$this->lang->line('date'), 'trim|required|xss_clean');
        //$this->form_validation->set_rules('leave_type', $this->lang->line('available') . " " . $this->lang->line('leave'), 'trim|required|xss_clean');
        //$this->form_validation->set_rules('userfile', $this->lang->line('file'), 'callback_handle_upload[userfile]');

        if ($this->form_validation->run() == FALSE) {


            $msg = array(
                'applieddate' => form_error('applieddate'),
                'leave_from_date' => form_error('leave_from_date'),
                'leave_to_date' => form_error('leave_to_date'),
                //'leave_type' => form_error('leave_type'),
				//'userfile' => form_error('userfile'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            
            $leavetype='';
            $leavefrom = date("Y-m-d", $this->customlib->datetostrtotime($this->input->post('leave_from_date')));
            $leaveto = date("Y-m-d", $this->customlib->datetostrtotime($this->input->post('leave_to_date')));

            $staff_id = $userdata["id"];
            $applied_by = $this->customlib->getAdminSessionUserName();
            $leave_days = $this->dateDifference($leavefrom, $leaveto);
            $my_laeve = $this->leaverequest_model->myallotedLeaveType($staff_id, $leavetype);

            // if ($my_laeve['alloted_leave'] >= $leave_days) {
            if ($staff_id != '') {

                if (isset($_FILES["userfile"]) && !empty($_FILES['userfile']['name'])) {
                    $uploaddir = './uploads/staff_documents/' . $staff_id . '/';
                    if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                        die("Error creating folder $uploaddir");
                    }
                    $fileInfo = pathinfo($_FILES["userfile"]["name"]);
                    $document = basename($_FILES['userfile']['name']);
                    $img_name = $uploaddir . basename($_FILES['userfile']['name']);
                    move_uploaded_file($_FILES["userfile"]["tmp_name"], $img_name);
                } else {

                    $document = $this->input->post("filename");
                }

                if ($this->input->post('is_half_day') == 1) {
                    $leave_days = $leave_days - 0.5;
                }
                if (!empty($request_id)) {
                    $leavetype=1;
                    $data = array('id' => $request_id,
                        'staff_id' => $staff_id,
                        'date' => date('Y-m-d', $this->customlib->datetostrtotime($applied_date)),
                        'leave_type_id' => $leavetype,
                        'leave_days' => $leave_days,
                        'leave_from' => $leavefrom,
                        'leave_to' => $leaveto, 'employee_remark' => $reason, 'status' => $status, 'admin_remark' => $remark, 'applied_by' => $applied_by, 'document_file' => $document);
                } else {

                    $data = array('staff_id' => $staff_id, 'date' => date("Y-m-d", $this->customlib->datetostrtotime($applied_date)), 'leave_days' => $leave_days, 'leave_type_id' => $leavetype, 'leave_from' => $leavefrom, 'leave_to' => $leaveto, 'employee_remark' => $reason, 'status' => $status, 'admin_remark' => $remark, 'applied_by' => $applied_by, 'document_file' => $document);
                    
                }
                
                $this->leaverequest_model->addLeaveRequest($data);

                $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            } else {
                $msg = array(
                    'applieddate' => $this->lang->line('selected') . " " . $this->lang->line('leave') . " " . $this->lang->line('days') . " > " . $this->lang->line('available') . " " . $this->lang->line('leaves'),
                );

                $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
            }
        }
        die();
        echo json_encode($array);
    }

  

    public function handle_upload($str,$var)
    {

        $image_validate = $this->config->item('file_validate');
        $result = $this->filetype_model->get();
        if (isset($_FILES[$var]) && !empty($_FILES[$var]['name'])) {

            $file_type         = $_FILES[$var]['type'];
            $file_size         = $_FILES[$var]["size"];
            $file_name         = $_FILES[$var]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($files = filesize($_FILES[$var]['tmp_name'])) {

                if (!in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', 'File Type Not Allowed');
                    return false;
                }

                if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', 'Extension Not Allowed');
                    return false;
                }
                if ($file_size > $result->file_size) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($image_validate['upload_size'] / 1048576, 2) . " MB");
                    return false;
                }

            } else {
                $this->form_validation->set_message('handle_upload', "File Type / Extension Error Uploading ");
                return false;
            }

            return true;
        }
        return true;

    }

    public function add_staff_leave_office() {

        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
       
        $userdata = $this->customlib->getUserData();
        $applied_date = $this->input->post("applieddate");
        //$leavetype = $this->input->post("leave_type");

        $reason = $this->input->post("reason");
        $remark = '';
        $status = 'pending';
        $request_id = $this->input->post("leaverequestid");


        $this->form_validation->set_rules('applieddate', $this->lang->line('applied_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('leave_from_date', $this->lang->line('leave')." ".$this->lang->line('from')." ".$this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('leave_to_date', $this->lang->line('leave')." ".$this->lang->line('to')." ".$this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('staff_id', $this->lang->line('staff'), 'trim|required|xss_clean');
        //$this->form_validation->set_rules('leave_type', $this->lang->line('available') . " " . $this->lang->line('leave'), 'trim|required|xss_clean');
        //$this->form_validation->set_rules('userfile', $this->lang->line('file'), 'callback_handle_upload[userfile]');

        if ($this->form_validation->run() == FALSE) {


            $msg = array(
                'applieddate' => form_error('applieddate'),
                'leave_from_date' => form_error('leave_from_date'),
                'leave_to_date' => form_error('leave_to_date'),
                //'leave_type' => form_error('leave_type'),
				//'userfile' => form_error('userfile'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            
            $leavetype= $this->input->post("leave_type");
            $leavefrom = date("Y-m-d", $this->customlib->datetostrtotime($this->input->post('leave_from_date')));
            $leaveto = date("Y-m-d", $this->customlib->datetostrtotime($this->input->post('leave_to_date')));

            $staff_id = $this->input->post("staff_id");
            $applied_by = $this->customlib->getAdminSessionUserName();
            $leave_days = $this->dateDifference($leavefrom, $leaveto);
            // $my_laeve = $this->leaverequest_model->myallotedLeaveType($staff_id, $leavetype);

            // if ($my_laeve['alloted_leave'] >= $leave_days) {
            if ($staff_id != '') {

                if (isset($_FILES["userfile"]) && !empty($_FILES['userfile']['name'])) {
                    $uploaddir = './uploads/staff_documents/' . $staff_id . '/';
                    if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                        die("Error creating folder $uploaddir");
                    }
                    $fileInfo = pathinfo($_FILES["userfile"]["name"]);
                    $document = basename($_FILES['userfile']['name']);
                    $img_name = $uploaddir . basename($_FILES['userfile']['name']);
                    move_uploaded_file($_FILES["userfile"]["tmp_name"], $img_name);
                } else {

                    $document = $this->input->post("filename");
                }
                
                if ($this->input->post('is_half_day') == 1) {

                    $leave_days = $leave_days - 0.5;
                }
                // echo "<pre>";
                // print_r ($this->input->post());die;
                // echo "</pre>";
                
                // if (!empty($request_id)) {
                //     $data = array('id' => $request_id,
                //         'staff_id' => $staff_id,
                //         'date' => date('Y-m-d', $this->customlib->datetostrtotime($applied_date)),
                //         'leave_type_id' => $leavetype,
                //         'leave_days' => $leave_days,
                //         'leave_from' => $leavefrom,
                //         'leave_to' => $leaveto, 'employee_remark' => $reason, 'status' => $status, 'admin_remark' => $remark, 'applied_by' => $applied_by, 'document_file' => $document);
                // } else {

                //     $data = array('staff_id' => $staff_id, 'date' => date("Y-m-d", $this->customlib->datetostrtotime($applied_date)), 'leave_days' => $leave_days, 'leave_type_id' => $leavetype, 'leave_from' => $leavefrom, 'leave_to' => $leaveto, 'employee_remark' => $reason, 'status' => $status, 'admin_remark' => $remark, 'applied_by' => $applied_by, 'document_file' => $document);
                // }
                
                // $this->leaverequest_model->addLeaveRequest($data);
                $insertArr =  array(
                    'staff_id' => $staff_id,
                    'date' => date('Y-m-d', $this->customlib->datetostrtotime($applied_date)),
                    'leave_days' => $leave_days,
                    // 'leave_type_id' => $value,
                    'leave_from' => $leavefrom,
                    'leave_to' => $leaveto,
                    'employee_remark' => $reason,
                    'status' => $status,
                    'admin_remark' => $remark,
                    'applied_by' => $applied_by,
                    'document_file' => $document,
                    'session_id' => $this->setting_model->getCurrentSession(),
                );
                
                $insert_id = $this->leaverequest_model->addLeaveRequest($insertArr);

                if ($this->input->post('other_leave_type') != '') {
                    $other_leave = $this->input->post('other_leave_type');
                } else {
                    $other_leave = $this->input->post('leave_type');
                }
                

                if (!empty($other_leave)) {
                    $i = 0;
                    foreach ($other_leave as $key => $value) {
                        if ($this->input->post('other_days') != '') {
                           $days = $this->input->post('other_days')[$i];
                        } else {
                           $days = $leave_days;
                        }

                        if ($this->input->post('other_from_date') != '') {
                            $leavefrom = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('other_from_date')[$i]));
                            $leaveto = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('other_to_date')[$i]));
                        } else {
                            $leavefrom = $leavefrom;
                            $leaveto = $leaveto;
                        }
                        

                        $insertSubArr =  array(
                            'leave_request_id' => $insert_id,
                            'staff_id' => $staff_id,
                            'from_date' => $leavefrom,
                            'to_date' => $leaveto,
                            'leave_type_id' => $value,
                            'days' => $days,
                            'created_at' => date('Y-m-d H:i:s')
                        );

                        $this->leaverequest_model->addLeaveRequestSub($insertSubArr);
                        
                        $i++;
                    }

                    if (isset($insert_id)) {
                        $staffRow = $this->staff_model->getStaffRecord($staff_id,'name,surname,contact_no');
                        $staff_name = $staffRow['name'].' '.$staffRow['surname'];
                        $sendArray = array(
                            'name' => $staff_name,
                            'from_date' => $leavefrom,
                            'to_date' => $leaveto,
                            'mobno' => 8286006099,//$staffRow['contact_no'],
                        );
                        // $this->wati_model->send_leave_apply_msg($sendArray);
                        // Principle 
                        // $principle = $this->staff_model->getPrincipal();
                        // $principleArr = ['name' =>$staff_name, 'from_date' => $leavefrom, 'to_date' => $leaveto, 'mobno' => 8286006099];
                        // $this->wati_model->send_leave_apply_msg($principleArr);
                    }
                }

                $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            } else {
                $msg = array(
                    'applieddate' => $this->lang->line('selected') . " " . $this->lang->line('leave') . " " . $this->lang->line('days') . " > " . $this->lang->line('available') . " " . $this->lang->line('leaves'),
                );

                $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
            }
        }
        echo json_encode($array);
    }
   
    
}

?>