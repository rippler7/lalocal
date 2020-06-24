<?php
class Model_csv extends CI_Model {
    function __construct() {
        parent::__construct();
 
    }
 
    function get_products() {     
        $query = $this->db->get('products');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }
 
    function insert_csv($data) {
        $this->db->insert('products', $data);
    }
}