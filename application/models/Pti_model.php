<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pti_model extends CI_Model {

    public function getCities($state=null,$city=null){
	    $this->db->select('*');
	    if(!is_null($state)){
	        $this->db->where('state_short_code',$state);
	    }
	    
	    if(!is_null($cate,$city)){
	        $this->db->where('short_code',$city);
	    }
	    $result = $this->db->get_where('ibc_cities',array('is_active'=>1))->result_array();
	    return $result;
	}
	
	function getIbcCategory($cate,$city){
	    $temp = array();
	    if($cate == 'VID'){
	        $temp['ibc_category'] = '4';
	        $temp['ibc_news_type'] = '6';
	    }
	    else if($cate == 'KHL'){
	        $temp['ibc_category'] = '5';
	        $temp['ibc_news_type'] = '6';
	    }
	    else if($cate == 'ART'){
	        $temp['ibc_category'] = '6';
	        $temp['ibc_news_type'] = '6';
	    }
	    else {
	        $city = trim($city,'.'); 
	        $result = $this->db->query("select state_short_code from ibc_cities where city_name_hindi = '$city'")->result_array();
	        if(count($result)>0){
	            if($result[0]['state_short_code'] == 'MP'){
	                $temp['ibc_category'] = '30';
	                $temp['ibc_news_type'] = '6';
	            }
	            else if($result[0]['state_short_code'] == 'CG'){
	                $temp['ibc_category'] = '29';
	                $temp['ibc_news_type'] = '6';
	            }
	            else if($result[0]['state_short_code'] == 'UP'){
	                $temp['ibc_category'] = '291';
	                $temp['ibc_news_type'] = '6';
	            }
	            else if($result[0]['state_short_code'] == 'MH'){
	                $temp['ibc_category'] = '292';
	                $temp['ibc_news_type'] = '6';
	            } else {
	                $temp['ibc_category'] = '3';                   //national
	                $temp['ibc_news_type'] = '6';
	            }
	        }
	    }
	    
	    return $temp;
	}
	
}
