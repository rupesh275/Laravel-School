<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Library_reports extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->current_session = $this->setting_model->getCurrentSession();
    }
    

    public function index()
    {
        if (!$this->rbac->hasPrivilege('library_reports', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'Library/library_reports');
        $this->session->set_userdata('subsub_menu', '');
       
        $this->load->view('layout/header');
        $this->load->view('admin/librarian/library_reports');
        $this->load->view('layout/footer');
    }

    /**
     * Generates a return pending reports page.
     *
     * This function retrieves the list of issued books with the status of "pending". 
     * It then calls the `getIssuedBooks_list` method of the `bookissue_model`
     * to retrieve the issued books with the status of "issue". Finally, it loads the views
     * for the header, return pending reports, and footer with the provided data.
     *
     * @return void
     */
    public function return_pending_reports()
    {
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'Library/library_reports');
        $this->session->set_userdata('subsub_menu', 'Library/library_reports/return_pending_reports');
        $data['title']           = 'Return Pending Reports';
        // $data['searchlist']      = $this->search_type;
        $data['sch_setting']     = $this->sch_setting_detail;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $searchterm              = '';
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        // ini_set('display_errors', '1');
        $data['result'] = $this->bookissue_model->getIssuedBooks_list("issue");

        $this->load->view('layout/header',$data);
        $this->load->view('admin/librarian/return_pending_reports',$data);
        $this->load->view('layout/footer',$data);
    }

    /**
     * Generates a damage reports page.
     *
     * 
     * Retrieves the issued books with the status of 'damage'.
     *
     * @return void
     */
    public function damage_reports()
    {
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'Library/library_reports');
        $this->session->set_userdata('subsub_menu', 'Library/library_reports/damage_reports');
        $data['title']           = 'Return Pending Reports';
        // $data['searchlist']      = $this->search_type;
        $data['sch_setting']     = $this->sch_setting_detail;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $searchterm              = '';
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        // ini_set('display_errors', '1');
        $data['result'] = $this->bookissue_model->getIssuedBooks("damage");

        $this->load->view('layout/header',$data);
        $this->load->view('admin/librarian/damage_reports',$data);
        $this->load->view('layout/footer',$data);
    }
    /**
     * Generates a lost reports page.
     *
     * It then calls the `getIssuedBooks` method of the `bookissue_model` to retrieve the
     * issued books with the status of "lost".
     *
     * @return void
     */
    public function lost_reports()
    {
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'Library/library_reports');
        $this->session->set_userdata('subsub_menu', 'Library/library_reports/lost_reports');
        $data['title']           = 'Return Pending Reports';
        // $data['searchlist']      = $this->search_type;
        $data['sch_setting']     = $this->sch_setting_detail;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $searchterm              = '';
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $data['result'] = $this->bookissue_model->getIssuedBooks("lost");

        $this->load->view('layout/header',$data);
        $this->load->view('admin/librarian/lost_reports',$data);    
        $this->load->view('layout/footer',$data);
    }
    /**
     * Function to generate issue reports.
     *
     * 
     */
    public function issue_reports()
    {
        // ini_set('display_errors', '1');
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'Library/library_reports');
        $this->session->set_userdata('subsub_menu', 'Library/library_reports/issue_reports');
        $data['title']           = 'Return Pending Reports';
        // $data['searchlist']      = $this->search_type;
        $data['sch_setting']     = $this->sch_setting_detail;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $searchterm              = '';
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $data['result'] = $this->bookissue_model->getIssuedBooks();

        $this->load->view('layout/header',$data);
        $this->load->view('admin/librarian/issue_reports',$data);
        $this->load->view('layout/footer',$data);
    }
    /**
     * Generates the staff booking report.
     *
     * This function sets the necessary session data for the report, retrieves the
     * class list, and fetches the staff booking data from the book model. It then
     * loads the header, staff booking report, and footer views with the data.
     *
     * @return void
     */
    public function staff_booking_report()
    {
        // ini_set('display_errors', '1');
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'Library/library_reports');
        $this->session->set_userdata('subsub_menu', 'library_reports/staff_booking_report');
        $data['title']           = 'Staff Booking Reports';
        // $data['searchlist']      = $this->search_type;
        $data['sch_setting']     = $this->sch_setting_detail;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $searchterm              = '';
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $data['result'] = $this->book_model->getBookingStaff();

        $this->load->view('layout/header',$data);
        $this->load->view('admin/librarian/staff_booking_report',$data);
        $this->load->view('layout/footer',$data);
    }
    /**
     * Function to generate a report on student bookings.
     *
     * 
     */
    public function student_booking_report()
    {
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'Library/library_reports');
        $this->session->set_userdata('subsub_menu', 'library_reports/student_booking_report');
        $data['title']           = 'Staff Booking Reports';
        // $data['searchlist']      = $this->search_type;
        $data['sch_setting']     = $this->sch_setting_detail;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $searchterm              = '';
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $data['result'] = $this->book_model->getBookingStudent();

        $this->load->view('layout/header',$data);
        $this->load->view('admin/librarian/student_booking_report',$data);
        $this->load->view('layout/footer',$data);
    }
}