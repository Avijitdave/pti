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
	    
	    $temp['ibc_category'] = '94';
	    $temp['ibc_country_id']  = '101';
	    $temp['country'] = 'इंडिया';
		$temp['state_id'] = NULL;
		$temp['state'] = NULL;
		$temp['city_id'] = NULL;
	    $temp['city'] = NULL;
	    
	    if($cate == 'VID'){
	        $temp['ibc_news_type'] = '14';   
	    }
	    else if($cate == 'KHL'){
	        $temp['ibc_news_type'] = '16';
	    }
	    else if($cate == 'ART'){
	        $temp['ibc_news_type'] = '17';
	    }
	    else if($cate == 'SNS'){
	        $temp['ibc_news_type'] = '12';
	    }
	    // else if($cate == 'DEL'){
	        // //$temp['ibc_news_type'] = '6';
	    // }
	    else {
			$city = trim($city,'.');
	        $result = $this->db->query("select state_short_code from ibc_cities where city_name_hindi = '$city'")->result_array();
	        
	        if(count($result)>0){
	            if($result[0]['state_short_code'] == 'MP'){
	                $temp['ibc_news_type'] = '18';
	                $temp['state_id'] = '21';
	                $temp['state'] = 'मध्य प्रदेश';
	            }
	            else if($result[0]['state_short_code'] == 'CG'){
	                $temp['ibc_news_type'] = '19';
					$temp['state_id'] = '7';
					$temp['state'] = 'छत्तीसगढ़';
	            }
	            else if($result[0]['state_short_code'] == 'UP'){
	                $temp['ibc_news_type'] = '38';
					$temp['state'] = 'उत्तर प्रदेश';
	            }
	            else if($result[0]['state_short_code'] == 'MH'){
	                $temp['ibc_news_type'] = '22';
					$temp['state'] = 'महाराष्ट्र';
	            }
	            else if($result[0]['state_short_code'] == 'BR'){
	                $temp['ibc_news_type'] = '5';
					$temp['state'] = 'बिहार';
	            }else {
	                $temp['ibc_news_type'] = '13';                   //national
					$temp['state'] = NULL;
	            }
	        }
			else {
				$temp['ibc_news_type'] = '13';                   //national
				$temp['state'] = NULL;
			}
			
	    }
	    
	    return $temp;
	}
	
}
