<?php
/**
 * @Author Siddiqkhan
 *
 * @Termination Controller
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Termination extends MY_Controller {

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
		$this->load->model("Termination_model");
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
		$data['all_termination_types'] = $this->Termination_model->all_termination_types();
		$data['breadcrumbs'] = 'Terminations';
		$data['path_url'] = 'termination';
		if(in_array('23',role_resource_ids()) || in_array('23a',role_resource_ids()) || in_array('23e',role_resource_ids()) || in_array('23d',role_resource_ids()) || in_array('23v',role_resource_ids())) { 
			if(!empty($this->userSession)){
			$data['subview'] = $this->load->view("termination/termination_list", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
     }
 
    public function termination_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("termination/termination_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		
		$termination = $this->Termination_model->get_terminations();
		
		$data = array();

		foreach($termination->result() as $r) {

				// get user > warning to
				$user = $this->Xin_model->read_user_info($r->employee_id);
				// user full name
				$ful_name = change_fletter_caps($user[0]->first_name.' '.$user[0]->middle_name.' '.$user[0]->last_name);
				// get notice date
				$notice_date = $this->Xin_model->set_date_format($r->notice_date);
				// get termination date
				$termination_date = $this->Xin_model->set_date_format($r->termination_date);

				// get status
				if($r->status==0): $status = '<span class="label label-warning">Pending</span>';
				elseif($r->status==1): $status = '<span class="label label-success">Accepted</span>'; else: $status = '<span class="label label-danger">Rejected</span>'; endif;
				// get warning type
				$termination_type = $this->Termination_model->read_termination_type_information($r->termination_type_id);

					  $edit_perm='';
					  $view_perm='';
					  $delete_perm='';
					  if(in_array('23e',role_resource_ids())) {
						$edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit-modal-data"  data-termination_id="'. $r->termination_id . '"><i class="icon-pencil7"></i> Edit</a></li>';
					  }
					  if(in_array('23v',role_resource_ids())) {
						$view_perm='<li><a href="#" data-toggle="modal" data-target=".view-modal-data" data-termination_id="'. $r->termination_id . '"><i class="icon-eye4"></i> View</a></li>';
					  }
					  if(in_array('23d',role_resource_ids())) {
						$delete_perm='<li><a class="delete" href="#" data-record-id="'. $r->termination_id . '"><i class="icon-trash"></i> Delete</a></li>';
					  }


				$data[] = array(
					$ful_name,
					$termination_type[0]->type_name,
					$notice_date,
					$termination_date,
					$status,
					'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$view_perm.$delete_perm.'</ul></li></ul>',
				);
     	 }

		  $output = array(
			   "draw" => $draw,
				 "recordsTotal" => $termination->num_rows(),
				 "recordsFiltered" => $termination->num_rows(),
				 "data" => $data
			);
		  $this->output($output);
     }

    public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('termination_id');
		$result = $this->Termination_model->read_termination_information($id);
		$data = array(
				'termination_id' => $result[0]->termination_id,
				'employee_id' => $result[0]->employee_id,
				'terminated_by' => $result[0]->terminated_by,
				'termination_type_id' => $result[0]->termination_type_id,
				'termination_date' => format_date('d F Y',$result[0]->termination_date),
				'notice_date' => format_date('d F Y',$result[0]->notice_date),
				'description' => $result[0]->description,
				'status' => $result[0]->status,
				'all_employees' => $this->Xin_model->all_employees(),
				'all_termination_types' => $this->Termination_model->all_termination_types(),
				);
		if(!empty($this->userSession)){
			$this->load->view('termination/dialog_termination', $data);
		} else {
			redirect('');
		}
	}

	public function add_termination() {
	
		if($this->input->post('add_type')=='termination') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		$notice_date = $this->input->post('notice_date');
		$termination_date = $this->input->post('termination_date');
		$nt_date = strtotime($notice_date);
    	$tt_date = strtotime($termination_date);
		$description = $this->input->post('description');
		$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
		
		if($this->input->post('employee_id')==='') {
       		 $Return['error'] = "The employee field is required.";
		} else if($this->input->post('notice_date')==='') {
			$Return['error'] = "The notice date field is required.";
		} else if($this->input->post('termination_date')==='') {
			 $Return['error'] = "The termination date field is required.";
		} else if($nt_date > $tt_date) {
        	$Return['error'] = "Notice Date should be less than or equal to Termination Date.";
		} else if(empty($this->input->post('type'))) {
			 $Return['error'] = "The termination type field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'employee_id' => $this->input->post('employee_id'),
		'notice_date' => format_date('Y-m-d',$this->input->post('notice_date')),
		'description' => $qt_description,
		'termination_date' => format_date('Y-m-d',$this->input->post('termination_date')),
		'termination_type_id' => $this->input->post('type'),
		'terminated_by' => $this->input->post('user_id'),
		'status' => '0',
		'created_at' => date('d-m-Y'),
		);
		$result = $this->Termination_model->add($data);	
		/*User Logs*/
		$affected_id= table_max_id('xin_employee_terminations','termination_id');
		userlogs('Employees-Employee Termination-Add','Employee Termination Added',$affected_id['field_id'],$affected_id['datas']);
		/*User Logs*/
		
		if ($result == TRUE) {
			$Return['result'] = 'Termination added.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}

	public function update() {
	
		if($this->input->post('edit_type')=='termination') {
			
		$id = $this->uri->segment(3);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		$notice_date = $this->input->post('notice_date');
		$termination_date = $this->input->post('termination_date');
		$nt_date = strtotime($notice_date);
    	$tt_date = strtotime($termination_date);
		$description = $this->input->post('description');
		$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
		
		if($this->input->post('employee_id')==='') {
       		 $Return['error'] = "The employee field is required.";
		} else if($this->input->post('notice_date')==='') {
			$Return['error'] = "The notice date field is required.";
		} else if($this->input->post('termination_date')==='') {
			 $Return['error'] = "The termination date field is required.";
		} else if($nt_date > $tt_date) {
        	$Return['error'] = "Notice Date should be less than or equal to Termination Date.";
		} else if(empty($this->input->post('type'))) {
			 $Return['error'] = "The termination type field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'employee_id' => $this->input->post('employee_id'),
		'notice_date' => format_date('Y-m-d',$this->input->post('notice_date')),
		'description' => $qt_description,
		'termination_date' => format_date('Y-m-d',$this->input->post('termination_date')),
		'termination_type_id' => $this->input->post('type'),
		'terminated_by' => $this->input->post('user_id'),
		'status' => $this->input->post('status'),
		);
		
		$result = $this->Termination_model->update_record($data,$id);		
		/*User Logs*/
		$affected_id= table_update_id('xin_employee_terminations','termination_id',$id);
		userlogs('Employees-Employee Termination-Update','Employee Termination Updated',$id,$affected_id['datas']);
		/*User Logs*/
		if ($result == TRUE) {
			$Return['result'] = 'Termination updated.';
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
		$affected_row= table_deleted_row('xin_employee_terminations','termination_id',$id);
		userlogs('Employees-Employee Termination-Delete','Employee Termination Deleted',$id,$affected_row);		
		/*User Logs*/
		$this->Termination_model->delete_record($id);
		if(isset($id)) {
			$Return['result'] = 'Termination deleted.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
	}

}
