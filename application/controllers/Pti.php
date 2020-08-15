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
    
    function com_create_guid() {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0x0fff ) | 0x4000,
            mt_rand( 0, 0x3fff ) | 0x8000,
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
            );
    }
    
    function Translate ($host, $path, $key, $params, $content) {    
        $headers = "Content-type: application/json\r\n" .
            "Content-length: " . strlen($content) . "\r\n" .
            "Ocp-Apim-Subscription-Key: $key\r\n" .
            "X-ClientTraceId: " . $this->com_create_guid() . "\r\n";
        
        $options = array (
            'http' => array (
                'header' => $headers,
                'method' => 'POST',
                'content' => $content
            )
        );
        
        $context  = stream_context_create ($options);
        $result = file_get_contents ($host . $path . $params, false, $context);
        return $result;
    }
    
    public function trans($text){
        $text = urldecode($text);
	    $requestBody = array (
	        array ('Text' => $text,),
	    );
	    $content = json_encode($requestBody);
	    $result = $this->Translate ($this->config->item('endpoint'), $this->config->item('path'), $this->config->item('subscription_key'), $this->config->item('params'), $content);
	    $json = json_decode(json_encode(json_decode($result), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),true);
	    return $json[0]['translations'][0]['text'];
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
        	        //$temp['slug_eng'] = str_replace(' ', '-', $this->trans($temp['slug_hindi']));
        	        $temp['slug_eng'] = '';
        	        $temp['origin_hindi'] = trim($result['origin'],'.');
        	        //$temp['origin_eng'] = $this->trans($temp['origin_hindi']);
        	        $temp['origin_eng'] = '';
        	        $temp['meta_tag'] = $temp['slug_hindi'].$temp['slug_eng'];
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
    	    if(count($newsList)>0){
    	       $this->db->insert_batch('ibc_news_pti',$newsList);
    	    }
    	    
    	    $ptiRecords = $this->Pti_model->news();
    	    
    	    foreach($ptiRecords as $ptiRecord){
    	        if($ptiRecord['categories'] == 'KHL' || $ptiRecord['categories'] == 'VID' || $ptiRecord['categories'] == 'ART' || $ptiRecord['categories'] == 'SNS'){
    	            $db2 = $this->load->database('main', TRUE);
    	            
    	            $temp = array();
    	            $temp['news_name_hindi'] = $ptiRecord['slug_hindi'];
    	            $temp['news_name_english'] = $ptiRecord['slug_eng'];
    	            $temp['news_content'] = $ptiRecord['content'];
                    $temp['meta_title'] = NULL;
    	            $temp['cannonical_link'] = NULL;
    	            $temp['slug'] = $ptiRecord['slug_eng'].'-'.date('U');
    	            $temp['meta_description'] = $ptiRecord['meta_tag'];
                    $temp['meta_keywords'] = NULL;
    	            $temp['status'] = 'Published';
                    $temp['published_by'] = $this->config->item('bhashaId');
    	            $temp['publish_datetime'] = date('Y-m-d H:i:s');
    	            $temp['created_by'] = $this->config->item('bhashaId');
    	            $temp['updated_by'] = NULL;
    	            $temp['country_id'] = '101';
                    $temp['country'] = 'इंडिया';
                    $temp['state_id'] = NULL;
                    $temp['state'] = NULL;
                    $temp['city_id'] = NULL;
                    $temp['city'] = NULL;
                    $temp['is_do_follow'] = 'No';
                    $temp['standout_tags'] = 'No';
                    $temp['reported_by'] = $this->config->item('bhashaId');
                    $temp['updated_datetime'] = date('Y-m-d H:i:s');
                    $temp['our_rating'] = '0.5';
                    $temp['critics_rating'] = '0.5';
                    $temp['static_tags'] = NULL;
                    $temp['user_tags'] = NULL;
                    $temp['content_type'] = 'news';
    	            
                    //insert record on table ibc_news
                    $db2->insert('ibc_news',$temp);                
                    
                    ///update slug inserted record
                    $insertId = $db2->insert_id();
                    $db2->where('id',$insertId);
                    $db2->update('ibc_news',array('slug'=>$ptiRecord['slug_eng'].'-'.$insertId));

                    //insert record on table ibc_news_categories
                    $db2->insert('ibc_news_categories',array('news_id'=>$insertId,'category_id'=>$ptiRecord['ibc_category']));

                    //update local record
                    $this->db->where('guid',$ptiRecord['guid']);
                    $this->db->update('ibc_news_pti',array('status'=>0));
    	        }
    	        
    	        else if($ptiRecord['categories'] == 'PRD'){
    	            if($ptiRecord['ibc_category'] != '29' && $ptiRecord['ibc_category'] != '30'){
//     	                $temp = array();
//     	                $temp['news_name_hindi'] = $ptiRecord['slug_hindi'];
//     	                $temp['news_name_english'] = $ptiRecord['slug_eng'];
//     	                $temp['news_content'] = $ptiRecord['content'];
//     	                $temp['meta_title'] = NULL;
//     	                $temp['cannonical_link'] = NULL;
//     	                $temp['slug'] = $ptiRecord['slug_eng'].'-'.date('U');
//     	                $temp['meta_description'] = $ptiRecord['meta_tag'];
//     	                $temp['meta_keywords'] = NULL;
//     	                $temp['status'] = 'Published';
//     	                $temp['published_by'] = $this->config->item('bhashaId');
//     	                $temp['publish_datetime'] = date('Y-m-d H:i:s');
//     	                $temp['created_by'] = $this->config->item('bhashaId');
//     	                $temp['updated_by'] = NULL;
//     	                $temp['country_id'] = '101';
//     	                $temp['country'] = 'इंडिया';
//     	                $temp['state_id'] = NULL;
//     	                $temp['state'] = NULL;
//     	                $temp['city_id'] = NULL;
//     	                $temp['city'] = NULL;
//     	                $temp['is_do_follow'] = 'No';
//     	                $temp['standout_tags'] = 'No';
//     	                $temp['reported_by'] = $this->config->item('bhashaId');
//     	                $temp['updated_datetime'] = date('Y-m-d H:i:s');
//     	                $temp['our_rating'] = '0.5';
//     	                $temp['critics_rating'] = '0.5';
//     	                $temp['static_tags'] = NULL;
//     	                $temp['user_tags'] = NULL;
//     	                $temp['content_type'] = 'news';
    	                
//     	                //insert record on table ibc_news
//     	                $db2->insert('ibc_news',$temp);
    	                
//     	                ///update slug inserted record
//     	                $insertId = $db2->insert_id();
//     	                $db2->where('id',$insertId);
//     	                $db2->update('ibc_news',array('slug'=>$ptiRecord['slug_eng'].'-'.$insertId));
    	                
//     	                //insert record on table ibc_news_categories
//     	                $db2->insert('ibc_news_categories',array('news_id'=>$insertId,'category_id'=>$ptiRecord['ibc_category']));
    	                
//     	                //update local record
//     	                $this->db->where('guid',$ptiRecord['guid']);
//     	                $this->db->update('ibc_news_pti',array('status'=>0));
    	                
    	            } else {
    	                echo "hold";
    	            }
    	        }
    	    }
    	    
	    } else {
	        echo "API not responding.";
	    }
	}
}
