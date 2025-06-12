<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class User extends Student_Controller
{
    public $school_name;
    public $school_setting;
    public $setting;
    public $payment_method;
    public $current_ay_session;

    public function __construct()
    {
        parent::__construct();
        $this->payment_method     = $this->paymentsetting_model->getActiveMethod();
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $settings              = $this->setting_model->getSetting();
        $this->current_ay_session = $settings->session_id;   
        $this->load->model("student_edit_field_model");
        $this->config->load('mailsms');
        $this->load->model('onlinestudent_model');
        $this->load->helper('url');
    }
    public function unauthorized()
    {
        $data = array();
        $this->load->view('layout/student/header');
        $this->load->view('unauthorized', $data);
        $this->load->view('layout/student/footer');
    }

    public function choose()
    {
        if ($this->session->has_userdata('current_class')) {

            redirect('user/user/dashboard');
        }
        
        $data['sch_setting']      = $this->sch_setting_detail;
        $role                     = $this->customlib->getUserRole();
        $data['role']             = $role;
        $student_current_class    = array();
        $default_login_student_id = "";
        if ($role == "student") {
            $student_id            = $this->customlib->getStudentSessionUserID();
            $data['student_lists'] = $this->studentsession_model->searchMultiClsSectionByStudent($student_id);
            if ($data['student_lists'][0]->default_login) {
                $default_login_student_id = $data['student_lists'][0]->student_id;
                $student_current_class    = array('class_id' => $data['student_lists'][0]->class_id, 'section_id' => $data['student_lists'][0]->section_id, 'student_session_id' => $data['student_lists'][0]->student_session_id);
            }
        } elseif ($role == "parent") {
            $parent_id             = $this->customlib->getUsersID();
            $data['student_lists'] = $this->student_model->getParentChilds_mobileno($parent_id);
            if ($data['student_lists'][0]->default_login) {
                $default_login_student_id = $data['student_lists'][0]->id;
                $student_current_class    = array('class_id' => $data['student_lists'][0]->class_id, 'section_id' => $data['student_lists'][0]->section_id, 'student_session_id' => $data['student_lists'][0]->student_session_id);
            }
        }
        if (!empty($student_current_class)) {
            $logged_In_User               = $this->customlib->getLoggedInUserData();
            $logged_In_User['student_id'] = $default_login_student_id;
            $this->session->set_userdata('student', $logged_In_User);
            $this->session->set_userdata('current_class', $student_current_class);
             redirect('user/user/dashboard');
        }

        $this->form_validation->set_rules('clschg', $this->lang->line('select') . " " . $this->lang->line('class'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == true) {
            $student_session_id           = $this->input->post('clschg');
            $student                      = $this->student_model->getByStudentSession($student_session_id);
            //$logged_In_User               = $this->customlib->getLoggedInUserData();
            //$logged_In_User['student_id'] = $student['id'];
            //$this->session->set_userdata('student', $logged_In_User);
            $this->change_sesson_with_another_parent_login($student_session_id);
            $this->studentsession_model->updateById(array('id'=>$student_session_id,'default_login'=>1));
            $student_current_class = array('class_id' => $student['class_id'], 'section_id' => $student['section_id'], 'student_session_id' => $student['student_session_id']);
            $this->session->set_userdata('current_class', $student_current_class);   
            redirect('user/user/dashboard');
        }
        
        $this->load->view('user/choose', $data);

    }
    public function change_sesson_with_another_parent_login($student_session_id)
    {
        $login_details         = $this->user_model->checkParentLogin_session_id($student_session_id);
        if(!empty($login_details)) {
            $user = $login_details[0];

                if ($user->role == "student") {
                    $result = $this->user_model->read_user_information($user->id);
                } else if ($user->role == "parent") {
                    $result = $this->user_model->checkLoginParent_id($user->id);
                }
                if ($result != false) {
                    $setting_result = $this->setting_model->get();
                    if ($result[0]->lang_id == 0) {
                        $language = array('lang_id' => $setting_result[0]['lang_id'], 'language' => $setting_result[0]['language']);
                    } else {
                        $language = array('lang_id' => $result[0]->lang_id, 'language' => $result[0]->language);
                    }
                    $image    = '';
                    if ($result[0]->role == "parent") {
                        $username = $result[0]->father_name;
                        if ($result[0]->guardian_relation == "Father") {
                            $image = $result[0]->father_pic;
                        } else if ($result[0]->guardian_relation == "Mother") {
                            $image = $result[0]->mother_pic;
                        } else if ($result[0]->guardian_relation == "Other") {
                            $image = $result[0]->guardian_pic;
                        }
                    } elseif ($result[0]->role == "student") {
                        $image    = $result[0]->image;
                        $username = $this->customlib->getFullName($result[0]->firstname,$result[0]->middlename,$result[0]->lastname,$this->sch_setting->middlename,$this->sch_setting->lastname);
                        $defaultclass = $this->user_model->get_studentdefaultClass($result[0]->user_id);
                        $this->customlib->setUserLog($result[0]->username, $result[0]->role,$defaultclass['id']);
                    }
                    $session_data = array(
                        'id'              => $result[0]->id,
                        'login_username'  => $result[0]->username,
                        'student_id'      => $result[0]->user_id,
                        'role'            => $result[0]->role,
                        'username'        => $username,
                        'date_format'     => $setting_result[0]['date_format'],
                        'start_week'      => date("w", strtotime($setting_result[0]['start_week'])),
                        'currency_symbol' => $setting_result[0]['currency_symbol'],
                        'timezone'        => $setting_result[0]['timezone'],
                        'sch_name'        => $setting_result[0]['name'],
                        'language'        => $language,
                        'is_rtl'          => $setting_result[0]['is_rtl'],
                        'theme'           => $setting_result[0]['theme'],
                        'image'           =>  $image,
                        'gender'          => $result[0]->gender,
                        'mobileno'        => $result[0]->mobileno,
                    );
                    if($session_data['is_rtl'] == "disabled"){
                    $language_result1 = $this->language_model->get($language['lang_id']);
                    if ($this->customlib->get_rtl_languages($language_result1['short_code'])) {
                        $session_data['is_rtl'] = 'enabled';
                    } else {
                        $session_data['is_rtl'] = 'disabled';
                    }
                    }
                    $this->session->set_userdata('student', $session_data);
                    if ($result[0]->role == "parent") {
                        $this->customlib->setUserLog($result[0]->username, $result[0]->role);
                    }
                }
                    
            }
    }
    public function dashboard()
    {

        $this->session->set_userdata('top_menu', 'Dashboard');
        $student_id            = $this->customlib->getStudentSessionUserID();
        $student_current_class = $this->customlib->getStudentCurrentClsSection();
        $data['sch_setting']  = $this->sch_setting_detail;

       // $student = $this->student_model->getStudentByClassSectionID($student_current_class->class_id, $student_current_class->section_id, $student_id);


        $student              = $this->student_model->getByStudentSession($student_current_class->student_session_id);
        $student_id           = $student['id'];
        $data['student']      = $student;
        

        $data = array();
        if (!empty($student)) {

            $student_session_id           = $student_current_class->student_session_id;
            $gradeList                    = $this->grade_model->get();
            $student_due_fee              = $this->studentfeemaster_model->getStudentFees($student_session_id);
            $student_discount_fee         = $this->feediscount_model->getStudentFeesDiscount($student_session_id);
            $data['student_discount_fee'] = $student_discount_fee;
            $data['student_due_fee']      = $student_due_fee;
            $timeline                     = $this->timeline_model->getStudentTimeline($student["id"], $status = 'yes');
            $data["timeline_list"]        = $timeline;
            $data['sch_setting']          = $this->sch_setting_detail;
            // echo "<pre>";
            // print_r($data['sch_setting']);die();
            $data['adm_auto_insert']      = $this->sch_setting_detail->adm_auto_insert;
            $data['examSchedule']         = array();
            $data['exam_result']          = $this->examgroupstudent_model->searchStudentExams($student['student_session_id'], true, true);
            $ss                           = $this->grade_model->getGradeDetails();
            $data['exam_grade']           = $this->grade_model->getGradeDetails();
            $student_doc                  = $this->student_model->getstudentdoc($student_id);
            $data['student_doc']          = $student_doc;
            $data['student_doc_id']       = $student_id;
            $category_list                = $this->category_model->get();
            $data['category_list']        = $category_list;
            $data['gradeList']            = $gradeList;
            $data['student']              = $student;

        }

        $unread_notifications = $this->notification_model->getUnreadStudentNotification();

        $notification_bydate = array();

        foreach ($unread_notifications as $unread_notifications_key => $unread_notifications_value) {
            if (date($this->customlib->getSchoolDateFormat()) >= date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($unread_notifications_value->publish_date))) {
                $notification_bydate[] = $unread_notifications_value;
            }
        }

        $data['unread_notifications'] = $notification_bydate;

        $this->load->view('layout/student/header', $data);
        $this->load->view('user/dashboard', $data);
        $this->load->view('layout/student/footer', $data);
    }
    public function changepass()
    {
        $data['title'] = 'Change Password';
        $this->form_validation->set_rules('current_pass', 'Current password', 'trim|required|xss_clean');
        $this->form_validation->set_rules('new_pass', 'New password', 'trim|required|xss_clean|matches[confirm_pass]');
        $this->form_validation->set_rules('confirm_pass', 'Confirm password', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $sessionData            = $this->session->userdata('student');
            $this->data['id']       = $sessionData['id'];
            $this->data['username'] = $sessionData['login_username'];
            $this->load->view('layout/student/header', $data);
            $this->load->view('user/change_password', $data);
            $this->load->view('layout/student/footer', $data);
        } else {
            $sessionData = $this->customlib->getLoggedInUserData();
            $data_array  = array(
                'current_pass' => ($this->input->post('current_pass')),
                'new_pass'     => ($this->input->post('new_pass')),
                'user_id'      => $sessionData['id'],
                'user_name'    => $sessionData['username'],
            );
            $newdata = array(
                'parent_id'       => $sessionData['id'],
                'password' => $this->input->post('new_pass'),
            );
            if ($sessionData['role'] == "parent") {
                $query1 = $this->user_model->checkOldPassNew($data_array);
            } else {
                $query1 = $this->user_model->checkOldPass($data_array);
            }
            
            if ($query1) {
                if ($sessionData['role'] == "parent") {
                    $query2 = $this->user_model->saveNewPassNew($newdata);
                } else {
                    $query2 = $this->user_model->saveNewPass($newdata);
                }
                if ($query2) {

                    $this->session->set_flashdata('success_msg', 'Password changed successfully');
                    $this->load->view('layout/student/header', $data);
                    $this->load->view('user/change_password', $data);
                    $this->load->view('layout/student/footer', $data);
                }
            } else {

                $this->session->set_flashdata('error_msg', 'Invalid current password');
                $this->load->view('layout/student/header', $data);
                $this->load->view('user/change_password', $data);
                $this->load->view('layout/student/footer', $data);
            }
        }
    }

    public function changeusername()
    {
        $sessionData = $this->customlib->getLoggedInUserData();

        $data['title'] = 'Change Username';
        $this->form_validation->set_rules('current_username', 'Current username', 'trim|required|xss_clean');
        $this->form_validation->set_rules('new_username', 'New username', 'trim|required|xss_clean|matches[confirm_username]');
        $this->form_validation->set_rules('confirm_username', 'Confirm username', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {

        } else {

            $data_array = array(
                'username'     => $this->input->post('current_username'),
                'new_username' => $this->input->post('new_username'),
                'role'         => $sessionData['role'],
                'user_id'      => $sessionData['id'],
            );
            $newdata = array(
                'id'       => $sessionData['id'],
                'username' => $this->input->post('new_username'),
            );
            $is_valid = $this->user_model->checkOldUsername($data_array);

            if ($is_valid) {
                $is_exists = $this->user_model->checkUserNameExist($data_array);
                if (!$is_exists) {
                    $is_updated = $this->user_model->saveNewUsername($newdata);
                    if ($is_updated) {
                        $this->session->set_flashdata('success_msg', 'Username changed successfully');
                        redirect('user/user/changeusername');
                    }
                } else {
                    $this->session->set_flashdata('error_msg', 'Username Already Exists, Please choose other');
                }
            } else {
                $this->session->set_flashdata('error_msg', 'Invalid current username');
            }
        }
        $this->data['id']       = $sessionData['id'];
        $this->data['username'] = $sessionData['username'];
        $this->load->view('layout/student/header', $data);
        $this->load->view('user/change_username', $data);
        $this->load->view('layout/student/footer', $data);
    }

    public function download($student_id, $doc)
    {
        $this->load->helper('download');
        $filepath = "./uploads/student_documents/$student_id/" . $this->uri->segment(5);
        $data     = file_get_contents($filepath);
        $name     = $this->uri->segment(6);
        force_download($name, $data);
    }

    public function user_language($lang_id)
    {
        $language_name = $this->db->select('languages.language')->from('languages')->where('id', $lang_id)->get()->row_array();
        $student       = $this->session->userdata('student');
        if (!empty($student)) {
            $this->session->unset_userdata('student');
        }
        $language_array      = array('lang_id' => $lang_id, 'language' => $language_name['language']);
        $student['language'] = $language_array;
        $this->session->set_userdata('student', $student);

        $session         = $this->session->userdata('student');
        $id              = $session['student_id'];
        $data['lang_id'] = $lang_id;
        $language_result = $this->language_model->set_studentlang($id, $data);
    }

    public function timeline_download($timeline_id, $doc)
    {
        $this->load->helper('download');
        $filepath = "./uploads/student_timeline/" . $doc;
        $data     = file_get_contents($filepath);
        $name     = $doc;
        force_download($name, $data);
    }

    public function view($id)
    {
        $data['title']           = 'Student Details';
        $student                 = $this->student_model->get($id);
        $student_due_fee         = $this->studentfee_model->getDueFeeBystudent($student['class_id'], $student['section_id'], $id);
        $data['student_due_fee'] = $student_due_fee;
        $transport_fee           = $this->studenttransportfee_model->getTransportFeeByStudent($student['student_session_id']);
        $data['transport_fee']   = $transport_fee;
        $examList                = $this->examschedule_model->getExamByClassandSection($student['class_id'], $student['section_id']);
        $data['examSchedule']    = array();
        if (!empty($examList)) {
            $new_array = array();
            foreach ($examList as $ex_key => $ex_value) {
                $array         = array();
                $x             = array();
                $exam_id       = $ex_value['exam_id'];
                $exam_subjects = $this->examschedule_model->getresultByStudentandExam($exam_id, $student['id']);
                foreach ($exam_subjects as $key => $value) {
                    $exam_array                     = array();
                    $exam_array['exam_schedule_id'] = $value['exam_schedule_id'];
                    $exam_array['exam_id']          = $value['exam_id'];
                    $exam_array['full_marks']       = $value['full_marks'];
                    $exam_array['passing_marks']    = $value['passing_marks'];
                    $exam_array['exam_name']        = $value['name'];
                    $exam_array['exam_type']        = $value['type'];
                    $exam_array['attendence']       = $value['attendence'];
                    $exam_array['get_marks']        = $value['get_marks'];
                    $x[]                            = $exam_array;
                }
                $array['exam_name']   = $ex_value['exam_name'];
                $array['exam_result'] = $x;
                $new_array[]          = $array;
            }
            $data['examSchedule'] = $new_array;
        }
        return $data['student'] = $student;
    }

    // public function getfees()
    // {

    //     $id                    = $this->customlib->getStudentSessionUserID();
    //     $student_current_class = $this->customlib->getStudentCurrentClsSection();

    //     $this->session->set_userdata('top_menu', 'fees');
    //     $this->session->set_userdata('sub_menu', 'student/getFees');
    //     $category                = $this->category_model->get();
    //     $data['categorylist']    = $category;
    //     $data['sch_setting']     = $this->sch_setting_detail;
    //     $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
    //     $paymentoption           = $this->customlib->checkPaypalDisplay();
    //     $data['paymentoption']   = $paymentoption;
    //     $data['payment_method']  = false;
    //     if (!empty($this->payment_method)) {
    //         $data['payment_method'] = true;
    //     }
    //     $student_id                   = $id;
    //     $student                      = $this->student_model->getStudentByClassSectionID($student_current_class->class_id, $student_current_class->section_id, $student_id);
    //     $class_id                     = $student_current_class->class_id;
    //     $section_id                   = $student_current_class->section_id;
    //     $data['title']                = 'Student Details';
    //     $student_due_fee              = $this->studentfeemaster_model->getStudentFees($student_current_class->student_session_id);
    //     $student_discount_fee         = $this->feediscount_model->getStudentFeesDiscount($student_current_class->student_session_id);
    //     $data['student_discount_fee'] = $student_discount_fee;
    //     $data['student_due_fee']      = $student_due_fee;
    //     $data['student']              = $student;
    //     $category                     = $this->category_model->get();
    //     $data['categorylist']         = $category;
    //     $class_section                = $this->student_model->getClassSection($student["class_id"]);
    //     $data["class_section"]        = $class_section;
    //     $session                      = $this->setting_model->getCurrentSession();
    //     $studentlistbysection         = $this->student_model->getStudentClassSection($student["class_id"], $session);
    //     $data["studentlistbysection"] = $studentlistbysection;

    //     $this->load->view('layout/student/header', $data);
    //     $this->load->view('student/getfees', $data);
    //     $this->load->view('layout/student/footer', $data);
    // }

    public function getfees()
    {
        echo "Sorry..Because of year ending purpose we are not allowing to see fee to pay online. Please contact to your school admin office.";die();
        $id                    = $this->customlib->getStudentSessionUserID();
        $student_current_class = $this->customlib->getStudentCurrentClsSection();
        $data['sch_setting']  = $this->sch_setting_detail;
        $this->session->set_userdata('top_menu', 'fees');
        $this->session->set_userdata('sub_menu', 'student/getFees');
        $student              = $this->student_model->getByStudentSession($student_current_class->student_session_id);
        $data['student']      = $student;
        $student_sessionlist  = $this->student_model->get_studentsessionlist($student['id']);
        $student_due_fee = [];
        $student_discount_fee = [];
        $student_session_id = [];
        foreach ($student_sessionlist as $key => $value) {
            $id =  $value['id'];
            $student_session_id[] = $id;
            $student_due_fee[]          = $this->studentfeemaster_model->getStudentFees($id);
            $student_discount_fee[]     = $this->feediscount_model->getStudentFeesDiscount($id);
        }
        $data['student_discount_fee'] = $student_discount_fee;
        $data['student_due_fee']      = $student_due_fee;
        $data['student_session_id']   = $student_session_id;

        $data['current_session']      = $this->setting_model->getCurrentSession();
        $category                     = $this->category_model->get();
        $data['categorylist']         = $category;
        $class_section                = $this->student_model->getClassSection($student["class_id"]);
        $data["class_section"]        = $class_section;
        $session                      = $this->setting_model->getCurrentSession();
        $studentlistbysection         = $this->student_model->getStudentClassSection($student["class_id"], $session);
        $data["studentlistbysection"] = $studentlistbysection;
        $data['userdata']             = $this->customlib->getUserData();
        //$data['class_teacher']        = $this->staff_model->get_class_teacher_data($student_session_id);

        $this->load->view('layout/student/header', $data);
        $this->load->view('student/getfeesNew', $data);
        $this->load->view('layout/student/footer', $data);
    }
    public function printFeesByGroupArray()
    {

        $setting_result        = $this->setting_model->get();
        $data['settinglist']   = $setting_result[0];
        $data['sch_setting'] = $this->sch_setting_detail;
        $record              = $this->input->post('data');
        $record_array        = json_decode($record);
        $fees_array          = array();
        foreach ($record_array as $key => $value) {
            $fee_groups_feetype_id = $value->fee_groups_feetype_id;
            $fee_master_id         = $value->fee_master_id;
            $fee_session_group_id  = $value->fee_session_group_id;
            $feeList               = $this->studentfeemaster_model->getDueFeeByFeeSessionGroupFeetype($fee_session_group_id, $fee_master_id, $fee_groups_feetype_id);
            $fees_array[]          = $feeList;
        }
        $data['feearray'] = $fees_array;
        $this->load->view('print/printFeesByGroupArrayNew', $data);
    }

    public function getcollectfee()
    {
        $setting_result      = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        $data['student_session_id']      = $this->input->post('student_session_id');
        $data['total_main_fees']         = $this->input->post('total_main_fees');
        $data['total_other_fees']        = $this->input->post('total_other_fees');
        $data['total_balance_fees']      = $this->input->post('total_balance_fees');
        $data['enable_auto_disc']        = $this->input->post('enable_auto_disc');  
        $data['selected_balance']        = $this->input->post('selected_balance');  
        $record              = $this->input->post('data');
        $record_array        = json_decode($record);
        $fees_array = array();
        $selected_amt = 0;
        foreach ($record_array as $key => $value) {
            $fee_groups_feetype_id = $value->fee_groups_feetype_id;
            $fee_master_id         = $value->fee_master_id;
            $fee_session_group_id  = $value->fee_session_group_id;
            $feeList               = $this->studentfeemaster_model->getDueFeeByFeeSessionGroupFeetype($fee_session_group_id, $fee_master_id, $fee_groups_feetype_id);
            $fees_array[]          = $feeList;
            $selected_amt += $feeList->amount;
        }
        if(!empty($fees_array))
        {
            foreach($fees_array as $fees)
            {
                $st_session_ids[] = $fees->student_session_id;
                $fee_deposits = json_decode(($fees->amount_detail));
                if(!empty($fee_deposits))
                {
                    foreach($fee_deposits as $fee)
                    {
                        $selected_amt = $selected_amt - ($fee->amount + $fee->amount_discount);
                    }
                }
            }
        }
        if($data['total_balance_fees'] == $data['selected_balance'] )
        {$data['full_pay'] = 1;$data['ot_enabled']=1;}
        else
        {$data['full_pay'] = 0;$data['ot_enabled']=0;}

        $data['feearray'] = $fees_array;
        $data['selected_amt'] = $selected_amt;
        if (!empty($fees_array)) {
            $data['discount_not_applied']   = $this->feediscount_model->getDiscountNotApplieddropdown_type($fees_array[0]->student_session_id);
            if(!empty($data['discount_not_applied']))
            {
                foreach($data['discount_not_applied'] as $discs)
                {
                    if($discs->code=="LAF" && ($discs->feepercent > 0 || $discs->amount > 0 )  )
                    {
                        $data['lafd']=$discs;
                        $data['ot_enabled']=0;
                        break;
                    }
                    elseif($discs->id== 1)
                    {
                        $data['sibling_disc']=$discs;
                        $data['ot_enabled']=0;
                        break;
                    }
                }
            }
        }
        if($data['total_balance_fees'] == $data['selected_balance'])
        {$data['ot_enabled']=1;}
        else
        {
            $data['ot_enabled']=0;
        }
        $data['ot_disc']  = $this->feediscount_model->getOneTimeDiscount();
        $result           = array(
            'view' => $this->load->view('student/getcollectfeeNew', $data, true),
        );       
        $this->output->set_output(json_encode($result));
    }

    public function create_doc()
    {

        $this->form_validation->set_rules('first_title', $this->lang->line('title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('first_doc', $this->lang->line('document'), 'callback_handle_upload');

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

    public function handle_upload()
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
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }

            if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_upload', $this->lang->line('extension_not_allowed'));
                return false;
            }
            if ($file_size > $result->file_size) {
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($image_validate['upload_size'] / 1048576, 2) . " MB");
                return false;
            }

            return true;
        } else {
            $this->form_validation->set_message('handle_upload', $this->lang->line('the_file_field_is_required'));
            return false;
        }
        return true;
    }

    public function edit()
    {
        $data['title']              = 'Edit Student';
        $id                         = $this->customlib->getStudentSessionUserID();
        $data['id']                 = $id;
        $student                    = $this->student_model->get($id);
        $genderList                 = $this->customlib->getGender();
        $data['student']            = $student;
        $data['genderList']         = $genderList;
        $session                    = $this->setting_model->getCurrentSession();
        $vehroute_result            = $this->vehroute_model->get();
        $data['vehroutelist']       = $vehroute_result;
        $category                   = $this->category_model->get();
        $data['categorylist']       = $category;
        $data["bloodgroup"]         = $this->config->item('bloodgroup');
        $data['inserted_fields']    = $this->student_edit_field_model->get();
        $data['sch_setting_detail'] = $this->sch_setting_detail;

        if ($this->findSelected($data['inserted_fields'], 'firstname')) {
            $this->form_validation->set_rules('firstname', $this->lang->line('first_name'), 'trim|required|xss_clean');
        }
        if ($this->findSelected($data['inserted_fields'], 'guardian_is')) {

            $this->form_validation->set_rules('guardian_is', $this->lang->line('guardian'), 'trim|required|xss_clean');
        }
        if ($this->findSelected($data['inserted_fields'], 'dob')) {

            $this->form_validation->set_rules('dob', $this->lang->line('date_of_birth'), 'trim|required|xss_clean');
        }
        if ($this->findSelected($data['inserted_fields'], 'gender')) {

            $this->form_validation->set_rules('gender', $this->lang->line('gender'), 'trim|required|xss_clean');
        }
        if ($this->findSelected($data['inserted_fields'], 'guardian_name')) {
            $this->form_validation->set_rules('guardian_name', $this->lang->line('guardian_name'), 'trim|required|xss_clean');
        }

        if ($this->findSelected($data['inserted_fields'], 'guardian_phone')) {

            $this->form_validation->set_rules('guardian_phone', $this->lang->line('guardian_phone'), 'trim|required|xss_clean');
        }

        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_edit_handle_upload[file]');
        $this->form_validation->set_rules('father_pic', $this->lang->line('image'), 'callback_edit_handle_upload[father_pic]');
        $this->form_validation->set_rules('mother_pic', $this->lang->line('image'), 'callback_edit_handle_upload[mother_pic]');
        $this->form_validation->set_rules('guardian_pic', $this->lang->line('image'), 'callback_edit_handle_upload[guardian_pic]');

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/student/header', $data);
            $this->load->view('user/edit', $data);
            $this->load->view('layout/student/footer', $data);
        } else {

            $student_id = $id;
            $data       = array(
                'id' => $id,
            );

            $firstname = $this->input->post('firstname');
            if (isset($firstname)) {
                $data['firstname'] = $this->input->post('firstname');
            }
            $rte = $this->input->post('rte');
            if (isset($rte)) {
                $data['rte'] = $this->input->post('rte');
            }
            $pincode = $this->input->post('pincode');
            if (isset($pincode)) {
                $data['pincode'] = $this->input->post('pincode');
            }
            $cast = $this->input->post('cast');
            if (isset($cast)) {
                $data['cast'] = $this->input->post('cast');
            }
            $guardian_is = $this->input->post('guardian_is');
            if (isset($guardian_is)) {
                $data['guardian_is'] = $this->input->post('guardian_is');
            }
            $previous_school = $this->input->post('previous_school');
            if (isset($previous_school)) {
                $data['previous_school'] = $this->input->post('previous_school');
            }
            $dob = $this->input->post('dob');
            if (isset($dob)) {
                $data['dob'] = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('dob')));
            }
            $current_address = $this->input->post('current_address');
            if (isset($current_address)) {
                $data['current_address'] = $this->input->post('current_address');
            }
            $permanent_address = $this->input->post('permanent_address');
            if (isset($permanent_address)) {
                $data['permanent_address'] = $this->input->post('permanent_address');
            }
            $bank_account_no = $this->input->post('bank_account_no');
            if (isset($bank_account_no)) {
                $data['bank_account_no'] = $this->input->post('bank_account_no');
            }
            $bank_name = $this->input->post('bank_name');
            if (isset($bank_name)) {
                $data['bank_name'] = $this->input->post('bank_name');
            }
            $ifsc_code = $this->input->post('ifsc_code');
            if (isset($ifsc_code)) {
                $data['ifsc_code'] = $this->input->post('ifsc_code');
            }
            $guardian_occupation = $this->input->post('guardian_occupation');
            if (isset($guardian_occupation)) {
                $data['guardian_occupation'] = $this->input->post('guardian_occupation');
            }
            $guardian_email = $this->input->post('guardian_email');
            if (isset($guardian_email)) {
                $data['guardian_email'] = $this->input->post('guardian_email');
            }
            $gender = $this->input->post('gender');
            if (isset($gender)) {
                $data['gender'] = $this->input->post('gender');
            }
            $guardian_name = $this->input->post('guardian_name');
            if (isset($guardian_name)) {
                $data['guardian_name'] = $this->input->post('guardian_name');
            }
            $guardian_relation = $this->input->post('guardian_relation');
            if (isset($guardian_relation)) {
                $data['guardian_relation'] = $this->input->post('guardian_relation');
            }
            $guardian_phone = $this->input->post('guardian_phone');
            if (isset($guardian_phone)) {
                $data['guardian_phone'] = $this->input->post('guardian_phone');
            }
            $guardian_address = $this->input->post('guardian_address');
            if (isset($guardian_address)) {
                $data['guardian_address'] = $this->input->post('guardian_address');
            }
            $adhar_no = $this->input->post('adhar_no');
            if (isset($adhar_no)) {
                $data['adhar_no'] = $this->input->post('adhar_no');
            }
            $samagra_id = $this->input->post('samagra_id');
            if (isset($samagra_id)) {
                $data['samagra_id'] = $this->input->post('samagra_id');
            }

            $house             = $this->input->post('house');
            $blood_group       = $this->input->post('blood_group');
            $measurement_date  = $this->input->post('measure_date');
            $roll_no           = $this->input->post('roll_no');
            $lastname          = $this->input->post('lastname');
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

            if (isset($measurement_date)) {
                $data['measurement_date'] = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('measure_date')));
            }

            if (isset($house)) {
                $data['school_house_id'] = $this->input->post('house');
            }
            if (isset($blood_group)) {

                $data['blood_group'] = $this->input->post('blood_group');
            }

            if (isset($lastname)) {

                $data['lastname'] = $this->input->post('lastname');
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

                $data['admission_date'] = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('admission_date')));
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

            $this->student_model->add($data);

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

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('user/user/edit');
        }
    }

    public function findSelected($inserted_fields, $find)
    {
        foreach ($inserted_fields as $inserted_key => $inserted_value) {
            if ($find == $inserted_value->name && $inserted_value->status) {
                return true;
            }
        }
        return false;
    }

    public function edit_handle_upload($value, $field_name)
    {

        $image_validate = $this->config->item('image_validate');

        if (isset($_FILES[$field_name]) && !empty($_FILES[$field_name]['name'])) {

            $file_type         = $_FILES[$field_name]['type'];
            $file_size         = $_FILES[$field_name]["size"];
            $file_name         = $_FILES[$field_name]["name"];
            $allowed_extension = $image_validate['allowed_extension'];
            $ext               = pathinfo($file_name, PATHINFO_EXTENSION);
            $allowed_mime_type = $image_validate['allowed_mime_type'];
            if ($files = @getimagesize($_FILES[$field_name]['tmp_name'])) {

                if (!in_array($files['mime'], $allowed_mime_type)) {
                    $this->form_validation->set_message('edit_handle_upload', 'File Type Not Allowed');
                    return false;
                }

                if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('edit_handle_upload', 'Extension Not Allowed');
                    return false;
                }
                if ($file_size > $image_validate['upload_size']) {
                    $this->form_validation->set_message('edit_handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($image_validate['upload_size'] / 1048576, 2) . " MB");
                    return false;
                }
            } else {
                $this->form_validation->set_message('edit_handle_upload', "File Type / Extension Error Uploading  Image");
                return false;
            }

            return true;
        }
        return true;
    }

    public function printFeesByName()
    {
        $data                   = array('payment' => "0");
        $record                 = $this->input->post('data');
        $invoice_id             = $this->input->post('main_invoice');
        $data['invo_no'] = $sub_invoice_id = $this->input->post('sub_invoice');
        $data['student_session_id'] = $student_session_id             = $this->input->post('student_session_id');
        $fee_master_id                  = $this->input->post('fee_master_id');
        $fee_groups_feetype_id          = $this->input->post('fee_groups_feetype_id');
        $data['paidamt']                = $this->input->post('paidamt');
        $data['total_amt']              = $this->input->post('total_amt');
        $data['total_paid']             = $this->input->post('total_paid');
        $data['total_balance']          = $this->input->post('total_balance');
        $data['discount_amt']           = $this->input->post('discount_amt');
        $data['payment_id']             = $this->input->post('payment_id');
        $setting_result         = $this->setting_model->get();
        $data['settinglist']    = $setting_result[0];

        $student                = $this->studentsession_model->searchStudentsBySession($student_session_id);
        $fee_record                     = $this->studentfeemaster_model->getFeeByInvoicereturn($invoice_id, $sub_invoice_id, $student_session_id);
        $data['fees_array']             = $this->studentfeemaster_model->getStudentFees($student_session_id);
        $data['previousfees']           = $this->studentfeemaster_model->getPreviousStudentFees($student_session_id);
        $data['amount_details']         = $this->studentfeemaster_model->getamountdetails($sub_invoice_id);
        $data['student']        = $student;
        $data['sub_invoice_id'] = $sub_invoice_id;
        $data['feeList']        = $fee_record;
        $data['sch_setting']    = $this->sch_setting_detail;
        // $remain_amount_object           = $this->getStuFeetypeBalance($fee_groups_feetype_id, $fee_master_id);
        // $data['remain_amount']          = json_decode($remain_amount_object)->balance;
        $data['session']                = $this->setting_model->getCurrentSession();

        $this->load->view('print/printFeesByNameNew', $data);
    }

    public function getStuFeetypeBalance($fee_groups_feetype_id, $student_fees_master_id)
    {
        $data                           = array();
        $data['fee_groups_feetype_id']  = $fee_groups_feetype_id;
        $data['student_fees_master_id'] = $student_fees_master_id;
        $result                         = $this->studentfeemaster_model->studentDeposit($data);
        $amount_balance                 = 0;
        $amount                         = 0;
        $amount_fine                    = 0;
        $amount_discount                = 0;
        $fine_amount                    = 0;
        $fee_fine_amount                = 0;
        $due_amt                        = $result->amount;
        if (strtotime($result->due_date) < strtotime(date('Y-m-d'))) {
            $fee_fine_amount = $result->fine_amount;
        }

        if ($result->is_system) {
            $due_amt = $result->student_fees_master_amount;
        }

        $amount_detail = json_decode($result->amount_detail);
        if (is_object($amount_detail)) {

            foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                $amount          = $amount + $amount_detail_value->amount;
                $amount_discount = $amount_discount + $amount_detail_value->amount_discount;
                $amount_fine     = $amount_fine + $amount_detail_value->amount_fine;
            }
        }

        $amount_balance = $due_amt - ($amount + $amount_discount);
        $fine_amount    = abs($amount_fine - $fee_fine_amount);
        $array          = array('status' => 'success', 'error' => '', 'balance' => $amount_balance, 'fine_amount' => $fine_amount);
        return json_encode($array);
    }
    public function addfeegrp()
    {
        $staff_record = $this->session->userdata('admin');
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('row_counter[]', $this->lang->line('fees_list'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('collected_date', $this->lang->line('date'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('valid_amount', $this->lang->line('amount'), 'required|callback_greater_than_or[' . $this->input->post('valid_amount') . ']|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'row_counter'    => form_error('row_counter'),
                'collected_date' => form_error('collected_date'),
                'valid_amount' => form_error('valid_amount'),
            );
            $array = array('status' => 0, 'error' => $data);
            echo json_encode($array);
        } else {
            $post_array = $this->input->post();
            $post_array_json = json_encode($post_array);
            $array_data = array(
                'session_id' => $this->setting_model->getCurrentSession(),
                'student_session_id' => $this->input->post('student_session_id'),
                'amount' => $this->input->post('paid_amount'),
                'trn_data' => $post_array_json,
                'session_data' => json_encode($this->session->userdata()),
                'trn_status' => 'pending',
                'source' => 'parent',
            );
            $result=$this->studentfee_model->add_online_cart($array_data);
            if(!empty($result))
            {
                //$this->addfeegrp_submit($result['hash_code']);
                //$this->addfeegrp_submit($post_array);
                //$url->redirect('onlineadmission/worldline/'.$result['hash_code'], 'refresh');
                $array = array('status' => 1, 'error' => '', 'hash_code' => $result['hash_code'] );
                echo json_encode($array);
            }
        }
    }
    public function addfeegrp_submit($hash_code)
    {
        $result=$this->onlinestudent_model->get_online_record($hash_code);
        if(!empty($result))
        {
            $post_array = (array) json_decode($result['trn_data'],true);
            $online_id = $result['id'];
        }
        else
        {
            $online_id = -1;
        }
        if(!empty($post_array))
        {
            $collected_array = array();
            $student_fees_discount_id = array();
            $collected_by       = " Collected By: Online"; //. $this->customlib->getAdminSessionUserName();
            $send_to            = $post_array['guardian_phone'];
            $email              = $post_array['guardian_email'];
            $parent_app_key     = $post_array['parent_app_key'];
            $paid_amount        = $post_array['paid_amount'];
            $paid_fine_amount   = $post_array['tot_fine_amount'];
            $data['student_session_id'] = $student_session_id = $post_array['student_session_id'];
            //$student_fees_discount_id = $post_array['discount_amt'];
            $total_row = $post_array['row_counter'];
            $total_amount = 0;
            $total_discount = 0;
            $total_fine = 0;
            $net_total = 0;
            foreach ($total_row as $total_row_key => $total_row_value) {
                $post_array['student_fees_master_id_' . $total_row_value];
                $post_array['fee_groups_feetype_id_' . $total_row_value];
                $discount_amt = $post_array['amount_discount_' . $total_row_value];
                if ($discount_amt > 0) {
                    $amt = $post_array['fee_amount_' . $total_row_value] - $discount_amt;
                    //$amt = $post_array['fee_amount_' . $total_row_value];
                } else {
                    $amt = $post_array['fee_amount_' . $total_row_value];
                }
                $this->db->where('id', $post_array['student_fees_master_id_' . $total_row_value]);
                $student_fees_master = $this->db->get('student_fees_master')->row_array();
                $json_array = array(
                    'amount'          => $amt,
                    'date'            => date('Y-m-d', $this->customlib->datetostrtotime($post_array['collected_date'])),
                    'description'     => $post_array['fee_gupcollected_note'] . $collected_by,
                    'amount_discount' => $post_array['amount_discount_' . $total_row_value],
                    'amount_fine'     => $post_array['fee_groups_feetype_fine_amount_' . $total_row_value],
                    'payment_mode'    => $post_array['payment_mode_fee'],
                    //'received_by'     => $staff_record['id'],
                    'student_session_id'     => $student_fees_master['student_session_id'],
                    'discount_id'     => $post_array['disc_code_' . $total_row_value],
                    'session_id'     => $this->current_ay_session,
                );               
                if(!empty($post_array['disc_code_' . $total_row_value]))
                {
                    $current_session_id = $this->setting_model->getCurrentSession();
                    $this->db->where('student_session_id',$student_fees_master['student_session_id']);
                    $this->db->where('fees_discount_id',$post_array['disc_code_' . $total_row_value]);
                    $this->db->where('session_id',$current_session_id);
                    $disc_rows = $this->db->get('student_fees_discounts')->row_array();
                    if(empty($disc_rows))
                    {
                        $insert_array = array(
                            'student_session_id' => $student_fees_master['student_session_id'],
                            'fees_discount_id' => $post_array['disc_code_' . $total_row_value],
                            'is_active' => "Yes",
                            'session_id' => $current_session_id,
                        );
                        $student_fees_discount_id[] = $this->feediscount_model->allotdiscount($insert_array);
                    }
                    else
                    {
                        $student_fees_discount_id[] = $disc_rows['id'];
                    }
                }
                $collected_array[] = array(
                    'student_fees_master_id' => $post_array['student_fees_master_id_' . $total_row_value],
                    'fee_groups_feetype_id'  => $post_array['fee_groups_feetype_id_' . $total_row_value],
                    'amount_detail'          => $json_array,
                );
                $total_amount += $amt;
                $total_discount += $post_array['amount_discount_' . $total_row_value];
                $total_fine += $post_array['fee_groups_feetype_fine_amount_' . $total_row_value];
            }

            $deposited_fees = $this->studentfeemaster_model->fee_deposit_collections($collected_array, $student_fees_discount_id);
            $fees_record    = json_decode($deposited_fees);

            foreach ($fees_record as  $feeRow) {

                $print = $post_array['print'];
                $print_record       = array();


                $invoice_id = $feeRow->invoice_id;
                $data['invo_no'] = $sub_invoice_id = $feeRow->sub_invoice_id;
                $data['payment_id'] = $sub_invoice_id;
                $fee_groups_feetype_id = $post_array['fee_groups_feetype_id_' . $total_row_value];
                $fee_master_id         = $post_array['student_fees_master_id_' . $total_row_value];

                $setting_result        = $this->setting_model->get();
                $data['settinglist']   = $setting_result[0];
                $data['sch_setting'] = $this->sch_setting_detail;
                foreach ($total_row as $total_row_key => $total_row_value) {
                    $this->db->where('id', $post_array['student_fees_master_id_' . $total_row_value]);
                    $student_fees_master = $this->db->get('student_fees_master')->row_array();
                    $student_session_id = $student_fees_master['student_session_id'];
                    $student                = $this->studentsession_model->searchStudentsBySession($student_session_id);
                    $fee_record             = $this->studentfeemaster_model->getFeeByInvoicereturn($invoice_id, $sub_invoice_id, $student_session_id);

                    $data['fees_array']             = $this->studentfeemaster_model->getStudentFees($student_session_id);
                    $data['previousfees']             = $this->studentfeemaster_model->getPreviousStudentFees($student_session_id);
                }
                $data['student']        = $student;
                $data['sub_invoice_id'] = $sub_invoice_id;
                $data['feeList']        = $fee_record;
                $data['sch_setting']    = $this->sch_setting_detail;
                $remain_amount_object   = $this->getStuFeetypeBalance($fee_groups_feetype_id, $fee_master_id);
                $data['remain_amount']          = json_decode($remain_amount_object)->balance;

                $data['total_paid']             = $post_array['total_paid'] +  $total_amount;
                $data['total_balance']          = $post_array['total_balance'] - $total_amount - $total_discount;
                $data['discount_amt']           = $total_discount;
                $data['session']                = $this->setting_model->getCurrentSession();

                $gross_amt = $paid_amount + $total_discount - $paid_fine_amount;
                $net_total = $paid_amount;
                // $arrayUpdate = array(
                //     'id'                        => $sub_invoice_id,
                //     'receipt_date'              => date('Y-m-d', $this->customlib->datetostrtotime($post_array['collected_date'])),
                //     'gross_amount'              => $gross_amt,
                //     'discount'                  => $total_discount,
                //     'fine'                      => $paid_fine_amount,
                //     'net_amt'                   => $net_total,
                //     'student_session_id'        => $student_session_id,
                //     'session_id'                => $this->setting_model->getCurrentSession(),
                //     //'created_by'                => $this->customlib->getAdminSessionUserName(),
                //     //'created_id'                => $staff_record['id'],
                //     'note'                      => $post_array['fee_gupcollected_note'],
                //     'payment_mode'              => $post_array['payment_mode_fee'],
                // );
                $arrayUpdate = array(
                    'id'                        => $sub_invoice_id,
                    'receipt_date'              => date('Y-m-d', strtotime($post_array['collected_date'])),
                    'gross_amount'              => $gross_amt,
                    'discount'                  => $total_discount,
                    'fine'                      => $paid_fine_amount,
                    'net_amt'                   => $net_total,
                    'total_balance'             => 0,
                    'prev_balance'              => 0,                 
                    'student_session_id'        => $student_session_id,
                    'session_id'                => $student['session_id'],
                    'created_by'                => $this->customlib->getAdminSessionUserName(),
                    //'created_id'                => $staff_record['id'],
                    'note'                      => $post_array['fee_gupcollected_note'],
                    'payment_mode'              => $post_array['payment_mode_fee'],
                    //'chequeid'                  => $chequeid,
                );                
                $this->studentfeemaster_model->update_receipt($arrayUpdate,$this->current_ay_session);
                $st_name = strtoupper($data['student']['firstname']." ".$data['student']['middlename']." ".$data['student']['lastname']);
                $class_div = $data['student']['code']."-".$data['student']['section'];
                $mobno = $this->staff_model->get_class_teacher($student_session_id);
                if($mobno)
                {
                    $data_msg1 = array(
                        "mobno" => $mobno,
                        //"mobno" => "9605252637",
                        "name" => $st_name,
                        "class" => $class_div,
                        "amount" => $net_total,
                        "rec_no" => $sub_invoice_id,
                        "rec_date" => date('d-m-Y', $this->customlib->datetostrtotime($post_array['collected_date']))
                    );
                    //$this->wati_model->send_receipt_to_class_teacher($data_msg1);                
                }
                if($student['mobileno']!='')
                {
                    $data_msg = array(
                       "mobno" => $student['mobileno'],
                        //"mobno" => "9605252637",
                        "name" => $st_name,
                        "class" => $class_div,
                        "amount" => $net_total,
                        "rec_no" => $sub_invoice_id,
                        "rec_date" => date('d-m-Y', $this->customlib->datetostrtotime($post_array['collected_date']))
                    );
                    //$this->wati_model->send_receipt_to_parent($data_msg);              
                }
                $data['note']=$post_array['fee_gupcollected_note'];

                $receipt                        = $this->studentfee_model->getReceipt_ay($sub_invoice_id);
                $data['receipt']                = $receipt; 
                $data['receipt_session']        = $this->session_model->get($student['session_id']); 
                if($receipt->chequeid>0)
                {$data['cheque'] = $this->feemaster_model->getCheque_only($receipt->chequeid);}
                else
                {$data['cheque'] = array();}           
                $data_rec = array(
                    "receipt_id" => $sub_invoice_id,
                    'pass_date' => date('Y-m-d'),
                    'trn_status' => 'passed',                
                );
                $this->db->where('id',$online_id);
                $this->db->update('online_transaction',$data_rec);
                $data['billing_session']        = $this->session_model->get($this->current_ay_session); 
    


                // $receipt                        = $this->studentfee_model->getReceipt($sub_invoice_id);
                // $data['receipt']                = $receipt;                
                if ($print == 1) {
                    $body           = $this->load->view('print/mail_invoice', $data, true);
                    $print_record  = $this->load->view('print/printFeesByNameNew', $data, true);
                } else {
                    $body           = $this->load->view('print/mail_invoice', $data, true);
                }
                if (!empty($email)) {
                    $this->send_mail($email, 'Fee Submission', $body);
                }
            }
            foreach ($total_row as $total_row_key => $total_row_value) {
                $mailsms_array                 = $this->feegrouptype_model->getFeeGroupByID($post_array['fee_groups_feetype_id_' . $total_row_value]);
                $mailsms_array->invoice        = json_encode($fees_record[$total_row_key]);
                $mailsms_array->contact_no     = $send_to;
                $mailsms_array->email          = $email;
                $mailsms_array->parent_app_key = $parent_app_key;
                // $this->mailsmsconf->mailsms('fee_submission', $mailsms_array);
            }
            //$this->load->view('print/printFeesByNameNew', $data);
            // $print_record  = $this->load->view('print/printFeesByNameNew', $data, true);
            // $data['invoice'] = $print_record;
            // $data['source'] = 'parent';
            // $this->load->view('print/FeesPrint', $data);
            redirect('user/user/printFeesByName_previous_id_get/'.$sub_invoice_id.'/'.$this->current_ay_session);            
        }
        else
        {
            $array = array('status' => 1, 'error' => '', 'print' => $print_record);
            echo json_encode($array);
        }
    }
    //$ids="",$recsessionid=0
    public function printFeesByName_previous_id_get($ids="",$recsessionid=0)
    {
        // ini_set ('display_errors', 1); 
        // ini_set ('display_startup_errors', 1);
        // error_reporting (E_ALL);         
        $data                           = array('payment' => "0");
        if($ids=='')
        { 
          $id                             = $this->input->post('id');
          $rec_session_id                 = $this->input->post('recsessionid');
        }
        else
        {$id=$ids;$rec_session_id=$recsessionid;}
        $receipt                        = $this->studentfee_model->getReceipt($id,$rec_session_id);
        if($receipt->chequeid>0)
        {$data['cheque'] = $this->feemaster_model->getCheque_only($receipt->chequeid);}
        else
        {$data['cheque'] = array();}           
        $data['receipt']                = $receipt;
        if($receipt->chequeid>0)
        {
            $data['cheque'] = $this->feemaster_model->getCheque_only($receipt->chequeid);
        }
        else
        {$data['cheque'] = array();}
        
        $invoice_id                     = $id;
        $data['inv_no']    = $data['invo_no']            = $sub_invoice_id = $id;
        $data['student_session_id']     = $student_session_id             = $receipt->student_session_id;
        $data['receipt_session_id']     = $receipt->session_id;
        $data['receipt_date']           = date('d-m-Y', strtotime($receipt->receipt_date));
        $data['receipt_session']        = $this->session_model->get($receipt->session_id);
        //$fee_master_id                  = $this->input->post('fee_master_id');
        //$fee_groups_feetype_id          = $this->input->post('fee_groups_feetype_id');
        $data['paidamt']                = $receipt->gross_amount;
        $data['total_amt']              = $receipt->net_amt;
        $data['total_paid']             = $this->input->post('total_paid');
        $data['total_balance']          = $receipt->total_balance;
        $data['discount_amt']           = $this->input->post('discount_amt');
        $data['payment_id']             = $id;

        $setting_result                 = $this->setting_model->get();
        $data['settinglist']            = $setting_result[0];
        $student                        = $this->studentsession_model->searchStudentsBySession($student_session_id);
        //echo "<pre>";
        //print_r($data);die();
        $data['receipt_session']        = $this->session_model->get($student['session_id']);

        
        
        $fee_record                     = $this->studentfeemaster_model->getFeeByInvoicereturn($invoice_id, $sub_invoice_id, $student_session_id);
        $data['fees_array']             = $this->studentfeemaster_model->getStudentFees($student_session_id);
        $data['previousfees']           = $this->studentfeemaster_model->getPreviousStudentFees($student_session_id);
        $data['amount_details']         = $this->studentfeemaster_model->getamountdetails($sub_invoice_id);
        $data['student']                = $student;
        $data['sub_invoice_id']         = $sub_invoice_id;
        $data['feeList']                = $fee_record;
        $data['sch_setting']            = $this->sch_setting_detail;
        //$remain_amount_object           = $this->getStuFeetypeBalance($fee_groups_feetype_id, $fee_master_id);
        //$data['remain_amount']          = json_decode($remain_amount_object)->balance;
        $data['session']                = $this->setting_model->getCurrentSession();
        if($rec_session_id > 0)
        {$data['billing_session']        = $this->session_model->get($rec_session_id);}
        else
        {$data['billing_session']        = $this->session_model->get($this->setting_model->getCurrentSession());}
        
        $data['st_session']             = $data['receipt_session']['session'];
        
        if($ids=='')
        {$this->load->view('print/printFeesByNameNew_previous', $data);}    
        else
        {
            $data['billing_session']        = $this->session_model->get($this->setting_model->getCurrentSession()); 
            $print_record  = $this->load->view('print/printFeesByNameNew_previous', $data, true);
            $data['invoice'] = $print_record;
            $data['source'] = 'parent';
            $this->load->view('print/FeesPrint', $data);            
        }
    }

    public function addfeegrp_previous()
    {
        $staff_record = $this->session->userdata('admin');
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('row_counter[]', $this->lang->line('fees_list'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('collected_date', $this->lang->line('date'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('valid_amount', $this->lang->line('amount'), 'required|callback_greater_than_or[' . $this->input->post('valid_amount') . ']|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'row_counter'    => form_error('row_counter'),
                'collected_date' => form_error('collected_date'),
                'valid_amount' => form_error('valid_amount'),
            );
            $array = array('status' => 0, 'error' => $data);
            echo json_encode($array);
        } else {
            $collected_array = array();
            $student_fees_discount_id = array();
            $collected_by    = " Collected By: " . $this->customlib->getAdminSessionUserName();

            $send_to            = $this->input->post('guardian_phone');
            $email              = "manojthannimattam@gmail.com";//$this->input->post('guardian_email');
            $parent_app_key     = $this->input->post('parent_app_key');
            $paid_amount        = $this->input->post('paid_amount');
            $paid_fine_amount   = $this->input->post('tot_fine_amount');
            $prev_paid          = $this->input->post('prev_paid');
            $session_id         = $this->input->post('session_id');
            $data['student_session_id'] = $student_session_id = $this->input->post('student_session_id');
            //$student_fees_discount_id = $this->input->post('discount_amt');
            $total_row = $this->input->post('row_counter');

            $total_amount = 0;
            $total_discount = 0;
            $total_fine = 0;
            $net_total = 0;

            foreach ($total_row as $total_row_key => $total_row_value) {

                $this->input->post('student_fees_master_id_' . $total_row_value);
                $this->input->post('fee_groups_feetype_id_' . $total_row_value);
                $discount_amt = $this->input->post('amount_discount_' . $total_row_value);
                if ($discount_amt > 0) {
                    $amt = $this->input->post('fee_amount_' . $total_row_value) - $discount_amt;
                    //$amt = $this->input->post('fee_amount_' . $total_row_value);
                } else {
                    $amt = $this->input->post('fee_amount_' . $total_row_value);
                }
                $this->db->where('id', $this->input->post('student_fees_master_id_' . $total_row_value));
                $student_fees_master = $this->db->get('student_fees_master')->row_array();

                $json_array = array(
                    'amount'          => $amt,
                    'date'            => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('collected_date'))),
                    'description'     => $this->input->post('fee_gupcollected_note') . $collected_by,
                    'amount_discount' => $this->input->post('amount_discount_' . $total_row_value),
                    'amount_fine'     => $this->input->post('fee_groups_feetype_fine_amount_' . $total_row_value),
                    'payment_mode'    => $this->input->post('payment_mode_fee'),
                    'received_by'     => $staff_record['id'],
                    'student_session_id'     => $student_fees_master['student_session_id'],
                    'discount_id'     => $this->input->post('disc_code_' . $total_row_value),
                );
                if(!empty($this->input->post('disc_code_' . $total_row_value)))
                {
                    $current_session_id = $this->setting_model->getCurrentSession();
                    $this->db->where('student_session_id',$student_fees_master['student_session_id']);
                    $this->db->where('fees_discount_id',$this->input->post('disc_code_' . $total_row_value));
                    $this->db->where('session_id',$session_id);
                    $disc_rows = $this->db->get('student_fees_discounts')->row_array();
                    if(empty($disc_rows))
                    {
                        $insert_array = array(
                            'student_session_id' => $student_fees_master['student_session_id'],
                            'fees_discount_id' => $this->input->post('disc_code_' . $total_row_value),
                            'is_active' => "Yes",
                            'session_id' => $session_id,
                        );
                        $student_fees_discount_id[] = $this->feediscount_model->allotdiscount($insert_array);
                    }
                    else
                    {
                        $student_fees_discount_id[] = $disc_rows['id'];
                    }
                }
                $collected_array[] = array(
                    'student_fees_master_id' => $this->input->post('student_fees_master_id_' . $total_row_value),
                    'fee_groups_feetype_id'  => $this->input->post('fee_groups_feetype_id_' . $total_row_value),
                    'amount_detail'          => $json_array,
                );
                $total_amount += $amt;
                $total_discount += $this->input->post('amount_discount_' . $total_row_value);
                $total_fine += $this->input->post('fee_groups_feetype_fine_amount_' . $total_row_value);
            }

            $deposited_fees = $this->studentfeemaster_model->fee_deposit_collections($collected_array, $student_fees_discount_id);
            $fees_record    = json_decode($deposited_fees);

            foreach ($fees_record as  $feeRow) {

                $print = $this->input->post('print');
                $print_record       = array();


                $invoice_id = $feeRow->invoice_id;
                $data['inv_no'] = $data['invo_no'] = $sub_invoice_id = $feeRow->sub_invoice_id;
                $data['payment_id'] = $sub_invoice_id;
                $fee_groups_feetype_id = $this->input->post('fee_groups_feetype_id_' . $total_row_value);
                $fee_master_id         = $this->input->post('student_fees_master_id_' . $total_row_value);

                $setting_result        = $this->setting_model->get();
                $data['settinglist']   = $setting_result[0];
                $data['sch_setting'] = $this->sch_setting_detail;
                foreach ($total_row as $total_row_key => $total_row_value) {
                    $this->db->where('id', $this->input->post('student_fees_master_id_' . $total_row_value));
                    $student_fees_master = $this->db->get('student_fees_master')->row_array();
                    $student_session_id = $student_fees_master['student_session_id'];
                    $student                = $this->studentsession_model->searchStudentsBySession($student_session_id);
                    $fee_record             = $this->studentfeemaster_model->getFeeByInvoicereturn($invoice_id, $sub_invoice_id, $student_session_id);

                    $data['fees_array']             = $this->studentfeemaster_model->getStudentFees($student_session_id);
                    $data['previousfees']             = $this->studentfeemaster_model->getPreviousStudentFees($student_session_id);
                }
                
                $data['receipt_session']        = $this->session_model->get($session_id);
                $data['receipt_session_id']        = $session_id;

                $data['student']        = $student;
                $data['sub_invoice_id'] = $sub_invoice_id;
                $data['feeList']        = $fee_record;
                $data['sch_setting']    = $this->sch_setting_detail;
                $data['session']                = $this->setting_model->getCurrentSession();
                
                $total_balance          = $this->input->post('total_balance') - $total_amount - $total_discount;
                $gross_amt = $paid_amount + $total_discount - $paid_fine_amount;
                $net_total = $paid_amount;
                $data['paidamt']                = $gross_amt;
                $data['total_amt']              = $net_total;
                $data['total_paid']             = $this->input->post('total_paid');
                $data['total_balance']          = $total_balance;
                $data['discount_amt']           = $total_discount;
                $arrayUpdate = array(
                    'id'                        => $sub_invoice_id,
                    'receipt_date'              => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('collected_date'))),
                    'gross_amount'              => $gross_amt,
                    'discount'                  => $total_discount,
                    'fine'                      => $paid_fine_amount,
                    'net_amt'                   => $net_total,
                    'total_balance'             => $total_balance,
                    'prev_balance'              => $prev_paid,
                    'student_session_id'        => $student_session_id,
                    'session_id'                => $session_id,
                    'created_by'                => $this->customlib->getAdminSessionUserName(),
                    'created_id'                => $staff_record['id'],
                    'note'                      => $this->input->post('fee_gupcollected_note'),
                    'payment_mode'              => $this->input->post('payment_mode_fee'),
                );

                $this->studentfeemaster_model->update_receipt($arrayUpdate);
                $st_session = $this->session_model->get($session_id);
                $data['st_session'] = $st_session['session'];
                $mobno = $this->staff_model->get_class_teacher($data['student_session_id']);
                $st_name = strtoupper($data['student']['firstname']." ".$data['student']['middlename']." ".$data['student']['lastname']);
                $class_div = $data['student']['code']."-".$data['student']['section']."[".$st_session['session']."]";
                if($mobno)
                {
                    $data_msg1 = array(
                        "mobno" => $mobno,
                        "name" => $st_name,
                        "class" => $class_div,
                        "amount" => $net_total,
                        "rec_no" => $sub_invoice_id,
                        "rec_date" => date('d-m-Y', $this->customlib->datetostrtotime($this->input->post('collected_date')))
                    );
                    $this->wati_model->send_receipt_to_class_teacher($data_msg1);                
                }

                if($student['mobileno']!='')
                {
                    $data_msg = array(
                        "mobno" => $student['mobileno'],
                        "name" => $st_name,
                        "class" => $class_div,
                        "amount" => $net_total,
                        "rec_no" => $sub_invoice_id,
                        "rec_date" => date('d-m-Y', $this->customlib->datetostrtotime($this->input->post('collected_date')))
                    );
                    $this->wati_model->send_receipt_to_parent($data_msg);              
                }
                $receipt                        = $this->studentfee_model->getReceipt($sub_invoice_id);
                $data['receipt']                = $receipt;                
                $data['id'] = $sub_invoice_id;
                if ($print == 1) {
                    $body           = $this->load->view('print/mail_invoice', $data, true);
                    $print_record  = $this->load->view('print/printFeesByNameNew_previous', $data, true);
                } else {
                    $body           = $this->load->view('print/mail_invoice', $data, true);
                }
                if (!empty($email)) {
                    $this->send_mail($email, 'Fee Submission', $body);
                }
            }
            foreach ($total_row as $total_row_key => $total_row_value) {
                $mailsms_array                 = $this->feegrouptype_model->getFeeGroupByID($this->input->post('fee_groups_feetype_id_' . $total_row_value));
                $mailsms_array->invoice        = json_encode($fees_record[$total_row_key]);
                $mailsms_array->contact_no     = $send_to;
                $mailsms_array->email          = $email;
                $mailsms_array->parent_app_key = $parent_app_key;
                // $this->mailsmsconf->mailsms('fee_submission', $mailsms_array);
            }
            $array = array('status' => 1, 'error' => '', 'print' => $print_record);
            echo json_encode($array);
        }
    }
    public function printFeesByName_previous_id()
    {
        $data                           = array('payment' => "0");
        $id                             = $this->input->post('id');
        $receipt                        = $this->studentfee_model->getReceipt($id);
        $data['receipt']                = $receipt;
        $invoice_id                     = $id;
        $data['inv_no']    = $data['invo_no']            = $sub_invoice_id = $id;
        $data['student_session_id']     = $student_session_id             = $receipt->student_session_id;
        $data['receipt_session_id']     = $receipt->session_id;
        //$fee_master_id                  = $this->input->post('fee_master_id');
        //$fee_groups_feetype_id          = $this->input->post('fee_groups_feetype_id');
        $data['paidamt']                = $receipt->gross_amount;
        $data['total_amt']              = $receipt->net_amt;
        $data['total_paid']             = $this->input->post('total_paid');
        $data['total_balance']          = $receipt->total_balance;
        $data['discount_amt']           = $this->input->post('discount_amt');
        $data['payment_id']             = $id;

        $setting_result                 = $this->setting_model->get();
        $data['settinglist']            = $setting_result[0];
        $student                        = $this->studentsession_model->searchStudentsBySession($student_session_id);
        
        $data['receipt_session']        = $this->session_model->get($receipt->session_id);
        $fee_record                     = $this->studentfeemaster_model->getFeeByInvoicereturn($invoice_id, $sub_invoice_id, $student_session_id);
        $data['fees_array']             = $this->studentfeemaster_model->getStudentFees($student_session_id);
        $data['previousfees']           = $this->studentfeemaster_model->getPreviousStudentFees($student_session_id);
        $data['amount_details']         = $this->studentfeemaster_model->getamountdetails($sub_invoice_id);
        $data['student']                = $student;
        $data['sub_invoice_id']         = $sub_invoice_id;
        $data['feeList']                = $fee_record;
        $data['sch_setting']            = $this->sch_setting_detail;
        //$remain_amount_object           = $this->getStuFeetypeBalance($fee_groups_feetype_id, $fee_master_id);
        //$data['remain_amount']          = json_decode($remain_amount_object)->balance;
        $data['session']                = $this->setting_model->getCurrentSession();
        $data['st_session']             = $data['receipt_session']['session'];
        $this->load->view('print/printFeesByNameNew_previous', $data);
    }
    public function greater_than_or($amount)
    {
        if (!is_numeric($amount) || $amount < 0) {
            $this->form_validation->set_message('greater_than_or', 'Amount Must Be greater than Zero');
            return false;
        }
        return true;
    }
    public function send_mail($toemail, $subject, $body, $cc = "", $FILES = array())
    {
        $sch_setting = $this->setting_model->get();
        $school_name = "SNG Central School(CBSE)";//$sch_setting[0]['name'];
        $school_email = $sch_setting[0]['email'];
        $mail_config = $this->emailconfig_model->getActiveEmail();
        if ($mail_config->email_type == "smtp") {
            $from_mail = $mail_config->smtp_username;
            $smtp_host = $mail_config->smtp_server;
            $smtp_port = $mail_config->smtp_port;
            $smtp_user = $mail_config->smtp_username;
            $smtp_pass = $mail_config->smtp_password;
            $config = array(
                'protocol' => "SMTP",
                'smtp_host' => $smtp_host,
                'smtp_port' => $smtp_port,
                'smtp_user' => $smtp_user,
                'smtp_pass' => $smtp_pass,
                'mailtype' => 'html',
                'crlf' => "\r\n",
                'newline' => "\r\n",
                'validate' => False,
                'charset' => "utf-8",
                'wordwrap' => TRUE,
            );

            $this->load->library('email');
            $this->email->initialize($config);
            $this->email->from($from_mail, $school_name);
            $this->email->to($toemail);
            if ($cc != "") {
                $this->email->cc($cc);
            }
            $this->email->subject($subject);
            $this->email->message($body);
            if (!empty($FILES)) {
                if (isset($_FILES['files']) && !empty($_FILES['files'])) {
                    $no_files = count($_FILES["files"]['name']);
                    for ($i = 0; $i < $no_files; $i++) {
                        if ($_FILES["files"]["error"][$i] > 0) {
                            echo "Error: " . $_FILES["files"]["error"][$i] . "<br>";
                        } else {
                            $file_tmp = $_FILES["files"]["tmp_name"][$i];
                            $file_name = $_FILES["files"]["name"][$i];
                            // $mail->AddAttachment($file_tmp, $file_name);
                            $this->CI->email->attach($file_name, 'attachment', $file_tmp);
                        }
                    }
                }
            }
            $abc = $this->email->send();
            if (!$abc) {
                echo $this->email->print_debugger();
            } else {
                return true;
            }
        }
    }
    public function payment_failure()
    {
        $this->load->view('onlineadmission/worldline/payment_failed');
    }
    public function print_session()
    {
        $this->load->library('session');
        echo "<pre>";
        print_r($this->session->userdata());
        $sess = json_encode($this->session->userdata());
        echo "<br>---------";
        print_r($sess);
        $this->auth->logout();
        echo "<br>---------";
        $newsess= (array) json_decode($sess);
        $this->session->set_userdata('admin',"yes");
        $this->session->set_userdata('student',(array)$newsess['student']);
        $this->session->set_userdata('current_class',(array)$newsess['current_class']);
        $this->session->set_userdata('top_menu',$newsess['top_menu']);
        echo "<br>---------";
        print_r($this->session->userdata());

    }
}
