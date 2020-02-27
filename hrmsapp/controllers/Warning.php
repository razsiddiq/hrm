<?php
/**
 * @Author Siddiqkhan
 *
 * @Warning Controller
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Warning extends MY_Controller {

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
		$this->load->model("Warning_model");
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
		$data['all_warning_types'] = $this->Warning_model->all_warning_types();
		$data['breadcrumbs'] = 'Warnings';
		$data['path_url'] = 'warning';
		if(in_array('22',role_resource_ids()) || in_array('22a',role_resource_ids()) || in_array('22e',role_resource_ids()) || in_array('22d',role_resource_ids()) || in_array('22v',role_resource_ids())) {
			if(!empty($this->userSession)){
			$data['subview'] = $this->load->view("warning/warning_list", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
  }
 
  public function warning_list()
  {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){ 
			$this->load->view("warning/warning_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));		
		$warning = $this->Warning_model->get_warning();		
		$data = array();
    foreach($warning->result() as $r) {			 			  
			// get user > warning to
			$user = $this->Xin_model->read_user_info($r->warning_to);
			// user full name
			$warning_to = change_fletter_caps($user[0]->first_name.' '.$user[0]->middle_name.' '.$user[0]->last_name);
			// get user > warning by
			$user_by = $this->Xin_model->read_user_info($r->warning_by);
			// user full name
			$warning_by = change_fletter_caps($user_by[0]->first_name.' '.$user_by[0]->middle_name.' '.$user_by[0]->last_name);
			// get warning date
			$warning_date = $this->Xin_model->set_date_format($r->warning_date);

			// get status
			if($r->status==0): $status = '<span class="label label-warning">Pending</span>';
			elseif($r->status==1): $status = '<span class="label label-success">Accepted</span>'; else: $status = '<span class="label label-danger">Rejected</span>'; endif;
			// get warning type
			$warning_type = $this->Warning_model->read_warning_type_information($r->warning_type_id);

			$edit_perm='';
			$view_perm='';
			$delete_perm='';
			if(in_array('22e',role_resource_ids())) {
			$edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit-modal-data"  data-warning_id="'. $r->warning_id . '"><i class="icon-pencil7"></i> Edit</a></li>';
			}
			if(in_array('22v',role_resource_ids())) {
				$view_perm='<li><a href="#" data-toggle="modal" data-target=".view-modal-data" data-warning_id="'. $r->warning_id . '"><i class="icon-eye4"></i> View</a></li>';
			}
			if(in_array('22d',role_resource_ids())) {
			$delete_perm='<li><a class="delete" href="#" data-record-id="'. $r->warning_id . '"><i class="icon-trash"></i> Delete</a></li>';
			}
			$data[] = array(
				$warning_to,
				$warning_date,
				$r->subject,
				$warning_type[0]->type_name,
				$status,
				$warning_by,
				'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$view_perm.$delete_perm.'</ul></li></ul>',
			);
    }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $warning->num_rows(),
			 "recordsFiltered" => $warning->num_rows(),
			 "data" => $data
		);
	  $this->output($output);
  }

  public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('warning_id');
		$result = $this->Warning_model->read_warning_information($id);
		$data = array(
				'warning_id' => $result[0]->warning_id,
				'warning_to' => $result[0]->warning_to,
				'warning_by' => $result[0]->warning_by,
				'warning_date' => format_date('d F Y',$result[0]->warning_date),
				'warning_type_id' => $result[0]->warning_type_id,
				'subject' => $result[0]->subject,
				'description' => $result[0]->description,
				'status' => $result[0]->status,
				'all_employees' => $this->Xin_model->all_employees(),
				'all_warning_types' => $this->Warning_model->all_warning_types(),
				);
		if(!empty($this->userSession)){
			$this->load->view('warning/dialog_warning', $data);
		} else {
			redirect('');
		}
	}

	public function add_warning() {	
		if($this->input->post('add_type')=='warning') {
		$Return = array('result'=>'', 'error'=>'');			
		/* Server side PHP input validation */
		$description = $this->input->post('description');
		$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
		
		if($this->input->post('warning_to')==='') {
      $Return['error'] = "The warning to field is required.";
		} else if($this->input->post('type')==='') {
			$Return['error'] = "The warning type field is required.";
		} else if($this->input->post('subject')==='') {
			$Return['error'] = "The subject field is required.";
		} else if(empty($this->input->post('warning_by'))) {
			$Return['error'] = "The warning by field is required.";
		} else if(empty($this->input->post('warning_date'))) {
			$Return['error'] = "The warning date field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'warning_to' => $this->input->post('warning_to'),
		'warning_type_id' => $this->input->post('type'),
		'description' => $qt_description,
		'subject' => $this->input->post('subject'),
		'warning_by' => $this->input->post('warning_by'),
		'warning_date' => format_date('Y-m-d',$this->input->post('warning_date')),
		'status' => '0',
		'created_at' => date('d-m-Y'),
		);
		$result = $this->Warning_model->add($data);
		/*User Logs*/
		$affected_id= table_max_id('xin_employee_warnings','warning_id');
		userlogs('Employees-Employee Warning-Add','Employee Warning Added',$affected_id['field_id'],$affected_id['datas']);
		/*User Logs*/
		if ($result == TRUE) {
			$Return['result'] = 'Warning added.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}

	public function update() {
	
		if($this->input->post('edit_type')=='warning') {
			
		$id = $this->uri->segment(3);

		$Return = array('result'=>'', 'error'=>'');

		$description = $this->input->post('description');
		$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
		
		if($this->input->post('warning_to')==='') {
      $Return['error'] = "The warning to field is required.";
		} else if($this->input->post('type')==='') {
			$Return['error'] = "The warning type field is required.";
		} else if($this->input->post('subject')==='') {
			$Return['error'] = "The subject field is required.";
		} else if(empty($this->input->post('warning_by'))) {
			$Return['error'] = "The warning by field is required.";
		} else if(empty($this->input->post('warning_date'))) {
			$Return['error'] = "The warning date field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'warning_to' => $this->input->post('warning_to'),
		'warning_type_id' => $this->input->post('type'),
		'description' => $qt_description,
		'subject' => $this->input->post('subject'),
		'warning_by' => $this->input->post('warning_by'),
		'warning_date' => format_date('Y-m-d',$this->input->post('warning_date')),
		'status' => $this->input->post('status'),
		);
		
		$result = $this->Warning_model->update_record($data,$id);		
		/*User Logs*/
		$affected_id= table_update_id('xin_employee_warnings','warning_id',$id);
		userlogs('Employees-Employee Warning-Update','Employee Warning Updated',$id,$affected_id['datas']);
		/*User Logs*/
		if ($result == TRUE) {
			$Return['result'] = 'Warning updated.';
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
		$affected_row= table_deleted_row('xin_employee_warnings','warning_id',$id);
		userlogs('Employees-Employee Warning-Delete','Employee Warning Deleted',$id,$affected_row);		
		/*User Logs*/
		$this->Warning_model->delete_record($id);
		if(isset($id)) {
			$Return['result'] = 'Warning deleted.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
	}

}
