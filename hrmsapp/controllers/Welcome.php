<?php
/**
 * @Author Siddiqkhan
 *
 * @Welcome Controller
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public $userSession = null;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url_helper');
		$this->load->model('Employees_model');
		$this->load->model('Xin_model');
		$this->load->library('session');
		$this->userSession = $this->session->userdata('username');
	}

	public function index()
	{	
		$data['title'] = APPLICATION_NAME;		
		$uri_data=base64_decode($this->uri->segment(1));	
		$string_check ="MAILREDIRECT-";
		if( strpos( $uri_data, $string_check ) !== false) {
			$uri=str_replace($string_check,'',$uri_data);
		}else{
			$uri='';
		}		
		$data['uri']=$uri;
		if($this->userSession['user_id']!=''){	         
		if($uri==''){
		redirect('/dashboard');
		}else{		
		redirect($uri);
		}
		}
		$this->load->view('login', $data);
	}

}