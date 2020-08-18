<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ani extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        //$this->load->database();
        //$this->load->model(array('Pti_model'));
        $this->load->library(['form_validation','session','email']);
        $this->load->helper(['url', 'language','download']);
    }
    
    
    public function ani_json(){
        $results = json_decode(file_get_contents("http://newsapi.org/v2/top-headlines?country=in&category=sports&apiKey=76d2475825c34620b550fac48aa50291"),true);
        print_r($results); die;
    }
}

