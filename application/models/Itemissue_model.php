<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Itemissue_model extends MY_Model {

    public $current_session;

    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function get($id = null) {
        $sql = "SELECT item_issue.*,item.name as `item_name`,item.item_category_id,item_category.item_category ,staff.employee_id,staff.name as staff_name,staff.surname,roles.name FROM `item_issue` INNER JOIN item on item.id=item_issue.item_id INNER JOIN item_category on item_category.id=item.item_category_id INNER JOIN staff on staff.id=item_issue.issue_to INNER JOIN staff_roles on staff_roles.staff_id =staff.id INNER JOIN roles on roles.id= staff_roles.role_id";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /**
     * This function is used to get issue item list
     * @param $id
     */
    public function getitemlist() {


         $sql="select item_issue.*,item.name as `item_name`,item.item_category_id,item_category.item_category ,staff.employee_id,staff.name as staff_name,staff.surname,roles.name from item_issue inner join item on item.id=item_issue.item_id inner join item_category on item_category.id=item.item_category_id inner join staff on staff.id=item_issue.issue_to inner join staff_roles on staff_roles.staff_id =staff.id inner join roles on roles.id= staff_roles.role_id ";
         $this->datatables->query($sql)
          ->orderable('item.name,item_category,issue_date,staff.name,issue_by,quantity,null')
          ->searchable('item.name,item_category,issue_date,staff.name,issue_by,item_issue.quantity,null')
         ->query_where_enable(TRUE);
         return $this->datatables->generate('json');   
    }

    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function remove($id) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('item_issue');
        $message = DELETE_RECORD_CONSTANT . " On item issue id " . $id;
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
    public function removeFD($id) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('fd_trn');
        $message = DELETE_RECORD_CONSTANT . " On FD Trn id " . $id;
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

    /**
     * This function will take the post data passed from the controller
     * If id is present, then it will do an update
     * else an insert. One function doing both add and edit.
     * @param $data
     */
    public function add($data) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('item_issue', $data);
            $message = UPDATE_RECORD_CONSTANT . " On  item issue id " . $data['id'];
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
            $this->db->insert('item_issue', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On item issue id " . $insert_id;
            $action = "Insert";
            $record_id = $insert_id;
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
            return $insert_id;
        }
    }

    public function get_IssueInventoryReport($start_date, $end_date) {

        $condition = " and date_format(item_issue.issue_date,'%Y-%m-%d') between '" . $start_date . "' and '" . $end_date . "'";

        $sql = "SELECT item_issue.*,item.name as `item_name`,item.item_category_id,item_category.item_category ,staff.employee_id,staff.name as staff_name,staff.surname,roles.name FROM `item_issue` INNER JOIN item on item.id=item_issue.item_id INNER JOIN item_category on item_category.id=item.item_category_id INNER JOIN staff on staff.id=item_issue.issue_to INNER JOIN staff_roles on staff_roles.staff_id =staff.id INNER JOIN roles on roles.id= staff_roles.role_id where 1 " . $condition;

       
         $this->datatables->query($sql)
          ->orderable('item.name,item_category,issue_date,staff_name,issue_by,quantity')
          ->searchable('item.name,item_category,issue_date,staff.name,surname,issue_by,item_issue.quantity')
         ->query_where_enable(TRUE);
       
         return $this->datatables->generate('json');   

    }

    public function getFDlist($id = null)
    {
        $this->db->select()->from('fd_trn');
        if ($id != null) {
            $this->db->where('fd_trn.id', $id);
        } else {
            $this->db->order_by('fd_trn.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function addFD($data) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (!empty($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('fd_trn', $data);
            $message = UPDATE_RECORD_CONSTANT . " On  FD trn id " . $data['id'];
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
            $this->db->insert('fd_trn', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On FD trn id " . $insert_id;
            $action = "Insert";
            $record_id = $insert_id;
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
            return $insert_id;
        }
    }

    public function getFDresult($id = null) {
        $this->db->select()->from('fd_trn');
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


}
