<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Generateidcard extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('Customlib');
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function search()
    {
        if (!$this->rbac->hasPrivilege('generate_id_card', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Certificate');
        $this->session->set_userdata('sub_menu', 'admin/generateidcard');

        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $data['sch_setting']     = $this->sch_setting_detail;
        $idcardlist              = $this->Generateidcard_model->getstudentidcard();
        $data['idcardlist']      = $idcardlist;
        $button                  = $this->input->post('search');
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/certificate/generateidcard', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class   = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $search  = $this->input->post('search');
            $id_card = $this->input->post('id_card');
            if (isset($search)) {
                $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');

                $this->form_validation->set_rules('id_card', $this->lang->line('id_card_template'), 'trim|required|xss_clean');
                if ($this->form_validation->run() == false) {

                } else {
                    $data['searchby']     = "filter";
                    $data['class_id']     = $this->input->post('class_id');
                    $data['section_id']   = $this->input->post('section_id');
                    $id_card              = $this->input->post('id_card');
                    $idcardResult         = $this->Generateidcard_model->getidcardbyid($id_card);
                    $data['idcardResult'] = $idcardResult;
                    $resultlist           = $this->student_model->searchByClassSection($class, $section);
                    $data['resultlist']   = $resultlist;
                    $title                = $this->classsection_model->getDetailbyClassSection($data['class_id'], $data['section_id']);
                    $data['title']        = 'Student Details for ' . $title['class'] . "(" . $title['section'] . ")";
                }
            }

            $this->load->view('layout/header', $data);
            $this->load->view('admin/certificate/generateidcard', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function generate($student, $class, $idcard)
    {

        $idcardlist         = $this->Generateidcard_model->getidcardbyid($idcard);
        $data['idcardlist'] = $idcardlist;
        $resultlist         = $this->student_model->searchByClassStudent($class, $student);
        $data['resultlist'] = $resultlist;

        $this->load->view('admin/certificate/studentidcard', $data);
    }

    public function generatemultiple()
    {

        $studentid           = $this->input->post('data');
        $student_array       = json_decode($studentid);
        $idcard              = $this->input->post('id_card');
        $class               = $this->input->post('class_id');
        $data                = array();
        $results             = array();
        $std_arr             = array();
        $data['sch_setting'] = $this->setting_model->get();
        $data['id_card']     = $this->Generateidcard_model->getidcardbyid($idcard);

        foreach ($student_array as $key => $value) {
            $std_arr[] = $value->student_id;
        }

        $data['students']        = $this->student_model->getStudentsByArray($std_arr);
        $data['sch_settingdata'] = $this->sch_setting_detail;

        $id_cards = $this->load->view('admin/certificate/idcard', $data, true);
        echo json_encode(array('status' => 1, 'page' => $id_cards));
    }

    public function generatemultiple_pdf()
    {
        
        // echo "<pre>";
        // print_r ($this->input->post());die;
        // echo "</pre>";
        ini_set('display_errors', '1');

        $studentid           = $this->input->post('check');
        $student_array       = $studentid;
        // echo "<pre>";
        // print_r($student_array);die;
        // echo "</pre>";
        $idcard              = $this->input->post('id_card');
        $data                = array();
        $std_arr             = array();
        $data['sch_setting'] = $this->setting_model->get();
        $data['id_card']     = $this->Generateidcard_model->getidcardbyid($idcard);

        foreach ($student_array as $key => $value) {
            $std_arr[] = $value;
        }

        $data['students']        = $this->student_model->getStudentsByArray($std_arr);
        $data['sch_settingdata'] = $this->sch_setting_detail;
        $html = $this->load->view('admin/certificate/idcard_pdf', $data,true);
        // echo $html;die;

        $this->load->library('pdf');
        $this->pdf->show($html, 'sample.pdf');
        
    }

    public function generatemultipleview()
    {

        $studentid           = $this->input->post('data');
        $student_array       = json_decode($studentid);
        $idcard              = $this->input->post('id_card');
        $class               = $this->input->post('class_id');
        $data                = array();
        $results             = array();
        $std_arr             = array();
        $data['sch_setting'] = $this->setting_model->get();
        $data['id_card']     = $this->Generateidcard_model->getidcardbyid($idcard);

        foreach ($student_array as $key => $value) {
            $std_arr[] = $value->student_id;
        }

        $data['students']        = $this->student_model->getStudentsByArray($std_arr);
        $data['sch_settingdata'] = $this->sch_setting_detail;

        $id_cards = $this->load->view('admin/certificate/idcard', $data, true);
        echo json_encode(array('status' => 1, 'page' => $id_cards));
    }

    public function generatemultipleCount()
    {
        
       
        $studentid           = $this->input->post('data');
        $student_array       = json_decode($studentid);
        $std_arr             = array();
        foreach ($student_array as $key => $value) {
            $std_arr[] = $value->student_id;
            $studentSession = $this->studentsession_model->searchActiveClassSectionStudent($value->student_id);
            $count = $studentSession->id_prn_cnt + 1;
            $array = array(
                'id'=> $studentSession->id,
                'id_prn_cnt'=> $count,
            );

            $this->studentsession_model->updateById($array);

        }

        echo json_encode(array('status' => 1));
    }

}
