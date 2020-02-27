<?php
/**
 * Author Siddiqkhan
 *
 * Policy Controller
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Policy extends MY_Controller {

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
		$this->load->model("Policy_model");
		$this->load->model("Xin_model");
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

	public function index()
	{
		$data['title'] = $this->Xin_model->site_title();
		$data['all_companies'] = $this->Xin_model->get_companies();
		$data['breadcrumbs'] = $this->lang->line('xin_policies');
		$data['path_url'] = 'policy';
		$data['archive'] = '';

		if(!empty($this->userSession)){
			$data['subview'] = $this->load->view("policy/policy_list_custom", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
		} else {
			redirect('');
		}
	}

	public function policy_archive()
	{
		$data['title'] = $this->Xin_model->site_title();
		$data['all_companies'] = $this->Xin_model->get_companies();
		$data['breadcrumbs'] = 'Policy Archive';
		$data['path_url'] = 'policy';
		$data['archive'] = 1;

		// pr($data);die;

		if(!empty($this->userSession)){
			$data['subview'] = $this->load->view("policy/policy_list_custom", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
		} else {
			redirect('');
		}
	}

	public function policy_list()
	{

		$data['title'] = $this->Xin_model->site_title();

		if(!empty($this->userSession)){
			$this->load->view("policy/policy_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));


		$policy = $this->Policy_model->get_policies();

		$data = array();

		foreach($policy->result() as $r) {

			// get user > added by
			$user = $this->Xin_model->read_user_info($r->added_by);
			// user full name
			$full_name = $user[0]->first_name.' '.$user[0]->last_name;
			// get date
			$pdate = $this->Xin_model->set_date_format($r->created_at);
			// get company
			if($r->company_id=='0'){
				$company = $this->lang->line('xin_all_companies');
			} else {
				$p_company = $this->Xin_model->read_company_info($r->company_id);
				$company = $p_company[0]->name;
			}


			$data[] = array(
				$r->title,
				$company,
				$pdate,
				//$full_name,
				'<ul class="icons-list">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="icon-menu9"></i>
							</a>
	
							<ul class="dropdown-menu dropdown-menu-right">
								<li><a href="#" data-toggle="modal" data-target=".edit-modal-data-md"  data-policy_id="'. $r->policy_id . '"><i class="icon-pencil7"></i> Edit</a></li>
								<li><a href="#" data-toggle="modal" data-target=".view-modal-data-md" data-policy_id="'. $r->policy_id . '"><i class="icon-eye4"></i> View</a></li>
								<li><a class="delete" href="#" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->policy_id . '"><i class="icon-trash"></i> Delete</a></li>
							</ul>
						</li>
					</ul>',
			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $policy->num_rows(),
			"recordsFiltered" => $policy->num_rows(),
			"data" => $data
		);
		echo json_encode($output);
		exit();
	}

	public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('policy_id');
		$result = $this->Policy_model->read_policy_information($id);
		$data = array(
			'policy_id' => $result[0]->policy_id,
			'company_id' => $result[0]->company_id,
			'title' => $result[0]->title,
			'description' => $result[0]->description,
			'all_companies' => $this->Xin_model->get_companies()
		);

		if(!empty($this->userSession)){
			$this->load->view('policy/dialog_policy', $data);
		} else {
			redirect('');
		}
	}

	// Validate and add info in database
	public function add_policy() {

		if($this->input->post('add_type')=='policy') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');

			/* Server side PHP input validation */
			$description = $this->input->post('description');
			$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);

			if($this->input->post('company')==='') {
				$Return['error'] = $this->lang->line('xin_error_company');
			}
			else if($this->input->post('title')==='') {
				$Return['error'] = $this->lang->line('xin_error_title');
			}

			if($Return['error']!=''){
				$this->output($Return);
			}

			$data = array(
				'company_id' => $this->input->post('company'),
				'title' => $this->input->post('title'),
				'description' => $qt_description,
				'added_by' => $this->input->post('user_id'),
				'created_at' => date('d-m-Y'),

			);
			$result = $this->Policy_model->add($data);
			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_success_add_policy');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	// Validate and update info in database
	public function update() {

		if($this->input->post('edit_type')=='policy') {

			$id = $this->uri->segment(3);

			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');

			/* Server side PHP input validation */
			$description = $this->input->post('description');
			$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);

			if($this->input->post('company')==='') {
				$Return['error'] = $this->lang->line('xin_error_company');
			} else if($this->input->post('title')==='') {
				$Return['error'] = $this->lang->line('xin_error_title');
			}

			if($Return['error']!=''){
				$this->output($Return);
			}

			$data = array(
				'company_id' => $this->input->post('company'),
				'title' => $this->input->post('title'),
				'description' => $qt_description,
			);

			$result = $this->Policy_model->update_record($data,$id);

			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_success_update_policy');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	public function delete() {
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		$id = $this->uri->segment(3);
		$result = $this->Policy_model->delete_record($id);
		if(isset($id)) {
			$Return['result'] = $this->lang->line('xin_success_delete_policy');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
	}
}
