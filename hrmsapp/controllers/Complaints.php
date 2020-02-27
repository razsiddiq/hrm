<?php
/**
 * @Author Siddiqkhan
 *
 * @Complaints Controller
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Complaints extends MY_Controller {

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
		$this->load->model("Complaints_model");
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
		$data['breadcrumbs'] = 'Complaints';
		$data['path_url'] = 'complaints';
		if(in_array('21',role_resource_ids()) || in_array('21a',role_resource_ids()) || in_array('21e',role_resource_ids()) || in_array('21d',role_resource_ids()) || in_array('21v',role_resource_ids())) { 
			if(!empty($this->userSession)){
			$data['subview'] = $this->load->view("complaints/complaint_list", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}	  
	}
 
    public function complaint_list()
	{
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("complaints/complaint_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		
		$complaint = $this->Complaints_model->get_complaints();
		
		$data = array();

		foreach($complaint->result() as $r) {

		$user = $this->Xin_model->read_user_info($r->complaint_from);
		$complaint_from = change_fletter_caps($user[0]->first_name.' '.$user[0]->middle_name.' '.$user[0]->last_name);
		$complaint_date = $this->Xin_model->set_date_format($r->complaint_date);
		
		if($r->complaint_against == '') {
			$ol = '--';
		} else {
			$ol = '<ol class="nl">';
			foreach(explode(',',$r->complaint_against) as $desig_id) {
				$_comp_name = $this->Xin_model->read_user_info($desig_id);
				$ol .= '<li>'.change_fletter_caps($_comp_name[0]->first_name.' '.$_comp_name[0]->middle_name.' '.$_comp_name[0]->last_name).'</li>';
			 }
			 $ol .= '</ol>';
		}

		if($r->status==0): $status = '<span class="label label-warning">Pending</span>';
		elseif($r->status==1): $status = '<span class="label label-success">Accepted</span>'; else: $status = '<span class="label label-danger">Rejected</span>'; endif;
		
		$edit_perm='';
		$view_perm='';
		$delete_perm='';
		if(in_array('21e',role_resource_ids())) {
			$edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit-modal-data"  data-complaint_id="'. $r->complaint_id . '"><i class="icon-pencil7"></i> Edit</a></li>';
		}
		if(in_array('21v',role_resource_ids())) {
			$view_perm='<li><a href="#" data-toggle="modal" data-target=".view-modal-data" data-complaint_id="'. $r->complaint_id . '"><i class="icon-eye4"></i> View</a></li>';
		}
		if(in_array('21d',role_resource_ids())) {
			$delete_perm='<li><a class="delete" href="#" data-record-id="'. $r->complaint_id . '"><i class="icon-trash"></i> Delete</a></li>';
		}
		
		$data[] = array(
			$complaint_from,
			$ol,
			$r->title,
			$complaint_date,
			$status,
			'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$view_perm.$delete_perm.'</ul></li></ul>',
		);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $complaint->num_rows(),
			 "recordsFiltered" => $complaint->num_rows(),
			 "data" => $data
		);
	  $this->output($output);
	}
	 
	public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('complaint_id');
		$result = $this->Complaints_model->read_complaint_information($id);
		$data = array(
				'complaint_id' => $result[0]->complaint_id,
				'complaint_from' => $result[0]->complaint_from,
				'title' => $result[0]->title,
				'complaint_date' => format_date('d F Y',$result[0]->complaint_date),
				'complaint_against' => $result[0]->complaint_against,
				'description' => $result[0]->description,
				'status' => $result[0]->status,
				'all_employees' => $this->Xin_model->all_employees(),
				);
		if(!empty($this->userSession)){
			$this->load->view('complaints/dialog_complaint', $data);
		} else {
			redirect('');
		}
	}

	public function add_complaint() {
	
		if($this->input->post('add_type')=='complaint') {
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		$description = $this->input->post('description');
		$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
		
		if($this->input->post('employee_id')==='') {
       		 $Return['error'] = "The complaint from field is required.";
		} else if($this->input->post('title')==='') {
			$Return['error'] = "The complaint title field is required.";
		} else if($this->input->post('complaint_date')==='') {
			 $Return['error'] = "The complaint date field is required.";
		} else if(empty($this->input->post('complaint_against'))) {
			 $Return['error'] = "The complaint against field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
		$complaint_against_ids = implode(',',$this->input->post('complaint_against'));
	
		$data = array(
		'complaint_from' => $this->input->post('employee_id'),
		'title' => $this->input->post('title'),
		'description' => $qt_description,
		'complaint_date' => format_date('Y-m-d',$this->input->post('complaint_date')),
		'complaint_against' => $complaint_against_ids,
		'created_at' => date('d-m-Y'),
		
		);
		$result = $this->Complaints_model->add($data);
		/*User Logs*/
		$affected_id= table_max_id('xin_employee_complaints','complaint_id');
		userlogs('Employees-Employee Complaint-Add','Employee Complaint Added',$affected_id['field_id'],$affected_id['datas']);
		/*User Logs*/
		if ($result == TRUE) {
			$Return['result'] = 'Complaint added.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}

	public function update() {
	
		if($this->input->post('edit_type')=='complaint') {
			
		$id = $this->uri->segment(3);

		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		$description = $this->input->post('description');
		$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
		
		if($this->input->post('employee_id')==='') {
       		 $Return['error'] = "The complaint from field is required.";
		} else if($this->input->post('title')==='') {
			$Return['error'] = "The complaint title field is required.";
		} else if($this->input->post('complaint_date')==='') {
			 $Return['error'] = "The complaint date field is required.";
		} else if(empty($this->input->post('complaint_against'))) {
			 $Return['error'] = "The complaint against field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
		$complaint_against_ids = implode(',',$this->input->post('complaint_against'));
	
		$data = array(
		'complaint_from' => $this->input->post('employee_id'),
		'title' => $this->input->post('title'),
		'description' => $qt_description,
		'complaint_date' => format_date('Y-m-d',$this->input->post('complaint_date')),
		'complaint_against' => $complaint_against_ids,
		'status' => $this->input->post('status'),
		);
		
		$result = $this->Complaints_model->update_record($data,$id);		
		/*User Logs*/
		$affected_id= table_update_id('xin_employee_complaints','complaint_id',$id);
		userlogs('Employees-Employee Complaint-Update','Employee Complaint Updated',$id,$affected_id['datas']);
		/*User Logs*/
		if ($result == TRUE) {
			$Return['result'] = 'Complaint updated.';
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
		$affected_row= table_deleted_row('xin_employee_complaints','complaint_id',$id);
		userlogs('Employees-Employee Complaint-Delete','Employee Complaint Deleted',$id,$affected_row);		
		/*User Logs*/
		$this->Complaints_model->delete_record($id);
		if(isset($id)) {
			$Return['result'] = 'Complaint deleted.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
	}

}
