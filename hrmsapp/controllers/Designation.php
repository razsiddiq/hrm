<?php
/**
 * @Author Siddiqkhan
 *
 * @Designation Controller
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Designation extends MY_Controller {

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
		$this->load->model("Designation_model");
		$this->load->model("Xin_model");
		$this->load->model("Department_model");
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
		$data['all_departments'] = $this->Department_model->all_departments();
		$data['breadcrumbs'] = $this->lang->line('xin_designations');
		$data['path_url'] = 'designation';
		if(in_array('6',role_resource_ids()) || in_array('6a',role_resource_ids()) || in_array('6e',role_resource_ids()) || in_array('6d',role_resource_ids()) || in_array('6v',role_resource_ids())) {
			if(!empty($this->userSession)){
			$data['subview'] = $this->load->view("designation/designation_list", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}		  
     }
 
    public function designation_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("designation/designation_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		
		$designation = $this->Designation_model->get_designations();
		
		$data = array();

		foreach($designation->result() as $r) {
			  

			  $edit_perm='';
			  $delete_perm='';			  
		      if(in_array('3e',role_resource_ids())) {
				$edit_perm='<li><a href="#" data-toggle="modal" data-target="#edit-modal-data-md"  data-designation_id="'. $r->designation_id . '"><i class="icon-pencil7"></i> Edit</a></li>';  
			  }			
			  if(in_array('3d',role_resource_ids())) {
				$delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->designation_id . '"><i class="icon-trash"></i> Delete</a></li>';  
			  }
			  
			  
			  
			  $data[] = array(
                    $r->designation_name,
					'<ul class="icons-list">
											<li class="dropdown">
												<a href="#" class="dropdown-toggle" data-toggle="dropdown">
													<i class="icon-menu9"></i>
												</a>
												<ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$delete_perm.'</ul>
											</li>
				</ul>',
			  );
		}

		$output = array(
               "draw" => $draw,
                 "recordsTotal" => $designation->num_rows(),
                 "recordsFiltered" => $designation->num_rows(),
                 "data" => $data
		);
		$this->output($output);
     }

    public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('designation_id');
		$result = $this->Designation_model->read_designation_information($id);
		$data = array(
				'designation_id' => $result[0]->designation_id,
				'department_id' => $result[0]->department_id,
				'designation_name' => $result[0]->designation_name,
				'all_departments' => $this->Department_model->all_departments()
				);
		if(!empty($this->userSession)){
			$this->load->view('designation/dialog_designation', $data);
		} else {
			redirect('');
		}
	}

	public function add_designation() {
	
		if($this->input->post('add_type')=='designation') {
			// Check validation for user input
			$this->form_validation->set_rules('department_id', 'Department', 'trim|required|xss_clean');
			$this->form_validation->set_rules('designation_name', 'Designation', 'trim|required|xss_clean');
			$check_unique_names = $this->Xin_model->check_unique_names('xin_designations','designation_name','designation_id',$this->input->post('designation_name'),'');
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');

			/* Server side PHP input validation */
			if($this->input->post('department_id')==='') {
				$Return['error'] = $this->lang->line('error_department_field');
			} else if($this->input->post('designation_name')==='') {
				$Return['error'] = $this->lang->line('error_designation_field');
			} else if($check_unique_names!=0){
				$Return['error'] = 'The designation name already exist.Enter different one.';
			}

			if($Return['error']!=''){
				$this->output($Return);
			}

			$data = array(
			'department_id' => $this->input->post('department_id'),
			'designation_name' => $this->input->post('designation_name'),
			'added_by' => $this->input->post('user_id'),
			'created_at' => date('d-m-Y'),

			);
			$result = $this->Designation_model->add($data);
			/*User Logs*/
			$affected_id= table_max_id('xin_designations','designation_id');
			userlogs('Organization-Designation-Add','New Designation Added',$affected_id['field_id'],$affected_id['datas']);
			/*User Logs*/

			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_success_add_designation');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	public function update() {
	
		if($this->input->post('edit_type')=='designation') {

			$id = $this->uri->segment(3);

			// Check validation for user input
			$this->form_validation->set_rules('department_id', 'Department', 'trim|required|xss_clean');
			$this->form_validation->set_rules('designation_name', 'Designation', 'trim|required|xss_clean');
			$check_unique_names = $this->Xin_model->check_unique_names('xin_designations','designation_name','designation_id',$this->input->post('designation_name'),$id);
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');

			/* Server side PHP input validation */
			if($this->input->post('department_id')==='') {
				$Return['error'] = $this->lang->line('error_department_field');
			} else if($this->input->post('designation_name')==='') {
				$Return['error'] = $this->lang->line('error_designation_field');
			}  else if($check_unique_names!=0){
				$Return['error'] = 'The designation name already exist.Enter different one.';
			}

			if($Return['error']!=''){
				$this->output($Return);
			}

			$data = array(
			'department_id' => $this->input->post('department_id'),
			'designation_name' => $this->input->post('designation_name'),
			);

			$result = $this->Designation_model->update_record($data,$id);
			/*User Logs*/
			$affected_id= table_update_id('xin_designations','designation_id',$id);
			userlogs('Organization-Designation-Update','Designation Updated',$id,$affected_id['datas']);
			/*User Logs*/
			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_success_update_designation');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}
	
	public function delete() {
		$Return = array('result'=>'', 'error'=>'');
		$id = $this->uri->segment(3);	
		/*User Logs*/
		$affected_row= table_deleted_row('xin_designations','designation_id',$id);		
		userlogs('Organization-Designation-Delete','Designation Deleted',$id,$affected_row);
		/*User Logs*/
		$this->Designation_model->delete_record($id);
		if(isset($id)) {
			$Return['result'] = $this->lang->line('xin_success_delete_designation');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
	}

}
