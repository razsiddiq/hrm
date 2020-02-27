<?php
/**
 * @Author Siddiqkhan
 *
 * @Location Controller
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Location extends MY_Controller {

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
		$this->load->model("Location_model");
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
		$data['all_companies'] = $this->Xin_model->get_companies();
		$data['all_employees'] = $this->Xin_model->all_employees();
		$data['breadcrumbs'] = $this->lang->line('xin_locations');
		$data['path_url'] = 'location';
		 if(in_array('4',role_resource_ids()) || in_array('4a',role_resource_ids()) || in_array('4e',role_resource_ids()) || in_array('4d',role_resource_ids()) || in_array('4v',role_resource_ids())) {
			if(!empty($this->userSession)){
			$data['subview'] = $this->load->view("location/location_list", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}		  
  }
 
  public function location_list()
  {

		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("location/location_list", $data);
		} else {
			redirect('');
		}

		$draw = intval($this->input->get("draw"));
		
		$location = $this->Location_model->get_locations();
		
		$data = array();

		foreach($location->result() as $r) {
			  $country = $this->Xin_model->read_country_info($r->country);
			  $company = $this->Xin_model->read_company_info($r->company_id);
			  $location_head = $this->Xin_model->location_read_user_info($r->location_head);
			  $head_name = change_fletter_caps($location_head[0]->first_name.' '.$location_head[0]->middle_name.' '.$location_head[0]->last_name);
			  $edit_perm='';
			  $view_perm='';
			  $delete_perm='';

		      if(in_array('4e',role_resource_ids())) {
				$edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit-modal-data"  data-location_id="'. $r->location_id . '"><i class="icon-pencil7"></i> Edit</a></li>';  
			  }
			  if(in_array('4v',role_resource_ids())) {
				$view_perm='<li><a href="#" data-toggle="modal" data-target=".view-modal-data" data-location_id="'. $r->location_id . '"><i class="icon-eye4"></i> View</a></li>'; 
			  }
			  if(in_array('4d',role_resource_ids())) {
				$delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->location_id . '"><i class="icon-trash"></i> Delete</a></li>';
			  }
			   
			  $data[] = array(
                    $r->location_name,
					$head_name,
                    $company[0]->name,
                    $r->city,
                    $country[0]->country_name,
					'<ul class="icons-list">
											<li class="dropdown">
												<a href="#" class="dropdown-toggle" data-toggle="dropdown">
													<i class="icon-menu9"></i>
												</a>
												<ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$view_perm.$delete_perm.'</ul>
											</li>
										</ul>',

              );
		}

	    $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $location->num_rows(),
			 "recordsFiltered" => $location->num_rows(),
			 "data" => $data
		);
        $this->output($output);
  }
	 
	public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('location_id');
		$result = $this->Location_model->read_location_information($id);
		$data = array(
				'location_id' => $result[0]->location_id,
				'company_id' => $result[0]->company_id,
				'location_head' => $result[0]->location_head,
				'currency_id' => $result[0]->currency_id,
				'location_manager' => $result[0]->location_manager,
				'location_name' => $result[0]->location_name,
				'email' => $result[0]->email,
				'phone' => $result[0]->phone,
				'fax' => $result[0]->fax,
				'address_1' => $result[0]->address_1,
				'address_2' => $result[0]->address_2,
				'city' => $result[0]->city,
				'state' => $result[0]->state,
				'zipcode' => $result[0]->zipcode,
				'countryid' => $result[0]->country,
				'all_countries' => $this->Xin_model->get_countries(),
				'all_companies' => $this->Xin_model->get_companies(),
				'all_employees' => $this->Xin_model->all_employees()
				);
		if(!empty($this->userSession)){
			$this->load->view('location/dialog_location', $data);
		} else {
			redirect('');
		}
	}

	public function add_location() {	
		if($this->input->post('add_type')=='location') {
			$this->form_validation->set_rules('company', 'Company', 'trim|required|xss_clean');
			$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
			$check_unique_names = $this->Xin_model->check_unique_names('xin_office_location','location_name','location_id',$this->input->post('name'),'');

			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');

			/* Server side PHP input validation */
			if($this->input->post('company')==='') {
				$Return['error'] = $this->lang->line('error_company_field');
			} else if($this->input->post('name')==='') {
				$Return['error'] = $this->lang->line('xin_error_lname_field');
			} else if($check_unique_names!=0){
				$Return['error'] = 'The location name already exist.Enter different one.';
			} else if($this->input->post('location_head')==='') {
				$Return['error'] = $this->lang->line('error_locationhead_field');
			} else if($this->input->post('city')==='') {
				$Return['error'] = $this->lang->line('xin_error_city_field');
			} else if($this->input->post('country')==='') {
				$Return['error'] = $this->lang->line('xin_error_country_field');
			} else if($this->input->post('phone')==='') {
				$Return['error'] = $this->lang->line('xin_error_contact_field');
			}

			if($Return['error']!=''){
				$this->output($Return);
			}

			$data = array(
			'company_id' => $this->input->post('company'),
			'location_name' => $this->input->post('name'),
			//'currency_id' => $this->input->post('currency_id'),
			'location_head' => $this->input->post('location_head'),
			'location_manager' => $this->input->post('location_manager'),
			'email' => $this->input->post('email'),
			'phone' => $this->input->post('country_code').$this->input->post('phone'),
			'fax' => $this->input->post('country_code1').$this->input->post('fax'),
			'address_1' => $this->input->post('address_1'),
			'address_2' => $this->input->post('address_2'),
			'city' => $this->input->post('city'),
			'state' => $this->input->post('state'),
			'zipcode' => $this->input->post('zipcode'),
			'country' => $this->input->post('country'),
			'added_by' => $this->input->post('user_id'),
			'created_at' => date('d-m-Y'),

			);
			$result = $this->Location_model->add($data);

			/*User Logs*/
			$affected_id= table_max_id('xin_office_location','location_id');
			userlogs('Organization-Location-Add','New Location Added',$affected_id['field_id'],$affected_id['datas']);
			/*User Logs*/


			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_success_add_location');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	public function update() {
	
		if($this->input->post('edit_type')=='location') {			
		$id = $this->uri->segment(3);		
		// Check validation for user input
		$this->form_validation->set_rules('company', 'Company', 'trim|required|xss_clean');
		$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		$check_unique_names = $this->Xin_model->check_unique_names('xin_office_location','location_name','location_id',$this->input->post('name'),$id);
		/* Server side PHP input validation */
		if($this->input->post('company')==='') {
        	$Return['error'] = $this->lang->line('error_company_field');
		} else if($this->input->post('name')==='') {
			$Return['error'] = $this->lang->line('xin_error_lname_field');
		} else if($check_unique_names!=0){
            $Return['error'] = 'The location name already exist.Enter different one.';
        } else if($this->input->post('location_head')==='') {
			$Return['error'] = $this->lang->line('error_locationhead_field');
		} else if($this->input->post('city')==='') {
			$Return['error'] = $this->lang->line('xin_error_city_field');
		} else if($this->input->post('country')==='') {
			$Return['error'] = $this->lang->line('xin_error_country_field');
		} else if($this->input->post('phone')==='') {
			$Return['error'] = $this->lang->line('xin_error_contact_field');
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'company_id' => $this->input->post('company'),
		'location_name' => $this->input->post('name'),
		'location_head' => $this->input->post('location_head'),
		'location_manager' => $this->input->post('location_manager'),
		'email' => $this->input->post('email'),
		'phone' => $this->input->post('country_code').$this->input->post('phone'),
		'fax' =>   $this->input->post('country_code1').$this->input->post('fax'),
		'address_1' => $this->input->post('address_1'),
		'address_2' => $this->input->post('address_2'),
		'city' => $this->input->post('city'),
		'state' => $this->input->post('state'),
		'zipcode' => $this->input->post('zipcode'),
		'country' => $this->input->post('country'),		
		);	
		
		$result = $this->Location_model->update_record($data,$id);		
		/*User Logs*/			 
		$affected_id= table_update_id('xin_office_location','location_id',$id);
		userlogs('Organization-Location-Update','Location Updated',$id,$affected_id['datas']);
		/*User Logs*/
			
		if ($result == TRUE) {
			$Return['result'] = $this->lang->line('xin_success_update_location');
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
		$affected_row= table_deleted_row('xin_office_location','location_id',$id);		
		userlogs('Organization-Location-Delete','Location Deleted',$id,$affected_row);
		/*User Logs*/
		$this->Location_model->delete_record($id);
		if(isset($id)) {
			$Return['result'] = $this->lang->line('xin_success_delete_location');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
	}

}
