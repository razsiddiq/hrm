<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approval extends CI_Controller {

	 // protected $userSession = null;

	public function __construct() {
		Parent::__construct();
		// $this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->library('email');
		$this->load->database();
		//load the model
		$this->load->model("Timesheet_model");	    
		$this->load->model('Employees_model');		
		$this->load->model('Xin_model');
		// $this->userSession = $this->session->userdata('username');
		  
	}

	public function passport_request_approval(){

		$id = base64_decode($this->uri->segment(4));
		$data['status'] = base64_decode($this->uri->segment(3));
		$check_data = $this->Timesheet_model->get_passport_request($id)[0];

		if($check_data->status == 0){
			if($data['status'] == 1){
				$msg_data['approval_title'] = 'Request has been approved.'; 

				$data['emp_data'] = $this->Employees_model->read_employee_information($check_data->user_id)[0];
				$data['visa_type'] = $this->Employees_model->checkVisaType($check_data->user_id);
				$reporting_manager = $this->Employees_model->read_employee_information($data['emp_data']->reporting_manager)[0];
				$data['reporting_manager'] = $reporting_manager->first_name.' '.$reporting_manager->middle_name.' '.$reporting_manager->last_name;
				$data['request_data'] = $check_data;
				$data['return_date'] = $check_data->return_date;
				$data['purpose'] = $check_data->purpose;

				if($check_data->request_type == 'passport_request'){

					$data['request_type'] = 'passport_request';

					$template = $this->Xin_model->read_email_template_info_bycode('Passport Request Approval');

					$reporting_manager_name = change_fletter_caps($data['reporting_manager']);
					$emp_name = change_fletter_caps($data['emp_data']->first_name.' '.$data['emp_data']->middle_name.' '.$data['emp_data']->last_name);
					$employee_address = change_fletter_caps($data['emp_data']->residing_address1.' '.$data['emp_data']->residing_address2);

					$message = '<div style="background: #f7eaea;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin: 0 auto;padding:20px;max-width: 65em;border: 2px solid #D40732;">'.
						str_replace(
							array(
								"{var reporting_manager}",
								"{var employee_name}",
								"{var employee_address}",
								"{var visa_type}",
								"{var purpose}",
								"{var return_date}",
								"{var curr_date}",
								"{var year}",
							),
							array(
								$reporting_manager_name,
								$emp_name,
								$employee_address,
								$data['visa_type'],
								$data['purpose'],
								date('d-m-Y',strtotime($data['return_date'])),
								date('d').' of '.date('F') .','.date('Y'),
								date('Y'),
							),htmlspecialchars_decode(stripslashes($template[0]->message))
						).'</div>';

					// $message = $this->load->view("employees/request_passport_mail_template",$data,true);
					$subject = 'Request for passport release';
					$hr_mail_by_location = get_hr_mail_bylocation($data['emp_data']->office_location_id,$data['emp_data']->user_id);
					$to_mail_array = array('hl@awok.com');
					foreach($hr_mail_by_location as $r) {
						array_push($to_mail_array, $r->email);
					}
					// $to_mail_array = array("siddiq.jalaludeen@awok.com");
			        $this->email->from(FROM_MAIL, "HRMS"); 
			        $this->email->to($to_mail_array);
			        $this->email->subject($subject);
			        $this->email->message($message);
			        $this->email->send();
			    }
		        $updata['status'] = 1;

			}else{
				$updata['status'] = 2;
				$msg_data['approval_title'] = 'Request has been rejected.'; 
			}

			$update_status = $this->Timesheet_model->update_passport_request_status($updata,$id); 
			
		}else{

			$msg_data['approval_title'] = 'Link has been expired.'; 
		}

		$this->load->view("approval",$msg_data);
	}

}