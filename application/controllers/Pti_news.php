<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pti_news extends CI_Controller {

	function __construct(){
	parent::__construct();
	//if($this->session->userdata('logged_in') !== TRUE){
	//   $this->load->view('backend/login');
	//}
	//$this->load->model('login_model');
	//$this->load->library('form_validation');

	}
    /*public function testInput($text) 
	{
	$data = trim($text);
	$data = addslashes($text);
	$data = htmlspecialchars($text);
	return $text;
	}*/
	
	public function index()
	{
		//$data['badge_records'] = $this->login_model->get_badge_records();
		$this->load->model('Pti_model');
		$data['feeds'] = $this->Pti_model->news();
		$this->load->view('pti_news',$data);
	}
	/*public function add_badgedata($badge_id = NULL)
	{
	$login_id = $this->session->userdata('userid');
    $username = $this->session->userdata('username');  	 
	//validate the form data 
    $this->form_validation->set_rules('badges_tittle', 'Badge Tittle', 'required');

	
	$this->form_validation->set_rules('badges_remark', 'Badge Remark', 'required');
	if (empty($_FILES['image']['name']))
	{
	$this->form_validation->set_rules('image', 'Image', 'required');
	}
    if($badge_id!=0){
		$editdata['badge_records'] = $this->login_model->get_badge_records();
		$editdata['edit_badgedata']= $this->login_model->edit_badgedata($badge_id);
        $this->load->view('backend/adminpage/badge',$editdata);	
		
		} 
	//if ($this->form_validation->run() == FALSE){
		//$this->load->view('backend/adminpage/register');
	//}else{
		
	if(isset($_POST['save'])){
		//get the form values		
		$data['badge_id'] = $this->testInput($this->input->post('badge_id', TRUE));
		$data['badge_tittle'] = $this->testInput($this->input->post('badges_tittle', TRUE));
		$data['badge_remark'] = $this->testInput($this->input->post('badges_remark', TRUE));
		
		$data['status'] = $this->input->post('status', TRUE);
		$data['createdby'] = $login_id;
		$data['crdate'] = date("Y-m-d");
		$create_date = date("Y-m-d H:i:s");
	
		//file upload code 
		//set file upload settings 
		$config['upload_path']          = APPPATH. '../assets/uploads/';
		$config['allowed_types']        = 'jpg|png';
		$config['max_size']             = 1000;

		$this->load->library('upload', $config);
		$this->upload->do_upload('image');
		//if ( !){
		//	$error = array('error' => $this->upload->display_errors());
		//	$this->load->view('backend/adminpage/register',$error);
		//}else{
			//file is uploaded successfully
			//now get the file uploaded data 
			$upload_data = $this->upload->data();
			
			//get the uploaded file name
			$data['image'] = $upload_data['file_name'];
			
		//}
		if ($this->form_validation->run() == FALSE){
	    $editdata['badge_records'] = $this->login_model->get_badge_records();
		$this->load->view('backend/adminpage/badge',$editdata);
		}
        else if($data['badge_id']==0){		
			//store pic data to the db
			
	     $this->login_model->add_badgedata($data);
		 $page="Badges";
         $remark="Badges Record Added";	 
         $this->login_model->activitylog($login_id,$page,$remark,$create_date);		 
	    
	  }else{
         $this->login_model->update_badgedata($data);
         if($data['image'] != "" && $data['image'] != "0" )
		{	
	      $unlink_image['unlink_badgeimage']= $this->login_model->unlink_badgeimage($data['badge_id']); 
	      $this->login_model->update_badgeimage($data);
		  $path = APPPATH.'../assets/uploads/'.$unlink_image['unlink_badgeimage'][0]->Badges_Img;
		  if(is_readable($path))
		{
		   unlink($path); 
		 }
		} 
		$page="Badges";
        $remark="Badges Record Updated";
		$this->login_model->activitylog($login_id,$page,$remark,$create_date);
		$messge = array('message' => 'Data Updated successfully','class' => 'alert alert-success fade in');
        $this->session->set_flashdata('item', $messge);
		redirect('badge','refresh');
	  }
	}
	}*/
	/*public function delete_badgedata($badge_id = NULL )
	{
	$login_id = $this->session->userdata('userid');
	  if (empty($badge_id))
		{
			show_404();
		}
				
		
		$unlink_image['unlink_badgeimage']= $this->login_model->unlink_badgeimage($badge_id);
		$path = APPPATH.'../assets/uploads/'.$unlink_image['unlink_badgeimage'][0]->Badges_Img;
		if(file_exists($path) && is_readable($path))
		{
		   unlink($path); 
		 }		
		$this->login_model->delete_badgedata($badge_id);    
		$page="Badges";
		$remark="Badges Record Deleted";
		$create_date = date("Y-m-d H:i:s");	
		$this->login_model->activitylog($login_id,$page,$remark,$create_date);		
		$messge = array('message' => 'Data Deleted successfully','class' => 'alert alert-success fade in');
		$this->session->set_flashdata('item', $messge);
		redirect('badge','refresh');  
	  
	}*/
	
}
