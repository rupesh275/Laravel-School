<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Examgroup extends Admin_Controller
{

    public $exam_type            = array();
    private $sch_current_session = "";

    public function __construct()
    {
        parent::__construct();
        $this->load->library('encoding_lib');
        $this->load->library('mailsmsconf');
        $this->exam_type           = $this->config->item('exam_type');
        $this->sch_current_session = $this->setting_model->getCurrentSession();
        $this->attendence_exam     = $this->config->item('attendence_exam');
        $this->sch_setting_detail  = $this->setting_model->getSetting();
    }

    public function exportformat()
    {
        $this->load->helper('download');
        $filepath = "./backend/import/import_marks_sample_file.csv";
        $data     = file_get_contents($filepath);
        $name     = 'import_marks_sample_file.csv';
        force_download($name, $data);
    }

    public function uploadfile()
    {

        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_upload');
        if ($this->form_validation->run() == false) {
            $data = array(
                'file' => form_error('file'),
            );
            $array = array('status' => 0, 'error' => $data);
            echo json_encode($array);
        } else {
            $return_array = array();
            //====================
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {

                $fileName = $_FILES["file"]["tmp_name"];
                if (isset($_FILES["file"]) && !empty($_FILES['file']['name']) && $_FILES["file"]["size"] > 0) {

                    $file = fopen($fileName, "r");
                    $flag = true;
                    while (($column = fgetcsv($file, 10000, ",")) !== false) {
                        if ($flag) {
                            $flag = false;
                            continue;
                        }
                        if (trim($column['0']) != "" && trim($column['1']) != "" && trim($column['2']) != "") {
                            $return_array[] = json_encode(
                                array(
                                    'roll_no'    => $column['0'],
                                    'attendence' => $column['1'],
                                    'marks'      => number_format($column['2'], 2, '.', ''),
                                    'note'       => $this->encoding_lib->toUTF8($column['3']),
                                )
                            );
                        }
                    }
                }

                $array = array('status' => '1', 'error' => '', 'student_marks' => $return_array);
                echo json_encode($array);
            }
            //=============
        }
    }

    public function handle_upload()
    {

        $image_validate = $this->config->item('csv_validate');

        if (isset($_FILES["file"]) && !empty($_FILES['file']['name']) && $_FILES["file"]["size"] > 0) {

            $file_type         = $_FILES["file"]['type'];
            $file_size         = $_FILES["file"]["size"];
            $file_name         = $_FILES["file"]["name"];
            $allowed_extension = $image_validate['allowed_extension'];
            $ext               = pathinfo($file_name, PATHINFO_EXTENSION);
            $allowed_mime_type = $image_validate['allowed_mime_type'];
            $finfo             = finfo_open(FILEINFO_MIME_TYPE);
            $mtype             = finfo_file($finfo, $_FILES['file']['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mtype, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_upload', 'File Type Not Allowed');
                return false;
            }

            if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_upload', 'Extension Not Allowed');
                return false;
            }
            if ($file_size > $image_validate['upload_size']) {
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($image_validate['upload_size'] / 1048576, 2) . " MB");
                return false;
            }

            return true;
        } else {
            $this->form_validation->set_message('handle_upload', 'Please choose a file to upload.');
            return false;
        }
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('exam_group', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'Examinations/examgroup');
        $data['title']      = 'Add Batch';
        $data['title_list'] = 'Recent Batch';
        $data['examType']   = $this->exam_type;
        $data['examGroupType']   = $this->examgroup_model->getexamType();
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('exam_type', $this->lang->line('exam') . " " . $this->lang->line('type'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
        } else {
            $is_active = 0;

            $data = array(
                'name'        => $this->input->post('name'),
                'exam_type'   => $this->input->post('exam_type'),
                'exam_type_id'   => $this->input->post('examtype_id'),
                'is_active'   => $is_active,
                'description' => $this->input->post('description'),
            );
            // print_r($data);die;
            $insert_id = $this->examgroup_model->add($data);

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/examgroup/index');
        }
        $examgroup_result      = $this->examgroup_model->get();
        $data['examgrouplist'] = $examgroup_result;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/examgroup/examgroupList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function getExamByExamgroup()
    {
        $exam_group_id = $this->input->post('exam_group_id');
        $data          = $this->examgroup_model->getExamByExamGroup($exam_group_id, true);
        echo json_encode($data);
    }

    public function deleteExam()
    {

        $data['title'] = 'deleteExam';
        $id            = $this->input->post('id');
        if (!$this->examgroup_model->delete_exam($id)) {
            echo json_encode(array('status' => 0, 'message' => $this->lang->line('something_wrong')));
        } else {
            echo json_encode(array('status' => 1, 'message' => $this->lang->line('record_deleted_successfully')));
        }
    }

    public function exam($id)
    {
        $data                    = array();
        $data['examgroupDetail'] = $this->examgroup_model->getExamByID($id);
        $data['exam_subjects']   = $this->batchsubject_model->getExamSubjects($id);
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $session                 = $this->session_model->get();
        $data['sessionlist']     = $session;
        $data['current_session'] = $this->sch_current_session;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/examgroup/exam', $data);
        $this->load->view('layout/footer', $data);
    }

    public function examresult($id)
    {
        $data = array();

        $data['id']        = $id;
        $class             = $this->class_model->get();
        $data['classlist'] = $class;
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $exam_subject_id                                = $this->input->post('exam_group_class_batch_exam_subject_id');
            $class_id                                       = $this->input->post('class_id');
            $batch_id                                       = $this->input->post('batch_id');
            $data['class_id']                               = $this->input->post('class_id');
            $data['batch_id']                               = $this->input->post('batch_id');
            $data['exam_group_class_batch_exam_subject_id'] = $this->input->post('exam_group_class_batch_exam_subject_id');

            $data['exam_subjects'] = $this->batchsubject_model->getExamSubjects($id);
            $resultlist            = $this->batchsubject_model->examGroupExamResult($class_id, $batch_id, $id);

            $data['resultlist'] = $resultlist;
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/examgroup/examresult', $data);
        $this->load->view('layout/footer', $data);
    }

    public function addmark($id)
    {
        $data = array();

        $data['exam_subjects'] = $this->batchsubject_model->getExamSubjects($id);
        $data['id']            = $id;
        $class                 = $this->class_model->get();
        $data['classlist']     = $class;
        $session               = $this->session_model->get();
        $data['sessionlist']   = $session;
        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $exam_subject_id                                = $this->input->post('exam_group_class_batch_exam_subject_id');
            $data['exam_group_class_batch_exam_subject_id'] = $this->input->post('exam_group_class_batch_exam_subject_id');
            $class_id                                       = $this->input->post('class_id');
            $section_id                                     = $this->input->post('section_id');
            $session_id                                     = $this->input->post('session_id');
            $data['class_id']                               = $this->input->post('class_id');
            $data['section_id']                             = $this->input->post('section_id');
            $data['session_id']                             = $this->input->post('session_id');
            $resultlist                                     = $this->examgroupstudent_model->examGroupSubjectResult($exam_subject_id, $class_id, $section_id, $session_id);
            $subject_detail                                 = $this->batchsubject_model->getExamSubject($exam_subject_id);
            $data['subject_detail']                         = $subject_detail;
            $data['attendence_exam']                        = $this->attendence_exam;
            $data['resultlist']                             = $resultlist;
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/examgroup/addmark', $data);
        $this->load->view('layout/footer', $data);
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('exam_group', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Delete Batch';
        $this->examgroup_model->remove($id);
        redirect('admin/examgroup');
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('exam', 'can_edit')) {
            access_denied();
        }

        $data['id']            = $id;
        $examgroup             = $this->examgroup_model->get($id);
        $data['examgroup']     = $examgroup;
        $data['examType']      = $this->exam_type;
        $examgroup_result      = $this->examgroup_model->get();
        $data['examgrouplist'] = $examgroup_result;
        $data['examGroupType']   = $this->examgroup_model->getexamType();

        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/examgroup/examgroupEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $is_active = 0;

            $data = array(
                'id'          => $this->input->post('id'),
                'name'        => $this->input->post('name'),
                'exam_type'   => $this->input->post('exam_type'),
                'exam_type_id'   => $this->input->post('examtype_id'),
                'is_active'   => $is_active,
                'description' => $this->input->post('description'),
            );
            $insert_id = $this->examgroup_model->add($data);

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/examgroup/index');
        }
    }

    public function getByClassSection()
    {
        $section_id = $this->input->post('section_id');
        $data       = $this->examgroup_model->getStudentBatch($section_id);
        echo json_encode($data);
    }

    public function addexam($id)
    {

        if (!$this->rbac->hasPrivilege('exam', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'Examinations/examgroup');
        $data['title']      = 'Add Batch';
        $data['title_list'] = 'Recent Batch';

        // $class               = $this->class_model->get();
        $class               = $this->examgroup_model->getclassbyexamgroup($id);
        $data['classlist']   = $class;
        $data['examType']    = $this->exam_type;
        $session             = $this->session_model->get();
        $data['sessionlist'] = $session;
        $subjectlist         = $this->subject_model->get();
        $data['subjectlist'] = $subjectlist;
        $userdata = $this->customlib->getUserData();
        // if ($userdata['role_id'] == 2) {
        //     $allsubjectlist         = $this->subject_model->getTeacherSubject($userdata['id']);
        //     $data['allsubjectlist'] = $allsubjectlist;
        // } else {
        //     $allsubjectlist         = $this->subject_model->getsubject();
        //     $data['allsubjectlist'] = $allsubjectlist;
        // }
        

        
        $data['current_session'] = $this->sch_current_session;
        $data['examgroup']       = $this->examgroup_model->get($id);
        $data['examlist']       = $this->examgroup_model->getexamlist($id);

        $this->load->view('layout/header', $data);
        $this->load->view('admin/examgroup/addexam', $data);
        $this->load->view('layout/footer', $data);
    }

    public function getNotAppliedDiscount($student_session_id)
    {
        return $this->feediscount_model->getDiscountNotApplied($student_session_id);
    }

    public function getstudents()
    {
        $exam_subject_id                                = $this->input->post('subject_id');
        $data['exam_group_class_batch_exam_subject_id'] = $exam_subject_id;
        $class_id                                       = $this->input->post('class_id');
        $section_id                                     = $this->input->post('section_id');
        $session_id                                     = $this->input->post('session_id');
        $data['class_id']                               = $this->input->post('class_id');
        $data['section_id']                             = $this->input->post('section_id');
        $data['session_id']                             = $this->input->post('session_id');
        $resultlist                                     = $this->examgroupstudent_model->examGroupSubjectResult($exam_subject_id, $class_id, $section_id, $session_id);

        $subject_detail = $this->batchsubject_model->getExamSubject($exam_subject_id);

        $data['subject_detail']  = $subject_detail;
        $data['attendence_exam'] = $this->attendence_exam;
        $data['resultlist']      = $resultlist;
        $data['sch_setting']     = $this->sch_setting_detail;
        $data['userdata'] = $this->customlib->getUserData();
        $student_exam_page       = $this->load->view('admin/examgroup/_partialstudentmarkEntry', $data, true);

        $array = array('status' => '1', 'error' => '', 'page' => $student_exam_page);
        echo json_encode($array);
    }

    public function getstudents_grace()
    {
        $exam_subject_id                                = $this->input->post('subject_id');
        $data['exam_group_class_batch_exam_subject_id'] = $exam_subject_id;
        $class_id                                       = $this->input->post('class_id');
        $section_id                                     = $this->input->post('section_id');
        $session_id                                     = $this->input->post('session_id');
        $data['class_id']                               = $this->input->post('class_id');
        $data['section_id']                             = $this->input->post('section_id');
        $data['session_id']                             = $this->input->post('session_id');
        $resultlist                                     = $this->examgroupstudent_model->examGroupSubjectResult($exam_subject_id, $class_id, $section_id, $session_id);

        $subject_detail = $this->batchsubject_model->getExamSubject($exam_subject_id);

        $userdata = $this->customlib->getUserData();
        $role_id  = $userdata["role_id"];
        $data['class_teacher'] = $this->classteacher_model->checkclassteacher($class_id, $section_id, $userdata['id']);

        $data['subject_detail']  = $subject_detail;
        $data['attendence_exam'] = $this->attendence_exam;
        $data['resultlist']      = $resultlist;
        $data['sch_setting']     = $this->sch_setting_detail;
        $data['userdata'] = $this->customlib->getUserData();
        $student_exam_page       = $this->load->view('admin/examgroup/_partialstudentgracemarkEntry', $data, true);

        $array = array('status' => '1', 'error' => '', 'page' => $student_exam_page);
        echo json_encode($array);
    }

    public function getstudentmarksSubjects()
    {
        $exam_studentid                                = $this->input->post('studentid');
        $main_sub                                      = $this->input->post('main_sub');

        $student_detail = $this->examgroup_model->getstudentdetail($exam_studentid);
        $data['student_detail'] = $student_detail;
        $data['student_Alldetail'] = $this->examgroup_model->getstudentAlldetail($student_detail['student_id']);
        $data['student_AllSubjects'] = $this->examgroup_model->getstudentSubjects($student_detail['exam_group_class_batch_exam_id'], $exam_studentid);
        $exam_subjects = $this->batchsubject_model->getExamSubjects($student_detail['exam_group_class_batch_exam_id'], $main_sub);
        $data['subjectList'] = $exam_subjects;
        $data['exam_studentid'] = $exam_studentid;
        $data['attendence_exam'] = $this->attendence_exam;
        $student_exam_page       = $this->load->view('admin/mark/student_subjectMark', $data, true);
        $data = array('status' => '1', 'error' => '', 'page' => $student_exam_page);
        echo json_encode($data);
        // print_r($student_AllSubjects);
    }

    public function subjectstudent()
    {
        $this->form_validation->set_error_delimiters('<p>', '</p>');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('subject_id', $this->lang->line('subject'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('session_id', $this->lang->line('session'), 'required|trim|xss_clean');

        $userdata = $this->customlib->getUserData();
        $role_id  = $userdata["role_id"];
        $can_edit = 1;
        if (isset($role_id) && $role_id == 2 && $userdata["user_type"] == "Teacher") {
            $main_sub = $this->input->post('main_sub');

            $myclasssubjects = $this->subjecttimetable_model->canAddExamMarksTeacher($userdata["id"], $this->input->post('class_id'), $this->input->post('section_id'), $this->input->post('teachersubject_id'), $main_sub);
            $can_edit        = $myclasssubjects;
        }

        if (isset($role_id) && $userdata["role_id"] == 2 && $userdata["class_teacher"] == "yes") {
            $myclasssubjects = $this->subjecttimetable_model->canAddExamMarks($userdata["id"], $this->input->post('class_id'), $this->input->post('section_id'), $this->input->post('teachersubject_id'));
            $can_edit        = $myclasssubjects;
        }
        // print_r($userdata);
        if ($this->form_validation->run() == false) {
            $data = array(
                'class_id'   => form_error('class_id'),
                'section_id' => form_error('section_id'),
                'session_id' => form_error('session_id'),
                'subject_id' => form_error('subject_id'),
            );
            $array = array('status' => 0, 'error' => $data);
            echo json_encode($array);
        } elseif ($can_edit == 0) {
            $msg   = array('lesson' => $this->lang->line('not_authoried'));
            $array = array('status' => 0, 'error' => $msg);
            echo json_encode($array);
        } else {
            $exam_subject_id                                = $this->input->post('subject_id');
            $data['exam_group_class_batch_exam_subject_id'] = $exam_subject_id;
            $class_id                                       = $this->input->post('class_id');
            $section_id                                     = $this->input->post('section_id');
            $session_id                                     = $this->input->post('session_id');
            $data['class_id']                               = $this->input->post('class_id');
            $data['section_id']                             = $this->input->post('section_id');
            $data['session_id']                             = $this->input->post('session_id');
            $resultlist                                     = $this->examgroupstudent_model->examGroupSubjectResult($exam_subject_id, $class_id, $section_id, $session_id);

            $subject_detail = $this->batchsubject_model->getExamSubject($exam_subject_id);

            $data['subject_detail']  = $subject_detail;
            $data['attendence_exam'] = $this->attendence_exam;
            $data['resultlist']      = $resultlist;
            $data['sch_setting']     = $this->sch_setting_detail;
            $student_exam_page       = $this->load->view('admin/examgroup/_partialstudentmarkEntry', $data, true);

            $array = array('status' => '1', 'error' => '', 'page' => $student_exam_page);
            echo json_encode($array);
        }
    }
    public function searchsubjectstudent()
    {
        // print_r($this->input->post());
        $this->form_validation->set_error_delimiters('<p>', '</p>');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('main_sub', $this->lang->line('subject'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('session_id', $this->lang->line('session'), 'required|trim|xss_clean');

        // $userdata = $this->customlib->getUserData();
        // $role_id  = $userdata["role_id"];
        // $can_edit = 1;
        // if (isset($role_id) && $role_id == 2 && $userdata["user_type"] == "Teacher") {
        //     $main_sub = $this->input->post('main_sub');

        //     $myclasssubjects = $this->subjecttimetable_model->canAddExamMarksTeacher($userdata["id"], $this->input->post('class_id'), $this->input->post('section_id'), $this->input->post('teachersubject_id'), $main_sub);
        //     $can_edit        = $myclasssubjects;
        // }

        // if (isset($role_id) && $userdata["role_id"] == 2 && $userdata["class_teacher"] == "yes") {
        //     $myclasssubjects = $this->subjecttimetable_model->canAddExamMarks($userdata["id"], $this->input->post('class_id'), $this->input->post('section_id'), $this->input->post('teachersubject_id'));
        //     $can_edit        = $myclasssubjects;
        // }
        // print_r($userdata);
        if ($this->form_validation->run() == false) {
            $data = array(
                'class_id'   => form_error('class_id'),
                'section_id' => form_error('section_id'),
                'session_id' => form_error('session_id'),
                'main_sub' => form_error('main_sub'),
            );
            $data = array('status' => 0, 'error' => $data);
            echo json_encode($data);
        } else {

            $exam_subject_id                                = $this->input->post('dataexam_id');
            $class_id                                       = $this->input->post('class_id');
            $section_id                                     = $this->input->post('section_id');
            $main_sub                                       = $this->input->post('main_sub');
            $session_id                                     = $this->input->post('session_id');
            $data['class_id']                               = $this->input->post('class_id');
            $data['section_id']                             = $this->input->post('section_id');
            $data['session_id']                             = $this->input->post('session_id');
            $data['exam_id']                                = $this->input->post('dataexam_id');
            $resultlist                                     = $this->examgroupstudent_model->examGroupSubjectResult($exam_subject_id, $class_id, $section_id, $session_id);

            $data['examgroupDetail'] = $this->examgroup_model->getExamByID($exam_subject_id);
            $userdata = $this->customlib->getUserData();
            $role_id  = $userdata["role_id"];
            $data['exam_subjects'] = $this->batchsubject_model->getExamSubjects($exam_subject_id, $main_sub);
            $class                   = $this->class_model->get();
            $data['classlist']       = $class;
            $session                 = $this->session_model->get();
            $data['sessionlist']     = $session;
            $data['current_session'] = $this->sch_current_session;
            // echo "<pre>";
            // print_r($data);

            $data['subject_page']    = $this->load->view('admin/examgroup/_getSubjectByExam', $data, true);
            echo json_encode($data);
        }
    }

    public function examstudent()
    {
        $this->form_validation->set_error_delimiters('<li>', '</li>');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'required|trim|xss_clean');
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $data['sch_setting']     = $this->sch_setting_detail;
        if ($this->form_validation->run() == false) {
            $msg = array(
                'class_id'   => form_error('class_id'),
                'section_id' => form_error('section_id'),
                // 'exam_id'    => form_error('exam_id'),
            );
            $array = array('status' => 0, 'error' => $msg);
            echo json_encode($array);
        } else {

            $class_id   = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');

            $data['class_id']   = $this->input->post('class_id');
            $data['section_id'] = $this->input->post('section_id');
            $data['exam_id']    = $this->input->post('exam_id');
            $resultlist         = $this->examstudent_model->searchExamStudents($data['class_id'], $data['section_id'], $data['exam_id']);

            $data['resultlist'] = $resultlist;
            $student_exam_page  = $this->load->view('admin/examgroup/_partialexamstudent', $data, true);
            $array              = array('status' => '1', 'error' => '', 'page' => $student_exam_page);
            echo json_encode($array);
        }
    }

    public function ajaxaddexam()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('exam', $this->lang->line('exam'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('examresulttype_id', "Exam Result Type", 'required|trim|xss_clean');
        $this->form_validation->set_rules('session_id', $this->lang->line('session'), 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'exam'       => form_error('exam'),
                'session_id' => form_error('session_id'),
            );
            $array = array('status' => 0, 'error' => $data);
            echo json_encode($array);
        } else {
            $subjectlist = [];

            $examlist    = $this->input->post('examlist');
            if (!empty($examlist)) {
                $subjectlist = $this->examgroup_model->getsubjectlist($examlist);
            }
            $exam_id    = $this->input->post('exam_id');
            $is_active  = $this->input->post('is_active');
            $is_publish = $this->input->post('is_publish');
            $mark_result = $this->input->post('mark_result');
            $exam_result_type = $this->input->post('examresulttype_id');
            $examcategory_id = $this->input->post('examcategory_id');

            if (isset($is_active)) {
                $is_active = 1;
            } else {
                $is_active = 0;
            }

            if (isset($is_publish)) {
                $is_publish = 1;
            } else {
                $is_publish = 0;
            }
            if (isset($mark_result)) {
                $mark_result = 1;
            } else {
                $mark_result = 0;
            }

            $postarray = array(
                'exam'          => $this->input->post('exam'),
                'exam_group_id' => $this->input->post('exam_group_id'),
                'session_id'    => $this->input->post('session_id'),
                'is_active'     => $is_active,
                'is_publish'    => $is_publish,
                'mark_result'   => $mark_result,
                "exam_result_type" => $exam_result_type,
                'examcategory_id'   => $examcategory_id,
                'description'   => $this->input->post('description'),
                'use_exam_roll_no'   => $this->input->post('use_exam_roll_no'),
                'examlist_id'   => $this->input->post('examlist'),
                'exam_srno'   => $this->input->post('exam_srno')

            );
            //  print_r($postarray);die;
            if ($exam_id != 0) {
                $postarray['id'] = $exam_id;
            }

            $inserted_id = $this->examgroup_model->add_exam($postarray);
            // Start New Exam List Update
            // $insert_array  = array();
            // print_r($subjectlist); die;
            if ($exam_id == 0) {
                if (!empty($subjectlist)) {
                    foreach ($subjectlist as  $row_value) {
                        $insert_array[] = array(
                            'exam_group_class_batch_exams_id'    => $inserted_id,
                            'subject_id'                         => $row_value['subject_id'],
                            'main_sub'                           => $row_value['main_sub'],
                            'max_marks'                          => $row_value['max_marks'],
                            'min_marks'                          => $row_value['min_marks'],
                        );
                    }
                    $this->examgroup_model->insertsubject($insert_array);
                }
            }


            // End New Exam List Update

            $exam_data   = $this->examgroup_model->getExamByID($exam_id);
            if ($is_publish) {
                $exam_students = $this->examgroupstudent_model->searchExamStudentsByExam($exam_id);
                $student_exams = array('exam' => $exam_data, 'exam_result' => $exam_students);
                $s             = $this->mailsmsconf->mailsms('exam_result', $student_exams);
            }

            if ($exam_id != 0) {
                $array = array('status' => '1', 'error' => '', 'message' => $this->lang->line('update_message'));
            } else {
                $array = array('status' => '1', 'error' => '', 'message' => $this->lang->line('success_message'));
            }

            echo json_encode($array);
        }
    }

    public function getExamsByExamGroup()
    {
        $exam_group_id = $this->input->post('exam_group_id');
        $exams         = $this->examgroup_model->getExamByExamGroup($exam_group_id, true);

        $array = array('status' => '1', 'error' => '', 'result' => $exams);
        echo json_encode($array);
    }

    public function entrymarks()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('exam_group_class_batch_exam_subject_id', 'Subject', 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'exam_group_class_batch_exam_subject_id' => form_error('exam_group_class_batch_exam_subject_id'),
            );
            $array = array('status' => 0, 'error' => $data);
            echo json_encode($array);
        } else {
            $exam_group_student_id = $this->input->post('exam_group_student_id');
            $insert_array          = array();
            $update_array          = array();
            if (!empty($exam_group_student_id)) {
                foreach ($exam_group_student_id as $exam_group_student_key => $exam_group_student_value) {
                    $attendance_post = $this->input->post('exam_group_student_attendance_' . $exam_group_student_value);
                    if (isset($attendance_post)) {
                        $attendance = $this->input->post('exam_group_student_attendance_' . $exam_group_student_value);
                    } else {
                        $attendance = "present";
                    }
                    $array = array(
                        'exam_group_class_batch_exam_subject_id' => $this->input->post('exam_group_class_batch_exam_subject_id'),
                        'exam_group_class_batch_exam_student_id' => $exam_group_student_value,
                        'attendence'                             => $attendance,
                        'get_marks'                              => $this->input->post('exam_group_student_mark_' . $exam_group_student_value),
                        'get_grade'                              => strtoupper($this->input->post('get_grade_' . $exam_group_student_value)),
                        'note'                                   => $this->input->post('exam_group_student_note_' . $exam_group_student_value),
                        'exam_group_class_batch_exams_id'        => $this->input->post('exam_id_' . $exam_group_student_value),
                        'subject_id'                             => $this->input->post('subject_id'),
                        'main_sub'                               => $this->input->post('main_sub'),
                    );
                    $insert_array[] = $array;
                }
            }
            // print_r($insert_array);die;
            $this->examgroupstudent_model->add_result($insert_array);
            $array = array('status' => '1', 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }

    public function entrymarksgrace()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('exam_group_class_batch_exam_subject_id', 'Subject', 'trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'exam_group_class_batch_exam_subject_id' => form_error('exam_group_class_batch_exam_subject_id'),
            );
            $array = array('status' => 0, 'error' => $data);
            echo json_encode($array);
        } else {
            $exam_group_student_id = $this->input->post('exam_group_student_id');
            $insert_array          = array();
            $update_array          = array();
            if (!empty($exam_group_student_id)) {
                foreach ($exam_group_student_id as $exam_group_student_key => $exam_group_student_value) {
                    // $attendance_post = $this->input->post('exam_group_student_attendance_' . $exam_group_student_value);
                    // if (isset($attendance_post)) {
                    //     $attendance = $this->input->post('exam_group_student_attendance_' . $exam_group_student_value);
                    // } else {
                    //     $attendance = "present";
                    // }
                    $array = array(
                        'exam_group_class_batch_exam_subject_id' => $this->input->post('exam_group_class_batch_exam_subject_id'),
                        'exam_group_class_batch_exam_student_id' => $exam_group_student_value,
                        // 'attendence'                             => $attendance,
                        // 'get_marks'                              => $this->input->post('exam_group_student_mark_' . $exam_group_student_value),
                        // 'note'                                   => $this->input->post('exam_group_student_note_' . $exam_group_student_value),
                        'exam_group_class_batch_exams_id'        => $this->input->post('exam_id_' . $exam_group_student_value),
                        'subject_id'                             => $this->input->post('subject_id'),
                        'main_sub'                               => $this->input->post('main_sub'),
                        'grace_mark'                             => $this->input->post('exam_group_student_grace_' . $exam_group_student_value),
                    );
                    $insert_array[] = $array;
                }
            }
            // print_r($insert_array);die;
            $this->examgroupstudent_model->add_result($insert_array);
            $array = array('status' => '1', 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }

    public function entrymarksupdate()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('get_marks', 'Marks', 'trim|xss_clean');
        $this->form_validation->set_rules('get_grade', 'Grade', 'trim|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'get_marks' => form_error('get_marks'),
                'get_grade' => form_error('get_grade'),
            );
            $array = array('status' => 0, 'error' => $data);
            echo json_encode($array);
        } else {

            $exam_group_class_batch_exam_subject_id = $this->input->post('exam_group_class_batch_exam_subject_id');
            $exam_group_student_id = $this->input->post('exam_group_student_id');
            $insert_array          = array();
            if (!empty($exam_group_class_batch_exam_subject_id)) {
                $i = 0;
                foreach ($exam_group_class_batch_exam_subject_id as $key => $exam_group_value) {

                    $examsubject_id = $this->input->post('exam_group_class_batch_exam_subject_id')[$i];
                    $result = $this->examresult_model->checkrowMarks($examsubject_id, $exam_group_student_id);

                    $attendance_post = $this->input->post('attendance_' . $exam_group_value);
                    if (isset($attendance_post)) {
                        $attendance = $this->input->post('attendance_' . $exam_group_value);
                    } else {
                        $attendance = "present";
                    }

                    $array = array(
                        'exam_group_class_batch_exam_subject_id' => $this->input->post('exam_group_class_batch_exam_subject_id')[$i],
                        'exam_group_class_batch_exam_student_id' => $exam_group_student_id,
                        'attendence'                             => $attendance,
                        'get_marks'                              => $this->input->post('get_marks_' . $exam_group_value),
                        'get_grade'                              => strtoupper($this->input->post('get_grade_' . $exam_group_value)),
                        'note'                                   => $this->input->post('notes_' . $exam_group_value),
                        'exam_group_class_batch_exams_id'        => $this->input->post('exam_id'),
                        'subject_id'                             => $this->input->post('subject_id')[$i],
                        'main_sub'                               => $this->input->post('main_sub'),
                    );
                    $insert_array[] = $array;
                    $i++;
                }
            }
            // print_r($array);die;
            $this->examgroupstudent_model->add_result($insert_array);
            $array = array('status' => '1', 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }

    public function getexam()
    {
        $examgroup_id     = $this->input->post('examgroup_id');
        $data['examList'] = $this->examgroup_model->getExamByExamGroup($examgroup_id);
        $data['userdata'] = $this->customlib->getUserData();

        $data['exam_page'] = $this->load->view('admin/examgroup/_partialexamList', $data, true);

        echo json_encode($data);
    }

    public function connectexams()
    {
        $examgroup_id         = $this->input->post('examgroup_id');
        $data['examList']     = $this->examgroup_model->getExamByExamGroupConnection1($examgroup_id);
        $data['markList']     = $this->examgroup_model->getExamByExamGroupConnection2($examgroup_id);
        $data['examgroup_id'] = $examgroup_id;
        $class               = $this->class_model->get();
        $data['classlist']   = $class;

        $data['exam_page'] = $this->load->view('admin/examgroup/_partialexamListConnection', $data, true);
        echo json_encode($data);
    }
    public function joinexams()
    {
        $examgroup_id         = $this->input->post('examgroup_id');
        $data['examList']     = $this->examgroup_model->getExamByExamGroupConnection1($examgroup_id);
        $data['markList']     = $this->examgroup_model->getExamByExamGroupConnection2($examgroup_id);
        $data['examgroup_id'] = $examgroup_id;
        $class               = $this->class_model->get();
        $data['classlist']   = $class;

        $data['exam_page'] = $this->load->view('admin/examgroup/_partialexamListJoin', $data, true);
        echo json_encode($data);
    }

    public function getExamByID()
    {
        $exam_id = $this->input->post('exam_id');
        $result  = $this->examgroup_model->getExamByID($exam_id);
        if (!empty($result)) {
            $result->date_from = $this->customlib->dateformat($result->date_from);
            $result->date_to   = $this->customlib->dateformat($result->date_to);
        }
        $data['exam'] = $result;
        echo json_encode($data);
    }

    public function getexamSubjects()
    {
        $exam_id                 = $this->input->post('exam_id');
        $class_batch_id          = $this->input->post('class_batch_id');
        $exam_group_ids          = $this->input->post('exam_group_id');
        $data['examgroupDetail'] = $this->examgroup_model->getExamByID($exam_id);
        $data['exam_subjects']   = $this->batchsubject_model->getExamSubjects($exam_id);
        $data['batch_subjects']  = $this->subject_model->get();
        $data['main_subjects']   = $this->subject_model->getsubject();

        $data['exam_id']             = $exam_id;
        $data['exam_subjects_count'] = count($data['exam_subjects']);
        $data['main_subjects_count'] = count($data['main_subjects']);

        $data['batch_subject_dropdown'] = $this->load->view('admin/examgroup/_partialexamSubjectDropdown', $data, true);
        $data['main_subject_dropdown'] = $this->load->view('admin/examgroup/_partialmainSubjectDropdown', $data, true);

        $data['subject_page'] = $this->load->view('admin/examgroup/_partialexamSubjects', $data, true);

        echo json_encode($data);
    }

    public function getajaxdata()
    {
        $dataid = $this->input->post('dataid');
        $parent = $this->input->post('parent');
        $subparent = $this->input->post('subparent');
        $child = $this->input->post('child');
        $val = $this->input->post('val');

        $this->db->where('parent_id', $val);
        $query = $this->db->get('subjects')->result_array();
        // print_r($query);
        $html = '<option value="">Select Sub Subject</option>';
        foreach ($query as $row) {

            $this->db->where('parent_id', $row['id']);
            $query = $this->db->get('subjects')->result_array();
            $sub_code = ($row['code'] != "") ? " (" . $row['code'] . ")" : "";
            $html .= '<option value="' . $row['id'] . '" data-subparent="">' . $row["name"] . ' ' . $sub_code . '' . '</option>';
            if (!empty($query)) {
                foreach ($query as  $value) {
                    $sub_code = ($value['code'] != "") ? " (" . $value['code'] . ")" : "";
                    $html .= '<option value="' . $value['id'] . '" data-subparent="' . $row['id'] . '">' . $value['name'] . ' ' . $sub_code . '' . '</option>';
                }
            }
        }
        $json = ['html' => $html];
        echo json_encode($json);
    }

    public function getSubjectByExam()
    {
        $data                    = array();
        $id                      = $this->input->post('recordid');
        $data['examgroupDetail'] = $this->examgroup_model->getExamByID($id);
        $userdata = $this->customlib->getUserData();
        $role_id  = $userdata["role_id"];
        $data['exam_subjects'] = $this->batchsubject_model->getExamSubjects($id);
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $session                 = $this->session_model->get();
        $data['sessionlist']     = $session;
        $data['current_session'] = $this->sch_current_session;
        $data['subject_page']    = $this->load->view('admin/examgroup/_getSubjectByExam', $data, true);
        echo json_encode($data);
    }

    public function getTeacherRemarkByExam()
    {
        $data                      = array();
        $id                        = $this->input->post('recordid');
        $data['examgroupDetail']   = $this->examgroup_model->getExamByID($id);
        $data['examgroupStudents'] = $this->examgroupstudent_model->searchExamStudentsByExam($id);
        $data['sch_setting']       = $this->sch_setting_detail;
        $data['subject_page']      = $this->load->view('admin/examgroup/_getTeacherRemarkByExam', $data, true);
        echo json_encode($data);
    }

    public function addexamsubject()
    {

        $student_id = '';
        $this->form_validation->set_rules('examgroup_id', $this->lang->line('exam') . " " . $this->lang->line('group'), 'trim|xss_clean');
        $this->form_validation->set_rules('main_sub[]', 'Main Subject', 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject[]', 'Subject', 'trim|required|xss_clean');
        $this->form_validation->set_rules('max_marks[]', 'Maximum Marks', 'trim|required|xss_clean');
        $this->form_validation->set_rules('min_marks[]', 'Minimum Marks', 'trim|required|xss_clean');
        $this->form_validation->set_rules('convertTo[]', 'Convert To', 'trim|xss_clean');
        $this->form_validation->set_rules('exam_group_class_batch_exam_id', $this->lang->line('exam') . " " . $this->lang->line('id'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('rows[]', $this->lang->line('subject'), 'trim|xss_clean');
        $rows = $this->input->post('rows');
        if (isset($rows) && !empty($rows)) {
            foreach ($rows as $row_key => $row_value) {
                if (
                    $this->input->post('subject_' . $row_value) == "" ||
                    // $this->input->post('input_type_'.$row_value) == ""||
                    // $this->input->post('time_from'.$row_value) == ""||
                    // $this->input->post('duration'.$row_value) == ""||
                    // $this->input->post('credit_hours'.$row_value) == ""||
                    // $this->input->post('room_no_'.$row_value) == ""||
                    $this->input->post('max_marks_' . $row_value) == "" ||
                    $this->input->post('min_marks_' . $row_value) == ""
                ) {
                    $this->form_validation->set_rules('parameter', 'parameter', 'trim|xss_clean', array('required' => $this->lang->line('fields_values_required')));
                }
            }
        }

        if ($this->form_validation->run() == false) {

            $msg = array(
                'parameter'                      => form_error('parameter'),
                'main_sub'                       => form_error('main_sub[]'),
                'subject'                        => form_error('subject[]'),
                'examgroup_id'                   => form_error('examgroup_id'),
                'exam_group_class_batch_exam_id' => form_error('exam_group_class_batch_exam_id'),
                'rows'                           => form_error('rows[]'),
                'max_marks'                      => form_error('max_marks[]'),
                'min_marks'                      => form_error('min_marks[]'),
                'convertTo'                      => form_error('convertTo[]'),
            );

            $array = array('status' => '0', 'error' => $msg, 'message' => '');
        } else {
            $insert_array  = array();
            $update_array  = array();
            $subject_array = array();

            $not_be_del = array();

            $main_sub = $this->input->post('main_sub');
            $subject = $this->input->post('subject');
            $input_type = $this->input->post('input_type');
            $max_marks = $this->input->post('max_marks');
            $min_marks = $this->input->post('min_marks');
            $convertTo = $this->input->post('convertTo');
            $rows = $this->input->post('rows');
            $i = 0;
            foreach ($rows as $row_key => $row_value) {
                $update_id = $this->input->post('prev_row[' . $row_value . ']');
                if ($update_id == 0) {
                    // if ($this->input->post('exam_group_class_batch_exam_id') != "" && $this->input->post('subject_' . $row_value) != "" && $this->input->post('date_from_' . $row_value) != "" && $this->input->post('time_from' . $row_value) != "" && $this->input->post('duration' . $row_value) != "" && $this->input->post('max_marks_' . $row_value) != "" && $this->input->post('min_marks_' . $row_value) != "") {
                    $insert_array[] = array(
                        'exam_group_class_batch_exams_id'    => $this->input->post('exam_group_class_batch_exam_id'),
                        'subject_id'                         => $subject[$i],
                        'main_sub'                           => $main_sub[$i],
                        'input_type'                         => $input_type[$i],
                        // 'credit_hours'                    => $this->input->post('credit_hours' . $row_value),
                        // 'date_from'                       => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date_from_' . $row_value))),
                        // 'time_from'                       => $this->input->post('time_from' . $row_value),
                        // 'duration'                        => $this->input->post('duration' . $row_value),
                        // 'room_no'                         => $this->input->post('room_no_' . $row_value),
                        'max_marks'                          => $max_marks[$i],
                        'min_marks'                          => $min_marks[$i],
                        'convertTo'                          => $convertTo[$i],
                    );
                    // }
                } else {
                    $not_be_del[]   = $update_id;
                    $update_array[] = array(
                        'id'                                 => $update_id,
                        'exam_group_class_batch_exams_id'    => $this->input->post('exam_group_class_batch_exam_id'),
                        'subject_id'                         => $subject[$i],
                        'main_sub'                           => $main_sub[$i],
                        'input_type'                         => $input_type[$i],
                        // 'credit_hours'                    => $this->input->post('credit_hours_' . $row_value),
                        // 'date_from'                       => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date_from_' . $row_value))),
                        // 'time_from'                       => $this->input->post('time_from' . $row_value),
                        // 'duration'                        => $this->input->post('duration' . $row_value),
                        // 'room_no'                         => $this->input->post('room_no_' . $row_value),
                        'max_marks'                          => $max_marks[$i],
                        'min_marks'                          => $min_marks[$i],
                        'convertTo'                          => $convertTo[$i],
                    );
                }
                $i++;
            }
            $this->examsubject_model->add($insert_array, $update_array, $not_be_del, $this->input->post('exam_group_class_batch_exam_id'));

            $array = array('status' => '1', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function assign($id)
    {
        if (!$this->rbac->hasPrivilege('fees_group_assign', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Batch');
        $this->session->set_userdata('sub_menu', 'examgroup/index');
        $data['id']        = $id;
        $data['title']     = 'student fees';
        $class             = $this->class_model->get();
        $data['classlist'] = $class;
        $examgroup         = $this->examgroup_model->getExamGroupDetailByID($id);

        $data['examgroup']   = $examgroup;
        $session_result      = $this->session_model->get();
        $data['sessionlist'] = $session_result;

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $data['class_id']     = $this->input->post('class_id');
            $data['section_id']   = $this->input->post('section_id');
            $data['session_id']   = $this->input->post('session_id');
            $data['examgroup_id'] = $this->input->post('examgroup_id');

            $resultlist = $this->examgroupstudent_model->searchExamGroupStudents($data['examgroup_id'], $data['class_id'], $data['section_id'], $data['session_id']);

            $data['resultlist'] = $resultlist;
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/examgroup/assign', $data);
        $this->load->view('layout/footer', $data);
    }

    public function addstudent()
    {
        $this->form_validation->set_rules('exam_group', $this->lang->line('exam') . " " . $this->lang->line('group'), 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'exam_group' => form_error('exam_group'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $array_insert = array();
            $array_delete = array();
            $exam_group   = $this->input->post('exam_group');

            $students_id  = $this->input->post('students_id');
            $all_students = $this->input->post('all_students');
            $students     = array();
            if (!isset($students_id)) {
                $students_id = array();
            }
            if (!empty($all_students)) {
                foreach ($all_students as $all_students_key => $all_students_value) {
                    if (in_array($all_students_value, $students_id)) {

                        $array_insert[] = array(
                            'exam_group_id'      => $exam_group,
                            'student_id'         => $all_students_value,
                            'student_session_id' => $all_students_value,
                        );
                    } else {
                        $array_delete[] = $all_students_value;
                    }
                }
            }

            $this->examgroupstudent_model->add($array_insert, $array_delete, $exam_group);

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }
    // public function ajaxConnectold()
    public function ajaxConnect()
    {
        if (isset($_POST['action'])) {
            if ($this->input->post('action') == "reset") {
                $exam_group_id = $this->input->post('examgroup_id');
                $this->examgroup_model->deleteExamGroupConnection($exam_group_id);
                $array = array('status' => 1, 'error' => '', 'message' => $this->lang->line('update_message'));
                echo json_encode($array);
            } elseif ($this->input->post('action') == "save") {

                $this->form_validation->set_error_delimiters('', '');
                $this->form_validation->set_rules('examgroup_id', $this->lang->line('exam') . " " . $this->lang->line('group'), 'required|trim|xss_clean');
                $this->form_validation->set_rules('class_id', 'class id', 'required|trim|xss_clean');
                $this->form_validation->set_rules('section_id', 'section id', 'required|trim|xss_clean');
                $this->form_validation->set_rules('mark_result[]', 'mark_result id', 'required|trim|xss_clean');

                if ($this->form_validation->run() == false) {
                    $data = array(
                        'examgroup_id' => form_error('examgroup_id'),
                        'class_id' => form_error('class_id'),
                        'section_id' => form_error('section_id'),
                        'mark_result[]' => form_error('mark_result[]'),
                    );
                    $array = array('status' => 0, 'error' => $data, 'message' => 'Class and Section or mark Result field is required');
                    echo json_encode($array);
                } else {
                    $array      = array();
                    $exam_array = $this->input->post('exam[]');
                    $mark_result = $this->input->post('mark_result[]');
                    $class_id = $this->input->post('class_id');
                    $section_id = $this->input->post('section_id');
                    foreach ($mark_result as  $value) {
                        $assignStudents = $this->examgroup_model->checkassignstudent($value)->row_array();
                        // print_r($assignStudents);die;
                        if (empty($assignStudents)) {
                            $array = array('status' => '', 'error' => 0, 'message' => 'No Students Assigned Please Assign');
                            echo json_encode($array);
                        } else {
                            if (!empty($exam_array)) {
                                if (count($exam_array) <= 1) {
                                    $array = array('status' => 0, 'error' => '', 'message' => $this->lang->line('please_select_atleast_two_or_more_exams'));
                                } else {
                                    $examArr = $this->examgroup_model->getsubjectlist2($exam_array);
                                    $array = [];
                                    foreach ($mark_result as $markRow) {

                                        foreach ($examArr as $exRow) {
                                            $array[] = [
                                                'exam_group_class_batch_exams_id' => $markRow,
                                                'main_sub' => $exRow['main_sub'],
                                                'subject_id' => $exRow['subject_id'],
                                                'max_marks' => $exRow['sum_max'],
                                                'min_marks' => 0,
                                            ];
                                        }
                                    }
                                    //$this->examgroup_model->insertsubject($array);
                                }
                                // $student_list=$this->student_model->searchCurrentSessionStudentsByClassSection($class_id,$section_id);

                                // print_r($student_list);
                                //$this->db->query("SELECT exam_group_exam_results.*,exam_group_class_batch_exam_subjects.subject_id,exam_group_class_batch_exam_subjects.main_sub FROM `exam_group_exam_results` inner join exam_group_class_batch_exam_subjects on exam_group_exam_results.exam_group_class_batch_exam_subject_id = exam_group_class_batch_exam_subjects.id where exam_group_exam_results.exam_group_class_batch_exams_id = 46")->getResult();
                            } else {
                                $array = array('status' => 0, 'error' => '', 'message' => $this->lang->line('no_exams_selected'));
                            }

                            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
                            echo json_encode($array);
                        }
                    }
                }
            }
        }
    }
    public function ajaxJoin()
    {
        if (isset($_POST['action'])) {
            if ($this->input->post('action') == "reset") {
                $exam_group_id = $this->input->post('examgroup_id');
                $this->examgroup_model->deleteExamGroupConnection($exam_group_id);
                $array = array('status' => 1, 'error' => '', 'message' => $this->lang->line('update_message'));
                echo json_encode($array);
            } elseif ($this->input->post('action') == "save") {
                $this->form_validation->set_error_delimiters('', '');
                $this->form_validation->set_rules('examgroup_id', $this->lang->line('exam') . " " . $this->lang->line('group'), 'required|trim|xss_clean');
                $this->form_validation->set_rules('class_id', 'class id', 'required|trim|xss_clean');
                $this->form_validation->set_rules('section_id', 'section id', 'required|trim|xss_clean');
                $this->form_validation->set_rules('mark_result[]', 'mark_result id', 'required|trim|xss_clean');
                if ($this->form_validation->run() == false) {
                    $data = array(
                        'examgroup_id' => form_error('examgroup_id'),
                        'class_id' => form_error('class_id'),
                        'section_id' => form_error('section_id'),
                        'mark_result[]' => form_error('mark_result[]'),
                    );
                    $array = array('status' => 0, 'error' => $data, 'message' => 'Class and Section or mark Result field is required');
                    echo json_encode($array);
                } else {

                    $array      = array();
                    $exam_array = $this->input->post('exam[]');
                    $mark_result = $this->input->post('mark_result[]');
                    $class_id = $this->input->post('class_id');
                    $section_id = $this->input->post('section_id');
                    $exam_details = $this->examgroup_model->getExamByID($mark_result[0]);

                    if ($exam_details->exam_result_type == "") {
                        $array = array('status' => '', 'error' => true, 'message' => 'Please Set Exam Result Type of the exam..');
                        echo json_encode($array);
                        return 0;
                    }
                    $pattern = $this->examgroup_model->get_class_exam_pattern($class_id);
                    if ($pattern == "") {
                        $array = array('status' => '', 'error' => 0, 'message' => 'Please set class wise exam pattern..');
                        echo json_encode($array);
                        return 0;
                    } else {
                        $exam_pattern = $pattern['name'];
                    }
                    foreach ($mark_result as  $value) {
                        $this->db->query("delete from exam_group_exam_results where exam_group_class_batch_exams_id = '$value'");
                        $this->db->query("delete from exam_group_class_batch_exam_subjects where 	exam_group_class_batch_exams_id = '$value'");
                        $assignStudents = $this->examgroup_model->checkassignstudentclass($value, $class_id, $section_id, $this->sch_current_session);
                        if (empty($assignStudents)) {
                            $this->examgroupstudent_model->assignStudentFromExam($exam_array[0], $mark_result[0], $class_id, $section_id, $this->sch_current_session);
                        }
                        if (!empty($exam_array)) {
                            if (count($exam_array) <= 1) {
                                $array = array('status' => 0, 'error' => '', 'message' => $this->lang->line('please_select_atleast_two_or_more_exams'));
                            } else {
                                $exam_subjects = $this->batchsubject_model->getExamSubjects($mark_result[0]);
                                if (empty($exam_subjects)) {
                                    $examArr_1 = $this->examgroup_model->getsubjectlist2($exam_array);
                                    $array_1 = [];
                                    foreach ($mark_result as $markRow) {
                                        foreach ($examArr_1 as $exRow_1) {
                                            $array_1[] = [
                                                'exam_group_class_batch_exams_id' => $markRow,
                                                'main_sub' => $exRow_1['main_sub'],
                                                'subject_id' => $exRow_1['subject_id'],
                                                'max_marks' => $exRow_1['sum_max'],
                                                'min_marks' => 0,
                                                'convertTo' => $exRow_1['sum_convertTo'],
                                            ];
                                        }
                                    }
                                    $this->examgroup_model->insertsubject($array_1);
                                }
                            }
                            if ($exam_pattern == "Evaluation") {
                                $this->joinExam($exam_array, $mark_result[0], $class_id, $section_id);
                                $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
                            } else {
                                $array = array('status' => 'error', 'error' => true, 'message' => 'Class Exam Pattern does not need any join exam');
                                echo json_encode($array);
                                return 0;
                            }
                        } else {
                            $array = array('status' => 0, 'error' => '', 'message' => $this->lang->line('no_exams_selected'));
                        }
                        echo json_encode($array);
                    }
                }
            }
        }
    }

    public function joinExam($exam_array, $mark_result, $class_id, $section_id)
    {
        $array      = array();
        $session_id = $this->setting_model->getCurrentSession();
        $studentList = $this->examgroupstudent_model->searchExamStudents("", $mark_result, $class_id, $section_id, $session_id);
        $exam_subjects = $this->batchsubject_model->getExamSubjects($mark_result);
        
        $exam_details = $this->examgroup_model->getExamByID($mark_result);
        $exam_grades = $this->grade_model->getByExamType($exam_details->exam_group_type, "5P");
        foreach ($studentList as $student) {
            //if($student->student_session_id == 3139)
           // {
            $exam_student_1 = $this->examgroupstudent_model->searchExamStudentsByStudentSession($exam_array[0], $class_id, $section_id, $session_id, $student->student_session_id);
            $exam_student_2 = $this->examgroupstudent_model->searchExamStudentsByStudentSession($exam_array[1], $class_id, $section_id, $session_id, $student->student_session_id);
            $exam_mark1 = array();
            $exam_mark2 = array();
            if (!empty($exam_student_1)) {
                $exam_mark1 = $this->examresult_model->getStudentResultByExam($exam_array[0], $exam_student_1[0]->exam_group_class_batch_exam_student_id);
            }
            if (!empty($exam_student_2)) {
                $exam_mark2 = $this->examresult_model->getStudentResultByExam($exam_array[1], $exam_student_2[0]->exam_group_class_batch_exam_student_id);
            }
            $total_mark = 0;
            $total_max_mark = 0;
            
            foreach ($exam_subjects as $subjects) {
                $mark_1 = $this->getsubjectmark($subjects->subject_id, $exam_mark1);
                $mark_2 = $this->getsubjectmark($subjects->subject_id, $exam_mark2);
                $main_subject = $this->subject_model->getSubjectByID($subjects->main_sub);
                
                $subjectType = $exam_details->exam_result_type;
                    $result = $this->process_mark($mark_1, $mark_2, $subjectType, $exam_grades, $subjects);
                    $array[] = [
                        'exam_group_class_batch_exam_subject_id' => $subjects->id,
                        'exam_group_class_batch_exam_student_id' => $student->exam_group_class_batch_exam_student_id,
                        'attendence' => $result['attendance'],
                        'get_marks' => $result['get_marks'],
                        'note' => '',
                        'exam_group_class_batch_exams_id' => $mark_result,
                        'subject_id' => $subjects->subject_id,
                        'main_sub' => $subjects->main_sub,
                        'final_mark' => $result['final_mark'],
                    ];
                    if ($main_subject['SubjectType'] == "Mark") {
                        
                        $total_mark += $result['get_marks'];
                        $total_max_mark += $result['max_marks'];
                    }
            }
            
            if ($total_mark > 0) {
                $total_percentage = ($total_mark * 100) / $total_max_mark;
            } else {
                $total_percentage = 0;
            }
            $final_grade = $this->grade_model->get_Grade($exam_grades, $total_percentage);
            $final_remark = $this->grade_model->get_Remark($exam_grades, $final_grade);
            $id = $student->exam_group_class_batch_exam_student_id;
            $data = array(
                'total_mark' => $total_mark,
                'max_mark' => $total_max_mark,
                'grade' => $final_grade,
                'remarks' => $final_remark,
                'exam_group_class_batch_exam_id' => $mark_result,
            );
//            echo "<pre>";print_r($data);
            $this->examgroupstudent_model->updateExamStudent_id($data, $id);
        //}
        }
        //echo "<pre>";print_r($array);
        $this->examgroup_model->insertstudentmarks($array);
        //check if it is a final process
        if ($exam_details->exam_srno == 3) {
            $this->process_finalresult_primary($class_id, $section_id);
            
        }
    }
    public function process_mark($mark_1, $mark_2, $subjectType, $exam_grades, $subject)
    {
        $attendance = "";
        $get_marks = 0;
        $final = "";
        $max_marks = 0;
        $v_mode=0;
        if($this->input->post('verification_mode')!='')
        {
            if($this->input->post('verification_mode')==1)
            {
                $v_mode=1;
            }
        }
        
        $note_status = array("NEW", "FD", "LEFT","NA");
        if ($mark_1['exist'] && $mark_2['exist']) {
             //if (in_array(trim(strtoupper($mark_1['note'])) == "NEW" && trim(strtoupper($mark_2['note'])) == "NEW") {
            if (in_array(trim(strtoupper($mark_1['note'])), $note_status) && in_array(trim(strtoupper($mark_2['note'])), $note_status)) {
                $attendance = "";
                $get_marks  = 0;
                $max_marks = 0;
             } elseif(in_array(trim(strtoupper($mark_1['note'])), $note_status) && (in_array(trim(strtoupper($mark_2['note'])), $note_status)==false) ) {
                $attendance = $mark_2['attendance'];
                $get_marks  = $mark_2['get_marks'];
                $max_marks  = $mark_2['max_marks'];
            } elseif((in_array(trim(strtoupper($mark_1['note'])), $note_status)==false) && in_array(trim(strtoupper($mark_2['note'])), $note_status)) {
                $attendance = $mark_1['attendance'];
                $get_marks  = $mark_1['get_marks'];
                $max_marks  = $mark_1['max_marks'];    
            } elseif(trim(strtoupper($mark_1['note']))=='AB' && trim(strtoupper($mark_2['note']))=='AB') {
                $attendance = "absent";
                $get_marks  = 0;
                $max_marks = 0;   
            } elseif(trim(strtoupper($mark_1['note']))=='AB' && trim(strtoupper($mark_2['note']))=='') {
                $attendance = "present";
                $get_marks  = $mark_2['get_marks'];
                $max_marks  = $mark_2['max_marks'];          
            } elseif(trim(strtoupper($mark_1['note']))=='' && trim(strtoupper($mark_2['note']))=='AB') {
                $attendance = "present";
                $get_marks  = $mark_1['get_marks'];
                $max_marks  = $mark_1['max_marks'];
            } elseif(trim(strtoupper($mark_1['note']))=='NA' && trim(strtoupper($mark_2['note']))=='') {
                $attendance = "present";
                $get_marks  = $mark_2['get_marks'];
                $max_marks  = $mark_2['max_marks'];          
            } elseif(trim(strtoupper($mark_1['note']))=='' && trim(strtoupper($mark_2['note']))=='NA') {
                $attendance = "present";
                $get_marks  = $mark_1['get_marks'];
                $max_marks  = $mark_1['max_marks'];
            } elseif ($mark_1['attendance'] == "present" && $mark_2['attendance'] == "present") {
                $attendance = "present";
                $get_marks  = $mark_1['get_marks'] + $mark_2['get_marks'];
                $max_marks  = $mark_1['max_marks'] + $mark_2['max_marks'];
            } elseif ($mark_1['attendance'] == "present" && $mark_2['attendance'] == "absent") {
                $attendance = "present";
                $get_marks  = $mark_1['get_marks'];
                $max_marks  = $mark_1['max_marks'];
            } elseif ($mark_1['attendance'] == "absent" && $mark_2['attendance'] == "present") {
                $attendance = "present";
                $get_marks  = $mark_2['get_marks'];
                $max_marks  = $mark_2['max_marks'];
            } else {
                $attendance = "absent";
                $get_marks  = 0;
                $max_marks = 0;
            }
        } elseif ($mark_1['exist']) {
            if (in_array(trim(strtoupper($mark_1['note'])), $note_status)) {
                $attendance = "";
                $get_marks  = 0;
                $max_marks = 0;
            } elseif(trim(strtoupper($mark_1['note']))=='AB') {
                $attendance = "absent";
                $get_marks  = 0;
                $max_marks = 0;   
            } else
            {
                $attendance = $mark_1['attendance'];
                $get_marks  = $mark_1['get_marks'];
                $max_marks  = $mark_1['max_marks'];
            }
        } elseif ($mark_2['exist']) {
            if (in_array(trim(strtoupper($mark_2['note'])), $note_status)) {
                $attendance = "";
                $get_marks  = 0;
                $max_marks = 0;
            } elseif(trim(strtoupper($mark_2['note']))=='AB') {
                $attendance = "absent";
                $get_marks  = 0;
                $max_marks = 0;   
            } 
            else
            {
                $attendance = $mark_2['attendance'];
                $get_marks  = $mark_2['get_marks'];
                $max_marks  = $mark_2['max_marks'];
            }            
        }
        else
        {
            $attendance = "";
            $get_marks  = 0;
            $max_marks = 0;
        }
        if ($get_marks > 0) {
            $total_percentage = ($get_marks * 100) / $max_marks;
        } else {
            $total_percentage = 0;
        }
        if($attendance == "")
        {
            $final = "-";
        }
        elseif($attendance == "absent")
        {
            $final = "Ab";
        }
        else
        {
            if($v_mode==1)
            {$final = $get_marks."/".$max_marks."(".$this->grade_model->get_Grade($exam_grades, $total_percentage).")";}
            else
            {$final = $this->grade_model->get_Grade($exam_grades, $total_percentage);}
        }
        // if ($subjectType == "Mark") {
        //     if (empty($subject->convertTo) || $subject->convertTo == 0) {
        //         $final = $get_marks;
        //     } else {
        //         $final = round(($get_marks / $max_marks) * $subject->convertTo, 2);
        //         $get_marks = $final;
        //     }
        // } elseif ($subjectType == "Grade") {
        // }
        $array = array('attendance' => $attendance, 'get_marks' => $get_marks, 'max_marks' => $max_marks, 'final_mark' => $final);
        return $array;
    }
    function getsubjectmark($subject_id, $exam_mark1)
    {
        $entry_exist_1 = 0;
        $get_marks_1 = 0;
        $attendence_1 = "";
        $max_marks = 0;
        $note= "";
        foreach ($exam_mark1 as $ex) {
            if ($ex->subject_id == $subject_id) {
        
                $get_marks_1 = $ex->get_marks;
                $attendence_1 = $ex->attendence;
                $entry_exist_1 = 1;
                $max_marks = $ex->max_marks;
                $note = $ex->note;
                break;
                
            }
           
        }
        $result = array(
            "exist" => $entry_exist_1,
            "get_marks" => $get_marks_1,
            "max_marks" => $max_marks,
            "attendance" => $attendence_1,
            "note" => $note,
        );

        return $result;
    }
    public function process_final_result_evaluation($class_id,$section_id,$reset_subject,$vertification_mode)
    {

        $exams = $this->examgroup_model->getexam_onlyentry($class_id, $section_id);
        $exams_count = sizeof($exams);
        if($exams_count!=6)
        {
            $exam_id = "";
            foreach($exams as $ex)
            {
                if($exam_id=="")
                {$exam_id = $ex['exam_group_class_batch_exam_id'];}
                else
                {$exam_id .= ",".$ex['exam_group_class_batch_exam_id'];}

            }
            echo "<pre>";            
            $exam_id = "(".$exam_id.")";
            $result=$this->db->query("SELECT exam_group_class_batch_exams.exam,exam_groups.name FROM exam_group_class_batch_exams INNER JOIN exam_groups ON exam_groups.id = exam_group_class_batch_exams.exam_group_id where exam_group_class_batch_exams.id in ".$exam_id)->result_array();
            echo "Error..Exam Count Error..";        
            print_r($result);

            $array = array('status' => '', 'error' => 1, 'message' => 'Invalid Exam Count');
            return json_encode($array);            
        }
        $exam_ids = array();
        foreach($exams as $exm)
        {
            $exam_ids[] = $exm['exam_group_class_batch_exam_id'];
        }
        $exams_evaluation=$this->examgroup_model->getexam_evaluation_from_exams($exam_ids);
        if(sizeof($exams_evaluation)!=3)
        {
            $exam_id = "";
            foreach($exams as $ex)
            {
                if($exam_id=="")
                {$exam_id = $ex->exam_group_class_batch_exam_id;}
                else
                {$exam_id .= ",".$ex->exam_group_class_batch_exam_id;}

            }
            echo "<pre>";            
            $exam_id = "(".$exam_id.")";
            $result=$this->db->query("SELECT exam_group_class_batch_exams.exam,exam_groups.name FROM exam_group_class_batch_exams INNER JOIN exam_groups ON exam_groups.id = exam_group_class_batch_exams.exam_group_id where exam_group_class_batch_exams.id in ".$exam_id)->result_array();
            echo "Error..Exam Result Count Error..";        
            print_r($result);            
            $array = array('status' => '', 'error' => 1, 'message' => 'Invalid Exam Count');
            return json_encode($array);            
        }
        $rw=0;
        //$this->process_finalresult_primary_worksheet_summary($class_id, $section_id);die();        
        foreach($exams_evaluation as $mark_result)
        {
            $array      = array();
            $exam_array = array();
            $exam_id_array = array();
            $exam_array[] = $exams[$rw];
            $exam_id_array[] = $exams[$rw]['exam_group_class_batch_exam_id'];
            $exam_array[] = $exams[$rw+1];
            $exam_id_array[] = $exams[$rw+1]['exam_group_class_batch_exam_id'];
            $class_id = $class_id;
            $section_id = $section_id;
            $exam_details = $this->examgroup_model->getExamByID($mark_result['exam_group_class_batch_exam_id']);    
            $pattern = $this->examgroup_model->get_class_exam_pattern($class_id);
            $exam_pattern = $pattern['name'];
            $value=$mark_result['exam_group_class_batch_exam_id'];
            $this->db->query("delete from exam_group_exam_results where exam_group_class_batch_exams_id = '$value'  and exam_group_exam_results.exam_group_class_batch_exam_student_id in (select id from exam_group_class_batch_exam_students where exam_group_class_batch_exam_students.student_session_id in (select id from  student_session where student_session.class_id = '$class_id' and student_session.section_id = '$section_id' and session_id = '$this->sch_current_session'))");
            if($reset_subject==1)
            {
                $this->db->query("delete from exam_group_exam_results where exam_group_class_batch_exams_id = '$value'");
                $this->db->query("delete from exam_group_class_batch_exam_subjects where 	exam_group_class_batch_exams_id = '$value'");
            }
                $assignStudents = $this->examgroup_model->checkassignstudentclass($value, $class_id, $section_id, $this->sch_current_session);
                if (empty($assignStudents)) {
                    $this->examgroupstudent_model->assignStudentFromExam($exam_array[0]['exam_group_class_batch_exam_id'], $value, $class_id, $section_id, $this->sch_current_session);
                }
                if (!empty($exam_array)) {
                        $exam_subjects = $this->batchsubject_model->getExamSubjects($value);
                        if (empty($exam_subjects)) {
                            $examArr_1 = $this->examgroup_model->getsubjectlist2($exam_id_array);
                            $array_1 = [];
                                foreach ($examArr_1 as $exRow_1) {
                                    $array_1[] = [
                                        'exam_group_class_batch_exams_id' => $value,
                                        'main_sub' => $exRow_1['main_sub'],
                                        'subject_id' => $exRow_1['subject_id'],
                                        'max_marks' => $exRow_1['sum_max'],
                                        'min_marks' => 0,
                                        'convertTo' => $exRow_1['sum_convertTo'],
                                    ];
                                }
                            
                            $this->examgroup_model->insertsubject($array_1);
                        }
                        
                    if ($exam_pattern == "Evaluation") {
                        $this->joinExam($exam_id_array, $value, $class_id, $section_id);
                        
                    }
                }
            $rw=$rw+2;
        }
        $this->process_finalresult_primary_worksheet_summary($class_id, $section_id);
        $array = array('status' => 'success', 'error' => '', 'message' => "Successfully Processed ...");
        return json_encode($array);
    }
    public function process_finalresult_primary($class_id, $section_id)
    {
        $data = array();
        $students = $this->student_model->getStudentByClassSectionAll($class_id, $section_id);
        foreach ($students as $student) {
            $exams = $this->examgroupstudent_model->studentExamsResultOnly($student['id']);
            if(!empty($exams))
            {
                break;
            }
        }
        if(empty($exams))
        {
            echo "Error..Exam Empty Error..";        
            die();
        }
        $exams_count = sizeof($exams);
        if($exams_count!=3)
        {
            $exam_id = "";
            foreach($exams as $ex)
            {
                if($exam_id=="")
                {$exam_id = $ex->exam_group_class_batch_exam_id;}
                else
                {$exam_id .= ",".$ex->exam_group_class_batch_exam_id;}

            }
            echo "<pre>";            
            $exam_id = "(".$exam_id.")";
            $result=$this->db->query("SELECT exam_group_class_batch_exams.exam,exam_groups.name FROM exam_group_class_batch_exams INNER JOIN exam_groups ON exam_groups.id = exam_group_class_batch_exams.exam_group_id where exam_group_class_batch_exams.id in ".$exam_id)->result_array();
            echo "Error..Exam Count Error..";        
            print_r($result);
            die();
        }
        $exam_details = $this->examgroup_model->getExamByID($exams[0]->exam_group_class_batch_exam_id);
        $exam_grades = $this->grade_model->getByExamType($exam_details->exam_group_type, "5P");
        foreach ($students as $student) {

            
            $student_session_id = $student['id'];
            $ev1_id = $exams[0]->exam_group_class_batch_exam_id;
            $ev2_id = $exams[1]->exam_group_class_batch_exam_id;
            $ev3_id = $exams[2]->exam_group_class_batch_exam_id;
            $student_1 = $this->db->query("select * from exam_group_class_batch_exam_students where student_session_id = '$student_session_id' and exam_group_class_batch_exam_id  = '$ev1_id'")->row_array();
            $student_2 = $this->db->query("select * from exam_group_class_batch_exam_students where student_session_id = '$student_session_id' and exam_group_class_batch_exam_id  = '$ev2_id'")->row_array();
            $student_3 = $this->db->query("select * from exam_group_class_batch_exam_students where student_session_id = '$student_session_id' and exam_group_class_batch_exam_id  = '$ev3_id'")->row_array();

            
            $total = 0;
            $max_mark = 0;
            if (!empty($student_1)) {
                $total += $student_1['total_mark'];
                $max_mark += $student_1['max_mark'];

            }
            if (!empty($student_2)) {
                $total += $student_2['total_mark'];
                $max_mark += $student_2['max_mark'];

            }
            if (!empty($student_3)) {
                $total += $student_3['total_mark'];
                $max_mark += $student_3['max_mark'];
            }
            if ($total > 0) {
                $total_percentage = ($total * 100) / $max_mark;
            } else {
                $total_percentage = 0;
            }
            
            $final_grade = $this->grade_model->get_Grade($exam_grades, $total_percentage);
            $final_remark = $this->grade_model->get_Remark($exam_grades, $final_grade);

            $data = array(
                'id' => $student_session_id,
                'total_mark' => $total,
                'max_mark' => $max_mark,
                'percentage' => round($total_percentage, 2),
                'grade' => $final_grade,
                'remark' => $final_remark,
            );
            $this->studentsession_model->updateById($data);
            }
    }
    public function process_finalresult_primary_worksheet_summary($class_id, $section_id)
    {
        $data = array();
        $students = $this->student_model->getStudentByClassSectionAll($class_id, $section_id);
        // echo "<pre>";print_r($students);die();
        foreach ($students as $student) {
            $exams = $this->examgroupstudent_model->studentExamsOnlyWorksheet($student['id']);
            //echo "<pre>";print_r($exams);die();
            if(!empty($exams))
            {
                break;
            }
        }
        if(empty($exams))
        {
            echo "Error..Exam Empty Error..";        
            die();
        }
        //echo "<pre>qqq";print_r($exams);
        if(!empty($exams)) {
        foreach($exams as $exam)
        {
            $exam_id = $exam->exam_group_class_batch_exam_id;

            $exam_details = $this->examgroup_model->getExamByID($exam_id);
            $exam_grades = $this->grade_model->getByExamType($exam_details->exam_group_type, "5P");

            $exam_group_id = 0;
            $session_id = $this->setting_model->getCurrentSession();
            $studentList = $this->examgroupstudent_model->searchExamStudents($exam_group_id, $exam_id, $class_id, $section_id, $session_id);
            foreach ($studentList as $student_key => $student_value) {
                
                $student_session_id = $student_value->student_session_id;
                $studentList[$student_key]->subject_results = $this->examresult_model->getStudentResultByExam($exam_id, $student_value->exam_group_class_batch_exam_student_id);
                $max_mark=0;
                $total_mark=0;
                $this->db->where('exam_id', $exam_id)->where('student_session_id', $student_session_id)->delete('student_worksheet_marks');
                $mark_exist = 0;
                $last_note = "";
                foreach($studentList[$student_key]->subject_results as $marklist)
                {
                    $mark = $marklist->get_marks;
                    if($mark>0)
                    {
                        $total_mark = $total_mark + $mark;
                        ++$mark_exist;
                    }
                    $max_mark = $max_mark + $marklist->max_marks;
                    $last_note = $marklist->note;
                }
                if($mark_exist > 0) {
                $total_percentage = ($total_mark/$max_mark)*100;
                $final_grade = $this->grade_model->get_Grade($exam_grades, $total_percentage);
                }
                else
                {
                    
                    if(strtoupper($last_note)=="AB")
                    {
                        $total_percentage = "-";
                        $final_grade = "AB";
                    }
                    else
                    {
                        $total_percentage = "-";
                        $final_grade = "-";
                    }
                }
                //$final_remark = $this->grade_model->get_Remark($exam_grades, $final_grade);                    
                $data_st = array(
                    'exam_id' => $exam_id,
                    'student_session_id' => $student_session_id,
                    'total_marks' => $total_mark,
                    'max_marks' => $max_mark,
                    'mark_percentage' => $total_percentage=='-' ? "-" : round($total_percentage, 2),
                    'grade' => $final_grade,
                );
                $this->db->insert('student_worksheet_marks', $data_st);
                
            }
        }   
        }     
    }
    public function ajaxConnectForm()
    {
        if (isset($_POST['action'])) {
            if ($this->input->post('action') == "reset") {
                $exam_group_id = $this->input->post('examgroup_id');
                $this->examgroup_model->deleteExamGroupConnection($exam_group_id);
                $array = array('status' => 1, 'error' => '', 'message' => $this->lang->line('update_message'));
                echo json_encode($array);
            } elseif ($this->input->post('action') == "save") {

                $this->form_validation->set_error_delimiters('', '');
                $this->form_validation->set_rules('examgroup_id', $this->lang->line('exam') . " " . $this->lang->line('group'), 'required|trim|xss_clean');

                if ($this->form_validation->run() == false) {
                    $data = array(
                        'examgroup_id' => form_error('examgroup_id'),
                    );
                    $array = array('status' => 0, 'error' => $data);
                    echo json_encode($array);
                } else {
                    $array      = array();
                    $exam_array = $this->input->post('exam[]');
                    $mark_result = $this->input->post('mark_result[]');
                    if (!empty($exam_array)) {
                        if (count($exam_array) <= 1) {
                            $array = array('status' => 0, 'error' => '', 'message' => $this->lang->line('please_select_atleast_two_or_more_exams'));
                        } else {

                            $exam_group = $this->examgroup_model->verifyExamConnection($exam_array);

                            if ($exam_group['no_record']) {
                                if (count($exam_group['exam_subject_array']) != count($exam_array)) {
                                    $array          = array('status' => 0, 'error' => '', 'message' => $this->lang->line('please_check_exam_subjects'));
                                    $insert_success = 0;
                                } else {

                                    reset($exam_group['exam_subject_array']);
                                    $result = key($exam_group['exam_subject_array']);

                                    $insert_success = 1;
                                    foreach ($exam_group['exam_subject_array'] as $exam_subject_key => $exam_subject_value) {

                                        $compair_result = $this->compare_multi_Arrays($exam_group['exam_subject_array'][$result], $exam_group['exam_subject_array'][$exam_subject_key]);

                                        if ($compair_result) {

                                            if (!empty($compair_result['more']) || !empty($compair_result['less']) || !empty($compair_result['diff'])) {
                                                $array          = array('status' => 0, 'error' => '', 'message' => $this->lang->line('please_check_exam_subjects'));
                                                $insert_success = 0;
                                                break;
                                            }
                                        } else {
                                            $array          = array('status' => 0, 'error' => '', 'message' => $this->lang->line('please_check_exam_subjects'));
                                            $insert_success = 0;
                                            break;
                                        }
                                    }
                                }
                            } else {
                                $array          = array('status' => 0, 'error' => '', 'message' => $this->lang->line('exams_subject_may_be_empty_please_check_exam_subjects'));
                                $insert_success = 0;
                            }

                            if ($insert_success) {
                                $insert_array  = array();
                                $exam_group_id = $this->input->post('examgroup_id');
                                if (!empty($exam_array)) {
                                    foreach ($exam_array as $exam_key => $exam_value) {

                                        $insert_array[] = array(
                                            'exam_group_id'                   => $exam_group_id,
                                            'exam_group_class_batch_exams_id' => $exam_value,
                                            'exam_weightage'                  => $this->input->post('exam_' . $exam_value),
                                        );
                                    }
                                }

                                $this->examgroup_model->connectExam($insert_array, $exam_group_id);
                                $array = array('status' => 1, 'error' => '', 'message' => $this->lang->line('exam_connected_successfully'));
                            }
                        }
                    } else {
                        $array = array('status' => 0, 'error' => '', 'message' => $this->lang->line('no_exams_selected'));
                    }

                    echo json_encode($array);
                }
            }
        }
    }
    public function compare_multi_Arrays($array1, $array2)
    {
        if (!empty($array1) && !empty($array2)) {
            $result = array("more" => array(), "less" => array(), "diff" => array());
            foreach ($array1 as $k => $v) {
                if (is_array($v) && isset($array2[$k]) && is_array($array2[$k])) {
                    $sub_result = compare_multi_Arrays($v, $array2[$k]);

                    foreach (array_keys($sub_result) as $key) {
                        if (!empty($sub_result[$key])) {
                            $result[$key] = array_merge_recursive($result[$key], array($k => $sub_result[$key]));
                        }
                    }
                } else {
                    if (isset($array2[$k])) {
                        if ($v !== $array2[$k]) {
                            $result["diff"][$k] = array("from" => $v, "to" => $array2[$k]);
                        }
                    } else {
                        $result["more"][$k] = $v;
                    }
                }
            }
            foreach ($array2 as $k => $v) {
                if (!isset($array1[$k])) {
                    $result["less"][$k] = $v;
                }
            }
            return $result;
        }
        return false;
    }

    public function getExamGroupByClassSection()
    {
        $exam_group = array();
        $class_id   = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        $session_id = $this->input->post('session_id');
        $exam_group = $this->examgroup_model->getExamGroupByClassSection($class_id, $section_id, $session_id);
        echo json_encode(array('status' => 1, 'exam_group' => $exam_group));
    }

    public function entrystudents()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('exam_group_class_batch_exam_id', $this->lang->line('exam'), 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {

            $data = array(
                'exam_group_class_batch_exam_id' => form_error('exam_group_class_batch_exam_id'),
            );

            $array = array('status' => 0, 'error' => $data);
            echo json_encode($array);
        } else {
            $check_alreay_inserted_students = array();
            $state                          = 1;
            $exam_group_class_batch_exam_id = $this->input->post('exam_group_class_batch_exam_id');
            $student_session                = $this->input->post('student_session_id');
            $all_students                   = $this->input->post('all_students');
            $insert_array                   = array();
            if (isset($student_session) && !empty($student_session)) {
                foreach ($student_session as $student_key => $student_value) {
                    $check_alreay_inserted_students[] = $this->input->post('student_' . $student_value);
                    $insert_array[]                   = array(
                        'exam_group_class_batch_exam_id' => $exam_group_class_batch_exam_id,
                        'student_id'                     => $this->input->post('student_' . $student_value),
                        'student_session_id'             => $student_value,
                    );
                }
            }

            $this->examstudent_model->add_student($insert_array, $exam_group_class_batch_exam_id, $all_students);
            $array = array('status' => '1', 'error' => '', 'message' => $this->lang->line('success_message'));

            echo json_encode($array);
        }
    }

    public function saveexamremark()
    {
        $students = $this->input->post('exam_group_class_batch_exam_student');
        if (!empty($students)) {
            $batch_update_array = array();
            foreach ($students as $student_key => $student_value) {
                $update_array = array(
                    'id'             => $student_value,
                    'teacher_remark' => $this->input->post('remark_' . $student_value),
                );
                $batch_update_array[] = $update_array;
            }
            $this->examgroupstudent_model->updateExamStudent($batch_update_array);
        }
        $array = array('status' => '1', 'error' => '', 'message' => $this->lang->line('success_message'));
        echo json_encode($array);
    }



    public function exam_type()
    {
        if (!$this->rbac->hasPrivilege('exam_type', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'Examinations/exam_type');
        $data['title'] = 'Exam Type List';

        $examtype_result      = $this->examgroup_model->getexamType();
        $data['examtype_result'] = $examtype_result;
        $this->form_validation->set_rules('exam_type', 'Exam Type', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/examgroup/exam_type', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'name' => $this->input->post('exam_type'),
                'key' => strtolower(str_replace(" ", "_", $this->input->post('exam_type'))),
                'status' => $this->input->post('status'),
            );
            $this->examgroup_model->add_exam_type($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/examgroup/exam_type');
        }
    }

    public function edit_examType($id)
    {
        if (!$this->rbac->hasPrivilege('exam_type', 'can_edit')) {
            access_denied();
        }
        $data['title']       = 'Exam Type List';
        $examtype_result      = $this->examgroup_model->getexamType();
        $data['examtype_result'] = $examtype_result;
        $data['title']       = 'Edit Exam Type';
        $data['id']          = $id;
        $examtype_array             = $this->examgroup_model->getexamType($id);
        $data['examtype_array']     = $examtype_array;
        $this->form_validation->set_rules('exam_type', 'Exam Type', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/examgroup/examtypeEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id'      => $id,
                'name' => $this->input->post('exam_type'),
                'status' => $this->input->post('status'),
            );
            $this->examgroup_model->add_exam_type($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/examgroup/exam_type');
        }
    }

    public function delete_examType($id)
    {
        if (!$this->rbac->hasPrivilege('exam_type', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Exam Type List';
        $this->examgroup_model->remove_examType($id);

        redirect('admin/examgroup/exam_type');
    }



    public function exam_category()
    {
        if (!$this->rbac->hasPrivilege('exam_category', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'Examinations/exam_category');
        $data['title'] = 'Exam Category Master List';


        $examtype_result      = $this->examgroup_model->getexamType();
        $data['examtype_result'] = $examtype_result;

        $examcategory_result      = $this->examgroup_model->getexamcategory();
        $data['examcategory_result'] = $examcategory_result;
        $this->form_validation->set_rules('exam_category', 'Exam Categry', 'trim|required|xss_clean');
        $this->form_validation->set_rules('group_type_id', 'Exam Type Group', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/examgroup/exam_category', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'name' => $this->input->post('exam_category'),
                'group_type_id' => $this->input->post('group_type_id'),
                'status' => $this->input->post('status'),
            );
            $this->examgroup_model->add_exam_categry($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/examgroup/exam_category');
        }
    }

    public function edit_examcategory($id)
    {
        if (!$this->rbac->hasPrivilege('exam_category', 'can_edit')) {
            access_denied();
        }
        $data['title']       = 'Exam Category Master List';
        $examtype_result      = $this->examgroup_model->getexamType();
        $data['examtype_result'] = $examtype_result;

        $examcategory_result      = $this->examgroup_model->getexamcategory();
        $data['examcategory_result'] = $examcategory_result;

        $data['title']       = 'Edit Exam Category Master';
        $data['id']          = $id;
        $examcategory_array             = $this->examgroup_model->getexamcategory($id);
        $data['examcategory_array']     = $examcategory_array;
        $this->form_validation->set_rules('exam_category', 'Exam Category', 'trim|required|xss_clean');
        $this->form_validation->set_rules('group_type_id', 'Exam Type Group', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/examgroup/examcategoryEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id'      => $id,
                'name' => $this->input->post('exam_category'),
                'group_type_id' => $this->input->post('group_type_id'),
                'status' => $this->input->post('status'),
            );
            $this->examgroup_model->add_exam_categry($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/examgroup/exam_category');
        }
    }

    public function delete_examcategory($id)
    {
        if (!$this->rbac->hasPrivilege('exam_category', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Exam Type List';
        $this->examgroup_model->remove_examCategory($id);

        redirect('admin/examgroup/exam_category');
    }

    public function exam_pattern($id = "")
    {
        if (!$this->rbac->hasPrivilege('exam_pattern', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'Examinations/exam_pattern');
        $data['title'] = 'Exam Pattern List';

        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $examscheme                 = $this->examgroup_model->getexam_scheme();
        $data['examschemelist']     = $examscheme;
        $data['current_session'] = $this->sch_current_session;
        if ($id != "") {
            $data['update']      = $this->examgroup_model->getclass_wise_exam_pattern($id);
        }
        // $exampattern_result      = $this->examgroup_model->getclass_wise_exam_pattern();
        $exampattern_result      = $this->examgroup_model->getclass_exam_pattern();
        $data['exampattern_result'] = $exampattern_result;

        $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
        //$this->form_validation->set_rules('section_id', 'Section', 'trim|required|xss_clean');
        $this->form_validation->set_rules('exam_pattern', 'Exam Pattern', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/examgroup/exam_pattern', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id'      => $this->input->post('id'),
                'class_id' => $this->input->post('class_id'),
                'session_id' => $this->input->post('session_id'),
                'exam_pattern' => $this->input->post('exam_pattern'),
            );

            $this->examgroup_model->add_exam_pattern($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/examgroup/exam_pattern');
        }
    }

    public function delete_exampattern($id)
    {
        if (!$this->rbac->hasPrivilege('exam_pattern', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Exam Pattern List';
        $this->examgroup_model->remove_exampattern($id);

        redirect('admin/examgroup/exam_pattern');
    }

    public function exam_scheme($id = "")
    {
        if (!$this->rbac->hasPrivilege('exam_scheme', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'Examinations/exam_scheme');
        $data['title'] = 'Exam Pattern List';
        if ($id != "") {
            $data['update']      = $this->examgroup_model->getexam_scheme($id);
        }

        $examscheme_result      = $this->examgroup_model->getexam_scheme();
        $data['examscheme_result'] = $examscheme_result;

        $this->form_validation->set_rules('name', 'Exam Scheme', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/examgroup/exam_scheme', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id' => $this->input->post('id'),
                'name' => $this->input->post('name'),
            );
            // print_r($data);
            $this->examgroup_model->add_exam_scheme($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/examgroup/exam_scheme');
        }
    }

    public function delete_examscheme($id)
    {
        if (!$this->rbac->hasPrivilege('exam_scheme', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Exam Scheme List';
        $this->examgroup_model->remove_examscheme($id);

        redirect('admin/examgroup/exam_scheme');
    }
    public function test()
    {
        $class_id = 4;
        $section_id = 1;
        $exam_id = 46;
        // $no_students=$this->student_model->getStudentCount($class_id, $section_id);
        // echo $rr;die();
        // $count=$this->examgroupstudent_model->getClassExamCount($class_id,$section_id);
        //$exams=$this->examgroupstudent_model->getClassExams($class_id,$section_id);
        //$exam_array = array_column($exams, 'exam_id');
        // $no_of_exams  = $count;
        // echo "<pre>".$no_of_exams;
        //print_r($exam_array);
        //$this->examgroupstudent_model->getClassExamStatus($class_id,$section_id,$exam_id);
        //$this->examgroupstudent_model->getClassExamVerificationStatus($class_id,$section_id,$exam_id);
        //echo $this->examgroupstudent_model->getClassExamMainSubjectStatus($class_id,$section_id,$exam_id,3);
        echo $this->class_model->getClassTeacherOfClass($class_id, $section_id);
        //echo  $this->examgroupstudent_model->getClassExamStatusFull($class_id,$section_id,$exam_array);
    }

    public function attendenceEntry()
    {
        if (!$this->rbac->hasPrivilege('attendenceEntry', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'Examinations/attendenceEntry');
        $examgroup_result = $this->examgroup_model->get();
        $data['examgrouplist'] = $examgroup_result;

        $marksheet_result = $this->marksheet_model->get();
        $data['marksheetlist'] = $marksheet_result;

        $class = $this->class_model->get();
        $data['title'] = 'Add Batch';
        $data['title_list'] = 'Recent Batch';
        $data['examType'] = $this->exam_type;
        $data['classlist'] = $class;
        $session = $this->session_model->get();
        $data['sessionlist'] = $session;
        $data['current_session'] = $this->sch_current_session;
        // print_r($data['current_session']);die;
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
        } else {
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $userdata = $this->customlib->getUserData();
            $role_id  = $userdata["role_id"];
            $class_teacher = $this->classteacher_model->checkclassteacher($class_id, $section_id, $userdata['id']);
            $data['grades'] = $this->class_model->get_class_grade_table($class_id);
            if (isset($role_id) && $userdata["role_id"] == 2 && $class_teacher == 0) {
                redirect('admin/unauthorized');
            } else {

                $marksheet_template = $this->input->post('marksheet');
                $data['marksheet_template'] = $marksheet_template;
                $studentList = $this->student_model->getStudentByClassSectionIDAll($class_id, $section_id);
                $data['studentList'] = $studentList;
                //echo "<pre>";
                ///print_r($data['studentList']);die();
            }
        }
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/examgroup/attendenceEntry', $data);
        $this->load->view('layout/footer', $data);
    }

    public function annual_attendence()
    {
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('student_att', 'student attendence', 'trim|xss_clean');
        $this->form_validation->set_rules('total_att', 'class Days', 'trim|xss_clean');
        //$this->form_validation->set_rules('remark', $this->lang->line('remark'), 'trim|xss_clean');
        //$this->form_validation->set_rules('pass_status', 'Result', 'trim|xss_clean');

        if ($this->form_validation->run() == false) {
        } else {
            $student_id = $this->input->post('student_id[]');
            $i = 0;
            foreach ($student_id as $student) {
                $studentatt = $this->input->post('student_att')[$i];
                $total_att = $this->input->post('total_att')[$i];
                //$remark = $this->input->post('remark')[$i];
                //$pass_status = $this->input->post('pass_status')[$i];

                $data = array(
                    'student_att' => $studentatt,
                    'total_att' => $total_att,
                    //'remark' => $remark,
                    //'pass_status' => $pass_status,
                );
                // print_r($data);
                $this->examgroup_model->statusupdate($data, $student);
                // $this->examgroup_model->statusupdate($data,$student);
                $i++;
            }

            
            $array = array('status' => '1', 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }

    public function healthEntry()
    {
        if (!$this->rbac->hasPrivilege('healthEntry', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'Examinations/healthEntry');
        $examgroup_result = $this->examgroup_model->get();
        $data['examgrouplist'] = $examgroup_result;

        $marksheet_result = $this->marksheet_model->get();
        $data['marksheetlist'] = $marksheet_result;

        $class = $this->class_model->get();
        $data['title'] = 'Add Batch';
        $data['title_list'] = 'Recent Batch';
        $data['examType'] = $this->exam_type;
        $data['classlist'] = $class;
        $session = $this->session_model->get();
        $data['sessionlist'] = $session;
        $data['current_session'] = $this->sch_current_session;
        // print_r($data['current_session']);die;
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
        } else {
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $userdata = $this->customlib->getUserData();
            $health_id  = 94;
            $class_teacher = $this->classteacher_model->checkclassteacher($class_id, $section_id, $userdata['id']);
            $subject_teacher = $this->examgroup_model->getsubject_teacher($class_id, $section_id, $userdata['id'], $health_id);
            // echo "<pre>";
            // print_r($userdata);
            // echo "class teacher =>";
            // print_r($class_teacher);
            // echo "<br>subject teacher =>";
            //  print_r($subject_teacher);die;\
            $permitted = 0;
            if (($userdata['role_id'] == 1  || $userdata['role_id'] == 7)) {
                $permitted = 1;
            } elseif (($userdata['role_id'] == '2' && $subject_teacher > 0) || (($userdata['role_id'] == '2' && $class_teacher > 0))) {
                $permitted = 1;
            }

            if ($permitted == 0) {
                redirect('admin/unauthorized');
            } else {
                $marksheet_template = $this->input->post('marksheet');
                $data['marksheet_template'] = $marksheet_template;

                $studentList = $this->student_model->getStudentByClassSectionIDAll($class_id, $section_id);
                $data['studentList'] = $studentList;
            }
        }
        $data['sch_setting'] = $this->sch_setting_detail;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/examgroup/healthEntry', $data);
        $this->load->view('layout/footer', $data);
    }

    public function healthsubmit()
    {
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('height', 'Height', 'trim');
        $this->form_validation->set_rules('weight', $this->lang->line('weight'), 'trim');

        if ($this->form_validation->run() == false) {
        } else {
            $student_id = $this->input->post('student_id[]');
            $i = 0;
            foreach ($student_id as $student) {
                $height = $this->input->post('height')[$i];
                $weight = $this->input->post('weight')[$i];

                $data = array(
                    'height' => $height,
                    'weight' => $weight,
                );
                // print_r($data);
                $this->examgroup_model->statusupdate($data, $student);
                // $this->examgroup_model->statusupdate($data,$student);
                $i++;
            }
            $array = array('status' => '1', 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }
    public function updateheight()
    {
        echo "<pre>";
        $exam_id = 78;
        $subject_id = 139;
        $sql = "SELECT id FROM `exam_group_class_batch_exam_subjects` where exam_group_class_batch_exam_subjects.subject_id = '$subject_id' and exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id = '$exam_id'";
        $res = $this->db->query($sql)->result_array();
        foreach ($res as $r) {
            $subid  = $r['id'];
            $entrys = $this->db->query("select * from exam_group_exam_results where exam_group_class_batch_exam_subject_id = '$subid'")->result_array();
            foreach ($entrys as $entry) {
                $student_data = $this->examstudent_model->getStudentSessionByExamStudentid($exam_id, $entry['exam_group_class_batch_exam_student_id']);

                $st_session_id = $student_data['student_session_id'];
                $height = $entry['get_marks'];

                $this->db->query("update student_session set height = '$height' where id = '$st_session_id'");
                echo "<br>Height : " . $height;
            }
        }
        echo "success height";
        $subject_id = 140;
        $weight = 0;
        $sql = "SELECT id FROM `exam_group_class_batch_exam_subjects` where exam_group_class_batch_exam_subjects.subject_id = '$subject_id' and exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id = '$exam_id'";
        $res = $this->db->query($sql)->result_array();
        foreach ($res as $r) {
            $subid  = $r['id'];
            $entrys = $this->db->query("select * from exam_group_exam_results where exam_group_class_batch_exam_subject_id = '$subid'")->result_array();
            foreach ($entrys as $entry) {
                $student_data = $this->examstudent_model->getStudentSessionByExamStudentid($exam_id, $entry['exam_group_class_batch_exam_student_id']);

                $st_session_id = $student_data['student_session_id'];
                $weight = $entry['get_marks'];

                $this->db->query("update student_session set weight = '$weight' where id = '$st_session_id'");
                echo "<br>Height : " . $height;
            }
        }
        echo "Success weight";
    }

    public function sch_section($id = "")
    {
        if (!$this->rbac->hasPrivilege('sch_section', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'Academics/sch_section');
        $data['title'] = 'Exam Pattern List';
        if ($id != "") {
            $data['update']      = $this->examgroup_model->getsch_section($id);
        }

        $sch_section_result      = $this->examgroup_model->getsch_section();
        $data['sch_section_result'] = $sch_section_result;

        $this->form_validation->set_rules('sch_section', 'School section', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/examgroup/sch_section', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id' => $this->input->post('id'),
                'sch_section' => $this->input->post('sch_section'),
            );
            // print_r($data);
            $this->examgroup_model->add_sch_section($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/examgroup/sch_section');
        }
    }

    public function delete_sch_section($id)
    {
        if (!$this->rbac->hasPrivilege('sch_section', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Sch Section List';
        $this->examgroup_model->remove_sch_section($id);

        redirect('admin/examgroup/sch_section');
    }


    public function newsessionyear($id = "")
    {
        if (!$this->rbac->hasPrivilege('newsessionyear', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'Academics/newsessionyear');
        $data['title'] = 'Exam Pattern List';
        if ($id != "") {
            $data['update']      = $this->examgroup_model->getnewyearsession($id);
        }

        $newsession_result      = $this->examgroup_model->getnewyearsession();
        $data['newsession_result'] = $newsession_result;

        $data['sch_section_result']      = $this->examgroup_model->getsch_section();

        $this->form_validation->set_rules('sch_section_id', 'Sch section Id', 'trim|required|xss_clean');
        $this->form_validation->set_rules('start_date', 'Start Date', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/examgroup/newsessionyear', $data);
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

    public function delete_newsessionyear($id)
    {
        if (!$this->rbac->hasPrivilege('newsession_start_date', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'newsession_start_date';
        $this->examgroup_model->remove_newsession_start_date($id);

        redirect('admin/examgroup/newsessionyear');
    }
    public function process_finalresult_preprimary($class_id, $section_id)
    {
        $data = array();
        $students = $this->student_model->getStudentByClassSectionAll($class_id, $section_id);
        $rw = 0;
        foreach ($students as $student) {
            $examList = $this->examgroupstudent_model->studentExamsOrder($student['id']);
            if(!empty($examList)) {
            $exam_subjects = $this->batchsubject_model->getExamSubjects($examList[2]->exam_group_class_batch_exam_id);
            $exam_details = $this->examgroup_model->getExamByID($examList[0]->exam_group_class_batch_exam_id);
            $exam_grades = $this->grade_model->getByExamType($exam_details->exam_group_type, "8P");
            $core_subjects = array();
            $core_subjects = $this->examresult_model->preprimary_subject_process($exam_subjects, $examList, $student,$exam_grades);
            }
        }
        $array = array('status' => 'success', 'error' => '', 'message' => "Successfully Processed ...");
        return json_encode($array);

    }
    public function process_finalresult_secondary($class_id, $section_id)
    {
        $data = array();
        $students = $this->student_model->getStudentByClassSectionAll($class_id, $section_id);
        $rw = 0;

        foreach ($students as $student) {
            
            $examList = $this->examgroupstudent_model->studentExamsOrder($student['id']);
            // echo "<pre>";
            // print_r($examList);die();            
            if(!empty($examList))
            {
                //echo "<br>Student Roll No : ".$student['roll_no']." Total Mark : ".$total_mark." Max Mark : ".$max_mark." Percentage : ".round($total_percentage, 2)." Grade : ".$final_grade." Remark : ".$final_remark;
                $exams_count = sizeof($examList);
                if($exams_count>6)
                {

                    $exam_id = "";
                    foreach($examList as $ex)
                    {
                        if($exam_id=="")
                        {$exam_id = $ex->exam_group_class_batch_exam_id;}
                        else
                        {$exam_id .= ",".$ex->exam_group_class_batch_exam_id;}
        
                    }
                    echo "<pre>";            
                    $exam_id = "(".$exam_id.")";
                    $result=$this->db->query("SELECT exam_group_class_batch_exams.exam,exam_groups.name FROM exam_group_class_batch_exams INNER JOIN exam_groups ON exam_groups.id = exam_group_class_batch_exams.exam_group_id where exam_group_class_batch_exams.id in ".$exam_id)->result_array();
                    echo "Error..Exam Count Error..Student Roll NO : ".$student['roll_no'];        
                    print_r($result);
        
                    $array = array('status' => '', 'error' => 1, 'message' => 'Invalid Exam Count');
                    return json_encode($array);            
                }
                $exam_subjects = $this->batchsubject_model->getExamSubjects($examList[0]->exam_group_class_batch_exam_id);
                $exam_details = $this->examgroup_model->getExamByID($examList[0]->exam_group_class_batch_exam_id);
                $exam_grades = $this->grade_model->getByExamType($exam_details->exam_group_type, "10P");
                $core_subjects = array();
                $subrw = 0;
                $total_mark = 0;
                $max_mark = 0;
                $total_percentage = 0;
                $grade = "";
                $remarks = "";
                // echo "<pre>";
                // print_r($exam_subjects);die();
                foreach ($exam_subjects as $subjects) {
                    $main_subject = $this->subject_model->getSubjectByID($subjects->main_sub);
                    $students_array[$rw]['main_subject'][] = $main_subject;

                    $core_subjects = $this->examresult_model->term_core_subject_process($subjects, $examList, $student);

                    if($core_subjects['total']!="Ab" && $core_subjects['total']!="-")
                    {
                        $total_mark += $core_subjects['total'];
                        $max_mark += $core_subjects['max_mark'];
                    }
                    $students_array[$rw]['main_subject'][$subrw]['marks'] = $core_subjects;
                    ++$subrw;
                }
                if ($total_mark > 0) {
                    $total_percentage = ($total_mark * 100) / $max_mark;
                } else {
                    $total_percentage = 0;
                }
                $final_grade = $this->grade_model->get_Grade($exam_grades, $total_percentage);
                $final_remark = $this->grade_model->get_Remark($exam_grades, $final_grade);
                $data = array(
                    'id' => $student['id'],
                    'total_mark' => $total_mark,
                    'max_mark' => $max_mark,
                    'percentage' => round($total_percentage, 2),
                    'grade' => $final_grade,
                    'remark' => $final_remark,
                );
                $this->studentsession_model->updateById($data);
            }
        }
        $array = array('status' => 'success', 'error' => '', 'message' => "Successfully Processed ...");
        return json_encode($array);
    }
    public function exam_finalize()
    {
        if (!$this->rbac->hasPrivilege('exam_finalize', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'Examinations/exam_finalize');
        $examgroup_result = $this->examgroup_model->get();
        $data['examgrouplist'] = $examgroup_result;

        $marksheet_result = $this->marksheet_model->get();
        $data['marksheetlist'] = $marksheet_result;

        //$class = $this->class_model->getsecondaryclass();
        $class = $this->class_model->get_class_all();
        $data['title'] = 'Add Batch';
        $data['title_list'] = 'Recent Batch';
        $data['classlist'] = $class;
        $data['current_session'] = $this->sch_current_session;
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
        } else {
            $section_id =  $data['section_id'] = $this->input->post('section_id');
            $class_id = $data['class_id'] = $this->input->post('class_id');
            //$this->class_exam_finalize($class_id,$section_id);
        }
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/examgroup/exam_finalize', $data);
        $this->load->view('layout/footer', $data);
    }
    public function class_exam_finalize()
    {
        // ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);        
        $section_id =  $data['section_id'] = $this->input->post('section_id');
        $class_id = $data['class_id'] = $this->input->post('class_id');
        $reset_subject = $this->input->post('reset_subject');
        $vertification_mode = $this->input->post('verification_mode');
        $pattern = $this->examgroup_model->get_class_exam_pattern($class_id);
                    if ($pattern == "") {
                        echo "Class Exam Pattern Not Found";
                        $array = array('status' => '', 'error' => 1, 'message' => 'Please set class wise exam pattern..');
                        return json_encode($array);
                    } else {
                        $exam_pattern = $pattern['name'];
                    }           
        if($exam_pattern=="Evaluation")                    
        {
            $result=$this->process_final_result_evaluation($class_id, $section_id,$reset_subject,$vertification_mode);
            echo $result;
        }
        elseif($exam_pattern=="Term")
        {
            $result=$this->process_finalresult_secondary($class_id, $section_id);
            echo $result;
        }
        elseif($exam_pattern=="EvaluationSingle")                    
        {
            $result=$this->process_finalresult_preprimary($class_id, $section_id);
            echo $result;
        }
    }
    public function sendmsg()
    {
        //$wati = new Wati();
        echo "hai";
        $this->examgroup_model->sendmsg();
        echo "send";
    }
    public function sendfile()
    {
        //$wati = new Wati();
        echo "hai";
        $this->examgroup_model->sendfile();
        echo "send";
    }    
    public function update_examstatus()
    {
        $data['exam'] = $exam_id       = $this->input->post('exam_id');
        $status       = $this->input->post('status');

        // print_r($this->input->post());
        $this->examgroup_model->update_main_exam_status($exam_id,$status);
        
        $data = array('status' => '1', 'error' => '','exam_id'=>$exam_id);
        echo json_encode($data);
    }

    public function mark_master($id = "")
    {
        if (!$this->rbac->hasPrivilege('mark_master', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'Examinations/mark_master');
        $data['title'] = 'Exam Pattern List';
        if ($id != "") {
            $data['update']      = $this->examgroup_model->getmark_master($id);
        }

        $sch_section_result      = $this->examgroup_model->getmark_master();
        $data['sch_section_result'] = $sch_section_result;

        $this->form_validation->set_rules('mark_master', 'Mark Remark', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/examgroup/mark_master', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id' => $this->input->post('id'),
                'mark_master' => $this->input->post('mark_master'),
            );
            // print_r($data);
            $this->examgroup_model->add_mark_master($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/examgroup/mark_master');
        }
    }

    public function delete_mark_master($id)
    {
        if (!$this->rbac->hasPrivilege('mark_master', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Mark Remark List';
        $this->examgroup_model->remove_mark_master($id);

        redirect('admin/examgroup/mark_master');
    }

    public function result_date($id = "")
    {
        if (!$this->rbac->hasPrivilege('result_date', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'Academics/result_date');
        $data['title'] = 'Exam Pattern List';
        if ($id != "") {
            $data['update']      = $this->examgroup_model->getresult_date($id);
        }

        $newsession_result      = $this->examgroup_model->getresult_date();
        $data['newsession_result'] = $newsession_result;

        $data['sch_section_result']      = $this->examgroup_model->getsch_section();

        $this->form_validation->set_rules('sch_section_id', 'Sch Section ', 'trim|required|xss_clean');
        $this->form_validation->set_rules('result_date', 'Result Date', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/examgroup/result_date', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $current_session = $this->sch_current_session;
            $data = array(
                'id' => $this->input->post('id'),
                'sch_section_id' => $this->input->post('sch_section_id'),
                'session_id' => $current_session,
                'result_date' => date('Y-m-d', strtotime($this->input->post('result_date'))),
            );
            $this->examgroup_model->add_result_date($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/examgroup/result_date');
        }
    }

    public function delete_result_date($id)
    {
        if (!$this->rbac->hasPrivilege('result_date', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'result_date';
        $this->examgroup_model->remove_result_date($id);

        redirect('admin/examgroup/result_date');
    }

    public function my_subjects()
    {
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'Academics/my_subjects');
        $data['title'] = 'Assign Teacher with Class and Subject wise';

        $teacher = $this->staff_model->getStaffbyrole(2);
        $data['teacherlist'] = $teacher;
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $userdata = $this->customlib->getUserData();
        $data['class_id']        = $class_id = $this->input->post('class_id');
        $data['section_id']      = $section_id = $this->input->post('section_id');
        $data['subject_id']        = $subject_id = $this->input->post('subject_id');
        $subject = $this->subject_model->getTeacherSubject($class_id, $section_id);
        $data['subjectlist'] = $subject;
        $data['section_list']    = $this->section_model->getClassBySection($this->input->post('class_id'));

        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_id', $this->lang->line('subject'), 'trim|required|xss_clean');
        // if ($this->form_validation->run() == false) {
        //     $this->load->view('layout/header', $data);
        //     $this->load->view('admin/examgroup/my_subjects_org', $data);
        //     $this->load->view('layout/footer', $data);
        // }else {
            
            $data['current_session'] = $this->setting_model->getCurrentSession();
            $data['current_session_name'] = $this->setting_model->getCurrentSessionName();
            // $data['resultlist'] = $this->examgroup_model->getExamListArray($class_id,$section_id,$subject_id);$data['resultlist'] = $this->examgroup_model->getSubjectListArray($userdata['id']);
            $data['resultlist'] = $this->examgroup_model->getSubjectListArray($userdata['id']);
            
            
            $this->load->view('layout/header', $data);
            $this->load->view('admin/examgroup/my_subjects_org', $data);
            $this->load->view('layout/footer', $data);
        // }
    }

    public function worksheet_mark_entry()
    {
        if (!$this->rbac->hasPrivilege('worksheet_mark_entry', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'Examinations/worksheet_mark_entry');
        $data['title'] = '';

        $teacher = $this->staff_model->getStaffbyrole(2);
        $data['teacherlist'] = $teacher;
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $userdata = $this->customlib->getUserData();
            
        $data['current_session'] = $this->setting_model->getCurrentSession();
        $data['current_session_name'] = $this->setting_model->getCurrentSessionName();
        // $data['resultlist'] = $this->examgroup_model->getSubjectListArray($userdata['id']);
        
        $this->load->view('layout/header', $data);
        $this->load->view('admin/examgroup/worksheet_mark_entry', $data);
        $this->load->view('layout/footer', $data);
    }

    public function getExamlistByStudent()
    {
        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        $student_session_id = $this->input->post('student_session_id');

        $resultlist = $this->examgroup_model->getExamListByStudent($class_id,$section_id,$student_session_id);
        $html = "";
        if (!empty($resultlist)) {
            foreach($resultlist as $value)
            {
                $html .= '<tr>';
                $html .= '<td>'. $value['exam'] .'</td>';
                $html .= '<td>
                <input type="hidden" name="id[]" value="'. $value['id'].'" >
                <textarea name="long_remarks[]" id="long_remarks" cols="20" class="form-control" rows="4">'. $value['long_remarks'] .'</textarea>
                </td>';
                $html .= '</tr>';
            }
        }

        $array = [
            'success' => "Successfully",
            'html' => $html,
        ];
        echo json_encode($array);
    }

    public function mark_entry_valid()
    {
        $id = $this->input->post('id');
        if (!empty($id)) {
            $i = 0;
            foreach ($id as $key => $value) {
                if ($this->input->post('long_remarks')[$i] != "") {
                    $array = ["long_remarks" =>$this->input->post('long_remarks')[$i]];
                    $this->examgroup_model->updateRemark($array,$value);
                }
                $i++;
            }
        }

        $array = [
            'status' => 'success',
            'error' => '',
            'message' => "Successfully Updated",
        ];
        echo json_encode($array);
    }

    public function get_class_by_examgroup()
    {
        $examgroup_id = $this->input->post('examgroup_id');
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $data['examgroup_id'] = $examgroup_id;
        $student_exam_page = $this->load->view('admin/examgroup/class_by_examgroup', $data, true);
        $array = array('status' => '1', 'error' => '', 'page' => $student_exam_page);
        echo json_encode($array);
    }

    public function assign_class_validation()
    {
        $examgroup_id = $this->input->post('examgroup_id');
        $class_id = $this->input->post('class_id');

        if (!empty($class_id)) {
            $insert_array = [];
            foreach ($class_id as $value) {
                $insert_array[] = [
                    "exam_group_id" => $examgroup_id,
                    "class_id" => $value,

                ];


            }
            $this->examgroup_model->assign_class($insert_array,$examgroup_id);
        }

        echo json_encode(['status' => '1', 'error' => '', 'message' => 'Successfully Updated']);
        
    }

    public function getSubjectByClass()
    {

        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        $session_id = $this->input->post('session_id');
        $exam_id = $this->input->post('dataexam_id');
        $main_sub = $this->input->post('main_sub');

        $data['exam_id'] = $exam_id;
        $data['main_sub'] = $main_sub;

        $exam_subjects = $this->batchsubject_model->getExamSubjects($exam_id, $main_sub);
        
        // echo "<pre>";
        // print_r ($exam_subjects);die;
        // echo "</pre>";
        
        $data['subjectList'] = $exam_subjects;

        $resultlist                                     = $this->examgroupstudent_model->searchExamStudents("",$exam_id, $class_id, $section_id, $session_id);
        
        // echo "<pre>";
        // print_r ($resultlist);die;
        // echo "</pre>";
        $data['attendence_exam'] = $this->attendence_exam;
        $data['sch_setting']     = $this->sch_setting_detail;
        $data['userdata'] = $this->customlib->getUserData();
        
        $data['resultlist']                             = $resultlist;
        $student_exam_page = $this->load->view('admin/examgroup/getstudentsformarkentry', $data, true);
        $array = array('status' => '1', 'error' => '', 'subject_page' => $student_exam_page);
        echo json_encode($array);
    }

    public function entrysubjectmarks()
    {
        
        // echo "<pre>";
        // print_r ($this->input->post());die;
        // echo "</pre>";
        // ini_set('display_errors', 1);
        // error_reporting(E_ALL);
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('main_sub', 'Subject', 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'main_sub' => form_error('main_sub'),
            );
            $array = array('status' => 0, 'error' => $data);
            echo json_encode($array);
        } else {
            $exam_group_student_id = $this->input->post('exam_group_student_id');
            $exam_group_class_batch_exam_subject_id = $this->input->post('exam_group_class_batch_exam_subject_id');
            $insert_array          = array();
            $update_array          = array();
            if (!empty($exam_group_student_id)) {
                // echo "<pre>";
                // print_r ($this->input->post('subject_id'));die;
                // echo "</pre>";
                $j=0;
                $i = 0;
                foreach ($exam_group_student_id as  $value) {
                    if (!empty($exam_group_class_batch_exam_subject_id)) {
                        $s=0;
                        foreach ($exam_group_class_batch_exam_subject_id as  $subject_id) {
                            $attendance_post = $this->input->post('exam_group_student_attendance_' . $value);
                            if (isset($attendance_post)) {
                                $attendance = $this->input->post('exam_group_student_attendance_' . $value);
                            } else {
                                $attendance = "present";
                            }
                            if (isset($this->input->post('get_grade')[$i])) {
                                $get_grade = strtoupper($this->input->post('get_grade')[$i]);
                            } else {
                                $get_grade = '';
                            }
                            $array = array(
                                'exam_group_class_batch_exam_subject_id' => $subject_id,
                                'exam_group_class_batch_exam_student_id' => $value,
                                'attendence'                             => $attendance,
                                'get_marks'                              => $this->input->post('exam_group_student_mark')[$i],
                                'get_grade'                              => $get_grade,
                                'note'                                   => $this->input->post('exam_group_student_note')[$i],
                                'exam_group_class_batch_exams_id'        => $this->input->post('exam_id'),
                                'subject_id'                             => $this->input->post('subject_id')[$s],
                                'main_sub'                               => $this->input->post('main_sub'),
                            );
                            
                            // echo "<pre>";
                            // print_r ($array);
                            // echo "</pre>";
                            
                            $insert_array[] = $array;
                            $i++;
                            $s++;
                        }
                    }
                    $j++;
                }
                // echo "<pre>";
                // print_r($insert_array);
                // die;
            }
            $this->examgroupstudent_model->add_result($insert_array);
            $array = array('status' => '1', 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }

    public function exams_list($subject_id,$class_id,$section_id,$session_id)
    {
        $data['subject_id'] = $subject_id;
        $data['class_id'] = $class_id;
        $data['section_id'] = $section_id;
        $data['session_id'] = $session_id;
        $examlist = $this->examgroup_model->getexamgroupbyClass($class_id);
        $data['examlist'] = $examlist;
        // $class               = $this->examgroup_model->getclassbyexamgroup($examgroup_id);
        // $data['classlist']   = $class;
        $session             = $this->session_model->get();
        $data['sessionlist'] = $session;
        $subjectlist         = $this->subject_model->get();
        $data['subjectlist'] = $subjectlist;
        $data['userdata'] = $this->customlib->getUserData();
        $data['current_session'] = $this->sch_current_session;

        $this->load->view('layout/header');
        $this->load->view('admin/examgroup/exams_list', $data);
        $this->load->view('layout/footer');
    }

    public function getclassByexamgroupId()
    {
        $examgroup_id = $this->input->post('examgroup_id');
        $data = $this->examgroup_model->getclassbyexamgroup($examgroup_id);
        echo json_encode($data);
    }

    public function exportMarks()
    {
        $exam_id = $this->input->post('exam_id');
        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        $session_id = $this->input->post('session_id');
        $main_sub = $this->input->post('main_sub');

        $exam_subjects = $this->batchsubject_model->getExamSubjects($exam_id, $main_sub);
        $data['subjectList'] = $exam_subjects;
        
        
        $resultlist = $this->examgroupstudent_model->searchExamStudents("",$exam_id, $class_id, $section_id, $session_id);
        
        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'GR.No');
        $sheet->setCellValue('B1', 'R.No');
        $sheet->setCellValue('C1', 'Student Name');
        $sheet->setCellValue('D1', 'Gender');
        $sheet->setCellValue('E1', 'Attendence');
       
        if (!empty($exam_subjects)) {
            $alpha = 'F';
            foreach ($exam_subjects as $subjects) {
                $sheet->setCellValue($alpha."1",  $subjects->subject_name);
                $alpha++;
            }
        }
        $sheet->setCellValue($alpha++ . '1', 'Note');

        $rows = 2;
        if (!empty($resultlist)) {
            foreach ($resultlist as $result) {
                $attendance = "";
                if ($result->attendence == "absent") {
                    $attendance = "Absent";
                }
                $sheet->setCellValue('A' . $rows, $result->admission_no);
                $sheet->setCellValue('B' . $rows, $result->roll_no);
                $sheet->setCellValue('C' . $rows, $result->firstname." ".$result->middlename." ".$result->lastname);
                $sheet->setCellValue('D' . $rows, $result->gender);
                $sheet->setCellValue('E' . $rows, $attendance);
                
                if (!empty($exam_subjects)) {
                    $alpha = 'F';
                    foreach ($exam_subjects as $subjects) {
                        $marksRow = $this->examgroupstudent_model->getStudentMarksBySubject($result->exam_group_class_batch_exam_student_id, $subjects->id, $subjects->subject_id);
                        
                        if (!empty($marksRow) && $subjects->input_type == "Grade") {
                            $marks = $marksRow->get_grade;
                        }elseif (!empty($marksRow) && $subjects->input_type == "Marks") {
                            $marks = $marksRow->get_marks;
                        } else {
                            $marks = "";
                        }
                        $sheet->setCellValue($alpha . $rows, $marks);
                        $alpha++;
                    }
                }

                $sheet->setCellValue($alpha++ . $rows, $marksRow->note);
                $rows++;
            }
        }
        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $filename = "Student_Marks_".date('Y_m_d_H_i_s');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }


    public function getMainSubjectByExam()
    {
        $exam_id = $this->input->post('exam_id');
        $data          = $this->examgroup_model->getMainSubjectByExam($exam_id, true);
        echo json_encode($data);
    }

    public function export_single_subject_marks()
    {
        $exam_subject_id                                = $this->input->post('exam_group_class_batch_exam_subject_id');
        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        $session_id = $this->input->post('session_id');
        // $exam_id = $this->input->post('exam_id');
        // $main_sub = $this->input->post('main_sub');

        $resultlist                                     = $this->examgroupstudent_model->examGroupSubjectResult($exam_subject_id, $class_id, $section_id, $session_id);
        
        // echo "<pre>";
        // print_r ($resultlist);die;
        // echo "</pre>";
        
        $subject_detail = $this->batchsubject_model->getExamSubject($exam_subject_id);
        $attendence_exam = $this->attendence_exam;
        $sch_setting =$this->sch_setting_detail;
        $userdata = $this->customlib->getUserData();
        
        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'GR.No');
        $sheet->setCellValue('B1', 'R.No');
        $sheet->setCellValue('C1', 'Student Name');
        $sheet->setCellValue('D1', 'Admission Date');
        $sheet->setCellValue('E1', 'Category');
        $sheet->setCellValue('F1', 'Gender');
        $sheet->setCellValue('G1', 'Attendence');
        $sheet->setCellValue('H1', 'Marks');
        $sheet->setCellValue('I1', 'Note');
       
        $rows = 2;
        if (!empty($resultlist)) {
            foreach ($resultlist as $result) {
                $attendance = "";
                if ($result['attendence'] == "absent") {
                    $attendance = "Absent";
                }

                if(!empty($result['admission_date'])) {
                    $adm_date =  date('d-m-Y',strtotime($result['admission_date']));
                }else{
                    $adm_date = "";
                }
                $roll_no = ($result['use_exam_roll_no']) ? $result['exam_roll_no'] : $result['roll_no'];
                $sheet->setCellValue('A' . $rows, $result['admission_no']);
                $sheet->setCellValue('B' . $rows, $roll_no);
                $sheet->setCellValue('C' . $rows, $result['firstname']." ".$result['middlename']." ".$result['lastname']);
                $sheet->setCellValue('D' . $rows, $adm_date);
                $sheet->setCellValue('E' . $rows, $result['category']);
                $sheet->setCellValue('F' . $rows, $result['gender']);
                $sheet->setCellValue('G' . $rows, $result['exam_group_exam_result_attendance']);
                $sheet->setCellValue('H' . $rows, $result['exam_group_exam_result_get_marks']);
                $sheet->setCellValue('I' . $rows, $result['exam_group_exam_result_note']);
                $rows++;
            }
        }
        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $filename = "Student_Marks_".date('Y_m_d_H_i_s');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }


    public function test_insert()
    {
        $this->db->where_in('exam_group_class_batch_exams_id', [83,85,91]);
		$result  = $this->db->get('exam_group_exam_results_new')->result_array();
        echo '<pre>';
		print_r($result);die;
        // $this->db->insert_batch('exam_group_exam_results', $result);
        // echo 'done';
    }
}
