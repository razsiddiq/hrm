<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author Siddiqkhan
 *
 * @Transfers Controller
 */
class Transfers extends MY_Controller {

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
		$this->load->model("Transfers_model");
		$this->load->model("Xin_model");
		$this->load->model("Department_model");
		$this->load->model("Location_model");
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
		$data['all_locations'] = $this->Xin_model->all_locations();
		$data['all_departments'] = $this->Department_model->all_departments();
		$data['breadcrumbs'] = 'Transfers';
		$data['path_url'] = 'transfers';
		if(in_array('16',role_resource_ids()) || in_array('16a',role_resource_ids()) || in_array('16e',role_resource_ids()) || in_array('16d',role_resource_ids()) || in_array('16v',role_resource_ids())) {
			if(!empty($this->userSession)){
			$data['subview'] = $this->load->view("transfers/transfer_list", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}		  
  }
 
  public function transfer_list()
  {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("transfers/transfer_list", $data);
		} else {
			redirect('');
		}

		$draw = intval($this->input->get("draw"));
		$transfer = $this->Transfers_model->get_transfers();
		$data = array();

		foreach($transfer->result() as $r) {
			// get user > added by
			$user = $this->Xin_model->read_user_info($r->added_by);
			// user full name
			$full_name = change_fletter_caps($user[0]->first_name.' '.$user[0]->middle_name.' '.$user[0]->last_name);

			// get user > employee_
			$employee = $this->Xin_model->read_user_info($r->employee_id);
			// employee full name
			$employee_name = change_fletter_caps($employee[0]->first_name.' '.$employee[0]->middle_name.' '.$employee[0]->last_name);
			// get date
			$transfer_date = $this->Xin_model->set_date_format($r->transfer_date);
			// get department by id
			$department = $this->Department_model->read_department_information($r->transfer_department);
			// get location by id
			$location = $this->Location_model->read_location_information($r->transfer_location);
			// get status
			if($r->status==0): $status = '<span class="label label-warning">Pending</span>';
			elseif($r->status==1): $status = '<span class="label label-success">Accepted</span>'; else: $status = '<span class="label label-danger">Rejected</span>'; endif;

			  $edit_perm='';
			  $view_perm='';
			  $delete_perm='';
			  if(in_array('16e',role_resource_ids())) {
				$edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit-modal-data"  data-transfer_id="'. $r->transfer_id . '"><i class="icon-pencil7"></i> Edit</a></li>';
			  }
			  if(in_array('16v',role_resource_ids())) {
				$view_perm='<li><a href="#" data-toggle="modal" data-target=".view-modal-data" data-transfer_id="'. $r->transfer_id . '"><i class="icon-eye4"></i> View</a></li>';
			  }
			  if(in_array('16d',role_resource_ids())) {
				$delete_perm='<li><a class="delete" href="#" data-record-id="'. $r->transfer_id . '"><i class="icon-trash"></i> Delete</a></li>';
			  }
			$data[] = array(
				$employee_name,
				$transfer_date,
				$department[0]->department_name,
				$location[0]->location_name,
				$status,
				$full_name,
				'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$view_perm.$delete_perm.'</ul></li></ul>',

			);
      }

	  $output = array(
		     "draw" => $draw,
			 "recordsTotal" => $transfer->num_rows(),
			 "recordsFiltered" => $transfer->num_rows(),
			 "data" => $data
		);
	  $this->output($output);
  }

  public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('transfer_id');
		$result = $this->Transfers_model->read_transfer_information($id);
		$data = array(
				'transfer_id' => $result[0]->transfer_id,
				'employee_id' => $result[0]->employee_id,
				'transfer_date' => format_date('d F Y',$result[0]->transfer_date),
				'transfer_department' => $result[0]->transfer_department,
				'transfer_location' => $result[0]->transfer_location,
				'description' => $result[0]->description,
				'status' => $result[0]->status,
				'all_employees' => $this->Xin_model->all_employees(),
				'all_locations' => $this->Xin_model->all_locations(),
				'all_departments' => $this->Department_model->all_departments()
				);

