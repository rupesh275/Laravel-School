<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Site extends Public_Controller
{
    public $pagination;
    public $agent;
    public $Notification_model;
    public $Setting_model;
    public $Notificationsetting_model;
    public $customlib;
    public $student_login_prefix;
    public $parent_login_prefix;
    public $teacher_login_prefix;
    public $librarian_login_prefix;
    public $accountant_login_prefix;
    public $role;
    public $setting_model;
    public $student_model;
    public $teacher_model;
    public $studentfeemaster_model;
    public $librarian_model;
    public $accountant_model;
    public $smsconfig_model;
    public $smsgateway;
    public $qdmailer;
    public $adler32;
    public $aes;
    public $session_model;
    public $wati_model;
    public $class_model;
    public $staff_model;
    public $section_model;
    public $classsection_model;
    public $category_model;
    public $feemaster_model;
    public $feecategory_model;
    public $feetype_model;
    public $studentfee_model;
    public $stuattendence_model;
    public $attendencetype_model;
    public $studentsession_model;
    public $language_model;
    public $admin_model;
    public $langpharses_model;
    public $subject_model;
    public $teachersubject_model;
    public $exam_model;
    public $mark_model;
    public $examschedule_model;
    public $examresult_model;
    public $expense_model;
    public $expensehead_model;
    public $studenttransportfee_model;
    public $book_model;
    public $grade_model;
    public $timetable_model;
    public $hostel_model;
    public $route_model;
    public $content_model;
    public $user_model;
    public $notification_model;
    public $paymentsetting_model;
    public $payroll_model;
    public $roomtype_model;
    public $department_model;
    public $designation_model;
    public $hostelroom_model;
    public $vehicle_model;
    public $vehroute_model;
    public $homework_model;
    public $librarymanagement_model;
    public $librarymember_model;
    public $bookissue_model;
    public $feegroup_model;
    public $feegrouptype_model;
    public $feesessiongroup_model;
    public $feediscount_model;
    public $emailconfig_model;
    public $income_model;
    public $incomehead_model;
    public $itemcategory_model;
    public $schoolhouse_model;
    public $item_model;
    public $messages_model;
    public $itemstore_model;
    public $itemsupplier_model;
    public $notificationsetting_model;
    public $itemstock_model;
    public $itemissue_model;
    public $datatables;
    public $userlog_model;
    public $cms_program_model;
    public $cms_menu_model;
    public $cms_media_model;
    public $cms_page_model;
    public $cms_menuitems_model;
    public $cms_page_content_model;
    public $role_model;
    public $calendar_model;
    public $userpermission_model;
    public $staffroles_model;
    public $staffattendancemodel;
    public $rolepermission_model;
    public $Certificate_model;
    public $classteacher_model;
    public $Generatecertificate_model;
    public $Student_id_card_model;
    public $timeline_model;
    public $Generateidcard_model;
    public $Module_model;
    public $subjectgroup_model;
    public $studentsubjectgroup_model;
    public $subjecttimetable_model;
    public $studentsubjectattendence_model;
    public $audit_model;
    public $Chat_model;
    public $apply_leave_model;
    public $disable_reason_model;
    public $question_model;
    public $leavetypes_model;
    public $alumni_model;
    public $lessonplan_model;
    public $syllabus_model;
    public $Staffidcard_model;
    public $Generatestaffidcard_model;
    public $auth;
    public $module_lib;
    public $pushnotification;
    public $jsonlib;
    public $customfield_model;
    public $onlinestudent_model;
    public $houselist_model;
    public $onlineexam_model;
    public $onlineexamquestion_model;
    public $onlineexamresult_model;
    public $examstudent_model;
    public $admitcard_model;
    public $marksheet_model;
    public $chatuser_model;
    public $examgroupstudent_model;
    public $examgroup_model;
    public $batchsubject_model;
    public $filetype_model;
    public $school_details;
    public $enc_lib;
    public $captcha_model;
    public $captchalib;
    public $mail_config;
    public $mailer;
    public $mailgateway;
    public $mailsmsconf;
    public $setting;
    public $sch_setting;
    public function __construct()
    {
        parent::__construct();
        /*
        $this->check_installation();
        if ($this->config->item('installed') == true) {
            $this->db->reconnect();
        }
        */
        $this->load->model("staff_model");
        $this->load->model('onlinestudent_model');        
        $this->load->library('session');
        $this->load->library('Auth');
        $this->load->library('Enc_lib');
        $this->load->library('customlib');
        $this->load->library('captchalib');
        $this->load->library('mailsmsconf');
        $this->load->library('mailer');
        $this->load->config('ci-blog');
        $this->mailer;
        $this->sch_setting =$this->setting =  $this->setting_model->getSetting();
    }

    private function check_installation()
    {
        if ($this->uri->segment(1) !== 'install') {
            $this->load->config('migration');
            if ($this->config->item('installed') == false && $this->config->item('migration_enabled') == false) {
                redirect(base_url() . 'install/start');
            } else {
                if (is_dir(APPPATH . 'controllers/install')) {
                    echo '<h3>Delete the install folder from application/controllers/install</h3>';
                    die;
                }
            }
        }
    }

    public function login()
    {
        
        $app_name = $this->setting_model->get();
        $app_name = $app_name[0]['name'];

        if ($this->auth->logged_in()) {
            $this->auth->is_logged_in(true);
        }
        $data          = array();
        $data['title'] = 'Login';
        $school        = $this->setting_model->get();
        $data['name'] = $app_name;
        $notice_content     = $this->config->item('ci_front_notice_content');
        $notices            = $this->cms_program_model->getByCategory($notice_content, array('start' => 0, 'limit' => 5));
        $data['notice']     = $notices;
        $data['school']     = $school[0];
        $is_captcha         = $this->captchalib->is_captcha('login');
        $data["is_captcha"] = $is_captcha;
        if ($this->captchalib->is_captcha('login')) {
            $this->form_validation->set_rules('captcha', $this->lang->line('captcha'), 'trim|required|callback_check_captcha');
        }
        $this->form_validation->set_rules('username', $this->lang->line('username'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', $this->lang->line('password'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $captcha =  $this->captchalib->generate_captcha();
            $data['captcha_image'] = isset($captcha['image'])?$captcha['image']:"";
            $data['name']          = $app_name; 
            $this->load->view('admin/login', $data);
        } else {
            $login_post = array(
                'email'    => $this->input->post('username'),
                'password' => $this->input->post('password'),
            );
            // echo "<pre>";
            
            $data['captcha_image'] = isset($this->captchalib->generate_captcha()['image']);
            $setting_result        = $this->setting_model->get();
            $result                = $this->staff_model->checkLogin($login_post);

            if (!empty($result->language_id)) {
                $lang_array = array('lang_id' => $result->language_id, 'language' => $result->language);
            } else {
                $lang_array = array('lang_id' => $setting_result[0]['lang_id'], 'language' => $setting_result[0]['language']);
            }

            if ($result) {
                if ($result->is_active) {
                    if ($result->surname != "") {
                        $logusername = $result->name . " " . $result->surname;
                    } else {
                        $logusername = $result->name;
                    }
                    $staff_rec              = $this->staff_model->getStaffRecord($result->id,"designation");
                    $session_data = array(
                        'id'              => $result->id,
                        'username'        => $logusername,
                        'email'           => $result->email,
                        'roles'           => $result->roles,
                        'date_format'     => $setting_result[0]['date_format'],
                        'currency_symbol' => $setting_result[0]['currency_symbol'],
                        'currency_place'  => $setting_result[0]['currency_place'],
                        'start_month'     => $setting_result[0]['start_month'],
                        'start_week'      => date("w", strtotime($setting_result[0]['start_week'])),
                        'school_name'     => $setting_result[0]['name'],
                        'timezone'        => $setting_result[0]['timezone'],
                        'sch_name'        => $setting_result[0]['name'],
                        'language'        => $lang_array,
                        'is_rtl'          => $setting_result[0]['is_rtl'],
                        'theme'           => $setting_result[0]['theme'],
                        'gender'          => $result->gender,
                        'designation'     => $staff_rec['designation'],
                    );
                    
                     if($session_data['is_rtl'] == "disabled"){
                    $language_result1 = $this->language_model->get($lang_array['lang_id']);
                    if ($this->customlib->get_rtl_languages($language_result1['short_code'])) {
                        $session_data['is_rtl'] = 'enabled';
                    } else {
                        $session_data['is_rtl'] = 'disabled';
                    }
                     }
                    $this->session->set_userdata('admin', $session_data);

                    $role      = $this->customlib->getStaffRole();
                    $role_name = json_decode($role)->name;
                    $this->customlib->setUserLog($this->input->post('username'), $role_name);
                    
                    if (isset($_SESSION['redirect_to'])) {
                        redirect($_SESSION['redirect_to']);
                    } else {
                        redirect('admin/admin/dashboard_new');
                    }

                } else {
                    $data['name']          = $app_name;
                    $data['error_message'] = $this->lang->line('your_account_is_disabled_please_contact_to_administrator');

                    $this->load->view('admin/login', $data);
                }
            } else {
                $data['name']          = $app_name;
                $data['error_message'] = $this->lang->line('invalid_username_or_password');
                $this->load->view('admin/login', $data);
            }
        }
    }

    public function logout()
    {
        $admin_session   = $this->session->userdata('admin');
        $student_session = $this->session->userdata('student');
        $this->auth->logout();
        if ($admin_session) {
            redirect('site/login');
        } else if ($student_session) {
            redirect('site/parentlogin');
        } else {
            redirect('site/parentlogin');
        }
    }

    public function forgotpassword()
    {

        $app_name     = $this->setting_model->get();
        $data['name'] = $app_name[0]['name'];
        $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|valid_email|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $this->load->view('admin/forgotpassword', $data);
        } else {
            $email = $this->input->post('email');

            $result = $this->staff_model->getByEmail($email);

            if ($result && $result->email != "") {
                if ($result->is_active == '1') {
                    $verification_code = $this->enc_lib->encrypt(uniqid(mt_rand()));
                    $update_record     = array('id' => $result->id, 'verification_code' => $verification_code);
                    $this->staff_model->add($update_record);
                    $name           = $result->name;
                    $resetPassLink  = site_url('admin/resetpassword') . "/" . $verification_code;
                    $sender_details = array('resetPassLink' => $resetPassLink, 'name' => $name, 'username' => $result->email, 'email' => $email);
                    $this->mailsmsconf->mailsms('forgot_password', $sender_details);
                    $this->session->set_flashdata('message', $this->lang->line('please_check_your_email_to_recover_your_password'));
                } else {
                    $this->session->set_flashdata('disable_message', $this->lang->line('your_account_is_disabled_please_contact_to_administrator'));
                }

                redirect('site/login', 'refresh');
            } else {

                $data = array(
                    'error_message' => $this->lang->line('incorrect') . " " . $this->lang->line('email'),
                );
            }
            $this->load->view('admin/forgotpassword', $data);
        }
    }

    //reset password - final step for forgotten password
    public function admin_resetpassword($verification_code = null)
    {
        $app_name     = $this->setting_model->get();
        $data['name'] = $app_name[0]['name'];
        if (!$verification_code) {
            show_404();
        }

        $user = $this->staff_model->getByVerificationCode($verification_code);

        if ($user) {
            //if the code is valid then display the password reset form
            $this->form_validation->set_rules('password', $this->lang->line('password'), 'required');
            $this->form_validation->set_rules('confirm_password', $this->lang->line('confirm_password'), 'required|matches[password]');
            if ($this->form_validation->run() == false) {

                $data['verification_code'] = $verification_code;
                //render
                $this->load->view('admin/admin_resetpassword', $data);
            } else {

                // finally change the password
                $password      = $this->input->post('password');
                $update_record = array(
                    'id'                => $user->id,
                    'password'          => $this->enc_lib->passHashEnc($password),
                    'verification_code' => "",
                );

                $change = $this->staff_model->update($update_record);
                if ($change) {
                    //if the password was successfully changed
                    $this->session->set_flashdata('message', $this->lang->line("password_reset_successfully"));
                    redirect('site/login', 'refresh');
                } else {
                    $this->session->set_flashdata('message', $this->lang->line("something_went_wrong"));
                    redirect('admin_resetpassword/' . $verification_code, 'refresh');
                }
            }
        } else {
            //if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('message', $this->lang->line('invalid_link'));
            redirect("site/forgotpassword", 'refresh');
        }
    }

    //reset password - final step for forgotten password
    public function resetpassword($role = null, $verification_code = null)
    {
        $app_name     = $this->setting_model->get();
        $data['name'] = $app_name[0]['name'];
        if (!$role || !$verification_code) {
            show_404();
        }

        $user = $this->user_model->getUserByCodeUsertype($role, $verification_code);

        if ($user) {
            //if the code is valid then display the password reset form
            $this->form_validation->set_rules('password', $this->lang->line('password'), 'required');
            $this->form_validation->set_rules('confirm_password', $this->lang->line('confirm_password'), 'required|matches[password]');
            if ($this->form_validation->run() == false) {

                $data['role']              = $role;
                $data['verification_code'] = $verification_code;
                //render
                $this->load->view('resetpassword', $data);
            } else {

                // finally change the password

                $update_record = array(
                    'id'                => $user->user_tbl_id,
                    'password'          => $this->input->post('password'),
                    'verification_code' => "",
                );

                $change = $this->user_model->saveNewPass($update_record);
                if ($change) {
                    //if the password was successfully changed
                    $this->session->set_flashdata('message', $this->lang->line('password_reset_successfully'));
                    redirect('site/userlogin', 'refresh');
                } else {
                    $this->session->set_flashdata('message', $this->lang->line("something_went_wrong"));
                    redirect('user/resetpassword/' . $role . '/' . $verification_code, 'refresh');
                }
            }
        } else {
            //if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('message', $this->lang->line('invalid_link'));
            redirect("site/ufpassword", 'refresh');
        }
    }
    public function ufpassword()
    {
        $app_name     = $this->setting_model->get();
        $data['name'] = $app_name[0]['name'];
        $this->form_validation->set_rules('username', $this->lang->line('email'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('user[]', $this->lang->line('user_type'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {

            $this->load->view('ufpassword', $data);
        } else {
            $email    = $this->input->post('username');
            $usertype = $this->input->post('user[]');
            $result   = $this->user_model->forgotPassword($usertype[0], $email);
            if ($result && $result->email != "") {

                $verification_code = $this->enc_lib->encrypt(uniqid(mt_rand()));
                $update_record     = array('id' => $result->user_tbl_id, 'verification_code' => $verification_code);
                $this->user_model->updateVerCode($update_record);

                if ($usertype[0] == "student") {
                    $name     = $this->customlib->getFullName($result->firstname, $result->middlename, $result->lastname, $this->sch_setting->middlename, $this->sch_setting->lastname);
                    $username = $result->username;
                } else {
                    $name     = $result->guardian_name;
                    $username = $result->username;
                }

                $resetPassLink  = site_url('user/resetpassword') . '/' . $usertype[0] . "/" . $verification_code;
                $sender_details = array('resetPassLink' => $resetPassLink, 'name' => $name, 'username' => $username, 'email' => $email);
                $this->mailsmsconf->mailsms('forgot_password', $sender_details);
                $this->session->set_flashdata('message', $this->lang->line("please_check_your_email_to_recover_your_password"));
                redirect('site/userlogin', 'refresh');
            } else {
                $data = array(
                    'name'          => $app_name[0]['name'],
                    'error_message' => $this->lang->line('invalid_email_or_user_type'),
                );
            }
            $this->load->view('ufpassword', $data);
        }
    }

    public function userlogin()
    {
        if ($this->auth->user_logged_in()) {
            $this->auth->user_redirect();
        }
        $data               = array();
        $data['title']      = 'Login';
        $school             = $this->setting_model->get();
        $data['name']       = $school[0]['name'];
        $notice_content     = $this->config->item('ci_front_notice_content');
        $notices            = $this->cms_program_model->getByCategory($notice_content, array('start' => 0, 'limit' => 5));
        $data['notice']     = $notices;
        $data['school']     = $school[0];
        $is_captcha         = $this->captchalib->is_captcha('userlogin');
        $data["is_captcha"] = $is_captcha;
        if ($is_captcha) {
            $this->form_validation->set_rules('captcha', $this->lang->line('captcha'), 'trim|required|callback_check_captcha');
        }
        $this->form_validation->set_rules('username', $this->lang->line('username'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', $this->lang->line('password'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $data['captcha_image'] = $this->captchalib->generate_captcha()['image'];
            $data['user_type']="Parent";
            $this->load->view('userlogin', $data);
        } else {
            $login_post = array(
                'username' => $this->input->post('username'),
                'password' => $this->input->post('password'),
            );
            $data['captcha_image'] = isset($this->captchalib->generate_captcha()['image']);
            $login_details         = $this->user_model->checkLogin($login_post);

            if (isset($login_details) && !empty($login_details)) {
                $user = $login_details[0];
                if ($user->is_active == "yes") {
                    if ($user->role == "student") {
                        $result = $this->user_model->read_user_information($user->id);
                    } else if ($user->role == "parent") {
                        $result = $this->user_model->checkLoginParent($login_post);
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
                            $username = $result[0]->guardian_name;
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
                        redirect('user/user/choose');
                    } else {
                        $data['user_type']="Student";
                        $data['error_message'] = 'Account Suspended';
                        $this->load->view('userlogin', $data);
                    }
                } else {
                    $data['user_type']="Student";
                    $data['error_message'] = $this->lang->line('your_account_is_disabled_please_contact_to_administrator');
                    $this->load->view('userlogin', $data);
                }
            } else {
                $data['user_type']="Student";
                $data['error_message'] = $this->lang->line('invalid_username_or_password');
                $this->load->view('userlogin', $data);
            }
        }
    }
    public function parentlogintest()
    {
        if ($this->auth->user_logged_in()) {
            $this->auth->user_redirect();
        }
        $data               = array();
        $data['title']      = 'Login';
        $school             = $this->setting_model->get();
        $data['name']       = $school[0]['name'];
        $notice_content     = $this->config->item('ci_front_notice_content');
        $notices            = $this->cms_program_model->getByCategory($notice_content, array('start' => 0, 'limit' => 5));
        $data['notice']     = $notices;
        $data['school']     = $school[0];
        $is_captcha         = $this->captchalib->is_captcha('userlogin');
        $data["is_captcha"] = $is_captcha;
        if ($is_captcha) {
            $this->form_validation->set_rules('captcha', $this->lang->line('captcha'), 'trim|required|callback_check_captcha');
        }
        $this->form_validation->set_rules('username', $this->lang->line('username'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', $this->lang->line('password'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $data['captcha_image'] = $this->captchalib->generate_captcha()['image'];
            $data['user_type']="parent";
            $this->load->view('userlogin', $data);
        } else {
            $login_post = array(
                'username' => $this->input->post('username'),
                'password' => $this->input->post('password'),
            );
            $data['captcha_image'] = isset($this->captchalib->generate_captcha()['image']);
            $login_details         = $this->user_model->checkParentLogin($login_post);
            if (isset($login_details) && !empty($login_details)) {
                $user = $login_details[0];
                if ($user->is_active == "yes") {
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
                        redirect('user/user/choose');
                    } else {
                        $data['error_message'] = 'Account Suspended';
                        $this->load->view('parentlogin', $data);
                    }
                } else {
                    $data['error_message'] = $this->lang->line('your_account_is_disabled_please_contact_to_administrator');
                    $this->load->view('parentlogin', $data);
                }
            } else {
                $data['user_type']="parent";
                $data['error_message'] = $this->lang->line('invalid_username_or_password');
                $this->load->view('parentlogin', $data);
            }
        }      
    }
    public function parentRegister()
    {
        $this->form_validation->set_rules('mobileno', 'mobile no', 'trim|required|xss_clean');
        $this->form_validation->set_rules('grno', 'GRNo', 'trim|required|xss_clean');
        $this->form_validation->set_rules('dob', 'Date of Birth"', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            //$data['captcha_image'] = $this->captchalib->generate_captcha()['image'];
            //$data['user_type']="parent";
            $data = array();
            $this->load->view('parentregistration',$data);
        } else {
            $data['current_session']    = $this->setting_model->getCurrentSession();

        }
    }
    public function checkdata_student()
    {
        $mobno=$this->input->post('mobileno');
        //$grno=$this->input->post('grno');
        $dob=$this->input->post('dob');
        //$result = $this->db->query("select id,firstname,middlename,lastname  from students where mobileno = '$mobno' and admission_no = '$grno' and dob = '$dob' and is_active = 'yes'")->row_array();
        $result = $this->db->query("select id,firstname,middlename,lastname  from students where mobileno = '$mobno'  and dob = '$dob' and is_active = 'yes'")->row_array();
        if(empty($result))
        {
            $msg="Invalid Record";
            $array = array('success' => '0', 'error' => '1' , 'message' => $msg);
        }
        else
        {
            $student_id = $result['id'];

            $current_session    = $this->setting_model->getCurrentSession();
            $student_sess = $this->db->query("select * from student_session where session_id = '$current_session' and student_id = '$student_id' and is_active = 'yes' ")->row_array();
            if(empty($student_sess))
            {
                $msg="Student not in current session";
                $array = array('success' => '0', 'error' => '1' , 'message' => $msg);
            }
            else
            {
                $data = array(
                    'mobileno' => $mobno,
                    //'grno' => $grno,
                    'dob' => $dob,
                    'student_id' => $student_id,
                );
                $this->db->insert('parent_registration',$data);
                $insert_id = $this->db->insert_id();
                $hash_key= md5($insert_id);
                $data = array(
                    'hashkey' => $hash_key,
                );
                $this->db->where('id',$insert_id);
                $this->db->update('parent_registration',$data);

                $name = $result['firstname'].' '.$result['middlename'].' '.$result['lastname'];
                $msg = '';
                $array = array('success' => '1', 'error' => '0' , 'message' => $msg, 'name' => $name, 'hashkey' => $hash_key);
            }    
        }
        echo json_encode($array);
    }
    public function reset_password()
    {
        $this->form_validation->set_rules('hashk', 'hash key', 'trim|required|xss_clean');
        $this->form_validation->set_rules('pwd', 'Password', 'trim|required|xss_clean');
        $this->form_validation->set_rules('repwd', 'Re-password', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $haskkey = $this->input->post('hashk');
            $data['hashk'] = $haskkey;
            $this->load->view('parent_reset_password',$data);
        } else {
            $data['current_session']    = $this->setting_model->getCurrentSession();
            $haskkey = $this->input->post('hashkey');
            $pwd = $this->input->post('pwd');
            $repwd = $this->input->post('repwd');
            $result=$this->db->query("select * from parent_registration where hashkey = '$hashkey'")->row_array();
            if(!empty($result))
            {
                if($pwd==$repwd)
                {
                    $student_id = $result['student_id'];
                    $this->db->query("update students set password = '$pwd' where id = '$student_id'");
                }
                redirect('reset_success');
            }
            
        }        
    }
    public function reset_success()
    {
        $data               = array();
        $data['title']      = 'Login';
        $school             = $this->setting_model->get();
        $data['name']       = $school[0]['name'];
        $notice_content     = $this->config->item('ci_front_notice_content');
        $notices            = $this->cms_program_model->getByCategory($notice_content, array('start' => 0, 'limit' => 5));
        $data['notice']     = $notices;
        $data['school']     = $school[0];
        $is_captcha         = $this->captchalib->is_captcha('userlogin');
        $data["is_captcha"] = $is_captcha;
        $this->load->view('reset_success',$data);
    }
    public function reset_password_submit()
    {
        $this->form_validation->set_rules('hashk', 'hash key', 'trim|required|xss_clean');
        $this->form_validation->set_rules('pwd', 'Password', 'trim|required|xss_clean');
        $this->form_validation->set_rules('repwd', 'Re-password', 'trim|required|matches[pwd]|xss_clean');
        if ($this->form_validation->run() == false) {
            $haskkey = $this->input->post('hashk');
            $data['hashk'] = $haskkey;
            $this->load->view('parent_reset_password',$data);
        } else {
            $data['current_session'] = $this->setting_model->getCurrentSession();
            $hashkey = $this->input->post('hashk');
            $pwd = $this->input->post('pwd');
            $repwd = $this->input->post('repwd');
            $result=$this->db->query("select * from parent_registration where hashkey = '$hashkey'")->row_array();
            if(!empty($result))
            {
                if($pwd==$repwd)
                {
                    $student_id = $result['student_id'];
                    $this->db->query("update students set password = '$pwd' where id = '$student_id'");
                }
                redirect('site/reset_success');
            }
        }         
    }
    public function savemulticlass()
    {
        $student_id = '';
        $this->form_validation->set_rules('student_id', $this->lang->line('student'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {

            $msg = array(
                'student_id' => form_error('student_id'),
            );

            $array = array('status' => '0', 'error' => $msg, 'message' => '');
        } else {

            $data = array(
                'student_id' => date('Y-m-d', strtotime($this->input->post('student_id'))),
            );

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }
    public function check_captcha($captcha)
    {
        if ($captcha != $this->session->userdata('captchaCode')):
            $this->form_validation->set_message('check_captcha', $this->lang->line('incorrect_captcha'));
            return false;
        else:
            return true;
        endif;
    }
    public function refreshCaptcha()
    {
        $captcha = $this->captchalib->generate_captcha();
        echo $captcha['image'];
    }
    public function gateway_admin()
    {
        $this->load->view('wline/admin');
    }
    public function gateway_admin_submit()
    {
        if(isset($_POST)){

                $data = array(
                    'merchantCode'							=> $_POST['merchantCode'],
                    'merchantSchemeCode' 					=> $_POST['merchantSchemeCode'],
                    'salt'									=> $_POST['salt'],
                    'typeOfPayment' 						=> $_POST['typeOfPayment'],
                    'currency' 								=> $_POST['currency'],
                    'primaryColor' 							=> $_POST['primaryColor'],
                    'secondaryColor'						=> $_POST['secondaryColor'],
                    'buttonColor1' 							=> $_POST['buttonColor1'],
                    'buttonColor2' 							=> $_POST['buttonColor2'],
                    'logoURL'			 					=> $_POST['logoURL'],
                    'enableExpressPay' 						=> $_POST['enableExpressPay'],
                    'separateCardMode' 						=> $_POST['separateCardMode'],
                    'enableNewWindowFlow'		 			=> $_POST['enableNewWindowFlow'],
                    'merchantMessage' 						=> $_POST['merchantMessage'],
                    'disclaimerMessage' 					=> $_POST['disclaimerMessage'],
                    'paymentMode' 							=> $_POST['paymentMode'],
                    'paymentModeOrder' 						=> $_POST['paymentModeOrder'],
                    'enableInstrumentDeRegistration' 		=> $_POST['enableInstrumentDeRegistration'],
                    'transactionType'						=> $_POST['transactionType'],
                    'hideSavedInstruments' 					=> $_POST['hideSavedInstruments'],
                    'saveInstrument' 						=> $_POST['saveInstrument'],
                    'displayTransactionMessageOnPopup' 		=> $_POST['displayTransactionMessageOnPopup'],
                    'embedPaymentGatewayOnPage' 			=> $_POST['embedPaymentGatewayOnPage'],
                    'enableEmandate' 						=> $_POST['enableEmandate'],
                    'hideSIConfirmation'					=> $_POST['hideSIConfirmation'],
                    'expandSIDetails'						=> $_POST['expandSIDetails'],
                    'enableDebitDay'						=> $_POST['enableDebitDay'],
                    'showSIResponseMsg' 					=> $_POST['showSIResponseMsg'],
                    'showSIConfirmation'					=> $_POST['showSIConfirmation'],
                    'enableTxnForNonSICards' 				=> $_POST['enableTxnForNonSICards'],
                    'showAllModesWithSI' 					=> $_POST['showAllModesWithSI'],
                    'enableSIDetailsAtMerchantEnd' 			=> $_POST['enableSIDetailsAtMerchantEnd']
                );

                $newData = json_encode($data);
                $name = "worldline_AdminData"; 
                $file_name = 'worldline_AdminData.json';
                $path =  './wline-admin/worldline_AdminData.json';
                if(file_exists($path))
                {  
                    //echo 1;die();
                    unlink($path);
                    if(file_put_contents($path, $newData ) ) 
                    { 
                        echo "File updated successfully";
                    }
                    else
                    { 
                        echo 'There is some error'; 
                    }
                }
                else
                {
                    if(file_put_contents( $path, $newData ) ) 
                    { 
                        echo "File updated successfully";
                    } 
                    else
                    { 
                        echo 'There is some error'; 
                    }
                }	
            }
    }
    public function worldline_payrequest($hash_code) {
        $result=$this->onlinestudent_model->get_online_record($hash_code);
        if(!empty($result))
        {
            $path =  './wline-admin/worldline_AdminData.json';           
            $admin_data = file_get_contents($path);
            $data['mer_array'] = json_decode($admin_data, true);            
            $data['amount']=$result['amount'];
            $data['hashcode']=$hash_code;
            $this->load->view('onlineadmission/worldline/chekoutsubmit',$data);
        }
        else
        {
            echo "Hascode not Found";
        }
    }
    public function worldline_payrequest_submit() {
        $hash_code = $_POST['hashcode'];
        $result=$this->onlinestudent_model->get_online_record($hash_code);
        if(empty($result))
        {
            echo "Invalid Hash Code";   
            die();
        }
        $id=$result['id'];
        $amnt=$result["amount"];
        $orderid = "sngcs".$id;
        $amnt_ps = $amnt;
        $source = $result['source'];
        $path =  './wline-admin/worldline_AdminData.json';    
        $admin_data = file_get_contents($path);
        $mer_array = json_decode($admin_data, true);

        $val = $_POST;
        if($mer_array['typeOfPayment'] == "TEST"){
            $val['amount'] = 1;
        }
        if($mer_array['enableEmandate'] == 1 && $mer_array['enableSIDetailsAtMerchantEnd'] == 1){
            $val['debitStartDate'] = date("d-m-Y");
            $val['debitEndDate'] = date("d-m-Y", strtotime($val['debitEndDate']));
        
        }
        $return_url = 'https://sngcentralschool.org/web/site/wl_response_new';
        //$datastring=$mer_array['merchantCode']."|".$orderid."|".$amnt_ps."|".$val['accNo']."|".$val['custID']."|".$val['mobNo']."|".$val['email']."|".$val['debitStartDate']."|".$val['debitEndDate']."|".$val['maxAmount']."|".$val['amountType']."|".$val['frequency']."|".$val['cardNumber']."|".$val['expMonth']."|".$val['expYear']."|".$val['cvvCode']."|".$val['SALT'];
        $datastring=$mer_array['merchantCode']."|".$orderid."|".$amnt_ps."||||||||||||||".$mer_array['salt'];
        $hashed = hash('sha512',$datastring);  

        //$data = array("hash"=>$hashed,"data"=>array($mer_array['merchantCode'],$orderid,$amnt_ps,,$val['debitEndDate'],$val['maxAmount'],$val['amountType'],$val['frequency'],$val['custID'],$val['mobNo'],$val['email'],$val['accNo'],$val['returnUrl'],$val['name'],$val['scheme'],$val['currency'],$val['accountName'],$val['ifscCode'],$val['accountType']));
        $data = array("hash"=>$hashed, "data"=>array($mer_array['merchantCode'],$orderid,$amnt_ps,'','','','','','','','','',$return_url ,'',$mer_array['merchantSchemeCode'],$mer_array['currency'],'','',''));

        //return json_encode($data);
        $test = json_encode($data);
        //echo "<pre>";
        print_r($test);die();
    }
    public function worldline($hash_code) {
        $this->load->library('worldline/standard/AWLMEAPI');
        $result=$this->onlinestudent_model->get_online_record($hash_code);
        if(!empty($result))
        {
            $id=$result['id'];
            $amnt=$result["amount"];
            $orderid = "sngcs".$id;
            $amnt_ps = $amnt * 100;
            $source = $result['source'];
            //$merchentid="WL0000000027698";//demo
            $merchentid="WL0000000031311";//live
            $response_url = 'https://sngcentralschool.org/web/site/wl_response';
            
            $obj = new AWLMEAPI();
            $reqMsgDTO = new ReqMsgDTO();
            $reqMsgDTO->setOrderId($orderid);
            $reqMsgDTO->setTrnAmt($amnt_ps); //Paisa Format
            $reqMsgDTO->setTrnCurrency("INR");
            $reqMsgDTO->setMeTransReqType("S");
            
            $reqMsgDTO->setRecurrPeriod("NA");
            $reqMsgDTO->setRecurrDay("");
            $reqMsgDTO->setNoOfRecurring("");

            $reqMsgDTO->setMid($merchentid);
            //$reqMsgDTO->setEnckey("6375b97b954b37f956966977e5753ee6");//demo
            $reqMsgDTO->setEnckey("83bb682439ffd5adb97422e7fb621993"); //live
            $reqMsgDTO->setTrnRemarks('School Fees');
            //Optional Fields
            $reqMsgDTO->setAddField1($id);
            $reqMsgDTO->setAddField2($source);
            $reqMsgDTO->setAddField3('');
            $reqMsgDTO->setAddField4('');
            $reqMsgDTO->setAddField5('');
            $reqMsgDTO->setAddField6('');
            $reqMsgDTO->setAddField7('');
            $reqMsgDTO->setAddField8('');
            $reqMsgDTO->setResponseUrl($response_url);
            //Step 3: API call to generate the Message
            // $reqMsgDTO = $obj.generateTrnReqMsg(objReqMsgDTO);
            $merchantRequest = "";
            $reqMsgDTO = $obj->generateTrnReqMsg($reqMsgDTO);
            if ($reqMsgDTO->getStatusDesc() == "Success"){
                $merchantRequest = $reqMsgDTO->getReqMsg();
            }
            $data['merchantRequest'] = $merchantRequest;
            $data['mid']=$merchentid;
            $data['amount']=$amnt;
            $data['hash_code']=$hash_code;
            $data['setting']=$this->setting;
            $data['amount'] = $result["amount"];
            $data['source'] = $source;
            $this->load->view('onlineadmission/worldline/index', $data);
        }
    }
    public function wl_response_new() {

        $response_msg = explode("|", $_REQUEST['msg']);  
        $statuscode = $response_msg[0];
        $status = $response_msg[1];
        $responsecode = $response_msg[0];
        $trnrefno = $response_msg[12];
        $online_id = substr(trim($response_msg[3]), 5);
        $status_desc = $response_msg[2];
        $payment_mode = $response_msg[11];
        if ($status=="SUCCESS"){$trn_status = "passed";}
        else
        {$trn_status = "failed";}
        $array_data = array(
            "trnrefno" => $trnrefno,
            "orderid" => $online_id,
            "responsecode" => $responsecode,
            "status_desc" => $status_desc,
            "payment_mode" => $payment_mode,
            "status_code" => $statuscode,
        );
        $this->db->where('id',$online_id);
        $this->db->update('online_transaction',$array_data);
		$orderid= $online_id;
		if ($status=="SUCCESS"){
            
            $result=$this->onlinestudent_model->get_online_record_by_id($orderid);
            if($result['source']=='parent') {
                $newsess= (array) json_decode($result['session_data']);
                $student_dt= (array)$newsess['student'];
                $lang_array=(array)$student_dt['language'];
                $student_dt['language'] = $lang_array;
                $this->session->set_userdata('student',$student_dt);
                $this->session->set_userdata('current_class',(array)$newsess['current_class']);
                $this->session->set_userdata('top_menu',$newsess['top_menu']);
                redirect('user/user/addfeegrp_submit/'.$result['hash_code']);
            }elseif ($result['source']=='counter-main') {
                $newsess= (array) json_decode($result['session_data']);
                $admin_dt= (array)$newsess['admin'];
                $lang_array=(array)$admin_dt['language'];
                $role_array=(array)$admin_dt['roles'];
                $admin_dt['language'] = $lang_array;
                $admin_dt['roles'] = $role_array;
                $this->session->set_userdata('admin',$admin_dt);
                if(isset($newsess['top_menu'])){$this->session->set_userdata('top_menu',$newsess['top_menu']);}
                if(isset($newsess['sub_menu'])){$this->session->set_userdata('sub_menu',$newsess['sub_menu']);}
                redirect('studentfee/addfeegrp_gateway_submit/'.$result['hash_code']);
            }elseif ($result['source']=='counter-others') {
                $newsess= (array) json_decode($result['session_data']);
                $admin_dt= (array)$newsess['admin'];
                $lang_array=(array)$admin_dt['language'];
                $role_array=(array)$admin_dt['roles'];
                $admin_dt['language'] = $lang_array;
                $admin_dt['roles'] = $role_array;
                $this->session->set_userdata('admin',$admin_dt);
                if(isset($newsess['top_menu'])){$this->session->set_userdata('top_menu',$newsess['top_menu']);}
                if(isset($newsess['sub_menu'])){$this->session->set_userdata('sub_menu',$newsess['sub_menu']);}
                redirect('studentfee/addfeegrp_gateway_submit/'.$result['hash_code']);                
            }elseif ($result['source']=='counter-previous') {
                $newsess= (array) json_decode($result['session_data']);
                $admin_dt= (array)$newsess['admin'];
                $lang_array=(array)$admin_dt['language'];
                $role_array=(array)$admin_dt['roles'];
                $admin_dt['language'] = $lang_array;
                $admin_dt['roles'] = $role_array;
                $this->session->set_userdata('admin',$admin_dt);
                if(isset($newsess['top_menu']))
                {
                $this->session->set_userdata('top_menu',$newsess['top_menu']);
                }
                if(isset($newsess['sub_menu']))
                {
                $this->session->set_userdata('sub_menu',$newsess['sub_menu']);
                }
                redirect('studentfee/addfeegrp_gateway_submit_previous/'.$result['hash_code']);
            }elseif ($result['source']=='counter-general') {
                $newsess= (array) json_decode($result['session_data']);
                $admin_dt= (array)$newsess['admin'];
                $lang_array=(array)$admin_dt['language'];
                $role_array=(array)$admin_dt['roles'];
                $admin_dt['language'] = $lang_array;
                $admin_dt['roles'] = $role_array;
                $this->session->set_userdata('admin',$admin_dt);
                if(isset($newsess['top_menu']))
                {
                $this->session->set_userdata('top_menu',$newsess['top_menu']);
                }
                if(isset($newsess['sub_menu']))
                {
                $this->session->set_userdata('sub_menu',$newsess['sub_menu']);
                }
                redirect('studentfee/addgeneral_gateway_submit/'.$result['hash_code']);
            }
    		exit(0);
		} else{
            $result=$this->onlinestudent_model->get_online_record_by_id($orderid);
            $this->db->query("update ");
            $source=$result['source'];
			redirect('site/payment_failure/'.$source);
    		exit(0);
		}        
    }    
    public function wl_response() {
            
		$this->load->library('worldline/standard/AWLMEAPI');
		$obj = new AWLMEAPI();
		$resMsgDTO = new ResMsgDTO();
		//$enc_key = "6375b97b954b37f956966977e5753ee6";//demo
		$enc_key = "83bb682439ffd5adb97422e7fb621993";//live
		$responseMerchant = $_REQUEST['merchantResponse'];   

		$response = $obj->parseTrnResMsg($responseMerchant, $enc_key);

        $orderid= $response->getOrderId();
        $trnrefno= $response->getPgMeTrnRefno();
        $responsecode= $response->getResponseCode();
        $status_desc = $response->getStatusDesc();
        $card_details = $response->getAddField10();
        $statuscode = $response->getStatusCode();
        if ($response->getStatusCode()=="S"){$trn_status = "passed";}
        else
        {$trn_status = "failed";}
        $array_data = array(
            "trnrefno" => $trnrefno,
            "orderid" => $orderid,
            "responsecode" => $responsecode,
            "status_desc" => $status_desc,
            //"card_details" => $card_details,
            "status_code" => $statuscode,
        );
        $this->db->where('id',$response->getAddField1());
        $this->db->update('online_transaction',$array_data);
		$orderid= $response->getAddField1();
        
		if ($response->getStatusCode()=="S"){
            $result=$this->onlinestudent_model->get_online_record_by_id($orderid);
            if($result['source']=='parent') {
                $newsess= (array) json_decode($result['session_data']);
                $student_dt= (array)$newsess['student'];
                $lang_array=(array)$student_dt['language'];
                $student_dt['language'] = $lang_array;
                $this->session->set_userdata('student',$student_dt);
                $this->session->set_userdata('current_class',(array)$newsess['current_class']);
                $this->session->set_userdata('top_menu',$newsess['top_menu']);
                redirect('user/user/addfeegrp_submit/'.$result['hash_code']);
            }elseif ($result['source']=='counter-main') {
                $newsess= (array) json_decode($result['session_data']);
                $admin_dt= (array)$newsess['admin'];
                $lang_array=(array)$admin_dt['language'];
                $role_array=(array)$admin_dt['roles'];
                $admin_dt['language'] = $lang_array;
                $admin_dt['roles'] = $role_array;
                $this->session->set_userdata('admin',$admin_dt);
                if(isset($newsess['top_menu'])){$this->session->set_userdata('top_menu',$newsess['top_menu']);}
                if(isset($newsess['sub_menu'])){$this->session->set_userdata('sub_menu',$newsess['sub_menu']);}
                redirect('studentfee/addfeegrp_gateway_submit/'.$result['hash_code']);
            }elseif ($result['source']=='counter-others') {
                $newsess= (array) json_decode($result['session_data']);
                $admin_dt= (array)$newsess['admin'];
                $lang_array=(array)$admin_dt['language'];
                $role_array=(array)$admin_dt['roles'];
                $admin_dt['language'] = $lang_array;
                $admin_dt['roles'] = $role_array;
                $this->session->set_userdata('admin',$admin_dt);
                if(isset($newsess['top_menu'])){$this->session->set_userdata('top_menu',$newsess['top_menu']);}
                if(isset($newsess['sub_menu'])){$this->session->set_userdata('sub_menu',$newsess['sub_menu']);}
                redirect('studentfee/addfeegrp_gateway_submit/'.$result['hash_code']);                
            }elseif ($result['source']=='counter-previous') {
                $newsess= (array) json_decode($result['session_data']);
                $admin_dt= (array)$newsess['admin'];
                $lang_array=(array)$admin_dt['language'];
                $role_array=(array)$admin_dt['roles'];
                $admin_dt['language'] = $lang_array;
                $admin_dt['roles'] = $role_array;
                $this->session->set_userdata('admin',$admin_dt);
                if(isset($newsess['top_menu']))
                {
                $this->session->set_userdata('top_menu',$newsess['top_menu']);
                }
                if(isset($newsess['sub_menu']))
                {
                $this->session->set_userdata('sub_menu',$newsess['sub_menu']);
                }
                redirect('studentfee/addfeegrp_gateway_submit_previous/'.$result['hash_code']);
            }elseif ($result['source']=='counter-general') {
                $newsess= (array) json_decode($result['session_data']);
                $admin_dt= (array)$newsess['admin'];
                $lang_array=(array)$admin_dt['language'];
                $role_array=(array)$admin_dt['roles'];
                $admin_dt['language'] = $lang_array;
                $admin_dt['roles'] = $role_array;
                $this->session->set_userdata('admin',$admin_dt);
                if(isset($newsess['top_menu']))
                {
                $this->session->set_userdata('top_menu',$newsess['top_menu']);
                }
                if(isset($newsess['sub_menu']))
                {
                $this->session->set_userdata('sub_menu',$newsess['sub_menu']);
                }
                redirect('studentfee/addgeneral_gateway_submit/'.$result['hash_code']);
            }
    		exit(0);
		} else{
            $result=$this->onlinestudent_model->get_online_record_by_id($orderid);
            $this->db->query("update ");
            $source=$result['source'];
			redirect('site/payment_failure/'.$source);
    		exit(0);
		}        
    }
    public function payment_failure($source)
    {
        $data['setting']=$this->setting;
        $data['source'] = $source;
        $this->load->view('print/payment_failure', $data);
    }

}
