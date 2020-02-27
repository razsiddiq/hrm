<?php
/**
 * @Author Siddiqkhan
 *
 * @Company Controller
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends MY_Controller {

	public $userSession = null;

	public function __construct() {
        Parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->database();
		$this->load->library('form_validation');
		//load the models
		$this->load->model("Company_model");
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
		$data['all_countries'] = $this->Xin_model->get_countries();
		$data['get_company_types'] = $this->Company_model->get_company_types();
		$data['breadcrumbs'] = $this->lang->line('module_company_title');
		$data['path_url'] = 'company';
		if(in_array('3',role_resource_ids()) || in_array('3a',role_resource_ids()) || in_array('3e',role_resource_ids()) || in_array('3d',role_resource_ids()) || in_array('3v',role_resource_ids())) {
			if(!empty($this->userSession)){
				$data['subview'] = $this->load->view("company/company_list", $data, TRUE);
				$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
     }
 
    public function company_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("company/company_list", $data);
		} else {
			redirect('');
		}

		$draw = intval($this->input->get("draw"));
		
		$company = $this->Company_model->get_companies();
		
		$data = array();

	    foreach($company->result() as $r) {

			  $country = $this->Xin_model->read_country_info($r->country);
			  $company_type = $this->Company_model->read_company_type_information($r->type_id);
			  if($r->license_expiry_date!='')
			  {
				$license_expiry_date=$this->Xin_model->set_date_format($r->license_expiry_date);
			  }
			  else
			  {
			  	$license_expiry_date='N/A';
			  }

			  $edit_perm='';
			  $view_perm='';
			  $delete_perm='';

			  if(in_array('3e',role_resource_ids())) {
				$edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit-modal-data"  data-company_id="'. $r->company_id . '"><i class="icon-pencil7"></i> Edit</a></li>';
			  }
			  if(in_array('3v',role_resource_ids())) {
				$view_perm='<li><a href="#" data-toggle="modal" data-target=".view-modal-data" data-company_id="'. $r->company_id . '"><i class="icon-eye4"></i> View</a></li>';
			  }
			  if(in_array('3d',role_resource_ids())) {
				$delete_perm='<li><a class="delete" href="#" data-record-id="'. $r->company_id . '"><i class="icon-trash"></i> Delete</a></li>';
			  }

			  $data[] = array(
					$r->name,
					$company_type,
					$license_expiry_date,
					$r->email,
					$r->contact_number,
					$country[0]->country_name,
					'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$view_perm.$delete_perm.'</ul></li></ul>',

			  );
	    }

	    $output = array(
               "draw" => $draw,
                 "recordsTotal" => $company->num_rows(),
                 "recordsFiltered" => $company->num_rows(),
                 "data" => $data
		);

	    $this->output($output);
     }

	public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('company_id');
		$result = $this->Company_model->read_company_information($id);
		$data = array(
				'company_id' => $result[0]->company_id,
				'name' => $result[0]->name,
				'type_id' => $result[0]->type_id,
				'license_no' => $result[0]->license_no,
				'trading_name' => $result[0]->trading_name,
				'registration_no' => $result[0]->registration_no,
				'email' => $result[0]->email,
				'logo' => $result[0]->logo,
				'trade_copy' => $result[0]->trade_copy,
				'contact_number' => $result[0]->contact_number,
				'website_url' => $result[0]->website_url,
				'address_1' => $result[0]->address_1,
				'address_2' => $result[0]->address_2,
				'city' => $result[0]->city,
				'state' => $result[0]->state,
				'zipcode' => $result[0]->zipcode,
				'countryid' => $result[0]->country,
				'contract_start_date' => $result[0]->contract_start_date,
				'contract_end_date' => $result[0]->contract_end_date,
				'license_expiry_date' => $result[0]->license_expiry_date,				
				'all_countries' => $this->Xin_model->get_countries(),
				'get_company_types' => $this->Company_model->get_company_types()
				);
		if(!empty($this->userSession)){
			$this->load->view('company/dialog_company', $data);
		} else {
			redirect('');
		}
	}

	public function add_company() {
	
		if($this->input->post('add_type')=='company') {
			// Check validation for user input
			$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('website', 'Website', 'trim|required|xss_clean');
			$this->form_validation->set_rules('city', 'City', 'trim|required|xss_clean');
			$name = $this->input->post('name');
			$trading_name = $this->input->post('trading_name');
			$registration_no = $this->input->post('registration_no');
			$email = $this->input->post('email');
			$contact_number = $this->input->post('contact_number');
			$website = $this->input->post('website');
			$address_1 = $this->input->post('address_1');
			$address_2 = $this->input->post('address_2');
			$city = $this->input->post('city');
			$state = $this->input->post('state');
			$zipcode = $this->input->post('zipcode');
			$country = $this->input->post('country');
			$user_id = $this->input->post('user_id');
			$file = $_FILES['logo']['tmp_name'];
			$file_copy = $_FILES['trade_copy']['tmp_name'];

			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');

			/* Server side PHP input validation */
			if($name==='') {
				$Return['error'] = $this->lang->line('xin_error_cname_field');
			} if($this->input->post('license_no')==='') {
				$Return['error'] = $this->lang->line('xin_error_licno_field');
			} else if($this->input->post('license_expiry_date')==='') {
				$Return['error'] = $this->lang->line('xin_error_expiry_field');
			} else if($this->input->post('company_type')==='') {
				$Return['error'] = $this->lang->line('xin_error_ctype_field');
			} else if($email==='') {
				$Return['error'] = $this->lang->line('xin_error_cemail_field');
			} else if($contact_number==='') {
				$Return['error'] = $this->lang->line('xin_error_contact_field');
			} else if($city==='') {
				$Return['error'] = $this->lang->line('xin_error_city_field');
			} else if($zipcode==='') {
				$Return['error'] = $this->lang->line('xin_error_zipcode_field');
			} else if($country==='') {
				$Return['error'] = $this->lang->line('xin_error_country_field');
			}


			/* Check if file uploaded..*/
			else if($_FILES['logo']['size'] == 0) {
				$fname = 'no file';
				 $Return['error'] = $this->lang->line('xin_error_logo_field');
			} else {
				if(is_uploaded_file($_FILES['logo']['tmp_name'])) {
					//checking image type
					$allowed =  array('png','jpg','jpeg','gif');
					$filename = strtolower($_FILES['logo']['name']);
					$ext = pathinfo($filename, PATHINFO_EXTENSION);

					if(in_array($ext,$allowed)){
						$tmp_name = $_FILES["logo"]["tmp_name"];
						$bill_copy = "uploads/company/";
						// basename() may prevent filesystem traversal attacks;
						// further validation/sanitation of the filename may be appropriate
						$lname = basename($_FILES["logo"]["name"]);
						$newfilename = 'logo_'.round(microtime(true)).'.'.$ext;
						move_uploaded_file($tmp_name, $bill_copy.$newfilename);
						$fname = $newfilename;
					} else {
						$Return['error'] = $this->lang->line('xin_error_attatchment_type');
					}
				}
			}

			$fname_1='';
			if(is_uploaded_file($_FILES['trade_copy']['tmp_name'])) {
					//checking image type
					$allowed_1 =  array('png','jpg','jpeg','gif','pdf');
					$filename_1 = strtolower($_FILES['trade_copy']['name']);
					$ext_1 = pathinfo($filename_1, PATHINFO_EXTENSION);

					if(in_array($ext_1,$allowed_1)){
						$tmp_name = $_FILES["trade_copy"]["tmp_name"];
						$bill_copy = "uploads/company/";
						// basename() may prevent filesystem traversal attacks;
						// further validation/sanitation of the filename may be appropriate
						$lname = basename($_FILES["trade_copy"]["name"]);
						$newfilename = 'trade_copy_'.round(microtime(true)).'.'.$ext_1;
						move_uploaded_file($tmp_name, $bill_copy.$newfilename);
						$fname_1 = $newfilename;
					} else {
						$Return['error'] = $this->lang->line('xin_error_attatchment_type');
					}
				}


			if($Return['error']!=''){
				$this->output($Return);
			}


			if($this->input->post('contract_start_date')!=''){
				$contract_start_date=format_date('Y-m-d',$this->input->post('contract_start_date'));
			}else {$contract_start_date='';}
			if($this->input->post('contract_end_date')!=''){
				$contract_end_date=format_date('Y-m-d',$this->input->post('contract_end_date'));
			}else {$contract_end_date='';}
			if($this->input->post('license_expiry_date')!=''){
				$license_expiry_date=format_date('Y-m-d',$this->input->post('license_expiry_date'));
			}else {$license_expiry_date='';}

			$data = array(
			'name' => $this->input->post('name'),
			'type_id' => $this->input->post('company_type'),
			'license_no' => $this->input->post('license_no'),
			'trading_name' => $this->input->post('trading_name'),
			'registration_no' => $this->input->post('register_no'),
			'email' => $this->input->post('email'),
			'contact_number' => $this->input->post('country_code').$this->input->post('contact_number'),
			'website_url' => $this->input->post('website'),
			'address_1' => $this->input->post('address_1'),
			'address_2' => $this->input->post('address_2'),
			'city' => $this->input->post('city'),
			'state' => $this->input->post('state'),
			'zipcode' => $this->input->post('zipcode'),
			'country' => $this->input->post('country'),
			'contract_start_date' => $contract_start_date,
			'contract_end_date' => $contract_end_date,
			'license_expiry_date' => $license_expiry_date,
			'added_by' => $this->input->post('user_id'),
			'logo' => $fname,
			'trade_copy' => $fname_1,
			'created_at' => date('d-m-Y'),

			);
			$result = $this->Company_model->add($data);
			/*User Logs*/
			$affected_id= table_max_id('xin_companies','company_id');
			userlogs('Organization-Company-Add','New Company Added',$affected_id['field_id'],$affected_id['datas']);
			/*User Logs*/
			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_success_add_company');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	public function update() {
	
		if($this->input->post('edit_type')=='company') {
			$id = $this->uri->segment(3);
			// Check validation for user input
			$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('website', 'Website', 'trim|required|xss_clean');
			$this->form_validation->set_rules('city', 'City', 'trim|required|xss_clean');
			$name = $this->input->post('name');
			$trading_name = $this->input->post('trading_name');
			$registration_no = $this->input->post('registration_no');
			$email = $this->input->post('email');
			$contact_number = $this->input->post('contact_number');
			$website = $this->input->post('website');
			$address_1 = $this->input->post('address_1');
			$address_2 = $this->input->post('address_2');
			$city = $this->input->post('city');
			$state = $this->input->post('state');
			$zipcode = $this->input->post('zipcode');
			$country = $this->input->post('country');
			$user_id = $this->input->post('user_id');
			$file = $_FILES['logo']['tmp_name'];
			$trade_copy = $_FILES['trade_copy']['tmp_name'];

			if($this->input->post('contract_start_date')!=''){
				$contract_start_date=format_date('Y-m-d',$this->input->post('contract_start_date'));
			}else {$contract_start_date='';}
			if($this->input->post('contract_end_date')!=''){
				$contract_end_date=format_date('Y-m-d',$this->input->post('contract_end_date'));
			}else {$contract_end_date='';}
			if($this->input->post('license_expiry_date')!=''){
				$license_expiry_date=format_date('Y-m-d',$this->input->post('license_expiry_date'));
			}else {$license_expiry_date='';}


			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');

			/* Server side PHP input validation */


			if($name==='') {
				$Return['error'] = $this->lang->line('xin_error_cname_field');
			} if($this->input->post('license_no')==='') {
				$Return['error'] = $this->lang->line('xin_error_licno_field');
			} else if($this->input->post('license_expiry_date')==='') {
				$Return['error'] = $this->lang->line('xin_error_expiry_field');
			} else if($this->input->post('company_type')==='') {
				$Return['error'] = $this->lang->line('xin_error_ctype_field');
			} else if($email==='') {
				$Return['error'] = $this->lang->line('xin_error_cemail_field');
			} else if($contact_number==='') {
				$Return['error'] = $this->lang->line('xin_error_contact_field');
			} else if($city==='') {
				$Return['error'] = $this->lang->line('xin_error_city_field');
			} else if($zipcode==='') {
				$Return['error'] = $this->lang->line('xin_error_zipcode_field');
			} else if($country==='') {
				$Return['error'] = $this->lang->line('xin_error_country_field');
			}

			/* Check if file uploaded..*/
			else if($_FILES['logo']['size'] == 0) {
				 $fname = 'no file';
				 $no_logo_data = array(
				'name' => $this->input->post('name'),
				'type_id' => $this->input->post('company_type'),
				'license_no' => $this->input->post('license_no'),
				'trading_name' => $this->input->post('trading_name'),
				'registration_no' => $this->input->post('register_no'),
				'email' => $this->input->post('email'),
				'contact_number' => $this->input->post('country_code').$this->input->post('contact_number'),
				'website_url' => $this->input->post('website'),
				'address_1' => $this->input->post('address_1'),
				'address_2' => $this->input->post('address_2'),
				'city' => $this->input->post('city'),
				'state' => $this->input->post('state'),
				'zipcode' => $this->input->post('zipcode'),
				'country' => $this->input->post('country'),
				'contract_start_date' => $contract_start_date,
				'contract_end_date' => $contract_end_date,
				'license_expiry_date' => $license_expiry_date,
				);
				 $result = $this->Company_model->update_record_no_logo($no_logo_data,$id);
				/*User Logs*/
				 $affected_id= table_update_id('xin_companies','company_id',$id);
				 userlogs('Organization-Company-Update','Company Updated With Existing Logo',$id,$affected_id['datas']);
				/*User Logs*/
			} else {
				if(is_uploaded_file($_FILES['logo']['tmp_name'])) {
					//checking image type
					$allowed =  array('png','jpg','jpeg','gif');
					$filename = strtolower($_FILES['logo']['name']);
					$ext = pathinfo($filename, PATHINFO_EXTENSION);

					if(in_array($ext,$allowed)){
						$tmp_name = $_FILES["logo"]["tmp_name"];
						$bill_copy = "uploads/company/";
						$lname = basename($_FILES["logo"]["name"]);
						$newfilename = 'logo_'.round(microtime(true)).'.'.$ext;
						move_uploaded_file($tmp_name, $bill_copy.$newfilename);
						$fname = $newfilename;
						$data = array(
						'name' => $this->input->post('name'),
						'type_id' => $this->input->post('company_type'),
						'license_no' => $this->input->post('license_no'),
						'trading_name' => $this->input->post('trading_name'),
						'registration_no' => $this->input->post('register_no'),
						'email' => $this->input->post('email'),
						'contact_number' => $this->input->post('country_code').$this->input->post('contact_number'),
						'website_url' => $this->input->post('website'),
						'address_1' => $this->input->post('address_1'),
						'address_2' => $this->input->post('address_2'),
						'city' => $this->input->post('city'),
						'state' => $this->input->post('state'),
						'zipcode' => $this->input->post('zipcode'),
						'country' => $this->input->post('country'),
						'contract_start_date' => $contract_start_date,
						'contract_end_date' => $contract_end_date,
						'license_expiry_date' => $license_expiry_date,
						'logo' => $fname,
						);
						// update record > model
						$result = $this->Company_model->update_record($data,$id);
						/*User Logs*/
						$affected_id= table_update_id('xin_companies','company_id',$id);
						userlogs('Organization-Company-Update','Company Updated With New Logo',$id,$affected_id['datas']);
						/*User Logs*/
					} else {
						$Return['error'] = $this->lang->line('xin_error_attatchment_type');
					}
				}
			}


			if(is_uploaded_file($_FILES['trade_copy']['tmp_name'])) {
					//checking image type
					$allowed_1 =  array('png','jpg','jpeg','gif','pdf');
					$filename_1 = strtolower($_FILES['trade_copy']['name']);
					$ext_1 = pathinfo($filename_1, PATHINFO_EXTENSION);

					if(in_array($ext_1,$allowed_1)){
						$tmp_name = $_FILES["trade_copy"]["tmp_name"];
						$bill_copy = "uploads/company/";
						$lname = basename($_FILES["trade_copy"]["name"]);
						$newfilename = 'trade_copy_'.round(microtime(true)).'.'.$ext_1;
						move_uploaded_file($tmp_name, $bill_copy.$newfilename);
						$fname_1 = $newfilename;
						$data = array(
						'trade_copy' => $fname_1,
						);
						$result = $this->Company_model->update_record($data,$id);
					} else {
						$Return['error'] = $this->lang->line('xin_error_attatchment_type');
					}
				}

			if($Return['error']!=''){
				$this->output($Return);
			}


			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_success_update_company');
			} else {
				$Return['error'] = $Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}
	
	public function delete() {
		$Return = array('result'=>'', 'error'=>'');
		$id = $this->uri->segment(3);
		/*User Logs*/
		$affected_row= table_deleted_row('xin_companies','company_id',$id);		
		userlogs('Organization-Company-Delete','Company Deleted',$id,$affected_row);
		/*User Logs*/
		$this->Company_model->delete_record($id);
		if(isset($id)) {
			$Return['result'] = $this->lang->line('xin_success_delete_company');
		} else {
			$Return['error'] = $Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
	}

}
