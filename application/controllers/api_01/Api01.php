<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Api01 extends CI_Controller {	

	public function __construct(){
		parent::__construct();
        $this->load->database(); // Load the database
    }
	public function index($id) {
        $query = $this->db->query('SELECT * FROM tbl_medicine WHERE id > $id LIMIT 1');
        $result = $query->result();

        // Return data as JSON
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }
}