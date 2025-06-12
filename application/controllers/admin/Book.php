<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Book extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('encoding_lib');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('books', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'book/index');

        $data['title']      = 'Add Book';
        $data['title_list'] = 'Book Details';
        $listbook           = $this->book_model->listbook();
        $data['listbook']   = $listbook;
        $data['publisherlist'] = $this->book_model->getpublisher();
        $data['authorlist'] = $this->book_model->getauthor();
        $data['subjectlist'] = $this->book_model->getsubject();
        $data['category'] = $this->book_model->getcategory();
        $data['itemTypeList'] = $this->book_model->getitem_type();
        $this->load->view('layout/header');
        $this->load->view('admin/book/createbook', $data);
        $this->load->view('layout/footer');
    }

    public function getall()
    {

        if (!$this->rbac->hasPrivilege('books', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'book/getall');
        $this->load->view('layout/header');
        $this->load->view('admin/book/getall');
        $this->load->view('layout/footer');
    }

    public function create()
    {
        if (!$this->rbac->hasPrivilege('books', 'can_add')) {
            access_denied();
        }
        $data['title']      = 'Add Book';
        $data['title_list'] = 'Book Details';
        $this->form_validation->set_rules('book_title', $this->lang->line('book_title'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $listbook         = $this->book_model->listbook();
            $data['listbook'] = $listbook;
            $data['publisherlist'] = $this->book_model->getpublisher();
            $data['authorlist'] = $this->book_model->getauthor();
            $data['subjectlist'] = $this->book_model->getsubject();
            $data['category'] = $this->book_model->getcategory();
            $data['itemTypeList'] = $this->book_model->getitem_type();
            $this->load->view('layout/header');
            $this->load->view('admin/book/createbook', $data);
            $this->load->view('layout/footer');
        } else {
            $data = array(
                'book_title'  => $this->input->post('book_title'),
                'book_no'     => $this->input->post('book_no'),
                'isbn_no'     => $this->input->post('isbn_no'),
                'subject'     => $this->input->post('subject'),
                'rack_no'     => $this->input->post('rack_no'),
                'publish'     => $this->input->post('publish'),
                'author'      => $this->input->post('author'),
                'qty'         => $this->input->post('qty'),
                'purchase_cost' => $this->input->post('purchase_cost'),
                'description' => $this->input->post('description'),
                'excession_no' => $this->input->post('excession_no'),
                'call_no' => $this->input->post('call_no'),
                'category' => $this->input->post('category'),
                'barcode' => $this->input->post('barcode'),
                'place_of_publication' => $this->input->post('place_of_publication'),
                'date_of_publication' => $this->input->post('date_of_publication') !="" ? date('Y-m-d',strtotime($this->input->post('date_of_publication'))):null,
                'no_of_page' => $this->input->post('no_of_page'),
                'price' => $this->input->post('price'),
                'classification_no' => $this->input->post('classification_no'),
                'extent' => $this->input->post('extent'),
                'physical_details' => $this->input->post('physical_details'),
                'item_type' => $this->input->post('item_type'),
            );

            if (isset($_POST['postdate']) && $_POST['postdate'] != '') {
                $data['postdate'] = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('postdate')));
            } else {
                $data['postdate'] = null;
            }
            $this->book_model->addbooks($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/book/index');
        }
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('books', 'can_edit')) {
            access_denied();
        }

        $data['title']      = 'Edit Book';
        $data['title_list'] = 'Book Details';
        $data['id']         = $id;
        $editbook           = $this->book_model->get($id);
        $data['editbook']   = $editbook;
        $this->form_validation->set_rules('book_title', $this->lang->line('book_title'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $listbook         = $this->book_model->listbook();
            $data['listbook'] = $listbook;
            $data['publisherlist'] = $this->book_model->getpublisher();
            $data['authorlist'] = $this->book_model->getauthor();
            $data['subjectlist'] = $this->book_model->getsubject();
            $data['category'] = $this->book_model->getcategory();
            $data['itemTypeList'] = $this->book_model->getitem_type();
            $this->load->view('layout/header');
            $this->load->view('admin/book/editbook', $data);
            $this->load->view('layout/footer');
        } else {
            $data = array(
                'id'          => $this->input->post('id'),
                'book_title'  => $this->input->post('book_title'),
                'book_no'     => $this->input->post('book_no'),
                'isbn_no'     => $this->input->post('isbn_no'),
                'subject'     => $this->input->post('subject'),
                'rack_no'     => $this->input->post('rack_no'),
                'publish'     => $this->input->post('publish'),
                'author'      => $this->input->post('author'),
                'qty'         => $this->input->post('qty'),
                'purchase_cost' => $this->input->post('purchase_cost'),
                'description' => $this->input->post('description'),
                'excession_no' => $this->input->post('excession_no'),
                'call_no' => $this->input->post('call_no'),
                'category' => $this->input->post('category'),
                'barcode' => $this->input->post('barcode'),
                'place_of_publication' => $this->input->post('place_of_publication'),
                'date_of_publication' => $this->input->post('date_of_publication') !="" ? date('Y-m-d',strtotime($this->input->post('date_of_publication'))):null,
                'no_of_page' => $this->input->post('no_of_page'),
                'price' => $this->input->post('price'),
                'classification_no' => $this->input->post('classification_no'),
                'extent' => $this->input->post('extent'),
                'physical_details' => $this->input->post('physical_details'),
                'item_type' => $this->input->post('item_type'),
            );
            if (isset($_POST['postdate']) && $_POST['postdate'] != '') {
                $data['postdate'] = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('postdate')));
            } else {
                $data['postdate'] = null;
            }

            $this->book_model->addbooks($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/book/index');
        }
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('books', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Fees Master List';
        $this->book_model->remove($id);
        redirect('admin/book/getall');
    }

    public function getAvailQuantity()
    {

        $book_id   = $this->input->post('book_id');
        $available = 0;
        if ($book_id != "") {
            $result    = $this->bookissue_model->getAvailQuantity($book_id);
            $available = $result->qty - $result->total_issue;
        }
        $result_final = array('status' => '1', 'qty' => $available);
        echo json_encode($result_final);
    }

    public function import()
    {
        // error_reporting(E_ALL);
        // ini_set('display_errors',1);

        $data['fields'] = array('book_title','book_no','isbn_no','subject','rack_no','publish','author','qty','price','purchase_cost',
        'excession_no','call_no','barcode','place_of_publication','date_of_publication','no_of_page','category',
        'classification_no','extent','physical_details','item_type','postdate','description','available');
        $this->form_validation->set_rules('file', 'Image', 'callback_handle_csv_upload');
        if ($this->form_validation->run() == false) {

            $this->load->view('layout/header');
            $this->load->view('admin/book/import', $data);
            $this->load->view('layout/footer');
        } else {
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                if ($ext == 'csv') {
                    $file = $_FILES['file']['tmp_name'];
                    $this->load->library('CSVReader');
                    $result = $this->csvreader->parse_file($file);
                    
                    $rowcount = 0;
                    if (!empty($result)) {
                        // echo '<pre>';
                        // book_title,book_no,isbn_no,subject,rack_no,publish,author,qty,price,purchase cost,excession no,call no,barcode,place of publication,date of publication
                        // ,no of page,category,classification no,extent,physical details,item type,postdate,description,available



                        foreach ($result as $r_key=>$r_val) {
                            // print_r($r_key['book_title']);
                            $publish_id = $this->book_model->save_publish_name($r_val['publish']);
                            $subject_id = $this->book_model->save_subject_name($r_val['subject']);
                            $author_id = $this->book_model->save_author_name($r_val['author']);
                            $item_type_id = $this->book_model->save_item_name($r_val['item_type']);
                            $category_id = $this->book_model->save_category_name($r_val['category']);
                            $result[$r_key]['book_title']  = $this->encoding_lib->toUTF8($r_val['book_title']);
                            $result[$r_key]['book_no']     = $this->encoding_lib->toUTF8($r_val['book_no']);
                            $result[$r_key]['isbn_no']     = $this->encoding_lib->toUTF8($r_val['isbn_no']);
                            $result[$r_key]['subject']     = $subject_id;
                            $result[$r_key]['rack_no']     = $this->encoding_lib->toUTF8($result[$r_key]['rack_no']);
                            $result[$r_key]['publish']     = $publish_id;
                            $result[$r_key]['author']      = $author_id;
                            $result[$r_key]['qty']         = $this->encoding_lib->toUTF8($r_val['qty']);
                            $result[$r_key]['price']       = $this->encoding_lib->toUTF8($r_val['price']);
                            $result[$r_key]['purchase_cost']= $this->encoding_lib->toUTF8($r_val['purchase_cost']);
                            $result[$r_key]['excession_no']= $this->encoding_lib->toUTF8($r_val['excession_no']);
                            $result[$r_key]['postdate']    = $this->customlib->dateformat($this->encoding_lib->toUTF8($r_val['postdate']));
                            $result[$r_key]['call_no']    = $this->encoding_lib->toUTF8($r_val['call_no']);
                            $result[$r_key]['barcode']    = $this->encoding_lib->toUTF8($r_val['barcode']);
                            $result[$r_key]['place_of_publication']    = $this->encoding_lib->toUTF8($r_val['place_of_publication']);
                            $result[$r_key]['date_of_publication']    = $this->customlib->dateformat($this->encoding_lib->toUTF8($r_val['date_of_publication']));
                            $result[$r_key]['no_of_page']    = $this->encoding_lib->toUTF8($r_val['no_of_page']);
                            $result[$r_key]['category']    = $category_id;
                            $result[$r_key]['classification_no']    = $this->encoding_lib->toUTF8($r_val['classification_no']);
                            $result[$r_key]['extent']    = $this->encoding_lib->toUTF8($r_val['extent']);
                            $result[$r_key]['physical_details']    = $this->encoding_lib->toUTF8($r_val['physical_details']);
                            $result[$r_key]['item_type']    = $item_type_id;
                            $result[$r_key]['available']    = $this->encoding_lib->toUTF8($r_val['available']);
                            $result[$r_key]['description'] = $this->encoding_lib->toUTF8($r_val['description']);
                            $rowcount++;
                        }
                        
                            // echo '<pre>';
                            // print_r($result);
                            // die();
                        // die();
                        $this->book_model->insert_all_book_data($result);
                        // $this->db->insert_batch('books', $result);
                        // echo $this->db->last_query(); die
                    }
                    $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('records_found_in_CSV_file_total') . ' ' . $rowcount . ' ' . $this->lang->line('records_imported_successfully'));
                }
            } else {
                $msg = array(
                    'e' => 'The File field is required.',
                );
                $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
            }

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">' . $this->lang->line('total') . ' ' . count($result) . "  " . $this->lang->line('records_found_in_CSV_file_total') . " " . $rowcount . ' ' . $this->lang->line('records_imported_successfully') . '</div>');
            redirect('admin/book/import');
        }
    }

    public function import_new()
    {

        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
            $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            if ($ext == 'csv') {
                $file = $_FILES['file']['tmp_name'];
                $this->load->library('CSVReader');
                $result = $this->csvreader->parse_file($file);

                $rowcount = 0;
                if (!empty($result)) {
                    foreach ($result as $r_key => $r_value) {
                        $result[$r_key]['book_title']  = $this->encoding_lib->toUTF8($result[$r_key]['book_title']);
                        $result[$r_key]['book_no']     = $this->encoding_lib->toUTF8($result[$r_key]['book_no']);
                        $result[$r_key]['isbn_no']     = $this->encoding_lib->toUTF8($result[$r_key]['isbn_no']);
                        $result[$r_key]['subject']     = $this->encoding_lib->toUTF8($result[$r_key]['subject']);
                        $result[$r_key]['rack_no']     = $this->encoding_lib->toUTF8($result[$r_key]['rack_no']);
                        $result[$r_key]['publish']     = $this->encoding_lib->toUTF8($result[$r_key]['publish']);
                        $result[$r_key]['author']      = $this->encoding_lib->toUTF8($result[$r_key]['author']);
                        $result[$r_key]['qty']         = $this->encoding_lib->toUTF8($result[$r_key]['qty']);
                        $result[$r_key]['perunitcost'] = $this->encoding_lib->toUTF8($result[$r_key]['perunitcost']);
                        $result[$r_key]['postdate']    = $this->encoding_lib->toUTF8($result[$r_key]['postdate']);
                        $result[$r_key]['description'] = $this->encoding_lib->toUTF8($result[$r_key]['description']);
                        $rowcount++;
                    }

                    $this->db->insert_batch('books', $result);
                }
                $array = array('status' => 'success', 'error' => '', 'message' => 'records found in CSV file. Total ' . $rowcount . 'records imported successfully.');
            }
        } else {
            $msg = array(
                'e' => 'The File field is required.',
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        }

        echo json_encode($array);
    }

    public function handle_csv_upload()
    {
        $error = "";
        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
            $allowedExts = array('csv');
            $mimes       = array('text/csv',
                'text/plain',
                'application/csv',
                'text/comma-separated-values',
                'application/excel',
                'application/vnd.ms-excel',
                'application/vnd.msexcel',
                'text/anytext',
                'application/octet-stream',
                'application/txt');
            $temp      = explode(".", $_FILES["file"]["name"]);
            $extension = end($temp);
            if ($_FILES["file"]["error"] > 0) {
                $error .= "Error opening the file<br />";
            }
            if (!in_array($_FILES['file']['type'], $mimes)) {
                $error .= "Error opening the file<br />";
                $this->form_validation->set_message('handle_csv_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }
            if (!in_array($extension, $allowedExts)) {
                $error .= "Error opening the file<br />";
                $this->form_validation->set_message('handle_csv_upload', $this->lang->line('extension_not_allowed'));
                return false;
            }
            if ($error == "") {
                return true;
            }
        } else {
            $this->form_validation->set_message('handle_csv_upload', $this->lang->line('please_select_file'));
            return false;
        }
    }

    public function exportformat()
    {
        $this->load->helper('download');
        $filepath = "./backend/import/import_book_sample_file.csv";
        $data     = file_get_contents($filepath);
        $name     = 'import_book_sample_file.csv';

        force_download($name, $data);
    }

    public function issue_report()
    {

        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'Library/book/issue_report');
        $data['title']       = 'Add Teacher';
        $teacher_result      = $this->teacher_model->getLibraryTeacher();
        $data['teacherlist'] = $teacher_result;

        $genderList         = $this->customlib->getGender();
        $data['genderList'] = $genderList;
        $issued_books       = $this->bookissue_model->getissueMemberBooks();

        $data['issued_books'] = $issued_books;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/book/issuereport', $data);

        $this->load->view('layout/footer', $data);
    }

    public function issue_returnreport()
    {
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/library');
        $this->session->set_userdata('subsub_menu', 'Reports/library/issue_returnreport');
        $data['title']  = 'Add Teacher';
        $teacher_result = $this->teacher_model->getLibraryTeacher();

        $issued_books         = $this->bookissue_model->getissuereturnMemberBooks();
        $data['issued_books'] = $issued_books;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/book/issue_returnreport', $data);
        $this->load->view('layout/footer', $data);
    }

    public function getbooklist()
    {

        $listbook        = $this->book_model->getbooklist();
        $m               = json_decode($listbook);
       
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
        $dt_data         = array();
        if (!empty($m->data)) {
            foreach ($m->data as $key => $value) {
                $editbtn   = '';
                $deletebtn = '';

                if ($this->rbac->hasPrivilege('books', 'can_edit')) {
                    $editbtn = "<a href='" . base_url() . "admin/book/edit/" . $value->id . "'   class='btn btn-default btn-xs'  data-toggle='tooltip' data-placement='left' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>";
                }
                if ($this->rbac->hasPrivilege('books', 'can_delete')) {
                    $deletebtn = "<a onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . "  )' href='" . base_url() . "admin/book/delete/" . $value->id . "' class='btn btn-default btn-xs' data-placement='left' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-trash'></i></a>";
                }

                $row   = array();
                $row[] = $value->book_title;
                if ($value->description == "") {
                    $row[] = $this->lang->line('no_description');
                } else {
                    $row[] = $value->description;
                }
                $row[]     = $value->book_no;
                $row[]     = $value->isbn_no;
                $row[]     = $value->publish;
                $row[]     = $value->author;
                $row[]     = $value->subject;
                $row[]     = $value->rack_no;
                $row[]     = $value->qty;
                $row[]     = $value->qty - $value->total_issue;
                $row[]     = $currency_symbol . $value->price;
                $row[]     = $this->customlib->dateformat($value->postdate);
                $row[]     = $editbtn . ' ' . $deletebtn;
                $dt_data[] = $row;
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

    /* function to get book inventory report by using datatable */
    public function dtbookissuereturnreportlist()
    {
        $teacher_result = $this->teacher_model->getLibraryTeacher();
        $issued_books   = $this->bookissue_model->getissuereturnMemberBooks();
        $resultlist     = json_decode($issued_books);
        $dt_data        = array();

        if (!empty($resultlist->data)) {

            $editbtn   = "";
            $deletebtn = "";
            foreach ($resultlist->data as $resultlist_key => $value) {

                $row   = array();
                $row[] = $value->book_title;
                $row[] = $value->book_no;
                $row[] = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value->issue_date));
                $row[] = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value->return_date));
                $row[] = $value->members_id;
                $row[] = $value->library_card_no;

                if ($value->admission != 0) {

                    $row[] = $value->admission;

                } else {
                    $row[] = "";
                }
                $row[] = ucwords($value->fname) . " " . ucwords($value->lname);
                $row[] = $value->member_type;

                $dt_data[] = $row;
            }

        }
        $json_data = array(
            "draw"            => intval($resultlist->draw),
            "recordsTotal"    => intval($resultlist->recordsTotal),
            "recordsFiltered" => intval($resultlist->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    public function publisher($id = null)
    {
        if (!$this->rbac->hasPrivilege('publisher', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'book/publisher');
        $this->data = "";

        if ($id != "") {
            $data['update']  = $this->book_model->getpublisher($id);
        }
        $this->form_validation->set_rules('publisher', "Publisher", 'required|trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
        } else {
            $userdata           = $this->customlib->getUserData();
            $insert_array = array(
                'id' => $this->input->post('id'),
                'publisher' => $this->input->post('publisher'),
                
            );

            $this->book_model->addPublisher($insert_array);

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/book/publisher');
        }

        $data['resultlist'] = $this->book_model->getpublisher();



        $this->load->view('layout/header');
        $this->load->view('admin/book/publisher', $data);
        $this->load->view('layout/footer');
    }

    public function delete_publisher($id)
    {
        if (!$this->rbac->hasPrivilege('publisher', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Publisher';
        $this->book_model->remove_publisher($id);
        redirect('admin/book/publisher');
    }

    public function author($id = null)
    {
        if (!$this->rbac->hasPrivilege('author', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'book/author');
        $this->data = "";

        if ($id != "") {
            $data['update']  = $this->book_model->getauthor($id);
        }
        $this->form_validation->set_rules('author', "Author", 'required|trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
        } else {
            $userdata           = $this->customlib->getUserData();
            $insert_array = array(
                'id' => $this->input->post('id'),
                'author' => $this->input->post('author'),
                
            );

            $this->book_model->addauthor($insert_array);

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/book/author');
        }

        $data['resultlist'] = $this->book_model->getauthor();



        $this->load->view('layout/header');
        $this->load->view('admin/book/author', $data);
        $this->load->view('layout/footer');
    }

    public function delete_author($id)
    {
        if (!$this->rbac->hasPrivilege('author', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'author';
        $this->book_model->remove_author($id);
        redirect('admin/book/author');
    }

    public function subject($id = null)
    {
        if (!$this->rbac->hasPrivilege('subject', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'book/subject');
        $this->data = "";

        if ($id != "") {
            $data['update']  = $this->book_model->getsubject($id);
        }
        $this->form_validation->set_rules('subject', "subject", 'required|trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
        } else {
            $userdata           = $this->customlib->getUserData();
            $insert_array = array(
                'id' => $this->input->post('id'),
                'subject_lib' => $this->input->post('subject'),
                
            );

            $this->book_model->addsubject($insert_array);

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/book/subject');
        }

        $data['resultlist'] = $this->book_model->getsubject();



        $this->load->view('layout/header');
        $this->load->view('admin/book/subject', $data);
        $this->load->view('layout/footer');
    }

    public function delete_subject($id)
    {
        if (!$this->rbac->hasPrivilege('subject', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Subject';
        $this->book_model->remove_subject($id);
        redirect('admin/book/subject');
    }

    public function category($id = "")
    {
        if (!$this->rbac->hasPrivilege('category', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'book/category');
        $this->data = "";

        if ($id != "") {
            $data['update']  = $this->book_model->getcategory($id);
        }
        $this->form_validation->set_rules('category', "category", 'required|trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
        } else {
            $userdata           = $this->customlib->getUserData();
            $insert_array = array(
                'id' => $this->input->post('id'),
                'category' => $this->input->post('category'),
                
            );

            $this->book_model->addcategory($insert_array);

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/book/category');
        }

        $data['resultlist'] = $this->book_model->getcategory();



        $this->load->view('layout/header');
        $this->load->view('admin/book/category', $data);
        $this->load->view('layout/footer');
    }

    public function delete_category($id)
    {
        if (!$this->rbac->hasPrivilege('category', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'category';
        $this->book_model->remove_category($id);
        redirect('admin/book/category');
    }
    public function item_type($id = "")
    {
        if (!$this->rbac->hasPrivilege('item_type', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'book/item_type');
        $this->data = "";

        if ($id != "") {
            $data['update']  = $this->book_model->getitem_type($id);
        }
        $this->form_validation->set_rules('item_type_name', "item type", 'required|trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
        } else {
            $userdata           = $this->customlib->getUserData();
            $insert_array = array(
                'id' => $this->input->post('id'),
                'item_type_name' => $this->input->post('item_type_name'),
                
            );

            $this->book_model->additem_type($insert_array);

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/book/item_type');
        }

        $data['resultlist'] = $this->book_model->getitem_type();



        $this->load->view('layout/header');
        $this->load->view('admin/book/item_type', $data);
        $this->load->view('layout/footer');
    }

    public function delete_item_type($id)
    {
        if (!$this->rbac->hasPrivilege('item_type', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'item_type';
        $this->book_model->remove_item_type($id);
        redirect('admin/book/item_type');
    }

    public function choose_book($member_id,$type)
    {
        // if (!$this->rbac->hasPrivilege('books', 'can_view')) {
        //     access_denied();
        // }
        $data['member_id'] = $member_id;
        $data['type'] = $type;
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'book/getall');
        $this->load->view('layout/header');
        $this->load->view('admin/book/choose_book',$data);
        $this->load->view('layout/footer');
    }

    public function getbooklistchoose()
    {
        $list = $this->book_model->get_datatables();

        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            if ($this->input->post('type') == 1) {
                $choosebtn = "<a class='btn btn-primary' href='" . base_url() . "admin/member/pre_book/".$this->input->post('member_id')."/" .$item->id  . "'   class='btn btn-default btn-xs'  data-toggle='tooltip' data-placement='left' title=''>Select</a>";
            }else {
                $choosebtn = "<a class='btn btn-primary' href='" . base_url() . "admin/member/issue/".$this->input->post('member_id')."/" .$item->id  . "'   class='btn btn-default btn-xs'  data-toggle='tooltip' data-placement='left' title=''>Select</a>";
            }
            $row = array();
            $row['book_title'] = $item->book_title;
            if ($item->description == "") {
                $row['description'] = $this->lang->line('no_description');
            } else {
                $row['description'] = $item->description;
            }
            $row['book_no'] = $item->book_no;
            $row['isbn_no'] = $item->isbn_no;
            $row['publish'] = $item->publisher_name;
            $row['author'] = $item->author_name;
            $row['subject'] = $item->subject_lib;
            $row['rack_no'] = $item->rack_no;
            $row['qty'] = $item->qty;
            $row['available'] = $item->qty - $item->total_issue;
            $row['perunitcost'] = $currency_symbol. $item->price;
            $row['postdate'] = $this->customlib->dateformat($item->postdate);
            $row['action']     = $choosebtn;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->book_model->count_all(),
            "recordsFiltered" => $this->book_model->count_filtered(),
            "data" => $data,
        );
        // Output to JSON format
        echo json_encode($output);
    }

    public function check_availability()
    {
        // error_reporting(E_ALL);
        // ini_set('display_errors', 1);
        $book_id = $this->input->post('book_id');
        $issue_date = $this->input->post('issue_date');

        $bookList = $this->book_model->check_availability_issue($book_id,$issue_date);
        $prebookList = $this->book_model->check_availability_booking($book_id,$issue_date);
        if (empty($bookList) && empty($prebookList)) {
            $json = ['success'=>1,'error'=>0];
        }else {
            $json = ['error'=>1,'success'=>0];
        }

        echo json_encode($json);        
    }

    public function test()
    {
        $listbook           = $this->book_model->listbook();
        
        // echo "<pre>";
        // print_r ($listbook);die;
        // echo "</pre>";
        
        foreach ($listbook as $key => $value) {
            if ($value['author'] != "") {
                $author_id =  $this->book_model->save_author_name($value['author']);
                $this->db->where('author', $value['author']);
                $this->db->update('books', ['author' => $author_id]);
                
            }
            if ($value['publish'] != "") {
                $publish_id = $this->book_model->save_publish_name($value['publish']);
                $this->db->where('publish', $value['publish']);
                $this->db->update('books', ['publish' => $publish_id]);
            }
        }
    }

}
