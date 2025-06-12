<?php

/**
 * 
 */
class Leavetypes_model extends MY_model {

    public $current_session;
    public $current_date;

    function __construct() {
        $this->current_session = $this->setting_model->getCurrentSession();
        $this->current_date = $this->setting_model->getDateYmd();
    }

    public function addLeaveType($data) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('leave_types', $data);
            $message = UPDATE_RECORD_CONSTANT . " On leave types id " . $data['id'];
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
            $this->db->insert('leave_types', $data);
            $id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On leave types id " . $id;
            $action = "Insert";
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
            return $id;
        }
    }

    public function getLeaveType() {

        $query = $this->db->get('leave_types');
        return $query->result_array();
    }
    public function getLeaveTypeActive() {

        $query = $this->db->from('leave_types') // Specify the table to query from
        ->where('is_active', 'yes') // Apply the where condition before calling get
        ->get(); 
        return $query->result_array();
    }

    public function deleteLeaveType($id) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('leave_types');
        $message = DELETE_RECORD_CONSTANT . " On subjects id " . $id;
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

    public function valid_leave_type($str) {
        $type = $this->input->post('type');
        $id = $this->input->post('leavetypeid');
        if (!isset($id)) {
            $id = 0;
        }
        if ($this->check_data_exists($type, $id)) {
            $this->form_validation->set_message('check_exists', 'Record already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function check_data_exists($name, $id) {

        if ($id != 0) {
            $data = array('id != ' => $id, 'type' => $name);
            $query = $this->db->where($data)->get('leave_types');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {

            $this->db->where('type', $name);
            $query = $this->db->get('leave_types');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    public function getLeavetypesBytype($type)
    {
        $this->db->select('id');
        $this->db->where('type', $type);
        $this->db->where('is_active', 'yes');
        $query =$this->db->get('leave_types')->row_array();
        
        return $query['id'];
    }

    public function addLeaveTypeCategory($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $userdata           = $this->customlib->getUserData();
        if (!empty($data['id'])) {
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['updated_by'] = $userdata['id'];
            $this->db->where('id', $data['id']);
            $this->db->update('leave_type_category_mst', $data);
            $message = UPDATE_RECORD_CONSTANT . " On  leave type category mst   id " . $data['id'];
            $action = "Update";
            $record_id = $return_value = $data['id'];
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = $userdata['id'];
            $this->db->insert('leave_type_category_mst', $data);
            $return_value = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On  leave type category mst   id " . $return_value;
            $action = "Insert";
            $record_id = $return_value;
        }
        $this->log($message, $record_id, $action);

        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {


            return $return_value;
        }
    }

    public function getLeaveTypeCategory($id=null,$leave_type=null)
    {
        $this->db->select('leave_type_category_mst.*,payroll_category.category_name,leave_types.type')->from('leave_type_category_mst');
        $this->db->join('leave_types', 'leave_types.id = leave_type_category_mst.leave_type');
        $this->db->join('payroll_category', 'payroll_category.id = leave_type_category_mst.payroll_category');
        if ($id != null) {
            $this->db->where('leave_type_category_mst.id', $id);
        } else {
            $this->db->order_by('leave_type_category_mst.id');
        }
        if ($leave_type != null) {
            $this->db->where('leave_type_category_mst.leave_type', $leave_type);
        }
        $this->db->where('leave_type_category_mst.status', 'active');
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function remove_leavetype_category($id)
    {
        $userdata           = $this->customlib->getUserData();
        $delArr = array(
            'status' => 'deleted',
            'deleted_at' => date('Y-m-d H:i:s'),
            'deleted_by' => $userdata['id'],
        );
        $this->db->where("id", $id);
        $this->db->update('leave_type_category_mst', $delArr);
       
        $message   = DELETE_RECORD_CONSTANT . " On leave type category mst id " . $id;
        $action    = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
    }

    public function getleaveTypeByType($type)
    {
        $this->db->select('id');
        $this->db->where('type', $type);
        $query = $this->db->get('leave_types');

        return $query->row_array();
        
    }
}

?>