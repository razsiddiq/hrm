<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Author Siddiqkhan
 *
 * Payroll Controller
 */
class Payroll extends MY_Controller {

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
		$this->load->library('email');
		$this->load->model("Payroll_model");
		$this->load->model("Xin_model");
		$this->load->model("Employees_model");
		$this->load->model("Designation_model");
		$this->load->model("Department_model");
		$this->load->model("Location_model");
		$this->load->model("Timesheet_model");
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

	// payroll templates
	public function templates()
	{
		$data['title'] = $this->Xin_model->site_title();
		$data['breadcrumbs'] = 'Payroll Templates';
		$data['path_url'] = 'payroll_templates';
		$data['all_employees']=$this->Payroll_model->all_employees_salary_new();
		if(in_array('38tv',role_resource_ids()) || in_array('38te',role_resource_ids()) || visa_wise_role_ids() != '') {
			if(!empty($this->userSession)){
				$data['subview'] = $this->load->view("payroll/templates", $data, TRUE);
				$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
	}

	public function pdf_leave_settlement() {
		$this->load->library('Pdf');
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$id = $this->uri->segment(3);
		$leave_settlement=$this->Payroll_model->read_make_payment_information($id);
		if($leave_settlement){
			// echo "<pre>";print_r($final_settlement);	die;
			$user = $this->Employees_model->read_employee_full_data($leave_settlement[0]->employee_id);
			$_des_name = $this->Designation_model->read_designation_information($user[0]->designation_id);
			/*Shift Hours*/
			$d_hours=explode(':',$user[0]->working_hours);
			if(@$d_hours[1]){ $d_mins=@$d_hours[1];} else {$d_mins='0';}
			$f_total_hours = new DateTime($d_hours[0].':'.$d_mins);
			$f_lunch_hours = new DateTime(LUNCH_HOURS);
			$interval_lunch = $f_total_hours;
			if($user[0]->is_break_included == 1)
				$interval_lunch = $f_total_hours->diff($f_lunch_hours);

			$total_work=$interval_lunch->format('%h').':'.$interval_lunch->format('%i');
			$shift_hours = decimalHours($total_work);
			/*Shift Hours*/
			// company info
			$location = $this->Xin_model->read_location_info($user[0]->office_location_id);
			$company = $this->Xin_model->read_company_info($location[0]->company_id);
			$system = $this->Xin_model->read_setting_info(1);
			$fname = $user[0]->first_name.$user[0]->middle_name.$user[0]->last_name;
			//$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
			$company_name = $company[0]->name;
			// set default header data
			$c_info_email = $company[0]->email;
			$c_info_phone = $company[0]->contact_number;
			$country = $this->Xin_model->read_country_info($company[0]->country);
			$c_info_address = $company[0]->address_1.' '.$company[0]->address_2.', '.$company[0]->city.' - '.$company[0]->zipcode.', '.$country[0]->country_name;
			$header_string ="Email : $c_info_email | Phone : $c_info_phone \nAddress: $c_info_address";
			//$pdf->SetProtection(array('print', 'copy','modify'), $user[0]->employee_id, "Siddiq@123", 0, null);
			// set document information
			$pdf->SetCreator('Awok');
			$pdf->SetHeaderData($system[0]->payroll_logo, 40,  $company_name, $header_string);
			// set header and footer fonts
			$pdf->setHeaderFont(Array('dejavusans', '', 10.5));
			$pdf->setFooterFont(Array('dejavusans', '', 6));

			// set default monospaced font
			$pdf->SetDefaultMonospacedFont('courier');
			// set margins
			$pdf->SetMargins(15, 27, 15);
			$pdf->SetHeaderMargin(5);
			$pdf->SetFooterMargin(10);
			// set auto page breaks
			$pdf->SetAutoPageBreak(TRUE, 10);
			// set image scale factor
			$pdf->setImageScale(1.25);
			$pdf->SetAuthor('Mohamed Siddiqkhan');
			$pdf->SetTitle($company[0]->name.' - Print Leave Settlement');
			$pdf->SetSubject('Payslip');
			$pdf->SetKeywords('Payslip');
			// set font
			$pdf->SetFont('dejavusans', 'B', 9);
			// add a page
			$pdf->AddPage();
			$pdf->SetFont('dejavusans', '', 9);
			// -----------------------------------------------------------------------------
			//echo "<pre>";//
			$tbl = '
			<table cellpadding="1" cellspacing="1" border="0">
				<tr>
					<td align="center"><h1>Leave Settlement</h1></td>
				</tr>			
				<tr>
					<td align="center"><strong>Date:</strong> '.date("d F Y",strtotime($leave_settlement[0]->created_at)).'</td>
				</tr>
			</table>';
			//print_r($tbl);
			$pdf->writeHTML($tbl, true, false, false, false, '');
			$tbl = '
		<table cellpadding="5" cellspacing="0" border="1">
			<tr>
				<td>Employee Code</td>
				<td>'.$user[0]->employee_code.'</td>
				<td>Employee Name</td>
				<td>'.change_fletter_caps($user[0]->first_name.' '.$user[0]->middle_name.' '.$user[0]->last_name).'</td>
			</tr>
			<tr>
				<td>Designation</td>
				<td>'.$_des_name[0]->designation_name.'</td>
				<td>Entity/Agency</td>
				<td>'.$user[0]->visa_type.'</td>
			</tr>
			<tr>
				<td>Date of Join</td>
				<td>'.date("F Y", strtotime($user[0]->date_of_joining)).'</td>
				<td>Daily Working Hours</td>
				<td>'.$shift_hours.'</td>
			</tr>
		</table>
		';
			$pdf->writeHTML($tbl, true, false, true, false, '');
			// print_r($tbl);
			// Allowances
			if($leave_settlement[0]->house_rent_allowance!='' || $leave_settlement[0]->house_rent_allowance!=0){
				$hra = $this->Xin_model->currency_sign(change_num_format($leave_settlement[0]->house_rent_allowance),$user[0]->user_id);
			} else { $hra = $this->Xin_model->currency_sign(change_num_format(0),$user[0]->user_id);}
			if($leave_settlement[0]->other_allowance!='' || $leave_settlement[0]->other_allowance!=0){
				$ma = $this->Xin_model->currency_sign(change_num_format($leave_settlement[0]->other_allowance),$user[0]->user_id);
			} else { $ma = $this->Xin_model->currency_sign(change_num_format(0),$user[0]->user_id);}
			if($leave_settlement[0]->travelling_allowance!='' || $leave_settlement[0]->travelling_allowance!=0){
				$ta = $this->Xin_model->currency_sign(change_num_format($leave_settlement[0]->travelling_allowance),$user[0]->user_id);
			} else { $ta = $this->Xin_model->currency_sign(change_num_format(0),$user[0]->user_id);}
			if($leave_settlement[0]->food_allowance!='' || $leave_settlement[0]->food_allowance!=0){
				$da = $this->Xin_model->currency_sign(change_num_format($leave_settlement[0]->food_allowance),$user[0]->user_id);
			} else { $da = $this->Xin_model->currency_sign(change_num_format(0),$user[0]->user_id);}
			if($leave_settlement[0]->additional_benefits!='' || $leave_settlement[0]->additional_benefits!=0){
				$ab = $this->Xin_model->currency_sign(change_num_format($leave_settlement[0]->additional_benefits),$user[0]->user_id);
			} else { $ab = $this->Xin_model->currency_sign(change_num_format(0),$user[0]->user_id);}
			if($leave_settlement[0]->bonus!='' || $leave_settlement[0]->bonus!=0){
				$bn = $this->Xin_model->currency_sign(change_num_format($leave_settlement[0]->bonus),$user[0]->user_id);
			} else { $bn = $this->Xin_model->currency_sign(change_num_format(0),$user[0]->user_id);}
			$total_comp_salary=$leave_settlement[0]->salary_with_bonus;
			$ot_hours_amount=json_decode($leave_settlement[0]->ot_hours_amount);
			$ot_day_rate=$ot_hours_amount->ot_day_amount;
			$ot_night_rate=$ot_hours_amount->ot_night_amount;
			$ot_holiday_rate=$ot_hours_amount->ot_holiday_amount;
			$ot_day_hours=decimalHours($ot_hours_amount->ot_day_hours);
			$ot_night_hours=decimalHours($ot_hours_amount->ot_night_hours);
			$ot_holiday_hours=decimalHours($ot_hours_amount->ot_holiday_hours);
			$tbl = '
		<table cellpadding="4" cellspacing="0" border="0">
			<tr>
				<td><table cellpadding="5" cellspacing="0" border="1">
			<tr style="background-color:#9F9;">
				<td><strong>Monthly Salary</strong></td>
				<td align="right"><strong>Amount</strong></td>
			</tr>
			<tr>
				<td>Basic Salary</td>
				<td align="right">'.$this->Xin_model->currency_sign(change_num_format($leave_settlement[0]->basic_salary),$user[0]->user_id).'</td>
			</tr>
			<tr>
				<td>Accomodation</td>
				<td align="right">'.$hra.'</td>
			</tr>
			
			<tr>
				<td>Transportation</td>
				<td align="right">'.$ta.'</td>
			</tr>
			<tr>
				<td>Food Allowance</td>
				<td align="right">'.$da.'</td>
			</tr>
			<tr>
				<td>Additional Benefits</td>
				<td align="right">'.$ab.'</td>
			</tr>
			<tr>
				<td>Other Allowance</td>
				<td align="right">'.$ma.'</td>
			</tr>
			<tr>
				<td>Bonus</td>
				<td align="right">'.$bn.'</td>
			</tr>
			
			<tr>
				<td>Total Salary</td>
				<td align="right">'.$this->Xin_model->currency_sign(change_num_format($total_comp_salary),$user[0]->user_id).'</td>
			</tr>
			
		</table></td>
				<td><table cellpadding="5" cellspacing="0" border="1">
			<tr style="background-color:#9F9;">
				<td colspan="2"><strong>Working Hours & Over Time</strong></td>
				
			</tr>
	
			<tr>
				<td>Working Hours for the month</td>
				<td align="right">'.$leave_settlement[0]->required_working_hours.'</td>
			</tr>
			
			<tr>
				<td>Actual Working Hours</td>
				<td align="right">'.$leave_settlement[0]->total_working_hours.'</td>
			</tr>
			<tr>
				<td>OT Hours @ 1.25</td>
				<td align="right">'.$ot_day_hours.'</td>
			</tr>
			<tr>
				<td>OT Hours @ 1.5</td>
				<td align="right">'.$ot_night_hours.'</td>
			</tr>
			<tr>
				<td>OT Hours @ PH</td>
				<td align="right">'.$ot_holiday_hours.'</td>
			</tr>		
		</table></td>
			</tr>
		</table>
		';
			$pdf->writeHTML($tbl, true, false, false, false, '');
			// print_r($tbl);
			$add_salary='';
			$ded_salary='';

			$find_internal_add_adjustments = $this->Payroll_model->payment_adjustments($leave_settlement[0]->employee_id,$leave_settlement[0]->make_payment_id,'Addition',$leave_settlement[0]->payment_date);
			$find_internal_ded_adjustments = $this->Payroll_model->payment_adjustments($leave_settlement[0]->employee_id,$leave_settlement[0]->make_payment_id,'Deduction',$leave_settlement[0]->payment_date);
			$adds=0;
			$deds=0;
			if($find_internal_add_adjustments){
				foreach($find_internal_add_adjustments as $int_add_adjustments){
					if($int_add_adjustments->parent_type_name=='Addition'){
						$adds+=$int_add_adjustments->amount;
						$add_salary.='<tr class=""><td>'.$int_add_adjustments->child_type_name.'</td><td></td><td></td><td>'.$int_add_adjustments->comments.'</td><td>'.$this->Xin_model->currency_sign(change_num_format($int_add_adjustments->amount),$user[0]->user_id).'</td></tr>';
					}
				}
			}
			if($find_internal_ded_adjustments){
				foreach($find_internal_ded_adjustments as $int_ded_adjustments){
					if($int_ded_adjustments->parent_type_name=='Deduction'){
						$deds+=$int_ded_adjustments->amount;
						$ded_salary.='<tr class=""><td>'.$int_ded_adjustments->child_type_name.'</td><td></td><td></td><td>'.$int_ded_adjustments->comments.'</td><td>'.$this->Xin_model->currency_sign(change_num_format($int_ded_adjustments->amount),$user[0]->user_id).'</td></tr>';
					}
				}
			}
			$leave_salary_days=0;
			$leave_salary_amount=0;
			$leave_salary_amount=json_decode($leave_settlement[0]->leave_salary_amount);
			foreach($leave_salary_amount as $leave_sl_amount){
				foreach($leave_sl_amount as $key=>$value){
					if($key=='AL'){
						$leave_salary_amount=$value->amount;
						$leave_salary_days=$value->days;
					}
				}
			}
			$total_earnings=$adds+$leave_settlement[0]->month_salary+$ot_day_rate+$ot_night_rate+$ot_holiday_rate+$leave_settlement[0]->annual_leave_salary;


			/*Tax*/
			$tax_html='';
			$tax_amount=json_decode($leave_settlement[0]->tax_amount);
			$tax_value=[];
			if($tax_amount){
				foreach($tax_amount as $tax_t){
					$tax_html.='<tr class=""><td>'.$tax_t->tax_name.'</td><td></td><td></td><td>'.$tax_t->tax_percentage.'%</td><td>'.$this->Xin_model->currency_sign(change_num_format($tax_t->tax_amount),$user[0]->user_id).'</td></tr>';
				}
				$tax_html.='<tr class="green"><td>Payment Amount with Tax</td><td></td><td></td><td></td><td>'.$this->Xin_model->currency_sign(change_num_format($leave_settlement[0]->payment_amount_with_tax),$user[0]->user_id).'</td></tr>';
			}
			/*Tax*/


			$tbl = '
		<table cellpadding="4" cellspacing="0" border="0">
			<tr>
				<td><table cellpadding="5" cellspacing="0" border="1">
			<tr style="background-color:#ff7575;">
				<td align="left"><strong>Elements</strong></td>
				<td align="left"><strong>From</strong></td>
				<td align="left"><strong>To</strong></td>
				<td align="middle"><strong>Days</strong></td>
				<td align="right"><strong>Amount</strong></td>
			</tr>
			<tr>
				<td colspan="5" align="left"><strong>Earnings</strong></td>
			
			</tr>
			<tr>
				<td align="left">Current Month Salary - '.format_date('F Y',$leave_settlement[0]->payment_date).'</td>
				<td>'.format_date('d F Y',$leave_settlement[0]->leave_settlement_start_date).'</td>
				<td>'.format_date('d F Y',$leave_settlement[0]->leave_settlement_end_date).'</td>
				<td></td>
				<td>'.$this->Xin_model->currency_sign(change_num_format($leave_settlement[0]->month_salary),$user[0]->user_id).'</td>
			</tr>
			<tr>
				<td align="left">Leave Salary</td>
				<td>'.format_date('d F Y',$leave_settlement[0]->leave_start_date).'</td>
				<td>'.format_date('d F Y',$leave_settlement[0]->leave_end_date).'</td>
				<td>'.$leave_salary_days.'</td>
				<td>'.$this->Xin_model->currency_sign(change_num_format($leave_settlement[0]->annual_leave_salary),$user[0]->user_id).'</td>
			</tr>
			<tr>
				<td align="left">OT Amount @ 1.25</td>
				<td></td>
				<td></td>
				<td></td>
				<td>'.$this->Xin_model->currency_sign(change_num_format($ot_day_rate),$user[0]->user_id).'</td>
			</tr>
			<tr>
				<td align="left">OT Amount @ 1.5</td>
				<td></td>
				<td></td>
				<td></td>
				<td>'.$this->Xin_model->currency_sign(change_num_format($ot_night_rate),$user[0]->user_id).'</td>
			</tr>
			<tr>
				<td align="left">OT Amount @ PH</td>
				<td></td>
				<td></td>
				<td></td>
				<td>'.$this->Xin_model->currency_sign(change_num_format($ot_holiday_rate),$user[0]->user_id).'</td>
			</tr>
			
			
			'.$add_salary.'
			<tr style="background-color:#9F9;">
				<td align="left">Total Earnings</td>
				<td></td>
				<td></td>
				<td></td>
				<td>'.$this->Xin_model->currency_sign(change_num_format($total_earnings),$user[0]->user_id).'</td>
			</tr>
			<tr>
				<td colspan="5" align="left"><strong>Deductions</strong></td>
			
			</tr>
			'.$ded_salary.'
			<tr style="background-color:#ff7575;">
				<td align="left">Total Deduction</td>
				<td></td><td></td><td></td>
				<td>'.$this->Xin_model->currency_sign(change_num_format($deds),$user[0]->user_id).'</td>
			</tr>
			<tr style="background-color:#9F9;">
				<td align="left">Net Payable</td>
				<td></td><td></td><td></td>
				<td>'.$this->Xin_model->currency_sign(change_num_format($total_earnings-$deds),$user[0]->user_id).'</td>
			</tr>'.$tax_html.'
		</table></td>
				
			</tr>
		</table>
		';
			$pdf->writeHTML($tbl, true, false, false, false, '');

			$query1=$this->db->query('select * from xin_employees_approval where employee_id="'.$leave_settlement[0]->employee_id.'" AND type_of_approval="Leave Settlement" AND field_id="'.$leave_settlement[0]->make_payment_id.'" AND pay_date="'.$leave_settlement[0]->payment_date.'"');
			$html='';
			$result1=$query1->result();
			if($result1){
				$html1.='<table cellpadding="5" cellspacing="0" border="1">
			    <tr  style="background-color:#9F9;">
				<td colspan="3"><strong>Leave Settlement Status</strong></td>				
			</tr><tr >';

				foreach($result1 as $approv_st){
					if($approv_st->approved_date!=''){$approved_date=format_date('d F Y',$approv_st->approved_date);}else{$approved_date= '-';}
					if($approv_st->approval_status==0){$approval_status='<span class="badge bg-info">Waiting for approval</span>';}else if($approv_st->approval_status==1){

						$approval_status= '<span class="badge bg-success">Approved</span>';}else if($approv_st->approval_status==2){$approval_status= '<span class="badge bg-danger">Declined</span>';}
					$html1.='<tr>
					<td>'.$approv_st->head_of_approval.'</td>
					<td>'.$approved_date.'</td>
					<td>'.$approval_status.'</td>    </tr>  ';

				}


				$html1.='</tr></table>';
			}

			$tbl=$html1;
			$pdf->writeHTML($tbl, true, false, false, false, '');
			$fname=change_fletter_caps($user[0]->first_name.' '.$user[0]->middle_name.' '.$user[0]->last_name);
			$fname = strtolower($fname);
			$strtotime =strtotime(date('Y-m-d H:i:s'));
			$app_name=str_replace(' ','_','Leave Settlement');
			//Close and output PDF document
			ob_end_clean();
			$pdf->Output($app_name.$fname.'_'.$strtotime.'.pdf', 'D');
		}
	}

	// create pdf - payroll
	public function pdf_create($sl='',$id='',$dt='') {
		$this->load->library('Pdf');
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


		if($dt!=''){
			$id=$dt;
		}else{
			$id = $this->uri->segment(4);
		}

		$payment = $this->Payroll_model->read_make_payment_information($id);
		//echo "<pre>";print_r($payment);die;
		$user = $this->Xin_model->read_user_info($payment[0]->employee_id);
		$_des_name = $this->Designation_model->read_designation_information($user[0]->designation_id);
		$department = $this->Department_model->read_department_information($user[0]->department_id);
		$location = $this->Xin_model->read_location_info($user[0]->office_location_id);
		// company info
		$company = $this->Xin_model->read_company_info($user[0]->company_id);
		$system = $this->Xin_model->read_setting_info(1);

		$p_method_type=$this->Xin_model->read_document_type($payment[0]->payment_method,'payment_method');
		$p_method=@$p_method_type[0]->type_name;

		//$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
		$company_name = $company[0]->name;
		// set default header data
		$c_info_email = $company[0]->email;
		$c_info_phone = $company[0]->contact_number;
		$country = $this->Xin_model->read_country_info($company[0]->country);
		$c_info_address = $company[0]->address_1.' '.$company[0]->address_2.', '.$company[0]->city.' - '.$company[0]->zipcode.', '.$country[0]->country_name;
		$header_string ="Email : $c_info_email | Phone : $c_info_phone \nAddress: $c_info_address";

		//$pdf->SetProtection(array('print', 'copy','modify'), $user[0]->employee_id, "Siddiq@123", 0, null);
		// set document information
		$pdf->SetCreator('HRMS');
		$pdf->SetHeaderData('$system[0]->payroll_logo', 40,  $company_name, $header_string);

		// set header and footer fonts
		$pdf->setHeaderFont(Array('helvetica', '', 11.5));
		$pdf->setFooterFont(Array('helvetica', '', 8));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont('courier');
		// set margins
		$pdf->SetMargins(15, 27, 15);
		$pdf->SetHeaderMargin(5);
		$pdf->SetFooterMargin(10);
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, 25);
		// set image scale factor
		$pdf->setImageScale(1.25);
		$pdf->SetAuthor('Siddiq');
		$pdf->SetTitle($company[0]->name.' - Print Payslip');
		$pdf->SetSubject('Payslip');
		$pdf->SetKeywords('Payslip');
		// set font
		$pdf->SetFont('helvetica', 'B', 11);
		// add a page
		$pdf->AddPage();
		$pdf->SetFont('helvetica', '', 10);
		// -----------------------------------------------------------------------------

		$month_year=date('Y-m',strtotime($payment[0]->payment_date));
		$attendance=explode('-',$payment[0]->payment_date);
		$days_count_per_month=cal_days_in_month(CAL_GREGORIAN,$attendance[1],$attendance[0]);
		$salary_start_date=$month_year.'-01';
		$salary_end_date=$month_year.'-'.$days_count_per_month;


		$tbl = '
		<table cellpadding="1" cellspacing="1" border="0">
			<tr>
				<td align="center"><h1>Payslip - '.date("F Y", strtotime($payment[0]->payment_date)).'</h1></td>
			</tr>
			<tr>
				<td align="center">Salary Period For The Month - '.date("d F Y", strtotime($salary_start_date)).' to '.date("d F Y", strtotime($salary_end_date)).'</td>
			</tr>
			<tr>
				<td align="right">(Figures in <strong>'.$payment[0]->currency.'</strong>)</td>
			</tr>
		</table>
		';
		$pdf->writeHTML($tbl, true, false, false, false, '');

		// -----------------------------------------------------------------------------

		$fname = $user[0]->first_name.' '.$user[0]->middle_name.' '.$user[0]->last_name;
		$attendance=explode('-',$payment[0]->payment_date);
		$days_count_per_month=cal_days_in_month(CAL_GREGORIAN,$attendance[1],$attendance[0]);
		$tbl = '
		<table cellpadding="5" cellspacing="0" border="1">
			<tr>
				<td>Name</td>
				<td>'.$fname.'</td>
				<td>Employee ID</td>
				<td>'.$user[0]->employee_id.'</td>
			</tr>
			<tr>
				<td>Department</td>
				<td>'.@$department[0]->department_name.'</td>
				<td>Position Title</td>
				<td>'.$_des_name[0]->designation_name.'</td>
			</tr>
			<tr>
				<td>Date of Joining</td>
				<td>'.$this->Xin_model->set_date_format($user[0]->date_of_joining).'</td>
				<td>Account Number</td>
			<td>'.employee_default_bankaccount($user[0]->user_id).'</td>
			</tr>
	     	<tr>
				<td>Total Working Hours</td>
				<td>'.$payment[0]->required_working_hours.'</td>
				<td>Actual Working Hours</td>
				<td>'.decimalHours($payment[0]->total_working_hours).'</td>
			</tr>
			<tr>
			<td>No of Days in a Month</td>
				<td>'.$days_count_per_month.'</td>
			
			<td>Actual Days Worked</td>
			<td>'.round($payment[0]->actual_days_worked,2).'</td>
			</tr>
			
		</table>
		';

		$pdf->writeHTML($tbl, true, false, true, false, '');





		if($payment[0]->bonus!='' || $payment[0]->bonus!=0){
			$bn = change_num_format($payment[0]->bonus);
		} else { $bn = change_num_format(0);}

		$salary_components=json_decode($payment[0]->salary_components);

		//$total_comp_salary=$payment[0]->total_salary;
		$total_comp_salary=$payment[0]->salary_with_bonus;

		$leave_t='';
		if($payment[0]->leave_start_date==''){$l_date='';}else{$l_date='<br>('.format_date('d F Y',$payment[0]->leave_start_date).' to '.format_date('d F Y',$payment[0]->leave_end_date).')';}
		$leave_salary_amount=json_decode($payment[0]->leave_salary_amount);
		$leave_t.='<tr><td>Leave Salary:'.$l_date.'</td><td align="right">';
		foreach($leave_salary_amount as $leave_sl){
			$l_amount=0;
			if($leave_sl){

				foreach($leave_sl as $l_key=>$l_value){
					if($l_key=='AL'){
						$l_amount+=$l_value->amount;

					}else if($l_key=='Final-Leave-settlement'){
						$l_amount+=$l_value->amount;
					}
				}

			}


		}
		$leave_t.=change_num_format($l_amount);
		$leave_t.='</td></tr>';



		$ot_hours_amount=json_decode($payment[0]->ot_hours_amount);
		$ot_day_rate=$ot_hours_amount->ot_day_amount;
		$ot_night_rate=$ot_hours_amount->ot_night_amount;
		$ot_holiday_rate=$ot_hours_amount->ot_holiday_amount;
		$leave_t.='<tr><td>Overtime Amount:</td><td align="right">';
		$ot_amount=$ot_day_rate+$ot_night_rate+$ot_holiday_rate;
		$leave_t.=change_num_format($ot_amount);
		$leave_t.='</td></tr>';


		$total_comp_salary+=$l_amount+$ot_amount;

		$add_sals=$this->Payroll_model->get_additional_salary($payment[0]->make_payment_id,'Addition');
		$sl_t='';
		if($add_sals){

			foreach($add_sals as $add_sal){

				if($add_sal->amount!=''){
					$total_comp_salary+=$add_sal->amount;
					$sl_t.='<tr><td>'.$add_sal->child_type_name.'</td><td align="right">'.change_num_format($add_sal->amount).'</td></tr>';
				}

			}

		}
		//$tt=$payment[0]->net_salary-$payment[0]->total_salary;

		$ded_sals=$this->Payroll_model->get_additional_salary($payment[0]->make_payment_id,'Deduction');



		$exceptional_employees=exceptional_employees($user[0]->user_id);
		if($exceptional_employees=='Yes'){
			$tt=round((($payment[0]->salary_with_bonus/$payment[0]->required_working_hours)),2);
			$tt=abs($payment[0]->month_salary-$payment[0]->salary_with_bonus);
		}else{
			//$tt=round((($payment[0]->salary_with_bonus/$payment[0]->required_working_hours)*$payment[0]->late_working_hours),2);
			//$tt+=round((($payment[0]->salary_with_bonus-$payment[0]->total_salary)),2);

			$tt=round((($payment[0]->salary_with_bonus-$payment[0]->month_salary)),2);
			echo currency_sign(change_num_format($tt),$currency);

		}

		$total_deduct=$tt;

		$dl_t='';
		if($ded_sals){

			foreach($ded_sals as $ded_sal){

				if($ded_sal->amount!=''){
					$total_deduct+=$ded_sal->amount;
					$dl_t.='<tr><td>'.$ded_sal->child_type_name.'</td><td align="right">'.change_num_format($ded_sal->amount).'</td></tr>';
				}

			}

		}
		$tb2= '
		<table cellpadding="4" cellspacing="0" border="0">
			<tr>
				<td><table cellpadding="5" cellspacing="0" border="1">
			<tr style="background-color:#9F9;">
				<td><strong>Earning Salary</strong></td>
				<td align="right"><strong>Amount</strong></td>
			</tr>
			<tr>
				<td>Basic Salary</td>
				<td align="right">'.change_num_format($payment[0]->basic_salary).'</td>
			</tr>';

		if($salary_components){
			foreach($salary_components as $key_com=>$val_com){
				if($val_com!='' || $val_com!=0){
					$allowan = change_num_format($val_com);
				} else { $allowan = change_num_format(0);}
				$tb2.='<tr>
				<td>'.salary_title_change($key_com).'</td>
				<td align="right">'.$allowan.'</td>
				</tr>';
			}
		}
		$tb2.='<tr>
				<td>Bonus</td>
				<td align="right">'.$bn.'</td>
			</tr>'.$leave_t.''.$sl_t.'
			
			
			
		</table></td>
				<td><table cellpadding="5" cellspacing="0" border="1">
			<tr style="background-color:#ff7575;">
				<td><strong>Deduction Salary</strong></td>
				<td align="right"><strong>Amount</strong></td>
			</tr>
			<tr>
				<td>Absence(s) / Late(s) / Early Out(s):</td>
				<td align="right">'.change_num_format($tt).'</td>
			</tr>'.$dl_t.'
			
		</table></td>
			</tr>
		</table>
		';

		$pdf->writeHTML($tb2, true, false, false, false, '');

		// -----------------------------------------------------------------------------

		/*Tax*/
		$tax_html='';
		/*$tax_amount=json_decode($payment[0]->tax_amount);
		$tax_value=[];
		if($tax_amount){
		foreach($tax_amount as $tax_t){
		$tax_html.='<tr>
				<td colspan="2">&nbsp;</td>
				<td>'.$tax_t->tax_name.' ('.$tax_t->tax_percentage.'%)</td>
				<td align="right">'.change_num_format($tax_t->tax_amount).'</td>
			</tr>';
		}
		$tax_html.='<tr>
				<td colspan="2">&nbsp;</td>
				<td>Payment Amount with Tax</td>
				<td align="right">'.change_num_format($payment[0]->payment_amount_with_tax).'</td>
			</tr>';
		}*/
		/*Tax*/

		$tbl = '
		<table cellpadding="5" cellspacing="0" border="1">
			<tr style="background-color:#c4e5fd;">
			  <th colspan="4" align="center"><strong>Payment Details</strong></th>
			 </tr>
			
			<tr>
				<td colspan="2">&nbsp;</td>
				<td>Total Compensation</td>
				<td align="right">'.change_num_format($total_comp_salary).'</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td>Total Deductions</td>
				<td align="right">'.change_num_format(@$total_deduct).'</td>
			</tr>
		
			<tr>
				<td colspan="2">&nbsp;</td>
				<td>Net Salary</td>
				<td align="right">'.change_num_format($payment[0]->payment_amount_to_employee).'</td>
			</tr>
			'.$tax_html.'
		</table>
		';



		$pdf->writeHTML($tbl, true, false, false, false, '');

		$tbl = '
		<table cellpadding="5" cellspacing="0" border="0">
			<tr>
				<td align="right" colspan="4">System Generated Payslip.Doesn\'t require any signature.</td>
			</tr>
		</table>
		';

		$pdf->writeHTML($tbl, true, false, false, false, '');

		ob_end_clean();
		if($dt!=''){
			$fname = str_replace(' ','_',strtolower($fname));
			$pay_month = strtolower(date("F Y", strtotime($payment[0]->payment_date)));
			$pdf->Output('/var/www/html/workablezone/uploads/'.$fname.$pay_month.'.pdf', 'F');
			return '/var/www/html/workablezone/uploads/'.$fname.$pay_month.'.pdf';


		}else{
			$fname = strtolower($fname);
			$pay_month = strtolower(date("F Y", strtotime($payment[0]->payment_date)));
			//Close and output PDF document

			$pdf->Output('payslip_'.$fname.'_'.$pay_month.'.pdf', 'D');
		}
	}

	public function count_of_absent_status($from_date,$to_date,$user_id,$exclude=''){
		// AL SL-1 SL-2 ML
		$this->load->model("Timesheet_model");
		$annual_id=$this->Timesheet_model->read_leave_type_id('Annual Leave');
		$authorised_id=$this->Timesheet_model->read_leave_type_id('Authorised Absence');
		$sick_id=$this->Timesheet_model->read_leave_type_id('Sick Leave');
		$maternity_id=$this->Timesheet_model->read_leave_type_id('Maternity Leave');
		$emergency_id=$this->Timesheet_model->read_leave_type_id('Emergency Leave');

		$start_date = new DateTime($from_date);
		$end_date = new DateTime($to_date);
		$end_date = $end_date->modify( '+1 day' );
		$interval_re = new DateInterval('P1D');
		$date_range = new DatePeriod($start_date, $interval_re ,$end_date);

		$annual_absent=0;
		$authorised_absent=0;
		$sick_result=0;
		$maternity_result=0;
		$emergency_absent=0;

		foreach($date_range as $date) {
			$attendance_date = $date->format("Y-m-d");
			//$annual_absent+= $this->Timesheet_model->check_leave_unpaid_type($attendance_date,$user_id,$annual_id,$exclude);
			$authorised_absent+= $this->Timesheet_model->check_leave_unpaid_type($attendance_date,$user_id,$annual_id,$maternity_id,$sick_id,$exclude);
			//$emergency_absent+= $this->Timesheet_model->check_leave_unpaid_type($attendance_date,$user_id,$emergency_id,$exclude);

			//$maternity_result+= $this->Timesheet_model->check_maternity_leave_type($attendance_date,$user_id,$maternity_id,$exclude);
			//$sick_result+= $this->Timesheet_model->check_leave_unpaid_type($attendance_date,$user_id,$sick_id,$exclude);
		}
		//For Sick Leave
		$all_array=(@$authorised_absent+@$annual_absent+@$sick_result+@$maternity_result+@$emergency_absent);
		return $all_array;
	}

	public function clearanceform(){
		$emp_id=$this->input->post('employee_id');
		$employee_details = $this->Employees_model->read_employee_full_data($emp_id);
		$joining_date=format_date('Y-m-d',$employee_details[0]->date_of_joining);
		$leaving_date=format_date('Y-m-d',$employee_details[0]->date_of_leaving);
		$s_end_date=date('Y-m-d',strtotime("0 Day",strtotime($employee_details[0]->date_of_leaving)));
		$finished_date=format_date('Y-m-d',$employee_details[0]->date_of_leaving);
		//$implement_date=TODAY_DATE;

		/*Resignation Date*/
		$query_res=$this->db->query("select resignation_date from xin_employee_resignations where employee_id='".$emp_id."' limit 1");
		$result_res=$query_res->result();
		if($result_res){
			$resignation_date=$result_res[0]->resignation_date;
		}else{
			$resignation_date=$s_end_date;
		}
		/*Resignation Date*/

		$row = $this->Xin_model->read_document_type($this->input->post('exit_type'),'exit_type');
		$exit_type=$row[0]->type_name;

		if($employee_details[0]->contract_date!=''){
			$contract_date=date('d F Y',strtotime($employee_details[0]->contract_date));
		}else{
			$contract_date='N/A';
		}
		//////

		$html='';
		//$html.='<div style="position: absolute;z-index: 100;left: 5.3em;top: 8em;cursor:pointer"><i class="icon-printer" style="color:#249E92;" id="btnPrint"></i></div>';
		$html.='<div class="panel panel-flat" id="dvContainer"><div class="panel-heading text-center"><h5 class="panel-title">					 
		<strong>Clearance Form<br>(Employee/Contract)</strong></h5><div class="pull-right mr-10">Date Processed: '.date('d F Y',strtotime(TODAY_DATE)).'</div>	</div>';
		$html.='<div class="panel-body">';

		$html.='<div class="col-lg-12 successtable-responsive"	><table class=" bg-teal-300 table table-md table-bordered "><tbody>
	
	
		<tr class=""><td>Employee Name</td><td>'.change_fletter_caps($employee_details[0]->first_name.' '.$employee_details[0]->middle_name.' '.$employee_details[0]->last_name).'</td><td>Visa</td><td>'.$employee_details[0]->visa_type.'</td></tr>
			
		<tr class=""><td>Designation</td><td>'.change_fletter_caps($employee_details[0]->designation_name).'</td><td>Department</td><td>'.$employee_details[0]->department_name.'</td></tr>
		
		<tr class=""><td>Joining Date</td><td>'.format_date('d F Y',$employee_details[0]->date_of_joining).'</td><td>Resignation Date</td><td>'.format_date('d F Y',$resignation_date).'</td></tr>
		<tr class=""><td>Last Working Day</td><td>'.format_date('d F Y',$s_end_date).'</td><td></td><td></td></tr>	
		
		<tr class=""><td>Nature of Cessation of Employement<br> **pls.check applicable box**</td><td colspan="3">
		<table class="table table-md table-bordered ">
		<input type="hidden" name="employee_name" value="'.change_fletter_caps($employee_details[0]->first_name.' '.$employee_details[0]->middle_name.' '.$employee_details[0]->last_name).'">
		
		
		<input type="hidden" name="visa" value="'.$employee_details[0]->visa_type.'">
		<input type="hidden" name="designation" value="'.$employee_details[0]->designation_name.'">
		<input type="hidden" name="department" value="'.$employee_details[0]->department_name.'">
		<input type="hidden" name="resignation_date" value="'.$resignation_date.'">
		<input type="hidden" name="joining_date" value="'.$employee_details[0]->date_of_joining.'">
		<input type="hidden" name="last_working_day" value="'.$s_end_date.'">
		
		
		<tr><div class="form-group">
							
										<label class="radio-inline radio-right">
											<input name="cessation_of_employement" checked="checked" onclick="show_cessation(0);" type="radio" value="Resignation">
											Resignation
										</label>

										<label class="radio-inline radio-right">
											<input name="cessation_of_employement" onclick="show_cessation(0);" type="radio" value="Termination">
											Termination
										</label>
										
									<label class="radio-inline radio-right">
											<input name="cessation_of_employement" onclick="show_cessation(0);" type="radio" value="Expiry-of-contract">
											Expiry of Contract
										</label>
										
										<label class="radio-inline radio-right">
											<input name="cessation_of_employement" onclick="show_cessation(1);" type="radio"  value="Other/s-pls.specify">
											Other/s pls.specify
										</label>
										
										<input style="display:none;" class="mt-10 form-input text-teal" name="cessation_of_employement_other" type="text"  value="">
									</div></tr>
									
									
	
		</table>
		
		<script>		
		function show_cessation(vals){
			
			if(vals=="1"){
		$("input[name=cessation_of_employement_other]").show();	
		}else{
		$("input[name=cessation_of_employement_other]").hide();	
		}
		}
		</script>
		</td></tr>
		<tr><td colspan="3">Persons in charge are required to sign in the respective sections and/or department below:<td><tr>';
		$html.='</tbody></table></div>';


		$html.='<div class="mt-10 col-lg-6 table-responsive" style="min-height: 35em;"><table class="table bg-teal-300 table-bordered  table-md"><tbody>
		<tr class=""><td colspan="3"><strong>1.Own Department\'s Clearance</strong></td></tr>	
		<tr class=""><td>Bitrix Account</td><td><input class="form-control" type="hidden" name="department_bitrix_account"/></td></tr>
		<tr class=""><td>Awok Logistics System (ALS)</td><td><input class="form-control" type="hidden" name="department_awok_logistics_system"/></td></tr>
		<tr class=""><td>RTA Fine</td><td><input class="form-control" type="hidden" name="department_rta_fine"/></td></tr>
		<tr class=""><td>Vehicle Damage / Fines</td><td><input class="form-control" type="hidden" name="department_vehicle_damage_fines"/></td></tr>
		<tr class=""><td>Etisalat Bills Outstanding</td><td><input class="form-control" type="hidden" name="department_etisalat_bills_outstanding"/></td></tr>
		<tr class=""><td>Remarks</td><td><input class="form-control" type="hidden" name="department_remarks"/></td></tr>
		<tr class=""><td>Name</td><td><input class="form-control" type="hidden" name="department_name"/></td></tr>
		<tr class=""><td>Signature</td><td><input class="form-control" type="hidden" name="department_signature"/></td></tr>		
		<tr><td>Date</td><td><input class="form-control" type="hidden" name="department_date"/></td></tr>	
		</tbody></table></div>';


		$html.='<div class="mt-10 col-lg-6 table-responsive" style="min-height: 35em;"><table class="table bg-teal-300 table-bordered  table-md"><tbody>
		<tr class=""><td colspan="3"><strong>2.IT Clearance</strong></td></tr>	
		<tr class=""><td>Bitrix</td><td><input class="form-control" type="hidden" name="it_bitrix"/></td></tr>
		<tr class=""><td>Email</td><td><input class="form-control" type="hidden" name="it_email"/></td></tr>
		<tr class=""><td>Skype (if official)</td><td><input class="form-control" type="hidden" name="it_skype"/></td></tr>
		<tr class=""><td>1c (if applicable)</td><td><input class="form-control" type="hidden" name="it_ic"/></td></tr>
		<tr class=""><td>Awok.com Access</td><td><input class="form-control" type="hidden" name="it_awok_com_access"/></td></tr>
		<tr class=""><td>Other/s (pls specify)</td><td><input class="form-control" type="hidden" name="it_other_specify"/></td></tr>
		<tr class=""><td>Desktop</td><td><input class="form-control" type="hidden" name="it_desktop"/></td></tr>
		<tr class=""><td>Laptop</td><td><input class="form-control" type="hidden" name="it_laptop"/></td></tr>
		<tr class=""><td>Mouse</td><td><input class="form-control" type="hidden" name="it_mouse"/></td></tr>
		<tr class=""><td>Headset</td><td><input class="form-control" type="hidden" name="it_headset"/></td></tr>
		<tr class=""><td>Keyboard</td><td><input class="form-control" type="hidden" name="it_keyboard"/></td></tr>
		<tr class=""><td>Remarks</td><td><input class="form-control" type="hidden" name="it_remarks"/></td></tr>
		<tr class=""><td>Name</td><td><input class="form-control" type="hidden" name="it_name"/></td></tr>
		<tr class=""><td>Signature</td><td><input class="form-control" type="hidden" name="it_signature"/></td></tr>		
	    <tr><td>Date</td><td><input class="form-control" type="hidden" name="it_date"/></td></tr>	
		</tbody></table></div> <div class="clear-fix"></div>';



		$html.='<div class="mt-10 col-lg-6 table-responsive" style="min-height: 35em;"><table class="table bg-teal-300 table-bordered  table-md"><tbody>
		<tr class=""><td colspan="3"><strong>3.HR Clearance</strong></td></tr>	
		<tr class=""><td>Labour Card</td><td><input class="form-control" type="hidden" name="hr_labour_card"/></td></tr>
		<tr class=""><td>Emirates Card</td><td><input class="form-control" type="hidden" name="hr_emirates_card"/></td></tr>
		<tr class=""><td>Medical Card</td><td><input class="form-control" type="hidden" name="hr_medical_card"/></td></tr>
		<tr class=""><td>Exit Interview</td><td><input class="form-control" type="hidden" name="hr_exit_interview"/></td></tr>

		<tr class=""><td>Remarks</td><td><input class="form-control" type="hidden" name="hr_remarks"/></td></tr>
		<tr class=""><td>Name</td><td><input class="form-control" type="hidden" name="hr_name"/></td></tr>
		<tr class=""><td>Signature</td><td><input class="form-control" type="hidden" name="hr_signature"/></td></tr>		
		<tr><td>Date</td><td><input class="form-control" type="hidden" name="hr_date"/></td></tr>	
		</tbody></table></div>';

		$html.='<div class="mt-10 col-lg-6 table-responsive" style="min-height: 35em;"><table class="table bg-teal-300 table-bordered  table-md"><tbody>
		<tr class=""><td colspan="3"><strong>4.Account\'s Clearance</strong></td></tr>	
		<tr class=""><td>Claims Settlement (1511)</td><td><input class="form-control" type="hidden" name="account_claims_settlement_1511"/></td></tr>
		<tr class=""><td>Advance to Employee for company purpose(3630) <br> 3630.01(AED)/3630.02(AED) (other currency)</td><td><input class="form-control" type="hidden" name="account_advance_to_employee_for_company_purpose"/></td></tr>
		<tr class=""><td>Settlement with Personnel On Payment.(3520)</td><td><input class="form-control" type="hidden" name="account_settlement_with_personnel_on_payment_3520"/></td></tr>
		<tr class=""><td>Settlement with Personnel Outsources.(3523)</td><td><input class="form-control" type="hidden" name="account_settlement_with_personnel_outsources_3523"/></td></tr>
		<tr class=""><td>Other/s (if any)</td><td><input class="form-control" type="hidden" name="account_other_specify"/></td></tr>
		<tr class=""><td>Total Amount Payable</td><td><input class="form-control" type="hidden" name="account_total_amount_payable"/></td></tr>

		<tr class=""><td>Prepared By</td><td><input class="form-control" type="hidden" name="account_prepardby"/></td></tr>
		<tr class=""><td>Checked By</td><td><input class="form-control" type="hidden" name="account_checkedby"/></td></tr>

		<tr class=""><td>Remarks</td><td><input class="form-control" type="hidden" name="account_remarks"/></td></tr>
		<tr class=""><td>Name</td><td><input class="form-control" type="hidden" name="account_name"/></td></tr>
		<tr class=""><td>Signature</td><td><input class="form-control" type="hidden" name="account_signature"/></td></tr>		
		<tr><td>Date</td><td><input class="form-control" type="hidden" name="account_date"/></td></tr>	
		</tbody></table></div>';
		$data=array('html_content'=>base64_encode($html));
		echo json_encode($data);
		exit;
	}

	public function finalsettlement(){
		$emp_id=$this->input->post('employee_id');
		$employee_details = $this->Employees_model->read_employee_full_data($emp_id);
		$joining_date=format_date('Y-m-d',$employee_details[0]->date_of_joining);
		$leaving_date=format_date('Y-m-d',$this->input->post('date_of_leaving'));
		$system_settings = $this->Xin_model->read_setting_info(1);
		$count_of_leave_status=$this->count_of_absent_status($joining_date,$leaving_date,$emp_id);
		$finished_date=format_date('Y-m-d',$leaving_date);
		$exceptional_employees=json_decode($system_settings[0]->exceptional_employees);
		$employee_contracts = $this->Employees_model->read_employee_contracts($emp_id);
		$row = $this->Xin_model->read_document_type($this->input->post('exit_type'),'exit_type');
		$monthly_target_count=$system_settings[0]->monthly_target_count;
		$order_cancellation_percentage=$system_settings[0]->order_cancellation_percentage;

		$eligible_visa_under=json_decode($system_settings[0]->eligible_visa_under);
		$exit_type=$row[0]->type_name;
		$department = $this->Department_model->read_department_information($employee_details[0]->department_id);
		$department_name=$department[0]->department_name;
		$visa_name=$employee_details[0]->visa_type;
		$grace_eligible=grace_eligible($employee_details[0]->department_id,$employee_details[0]->office_location_id);
		/*Shift Hours*/
		$d_hours=explode(':',$employee_details[0]->working_hours);
		if(@$d_hours[1]){ $d_mins=@$d_hours[1];} else {$d_mins='0';}
		$f_total_hours = new DateTime($d_hours[0].':'.$d_mins);
		$f_lunch_hours = new DateTime(LUNCH_HOURS);

		$interval_lunch = $f_total_hours;
		if($employee_details[0]->is_break_included == 1)
			$interval_lunch = $f_total_hours->diff($f_lunch_hours);

		$total_work=$interval_lunch->format('%h').':'.$interval_lunch->format('%i');
		$shift_hours = decimalHours($total_work);
		/*Shift Hours*/

		if($leaving_date!=''){
			$date_of_leaving=date('d F Y',strtotime($leaving_date));
			$s_date=salary_start_end_date(date('Y-m',strtotime($leaving_date)));

			$s_end_date=date('Y-m-d',strtotime("0 Day",strtotime($leaving_date)));
			$leaving_date1=new DateTime($s_end_date);
			$begin_date=new DateTime($employee_details[0]->date_of_joining);
			$interval_date=$leaving_date1->diff($begin_date);
			$no_of_days_worked=$interval_date->days;


			$days_count_per_month=cal_days_in_month(CAL_GREGORIAN,$attendance[1],$attendance[0]);
			$check_date=$s_date['exact_date_end'];

			if(strtotime($check_date) > strtotime($s_end_date)){
				$month_year=date('Y-m',strtotime("-1 month",strtotime($s_end_date)));
				$check_month_paid_or_not=$this->Payroll_model->check_already_paid_or_not_not_hold($emp_id,$month_year);
				if($check_month_paid_or_not==2){
					$attendance=explode('-',date('Y-m',strtotime("-1 month",strtotime($s_end_date))));
				}else{
					$month_year=date('Y-m',strtotime($s_end_date));
					$attendance=explode('-',date('Y-m',strtotime($s_end_date)));
				}
				$days_count_per_month=cal_days_in_month(CAL_GREGORIAN,$attendance[1],$attendance[0]);
				$first_date_of_the_month=$month_year.'-01';
				$last_date_of_the_month=$month_year.'-'.$days_count_per_month;
			}else{
				$month_year=date('Y-m',strtotime($s_end_date));
				$attendance=explode('-',date('Y-m',strtotime($s_end_date)));
				$days_count_per_month=cal_days_in_month(CAL_GREGORIAN,$attendance[1],$attendance[0]);
				$first_date_of_the_month=date('Y-m-01',strtotime($leaving_date));
				$first_date_of_the_month=$month_year.'-01';
				$last_date_of_the_month=$month_year.'-'.$days_count_per_month;
			}

		}
		else{
			$date_of_leaving='N/A';
			$no_of_days_worked='0';
			$days_count_per_month='0';
		}
		$p_month_date=salary_start_end_date($month_year);
		$p_month_start_date=$p_month_date['exact_date_start'];
		$p_month_end_date=date('Y-m-d',strtotime("-1 Day",strtotime($first_date_of_the_month)));

		$second_month_last_date=$s_end_date;
		$second_month_first_date='';
		if(strtotime($last_date_of_the_month) < strtotime($s_end_date)){
			$s_end_date=$last_date_of_the_month;
			$second_month_first_date=date('Y-m-d',strtotime("+1 Day",strtotime($last_date_of_the_month)));
		}
		$payroll_current_data=$this->payslip_list_data($emp_id,$month_year,$first_date_of_the_month,$s_end_date,1);

		$driver_delivery_details=[];
		$days_count_per_curmonth=0;

		if($second_month_first_date!=''){
			$post_month_year=date('Y-m',strtotime("+1 month",strtotime($month_year)));
			$payroll_current_second_data=$this->payslip_list_data($emp_id,$post_month_year,$second_month_first_date,$second_month_last_date,1); // Feb
			$sec_attendance=explode('-',date('Y-m',strtotime($second_month_last_date)));
			$days_count_per_curmonth=cal_days_in_month(CAL_GREGORIAN,$sec_attendance[1],$sec_attendance[0]);
			if($department_name=='TD' && $visa_name!='DMCC'){
				$driver_delivery_count=get_driver_report($emp_id,$second_month_first_date,$second_month_last_date,'P');
				if($driver_delivery_count){
					$assigned_count=$driver_delivery_count['assigned_count'];
					$delivery_count=$driver_delivery_count['delivery_count'];
					$cancellation_count=$driver_delivery_count['cancellation_count'];
					$cancellation_rate=$driver_delivery_count['cancellation_rate'];
					if(($monthly_target_count<=$delivery_count) &&($cancellation_rate<=$order_cancellation_percentage)){
						if($payroll_current_data['agreed_bonus']!=null){
							$agreed_bonus=$payroll_current_data['agreed_bonus'];
						}else{
							$agreed_bonus=0;
						}
					}else{
						$agreed_bonus=0;
					}
					$driver_delivery_details=json_encode(array('assigned_count'=>$assigned_count,'delivery_count'=>$delivery_count,'cancellation_count'=>$cancellation_count,'cancellation_rate'=>$cancellation_rate,'monthly_target_count'=>$monthly_target_count,'order_cancellation_percentage'=>$order_cancellation_percentage,'agreed_bonus'=>$agreed_bonus));
				}

			}
		}else{
			$payroll_current_second_data=[];
		}

		/*Pre Month*/
		$pre_month_year=date('Y-m',strtotime("-1 month",strtotime($month_year)));
		if($department_name=='TD' && $visa_name!='DMCC'){
			$payroll_prev_data=[];
		}else{
			$payroll_prev_data=$this->payslip_list_data($emp_id,$pre_month_year,$p_month_start_date,$p_month_end_date,1); // Feb
		}

		//print_r($payroll_prev_data);
		//print_r($payroll_current_second_data);
		//print_r($payroll_current_data);

		/*Pre Month*/

		$leave_salary_current_paid=json_decode($payroll_current_data['leave_salary_paid']);
		$leave_salary_current_second_paid=@json_decode($payroll_current_second_data['leave_salary_paid']);
		$leave_salary_pre_paid=json_decode($payroll_prev_data['leave_salary_paid']);


		$c_m_leave_salary=json_decode($payroll_current_data['leave_salary']);
		$p_m_leave_salary=json_decode($leave_salary_pre_paid['leave_salary']);
		$a_m_leave_array=json_decode($payroll_current_second_data['leave_salary']);

		$c_m_late_work_array=json_decode($payroll_current_data['late_work_array']);
		$p_m_late_work_array=json_decode($payroll_current_second_data['late_work_array']);
		$a_m_late_work_array=json_decode($payroll_prev_data['late_work_array']);


		$count_of_leave_id_array=[];
		if($leave_salary_current_paid){
			foreach($leave_salary_current_paid as $count_leave_c_m){
				foreach($count_leave_c_m as $key_c_m=>$value_c_m){
					$count_of_leave_id_array[$key_c_m]=array('leave_id'=>$value_c_m->leave_id,'leave_status_code'=>$value_c_m->leave_status_code,'attendance_date'=>$value_c_m->attendance_date,'leave_type_id'=>$value_c_m->leave_type_id,'type_name'=>$value_c_m->type_name);
				}
			}
		}
		if($leave_salary_pre_paid){
			foreach($leave_salary_pre_paid as $p_m_leave){
				foreach($p_m_leave as $key_l=>$value_l){
					$count_of_leave_id_array[$value_l->attendance_date]=array('leave_id'=>$value_l->leave_id,'leave_status_code'=>$value_l->leave_status_code,'attendance_date'=>$value_l->attendance_date,'leave_type_id'=>$value_l->leave_type_id,'type_name'=>$value_l->type_name);
				}

			}
		}


		if($leave_salary_current_second_paid){
			foreach($leave_salary_current_second_paid as $count_leave_a_m){
				foreach($count_leave_a_m as $key_a_m=>$value_a_m){
					$count_of_leave_id_array[$key_a_m]=array('leave_id'=>$value_a_m->leave_id,'leave_status_code'=>$value_a_m->leave_status_code,'attendance_date'=>$value_a_m->attendance_date,'leave_type_id'=>$value_a_m->leave_type_id,'type_name'=>$value_a_m->type_name);
				}
			}
		}



		$leave_salary_paid_arr=json_encode($count_of_leave_id_array);

		///
		$late_hours_value=0;
		$late_hours_amount=0;
		if($p_m_late_work_array){
			foreach($p_m_late_work_array as $p_m_late_array){
				$late_hours_value+=$p_m_late_array->time_late;
				$late_hours_amount+=$p_m_late_array->time_late_amount;
			}
		}
		if($c_m_late_work_array){
			foreach($c_m_late_work_array as $c_m_late_array){
				$late_hours_value+=$c_m_late_array->time_late;
				$late_hours_amount+=$c_m_late_array->time_late_amount;
			}
		}
		if($a_m_late_work_array){
			foreach($a_m_late_work_array as $a_m_late_array){
				$late_hours_value+=$a_m_late_array->time_late;
				$late_hours_amount+=$a_m_late_array->time_late_amount;
			}
		}

		$late_working_hours_v=secondsToTime($late_hours_value);
		$late_working_hours=$late_working_hours_v[h].':'.$late_working_hours_v[m];

		//
		if($department_name=='TD' && $visa_name!='DMCC'){
			$tally_pre_month_working_hours=0;
			$tally_pre_month_salary=0;
		}else{

			$required_pre_working_hours=$this->remaining_days_of_monthwithsalary($p_month_start_date,$p_month_end_date,$shift_hours,$payroll_current_data['country_id'],$emp_id,$payroll_prev_data['required_working_hours']);
			$tally_pre_month_working_hours=$required_pre_working_hours['count_of_remaining_hours']-$payroll_prev_data['total_working_hours'];
			$tally_pre_month_salary=$required_pre_working_hours['salary_count']-$payroll_prev_data['total_salary'];
		}


		if(in_array($emp_id,$exceptional_employees)){
			$total_working_hours=$payroll_current_data['total_working_hours']-$tally_pre_month_working_hours;
			$total_salary=$payroll_current_data['total_salary']-$tally_pre_month_salary;
		}else{

			if($late_hours_value <= GRACE_PERIOD_MINUTES && $grace_eligible==1){
				$total_working_hours=($payroll_current_data['total_working_hours']-$tally_pre_month_working_hours)+decimalHourswithoutround($late_working_hours);
				$total_salary=($payroll_current_data['total_salary']-$tally_pre_month_salary)+$late_hours_amount;
			}else{
				$total_working_hours=($payroll_current_data['total_working_hours']-$tally_pre_month_working_hours);
				$total_salary=$payroll_current_data['total_salary']-$tally_pre_month_salary;
			}

		}


		$required_working_hours=$this->remaining_days_of_monthwithsalary($first_date_of_the_month,$s_end_date,$shift_hours,$payroll_current_data['country_id'],$emp_id,$payroll_current_data['required_working_hours']);

		if($second_month_first_date!=''){
			$required_post_working_hours=$this->remaining_days_of_monthwithsalary($second_month_first_date,$second_month_last_date,$shift_hours,$payroll_current_second_data['country_id'],$emp_id,$payroll_current_second_data['required_working_hours']);
			$required_post_working_hours=$required_post_working_hours['required_working_hours'];
			$total_working_hours_second=($payroll_current_second_data['total_working_hours']);
			$total_salary_second=($payroll_current_second_data['total_salary']);
		}else{
			$required_post_working_hours=[];
			$required_post_working_hours=0;
			$total_working_hours_second=0;
			$total_salary_second=0;
		}

		$required_working_hours=$payroll_current_data['required_working_hours'];//change it
		$total_days=($total_working_hours/$shift_hours)+($total_working_hours_second/$shift_hours);
		$net_total_years_worked=($no_of_days_worked-$count_of_leave_status)/365;

		$ot_hours_amount_curr=json_decode($payroll_current_data['ot_hours_amount']);
		$ot_hours_amount_prev=json_decode($payroll_prev_data['ot_hours_amount']);
		$ot_hours_amount_curr_second=@json_decode($payroll_current_second_data['ot_hours_amount']);


		$ot_day_rate1=$payroll_current_data['ot_day_rate'];
		$ot_night_rate1=$payroll_current_data['ot_night_rate'];
		$ot_holiday_rate1=$payroll_current_data['ot_holiday_rate'];

		$ot_day_rate=@$ot_hours_amount_curr->ot_day_amount+@$ot_hours_amount_prev->ot_day_amount+@$ot_hours_amount_curr_second->ot_day_amount;
		$ot_night_rate=@$ot_hours_amount_curr->ot_night_amount+@$ot_hours_amount_prev->ot_night_amount+@$ot_hours_amount_curr_second->ot_night_amount;
		$ot_holiday_rate=@$ot_hours_amount_curr->ot_holiday_amount+@$ot_hours_amount_prev->ot_holiday_amount+@$ot_hours_amount_curr_second->ot_holiday_amount;

		$ot_day_hour=decimalHours($ot_hours_amount_curr->ot_day_hours)+decimalHours($ot_hours_amount_prev->ot_day_hours)+@decimalHours($ot_hours_amount_curr_second->ot_day_hours);
		$ot_night_hour=decimalHours($ot_hours_amount_curr->ot_night_hours)+decimalHours($ot_hours_amount_prev->ot_night_hours)+@decimalHours($ot_hours_amount_curr_second->ot_night_hours);
		$ot_holiday_hour=decimalHours($ot_hours_amount_curr->ot_holiday_hours)+decimalHours($ot_hours_amount_prev->ot_holiday_hours)+@decimalHours($ot_hours_amount_curr_second->ot_holiday_hours);


		$ot_hours_array=json_encode(array('ot_day_hours'=>decimalHours_reversewith_symbol($ot_day_hour),'ot_night_hours'=>decimalHours_reversewith_symbol($ot_night_hour),'ot_holiday_hours'=>decimalHours_reversewith_symbol($ot_holiday_hour),'ot_day_amount'=>$ot_day_rate,'ot_night_amount'=>$ot_night_rate,'ot_holiday_amount'=>$ot_holiday_rate));
		/// This is for Annual Leave

		if(!in_array($employee_details[0]->visa_id,$eligible_visa_under)){
			$total_leave_accrued=0;
			$leave_availed=0;
			$balance_leave=0;
		}else{
			$annual_leave_balance=annual_leave_balance($emp_id,$joining_date,$finished_date);
			$total_leave_accrued=$annual_leave_balance['total_leave_accrued'];
			$leave_availed=$annual_leave_balance['leave_availed'];
			$balance_leave=$annual_leave_balance['balance_leave'];
		}
		if($employee_details[0]->contract_date!=''){
			$contract_date=date('d F Y',strtotime($employee_details[0]->contract_date));
		}else{
			$contract_date='N/A';
		}
		//////

		$html='';
		$html.='<div class="panel panel-flat" id="dvContainer"><div class="panel-heading text-center"><h5 class="panel-title">					 
		<strong>Final Settlement Computation</strong></h5><div class="pull-right mr-10">Date: '.date('d F Y',strtotime(TODAY_DATE)).'</div>	</div>';
		$html.='<div class="panel-body">';

		$html.='<div class="col-lg-6 table-responsive" style="min-height: 35em;"><table class="table table-md"><tbody>
		<tr class="bg-slate-600 text-center"><td colspan="3">Basic Details</td></tr>
		<tr class="success"><td>Employee Code</td><td>:</td><td>'.$employee_details[0]->employee_code.'</td></tr>	
		<tr class=""><td>Employee Name</td><td>:</td><td>'.change_fletter_caps($employee_details[0]->first_name.' '.$employee_details[0]->middle_name.' '.$employee_details[0]->last_name).'</td></tr>	
		<tr class="danger"><td>Designation</td><td>:</td><td>'.$employee_details[0]->designation_name.'</td></tr>
		<tr class=""><td>Entity/Agency</td><td>:</td><td>'.$employee_details[0]->visa_type.'</td></tr>
		<tr class="warning"><td>Date of Join</td><td>:</td><td>'.date('d F Y',strtotime($employee_details[0]->date_of_joining)).'</td></tr>
		<tr class=""><td>Contract Date</td><td>:</td><td>'.$contract_date.'</td></tr>
		<tr class="info"><td>Last Working Day</td><td>:</td><td>'.date('d F Y',strtotime($second_month_last_date)).'</td></tr>
		<tr class=""><td>Type of Seperation</td><td>:</td><td>'.$exit_type.'</td></tr>		
		';
		$html.='</tbody></table></div>';


		$html.='<div class="col-lg-6 table-responsive" style="min-height: 35em;"><table class="table table-md"><tbody>
		<tr class="bg-slate-600 text-center"><td colspan="3">Years of Work & Leave Balance</td></tr>	
		<tr class=""><td>Number of Days worked</td><td>:</td><td>'.$no_of_days_worked.'</td></tr>	
		<tr class="danger"><td>Number of Years worked</td><td>:</td><td>'.round($no_of_days_worked/365,2).'</td></tr>
		<tr class=""><td>No of Absence</td><td>:</td><td>'.$count_of_leave_status.'</td></tr>
		<tr class="warning"><td>Net Total Years worked</td><td>:</td><td>'.round($net_total_years_worked,2).'</td></tr>
		<tr class=""><td>Total Leave Accrued</td><td>:</td><td>'.$total_leave_accrued.'</td></tr>
		<tr class="info"><td>Leave Availed</td><td>:</td><td>'.$leave_availed.'</td></tr>
		<tr class=""><td>Balance Leave </td><td>:</td><td>'.$balance_leave.'</td></tr>		
		';
		$html.='</tbody></table></div>';

		$basic_salary=change_num_format($employee_details[0]->basic_salary);
		if($employee_details[0]->house_rent_allowance!=''){
			$house_rent_allowance=change_num_format($employee_details[0]->house_rent_allowance);
		}else{$house_rent_allowance='-';}
		if($employee_details[0]->travelling_allowance!=''){
			$travelling_allowance=change_num_format($employee_details[0]->travelling_allowance);
		}else{$travelling_allowance='-';}
		if($employee_details[0]->other_allowance!=''){
			$other_allowance=change_num_format($employee_details[0]->other_allowance);
		}else{$other_allowance='-';}
		if($employee_details[0]->food_allowance!=''){
			$food_allowance=change_num_format($employee_details[0]->food_allowance);
		}else{$food_allowance='-';}
		if($employee_details[0]->additional_benefits!=''){
			$additional_benefits=change_num_format($employee_details[0]->additional_benefits);
		}else{$additional_benefits='-';}
		if($employee_details[0]->bonus!=''){
			$bonus=@change_num_format($employee_details[0]->bonus);
		}else{$bonus='-';}
		if($employee_details[0]->agreed_bonus!=''){
			$target_bonus=@change_num_format($employee_details[0]->agreed_bonus);
		}else{$target_bonus='-';}
		$gross_salary=change_num_format($employee_details[0]->gross_salary);

		$html.='<div class="col-lg-6 table-responsive" style="min-height: 35em;"><table class="table table-md"><tbody>
		<tr class="bg-slate-600 text-center"><td colspan="3">Monthly Salary</td></tr>	
		<tr class=""><td>Basic Salary</td><td>:</td><td>'.$basic_salary.'</td></tr>
        <tr class="danger"><td>House Rent Allowance</td><td>:</td><td>'.$house_rent_allowance.'</td></tr>
		<tr class=""><td>Transp. Allowance</td><td>:</td><td>'.$travelling_allowance.'</td></tr>
		<tr class="warning"><td>Food Allowance</td><td>:</td><td>'.$food_allowance.'</td></tr>
		<tr class=""><td>Other Allowance</td><td>:</td><td>'.$other_allowance.'</td></tr>
		<tr class="info"><td>Additional Benefit</td><td>:</td><td>'.$additional_benefits.'</td></tr>
		<tr class=""><td>'.$this->lang->line('xin_bonus').'</td><td>:</td><td>'.$bonus.'</td></tr>
		<tr class=""><td>'.$this->lang->line('xin_agreed_bonus').'</td><td>:</td><td>'.$target_bonus.'</td></tr>
		<tr class="success"><td>Total Salary </td><td>:</td><td>'.$gross_salary.'</td></tr>			
	
		';
		$html.='</tbody></table></div>';

		/*Current Rate*/
		$rate_per_hour_contract_bonus_cur=($employee_details[0]->gross_salary/$payroll_current_data['required_working_hours']);
		$rate_per_hour_contract_only_cur=(($employee_details[0]->gross_salary-$employee_details[0]->bonus)/$payroll_current_data['required_working_hours']);
		$rate_per_hour_basic_only_cur=($employee_details[0]->basic_salary/$payroll_current_data['required_working_hours']);
		/*Current Rate*/

		if($second_month_first_date!=''){
			$rate_per_hour_contract_bonus_second=($employee_details[0]->gross_salary/$payroll_current_second_data['required_working_hours']);
			$rate_per_hour_contract_only_second=(($employee_details[0]->gross_salary-$employee_details[0]->bonus)/$payroll_current_second_data['required_working_hours']);
			$rate_per_hour_basic_only_second=($employee_details[0]->basic_salary/$payroll_current_second_data['required_working_hours']);
			$working_hours_for_the_month=$payroll_current_second_data['required_working_hours'];
			$total_working_hours_for_the_month=$payroll_current_second_data['total_working_hours']+$total_working_hours;

		}else{

			$rate_per_hour_contract_bonus_second=($employee_details[0]->gross_salary/$payroll_current_data['required_working_hours']);
			$rate_per_hour_contract_only_second=(($employee_details[0]->gross_salary-$employee_details[0]->bonus)/$payroll_current_data['required_working_hours']);
			$rate_per_hour_basic_only_second=($employee_details[0]->basic_salary/$payroll_current_data['required_working_hours']);
			$working_hours_for_the_month=$payroll_current_data['required_working_hours'];
			$total_working_hours_for_the_month=$total_working_hours;
		}



		$leave_salary=round((($employee_details[0]->basic_salary*12)/365)*$balance_leave,2);



		$salary_of_the_month=$total_salary+$total_salary_second;




		$ot_day_rate=change_num_format($ot_day_rate);
		$ot_night_rate=change_num_format($ot_night_rate);
		$ot_holiday_rate=change_num_format($ot_holiday_rate);


		$html.='<div class="col-lg-6 table-responsive" style="min-height: 35em;"><table class="table table-md"><tbody>
		<tr class="bg-slate-600 text-center"><td colspan="3">Working Hours & Over Time</td></tr>	
		<tr class="danger"><td>Normal Working Hours</td><td>:</td><td>'.$shift_hours.'</td></tr>	
		<tr class=""><td>Working Hours for the month</td><td>:</td><td>'.$working_hours_for_the_month.'</td></tr>
		<tr class="warning"><td>Actual Working Hours</td><td>:</td><td>'.round($total_working_hours_for_the_month,2).'</td></tr>
		<tr class=""><td>OT Hours @ 1.25</td><td>:</td><td>'.$ot_day_hour.'</td></tr>
		<tr class="success"><td>OT Hours @ 1.5</td><td>:</td><td>'.$ot_night_hour.'</td></tr>
		<tr class=""><td>OT Hours @ PH</td><td>:</td><td>'.$ot_holiday_hour.'</td></tr>	
		';
		$html.='</tbody></table></div>';
		$find_external_perp_adjustments = $this->Payroll_model->find_ext_adjustments($emp_id,$first_date_of_the_month,$s_end_date,'external_adjustments','Perpetual');
		$find_external_nonperp_adjustments = $this->Payroll_model->find_ext_adjustments($emp_id,$first_date_of_the_month,$s_end_date,'external_adjustments','Non-Perpetual');
		$find_internal_add_adjustments = $this->Payroll_model->find_adjustments_anystatus($emp_id,$first_date_of_the_month,$s_end_date,'internal_adjustments','Addition');
		$find_internal_ded_adjustments = $this->Payroll_model->find_adjustments_anystatus($emp_id,$first_date_of_the_month,$s_end_date,'internal_adjustments','Deduction');
		$adds=0;
		$adds_html='';
		$deds=0;
		$deds_html='';
		$perp=0;
		$perp_html='';
		if(in_array($employee_details[0]->visa_id,$eligible_visa_under)){
			/*Auto calculation Addition & deduction start*/
			if($exit_type=='Resignation45 ( + )'){ //45 Days Salary Deduction
				$other_adjustment_p=$this->Payroll_model->read_salary_type_name('Deduction');
				$other_adjustment_p_type_id=$other_adjustment_p[0]->type_id;

				$other_adjustment_c_type_id=$this->Payroll_model->read_salary_child_type_name($other_adjustment_p_type_id,'Others');
				$other_adjustment_c_type_id=$other_adjustment_c_type_id[0]->type_id;

				$_45_days_salary=(($employee_details[0]->gross_salary/30)*45);
				$deds+=$_45_days_salary;
				$deds_html.='<tr class=""><td>'.$exit_type.'</td><td>45 Days Notice</td><td>'.$_45_days_salary.'</td></tr>';
				$html.='<input type="hidden" name="parent_type_name[]" value="Deduction">
			<input type="hidden" name="child_type_name[]" value="'.$exit_type.'">
			<input type="hidden" name="adjustment_id[]" value="">
            <input type="hidden" name="parent_type[]" value="'.$other_adjustment_p_type_id.'">
			<input type="hidden" name="child_type[]"  value="'.$other_adjustment_c_type_id.'">
			<input type="hidden" name="amount[]"  value="'.$_45_days_salary.'">
			<input type="hidden" name="salary_comments[]"  value="45 Days Notice">';
			}
			else if($exit_type=='Termination ( 3 Months + )'){ //90 Days Salary Addition
				$_90_days_salary=(($employee_details[0]->gross_salary/30)*90);
				$adds+=$_90_days_salary;
				$adds_html.='<tr class=""><td>'.$exit_type.'</td><td>90 Days Paid</td><td>'.$_90_days_salary.'</td></tr>';
				$html.='<input type="hidden" name="parent_type_name[]" value="Addition">
			<input type="hidden" name="child_type_name[]" value="'.$exit_type.'">
			<input type="hidden" name="adjustment_id[]" value="">
            <input type="hidden" name="parent_type[]" value="'.$other_adjustment_p_type_id.'">
			<input type="hidden" name="child_type[]"  value="'.$other_adjustment_c_type_id.'">
			<input type="hidden" name="amount[]"  value="'.$_90_days_salary.'">
			<input type="hidden" name="salary_comments[]"  value="90 Days Paid">';


			}
		}
		if($second_month_first_date!=''){
			$date1=date_create($joining_date);
			$date2=date_create($second_month_last_date);
			$diff=date_diff($date1,$date2);
			$val=($diff->days/365);
		}else{
			$date1=date_create($joining_date);
			$date2=date_create($s_end_date);
			$diff=date_diff($date1,$date2);
			$val=($diff->days/365);
		}


		if(in_array($employee_details[0]->visa_id,$eligible_visa_under)){
			$gratutity_pay=$this->Payroll_model->read_salary_type_name('Gratuity Payable');
			if(($val > 1) && (($exit_type=='Termination ( 3 Months + )') || ($exit_type=='Termination ( 3 Months - )'))){
				$calculation=round((((($employee_details[0]->basic_salary*12)/365)*21)*$val),2);
				$adds+=$calculation;

				$adds_html.='<tr class=""><td>Gratuity Payable</td><td></td><td>'.$calculation.'</td></tr>';

				$html.='<input type="hidden" name="parent_type_name[]" value="Addition">
			<input type="hidden" name="child_type_name[]" value="Gratuity Payable">
			<input type="hidden" name="adjustment_id[]" value="">
            <input type="hidden" name="parent_type[]" value="'.$gratutity_pay[0]->type_parent.'">
			<input type="hidden" name="child_type[]"  value="'.$gratutity_pay[0]->type_id.'">
			<input type="hidden" name="amount[]"  value="'.$calculation.'">
			<input type="hidden" name="salary_comments[]"  value="Gratuity Payable">';


			}else if(($val > 1) && (($exit_type=='Resignation By Mutual Agreement ( 45 Days - )') || ($exit_type=='Resignation45 ( + )')) && ($employee_contracts=='Unlimited Contract')){

				if($val > 1 && $val < 3){
					$calculation=round(((((($employee_details[0]->basic_salary*12)/365)*21)*$val)/3),2);
				}else if($val >=3 && $val < 5){
					$calculation=round((((((($employee_details[0]->basic_salary*12)/365)*21)*$val)/3)*2),2);
				}else if($val >= 5){
					$calculation=round((((($employee_details[0]->basic_salary*12)/365)*21)*$val),2);
				}else{
					$calculation=0;
				}




				$adds+=$calculation;

				$adds_html.='<tr class=""><td>Gratuity Payable</td><td></td><td>'.$calculation.'</td></tr>';

				$html.='<input type="hidden" name="parent_type_name[]" value="Addition">
			<input type="hidden" name="child_type_name[]" value="Gratuity Payable">
			<input type="hidden" name="adjustment_id[]" value="">
            <input type="hidden" name="parent_type[]" value="'.$gratutity_pay[0]->type_parent.'">
			<input type="hidden" name="child_type[]"  value="'.$gratutity_pay[0]->type_id.'">
			<input type="hidden" name="amount[]"  value="'.$calculation.'">
			<input type="hidden" name="salary_comments[]"  value="Gratuity Payable">';


			}else{

				$adds+=0;

				$adds_html.='<tr class=""><td>Gratuity Payable</td><td></td><td>0.00</td></tr>';

				$html.='<input type="hidden" name="parent_type_name[]" value="Addition">
			<input type="hidden" name="child_type_name[]" value="Gratuity Payable">
			<input type="hidden" name="adjustment_id[]" value="">
            <input type="hidden" name="parent_type[]" value="'.$gratutity_pay[0]->type_parent.'">
			<input type="hidden" name="child_type[]"  value="'.$gratutity_pay[0]->type_id.'">
			<input type="hidden" name="amount[]"  value="0">
			<input type="hidden" name="salary_comments[]"  value="Gratuity Payable">';


			}
		}



		if($agreed_bonus!=0){
			$driver_incentives=$this->Payroll_model->read_salary_type_name('Incentives');
			$adds+=$agreed_bonus;

			$adds_html.='<tr class=""><td>Target Bonus(Incentives)</td><td></td><td>'.$agreed_bonus.'</td></tr>';

			$html.='<input type="hidden" name="parent_type_name[]" value="Addition">
			<input type="hidden" name="child_type_name[]" value="Incentives">
			<input type="hidden" name="adjustment_id[]" value="">
            <input type="hidden" name="parent_type[]" value="'.$driver_incentives[0]->type_parent.'">
			<input type="hidden" name="child_type[]"  value="'.$driver_incentives[0]->type_id.'">
			<input type="hidden" name="amount[]"  value="'.$agreed_bonus.'">
			<input type="hidden" name="salary_comments[]"  value="Target Bonus(Incentives)">';


		}


		/*Auto calculation Addition & deduction start*/



		if($find_internal_add_adjustments){
			foreach($find_internal_add_adjustments as $int_add_adjustments){
				if($int_add_adjustments->parent_type_name=='Addition'){
					$adds+=$int_add_adjustments->adjustment_amount;
					$adds_html.='<tr class=""><td>'.$int_add_adjustments->child_type_name.'</td><td>'.$int_add_adjustments->comments.'</td><td>'.$int_add_adjustments->adjustment_amount.'</td></tr>';
					$html.='<input type="hidden" name="parent_type_name[]" value="'.$int_add_adjustments->parent_type_name.'">
			<input type="hidden" name="child_type_name[]" value="'.$int_add_adjustments->child_type_name.'"><input type="hidden" name="adjustment_id[]" value="'.$int_add_adjustments->adjustment_id.'">
            <input type="hidden" name="parent_type[]" value="'.$int_add_adjustments->adjustment_type.'">
			<input type="hidden" name="child_type[]"  value="'.$int_add_adjustments->adjustment_name.'">
			<input type="hidden" name="amount[]"  value="'.$int_add_adjustments->adjustment_amount.'">
			<input type="hidden" name="salary_comments[]"  value="'.$int_add_adjustments->comments.'">';
				}
			}
		}

		if($find_internal_ded_adjustments){
			foreach($find_internal_ded_adjustments as $int_ded_adjustments){
				if($int_ded_adjustments->parent_type_name=='Deduction'){
					$deds+=$int_ded_adjustments->adjustment_amount;
					$deds_html.='<tr class=""><td>'.$int_ded_adjustments->child_type_name.'</td><td>'.$int_ded_adjustments->comments.'</td><td>'.$int_ded_adjustments->adjustment_amount.'</td></tr>';

					$html.='<input type="hidden" name="parent_type_name[]" value="'.$int_ded_adjustments->parent_type_name.'">
			<input type="hidden" name="child_type_name[]" value="'.$int_ded_adjustments->child_type_name.'">
			<input type="hidden" name="adjustment_id[]" value="'.$int_ded_adjustments->adjustment_id.'">
            <input type="hidden" name="parent_type[]" value="'.$int_ded_adjustments->adjustment_type.'">
			<input type="hidden" name="child_type[]"  value="'.$int_ded_adjustments->adjustment_name.'">
			<input type="hidden" name="amount[]"  value="'.$int_ded_adjustments->adjustment_amount.'">
			<input type="hidden" name="salary_comments[]"  value="'.$int_ded_adjustments->comments.'">';
				}
			}
		}



		if($find_external_perp_adjustments){
			foreach($find_external_perp_adjustments as $ext_perp_adjustments){

				$ext_perp_adjustments_amount=$ext_perp_adjustments->adjustment_amount;

				if($ext_perp_adjustments->compute_amount==0){
					if($ext_perp_adjustments->child_type_name=='Agency Fees'){
						// DOJ
						if((strtotime($second_month_first_date) <= strtotime($leaving_date)) && (strtotime($second_month_last_date) >= strtotime($leaving_date))){
							$date1=new DateTime($second_month_first_date);
							$date2=new DateTime($leaving_date);
							$interval_date=$date2->diff($date1);
							$no_of_days_worked=$interval_date->days+1;
							$computed_perp_amount=($ext_perp_adjustments_amount/$days_count_per_curmonth)*$no_of_days_worked;

						}else{
							$computed_perp_amount=$ext_perp_adjustments_amount;
						}
					}else{
						$computed_perp_amount=$ext_perp_adjustments_amount;
					}
				}else{
					//
					if($ext_perp_adjustments->child_type_name=='Agency Fees'){
						// DOJ
						if((strtotime($second_month_first_date) <= strtotime($leaving_date)) && (strtotime($second_month_last_date) >= strtotime($leaving_date))){
							$computed_perp_amount=(($ext_perp_adjustments_amount/$working_hours_for_the_month)*$total_working_hours_for_the_month);
						}else{
							$computed_perp_amount=(($ext_perp_adjustments_amount/$working_hours_for_the_month)*$total_working_hours_for_the_month);
						}
					}else{
						$computed_perp_amount=(($ext_perp_adjustments_amount/$working_hours_for_the_month)*$total_working_hours_for_the_month);	}
				}
				$perp+=$computed_perp_amount;

				$perp_html.='<tr class=""><td>'.$ext_perp_adjustments->child_type_name.'</td><td>'.$ext_perp_adjustments->comments.'</td><td>'.round($computed_perp_amount,2).'</td></tr>';


				$html.='<input type="hidden" name="parent_type_name[]" value="'.$ext_perp_adjustments->parent_type_name.'">
			<input type="hidden" name="child_type_name[]" value="'.$ext_perp_adjustments->child_type_name.'">
			<input type="hidden" name="adjustment_id[]" value="'.$ext_perp_adjustments->adjustment_id.'">
            <input type="hidden" name="parent_type[]" value="'.$ext_perp_adjustments->adjustment_type.'">
			<input type="hidden" name="child_type[]"  value="'.$ext_perp_adjustments->adjustment_name.'">
			<input type="hidden" name="amount[]"  value="'.$computed_perp_amount.'">
			<input type="hidden" name="salary_comments[]"  value="'.$ext_perp_adjustments->comments.'">';
			}
		}

		if($find_external_nonperp_adjustments){
			foreach($find_external_nonperp_adjustments as $ext_nonperp_adjustments){

				$ext_nonperp_adjustments_amount=$ext_nonperp_adjustments->adjustment_amount;

				if($ext_nonperp_adjustments->compute_amount==0){
					$computed_nonperp_amount=$ext_nonperp_adjustments_amount;
				}else{
					$computed_nonperp_amount=(($ext_nonperp_adjustments_amount/$working_hours_for_the_month)*$total_working_hours_for_the_month);
				}
				$perp+=$computed_nonperp_amount;

				$perp_html.='<tr class=""><td>'.$ext_nonperp_adjustments->child_type_name.'</td><td>'.$ext_nonperp_adjustments->comments.'</td><td>'.round($computed_nonperp_amount,2).'</td></tr>';


				$html.='<input type="hidden" name="parent_type_name[]" value="'.$ext_nonperp_adjustments->parent_type_name.'">
			<input type="hidden" name="child_type_name[]" value="'.$ext_nonperp_adjustments->child_type_name.'">
			<input type="hidden" name="adjustment_id[]" value="'.$ext_nonperp_adjustments->adjustment_id.'">
            <input type="hidden" name="parent_type[]" value="'.$ext_nonperp_adjustments->adjustment_type.'">
			<input type="hidden" name="child_type[]"  value="'.$ext_nonperp_adjustments->adjustment_name.'">
			<input type="hidden" name="amount[]"  value="'.$computed_nonperp_amount.'">
			<input type="hidden" name="salary_comments[]"  value="'.$ext_nonperp_adjustments->comments.'">';

			}
		}



		if(($exit_type!='Termination ( Under PROB - Article 120 )') && ($exit_type!='Termination ( Article 120 )')){
			$leave_salary=$leave_salary;
		}else{
			$leave_salary=0;
		}



		$total_earnings=$adds+$salary_of_the_month+$ot_day_rate+$ot_night_rate+$ot_holiday_rate+$leave_salary;




		$leave_salary_arr=json_encode(array(array('Final-Leave-settlement'=>$leave_salary)));



		$html.='<div class="col-lg-12 table-responsive" style="min-height: 35em;"><table class="table table-md"><tbody>
		<tr class="bg-slate-600"><td>Elements</td><td>Days</td><td>Amount</td></tr>	
		<tr class="success"><td colspan="3">Earnings</td></tr>	
		<tr class=""><td>Current Month Salary - '.date('d F Y',strtotime($first_date_of_the_month)).' - '.date('d F Y',strtotime($second_month_last_date)).'</td><td>'.change_num_format($total_days).'</td><td>'.change_num_format($salary_of_the_month).'</td></tr>
		<tr class=""><td>Leave Salary</td><td>'.$balance_leave.'</td><td>'.$leave_salary.'</td></tr>
		<tr class=""><td>OT  Amount @ 1.25</td><td></td><td>'.$ot_day_rate.'</td></tr>
		<tr class=""><td>OT Amount @ 1.5</td><td></td><td>'.$ot_night_rate.'</td></tr>
		<tr class=""><td>OT  Amount @ PH</td><td></td><td>'.$ot_holiday_rate.'</td></tr>'.$adds_html.'
		<tr class=""><td>Total Earnings</td><td></td><td>'.change_num_format($total_earnings).'</td></tr>
		<tr class="danger"><td colspan="3" text-center>Deductions</td></tr>
		'.$deds_html.'
		<tr class=""><td>Total Deductions</td><td></td><td>'.change_num_format($deds).'</td></tr>
		'.$perp_html.'
		<tr class="warning"><td>Net Payable</td><td></td><td>'.change_num_format(($total_earnings+$perp)-$deds).'</td></tr>
		<tr class="warning"><td>Payment Amount to Employee</td><td></td><td>'.change_num_format(($total_earnings)-$deds).'</td></tr>';
		$html.='</tbody></table></div>';

		$html.='</div>';
		$html.='</div>';

		if($second_month_first_date!=''){
			$rate_contract_bonus=$rate_per_hour_contract_bonus_second;
			$rate_contract_only=$rate_per_hour_contract_only_second;
			$rate_contract_basic=$rate_per_hour_basic_only_second;
			$payment_date=$post_month_year;
			$ot_day_rate1=$payroll_current_second_data['ot_day_rate'];
			$ot_night_rate1=$payroll_current_second_data['ot_night_rate'];
			$ot_holiday_rate1=$payroll_current_second_data['ot_holiday_rate'];
		}else{
			$rate_contract_bonus=$rate_per_hour_contract_bonus_second;
			$rate_contract_only=$rate_per_hour_contract_only_second;
			$rate_contract_basic=$rate_per_hour_basic_only_second;
			$payment_date=$month_year;
			$ot_day_rate1=$ot_day_rate1;
			$ot_night_rate1=$ot_night_rate1;
			$ot_holiday_rate1=$ot_holiday_rate1;
		}
		$html.='<input type="hidden" name="employee_code" value="'.$employee_details[0]->employee_code.'">
				<input type="hidden" name="employee_name" value="'.change_fletter_caps($employee_details[0]->first_name.' '.$employee_details[0]->middle_name.' '.$employee_details[0]->last_name).'">
				<input type="hidden" name="employee_designation" value="'.$employee_details[0]->designation_name.'">
				<input type="hidden" name="entity_agency" value="'.$employee_details[0]->visa_type.'">
				<input type="hidden" name="date_of_joining" value="'.$employee_details[0]->date_of_joining.'">
				<input type="hidden" name="contract_date" value="'.$employee_details[0]->contract_date.'">
				<input type="hidden" name="last_working_day" value="'.$s_end_date.'">
				<input type="hidden" name="type_of_separtion" value="'.$exit_type.'">';

		$html.='<input type="hidden" name="no_of_days_worked" value="'.$no_of_days_worked.'">
				<input type="hidden" name="no_of_years_worked" value="'.round($no_of_days_worked/365,2).'">
				<input type="hidden" name="no_of_absence" value="'.$count_of_leave_status.'">
				<input type="hidden" name="net_total_years_worked" value="'.round($net_total_years_worked,2).'">
				<input type="hidden" name="total_leave_accrued" value="'.$total_leave_accrued.'">
				<input type="hidden" name="leave_availed" value="'.$leave_availed.'">
				<input type="hidden" name="balance_leave" value="'.$balance_leave.'">';

		$html.='<input type="hidden" name="basic_salary" value="'.$employee_details[0]->basic_salary.'">
				<input type="hidden" name="house_rent_allowance" value="'.$employee_details[0]->house_rent_allowance.'">
				<input type="hidden" name="transportation" value="'.$employee_details[0]->travelling_allowance.'">
				<input type="hidden" name="food_allowance" value="'.$employee_details[0]->food_allowance.'">
				<input type="hidden" name="other_allowance" value="'.$employee_details[0]->other_allowance.'">
				<input type="hidden" name="additional_benefits" value="'.$employee_details[0]->additional_benefits.'">
				<input type="hidden" name="bonus" value="'.$employee_details[0]->bonus.'">
				<input type="hidden" name="total_salary" value="'.$employee_details[0]->gross_salary.'">';

		$html.='<input type="hidden" name="normal_working_hours" value="'.$shift_hours.'">
				<input type="hidden" name="working_hours_for_the_month" value="'.$working_hours_for_the_month.'">
				<input type="hidden" name="actual_working_hour" value="'.$total_working_hours_for_the_month.'">
				<input type="hidden" name="ot_day_hours" value="'.$ot_day_hour.'">
				<input type="hidden" name="ot_night_hours" value="'.$ot_night_hour.'">
				<input type="hidden" name="ot_holiday_hours" value="'.$overtime_holiday_hour.'">';
		$html.='<input type="hidden" name="current_month_salary" value="'.date('d F Y',strtotime($first_date_of_the_month)).' - '.date('d F Y',strtotime($second_month_last_date)).'">
		        <input type="hidden" name="total_days_of_the_month" value="'.$total_days.'">
				<input type="hidden" name="salary_of_the_month" value="'.$salary_of_the_month.'">
				<input type="hidden" name="leave_salary" value="'.$leave_salary.'">
				<input type="hidden" name="leave_salary_days" value="'.$balance_leave.'">
				<input type="hidden" name="ot_day_rate" value="'.$ot_day_rate1.'">
				<input type="hidden" name="ot_night_rate" value="'.$ot_night_rate1.'">
				<input type="hidden" name="ot_holiday_rate" value="'.$ot_holiday_rate1.'">
				<input type="hidden" name="total_earnings" value="'.$total_earnings.'">
				<input type="hidden" name="total_deductions" value="'.$deds.'">
				<input type="hidden" name="net_payable" value="'.(($total_earnings+$perp)-$deds).'">
				<input type="hidden" name="payment_amount_to_employee" value="'.($total_earnings-$deds).'">
				
				<input type="hidden" name="payment_date" value="'.$payment_date.'">
				<input type="hidden" name="late_working_hours" value="'.($payroll_current_data['late_working_hours']+$payroll_prev_data['late_working_hours']+$payroll_current_data_second['late_working_hours']).'">
				<input type="hidden" name="salary_template_id" value="'.$payroll_current_data['salary_template_id'].'">
				<input type="hidden" name="rate_per_hour_contract_bonus" value="'.$rate_contract_bonus.'">
				<input type="hidden" name="rate_per_hour_contract_only" value="'.$rate_contract_only.'">
				<input type="hidden" name="rate_per_hour_basic_only" value="'.$rate_contract_basic.'">
		
				<input type="hidden" name="ot_hours_amount" value='.$ot_hours_array.'>
				<input type="hidden" name="leave_salary_arr" value='.$leave_salary_arr.'>
				<input type="hidden" name="leave_salary_paid_arr" value='.$leave_salary_paid_arr.'>	
				<input type="hidden" name="visa_id" value='.$employee_details[0]->visa_id.'>
				<input type="hidden" name="driver_delivery_details" value='.$driver_delivery_details.'>				
				
				';

		if($leaving_date!=''){
			$leaving_date=format_date('d F Y',$leaving_date);
		}else{
			$leaving_date='';
		}

		$data=array('html_content'=>base64_encode($html),'leaving_date'=>base64_encode($leaving_date));

		echo json_encode($data);
		exit;
	}

	// generate payslips
	public function generate_payslip()
	{	
		$data['title'] = $this->Xin_model->site_title();
		$all_employees = $this->Employees_model->get_employees_payroll(0,0);

		$data['all_employees']=$all_employees->result();
		$data['breadcrumbs'] = 'Generate Payslip';
		$data['path_url'] = 'generate_payslip';
		if(in_array('41',role_resource_ids()) || visa_wise_role_ids() != '') {
			if(!empty($this->userSession)){
				$data['subview'] = $this->load->view("payroll/generate_payslip", $data, TRUE);
				$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
	}

	// payment history
	public function payslip()
	{
		$data['title'] = $this->Xin_model->site_title();
		$payment_id = $this->uri->segment(4);
		$result = $this->Payroll_model->read_make_payment_information($payment_id);
		if(!$result){
			redirect('dashboard');
		}
		$p_method_type=$this->Xin_model->read_document_type($result[0]->payment_method,'payment_method');
		$p_method=$p_method_type[0]->type_name;

		if($result[0]->employee_id!=$this->userSession['user_id'] && (!in_array('38',role_resource_ids()))){
			redirect('dashboard');
		}
		// get addd by > template
		$user = $this->Xin_model->read_user_info($result[0]->employee_id);
		// user full name
		$full_name = change_fletter_caps($user[0]->first_name.' '.$user[0]->middle_name.' '.$user[0]->last_name);
		// get designation
		$designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
		// department
		$department = $this->Department_model->read_department_information($user[0]->department_id);

		$data['all_employees'] = $this->Xin_model->all_employees();
		/*Shift Hours*/
		$d_hours=explode(':',$user[0]->working_hours);
		if(@$d_hours[1]){ $d_mins=@$d_hours[1];} else {$d_mins='0';}
		$f_total_hours = new DateTime($d_hours[0].':'.$d_mins);
		$f_lunch_hours = new DateTime(LUNCH_HOURS);
		$interval_lunch = $f_total_hours;
		if($user[0]->is_break_included == 1)
			$interval_lunch = $f_total_hours->diff($f_lunch_hours);

		$total_work=$interval_lunch->format('%h').':'.$interval_lunch->format('%i');
		$shift_hours = decimalHours($total_work);
		/*Shift Hours*/

		$data = array(
			'title' => $this->Xin_model->site_title(),
			'first_name' => $user[0]->first_name,
			'middle_name' => $user[0]->middle_name,
			'user_id' => $user[0]->user_id,
			'last_name' => $user[0]->last_name,
			'employee_id' => $user[0]->employee_id,
			'contact_no' => $user[0]->contact_no,
			'date_of_joining' => $user[0]->date_of_joining,
			'department_name' => $department[0]->department_name,
			'designation_name' => $designation[0]->designation_name,
			'profile_picture' => $user[0]->profile_picture,
			'gender' => $user[0]->gender,
			'make_payment_id' => $result[0]->make_payment_id,
			'basic_salary' => $result[0]->basic_salary,
			'payment_date' => $result[0]->payment_date,
			'payment_amount' => $result[0]->payment_amount,
			'payment_amount_to_employee' => $result[0]->payment_amount_to_employee,
			'payment_method' => $p_method,
			'required_working_hours' => $result[0]->required_working_hours,
			'shift_hours' => $shift_hours,
			'total_working_hours' => $result[0]->total_working_hours,
			'late_working_hours' => $result[0]->late_working_hours,
			'is_payment' => $result[0]->is_payment,
			'bonus' => $result[0]->bonus,
			'salary_components' => json_decode($result[0]->salary_components),
			'additonal_salary' => $this->Payroll_model->get_additional_salary($result[0]->make_payment_id,'Addition'),
			'deduction_salary' => $this->Payroll_model->get_additional_salary($result[0]->make_payment_id,'Deduction'),
			'find_external_perp_adjustments' => $this->Payroll_model->get_additional_salary($result[0]->make_payment_id,'Perpetual'),
			'find_external_nonperp_adjustments' => $this->Payroll_model->get_additional_salary($result[0]->make_payment_id,'Non-Perpetual'),
			'salary_with_bonus' => $result[0]->salary_with_bonus,
			'total_salary' => $result[0]->total_salary,
			'comments' => $result[0]->comments,
			'actual_days_worked' => $result[0]->actual_days_worked,
			'currency' => $result[0]->currency,
			'leave_salary_paid' => json_decode($result[0]->leave_salary_paid),
			'leave_salary_amount' => $result[0]->leave_salary_amount,
			'annual_leave_salary' => $result[0]->annual_leave_salary,
			'joining_month_salary' => $result[0]->joining_month_salary,
			'month_salary' => $result[0]->month_salary,
			'leave_start_date' => $result[0]->leave_start_date,
			'leave_end_date' => $result[0]->leave_end_date,
			'ot_hours_amount' => $result[0]->ot_hours_amount,
			'tax_amount' => json_decode($result[0]->tax_amount),
			'payment_amount_with_tax' => $result[0]->payment_amount_with_tax,
		);

		$data['breadcrumbs'] = 'Employee Payslip';
		$data['path_url'] = 'payslip';


		if(!empty($this->userSession)){
			$data['subview'] = $this->load->view("payroll/payslip", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
		} else {
			redirect('');
		}
	}

	// payment history
	public function makepayment()
	{
		$data['title'] = $this->Xin_model->site_title();


		$id = $this->input->post('employee_id');
		// get addd by > template
		$user = $this->Xin_model->read_user_info($id);
		$result = $this->Payroll_model->read_template_information_byempid($id);

		$designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
		// department

		$department = $this->Department_model->read_department_information($user[0]->department_id);
		$location = $this->Location_model->read_location_information($department[0]->location_id);
		$data = array(
			'salary_template_id' => $result[0]->salary_template_id,
			'user_id' => $user[0]->user_id,
			'salary_grades' => $result[0]->salary_grades,
			'basic_salary' => $result[0]->basic_salary,
			'house_rent_allowance' => $result[0]->house_rent_allowance,
			'other_allowance' => $result[0]->other_allowance,
			'additional_benefits' => $result[0]->additional_benefits,
			'bonus' => $result[0]->bonus,
			'travelling_allowance' => $result[0]->travelling_allowance,
			'food_allowance' => $result[0]->food_allowance,
			'gross_salary' => $result[0]->gross_salary,
			'total_allowance' => ($result[0]->house_rent_allowance+$result[0]->other_allowance+$result[0]->additional_benefits+$result[0]->travelling_allowance+$result[0]->food_allowance),
			'net_salary' => $result[0]->net_salary,
			'added_by' => $result[0]->added_by,
			'required_working_hours'=>$this->input->post('required_working_hours'),
			'total_working_hours'=>$this->input->post('total_working_hours'),
			'late_working_hours'=>$this->input->post('late_working_hours'),
			'rate_per_hour_contract_bonus'=>$this->input->post('rate_per_hour_contract_bonus'),
			'rate_per_hour_contract_only'=>$this->input->post('rate_per_hour_contract_only'),
			'rate_per_hour_basic_only'=>$this->input->post('rate_per_hour_basic_only'),
			'ot_day_rate'=>$this->input->post('ot_day_rate'),
			'ot_night_rate'=>$this->input->post('ot_night_rate'),
			'ot_holiday_rate'=>$this->input->post('ot_holiday_rate'),
			'total_salary'=>$this->input->post('total_salary'),
			'employee_id'=> $user[0]->employee_id,
			'first_name'=> $user[0]->first_name,
			'middle_name'=> $user[0]->middle_name,
			'last_name'=> $user[0]->last_name,
			'date_of_joining'=> $user[0]->date_of_joining,
			'department_name' => $department[0]->department_name,
			'designation_name' => $designation[0]->designation_name,
			'shift_hours' => total_shift_hours($user[0]->office_shift_id),
			'payment_date'=>$this->input->post('pay_date'),
			'make_payment_id'=> $this->Payroll_model->get_max_payment_id(),
			'user_info'=>$user,
			'department_id' => $user[0]->department_id,
			'designation_id' => $user[0]->designation_id,
			'location_id' => $location[0]->location_id,
			'company_id' => $location[0]->company_id,
		);

		$data['breadcrumbs'] = 'Make Payment';
		$data['path_url'] = 'payslip';
		if(!empty($this->userSession)){
			$data['subview'] = $this->load->view("payroll/makepayment", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
		} else {
			redirect('');
		}
	}

	// salary_addition_deduction
	public function salary_addition_deduction()
	{
		$data['title'] = $this->Xin_model->site_title();
		$data['all_employees'] = $this->Xin_model->all_employees();
		$data['breadcrumbs'] = 'Payment Addition/Deduction';
		$data['path_url'] = 'salary_addition_deduction';
		if(in_array('42',role_resource_ids())) {
			if(!empty($this->userSession)){
				$data['subview'] = $this->load->view("payroll/salary_addition_deduction", $data, TRUE);
				$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
	}

	// payment history
	public function payment_history()
	{
		$data['title'] = $this->Xin_model->site_title();
		$data['all_employees'] = $this->Xin_model->all_employees();
		$data['breadcrumbs'] = 'Payment History';
		$data['path_url'] = 'payment_history';
		if(in_array('42',role_resource_ids()) || visa_wise_role_ids() != '') {
			if(!empty($this->userSession)){
				$data['subview'] = $this->load->view("payroll/payment_history", $data, TRUE);
				$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
	}

	// payroll template list
	public function template_list_employee()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->uri->segment(3);
		if(!empty($this->userSession)){
			$this->load->view("payroll/templates", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		$template = $this->Payroll_model->get_template_byemployee($id);
		$data = array();
		$bns=0;
		foreach($template->result() as $r) {
			$user = $this->Xin_model->read_user_info($r->added_by);
			@$full_name = change_fletter_caps($user[0]->first_name.' '.$user[0]->middle_name.' '.$user[0]->last_name);
			$emp_location = $this->Xin_model->read_user_info($id);
			$basic_salary = get_salary_fields_byname($r->salary_template_id,'basic_salary');
			$bonus = get_salary_fields_byname($r->salary_template_id,'bonus');
			$country_id=$basic_salary[0]->country_id;
			// get basic salary
			$sbs = $this->Xin_model->currency_sign($basic_salary[0]->salary_amount,$emp_location[0]->office_location_id,$emp_location[0]->user_id);
			// get net salary
			$sns = $this->Xin_model->currency_sign($r->salary_with_bonus,$emp_location[0]->office_location_id,$emp_location[0]->user_id);
			$salary_with_bonus = $r->salary_with_bonus;
			$salry_based_contract_s = $this->Xin_model->currency_sign($r->salary_based_on_contract,$emp_location[0]->office_location_id,$emp_location[0]->user_id);


			// get date > created at > and format
			$todate=$r->effective_to_date;
			if($todate==''){
				$todate='<span class="label label-info">Current</span>';
			}else{
				$todate=$this->Xin_model->set_date_format($r->effective_to_date);
			}
			$bns_tm='';


			if($bonus[0]->salary_amount!='' && $bonus[0]->salary_amount!=0){

				$bns_tm='<i style="cursor:pointer;" class="ml-10 icon-bubble-dots4 position-right text-teal-400" data-popup="popover-custom" data-placement="top"  data-trigger="hover" data-content="Bonus-'.($bonus[0]->salary_amount-$bns).'"></i>';


			}
			$bns=$bonus[0]->salary_amount;
			$cdate = $this->Xin_model->set_date_format($r->created_at);

			$edit_perm='';
			$view_perm='';
			if(in_array('pay-structure-edit',role_resource_ids())) {
				$edit_perm='<li><a  data-toggle="modal"  data-target=".edit-modal-data-payrol"  data-salary_template_id="'.$r->salary_template_id.'" data-country_id="'.$country_id.'" data-field_role="edit"><i class="icon-pencil7"></i> Edit</a></li>';
			}


			if(in_array('pay-structure-view',role_resource_ids())) {
				$view_perm='<li><a  data-toggle="modal"  data-target=".edit-modal-data-payrol"  data-salary_template_id="'.$r->salary_template_id.'" data-country_id="'.$country_id.'" data-field_role="view"><i class="icon-eye4"></i> View</a></li>';
			}




			if($r->is_approved==0){
				$is_approved='<span class="label label-warning">Waiting For Approval</span>';
			}else{
				$is_approved='<span class="label label-success">Approved</span>';

			}

			$data[] = array(
				$sbs,
				$salry_based_contract_s,
				$salary_with_bonus.$bns_tm,
				$this->Xin_model->set_date_format($r->effective_from_date),
				$todate.' '.$is_approved,
				$full_name,
				$cdate,
				'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$view_perm.'</ul></li></ul>',
			);


		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $template->num_rows(),
			"recordsFiltered" => $template->num_rows(),
			"data" => $data
		);
		echo json_encode($output);
		exit();
	}

	public function template_list()
	{
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("payroll/templates", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		$template = $this->Payroll_model->get_templates_latest();
		$data = array();

		foreach($template->result() as $r) {
			$user = $this->Xin_model->read_user_info($r->employee_id);
			$full_name = change_fletter_caps($user[0]->first_name.' '.$user[0]->middle_name.' '.$user[0]->last_name);

			$basic_salary = get_salary_fields_byname($r->salary_template_id,'basic_salary');
			$bonus = get_salary_fields_byname($r->salary_template_id,'bonus');
			$country_id=$basic_salary[0]->country_id;
			// get basic salary
			$sbs = $this->Xin_model->currency_sign($basic_salary[0]->salary_amount,$user[0]->office_location_id,$user[0]->user_id);
			// get net salary
			$sns = $this->Xin_model->currency_sign($r->salary_with_bonus,$user[0]->office_location_id,$user[0]->user_id);
			$salary_with_bonus = $r->salary_with_bonus;
			$salry_based_contract_s = $this->Xin_model->currency_sign($r->salary_based_on_contract,$user[0]->office_location_id,$user[0]->user_id);


			if($bonus[0]->salary_amount!='' && $bonus[0]->salary_amount!=0){
				$bonus = $this->Xin_model->currency_sign($bonus[0]->salary_amount,$user[0]->office_location_id,$user[0]->user_id);
			}else{
				$bonus=0;
			}

			$todate=$r->effective_to_date;
			if($todate==''){
				$todate='<span class="label label-info">Current</span>';
			}else{
				$todate=$this->Xin_model->set_date_format($r->effective_to_date);
			}

			if($r->is_approved==1){
				$approve_status = '<span class="label label-success">Approved</span>';
			}else{
				$approve_status = '<span class="label label-warning">Not Yet Approved</span>';
			}

			if(in_array('38te',role_resource_ids()) || ($this->userSession['role_name']==AD_ROLE)){
				$edit_role='<li><a href="#" data-toggle="modal" data-target=".edit-modal-data-payrol"  data-salary_template_id="'. $r->salary_template_id . '" data-country_id="'.$country_id.'"><i class="icon-pencil7"></i> Edit</a></li>';
			}else if(in_array('38tv',role_resource_ids())){
				$edit_role='<li><a href="#" data-toggle="modal" data-target=".edit-modal-data-payrol"  data-salary_template_id="'. $r->salary_template_id . '" data-country_id="'.$country_id.'"><i class="icon-eye4"></i> View Details</a></li>';
			}else{
				$edit_role='<li><a href="#">No Permission</a></li>';
			}

			if(($this->userSession['role_name']==AD_ROLE)){
				$delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->salary_template_id . '"><i class="icon-trash"></i> Delete</a></li>';
			}else{
				$delete_perm='';
			}

			$cdate = $this->Xin_model->set_date_format($r->created_at);

			$data[] = array(
				$full_name,
				$r->location_name,
				$sbs,
				$bonus,
				$salry_based_contract_s,
				$salary_with_bonus,
				$approve_status,
				$this->Xin_model->set_date_format($r->effective_from_date),
				//$todate,
				$cdate,
				'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_role.$delete_perm.'</ul></li></ul>',
			);
		}
		$output = array(
			"draw" => $draw,
			"recordsTotal" => $template->num_rows(),
			"recordsFiltered" => $template->num_rows(),
			"data" => $data
		);
		echo json_encode($output);
		exit();
	}

	// hourly_list > templates
	public function payment_history_list()
	{
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("payroll/payment_history", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		/*Get Passed Variables*/
		$department_id=$this->input->get('department_id');
		$location_id=$this->input->get('location_id');
		$visa_id=$this->input->get('visa_id');
		$month_year=format_date('Y-m',$this->input->get('month_year'));
		/*Get Passed Variables*/
		$history = $this->Payroll_model->all_payment_history($department_id,$location_id,$month_year,$visa_id);
		$data = array();
		$total_paid_amount=0;
		$perp_nonperp_amount=0;
		$total_addition=0;
		$total_perp=0;
		$total_deduct=0;
		$total_nonperp=0;
		$total_am='';
		$total_am_emp='';
		$total_adds='';
		$total_perpatual='';
		$total_nonperpatual='';
		$total_addition='';
		$total_deduction='';
		$footer_arr='';
		$result=$history->result();
		if($result){
			foreach($result as $r) {
				$internal_external_amount=$this->Payroll_model->get_cumulative_amount($r->make_payment_id);


				$curr=$this->Location_model->get_all_currency();
				foreach($curr as $cur_code){
					if($cur_code->code==$r->currency){
						$total_am[$cur_code->code][]=$r->payment_amount;
						$total_am_emp[$cur_code->code][]=+$r->payment_amount_to_employee;
						$total_perpatual[$cur_code->code][]=$this->sum_index($internal_external_amount, 'Perpetual');
						$total_nonperpatual[$cur_code->code][]=$this->sum_index($internal_external_amount, 'Non-Perpetual');
						$total_addition[$cur_code->code][]=$this->sum_index($internal_external_amount, 'Addition');
						$total_deduction[$cur_code->code][]=$this->sum_index($internal_external_amount, 'Deduction');
					}


				}

				// get addd by > template
				$user = $this->Xin_model->read_user_info($r->employee_id);
				// user full name
				$full_name = @$user[0]->first_name.' '.@$user[0]->middle_name.' '.@$user[0]->last_name;

				$emp_link = '<a target="_blank" href="'.site_url().'employees/detail/'.$r->employee_id.'/">'.$r->employee_id.'</a>';

				$month_payment = date("F Y", strtotime($r->payment_date));

				$p_amount = currency_sign(change_num_format($r->payment_amount),$r->currency);//$this->Xin_model->currency_sign($r->payment_amount,$user[0]->office_location_id,$user[0]->user_id);
				$p_amount_to_emp = currency_sign(change_num_format($r->payment_amount_to_employee),$r->currency);
				// get date > created at > and format
				$created_at = $this->Xin_model->set_date_format($r->created_at);
				// get hourly rate
				// payslip
				$payslip = '<a class="text-success" href="'.site_url().'payroll/payslip/id/'.$r->make_payment_id.'/">Generate Payslip</a>';
				// view



				$total_paid_amount+=$r->payment_amount;
				$perp_nonperp_amount+=$r->payment_amount_to_employee;


				$functions = '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right"><li><a href="#" data-toggle="modal" data-target=".detail_modal_data" data-employee_id="'. $r->employee_id . '" data-pay_id="'. $r->make_payment_id . '"><i class="icon-eye4"></i> View Details</a></li><li><a href="'.site_url().'payroll/payslip/id/'.$r->make_payment_id.'/"><i class="icon-printer"></i> Generate Payslip</a></li></ul></li></ul>';

				$p_method_type=$this->Xin_model->read_document_type($r->payment_method,'payment_method');
				$p_method=$p_method_type[0]->type_name;
				$p_remarks=$r->comments;
				$data[] = array(
					$emp_link,
					$full_name,
					$month_payment,
					$created_at,
					$p_amount,
					$p_amount_to_emp,
					$p_remarks,
					$functions,
				);



			}
		}
		if($total_perpatual){
			foreach($total_perpatual as $key2=>$t_m_perp){
				$footer_arr[]=array('0'=>'','1'=>'','2'=>'','3'=>'','4'=>'Total Perpetual Amount Paid By '.$key2,'5'=>':','6'=>change_num_format(array_sum($t_m_perp)).''.$key2,'7'=>'');
			}}

		if($total_nonperpatual){
			foreach($total_nonperpatual as $key3=>$t_m_nonperp){
				$footer_arr[]=array('0'=>'','1'=>'','2'=>'','3'=>'','4'=>'Total Non-Perpetual Amount Paid By '.$key3,'5'=>':','6'=>change_num_format(array_sum($t_m_nonperp)).''.$key3,'7'=>'');
			}}
		if($total_addition){
			foreach($total_addition as $key4=>$t_m_addition){
				$footer_arr[]=array('0'=>'','1'=>'','2'=>'','3'=>'','4'=>'Total Addition Amount Paid By '.$key4,'5'=>':','6'=>change_num_format(array_sum($t_m_addition)).''.$key4,'7'=>'');
			}}

		if($total_deduction){
			foreach($total_deduction as $key5=>$t_m_deduction){
				$footer_arr[]=array('0'=>'','1'=>'','2'=>'','3'=>'','4'=>'Total Deduction Amount Paid By '.$key5,'5'=>':','6'=>change_num_format(array_sum($t_m_deduction)).''.$key5,'7'=>'');
			}}
		if($total_am){
			foreach($total_am as $key=>$t_m){
				$footer_arr[]=array('0'=>'','1'=>'','2'=>'','3'=>'','4'=>'Total Salary Paid By '.$key,'5'=>':','6'=>change_num_format(array_sum($t_m)).''.$key,'7'=>'');
			}}


		if($total_perpatual || $total_nonperpatual || $total_addition || $total_deduction  || $total_am ){
			$data=array_merge($footer_arr,$data);
		}

		//echo "<pre>";print_r($data);die;
		$output = array(
			"draw" => $draw,
			"recordsTotal" => $history->num_rows(),
			"recordsFiltered" => $history->num_rows(),
			"data" => $data
		);

		echo json_encode($output);
		exit();
	}

	// hourly_list > templates
	public function payment_agency_list()
	{
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("payroll/payment_history", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		/*Get Passed Variables*/
		$department_id=$this->input->get('department_id');
		$location_id=$this->input->get('location_id');
		$visa_id=$this->input->get('visa_id');
		$month_year=format_date('Y-m',$this->input->get('month_year'));
		/*Get Passed Variables*/

		$history = $this->Payroll_model->all_agency_payment_history($department_id,$location_id,$month_year,$visa_id);
		//echo "<pre>";print_r($history);die;
		//$history = $this->Payroll_model->total_amount_($department_id,$location_id,$month_year);
		$data = array();
		$total_am='';
		foreach($history->result() as $r) {

			$user = $this->Xin_model->read_user_info($r->employee_id);
			//$agency= $this->Xin_model->read_agency_info($r->employee_id);
			$full_name = change_fletter_caps($user[0]->first_name.' '.$user[0]->middle_name.' '.$user[0]->last_name);
			$emp_link = '<a target="_blank" href="'.site_url().'employees/detail/'.$r->employee_id.'/">'.$user[0]->employee_id.'</a>';
			$month_payment = date("F Y", strtotime($r->payment_date));
			$created_at = $this->Xin_model->set_date_format($r->created_at);
			if($r->agency_name!=''){
				$agency_name=$r->agency_name;
			}else{
				$agency_name='N/A';
			}


			$curr=$this->Location_model->get_all_currency();
			foreach($curr as $cur_code){
				if($cur_code->code==$r->currency){
					$total_am[$cur_code->code][$agency_name][]=+$r->amount;
				}
			}
			$data[] = array(
				$full_name,
				$emp_link,
				$month_payment,
				$created_at,
				$agency_name,
				change_num_format($r->agency_fees).' '.$r->currency,
				$r->required_working_hours,
				round($r->total_working_hours,2),
				round($r->amount,2).' '.$r->currency,
			);
		}

		$footer_arr=[];
		$ads=0;
		if($total_am){
			foreach($total_am as $key_name=>$ag){
				foreach($ag as $vl=>$v2){
					$footer_arr[]=array('0'=>'','1'=>'','2'=>'','3'=>'','4'=>'','5'=>'','6'=>$vl.' ['.$key_name.']','7'=>':','8'=>array_sum($v2).''.$key_name	);
				}
			}
		}

		$data=array_merge($footer_arr,$data);
		if($data==null){$data='';}
		$output = array(
			"draw" => $draw,
			"recordsTotal" => $history->num_rows(),
			"recordsFiltered" => $history->num_rows(),
			"data" => $data
		);

		echo json_encode($output);
		exit();
	}

	public function sum_index($arr,$col_name){
		$sum = 0;
		foreach ($arr as $item) {
			if($item->type_name==$col_name){
				$sum+=$item->amount;
			}
		}
		return $sum;
	}

	public function salary_addition_deduction_list(){
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("payroll/payment_history", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$parent_type = $this->input->get("parent_type");
		$child_type = $this->input->get("child_type");
		$user_id=$this->input->get('user_id');
		$payment_month=$this->input->get('payment_month');
		$s_add_ded = $this->Payroll_model->salary_addition_deduction_list($parent_type,$child_type,$user_id,$payment_month);
		$data = array();
		foreach($s_add_ded->result() as $r) {
			// user full name
			$full_name = $r->first_name.' '.$r->middle_name.' '.$r->last_name;
			$employee_id=$r->employee_id;
			$parent_name=$r->parent_name;
			$child_name=$r->child_name;
			$month_payment = date("F Y", strtotime($r->payment_date));
			$p_amount = $this->Xin_model->currency_sign($r->amount,'',$r->user_id);
			$comments=$r->comments;
			if($r->is_payment==1){$paid='<span class="label label-success">Paid</span>';}else{$paid='<span class="label label-danger">UnPaid</span>';}
			$created_at = $this->Xin_model->set_date_format($r->created_at);
			$payslip = '<a class="text-success" href="'.site_url().'payroll/payslip/id/'.$r->make_payment_id.'/"><span data-toggle="tooltip" data-placement="top" title="" data-original-title="View"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" ><i class="icon-eye4"></i></button></span></a>';

			$data[] = array(
				$month_payment,
				$full_name,
				$employee_id,
				$parent_name,
				$child_name,
				$p_amount,
				$comments,
				$paid,
				$created_at,
				$payslip,
			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $s_add_ded->num_rows(),
			"recordsFiltered" => $s_add_ded->num_rows(),
			"data" => $data
		);
		echo json_encode($output);
		exit();
	}

	public function read_settlement_date(){
		if($this->input->get('type')=='read_settlement_date'){
			$employee_id=$this->input->get('employee_id');
			$month_year=format_date('Y-m',$this->input->get('month_year'));
			$s_end_date=salary_start_end_date($month_year);
			$salary_dat=explode('-',$month_year);
			$count_of_days_of_month=cal_days_in_month(CAL_GREGORIAN,$salary_dat[1],$salary_dat[0]);
			$annual_id_a=$this->Timesheet_model->read_leave_type_id('Annual Leave');
			$salary_month_start_date=$month_year.'-1';
			$salary_month_end_date=$month_year.'-'.$count_of_days_of_month;
			/*
			$query=$this->db->query("select from_date from xin_leave_applications where leave_type_id='".$annual_id_a."' AND employee_id='".$employee_id."' AND
			from_date like '%".$month_year."%' AND status=2 AND reporting_manager_status=2 limit 1");
			$result=$query->result();

			if($result){

				$end_date=date('Y-m-d',strtotime('-1 day',strtotime($result[0]->from_date)));
			}else{
				$end_date=$s_end_date['exact_date_end'];
			}*/

			$end_date=$s_end_date['exact_date_end'];
			echo $return_date=json_encode(array('start_date'=>format_date('d F Y',$s_end_date['exact_date_start']),'end_date'=>format_date('d F Y',$end_date)));
		}
	}

	public function dialog_leave_settlement(){
		//if($this->input->post('type')=='compute_leave_settlement') {
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('pay_id');
		// $data['all_countries'] = $this->xin_model->get_countries();
		$result = $this->Payroll_model->read_make_payment_information($id);
		// get addd by > template

		$employee_id=$result[0]->employee_id;
		$employee_details = $this->Employees_model->read_employee_full_data($employee_id);

		$d_hours=explode(':',$employee_details[0]->working_hours);

		if(@$d_hours[1]){ $d_mins=@$d_hours[1];} else {$d_mins='0';}

		$f_total_hours = new DateTime($d_hours[0].':'.$d_mins);
		$f_lunch_hours = new DateTime(LUNCH_HOURS);
		$interval_lunch = $f_total_hours;
		if($employee_details[0]->is_break_included == 1)
			$interval_lunch = $f_total_hours->diff($f_lunch_hours);

		$total_work=$interval_lunch->format('%h').':'.$interval_lunch->format('%i');
		$shift_hours = decimalHours($total_work);

		$html1='';

		$query1=$this->db->query('select * from xin_employees_approval where employee_id="'.$employee_id.'" AND type_of_approval="Leave Settlement" AND field_id="'.$result[0]->make_payment_id.'" AND pay_date="'.$result[0]->payment_date.'"');
		$result1=$query1->result();

		if($result1){
			$html1.='<div class="panel panel-flat"><table class="table table-md"><tbody>
			<tr class="bg-slate-600 text-center"><td colspan="3">Leave Settlement Status</td></tr>';

			foreach($result1 as $approv_st){
				if($approv_st->approved_date!=''){$approved_date=format_date('d F Y',$approv_st->approved_date);}else{$approved_date= '-';}
				if($approv_st->approval_status==0){$approval_status='<span class="badge bg-info">Waiting for approval</span>';}else if($approv_st->approval_status==1){

					$approval_status= '<span class="badge bg-success">Approved</span>';}else if($approv_st->approval_status==2){$approval_status= '<span class="badge bg-danger">Declined</span>';}
				$html1.='<tr>
					<td>'.$approv_st->head_of_approval.'</td>
					<td>'.$approved_date.'</td>
					<td>'.$approval_status.'</td>    </tr>  ';

			}
			$html1.='</tbody></table></div>';
		}
		
		$html1.='<div class="clearfix"></div><div class="panel panel-flat" id="dvContainer"><div class="panel-heading text-center"><h5 class="panel-title">					 
		<strong>Leave Salary Computation</strong></h5><div class="heading-elements">Date: '.date('d F Y',strtotime($result[0]->created_at)).'</div>	</div>';
		$html1.='<div class="panel-body">';

		$html1.='<div class="col-lg-12 table-responsive"><table class="table table-md"><tbody>
		<tr class="bg-slate-600 text-center"><td colspan="3">Basic Details</td></tr>
		<tr class="success"><td>Employee Code</td><td>:</td><td>'.$employee_details[0]->employee_code.'</td></tr>	
		<tr class=""><td>Employee Name</td><td>:</td><td>'.change_fletter_caps($employee_details[0]->first_name.' '.$employee_details[0]->middle_name.' '.$employee_details[0]->last_name).'</td></tr>	
		<tr class="danger"><td>Designation</td><td>:</td><td>'.$employee_details[0]->designation_name.'</td></tr>
		<tr class=""><td>Entity/Agency</td><td>:</td><td>'.$employee_details[0]->visa_type.'</td></tr>
		<tr class="warning"><td>Date of Join</td><td>:</td><td>'.date('d F Y',strtotime($employee_details[0]->date_of_joining)).'</td></tr>
		<tr class="info"><td>Daily Working Hours</td><td>:</td><td>'.$shift_hours.'</td></tr>';
		$html1.='</tbody></table></div>';

		$basic_salary=change_num_format($result[0]->basic_salary);
		if($result[0]->house_rent_allowance!=''){
			$house_rent_allowance=change_num_format($result[0]->house_rent_allowance);
		}else{$house_rent_allowance='-';}
		if($result[0]->travelling_allowance!=''){
			$travelling_allowance=change_num_format($result[0]->travelling_allowance);
		}else{$travelling_allowance='-';}
		if($result[0]->other_allowance!=''){
			$other_allowance=change_num_format($result[0]->other_allowance);
		}else{$other_allowance='-';}
		if($result[0]->food_allowance!=''){
			$food_allowance=change_num_format($result[0]->food_allowance);
		}else{$food_allowance='-';}
		if($result[0]->additional_benefits!=''){
			$additional_benefits=change_num_format($result[0]->additional_benefits);
		}else{$additional_benefits='-';}
		if($result[0]->bonus!=''){
			$bonus=@change_num_format($result[0]->bonus);
		}else{$bonus='-';}
		$gross_salary=change_num_format($result[0]->gross_salary);

		$html1.='<div class="col-lg-12 table-responsive"><table class="table table-md"><tbody>
		<tr class="bg-slate-600 text-center"><td colspan="3">Monthly Salary</td></tr>	
		<tr class=""><td>Basic Salary</td><td>:</td><td>'.$basic_salary.'</td></tr>
        <tr class="danger"><td>House Rent Allowance</td><td>:</td><td>'.$house_rent_allowance.'</td></tr>
		<tr class=""><td>Transp. Allowance</td><td>:</td><td>'.$travelling_allowance.'</td></tr>
		<tr class="warning"><td>Food Allowance</td><td>:</td><td>'.$food_allowance.'</td></tr>
		<tr class=""><td>Other Allowance</td><td>:</td><td>'.$other_allowance.'</td></tr>
		<tr class="info"><td>Additional Benefit</td><td>:</td><td>'.$additional_benefits.'</td></tr>
		<tr class=""><td>Bonus</td><td>:</td><td>'.$bonus.'</td></tr>
		<tr class="success"><td>Total Salary </td><td>:</td><td>'.$gross_salary.'</td></tr>';
		$html1.='</tbody></table></div>';

		$ot_hours_amount=json_decode($result[0]->ot_hours_amount);
		$ot_day_rate=$ot_hours_amount->ot_day_amount;
		$ot_night_rate=$ot_hours_amount->ot_night_amount;
		$ot_holiday_rate=$ot_hours_amount->ot_holiday_amount;

		$html1.='<div class="col-lg-12 table-responsive"><table class="table table-md"><tbody>
		<tr class="bg-slate-600 text-center"><td colspan="3">Working Hours</td></tr>			
		<tr class=""><td>Working Hours for the month</td><td>:</td><td>'.$result[0]->required_working_hours.'</td></tr>
		<tr class="warning"><td>Actual Working Hours</td><td>:</td><td>'.round($result[0]->total_working_hours,2).'</td></tr>
		<tr class=""><td>OT Hours @ 1.25</td><td>:</td><td>'.decimalHours($ot_hours_amount->ot_day_hours).'</td></tr>
		<tr class="success"><td>OT Hours @ 1.5</td><td>:</td><td>'.decimalHours($ot_hours_amount->ot_night_hours).'</td></tr>
		<tr class=""><td>OT Hours @ PH</td><td>:</td><td>'.decimalHours($ot_hours_amount->ot_holiday_hours).'</td></tr>	
		';
		$html1.='</tbody></table></div>';

		$leave_salary_amount=0;
		$leave_salary_days=0;

		$leave_salary_amount=json_decode($result[0]->leave_salary_amount);
		foreach($leave_salary_amount as $leave_sl_amount){
			foreach($leave_sl_amount as $key=>$value){
				if($key=='AL'){
					$leave_salary_amount=$value->amount;
					$leave_salary_days=$value->days;
				}
			}
		}
		$total_days=$result[0]->total_working_hours/$shift_hours;
		$find_internal_add_adjustments = $this->Payroll_model->payment_adjustments($employee_id,$result[0]->make_payment_id,'Addition',$result[0]->payment_date);
		$find_internal_ded_adjustments = $this->Payroll_model->payment_adjustments($employee_id,$result[0]->make_payment_id,'Deduction',$result[0]->payment_date);
		$adds=0;
		$adds_html='';
		$deds=0;
		$deds_html='';

		if($find_internal_add_adjustments){
			foreach($find_internal_add_adjustments as $int_add_adjustments){
				if($int_add_adjustments->parent_type_name=='Addition'){
					$adds+=$int_add_adjustments->amount;
					$adds_html.='<tr class=""><td>'.$int_add_adjustments->child_type_name.'</td><td></td><td></td><td>'.$int_add_adjustments->comments.'</td><td>'.$int_add_adjustments->amount.'</td></tr>';

				}
			}
		}


		if($find_internal_ded_adjustments){
			foreach($find_internal_ded_adjustments as $int_ded_adjustments){
				if($int_ded_adjustments->parent_type_name=='Deduction'){
					$deds+=$int_ded_adjustments->amount;
					$deds_html.='<tr class=""><td>'.$int_ded_adjustments->child_type_name.'</td><td></td><td></td><td>'.$int_ded_adjustments->comments.'</td><td>'.$int_ded_adjustments->amount.'</td></tr>';


				}
			}
		}

		$total_earnings=$adds+$result[0]->month_salary+$ot_day_rate+$ot_night_rate+$ot_holiday_rate+$result[0]->annual_leave_salary;

		$html1.='<div class="col-lg-12 table-responsive"><table class="table table-md"><tbody>
		<tr class="bg-slate-600"><td>Elements</td><td>From</td><td>To</td><td>Days</td><td>Amount</td></tr>	
		<tr class="success"><td colspan="5">Earnings</td></tr>	
		<tr class=""><td>Current Month Salary</td><td>'.date('d F Y',strtotime($result[0]->leave_settlement_start_date)).'</td><td>'.date('d F Y',strtotime($result[0]->leave_settlement_end_date)).'</td><td>'.round($total_days,2).'</td><td>'.round($result[0]->month_salary,2).'</td></tr>
		<tr class=""><td>Leave Salary</td><td>'.date('d F Y',strtotime($result[0]->leave_start_date)).'</td><td>'.date('d F Y',strtotime($result[0]->leave_end_date)).'</td><td>'.$leave_salary_days.'</td><td>'.round($result[0]->annual_leave_salary,2).'</td></tr><tr class=""><td>OT  Amount @ 1.25</td><td></td><td></td><td></td><td>'.$ot_day_rate.'</td></tr>
		<tr class=""><td>OT Amount @ 1.5</td><td></td><td></td><td></td><td>'.$ot_night_rate.'</td></tr>
		<tr class=""><td>OT  Amount @ PH</td><td></td><td></td><td></td><td>'.$ot_holiday_rate.'</td></tr>
		'.$adds_html.'<tr class=""><td>Total Earnings</td><td></td><td></td><td></td><td>'.change_num_format($total_earnings).'</td></tr>	<tr class="danger"><td colspan="5" text-center>Deductions</td></tr>
		'.$deds_html.'<tr class=""><td>Total Deductions</td><td></td><td></td><td></td><td>'.change_num_format($deds).'</td></tr>
		<tr class="warning"><td>Net Payable</td><td></td><td></td><td></td><td>'.change_num_format($total_earnings-$deds).'</td></tr>';

		/*Tax*/
		$tax_amount=json_decode($result[0]->tax_amount);
		$tax_value=[];
		if($tax_amount){
			foreach($tax_amount as $tax_t){
				$html1.='<tr class=""><td>'.$tax_t->tax_name.'</td><td>'.$tax_t->tax_percentage.'%</td><td></td><td></td><td>'.change_num_format($tax_t->tax_amount).'</td></tr>';
			}
			$html1.='<tr class="green"><td>Payment Amount with Tax</td><td></td><td></td><td></td><td>'.change_num_format($result[0]->payment_amount_with_tax).'</td></tr>';
		}
		/*Tax*/
		$html1.='</tbody></table></div>';
		$html1.='</div>';
		$html1.='</div>';
		$data = array(
			'first_name' => $employee_details[0]->first_name,
			'middle_name' => $employee_details[0]->middle_name,
			'last_name' => $employee_details[0]->last_name,
			'employee_id' => $employee_details[0]->employee_id,
			'html1'=>$html1,
		);
		if(!empty($this->userSession)){
			$this->load->view('payroll/dialog_payslip', $data);
		} else {
			redirect('');
		}
	}

	public function compute_leave_settlement(){
		if($this->input->post('type')=='compute_leave_settlement') {
			$Return = array('result'=>'', 'error'=>'', 'message'=>'');
			$employee_id=$this->input->post('employee_id');
			$employee_details = $this->Employees_model->read_employee_full_data($employee_id);
			$month_year=format_date('Y-m',$this->input->post('month_year'));
			$exact_date=salary_start_end_date($month_year);
			$start_date=$exact_date['exact_date_start'];
			$end_date=$exact_date['exact_date_end'];
			$payroll_data=$this->payslip_list_new($employee_id,$month_year,$start_date,$end_date);
			$save=$this->input->post('save');

			$salary_dat=explode('-',$month_year);
			$count_of_days_of_month=cal_days_in_month(CAL_GREGORIAN,$salary_dat[1],$salary_dat[0]);
			$salary_month_start_date=$month_year.'-01';
			$salary_month_end_date=$month_year.'-'.$count_of_days_of_month;

			$country=$this->Location_model->read_location_information($employee_details[0]->office_location_id);
			$country_id=$country[0]->country;
			$shift_hours = $payroll_data['shift_hours'];

			$leave_start_date=$payroll_data['leave_start_date'];
			$leave_end_date=$payroll_data['leave_end_date'];

			$leave_start_date1 = new DateTime($payroll_data['leave_start_date']);
			$leave_end_date1 = new DateTime($payroll_data['leave_end_date']);
			$leave_salary_days = ($leave_start_date1->diff($leave_end_date1)->format("%a")+1);
			$leave_salary_amount=$payroll_data['annual_leave_salary'];
			if($Return['error']!=''){
				$this->output($Return);
			}


			$required_working_hours=$payroll_data['required_working_hours'];

			$s_start_date=$start_date;
			$s_end_date=$end_date;


			if(strtotime($end_date) >= strtotime($leave_start_date)){
				$end_date=date('Y-m-d',strtotime('-1 day',strtotime($leave_start_date)));
				$s_end_date=$end_date;
			}

			$salary_of_the_month=$payroll_data['month_salary'];
			$ot_hours_amount=json_decode($payroll_data['ot_hours_amount']);


			$ot_day_rate=$ot_hours_amount->ot_day_amount;
			$ot_night_rate=$ot_hours_amount->ot_night_amount;
			$ot_holiday_rate=$ot_hours_amount->ot_holiday_amount;

			$html1='';
			$html1.='<div class="panel panel-flat" id="dvContainer"><div class="panel-heading text-center"><h3 class="panel-title">					 
		<strong>Leave Salary Computation</strong></h3><div class="heading-elements">Date: '.date('d F Y',strtotime(TODAY_DATE)).'</div>	</div>';
			$html1.='<div class="panel-body">';

			$html1.='<div class="col-lg-9 table-responsive" style="margin-bottom: 1em;"><table class="table table-md"><tbody>
			<tr class="bg-slate-600 text-center"><td colspan="3">Basic Details</td></tr>
			<tr class="success"><td>Employee Code</td><td>:</td><td>'.$employee_details[0]->employee_code.'</td></tr>	
			<tr class=""><td>Employee Name</td><td>:</td><td>'.change_fletter_caps($employee_details[0]->first_name.' '.$employee_details[0]->middle_name.' '.$employee_details[0]->last_name).'</td></tr>	
			<tr class="danger"><td>Designation</td><td>:</td><td>'.$employee_details[0]->designation_name.'</td></tr>
			<tr class=""><td>Entity/Agency</td><td>:</td><td>'.$employee_details[0]->visa_type.'</td></tr>
			<tr class="warning"><td>Date of Join</td><td>:</td><td>'.date('d F Y',strtotime($employee_details[0]->date_of_joining)).'</td></tr>
			<tr class="info"><td>Daily Working Hours</td><td>:</td><td>'.$shift_hours.'</td></tr>';
			$html1.='</tbody></table></div>';



			$basic_salary=change_num_format($payroll_data['basic_salary']);
			if($payroll_data['house_rent_allowance']!=''){
				$house_rent_allowance=change_num_format($payroll_data['house_rent_allowance']);
			}else{$house_rent_allowance='-';}
			if($payroll_data['travelling_allowance']!=''){
				$travelling_allowance=change_num_format($payroll_data['travelling_allowance']);
			}else{$travelling_allowance='-';}
			if($payroll_data['other_allowance']!=''){
				$other_allowance=change_num_format($payroll_data['other_allowance']);
			}else{$other_allowance='-';}
			if($payroll_data['food_allowance']!=''){
				$food_allowance=change_num_format($payroll_data['food_allowance']);
			}else{$food_allowance='-';}
			if($payroll_data['additional_benefits']!=''){
				$additional_benefits=change_num_format($payroll_data['additional_benefits']);
			}else{$additional_benefits='-';}
			if($payroll_data['bonus']!=''){
				$bonus=@change_num_format($payroll_data['bonus']);
			}else{$bonus='-';}
			$gross_salary=change_num_format($payroll_data['gross_salary']);

			$html1.='<div class="col-lg-6 table-responsive" "margin-bottom: 1em;"><table class="table table-md"><tbody>
			<tr class="bg-slate-600 text-center"><td colspan="3">Monthly Salary</td></tr>	
			<tr class=""><td>Basic Salary</td><td>:</td><td>'.$basic_salary.'</td></tr>
			<tr class="danger"><td>House Rent Allowance</td><td>:</td><td>'.$house_rent_allowance.'</td></tr>
			<tr class=""><td>Transp. Allowance</td><td>:</td><td>'.$travelling_allowance.'</td></tr>
			<tr class="warning"><td>Food Allowance</td><td>:</td><td>'.$food_allowance.'</td></tr>
			<tr class=""><td>Other Allowance</td><td>:</td><td>'.$other_allowance.'</td></tr>
			<tr class="info"><td>Additional Benefit</td><td>:</td><td>'.$additional_benefits.'</td></tr>
			<tr class=""><td>Bonus</td><td>:</td><td>'.$bonus.'</td></tr>
			<tr class="success"><td>Total Salary </td><td>:</td><td>'.$gross_salary.'</td></tr>';
			$html1.='</tbody></table></div>';

			$html1.='<div class="col-lg-6 table-responsive" style="margin-bottom: 1em;"><table class="table table-md"><tbody>		
			<tr class=""><td>Working Hours for the month</td><td>:</td><td>'.$required_working_hours.'</td></tr>
			<tr class="warning"><td>Actual Working Hours</td><td>:</td><td>'.round($payroll_data['total_working_hours'],2).'</td></tr>
			<tr class=""><td>OT Hours @ 1.25</td><td>:</td><td>'.decimalHours($ot_hours_amount->ot_day_hours).'</td></tr>
			<tr class="success"><td>OT Hours @ 1.5</td><td>:</td><td>'.decimalHours($ot_hours_amount->ot_night_hours).'</td></tr>
			<tr class=""><td>OT Hours @ PH</td><td>:</td><td>'.decimalHours($ot_hours_amount->ot_holiday_hours).'</td></tr>	
			';
			$html1.='</tbody></table></div>';



			$find_internal_add_adjustments = $this->Payroll_model->find_adjustments($employee_id,$s_start_date,$s_end_date,'internal_adjustments','Addition');
			$find_internal_ded_adjustments = $this->Payroll_model->find_adjustments($employee_id,$s_start_date,$s_end_date,'internal_adjustments','Deduction');

			$adds=0;
			$adds_html='';
			$deds=0;
			$deds_html='';

			if($find_internal_add_adjustments){
				foreach($find_internal_add_adjustments as $int_add_adjustments){
					if($int_add_adjustments->parent_type_name=='Addition'){
						$adds+=$int_add_adjustments->adjustment_amount;
						$adds_html.='<tr class=""><td>'.$int_add_adjustments->child_type_name.'</td><td></td><td></td><td>'.$int_add_adjustments->comments.'</td><td>'.$int_add_adjustments->adjustment_amount.'</td></tr>';

					}
				}
			}

			if($find_internal_ded_adjustments){
				foreach($find_internal_ded_adjustments as $int_ded_adjustments){
					if($int_ded_adjustments->parent_type_name=='Deduction'){
						$deds+=$int_ded_adjustments->adjustment_amount;
						$deds_html.='<tr class=""><td>'.$int_ded_adjustments->child_type_name.'</td><td></td><td></td><td>'.$int_ded_adjustments->comments.'</td><td>'.$int_ded_adjustments->adjustment_amount.'</td></tr>';


					}
				}
			}
			$total_earnings=$adds+$salary_of_the_month+$ot_day_rate+$ot_night_rate+$ot_holiday_rate+$leave_salary_amount;

			//$total_days=$payroll_data['total_working_hours']/$shift_hours;
			$total_days=$payroll_data['actual_days_worked'];
			if($leave_start_date!=''){	$l_start_date=date('d F Y',strtotime($leave_start_date));}else{$l_start_date='-';}
			if($leave_end_date!=''){	$l_end_date=date('d F Y',strtotime($leave_end_date));}else{$l_end_date='-';}
			if($leave_salary_days==''){ $leave_salary_days=0; }
			if($leave_salary_amount==''){ $leave_salary_amount=0; }

			$total_after_deduction=$total_earnings-$deds;



			$html1.='<div class="col-lg-12 table-responsive" "margin-bottom: 1em;"><table class="table table-md"><tbody>
			<tr class="bg-slate-600"><td>Elements</td><td>From</td><td>To</td><td>Days</td><td>Amount</td></tr>	
			<tr class="success"><td colspan="5">Earnings</td></tr>	
			<tr class=""><td>Current Month Salary</td><td>'.date('d F Y',strtotime($s_start_date)).'</td><td>'.date('d F Y',strtotime($s_end_date)).'</td><td>'.change_num_format($total_days).'</td><td>'.change_num_format($salary_of_the_month).'</td></tr>
			<tr class=""><td>Leave Salary</td><td>'.$l_start_date.'</td><td>'.$l_end_date.'</td><td>'.$leave_salary_days.'</td><td>'.change_num_format($leave_salary_amount).'</td></tr>
			<tr class=""><td>OT  Amount @ 1.25</td><td></td><td></td><td></td><td>'.change_num_format($ot_day_rate).'</td></tr>
			<tr class=""><td>OT Amount @ 1.5</td><td></td><td></td><td></td><td>'.change_num_format($ot_night_rate).'</td></tr>
			<tr class=""><td>OT  Amount @ PH</td><td></td><td></td><td></td><td>'.change_num_format($ot_holiday_rate).'</td></tr>'.$adds_html.'
			<tr class=""><td>Total Earnings</td><td></td><td></td><td></td><td>'.change_num_format($total_earnings).'</td></tr>
			<tr class="danger"><td colspan="5" text-center>Deductions</td></tr>
			'.$deds_html.'
			<tr class=""><td>Total Deductions</td><td></td><td></td><td></td><td>'.change_num_format($deds).'</td></tr>

			<tr class="warning"><td>Net Payable</td><td></td><td></td><td></td><td>'.change_num_format($total_after_deduction).'</td></tr>';
			/*Tax*/
			$visa_id=$payroll_data['visa_type'];
			$tax_type=get_tax_info($visa_id);
			$total_tax_amount=0;
			$tax_value=[];
			if($tax_type){
				foreach($tax_type as $tax_t){
					$tax_amount=($total_after_deduction*($tax_t->type_symbol/100));
					$total_tax_amount+=$tax_amount;
					$tax_value[]=array('tax_name'=>$tax_t->type_name,'tax_percentage'=>$tax_t->type_symbol,'tax_amount'=>$tax_amount);
					$html1.='<tr class=""><td>'.$tax_t->type_name.'</td><td>'.$tax_t->type_symbol.'%</td><td></td><td></td><td>'.change_num_format($tax_amount).'</td></tr>';
				}
				$payment_amount_with_tax=$total_after_deduction+$total_tax_amount;
				$html1.='<tr class="green"><td>Payment Amount with Tax</td><td></td><td></td><td></td><td>'.change_num_format($payment_amount_with_tax).'</td></tr>';
			}

			/*Tax*/
			$html1.='</tbody></table></div><div class="clearfix"></div>
			<div class="footer-elements" ><form class="m-b-1 add form-hrm" action="'.site_url("payroll/compute_leave_settlement").'"  method="post" name="compute_leave_settlement_save" id="compute_leave_settlement_save"><input type="hidden" name="employee_id" value="'.$employee_id.'"/>	<input type="hidden" name="month_year" value="'.$month_year.'"/>	<input type="hidden" name="start_date" value="'.$start_date.'"/>	<input type="hidden" name="end_date" value="'.$end_date.'"/>	<input type="hidden" value="1" name="save"/>';

			if($leave_salary_amount==0){
				$html1.='<span class="badge bg-warning">Leave settlement not available for this month.</span>';
			}else{
				$html1.='<button type="submit" class="btn bg-teal-400 save_l">Send Approval</button>';
			}
			$html1.='</form></div>';
			$html1.='</div>';
			$html1.='</div>';
			$data=array('html_content'=>base64_encode($html1));

			if($save==0){
				echo json_encode($data);
				die;
			}
			else{

				$find_external_perp_adjustments = $this->Payroll_model->find_ext_adjustments($employee_id,$s_start_date,$s_end_date,'external_adjustments','Perpetual');
				$find_external_nonperp_adjustments = $this->Payroll_model->find_ext_adjustments($employee_id,$s_start_date,$s_end_date,'external_adjustments','Non-Perpetual');

				$adds=0;
				$deds=0;
				$perp=0;

				$adjustment_id='';
				$parent_type='';
				$child_type='';
				$amount='';
				$salary_comments='';
				if($find_internal_add_adjustments){
					foreach($find_internal_add_adjustments as $int_add_adjustments){
						$adds+=$int_add_adjustments->adjustment_amount;
						$adjustment_id[]=$int_add_adjustments->adjustment_id;
						$parent_type[]=$int_add_adjustments->adjustment_type;
						$child_type[]=$int_add_adjustments->adjustment_name;
						$amount[]=$int_add_adjustments->adjustment_amount;
						$salary_comments[]=$int_add_adjustments->comments;
					}
				}

				if($find_internal_ded_adjustments){
					foreach($find_internal_ded_adjustments as $int_ded_adjustments){
						$deds+=$int_ded_adjustments->adjustment_amount;
						$adjustment_id[]=$int_ded_adjustments->adjustment_id;
						$parent_type[]=$int_ded_adjustments->adjustment_type;
						$child_type[]=$int_ded_adjustments->adjustment_name;
						$amount[]=$int_ded_adjustments->adjustment_amount;
						$salary_comments[]=$int_ded_adjustments->comments;
					}
				}

				if($find_external_perp_adjustments){
					foreach($find_external_perp_adjustments as $ext_perp_adjustments){

						//$ext_perp_adjustments_amount=(($ext_perp_adjustments->adjustment_amount)-($ext_perp_adjustments->adjustment_amount*($ext_perp_adjustments->tax_percentage/100)));
						$ext_perp_adjustments_amount=$ext_perp_adjustments->adjustment_amount;

						if($ext_perp_adjustments->compute_amount==0){
							$computed_perp_amount=$ext_perp_adjustments_amount;
							$perp+=$computed_perp_amount;
						}else{
							$computed_perp_amount=(($ext_perp_adjustments_amount/$get_payroll_details['required_working_hours'])*$get_payroll_details['total_working_hours']);
							$perp+=$computed_perp_amount;
						}

						$parent_type[]=$ext_perp_adjustments->adjustment_type;
						$child_type[]=$ext_perp_adjustments->adjustment_name;
						$amount[]=round($computed_perp_amount,2);
						$salary_comments[]=$ext_perp_adjustments->comments;
					}
				}

				if($find_external_nonperp_adjustments){
					foreach($find_external_nonperp_adjustments as $ext_nonperp_adjustments){

						$ext_nonperp_adjustments_amount=$ext_nonperp_adjustments->adjustment_amount;

						if($ext_nonperp_adjustments->compute_amount==0){
							$computed_nonperp_amount=$ext_nonperp_adjustments_amount;
							$perp+=$computed_nonperp_amount;
						}else{
							$computed_nonperp_amount=(($ext_nonperp_adjustments_amount/$get_payroll_details['required_working_hours'])*$get_payroll_details['total_working_hours']);
							$perp+=$computed_nonperp_amount;
						}

						$parent_type[]=$ext_nonperp_adjustments->adjustment_type;
						$child_type[]=$ext_nonperp_adjustments->adjustment_name;
						$amount[]=round($computed_nonperp_amount,2);
						$salary_comments[]=$ext_nonperp_adjustments->comments;
					}
				}


				if($payroll_data['other_allowance']!=''){
					$other_allowance=$payroll_data['other_allowance'];
				}else{$other_allowance=0;}
				if($payroll_data['additional_benefits']!=''){
					$additional_benefits=$payroll_data['additional_benefits'];
				}else{$additional_benefits=0;}
				if($payroll_data['bonus']!=''){
					$bonus=$payroll_data['bonus'];
				}else{$bonus=0;}
				if($payroll_data['travelling_allowance']!=''){
					$travelling_allowance=$payroll_data['travelling_allowance'];
				}else{$travelling_allowance=0;}
				if($payroll_data['food_allowance']!=''){
					$food_allowance=$payroll_data['food_allowance'];
				}else{$food_allowance=0;}
				if($payroll_data['house_rent_allowance']!=''){
					$house_rent_allowance=$payroll_data['house_rent_allowance'];
				}else{$house_rent_allowance=0;}




				$N_salary=($total_earnings+$perp)-$deds;
				$data = array(
					'employee_id' => $employee_id,
					'department_id' => $payroll_data['department_id'],
					'company_id' => $payroll_data['company_id'],
					'location_id' => $payroll_data['office_location_id'],
					'designation_id' => $payroll_data['designation_id'],
					'payment_date' => $month_year,
					'payment_amount' => $N_salary,
					'payment_amount_to_employee' => ($N_salary-$perp),
					'basic_salary' => $payroll_data['basic_salary'],
					'gross_salary' => $payroll_data['gross_salary'],
					'net_salary' => $payroll_data['net_salary'],
					'total_salary' => $payroll_data['total_salary'],
					'salary_template_id' => $payroll_data['salary_template_id'],
					'house_rent_allowance' => $house_rent_allowance,
					'other_allowance' => $other_allowance,
					'travelling_allowance' => $travelling_allowance,
					'food_allowance' => $food_allowance,
					'additional_benefits' => $additional_benefits,
					'bonus' => $bonus,
					'required_working_hours' => $payroll_data['required_working_hours'],
					'total_working_hours' => $payroll_data['total_working_hours'],
					'late_working_hours' => $payroll_data['late_working_hours'],
					'rate_per_hour_contract_bonus' => $payroll_data['rate_per_hour_contract_bonus'],
					'rate_per_hour_contract_only' => $payroll_data['rate_per_hour_contract_only'],
					'rate_per_hour_basic_only' => $payroll_data['rate_per_hour_basic_only'],
					'ot_day_rate' => $payroll_data['ot_day_rate'],
					'ot_night_rate' => $payroll_data['ot_night_rate'],
					'ot_holiday_rate' => $payroll_data['ot_holiday_rate'],
					'leave_salary_paid' => $payroll_data['leave_salary_paid'],
					'leave_salary_amount' =>  $payroll_data['leave_salary'],
					'ot_hours_amount' => $payroll_data['ot_hours_amount'],
					'actual_days_worked' => $payroll_data['actual_days_worked'],
					'is_payment' => '1',
					'payment_method' => 150,
					'comments' => 'Leave Settlement',
					'status' => '2',
					'created_at' => date('d-m-Y h:i:s'),
					'currency' => $this->Xin_model->currency_sign('',$location_id='',$employee_id),
					'leave_settlement_start_date'=>$start_date,
					'leave_settlement_end_date'=>$end_date,
					'leave_start_date'=>$leave_start_date,
					'leave_end_date'=>$leave_end_date,
					'annual_leave_salary' => $payroll_data['annual_leave_salary'],
					'month_salary' => $payroll_data['month_salary'],
					'joining_month_salary' => $payroll_data['joining_month_salary'],
					'tax_amount' => json_encode($tax_value),
					'payment_amount_with_tax' => $payment_amount_with_tax,
				);


				$remove_payment_hold=$this->Payroll_model->remove_payment_leave_hold($employee_id,$month_year,$s_start_date,$s_end_date);

				$this->db->where('employee_id', $employee_id);
				$this->db->where('type_of_approval', 'Leave Settlement');
				$this->db->where('pay_date', $month_year);
				$this->db->delete('xin_employees_approval');

				if($adjustment_id){
					foreach($adjustment_id as $adjus){
						$this->Payroll_model->update_salary_adjustments_status($adjus,1);
					}
				}


				$result = $this->Payroll_model->add_monthly_payment_payslip($data);

				if($parent_type){
					$count_type=count($parent_type);
					$payment_return_id=$result;
					$created_at=date('d-m-Y h:i:s');
					$parent_type=$parent_type;
					$child_type=$child_type;
					$amount=$amount;
					$comments=$salary_comments;

					for($i=0;$i<$count_type;$i++){
						$data_options=array('make_payment_id'=>$payment_return_id,'parent_type'=>$parent_type[$i],'child_type'=>$child_type[$i],'amount'=>$amount[$i],'comments'=>$comments[$i],'created_at'=>$created_at,'payment_date'=>$month_year);
						$result1 = $this->Payroll_model->add_payment_options($data_options);
					}
				}

				$field_id=$result;
				$created_by=$this->userSession['user_id'];
				$type_of_approval='Leave Settlement';

				$all_department_heads=$this->Employees_model->all_department_heads($payroll_data['department_id'],'','',$type_of_approval);


				$count_of_dept=count($all_department_heads);
				$setting = $this->Xin_model->read_setting_info(1);
				$this->email->set_mailtype("html");
				if($all_department_heads){
					$insert_approvals=$this->Employees_model->insert_approvals($employee_id,$field_id,$created_by,$all_department_heads,$type_of_approval,$month_year);
					$emp_info = $this->Xin_model->read_user_info($employee_id);
					$designation = $this->Designation_model->read_designation_information($emp_info[0]->designation_id);
					$emp_full_name = change_fletter_caps($emp_info[0]->first_name.' '.$emp_info[0]->middle_name.' '.$emp_info[0]->last_name);

					$cinfo = $this->Xin_model->read_company_setting_info(1);

					$sender = $this->Xin_model->read_user_info($created_by);
					$sender_designation = $this->Designation_model->read_designation_information($sender[0]->designation_id);
					$sender_full_name = change_fletter_caps($sender[0]->first_name.' '.$sender[0]->middle_name.' '.$sender[0]->last_name);
					foreach($all_department_heads as $head_of_dep){

						$head_info = $this->Xin_model->read_user_info($head_of_dep->head_id);
						$approve_link=$_SERVER['HTTP_HOST'].base_url().'index/approval/'.base64_encode($head_of_dep->head_id.'/'.$employee_id.'/1/'.$type_of_approval.'/'.$created_by.'/'.$field_id);
						$decline_link=$_SERVER['HTTP_HOST'].base_url().'index/approval/'.base64_encode($head_of_dep->head_id.'/'.$employee_id.'/2/'.$type_of_approval.'/'.$created_by.'/'.$field_id);

						//if($setting[0]->enable_email_notification == 'yes') {
						//get email template
						$template = $this->Xin_model->read_email_template_info_bycode('Leave Settlement');

						if($count_of_dept!=1){
							$head_title='Leave Settlement';
							$subject = $template[0]->subject;
						}else{
							$head_title='Leave Settlement ReApproval';
							$subject = $head_title;
						}


						$html1=str_replace(array(
							'<button type="submit" class="btn bg-teal-400 save_l">Send Approval</button>',
							'<table',
							'<tr class="bg-slate-600"',
							'<tr class="bg-slate-600 text-center"',
							'<div class="panel-heading text-center">'
						),
							array(
								'',
								'<table border="1" style="padding:10px;width: 100%;text-align:center;font-size:14px;line-height:20px;" ',
								'<tr style="background:#cc6076;color: white;font-size: 13px;" ',
								'<tr style="text-align:center;background: #cc6076;color: white;font-size: 13px;" ',
								'<div class="panel-heading text-center" style="text-align: center;margin-bottom: 1em;font-weight: bold;">'

							),
							htmlspecialchars_decode(stripslashes($html1)));


						if($emp_info[0]->gender=='Male'){
							$title='Mr ';
						}else{
							$title='Ms ';
						}
						$message = '
							<div style="background: #f7eaea;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin: 0 auto;padding:20px;max-width: 65em;border: 2px solid #D40732;">'.
							str_replace(array(
								"{var head_title}",
								"{var head_name}",
								"{var title}",
								"{var employee_name}",
								"{var joining_date}",
								"{var 1c_id}",
								"{var html_structure}",
								"{var site_url}",
								"{var designation}",
								"{var approve_link}",
								"{var decline_link}",
								"{var sender_full_name}",
								"{var sender_designation}",
							),
								array(
									$head_title,
									$head_of_dep->head_name,
									$title,
									$emp_full_name,
									format_date('d F Y',$emp_info[0]->date_of_joining),
									$emp_info[0]->employee_id,
									$html1,
									'',
									$designation[0]->designation_name,
									$approve_link,
									$decline_link,
									$sender_full_name,
									$sender_designation[0]->designation_name
								),
								htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';



						if(TESTING_MAIL==TRUE){
							$this->email->from(FROM_MAIL, $sender_full_name);
							$this->email->to(TO_MAIL);
						}else{
							$this->email->from($sender[0]->email, $sender_full_name);
							$this->email->to($head_info[0]->email);
						}


						$this->email->subject($subject);
						$this->email->message($message);
						//$this->email->send();
						//}
					}

					if($insert_approvals){
						$Return['result'] = 'Leave approval send successfully.';
						$Return['message']='<div class="alert alert-success no-border">
										<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
										<span class="text-semibold">Well done!</span> You successfully send this for approval.
								    </div>';
					}else{
						$Return['error'] = $this->lang->line('xin_error_msg');
					}
				}else{
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				$this->output($Return);
				exit;
			}
		}
	}

	public function leave_settlement(){
		$data['title'] = $this->Xin_model->site_title();
		$data['all_employees'] = $this->Xin_model->all_active_employees();
		$data['breadcrumbs'] = "Employee's Leave Settlement";
		$data['path_url'] = 'leave_settlement';
		if(in_array('41L',role_resource_ids()) || visa_wise_role_ids() != '') {
			if(!empty($this->userSession)){
				$data['subview'] = $this->load->view("payroll/leave_settlement", $data, TRUE);
				$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
	}

	public function joining_date_hours($employee_id,$date_of_joining,$shift_hours,$total_rest_hours,$check_ramadan_date,$rate_per_hour_contract_bonus,$calculate_salary){
		$system_settings = $this->Xin_model->read_setting_info(1);
		$query=$this->db->query("select total_rest from xin_attendance_time where attendance_date='".$date_of_joining."' AND employee_id='".$employee_id."' ");
		$result=$query->result();
		$date_of_join_hours=$result[0]->total_rest;
		$date_of_join_salary=$date_of_join_hours*$rate_per_hour_contract_bonus;
		$modified_salary=$shift_hours*$rate_per_hour_contract_bonus;
		$actual_hours=($total_rest_hours+$shift_hours)-$date_of_join_hours;
		$actual_salary=($calculate_salary+$modified_salary)-$date_of_join_salary;
		return array('actual_hours'=>$actual_hours,'actual_salary'=>$actual_salary);
	}

	public function required_working_hours($salary_month_start_date,$salary_month_end_date,$shift_hours,$country_id){
		$days_count=[];
		$start_date = new DateTime($salary_month_start_date);
		$end_date = new DateTime($salary_month_end_date);
		$end_date = $end_date->modify( '+1 day' );
		$interval_re = new DateInterval('P1D');
		$date_range = new DatePeriod($start_date, $interval_re ,$end_date);
		$ramadan_date=array();
		foreach($date_range as $date) {
			$attendance_date = $date->format("Y-m-d");
			$days_count[]=$shift_hours;
		}
		return array('count_of_remaining_days'=>count($days_count),'count_of_remaining_hours'=>array_sum($days_count));
	}

	public function remaining_days_of_month($salary_month_start_date,$salary_month_end_date,$shift_hours,$country_id){
		$days_count=[];
		$start_date = new DateTime($salary_month_start_date);
		$end_date = new DateTime($salary_month_end_date);
		$end_date = $end_date->modify( '+1 day' );
		$interval_re = new DateInterval('P1D');
		$date_range = new DatePeriod($start_date, $interval_re ,$end_date);
		$ramadan_date=array();
		foreach($date_range as $date){
			$attendance_date = $date->format("Y-m-d");
			$days_count[]=$shift_hours;
		}
		return array('count_of_remaining_days'=>count($days_count),'count_of_remaining_hours'=>array_sum($days_count));
	}

	public function remaining_days_of_monthwithsalary($salary_month_start_date,$salary_month_end_date,$shift_hours,$country_id,$user_id,$p_m_required_working_hours){
		$rate_per_hour_contract_bonus=rate_per_hour_contract_bonus($user_id,$salary_month_start_date,$salary_month_end_date,$p_m_required_working_hours);

		$rate_per_hours=0;
		$rate_per_hour_count=count($rate_per_hour_contract_bonus);
		if($rate_per_hour_count==1){
			$rate_per_hours=$rate_per_hour_contract_bonus[0]['rate_per_hour'];
		}
		$days_count=[];
		$salary_count=[];
		$start_date = new DateTime($salary_month_start_date);
		$end_date = new DateTime($salary_month_end_date);
		$end_date = $end_date->modify( '+1 day' );
		$interval_re = new DateInterval('P1D');
		$date_range = new DatePeriod($start_date, $interval_re ,$end_date);
		$ramadan_date=array();
		foreach($date_range as $date) {
			$attendance_date = $date->format("Y-m-d");
			if($rate_per_hour_count==1){
				$days_count[]=$shift_hours;
				$salary_count[]=$shift_hours*$rate_per_hours;
			}else{
				$rate_per_hours1=get_rate_per_hours($attendance_date,$rate_per_hour_contract_bonus);
				$days_count[]=$shift_hours;
				$salary_count[]=$shift_hours*$rate_per_hours1;
			}

		}
		return array('count_of_remaining_days'=>count($days_count),'count_of_remaining_hours'=>array_sum($days_count),'salary_count'=>array_sum($salary_count));
	}

	public function remaining_days_of_leave_hours($leave_st_date,$leave_ed_date,$payment_date,$user_id,$shift_hours,$country_id){
		$required_pre_working_hours=$this->remaining_days_of_month($leave_st_date,$leave_ed_date,$shift_hours,$country_id);
		$tally_pre_month_working_hours=floor($required_pre_working_hours['count_of_remaining_hours']-$payroll_prev_data['total_working_hours']);
		$leave_settlement_late=$this->payslip_list_data($user_id,$payment_date,$leave_st_date,$leave_ed_date);
		$count_of_remaining_hours=$required_pre_working_hours['count_of_remaining_hours'];
		$worked_hours=$leave_settlement_late['total_working_hours'];
		$actual_hours= $count_of_remaining_hours-$worked_hours;
		if($actual_hours < 0){
			return 0;
		}
		else{
			return $actual_hours;
		}
	}

	public function count_of_annual_days_of_month($annual_result_a,$salary_month_start_date,$salary_month_end_date,$country_id,$shift_hours){
		$check_ramadan_date=$this->Payroll_model->check_ramadan_date($country_id,$salary_month_start_date,$salary_month_end_date);
		$ramadan_reduced_hours='';
		$w_of_date=[];
		$w_of_hours='';
		if($check_ramadan_date){
			foreach($check_ramadan_date as $ramadan_date){
				$w_of_date[]=$ramadan_date['attendance_date'];
				$w_of_hours[$ramadan_date['attendance_date']]=$ramadan_date['reduced_hours'];
			}
		}

		$days_count=[];
		if($annual_result_a){
			foreach($annual_result_a as $annual_dates){
				if((strtotime($salary_month_start_date) <= strtotime($annual_dates['attendance_date'])) && (strtotime($salary_month_end_date) >= strtotime($annual_dates['attendance_date']))){
					if(in_array($annual_dates['attendance_date'],$w_of_date)){
						$days_count[]=$shift_hours-$w_of_hours[$attendance_date];
					}else{
						$days_count[]=$shift_hours;
					}
				}
			}
		}
		return array('count_of_annual'=>count($days_count),'count_of_annual_hours'=>array_sum($days_count));
	}

	public function payslip_list_data($emp_id='',$py_date='',$l_start_date='',$l_end_date='',$status='',$shift_hours){
		$employee_id = $emp_id;
		$month_year=$py_date;
		$p_date = $month_year;
		$payslip = $this->Payroll_model->get_employee_template($employee_id,0,0,$status);
		$system_settings = $this->Xin_model->read_setting_info(1);
		$p_date=date('Y-m',strtotime($p_date));
		$eligible_visa_under=json_decode($system_settings[0]->eligible_visa_under);
		$exceptional_employees=json_decode($system_settings[0]->exceptional_employees);
		$attendance=explode('-',$p_date);
		$days_count_per_month=cal_days_in_month(CAL_GREGORIAN,$attendance[1],$attendance[0]);
		$calendar_start_date=$p_date.'-01';
		$calendar_end_date=$p_date.'-'.$days_count_per_month;
		$s_end_date=array('exact_date_start'=>$l_start_date,'exact_date_end'=>$l_end_date);
		$data = array();
		foreach($payslip->result() as $r) {
			$full_name = $r->first_name.' '.$r->middle_name.' '.$r->last_name;
			$department_id = $r->department_id;
			$country_id = $r->country_id;
			$location_id = $r->office_location_id;
			$date_of_joining = $r->date_of_joining;
			$visa_type=$r->type;
			$ann_calculation=1;
			if(!in_array($visa_type,$eligible_visa_under)){
				$ann_calculation=0;
			}
			$check_ramadan_date=$this->Payroll_model->check_ramadan_date($country_id,$s_end_date['exact_date_start'],$s_end_date['exact_date_end']); //Normal Exceptional Time
			// Find Total Hours
			/*$d_hours=explode(':',$r->working_hours);
			if(@$d_hours[1]){ $d_mins=@$d_hours[1];} else {$d_mins='0';}
			$f_total_hours = new DateTime($d_hours[0].':'.$d_mins);
			$f_lunch_hours = new DateTime(LUNCH_HOURS);
			$interval_lunch = $f_total_hours->diff($f_lunch_hours);
			$total_work=$interval_lunch->format('%h').':'.$interval_lunch->format('%i');
			$shift_hours = decimalHours($total_work);*/
			// Find Total Hours

			$required_working_hours1=$this->required_working_hours($calendar_start_date,$calendar_end_date,$shift_hours,$country_id);
			$required_working_hours=$required_working_hours1['count_of_remaining_hours'];
			/*if($check_ramadan_date){
			$required_working_hours1=$this->required_working_hours($calendar_start_date,$calendar_end_date,$shift_hours,$country_id);
			$required_working_hours=$required_working_hours1['count_of_remaining_hours'];
			}else{
			$required_working_hours=required_working_hours($shift_hours,$p_date,$check_ramadan_date);
			}*/
			$unpaid_view ='';
			$check_end_effective_date = $this->Payroll_model->read_template_end_effective_date($r->user_id);
			$calculate_salary = $calculate_empty_status = $total_rest_hours = $total_count_of_attendance = $overtime_day = $overtime_night = $ob_rest_hours = $ob_tally_hours = $ob_amount = $ot_day_salary = $ot_night_salary = $overtime_day_hours = $overtime_night_hours = $grace = $ph_status = $maternityleave=0;
			$late_work_array = $leave_salary = $count_of_all_status = $count_of_leave_status = $sal_components = $count_of_leave_id=[];
			foreach($check_end_effective_date as $salary_d){
				$salary_effective_from_date=$salary_d->effective_from_date;
				if($salary_d->effective_to_date!=''){
					$salary_effective_to_date=$salary_d->effective_to_date;
				}else{
					$salary_effective_to_date=date('Y-m-d',strtotime('+2 month',strtotime(TODAY_DATE)));
				}
				if(strtotime($salary_effective_from_date) > strtotime($s_end_date['exact_date_start'])){
					$from_date=$salary_effective_from_date;
				}else{
					$from_date=$s_end_date['exact_date_start'];
				}
				if(strtotime($salary_effective_to_date) < strtotime($s_end_date['exact_date_end'])){
					$to_date=$salary_effective_to_date;

				}else{
					$to_date=$s_end_date['exact_date_end'];
				}
				if(strtotime($from_date) <= strtotime($to_date)){
					$salary_fields=$this->Payroll_model->get_salary_fields($salary_d->salary_template_id);
					if($salary_fields){
						$sal_components=[];
						foreach($salary_fields as $sal_key=>$sal_value){
							$sal_components[$sal_key]=$sal_value->salary_amount;
						}
						$salary_template_id=$salary_d->salary_template_id;
						if(@$salary_fields['basic_salary']->salary_amount){
							$basic_salary=@$salary_fields['basic_salary']->salary_amount;
						}else {$basic_salary=0;}
						if(@$salary_fields['house_rent_allowance']->salary_amount){
							$house_rent_allowance=@$salary_fields['house_rent_allowance']->salary_amount;
						}else {$house_rent_allowance=0;}
						if(@$salary_fields['bonus']->salary_amount){
							$bonus=@$salary_fields['bonus']->salary_amount;
						}else {$bonus=0;}
						if(@$salary_fields['agreed_bonus']->salary_amount){
							$agreed_bonus=@$salary_fields['agreed_bonus']->salary_amount;
						}else {$agreed_bonus=0;}
						$salary_with_bonus=$salary_d->salary_with_bonus;
						$rate_per_hour_contract_bonus=($salary_with_bonus/$required_working_hours);
						$rate_per_hour_contract_only=(($salary_with_bonus-$bonus)/$required_working_hours);
						$rate_per_hour_basic_only=($basic_salary/$required_working_hours);
						$ot_day_rate=($rate_per_hour_contract_only*1.25);
						$ot_night_rate=($rate_per_hour_contract_only*1.5);
						$ot_holiday_rate=($rate_per_hour_basic_only*1.5);
						//365 Days
						$rate_per_hour_contract_bonus_365=(($salary_with_bonus*12/365)/$shift_hours);
						$rate_per_hour_contract_only_365=((($salary_with_bonus-$bonus)*12/365)/$shift_hours);
						$rate_per_hour_basic_only_365=(($basic_salary*12/365)/$shift_hours);
						$ot_day_rate_365=($rate_per_hour_contract_only_365*1.25);
						$ot_night_rate_365=($rate_per_hour_contract_only_365*1.5);
						$ot_holiday_rate_365=($rate_per_hour_basic_only_365*1.5);
						//365 Days
						$count_of_all_status=$this->Payroll_model->count_of_all_status($from_date,$to_date,$r->user_id,$shift_hours,$check_ramadan_date,$department_id,$country_id,$location_id);
						if(!$count_of_all_status){
							$count_of_all_status=[];
						}
						$count_of_wo_status=$this->Payroll_model->check_weekoff_hours($r->user_id,$from_date,$to_date,$department_id,$country_id,$shift_hours);
						$count_of_wo_status=$count_of_wo_status;
						//if($ann_calculation==1){
						$count_of_leave_status=$this->Payroll_model->count_of_leave_status($from_date,$to_date,$r->user_id,$department_id, $country_id,'',$check_ramadan_date,$shift_hours);
						//}
						$count_of_leave_id=$this->Payroll_model->count_of_leave_id($from_date,$to_date,$r->user_id,$department_id,$country_id);
						if(!$count_of_leave_status){
							$count_of_leave_status=[];
						}
						$count_of_all_status=array_merge($count_of_all_status,$count_of_wo_status,$count_of_leave_status);
						//echo "<pre>";print_r($count_of_all_status);
						$ob_hours=check_manual_attendance_hours($r->user_id,$from_date,$to_date,$shift_hours,$department_id,$location_id,$country_id,$rate_per_hour_contract_bonus);
						//echo "<pre>";print_r($ob_hours);
						$ob_rest_hours+=$ob_hours['rest_hours'];
						$ob_tally_hours+=$ob_hours['tally_hours'];
						$ob_amount+=$ob_hours['ob_salary'];
						$calculate_salary_1=calculate_salary($r->user_id,$shift_hours,$count_of_all_status,$rate_per_hour_contract_bonus,$rate_per_hour_contract_only,$basic_salary,$house_rent_allowance,$maternityleave,$ann_calculation);
						//echo "<pre>";print_r($calculate_salary_1);
						if($calculate_salary_1){
							$calculate_salary+=$calculate_salary_1['total_salary_amount'];
							$calculate_empty_status+=$calculate_salary_1['total_empty_status'];
							$total_rest_hours+=$calculate_salary_1['total_rest_hours'];
							$total_count_of_attendance+=$calculate_salary_1['total_count_of_attendance'];
							$leave_salary[]=$calculate_salary_1['leave_salary'];
							$overtime_day+=$calculate_salary_1['overtime_day'];
							$overtime_night+=$calculate_salary_1['overtime_night'];
							$late_work_array[]= $this->Payroll_model->late_hours_worked($r->user_id,$from_date,$to_date,$date_of_joining,$rate_per_hour_contract_bonus,$department_id,$location_id);
							//print_r($late_work_array);
							//OT Calculation
							$overtime_day_object=secondsToTime($calculate_salary_1['overtime_day']);
							$overtime_night_object=secondsToTime($calculate_salary_1['overtime_night']);
							if($calculate_salary_1['overtime_day']!=0){
								$overtime_day_hours+=$calculate_salary_1['overtime_day'];
								$overtime_day_h=$overtime_day_object['h'].':'.$overtime_day_object['m'];
								$ot_day_salary+=(decimalHourswithoutround($overtime_day_h)*$ot_day_rate_365);
							}
							if($calculate_salary_1['overtime_night']!=0){
								$overtime_night_hours+=$calculate_salary_1['overtime_night'];
								$overtime_night_h=$overtime_night_object['h'].':'.$overtime_night_object['m'];
								$ot_night_salary+=(decimalHourswithoutround($overtime_night_h)*$ot_night_rate_365);
							}
							//OT Calculation
						}
					}
				}
			}
			$late_work_array=array_filter($late_work_array);
			$late_hours_value=0;
			if($late_work_array){
				foreach($late_work_array as $late_work_a){
					$late_hours_value+=$late_work_a['time_late'];
				}
			}
			$late_working_hours_object=secondsToTime($late_hours_value);
			$late_working_hours=$late_working_hours_object['h'].':'.$late_working_hours_object['m'];
			$late_working_hours=decimalHourswithoutround($late_working_hours);
			if($late_working_hours < 0)
				$late_working_hours=0;
			$total_working_hours=$total_rest_hours+$ob_rest_hours;
			$total_calculated_salary=$calculate_salary+$ob_amount;
			$leave_salary=array_filter($leave_salary);
			$count_of_leave_id=array_filter($count_of_leave_id);
			if((strtotime($s_end_date['exact_date_start']) <= strtotime($date_of_joining)) && (strtotime($s_end_date['exact_date_end']) >= strtotime($date_of_joining))){
				if(in_array($employee_id,$exceptional_employees)){
					$total_working_hours=$total_working_hours;
					$total_calculated_salary=$total_calculated_salary;
				}else{
					$total_working_h=$this->joining_date_hours($r->user_id,$date_of_joining,$shift_hours,$total_working_hours,$check_ramadan_date,$rate_per_hour_contract_bonus,$total_calculated_salary);
					$total_working_hours=$total_working_h['actual_hours'];
					$total_calculated_salary=$total_working_h['actual_salary'];
				}
			}else{
				$total_working_hours=$total_working_hours;
				$total_calculated_salary=$total_calculated_salary;
			}
			if($ob_tally_hours!=0){
				$ob_tally_h=decimalHours_reversewith_symbol(round($ob_tally_hours,2));
				$ob_tally_hour=explode(':',$ob_tally_h);
				$ob_tally='<tr><td>OB Hours<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.$ob_tally_hour[0].' h '.$ob_tally_hour[1].' m '.'</td><tr>';
				$ot_t_hour=$ob_tally_h;
			}else{
				$ob_tally='';
				$ot_t_hour='00:00';
			}
			if(in_array($r->user_id,$exceptional_employees)){$ot_hours_amount='';}else{
				/*OT Holiday Calculation Start*/
				$count_of_ot_holiday_status=$this->Payroll_model->count_of_ot_holiday_status($s_end_date['exact_date_start'],$s_end_date['exact_date_end'],$r->user_id,$department_id,$country_id);
				$count_of_ot_holiday_status=json_decode($count_of_ot_holiday_status);
				//print_r($count_of_ot_holiday_status);die;
				if(($count_of_ot_holiday_status->actual_working_hours!=0) || ($count_of_ot_holiday_status->ot_holiday_day!=0) || ($count_of_ot_holiday_status->ot_holiday_night!=0) || ($count_of_ot_holiday_status->total_work!=0)){
					$holiday_total_rest=$count_of_ot_holiday_status->actual_working_hours;
					$holiday_overtime_day=secondsToTime($count_of_ot_holiday_status->ot_holiday_day);
					$holiday_overtime_d=decimalHourswithoutround($holiday_overtime_day['h'].':'.$holiday_overtime_day['m']);
					$holiday_overtime_night=secondsToTime($count_of_ot_holiday_status->ot_holiday_night);
					$holiday_overtime_n=decimalHourswithoutround($holiday_overtime_night['h'].':'.$holiday_overtime_night['m']);
					$ov_holiday_salary=($holiday_total_rest+$holiday_overtime_d+$holiday_overtime_n)*$ot_holiday_rate_365;
					$overtime_night_object=secondsToTime(@$count_of_ot_holiday_status->ot_holiday_day+@$count_of_ot_holiday_status->ot_holiday_night+@$count_of_ot_holiday_status->total_work);
					$overtime_holiday_h=$overtime_night_object['h'].':'.$overtime_night_object['m'];
				}else{
					$overtime_holiday_h='00:00';
					$ov_holiday_salary=0;
				}
				$overtime_day_h=secondsToTime($overtime_day_hours);
				$overtime_day_h1=$overtime_day_h['h'].':'.$overtime_day_h['m'];
				$overtime_night_h=secondsToTime($overtime_night_hours);
				$overtime_night_h1=$overtime_night_h['h'].':'.$overtime_night_h['m'];
				$ot_hours_amount=array('ot_day_hours'=>$overtime_day_h1,'ot_day_amount'=>$ot_day_salary,'ot_night_hours'=>$overtime_night_h1,'ot_night_amount'=>$ot_night_salary,'ot_holiday_hours'=>$overtime_holiday_h,'ot_holiday_amount'=>$ov_holiday_salary,'ob_hours'=>$ot_t_hour,'ob_amount'=>$ob_amount);
			}
			if($total_calculated_salary==0){
				$total_working_hours=0;
			}
			$data = array(
				'user_id'=>$r->user_id,
				'employee_id'=>$r->employee_id,
				'employee_name'=>$full_name,
				'basic_salary'=>@$basic_salary,
				'salary_template_id'=>@$salary_template_id,
				'salary_with_bonus'=>@$salary_with_bonus,
				'bonus'=>@$bonus,
				'agreed_bonus'=>@$agreed_bonus,
				'department_id'=>$r->department_id,
				'designation_id'=>$r->designation_id,
				'company_id'=>$r->company_id,
				'office_location_id'=>$r->office_location_id,
				'required_working_hours'=>@$required_working_hours,
				'total_working_hours'=>@$total_working_hours,
				'late_working_hours'=>@$late_working_hours,
				'rate_per_hour_contract_bonus'=>@$rate_per_hour_contract_bonus,
				'rate_per_hour_contract_only'=>@$rate_per_hour_contract_only,
				'rate_per_hour_basic_only'=>@$rate_per_hour_basic_only,
				'ot_day_rate'=>@$ot_day_rate,
				'ot_night_rate'=>@$ot_night_rate,
				'ot_holiday_rate'=>@$ot_holiday_rate,
				'ot_hours_amount' =>json_encode(@$ot_hours_amount),
				'leave_salary'=>json_encode(@$leave_salary),
				'late_work_array'=>json_encode(@$late_work_array),
				'leave_salary_paid'=>json_encode(@$count_of_leave_id),
				'total_salary'=>@$total_calculated_salary,
				//'actual_days_worked'=>$actual_days_worked,
				'country_id'=>$country_id,
				'late_hours_value'=>@$late_hours_value,
				'check_ramadan_date'=>json_encode(@$check_ramadan_date)
			);

			$data=array_merge($data,$sal_components);
			return $data;
		}
	}

	public function payslip_list_new($emp_id='',$py_date='',$l_start_date='',$l_end_date='',$status='')
	{
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("payroll/generate_payslip", $data);
		} else {
			redirect('');
		}
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		$limit = intval($this->input->get("limit"));
		$location_value=$this->input->get("location_value");
		$department_value=$this->input->get("department_value");

		if($emp_id=='' && $py_date==''){
			if($this->input->get("employee_id")){
				$employee_id = $this->input->get("employee_id");
				$p_date = $this->input->get("month_year");
				$payslip = $this->Payroll_model->get_employee_template($employee_id,$location_value,$department_value,$status);
			} else {
				$p_date = $this->input->get("month_year");
				$payslip = $this->Payroll_model->get_employee_template('',$location_value,$department_value,$status);
			}
			$month_year=$this->input->get('month_year');
		}
		else{
			$employee_id = $emp_id;
			$month_year=$py_date;
			$p_date = $month_year;
			$payslip = $this->Payroll_model->get_employee_template($employee_id,0,0,$status);
		}
		$system_settings = $this->Xin_model->read_setting_info(1);
		$p_date=date('Y-m',strtotime($p_date));
		$eligible_visa_under=json_decode($system_settings[0]->eligible_visa_under);
		$exceptional_employees=json_decode($system_settings[0]->exceptional_employees);
		$monthly_target_count=$system_settings[0]->monthly_target_count;
		$order_cancellation_percentage=$system_settings[0]->order_cancellation_percentage;

		$cut_off_dates=salary_start_end_date($p_date);
		$cut_off_start_date=$cut_off_dates['exact_date_start'];
		$cut_off_end_date=$cut_off_dates['exact_date_end'];
		$attendance=explode('-',$p_date);
		$days_count_per_month=cal_days_in_month(CAL_GREGORIAN,$attendance[1],$attendance[0]);
		$calendar_start_date=$p_date.'-01';
		$calendar_end_date=$p_date.'-'.$days_count_per_month;
		$calendar_end_date_2months=date("Y-m-d",strtotime("last day of +2 month",strtotime($calendar_end_date)));
		$calendar_end_date_1months=date("Y-m-d",strtotime("+10 days",strtotime($calendar_end_date)));
		$pre_month=date('Y-m',strtotime("-1 month",strtotime($p_date)));
		$pre_month_start_date=$cut_off_start_date;
		$pre_month_end_date=date('Y-m-d',strtotime("-1 Day",strtotime($calendar_start_date)));
		$pre_check_leave_start_date=date('Y-m-d',strtotime("-10 Day",strtotime($pre_month_start_date)));
		$current_month_start_date=$calendar_start_date;
		$current_month_end_date=$cut_off_end_date;
		$assuming_month_start_date=date('Y-m-d',strtotime("+1 Day",strtotime($cut_off_end_date)));
		$assuming_month_end_date=$calendar_end_date;
		$annual_id_a=$this->Timesheet_model->read_leave_type_id('Annual Leave');
		$check_365days_enabled=0;
		$data = array();
		foreach($payslip->result() as $r) {
			$full_name = $r->first_name.' '.$r->middle_name.' '.$r->last_name;
			$department_id = $r->department_id;
			$country_id = $r->country_id;
			$location_id = $r->office_location_id;
			$date_of_joining = $r->date_of_joining;
			$visa_type=$r->type;
			$visa_name=$r->visa_name;
			$date_of_leaving=$r->date_of_leaving;
			$ann_calculation=1;
			if(!in_array($visa_type,$eligible_visa_under)){
				$ann_calculation=0;
			}
			$department = $this->Department_model->read_department_information($department_id);
			$department_name=$department[0]->department_name;
			$grace_eligible=grace_eligible($department_id,$location_id);
			// Find Total Hours
			$d_hours=explode(':',$r->working_hours);
			if(@$d_hours[1]){ $d_mins=@$d_hours[1];} else {$d_mins='0';}
			$f_total_hours = new DateTime($d_hours[0].':'.$d_mins);
			$f_lunch_hours = new DateTime(LUNCH_HOURS);
			$interval_lunch = $f_total_hours;
			if($r->is_break_included == 1)
				$interval_lunch = $f_total_hours->diff($f_lunch_hours);

			$total_work=$interval_lunch->format('%h').':'.$interval_lunch->format('%i');
			$shift_hours = decimalHours($total_work);
			// Find Total Hours

			$required_working_hours=$this->required_working_hours($calendar_start_date,$calendar_end_date,$shift_hours,$country_id);
			if($department_name=='TD' && $visa_name!='DMCC'){
				$prev_month_data=[];
				$assuming_month_data=[];
				$a_m_leave_paid_salary=[];
				$a_m_leave_salary=[];
				if(strtotime($calendar_start_date) < strtotime($calendar_end_date)){
					if((strtotime($calendar_start_date) <= strtotime($date_of_joining)) && (strtotime($calendar_end_date) >= strtotime($date_of_joining))){
						$current_month_data=$this->payslip_list_data($r->user_id,$p_date,$date_of_joining,$calendar_end_date,1,$shift_hours);
					}else{

						if((strtotime($calendar_start_date) <= strtotime($date_of_leaving)) && (strtotime($calendar_end_date) >= strtotime($date_of_leaving))){
							$current_month_data=$this->payslip_list_data($r->user_id,$p_date,$calendar_start_date,$date_of_leaving,1,$shift_hours);
						}else{
							$current_month_data=$this->payslip_list_data($r->user_id,$p_date,$calendar_start_date,$calendar_end_date,1,$shift_hours);
						}
					}
				}else{
					$current_month_data=[];
				}
			}else{
				if(strtotime($pre_month_start_date) < strtotime($pre_month_end_date)){
					if((strtotime($pre_month_start_date) <= strtotime($date_of_joining)) && (strtotime($pre_month_end_date) >= strtotime($date_of_joining))){
						$prev_month_data=$this->payslip_list_data($r->user_id,$pre_month,$date_of_joining,$pre_month_end_date,1,$shift_hours);
					}else{
						$prev_month_data=$this->payslip_list_data($r->user_id,$pre_month,$pre_month_start_date,$pre_month_end_date,1,$shift_hours);
					}}else{
					$prev_month_data=[];
				}
				if(strtotime($current_month_start_date) < strtotime($current_month_end_date)){
					if((strtotime($current_month_start_date) <= strtotime($date_of_joining)) && (strtotime($current_month_end_date) >= strtotime($date_of_joining))){
						$current_month_data=$this->payslip_list_data($r->user_id,$p_date,$date_of_joining,$current_month_end_date,1,$shift_hours);
					}else{
						if((strtotime($current_month_start_date) <= strtotime($date_of_leaving)) && (strtotime($current_month_end_date) >= strtotime($date_of_leaving))){
							$current_month_data=$this->payslip_list_data($r->user_id,$p_date,$current_month_start_date,$date_of_leaving,1,$shift_hours);
						}else{
							$current_month_data=$this->payslip_list_data($r->user_id,$p_date,$current_month_start_date,$current_month_end_date,1,$shift_hours);
						}
					}
				}else{
					$current_month_data=[];
				}

				if((strtotime($current_month_start_date) <= strtotime($date_of_leaving)) && (strtotime($current_month_end_date) >= strtotime($date_of_leaving))){
					$assuming_month_data=[];
					$a_m_leave_paid_salary=[];
					$a_m_leave_salary=[];
				}else{
					if(strtotime($assuming_month_start_date) < strtotime($assuming_month_end_date)){
						if((strtotime($assuming_month_start_date) <= strtotime($date_of_leaving)) && (strtotime($assuming_month_end_date) >= strtotime($date_of_leaving))){
							$assuming_month_data=$this->payslip_list_data($r->user_id,$p_date,$assuming_month_start_date,$date_of_leaving,1,$shift_hours);
							$a_m_leave_paid_salary=json_decode($assuming_month_data['leave_salary_paid']);
							$a_m_leave_salary=json_decode($assuming_month_data['leave_salary']);
						}else{
							$assuming_month_data=$this->payslip_list_data($r->user_id,$p_date,$assuming_month_start_date,$assuming_month_end_date,1,$shift_hours);
							$a_m_leave_paid_salary=json_decode($assuming_month_data['leave_salary_paid']);
							$a_m_leave_salary=json_decode($assuming_month_data['leave_salary']);
						}
					}else{
						$assuming_month_data=[];
						$a_m_leave_paid_salary=[];
						$a_m_leave_salary=[];
					}
				}
			}
			//print_r($prev_month_data);
			//print_r($current_month_data);
			//print_r($assuming_month_data);

			$salary_components=array_slice($current_month_data, 29);
			//Prev Month 21-30
			$p_m_required_working_hours=$prev_month_data['required_working_hours'];
			$p_m_total_working_hours=$prev_month_data['total_working_hours'];
			$p_m_late_working_hours=$prev_month_data['late_working_hours'];
			$p_m_total_salary=$prev_month_data['total_salary'];
			$p_m_rate_per_hour_contract_bonus=$prev_month_data['rate_per_hour_contract_bonus'];
			$p_m_rate_per_hour_contract_only=$prev_month_data['rate_per_hour_contract_only'];
			$p_m_rate_per_hour_basic_only=$prev_month_data['rate_per_hour_basic_only'];
			$p_m_ot_day_rate=$prev_month_data['ot_day_rate'];
			$p_m_ot_night_rate_worked=$prev_month_data['ot_night_rate'];
			$p_m_ot_holiday_rate=$prev_month_data['ot_holiday_rate'];
			$p_m_ot_hours_amount=json_decode($prev_month_data['ot_hours_amount']);
			$p_m_leave_salary=json_decode($prev_month_data['leave_salary']);	$p_m_leave_paid_salary=json_decode($prev_month_data['leave_salary_paid']);
			$p_m_late_work_array=json_decode($prev_month_data['late_work_array']);
			$p_m_late_hours_value=$prev_month_data['late_hours_value'];
			//Prev Month 21-30
			//Current Month 1-20

			$c_m_total_working_hours=$current_month_data['total_working_hours'];
			$c_m_late_working_hours=$current_month_data['late_working_hours'];
			$c_m_total_salary=$current_month_data['total_salary'];
			$c_m_required_working_hours=$required_working_hours['count_of_remaining_hours'];
			$c_m_rate_per_hour_contract_bonus=($current_month_data['salary_with_bonus']/$c_m_required_working_hours);
			$c_m_rate_per_hour_contract_only=(($current_month_data['salary_with_bonus']-$current_month_data['bonus'])/$c_m_required_working_hours);
			$c_m_rate_per_hour_basic_only=$current_month_data['basic_salary']/$c_m_required_working_hours;
			$c_m_ot_day_rate=($c_m_rate_per_hour_contract_only*1.25);
			$c_m_ot_night_rate=($c_m_rate_per_hour_contract_only*1.5);
			$c_m_ot_holiday_rate=($c_m_rate_per_hour_basic_only*1.5);
			$c_m_ot_hours_amount=json_decode($current_month_data['ot_hours_amount']);
			$c_m_leave_salary=json_decode($current_month_data['leave_salary']);
			$c_m_late_hours_value=$current_month_data['late_hours_value'];
			$c_m_leave_paid_salary=json_decode($current_month_data['leave_salary_paid']);
			$c_m_late_work_array=json_decode($current_month_data['late_work_array']);
			$check_ramadan_date=json_decode($current_month_data['check_ramadan_date']);
			//Current Month 1-20

			//Assuming Month 18-21
			$a_m_total_working_hours=$assuming_month_data['total_working_hours'];
			$a_m_late_working_hours=$assuming_month_data['late_working_hours'];
			$a_m_total_salary=$assuming_month_data['total_salary'];
			$a_m_late_work_array=json_decode($assuming_month_data['late_work_array']);
			$a_m_ot_hours_amount=json_decode($assuming_month_data['ot_hours_amount']);
			$a_m_late_hours_value=$assuming_month_data['late_hours_value'];
			//Assuming Month 28-31
			$ramadan_date=[];
			if($check_ramadan_date){
				foreach($check_ramadan_date as $ramadan_date1){
					$ramadan_date[]=array('attendance_date'=>$ramadan_date1->attendance_date,'reduced_hours'=>$ramadan_date1->reduced_hours);
				}
			}
			$check_ramadan_date=$ramadan_date;
			$pre_leave_status=$this->Payroll_model->count_of_leave_status_new($pre_month_start_date,$pre_month_end_date,$r->user_id,$department_id, $country_id,'',$check_ramadan_date,$shift_hours,$p_m_required_working_hours);
			$p_m_leave_salary_id=$this->Payroll_model->count_of_leave_id($pre_check_leave_start_date,$pre_month_end_date,$r->user_id,$department_id, $country_id,'',$check_ramadan_date,$shift_hours);
			$pre_count_of_leave_days=0;
			$pre_count_of_rest_hours=0;
			$pre_count_of_salary_amount=0;
			foreach($pre_leave_status as $pre_leave){
				/*if($pre_leave->attendance_status=='AA'){
				$pre_count_of_leave_days+=$pre_leave->count_attendance_status;
				$pre_count_of_rest_hours+=$pre_leave->total_rest;
				$pre_count_of_salary_amount+=$pre_leave->salary_count;
				}*/
			}
			//Annual Leave Count
			$result_annual_query_res=$this->Payroll_model->check_leaves_occupiedwithdate_counts($annual_id_a,$r->user_id,$pre_month_start_date,$calendar_end_date_1months);
			if($result_annual_query_res){
				$leave_start_date=$result_annual_query_res['leave_start_date'];
				$leave_end_date=$result_annual_query_res['leave_end_date'];
			}else{$leave_start_date='';$leave_end_date='';}
			$result_query_id=$this->Payroll_model->count_of_leave_id_cal($pre_month_start_date,$calendar_end_date_1months,$calendar_end_date,$r->user_id,$department_id, $country_id,'',$check_ramadan_date,$shift_hours,'annual_leave');
			$count_of_leave_id_array=[];

			if($c_m_leave_paid_salary){
				foreach($c_m_leave_paid_salary as $count_leave_c_m){
					foreach($count_leave_c_m as $key_c_m=>$value_c_m){
						$count_of_leave_id_array[$key_c_m]=array('leave_id'=>$value_c_m->leave_id,'leave_status_code'=>$value_c_m->leave_status_code,'attendance_date'=>$value_c_m->attendance_date,'leave_type_id'=>$value_c_m->leave_type_id,'type_name'=>$value_c_m->type_name);
					}
				}
			}
			if($p_m_leave_salary_id){
				foreach($p_m_leave_salary_id as $p_m_leave){
					foreach($p_m_leave as $key_l=>$value_l){
						$count_of_leave_id_array[$value_l['attendance_date']]=array('leave_id'=>$value_l['leave_id'],'leave_status_code'=>$value_l['leave_status_code'],'attendance_date'=>$value_l['attendance_date'],'leave_type_id'=>$value_l['leave_type_id'],'type_name'=>$value_l['type_name']);
					}

				}
			}
			if($a_m_leave_paid_salary){
				foreach($a_m_leave_paid_salary as $count_leave_a_m){
					foreach($count_leave_a_m as $key_a_m=>$value_a_m){
						$count_of_leave_id_array[$key_a_m]=array('leave_id'=>$value_a_m->leave_id,'leave_status_code'=>$value_a_m->leave_status_code,'attendance_date'=>$value_a_m->attendance_date,'leave_type_id'=>$value_a_m->leave_type_id,'type_name'=>$value_a_m->type_name);
					}
				}
			}

			$result_annual_query_id=[];
			if($result_query_id){
				foreach($result_query_id as $count_leave){
					foreach($count_leave as $key_l=>$value_l){
						if($value_l['leave_status_code']=='AL'){
							$result_annual_query_id[]=$value_l['attendance_date'];
							$count_of_leave_id_array[$value_l['attendance_date']]=array('leave_id'=>$value_l['leave_id'],'leave_status_code'=>$value_l['leave_status_code'],'attendance_date'=>$value_l['attendance_date'],'leave_type_id'=>$value_l['leave_type_id'],'type_name'=>$value_l['type_name']);}else{
							$count_of_leave_id_array[$value_l['attendance_date']]=array('leave_id'=>$value_l['leave_id'],'leave_status_code'=>$value_l['leave_status_code'],'attendance_date'=>$value_l['attendance_date'],'leave_type_id'=>$value_l['leave_type_id'],'type_name'=>$value_l['type_name']);
						}
					}
				}
			}

			//Annual Leave Count
			$annual_leave_salary=0;
			$annual_leave_rest=0;
			$annual_leave_array=[];
			$result_annual_query=count($result_annual_query_id);
			if($ann_calculation==1 && $result_annual_query_id){
				$annual_leave_salary =((($current_month_data['basic_salary']+$current_month_data['house_rent_allowance'])*12)/365)*$result_annual_query;
				$annual_leave_rest=$result_annual_query*$shift_hours;
				$annual_leave_array=array(0=> (object) array('AL'=> (object) array('days'=>$result_annual_query,'amount'=>$annual_leave_salary,'total_rest'=>$annual_leave_rest)));

			}


			if(!$p_m_leave_salary){$p_m_leave_salary=[];}
			if(!$c_m_leave_salary){$c_m_leave_salary=[];}
			$leave_array=array_merge($c_m_leave_salary,$p_m_leave_salary,$annual_leave_array);
			$leave_salary_paid=$count_of_leave_id_array;


			if((strtotime($current_month_start_date) <= strtotime($date_of_leaving)) && (strtotime($current_month_end_date) >= strtotime($date_of_leaving))){
				$assuming_count_of_leave_days=0;
				$assuming_count_of_rest_hours=0;
				$assuming_count_of_salary_amount=0;
			}else if((strtotime($assuming_month_start_date) <= strtotime($date_of_leaving)) && (strtotime($assuming_month_end_date) >= strtotime($date_of_leaving))){
				$assuming_count_of_leave_days=0;
				$assuming_count_of_rest_hours=0;
				$assuming_count_of_salary_amount=0;
			}else{
				//check
				$assuming_leave_status=$this->Payroll_model->count_of_leave_status_new($assuming_month_start_date,$assuming_month_end_date,$r->user_id,$department_id, $country_id,'',$check_ramadan_date,$shift_hours,$c_m_required_working_hours);
				$assuming_count_of_leave_days=0;
				$assuming_count_of_rest_hours=0;
				$assuming_count_of_salary_amount=0;
				foreach($assuming_leave_status as $assuming_leave){
					//if($assuming_leave->attendance_status!='SL-1' && $assuming_leave->attendance_status!='SL-2' && $assuming_leave->attendance_status!='SL-UP' && $assuming_leave->attendance_status!='ML-1' && $assuming_leave->attendance_status!='ML-2' && $assuming_leave->attendance_status!='ML-UP'  && $assuming_leave->attendance_status!='EL'){
					if($assuming_leave->attendance_status!='PH'){
						$assuming_count_of_leave_days+=$assuming_leave->count_attendance_status;
						$assuming_count_of_rest_hours+=$assuming_leave->total_rest;
						$assuming_count_of_salary_amount+=$assuming_leave->salary_count;
					}
				}
			}

			$pre_working_hours=$this->remaining_days_of_monthwithsalary($pre_month_start_date,$pre_month_end_date,$shift_hours,$country_id,$r->user_id,$p_m_required_working_hours);
			if((strtotime($current_month_start_date) <= strtotime($date_of_leaving)) && (strtotime($current_month_end_date) >= strtotime($date_of_leaving))){
				$assuming_working_hours['count_of_remaining_days']=0;
				$assuming_working_hours['count_of_remaining_hours']=0;
				$assuming_working_hours['salary_count']=0;
			}else if((strtotime($assuming_month_start_date) <= strtotime($date_of_leaving)) && (strtotime($assuming_month_end_date) >= strtotime($date_of_leaving))){
				$assuming_working_hours['count_of_remaining_days']=0;
				$assuming_working_hours['count_of_remaining_hours']=0;
				$assuming_working_hours['salary_count']=0;
			}else{
				$assuming_working_hours=$this->remaining_days_of_monthwithsalary($assuming_month_start_date,$assuming_month_end_date,$shift_hours,$country_id,$r->user_id,$c_m_required_working_hours);
			}
			$ot_day_rate_p=@$p_m_ot_hours_amount->ot_day_amount;
			$ot_night_rate_p=@$p_m_ot_hours_amount->ot_night_amount;
			$ot_holiday_rate_p=@$p_m_ot_hours_amount->ot_holiday_amount;
			$ot_day_rate_c=@$c_m_ot_hours_amount->ot_day_amount;
			$ot_night_rate_c=@$c_m_ot_hours_amount->ot_night_amount;
			$ot_holiday_rate_c=@$c_m_ot_hours_amount->ot_holiday_amount;
			$ob_rate_c=@$c_m_ot_hours_amount->ob_amount;
			$ob_rate_p=@$p_m_ot_hours_amount->ob_amount;
			$ob_rate_a=@$a_m_ot_hours_amount->ob_amount;

			$ot_day_hours_p=decimalHours($p_m_ot_hours_amount->ot_day_hours);
			$ot_night_hours_p=decimalHours($p_m_ot_hours_amount->ot_night_hours);
			$ot_holiday_hours_p=decimalHours($p_m_ot_hours_amount->ot_holiday_hours);
			$ot_day_hours_c=decimalHours($c_m_ot_hours_amount->ot_day_hours);
			$ot_night_hours_c=decimalHours($c_m_ot_hours_amount->ot_night_hours);
			$ot_holiday_hours_c=decimalHours($c_m_ot_hours_amount->ot_holiday_hours);
			$ob_hours_c=decimalHours($c_m_ot_hours_amount->ob_hours);
			$ob_hours_p=decimalHours($p_m_ot_hours_amount->ob_hours);
			$ob_hours_a=decimalHours($a_m_ot_hours_amount->ob_hours);

			$ot_day_rate_a=$a_m_ot_hours_amount->ot_day_amount;
			$ot_night_rate_a=$a_m_ot_hours_amount->ot_night_amount;
			$ot_holiday_rate_a=$a_m_ot_hours_amount->ot_holiday_amount;
			$ot_day_hours_a=decimalHours($a_m_ot_hours_amount->ot_day_hours);
			$ot_night_hours_a=decimalHours($a_m_ot_hours_amount->ot_night_hours);
			$ot_holiday_hours_a=decimalHours($a_m_ot_hours_amount->ot_holiday_hours);

			if((strtotime($assuming_month_start_date) <= strtotime($date_of_leaving)) && (strtotime($assuming_month_end_date) >= strtotime($date_of_leaving))){
			}else if($department_name=='TD' && $visa_name!='DMCC'){
				$ob_hours_a=0;
				$ot_day_rate_a=0;
				$ot_night_rate_a=0;
				$ot_holiday_rate_a=0;
				$ot_day_hours_a=0;
				$ot_night_hours_a=0;
				$ot_holiday_hours_a=0;
			}else{
				$ob_hours_a=0;
				$ot_day_rate_a=0;
				$ot_night_rate_a=0;
				$ot_holiday_rate_a=0;
				$ot_day_hours_a=0;
				$ot_night_hours_a=0;
				$ot_holiday_hours_a=0;
			}


			//$pre_month_end_date
			$required_working_hours=$c_m_required_working_hours;
			$taken_leave_status=$this->Payroll_model->count_of_leave_status_paid($pre_month_start_date,$pre_month_end_date,$r->user_id,$department_id, $country_id,'',$check_ramadan_date,$shift_hours,$p_m_required_working_hours);
			$taken_count_of_leave_days=0;
			$taken_count_of_rest_hours=0;
			$taken_count_of_salary_amount=0;
			foreach($taken_leave_status as $taken_leave){
				$taken_count_of_leave_days+=$taken_leave->count_attendance_status;
				$taken_count_of_rest_hours+=$taken_leave->total_rest;
				$taken_count_of_salary_amount+=$taken_leave->salary_count;
			}

			$joining_month_salary=0;
			$joining_month_hours=0;
			$total_salary=0;
			if($department_name=='TD' && $visa_name!='DMCC'){
				$total_working_hours=$c_m_total_working_hours;
				$total_salary=$c_m_total_salary;
				$pre_m_required_working_hours=$p_m_required_working_hours;
			}
			else{
				if((strtotime($pre_month_start_date) <= strtotime($date_of_joining)) && (strtotime($pre_month_end_date) >= strtotime($date_of_joining))){
					$total_working_hours=$c_m_total_working_hours+($assuming_working_hours['count_of_remaining_hours']-$assuming_count_of_rest_hours-$pre_count_of_rest_hours);
					$total_salary=$c_m_total_salary+($assuming_working_hours['salary_count']-$assuming_count_of_salary_amount-$pre_count_of_salary_amount);
					$joining_month_hours=$p_m_total_working_hours;
					$joining_month_salary=$p_m_total_salary;
					$pre_m_required_working_hours=$p_m_required_working_hours;
				}
				else if((strtotime($current_month_start_date) <= strtotime($date_of_joining)) && (strtotime($current_month_end_date) >= strtotime($date_of_joining))){
					$total_working_hours=$c_m_total_working_hours+($assuming_working_hours['count_of_remaining_hours']-$assuming_count_of_rest_hours);
					$total_salary=$c_m_total_salary+($assuming_working_hours['salary_count']-$assuming_count_of_salary_amount);
					$pre_m_required_working_hours=$c_m_required_working_hours;
				}
				else{
					$pre_m_required_working_hours=$c_m_required_working_hours;
					$pre_work_hours=($pre_working_hours['count_of_remaining_hours']-($p_m_total_working_hours+$taken_count_of_rest_hours));
					$pre_total_salary=($pre_working_hours['salary_count']-($p_m_total_salary+$taken_count_of_salary_amount));
					if($pre_work_hours < 0){$pre_work_hours=0;}
					if($pre_total_salary < 0){$pre_total_salary=0;}
					if((strtotime($assuming_month_start_date) <= strtotime($date_of_leaving)) && (strtotime($assuming_month_end_date) >= strtotime($date_of_leaving))){
						$total_working_hours=($c_m_total_working_hours)+$a_m_total_working_hours-($pre_work_hours-$pre_count_of_rest_hours);
						$total_salary=($c_m_total_salary)+$a_m_total_salary-($pre_total_salary-$pre_count_of_salary_amount);
					}else{
						$total_working_hours=($c_m_total_working_hours)+($assuming_working_hours['count_of_remaining_hours']-$assuming_count_of_rest_hours)-($pre_work_hours-$pre_count_of_rest_hours);
						$total_salary=($c_m_total_salary)+($assuming_working_hours['salary_count']-$assuming_count_of_salary_amount)-($pre_total_salary-$pre_count_of_salary_amount);
					}
				}
			}
			$late_hours_value=0;
			$late_hours_amount=0;
			if($p_m_late_work_array){
				foreach($p_m_late_work_array as $p_m_late_array){
					$late_hours_value+=$p_m_late_array->time_late;
					$late_hours_amount+=$p_m_late_array->time_late_amount;
				}
			}
			if($c_m_late_work_array){
				foreach($c_m_late_work_array as $c_m_late_array){
					$late_hours_value+=$c_m_late_array->time_late;
					$late_hours_amount+=$c_m_late_array->time_late_amount;
				}
			}
			if((strtotime($assuming_month_start_date) <= strtotime($date_of_leaving)) && (strtotime($assuming_month_end_date) >= strtotime($date_of_leaving))){
				if($a_m_late_work_array){
					foreach($a_m_late_work_array as $a_m_late_array){
						$late_hours_value+=$a_m_late_array->time_late;
						$late_hours_amount+=$a_m_late_array->time_late_amount;
					}
				}
			}

			$late_working_hours_v=secondsToTime($late_hours_value);
			$late_working_hours=$late_working_hours_v['h'].':'.$late_working_hours_v['m'];
			if($late_hours_value <= GRACE_PERIOD_MINUTES && $grace_eligible==1){
				if(in_array(@$employee_id,$exceptional_employees)){
					if((strtotime($assuming_month_start_date) <= strtotime($date_of_leaving)) && (strtotime($assuming_month_end_date) >= strtotime($date_of_leaving))){
						$total_working_hours=$total_working_hours+$c_m_late_working_hours+$a_m_late_working_hours;
						$total_salary=$total_salary;
					}else{
						$total_working_hours=$total_working_hours+$c_m_late_working_hours;
						$total_salary=$total_salary;
					}
				}else{
					if((strtotime($assuming_month_start_date) <= strtotime($date_of_leaving)) && (strtotime($assuming_month_end_date) >= strtotime($date_of_leaving))){
						$total_working_hours=$total_working_hours+$c_m_late_working_hours+$p_m_late_working_hours+$a_m_late_working_hours;
						$total_salary=$total_salary+$late_hours_amount;
					}else{
						$total_working_hours=$total_working_hours+$c_m_late_working_hours+$p_m_late_working_hours;
						$total_salary=$total_salary+$late_hours_amount;
					}
				}
				$late_working_hours=0;
			}else{
				$late_working_hours=$late_working_hours;
			}
			$late_slug=	$late_working_hours.' ('.decimalHours($late_working_hours).')';

			/* When total working hours goes greater than required working hours*/
			if(($total_working_hours > $required_working_hours) ){//&& (strtotime($p_date) < strtotime('Y-m'))
				$total_working_hours = $required_working_hours;
				$total_salary = $current_month_data['salary_with_bonus'];
			}
			/* When total working hours goes greater than required working hours*/

			$check_365days_enabled=check_365days_enabled($calendar_start_date,$calendar_end_date,$r->user_id,$shift_hours,$check_ramadan_date,$department_id,$country_id,$location_id,$date_of_joining,$date_of_leaving);
			if($check_365days_enabled==1){
				$total_salary=$current_month_data['salary_with_bonus']-(($current_month_data['required_working_hours']-$total_working_hours)*(($current_month_data['salary_with_bonus']*12))/365/$shift_hours);
			}
			if($total_working_hours!=0){
				$accumulate_salary=$total_salary+$joining_month_salary+$annual_leave_salary;
			}
			else{
				$accumulate_salary=0;$total_salary=0;
			}


			$actual_days_worked=($total_working_hours/$required_working_hours)*$days_count_per_month;
			$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id,$p_date);
			$payment_check=count($make_payment);
			if($payment_check > 0){
				$functions='<li><a href="'.site_url().'payroll/payslip/id/'.$make_payment[0]->make_payment_id.'/" class="text-success"><i class="icon-printer"></i> Generate Payslip</a></li>';
				$p_details='<li><a href="#" data-toggle="modal" data-target=".detail_modal_data" data-employee_id="'. $r->user_id . '" data-pay_id="'. $make_payment[0]->make_payment_id . '"><i class="icon-eye4"></i> View Details</a></li>';
			} else {
				//if(@$total_salary > 0) {
				$functions='<li><a href="#" data-toggle="modal" data-target=".emo_monthly_pay" data-employee_id="'. $r->user_id . '" data-payment_date="'. $p_date . '"><i class="icon-cash"></i> Make Payment</a><script>
					function make_pay_ment(id){$("#mak_payment_submit_"+id).submit();}</script></li>';
				/*} else {
				    $functions='<li><a href="#" class="text-danger">You can not make payment because net salary is 0</a></li>';
					}*/

				$p_details = '<li><a href="#" data-toggle="modal" data-target=".small-view-modal" data-employee_id="'. $r->user_id . '"><i class="icon-eye4"></i> View Details</a></li>';
			}

			$overtime_hours='<i style="cursor:pointer;" data-html="true" class="ml-10 icon-bubble-dots4 position-right text-teal-400" data-popup="popover-custom" data-placement="top" title="Over Time Hours" data-trigger="hover" data-content="<table><tr><td>OT - Day<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.decimalHoursFormat_h($ot_day_hours_c+$ot_day_hours_p+$ot_day_hours_a).'</td><tr><td>OT - Night<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.decimalHoursFormat_h($ot_night_hours_p+$ot_night_hours_c+$ot_night_hours_a).'</td><tr><td>OT - Holiday<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.decimalHoursFormat_h($ot_holiday_hours_c+$ot_holiday_hours_p+$ot_holiday_hours_a).'</td><tr><tr><td>OB Hours<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.decimalHoursFormat_h($ob_hours_c+$ob_hours_p+$ob_hours_a).'</td><tr><table>"></i>';

			$al_detailed_salary='';
			if($annual_leave_salary!=0){
				$al_detailed_salary='<tr><td>AL Salary<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.$this->Xin_model->currency_sign(change_num_format($annual_leave_salary),'',$r->user_id).'</td></tr>';
			}
			$join_detailed_salary='';
			if($joining_month_salary!=0){
				$join_detailed_salary='<tr><td>Joining Month Salary<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.$this->Xin_model->currency_sign(change_num_format($joining_month_salary),'',$r->user_id).'</td></tr>';
			}

			$detailed_salary='<i style="cursor:pointer;" data-html="true" class="ml-10 icon-bubble-dots4 position-right text-teal-400" data-popup="popover-custom" data-placement="left" title="Salary Details" data-trigger="hover" data-content="<table><tr><td>Total Salary<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.$this->Xin_model->currency_sign(change_num_format($total_salary),'',$r->user_id).'</td></tr><tr><td>OT Day Salary<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.$this->Xin_model->currency_sign(change_num_format($ot_day_rate_p+$ot_day_rate_c+$ot_day_rate_a),'',$r->user_id).'</td></tr><tr><td>OT Night Salary<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.$this->Xin_model->currency_sign(change_num_format($ot_night_rate_p+$ot_night_rate_c+$ot_night_rate_a),'',$r->user_id).'</td></tr><tr><td>OT Holiday Salary<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.$this->Xin_model->currency_sign(change_num_format($ot_holiday_rate_p+$ot_holiday_rate_c+$ot_holiday_rate_a),'',$r->user_id).'</td></tr>'.$al_detailed_salary.$join_detailed_salary.'<table>"></i>';

			$ot_hours_amount=array('ot_day_hours'=>($ot_day_hours_c+$ot_day_hours_p+$ot_day_hours_a),'ot_day_amount'=>($ot_day_rate_p+$ot_day_rate_c+$ot_day_rate_a),'ot_night_hours'=>($ot_night_hours_c+$ot_night_hours_p+$ot_night_hours_a),'ot_night_amount'=>($ot_night_rate_p+$ot_night_rate_c+$ot_night_rate_a),'ot_holiday_hours'=>($ot_holiday_hours_c+$ot_holiday_hours_p+$ot_holiday_hours_a),'ot_holiday_amount'=>($ot_holiday_rate_c+$ot_holiday_rate_p+$ot_holiday_rate_a),
				'ob_hours'=>decimalHours(decimalHours_reversewith_symbol($ob_hours_c+$ob_hours_p+$ob_hours_a)),'ob_amount'=>($ob_rate_c+$ob_rate_p+$ob_rate_a));

			if($make_payment){
				if($make_payment[0]->status==1){
					$status = '<span class="label label-success">Paid</span>';
					$total_working_hours=$make_payment[0]->total_working_hours;
					$accumulate_salary=$make_payment[0]->payment_amount;
					$late_slug=decimalHours_reverse($make_payment[0]->late_working_hours).' ('.decimalHours($make_payment[0]->late_working_hours).')';
					$rate_per_hour_contract_bonus=$make_payment[0]->rate_per_hour_contract_bonus;
					$rate_per_hour_contract_only=$make_payment[0]->rate_per_hour_contract_only;
					$rate_per_hour_basic_only=$make_payment[0]->rate_per_hour_basic_only;
					$ot_day_rate=$make_payment[0]->ot_day_rate;
					$ot_night_rate=$make_payment[0]->ot_night_rate;
					$ot_holiday_rate=$make_payment[0]->ot_holiday_rate;
					$ot_ar=json_decode($make_payment[0]->ot_hours_amount);
					$detailed_salary='<i style="cursor:pointer;" data-html="true" class="ml-10 icon-bubble-dots4 position-right text-teal-400" data-popup="popover-custom" data-placement="top" title="Salary Details" data-trigger="hover" data-content="<table><tr><td>Total Salary<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.change_num_format($make_payment[0]->month_salary).$make_payment[0]->currency.'</td></tr><tr><td>OT Day Salary<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.change_num_format($ot_ar->ot_day_amount).$make_payment[0]->currency.'</td></tr><tr><td>OT Night Salary<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.change_num_format($ot_ar->ot_night_amount).$make_payment[0]->currency.'</td></tr><tr><td>OT Holiday Salary<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.change_num_format($ot_ar->ot_holiday_amount).$make_payment[0]->currency.'</td></tr><tr><td>AL Salary<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.change_num_format($make_payment[0]->annual_leave_salary).$make_payment[0]->currency.'</td></tr><table>"></i>';

					$overtime_hours='<i style="cursor:pointer;" data-html="true" class="ml-10 icon-bubble-dots4 position-right text-teal-400" data-popup="popover-custom" data-placement="top" title="Over Time Hours" data-trigger="hover" data-content="<table><tr><td>OT - Day<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.decimalHoursFormat_h($ot_ar->ot_day_hours).'</td><tr><td>OT - Night<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.decimalHoursFormat_h($ot_ar->ot_night_hours).'</td></tr><tr><td>OT - Holiday<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.decimalHoursFormat_h($ot_ar->ot_holiday_hours).'</td></tr><tr><td>OB Hours<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.decimalHoursFormat_h($ot_ar->ob_hours).'</td></tr><table>"></i>';
				}
				else if($make_payment[0]->status==2){
					$status = '<span class="label label-warning">Hold</span>';
					$functions='<li><a href="#" class="text-danger">This payment put on Hold.</a></li>';
				}
			}else{
				if($result_annual_query > 10 && $ann_calculation==1 && $result_annual_query_id){
					$status='<span class="label label-info">Leave Settlement</span>';
					$p_details='<li><a href="#" data-toggle="modal" data-target=".small-view-modal" data-employee_id="'. $r->user_id . '"><i class="icon-eye4"></i> View Details</a></li>';
				}else {
					$status = '<span class="label label-danger">UnPaid</span>';
				}
			}
			if(@$total_salary < 0) {
				$total_working_hours=substr($total_working_hours,0,6);
				$accumulate_salary=substr($accumulate_salary,0,6);
			}
			if($date_of_leaving!=''){
				if((strtotime($current_month_start_date) > strtotime($date_of_leaving))){
					$total_working_hours=0;
					$accumulate_salary=0;
				}
			}
			$driver_delivery_details=json_encode([]);
			if($department_name=='TD' && $visa_name!='DMCC'){
				if((strtotime($calendar_start_date) <= strtotime($date_of_leaving)) && (strtotime($calendar_end_date) >= strtotime($date_of_leaving))){
					$driver_delivery_count=get_driver_report($r->user_id,$calendar_start_date,$date_of_leaving,'P');
				}else{
					$driver_delivery_count=get_driver_report($r->user_id,$calendar_start_date,$calendar_end_date,'P');
				}
				if($driver_delivery_count){

					$assigned_count=$driver_delivery_count['assigned_count'];
					$delivery_count=$driver_delivery_count['delivery_count'];
					$cancellation_count=$driver_delivery_count['cancellation_count'];
					$cancellation_rate=$driver_delivery_count['cancellation_rate'];
					if(($monthly_target_count<=$delivery_count) &&($cancellation_rate<=$order_cancellation_percentage)){
						if($current_month_data['agreed_bonus']!=null){
							$agreed_bonus=$current_month_data['agreed_bonus'];
						}else{
							$agreed_bonus=0;
						}

					}else{
						$agreed_bonus=0;
					}
					$driver_delivery_details=json_encode(array('assigned_count'=>$assigned_count,'delivery_count'=>$delivery_count,'cancellation_count'=>$cancellation_count,'cancellation_rate'=>$cancellation_rate,'monthly_target_count'=>$monthly_target_count,'order_cancellation_percentage'=>$order_cancellation_percentage,'agreed_bonus'=>$agreed_bonus));

				}
			}
			$functions.='<form action="'.base_url('payroll/makepayment/'.$r->user_id).'" method="post" id="mak_payment_submit_'.$r->user_id.'"><input type="hidden" name="required_working_hours" id="required_working_hours_'.$r->user_id.'" value="'.$required_working_hours.'"/>
                            <input type="hidden" name="total_working_hours" id="total_working_hours_'.$r->user_id.'" value="'.decimalHourswithoutround($total_working_hours).'"/>
                           <input type="hidden" name="late_working_hours" id="late_working_hours_'.$r->user_id.'" value="'.decimalHourswithoutround($late_working_hours).'"/>
                           <input type="hidden" name="rate_per_hour_contract_bonus" id="rate_per_hour_contract_bonus_'.$r->user_id.'" value="'.$c_m_rate_per_hour_contract_bonus.'"/>
                           <input type="hidden" name="rate_per_hour_contract_only" id="rate_per_hour_contract_only_'.$r->user_id.'" value="'.$c_m_rate_per_hour_contract_only.'"/>
                           <input type="hidden" name="rate_per_hour_basic_only" id="rate_per_hour_basic_only_'.$r->user_id.'" value="'.$c_m_rate_per_hour_basic_only.'"/>
						   <input type="hidden" name="leave_salary_amount" id="leave_salary_amount_'.$r->user_id.'" value='.json_encode($leave_array).'>
						   <input type="hidden" name="leave_salary_paid" id="leave_salary_paid_'.$r->user_id.'" value='.json_encode($leave_salary_paid).'>
                           <input type="hidden" name="ot_day_rate" id="ot_day_rate_'.$r->user_id.'" value="'.$c_m_ot_day_rate.'"/>
                           <input type="hidden" name="ot_night_rate" id="ot_night_rate_'.$r->user_id.'" value="'.$c_m_ot_night_rate.'"/>
                           <input type="hidden" name="ot_holiday_rate" id="ot_holiday_rate_'.$r->user_id.'" value="'.$c_m_ot_holiday_rate.'"/>
						   <input type="hidden" name="ot_hours_amount" id="ot_hours_amount_'.$r->user_id.'" value='.json_encode($ot_hours_amount).'>				 
                           <input type="hidden" name="total_salary" id="total_salary_'.$r->user_id.'" value="'.$accumulate_salary.'"><input type="hidden" name="employee_id" value="'.$r->user_id.'"><input type="hidden" id="pay_date_'.$r->user_id.'" name="pay_date"  value="'.$p_date.'">
						   <input type="hidden" id="actual_days_worked_'.$r->user_id.'" name="actual_days_worked"  value="'.$actual_days_worked.'">
						   <input type="hidden" id="shift_hours_'.$r->user_id.'" name="shift_hours"  value="'.$shift_hours.'">
						   <input type="hidden" id="basic_salary_'.$r->user_id.'" name="basic_salary"  value="'.$current_month_data['basic_salary'].'">
						   <input type="hidden" id="salary_with_bonus_'.$r->user_id.'" name="salary_with_bonus"  value="'.$current_month_data['salary_with_bonus'].'">						 
						   <input type="hidden" id="salary_template_id_'.$r->user_id.'" name="salary_template_id"  value="'.$current_month_data['salary_template_id'].'">					   
						   <input type="hidden" id="salary_components_'.$r->user_id.'" name="salary_components"  value='.json_encode($salary_components).'>						   
						   <input type="hidden" id="bonus_'.$r->user_id.'" name="bonus"  value="'.$current_month_data['bonus'].'">
						   <input type="hidden" id="leave_start_date_'.$r->user_id.'" name="leave_start_date"  value="'.$leave_start_date.'">
						   <input type="hidden" id="leave_end_date_'.$r->user_id.'" name="leave_end_date"  value="'.$leave_end_date.'">
						   <input type="hidden" id="annual_leave_salary_'.$r->user_id.'" name="annual_leave_salary"  value="'.$annual_leave_salary.'">
						   <input type="hidden" id="month_salary_'.$r->user_id.'" name="month_salary"  value="'.$total_salary.'">
						   <input type="hidden" id="joining_month_salary_'.$r->user_id.'" name="joining_month_salary"  value="'.$joining_month_salary.'">
						   <input type="hidden" id="visa_type_'.$r->user_id.'" name="visa_type"  value="'.$visa_type.'">
						   <input type="hidden" id="joining_month_hours_'.$r->user_id.'" name="joining_month_hours"  value="'.$joining_month_hours.'">
							<input type="hidden" id="p_m_required_working_hours_'.$r->user_id.'" name="p_m_required_working_hours"  value="'.$pre_m_required_working_hours.'">
							<input type="hidden" id="driver_delivery_details_'.$r->user_id.'" name="driver_delivery_details"  value='.$driver_delivery_details.'>
 							<input type="hidden" id="check_365days_enabled_'.$r->user_id.'" name="check_365days_enabled"  value='.$check_365days_enabled.'>
						   </form>';
			if($emp_id!='' && $py_date!=''){
				$data = array(
					'user_id'=>$r->user_id,
					'employee_id'=>$r->employee_id,
					'employee_name'=>$full_name,
					'basic_salary'=>$current_month_data['basic_salary'],
					'salary_with_bonus'=>@$current_month_data['salary_with_bonus'],
					'salary_template_id'=>@$current_month_data['salary_template_id'],
					'bonus'=>@$current_month_data['bonus'],
					'department_id'=>$r->department_id,
					'designation_id'=>$r->designation_id,
					'company_id'=>$r->company_id,
					'office_location_id'=>$r->office_location_id,
					'required_working_hours'=>$required_working_hours,
					'total_working_hours'=>decimalHourswithoutround($total_working_hours),
					'late_working_hours'=>decimalHourswithoutround($late_working_hours),
					'rate_per_hour_contract_bonus'=>$c_m_rate_per_hour_contract_bonus,
					'rate_per_hour_contract_only'=>$c_m_rate_per_hour_contract_only,
					'rate_per_hour_basic_only'=>$c_m_rate_per_hour_basic_only,
					'ot_day_rate'=>$c_m_ot_day_rate,
					'ot_night_rate'=>$c_m_ot_night_rate,
					'ot_holiday_rate'=>$c_m_ot_holiday_rate,
					'ot_hours_amount' =>json_encode($ot_hours_amount),
					'leave_salary'=>json_encode($leave_array),
					'leave_salary_paid'=>json_encode($leave_salary_paid),
					'total_salary'=>$accumulate_salary,
					'actual_days_worked'=>$actual_days_worked,
					'country_id'=>$country_id,
					'leave_start_date'=>$leave_start_date,
					'leave_end_date'=>$leave_end_date,
					'annual_leave_salary'=>$annual_leave_salary,
					'shift_hours'=>$shift_hours,
					'month_salary'=>$total_salary,
					'joining_month_salary'=>$joining_month_salary,
					'visa_type'=>$visa_type,
					'joining_month_hours'=>$joining_month_hours,
					'p_m_required_working_hours'=>$pre_m_required_working_hours,
					'driver_delivery_details'=>$driver_delivery_details,
					'salary_components'=>json_encode($salary_components),
					'check_365days_enabled'=>$check_365days_enabled,
				);
				return $data;
			}else{
				$rate_slug='<i style="cursor:pointer;" data-html="true" class="ml-10 icon-bubble-dots4 position-right text-teal-400" data-popup="popover-custom" data-placement="left" title="Rate Per Hours" data-trigger="hover" data-content="<table>
				<tr><td>Rate Per Hour (Contract + Bonus)<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.change_num_format($c_m_rate_per_hour_contract_bonus).'</td></tr>
				<tr><td>Rate Per Hour (Contract Only)<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.change_num_format($c_m_rate_per_hour_contract_only).'</td></tr>
				<tr><td>Rate Per Hour (Basic Only)<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.change_num_format($c_m_rate_per_hour_basic_only).'</td></tr>
				<tr><td>OT Day Rate<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.change_num_format($c_m_ot_day_rate).'</td></tr>
				<tr><td>OT Night Rate<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.change_num_format($c_m_ot_night_rate).'</td></tr>
				<tr><td>OT Holiday Rate<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.change_num_format($c_m_ot_holiday_rate).'</td></tr>
				<table>"></i>';

				$data[] = array(
					$r->user_id,
					$r->employee_id,
					$full_name,
					$required_working_hours,
					$total_working_hours.$overtime_hours,
					$late_slug,
					change_num_format($c_m_rate_per_hour_contract_bonus).$rate_slug,
					change_num_format($accumulate_salary).$detailed_salary,
					$status,
					$del_slug='<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$p_details.$functions.'</ul></li></ul>',
				);
			}
		}
		$output = array(
			"draw" => $draw,
			"recordsTotal" => $payslip->num_rows(),
			"recordsFiltered" => $payslip->num_rows(),
			"data" => $data
		);
		echo json_encode($output);
		exit();
	}

	public function generate_monthly_sheet($emp_id='',$py_date='',$l_start_date='',$l_end_date='',$status=''){
		$location_value=$this->input->get("location_value");
		$department_value=$this->input->get("department_value");
		$monthlySalarySum = 0;
		$bonusSum = 0;
		$oTSum = 0;
		$additionSum = 0;
		$totalCompensationSum = 0;
		$deductionSum = 0;
		$leaveSalarySum = 0;
		$ticketSum = 0;
		$totalAmountOfSalarySum = 0;
		$agencyFeeSum = 0;
		$totalSalaryWithoutVatSum = 0;
		$vatSum = 0;
		$totalSalaryWithVatSum = 0;

		$empID = '';
		$employee_id=$this->input->get("employee_id");
		if($employee_id!=0){
			$empID = $employee_id;
		}

		$payslip = $this->Payroll_model->get_employee_template($empID,$location_value,$department_value,$status);
		$month_year=$this->input->get('month_year');
		$p_date = $this->input->get("month_year");

		$system_settings = $this->Xin_model->read_setting_info(1);
		$p_date=date('Y-m',strtotime($p_date));
		$eligible_visa_under=json_decode($system_settings[0]->eligible_visa_under);
		$exceptional_employees=json_decode($system_settings[0]->exceptional_employees);

		$monthly_target_count=$system_settings[0]->monthly_target_count;
		$order_cancellation_percentage=$system_settings[0]->order_cancellation_percentage;

		$cut_off_dates=salary_start_end_date($p_date);
		$cut_off_start_date=$cut_off_dates['exact_date_start'];
		$cut_off_end_date=$cut_off_dates['exact_date_end'];
		$attendance=explode('-',$p_date);
		$days_count_per_month=cal_days_in_month(CAL_GREGORIAN,$attendance[1],$attendance[0]);
		$calendar_start_date=$p_date.'-01';
		$calendar_end_date=$p_date.'-'.$days_count_per_month;
		$calendar_end_date_1months=date("Y-m-d",strtotime("+15 days",strtotime($calendar_end_date)));
		$pre_month=date('Y-m',strtotime("-1 month",strtotime($p_date)));
		$pre_month_start_date=$cut_off_start_date;
		$pre_month_end_date=date('Y-m-d',strtotime("-1 Day",strtotime($calendar_start_date)));
		$pre_check_leave_start_date=date('Y-m-d',strtotime("-10 Day",strtotime($pre_month_start_date)));
		$current_month_start_date=$calendar_start_date;
		$current_month_end_date=$cut_off_end_date;
		$assuming_month_start_date=date('Y-m-d',strtotime("+1 Day",strtotime($cut_off_end_date)));
		$assuming_month_end_date=$calendar_end_date;
		$annual_id_a=$this->Timesheet_model->read_leave_type_id('Annual Leave');
		$data = array();
		$f = fopen('uploads/csv/monthly-sheet.csv', 'w');

		fputcsv($f, array(
			$month_year,
			'Name',
			'1C ID',
			'Hire Date',
			'Designation',
			'Department',
			'Visa Under',
			'Location',
			'NO. OF WORK SCHEDULE',
			'Actual Days Worked',
			'Required Working Hours',
			'Actual Working hours',
			'Monthly salary',
			'New Monthly Salary',
			'Agreed Bonus',
			'OT Amount',
			'Addition',
			'Leave Salary',
			'Ticket',
			'Total Compensation',
			'Deduction',
			'Total Amount of Salary',
			'Computation of Agency Fee',
			'Total Salary (Without VAT)',
			'VAT Amount (5%)',
			'Total Salary (With 5% VAT) Exception: DMCC',
		));

		foreach($payslip->result() as $r) {
			$full_name = $r->first_name.' '.$r->middle_name.' '.$r->last_name;
			$department_id = $r->department_id;
			$country_id = $r->country_id;
			$location_id = $r->office_location_id;
			$date_of_joining = $r->date_of_joining;
			$visa_type=$r->type;
			$designation = $this->Designation_model->read_designation_information($r->designation_id);
			$visa_name=$r->visa_name;
			$date_of_leaving=$r->date_of_leaving;
			$ann_calculation=1;
			if(!in_array($visa_type,$eligible_visa_under)){
				$ann_calculation=0;
			}
			$department = $this->Department_model->read_department_information($department_id);
			$department_name=$department[0]->department_name;
			$grace_eligible=grace_eligible($department_id,$location_id);
			// Find Total Hours
			$d_hours=explode(':',$r->working_hours);
			if(@$d_hours[1]){ $d_mins=@$d_hours[1];} else {$d_mins='0';}
			$f_total_hours = new DateTime($d_hours[0].':'.$d_mins);
			$f_lunch_hours = new DateTime(LUNCH_HOURS);
			$interval_lunch = $f_total_hours;
			if($r->is_break_included == 1)
				$interval_lunch = $f_total_hours->diff($f_lunch_hours);

			$total_work=$interval_lunch->format('%h').':'.$interval_lunch->format('%i');
			$shift_hours = decimalHours($total_work);
			// Find Total Hours

			$required_working_hours=$this->required_working_hours($calendar_start_date,$calendar_end_date,$shift_hours,$country_id);
			if($department_name=='TD' && $visa_name!='DMCC'){
				$prev_month_data=[];
				$assuming_month_data=[];
				$a_m_leave_paid_salary=[];
				$a_m_leave_salary=[];
				if(strtotime($calendar_start_date) < strtotime($calendar_end_date)){
					if((strtotime($calendar_start_date) <= strtotime($date_of_joining)) && (strtotime($calendar_end_date) >= strtotime($date_of_joining))){
						$current_month_data=$this->payslip_list_data($r->user_id,$p_date,$date_of_joining,$calendar_end_date,1,$shift_hours);
					}
					else{

						if((strtotime($calendar_start_date) <= strtotime($date_of_leaving)) && (strtotime($calendar_end_date) >= strtotime($date_of_leaving))){
							$current_month_data=$this->payslip_list_data($r->user_id,$p_date,$calendar_start_date,$date_of_leaving,1,$shift_hours);
						}else{
							$current_month_data=$this->payslip_list_data($r->user_id,$p_date,$calendar_start_date,$calendar_end_date,1,$shift_hours);
						}
					}
				}else{
					$current_month_data=[];
				}
			}
			else{
				if(strtotime($pre_month_start_date) < strtotime($pre_month_end_date)){
					if((strtotime($pre_month_start_date) <= strtotime($date_of_joining)) && (strtotime($pre_month_end_date) >= strtotime($date_of_joining))){
						$prev_month_data=$this->payslip_list_data($r->user_id,$pre_month,$date_of_joining,$pre_month_end_date,1,$shift_hours);
					}else{
						$prev_month_data=$this->payslip_list_data($r->user_id,$pre_month,$pre_month_start_date,$pre_month_end_date,1,$shift_hours);
					}}else{
					$prev_month_data=[];
				}
				if(strtotime($current_month_start_date) < strtotime($current_month_end_date)){
					if((strtotime($current_month_start_date) <= strtotime($date_of_joining)) && (strtotime($current_month_end_date) >= strtotime($date_of_joining))){
						$current_month_data=$this->payslip_list_data($r->user_id,$p_date,$date_of_joining,$current_month_end_date,1,$shift_hours);
					}else{
						if((strtotime($current_month_start_date) <= strtotime($date_of_leaving)) && (strtotime($current_month_end_date) >= strtotime($date_of_leaving))){
							$current_month_data=$this->payslip_list_data($r->user_id,$p_date,$current_month_start_date,$date_of_leaving,1,$shift_hours);
						}else{

							$current_month_data=$this->payslip_list_data($r->user_id,$p_date,$current_month_start_date,$current_month_end_date,1,$shift_hours);
						}
					}
				}else{
					$current_month_data=[];
				}

				if((strtotime($current_month_start_date) <= strtotime($date_of_leaving)) && (strtotime($current_month_end_date) >= strtotime($date_of_leaving))){
					$assuming_month_data=[];
					$a_m_leave_paid_salary=[];
					$a_m_leave_salary=[];
				}else{
					if(strtotime($assuming_month_start_date) < strtotime($assuming_month_end_date)){
						if((strtotime($assuming_month_start_date) <= strtotime($date_of_leaving)) && (strtotime($assuming_month_end_date) >= strtotime($date_of_leaving))){
							$assuming_month_data=$this->payslip_list_data($r->user_id,$p_date,$assuming_month_start_date,$date_of_leaving,1,$shift_hours);
							$a_m_leave_paid_salary=json_decode($assuming_month_data['leave_salary_paid']);
							$a_m_leave_salary=json_decode($assuming_month_data['leave_salary']);
						}else{
							$assuming_month_data=$this->payslip_list_data($r->user_id,$p_date,$assuming_month_start_date,$assuming_month_end_date,1,$shift_hours);
							$a_m_leave_paid_salary=json_decode($assuming_month_data['leave_salary_paid']);
							$a_m_leave_salary=json_decode($assuming_month_data['leave_salary']);
						}
					}else{
						$assuming_month_data=[];
						$a_m_leave_paid_salary=[];
						$a_m_leave_salary=[];
					}
				}
			}
			$salary_components=array_slice($current_month_data, 29);
			//Prev Month 21-30
			$p_m_required_working_hours=$prev_month_data['required_working_hours'];
			$p_m_total_working_hours=$prev_month_data['total_working_hours'];
			$p_m_late_working_hours=$prev_month_data['late_working_hours'];
			$p_m_total_salary=$prev_month_data['total_salary'];

			$p_m_ot_hours_amount=json_decode($prev_month_data['ot_hours_amount']);
			$p_m_leave_salary=json_decode($prev_month_data['leave_salary']);	$p_m_leave_paid_salary=json_decode($prev_month_data['leave_salary_paid']);
			$p_m_late_work_array=json_decode($prev_month_data['late_work_array']);
			//Prev Month 21-30
			//Current Month 1-20
			$c_m_total_working_hours=$current_month_data['total_working_hours'];
			$c_m_late_working_hours=$current_month_data['late_working_hours'];
			$c_m_total_salary=$current_month_data['total_salary'];
			$c_m_required_working_hours=$required_working_hours['count_of_remaining_hours'];
			$c_m_rate_per_hour_contract_only=(($current_month_data['salary_with_bonus']-$current_month_data['bonus'])/$c_m_required_working_hours);
			$c_m_rate_per_hour_basic_only=$current_month_data['basic_salary']/$c_m_required_working_hours;

			$c_m_ot_hours_amount=json_decode($current_month_data['ot_hours_amount']);
			$c_m_leave_salary=json_decode($current_month_data['leave_salary']);
			$c_m_leave_paid_salary=json_decode($current_month_data['leave_salary_paid']);
			$c_m_late_work_array=json_decode($current_month_data['late_work_array']);
			$check_ramadan_date=json_decode($current_month_data['check_ramadan_date']);
			//Current Month 1-20

			//Assuming Month 18-21
			$a_m_total_working_hours=$assuming_month_data['total_working_hours'];
			$a_m_late_working_hours=$assuming_month_data['late_working_hours'];
			$a_m_total_salary=$assuming_month_data['total_salary'];
			$a_m_late_work_array=json_decode($assuming_month_data['late_work_array']);
			$a_m_ot_hours_amount=json_decode($assuming_month_data['ot_hours_amount']);
			//Assuming Month 28-31
			$ramadan_date=[];
			//			if($check_ramadan_date){
			//				foreach($check_ramadan_date as $ramadan_date1){
			//					$ramadan_date[]=array('attendance_date'=>$ramadan_date1->attendance_date,'reduced_hours'=>$ramadan_date1->reduced_hours);
			//				}
			//			}
			$check_ramadan_date=$ramadan_date;
			$p_m_leave_salary_id=$this->Payroll_model->count_of_leave_id($pre_check_leave_start_date,$pre_month_end_date,$r->user_id,$department_id, $country_id,'',$check_ramadan_date,$shift_hours);
			$pre_count_of_rest_hours=0;
			$pre_count_of_salary_amount=0;

			//Annual Leave Count
			$result_annual_query_res=$this->Payroll_model->check_leaves_occupiedwithdate_counts($annual_id_a,$r->user_id,$pre_month_start_date,$calendar_end_date_1months);

			$result_query_id=$this->Payroll_model->count_of_leave_id_cal($pre_month_start_date,$calendar_end_date_1months,$calendar_end_date,$r->user_id,$department_id, $country_id,'',$check_ramadan_date,$shift_hours,'annual_leave');
			$count_of_leave_id_array=[];

			if($c_m_leave_paid_salary){
				foreach($c_m_leave_paid_salary as $count_leave_c_m){
					foreach($count_leave_c_m as $key_c_m=>$value_c_m){
						$count_of_leave_id_array[$key_c_m]=array('leave_id'=>$value_c_m->leave_id,'leave_status_code'=>$value_c_m->leave_status_code,'attendance_date'=>$value_c_m->attendance_date,'leave_type_id'=>$value_c_m->leave_type_id,'type_name'=>$value_c_m->type_name);
					}
				}
			}
			if($p_m_leave_salary_id){
				foreach($p_m_leave_salary_id as $p_m_leave){
					foreach($p_m_leave as $key_l=>$value_l){
						$count_of_leave_id_array[$value_l['attendance_date']]=array('leave_id'=>$value_l['leave_id'],'leave_status_code'=>$value_l['leave_status_code'],'attendance_date'=>$value_l['attendance_date'],'leave_type_id'=>$value_l['leave_type_id'],'type_name'=>$value_l['type_name']);
					}

				}
			}
			if($a_m_leave_paid_salary){
				foreach($a_m_leave_paid_salary as $count_leave_a_m){
					foreach($count_leave_a_m as $key_a_m=>$value_a_m){
						$count_of_leave_id_array[$key_a_m]=array('leave_id'=>$value_a_m->leave_id,'leave_status_code'=>$value_a_m->leave_status_code,'attendance_date'=>$value_a_m->attendance_date,'leave_type_id'=>$value_a_m->leave_type_id,'type_name'=>$value_a_m->type_name);
					}
				}
			}

			$result_annual_query_id=[];
			if($result_query_id){
				foreach($result_query_id as $count_leave){
					foreach($count_leave as $key_l=>$value_l){
						if($value_l['leave_status_code']=='AL'){
							$result_annual_query_id[]=$value_l['attendance_date'];
							$count_of_leave_id_array[$value_l['attendance_date']]=array('leave_id'=>$value_l['leave_id'],'leave_status_code'=>$value_l['leave_status_code'],'attendance_date'=>$value_l['attendance_date'],'leave_type_id'=>$value_l['leave_type_id'],'type_name'=>$value_l['type_name']);}else{
							$count_of_leave_id_array[$value_l['attendance_date']]=array('leave_id'=>$value_l['leave_id'],'leave_status_code'=>$value_l['leave_status_code'],'attendance_date'=>$value_l['attendance_date'],'leave_type_id'=>$value_l['leave_type_id'],'type_name'=>$value_l['type_name']);
						}
					}
				}
			}

			//Annual Leave Count
			$annual_leave_salary=0;
			$annual_leave_rest=0;
			$annual_leave_array=[];
			$result_annual_query=count($result_annual_query_id);
			if($ann_calculation==1 && $result_annual_query_id){
				$annual_leave_salary =((($current_month_data['basic_salary']+$current_month_data['house_rent_allowance'])*12)/365)*$result_annual_query;
				$annual_leave_rest=$result_annual_query*$shift_hours;
				$annual_leave_array=array(0=> (object) array('AL'=> (object) array('days'=>$result_annual_query,'amount'=>$annual_leave_salary,'total_rest'=>$annual_leave_rest)));

			}


			if(!$p_m_leave_salary){$p_m_leave_salary=[];}
			if(!$c_m_leave_salary){$c_m_leave_salary=[];}


			if((strtotime($current_month_start_date) <= strtotime($date_of_leaving)) && (strtotime($current_month_end_date) >= strtotime($date_of_leaving))){
				$assuming_count_of_leave_days=0;
				$assuming_count_of_rest_hours=0;
				$assuming_count_of_salary_amount=0;
			}
			else if((strtotime($assuming_month_start_date) <= strtotime($date_of_leaving)) && (strtotime($assuming_month_end_date) >= strtotime($date_of_leaving))){
				$assuming_count_of_leave_days=0;
				$assuming_count_of_rest_hours=0;
				$assuming_count_of_salary_amount=0;
			}
			else{
				//check
				$assuming_leave_status=$this->Payroll_model->count_of_leave_status_new($assuming_month_start_date,$assuming_month_end_date,$r->user_id,$department_id, $country_id,'',$check_ramadan_date,$shift_hours,$c_m_required_working_hours);
				$assuming_count_of_leave_days=0;
				$assuming_count_of_rest_hours=0;
				$assuming_count_of_salary_amount=0;
				foreach($assuming_leave_status as $assuming_leave){
					//if($assuming_leave->attendance_status!='SL-1' && $assuming_leave->attendance_status!='SL-2' && $assuming_leave->attendance_status!='SL-UP' && $assuming_leave->attendance_status!='ML-1' && $assuming_leave->attendance_status!='ML-2' && $assuming_leave->attendance_status!='ML-UP'  && $assuming_leave->attendance_status!='EL'){
					if($assuming_leave->attendance_status!='PH'){
						$assuming_count_of_leave_days+=$assuming_leave->count_attendance_status;
						$assuming_count_of_rest_hours+=$assuming_leave->total_rest;
						$assuming_count_of_salary_amount+=$assuming_leave->salary_count;
					}
				}
			}

			$pre_working_hours=$this->remaining_days_of_monthwithsalary($pre_month_start_date,$pre_month_end_date,$shift_hours,$country_id,$r->user_id,$p_m_required_working_hours);
			if((strtotime($current_month_start_date) <= strtotime($date_of_leaving)) && (strtotime($current_month_end_date) >= strtotime($date_of_leaving))){
				$assuming_working_hours['count_of_remaining_days']=0;
				$assuming_working_hours['count_of_remaining_hours']=0;
				$assuming_working_hours['salary_count']=0;
			}else if((strtotime($assuming_month_start_date) <= strtotime($date_of_leaving)) && (strtotime($assuming_month_end_date) >= strtotime($date_of_leaving))){
				$assuming_working_hours['count_of_remaining_days']=0;
				$assuming_working_hours['count_of_remaining_hours']=0;
				$assuming_working_hours['salary_count']=0;
			}else{
				$assuming_working_hours=$this->remaining_days_of_monthwithsalary($assuming_month_start_date,$assuming_month_end_date,$shift_hours,$country_id,$r->user_id,$c_m_required_working_hours);
			}

			$ob_hours_a=0;
			$ot_day_rate_a=0;
			$ot_night_rate_a=0;
			$ot_holiday_rate_a=0;
			$ot_day_hours_a=0;
			$ot_night_hours_a=0;
			$ot_holiday_hours_a=0;


			//$pre_month_end_date
			$required_working_hours=$c_m_required_working_hours;
			$taken_leave_status=$this->Payroll_model->count_of_leave_status_paid($pre_month_start_date,$pre_month_end_date,$r->user_id,$department_id, $country_id,'',$check_ramadan_date,$shift_hours,$p_m_required_working_hours);
			$taken_count_of_leave_days=0;
			$taken_count_of_rest_hours=0;
			$taken_count_of_salary_amount=0;
			foreach($taken_leave_status as $taken_leave){
				$taken_count_of_leave_days+=$taken_leave->count_attendance_status;
				$taken_count_of_rest_hours+=$taken_leave->total_rest;
				$taken_count_of_salary_amount+=$taken_leave->salary_count;
			}

			$joining_month_salary=0;
			$joining_month_hours=0;
			$total_salary=0;
			if($department_name=='TD' && $visa_name!='DMCC'){
				$total_working_hours=$c_m_total_working_hours;
				$total_salary=$c_m_total_salary;
				$pre_m_required_working_hours=$p_m_required_working_hours;
			}
			else{
				if((strtotime($pre_month_start_date) <= strtotime($date_of_joining)) && (strtotime($pre_month_end_date) >= strtotime($date_of_joining))){
					$total_working_hours=$c_m_total_working_hours+($assuming_working_hours['count_of_remaining_hours']-$assuming_count_of_rest_hours-$pre_count_of_rest_hours);
					$total_salary=$c_m_total_salary+($assuming_working_hours['salary_count']-$assuming_count_of_salary_amount-$pre_count_of_salary_amount);
					$joining_month_hours=$p_m_total_working_hours;
					$joining_month_salary=$p_m_total_salary;
					$pre_m_required_working_hours=$p_m_required_working_hours;
				}else if((strtotime($current_month_start_date) <= strtotime($date_of_joining)) && (strtotime($current_month_end_date) >= strtotime($date_of_joining))){
					$total_working_hours=$c_m_total_working_hours+($assuming_working_hours['count_of_remaining_hours']-$assuming_count_of_rest_hours);
					$total_salary=$c_m_total_salary+($assuming_working_hours['salary_count']-$assuming_count_of_salary_amount);
					$pre_m_required_working_hours=$c_m_required_working_hours;
				}else{
					$pre_m_required_working_hours=$c_m_required_working_hours;
					$pre_work_hours=($pre_working_hours['count_of_remaining_hours']-($p_m_total_working_hours+$taken_count_of_rest_hours));
					$pre_total_salary=($pre_working_hours['salary_count']-($p_m_total_salary+$taken_count_of_salary_amount));
					if($pre_work_hours < 0){$pre_work_hours=0;}
					if($pre_total_salary < 0){$pre_total_salary=0;}
					if((strtotime($assuming_month_start_date) <= strtotime($date_of_leaving)) && (strtotime($assuming_month_end_date) >= strtotime($date_of_leaving))){
						$total_working_hours=($c_m_total_working_hours)+$a_m_total_working_hours-($pre_work_hours-$pre_count_of_rest_hours);
						$total_salary=($c_m_total_salary)+$a_m_total_salary-($pre_total_salary-$pre_count_of_salary_amount);
					}else{
						$total_working_hours=($c_m_total_working_hours)+($assuming_working_hours['count_of_remaining_hours']-$assuming_count_of_rest_hours)-($pre_work_hours-$pre_count_of_rest_hours);
						$total_salary=($c_m_total_salary)+($assuming_working_hours['salary_count']-$assuming_count_of_salary_amount)-($pre_total_salary-$pre_count_of_salary_amount);
					}
				}
			}
			$late_hours_value=0;
			$late_hours_amount=0;
			if($p_m_late_work_array){
				foreach($p_m_late_work_array as $p_m_late_array){
					$late_hours_value+=$p_m_late_array->time_late;
					$late_hours_amount+=$p_m_late_array->time_late_amount;
				}
			}
			if($c_m_late_work_array){
				foreach($c_m_late_work_array as $c_m_late_array){
					$late_hours_value+=$c_m_late_array->time_late;
					$late_hours_amount+=$c_m_late_array->time_late_amount;
				}
			}
			if((strtotime($assuming_month_start_date) <= strtotime($date_of_leaving)) && (strtotime($assuming_month_end_date) >= strtotime($date_of_leaving))){
				if($a_m_late_work_array){
					foreach($a_m_late_work_array as $a_m_late_array){
						$late_hours_value+=$a_m_late_array->time_late;
						$late_hours_amount+=$a_m_late_array->time_late_amount;
					}
				}
			}

			$late_working_hours_v=secondsToTime($late_hours_value);
			$late_working_hours=$late_working_hours_v['h'].':'.$late_working_hours_v['m'];
			if($late_hours_value <= GRACE_PERIOD_MINUTES && $grace_eligible==1){
				if(in_array(@$employee_id,$exceptional_employees)){
					if((strtotime($assuming_month_start_date) <= strtotime($date_of_leaving)) && (strtotime($assuming_month_end_date) >= strtotime($date_of_leaving))){
						$total_working_hours=$total_working_hours+$c_m_late_working_hours+$a_m_late_working_hours;
						$total_salary=$total_salary;
					}else{
						$total_working_hours=$total_working_hours+$c_m_late_working_hours;
						$total_salary=$total_salary;
					}
				}else{
					if((strtotime($assuming_month_start_date) <= strtotime($date_of_leaving)) && (strtotime($assuming_month_end_date) >= strtotime($date_of_leaving))){
						$total_working_hours=$total_working_hours+$c_m_late_working_hours+$p_m_late_working_hours+$a_m_late_working_hours;
						$total_salary=$total_salary+$late_hours_amount;
					}else{
						$total_working_hours=$total_working_hours+$c_m_late_working_hours+$p_m_late_working_hours;
						$total_salary=$total_salary+$late_hours_amount;
					}
				}
				$late_working_hours=0;
			}else{
				$late_working_hours=$late_working_hours;
			}

			$check_365days_enabled=check_365days_enabled($calendar_start_date,$calendar_end_date,$r->user_id,$shift_hours,$check_ramadan_date,$department_id,$country_id,$location_id,$date_of_joining,$date_of_leaving);
			if($check_365days_enabled==1){
				$total_salary=$current_month_data['salary_with_bonus']-(($current_month_data['required_working_hours']-$total_working_hours)*(($current_month_data['salary_with_bonus']*12))/365/$shift_hours);
			}
			if($total_working_hours!=0){
				$accumulate_salary=$total_salary+$joining_month_salary+$annual_leave_salary;}else{$accumulate_salary=0;$total_salary=0;}
			$actual_days_worked=($total_working_hours/$required_working_hours)*$days_count_per_month;

			if(@$total_salary < 0) {
				$total_working_hours=substr($total_working_hours,0,6);
				$accumulate_salary=substr($accumulate_salary,0,6);
			}
			if($date_of_leaving!=''){
				if((strtotime($current_month_start_date) > strtotime($date_of_leaving))){
					$total_working_hours=0;
					$accumulate_salary=0;
				}
			}

			if($department_name=='TD' && $visa_name!='DMCC'){
				if((strtotime($calendar_start_date) <= strtotime($date_of_leaving)) && (strtotime($calendar_end_date) >= strtotime($date_of_leaving))){
					$driver_delivery_count=get_driver_report($r->user_id,$calendar_start_date,$date_of_leaving,'P');
				}else{
					$driver_delivery_count=get_driver_report($r->user_id,$calendar_start_date,$calendar_end_date,'P');
				}
				if($driver_delivery_count){

					$assigned_count=$driver_delivery_count['assigned_count'];
					$delivery_count=$driver_delivery_count['delivery_count'];
					$cancellation_count=$driver_delivery_count['cancellation_count'];
					$cancellation_rate=$driver_delivery_count['cancellation_rate'];
					if(($monthly_target_count<=$delivery_count) &&($cancellation_rate<=$order_cancellation_percentage)){
						if($current_month_data['agreed_bonus']!=null){
							$agreed_bonus=$current_month_data['agreed_bonus'];
						}else{
							$agreed_bonus=0;
						}

					}else{
						$agreed_bonus=0;
					}

				}
			}
			$adds = 0;
			$deds = 0;
			$perp = 0;
			$OT = 0;
			$ticket = 0;
			$locationName = $this->Timesheet_model->get_location_name($r->user_id);
			$find_internal_add_adjustments = $this->Payroll_model->find_adjustments($r->user_id,$cut_off_start_date,$cut_off_end_date,'internal_adjustments','Addition');
			$find_internal_ded_adjustments = $this->Payroll_model->find_adjustments($r->user_id,$cut_off_start_date,$cut_off_end_date,'internal_adjustments','Deduction');

			$find_external_perp_adjustments = $this->Payroll_model->find_ext_adjustments($r->user_id,$cut_off_start_date,$cut_off_end_date,'external_adjustments','Perpetual');
			$find_external_nonperp_adjustments = $this->Payroll_model->find_ext_adjustments($r->user_id,$cut_off_start_date,$cut_off_end_date,'external_adjustments','Non-Perpetual');
			$start_dt = $cut_off_start_date;
			$end_dt = $cut_off_end_date;

			foreach($find_external_perp_adjustments as $ext_per_adjustments) {
				if ($ext_per_adjustments->parent_type_name == 'Perpetual') {
					$ext_per_adjustments_amount = $ext_per_adjustments->adjustment_amount;
					if ($ext_per_adjustments->compute_amount == 0) {
						if ($ext_per_adjustments->child_type_name == 'Agency Fees') {
							if ((strtotime($start_dt) <= strtotime($date_of_joining)) && (strtotime($end_dt) >= strtotime($date_of_joining))) {

								$date1 = new DateTime($date_of_joining);

								if ((strtotime($start_dt) <= strtotime($date_of_leaving)) && (strtotime($end_dt) >= strtotime($date_of_leaving))) {
									$date2 = new DateTime($date_of_leaving);
								} else {
									$date2 = new DateTime($calendar_end_date);
								}
								$interval_date = $date2->diff($date1);
								$no_of_days_worked = $interval_date->days + 1;

								$computed_perp_amount = ($ext_per_adjustments_amount / $days_count_per_month) * $no_of_days_worked;
								$perp += $computed_perp_amount;

							} else if ((strtotime($start_dt) <= strtotime($date_of_leaving)) && (strtotime($end_dt) >= strtotime($date_of_leaving))) {


								$date1 = new DateTime($calendar_start_date);

								$date2 = new DateTime($date_of_leaving);
								$interval_date = $date2->diff($date1);
								$no_of_days_worked = $interval_date->days + 1;

								$computed_perp_amount = ($ext_per_adjustments_amount / $days_count_per_month) * $no_of_days_worked;
								$perp += $computed_perp_amount;
								// DOJ
							} else {
								$computed_perp_amount = $ext_per_adjustments_amount;
								$perp += $computed_perp_amount;

							}

						} else {
							$computed_perp_amount = $ext_per_adjustments_amount;
							$perp += $computed_perp_amount;
						}

					}
					else {
						if ($ext_per_adjustments->child_type_name == 'Agency Fees') {
							// DOJ
							if ((strtotime($start_dt) <= strtotime($date_of_joining)) && (strtotime($end_dt) >= strtotime($date_of_joining))) {
								if ((strtotime($pre_month_start_date) <= strtotime($date_of_joining)) && (strtotime($pre_month_end_date) >= strtotime($date_of_joining))) {
									$computed_perp_amount = (($ext_per_adjustments_amount / $required_working_hours) * $total_working_hours) + (($ext_per_adjustments_amount / $_GET['t_p_m_required_working_hours']) * $_GET['t_joining_month_hours']);
									$perp += $computed_perp_amount;
								}
								else if ((strtotime($calendar_start_date) <= strtotime($date_of_joining)) && (strtotime($calendar_end_date) >= strtotime($date_of_joining))) {
									$computed_perp_amount = (($ext_per_adjustments_amount / $required_working_hours) * $total_working_hours);
									$perp += $computed_perp_amount;
								}
							} else if ((strtotime($start_dt) <= strtotime($date_of_leaving)) && (strtotime($end_dt) >= strtotime($date_of_leaving))) {

								$computed_perp_amount = (($ext_per_adjustments_amount / $required_working_hours) * $total_working_hours);
								$perp += $computed_perp_amount;
								// DOJ
							} else {
								if ($check_365days_enabled == 1) {
									$computed_perp_amount = $ext_per_adjustments_amount - (($required_working_hours - $total_working_hours) * ($ext_per_adjustments_amount * 12 / 365 / $shift_hours));
									$perp += $computed_perp_amount;
								} else {
									$computed_perp_amount = (($ext_per_adjustments_amount / $required_working_hours) * $total_working_hours);
									$perp += $computed_perp_amount;

								}
							}
						} else {
							$computed_perp_amount = (($ext_per_adjustments_amount / $required_working_hours) * $total_working_hours);
							$perp += $computed_perp_amount;
						}
					}
				}
			}

			foreach($find_external_nonperp_adjustments as $ext_nonper_adjustments) {
				if ($ext_nonper_adjustments->parent_type_name == 'Non-Perpetual') {
					$ext_nonper_adjustments_amount = $ext_nonper_adjustments->adjustment_amount;
					if ($ext_nonper_adjustments->compute_amount == 0) {
						$computed_nonperp_amount = $ext_nonper_adjustments_amount;
						$perp += $computed_nonperp_amount;
					} else {
						$computed_nonperp_amount = (($ext_nonper_adjustments_amount / $required_working_hours) * $total_working_hours);
						$perp += $computed_nonperp_amount;
					}
				}
			}

			foreach($find_internal_add_adjustments as $int_add_adjustments) {
				if ($int_add_adjustments->parent_type_name == 'Addition') {
					$adds += $int_add_adjustments->adjustment_amount;
				}
				if ($int_add_adjustments->child_type_name == 'OT') {
					$OT += $int_add_adjustments->adjustment_amount;
				}
				if ($int_add_adjustments->child_type_name == 'Air Ticket') {
					$ticket += $int_add_adjustments->adjustment_amount;
				}
			}

			foreach($find_internal_ded_adjustments as $int_ded_adjustments){
				if($int_ded_adjustments->parent_type_name=='Deduction') {
					$deds += $int_ded_adjustments->adjustment_amount;
				}
			}

			if(is_nan($perp))
				$perp = 1200;

			$tax_type=get_tax_info($visa_type);
			$total_tax_amount=0;
			if($tax_type) {
				foreach ($tax_type as $tax_t) {
					$total_tax_amount += $tax_t->type_symbol/100;
				}
			}
			$addition = $adds-$ticket-$OT;
			$totalCompensation = $accumulate_salary+$adds;
			$totalAmountOfSalary = $accumulate_salary+$adds-$deds;
			$salaryWithoutVat = $accumulate_salary+$adds-$deds+$perp;
			$taxOnSalary = $total_tax_amount*($salaryWithoutVat);
			$finalSalaryAfterTax = abs($salaryWithoutVat+$taxOnSalary);
			$monthlySalarySum += $current_month_data['salary_with_bonus'];
			$oTSum += !(is_nan($OT))? $OT : 0;
			$additionSum += !(is_nan($addition))? $addition : 0;
			$deductionSum += !(is_nan($deds))? $deds : 0;
			$totalCompensationSum += !(is_nan($totalCompensation))? $totalCompensation : 0;
			$leaveSalarySum += !(is_nan($annual_leave_salary))? $annual_leave_salary : 0;
			$ticketSum += !(is_nan($ticket))? $ticket : 0;
			$totalAmountOfSalarySum += !(is_nan($totalAmountOfSalary))? $totalAmountOfSalary : 0;
			$agencyFeeSum += !(is_nan($perp))? $perp : 0;
			$totalSalaryWithoutVatSum += !(is_nan($salaryWithoutVat))? $salaryWithoutVat : 0;
			$vatSum += !(is_nan($taxOnSalary))? $taxOnSalary : 0;
			$totalSalaryWithVatSum += !(is_nan($finalSalaryAfterTax))? $finalSalaryAfterTax : 0;

			$temp = array(
				'', // Month
				$full_name, //Name
				$r->employee_id, //1C ID
				$r->date_of_joining,//Hire Date
				$designation[0]->designation_name,//Designation
				$department_name,//Department
				$r->visa_name, //Visa Under
				$locationName, //Location
				$shift_hours, //NO. OF WORK SCHEDULE,
				$actual_days_worked, //Actual Days Worked
				$required_working_hours, //Required Working Hours
				decimalHourswithoutround($total_working_hours), //Actual Working hours
				$current_month_data['salary_with_bonus'],//Monthly salary
				$accumulate_salary,//New Monthly Salary
				'0', //Agreed Bonus
				$OT, //OT
				$addition, //Addition
				$annual_leave_salary, // leave salary
				$ticket, // ticket
				$totalCompensation, // total compensation
				$deds,// Deduction
				$totalAmountOfSalary,// Total Amount of Salary
				$perp,// Agency Fees
				$salaryWithoutVat,// salary without vat
				$taxOnSalary, // 5% VAT for NON DMCC
				$finalSalaryAfterTax // Total Salary (With 5% VAT) Exception: DMCC'
			);
			$data[] = $temp;
			fputcsv($f, $temp);
		}
		$temp = [
			'','','','','','','','','','','','','','','','','','','','','','','','','','',''
		];
		fputcsv($f, $temp);
		$temp = [
			'','','','','','','','','','','','','','','','','','','','','','','','','','',''
		];
		fputcsv($f, $temp);
		$temp = [
			'','','','','','','','','','','','TOTAL',$monthlySalarySum,0,$monthlySalarySum,'0',$oTSum,$additionSum,$leaveSalarySum,$ticketSum,$totalCompensationSum,$deductionSum,$totalAmountOfSalarySum,$agencyFeeSum,$totalSalaryWithoutVatSum,$vatSum,$totalSalaryWithVatSum,
		];
		fputcsv($f, $temp);
		$temp = [
			'','','','','','','','','','','','','','','','','','','','','','','','','','',''
		];
		fputcsv($f, $temp);
		$temp = [
			'','','','','','','','','','','','','','','','','','','','','','','','','','',''
		];
		fputcsv($f, $temp);

		$temp = [
			'','','','','','','','','','','','NO OF EMPLOYEES',sizeof($data)
		];
		fputcsv($f, $temp);
		$temp = [
			'','','','','','','','','','','','TOTAL SALARY INVOICE',$totalSalaryWithVatSum
		];
		fputcsv($f, $temp);
		fclose($f);

		$template = $this->Xin_model->read_email_template_info_bycode('monthly_payroll_sheet');
		$message = htmlspecialchars_decode(stripslashes($template[0]->message));
		$this->email->from(FROM_MAIL);
		$this->email->to($this->userSession['email']);
		$this->email->subject("Monthly Payroll Sheet [".$month_year."]");
		$this->email->message($message);
		$this->email->send();
		exit();
	}

	public function leave_settlement_list()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get("employee_id");
		if(empty($this->userSession)){
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		$leave_settlement_list = $this->Payroll_model->read_all_leave_settlement_list('');
		$data = array();
		foreach($leave_settlement_list->result() as $r) {
			$user = $this->Xin_model->read_user_info($r->employee_id);
			$added_by = $this->Xin_model->read_user_info($r->created_by);
			if(in_array('38',role_resource_ids())) {
				$download_perm='<li><a href="'.site_url().'payroll/pdf_leave_settlement/'.$r->make_payment_id .'" class="text-success"><i class="icon-printer"></i> Generate PDF</a></li>';
			}

			$data[] = array(
				change_fletter_caps($user[0]->first_name.' '.$user[0]->middle_name.' '.$user[0]->last_name),
				format_date('Y F',$r->payment_date),
				round($r->payment_amount_to_employee,2),
				$this->Xin_model->set_date_format($r->leave_settlement_start_date),
				$this->Xin_model->set_date_format($r->leave_settlement_end_date),
				$this->Xin_model->set_date_format($r->created_at).' '.format_date('H:i:s',$r->created_at),
				change_fletter_caps($added_by[0]->first_name.' '.$added_by[0]->middle_name.' '.$added_by[0]->last_name),
				'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right"><li><a href="#" data-toggle="modal" data-target=".detail_modal_data" data-employee_id="'. $r->employee_id . '" data-pay_id="'. $r->make_payment_id . '"><i class="icon-eye4"></i> View Details</a></li><li><a class="delete" href="#" data-record-id="'. $r->make_payment_id .'"><i class="icon-trash"></i> Delete</a></li>'.$download_perm.'</ul></li></ul>',
			);
		}
		$output = array(
			"draw" => $draw,
			"recordsTotal" => $leave_settlement_list->num_rows(),
			"recordsFiltered" => $leave_settlement_list->num_rows(),
			"data" => $data
		);

		echo json_encode($output);
		exit();
	}

	public function make_all_payment(){
		if($this->input->post('add_type')=='make_all_payment') {
			$Return = array('result'=>'', 'error'=>'');
			if(!$this->input->post('id')) {
				$Return['error'] = "Select atlease one checkbox.";
			}

			if($Return['error']!=''){
				$this->output($Return);
			}


			$paydate=$this->input->post('paydate');
			$employee_id=$this->input->post('id');
			if(!$this->input->post('location'))
				$employee_id= array_column($this->Xin_model->get_all_active_users($this->input->post('location'),$this->input->post('department')), 'user_id');

			$status=$this->input->post('status');
		
			$attn_date=salary_start_end_date($paydate);
			$start_dt=$attn_date['exact_date_start'];
			$end_dt=$attn_date['exact_date_end'];

			$attendance=explode('-',$paydate);
			$days_count_per_month=cal_days_in_month(CAL_GREGORIAN,$attendance[1],$attendance[0]);
			$calendar_start_date=$paydate.'-01';
			$calendar_end_date=$paydate.'-'.$days_count_per_month;

			$pre_month=date('Y-m',strtotime("-1 month",strtotime($paydate)));
			$pre_month_start_date=$start_dt;
			$pre_month_end_date=date('Y-m-d',strtotime("-1 Day",strtotime($calendar_start_date)));


			$emp_inc=0;
			$emp_hold_rem_inc=0;
			$setting = $this->Xin_model->read_setting_info(1);

			$paymentData = [];
			foreach($employee_id as $all_id){
				$user_info = $this->Xin_model->read_user_info($all_id);

				$date_of_joining=$user_info[0]->date_of_joining;
				$date_of_leaving=$user_info[0]->date_of_leaving;


				if($status==3){
					$remove_payment_hold=$this->Payroll_model->remove_payment_hold($all_id,$paydate);
					$emp_hold_rem_inc++;
				}else{
					//check_already_paid_or_not
					$check_already_paid_or_not=$this->Payroll_model->check_already_paid_or_not($all_id,$paydate);
					if($check_already_paid_or_not==''){ // If Not Continue start

						$get_payroll_details=$this->payslip_list_new($all_id,$paydate,'','');
						$attn_date=salary_start_end_date($paydate);
						$start_dt=$attn_date['exact_date_start'];
						$end_dt=$attn_date['exact_date_end'];
						$shift_hours=$get_payroll_details['shift_hours'];
						$check_365days_enabled=$get_payroll_details['check_365days_enabled'];
						$find_internal_add_adjustments = $this->Payroll_model->find_adjustments($all_id,$start_dt,$end_dt,'internal_adjustments','Addition');
						$find_internal_ded_adjustments = $this->Payroll_model->find_adjustments($all_id,$start_dt,$end_dt,'internal_adjustments','Deduction');

						$find_external_perp_adjustments = $this->Payroll_model->find_ext_adjustments($all_id,$start_dt,$end_dt,'external_adjustments','Perpetual');
						$find_external_nonperp_adjustments = $this->Payroll_model->find_ext_adjustments($all_id,$start_dt,$end_dt,'external_adjustments','Non-Perpetual');

						$adds=0;
						$deds=0;
						$perp=0;

						$adjustment_id=[];
						$parent_type=[];
						$child_type=[];
						$amount=[];
						$salary_comments=[];

						$driver_delivery_count=json_decode($get_payroll_details['driver_delivery_details']);
						if($driver_delivery_count){
							if($driver_delivery_count->agreed_bonus!=null || $driver_delivery_count->agreed_bonus!=0){
								$driver_incentives=$this->Payroll_model->read_salary_type_name('Incentives');
								$adds+=$driver_delivery_count->agreed_bonus;

								$data_adj = array(
									'adjustment_type' => $driver_incentives[0]->type_parent,
									'adjustment_name' => $driver_incentives[0]->type_id,
									'adjustment_amount' => $driver_delivery_count->agreed_bonus,
									'adjustment_for_employee' => $all_id,
									'adjustment_perpared_by' => $this->userSession['user_id'],
									'salary_type' => 'internal_adjustments',
									'end_date' => $paydate.'-01',
									'comments' => 'Top Delivery Bonus',
									'status' => '0',
									'created_by' => $this->userSession['user_id'],
								);
								$result = $this->Payroll_model->add_adjustments($data_adj);
								$affected_id= table_max_id('xin_salary_adjustments','adjustment_id');
								$adjus=$affected_id['field_id'];
								$adjustment_id[]=$adjus;
								$parent_type[]=$driver_incentives[0]->type_parent;
								$child_type[]=$driver_incentives[0]->type_id;
								$amount[]=$driver_delivery_count->agreed_bonus;
								$salary_comments[]='Top Delivery Bonus';
							}
						}

						if($find_internal_add_adjustments){
							foreach($find_internal_add_adjustments as $int_add_adjustments){
								$adds+=$int_add_adjustments->adjustment_amount;
								$adjustment_id[]=$int_add_adjustments->adjustment_id;
								$parent_type[]=$int_add_adjustments->adjustment_type;
								$child_type[]=$int_add_adjustments->adjustment_name;
								$amount[]=$int_add_adjustments->adjustment_amount;
								$salary_comments[]=$int_add_adjustments->comments;
							}
						}

						if($find_internal_ded_adjustments){
							foreach($find_internal_ded_adjustments as $int_ded_adjustments){
								$deds+=$int_ded_adjustments->adjustment_amount;
								$adjustment_id[]=$int_ded_adjustments->adjustment_id;
								$parent_type[]=$int_ded_adjustments->adjustment_type;
								$child_type[]=$int_ded_adjustments->adjustment_name;
								$amount[]=$int_ded_adjustments->adjustment_amount;
								$salary_comments[]=$int_ded_adjustments->comments;
							}
						}

						if($find_external_perp_adjustments){
							foreach($find_external_perp_adjustments as $ext_perp_adjustments){

								//$ext_perp_adjustments_amount=(($ext_perp_adjustments->adjustment_amount)-($ext_perp_adjustments->adjustment_amount*($ext_perp_adjustments->tax_percentage/100)));
								$ext_perp_adjustments_amount=$ext_perp_adjustments->adjustment_amount;

								if($ext_perp_adjustments->compute_amount==0){
									if($ext_perp_adjustments->child_type_name=='Agency Fees'){

										// DOJ
										if((strtotime($start_dt) <= strtotime($date_of_joining)) && (strtotime($end_dt) >= strtotime($date_of_joining))){

											$date1=new DateTime($date_of_joining);

											if((strtotime($start_dt) <= strtotime($date_of_leaving)) && (strtotime($end_dt) >= strtotime($date_of_leaving))){
												$date2=new DateTime($date_of_leaving);
											}else{
												$date2=new DateTime($calendar_end_date);
											}
											$interval_date=$date2->diff($date1);
											$no_of_days_worked=$interval_date->days+1;

											$computed_perp_amount=($ext_perp_adjustments_amount/$days_count_per_month)*$no_of_days_worked;
											$perp+=$computed_perp_amount;

										}else if((strtotime($start_dt) <= strtotime($date_of_leaving)) && (strtotime($end_dt) >= strtotime($date_of_leaving))){


											$date1=new DateTime($calendar_start_date);

											$date2=new DateTime($date_of_leaving);
											$interval_date=$date2->diff($date1);
											$no_of_days_worked=$interval_date->days+1;

											$computed_perp_amount=($ext_perp_adjustments_amount/$days_count_per_month)*$no_of_days_worked;
											$perp+=$computed_perp_amount;
											// DOJ
										}else{

											$computed_perp_amount=$ext_perp_adjustments_amount;
											$perp+=$computed_perp_amount;
										}
									}else{
										$computed_perp_amount=$ext_perp_adjustments_amount;
										$perp+=$computed_perp_amount;

									}
								}else{

									//
									if($ext_perp_adjustments->child_type_name=='Agency Fees'){
										// DOJ
										if((strtotime($start_dt) <= strtotime($date_of_joining)) && (strtotime($end_dt) >= strtotime($date_of_joining))){


											if((strtotime($pre_month_start_date) <= strtotime($date_of_joining)) && (strtotime($pre_month_end_date) >= strtotime($date_of_joining))){

												$computed_perp_amount=(($ext_perp_adjustments_amount/$get_payroll_details['required_working_hours'])*$get_payroll_details['total_working_hours'])+(($ext_perp_adjustments_amount/$get_payroll_details['p_m_required_working_hours'])*$get_payroll_details['joining_month_hours']);
												$perp+=$computed_perp_amount;


											}else if((strtotime($calendar_start_date) <= strtotime($date_of_joining)) && (strtotime($calendar_end_date) >= strtotime($date_of_joining))){
												$computed_perp_amount=(($ext_perp_adjustments_amount/$get_payroll_details['required_working_hours'])*$get_payroll_details['total_working_hours']);
												$perp+=$computed_perp_amount;
											}
										}else if((strtotime($start_dt) <= strtotime($date_of_leaving)) && (strtotime($end_dt) >= strtotime($date_of_leaving))){
											$computed_perp_amount=(($ext_perp_adjustments_amount/$get_payroll_details['required_working_hours'])*$get_payroll_details['total_working_hours']);
											$perp+=$computed_perp_amount;
											// DOJ
										}else{
											if($check_365days_enabled==1){
												$computed_perp_amount=$ext_perp_adjustments_amount-(($get_payroll_details['required_working_hours']-$get_payroll_details['total_working_hours'])*($ext_perp_adjustments_amount*12/365/$shift_hours));
												$perp+=$computed_perp_amount;
											}else{

												$computed_perp_amount=(($ext_perp_adjustments_amount/$get_payroll_details['required_working_hours'])*$get_payroll_details['total_working_hours']);
												$perp+=$computed_perp_amount;

											}

										}
									}else{
										$computed_perp_amount=(($ext_perp_adjustments_amount/$get_payroll_details['required_working_hours'])*$get_payroll_details['total_working_hours']);
										$perp+=$computed_perp_amount;
									}
									//




								}

								$parent_type[]=$ext_perp_adjustments->adjustment_type;
								$child_type[]=$ext_perp_adjustments->adjustment_name;
								$amount[]=round($computed_perp_amount,2);
								$salary_comments[]=$ext_perp_adjustments->comments;
							}
						}

						if($find_external_nonperp_adjustments){
							foreach($find_external_nonperp_adjustments as $ext_nonperp_adjustments){

								//$ext_nonperp_adjustments_amount=(($ext_nonperp_adjustments->adjustment_amount)-($ext_nonperp_adjustments->adjustment_amount*($ext_nonperp_adjustments->tax_percentage/100)));
								$ext_nonperp_adjustments_amount=$ext_nonperp_adjustments->adjustment_amount;

								if($ext_nonperp_adjustments->compute_amount==0){
									$computed_nonperp_amount=$ext_nonperp_adjustments_amount;
									$perp+=$computed_nonperp_amount;
								}else{
									$computed_nonperp_amount=(($ext_nonperp_adjustments_amount/$get_payroll_details['required_working_hours'])*$get_payroll_details['total_working_hours']);
									$perp+=$computed_nonperp_amount;
								}

								$parent_type[]=$ext_nonperp_adjustments->adjustment_type;
								$child_type[]=$ext_nonperp_adjustments->adjustment_name;
								$amount[]=round($computed_nonperp_amount,2);
								$salary_comments[]=$ext_nonperp_adjustments->comments;
							}
						}


						$perp=round($perp,2);

						if($status==1){
							$comments='Transaction Completed.';
						}else{
							$comments='Transaction Hold.';
						}
						$ot_day_rate=0;$ot_night_rate=0;$ot_holiday_rate=0;
						if($get_payroll_details['ot_hours_amount']!=null){
							$ot_hours_amount=json_decode($get_payroll_details['ot_hours_amount']);
							$ot_day_rate=$ot_hours_amount->ot_day_amount;
							$ot_night_rate=$ot_hours_amount->ot_night_amount;
							$ot_holiday_rate=$ot_hours_amount->ot_holiday_amount;

						}

						$N_salary=($get_payroll_details['total_salary']+$adds+$ot_day_rate+$ot_night_rate+$ot_holiday_rate+$perp)-$deds;
						///////////////


						/*Tax*/
						$visa_id=$get_payroll_details['visa_type'];
						$tax_type=get_tax_info($visa_id);
						$total_tax_amount=0;
						$tax_value=[];
						if($tax_type){
							foreach($tax_type as $tax_t){
								$tax_amount=($N_salary*($tax_t->type_symbol/100));
								$total_tax_amount+=$tax_amount;
								$tax_value[]=array('tax_name'=>$tax_t->type_name,'tax_percentage'=>$tax_t->type_symbol,'tax_amount'=>$tax_amount);
							}
						}
						$payment_amount_with_tax=$N_salary+$total_tax_amount;


						/*Tax*/
						$driver_delivery_count=json_decode($get_payroll_details['driver_delivery_details']);

						if($get_payroll_details['other_allowance']!=''){
							$other_allowance=$get_payroll_details['other_allowance'];
						}else{$other_allowance=0;}
						if($get_payroll_details['house_rent_allowance']!=''){
							$house_rent_allowance=$get_payroll_details['house_rent_allowance'];
						}else{$house_rent_allowance=0;}
						if($get_payroll_details['travelling_allowance']!=''){
							$travelling_allowance=$get_payroll_details['travelling_allowance'];
						}else{$travelling_allowance=0;}
						if($get_payroll_details['food_allowance']!=''){
							$food_allowance=$get_payroll_details['food_allowance'];
						}else{$food_allowance=0;}
						if($get_payroll_details['additional_benefits']!=''){
							$additional_benefits=$get_payroll_details['additional_benefits'];
						}else{$additional_benefits=0;}
						if($get_payroll_details['bonus']!=''){
							$bonus=$get_payroll_details['bonus'];
						}else{$bonus=0;}

						if($get_payroll_details['leave_start_date']!=''){
							$leave_start_date=$get_payroll_details['leave_start_date'];
						}else{$leave_start_date='';}
						if($get_payroll_details['leave_end_date']!=''){
							$leave_end_date=$get_payroll_details['leave_end_date'];
						}else{$leave_end_date='';}

						$data = array(
							'employee_id' => $this->Xin_model->isNull( $get_payroll_details['user_id'] ),
							'department_id' => $this->Xin_model->isNull( $get_payroll_details['department_id'] ),
							'company_id' => $this->Xin_model->isNull( $get_payroll_details['company_id'] ),
							'location_id' => $this->Xin_model->isNull( $get_payroll_details['office_location_id'] ),
							'designation_id' => $this->Xin_model->isNull( $get_payroll_details['designation_id'] ),
							'payment_date' => $this->Xin_model->isNull( $paydate ),
							'payment_amount' => $this->Xin_model->isNull( $N_salary ),
							'payment_amount_to_employee' => $this->Xin_model->isNull( ($N_salary-$perp) ),
							'basic_salary' => $this->Xin_model->isNull( $get_payroll_details['basic_salary'] ),
							//'gross_salary' => $this->Xin_model->isNull( $get_payroll_details['gross_salary'] ),
							//'net_salary' => $this->Xin_model->isNull( $get_payroll_details['net_salary'] ),
							'total_salary' => $this->Xin_model->isNull( $get_payroll_details['total_salary'] ),
							'salary_with_bonus' => $this->Xin_model->isNull( $get_payroll_details['total_salary'] ),
							'salary_template_id' => $this->Xin_model->isNull( $get_payroll_details['salary_template_id'] ),
							'bonus' => $this->Xin_model->isNull( $bonus ),
							'salary_components' => json_encode([
								'house_rent_allowance' => $this->Xin_model->isNull( $house_rent_allowance ),
								'other_allowance' => $this->Xin_model->isNull( $other_allowance ),
								'travelling_allowance' => $this->Xin_model->isNull( $travelling_allowance ),
								'food_allowance' => $this->Xin_model->isNull( $food_allowance ),
								'additional_benefits' => $this->Xin_model->isNull( $additional_benefits ),
							]),
							'required_working_hours' => $this->Xin_model->isNull( $get_payroll_details['required_working_hours'] ),
							'total_working_hours' => $this->Xin_model->isNull( $get_payroll_details['total_working_hours'] ),
							'late_working_hours' => $this->Xin_model->isNull( $get_payroll_details['late_working_hours'] ),
							'rate_per_hour_contract_bonus' => $this->Xin_model->isNull( $get_payroll_details['rate_per_hour_contract_bonus'] ),
							'rate_per_hour_contract_only' => $this->Xin_model->isNull( $get_payroll_details['rate_per_hour_contract_only'] ),
							'rate_per_hour_basic_only' => $this->Xin_model->isNull( $get_payroll_details['rate_per_hour_basic_only'] ),
							'ot_day_rate' => $this->Xin_model->isNull( $get_payroll_details['ot_day_rate'] ),
							'ot_night_rate' => $this->Xin_model->isNull( $get_payroll_details['ot_night_rate'] ),
							'ot_holiday_rate' => $this->Xin_model->isNull( $get_payroll_details['ot_holiday_rate'] ),
							'ot_hours_amount' => $this->Xin_model->isNull( $get_payroll_details['ot_hours_amount'] ),
							'leave_salary_paid' => $this->Xin_model->isNull( $get_payroll_details['leave_salary_paid'] ),
							'leave_salary_amount' => $this->Xin_model->isNull( $get_payroll_details['leave_salary'] ),
							'actual_days_worked' => $this->Xin_model->isNull( $get_payroll_details['actual_days_worked'] ),
							'is_payment' => $this->Xin_model->isNull( 1 ),
							'payment_method' => $this->Xin_model->isNull( 150 ),
							'comments' => $this->Xin_model->isNull( $comments ),
							'status' => $this->Xin_model->isNull( $status ),
							'created_at' => ( date('d-m-Y h:i:s') ),
							'currency' => $this->Xin_model->isNull( $this->Xin_model->currency_sign('',$location_id='',$get_payroll_details['user_id']) ),
							'leave_start_date' => $this->Xin_model->isNull( @$leave_start_date ),
							'leave_end_date' => $this->Xin_model->isNull( @$leave_end_date ),
							'annual_leave_salary' => $this->Xin_model->isNull( $get_payroll_details['annual_leave_salary'] ),
							'month_salary' => $this->Xin_model->isNull( $get_payroll_details['month_salary'] ),
							'joining_month_salary' => $this->Xin_model->isNull( $get_payroll_details['joining_month_salary'] ),
							'payment_amount_with_tax'=> $this->Xin_model->isNull(payment_amount_with_tax ),
							'tax_amount'=> json_encode($tax_value) ,
							'driver_delivery_details'=> json_encode($driver_delivery_count) ,
						);


						$result = $this->Payroll_model->add_monthly_payment_payslip($data);


						if($adjustment_id){
							foreach($adjustment_id as $adjus){
								$this->Payroll_model->update_salary_adjustments_status($adjus,1);
							}
						}

						if($parent_type){
							$count_type=count($parent_type);
							$payment_return_id=$result;
							$created_at=date('d-m-Y h:i:s');

							for($i=0;$i<$count_type;$i++){
								$data_options=array('make_payment_id'=>$payment_return_id,'parent_type'=>$parent_type[$i],'child_type'=>$child_type[$i],'amount'=>$amount[$i],'comments'=>$salary_comments[$i],'created_at'=>$created_at,'payment_date'=>$paydate);
								$result1 = $this->Payroll_model->add_payment_options($data_options);
							}

						}


						// For Mail

						if($setting[0]->enable_email_notification == 'yes' && $status==1) {
							//$this->load->library('email');
							//error_reporting(E_ALL);
							//$this->email->set_mailtype("html");
							//$cinfo = $this->Xin_model->read_company_setting_info(1);
							//$template = $this->Xin_model->read_email_template(1);
							//							$user_info = $this->Xin_model->read_user_info($all_id);
							//							//echo "<pre>";print_r($user_info);
							//							$full_name = change_fletter_caps($user_info[0]->first_name.' '.$user_info[0]->middle_name.' '.$user_info[0]->last_name);
							//							$d = explode('-',$paydate);
							//							$get_month = date('F', mktime(0, 0, 0, $d[1], 10));
							//							$pdate = $get_month.', '.$d[0];
							//							$subject = $template[0]->subject.' - '.$cinfo[0]->company_name;
							//							$logo = base_url().'uploads/logo/'.$cinfo[0]->logo;
							//							$cid = $this->email->attachment_cid($logo);
							//							$message = '<div style="background: #f7eaea;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin: 0 auto;padding:20px;max-width: 65em;border: 2px solid #D40732;">
							//		    '.str_replace(array("{var site_name}","{var site_url}","{var employee_name}","{var payslip_date}"),array($cinfo[0]->company_name,site_url(),$full_name,$pdate),html_entity_decode(stripslashes($template[0]->message))).'</div>';
							//
							//							if(TESTING_MAIL==TRUE){
							//								$this->email->from(FROM_MAIL, $cinfo[0]->company_name);
							//								$this->email->to(TO_MAIL);
							//							}else{
							//								$this->email->from(FROM_MAIL, $cinfo[0]->company_name);//$cinfo[0]->email
							//								$this->email->to($user_info[0]->email);
							//							}
							//
							//							$this->email->subject($subject);
							//							$this->email->message($message);
							//							$attach_url=$this->pdf_create('','',$result);
							//							//$this->email->attach('http://192.168.1.27/workablezone/payslip_testing_november_2017.pdf');
							//							$this->email->attach($attach_url);
							//							//$this->email->send();
							//							$this->email->clear(TRUE);
							//							unlink($attach_url);

						}
						// For Mail
						$emp_inc++;
					} // If Not Continue End
				}



			}

			//if ($result) { // == TRUE
			if($status==1){
				$Return['result'] = 'Payment paid for '.$emp_inc.' Employees';
				$Return['employees'] = $employee_id;
				//$Return['data'] = $paymentData;
			}else if($status==2){
				$Return['result'] = 'Payment put on hold for '.$emp_inc.' Employees';
			}else if($status==3){
				$Return['result'] = 'Remove the Payment hold for '.$emp_hold_rem_inc.' Employees';
			}
			$this->output($Return);
			exit;

		}

	}

	public function dynamic_salary_type(){
		$count_id=$_GET['count_id'];
		$html='';
		$parent_salary_type=$this->Payroll_model->get_salaray_type('parent');
		$html.='<div class="row" id="parent_div_'.$count_id.'"><div class="col-md-3"><div class="form-group"><label for="payment_method">Parent Type</label><select  required name="parent_type[]" class="select2" data-plugin="select_hrm" data-placeholder="Choose Type..." onchange="getParentChildType(this.value,'.$count_id.')"><option value="">&nbsp;</option>';

		foreach($parent_salary_type as $parent_type){
			$html.='<option value="'.$parent_type->type_id.'">'.$parent_type->type_name.'</option>';
		}
		$html.='</select></div></div><div class="col-md-3"><label for="payment_method">Child Type</label><select required name="child_type[]" class="select2"    data-plugin="select_hrm" data-placeholder="Choose..." id="child_type_'.$count_id.'"><option value="">&nbsp;</option></select></div>
			<div class="col-md-2"><label for="payment_method">Amount</label><input id="child_amount_'.$count_id.'" class="form-control salary" placeholder="Amount" name="amount[]" value="" type="text" pattern="\d*\.?\d*" title="Format should be 0000 or 0000.00" required></div><div class="col-md-3"><label for="payment_method" >Comments</label><textarea name="salary_comments[]" class="form-control"></textarea></div><div class="col-md-1" onclick="delete_append_div('.$count_id.')"><i class="icon-trash" style="cursor:pointer;font-size: 1.5em;padding-top: 2.5em;color: red"></i></div></div>';

		echo $html;
	}

	public function dynamic_add_salary_type(){
		$count_id=$_GET['count_id'];
		$html='';
		$add_salary_type=$this->Payroll_model->get_salaray_type_slug('parent',1);
		$html.='<div class="col-lg-12 mb-20" id="add_parent_div_'.$count_id.'">	
		<input type="hidden" name="parent_type[]" value="30" />
		<div class="col-lg-4 no-padding-left"><select required name="child_type[]" class="select2" data-plugin="select_hrm" data-placeholder="Choose Type..."><option value="">&nbsp;</option>';
		foreach($add_salary_type as $add_type){
			$html.='<option value="'.$add_type->type_id.'">'.$add_type->type_name.'</option>';
		}
		$html.='</select></div><div class="col-lg-3"><input id="child_amount_'.$count_id.'" class="form-control salary add_action" placeholder="Amount" name="amount[]" value="" type="text" pattern="\d*\.?\d*" title="Format should be 0000 or 0000.00" required></div><div class="col-lg-4"><textarea name="salary_comments[]" class="form-control" placeholder="Comments"></textarea></div><div class="col-lg-1" onclick="delete_add_append_div('.$count_id.')"><i class="icon-trash" style="cursor:pointer;color: red;padding-top: 1.5em;"></i></div></div>';
		echo $html;
	}

	public function dynamic_deduct_salary_type(){
		$count_id=$_GET['count_id'];
		$html='';
		$add_salary_type=$this->Payroll_model->get_salaray_type_slug('parent',0);
		$html.='<div class="col-lg-12 mb-20" id="deduct_parent_div_'.$count_id.'">	
		<input type="hidden" name="parent_type[]" value="30" />
		<div class="col-lg-4 no-padding-left"><select required name="child_type[]" class="select2" data-plugin="select_hrm" data-placeholder="Choose Type..."><option value="">&nbsp;</option>';
		foreach($add_salary_type as $add_type){
			$html.='<option value="'.$add_type->type_id.'">'.$add_type->type_name.'</option>';
		}
		$html.='</select></div><div class="col-lg-3"><input id="child_amount_'.$count_id.'" class="form-control salary deduct_action" placeholder="Amount" name="amount[]" value="" type="text" pattern="\d*\.?\d*" title="Format should be 0000 or 0000.00" required></div><div class="col-lg-4"><textarea name="salary_comments[]" class="form-control" placeholder="Comments"></textarea></div><div class="col-lg-1" onclick="delete_deduct_append_div('.$count_id.')"><i class="icon-trash" style="cursor:pointer;color: red;padding-top: 1.5em;"></i></div></div>';
		echo $html;
	}

	public function getChildType(){
		$value=$this->uri->segment(3);
		$id=$this->uri->segment(4);
		$parent_salary_type=$this->Payroll_model->get_salaray_type($value);
		$get_action_type=$this->Payroll_model->get_action_type($value);
		if($get_action_type==1){
			$class='add_action';
		}else{
			$class='deduct_action';
		}
		$html='';
		$html.='option value="">&nbsp;</option>';
		foreach($parent_salary_type as $parent_type){
			$selected='';
			if($id!=''){
				if($id==$parent_type->type_id){
					$selected='selected';
				}
			}
			$html.='<option '.$selected.' value="'.$parent_type->type_id.'">'.$parent_type->type_name.'</option>';
		}
		echo json_encode(array('html'=>$html,'class'=>$class));
	}

	public function get_currency_byemployee(){
		$id=$this->uri->segment(3);
		$html=$this->Xin_model->currency_sign('','',$id);
		echo json_encode(array('html'=>$html));
	}

	public function get_employee_working_hours(){
		$id=$this->uri->segment(3);
		$user_info = $this->Xin_model->read_user_info($id);
		$d_hours=explode(':',$user_info[0]->working_hours);
		if(@$d_hours[1]){ $d_mins=@$d_hours[1];} else {$d_mins='0';}
		$f_total_hours = new DateTime($d_hours[0].':'.$d_mins);
		$f_lunch_hours = new DateTime(LUNCH_HOURS);
		$interval_lunch = $f_total_hours->diff($f_lunch_hours);

		$interval_lunch = $f_total_hours;
		if($user_info[0]->is_break_included == 1)
			$interval_lunch = $f_total_hours->diff($f_lunch_hours);

		$total_work=$interval_lunch->format('%h').'h '.$interval_lunch->format('%i').'m';
		$shift_hours = decimalHours($total_work);

		$query = $this->db->query("SELECT count(adjustment_id) as counts FROM `xin_salary_adjustments` WHERE `adjustment_for_employee` = '".$id."' AND adjustment_type='30' AND adjustment_name='57' limit 1");
		$count_result = $query->result();
		if($count_result){
			$counts=$count_result[0]->counts;
		}else{
			$counts=0;
		}


		$message='';
		$date_of_joining=$user_info[0]->date_of_joining;
		if($date_of_joining!=''){
			$f_s_total_years = new DateTime($date_of_joining);
			$f_e_total_years = new DateTime(TODAY_DATE);
			$interval_years = $f_s_total_years->diff($f_e_total_years);
			$eligible_air_ticket=($interval_years->y%2);
			$given_airticket=round($interval_years->y/2);
			if($eligible_air_ticket==0 && $given_airticket!=$counts){
				$message='Eligible to get Air Tickets Now.';
			}
		}
		echo json_encode(array('html'=>change_fletter_caps($user_info[0]->first_name.' '.$user_info[0]->middle_name.' '.$user_info[0]->last_name).'\'s Working Hours : '.$total_work.'<br>'.$message));
	}

	public function make_payment_view()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('pay_id');
		// $data['all_countries'] = $this->xin_model->get_countries();
		$result = $this->Payroll_model->read_make_payment_information($id);
		// get addd by > template
		$user = $this->Xin_model->read_user_info($result[0]->employee_id);
		// get designation
		$designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
		// department
		$department = $this->Department_model->read_department_information($user[0]->department_id);
		$data = array(
			'first_name' => $user[0]->first_name,
			'middle_name' => $user[0]->middle_name,
			'last_name' => $user[0]->last_name,
			'employee_id' => $user[0]->employee_id,
			'department_name' => $department[0]->department_name,
			'designation_name' => $designation[0]->designation_name,
			'date_of_joining' => $user[0]->date_of_joining,
			'profile_picture' => $user[0]->profile_picture,
			'gender' => $user[0]->gender,
			'basic_salary' => $result[0]->basic_salary,
			'payment_date' => $result[0]->payment_date,
			'payment_method' => $result[0]->payment_method,
			'salary_components' => $result[0]->salary_components,
			'bonus' => $result[0]->bonus,
			'is_payment' => $result[0]->is_payment,
			'total_working_hours' => $result[0]->total_working_hours,
			'late_working_hours' => $result[0]->late_working_hours,
			'total_salary' => $result[0]->total_salary,
			'additonal_salary' => $this->Payroll_model->get_additional_salary($result[0]->make_payment_id,'Addition'),
			'deduction_salary' => $this->Payroll_model->get_additional_salary($result[0]->make_payment_id,'Deduction'),
			'find_external_perp_adjustments' => $this->Payroll_model->get_additional_salary($result[0]->make_payment_id,'Perpetual'),
			'find_external_nonperp_adjustments' => $this->Payroll_model->get_additional_salary($result[0]->make_payment_id,'Non-Perpetual'),
			'salary_with_bonus' => $result[0]->salary_with_bonus,
			'comments' => $result[0]->comments,
			'payment_amount' => $result[0]->payment_amount,
			'payment_amount_to_employee' => $result[0]->payment_amount_to_employee,
			'payment_amount_with_tax' => $result[0]->payment_amount_with_tax,
			'tax_amount' => json_decode($result[0]->tax_amount),
			'currency' => $result[0]->currency,
		);
		if(!empty($this->userSession)){
			$this->load->view('payroll/dialog_payslip', $data);
		}
		else {
			redirect('');
		}
	}

	// pay monthly > create payslip
	public function pay_monthly()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('employee_id');
		// get addd by > template
		$user = $this->Xin_model->read_user_info($id);
		$result = $this->Payroll_model->read_template_information_byempid($id);
		$department = $this->Department_model->read_department_information($user[0]->department_id);
		$location = $this->Location_model->read_location_information($department[0]->location_id);
		if($result){
			$data = array(
				'department_id' => $user[0]->department_id,
				'designation_id' => $user[0]->designation_id,
				'location_id' => $location[0]->location_id,
				'company_id' => $location[0]->company_id,
				'salary_template_id' => $result[0]->salary_template_id,
				'user_id' => $user[0]->user_id,
				'salary_with_bonus' => $result[0]->salary_with_bonus,
				'added_by' => $result[0]->added_by,
			);
		}
		if(!empty($this->userSession)){
			$this->load->view('payroll/dialog_make_payment', $data);
		} else {
			redirect('');
		}
	}

	// get payroll template info by id
	public function template_read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('salary_template_id');
		$country_id = $this->input->get('country_id');
		$result = $this->Payroll_model->read_template_information($id);
		if($result[0]->effective_to_date!=''){$effective_to_date=format_date('d F Y',$result[0]->effective_to_date);}else {$effective_to_date='';}
		$data = array(
			'user_id'=>$result[0]->employee_id,
			'salary_template_id'=>$result[0]->salary_template_id,
			'salary_based_on_contract' => $result[0]->salary_based_on_contract,
			'salary_with_bonus' =>  $result[0]->salary_with_bonus,
			'is_approved' =>  $result[0]->is_approved,
			'effective_from_date' => format_date('d F Y',$result[0]->effective_from_date),
			'effective_to_date' => $effective_to_date,
			'salary_fields'=> get_salary_fields($result[0]->salary_template_id,$country_id)
		);
		if(!empty($this->userSession)){
			$this->load->view('payroll/dialog_templates', $data);
		} else {
			redirect('');
		}
	}

	// get payroll template info by id
	public function payroll_template_read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('employee_id');
		// get addd by > template
		$user = $this->Xin_model->read_user_info($id);
		// user full name
		$full_name = change_fletter_caps($user[0]->first_name.' '.$user[0]->middle_name.' '.$user[0]->last_name);
		// get designation
		$designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
		// department
		$department = $this->Department_model->read_department_information($user[0]->department_id);
		$data = array(
			'first_name' => $user[0]->first_name,
			'middle_name' => $user[0]->middle_name,
			'last_name' => $user[0]->last_name,
			'employee_id' => $user[0]->employee_id,
			'department_name' => $department[0]->department_name,
			'designation_name' => $designation[0]->designation_name,
			'date_of_joining' => $user[0]->date_of_joining,
			'profile_picture' => $user[0]->profile_picture,
			'gender' => $user[0]->gender

		);
		if(!empty($this->userSession)){
			$this->load->view('payroll/dialog_templates', $data);
		} else {
			redirect('');
		}
	}

	// get hourly wage template info by id
	public function hourlywage_template_read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('employee_id');
		// get addd by > template
		$user = $this->Xin_model->read_user_info($id);
		// user full name
		$full_name = $user[0]->first_name.' '.$user[0]->middle_name.' '.$user[0]->last_name;
		// get designation
		$designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
		// department
		$department = $this->Department_model->read_department_information($user[0]->department_id);
		$data = array(
			'first_name' => $user[0]->first_name,
			'middle_name' => $user[0]->middle_name,
			'last_name' => $user[0]->last_name,
			'employee_id' => $user[0]->employee_id,
			'department_name' => $department[0]->department_name,
			'designation_name' => $designation[0]->designation_name,
			'date_of_joining' => $user[0]->date_of_joining,
			'profile_picture' => $user[0]->profile_picture,
			'gender' => $user[0]->gender
		);
		if(!empty($this->userSession)){
			$this->load->view('payroll/dialog_templates', $data);
		} else {
			redirect('');
		}
	}

	public function all_employees_salary_new(){
		$employees=$this->Payroll_model->all_employees_salary_new();
		$html='<option value="">Select Employee</option>';
		if(!empty($employees)){
			foreach($employees as $employee){
				$employeeid =  $employee->user_id;
				$employeename = change_fletter_caps($employee->first_name.' '.$employee->middle_name.' '.$employee->last_name);
				$html.="<option value='".$employeeid."' >" . change_to_caps($employeename) . "</option>";
			}
			echo $html;
		}else{
			echo "<option value='' >No Employees Found.</option>";
		}
	}

	// Validate and add info in database
	public function add_template() {
		if($this->input->post('add_type')=='payroll') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'message'=>'');

			/* Server side PHP input validation */
			if($this->input->post('employee_id')==='') {
				$Return['error'] = "The employee field is required.";
			} else if($this->input->post('effective_from_date')==='') {
				$Return['error'] = "The effective from date field is required.";
			}

			$sal_field=$this->input->post('sal_field');
			$employee_id=$this->input->post('employee_id');
			$check_already_added_pay=$this->Payroll_model->if_pay_add_already($employee_id);
			$effective_from_date= format_date('Y-m-d',$this->input->post('effective_from_date'));
			if(@$check_already_added_pay[0]->effective_from_date!=''){
				if($check_already_added_pay[0]->effective_from_date==$effective_from_date){
					$Return['error'] = "The effective from date field already there in out db.";
				}
			}
			if($Return['error']!=''){
				$this->output($Return);
			}
			if(@$check_already_added_pay[0]->effective_from_date!=''){
				$prev_date = date('Y-m-d', strtotime($effective_from_date .' -1 day'));
				$this->Payroll_model->update_effective_to_date(array('effective_to_date'=>$prev_date),$check_already_added_pay[0]->salary_template_id);
			}

			if($this->input->post('effective_to_date')!=''){
				$effective_to_date=format_date('Y-m-d',$this->input->post('effective_to_date'));
			}else{
				$effective_to_date='';
			}

			$data = array(
				'employee_id' => $employee_id,
				'effective_from_date' => $effective_from_date,
				'effective_to_date' => $effective_to_date,
				'salary_based_on_contract' => $this->input->post('salary_based_on_contract'),
				'salary_with_bonus' => $this->input->post('salary_with_bonus'),
				'added_by' => $this->input->post('user_id'),
				'created_at' => date('Y-m-d H:i:s'),
			);
			$result = $this->Payroll_model->add_template($data);

			/*User Logs*/
			$affected_id= table_max_id('xin_salary_templates','salary_template_id');
			userlogs('Employees-Pay Structure-Add','Employee Pay Structure Added',$affected_id['field_id'],$affected_id['datas']);
			/*User Logs*/
			if($result == false) {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			} else {
				if($sal_field){
					foreach($sal_field as $key=>$value){
						$emp_salary_data=array('salary_template_id'=>$result,'salary_employee_id'=>$employee_id,'salary_field_id'=>$key,'salary_amount'=>$value);
						$this->Payroll_model->insert_employee_salary($emp_salary_data);
					}
				}
				$Return['result'] = 'Payroll Template added.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function get_payroll_template_country(){
		if($this->input->get('data')=='payroll_country_listing'){
			$employee_id=$this->input->get('employee_id');
			$result = $this->Employees_model->read_employee_information($employee_id);
			$read_location=$this->Location_model->read_location_information($result[0]->office_location_id);
			if($read_location){
				$country_id=$read_location[0]->country;
			}else{
				$country_id=0;
			}
			$salary_fields=get_salary_fields_bycountry($country_id);
			$html='';
			if($salary_fields){
				foreach($salary_fields as $sal_field){
					if($sal_field->salary_calculate==1){
						$class="salary";
					}else{
						$class='';
					}
					$html.='<div class="col-md-4">
                      <div class="form-group">
                        <label for='.$sal_field->salary_field_name.' class="control-label">'. salary_title_change($sal_field->salary_field_name).'</label>
                        <input class="form-control '.$sal_field->salary_field_name.' '.$class.'" placeholder="'.salary_title_change($sal_field->salary_field_name).'" name="sal_field['.$sal_field->salary_field_id.']" value="" type="text" pattern="\d*\.?\d*" title="'.$this->lang->line('xin_use_numbers_price').'">
                      </div>
                    </div>';
				}
			}
			echo $html;
		}
	}

	// Validate and update info in database
	public function update_template() {
		if($this->input->post('edit_type')=='payroll') {
			$id = $this->uri->segment(3);
			$employee_id=$this->input->post('employee_id');
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');
			$sal_field=$this->input->post('sal_field');
			$count_sal=count(array_filter($sal_field));
			$effective_from_date=$this->input->post('effective_from_date');
			/* Server side PHP input validation */
			if($count_sal==0) {
				$Return['error'] = "The basic component fields are required.";
			}else if($effective_from_date==='') {
				$Return['error'] = "The effective from field is required.";
			}

			if($Return['error']!=''){
				$this->output($Return);
			}
			if($this->input->post('effective_to_date')!=''){
				$effective_to_date=format_date('Y-m-d',$this->input->post('effective_to_date'));
			}else{
				$effective_to_date='';
			}

			$data = array(
				'salary_with_bonus' => $this->input->post('salary_with_bonus'),
				'effective_from_date' => format_date('Y-m-d',$effective_from_date),
				'effective_to_date'=>$effective_to_date,
				'salary_based_on_contract' => $this->input->post('salary_based_on_contract'),
				'is_notification'=>0,
				'is_approved'=>$this->input->post('is_approved'),
				'updated_by' =>$this->userSession['user_id']
			);

			$result = $this->Payroll_model->update_template_record($data,$id);
			if($sal_field){
				foreach($sal_field as $key=>$value){
					$emp_salary_data=array('salary_field_id'=>$key,'salary_amount'=>$value);
					$emp_salary_insertdata=array('salary_template_id'=>$id,'salary_employee_id'=>$employee_id,'salary_field_id'=>$key,'salary_amount'=>$value);
					$this->Payroll_model->update_employee_salary($emp_salary_data,$key,$id,$emp_salary_insertdata);
				}
			}

			/*User Logs*/
			$affected_id= table_update_id('xin_salary_templates','salary_template_id',$id);
			userlogs('Employees-Pay Structure-Update','Employee Pay Structure Updated',$id,$affected_id['datas']);
			/*User Logs*/

			if ($result == TRUE) {
				$Return['result'] = 'Payroll Template updated.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}




	}

	// Validate and update info in database > update salary template
	public function user_salary_template() {
		if($this->input->post('edit_type')=='payroll') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');
			$count = count($this->input->post('grade_status'));
			$Return['result'] = 'Salary Information updated.';
			$this->output($Return);
			exit;
		}
	}
	// Validate and add info in database > add monthly payment

	public function add_pay_monthly() {
		if($this->input->post('add_type')=='add_monthly_payment') {
			$Return = array('result'=>'', 'error'=>'');
			if($this->input->post('payment_method')==='') {
				$Return['error'] = "The payment method field is required.";
			} else if($this->input->post('comments')==='') {
				$Return['error'] = "The comments field is required.";
			}

			if($Return['error']!=''){
				$this->output($Return);
			}

			if($this->input->post('comments')){
				$com=$this->input->post('comments');
			}else{
				$com='';
			}
			$s_end_date=salary_start_end_date($this->input->post('pay_date'));

			$tax_value=[];
			if($this->input->post('tax_name')){
				$count_of_tax=count($this->input->post('tax_name'));
				$tax_name=$this->input->post('tax_name');
				$tax_percentage=$this->input->post('tax_percentage');
				$tax_amount=$this->input->post('tax_amount');
				for($i=0;$i<$count_of_tax;$i++){
					$tax_value[]=array('tax_name'=>$tax_name[$i],'tax_percentage'=>$tax_percentage[$i],'tax_amount'=>$tax_amount[$i]);
				}
			}

			$data = array(
				'employee_id' => $this->input->post('emp_id'),
				'department_id' => $this->input->post('department_id'),
				'company_id' => $this->input->post('company_id'),
				'location_id' => $this->input->post('location_id'),
				'designation_id' => $this->input->post('designation_id'),
				'payment_date' => $this->input->post('pay_date'),
				'payment_amount' => $this->input->post('payment_amount'),
				'payment_amount_to_employee' => ($this->input->post('payment_amount')-$this->input->post('perpertual_amount')),
				'basic_salary' => $this->input->post('basic_salary'),
				'salary_with_bonus' => $this->input->post('salary_with_bonus'),
				'total_salary' => $this->input->post('total_salary'),
				'salary_template_id' => $this->input->post('salary_template_id'),
				'salary_components' => $this->input->post('salary_components'),
				'bonus' => $this->input->post('bonus'),
				'required_working_hours' => $this->input->post('required_working_hours'),
				'total_working_hours' => $this->input->post('total_working_hours'),
				'late_working_hours' => $this->input->post('late_working_hours'),
				'rate_per_hour_contract_bonus' => $this->input->post('rate_per_hour_contract_bonus'),
				'rate_per_hour_contract_only' => $this->input->post('rate_per_hour_contract_only'),
				'rate_per_hour_basic_only' => $this->input->post('rate_per_hour_basic_only'),
				'ot_day_rate' => $this->input->post('ot_day_rate'),
				'ot_night_rate' => $this->input->post('ot_night_rate'),
				'ot_holiday_rate' => $this->input->post('ot_holiday_rate'),
				'leave_salary_paid' => $this->input->post('leave_salary_paid'),
				'leave_salary_amount' =>  $this->input->post('leave_salary_amount'),
				'ot_hours_amount' => $this->input->post('ot_hours_amount'),
				'actual_days_worked' => $this->input->post('actual_days_worked'),
				'is_payment' => '1',
				'payment_method' => $this->input->post('payment_method'),
				'comments' => $com,
				'status' => '1',
				'created_at' => date('Y-m-d H:i:s'),
				'currency' => $this->Xin_model->currency_sign('',$location_id='',$this->input->post('emp_id')),
				'leave_start_date' => $this->input->post('leave_start_date'),
				'leave_end_date' => $this->input->post('leave_end_date'),
				'annual_leave_salary' => $this->input->post('annual_leave_salary'),
				'month_salary' => $this->input->post('month_salary'),
				'joining_month_salary' => $this->input->post('joining_month_salary'),
				'payment_amount_with_tax' => $this->input->post('payment_amount_with_tax'),
				'tax_amount' => json_encode($tax_value),
				'driver_delivery_details'=>$this->input->post('driver_delivery_details'),
			);

			//echo "<pre>";print_r($data);die;
			if($this->input->post('adjustment_id')){
				$adjustment_id=$this->input->post('adjustment_id');
				$adjustment_type=$this->input->post('parent_type');
				$adjustment_name=$this->input->post('child_type');
				$adjustment_amount=$this->input->post('amount');
				$comments=$this->input->post('salary_comments');
				$aj=0;
				foreach($adjustment_id as $adjus){
					if($adjus==0){
						$data_adj = array(
							'adjustment_type' => $adjustment_type[$aj],
							'adjustment_name' => $adjustment_name[$aj],
							'adjustment_amount' => $adjustment_amount[$aj],
							'adjustment_for_employee' => $this->input->post('emp_id'),
							'adjustment_perpared_by' => $this->userSession['user_id'],
							'salary_type' => 'internal_adjustments',
							'end_date' => $this->input->post('pay_date').'-01',
							'comments' => $comments[$aj],
							'status' => '0',
							'created_by' => $this->userSession['user_id'],
						);
						$this->Payroll_model->add_adjustments($data_adj);
						$affected_id= table_max_id('xin_salary_adjustments','adjustment_id');
						$adjus=$affected_id['field_id'];
					}
					$this->Payroll_model->update_salary_adjustments_status($adjus,1);
					$aj++;
				}
			}

			$result = $this->Payroll_model->add_monthly_payment_payslip($data);

			if($this->input->post('parent_type')){
				$count_type=count($this->input->post('parent_type'));
				$payment_return_id=$result;
				$created_at=date('d-m-Y h:i:s');
				$parent_type=$this->input->post('parent_type');
				$child_type=$this->input->post('child_type');
				$amount=$this->input->post('amount');
				$comments=$this->input->post('salary_comments');
				for($i=0;$i<$count_type;$i++){
					$data_options=array('make_payment_id'=>$payment_return_id,'parent_type'=>$parent_type[$i],'child_type'=>$child_type[$i],'amount'=>$amount[$i],'comments'=>$comments[$i],'created_at'=>$created_at,'payment_date'=>$this->input->post('pay_date'));
					$result1 = $this->Payroll_model->add_payment_options($data_options);
				}

			}
			//echo $result;

			if ($result== TRUE) { // == TRUE

				$Return['result'] = 'Payment paid.';


				$setting = $this->Xin_model->read_setting_info(1);
				/*if($setting[0]->enable_email_notification == 'yes') {

				$this->load->library('email');
				$cinfo = $this->Xin_model->read_company_setting_info(1);
				//get email template
				$template = $this->Xin_model->read_email_template(1);
				//get employee info
				$user_info = $this->Xin_model->read_user_info($this->input->post('emp_id'));
				$full_name = change_fletter_caps($user_info[0]->first_name.' '.$user_info[0]->middle_name.' '.$user_info[0]->last_name);
				// get date
				$d = explode('-',$this->input->post('pay_date'));
				$get_month = date('F', mktime(0, 0, 0, $d[1], 10));
				$pdate = $get_month.', '.$d[0];

				$subject = $template[0]->subject.' - '.$cinfo[0]->company_name;
				$logo = base_url().'uploads/logo/'.$cinfo[0]->logo;
				$cid = $this->email->attachment_cid($logo);

				$message = '<div style="background:#f6f6f6;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;padding: 20px;">
			<img src="'.$logo.'" title="'.$cinfo[0]->company_name.'"><br>'.str_replace(array("{var site_name}","{var site_url}","{var employee_name}","{var payslip_date}"),array($cinfo[0]->company_name,site_url(),$full_name,$pdate),html_entity_decode(stripslashes($template[0]->message))).'</div>';
				$this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
				$this->email->to($user_info[0]->email);

				$this->email->subject($subject);
				$this->email->message($message);

				//$this->email->send();
			}*/
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	// Validate and add info in database > add hourly payment
	public function add_pay_hourly() {
		if($this->input->post('add_type')=='pay_hourly') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');
			/* Server side PHP input validation */
			if($this->input->post('payment_method')==='') {
				$Return['error'] = "The payment method field is required.";
			} else if($this->input->post('comments')==='') {
				$Return['error'] = "The comments field is required.";
			}

			if($Return['error']!=''){
				$this->output($Return);
			}

			$data = array(
				'employee_id' => $this->input->post('emp_id'),
				'department_id' => $this->input->post('department_id'),
				'company_id' => $this->input->post('company_id'),
				'location_id' => $this->input->post('location_id'),
				'designation_id' => $this->input->post('designation_id'),
				'payment_date' => $this->input->post('pay_date'),
				'payment_amount' => $this->input->post('payment_amount'),
				'total_hours_work' => $this->input->post('total_hours_work'),
				'hourly_rate' => $this->input->post('hourly_rate'),
				'is_payment' => '1',
				'payment_method' => $this->input->post('payment_method'),
				'comments' => $this->input->post('comments'),
				'status' => '1',
				'created_at' => date('d-m-Y h:i:s')
			);
			$result = $this->Payroll_model->add_hourly_payment_payslip($data);
			if ($result == TRUE) {
				$Return['result'] = 'Payment paid.';

				//get setting info
				$setting = $this->Xin_model->read_setting_info(1);
				if($setting[0]->enable_email_notification == 'yes') {
					// load email library
					$this->load->library('email');
					$this->email->set_mailtype("html");
					//get company info
					$cinfo = $this->Xin_model->read_company_setting_info(1);
					//get email template
					$template = $this->Xin_model->read_email_template(1);
					//get employee info
					$user_info = $this->Xin_model->read_user_info($this->input->post('emp_id'));
					$full_name = change_fletter_caps($user_info[0]->first_name.' '.$user_info[0]->middle_name.' '.$user_info[0]->last_name);
					// get date
					$d = explode('-',$this->input->post('pay_date'));
					$get_month = date('F', mktime(0, 0, 0, $d[1], 10));
					$pdate = $get_month.', '.$d[0];

					$subject = $template[0]->subject.' - '.$cinfo[0]->company_name;
					$logo = base_url().'uploads/logo/'.$cinfo[0]->logo;

					$message = '
					<div style="background:#f6f6f6;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;padding: 20px;">
					<img src="'.$logo.'" title="'.$cinfo[0]->company_name.'"><br>'.str_replace(array("{var site_name}","{var site_url}","{var employee_name}","{var payslip_date}"),array($cinfo[0]->company_name,site_url(),$full_name,$pdate),html_entity_decode(stripslashes($template[0]->message))).'</div>';

					$this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
					$this->email->to($user_info[0]->email);

					$this->email->subject($subject);
					$this->email->message($message);

					//$this->email->send();
				}

			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function adjustments_delete() {
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		$id = $this->uri->segment(3);
		$result = $this->Payroll_model->adjustments_delete($id);
		if(isset($id)) {
			$Return['result'] = 'Adjustments deleted.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
	}

	public function delete_leave_settlement() {
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		$id = $this->uri->segment(3);

		$payment_data=$this->Payroll_model->read_make_payment_information($id);

		if(@$payment_data[0]->leave_settlement_start_date!=''){
			$remove_payment_hold=$this->Payroll_model->remove_payment_leave_hold($payment_data[0]->employee_id,$payment_data[0]->payment_date,$payment_data[0]->leave_settlement_start_date,$payment_data[0]->leave_settlement_end_date);
			$this->db->where('employee_id', $employee_id);
			$this->db->where('type_of_approval', 'Leave Settlement');
			$this->db->where('pay_date', $month_year);
			$this->db->delete('xin_employees_approval');
			if(isset($id)) {
				$Return['result'] = 'Leave Settlement deleted.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
		}else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
	}

	public function delete_template() {
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		$id = $this->uri->segment(3);
		$result = $this->Payroll_model->delete_template_record($id);
		if(isset($id)) {
			$Return['result'] = 'Payroll Template deleted.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
	}

	public function delete_hourly_wage() {
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		$id = $this->uri->segment(3);
		$result = $this->Payroll_model->delete_hourly_wage_record($id);
		if(isset($id)) {
			$Return['result'] = 'Hourly Wage Template deleted.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
	}

	//Internal & External Adjustments
	public function external_adjustments(){
		$data['title'] = $this->Xin_model->site_title();
		$data['all_employees'] = $this->Xin_model->all_employees();
		$data['breadcrumbs'] = 'External Adjustments';
		$data['path_url'] = 'external_adjustments';
		if(in_array('38',role_resource_ids()) || in_array('38a',role_resource_ids()) || in_array('38e',role_resource_ids()) || in_array('38d',role_resource_ids()) || in_array('38v',role_resource_ids()) || visa_wise_role_ids() != '') {
			if(!empty($this->userSession)){
				$data['subview'] = $this->load->view("payroll/external_adjustments", $data, TRUE);
				$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
	}

	public function internal_adjustments(){
		$data['title'] = $this->Xin_model->site_title();
		$data['all_employees'] = $this->Xin_model->all_employees();
		$data['breadcrumbs'] = 'Internal Adjustments';
		$data['path_url'] = 'internal_adjustments';
		if(in_array('38',role_resource_ids()) || in_array('38a',role_resource_ids()) || in_array('38e',role_resource_ids()) || in_array('38d',role_resource_ids()) || in_array('38v',role_resource_ids()) || visa_wise_role_ids() != '') {
			if(!empty($this->userSession)){
				$data['subview'] = $this->load->view("payroll/internal_adjustments", $data, TRUE);
				$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}

	}

	public function add_external_adjustments(){
		if($this->input->post('add_type')=='external_adjustments') {
			$Return = array('result'=>'', 'error'=>'');
			/* Server side PHP input validation */
			$check_unique_adjustments=$this->Payroll_model->check_unique_adjustments($this->input->post('adjustment_type'),$this->input->post('adjustment_name'),$this->input->post('adjustment_for_employee'),'');
			if($this->input->post('adjustment_type')==='') {
				$Return['error'] = "The adjustment type field is required.";
			} else if($this->input->post('adjustment_name')==='') {
				$Return['error'] = "The adjustment name field is required.";
			} else if($this->input->post('adjustment_amount')==='') {
				$Return['error'] = "The adjustment amount field is required.";
			} else if($this->input->post('adjustment_for_employee')==='') {
				$Return['error'] = "The adjustment for employee field is required.";
			} else if($check_unique_adjustments!=0){
				$Return['error'] = "This adjustment already added to this employee.";
			}

			if($Return['error']!=''){
				$this->output($Return);
			}

			if($this->input->post('end_date')!=''){
				$end_date=format_date('Y-m-d',$this->input->post('end_date'));
			}else{
				$end_date='';
			}

			/*if($this->input->post('tax_percentage')!=''){
				$tax_percentage=$this->input->post('tax_percentage');
			}else{
				$tax_percentage=0;
			}*/
			$data = array(
				'adjustment_type' => $this->input->post('adjustment_type'),
				'adjustment_name' => $this->input->post('adjustment_name'),
				'adjustment_amount' => $this->input->post('adjustment_amount'),
				'adjustment_for_employee' => $this->input->post('adjustment_for_employee'),
				'adjustment_perpared_by' => $this->input->post('_user'),
				'salary_type' => $this->input->post('add_type'),
				'end_date' => $end_date,
				//'tax_percentage' => $tax_percentage,
				'comments' => $this->input->post('comments'),
				'status' => '1',
				'compute_amount' => $this->input->post('compute_amount'),
				'created_by' => $this->input->post('_user'),
			);


			$result = $this->Payroll_model->add_adjustments($data);
			/*User Logs*/
			$affected_id= table_max_id('xin_salary_adjustments','adjustment_id');
			userlogs('Payroll-External Adjustment-Add','External Adjustment Added',$affected_id['field_id'],$affected_id['datas']);
			/*User Logs*/

			if ($result == TRUE) {
				$Return['result'] = 'External adjustment added.';

			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}

	}

	public function update_external_adjustments(){
		if($this->input->post('edit_type')=='external_adjustments') {
			$adjustment_id=$this->uri->segment(3);
			$check_unique_adjustments=$this->Payroll_model->check_unique_adjustments($this->input->post('adjustment_type'),$this->input->post('adjustment_name'),$this->input->post('adjustment_for_employee'),$adjustment_id);
			$Return = array('result'=>'', 'error'=>'');
			/* Server side PHP input validation */
			if($this->input->post('adjustment_type')==='') {
				$Return['error'] = "The adjustment type field is required.";
			} else if($this->input->post('adjustment_name')==='') {
				$Return['error'] = "The adjustment name field is required.";
			} else if($this->input->post('adjustment_amount')==='') {
				$Return['error'] = "The adjustment amount field is required.";
			} else if($this->input->post('adjustment_for_employee')==='') {
				$Return['error'] = "The adjustment for employee field is required.";
			} else if($check_unique_adjustments!=0){
				$Return['error'] = "This adjustment already added to this employee.";
			}

			if($Return['error']!=''){
				$this->output($Return);
			}

			if($this->input->post('end_date')!=''){
				$end_date=format_date('Y-m-d',$this->input->post('end_date'));
			}else{
				$end_date='';
			}

			/*if($this->input->post('tax_percentage')!=''){
				$tax_percentage=$this->input->post('tax_percentage');
			}else{
				$tax_percentage=0;
			}*/
			$data = array(
				'adjustment_type' => $this->input->post('adjustment_type'),
				'adjustment_name' => $this->input->post('adjustment_name'),
				'adjustment_amount' => $this->input->post('adjustment_amount'),
				'adjustment_for_employee' => $this->input->post('adjustment_for_employee'),
				'adjustment_perpared_by' => $this->input->post('_user'),
				'status' => $this->input->post('status'),
				//'tax_percentage' => $tax_percentage,
				'compute_amount' => $this->input->post('compute_amount'),
				'end_date' => $end_date,
				'comments' => $this->input->post('comments'),
				'created_by' => $this->input->post('_user'),
			);


			$result = $this->Payroll_model->update_adjustments($data,$adjustment_id);
			/*User Logs*/
			$affected_id= table_update_id('xin_salary_adjustments','adjustment_id',$adjustment_id);
			userlogs('Payroll-External Adjustment-Update','External Adjustment Updated',$adjustment_id,$affected_id['datas']);
			/*User Logs*/
			if ($result == TRUE) {
				$Return['result'] = 'External adjustment Updated.';

			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}

	}

	/*
    public function update_internal_adjustments(){

        if($this->input->post('edit_type')=='internal_adjustments') {

        $adjustment_id=$this->uri->segment(3);
        $Return = array('result'=>'', 'error'=>'');

        if($this->input->post('adjustment_type')==='') {
            $Return['error'] = "The adjustment type field is required.";
        } else if($this->input->post('adjustment_name')==='') {
            $Return['error'] = "The adjustment name field is required.";
        } else if($this->input->post('adjustment_amount')==='') {
            $Return['error'] = "The adjustment amount field is required.";
        } else if($this->input->post('adjustment_for_employee')==='') {
            $Return['error'] = "The adjustment for employee field is required.";
        } else if($this->input->post('adjustment_perpared_by')==='') {
            $Return['error'] = "The adjustment prepared by field is required.";
        }  else if($this->input->post('end_date')==='') {
            $Return['error'] = "The date of entry field is required.";
        }


        if($Return['error']!=''){
               $this->output($Return);
        }



        $data = array(
        'adjustment_type' => $this->input->post('adjustment_type'),
        'adjustment_name' => $this->input->post('adjustment_name'),
        'adjustment_amount' => $this->input->post('adjustment_amount'),
        'adjustment_for_employee' => $this->input->post('adjustment_for_employee'),
        'adjustment_perpared_by' => $this->input->post('adjustment_perpared_by'),
        'end_date' => format_date('Y-m-d',$this->input->post('end_date')),
        'comments' => $this->input->post('comments'),
        'created_by' => $this->input->post('_user'),
        );


        $result = $this->Payroll_model->update_adjustments($data,$adjustment_id);
        if ($result == TRUE) {
            $Return['result'] = 'Internal adjustment Updated.';

        } else {
            $Return['error'] = 'Bug. Something went wrong, please try again.';
        }
        $this->output($Return);
        exit;
        }

    }
    */

	public function get_amounts_byhour(){
		if($this->input->post('type')=='getamountbyhours') {
			$employee_id=$this->input->post('employee');
			$hours=decimalHours($this->input->post('hours'));
			$adjustment_name=$this->input->post('adjustment_name');
			$query = $this->db->query("SELECT `effective_from_date`, `effective_to_date`, `basic_salary`, `house_rent_allowance`, `gross_salary`, `bonus`, `salary_template_id`,`salary_based_on_contract` FROM `xin_salary_templates` WHERE `employee_id` = '".$employee_id."' AND effective_to_date='' AND is_approved=1 limit 1");

			$check_end_effective_date = $query->result();
			$user_info = $this->Xin_model->read_user_info($employee_id);
			$d_hours=explode(':',$user_info[0]->working_hours);
			if(@$d_hours[1]){ $d_mins=@$d_hours[1];} else {$d_mins='0';}
			$f_total_hours = new DateTime($d_hours[0].':'.$d_mins);
			$f_lunch_hours = new DateTime(LUNCH_HOURS);
			$interval_lunch = $f_total_hours->diff($f_lunch_hours);

			$interval_lunch = $f_total_hours;
			if($user_info[0]->is_break_included == 1)
				$interval_lunch = $f_total_hours->diff($f_lunch_hours);

			$total_work=$interval_lunch->format('%h').':'.$interval_lunch->format('%i');
			$shift_hours = decimalHours($total_work);
			$p_date=date('Y-m');
			// Find Total Hours
			$required_working_hours=required_working_hours($shift_hours,$p_date);
			if($check_end_effective_date){
				$from_date=$check_end_effective_date[0]->effective_from_date;
				$to_date=$check_end_effective_date[0]->effective_to_date;
				$basic_salary=$check_end_effective_date[0]->basic_salary;
				$accomodation=$check_end_effective_date[0]->house_rent_allowance;
				$gross_salary=$check_end_effective_date[0]->gross_salary;
				$bonus=$check_end_effective_date[0]->bonus;
				$salary_based_on_contract=$check_end_effective_date[0]->salary_based_on_contract;
				$rate_per_hour_contract_bonus=($gross_salary/$required_working_hours);
				$rate_per_hour_contract_only=(($gross_salary-$bonus)/$required_working_hours);
				$rate_per_hour_basic_only=($basic_salary/$required_working_hours);
				$rate_per_hour_accomodation_only=($accomodation/$required_working_hours);
				$ot_day_rate=($rate_per_hour_contract_only*1.25);
				$ot_night_rate=($rate_per_hour_contract_only*1.5);
				$ot_holiday_rate=($rate_per_hour_basic_only*1.5);
			}

			if($adjustment_name=='OT' || $adjustment_name=='OT Arrears' || $adjustment_name=='OT Overpaid'){
				$ot_amount=$this->Xin_model->currency_sign(round($hours*$ot_day_rate,2),'',$employee_id);
				echo json_encode(array('message'=>$ot_amount,'value'=>round($hours*$ot_day_rate,2)));
			}
			else if($adjustment_name=='Leave Reimbursement'){
				$total_salary_amount =(((($basic_salary)*12)/365)/$shift_hours)*$hours; //+$accomodation
				$leave_amount=$this->Xin_model->currency_sign(round($total_salary_amount,2),'',$employee_id);
				echo json_encode(array('message'=>$leave_amount,'value'=>round($total_salary_amount,2)));
			}
			else if($adjustment_name=='Salary Arrears' || $adjustment_name=='Salary Overpaid'){
				$total_salary_amount =$rate_per_hour_contract_bonus*$hours;
				$leave_amount=$this->Xin_model->currency_sign(round($total_salary_amount,2),'',$employee_id);
				echo json_encode(array('message'=>$leave_amount,'value'=>round($total_salary_amount,2)));
			}else if($adjustment_name=='End of service Benefit'){
				if(@$user_info[0]->date_of_joining!='' && @$user_info[0]->date_of_leaving){
					$date1=date_create($user_info[0]->date_of_joining);
					$s_end_date=date('Y-m-d',strtotime("-1 Day",strtotime($user_info[0]->date_of_leaving)));
					$date2=date_create($s_end_date);
					$diff=date_diff($date1,$date2);
					$val=($diff->days/365);
					if($val > 1){
						$calculation=round((((($basic_salary*12)/365)*21)*$val),2);
						echo json_encode(array('message'=>'','value'=>$calculation));
					}else{
						echo json_encode(array('message'=>"Employee's service should be more than 1 year.",'value'=>'0'));
					}
				}else{
					echo json_encode(array('message'=>'Check the Date of joining & Date of leaving Entered','value'=>''));
				}
			}
		}
	}

	public function add_internal_adjustments(){
		if($this->input->post('add_type')=='internal_adjustments') {
			$Return = array('result'=>'', 'error'=>'', 'alerts'=>'');
			/* Server side PHP input validation */
			if($this->input->post('adjustment_type')==='') {
				$Return['error'] = "The adjustment type field is required.";
			} else if($this->input->post('adjustment_name')==='') {
				$Return['error'] = "The adjustment name field is required.";
			} else if($this->input->post('adjustment_for_employee')==='') {
				$Return['error'] = "The adjustment for employee field is required.";
			} else if($this->input->post('end_date')==='') {
				$Return['error'] = "The date of entry field is required.";
			} else if($this->input->post('pay_by')=='amount') {
				if($this->input->post('adjustment_amount')==='') {
					$Return['error'] = "The adjustment amount field is required.";
				} } else if($this->input->post('pay_by')=='hours') {
				if($this->input->post('adjustment_hours')==='') {
					$Return['error'] = "The adjustment Hours field is required.";
				} }


			if($this->input->post('confirm_alert')==='') {
				$Return['alerts'] = 'ok';
				$this->output($Return);
			}


			if($Return['error']!=''){
				$this->output($Return);
			}

			if($this->input->post('adjustment_amount')!='') {
				$adjustment_amount=$this->input->post('adjustment_amount');
			}else{
				$adjustment_amount='';
			}

			if($this->input->post('adjustment_hours')!='') {
				$adjustment_hours=$this->input->post('adjustment_hours');
			}else{
				$adjustment_hours='';
			}


			$data = array(
				'adjustment_type' => $this->input->post('adjustment_type'),
				'adjustment_name' => $this->input->post('adjustment_name'),
				'adjustment_amount' => $adjustment_amount,
				'adjustment_hours' => $adjustment_hours,
				'adjustment_for_employee' => $this->input->post('adjustment_for_employee'),
				'adjustment_perpared_by' => $this->input->post('_user'),
				'salary_type' => $this->input->post('add_type'),
				'end_date' => format_date('Y-m-d',$this->input->post('end_date')),
				'comments' => $this->input->post('comments'),
				'status' => '0',
				'created_by' => $this->input->post('_user'),
			);
			$result = $this->Payroll_model->add_adjustments($data);
			/*User Logs*/
			$affected_id= table_max_id('xin_salary_adjustments','adjustment_id');
			userlogs('Payroll-Internal Adjustment-Add','Internal Adjustment Added',$affected_id['field_id'],$affected_id['datas']);
			/*User Logs*/
			if ($result == TRUE) {
				$Return['result'] = 'Internal adjustment added.';

			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}

	}

	public function adjustments_list(){
		$type=$this->uri->segment(3);
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("payroll/external_adjustments", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$department_id=$this->input->get('department_id');
		$location_id=$this->input->get('location_id');
		$adjustment_type=$this->input->get('adjustment_type');
		$adjustment_name=$this->input->get('adjustment_name');

		$visa_value=$this->input->get('visa_value');
		$month_year=format_date('Y-m',$this->input->get('month_year'));

		$adjustment_lists = $this->Payroll_model->get_adjustments_list($type,$department_id,$location_id,$month_year,$adjustment_type,$adjustment_name,$visa_value);

		$data = array();

		foreach($adjustment_lists->result() as $a_list) {

			$adjust_id='Adj'.$a_list->adjustment_id;
			// user full name
			$adjustment_type = $this->Payroll_model->read_salary_type($a_list->adjustment_type);
			$adjustment_name = $this->Payroll_model->read_salary_type($a_list->adjustment_name);
			$amount = $this->Xin_model->currency_sign($a_list->adjustment_amount,'',$a_list->adjustment_for_employee);
			$adjustment_for_employee = $this->Xin_model->read_user_info($a_list->adjustment_for_employee);
			$adjustment_perpared_by = $this->Xin_model->read_user_info($a_list->adjustment_perpared_by);
			$hours='';
			if($a_list->adjustment_hours!=''){
				$hours=$a_list->adjustment_hours." h";
			}
			if($a_list->end_date!=''){
				$end_date = $this->Xin_model->set_date_format($a_list->end_date);
			}else{
				$end_date='N/A';
			}
			$created_at = $a_list->updated_at;
			//$del_slug='';
			if($a_list->salary_type=='internal_adjustments'){
				if($a_list->status==1){$status='<span class="label label-success">Paid</span>';}else{

					$status='<span class="label label-danger">Not-Paid</span>';

					//$del_slug='<li><a class="delete" href="#" data-record-id="'. $a_list->adjustment_id .'"><i class="icon-trash"></i> Delete</a></li>';
				}

				$edit_perm='';
				if($hours!=''){
					$hours=' ('.$hours.') ';
				}else{
					$hours='';
				}

				$del_slug=$a_list->comments;
				$data[] = array(
					$adjust_id,
					$adjustment_type[0]->type_name,
					$adjustment_name[0]->type_name,
					$amount.$hours,
					$adjustment_for_employee[0]->first_name.' '.$adjustment_for_employee[0]->middle_name.' '.$adjustment_for_employee[0]->last_name,
					$adjustment_perpared_by[0]->first_name.' '.$adjustment_perpared_by[0]->middle_name.' '.$adjustment_perpared_by[0]->last_name,
					$end_date,
					$this->Xin_model->set_date_format($created_at).' '.format_date('H:i:s',$created_at),
					$del_slug
				);
			}else{
				if($a_list->status==1){$status='<span class="label label-success">Active</span>';}else{$status='<span class="label label-danger">In-Active</span>';}
				if(in_array('38e',role_resource_ids())) {
					$edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit-modal-data"  data-field_id="'. $a_list->adjustment_id . '"><i class="icon-pencil7"></i> Edit</a></li>';
				}
				$del_slug='<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.'</ul></li></ul>';
				//<li><a class="delete" href="#" data-record-id="'. $a_list->adjustment_id .'"><i class="icon-trash"></i> Delete</a></li>
				$data[] = array(
					$adjust_id,
					$adjustment_type[0]->type_name,
					$adjustment_name[0]->type_name,
					$amount,
					$adjustment_for_employee[0]->first_name.' '.$adjustment_for_employee[0]->middle_name.' '.$adjustment_for_employee[0]->last_name,
					$adjustment_perpared_by[0]->first_name.' '.$adjustment_perpared_by[0]->middle_name.' '.$adjustment_perpared_by[0]->last_name,
					$end_date,
					$status,
					$this->Xin_model->set_date_format($created_at).' '.format_date('H:i:s',$created_at),
					$del_slug
				);
			}
		}
		$output = array(
			"draw" => $draw,
			"recordsTotal" => $adjustment_lists->num_rows(),
			"recordsFiltered" => $adjustment_lists->num_rows(),
			"data" => $data
		);
		echo json_encode($output);
		exit();
	}

	// read and view all constants data > modal form
	public function adjustment_read()
	{
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view('payroll/dialog_adjustments', $data);
		} else {
			redirect('');
		}
	}

}
