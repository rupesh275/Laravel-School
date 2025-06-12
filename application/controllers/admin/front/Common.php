<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Common extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $config = array(
            'field' => 'slug',
            'title' => 'title',
            'table' => 'front_cms_programs',
            'id'    => 'id',
        );
        $this->load->library('slug', $config);
        $this->load->config('ci-blog');
        $this->load->library('imageResize');
    }
    public function index()
    {
        if (!$this->rbac->hasPrivilege('notice', 'can_view')) {
            access_denied();
        }
        $data = array();
        $this->session->set_userdata('top_menu', 'Front CMS');
        $this->session->set_userdata('sub_menu', 'admin/front/common');
        $notice_content = ''; // $this->config->item('ci_front_facility_content');
        
        $listResult     = $this->cms_program_model->getByCommon($notice_content);
        $data['category'] = $this->customlib->getPageContentCategory();
        $data['listResult'] = $listResult;
        $this->load->view('layout/header');
        $this->load->view('admin/front/common/index', $data);
        $this->load->view('layout/footer');
    }
    public function create()
    {
        if (!$this->rbac->hasPrivilege('notice', 'can_add')) {
            access_denied();
        }
        $data['title']      = 'Add Facility';
        $data['title_list'] = 'Facilities';
        
        $this->session->set_userdata('top_menu', 'Front CMS');
        $this->session->set_userdata('sub_menu', 'admin/front/facility');
        $this->form_validation->set_rules('title', $this->lang->line('title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('description', $this->lang->line('description'), 'trim|required');

        if ($this->form_validation->run() == false) {
            $data['category'] = $this->customlib->getPageContentCategory();
            $this->load->view('layout/header');
            $this->load->view('admin/front/common/create', $data);
            $this->load->view('layout/footer');
        } else {

            $category = $this->input->post('content_category');
            if (isset($category)) {
                $contents_category = $category;
            } else {
                $contents_category = "";
            }
            
            $data = array(
                'title'            => $this->input->post('title'),
                'description'      => htmlspecialchars_decode($this->input->post('description', false)),
                'meta_title'       => $this->input->post('meta_title'),
                'meta_keyword'     => $this->input->post('meta_keywords'),
                'feature_image'    => $this->input->post('image'),
                'date'             => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                'sidebar'          => $this->input->post('sidebar'),
                'type'             => $contents_category,//$this->config->item('ci_front_facility_content'),
                'meta_description' => $this->input->post('meta_description'),
            );
            $data['slug'] = $this->slug->create_uri($data);
            $data['url']  = $this->config->item('ci_front_page_read_url') . $data['slug'];
            $this->cms_program_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/front/common');
        }
    }

    public function edit($slug)
    {
        if (!$this->rbac->hasPrivilege('notice', 'can_edit')) {
            access_denied();
        }
        $data['title']      = 'Edit Facility';
        $data['title_list'] = 'Facility Details';
        $this->session->set_userdata('top_menu', 'Front CMS');
        $this->session->set_userdata('sub_menu', 'admin/front/facility');
        $result         = $this->cms_program_model->getBySlug(urldecode($slug));
        $data['category'] = $this->customlib->getPageContentCategory();
        $data['result'] = $result;
        $this->form_validation->set_rules('title', $this->lang->line('title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('description', $this->lang->line('description'), 'trim|required');

        if ($this->form_validation->run() == false) {
            $listbook         = $this->book_model->listbook();
            $data['listbook'] = $listbook;
            $this->load->view('layout/header');
            $this->load->view('admin/front/common/edit', $data);
            $this->load->view('layout/footer');
        } else {

            $data = array(
                'id'               => $this->input->post('id'),
                'title'            => $this->input->post('title'),
                'url'              => $this->config->item('ci_front_page_url') . $this->input->post('url'),
                'description'      => htmlspecialchars_decode($this->input->post('description', false)),
                'meta_title'       => $this->input->post('meta_title'),
                'meta_keyword'     => $this->input->post('meta_keywords'),
                'feature_image'    => $this->input->post('image'),
                'date'             => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                'sidebar'          => $this->input->post('sidebar'),
                'meta_description' => $this->input->post('meta_description'),
            );

            $data['slug'] = $this->slug->create_uri($data, $this->input->post('id'));
            $data['url']  = $this->config->item('ci_front_page_read_url') . $data['slug'];
            $this->cms_program_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/front/common');
        }
    }

    public function delete($id)
    {

        if (!$this->rbac->hasPrivilege('notice', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Facility';
        $this->cms_program_model->removeByid($id);
        redirect('admin/front/common');
    }

}
