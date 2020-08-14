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
	    $temp['ibc_country_id']  = '101';
	    $temp['country'] = 'इंडिया';
		$temp['state_id'] = NULL;
		$temp['state'] = NULL;
		$temp['city_id'] = NULL;
	    $temp['city'] = NULL;
	    $temp['categories'] = $cate;
	    
	    if($cate == 'VID'){
	        $temp['ibc_category'] = '4';
	    }
	    else if($cate == 'KHL'){
	        $temp['ibc_category'] = '5';
	    }
	    else if($cate == 'ART'){
	        $temp['ibc_category'] = '6';
	    }
	    else if($cate == 'SNS'){
	        $temp['ibc_category'] = '98';
	    }
	    
	    else {
			$city = trim($city,'.');
	        $result = $this->db->query("select id,state_short_code from ibc_cities where city_name_hindi = '$city'")->result_array();   
	        if(count($result)>0){
	            
	            if($result[0]['state_short_code'] == 'MP'){
	                $temp['ibc_category'] = '30';
	                $temp['state_id'] = '21';
	                $temp['state'] = 'मध्य प्रदेश';
	                $temp['city_id'] = $result[0]['id'];
	            }
	            else if($result[0]['state_short_code'] == 'CG'){
	                $temp['ibc_category'] = '29';
					$temp['state_id'] = '7';
					$temp['state'] = 'छत्तीसगढ़';
					$temp['city_id'] = $result[0]['id'];
	            }
	            else if($result[0]['state_short_code'] == 'UP'){
	                $temp['ibc_category'] = '96';
	                $temp['state_id'] = '38';
					$temp['state'] = 'उत्तर प्रदेश';
					$temp['city_id'] = $result[0]['id'];
	            }
	            else if($result[0]['state_short_code'] == 'MH'){
	                $temp['ibc_category'] = '97';
	                $temp['state_id'] = '22';
					$temp['state'] = 'महाराष्ट्र';
					$temp['city_id'] = $result[0]['id'];
	            }
	            else if($result[0]['state_short_code'] == 'BR'){
	                $temp['ibc_category'] = '95';
	                $temp['state_id'] = '5';
					$temp['state'] = 'बिहार';
					$temp['city_id'] = $result[0]['id'];
	            }else {
	                $temp['ibc_category'] = '3';
	                $temp['city_id'] = $result[0]['id'];
	            }
	        }
			else {
				$temp['ibc_category'] = '3';                   //national
			}
	    }
	    return $temp;
	}
	
	
	
	function news(){
	    $this->db->select('inp.*,ic.category_name_hindi,ici.city_name_hindi');
	    $this->db->order_by('inp.published','desc');
	    $this->db->join('ibc_categories ic','ic.id = inp.ibc_category','left');
	    $this->db->join('ibc_cities ici','ici.id = inp.city_id','left');
	    $data['feeds'] = $this->db->get_where('ibc_news_pti inp',array('date(inp.published)'=>'2020-08-07','inp.status'=>1))->result_array();
	    return $data['feeds'];
	}
	
}
