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
        	    $results = json_decode(file_get_contents("http://192.168.25.184:82/ptijson.json"),true);
        	    //$results = json_decode(file_get_contents("http://editorial.pti.in/bhashajsontoken/webservice1.asmx/JsonFiley3?centercode=27072020001&n=400&FromTime=".date('Y/m/d')),true);
        	    if(isset($results['items']) && count($results['items'])>0){
        
        	        $this->db->trans_begin();
        
            	    $newsList = array();
            	    foreach($results['items'] as $result){
            	        $this->db->select('*');
            	        $inRecord = $this->db->get_where('ibc_news_pti',array('guid'=>$result['guid']))->result_array();
        
            	        if(!count($inRecord)>0){
                	        $temp = array();
                	        $temp['guid'] = $result['guid'];
                	        $temp['link'] = $result['link'];
                	        $temp['slug_hindi'] = $result['Slug'];
                	        $temp['title'] = $result['title'];
                	        $temp['title_eng'] = $this->trans($result['title']);
                	        $temp['slug_eng'] = str_replace(' ', '-', $temp['title_eng']);
                	        $temp['origin_hindi'] = trim(trim($result['origin'],'.'));
                	        $temp['origin_eng'] = $this->trans($temp['origin_hindi']);
                	        $temp['meta_tag'] = $temp['slug_hindi'].' '.$temp['slug_eng'];
                	        $temp['content'] = $result['content'];
                	        $temp['summary'] = $result['summary'];
                	        $temp['author'] = $this->config->item('bhashaId');
                	        $temp['published'] = $result['published'];
                	        $temp['updated'] = $result['updated'];
                	        $temp['sub_headline'] = $result['sub_headline'];
                	        $temp['status'] = 1;
        
                	        $Detail = $this->Pti_model->getIbcCategory($result['categories'][0],$temp['origin_eng']);
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
            $db2 = $this->load->database('main', TRUE);
            
            if($ptiRecord['categories'] == 'KHL' || $ptiRecord['categories'] == 'VID' || $ptiRecord['categories'] == 'ART' || $ptiRecord['categories'] == 'SNS'){
                $temp = array();
                $temp['news_name_hindi'] = $ptiRecord['title'];
                $temp['news_name_english'] = $ptiRecord['title_eng'];
                //$temp['news_content'] = $ptiRecord['content'];
                $temp['news_content'] = substr($ptiRecord['content'], 0,(strrpos($ptiRecord['content'], '<p>भाषा')));
                $temp['meta_title'] = $ptiRecord['title'].' '.str_replace('-', ' ', $ptiRecord['slug_eng']);
                $temp['cannonical_link'] = NULL;
                $temp['slug'] = str_replace('-', ' ',$ptiRecord['slug_eng'].'-'.date('U'));
                $temp['meta_description'] = $ptiRecord['title'].' '.str_replace('-', ' ', $ptiRecord['slug_eng']);
                $temp['meta_keywords'] = $ptiRecord['title'].' '.str_replace('-', ' ', $ptiRecord['slug_eng']);
                $temp['status'] = 'Published';
                $temp['published_by'] = $this->config->item('bhashaId');
                $temp['publish_datetime'] = $ptiRecord['published'];
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
                $temp['updated_datetime'] = $ptiRecord['updated'];
                $temp['our_rating'] = '0.5';
                $temp['critics_rating'] = '0.5';
                $temp['static_tags'] = NULL;
                $temp['user_tags'] = NULL;
                $temp['content_type'] = 'news';
                $temp['created_at'] = $ptiRecord['published'];
                
                //insert record on table ibc_news
                $db2->insert('ibc_news',$temp);
                
                ///update slug inserted record
                $insertId = $db2->insert_id();
                $db2->where('id',$insertId);
                $db2->update('ibc_news',array('slug'=>$ptiRecord['slug_eng'].'-'.$insertId));
                
                //insert record on table ibc_news_categories
                $db2->insert('ibc_news_categories',array('news_id'=>$insertId,'category_id'=>$ptiRecord['ibc_category'],'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')));
                
                $db2->insert('ibc_news_types_mapping',array('news_id'=>$insertId,'news_type_id'=>'9','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')));
                
                if($ptiRecord['categories'] == 'KHL'){
                    $categoryId = '1';
                } else if($ptiRecord['categories'] == 'VID'){
                    $categoryId = '2';
                } else if($ptiRecord['categories'] == 'ART'){
                    $categoryId = '3';
                } else if($ptiRecord['categories'] == 'SNS'){
                    $categoryId = '8';
                }
                
                $this->db->select('*');
                $categoryImages = $this->db->get_where('ibc_news_pti_medias',array('pti_category'=>$categoryId,'status'=>1))->result_array();
                
                if(count($categoryImages)>0){
                    $imageId = rand(1,count($categoryImages));
                    
                    $db2->insert('ibc_medias',array(
                        'title'=>$categoryImages[$imageId-1]['image'],
                        'name'=>$categoryImages[$imageId-1]['image'],
                        'path'=>$categoryImages[$imageId-1]['image_path'].$categoryImages[$imageId-1]['image'],
                        'thumb_path'=>$categoryImages[$imageId-1]['thumb_path'].$image,
                        'size'=>'0',
                        'description'=>'',
                        'media_type'=>'image'
                    ));
                    $mediaInsertId = $db2->insert_id();
                }
                else {
                    $mediaInsertId = '1001';
                }
                
                //media file
                
                $db2->insert('ibc_news_medias',array('news_id'=>$insertId,'media_id'=>$mediaInsertId,'is_featured'=>'0','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')));
                
                
                /*
                 * keyword
                 */
                
                $slugEnglish = $this->trans($ptiRecord['slug_hindi']);
                $keywords = explode(' ', $slugEnglish);
                $keywordTemp = array();
                foreach($keywords as $keyword){
                    $temp = array();
                    
                    $db2->select('*');
                    $keyresult = $db2->get_where('ibc_keywords',array('keyword'=>$keyword,'is_active'=>1))->result_array();
                    if(count($keyresult)>0){
                        $temp['keyword_id'] = $keyresult[0]['id'];
                        $temp['news_id'] = $insertId;
                    } else {
                        $db2->insert('ibc_keywords',array('keyword'=>$keyword,'is_active'=>1,'is_special_news_keyword'=>'0'));
                        $temp['keyword_id'] = $db2->insert_id();
                        $temp['news_id'] = $insertId;
                    }
                    $temp['created_at'] = date('Y-m-d H:i:s');
                    $temp['updated_at'] = date('Y-m-d H:i:s');
                    $keywordTemp[] = $temp;
                }
                
                
                $temp = array();
                $db2->select('*');
                $keyresult = $db2->get_where('ibc_keywords',array('keyword'=>$slugEnglish,'is_active'=>1))->result_array();
                if(count($keyresult)>0){
                    $temp['keyword_id'] = $keyresult[0]['id'];
                    $temp['news_id'] = $insertId;
                    $temp['created_at'] = date('Y-m-d H:i:s');
                    $temp['updated_at'] = date('Y-m-d H:i:s');
                } else {
                    $db2->insert('ibc_keywords',array('keyword'=>$slugEnglish,'is_active'=>1,'is_special_news_keyword'=>'0'));
                    $temp['keyword_id'] = $db2->insert_id();
                    $temp['news_id'] = $insertId;
                    $temp['created_at'] = date('Y-m-d H:i:s');
                    $temp['updated_at'] = date('Y-m-d H:i:s');
                }
                $keywordTemp[] = $temp;
                $db2->insert_batch('ibc_news_keywords',$keywordTemp);
                /*
                 * keyword closed
                 */
                
                
                
                //update local record
                $this->db->where('guid',$ptiRecord['guid']);
                $this->db->update('ibc_news_pti',array('status'=>0));
            }
            
            else if($ptiRecord['categories'] == 'PRD'){
                if($ptiRecord['ibc_category'] == '91' || $ptiRecord['ibc_category'] == '90'|| $ptiRecord['ibc_category'] == '92'){
                    $this->Pti_model->prd_submit($ptiRecord);
                    
                } else {
                    echo "on hold";
                }
            }
            else {                 //DEL
                if($ptiRecord['ibc_category'] == '91' || $ptiRecord['ibc_category'] == '90'|| $ptiRecord['ibc_category'] == '92'){
                    $this->Pti_model->del_submit($ptiRecord);
                    
                } else {
                    echo "on hold";
                }
            }
        }
        
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        else{
            $this->db->trans_commit();
            return true;
        }
        
	    } else {
	        echo "API not responding.";
	    }
    }
    
    
    function pti_submit(){
        $data['guids'] = $this->input->post('news');
        if($this->Pti_model->pti_submit($data)){
            echo json_encode(array('status'=>200));
        }
    }
    
}