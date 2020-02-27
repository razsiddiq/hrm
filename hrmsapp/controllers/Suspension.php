<?php
/**
 * @Author Siddiqkhan
 *
 * @Suspension Controller
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Suspension extends MY_Controller {

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
		$this->load->model("Suspension_model");
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
		$data['all_termination_types'] = $this->Suspension_model->all_termination_types();
		$data['breadcrumbs'] = 'Suspensions';
		$data['path_url'] = 'suspension';
		if(in_array('23',role_resource_ids()) || in_array('23a',role_resource_ids()) || in_array('23e',role_resource_ids()) || in_array('23d',role_resource_ids()) || in_array('23v',role_resource_ids())) { 
			if(!empty($this->userSession)){
			$data['subview'] = $this->load->view("suspension/suspension_list", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
     }

    public function suspension_list()
     {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("suspension/suspension_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$termination = $this->Suspension_model->get_suspension();
		$data = array();

		foreach($termination->result() as $r) {
			 			  
			// get user > warning to
			$user = $this->Xin_model->read_user_info($r->employee_id);
			// user full name
			$ful_name = change_fletter_caps($user[0]->first_name.' '.$user[0]->middle_name.' '.$user[0]->last_name);
			// get notice date
			$suspension_start_date = $this->Xin_model->set_date_format($r->suspension_start_date);
			// get termination date
			$suspension_end_date = $this->Xin_model->set_date_format($r->suspension_end_date);

			// get status
			if($r->status==0): $status = '<span class="label label-warning">Pending</span>';
			elseif($r->status==1): $status = '<span class="label label-success">Accepted</span>'; else: $status = '<span class="label label-danger">Rejected</span>'; endif;
			// get warning type
			$termination_type = $this->Suspension_model->read_termination_type_information($r->suspension_type_id);
			 $edit_perm='';
				  $view_perm='';
				  $delete_perm='';
				  if(in_array('23e',role_resource_ids())) {
					$edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit-modal-data"  data-suspension_id="'. $r->suspension_id . '"><i class="icon-pencil7"></i> Edit</a></li>';
				  }
				  if(in_array('23v',role_resource_ids())) {
					$view_perm='<li><a href="#" data-toggle="modal" data-target=".view-modal-data" data-suspension_id="'. $r->suspension_id . '"><i class="icon-eye4"></i> View Details</a></li>';
				  }
				  if(in_array('23d',role_resource_ids())) {
					$delete_perm='<li><a class="delete" href="#" data-record-id="'. $r->suspension_id . '"><i class="icon-trash"></i> Delete</a></li>';
				  }
			$data[] = array(
				$ful_name,
				$termination_type[0]->type_name,
				$suspension_start_date,
				$suspension_end_date,
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
		$id = $this->input->get('suspension_id');
		$result = $this->Suspension_model->read_termination_information($id);
		$data = array(
				'suspension_id' => $result[0]->suspension_id,
				'employee_id' => $result[0]->employee_id,
				'suspended_by' => $result[0]->suspended_by,
				'suspension_type_id' => $result[0]->suspension_type_id,
				'suspension_start_date' => format_date('d F Y',$result[0]->suspension_start_date),
				'suspension_end_date' => format_date('d F Y',$result[0]->suspension_end_date),
				'description' => $result[0]->description,
				'status' => $result[0]->status,
				'all_employees' => $this->Xin_model->all_employees(),
				'all_termination_types' => $this->Suspension_model->all_termination_types(),
				);
		if(!empty($this->userSession)){
			$this->load->view('suspension/dialog_suspension', $data);
		} else {
			redirect('');
		}
	}

	public function add_suspension() {
	
		if($this->input->post('add_type')=='suspension') {		
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');

			/* Server side PHP input validation */
			$suspension_start_date = $this->input->post('suspension_start_date');
			$suspension_end_date = $this->input->post('suspension_end_date');
			$st_date = strtotime($suspension_start_date);
			$et_date = strtotime($suspension_end_date);
			$description = $this->input->post('description');
			$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);

			if($this->input->post('employee_id')==='') {
				 $Return['error'] = "The employee field is required.";
			} else if($this->input->post('suspension_start_date')==='') {
				$Return['error'] = "The suspension start date field is required.";
			} else if($this->input->post('suspension_end_date')==='') {
				 $Return['error'] = "The suspension end date field is required.";
			} else if($st_date > $et_date) {
				$Return['error'] = "Suspension Start Date should be less than or equal to Suspension End Date.";
			} else if(empty($this->input->post('type'))) {
				 $Return['error'] = "The termination type field is required.";
			}

			if($Return['error']!=''){
				$this->output($Return);
			}

			$data = array(
			'employee_id' => $this->input->post('employee_id'),
			'suspension_start_date' => format_date('Y-m-d',$this->input->post('suspension_start_date')),
			'description' => $qt_description,
			'suspension_end_date' => format_date('Y-m-d',$this->input->post('suspension_end_date')),
			'suspension_type_id' => $this->input->post('type'),
			'suspended_by' => $this->input->post('user_id'),
			'status' => '0',
			'created_at' => date('d-m-Y'),
			);
			$result = $this->Suspension_model->add($data);
			/*User Logs*/
			$affected_id= table_max_id('xin_employee_suspension','suspension_id');
			userlogs('Employees-Employee Suspension-Add','Employee Suspension Added',$affected_id['field_id'],$affected_id['datas']);
			/*User Logs*/
			if ($result == TRUE) {
				$Return['result'] = 'Suspension added.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function update() {
	
		if($this->input->post('edit_type')=='suspension') {
			$id = $this->uri->segment(3);

			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');

			/* Server side PHP input validation */
			$suspension_start_date = $this->input->post('suspension_start_date');
			$suspension_end_date = $this->input->post('suspension_end_date');
			$st_date = strtotime($suspension_start_date);
			$et_date = strtotime($suspension_end_date);
			$description = $this->input->post('description');
			$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);

			if($this->input->post('employee_id')==='') {
				 $Return['error'] = "The employee field is required.";
			} else if($this->input->post('suspension_start_date')==='') {
				$Return['error'] = "The suspension start date field is required.";
			} else if($this->input->post('suspension_end_date')==='') {
				 $Return['error'] = "The suspension end date field is required.";
			} else if($st_date > $et_date) {
				$Return['error'] = "Suspension Start Date should be less than or equal to Suspension End Date.";
			} else if(empty($this->input->post('type'))) {
				 $Return['error'] = "The termination type field is required.";
			}

			if($Return['error']!=''){
				$this->output($Return);
			}

			$data = array(
			'employee_id' => $this->input->post('employee_id'),
			'suspension_start_date' => format_date('Y-m-d',$this->input->post('suspension_start_date')),
			'description' => $qt_description,
			'suspension_end_date' => format_date('Y-m-d',$this->input->post('suspension_end_date')),
			'suspension_type_id' => $this->input->post('type'),
			'suspended_by' => $this->input->post('user_id'),
			'status' => $this->input->post('status'),
			);


			$result = $this->Suspension_model->update_record($data,$id);
			/*User Logs*/
			$affected_id= table_update_id('xin_employee_suspension','suspension_id',$id);
			userlogs('Employees-Employee Suspension-Update','Employee Suspension Updated',$id,$affected_id['datas']);
			/*User Logs*/
			if ($result == TRUE) {
				$Return['result'] = 'Suspension updated.';
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
		$affected_row= table_deleted_row('xin_employee_suspension','suspension_id',$id);
		userlogs('Employees-Employee Suspension-Delete','Employee Suspension Deleted',$id,$affected_row);		
		/*User Logs*/
		$this->Suspension_model->delete_record($id);
		if(isset($id)) {
			$Return['result'] = 'Suspension deleted.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
	}

}