		if(!empty($this->userSession)){
			$this->load->view('transfers/dialog_transfer', $data);
		} else {
			redirect('');
		}
	}

	public function add_transfer() {	
		if($this->input->post('add_type')=='transfer') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		$description = $this->input->post('description');
		$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
		
		if($this->input->post('employee_id')==='') {
       		 $Return['error'] = "The employee field is required.";
		} else if($this->input->post('transfer_date')==='') {
			$Return['error'] = "The date field is required.";
		} else if($this->input->post('transfer_department')==='') {
			 $Return['error'] = "The department field is required.";
		} else if($this->input->post('transfer_location')==='') {
       		$Return['error'] = "The location field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    }
	
		$data = array(
		'employee_id' => $this->input->post('employee_id'),
		'transfer_date' => format_date('Y-m-d',$this->input->post('transfer_date')),
		'transfer_department' => $this->input->post('transfer_department'),
		'description' => $qt_description,
		'transfer_location' => $this->input->post('transfer_location'),
		'added_by' => $this->input->post('user_id'),
		'created_at' => date('d-m-Y'),
		
		);
		$result = $this->Transfers_model->add($data);
		
		/*User Logs*/
		$affected_id= table_max_id('xin_employee_transfer','transfer_id');
		userlogs('Employees-Employee Transfer-Add','Employee Transfer Added',$affected_id['field_id'],$affected_id['datas']);
		/*User Logs*/

		if ($result == TRUE) {
			$Return['result'] = 'Transfer added.';
			
			//get setting info 
			/*$setting = $this->Xin_model->read_setting_info(1);
			if($setting[0]->enable_email_notification == 'yes') {
				
				// load email library
				$this->load->library('email');
				$this->email->set_mailtype("html");
				
				//get company info
				$cinfo = $this->Xin_model->read_company_setting_info(1);
				//get email template
				$template = $this->Xin_model->read_email_template(9);
				//get employee info
				$user_info = $this->Xin_model->read_user_info($this->input->post('employee_id'));
				
				$full_name = $user_info[0]->first_name.' '.$user_info[0]->last_name;
						
				$subject = $template[0]->subject.' - '.$cinfo[0]->company_name;
				$logo = base_url().'uploads/logo/'.$cinfo[0]->logo;
				
				$message = '
			<div style="background:#f6f6f6;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;padding: 20px;">
			<img src="'.$logo.'" title="'.$cinfo[0]->company_name.'"><br>'.str_replace(array("{var site_name}","{var site_url}","{var employee_name}"),array($cinfo[0]->company_name,site_url(),$full_name),htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';
				
				$this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
				$this->email->to($user_info[0]->email);
				
				$this->email->subject($subject);
				$this->email->message($message);
				
				$this->email->send();
			}*/
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}

	public function update() {
		if($this->input->post('edit_type')=='transfer') {			
		$id = $this->uri->segment(3);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		$description = $this->input->post('description');
		$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
		
		if($this->input->post('employee_id')==='') {
       		 $Return['error'] = "The employee field is required.";
		} else if($this->input->post('transfer_date')==='') {
			$Return['error'] = "The date field is required.";
		} else if($this->input->post('transfer_department')==='') {
			 $Return['error'] = "The department field is required.";
		} else if($this->input->post('transfer_location')==='') {
       		$Return['error'] = "The location field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'employee_id' => $this->input->post('employee_id'),
		'transfer_date' => format_date('Y-m-d',$this->input->post('transfer_date')),
		'transfer_department' => $this->input->post('transfer_department'),
		'description' => $qt_description,
		'transfer_location' => $this->input->post('transfer_location'),
		'status' => $this->input->post('status'),
		);
		
		$result = $this->Transfers_model->update_record($data,$id);		
		/*User Logs*/
		$affected_id= table_update_id('xin_employee_transfer','transfer_id',$id);
		userlogs('Employees-Employee Transfer-Update','Employee Transfer Updated',$id,$affected_id['datas']);
		/*User Logs*/
		if ($result == TRUE) {
			$Return['result'] = 'Transfer updated.';
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
		$affected_row= table_deleted_row('xin_employee_transfer','transfer_id',$id);
		userlogs('Employees-Employee Transfer-Delete','Employee Transfer Deleted',$id,$affected_row);		
		/*User Logs*/
		
		$this->Transfers_model->delete_record($id);
		if(isset($id)) {
			$Return['result'] = 'Transfer deleted.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
	}

}
