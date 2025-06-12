<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Generatecertificate extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('Customlib');
        $this->sch_setting_detail = $this->setting_model->getSetting();

        $this->load->model('certificate_model');
        
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('generate_certificate', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Certificate');
        $this->session->set_userdata('sub_menu', 'admin/generatecertificate');

        $certificateList         = $this->Certificate_model->getstudentcertificate();
        $data['certificateList'] = $certificateList;
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/certificate/generatecertificate', $data);
        $this->load->view('layout/footer', $data);
    }

    public function search()
    {
        $this->session->set_userdata('top_menu', 'Certificate');
        $this->session->set_userdata('sub_menu', 'admin/generatecertificate');

        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $certificateList         = $this->Certificate_model->getstudentcertificate();
        $data['certificateList'] = $certificateList;
        $button                  = $this->input->post('search');
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/certificate/generatecertificate', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class       = $this->input->post('class_id');
            $section     = $this->input->post('section_id');
            $search      = $this->input->post('search');
            $certificate = $this->input->post('certificate_id');
            if (isset($search)) {
                $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');

                $this->form_validation->set_rules('certificate_id', $this->lang->line('certificate'), 'trim|required|xss_clean');
                if ($this->form_validation->run() == false) {

                } else {
                    $data['searchby']          = "filter";
                    $data['class_id']          = $this->input->post('class_id');
                    $data['section_id']        = $this->input->post('section_id');
                    $certificate               = $this->input->post('certificate_id');
                    $certificateResult         = $this->Generatecertificate_model->getcertificatebyid($certificate);
                    $data['certificateResult'] = $certificateResult;
                    $resultlist                = $this->student_model->searchByClassSection($class, $section);
                    $data['resultlist']        = $resultlist;
                    $title                     = $this->classsection_model->getDetailbyClassSection($data['class_id'], $data['section_id']);
                    $data['title']             = $this->lang->line('std_dtl_for') . ' ' . $title['class'] . "(" . $title['section'] . ")";
                }
            }
            $data['sch_setting'] = $this->sch_setting_detail;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/certificate/generatecertificate', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function generate($student, $class, $certificate)
    {
        $certificateResult         = $this->Generatecertificate_model->getcertificatebyid($certificate);
        $data['certificateResult'] = $certificateResult;
        $resultlist                = $this->student_model->searchByClassStudent($class, $student);
        $data['resultlist']        = $resultlist;

        $this->load->view('admin/certificate/transfercertificate', $data);
    }

    public function generatemultiple()
    {

        $studentid           = $this->input->post('data');
        $student_array       = json_decode($studentid);
        $certificate_id      = $this->input->post('certificate_id');
        $class               = $this->input->post('class_id');
        $data                = array();
        $results             = array();
        $std_arr             = array();
        $data['sch_setting'] = $this->setting_model->get();
        $setting_result                 = $this->setting_model->get();
        $data['settinglist']            = $setting_result[0];
        $data['certificate'] = $this->Generatecertificate_model->getcertificatebyid($certificate_id);

        foreach ($student_array as $key => $value) {
            $std_arr[] = $value->student_id;
        }
        $data['students'] = $this->student_model->getStudentsByArray($std_arr);
        foreach ($data['students'] as $key => $value) {
            $data['students'][$key]->name = $this->customlib->getFullName($value->firstname, $value->middlename, $value->lastname, $this->sch_setting_detail->middlename, $this->sch_setting_detail->lastname);
        }

        $data['sch_setting'] = $this->sch_setting_detail;
        $certificates        = $this->load->view('admin/certificate/printcertificate', $data, true);
        echo $certificates;
    }

    public function bonafide()
    {
        if (!$this->rbac->hasPrivilege('generate_bonafide', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Certificate');
        $this->session->set_userdata('sub_menu', 'admin/generatecertificate/generate_bonafide');
        $this->data = "";

        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $session = $this->session_model->get();
        $data['sessionlist'] = $session;
        $data['resultlist'] = $this->certificate_model->getBonafide();
        $this->load->view('layout/header');
        $this->load->view('admin/certificate/bonafide_list', $data);
        $this->load->view('layout/footer');
    }

    public function generate_bonafide()
    {
        $this->session->set_userdata('top_menu', 'Certificate');
        $this->session->set_userdata('sub_menu', 'admin/generatecertificate/generate_bonafide');

        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $certificateList         = $this->Certificate_model->getstudentcertificate();
        $data['certificateList'] = $certificateList;
        $button                  = $this->input->post('search');
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/certificate/generate_bonafide', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class       = $this->input->post('class_id');
            $section     = $this->input->post('section_id');
            $search      = $this->input->post('search');
            $certificate = $this->input->post('certificate_id');
            if (isset($search)) {
                $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');

                $this->form_validation->set_rules('certificate_id', $this->lang->line('certificate'), 'trim|required|xss_clean');
                if ($this->form_validation->run() == false) {

                } else {
                    $data['searchby']          = "filter";
                    $data['class_id']          = $this->input->post('class_id');
                    $data['section_id']        = $this->input->post('section_id');
                    $certificate               = $this->input->post('certificate_id');
                    $certificateResult         = $this->Generatecertificate_model->getcertificatebyid($certificate);
                    $data['certificateResult'] = $certificateResult;
                    $resultlist                = $this->student_model->searchByClassSection($class, $section);
                    $data['resultlist']        = $resultlist;
                    $title                     = $this->classsection_model->getDetailbyClassSection($data['class_id'], $data['section_id']);
                    $data['title']             = $this->lang->line('std_dtl_for') . ' ' . $title['class'] . "(" . $title['section'] . ")";
                }
            }
            $data['sch_setting'] = $this->sch_setting_detail;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/certificate/generate_bonafide', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function generatemultiple_bonafide()
    {

        $studentid           = $this->input->post('data');
        $student_array       = json_decode($studentid);
        $certificate_id      = $this->input->post('certificate_id');
        $class               = $this->input->post('class_id');
        $data                = array();
        $results             = array();
        $std_arr             = array();
        $data['sch_setting'] = $this->setting_model->get();
        $setting_result                 = $this->setting_model->get();
        $data['settinglist']            = $setting_result[0];
        $data['certificate'] = $this->Generatecertificate_model->getcertificatebyid($certificate_id);

        foreach ($student_array as $key => $value) {
            $std_arr[] = $value->student_id;
        }
        $data['students'] = $this->student_model->getStudentsByArray($std_arr);

        $feesrow = $this->certificate_model->getbonafide_trnRow();
        $count = !empty($feesrow) ? $feesrow['srno'] + 1 : 1 ;
        foreach ($data['students'] as $key => $value) {
           
            $data['students'][$key]->name = $this->customlib->getFullName($value->firstname, $value->middlename, $value->lastname, $this->sch_setting_detail->middlename, $this->sch_setting_detail->lastname);
            $current_session_id = $this->setting_model->getCurrentSession();
            $userdata = $this->customlib->getUserData();
            
            $q = $this->db->get('bonafide_trn')->num_rows();
            $arrayBonafide = array(
                'session_id'            => $current_session_id,
                'student_session_id'    => $value->student_session_id ,
                'bt_date'               => date('Y-m-d'),
                'status'                => 1,
                'created_on'            => date('Y-m-d H:i:s') ,
                'created_by'            => $userdata['name'],
                'srno'                  => $count,
            );

            $insert_id = $this->certificate_model->addBonafide($arrayBonafide);
            ++$count;
        }

        $data['sch_settingdata'] = $this->setting_model->get();
        $certificates        = $this->load->view('admin/certificate/printbonafide', $data, true);
        echo $certificates;
    }

    public function deleteBonafide($id)
    {
        if (!$this->rbac->hasPrivilege('generate_bonafide', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'tc';

        $this->certificate_model->remove_bonafide($id);


        redirect('admin/generatecertificate/bonafide');
    }

    public function print_bonafide_certificate()
    {
        $data['id'] =  $this->input->post('student_id');
        $setting_result        = $this->setting_model->get();
        $data['settinglist']   = $setting_result[0];
        $data['sch_setting'] = $this->setting_model->get();
        $session_id = $this->setting_model->getCurrentSession();
        $data['students'] = $this->student_model->getbonafideresult_id($data['id']);
        
        $data['bonfide'] = $this->certificate_model->getBonafide($data['id']);
        $this->load->view('admin/certificate/printbonafide', $data);
    }

}
