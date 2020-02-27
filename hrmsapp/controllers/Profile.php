<?php
/**
 * Author Siddiqkhan
 *
 * Employee Controller
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller {

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
		$this->load->model("Employees_model");
		$this->load->model("Xin_model");
		$this->load->model("Department_model");
		$this->load->model("Designation_model");
		$this->load->model("Roles_model");
		$this->load->model("Location_model");
		$this->userSession = $this->session->userdata('username');
		
	}
	
	/*Function to set JSON output*/
	public function output($Return=array()){
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}
	
	public function index() {

		if($this->userSession['user_id']==''){
			redirect('');
		}
		$result = $this->Employees_model->read_employee_information($this->userSession['user_id']);
		$progress_bar=get_profile_complete_progress($this->userSession['user_id']);
		// get designation
		$designation = $this->Designation_model->read_designation_information($result[0]->designation_id);
		$data = array(
			'first_name' => change_to_caps($result[0]->first_name),
			'middle_name' => change_to_caps($result[0]->middle_name),
			'last_name' => change_to_caps($result[0]->last_name),
			'designation' => $designation[0]->designation_name,
			'user_id' => $result[0]->user_id,
			'tenure' => $result[0]->tenure,
			'employee_id' => $result[0]->employee_id,
			'username' => $result[0]->username,
			'email' => $result[0]->email,
			'personal_email' => $result[0]->personal_email,
			'department_id' => $result[0]->department_id,
			'designation_id' => $result[0]->designation_id,
			'user_role_id' => $result[0]->user_role_id,
			'date_of_birth' => $result[0]->date_of_birth,
			'date_of_leaving' => $result[0]->date_of_leaving,
			'gender' => $result[0]->gender,
			'marital_status' => $result[0]->marital_status,
			'contact_no' => $result[0]->contact_no,
			'address' => $result[0]->address,
			'address2' => $result[0]->address2,
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
			'office_shift_id' =>  $result[0]->office_shift_id,
			'office_location_id' =>  $result[0]->office_location_id,
			'is_active' => $result[0]->is_active,
			'date_of_joining' => $result[0]->date_of_joining,
			'languages_known' => $result[0]->languages_known,
			'all_departments' => $this->Department_model->all_departments(),
			'all_designations' => $this->Designation_model->all_designations(),
			'all_user_roles' => $this->Roles_model->all_user_roles(),
			'title' => $this->Xin_model->site_title(),
			'profile_picture' => $result[0]->profile_picture,
			'last_login_date' => $result[0]->last_login_date,
			'last_login_date' => $result[0]->last_login_date,
			'last_login_ip' => $result[0]->last_login_ip,
			'reporting_manager' => $result[0]->reporting_manager,
			'working_hours' => $result[0]->working_hours,
			'all_countries' => $this->Xin_model->get_countries(),
			'all_document_types' => $this->Employees_model->all_document_types(),
			'all_visa_under' => $this->Employees_model->all_visa_under(),
			'all_medical_card_types' => $this->Employees_model->all_medical_card_types(),
			'all_education_level' => $this->Employees_model->all_education_level(),
			'all_qualification_language' => $this->Employees_model->all_qualification_language(),
			'all_qualification_skill' => $this->Employees_model->all_qualification_skill(),
			'emp_pay_roll' => $this->Employees_model->emp_pay_roll($result[0]->user_id),
			'all_contract_types' => $this->Employees_model->all_contract_types(),
			'all_contracts' => $this->Employees_model->all_contracts(),
			'all_office_shifts' => '',
			'all_office_locations' => $this->Location_model->all_office_locations(),
			'progress_bar' => $progress_bar,
			'company_id'=>$result[0]->company_id,
			'phone_numbers_code' => phone_numbers_code(),
			'all_companies' => $this->Employees_model->all_companies()
			);

		$data['breadcrumbs'] = $this->lang->line('header_my_profile');
		$data['path_url'] = 'profile';
		if(!empty($this->userSession)){
			$data['subview'] = $this->load->view("employees/profile", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
		} else {
			redirect('');
		}
	}

	public function user_basic_info() {
	
		if($this->input->post('type')=='basic_info') {

		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */		
		if($this->input->post('first_name')==='') {
       		 $Return['error'] = $this->lang->line('xin_employee_error_first_name');
		} else if($this->input->post('last_name')==='') {
			$Return['error'] = $this->lang->line('xin_employee_error_last_name');
		} else if(empty($this->input->post('email'))) {
			 $Return['error'] = $this->lang->line('xin_employee_error_email');
		} else if (!filter_var($this->input->post('email'), FILTER_VALIDATE_EMAIL)) {
			$Return['error'] = $this->lang->line('xin_employee_error_invalid_email');
		} else if(empty($this->input->post('contact_no'))) {
			 $Return['error'] = $this->lang->line('xin_employee_error_contact_number');
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	    $languages_known=@implode(',',@$this->input->post('languages_known'));
		$data = array(
		'first_name' => change_to_caps($this->input->post('first_name')),
		'last_name' => change_to_caps($this->input->post('last_name')),
		'middle_name' => change_to_caps($this->input->post('middle_name')),
		'languages_known' => $languages_known,
		'email' => $this->input->post('email'),
		'reporting_manager' => $this->input->post('reporting_manager'),
		'personal_email' => $this->input->post('personal_email'),
		'date_of_birth' => format_date('Y-m-d',$this->input->post('date_of_birth')),
		'gender' => $this->input->post('gender'),
		'marital_status' => $this->input->post('marital_status'),
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
		'spouse_name' => $this->input->post('spouse_name'),
		);
		$id = $this->input->post('user_id');
		$result = $this->Employees_model->basic_info($data,$id);
		
		
		/*User Logs*/
		$affected_id= table_update_id('xin_employees','user_id',$id);
		userlogs('Employees-Basic Info-Update','Employee Profile Updated By Employee',$id,$affected_id['datas']);
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
				'country' => $result[0]->country,
				'all_countries' => $this->Xin_model->get_countries()
				);
		if(!empty($this->userSession)){
			$this->load->view('employees/dialog_employee_details', $data);
		} else {
			redirect('');
		}
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
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
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
		
		  
			$edit_perm='<li><a data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->contact_id . '" data-field_type="contact" data-field_role="edit"><i class="icon-pencil7"></i> Edit</a></li>';  
		
			//$delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->contact_id . '" data-token_type="contact"><i class="icon-trash"></i> Delete</a></li>';

		  	if($r->city!=''){$city=$r->city;}else{$city='-';}
		
			$data[] = array(
				$r->contact_name . ' ' .$primary . ' '.$secondary,
				$r->relation,
				$city,
				$r->mobile_phone,
				'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$delete_perm.'</ul></li></ul>',
			);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $contacts->num_rows(),
			 "recordsFiltered" => $contacts->num_rows(),
			 "data" => $data
		);
	  echo json_encode($output);
	  exit();
     }

	public function qualification() {
		//set data
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("employees/employee_detail", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		$id = $this->uri->segment(3);
		$qualification = $this->Employees_model->set_employee_qualification($id);
		
		$data = array();

        foreach($qualification->result() as $r) {
			
			$education = $this->Employees_model->read_education_information($r->education_level_id);
			$language = $this->Employees_model->read_qualification_language_information($r->language_id);
		
			$sdate = $r->from_year;
			$edate = $r->to_year;

			$time_period = $sdate.' - '.$edate;
			// get date
			$pdate = $time_period;

			$edit_perm='';
			$delete_perm='';
		  
			$edit_perm='<li><a data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->qualification_id . '" data-field_type="qualification" data-field_role="edit"><i class="icon-pencil7"></i> Edit</a></li>';  
		 		
		 
			//$delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->qualification_id . '" data-token_type="qualification"><i class="icon-trash"></i> Delete</a></li>';  
		  
		  

		  
		$data[] = array(
			$r->name,
			$pdate,
			$education[0]->type_name,
			'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$delete_perm.'</ul></li></ul>',
		);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $qualification->num_rows(),
			 "recordsFiltered" => $qualification->num_rows(),
			 "data" => $data
		);
	  echo json_encode($output);
	  exit();
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
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		$id = $this->uri->segment(3);
		$immigration = $this->Employees_model->set_employee_immigration($id);
		
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
			$edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->immigration_id . '" data-field_type="imgdocument" data-field_role="edit"><i class="icon-pencil7"></i> Edit</a></li>';  
		 		
		 
			//$delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->immigration_id . '" data-token_type="imgdocument"><i class="icon-trash"></i> Delete</a></li>';  
		
		  
		 
		  
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
			//'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$view_perm.$delete_perm.'</ul></li></ul>',
		);			
		
		
		
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $immigration->num_rows(),
			 "recordsFiltered" => $immigration->num_rows(),
			 "data" => $data
		);
	  echo json_encode($output);
	  exit();
     }

	public function experience() {
		//set data
		$data['title'] = $this->Xin_model->site_title();

		if(!empty($this->userSession)){
			$this->load->view("employees/employee_detail", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
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
		 
		
		  
			$edit_perm='<li><a data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->work_experience_id . '" data-field_type="work_experience" data-field_role="edit"><i class="icon-pencil7"></i> Edit</a></li>';  
		  
			//$delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->work_experience_id . '" data-token_type="work_experience"><i class="icon-trash"></i> Delete</a></li>';  
		  



		  if(trim($r->description)!=''){
			  $description=$r->description;
		  }else{$description ='<div align="center">-</div>';}
		$data[] = array(
			$r->company_name,
			$from_date,
			$to_date,
			$r->post,
			$description,
			'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$delete_perm.'</ul></li></ul>',
		);
    


	}

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $experience->num_rows(),
			 "recordsFiltered" => $experience->num_rows(),
			 "data" => $data
		);
	  echo json_encode($output);
	  exit();
     }

	public function bank_account() {
		//set data
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("employees/employee_detail", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		$id = $this->uri->segment(3);
		$bank_account = $this->Employees_model->set_employee_bank_account($id);
		
		$data = array();

        foreach($bank_account->result() as $r) {			
		
		$is_primary='';
	
		 
		 
		
		$edit_perm='';
		$view_perm='';

		$edit_perm='<li><a data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->bankaccount_id . '" data-field_type="bank_account" data-field_role="edit"><i class="icon-pencil7"></i> Edit</a></li>';
		//$is_primary='<li><a class="default-account" data-user_updated_id="'. $r->employee_id.'" data-bank_account_id="'. $r->bankaccount_id . '"><i class="icon-pushpin"></i> Make Default Account</a></li>';




	   $functions='<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$view_perm.$is_primary.'</ul></li></ul>';
		

		 if($r->is_primary==1){
			$success = '<span class="label ml-5 label-success"">default</span>';
		 } else {
			 $success = '';
		 }
		 
		 
		$data[] = array(
			
			//$r->account_title,
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
	  echo json_encode($output);
	  exit();
     }
	
	public function contract() {
		//set data
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("employees/employee_detail", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		$id = $this->uri->segment(3);
		$contract = $this->Employees_model->set_employee_contract($id);
		
		$data = array();

        foreach($contract->result() as $r) {			
			// designation
			//$designation = $this->Designation_model->read_designation_information($r->designation_id);
			//contract type
			$contract_type = $this->Employees_model->read_document_type_information($r->contract_type_id,'contract_type');
			// date
			$duration = $this->Xin_model->set_date_format($r->from_date).' '.$this->lang->line('dashboard_to').' '.$this->Xin_model->set_date_format($r->to_date);
		
			
			  $edit_perm='';
			  $delete_perm='';	
			  $view_perm='';				  
		    
				$edit_perm='<li><a data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->contract_id . '" data-field_type="contract" data-field_role="edit"><i class="icon-pencil7"></i> Edit</a></li>';  
			  
			
				//$delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->contract_id . '" data-token_type="contract"><i class="icon-trash"></i> Delete</a></li>';  
			 
			  
			 
			  
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
		);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $contract->num_rows(),
			 "recordsFiltered" => $contract->num_rows(),
			 "data" => $data
		);
	  echo json_encode($output);
	  exit();
     }

}
