<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Classes extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('class', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'classes/index');
        $data['title']      = 'Add Class';
        $data['title_list'] = 'Class List';

        $this->form_validation->set_rules(
            'class',
            $this->lang->line('class'),
            array(
                'required',
                array('class_exists', array($this->class_model, 'class_exists')),
            )
        );
        $this->form_validation->set_rules('code', $this->lang->line('code'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('sections[]', $this->lang->line('section'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
        } else {
            $class       = $this->input->post('class');
            $class_array = array(
                'class' => $this->input->post('class'),
                'code' => $this->input->post('code'),
                'sch_section_id' => $this->input->post('sch_section_id'),
            );
            $sections = $this->input->post('sections');
            $this->classsection_model->add($class_array, $sections);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('classes');
        }
        $vehicle_result       = $this->section_model->get();
        $data['vehiclelist']  = $vehicle_result;
        $vehroute_result      = $this->classsection_model->getByID();
        $data['vehroutelist'] = $vehroute_result;
        $data['sch_section_result']      = $this->examgroup_model->getsch_section();
        $this->load->view('layout/header', $data);
        $this->load->view('class/classList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('class', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Fees Master List';
        $this->class_model->remove($id);

        $student_delete = $this->student_model->getUndefinedStudent();
        if (!empty($student_delete)) {
            $delte_student_array = array();
            foreach ($student_delete as $student_key => $student_value) {

                $delte_student_array[] = $student_value->id;
            }
            $this->student_model->bulkdelete($delte_student_array);
        }


        redirect('classes');
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('class', 'can_edit')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'classes/index');
        $data['title']      = 'Edit Class';
        $data['id']         = $id;
        $vehroute           = $this->classsection_model->getByID($id);
        $data['sch_section_result']      = $this->examgroup_model->getsch_section();
        
        $data['vehroute']   = $vehroute;
        $data['title_list'] = 'Fees Master List';

        $this->form_validation->set_rules(
            'class',
            $this->lang->line('class'),
            array(
                'required',
                array('class_exists', array($this->class_model, 'class_exists')),
            )
        );
        $this->form_validation->set_rules('sections[]', $this->lang->line('sections'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $vehicle_result       = $this->section_model->get();
            $data['vehiclelist']  = $vehicle_result;
            $routeList            = $this->route_model->get();
            $data['routelist']    = $routeList;
            $vehroute_result      = $this->classsection_model->getByID();
            $data['vehroutelist'] = $vehroute_result;
            $this->load->view('layout/header', $data);
            $this->load->view('class/classEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $sections      = $this->input->post('sections');
            $prev_sections = $this->input->post('prev_sections');
            $route_id      = $this->input->post('route_id');
            $class_id      = $this->input->post('pre_class_id');
            if (!isset($prev_sections)) {
                $prev_sections = array();
            }
            $add_result    = array_diff($sections, $prev_sections);
            $delete_result = array_diff($prev_sections, $sections);
            if (!empty($add_result)) {
                $vehicle_batch_array = array();
                $class_array         = array(
                    'id'    => $class_id,
                    'class' => $this->input->post('class'),
                    'code' => $this->input->post('code'),
                    'sch_section_id' => $this->input->post('sch_section_id'),
                );
                foreach ($add_result as $vec_add_key => $vec_add_value) {
                    $vehicle_batch_array[] = $vec_add_value;
                }
                $this->classsection_model->add($class_array, $vehicle_batch_array);
            } else {
                $class_array = array(
                    'id'    => $class_id,
                    'class' => $this->input->post('class'),
                    'code' => $this->input->post('code'),
                    'sch_section_id' => $this->input->post('sch_section_id'),
                );
                $this->classsection_model->update($class_array);
            }

            if (!empty($delete_result)) {
                $classsection_delete_array = array();
                foreach ($delete_result as $vec_delete_key => $vec_delete_value) {
                    $classsection_delete_array[] = $vec_delete_value;
                }

                $this->classsection_model->remove($class_id, $classsection_delete_array);
            }

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('classes/index');
        }
    }

    public function get_section($id)
    {
        $data['sections'] = $this->class_model->get_section($id);
        $this->load->view('class/_section_list', $data);
    }

    public function getClass()
    {
        $class_id = $this->input->get('class_id');
        $data['class'] = $this->class_model->get_class($class_id);
        echo json_encode($data);
    }

    public function getclassBySchsection()
    {
        ini_set('display_errors', '1');
        $sch_section = $this->input->post('sch_section');
        $class_array = $this->classsection_model->getClassSectionBySection($sch_section);
        // echo $this->db->last_query();die;
        
        

        $html = "";
        if (!empty($class_array)) {
            foreach ($class_array as $key => $value) {
                $html .= "<tr>";
                $html .= "<td><input type='checkbox' class='itemCheckbox' name='class_section[]' value='" . $value->class_section_id . "'></td>";
                $html .= "<td>" . $value->class."(".$value->section.")" . "</td>";
                $html .= "</tr>";
            }
        }
        echo json_encode($html);
    }
}
