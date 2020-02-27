<?php
/**
 * @Author Siddiqkhan
 *
 * @Travel Controller
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Travel extends MY_Controller {

	protected $userSession = null;
	
	public function __construct() {
    Parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->database();
		$this->load->library('form_validation');
		//load the model
		$this->load->model("Travel_model");
		$this->load->model("Xin_model");
		$this->userSession = $this->session->userdata('username');
	}

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
		$data['travel_arrangement_types'] = $this->Travel_model->travel_arrangement_types();
		$data['breadcrumbs'] = 'Travel';
		$data['path_url'] = 'travel';
		if(in_array('18',role_resource_ids()) || in_array('18a',role_resource_ids()) || in_array('18e',role_resource_ids()) || in_array('18d',role_resource_ids()) || in_array('18v',role_resource_ids())) { 
			if(!empty($this->userSession)){
			$data['subview'] = $this->load->view("travel/travel_list", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
  }
 
  public function travel_list()
  {

		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("travel/travel_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		
		$travel = $this->Travel_model->get_travel();
		
		$data = array();

		foreach($travel->result() as $r) {
								
			// get user > added by
			$user = $this->Xin_model->read_user_info($r->added_by);
			// user full name
			$full_name = change_fletter_caps($user[0]->first_name.' '.$user[0]->middle_name.' '.$user[0]->last_name);
			
			// get user > employee_
			$employee = $this->Xin_model->read_user_info($r->employee_id);
			// employee full name
			$employee_name = change_fletter_caps($employee[0]->first_name.' '.$employee[0]->middle_name.' '.$employee[0]->last_name);
			// get start date
			$start_date = $this->Xin_model->set_date_format($r->start_date);
			// get end date
			$end_date = $this->Xin_model->set_date_format($r->end_date);
			// status
			if($r->status==0): $status = '<span class="label label-warning">Pending</span>';
			elseif($r->status==1): $status = '<span class="label label-success">Accepted</span>'; else: $status = '<span class="label label-danger">Rejected</span>'; endif;
			
			$edit_perm='';
			$view_perm='';
			$delete_perm='';
			if(in_array('18e',role_resource_ids())) {
				$edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit-modal-data"  data-travel_id="'. $r->travel_id . '"><i class="icon-pencil7"></i> Edit</a></li>';
			}
			if(in_array('18v',role_resource_ids())) {
				$view_perm='<li><a href="#" data-toggle="modal" data-target=".view-modal-data" data-travel_id="'. $r->travel_id . '"><i class="icon-eye4"></i> View</a></li>';
			}
			if(in_array('18d',role_resource_ids())) {
				$delete_perm='<li><a class="delete" href="#" data-record-id="'. $r->travel_id . '"><i class="icon-trash"></i> Delete</a></li></ul></li>';
			}
			
			$data[] = array(
				$employee_name,
				$r->visit_purpose,
				$r->visit_place,
				$start_date,
				$end_date,
				$status,
				$full_name,
				'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$view_perm.$delete_perm.'</ul>',
			);
    }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $travel->num_rows(),
			 "recordsFiltered" => $travel->num_rows(),
			 "data" => $data
		);
	  $this->output($output);
  }

  public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('travel_id');
		$result = $this->Travel_model->read_travel_information($id);
		$data = array(
				'travel_id' => $result[0]->travel_id,
				'employee_id' => $result[0]->employee_id,
				'start_date' => format_date('d F Y',$result[0]->start_date),
				'end_date' => format_date('d F Y',$result[0]->end_date),
				'visit_purpose' => $result[0]->visit_purpose,
				'visit_place' => $result[0]->visit_place,
				'travel_mode' => $result[0]->travel_mode,
				'arrangement_type' => $result[0]->arrangement_type,
				'expected_budget' => $result[0]->expected_budget,
				'actual_budget' => $result[0]->actual_budget,
				'description' => $result[0]->description,
				'status' => $result[0]->status,
				'all_employees' => $this->Xin_model->all_employees(),
				'travel_arrangement_types' => $this->Travel_model->travel_arrangement_types(),
				);
		if(!empty($this->userSession)){
			$this->load->view('travel/dialog_travel', $data);
		} else {
			redirect('');
		}
	}

	public function add_travel() {	
		if($this->input->post('add_type')=='travel') {
		$Return = array('result'=>'', 'error'=>'');

		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');
		$description = $this->input->post('description');
		$st_date = strtotime($start_date);
		$ed_date = strtotime($end_date);
		$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
		
		if($this->input->post('employee_id')==='') {
        	$Return['error'] = "The employee field is required.";
		} else if($this->input->post('start_date')==='') {
			$Return['error'] = "The start date field is required.";
		} else if($this->input->post('end_date')==='') {
			$Return['error'] = "The end date field is required.";
		} else if($st_date > $ed_date) {
			$Return['error'] = "Start Date should be less than or equal to End Date.";
		} else if($this->input->post('visit_purpose')==='') {
			$Return['error'] = "The purpose of visit field is required.";
		} else if($this->input->post('visit_place')==='') {
			$Return['error'] = "The place of visit field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'employee_id' => $this->input->post('employee_id'),
		'start_date' => format_date('Y-m-d',$this->input->post('start_date')),
		'end_date' => format_date('Y-m-d',$this->input->post('end_date')),
		'visit_purpose' => $this->input->post('visit_purpose'),
		'visit_place' => $this->input->post('visit_place'),
		'travel_mode' => $this->input->post('travel_mode'),
		'arrangement_type' => $this->input->post('arrangement_type'),
		'expected_budget' => $this->input->post('expected_budget'),
		'actual_budget' => $this->input->post('actual_budget'),
		'description' => $qt_description,
		'added_by' => $this->input->post('user_id'),
		'created_at' => date('d-m-Y'),
		
		);
		$result = $this->Travel_model->add($data);
		/*User Logs*/
		$affected_id= table_max_id('xin_employee_travels','travel_id');
		userlogs('Employees-Employee Travel-Add','Employee Travel Added',$affected_id['field_id'],$affected_id['datas']);
		/*User Logs*/
		if ($result == TRUE) {
			$Return['result'] = 'Travel added.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}

	public function update() {	
		if($this->input->post('edit_type')=='travel') {			
		$id = $this->uri->segment(3);
		$Return = array('result'=>'', 'error'=>'');
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');
		$description = $this->input->post('description');
		$st_date = strtotime($start_date);
		$ed_date = strtotime($end_date);
		$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
		
		if($this->input->post('employee_id')==='') {
        	$Return['error'] = "The employee field is required.";
		} else if($this->input->post('start_date')==='') {
			$Return['error'] = "The start date field is required.";
		} else if($this->input->post('end_date')==='') {
			$Return['error'] = "The end date field is required.";
		} else if($st_date > $ed_date) {
			$Return['error'] = "Start Date should be less than or equal to End Date.";
		} else if($this->input->post('visit_purpose')==='') {
			$Return['error'] = "The purpose of visit field is required.";
		} else if($this->input->post('visit_place')==='') {
			$Return['error'] = "The place of visit field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'employee_id' => $this->input->post('employee_id'),
		'start_date' => format_date('Y-m-d',$this->input->post('start_date')),
		'end_date' => format_date('Y-m-d',$this->input->post('end_date')),
		'visit_purpose' => $this->input->post('visit_purpose'),
		'visit_place' => $this->input->post('visit_place'),
		'travel_mode' => $this->input->post('travel_mode'),
		'arrangement_type' => $this->input->post('arrangement_type'),
		'expected_budget' => $this->input->post('expected_budget'),
		'actual_budget' => $this->input->post('actual_budget'),
		'description' => $qt_description,
		'status' => $this->input->post('status'),		
		);
		
		$result = $this->Travel_model->update_record($data,$id);		
		/*User Logs*/
		$affected_id= table_update_id('xin_employee_travels','travel_id',$id);
		userlogs('Employees-Employee Travel-Update','Employee Travel Updated',$id,$affected_id['datas']);
		/*User Logs*/
		if ($result == TRUE) {
			$Return['result'] = 'Travel updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	public function delete() {
		$Return = array('result'=>'', 'error'=>'');
		$id = $this->uri->segment(3);
		/*User Logs*/
		$affected_row= table_deleted_row('xin_employee_travels','travel_id',$id);
		userlogs('Employees-Employee Travel-Delete','Employee Travel Deleted',$id,$affected_row);		
		/*User Logs*/
		$this->Travel_model->delete_record($id);
		if(isset($id)) {
			$Return['result'] = 'Travel deleted.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
	}

}
