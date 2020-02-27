<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author Siddiqkhan
 *
 * @Resignation Controller
 */
class Resignation extends MY_Controller {

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
		$this->load->model("Resignation_model");
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
		$data['breadcrumbs'] = 'Resignations';
		$data['path_url'] = 'resignation';
		if(in_array('17',role_resource_ids()) || in_array('17a',role_resource_ids()) || in_array('17e',role_resource_ids()) || in_array('17d',role_resource_ids()) || in_array('17v',role_resource_ids())) {
			if(!empty($this->userSession)){
			$data['subview'] = $this->load->view("resignation/resignation_list", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}		  
     }
 
    public function resignation_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("resignation/resignation_list", $data);
		} else {
			redirect('');
		}

		$draw = intval($this->input->get("draw"));
		$resignation = $this->Resignation_model->get_resignations();
		$data = array();

		foreach($resignation->result() as $r) {

			// get user > added by
			$user = $this->Xin_model->read_user_info($r->added_by);
			// user full name
			$full_name = change_fletter_caps($user[0]->first_name.' '.$user[0]->middle_name.' '.$user[0]->last_name);

			// get user > employee_
			$employee = $this->Xin_model->read_user_info($r->employee_id);
			// employee full name
			$employee_name = change_fletter_caps($employee[0]->first_name.' '.$employee[0]->middle_name.' '.$employee[0]->last_name);
			// get notice date
			$notice_date = $this->Xin_model->set_date_format($r->notice_date);
			// get resignation date
			$resignation_date = $this->Xin_model->set_date_format($r->resignation_date);

			$edit_perm='';
			$view_perm='';
			$delete_perm='';
			if(in_array('17e',role_resource_ids())) {
				$edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit-modal-data"  data-resignation_id="'. $r->resignation_id . '"><i class="icon-pencil7"></i> Edit</a></li>';
			}
			if(in_array('17v',role_resource_ids())) {
				$view_perm='<li><a href="#" data-toggle="modal" data-target=".view-modal-data" data-resignation_id="'. $r->resignation_id . '"><i class="icon-eye4"></i> View</a></li>';
			}
			if(in_array('17d',role_resource_ids())) {
				$delete_perm='<li><a class="delete" href="#" data-record-id="'. $r->resignation_id . '"><i class="icon-trash"></i> Delete</a></li>';
			}
			$data[] = array(
				$employee_name,
				$notice_date,
				$resignation_date,
				$full_name,
				'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$view_perm.$delete_perm.'</ul></li></ul>',
			);
      	}

	 	$output = array(
		   "draw" => $draw,
			 "recordsTotal" => $resignation->num_rows(),
			 "recordsFiltered" => $resignation->num_rows(),
			 "data" => $data
		);

		$this->output($output);
     }

    public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('resignation_id');
		$result = $this->Resignation_model->read_resignation_information($id);
		$data = array(
				'resignation_id' => $result[0]->resignation_id,
				'employee_id' => $result[0]->employee_id,
				'notice_date' => format_date('d F Y',$result[0]->notice_date),
				'resignation_date' => format_date('d F Y',$result[0]->resignation_date),
				'reason' => $result[0]->reason,
				'all_employees' => $this->Xin_model->all_employees(),
				);

		if(!empty($this->userSession)){
			$this->load->view('resignation/dialog_resignation', $data);
		} else {
			redirect('');
		}
	}

	public function add_resignation() {

		$Return = array('result'=>'', 'error'=>'');

		if($this->input->post('add_type')=='resignation') {		
		$reason = $this->input->post('reason');
		$qt_reason = htmlspecialchars(addslashes($reason), ENT_QUOTES);
		
		if($this->input->post('employee_id')==='') {
       		 $Return['error'] = "The employee field is required.";
		} else if($this->input->post('notice_date')==='') {
			$Return['error'] = "The notice date field is required.";
		} else if($this->input->post('resignation_date')==='') {
			 $Return['error'] = "The resignation date field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'employee_id' => $this->input->post('employee_id'),
		'notice_date' => format_date('Y-m-d',$this->input->post('notice_date')),
		'resignation_date' => format_date('Y-m-d',$this->input->post('resignation_date')),
		'reason' => $qt_reason,
		'added_by' => $this->input->post('user_id'),
		'created_at' => date('d-m-Y H i s'),
		
		);
	
		$result = $this->Resignation_model->add($data);
		/*User Logs*/
		$affected_id= table_max_id('xin_employee_resignations','resignation_id');
		userlogs('Employees-Employee Resignation-Add','Employee Resignation Added',$affected_id['field_id'],$affected_id['datas']);
		/*User Logs*/
		if ($result == TRUE) {
			$Return['result'] = 'Resignation added.';
			//get setting info 
			/*$setting = $this->Xin_model->read_setting_info(1);
			if($setting[0]->enable_email_notification == 'yes') {
				
				$this->load->library('email');
				$this->email->set_mailtype("html");
				
				//get company info
				$cinfo = $this->Xin_model->read_company_setting_info(1);
				//get email template
				$template = $this->Xin_model->read_email_template(12);
				//get employee info
				$user_info = $this->Xin_model->read_user_info($this->input->post('employee_id'));
				
				$full_name = change_fletter_caps($user_info[0]->first_name.' '.$user_info[0]->middle_name.' '.$user_info[0]->last_name);
						
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
	
		if($this->input->post('edit_type')=='resignation') {
			
		$id = $this->uri->segment(3);

		$Return = array('result'=>'', 'error'=>'');

		$reason = $this->input->post('reason');
		$qt_reason = htmlspecialchars(addslashes($reason), ENT_QUOTES);
		
		if($this->input->post('employee_id')==='') {
       		 $Return['error'] = "The employee field is required.";
		} else if($this->input->post('notice_date')==='') {
			$Return['error'] = "The notice date field is required.";
		} else if($this->input->post('resignation_date')==='') {
			 $Return['error'] = "The resignation date field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'employee_id' => $this->input->post('employee_id'),
		'notice_date' => format_date('Y-m-d',$this->input->post('notice_date')),
		'resignation_date' => format_date('Y-m-d',$this->input->post('resignation_date')),
		'reason' => $qt_reason,
		);
		
		$result = $this->Resignation_model->update_record($data,$id);		
		/*User Logs*/
		$affected_id= table_update_id('xin_employee_resignations','resignation_id',$id);
		userlogs('Employees-Employee Resignation-Update','Employee Resignation Updated',$id,$affected_id['datas']);
		/*User Logs*/
		if ($result == TRUE) {
			$Return['result'] = 'Resignation updated.';
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
		$affected_row= table_deleted_row('xin_employee_resignations','resignation_id',$id);
		userlogs('Employees-Employee Resignation-Delete','Employee Resignation Deleted',$id,$affected_row);		
		/*User Logs*/
		$this->Resignation_model->delete_record($id);
		if(isset($id)) {
			$Return['result'] = 'Resignation deleted.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
	}

}
