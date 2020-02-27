<?php
/**
 * @Author Siddiqkhan
 *
 * @Department Controller
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Department extends MY_Controller {

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
		$this->load->model("Department_model");
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
		$data['all_locations'] = $this->Xin_model->all_locations();
		$data['all_employees'] = $this->Xin_model->all_employees_dept_head();
		$data['breadcrumbs'] = $this->lang->line('xin_departments');
		$data['path_url'] = 'department';
		 if(in_array('5',role_resource_ids()) || in_array('5a',role_resource_ids()) || in_array('5e',role_resource_ids()) || in_array('5d',role_resource_ids()) || in_array('5v',role_resource_ids())) {
			if(!empty($this->userSession)){
				$data['subview'] = $this->load->view("department/department_list", $data, TRUE);
				$this->load->view('layout_main', $data); //page load
			}
			else {
				redirect('');
			}
		}
		else {
			redirect('dashboard/');
		}
     }

    public function department_list()
     {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("department/department_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));

		$department = $this->Department_model->get_departments();

		$data = array();

		foreach($department->result() as $r) {

			   // get user > head
			  $head_user = $this->Xin_model->location_read_user_info($r->employee_id);
			  // user full name
			  $dep_head = change_fletter_caps($head_user[0]->first_name.' '.$head_user[0]->middle_name.' '.$head_user[0]->last_name);



			  $str_loc=[];
			  if($r->location_id!=''){
				$location_id=explode(',',$r->location_id);
				foreach($location_id as $loc_id){
				$loc_id = $this->Xin_model->read_location_info($loc_id);

				$str_loc[]= '<span class="label bg-teal-400 mr-5 mt-5">'.$loc_id[0]->location_name.'</span>';
				}
				$str_loc=implode('',$str_loc);
			  }

			  $edit_perm='';
			  $delete_perm='';
		      if(in_array('3e',role_resource_ids())) {
				$edit_perm='<li><a href="#" data-toggle="modal" data-target="#edit-modal-data-md"  data-department_id="'. $r->department_id . '"><i class="icon-pencil7"></i> Edit</a></li>	';
			  }

			  if(in_array('3d',role_resource_ids())) {
				$delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->department_id . '"><i class="icon-trash"></i> Delete</a></li>';
			  }

			  $data[] = array(
                    '<span data-toggle="tooltip" data-placement="right" title="'.$r->department_detail.'">'.$r->department_name.'</span>',
                    $dep_head,
                    $str_loc,
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
		   "recordsTotal" => $department->num_rows(),
		   "recordsFiltered" => $department->num_rows(),
		   "data" => $data
		);
	  	$this->output($output);
     }

	public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('department_id');
		$result = $this->Department_model->read_department_information($id);
		$data = array(
				'department_id' => $result[0]->department_id,
				'department_name' => $result[0]->department_name,
				'department_detail' => $result[0]->department_detail,
				'location_id' => $result[0]->location_id,
				'employee_id' => $result[0]->employee_id,
				'all_locations' => $this->Xin_model->all_locations(),
				'all_employees' => $this->Xin_model->all_employees()
				);
		$session = $this->session->userdata('username');
		if(!empty($session)){
			$this->load->view('department/dialog_department', $data);
		} else {
			redirect('');
		}
	}

	public function add_department() {

		if($this->input->post('add_type')=='department') {
		// Check validation for user input
		$this->form_validation->set_rules('department_name', 'Department Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('department_detail', 'Department Detail', 'trim|required|xss_clean');
		$this->form_validation->set_rules('location_id', 'Location', 'trim|required|xss_clean');
		$this->form_validation->set_rules('employee_id', 'Employee', 'trim|required|xss_clean');

		$check_unique_names = $this->Xin_model->check_unique_names('xin_departments','department_name','department_id',$this->input->post('department_name'),'');
	    $check_unique_names1 = $this->Xin_model->check_unique_names('xin_departments','department_detail','department_id',$this->input->post('department_detail'),'');
	    /* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');

		/* Server side PHP input validation */
		if($this->input->post('department_name')==='') {
        	$Return['error'] = $this->lang->line('error_department_field');
		} else if($check_unique_names!=0){
            $Return['error'] = 'The department code already exist.Enter different one.';
        } else if($this->input->post('department_detail')==='') {
        	$Return['error'] = $this->lang->line('error_department_detail_field');
		} else if($check_unique_names1!=0){
            $Return['error'] = 'The department name already exist.Enter different one.';
        } else if(implode(',',$this->input->post('location_id'))=='') {
			$Return['error'] = $this->lang->line('error_location_dept_field');
		} else if($this->input->post('employee_id')==='') {
			$Return['error'] = $this->lang->line('error_department_head_field');
		}

		if($Return['error']!=''){
       		$this->output($Return);
    	}

		$data = array(
		'department_name' => $this->input->post('department_name'),
		'department_detail' => $this->input->post('department_detail'),
		'location_id' => implode(',',$this->input->post('location_id')),
		'employee_id' => $this->input->post('employee_id'),
		'added_by' => $this->input->post('user_id'),
		'created_at' => date('d-m-Y'),

		);
		$result = $this->Department_model->add($data);
		/*User Logs*/
		$affected_id= table_max_id('xin_departments','department_id');
		userlogs('Organization-Department-Add','New Department Added',$affected_id['field_id'],$affected_id['datas']);
		/*User Logs*/

		if ($result == TRUE) {
			$Return['result'] = $this->lang->line('xin_success_add_department');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;
		}
	}

	public function update() {

		if($this->input->post('edit_type')=='department') {

		$id = $this->uri->segment(3);

		// Check validation for user input
		$this->form_validation->set_rules('department_name', 'Department Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('department_detail', 'Department Detail', 'trim|required|xss_clean');
		$this->form_validation->set_rules('location_id', 'Location', 'trim|required|xss_clean');
		$this->form_validation->set_rules('employee_id', 'Employee', 'trim|required|xss_clean');

		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		$check_unique_names = $this->Xin_model->check_unique_names('xin_departments','department_name','department_id',$this->input->post('department_name'),$id);
	    $check_unique_names1 = $this->Xin_model->check_unique_names('xin_departments','department_detail','department_id',$this->input->post('department_detail'),$id);
		/* Server side PHP input validation */
		if($this->input->post('department_name')==='') {
        	$Return['error'] = $this->lang->line('error_department_field');
		} else if($check_unique_names!=0){
            $Return['error'] = 'The department code already exist.Enter different one.';
        } else if($this->input->post('department_detail')==='') {
        	$Return['error'] = $this->lang->line('error_department_detail_field');
		} else if($check_unique_names1!=0){
            $Return['error'] = 'The department name already exist.Enter different one.';
        } else if($this->input->post('location_id')==='') {
			$Return['error'] = $this->lang->line('error_location_dept_field');
		} else if($this->input->post('employee_id')==='') {
			$Return['error'] = $this->lang->line('error_department_head_field');
		}

		if($Return['error']!=''){
       		$this->output($Return);
    	}

		$data = array(
		'department_name' => $this->input->post('department_name'),
		'department_detail' => $this->input->post('department_detail'),
		'location_id' => implode(',',$this->input->post('location_id')),
		'employee_id' => $this->input->post('employee_id'),
		);

		$result = $this->Department_model->update_record($data,$id);

		/*User Logs*/
		$affected_id= table_update_id('xin_departments','department_id',$id);
		userlogs('Organization-Department-Update','Department Updated',$id,$affected_id['datas']);
		/*User Logs*/


		if ($result == TRUE) {
			$Return['result'] = $this->lang->line('xin_success_update_department');
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
		$affected_row= table_deleted_row('xin_departments','department_id',$id);
		userlogs('Organization-Department-Delete','Department Deleted',$id,$affected_row);
		/*User Logs*/

		$this->Department_model->delete_record($id);
		if(isset($id)) {
			$Return['result'] = $this->lang->line('xin_success_delete_department');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
	}

}
