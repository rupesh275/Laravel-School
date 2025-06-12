<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Student_model extends MY_Model
{
    public $current_session;
    public $current_date;

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
        $this->current_date    = $this->setting_model->getDateYmd();
    }

    public function getBirthDayStudents($date, $email = false, $contact_no = false)
    {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.aadhar_name,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender,users.id as `user_tbl_id`,users.username,users.password as `user_tbl_password`,users.is_active as `user_tbl_active`,students.app_key,students.parent_app_key')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        $this->db->where('users.role', 'student');
        if ($email) {
            $this->db->where('students.email !=', "");
        }
        if ($contact_no) {
            $this->db->where('students.mobileno !=', "");
        }

        $this->db->where("DATE_FORMAT(students.dob,'%m-%d') = DATE_FORMAT('" . $date . "','%m-%d')");

        $this->db->order_by('students.id');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function getStudents()
    {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender,users.id as `user_tbl_id`,users.username,users.password as `user_tbl_password`,users.is_active as `user_tbl_active`')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        $this->db->where('users.role', 'student');

        $this->db->order_by('students.id');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function getAppStudents()
    {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,  students.middlename,students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.app_key ,students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender,users.id as `user_tbl_id`,users.username,users.password as `user_tbl_password`,users.is_active as `user_tbl_active`')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        $this->db->where('students.app_key !=', "");
        $this->db->where('users.role', 'student');

        $this->db->order_by('students.id');

        $query = $this->db->get();
        return $query->result();
    }

    public function getRecentRecord($id = null)
    {
        $this->db->select('classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname, students.middlename, students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,students.category_id,    students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->where('student_session.session_id', $this->current_session);
        if ($id != null) {
            $this->db->where('students.id', $id);
        } else {
        }
        $this->db->order_by('students.id', 'desc');
        $this->db->limit(5);
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getParentChilds($parent_id)
    {
        $sql   = "SELECT students.*,student_session.id as `student_session_id`,student_session.session_id,student_session.student_id,student_session.class_id,student_session.default_login,student_session.section_id,classes.class,sections.section From students inner JOIN student_session on student_session.student_id=students.id inner join classes on student_session.class_id=classes.id INNER JOIN sections on sections.id=student_session.section_id WHERE students.parent_id=" . $this->db->escape($parent_id) . " and student_session.session_id=" . $this->current_session . " and students.is_active = 'yes' order by student_session.default_login desc,student_session.class_id asc";
        $query = $this->db->query($sql);
        return $query->result();
        // $this->db->select('students.*,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section')->from('students');
        // $this->db->join('student_session', 'student_session.student_id = students.id');
        // $this->db->join('classes', 'student_session.class_id = classes.id');
        // $this->db->join('sections', 'sections.id = student_session.section_id');
        // $this->db->where('student_session.session_id', $this->current_session);
        // $this->db->where('parent_id', $parent_id);
        // $this->db->where('students.is_active', 'yes');
        // $this->db->group_by('students.id');
        // $query = $this->db->get();
        // return $query->result();
    }
    public function getParentChilds_mobileno($parent_id)
    {
        
        $sess_data = $this->session->userdata('student');        
        $sql   = "SELECT students.*,student_session.id as `student_session_id`,student_session.session_id,student_session.student_id,student_session.class_id,student_session.default_login,student_session.section_id,classes.class,sections.section From students inner JOIN student_session on student_session.student_id=students.id inner join classes on student_session.class_id=classes.id INNER JOIN sections on sections.id=student_session.section_id WHERE students.mobileno=" . $sess_data['mobileno'] . " and students.is_active = 'yes' and student_session.session_id = ".$this->current_session." order by student_session.default_login desc,student_session.class_id asc";
        $query = $this->db->query($sql);
        return $query->result();
        // $this->db->select('students.*,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section')->from('students');
        // $this->db->join('student_session', 'student_session.student_id = students.id');
        // $this->db->join('classes', 'student_session.class_id = classes.id');
        // $this->db->join('sections', 'sections.id = student_session.section_id');
        // $this->db->where('student_session.session_id', $this->current_session);
        // $this->db->where('parent_id', $parent_id);
        // $this->db->where('students.is_active', 'yes');
        // $this->db->group_by('students.id');
        // $query = $this->db->get();
        // return $query->result();
    }     
    public function getStudentCount($class_id, $section_id)
    {
        $this->db->select('id')->from('student_session');
        $this->db->where('student_session.class_id', $class_id);
        $this->db->where('student_session.section_id', $section_id);
        $this->db->where('student_session.session_id', $this->current_session);
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function getStudentByClassSectionAll($class_id, $section_id)
    {
        $this->db->select('student_session.*,id as student_session_id')->from('student_session');
        $this->db->where('student_session.class_id', $class_id);
        if (!empty($section_id)) {
            $this->db->where('student_session.section_id', $section_id);
        }
        $this->db->where('student_session.session_id', $this->current_session);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getStudentByClassSectionID($class_id = null, $section_id = null, $id = null)
    {
        $this->db->select('student_session.is_active as session_is_active,student_session.transport_fees,classes.code,students.vehroute_id,student_session.roll_no,pass_status,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,students.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname, students.middlename, students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast, school_houses.house_name,   students.dob ,students.current_address, students.previous_school,
            students.guardian_is,students.parent_id,categories.category,students.mobileno,
            students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email, users.username,users.password,students.dis_reason,students.dis_note,students.app_key,students.parent_app_key,students.dep_student_id,students.aapar_id,students.uid_no')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = students.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = student_session.house_id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');

        $this->db->where('student_session.class_id', $class_id);
        $this->db->where('student_session.section_id', $section_id);
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('users.role', 'student');

        if ($id != null) {
            $this->db->where('students.id', $id);
        } else {
            $this->db->where('students.is_active', 'yes');
            $this->db->where('student_session.is_active', 'yes');
            $this->db->order_by('student_session.roll_no', 'asc');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }
    public function getStudentByClassSectionIDAll($class_id = null, $section_id = null, $id = null)
    {
        $this->db->select('student_session.is_active as session_is_active,student_session.transport_fees,classes.code,students.vehroute_id,student_session.roll_no,pass_status,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,students.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname, students.middlename, students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast, school_houses.house_name,   students.dob ,students.current_address, students.previous_school,
            students.guardian_is,students.parent_id,categories.category,students.mobileno,
            students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email, users.username,users.password,students.dis_reason,students.dis_note,students.app_key,students.parent_app_key')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = students.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = student_session.house_id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');

        $this->db->where('student_session.class_id', $class_id);
        $this->db->where('student_session.section_id', $section_id);
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('users.role', 'student');

        if ($id != null) {
            $this->db->where('students.id', $id);
        } else {

            $this->db->order_by('student_session.roll_no', 'asc');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getStudentByClassSectionIDforinstall($class_id = null, $section_id = null, $id = null)
    {
        $this->db->select('student_session.is_active as session_is_active,student_session.transport_fees,students.vehroute_id,student_session.roll_no,pass_status,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,students.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname, students.middlename, students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast, school_houses.house_name,   students.dob ,students.current_address, students.previous_school,
            students.guardian_is,students.parent_id,
            students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email, users.username,users.password,students.dis_reason,students.dis_note,students.app_key,students.parent_app_key')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = students.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->where('student_session.class_id', $class_id);
        $this->db->where('student_session.section_id', $section_id);
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('users.role', 'student');

        if ($id != null) {
            $this->db->where('students.id', $id);
        } else {
            //$this->db->where('students.is_active', 'yes');
             $this->db->where('student_session.is_active', 'yes');
            $this->db->order_by('student_session.roll_no', 'asc');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }
    public function getStudentByClassSectionIDforinstall_bysession($class_id = null, $section_id = null, $id = null,$rep_session_id = null)
    {
        $this->db->select('student_session.is_active as session_is_active,student_session.transport_fees,students.vehroute_id,student_session.roll_no,pass_status,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,students.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname, students.middlename, students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast, school_houses.house_name,   students.dob ,students.current_address, students.previous_school,
            students.guardian_is,students.parent_id,
            students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email, users.username,users.password,students.dis_reason,students.dis_note,students.app_key,students.parent_app_key')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = students.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->where('student_session.class_id', $class_id);
        $this->db->where('student_session.section_id', $section_id);
        if($rep_session_id=="")
        {$this->db->where('student_session.session_id', $this->current_session);}
        else
        {$this->db->where('student_session.session_id', $rep_session_id);}
        $this->db->where('users.role', 'student');

        if ($id != null) {
            $this->db->where('students.id', $id);
        } else {
            //$this->db->where('students.is_active', 'yes');
             $this->db->where('student_session.is_active', 'yes');
            $this->db->order_by('student_session.roll_no', 'asc');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }
    public function getStudentByClassSectionIDforinstall_dues_session($class_id = null, $section_id = null, $id = null,$rep_session_id = null)
    {
        $this->db->select('student_session.is_active as session_is_active,student_session.transport_fees,students.vehroute_id,student_session.roll_no,pass_status,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,students.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname, students.middlename, students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast, school_houses.house_name,   students.dob ,students.current_address, students.previous_school,
            students.guardian_is,students.parent_id,
            students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email, users.username,users.password,students.dis_reason,students.dis_note,students.app_key,students.parent_app_key')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = students.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->where('student_session.class_id', $class_id);
        $this->db->where('student_session.section_id', $section_id);
        if($rep_session_id=="")
        {$this->db->where('student_session.session_id', $this->current_session);}
        else
        {$this->db->where('student_session.session_id', $rep_session_id);}
        $this->db->where('users.role', 'student');

        if ($id != null) {
            $this->db->where('students.id', $id);
        } else {
            $this->db->order_by('student_session.roll_no', 'asc');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getStudentByClassSectionIDforinstall_dues($class_id = null, $section_id = null, $id = null)
    {
        $this->db->select('student_session.is_active as session_is_active,student_session.transport_fees,students.vehroute_id,student_session.roll_no,pass_status,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,students.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname, students.middlename, students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast, school_houses.house_name,   students.dob ,students.current_address, students.previous_school,
            students.guardian_is,students.parent_id,
            students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email, users.username,users.password,students.dis_reason,students.dis_note,students.app_key,students.parent_app_key')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = students.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->where('student_session.class_id', $class_id);
        $this->db->where('student_session.section_id', $section_id);
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('users.role', 'student');

        if ($id != null) {
            $this->db->where('students.id', $id);
        } else {
            $this->db->order_by('student_session.roll_no', 'asc');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getStudentByClassSectionIDforinstall_simple($class_id = null, $section_id = null, $id = null)
    {

        $this->db->select('student_session.is_active as session_is_active,student_session.transport_fees,students.vehroute_id,student_session.roll_no,pass_status,students.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname, students.middlename, students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast,    students.dob ,students.current_address, students.previous_school,
            students.guardian_is,students.parent_id,
            students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email,students.dis_reason,students.dis_note,students.app_key,students.parent_app_key')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->where('student_session.class_id', $class_id);
        $this->db->where('student_session.section_id', $section_id);
        $this->db->where('student_session.session_id', $this->current_session);

        if ($id != null) {
            $this->db->where('students.id', $id);
        } else {
            $this->db->where('students.is_active', 'yes');
            // $this->db->where('student_session.is_active', 'yes');
            $this->db->order_by('student_session.roll_no', 'asc');
        }
        $query = $this->db->get();

        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }
    public function getStudentByClassSectionIDforinstall_simple_all($class_id = null, $section_id = null, $id = null,$isactive=null)
    {

        $this->db->select('student_session.is_active as session_is_active,student_session.transport_fees,students.vehroute_id,student_session.roll_no,pass_status,students.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname, students.middlename, students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast,    students.dob ,students.current_address, students.previous_school,
            students.guardian_is,students.parent_id,
            students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email,students.dis_reason,students.dis_note,students.app_key,students.parent_app_key')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->where('student_session.class_id', $class_id);
        $this->db->where('student_session.section_id', $section_id);
        $this->db->where('student_session.session_id', $this->current_session);

        if ($id != null) {
            $this->db->where('students.id', $id);
        } else {
            if($isactive==1)
            {
                $this->db->where('student_session.is_active', 'yes');
            }
            //$this->db->where('students.is_active', 'yes');
            // $this->db->where('student_session.is_active', 'yes');
            $this->db->order_by('student_session.roll_no', 'asc');
        }
        $query = $this->db->get();

        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }
    public function getStudentByClassSectionIDfordues($class_id = null, $section_id = null, $id = null)
    {
        $this->db->select('student_session.is_active as session_is_active,student_session.transport_fees,students.vehroute_id,student_session.roll_no,pass_status,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,students.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname, students.middlename, students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast, school_houses.house_name,   students.dob ,students.current_address, students.previous_school,
            students.guardian_is,students.parent_id,
            students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email, users.username,users.password,students.dis_reason,students.dis_note,students.app_key,students.parent_app_key')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = students.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->where('student_session.class_id', $class_id);
        $this->db->where('student_session.section_id', $section_id);
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('users.role', 'student');

        if ($id != null) {
            $this->db->where('students.id', $id);
        } else {
            //$this->db->where('students.is_active', 'yes');
            // $this->db->where('student_session.is_active', 'yes');
            $this->db->order_by('student_session.roll_no', 'asc');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getByStudentSessionSmall($student_session_id)
    {
        $this->db->select('student_session.transport_fees,students.app_key,students.vehroute_id,students.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,student_session.remark,student_session.total_att,student_session.student_att,student_session.pass_status,student_session.height,student_session.weight,classes.id AS `class_id`,classes.class,classes.code,sections.id AS `section_id`,sections.section,class_sections.id as `class_section_id`,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast,students.dob ,students.current_address, students.previous_school,
            students.guardian_is,students.parent_id,students.aadhar_name,students.aapar_id,students.dep_student_id,students.uid_no,
            students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email,students.dis_reason,students.dis_note,students.app_key,students.parent_app_key')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('class_sections', 'class_sections.class_id = classes.id and class_sections.section_id = sections.id');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where_in('student_session.id', $student_session_id);


        $this->db->order_by('student_session.roll_no');
        $query = $this->db->get();

        return $query->result_array();
    }
    public function getByStudentSessionOnly($student_session_id)
    {
        $this->db->select('*')->from('student_session');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where_in('student_session.id', $student_session_id);
        $query = $this->db->get();
        return $query->row_array();
    }
    public function getByStudentSession($student_session_id)
    {
        $this->db->select('student_session.transport_fees,students.app_key,students.vehroute_id,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,students.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,class_sections.id as `class_section_id`,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast, school_houses.house_name,   students.dob ,students.current_address, students.previous_school,
            students.guardian_is,students.parent_id,students.aadhar_name,students.application_no,
            students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email, users.username,users.password,students.dis_reason,students.dis_note,students.app_key,students.parent_app_key')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('class_sections', 'class_sections.class_id = classes.id and class_sections.section_id = sections.id');

        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = students.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');

        $this->db->where('student_session.session_id', $this->current_session);
        
        $this->db->where('users.role', 'student');

        $this->db->where('student_session.id', $student_session_id);

        $query = $this->db->get();

        return $query->row_array();
    }

    public function getByStudentSessionid($student_session_id)
    {
        $this->db->select('student_session.transport_fees,students.app_key,students.vehroute_id,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,students.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,class_sections.id as `class_section_id`,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast, school_houses.house_name,   students.dob ,students.current_address, students.previous_school,
            students.guardian_is,students.parent_id,students.sub_caste,
            students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email, users.username,users.password,students.dis_reason,students.dis_note,students.app_key,students.parent_app_key')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('class_sections', 'class_sections.class_id = classes.id and class_sections.section_id = sections.id');

        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = students.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');

        // $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('users.role', 'student');

        $this->db->where('student_session.id', $student_session_id);

        $query = $this->db->get();

        return $query->row_array();
    }

    public function getStudentBy_class_section_id($cls_section_id)
    {
        $i = 1;

        $custom_fields   = $this->customfield_model->get_custom_fields('students', 1);
        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->db->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }

        $field_variable = implode(',', $field_var_array);


        $this->db->select('student_session.transport_fees,students.app_key,students.vehroute_id,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,students.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,class_sections.id as `class_section_id`,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast, school_houses.house_name,   students.dob ,students.current_address, students.previous_school,
            students.guardian_is,students.parent_id,
            students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email, users.username,users.password,students.dis_reason,students.dis_note,students.app_key,students.parent_app_key,IFNULL(categories.category, "") as `category`,' . $field_variable)->from('students');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('class_sections', 'class_sections.class_id = classes.id and class_sections.section_id = sections.id');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = students.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('class_sections.id', $cls_section_id);
        $this->db->where('users.role', 'student');
        $this->db->where('students.is_active', 'yes');
        $this->db->where('student_session.is_active', 'yes');
        $query = $this->db->get();
        return $query->result_array();
    }


    public function get_student_only($id)
    {
        $this->db->select('students.*')->from('students');
        $this->db->where('students.id', $id);
        
        $query = $this->db->get();
        return $query->row_array();
    }
    public function get($id = null)
    {
        $this->db->select('student_session.transport_fees,remark,total_att,student_att,pass_status,students.app_key,students.parent_app_key,students.vehroute_id,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,students.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast, school_houses.house_name,   students.dob ,students.current_address, students.previous_school,
            students.guardian_is,students.parent_id,students.uid_no,students.aapar_id,students.dep_student_id,students.pan_no_father,students.pan_no_mother,students.adharno,students.parent_aadhar_no,students.tc_no,students.duplicate_tc_no,students.disability_type,students.disability_card_no,students.disability,students.sub_caste,students.place_of_birth,students.father_annual_income,students.mother_annual_income,students.application_no,student_session.house_id,students.aadhar_name,students.father_status,students.mother_status,
            students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email, users.username,users.password,students.dis_reason,students.dis_note,students.disable_at,students.aadhar_name')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = students.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = student_session.house_id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');

        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('users.role', 'student');
        if ($id != null) {
            $this->db->where('students.id', $id);
        } else {
            $this->db->where('students.is_active', 'yes');
            $this->db->order_by('students.id', 'desc');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getstudent($id = null)
    {
        $this->db->select('student_session.transport_fees,remark,total_att,student_att,pass_status,students.app_key,students.parent_app_key,students.vehroute_id,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,students.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast, school_houses.house_name,   students.dob ,students.current_address, students.previous_school,
            students.guardian_is,students.parent_id,students.uid_no,students.pan_no_father,students.pan_no_mother,students.parent_aadhar_no,students.tc_no,students.duplicate_tc_no,students.disability_type,students.disability_card_no,students.disability,
            students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email, users.username,users.password,students.dis_reason,students.dis_note,students.disable_at')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = students.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');

        // $this->db->where('student_session.session_id', $session_id);
        $this->db->where('users.role', 'student');
        if ($id != null) {
            $this->db->where('students.id', $id);
        } else {
            $this->db->where('students.is_active', 'yes');
            $this->db->order_by('students.id', 'desc');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function check_teacher($section_id, $class_id, $session_id)
    {
        $this->db->where(['class_id' => $class_id, 'section_id' => $section_id, 'staff_id' => $session_id]);
        $query = $this->db->get('class_teacher');
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function findByAdmission($admission_no = null)
    {

        $this->db->select('student_session.transport_fees,students.vehroute_id,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,students.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname, students.middlename, students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast, school_houses.house_name,   students.dob ,students.current_address, students.previous_school,
            students.guardian_is,students.parent_id,
            students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email, users.username,users.password,students.dis_reason,students.dis_note')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = students.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');

        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('users.role', 'student');
        $this->db->where('students.is_active', 'yes');
        $this->db->where('students.admission_no', $admission_no);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false;
    }

    public function search_alumniStudent($class_id = null, $section_id = null, $session_id = null)
    {

        $i = 1;

        $custom_fields   = $this->customfield_model->get_custom_fields('students', 1);
        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->db->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }

        $field_variable = implode(',', $field_var_array);

        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,GROUP_CONCAT(classes.class,"(",sections.section,")") as class,sections.id AS `section_id`,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender,' . $field_variable)->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.is_alumni', 1);
        $this->db->where('students.is_active', "yes");
        if ($class_id != null) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->db->where('student_session.section_id', $section_id);
        }
        if ($session_id != null) {
            $this->db->where('student_session.session_id', $session_id);
        }
        $this->db->group_by('students.id');
        $this->db->order_by('students.admission_no', 'asc');

        $query = $this->db->get();

        return $query->result_array();
    }

    public function search_alumniStudentbyAdmissionNo($searchterm, $carray)
    {

        $userdata = $this->customlib->getUserData();
        $staff_id = $userdata['id'];

        $i               = 1;
        $custom_fields   = $this->customfield_model->get_custom_fields('students', 1);
        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->db->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }

        $field_variable = implode(',', $field_var_array);

        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if (!empty($carray)) {

                $this->db->where_in("student_session.class_id", $carray);
                $sections = $this->teacher_model->get_teacherrestricted_modeallsections($staff_id);
                foreach ($sections as $key => $value) {
                    $sections_id[] = $value['section_id'];
                }
                $this->db->where_in("student_session.section_id", $sections_id);
            } else {
                $this->db->where_in("student_session.class_id", "");
            }
        }
        $this->db->select('classes.id AS `class_id`,students.id,student_session.id as student_session_id,GROUP_CONCAT(classes.class,"(",sections.section,")") as class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,      students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.father_name , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.gender,students.rte,student_session.session_id,' . $field_variable)->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        $this->db->where('student_session.is_alumni', '1');
        $this->db->group_start();
        $this->db->like('students.admission_no', $searchterm);
        $this->db->group_end();
        $this->db->group_by('students.id');
        $this->db->order_by('students.id');

        $query = $this->db->get();

        return $query->result_array();
    }

    public function guardian_credential($parent_id)
    {
        $this->db->select('id,user_id,username,password')->from('users');
        $this->db->where('id', $parent_id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function search_student()
    {
        $this->db->select('classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,students.category_id,    students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->where('student_session.session_id', $this->current_session);
        if ($id != null) {
            $this->db->where('students.id', $id);
        } else {
            $this->db->order_by('students.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getstudentdoc($id)
    {
        $this->db->select()->from('student_doc');
        $this->db->where('student_id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getDatatableByClassSection($class_id = null, $section_id = null)
    {
        $this->datatables
            ->select('classes.id as `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id as `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.app_key,students.parent_app_key,students.rte,students.gender')
            ->searchable('class_id,section_id,admission_no,students.firstname,students.middlename,  students.lastname,students.father_name,students.dob,students.guardian_phone')
            ->orderable('class_id,section_id,admission_no,students.firstname,students.father_name,students.dob,students.guardian_phone')
            ->join('student_session', 'student_session.student_id = students.id')
            ->join('classes', 'student_session.class_id = classes.id')
            ->join('sections', 'sections.id = student_session.section_id')
            ->join('categories', 'students.category_id = categories.id', 'left')
            ->where('student_session.session_id', $this->current_session)
            ->where('students.is_active', "yes")
            ->where('student_session.is_active', "yes")
            ->sort('student_session.roll_no', 'asc');
        if ($class_id != null) {
            $this->datatables->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->datatables->where('student_session.section_id', $section_id);
        }
        //     ->order_by('students.admission_no', 'asc')

        $this->datatables->from('students');
        return $this->datatables->generate('json');
    }
    public function getDatatableByClassSectionForFees($class_id = null, $section_id = null)
    {
        $this->datatables
            ->select('classes.id as `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id as `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.app_key,students.parent_app_key,students.rte,students.gender')
            ->searchable('class_id,section_id,admission_no,students.firstname,students.middlename,  students.lastname,students.father_name,students.dob,students.guardian_phone')
            ->orderable('class_id,section_id,admission_no,students.firstname,students.father_name,students.dob,students.guardian_phone')
            ->join('student_session', 'student_session.student_id = students.id')
            ->join('classes', 'student_session.class_id = classes.id')
            ->join('sections', 'sections.id = student_session.section_id')
            ->join('categories', 'students.category_id = categories.id', 'left')
            ->where('student_session.session_id', $this->current_session)
            ->where('students.is_active', "yes")
            ->where('student_session.is_active', "yes")
            ->sort('student_session.roll_no', 'asc');
        if ($class_id != null) {
            $this->datatables->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->datatables->where('student_session.section_id', $section_id);
        }
        //     ->order_by('students.admission_no', 'asc')

        $this->datatables->from('students');
        return $this->datatables->generate('json');
    }

    public function getDatatableByFullTextSearch($searchterm)
    {
        $this->datatables->select('`classes`.`id` as `class_id`,`students`.`id`,`student_session`.`id` as `student_session_id`,`classes`.`class`,sections.id as `section_id`,sections.section,students.id,students.admission_no, student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,      students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.father_name , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.gender,students.rte,student_session.session_id');
        $this->datatables->join('student_session', 'student_session.student_id = students.id');
        $this->datatables->join('classes', 'student_session.class_id = classes.id');
        $this->datatables->join('sections', 'sections.id = student_session.section_id');
        $this->datatables->join('categories', 'students.category_id = categories.id', 'left');
        $this->datatables->join('school_houses', 'students.school_house_id = school_houses.id', 'left');
        $this->datatables->group_start();
        $this->datatables->or_like_string('students.firstname,students.middlename,students.lastname,school_houses.house_name,students.guardian_name,students.adhar_no,students.samagra_id,student_session.roll_no,students.admission_no,students.mobileno,students.email,students.religion,students.cast,students.gender,students.current_address,students.permanent_address,students.blood_group,students.bank_name,students.ifsc_code,students.father_name,students.father_phone,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_name,students.guardian_relation,students.guardian_phone,students.guardian_occupation,students.guardian_address,students.guardian_email,students.previous_school,students.note', $searchterm);
        $this->datatables->group_end();
        $this->datatables->where('student_session.session_id', $this->current_session);
        $this->datatables->where('students.is_active', 'yes');
        $this->datatables->where('student_session.is_active', 'yes');
        $this->datatables->sort('students.admission_no', 'asc');
        $this->datatables->searchable('class_id,section_id,admission_no,students.firstname,students.middlename,  students.lastname,students.father_name,students.dob,students.guardian_phone');
        $this->datatables->orderable('class_id,section_id,admission_no,students.firstname,students.father_name,students.dob,students.guardian_phone');
        $this->datatables->from('students');
        return $this->datatables->generate('json');
    }
    public function getDatatableByFullTextSearchForFees($searchterm)
    {
        $this->datatables->select('`classes`.`id` as `class_id`,`students`.`id`,`student_session`.`id` as `student_session_id`,`classes`.`class`,sections.id as `section_id`,sections.section,students.id,students.admission_no, student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,      students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.father_name , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.gender,students.rte,student_session.session_id');
        $this->datatables->join('student_session', 'student_session.student_id = students.id');
        $this->datatables->join('classes', 'student_session.class_id = classes.id');
        $this->datatables->join('sections', 'sections.id = student_session.section_id');
        $this->datatables->join('categories', 'students.category_id = categories.id', 'left');
        $this->datatables->join('school_houses', 'students.school_house_id = school_houses.id', 'left');
        $this->datatables->group_start();
        $this->datatables->or_like_string('students.firstname,students.middlename,students.lastname,school_houses.house_name,students.guardian_name,students.adhar_no,students.samagra_id,student_session.roll_no,students.admission_no,students.mobileno,students.email,students.religion,students.cast,students.gender,students.current_address,students.permanent_address,students.blood_group,students.bank_name,students.ifsc_code,students.father_name,students.father_phone,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_name,students.guardian_relation,students.guardian_phone,students.guardian_occupation,students.guardian_address,students.guardian_email,students.previous_school,students.note', $searchterm);
        $this->datatables->group_end();
        $this->datatables->where('student_session.session_id', $this->current_session);
        $this->datatables->where('students.is_active', 'yes');
        $this->datatables->where('student_session.is_active', 'yes');
        $this->datatables->sort('students.admission_no', 'asc');
        $this->datatables->searchable('class_id,section_id,admission_no,students.firstname,students.middlename,  students.lastname,students.father_name,students.dob,students.guardian_phone');
        $this->datatables->orderable('class_id,section_id,admission_no,students.firstname,students.father_name,students.dob,students.guardian_phone');
        $this->datatables->from('students');
        return $this->datatables->generate('json');
    }

    public function searchByClassSection($class_id = null, $section_id = null)
    {

        $i = 1;

        $custom_fields   = $this->customfield_model->get_custom_fields('students', 1);
        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->db->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }

        $field_variable = implode(',', $field_var_array);

        $this->db->select('classes.id AS `class_id`,transport_route.route_title,students.blood_group,student_session.id_prn_cnt,school_houses.house_name,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.app_key,students.parent_app_key,students.rte,students.gender,' . $field_variable)->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->join('school_houses', 'student_session.house_id = school_houses.id', 'left');
        $this->db->join('transport_route', 'student_session.route = transport_route.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('student_session.is_active', "yes");
        if ($class_id != null) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->db->where('student_session.section_id', $section_id);
        }
        $this->db->order_by('student_session.roll_no', 'asc');

        $query = $this->db->get();

        return $query->result_array();
    }



    public function searchByClassSectionWithoutCurrent($class_id = null, $section_id = null, $student_id = null)
    {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', "yes");
        $this->db->where('students.id !=', $student_id);
        if ($class_id != null) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->db->where('student_session.section_id', $section_id);
        }
        $this->db->order_by('students.id');

        $query = $this->db->get();

        return $query->result_array();
    }



    public function searchdatatableByClassSectionCategoryGenderRte(
        $class_id = null,
        $section_id = null,
        $category = null,
        $gender = null,
        $rte = null
    ) {

        if ($class_id != null) {
            $this->datatables->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->datatables->where('student_session.section_id', $section_id);
        }
        if ($category != null) {
            $this->datatables->where('students.category_id', $category);
        }
        if ($gender != null) {
            $this->datatables->where('students.gender', $gender);
        }
        if ($rte != null) {
            $this->datatables->where('students.rte', $rte);
        }

        $this->datatables->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,students.category_id, categories.category,   students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender')

            ->searchable('sections.section,students.admission_no,students.firstname,students.father_name,students.dob,students.gender,categories.category,students.mobileno,students.samagra_id,students.adhar_no,students.rte')
            ->orderable('sections.section,students.admission_no,students.firstname,students.father_name,students.dob,students.gender,categories.category,students.mobileno,students.samagra_id,students.adhar_no,students.rte')
            ->join('student_session', 'student_session.student_id = students.id')
            ->join('classes', 'student_session.class_id = classes.id')
            ->join('sections', 'sections.id = student_session.section_id')
            ->join('categories', 'students.category_id = categories.id', 'left')
            ->where('student_session.session_id', $this->current_session)
            ->where('students.is_active', 'yes')
            ->sort('students.id')
            ->from('students');
        return $this->datatables->generate('json');
    }




    public function searchFullText($searchterm, $carray = null)
    {
        $userdata = $this->customlib->getUserData();
        $staff_id = $userdata['id'];

        $i             = 1;
        $custom_fields = $this->customfield_model->get_custom_fields('students', 1);

        $field_var_array = array();
        $field_var_array_name = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->db->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                array_push($field_var_array_name, 'table_custom_' . $i . '.field_value');
                $i++;
            }
        }
        $field_variable = (empty($field_var_array)) ? "" : "," . implode(',', $field_var_array);
        $field_name = (empty($field_var_array_name)) ? "" : "," . implode(',', $field_var_array_name);


        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if (!empty($carray)) {

                $this->db->where_in("student_session.class_id", $carray);
                $sections = $this->teacher_model->get_teacherrestricted_modeallsections($staff_id);
                foreach ($sections as $key => $value) {
                    $sections_id[] = $value['section_id'];
                }
                $this->db->where_in("student_session.section_id", $sections_id);
            } else {
                $this->db->where_in("student_session.class_id", "");
            }
        }

        $this->datatables->select('classes.id AS `class_id`,students.adhar_no,students.cast,students.mother_name,students.id,student_session.id as student_session_id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     DATE(students.dob) as dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,      students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.father_name , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.gender,students.rte,student_session.session_id' . $field_variable);
        $this->datatables->join('student_session', 'student_session.student_id = students.id');
        $this->datatables->join('classes', 'student_session.class_id = classes.id');
        $this->datatables->join('sections', 'sections.id = student_session.section_id');
        $this->datatables->join('categories', 'students.category_id = categories.id', 'left');
        $this->datatables->join('school_houses', 'students.school_house_id = school_houses.id', 'left');
        $this->datatables->group_start();
        $this->datatables->or_like_string('students.firstname,students.middlename,students.lastname,school_houses.house_name,students.guardian_name,students.adhar_no,students.samagra_id,student_session.roll_no,students.admission_no,students.mobileno,students.email,students.religion,students.cast,students.gender,students.current_address,students.permanent_address,students.blood_group,students.bank_name,students.ifsc_code,students.father_name,students.father_phone,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_name,students.guardian_relation,students.guardian_phone,students.guardian_occupation,students.guardian_address,students.guardian_email,students.previous_school,students.note', $searchterm);
        $this->datatables->group_end();
        $this->datatables->where('student_session.session_id', $this->current_session);
        $this->datatables->where('student_session.is_active', 'yes');
        $this->datatables->searchable('students.admission_no,students.firstname,students.middlename,students.lastname,classes.class,students.father_name,students.dob,students.gender,categories.category,students.mobileno' . $field_variable);
        $this->datatables->orderable('students.admission_no,roll_no,students.firstname,classes.class,students.father_name,dob,students.gender,categories.category,students.mobileno' . $field_name);
        $this->datatables->sort('students.id');
        $this->datatables->from('students');
        return $this->datatables->generate('json');
    }
    public function searchFullTextInactive($searchterm, $carray = null)
    {
        $userdata = $this->customlib->getUserData();
        $staff_id = $userdata['id'];

        $i             = 1;
        $custom_fields = $this->customfield_model->get_custom_fields('students', 1);

        $field_var_array = array();
        $field_var_array_name = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->db->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                array_push($field_var_array_name, 'table_custom_' . $i . '.field_value');
                $i++;
            }
        }
        $field_variable = (empty($field_var_array)) ? "" : "," . implode(',', $field_var_array);
        $field_name = (empty($field_var_array_name)) ? "" : "," . implode(',', $field_var_array_name);


        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if (!empty($carray)) {

                $this->db->where_in("student_session.class_id", $carray);
                $sections = $this->teacher_model->get_teacherrestricted_modeallsections($staff_id);
                foreach ($sections as $key => $value) {
                    $sections_id[] = $value['section_id'];
                }
                $this->db->where_in("student_session.section_id", $sections_id);
            } else {
                $this->db->where_in("student_session.class_id", "");
            }
        }

        $this->datatables->select('classes.id AS `class_id`,student_status_master.student_status,students.adhar_no,students.cast,students.mother_name,students.id,student_session.id as student_session_id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     DATE(students.dob) as dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,      students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.father_name , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.gender,students.rte,student_session.session_id' . $field_variable);
        $this->datatables->join('student_session', 'student_session.student_id = students.id');
        $this->datatables->join('classes', 'student_session.class_id = classes.id');
        $this->datatables->join('sections', 'sections.id = student_session.section_id');
        $this->datatables->join('categories', 'students.category_id = categories.id', 'left');
        $this->datatables->join('school_houses', 'students.school_house_id = school_houses.id', 'left');
        $this->datatables->join('student_pass_status', 'students.id = student_pass_status.student_id', 'left');
        $this->datatables->join('student_status_master', 'student_pass_status.status = student_status_master.id', 'left');
        $this->datatables->group_start();
        $this->datatables->or_like_string('students.firstname,students.middlename,students.lastname,school_houses.house_name,students.guardian_name,students.adhar_no,students.samagra_id,student_session.roll_no,students.admission_no,students.mobileno,students.email,students.religion,students.cast,students.gender,students.current_address,students.permanent_address,students.blood_group,students.bank_name,students.ifsc_code,students.father_name,students.father_phone,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_name,students.guardian_relation,students.guardian_phone,students.guardian_occupation,students.guardian_address,students.guardian_email,students.previous_school,students.note', $searchterm);
        $this->datatables->group_end();
        $this->datatables->where('student_session.session_id', $this->current_session);
        $this->datatables->where('student_session.is_active', 'no');
        $this->datatables->searchable('students.admission_no,students.firstname,students.middlename,students.lastname,classes.class,students.father_name,students.dob,students.gender,categories.category,students.mobileno' . $field_variable);
        $this->datatables->orderable('students.admission_no,roll_no,students.firstname,classes.class,students.father_name,dob,students.gender,categories.category,students.mobileno' . $field_name);
        $this->datatables->sort('students.id');
        $this->datatables->from('students');
        return $this->datatables->generate('json');
    }

    public function searchusersbyFullText($searchterm, $carray = null)
    {
        $userdata = $this->customlib->getUserData();
        $staff_id = $userdata['id'];

        $i             = 1;
        $custom_fields = $this->customfield_model->get_custom_fields('students', 1);

        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->db->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }

        $field_variable = implode(',', $field_var_array);

        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if (!empty($carray)) {

                $this->db->where_in("student_session.class_id", $carray);
                $sections = $this->teacher_model->get_teacherrestricted_modeallsections($staff_id);
                foreach ($sections as $key => $value) {
                    $sections_id[] = $value['section_id'];
                }
                $this->db->where_in("student_session.section_id", $sections_id);
            } else {
                $this->db->where_in("student_session.class_id", "");
            }
        }

        $this->db->select('classes.id AS `class_id`,students.id,student_session.id as student_session_id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,      students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.father_name , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.gender,students.rte,student_session.session_id,' . $field_variable)->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->join('school_houses', 'students.school_house_id = school_houses.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('student_session.is_active', 'yes');
        $this->db->group_start();

        $this->db->like('students.firstname', $searchterm);
        $this->db->or_like('students.middlename', $searchterm);
        $this->db->or_like('students.lastname', $searchterm);
        $this->db->or_like('school_houses.house_name', $searchterm);
        $this->db->or_like('students.guardian_name', $searchterm);
        $this->db->or_like('students.adhar_no', $searchterm);
        $this->db->or_like('students.samagra_id', $searchterm);
        $this->db->or_like('student_session.roll_no', $searchterm);
        $this->db->or_like('students.admission_no', $searchterm);
        $this->db->or_like('students.mobileno', $searchterm);
        $this->db->or_like('students.email', $searchterm);
        $this->db->or_like('students.religion', $searchterm);
        $this->db->or_like('students.cast', $searchterm);
        $this->db->or_like('students.gender', $searchterm);
        $this->db->or_like('students.current_address', $searchterm);
        $this->db->or_like('students.permanent_address', $searchterm);
        $this->db->or_like('students.blood_group', $searchterm);
        $this->db->or_like('students.bank_name', $searchterm);
        $this->db->or_like('students.ifsc_code', $searchterm);
        $this->db->or_like('students.father_name', $searchterm);
        $this->db->or_like('students.father_phone', $searchterm);
        $this->db->or_like('students.father_occupation', $searchterm);
        $this->db->or_like('students.mother_name', $searchterm);
        $this->db->or_like('students.mother_phone', $searchterm);
        $this->db->or_like('students.mother_occupation', $searchterm);
        $this->db->or_like('students.guardian_name', $searchterm);
        $this->db->or_like('students.guardian_relation', $searchterm);
        $this->db->or_like('students.guardian_phone', $searchterm);
        $this->db->or_like('students.guardian_occupation', $searchterm);
        $this->db->or_like('students.guardian_address', $searchterm);
        $this->db->or_like('students.guardian_email', $searchterm);
        $this->db->or_like('students.previous_school', $searchterm);
        $this->db->or_like('students.note', $searchterm);
        $this->db->group_end();
        $this->db->order_by('students.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function admission_report($searchterm, $carray = null, $condition = null)
    {
        $userdata = $this->customlib->getUserData();
        $i               = 1;
        $custom_fields   = $this->customfield_model->get_custom_fields('students', 1);
        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->db->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }
        $field_variable = implode(',', $field_var_array);
        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if (!empty($carray)) {

                $this->db->where_in("student_session.class_id", $carray);
            } else {
            }
        }
        if ($condition != null) {
            $this->datatables->where($condition);
        }
        /*----------------------------------------*/
        $this->datatables->select('classes.id AS `class_id`,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,      students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.father_name , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.gender,students.rte,student_session.session_id,' . $field_variable);
        $this->datatables->searchable('admission_no,students.firstname,classes.class,students.father_name,students.dob,students.admission_date,students.gender,categories.category');
        $this->datatables->orderable('admission_no,students.firstname,classes.class,students.father_name,students.dob,students.admission_date,students.gender,categories.category');
        $this->datatables->join('student_session', 'student_session.student_id = students.id');
        $this->datatables->join('classes', 'student_session.class_id = classes.id');
        $this->datatables->join('sections', 'sections.id = student_session.section_id');
        $this->datatables->join('categories', 'students.category_id = categories.id', 'left');
        $this->datatables->where('student_session.session_id', $this->current_session);
        $this->datatables->where('student_session.is_active', 'yes');

        $this->datatables->group_start();
        $this->datatables->or_like_string('students.firstname,students.lastname,students.guardian_name,students.adhar_no,students.samagra_id,student_session.roll_no,students.admission_no', $searchterm);
        $this->datatables->group_end();

        $this->datatables->sort('students.id');
        $this->datatables->from('students');
        return $this->datatables->generate('json');
    }
    public function admission_report_new($fromdate,$todate)
    {

        $result=$this->db->query("select student_session.student_id,student_session.id,student_session.roll_no, students.admission_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.mobileno,students.gender from student_session inner join students on student_session.student_id = students.id  where  student_session.session_id = '$this->current_session' and student_session.student_id in (select student_id from student_session where student_session.session_id <= '$this->current_session' group by student_id having count(*) = 1) order by students.admission_no");
        if($result)
        {
            return $result->result_array();
        }
        else
        {
            return false;
        }
        
    }
    public function student_ratio()
    {

        $i               = 1;
        $custom_fields   = $this->customfield_model->get_custom_fields('students', 1);
        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->db->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }

        $field_variable = implode(',', $field_var_array);

        $this->db->select(' count(*) as total_student, SUM(CASE WHEN `gender` = "Male" THEN 1 ELSE 0 END) AS "male",SUM(CASE WHEN `gender` = "Female" THEN 1 ELSE 0 END) AS "female", classes.class,sections.section, classes.id as class_id, sections.id as section_id')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('student_session.is_active', 'yes');
        $this->db->group_by('classes.id,sections.id');
        $this->db->order_by('classes.id,sections.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function sibling_report($searchterm, $carray = null, $condition = null)
    {
        $userdata = $this->customlib->getUserData();

        $i               = 1;
        $custom_fields   = $this->customfield_model->get_custom_fields('students', 1);
        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->db->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }

        $field_variable = implode(',', $field_var_array);
        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if (!empty($carray)) {
                $this->db->where_in("student_session.class_id", $carray);
            } else {
                $this->db->where_in("student_session.class_id", "");
            }
        }
        $this->db->select('classes.id AS `class_id`,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,      students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.father_name,students.mother_name , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.gender,students.rte,student_session.session_id,students.parent_id,' . $field_variable)->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('student_session.is_active', 'yes');
        if ($condition != null) {

            $this->db->where($condition);
        }
        $this->db->group_by('students.admission_no');
        $this->db->order_by('students.id');
        $query = $this->db->get();
        return $query->result_array();
    }


    public function sibling_reportsearch($searchterm, $carray = null, $condition = null)
    {

        $userdata = $this->customlib->getUserData();

        $i               = 1;
        $custom_fields   = $this->customfield_model->get_custom_fields('students', 1);
        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->db->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }

        $field_variable = implode(',', $field_var_array);
        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if (!empty($carray)) {

                $this->db->where_in("student_session.class_id", $carray);
            } else {
                $this->db->where_in("student_session.class_id", "");
            }
        }
        $this->db->select('students.parent_id')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('student_session.is_active', 'yes');
        if ($condition != null) {

            $this->db->where($condition);
        }
        $this->db->group_by('students.parent_id');
        $this->db->group_by('students.admission_no');
        $this->db->order_by('students.father_name');
        $query = $this->db->get();
        return $query->result_array();
    }


    public function getStudentListBYStudentsessionID($array)
    {
        $array = implode(',', $array);
        $sql   = ' SELECT students.* FROM students INNER join (SELECT * FROM `student_session` WHERE `student_session`.`id` IN (' . $array . ')) as student_session on students.id=student_session.student_id';
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function remove($id)
    {
        $this->db->trans_start();

        $sql   = "SELECT * FROM `users` WHERE childs LIKE '%," . $id . ",%' OR childs LIKE '" . $id . ",%' OR childs LIKE '%," . $id . "' OR childs = " . $id;
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            $result      = $query->row();
            $array_slice = explode(',', $result->childs);
            if (count($array_slice) > 1) {
                $arr    = array_diff($array_slice, array($id));
                $update = implode(",", $arr);
                $data   = array('childs' => $update);

                $this->db->where('id', $result->id);
                $this->db->update('users', $data);
            } else {
                $this->db->where('id', $result->id);
                $this->db->delete('users');
            }
        }

        $this->db->where('id', $id);
        $this->db->delete('students');

        $this->db->where('student_id', $id);
        $this->db->delete('student_session');

        $this->db->where('user_id', $id);
        $this->db->where('role', 'student');
        $this->db->delete('users');
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            return false;
        } else {
            return true;
        }
    }

    public function doc_delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('student_doc');
    }

    public function add($data, $data_setting = array())
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('students', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On students id " . $data['id'];
            $action    = "Update";
            $record_id = $insert_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            if (!empty($data_setting)) {

                if ($data_setting['adm_auto_insert']) {
                    if ($data_setting['adm_update_status'] == 0) {
                        $data_setting['adm_update_status'] = 1;
                        $this->setting_model->add($data_setting);
                    }
                }
                $this->db->insert('students', $data);
                $insert_id = $this->db->insert_id();
                $message   = INSERT_RECORD_CONSTANT . " On students id " . $insert_id;
                $action    = "Insert";
                $record_id = $insert_id;
                $this->log($message, $record_id, $action);

                return $insert_id;
            }
        }
    }

    public function add_import_update($data)
    {
        if (isset($data['admission_no'])) {
            $data_array = array(
                                    'category_id' => $data['category_id'],
                                    'religion'   => $data['religion'],
                                    'cast'   => $data['cast'],
                                    'father_phone' => $data['father_phone'],
                                    'mother_name' => $data['mother_name'],
                                    'mother_phone' => $data['mother_phone'],
                                    'father_occupation' => $data['father_occupation'],
                                    'mother_occupation' => $data['mother_occupation'],
                                    'current_address' =>  $data['current_address'],
                                    'blood_group' => $data['blood_group'],
                                    'adhar_no' => $data['adhar_no'],
                                );            

            //$this->db->query("update students set blood_group = '$blood_grp',current_address = '$current_add' where  admission_no = '$admno'");
             $this->db->where('admission_no', $data['admission_no']);
             $this->db->update('students', $data_array);
            // $message   = UPDATE_RECORD_CONSTANT . " On students id " . $data['id'];
            // $action    = "Update";
            // $record_id = $insert_id = $data['id'];
            // $this->log($message, $record_id, $action);
        }   
    }

    public function add_student_sibling($data_sibling)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('student_id',$data_sibling['student_id']);
        $this->db->where('sibling_student_id',$data_sibling['sibling_student_id']);
            $this->db->where('is_active','yes');
            $query=$this->db->get('student_sibling')->result_array();
            if(empty($query))
            {
            $this->db->insert('student_sibling', $data_sibling);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On student sibling id " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);
            }    
        else {
            // if (isset($data['id'])) {
            //     $this->db->where('id', $data['id']);
            //     $this->db->update('student_sibling', $data_sibling);
            //     $message   = UPDATE_RECORD_CONSTANT . " On  student sibling id " . $data['id'];
            //     $action    = "Update";
            //     $record_id = $insert_id = $data['id'];
            //     $this->log($message, $record_id, $action);
            // } else {
            // }
        }
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $insert_id;
        }
    }

    public function getsibling($student_id)
    {
        $this->db->where('student_id', $student_id);
        return $this->db->get('student_sibling')->row_array();
    }
    public function getmember($student_id)
    {
        $this->db->where('student_id', $student_id);
        return $this->db->get('trustmembership')->row_array();
    }

    public function add_student_session($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('session_id', $data['session_id']);
        $this->db->where('student_id', $data['student_id']);
        $q = $this->db->get('student_session');
        if ($q->num_rows() > 0) {
            $rec = $q->row_array();
            $this->db->where('id', $rec['id']);
            $this->db->update('student_session', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  student session id " . $rec['id'];
            $action    = "Update";
            $record_id = $rec['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('student_session', $data);
            $id        = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On  student session id " . $id;
            $action    = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);
        }
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return  $record_id;
        }
    }

    public function add_promote_student($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('session_id', $data['session_id']);
        $this->db->where('student_id', $data['student_id']);
        $q = $this->db->get('promotion_trn');
        if ($q->num_rows() > 0) {
            $rec = $q->row_array();
            $this->db->where('id', $rec['id']);
            $this->db->update('promotion_trn', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  promote students id " . $rec['id'];
            $action    = "Update";
            $record_id = $rec['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('promotion_trn', $data);
            $id        = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On  promote students id " . $id;
            $action    = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);
        }
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return  $record_id;
        }
    }
    public function add_member($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('student_id', $data['student_id']);
        $q = $this->db->get('trustmembership');
        if ($q->num_rows() > 0) {
            $rec = $q->row_array();
            $this->db->where('id', $rec['id']);
            $this->db->update('trustmembership', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  Trust Membership id " . $rec['id'];
            $action    = "Update";
            $record_id = $rec['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('trustmembership', $data);
            $id        = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On  Trust Membership id " . $id;
            $action    = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);
        }
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return true;
        }
    }

    public function add_student_session_update($data)
    {
        $this->db->where('session_id', $data['session_id']);
        $q = $this->db->get('student_session');
        if ($q->num_rows() > 0) {
            $this->db->where('session_id', $student_session);
            $this->db->update('student_session', $data);

            $message   = UPDATE_RECORD_CONSTANT . " On  student_session id " . $data['session_id'];
            $action    = "Update";
            $record_id = $data['session_id'];
            $this->log($message, $record_id, $action);

        } else {
            $this->db->insert('student_session', $data);
            return $this->db->insert_id();
            $id        = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On  student_session id " . $id;
            $action    = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);

        }

        $this->db->trans_complete(); # Completing transaction
            /* Optional */
    
            if ($this->db->trans_status() === false) {
                # Something went wrong.
                $this->db->trans_rollback();
                return false;
            } else {
                return $record_id;
            }
    }

    public function alumni_student_status($data)
    {
        $this->db->where('student_id', $data['student_id']);
        $this->db->where('session_id', $this->current_session);
        $this->db->update('student_session', $data);

        $message   = UPDATE_RECORD_CONSTANT . " On  student_session id " . $data['student_id'];
        $action    = "Update";
        $record_id = $data['student_id'];
        $this->log($message, $record_id, $action);

        $this->db->trans_complete(); # Completing transaction
            /* Optional */
    
            if ($this->db->trans_status() === false) {
                # Something went wrong.
                $this->db->trans_rollback();
                return false;
            } else {
                return $record_id;
            }
    }

    public function adddoc($data)
    {
        $this->db->insert('student_doc', $data);
        return $this->db->insert_id();
        $insert_id = $this->db->insert_id();
        $message   = INSERT_RECORD_CONSTANT . " On Student Doc " . $insert_id;
        $action    = "Insert";
        $record_id = $insert_id;
        $this->log($message, $record_id, $action);
        $this->db->trans_complete(); # Completing transaction
            /* Optional */
    
            if ($this->db->trans_status() === false) {
                # Something went wrong.
                $this->db->trans_rollback();
                return false;
            } else {
                return $record_id;
            }
    }

    public function read_siblings_students($parent_id)
    {
        $this->db->select('*')->from('students');
        $this->db->where('parent_id', $parent_id);
        $this->db->where('students.is_active', 'yes');
        $query = $this->db->get();
        return $query->result();
    }
    public function getMySiblings($parent_id, $student_id)
    {
        // $result=$this->db->query("select sibling_student_id from student_sibling where student_id = '$student_id' ")->result_array();
        // if(!empty($result))
        // {
        $this->db->select('students.*,classes.id as `class_id`,classes.class,sections.id as `section_id`,sections.section,student_session.session_id as `session_id`')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where_not_in('students.id', $student_id);
        $this->db->where('students.parent_id', $parent_id);
            //$this->db->where_in('students.id', $result);
        $this->db->where('student_session.is_active', 'yes');
        $query = $this->db->get();
        return $query->result();
        // }
        // else
        // { return false; }
    }
    public function getMySiblings_new($student_id)
    {
        $result=$this->db->query("select sibling_student_id from student_sibling where student_id = '$student_id' ")->result_array();
        
        if(!empty($result))
        {
            $student_ids = array_column($result, 'sibling_student_id');            
            $this->db->select('students.*,classes.id as `class_id`,classes.class,sections.id as `section_id`,sections.section,student_session.session_id as `session_id`')->from('students');
            $this->db->join('student_session', 'student_session.student_id = students.id');
            $this->db->join('classes', 'student_session.class_id = classes.id');
            $this->db->join('sections', 'sections.id = student_session.section_id');
            $this->db->join('categories', 'students.category_id = categories.id', 'left');
            $this->db->where('student_session.session_id', $this->current_session);
            $this->db->where_in('students.id', $student_ids);
            $this->db->where('student_session.is_active', 'yes');
            $query = $this->db->get();
            return $query->result();
        }
        else
        { return array(); }        
    }
    public function getMySiblings_new_reverse($student_id)
    {
        $result=$this->db->query("select student_id from student_sibling where sibling_student_id = '$student_id' ")->result_array();
        if(!empty($result))
        {
            $student_ids = array_column($result, 'student_id');            
            $this->db->select('students.*,classes.id as `class_id`,classes.class,sections.id as `section_id`,sections.section,student_session.session_id as `session_id`')->from('students');
            $this->db->join('student_session', 'student_session.student_id = students.id');
            $this->db->join('classes', 'student_session.class_id = classes.id');
            $this->db->join('sections', 'sections.id = student_session.section_id');
            $this->db->join('categories', 'students.category_id = categories.id', 'left');
            $this->db->where('student_session.session_id', $this->current_session);
            $this->db->where_in('students.id', $student_ids);
            $this->db->where('student_session.is_active', 'yes');
            $query = $this->db->get();
            return $query->result();
        }
        else
        { return array(); }        
    }
    public function delete_siblng($student_id)
    {
        $result=$this->db->query("select * from student_fees_discounts where fees_discount_id = '1' and  student_session_id in (select id from student_session where student_id = '$student_id' and session_id = '$this->current_session')")->row_array();
        if(!empty($result))
        {
            if($result['status']=='applied')
            {
                return false;
            }
            $this->db->where('id',$result['id']);
            $this->db->delete('student_fees_discounts');    
        }
        $this->db->where('student_id', $student_id);
        $this->db->delete('student_sibling');
        
        $message = DELETE_RECORD_CONSTANT . " On student_sibling id " . $student_id;
        $action = "Delete";
        $record_id = $student_id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return true;
        }
    }

    public function getAttedenceByDateandClass($date)
    {
        $sql   = "SELECT IFNULL(student_attendences.id, 0) as attencence FROM `student_session`left JOIN student_attendences on student_attendences.student_session_id=student_session.id and student_attendences.date=" . $this->db->escape($date) . " and student_attendences.attendence_type_id != 2 where student_session.class_id=7 and student_session.session_id=$this->current_session";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function searchCurrentSessionStudents()
    {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);

        $this->db->order_by('students.firstname', 'asc');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function searchLibraryStudent($class_id = null, $section_id = null)
    {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,
           IFNULL(libarary_members.id,0) as `libarary_member_id`,
           IFNULL(libarary_members.library_card_no,0) as `library_card_no`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname, students.middlename, students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->join('libarary_members', 'libarary_members.member_id = students.id and libarary_members.member_type = "student"', 'left');

        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        if ($class_id != null) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->db->where('student_session.section_id', $section_id);
        }
        $this->db->order_by('students.id');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function searchNameLike($searchterm)
    {
        $this->db->select('classes.id AS `class_id`,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,      students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.father_name , students.guardian_name , students.guardian_relation,students.guardian_email,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.gender,students.rte,students.app_key,students.parent_app_key,student_session.session_id')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('student_session.is_active', 'yes');
        $this->db->group_start();
        $this->db->like('students.firstname', $searchterm);
        $this->db->or_like('students.lastname', $searchterm);
        $this->db->group_end();
        $this->db->order_by('students.id');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function searchGuardianNameLike($searchterm)
    {
        $this->db->select('classes.id AS `class_id`,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.father_name , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.gender,students.guardian_email,students.rte,student_session.session_id,students.app_key,students.parent_app_key')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        $this->db->group_start();
        $this->db->like('students.guardian_name', $searchterm);

        $this->db->group_end();
        $this->db->order_by('students.id');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function searchByClassSectionWithSession($class_id = null, $section_id = null, $session_id = null)
    {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('student_session.is_active', 'yes');
        if ($class_id != null) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->db->where('student_session.section_id', $section_id);
        }
        $this->db->order_by('student_session.roll_no');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function searchNonPromotedStudents($class_id = null, $section_id = null, $promoted_session_id = null, $promoted_class_id = null, $promoted_section_id = null)
    {
        $sql = "SELECT promoted_students.id as `promoted_student_id`,`classes`.`id` AS `class_id`, `student_session`.`id` as `student_session_id`, `students`.`id`, `classes`.`class`, `sections`.`id` AS `section_id`, `sections`.`section`, `students`.`id`, `students`.`admission_no`, `student_session`.`roll_no`, `students`.`admission_date`, `students`.`firstname`, `students`.`middlename`, `students`.`lastname`, `students`.`image`, `students`.`mobileno`, `students`.`email`, `students`.`state`, `students`.`city`, `students`.`pincode`, `students`.`religion`, `students`.`dob`, `students`.`current_address`, `students`.`permanent_address`, IFNULL(students.category_id, 0) as `category_id`, IFNULL(categories.category, '') as `category`, `students`.`adhar_no`, `students`.`samagra_id`, `students`.`bank_account_no`, `students`.`bank_name`, `students`.`ifsc_code`, `students`.`guardian_name`, `students`.`guardian_relation`, `students`.`guardian_phone`, `students`.`guardian_address`, `students`.`is_active`, `students`.`created_at`, `students`.`updated_at`, `students`.`father_name`, `students`.`rte`, `students`.`gender` FROM `students` JOIN `student_session` ON `student_session`.`student_id` = `students`.`id` JOIN `classes` ON `student_session`.`class_id` = `classes`.`id` JOIN `sections` ON `sections`.`id` = `student_session`.`section_id` LEFT JOIN `categories` ON `students`.`category_id` = `categories`.`id` LEFT join (select * from student_session WHERE session_id=" . $promoted_session_id . " and class_id=" . $promoted_class_id . " and section_id=" . $promoted_section_id . ") as promoted_students on promoted_students.student_id=students.id WHERE `student_session`.`session_id` = " . $this->current_session . " AND `students`.`is_active` = 'yes' AND `student_session`.`class_id` = " . $class_id . " AND `student_session`.`section_id` = " . $section_id . " and promoted_students.id IS NULL ORDER BY student_session.roll_no";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getPreviousSessionStudent($previous_session_id, $class_id, $section_id)
    {
        $sql = "SELECT student_session.student_id as student_id, student_session.id as current_student_session_id, student_session.class_id as current_session_class_id ,previous_session.id as previous_student_session_id,students.firstname,students.middlename,students.lastname,students.admission_no,student_session.roll_no,students.father_name,students.admission_date FROM `student_session` left JOIN (SELECT * FROM `student_session` where session_id=$previous_session_id) as previous_session on student_session.student_id=previous_session.student_id INNER join students on students.id =student_session.student_id where student_session.session_id=$this->current_session and student_session.class_id=$class_id and student_session.section_id=$section_id and students.is_active='yes' ORDER BY student_session.roll_no ASC";

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function studentGuardianDetails($carray)
    {
        $userdata = $this->customlib->getUserData();

        $this->db->SELECT("students.admission_no,students.firstname,students.middlename,students.mobileno,students.father_phone,students.mother_phone,students.lastname,students.father_name,students.mother_name,students.guardian_name,students.guardian_relation,students.guardian_phone,students.id,classes.class,sections.section");
        $this->db->join("student_session", "student_session.student_id = students.id");
        $this->db->join("classes", "student_session.class_id = classes.id");
        $this->db->join("sections", "student_session.section_id = sections.id");
        $this->db->where("students.is_active", "yes");
        $this->db->where('student_session.session_id', $this->current_session);
        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {

            if (!empty($carray)) {

                $this->db->where_in("student_session.class_id", $carray);
            } else {
                $this->db->where_in("student_session.class_id", "");
            }
        }
        $query = $this->db->get("students");

        return $query->result_array();
    }

    public function searchGuardianDetails($class_id, $section_id)
    {

        $this->db->SELECT("students.admission_no,students.firstname,students.middlename,students.lastname,students.mobileno,students.father_phone,students.mother_phone,students.father_name,students.mother_name,students.guardian_name,students.guardian_relation,students.guardian_phone,students.id,classes.class,sections.section");
        $this->db->join("student_session", "student_session.student_id = students.id");
        $this->db->join("classes", "student_session.class_id = classes.id");
        $this->db->join("sections", "student_session.section_id = sections.id");
        $this->db->where("students.is_active", "yes");
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where(array('student_session.class_id' => $class_id, 'student_session.section_id' => $section_id));
        $query = $this->db->get("students");

        return $query->result_array();
    }

    public function studentAdmissionDetails($carray = null)
    {

        $userdata = $this->customlib->getUserData();
        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {

            if (!empty($carray)) {

                $this->db->where_in("student_session.class_id", $carray);
            } else {
                $this->db->where_in("student_session.class_id", "");
            }
        }
        $query = $this->db->SELECT("students.firstname,students.middlename,students.lastname,students.is_active, students.mobileno, students.id as sid ,students.admission_no, students.admission_date, students.guardian_name, students.guardian_relation, students.guardian_phone, classes.class, sessions.id, sections.section")->join("student_session", "students.id = student_session.student_id")->join("classes", "student_session.class_id = classes.id")->join("sections", "student_session.section_id = sections.id")->join("sessions", "student_session.session_id = sessions.id")->group_by("students.id")->get("students");

        return $query->result_array();
    }

    public function studentSessionDetails($id)
    {

        $query = $this->db->query("SELECT min(sessions.session) as start , max(sessions.session) as end, min(classes.class) as startclass, max(classes.class) as endclass from sessions join student_session on (sessions.id = student_session.session_id) join classes on (classes.id = student_session.class_id) where student_session.student_id = " . $id);

        return $query->row_array();
    }



    public function searchdatatablebyAdmissionDetails($class_id, $year)
    {

        if (!empty($year)) {

            $data = array('year(admission_date)' => $year, 'student_session.class_id' => $class_id);
        } else {
            $data = array('student_session.class_id' => $class_id);
        }

        $this->datatables->select('students.firstname,students.middlename,students.lastname,students.is_active, students.mobileno, students.id as sid ,students.admission_no, students.admission_date, students.guardian_name, students.guardian_relation, students.guardian_phone, classes.class, sessions.id, sections.section')
            ->searchable('students.admission_no,students.firstname,students.admission_date,students.mobileno,students.guardian_name,students.guardian_phone')
            ->join('student_session', 'students.id = student_session.student_id')
            ->join('classes', 'student_session.class_id = classes.id')
            ->join('sections', 'student_session.section_id = sections.id')
            ->join('sessions', 'student_session.session_id = sessions.id')
            ->where($data)
            ->group_by('students.id')
            ->orderable('students.admission_no,students.firstname,students.admission_date," "," "," ",students.mobileno,students.guardian_name,students.guardian_phone')
            ->sort('students.id')
            ->from('students');
        return $this->datatables->generate('json');
    }

    public function admissionYear()
    {

        $query = $this->db->SELECT("distinct(year(admission_date)) as year")->where_not_in('admission_date', array('0000-00-00', '1970-01-01'))->get("students");

        return $query->result_array();
    }

    public function getStudentSession($id)
    {

        $query = $this->db->query("SELECT  max(sessions.id) as student_session_id, max(sessions.session) as session from sessions join student_session on (sessions.id = student_session.session_id)  where student_session.student_id = " . $id);

        return $query->row_array();
    }

    public function valid_student_roll()
    {
        $roll_no    = $this->input->post('roll_no');
        $student_id = $this->input->post('studentid');
        $class      = $this->input->post('class_id');

        if ($roll_no != "") {

            if (!isset($student_id)) {
                $student_id = 0;
            }

            if ($this->check_rollno_exists($roll_no, $student_id, $class)) {
                $this->form_validation->set_message('check_exists', 'Roll Number should be unique at Class level');
                return false;
            } else {
                return true;
            }
        }
        return true;
    }

    public function check_rollno_exists($roll_no, $student_id, $class)
    {

        if ($student_id != 0) {
            $data  = array('students.id != ' => $student_id, 'student_session.class_id' => $class, 'student_session.roll_no' => $roll_no);
            $query = $this->db->where($data)->join("student_session", "students.id = student_session.student_id")->get('students');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {

            $this->db->where(array('class_id' => $class, 'roll_no' => $roll_no));
            $query = $this->db->join("student_session", "students.id = student_session.student_id")->get('students');
            // echo $this->db->last_query();die;
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function gethouselist()
    {

        $query = $this->db->where("is_active", "yes")->get("school_houses");

        return $query->result_array();
    }

    public function disableStudent($id, $data)
    {

        $this->db->where("id", $id)->update("students", $data);
    }

    public function getdisableStudent()
    {

        $this->db->select('classes.id AS `class_id`,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename, students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,      students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.father_name , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.gender,students.rte,student_session.session_id,dis_reason,dis_note')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'no');
        $this->db->order_by('students.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function disablestudentByClassSection($class, $section)
    {

        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender,dis_reason,dis_note')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', "no");
        if ($class != null) {
            $this->db->where('student_session.class_id', $class);
        }
        if ($section != null) {
            $this->db->where('student_session.section_id', $section);
        }
        $this->db->order_by('students.id');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function disablestudentFullText($searchterm)
    {
        $userdata = $this->customlib->getUserData();
        $class    = $this->class_model->get();
        if (!empty($class)) {
            foreach ($class as $ckey => $cvalue) {

                $carray[] = $cvalue["id"];
            }
        }
        $this->db->select('classes.id AS `class_id`,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname, students.middlename, students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,      students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.father_name , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.gender,students.rte,student_session.session_id,dis_reason,dis_note')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->join('school_houses', 'students.school_house_id = school_houses.id', 'left');
        $this->db->where('students.is_active', 'no');
        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {

            if (!empty($carray)) {

                $this->db->where_in("student_session.class_id", $carray);
            } else {
                //$this->db->where_in("student_session.class_id", "");
            }
        } else {
            $this->db->group_start();
            $this->db->like('students.firstname', $searchterm);
            $this->db->or_like('students.middlename', $searchterm);
            $this->db->or_like('students.lastname', $searchterm);
            $this->db->or_like('school_houses.house_name', $searchterm);
            $this->db->or_like('students.guardian_name', $searchterm);
            $this->db->or_like('students.adhar_no', $searchterm);
            $this->db->or_like('students.samagra_id', $searchterm);
            $this->db->or_like('student_session.roll_no', $searchterm);
            $this->db->or_like('students.admission_no', $searchterm);
            $this->db->or_like('students.mobileno', $searchterm);
            $this->db->or_like('students.email', $searchterm);
            $this->db->or_like('students.religion', $searchterm);
            $this->db->or_like('students.cast', $searchterm);
            $this->db->or_like('students.gender', $searchterm);
            $this->db->or_like('students.current_address', $searchterm);
            $this->db->or_like('students.permanent_address', $searchterm);
            $this->db->or_like('students.blood_group', $searchterm);
            $this->db->or_like('students.bank_name', $searchterm);
            $this->db->or_like('students.ifsc_code', $searchterm);
            $this->db->or_like('students.father_name', $searchterm);
            $this->db->or_like('students.father_phone', $searchterm);
            $this->db->or_like('students.father_occupation', $searchterm);
            $this->db->or_like('students.mother_name', $searchterm);
            $this->db->or_like('students.mother_phone', $searchterm);
            $this->db->or_like('students.mother_occupation', $searchterm);
            $this->db->or_like('students.guardian_name', $searchterm);
            $this->db->or_like('students.guardian_relation', $searchterm);
            $this->db->or_like('students.guardian_phone', $searchterm);
            $this->db->or_like('students.guardian_occupation', $searchterm);
            $this->db->or_like('students.guardian_address', $searchterm);
            $this->db->or_like('students.guardian_email', $searchterm);
            $this->db->or_like('students.previous_school', $searchterm);
            $this->db->or_like('students.note', $searchterm);
            $this->db->group_end();
        }
        $this->db->order_by('students.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getClassSection($id)
    {

        $query = $this->db->SELECT("*")->join("sections", "class_sections.section_id = sections.id")->where("class_sections.class_id", $id)->get("class_sections");
        return $query->result_array();
    }

    public function getStudentClassSection($id, $sessionid)
    {

        $query = $this->db->SELECT("students.firstname,students.middlename,students.id,students.lastname,students.image,student_session.section_id")->join("student_session", "students.id = student_session.student_id")->where("student_session.class_id", $id)->where("student_session.session_id", $sessionid)->where("students.is_active", "yes")->get("students");

        return $query->result_array();
        //SELECT `students`.`firstname`, `students`.`id`, `students`.`lastname`, `students`.`image`, `student_session`.`section_id` FROM `students` JOIN `student_session` ON `students`.`id` = `student_session`.`student_id` WHERE `student_session`.`class_id` = '1' AND `student_session`.`session_id` = '14' AND `students`.`is_active` = 'yes'
    }

    public function getStudentsByArray($array)
    {
        $i             = 1;
        $custom_fields = $this->customfield_model->get_custom_fields('students');

        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->db->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }

        $field_variable = implode(',', $field_var_array);

        $this->db->select('classes.id AS `class_id`,classes.code,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no ,students.blood_group, student_session.roll_no,students.admission_date,students.firstname, students.middlename, students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,students.blood_group ,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.cast,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.mother_name,students.updated_at,students.father_name,students.rte,students.gender,users.id as `user_tbl_id`,users.username,users.password as `user_tbl_password`,users.is_active as `user_tbl_active`,' . $field_variable)->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('users.role', 'student');
        $this->db->where_in('students.id', $array);
        $this->db->order_by('student_session.roll_no');

        $query = $this->db->get();
        return $query->result();
    }

    public function get_studentsession($student_session_id)
    {

        $query = $this->db->select('sessions.session')->join("student_session", "sessions.id = student_session.session_id")->where('student_session.id', $student_session_id)->get("sessions");
        return $query->row_array();
    }

    public function check_adm_exists($admission_no)
    {

        $this->db->where(array('admission_no' => $admission_no));
        $query = $this->db->get('students');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function lastRecord()
    {
        $last_row = $this->db->select('*')->order_by('id', "desc")->limit(1)->get('students')->row();
        return $last_row;
    }

    public function currentClassSectionById($studentid, $schoolsessionId)
    {
        return $this->db->select('class_id,section_id')->from('student_session')->where('session_id', $schoolsessionId)->where('student_id', $studentid)->get()->row_array();
    }

    public function reportClassSection($class_id = null, $section_id = null)
    {

        $i = 1;

        $custom_fields   = $this->customfield_model->get_custom_fields('students', 1);
        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->db->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }

        $field_variable = implode(',', $field_var_array);

        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender,' . $field_variable)->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', "yes");

        $this->db->where('student_session.class_id', $class_id);

        $this->db->where('student_session.section_id', $section_id);

        $this->db->group_by('students.id');
        $this->db->order_by('student_session.roll_no', 'asc');

        $query = $this->db->get();

        return $query->result_array();
    }

    public function getAllClassSection($class_id = null, $section_id = null)
    {

        $where = array();

        if ($class_id != null) {
            $where['class_id'] = $class_id;
        }

        if ($section_id != null) {
            $where['section_id'] = $section_id;
        }

        return $this->db->select('*')->from('class_sections')->join('classes', 'class_sections.class_id=classes.id', 'inner')->join('sections', 'class_sections.section_id=sections.id', 'inner')->where($where)->get()->result_array();
    }

    public function student_profile($condition)
    {

        $this->db->select('student_session.transport_fees,students.vehroute_id,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,students.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast, school_houses.house_name,   students.dob ,students.current_address, students.previous_school,
            students.guardian_is,students.parent_id,
            students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email, users.username,users.password,students.dis_reason,students.dis_note,category')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = students.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->join('categories', 'categories.id = students.category_id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('users.role', 'student');
        $this->db->where('student_session.is_active', 'yes');
        if ($condition != '') {
            $this->db->where($condition);
        }

        $this->db->order_by('students.id', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function student_profile2($class_id,$section_id)
    {

        $this->db->select('student_session.transport_fees,students.vehroute_id,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,students.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast, school_houses.house_name,   students.dob ,students.current_address, students.previous_school,
            students.guardian_is,students.parent_id,
            students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email, users.username,users.password,students.dis_reason,students.dis_note,category')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = students.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->join('categories', 'categories.id = students.category_id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('users.role', 'student');
        $this->db->where('student_session.is_active', 'yes');
        if ($class_id != '') {
            $this->db->where('student_session.class_id',$class_id);
        }
        if ($section_id != '') {
            $this->db->where('student_session.section_id',$section_id);
        }
        $this->db->order_by('classes.id', 'asc');
        $this->db->order_by('sections.id', 'asc');
        $this->db->order_by('student_session.roll_no', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function bulkdelete($students)
    {
        
        if (!empty($students)) {

            $this->db->trans_start();
            $student_comma_seprate = implode(', ', $students);
            //delete from students
            $this->db->where_in('id', $students);
            $this->db->delete('students');

            //delete from users
            $this->db->where_in('user_id', $students);
            $this->db->where_in('role', 'student');
            $this->db->delete('users');
            //delete from custom_field_value

            $sql = "DELETE FROM custom_field_values WHERE id IN (select * from (SELECT t2.id as `id` FROM `custom_fields` INNER JOIN custom_field_values as t2 on t2.custom_field_id=custom_fields.id WHERE custom_fields.belong_to='students' and t2.belong_table_id IN (" . implode(', ', $students) . ")) as m2)";

            $query = $this->db->query($sql);

            $sql_parent = "DELETE from users WHERE id in (SELECT id from (SELECT users.*,students.id as `student_id` FROM `users` LEFT JOIN students on users.id= students.parent_id WHERE role ='parent') as a WHERE a.student_id IS NULL)";
            $query      = $this->db->query($sql_parent);

            $this->db->trans_complete();

            if ($this->db->trans_status() === false) {
                return false;
            } else {
                return true;
            }
        }
    }


    public function checkstudent($student_id)
    {
        if (!empty($student_id)) {
            
            $this->db->where('student_id', $student_id);
            $row = $this->db->get('student_session')->row_array();
            
            $this->db->where('student_id', $student_id);
            $student_sessionRow = $this->db->get('student_session')->num_rows();

            $this->db->where('student_session_id', $row['id']);
            $examRow = $this->db->get('exam_group_class_batch_exam_students')->num_rows();

            $feesRow = $this->db->query("select * from student_fees_deposite where student_fees_deposite.student_fees_master_id in( SELECT id FROM `student_fees_master` where student_session_id in (select id from student_session where student_session.student_id= $student_id ))")->num_rows();

           
            if ($student_sessionRow > 1) {
                return 1;
            }elseif ($examRow > 0)  {
               return 1 ;
            }elseif ($feesRow > 0) {
               return 1;
            }else{
                return 0;
            }
            
        }
    }
    public function depromote_student($student_id)
    {
        
        if (!empty($student_id)) {

            $this->db->where('student_id', $student_id);
            $this->db->where('session_id', $this->current_session);
            
            $row = $this->db->get('student_session')->row_array();
            // echo "<pre>";
            // print_r ($student_session);
            // echo "</pre>";

            $this->db->trans_start();
            // $student_comma_seprate = implode(', ', $student_session_ids);
            //delete from students
            $this->db->where('id', $row['id']);
            $this->db->delete('student_session');

            $this->db->where('student_session_id', $row['id']);
            $this->db->delete('student_fees_master');

            $message = DELETE_RECORD_CONSTANT . " On student_fees_master id " . $row['id'];
            $action = "Delete";
            $record_id = $row['id'];
            $this->log($message, $record_id, $action);

            $this->db->trans_complete();

            if ($this->db->trans_status() === false) {
                return false;
            } else {
                return true;
            }
        }
    }
    public function checkstudent_for_depromote($student_id)
    {
        if (!empty($student_id)) {
            
            $this->db->where('student_id', $student_id);
            $this->db->where('session_id', $this->current_session);
            $row = $this->db->get('student_session')->row_array();

            // $this->db->where('student_id', $student_id);
            // $this->db->where('session_id', $this->current_session);
            // $rowsession = $this->db->get('student_session')->row_array();
            

            $this->db->where('student_id', $student_id);
            $student_sessionRow = $this->db->get('student_session')->num_rows();

            $this->db->where('student_session_id', $row['id']);
            $examRow = $this->db->get('exam_group_class_batch_exam_students')->num_rows();
            
            $feesRow = $this->db->query("select * from student_fees_deposite where student_fees_deposite.student_fees_master_id in( SELECT id FROM `student_fees_master` where student_session_id = '". $row['id'] . "' )")->num_rows();

            if ($student_sessionRow < 2) {
                return 1;
            }elseif ($examRow > 0)  {
               return 1 ;
            }elseif ($feesRow > 0) {
               return 1;
            }else{
                return 0;
            }
            
        }
    }

    public function valid_student_admission_no()
    {

        $admission_no = $this->input->post('admission_no');
        $student_id   = $this->input->post('studentid');

        if ($admission_no != "") {

            if (!isset($student_id)) {
                $student_id = 0;
            }

            if ($this->check_admission_no_exists($admission_no, $student_id)) {
                $this->form_validation->set_message('check_admission_no_exists', 'Admission No Exists');
                return false;
            } else {
                return true;
            }
        }
        return true;
    }

    public function check_admission_no_exists($admission_no, $student_id)
    {

        if ($student_id != 0) {
            $data  = array('students.id != ' => $student_id, 'students.admission_no' => $admission_no);
            $query = $this->db->where($data)->join("student_session", "students.id = student_session.student_id")->get('students');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {

            $this->db->where(array('class_id' => $class, 'admission_no' => $admission_no));
            $query = $this->db->join("student_session", "students.id = student_session.student_id")->get('students');

            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function search_alumniStudentReport($class_id = null, $section_id = null, $session_id = null)
    {

        $this->db->select('classes.id AS `class_id`,students.id,student_session.id as student_session_id,GROUP_CONCAT(classes.class,"(",sections.section,")") as class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,      students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.father_name , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.gender,students.rte,student_session.session_id')->from('alumni_students');

        $this->db->join('students', 'students.id = alumni_students.student_id');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.is_alumni', 1);
        $this->db->where('students.is_active', "yes");
        if ($class_id != null) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->db->where('student_session.section_id', $section_id);
        }
        if ($session_id != null) {
            $this->db->where('student_session.session_id', $session_id);
        }
        $this->db->group_by('students.id');
        $this->db->order_by('students.admission_no', 'asc');

        $query = $this->db->get();

        return $query->result_array();
    }

    public function search_alumniStudentbyAdmissionNoReport($searchterm, $carray)
    {

        $userdata = $this->customlib->getUserData();
        $staff_id = $userdata['id'];

        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if (!empty($carray)) {

                $this->db->where_in("student_session.class_id", $carray);
                $sections = $this->teacher_model->get_teacherrestricted_modeallsections($staff_id);
                foreach ($sections as $key => $value) {
                    $sections_id[] = $value['section_id'];
                }
                $this->db->where_in("student_session.section_id", $sections_id);
            } else {
                $this->db->where_in("student_session.class_id", "");
            }
        }
        $this->db->select('classes.id AS `class_id`,students.id,student_session.id as student_session_id,GROUP_CONCAT(classes.class,"(",sections.section,")") as class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,      students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.father_name , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.gender,students.rte,student_session.session_id')->from('alumni_students');

        $this->db->join('students', 'students.id = alumni_students.student_id');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        $this->db->where('student_session.is_alumni', '1');
        $this->db->group_start();
        $this->db->like('students.admission_no', $searchterm);
        $this->db->group_end();
        $this->db->group_by('students.id');
        $this->db->order_by('students.id');

        $query = $this->db->get();

        return $query->result_array();
    }

    public function getParentList()
    {
        $sql = "SELECT students.*,users.username,users.password,users.role,users.is_active FROM `students` INNER JOIN users on users.id = students.parent_id WHERE parent_id != 0 GROUP BY parent_id";

        $query   = $this->db->query($sql);
        $parents = $query->result();

        return $parents;
    }

    public function count_classteachers($class_id, $section_id)
    {

        $sql = "SELECT staff.id FROM `subject_timetable` JOIN `subject_group_subjects` ON `subject_timetable`.`subject_group_subject_id` = `subject_group_subjects`.`id`inner JOIN subjects on subject_group_subjects.subject_id = subjects.id INNER JOIN staff on staff.id=subject_timetable.staff_id   WHERE staff.is_active='1' and `subject_timetable`.`class_id` = " . $class_id . " AND `subject_timetable`.`section_id` = " . $section_id . "  AND `subject_timetable`.`session_id` = " . $this->current_session;

        $query   = $this->db->query($sql);
        $count   = $query->result();
        $teacher = array();
        if (!empty($count)) {
            foreach ($count as $key => $value) {
                $teacher[$value->id] = $value->id;
            }
        }

        return count($teacher);
        die;
    }

    //===========
    public function check_student_email_exists($str)
    {
        $email = $this->security->xss_clean($str);
        if ($email != "") {
            $id = $this->input->post('student_id');
            if (!isset($id)) {
                $id = 0;
            }

            if ($this->check_data_exists($email, $id)) {
                $this->form_validation->set_message('check_student_email_exists', $this->lang->line('record_already_exists'));
                return false;
            } else {
                return true;
            }
        }
        return true;
    }

    public function check_data_exists($email, $id)
    {
        $this->db->where('email', $email);
        $this->db->where('id !=', $id);

        $query = $this->db->get('students');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function searchdtByClassSection($class_id = null, $section_id = null)
    {

        $i = 1;
        $custom_fields   = $this->customfield_model->get_custom_fields('students', 1);
        $field_var_array = array();
        $field_var_array_name = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->datatables->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                array_push($field_var_array_name, 'table_custom_' . $i . '.field_value');
                $i++;
            }
        }

        $field_variable = (empty($field_var_array)) ? "" : "," . implode(',', $field_var_array);
        $field_name = (empty($field_var_array_name)) ? "" : "," . implode(',', $field_var_array_name);

        if ($class_id != null) {
            $this->datatables->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->datatables->where('student_session.section_id', $section_id);
        }

        $this->datatables->select('classes.id AS `class_id`,students.adhar_no,students.cast,students.mother_name,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     DATE(students.dob) as dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.app_key,students.parent_app_key,students.rte,students.gender' . $field_variable)
            ->searchable('students.admission_no,students.firstname,classes.class,students.father_name,students.dob,students.admission_date,students.gender,categories.category,students.mobileno' . $field_variable)
            ->join('student_session', 'student_session.student_id = students.id')
            ->join('classes', 'student_session.class_id = classes.id')
            ->join('sections', 'sections.id = student_session.section_id')
            ->join('categories', 'students.category_id = categories.id', 'left')
            ->where('student_session.session_id', $this->current_session)
            ->where('student_session.is_active', "yes")
            ->orderable('students.admission_no,student_session.roll_no,students.firstname,classes.class,students.father_name,students.dob,students.gender,categories.category,students.mobileno' . $field_name)
            ->from('students');


        $this->datatables->sort('student_session.roll_no', 'asc');
        return $this->datatables->generate('json');
    }
    public function searchdtByClassSectionInactive($class_id = null, $section_id = null)
    {

        $i = 1;
        $custom_fields   = $this->customfield_model->get_custom_fields('students', 1);
        $field_var_array = array();
        $field_var_array_name = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->datatables->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                array_push($field_var_array_name, 'table_custom_' . $i . '.field_value');
                $i++;
            }
        }

        $field_variable = (empty($field_var_array)) ? "" : "," . implode(',', $field_var_array);
        $field_name = (empty($field_var_array_name)) ? "" : "," . implode(',', $field_var_array_name);

        if ($class_id != null) {
            $this->datatables->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->datatables->where('student_session.section_id', $section_id);
        }

        $this->datatables->select('classes.id AS `class_id`,students.adhar_no,students.cast,students.mother_name,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     DATE(students.dob) as dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.app_key,students.parent_app_key,students.rte,student_status_master.student_status,students.gender,' . $field_variable)
            ->searchable('students.admission_no,students.firstname,classes.class,students.father_name,students.dob,students.admission_date,students.gender,categories.category,students.mobileno' . $field_variable)
            ->join('student_session', 'student_session.student_id = students.id')
            ->join('classes', 'student_session.class_id = classes.id')
            ->join('sections', 'sections.id = student_session.section_id')
            ->join('categories', 'students.category_id = categories.id', 'left')
            ->join('student_pass_status', 'students.id = student_pass_status.student_id', 'left')
            ->join('student_status_master', 'student_pass_status.status = student_status_master.id', 'left')
            ->where('student_session.session_id', $this->current_session)
            ->where('student_session.is_active', "no")
            ->orderable('students.admission_no,student_session.roll_no,students.firstname,classes.class,students.father_name,students.dob,students.gender,categories.category,students.mobileno' . $field_name)
            ->from('students');


        $this->datatables->sort('student_session.roll_no', 'asc');
        return $this->datatables->generate('json');
    }
    /* function to get record for login credential report */
    public function getdtforlogincredential($class_id = null, $section_id = null)
    {

        $i = 1;
        $custom_fields   = $this->customfield_model->get_custom_fields('students', 1);
        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->datatables->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }

        $field_variable = implode(',', $field_var_array);

        $this->datatables
            ->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.app_key,students.parent_app_key,students.rte,students.gender,' . $field_variable)
            ->searchable('students.admission_no,students.firstname')
            ->orderable('students.admission_no,students.firstname," "," ", " "')
            ->join('student_session', 'student_session.student_id = students.id')
            ->join('classes', 'student_session.class_id = classes.id')
            ->join('sections', 'sections.id = student_session.section_id')
            ->join('categories', 'students.category_id = categories.id', 'left')
            ->where('student_session.session_id', $this->current_session)
            ->where('students.is_active', "yes")
            ->from('students');

        if ($class_id != null) {
            $this->datatables->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->datatables->where('student_session.section_id', $section_id);
        }
        $this->datatables->sort('students.admission_no', 'asc');
        return $this->datatables->generate('json');
    }

    public function getUndefinedStudent()
    {
        $sql = "SELECT students.id FROM `students` LEFT join student_session on student_session.student_id=students.id WHERE student_session.id IS NULL";
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result;
    }

    public function getstudentbyclass_section($class_id, $section_id, $session_id = "")
    {
        $this->db->select('students.*,student_session.id as studentses_id,student_session.roll_no,IFNULL(categories.category, "") as category');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.class_id', $class_id);
        $this->db->where('student_session.section_id', $section_id);
        $this->db->where('student_session.session_id', $session_id);
        $this->db->where('student_session.is_active', 'yes');
        $this->db->order_by('student_session.roll_no', 'asc');
        $query = $this->db->get();
        return $query;
    }
    public function getstudentforcertificate($class_id, $section_id, $session_id = "",$main_id ="")
    {
        $this->db->select('students.*,student_session.id as studentses_id,student_session.roll_no,IFNULL(categories.category, "") as category');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.class_id', $class_id);
        $this->db->where('student_session.section_id', $section_id);
        $this->db->where('student_session.session_id', $session_id);
        if ($main_id == "") {
            
            $this->db->where('student_session.is_active', 'yes');
        }
        // $this->db->where('students.is_active', 'yes');
        $this->db->order_by('student_session.roll_no', 'asc');
        $query = $this->db->get();
        return $query;
    }

    public function getstudentforcheque($class_id, $section_id)
    {
        $this->db->select('students.*,student_session.id as studentses_id,student_session.roll_no,IFNULL(categories.category, "") as category');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.class_id', $class_id);
        $this->db->where('student_session.section_id', $section_id);
        $this->db->where('student_session.session_id', $this->current_session);
        // $this->db->where('student_session.is_active', 'yes');
        // $this->db->where('students.is_active', 'yes');
        $this->db->order_by('student_session.roll_no', 'asc');
        $query = $this->db->get();
        return $query;
    }

    //===========
    public function add_referred($insert_array)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['student_session_id'])) {
            $this->db->where('student_session_id', $data['student_session_id']);
            $this->db->update('referal_discount', $insert_array);
            $message   = UPDATE_RECORD_CONSTANT . " On  Referal Discount " . $data['id'];
            $action    = "Update";
            $record_id = $insert_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('referal_discount', $insert_array);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On Referal Discount " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);

            //return $insert_id;
        }
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $insert_id;
        }
    }

    public function getreferred($student_session_id)
    {
        $this->db->where('student_session_id', $student_session_id);
        return $this->db->get('referal_discount')->row_array();
    }

    public function add_student_status($insert_array)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (!empty($insert_array['id'])) {
            $this->db->where('id', $insert_array['id']);
            $this->db->update('student_status_master', $insert_array);
            $message   = UPDATE_RECORD_CONSTANT . " On  Student Status " . $insert_array['id'];
            $action    = "Update";
            $record_id = $insert_id = $insert_array['id'];
            $this->log($message, $record_id, $action);
        } else {

            $this->db->insert('student_status_master', $insert_array);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On Student Status " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);

            //return $insert_id;
        }
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $insert_id;
        }
    }

    public function get_student_status($id = null)
    {
        $this->db->select()->from('student_status_master');

        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function remove_student_status($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('student_status_master');
        $message = DELETE_RECORD_CONSTANT . " On Student Status id " . $id;
        $action = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

    public function student_status($insert_array)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (!empty($insert_array['id'])) {
            $this->db->where('id', $insert_array['id']);
            $this->db->update('student_status_update', $insert_array);
            $message   = UPDATE_RECORD_CONSTANT . " On  Student Status " . $insert_array['id'];
            $action    = "Update";
            $record_id = $insert_id = $insert_array['id'];
            $this->log($message, $record_id, $action);
        } else {

            $this->db->insert('student_status_update', $insert_array);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On Student Status " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);

            //return $insert_id;
        }
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $insert_id;
        }
    }

    public function student_update_status($insert_array)
    {
        $this->db->where('id', $insert_array['student_id']);
        $this->db->set('status', $insert_array['status']);
        $this->db->update('students');

        $this->db->where('id', $insert_array['student_session_id']);
        $this->db->set('status', $insert_array['status']);
        $this->db->update('student_session');

        $this->db->where('student_id', $insert_array['student_id']);
        $statusrow = $this->db->get('student_pass_status')->row_array();

        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (!empty($statusrow['id'])) {
            $this->db->where('student_id', $insert_array['student_id']);
            $this->db->update('student_pass_status', $insert_array);
            $message   = UPDATE_RECORD_CONSTANT . " On  Student Pass Status " . $insert_array['student_id'];
            $action    = "Update";
            $record_id = $insert_id = $insert_array['student_id'];
            $this->log($message, $record_id, $action);
        } else {

            $this->db->insert('student_pass_status', $insert_array);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On Student Pass Status " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);

            //return $insert_id;
        }
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $insert_id;
        }
    }

    public function student_pass_status($id = null)
    {
        $this->db->select()->from('student_pass_status');

        if ($id != null) {
            $this->db->where('student_id', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function reportClassSection2($class_id = null, $section_id = null)
    {

        $i = 1;

        $custom_fields   = $this->customfield_model->get_custom_fields('students', 1);
        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->db->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }

        $field_variable = implode(',', $field_var_array);

        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender,' . $field_variable)->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', "yes");

        $this->db->where('student_session.class_id', $class_id);

        $this->db->where('student_session.section_id', $section_id);

        $this->db->group_by('students.id');
        $this->db->order_by('student_session.roll_no', 'asc');

        $query = $this->db->get();

        $studentlist = $query->result_array();

        $data = array();
        $student_Array = array();
        if (!empty($studentlist)) {
            foreach ($studentlist as $key => $eachstudent) {
                $obj = new stdClass();
                $obj->name = $this->customlib->getFullName($eachstudent['firstname'], $eachstudent['middlename'], $eachstudent['lastname'], $this->sch_setting_detail->middlename, $this->sch_setting_detail->lastname);
                $obj->class = $eachstudent['class'];
                $obj->section = $eachstudent['section'];
                $obj->admission_no = $eachstudent['admission_no'];
                $obj->roll_no = $eachstudent['roll_no'];
                $obj->father_name = $eachstudent['father_name'];
                $student_session_id = $eachstudent['student_session_id'];
                $student_total_fees = $this->studentfeemaster_model->getStudentFees($student_session_id);

                if (!empty($student_total_fees)) {
                    $totalfee = 0;
                    $deposit = 0;
                    $discount = 0;
                    $balance = 0;
                    $fine = 0;
                    foreach ($student_total_fees as $student_total_fees_key => $student_total_fees_value) {

                        if (!empty($student_total_fees_value->fees)) {
                            foreach ($student_total_fees_value->fees as $each_fee_key => $each_fee_value) {

                                $totalfee = $totalfee + $each_fee_value->amount;

                                $amount_detail = json_decode($each_fee_value->amount_detail);

                                if (is_object($amount_detail)) {
                                    foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                                        $deposit = $deposit + $amount_detail_value->amount;
                                        $fine = $fine + $amount_detail_value->amount_fine;
                                        $discount = $discount + $amount_detail_value->amount_discount;
                                    }
                                }
                            }
                        }
                    }

                    $obj->totalfee = $totalfee;
                    $obj->payment_mode = "N/A";
                    $obj->deposit = $deposit;
                    $obj->fine = $fine;
                    $obj->discount = $discount;
                    $obj->balance = $totalfee - ($deposit + $discount);
                } else {

                    $obj->totalfee = 0;
                    $obj->payment_mode = 0;
                    $obj->deposit = 0;
                    $obj->fine = 0;
                    $obj->balance = 0;
                    $obj->discount = 0;
                }

                if ($obj->balance > 0) {
                    $student_Array[] = $obj;
                }
            }
        }
        $classlistdata[$class_id][] = array('result' => $student_Array);

        $totalfeelabel = array();
        $depositfeelabel = array();
        $discountlabel = array();
        $finelabel = array();
        $balancelabel = array();

        if (!empty($student_Array)) {
            foreach ($student_Array as  $student) {

                $totalfeelabel[] = number_format($student->totalfee, 2, '.', '');

                $depositfeelabel[] = number_format($student->deposit, 2, '.', '');

                $discountlabel[] = number_format($student->discount, 2, '.', '');

                $finelabel[] = number_format($student->fine, 2, '.', '');

                $balancelabel[] = $student->balance;
            }

           $data['grand_total'] =  array_sum($totalfeelabel);
           $data['grand_depositfee'] =  array_sum($depositfeelabel);
           $data['grand_discount'] =  array_sum($discountlabel);
           $data['grand_fine'] =  array_sum($finelabel);
           $data['grand_balance'] =  array_sum($balancelabel);
        }
        return $data;
    }
    public function get_studentsessionlist($student_id)
    {
       $this->db->where('student_id', $student_id);
       $this->db->order_by('id', 'asc');
       return $this->db->get('student_session')->result_array();
       
    }
    public function get_studentsessionlist_current($student_id)
    {
       $this->db->where('student_id', $student_id);
       $this->db->where('session_id', $this->current_session);
       $this->db->order_by('id', 'asc');
       return $this->db->get('student_session')->result_array();
       
    }    
    public function get_studentsessionlist_previous($student_id)
    {
       $this->db->where('student_id', $student_id);
       $this->db->where('session_id < ', $this->current_session);
       $this->db->order_by('id', 'asc');
       return $this->db->get('student_session')->result_array();
       
    }    
    public function delete_student_session($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('student_session');

        $message = DELETE_RECORD_CONSTANT . " On student_session id " . $id;
        $action = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            return false;
        } else {
            return true;
        }
    }
    public function get_category_id($category)
    {
        $res=$this->db->query("select * from categories where category = '$category'")->result_array();
        if(empty($res))
        {
                                $data_new = array(
                                    'category' => $category,
                                );
                                $this->db->insert('categories', $data_new);
                                $insert_id = $this->db->insert_id();                                
                                return $insert_id;
        }
        else
        {
            return $res[0]['id'];
        }
        
        
    }

    public function getByClassSection($class_id, $section_id,$by_wise)
    {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.app_key,students.parent_app_key,students.rte,students.gender,')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('student_session.is_active', "yes");
        if ($class_id != null) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->db->where('student_session.section_id', $section_id);
        }

        if ($by_wise == "Gender") {

            $this->db->order_by('students.gender,firstname,middlename,lastname');
        }else{
            $this->db->order_by('students.firstname');

        }

        $query = $this->db->get();

        return $query->result_array();
    }

    public function generate_roll_no($student_session_id,$array)
    {
        $this->db->where('id', $student_session_id);
        $this->db->update('student_session', $array);
        
    }

    public function getclassbysectionid($id = null)
    {
        $this->db->select()->from('classes');
        $this->db->join('class_sections', 'class_sections.class_id = classes.id');
        $this->db->join('sections', 'sections.id = class_sections.section_id');
        
        $this->db->where('sch_section_id', $id);
        $query = $this->db->get();
        return $query->result();
       
    }

    public function change_div($student_session_id,$data)
    {
        
        // echo "<pre>";
        // print_r ($data);die;
        // echo "</pre>";
        
        $this->db->where_in('id', $student_session_id);
        $res=$this->db->update('student_session', $data);
        
    }
    public function change_class($student_session_id,$data)
    {
        
        $this->db->where_in('id', $student_session_id);
        $res=$this->db->update('student_session', $data);
        
    }

    public function div_insert($insert_array)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('student_seesion_id', $insert_array['student_seesion_id']);
        $q =$this->db->get('student_div_change')->num_rows();
        if ($q >0) {
            $this->db->where('student_seesion_id', $insert_array['student_seesion_id']);
            $this->db->update('student_div_change', $insert_array);
            $message   = UPDATE_RECORD_CONSTANT . " On  Student Division Change " . $insert_array['student_seesion_id'];
            $action    = "Update";
            $record_id = $insert_id = $insert_array['student_seesion_id'];
            $this->log($message, $record_id, $action);
        } else {

            $this->db->insert('student_div_change', $insert_array);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On Student Division Change " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);

            //return $insert_id;
        }
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $insert_id;
        }
    }

    public function check_student_exist($student_id,$new_session_id)
    {
       $query= $this->db->query("select id from student_session  where student_id = '$student_id' and session_id = '$new_session_id'");
       if (!empty($query)) {
        return true;
       } else {
        return false;
       }
       

    }

    public function gethouse($id = null) {
        $this->db->select()->from('school_houses');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getReminderlist($id = null) {
        $this->db->select()->from('reminder_trn');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }
    public function getchecklist($id = null) {
        $this->db->select()->from('checklist_mst');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getchecklistforstudent() {
        $this->db->select()->from('checklist_mst');
        $this->db->where('type', 2);
        $this->db->order_by('id');
        $query = $this->db->get();
        return $query->result_array();
        
    }
    public function getchecklistofstudent($student_id) {
        $this->db->select('student_checklist.*,checklist_mst.*,checklist_mst.id as master_id,student_checklist.id as sub_id')->from('student_checklist');
        $this->db->join('checklist_mst', 'checklist_mst.id = student_checklist.checklist_id');
        $this->db->where('student_id', $student_id);
        $this->db->where('checklist_mst.type', 2);
        $this->db->order_by('student_checklist.id');
        $query = $this->db->get();
        return $query->result_array();
        
    }
    
    public function addstudentchecklist($student_id)
    {
        $userdata = $this->customlib->getUserData();
        $staff_id = $userdata['name'];
        $created_at = date('Y-m-d h:i:s');
        $query="insert into student_checklist(student_id,checklist_id,status,created_by,created_at) select ".$student_id.", id,'Pending', '".$staff_id."', '".$created_at."' from checklist_mst";
        $this->db->query($query);
        
    }
    public function addchecklist($insert_array)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        
        if (!empty($insert_array['id'])) {
            $this->db->where('id', $insert_array['id']);
            $this->db->update('checklist_mst', $insert_array);
            $message   = UPDATE_RECORD_CONSTANT . " On  checklist_mst " . $insert_array['id'];
            $action    = "Update";
            $record_id = $insert_id = $insert_array['id'];
            $this->log($message, $record_id, $action);
        } else {

            $this->db->insert('checklist_mst', $insert_array);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On checklist_mst " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);

            //return $insert_id;
        }
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $insert_id;
        }
    }
    public function addreminder($insert_array)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        
        if (!empty($insert_array['id'])) {
            $this->db->where('id', $insert_array['id']);
            $this->db->update('reminder_trn', $insert_array);
            $message   = UPDATE_RECORD_CONSTANT . " On  reminder_trn " . $insert_array['id'];
            $action    = "Update";
            $record_id = $insert_id = $insert_array['id'];
            $this->log($message, $record_id, $action);
        } else {

            $this->db->insert('reminder_trn', $insert_array);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On reminder_trn " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);

            //return $insert_id;
        }
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $insert_id;
        }
    }

    public function deleteChecklist_mst($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('checklist_mst');
        $message = DELETE_RECORD_CONSTANT . " On checklist_mst id " . $id;
        $action = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }
    public function deletereminder($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('reminder_trn');
        $message = DELETE_RECORD_CONSTANT . " On reminder_trn id " . $id;
        $action = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

    public function addstudent_checklist($insert_array)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        
        if (!empty($insert_array['id'])) {
            $this->db->where('id', $insert_array['id']);
            $this->db->update('student_checklist', $insert_array);
            $message   = UPDATE_RECORD_CONSTANT . " On  student_checklist " . $insert_array['id'];
            $action    = "Update";
            $record_id = $insert_id = $insert_array['id'];
            $this->log($message, $record_id, $action);
        } else {

            $this->db->insert('student_checklist', $insert_array);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On student_checklist " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);

            //return $insert_id;
        }
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $insert_id;
        }
    }

    public function getbonafideresult($id)
    {
        $this->db->select('classes.id AS `class_id`,classes.code,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no ,students.blood_group, student_session.roll_no,students.admission_date,students.firstname, students.middlename, students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,students.blood_group ,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.cast,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.mother_name,students.updated_at,students.father_name,students.rte,students.gender,users.id as `user_tbl_id`,users.username,users.password as `user_tbl_password`,users.is_active as `user_tbl_active`')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('users.role', 'student');
        $this->db->where('students.id', $id);
        $this->db->order_by('student_session.roll_no');

        $query = $this->db->get();
        return $query->result();
    }
    public function getbonafideresult_id($id)
    {
        $res = $this->db->query('select * from bonafide_trn where id = ' . $id)->row_array();
        $student_id = $res['student_session_id'];
        $this->db->select('classes.id AS `class_id`,classes.code,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no ,students.blood_group, student_session.roll_no,students.admission_date,students.firstname, students.middlename, students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,students.blood_group ,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.cast,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.mother_name,students.updated_at,students.father_name,students.rte,students.gender,students.aadhar_name,users.id as `user_tbl_id`,users.username,users.password as `user_tbl_password`,users.is_active as `user_tbl_active`')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('users.role', 'student');
        $this->db->where('student_session.id', $student_id);
        $this->db->order_by('student_session.roll_no');

        $query = $this->db->get();
        return $query->result();
    }

    public function getfeecerticateresult($id)
    {
        $this->db->select('classes.id AS `class_id`,students.aadhar_name,students.father_name,fees_certicate_trn.id as feestrn_id,classes.code,student_session.id as student_session_id,students.id,classes.class,classes.code,sections.id AS `section_id`,sections.section,students.id,students.admission_no ,students.blood_group, student_session.roll_no,students.admission_date,students.firstname, students.middlename, students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,students.blood_group ,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.cast,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.mother_name,students.updated_at,students.father_name,students.rte,students.gender,users.id as `user_tbl_id`,users.username,users.password as `user_tbl_password`,users.is_active as `user_tbl_active`')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->join('fees_certicate_trn', 'fees_certicate_trn.student_session_id = student_session.id', 'left');
        // $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('users.role', 'student');
        $this->db->where('fees_certicate_trn.id', $id);
        $this->db->order_by('student_session.roll_no');

        $query = $this->db->get();
        return $query->result();
    }

    public function getDepartmentalArray()
    {
        $query=$this->db->query("select classes.class,classes.code,sections.section,student_session.student_id,student_session.id,student_session.roll_no,students.dob,students.religion,students.category_id, students.admission_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.mobileno,students.gender from student_session inner join students on student_session.student_id = students.id inner join classes on student_session.class_id = classes.id inner join sections on  student_session.section_id = sections.id   where   student_session.is_active = 'yes' and student_session.session_id = '$this->current_session' order by students.admission_no");
        return $query->result_array();
    }
    public function getSchSectionForDeptmt($sch_section_id)
    {
        //echo "select classes.class,sections.section,student_session.student_id,student_session.id,student_session.roll_no, students.admission_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.mobileno,students.gender from student_session inner join students on student_session.student_id = students.id inner join classes on student_session.class_id = classes.id inner join sections on  student_session.section_id = sections.id   where student_session.is_active = 'yes' and student_session.session_id = '$this->current_session'  and classes.sch_section_id ='$sch_section_id'  order by students.admission_no";die();
        $query=$this->db->query("select classes.class,sections.section,student_session.student_id,student_session.id,student_session.roll_no, students.admission_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.mobileno,students.gender from student_session inner join students on student_session.student_id = students.id inner join classes on student_session.class_id = classes.id inner join sections on  student_session.section_id = sections.id   where student_session.is_active = 'yes' and student_session.session_id = '$this->current_session'  and classes.sch_section_id ='$sch_section_id'  order by students.admission_no");
        return $query->result_array();
         
    }
    public function getDepartmentalArrayOnAge()
    {
        $query=$this->db->query("select TIMESTAMPDIFF(YEAR,dob,CURDATE()) as age,classes.class,classes.code,sections.section,student_session.student_id,student_session.id,student_session.roll_no,students.religion,students.dob,students.category_id, students.admission_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.mobileno,students.gender from student_session inner join students on student_session.student_id = students.id inner join classes on student_session.class_id = classes.id inner join sections on  student_session.section_id = sections.id   where   student_session.is_active = 'yes' and student_session.session_id = '$this->current_session' order by students.admission_no");
        return $query->result_array();
    }
    public function getAdmissionreport($start_date,$end_date)
    {
        ini_set('display_errors', 1);
        $query=$this->db->query("select classes.class,sections.section,student_session.student_id,student_session.id,student_session.roll_no, students.admission_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.mobileno,students.gender from student_session inner join students on student_session.student_id = students.id inner join classes on student_session.class_id = classes.id inner join sections on  student_session.section_id = sections.id   where students.admission_date >= '".date('Y-m-d',strtotime($start_date))."' and students.admission_date <= '".date('Y-m-d',strtotime($end_date))."' and student_session.session_id = '$this->current_session' and student_session.student_id in (select student_id from student_session where  student_session.session_id <= '$this->current_session' group by student_id having count(*) = 1) order by classes.sch_section_id, CONVERT(students.admission_no, UNSIGNED) DESC");
        return $query->result_array();
    }
    public function getStudentDetails()
    {
        $query = $this->db->query("select id from student_session where id in (select id from student_session where session_id = '$this->current_session' )");
    }
    public function getAdmissionreportBrief2($start_date,$end_date)
    {
        $query=$this->db->query("select classes.class,classes.code,sections.section,student_session.student_id,student_session.id,student_session.roll_no,students.dob,students.religion,students.category_id, students.admission_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.mobileno,students.gender from student_session inner join students on student_session.student_id = students.id inner join classes on student_session.class_id = classes.id inner join sections on  student_session.section_id = sections.id   where students.admission_date >= '".date('Y-m-d',strtotime($start_date))."' and students.admission_date <= '".date('Y-m-d',strtotime($end_date))."' and student_session.session_id = '$this->current_session' and student_session.student_id in (select student_id from student_session where student_session.is_active = 'yes' and student_session.session_id <= '$this->current_session' group by student_id having count(*) = 1) order by students.admission_no");
        return $query->result_array();
    }
    public function getAdmissionreportBrief($start_date,$end_date,$class_id,$section_id)
    {
        $query=$this->db->query("select classes.class,sections.section,student_session.student_id,student_session.id,student_session.roll_no, students.admission_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.mobileno,students.gender from student_session inner join students on student_session.student_id = students.id inner join classes on student_session.class_id = classes.id inner join sections on  student_session.section_id = sections.id   where students.admission_date >= '".date('Y-m-d',strtotime($start_date))."' and students.admission_date <= '".date('Y-m-d',strtotime($end_date))."' and student_session.session_id = '$this->current_session' and student_session.student_id in (select student_id from student_session where student_session.is_active = 'yes' and classes.id ='$class_id' and sections.id ='$section_id' and student_session.session_id <= '$this->current_session' group by student_id having count(*) = 1) order by students.admission_date");
        return $query->result_array();
         
    }
    public function getSchSectionData($start_date,$end_date,$sch_section_id)
    {
        $query=$this->db->query("select classes.class,sections.section,student_session.student_id,student_session.id,student_session.roll_no, students.admission_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.mobileno,students.gender from student_session inner join students on student_session.student_id = students.id inner join classes on student_session.class_id = classes.id inner join sections on  student_session.section_id = sections.id   where students.admission_date >= '".date('Y-m-d',strtotime($start_date))."' and students.admission_date <= '".date('Y-m-d',strtotime($end_date))."' and student_session.session_id = '$this->current_session' and student_session.student_id in (select student_id from student_session where student_session.is_active = 'yes' and classes.sch_section_id ='$sch_section_id'  and student_session.session_id <= '$this->current_session' group by student_id having count(*) = 1) order by students.admission_date");
        return $query->result_array();
         
    }
    public function get_class_wise_Admission_report($start_date,$end_date)
    {
        $query=$this->db->query("select classes.class,sections.section,student_session.student_id,student_session.id,student_session.roll_no, students.admission_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.mobileno,students.gender from student_session inner join students on student_session.student_id = students.id inner join classes on student_session.class_id = classes.id inner join sections on  student_session.section_id = sections.id   where students.admission_date >= '".date('Y-m-d',strtotime($start_date))."' and students.admission_date <= '".date('Y-m-d',strtotime($end_date))."' and student_session.session_id = '$this->current_session' and student_session.student_id in (select student_id from student_session where student_session.is_active = 'yes' and  student_session.session_id <= '$this->current_session' group by student_id having count(*) = 1) order by student_session.class_id,student_session.section_id,students.admission_date");
        return $query->result_array();
    }
    public function get_division_wise_Admission_report($start_date,$end_date)
    {
        $query=$this->db->query("select classes.class,sections.section,student_session.student_id,student_session.id,student_session.roll_no, students.admission_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.mobileno,students.gender from student_session inner join students on student_session.student_id = students.id inner join classes on student_session.class_id = classes.id inner join sections on  student_session.section_id = sections.id   where students.admission_date >= '".date('Y-m-d',strtotime($start_date))."' and students.admission_date <= '".date('Y-m-d',strtotime($end_date))."' and student_session.session_id = '$this->current_session' and student_session.student_id in (select student_id from student_session where student_session.is_active = 'yes' and  student_session.session_id <= '$this->current_session' group by student_id having count(*) = 1) order by student_session.class_id,student_session.section_id,students.admission_date");
        return $query->result_array();
    }    
    public function get_admission_count($start_date,$end_date)
    {

        $query=$this->db->query("select student_session.id from student_session inner join students on student_session.student_id = students.id inner join classes on student_session.class_id = classes.id inner join sections on  student_session.section_id = sections.id   where students.admission_date >= '".date('Y-m-d',strtotime($start_date))."' and students.admission_date <= '".date('Y-m-d',strtotime($end_date))."' and student_session.session_id = '$this->current_session' and student_session.student_id in (select student_id from student_session where student_session.is_active = 'yes' and  student_session.session_id <= '$this->current_session' group by student_id having count(*) = 1) order by student_session.class_id,student_session.section_id,students.admission_date");
        return $query->num_rows();
    }  
    
    public function getstudentByAge($start_date,$end_date)
    {
        $query=$this->db->query("select TIMESTAMPDIFF(YEAR,dob,CURDATE()) as age,classes.class,classes.code,sections.section,student_session.student_id,student_session.id,student_session.roll_no,students.dob,students.religion,students.category_id, students.admission_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.mobileno,students.gender from student_session inner join students on student_session.student_id = students.id inner join classes on student_session.class_id = classes.id inner join sections on  student_session.section_id = sections.id   where students.admission_date >= '".date('Y-m-d',strtotime($start_date))."' and students.admission_date <= '".date('Y-m-d',strtotime($end_date))."' and student_session.session_id = '$this->current_session' and student_session.student_id in (select student_id from student_session where student_session.is_active = 'yes' and student_session.session_id <= '$this->current_session' group by student_id having count(*) = 1) order by students.admission_no");
        return $query->result_array();
    }
    public function getclassLastRoll($class_id,$section_id,$session_id)
    {
        $this->db->where('class_id', $class_id);
        $this->db->where('section_id', $section_id);
        $this->db->where('session_id', $session_id);
        $this->db->where('is_active', "yes");
        $this->db->order_by('roll_no', 'desc');
        $q =$this->db->get('student_session',1);
        return $q->row_array();
         
    }
    public function getsiblingReport($class_id = null,$section_id = null)
    {
        $this->db->select('students.admission_no,students.firstname,students.middlename,students.lastname,students.father_name,students.mother_name,students.gender,students.mobileno,classes.class,sections.section,student_session.roll_no,student_fees_discounts.status');
        $this->db->join('student_session', 'student_session.id = student_fees_discounts.student_session_id');
        $this->db->join('students', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id' );
        $this->db->join('sections', 'student_session.section_id = sections.id');
        if ($class_id != null) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->db->where('student_session.section_id', $section_id);
        }
        $this->db->where('student_fees_discounts.fees_discount_id', 1);
        $this->db->where('student_fees_discounts.session_id', $this->current_session);
        $this->db->order_by('student_session.class_id,section_id,roll_no');
        
        $query = $this->db->get('student_fees_discounts');
        return $query->result_array();
    }

    public function getsiblingDetail($class_id = null,$section_id = null)
    {
        $this->db->select('s1.id,s1.admission_no,s1.firstname,s1.lastname,s1.father_name,s1.mother_name,s1.gender,s1.mobileno,c1.class,sec1.section,s2.id sid,s2.admission_no sadmission_no,s2.firstname sfirstname,s2.lastname slastname,s2.father_name sfather_name,s2.mother_name smother_name,s2.gender sgender,s2.mobileno smobileno,c2.class sclass,sec2.section sesection')->from('student_sibling');
        $this->db->join('students s1', 'student_sibling.student_id = s1.id');
        $this->db->join('student_session ss1', 'ss1.student_id = s1.id');
        $this->db->join('classes c1', 'ss1.class_id = c1.id' );
        $this->db->join('sections sec1', 'ss1.section_id = sec1.id');

        $this->db->join('students s2', 'student_sibling.sibling_student_id = s2.id','left');
        $this->db->join('student_session ss2', 'ss2.student_id = s2.id','left');
        $this->db->join('classes c2', 'ss2.class_id = c2.id' ,'left');
        $this->db->join('sections sec2', 'ss2.section_id = sec2.id','left');
        if ($class_id != null) {
            $this->db->where('ss1.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->db->where('ss1.section_id', $section_id);
        }
        $this->db->where('ss1.session_id', $this->current_session);
        $this->db->where('ss2.session_id', $this->current_session);
        $this->db->order_by('ss1.class_id,ss1.section_id');
        return $this->db->get()->result_array();
    }

    public function getMedicalMaster($id = null)
    {
        $this->db->select('*')->from('medical_exam_master');
        
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function addMedicalMst($insert_array)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        
        if (!empty($insert_array['id'])) {
            $this->db->where('id', $insert_array['id']);
            $this->db->update('medical_exam_master', $insert_array);
            $message   = UPDATE_RECORD_CONSTANT . " On  medical_exam_master " . $insert_array['id'];
            $action    = "Update";
            $record_id = $insert_id = $insert_array['id'];
            $this->log($message, $record_id, $action);
        } else {

            $this->db->insert('medical_exam_master', $insert_array);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On medical_exam_master " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);

            //return $insert_id;
        }
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $insert_id;
        }
    }

    public function removeMedMst($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('medical_exam_master');
        
        $message = DELETE_RECORD_CONSTANT . " On medical_exam_master id " . $id;
        $action = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return true;
        }
    }

    public function addMedicalResult($insert_array)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('student_session_id', $insert_array['student_session_id']);
        $this->db->where('exammst_id', $insert_array['exammst_id']);
        $this->db->where('session_id', $insert_array['session_id']);
        $q =$this->db->get('medical_result')->num_rows();
        if ($q > 0) {
            $this->db->where('id', $insert_array['id']);
            $this->db->update('medical_result', $insert_array);
            $message   = UPDATE_RECORD_CONSTANT . " On  medical_result " . $insert_array['id'];
            $action    = "Update";
            $record_id = $insert_id = $insert_array['id'];
            $this->log($message, $record_id, $action);
        } else {

            $this->db->insert('medical_result', $insert_array);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On medical_result " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);

            //return $insert_id;
        }
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $insert_id;
        }
    }

    public function getMedicResult($student_session_id,$exammst_id= null)
    {
        $this->db->select('medical_result.*,medical_exam_master.name,medical_exam_master.id as mst_id')->from('medical_result');
        $this->db->join('medical_exam_master', 'medical_exam_master.id = medical_result.exammst_id');
        $this->db->where('medical_result.student_session_id', $student_session_id);
        if (!empty($exammst_id)) {
            $this->db->where('medical_result.exammst_id', $exammst_id);
        }
        $query = $this->db->get();
        return $query->row_array();
        
    }
    public function getMedicResultArray($student_session_id,$exammst_id= null)
    {
        $this->db->select('medical_result.*,medical_exam_master.name,medical_exam_master.id as mst_id')->from('medical_result');
        $this->db->join('medical_exam_master', 'medical_exam_master.id = medical_result.exammst_id');
        $this->db->where('medical_result.student_session_id', $student_session_id);
        if (!empty($exammst_id)) {
            $this->db->where('medical_result.exammst_id', $exammst_id);
        }
        $query = $this->db->get();
        return $query->result_array();
        
    }

    public function addDisable($insert_array)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (!empty($insert_array['id'])) {
            $this->db->where('id', $insert_array['id']);
            $this->db->update('disability', $insert_array);
            $message   = UPDATE_RECORD_CONSTANT . " On  Disability " . $insert_array['id'];
            $action    = "Update";
            $record_id = $insert_id = $insert_array['id'];
            $this->log($message, $record_id, $action);
        } else {

            $this->db->insert('disability', $insert_array);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On Disability " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);

            //return $insert_id;
        }
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $insert_id;
        }
    }

    public function getDisableList($id=null)
    {
        $this->db->select('disability.*,student_session.roll_no,session_id,class_id,section_id,students.admission_no,firstname,lastname,classes.class,sections.section')->from('disability');
        $this->db->join('student_session', 'student_session.id = disability.student_session_id');
        $this->db->join('students', 'disability.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id' );
        $this->db->join('sections', 'student_session.section_id = sections.id');

        if ($id != null) {
            $this->db->where('disability.id', $id);
        }
        $this->db->order_by('disability.id', 'desc');
        
        $query = $this->db->get();

        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
        
    }

    public function removeDisable($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('disability');
        $message = DELETE_RECORD_CONSTANT . " On disability id " . $id;
        $action = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

    public function getcountBygender($class_id,$section_id)
    {
        $this->db->select('*');
        $this->db->join('students', 'students.id = student_session.student_id');
        $this->db->where('student_session.class_id', $class_id);
        $this->db->where('student_session.section_id', $section_id);
        $this->db->where('students.is_active','yes');
        $this->db->where('students.gender','Male');
        $this->db->where('student_session.session_id',  $this->current_session);
        return $this->db->get('student_session')->num_rows();
        
    }

    public function add_docs_status($insert_array)
    {
        
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('student_id', $insert_array[0]['student_id']);
        $this->db->where('checklist_id', $insert_array[0]['checklist_id']);
        $q = $this->db->get('student_checklist')->num_rows();
        
        if ($q >0) {
            $this->db->where('student_id', $insert_array[0]['student_id']);
            $this->db->update_batch('student_checklist', $insert_array,'checklist_id');
            $message   = UPDATE_RECORD_CONSTANT . " On  Student Checklist " . $insert_array[0]['student_id'];
            $action    = "Update";
            $record_id = $insert_id = $insert_array[0]['student_id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert_batch('student_checklist', $insert_array);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On Student Checklist " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);

            //return $insert_id;
        }
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $insert_id;
        }
    }

    public function getDocuments($student_id)
    {
        $this->db->join('checklist_mst', 'checklist_mst.id = student_checklist.checklist_id');
        $this->db->where('student_id', $student_id);
       return $this->db->get('student_checklist')->result_array();
        
    }

    public function getAllStudentBySession($current_session)
    {
        $this->db->select('id');
        $this->db->where('session_id', $current_session);
        $this->db->where('is_active', 'yes');
        $query = $this->db->get('student_session');
        return $query->result_array();
        
    }

    public function getLastAdmNo($class_id,$section_id)
    {
        $this->db->select('sch_section_id');
        $this->db->where('id',$class_id);
       $query = $this->db->get('classes')->row_array();
       
        
       if ($query['sch_section_id'] == 1) {
        $prifix = '511';
       } else {
        $prifix = '512';
       }
       
       $this->db->select('admission_no');
       $this->db->like('admission_no', $prifix);
       
       $this->db->order_by('id', 'desc');
       $query1 = $this->db->get('students',1)->row_array();
       
       return $query1['admission_no'];
       
    }

    // get student wise fees statement by alex 
    // created at 13-01-2024
    public function getStudentByClassSectionIDforstatement($class_id = null, $section_id = null)
    {
        $this->db->select('student_session.is_active as session_is_active,student_session.transport_fees,students.vehroute_id,student_session.roll_no,pass_status,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,students.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname, students.middlename, students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast, school_houses.house_name,   students.dob ,students.current_address, students.previous_school,
            students.guardian_is,students.parent_id,
            students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email, users.username,users.password,students.dis_reason,students.dis_note,students.app_key,students.parent_app_key,students.uid_no,students.dep_student_id,students.aapar_id')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = students.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->where('student_session.class_id', $class_id);
        if ($section_id != null) {
            $this->db->where('student_session.section_id', $section_id);
        }
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('users.role', 'student');
        $this->db->where('students.is_active', 'yes');
        // $this->db->where('student_session.is_active', 'yes');
        $this->db->order_by('student_session.section_id,roll_no', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getStudentByClassSectionIDforstatementwithinactive($class_id = null, $section_id = null,$rep_session_id = null)
    {
        $this->db->select('student_session.is_active as session_is_active,student_session.transport_fees,students.vehroute_id,student_session.roll_no,pass_status,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,students.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , student_session.roll_no,students.admission_date,students.firstname, students.middlename, students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast, school_houses.house_name,   students.dob ,students.current_address, students.previous_school,
            students.guardian_is,students.parent_id,
            students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email, users.username,users.password,students.dis_reason,students.dis_note,students.app_key,students.parent_app_key')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = students.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->where('student_session.class_id', $class_id);
        if ($section_id != null) {
            $this->db->where('student_session.section_id', $section_id);
        }
        if($rep_session_id=="")
        {$this->db->where('student_session.session_id', $this->current_session);}
        else
        {$this->db->where('student_session.session_id', $rep_session_id);}
        $this->db->where('users.role', 'student');
        $this->db->order_by('student_session.section_id,roll_no', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_preprimary_details()
    {       
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        error_reporting(E_ALL);         
        $adm_query = "select classes.sch_section_id, count(*) as nos from student_session inner join classes on student_session.class_id = classes.id where session_id = ".$this->current_session." and student_id in (select student_id from student_session group by student_id having count(*) = 1) group by sch_section_id order by sch_section_id";
        $adm_result = $this->db->query($adm_query)->result_array();
        
        $inactive_query = "select classes.sch_section_id, count(*) as nos from student_session inner join classes on student_session.class_id = classes.id where session_id = ".$this->current_session." and student_session.is_active = 'no' and and student_id in (select student_id from students where status != 4 ) group by sch_section_id order by sch_section_id";
        $inactive_result = $this->db->query($inactive_query);
        if(!empty($inactive_result))
        {
            $inactive_query = $inactive_result->result_query();
        }
        
        $pro_query = "select classes.sch_section_id, count(*) as nos from student_session inner join classes on student_session.class_id = classes.id where session_id = ".$this->current_session." and student_id in (select student_id from student_session group by student_id having count(*) > 1) group by sch_section_id order by sch_section_id";
        $pro_result = $this->db->query($pro_query)->result_array();

        $tc_query = "select classes.sch_section_id, count(*) as nos from student_session inner join classes on student_session.class_id = classes.id where session_id = ".$this->current_session." and student_session.id in (select student_session_id from student_info where status = 'Active'  ) group by sch_section_id order by sch_section_id";
        $tc_result = $this->db->query($tc_query)->result_array();


        $report[] = array(0,0,0,0);
        $report[] = array(0,0,0.0);
        $report[] = array(0,0,0,0);
        $i=0;        
        while($i<=2)
        {
            $report[$i][0]=$pro_result[$i]['nos'];
            $report[$i][1]=$adm_result[$i]['nos'];
            // if(!empty($inactive_result)) {
            // $report[$i][2]=$inactive_result[$i]['nos'];
            // }
            // else
            // {$report[$i][2]=0;}
            $report[$i][2]=$tc_result[$i]['nos'];
            $report[$i][3]=($pro_result[$i]['nos'] + $adm_result[$i]['nos'] )-$tc_result[$i]['nos'];
            ++$i;
        }
        return $report;
    }
}
