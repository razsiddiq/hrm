<?php
/**
 * @Author Siddiqkhan
 *
 * @Suspension Controller
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Suspension extends MY_Controller {
	
	 public function __construct() {
        Parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->database();
		$this->load->library('form_validation');
		//load the model
		$this->load->model("Suspension_model");
		$this->load->model("Xin_model");
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
		$data['all_termination_types'] = $this->Suspension_model->all_termination_types();
		$session = $this->session->userdata('username');
		$data['breadcrumbs'] = 'Suspension';
		$data['path_url'] = 'user/user_suspension';
		if(!empty($session)){ 
			$data['subview'] = $this->load->view("user/suspension_list", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
		} else {
			redirect('');
		}
		  
     }
 
    public function suspension_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("user/suspension_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
	
		$suspension = $this->Suspension_model->get_employee_suspension($session['user_id']);
		
		$data = array();

        foreach($suspension->result() as $r) {

		// get user > warning by
		$user_by = $this->Xin_model->read_user_info($r->suspended_by);
		// user full name
		$suspended_by = change_fletter_caps($user_by[0]->first_name.' '.$user_by[0]->middle_name.' '.$user_by[0]->last_name);
		// get warning date
		$suspension_start_date = $this->Xin_model->set_date_format($r->suspension_start_date);
		$suspension_end_date = $this->Xin_model->set_date_format($r->suspension_end_date);
				
		// get status
			if($r->status==0): $status = '<span class="label label-warning">Pending</span>';
		elseif($r->status==1): $status = '<span class="label label-success">Accepted</span>'; else: $status = '<span class="label label-danger">Rejected</span>'; endif;
		// get warning type
		$suspension_type = $this->Suspension_model->read_termination_type_information($r->suspension_type_id);
		// description
		$description =  html_entity_decode($r->description);
		
		$data[] = array(
		    $suspended_by,
			$suspension_type[0]->type_name,
			$suspension_start_date,
			$suspension_end_date,
			$status,
			'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right"><li><a href="#" data-toggle="modal" data-target=".view-modal-data" data-suspension_id="'. $r->suspension_id . '"><i class="icon-eye4"></i> View Details</a></li></ul></li></ul>',
			
			
		);
		
	
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $suspension->num_rows(),
			 "recordsFiltered" => $suspension->num_rows(),
			 "data" => $data
		);
	  echo json_encode($output);
	  exit();
     }
}
