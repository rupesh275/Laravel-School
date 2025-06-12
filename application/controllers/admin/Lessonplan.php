<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Lessonplan extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('customlib');
        $this->sch_current_session = $this->setting_model->getCurrentSession();
        $this->staff_id            = $this->customlib->getStaffID();
        $this->load->library("datatables");
        $this->load->library('imageResize');
    }

    public function index()
    {
        if (!($this->rbac->hasPrivilege('manage_syllabus_status', 'can_view'))) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'lessonplan');
        $this->session->set_userdata('sub_menu', 'admin/lessonplan');
        $data                     = array();
        $class                    = $this->class_model->get();
        $data['classlist']        = $class;
        $data['class_id']         = "";
        $data['section_id']       = "";
        $data['subject_group_id'] = "";
        $data['subject_id']       = "";
        $data['subject_name']     = "";
        $data['lessons']          = array();
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_group_id', $this->lang->line('subject') . " " . $this->lang->line('group'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_id', $this->lang->line('subject'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {

        } else {
            $data['class_id']               = $_POST['class_id'];
            $data['section_id']             = $_POST['section_id'];
            $data['subject_group_id']       = $_POST['subject_group_id'];
            $data['subject_id']             = $_POST['subject_id'];
            $subject_details                = $this->lessonplan_model->get_subjectNameBySubjectGroupSubjectId($_POST['subject_id']);
            $subject_group_class_sectionsId = $this->lessonplan_model->getsubject_group_class_sectionsId($_POST['class_id'], $_POST['section_id'], $_POST['subject_group_id']);
            $data['subject_name']           = $subject_details['name'] . " (" . $subject_details['code'] . ")";
            $lessonlist                     = $this->lessonplan_model->getlessonBysubjectid($_POST['subject_id'], $subject_group_class_sectionsId['id']);

            foreach ($lessonlist as $key => $value) {

                $data['lessons'][$value['id']] = $value;
                $topics                        = $this->lessonplan_model->gettopicBylessonid($value['id'], $this->sch_current_session);
                foreach ($topics as $topic_key => $topic_value) {
                    $data['lessons'][$value['id']]['topic'][] = $topic_value;
                }
            }
        }

        $data['status'] = array('1' => '<span class="label " style="background:#0e0e0e">' . $this->lang->line('complete') . '</span>', '0' => '<span class="label " style="background:#b3b3b3">' . $this->lang->line('incomplete') . '</span>');
        $this->load->view('layout/header');
        $this->load->view('admin/lessonplan/index', $data);
        $this->load->view('layout/footer');
    }

    public function lesson()
    {
        if (!($this->rbac->hasPrivilege('lesson', 'can_view'))) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'lessonplan');
        $this->session->set_userdata('sub_menu', 'admin/lessonplan/lesson');
        $class = $this->class_model->get();

        $data['classlist'] = $class;
        foreach ($class as $class_key => $class_value) {
            $data['class_array'][] = $class_value['id'];
        }
        $carray                   = array();
        $data['class_id']         = "";
        $data['section_id']       = "";
        $data['subject_group_id'] = "";
        $data['subject_id']       = "";
        $userdata                 = $this->customlib->getUserData();
        $role_id                  = $userdata["role_id"];
        $staff_id                 = $userdata["id"];
        $concate                  = "no";
        $condition                = "";
        $where_in                 = array();
        if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            $myclasssubjects = $this->subjecttimetable_model->getByStaffClassTeachersubjects($staff_id);

            if (!empty($myclasssubjects[0]->subject_group_subject_id)) {

                $timetableid = $myclasssubjects[0]->subject_group_subject_id;
                $concate     = "yes";
            }

            $mysubjects = $this->subjecttimetable_model->getByTeacherSubjects($staff_id);
            if (!empty($mysubjects[0]->subject_group_subject_id)) {
                if ($concate == 'yes') {
                    $timetableid = $timetableid . "," . $mysubjects[0]->subject_group_subject_id;
                } else {
                    $timetableid = $mysubjects[0]->subject_group_subject_id;
                }
            }

            if ($timetableid == '') {

                $condition = " and subject_timetable.id in(0) ";
            } else {

                $condition = " and subject_timetable.id in(" . $timetableid . ") ";
            }
            $where_in = explode(',', $timetableid);
        }

        $this->load->view('layout/header');
        $this->load->view('admin/lessonplan/lesson', $data);
        $this->load->view('layout/footer');
    }

    public function createlesson()
    {

        $data['title'] = 'Add Library';
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_group_id', $this->lang->line('subject') . " " . $this->lang->line('group'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_id', $this->lang->line('subject'), 'trim|required|xss_clean');

        $validate = 1;
        if (!empty($_POST['lessons'])) {
            foreach ($_POST['lessons'] as $lessonkey => $lessonvalue) {
                if ($lessonvalue == '') {
                    $validate = 0;
                }
            }
        } else {
            $validate = 0;
        }

        if ($this->form_validation->run() == false) {
            $msg = array(
                'class_id'         => form_error('class_id'),
                'section_id'       => form_error('section_id'),
                'subject_group_id' => form_error('subject_group_id'),
                'subject_id'       => form_error('subject_id'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } elseif ($validate == 0) {
            $msg   = array('lesson' => $this->lang->line('lesson') . " " . $this->lang->line('name') . " field is required");
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $subject_group_class_sectionsId = $this->lessonplan_model->getsubject_group_class_sectionsId($_POST['class_id'], $_POST['section_id'], $_POST['subject_group_id']);
            foreach ($_POST['lessons'] as $key => $value) {
                $data = array(
                    'subject_group_subject_id'        => $_POST['subject_id'],
                    'name'                            => $value,
                    'subject_group_class_sections_id' => $subject_group_class_sectionsId['id'],
                    'session_id'                      => $this->sch_current_session,
                );

                $this->lessonplan_model->add_lesson($data);
            }
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function deletelesson($id)
    {
        if (!($this->rbac->hasPrivilege('lesson', 'can_delete'))) {
            access_denied();
        }
        $this->lessonplan_model->deletelesson($id, $this->sch_current_session);
        redirect('admin/lessonplan/lesson');
    }

    public function deletelessonbulk($id, $subject_group_subject_id)
    {
        if (!($this->rbac->hasPrivilege('lesson', 'can_delete'))) {
            access_denied();
        }
        $this->lessonplan_model->deletelessonbulk($id, $this->sch_current_session, $subject_group_subject_id);
        $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('delete_message'));
        echo json_encode($array);
    }

    public function editlesson($id, $subject_group_subject_id)
    {
        if (!($this->rbac->hasPrivilege('lesson', 'can_edit'))) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'lessonplan');
        $this->session->set_userdata('sub_menu', 'admin/lessonplan/lesson');
        $class             = $this->class_model->get();
        $data['classlist'] = $class;

        foreach ($class as $class_key => $class_value) {
            $data['class_array'][] = $class_value['id'];
        }

        $carray = array();
		$result = $this->lessonplan_model->get($this->sch_current_session, '');		
        if (!empty($result)) {
            foreach ($result as $key => $value) {
                $lesson = $this->lessonplan_model->getlesson($value["subject_group_subject_id"], $value["subject_group_class_sections_id"], $this->sch_current_session);
                if ($lesson != '') {
                    $lessonname[$key] = $lesson;
                }
            }
        }
        $data['result'] = $result;
        if (!empty($lessonname)) {
            $data['lessonname'] = $lessonname;
        }
		
		$editresult = $this->lessonplan_model->get($this->sch_current_session, $id, $subject_group_subject_id);
        $editlesson = $this->lessonplan_model->getlesson($editresult["subject_group_subject_id"], $editresult["subject_group_class_sections_id"], $this->sch_current_session);

        $data['editlessonname']                 = $editlesson;
        $data['class_id']                       = $editresult['classid'];
        $data['section_id']                     = $editresult['sectionid'];
        $data['subject_group_id']               = $editresult['subjectgroupsid'];
        $data['subject_id']                     = $editresult['subjectid'];
        $data['lesson_subject_group_subjectid'] = $editresult['subject_group_subject_id'];

        $this->load->view('layout/header');
        $this->load->view('admin/lessonplan/editlesson', $data);
        $this->load->view('layout/footer');
    }

    public function updatelesson()
    {

        $can_edit      = 1;
        $data['title'] = 'Add Library';
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_group_id', $this->lang->line('subject') . " " . $this->lang->line('group'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_id', $this->lang->line('subject'), 'trim|required|xss_clean');

        $subject_group_class_sectionsId = $this->lessonplan_model->getsubject_group_class_sectionsId($_POST['class_id'], $_POST['section_id'], $_POST['subject_group_id']);
        $all_lessons                    = $this->lessonplan_model->getlessonBysubjectid($_POST['subject_id'], $subject_group_class_sectionsId['id']);
        $userdata                       = $this->customlib->getUserData();

        $role_id = $userdata["role_id"];
        if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            $class_section = $this->lessonplan_model->ifclassteacher($_POST['class_id'], $_POST['section_id'], $userdata['id'], $_POST['subject_group_id'], $_POST['subject_id']);

            $can_edit = $class_section;
        }

        $validate = 1;
        foreach ($all_lessons as $lessonkey => $lessonvalue) {
            if (!empty($_POST['lesson_delete'])) {
                if (in_array($lessonvalue['id'], $_POST['lesson_delete'])) {
                    $validate = 1;
                }
            } elseif ($_POST['lessons_' . $lessonvalue['id']] == '') {
                $validate = 0;
            }
        }

        if (isset($_POST['lessons'])) {
            foreach ($_POST['lessons'] as $lessonkey => $lessonvalue) {
                if ($lessonvalue == '') {
                    $validate = 0;
                }
            }
        }
        if (!empty($_POST['lesson_delete'])) {
            if (count($all_lessons) == count($_POST['lesson_delete'])) {
                if (empty($_POST['topic'])) {
                    $validate = 0;
                }
            }
        }

        if ($this->form_validation->run() == false) {
            $msg = array(
                'class_id'         => form_error('class_id'),
                'section_id'       => form_error('section_id'),
                'subject_group_id' => form_error('subject_group_id'),
                'subject_id'       => form_error('subject_id'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } elseif ($validate == 0) {
            $msg   = array('lesson' => $this->lang->line('lesson') . " " . $this->lang->line('name') . " field is required");
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } elseif ($can_edit == 0) {
            $msg   = array('lesson' => $this->lang->line('you_are_not_authorised_to_update_lessons'));
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            if (isset($_POST['lesson_delete'])) {
                foreach ($_POST['lesson_delete'] as $delete_key => $delete_value) {
                    $this->lessonplan_model->deletelesson($delete_value, $this->sch_current_session);
                }
            }

            $subject_group_class_sectionsId = $this->lessonplan_model->getsubject_group_class_sectionsId($_POST['class_id'], $_POST['section_id'], $_POST['subject_group_id']);
            $all_lessons                    = $this->lessonplan_model->getlessonBysubjectid($_POST['subject_id'], $subject_group_class_sectionsId['id']);

            foreach ($all_lessons as $lessonkey => $lessonvalue) {
                if (isset($_POST['lessons_' . $lessonvalue['id']])) {
                    $data = array(
                        'subject_group_subject_id'        => $_POST['subject_id'],
                        'name'                            => $_POST['lessons_' . $lessonvalue['id']],
                        'subject_group_class_sections_id' => $subject_group_class_sectionsId['id'],
                        'session_id'                      => $this->sch_current_session,
                        'id'                              => $lessonvalue['id'],
                    );

                    $this->lessonplan_model->add_lesson($data);
                }
            }

            if (isset($_POST['lessons'])) {
                foreach ($_POST['lessons'] as $key => $value) {
                    $data = array(
                        'subject_group_subject_id'        => $_POST['subject_id'],
                        'name'                            => $value,
                        'subject_group_class_sections_id' => $subject_group_class_sectionsId['id'],
                        'session_id'                      => $this->sch_current_session,
                    );

                    $this->lessonplan_model->add_lesson($data);
                }
            }
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    //==================================Topic Start===============================
    public function topic()
    {
        if (!($this->rbac->hasPrivilege('topic', 'can_view'))) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'lessonplan');
        $this->session->set_userdata('sub_menu', 'admin/lessonplan/topic');
        $class             = $this->class_model->get();
        $data['classlist'] = $class;
        foreach ($class as $class_key => $class_value) {
            $data['class_array'][] = $class_value['id'];
        }
        $carray                   = array();
        $data['class_id']         = "";
        $data['section_id']       = "";
        $data['subject_group_id'] = "";
        $data['subject_id']       = "";
        $this->load->view('layout/header');
        $this->load->view('admin/lessonplan/topic', $data);
        $this->load->view('layout/footer');
    }

    public function getlessonBysubjectid($sub_id)
    {
        $subject_group_class_sectionsId = $this->lessonplan_model->getsubject_group_class_sectionsId($_POST['class_id'], $_POST['section_id'], $_POST['subject_group_id']);
        $data                           = $this->lessonplan_model->getlessonBysubjectid($sub_id, $subject_group_class_sectionsId['id']);

        echo json_encode($data);
    }

    public function getlessonBylessonid($lesson_id)
    {
        $data = $this->lessonplan_model->getlessonBylessonid($lesson_id);

        echo json_encode($data);
    }

    public function getlessonBysubjectidedit($sub_id)
    {
        $subject_group_class_sections_id = $_POST['subject_group_class_sections_id'];
        $data                            = $this->lessonplan_model->getlessonBysubjectidedit($sub_id, $subject_group_class_sections_id);

        echo json_encode($data);
    }

    public function createtopic()
    {
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_group_id', $this->lang->line('subject') . " " . $this->lang->line('group'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_id', $this->lang->line('subject'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('lesson_id', $this->lang->line('lesson'), 'trim|required|xss_clean');

        $validate = 1;
        if (!empty($_POST['topic'])) {
            foreach ($_POST['topic'] as $topickey => $topicvalue) {
                if ($topicvalue == '') {
                    $validate = 0;
                }
            }
        } else {
            $validate = 0;
        }
        if ($this->form_validation->run() == false) {

            $msg = array(
                'class_id'         => form_error('class_id'),
                'section_id'       => form_error('section_id'),
                'subject_group_id' => form_error('subject_group_id'),
                'subject_id'       => form_error('subject_id'),
                'lesson_id'        => form_error('lesson_id'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } elseif ($validate == 0) {
            $msg   = array('topic' => $this->lang->line('topic') . " " . $this->lang->line('name') . " field is required");
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            foreach ($_POST['topic'] as $key => $value) {
                $data = array(
                    'lesson_id'  => $_POST['lesson_id'],
                    'name'       => $value,
                    'session_id' => $this->sch_current_session,
                );
                $this->lessonplan_model->add_topic($data);
            }
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function deletetopicbulk($id)
    {
        if (!($this->rbac->hasPrivilege('topic', 'can_delete'))) {
            access_denied();
        }
        $this->lessonplan_model->deletetopicbulk($id, $this->sch_current_session);
        $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('delete_message'));
        echo json_encode($array);
    }

    public function edittopic($id)
    {
        if (!($this->rbac->hasPrivilege('topic', 'can_edit'))) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'lessonplan');
        $this->session->set_userdata('sub_menu', 'admin/lessonplan/topic');
        $media_result = $this->lessonplan_model->get_media_master();
        $data['medialist'] = $media_result;
        $class             = $this->class_model->get();
        $data['classlist'] = $class;
        foreach ($class as $class_key => $class_value) {
            $data['class_array'][] = $class_value['id'];
        }
        $carray = array();
        $data['userdata'] = $this->customlib->getUserData();
        $result = $this->lessonplan_model->gettopic($this->sch_current_session, '');

        if (!empty($result)) {
            foreach ($result as $key => $value) {
                $topic             = $this->lessonplan_model->gettopicBylessonid($value["lesson_id"], $this->sch_current_session);
                $topicresult[$key] = $topic;
            }
        }

        $data['result'] = $result;
        if (!empty($topicresult)) {
            $data['topicresult'] = $topicresult;
        }

        $editresult                              = $this->lessonplan_model->gettopic($this->sch_current_session,$id);
        $edittopic                               = $this->lessonplan_model->gettopicBylessonid($editresult["lesson_id"], $this->sch_current_session);
        $data['lesson_id']                       = $editresult["lesson_id"];
        $data['topic_lesson_id']                 = $id;
        $data['edittopicname']                   = $edittopic;
        $data['class_id']                        = $editresult['classid'];
        $data['section_id']                      = $editresult['sectionid'];
        $data['subject_group_id']                = $editresult['subjectgroupsid'];
        $data['subject_id']                      = $editresult['subjectid'];
        $data['lesson_subject_group_subjectid']  = $editresult['subject_group_subject_id'];
        $data['subject_group_class_sections_id'] = $editresult['subject_group_class_sections_id'];

        $this->load->view('layout/header');
        $this->load->view('admin/lessonplan/edittopic', $data);
        $this->load->view('layout/footer');
    }

    public function updatetopic()
    {
        $can_edit = 1;
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_group_id', $this->lang->line('subject_group'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_id', $this->lang->line('subject'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('lesson_id', $this->lang->line('lesson'), 'trim|required|xss_clean');

        $validate = 1;

        $alltopic = $this->lessonplan_model->gettopicBylessonid($_POST['lesson_id'], $this->sch_current_session);
        $userdata = $this->customlib->getUserData();
        $role_id  = $userdata["role_id"];
        if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            $class_section = $this->lessonplan_model->ifclassteacher($_POST['class_id'], $_POST['section_id'], $this->staff_id, $_POST['subject_group_id'], $_POST['subject_id']);

            $can_edit = $class_section;
        }

        foreach ($alltopic as $topickey => $topicvalue) {
            if (!empty($_POST['topic_delete'])) {
                if (in_array($topicvalue['id'], $_POST['topic_delete'])) {
                    $validate = 1;
                }
            } elseif ($_POST['topic_' . $topicvalue['id']] == '') {
                $validate = 0;
            }
        }

        if (!empty($_POST['topic'])) {
            foreach ($_POST['topic'] as $topickey => $topicvalue) {
                if ($topicvalue == '') {
                    $validate = 0;
                }
            }
        }

        if (!empty($_POST['topic_delete'])) {
            if (count($alltopic) == count($_POST['topic_delete'])) {
                if (empty($_POST['topic'])) {
                    $validate = 0;
                }
            }
        }

        if ($this->form_validation->run() == false) {
            $msg = array(
                'class_id'         => form_error('class_id'),
                'section_id'       => form_error('section_id'),
                'subject_group_id' => form_error('subject_group_id'),
                'subject_id'       => form_error('subject_id'),
                'lesson_id'        => form_error('lesson_id'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } elseif ($validate == 0) {
            $msg   = array('topic' => $this->lang->line('topic') . " " . $this->lang->line('name') . " field is required");
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } elseif ($can_edit == 0) {
            $msg   = array('topic' => $this->lang->line('you_are_not_authorised_to_update_topics'));
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            if (isset($_POST['topic_delete'])) {
                foreach ($_POST['topic_delete'] as $delete_key => $delete_value) {
                    $this->lessonplan_model->deletetopic($delete_value, $this->sch_current_session);
                }
            }

            $alltopic = $this->lessonplan_model->gettopicBylessonid($_POST['lesson_id'], $this->sch_current_session);

            foreach ($alltopic as $topickey => $topicvalue) {
                if (isset($_POST['topic_' . $topicvalue['id']])) {
                    $data = array(
                        'lesson_id'  => $_POST['lesson_id'],
                        'name'       => $_POST['topic_' . $topicvalue['id']],
                        'session_id' => $this->sch_current_session,
                        'id'         => $topicvalue['id'],
                    );

                    $this->lessonplan_model->add_topic($data);
                }
            }
            if (isset($_POST['topic'])) {
                foreach ($_POST['topic'] as $key => $value) {
                    $data = array(
                        'lesson_id'  => $_POST['lesson_id'],
                        'name'       => $value,
                        'session_id' => $this->sch_current_session,
                    );

                    $this->lessonplan_model->add_topic($data);
                }
            }
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function changeTopicStatus()
    {
        $id     = $this->input->post('id');
        $status = $this->input->post('status');

        $data   = array('id' => $id, 'complete_date' => '0000-00-00', 'status' => $status);
        $result = $this->lessonplan_model->changeTopicStatus($data);

        if ($result) {
            $response = array('status' => 1, 'msg' => $this->lang->line('success_message'));
            echo json_encode($response);
        }
    }

    public function topic_completedate()
    {

        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {

            $msg = array(
                'date' => form_error('date'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            $data = array(
                'complete_date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                'status'        => 1,
                'id'            => $_POST['id'],
            );

            $this->lessonplan_model->changeTopicStatus($data);

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    //==========================================Syllabus-Assign=========================

    public function gettopicBylessonid($lessonid)
    {
        $data = $this->lessonplan_model->gettopicBylessonid($lessonid, $this->sch_current_session);

        echo json_encode($data);
    }

    public function get_topicbyid()
    {
        $this->lessonplan_model->gettopic($this->sch_current_session,'');
    }

    public function gettopiclist()
    {

        $class             = $this->class_model->get();
        $data['classlist'] = $class;
        foreach ($class as $class_key => $class_value) {

            $class_array[] = $class_value['id'];
        }
        $carray                   = array();
        $data['class_id']         = "";
        $data['section_id']       = "";
        $data['subject_group_id'] = "";
        $data['subject_id']       = "";
        $result                   = $this->lessonplan_model->gettopiclist($this->sch_current_session);
        $m                        = json_decode($result);
        $currency_symbol          = $this->customlib->getSchoolCurrencyFormat();
        $dt_data                  = array();
        if (!empty($m->data)) {
            foreach ($m->data as $key => $value) {
                $topic1    ='';
                $topic     = "";
                $lesson_id = $key;
                $topic     = $this->lessonplan_model->gettopicBylessonid($value->lesson_id, $this->sch_current_session);
                $editbtn = "";
                if ($this->rbac->hasPrivilege('topic', 'can_edit')) {
                    $editbtn = "<a href='" . base_url() . "admin/lessonplan/edittopic/" . $value->lesson_id . "'   class='btn btn-default btn-xs'  data-toggle='tooltip' data-placement='left' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>";
                }
                $deletebtn = '';
                if ($this->rbac->hasPrivilege('topic', 'can_delete')) {
                    $deletebtn = "<a onclick='deletetopicbulk(" . $this->lang->line('delete_confirm') . ")'  class='btn btn-default btn-xs' data-placement='left' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-trash'></i></a>";
                }

                foreach ($topic as $rl_value) {
                    $topic = $rl_value['name'] . '<br>';
                    $topic1 .=  $topic;
                }
                
                if (in_array($value->classid, $class_array)) {
                    $lesson_id = $key;
                    $row       = array();
                    $row[]     = $value->cname;
                    $row[]     = $value->sname;
                    $row[]     = $value->sgname;
                    $row[]     = $value->subname;
                    $row[]     = $value->lessonname;
                    $row[]     = $topic1;
                    $row[]     = $editbtn . ' ' . $deletebtn;
                    $dt_data[] = $row;
                }
            }
        }

        $json_data = array(
            "draw"            => intval($m->draw),
            "recordsTotal"    => intval($m->recordsTotal),
            "recordsFiltered" => intval($m->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    public function getlessonlist()
    {
        $class = $this->class_model->get();

        foreach ($class as $class_key => $class_value) {
            $class_array[] = $class_value['id'];
        }
        $result  = $this->lessonplan_model->getlessonlist($this->sch_current_session, '');
        $m       = json_decode($result);
        $dt_data = array();
        if (!empty($m->data)) {
            foreach ($m->data as $key => $value) {

                $topic       = "";
                $lesson_id   = $key;
                $lesson_name = "";
                $lesson      = $this->lessonplan_model->getlesson($value->subject_group_subject_id, $value->subject_group_class_sections_id, $this->sch_current_session);

                if ($this->rbac->hasPrivilege('lesson', 'can_edit')) {
                    $editbtn = "<a href='" . base_url() . "admin/lessonplan/editlesson/" . $value->subject_group_class_sections_id . "/" . $value->subject_group_subject_id . "'   class='btn btn-default btn-xs'  data-toggle='tooltip' data-placement='left' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>";
                }
                if ($this->rbac->hasPrivilege('lesson', 'can_delete')) {
                    $deletebtn = '';
                    $deletebtn = "<a onclick='deletelessonbulk(" . '"' . $value->subject_group_class_sections_id . '"' . ',' . '"' . $value->subject_group_subject_id . '"' . "  )'  class='btn btn-default btn-xs' data-placement='left' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-remove'></i></a>";
                }

                if (in_array($value->classid, $class_array)) {

                    foreach ($lesson as $rl_value) {
                        $lesson_name .= $rl_value['name'] . '<br>';
                    };
                    $row       = array();
                    $row[]     = $value->cname;
                    $row[]     = $value->sname;
                    $row[]     = $value->sgname;
                    $row[]     = $value->subname;
                    $row[]     = $lesson_name;
                    $row[]     = $editbtn . ' ' . $deletebtn;
                    $dt_data[] = $row;
                }
            }
        }

        $json_data = array(
            "draw"            => intval($m->draw),
            "recordsTotal"    => intval($m->recordsTotal),
            "recordsFiltered" => intval($m->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    public function media_master($id ="")
    {
        if (!$this->rbac->hasPrivilege('media_master', 'can_edit')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'lessonplan');
        $this->session->set_userdata('sub_menu', 'admin/lessonplan/media_master');
        $media_result = $this->lessonplan_model->get_media_master();
        $data['medialist'] = $media_result;
        $data['title'] = 'Media Master';
        $data['id'] = $id;
        if ($id != "") {
            $data['update'] = $this->lessonplan_model->get_media_master($id);
        }
        $this->form_validation->set_rules('media_name', "Media Name", 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/lessonplan/media_master', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id' => $this->input->post('id'),
                'media_name' => $this->input->post('media_name'),
                'input_type' => $this->input->post('input_type'),
                'type_detail' => $this->input->post('type_detail'),
            );
            
            $this->lessonplan_model->add_media($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/lessonplan/media_master');
        }
    }

    public function delete_media_master($id)
    {
        if (!$this->rbac->hasPrivilege('media_master', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Exam Scheme List';
        $this->lessonplan_model->remove_media_master($id);

        redirect('admin/lessonplan/media_master');
    }

    public function getAllmedia()
    {
        $data['topic_id']= $topic_id = $this->input->post('topic_id');
        $data['resultlist'] = $this->lessonplan_model->getallmedia($topic_id);
        $media_page       = $this->load->view('admin/lessonplan/media', $data, true);
        $data = array('status' => '1', 'error' => '', 'page' => $media_page);
        echo json_encode($data);
    }

    public function addmedia()
    {
        $this->form_validation->set_error_delimiters('', '');
        if ($this->input->post('media_type_id') == 1) {
            $this->form_validation->set_rules('embed', "Video ID", 'required|trim|xss_clean');
        }else {
            $this->form_validation->set_rules('file', "file", 'callback_handle_upload');
        }
        $this->form_validation->set_rules('title', "title", 'required|trim|xss_clean');
        $this->form_validation->set_rules('keywords', "keywords", 'required|trim|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'file' => form_error('file'),
                'embed' => form_error('embed'),
                'title' => form_error('title'),
                'keywords' => form_error('keywords'),
            );
            $array = array('status' => 0, 'error' => $data);
            echo json_encode($array);
        } else {
            
            $data  = array(
                'media_type_id'    => $this->input->post('media_type_id'),
                'topic_id'    => $this->input->post('topic_id'),
                'media_name'   => $this->input->post('embed'),
                'title'   => $this->input->post('title'),
                'keywords'   => $this->input->post('keywords'),
                'description'   => $this->input->post('description'),
                'created_at'  => date('Y-m-d h:i:s'),
                'created_by'  => $this->customlib->getAdminSessionUserName(),
            );

            $insert_id = $this->lessonplan_model->addmedia($data);
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $fileInfo = pathinfo($_FILES["file"]["name"]);
                $img_name = $insert_id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/lessonplan/media/" . $img_name);
                $data_img = array('id' => $insert_id, 'media_name' => 'uploads/lessonplan/media/' . $img_name);
                $this->lessonplan_model->addmedia($data_img);
            }
            echo json_encode(array('status' => 1, 'msg' => $this->lang->line('file_upload_successfully'), 'error' => ''));
        }
    }
    
    public function handle_upload()
    {
        $image_validate = $this->config->item('file_validate');
        $result         = $this->filetype_model->get();
        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {

            $file_type         = $_FILES["file"]['type'];
            $file_size         = $_FILES["file"]["size"];
            $file_name         = $_FILES["file"]["name"];
            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($files = filesize($_FILES['file']['tmp_name'])) {

                if (!in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                    return false;
                }
                if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                    return false;
                }
                if ($file_size > $result->file_size) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . 
                    number_format($result->file_size / 1048576, 2) . ' MB');
                    return false;
                }
            } else {
                $this->form_validation->set_message('handle_upload', 'File size is too small');
                return false;
            }

            return true;
        } else {
            $this->form_validation->set_message('handle_upload', $this->lang->line('the_file_field_is_required'));
            return false;
        }
    }

    public function deleteTopicMedia()
    {
        $id = $this->input->post('id');
        $this->lessonplan_model->remove_topic_media($id);
        echo json_encode(array('status' => 1, 'message' => $this->lang->line('record_deleted_successfully')));
    }
    

    public function getvideopopup($id)
    {
        $data['result'] = $this->lessonplan_model->getvideo($id);
        $this->load->view('layout/header');
        $this->load->view('admin/lessonplan/videopopup', $data);
        $this->load->view('layout/footer');
    }
}
