<?php
/**
 * @Author Siddiqkhan
 *
 * @Employees_last_login Controller
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Employees_last_login extends MY_Controller {

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
		$data['breadcrumbs'] = 'Employees Last Login';
		$data['path_url'] = 'employees_last_login';
		if(in_array('26',role_resource_ids())) {
			if(!empty($this->userSession)){
				$data['subview'] = $this->load->view("last_login/last_login_list", $data, TRUE);
				$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
	}

	public function last_login_list()
	{

		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("last_login/last_login_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));

		$employee = $this->Employees_model->get_employees_latest_login();

		$data = array();

		foreach($employee->result() as $r) {

			// login date and time
			if($r->email == CC_MAIL)
				continue;

			if($r->last_login_date==''){
				$edate = '-';
				$etime = '-';
			} else {
				$edate = $this->Xin_model->set_date_format($r->last_login_date);
				$last_login =  new DateTime($r->last_login_date);
				$etime = $last_login->format('h:i a');
			}
			// employee link
			if(in_array('13',role_resource_ids())) {
				$emp_link = '<a href="employees/detail/'.$r->user_id.'/">'.$r->employee_id.'</a>';
			} else {
				$emp_link = $r->employee_id;
			}

			$full_name = change_fletter_caps($r->first_name.' '.$r->middle_name.' '.$r->last_name);
			$role = $this->Xin_model->read_user_role_info($r->user_role_id);
			if($r->is_active==1): $status = 'Active'; elseif($r->is_active==0): $status = 'In Active'; endif;

			$data[] = array(
				$r->last_login_date,
				$emp_link,
				$full_name,
				$r->email,
				$edate,
				$etime,
				$role[0]->role_name,
				$status
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

}
