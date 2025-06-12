<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Leaverequest_model extends MY_model {

    public function staff_leave_request($id = null) {

        if ($id != null) {
            $this->db->where("staff_leave_request.staff_id", $id);
        }

        $query = $this->db->select('staff.name,staff.surname,staff.employee_id,staff_leave_request.*,leave_types.type')->join("staff", "staff.id = staff_leave_request.staff_id")->join("leave_types", "leave_types.id = staff_leave_request.leave_type_id",'left')->where("staff.is_active", "1")->order_by("staff_leave_request.id", "desc")->get("staff_leave_request");

        return $query->result_array();
    }

    public function user_leave_request($id = null) {


        $query = $this->db->select('staff.name,staff.surname,staff.employee_id,staff_leave_request.*,leave_types.type')->join("staff", "staff.id = staff_leave_request.staff_id")->join("leave_types", "leave_types.id = staff_leave_request.leave_type_id")->where("staff.is_active", "1")->where("staff.id", $id)->order_by("staff_leave_request.id", "desc")->get("staff_leave_request");

        return $query->result_array();
    }


    public function allotedLeaveType($id) {

        $query = $this->db->select('staff_leave_details.*,leave_types.type,leave_types.id as typeid')->where(array('staff_id' => $id))->join("leave_types", "staff_leave_details.leave_type_id = leave_types.id")->get("staff_leave_details");

        return $query->result_array();
    }

    public function myallotedLeaveType($id, $leave_type_id) {

        $query = $this->db->select('staff_leave_details.*,leave_types.type,leave_types.id as typeid')->where(array('staff_id' => $id, 'leave_types.id' => $leave_type_id))->join("leave_types", "staff_leave_details.leave_type_id = leave_types.id")->get("staff_leave_details");

        return $query->row_array();
    }

    public function countLeavesData($staff_id, $leave_type_id) {

        $query1 = $this->db->select('sum(leave_days) as approve_leave')->where(array('staff_id' => $staff_id, 'status!=' => 'disapprove', 'leave_type_id' => $leave_type_id))->get("staff_leave_request");
        return $query1->row_array();
    }

    public function changeLeaveStatus($data, $staff_id) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where("id", $staff_id)->update("staff_leave_request", $data);
        $message = UPDATE_RECORD_CONSTANT . " On staff leave request id " . $staff_id;
        $action = "Update";
        $record_id = $staff_id;
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

    public function getLeaveSummary() {

        $query = $this->db->select('*')->get("staff");

        return $query->result_array();
    }

    public function leave_remove($id) {

        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('staff_leave_request');

        $this->db->where('leave_request_id',$id);
        $this->db->delete('staff_leave_request_sub');

        $this->db->where('leave_request_id', $id);
        $this->db->delete('approved_staff_leave_trn');
        $message = DELETE_RECORD_CONSTANT . " On staff leave request id " . $id;
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

    function addLeaveRequest($data) {
        // echo "<pre>";
        // print_r($data);
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {

            $data['updated_at'] = date("Y-m-d H:i:s");
            $data['updated_by'] = $this->customlib->getStaffID();
            $this->db->where("id", $data["id"]);
            $this->db->update("staff_leave_request", $data);
            $message = UPDATE_RECORD_CONSTANT . " On staff leave request id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];

        } else {

            $data['created_at'] = date("Y-m-d H:i:s");
            $data['created_by'] = $this->customlib->getStaffID();
            $this->db->insert("staff_leave_request", $data);
            // echo $this->db->last_query();
            $id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On staff leave request id " . $id;
            $action = "Insert";
            $record_id = $id;
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
            return $record_id;
        }
    }

    public function user_leave_request_all() {


        $query = $this->db->select('staff.name,staff.surname,staff.employee_id,staff_leave_request.*,leave_types.type')->join("staff", "staff.id = staff_leave_request.staff_id")->join("leave_types", "leave_types.id = staff_leave_request.leave_type_id")->where("staff.is_active", "1")->order_by("staff_leave_request.id", "desc")->get("staff_leave_request");

        return $query->result_array();
    }
    public function staff_leave_request_all($month = null, $year = null) {


        $this->db->select('staff.name, staff.surname, staff.employee_id, staff_leave_request.*,leave_types.type');
        $this->db->join('staff', 'staff.id = staff_leave_request.staff_id');
        $this->db->join('leave_types', 'leave_types.id = staff_leave_request.leave_type_id', 'left');
        $this->db->where('staff.is_active', '1');
        if ($month != null) {
            $this->db->where('MONTH(staff_leave_request.leave_from)', date("m", strtotime($month)));
        }
        if ($year != null) {
            $this->db->where('YEAR(staff_leave_request.leave_from)', $year);
        }
        $this->db->order_by('staff_leave_request.id', 'desc');
        $query = $this->db->get('staff_leave_request');

        return $query->result_array();
    }

    public function addLeaveRequestSub($array)
    {

        $this->db->insert('staff_leave_request_sub', $array);
    }

    public function getLeaveTypeByLeaveRequestId($leave_request_id)
    {
        $this->db->select('leave_types.*');
        $this->db->from('staff_leave_request_sub');
        $this->db->join('leave_types', 'leave_types.id = staff_leave_request_sub.leave_type_id');
        $this->db->where('staff_leave_request_sub.leave_request_id', $leave_request_id);
        return $this->db->get()->result_array();
    }

    public function getLeaveRequestSubByLeaveRequestId($leave_request_id)
    {
        $this->db->select('leave_types.type,staff_leave_request_sub.*');
        $this->db->from('staff_leave_request_sub');
        $this->db->join('leave_types', 'leave_types.id = staff_leave_request_sub.leave_type_id');
        $this->db->where('staff_leave_request_sub.leave_request_id', $leave_request_id);
        return $this->db->get()->result_array();
    }

}

?>