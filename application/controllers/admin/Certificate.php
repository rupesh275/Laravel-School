<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Certificate extends Admin_Controller
{
    public $sch_setting_detail;
    public $current_ay_session;
    public function __construct()
    {
        parent::__construct();

        $this->load->library('Customlib');
        $this->load->model('certificate_model');
        $this->sch_setting_detail  = $this->setting_model->getSetting(); 
        $settings              = $this->setting_model->getSetting();
        $this->current_ay_session = $settings->session_id;   

    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('student_certificate', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Certificate');
        $this->session->set_userdata('sub_menu', 'admin/certificate');

        $custom_fields                 = $this->customfield_model->get_custom_fields('students');
        $this->data['custom_fields']   = $custom_fields;
        $this->data['certificateList'] = $this->certificate_model->certificateList();
        $this->load->view('layout/header');
        $this->load->view('admin/certificate/createcertificate', $this->data);
        $this->load->view('layout/footer');
    }
    public function tc_list()
    {
        if (!$this->rbac->hasPrivilege('tc_issue', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Certificate');
        $this->session->set_userdata('sub_menu', 'admin/certificate/tc_register');
        $this->session->set_userdata('subsub_menu', 'admin/certificate/tc_register');

        $this->data = "";

        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $session = $this->session_model->get();
        $data['sessionlist'] = $session;

        $data['resultlist'] = $this->certificate_model->getstudent_info();

        $this->load->view('layout/header');
        $this->load->view('admin/certificate/tc_list', $data);
        $this->load->view('layout/footer');
    }

    public function print_tc_certificate()
    {
        $data['student_id'] = $student_id = $this->input->post('student_id');
        $student_info_id = $this->input->post('student_info_id');
        $setting_result        = $this->setting_model->get();
        $data['settinglist']   = $setting_result[0];
        $data['sch_setting']   = $this->sch_setting_detail;
        $session_id = $this->setting_model->getCurrentSession();
        $data['student_info'] = $this->certificate_model->getstudent_info($student_info_id);
        
        $data['student'] = $this->student_model->get_student_only($data['student_info']['student_id']);
        $this->load->view('admin/certificate/tc_issue', $data);
    }
    public function tc_register($id = "")
    {
        $this->session->set_userdata('top_menu', 'Certificate');
        $this->session->set_userdata('sub_menu', 'admin/certificate/tc_register');
        $this->data = "";

        if ($id != "") {
            $data['update'] = $update      = $this->certificate_model->getstudent_info($id);
            $data['student_session_id']  = $this->studentsession_model->getSessionById($update['student_session_id']);
        }

        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $session = $this->session_model->get();
        $data['sessionlist'] = $session;
        $reasonlist = $this->certificate_model->get_reasons();
        $data['reasonlist'] = $reasonlist;
        $conductlist = $this->certificate_model->get_conductlist();
        $data['conductlist'] = $conductlist;
        $subject_results = $this->subject_model->getsubject();
        $data['subjectlist'] = $subject_results;


        $data['student_info'] = $this->certificate_model->getstudent_info();

        $this->load->view('layout/header');
        $this->load->view('admin/certificate/tc_register', $data);
        $this->load->view('layout/footer');
    }

    public function tc_register_valid($id = "")
    {
        // if (!$this->rbac->hasPrivilege('newsessionyear', 'can_view')) {
        //     access_denied();
        // }
        // $this->session->set_userdata('top_menu', 'Certificate');
        // $this->session->set_userdata('sub_menu', 'admin/certificate/tc_register');
        // $this->data = "";

        // if ($id != "") {
        //     $data['update']      = $this->certificate_model->getstudent_info($id);
        // }
        // $class = $this->class_model->get();
        // $data['classlist'] = $class;
        // $session = $this->session_model->get();
        // $data['sessionlist'] = $session;


        // $data['student_info'] = $this->certificate_model->getstudent_info();

        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|xss_clean');
         $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
         //$this->form_validation->set_rules('student_id', $this->lang->line('student'), 'trim|required|xss_clean');
        //  $this->form_validation->set_rules('mother_tongue', "Mother Tongue", 'trim|required|xss_clean');
        //  $this->form_validation->set_rules('nationality', "Nationality", 'trim|required|xss_clean');
        //  $this->form_validation->set_rules('pob', "Place Of Birth", 'trim|required|xss_clean');
        //  $this->form_validation->set_rules('first_adm_class', "First Admission", 'trim|required|xss_clean');
        //  $this->form_validation->set_rules('prev_school_board', "Previous School", 'trim|required|xss_clean');
        //  $this->form_validation->set_rules('repeated_class', "Failed & Repetated Class", 'trim|required|xss_clean');
        //  $this->form_validation->set_rules('subject_studied', "Subject studied", 'trim|required|xss_clean');
        //  $this->form_validation->set_rules('passed_promoted', "Passed and promoted", 'trim|required|xss_clean');
        //  $this->form_validation->set_rules('school_dues', "School dues", 'trim|required|xss_clean');
        //  $this->form_validation->set_rules('fee_concession', "Fee concession", 'trim|required|xss_clean');
        //  $this->form_validation->set_rules('working_academic', "Working days academic", 'trim|required|xss_clean');
        //  $this->form_validation->set_rules('working_present', "Working days present", 'trim|required|xss_clean');
        //  $this->form_validation->set_rules('special_category', "NCC Cadet/boy Scout/Girl Guide", 'trim|required|xss_clean');
        //  $this->form_validation->set_rules('curricular_activities', "Extra-currical activities", 'trim|required|xss_clean');
        //  $this->form_validation->set_rules('general_conduct', "General Conduct", 'trim|required|xss_clean');
        //  $this->form_validation->set_rules('doa', "Date of application", 'trim|required|xss_clean');
        //  $this->form_validation->set_rules('doic', "Date of issue", 'trim|required|xss_clean');
        //  $this->form_validation->set_rules('reason_leave', "Reason For Leave", 'trim|required|xss_clean');
        
        if ($this->form_validation->run() == false) {
            $json = array(
                // "error" => true,
                // 'class_id' => form_error('class_id', '<p>', '</p>'),
                // 'section_id' => form_error('section_id', '<p>', '</p>'),
                // 'student_id' => form_error('student_id', '<p>', '</p>'),
                // 'mother_tongue' => form_error('mother_tongue', '<p>', '</p>'),
                // 'nationality' => form_error('nationality', '<p>', '</p>'),
                // 'pob' => form_error('pob', '<p>', '</p>'),
                // 'first_adm_class' => form_error('first_adm_class', '<p>', '</p>'),
                // 'prev_school_board' => form_error('prev_school_board', '<p>', '</p>'),
                // 'repeated_class' => form_error('repeated_class', '<p>', '</p>'),
                // 'subject_studied' => form_error('subject_studied', '<p>', '</p>'),
                // 'passed_promoted' => form_error('passed_promoted', '<p>', '</p>'),
                // 'school_dues' => form_error('school_dues', '<p>', '</p>'),
                // 'fee_concession' => form_error('fee_concession', '<p>', '</p>'),
                // 'working_academic' => form_error('working_academic', '<p>', '</p>'),
                // 'working_present' => form_error('working_present', '<p>', '</p>'),
                // 'special_category' => form_error('special_category', '<p>', '</p>'),
                // 'curricular_activities' => form_error('curricular_activities', '<p>', '</p>'),
                // 'general_conduct' => form_error('general_conduct', '<p>', '</p>'),
                // 'doa' => form_error('doa', '<p>', '</p>'),
                // 'doic' => form_error('doic', '<p>', '</p>'),
                // 'reason_leave' => form_error('reason_leave', '<p>', '</p>'),
            );
            // $this->load->view('layout/header');
            // $this->load->view('admin/certificate/tc_register', $data);
            // $this->load->view('layout/footer');
        } else {

            $student_id      = $this->input->post('student_id');
            $session         = $this->student_model->get($student_id);
            $setting         = $this->setting_model->getSetting();
            $current_session_id      = $this->setting_model->getCurrentSession();

            $studentinfo_count = $this->certificate_model->getstudent_info_count();
            $tc_certificate_no      = $this->input->post('tc_certificate_no');

            if($tc_certificate_no=='' || $tc_certificate_no <= 0)
            {
            if (empty($studentinfo_count)) {
                $tc_certificate_no = $setting->last_certificate_no + 1;
            }elseif (!empty($this->input->post('id'))) {
                $tc_certificate_no = $studentinfo_count['tc_certificate_no'];
            } 
            else {
                $tc_certificate_no = $studentinfo_count['tc_certificate_no'] + 1;
            }
            }
            
            $subjects = $this->input->post('subject_studied');
            if (!empty($subjects)) {
                $allsub = implode(',', $subjects);
            } else {
                $allsub = "";
            }
            $student_session_id = $this->input->post('student_id');
            $student_session = $this->studentsession_model->getSessionById($student_session_id);
            $data = array(
                'id' => $this->input->post('id'),
                'session_id' => $current_session_id,
                'class_id' => $this->input->post('class_id'),
                'section_id' => $this->input->post('section_id'),
                'student_id' => $student_session->student_id,
                'student_session_id' => $student_session_id,
                'mother_tongue' => $this->input->post('mother_tongue'),
                'nationality' => $this->input->post('nationality'),
                'pob' => $this->input->post('pob'),
                'religion' => $this->input->post('religion'),
                'cast' => $this->input->post('cast'),
                'first_adm_class' => $this->input->post('first_adm_class'),
                'prev_school_board' => $this->input->post('prev_school_board'),
                'repeated_class' => $this->input->post('repeated_class'),
                'subject_studied' => $allsub,
                'passed_promoted' => $this->input->post('passed_promoted'),
                'school_dues' => $this->input->post('school_dues'),
                'fee_concession' => $this->input->post('fee_concession'),
                'working_academic' => $this->input->post('working_academic'),
                'working_present' => $this->input->post('working_present'),
                'special_category' => $this->input->post('special_category'),
                'curricular_activities' => $this->input->post('curricular_activities'),
                'general_conduct' => $this->input->post('general_conduct'),
                'doa' => $this->input->post('doa') !="" ? date('Y-m-d', strtotime($this->input->post('doa'))):null,
                'doic' => $this->input->post('doic') !="" ? date('Y-m-d', strtotime($this->input->post('doic'))):null,
                'reason_leave' => $this->input->post('reason_leave'),
                'tc_certificate_no' => $tc_certificate_no,
                'st_session_id' =>  $this->input->post('session_id'),
                'remark' =>  $this->input->post('remark'),
                'last_class' =>  $this->input->post('last_class'),
                'status' =>  "Active",
                'tc_session_id' => $current_session_id,
            );

            // echo "<pre>";
            // print_r($data);die;
            // echo "</pre>";

            // $this->db->trans_begin();
            $insert_id = $this->certificate_model->addstudent_info($data);

            $array = array(
                // 'religion' => $this->input->post('religion'),
                // 'cast' => $this->input->post('cast'),
                // 'place_of_birth' => $this->input->post('pob'),
                'status' => 4,
                'is_active' => "no",
            );
            $this->studentsession_model->update_status_tc($array, $student_session->student_id,$student_session_id);
            $userdata = $this->customlib->getUserData();
            $data = array(
                'student_id' => $student_session->student_id,
                'student_session_id' => $student_session_id,
                'session_id' => $this->input->post('session_id'),
                'status' => 4,
                'remark' => "TC Taken",
                'created_at' => date('Y-m-d H:i:s'),
                'done_by' => $userdata['name'],
            );
            $this->student_model->student_update_status($data);
            // $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            // if ($this->db->trans_status() === FALSE)
            // {
            //         $this->db->trans_rollback();
            //         $json = array(
            //             "error" => "Please Try Again",
            //             "response" => ""
            //         );                  

            // }
            // else
            // {
                    // $this->db->trans_commit();
                    $student_info_id = $insert_id;
                    $setting_result        = $this->setting_model->get();
                    $data['settinglist']   = $setting_result[0];
                    $data['sch_setting']   = $this->sch_setting_detail;
                    $session_id = $this->setting_model->getCurrentSession();
        
                    $data['student'] = $this->student_model->get_student_only($student_session->student_id);
                    $data['student_info'] = $this->certificate_model->getstudent_info($student_info_id);
        
                    $dataview = $this->load->view('admin/certificate/tc_issue', $data, TRUE);
        
                    $json = array(
                        "success" => "Data Updates Successfully!!!!",
                        "response" => $dataview
                    );                    
            // }


            // redirect('admin/certificate/tc_register/' . $insert_id);
        }
        echo json_encode($json);
    }


    public function getstudents_detail()
    {
        $student_session_id = $this->input->post('student_session_id');

        $studentsdetail = $this->student_model->getByStudentSessionid($student_session_id);
        $studentsdetail['admission_date'] = $studentsdetail['admission_date'] !=""? date('d-m-Y', strtotime($studentsdetail['admission_date'])):null;
        echo json_encode($studentsdetail);
    }

    public function delete_tc($id)
    {
        if (!$this->rbac->hasPrivilege('tc', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'tc';

        $this->db->where('id', $id);
        $info = $this->db->get('student_info')->row_array();

        $student_id = $info['student_id'];
        $student_session_id = $info['student_session_id'];
        

        $this->certificate_model->remove_tc($id);

        $array = array(
            'status' => 1,
            'is_active' => "yes",
        );

        $this->studentsession_model->update_status_tc($array,$student_id,$student_session_id);

        redirect('admin/certificate/tc_list');
    }
    public function delete_fees_certificate($id)
    {
        if (!$this->rbac->hasPrivilege('fees_certicate', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'fees certificate';

        $array = array(
            'status' => 1,
        );

        $this->certificate_model->update_fees_certicate($id,$array);
        // $this->certificate_model->remove_fees_certicate($id);

        redirect('admin/certificate/fees_certicate');
    }

    public function getclass_students()
    {
        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        $session_id = $this->input->post('session_id');
        $main_id = $this->input->post('main_id');


        $studentslist = $this->student_model->getstudentforcertificate($class_id, $section_id, $session_id, $main_id)->result_array();
        echo json_encode($studentslist);
    }
    public function tc_issue()
    {
        // $this->load->view('layout/header');
        $this->load->view('admin/certificate/tc_issue');
        // $this->load->view('layout/footer');
    }

    public function create()
    {
        if (!$this->rbac->hasPrivilege('student_certificate', 'can_add')) {
            access_denied();
        }

        $data['title'] = 'Add Library';

        if (!empty($_FILES['background_image']['name'])) {
            $config['upload_path']   = 'uploads/certificate/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['file_name']     = $_FILES['background_image']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('background_image')) {
                $uploadData = $this->upload->data();
                $picture    = $uploadData['file_name'];
            } else {
                $picture = '';
            }
        } else {
            $picture = '';
        }

        $this->form_validation->set_rules('certificate_name', $this->lang->line('certificate_name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('certificate_text', $this->lang->line('certificate_text'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {

            $this->data['certificateList'] = $this->certificate_model->certificateList();
            $this->load->view('layout/header');
            $this->load->view('admin/certificate/createcertificate', $this->data);
            $this->load->view('layout/footer');
        } else {
            if ($this->input->post('is_active_student_img') == 1) {
                $enableimg = $this->input->post('is_active_student_img');
                $imgHeight = $this->input->post('image_height');
            } else {
                $enableimg = 0;
                $imgHeight = 0;
            }
            $data = array(
                'certificate_name'     => $this->input->post('certificate_name'),
                'certificate_text'     => $this->input->post('certificate_text'),
                'left_header'          => $this->input->post('left_header'),
                'center_header'        => $this->input->post('center_header'),
                'right_header'         => $this->input->post('right_header'),
                'left_footer'          => $this->input->post('left_footer'),
                'right_footer'         => $this->input->post('right_footer'),
                'center_footer'        => $this->input->post('center_footer'),
                'created_for'          => 2,
                'status'               => 1,
                'background_image'     => $picture,
                'header_height'        => $this->input->post('header_height'),
                'content_height'       => $this->input->post('content_height'),
                'footer_height'        => $this->input->post('footer_height'),
                'content_width'        => $this->input->post('content_width'),
                'enable_student_image' => $enableimg,
                'enable_image_height'  => $imgHeight,
            );
            $this->certificate_model->addcertificate($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/certificate/index');
        }
    }

    public function edit($id)
    {

        if (!$this->rbac->hasPrivilege('student_certificate', 'can_edit')) {
            access_denied();
        }
        $data['title']                 = 'Add Hostel';
        $data['id']                    = $id;
        $editcertificate               = $this->certificate_model->get($id);
        $this->data['editcertificate'] = $editcertificate;

        $custom_fields               = $this->customfield_model->get_custom_fields('students');
        $this->data['custom_fields'] = $custom_fields;
        $this->form_validation->set_rules('certificate_name', $this->lang->line('certificate_name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('certificate_text', $this->lang->line('certificate_text'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->data['certificateList'] = $this->certificate_model->certificateList();
            $this->load->view('layout/header');
            $this->load->view('admin/certificate/studentcertificateedit', $this->data);
            $this->load->view('layout/footer');
        } else {

            if ($this->input->post('is_active_student_img') == 1) {
                $enableimg = $this->input->post('is_active_student_img');
                $imgHeight = $this->input->post('image_height');
            } else {
                $enableimg = 0;
                $imgHeight = 0;
            }
            if (!empty($_FILES['background_image']['name'])) {

                $config['upload_path']   = 'uploads/certificate/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['file_name']     = $_FILES['background_image']['name'];

                //Load upload library and initialize configuration
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('background_image')) {
                    $uploadData = $this->upload->data();
                    $picture    = $uploadData['file_name'];
                    $data       = array(
                        'id'                   => $this->input->post('id'),
                        'certificate_name'     => $this->input->post('certificate_name'),
                        'certificate_text'     => $this->input->post('certificate_text'),
                        'left_header'          => $this->input->post('left_header'),
                        'center_header'        => $this->input->post('center_header'),
                        'right_header'         => $this->input->post('right_header'),
                        'left_footer'          => $this->input->post('left_footer'),
                        'right_footer'         => $this->input->post('right_footer'),
                        'center_footer'        => $this->input->post('center_footer'),
                        'created_for'          => 2,
                        'status'               => 1,
                        'background_image'     => $picture,
                        'header_height'        => $this->input->post('header_height'),
                        'content_height'       => $this->input->post('content_height'),
                        'footer_height'        => $this->input->post('footer_height'),
                        'content_width'        => $this->input->post('content_width'),
                        'enable_student_image' => $enableimg,
                        'enable_image_height'  => $imgHeight,
                    );
                } else {
                    $picture = '';
                    $data    = array(
                        'id'                   => $this->input->post('id'),
                        'certificate_name'     => $this->input->post('certificate_name'),
                        'certificate_text'     => $this->input->post('certificate_text'),
                        'left_header'          => $this->input->post('left_header'),
                        'center_header'        => $this->input->post('center_header'),
                        'right_header'         => $this->input->post('right_header'),
                        'left_footer'          => $this->input->post('left_footer'),
                        'right_footer'         => $this->input->post('right_footer'),
                        'center_footer'        => $this->input->post('center_footer'),
                        'header_height'        => $this->input->post('header_height'),
                        'content_height'       => $this->input->post('content_height'),
                        'footer_height'        => $this->input->post('footer_height'),
                        'content_width'        => $this->input->post('content_width'),
                        'enable_student_image' => $enableimg,
                        'enable_image_height'  => $imgHeight,
                    );
                }
            } else {
                $data = array(
                    'id'                   => $this->input->post('id'),
                    'certificate_name'     => $this->input->post('certificate_name'),
                    'certificate_text'     => $this->input->post('certificate_text'),
                    'left_header'          => $this->input->post('left_header'),
                    'center_header'        => $this->input->post('center_header'),
                    'right_header'         => $this->input->post('right_header'),
                    'left_footer'          => $this->input->post('left_footer'),
                    'right_footer'         => $this->input->post('right_footer'),
                    'center_footer'        => $this->input->post('center_footer'),
                    'header_height'        => $this->input->post('header_height'),
                    'content_height'       => $this->input->post('content_height'),
                    'footer_height'        => $this->input->post('footer_height'),
                    'content_width'        => $this->input->post('content_width'),
                    'enable_student_image' => $enableimg,
                    'enable_image_height'  => $imgHeight,
                );
            }
            $this->certificate_model->addcertificate($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/certificate/index');
        }
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('student_certificate', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Certificate List';
        $this->certificate_model->remove($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('delete_message') . '</div>');
        redirect('admin/certificate/index');
    }

    public function view()
    {
        $id     = $this->input->post('certificateid');
        $output = '';
        $data   = array();

        $data['certificate'] = $this->certificate_model->certifiatebyid($id);
        $preview             = $this->load->view('admin/certificate/preview_certificate', $data, true);
        echo $preview;
    }

    public function view1()
    {

        $id          = $this->input->post('certificateid');
        $output      = '';
        $certificate = $this->certificate_model->certifiatebyid($id);
?>
        <style type="text/css">
            body {
                font-family: 'arial';
            }

            .tc-container {
                width: 100%;
                position: relative;
                text-align: center;
            }

            .tc-container tr td {
                vertical-align: bottom;
            }
        </style>
        <div class="tc-container">
            <img src="<?php echo base_url('uploads/certificate/') ?><?php echo $certificate->background_image; ?>" width="100%" height="100%" />
            <table width="100%" cellspacing="0" cellpadding="0">
                <tr style="position:absolute; margin-left: auto;margin-right: auto;left: 0;right: 0;  width:<?php echo $certificate->content_width; ?>px; top:<?php echo $certificate->enable_image_height; ?>px">
                    <td valign="top" style="position: absolute;right: 0;">
                        <?php if ($certificate->enable_student_image == 1) { ?>
                            <img src="<?php echo base_url('uploads/certificate/noimage.jpg') ?>" width="100" height="auto">
                        <?php } ?>
                    </td>
                </tr>
                <tr style="position:absolute; margin-left: auto;margin-right: auto;left: 0;right: 0;  width:<?php echo $certificate->content_width; ?>px; top:<?php echo $certificate->header_height; ?>px">
                    <td valign="top" style="width:<?php echo $certificate->content_width; ?>px;font-size: 18px; text-align:left;position:relative;"><?php echo $certificate->left_header; ?></td>
                    <td valign="top" style="width:<?php echo $certificate->content_width; ?>px;font-size: 18px; text-align:center; position:relative; "><?php echo $certificate->center_header; ?></td>
                    <td valign="top" style="width:<?php echo $certificate->content_width; ?>px;font-size: 18px; text-align:right;position:relative;"><?php echo $certificate->right_header; ?></td>
                </tr>
                <tr style="position:absolute;margin-left: auto;margin-right: auto;left: 0;right: 0; width:<?php echo $certificate->content_width; ?>px; display: block; top:<?php echo $certificate->content_height; ?>px;">
                    <td colspan="3" valign="top" align="center">
                        <p style="font-size: 16px;position: relative;text-align:center; margin:0 auto; width: 100%; left:auto; right:0;"><?php echo $certificate->certificate_text; ?></p>
                    </td>
                </tr>
                <tr style="position:absolute; margin-left: auto;margin-right: auto;left: 0;right: 0;  width:<?php echo $certificate->content_width; ?>px; top:<?php echo $certificate->footer_height; ?>px">
                    <td valign="top" style="width:<?php echo $certificate->content_width; ?>px; font-size:18px;text-align:left;"><?php echo $certificate->left_footer; ?></td>
                    <td valign="top" style="width:<?php echo $certificate->content_width; ?>px; font-size:18px;text-align:center;"><?php echo $certificate->center_footer; ?></td>
                    <td valign="top" style="width:<?php echo $certificate->content_width; ?>px;font-size:18px;text-align:right;"><?php echo $certificate->right_footer; ?></td>
                </tr>
            </table>
        </div>
<?php
    }

    public function fees_certicate($id = "")
    {
        if (!$this->rbac->hasPrivilege('fees_certicate', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Certificate');
        $this->session->set_userdata('sub_menu', 'admin/certificate/fees_certicate');
        $this->data = "";

        if ($id != "") {
            $data['update']      = $this->certificate_model->getfeecertificate($id);
        }
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $session = $this->session_model->get();
        $data['sessionlist'] = $session;
        $data['resultlist'] = $this->certificate_model->getfeecertificate();
        $data['current_session_id'] = $this->setting_model->getCurrentSession();
        $data['student_info'] = $this->certificate_model->getstudent_info();
        $this->load->view('layout/header');
        $this->load->view('admin/certificate/fees_certicate', $data);
        $this->load->view('layout/footer');
    }

    public function feeCertificateValid()
    {
        // $class = $this->class_model->get();
        // $data['classlist'] = $class;
        // $session = $this->session_model->get();
        // $data['sessionlist'] = $session;
        // $data['current_session_id'] = $this->setting_model->getCurrentSession();


        $this->form_validation->set_rules('student_session_id', "Student", 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', "class", 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            // $this->load->view('layout/header');
            // $this->load->view('admin/certificate/fees_certicate', $data);
            // $this->load->view('layout/footer');
            $json = array(
                "error" => true,
                'student_session_id' => form_error('student_session_id', '<p>', '</p>'),
                'class_id' => form_error('class_id', '<p>', '</p>'),
            );
        } else {
            
            // echo "<pre>";
            // print_r ($this->input->post());die;
            // echo "</pre>";
            
            $fees_name = $this->input->post('fees_name');
            $amount = $this->input->post('amount');

            if (!empty($fees_name) && !empty($amount)) {

                $feesrow = $this->certificate_model->getfees_trnRow();
                $count = !empty($feesrow) ? $feesrow['certificate_no'] + 1 : 1 ;

                $arrayfeetrn = array(
                    'id' => $this->input->post('feetrn_id'),
                    'student_session_id' => $this->input->post('student_session_id'),
                    'session_id' => $this->input->post('current_session_id'),
                    'date' => date('Y-m-d'),
                );

                if ($this->input->post('feetrn_id')=='') {
                    $arrayfeetrn['certificate_no'] =  $count;
                }

                $fees_trn_id = $this->certificate_model->addfeecertificate_trn($arrayfeetrn);
                
                $i = 0;

                $sbids = $this->input->post('feesub_id');
                if($this->input->post('feetrn_id')=='')
                {
                    foreach ($fees_name as  $value) {

                        $arrayfeeSub = array(
                            'fees_trn_id' => $fees_trn_id,
                            'fees_name' => $value,
                            'amount' => $this->input->post('amount')[$i],
                        );

                        $this->certificate_model->addfeecertificate_sub($arrayfeeSub);
                        $i++;
                    }                    
                }
                else
                {
                    $this->db->where('fees_trn_id', $fees_trn_id);
                    $this->db->delete('fees_certicate_sub');
                    foreach ($fees_name as  $value) {
                        if($i< sizeof($sbids))
                        {$subid = $sbids[$i];}
                        else
                        {$subid="";}
                        
                        $arrayfeeSub = array(
                            'id' => $subid,
                            'fees_trn_id' => $fees_trn_id,
                            'fees_name' => $value,
                            'amount' => $this->input->post('amount')[$i],
                        );
                        // print_r($arrayfeeSub);
                        $this->certificate_model->addfeecertificate_sub($arrayfeeSub);
                        $i++;
                    }
                }

                $data['student_session_id'] = $student_session_id = $this->input->post('student_session_id');
                $setting_result        = $this->setting_model->get();
                $data['settinglist']   = $setting_result[0];
                $data['sch_setting'] = $this->setting_model->get();
                $session_id = $this->setting_model->getCurrentSession();

                $data['students'] = $this->student_model->getfeecerticateresult($fees_trn_id);
                $dataview = $this->load->view('admin/certificate/printfeecertificate', $data, TRUE);

                $json = array(
                "success" => "Data Updates Successfully!!!!",
                "response" => $dataview
                );
            }
        }
        echo json_encode($json);
    }
    public function print_fee_certificate()
    {
        $data['certificate_id'] = $certificate_id = $this->input->post('certificate_id');
        $setting_result        = $this->setting_model->get();
        $data['settinglist']   = $setting_result[0];
        $data['sch_setting'] = $this->setting_model->get();
        $session_id = $this->setting_model->getCurrentSession();

        $data['students'] = $this->student_model->getfeecerticateresult($certificate_id);
        
        $this->load->view('admin/certificate/printfeecertificate', $data);
    }

    public function tru_tc()
    {
        if (!$this->rbac->hasPrivilege('tru_tc', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Certificate');
        $this->session->set_userdata('sub_menu', 'admin/certificate/tc_register');
        $this->session->set_userdata('subsub_menu', 'admin/certificate/tru_tc');

        $this->data = "";

        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $session = $this->session_model->get();
        $data['sessionlist'] = $session;

        $data['resultlist'] = $this->certificate_model->getstudent_info();

        $this->load->view('layout/header');
        $this->load->view('admin/certificate/tru_tc', $data);
        $this->load->view('layout/footer');
    }

    public function trutc_register($id = "")
    {
        $this->session->set_userdata('top_menu', 'Certificate');
        $this->session->set_userdata('sub_menu', 'admin/certificate/tc_register');
        $this->session->set_userdata('subsub_menu', 'admin/certificate/trutc_register');
        $this->data = "";

        if ($id != "") {
            $data['update'] = $update      = $this->certificate_model->getstudent_info($id);
            $data['student_session_id']  = $this->studentsession_model->getSessionById($update['student_session_id']);
        }

        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $session = $this->session_model->get();
        $data['sessionlist'] = $session;
        $reasonlist = $this->certificate_model->get_reasons();
        $data['reasonlist'] = $reasonlist;
        $conductlist = $this->certificate_model->get_conductlist();
        $data['conductlist'] = $conductlist;
        $subject_results = $this->subject_model->getsubject();
        $data['subjectlist'] = $subject_results;


        $data['student_info'] = $this->certificate_model->getstudent_info();

        $this->load->view('layout/header');
        $this->load->view('admin/certificate/trutc_register', $data);
        $this->load->view('layout/footer');
    }

    public function trutc_registerValid()
    {

        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|xss_clean');
        
        if ($this->form_validation->run() == false) {
            $json = array(
               
            );
            
        } else {

            $student_id      = $this->input->post('student_id');
            $session         = $this->student_model->get($student_id);
            $setting         = $this->setting_model->getSetting();
            $current_session_id      = $this->setting_model->getCurrentSession();

            $studentinfo_count = $this->certificate_model->getstudent_info_count();

            if (empty($studentinfo_count)) {
                $tc_certificate_no = $setting->last_certificate_no + 1;
            }
            // elseif (!empty($this->input->post('id'))) {
            //     $tc_certificate_no = $studentinfo_count['tc_certificate_no'];
            // } 
            else {
                $tc_certificate_no = $studentinfo_count['tc_certificate_no'] + 1;
            }
            
            $subjects = $this->input->post('subject_studied');
            if (!empty($subjects)) {
                $allsub = implode(',', $subjects);
            } else {
                $allsub = "";
            }
            $student_session_id = $this->input->post('student_id');
            $student_session = $this->studentsession_model->getSessionById($student_session_id);
            $studentCount  = $this->certificate_model->getCertificateCount($student_session->student_id);
            
            $data = array(
                'session_id' => $current_session_id,
                'class_id' => $this->input->post('class_id'),
                'section_id' => $this->input->post('section_id'),
                'student_id' => $student_session->student_id,
                'student_session_id' => $student_session_id,
                'mother_tongue' => $this->input->post('mother_tongue'),
                'nationality' => $this->input->post('nationality'),
                'pob' => $this->input->post('pob'),
                'religion' => $this->input->post('religion'),
                'cast' => $this->input->post('cast'),
                'first_adm_class' => date('Y-m-d', strtotime($this->input->post('first_adm_class'))),
                'prev_school_board' => $this->input->post('prev_school_board'),
                'repeated_class' => $this->input->post('repeated_class'),
                'subject_studied' => $allsub,
                'passed_promoted' => $this->input->post('passed_promoted'),
                'school_dues' => $this->input->post('school_dues'),
                'fee_concession' => $this->input->post('fee_concession'),
                'working_academic' => $this->input->post('working_academic'),
                'working_present' => $this->input->post('working_present'),
                'special_category' => $this->input->post('special_category'),
                'curricular_activities' => $this->input->post('curricular_activities'),
                'general_conduct' => $this->input->post('general_conduct'),
                'doa' => date('Y-m-d', strtotime($this->input->post('doa'))),
                'doic' => date('Y-m-d', strtotime($this->input->post('doic'))),
                'reason_leave' => $this->input->post('reason_leave'),
                'tc_certificate_no' => $tc_certificate_no,
                'st_session_id' =>  $this->input->post('session_id'),
                'status' =>  "Active",
                'tc_type' =>  "ttc",
            );


            if ($studentCount < 3) {
                
                $insert_id = $this->certificate_model->addstudent_info2($data);
            }else {
                $info = $this->certificate_model->getByStudentId($student_session->student_id);
                $insert_id = $info['id'];
            }


            $array = array(
                
                'status' => 4,
                'is_active' => "no",
            );
            $this->studentsession_model->update_status_tc($array, $student_session->student_id,$student_session_id);

            $student_info_id = $insert_id;
            $setting_result        = $this->setting_model->get();
            $data['settinglist']   = $setting_result[0];
            $data['sch_setting']   = $this->sch_setting_detail;
            $session_id = $this->setting_model->getCurrentSession();

            $data['student'] = $this->student_model->get($student_session->student_id);
            $data['student_info'] = $this->certificate_model->getstudent_info($student_info_id);

            $dataview = $this->load->view('admin/certificate/tc_issue', $data, TRUE);

            $json = array(
                "success" => "Data Updates Successfully!!!!",
                "response" => $dataview
            );

        }
        echo json_encode($json);
    }

    
    public function ctc()
    {
        if (!$this->rbac->hasPrivilege('ctc', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Certificate');
        $this->session->set_userdata('sub_menu', 'admin/certificate/tc_register');
        $this->session->set_userdata('subsub_menu', 'admin/certificate/ctc');

        $this->data = "";

        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $session = $this->session_model->get();
        $data['sessionlist'] = $session;

        $data['resultlist'] = $this->certificate_model->getstudent_info();

        $this->load->view('layout/header');
        $this->load->view('admin/certificate/ctc', $data);
        $this->load->view('layout/footer');
    }

    public function ctc_register($id = "")
    {
        $this->session->set_userdata('top_menu', 'Certificate');
        $this->session->set_userdata('sub_menu', 'admin/certificate/tc_register');
        $this->data = "";

        if ($id != "") {
            $data['update'] = $update      = $this->certificate_model->getstudent_info($id);
            $data['student_session_id']  = $this->studentsession_model->getSessionById($update['student_session_id']);
        }

        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $session = $this->session_model->get();
        $data['sessionlist'] = $session;
        $reasonlist = $this->certificate_model->get_reasons();
        $data['reasonlist'] = $reasonlist;
        $conductlist = $this->certificate_model->get_conductlist();
        $data['conductlist'] = $conductlist;
        $subject_results = $this->subject_model->getsubject();
        $data['subjectlist'] = $subject_results;


        $data['student_info'] = $this->certificate_model->getstudent_info();

        $this->load->view('layout/header');
        $this->load->view('admin/certificate/ctc_register', $data);
        $this->load->view('layout/footer');
    }

    public function ctc_registerValid()
    {

        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|xss_clean');
        
        if ($this->form_validation->run() == false) {
            $json = array(
               
            );
            
        } else {

            $student_id      = $this->input->post('student_id');
            $session         = $this->student_model->get($student_id);
            $setting         = $this->setting_model->getSetting();
            $current_session_id      = $this->setting_model->getCurrentSession();

            $studentinfo_count = $this->certificate_model->getstudent_info_count();

            if (empty($studentinfo_count)) {
                $tc_certificate_no = $setting->last_certificate_no + 1;
            }
            // elseif (!empty($this->input->post('id'))) {
            //     $tc_certificate_no = $studentinfo_count['tc_certificate_no'];
            // } 
            else {
                $tc_certificate_no = $studentinfo_count['tc_certificate_no'] + 1;
            }
            
            $subjects = $this->input->post('subject_studied');
            if (!empty($subjects)) {
                $allsub = implode(',', $subjects);
            } else {
                $allsub = "";
            }
            $student_session_id = $this->input->post('student_id');
            $student_session = $this->studentsession_model->getSessionById($student_session_id);
            $studentCount  = $this->certificate_model->getCertificateCount($student_session->student_id);
            $data = array(
                'session_id' => $current_session_id,
                'class_id' => $this->input->post('class_id'),
                'section_id' => $this->input->post('section_id'),
                'student_id' => $student_session->student_id,
                'student_session_id' => $student_session_id,
                'mother_tongue' => $this->input->post('mother_tongue'),
                'nationality' => $this->input->post('nationality'),
                'pob' => $this->input->post('pob'),
                'religion' => $this->input->post('religion'),
                'cast' => $this->input->post('cast'),
                'first_adm_class' => date('Y-m-d', strtotime($this->input->post('first_adm_class'))),
                'prev_school_board' => $this->input->post('prev_school_board'),
                'repeated_class' => $this->input->post('repeated_class'),
                'subject_studied' => $allsub,
                'passed_promoted' => $this->input->post('passed_promoted'),
                'school_dues' => $this->input->post('school_dues'),
                'fee_concession' => $this->input->post('fee_concession'),
                'working_academic' => $this->input->post('working_academic'),
                'working_present' => $this->input->post('working_present'),
                'special_category' => $this->input->post('special_category'),
                'curricular_activities' => $this->input->post('curricular_activities'),
                'general_conduct' => $this->input->post('general_conduct'),
                'doa' => date('Y-m-d', strtotime($this->input->post('doa'))),
                'doic' => date('Y-m-d', strtotime($this->input->post('doic'))),
                'reason_leave' => $this->input->post('reason_leave'),
                'tc_certificate_no' => $tc_certificate_no,
                'st_session_id' =>  $this->input->post('session_id'),
                'status' =>  "Active",
                'tc_type' =>  "ctc",
            );


            if ($studentCount < 3) {
                
                $insert_id = $this->certificate_model->addstudent_info2($data);
            }else {
                $info = $this->certificate_model->getByStudentId($student_session->student_id);
                $insert_id = $info['id'];
            }


            $array = array(
                
                'status' => 4,
                'is_active' => "no",
            );
            $this->studentsession_model->update_status_tc($array, $student_session->student_id,$student_session_id);

            $student_info_id = $insert_id;
            $setting_result        = $this->setting_model->get();
            $data['settinglist']   = $setting_result[0];
            $data['sch_setting']   = $this->sch_setting_detail;
            $session_id = $this->setting_model->getCurrentSession();

            $data['student'] = $this->student_model->get($student_session->student_id);
            $data['student_info'] = $this->certificate_model->getstudent_info($student_info_id);

            $dataview = $this->load->view('admin/certificate/tc_issue', $data, TRUE);

            $json = array(
                "success" => "Data Updates Successfully!!!!",
                "response" => $dataview
            );

        }
        echo json_encode($json);
    }
}
?>