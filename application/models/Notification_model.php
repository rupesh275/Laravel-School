<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Notification_model extends MY_Model {

    public $current_session;

    public function __construct() {
        parent::__construct();
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function get($id = null) {

        $userdata = $this->customlib->getUserData();
        $role_id = $userdata["role_id"];
        $sql = "SELECT * from send_notification left JOIN (SELECT send_notification_id, GROUP_CONCAT(role_id) as roles  FROM notification_roles  group by send_notification_id) as notification_roles on notification_roles.send_notification_id = send_notification.id ";

        if ($id != null) {

            $sql .= "where send_notification.id =" . $id;
        }

        $query = $this->db->query($sql);
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getRole($arr) {

        $query = $this->db->where_in("id", $arr)->get("roles");
        return $query->result_array();
    }

    public function getUnreadStaffNotification($staffid = null, $role_id = null) {


        $sql = "select send_notification.* from send_notification INNER JOIN notification_roles on notification_roles.send_notification_id = send_notification.id left JOIN read_notification on read_notification.staff_id=" . $this->db->escape($staffid) . " and read_notification.notification_id = send_notification.id WHERE send_notification.created_id !=" . $this->db->escape($staffid) . " and send_notification.visible_staff='yes' and read_notification.id IS NULL and notification_roles.role_id=" . $this->db->escape($role_id) . " order by send_notification.id desc";


        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getUnreadStudentNotification() {


        $sql = "select send_notification.* from send_notification  left JOIN read_notification on  read_notification.student_id=".$this->customlib->getStudentSessionUserID()." and read_notification.notification_id = send_notification.id WHERE  send_notification.visible_student='yes' and read_notification.id IS NULL  group by send_notification.id order by send_notification.id desc";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getUnreadParentNotification() {
        $sql = "select send_notification.* from send_notification  left JOIN read_notification on  read_notification.notification_id = send_notification.id WHERE  send_notification.visible_parent='yes' and read_notification.id IS NULL   order by send_notification.id desc";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getNotificationForStudent($studentid = null) {
        $date = date('Y-m-d');
        $query = $this->db->query("SELECT
send_notification.id,send_notification.title,send_notification.publish_date,send_notification.date,send_notification.message,
IF (read_notification.id IS NULL,'unread','read') as notification_id
FROM send_notification
LEFT JOIN read_notification ON send_notification.id = read_notification.notification_id and read_notification.student_id=" . $this->db->escape($studentid) . " where send_notification.visible_student='Yes' order by send_notification.publish_date desc");
        return $query->result_array();
    }

    public function getNotificationForParent($parentid = null) {
        $date = date('Y-m-d');
        $query = $this->db->query("SELECT
send_notification.id,send_notification.title,send_notification.publish_date,send_notification.date,send_notification.message,
IF (read_notification.id IS NULL,'unread','read') as notification_id
FROM send_notification
LEFT JOIN read_notification ON send_notification.id = read_notification.notification_id and read_notification.parent_id=" . $this->db->escape($parentid) . " where send_notification.visible_parent='Yes' order by send_notification.publish_date desc");
        return $query->result_array();
    }

    public function countUnreadNotificationStudent($studentid = null) {
        $date = date('Y-m-d');
        $query = $this->db->query("select * from (SELECT  IF (read_notification.id IS NULL,'unread','read') as notification_id FROM send_notification left JOIN read_notification ON send_notification.id = read_notification.notification_id and read_notification.student_id=" . $this->db->escape($studentid) . " where  send_notification.visible_student='Yes' and send_notification.publish_date <='" . $date . "') final where notification_id ='unread'");

        return $query->num_rows();
    }

    public function countUnreadNotificationParent($parentid = null) {
        $date = date('Y-m-d');
        $query = $this->db->query("select * from (SELECT  IF (read_notification.id IS NULL,'unread','read') as notification_id FROM send_notification LEFT JOIN read_notification ON send_notification.id = read_notification.notification_id and read_notification.parent_id=" . $this->db->escape($parentid) . " where  send_notification.visible_parent='Yes'  and send_notification.publish_date <='" . $date . "') final where notification_id ='unread'");
        return $query->num_rows();
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
        $this->db->delete('send_notification');
        $message = DELETE_RECORD_CONSTANT . " On send notification id " . $id;
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
            $this->db->update('send_notification', $data);
            $message = UPDATE_RECORD_CONSTANT . " On  send notification id " . $data['id'];
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
            $this->db->insert('send_notification', $data);
            $id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On  send notification id " . $id;
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

    public function insertBatch($data, $staff_roles, $delete_array = array()) {

        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================

        if (isset($data['id'])) {
            $insert_id = $data['id'];
            $this->db->where('id', $data['id']);
            $this->db->update('send_notification', $data);
            $message = UPDATE_RECORD_CONSTANT . " On  send notification id " . $data['id'];
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
            $this->db->insert('send_notification', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On send notification id " . $insert_id;
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
        }


        if (!empty($staff_roles)) {
            foreach ($staff_roles as $stf_role_key => $stf_role_value) {
                $staff_roles[$stf_role_key]['send_notification_id'] = $insert_id;
            }

            $this->db->insert_batch('notification_roles', $staff_roles);
        }
        if (!empty($delete_array)) {

            $this->db->where('send_notification_id', $insert_id);
            $this->db->where_in('role_id', $delete_array);
            $this->db->delete('notification_roles');
        }


        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {

            $this->db->trans_rollback();
            return FALSE;
        } else {

            $this->db->trans_commit();
            return TRUE;
        }
    }

    public function updateStatus($notification_id, $studentid) {
        $this->db->where('notification_id', $notification_id);
        $this->db->where('student_id', $studentid);
        $q = $this->db->get('read_notification');
        if ($q->num_rows() > 0) {
            return true;
        } else {
            $data = array(
                'notification_id' => $notification_id,
                'student_id' => $studentid
            );
            $this->db->insert('read_notification', $data);
        }
    }

    public function updateStatusforStaff($notification_id, $staff_id) {
        $this->db->where('notification_id', $notification_id);
        $this->db->where('parent_id', $staff_id);
        $q = $this->db->get('read_notification');
        if ($q->num_rows() > 0) {
            return true;
        } else {
            $data = array(
                'notification_id' => $notification_id,
                'staff_id' => $staff_id
            );
            $this->db->insert('read_notification', $data);
        }
    }

    public function updateStatusforParent($notification_id, $parentid) {
        $this->db->where('notification_id', $notification_id);
        $this->db->where('parent_id', $parentid);
        $q = $this->db->get('read_notification');
        if ($q->num_rows() > 0) {
            return true;
        } else {
            $data = array(
                'notification_id' => $notification_id,
                'parent_id' => $parentid
            );
            $this->db->insert('read_notification', $data);
        }
    }

    public function updateStatusforStudent($notification_id, $studentid) {
        $this->db->where('notification_id', $notification_id);
        $this->db->where('student_id', $studentid);
        $q = $this->db->get('read_notification');
        if ($q->num_rows() > 0) {
            return true;
        } else {
            $data = array(
                'notification_id' => $notification_id,
                'student_id' => $studentid
            );
            $this->db->insert('read_notification', $data);
        }
    }

    function add_exam_schedule($data) {
        $this->db->where('exam_id', $data['exam_id']);
        $this->db->where('teacher_subject_id', $data['teacher_subject_id']);
        $q = $this->db->get('exam_schedules');
        if ($q->num_rows() > 0) {
            $result = $q->row_array();
            $this->db->where('id', $result['id']);
            $this->db->update('exam_schedules', $data);
        } else {
            $this->db->insert('exam_schedules', $data);
        }
    }

    public function getcreatedByName($id) {
        $query = $this->db->select('staff.name,staff.surname')->where("id", $id)->get("staff");
        return $query->row_array();
    }

    public function addnotification($data) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('notification_trn', $data);
            $message = UPDATE_RECORD_CONSTANT . " On   notification id " . $data['id'];
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
            $this->db->insert('notification_trn', $data);
            $id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On   notification id " . $id;
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

    public function getNotificationList($id)
    {
        $this->db->where('role_id', $id);
        $query = $this->db->get('notification_trn');
        return $query->result_array();
    }

    public function getNotification($status = 0)
    {
        $data = $this->customlib->getUserData();
        $user_id = $data['id'];
        $role_id = $data['role_id'];
        $date = date('Y-m-d');

        $this->db->where('user_id',$user_id);
        $this->db->where('notification_date <=',$date);
        $this->db->where('status',$status);
        $this->db->order_by('notification_date','desc');
        $todayList = $this->db->get('notification_trn')->result_array();

        $previuosList = $this->db->query("SELECT * FROM `notification_trn` WHERE `notification_date` <= '$date' AND `status` = $status AND FIND_IN_SET($role_id,role_id)")->result_array();

        $allNotification = array_merge($todayList,$previuosList);
        return $allNotification;        
    }

    public function getStuParNotification($status = 0)
    {
        $data = $this->session->userdata('student');
        $id = $data['id'];
        $student_id = $data['student_id'];
        $role = $data['role'];

        $date = date('Y-m-d');
        if($role == 'student'){
            $this->db->where('student_id',$id);
        } else{
            $this->db->where('parent_id',$id);
        }
        $this->db->where('notification_date <=',$date);
        $this->db->where('status',$status);
        $this->db->order_by('notification_date','desc');
        $todayList = $this->db->get('notification_trn')->result_array();

        // $previuosList = $this->db->query("SELECT * FROM `notification_trn` WHERE `notification_date` <= '$date' AND `status` = $status AND FIND_IN_SET($role_id,role_id)")->result_array();

        // $allNotification = array_merge($todayList,$previuosList);
        return $todayList;        
    }

    public function getAllNotification()
    {
        $data = $this->customlib->getUserData();
        $user_id = $data['id'];
        $role_id = $data['role_id'];

        $previuosList = $this->db->query("SELECT * FROM `notification_trn` WHERE `user_id` = '$user_id' AND FIND_IN_SET($role_id,role_id) ORDER BY `notification_date` DESC")->result_array();
        return $previuosList;
    }

    public function update_notification($id)
    {
        $this->db->select('status');
        $this->db->where('id',$id);
        $this->db->where('status',0);
        $data = $this->db->get('notification_trn')->row_array();
        if(!empty($data)){
            $this->db->where('id',$id)
            ->set('status',1)
            ->set('view_time',date('Y-m-d H:i:s'))
            ->update('notification_trn');
        }
    }

    public function deleteNotification($id)
    {
        $this->db->where('transcation_id',$id);
        $this->db->delete('notification_trn');
    }
}
