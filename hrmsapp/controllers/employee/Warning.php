<?php
/**
 * @Author Siddiqkhan
 *
 * @Warning Controller
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Warning extends MY_Controller {

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
		$this->load->model("Warning_model");
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
		$data['all_warning_types'] = $this->Warning_model->all_warning_types();
		$data['breadcrumbs'] = 'Warning';
		$data['path_url'] = 'user/user_warning';
		if(!empty($this->userSession)){
			$data['subview'] = $this->load->view("user/warning_list", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
		} else {
			redirect('');
		}
		  
    }

    public function warning_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("user/warning_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$warning = $this->Warning_model->get_employee_warning($this->userSession['user_id']);
		$data = array();
        foreach($warning->result() as $r) {
			$user_by = $this->Xin_model->read_user_info($r->warning_by);
			$warning_by = $user_by[0]->first_name.' '.$user_by[0]->last_name;
			$warning_date = $this->Xin_model->set_date_format($r->warning_date);
			if($r->status==0): $status = 'Pending';
			elseif($r->status==1): $status = 'Accepted'; else: $status = 'Rejected'; endif;

			$warning_type = $this->Warning_model->read_warning_type_information($r->warning_type_id);
			$description =  html_entity_decode($r->description);

			$data[] = array('<span data-toggle="tooltip" data-placement="top" title="View"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-warning_id="'. $r->warning_id . '"><i class="fa fa-eye"></i></button></span>',
				$warning_date,
				$r->subject,
				$warning_type[0]->type,
				$status,
				$warning_by,
				$description
			);
        }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $warning->num_rows(),
			 "recordsFiltered" => $warning->num_rows(),
			 "data" => $data
		);
	  $this->output($output);
     }

}
