<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 
 */
class Generatecertificate_model extends CI_Model {

    public $current_session;

    function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function getcertificatebyid($certificate) {
        $this->db->select('*');
        $this->db->from('certificates');
        $this->db->where('id', $certificate);
        $query = $this->db->get();
        return $query->result();
    }
    public function getbonafide_trnRow()
    {
        $this->db->order_by('id', 'desc');
        $q = $this->db->get('bonafide_trn');
        
        return $q->row_array();
    }
}

?>