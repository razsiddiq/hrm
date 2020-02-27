<?php
/**
 * @Author Siddiqkhan
 *
 * @Forgot_password Controller
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Forgot_password extends CI_Controller {

	public function __construct() {
        Parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->database();
		$this->load->library('form_validation');
		//load the model
		$this->load->model("Payroll_model");
		$this->load->model("Xin_model");
		$this->load->model("Employees_model");
		$this->load->model("Designation_model");
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
		$data['title'] = APPLICATION_NAME;
		$data['slugs'] ='forgot_password';
		$this->load->view('user/forgot_password', $data);
	}
	
	public function send_mail()
	{
		$data['title'] = APPLICATION_NAME;
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		/* Server side PHP input validation */
		if($this->input->post('iemail')==='') {
			$Return['error'] = "Please enter your email.";
		} else if (!filter_var($this->input->post('iemail'), FILTER_VALIDATE_EMAIL)) {
			$Return['error'] = "Invalid email format";
		}
		
		if($Return['error']!=''){
			$this->output($Return);
		}
		
		if($this->input->post('iemail')) {
	
			$this->load->library('email');			
			//get company info
			$cinfo = $this->Xin_model->read_company_setting_info(1);
			//get email template
			$template = $this->Xin_model->read_email_template(2);
			//get employee info
			$query = $this->Xin_model->read_user_info_byemail($this->input->post('iemail'));
			
			$user = $query->num_rows();
			if($user > 0) {				
				
				$user_info = $query->result();
				$full_name = $user_info[0]->first_name.' '.$user_info[0]->middle_name.' '.$user_info[0]->last_name;				
				$subject = $template[0]->subject.' - '.$cinfo[0]->company_name;				
				
				$new_password=randomPassword();				
				$this->db->query('update xin_employees set password="'.$new_password.'" where user_id="'.$user_info[0]->user_id.'"');
				
				$site_url=str_replace('http://','',str_replace('https://','',base_url().'setup_password?email='.urlencode($user_info[0]->email).'&type=reset&uniqueid='.strtotime(date('Y-m-d')).'&randomid='.$new_password));
				
				if(email_notification('forgot_password') == 'yes') {
				$message = '
					<div>
					'.str_replace(
					array(
					"{var employee_name}",
					"{var site_name}",
					"{var site_url}",
					"{var year}",
					),array(
					$full_name,
					$cinfo[0]->company_name,
					$site_url,
					date('Y')
					),htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';
				
				
					if(TESTING_MAIL==TRUE){
					$this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
					$this->email->to(TO_MAIL);
					}else{
					$this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
					$this->email->to($this->input->post('iemail'));
					}
					$this->email->subject($subject);
					$this->email->message($message);
					if($this->email->send()){
					$Return['result'] = 'Reset password link has been sent to your email address.';
					}else{
					$Return['error'] = "Problem in mail sending.Contact HR Administrator";
					}
			}else{
					$Return['error'] = "Problem in mail sending.Contact HR Administrator";
			}					
			} else {				
				$Return['error'] = "Email address doesn't exist.";
			}
			$this->output($Return);
			exit;
		}
	}
}
