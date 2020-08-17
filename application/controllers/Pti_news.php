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
}
