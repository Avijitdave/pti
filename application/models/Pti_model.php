<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pti_model extends CI_Model {
    
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
	        $temp['ibc_category'] = '93';
	    }
	    else {
			$city = trim(trim($city,'.'));
	        
	        $result = $this->db->query("select id,state_short_code from ibc_cities where city_name_english = '$city'")->result_array();   
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
	                $temp['ibc_category'] = '91';
	                $temp['state_id'] = '38';
					$temp['state'] = 'उत्तर प्रदेश';
					$temp['city_id'] = $result[0]['id'];
	            }
	            else if($result[0]['state_short_code'] == 'MH'){
	                $temp['ibc_category'] = '92';
	                $temp['state_id'] = '22';
					$temp['state'] = 'महाराष्ट्र';
					$temp['city_id'] = $result[0]['id'];
	            }
	            else if($result[0]['state_short_code'] == 'BR'){
	                $temp['ibc_category'] = '90';
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
				$temp['categories'] = 'DEL';
			}
	    }
	    return $temp;
	}
	
	
	
	function news(){
	    $this->db->select('inp.*,ic.category_name_english,ici.city_name_english,is.state_name_english');
	    $this->db->order_by('inp.published','desc');
	    $this->db->join('ibc_categories ic','ic.id = inp.ibc_category','left');
	    $this->db->join('ibc_cities ici','ici.id = inp.city_id','left');
	    $this->db->join('ibc_states is','is.id = inp.state_id','left');
	    $data['feeds'] = $this->db->get_where('ibc_news_pti inp',array('date(inp.published)'=>'2020-08-19','inp.status'=>1))->result_array();
	    return $data['feeds'];
	}
	
	function archive_news($fromdate=null,$todate=null){
	    $this->db->select('inp.*,ic.category_name_english,ici.city_name_english,is.state_name_english');
	    $this->db->order_by('inp.published','desc');
	    $this->db->join('ibc_categories ic','ic.id = inp.ibc_category','left');
	    $this->db->join('ibc_cities ici','ici.id = inp.city_id','left');
	    $this->db->join('ibc_states is','is.id = inp.state_id','left');
	    $this->db->order_by('inp.published,inp.id','desc');
	    if(!is_null($fromdate)){
	       $this->db->where('date(inp.published)>=',$fromdate);
	    }
	    if(!is_null($todate)){
	        $this->db->where('date(inp.published)<=',$todate);
	    }
	    $data['feeds'] = $this->db->get('ibc_news_pti inp')->result_array();
	    return $data['feeds'];
	}
	
	function pti_submit($data){
	    foreach($data['guids'] as $feed){
	        
	        $this->db->select('*');
	        $ptiRecord = $this->db->get_where('ibc_news_pti',array('guid'=>$feed))->result_array();
	        
	        if(count($ptiRecord)>0){
	            $ptiRecord = $ptiRecord[0];
	            
	            if($ptiRecord['categories'] == 'DEL'){
	                $this->del_submit($ptiRecord);
	            } else {
	                $this->prd_submit($ptiRecord);
	            }
	        }
	    }
	    return true;
	}
	
	
	function del_submit($ptiRecord){
	    $db2 = $this->load->database('main', TRUE);
	    $this->db->trans_begin();
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
    	    $temp['state_id'] = $ptiRecord['state_id'];
    	    $temp['state'] = $ptiRecord['state'];
    	    $temp['city_id'] = $ptiRecord['city_id'];;
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
//     	    $temp['1000X563'] = '';
//     	    $temp['1000X752'] = '';
//     	    $temp['500X500'] = '';
    	    
    	    //insert record on table ibc_news
    	    $db2->insert('ibc_news',$temp);
    	    
    	    ///update slug inserted record
    	    $insertId = $db2->insert_id();
    	    $db2->where('id',$insertId);
    	    $db2->update('ibc_news',array('slug'=>$ptiRecord['slug_eng'].'-'.$insertId));
    	    
    	    //insert record on table ibc_news_categories
    	    $db2->insert('ibc_news_categories',array('news_id'=>$insertId,'category_id'=>3,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')));
    	    $db2->insert('ibc_news_categories',array('news_id'=>$insertId,'category_id'=>$ptiRecord['ibc_category'],'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')));
    	    
    	    $db2->insert('ibc_news_types_mapping',array('news_id'=>$insertId,'news_type_id'=>'9','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')));
    	    
    	    $categoryId = '4';  //DEL
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
	    
	    if ($this->db->trans_status() === FALSE){
	        $this->db->trans_rollback();
	        return false;
	    }
	    else{
	        $this->db->trans_commit();
	        return true;
	    }
	}
	
	function prd_submit($ptiRecord){
	    $db2 = $this->load->database('main', TRUE);
	    $this->db->trans_begin();
	    
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
    	    $temp['state_id'] = $ptiRecord['state_id'];
    	    $temp['state'] = $ptiRecord['state'];
    	    $temp['city_id'] = $ptiRecord['city_id'];;
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
    	    $db2->insert('ibc_news_categories',array('news_id'=>$insertId,'category_id'=>2,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')));
    	    $db2->insert('ibc_news_categories',array('news_id'=>$insertId,'category_id'=>$ptiRecord['ibc_category'],'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')));
    	    
    	    /// ibc news media
    	    $db2->insert('ibc_news_types_mapping',array('news_id'=>$insertId,'news_type_id'=>'9','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')));
    	    
    	    
    	    if($ptiRecord['ibc_category'] == '90'){
    	        $categoryId = '5';  //BR
    	    } else if($ptiRecord['ibc_category'] == '91'){
    	        $categoryId = '6';  //UP
    	    } else if($ptiRecord['ibc_category'] == '92'){
    	        $categoryId = '7';  //MH
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
	    
	    if ($this->db->trans_status() === FALSE){
	        $this->db->trans_rollback();
	        return false;
	    }
	    else{
	        $this->db->trans_commit();
	        return true;
	    }
	    
	}
	
	
	
}
