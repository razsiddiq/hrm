<?php
/**
 * @Author Siddiqkhan
 *
 * @Announcement Controller
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Announcement extends MY_Controller {

	public $userSession = null;

	public function __construct() {
		Parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->database();
		$this->load->library('form_validation');
		$this->load->model("Announcement_model");
		$this->load->model("Xin_model");
		$this->load->model("Designation_model");
		$this->load->model("Department_model");
		$this->userSession = $this->session->userdata('username');
	}

	/**
	 * Function to set JSON output
	 *
	 * @param array $response
	 */
	public function output($response=array()){
		header("Access-Control-Allow-Origin: *"); //Set response header
		header("Content-Type: application/json; charset=UTF-8");
		exit(json_encode($response));
	}

	/**
	 * Listing all the announcements
	 *
	 */
	public function index(){
		if(!empty($this->userSession)){
			$data['title'] = $this->Xin_model->site_title();
			$data['all_designations'] = $this->Designation_model->all_designations();
			$data['breadcrumbs'] = 'Announcements';
			$data['path_url'] = 'user/user_announcement';
			$data['subview'] = $this->load->view("user/announcement_list", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			return ;
		}
		redirect('');
	}

	/**
	 * List all announcement of user
	 *
	 */
	public function announcement_list(){

		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession))
			$this->load->view("user/announcement_list", $data);
		else
			redirect('');

		/**
		 * Initialize Datatable
		 */
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		$announcement = $this->Announcement_model->get_announcements();
		$user_department=$this->Announcement_model->get_user_department($session['user_id']);
		$data = array();

		foreach($announcement->result() as $row) {
			if($row->department_id==$user_department || $row->department_id==0){
				// get user > added by
				$user = $this->Xin_model->read_user_info($row->published_by);
				// user full name
				$full_name = $user[0]->first_name.' '.$user[0]->last_name;
				// get date
				$startDate = $this->Xin_model->set_date_format($row->start_date);
				$endDate = $this->Xin_model->set_date_format($row->end_date);

				if($row->department_id == '0') {
					$ol = 'All Department';
				} else {

					$department = $this->Department_model->read_department_information($row->department_id);
					$ol = $department[0]->department_name;

				}
				$data[] = array('<span data-toggle="tooltip" data-placement="top" title="View"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-announcement_id="'. $row->announcement_id . '"><i class="fa fa-eye"></i></button></span>',
					$row->title,
					$row->summary,
					$ol,
					$startDate,
					$endDate,
					$full_name
				);
			}
		}

		echo json_encode(array(
			"draw" => $draw,
			"recordsTotal" => $announcement->num_rows(),
			"recordsFiltered" => $announcement->num_rows(),
			"data" => $data
		));
		exit();
	}
}
