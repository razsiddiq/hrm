<?php
/**
 * @Author Siddiqkhan
 *
 * @Transfer Controller
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Transfer extends MY_Controller {
	
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
	}
	
	/*Function to set JSON output*/
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
		$session = $this->session->userdata('username');
		$data['breadcrumbs'] = 'Transfer';
		$data['path_url'] = 'user/user_transfer';
		if(!empty($session)){ 
			$data['subview'] = $this->load->view("user/transfer_list", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
		} else {
			redirect('');
		}
		  
     }
 
    public function transfer_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("user/transfer_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$transfer = $this->Transfers_model->get_employee_transfers($session['user_id']);
		
		$data = array();

          foreach($transfer->result() as $r) {
			 			  
		// get user > added by
		$user = $this->Xin_model->read_user_info($r->added_by);
		// user full name
		$full_name = $user[0]->first_name.' '.$user[0]->last_name;
		
		// get user > employee_
		$employee = $this->Xin_model->read_user_info($r->employee_id);
		// employee full name
		$employee_name = $employee[0]->first_name.' '.$employee[0]->last_name;
		// get date
		$transfer_date = $this->Xin_model->set_date_format($r->transfer_date);
		// get department by id
		$department = $this->Department_model->read_department_information($r->transfer_department);
		// get location by id
		$location = $this->Location_model->read_location_information($r->transfer_location);
		// get status
		if($r->status==0): $status = 'Pending';
		elseif($r->status==1): $status = 'Accepted'; else: $status = 'Rejected'; endif;
		
		$data[] = array('<span data-toggle="tooltip" data-placement="top" title="View"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-transfer_id="'. $r->transfer_id . '"><i class="fa fa-eye"></i></button></span>',
			$employee_name,
			$transfer_date,
			$department[0]->department_name,
			$location[0]->location_name,
			$status,
			$full_name
		);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $transfer->num_rows(),
			 "recordsFiltered" => $transfer->num_rows(),
			 "data" => $data
		);
	  echo json_encode($output);
	  exit();
     }
}
