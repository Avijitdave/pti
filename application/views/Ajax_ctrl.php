<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax_ctrl extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->database();
//         $db2 = $this->load->database('sqlsvr', TRUE);
        
        $this->load->library(['form_validation','session','email']);
        //$this->load->model(array('Channel_model'));
        $this->load->helper(['url', 'language','download']);
    }
    
    public function getCities($state=null,$city=null){
	    $this->db->select('*');
	    if(!is_null($state)){
	        $this->db->where('state_short_code',$state);
	    }
	    
	    if(!is_null($city)){
	        $this->db->where('short_code',$city);
	    }
	    
	    $this->db->get_where('ibc_cities',array('is_active'=>1))->result_array();
		$this->load->view('welcome_message');
	}
	
}
