<?php
/**
 * @Author Siddiqkhan
 *
 * @OfficeShift Controller
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Office_shift extends MY_Controller {
	
	public $userSession = null;

	public function __construct() {
        Parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->database();
		$this->load->library('form_validation');
		//load the model
		$this->load->model("Employees_model");
		$this->load->model("Xin_model");
		$this->userSession = $this->session->userdata('username');
	}
	
	/*Function to set JSON output*/
	public function output($Return=array()){
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}
	
	public function index()
    {
		$data['title'] = $this->Xin_model->site_title();
		$data['all_employees'] = $this->Xin_model->all_employees();
		$data['breadcrumbs'] = 'Office Shift';
		$data['path_url'] = 'user/user_office_shift';
		if(!empty($this->userSession)){
			$data['subview'] = $this->load->view("user/office_shift", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
		} else {
			redirect('');
		}		  
    }
 
    public function office_shift_list()
    {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){ 
			$this->load->view("user/office_shift", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));	
		
		$user = $this->Xin_model->get_employee_officeshift($this->userSession['user_id']);
		
		$data = array();

        foreach($user->result() as $r) {			 			  
			// get from date
			$from_date = $this->Xin_model->set_date_format($r->from_date);
			// get to date
			$to_date = $this->Xin_model->set_date_format($r->to_date);
			//shift date
			$shift_date = $from_date . ' to '.$to_date;
			// status info
			$shift = $this->Employees_model->read_shift_information($r->shift_id);
			
			$data[] = array(
				$shift[0]->shift_name,
				$shift_date
			);
      	}

	  	$output = array(
		   	 "draw" => $draw,
			 "recordsTotal" => $user->num_rows(),
			 "recordsFiltered" => $user->num_rows(),
			 "data" => $data
		);
	  echo json_encode($output);
	  exit();
	}
	
}
