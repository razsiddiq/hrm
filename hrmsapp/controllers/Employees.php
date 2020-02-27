<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author Siddiqkhan
 *
 * @Employee Controller
*/
class Employees extends MY_Controller {

	protected $userSession = null;

	public function __construct() {
		Parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->database();
		$this->load->library('email');
		$this->load->library('form_validation');
		//load the models
		$this->load->model("Employees_model");
		$this->load->model("Xin_model");
		$this->load->model("Department_model");
		$this->load->model("Designation_model");
		$this->load->model("Roles_model");
		$this->load->model("Reports_model");
		$this->load->model("Location_model");
		$this->load->model('Timesheet_model');
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
		$data['all_designations'] = $this->Designation_model->all_designations();
		$data['all_user_roles'] = $this->Roles_model->all_user_roles();
		$data['all_office_shifts'] = '';
		$data['all_office_locations']= $this->Location_model->all_office_locations();
		$data['breadcrumbs'] = $this->lang->line('xin_employees');
		$data['path_url'] = 'employees';
		$data['all_countries'] = $this->Xin_model->get_countries();
		$data['phone_numbers_code'] =phone_numbers_code();
		$data['all_companies']=$this->Employees_model->all_companies();

		if(in_array('employees-list-view',role_resource_ids()) || in_array('basic-info-view',role_resource_ids()) || in_array('documents-view',role_resource_ids()) || in_array('offer-letter-view',role_resource_ids()) || in_array('contract-view',role_resource_ids()) || in_array('pay-structure-view',role_resource_ids()) || in_array('bank-account-view',role_resource_ids()) || in_array('emergency-contacts-view',role_resource_ids()) ||  in_array('qualification-view',role_resource_ids()) ||  in_array('work-experience-view',role_resource_ids()) ||  in_array('change-password',role_resource_ids()) || visa_wise_role_ids() != '') {		
			if(!empty($this->userSession)){
				$data['subview'] = $this->load->view("employees/employees_list", $data, TRUE);
				$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
	}

	public function directory() {

		// pr($this->input->get());die;
		$data['title'] = $this->Xin_model->site_title();
		$data['all_departments'] = $this->Department_model->all_departments();
		$data['all_designations'] = $this->Designation_model->all_designations();
		$data['all_user_roles'] = $this->Roles_model->all_user_roles();
		$data['breadcrumbs'] = $this->lang->line('xin_employees_directory');
		$data['path_url'] = 'employees_directory';
		$data['all_office_locations']= $this->Location_model->all_office_locations();
		$data['all_countries'] = $this->Xin_model->get_countries();

		if(!empty($this->input->get())){

			$status=$this->input->get('status');
			$role_value=$this->input->get('role_value');
			$department_value=$this->input->get('department_value');
			$location_value=$this->input->get('location_value');
			$country_value = $this->input->get('country_value');
			$employee_value = $this->input->get('employee_value');
			$all_employees = $this->Employees_model->get_employees_list_directory($department_value,$location_value,$role_value,$country_value,$status,$employee_value);
			$data['all_employees'] = $all_employees->result();
			$data['location_value'] = $this->input->get('location_value');
		}else{

			// $data['all_employees'] = $this->Xin_model->all_employees();
			$data['location_value'] = 1;
			$all_employees = $this->Employees_model->get_employees_list_directory('',1,'','','','');
			$data['all_employees'] = $all_employees->result();
		}

		if(in_array('52',role_resource_ids())) {
			if(!empty($this->userSession)){
				$data['subview'] = $this->load->view("employees/directory", $data, TRUE);
				$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
	}

	public function progress_bar(){
		$user_id=$this->input->get('user_id');
		echo $progress_bar=get_profile_complete_progress($user_id);
	}

	public function reminder_profile_completion($schedule = 'daily'){
		if(!$this->input->is_cli_request())
			die("Access Denied");

		$result = $this->Employees_model->get_employees_uae();
		$cinfo = $this->Xin_model->read_company_setting_info(1);
		$currentDate = new DateTime();
		$currentDate = new DateTime($currentDate->format('Y-m-d'));
		$site_url=str_replace('http://','',str_replace('https://','',base_url()));
		$template = $this->Xin_model->read_email_template_info_bycode('profile_completion_reminder');

		foreach ($result->result() as $employee){
			$progress_bar=get_profile_complete_progress($employee->user_id,true);
			$percentage = 70;
			/**
			 * For JLT -> profile should be 90% complete
			 * For DIP or Diera -> 70%
			 */
			if($employee->office_location_id == 1 || $employee->office_location_id == 4)
				$percentage = 70;
			else if($employee->office_location_id == 3)
				$percentage = 50;
			$sendEmail = false;

			if($schedule == 'daily'){
				$firstDay = (new DateTime($employee->date_of_joining) )->modify("+1 day");
				$secondDay = (new DateTime($employee->date_of_joining) )->modify("+2 day");
				$thirdDay = (new DateTime($employee->date_of_joining) )->modify("+3 day");
				if($currentDate == $firstDay || $currentDate == $secondDay || $currentDate == $thirdDay )
					$sendEmail = true;
			}
			else if($schedule == 'weekly')
				$sendEmail = true;

			if($progress_bar < $percentage && $sendEmail && $employee->email != '' && $employee->email != 'u@awok.com'){
				$message = str_replace("{var employee_name}",$employee->first_name.' '.$employee->last_name,htmlspecialchars_decode(stripslashes($template[0]->message)));
				$message = str_replace("{var site_url}",$site_url,$message);
				$message = str_replace("{var year}",date("Y"),$message);
				$this->email->from(FROM_MAIL, $cinfo[0]->company_name);
				$this->email->to(strtolower($employee->email));
				$this->email->subject("Complete Your Profile");
				$this->email->message($message);
				$this->email->send();
			}
		}
		echo "Success";
	}

	public function reminder_documents_expiry(){

		if(!$this->input->is_cli_request())
			die("Access Denied");

		$result = $this->Employees_model->getDocumentsNearbyExpiry();
		$template = $this->Xin_model->read_email_template_info_bycode('expiry_documents');
		$site_url=str_replace('http://','',str_replace('https://','',base_url()));
		$message = str_replace("{var site_url}",$site_url,htmlspecialchars_decode(stripslashes($template[0]->message)));
		$message = str_replace("{var year}",date("Y"),$message);
		$tableData = '';
		foreach ($result as $row){
			$visaType = ($this->Employees_model->checkVisaType($row->user_id));
			$tableData .= '<tr>
							<td style="padding:7px;">'.$row->first_name.' '.$row->last_name.'</td>
							<td style="padding:7px;">'.$row->designation_name.'</td>
							<td style="padding:7px;">'.$visaType.'</td>
							<td style="padding:7px;">'.$row->type_name.'</td>
							<td style="padding:7px;">'.DateTime::createFromFormat("Y-m-d",$row->expiry_date)->format("d M y").'</td>
						  </tr>';
		}

		$table = '<table class="table table-bordered" style="border: 1px solid #ddd; font-size: 11px;" width="100%" border="1">
					<tbody>
					<tr>
						<td style="padding:7px;"><b>Employee</b></td>
						<td style="padding:7px;"><b>Designation</b></td>
						<td style="padding:7px;"><b>Visa</b></td>
						<td style="padding:7px;"><b>Document</b></td>
						<td style="padding:7px;"><b>Expiry</b></td>
					</tr>
					'.$tableData.'
					</tbody>
					</table>';
		$message = str_replace("{var table_data}",$table,$message);
		$cinfo = $this->Xin_model->read_company_setting_info(1);
		$hrAdmins = [
			'sharoon.y@awok.ae',
			'gurmeet@awok.ae',
			'hr@awok.com'
		];
		$currentDate = new DateTime();
		$this->email->from(FROM_MAIL, $cinfo[0]->company_name);
		$this->email->to($hrAdmins);
		$this->email->bcc('bilal@awok.com');
		$this->email->subject("Documents Expiring Report [".$currentDate->format('d M Y')."]");
		$this->email->message($message);
		$this->email->send();
		echo 'Email send successfully';
	}

	public function employees_list()
	{
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("employees/employees_list", $data);
		}
		else {
			redirect('');
		}

		$draw = intval($this->input->get("draw"));
		$department_value=$this->input->get('department_value');
		$location_value=$this->input->get('location_value');
		$role_value=$this->input->get('role_value');
		$medical_card_value=$this->input->get('medical_card_value');
		$visa_value=$this->input->get('visa_value');
		$country_value = $this->input->get('country_value');
		$employee = $this->Employees_model->get_employees_list_table($department_value,$location_value,$role_value,$medical_card_value,$visa_value,$country_value);
		 
		$data = array();
		foreach($employee->result() as $r) {
			$full_name = change_fletter_caps($r->first_name.' '.$r->middle_name.' '.$r->last_name);
			if($r->visa_type!=''){
				$visa_type=$r->visa_type;
			}else{
				$visa_type='N/A';
			}
			// get status
			$check_if_resign=$this->Employees_model->check_if_resign($r->user_id);
			$check_if_terminate=$this->Employees_model->check_if_terminate($r->user_id);
			$check_if_eos=$this->Employees_model->check_if_eos($r->user_id,'Final Settlement');
			$status1='<i class="icon-cancel-square text-danger"></i>';
			$statusClass = 'label-danger';
			if($r->is_active==0){
				if($check_if_resign==1)
					$status = 'Resigned';
				else if($check_if_terminate==1)
					$status = 'Termination';
				else if($check_if_eos==1)
					$status = 'End of Service';
				else
					$status = 'In-Active';
			}
			else if($r->is_active==1){
				$statusClass = 'label-success';
				if($check_if_resign==1)
					$status = 'Resigned';
				else if($check_if_terminate==1)
					$status = 'Termination';
				else if($check_if_eos==1)
					$status = 'End of Service';
				else
					$status = 'Active';
				$status1='<i class="icon-checkmark4 text-success"></i>';
			}

			$designation = $this->Designation_model->read_designation_information($r->designation_id);
			$department = $this->Department_model->read_department_information($r->department_id);
			$department_designation = @$designation[0]->designation_name.'('.@$department[0]->department_name.')';
			$progress_bar=get_profile_complete_progress($r->user_id);

			$edit_perm='';
			$view_perm='';
			$delete_perm='';


			if(in_array('employees-list-edit',role_resource_ids())) {
				$edit_perm='<li><a href="employees/detail/'.$r->user_id.'"><i class="icon-pencil7"></i> Edit Details</a></li>';
			}

			if(in_array('employees-list-view',role_resource_ids()) || visa_wise_role_ids() != '') {
				$view_perm='<li><a href="employees/view_detail/'.$r->user_id.'"><i class="icon-eye4"></i> View Details</a></li>';
			}

			if(in_array('employees-list-delete',role_resource_ids())) {
				$delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->user_id . '"><i class="icon-trash"></i> Delete</a></li>';
			}


			$option = '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$view_perm.$delete_perm.'</ul></li></ul>';

			if($r->email!=''){$email=$r->email;}else{$email='N/A';}
			if($r->contact_no!=''){
				if(in_array(str_replace('-','',$r->contact_no),phone_numbers_code())){
					$contact_no='N/A';
				}else{
					$contact_no=$r->contact_no;
				}
			}else{$contact_no='N/A';}


			$data[] = array(
				'<span class="label '.$statusClass.'">'.$status.'</span>',
				$full_name,
				'<span data-toggle="tooltip" data-placement="top" title="1C ID - '.$r->employee_id.'">'.$full_name.'</span><br>'.$progress_bar,
				$this->Xin_model->set_date_format($r->date_of_joining),
				$email,
				$contact_no,
				$department_designation,
				$visa_type,
				$r->location_name,
				$option,
			);

		}
		$output = array(
			"draw" => $draw,
			"recordsTotal" => $employee->num_rows(),
			"recordsFiltered" => $employee->num_rows(),
			"data" => $data
		);
		$this->output($output);
	}

	public function report()
	{
		$data['title'] = $this->Xin_model->site_title();
		$data['all_departments'] = $this->Department_model->all_departments();
		$data['all_designations'] = $this->Designation_model->all_designations();
		$data['all_user_roles'] = $this->Roles_model->all_user_roles();
		$data['all_office_shifts'] = '';
		$data['all_office_locations']= $this->Location_model->all_office_locations();
		$data['breadcrumbs'] = $this->lang->line('xin_employees');
		$data['path_url'] = 'employees';
		$data['sub_path'] = 'report';
		$data['all_countries'] = $this->Xin_model->get_countries();
		$data['phone_numbers_code'] =phone_numbers_code();
		$data['all_companies']=$this->Employees_model->all_companies();
		$data['all_employees'] = $this->Xin_model->all_employees();

		if(in_array('60c-view',role_resource_ids())) {
			if(!empty($this->userSession)){
				$data['subview'] = $this->load->view("employees/employees_report", $data, TRUE);
				$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
	}

	public function employees_report_ajax()
	{
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("employees/employees_list", $data);
		}
		else {
			redirect('');
		}
		// Datatables Variables
		$draw = ($_REQUEST['draw']);
		$start = ($_REQUEST['start']);
		$length = ($_REQUEST['length']);

		$employee_id=$this->input->get('employee_id');
		$department_value=$this->input->get('department_value');
		$location_value=$this->input->get('location_value');
		$role_value=$this->input->get('role_value');
		$reporting_to=$this->input->get('reporting_to');
		$employee_status=$this->input->get('employee_status');
		$medical_card_value=$this->input->get('medical_card_value');
		$visa_value=$this->input->get('visa_value');
		$employees_column =$this->input->get('employees_column');
		$country_value =$this->input->get('country_value');
		$employee = $this->Employees_model->get_employee_reports($department_value,$employee_status,$reporting_to,$country_value,$location_value,$role_value,$medical_card_value,$visa_value,$start,$length,$employee_id);
		$totalRecords = $this->Employees_model->get_employee_reports($department_value,$employee_status,$reporting_to,$country_value,$location_value,$role_value,$medical_card_value,$visa_value,0,0);
		$data = array();


		foreach($employee->result() as $r) {
		
			$full_name = change_fletter_caps($r->first_name.' '.$r->middle_name.' '.$r->last_name);

			// get status
			$check_if_resign=$this->Employees_model->check_if_resign($r->user_id);
			$check_if_terminate=$this->Employees_model->check_if_terminate($r->user_id);
			$check_if_eos=$this->Employees_model->check_if_eos($r->user_id,'Final Settlement');
			$status1='<i class="icon-cancel-square text-danger"></i>';
			$statusClass = 'label-danger';
			if($r->is_active==0){
				if($check_if_resign==1)
					$status = 'Resigned';
				else if($check_if_terminate==1)
					$status = 'Termination';
				else if($check_if_eos==1)
					$status = 'End of Service';
				else
					$status = 'In-Active';
			}
			else if($r->is_active==1){
				$statusClass = 'label-success';
				if($check_if_resign==1)
					$status = 'Resigned';
				else if($check_if_terminate==1)
					$status = 'Termination';
				else if($check_if_eos==1)
					$status = 'End of Service';
				else
					$status = 'Active';
				$status1='<i class="icon-checkmark4 text-success"></i>';
			}

			$designation = $this->Designation_model->read_designation_information($r->designation_id);
			$department = $this->Department_model->read_department_information($r->department_id);
			$department_designation = @$designation[0]->designation_name;

			$progress_bar=get_profile_complete_progress($r->user_id);

			$edit_perm='';
			$view_perm='';
			$delete_perm='';

			if(in_array('employees-list-edit',role_resource_ids())) {
				$edit_perm='<li><a href="detail/'.$r->user_id.'"><i class="icon-pencil7"></i> Edit Details</a></li>';
			}
			if(in_array('employees-list-view',role_resource_ids())) {
				$view_perm='<li><a href="view_detail/'.$r->user_id.'"><i class="icon-eye4"></i> View Details</a></li>';
			}
			if((in_array('employees-list-delete',role_resource_ids())) && ($this->userSession['role_name']==AD_ROLE)) {
				$delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->user_id . '"><i class="icon-trash"></i> Delete</a></li>';
			}

			$option = '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$view_perm.$delete_perm.'</ul></li></ul>';

			if($r->email!=''){$email=$r->email;}else{$email='N/A';}
			if($r->personal_email!=''){$personal_email=$r->personal_email;}else{$personal_email='N/A';}
			if($r->contact_no!=''){
				if(in_array(str_replace('-','',$r->contact_no),phone_numbers_code())){
					$contact_no='N/A';
				}else{
					$contact_no=$r->contact_no;
				}
			}else{$contact_no='N/A';}

			$response = [];
			$response[] = '<span class="label '.$statusClass.'">'.$status.'</span>';
			$response[] = $full_name;
    	//$response[] = '<span data-toggle="tooltip" data-placement="top" title="1C ID - '.$r->employee_id.'">'.$full_name.'</span><br>'.$progress_bar;

			/**
			 * Profile Section
			 */
			if(in_array('employee_id',$employees_column))
				$response[] = $r->employee_id;
			if(in_array('date_of_joining',$employees_column))
				$response[] = $this->Xin_model->set_date_format($r->date_of_joining);
			if(in_array('email',$employees_column))
				$response[] = $email;
			if(in_array('nationality',$employees_column))
				$response[] = $r->nationality;
			if(in_array('home_address',$employees_column))
				$response[] = $r->home_address1.' '.$r->home_address2.' '.$r->home_area.' '.$r->home_city.' '.$r->home_country;
			if(in_array('residing_address',$employees_column))
				$response[] = $r->residing_address1.' '.$r->residing_address2.' '.$r->residing_area.' '.$r->residing_city.' '.$r->residing_country;
			if(in_array('personal_email',$employees_column))
				$response[] = $personal_email;
			if(in_array('contact_number',$employees_column))
				$response[] = $r->contact_no;
			if(in_array('emergency_contact',$employees_column))
				$response[] = $r->relation_name.' '.$r->relation_phone_no;
			if(in_array('date_of_birth',$employees_column))
				$response[] = $this->Xin_model->set_date_format($r->date_of_birth);
			if(in_array('gender',$employees_column))
				$response[] = $r->gender;
			if(in_array('designation',$employees_column))
				$response[] = $department_designation;
			if(in_array('department',$employees_column))
				$response[] = $department[0]->department_name;
			if(in_array('location_name',$employees_column))
				$response[] = $r->location_name;
			if(in_array('visa_name',$employees_column))
				$response[] = ($r->type_name)? $r->type_name : 'N/A';
			if(in_array('reporting_manager',$employees_column)){
				if($r->reporting_manager != ''){
					$rm = $this->Employees_model->read_employee_information_by_1cid($r->reporting_manager)[0];
					$response[] = $rm->first_name.' '.$rm->last_name;
				}
				else
					$response[] = 'N/A';
			}
			$cont_details = [];
			if(in_array('contract_type',$employees_column)){
				$cont_details=$this->Reports_model->get_contract_details($r->user_id);
				if(isset($cont_details[0]) && $cont_details[0]->document_file!=''){
					$contract_name=@$cont_details[0]->contract_name;
					$response[] = $contract_name;
				}
				else
					$response[] = 'N/A';
			}
			/**
			 * Documents Section
			 */
			if(in_array('visa_type',$employees_column)){
				$doc = $this->Employees_model->get_employee_document($r->user_id,3)[0];
				if($doc->document_file && $doc->document_file!=''){
					$start_date=@$this->Xin_model->set_date_format($doc->issue_date);
					$end_date=@$this->Xin_model->set_date_format($doc->expiry_date);
					//$response[] = $doc->type_name." ($start_date - $end_date)";
					$document = explode(",",$doc->document_file);
					$response[] = "<a target='_blank' href='".base_url()."uploads/document/immigration/".$document[0]."'>Yes</a> ($start_date - $end_date)";
					$response[] = $doc->document_number;
				}else{
					$response[] = 'No';
					$response[] = '--';
				}
			}
			if(in_array('contract',$employees_column)){
				if(!isset($cont_details[0]))
					$cont_details=$this->Reports_model->get_contract_details($r->user_id);
				if(isset($cont_details[0]) && $cont_details[0]->document_file!=''){
					$contract_name=@$cont_details[0]->contract_name;
					$contract_start_date=@$this->Xin_model->set_date_format($cont_details[0]->from_date);
					if($cont_details[0]->to_date!='Unlimited'){
						$contract_end_date=@$this->Xin_model->set_date_format($cont_details[0]->to_date);
					}else{
						$contract_end_date='Unlimited';
					}
					$document = explode(",",$cont_details[0]->document_file);
					$response[] = "<a target='_blank' href='".base_url()."uploads/document/immigration/".$document[0]."'>Yes</a> ($contract_start_date - $contract_end_date)";
				}
				else
					$response[] = 'No';        
			}
			if(in_array('passport',$employees_column)){
				$doc = $this->Employees_model->get_employee_document($r->user_id,2)[0];
				if($doc->document_file && $doc->document_file!=''){
					$start_date=@$this->Xin_model->set_date_format($doc->issue_date);
					$end_date=@$this->Xin_model->set_date_format($doc->expiry_date);
					$document = explode(",",$doc->document_file);
					$response[] = "<a target='_blank' href='".base_url()."uploads/document/immigration/".$document[0]."'>Yes</a> ($start_date - $end_date)";
					$response[] = $doc->document_number;
				}else{
					$response[] = 'No';
					$response[] = '--';
				}
			}
			if(in_array('eid',$employees_column)){
				$doc = $this->Employees_model->get_employee_document($r->user_id,4)[0];
				if($doc->document_file && $doc->document_file!=''){
					$start_date=@$this->Xin_model->set_date_format($doc->issue_date);
					$end_date=@$this->Xin_model->set_date_format($doc->expiry_date);
					$document = explode(",",$doc->document_file);
					$response[] = "<a target='_blank' href='".base_url()."uploads/document/immigration/".$document[0]."'>Yes</a> ($start_date - $end_date)";
					$response[] = $doc->document_number;
				}else{
					$response[] = 'No';
					$response[] = '--';
				}
			}
			if(in_array('labor_card',$employees_column)){
				$doc = $this->Employees_model->get_employee_document($r->user_id,5)[0];

				
				if($doc->document_file && $doc->document_file!=''){
					$start_date=@$this->Xin_model->set_date_format($doc->issue_date);
					$end_date=@$this->Xin_model->set_date_format($doc->expiry_date);
					$document = explode(",",$doc->document_file);
					$response[] = "<a target='_blank' href='".base_url()."uploads/document/immigration/".$document[0]."'>Yes </a>".$doc->document_number." ($start_date - $end_date)";
				}
				else
					$response[] = 'No';
			}
			if(in_array('medical_card',$employees_column)){
				$doc = $this->Employees_model->get_employee_document($r->user_id,6)[0];
				if($doc->document_file && $doc->document_file!=''){
					$start_date=@$this->Xin_model->set_date_format($doc->issue_date);
					$end_date=@$this->Xin_model->set_date_format($doc->expiry_date);
					$document = explode(",",$doc->document_file);
					$response[] = "<a target='_blank' href='".base_url()."uploads/document/immigration/".$document[0]."'>Yes</a> ($start_date - $end_date)";
				}
				else
					$response[] = 'No';
			}
			if(in_array('offer_letter',$employees_column)){
				$doc = $this->Employees_model->get_employee_document($r->user_id,7)[0];
				if($doc->document_file && $doc->document_file!=''){
					$start_date=@$this->Xin_model->set_date_format($doc->issue_date);
					$end_date=@$this->Xin_model->set_date_format($doc->expiry_date);
					$document = explode(",",$doc->document_file);
					$response[] = "<a target='_blank' href='".base_url()."uploads/document/immigration/".$document[0]."'>Yes</a> ($start_date - $end_date)";
				}
				else
					$response[] = 'No';
			}

			/**
			 * Salary Section
			 */
			if(in_array('basic_salary',$employees_column)){
				$salary = $this->Employees_model->get_employee_salary_by_type($r->user_id,'basic_salary')[0];
				$response[] = ($salary->salary_amount)? $salary->salary_amount : 'N/A';
			}
			if(in_array('house_rent_allowance',$employees_column)){
				$salary = $this->Employees_model->get_employee_salary_by_type($r->user_id,'house_rent_allowance')[0];
				$response[] = ($salary->salary_amount)? $salary->salary_amount : 'N/A';
			}
			if(in_array('travelling_allowance',$employees_column)){
				$salary = $this->Employees_model->get_employee_salary_by_type($r->user_id,'travelling_allowance')[0];
				$response[] = ($salary->salary_amount)? $salary->salary_amount : 'N/A';
			}
			if(in_array('food_allowance',$employees_column)){
				$salary = $this->Employees_model->get_employee_salary_by_type($r->user_id,'food_allowance')[0];
				$response[] = ($salary->salary_amount)? $salary->salary_amount : 'N/A';
			}
			if(in_array('other_allowance',$employees_column)){
				$salary = $this->Employees_model->get_employee_salary_by_type($r->user_id,'other_allowance')[0];
				$response[] = ($salary->salary_amount)? $salary->salary_amount : 'N/A';
			}
			if(in_array('additional_benefits',$employees_column)){
				$salary = $this->Employees_model->get_employee_salary_by_type($r->user_id,'additional_benefits')[0];
				$response[] = ($salary->salary_amount)? $salary->salary_amount : 'N/A';
			}
			if(in_array('bonus',$employees_column)){
				$salary = $this->Employees_model->get_employee_salary_by_type($r->user_id,'bonus')[0];
				$response[] = ($salary->salary_amount)? $salary->salary_amount : 'N/A';
			}
			if(in_array('total_salary',$employees_column)){
				$response[] = ($r->salary_with_bonus)? $r->salary_with_bonus : 'N/A';
			}
			$response[] = $option;
			$data[] = $response;
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $employee->num_rows(),
			"recordsFiltered" => $totalRecords->num_rows(),
			"data" => $data
		);
		$this->output($output);
	}

	public function send_approvals(){

		if($this->input->post('type')=='send_approvals') {
			$this->load->model("Payroll_model");
			$Return = array('result'=>'', 'error'=>'','message'=>'');

			$employee_id=$this->input->post("employee_id");
			$field_id=$this->input->post("field_id");
			$created_by=$this->input->post("created_by");
			$department_id=$this->input->post("department_id");
			$type_of_approval=$this->input->post("type_of_approval");
			$head_id=$this->input->post("head_id");

			$all_department_heads=$this->Employees_model->all_department_heads($department_id,'',$head_id);

			$count_of_dept=count($all_department_heads);
			$setting = $this->Xin_model->read_setting_info(1);
			$this->email->set_mailtype("html");
			if($all_department_heads){
				$insert_approvals=$this->Employees_model->insert_approvals($employee_id,$field_id,$created_by,$all_department_heads,$type_of_approval);
				$emp_info = $this->Xin_model->read_user_info($employee_id);
				$designation = $this->Designation_model->read_designation_information($emp_info[0]->designation_id);
				$emp_full_name = change_fletter_caps($emp_info[0]->first_name.' '.$emp_info[0]->middle_name.' '.$emp_info[0]->last_name);
				$payroll=$this->Payroll_model->read_template_information_byempid($employee_id);
				$pay_structure='';
				$currency=$this->Xin_model->currency_sign($number=null,$location_id='',$employee_id);
				if($payroll){
					$pay_structure.='<div><table border="1" style="padding:10px;width: 100%;text-align:center;font-size:14px;line-height:20px;"><tbody>
					<tr class="bg-slate-600 text-center"><td colspan="2" style="text-align:center;background: #cc6076;color: white;font-size: 13px;"><strong>Compensation Details <br> All figures in <b>'.$currency.'</b> per month</strong></td></tr>
					<tr><td>Name</td><td><strong>'.@$emp_full_name.'</strong></td></tr>	
					<tr><td>Role</td><td><strong>'.@$designation[0]->designation_name.'</strong></td></tr>
					<tr><td colspan="2" style="text-align:center;background: #cc6076;color: white;font-size: 13px;"><strong>Monthly Components</strong></td></tr>';
					if($payroll[0]->basic_salary!=''){
						$pay_structure.='<tr class=""><td>Basic Salary</td><td><strong>'.@$payroll[0]->basic_salary.'</strong></td></tr>	';
					}
					if($payroll[0]->house_rent_allowance!=''){
						$pay_structure.='<tr class=""><td>Housing</td><td><strong>'.@$payroll[0]->house_rent_allowance.'</strong></td></tr>	';
					}
					if($payroll[0]->travelling_allowance!=''){
						$pay_structure.='<tr class=""><td>Transportation</td><td><strong>'.@$payroll[0]->travelling_allowance.'</strong></td></tr>	';
					}
					if($payroll[0]->food_allowance!=''){
						$pay_structure.='<tr class=""><td>Food</td><td><strong>'.@$payroll[0]->food_allowance.'</strong></td></tr>	';
					}
					if($payroll[0]->other_allowance!=''){
						$pay_structure.='<tr class=""><td>Other</td><td><strong>'.@$payroll[0]->other_allowance.'</strong></td></tr>	';
					}
					if($payroll[0]->additional_benefits!=''){
						$pay_structure.='<tr class=""><td>Additional Benefits</td><td><strong>'.@$payroll[0]->additional_benefits.'</strong></td></tr>	';
					}
					if($payroll[0]->bonus!=''){
						$pay_structure.='<tr class=""><td>Bonus</td><td><strong>'.@$payroll[0]->bonus.'</strong></td></tr>	';
					}
					if($payroll[0]->agency_fee!=''){
						$pay_structure.='<tr class=""><td>Agency Fees</td><td><strong>'.@$payroll[0]->agency_fee.'</strong></td></tr>	';
					}
					if($payroll[0]->ot_amount!=''){
						$pay_structure.='<tr class=""><td>OT Amount</td><td><strong>'.@$payroll[0]->ot_amount.'</strong></td></tr>	';
					}

					if($payroll[0]->gross_salary!=''){
						$pay_structure.='<tr class="success"><td>Gross Monthly Salary</td><td><strong>'.@$payroll[0]->gross_salary.'</strong></td></tr>	';
					}

					$pay_structure.='</tbody></table></div>';
				}

				$cinfo = $this->Xin_model->read_company_setting_info(1);

				$sender = $this->Xin_model->read_user_info($created_by);
				$sender_designation = $this->Designation_model->read_designation_information($sender[0]->designation_id);
				$sender_full_name = change_fletter_caps($sender[0]->first_name.' '.$sender[0]->middle_name.' '.$sender[0]->last_name);

				foreach($all_department_heads as $head_of_dep){

					$head_info = $this->Xin_model->read_user_info($head_of_dep->head_id);
					$approve_link=$_SERVER['HTTP_HOST'].base_url().'index/approval/'.base64_encode($head_of_dep->head_id.'/'.$employee_id.'/1/'.$type_of_approval.'/'.$created_by.'/'.$field_id);
					$decline_link=$_SERVER['HTTP_HOST'].base_url().'index/approval/'.base64_encode($head_of_dep->head_id.'/'.$employee_id.'/2/'.$type_of_approval.'/'.$created_by.'/'.$field_id);

					//if($setting[0]->enable_email_notification == 'yes') {

					//get email template
					$template = $this->Xin_model->read_email_template_info_bycode('Employee On Boarding');

					if($count_of_dept!=1){
						$head_title='Employee On Boarding Approval';
						$subject = $template[0]->subject;
					}else{
						$head_title='Employee On Boarding ReApproval';
						$subject = $head_title;
					}

					if($emp_info[0]->gender=='Male'){
						$title='Mr ';
					}else{
						$title='Ms ';
					}
					$message = '<div style="background: #f7eaea;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin: 0 auto;padding:20px;max-width: 65em;border: 2px solid #D40732;">'.
							str_replace(array(
								"{var head_title}",
								"{var head_name}",
								"{var title}",
								"{var employee_name}",
								"{var joining_date}",
								"{var 1c_id}",
								"{var pay_structure}",
								"{var site_url}",
								"{var designation}",
								"{var approve_link}",
								"{var decline_link}",
								"{var sender_full_name}",
								"{var sender_designation}",
							),
							array(
								$head_title,
								$head_of_dep->head_name,
								$title,
								$emp_full_name,
								format_date('d F Y',$emp_info[0]->date_of_joining),
								$emp_info[0]->employee_id,
								$pay_structure,
								'',
								$designation[0]->designation_name,
								$approve_link,
								$decline_link,
								$sender_full_name,
								$sender_designation[0]->designation_name
							),
							htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';

					if(TESTING_MAIL==TRUE){
						$this->email->from(FROM_MAIL, $cinfo[0]->company_name);
						$this->email->to(TO_MAIL);
					}else{
						$this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
						$this->email->to($head_info[0]->email);
					}

					$this->email->subject($subject);
					$this->email->message($message);
					//$this->email->send();
					//}
				}

				if($insert_approvals){
					$Return['result'] = 'Employee on boarding approval send successfully.';
					$Return['message']='<div class="alert alert-success no-border">
										<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
										<span class="text-semibold">Well done!</span> You successfully send this for approval.
								    </div>';
				}else{
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
			}else{
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	public function create_approval_request(){

		$employee_id = $this->input->get("user_id");
		$employee_full_data = $this->Employees_model->employee_full_data_approval($employee_id);
		$visa_details = $this->Employees_model->set_employee_immigration($employee_id,3);
		$visa_details = $visa_details->result_array();

		$passport_details = $this->Employees_model->set_employee_immigration($employee_id,2);
		$passport_details = $passport_details->result_array();

		$session = $this->session->all_userdata();

		if(!empty($employee_id)){

			/*if(empty($employee_full_data[0]->reporting_manager)){

				$Return['message']='<div class="alert alert-danger no-border">
										<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
										<span class="text-semibold">Bug!</span> Please assign reporting manager for this employee.
								    </div>';

			}else*/

			if(empty($employee_full_data[0]->first_name)){

				$Return['message']='<div class="alert alert-danger no-border">
										<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
										<span class="text-semibold">Bug!</span>  Name field is empty for this employee.
								    </div>';

			}elseif(empty($employee_full_data[0]->nationality)){

				$Return['message']='<div class="alert alert-danger no-border">
										<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
										<span class="text-semibold">Bug!</span>  Country field is empty for this employee.
								    </div>';

			}elseif(empty($employee_full_data[0]->designation_id)){

				$Return['message']='<div class="alert alert-danger no-border">
										<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
										<span class="text-semibold">Bug!</span>  Designation is not added for this employee.
								    </div>';

			}elseif(empty($passport_details)){

				$Return['message']='<div class="alert alert-danger no-border">
										<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
										<span class="text-semibold">Bug!</span>  Passport details is not added for this employee.
								    </div>';

			}elseif(empty($visa_details)){

				$Return['message']='<div class="alert alert-danger no-border">
										<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
										<span class="text-semibold">Bug!</span>  Visa details is not added for this employee.
								    </div>';

			}else{

				$rand=strtotime(date('Y-m-d H:i:s'));

				$pendingNonDisclosureApproval = $this->Employees_model->getPendingNonDisclosureApproval($this->input->get('user_id'),'employee');

				if(!empty($pendingNonDisclosureApproval)){
					$data = array(
									'created_by' => $session['username']['user_id'],
									'approval_status'=> 1,
								);
					$approval_id = $pendingNonDisclosureApproval[0]['approval_id'];
					$result = $this->Employees_model->update_non_disclosure_approval($data,$approval_id);

				}else{

					$data = array(
									'employee_id' => $this->input->get('user_id'),
									'field_id' => $rand,
									'type_of_approval' => 'non_disclosure_approval',
									'head_of_approval' => 'Reporting Manager',
									'approval_head_id' => $employee_full_data[0]->reporting_manager,
									'created_by' => $session['username']['user_id'],
									'pay_date' => 1,
									'approval_status'=> 1,
								);

					$result = $this->Employees_model->add_approval_record($data);
				}

				if ($result == TRUE) {
					$Return['message']='<div class="alert alert-success no-border">
										<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
										<span class="text-semibold">Well done!</span> You successfully send.
								    </div>';
				}else{
					$Return['message']='<div class="alert alert-danger no-border">
										<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
										<span class="text-semibold">Bug!</span> Something Went wrong.
								    </div>';
				}
			}

		}else{
			$Return['message']='<div class="alert alert-danger no-border">
										<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
										<span class="text-semibold">Bug!</span> Something Went wrong.
								    </div>';
		}
		$this->output($Return);
		exit;
	}

	public function update_non_disclosure_approval(){

		$session_user = $this->session->all_userdata();

		$user_info = $this->Xin_model->read_user_info($session_user['username']['user_id']);
		$designation_info = $this->Xin_model->read_designation_info($user_info[0]->designation_id);
		$designation_name = $designation_info[0]->designation_name;

		$post_data = $this->input->post();

		/* Creating png and saving to emp docs list */

		$img = $post_data['converted_image'];
		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$img_data = base64_decode($img);

		$set_img = FCPATH."uploads/document/immigration/";
		$filename = 'non_disclosure_form_'.round(microtime(true)).'.png';
		$newfilename = $set_img.$filename;

		file_put_contents($newfilename, $img_data);
		$doc_data['document_file'] = $filename;
		$doc_data['document_type_id'] = 10;
		$doc_data['employee_id'] = $session_user['username']['user_id'];

		$this->db->insert('xin_employee_immigration',$doc_data);

		/* End */

		/* Sending mail to hr */
		$template = $this->Xin_model->read_email_template_info_bycode('Non disclosure');
		$full_name = change_fletter_caps($user_info[0]->first_name.' '.$user_info[0]->middle_name.' '.$user_info[0]->last_name);
		$subject = $template[0]->subject.' from '.$full_name;

		$cinfo = $this->Xin_model->read_company_setting_info(1);
		$logo = base_url().'uploads/logo/'.$cinfo[0]->logo;

		$message = '
			<div style="background:#f6f6f6;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;padding: 20px;">
			<img src="'.$logo.'" title="'.$cinfo[0]->company_name.'"><br>'.str_replace(array("{var employee_name}","{var designation}"),array($full_name,$designation_name),htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';

        // Live
        $to_mail = array("hl@awok.com","sharoon.y@awok.com");
        $this->email->from(FROM_MAIL, $full_name); 
        $this->email->to($to_mail);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->send();

        /* End */

		for ($i=0; $i < count($post_data['approval_id']); $i++) { 
			
			$data['approval_status'] 	= 2;
			$approval_id 	 			= $post_data['approval_id'][$i];
			$data['remarks'] 			= $post_data['data_signature'];
			$this->Employees_model->update_non_disclosure_approval($data,$approval_id);

		}
		$Return['result'] 	= 'Successfully updated.';
		$this->output($Return);
		exit;
	}

	public function detail($sl='',$dt='') {
		$id = $this->uri->segment(3);
		$result = $this->Employees_model->read_employee_information($id);
		$read_location=$this->Location_model->read_location_information($result[0]->office_location_id);
		if($read_location){
			$country_id=$read_location[0]->country;
		}else{
			$country_id=0;
		}
		$salary_fields=get_salary_fields_bycountry($country_id);
		$approval_status = $this->Employees_model->read_approval_status($id);
		$data['breadcrumbs'] = $this->lang->line('xin_employee_details');
		$data['path_url'] = 'employees_detail';

		if(in_array('employees-list-edit',role_resource_ids()) || in_array('employees-list-view',role_resource_ids()) || visa_wise_role_ids() != '') {
			if(!empty($this->userSession)){
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}


		$data = array(
			'breadcrumbs' => $this->lang->line('xin_employee_detail'),
			'path_url' => 'employees_detail',
			'first_name' => change_fletter_caps($result[0]->first_name),
			'middle_name' => change_fletter_caps($result[0]->middle_name),
			'last_name' => change_fletter_caps($result[0]->last_name),
			'user_id' => $result[0]->user_id,
			'employee_id' => $result[0]->employee_id,
			'email' => $result[0]->email,
			'is_break_included' => $result[0]->is_break_included,
			'send_punch_reminder' => $result[0]->send_punch_reminder,
			'personal_email' => $result[0]->personal_email,
			'department_id' => $result[0]->department_id,
			'company_id' => $result[0]->company_id,
			'designation_id' => $result[0]->designation_id,
			'visa_occupation' => $result[0]->visa_occupation,
			'user_role_id' => $result[0]->user_role_id,
			'date_of_birth' => $result[0]->date_of_birth,
			'date_of_leaving' => $result[0]->date_of_leaving,
			'gender' => $result[0]->gender,
			'marital_status' => $result[0]->marital_status,
			'contact_no' => $result[0]->contact_no,
			'address' => $result[0]->address,
			'address2' => $result[0]->address2,
			'languages_known' => $result[0]->languages_known,
			'city' => $result[0]->city,
			'area' => $result[0]->area,
			'zipcode' => $result[0]->zipcode,
			'home_country' =>  $result[0]->home_country,
			'nationality' =>  $result[0]->nationality,
			'residing_address1' => $result[0]->residing_address1,
			'residing_address2' => $result[0]->residing_address2,
			'residing_city' => $result[0]->residing_city,
			'residing_area' => $result[0]->residing_area,
			'residing_zipcode' => $result[0]->residing_zipcode,
			'residing_country' => $result[0]->residing_country,
			'tenure' => $result[0]->tenure,
			'reporting_manager' => $result[0]->reporting_manager,
			'is_active' => $result[0]->is_active,
			'date_of_joining' => $result[0]->date_of_joining,
			'all_departments' => $this->Department_model->all_departments(),
			'all_designations' => $this->Designation_model->all_designations(),
			'all_user_roles' => $this->Roles_model->all_user_roles(),
			'title' => $this->Xin_model->site_title(),
			'profile_picture' => $result[0]->profile_picture,
			'working_hours' => $result[0]->working_hours,
			'office_shift_id' => $result[0]->office_shift_id,
			'company_id' => $result[0]->company_id,
			'office_location_id' => $result[0]->office_location_id,
			'all_countries' => $this->Xin_model->get_countries(),
			'all_document_types' => $this->Employees_model->all_document_types(),
			'all_visa_under' => $this->Employees_model->all_visa_under(),
			'all_medical_card_types' => $this->Employees_model->all_medical_card_types(),
			'all_education_level' => $this->Employees_model->all_education_level(),
			'all_qualification_language' => $this->Employees_model->all_qualification_language(),
			'all_qualification_skill' => $this->Employees_model->all_qualification_skill(),
			'all_contract_types' => $this->Employees_model->all_contract_types(),
			'emp_pay_roll' => $this->Employees_model->emp_pay_roll($result[0]->user_id),
			'all_contracts' => $this->Employees_model->all_contracts(),
			'all_office_shifts' => '',
			'all_companies' => $this->Employees_model->all_companies(),
			'all_office_locations' => $this->Location_model->all_office_locations(),
			'phone_numbers_code'=> phone_numbers_code(),
			'currency_id' => $result[0]->currency_id,
			'approval_status'=>$approval_status,
			'blood_group'=>$result[0]->blood_group,
			'company_transport'=>$result[0]->company_transport,
			'salary_fields'=>$salary_fields,
			'spouse_name'=>$result[0]->spouse_name,
		);

		if($dt==''){
			$data['subview'] = $this->load->view("employees/employee_detail", $data, TRUE);
		}else{
			$data['subview'] = $this->load->view("employees/employee_view_detail", $data, TRUE);
		}

		$this->load->view('layout_main', $data); //page load
	}

	public function view_detail() {
		$this->detail('','view');
	}

	public function getdeptemployee(){

		$department_id=$this->uri->segment(3);
		$user_id=$this->uri->segment(4);
		$manager_id=$this->uri->segment(5);
		$employees=$this->Employees_model->getemployeebydepatment($department_id,$user_id);
		echo '<option value="">Select Reporting Manager</option>';
		if(!empty($employees)){
			foreach($employees as $employee){
				$employeeid =  $employee->user_id;
				$employeename = change_fletter_caps($employee->first_name.' '.$employee->middle_name.' '.$employee->last_name);
				if($user_id!=$employeeid){

					if($manager_id!='' && $manager_id==$employeeid){
						$selected="selected='selected'";
					}else{
						$selected="";
					}
					echo "<option ".$selected." value='".$employeeid."' >" . change_to_caps($employeename) . "</option>";
				}
			}
		}else{
			echo "<option value='' >Not Available</option>";
		}
	}

	public function designation() {

		$data['title'] = $this->Xin_model->site_title();
		$id = $this->uri->segment(3);
		$data = array(
			'department_id' => $id,
			'all_designations' => $this->Designation_model->all_designations(),
		);

		if(!empty($this->userSession)){
			$this->load->view("employees/get_designations", $data);
		} else {
			redirect('');
		}
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
			'warning_date' => $result[0]->warning_date,
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

	public function basic_info() {
		if($this->input->post('type')=='basic_info') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');
			$working_hours=decimalHours($this->input->post('working_hours'));
			$check_email = $this->Employees_model->check_email(addslashes($this->input->post('email')),$this->input->post('user_id'));
			$check_employeeid = $this->Employees_model->check_employeeid($this->input->post('employee_id'),$this->input->post('user_id'));
			/* Server side PHP input validation */
			if($this->input->post('first_name')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_first_name');
			} 
			else if($this->input->post('employee_id')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_employee_id');
			} else if($check_employeeid!=0){
				$Return['error'] = $this->lang->line('xin_employee_error_empid_exist');
			} /*else if(empty($this->input->post('email'))) {
			 $Return['error'] = $this->lang->line('xin_employee_error_email');
			} else if (!filter_var($this->input->post('email'), FILTER_VALIDATE_EMAIL)) {
				$Return['error'] = $this->lang->line('xin_employee_error_invalid_email');
			} else if($check_email!=0){
				 $Return['error'] = $this->lang->line('xin_employee_error_useremail_exist');
			} */
			else if($this->input->post('nationality')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_nationality');
			} else if(empty($this->input->post('department_id'))) {
				$Return['error'] = $this->lang->line('xin_employee_error_department');
			} else if(empty($this->input->post('designation_id'))) {
				$Return['error'] = $this->lang->line('xin_employee_error_designation');
			} else if($this->input->post('office_location_id')==='') {
				$Return['error'] = $this->lang->line('xin_e_details_office_location');
			} else if(empty($this->input->post('date_of_joining'))) {
				$Return['error'] = $this->lang->line('xin_employee_error_joining_date');
			}  else if(empty($this->input->post('role'))) {
				$Return['error'] = $this->lang->line('xin_employee_error_user_role');
			}  else if($working_hours < 8) {
				$Return['error'] = 'Working hours should be minimum 8 hours';
			} /*else if($this->input->post('address')==='') {
			 $Return['error'] = $this->lang->line('xin_error_address1_field');
			} else if($this->input->post('address2')==='') {
				 $Return['error'] = $this->lang->line('xin_error_address2_field');
			} else if($this->input->post('city')==='') {
				 $Return['error'] = $this->lang->line('xin_error_city_field');
			} else if($this->input->post('area')==='') {
				 $Return['error'] = $this->lang->line('xin_error_area_field');
			} else if($this->input->post('zipcode')==='') {
				 $Return['error'] = $this->lang->line('xin_error_zipcode_field');
			} else if($this->input->post('home_country')==='') {
				 $Return['error'] = $this->lang->line('xin_error_country_field');
			} else if(empty($this->input->post('contact_no'))) {
				 $Return['error'] = $this->lang->line('xin_employee_error_contact_number');
			} */else if(empty($this->input->post('reporting_manager'))) {
				$Return['error'] = $this->lang->line('xin_employee_error_reporting_manager');
			} else if($this->input->post('status')==0) {
				if(empty($this->input->post('date_of_leaving'))) {
					$Return['error'] = $this->lang->line('xin_employee_error_leaving_date');
				}

			}
			$fname=$this->input->post('p_uploaded_file');
			if($_FILES['p_file']['size'] != 0 && is_uploaded_file($_FILES['p_file']['tmp_name'])) {
				//checking image type
				$allowed =  array('png','jpg','jpeg','pdf','gif');
				$filename = strtolower($_FILES['p_file']['name']);
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				if(in_array($ext,$allowed)){
					$tmp_name = $_FILES["p_file"]["tmp_name"];
					$profile = "uploads/profile/";
					$set_img = base_url()."uploads/profile/";
					$name = basename($_FILES["p_file"]["name"]);
					$newfilename = 'profile_'.round(microtime(true)).'.'.$ext;
					move_uploaded_file($tmp_name, $profile.$newfilename);
					$fname = $newfilename;
				} else {
					$Return['error'] = $this->lang->line('xin_employee_picture_type');
				}
			}
			if($Return['error']!=''){
				$this->output($Return);
			}
			$languages_known=@implode(',',$this->input->post('languages_known'));
			$data = array(
				'employee_id' => $this->input->post('employee_id'),
				'first_name' => change_fletter_caps($this->input->post('first_name')), // Given Name
				'middle_name' => change_fletter_caps($this->input->post('middle_name')),
				'last_name' => change_fletter_caps($this->input->post('last_name')),// Sur Name
				'company_id' => $this->input->post('company_id'),
				'spouse_name' => $this->input->post('spouse_name'),
				'email' => $this->input->post('email'),
				'personal_email' => $this->input->post('personal_email'),
				'date_of_birth' => format_date('Y-m-d',$this->input->post('date_of_birth')),
				'gender' => $this->input->post('gender'),
				'user_role_id' => $this->input->post('role'),
				'marital_status' => $this->input->post('marital_status'),
				'is_active' => $this->input->post('status'),
				'department_id' => $this->input->post('department_id'),
				'designation_id' => $this->input->post('designation_id'),
				'visa_occupation' => $this->input->post('visa_occupation'),
				'date_of_joining' => format_date('Y-m-d',$this->input->post('date_of_joining')),
				'date_of_leaving' => format_date('Y-m-d',$this->input->post('date_of_leaving')),
				'contact_no' => $this->input->post('country_code').$this->input->post('contact_no'),
				'profile_picture'=>$fname,
				'languages_known' => $languages_known,
				'address' => $this->input->post('address'),
				'address2' => $this->input->post('address2'),
				'city' => $this->input->post('city'),
				'area' => $this->input->post('area'),
				'zipcode' => $this->input->post('zipcode'),
				'home_country' => $this->input->post('home_country'),
				'nationality' => $this->input->post('nationality'),
				'residing_address1' => $this->input->post('residing_address1'),
				'residing_address2' => $this->input->post('residing_address2'),
				'residing_city' => $this->input->post('residing_city'),
				'residing_area' => $this->input->post('residing_area'),
				'residing_zipcode' => $this->input->post('residing_zipcode'),
				'residing_country' => $this->input->post('residing_country'),
				'tenure' => $this->input->post('tenure'),
				'reporting_manager' => $this->input->post('reporting_manager'),
				'office_location_id' => $this->input->post('office_location_id'),
				'currency_id' => $this->input->post('currency_id'),
				'working_hours' => $this->input->post('working_hours'),
				'blood_group' => $this->input->post('blood_group'),
				'company_transport' => $this->input->post('company_transport'),
				'is_break_included' => $this->input->post('is_break_included'),
				'send_punch_reminder' => $this->input->post('send_punch_reminder'),
			);
			$id = $this->input->post('user_id');


			$data = $this->security->xss_clean($data);

			$result = $this->Employees_model->basic_info($data,$id);

			/*User Logs*/
			$affected_id= table_update_id('xin_employees','user_id',$id);
			userlogs('Employees-Basic Info-Update','Employee Updated',$id,$affected_id['datas']);
			/*User Logs*/

			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_basic_info_updated');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	public function profile_picture() {

		if($this->input->post('type')=='profile_picture') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->input->post('user_id');

			/* Check if file uploaded..*/
			if($_FILES['p_file']['size'] == 0 && null ==$this->input->post('remove_profile_picture')) {
				$Return['error'] = $this->lang->line('xin_employee_select_picture');
			} else {
				if(is_uploaded_file($_FILES['p_file']['tmp_name'])) {
					//checking image type
					$allowed =  array('png','jpg','jpeg','pdf','gif');
					$filename = strtolower($_FILES['p_file']['name']);
					$ext = pathinfo($filename, PATHINFO_EXTENSION);

					if(in_array($ext,$allowed)){
						$tmp_name = $_FILES["p_file"]["tmp_name"];
						$profile = "uploads/profile/";
						$set_img = base_url()."uploads/profile/";
						$name = basename($_FILES["p_file"]["name"]);
						$newfilename = 'profile_'.round(microtime(true)).'.'.$ext;
						move_uploaded_file($tmp_name, $profile.$newfilename);
						$fname = $newfilename;

						//UPDATE Employee info in DB
						$data = array('profile_picture' => $fname);
						$result = $this->Employees_model->profile_picture($data,$id);

						/*User Logs*/
						$affected_id= table_update_id('xin_employees','user_id',$id);
						userlogs('Employees-Profile Picture-Update','Employee Profile Picture Updated',$id,$affected_id['datas']);
						/*User Logs*/

						if ($result == TRUE) {
							$Return['result'] = $this->lang->line('xin_employee_picture_updated');
							$Return['img'] = $set_img.$fname;
						} else {
							$Return['error'] = $this->lang->line('xin_error_msg');
						}
						$this->output($Return);
						exit;

					} else {
						$Return['error'] = $this->lang->line('xin_employee_picture_type');
					}
				}
			}

			if(null!=$this->input->post('remove_profile_picture')) {
				//UPDATE Employee info in DB
				$data = array('profile_picture' => 'no file');
				$row = $this->Employees_model->read_employee_information($id);
				$profile = base_url()."uploads/profile/";
				$result = $this->Employees_model->profile_picture($data,$id);

				if ($result == TRUE) {
					$Return['result'] = $this->lang->line('xin_employee_picture_updated');
					if($row[0]->gender=='Male') {
						$Return['img'] = $profile.'default_male.jpg';
					} else {
						$Return['img'] = $profile.'default_female.jpg';
					}
				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				$this->output($Return);
				exit;

			}

			if($Return['error']!=''){
				$this->output($Return);
			}
		}
	}

	public function social_info() {

		if($this->input->post('type')=='social_info') {
			$Return = array('result'=>'', 'error'=>'');

			$data = array(
				'facebook_link' => $this->input->post('facebook_link'),
				'twitter_link' => $this->input->post('twitter_link'),
				'blogger_link' => $this->input->post('blogger_link'),
				'linkdedin_link' => $this->input->post('linkdedin_link'),
				'google_plus_link' => $this->input->post('google_plus_link'),
				'instagram_link' => $this->input->post('instagram_link'),
				'pinterest_link' => $this->input->post('pinterest_link'),
				'youtube_link' => $this->input->post('youtube_link')
			);
			$id = $this->input->post('user_id');
			$result = $this->Employees_model->social_info($data,$id);

			/*User Logs*/
			$affected_id= table_update_id('xin_employees','user_id',$id);
			userlogs('Employees-Social Info-Update','Employee Social Info Updated',$id,$affected_id['datas']);
			/*User Logs*/

			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_success_update_social_info');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	public function contact_info() {

		if($this->input->post('type')=='contact_info') {
			$Return = array('result'=>'', 'error'=>'');

			if($this->input->post('contact_name')==='') {
				$Return['error'] = "The contact name field is required.";
			} else if($this->input->post('relation')==='') {
				$Return['error'] = "The relation field is required.";
			} else if($this->input->post('mobile_phone')==='') {
				$Return['error'] = "The mobile field is required.";
			} else if(!is_numeric($this->input->post('mobile_phone'))) {
				$Return['error'] = "Mobile number should be numeric.";
			}

			if(null!=$this->input->post('is_primary')) {
				$is_primary = $this->input->post('is_primary');
			} else {
				$is_primary = '';
			}
			if(null!=$this->input->post('is_secondary')) {
				$is_secondary = $this->input->post('is_secondary');
			} else {
				$is_secondary = '';
			}

			if($Return['error']!=''){
				$this->output($Return);
			}

			$data = array(
				'relation' => $this->input->post('relation'),
				'is_primary' => $is_primary,
				'is_secondary' => $is_secondary,
				'contact_name' => $this->input->post('contact_name'),
				'address_1' => $this->input->post('address_1'),
				'address_2' => $this->input->post('address_2'),
				'mobile_phone' => $this->input->post('country_code').$this->input->post('mobile_phone'),
				'city' => $this->input->post('city'),
				'state' => $this->input->post('state'),
				'zipcode' => $this->input->post('zipcode'),
				'country' => $this->input->post('country'),
				'employee_id' => $this->input->post('user_id'),
				'created_at' => date('d-m-Y'),
			);

			$result = $this->Employees_model->contact_info_add($data);

			/*User Logs*/
			$affected_id= table_max_id('xin_employee_contacts','contact_id');
			userlogs('Employees-Emergency Contact-Add','Employee Emergency Contact Added',$affected_id['field_id'],$affected_id['datas']);
			/*User Logs*/

			if ($result == TRUE) {
				$Return['result'] = 'Contact Information added.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function immigration_info() {
		if($this->input->post('type')=='immigration_info' && $this->input->post('data')=='immigration_info') {
			$Return = array('result'=>'', 'error'=>'');

			$fname=[];

			if($this->input->post('document_type_id')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_d_type');
			} /*else if($this->input->post('document_number')==='') {
			$document_type=$this->Employees_model->read_document_type_information($this->input->post('document_type_id'),'document_type');
			$Return['error'] = 'The '.$document_type[0]->type_name.' number field is required.';//$this->lang->line('xin_employee_error_d_number');
			} else if($this->input->post('issue_date')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_d_issue');
			} else if($this->input->post('expiry_date')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_expiry_date');
			} else if(empty($this->input->post('expiry_date'))==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_expiry_date');
			} */

			if($_FILES['document_file']['size'][0]!= 0) {
				//checking image type
				//$allowed =  array('png','jpg','jpeg','pdf','gif','txt','PDF','xls','xlsx','doc','docx');
				$allowed =  array('png','jpg','jpeg','pdf','gif');
				$count=count($_FILES['document_file']['name']);
				for($i=0;$i<$count;$i++){
					$filename = strtolower($_FILES['document_file']['name'][$i]);
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
					if(in_array($ext,$allowed)){
						$tmp_name = $_FILES["document_file"]["tmp_name"][$i];
						$documentd = "uploads/document/immigration/";
						$name = basename($_FILES["document_file"]["name"][$i]);
						$newfilename = 'document_'.round(microtime(true)).'_'.$i.'.'.$ext;
						move_uploaded_file($tmp_name, $documentd.$newfilename);
						$fname[] = $newfilename;

					}

				}


			}
			$fname=implode(',',$fname);

			if($this->input->post('document_type_id')==2){
				if($this->input->post('country')===''){
					$Return['error'] = $this->lang->line('xin_error_country_field');
				}
			} else if($this->input->post('document_type_id')==3){
				if($this->input->post('visacard')===''){
					$Return['error'] = 'Visa under field is required';
				}
			} else if($this->input->post('document_type_id')==6){
				if($this->input->post('medicalcard')===''){
					$Return['error'] = 'Medical card type field is required';
				}
			}

			if($Return['error']!=''){
				$this->output($Return);
			}

			if($this->input->post('visacard')!='') {
				$cardtype=$this->input->post('visacard');
			}else if($this->input->post('medicalcard')!='') {
				$cardtype=$this->input->post('medicalcard');
			}else{
				$cardtype='';
			}

			$visa_cancellation_cost=0;
			$visa_renewal_cost=0;
			$cost=0;
			$data = array(
				'document_type_id' => $this->input->post('document_type_id'),
				'document_number' => $this->input->post('document_number'),
				'document_file' => $fname,
				'issue_date' => format_date('Y-m-d',$this->input->post('issue_date')),
				'expiry_date' => format_date('Y-m-d',$this->input->post('expiry_date')),
				'country_id' => $this->input->post('country'),
				'eligible_review_date' => format_date('Y-m-d',$this->input->post('eligible_review_date')),
				'date_of_cancellation' => format_date('Y-m-d',$this->input->post('date_of_cancellation')),
				'type' => $cardtype,
				'cost' => $cost,
				'visa_renewal_cost' => $visa_renewal_cost,
				'visa_cancellation_cost' => $visa_cancellation_cost,
				'employee_id' => $this->input->post('user_id'),
				'created_at' => date('d-m-Y h:i:s'),
			);

			//	echo "<pre>";print_r($data);die;
			$result = $this->Employees_model->immigration_info_add($data);
			/*User Logs*/
			$affected_id= table_max_id('xin_employee_immigration','immigration_id');
			userlogs('Employees-Employee Document-Add','Employee Document Added',$affected_id['field_id'],$affected_id['datas']);
			/*User Logs*/

			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_img_info_added');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	public function qualification_info() {

		if($this->input->post('type')=='qualification_info') {
			$Return = array('result'=>'', 'error'=>'');

			$from_year = $this->input->post('from_year');
			$to_year = $this->input->post('to_year');
			$st_date = strtotime($from_year);
			$ed_date = strtotime($to_year);

			if($this->input->post('name')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_sch_uni');
			} else if($this->input->post('from_year')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_frm_year');
			} else if($this->input->post('to_year')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_to_year');
			} else if($st_date > $ed_date) {
				$Return['error'] = $this->lang->line('xin_employee_error_date_shouldbe');
			}

			if($Return['error']!=''){
				$this->output($Return);
			}

			$data = array(
				'name' => $this->input->post('name'),
				'education_level_id' => $this->input->post('education_level'),
				'from_year' => $this->input->post('from_year'),
				'language_id' => $this->input->post('language'),
				'to_year' => $this->input->post('to_year'),
				'skill_id' => $this->input->post('skill'),
				'description' => $this->input->post('description'),
				'employee_id' => $this->input->post('user_id'),
				'created_at' => date('d-m-Y'),
			);
			$result = $this->Employees_model->qualification_info_add($data);
			/*User Logs*/
			$affected_id= table_max_id('xin_employee_qualification','qualification_id');
			userlogs('Employees-Employee Qualification Info-Add','Employee Qualification Info added',$affected_id['field_id'],$affected_id['datas']);
			/*User Logs*/
			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_error_q_info_added');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	public function work_experience_info() {

		if($this->input->post('type')=='work_experience_info') {
			$Return = array('result'=>'', 'error'=>'');
			$frm_date = strtotime($this->input->post('from_date'));
			$to_date = strtotime($this->input->post('to_date'));

			if($this->input->post('company_name')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_company_name');
			} else if($this->input->post('post')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_post');
			} else if($this->input->post('from_date')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_frm_date');
			} else if(($this->input->post('to_date')==='') && (!$this->input->post('is_current_exp'))) {
				$Return['error'] = $this->lang->line('xin_employee_error_to_date');
			} else if(($frm_date > $to_date)  && (!$this->input->post('is_current_exp'))) {
				$Return['error'] = $this->lang->line('xin_employee_error_date_shouldbe');
			}

			if($Return['error']!=''){
				$this->output($Return);
			}

			if($this->input->post('is_current_exp')){
				$t_date='';
			}else{
				$t_date=format_date('Y-m',$this->input->post('to_date'));
			}
			$data = array(
				'company_name' => $this->input->post('company_name'),
				'from_date' => format_date('Y-m',$this->input->post('from_date')),
				'to_date' => $t_date,
				'post' => $this->input->post('post'),
				'description' => $this->input->post('description'),
				'employee_id' => $this->input->post('user_id'),
				'created_at' => date('d-m-Y'),
			);
			$result = $this->Employees_model->work_experience_info_add($data);

			/*User Logs*/
			$affected_id= table_max_id('xin_employee_work_experience','work_experience_id');
			userlogs('Employees-Employee Work Experience-Add','Employee Work Experience Added',$affected_id['field_id'],$affected_id['datas']);
			/*User Logs*/

			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_error_w_exp_added');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	public function bank_account_info() {

		if($this->input->post('type')=='bank_account_info') {
			$Return = array('result'=>'', 'error'=>'');
			if($this->input->post('account_number')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_acc_number');
			} else if(!ctype_alnum($this->input->post('account_number'))) {
				$Return['error'] = $this->lang->line('xin_employee_error_acc_number_valid');
			} else if($this->input->post('bank_name')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_bank_name');
			} else if($this->input->post('bank_code')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_bank_code');
			}

			if($Return['error']!=''){
				$this->output($Return);
			}

			$data = array(
				'account_title' => $this->input->post('account_title'),
				'account_number' => strtoupper($this->input->post('account_number')),
				'bank_name' => strtoupper($this->input->post('bank_name')),
				'bank_code' => strtoupper($this->input->post('bank_code')),
				'bank_branch' => strtoupper($this->input->post('bank_branch')),
				'employee_id' => $this->input->post('user_id'),
				'created_at' => date('d-m-Y'),
			);
			$result = $this->Employees_model->bank_account_info_add($data);
			/*User Logs*/
			$affected_id= table_max_id('xin_employee_bankaccount','bankaccount_id');
			userlogs('Employees-Employee Bank Account Info-Add','Employee Bank Account Info added',$affected_id['field_id'],$affected_id['datas']);
			/*User Logs*/
			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_error_bank_info_added');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	public function contract_info() {

		if($this->input->post('type')=='contract_info') {
			$Return = array('result'=>'', 'error'=>'');
			$frm_date = strtotime($this->input->post('from_date'));
			$to_date = strtotime($this->input->post('to_date'));

			$fname=[];

			if($this->input->post('contract_type_id')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_contract_type');
			} else if($this->input->post('from_date')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_frm_date');
			} else if($this->input->post('to_date')==='') {
				if($this->input->post('contract_type_id')!='18'){
					$Return['error'] = $this->lang->line('xin_employee_error_to_date');
				}
			} else if(($frm_date > $to_date) && $this->input->post('contract_type_id')!='18') {
				$Return['error'] = $this->lang->line('xin_employee_error_frm_to_date');
			}
			/*else if($this->input->post('designation_id')==='') {
			 $Return['error'] = $this->lang->line('xin_employee_error_designation');
			}*/

			if($_FILES['document_file']['size'][0]!=0) {
				//checking image type
				$allowed =  array('png','jpg','jpeg','pdf','gif');
				$count=count($_FILES['document_file']['name']);
				for($i=0;$i<$count;$i++){
					$filename = strtolower($_FILES['document_file']['name'][$i]);
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
					if(in_array($ext,$allowed)){
						$tmp_name = $_FILES["document_file"]["tmp_name"][$i];
						$documentd = "uploads/document/immigration/";
						$name = basename($_FILES["document_file"]["name"][$i]);
						$newfilename = 'document_'.round(microtime(true)).'_'.$i.'.'.$ext;
						move_uploaded_file($tmp_name, $documentd.$newfilename);
						$fname[] = $newfilename;
					}

				}

			}
			$fname=implode(',',$fname);
			if($Return['error']!=''){
				$this->output($Return);
			}

			$data = array(
				'contract_type_id' => $this->input->post('contract_type_id'),
				//'title' => $this->input->post('title'),
				'from_date' => format_date('Y-m-d',$this->input->post('from_date')),
				'to_date' => format_date('Y-m-d',$this->input->post('to_date')),
				//'designation_id' => $this->input->post('designation_id'),
				'description' => $this->input->post('description'),
				'document_file' => $fname,
				'employee_id' => $this->input->post('user_id'),
				'created_at' => date('d-m-Y'),
			);
			$result = $this->Employees_model->contract_info_add($data);

			/*User Logs*/
			$affected_id= table_max_id('xin_employee_contract','contract_id');
			userlogs('Employees-Employee Contract-Add','Employee Contract Added',$affected_id['field_id'],$affected_id['datas']);
			/*User Logs*/


			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_contract_info_added');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	public function change_password() {

		if($this->input->post('type')=='change_password') {
			$Return = array('result'=>'', 'error'=>'');

			if(trim($this->input->post('new_password'))==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_newpassword');
			} else if($this->valid_password($this->input->post('new_password'))!='') {
				$Return['error'] = $this->valid_password($this->input->post('new_password'));
			}
			/*else if(strlen($this->input->post('new_password')) < 6) {
                $Return['error'] = $this->lang->line('xin_employee_error_password_least');
            } */else if(trim($this->input->post('new_password_confirm'))==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_new_cpassword');
			} else if($this->input->post('new_password')!=$this->input->post('new_password_confirm')) {
				$Return['error'] = $this->lang->line('xin_employee_error_old_new_cpassword');
			}

			if($Return['error']!=''){
				$this->output($Return);
			}

			$data = array(
				'password' => md5($this->input->post('new_password'))
			);
			if($this->input->post('email')){
				$email = $this->input->post('email');
				$query_ml = $this->db->query("select user_id from xin_employees as emp where emp.email='".$email."' limit 1");
				$result_ml= $query_ml->result();

				if($result_ml){
					$id=$result_ml[0]->user_id;
				}
			}else{
				$id = $this->input->post('user_id');
			}

			$result = $this->Employees_model->change_password($data,$id);
			/*User Logs*/
			$affected_id= table_update_id('xin_employees','user_id',$id);
			userlogs('Employees-Employee Password-Update','Employee Password Updated',$id,$affected_id['datas']);
			/*User Logs*/

			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_password_update');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	public function valid_password($password = '')
	{
		$password = trim($password);
		$regex_lowercase = '/[a-z]/';
		$regex_uppercase = '/[A-Z]/';
		$regex_number = '/[0-9]/';
		$regex_special = '/[!@#$%^&*()\-_=+{};:,<.>§~]/';
		if (preg_match_all($regex_lowercase, $password) < 1)
		{
			return 'The field must be at least one lowercase letter.';
		}
		if (preg_match_all($regex_uppercase, $password) < 1)
		{
			return 'The field must be at least one uppercase letter.';
		}
		if (preg_match_all($regex_number, $password) < 1)
		{
			return 'The field must have at least one number.';
		}
		if (preg_match_all($regex_special, $password) < 1)
		{
			$this->form_validation->set_message('valid_password', 'The {field} field must have at least one special character.' . ' ' . htmlentities('!@#$%^&*()\-_=+{};:,<.>§~'));
			return FALSE;
		}
		if (strlen($password) < 6)
		{
			return 'The field must be at least 6 characters in length.';
		}
		if (strlen($password) > 32)
		{
			return 'The {field} field cannot exceed 32 characters in length.';
		}
		return '';
	}

	public function contacts()
	{
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("employees/employee_detail", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));

		$id = $this->uri->segment(3);
		$contacts = $this->Employees_model->set_employee_contacts($id);

		$data = array();

		foreach($contacts->result() as $r) {

			if($r->is_primary==1){
				$primary = '<span class="ml-10 label label-success">'.$this->lang->line('xin_employee_primary').'</span>';
			} else {
				$primary = '';
			}
			if($r->is_secondary==1){
				$secondary = '<span class="ml-10 label label-info">'.$this->lang->line('xin_employee_secondary').'</span>';
			} else {
				$secondary = '';
			}
			$edit_perm='';
			$delete_perm='';
			$view_perm='';

			if(in_array('emergency-contacts-edit',role_resource_ids())) {
				$edit_perm='<li><a data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->contact_id . '" data-field_type="contact" data-field_role="edit"><i class="icon-pencil7"></i> Edit</a></li>';
			}
			if(in_array('emergency-contacts-delete',role_resource_ids())) {
				$delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->contact_id . '" data-token_type="contact"><i class="icon-trash"></i> Delete</a></li>';
			}

			if(in_array('emergency-contacts-view',role_resource_ids()) || visa_wise_role_ids() != '') {
				$view_perm='<li><a data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->contact_id . '" data-field_type="contact" data-field_role="view"><i class="icon-eye4"></i> View</a></li>';
			}

			if($r->city!=''){$city=$r->city;}else{$city='-';}

			$data[] = array(
				$r->contact_name . ' ' .$primary . ' '.$secondary,
				$r->relation,
				$city,
				$r->mobile_phone,
				'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$view_perm.$delete_perm.'</ul></li></ul>',
			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $contacts->num_rows(),
			"recordsFiltered" => $contacts->num_rows(),
			"data" => $data
		);
		$this->output($output);
	}

	public function immigration() {
		//set data
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("employees/employee_detail", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));

		$id = $this->uri->segment(3);
		$offer_letter_id = $this->uri->segment(4);

		if($offer_letter_id!=''){
			$d_types = 'offerletter';
		}else{
			$d_types = 'imgdocument';
		}

		$immigration = $this->Employees_model->set_employee_immigration($id,$offer_letter_id);
		$data = array();
		foreach($immigration->result() as $r) {
			$issue_date = $this->Xin_model->set_date_format($r->issue_date);
			$expiry_date = $this->Xin_model->set_date_format($r->expiry_date);
			$eligible_review_date = $this->Xin_model->set_date_format($r->eligible_review_date);
			$date_of_cancellation = $this->Xin_model->set_date_format($r->date_of_cancellation);

			if($r->country_id!=''){
				$country_id = $this->Xin_model->read_country_info($r->country_id);
				$country_name=$country_id[0]->country_name;
			}else{
				$country_name='';

			}

			if($r->document_number!=''){$doc_no='<br>('.$r->document_number.')';}else{$doc_no='';}

			$d_type = $this->Employees_model->read_document_type_information($r->document_type_id,'document_type');
			$document_d = $d_type[0]->type_name.$doc_no;
			$get_type_name=$this->Employees_model->get_type_name($r->type);

			if($r->type!='0'){$type='<tr><td>Type<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.@$get_type_name.'</td><tr>';	}else{$type='';}
			if($r->cost!=0){$cost='<tr><td>Visa Cost<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.@$this->Xin_model->currency_sign($r->cost,'',$r->employee_id).'</td><tr>';}else{$cost='';}
			if($r->visa_renewal_cost!=0){$visa_renew='<tr><td>Visa Renewal Cost<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.@$this->Xin_model->currency_sign($r->visa_renewal_cost,'',$r->employee_id).'</td><tr>';}else{$visa_renew='';}
			if($r->visa_cancellation_cost!=0){$visa_cancell='<tr><td>Visa Cancellation Cost<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.@$this->Xin_model->currency_sign($r->visa_cancellation_cost,'',$r->employee_id).'</td><tr>';}else{$visa_cancell='';}
			if($r->eligible_review_date!=''){$visa_stamped_date='<tr><td>Visa Stamped Date<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.@$eligible_review_date.'</td><tr>';}else {$visa_stamped_date='';}
			if($r->date_of_cancellation!=''){$date_of_cancellation='<tr><td>Visa Cancellation Date<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.@$date_of_cancellation.'</td><tr>';}else{$date_of_cancellation='';}
			if($country_name!=''){$ctry='<tr><td>Passport Issued Country<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.@$country_name.'</td><tr>';	}else{$ctry='';}


			if($type!='' || $cost!='' || $visa_renew!='' || $visa_cancell!='' ||  $visa_stamped_date!='' || $date_of_cancellation!='' || $country_name!=''){
				$document_d1='<i style="cursor:pointer;" data-html="true" class="icon-bubble-lines3 text-teal-400" data-popup="popover-custom" data-placement="bottom" title="" data-trigger="hover" data-content="<table>'.@$type.@$cost.@$visa_renew.@$visa_cancell.@$visa_stamped_date.@$date_of_cancellation.$ctry.'<table>"></i>';
			}else{
				$document_d1='N/A';
			}

			$edit_perm='';
			$delete_perm='';
			$view_perm='';	

			if(in_array('documents-edit',role_resource_ids()) || in_array('offer-letter-edit',role_resource_ids())) {
				$edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->immigration_id . '" data-field_type="imgdocument" data-field_role="edit"><i class="icon-pencil7"></i> Edit</a></li>';
			}
			if(in_array('documents-delete',role_resource_ids()) || in_array('offer-letter-delete',role_resource_ids())) {
				$delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->immigration_id . '" data-token_type="'.$d_types.'"><i class="icon-trash"></i> Delete</a></li>';
			}

			if(in_array('documents-view',role_resource_ids()) || in_array('offer-letter-view',role_resource_ids()) || visa_wise_role_ids() != '') {
				$view_perm='<li><a href="#" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->immigration_id . '" data-field_type="imgdocument" data-field_role="view"><i class="icon-eye4"></i> View</a></li>';
			}



			if($r->document_file!='' && $r->document_file!='no file') {
				$docu=explode(',',$r->document_file);
				$file_parts = pathinfo($docu[0]);
				if($file_parts['extension']!='pdf'){
					$document_file='<a href="#" data-toggle="modal" data-target=".down-modal-data" data-field_id="'. $r->immigration_id . '" data-field_type="document">
					<img src="'.base_url().'uploads/document/immigration/'.$docu[0].'" width="100" style="border: 1px solid #ddd;padding: 10px;"/></a>';
				}else{
					$document_file='<a href="#" data-toggle="modal" data-target=".down-modal-data" data-field_id="'. $r->immigration_id . '" data-field_type="document">
					<img src="'.base_url().'uploads/pdf-preview.jpg" width="100" style="border: 1px solid #ddd;padding: 10px;"/></a>';
				}
			}else{
				$document_file='Not Yet Uploaded';
			}
			$data[] = array(
				$document_d,
				$issue_date,
				$expiry_date,
				$document_d1,
				$document_file,
				'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$view_perm.$delete_perm.'</ul></li></ul>',
			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $immigration->num_rows(),
			"recordsFiltered" => $immigration->num_rows(),
			"data" => $data
		);
		$this->output($output);
	}

	public function qualification() {
		$data['title'] = $this->Xin_model->site_title();

		if(!empty($this->userSession)){
			$this->load->view("employees/employee_detail", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));

		$id = $this->uri->segment(3);
		$qualification = $this->Employees_model->set_employee_qualification($id);

		$data = array();
		foreach($qualification->result() as $r) {

			$education = $this->Employees_model->read_education_information($r->education_level_id);

			$sdate = $r->from_year;
			$edate = $r->to_year;

			$time_period = $sdate.' - '.$edate;
			// get date
			$pdate = $time_period;

			$edit_perm='';
			$delete_perm='';
			$view_perm='';
			if(in_array('qualification-edit',role_resource_ids())) {
				$edit_perm='<li><a data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->qualification_id . '" data-field_type="qualification" data-field_role="edit"><i class="icon-pencil7"></i> Edit</a></li>';
			}
			if(in_array('qualification-delete',role_resource_ids())) {
				$delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->qualification_id . '" data-token_type="qualification"><i class="icon-trash"></i> Delete</a></li>';
			}

			if(in_array('qualification-view',role_resource_ids()) || visa_wise_role_ids() != '') {
				$view_perm='<li><a data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->qualification_id . '" data-field_type="qualification" data-field_role="view"><i class="icon-eye4"></i> View</a></li>';
			}

			$data[] = array(
				$r->name,
				$pdate,
				$education[0]->type_name,
				'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$view_perm.$delete_perm.'</ul></li></ul>',
			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $qualification->num_rows(),
			"recordsFiltered" => $qualification->num_rows(),
			"data" => $data
		);
		$this->output($output);
	}

	public function experience() {
		$data['title'] = $this->Xin_model->site_title();

		if(!empty($this->userSession)){
			$this->load->view("employees/employee_detail", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));

		$id = $this->uri->segment(3);
		$experience = $this->Employees_model->set_employee_experience($id);

		$data = array();

		foreach($experience->result() as $r) {

			$from_date = format_date('F Y',$r->from_date);
			if($r->to_date!=''){
				$to_date = format_date('F Y',$r->to_date);
			}else{$to_date='Current';}
			$edit_perm='';
			$delete_perm='';
			$view_perm='';

			if(in_array('work-experience-edit',role_resource_ids())) {
				$edit_perm='<li><a data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->work_experience_id . '" data-field_type="work_experience" data-field_role="edit"><i class="icon-pencil7"></i> Edit</a></li>';
			}
			if(in_array('work-experience-delete',role_resource_ids())) {
				$delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->work_experience_id . '" data-token_type="work_experience"><i class="icon-trash"></i> Delete</a></li>';
			}

			if(in_array('work-experience-view',role_resource_ids()) || visa_wise_role_ids() != '') {
				$view_perm='<li><a data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->work_experience_id . '" data-field_type="work_experience" data-field_role="view"><i class="icon-eye4"></i> View</a></li>';
			}

			if(trim($r->description)!=''){
				$description=$r->description;
			}else{$description ='<div align="center">-</div>';}
			$data[] = array(
				$r->company_name,
				$from_date,
				$to_date,
				$r->post,
				$description,
				'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$view_perm.$delete_perm.'</ul></li></ul>',
			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $experience->num_rows(),
			"recordsFiltered" => $experience->num_rows(),
			"data" => $data
		);
		$this->output($output);
	}

	public function bank_account() {
		$data['title'] = $this->Xin_model->site_title();

		if(!empty($this->userSession)){
			$this->load->view("employees/employee_detail", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));

		$id = $this->uri->segment(3);
		$bank_account = $this->Employees_model->set_employee_bank_account($id);

		$data = array();
		foreach($bank_account->result() as $r) {
			$is_primary='';
			$edit_perm='';
			$view_perm='';
			if(in_array('bank-account-edit',role_resource_ids())) {
				$edit_perm='<li><a data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->bankaccount_id . '" data-field_type="bank_account" data-field_role="edit"><i class="icon-pencil7"></i> Edit</a></li>';
				$is_primary='<li><a class="default-account" data-user_updated_id="'. $r->employee_id.'" data-bank_account_id="'. $r->bankaccount_id . '"><i class="icon-pushpin"></i> Make Default Account</a></li>';
			}

			if(in_array('bank-account-view',role_resource_ids()) || visa_wise_role_ids() != '') {
				$view_perm='<li><a data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->bankaccount_id . '" data-field_type="bank_account"  data-field_role="view"><i class="icon-eye4"></i> View</a></li>';
			}

			$functions='<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$view_perm.$is_primary.'</ul></li></ul>';

			if($r->is_primary==1){
				$success = '<span class="label ml-5 label-success"">default</span>';
			} else {
				$success = '';
			}

			$data[] = array(
				strtoupper($r->account_number). ' ' .$success,
				strtoupper($r->bank_name),
				strtoupper($r->bank_code),
				strtoupper($r->bank_branch),
				$functions,
			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $bank_account->num_rows(),
			"recordsFiltered" => $bank_account->num_rows(),
			"data" => $data
		);
		$this->output($output);
	}

	public function default_bank_account() {

		if($this->input->get('bankaccount_id')) {

			$bankaccount_id = $this->input->get('bankaccount_id');
			$employee_id = $this->input->get('employee_id');

			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');

			$data = array(
				'is_primary' => '0',
				'employee_id' =>$employee_id
			);

			$data2 = array(
				'is_primary' => '1',
				'employee_id' =>$employee_id
			);

			$result = $this->Employees_model->update_default_bankaccount($data,'');
			$result = $this->Employees_model->update_default_bankaccount($data2,$bankaccount_id);

			/*User Logs*/
			$affected_id= table_update_id('xin_employee_bankaccount','bankaccount_id',$bankaccount_id);
			userlogs('Employees-Employee Bank Account Info-Update','Employee Bank Default Account Updated',$bankaccount_id,$affected_id['datas']);
			/*User Logs*/
			if ($result == TRUE) {
				$Return['result'] = 'Bank Account made default';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function contract() {
		$data['title'] = $this->Xin_model->site_title();

		if(!empty($this->userSession)){
			$this->load->view("employees/employee_detail", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));

		$id = $this->uri->segment(3);
		$contract = $this->Employees_model->set_employee_contract($id);

		$data = array();

		foreach($contract->result() as $r) {
			$contract_type = $this->Employees_model->read_document_type_information($r->contract_type_id,'contract_type');
			// date
			$duration = $this->Xin_model->set_date_format($r->from_date).' '.$this->lang->line('dashboard_to').' '.$this->Xin_model->set_date_format($r->to_date);


			$edit_perm='';
			$delete_perm='';
			$view_perm='';
			if(in_array('contract-edit',role_resource_ids())) {
				$edit_perm='<li><a data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->contract_id . '" data-field_type="contract" data-field_role="edit"><i class="icon-pencil7"></i> Edit</a></li>';
			}

			if(in_array('contract-delete',role_resource_ids())) {
				$delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->contract_id . '" data-token_type="contract"><i class="icon-trash"></i> Delete</a></li>';
			}

			if(in_array('contract-view',role_resource_ids()) || visa_wise_role_ids() != '') {
				$view_perm='<li><a data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->contract_id . '" data-field_type="contract" data-field_role="view"><i class="icon-eye4"></i> View</a></li>';
			}

			if($r->document_file!='' && $r->document_file!='no file') {


				$docu=explode(',',$r->document_file);

				$file_parts = pathinfo($docu[0]);
				if($file_parts['extension']!='pdf'){
					$document_file='<a href="#" data-toggle="modal" data-target=".down-modal-data" data-field_id="'. $r->contract_id . '" data-field_type="contract">
			<img src="'.base_url().'uploads/document/immigration/'.$docu[0].'" width="100" style="border: 1px solid #ddd;padding: 10px;"/></a>';
				}else{
					$document_file='<a href="#" data-toggle="modal" data-target=".down-modal-data" data-field_id="'. $r->contract_id . '" data-field_type="contract">
			<img src="'.base_url().'uploads/pdf-preview.jpg" width="100" style="border: 1px solid #ddd;padding: 10px;"/></a>';
				}


			}else{
				$document_file='Not Yet Uploaded';
			}
			$data[] = array(
				$duration,
				$contract_type[0]->type_name,
				$document_file,
				'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$view_perm.$delete_perm.'</ul></li></ul>',
			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $contract->num_rows(),
			"recordsFiltered" => $contract->num_rows(),
			"data" => $data
		);
		$this->output($output);
	}

	public function check_visa_under(){
		$user_id=$_GET['user_id'];
		$return_rows=$this->Employees_model->check_visa_under($user_id);
		echo $return_rows;
	}

	/* Add Operations */

	public function add_employee() {

		if(empty($this->userSession)){
			redirect('');
		}

		if($this->input->post('add_type')=='employee') {
			$Return = array('result'=>'', 'error'=>'', 'message'=>'');

			$check_email = $this->Employees_model->check_email(addslashes($this->input->post('email')),'');
			$check_employeeid = $this->Employees_model->check_employeeid($this->input->post('employee_id'),'');

			if($this->input->post('first_name')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_first_name');
			} else if($this->input->post('employee_id')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_employee_id');
			} else if($check_employeeid!=0){
				$Return['error'] = $this->lang->line('xin_employee_error_empid_exist');
			} else if(empty($this->input->post('date_of_joining'))) {
				$Return['error'] = $this->lang->line('xin_employee_error_joining_date');
			} else if(empty($this->input->post('department_id'))) {
				$Return['error'] = $this->lang->line('xin_employee_error_department');
			} else if(empty($this->input->post('designation_id'))) {
				$Return['error'] = $this->lang->line('xin_employee_error_designation');
			} else if($this->input->post('office_location_id')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_location');
			} else if($this->input->post('nationality')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_nationality');
			} else if(empty($this->input->post('email'))) {
				$Return['error'] = $this->lang->line('xin_employee_error_email');
			}
			else if($check_email!=0){
				$Return['error'] = $this->lang->line('xin_employee_error_useremail_exist');
			}
			else if (!filter_var($this->input->post('email'), FILTER_VALIDATE_EMAIL)) {
				$Return['error'] = $this->lang->line('xin_employee_error_invalid_email');
			}
			else if(empty($this->input->post('contact_no'))) {
				$Return['error'] = $this->lang->line('xin_employee_error_contact_number');
			}
			else if(empty($this->input->post('company_id'))) {
				$Return['error'] = 'Company name field is required.';
			}


			if($Return['error']!=''){
				$this->output($Return);
			}
			$new_password=randomPassword();

			$data = array(
				'employee_id' => $this->input->post('employee_id'),
				'office_location_id' => $this->input->post('office_location_id'),
				'first_name' => change_fletter_caps($this->input->post('first_name')), // Given Name
				'middle_name' => change_fletter_caps($this->input->post('middle_name')),// Middle Name
				'last_name' => change_fletter_caps($this->input->post('last_name')),// SurName
				'company_id' => change_to_caps($this->input->post('company_id')),
				'marital_status' => $this->input->post('marital_status'),
				'email' => $this->input->post('email'),
				'password' => $new_password,
				'date_of_birth' => format_date('Y-m-d',$this->input->post('date_of_birth')),
				'gender' => $this->input->post('gender'),
				'department_id' => $this->input->post('department_id'),
				'designation_id' => $this->input->post('designation_id'),
				'visa_occupation' => $this->input->post('visa_occupation'),
				'date_of_joining' => format_date('Y-m-d',$this->input->post('date_of_joining')),
				'contact_no' => $this->input->post('country_code').$this->input->post('contact_no'),
				'address' => $this->input->post('address'),
				'address2' => $this->input->post('address2'),
				'city' => $this->input->post('city'),
				'area' => $this->input->post('area'),
				'zipcode' => $this->input->post('zipcode'),
				'home_country' => $this->input->post('home_country'),
				'nationality' => $this->input->post('nationality'),
				'residing_address1' => $this->input->post('residing_address1'),
				'residing_address2' => $this->input->post('residing_address2'),
				'residing_city' => $this->input->post('residing_city'),
				'residing_area' => $this->input->post('residing_area'),
				'residing_zipcode' => $this->input->post('residing_zipcode'),
				'residing_country' => $this->input->post('residing_country'),
				'created_at' => date('d-m-Y'),
				'is_active' => 1,
				'currency_id'=>180
			);

			$data = $this->security->xss_clean($data);
			$result = $this->Employees_model->add($data);

			/*User Logs*/
			$affected_id= table_max_id('xin_employees','user_id');
			userlogs('Employees-Basic Info-Add','New Employee Added',$affected_id['field_id'],$affected_id['datas']);
			/*User Logs*/

			if ($result == TRUE) {

				if(email_notification('welcome_email')== 'yes') {
					$cinfo = $this->Xin_model->read_company_setting_info(1);
					$template = $this->Xin_model->read_email_template(8);
					$subject = $template[0]->subject.' - '.$cinfo[0]->company_name;
					$full_name = change_fletter_caps($this->input->post('first_name')).' '.change_fletter_caps($this->input->post('middle_name')).' '.change_fletter_caps($this->input->post('last_name'));
					$site_url=str_replace('http://','',str_replace('https://','',base_url().'setup_password?email='.urlencode($this->input->post('email')).'&type=new&uniqueid='.strtotime(date('Y-m-d')).'&randomid='.$new_password));
					$message = '<div>'.str_replace(array(
							"{var site_url}",
							"{var employee_name}",
							"{var year}"),
							array(
								$site_url,
								$full_name,
								date('Y')),
							htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';
					if(TESTING_MAIL==TRUE){
						$this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
						$this->email->to(TO_MAIL);
					}else{
						$this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
						$this->email->to($this->input->post('email'));
					}
					$this->email->subject($subject);
					$this->email->message($message);
					if($this->email->send()){
						$Return['result'] = $this->lang->line('xin_success_add_employee');
					}else{
						$Return['result'] = $this->lang->line('xin_success_add_employee_error');
					}
				}else{
					$Return['result'] = $this->lang->line('xin_success_add_employee_error');
				}
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	public function add_existing_work_experience(){
		$result = $this->Employees_model->get_employees();
		foreach ($result->result() as $employee){
			$awokExperience = $this->Employees_model->get_work_experience_for_awok($employee->user_id);
			if(!$awokExperience->result()){
				$designation = $this->Designation_model->read_designation_information($employee->designation_id);
				$doj = DateTime::createFromFormat("Y-m-d",$employee->date_of_joining)->format("Y-m");
				$data = [
					'employee_id'=>$employee->user_id,
					'company_name'=>'AWOK',
					'from_date'=>$doj,
					'post'=>$designation[0]->designation_name,
					'created_at' => date('d-m-Y'),
				];
				$this->Employees_model->work_experience_info_add($data);
			}
		}
	}

	/* Add Operations */

	/* Edit Operations */

	public function e_contact_info() {

		if($this->input->post('type')=='e_contact_info') {
			$Return = array('result'=>'', 'error'=>'');

			if($this->input->post('relation')==='') {
				$Return['error'] = "The relation field is required.";
			} else if($this->input->post('contact_name')==='') {
				$Return['error'] = "The contact name field is required.";
			} else if($this->input->post('mobile_phone')==='') {
				$Return['error'] = "The mobile field is required.";
			} else if(!is_numeric($this->input->post('mobile_phone'))) {
				$Return['error'] = "Mobile number should be numeric.";
			}

			if(null!=$this->input->post('is_primary')) {
				$is_primary = $this->input->post('is_primary');
			} else {
				$is_primary = '';
			}
			if(null!=$this->input->post('is_secondary')) {
				$is_secondary = $this->input->post('is_secondary');
			} else {
				$is_secondary = '';
			}

			if($Return['error']!=''){
				$this->output($Return);
			}

			$data = array(
				'relation' => $this->input->post('relation'),
				'is_primary' => $is_primary,
				'is_secondary' => $is_secondary,
				'contact_name' => $this->input->post('contact_name'),
				'address_1' => $this->input->post('address_1'),
				'address_2' => $this->input->post('address_2'),
				'mobile_phone' => $this->input->post('country_code').$this->input->post('mobile_phone'),
				'city' => $this->input->post('city'),
				'state' => $this->input->post('state'),
				'zipcode' => $this->input->post('zipcode'),
				'country' => $this->input->post('country')
			);

			$e_field_id = $this->input->post('e_field_id');
			$result = $this->Employees_model->contact_info_update($data,$e_field_id);

			/*User Logs*/
			$affected_id= table_update_id('xin_employee_contacts','contact_id',$e_field_id);
			userlogs('Employees-Emergency Contact-Update','Employee Emergency Contact Updated',$e_field_id,$affected_id['datas']);
			/*User Logs*/

			if ($result == TRUE) {
				$Return['result'] = 'Contact Information updated.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function e_qualification_info() {

		if($this->input->post('type')=='e_qualification_info') {
			$Return = array('result'=>'', 'error'=>'');

			$from_year = $this->input->post('from_year');
			$to_year = $this->input->post('to_year');
			$st_date = strtotime($from_year);
			$ed_date = strtotime($to_year);

			if($this->input->post('name')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_sch_uni');
			} else if($this->input->post('from_year')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_frm_year');
			} else if($this->input->post('to_year')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_to_year');
			} else if($st_date > $ed_date) {
				$Return['error'] = $this->lang->line('xin_employee_error_date_shouldbe');
			}

			if($Return['error']!=''){
				$this->output($Return);
			}

			$data = array(
				'name' => $this->input->post('name'),
				'education_level_id' => $this->input->post('education_level'),
				'from_year' => $this->input->post('from_year'),
				'language_id' => $this->input->post('language'),
				'to_year' => $this->input->post('to_year'),
				'skill_id' => $this->input->post('skill'),
				'description' => $this->input->post('description')
			);
			$e_field_id = $this->input->post('e_field_id');
			$result = $this->Employees_model->qualification_info_update($data,$e_field_id);

			/*User Logs*/
			$affected_id= table_update_id('xin_employee_qualification','qualification_id',$e_field_id);
			userlogs('Employees-Employee Qualification Info-Update','Employee Qualification Info updated',$e_field_id,$affected_id['datas']);
			/*User Logs*/


			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_error_q_info_updated');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	public function e_immigration_info() {
		$result = TRUE;
		if($this->input->post('type')=='e_immigration_info' && $this->input->post('data')=='e_immigration_info') {
			$Return = array('result'=>'', 'error'=>'');
			if($this->input->post('visacard')!='') {
				$cardtype=$this->input->post('visacard');
			}else if($this->input->post('medicalcard')!='') {
				$cardtype=$this->input->post('medicalcard');
			}else{
				$cardtype='';
			}

			$visa_cancellation_cost=0;
			$visa_renewal_cost=0;
			$cost=0;

			if($_FILES['document_file']['size'][0]== 0) {
				$data = array(
					'document_type_id' => $this->input->post('document_type_id'),
					'document_number' => $this->input->post('document_number'),
					'issue_date' => format_date('Y-m-d',$this->input->post('issue_date')),
					'expiry_date' => format_date('Y-m-d',$this->input->post('expiry_date')),
					'country_id' => $this->input->post('country'),
					'eligible_review_date' => format_date('Y-m-d',$this->input->post('eligible_review_date')),
					'date_of_cancellation' => format_date('Y-m-d',$this->input->post('date_of_cancellation')),
					'type' => $cardtype,
					'cost' => $cost,
					'visa_renewal_cost' => $visa_renewal_cost,
					'visa_cancellation_cost' => $visa_cancellation_cost,
				);
				$e_field_id = $this->input->post('e_field_id');
				$result = $this->Employees_model->img_document_info_update($data,$e_field_id);

				/*User Logs*/
				$affected_id= table_update_id('xin_employee_immigration','immigration_id',$e_field_id);
				userlogs('Employees-Employee Document-Update','Employee Document Updated',$e_field_id,$affected_id['datas']);
				/*User Logs*/

				if ($result == TRUE) {
					$Return['result'] = $this->lang->line('xin_employee_img_info_updated');
				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				$this->output($Return);
				exit;
			} else {
				if(isset($_FILES['document_file'])) {
					//checking image type
					$allowed =  array('png','jpg','jpeg','pdf','gif');
					$count=count($_FILES['document_file']['name']);
					for($i=0;$i<$count;$i++){
						$filename = strtolower($_FILES['document_file']['name'][$i]);
						$ext = pathinfo($filename, PATHINFO_EXTENSION);
						if(in_array($ext,$allowed)){
							$tmp_name = $_FILES["document_file"]["tmp_name"][$i];
							$documentd = "uploads/document/immigration/";
							$name = basename($_FILES["document_file"]["name"][$i]);
							$newfilename = 'document_'.round(microtime(true)).'_'.$i.'.'.$ext;
							move_uploaded_file($tmp_name, $documentd.$newfilename);
							$fname[] = $newfilename;
						}
					}

					$fname=implode(',',$fname);
					$fname=$fname.','.$this->input->post('document_file_old');
					$data = array(
						'document_type_id' => $this->input->post('document_type_id'),
						'document_number' => $this->input->post('document_number'),
						'document_file' => $fname,
						'issue_date' => format_date('Y-m-d',$this->input->post('issue_date')),
						'expiry_date' => format_date('Y-m-d',$this->input->post('expiry_date')),
						'country_id' => $this->input->post('country'),
						'eligible_review_date' => format_date('Y-m-d',$this->input->post('eligible_review_date')),
						'date_of_cancellation' => format_date('Y-m-d',$this->input->post('date_of_cancellation')),
						'type' => $cardtype,
						'cost' => $cost,
						'visa_renewal_cost' => $visa_renewal_cost,
						'visa_cancellation_cost' => $visa_cancellation_cost,

					);
					$e_field_id = $this->input->post('e_field_id');
					$result = $this->Employees_model->img_document_info_update($data,$e_field_id);
					/*User Logs*/
					$affected_id= table_update_id('xin_employee_immigration','immigration_id',$e_field_id);
					userlogs('Employees-Employee Document-Update','Employee Document Updated With Document',$e_field_id,$affected_id['datas']);
					/*User Logs*/

					if ($result == TRUE) {
						$Return['result'] = $this->lang->line('xin_employee_d_info_updated');
					} else {
						$Return['error'] = $this->lang->line('xin_error_msg');
					}
					$this->output($Return);
					exit;
				}
			}

			if($Return['error']!=''){
				$this->output($Return);
			}

			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_img_info_added');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	public function e_work_experience_info() {
		if($this->input->post('type')=='e_work_experience_info') {
			$Return = array('result'=>'', 'error'=>'');
			$frm_date = strtotime($this->input->post('from_date'));
			$to_date = strtotime($this->input->post('to_date'));
			/* Server side PHP input validation */
			if($this->input->post('company_name')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_company_name');
			} else if($this->input->post('post')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_post');
			} else if($this->input->post('from_date')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_frm_date');
			} else if(($this->input->post('to_date')==='')  && (!$this->input->post('is_current_exp'))) {
				$Return['error'] = $this->lang->line('xin_employee_error_to_date');
			} else if(($frm_date > $to_date) && (!$this->input->post('is_current_exp'))) {
				$Return['error'] = $this->lang->line('xin_employee_error_date_shouldbe');
			}

			if($Return['error']!=''){
				$this->output($Return);
			}

			if($this->input->post('is_current_exp')){
				$t_date='';
			}else{
				$t_date=format_date('Y-m',$this->input->post('to_date'));
			}

			$data = array(
				'company_name' => $this->input->post('company_name'),
				'from_date' => format_date('Y-m',$this->input->post('from_date')),
				'to_date' => $t_date,
				'post' => $this->input->post('post'),
				'description' => $this->input->post('description')
			);
			$e_field_id = $this->input->post('e_field_id');
			$result = $this->Employees_model->work_experience_info_update($data,$e_field_id);
			/*User Logs*/
			$affected_id= table_update_id('xin_employee_work_experience','work_experience_id',$e_field_id);
			userlogs('Employees-Employee Work Experience-Update','Employee Work Experience Updated',$e_field_id,$affected_id['datas']);
			/*User Logs*/
			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_error_w_exp_updated');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	public function e_bank_account_info() {

		if($this->input->post('type')=='e_bank_account_info') {
			$Return = array('result'=>'', 'error'=>'');

			if($this->input->post('account_number')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_acc_number');
			} else if(!ctype_alnum($this->input->post('account_number'))) {
				$Return['error'] = $this->lang->line('xin_employee_error_acc_number_valid');
			} else if($this->input->post('bank_name')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_bank_name');
			} else if($this->input->post('bank_code')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_bank_code');
			}

			if($Return['error']!=''){
				$this->output($Return);
			}

			$data = array(
				'account_title' => $this->input->post('account_title'),
				'account_number' => strtoupper($this->input->post('account_number')),
				'bank_name' => strtoupper($this->input->post('bank_name')),
				'bank_code' => strtoupper($this->input->post('bank_code')),
				'bank_branch' => strtoupper($this->input->post('bank_branch'))
			);
			$e_field_id = $this->input->post('e_field_id');
			$result = $this->Employees_model->bank_account_info_update($data,$e_field_id);
			/*User Logs*/
			$affected_id= table_update_id('xin_employee_bankaccount','bankaccount_id',$e_field_id);
			userlogs('Employees-Employee Bank Account Info-Update','Employee Bank Account Info Updated',$e_field_id,$affected_id['datas']);
			/*User Logs*/
			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_error_bank_info_updated');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	public function e_contract_info() {

		if($this->input->post('type')=='e_contract_info') {
			$Return = array('result'=>'', 'error'=>'');
			$frm_date = strtotime($this->input->post('from_date'));
			$to_date = strtotime($this->input->post('to_date'));
			/* Server side PHP input validation */
			if($this->input->post('contract_type_id')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_contract_type');
			} /*else if($this->input->post('title')==='') {
       		 $Return['error'] = $this->lang->line('xin_employee_error_contract_title');
		}	 */else if($this->input->post('from_date')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_frm_date');
			} else if($this->input->post('to_date')==='') {
				if($this->input->post('contract_type_id')!='18'){
					$Return['error'] = $this->lang->line('xin_employee_error_to_date');
				}
			} else if(($frm_date > $to_date) && $this->input->post('contract_type_id')!='18') {
				$Return['error'] = $this->lang->line('xin_employee_error_frm_to_date');
			}


			if($Return['error']!=''){
				$this->output($Return);
			}

			if($_FILES['document_file']['size'][0]== 0) {
				$data = array(
					'contract_type_id' => $this->input->post('contract_type_id'),
					//'title' => $this->input->post('title'),
					'from_date' => format_date('Y-m-d',$this->input->post('from_date')),
					'to_date' => format_date('Y-m-d',$this->input->post('to_date')),
					//'designation_id' => $this->input->post('designation_id'),
					'description' => $this->input->post('description')
				);
				$e_field_id = $this->input->post('e_field_id');
				$result = $this->Employees_model->contract_info_update($data,$e_field_id);
			} else {

				if(isset($_FILES['document_file'])) {
					//checking image type
					$allowed =  array('png','jpg','jpeg','pdf','gif');
					$count=count($_FILES['document_file']['name']);
					for($i=0;$i<$count;$i++){
						$filename = strtolower($_FILES['document_file']['name'][$i]);
						$ext = pathinfo($filename, PATHINFO_EXTENSION);
						if(in_array($ext,$allowed)){
							$tmp_name = $_FILES["document_file"]["tmp_name"][$i];
							$documentd = "uploads/document/immigration/";
							$name = basename($_FILES["document_file"]["name"][$i]);
							$newfilename = 'document_'.round(microtime(true)).'_'.$i.'.'.$ext;
							move_uploaded_file($tmp_name, $documentd.$newfilename);
							$fname[] = $newfilename;
						}
					}

					$fname=implode(',',$fname);
					$fname=$fname.','.$this->input->post('document_file_old');

					$data = array(
						'contract_type_id' => $this->input->post('contract_type_id'),
						//'title' => $this->input->post('title'),
						'from_date' => format_date('Y-m-d',$this->input->post('from_date')),
						'to_date' => format_date('Y-m-d',$this->input->post('to_date')),
						'document_file' => $fname,
						//'designation_id' => $this->input->post('designation_id'),
						'description' => $this->input->post('description')
					);
					$e_field_id = $this->input->post('e_field_id');
					$result = $this->Employees_model->contract_info_update($data,$e_field_id);


				}
			}

			/*User Logs*/
			$affected_id= table_update_id('xin_employee_contract','contract_id',$e_field_id);
			userlogs('Employees-Employee Contract-Update','Employee Contract Updated',$e_field_id,$affected_id['datas']);
			/*User Logs*/

			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_contract_info_updated');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	/* Edit Operations */

	/* Dialog Popup Functions */

	public function dialog_contact() {
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('field_id');
		$result = $this->Employees_model->read_contact_information($id);
		$data = array(
			'contact_id' => $result[0]->contact_id,
			'employee_id' => $result[0]->employee_id,
			'relation' => $result[0]->relation,
			'is_primary' => $result[0]->is_primary,
			'is_secondary' => $result[0]->is_secondary,
			'contact_name' => $result[0]->contact_name,
			'mobile_phone' => $result[0]->mobile_phone,
			'address_1' => $result[0]->address_1,
			'address_2' => $result[0]->address_2,
			'city' => $result[0]->city,
			'state' => $result[0]->state,
			'zipcode' => $result[0]->zipcode,
			'country1' => $result[0]->country,
			'all_countries' => $this->Xin_model->get_countries(),
			'phone_numbers_code' => phone_numbers_code()
		);

		if(!empty($this->userSession)){
			$this->load->view('employees/dialog_employee_details', $data);
		} else {
			redirect('');
		}
	}

	public function dialog_imgdocument() {
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('field_id');
		$document = $this->Employees_model->read_imgdocument_information($id);
		$data = array(
			'immigration_id' => $document[0]->immigration_id,
			'document_type_id' => $document[0]->document_type_id,
			'd_employee_id' => $document[0]->employee_id,
			'all_document_types' => $this->Employees_model->all_document_types(),
			'all_countries' => $this->Xin_model->get_countries(),
			'document_number' => $document[0]->document_number,
			'document_file' => $document[0]->document_file,
			'issue_date' => $document[0]->issue_date,
			'expiry_date' => $document[0]->expiry_date,
			'country_id' => $document[0]->country_id,
			'card_type' => $document[0]->type,
			'cost' => $document[0]->cost,
			'visa_cancellation_cost' => $document[0]->visa_cancellation_cost,
			'visa_renewal_cost' => $document[0]->visa_renewal_cost,
			'all_visa_under' => $this->Employees_model->all_visa_under(),
			'all_medical_card_types' => $this->Employees_model->all_medical_card_types(),
			'eligible_review_date' => $document[0]->eligible_review_date,
			'date_of_cancellation' => $document[0]->date_of_cancellation,
		);

		if(!empty($this->userSession)){
			$this->load->view('employees/dialog_employee_details', $data);
		} else {
			redirect('');
		}
	}

	public function dialog_qualification() {
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('field_id');
		$result = $this->Employees_model->read_qualification_information($id);
		$data = array(
			'qualification_id' => $result[0]->qualification_id,
			'employee_id' => $result[0]->employee_id,
			'name' => $result[0]->name,
			'education_level_id' => $result[0]->education_level_id,
			'from_year' => $result[0]->from_year,
			'language_id' => $result[0]->language_id,
			'to_year' => $result[0]->to_year,
			'skill_id' => $result[0]->skill_id,
			'description' => $result[0]->description,
			'all_education_level' => $this->Employees_model->all_education_level(),
			'all_qualification_language' => $this->Employees_model->all_qualification_language(),
			'all_qualification_skill' => $this->Employees_model->all_qualification_skill()
		);

		if(!empty($this->userSession)){
			$this->load->view('employees/dialog_employee_details', $data);
		} else {
			redirect('');
		}
	}

	public function dialog_work_experience() {
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('field_id');
		$result = $this->Employees_model->read_work_experience_information($id);
		$data = array(
			'work_experience_id' => $result[0]->work_experience_id,
			'employee_id' => $result[0]->employee_id,
			'company_name' => $result[0]->company_name,
			'from_date' => $result[0]->from_date,
			'to_date' => $result[0]->to_date,
			'post' => $result[0]->post,
			'description' => $result[0]->description
		);

		if(!empty($this->userSession)){
			$this->load->view('employees/dialog_employee_details', $data);
		} else {
			redirect('');
		}
	}

	public function dialog_bank_account() {
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('field_id');
		$result = $this->Employees_model->read_bank_account_information($id);
		$data = array(
			'bankaccount_id' => $result[0]->bankaccount_id,
			'employee_id' => $result[0]->employee_id,
			'is_primary' => $result[0]->is_primary,
			'account_title' => $result[0]->account_title,
			'account_number' => $result[0]->account_number,
			'bank_name' => $result[0]->bank_name,
			'bank_code' => $result[0]->bank_code,
			'bank_branch' => $result[0]->bank_branch
		);

		if(!empty($this->userSession)){
			$this->load->view('employees/dialog_employee_details', $data);
		} else {
			redirect('');
		}
	}

	public function dialog_contract() {
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('field_id');
		$result = $this->Employees_model->read_contract_information($id);
		$data = array(
			'contract_id' => $result[0]->contract_id,
			'employee_id' => $result[0]->employee_id,
			'contract_type_id' => $result[0]->contract_type_id,
			'document_file' => $result[0]->document_file,
			'from_date' => $result[0]->from_date,
			'designation_id' => $result[0]->designation_id,
			'title' => $result[0]->title,
			'to_date' => $result[0]->to_date,
			'description' => $result[0]->description,
			'all_contract_types' => $this->Employees_model->all_contract_types(),
			'all_designations' => $this->Designation_model->all_designations(),
		);

		if(!empty($this->userSession)){
			$this->load->view('employees/dialog_employee_details', $data);
		} else {
			redirect('');
		}
	}

	public function dialog_image_document(){
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('field_id');
		$document = $this->Employees_model->read_imgdocument_information($id);
		$data = array(
			'immigration_id' => $document[0]->immigration_id,
			'document_type_id' => $document[0]->document_type_id,
			'd_employee_id' => $document[0]->employee_id,
			'all_document_types' => $this->Employees_model->all_document_types(),
			'all_countries' => $this->Xin_model->get_countries(),
			'document_number' => $document[0]->document_number,
			'document_file' => $document[0]->document_file,
			'issue_date' => $document[0]->issue_date,
			'expiry_date' => $document[0]->expiry_date,
			'country_id' => $document[0]->country_id,
			'card_type' => $document[0]->type,
			'cost' => $document[0]->cost,
			'visa_cancellation_cost' => $document[0]->visa_cancellation_cost,
			'visa_renewal_cost' => $document[0]->visa_renewal_cost,
			'all_visa_under' => $this->Employees_model->all_visa_under(),
			'all_medical_card_types' => $this->Employees_model->all_medical_card_types(),
			'eligible_review_date' => $document[0]->eligible_review_date,
			'date_of_cancellation' => $document[0]->date_of_cancellation,
		);

		if(!empty($this->userSession)){
			$this->load->view('employees/dialog_employee_details', $data);
		} else {
			redirect('');
		}

	}

	public function dialog_image_contract(){
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('field_id');
		$result = $this->Employees_model->read_contract_information($id);
		$data = array(
			'contract_id' => $result[0]->contract_id,
			'employee_id' => $result[0]->employee_id,
			'contract_type_id' => $result[0]->contract_type_id,
			'document_file' => $result[0]->document_file,
			'from_date' => $result[0]->from_date,
			'designation_id' => $result[0]->designation_id,
			'title' => $result[0]->title,
			'to_date' => $result[0]->to_date,
			'description' => $result[0]->description,
			'all_contract_types' => $this->Employees_model->all_contract_types(),
			'all_designations' => $this->Designation_model->all_designations(),
		);

		if(!empty($this->userSession)){
			$this->load->view('employees/dialog_employee_details', $data);
		} else {
			redirect('');
		}
	}

	/* Dialog Popup Functions */

	/* Update Operations */

	public function update_contacts_info() {

		if($this->input->post('type')=='contact_info') {

			$Return = array('result'=>'', 'error'=>'');

			if($this->input->post('salutation')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_salutation');
			} else if($this->input->post('contact_name')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_contact_name');
			} else if($this->input->post('relation')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_grp');
			} else if($this->input->post('primary_email')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_pemail');
			} else if($this->input->post('mobile_phone')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_mobile');
			} else if($this->input->post('city')==='') {
				$Return['error'] = $this->lang->line('xin_error_city_field');
			} else if($this->input->post('country')==='') {
				$Return['error'] = $this->lang->line('xin_error_country_field');
			}

			if($Return['error']!=''){
				$this->output($Return);
			}

			$data = array(
				'salutation' => $this->input->post('salutation'),
				'contact_name' => $this->input->post('contact_name'),
				'relation' => $this->input->post('relation'),
				'company' => $this->input->post('company'),
				'job_title' => $this->input->post('job_title'),
				'primary_email' => $this->input->post('primary_email'),
				'mobile_phone' => $this->input->post('mobile_phone'),
				'address' => $this->input->post('address'),
				'city' => $this->input->post('city'),
				'state' => $this->input->post('state'),
				'zipcode' => $this->input->post('zipcode'),
				'country' => $this->input->post('country'),
				'employee_id' => $this->input->post('user_id'),
				'contact_type' => 'permanent'
			);

			$query = $this->Employees_model->check_employee_contact_permanent($this->input->post('user_id'));
			if ($query->num_rows() > 0 ) {
				$res = $query->result();
				$e_field_id = $res[0]->contact_id;
				$result = $this->Employees_model->contact_info_update($data,$e_field_id);
			} else {
				$result = $this->Employees_model->contact_info_add($data);
			}

			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_contact_info_updated');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	public function updateemployeetenure(){
		$user_id=$this->uri->segment(3);
		$tenure=$this->uri->segment(4);
		$data=array('tenure' => $tenure);
		$this->Employees_model->update_record($data,$user_id);
	}

	public function update_contact_info() {

		if($this->input->post('type')=='contact_info') {
			$Return = array('result'=>'', 'error'=>'');

			if($this->input->post('salutation')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_salutation');
			} else if($this->input->post('contact_name')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_contact_name');
			} else if($this->input->post('relation')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_grp');
			} else if($this->input->post('primary_email')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_pemail');
			} else if($this->input->post('mobile_phone')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_mobile');
			} else if($this->input->post('city')==='') {
				$Return['error'] = $this->lang->line('xin_error_city_field');
			} else if($this->input->post('country')==='') {
				$Return['error'] = $this->lang->line('xin_error_country_field');
			}

			if($Return['error']!=''){
				$this->output($Return);
			}

			$data = array(
				'salutation' => $this->input->post('salutation'),
				'contact_name' => $this->input->post('contact_name'),
				'relation' => $this->input->post('relation'),
				'company' => $this->input->post('company'),
				'job_title' => $this->input->post('job_title'),
				'primary_email' => $this->input->post('primary_email'),
				'mobile_phone' => $this->input->post('country_code').$this->input->post('mobile_phone'),
				'address' => $this->input->post('address'),
				'city' => $this->input->post('city'),
				'state' => $this->input->post('state'),
				'zipcode' => $this->input->post('zipcode'),
				'country' => $this->input->post('country'),
				'employee_id' => $this->input->post('user_id'),
				'contact_type' => 'current',
			);

			$query = $this->Employees_model->check_employee_contact_current($this->input->post('user_id'));
			if ($query->num_rows() > 0 ) {
				$res = $query->result();
				$e_field_id = $res[0]->contact_id;
				$result = $this->Employees_model->contact_info_update($data,$e_field_id);
			} else {
				$result = $this->Employees_model->contact_info_add($data);
			}

			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_contact_info_updated');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	public function update() {

		if($this->input->post('edit_type')=='warning') {

			$id = $this->uri->segment(3);
			$Return = array('result'=>'', 'error'=>'');

			/* Server side PHP input validation */
			$description = $this->input->post('description');
			$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);

			if($this->input->post('warning_to')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_warning');
			} else if($this->input->post('type')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_warning_type');
			} else if($this->input->post('subject')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_subject');
			} else if(empty($this->input->post('warning_by'))) {
				$Return['error'] = $this->lang->line('xin_employee_error_warning_by');
			} else if(empty($this->input->post('warning_date'))) {
				$Return['error'] = $this->lang->line('xin_employee_error_warning_date');
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
				'warning_date' => $this->input->post('warning_date'),
				'status' => $this->input->post('status'),
			);

			$result = $this->Warning_model->update_record($data,$id);

			/*User Logs*/
			$affected_id= table_update_id('xin_employee_warnings','warning_id',$id);
			userlogs('Employees-Employee Warning-Update','Employee Warning Updated',$id,$affected_id['datas']);
			/*User Logs*/


			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_warning_updated');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	public function update_personal_form() {
		if($this->input->post('form')=='update_personal_form') {
			$Return = array('result'=>'', 'error'=>'');

			$phonecode =phone_numbers_code(); 
			$p_country_code =  $this->input->post('p_country_code') ; 
			$contact_country_code =  $this->input->post('contact_country_code') ; 
			$p_country_code = substr($p_country_code, 0, -1);
			$contact_country_code = substr($contact_country_code, 0, -1);
			$p_current_length = strlen($this->input->post('p_mobile_phone')) ; 
			$p_required_length = $phonecode[$p_country_code]['length'] ; 
			$c_current_length = strlen($this->input->post('contact_mobile_phone')) ; 
			$c_required_length = $phonecode[$contact_country_code]['length'] ;  
			
		
			if($this->input->post('address') === ''){
				$Return['error'] = 'Present address field is required.';
			} else if($this->input->post('p_city') === ''){
				$Return['error'] = 'Present city field is required.';
			} else if($this->input->post('home_country') === ''){
				$Return['error'] = 'Present country field is required.';
			} else if($this->input->post('residing_address1') === ''){
				$Return['error'] = 'Residing address field is required.';
			} else if($this->input->post('residing_city') === ''){
				$Return['error'] = 'Residing city field is required.';
			} else if($this->input->post('residing_country') === ''){
				$Return['error'] = 'Residing country field is required.';
			} else if($this->input->post('marital_status')== 'Married' && $this->input->post('spouse_name')==='') {
				$Return['error'] = 'Spouse name is required.';
			}
			else if($this->input->post('marital_status')== 'Single' && $this->input->post('spouse_name')!='') {
				$Return['error'] = 'Spouse name field shoule be empty when single marital status.';
			}
			else if($this->input->post('p_mobile_phone') === ''){
				$Return['error'] = 'Personal mobile number is required.';
			}
			else if(!is_numeric($this->input->post('p_mobile_phone'))) {
				$Return['error'] = "Personal mobile number should be numeric.";
			}
			else if($this->input->post('personal_email') === ''){
				$Return['error'] = 'Personal email is required.';
			}
			else if($this->input->post('contact_name') === ''){
				$Return['error'] = 'Emergency contact person name is required.';
			}
			else if($this->input->post('relation') === ''){
				$Return['error'] = 'Emergency contact person relation is required.';
			}
			else if($this->input->post('address_1') === ''){
				$Return['error'] = 'Emergency contact person address is required.';
			}
			else if($this->input->post('contact_mobile_phone') === ''){
				$Return['error'] = 'Emergency contact person number is required.';
			}
			else if(!is_numeric($this->input->post('contact_mobile_phone'))) {
				$Return['error'] = "Emergency contact person number should be numeric.";
			}
			else if($this->input->post('contact_city') === ''){
				$Return['error'] = 'Emergency contact person city is required.';
			}
			else if($this->input->post('contact_country') === ''){
				$Return['error'] = 'Emergency contact person country is required.';
			}
			else if( $p_current_length != $p_required_length ) {
				$Return['error'] = "personal mobile number should be of length $p_required_length.";
			}
			else if( $c_current_length != $c_required_length ) {
				$Return['error'] = "contact mobile number should be of length $c_required_length.";
			}

			if($Return['error']!=''){
				$this->output($Return);
			}

			$user_id				=  $this->userSession['user_id'];
			$employee_data = array(
				'address' 			=> $this->input->post('address'),
				'city' 				=> $this->input->post('p_city'),
				'area' 				=> $this->input->post('p_area'),
				'home_country'	 	=> $this->input->post('home_country'),
				'residing_address1' => $this->input->post('residing_address1'),
				'residing_city' 	=> $this->input->post('residing_city'),
				'residing_area' 	=> $this->input->post('residing_area'),
				'residing_country' 	=> $this->input->post('residing_country'),
				'marital_status' 	=> $this->input->post('marital_status'),
				'spouse_name' 		=> $this->input->post('spouse_name'),
				'contact_no'		=> $this->input->post('p_country_code').$this->input->post('p_mobile_phone'),
				'personal_email' 	=> $this->input->post('personal_email'),
			);

			$user_contact_id 		= $this->input->post('user_contact_id');
			$contact_data = array(
				'contact_name' 		=> $this->input->post('contact_name'),
				'relation' 			=> $this->input->post('relation'),
				'address_1' 		=> $this->input->post('address_1'),
				'mobile_phone' 		=> $this->input->post('contact_country_code').$this->input->post('contact_mobile_phone'),
				'city' 				=> $this->input->post('contact_city'),
				'state' 			=> $this->input->post('contact_state'),
				'country' 			=> $this->input->post('contact_country'),
				'employee_id' 		=> $user_id,
				'is_primary'	 	=> 1
			);

			if($employee_data){
				$result = $this->Employees_model->update_record($employee_data, $user_id);
			}

			if($this->input->post('contact_name')){
				if($user_contact_id){
					$this->Employees_model->contact_info_update($contact_data, $user_contact_id);
				}
				else{
					$this->Employees_model->contact_info_add($contact_data);
				}
			}

			if ($result == true) {
				$Return['result'] 	= 'Personnel Form Updated!';
			} else {
				$Return['error'] 	= $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	/* Update Operations */

	/* Delete Operations */

	public function delete_contact() {

		if($this->input->post('data')=='delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			/*User Logs*/
			$affected_row= table_deleted_row('xin_employee_contacts','contact_id',$id);
			userlogs('Employees-Emergency Contact-Delete','Employee Contact Deleted',$id,$affected_row);
			/*User Logs*/

			$this->Employees_model->delete_contact_record($id);
			if(isset($id)) {
				$Return['result'] = $this->lang->line('xin_employee_contact_deleted');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}

	public function delete_imgdocument() {

		if($this->input->post('data')=='delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			$get_document_image_name=$this->Employees_model->get_document_image_name($id,'xin_employee_immigration','document');

			$document_information = $this->Employees_model->read_imgdocument_information($id);
			if($document_information[0]->document_type_id == 10){
				$update_data['approval_status'] = 1;
				$this->db->where('employee_id',$document_information[0]->employee_id);
				$this->db->where('type_of_approval','non_disclosure_approval');
				$this->db->update('xin_employees_approval',$update_data);
			}

			$arr = explode(',',$get_document_image_name);

			/*User Logs*/
			$affected_row= table_deleted_row('xin_employee_immigration','immigration_id',$id);
			userlogs('Employees-Employee Document-Delete','Employee Document Deleted',$id,$affected_row);
			/*User Logs*/

			$this->Employees_model->delete_imgdocument_record($id);
			foreach($arr as $del_ar){
				unlink($_SERVER['DocumentRoot'].'uploads/document/immigration/'.$del_ar);
			}
			if(isset($id)) {
				$Return['result'] = 'Immigration deleted.';
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}

	public function delete_qualification() {

		if($this->input->post('data')=='delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			/*User Logs*/
			$affected_row= table_deleted_row('xin_employee_qualification','qualification_id',$id);
			userlogs('Employees-Employee Qualification Info-Delete','Employee Qualification Info Deleted',$id,$affected_row);
			/*User Logs*/
			$this->Employees_model->delete_qualification_record($id);
			if(isset($id)) {
				$Return['result'] = $this->lang->line('xin_employee_qualification_deleted');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}

	public function delete_work_experience() {

		if($this->input->post('data')=='delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);

			/*User Logs*/
			$affected_row= table_deleted_row('xin_employee_work_experience','work_experience_id',$id);
			userlogs('Employees-Employee Work Experience-Delete','Employee Work Experience Info Deleted',$id,$affected_row);
			/*User Logs*/

			$this->Employees_model->delete_work_experience_record($id);
			if(isset($id)) {
				$Return['result'] = $this->lang->line('xin_employee_work_deleted');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}

	public function delete_bank_account() {

		if($this->input->post('data')=='delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);

			/*User Logs*/
			$affected_row= table_deleted_row('xin_employee_bankaccount','bankaccount_id',$id);
			userlogs('Employees-Employee Bank Account Info-Delete','Employee Bank Account Info Deleted',$id,$affected_row);
			/*User Logs*/

			$this->Employees_model->delete_bank_account_record($id);
			if(isset($id)) {
				$Return['result'] = $this->lang->line('xin_employee_bankaccount_deleted');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}

	public function delete_contract() {

		if($this->input->post('data')=='delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);

			$get_document_image_name=$this->Employees_model->get_document_image_name($id,'xin_employee_contract','contract');
			$arr = explode(',',$get_document_image_name);


			/*User Logs*/
			$affected_row= table_deleted_row('xin_employee_contract','contract_id',$id);
			userlogs('Employees-Employee Contract-Delete','Employee Contract Info Deleted',$id,$affected_row);
			/*User Logs*/

			$this->Employees_model->delete_contract_record($id);
			foreach($arr as $del_ar){
				unlink($_SERVER['DocumentRoot'].'uploads/document/immigration/'.$del_ar);
			}
			if(isset($id)) {
				$Return['result'] = $this->lang->line('xin_employee_contract_deleted');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}

	public function delete() {

		if($this->input->post('is_ajax')=='2') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			/*User Logs*/
			$affected_row= table_deleted_row('xin_employees','user_id',$id);
			userlogs('Employees-Employee Account-Delete','Employee Account Deleted',$id,$affected_row);
			/*User Logs*/

			$this->Employees_model->delete_record($id);
			if(isset($id)) {
				$Return['result'] = $this->lang->line('xin_employee_current_deleted');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}

	public function document_image_delete(){

		$image_name= $this->input->post('name');
		$document_id= $this->input->post('id');
		$type= $this->input->post('type');
		if($type=='document'){
			$get_document_image_name=$this->Employees_model->get_document_image_name($document_id,'xin_employee_immigration',$type);
			$arr = explode(',',$get_document_image_name);
			$arr = array_diff($arr, array($image_name));
			$implode_arr=implode(',',$arr);
			$data=array('document_file'=>$implode_arr);
			$affected_id= table_deleted_row('xin_employee_immigration','immigration_id',$document_id);
			$this->Employees_model->img_document_info_update($data,$document_id);
			userlogs('Employees','Employee Document File Deleted',$document_id,$affected_id);
		}
		else if($type=='contract'){
			$get_document_image_name=$this->Employees_model->get_document_image_name($document_id,'xin_employee_contract',$type);
			$arr = explode(',',$get_document_image_name);
			$arr = array_diff($arr, array($image_name));
			$implode_arr=implode(',',$arr);
			$data=array('document_file'=>$implode_arr);
			$affected_id= table_deleted_row('xin_employee_contract','contract_id',$document_id);
			$this->Employees_model->contract_info_update($data,$document_id);
			userlogs('Employees','Employee Contract File Deleted',$document_id,$affected_id);
		}

		unlink($_SERVER['DocumentRoot'].'uploads/document/immigration/'.$image_name);
		echo $implode_arr;
	}

	public function passport_request_document(){

		$cinfo = $this->Xin_model->read_company_setting_info(1);

		if(empty($this->userSession)){
			redirect('');
		}

		if($this->input->post('add_type') == 'passport_request'){
			$Return = array('result'=>'', 'error'=>'', 'message'=>'');

			$check_data = $this->Timesheet_model->get_passport_request($id='',$this->userSession['user_id'],'','passport_request','Request raised')[0];

			if(!empty($check_data)){
				$Return['message'] = 'Found a pending request, Please wait for some time or contact to reporting manager.';
			   	$Return['error'] = 1;
			}else{
				$doc_data['from_date'] = date('Y-m-d',strtotime($this->input->post('from_date')));
				$doc_data['return_date'] = date('Y-m-d',strtotime($this->input->post('return_date')));
				$doc_data['purpose']	= $this->input->post('purpose');
				$doc_data['user_id'] 	= $this->userSession['user_id'];
				$doc_data['created_at'] = date('Y-m-d');
				$doc_data['status'] 	= 'Request raised';
				$doc_data['request_type'] 	= 'passport_request';

				$request_log[0]['created_by'] = $this->userSession['user_id']; 
				$request_log[0]['status'] = 'Request raised'; 
				$request_log[0]['created_at'] = date('Y-m-d H:i:s'); 
				$doc_data['request_log'] = json_encode($request_log);

				if(!empty($doc_data['from_date']) && !empty($doc_data['return_date']) && !empty($doc_data['purpose'])){

					$data['emp_data'] = $this->Employees_model->read_employee_information($this->userSession['user_id'])[0];
					$reporting_manager = $this->Employees_model->read_employee_information($data['emp_data']->reporting_manager)[0];
					$data['reporting_manager'] = $reporting_manager->first_name.' '.$reporting_manager->middle_name.' '.$reporting_manager->last_name;
					$this->db->insert('xin_employee_doc_request',$doc_data);
					$data['insert_id'] = $this->db->insert_id();

					$reporting_manager_name = change_fletter_caps($data['reporting_manager']);
					$emp_name = change_fletter_caps($data['emp_data']->first_name.' '.$data['emp_data']->middle_name.' '.$data['emp_data']->last_name);
					$employee_address = change_fletter_caps($data['emp_data']->residing_address1.' '.$data['emp_data']->residing_address2);

					$template = $this->Xin_model->read_email_template_info_bycode('Passport Request');

					$sending_msg = $emp_name.' has raised the passport request ( '.$this->input->post('purpose').' ). Kindly review and approve.<br>';
					$sending_msg .= 'Start date : '.date('d F Y',strtotime($this->input->post('from_date'))).'<br>';
					$sending_msg .= 'End date : '.date('d F Y',strtotime($this->input->post('return_date'))).'<br>';

					$message = '<div style="background: #f7eaea;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin: 0 auto;padding:20px;max-width: 65em;border: 2px solid #D40732;">'.
						str_replace(
							array(
								"{var reporting_manager}",
								"{var message}",
								"{var approval_url}",
								"{var year}",
								"{var contact_person}",
								"{var hr_email}",
							),
							array(
								$reporting_manager_name,
								$sending_msg,
								site_url().'employees/passport_request_document',
								date('Y'),
								$cinfo[0]->contact_person,
								$cinfo[0]->email,
							),htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';

					$to_mail = $reporting_manager->email;
					// $to_mail = array("sajith.v@awok.com");
					$subject = 'Request for passport release'.' - '.$cinfo[0]->company_name;
			        $this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
			        $this->email->to($to_mail);
			        $this->email->subject($subject);
			        $this->email->message($message);

			        if($this->email->send()){
			        	// echo $this->email->print_debugger();
					   	$Return['message'] = 'Successfully send.';
					   	$Return['error'] = '';
					}else{
						// echo $this->email->print_debugger();
					   	$Return['message'] = 'Something went wrong.';
					   	$Return['error'] = 1;
					}
				}else{
					$Return['message'] = 'Please fill the mandatory fields.';
				   	$Return['error'] = 1;
				}
			}

			$this->output($Return);exit;

		}else{

			$data['emp_data'] = $this->Employees_model->read_employee_information($this->userSession['user_id'])[0];
			$data['visa_type'] = $this->Employees_model->checkVisaType($this->userSession['user_id']);
			$data['role_user'] = $this->Xin_model->read_user_role_info($this->userSession['role_of_user']);
			$data['title'] = $this->Xin_model->site_title();
			$data['all_user_roles'] = $this->Roles_model->all_user_roles();
			$data['breadcrumbs'] = 'Document request';
			$data['path_url'] = 'request_document';
			$data['subview'] = $this->load->view("employees/passport_request_document", $data, true);
			$this->load->view('layout_main', $data);

		}

	}

	public function passport_request_list(){

		if(!empty($this->userSession)){
			$this->load->view("employees/passport_request_document", $data);
		}
		else {
			redirect('');
		}
		$data['title'] = $this->Xin_model->site_title();

		$role_user = $this->Xin_model->read_user_role_info($this->userSession['role_of_user']);
		$role_name = $role_user[0]->role_name;
		// $HR_L_ROLE = unserialize(HR_L_ROLE);
		// if(rp_manager_access() || $role_name == AD_ROLE || in_array($role_name,$HR_L_ROLE)){
		if(rp_manager_access() || $role_name == AD_ROLE || (in_array('request-passport-delete',role_resource_ids()) && in_array('request-passport-view',role_resource_ids())) ){
			$request_list = $this->Timesheet_model->get_passport_request_reporting_manager($this->userSession['user_id'],$role_name,'passport_request');
		}else{
			$request_list = $this->Timesheet_model->get_passport_request($id='',$this->userSession['user_id'],$list=1,'passport_request');
		}

		$data = array();
		$i = 1;
		foreach($request_list as $r) {
			
			if($this->userSession['user_id'] == $r->user_id){
				$name = 'Myself';
			}else{
				$name = $r->first_name.' '.$r->middle_name.' '.$r->last_name;
			}

			if($r->status == 'Request raised'){
				$status = 'Request raised';
				$statusClass = 'label-warning';
			}else if($r->status == 'Approve'){
				$status = 'Approve';
				$statusClass = 'label-success';
			}elseif($r->status == 'Reject'){
				$status = 'Reject';
				$statusClass = 'label-danger';
			}elseif($r->status == 'Ready for shipment'){
				$status = 'Ready for shipment';
				$statusClass = 'label-success';
			}elseif($r->status == 'Delivered'){
				$status = 'Delivered';
				$statusClass = 'label-success';
			}elseif($r->status == 'Passport in vault'){
				$status = 'Passport in vault';
				$statusClass = 'label-primary';
			}

			$status_perm = '';
			$delete_perm = '';
			$option = '';
			if($role_user[0]->role_name == AD_ROLE || (in_array('request-passport-delete',role_resource_ids()) && in_array('request-passport-delete',role_resource_ids()) && $name != 'Myself') ){

				$delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-target=".delete-modal" data-type-id="Delete" data-record-id="'. $r->id . '"><i class="icon-trash"></i> Delete</a></li>';

				$status_perm .= '<li><a class="delete" href="#" data-toggle="modal" data-target=".delete-modal" data-type-id="Request raised" data-record-id="'. $r->id . '"><i class="icon-warning"></i> Request raised</a></li>';
				$status_perm .= '<li><a class="delete" href="#" data-toggle="modal" data-target=".delete-modal" data-type-id="Approve" data-record-id="'. $r->id . '"><i class="icon-checkmark4"></i> Approve</a></li>';
				$status_perm .= '<li><a class="delete" href="#" data-toggle="modal" data-target=".delete-modal" data-type-id="Reject" data-record-id="'. $r->id . '"><i class="icon-cross2"></i> Reject</a></li>';
				$status_perm .= '<li><a class="delete" href="#" data-toggle="modal" data-target=".delete-modal" data-type-id="Ready for shipment" data-record-id="'. $r->id . '"><i class="icon-thumbs-up2"></i> Ready for shipment</a></li>';
				$status_perm .= '<li><a class="delete" href="#" data-toggle="modal" data-target=".delete-modal" data-type-id="Delivered" data-record-id="'. $r->id . '"><i class="icon-checkmark4"></i> Delivered</a></li>';
				$status_perm .= '<li><a class="delete" href="#" data-toggle="modal" data-target=".delete-modal" data-type-id="Passport in vault" data-record-id="'. $r->id . '"><i class="icon-upload10"></i> Passport in vault</a></li>';

			}elseif(rp_manager_access() && $name != 'Myself'){

				if($r->status == 'Request raised'){
					$status_perm = '<li><a class="delete" href="#" data-toggle="modal" data-target=".delete-modal" data-type-id="Approve" data-record-id="'. $r->id . '"><i class="icon-checkmark4"></i> Approve</a></li>';

					$status_perm .= '<li><a class="delete" href="#" data-toggle="modal" data-target=".delete-modal" data-type-id="Reject" data-record-id="'. $r->id . '"><i class="icon-cross2"></i> Reject</a></li>';
				}else{
					$status_perm = '<li><a><i class="icon-checkmark4"></i> Process completed</a></li>';
				}
			}else{

				$status_perm = '<li><a><i class="icon-warning"></i> Not allowed</a></li>';
			}

			$option = '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$status_perm.$delete_perm.'</ul></li></ul>';

			$data[] = array(
				$i,
				$name,
				'<span class="label '.$statusClass.'">'.$status.'</span>',
				$this->Xin_model->set_date_format($r->created_at),
				$this->Xin_model->set_date_format($r->return_date),
				$r->purpose,
				$option
			);

			$i++;

		}
		$output = array(
			"data" => $data
		);
		$this->output($output);

	}

	public function delete_passport_request() {

		$cinfo = $this->Xin_model->read_company_setting_info(1);
		if($this->input->post('is_ajax')=='2') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			$type = $this->input->post('type');

			if($type == 'Delete'){
				/*User Logs*/
				$affected_row= table_deleted_row('xin_employee_doc_request','id',$id);
				userlogs('Employees-Employee Passport-Request-Delete','Employee Passport Request Deleted',$id,$affected_row);
				/*User Logs*/

				$this->Employees_model->delete_passport_request($id);

				if(isset($id)) {
					$Return['result'] = 'Deleted successfully';
				} else {
					$Return['error'] = 'Something went wrong';
				}

			}else{

				$type_of_status = $this->input->post('type');
				if($type_of_status == 'Ready for shipment' || $type_of_status == 'Approve' || $type_of_status == 'Reject'){

					$check_data = $this->Timesheet_model->get_passport_request($id)[0];
					$data['emp_data'] = $this->Employees_model->read_employee_information($check_data->user_id)[0];
					$template = $this->Xin_model->read_email_template_info_bycode('Passport Request Approval');

					if($type_of_status == 'Ready for shipment'){

						$emp_name = change_fletter_caps($data['emp_data']->first_name.' '.$data['emp_data']->middle_name.' '.$data['emp_data']->last_name);
						$to_mail = $data['emp_data']->email;
						if($check_data->request_type == 'passport_request'){

							$sending_msg = 'Your passport request is approved. Kindly contact your HR representative to collect the passport before the '.date('d F Y',strtotime($check_data->from_date)).'.<br>';
							$sending_msg .= 'Do ensure to return the passport to HR Rep within two days of the re-joining.';

						}else{

							$sending_msg = 'Your '.$check_data->request_type.' is ready for collection';
						}
					}elseif($type_of_status == 'Reject'){

						$updata['comment'] = $this->input->post('comment');

						$emp_name = change_fletter_caps($data['emp_data']->first_name.' '.$data['emp_data']->middle_name.' '.$data['emp_data']->last_name);
						$to_mail = $data['emp_data']->email;
						$sending_msg = 'Your request for passport release has been declined for ('.$updata['comment'].').';

					}elseif($type_of_status == 'Approve'){

						$reporting_manager = $this->Employees_model->read_employee_information($data['emp_data']->reporting_manager)[0];
						$reporting_manager_name = $reporting_manager->first_name.' '.$reporting_manager->middle_name.' '.$reporting_manager->last_name;
						$sending_emp = change_fletter_caps($data['emp_data']->first_name.' '.$data['emp_data']->middle_name.' '.$data['emp_data']->last_name);
						$emp_name = 'HR';

						// $to_mail_array = array('sajith.v@awok.com','siddiq.jalaludeen@awok.com');
						$hr_mail_by_location = get_hr_mail_bylocation($data['emp_data']->office_location_id,$data['emp_data']->user_id);
						$to_mail_array = array('hl@awok.com');
						foreach($hr_mail_by_location as $r) {
							array_push($to_mail_array, $r->email);
						}

						$to_mail = $to_mail_array;
						$sending_msg = $reporting_manager_name.' has approved the passport request for '.$sending_emp.',<br>';
						$sending_msg .= '('.$check_data->purpose.') from '.date('d F Y',strtotime($check_data->from_date)).' to '.date('d F Y',strtotime($check_data->return_date)).'. <br>';
						$sending_msg .= 'Kindly ensure the passport is delivered to said employee before the '.date('d F Y',strtotime($check_data->from_date)).'.<br>';
					}else{ }

					$message = '<div style="background: #f7eaea;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin: 0 auto;padding:20px;max-width: 65em;border: 2px solid #D40732;">'.
						str_replace(
							array(
								"{var employee_name}",
								"{var message}",
								"{var year}",
								"{var contact_person}",
								"{var hr_email}",
							),
							array(
								$emp_name,
								$sending_msg,
								date('Y'),
								$cinfo[0]->contact_person,
								$cinfo[0]->email,
							),htmlspecialchars_decode(stripslashes($template[0]->message))
						).'</div>';


					$subject = 'Request for '.$check_data->request_type.' - '.$cinfo[0]->company_name;
					
					// $to_mail_array = array("siddiq.jalaludeen@awok.com");
			        $this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
			        $this->email->to($to_mail);
			        $this->email->subject($subject);
			        $this->email->message($message);
			        $this->email->send();
				}

				$request_log = json_decode($check_data->request_log,1);
				$request_log_update['created_by'] = $this->userSession['user_id']; 
				$request_log_update['status'] = $this->input->post('type'); 
				$request_log_update['created_at'] = date('Y-m-d H:i:s'); 
				array_push($request_log, $request_log_update);
				$updata['request_log'] = json_encode($request_log);

				$updata['status'] = $this->input->post('type');
				$this->Timesheet_model->update_passport_request_status($updata,$id);

				if(isset($id)) {
					$Return['result'] = 'Updated successfully';
				} else {
					$Return['error'] = 'Something went wrong';
				}

			}

			$this->output($Return);
		}
	}

	public function other_request_document(){

		$cinfo = $this->Xin_model->read_company_setting_info(1);
		if(empty($this->userSession) && !in_array('request-others-view',role_resource_ids()) || !in_array($this->userSession['user_id'], email_settings_data())){
			redirect('');
		}

		if($this->input->post('add_type') == 'other_request'){
			$Return = array('result'=>'', 'error'=>'', 'message'=>'');

			$check_data = $this->Timesheet_model->get_passport_request($id='',$this->userSession['user_id'],'Request raised')[0];

			if(!empty($check_data)){
				$Return['message'] = 'Found a pending request, Please wait for some time or contact to reporting manager.';
			   	$Return['error'] = 1;
			}else{
				$doc_data['return_date'] = date('Y-m-d');
				$doc_data['purpose']	= $this->input->post('purpose');
				$doc_data['address_to'] = $this->input->post('address_to');
				$doc_data['user_id'] 	= $this->userSession['user_id'];
				$doc_data['created_at'] = date('Y-m-d');
				$doc_data['status'] 	= 'Request raised';
				$doc_data['request_type'] 	= $this->input->post('request_type');

				$request_log[0]['created_by'] = $this->userSession['user_id']; 
				$request_log[0]['status'] = 'Request raised'; 
				$request_log[0]['created_at'] = date('Y-m-d H:i:s'); 
				$doc_data['request_log'] = json_encode($request_log);

				if(!empty($doc_data['request_type']) && !empty($doc_data['purpose']) && !empty($doc_data['address_to'])){

					$data['emp_data'] = $this->Employees_model->read_employee_information($this->userSession['user_id'])[0];
					$data['request_type']	= $this->input->post('request_type');
					$data['purpose']	= $this->input->post('purpose');
					$data['address_to']	= $this->input->post('address_to');
					$this->db->insert('xin_employee_doc_request',$doc_data);
					$data['insert_id'] = $this->db->insert_id();
					$data['request_data'] = '';

					$reporting_manager_name = change_fletter_caps($data['reporting_manager']);
					$emp_name = change_fletter_caps($data['emp_data']->first_name.' '.$data['emp_data']->middle_name.' '.$data['emp_data']->last_name);
					$employee_address = change_fletter_caps($data['emp_data']->residing_address1.' '.$data['emp_data']->residing_address2);

					$template = $this->Xin_model->read_email_template_info_bycode('Other Document Request');

					$message = '<div style="background: #f7eaea;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin: 0 auto;padding:20px;max-width: 65em;border: 2px solid #D40732;">'.
						str_replace(
							array(
								"{var employee_name}",
								"{var request_type}",
								"{var purpose}",
								"{var address_to}",
								"{var approval_url}",
								// "{var reject_url}",
								"{var year}",
								"{var contact_person}",
								"{var hr_email}",
							),
							array(
								$emp_name,
								$data['request_type'],
								$data['purpose'],
								$data['address_to'],
								site_url().'employees/other_request_document',
								// site_url().'approval/passport_request_approval/'.base64_encode(1).'/'.base64_encode($data['insert_id']),
								// site_url().'approval/passport_request_approval/'.base64_encode(2).'/'.base64_encode($data['insert_id']),
								date('Y'),
								$cinfo[0]->contact_person,
								$cinfo[0]->email,
							),htmlspecialchars_decode(stripslashes($template[0]->message))
						).'</div>';

					$subject = 'Request for '.$this->input->post('request_type').' - '.$cinfo[0]->company_name;
					$hr_mail_by_location = get_hr_mail_bylocation($data['emp_data']->office_location_id,$data['emp_data']->user_id);

					// pr($hr_mail_by_location);die;
					$to_mail_array = array('hl@awok.com');


					$email_settings_mails = $this->Xin_model->checkEmailSettingsData('Email Settings','Salary Certificate');
					if(!empty($email_settings_mails)){
						$mail_ids = json_decode($email_settings_mails[0]->settings_value,1);

						foreach ($mail_ids as $scm) {
							$mail_val = explode('//',$scm);
							array_push($to_mail_array, $mail_val[1]);
						}
					}


					// $to_mail_array = array('sajith.v@awok.com','siddiq.jalaludeen@awok.com');
					// foreach($hr_mail_by_location as $r) {
					// 	array_push($to_mail_array, $r->email);
					// }

			        $this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
			        $this->email->to($to_mail_array);
			        $this->email->subject($subject);
			        $this->email->message($message);
			        if($this->email->send()){
			        	// echo $this->email->print_debugger();
					   	$Return['message'] = 'Successfully send.';
					   	$Return['error'] = '';
					}else{
						// echo $this->email->print_debugger();
					   	$Return['message'] = 'Something went wrong.';
					   	$Return['error'] = 1;
					}
				}else{
					$Return['message'] = 'Please fill the mandatory fields.';
				   	$Return['error'] = 1;
				}
			}

			$this->output($Return);exit;

		}else{

			$data['emp_data'] = $this->Employees_model->read_employee_information($this->userSession['user_id'])[0];
			$data['role_user'] = $this->Xin_model->read_user_role_info($this->userSession['role_of_user']);
			$data['title'] = $this->Xin_model->site_title();
			$data['all_user_roles'] = $this->Roles_model->all_user_roles();
			$data['breadcrumbs'] = 'Document request';
			$data['path_url'] = 'request_document_other';
			$data['subview'] = $this->load->view("employees/other_request_document", $data, true);
			$this->load->view('layout_main', $data);

		}

	}

	public function other_request_list(){

		if(!empty($this->userSession)){
			$this->load->view("employees/passport_request_document", $data);
		}
		else {
			redirect('');
		}

		if(in_array('request-others-view',role_resource_ids()) || in_array($this->userSession['user_id'], email_settings_data())) {
			
			/*$mail_settings_array = array();
			$email_settings_mails = $this->Xin_model->checkEmailSettingsData('Email Settings','Salary Certificate');
			if(!empty($email_settings_mails)){
				$mail_ids = json_decode($email_settings_mails[0]->settings_value,1);

				foreach ($mail_ids as $scm) {
					$mail_val = explode('//',$scm);
					array_push($mail_settings_array, $mail_val[0]);
				}
			}*/

			$data['title'] = $this->Xin_model->site_title();

			$role_user = $this->Xin_model->read_user_role_info($this->userSession['role_of_user']);
			$role_name = $role_user[0]->role_name;
			// $HR_L_ROLE = unserialize(HR_L_ROLE);
			// if($role_name == AD_ROLE || in_array($role_name,$HR_L_ROLE)){
			if($role_name == AD_ROLE || (in_array('request-others-delete',role_resource_ids()) && in_array('request-others-view',role_resource_ids()) ) || in_array($this->userSession['user_id'], email_settings_data())){
				$request_list = $this->Timesheet_model->get_passport_request_reporting_manager($this->userSession['user_id'],$role_name);
			}else{
				$request_list = $this->Timesheet_model->get_passport_request($id='',$this->userSession['user_id'],$list=1);
			}
			$data = array();
			$i = 1;
			foreach($request_list as $r) {
				
				if($this->userSession['user_id'] == $r->user_id){
					$name = 'Myself';
				}else{
					$name = $r->first_name.' '.$r->middle_name.' '.$r->last_name;
				}
				
				if($r->status == 'Request raised'){
					$status = 'Request raised';
					$statusClass = 'label-warning';
				}else if($r->status == 'Ready for shipment'){
					$status = 'Ready for shipment';
					$statusClass = 'label-primary';
				}elseif($r->status == 'Delivered'){
					$status = 'Delivered';
					$statusClass = 'label-success';
				}

				$delete_perm = '';
				$status_perm = '';
				$option = '';

				if($role_user[0]->role_name == AD_ROLE || (in_array('request-others-delete',role_resource_ids()) && in_array('request-others-view',role_resource_ids()) && $name != 'Myself') || (in_array($this->userSession['user_id'], email_settings_data()) && $name != 'Myself' )){
					$delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-target=".delete-modal" data-type-id="Delete" data-record-id="'. $r->id . '"><i class="icon-trash"></i> Delete</a></li>';

					$status_perm = '<li><a class="delete" href="#" data-toggle="modal" data-target=".delete-modal" data-type-id="Request raised" data-record-id="'. $r->id . '"><i class="icon-warning"></i> Request raised</a></li>';
					$status_perm .= '<li><a class="delete" href="#" data-toggle="modal" data-target=".delete-modal" data-type-id="Ready for shipment" data-record-id="'. $r->id . '"><i class="icon-question3"></i> Ready for shipment</a></li>';
					$status_perm .= '<li><a class="delete" href="#" data-toggle="modal" data-target=".delete-modal" data-type-id="Delivered" data-record-id="'. $r->id . '"><i class="icon-checkmark4"></i> Delivered</a></li>';

				}else{
					$status_perm = '<li><a><i class="icon-warning"></i> Not allowed</a></li>';
				}
				$option = '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$status_perm.$delete_perm.'</ul></li></ul>';


				$data[] = array(
					$i,
					$name,
					'<span class="label '.$statusClass.'">'.$status.'</span>',
					$this->Xin_model->set_date_format($r->created_at),
					$r->purpose,
					$r->address_to,
					$option
				);

				$i++;

			}
			$output = array(
				"data" => $data
			);
			$this->output($output);
		}else{
			redirect('');
		}

	}

}
