<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Classsection_model extends MY_Model
{

    public $current_session;

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }
    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function get($classid = null)
    {
        $this->db->select('class_sections.id,class_sections.section_id,sections.section');
        $this->db->from('class_sections');
        $this->db->join('sections', 'sections.id = class_sections.section_id');
        if($class_id!='')
        {$this->db->where('class_sections.class_id', $classid);}    
        $this->db->order_by('class_sections.id');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_detailed($classid = null)
    {
        $this->db->select('classes.class,class_sections.class_id,class_sections.id,class_sections.section_id,sections.section');
        $this->db->from('class_sections');
        $this->db->join('sections', 'sections.id = class_sections.section_id');
        $this->db->join('classes', 'classes.id = class_sections.class_id');
        if($classid!='')
        {$this->db->where('class_sections.class_id', $classid);}    
        $this->db->order_by('class_sections.class_id');
        $this->db->order_by('class_sections.section_id');
        //$this->db->limit(1);
        $query = $this->db->get();
        return $query->result_array();
    }  
    public function get_detailed_test($classid = null)
    {
        $this->db->select('classes.class,class_sections.class_id,class_sections.id,class_sections.section_id,sections.section');
        $this->db->from('class_sections');
        $this->db->join('sections', 'sections.id = class_sections.section_id');
        $this->db->join('classes', 'classes.id = class_sections.class_id');
        $this->db->where('class_sections.class_id', 3);
        $this->db->where('class_sections.section_id', 1);
            
        $this->db->order_by('class_sections.class_id ');
        //$this->db->limit(1);
        $query = $this->db->get();
        return $query->result_array();
    }       
    public function get_section($id)
    {
        $this->db->select()->from('sections');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            $classlist = $query->row_array();
        } else {
            $classlist = $query->result_array();
        }

        return $classlist;

    }


    public function update($data)
    {

        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('classes', $data);
        }
    }

    public function add($data, $sections)
    {

        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('classes', $data);
            $class_id = $data['id'];
            $message = UPDATE_RECORD_CONSTANT . " On classes id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
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
        } else {
            $this->db->insert('classes', $data);
            $class_id = $this->db->insert_id();

            $message = INSERT_RECORD_CONSTANT . " On subject groups id " . $class_id;
            $action = "Insert";
            $record_id = $class_id;
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

        $sections_array = array();
        foreach ($sections as $vec_key => $vec_value) {

            $vehicle_array = array(
                'class_id' => $class_id,
                'section_id' => $vec_value,
            );

            $sections_array[] = $vehicle_array;
        }
        $this->db->insert_batch('class_sections', $sections_array);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    public function getDetailbyClassSection($class_id, $section_id)
    {
        $this->db->select('class_sections.*,classes.class,sections.section')->from('class_sections');
        $this->db->where('class_id', $class_id);
        $this->db->where('section_id', $section_id);
        $this->db->join('classes', 'classes.id = class_sections.class_id');
        $this->db->join('sections', 'sections.id = class_sections.section_id');
        $this->db->where('class_sections.class_id', $class_id);
        $this->db->where('class_sections.section_id', $section_id);
        $query = $this->db->get();
        return $query->row_array();
    }
    public function getDetailbyClassSectionrResult($class_id, $section_id)
    {
        $this->db->select('class_sections.*,classes.class,sections.section')->from('class_sections');
        $this->db->where('class_id', $class_id);
        $this->db->where('section_id', $section_id);
        $this->db->join('classes', 'classes.id = class_sections.class_id');
        $this->db->join('sections', 'sections.id = class_sections.section_id');
        $this->db->where('class_sections.class_id', $class_id);
        $this->db->where('class_sections.section_id', $section_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getDetailbyClassSection_id($class_id)
    {
        $this->db->select('class_sections.*,classes.class,sections.section')->from('class_sections');
        $this->db->where('class_id', $class_id);
        $this->db->join('classes', 'classes.id = class_sections.class_id');
        $this->db->join('sections', 'sections.id = class_sections.section_id');
        $this->db->where('class_sections.class_id', $class_id);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getAllClass()
    {
        $this->db->select('class_sections.*,classes.class,sections.section')->from('class_sections');
        $this->db->join('classes', 'classes.id = class_sections.class_id');
        $this->db->join('sections', 'sections.id = class_sections.section_id');
        $this->db->order_by('class_sections.class_id,class_sections.section_id');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function check_data_exists($data)
    {
        $this->db->where('class_id', $data['class_id']);
        $this->db->where('section_id', $data['section_id']);
        $query = $this->db->get('class_sections');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getByID($id = null)
    {
        $this->db->select('classes.*')->from('classes');

        if ($id != null) {
            $this->db->where('classes.id', $id);
        } else {
            $this->db->order_by('classes.id', 'DESC');
        }

        $query = $this->db->get();
        if ($id != null) {
            $vehicle_routes = $query->result_array();

            $array = array();
            if (!empty($vehicle_routes)) {
                foreach ($vehicle_routes as $vehicle_key => $vehicle_value) {
                    $vec_route = new stdClass();
                    $vec_route->id = $vehicle_value['id'];

                    $vec_route->route_id = $vehicle_value['class'];
                    $vec_route->code = $vehicle_value['code'];
                    $vec_route->vehicles = $this->getVechileByRoute($vehicle_value['id']);
                    $vec_route->sch_section_id = $vehicle_value['sch_section_id'];
                    $array[] = $vec_route;
                }
            }
            return $array;
        } else {
            $vehicle_routes = $query->result_array();
            $array = array();
            if (!empty($vehicle_routes)) {
                foreach ($vehicle_routes as $vehicle_key => $vehicle_value) {

                    $vec_route = new stdClass();
                    $vec_route->id = $vehicle_value['id'];
                    $vec_route->class = $vehicle_value['class'];
                    $vec_route->code = $vehicle_value['code'];
                    $vec_route->sch_section_id = $vehicle_value['sch_section_id'];

                    $vec_route->vehicles = $this->getVechileByRoute($vehicle_value['id']);
                    $array[] = $vec_route;
                }
            }
            return $array;
        }
    }

    public function getbyid_sch($id)
    {
        $this->db->select('classes.*,sch_section.sch_section');
        $this->db->join('sch_section', 'sch_section.id = classes.sch_section_id');
        $this->db->where('classes.id', $id);
        return $this->db->get('classes')->row_array();
       
    }

    public function getVechileByRoute($route_id)
    {
        $this->db->select('class_sections.id as class_section_id,class_sections.class_id,class_sections.section_id,sections.*')->from('class_sections');
        $this->db->join('sections', 'sections.id = class_sections.section_id');

        $this->db->where('class_sections.class_id', $route_id);
        $this->db->order_by('class_sections.id', 'asc');
        $query = $this->db->get();
        return $vehicle_routes = $query->result();
    }

    public function remove($class_id, $array)
    {

        $this->db->where('class_id', $class_id);
        $this->db->where_in('section_id', $array);
        $this->db->delete('class_sections');
    }

    public function allClassSections()
    {

        $classes = $this->class_model->get();
        if (!empty($classes)) {
            foreach ($classes as $class_key => $class_value) {
                $classes[$class_key]['sections'] = $this->get($class_value['id']);
            }
        }
        return $classes;
    }




    public function getClassSectionStudentCountForReport()
    {
        $query = "SELECT class_sections.*,classes.class,sections.section,(SELECT COUNT(*) FROM student_session INNER JOIN students on students.id=student_session.student_id WHERE student_session.class_id=classes.id and student_session.section_id=sections.id and students.is_active='yes' and student_session.is_active='yes' and student_session.session_id=" . $this->current_session . " )  as student_count FROM `class_sections` INNER JOIN classes on classes.id=class_sections.class_id INNER JOIN sections on sections.id=class_sections.section_id ORDER by classes.id asc, sections.section asc";

        $query = $this->db->query($query);
        return $query->result();
    }
    public function getClassSectionStudentCount()
    {
        $query = "SELECT class_sections.*,classes.class,sections.section,(SELECT COUNT(*) FROM student_session INNER JOIN students on students.id=student_session.student_id WHERE student_session.class_id=classes.id and student_session.section_id=sections.id and students.is_active='yes' and student_session.is_active='yes' and student_session.session_id=" . $this->current_session . " )  as student_count FROM `class_sections` INNER JOIN classes on classes.id=class_sections.class_id INNER JOIN sections on sections.id=class_sections.section_id ORDER by classes.class ASC, sections.section asc";

        $query = $this->db->query($query);
        return $query->result();
    }

    public function getClassSectionStudentCountall()
    {
        $query = "SELECT class_sections.*,classes.class,sections.section,(SELECT COUNT(*) FROM student_session INNER JOIN students on students.id=student_session.student_id WHERE student_session.class_id=classes.id and student_session.section_id=sections.id and students.is_active='yes' and student_session.session_id=" . $this->current_session . " )  as student_count FROM `class_sections` INNER JOIN classes on classes.id=class_sections.class_id INNER JOIN sections on sections.id=class_sections.section_id ORDER by classes.id ASC, sections.id asc";

        $query = $this->db->query($query);
        return $query->result();
    }

    public function getClassSectionStudentall($sch_section_id = "")
    {
        if ($sch_section_id == "") {
            $query = "SELECT class_sections.*,classes.class,sections.section,(SELECT COUNT(*) FROM student_session INNER JOIN students on students.id=student_session.student_id WHERE student_session.class_id=classes.id and student_session.section_id=sections.id and students.is_active='yes' and student_session.session_id=" . $this->current_session . " )  as student_count FROM `class_sections` INNER JOIN classes on classes.id=class_sections.class_id INNER JOIN sections on sections.id=class_sections.section_id ORDER by classes.id ASC, sections.id asc";
        } else {
            $query = "SELECT class_sections.*,classes.class,sections.section,(SELECT COUNT(*) FROM student_session INNER JOIN students on students.id=student_session.student_id WHERE student_session.class_id=classes.id and student_session.section_id=sections.id and students.is_active='yes' and student_session.session_id=" . $this->current_session . " )  as student_count FROM `class_sections` INNER JOIN classes on classes.id=class_sections.class_id INNER JOIN sections on sections.id=class_sections.section_id WHERE classes.sch_section_id=" . $sch_section_id . " ORDER by classes.id ASC, sections.id asc";
        }
        $query = $this->db->query($query);
        return $query->result();
    }

    public function getClassSectionBySection($sch_section_id)
    {
        $this->db->select('classes.id as class_id,classes.class,sections.id as section_id,sections.section,class_sections.id as class_section_id');
        $this->db->from('class_sections');
        $this->db->join('classes', 'classes.id = class_sections.class_id');
        $this->db->join('sections', 'sections.id = class_sections.section_id');
        if ($sch_section_id != "" && $sch_section_id != "All") {
            $this->db->where('classes.sch_section_id', $sch_section_id);
        }
        $this->db->order_by('classes.id, sections.section', 'asc');
        
        $query = $this->db->get();
        return $query->result();
        
    }

    public function getclass_sectionbyclasssection($class_section_id)
    {
        $this->db->select('classes.id as class_id,classes.class,sections.id as section_id,sections.section');
        $this->db->from('class_sections');
        $this->db->join('classes', 'classes.id = class_sections.class_id');
        $this->db->join('sections', 'sections.id = class_sections.section_id');
        $this->db->where_in('class_sections.id', $class_section_id);
        $query = $this->db->get();
        return $query->result();
    }
}
