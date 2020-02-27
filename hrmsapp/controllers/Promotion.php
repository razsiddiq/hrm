<?php
/**
 * @Author Siddiqkhan
 *
 * @Promotion Controller
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Promotion extends MY_Controller {

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
		$this->load->model("Promotion_model");
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
		$data['breadcrumbs'] = 'Promotions';
		$data['path_url'] = 'promotion';
		if(in_array('20',role_resource_ids()) || in_array('20a',role_resource_ids()) || in_array('20e',role_resource_ids()) || in_array('20d',role_resource_ids()) || in_array('20v',role_resource_ids())) {
			if(!empty($this->userSession)){
			$data['subview'] = $this->load->view("promotion/promotion_list", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}		  
     }
 
    public function promotion_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("promotion/promotion_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		
		$promotion = $this->Promotion_model->get_promotions();
		
		$data = array();

		foreach($promotion->result() as $r) {

		$user = $this->Xin_model->read_user_info($r->added_by);
		$full_name = change_fletter_caps($user[0]->first_name.' '.$user[0]->middle_name.' '.$user[0]->last_name);
		
		// get user > employee_
		$employee = $this->Xin_model->read_user_info($r->employee_id);
		// employee full name
		$employee_name = change_fletter_caps($employee[0]->first_name.' '.$employee[0]->middle_name.' '.$employee[0]->last_name);
		// get promotion date
		$promotion_date = $this->Xin_model->set_date_format($r->promotion_date);
		
		$edit_perm='';
	    $view_perm='';
	    $delete_perm='';
	    if(in_array('20e',role_resource_ids())) {
		 	$edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit-modal-data"  data-promotion_id="'. $r->promotion_id . '"><i class="icon-pencil7"></i> Edit</a></li>';
	    }
	    if(in_array('20v',role_resource_ids())) {
			$view_perm='<li><a href="#" data-toggle="modal" data-target=".view-modal-data" data-promotion_id="'. $r->promotion_id . '"><i class="icon-eye4"></i> View</a></li>';
	    }
	    if(in_array('20d',role_resource_ids())) {
			$delete_perm='<li><a class="delete" href="#" data-record-id="'. $r->promotion_id . '"><i class="icon-trash"></i> Delete</a></li>';
	    }
			  
		$data[] = array(
			$employee_name,
			$r->title,
			$promotion_date,
			$full_name,
			'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$view_perm.$delete_perm.'</ul></li></ul>',
		);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $promotion->num_rows(),
			 "recordsFiltered" => $promotion->num_rows(),
			 "data" => $data
		);

	  $this->output($output);
     }

    public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('promotion_id');
		$result = $this->Promotion_model->read_promotion_information($id);
		$data = array(
				'promotion_id' => $result[0]->promotion_id,
				'employee_id' => $result[0]->employee_id,
				'title' => $result[0]->title,
				'promotion_date' => $result[0]->promotion_date,
				'description' => $result[0]->description,
				'all_employees' => $this->Xin_model->all_employees(),
				);
		if(!empty($this->userSession)){
			$this->load->view('promotion/dialog_promotion', $data);
		} else {
			redirect('');
		}
	}

	public function add_promotion() {
	
		if($this->input->post('add_type')=='promotion') {
		$Return = array('result'=>'', 'error'=>'');

		$description = $this->input->post('description');
		$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
		
		if($this->input->post('employee_id')==='') {
       		 $Return['error'] = "The employee field is required.";
		} else if($this->input->post('title')==='') {
			$Return['error'] = "The title field is required.";
		} else if($this->input->post('promotion_date')==='') {
			 $Return['error'] = "The promotion date field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'employee_id' => $this->input->post('employee_id'),
		'title' => $this->input->post('title'),
		'promotion_date' => $this->input->post('promotion_date'),
		'description' => $qt_description,
		'added_by' => $this->input->post('user_id'),
		'created_at' => date('d-m-Y'),
		
		);
		$result = $this->Promotion_model->add($data);
		/*User Logs*/
		$affected_id= table_max_id('xin_employee_promotions','promotion_id');
		userlogs('Employees-Employee Promotion-Add','Employee Promotion Added',$affected_id['field_id'],$affected_id['datas']);
		/*User Logs*/
		if ($result == TRUE) {
			$Return['result'] = 'Promotion added.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}

	public function update() {
	
		if($this->input->post('edit_type')=='promotion') {
			
		$id = $this->uri->segment(3);

		$Return = array('result'=>'', 'error'=>'');

		$description = $this->input->post('description');
		$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
		
		if($this->input->post('employee_id')==='') {
       		 $Return['error'] = "The employee field is required.";
		} else if($this->input->post('title')==='') {
			$Return['error'] = "The title field is required.";
		} else if($this->input->post('promotion_date')==='') {
			 $Return['error'] = "The promotion date field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'employee_id' => $this->input->post('employee_id'),
		'title' => $this->input->post('title'),
		'promotion_date' => $this->input->post('promotion_date'),
		'description' => $qt_description,		
		);
		
		$result = $this->Promotion_model->update_record($data,$id);		
		/*User Logs*/
		$affected_id= table_update_id('xin_employee_promotions','promotion_id',$id);
		userlogs('Employees-Employee Promotion-Update','Employee Promotion Updated',$id,$affected_id['datas']);
		/*User Logs*/
		
		if ($result == TRUE) {
			$Return['result'] = 'Promotion updated.';
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
		$affected_row= table_deleted_row('xin_employee_promotions','promotion_id',$id);
		userlogs('Employees-Employee Promotion-Delete','Employee Promotion Deleted',$id,$affected_row);		
		/*User Logs*/
		$this->Promotion_model->delete_record($id);
		if(isset($id)) {
			$Return['result'] = 'Promotion deleted.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
	}
}
