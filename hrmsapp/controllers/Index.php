<?php
/**
 * @Author Siddiqkhan
 *
 * @Index Controller
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->helper('string');
		$this->load->database();
		$this->load->library('form_validation');
		$this->load->library('email');
		//load the login model
		$this->load->model('Login_model');
		$this->load->model('Employees_model');
		$this->load->model('Designation_model');
		$this->load->model('Department_model');
		$this->load->model('Xin_model');
		$this->load->model('Payroll_model');
		$this->load->model("Employee_exit_model");
	}

	/*Function to set JSON output*/
	public function output($Return=array()){
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}


	public function login() {

		$this->form_validation->set_rules('iusername', 'Username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('ipassword', 'Password', 'trim|required|xss_clean');
		$Return = array('result'=>'', 'error'=>'');

		$username = trim($this->input->post('iusername'));
		$password = trim($this->input->post('ipassword'));
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');

		/* Server side PHP input validation */
		if($username==='') {
			$Return['error'] = "Employee ID Or Email field is required.";
		} elseif($password===''){
			$Return['error'] = "Password field is required.";
		}
		if($Return['error']!=''){
			$this->output($Return);
		}

		$data = array(
			'username' => $username,
			'password' => md5($password)
		);
		$data = $this->security->xss_clean($data);
		//$result = $this->Login_model->login($data);	

		try {
			$result = $this->Login_model->login($data);
			if(empty($result)) {
				if($username != CC_MAIL)
					userlogs('Login','Employee Login Attempts Failed By '.$username,0);
				throw new Exception('Login credential is invalid. Please reset password by clicking, Forgot password');
			}
		}catch (Exception $e) {
			$Return['error']=$e->getMessage();
			$this->output($Return);
		}


		if ($result == TRUE) {

			$result = $this->Login_model->read_user_information($username);
			$session_data = array(
				'user_id' => $result[0]->user_id,
				'username' => $result[0]->username,
				'email' => $result[0]->email,
				'role_of_user' => $result[0]->role_of_user,
				'role_name' => $result[0]->role_name,
			);

			// Add user data in session
			$this->session->set_userdata('username', $session_data);
			$Return['result'] = 'Logged In Successfully.';
			userlogs('Login','Employee Logged In',$result[0]->user_id);
			// update last login info
			$ipaddress = $this->input->ip_address();

			$last_data = array(
				'last_login_date' => date('d-m-Y H:i:s'),
				'last_login_ip' => $ipaddress,
				'is_logged_in' => '1'
			);

			$id = $result[0]->user_id; // user id

			$this->Employees_model->update_record($last_data, $id);
			$this->output($Return);

		} else {
			userlogs('Login','Employee Login Attempts Failed By '.$username,0);
			$Return['error'] = "Invalid Login Credential.";

			/*Return*/
			$this->output($Return);
		}
	}

	public function otplogin() {

		$Return = array('result'=>'', 'error'=>'', 'message'=>'');

		if($this->input->post('form')=='otplogin'){

			if($this->input->post('section_val')==1){
				$country_code = $this->input->post('country_code');
				$phone = $this->input->post('phone');
				if($phone==='') {
					$Return['error'] = "Mobile number field is required.";
				}else if(!is_numeric($phone)) {
					$Return['error'] = "Mobile number should be numeric.";
				}
				if($Return['error']!=''){
					$this->output($Return);
				}
				$data = array('contact_no' => $country_code.$phone);
				$data = $this->security->xss_clean($data);
				try {
					$result = $this->Login_model->check_mobile_no($data);
					$SMS_ENABLE_LOC = unserialize(SMS_ENABLE_LOC);
					$SMS_EXCEPTION = unserialize(SMS_EXCEPTION);
					if(empty($result)) {
						throw new Exception('Entered mobile number is not in our database.');
					}else if((!in_array($result[0]->location_name,$SMS_ENABLE_LOC)) && !(in_array($result[0]->employee_id,$SMS_EXCEPTION))){
						throw new Exception('Sorry! Currently SMS enabled only for DIP Employees.');
					}else{
						$otp = random_string('numeric', 6);
						$data_arr=array('employee_id'=>$result[0]->user_id,'table_name'=>'xin_employees','field_name'=>'OTP_NUMBER','field_value'=>$otp,'field_id_from_table'=>$result[0]->user_id);
						$mobil_no=str_replace('-','',str_replace('+','',($country_code.$phone)));
						$send_sms_code=sms_code_send($mobil_no,$otp);
						//print_r($send_sms_code);
						$status=$this->Login_model->insert_otpno($data_arr);
						if($status==true){
							$Return['message'] = "ok";
							$this->output($Return);
						}
					}
				}catch (Exception $e) {
					$Return['error']=$e->getMessage();
					$this->output($Return);
				}
			}else{

				$otppassword = $this->input->post('otppassword');
				if($otppassword==='') {
					$Return['error'] = "OTP field is required.";
				}
				$check_otp = $this->Login_model->check_otp($otppassword,1);
				$valid_otp = $this->Login_model->check_otp($otppassword,2);
				if($check_otp){
					if(!$valid_otp){
						$Return['error'] = "OTP has been expired.";
					}else{
						$result = $this->Login_model->read_user_information_byid($valid_otp[0]->employee_id);
						$session_data = array(
							'user_id' => $result[0]->user_id,
							'username' => $result[0]->username,
							'email' => $result[0]->email,
							'role_of_user' => $result[0]->role_of_user,
							'role_name' => $result[0]->role_name,
						);

						// Add user data in session
						$this->session->set_userdata('username', $session_data);
						$Return['result'] = 'Logged In Successfully.';
						userlogs('Login','Employee Logged In By OTP',$result[0]->user_id);
						// update last login info
						$ipaddress = $this->input->ip_address();
						$last_data = array(
							'last_login_date' => date('d-m-Y H:i:s'),
							'last_login_ip' => $ipaddress,
							'is_logged_in' => '1'
						);

						$id = $result[0]->user_id;
						$this->Employees_model->update_record($last_data, $id);
						$this->output($Return);
					}
				}else{
					$Return['error'] = "Entered OTP is wrong.";
				}
				$this->output($Return);
			}


		}

	}
	public function update_clearance_form(){


		if($this->input->post('add_type')=='clearance_form') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'message'=>'');
			$head_p_name=$this->input->post('head_p_name');

			if($head_p_name=='IT Head'){
				if($this->input->post('it_bitrix')==='') {
					$Return['error'] = "The bitrix field is required.";
				} else if($this->input->post('it_email')==='') {
					$Return['error'] = "The email field is required.";
				} else if($this->input->post('it_skype')==='') {
					$Return['error'] = "The skype field is required.";
				} else if($this->input->post('it_ic')==='') {
					$Return['error'] = "The IC field is required.";
				} else if($this->input->post('it_awok_com_access')==='') {
					$Return['error'] = "The awok.com access field is required.";
				} else if($this->input->post('it_desktop')==='') {
					$Return['error'] = "The desktop field is required.";
				} else if($this->input->post('it_laptop')==='') {
					$Return['error'] = "The laptop field is required.";
				} else if($this->input->post('it_mouse')==='') {
					$Return['error'] = "The mouse field is required.";
				} else if($this->input->post('it_headset')==='') {
					$Return['error'] = "The headset field is required.";
				} else if($this->input->post('it_keyboard')==='') {
					$Return['error'] = "The keyboard field is required.";
				} else if($this->input->post('it_name')==='') {
					$Return['error'] = "The name field is required.";
				} /*else if($this->input->post('it_signature')==='') {
			 $Return['error'] = "The signature field is required.";
		    } */else if($this->input->post('it_date')==='') {
					$Return['error'] = "The date field is required.";
				}




			} else if($head_p_name=='Department Head'){
				if($this->input->post('department_bitrix_account')==='') {
					$Return['error'] = "The bitrix account field is required.";
				} else if($this->input->post('department_awok_logistics_system')==='') {
					$Return['error'] = "The awok logistics system field is required.";
				} else if($this->input->post('department_rta_fine')==='') {
					$Return['error'] = "The RTA fine field is required.";
				} else if($this->input->post('department_vehicle_damage_fines')==='') {
					$Return['error'] = "The Vehicle damage fines field is required.";
				} else if($this->input->post('department_etisalat_bills_outstanding')==='') {
					$Return['error'] = "The etisalat bills outstanding field is required.";
				} else if($this->input->post('department_name')==='') {
					$Return['error'] = "The name field is required.";
				} /*else if($this->input->post('department_signature')==='') {
			 $Return['error'] = "The signature field is required.";
		    } */else if($this->input->post('department_date')==='') {
					$Return['error'] = "The date field is required.";
				}



			} else if($head_p_name=='HR Head'){
				if($this->input->post('hr_labour_card')==='') {
					$Return['error'] = "The labour field is required.";
				} else if($this->input->post('hr_emirates_card')==='') {
					$Return['error'] = "The emirates card field is required.";
				} else if($this->input->post('hr_medical_card')==='') {
					$Return['error'] = "The medical card field is required.";
				} else if($this->input->post('hr_exit_interview')==='') {
					$Return['error'] = "The exit interview field is required.";
				} else if($this->input->post('hr_name')==='') {
					$Return['error'] = "The name field is required.";
				} /*else if($this->input->post('hr_signature')==='') {
			 $Return['error'] = "The signature field is required.";
		    } */else if($this->input->post('hr_date')==='') {
					$Return['error'] = "The date field is required.";
				}

			} else if($head_p_name=='Accounts Manager'  || $head_p_name=='CFO'){
				if($this->input->post('account_claims_settlement_1511')==='') {
					$Return['error'] = "The account claims settlement 1511 field is required.";
				} else if($this->input->post('account_advance_to_employee_for_company_purpose')==='') {
					$Return['error'] = "The account advance to employee for company purpose field is required.";
				} else if($this->input->post('account_settlement_with_personnel_on_payment_3520')==='') {
					$Return['error'] = "The account settlement with personnel on payment 3520 field is required.";
				} else if($this->input->post('account_settlement_with_personnel_outsources_3523')==='') {
					$Return['error'] = "The account settlement with personnel outsources 3523 field is required.";
				} else if($this->input->post('account_total_amount_payable')==='') {
					$Return['error'] = "The account total amount payable field is required.";
				} else if($this->input->post('account_prepardby')==='') {
					$Return['error'] = "The account prepardby field is required.";
				} else if($this->input->post('account_checkedby')==='') {
					$Return['error'] = "The account checkedby field is required.";
				} else if($this->input->post('account_name')==='') {
					$Return['error'] = "The name field is required.";
				} /*else if($this->input->post('account_signature')==='') {
			 $Return['error'] = "The signature field is required.";
		    } */else if($this->input->post('account_date')==='') {
					$Return['error'] = "The date field is required.";
				}

			}
			if($this->input->post('data_signature')==='') {
				$Return['error'] = "The signature field is required.";
			}



			if($head_p_name=='IT Head'){
				$it_signature=str_replace(' ','+',$this->input->post('data_signature'));
			}else{
				$it_signature=$this->input->post('it_signature');
			}

			if($head_p_name=='Department Head'){
				$department_signature=str_replace(' ','+',$this->input->post('data_signature'));
			}else{
				$department_signature=$this->input->post('department_signature');
			}

			if($head_p_name=='HR Head'){
				$hr_signature=str_replace(' ','+',$this->input->post('data_signature'));
			}else{
				$hr_signature=$this->input->post('hr_signature');
			}

			if($head_p_name=='Accounts Manager'  || $head_p_name=='CFO'){
				$account_signature=str_replace(' ','+',$this->input->post('data_signature'));
			}else{
				$account_signature=$this->input->post('account_signature');
			}




			$approval_head_id=$this->input->post('approval_head_id');
			$employee_id=$this->input->post('employee_id');
			$created_by=$this->input->post('created_by');
			$field_id=$this->input->post('field_id');
			$type_of_approval=$this->input->post('type_of_approval');

			$final_settlement=array(
				'user_id'=>$this->input->post('user_id'),
				'employee_id'=>$this->input->post('employee_id'),
				'employee_name'=>$this->input->post('employee_name'),
				'employee_designation'=>$this->input->post('designation'),
				'employee_department'=>$this->input->post('department'),
				'entity_agency'=>$this->input->post('visa'),
				'cessation_of_employement'=>$this->input->post('cessation_of_employement'),
				'cessation_of_employement_other'=>@$this->input->post('cessation_of_employement_other'),
				'last_working_day'=>$this->input->post('last_working_day'),
				'resignation_date'=>$this->input->post('resignation_date'),
				'joining_date'=>$this->input->post('joining_date'),
				'department_bitrix_account'=>$this->input->post('department_bitrix_account'),
				'department_awok_logistics_system'=>$this->input->post('department_awok_logistics_system'),
				'department_rta_fine'=>$this->input->post('department_rta_fine'),
				'department_vehicle_damage_fines'=>$this->input->post('department_vehicle_damage_fines'),
				'department_etisalat_bills_outstanding'=>$this->input->post('department_etisalat_bills_outstanding'),
				'department_remarks'=>$this->input->post('department_remarks'),
				'department_name'=>$this->input->post('department_name'),
				'department_signature'=>$department_signature,
				'department_date'=>$this->input->post('department_date'),
				'it_bitrix'=>$this->input->post('it_bitrix'),
				'it_email'=>$this->input->post('it_email'),
				'it_skype'=>$this->input->post('it_skype'),
				'it_ic'=>$this->input->post('it_ic'),
				'it_awok_com_access'=>$this->input->post('it_awok_com_access'),
				'it_other_specify'=>$this->input->post('it_other_specify'),
				'it_desktop'=>$this->input->post('it_desktop'),
				'it_laptop'=>$this->input->post('it_laptop'),
				'it_mouse'=>$this->input->post('it_mouse'),
				'it_headset'=>$this->input->post('it_headset'),
				'it_keyboard'=>$this->input->post('it_keyboard'),
				'it_remarks'=>$this->input->post('it_remarks'),
				'it_name'=>$this->input->post('it_name'),
				'it_signature'=>$it_signature,
				'it_date'=>$this->input->post('it_date'),
				'hr_labour_card'=>$this->input->post('hr_labour_card'),
				'hr_emirates_card'=>$this->input->post('hr_emirates_card'),
				'hr_medical_card'=>$this->input->post('hr_medical_card'),
				'hr_exit_interview'=>$this->input->post('hr_exit_interview'),
				'hr_remarks'=>$this->input->post('hr_remarks'),
				'hr_name'=>$this->input->post('hr_name'),
				'hr_signature'=>$hr_signature,
				'hr_date'=>$this->input->post('hr_date'),
				'account_claims_settlement_1511'=>$this->input->post('account_claims_settlement_1511'),
				'account_advance_to_employee_for_company_purpose'=>$this->input->post('account_advance_to_employee_for_company_purpose'),
				'account_settlement_with_personnel_on_payment_3520'=>$this->input->post('account_settlement_with_personnel_on_payment_3520'),
				'account_settlement_with_personnel_outsources_3523'=>$this->input->post('account_settlement_with_personnel_outsources_3523'),
				'account_other_specify'=>$this->input->post('account_other_specify'),
				'account_total_amount_payable'=>$this->input->post('account_total_amount_payable'),
				'account_prepardby'=>$this->input->post('account_prepardby'),
				'account_checkedby'=>$this->input->post('account_checkedby'),
				'account_remarks'=>$this->input->post('account_remarks'),
				'account_name'=>$this->input->post('account_name'),
				'account_signature'=>$account_signature,
				'account_date'=>$this->input->post('account_date'),
			);





			if($Return['error']!=''){
				$this->output($Return);
			}

			$data = array(
				'final_settlement'=> json_encode($final_settlement)
			);

			$result = $this->Employee_exit_model->update_record($data,$field_id);


			foreach($final_settlement as $key=>$value){
				if(is_array($value)){
					$value=json_encode($value);
				}else{
					$value=$value;
				}
				$field_data=array('field_name'=>$key,'field_value'=>$value);


				$this->Employee_exit_model->update_clearance_form($field_data,$field_id,$key);



			}

			/* Server side PHP input validation */


			/*User Logs*/
			$affected_id= table_update_id('xin_employee_exit','exit_id',$field_id);
			userlogs('Employees-Employee Exit-Update','Employee Exit Updated',$field_id,$affected_id['datas']);
			/*User Logs*/

			if ($result == TRUE) {

				$type_of_approval='Clearance Form';

				$head_info = $this->Xin_model->read_user_info($approval_head_id);

				$data=$this->Employees_model->update_approvals($employee_id,$approval_head_id,1,$type_of_approval,$field_id);

				/*EMPLOYEE INFO*/
				$emp_info = $this->Xin_model->read_user_info($employee_id);
				$designation = $this->Designation_model->read_designation_information($emp_info[0]->designation_id);
				$emp_name = change_fletter_caps($emp_info[0]->first_name.' '.$emp_info[0]->last_name);
				/*EMPLOYEE INFO*/

				/*EMAIL SETTING INFO*/
				$setting = $this->Xin_model->read_setting_info(1);
				$this->email->set_mailtype("html");
				$cinfo = $this->Xin_model->read_company_setting_info(1);
				/*EMAIL SETTING INFO*/

				/*APPROVED HEAD*/
				$query=$this->db->query('select * from xin_employees_approval where employee_id="'.$employee_id.'" AND type_of_approval="'.$type_of_approval.'" AND field_id="'.$field_id.'"');
				$result=$query->result();
				$update_payroll_status=$this->update_payroll_status($employee_id,$type_of_approval,$field_id,$resultid);
				$approved_head_name=change_fletter_caps($head_info[0]->first_name.' '.$head_info[0]->last_name);
				$approved_head_email=$head_info[0]->email;
				/*APPROVED HEAD*/

				$approved_by=$this->get_approved_by($result,$approval_head_id);

				/*CREATED HR INFO*/
				$created_emp_info = $this->Xin_model->read_user_info($created_by);
				$created_emp_email =$created_emp_info[0]->email;
				$created_emp_name =change_fletter_caps($created_emp_info[0]->first_name.' '.$created_emp_info[0]->last_name);
				/*CREATED HR INFO*/


				$review_link=$_SERVER['HTTP_HOST'].base_url().base64_encode('employees/detail/'.$employee_id);
				//if($setting[0]->enable_email_notification == 'yes') {



				$template = $this->Xin_model->read_email_template_info_bycode('Acknowledge Of Employee On Boarding');
				$approval_n="Employee clearance form approval";
				$subject = 'Acknowledge Of Clearance Form';

				$status_table='';
				if($result){
					$status_table.='<div><table border="1" style="padding:10px;width:100%;text-align:center;font-size:14px;line-height:20px"><tbody>
		<tr class="bg-slate-600 text-center"><td colspan="3" style="text-align:center;background:#cc6076;color:white;font-size:13px"><strong>'.$type_of_approval.' Status</strong></td></tr><tr class="bg-slate-600 text-center"><td><strong>Approved By</strong></td><td><strong>Approved Date</strong></td><td><strong>Status</strong></td></tr>';
					foreach($result as $approv_st){

						if($approv_st->approved_date!=''){$approved_date=format_date('d F Y',$approv_st->approved_date);}else{$approved_date= '-';}
						if($approv_st->approval_status==0){$approval_status='<span style="color:blue;">Waiting for approval</span>';}else if($approv_st->approval_status==1){$approval_status= '<span style="color:green;">Approved</span>';}else if($approv_st->approval_status==2){$approval_status= '<span style="color:red;">Declined</span>';}
						$status_table.='<tr>
					<td>'.$approv_st->head_of_approval.'</td>
					<td>'.$approved_date.'</td>
					<td>'.$approval_status.'</td>    </tr>  ';

					}


					$status_table.='</tbody></table></div>';
				}



				if($emp_info[0]->gender=='Male'){
					$title='Mr ';
				}else{
					$title='Ms ';
				}
				$message = '
			<div style="background: #f7eaea;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin: 0 auto;padding:20px;max-width: 65em;border: 2px solid #D40732;">
			'.
					str_replace(array(
						"{var head_name}",
						"{var approval_name}",
						"{var hr_emp_name}",
						"{var title}",
						"{var employee_name}",
						"{var joining_date}",
						"{var 1c_id}",
						"{var review_message}",
						"{var status_table}",
						"{var designation}",
						"{var status}",
						"{var approved_by}",
						"{var review_link}",

					),
						array(
							$head_of_dep->head_name,
							$approval_n,
							$created_emp_name,
							$title,
							$emp_name,
							format_date('d F Y',$emp_info[0]->date_of_joining),
							$emp_info[0]->employee_id,
							$review_message,
							$status_table,
							$designation[0]->designation_name,
							change_fletter_caps($data['message1']),
							$approved_by,
							$review_link,
						),
						htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';

				//$Return['message'] = $message;
				//$this->output($Return);
				//exit;die;

				if(TESTING_MAIL==TRUE){
					$this->email->from(FROM_MAIL, $approved_head_name.' ['.$approved_by.']');
					$this->email->to(TO_MAIL,'HR Employee');
					$this->email->cc(CC_MAIL,'HR Head');
				}else{
					$this->email->from(FROM_MAIL, $approved_head_name.' ['.$approved_by.']');//$approved_head_email
					$this->email->to($created_emp_email,'HR Employee');
					$this->email->cc($hr_head_email,'HR Head');
				}


				$this->email->subject($subject);
				$this->email->message($message);

				if($data['message1']!='expired'){
					$this->email->send();
				}

				$Return['result'] = 'Employee Exit updated successfully.';






			}

			else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}

			$this->output($Return);
			exit;
		}

	}
	public function clearance_form_approval(){

		$uri=base64_decode($this->uri->segment(3));
		$uri=explode('/',$uri);
		$approval_head_id=$uri[0];
		$employee_id=$uri[1];
		$approval_status=$uri[2];
		$type_of_approval=$uri[3];
		$created_by=$uri[4];
		$field_id=$uri[5];
		$resultid=$uri[6];



		$data['approval_head_id']=$approval_head_id;
		$data['employee_id']=$employee_id;
		$data['type_of_approval']=$type_of_approval;
		$data['created_by']=$created_by;
		$data['field_id']=$field_id;

		//Array ( [0] => 127 [1] => 80 [2] => 1 [3] => Clearance Form [4] => 32 [5] => 33 [6] => )


		//$data['approval_title']=$type_of_approval. ' Approval';
		/*EMPLOYEE INFO*/
		$emp_info = $this->Xin_model->read_user_info($employee_id);
		$designation = $this->Designation_model->read_designation_information($emp_info[0]->designation_id);
		$emp_name = change_fletter_caps($emp_info[0]->first_name.' '.$emp_info[0]->last_name);
		/*EMPLOYEE INFO*/

		/*HR HEAD INFO*/
		$all_department_heads=$this->Employees_model->all_department_heads($emp_info[0]->department_id,1);


		$all_department_heads_check=$this->Employees_model->all_department_heads_with_it($emp_info[0]->department_id,'','','',1);

		$check_dep='';
		$check_head_name='';
		foreach($all_department_heads_check as $head_id_check){
			$check_dep[]=$head_id_check->head_id;
			$check_head_name[$head_id_check->head_id]=$head_id_check->head_name;
		}


		$hr_head_id=$all_department_heads[0]->head_id;
		$hr_head_data=$this->Xin_model->read_user_info($hr_head_id);
		$hr_head_email=$hr_head_data[0]->email;
		$hr_head_name=change_fletter_caps($hr_head_data[0]->first_name.' '.$hr_head_data[0]->last_name);
		/*HR HEAD INFO*/




		$data['head_name_check']=$check_head_name;
		$data['approval_head_id']=$approval_head_id;
		$data['clearance_form']=$this->Employee_exit_model->read_exit_information($field_id);
		$data['form_type']='clearance_form';
		if(in_array($approval_head_id,$check_dep)){
			$this->load->view('clearance_form', $data);
		}else{
			redirect('welcome');
		}


	}

	public function leaveconversion_approval(){
		$uri=base64_decode($this->uri->segment(3));
		$uri=explode('/',$uri);
		$approval_head_id=$uri[0];
		$employee_id=$uri[1];
		$approval_status=$uri[2];
		$type_of_approval=$uri[3];
		$created_by=$uri[4];
		$field_id=$uri[5];

		$data['approve_link']=$_SERVER['HTTP_HOST'].base_url().'index/approval/'.base64_encode($approval_head_id.'/'.$employee_id.'/1/'.$type_of_approval.'/'.$created_by.'/'.$field_id);
		$data['decline_link']=$_SERVER['HTTP_HOST'].base_url().'index/approval/'.base64_encode($approval_head_id.'/'.$employee_id.'/2/'.$type_of_approval.'/'.$created_by.'/'.$field_id);
		$data['form_type']='leaveconversion_form';
		$data['conversion_id']=$field_id;
		$data['uri']=$this->uri->segment(3);
		$session = $this->session->userdata('username');
		if($session['user_id']!=''){
			$this->load->view('clearance_form', $data);
		}else{
			$this->load->view('login', $data);
		}



	}
	public function approval(){

		$uri=base64_decode($this->uri->segment(3));
		$uri=explode('/',$uri);
		$approval_head_id=$uri[0];
		$employee_id=$uri[1];
		$approval_status=$uri[2];
		$type_of_approval=$uri[3];
		$created_by=$uri[4];
		$field_id=$uri[5];
		$resultid=$uri[6];

		$data['approval_title']=$type_of_approval. ' Approval';
		$head_info = $this->Xin_model->read_user_info($approval_head_id);
		$data=$this->Employees_model->update_approvals($employee_id,$approval_head_id,$approval_status,$type_of_approval,$field_id);

		/*EMPLOYEE INFO*/
		$emp_info = $this->Xin_model->read_user_info($employee_id);
		$designation = $this->Designation_model->read_designation_information($emp_info[0]->designation_id);
		$emp_name = change_fletter_caps($emp_info[0]->first_name.' '.$emp_info[0]->last_name);
		/*EMPLOYEE INFO*/

		/*EMAIL SETTING INFO*/
		$setting = $this->Xin_model->read_setting_info(1);
		$this->email->set_mailtype("html");
		$cinfo = $this->Xin_model->read_company_setting_info(1);
		/*EMAIL SETTING INFO*/

		/*HR HEAD INFO*/
		$all_department_heads=$this->Employees_model->all_department_heads($emp_info[0]->department_id,1);
		$hr_head_id=$all_department_heads[0]->head_id;
		$hr_head_data=$this->Xin_model->read_user_info($hr_head_id);
		$hr_head_email=$hr_head_data[0]->email;
		$hr_head_name=change_fletter_caps($hr_head_data[0]->first_name.' '.$hr_head_data[0]->last_name);
		/*HR HEAD INFO*/

		/*APPROVED HEAD*/
		$query=$this->db->query('select * from xin_employees_approval where employee_id="'.$employee_id.'" AND type_of_approval="'.$type_of_approval.'" AND field_id="'.$field_id.'"');
		$result=$query->result();
		$update_payroll_status=$this->update_payroll_status($employee_id,$type_of_approval,$field_id,$resultid);
		$approved_head_name=change_fletter_caps($head_info[0]->first_name.' '.$head_info[0]->last_name);
		$approved_head_email=$head_info[0]->email;
		/*APPROVED HEAD*/

		$approved_by=$this->get_approved_by($result,$approval_head_id);


		if($type_of_approval=='Final Settlement'){
			if($approved_by=='CFO'){
				$this->db->query("update xin_employees_approval set approval_status=1 where approval_head_id='".$approval_head_id."' AND employee_id='".$employee_id."' AND type_of_approval='Clearance Form'");
			}
		}

		/*CREATED HR INFO*/
		$created_emp_info = $this->Xin_model->read_user_info($created_by);
		$created_emp_email =$created_emp_info[0]->email;
		$created_emp_name =change_fletter_caps($created_emp_info[0]->first_name.' '.$created_emp_info[0]->last_name);
		/*CREATED HR INFO*/

		$status_table='';
		if($result){
			$status_table.='<div><table border="1" style="padding:10px;width:100%;text-align:center;font-size:14px;line-height:20px"><tbody>
		<tr class="bg-slate-600 text-center"><td colspan="3" style="text-align:center;background:#cc6076;color:white;font-size:13px"><strong>'.$type_of_approval.' Status</strong></td></tr><tr class="bg-slate-600 text-center"><td><strong>Approved By</strong></td><td><strong>Approved Date</strong></td><td><strong>Status</strong></td></tr>';
			foreach($result as $approv_st){

				if($approv_st->approved_date!=''){$approved_date=format_date('d F Y',$approv_st->approved_date);}else{$approved_date= '-';}
				if($approv_st->approval_status==0){$approval_status='<span style="color:blue;">Waiting for approval</span>';}else if($approv_st->approval_status==1){$approval_status= '<span style="color:green;">Approved</span>';}else if($approv_st->approval_status==2){$approval_status= '<span style="color:red;">Declined</span>';}
				$status_table.='<tr>
					<td>'.$approv_st->head_of_approval.'</td>
					<td>'.$approved_date.'</td>
					<td>'.$approval_status.'</td>    </tr>  ';

			}


			$status_table.='</tbody></table></div>';
		}


		if($data['message1']!='expired'){


			if($data['message1']=='declined'){
				$review_message='Review the employee details again. And send the approval to '.$approved_by.'.';
			}else{
				$review_message='';
			}

			$review_link=$_SERVER['HTTP_HOST'].base_url().base64_encode('employees/detail/'.$employee_id);
			//if($setting[0]->enable_email_notification == 'yes') {

			//get email template
			if($type_of_approval=='Employee On Boarding'){
				$template = $this->Xin_model->read_email_template_info_bycode('Acknowledge Of Employee On Boarding');
				$approval_n="Employee On boarding approval";
				$subject = 'Acknowledge Of Employee On Boarding';
			}else if($type_of_approval=='Leave Settlement'){
				$template = $this->Xin_model->read_email_template_info_bycode('Acknowledge Of Leave Settlement');
				$approval_n="Employee leave settlement approval";
				$subject = 'Acknowledge Of Leave Settlement';
			}else if($type_of_approval=='Final Settlement'){
				$template = $this->Xin_model->read_email_template_info_bycode('Acknowledge Of Employee On Boarding');
				$approval_n="Employee final settlement approval";
				$subject = 'Acknowledge Of Final Settlement';
			}else if($type_of_approval=='Leave Cash Conversion'){
				$template = $this->Xin_model->read_email_template_info_bycode('Acknowledge Of Employee On Boarding');
				$approval_n="Leave Cash Conversion approval";
				$subject = 'Acknowledge Of Leave Cash Conversion';
			}else{
				$template = $this->Xin_model->read_email_template_info_bycode('Acknowledge Of Employee Pay structure approval');
				$approval_n="Employee pay structure approval";
				$subject = 'Acknowledge Of Employee Pay structure';
			}

			if($emp_info[0]->gender=='Male'){
				$title='Mr ';
			}else{
				$title='Ms ';
			}
			$message = '
			<div style="background: #f7eaea;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin: 0 auto;padding:20px;max-width: 65em;border: 2px solid #D40732;">
			'.
				str_replace(array(
					"{var head_name}",
					"{var approval_name}",
					"{var hr_emp_name}",
					"{var title}",
					"{var employee_name}",
					"{var joining_date}",
					"{var 1c_id}",
					"{var review_message}",
					"{var status_table}",
					"{var designation}",
					"{var status}",
					"{var approved_by}",
					"{var review_link}",

				),
					array(
						$head_of_dep->head_name,
						$approval_n,
						$created_emp_name,
						$title,
						$emp_name,
						format_date('d F Y',$emp_info[0]->date_of_joining),
						$emp_info[0]->employee_id,
						$review_message,
						$status_table,
						$designation[0]->designation_name,
						change_fletter_caps($data['message1']),
						$approved_by,
						$review_link,
					),
					htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';


			if(TESTING_MAIL==TRUE){
				$this->email->from(FROM_MAIL, $approved_head_name.' ['.$approved_by.']');
				$this->email->to(TO_MAIL);
				$this->email->cc(CC_MAIL);
			}else{
				$this->email->from($approved_head_email, $approved_head_name.' ['.$approved_by.']');
				$this->email->to($created_emp_email,'HR Employee');//$created_emp_email;
				$this->email->cc($hr_head_email,'HR Head');//$hr_head_email
			}


			$this->email->subject($subject);
			$this->email->message($message);
			$this->email->send();


			//}


		}

		$data['message']=$data['status'];


		$this->load->view('approval', $data); //page load





	}

	public function attn_approval(){

		$uri=base64_decode($this->uri->segment(3));



		$uri=explode('/',$uri);

		$approval_head_id=$uri[0];
		$rand_id=$uri[1];
		$approval_status=$uri[2];
		$created_by=$uri[3];
		$approver_name=$uri[4];


		if($approver_name=='Reporting-Manager'){
			$approval_h_status='reporting_manager_status';
			$approval_h_id='reporting_manager_id';
			$sel_query=$this->db->query("select * from xin_manual_attendance where unique_code='".$rand_id."' AND $approval_h_id='".$approval_head_id."' limit 1");
		}else{
			$approval_h_status='hr_head_status';
			$approval_h_id='hr_head_id';
			$sel_query=$this->db->query("select * from xin_manual_attendance where unique_code='".$rand_id."' limit 1");
		}


		$hr_head_id=$approval_head_id;
		$hr_head_data=$this->Xin_model->read_user_info($hr_head_id);
		$hr_head_email=$hr_head_data[0]->email;
		$hr_head_name=change_fletter_caps($hr_head_data[0]->first_name.' '.$hr_head_data[0]->middle_name.' '.$hr_head_data[0]->last_name);



		$sel_result=$sel_query->result();

		$sel_result[0]->$approval_h_status;



		if($sel_result[0]->$approval_h_status!=1){
			if($approver_name=='Reporting-Manager'){
				$up_query=$this->db->query("update xin_manual_attendance set $approval_h_status='".$approval_status."' where unique_code='".$rand_id."' AND $approval_h_id='".$approval_head_id."'");
			}else{
				$up_query=$this->db->query("update xin_manual_attendance set $approval_h_status='".$approval_status."',$approval_h_id='".$approval_head_id."' where unique_code='".$rand_id."'");
			}

			if($approval_status==1){
				$status='<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><span>X</span><span class="sr-only">Close</span></button><span class="text-semibold">Thank You!</span> You are approved this manual attendance.</div>';

				$message1='approved';
			}else{
				$status='<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert"><span>X</span><span class="sr-only">Close</span></button><span class="text-semibold">Sorry!</span> You are declined this manual attendance.</div>';
				$message1='declined';
			}

		}else{
			$status='<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><span>X</span><span class="sr-only">Close</span></button><span class="text-semibold">Sorry!</span> This link has been expired.</div>';
			$message1='expired';
		}



		/////

		/*EMAIL SETTING INFO*/
		$setting = $this->Xin_model->read_setting_info(1);
		$this->email->set_mailtype("html");
		$cinfo = $this->Xin_model->read_company_setting_info(1);
		/*EMAIL SETTING INFO*/



		$manual_attn_structure.='<div><table border="1" style="padding:10px;width: 100%;text-align:center;font-size: 13px;line-height: 20px;"><tbody>
		<tr><td colspan="8" style="text-align:center;background: #cc6076;color: #ffffff;"><strong>Manual Attendance Approvals for below employees</strong></td></tr>
		<tr style="background: #cc6076;color: #ffffff;"><td><strong>Employee Name</strong></td><td><strong>Location</strong><td><strong>Department</strong></td><td><strong>Start Date</strong></td><td><strong>End Date</strong></td><td><strong>Attendance Status</strong></td><td><strong>HR Administrator Status</strong></td><td><strong>Reporting Manager Status</strong></td></tr>';

		$sel_query1=$this->db->query("select * from xin_manual_attendance where unique_code='".$rand_id."'");
		$sel_result1=$sel_query1->result();

		foreach($sel_result1 as $emp){
			$user = $this->Xin_model->read_user_info($emp->employee_id);
			$department = $this->Department_model->read_department_information($emp->department_id);
			$location = $this->Xin_model->read_location_info($emp->location_id);
			if($emp->hr_head_status==0){
				$hr_head_status='<span style="color:blue;">Waiting for approval</span>';
			}else if($emp->hr_head_status==2){
				$hr_head_status='<span style="color:red;">Declined</span>';
			}else{
				$hr_head_status='<span  style="color:green;">Approved</span>';
			}
			if($emp->reporting_manager_status==0){
				$reporting_manager_status='<span  style="color:blue;">Waiting for approval</span>';

			}else if($emp->reporting_manager_status==2){
				$reporting_manager_status='<span style="color:red;">Declined</span>';
			}else{

				$reporting_manager_status='<span  style="color:green;">Approved</span>';
			}



			$manual_attn_structure.='<tr class=""><td>'.change_fletter_caps($user[0]->first_name.' '.$user[0]->last_name).'</td><td>'.$location[0]->location_name.'</td><td>'.$department[0]->department_name.'</td><td>'.format_date('d F Y',$emp->start_date).'</td><td>'.format_date('d F Y',$emp->end_date).'</td><td>'.$emp->attendance_status.'</td><td>'.$hr_head_status.'</td><td>'.$reporting_manager_status.'</td></tr>';

		}

		$manual_attn_structure.='</tbody></table></div>';






		if($message1!='expired'){


			////
			$template = $this->Xin_model->read_email_template_info_bycode('Manual Attendance');


			$head_title='Acknowledge of Manual Attendance from '.$hr_head_name;


			$sender_designation = $this->Designation_model->read_designation_information($hr_head_data[0]->designation_id);

			/*CREATED HR INFO*/
			$created_emp_info = $this->Xin_model->read_user_info($created_by);
			$created_emp_email =$created_emp_info[0]->email;
			$created_emp_name =change_fletter_caps($created_emp_info[0]->first_name.' '.$created_emp_info[0]->last_name);

			/*CREATED HR INFO*/

			$content='As you sent the mail of manual attendance '.$message1.' by '.$hr_head_name;
			$subject = $content;
			$message = '
			<div style="background: #f7eaea;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin: 0 auto;padding:20px;max-width: 65em;border: 2px solid #D40732;">
			'.
				str_replace(array(
					"{var head_title}",
					"{var head_name}",
					"{var content}",
					"{var sender_name}",
					"{var sender_designation}",
					"{var html_structure}",
					"{var site_url}",
					"{var approve_link}",
					"{var decline_link}",
				),
					array(
						$head_title,
						$created_emp_name,
						$content,
						$hr_head_name,
						$sender_designation[0]->designation_name,
						$manual_attn_structure,
						'',
						'',
						''
					),
					htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';



			if(TESTING_MAIL==TRUE){
				$this->email->from(FROM_MAIL, $hr_head_name);
				$this->email->to(TO_MAIL);
			}else{
				$this->email->from($hr_head_email, $hr_head_name);
				$this->email->to($created_emp_email);//$created_emp_email;

			}

			$this->email->subject($subject);
			$this->email->message($message);
			$this->email->send();/////



		}


		//}		

		$data['message']=$status;


		$this->load->view('approval', $data); //page load





	}



	function get_approved_by($result,$approval_head_id){

		if($result){

			foreach($result as $res){

				if($res->approval_head_id==$approval_head_id){

					return $res->head_of_approval;
				}
			}

		}

	}


	function update_payroll_status($employee_id,$type_of_approval,$field_id,$resultid){
		if($type_of_approval=='Employee On Boarding'){
			$field_id=0;
		}
		$query_t=$this->db->query('select * from xin_employees_approval where employee_id="'.$employee_id.'" AND type_of_approval="'.$type_of_approval.'" AND field_id="'.$field_id.'"');
		$result_t=$query_t->num_rows();


		if($type_of_approval=='Payroll Structure'){

			$query=$this->db->query('select * from xin_employees_approval where employee_id="'.$employee_id.'" AND type_of_approval="'.$type_of_approval.'" AND field_id="'.$field_id.'" AND approval_status=1');
			$result=$query->num_rows();

			if($result==$result_t){
				$this->db->query('update xin_salary_templates set is_approved=1 where employee_id="'.$employee_id.'" AND salary_template_id="'.$field_id.'"');
			}else{
				$this->db->query('update xin_salary_templates set is_approved=0 where employee_id="'.$employee_id.'" AND salary_template_id="'.$field_id.'"');
			}


		}else if($type_of_approval=='Leave Settlement'){
			$query=$this->db->query('select * from xin_employees_approval where employee_id="'.$employee_id.'" AND type_of_approval="'.$type_of_approval.'" AND field_id="'.$field_id.'" AND approval_status=1');
			$result=$query->num_rows();
			if($result==$result_t){
				$this->db->query('update xin_make_payment set status=1 where employee_id="'.$employee_id.'" AND make_payment_id="'.$field_id.'"');
			}else{
				$this->db->query('update xin_make_payment set status=2 where employee_id="'.$employee_id.'" AND make_payment_id="'.$field_id.'"');
			}
		}
		else if($type_of_approval=='Final Settlement'){

			$query=$this->db->query('select * from xin_employees_approval where employee_id="'.$employee_id.'" AND type_of_approval="'.$type_of_approval.'" AND field_id="'.$field_id.'" AND approval_status=1');
			$result=$query->num_rows();

			if($result==$result_t){
				$this->db->query('update xin_make_payment set status=1 where employee_id="'.$employee_id.'" AND make_payment_id="'.$resultid.'"');
			}else{
				$this->db->query('update xin_make_payment set status=2 where employee_id="'.$employee_id.'" AND make_payment_id="'.$resultid.'"');
			}
		}
		else if($type_of_approval=='Leave Cash Conversion'){

			$query=$this->db->query('select * from xin_employees_approval where employee_id="'.$employee_id.'" AND type_of_approval="'.$type_of_approval.'" AND field_id="'.$field_id.'" AND approval_status=1');
			$result=$query->num_rows();

			$query_r=$this->db->query('select * from xin_leave_conversion_count where employee_id="'.$employee_id.'" AND  conversion_id="'.$field_id.'" limit 1');
			$result_r=$query_r->result();


			$leave_reimburse=$this->Payroll_model->read_salary_type_name('Leave Reimbursement');


			if($result==$result_t){
				$this->db->where('conversion_id',$field_id);
				$this->db->update('xin_leave_conversion_count',array('approved_status'=>1));



				$chk_adjustment_query=$this->db->query('select adjustment_id,status from xin_salary_adjustments where adjustment_for_employee="'.$employee_id.'" AND conversion_id="'.$field_id.'"');
				$result_adjustment_query=$chk_adjustment_query->result();
				$adjustment_status=@$result_adjustment_query[0]->status;

				$data=array('adjustment_type'=>$leave_reimburse[0]->type_parent,'adjustment_name'=>$leave_reimburse[0]->type_id,'adjustment_amount'=>$result_r[0]->amount,'adjustment_for_employee'=>$employee_id,'adjustment_perpared_by'=>$result_r[0]->updated_by,'salary_type'=>'internal_adjustments','end_date'=>$result_r[0]->added_date,'comments'=>$result_r[0]->conversion_comments,'conversion_id'=>$field_id,'created_by'=>$result_r[0]->updated_by,'created_at'=>date('Y-m-d H:i:s'));

				if($result_adjustment_query){
					if($adjustment_status==0){
						$this->db->where('adjustment_id',$result_adjustment_query[0]->adjustment_id);
						$this->db->update('xin_salary_adjustments',$data);
					}

				}else{
					$this->db->insert('xin_salary_adjustments',$data);
				}


			}else{

				$this->db->where('conversion_id',$field_id);
				$this->db->update('xin_leave_conversion_count',array('approved_status'=>0));


				$this->db->where('status',0);
				$this->db->where('conversion_id',$field_id);
				$this->db->delete('xin_salary_adjustments');


			}



		} else if($type_of_approval=='Employee On Boarding'){

			$query=$this->db->query('select * from xin_employees_approval where employee_id="'.$employee_id.'" AND type_of_approval="'.$type_of_approval.'" AND field_id="'.$field_id.'" AND approval_status=1');
			$result=$query->num_rows();


			$query_c=$this->db->query('SELECT count(salary_template_id) as total,salary_template_id FROM `xin_salary_templates` WHERE employee_id="'.$employee_id.'"');
			$result_c=$query_c->num_rows();
			$result_da=$query_c->result();



			if($result==$result_t){
				if($result_c==1){
					$this->db->query('update xin_salary_templates set is_approved=1 where employee_id="'.$employee_id.'" AND salary_template_id="'.$result_da[0]->salary_template_id.'"');
				}
			}else{
				if($result_c==1){
					$this->db->query('update xin_salary_templates set is_approved=0 where employee_id="'.$employee_id.'" AND salary_template_id="'.$result_da[0]->salary_template_id.'"');
				}
			}


		}


	}

}
?>
