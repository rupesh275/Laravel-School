<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Student extends Admin_Controller
{
    public $sch_setting_detail = array();
    public function __construct()
    {
        parent::__construct();
        $this->config->load('app-config');
        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');
        $this->load->library('encoding_lib');
        $this->load->model("classteacher_model");
        $this->load->model(array("timeline_model", "student_edit_field_model"));
        $this->blood_group        = $this->config->item('bloodgroup');
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->role;
    }
    public function index()
    {

        $data['title']       = 'Student List';
        $student_result      = $this->student_model->get();
        $data['studentlist'] = $student_result;
        $this->load->view('layout/header', $data);
        $this->load->view('student/studentList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function multiclass()
    {
        if (!$this->rbac->hasPrivilege('multi_class_student', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'student/multiclass');
        $data['title']       = 'student fees';
        $data['title']       = 'student fees';
        $class               = $this->class_model->get();
        $data['classlist']   = $class;
        $data['sch_setting'] = $this->sch_setting_detail;

        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
        } else {
            $class                   = $this->class_model->get();
            $data['classlist']       = $class;
            $data['student_due_fee'] = array();
            $class_id                = $this->input->post('class_id');
            $section_id              = $this->input->post('section_id');
            $classes                 = $this->classsection_model->allClassSections();

            $data['classes'] = $classes;

            $students         = $this->studentsession_model->searchMultiStudentByClassSection($class_id, $section_id);
            $data['students'] = $students;
        }
        $this->load->view('layout/header', $data);
        $this->load->view('student/multiclass', $data);
        $this->load->view('layout/footer', $data);
    }

    public function classsectionreport()
    {
        if (!$this->rbac->hasPrivilege('student_report', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/student_information');
        $this->session->set_userdata('subsub_menu', 'Reports/student_information/classsectionreport');
        $data['title']              = 'Class & Section Report';
        $data['class_section_list'] = $this->classsection_model->getClassSectionStudentCount();

        $this->load->view('layout/header', $data);
        $this->load->view('reports/classsectionreport', $data);
        $this->load->view('layout/footer', $data);
    }

    public function studentreport()
    {
        if (!$this->rbac->hasPrivilege('student_report', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/student_information');
        $this->session->set_userdata('subsub_menu', 'Reports/student_information/student_report');
        $data['title']           = 'student fee';
        $data['title']           = 'student fee';
        $genderList              = $this->customlib->getGender();
        $data['genderList']      = $genderList;
        $RTEstatusList           = $this->customlib->getRteStatus();
        $data['RTEstatusList']   = $RTEstatusList;
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $data['sch_setting']     = $this->sch_setting_detail;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $userdata                = $this->customlib->getUserData();
        $category                = $this->category_model->get();
        $data['categorylist']    = $category;
        $this->load->view('layout/header', $data);
        $this->load->view('student/studentReport', $data);
        $this->load->view('layout/footer', $data);
    }

    public function download($student_id, $doc)
    {
        $this->load->helper('download');
        $filepath = "./uploads/student_documents/$student_id/" . $this->uri->segment(4);
        $data     = file_get_contents($filepath);
        $name     = $this->uri->segment(6);
        force_download($name, $data);
    }

    public function view($id)
    {

        if (!$this->rbac->hasPrivilege('student', 'can_view')) {
            access_denied();
        }
        // ini_set('display_errors', 1);

        $data['title']         = 'Student Details';
        $student               = $this->student_model->get($id);
        $gradeList             = $this->grade_model->get();
        $studentSession        = $this->student_model->getStudentSession($id);
        $timeline              = $this->timeline_model->getStudentTimeline($id, $status = '');
        $data["timeline_list"] = $timeline;

        $student_session_id = $studentSession["student_session_id"];

        $student_session         = $studentSession["session"];
        $data['sch_setting']     = $this->sch_setting_detail;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $current_student_session = $this->student_model->get_studentsession($student['student_session_id']);

        $data["session"]              = $current_student_session["session"];
        $student_due_fee              = $this->studentfeemaster_model->getStudentFees($student['student_session_id']);
        $student_discount_fee         = $this->feediscount_model->getStudentFeesDiscount($student['student_session_id']);
        $data['student_discount_fee'] = $student_discount_fee;
        $data['student_due_fee']      = $student_due_fee;
        $siblings                     = $this->student_model->getMySiblings($student['parent_id'], $student['id']);

        $student_doc = $this->student_model->getstudentdoc($id);

        $data['student_doc']    = $student_doc;
        $data['student_doc_id'] = $id;
        $category_list          = $this->category_model->get();
        $data['category_list']  = $category_list;
        $data['gradeList']      = $gradeList;
        $data['student']        = $student;
        $data['siblings']       = $siblings;
        $class_section          = $this->student_model->getClassSection($student["class_id"]);
        $data["class_section"]  = $class_section;
        $session                = $this->setting_model->getCurrentSession();

        $studentlistbysection         = $this->student_model->getStudentClassSection($student["class_id"], $session);
        $data["studentlistbysection"] = $studentlistbysection;

        $data['guardian_credential'] = $this->student_model->guardian_credential($student['parent_id']);

        $data['reason'] = $this->disable_reason_model->get();
        $data['reason_data'] = [];
        if ($student['is_active'] = 'no') {
            $data['reason_data'] = $this->disable_reason_model->get($student['dis_reason']);
        }
        $data['exam_result'] = $this->examgroupstudent_model->searchStudentExams($student['student_session_id'], true, true);
        $data['exam_grade']  = $this->grade_model->getGradeDetails();

        $this->load->view('layout/header', $data);
        $this->load->view('student/studentShow', $data);
        $this->load->view('layout/footer', $data);
    }

    public function exportformat()
    {
        $this->load->helper('download');
        $filepath = "./backend/import/import_student_sample_file.csv";
        $data     = file_get_contents($filepath);
        $name     = 'import_student_sample_file.csv';

        force_download($name, $data);
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('student', 'can_delete')) {
            access_denied();
        }
        $this->student_model->remove($id);
        $this->session->set_flashdata('msg', '<i class="fa fa-check-square-o" aria-hidden="true"></i> ' . $this->lang->line('delete_message') . '');
        redirect('student/search');
    }

    public function doc_delete($id, $student_id)
    {
        $this->student_model->doc_delete($id);
        $this->session->set_flashdata('msg', '<i class="fa fa-check-square-o" aria-hidden="true"></i> ' . $this->lang->line('delete_message') . '');
        redirect('student/view/' . $student_id);
    }

    public function create()
    {
        // show php ini errors
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        if (!$this->rbac->hasPrivilege('student', 'can_add')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'student/create');
        $genderList                 = $this->customlib->getGender();
        $data['genderList']         = $genderList;
        $data['sch_setting']        = $this->sch_setting_detail;
        $data['title']              = 'Add Student';
        $data['title_list']         = 'Recently Added Student';
        $data['adm_auto_insert']    = $this->sch_setting_detail->adm_auto_insert;
        $data["student_categorize"] = 'class';
        // $session                    = $this->setting_model->getCurrentSession();
        // $data['session_id']         = $session;
        // $data['last_admission_no']  = $this->student_model->getLastAdmNo();
        $session = $this->session_model->get();
        $data['sessionlist'] = $session;
        $student_result             = $this->student_model->getRecentRecord();
        $data['studentlist']        = $student_result;
        $class                      = $this->class_model->get('', $classteacher = 'yes');
        $data['classlist']          = $class;
        $userdata                   = $this->customlib->getUserData();
        $category                   = $this->category_model->get();
        $data['categorylist']       = $category;
        $houses                     = $this->student_model->gethouselist();
        $data['houses']             = $houses;
        $data["bloodgroup"]         = $this->blood_group;
        $hostelList                 = $this->hostel_model->get();
        $data['hostelList']         = $hostelList;
        $vehroute_result            = $this->vehroute_model->get();
        $data['vehroutelist']       = $vehroute_result;
        $checklistresult            = $this->student_model->getchecklistforstudent();
        $data['checklistresult']    = $checklistresult;
        $custom_fields              = $this->customfield_model->getByBelong('students');

        foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
            if ($custom_fields_value['validation']) {
                $custom_fields_id   = $custom_fields_value['id'];
                $custom_fields_name = $custom_fields_value['name'];
                $this->form_validation->set_rules("custom_fields[students][" . $custom_fields_id . "]", $custom_fields_name, 'trim|required');
            }
        }

        $this->form_validation->set_rules('firstname', $this->lang->line('first_name'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('gender', $this->lang->line('gender'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('dob', $this->lang->line('date_of_birth'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('aadhar_name', "Student Aadhar Name.", 'trim|required|xss_clean');
        // if ($this->sch_setting_detail->guardian_name) {
        //     $this->form_validation->set_rules('guardian_name', $this->lang->line('guardian_name'), 'trim|required|xss_clean');
        //     $this->form_validation->set_rules('guardian_is', $this->lang->line('guardian'), 'trim|required|xss_clean');
        // }
        // if ($this->sch_setting_detail->guardian_phone) {
        //     $this->form_validation->set_rules('guardian_phone', $this->lang->line('guardian_phone'), 'trim|required|xss_clean');
        // }
        if (!empty($this->input->post('pan_no_father'))) {
            $this->form_validation->set_rules('pan_no_father', "PAN No. (Father)", 'trim|xss_clean|callback__panRegex');
        }

        if (!empty($this->input->post('pan_no_mother'))) {
            $this->form_validation->set_rules('pan_no_mother', "PAN No. (mother)", 'trim|xss_clean|callback__panRegex');
        }

        if (!empty($this->input->post('parent_aadhar_no'))) {
            $this->form_validation->set_rules('parent_aadhar_no', "National Identification Number (Parent)", 'trim|xss_clean|exact_length[12]');
        }

        $this->form_validation->set_rules(
            'email',
            $this->lang->line('email'),
            'required|trim|valid_email|xss_clean'
            // array(
            //     'valid_email',
            //     array('check_student_email_exists', array($this->student_model, 'check_student_email_exists')),
            // )
        );
        $this->form_validation->set_rules('guardian_email', $this->lang->line('guardian_email'), 'trim|valid_email|xss_clean');

        if (!$this->sch_setting_detail->adm_auto_insert) {

            $this->form_validation->set_rules('admission_no', $this->lang->line('admission_no'), 'trim|required|xss_clean|is_unique[students.admission_no]');
        }
        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_upload');

        if ($this->form_validation->run() == false) {

            $this->load->view('layout/header', $data);
            $this->load->view('student/studentCreate', $data);
            $this->load->view('layout/footer', $data);
        } else {
            
            $custom_field_post  = $this->input->post("custom_fields[students]");
            $custom_value_array = array();
            if (!empty($custom_field_post)) {

                foreach ($custom_field_post as $key => $value) {
                    $check_field_type = $this->input->post("custom_fields[students][" . $key . "]");
                    $field_value      = is_array($check_field_type) ? implode(",", $check_field_type) : $check_field_type;
                    $array_custom     = array(
                        'belong_table_id' => 0,
                        'custom_field_id' => $key,
                        'field_value'     => $field_value,
                    );
                    $custom_value_array[] = $array_custom;
                }
            }

            $class_id   = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');

            $fees_discount = $this->input->post('fees_discount');

            $vehroute_id    = $this->input->post('vehroute_id');
            $remark          = $this->input->post('remark');
            $total_att       = $this->input->post('total_att');
            $student_att     = $this->input->post('student_att');
            $pass_status     = $this->input->post('pass_status');
            $hostel_room_id = $this->input->post('hostel_room_id');
            if (empty($vehroute_id)) {
                $vehroute_id = 0;
            }
            if (empty($hostel_room_id)) {
                $hostel_room_id = 0;
            }

            $data_insert = array(
                'application_no'         => $this->input->post('application_no'),
                'firstname'         => $this->input->post('firstname'),
                'aadhar_name'       => $this->input->post('aadhar_name'),
                'rte'               => $this->input->post('rte'),
                'state'             => $this->input->post('state'),
                'city'              => $this->input->post('city'),
                'pincode'           => $this->input->post('pincode'),
                'cast'              => $this->input->post('cast'),
                'previous_school'   => $this->input->post('previous_school'),
                'dob'               => $this->customlib->dateFormatToYYYYMMDD($this->input->post('dob')),
                'current_address'   => $this->input->post('current_address'),
                'permanent_address' => $this->input->post('permanent_address'),
                'adhar_no'          => $this->input->post('adhar_no'),
                'samagra_id'        => $this->input->post('samagra_id'),
                'bank_account_no'   => $this->input->post('bank_account_no'),
                'bank_name'         => $this->input->post('bank_name'),
                'ifsc_code'         => $this->input->post('ifsc_code'),
                'guardian_email'    => $this->input->post('guardian_email'),
                'gender'            => $this->input->post('gender'),
                'guardian_name'     => $this->input->post('guardian_name'),
                'guardian_relation' => $this->input->post('guardian_relation'),
                'guardian_phone'    => $this->input->post('guardian_phone'),
                'guardian_address'  => $this->input->post('guardian_address'),
                'vehroute_id'       => $vehroute_id,
                'hostel_room_id'    => $hostel_room_id,
                'note'              => $this->input->post('note'),
                'is_active'         => 'yes',
                'father_status'     => $this->input->post('father_status'),
                'mother_status'     => $this->input->post('mother_status'),
            );
            if ($this->sch_setting_detail->guardian_occupation) {
                $data_insert['guardian_occupation'] = $this->input->post('guardian_occupation');
            }
            if ($this->input->post('gender') == 'Female') {
                $data_insert['image'] = 'uploads/student_images/default_female.jpg';
            } else {
                $data_insert['image'] = 'uploads/student_images/default_male.jpg';
            }
            $house             = $this->input->post('house');
            $blood_group       = $this->input->post('blood_group');
            $measurement_date  = $this->input->post('measure_date');
            $roll_no           = $this->input->post('roll_no');
            $lastname          = $this->input->post('lastname');
            $middlename        = $this->input->post('middlename');
            $category_id       = $this->input->post('category_id');
            $religion          = $this->input->post('religion');
            $mobileno          = $this->input->post('mobileno');
            $email             = $this->input->post('email');
            $admission_date    = $this->input->post('admission_date');
            $height            = $this->input->post('height');
            $weight            = $this->input->post('weight');
            $father_name       = $this->input->post('father_name');
            $father_phone      = $this->input->post('father_phone');
            $father_occupation = $this->input->post('father_occupation');
            $mother_name       = $this->input->post('mother_name');
            $mother_phone      = $this->input->post('mother_phone');
            $mother_occupation = $this->input->post('mother_occupation');
            $uid_no            = $this->input->post('uid_no');
            $pan_no_father     = $this->input->post('pan_no_father');
            $pan_no_mother     = $this->input->post('pan_no_mother');
            $parent_aadhar_no  = $this->input->post('parent_aadhar_no');
            $tc_no             = $this->input->post('tc_no');
            $duplicate_tc_no   = $this->input->post('duplicate_tc_no');
            $disability_type   = $this->input->post('disability_type');
            $disability_card_no = $this->input->post('disability_card_no');
            $disability        = $this->input->post('disability');
            $place_of_birth    = $this->input->post('place_of_birth');
            $sub_caste         = $this->input->post('sub_caste');
            $father_annual_income        = $this->input->post('father_annual_income');
            $mother_annual_income        = $this->input->post('mother_annual_income');

            if ($this->sch_setting_detail->guardian_name) {
                $data_insert['guardian_is'] = $this->input->post('guardian_is');
            }

            if (isset($measurement_date)) {
                $data_insert['measurement_date'] = $this->customlib->dateFormatToYYYYMMDD($this->input->post('measure_date'));
            }

            if (isset($house)) {
                $data_insert['school_house_id'] = $this->input->post('house');
            }
            if (isset($blood_group)) {

                $data_insert['blood_group'] = $this->input->post('blood_group');
            }

            // if (isset($roll_no)) {

            //     $data_insert['roll_no'] = $this->input->post('roll_no');
            // }

            if (isset($lastname)) {

                $data_insert['lastname'] = $this->input->post('lastname');
            }
            if (isset($middlename)) {

                $data_insert['middlename'] = $this->input->post('middlename');
            }
            if (isset($category_id)) {

                $data_insert['category_id'] = $this->input->post('category_id');
            }

            if (isset($religion)) {

                $data_insert['religion'] = $this->input->post('religion');
            }

            if (isset($mobileno)) {

                $data_insert['mobileno'] = $this->input->post('mobileno');
            }

            if (isset($email)) {

                $data_insert['email'] = $this->input->post('email');
            }

            if (isset($admission_date)) {

                $data_insert['admission_date'] = $this->customlib->dateFormatToYYYYMMDD($this->input->post('admission_date'));
            }

            if (isset($height)) {

                $data_insert['height'] = $this->input->post('height');
            }

            if (isset($weight)) {

                $data_insert['weight'] = $this->input->post('weight');
            }

            if (isset($father_name)) {

                $data_insert['father_name'] = $this->input->post('father_name');
            }

            if (isset($father_phone)) {

                $data_insert['father_phone'] = $this->input->post('father_phone');
            }

            if (isset($father_occupation)) {

                $data_insert['father_occupation'] = $this->input->post('father_occupation');
            }

            if (isset($mother_name)) {

                $data_insert['mother_name'] = $this->input->post('mother_name');
            }

            if (isset($mother_phone)) {

                $data_insert['mother_phone'] = $this->input->post('mother_phone');
            }

            if (isset($mother_occupation)) {

                $data_insert['mother_occupation'] = $this->input->post('mother_occupation');
            }

            if (isset($uid_no)) {

                $data_insert['uid_no'] = $this->input->post('uid_no');
            }

            if (isset($pan_no_father)) {

                $data_insert['pan_no_father'] = $this->input->post('pan_no_father');
            }

            if (isset($pan_no_mother)) {

                $data_insert['pan_no_mother'] = $this->input->post('pan_no_mother');
            }

            if (isset($parent_aadhar_no)) {

                $data_insert['parent_aadhar_no'] = $this->input->post('parent_aadhar_no');
            }

            if (isset($tc_no)) {

                $data_insert['tc_no'] = $this->input->post('tc_no');
            }

            if (isset($duplicate_tc_no)) {

                $data_insert['duplicate_tc_no'] = $this->input->post('duplicate_tc_no');
            }

            if (isset($disability_type)) {

                $data_insert['disability_type'] = $this->input->post('disability_type');
            }
            if (isset($disability_card_no)) {

                $data_insert['disability_card_no'] = $this->input->post('disability_card_no');
            }
            if (isset($disability)) {

                $data_insert['disability'] = $this->input->post('disability');
            }
            if (isset($place_of_birth)) {

                $data_insert['place_of_birth'] = $this->input->post('place_of_birth');
            }
            if (isset($sub_caste)) {

                $data_insert['sub_caste'] = $this->input->post('sub_caste');
            }
            if (isset($father_annual_income)) {

                $data_insert['father_annual_income'] = $this->input->post('father_annual_income');
            }
            if (isset($mother_annual_income)) {

                $data_insert['mother_annual_income'] = $this->input->post('mother_annual_income');
            }

            $insert                            = true;
            $data_setting                      = array();
            $data_setting['id']                = $this->sch_setting_detail->id;
            $data_setting['adm_auto_insert']   = $this->sch_setting_detail->adm_auto_insert;
            $data_setting['adm_update_status'] = $this->sch_setting_detail->adm_update_status;
            $admission_no                      = 0;


            if ($this->sch_setting_detail->adm_auto_insert) {
                if ($this->sch_setting_detail->adm_update_status) {

                    $admission_no = $this->sch_setting_detail->adm_prefix . $this->sch_setting_detail->adm_start_from;

                    $last_student = $this->student_model->lastRecord();
                    if (!empty($last_student)) {

                        $last_admission_digit = str_replace($this->sch_setting_detail->adm_prefix, "", $last_student->admission_no);

                        $admission_no                = $this->sch_setting_detail->adm_prefix . sprintf("%0" . $this->sch_setting_detail->adm_no_digit . "d", $last_admission_digit + 1);
                        $data_insert['admission_no'] = $admission_no;
                    } else {
                        $admission_no                = $this->sch_setting_detail->adm_prefix . $this->sch_setting_detail->adm_start_from;
                        $data_insert['admission_no'] = $admission_no;
                    }
                } else {
                    $admission_no                = $this->sch_setting_detail->adm_prefix . $this->sch_setting_detail->adm_start_from;
                    $data_insert['admission_no'] = $admission_no;
                }

                $admission_no_exists = $this->student_model->check_adm_exists($admission_no);
                if ($admission_no_exists) {
                    $insert = false;
                }
            } else {
                $admission_no = $this->student_model->getLastAdmNo($class_id,$section_id);
                $data_insert['admission_no'] = $admission_no +1;
            }
            if ($insert) {
                $insert_id = $this->student_model->add($data_insert, $data_setting);
                $data = array(
                    'member_type' => 'student',
                    'member_id' => $insert_id,
                    'library_card_no' => null,
                );

                $inserted_id = $this->librarymanagement_model->add($data);
                if (!empty($custom_value_array)) {
                    $this->customfield_model->insertRecord($custom_value_array, $insert_id);
                }
                $data_new = array(
                    'student_id'    => $insert_id,
                    'class_id'      => $class_id,
                    'section_id'    => $section_id,
                    'session_id'    => $this->input->post('session_id'),
                    'fees_discount' => $fees_discount,
                    'remark'        => $remark,
                    'total_att'     => $total_att,
                    'student_att'   => $student_att,
                    'pass_status'   => $pass_status,
                    'house_id'   => $house,
                );
                
                if ($this->input->post('roll_no') == "") {
                    $roll_no_up = $this->student_model->getclassLastRoll($class_id,$section_id,$this->input->post('session_id'));
                    $data_new['roll_no'] = $roll_no_up['roll_no'] + 1;
                }else {
                    $data_new['roll_no'] = $this->input->post('roll_no');
                }
                $student_session_id = $this->student_model->add_student_session($data_new);
                $user_password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
                $sibling_id         = $this->input->post('sibling_id');
                $data_student_login = array(
                    'username' => $this->student_login_prefix . $insert_id,
                    'password' => $user_password,
                    'user_id'  => $insert_id,
                    'role'     => 'student',
                );
                $this->user_model->add($data_student_login);
                $insert_array = array(
                    'session_id' => $this->input->post('session_id'),
                    'student_session_id' => $student_session_id,
                    'fees_discount_id' => 5,
                    'is_active'  => 'Yes',
                    'amount'     =>  $this->input->post('amount'),
                );
                $this->feediscount_model->allotdiscount($insert_array);
                if ($this->input->post('late_adm_discount') != "") {
                    $insert_array2 = array(
                        'session_id' => $this->input->post('session_id'),
                        'student_session_id' => $student_session_id,
                        'fees_discount_id' => 11,
                        'is_active'  => 'Yes',
                        'amount'     =>  $this->input->post('late_adm_discount'),
                    );  
                    $this->feediscount_model->allotdiscount($insert_array2);
                }
                $check_list_ids = $this->input->post('checklist_id[]');
                if (!empty($check_list_ids)) {
                    foreach ($check_list_ids as $key => $check_id) {
                       
                        $arrayinsert = array(
                            'id' => "",
                            'student_id' => $insert_id,
                            'checklist_id' => $check_id,
                        );
    
                        $this->student_model->addstudent_checklist($arrayinsert); 
                    }
                }

                // $classmst = $this->feegrouptype_model->getclassfess($class_id,$section_id)->num_rows();
                // $classmst_fees = $this->feegrouptype_model->getclassfess($class_id,$section_id)->result_array();
                // if ($classmst > 0) {
                //     foreach ($classmst_fees as  $classfees) {

                //         $array_fees = array(
                //             'student_session_id' => $student_session_id,
                //             'fee_session_group_id' => $classfees['fees_group_id'],
                //         );

                //         $inserted_id = $this->studentfeemaster_model->add($array_fees);
                //     }

                // }
                if ($sibling_id > 0) {
                    $student_sibling = $this->student_model->get($sibling_id);
                    $update_student  = array(
                        'id'        => $insert_id,
                        'parent_id' => $student_sibling['parent_id'],
                    );
                    $student_sibling = $this->student_model->add($update_student);
                } else {
                    $parent_password   = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
                    $temp              = $insert_id;
                    $data_parent_login = array(
                        'username' => $this->parent_login_prefix . $insert_id,
                        'password' => $parent_password,
                        'user_id'  => 0,
                        'role'     => 'parent',
                        'childs'   => $temp,
                    );
                    $ins_parent_id  = $this->user_model->add($data_parent_login);
                    $update_student = array(
                        'id'        => $insert_id,
                        'parent_id' => $ins_parent_id,
                    );
                    $this->student_model->add($update_student);
                }

                if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                    $fileInfo = pathinfo($_FILES["file"]["name"]);
                    $img_name = $insert_id . '.' . $fileInfo['extension'];
                    move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/student_images/" . $img_name);
                    $data_img = array('id' => $insert_id, 'image' => 'uploads/student_images/' . $img_name);
                    $this->student_model->add($data_img);
                }

                if (isset($_FILES["father_pic"]) && !empty($_FILES['father_pic']['name'])) {
                    $fileInfo = pathinfo($_FILES["father_pic"]["name"]);
                    $img_name = $insert_id . "father" . '.' . $fileInfo['extension'];
                    move_uploaded_file($_FILES["father_pic"]["tmp_name"], "./uploads/student_images/" . $img_name);
                    $data_img = array('id' => $insert_id, 'father_pic' => 'uploads/student_images/' . $img_name);
                    $this->student_model->add($data_img);
                }
                if (isset($_FILES["mother_pic"]) && !empty($_FILES['mother_pic']['name'])) {
                    $fileInfo = pathinfo($_FILES["mother_pic"]["name"]);
                    $img_name = $insert_id . "mother" . '.' . $fileInfo['extension'];
                    move_uploaded_file($_FILES["mother_pic"]["tmp_name"], "./uploads/student_images/" . $img_name);
                    $data_img = array('id' => $insert_id, 'mother_pic' => 'uploads/student_images/' . $img_name);
                    $this->student_model->add($data_img);
                }

                if (isset($_FILES["guardian_pic"]) && !empty($_FILES['guardian_pic']['name'])) {
                    $fileInfo = pathinfo($_FILES["guardian_pic"]["name"]);
                    $img_name = $insert_id . "guardian" . '.' . $fileInfo['extension'];
                    move_uploaded_file($_FILES["guardian_pic"]["tmp_name"], "./uploads/student_images/" . $img_name);
                    $data_img = array('id' => $insert_id, 'guardian_pic' => 'uploads/student_images/' . $img_name);
                    $this->student_model->add($data_img);
                }

                if (isset($_FILES["first_doc"]) && !empty($_FILES['first_doc']['name'])) {
                    $uploaddir = './uploads/student_documents/' . $insert_id . '/';
                    if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                        die("Error creating folder $uploaddir");
                    }
                    $fileInfo    = pathinfo($_FILES["first_doc"]["name"]);
                    $first_title = $this->input->post('first_title');
                    $file_name   = $_FILES['first_doc']['name'];
                    $exp         = explode(' ', $file_name);
                    $imp         = implode('_', $exp);
                    $img_name    = $uploaddir . $imp;
                    move_uploaded_file($_FILES["first_doc"]["tmp_name"], $img_name);
                    $data_img = array('student_id' => $insert_id, 'title' => $first_title, 'doc' => $imp);
                    $this->student_model->adddoc($data_img);
                }
                if (isset($_FILES["second_doc"]) && !empty($_FILES['second_doc']['name'])) {
                    $uploaddir = './uploads/student_documents/' . $insert_id . '/';
                    if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                        die("Error creating folder $uploaddir");
                    }
                    $fileInfo     = pathinfo($_FILES["second_doc"]["name"]);
                    $second_title = $this->input->post('second_title');
                    $file_name    = $_FILES['second_doc']['name'];
                    $exp          = explode(' ', $file_name);
                    $imp          = implode('_', $exp);
                    $img_name     = $uploaddir . $imp;
                    move_uploaded_file($_FILES["second_doc"]["tmp_name"], $img_name);
                    $data_img = array('student_id' => $insert_id, 'title' => $second_title, 'doc' => $imp);
                    $this->student_model->adddoc($data_img);
                }

                if (isset($_FILES["fourth_doc"]) && !empty($_FILES['fourth_doc']['name'])) {
                    $uploaddir = './uploads/student_documents/' . $insert_id . '/';
                    if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                        die("Error creating folder $uploaddir");
                    }
                    $fileInfo     = pathinfo($_FILES["fourth_doc"]["name"]);
                    $fourth_title = $this->input->post('fourth_title');
                    $file_name    = $_FILES['fourth_doc']['name'];
                    $exp          = explode(' ', $file_name);
                    $imp          = implode('_', $exp);
                    $img_name     = $uploaddir . $imp;
                    move_uploaded_file($_FILES["fourth_doc"]["tmp_name"], $img_name);
                    $data_img = array('student_id' => $insert_id, 'title' => $fourth_title, 'doc' => $imp);
                    $this->student_model->adddoc($data_img);
                }
                if (isset($_FILES["fifth_doc"]) && !empty($_FILES['fifth_doc']['name'])) {
                    $uploaddir = './uploads/student_documents/' . $insert_id . '/';
                    if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                        die("Error creating folder $uploaddir");
                    }
                    $fileInfo    = pathinfo($_FILES["fifth_doc"]["name"]);
                    $fifth_title = $this->input->post('fifth_title');
                    $file_name   = $_FILES['fifth_doc']['name'];
                    $exp         = explode(' ', $file_name);
                    $imp         = implode('_', $exp);
                    $img_name    = $uploaddir . $imp;

                    move_uploaded_file($_FILES["fifth_doc"]["tmp_name"], $img_name);
                    $data_img = array('student_id' => $insert_id, 'title' => $fifth_title, 'doc' => $imp);
                    $this->student_model->adddoc($data_img);
                }

                // $sender_details = array('student_id' => $insert_id, 'contact_no' => $this->input->post('guardian_phone'), 'email' => $this->input->post('guardian_email'));
                // $this->mailsmsconf->mailsms('student_admission', $sender_details);

                $this->send_admission_mail($student_session_id,$this->input->post('email'));

                $student_login_detail = array('id' => $insert_id, 'credential_for' => 'student', 'username' => $this->student_login_prefix . $insert_id, 'password' => $user_password, 'contact_no' => $this->input->post('mobileno'), 'email' => $this->input->post('email'));
                // $this->mailsmsconf->mailsms('login_credential', $student_login_detail);

                if ($sibling_id > 0) {
                } else {
                    $parent_login_detail = array('id' => $insert_id, 'credential_for' => 'parent', 'username' => $this->parent_login_prefix . $insert_id, 'password' => $parent_password, 'contact_no' => $this->input->post('guardian_phone'), 'email' => $this->input->post('guardian_email'));
                    $this->mailsmsconf->mailsms('login_credential', $parent_login_detail);
                }

                $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('success_message') . '</div>');
                $this->add_student_fees($student_session_id);
                // redirect('student/create');
            } else {

                $data['error_message'] = $this->lang->line('admission_no') . ' ' . $admission_no . ' ' . $this->lang->line('already_exists');
                $this->load->view('layout/header', $data);
                $this->load->view('student/studentCreate', $data);
                $this->load->view('layout/footer', $data);
            }
        }
    }

    public function _panRegex($pan)
    {
        if (preg_match('/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/', $pan)) {
            return TRUE;
        } else {
            $this->form_validation->set_message('_panRegex', 'Please Enter Valid Pan No');
            return FALSE;
        }
    }

    public function create_doc()
    {

        $this->form_validation->set_rules('first_title', $this->lang->line('title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('first_doc', $this->lang->line('document'), 'callback_handle_uploadcreate_doc');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'first_title' => form_error('first_title'),
                'first_doc'   => form_error('first_doc'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $student_id = $this->input->post('student_id');
            if (isset($_FILES["first_doc"]) && !empty($_FILES['first_doc']['name'])) {
                $uploaddir = './uploads/student_documents/' . $student_id . '/';
                if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                    die("Error creating folder $uploaddir");
                }

                $fileInfo    = pathinfo($_FILES["first_doc"]["name"]);
                $first_title = $this->input->post('first_title');
                $file_name   = $_FILES['first_doc']['name'];
                $exp         = explode(' ', $file_name);
                $imp         = implode('_', $exp);
                $img_name    = $uploaddir . basename($imp);
                move_uploaded_file($_FILES["first_doc"]["tmp_name"], $img_name);
                $data_img = array('student_id' => $student_id, 'title' => $first_title, 'doc' => $imp);
                $this->student_model->adddoc($data_img);
            }

            $msg   = $this->lang->line('success_message');
            $array = array('status' => 'success', 'error' => '', 'message' => $msg);
        }
        echo json_encode($array);
    }

    public function handle_uploadcreate_doc()
    {

        $image_validate = $this->config->item('file_validate');
        $result         = $this->filetype_model->get();
        if (isset($_FILES["first_doc"]) && !empty($_FILES['first_doc']['name'])) {

            $file_type = $_FILES["first_doc"]['type'];
            $file_size = $_FILES["first_doc"]["size"];
            $file_name = $_FILES["first_doc"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mtype = finfo_file($finfo, $_FILES['first_doc']['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mtype, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_uploadcreate_doc', $this->lang->line('file_type_not_allowed'));
                return false;
            }

            if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_uploadcreate_doc', $this->lang->line('extension_not_allowed'));
                return false;
            }
            if ($file_size > $result->file_size) {
                $this->form_validation->set_message('handle_uploadcreate_doc', $this->lang->line('file_size_shoud_be_less_than') . number_format($image_validate['upload_size'] / 1048576, 2) . " MB");
                return false;
            }

            return true;
        } else {
            $this->form_validation->set_message('handle_uploadcreate_doc', $this->lang->line('the_file_field_is_required'));
            return false;
        }
        return true;
    }

    public function handle_upload()
    {

        $image_validate = $this->config->item('image_validate');
        $result         = $this->filetype_model->get();
        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {

            $file_type = $_FILES["file"]['type'];
            $file_size = $_FILES["file"]["size"];
            $file_name = $_FILES["file"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->image_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->image_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($files = @getimagesize($_FILES['file']['tmp_name'])) {

                if (!in_array($files['mime'], $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', 'File Type Not Allowed');
                    return false;
                }

                if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', 'Extension Not Allowed');
                    return false;
                }
                if ($file_size > $result->image_size) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($image_validate['upload_size'] / 1048576, 2) . " MB");
                    return false;
                }
            } else {
                $this->form_validation->set_message('handle_upload', "File Type / Extension Error Uploading  Image");
                return false;
            }

            return true;
        }
        return true;
    }

    public function handle_father_upload()
    {

        $image_validate = $this->config->item('image_validate');
        $result         = $this->filetype_model->get();
        if (isset($_FILES["father_pic"]) && !empty($_FILES['father_pic']['name'])) {

            $file_type = $_FILES["father_pic"]['type'];
            $file_size = $_FILES["father_pic"]["size"];
            $file_name = $_FILES["father_pic"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->image_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->image_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($files = @getimagesize($_FILES['father_pic']['tmp_name'])) {

                if (!in_array($files['mime'], $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_father_upload', 'File Type Not Allowed');
                    return false;
                }

                if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_father_upload', 'Extension Not Allowed');
                    return false;
                }
                if ($file_size > $result->image_size) {
                    $this->form_validation->set_message('handle_father_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($image_validate['upload_size'] / 1048576, 2) . " MB");
                    return false;
                }
            } else {
                $this->form_validation->set_message('handle_father_upload', "File Type / Extension Error Uploading  Image");
                return false;
            }

            return true;
        }
        return true;
    }

    public function handle_mother_upload()
    {

        $image_validate = $this->config->item('image_validate');
        $result         = $this->filetype_model->get();
        if (isset($_FILES["mother_pic"]) && !empty($_FILES['mother_pic']['name'])) {

            $file_type = $_FILES["mother_pic"]['type'];
            $file_size = $_FILES["mother_pic"]["size"];
            $file_name = $_FILES["mother_pic"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->image_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->image_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($files = @getimagesize($_FILES['mother_pic']['tmp_name'])) {

                if (!in_array($files['mime'], $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_mother_upload', 'File Type Not Allowed');
                    return false;
                }

                if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_mother_upload', 'Extension Not Allowed');
                    return false;
                }
                if ($file_size > $result->image_size) {
                    $this->form_validation->set_message('handle_mother_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($image_validate['upload_size'] / 1048576, 2) . " MB");
                    return false;
                }
            } else {
                $this->form_validation->set_message('handle_mother_upload', "File Type / Extension Error Uploading  Image");
                return false;
            }

            return true;
        }
        return true;
    }

    public function handle_guardian_upload()
    {

        $image_validate = $this->config->item('image_validate');
        $result         = $this->filetype_model->get();
        if (isset($_FILES["guardian_pic"]) && !empty($_FILES['guardian_pic']['name'])) {

            $file_type = $_FILES["guardian_pic"]['type'];
            $file_size = $_FILES["guardian_pic"]["size"];
            $file_name = $_FILES["guardian_pic"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->image_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->image_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($files = @getimagesize($_FILES['guardian_pic']['tmp_name'])) {

                if (!in_array($files['mime'], $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_guardian_upload', 'File Type Not Allowed');
                    return false;
                }

                if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_guardian_upload', 'Extension Not Allowed');
                    return false;
                }
                if ($file_size > $result->image_size) {
                    $this->form_validation->set_message('handle_guardian_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($image_validate['upload_size'] / 1048576, 2) . " MB");
                    return false;
                }
            } else {
                $this->form_validation->set_message('handle_guardian_upload', "File Type / Extension Error Uploading  Image");
                return false;
            }

            return true;
        }
        return true;
    }

    public function sendpassword()
    {

        $student_login_detail = array('id' => $this->input->post('student_id'), 'credential_for' => 'student', 'username' => $this->input->post('username'), 'password' => $this->input->post('password'), 'contact_no' => $this->input->post('contact_no'), 'email' => $this->input->post('email'));

        $msg = $this->mailsmsconf->mailsms('login_credential', $student_login_detail);
    }

    public function send_parent_password()
    {
        $parent_login_detail = array('id' => $this->input->post('student_id'), 'credential_for' => 'parent', 'username' => $this->input->post('username'), 'password' => $this->input->post('password'), 'contact_no' => $this->input->post('contact_no'), 'email' => $this->input->post('email'));

        $msg = $this->mailsmsconf->mailsms('login_credential', $parent_login_detail);
    }

    public function import()
    {
        if (!$this->rbac->hasPrivilege('import_student', 'can_view')) {
            access_denied();
        }
        $data['title']      = 'Import Student';
        $data['title_list'] = 'Recently Added Student';
        $session            = $this->setting_model->getCurrentSession();
        $class              = $this->class_model->get('', $classteacher = 'yes');
        $data['classlist']  = $class;
        $userdata           = $this->customlib->getUserData();

        $category = $this->category_model->get();
        $fields = array('admission_no', 'roll_no', 'firstname', 'middlename', 'lastname', 'gender', 'dob', 'category_id', 'religion', 'cast', 'mobileno', 'email', 'admission_date', 'blood_group', 'school_house_id', 'height', 'weight', 'measurement_date', 'father_name', 'father_phone', 'father_occupation', 'mother_name', 'mother_phone', 'mother_occupation', 'guardian_is', 'guardian_name', 'guardian_relation', 'guardian_email', 'guardian_phone', 'guardian_occupation', 'guardian_address', 'current_address', 'permanent_address', 'bank_account_no', 'bank_name', 'ifsc_code', 'adhar_no', 'samagra_id', 'rte', 'previous_school', 'note');
        $data["fields"]       = $fields;
        $data['categorylist'] = $category;
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_csv_upload');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('student/import', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $student_categorize = 'class';
            if ($student_categorize == 'class') {
                $section = 0;
            } else if ($student_categorize == 'section') {
                $section = $this->input->post('section_id');
            }
            $class_id   = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $session = $this->setting_model->getCurrentSession();
            $roll_no_arr = [];
            $data_new = [];
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                if ($ext == 'csv') {
                    $file = $_FILES['file']['tmp_name'];
                    $this->load->library('CSVReader');
                    $result = $this->csvreader->parse_file($file);
                    // echo "<pre>";
                    // print_r($result);
                    // die();
                    if (!empty($result)) {
                        $rowcount = 0;
                        for ($i = 1; $i <= count($result); $i++) {
                            $student_data[$i] = array();
                            $n                = 0;
                            foreach ($result[$i] as $key => $value) {
                                //echo "<br>".$i . " Key : ".$key;
                                if ($fields[$n] != 'roll_no') {
                                    $student_data[$i][$fields[$n]] = $this->encoding_lib->toUTF8($result[$i][$key]);
                                }
                                //$student_data[$i]['roll_no'] = null;
                                $student_data[$i]['is_active'] = 'yes';
                                $roll_no_arr[$i] = $result[$i]['roll_no'];
                                //if (date('Y-m-d', strtotime($result[$i]['date_of_birth'])) === $result[$i]['date_of_birth']) {
                                if ($result[$i]['date_of_birth'] != '') {
                                    $student_data[$i]['dob'] = date('Y-m-d', strtotime($result[$i]['date_of_birth']));
                                } else {
                                    $student_data[$i]['dob'] = null;
                                }

                                if (date('Y-m-d', strtotime($result[$i]['measurement_date'])) === $result[$i]['measurement_date']) {
                                    $student_data[$i]['measurement_date'] = date('Y-m-d', strtotime($result[$i]['measurement_date']));
                                } else {
                                    $student_data[$i]['measurement_date'] = '';
                                }

                                //if (date('Y-m-d', strtotime($result[$i]['admission_date'])) === $result[$i]['admission_date']) {
                                if ($result[$i]['admission_date'] != '') {
                                    $student_data[$i]['admission_date'] = date('Y-m-d', strtotime($result[$i]['admission_date']));
                                } else {
                                    $student_data[$i]['admission_date'] = null;
                                }
                                $n++;
                            }


                            $roll_no                           = $roll_no_arr[$i]; //$student_data[$i]["roll_no"];
                            $adm_no                            = $student_data[$i]["admission_no"];
                            $mobile_no                         = $student_data[$i]["mobileno"];
                            $email                             = $student_data[$i]["email"];
                            $guardian_phone                    = $student_data[$i]["guardian_phone"];
                            $guardian_email                    = $student_data[$i]["guardian_email"];
                            $data_setting                      = array();
                            $data_setting['id']                = $this->sch_setting_detail->id;
                            $data_setting['adm_auto_insert']   = $this->sch_setting_detail->adm_auto_insert;
                            $data_setting['adm_update_status'] = $this->sch_setting_detail->adm_update_status;
                            $category = $result[$i]['category'];
                            $student_data[$i]['category_id'] = $this->student_model->get_category_id($category);
                            //-------------------------
                            if ($this->sch_setting_detail->adm_auto_insert) {

                                if ($this->sch_setting_detail->adm_update_status) {
                                    $last_student                     = $this->student_model->lastRecord();
                                    $last_admission_digit             = str_replace($this->sch_setting_detail->adm_prefix, "", $last_student->admission_no);
                                    $admission_no                     = $this->sch_setting_detail->adm_prefix . sprintf("%0" . $this->sch_setting_detail->adm_no_digit . "d", $last_admission_digit + 1);
                                    $student_data[$i]["admission_no"] = $admission_no;
                                } else {
                                    $admission_no                     = $this->sch_setting_detail->adm_prefix . $this->sch_setting_detail->adm_start_from;
                                    $student_data[$i]["admission_no"] = $admission_no;
                                }

                                $admission_no_exists = $this->student_model->check_adm_exists($admission_no);

                                if ($admission_no_exists) {
                                    $insert = "";
                                } else {
                                    $insert_id = $this->student_model->add($student_data[$i], $data_setting);
                                }
                            } else {

                                if ($this->form_validation->is_unique($adm_no, 'students.admission_no')) {
                                    $insert_id = $this->student_model->add($student_data[$i], $data_setting);
                                } else {
                                    $insert_id = "";
                                }
                            }

                            //-------------------------
                            if (!empty($insert_id)) {
                                $data_new = array(
                                    'student_id' => $insert_id,
                                    'class_id'   => $class_id,
                                    'section_id' => $section_id,
                                    'session_id' => $session,
                                    'roll_no' =>  $result[$i]['roll_no'],
                                );


                                $this->student_model->add_student_session($data_new);
                                $user_password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
                                $sibling_id    = $this->input->post('sibling_id');

                                $data_student_login = array(
                                    'username' => $this->student_login_prefix . $insert_id,
                                    'password' => $user_password,
                                    'user_id'  => $insert_id,
                                    'role'     => 'student',
                                );

                                $this->user_model->add($data_student_login);
                                $parent_password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);

                                $temp              = $insert_id;
                                $data_parent_login = array(
                                    'username' => $this->parent_login_prefix . $insert_id,
                                    'password' => $parent_password,
                                    'user_id'  => $insert_id,
                                    'role'     => 'parent',
                                    'childs'   => $temp,
                                );

                                $ins_id         = $this->user_model->add($data_parent_login);
                                $update_student = array(
                                    'id'        => $insert_id,
                                    'parent_id' => $ins_id,
                                );

                                $this->student_model->add($update_student);
                                $sender_details = array('student_id' => $insert_id, 'contact_no' => $guardian_phone, 'email' => $guardian_email);
                                //$this->mailsmsconf->mailsms('student_admission', $sender_details);

                                $student_login_detail = array('id' => $insert_id, 'credential_for' => 'student', 'username' => $this->student_login_prefix . $insert_id, 'password' => $user_password, 'contact_no' => $mobile_no, 'email' => $email);
                                //$this->mailsmsconf->mailsms('login_credential', $student_login_detail);

                                $parent_login_detail = array('id' => $insert_id, 'credential_for' => 'parent', 'username' => $this->parent_login_prefix . $insert_id, 'password' => $parent_password, 'contact_no' => $guardian_phone, 'email' => $guardian_email);

                                //$this->mailsmsconf->mailsms('login_credential', $parent_login_detail);

                                $data['csvData'] = $result;
                                //$this->session->set_flashdata('msg', '<div class="alert alert-success text-center">' . $this->lang->line('students_imported_successfully') . '</div>');

                                $rowcount++;
                                //$this->session->set_flashdata('msg', '<div class="alert alert-success text-center">Total ' . count($result) . " records found in CSV file. Total " . $rowcount . ' records imported successfully.</div>');

                            } else {

                                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">' . $this->lang->line('record_already_exists') . '</div>');
                            }
                        }
                    } else {

                        $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">' . $this->lang->line('no_record_found') . '</div>');
                    }
                } else {

                    $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">' . $this->lang->line('please_upload_CSV_file_only') . '</div>');
                }
            }

            redirect('student/import');
        }
    }

    public function import_update()
    {
        if (!$this->rbac->hasPrivilege('import_student', 'can_view')) {
            access_denied();
        }
        $data['title']      = 'Import Student';
        $data['title_list'] = 'Recently Added Student';
        $session            = $this->setting_model->getCurrentSession();
        $class              = $this->class_model->get('', $classteacher = 'yes');
        $data['classlist']  = $class;
        $userdata           = $this->customlib->getUserData();

        $category = $this->category_model->get();
        $fields = array('admission_no', 'roll_no', 'firstname', 'middlename', 'lastname', 'gender', 'dob', 'category_id', 'religion', 'cast', 'mobileno', 'email', 'admission_date', 'blood_group', 'school_house_id', 'height', 'weight', 'measurement_date', 'father_name', 'father_phone', 'father_occupation', 'mother_name', 'mother_phone', 'mother_occupation', 'guardian_is', 'guardian_name', 'guardian_relation', 'guardian_email', 'guardian_phone', 'guardian_occupation', 'guardian_address', 'current_address', 'permanent_address', 'bank_account_no', 'bank_name', 'ifsc_code', 'adhar_no', 'samagra_id', 'rte', 'previous_school', 'note');
        $data["fields"]       = $fields;
        $data['categorylist'] = $category;
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_csv_upload');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('student/import_update', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $student_categorize = 'class';
            if ($student_categorize == 'class') {
                $section = 0;
            } else if ($student_categorize == 'section') {
                $section = $this->input->post('section_id');
            }
            $class_id   = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $session = $this->setting_model->getCurrentSession();
            $roll_no_arr = [];
            $data_new = [];
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                if ($ext == 'csv') {
                    $file = $_FILES['file']['tmp_name'];
                    $this->load->library('CSVReader');
                    $result = $this->csvreader->parse_file($file);
                    if (!empty($result)) {
                        $rowcount = 0;
                        for ($i = 1; $i <= count($result); $i++) {
                            $student_data[$i] = array();
                            $n                = 0;
                            foreach ($result[$i] as $key => $value) {
                                $student_data[$i][$fields[$n]] = $this->encoding_lib->toUTF8($result[$i][$key]);
                                $student_data[$i]['roll_no'] = null;
                                $student_data[$i]['is_active'] = 'yes';
                                $roll_no_arr[$i] = $result[$i]['roll_no'];
                                if (date('d-m-Y', strtotime($result[$i]['date_of_birth'])) === $result[$i]['date_of_birth']) {
                                    $student_data[$i]['dob'] = date('Y-m-d', strtotime($result[$i]['date_of_birth']));
                                } else {
                                    $student_data[$i]['dob'] = null;
                                }

                                if (date('d-m-Y', strtotime($result[$i]['measurement_date'])) === $result[$i]['measurement_date']) {
                                    $student_data[$i]['measurement_date'] = date('Y-m-d', strtotime($result[$i]['measurement_date']));
                                } else {
                                    $student_data[$i]['measurement_date'] = '';
                                }

                                if (date('d-m-Y', strtotime($result[$i]['admission_date'])) === $result[$i]['admission_date']) {
                                    $student_data[$i]['admission_date'] = date('Y-m-d', strtotime($result[$i]['admission_date']));
                                } else {
                                    $student_data[$i]['admission_date'] = null;
                                }
                                $n++;
                            }

                            $roll_no                           = $student_data[$i]["roll_no"];
                            $adm_no                            = $student_data[$i]["admission_no"];
                            $mobile_no                         = $student_data[$i]["mobileno"];
                            $email                             = $student_data[$i]["email"];
                            $guardian_phone                    = $student_data[$i]["guardian_phone"];
                            $guardian_email                    = $student_data[$i]["guardian_email"];
                            $data_setting                      = array();
                            $data_setting['id']                = $this->sch_setting_detail->id;
                            $data_setting['adm_auto_insert']   = $this->sch_setting_detail->adm_auto_insert;
                            $data_setting['adm_update_status'] = $this->sch_setting_detail->adm_update_status;
                            $category = $result[$i]['category'];
                            $student_data[$i]['category_id'] = $this->student_model->get_category_id($category);

                            //-------------------------
                            if ($this->sch_setting_detail->adm_auto_insert) {
                            
                                if ($this->sch_setting_detail->adm_update_status) {
                                    $last_student                     = $this->student_model->lastRecord();
                                    $last_admission_digit             = str_replace($this->sch_setting_detail->adm_prefix, "", $last_student->admission_no);
                                    $admission_no                     = $this->sch_setting_detail->adm_prefix . sprintf("%0" . $this->sch_setting_detail->adm_no_digit . "d", $last_admission_digit + 1);
                                    $student_data[$i]["admission_no"] = $admission_no;
                                } else {

                                    $admission_no                     = $this->sch_setting_detail->adm_prefix . $this->sch_setting_detail->adm_start_from;
                                    $student_data[$i]["admission_no"] = $admission_no;
                                }

                                $admission_no_exists = $this->student_model->check_adm_exists($admission_no);

                                if ($admission_no_exists) {
                                    $insert_id = "";
                                } else {
                                    $insert_id = $this->student_model->add_import_update($student_data[$i], $data_setting);
                                }
                            } else {
                                if ($this->form_validation->is_unique($adm_no, 'students.admission_no')) {
                                    $insert_id = "";
                                } else {
                                    $insert_id = $this->student_model->add_import_update($student_data[$i], $data_setting);
                                }
                            }

                            //-------------------------
                            if (!empty($insert_id)) {
                                // $data_new = array(
                                //     'student_id' => $insert_id,
                                //     'class_id'   => $class_id,
                                //     'section_id' => $section_id,
                                //     'session_id' => $session,
                                //     'roll_no' =>  $result[$i]['roll_no'],
                                // );


                                // $this->student_model->add_student_session($data_new);
                                // $user_password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
                                // $sibling_id    = $this->input->post('sibling_id');

                                // $data_student_login = array(
                                //     'username' => $this->student_login_prefix . $insert_id,
                                //     'password' => $user_password,
                                //     'user_id'  => $insert_id,
                                //     'role'     => 'student',
                                // );

                                // $this->user_model->add($data_student_login);
                                // $parent_password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);

                                // $temp              = $insert_id;
                                // $data_parent_login = array(
                                //     'username' => $this->parent_login_prefix . $insert_id,
                                //     'password' => $parent_password,
                                //     'user_id'  => $insert_id,
                                //     'role'     => 'parent',
                                //     'childs'   => $temp,
                                // );

                                // $ins_id         = $this->user_model->add($data_parent_login);
                                // $update_student = array(
                                //     'id'        => $insert_id,
                                //     'parent_id' => $ins_id,
                                // );

                                // $this->student_model->add($update_student);
                                // $sender_details = array('student_id' => $insert_id, 'contact_no' => $guardian_phone, 'email' => $guardian_email);
                                // //$this->mailsmsconf->mailsms('student_admission', $sender_details);

                                // $student_login_detail = array('id' => $insert_id, 'credential_for' => 'student', 'username' => $this->student_login_prefix . $insert_id, 'password' => $user_password, 'contact_no' => $mobile_no, 'email' => $email);
                                // //$this->mailsmsconf->mailsms('login_credential', $student_login_detail);

                                // $parent_login_detail = array('id' => $insert_id, 'credential_for' => 'parent', 'username' => $this->parent_login_prefix . $insert_id, 'password' => $parent_password, 'contact_no' => $guardian_phone, 'email' => $guardian_email);

                                // //$this->mailsmsconf->mailsms('login_credential', $parent_login_detail);

                                // $data['csvData'] = $result;
                                // //$this->session->set_flashdata('msg', '<div class="alert alert-success text-center">' . $this->lang->line('students_imported_successfully') . '</div>');

                                // $rowcount++;
                                // //$this->session->set_flashdata('msg', '<div class="alert alert-success text-center">Total ' . count($result) . " records found in CSV file. Total " . $rowcount . ' records imported successfully.</div>');

                            } else {

                                $this->session->set_flashdata('msg', '<div class="alert alert-success text-center"> Sucessfully Updated</div>');
                            }
                        }
                    } else {

                        $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">' . $this->lang->line('no_record_found') . '</div>');
                    }
                } else {

                    $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">' . $this->lang->line('please_upload_CSV_file_only') . '</div>');
                }
            }

            redirect('student/import_update');
        }
    }

    public function handle_csv_upload()
    {
        $error = "";
        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
            $allowedExts = array('csv');
            $mimes       = array(
                'text/csv',
                'text/plain',
                'application/csv',
                'text/comma-separated-values',
                'application/excel',
                'application/vnd.ms-excel',
                'application/vnd.msexcel',
                'text/anytext',
                'application/octet-stream',
                'application/txt'
            );
            $temp      = explode(".", $_FILES["file"]["name"]);
            $extension = end($temp);
            if ($_FILES["file"]["error"] > 0) {
                $error .= "Error opening the file<br />";
            }
            if (!in_array($_FILES['file']['type'], $mimes)) {
                $error .= "Error opening the file<br />";
                $this->form_validation->set_message('handle_csv_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }
            if (!in_array($extension, $allowedExts)) {
                $error .= "Error opening the file<br />";
                $this->form_validation->set_message('handle_csv_upload', $this->lang->line('extension_not_allowed'));
                return false;
            }
            if ($error == "") {
                return true;
            }
        } else {
            $this->form_validation->set_message('handle_csv_upload', $this->lang->line('please_select_file'));
            return false;
        }
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('student', 'can_edit')) {
            access_denied();
        }

        $data['title']   = 'Edit Student';
        $data['id']      = $id;
        $student         = $this->student_model->get($id);
        $genderList      = $this->customlib->getGender();
        $data['student'] = $student;

        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $data['genderList']      = $genderList;
        $session                 = $this->setting_model->getCurrentSession();
        $data['session']         = $session;
        $vehroute_result         = $this->vehroute_model->get();
        $data['vehroutelist']    = $vehroute_result;
        $class                   = $this->class_model->get();
        $setting_result          = $this->setting_model->get();

        $data["student_categorize"] = 'class';
        $data['classlist']          = $class;
        $category                   = $this->category_model->get();
        $data['categorylist']       = $category;
        $hostelList                 = $this->hostel_model->get();
        $data['hostelList']         = $hostelList;
        $houses                     = $this->student_model->gethouselist();
        $data['houses']             = $houses;
        $data["bloodgroup"]         = $this->blood_group;
        //$siblings                   = $this->student_model->getMySiblings($student['parent_id'], $student['id']);
        $siblings                   = $this->student_model->getMySiblings_new($student['id']);
        $siblings_reverse           = $this->student_model->getMySiblings_new_reverse($student['id']);
        $data['siblings']           = $siblings;
        $data['siblings_reverse']   = $siblings_reverse;
        
        $data['siblings_counts']    = count($siblings);
        $custom_fields              = $this->customfield_model->getByBelong('students');
        $data['sch_setting']        = $this->sch_setting_detail;
        if ($this->rbac->hasPrivilege('add_sibling', 'can_add')) {
            $data['add_sibling'] = 1;
        }
        else
        {$data['add_sibling'] = 0;}
        if ($this->rbac->hasPrivilege('add_sibling', 'can_delete')) {
            $data['del_sibling'] = 1;
        }
        else
        {$data['del_sibling'] = 0;}
        foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
            if ($custom_fields_value['validation']) {
                $custom_fields_id   = $custom_fields_value['id'];
                $custom_fields_name = $custom_fields_value['name'];
                $this->form_validation->set_rules("custom_fields[students][" . $custom_fields_id . "]", $custom_fields_name, 'trim|required');
            }
        }

        $this->form_validation->set_rules('firstname', $this->lang->line('first_name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('dob', $this->lang->line('date_of_birth'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('gender', $this->lang->line('gender'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('aadhar_name', "Student Aadhar Name", 'trim|required|xss_clean');

        // if ($this->sch_setting_detail->guardian_name) {
        //     $this->form_validation->set_rules('guardian_name', $this->lang->line('guardian_name'), 'trim|required|xss_clean');
        //     $this->form_validation->set_rules('guardian_is', $this->lang->line('guardian'), 'trim|required|xss_clean');
        // }
        // if ($this->sch_setting_detail->guardian_phone) {
        //     $this->form_validation->set_rules('guardian_phone', $this->lang->line('guardian_phone'), 'trim|required|xss_clean');
        // }

        // $this->form_validation->set_rules(
        //     'email',
        //     $this->lang->line('email'),
        //     'required|trim|valid_email|xss_clean'
        //     // array(
        //     //     'valid_email',
        //     //     array('check_student_email_exists', array($this->student_model, 'check_student_email_exists')),
        //     // )
        // );
        $this->form_validation->set_rules('guardian_email', $this->lang->line('guardian_email'), 'trim|valid_email|xss_clean');
        if (!$this->sch_setting_detail->adm_auto_insert) {

            // $this->form_validation->set_rules('admission_no', $this->lang->line('admission_no'), array('required', array('check_admission_no_exists', array($this->student_model, 'valid_student_admission_no'))));
        }

        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_upload');
        $this->form_validation->set_rules('father_pic', $this->lang->line('image'), 'callback_handle_father_upload');
        $this->form_validation->set_rules('mother_pic', $this->lang->line('image'), 'callback_handle_mother_upload');
        $this->form_validation->set_rules('guardian_pic', $this->lang->line('image'), 'callback_handle_guardian_upload');
        if ($this->form_validation->run() == false) {
            // print_r($data);die();
            $this->load->view('layout/header', $data);
            $this->load->view('student/studentEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $custom_field_post = $this->input->post("custom_fields[students]");
            if (isset($custom_field_post)) {
                $custom_value_array = array();
                foreach ($custom_field_post as $key => $value) {
                    $check_field_type = $this->input->post("custom_fields[students][" . $key . "]");
                    $field_value      = is_array($check_field_type) ? implode(",", $check_field_type) : $check_field_type;
                    $array_custom     = array(
                        'belong_table_id' => $id,
                        'custom_field_id' => $key,
                        'field_value'     => $field_value,
                    );
                    $custom_value_array[] = $array_custom;
                }
                $this->customfield_model->updateRecord($custom_value_array, $id, 'students');
            }
            $student_id      = $this->input->post('student_id');
            $student         = $this->student_model->get($student_id);
            $sibling_id      = $this->input->post('sibling_id');
            $siblings_counts = $this->input->post('siblings_counts');
            $siblings        = $this->student_model->getMySiblings($student['parent_id'], $student_id);
            $total_siblings  = count($siblings);
            $class_id        = $this->input->post('class_id');
            $section_id      = $this->input->post('section_id');
            $hostel_room_id  = $this->input->post('hostel_room_id');
            $fees_discount   = $this->input->post('fees_discount');
            $vehroute_id     = $this->input->post('vehroute_id');
            $remark          = $this->input->post('remark');
            $total_att       = $this->input->post('total_att');
            $student_att     = $this->input->post('student_att');
            $pass_status     = $this->input->post('pass_status');
            if (empty($vehroute_id)) {
                $vehroute_id = 0;
            }
            if (empty($hostel_room_id)) {
                $hostel_room_id = 0;
            }

            $data = array(
                'id'                => $id,
                'application_no'    => $this->input->post('application_no'),
                'firstname'         => $this->input->post('firstname'),
                'aadhar_name'       => $this->input->post('aadhar_name'),
                'rte'               => $this->input->post('rte'),
                'state'             => $this->input->post('state'),
                'city'              => $this->input->post('city'),
                'pincode'           => $this->input->post('pincode'),
                'cast'              => $this->input->post('cast'),
                'previous_school'   => $this->input->post('previous_school'),
                'dob'               => $this->customlib->dateFormatToYYYYMMDD($this->input->post('dob')),
                'current_address'   => $this->input->post('current_address'),
                'permanent_address' => $this->input->post('permanent_address'),
                'adhar_no'          => $this->input->post('adhar_no'),
                'aapar_id'          => $this->input->post('aapar_id'),
                'samagra_id'        => $this->input->post('samagra_id'),
                'bank_account_no'   => $this->input->post('bank_account_no'),
                'bank_name'         => $this->input->post('bank_name'),
                'ifsc_code'         => $this->input->post('ifsc_code'),
                'adharno'           => $this->input->post('adharno'),

                'guardian_email'    => $this->input->post('guardian_email'),
                'gender'            => $this->input->post('gender'),
                'guardian_name'     => $this->input->post('guardian_name'),
                'guardian_relation' => $this->input->post('guardian_relation'),
                'guardian_phone'    => $this->input->post('guardian_phone'),
                'guardian_address'  => $this->input->post('guardian_address'),
                'vehroute_id'       => $vehroute_id,
                'hostel_room_id'    => $hostel_room_id,
                'note'              => $this->input->post('note'),
                'is_active'         => 'yes',
                'father_status'     => $this->input->post('father_status'),
                'mother_status'     => $this->input->post('mother_status'),
            );
            if ($this->sch_setting_detail->guardian_occupation) {
                $data['guardian_occupation'] = $this->input->post('guardian_occupation');
            }
            $house             = $this->input->post('house');
            $blood_group       = $this->input->post('blood_group');
            $measurement_date  = $this->input->post('measure_date');
            $roll_no           = $this->input->post('roll_no');
            $lastname          = $this->input->post('lastname');
            $middlename        = $this->input->post('middlename');
            $category_id       = $this->input->post('category_id');
            $religion          = $this->input->post('religion');
            $mobileno          = $this->input->post('mobileno');
            $email             = $this->input->post('email');
            $admission_date    = $this->input->post('admission_date');
            $height            = $this->input->post('height');
            $weight            = $this->input->post('weight');
            $father_name       = $this->input->post('father_name');
            $father_phone      = $this->input->post('father_phone');
            $father_occupation = $this->input->post('father_occupation');
            $mother_name       = $this->input->post('mother_name');
            $mother_phone      = $this->input->post('mother_phone');
            $mother_occupation = $this->input->post('mother_occupation');
            $uid_no            = $this->input->post('uid_no');
            $aapar_id          = $this->input->post('aapar_id');
            $dep_student_id    = $this->input->post('dep_student_id');
            $pan_no_father     = $this->input->post('pan_no_father');
            $pan_no_mother     = $this->input->post('pan_no_mother');
            $parent_aadhar_no  = $this->input->post('parent_aadhar_no');
            $tc_no             = $this->input->post('tc_no');
            $duplicate_tc_no   = $this->input->post('duplicate_tc_no');
            $disability_type   = $this->input->post('disability_type');
            $disability_card_no = $this->input->post('disability_card_no');
            $disability        = $this->input->post('disability');
            $place_of_birth    = $this->input->post('place_of_birth');
            $sub_caste         = $this->input->post('sub_caste');
            $father_annual_income        = $this->input->post('father_annual_income');
            $mother_annual_income        = $this->input->post('mother_annual_income');



            if ($this->sch_setting_detail->guardian_name) {
                $data['guardian_is'] = $this->input->post('guardian_is');
            }

            if (isset($measurement_date)) {
                $data['measurement_date'] = $this->customlib->dateFormatToYYYYMMDD($this->input->post('measure_date'));
            }

            if (isset($house)) {
                $data['school_house_id'] = $this->input->post('house');
            }
            if (isset($blood_group)) {

                $data['blood_group'] = $this->input->post('blood_group');
            }

            // if (isset($roll_no)) {

            //     $data['roll_no'] = $this->input->post('roll_no');
            // }

            if (isset($lastname)) {

                $data['lastname'] = $this->input->post('lastname');
            }

            if (isset($middlename)) {
                $data['middlename'] = $this->input->post('middlename');
            }

            if (isset($category_id)) {

                $data['category_id'] = $this->input->post('category_id');
            }

            if (isset($religion)) {

                $data['religion'] = $this->input->post('religion');
            }

            if (isset($mobileno)) {

                $data['mobileno'] = $this->input->post('mobileno');
            }

            if (isset($email)) {

                $data['email'] = $this->input->post('email');
            }

            if (isset($admission_date)) {

                $data['admission_date'] = $this->customlib->dateFormatToYYYYMMDD($this->input->post('admission_date'));
            }

            if (isset($height)) {

                $data['height'] = $this->input->post('height');
            }

            if (isset($weight)) {

                $data['weight'] = $this->input->post('weight');
            }

            if (isset($father_name)) {

                $data['father_name'] = $this->input->post('father_name');
            }

            if (isset($father_phone)) {

                $data['father_phone'] = $this->input->post('father_phone');
            }

            if (isset($father_occupation)) {

                $data['father_occupation'] = $this->input->post('father_occupation');
            }

            if (isset($mother_name)) {

                $data['mother_name'] = $this->input->post('mother_name');
            }

            if (isset($mother_phone)) {

                $data['mother_phone'] = $this->input->post('mother_phone');
            }

            if (isset($mother_occupation)) {

                $data['mother_occupation'] = $this->input->post('mother_occupation');
            }

            if (isset($uid_no)) {

                $data['uid_no'] = $this->input->post('uid_no');
            }
            if (isset($aapar_id)) {

                $data['aapar_id'] = $this->input->post('aapar_id');
            }
            if (isset($dep_student_id)) {

                $data['dep_student_id'] = $this->input->post('dep_student_id');
            }

            if (isset($pan_no_father)) {

                $data['pan_no_father'] = $this->input->post('pan_no_father');
            }

            if (isset($pan_no_mother)) {

                $data['pan_no_mother'] = $this->input->post('pan_no_mother');
            }

            if (isset($parent_aadhar_no)) {

                $data['parent_aadhar_no'] = $this->input->post('parent_aadhar_no');
            }

            if (isset($tc_no)) {

                $data['tc_no'] = $this->input->post('tc_no');
            }

            if (isset($duplicate_tc_no)) {

                $data['duplicate_tc_no'] = $this->input->post('duplicate_tc_no');
            }

            if (isset($disability_type)) {

                $data['disability_type'] = $this->input->post('disability_type');
            }
            if (isset($disability_card_no)) {

                $data['disability_card_no'] = $this->input->post('disability_card_no');
            }
            if (isset($disability)) {

                $data['disability'] = $this->input->post('disability');
            }
            if (isset($place_of_birth)) {

                $data['place_of_birth'] = $this->input->post('place_of_birth');
            }
            if (isset($sub_caste)) {

                $data['sub_caste'] = $this->input->post('sub_caste');
            }
            if (isset($father_annual_income)) {

                $data['father_annual_income'] = $this->input->post('father_annual_income');
            }
            if (isset($mother_annual_income)) {

                $data['mother_annual_income'] = $this->input->post('mother_annual_income');
            }


            $default_image = array('uploads/student_images/default_female.jpg', 'uploads/student_images/default_male.jpg');
            if (in_array($student['image'], $default_image)) {
                if ($this->input->post('gender') == 'Female') {
                    $data['image'] = 'uploads/student_images/default_female.jpg';
                } else {
                    $data['image'] = 'uploads/student_images/default_male.jpg';
                }
            }

            if (!$this->sch_setting_detail->adm_auto_insert) {

                $data['admission_no'] = $this->input->post('admission_no');
            }
            $this->student_model->add($data);
            $data_new = array(
                'student_id'    => $id,
                'class_id'      => $class_id,
                'section_id'    => $section_id,
                'session_id'    => $session,
                'fees_discount' => $fees_discount,
                'remark'        => $remark,
                'total_att'     => $total_att,
                'student_att'   => $student_att,
                'pass_status'   => $pass_status,
                'house_id'      => $house,
            );
            if (isset($roll_no)) {

                $data_new['roll_no'] = $this->input->post('roll_no');
            }
            $insert_id = $this->student_model->add_student_session($data_new);

            $insert_array = array(
                'student_session_id' => $insert_id,
                'session_id' => $session,
                'fees_discount_id' => 5,
                'is_active'  => 'Yes',
                'amount'     =>  $this->input->post('amount'),
            );
            $this->feediscount_model->allotdiscount($insert_array);
            if ($this->input->post('late_adm_discount') != "") {
                $insert_array2 = array(
                    'session_id' => $session,
                    'student_session_id' => $insert_id,
                    'fees_discount_id' => 7,
                    'is_active'  => 'Yes',
                    'amount'     =>  $this->input->post('late_adm_discount'),
                );
                $this->feediscount_model->allotdiscount($insert_array2);
            }

            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $fileInfo = pathinfo($_FILES["file"]["name"]);
                $img_name = $id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/student_images/" . $img_name);
                $data_img = array('id' => $id, 'image' => 'uploads/student_images/' . $img_name);
                $this->student_model->add($data_img);
            }

            if (isset($_FILES["father_pic"]) && !empty($_FILES['father_pic']['name'])) {
                $fileInfo = pathinfo($_FILES["father_pic"]["name"]);
                $img_name = $id . "father" . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["father_pic"]["tmp_name"], "./uploads/student_images/" . $img_name);
                $data_img = array('id' => $id, 'father_pic' => 'uploads/student_images/' . $img_name);
                $this->student_model->add($data_img);
            }

            if (isset($_FILES["mother_pic"]) && !empty($_FILES['mother_pic']['name'])) {
                $fileInfo = pathinfo($_FILES["mother_pic"]["name"]);
                $img_name = $id . "mother" . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["mother_pic"]["tmp_name"], "./uploads/student_images/" . $img_name);
                $data_img = array('id' => $id, 'mother_pic' => 'uploads/student_images/' . $img_name);
                $this->student_model->add($data_img);
            }

            if (isset($_FILES["guardian_pic"]) && !empty($_FILES['guardian_pic']['name'])) {
                $fileInfo = pathinfo($_FILES["guardian_pic"]["name"]);
                $img_name = $id . "guardian" . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["guardian_pic"]["tmp_name"], "./uploads/student_images/" . $img_name);
                $data_img = array('id' => $id, 'guardian_pic' => 'uploads/student_images/' . $img_name);
                $this->student_model->add($data_img);
            }

            if (isset($siblings_counts) && ($total_siblings == $siblings_counts)) {
                //if there is no change in sibling
            } else if (!isset($siblings_counts) && $sibling_id == 0 && $total_siblings > 0) {
                // add for new parent
                $parent_password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);

                $data_parent_login = array(
                    'username' => $this->parent_login_prefix . $student_id . "_1",
                    'password' => $parent_password,
                    'user_id'  => "",
                    'role'     => 'parent',
                );

                $update_student = array(
                    'id'        => $student_id,
                    'parent_id' => 0,
                );
                $ins_id = $this->user_model->addNewParent($data_parent_login, $update_student);
            } else if ($sibling_id != 0) {
                //join to student with new parent
                $student_sibling = $this->student_model->get($sibling_id);
                $update_student  = array(
                    'id'        => $student_id,
                    'parent_id' => $student_sibling['parent_id'],
                );
                $student_sibling = $this->student_model->add($update_student);
            } else {
            }

            $this->session->set_flashdata('msg', '<div student="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('student/search');
        }
    }
    public function bulkdelete()
    {

        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'bulkdelete');
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $data['sch_setting']     = $this->sch_setting_detail;
        $data['fields']          = $this->customfield_model->get_custom_fields('students', 1);
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $class   = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $search  = $this->input->post('search');

            $data['searchby']    = "filter";
            $data['class_id']    = $this->input->post('class_id');
            $data['section_id']  = $this->input->post('section_id');
            $data['search_text'] = $this->input->post('search_text');
            $resultlist          = $this->student_model->searchByClassSection($class, $section);
            $data['resultlist']  = $resultlist;
            $title               = $this->classsection_model->getDetailbyClassSection($data['class_id'], $data['section_id']);
            $data['title']       = 'Student Details for ' . @$title['class'] . "(" . @$title['section'] . ")";
        }
        $this->load->view('layout/header', $data);
        $this->load->view('student/bulkdelete', $data);
        $this->load->view('layout/footer', $data);
    }

    public function search()
    {
        if (!$this->rbac->hasPrivilege('student', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'student/search');
        $data['title']           = 'Student Search';
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $data['sch_setting']     = $this->sch_setting_detail;
        $data['fields']          = $this->customfield_model->get_custom_fields('students', 1);
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $status_result      = $this->student_model->get_student_status();
        $data['status_result'] = $status_result;
        $this->load->view('layout/header', $data);
        $this->load->view('student/studentSearch', $data);
        $this->load->view('layout/footer', $data);
    }

    public function save_student_status()
    {
        $student_id = $this->input->post('student_id');
        $status = $this->input->post('status');

        $student_session = $this->studentsession_model->searchActiveClassSectionStudent($student_id);

        $student_session_id = $student_session->id;
        $session_id = $student_session->session_id;
        $userdata = $this->customlib->getUserData();

        $data = array(
            'student_id' => $student_id,
            'student_session_id' => $student_session_id,
            'session_id' => $session_id,
            'status' => $this->input->post('status'),
            'remark' => $this->input->post('remark'),
            'created_at' => date('Y-m-d H:i:s'),
            'done_by' => $userdata['name'],
        );
        $this->student_model->student_update_status($data);
        $data2 = array(
            'student_session_id' => $student_session_id,
            'session_id' => $session_id,
            'old_status' => $student_session->is_active,
            'updated_status' => $status,
            'updated_date' =>  date('Y-m-d H:i:s'),
            'updated_by' => $userdata['name'],
            'updated_time' => date('Y-m-d H:i:s'),
        );
        $this->student_model->student_status($data2);
        if ($status == 1) {
            $array = array(
                'status' => $this->input->post('status'),
                'is_active' => "yes",
            );
        }
        else
        {
            $array = array(
                'status' => $this->input->post('status'),
                'is_active' => "no",
            );
        }   
            $this->studentsession_model->update_status($array, $student_id);
        $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        echo json_encode($array);
    }

    public function getstudentdata()
    {
        $student_id = $this->input->post('student_id');
        $resultlist = $this->student_model->get($student_id);

        $resultlist['full_name'] = $this->customlib->getFullName($resultlist['firstname'], $resultlist['middlename'], $resultlist['lastname'], $this->sch_setting_detail->middlename, $this->sch_setting_detail->lastname);

        $resultlist['datas'] = $this->student_model->student_pass_status($student_id);

        echo json_encode($resultlist);
    }

    public function ajaxsearch()
    {
        $search_type = $this->input->post('search_type');
        if ($search_type == "search_filter") {
            $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        }

        if ($this->form_validation->run() == false && $search_type == "search_filter") {
            $error = array();
            if ($search_type == "search_filter") {
                $error['class_id'] = form_error('class_id');
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

    public function getByClassAndSection()
    {
        $class      = $this->input->get('class_id');
        $section    = $this->input->get('section_id');
        $resultlist = $this->student_model->searchByClassSection($class, $section);
        foreach ($resultlist as $key => $value) {
            $resultlist[$key]['full_name'] = $this->customlib->getFullName($value['firstname'], $value['middlename'], $value['lastname'], $this->sch_setting_detail->middlename, $this->sch_setting_detail->lastname);
            # code...
        }
        echo json_encode($resultlist);
    }

    public function getByClassAndSectionExcludeMe()
    {
        $class      = $this->input->get('class_id');
        $section    = $this->input->get('section_id');
        $student_id = $this->input->get('current_student_id');
        $resultlist = $this->student_model->searchByClassSectionWithoutCurrent($class, $section, $student_id);

        foreach ($resultlist as $key => $value) {
            $resultlist[$key]['full_name'] = $this->customlib->getFullName($value['firstname'], $value['middlename'], $value['lastname'], $this->sch_setting_detail->middlename, $this->sch_setting_detail->lastname);
            # code...
        }

        echo json_encode($resultlist);
    }

    public function getStudentRecordByID()
    {
        $student_id = $this->input->get('student_id');
        $resultlist = $this->student_model->get($student_id);

        foreach ($resultlist as $key => $value) {
            // echo "<pre>";
            // print_r($resultlist);

            $resultlist['full_name'] = $this->customlib->getFullName($resultlist['firstname'], $resultlist['middlename'], $resultlist['lastname'], $this->sch_setting_detail->middlename, $this->sch_setting_detail->lastname);
        }
        $student_session = $this->studentsession_model->getStudentByStudentId($student_id);

        $this->feediscount_model->deletedisstdata($feediscount_id = 1, $student_session['id']);
        $session                    = $this->setting_model->getCurrentSession();
        $insert_array = array(
            'student_session_id' => $student_session['id'],
            'session_id' => $session,
            'fees_discount_id' => 1,

        );
        $inserted_id = $this->feediscount_model->allotdiscount($insert_array);


        echo json_encode($resultlist);
    }
    public function addsibling()
    {
        $current_student_id = $this->input->post('current_student_id');
        $student_id = $this->input->post('student_id');
        $session                    = $this->setting_model->getCurrentSession();
        $data_sibling = array(
            'student_id' => $current_student_id,
            'sibling_student_id' => $student_id,
        );
        $this->student_model->add_student_sibling($data_sibling);
    
        $resultlist = $this->student_model->get($student_id);
        // print_r($resultlist);
        // foreach ($resultlist as $key => $value) {
        // echo "<pre>";
        // print_r($resultlist);

        $resultlist['full_name'] = $this->customlib->getFullName($resultlist['firstname'], $resultlist['middlename'], $resultlist['lastname'], $this->sch_setting_detail->middlename, $this->sch_setting_detail->lastname);
        //}
        $student_session = $this->studentsession_model->getStudentByStudentId($current_student_id);
        //$this->feediscount_model->deletedisstdata($feediscount_id = 1, $student_session['id']);
        $insert_array = array(
            'student_session_id' => $student_session['id'],
            'session_id' => $session,
            'fees_discount_id' => 1,    
            'is_active' => "Yes",
        );
        $inserted_id = $this->feediscount_model->allotdiscount($insert_array);


        echo json_encode($resultlist);
    }
    public function add_member()
    {
        $current_student_id = $this->input->post('current_student_id');
        $membership_id = $this->input->post('membership_id');
        $member_name = $this->input->post('member_name');
        $member_relation = $this->input->post('member_relation');

        $data_member = array(
            'student_id' => $current_student_id,
            'membership_id' => $membership_id,
            'member_name' => $member_name,
            'member_relation' => $member_relation,
        );
        $this->student_model->add_member($data_member);

        $resultlist = $this->student_model->getmember($current_student_id);

        $student_session = $this->studentsession_model->getStudentByStudentId($current_student_id);
        $session                    = $this->setting_model->getCurrentSession();
        $insert_array = array(
            'session_id' => $session,
            'student_session_id' => $student_session['id'],
            'fees_discount_id' => 2,
            'is_active' => "Yes",
        );
        $inserted_id = $this->feediscount_model->allotdiscount($insert_array);

        echo json_encode($resultlist);
    }

    public function add_referred()
    {

        $current_student_id = $this->input->post('current_student_id');
        $referred_by = $this->input->post('referred_by');
        $designation = $this->input->post('designation');
        $letter_no = $this->input->post('letter_no');
        $approval_date = $this->input->post('approval_date');
        $discount_amt = $this->input->post('discount_amt');
        $document = $_FILES["document"]["name"];
        $session                    = $this->setting_model->getCurrentSession();
        $img_name = '';
        if (isset($_FILES["document"]) && !empty($_FILES['document']['name'])) {
            $fileInfo = pathinfo($_FILES["document"]["name"]);
            $img_name = 'id' . $current_student_id . '.' . $fileInfo['extension'];
            move_uploaded_file($_FILES["document"]["tmp_name"], "./upload/referred/" . $img_name);
        }


        $student_session = $this->studentsession_model->getStudentByStudentId($current_student_id);
        $insert_array = array(
            'student_session_id' => $student_session['id'],
            'referred_by' => $referred_by,
            'designation' => $designation,
            'letter_no' => $letter_no,
            'approval_date' => date('Y-m-d', strtotime($approval_date)),
            'discount_amt' => $discount_amt,
            'document_upload' => $img_name,
            'session_id' => $this->setting_model->getCurrentSession(),
        );
        $inserted_id = $this->student_model->add_referred($insert_array);

        $student_session = $this->studentsession_model->getStudentByStudentId($current_student_id);
        $insert_array = array(
            'session_id' => $session,
            'student_session_id' => $student_session['id'],
            'fees_discount_id' => 3,
            'is_active' => "Yes",
        );
        $inserted_id = $this->feediscount_model->allotdiscount($insert_array);

        $resultlist = $this->student_model->getreferred($student_session['id']);

        echo json_encode($resultlist);
    }

    public function get_referredlist()
    {
        $current_student_id = $this->input->post('current_student_id');
        $student_session = $this->studentsession_model->getStudentByStudentId($current_student_id);
        $resultlist = $this->student_model->getreferred($student_session['id']);

        echo json_encode($resultlist);
    }

    public function uploadimage($id)
    {
        $data['title'] = 'Add Image';
        $data['id']    = $id;
        $this->load->view('layout/header', $data);
        $this->load->view('student/uploadimage', $data);
        $this->load->view('layout/footer', $data);
    }

    public function doupload($id)
    {
        $config = array(
            'upload_path'   => "./uploads/student_images/",
            'allowed_types' => "gif|jpg|png|jpeg|df",
            'overwrite'     => true,
        );
        $config['file_name'] = $id . ".jpg";
        $this->upload->initialize($config);
        $this->load->library('upload', $config);
        if ($this->upload->do_upload()) {
            $data        = array('upload_data' => $this->upload->data());
            $upload_data = $this->upload->data();
            $data_record = array('id' => $id, 'image' => $upload_data['file_name']);
            $this->setting_model->add($data_record);

            $this->load->view('upload_success', $data);
        } else {
            $error = array('error' => $this->upload->display_errors());

            $this->load->view('file_view', $error);
        }
    }

    public function getlogindetail()
    {
        if (!$this->rbac->hasPrivilege('student_login_credential_report', 'can_view')) {
            access_denied();
        }
        $student_id   = $this->input->post('student_id');
        $examSchedule = $this->user_model->getStudentLoginDetails($student_id);
        echo json_encode($examSchedule);
    }

    public function getUserLoginDetails()
    {
        $studentid = $this->input->post("student_id");
        $result    = $this->user_model->getUserLoginDetails($studentid);
        echo json_encode($result);
    }

    public function guardianreport()
    {

        if (!$this->rbac->hasPrivilege('guardian_report', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/student_information');
        $this->session->set_userdata('subsub_menu', 'Reports/student_information/guardian_report');
        $data['title']           = 'Student Guardian Report';
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $data['sch_setting']     = $this->sch_setting_detail;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $userdata                = $this->customlib->getUserData();
        $carray                  = array();

        if (!empty($data["classlist"])) {
            foreach ($data["classlist"] as $ckey => $cvalue) {

                $carray[] = $cvalue["id"];
            }
        }

        $class_id   = $this->input->post("class_id");
        $section_id = $this->input->post("section_id");

        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {

            $resultlist         = $this->student_model->studentGuardianDetails($carray);
            $data["resultlist"] = "";
        } else {

            $resultlist         = $this->student_model->searchGuardianDetails($class_id, $section_id);
            $data["resultlist"] = $resultlist;
        }

        $this->load->view("layout/header", $data);
        $this->load->view("student/guardianReport", $data);
        $this->load->view("layout/footer", $data);
    }

    public function disablestudentslist()
    {
        if (!$this->rbac->hasPrivilege('disable_student', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'student/disablestudentslist');
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $result                  = $this->student_model->getdisableStudent();
        $data["resultlist"]      = array();
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $data['sch_setting']     = $this->sch_setting_detail;
        $userdata                = $this->customlib->getUserData();
        $carray                  = array();
        $reason_list             = array();
        if (!empty($data["classlist"])) {
            foreach ($data["classlist"] as $ckey => $cvalue) {

                $carray[] = $cvalue["id"];
            }
        }

        $button = $this->input->post('search');
        if ($this->input->server('REQUEST_METHOD') == "GET") {
        } else {
            $class       = $this->input->post('class_id');
            $section     = $this->input->post('section_id');
            $search      = $this->input->post('search');
            $search_text = $this->input->post('search_text');
            if (isset($search)) {
                if ($search == 'search_filter') {
                    $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
                    if ($this->form_validation->run() == false) {
                    } else {
                        $data['searchby']   = "filter";
                        $data['class_id']   = $this->input->post('class_id');
                        $data['section_id'] = $this->input->post('section_id');

                        $data['search_text'] = $this->input->post('search_text');
                        $resultlist          = $this->student_model->disablestudentByClassSection($class, $section);
                        $data['resultlist']  = $resultlist;
                        $title               = $this->classsection_model->getDetailbyClassSection($data['class_id'], $data['section_id']);
                        $data['title']       = 'Student Details for ' . $title['class'] . "(" . $title['section'] . ")";
                    }
                } else if ($search == 'search_full') {
                    $data['searchby'] = "text";

                    $data['search_text'] = trim($this->input->post('search_text'));
                    $resultlist          = $this->student_model->disablestudentFullText($search_text);

                    $data['resultlist'] = $resultlist;
                    $data['title']      = 'Search Details: ' . $data['search_text'];
                }
            }
        }

        $disable_reason = $this->disable_reason_model->get();

        foreach ($disable_reason as $key => $value) {
            $id               = $value['id'];
            $reason_list[$id] = $value;
        }

        $data['disable_reason'] = $reason_list;

        $this->load->view("layout/header", $data);
        $this->load->view("student/disablestudents", $data);
        $this->load->view("layout/footer", $data);
    }

    public function disablestudent($id)
    {

        $data = array('is_active' => "no", 'disable_at' => date("Y-m-d"));
        $this->student_model->disableStudent($id, $data);
        redirect("student/view/" . $id);
    }

    public function enablestudent($id)
    {

        $data = array('is_active' => "yes");
        $this->student_model->disableStudent($id, $data);
        echo "0";
    }

    public function savemulticlass()
    {

        $student_id       = '';
        $message          = "";
        $duplicate_record = 0;
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('student_id', $this->lang->line('student_id'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('row_count[]', 'row_count[]', 'trim|required|xss_clean');

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $total_rows = $this->input->post('row_count[]');
            if (!empty($total_rows)) {

                foreach ($total_rows as $key_rowcount => $row_count) {

                    $this->form_validation->set_rules('class_id_' . $row_count, $this->lang->line('class'), 'trim|required|xss_clean');

                    $this->form_validation->set_rules('section_id_' . $row_count, $this->lang->line('section'), 'trim|required|xss_clean');
                }
            }
        }

        if ($this->form_validation->run() == false) {

            $msg = array(
                'student_id'  => form_error('student_id'),
                'row_count[]' => form_error('row_count[]'),
            );

            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                if (!empty($total_rows)) {

                    $total_rows = $this->input->post('row_count[]');
                    foreach ($total_rows as $key_rowcount => $row_count) {

                        $msg['class_id_' . $row_count]   = form_error('class_id_' . $row_count);
                        $msg['section_id_' . $row_count] = form_error('section_id_' . $row_count);
                    }
                }
            }
            if (!empty($msg)) {
                $message = $this->lang->line('something_went_wrong');
            }

            $array = array('status' => '0', 'error' => $msg, 'message' => $message);
        } else {

            $rowcount            = $this->input->post('row_count[]');
            $class_section_array = array();
            $duplicate_array     = array();
            foreach ($rowcount as $key_rowcount => $value_rowcount) {

                $array = array(
                    'class_id'   => $this->input->post('class_id_' . $value_rowcount),
                    'session_id' => $this->setting_model->getCurrentSession(),
                    'student_id' => $this->input->post('student_id'),
                    'section_id' => $this->input->post('section_id_' . $value_rowcount),
                );

                $class_section_array[] = $array;
                $duplicate_array[]     = $this->input->post('class_id_' . $value_rowcount) . "-" . $this->input->post('section_id_' . $value_rowcount);
            }

            foreach (array_count_values($duplicate_array) as $val => $c) {

                if ($c > 1) {
                    $duplicate_record = 1;
                    break;
                }
            }
            if ($duplicate_record) {

                $array = array('status' => 0, 'error' => '', 'message' => $this->lang->line('duplicate_entry'));
            } else {
                $this->studentsession_model->add($class_section_array, $this->input->post('student_id'));

                $array = array('status' => 1, 'error' => '', 'message' => $this->lang->line('success_message'));
            }
        }
        echo json_encode($array);
    }

    public function disable_reason()
    {

        $student_id = '';
        $this->form_validation->set_rules('reason', $this->lang->line('reason'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('disable_date', $this->lang->line('date'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {

            $msg = array(
                'reason' => form_error('reason'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            $data = array(
                'dis_reason' => $this->input->post('reason'),
                'dis_note'   => $this->input->post('note'),
                'id'         => $this->input->post('student_id'),
                'disable_at' => $this->customlib->dateFormatToYYYYMMDD($this->input->post('disable_date')),
                'is_active'  => 'no',
            );

            $this->student_model->add($data);

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function ajax_delete()
    {

        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('student[]', $this->lang->line('student'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {

            $msg = array(
                'student[]' => form_error('student[]'),
            );
            $array = array('status' => 0, 'error' => $msg, 'message' => '');
        } else {
            $students = $this->input->post('student');
            $condition = "0";
            foreach ($students as $student_key => $student_value) {
                $condition = $this->student_model->checkstudent($student_value);
                if ($condition == 0) {
                    $this->student_model->bulkdelete([$student_value]);
                }
            }




            $array = array('status' => 1, 'error' => '', 'message' => $this->lang->line('delete_message'));
        }
        echo json_encode($array);
    }

    public function profilesetting()
    {

        if (!$this->rbac->hasPrivilege('student_profile_update', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'System Settings/profilesetting');
        $data                    = array();
        $data['result']          = $this->setting_model->getSetting();
        $data['fields']          = get_student_editable_fields();
        $data['inserted_fields'] = $this->student_edit_field_model->get();
        $data['result']          = $this->setting_model->getSetting();
        $this->form_validation->set_rules('student_profile_edit', $this->lang->line('student') . " " . $this->lang->line('profile') . " " . $this->lang->line('update'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == true) {
            $data_record = array(
                'id'                   => $this->input->post('sch_id'),
                'student_profile_edit' => $this->input->post('student_profile_edit'),
            );
            $this->setting_model->add($data_record);
            $this->session->set_flashdata('msg', '<div class="alert alert-left">' . $this->lang->line('update_message') . '</div>');
            redirect('student/profilesetting');
        }
        $data['sch_setting_detail'] = $this->sch_setting_detail;
        $this->load->view("layout/header");
        $this->load->view("student/profilesetting", $data);
        $this->load->view("layout/footer");
    }

    public function changeprofilesetting()
    {

        $this->form_validation->set_rules('name', $this->lang->line('student'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('status', $this->lang->line('status'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {

            $msg = array(
                'status' => form_error('status'),
                'name'   => form_error('name'),
            );

            $array = array('status' => '0', 'error' => $msg, 'msg' => $this->lang->line('something_went_wrong'));
        } else {
            $insert = array(
                'name'   => $this->input->post('name'),
                'status' => $this->input->post('status'),
            );
            $this->student_edit_field_model->add($insert);
            $array = array('status' => '1', 'error' => '', 'msg' => $this->lang->line('success_message'));
        }

        echo json_encode($array);
    }

    /**
     * This function is used to view bulk mail page
     */
    public function bulkmail()
    {
        if (!$this->rbac->hasPrivilege('student', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Communicate');
        $this->session->set_userdata('sub_menu', 'bulk_mail');
        $class                    = $this->class_model->get();
        $data['classlist']        = $class;
        $data['sch_setting']      = $this->sch_setting_detail;
        $data['bulkmailto']       = $this->customlib->bulkmailto();
        $data['notificationtype'] = $this->customlib->bulkmailnotificationtype();
        $data['fields']           = $this->customfield_model->get_custom_fields('students', 1);
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('student/bulkmail', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class   = $this->input->post('class_id');
            $section = $this->input->post('section_id');

            $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
            if ($this->form_validation->run() == false) {
            } else {
                $data['class_id']   = $this->input->post('class_id');
                $data['section_id'] = $this->input->post('section_id');
                $resultlist         = $this->student_model->searchByClassSection($class, $section);
                $data['resultlist'] = $resultlist;
            }

            $this->load->view('layout/header', $data);
            $this->load->view('student/bulkmail', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    /**
     * This function is used to send bulk mail to student and Parent
     */
    public function sendbulkmail()
    {

        $this->form_validation->set_rules('student[]', $this->lang->line('student'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('message_to', $this->lang->line('message_to'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('notification_type', $this->lang->line('notification_type'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'student[]'         => form_error('student[]'),
                'message_to'        => form_error('message_to'),
                'notification_type' => form_error('notification_type'),
            );
            $array = array('status' => 0, 'error' => $msg, 'message' => '');
        } else {
            $students          = $this->input->post('student');
            $message_to        = $this->input->post('message_to');
            $notification_type = $this->input->post('notification_type');

            foreach ($students as $students_value) {

                $student_detail = $this->user_model->student_information($students_value);
                $parent_detail  = $this->user_model->read_single_child($students_value);

                if (($message_to == 1 && $notification_type == 1) || ($message_to == 1 && $notification_type == 3) || ($message_to == 3 && $notification_type == 3)) {
                    $sender_details = array('student_id' => $students_value, 'contact_no' => $student_detail[0]->mobileno, 'email' => $student_detail[0]->email);
                    $this->mailsmsconf->mailsms('student_admission', $sender_details);
                }

                if (($message_to == 1 && $notification_type == 2)  || ($message_to == 1 && $notification_type == 3) || ($message_to == 3 && $notification_type == 3)) {
                    $student_login_detail = array('id' => $students_value, 'credential_for' => 'student', 'username' => $student_detail[0]->username, 'password' => $student_detail[0]->password, 'contact_no' => $student_detail[0]->mobileno, 'email' => $student_detail[0]->email);
                    $this->mailsmsconf->mailsms('login_credential', $student_login_detail);
                }

                if (($message_to == 2 && $notification_type == 1) || ($message_to == 2 && $notification_type == 3) || ($message_to == 3 && $notification_type == 3)) {
                    $sender_details = array('student_id' => $students_value, 'contact_no' => $student_detail[0]->guardian_phone, 'email' => $student_detail[0]->guardian_email);
                    $this->mailsmsconf->mailsms('student_admission', $sender_details);
                }

                if (($message_to == 2 && $notification_type == 2) || ($message_to == 2 && $notification_type == 3) || ($message_to == 3 && $notification_type == 3)) {
                    $parent_login_detail = array('id' => $students_value, 'credential_for' => 'parent', 'username' => $parent_detail->username, 'password' => $parent_detail->password, 'contact_no' => $student_detail[0]->guardian_phone, 'email' => $student_detail[0]->guardian_email);
                    $this->mailsmsconf->mailsms('login_credential', $parent_login_detail);
                }
            }

            $array = array('status' => 1, 'error' => '', 'message' => $this->lang->line('message_sent_successfully'));
        }
        echo json_encode($array);
    }

    public function dtstudentlist()
    {
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
        $class           = $this->input->post('class_id');
        $section         = $this->input->post('section_id');
        $search_text     = $this->input->post('search_text');
        $search_type     = $this->input->post('srch_type');
        $classlist       = $this->class_model->get();
        $classlist       = $classlist;
        $carray          = array();
        if (!empty($classlist)) {
            foreach ($classlist as $ckey => $cvalue) {
                $carray[] = $cvalue["id"];
            }
        }

        $sch_setting = $this->sch_setting_detail;
        if ($search_type == "search_filter") {
            $resultlist = $this->student_model->searchdtByClassSection($class, $section);
        } elseif ($search_type == "search_full") {
            $resultlist = $this->student_model->searchFullText($search_text, $carray);
        }

        $students = array();
        $students = json_decode($resultlist);
        $dt_data  = array();
        $fields   = $this->customfield_model->get_custom_fields('students', 1);
        if (!empty($students->data)) {
            foreach ($students->data as $student_key => $student) {

                $editbtn   = '';
                $deletebtn = '';
                $viewbtn   = '';
                $collectbtn   = '';
                $status   = '';
                $promo   = '';
                $div_change   = '';
                $class_change   = '';
                $btnstatus   = '';
                $admission_mail_confirm   = '';

                $this->db->where('student_session_id', $student->student_session_id);
                $feeStatus=$this->db->get('student_fees_master')->num_rows();
                if ($feeStatus > 0) {
                    $onclick = 'onclick="myAlert()"';
                    // $link="javascript:void(0);";
                    $link_docs="javascript:void(0);";
                    $target = "";
                } else {
                    $onclick = "";
                    $link_docs = base_url() . "student/student_docs/" . $student->student_session_id;
                    $target = "_blank";
                }
                $link= base_url() . "student/div_change/" . $student->student_session_id;
                
                
                
                $action   = '<div class="dropdown">
                <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    Action
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">';

                $viewbtn = "<li><a target='_blank'  href='" . base_url() . "student/view/" . $student->id . "'>View</a></li>";

                if ($this->rbac->hasPrivilege('student', 'can_edit')) {
                    $userdata = $this->customlib->getUserData();
                    if ($userdata['user_type'] == 'Teacher') {
                        $data = $this->student_model->check_teacher($student->section_id, $student->class_id, $userdata['id']);
                        if (!empty($data)) {
                            $editbtn = "<li><a target='_blank' href='" . base_url() . "student/edit/" . $student->id . "' >" . $this->lang->line('edit') . "</a> </li>";
                        } 
                    } else {
                        $editbtn = "<li><a target='_blank'  href='" . base_url() . "student/edit/" . $student->id . "' >" . $this->lang->line('edit') . "</a> </li>";
                    }
                }
                if ($this->rbac->hasPrivilege('collect_fees', 'can_add')) {

                    $collectbtn = "<li><a target='_blank'  href='" . base_url() . "studentfee/addfee/" . $student->student_session_id . "'>Add Fees</a></li>";
                }
                $status = "<li><a class='view_modal' data-student_id=" . $student->id . "  href='javascript:void(0);'>Status</a></li>";
                $div_change = "<li><a target='".$target."'  href='" .$link. "' > Division Change</a> </li>";
                $class_change = "<li><a target='".$target."' ".$onclick."  href='" . $link_docs. "' > Class Change </a> </li>";
                $btnstatus = "<li><a target='_blank'  href='" . base_url() . "student/student_docs/" . $student->student_session_id  . "' > Documents </a> </li>";
                $admission_card = "<li><a target='_blank' href='". base_url() . "student/admission_card/" . $student->student_session_id . "' > Admission Card </a> </li>";
                if ($this->rbac->hasPrivilege('admission_mail_confirm', 'can_view')) {
                    $admission_mail_confirm = "<li><a class='admission_mail_confirm' href='javascript:void(0);' data-student_session_id=" . $student->student_session_id . " > Email ADM Confirmation </a> </li>";
                }
                // $assign_count = $this->db->where('student_session_id',$student->student_session_id)->get('student_fees_deposite')->num_rows();
                // if ($assign_count > 0) {
                //     $promo = "<li><a href='" . base_url() . "student/delete_promo/" . $student->student_session_id . "' >Cancel Promotion</a></li>";
                // }

                $row   = array();
                $row[] = $student->admission_no;
                $row[] = $student->roll_no;
                $row[] = "<a  target='_blank' href='" . base_url() . "student/view/" . $student->id . "'>" . $this->customlib->getFullName($student->firstname, $student->middlename, $student->lastname, $sch_setting->middlename, $sch_setting->lastname) . "</a>";
                $row[] = $student->class . "(" . $student->section . ")";
                if ($sch_setting->father_name) {
                    $row[] = $student->father_name;
                }

                $row[] = $this->customlib->dateformat($student->dob);

                $row[] = $student->gender;
                if ($sch_setting->category) {
                    $row[] = $student->category;
                }
                if ($sch_setting->mobile_no) {
                    $row[] = $student->mobileno;
                }

                foreach ($fields as $fields_key => $fields_value) {

                    $custom_name   = $fields_value->name;
                    $display_field = $student->$custom_name;
                    if ($fields_value->type == "link") {
                        $display_field = "<a href=" . $student->$custom_name . " target='_blank'>" . $student->$custom_name . "</a>";
                    }
                    $row[] = $display_field;
                }

                $row[] = $action . '' . $viewbtn . '' . $editbtn . '' . $collectbtn . '' . $status . '' . $promo . '' . $div_change.''.$class_change .''.$btnstatus.''.$admission_card.''.$admission_mail_confirm;

                $dt_data[] = $row;
            }
        }
        $sch_setting         = $this->sch_setting_detail;
        $student_detail_view = $this->load->view('student/_searchDetailView', array('sch_setting' => $sch_setting, 'students' => $students), true);
        $json_data           = array(
            "draw"                => intval($students->draw),
            "recordsTotal"        => intval($students->recordsTotal),
            "recordsFiltered"     => intval($students->recordsFiltered),
            "data"                => $dt_data,
            "student_detail_view" => $student_detail_view,
        );

        echo json_encode($json_data);
    }

    //datatable function to check search parameter validation
    public function searchvalidation()
    {
        $class_id   = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');

        $srch_type = $this->input->post('search_type');
        $search_text = $this->input->post('search_text');

        if ($srch_type == 'search_filter') {

            $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
            if ($this->form_validation->run() == true) {

                $params = array('srch_type' => $srch_type, 'class_id' => $class_id, 'section_id' => $section_id);
                $array  = array('status' => 1, 'error' => '', 'params' => $params);
                echo json_encode($array);
            } else {

                $error             = array();
                $error['class_id'] = form_error('class_id');
                $array             = array('status' => 0, 'error' => $error);
                echo json_encode($array);
            }
        } else {
            $params = array('srch_type' => 'search_full', 'class_id' => $class_id, 'section_id' => $section_id, 'search_text' => $search_text);
            $array  = array('status' => 1, 'error' => '', 'params' => $params);
            echo json_encode($array);
        }
    }

    /* this function is used to validate student report   */
    public function studentreportvalidation()
    {
        $class_id    = $this->input->post('class_id');
        $section_id  = $this->input->post('section_id');
        $category_id = $this->input->post('category_id');
        $gender      = $this->input->post('gender');
        $rte         = $this->input->post('rte');

        $srch_type = $this->input->post('search_type');

        if ($srch_type == 'search_filter') {

            $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
            if ($this->form_validation->run() == true) {

                $params = array('srch_type' => $srch_type, 'class_id' => $class_id, 'section_id' => $section_id, 'category_id' => $category_id, 'gender' => $gender, 'rte' => $rte);
                $array  = array('status' => 1, 'error' => '', 'params' => $params);
                echo json_encode($array);
            } else {

                $error             = array();
                $error['class_id'] = form_error('class_id');
                $array             = array('status' => 0, 'error' => $error);
                echo json_encode($array);
            }
        } else {
            $params = array('srch_type' => 'search_full', 'class_id' => $class_id, 'section_id' => $section_id);
            $array  = array('status' => 1, 'error' => '', 'params' => $params);
            echo json_encode($array);
        }
    }

    public function dtstudentreportlist()
    {

        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
        $class           = $this->input->post('class_id');
        $section         = $this->input->post('section_id');
        $category_id     = $this->input->post('category_id');
        $gender          = $this->input->post('gender');
        $rte             = $this->input->post('rte');
        $sch_setting     = $this->sch_setting_detail;

        $result     = $this->student_model->searchdatatableByClassSectionCategoryGenderRte($class, $section, $category_id, $gender, $rte);
        $resultlist = json_decode($result);
        $dt_data    = array();
        if (!empty($resultlist->data)) {
            foreach ($resultlist->data as $resultlist_key => $student) {

                $viewbtn = "<a  href='" . base_url() . "student/view/" . $student->id . "'>" . $this->customlib->getFullName($student->firstname, $student->middlename, $student->lastname, $sch_setting->middlename, $sch_setting->lastname) . "</a>";

                $row   = array();
                $row[] = $student->section;
                $row[] = $student->admission_no;
                $row[] = $viewbtn;
                if ($sch_setting->father_name) {
                    $row[] = $student->father_name;
                }
                if ($student->dob != null && $student->dob != '0000-00-00') {
                    $row[] = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student->dob));
                } else {
                    $row[] = "";
                }
                $row[] = $student->gender;

                if ($sch_setting->category) {
                    $row[] = $student->category;
                }
                if ($sch_setting->mobile_no) {
                    $row[] = $student->mobileno;
                }
                if ($sch_setting->national_identification_no) {
                    $row[] = $student->samagra_id;
                }
                if ($sch_setting->local_identification_no) {
                    $row[] = $student->adhar_no;
                }
                if ($sch_setting->rte) {
                    $row[] = $student->rte;
                }

                $dt_data[] = $row;
            }
        }
        $json_data = array(
            "draw"            => intval($resultlist->draw),
            "recordsTotal"    => intval($resultlist->recordsTotal),
            "recordsFiltered" => intval($resultlist->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    public function getStudentByClassSection()
    {

        $data                 = array();
        $cls_section_id       = $this->input->post('cls_section_id');
        $data['fields']       = $this->customfield_model->get_custom_fields('students', 1);
        $student_list         = $this->student_model->getStudentBy_class_section_id($cls_section_id);
        $data['student_list'] = $student_list;
        $data['sch_setting']  = $this->sch_setting_detail;
        $page                 = $this->load->view('reports/_getStudentByClassSection', $data, true);
        echo json_encode(array('status' => 1, 'page' => $page));
    }


    public function student_info($id = "")
    {
        if (!$this->rbac->hasPrivilege('student_info', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'students');
        $this->session->set_userdata('sub_menu', 'students/student_info');
        $data['title'] = 'Student';
        if ($id != "") {
            $data['update']      = $this->examgroup_model->getnewyearsession($id);
        }
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $session = $this->session_model->get();
        $data['sessionlist'] = $session;
        $newsession_result      = $this->examgroup_model->getnewyearsession();
        $data['newsession_result'] = $newsession_result;

        $data['sch_section_result']      = $this->examgroup_model->getsch_section();

        $this->form_validation->set_rules('sch_section_id', 'Sch section Id', 'trim|required|xss_clean');
        $this->form_validation->set_rules('start_date', 'Start Date', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('student/student_info', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $current_session = $this->sch_current_session;
            $data = array(
                'id' => $this->input->post('id'),
                'sch_section_id' => $this->input->post('sch_section_id'),
                'session_id' => $current_session,
                'start_date' => date('Y-m-d', strtotime($this->input->post('start_date'))),
            );
            $this->examgroup_model->add_newyearsession($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/examgroup/newsessionyear');
        }
    }

    public function student_status($id = "")
    {
        if (!$this->rbac->hasPrivilege('student_status', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'Academics/student_status');
        $data['title'] = 'Student Status';
        if ($id != "") {
            $data['update']      = $this->student_model->get_student_status($id);
        }


        $status_result      = $this->student_model->get_student_status();
        $data['status_result'] = $status_result;


        $this->form_validation->set_rules('student_status', 'Student Status', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('student/student_status', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id' => $this->input->post('id'),
                'student_status' => $this->input->post('student_status'),
            );
            $this->student_model->add_student_status($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('student/student_status');
        }
    }

    public function remove_student_status($id)
    {
        if (!$this->rbac->hasPrivilege('student_status', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Exam Type List';
        $this->student_model->remove_student_status($id);

        redirect('student/student_status');
    }

    public function remove_sibling()
    {
        $id = $this->input->post('id');
        $res = $this->student_model->delete_siblng($id);
        if($res)
        {
            $array = array('success' => 'success', 'error' => '', 'message' => '');
        }
        else
        {
            $array = array('success' => '', 'error' => 'error', 'message' => 'Some error occured');
        }
        echo json_encode($array);
    }
    public function fast_admission()
    {
        if (!$this->rbac->hasPrivilege('fast_admission', 'can_add')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'student/fast_admission');
        $genderList                 = $this->customlib->getGender();
        $data['genderList']         = $genderList;
        $data['sch_setting']        = $this->sch_setting_detail;
        $data['title']              = 'Add Student';
        $data['title_list']         = 'Recently Added Student';
        $data['adm_auto_insert']    = $this->sch_setting_detail->adm_auto_insert;
        $data["student_categorize"] = 'class';
        $session                    = $this->setting_model->getCurrentSession();
        $student_result             = $this->student_model->getRecentRecord();
        $data['studentlist']        = $student_result;
        $class                      = $this->class_model->get('', $classteacher = 'yes');
        $data['classlist']          = $class;
        $userdata                   = $this->customlib->getUserData();
        $category                   = $this->category_model->get();
        $data['categorylist']       = $category;
        $houses                     = $this->student_model->gethouselist();
        $data['houses']             = $houses;
        $data["bloodgroup"]         = $this->blood_group;
        $hostelList                 = $this->hostel_model->get();
        $data['hostelList']         = $hostelList;
        $vehroute_result            = $this->vehroute_model->get();
        $data['vehroutelist']       = $vehroute_result;
        $custom_fields              = $this->customfield_model->getByBelong('students');
        $data['sessionlist']        = $this->session_model->getsessionlist($session);

        foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
            if ($custom_fields_value['validation']) {
                $custom_fields_id   = $custom_fields_value['id'];
                $custom_fields_name = $custom_fields_value['name'];
                $this->form_validation->set_rules("custom_fields[students][" . $custom_fields_id . "]", $custom_fields_name, 'trim|required');
            }
        }

        $this->form_validation->set_rules('session_id', $this->lang->line('session'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('firstname', $this->lang->line('first_name'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('gender', $this->lang->line('gender'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('dob', $this->lang->line('date_of_birth'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('aadhar_name', "Student Aadhar Name.", 'trim|required|xss_clean');
        if ($this->sch_setting_detail->guardian_name) {
            $this->form_validation->set_rules('guardian_name', $this->lang->line('guardian_name'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('guardian_is', $this->lang->line('guardian'), 'trim|required|xss_clean');
        }
        // if ($this->sch_setting_detail->guardian_phone) {
        //     $this->form_validation->set_rules('guardian_phone', $this->lang->line('guardian_phone'), 'trim|required|xss_clean');
        // }

        // if (!empty($this->input->post('pan_no_father'))) {
        //     $this->form_validation->set_rules('pan_no_father', "PAN No. (Father)", 'trim|xss_clean|callback__panRegex');
        // }

        // if (!empty($this->input->post('pan_no_mother'))) {
        //     $this->form_validation->set_rules('pan_no_mother', "PAN No. (mother)", 'trim|xss_clean|callback__panRegex');
        // }

        // if (!empty($this->input->post('parent_aadhar_no'))) {
        //     $this->form_validation->set_rules('parent_aadhar_no', "National Identification Number (Parent)", 'trim|xss_clean|exact_length[12]');
        // }

        $this->form_validation->set_rules(
            'email',
            $this->lang->line('email'),
            'required|trim|valid_email|xss_clean'
            // array(
            //     'valid_email',
            //     array('check_student_email_exists', array($this->student_model, 'check_student_email_exists')),
            // )
        );
        $this->form_validation->set_rules('guardian_email', $this->lang->line('guardian_email'), 'trim|valid_email|xss_clean');

        if (!$this->sch_setting_detail->adm_auto_insert) {

            $this->form_validation->set_rules('admission_no', $this->lang->line('admission_no'), 'trim|required|xss_clean|is_unique[students.admission_no]');
        }
        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_upload');

        if ($this->form_validation->run() == false) {

            $this->load->view('layout/header', $data);
            $this->load->view('student/fast_studentCreate', $data);
            $this->load->view('layout/footer', $data);
        } else {

            $custom_field_post  = $this->input->post("custom_fields[students]");
            $custom_value_array = array();
            if (!empty($custom_field_post)) {

                foreach ($custom_field_post as $key => $value) {
                    $check_field_type = $this->input->post("custom_fields[students][" . $key . "]");
                    $field_value      = is_array($check_field_type) ? implode(",", $check_field_type) : $check_field_type;
                    $array_custom     = array(
                        'belong_table_id' => 0,
                        'custom_field_id' => $key,
                        'field_value'     => $field_value,
                    );
                    $custom_value_array[] = $array_custom;
                }
            }

            $class_id   = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');

            $fees_discount = $this->input->post('fees_discount');

            $vehroute_id    = $this->input->post('vehroute_id');
            $remark          = $this->input->post('remark');
            $total_att       = $this->input->post('total_att');
            $student_att     = $this->input->post('student_att');
            $pass_status     = $this->input->post('pass_status');
            $hostel_room_id = $this->input->post('hostel_room_id');
            if (empty($vehroute_id)) {
                $vehroute_id = 0;
            }
            if (empty($hostel_room_id)) {
                $hostel_room_id = 0;
            }

            $data_insert = array(
                'application_no'         => $this->input->post('application_no'),
                'firstname'         => $this->input->post('firstname'),
                'aadhar_name'       => $this->input->post('aadhar_name'),
                'rte'               => $this->input->post('rte'),
                'state'             => $this->input->post('state'),
                'city'              => $this->input->post('city'),
                'pincode'           => $this->input->post('pincode'),
                'cast'              => $this->input->post('cast'),
                'previous_school'   => $this->input->post('previous_school'),
                'dob'               => $this->customlib->dateFormatToYYYYMMDD($this->input->post('dob')),
                'current_address'   => $this->input->post('current_address'),
                'permanent_address' => $this->input->post('permanent_address'),
                'adhar_no'          => $this->input->post('adhar_no'),
                'samagra_id'        => $this->input->post('samagra_id'),
                'bank_account_no'   => $this->input->post('bank_account_no'),
                'bank_name'         => $this->input->post('bank_name'),
                'ifsc_code'         => $this->input->post('ifsc_code'),
                'guardian_email'    => $this->input->post('guardian_email'),
                'gender'            => $this->input->post('gender'),
                'guardian_name'     => $this->input->post('guardian_name'),
                'guardian_relation' => $this->input->post('guardian_relation'),
                'guardian_phone'    => $this->input->post('guardian_phone'),
                'guardian_address'  => $this->input->post('guardian_address'),
                'vehroute_id'       => $vehroute_id,
                'hostel_room_id'    => $hostel_room_id,
                'note'              => $this->input->post('note'),
                'is_active'         => 'yes',
                'father_status'     => $this->input->post('father_status'),
                'mother_status'     => $this->input->post('mother_status'),
            );
            if ($this->sch_setting_detail->guardian_occupation) {
                $data_insert['guardian_occupation'] = $this->input->post('guardian_occupation');
            }
            if ($this->input->post('gender') == 'Female') {
                $data_insert['image'] = 'uploads/student_images/default_female.jpg';
            } else {
                $data_insert['image'] = 'uploads/student_images/default_male.jpg';
            }
            $house             = $this->input->post('house');
            $blood_group       = $this->input->post('blood_group');
            $measurement_date  = $this->input->post('measure_date');
            $roll_no           = $this->input->post('roll_no');
            $lastname          = $this->input->post('lastname');
            $middlename        = $this->input->post('middlename');
            $category_id       = $this->input->post('category_id');
            $religion          = $this->input->post('religion');
            $mobileno          = $this->input->post('mobileno');
            $email             = $this->input->post('email');
            $admission_date    = $this->input->post('admission_date');
            $height            = $this->input->post('height');
            $weight            = $this->input->post('weight');
            $father_name       = $this->input->post('father_name');
            $father_phone      = $this->input->post('father_phone');
            $father_occupation = $this->input->post('father_occupation');
            $mother_name       = $this->input->post('mother_name');
            $mother_phone      = $this->input->post('mother_phone');
            $mother_occupation = $this->input->post('mother_occupation');
            $uid_no            = $this->input->post('uid_no');
            $pan_no_father     = $this->input->post('pan_no_father');
            $pan_no_mother     = $this->input->post('pan_no_mother');
            $parent_aadhar_no  = $this->input->post('parent_aadhar_no');
            $tc_no             = $this->input->post('tc_no');
            $duplicate_tc_no   = $this->input->post('duplicate_tc_no');
            $disability_type   = $this->input->post('disability_type');
            $disability_card_no = $this->input->post('disability_card_no');
            $disability        = $this->input->post('disability');
            $session_id        = $this->input->post('session_id');

            if ($this->sch_setting_detail->guardian_name) {
                $data_insert['guardian_is'] = $this->input->post('guardian_is');
            }

            if (isset($measurement_date)) {
                $data_insert['measurement_date'] = $this->customlib->dateFormatToYYYYMMDD($this->input->post('measure_date'));
            }

            if (isset($house)) {
                $data_insert['school_house_id'] = $this->input->post('house');
            }
            if (isset($blood_group)) {

                $data_insert['blood_group'] = $this->input->post('blood_group');
            }

            // if (isset($roll_no)) {

            //     $data_insert['roll_no'] = $this->input->post('roll_no');
            // }

            if (isset($lastname)) {

                $data_insert['lastname'] = $this->input->post('lastname');
            }
            if (isset($middlename)) {

                $data_insert['middlename'] = $this->input->post('middlename');
            }
            if (isset($category_id)) {

                $data_insert['category_id'] = $this->input->post('category_id');
            }

            if (isset($religion)) {

                $data_insert['religion'] = $this->input->post('religion');
            }

            if (isset($mobileno)) {

                $data_insert['mobileno'] = $this->input->post('mobileno');
            }

            if (isset($email)) {

                $data_insert['email'] = $this->input->post('email');
            }

            if (isset($admission_date)) {

                $data_insert['admission_date'] = $this->customlib->dateFormatToYYYYMMDD($this->input->post('admission_date'));
            }

            if (isset($height)) {

                $data_insert['height'] = $this->input->post('height');
            }

            if (isset($weight)) {

                $data_insert['weight'] = $this->input->post('weight');
            }

            if (isset($father_name)) {

                $data_insert['father_name'] = $this->input->post('father_name');
            }

            if (isset($father_phone)) {

                $data_insert['father_phone'] = $this->input->post('father_phone');
            }

            if (isset($father_occupation)) {

                $data_insert['father_occupation'] = $this->input->post('father_occupation');
            }

            if (isset($mother_name)) {

                $data_insert['mother_name'] = $this->input->post('mother_name');
            }

            if (isset($mother_phone)) {

                $data_insert['mother_phone'] = $this->input->post('mother_phone');
            }

            if (isset($mother_occupation)) {

                $data_insert['mother_occupation'] = $this->input->post('mother_occupation');
            }

            if (isset($uid_no)) {

                $data_insert['uid_no'] = $this->input->post('uid_no');
            }

            if (isset($pan_no_father)) {

                $data_insert['pan_no_father'] = $this->input->post('pan_no_father');
            }

            if (isset($pan_no_mother)) {

                $data_insert['pan_no_mother'] = $this->input->post('pan_no_mother');
            }

            if (isset($parent_aadhar_no)) {

                $data_insert['parent_aadhar_no'] = $this->input->post('parent_aadhar_no');
            }

            if (isset($tc_no)) {

                $data_insert['tc_no'] = $this->input->post('tc_no');
            }

            if (isset($duplicate_tc_no)) {

                $data_insert['duplicate_tc_no'] = $this->input->post('duplicate_tc_no');
            }

            if (isset($disability_type)) {

                $data_insert['disability_type'] = $this->input->post('disability_type');
            }
            if (isset($disability_card_no)) {

                $data_insert['disability_card_no'] = $this->input->post('disability_card_no');
            }
            if (isset($disability)) {

                $data_insert['disability'] = $this->input->post('disability');
            }

            $insert                            = true;
            $data_setting                      = array();
            $data_setting['id']                = $this->sch_setting_detail->id;
            $data_setting['adm_auto_insert']   = $this->sch_setting_detail->adm_auto_insert;
            $data_setting['adm_update_status'] = $this->sch_setting_detail->adm_update_status;
            $admission_no                      = 0;


            if ($this->sch_setting_detail->adm_auto_insert) {
                if ($this->sch_setting_detail->adm_update_status) {

                    $admission_no = $this->sch_setting_detail->adm_prefix . $this->sch_setting_detail->adm_start_from;

                    $last_student = $this->student_model->lastRecord();
                    if (!empty($last_student)) {

                        $last_admission_digit = str_replace($this->sch_setting_detail->adm_prefix, "", $last_student->admission_no);

                        $admission_no                = $this->sch_setting_detail->adm_prefix . sprintf("%0" . $this->sch_setting_detail->adm_no_digit . "d", $last_admission_digit + 1);
                        $data_insert['admission_no'] = $admission_no;
                    } else {
                        $admission_no                = $this->sch_setting_detail->adm_prefix . $this->sch_setting_detail->adm_start_from;
                        $data_insert['admission_no'] = $admission_no;
                    }
                } else {
                    $admission_no                = $this->sch_setting_detail->adm_prefix . $this->sch_setting_detail->adm_start_from;
                    $data_insert['admission_no'] = $admission_no;
                }

                $admission_no_exists = $this->student_model->check_adm_exists($admission_no);
                if ($admission_no_exists) {
                    $insert = false;
                }
            } else {
                $data_insert['admission_no'] = $this->input->post('admission_no');
            }
            if ($insert) {
                $insert_id = $this->student_model->add($data_insert, $data_setting);
                $data = array(
                    'member_type' => 'student',
                    'member_id' => $insert_id,
                    'library_card_no' => null,
                );

                $inserted_id = $this->librarymanagement_model->add($data);
                if (!empty($custom_value_array)) {
                    $this->customfield_model->insertRecord($custom_value_array, $insert_id);
                }
                $data_new = array(
                    'student_id'    => $insert_id,
                    'class_id'      => $class_id,
                    'section_id'    => $section_id,
                    'session_id'    => $session_id,
                    'fees_discount' => $fees_discount,
                    'remark'        => $remark,
                    'total_att'     => $total_att,
                    'student_att'   => $student_att,
                    'pass_status'   => $pass_status,
                );
                if ($this->input->post('roll_no') == "") {
                    $roll_no_up = $this->student_model->getclassLastRoll($class_id,$section_id,$session_id);
                    $data_new['roll_no'] = $roll_no_up['roll_no'] + 1;
                }else {
                    $data_new['roll_no'] = $this->input->post('roll_no');
                    
                }
                $student_session_id = $this->student_model->add_student_session($data_new);
                $user_password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);

                $sibling_id         = $this->input->post('sibling_id');
                $data_student_login = array(
                    'username' => $this->student_login_prefix . $insert_id,
                    'password' => $user_password,
                    'user_id'  => $insert_id,
                    'role'     => 'student',
                );

                $this->user_model->add($data_student_login);

                $insert_array = array(
                    'session_id' => $session_id,
                    'student_session_id' => $student_session_id,
                    'fees_discount_id' => 5,
                    'is_active'  => 'Yes',
                    'amount'     =>  $this->input->post('amount'),
                );
                $this->feediscount_model->allotdiscount($insert_array);

                $classmst = $this->feegrouptype_model->getclassfess($class_id)->num_rows();
                $classmst_fees = $this->feegrouptype_model->getclassfess($class_id)->result_array();
                if ($classmst > 0) {
                    foreach ($classmst_fees as  $classfees) {

                        $array_fees = array(
                            'student_session_id' => $student_session_id,
                            'fee_session_group_id' => $classfees['fees_group_id'],
                        );

                        $inserted_id = $this->studentfeemaster_model->add($array_fees);
                    }
                }

                if ($sibling_id > 0) {
                    $student_sibling = $this->student_model->get($sibling_id);
                    $update_student  = array(
                        'id'        => $insert_id,
                        'parent_id' => $student_sibling['parent_id'],
                    );
                    $student_sibling = $this->student_model->add($update_student);
                } else {
                    $parent_password   = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
                    $temp              = $insert_id;
                    $data_parent_login = array(
                        'username' => $this->parent_login_prefix . $insert_id,
                        'password' => $parent_password,
                        'user_id'  => 0,
                        'role'     => 'parent',
                        'childs'   => $temp,
                    );
                    $ins_parent_id  = $this->user_model->add($data_parent_login);
                    $update_student = array(
                        'id'        => $insert_id,
                        'parent_id' => $ins_parent_id,
                    );
                    $this->student_model->add($update_student);
                }

                // if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                //     $fileInfo = pathinfo($_FILES["file"]["name"]);
                //     $img_name = $insert_id . '.' . $fileInfo['extension'];
                //     move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/student_images/" . $img_name);
                //     $data_img = array('id' => $insert_id, 'image' => 'uploads/student_images/' . $img_name);
                //     $this->student_model->add($data_img);
                // }

                // if (isset($_FILES["father_pic"]) && !empty($_FILES['father_pic']['name'])) {
                //     $fileInfo = pathinfo($_FILES["father_pic"]["name"]);
                //     $img_name = $insert_id . "father" . '.' . $fileInfo['extension'];
                //     move_uploaded_file($_FILES["father_pic"]["tmp_name"], "./uploads/student_images/" . $img_name);
                //     $data_img = array('id' => $insert_id, 'father_pic' => 'uploads/student_images/' . $img_name);
                //     $this->student_model->add($data_img);
                // }
                // if (isset($_FILES["mother_pic"]) && !empty($_FILES['mother_pic']['name'])) {
                //     $fileInfo = pathinfo($_FILES["mother_pic"]["name"]);
                //     $img_name = $insert_id . "mother" . '.' . $fileInfo['extension'];
                //     move_uploaded_file($_FILES["mother_pic"]["tmp_name"], "./uploads/student_images/" . $img_name);
                //     $data_img = array('id' => $insert_id, 'mother_pic' => 'uploads/student_images/' . $img_name);
                //     $this->student_model->add($data_img);
                // }

                // if (isset($_FILES["guardian_pic"]) && !empty($_FILES['guardian_pic']['name'])) {
                //     $fileInfo = pathinfo($_FILES["guardian_pic"]["name"]);
                //     $img_name = $insert_id . "guardian" . '.' . $fileInfo['extension'];
                //     move_uploaded_file($_FILES["guardian_pic"]["tmp_name"], "./uploads/student_images/" . $img_name);
                //     $data_img = array('id' => $insert_id, 'guardian_pic' => 'uploads/student_images/' . $img_name);
                //     $this->student_model->add($data_img);
                // }

                // if (isset($_FILES["first_doc"]) && !empty($_FILES['first_doc']['name'])) {
                //     $uploaddir = './uploads/student_documents/' . $insert_id . '/';
                //     if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                //         die("Error creating folder $uploaddir");
                //     }
                //     $fileInfo    = pathinfo($_FILES["first_doc"]["name"]);
                //     $first_title = $this->input->post('first_title');
                //     $file_name   = $_FILES['first_doc']['name'];
                //     $exp         = explode(' ', $file_name);
                //     $imp         = implode('_', $exp);
                //     $img_name    = $uploaddir . $imp;
                //     move_uploaded_file($_FILES["first_doc"]["tmp_name"], $img_name);
                //     $data_img = array('student_id' => $insert_id, 'title' => $first_title, 'doc' => $imp);
                //     $this->student_model->adddoc($data_img);
                // }
                // if (isset($_FILES["second_doc"]) && !empty($_FILES['second_doc']['name'])) {
                //     $uploaddir = './uploads/student_documents/' . $insert_id . '/';
                //     if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                //         die("Error creating folder $uploaddir");
                //     }
                //     $fileInfo     = pathinfo($_FILES["second_doc"]["name"]);
                //     $second_title = $this->input->post('second_title');
                //     $file_name    = $_FILES['second_doc']['name'];
                //     $exp          = explode(' ', $file_name);
                //     $imp          = implode('_', $exp);
                //     $img_name     = $uploaddir . $imp;
                //     move_uploaded_file($_FILES["second_doc"]["tmp_name"], $img_name);
                //     $data_img = array('student_id' => $insert_id, 'title' => $second_title, 'doc' => $imp);
                //     $this->student_model->adddoc($data_img);
                // }

                // if (isset($_FILES["fourth_doc"]) && !empty($_FILES['fourth_doc']['name'])) {
                //     $uploaddir = './uploads/student_documents/' . $insert_id . '/';
                //     if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                //         die("Error creating folder $uploaddir");
                //     }
                //     $fileInfo     = pathinfo($_FILES["fourth_doc"]["name"]);
                //     $fourth_title = $this->input->post('fourth_title');
                //     $file_name    = $_FILES['fourth_doc']['name'];
                //     $exp          = explode(' ', $file_name);
                //     $imp          = implode('_', $exp);
                //     $img_name     = $uploaddir . $imp;
                //     move_uploaded_file($_FILES["fourth_doc"]["tmp_name"], $img_name);
                //     $data_img = array('student_id' => $insert_id, 'title' => $fourth_title, 'doc' => $imp);
                //     $this->student_model->adddoc($data_img);
                // }
                // if (isset($_FILES["fifth_doc"]) && !empty($_FILES['fifth_doc']['name'])) {
                //     $uploaddir = './uploads/student_documents/' . $insert_id . '/';
                //     if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                //         die("Error creating folder $uploaddir");
                //     }
                //     $fileInfo    = pathinfo($_FILES["fifth_doc"]["name"]);
                //     $fifth_title = $this->input->post('fifth_title');
                //     $file_name   = $_FILES['fifth_doc']['name'];
                //     $exp         = explode(' ', $file_name);
                //     $imp         = implode('_', $exp);
                //     $img_name    = $uploaddir . $imp;

                //     move_uploaded_file($_FILES["fifth_doc"]["tmp_name"], $img_name);
                //     $data_img = array('student_id' => $insert_id, 'title' => $fifth_title, 'doc' => $imp);
                //     $this->student_model->adddoc($data_img);
                // }

                $sender_details = array('student_id' => $insert_id, 'contact_no' => $this->input->post('guardian_phone'), 'email' => $this->input->post('guardian_email'));
                $this->mailsmsconf->mailsms('student_admission', $sender_details);

                $student_login_detail = array('id' => $insert_id, 'credential_for' => 'student', 'username' => $this->student_login_prefix . $insert_id, 'password' => $user_password, 'contact_no' => $this->input->post('mobileno'), 'email' => $this->input->post('email'));
                $this->mailsmsconf->mailsms('login_credential', $student_login_detail);

                if ($sibling_id > 0) {
                } else {
                    $parent_login_detail = array('id' => $insert_id, 'credential_for' => 'parent', 'username' => $this->parent_login_prefix . $insert_id, 'password' => $parent_password, 'contact_no' => $this->input->post('guardian_phone'), 'email' => $this->input->post('guardian_email'));
                    $this->mailsmsconf->mailsms('login_credential', $parent_login_detail);
                }

                $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('success_message') . '</div>');
                $this->add_student_fees($student_session_id);
                // redirect('student/fast_admission');
            } else {

                $data['error_message'] = $this->lang->line('admission_no') . ' ' . $admission_no . ' ' . $this->lang->line('already_exists');
                $this->load->view('layout/header', $data);
                $this->load->view('student/fast_studentCreate', $data);
                $this->load->view('layout/footer', $data);
            }
        }
    }

    public function delete_promo($student_session_id)
    {
        $this->student_model->delete_student_session($student_session_id);
        $this->session->set_flashdata('msg', '<i class="fa fa-check-square-o" aria-hidden="true"></i> ' . $this->lang->line('delete_message') . '');
        redirect('student/search');
    }

    public function add_student_fees($student_session_id)
    {
        $this->db->where('id', $student_session_id);
        $student_session = $this->db->get('student_session')->row_array();


        $data['student_session_id'] = $student_session_id;
        $data['class_id'] = $class_id = $student_session['class_id'];
        $data['section_id'] = $section_id = $student_session['section_id'];
        $data['session_id'] = $this->setting_model->getCurrentSession();

        $data['current_session']    = $this->setting_model->getCurrentSession();

        $data['fees_array']         = $this->feegrouptype_model->getclassfess($class_id)->result_array();

        $session                    = $this->setting_model->getCurrentSession();
        $data['userdata']           = $this->customlib->getUserData();
        $feesdiscount_result = $this->feediscount_model->get();
        $data['feediscountList'] = $feesdiscount_result;

        $category                     = $this->category_model->get();
        $data['categorylist']         = $category;
        $data['sch_setting']  = $this->sch_setting_detail;
        $student              = $this->student_model->getByStudentSession($student_session_id);
        $data['student']      = $student;

        $this->load->view('layout/header', $data);
        $this->load->view('student/studentclassfeesShow', $data);
        $this->load->view('layout/footer', $data);

    }

    public function feesubmit()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('student_session_id', 'student_session_id', 'trim|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'student_session_id' => form_error('student_session_id'),
            );
            $array = array('status' => 0, 'error' => $data);
            echo json_encode($array);
        } else {
            $current_session    = $this->setting_model->getCurrentSession();
            $student_session_id = $this->input->post('student_session_id');
            $fee_group_id = $this->input->post('fees_group_id[]');
            $discount_id = $this->input->post('discount_id[]');
            if (!empty($discount_id)) {
                foreach ($discount_id as $key => $discount) {

                    $insert_array = array(
                        'session_id' => $current_session,
                        'student_session_id' => $student_session_id,
                        'fees_discount_id' => $discount,
                        'is_active'  => 'Yes',
                    );

                    $this->feediscount_model->allotdiscount($insert_array);
                }
            }

            // echo "<pre>";
            // print_r ($student_session_id);
            // print_r ($fee_group_id);
            // echo "</pre>";die;

            if (!empty($fee_group_id)) {
                foreach ($fee_group_id as  $feegroup) {

                    $array_fees = array(
                        'student_session_id' => $student_session_id,
                        'fee_session_group_id' => $feegroup,
                        'session_id' => $current_session,
                    );


                    $inserted_id = $this->studentfeemaster_model->add($array_fees);
                }
            }
            // $array  = array('status' => 1, 'error' => '','message' => $this->lang->line('success_message'));
            // echo json_encode($array);
            // redirect('student/create');
            $this->student_docs($student_session_id,$id=1);
        }
    }

    public function generate_roll()
    {

        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'student/generate_roll_no');
        $this->session->set_userdata('subsub_menu', 'student/generate_roll_no/student_report');


        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $data['sch_setting']     = $this->sch_setting_detail;
        $data['fields']          = $this->customfield_model->get_custom_fields('students', 1);

        $this->load->view('layout/header', $data);
        $this->load->view('student/generate_roll_no', $data);
        $this->load->view('layout/footer', $data);
    }

    public function generate_roll_no()
    {

        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section_id'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'class_id' => form_error('class_id'),
                'section_id' => form_error('section_id'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {


            $class_id   = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $by_wise = $this->input->post('by_wise');
            $userdata = $this->customlib->getUserData();
            $role_id  = $userdata["role_id"];
            $class_teacher = $this->classteacher_model->checkclassteacher($class_id, $section_id, $userdata['id']);
            if (isset($role_id) && $userdata["role_id"] == 2 && $class_teacher == 0) {
                $array = array('status' => 'fail', 'error' => '', 'message' => "You Don't Have Permission !");
                echo json_encode($array);
            } else {

                $resultlist          = $this->student_model->getByClassSection($class_id, $section_id, $by_wise);

                $i = 1;
                foreach ($resultlist as  $student) {

                    $arrayRoll = array('roll_no' => $i);
                    $student_session_id = $student['student_session_id'];

                    $this->student_model->generate_roll_no($student_session_id, $arrayRoll);
                    $i++;
                }
                $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
                echo json_encode($array);
            }
        }
    }

    public function getstudentsbyclass_section()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $class   = $this->input->post('class_id');
            $section = $this->input->post('section_id');

            $resultlist          = $this->student_model->searchByClassSection($class, $section);
            $data['resultlist']  = $resultlist;

            echo json_encode($resultlist);
        }
    }

    public function div_change($student_session_id = null)
    {
        $student              = $this->student_model->getByStudentSession($student_session_id);
        $data['student']      = $student;
        $section_result      = $this->section_model->get();
        $data['sectionlist'] = $section_result;
        $data['student_session_id'] = $student_session_id;

        $data['current_session']    = $this->setting_model->getCurrentSession();
        $category                     = $this->category_model->get();
        $data['categorylist']         = $category;
        $data['sch_setting']  = $this->sch_setting_detail;
        $data['userdata'] = $userdata          = $this->customlib->getUserData();

        if ($this->form_validation->run() == false) {

            $this->load->view('layout/header', $data);
            $this->load->view('student/studentdiv_change', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $student_session_id = $this->input->post('student_session_id');

            $data = array(
                'section_id' => $this->input->post('section_id'),
            );
            $this->student_model->change_div($student_session_id, $data);

            $dataArray = array(
                'student_seesion_id' => $student_session_id,
                'new_division_id' => $this->input->post('section_id'),
                'remark' => $this->input->post('remark'),
                'created_at' => date('Y-m-d h:i:s'),
                'created_by' => $userdata['name'],
            );

            $this->student_model->div_insert($dataArray);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            // redirect('student/search');
        }


        // $this->load->view('layout/header', $data);
        // $this->load->view('student/studentdiv_change', $data);
        // $this->load->view('layout/footer', $data);
    }

    public function div_update()
    {
        $data['userdata'] = $userdata          = $this->customlib->getUserData();
        // if ($this->form_validation->run() == false) {

        // $this->load->view('layout/header', $data);
        // $this->load->view('student/studentdiv_change', $data);
        // $this->load->view('layout/footer', $data);
        // } else {
        $student_session_id = $this->input->post('student_session_id');

        $data = array(
            'section_id' => $this->input->post('section_id'),
        );

        $this->student_model->change_div($student_session_id, $data);

        $dataArray = array(
            'student_seesion_id' => $student_session_id,
            'new_division_id' => $this->input->post('section_id'),
            'remark' => $this->input->post('remark'),
            'created_at' => date('Y-m-d h:i:s'),
            'created_by' => $userdata['name'],
        );

        // echo "<pre>";
        // print_r ($dataArray);die;
        // echo "</pre>";


        $this->student_model->div_insert($dataArray);
        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
        redirect('student/search');
        // }
    }

    public function bulk_division_change()
    {
        if (!$this->rbac->hasPrivilege('bulk_division_change', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'student/bulk_division_change');
        $data['title']           = 'Exam Schedule';
        $class                   = $this->class_model->get('', $classteacher = 'yes');
        $data['classlist']       = $class;
        $userdata                = $this->customlib->getUserData();
        $data['sch_setting']     = $this->sch_setting_detail;
        $feecategory             = $this->feecategory_model->get();
        $data['feecategorylist'] = $feecategory;
        $session_result          = $this->session_model->get();
        $data['sessionlist']     = $session_result;
        $section_result      = $this->section_model->get();
        $data['sectionlist'] = $section_result;
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('to_section_id', "To " . $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        // $this->form_validation->set_rules('section_promote_id', $this->lang->line('session'), 'trim|required|xss_clean');
        // $this->form_validation->set_rules('session_id', $this->lang->line('session'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == true) {
            $class                         = $this->input->post('class_id');
            $section                       = $this->input->post('section_id');
            $to_section_id                 = $this->input->post('to_section_id');
            $data['to_section_id']  = $to_section_id;


            $resultlist          = $this->student_model->searchByClassSection($class, $section);
            $data['resultlist']  = $resultlist;
        }

        $this->load->view('layout/header', $data);
        $this->load->view('student/bulk_division_change', $data);
        $this->load->view('layout/footer', $data);
    }

    public function bulk_div_change()
    {
        $this->form_validation->set_rules('to_section_id', "To " . $this->lang->line('section'), 'required|trim|xss_clean');
        if ($this->form_validation->run() == false) {
        } else {
            $student_list = $this->input->post('student_list');
            
            if (!empty($student_list) && isset($student_list)) {
                $data = array(
                    'section_id' => $this->input->post('to_section_id'),
                );

                $this->student_model->change_div($student_list, $data);                
                // foreach ($student_list as $key => $value) {
                //     $data = array(
                //         'section_id' => $this->input->post('to_section_id'),
                //     );

                //     $this->student_model->change_div($value, $data);
                // }
            }
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('student/bulk_division_change');
        }
    }

    public function bulk_class_change()
    {
        if (!$this->rbac->hasPrivilege('bulk_class_change', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'student/bulk_class_change');
        $data['title']           = 'Exam Schedule';
        $class                   = $this->class_model->get('', $classteacher = 'yes');
        $data['classlist']       = $class;
        $userdata                = $this->customlib->getUserData();
        $data['sch_setting']     = $this->sch_setting_detail;
        $feecategory             = $this->feecategory_model->get();
        $data['feecategorylist'] = $feecategory;
        $session_result          = $this->session_model->get();
        $data['sessionlist']     = $session_result;
        $section_result      = $this->section_model->get();
        $data['sectionlist'] = $section_result;
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('to_section_id', "To " . $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('to_class_id',"To " . $this->lang->line('class'), 'trim|required|xss_clean');
        // $this->form_validation->set_rules('session_id', $this->lang->line('session'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == true) {
            $class                         = $this->input->post('class_id');
            $section                       = $this->input->post('section_id');
            $to_class_id                 = $this->input->post('to_class_id');
            $to_section_id                 = $this->input->post('to_section_id');
            $data['to_section_id']  = $to_section_id;
            $data['to_class_id']  = $to_class_id;


            $resultlist          = $this->student_model->searchByClassSection($class, $section);
            $data['resultlist']  = $resultlist;
        }

        $this->load->view('layout/header', $data);
        $this->load->view('student/bulk_class_change', $data);
        $this->load->view('layout/footer', $data);
    }

    public function bulk_class_change_validation()
    {
        
        $this->form_validation->set_rules('to_class_id', "To " . $this->lang->line('class'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('to_section_id', "To " . $this->lang->line('section'), 'required|trim|xss_clean');
        if ($this->form_validation->run() == false) {
        } else {
            $student_list = $this->input->post('student_list');
            
            if (!empty($student_list) && isset($student_list)) {
                $data = array(
                    'class_id' => $this->input->post('to_class_id'),
                    'section_id' => $this->input->post('to_section_id'),
                );
                
                $this->student_model->change_class($student_list, $data);                
            }
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('student/bulk_class_change');
        }
    }

    public function roll_list()
    {
        if (!$this->rbac->hasPrivilege('roll_list', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'student/roll_list');
        $data['title']           = 'Student Search';
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $data['sch_setting']     = $this->sch_setting_detail;
        $data['fields']          = $this->customfield_model->get_custom_fields('students', 1);
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $status_result      = $this->student_model->get_student_status();
        $data['status_result'] = $status_result;

        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
        } else {
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');

            $studentList = $this->student_model->getStudentByClassSectionID($class_id, $section_id);
            //echo "<pre>";
            //print_r($studentList);die();
            
            $data['studentList'] = $studentList;
            $data['classess'] = $this->class_model->getAll($class_id);
            $data['section'] = $this->section_model->get($section_id);
            $data['session'] = $this->session_model->get($this->setting_model->getCurrentSession());

        }

        $this->load->view('layout/header', $data);
        $this->load->view('student/roll_list', $data);
        $this->load->view('layout/footer', $data);
    }
    public function attendence_sheet()
    {
        if (!$this->rbac->hasPrivilege('attendence_sheet', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'student/attendence_sheet');
        $data['title']           = 'Student Search';
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $data['sch_setting']     = $this->sch_setting_detail;
        $data['fields']          = $this->customfield_model->get_custom_fields('students', 1);
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $status_result      = $this->student_model->get_student_status();
        $data['status_result'] = $status_result;

       
        $this->load->view('layout/header', $data);
        $this->load->view('student/attendence_sheet', $data);
        $this->load->view('layout/footer', $data);
    }

    public function attend_sheet_print()
    {
        
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('month', $this->lang->line('month'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $json = array(
                "error" => true,
                'class_id' => form_error('class_id', '<p>', '</p>'),
                'section_id' => form_error('section_id', '<p>', '</p>'),
                'month' => form_error('month', '<p>', '</p>'),
            );
        } else {
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $data['month'] = $month = $this->input->post('month');
            $data['classSection'] = $this->classsection_model->getDetailbyClassSection($class_id,$section_id);

            $studentList = $this->student_model->getStudentByClassSectionID($class_id, $section_id);
            $data['studentList'] = $studentList;
            $data['sch_setting'] = $this->setting_model->getSetting();

            $dataview = $this->load->view('student/attendence_sheet_print', $data,TRUE);
            $json = array(
				"success" => " Success!!!!",
                "response" => $this->load->view('student/attendence_sheet_print', $data,TRUE) 
			);
        }
        echo json_encode($json);
    }

    public function bulk_house_change()
    {
        if (!$this->rbac->hasPrivilege('bulk_house_change', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'student/bulk_house_change');
        $data['title']           = 'Exam Schedule';
        $class                   = $this->class_model->get('', $classteacher = 'yes');
        $data['classlist']       = $class;
        $userdata                = $this->customlib->getUserData();
        $data['sch_setting']     = $this->sch_setting_detail;
        $feecategory             = $this->feecategory_model->get();
        $data['feecategorylist'] = $feecategory;
        $session_result          = $this->session_model->get();
        $data['sessionlist']     = $session_result;
        $section_result      = $this->section_model->get();
        $data['sectionlist'] = $section_result;
        $houselist      = $this->student_model->gethouse();
        $data['houselist'] = $houselist;
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('house_id', "To House" , 'trim|required|xss_clean');
        // $this->form_validation->set_rules('section_promote_id', $this->lang->line('session'), 'trim|required|xss_clean');
        // $this->form_validation->set_rules('session_id', $this->lang->line('session'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == true) {
            $class                         = $this->input->post('class_id');
            $section                       = $this->input->post('section_id');
            $house_id                 = $this->input->post('house_id');
            $data['house_id']  = $house_id;


            $resultlist          = $this->student_model->searchByClassSection($class, $section);
            $data['resultlist']  = $resultlist;
        }

        $this->load->view('layout/header', $data);
        $this->load->view('student/bulk_house_change', $data);
        $this->load->view('layout/footer', $data);
    }

    public function bulkHouseChange()
    {
        $this->form_validation->set_rules('house_id', "To House", 'required|trim|xss_clean');
        if ($this->form_validation->run() == false) {
        } else {
            $student_list = $this->input->post('student_list');
            
            if (!empty($student_list) && isset($student_list)) {
                $data = array(
                    'house_id' => $this->input->post('house_id'),
                );

                $this->student_model->change_div($student_list, $data);                
                
            }
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('student/bulk_house_change');
        }
    }

    public function checklist_mst($id=null)
    {
        if (!$this->rbac->hasPrivilege('checklist_mst', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'System Settings/checklist_mst');
        $data['title'] = 'Check List';
        
        $checklist = $this->student_model->getchecklist();
        $data['checklist'] = $checklist;
 
        if ($id != null) {
            $data['update']  = $this->student_model->getchecklist($id);
        }

        $this->form_validation->set_rules('type', $this->lang->line('type'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('item_name', "Item Name", 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            
        } else {


            $insert_array = array(
                'id' => $this->input->post('id'),
                'type' => $this->input->post('type'),
                'item_name' => $this->input->post('item_name'),
            ); 

            $feegroup_result = $this->student_model->addchecklist($insert_array);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('student/checklist_mst');
        }
 
        $this->load->view('layout/header', $data);
        $this->load->view('student/checklist_mst', $data);
        $this->load->view('layout/footer', $data);
    }

    public function deleteChecklist_mst($id)
    {
        if (!$this->rbac->hasPrivilege('checklist_mst', 'can_delete')) {
            access_denied();
        }
        $this->student_model->deleteChecklist_mst($id);
        $this->session->set_flashdata('msg', '<i class="fa fa-check-square-o" aria-hidden="true"></i> ' . $this->lang->line('delete_message') . '');
        redirect('student/checklist_mst');
    }

    public function reminder($id = null)
    {
        if (!$this->rbac->hasPrivilege('reminder', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Communicate');
        $this->session->set_userdata('sub_menu', 'student/reminder');
        $data['title'] = 'Check List';
        
        $resultlist = $this->student_model->getReminderlist();
        $data['resultlist'] = $resultlist;
 
        if ($id != null) {
            $data['update']  = $this->student_model->getReminderlist($id);
        }

        $this->form_validation->set_rules('reminder_type', $this->lang->line('reminder')." ".$this->lang->line('type'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('reminder_category', $this->lang->line('reminder')." ". $this->lang->line('category'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject', $this->lang->line('subject'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('description', $this->lang->line('description'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            
        } else {


            $insert_array = array(
                'id' => $this->input->post('id'),
                'reminder_type' => $this->input->post('reminder_type'),
                'reminder_category' => $this->input->post('reminder_category'),
                'date' => date('Y-m-d',strtotime($this->input->post('date'))),
                'reminder_period' => $this->input->post('reminder_period'),
                'subject' => $this->input->post('subject'),
                'description' => $this->input->post('description'),
            ); 

            $this->student_model->addreminder($insert_array);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('student/reminder');
        }
 
        $this->load->view('layout/header', $data);
        $this->load->view('student/reminder', $data);
        $this->load->view('layout/footer', $data);
    }

    public function deletereminder($id)
    {
        if (!$this->rbac->hasPrivilege('reminder', 'can_delete')) {
            access_denied();
        }
        $this->student_model->deletereminder($id);
        $this->session->set_flashdata('msg', '<i class="fa fa-check-square-o" aria-hidden="true"></i> ' . $this->lang->line('delete_message') . '');
        redirect('student/reminder');
    }


    public function inactive_student()
    {
        if (!$this->rbac->hasPrivilege('inactive_student', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'student/inactive_student');
        // ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);        
        $data['title']           = 'Inactive Student';
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $data['sch_setting']     = $this->sch_setting_detail;
        $data['fields']          = $this->customfield_model->get_custom_fields('students', 1);
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $status_result      = $this->student_model->get_student_status();
        $data['status_result'] = $status_result;
        $this->load->view('layout/header', $data);
        $this->load->view('student/inactive_student', $data);
        $this->load->view('layout/footer', $data);
    }

    public function inactivesearchvalidation()
    {
        $class_id   = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');

        $srch_type = $this->input->post('search_type');
        $search_text = $this->input->post('search_text');

        if ($srch_type == 'search_filter') {

            $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
            if ($this->form_validation->run() == true) {

                $params = array('srch_type' => $srch_type, 'class_id' => $class_id, 'section_id' => $section_id);
                $array  = array('status' => 1, 'error' => '', 'params' => $params);
                echo json_encode($array);
            } else {

                $error             = array();
                $error['class_id'] = form_error('class_id');
                $array             = array('status' => 0, 'error' => $error);
                echo json_encode($array);
            }
        } else {
            $params = array('srch_type' => 'search_full', 'class_id' => $class_id, 'section_id' => $section_id, 'search_text' => $search_text);
            $array  = array('status' => 1, 'error' => '', 'params' => $params);
            echo json_encode($array);
        }
    }

    public function inactivedtstudentlist()
    {
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
        $class           = $this->input->post('class_id');
        $section         = $this->input->post('section_id');
        $search_text     = $this->input->post('search_text');
        $search_type     = $this->input->post('srch_type');
        $classlist       = $this->class_model->get();
        $classlist       = $classlist;
        $carray          = array();
        if (!empty($classlist)) {
            foreach ($classlist as $ckey => $cvalue) {
                $carray[] = $cvalue["id"];
            }
        }

        $sch_setting = $this->sch_setting_detail;
        if ($search_type == "search_filter") {
            $resultlist = $this->student_model->searchdtByClassSectionInactive($class, $section);
        } elseif ($search_type == "search_full") {
            $resultlist = $this->student_model->searchFullTextInactive($search_text, $carray);
        }

        $students = array();
        $students = json_decode($resultlist);
        $dt_data  = array();
        $fields   = $this->customfield_model->get_custom_fields('students', 1);
        if (!empty($students->data)) {
            foreach ($students->data as $student_key => $student) {

                $editbtn   = '';
                $deletebtn = '';
                $viewbtn   = '';
                $collectbtn   = '';
                $status   = '';
                $promo   = '';
                $div_change   = '';
                $btnstatus   = '';

                $action   = '<div class="dropdown">
                <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    Action
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">';

                $viewbtn = "<li><a target='_blank'  href='" . base_url() . "student/view/" . $student->id . "'>View</a></li>";

                if ($this->rbac->hasPrivilege('inactive_student', 'can_edit')) {
                    $userdata = $this->customlib->getUserData();
                    if ($userdata['user_type'] == 'Teacher') {
                        $data = $this->student_model->check_teacher($student->section_id, $student->class_id, $userdata['id']);
                        if (!empty($data)) {
                            $editbtn = "<li><a target='_blank' href='" . base_url() . "student/edit/" . $student->id . "' >" . $this->lang->line('edit') . "</a> </li>";
                        } else {
                        }
                    } else {
                        $editbtn = "<li><a target='_blank'  href='" . base_url() . "student/edit/" . $student->id . "' >" . $this->lang->line('edit') . "</a> </li>";
                    }
                }
                if ($this->rbac->hasPrivilege('collect_fees', 'can_add')) {

                    $collectbtn = "<li><a target='_blank'  href='" . base_url() . "studentfee/addfee/" . $student->student_session_id . "'>Add Fees</a></li>";
                }
                $status = "<li><a class='view_modal' data-student_id=" . $student->id . "  href='javascript:void(0);'>Status</a></li>";
                $div_change = "<li><a target='_blank'  href='" . base_url() . "student/div_change/" . $student->student_session_id  . "' > Division Change</a> </li>";
                $btnstatus = "<li><a target='_blank'  href='" . base_url() . "student/student_docs/" . $student->student_session_id  . "' > Documents </a> </li>";
                // $assign_count = $this->db->where('student_session_id',$student->student_session_id)->get('student_fees_deposite')->num_rows();
                // if ($assign_count > 0) {
                //     $promo = "<li><a href='" . base_url() . "student/delete_promo/" . $student->student_session_id . "' >Cancel Promotion</a></li>";
                // }

                $row   = array();
                $row[] = $student->admission_no;
                $row[] = $student->roll_no;
                $row[] = "<a  target='' href='" . base_url() . "student/view/" . $student->id . "'>" . $this->customlib->getFullName($student->firstname, $student->middlename, $student->lastname, $sch_setting->middlename, $sch_setting->lastname) . "</a>";
                $row[] = $student->class . "(" . $student->section . ")";
                if ($sch_setting->father_name) {
                    $row[] = $student->father_name;
                }

                $row[] = $this->customlib->dateformat($student->dob);

                $row[] = $student->gender;
                $row[] = $student->student_status;
                if ($sch_setting->mobile_no) {
                    $row[] = $student->mobileno;
                }

                foreach ($fields as $fields_key => $fields_value) {

                    $custom_name   = $fields_value->name;
                    $display_field = $student->$custom_name;
                    if ($fields_value->type == "link") {
                        $display_field = "<a href=" . $student->$custom_name . " target='_blank'>" . $student->$custom_name . "</a>";
                    }
                    $row[] = $display_field;
                }

                //$row[] = $action . '' . $viewbtn . '' . $editbtn . '' . $collectbtn . '' . $status . '' . $promo . '' . $div_change;
                $row[] = $action . '' . $viewbtn.'' . $editbtn. '' . $status  . '' . $collectbtn.'' . $btnstatus;

                $dt_data[] = $row;
            }
        }
        $sch_setting         = $this->sch_setting_detail;
        $student_detail_view = $this->load->view('student/_searchDetailView', array('sch_setting' => $sch_setting, 'students' => $students), true);
        $json_data           = array(
            "draw"                => intval($students->draw),
            "recordsTotal"        => intval($students->recordsTotal),
            "recordsFiltered"     => intval($students->recordsFiltered),
            "data"                => $dt_data,
            "student_detail_view" => $student_detail_view,
        );

        echo json_encode($json_data);
    }

    public function medical_master($id = "")
    {
        if (!$this->rbac->hasPrivilege('medical_master', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'student/medical_master');
        $data['title'] = 'Student';
        if ($id != "") {
            $data['update']      = $this->student_model->getMedicalMaster($id);
        }
        $session = $this->session_model->get();
        $data['sessionlist'] = $session;

        $data['results'] = $this->student_model->getMedicalMaster();
        $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('student/medical_master', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $current_session = $this->setting_model->getCurrentSession();
            $data = array(
                'id' => $this->input->post('id'),
                'name' => $this->input->post('name'),
                'session_id' => $current_session,
            );
            $this->student_model->addMedicalMst($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('student/medical_master');
        }
    }

    public function deletemed_master()
    {
        if (!$this->rbac->hasPrivilege('medical_master', 'can_delete')) {
            access_denied();
        }
        $id = $this->input->post('id');
        $this->student_model->removeMedMst($id);
        $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('delete_message'));
        echo json_encode($array);
    }

    public function medical_exam()
    {
        if (!$this->rbac->hasPrivilege('medical_exam', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'student/medical_exam');
        $data['title']           = 'Student Search';
        $data['sch_setting'] = $this->sch_setting_detail;
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $this->load->view('layout/header', $data);
        $this->load->view('student/studentSearchMedical', $data);
        $this->load->view('layout/footer', $data);
    }

    public function ajaxSearchMed()
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
            $printbtn  = "";
            $addbtn  = "";
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
                $addbtn = "<a target='_blank' href=" . site_url('student/medicalExam/' . $student->student_session_id) . "  class='btn btn-default btn-xs mt-5' data-toggle='tooltip' title='Medical Exam' ><i class='fa fa-plus'></i></a>";
                $printbtn = "<a data-placement='left'  data-student_session_id=".$student->student_session_id."  href='javascript:void(0)'  class='printmed btn btn-default btn-xs mt-5 pull-right' data-toggle='tooltip' title='" . $this->lang->line('print') . "' ><i class='fa fa-print'></i></a>";
                $row[] = $printbtn." ". $addbtn ;

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

    public function medicalExam($student_session_id)
    {
        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'student/medical_exam');

        $student              = $this->student_model->getByStudentSession($student_session_id);
        $data['student']      = $student; 
        $category                = $this->category_model->get();
        $data['categorylist']    = $category;
        $data['results'] = $this->student_model->getMedicalMaster();

        $this->load->view('layout/header', $data);
        // $this->load->view('student/medicalexam', $data);
        $this->load->view('student/medicalexamNew', $data);
        $this->load->view('layout/footer', $data);
    }

    public function medicalValid()
    {
        $this->form_validation->set_rules('exammst_id', "Medical Exam", 'trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('student/medicalexamNew', $data);
            $this->load->view('layout/footer', $data);
        } else {
            
            $current_session = $this->setting_model->getCurrentSession();
            $examMstId = $this->input->post('exammst_id');
            if (!empty($examMstId)) {
                $i = 0;
                foreach ($examMstId as  $master) {
                    $data = array(
                        'id' => $this->input->post('id')[$i],
                        'student_session_id' => $this->input->post('student_session_id'),
                        'exammst_id' => $master,
                        'session_id' => $current_session,
                        'content' => $this->input->post('content')[$i],
                        'height' => $this->input->post('height'),
                        'weight' => $this->input->post('weight'),
                    );
                    
                    $this->student_model->addMedicalResult($data);
                    
                    $i++;
                }
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
                redirect('student/medical_exam');
            }
            
        }

    }

    public function printMedical()
    {
        $data['student_session_id']= $student_session_id =$this->input->post('student_session_id');
        $student              = $this->student_model->getByStudentSession($student_session_id);
        $data['student']      = $student; 
        $category                = $this->category_model->get();
        $data['categorylist']    = $category;
        $data['rowlist'] = $this->student_model->getMedicResult($student_session_id);
        $data['resultlist'] = $this->student_model->getMedicResultArray($student_session_id);
        $data['sch_setting']         = $this->sch_setting_detail;
        $this->load->view('student/medicalexam', $data);
    }

    public function disable()
    {
        if (!$this->rbac->hasPrivilege('disable', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'student/disable');

        $this->data = "";

        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $session = $this->session_model->get();
        $data['sessionlist'] = $session;

        $data['resultlist'] = $this->student_model->getDisableList();

        $this->load->view('layout/header');
        $this->load->view('student/disable', $data);
        $this->load->view('layout/footer');
    }

    public function addDisabilty($id = "")
    {
        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'student/disable');
        $this->data = "";

        if ($id != "") {
            $data['update'] = $update      = $this->student_model->getDisableList($id);
            $current_session = $this->setting_model->getCurrentSession();
        }

        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $session = $this->session_model->get();
        $data['sessionlist'] = $session;
        // $reasonlist = $this->certificate_model->get_reasons();
        // $data['reasonlist'] = $reasonlist;
        // $conductlist = $this->certificate_model->get_conductlist();
        // $data['conductlist'] = $conductlist;
        $subject_results = $this->subject_model->getsubject();
        $data['subjectlist'] = $subject_results;


        // $data['student_info'] = $this->certificate_model->getstudent_info();

        $this->load->view('layout/header');
        $this->load->view('student/addDisabilty', $data);
        $this->load->view('layout/footer');
    }

    public function disability_valid()
    {
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('student_id', $this->lang->line('student'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $json = array(
                "error" => true,
                'class_id' => form_error('class_id', '<p>', '</p>'),
                'section_id' => form_error('section_id', '<p>', '</p>'),
                'student_id' => form_error('student_id', '<p>', '</p>'),
            );

        } else {
            $student_id      = $this->input->post('student_id');
            $session         = $this->student_model->get($student_id);
            $setting         = $this->setting_model->getSetting();
            $current_session_id      = $this->setting_model->getCurrentSession();
            $student_session_id = $this->input->post('student_id');
            $student_session = $this->studentsession_model->getSessionById($student_session_id);

            $data = array(
                'id' => $this->input->post('id'),
                'student_id' => $student_session->student_id,
                'student_session_id' => $student_session_id,
                'disabilty' => $this->input->post('disabilty'),
                'disabilty_detail' => $this->input->post('disabilty_detail'),
                'percentage' => $this->input->post('percentage'),
                'certificate_no' => $this->input->post('certificate_no'),
                'lering_style' => $this->input->post('lering_style'),
                'supportive_services' => $this->input->post('supportive_services'),
                'udid' => $this->input->post('udid'),
            );

            $this->student_model->addDisable($data);

            $json = array(
                "success" => "Data Updates Successfully!!!!",
            );
        }
        echo json_encode($json);
    }

    public function deleteDisable($id)
    {
        if (!$this->rbac->hasPrivilege('disable', 'can_delete')) {
            access_denied();
        }
        $this->student_model->removeDisable($id);
        $this->session->set_flashdata('msg', '<i class="fa fa-check-square-o" aria-hidden="true"></i> ' . $this->lang->line('delete_message') . '');
        redirect('student/disable');
    }

    public function updateReceiptstatus()
    {
        // if (!$this->rbac->hasPrivilege('collect_fees', 'can_delete')) {
        //     access_denied();
        // }
        $sub_invoice = $this->input->post('sub_invoice');
        $pass_status = $this->input->post('pass_status');
        $pass_date = $this->input->post('pass_date');
        if (!empty($sub_invoice)) {
            $arrayUpdate = array(
                'id'                        => $sub_invoice,
                'pass_status'              => $pass_status,
                'pass_date'        => date('Y-m-d',strtotime($pass_date)),
            );
            
            $this->studentfee_model->update_receipt_status($arrayUpdate);
        }
        $array = array('status' => 'success', 'result' => 'success');
        echo json_encode($array);
    }

    public function student_docs($student_session_id,$id=null)
    {
        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'student/search');

       $data['con_id']  = $id;
       $data['sch_setting']  = $this->sch_setting_detail;
       $student              = $this->student_model->getByStudentSession($student_session_id);
       $data['student']      = $student;
       $category                     = $this->category_model->get();
       $data['categorylist']         = $category;
       $class_section                = $this->student_model->getClassSection($student["class_id"]);
       $data["class_section"]        = $class_section;
       $data['current_session'] = $session      = $this->setting_model->getCurrentSession();
       $studentlistbysection         = $this->student_model->getStudentClassSection($student["class_id"], $session);
       $data["studentlistbysection"] = $studentlistbysection;
       $data['userdata']             = $this->customlib->getUserData();
       $data['class_teacher']        = $this->staff_model->get_class_teacher_data($student_session_id);
       $checklistresultstudent            = $this->student_model->getchecklistofstudent($student["id"]);
       if(empty($checklistresultstudent)){
        $this->student_model->addstudentchecklist($student["id"]);
        $checklistresultstudent            = $this->student_model->getchecklistofstudent($student["id"]);
       }

       
       if(!empty($checklistresultstudent)){
        $data['data_exist'] = 1;
        $data['checklistresult']    = $checklistresultstudent;
        $data['pendSubIdList'] = [];
        foreach($checklistresultstudent as $checkRow){
            if($checkRow['status'] == 'Pending' || $checkRow['status'] == 'Submitted'){
                $pendSubIdList[] = $checkRow['checklist_id'];
            }
        }
        $data['pendSubIdList'] = $pendSubIdList;
       }else {
        $data['data_exist'] = 0;
        $checklistresult            = $this->student_model->getchecklistforstudent();
        $data['checklistresult']    = $checklistresult;
       
        $pendSubIdList = [];
        $data['pendSubIdList'] = $pendSubIdList;
       }
       

       $data['checklistNotExist'] = $this->student_model->getchecklistforstudent();

       $this->load->view('layout/header', $data);
       $this->load->view('student/studentdocs', $data);
       $this->load->view('layout/footer', $data);
    }

    public function admission_card($student_session_id)
    {
        // $student_session_id = $this->input->post('student_session_id');
        $student            = $this->student_model->getByStudentSession($student_session_id);
        $data['student']      = $student;
        $data['sch_setting']  = $this->sch_setting_detail;
        $userdata          = $this->customlib->getUserData();
        $data['userdata']     = $userdata;
        // print_r($data);die();
        $session                = $this->setting_model->getCurrentSessionName();
        $data['current_session_name'] = $session;
        
        $this->load->view('student/admissioncard', $data);
        // $admission_card = 
        // echo $admission_card;
    }

    public function addchecklistmst()
    {
        $userdata          = $this->customlib->getUserData();
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('checklist_id', "Document Name", 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $data = array(
                'checklist_id' => form_error('checklist_id'),
            );
            $array = array('status' => 0, 'error' => $data);
            echo json_encode($array);
        } else {

        $insert_array = array(
            'student_id' => $this->input->post('student_id'),
            'checklist_id' => $this->input->post('checklist_id'),
            'status' => $this->input->post('status'),
            'created_by' => $userdata['name'],
            'created_at' =>  date("Y-m-d H:i:s"),
        ); 

        $feegroup_result = $this->student_model->addstudent_checklist($insert_array);
        $array = array('status' => '1', 'error' => '', 'message' => $this->lang->line('success_message'));
        echo json_encode($array);
    }
    }

    public function student_docs_valid()
    {
        $userdata          = $this->customlib->getUserData();
        $student_id = $this->input->post('student_id');
        $checklist_id = $this->input->post('checklist_id[]');
        $condition_id = $this->input->post('condition_id');
        $array = array();
        foreach ($checklist_id as $key => $check_id) {
            $this->db->where('student_id', $student_id);
            $checkCount = $this->db->get('student_checklist')->num_rows();
            if ($checkCount > 0) {
                $array[] = [
                    'student_id' => $student_id,
                    'checklist_id' => $check_id,
                    'status' => $this->input->post('status'.$check_id),
                    'updated_by' => $userdata['name'],
                    'updated_at' =>  date("Y-m-d H:i:s"),
                ];
            } else {
                $array[] = [
                    'student_id' => $student_id,
                    'checklist_id' => $check_id,
                    'status' => $this->input->post('status'.$check_id),
                    'created_by' => $userdata['name'],
                    'created_at' =>  date("Y-m-d H:i:s"),
                ];
            }
            
            
        }
        $this->student_model->add_docs_status($array);
        $this->db->where('student_id', $student_id);
        $this->db->where('status', "Not Required");
        $this->db->delete('student_checklist');

        $student_session = $this->studentsession_model->getStudentByStudentId($student_id);
        $data['sch_setting']  = $this->sch_setting_detail;
        $student              = $this->student_model->getByStudentSession($student_session['id']);
        $data['student']      = $student;
        $document             = $this->student_model->getDocuments($student_id);
        $data['document']      = $document;
        $dataview = $this->load->view('student/acknowledge', $data,TRUE);
        if ($condition_id) {
            $status = 1;
        }else {
            $status = 2;
        }
        $array = array('status' => $status, 'error' => '', "response" => $dataview,'message' => $this->lang->line('success_message'));
        echo json_encode($array);

    }

    public function class_change($student_session_id = null)
    {
        $student              = $this->student_model->getByStudentSession($student_session_id);
        $data['student']      = $student;
        $section_result      = $this->section_model->get();
        $data['sectionlist'] = $section_result;
        $data['student_session_id'] = $student_session_id;
        $class                      = $this->class_model->get('', $classteacher = 'yes');
        $data['classlist']          = $class;

        $data['current_session']    = $this->setting_model->getCurrentSession();
        $category                     = $this->category_model->get();
        $data['categorylist']         = $category;
        $data['sch_setting']  = $this->sch_setting_detail;
        $data['userdata'] = $userdata          = $this->customlib->getUserData();

        if ($this->form_validation->run() == false) {

            $this->load->view('layout/header', $data);
            $this->load->view('student/studentclass_change', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $student_session_id = $this->input->post('student_session_id');

            $data = array(
                'class_id' => $this->input->post('class_id'),
                'section_id' => $this->input->post('section_id'),
            );
            $this->student_model->change_div($student_session_id, $data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            // redirect('student/search');
        }

    }

    public function class_update()
    {
        $data['userdata'] = $userdata          = $this->customlib->getUserData();
        $student_session_id = $this->input->post('student_session_id');

        $data = array(
            'class_id' => $this->input->post('class_id'),
            'section_id' => $this->input->post('section_id'),
        );

        $this->student_model->change_div($student_session_id, $data);

        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
        redirect('student/search');
    }

    public function acknowledgement()
    {
        $student_session_id = 1569;
        $data['sch_setting']  = $this->sch_setting_detail;
        $student              = $this->student_model->getByStudentSession($student_session_id);
        $data['student']      = $student;
        $document             = $this->student_model->getDocuments($student['id']);
        $data['document']      = $document;
        
        
        $this->load->view('student/acknowledge',$data);
        
    }

    public function getlastAdmNo()
    {
        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        

        $adm_no = $this->student_model->getLastAdmNo($class_id,$section_id);
        
        $array = array('status' => 'Success', 'error' => '', "admission_no" => $adm_no+1,'message' => $this->lang->line('success_message'));
        echo json_encode($array);
    }

    public function student_photo()
    {
        if (!$this->rbac->hasPrivilege('student_photo', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'student/student_photo');
        $data['title']           = 'Student Photo';
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $data['sch_setting']     = $this->sch_setting_detail;
        $data['fields']          = $this->customfield_model->get_custom_fields('students', 1);
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $status_result      = $this->student_model->get_student_status();
        $data['status_result'] = $status_result;
        $this->load->view('layout/header', $data);
        $this->load->view('student/student_photo', $data);
        $this->load->view('layout/footer', $data);
    }

    public function searchphotovalidation()
    {
        $class_id   = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');

        $srch_type = $this->input->post('search_type');
        $search_text = $this->input->post('search_text');

        if ($srch_type == 'search_filter') {

            $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
            if ($this->form_validation->run() == true) {

                $params = array('srch_type' => $srch_type, 'class_id' => $class_id, 'section_id' => $section_id);
                $array  = array('status' => 1, 'error' => '', 'params' => $params);
                echo json_encode($array);
            } else {

                $error             = array();
                $error['class_id'] = form_error('class_id');
                $array             = array('status' => 0, 'error' => $error);
                echo json_encode($array);
            }
        } else {
            $params = array('srch_type' => 'search_full', 'class_id' => $class_id, 'section_id' => $section_id, 'search_text' => $search_text);
            $array  = array('status' => 1, 'error' => '', 'params' => $params);
            echo json_encode($array);
        }
    }

    public function dtstudentlistphoto()
    {
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
        $class           = $this->input->post('class_id');
        $section         = $this->input->post('section_id');
        $search_text     = $this->input->post('search_text');
        $search_type     = $this->input->post('srch_type');
        $classlist       = $this->class_model->get();
        $classlist       = $classlist;
        $carray          = array();
        if (!empty($classlist)) {
            foreach ($classlist as $ckey => $cvalue) {
                $carray[] = $cvalue["id"];
            }
        }

        $sch_setting = $this->sch_setting_detail;
        if ($search_type == "search_filter") {
            $resultlist = $this->student_model->searchdtByClassSection($class, $section);
        } elseif ($search_type == "search_full") {
            $resultlist = $this->student_model->searchFullText($search_text, $carray);
        }

        $students = array();
        $students = json_decode($resultlist);
        $dt_data  = array();
        $fields   = $this->customfield_model->get_custom_fields('students', 1);
        if (!empty($students->data)) {
            foreach ($students->data as $student_key => $student) {

                $editbtn   = '';
                $deletebtn = '';
                $viewbtn   = '';
                $collectbtn   = '';
                $status   = '';
                $promo   = '';
                $div_change   = '';
                $class_change   = '';
                $btnstatus   = '';
                $student_photo   = '';

                $this->db->where('student_session_id', $student->student_session_id);
                $feeStatus=$this->db->get('student_fees_master')->num_rows();
                if ($feeStatus > 0) {
                    $onclick = 'onclick="myAlert()"';
                    // $link="javascript:void(0);";
                    $link_docs="javascript:void(0);";
                    $target = "";
                } else {
                    $onclick = "";
                    $link_docs = base_url() . "student/student_docs/" . $student->student_session_id;
                    $target = "_blank";
                }
                $link= base_url() . "student/div_change/" . $student->student_session_id;
                
                
                
                $action   = '<div class="dropdown">
                <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    Action
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">';

                $viewbtn = "<li><a target='_blank'  href='" . base_url() . "student/view/" . $student->id . "'>View</a></li>";

                if ($this->rbac->hasPrivilege('student', 'can_edit')) {
                    $userdata = $this->customlib->getUserData();
                    if ($userdata['user_type'] == 'Teacher') {
                        $data = $this->student_model->check_teacher($student->section_id, $student->class_id, $userdata['id']);
                        if (!empty($data)) {
                            $editbtn = "<li><a target='_blank' href='" . base_url() . "student/edit/" . $student->id . "' >" . $this->lang->line('edit') . "</a> </li>";
                        } else {
                        }
                    } else {
                        $editbtn = "<li><a target='_blank'  href='" . base_url() . "student/edit/" . $student->id . "' >" . $this->lang->line('edit') . "</a> </li>";
                    }
                }
                if ($this->rbac->hasPrivilege('collect_fees', 'can_add')) {

                    $collectbtn = "<li><a target='_blank'  href='" . base_url() . "studentfee/addfee/" . $student->student_session_id . "'>Add Fees</a></li>";
                }
                $status = "<li><a class='view_modal' data-student_id=" . $student->id . "  href='javascript:void(0);'>Status</a></li>";
                $div_change = "<li><a target='".$target."'  href='" .$link. "' > Division Change</a> </li>";
                $class_change = "<li><a target='".$target."' ".$onclick."  href='" . $link_docs. "' > Class Change </a> </li>";
                $btnstatus = "<li><a target='_blank'  href='" . base_url() . "student/student_docs/" . $student->student_session_id  . "' > Documents </a> </li>";
                $student_photo = "<li><a class='view_modal_photo' data-student_id=" . $student->id . "  href='javascript:void(0);'>Student Photo</a></li>";

                // $assign_count = $this->db->where('student_session_id',$student->student_session_id)->get('student_fees_deposite')->num_rows();
                // if ($assign_count > 0) {
                //     $promo = "<li><a href='" . base_url() . "student/delete_promo/" . $student->student_session_id . "' >Cancel Promotion</a></li>";
                // }

                $row   = array();
                $row[] = $student->admission_no;
                $row[] = $student->roll_no;
                $row[] = "<a  target='_blank' href='" . base_url() . "student/view/" . $student->id . "'>" . $this->customlib->getFullName($student->firstname, $student->middlename, $student->lastname, $sch_setting->middlename, $sch_setting->lastname) . "</a>";
                $row[] = $student->class . "(" . $student->section . ")";
                if ($sch_setting->father_name) {
                    $row[] = $student->father_name;
                }

                $row[] = $this->customlib->dateformat($student->dob);

                $row[] = $student->gender;
                if ($sch_setting->category) {
                    $row[] = $student->category;
                }
                if ($sch_setting->mobile_no) {
                    $row[] = $student->mobileno;
                }

                foreach ($fields as $fields_key => $fields_value) {

                    $custom_name   = $fields_value->name;
                    $display_field = $student->$custom_name;
                    if ($fields_value->type == "link") {
                        $display_field = "<a href=" . $student->$custom_name . " target='_blank'>" . $student->$custom_name . "</a>";
                    }
                    $row[] = $display_field;
                }

                $row[] = $action . '' . $student_photo;

                $dt_data[] = $row;
            }
        }
        $sch_setting         = $this->sch_setting_detail;
        $student_detail_view = $this->load->view('student/_searchDetailView', array('sch_setting' => $sch_setting, 'students' => $students), true);
        $json_data           = array(
            "draw"                => intval($students->draw),
            "recordsTotal"        => intval($students->recordsTotal),
            "recordsFiltered"     => intval($students->recordsFiltered),
            "data"                => $dt_data,
            "student_detail_view" => $student_detail_view,
        );

        echo json_encode($json_data);
    }

    public function save_student_photos()
    {
        
        // echo "<pre>";
        // print_r ($this->input->post('student_id'));die;
        // echo "</pre>";
        $id = $this->input->post('student_id');
        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
            $fileInfo = pathinfo($_FILES["file"]["name"]);
            $img_name = $id . '.' . $fileInfo['extension'];
            move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/student_images/" . $img_name);
            

            // $source_image_path = './uploads/student_images/'. $img_name;
            // $destination_image_path = './uploads/student_images/'. 'crop_'.$img_name;
            // $crop_width = 220;
            // $crop_height = 280;
            // $x = 100;
            // $y = 50;
            $data_img = array('id' => $id, 'image' => 'uploads/student_images/' . $img_name);
            $this->student_model->add($data_img);
            // $this->crop_image($source_image_path, $destination_image_path, $crop_width, $crop_height, $x, $y);
        }

        $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        echo json_encode($array);
    }

    
    
    public function upload_image()
    {
        // $this->load->view('layout/header');
        $this->load->view('admin/upload_image');
        // $this->load->view('layout/footer');
    }

    public function upload_valid()
    {
        if(isset($_POST['image']))
        {
            $data = $_POST['image'];

            ////data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAZAAAAC0CAIAAAA1l+0PAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAAEnQAABJ0Ad5mH3gAABk3SURBVHhe7Z1/iF5VesfnjyJM/3hLWJyhL13WQWGlKETYLVKEsF0WFTrSTZBaRIpdVrZZ2SZl0W4bdV3sgtsqMSC1RurWZNsSXImQqFXYCOLG/CErWUhapdHJTDNm4saEDJhJ0qTfc+/JzZ1zfz3Puee+73tevx++SJz3nvOee997vu9znvvc+05cIoSQSKBhEUKigYZFCIkGGhYhJBpoWISQaKBhEUKigYZFCIkGGhYhJBpoWISQaKBhEUKigYZFCIkGGhYhJBpoWISQaKBhEUKigYZFCIkGGhYhJBpoWISQaKBhETI+nF+Yv7h8xv7POELDImR8WFw/e/KJx+3/jCM0LELGhM8O7P9oYmJuzQTiLPunsYOGRciYsHDzTUdnpuemJo9vvM/+aeygYREyDpze+QLCq6PX9iH8A9GWfWG8oGEREj0XlpaMVc1Mp4aFfyDasq+NFzQsQqLnk8cenVtjw6tUCLIQc9mXxwgaFiFxs3L4ULYYvKKZafxx/EocaFiExM3Hd995tN9zDevaPmKuEw9+3240LtCwCImY5Tf3lYRXlwXPOnvwPbvpWEDDIiRijDFlufai+r3F9bN20yaWF04tvn2kpS6urNjuuoGGRUisnNr+TE14lQobnNn9km1Qzb5v/+z5iQdaatfarba7zqBhERIlF5aW5qYm68KrVMkG9dn3V29/GnazY+KhNkIPpz44YXvsDBoWIVFy4pEtTilDlbDZJ489apsVeH/Xu0Hc6sDDe22PXULDIiQ+Vg4fMm7VGF5dFhaGpTcYXlxZcazHW8sLp2ynXULDIiQ+qkoZKtXvoYltnANhUZDw6tDz79geO4aGRUhkLL+ypzHXXhSaLL+5z3aRgJiovVtBL9+yreuLgxk0LEJiAtYwv/Z6+WLwipIbDPPO8tqG7UHCq8W3j9geu4eGRUhMSEoZqoSGaJ72A5cJ4lZwvbTDwUDDIiQaLi6fMW7lEV6lQsN+78LSErpyrMdPMKzB5NozaFiERINf9iqv9AbDQ8+/EyS8GkwpQx4aFiHRcHrnC8LaqyrNTU3+719896cTP3Tcx08Dy7Vn0LAIiYazB99rFWHNTM+vvf6tu//J8R0PIbx6f9e7dlgDhIZFSEyoK7ByQnR25L6HgoRXL9+yzQ5osNCwCImJ8wvznkFWUtbw6u1PO9bjIYRXgyxlyEPDIiQyfrNtq4dnzU1NHv7uk+3DK7jVvm//zA5l4NCwCImMiysr6S96OZZUp+TBWLvWbnXcx0MwrAGXMuShYRESH/UPGi1qbs1EqFz74EsZ8tCwCImS4xvvM8/DKnhTUWFLGezbDwkaFiFRYh7gJ3nCTFLKECrXPpRShjw0LEJiJf2154+u7tcI4VWoUoa9f/Kv9o2HBw2LkGGw/yeXXt/cXmd/PN2o3Tf8yLEeR//8O3+N6Mn5oyNY3qfb73fe3dWRN+zedQYNi5BhcPy9S89MXHqutf5lqk47rvrvv/rzmvAKL/3N7215rv/1/V/8Zv1mv/rOJvTmvrsj7NHr37E72A00LEKGBOY2JrljMUF1/tkv19jQ36/5u80zD/7gS5tenLrh6LX9+iALXTmdlwue9atn7Q52AA2LkCFx7tNuDWvHVb/csLnUsFKrSrXzd9cZw5qZPnjN10o3xh8Rppnwyum/Stgp7Fo30LAIGR4IRhCSOBM+kJa3frVoQPgL1oCZWz1147devvq6NMI62u/t+cLGYpPdN/xI4VYQDKuzhSENi5ChYR7P8h9/4E74INpx1d6v/K1jPfnAKhWsKlVaAPHBdTc6TeBfS//4pzrDguDCxzv5iXwaFiFD5cgb4YOsHVedfOCufKxUtCooC6+sYSVB1i+m78k3fOO2h9RuBSHIghF3AA2LkGGze13YZNb5Z7+cZdDhPo5PZcrCqyuGlZShZm6FtlhXOp1L1U32nYZFyLBJSxycCe+tHVf9cvbONErKp6scPdf/eolhJUHWr2/YgOaQLWVw+perg+w7DYuQEeD1zaGCrDTXXroGzPSDL23KFoOuYSWe9fPJTfC7tvk1uPD+n9gdDAQNi5ARIFyJA8Krp278Vr3SUoZKw0pKHP7nHx5uFV6lwk4Fzb7TsAgZDUKVOKCTv5y49Md1+q+v9OsMK3nAw2f/+e8BrmDCsIKWONCwCBkZApY4wLbu/C3Hp/KqWxJCM9OL62fNgq593IeRhLvHkIZFyMgQusTh7I+nq2zr0z/q1RlW9jPRQTw0XIkDDYuQEeL/XrorVDLrir73245bpTrw+9fUGBaCrPm11wcLsgKVONCwCBklwpY4ZEKfhcRWPshy3SrR3NTkiUe2mDIxpzcPYQAhShxoWISMGK9v7sSzIHS7eoWYZd8dq8qEheHZvU8GCLJM9n2z3cEW0LAIGTEQiXRkWImcxNaVm59LlWTfwyxUsVOtSxxoWISMHl0+xcHqcmIrXRi6PpVXv2eeNRqiJssYXztoWISMJLvXde5Z6D+xLQRZrknllfxkdLAgq12JAw2LkFElvUKHSV4lvIrAp6WemTj9Z72Pri74VF793skH7ko3bivYVovsOw2LkFi5uLLyb1dva9Svb9gw/9U/rBfCKNekVmtuavLswfeM17RXC2hYhMTKW5tebPy1G+jnk5uMH9Wr4FCu+r2P777TvvHwoGEREiWnPjghcSvopxM/RJAFx3E9SKmPJiaW39xn335I0LAIiRLtjzk77uMjBGL9nn37IUHDIiQ+3t/1rjC8SoUg6xfT97QPsubWTJx84nE7iGFAwyIkMi6urDh+JBE864PrbhSlq2o0M42F4fmFeTuUgUPDIiQyDjy8VxVepYJh7fnCxgBB1tTk4r332KEMHBoWITGxvHDKw61SwbMOXvO1tkHWULPvNCxCYuK1Ddu9DQuyJQ4FD9Kp3zv2jXXmRxUHDg2LkGhYfPtIG7eCEGTt/+I3g5Q4fHZgvx3WAKFhERINu9ZudQzIT/Nrr28ZZ8Gwzux+yQ5rgNCwCImDQ8+/0zK8gtAD+ll+cx8cx/EgldDc3KkzcGhYhETA8sIpx3r8hBgtzT0dm73Vf2HY76F5OrABQ8MiJAKEtw3WCz28v+vdtMNzHx7xDrKGlcACNCxCRh35bYM1Qg+vbdhue0w48ciWuTVqz5qbmlzadL/tYuDQsAgZdV6+ZZvjPh6CYcH4bI8JF5aWjAepsu8z0/A4VroTQsrR3jZYKvRw4OG9tsccp3e+oFoY8l5CQkglfrcNlqq0zhN/PPaNddLsexJe2ZZDgoZFyOjid9ugI/SQ5dqLfHZgvzDIwmbLr+yxzYYEDYuQESXNtbcxrLT5W5tetD1WsHiv4Mkz/d7i+lnbYHjQsAgZURBevXr7069t2O4tWFVNbJVxfmG+McjCBkOpFHWgYRFCLn3y2KM1JQ546cSD37ebDhUaFiHEZN9NfUNpiUPy9wtLS3bToULDIoQYzux+qXRhiPDq1PZn7EbDhoZFCLEs3nGbm32fmZ5fe719eQSgYRFCLMUSB1PKMOyf9spDwyKEXOH4xvvmpiatYY3Gj6fmoWERQq5wYWnJXC5Msu8Ir859eMS+MBrQsAghqzj51JPwLOiTxx61fxoZAhjWb7ZtRdy4eO89jcJmp3e+YJu1AJ3I39HjAoe8/1R+V3xPPvG4009RfuNP+ezAfsleYJvSu8zCcn5hXnVI82o5vJXDhzze2qPsCNPb6aRRbT7f7jAlDknq/eLyGfunkSGAYeGjNYk67GGTsBnM2zZrAaa6/B1PPLLFNhODQQr7N6v9fs/DsM4efE++C34Vxojq0+HVC/3jK8e26YyFm28yC43CuzfKfIKtSxZhDcKjnQnbq5LN9nl4hX5qhE8Hx2SIj2qp4czul0bQSUEYw7qSpasVPp5QhmXO/kL/RWFgHoaFCSzsP13qexgWJnDatln9HjbWRhnmQxHvAmZap2WBisGUCcNr+XsH+byMVMkvWdn2AlblqmXCfgVZcHyuoGGV0LVhVVXoVQkbq46b/P77VDhKmG+2cWiWX9mjGkyJklCxZfYX8YJ2GNhe+HAChMBqR1YaIkmhYZXQqWGZBMHlhnJh8ggXhuhf8YSjy8L+dnFrK46M6V+5syUK8agA9W9bzUwjtrWNa/ELr4b1WPSooWGV0Klh4QhIO89L/IWM/n0imm4qbsxM9tjZMmGnWmZVtIEnhO0bgyybjiy0rRFOyyE+Fj1qaFgldGdYJuJAE6+IA7OiMZXT8qdQwtY0t//xO0foTfXdUEQdCiWflG1cwaLkYVJ5JUnDEbwAFwU0rBK6Myz5yEuUnui12XdESdrF4BWF/rE5c1Z4WXOV0GHLXJv5wsDx0YwKx7zme8IjvKrvkNRDwyqhI8NqE16lwqhqavk8ljyO0DxUYsUjyS0R+myZa1MPLMlkVX1PLK6f1X1DDO8nSMcDGlYJHRkWpoq02yrV/siSolSiSoGmE9Y7ZiQtB1Oq1rk2WI/2QMHgSusP/MKrLi5ufH6gYZXQhWGZi4Mh5jAGhgNuO80RoHogETpZOXzIduqL4gDqhRG2DAPVoWhFkKUNr6o+OyKHhlVCF4alrb2qVBJkFYuSAoRXqfq9xXvvsZ16YcIr9KMajGrjECUO2uw7PjsnyPJwPfyXufaW0LBK6MKw1EVA1cLYnJ0KeT2udtUpQZ0kSoMUzcFB/y2DLHxkZpDyN02eY5e3G214hbdjrr09NKwSghuWR7KjTknld/5NW10cLKjNx6TOECX1ZfBHM35Nq5ZhINAaKzbOgiz1N4S4jI7UQ8MqIbhhLW26X3iIhMpPnpXDh0K6IVSIJuRoU2nYOE2ZeTRsebMOvFV3S0ASe6aZrJJHCdcq203SEhpWCWENq5NLZrm7RuRHQy5MML8i0mOzt8pnMoadr9JQVWA6bf3Q5qHwpojLtPEyWnmchKQUGlYJYQ1LnW5Hn4J5iz7TEEO4BFOFeNjY494RXayXmHj+0GmNABsXr9xpMcGv3O5nphGUwSgVBzP5aJhrDwUNq4SwhqVLMM1ML66flTTBCLEqPC/4zV7IHHlVIJa4iXaa4VDLZzIGUwyRVMcKO94+jY3PzoxZ4PhW2FL+aQYaJMmgYZUQ0LDwkjm/xfMB5zfWKaJbAvs9RAeS1A/2BZ8RBmNSNpqRNN7362BGIuy/ojhDl8wOVOaqvqwp1+j9iEPs0LBKCGhYuvVgLjNl1h31Y0hS4/Cshi/83K22qsGYQ6epctR6TdVMVtV/4BAFeWKn6U38pnLhgDDXHhYaVgkBDUuVSMb5nS0fMA/NGOpnEV5t6twc8yceT/uEbSnsQHCvdR7tnlYl9VU3MGFLbG9btiBw3UkijK39ZQHiQMMqIZRhmeuD8vUgNludNvJ4LFxRmIf5GKQ5cMsJbYX1mWbliybiPcUYbMsCwqycVbh7iXXZ90bhUPR77a8JEAcaVgmhDEt11Rzv6AxVe9G9RIUCS6xQ5NNS/nmpqqjwodRfglSVnmKQLQuyUmCUim+XJuFoaDOARAINq4RQhiUfJ4RT3Aln8P2sSugUVTptFKn3JE1mm9ViwhNxMGhGVVvkpV0Vlj5KwQP0I7fdOoW425GUQsMqIZRhKaoo0+cBFMoIMHi5EbjC2Mp+gkxlB5jAxVE56FJjaZ+1ayVdPVeI23RSMCpd7XuFMHjm2juChlVCEMPC2S+fdVXjbLUqrLgSp1oV4t0bS951GWuZv5gtxWFgqS/7gQMuPzilMif55ascJDg0rBKCGJbKa6p8QeV6jrALVVfQzAYyO0AnjZe6FIcr6VCygpOfVBAOkbOaboP2MQyrVBEpk1DQsEoIYliKlVd1J0B1d15eeHfEPraX1ShSToLHDGjL0yXLJV3JWLig5tyHR8y+yNy8REnDIKVhpBQaVglBDEtRl1R7bV4Vv1wRBjYzXZUqUlzUS3awJmTAS2Z4whmejkoQgGiLG0IluVXmWyqccqFyaqQIDauEIIZljolsGpvDUh0g6CrIM2EO33Gb7aKAyg4wvJqYqIsEFoCpqYobMIb2C7FQVwnRSWPij/hBwyqhvWHhL/JTv/78xjz0mEWNh9rsoMwOzPCqS4q0JQjyE+D4xvvkwQ4G2TKNFbIOK+mk/koo8YOGVUJ7w1Jl3PFe9aWPHtVY9SYI5CtW86lVB4BaW5GHHjhVpJ9CMsiqKwxCcECEp7FEGA/vy+mCKA1LbijGsPS/U9LesBQ54+R5x7ZZBYp02GXh3etz24rIqHYdp4pKMCp5Qlq3FhYvNktRVeoLhQ6DlOCTPFEalmqyefxWcHvDQlgn/boW3A0njygzYbbYxhWo8u5VFwpVK1/0o7rkj9mu2Ova+xPrsYtuZQzbrHCXAkhGlIaliF9qc89VtDcseS2PiQGbFq2nd76gMyyMqt+zjSuAHcgNC/8t7iPQBkGqh0PBR7QF9KWDbCTITealwpCyx2+QIERpWIoMEU736qv7VbQ3LPkVLrxR4zmtyogZCb7bVbl8DLJ0daNyUpwk9fc8F1HVoGF3PPLu6sVgcka5f6xSi5/zIKVEaViK6CA5j7WFfC0NC/+bvdQoDK8xD60rSkqsoTFqg4nLXRXvXuoFqtgEh1SbF1dl9NG/NpzxCOLwFvJdhjAqZt8DEqVhqRzB4zxuaViq5AvmQGPlN/o3HYrnFTaWWIO8SBIdlt5Poy2Vkl8iTFE9vcvYtPICi0k1ivtP9xSttEtIyUdMhERpWEBxw1d1zriKloalqqXElo1LBm0ggMFLPFr3wZVVNpijpDGsqluFqsBeyI8kzgfVB61daGPj1HB1tbKQcmCkhlgNq6MnZ6a0NCxVWgRb2mbVYPmGM15lDTWlnhm6crZC8KK9iod5q12b6zxF/PQugEOq+g7A4PNpQY8gS3LLN2lk0IYFL7DN2qG7PpWcyo6t1NDSsBRVF0lz26wWRUQpNuiWpVgebqJNP2MlpfVE4aesWwwWDqkdmNzv0l+NZva9NQM1LJxPS5vuxwcPu/GQfb8EfEOaDjXfkAs331R6qatIS8NSFGHlfianHlXtKGaXZPGlWHAly2rnYqvu+pp4T/MgIjNHUvwp41OTZIu0azqMoejX5rTXWB421l4kJUUGa1hQv4dzxU/OhNF+SeKtsT1GWz+Z4T6KPSozLMW1LXFtofYZxBJrxncAtnTalqssPlLYOqTPJAK8oznCYsPC7jSGljiLzLUCsftD2M2iD+JDV5kpJBkeqWfghuWromHhjJHOt0zJfTBoBR2bvRXOAiOA8A8YB+Zk+pJid8oMS/GIEnEhfheGpVjTla1oVEMy1ux164wq04TdabzacPKJx6V7nQj7iDPcNl7NSc3djkZJ9t05jYmKiA0LqIOsvDANEHNNTZrBw18gzbelVcGwMEhT7ijrqmYyOGgNS5LeRtQgn7rY0t+Xkz3FLtiWGlSVEzgZau7TBurcU9mOZ+Cz9uhNW4xG8sRtWNolQ3glb73KsDQPcjKGJXuYhOog41hJDEtVj4otnWWR6sIlJrZf/aSq2N0cz9ovAFVvUOOw1Y/QSmLVKgckjcRtWEBXqhNcBcPCv7O/N0o+jUfBsPL5F3wW2tjHL7JQXW3AljULTwxAd6rkfuW/hnTLVQ1rhc/Rb3VMQPSGBRCk6E7EgCozLHM0xIYlLEwbBcPK13apfBlCc0ktaxF1pqziIoZqZ1Nhe0nxlPpuxPRgKov+Sco4GBb+/vHdd2LyO00GoYJhqSYGxiwsTOvIsOQHDVvmZy/amsBHY1iSWtYiihoRqKz8ImXxjttUi0F0hRCy6pRzUFXJGaVXXWWdkzzjYFgp+CqWO0UwFQzrnObG7OEalgkGNYaVX9PZ7HVhsyoZw/KKKVS3NFiXKSzi1JmmZMDykFBxvfWysFOh7vr4XDE+hgVskkL1XddSSW1X3rBURYloG5Fh5YequsIIYWM/w9JWeyFyyX8cQBsMGunv/lNdMzVKsu/CSmaSMVaGBTCRcKqZuaQ6QbW6XM+F6YFv7/zAFNWYsRlWvmJAWyyOjf0MC19CKsOCnH1f1D+s3WO0WgeHMCpm37WMm2GlLL+yB0sDcwJpv1prlEwGTJ70vMQKtLRqWZWCjcmwVldgaBdB2Lj0cDWiNSyMM7/vPheRlU9GzdDeEQ1hbH6pvc8tAQwrTR4NQHLDSsGpkJ5DaGtOeg/zwvbJoi/t4djsrZi0+O51Fh150nSJXPWFjhnYEadhvSSGhW2cVvXKhwOpL6vkbVhOP43K1lnaHczkVJwJwVnh9CMRzqvGygmSEcCw8OniXByA7PspwWkEi4Ev4Gszu/kmO1fyyr+UCp4Fp8CcwbujH4ljYpLkx9woYRZDe5AlQ8U2Tqt65e/BxNFwXm2U37TUHk8oeyOPtqnS5h7gEDldSVTz/UccAhhWXGCWwiNgYVgsQAiIoPTfiBqM3tyHcwjnujagI4R0zefOsAgh8ULDIoREAw2LEBINNCxCSDTQsAgh0UDDIoREAw2LEBINNCxCSDTQsAgh0UDDIoREAw2LEBINNCxCSDTQsAgh0UDDIoREAw2LEBINNCxCSDTQsAgh0UDDIoREAw2LEBINNCxCSDTQsAgh0UDDIoREAw2LEBINNCxCSDTQsAgh0UDDIoREwqVL/w8KSyqJVFfZUwAAAABJRU5ErkJggg==

            $image_array_1 = explode(";", $data);

            //base64,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

            $image_array_2 = explode(",", $image_array_1[1]);

            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

            $data = base64_decode($image_array_2[1]);
            $id = 111;
            
            $image_name = 'uploads/marksheet/' . $id .'.jpg';

            file_put_contents($image_name, $data);

            echo $image_name;
        }

    }

    public function send_admission_mail($student_session_id,$to_mail)
    {
        // error_reporting( E_ALL );
        // ini_set('display_errors', '1');
        // $student_session_id = 3906;
        $student            = $this->student_model->getByStudentSession($student_session_id);
        $data['student']      = $student;
        $data['sch_setting']  = $this->sch_setting_detail;
        $userdata          = $this->customlib->getUserData();
        $data['userdata']     = $userdata;
        // print_r($data);die();
        $session                = $this->setting_model->getCurrentSessionName();
        $data['current_session_name'] = $session;
        $html = $this->load->view('student/admissioncard', $data, TRUE);
        $this->mailer->send_mail($to_mail,"Admission Confirmation",$html);

        return true;
    }

    public function admission_mail_confirmation()
    {
        // error_reporting( E_ALL );
        // ini_set('display_errors', '1');
        $student_session_id = 3906;
        $student_session_id = $this->input->post('student_session_id');
        $student            = $this->student_model->getByStudentSession($student_session_id);
        $to_mail            = 'alextezitservices@gmail.com';
        // $to_mail            = $student['email'];
        $data['student']      = $student;
        $data['sch_setting']  = $this->sch_setting_detail;
        $userdata          = $this->customlib->getUserData();
        $data['userdata']     = $userdata;
        // print_r($data);die();
        $session                = $this->setting_model->getCurrentSessionName();
        $data['current_session_name'] = $session;
        $html = $this->load->view('student/admissioncard', $data, TRUE);
        $this->mailer->send_mail($to_mail,"Admission Confirmation",$html);

        $array = array('status' => '1', 'error' => '', 'message' => "Mail Sent Successfully");
        echo json_encode($array);
    }

    public function send_email() {
        // Load the email library
        $this->load->library('email');

        // Email configuration
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = '119.18.54.50';
        $config['smtp_user'] = 'info@sngcentralschool.org';
        $config['smtp_pass'] = 'SYYI6KBQCTQQ';
        $config['smtp_port'] = 465; // or 587
        $config['smtp_crypto'] = 'ssl'; // or 'tls'
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['wordwrap'] = TRUE;
        // Initialize email config
        $this->email->initialize($config);

        // Set email parameters
        $this->email->from('info@sngcentralschool.org', 'SNG Central School');
        // $this->email->to('alextezitservices@gmail.com');
        $this->email->to('guptarupesh275@gmail.com');
        $this->email->subject('Test Email');
        $this->email->message('This is a test email sent from CodeIgniter.');

        // Send the email
        if ($this->email->send()) {
            echo "Email sent successfully!";
        } else {
            echo "Failed to send email.";
            echo $this->email->print_debugger(); // Debugging the error
        }
    }

    public function student_info_upload()
    {
        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'student/student_info_upload');

        $roles               = $this->role_model->get();
        $data["roles"]       = $roles;
        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_csv_upload');

        if ($this->form_validation->run() == false) {
            $this->load->view("layout/header", $data);
            $this->load->view('student/student_info_upload', $data);
            $this->load->view("layout/footer", $data);
        } else {

            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                if ($ext == 'csv') {
                    $file = $_FILES['file']['tmp_name'];
                    $this->load->library('CSVReader');
                    $result = $this->csvreader->parse_file($file);
                    if (!empty($result)) {
                        $i = 0;
                        foreach ($result as $key => $value) {
                            $keys = array_keys($value);
                            foreach ($value as $subkey => $subvalue) {
                                // if (is_numeric($subvalue)) {
                                //     // Format the number and remove scientific notation
                                //     $value[$subkey] = number_format($subvalue, 0, '', ''); // Adjust the number of decimals as needed
                                // }
                                
                            }
                            $admission_no = $value[$keys[0]];  // First key's value
                            $update_column = $value[$keys[1]];
                            $update_column = (string)$update_column;                        
                            // if (is_numeric($update_column) && strpos($update_column, 'E') !== false) {
                            //     $update_column = sprintf('%.0f', $update_column);  // Use sprintf to convert to a string without scientific notation
                            // }
                            $update_column = (string)$update_column;
                            // echo $admission_no.'-'.$update_column.'<br>';
                            $this->db->where('admission_no', $admission_no);
                            if ($this->input->post('column') == '1') { // Apaar ID
                                $this->db->update('students', ['aapar_id' => $update_column]);
                            }elseif ($this->input->post('column') == '2') { // Aadhar No
                                $this->db->update('students', ['adhar_no' => $update_column]);
                            }elseif ($this->input->post('column') == '3') { // saral id
                                $this->db->update('students', ['dep_student_id' => $update_column]);
                            }elseif ($this->input->post('column') == '4') { // PEN No (UID No)
                                $this->db->update('students', ['uid_no' => $update_column]);
                            }
                            
                        }
                    }
                }
            }

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">' . $this->lang->line('total') . ' ' . count($result) . " " . $this->lang->line('records_found_in_CSV_file_total') . ' ' . $rowcount . ' Records Updated </div>');
            redirect('student/student_info_upload');
        }

    }
    

}
