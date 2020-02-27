<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url_helper');		 
	}
	
	public function index()
	{	$session = $this->session->userdata('username');
		if(!empty($session)){ 
			
		} else {
			redirect('');
		}
	    $data['title'] = 'Alifca DMCC - Error Page';
		$this->load->view('errors/html/error_404', $data);
	}
}
