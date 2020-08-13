<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pti extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->database();
//         $db2 = $this->load->database('sqlsvr', TRUE);
        
        $this->load->library(['form_validation','session','email']);
        //$this->load->model(array('Channel_model'));
        $this->load->helper(['url', 'language','download']);
    }
    
	public function index(){
		$this->load->view('welcome_message');
	}
	
	public function pti_json(){
	    $results = json_decode(file_get_contents("http://localhost:82/ptijson.json"),true);
	    
	    $newsList = array();
	    
	    foreach($results['items'] as $result){
	        $temp = array();
	        
	        $temp['guid'] = $result['guid'];
	        $temp['link'] = $result['link'];
	        $temp['slug_hindi'] = $result['Slug'];
	        $temp['slug_eng'] = '';
	        $temp['origin_hindi'] = $result['origin'];
	        $temp['origin_eng'] = '';
	        $temp['content'] = $result['content'];
	        $temp['summary'] = $result['summary'];
	        $temp['author'] = 101;
	        $temp['published'] = $result['published'];
	        $temp['updated'] = $result['updated'];
	        $temp['categories'] = $result['categories'][0];
	        $temp['sub_headline'] = $result['sub_headline'];
	        $temp['status'] = 1;	        
	        $newsList[] = $temp;
	    }
	    
	    $this->db->insert_batch('ibc_news_pti',$newsList);
	}
}