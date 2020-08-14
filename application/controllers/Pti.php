<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pti extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->database();
        $this->load->model(array('Pti_model'));
        $this->load->library(['form_validation','session','email']);
        $this->load->helper(['url', 'language','download']);
    }
    
	public function index(){
		$this->load->view('welcome_message');
	}
	
	
	public function pti_json(){
	    $results = json_decode(file_get_contents("http://localhost:82/ptijson.json"),true);
	    if(count($results['items'])>0){
	        
    	    $newsList = array(); 
    	    foreach($results['items'] as $result){
    	        $this->db->select('*');
    	        $inRecord = $this->db->get_where('ibc_news_pti',array('guid'=>$result['guid']))->result_array();
    	        
    	        if(!count($inRecord)>0){ 
        	        $temp = array();    
        	        $temp['guid'] = $result['guid'];
        	        $temp['link'] = $result['link'];
        	        $temp['slug_hindi'] = $result['Slug'];
        	        $temp['slug_eng'] = '';
        	        $temp['origin_hindi'] = trim($result['origin'],'.');
        	        $temp['origin_eng'] = '';
        	        $temp['content'] = $result['content'];
        	        $temp['summary'] = $result['summary'];
        	        $temp['author'] = 101;
        	        $temp['published'] = $result['published'];
        	        $temp['updated'] = $result['updated'];
        	        $temp['sub_headline'] = $result['sub_headline'];
        	        $temp['status'] = 1;	
        	        
        	        $Detail = $this->Pti_model->getIbcCategory($result['categories'][0],$result['origin']);
        	        if(count($Detail)>0){  
        	           $temp['categories'] = $Detail['categories'];
        	           $temp['ibc_category'] = $Detail['ibc_category'];
        	           $temp['ibc_country_id'] = $Detail['ibc_country_id'];
        	           $temp['country'] = $Detail['country'];
        	           $temp['city_id'] = $Detail['city_id'];
        	           $temp['city'] = $Detail['city'];
        	           $temp['state_id'] = $Detail['state_id'];
        	           $temp['state'] = $Detail['state'];
        	        }
        	        $newsList[] = $temp;
    	        } else {
    	            continue;
    	        }
    	    }
    	    $this->db->insert_batch('ibc_news_pti',$newsList);
    	    
	    } else {
	        echo "API not responding.";
	    }
	}
}
