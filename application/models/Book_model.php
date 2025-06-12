<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Book_model extends MY_Model
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
    public function get($id = null)
    {
        $this->db->select()->from('books');
        if ($id != null) {
            $this->db->where('books.id', $id);
        } else {
            $this->db->order_by('books.id');
        }
        $this->db->where('status', 'active');
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getbooklist()
    {

        $this->datatables
            ->select('books.*,IFNULL(total_issue, "0") as `total_issue`,publisher.publisher as publish,author.author')
            ->searchable('book_title,description,book_no,isbn_no,publisher.publisher as publish,author.author,subject,rack_no,qty," ",perunitcost,postdate')
            ->orderable('book_title,description,book_no,isbn_no,publisher.publisher as publish,author.author,subject,rack_no,qty," ",perunitcost,postdate')
            ->join(" (SELECT COUNT(*) as `total_issue`, book_id from book_issues  where is_returned= 0  GROUP by book_id) as `book_count`", "books.id=book_count.book_id", "left")
            ->join('author', 'author.id = books.author', 'left')
            ->join('publisher', 'publisher.id = books.publish', 'left')
            ->from('books');
            // $this->db->last_query();die;
            
        return $this->datatables->generate('json');
    }

    public function getBookwithQty()
    {

        $sql = "SELECT books.*,IFNULL(total_issue, '0') as `total_issue` FROM books LEFT JOIN (SELECT COUNT(*) as `total_issue`, book_id from book_issues  where is_returned= 0 GROUP by book_id) as `book_count` on books.id=book_count.book_id";

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function remove($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('books');
        $this->db->where('book_id', $id);
        $this->db->delete('book_issues');
        $message = DELETE_RECORD_CONSTANT . " On books id " . $id;
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
    public function addbooks($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('books', $data);
            $message = UPDATE_RECORD_CONSTANT . " On books id " . $data['id'];
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
            $this->db->insert('books', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On books id " . $insert_id;
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

    public function listbook()
    {
        $this->db->select()->from('books');
        $this->db->order_by("id", "desc");
        $listbook = $this->db->get();
        return $listbook->result_array();
    }

    public function check_Exits_group($data)
    {
        $this->db->select('*');
        $this->db->from('feemasters');
        $this->db->where('session_id', $this->current_session);
        $this->db->where('feetype_id', $data['feetype_id']);
        $this->db->where('class_id', $data['class_id']);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return false;
        } else {
            return true;
        }
    }

    public function getTypeByFeecategory($type, $class_id)
    {
        $this->db->select('feemasters.id,feemasters.session_id,feemasters.amount,feemasters.description,classes.class,feetype.type')->from('feemasters');
        $this->db->join('classes', 'feemasters.class_id = classes.id');
        $this->db->join('feetype', 'feemasters.feetype_id = feetype.id');
        $this->db->where('feemasters.class_id', $class_id);
        $this->db->where('feemasters.feetype_id', $type);
        $this->db->where('feemasters.session_id', $this->current_session);
        $this->db->order_by('feemasters.id');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function bookinventory($start_date, $end_date)
    {
        $condition = " and date_format(`books`.`postdate`,'%Y-%m-%d') between '" . $start_date . "' and '" . $end_date . "'";
        $sql = "SELECT books.*,author.author as author_name,subject_lib.subject_lib as subject_name,publisher.publisher as publisher_name,IFNULL(total_issue, '0') as `total_issue` FROM books LEFT JOIN (SELECT COUNT(*) as `total_issue`, book_id from book_issues  where is_returned= 0  GROUP by book_id) as `book_count` on books.id=book_count.book_id LEFT JOIN author on author.id = books.author LEFT JOIN publisher on publisher.id = books.publish LEFT JOIN subject_lib on subject_lib.id = books.subject where 0=0 " . $condition . " ";

        $this->datatables->query($sql)
            ->orderable('book_title,book_no,isbn_no,publish,author,subject,rack_no,qty,null,null,perunitcost,postdate')
            ->searchable('book_title,book_no,isbn_no,publish,author,subject,rack_no,qty,null,null,perunitcost,postdate')

            ->query_where_enable(TRUE);
        
        return $this->datatables->generate('json');
    }

    public function bookoverview($start_date, $end_date)
    {
        $condition = " and date_format(`books`.`postdate`,'%Y-%m-%d') between '" . $start_date . "' and '" . $end_date . "'";
        $sql = "SELECT sum(books.qty) as qty,sum(IFNULL(total_issue, '0')) as `total_issue` FROM books LEFT JOIN (SELECT COUNT(*) as `total_issue`, book_id from book_issues  where is_returned= 0  GROUP by book_id) as `book_count` on books.id=book_count.book_id where 0=0 " . $condition . " ";

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function getpublisher($id = null)
    {
        $this->db->select()->from('publisher');
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

    public function addPublisher($data)
    {

        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (!empty($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('publisher', $data);
            $message = UPDATE_RECORD_CONSTANT . " On  publisher   id " . $data['id'];
            $action = "Update";
            $record_id = $return_value = $data['id'];
        } else {
            $this->db->insert('publisher', $data);
            $return_value = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On  publisher   id " . $return_value;
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

    public function remove_publisher($id)
    {

        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('publisher');

        //$return_value = $this->db->insert_id();
        $message = DELETE_RECORD_CONSTANT . " On  publisher   id " . $id;
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


            return $record_id;
        }
    }

    public function getauthor($id = null)
    {
        $this->db->select()->from('author');
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

    public function addauthor($data)
    {

        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (!empty($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('author', $data);
            $message = UPDATE_RECORD_CONSTANT . " On  author   id " . $data['id'];
            $action = "Update";
            $record_id = $return_value = $data['id'];
        } else {
            $this->db->insert('author', $data);
            $return_value = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On  author   id " . $return_value;
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

    public function remove_author($id)
    {

        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('author');

        //$return_value = $this->db->insert_id();
        $message = DELETE_RECORD_CONSTANT . " On  author   id " . $id;
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


            return $record_id;
        }
    }
    public function getsubject($id = null)
    {
        $this->db->select()->from('subject_lib');
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

    public function addsubject($data)
    {

        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (!empty($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('subject_lib', $data);
            $message = UPDATE_RECORD_CONSTANT . " On  subject_lib   id " . $data['id'];
            $action = "Update";
            $record_id = $return_value = $data['id'];
        } else {
            $this->db->insert('subject_lib', $data);
            $return_value = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On  subject_lib   id " . $return_value;
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

    public function remove_subject($id)
    {

        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('subject_lib');

        //$return_value = $this->db->insert_id();
        $message = DELETE_RECORD_CONSTANT . " On  subject_lib   id " . $id;
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


            return $record_id;
        }
    }

    public function getcategory($id = null)
    {
        $this->db->select()->from('book_category_mst');
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

    public function addcategory($data)
    {

        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (!empty($data['id'])) {
            $data['updated_at'] = date('Y-m-d h:i:s');
            $data['updated_by'] = $this->session->userdata('id');
            $this->db->where('id', $data['id']);
            $this->db->update('book_category_mst', $data);
            $message = UPDATE_RECORD_CONSTANT . " On  book_category_mst   id " . $data['id'];
            $action = "Update";
            $record_id = $return_value = $data['id'];
        } else {
            $data['created_at'] = date('Y-m-d h:i:s');
            $data['created_by'] = $this->session->userdata('id');
            $this->db->insert('book_category_mst', $data);
            $return_value = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On  book_category_mst   id " . $return_value;
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

    public function remove_category($id)
    {

        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('book_category_mst');

        //$return_value = $this->db->insert_id();
        $message = DELETE_RECORD_CONSTANT . " On  book_category_mst   id " . $id;
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


            return $record_id;
        }
    }

    public function getitem_type($id = null)
    {
        $this->db->select()->from('book_item_type');
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

    public function additem_type($data)
    {

        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (!empty($data['id'])) {
            $data['updated_at'] = date('Y-m-d h:i:s');
            $data['updated_by'] = $this->session->userdata('admin')['id'];
            $this->db->where('id', $data['id']);
            $this->db->update('book_item_type', $data);
            $message = UPDATE_RECORD_CONSTANT . " On  book_item_type   id " . $data['id'];
            $action = "Update";
            $record_id = $return_value = $data['id'];
        } else {
            $data['created_at'] = date('Y-m-d h:i:s');
            $data['created_by'] = $this->session->userdata('admin')['id'];
            $this->db->insert('book_item_type', $data);
            $return_value = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On  book_item_type   id " . $return_value;
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

    public function remove_item_type($id)
    {

        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('book_item_type');

        //$return_value = $this->db->insert_id();
        $message = DELETE_RECORD_CONSTANT . " On  book_item_type   id " . $id;
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


            return $record_id;
        }
    }

    public function save_publish_name($name)
    {
        if ($name != '') {
            $pubRow = $this->db->select('id')->where('publisher', $name)->get('publisher')->row_array();
            if (!empty($pubRow)) {
                return $pubRow['id'];
            } else {
                $array = [
                    'publisher' => $name
                ];
                $this->db->insert('publisher', $array);
                $id = $this->db->insert_id();
                return $id;
            }
        } else {
            return '';
        }
    }
    public function save_subject_name($name)
    {
        if ($name != '') {
            $subRow = $this->db->select('id')->where('name', $name)->get('subjects')->row_array();
            if (!empty($subRow)) {
                return $subRow['id'];
            } else {
                $array = [
                    'name' => $name
                ];
                $this->db->insert('subjects', $array);
                $id = $this->db->insert_id();
                return $id;
            }
        } else {
            return '';
        }
    }
    public function save_author_name($name)
    {
        if ($name != '') {
            $autRow = $this->db->select('id')->where('author', $name)->get('author')->row_array();
            if (!empty($autRow)) {
                return $autRow['id'];
            } else {
                $array = [
                    'author' => $name
                ];
                $this->db->insert('author', $array);
                $id = $this->db->insert_id();
                return $id;
            }
        } else {
            return '';
        }
    }
    public function save_item_name($name)
    {
        if ($name != '') {
            $itemRow = $this->db->select('id')->where('item_type_name', $name)->get('book_item_type')->row_array();
            if (!empty($itemRow)) {
                return $itemRow['id'];
            } else {
                $array = [
                    'item_type_name' => $name
                ];
                $this->db->insert('book_item_type', $array);
                $id = $this->db->insert_id();
                return $id;
            }
        } else {
            return '';
        }
    }
    public function save_category_name($name)
    {
        if ($name != '') {
            $itemRow = $this->db->select('id')->where('category', $name)->get('book_category_mst')->row_array();
            if (!empty($itemRow)) {
                return $itemRow['id'];
            } else {
                $array = [
                    'category' => $name
                ];
                $this->db->insert('book_category_mst', $array);
                $id = $this->db->insert_id();
                return $id;
            }
        } else {
            return '';
        }
    }
    public function insert_all_book_data($array)
    {
        $this->db->insert_batch('books', $array);
    }

    private function _get_datatables_query() {
        $this->db->select('books.*, IFNULL(book_count.total_issue, 0) as total_issue,
        publisher.publisher as publisher_name,author.author as author_name,subject_lib');
        $this->db->from('books');
        $this->db->join('publisher','publisher.id = books.publish','left');
        $this->db->join('author','author.id = books.author','left');
        $this->db->join('subject_lib','subject_lib.id = books.subject','left');
        $this->db->join('(SELECT COUNT(*) as total_issue, book_id FROM book_issues WHERE is_returned = 0 GROUP BY book_id) as book_count', 'books.id = book_count.book_id', 'left');
        $column_search = array('book_title', 'description', 'book_no', 'isbn_no', 'publisher.publisher', 'author.author', 'subject', 'rack_no', 'qty'); // Specify columns for searching
        $column_order = array(null, 'book_title', 'description', 'book_no', 'isbn_no', 'publisher.publisher', 'author.author', 'subject', 'rack_no', 'qty', 'postdate'); // Specify your table columns for ordering
        $i = 0;
        foreach ($column_search as $item) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start(); // open bracket
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($column_search) - 1 == $i)
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables() {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $this->_get_datatables_query();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        // echo $this->db->last_query();die;
        
        return $query->result();
    }

    function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all() {
        $this->db->from('books');
        return $this->db->count_all_results();
    }

    public function check_availability_issue($book_id,$issue_date)
    {
        $this->db->where('book_id', $book_id);
        $this->db->where('is_returned', 0);
        $this->db->where('status', 'issue');
        $query = $this->db->get('book_issues')->row();
        
        return $query;
    }
    public function check_availability_booking($book_id,$issue_date)
    {
        $this->db->where('book_id', $book_id);
        $this->db->where('booking_date', date('Y-m-d',strtotime($issue_date)));
        $query2 = $this->db->get('lib_pre_booking')->row();
        
        return $query2;
    }

    /**
     * Retrieves the booking details of staff members.
     *
     * This function retrieves the booking details of staff members from the database. It performs a join operation
     * on multiple tables to fetch the required information. The function selects the booking details, book title,
     * member type, staff name, middle name, and surname. The join operations are performed on the 'books',
     * 'libarary_members', and 'staff' tables. The function applies the conditions to filter the results based on the
     * member type and status. Finally, it executes the query and returns the result as an array.
     *
     * @return array An array containing the booking details of staff members.
     */
    public function getBookingStaff()
    {
        $this->db->select('lib_pre_booking.*,books.book_title,libarary_members.member_type,staff.name as staff_name,staff.middle_name,staff.surname');
        $this->db->join('books', 'books.id = lib_pre_booking.book_id', 'left');
        $this->db->join('libarary_members', 'libarary_members.id = lib_pre_booking.member_id', 'left');
        $this->db->join('staff', 'staff.id = libarary_members.member_id', 'left');
        $this->db->where('libarary_members.member_type', 'teacher');
        $this->db->where('lib_pre_booking.status', 'active');
        $query = $this->db->get('lib_pre_booking')->result_array();

        return $query;
        
    }

    /**
     * Retrieves the booking details of students.
     *
     * This function retrieves the booking details of students from the database. It performs a join operation
     * on multiple tables to fetch the required information. The function selects the booking details, book title,
     * member type, student's first name, middle name, last name, roll number, class, and section. The join
     * operations are performed on the 'books', 'libarary_members', 'students', 'student_session', 'classes',
     * and 'sections' tables. The function applies the conditions to filter the results based on the member type,
     * status, and current session. Finally, it executes the query and returns the result as an array.
     *
     * @return array An array containing the booking details of students.
     */
    public function getBookingStudent()
    {
        $this->db->select('lib_pre_booking.*,books.book_title,libarary_members.member_type,
        students.firstname,students.middlename,students.lastname,
        student_session.roll_no,classes.class,sections.section');
        $this->db->join('books', 'books.id = lib_pre_booking.book_id', 'left');
        $this->db->join('libarary_members', 'libarary_members.id = lib_pre_booking.member_id', 'left');
        $this->db->join('students', 'students.id = libarary_members.member_id', 'left');
        $this->db->join('student_session',  'student_session.student_id = students.id', 'left');
        $this->db->join('classes',  'classes.id = student_session.class_id', 'left');
        $this->db->join('sections',  'sections.id = student_session.section_id', 'left');
        $this->db->where('libarary_members.member_type', 'student');
        $this->db->where('lib_pre_booking.status', 'active');
        $this->db->where('student_session.session_id', $this->current_session);
        $query = $this->db->get('lib_pre_booking')->result_array();

        return $query;
    }
}
