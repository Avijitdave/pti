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
	    
	    $newsList = array();
	    
	    foreach($results['items'] as $result){
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
	        $temp['categories'] = $result['categories'][0];
	        $temp['sub_headline'] = $result['sub_headline'];
	        $temp['status'] = 1;	
	        $Detail = $this->Pti_model->getIbcCategory($result['categories'][0],$result['origin']);
	        if(count($Detail)>0){  
	           $temp['ibc_category'] = $Detail['ibc_category'];
	           $temp['ibc_news_type'] = $Detail['ibc_news_type'];
	           $temp['ibc_country_id'] = $Detail['ibc_country_id'];
	           $temp['country'] = $Detail['country'];
	           $temp['city_id'] = $Detail['city_id'];
	           $temp['city'] = $Detail['city'];
	           $temp['state_id'] = $Detail['state_id'];
	           $temp['state'] = $Detail['state'];
	        }
	        $newsList[] = $temp;
	    }
	    $this->db->insert_batch('ibc_news_pti',$newsList);
	}
	
	
	function fetch($date=null,$cate=null,$city=null){
	    if(is_null($date)){
	        $this->db->where('date(published)',date('Y-m-d'));
	    } else {
	        $this->db->where('date(published)',$date);
	    }
	    
	    if(!is_null($cate)){
	        $this->db->where('categories',$cate);
	    }
	    
	    if(!is_null($city)){
	        $this->db->where('origin_hindi',$city);
	    }
	    
	    $this->db->select('*');
	    $this->db->order_by('published','desc');
	    $data['feeds'] = $this->db->get_where('ibc_news_pti',array('status'=>1))->result_array();
	    $this->load->view('pti_dashboard',$data);
	}
}
