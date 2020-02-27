<?php
/**
 * @Author Siddiqkhan
 *
 * @Promotions Controller
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
		$data['path_url'] = 'user/user_promotion';
		if(!empty($this->userSession)){
			$data['subview'] = $this->load->view("user/promotion_list", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
		} else {
			redirect('');
		}
		  
	}
 
    public function promotion_list()
     {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("user/promotion_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$promotion = $this->Promotion_model->get_employee_promotions($this->userSession['user_id']);
		$data = array();

		foreach($promotion->result() as $r) {
		$user = $this->Xin_model->read_user_info($r->added_by);
		$full_name = $user[0]->first_name.' '.$user[0]->last_name;

		$employee = $this->Xin_model->read_user_info($r->employee_id);
		$employee_name = $employee[0]->first_name.' '.$employee[0]->last_name;
		$promotion_date = $this->Xin_model->set_date_format($r->promotion_date);
		$description =  html_entity_decode($r->description);
		$data[] = array('<span data-toggle="tooltip" data-placement="top" title="View"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-promotion_id="'. $r->promotion_id . '"><i class="fa fa-eye"></i></button></span>',
			$employee_name,
			$r->title,
			$promotion_date,
			$description,
			$full_name
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
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
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
		$this->Promotion_model->delete_record($id);
		if(isset($id)) {
			$Return['result'] = 'Promotion deleted.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
	}

}
