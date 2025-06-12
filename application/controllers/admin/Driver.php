<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Driver extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('driver_model');
        $this->load->model('driver_transfer_model');
    }

    public function index($id='')
    {
        if (!$this->rbac->hasPrivilege('driver', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Transport');
        $this->session->set_userdata('sub_menu', 'driver/index');
        if($id!=''){
            $data['title'] = 'Update Driver';
            $data['id'] = $id;
            $data['update'] = $this->driver_model->get($id);
        } else{
            $data['title'] = 'Add Driver';
        }
        $driverList = $this->driver_model->get();
        $data['driverList'] = $driverList;
        $this->form_validation->set_rules('driver_name', $this->lang->line('driver_name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('driver_license_no', $this->lang->line('driver_license_no'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('driver_mobileno', $this->lang->line('driver_mobileno'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('driver_address', $this->lang->line('driver_address'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {

            $this->load->view('layout/header');
            $this->load->view('admin/driver/index', $data);
            $this->load->view('layout/footer');
        } else {
            $driverData = array(
                'id' => $this->input->post('id'),
                'driver_name' => $this->input->post('driver_name'),
                'driver_license_no' => $this->input->post('driver_license_no'),
                'driver_mobileno' => $this->input->post('driver_mobileno'),
                'driver_address' => $this->input->post('driver_address'),
            );
            if($this->input->post('id') == ''){
                $driverData['created_at'] = date('Y-m-d H:i:s');
            } else{
                $driverData['update_at'] = date('Y-m-d H:i:s');
            }
            // echo '<pre>'; print_r($driverData); die();
            $this->driver_model->add($driverData);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/driver/index');
        }

    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('driver', 'can_delete')) {
            access_denied();
        }
        $this->driver_model->remove($id);
        redirect('admin/driver/index');
    }

    public function assign_driver($id='')
    {
        if (!$this->rbac->hasPrivilege('assign_driver', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Transport');
        $this->session->set_userdata('sub_menu', 'driver/assign_driver');

        if($id!=''){
            $data['title'] = 'Update Assign Driver';
            $data['id'] = $id;
            $data['update'] = $this->driver_transfer_model->get($id);
        } else{
            $data['title'] = 'Add Assign Driver';
        }
        $data['driverList'] = $this->driver_model->get();
        $data['vehicleList'] = $this->vehicle_model->get();
        $data['assDriverList'] = $this->driver_transfer_model->get();

        $this->form_validation->set_rules('driver_id', $this->lang->line('driver'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('vehicle_id', $this->lang->line('vehicle'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('start_date', $this->lang->line('date'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {

            $this->load->view('layout/header');
            $this->load->view('admin/driver/assign_driver', $data);
            $this->load->view('layout/footer');
        } else {
            $driverTranData = array(
                'id' => $this->input->post('id'),
                'start_date' => date('Y-m-d',strtotime($this->input->post('start_date'))),
                'driver_id' => $this->input->post('driver_id'),
                'vehicle_id' => $this->input->post('vehicle_id'),
            );
            $this->driver_transfer_model->add($driverTranData);
            $vehicleData = array(
                'id' => $this->input->post('vehicle_id'),
                'current_driver' => $this->input->post('driver_id'),
            );
            $this->vehicle_model->add($vehicleData);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/driver/assign_driver');
        }

    }
}