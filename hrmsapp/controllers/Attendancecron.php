<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author Siddiqkhan
 *
 * @Attendancecron Controller
 */
class Attendancecron extends MY_Controller {

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
		$this->load->library('email');
		$this->load->model("Timesheet_model");
		$this->load->model("Employees_model");
		$this->load->model("payroll_model");
		$this->load->model("Xin_model");
		$this->load->model("Department_model");
		$this->load->model("Designation_model");
		$this->load->model("Roles_model");
		$this->load->model("Location_model");
        $this->userSession = $this->session->userdata('username');
	}

	public function decimalHours($time)
	{
		$hms = explode(":", $time);
		$res = ($hms[0] + ($hms[1]/60));// + ($hms[2]/3600)
		//return round($res,2);
		return $res;
	}

	/*Expiry Document/Contract Mail Notification To HR Team Who Has Access Permission of Employee Start */
	public function document_expirty_cron(){
		$today_date=TODAY_DATE;
		$date_after_twomonths=date('Y-m-d',strtotime('+2months',strtotime($today_date)));
		$date_after_onemonths=date('Y-m-d',strtotime('+1months',strtotime($today_date)));
		$check_expiry_contracts=$this->Xin_model->check_expiry_contracts($today_date,$date_after_twomonths);
		$check_expiry_documents=$this->Xin_model->check_expiry_documents($today_date,$date_after_onemonths,$date_after_twomonths);
		$hr_emails=$this->Xin_model->get_hr_mail_address();

		if($check_expiry_contracts){
			$table_row='';
			$table_row.='<table border="1"><tr><th>Employee ID</th><th>Employee Name</th><th>Contract Type</th><th>Expiry Date</th></tr>';
			foreach($check_expiry_contracts as $exp_emp){
				$table_row.='<tr style="text-align:center;background:#cc6076;color:white;font-size:13px"><td>'.$exp_emp->employee_id.'</td><td>'.$exp_emp->first_name.'</td><td>'.$exp_emp->name.'</td><td>'.format_date('d F Y',$exp_emp->to_date).'</td></tr>';
			}
			$table_row.='</table>';
			if($hr_emails){
				foreach($hr_emails as $hr_em){
					$send_email=$hr_em->email;
					/*$setting = $this->Xin_model->read_setting_info(1);
					    if($setting[0]->enable_email_notification == 'yes') {
						//load email library
						//$this->load->library('email');
						$this->email->set_mailtype("html");
						//get company info
						$cinfo = $this->Xin_model->read_company_setting_info(1);
						//get email template
						$template = $this->Xin_model->read_email_template(16);
						$subject = $template[0]->subject.' - '.$cinfo[0]->company_name;
						$logo = base_url().'uploads/logo/'.$cinfo[0]->logo;
						$message = '
					<div style="background: #f7eaea;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin: 0 auto;padding:20px;max-width: 65em;border: 2px solid #D40732;">'.
					str_replace(array("{var site_name}","{var site_url}","{var table_row}","{var types}"),
					array($cinfo[0]->company_name,site_url(),$table_row,'contracts'),htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';



					if(TESTING_MAIL==TRUE){
					$this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
					$this->email->to(TO_MAIL);
					}else{
					$this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
					$this->email->to($send_email);
					}

						$this->email->subject($subject);
						$this->email->message($message);
						$this->email->send();

						//print_r($message);


					}*/
				}
			}
		}

		if($check_expiry_documents){
			$table_row='';
			$table_row.='<table border="1"><tr><th>Employee ID</th><th>Employee Name</th><th>Document Type</th><th>Document Number</th><th>Expiry Date</th></tr>';
			foreach($check_expiry_documents as $exp_emps){
				$table_row.='<tr style="text-align:center;background:#cc6076;color:white;font-size:13px"><td>'.$exp_emps->employee_id.'</td><td>'.$exp_emps->first_name.'</td><td>'.$exp_emps->type_name.'</td><td>'.$exp_emps->document_number.'</td><td>'.format_date('d F Y',$exp_emps->expiry_date).'</td></tr>';
			}
			$table_row.='</table>';
			if($hr_emails){
				foreach($hr_emails as $hr_em){
					$send_email=$hr_em->email;
					/*$setting = $this->Xin_model->read_setting_info(1);
					if($setting[0]->enable_email_notification == 'yes') {
						//load email library
						//$this->load->library('email');
						$this->email->set_mailtype("html");
						//get company info
						$cinfo = $this->Xin_model->read_company_setting_info(1);
						//get email template
						$template = $this->Xin_model->read_email_template(16);
						$subject = 'Document Going To Be Expired'.' - '.$cinfo[0]->company_name;
						$logo = base_url().'uploads/logo/'.$cinfo[0]->logo;
						$message = '
					<div style="background: #f7eaea;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin: 0 auto;padding:20px;max-width: 65em;border: 2px solid #D40732;">'.
					str_replace(array("{var site_name}","{var site_url}","{var table_row}","{var types}"),
					array($cinfo[0]->company_name,site_url(),$table_row,'documents'),htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';

						if(TESTING_MAIL==TRUE){
						$this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
						$this->email->to(TO_MAIL);
						}else{
						$this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
						$this->email->to($send_email);
						}
						$this->email->subject($subject);
						$this->email->message($message);
						$this->email->send();
						//print_r($message);
					}*/

				}
			}
		}
	}
	/*Expiry Document/Contract Mail Notification To HR Team Who Has Access Permission of Employee End */

	public function clearance_form_remaindermail(){
		$today_date=TODAY_DATE;
		$query=$this->db->query("SELECT * FROM `xin_employees_approval` WHERE created_at like '%".$today_date."%' AND approval_status=0 AND type_of_approval='Clearance Form'");
		$result=$query->result();
		$setting = $this->Xin_model->read_setting_info(1);
		$this->email->set_mailtype("html");
		foreach($result as $res){
			$type_of_exit=$res->type_of_approval;
			$head_id='';
			$created_at = date('Y-m-d');
			$employee_id=$res->employee_id;
			$field_id=$res->field_id;
			$approval_head_id=$res->approval_head_id;
			$head_of_approval=$res->head_of_approval;
			$user = $this->Xin_model->read_user_info($employee_id);
			$department_id=$user[0]->department_id;
			$office_location_id=$user[0]->office_location_id;
			$designation_id=$user[0]->designation_id;
			$location = $this->Xin_model->read_location_info($user[0]->office_location_id);
			$designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
			$emp_full_name = change_fletter_caps($user[0]->first_name.' '.$user[0]->last_name);

			$html_structure='';
			$currency=$this->Xin_model->currency_sign($number = null,$location_id='',$employee_id);

			$query_clearance=$this->db->query("select added_by,created_at,final_settlement,approval_form from xin_employee_exit where employee_id='".$employee_id."' AND approval_form='Clearance Form' AND exit_id='".$field_id."'");
			$result_clearance=$query_clearance->result();
			$final_settlement=json_decode($result_clearance[0]->final_settlement);
			$final_settlement = (array) $final_settlement;
			if($result_clearance){
				$cinfo = $this->Xin_model->read_company_setting_info(1);
				$head_info = $this->Xin_model->read_user_info($approval_head_id);
				$created_by=$result_clearance[0]->added_by;
				/*if($setting[0]->enable_email_notification == 'yes') {
                   //get email template
                $template = $this->Xin_model->read_email_template_info_bycode('Final Settlement');

               $html_structure='';


               $html_structure.='<div class="panel panel-flat" id="dvContainer"><div class="panel-heading text-center"><h5 class="panel-title">
               <strong>Clearance Form<br>(Employee/Contract)</strong></h5><div class="pull-right mr-10">Date Processed: '.date('d F Y',strtotime($created_at)).'</div>	</div>';
               $html_structure.='<div class="panel-body">';

               $html_structure.='<div class="col-lg-12 successtable-responsive"	><table class=" bg-teal-300 table table-md table-bordered "><tbody>


               <tr class=""><td>Employee Name</td><td>'.$final_settlement['employee_name'].'</td><td>Visa</td><td>'.$final_settlement['entity_agency'].'</td></tr>

               <tr class=""><td>Designation</td><td>'.$final_settlement['employee_designation'].'</td><td>Department</td><td>'.$final_settlement['employee_department'].'</td></tr>

               <tr class=""><td>Joining Date</td><td>'.format_date('d F Y',$final_settlement['joining_date']).'</td><td>Resignation Date</td><td>'.format_date('d F Y',$final_settlement['resignation_date']).'</td></tr>
               <tr class=""><td>Last Working Day</td><td>'.format_date('d F Y',$final_settlement['last_working_day']).'</td><td></td><td></td></tr>

               <tr class=""><td>Nature of Cessation of Employement<br> **pls.check applicable box**</td><td colspan="3">
               <table class="table table-md table-bordered ">

               <tr><div class="form-group">

                                               <label class="radio-inline radio-right">
                                                   <input name="cessation_of_employement" checked type="radio" value="Resignation">
                                                   '.$final_settlement['cessation_of_employement'].'
                                               </label>';

                               if($final_settlement['cessation_of_employement_other']!=''){
                   $html_structure.='<input  class="mt-10 form-input text-teal" name="cessation_of_employement_other" type="text"  value="'.$final_settlement['cessation_of_employement_other'].'">';
                               }

                                           $html_structure.='</div></tr>
               </table>

               </td></tr>
               <tr><td colspan="3">Persons in charge are required to sign in the respective sections and/or department below:<td><tr>';
               $html_structure.='</tbody></table></div>';

               $html_structure.='<div class="mt-10 col-lg-6 table-responsive" ><table class="table bg-teal-300 table-bordered  table-md"><tbody>
               <tr class=""><td colspan="3"><strong>1.Own Department\'s Clearance</strong></td></tr>
               <tr class=""><td>Bitrix Account</td><td>'.$final_settlement['department_bitrix_account'].'</td></tr>
           <tr class=""><td>Awok Logistics System (ALS)</td><td>'.$final_settlement['department_awok_logistics_system'].'</td></tr>
           <tr class=""><td>RTA Fine</td><td>'.$final_settlement['department_rta_fine'].'</td></tr>
           <tr class=""><td>Vehicle Damage / Fines</td><td>'.$final_settlement['department_vehicle_damage_fines'].'</td></tr>
           <tr class=""><td>Etisalat Bills Outstanding</td><td>'.$final_settlement['department_etisalat_bills_outstanding'].'</td></tr>
           <tr class=""><td>Remarks</td><td>'.$final_settlement['department_remarks'].'</td></tr>
           <tr class=""><td>Name</td><td>'.$final_settlement['department_name'].'</td></tr>
           <tr class=""><td>Signature</td><td>'.$final_settlement['department_signature'].'</td></tr>
           <tr><td>Date</td><td>'.$final_settlement['department_date'].'</td></tr>
               </tbody></table></div>';


               $html_structure.='<div class="mt-10 col-lg-6 table-responsive" ><table class="table bg-teal-300 table-bordered  table-md"><tbody>
               <tr class=""><td colspan="3"><strong> 2.IT Clearance</strong></td></tr>
               <tr class=""><td>Bitrix</td><td>'.$final_settlement['it_bitrix'].'</td></tr>
           <tr class=""><td>Email</td><td>'.$final_settlement['it_email'].'</td></tr>
           <tr class=""><td>Skype (if official)</td><td>'.$final_settlement['it_skype'].'</td></tr>
           <tr class=""><td>1c (if applicable)</td><td>'.$final_settlement['it_ic'].'</td></tr>
           <tr class=""><td>Awok.com Access</td><td>'.$final_settlement['it_awok_com_access'].'</td></tr>
           <tr class=""><td>Other/s (pls specify)</td><td>'.$final_settlement['it_other_specify'].'</td></tr>
           <tr class=""><td>Desktop</td><td>'.$final_settlement['it_desktop'].'</td></tr>
           <tr class=""><td>Laptop</td><td>'.$final_settlement['it_laptop'].'</td></tr>
           <tr class=""><td>Mouse</td><td>'.$final_settlement['it_mouse'].'</td></tr>
           <tr class=""><td>Headset</td><td>'.$final_settlement['it_headset'].'</td></tr>
           <tr class=""><td>Keyboard</td><td>'.$final_settlement['it_keyboard'].'</td></tr>
           <tr class=""><td>Remarks</td><td>'.$final_settlement['it_remarks'].'</td></tr>
           <tr class=""><td>Name</td><td>'.$final_settlement['it_name'].'</td></tr>
           <tr class=""><td>Signature</td><td>'.$final_settlement['it_signature'].'</td></tr>
           <tr><td>Date</td><td>'.$final_settlement['it_date'].'</td></tr>
               </tbody></table></div> <div class="clear-fix"></div>';



               $html_structure.='<div class="mt-10 col-lg-6 table-responsive" ><table class="table bg-teal-300 table-bordered  table-md"><tbody>
               <tr class=""><td colspan="3"><strong> 3.HR Clearance</strong></td></tr>
               <tr class=""><td>Labour Card</td><td>'.$final_settlement['hr_labour_card'].'</td></tr>
               <tr class=""><td>Emirates Card</td><td>'.$final_settlement['hr_emirates_card'].'</td></tr>
               <tr class=""><td>Medical Card</td><td>'.$final_settlement['hr_medical_card'].'</td></tr>
               <tr class=""><td>Exit Interview</td><td>'.$final_settlement['hr_exit_interview'].'</td></tr>

           <tr class=""><td>Remarks</td><td>'.$final_settlement['hr_remarks'].'</td></tr>
           <tr class=""><td>Name</td><td>'.$final_settlement['hr_name'].'</td></tr>
           <tr class=""><td>Signature</td><td>'.$final_settlement['hr_signature'].'</td></tr>
           <tr><td>Date</td><td>'.$final_settlement['hr_date'].'</td></tr>
               </tbody></table></div>';

                   $html_structure.='<div class="mt-10 col-lg-6 table-responsive" ><table class="table bg-teal-300 table-bordered  table-md"><tbody>
               <tr class=""><td colspan="3"><strong>4.Account\'s Clearance</strong></td></tr>
               <tr class=""><td>Claims Settlement (1511)</td><td>'.$final_settlement['account_claims_settlement_1511'].'</td></tr>
               <tr class=""><td>Advance to Employee for company purpose(3630) <br> 3630.01(AED)/3630.02(AED) (other currency)</td><td>'.$final_settlement['account_advance_to_employee_for_company_purpose'].'</td></tr>
               <tr class=""><td>Settlement with Personnel On Payment.(3520)</td><td>'.$final_settlement['account_settlement_with_personnel_on_payment_3520'].'</td></tr>
               <tr class=""><td>Settlement with Personnel Outsources.(3523)</td><td>'.$final_settlement['account_settlement_with_personnel_outsources_3523'].'</td></tr>
           <tr class=""><td>Other/s (if any)</td><td>'.$final_settlement['account_other_specify'].'</td></tr>
           <tr class=""><td>Total Amount Payable</td><td>'.$final_settlement['account_total_amount_payable'].'</td></tr>

           <tr class=""><td>Prepared By</td><td>'.$final_settlement['account_prepardby'].'</td></tr>
           <tr class=""><td>Checked By</td><td>'.$final_settlement['account_checkedby'].'</td></tr>

           <tr class=""><td>Remarks</td><td>'.$final_settlement['account_remarks'].'</td></tr>
           <tr class=""><td>Name</td><td>'.$final_settlement['account_name'].'</td></tr>
           <tr class=""><td>Signature</td><td>'.$final_settlement['account_signature'].'</td></tr>
           <tr><td>Date</td><td>'.$final_settlement['account_date'].'</td></tr>
               </tbody></table></div>';

           $html_structure=str_replace(array(
                   '<table',
                   '<tr class="bg-slate-600"',
                   '<tr class="bg-slate-600 text-center"',
                   '<td',
                   '<div class="panel-heading text-center">'
                   ),
                   array(

                   '<table border="1" style="padding:10px;width: 100%;font-size:14px;line-height:20px;" ',
                   '<tr style="text-align:center;background:#cc6076;color:white;font-size:13px" ',
                   '<tr style="text-align:center; background-color: #cc6076; border-color: #cc6076;
           color: #fff;" ',
                   '<td style="padding:6px;" ',
                   '<div class="panel-heading text-center" style="text-align: center;margin-bottom: 1em;font-weight: bold;">'
                   ),
                   htmlspecialchars_decode(stripslashes($html_structure)));




                   $head_title='Clearance Form Approval';
                   $subject = 'Clearance Form Approval Remainder';
                   $sender = $this->Xin_model->read_user_info($created_by);
                   $sender_designation = $this->Designation_model->read_designation_information($sender[0]->designation_id);
                   $sender_full_name = change_fletter_caps($sender[0]->first_name.' '.$sender[0]->last_name);


                   if($user[0]->gender=='Male'){
                   $title='Mr ';
                   }else{
                   $title='Ms ';
                   }
                   $message = '<html><head>
                   </head><body>
                   <div style="background:#f7eaea;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin: 0 auto;padding:20px;max-width: 65em;border: 2px solid #D40732;">
                   '.
                   str_replace(array(
                   "{var head_title}",
                   "{var content}",
                   "{var head_name}",
                   "{var title}",
                   "{var employee_name}",
                   "{var 1c_id}",
                   "{var html_structure}",
                   "{var site_url}",
                   "{var designation}",
                   "{var approve_link}",
                   "{var decline_link}",
                   "{var sender_name}",
                   "{var sender_designation}",
                   ),
                   array(
                   $head_title,
                   $head_title,
                   $head_of_approval,
                   $title,
                   $emp_full_name,
                   $user[0]->employee_id,
                   $html_structure,
                   '',
                   $designation[0]->designation_name,
                   $approve_link,
                   $decline_link,
                   $sender_full_name,
                   $sender_designation[0]->designation_name,
                   ),
                   htmlspecialchars_decode(stripslashes($template[0]->message))).'</div></body></html>';

                   //$Return['message']=$message;




                   if(TESTING_MAIL==TRUE){
                   $this->email->from(FROM_MAIL, $sender_full_name);
                   $this->email->to(TO_MAIL);
                   }else{
                   $this->email->from($sender[0]->email, $sender_full_name);
                   $this->email->to($head_info[0]->email);
                   }


                   $this->email->subject($subject);
                   $this->email->message($message);
                   $this->email->send();

               }*/



			}
		}

		//print_r($result);die;

		$date_after_twomonths=date('Y-m-d',strtotime('+2months',strtotime($today_date)));
		$date_after_onemonths=date('Y-m-d',strtotime('+1months',strtotime($today_date)));
		$check_expiry_contracts=$this->Xin_model->check_expiry_contracts($today_date,$date_after_twomonths);
		$check_expiry_documents=$this->Xin_model->check_expiry_documents($today_date,$date_after_onemonths,$date_after_twomonths);
		$hr_emails=$this->Xin_model->get_hr_mail_address();
		if($check_expiry_contracts){
			$table_row='';
			$table_row.='<table border="1"><tr><th>Employee ID</th><th>Employee Name</th><th>Contract Type</th><th>Expiry Date</th></tr>';
			foreach($check_expiry_contracts as $exp_emp){
				$table_row.='<tr style="text-align:center;background:#cc6076;color:white;font-size:13px"><td>'.$exp_emp->employee_id.'</td><td>'.$exp_emp->first_name.'</td><td>'.$exp_emp->name.'</td><td>'.format_date('d F Y',$exp_emp->to_date).'</td></tr>';
			}
			$table_row.='</table>';
			if($hr_emails){
				foreach($hr_emails as $hr_em){
					$send_email=$hr_em->email;
					$setting = $this->Xin_model->read_setting_info(1);
					/*if($setting[0]->enable_email_notification == 'yes') {
						//load email library
						//$this->load->library('email');
						$this->email->set_mailtype("html");
						//get company info
						$cinfo = $this->Xin_model->read_company_setting_info(1);
						//get email template
						$template = $this->Xin_model->read_email_template(16);
						$subject = $template[0]->subject.' - '.$cinfo[0]->company_name;
						$logo = base_url().'uploads/logo/'.$cinfo[0]->logo;
						$message = '
					<div style="background: #f7eaea;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin: 0 auto;padding:20px;max-width: 65em;border: 2px solid #D40732;">'.
					str_replace(array("{var site_name}","{var site_url}","{var table_row}","{var types}"),
					array($cinfo[0]->company_name,site_url(),$table_row,'contracts'),htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';



					if(TESTING_MAIL==TRUE){
					$this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
					$this->email->to(TO_MAIL);
					}else{
					$this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
					$this->email->to($send_email);
					}
						$this->email->subject($subject);
						$this->email->message($message);
						$this->email->send();

					}*/
				}
			}
		}

	}

	public function cron_email_miss_loginout(){

		$module_name= "Cron";
		$module_type= "Cron Missed Login/Logout";
		$details= "Missed Login/Logout Mail Running On ".date('Y-m-d H:i:s');
		systemlogs($module_name,$module_type,0,$details);
		$clockInEmployees = $this->Employees_model->get_employees_who_missed_clock_in();
		$clockOutEmployees = $this->Employees_model->get_employees_who_missed_clock_out();
		$template = $this->Xin_model->read_email_template(28);
		$cinfo = $this->Xin_model->read_company_setting_info(1);
		$this->send_email_missed_login_logout($clockInEmployees,'Login',$template,$cinfo);
		$this->send_email_missed_login_logout($clockOutEmployees,'Logout',$template,$cinfo);
	}

	public function debug_function(){
		$dateRange1 = date('H:i', time() - 7200);
		$dateRange2 = date('H:i', time() - 5400);
		$currentDay = date('H:i');
		var_dump($currentDay,$dateRange1,$dateRange2);
	}

	public function send_email_missed_login_logout($employees,$type,$template,$cinfo){
		$site_url=str_replace('http://','',str_replace('https://','',base_url()));
		$subject = str_replace(array("{var punch_time}"),array(
				$type),htmlspecialchars_decode(stripslashes($template[0]->subject))).' - '.$cinfo[0]->company_name;
		foreach ($employees as $item){

			$full_name = change_fletter_caps($item->first_name.' '.$item->last_name);
			$message = '<div>
				'.str_replace(array(
					"{var site_url}",
					"{var site_name}",
					"{var employee_name}",
					"{var punch_time}",
					"{var date}",
					"{var year}"),
					array(
						$site_url,
						$cinfo[0]->company_name,
						$full_name,
						$type,
						date('Y-m-d'),
						date('Y')),
					htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';
			$this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
			$sendEmailTo = ($item->email!='')? $item->email : $item->personal_email;
			$this->email->to($sendEmailTo);
			$this->email->subject($subject);
			$this->email->message($message);
			if($sendEmailTo!=''){			
				$this->email->send();
				$module_name= "Missed Login/Logout";
				$module_type= ucfirst($type);
				$track_id = $item->user_id;
				$details= "Missed ". $type." mail sent to ". $full_name . "(".$sendEmailTo.")";
				systemlogs($module_name,$module_type,$track_id,$details) ; 
			}
		}
	}

	public function action_attendance_calculation($check_in_time,$check_out_time,$user_id,$today_date,$shift_start='',$shift_end='',$week_off='',$department_id,$working_hours,$result_ramadan_val,$employee = null){
		$check_in_time=date("Y-m-d H:i",strtotime($check_in_time)).':00';
		if($check_out_time!=''){
			$check_out_time=date("Y-m-d H:i",strtotime($check_out_time)).':00';
		}
		$flexibleEmployees = getFlexibleEmployees();
		$isFlexible = in_array($user_id,$flexibleEmployees);

		// sajith
		if($isFlexible){

			$shift_start_flexible = strtotime($shift_start) - (60*60) - (60*60);
			$shift_start_flexible = date('H:i', $shift_start_flexible);

			$shift_end_flexible = strtotime($shift_end) + 60*60 + 60*60;
			$shift_end_flexible = date('H:i', $shift_end_flexible);

			$check_in_time_temp = date('H:i a',strtotime($check_in_time));
			if($check_out_time!=''){
				$check_out_time_temp = date("H:i a",strtotime($check_out_time));

				if ($check_out_time_temp > $shift_end_flexible){

					$out_date = explode(' ', $check_out_time);
					$check_out_time = date("Y-m-d H:i",strtotime($out_date[0].$shift_end_flexible)).':00';
				}
			}

			if ($check_in_time_temp < $shift_start_flexible){

				$in_date = explode(' ', $check_in_time);
		   		$check_in_time = date("Y-m-d H:i",strtotime($in_date[0].$shift_start_flexible)).':00';
			}

		}

		/*Find end shift time start*/
		$shift_in = new DateTime($shift_start);
		$shift_out = new DateTime($shift_end);
		$interval_shift = $shift_in->diff($shift_out);
		$shift_hours   = $interval_shift->format('%h');
		$shift_minutes   = $interval_shift->format('%i');
		/*Find end shift time end*/
		$working_hours_t=explode(':',$working_hours);
		$working_hours=$working_hours_t[0];
		$working_minutes=$working_hours_t[1];
		$attendanceStatus = '';
		if($result_ramadan_val!=''){
			$actual_working_hours = new DateTime($working_hours.':'.$working_minutes);
			$reduced_working_hours = new DateTime($result_ramadan_val);
			$remaining_hours = $actual_working_hours->diff($reduced_working_hours);
			$working_hours=$remaining_hours->format('%h');
			$working_minutes=$remaining_hours->format('%i');

			$diff_time=decimalHours($interval_shift->format('%h').':'.$interval_shift->format('%m'));
			$actual_working_hours=decimalHours($working_hours_t[0].':'.$working_hours_t[1]);
			if($diff_time==$actual_working_hours){
				$actual_shift_out_time = new DateTime($shift_end);
				$ramadan_reduced_time = new DateTime($result_ramadan_val);
				$interval_time = $actual_shift_out_time->diff($ramadan_reduced_time);
				$shift_end=$interval_time->format('%h').':'.$interval_time->format('%i');
			}
		}

		$working_hours_start=date("Y-m-d",strtotime($check_in_time)).' '.$shift_start;	/*Find Actual Workin hours end*/
		$working_hours_end1=date("Y-m-d",strtotime($check_in_time)).' '.$shift_start;
		$working_hours_end=date('Y-m-d H:i',strtotime("+".$working_hours." hours ".$working_minutes." minutes",strtotime($working_hours_end1)));
		$working_hours_end_time=new DateTime($working_hours_end);
		$working_hours_time=strtotime($working_hours_end);

		if($check_in_time!='' && $check_out_time!=''){
			if(strtotime($check_out_time) > strtotime($working_hours_start)){
				$clock_in = new DateTime($check_in_time);
				$clock_in2 = $clock_in->format('h:i a');
				$office_time =  new DateTime(date("Y-m-d",strtotime($check_in_time)).' '.$shift_start);
				$office_time_new = strtotime(date("Y-m-d",strtotime($check_in_time)).' '.$shift_start);
				$clock_in_time_new = strtotime($check_in_time);
				$clock_out = new DateTime($check_out_time);
				$clock_out2 = $clock_out->format('h:i a');
				$early_time =  new DateTime(date("Y-m-d",strtotime($check_out_time)).' '.$shift_end);
				$early_new_time = strtotime(date("Y-m-d",strtotime($check_out_time)).' '.$shift_end);
				$clock_out_time_new = strtotime($check_out_time);

				if($clock_in_time_new <= $office_time_new) {
					$total_time_l = '00:00';
				}
				else {
					$interval_late = $clock_in->diff($office_time);
					$hours_l   = $interval_late->format('%h');
					$minutes_l = $interval_late->format('%i');
					$total_time_l = $hours_l ."h ".$minutes_l."m"; // Total Late
				}


				if($working_hours_time <= $clock_out_time_new) {
					$total_time_e = '00:00';
				}
				else {
					$interval_lateo = $clock_out->diff($working_hours_end_time);
					$hours_e   = $interval_lateo->format('%h');
					$minutes_e = $interval_lateo->format('%i');
					$total_time_e = $hours_e ."h ".$minutes_e."m";
				}

				if(!$isFlexible){
					if($clock_in_time_new <= $office_time_new) {
						$c_in=date("Y-m-d",strtotime($check_in_time)).' '.$shift_start;
					}
					else {
						$c_in=date("Y-m-d",strtotime($check_in_time)).' '.date("H:i", $clock_in_time_new);
					}

					if($working_hours_time <= $clock_out_time_new) {
						$c_out=$working_hours_end;
					}
					else{
						$c_out=date("Y-m-d",strtotime($check_out_time)).' '.date("H:i", $clock_out_time_new);
					}

				}else{
					$c_in = $check_in_time;
					$c_out = $check_out_time;
				}

				/*Calculate Total Hours without OT*/
				
				$c_in = new DateTime($c_in);
				$c_out = new DateTime($c_out);
				$interval = $c_in->diff($c_out);
				$hours   = $interval->format('%h');
				$minutes = $interval->format('%i');
				$f_total_hours = new DateTime($hours.':'.$minutes);
				$f_lunch_hours = new DateTime(LUNCH_HOURS);
				$interval_lunch = $f_total_hours;

				if($employee!=null){
					if($employee->is_break_included == 1){
						$interval_lunch = $f_total_hours->diff($f_lunch_hours);
					}
				}
				else{
					$interval_lunch = $f_total_hours->diff($f_lunch_hours);
				}

				if(strtotime($hours.':'.$minutes) >= strtotime(LUNCH_HOURS)){
					$total_work_s=$interval_lunch->format('%h').'h '.$interval_lunch->format('%i').'m';
					$total_work_s = str_replace('%', '', $total_work_s);
					$total_work=$interval_lunch->format('%h').':'.$interval_lunch->format('%i');
					$total_work = str_replace('%', '', $total_work);
					$total_rest = $this->decimalHours($total_work);
				}
				else{
					$total_work_s='0h 0m';
					$total_work='00:00';
					$total_rest = $this->decimalHours($total_work);
				}
				$lunchHour = 0;
				if($employee->is_break_included == 1){
					$lunchHour = $f_lunch_hours->format("h");
				}

				// if($isFlexible){

				// 	$total_time_l = '00:00';
				// 	$total_time_e = '00:00';
				// 	$hoursWorkedInterval = $clock_in->diff($clock_out);
				// 	$hoursWorked = $hoursWorkedInterval->format("%H");
				// 	$minutesWorked = $hoursWorkedInterval->format("%I");

				// 	if($hoursWorked < $shift_hours){ // if employee didn't complete his working hours

				// 		$hoursWorkedInterval = DateTime::createFromFormat("h:i",$hoursWorkedInterval->format("%H:%I"));
				// 		$intervalShift = DateTime::createFromFormat("h:i",$interval_shift->format("%H:%I"));
				// 		$missingHours = $hoursWorkedInterval->diff($intervalShift);
				// 		$hours_e   = $missingHours->format('%H');
				// 		$minutes_e = $missingHours->format('%I');
				// 		$total_time_e = ($hours_e) ."h ".($minutes_e)."m";
				// 		$total_work_s = abs($hoursWorked-$lunchHour) ."h ".$minutesWorked."m";
				// 		$attendanceStatus = 'LT';
				// 	}
				// 	else{

				// 		$total_work_s= ($shift_hours-$lunchHour).'h 0m';
				// 		$attendanceStatus = 'P';
				// 	}
				// }
				$time_late=$total_time_l;
				$early_leaving=$total_time_e;


				if($early_new_time <= $clock_out_time_new) {
					$ot_out=date("Y-m-d",strtotime($check_out_time)).' '.$shift_end;
				} else {
					$ot_out=date("Y-m-d",strtotime($check_out_time)).' '.date("H:i", $clock_out_time_new);
				}

				$overtime_day_end=date("Y-m-d",strtotime($check_out_time)).' 21:00';
				if(($working_hours_time <= strtotime($ot_out)) && (strtotime($overtime_day_end) >= strtotime($ot_out))) {

					$ot_in = new DateTime($working_hours_end);
					$ot_out = new DateTime($ot_out);
					$ot_interval = $ot_in->diff($ot_out);
					$ot_hours   = $ot_interval->format('%h');
					$ot_minutes = $ot_interval->format('%i');


					$ot_check_min = decimalHours($ot_hours.':'.$ot_minutes);
					$ot_eligible_hours=decimalHours(OT_ELIGIBLE_HOURS);
					if($ot_check_min >= $ot_eligible_hours){
						$overtime=$ot_hours.'h '.$ot_minutes.'m';	  // Over Time
					}else{
						$overtime='00:00';
					}

					$overtime_night='00:00';


				}
				else if(($working_hours_time <= strtotime($ot_out)) && (strtotime($overtime_day_end) < strtotime($ot_out))) {

					/*First Find OT Day Rate - Start*/  /*OT Day Rate Till 9 PM*/
					$ot_day_in = new DateTime($working_hours_end);
					$ot_day_out = new DateTime($overtime_day_end);
					$ot_day_interval = $ot_day_in->diff($ot_day_out);
					$ot_day_hours   = $ot_day_interval->format('%h');
					$ot_day_minutes = $ot_day_interval->format('%i');
					$overtime=$ot_day_hours.'h '.$ot_day_minutes.'m';	  // Over Time Day
					/*First Find OT Day Rate - End*/

					/*Second Find OT Night Rate - Start*/  /*OT Night Rate After 9 PM*/
					$ot_night_in = new DateTime($overtime_day_end);
					$ot_night_out = new DateTime($ot_out);
					$ot_night_interval = $ot_night_in->diff($ot_night_out);
					$ot_night_hours   = $ot_night_interval->format('%h');
					$ot_night_minutes = $ot_night_interval->format('%i');
					$overtime_night=$ot_night_hours.'h '.$ot_night_minutes.'m';	  // Over Time Night
					/*Second Find OT Night Rate - End*/


				}
				else{
					$overtime = '00:00';   // Over Time
					$overtime_night = '00:00';   // Over Time
				}
				/*Calculate Total OT Hours */
			}
		}
		else if($check_in_time!='' && $check_out_time==''){
			//Logic for first deduction
			$first_no_logout_check=$this->Employees_model->first_no_logout_check($user_id);
			//Logic for first deduction
			$c_in = $shift_start;
			$c_out = $shift_end;
			$clock_in = new DateTime($check_in_time);
			$clock_in2 = $clock_in->format('H:i');
			$office_time =  new DateTime(date("Y-m-d",strtotime($check_in_time)).' '.$shift_start);
			$office_time_new = strtotime(date("Y-m-d",strtotime($check_in_time)).' '.$shift_start);
			$clock_in_time_new = strtotime($check_in_time);
			if($clock_in_time_new <= $office_time_new) {
				$total_time_l = '00:00';
			}
			else {
				$interval_late = $clock_in->diff($office_time);
				$hours_l   = $interval_late->format('%h');
				$minutes_l = $interval_late->format('%i');
				$total_time_l = $hours_l ."h ".$minutes_l."m";
			}
			if($isFlexible){
				$total_time_l = '00:00';
				$attendanceStatus = 'P';
			}
			$time_late=$total_time_l;

			$c_in_after14hours=date('Y-m-d H:i',strtotime("+14 hours",strtotime($check_in_time)));
			$c_in_after14hours_str=strtotime($c_in_after14hours);
			$clock_in2_str=strtotime(date("Y-m-d",strtotime($check_in_time)).' '.$clock_in2);

			$current_time_str=strtotime(date('Y-m-d H:i'));

			$check_in_start_date=date('Y-m-d',strtotime(($check_in_time)));
			$c_out_hours=date('Y-m-d H:i:s',strtotime('+'.$shift_hours.'hours',strtotime($check_in_start_date.' '.$shift_start)));
			$c_out_before1minute=date('Y-m-d H:i',strtotime("-1 minute",strtotime($c_out_hours)));
			$c_out_before1minute_str=strtotime($c_out_before1minute);

			if(($clock_in2_str < $c_out_before1minute_str) && ($current_time_str < $c_out_before1minute_str)){ //echo "1111";
				$early_leaving='';
				$overtime='';
				$overtime_night='';
				$total_work_s='';
				$total_rest='';
			}
			/*else if(($c_out_before1minute_str < $c_in_after14hours_str)  && ($current_time_str < $c_in_after14hours_str)){//echo "2222";
					$early_leaving='';
					$overtime='';
					$overtime_night='';
					$total_work_s='';
					$total_rest='';
			}*/
			else{  //echo "4444";
				$early_leaving='1h 0m';
				$overtime='00:00';
				$overtime_night='';
				if($clock_in_time_new <= $office_time_new) {
					$c_in=date("Y-m-d",strtotime($check_in_time)).$shift_start;
				}
				else {
					$c_in=date("Y-m-d",strtotime($check_in_time)).date("H:i", $clock_in_time_new);
				}

				if(strtotime($c_in) < strtotime($working_hours_end)){
					$c_in = new DateTime($c_in);
					$c_out = new DateTime($working_hours_end);
					$interval = $c_in->diff($c_out);
					$hours   = $interval->format('%h');
					$minutes = $interval->format('%i');

					if($first_no_logout_check==1){
						$deduct_hours=0;
					}
					else{
						$d_hours=explode(':',DEDUCT_HOURS);
						$deduct_hours=$d_hours[0] * 60 + $d_hours[1];
					}

					$total_deduct_lunch=date('H:i',strtotime('+'.$deduct_hours.'minutes',strtotime(LUNCH_HOURS)));
					$f_total_hours = new DateTime($hours.':'.$minutes);
					$f_lunch_hours = new DateTime($total_deduct_lunch);
					$interval_lunch = $f_total_hours;
					if($employee!=null){
						if($employee->is_break_included == 1){
							$interval_lunch = $f_total_hours->diff($f_lunch_hours);
						}
					}
					else
						$interval_lunch = $f_total_hours->diff($f_lunch_hours);

					$total_work_s=$interval_lunch->format('%h').'h '.$interval_lunch->format('%i').'m';
					$total_work=$interval_lunch->format('%h').':'.$interval_lunch->format('%i');
					$total_rest = $this->decimalHours($total_work);
				}
				else{
					$time_late='00:00';
					$early_leaving='00:00';
					$overtime='00:00';
					$overtime_night='00:00';
					$hours_shift   = $working_hours;
					$minutes_shift = $working_minutes;

					/*$d_hours=explode(':',DEDUCT_HOURS);
					$deduct_hours=$d_hours[0] * 60 + $d_hours[1];
					*/
					if($first_no_logout_check==1){
						$deduct_hours=0;
					}else{
						$d_hours=explode(':',DEDUCT_HOURS);
						$deduct_hours=$d_hours[0] * 60 + $d_hours[1];
					}

					$total_deduct_lunch=date('H:i',strtotime('+'.$deduct_hours.'minutes',strtotime(LUNCH_HOURS)));
					$f_total_hours = new DateTime($hours_shift.':'.$minutes_shift);
					$f_lunch_hours = new DateTime($total_deduct_lunch);
					$interval_lunch = $f_total_hours;
					if($employee!=null){
						if($employee->is_break_included == 1){
							$interval_lunch = $f_total_hours->diff($f_lunch_hours);
						}
					}
					else
						$interval_lunch = $f_total_hours->diff($f_lunch_hours);
					$total_work_s=$interval_lunch->format('%h').'h '.$interval_lunch->format('%i').'m';
					$total_work=$interval_lunch->format('%h').':'.$interval_lunch->format('%i');
					$total_rest = $this->decimalHours($total_work);
				}
			}

			/*	// Clock in after finishing the shifts
                $t_date=$today_date.' '.$clock_in2;
                $clock_in_today_str=strtotime($t_date);
                if($clock_in_today_str > $c_out_before1minute_str){	 //echo "3333";
                        $time_late='00:00';
                        $early_leaving='00:00';
                        $overtime='00:00';
                        $overtime_night='00:00';
                        $hours_shift   = $interval_shift->format('%h');
                        $minutes_shift = $interval_shift->format('%i');


                        $f_total_hours = new DateTime($hours_shift.':'.$minutes_shift);
                        $f_lunch_hours = new DateTime($total_deduct_lunch);
                        $interval_lunch = $f_total_hours->diff($f_lunch_hours);
                        $total_work_s=$interval_lunch->format('%h').'h '.$interval_lunch->format('%i').'m';
                        $total_work=$interval_lunch->format('%h').':'.$interval_lunch->format('%i');
                        $total_rest = $this->decimalHours($total_work);

                }*/
			// Clock in after finishing the shifts
		}
		$result_manual= check_OB_Hours($user_id,$today_date);
		if($result_manual){
			$workingHoursIncludeOB = add_ob_hours(array($total_work_s,$result_manual[0]->total_hours),$result_manual[0]->attendance_status);
			$workingHoursIncludeOB = decodeHumanDateTime($workingHoursIncludeOB);
			$shiftHours = new DateTime("08:00");
			if($workingHoursIncludeOB >= $shiftHours){
				$time_late = '00:00';
				$early_leaving = '00:00';
				$attendanceStatus = 'P';
			}
			else{
				$timeLate = decodeHumanDateTime($time_late);
				$earlyOut = decodeHumanDateTime($early_leaving);
				$obHours = decodeHumanDateTime($result_manual[0]->total_hours);
				$lateEarlyDiff = new DateTime($timeLate->diff($earlyOut)->format("%H:%I"));
				$lateEarlyDiff = $obHours->diff($lateEarlyDiff);
				$time_late = $lateEarlyDiff->format('%h').'h '.$lateEarlyDiff->format('%i').'m';
				$early_leaving = '00:00';
				$attendanceStatus = 'LT';
			}
		}
		//echo 'user_id-----'.$user_id.'---check_in_time--'.$check_in_time.'--check_out_time---'.$check_out_time.'---time_late--'.$time_late.'---early_leaving--'.$early_leaving.'---overtime--'.$overtime.'---overtime_night--'.$overtime_night.'--total_work_s---'.$total_work_s.'---total_rest--'.$total_rest.'--today_date---'.$today_date.'--shift_start---'.$shift_start.'---shift_end--'.$shift_end.'---week_off--'.$week_off.'---department_id--'.$department_id.'---first_no_logout_check--'.$first_no_logout_check.'-----';echo "<br>";die;
		$this->Employees_model->action_employee_attendance_today($user_id,$check_in_time,$check_out_time,$time_late,$early_leaving,$overtime,$overtime_night,$total_work_s,$total_rest,$today_date,$shift_start,$shift_end,$week_off,$department_id,$first_no_logout_check,(($employee!=null)?$employee->office_location_id:1),$attendanceStatus);

	}

	public function attendance_manual_sync(){
		$data['title'] = $this->Xin_model->site_title();
		$data['all_employees'] = $this->Xin_model->all_employees();
		$data['breadcrumbs'] = 'Manual Attendance Sync';
		$data['path_url'] = 'attendance_manual_sync';
		if(in_array('58',role_resource_ids())) {
			if(!empty($this->userSession)){
				$data['subview'] = $this->load->view("timesheet/attendance_manual_sync", $data, TRUE);
				$this->load->view('layout_main', $data);
			}
			else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
	}

	public function filter_employee_bylocation(){
		$employee_data=$this->Employees_model->get_employees_list($_GET['department_id'],$_GET['location_id'],'','0');
		$html='';
		$employee_data=$employee_data->result();
		
		$html='<option value="all">All Employees</option>';
	
		if($employee_data){
			foreach($employee_data as $emp){
				$html.='<option value="'.$emp->user_id.'">'.$emp->first_name.' '.$emp->middle_name.' '.$emp->last_name.'</option>';
			}
		}
		echo $html;
	}

	public function output($Return=array()){
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}

	public function manual_sync_attendance_process(){
		$office_location_id = 1;
		if($this->input->post('form')=='sync_process') {
			$Return = array('result'=>'', 'error'=>'');
			$start_date=format_date('Y-m-d',$this->input->post('start_date'));
			$end_date=format_date('Y-m-d',$this->input->post('end_date'));
			$location_value=$this->input->post('location_value');
			$department_value=$this->input->post('department_value');
			$st_date = strtotime($start_date);
			$ed_date = strtotime($end_date);
			$date_first = new DateTime($start_date);
			$date_second = new DateTime($end_date);
			$date_interval= $date_first->diff($date_second);
			$days=$date_interval->days+1;

			if($this->input->post('employee_id')===''){
				$Return['error'] = "Employee field is required";
			}
			else if($st_date > $ed_date) {
				$Return['error'] = "Start Date should be less than or equal to End Date.";
			}
			else if($days > 100) {
				$Return['error'] = "Days should be not more than 100 days.";
			}

			$user_ids=$this->input->post('employee_id');

			if($Return['error']!=''){
				$this->output($Return);
			}

			$employee = $this->Xin_model->read_user_info_attendance($user_ids,$department_value,$location_value);

			//Biometric Sync  1,3
			foreach($employee as $emp){
				$department = $this->Department_model->read_department_information($emp->department_id);
				$department_name=$department[0]->department_name;
				if($emp->biometric_id!=''){

					//Attendance sync procees update
					$emp_biometri_id=$emp->biometric_id;
					$user_id=$emp->user_id;
					$employee_id=$emp->employee_id;
					$office_shift_id=$emp->office_shift_id;
					$office_location_id=$emp->office_location_id;
					$department_id=$emp->department_id;
					$working_hours=$emp->working_hours;

					$begin = new DateTime($start_date);
					$end   = new DateTime($end_date);

					for($dts = $begin; $dts <= $end; $dts->modify('+1 day')){
						$today_date=$dts->format("Y-m-d");
						$find_shifts=$this->Employees_model->get_shifts_bydep_loc($user_id,$office_location_id,$department_id,$today_date);
						$flexibleEmployees = getFlexibleEmployees();
						$isFlexible = in_array($user_id,$flexibleEmployees);

						if(is_numeric($user_id)){
							$response=$this->Employees_model->get_biometric_attendance($emp_biometri_id,$office_location_id,$today_date,$find_shifts['in_time'],$find_shifts['out_time'],$find_shifts['week_off']);					
						}
						else{
							$response='';
						}
						$all_value = $response;
						if(empty($all_value)){
							$this->Employees_model->action_employee_attendance_today($user_id,'','','','','','','','',$today_date,$find_shifts['in_time'],$find_shifts['out_time'],$find_shifts['week_off'],$department_id,0);
							continue;
						}
						$count_res=count($all_value);
						$first_array_key=0;
						$last_array_key=$count_res-1;
						if($count_res==1){
							$check_in_time=$all_value[$first_array_key]['check_in_date'];
							$check_out_time='';
						}
						else if($count_res==2){
							$check_in_time=$all_value[$first_array_key]['check_in_date'];
							$check_out_time=$all_value[$last_array_key]['check_in_date'];
							$new_time = date("Y-m-d H:i:s", strtotime('+15 minutes', strtotime($check_in_time)));
							if($check_out_time < $new_time){
								$check_out_time = '';
							}
						}
						else if($count_res > 2){
							$check_in_time=$all_value[$first_array_key]['check_in_date'];
							$check_out_time=$all_value[$last_array_key]['check_in_date'];
							$new_time = date("Y-m-d H:i:s", strtotime('+15 minutes', strtotime($check_in_time)));
							if($check_out_time < $new_time){
								$check_out_time = '';
							}
						}

						 //echo $check_in_time.'--------'.$check_out_time.'--------'.$user_id.'--------'.$today_date.'--------'.$find_shifts['in_time'].'--------'.$find_shifts['out_time'].'--------'.$find_shifts['week_off'];echo "<br>";
						$this->action_attendance_calculation($check_in_time,$check_out_time,$user_id,$today_date,$find_shifts['in_time'],$find_shifts['out_time'],$find_shifts['week_off'],$department_id,$working_hours,$find_shifts['result_ramadan_val'],$emp);

					}
				}

				//Transport Department Attendance
				$emp_biometri_id=$emp->biometric_id;
				$user_id=$emp->user_id;
				$employee_id=$emp->employee_id;
				$usr_email=$emp->email;
				$office_shift_id=$emp->office_shift_id;
				$office_location_id=$emp->office_location_id;
				$department_id=$emp->department_id;
				$working_hours=$emp->working_hours;
				$datefrom=date('Y-m-d', strtotime($start_date));
				$dateto=date('Y-m-d', strtotime($end_date));
				if($this->input->post('driver_s')){
					$begin = new DateTime($start_date);
					$end   = new DateTime($end_date);
					for($dts = $begin; $dts <= $end; $dts->modify('+1 day')){
						$today_date=$dts->format("Y-m-d");
						$find_shifts=$this->Employees_model->get_shifts_bydep_loc($user_id,$office_location_id,$department_id,$today_date);
						if(is_numeric($emp_biometri_id) || is_numeric($user_id)){
							$response1=$this->Employees_model->get_driver_attendance($usr_email,$today_date);
						}else{$response1='';}
						$all_value1 = $response1;
						if(empty($all_value1)){
							$this->Employees_model->action_employee_attendance_today($user_id,'','','','','','','','',$today_date,$find_shifts['in_time'],$find_shifts['out_time'],$find_shifts['week_off'],$department_id,0);
							continue;
						}
						$minimum_delivery_count = MIN_DELIVERY_COUNT;

						if($all_value1){

							foreach($all_value1 as $vals){
								$achieved_delivery_count=$vals['delivery_count'];
								$assigned_count=$vals['assigned_count'];
								$cancellation_count=$vals['cancellation_count'];
								$cancellation_rate=$vals['cancellation_rate'];

								$check_in_time=$today_date.' '.$find_shifts['in_time'];
								$check_out_time=$today_date.' '.$find_shifts['out_time'];
								$status=attendance_status_action($today_date,$user_id,$status='',$check_in_time,$check_out_time,$find_shifts['in_time'],$find_shifts['out_time'],$find_shifts['week_off'],$department_id,$office_location_id);
								$clock_in_out=1;
								$time_late='00:00';
								$early_leaving='00:00';
								$overtime='0h 0m';
								$overtime_night='00:00';

								$c_in = new DateTime($find_shifts['in_time']);
								$c_out = new DateTime($find_shifts['out_time']);
								$interval = $c_in->diff($c_out);
								$hours   = $interval->format('%h');
								$minutes = $interval->format('%i');
								$f_total_hours = new DateTime($hours.':'.$minutes);
								$f_lunch_hours = new DateTime(LUNCH_HOURS);

								$interval_lunch = $f_total_hours;
								if($emp->is_break_included == 1)
									$interval_lunch = $f_total_hours->diff($f_lunch_hours);

								$total_work_s=$interval_lunch->format('%h').'h '.$interval_lunch->format('%i').'m';
								$total_work=$interval_lunch->format('%h').':'.$interval_lunch->format('%i');
								$total_rest = $this->decimalHours($total_work);


								if($status!='P' && $status!='WO'){
									$total_work='';
									$total_rest='';
								}else{
									$total_work=$total_work_s;
									$total_rest=$total_rest;
								}
								if($minimum_delivery_count <=$achieved_delivery_count){
									$data=array('employee_id' => $user_id, 'attendance_date' => $today_date, 'clock_in' => $check_in_time, 'clock_out' => $check_out_time, 'attendance_status' => $status,'clock_in_out' =>$clock_in_out,'time_late' =>$time_late,'early_leaving' =>$early_leaving,'overtime' =>$overtime,'overtime_night' =>$overtime_night,'total_work' =>$total_work,'total_rest' =>$total_rest,'shift_start_time' =>$find_shifts['in_time'],'shift_end_time' =>$find_shifts['out_time'],'week_off' =>$find_shifts['week_off'],'target_delivery_count' => $minimum_delivery_count,'achieved_delivery_count' => $achieved_delivery_count,'assigned_count' => $assigned_count,'cancellation_count' => $cancellation_count,'cancellation_rate' => $cancellation_rate);
								}else{
									$data=array('employee_id' => $user_id, 'attendance_date' => $today_date, 'clock_in' => '', 'clock_out' => '', 'attendance_status' => 'Absent','clock_in_out' =>$clock_in_out,'time_late' =>'','early_leaving' =>'','overtime' =>'','overtime_night' =>'','total_work' =>'','total_rest' =>'','shift_start_time' =>$find_shifts['in_time'],'shift_end_time' =>$find_shifts['out_time'],'week_off' =>$find_shifts['week_off'],'target_delivery_count' => $minimum_delivery_count,'achieved_delivery_count' => $achieved_delivery_count,'assigned_count' => $assigned_count,'cancellation_count' => $cancellation_count,'cancellation_rate' => $cancellation_rate);

								}

								$check_if_already=$this->Employees_model->check_if_already_insert($user_id,$today_date);

								if($check_if_already > 0){
									$condition = "employee_id =" . "'" . $user_id . "' and attendance_date ='".$today_date."' ";
									$this->db->where($condition);
									$this->db->update('xin_attendance_time',$data);
								}else{

									$this->db->insert('xin_attendance_time',$data);
								}

							}
						}

					}
				}
			}
			$Return['result'] = 'Sync Process Completed';
			$this->output($Return);
			exit;
		}

	}

	public function manual_sync_script(){
		$Return = array('result'=>'', 'error'=>'');
		$start_date=format_date('Y-m-d',$this->input->get('start_date'));
		$end_date=format_date('Y-m-d',$this->input->get('end_date'));

		$st_date = strtotime($start_date);
		$ed_date = strtotime($end_date);
		$date_first = new DateTime($start_date);
		$date_second = new DateTime($end_date);
		$date_interval= $date_first->diff($date_second);
		$days=$date_interval->days+1;
		if($Return['error']!=''){
			$this->output($Return);
		}

		$employee = $this->Employees_model->get_employees_uae()->result();
		foreach($employee as $emp){
			if($emp->biometric_id!='' || $emp->employee_id!=''){
				$emp_biometri_id=$emp->biometric_id;
				$user_id=$emp->user_id;
				$employee_id=$emp->employee_id;
				$office_shift_id=$emp->office_shift_id;
				$office_location_id=$emp->office_location_id;
				$department_id=$emp->department_id;
				$working_hours=$emp->working_hours;
				$begin = new DateTime($start_date);
				$end   = new DateTime($end_date);
				for($dts = $begin; $dts <= $end; $dts->modify('+1 day')){
					$today_date=$dts->format("Y-m-d");
					$find_shifts=$this->Employees_model->get_shifts_bydep_loc($user_id,$office_location_id,$department_id,$today_date);
					if(is_numeric($emp_biometri_id) || is_numeric($user_id)){
						$response=$this->Employees_model->get_biometric_attendance($emp_biometri_id,$office_location_id,$today_date,$find_shifts['in_time'],$find_shifts['out_time'],$find_shifts['week_off']);
					}
					else{
						$response='';
					}
					$all_value = $response;
					if(empty($all_value)){
						$this->Employees_model->action_employee_attendance_today($user_id,'','','','','','','','',$today_date,$find_shifts['in_time'],$find_shifts['out_time'],$find_shifts['week_off'],$department_id,0);
						continue;
					}
					$count_res=count($all_value);
					$first_array_key=0;
					$last_array_key=$count_res-1;
					if($count_res==1){
						$check_in_time=$all_value[$first_array_key]['check_in_date'];
						$check_out_time='';
					}
					else if($count_res==2){
						$check_in_time=$all_value[$first_array_key]['check_in_date'];
						$check_out_time=$all_value[$last_array_key]['check_in_date'];
						$new_time = date("Y-m-d H:i:s", strtotime('+15 minutes', strtotime($check_in_time)));
						if($check_out_time < $new_time){
							$check_out_time = '';
						}
					}
					else if($count_res > 2){
						$check_in_time=$all_value[$first_array_key]['check_in_date'];
						$check_out_time=$all_value[$last_array_key]['check_in_date'];
						$new_time = date("Y-m-d H:i:s", strtotime('+15 minutes', strtotime($check_in_time)));
						if($check_out_time < $new_time){
							$check_out_time = '';
						}
					}
					//echo $check_in_time.'--------'.$check_out_time.'--------'.$user_id.'--------'.$today_date.'--------'.$find_shifts['in_time'].'--------'.$find_shifts['out_time'].'--------'.$find_shifts['week_off'];echo "<br>";
					$this->action_attendance_calculation($check_in_time,$check_out_time,$user_id,$today_date,$find_shifts['in_time'],$find_shifts['out_time'],$find_shifts['week_off'],$department_id,$working_hours,$find_shifts['result_ramadan_val'],$emp);

				}
			}
		}
		$Return['result'] = 'Sync Process Completed';
		$this->output($Return);
		exit;
	}

	public function checkattendance(){
		$ip=$_GET['ip'];
		$slug='';
		if($ip!=''){
			$slug.=" where ip_address='".$ip."'";
		}
		$query=$this->db->query("select * from xin_biometric_attendance_synch $slug order by biometric_id asc");
		$result=$query->result();
		if($result){
			$i=1;
			foreach($result as $res){

				echo '---'.$i.'---'.$res->biometric_id.'---'.$res->check_in_date.'---'.$res->ip_address.'---'.format_date('Y-m-d',$res->process_date);
				echo "<br>";
				$i++;
			}

		}
		echo "-------------Driver Attendance-------------------";
		$query1=$this->db->query("select * from xin_driver_attendance_synch order by attendance_date asc");
		$result1=$query1->result();
		if($result1){
			$j=1;
			foreach($result1 as $res1){
				echo '---'.$j.'---'.$res1->driver_email.'---'.$res1->assigned_count.'---'.$res1->delivery_count.'---'.$res1->cancellation_count.'---'.$res1->cancellation_rate.'---'.format_date('Y-m-d',$res1->attendance_date);
				echo "<br>";
				$j++;
			}
		}
	}

	//$rawInput=file_get_contents("php://input");
	//$getMessage=json_decode($rawInput,true);*
	//$datas=json_decode($message->data());//$message['data']

	public function show_table(){
		$table=$_GET['table'];
		$query=$this->db->query("select * from $table");
		$result=$query->result();
		echo "<pre>";
		if($result){
			foreach($result as $res){
				print_r($res);
			}
		}
	}

	public function employeeattendance(){
		$employee = $this->Xin_model->read_user_info_attendance('all',0,0);
		$date=@$_GET['date'];
		if($date!=''){
			$today_date=format_date('Y-m-d',$date);
		}
		else{
			$today_date=TODAY_DATE;
		}
		$module_name= "Cron";
		$module_type= "Employee Attendance Initiated";
		$details= "Employee Attendance Initiate Cron Running On ".date('Y-m-d H:i:s');
		systemlogs($module_name,$module_type,0,$details); 
		
		foreach($employee as $emp){
			$user_id=$emp->user_id;
			$usr_email=$emp->email;
			$employee_id=$emp->employee_id;
			$office_shift_id=$emp->office_shift_id;
			$office_location_id=$emp->office_location_id;
			$department_id=$emp->department_id;
			$working_hours=$emp->working_hours;
			$find_shifts=$this->Employees_model->get_shifts_bydep_loc($user_id,$office_location_id,$department_id,$today_date);
			$this->Employees_model->action_employee_attendance_today_insert($user_id,'','','','','','','','',$today_date,$find_shifts['in_time'],$find_shifts['out_time'],$find_shifts['week_off'],$department_id,$office_location_id);
		}
	}

	public function cloudattendance(){
		//mail("siddiq.jalalu@gmail.com","Cloud Attendance","Comming1");
		$db_date=TODAY_DATE;
		$db_date_after_9_hours=date('Y-m-d H:i',strtotime('+9 hours',strtotime($db_date)));
		$db_date_after_14_hours=date('Y-m-d H:i',strtotime('+14 hours',strtotime($db_date)));
		$db_date_after_23_hours=date('Y-m-d H:i',strtotime('+23 hours',strtotime($db_date)));
		$current_time=date('Y-m-d H:i');
		$current_time_5plus=date('Y-m-d H:i',strtotime('+5 mins',strtotime(date('Y-m-d H:i'))));
		if((strtotime($current_time) <= strtotime($db_date_after_9_hours)) && (strtotime($current_time_5plus) >= strtotime($db_date_after_9_hours)) ){
			$this->employeeattendance();
		}
		else if((strtotime($current_time) <= strtotime($db_date_after_14_hours)) && (strtotime($current_time_5plus) >= strtotime($db_date_after_14_hours)) ){
			$this->employeeattendance();
		}
		else if((strtotime($current_time) <= strtotime($db_date_after_23_hours)) && (strtotime($current_time_5plus) >= strtotime($db_date_after_23_hours)) ){
			$this->employeeattendance();
		}

		// $module_name= "Cron";
		// $module_type= "Cloud Attendance Pubsub";
		// $details= "Cloud Attendance Pubsub Cron Running On ".date('Y-m-d H:i:s');
		// systemlogs($module_name,$module_type,0,$details); 

		require_once('PublishService.php');
		$topic='biometric-attendance';
		$subscription='biometric_data';
		$env='production';
		$vars = new PublishService($topic,$env);
		$getMessages = $vars->GetMessages($subscription,40);
		$getMessage=$getMessages['message'];
		$subs=$getMessages['subscription'];
		mail("siddiq.jalaludeen@awok.com","Cloud Attendance","Comming".count($getMessage));
		if($getMessage){
			mail("siddiq.jalaludeen@awok.com","Cloud Attendance","Working".count($getMessage));
			foreach($getMessage as $message){
				$datas=json_decode($message->data());
				$biometric_id=$datas->biometric_id;
				$name=$datas->name;
				$email=$datas->email;
				$status_atn=$datas->status;
				$check_in_out_date=$datas->check_in_out_date;
				$check_in_out_time=$datas->check_in_out_time;
				$ip_address=$datas->ip_address;
				$check_in_date=$datas->check_in_date;
				$process_date=$datas->process_date;
				$assigned_count=$datas->assigned_count;
				$delivered_cnt=$datas->delivered_cnt;
				$cancelled_cnt=$datas->cancelled_cnt;
				$cancel_percent=$datas->cancel_percent;
				if($email==''){
					$this->db->query("delete from xin_biometric_attendance_synch where check_in_date='".$check_in_date."' and ip_address='".$ip_address."' and biometric_id='".$biometric_id."'");
					$sql = "INSERT INTO xin_biometric_attendance_synch (biometric_id,status,check_in_out_date,check_in_out_time,ip_address,check_in_date,WorkingShift,scheduleshift,shiftstart,shiftend,process_date) VALUES('".$biometric_id."','".$status_atn."','".$check_in_out_date."','".$check_in_out_time."','".$ip_address."','".$check_in_date."','','','','','".$process_date."');";
					$this->db->query($sql);
				}
				else{
					$this->db->query("delete from xin_driver_attendance_synch where driver_id='".$biometric_id."' and attendance_date='".$process_date."'");
					$sql1 = "INSERT INTO xin_driver_attendance_synch (driver_id,driver_name,driver_email,attendance_date,assigned_count,delivery_count,cancellation_count,cancellation_rate) VALUES('".$biometric_id."','".$name."','".$email."','".$process_date."','".$assigned_count."','".$delivered_cnt."','".$cancelled_cnt."','".$cancel_percent."')";
					$this->db->query($sql1);
				}
				$emp = $this->Xin_model->current_attendance_info($biometric_id,$email,$status_atn,$ip_address);
				if($emp){
					$emp_biometri_id=$this->Xin_model->concat_biometric_id($emp[0]->user_id);
					$user_id=$emp[0]->user_id;
					$usr_email=$emp[0]->email;
					$employee_id=$emp[0]->employee_id;
					$office_shift_id=$emp[0]->office_shift_id;
					$office_location_id=$emp[0]->office_location_id;
					$department_id=$emp[0]->department_id;
					$working_hours=$emp[0]->working_hours;
					$department = $this->Department_model->read_department_information($department_id);
					$department_name=$department[0]->department_name;
					//if($department_name!='TD' || $emp[0]->visa_type=='DMCC'){
					if($status_atn!='D'){
						if($emp_biometri_id!=''){
							$find_shifts=$this->Employees_model->get_shifts_bydep_loc($user_id,$office_location_id,$department_id,$process_date);
							if(is_numeric($user_id)){
								$response=$this->Employees_model->get_biometric_attendance($emp_biometri_id,$office_location_id,$process_date,$find_shifts['in_time'],$find_shifts['out_time'],$find_shifts['week_off']);
							}else{$response='';}
							$all_value = $response;
							if(empty($all_value)){
								$this->Employees_model->action_employee_attendance_today($user_id,'','','','','','','','',$process_date,$find_shifts['in_time'],$find_shifts['out_time'],$find_shifts['week_off'],$department_id,0);
								continue;
							}
							$count_res=count($all_value);
							$first_array_key=0;
							$last_array_key=$count_res-1;
							if($count_res==1){
								$check_in_time=$all_value[$first_array_key]['check_in_date'];
								$check_out_time='';
							} else if($count_res==2){
								$check_in_time=$all_value[$first_array_key]['check_in_date'];
								$check_out_time=$all_value[$last_array_key]['check_in_date'];
								$new_time = date("Y-m-d H:i:s", strtotime('+15 minutes', strtotime($check_in_time)));
								if($check_out_time < $new_time){
									$check_out_time='';
								}
							} else if($count_res > 2){
								$check_in_time=$all_value[$first_array_key]['check_in_date'];
								$check_out_time=$all_value[$last_array_key]['check_in_date'];
								$new_time = date("Y-m-d H:i:s", strtotime('+15 minutes', strtotime($check_in_time)));
								if($check_out_time < $new_time){
									$check_out_time='';
								}
							}
							$this->action_attendance_calculation($check_in_time,$check_out_time,$user_id,$process_date,$find_shifts['in_time'],$find_shifts['out_time'],$find_shifts['week_off'],$department_id,$working_hours,$find_shifts['result_ramadan_val'],$emp[0]);
						}
					}
					//else if($department_name=='TD'){// For Driver Attendance
					else if($status_atn=='D'){
						$find_shifts=$this->Employees_model->get_shifts_bydep_loc($user_id,$office_location_id,$department_id,$process_date);
						if($usr_email!=''){
							$response=$this->Employees_model->get_driver_attendance($usr_email,$process_date);
						}else{$response='';}
						$all_value = $response;
						if(empty($all_value)){
							$this->Employees_model->action_employee_attendance_today($user_id,'','','','','','','','',$process_date,$find_shifts['in_time'],$find_shifts['out_time'],$find_shifts['week_off'],$department_id,0);
							continue;
						}
						$minimum_delivery_count = MIN_DELIVERY_COUNT;
						if($all_value){
							foreach($all_value as $vals){
								$achieved_delivery_count=$vals['delivery_count'];
								$assigned_count=$vals['assigned_count'];
								$cancellation_count=$vals['cancellation_count'];
								$cancellation_rate=$vals['cancellation_rate'];
								$check_in_time=$process_date.' '.$find_shifts['in_time'];
								$check_out_time=$process_date.' '.$find_shifts['out_time'];
								$atnstatus=attendance_status_action($process_date,$user_id,$status='',$check_in_time,$check_out_time,$find_shifts['in_time'],$find_shifts['out_time'],$find_shifts['week_off'],$department_id,$office_location_id);
								$clock_in_out=1;
								$time_late='00:00';
								$early_leaving='00:00';
								$overtime='0h 0m';
								$overtime_night='00:00';
								$c_in = new DateTime($find_shifts['in_time']);
								$c_out = new DateTime($find_shifts['out_time']);
								$interval = $c_in->diff($c_out);
								$hours   = $interval->format('%h');
								$minutes = $interval->format('%i');
								$f_total_hours = new DateTime($hours.':'.$minutes);
								$f_lunch_hours = new DateTime(LUNCH_HOURS);
								$interval_lunch = $f_total_hours->diff($f_lunch_hours);

								$interval_lunch = $f_total_hours;
								if($emp[0]->is_break_included == 1)
									$interval_lunch = $f_total_hours->diff($f_lunch_hours);


								$total_work_s=$interval_lunch->format('%h').'h '.$interval_lunch->format('%i').'m';
								$total_work=$interval_lunch->format('%h').':'.$interval_lunch->format('%i');
								$total_rest = $this->decimalHours($total_work);
								if($atnstatus!='P' && $atnstatus!='WO'){
									$total_work='';
									$total_rest='';
								}else{
									$total_work=$total_work_s;
									$total_rest=$total_rest;
								}
								if($minimum_delivery_count <=$achieved_delivery_count){
									$data=array('employee_id' => $user_id, 'attendance_date' => $process_date, 'clock_in' => $check_in_time, 'clock_out' => $check_out_time, 'attendance_status' => $atnstatus,'clock_in_out' =>$clock_in_out,'time_late' =>$time_late,'early_leaving' =>$early_leaving,'overtime' =>$overtime,'overtime_night' =>$overtime_night,'total_work' =>$total_work,'total_rest' =>$total_rest,'shift_start_time' =>$find_shifts['in_time'],'shift_end_time' =>$find_shifts['out_time'],'week_off' =>$find_shifts['week_off'],'target_delivery_count' => $minimum_delivery_count,'achieved_delivery_count' => $achieved_delivery_count,'assigned_count' => $assigned_count,'cancellation_count' => $cancellation_count,'cancellation_rate' => $cancellation_rate);
								}else{
									$data=array('employee_id' => $user_id, 'attendance_date' => $process_date, 'clock_in' => '', 'clock_out' => '', 'attendance_status' => 'Absent','clock_in_out' =>$clock_in_out,'time_late' =>'','early_leaving' =>'','overtime' =>'','overtime_night' =>'','total_work' =>'','total_rest' =>'','shift_start_time' =>$find_shifts['in_time'],'shift_end_time' =>$find_shifts['out_time'],'week_off' =>$find_shifts['week_off'],'target_delivery_count' => $minimum_delivery_count,'achieved_delivery_count' => $achieved_delivery_count,'assigned_count' => $assigned_count,'cancellation_count' => $cancellation_count,'cancellation_rate' => $cancellation_rate);
								}
								$check_if_already=$this->Employees_model->check_if_already_insert($user_id,$process_date);
								if($check_if_already > 0){
									$condition = "employee_id =" . "'" . $user_id . "' and attendance_date ='".$process_date."' ";
									$this->db->where($condition);
									$this->db->update('xin_attendance_time',$data);
								}else{
									$this->db->insert('xin_attendance_time',$data);
								}

							}
						}

					}
				}
				$subs->acknowledge( $message );
			}

		}

	}

	public function cloudattendance1(){
		$office_location_id = 1;
		$db_date=TODAY_DATE;
		$db_date_after_9_hours=date('Y-m-d H:i',strtotime('+9 hours',strtotime($db_date)));
		$db_date_after_14_hours=date('Y-m-d H:i',strtotime('+14 hours',strtotime($db_date)));
		$db_date_after_23_hours=date('Y-m-d H:i',strtotime('+23 hours',strtotime($db_date)));
		$current_time=date('Y-m-d H:i');
		$current_time_5plus=date('Y-m-d H:i',strtotime('+5 mins',strtotime(date('Y-m-d H:i'))));
		$office_location_id = 1;
		$this->employeeattendance();
		require_once('PublishService.php');
		$topic='biometric-attendance';
		$subscription='biometric_data';
		$env='production';
		$vars = new PublishService($topic,$env);
		$getMessages = $vars->GetMessages($subscription,300);
		$getMessage=$getMessages['message'];
		$subs=$getMessages['subscription'];
		/*$getMessage[] = '{"biometric_id":"1271","name":"","email":"","status":"0","check_in_out_date":"2019-07-23","check_in_out_time":"08:14:16","ip_address":"192.168.1.21","check_in_date":"2019-07-23 08:14:16","process_date":"2019-07-23","assigned_count":"","delivered_cnt":"","cancelled_cnt":"","cancel_percent":""}';
		$getMessage[] = '{"biometric_id":"1280","name":"","email":"","status":"0","check_in_out_date":"2019-07-23","check_in_out_time":"18:01:38","ip_address":"192.168.1.22","check_in_date":"2019-07-23 18:01:38","process_date":"2019-07-23","assigned_count":"","delivered_cnt":"","cancelled_cnt":"","cancel_percent":""}';*/
		//echo "<pre>";print_r($getMessage);die;
		show_data($getMessage);
		if($getMessage){
			foreach($getMessage as $message){
				$datas=json_decode($message->data());
				//$datas=json_decode($message); 
				$biometric_id=$datas->biometric_id;
				$name=$datas->name;
				$email=$datas->email;
				$status=$datas->status;
				$check_in_out_date=$datas->check_in_out_date;
				$check_in_out_time=$datas->check_in_out_time;
				$ip_address=$datas->ip_address;
				$check_in_date=$datas->check_in_date;
				$process_date=$datas->process_date;
				$assigned_count=$datas->assigned_count;
				$delivered_cnt=$datas->delivered_cnt;
				$cancelled_cnt=$datas->cancelled_cnt;
				$cancel_percent=$datas->cancel_percent;
				if($email==''){
					$this->db->query("delete from xin_biometric_attendance_synch where check_in_date='".$check_in_date."' and ip_address='".$ip_address."' and biometric_id='".$biometric_id."'");
					$sql = "INSERT INTO xin_biometric_attendance_synch (biometric_id,status,check_in_out_date,check_in_out_time,ip_address,check_in_date,WorkingShift,scheduleshift,shiftstart,shiftend,process_date) VALUES('".$biometric_id."','".$status."','".$check_in_out_date."','".$check_in_out_time."','".$ip_address."','".$check_in_date."','','','','','".$process_date."');";
					$this->db->query($sql);
				}
				else{
					$this->db->query("delete from xin_driver_attendance_synch where driver_id='".$biometric_id."' and attendance_date='".$process_date."'");
					$sql1 = "INSERT INTO xin_driver_attendance_synch (driver_id,driver_name,driver_email,attendance_date,assigned_count,delivery_count,cancellation_count,cancellation_rate) VALUES('".$biometric_id."','".$name."','".$email."','".$process_date."','".$assigned_count."','".$delivered_cnt."','".$cancelled_cnt."','".$cancel_percent."')";
					$this->db->query($sql1);
				}

				$emp = $this->Xin_model->current_attendance_info($biometric_id,$email,$status,$ip_address);			
				if($emp){
					$emp_biometri_id=$this->Xin_model->concat_biometric_id($emp[0]->user_id);					
					$user_id=$emp[0]->user_id;
					$usr_email=$emp[0]->email;
					$employee_id=$emp[0]->employee_id;
					$office_shift_id=$emp[0]->office_shift_id;
					$office_location_id=$emp[0]->office_location_id;
					$department_id=$emp[0]->department_id;
					$working_hours=$emp[0]->working_hours;
					$department = $this->Department_model->read_department_information($department_id);
					$department_name=$department[0]->department_name;
					//if($department_name!='TD' || $emp[0]->visa_type=='DMCC'){
					if($status!='D'){
						if($emp_biometri_id!=''){
							$find_shifts=$this->Employees_model->get_shifts_bydep_loc($user_id,$office_location_id,$department_id,$process_date);
							if(is_numeric($user_id)){
								$response=$this->Employees_model->get_biometric_attendance($emp_biometri_id,$office_location_id,$process_date,$find_shifts['in_time'],$find_shifts['out_time'],$find_shifts['week_off']);
							}else{$response='';}
							$all_value = $response;
							if(empty($all_value)){
								$this->Employees_model->action_employee_attendance_today($user_id,'','','','','','','','',$process_date,$find_shifts['in_time'],$find_shifts['out_time'],$find_shifts['week_off'],$department_id,0);
								continue;
							}
							$count_res=count($all_value);
							$first_array_key=0;
							$last_array_key=$count_res-1;
							if($count_res==1){
								$check_in_time=$all_value[$first_array_key]['check_in_date'];
								$check_out_time='';
							} else if($count_res==2){
								$check_in_time=$all_value[$first_array_key]['check_in_date'];
								$check_out_time=$all_value[$last_array_key]['check_in_date'];
								$new_time = date("Y-m-d H:i:s", strtotime('+15 minutes', strtotime($check_in_time)));
								if($check_out_time < $new_time){
									$check_out_time='';
								}
							} else if($count_res > 2){
								$check_in_time=$all_value[$first_array_key]['check_in_date'];
								$check_out_time=$all_value[$last_array_key]['check_in_date'];
								$new_time = date("Y-m-d H:i:s", strtotime('+15 minutes', strtotime($check_in_time)));
								if($check_out_time < $new_time){
									$check_out_time='';
								}
							}
							// echo $check_in_time.'--------'.$check_out_time.'--------'.$user_id.'--------'.$today_date.'--------'.$find_shifts['in_time'].'--------'.$find_shifts['out_time'].'--------'.$find_shifts['week_off'];echo "<br>";
							$this->action_attendance_calculation($check_in_time,$check_out_time,$user_id,$process_date,$find_shifts['in_time'],$find_shifts['out_time'],$find_shifts['week_off'],$department_id,$working_hours,$find_shifts['result_ramadan_val'],$emp[0]);
						}
					}
					else if($status=='D'){// For Driver Attendance
						$find_shifts=$this->Employees_model->get_shifts_bydep_loc($user_id,$office_location_id,$department_id,$process_date);
						if($usr_email!=''){
							$response=$this->Employees_model->get_driver_attendance($usr_email,$process_date);
						}else{$response='';}
						$all_value = $response;
						if(empty($all_value)){
							$this->Employees_model->action_employee_attendance_today($user_id,'','','','','','','','',$process_date,$find_shifts['in_time'],$find_shifts['out_time'],$find_shifts['week_off'],$department_id,0);
							continue;
						}
						$minimum_delivery_count = MIN_DELIVERY_COUNT;
						if($all_value){
							foreach($all_value as $vals){
								$achieved_delivery_count=$vals['delivery_count'];
								$assigned_count=$vals['assigned_count'];
								$cancellation_count=$vals['cancellation_count'];
								$cancellation_rate=$vals['cancellation_rate'];
								$check_in_time=$process_date.' '.$find_shifts['in_time'];
								$check_out_time=$process_date.' '.$find_shifts['out_time'];
								$status=attendance_status_action($process_date,$user_id,$status='',$check_in_time,$check_out_time,$find_shifts['in_time'],$find_shifts['out_time'],$find_shifts['week_off'],$department_id,$office_location_id);
								$clock_in_out=1;
								$time_late='00:00';
								$early_leaving='00:00';
								$overtime='0h 0m';
								$overtime_night='00:00';
								$c_in = new DateTime($find_shifts['in_time']);
								$c_out = new DateTime($find_shifts['out_time']);
								$interval = $c_in->diff($c_out);
								$hours   = $interval->format('%h');
								$minutes = $interval->format('%i');
								$f_total_hours = new DateTime($hours.':'.$minutes);
								$f_lunch_hours = new DateTime(LUNCH_HOURS);
								$interval_lunch = $f_total_hours;
								if($emp[0]->is_break_included == 1)
									$interval_lunch = $f_total_hours->diff($f_lunch_hours);

								$total_work_s=$interval_lunch->format('%h').'h '.$interval_lunch->format('%i').'m';
								$total_work=$interval_lunch->format('%h').':'.$interval_lunch->format('%i');

								$total_rest = $this->decimalHours($total_work);
								if($status!='P' && $status!='WO'){
									$total_work='';
									$total_rest='';
								}else{
									$total_work=$total_work_s;
									$total_rest=$total_rest;
								}
								if($minimum_delivery_count <=$achieved_delivery_count){
									$data=array('employee_id' => $user_id, 'attendance_date' => $process_date, 'clock_in' => $check_in_time, 'clock_out' => $check_out_time, 'attendance_status' => $status,'clock_in_out' =>$clock_in_out,'time_late' =>$time_late,'early_leaving' =>$early_leaving,'overtime' =>$overtime,'overtime_night' =>$overtime_night,'total_work' =>$total_work,'total_rest' =>$total_rest,'shift_start_time' =>$find_shifts['in_time'],'shift_end_time' =>$find_shifts['out_time'],'week_off' =>$find_shifts['week_off'],'target_delivery_count' => $minimum_delivery_count,'achieved_delivery_count' => $achieved_delivery_count,'assigned_count' => $assigned_count,'cancellation_count' => $cancellation_count,'cancellation_rate' => $cancellation_rate);
								}else{
									$data=array('employee_id' => $user_id, 'attendance_date' => $process_date, 'clock_in' => '', 'clock_out' => '', 'attendance_status' => 'Absent','clock_in_out' =>$clock_in_out,'time_late' =>'','early_leaving' =>'','overtime' =>'','overtime_night' =>'','total_work' =>'','total_rest' =>'','shift_start_time' =>$find_shifts['in_time'],'shift_end_time' =>$find_shifts['out_time'],'week_off' =>$find_shifts['week_off'],'target_delivery_count' => $minimum_delivery_count,'achieved_delivery_count' => $achieved_delivery_count,'assigned_count' => $assigned_count,'cancellation_count' => $cancellation_count,'cancellation_rate' => $cancellation_rate);
								}
								$check_if_already=$this->Employees_model->check_if_already_insert($user_id,$process_date);
								if($check_if_already > 0){
									$condition = "employee_id =" . "'" . $user_id . "' and attendance_date ='".$process_date."' ";
									$this->db->where($condition);
									$this->db->update('xin_attendance_time',$data);
								}else{
									$this->db->insert('xin_attendance_time',$data);
								}

							}
						}

					}


				}
				//$subs->acknowledge( $message );
			}
		}
	}

	public function inout_missed_triggers(){
		if(email_notification('missed_loginout') == 'yes') {
			$get_today_attendance=$this->Employees_model->get_today_attendance();
			$cinfo = $this->Xin_model->read_company_setting_info(1);
			$template = $this->Xin_model->read_email_template(28);
			if($get_today_attendance){
				foreach($get_today_attendance as $attendance){
					$current_date_time=date('Y-m-d H:i').':00';

					$shift_start_time=$attendance->attendance_date.' '.$attendance->shift_start_time.':00';
					if(strtotime($attendance->attendance_date.' '.$attendance->shift_start_time.':00') < strtotime($attendance->attendance_date.' '.$attendance->shift_end_time.':00'))
					{
						$c_in = new DateTime($shift_start_time);
						$c_out = new DateTime($attendance->attendance_date.' '.$attendance->shift_end_time);
					}else{
						$next_day=date('Y-m-d',strtotime('+1 day',strtotime($attendance->attendance_date)));
						$c_in = new DateTime($shift_start_time);
						$c_out = new DateTime($next_day.' '.$attendance->shift_end_time);
					}

					$interval = $c_in->diff($c_out);
					$hours   = $interval->format('%h');
					$minutes = $interval->format('%i');
					$shift_end_time=date('Y-m-d H:i:s',strtotime('+'.$hours.' hours +'.$minutes.' minutes',strtotime($shift_start_time)));

					$shift_start_after_1hour=date('Y-m-d H:i:s',strtotime('+1 hour 30 minutes',strtotime($shift_start_time)));
					$shift_end_after_1hour=date('Y-m-d H:i:s',strtotime('+1 hour 30 minutes',strtotime($shift_end_time)));

					$clock_in_time=$attendance->clock_in;
					$clock_out_time=$attendance->clock_out;
					if($clock_in_time=='' && $clock_out_time==''){//PunchIn
						if((strtotime($shift_start_after_1hour) <= strtotime($current_date_time)) && (strtotime($shift_end_time) >= strtotime($current_date_time))){
							//Missed Logins
							$subject = str_replace(array("{var punch_time}"),array(
									'Login'),htmlspecialchars_decode(stripslashes($template[0]->subject))).' - '.$cinfo[0]->company_name;
							$full_name = change_fletter_caps($attendance->first_name.' '.$attendance->middle_name.' '.$attendance->last_name);
							$site_url=str_replace('http://','',str_replace('https://','',base_url()));
							$message = '<div>
				'.str_replace(array(
									"{var site_url}",
									"{var site_name}",
									"{var employee_name}",
									"{var punch_time}",
									"{var date}",
									"{var year}"),
									array(
										$site_url,
										$cinfo[0]->company_name,
										$full_name,
										'log in',
										format_date('d F Y',$attendance->attendance_date),
										date('Y')),
									htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';
							if(TESTING_MAIL==TRUE){
								$this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
								$this->email->to(TO_MAIL);
							}else{
								$this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
								//$this->email->to($attendance->email);
								$this->email->to(TO_MAIL);
							}
							$this->email->subject($subject);
							$this->email->message($message);
							if($this->email->send()){				$this->Employees_model->update_mail_trigger($attendance->attendance_date,$attendance->employee_id);
							}
							//echo"<pre>";print_r($message);
						}
					}
					else if($clock_in_time!='' && $clock_out_time==''){//PunchOut
						if((strtotime($shift_end_after_1hour) <= strtotime($current_date_time))){
							//Missed Logouts
							$subject = str_replace(array("{var punch_time}"),array(
									'Logout'),htmlspecialchars_decode(stripslashes($template[0]->subject))).' - '.$cinfo[0]->company_name;
							$full_name = change_fletter_caps($attendance->first_name.' '.$attendance->middle_name.' '.$attendance->last_name);
							$site_url=str_replace('http://','',str_replace('https://','',base_url()));
							$message = '<div>
					'.str_replace(array(
									"{var site_url}",
									"{var site_name}",
									"{var employee_name}",
									"{var punch_time}",
									"{var date}",
									"{var year}"),
									array(
										$site_url,
										$cinfo[0]->company_name,
										$full_name,
										'Log out',
										format_date('d F Y',$attendance->attendance_date),
										date('Y')),
									htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';
							if(TESTING_MAIL==TRUE){
								$this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
								$this->email->to(TO_MAIL);
							}else{
								$this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
								//$this->email->to($attendance->email);
								$this->email->to(TO_MAIL);
							}
							$this->email->subject($subject);
							$this->email->message($message);
							if($this->email->send()){
								$this->Employees_model->update_mail_trigger($attendance->attendance_date,$attendance->employee_id);
							}
							//echo"<pre>";print_r($message);
						}
					}

				}

			}
		}
	}

	//Leave Triggers
	public function leave_triggers(){
		$today_date=date('Y-m-d H:i:s');
		$previous_date=date('Y-m-d',strtotime('-1 day',strtotime($today_date))).' 00:00:00';
		//$previous_date='2018-07-17 00:00:00';
		$get_current_leave_list=$this->Timesheet_model->get_current_leave_list($today_date,$previous_date);
		if($get_current_leave_list){
			foreach($get_current_leave_list as $current_leave_list){
				$check_approval_insert=$this->Timesheet_model->leave_approval_insert_update($current_leave_list->employee_id,$current_leave_list->applied_on,$current_leave_list->reporting_manager,$current_leave_list->leave_type_id);
			}
		}

		$site_url=str_replace('http://','',str_replace('https://','',base_url().base64_encode('MAILREDIRECT-timesheet/leave')));
		if(email_notification('leave_email') == 'yes') {
			$cinfo = $this->Xin_model->read_company_setting_info(1);
			$request_template = $this->Xin_model->read_email_template(5);
			$forward_template = $this->Xin_model->read_email_template(27);

			$get_first_approval_list=$this->Timesheet_model->get_current_approval_list($today_date,$previous_date,1);
			//echo "<pre>";print_r($get_first_approval_list);
			if($get_first_approval_list){
				$group_reporting_managers=$this->group_managers($get_first_approval_list);
				if($group_reporting_managers){
					foreach($group_reporting_managers as $key_m=>$value_m){
						$leave_html='';
						$leave_html.='<table style="text-align:center;border-collapse: collapse;border: 1px solid grey;"><tr><th style="border:1px solid grey;padding: 5px;">Employee Name</th><th style="border:1px solid grey;padding: 5px;">Department</th><th style="border:1px solid grey;padding: 5px;">Type of Leave</th><th style="border:1px solid grey;padding: 5px;">Period</th><th style="border:1px solid grey;padding: 5px;">Days</th></tr>';
						$manager = $this->Xin_model->read_user_info($key_m);
						$manager_name=change_fletter_caps(@$manager[0]->first_name.' '.@$manager[0]->middle_name.' '.@$manager[0]->last_name);
						$manager_email=@$manager[0]->email;
						foreach($value_m as $v_details){
							$leave_details=$this->Timesheet_model->get_leaves_appliedon($v_details->employee_id,$v_details->field_id);
							if($leave_details){
								$leave_html.='<tr><td style="border:1px solid grey;padding: 5px;">'.change_fletter_caps(@$leave_details[0]->first_name.' '.@$leave_details[0]->middle_name.' '.@$leave_details[0]->last_name).'</td><td style="border:1px solid grey;padding: 5px;">'.$leave_details[0]->department_name.'</td><td style="border:1px solid grey;padding: 5px;">'.$leave_details[0]->type_name.'</td><td style="border:1px solid grey;padding: 5px;">'.format_date('d M Y',$leave_details[0]->from_date).' to '.format_date('d M Y',$leave_details[0]->to_date).'</td><td style="border:1px solid grey;padding: 5px;">'.$leave_details[0]->count_of_days.'</td></tr>';
							}
						}
						$leave_html.='</table>';

						//Leave Email Trigger Here
						$subject = $request_template[0]->subject.' - '.$cinfo[0]->company_name;

						$message = '<div>
						'.str_replace(array(
								"{var manager_name}",
								"{var content}",
								"{var site_url}",
								"{var year}"),
								array(
									$manager_name,
									$leave_html,
									$site_url,
									date('Y')),
								htmlspecialchars_decode(stripslashes($request_template[0]->message))).'</div>';
						if(TESTING_MAIL==TRUE){
							$this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
							$this->email->to(TO_MAIL);
						}else{
							$this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
							$this->email->to($manager_email);
						}
						$this->email->subject($subject);
						$this->email->message($message);
						$this->email->send();
						//Leave Email Trigger Here
						//print_r($message);
					}
				}

			}

			$get_second_approval_list=$this->Timesheet_model->get_current_approval_list($today_date,$previous_date,2);
			if($get_second_approval_list){
				$group_hod_managers=$this->group_managers($get_second_approval_list);
				if($group_hod_managers){
					foreach($group_hod_managers as $key_hod=>$value_hod){
						$leave_html='';
						$leave_html.='<table style="text-align:center;border-collapse: collapse;border: 1px solid grey;"><tr><th style="border:1px solid grey;padding: 5px;">Employee Name</th><th style="border:1px solid grey;padding: 5px;">Department</th><th style="border:1px solid grey;padding: 5px;">Type of Leave</th><th style="border:1px solid grey;padding: 5px;">Period</th><th style="border:1px solid grey;padding: 5px;">Days</th></tr>';
						$manager = $this->Xin_model->read_user_info($key_hod);
						$manager_name=change_fletter_caps(@$manager[0]->first_name.' '.@$manager[0]->middle_name.' '.@$manager[0]->last_name);
						$manager_email=@$manager[0]->email;
						foreach($value_hod as $v_details){
							$leave_details=$this->Timesheet_model->get_leaves_appliedon($v_details->employee_id,$v_details->field_id);
							if($leave_details){
								$leave_html.='<tr><td style="border:1px solid grey;padding: 5px;">'.change_fletter_caps(@$leave_details[0]->first_name.' '.@$leave_details[0]->middle_name.' '.@$leave_details[0]->last_name).'</td><td style="border:1px solid grey;padding: 5px;">'.$leave_details[0]->department_name.'</td><td style="border:1px solid grey;padding: 5px;">'.$leave_details[0]->type_name.'</td><td style="border:1px solid grey;padding: 5px;">'.format_date('d M Y',$leave_details[0]->from_date).' to '.format_date('d M Y',$leave_details[0]->to_date).'</td><td style="border:1px solid grey;padding: 5px;">'.$leave_details[0]->count_of_days.'</td></tr>';
							}
						}
						$leave_html.='</table>';

						//Leave Email Trigger Here
						$subject = $forward_template[0]->subject.' - '.$cinfo[0]->company_name;
						$message = '<div>
					'.str_replace(array(
								"{var manager_name}",
								"{var content}",
								"{var site_url}",
								"{var year}"),
								array(
									$manager_name,
									$leave_html,
									$site_url,
									date('Y')),
								htmlspecialchars_decode(stripslashes($forward_template[0]->message))).'</div>';
						if(TESTING_MAIL==TRUE){
							$this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
							$this->email->to(TO_MAIL);
						}else{
							$this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
							$this->email->to($manager_email);
						}
						$this->email->subject($subject);
						$this->email->message($message);
						$this->email->send();
						//Leave Email Trigger Here
						//print_r($message);
					}
				}

			}



			$get_final_approval_list=$this->Timesheet_model->get_current_approval_list($today_date,$previous_date,3);
			if($get_final_approval_list){
				$group_ceo_managers=$this->group_managers($get_final_approval_list);
				if($group_ceo_managers){
					foreach($group_ceo_managers as $key_ceo=>$value_ceo){
						$leave_html='';
						$leave_html.='<table style="text-align:center;border-collapse: collapse;border: 1px solid grey;"><tr><th style="border:1px solid grey;padding: 5px;">Employee Name</th><th style="border:1px solid grey;padding: 5px;">Department</th><th style="border:1px solid grey;padding: 5px;">Type of Leave</th><th style="border:1px solid grey;padding: 5px;">Period</th><th style="border:1px solid grey;padding: 5px;">Days</th></tr>';
						$manager = $this->Xin_model->read_user_info($key_ceo);
						$manager_name=change_fletter_caps(@$manager[0]->first_name.' '.@$manager[0]->middle_name.' '.@$manager[0]->last_name);
						$manager_email=@$manager[0]->email;
						foreach($value_ceo as $v_details){
							$leave_details=$this->Timesheet_model->get_leaves_appliedon($v_details->employee_id,$v_details->field_id);
							if($leave_details){
								$leave_html.='<tr><td style="border:1px solid grey;padding: 5px;">'.change_fletter_caps(@$leave_details[0]->first_name.' '.@$leave_details[0]->middle_name.' '.@$leave_details[0]->last_name).'</td><td style="border:1px solid grey;padding: 5px;">'.$leave_details[0]->department_name.'</td><td style="border:1px solid grey;padding: 5px;">'.$leave_details[0]->type_name.'</td><td style="border:1px solid grey;padding: 5px;">'.format_date('d M Y',$leave_details[0]->from_date).' to '.format_date('d M Y',$leave_details[0]->to_date).'</td><td style="border:1px solid grey;padding: 5px;">'.$leave_details[0]->count_of_days.'</td></tr>';
							}
						}
						$leave_html.='</table>';

						//Leave Email Trigger Here
						$subject = $forward_template[0]->subject.' - '.$cinfo[0]->company_name;
						$message = '<div>
					'.str_replace(array(
								"{var manager_name}",
								"{var content}",
								"{var site_url}",
								"{var year}"),
								array(
									$manager_name,
									$leave_html,
									$site_url,
									date('Y')),
								htmlspecialchars_decode(stripslashes($forward_template[0]->message))).'</div>';
						if(TESTING_MAIL==TRUE){
							$this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
							$this->email->to(TO_MAIL);
						}else{
							$this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
							$this->email->to($manager_email);
						}
						$this->email->subject($subject);
						$this->email->message($message);
						$this->email->send();
						//Leave Email Trigger Here
						//print_r($message);
					}
				}

			}
			//Acknowlegde Mail to HR Administrator
		}
		//For Attn Previous day total working hour calculate for those who does not logout
		$this->check_attn_previous_date();

	}

	public function group_managers($get_first_approval_list){
		$group_managers=[];
		if($get_first_approval_list){
			foreach($get_first_approval_list as $appr_list){
				$group_managers[$appr_list->approval_head_id][]=$appr_list;
			}
		}
		return $group_managers;
	}

	public function check_attn_previous_date(){
		$previous_date=date('Y-m-d',strtotime('-1 day',strtotime(TODAY_DATE)));
		$missed_loginout_employees=$this->Xin_model->missed_loginout_employees($previous_date);
		if($missed_loginout_employees){
			foreach($missed_loginout_employees as $update_pre_attn){
				$user = $this->Xin_model->read_user_info($update_pre_attn->employee_id);
				$department_id=$user[0]->department_id;
				$location_id=$user[0]->office_location_id;
				$working_hours=$user[0]->working_hours;
				$result_ramadan_val='';

				$condition_l = "location_id =" . "'" . $location_id . "'";
				$this->db->select('country,location_name');
				$this->db->from('xin_office_location');
				$this->db->where($condition_l);
				$this->db->limit(1);
				$query_l = $this->db->get();
				$result_l = $query_l->result();
				$country_id=$result_l[0]->country;

				$condition_ram = "is_publish = '1' AND country_id='".$country_id."' AND  '".$update_pre_attn->attendance_date."' BETWEEN start_date and end_date";
				$this->db->select('reduced_hours');
				$this->db->from('xin_ramadan_schedule');
				$this->db->where($condition_ram);
				$this->db->limit(1);
				$query_ram = $this->db->get();
				$result_ramadan = $query_ram->row();
				if($result_ramadan){
					$result_ramadan_val=@$result_ramadan->reduced_hours;
				}
				$this->action_attendance_calculation($update_pre_attn->clock_in,$update_pre_attn->clock_out,$update_pre_attn->employee_id,$update_pre_attn->attendance_date,$update_pre_attn->shift_start_time,$update_pre_attn->shift_end_time,$update_pre_attn->week_off,$department_id,$working_hours,$result_ramadan_val,$user[0]);

			}
		}

	}

	public function syncattendance(){
		$Return = array('result'=>'', 'error'=>'');
		$start_date=format_date('Y-m-d',$this->input->get('start_date'));
		$end_date=format_date('Y-m-d',$this->input->get('end_date'));
		$location_id=$this->input->get('location_id');
		$st_date = strtotime($start_date);
		$ed_date = strtotime($end_date);
		$date_first = new DateTime($start_date);
		$date_second = new DateTime($end_date);
		$date_interval= $date_first->diff($date_second);
		$days=$date_interval->days+1;
		if($Return['error']!=''){
			$this->output($Return);
		}

		$employee = $this->Employees_model->get_employees_uae($location_id)->result();

		foreach($employee as $emp){
			if($emp->biometric_id!=''){
				$emp_biometri_id=$emp->biometric_id;
				$user_id=$emp->user_id;
				$employee_id=$emp->employee_id;
				$office_shift_id=$emp->office_shift_id;
				$office_location_id=$emp->office_location_id;
				$department_id=$emp->department_id;
				$working_hours=$emp->working_hours;
				$begin = new DateTime($start_date);
				$end   = new DateTime($end_date);
				for($dts = $begin; $dts <= $end; $dts->modify('+1 day')){
					$today_date=$dts->format("Y-m-d");
					$find_shifts=$this->Employees_model->get_shifts_bydep_loc($user_id,$office_location_id,$department_id,$today_date);
					
					$response=$this->Employees_model->get_biometric_attendance($emp_biometri_id,$office_location_id,$today_date,$find_shifts['in_time'],$find_shifts['out_time'],$find_shifts['week_off']);
					
					$all_value = $response;
					if(empty($all_value)){
						$this->Employees_model->action_employee_attendance_today($user_id,'','','','','','','','',$today_date,$find_shifts['in_time'],$find_shifts['out_time'],$find_shifts['week_off'],$department_id,0);
						continue;
					}
					$count_res=count($all_value);
					$first_array_key=0;
					$last_array_key=$count_res-1;
					if($count_res==1){
						$check_in_time=$all_value[$first_array_key]['check_in_date'];
						$check_out_time='';
					}
					else if($count_res==2){
						$check_in_time=$all_value[$first_array_key]['check_in_date'];
						$check_out_time=$all_value[$last_array_key]['check_in_date'];
						$new_time = date("Y-m-d H:i:s", strtotime('+15 minutes', strtotime($check_in_time)));
						if($check_out_time < $new_time){
							$check_out_time = '';
						}
					}
					else if($count_res > 2){
						$check_in_time=$all_value[$first_array_key]['check_in_date'];
						$check_out_time=$all_value[$last_array_key]['check_in_date'];
						$new_time = date("Y-m-d H:i:s", strtotime('+15 minutes', strtotime($check_in_time)));
						if($check_out_time < $new_time){
							$check_out_time = '';
						}
					}
					//echo $check_in_time.'--------'.$check_out_time.'--------'.$user_id.'--------'.$today_date.'--------'.$find_shifts['in_time'].'--------'.$find_shifts['out_time'].'--------'.$find_shifts['week_off'];echo "<br>";
					$this->action_attendance_calculation($check_in_time,$check_out_time,$user_id,$today_date,$find_shifts['in_time'],$find_shifts['out_time'],$find_shifts['week_off'],$department_id,$working_hours,$find_shifts['result_ramadan_val'],$emp);

				}
			}
		}
		$Return['result'] = 'Sync Process Completed';
		$this->output($Return);
		exit;
	}

	public function cron_email_birth_day(){

		$cinfo = $this->Xin_model->read_company_setting_info(1);
		$status_email_notification = json_decode($this->Xin_model->read_setting_info(1)[0]->enable_email_notification);
		if($status_email_notification->birthday_email == 'yes'){

			$template = $this->Xin_model->read_email_template_info_bycode('Birthday email');
			$birthday_employees_list = $this->Employees_model->get_this_month_birthday_employees();
			// pr($birthday_employees_list);

			$image_array = array("birthdaywishes2.png","birthdaywishes1.png");

			foreach($birthday_employees_list as $r) {

				$image_name = array_rand($image_array);
				$image_url = site_url().'birthday/'.$image_array[$image_name];

				$emp_name = $r->first_name.' '.$r->middle_name.' '.$r->last_name;
				$reporting_manager = $this->Employees_model->read_employee_information($r->reporting_manager)[0];
				$reporting_manager_mail = $reporting_manager->email;

				$department_head_id = $this->Employees_model->get_department_head_id($r->department_id)[0];
				$department_head = $this->Employees_model->read_employee_information($department_head_id->head_id)[0];
				$hod_mail = $department_head->email;

				$message = '<div style="background: #f7eaea;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin: 0 auto;padding:20px;max-width: 65em;border: 2px solid #D40732;">'.
						str_replace(
							array(
								"{var employee_name}",
								"{var image_url}",
								"{var year}",
								"{var contact_person}",
								"{var hr_email}",
							),
							array(
								$emp_name,
								$image_url,
								date('Y'),
								$cinfo[0]->contact_person,
								$cinfo[0]->email,
							),htmlspecialchars_decode(stripslashes($template[0]->message))
					).'</div>';

				$subject = $template[0]->subject;
				$to_mail_array = array($r->email);
				$cc_mail_array = array($reporting_manager_mail,$hod_mail,$cinfo[0]->email,'fijoe.jm@awok.com','steve@awok.com','siddiq.jalaludeen@awok.com');
				// $to_mail_array = array('siddiq.jalaludeen@awok.com');
				// $cc_mail_array = array('sajith.v@awok.com');

		        $this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
		        $this->email->to($to_mail_array);
		        $this->email->bcc($cc_mail_array);
		        $this->email->subject($subject);
		        $this->email->message($message);
		        $this->email->send();
			}

		}
	}

}
