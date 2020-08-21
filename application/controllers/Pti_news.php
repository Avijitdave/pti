<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pti_news extends CI_Controller {

	function __construct(){
	   parent::__construct();
	}
	
	public function index(){
		$this->load->model('Pti_model');
		$data['header'] = $this->load->view('include/headercss','',true);
		$data['feeds'] = $this->Pti_model->news();
		$this->load->view('pti_news',$data);
	}	
	
	function archive($fromdate=null,$todate=null){
	    if(!is_null($fromdate)){
	        $fromdate = explode('-', $fromdate);
	        $fromdate = $fromdate[2].'-'.$fromdate[0].'-'.$fromdate[1];
	    }
	    
	    if(!is_null($todate)){
	        $todate = explode('-', $todate);
	        $todate = $todate[2].'-'.$todate[0].'-'.$todate[1];
	    }
	    
	    $this->load->model('Pti_model');
	    $data['header'] = $this->load->view('include/headercss','',true);
	    $data['feeds'] = $this->Pti_model->archive_news($fromdate,$todate);
	    $this->load->view('pti_news_archive',$data);
	}
}
