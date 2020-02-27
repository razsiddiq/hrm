<?php
/**
 * @Author Siddiqkhan
 *
 * @EmployeeExit Controller
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_exit extends MY_Controller {

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
		$this->load->model("Employee_exit_model");
		$this->load->model("Employees_model");
		$this->load->model("Xin_model");
		$this->load->model("Designation_model");
		$this->load->model("Payroll_model");
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
		//$all_employees=  $this->Xin_model->all_employees_with_date_of_leaving();
	    $data['all_employees']=$this->Xin_model->all_employees();
		$data['all_exit_types'] = $this->Employee_exit_model->all_exit_types();
		$data['breadcrumbs'] = 'Exployee Exit';
		$data['path_url'] = 'employee_exit';
		if(in_array('27',role_resource_ids()) || in_array('27a',role_resource_ids()) || in_array('27e',role_resource_ids()) || in_array('27d',role_resource_ids()) || in_array('27v',role_resource_ids())) {
			if(!empty($this->userSession)){
			$data['subview'] = $this->load->view("exit/exit_list", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
     }
 
    public function exit_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("exit/exit_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$exit = $this->Employee_exit_model->get_exit();
		$data = array();

		foreach($exit->result() as $r) {

			// get user > employee to exit
			$user = $this->Xin_model->read_user_info($r->employee_id);
			// user full name
			$full_name = change_fletter_caps($user[0]->first_name.' '.$user[0]->middle_name.' '.$user[0]->last_name);
			// get user > added by
			$user_by = $this->Xin_model->read_user_info($r->added_by);
			// user full name
			$added_by = change_fletter_caps($user_by[0]->first_name.' '.$user_by[0]->middle_name.' '.$user_by[0]->last_name);

			// get exit date
			$exit_date = $this->Xin_model->set_date_format($r->exit_date);

			$cancellation_expense =	$this->Xin_model->currency_sign($r->cancellation_expense);
			// get exit type
			$exit_type = $this->Employee_exit_model->read_exit_type_information($r->exit_type_id);
			if($r->exit_interview==0): $exit_interview = '<span class="label label-danger">No</span>'; else: $exit_interview = '<span class="label label-success">Yes</span>'; endif;
			if($r->is_inactivate_account==0): $account = '<span class="label label-danger">No</span>'; else: $account = '<span class="label label-success">Yes</span>'; endif;


			 if($r->approval_form=='Final Settlement'){
				 $app_name="final_settlement";
				 $approval_form='<span class="label label-success">Final Settlement</span>';
			  }else{
				 $app_name="clearance_form";
				 $approval_form='<span class="label label-info">Clearance Form</span>';
			  }


			  $edit_perm='';
			  $view_perm='';
			  $delete_perm='';
			  $download_perm='';
			  if(in_array('27e',role_resource_ids())) {
				$edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit-modal-data"  data-exit_id="'. $r->exit_id . '" data-exit_type="'.$app_name.'"><i class="icon-pencil7"></i> Edit</a></li>';
			  }
			  if(in_array('27v',role_resource_ids())) {
				$view_perm='<li><a href="#" data-toggle="modal" data-target=".view-modal-data" data-exit_id="'. $r->exit_id . '" data-exit_type="'.$app_name.'"><i class="icon-eye4"></i> View</a></li>';
			  }
			  if(in_array('27d',role_resource_ids())) {
				$delete_perm='<li><a class="delete" href="#" data-record-id="'. $r->exit_id . '"><i class="icon-trash"></i> Delete</a></li>';
			  }

			  if(in_array('27e',role_resource_ids())) {
				 $download_perm='<li><a href="'.site_url().'employee_exit/pdf_create/'.$r->exit_id .'" class="text-success"><i class="icon-printer"></i> Generate PDF</a></li>';
			  }



			$data[] = array(
				$full_name,
				$exit_type[0]->type_name,
				$approval_form,
				$added_by,
				$this->Xin_model->set_date_format($r->created_at),
				'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$view_perm.$delete_perm.$download_perm.'</ul></li></ul>',
			);
        }

        $output = array(
			   "draw" => $draw,
				 "recordsTotal" => $exit->num_rows(),
				 "recordsFiltered" => $exit->num_rows(),
				 "data" => $data
		);
		$this->output($output);
     }

	public function pdf_create() {

		$this->load->library('Pdf');
   		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$id = $this->uri->segment(3);
		$query_exit=$this->db->query("select * from xin_employee_exit where exit_id='".$id."'");
		$result_exit=$query_exit->result();
		if($result_exit){
			 
			$final_settlement= (array) json_decode($result_exit[0]->final_settlement);
			$user = $this->Xin_model->read_user_info($result_exit[0]->employee_id);
			$location = $this->Xin_model->read_location_info($user[0]->office_location_id);
			$company = $this->Xin_model->read_company_info($location[0]->company_id);
			$fname = $user[0]->first_name.$user[0]->middle_name.$user[0]->last_name;
			//$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
			$company_name = $company[0]->name;
			$c_info_email = $company[0]->email;
			$c_info_phone = $company[0]->contact_number;
			$country = $this->Xin_model->read_country_info($company[0]->country);
			$c_info_address = $company[0]->address_1.' '.$company[0]->address_2.', '.$company[0]->city.' - '.$company[0]->zipcode.', '.$country[0]->country_name;
			$header_string ="Email : $c_info_email | Phone : $c_info_phone \nAddress: $c_info_address";
			//$pdf->SetProtection(array('print', 'copy','modify'), $user[0]->employee_id, "Siddiq@123", 0, null);
			$pdf->SetCreator('Awok');
			$pdf->SetHeaderData('', 40,  $company_name, $header_string);
			$pdf->setHeaderFont(Array('dejavusans', '', 11.5));
			$pdf->setFooterFont(Array('dejavusans', '', 8));
			$pdf->SetDefaultMonospacedFont('courier');
			$pdf->SetMargins(15, 27, 15);
			$pdf->SetHeaderMargin(5);
			$pdf->SetFooterMargin(10);
			$pdf->SetAutoPageBreak(TRUE, 25);
			$pdf->setImageScale(1.25);
			$pdf->SetAuthor('Mohamed Siddiqkhan');
			$pdf->SetTitle($company[0]->name.' - Print Final Settlement');
			$pdf->SetSubject('Payslip');
			$pdf->SetKeywords('Payslip');
			$pdf->SetFont('dejavusans', 'B', 11);
			$pdf->AddPage();		
			$pdf->SetFont('dejavusans', '', 10);
			
			
			if($result_exit[0]->approval_form=='Final Settlement'){
				$tbl = '
				<table cellpadding="1" cellspacing="1" border="0">
					<tr>
						<td align="center"><h1>Final Settlement</h1></td>
					</tr>
				
					<tr>
						<td align="center"><strong>Date:</strong> '.date("d F Y",strtotime($result_exit[0]->created_at)).'</td>
					</tr>
				</table>
				';
				$pdf->writeHTML($tbl, true, false, false, false, '');
				if($final_settlement['contract_date']!=''){
					$contract_date=date("d F Y", strtotime($final_settlement['contract_date']));
				}
				else{
					$contract_date='N/A';
				}
				$ot_hours_amount=json_decode($final_settlement['ot_hours_amount']);
				$ot_day_cal_amount=$ot_hours_amount->ot_day_amount;
				$ot_night_cal_amount=$ot_hours_amount->ot_night_amount;
				$ot_holiday_cal_amount=$ot_hours_amount->ot_holiday_amount;
				$tbl = '
				<table cellpadding="5" cellspacing="0" border="1">
				<tr>
					<td>Employee Name</td>
					<td>'.$final_settlement['employee_name'].'</td>
					<td>Visa</td>
					<td>'.$final_settlement['entity_agency'].'</td>
				</tr>
				<tr>
					<td colspan="1">Designation</td>
					<td colspan="3">'.$final_settlement['employee_designation'].'</td>
				
				</tr>
				<tr>
					<td>Date of Join</td>
					<td>'.date("d F Y", strtotime($final_settlement['date_of_joining'])).'</td>
					<td>Contract Date</td>
					<td>'.$contract_date.'</td>
				</tr>
				
				<tr>
					<td>Last Working Day</td>
					<td>'.date("d F Y", strtotime($final_settlement['last_working_day'])).'</td>
					<td>Type of Seperation</td>
					<td>'.$final_settlement['type_of_separtion'].'</td>
				</tr> 
				
				</table>';
				$pdf->writeHTML($tbl, true, false, true, false, '');

				// Allowances
				if($final_settlement['house_rent_allowance']!='' || $final_settlement['house_rent_allowance']!=0){
					$hra = $this->Xin_model->currency_sign(change_num_format($final_settlement['house_rent_allowance']),$user[0]->user_id);
				} else { $hra = $this->Xin_model->currency_sign(change_num_format(0),$user[0]->user_id);;}
				if($final_settlement['other_allowance']!='' || $final_settlement['other_allowance']!=0){
					$ma = $this->Xin_model->currency_sign(change_num_format($final_settlement['other_allowance']),$user[0]->user_id);
				} else { $ma = $this->Xin_model->currency_sign(change_num_format(0),$user[0]->user_id);}
				if($final_settlement['transportation']!='' || $final_settlement['transportation']!=0){
					$ta = $this->Xin_model->currency_sign(change_num_format($final_settlement['transportation']),$user[0]->user_id);
				} else { $ta = $this->Xin_model->currency_sign(change_num_format(0),$user[0]->user_id);}
				if($final_settlement['food_allowance']!='' || $final_settlement['food_allowance']!=0){
					$da = $this->Xin_model->currency_sign(change_num_format($final_settlement['food_allowance']),$user[0]->user_id);
				} else { $da = $this->Xin_model->currency_sign(change_num_format(0),$user[0]->user_id);}
				if($final_settlement['additional_benefits']!='' || $final_settlement['additional_benefits']!=0){
					$ab = $this->Xin_model->currency_sign(change_num_format($final_settlement['additional_benefits']),$user[0]->user_id);
				} else { $ab = $this->Xin_model->currency_sign(change_num_format(0),$user[0]->user_id);}
				if($final_settlement['bonus']!='' || $final_settlement['bonus']!=0){
					$bn = $this->Xin_model->currency_sign(change_num_format($final_settlement['bonus']),$user[0]->user_id);
				} else { $bn = $this->Xin_model->currency_sign(change_num_format(0),$user[0]->user_id);}


				$total_comp_salary=$final_settlement['total_salary'];
				if($final_settlement['ot_holiday_hours']!=''){
					$ot_holiday_hours=$final_settlement['ot_holiday_hours'];
				}else{
					$ot_holiday_hours=0;
				}

				$tbl = '<table cellpadding="4" cellspacing="0" border="0">
					<tr>
						<td><table cellpadding="5" cellspacing="0" border="1">
					<tr style="background-color:#9F9;">
						<td><strong>Monthly Salary</strong></td>
						<td align="right"><strong>Amount</strong></td>
					</tr>
					<tr>
						<td>Basic Salary</td>
						<td align="right">'.$this->Xin_model->currency_sign(change_num_format($final_settlement['basic_salary']),$user[0]->user_id).'</td>
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
					<tr style="background-color:#ff7575;">
						<td colspan="2"><strong>Years of Work & Leave Balance</strong></td>
						
					</tr>
					<tr>
						<td>Number of Days Worked</td>
						<td align="right">'.$final_settlement['no_of_days_worked'].'</td>
					</tr>
					<tr>
						<td>Number of Years Worked</td>
						<td align="right">'.$final_settlement['no_of_years_worked'].'</td>
					</tr>
					
					<tr>
						<td>No of Absence</td>
						<td align="right">'.$final_settlement['no_of_absence'].'</td>
					</tr>
					<tr>
						<td>Net Total Years Worked</td>
						<td align="right">'.$final_settlement['net_total_years_worked'].'</td>
					</tr>
					<tr>
						<td>Total Leave Accrued</td>
						<td align="right">'.$final_settlement['total_leave_accrued'].'</td>
					</tr>
					<tr>
						<td>Leave Availed</td>
						<td align="right">'.$final_settlement['leave_availed'].'</td>
					</tr>
					<tr>
						<td>Balance Leave</td>
						<td align="right">'.$final_settlement['balance_leave'].'</td>
					</tr>
					
					</table></td><td><table cellpadding="5" cellspacing="0" border="1">
					<tr style="background-color:#9F9;">
						<td colspan="2"><strong>Working Hours & Over Time</strong></td>
						
					</tr>
					<tr>
						<td>Normal Working Hours</td>
						<td align="right">'.$final_settlement['normal_working_hours'].'</td>
					</tr>
					<tr>
						<td>Working Hours for the month</td>
						<td align="right">'.$final_settlement['working_hours_for_the_month'].'</td>
					</tr>
					
					<tr>
						<td>Actual Working Hours</td>
						<td align="right">'.$final_settlement['actual_working_hour'].'</td>
					</tr>
					<tr>
						<td>OT Hours @ 1.25</td>
						<td align="right">'.$final_settlement['ot_day_hours'].'</td>
					</tr>
					<tr>
						<td>OT Hours @ 1.5</td>
						<td align="right">'.$final_settlement['ot_night_hours'].'</td>
					</tr>
					<tr>
						<td>OT Hours @ PH</td>
						<td align="right">'.$ot_holiday_hours.'</td>
					</tr>
			
					
					</table></td>
						</tr>
					</table>';
				$pdf->writeHTML($tbl, true, false, false, false, '');

				$add_salary='';
				$ded_salary='';

				$parent_count=count($final_settlement['parent_type_name']);
				if($parent_count!=0){
					$parent_t=$final_settlement['parent_type_name'];
					for($i=0;$i<$parent_count;$i++){
						if($parent_t[$i]=='Addition'){
							$add_salary.='<tr>
							<td>'.$final_settlement['child_type_name'][$i].'</td>
							<td>'.$final_settlement['salary_comments'][$i].'</td>
							<td>'.$this->Xin_model->currency_sign(change_num_format($final_settlement['amount'][$i]),$user[0]->user_id).'</td>
						</tr>';
						}
						else if($parent_t[$i]=='Deduction'){
							$ded_salary.='<tr>
							<td>'.$final_settlement['child_type_name'][$i].'</td>
							<td>'.$final_settlement['salary_comments'][$i].'</td>
							<td>'.$this->Xin_model->currency_sign(change_num_format($final_settlement['amount'][$i]),$user[0]->user_id).'</td>
						</tr>';

						}
					}
				}

				$tbl = '
					<table cellpadding="4" cellspacing="0" border="0">
					<tr>
						<td><table cellpadding="5" cellspacing="0" border="1">
					<tr style="background-color:#ff7575;">
						<td align="left"><strong>Elements</strong></td>
						<td align="middle"><strong>Days</strong></td>
						<td align="right"><strong>Amount</strong></td>
					</tr>
					<tr>
						<td colspan="3" align="left"><strong>Earnings</strong></td>
					
					</tr>
					<tr>
						<td align="left">Current Month Salary - '.$final_settlement['current_month_salary'].'</td>
						<td>'.$final_settlement['total_days_of_the_month'].'</td>
						<td>'.$this->Xin_model->currency_sign(change_num_format($final_settlement['salary_of_the_month']),$user[0]->user_id).'</td>
					</tr>
					<tr>
						<td align="left">Leave Salary</td>
						<td>'.$final_settlement['leave_salary_days'].'</td>
						<td>'.$this->Xin_model->currency_sign(change_num_format($final_settlement['leave_salary']),$user[0]->user_id).'</td>
					</tr>
					<tr>
						<td align="left">OT Amount @ 1.25</td>
						<td></td>
						<td>'.$this->Xin_model->currency_sign(change_num_format($ot_day_cal_amount),$user[0]->user_id).'</td>
					</tr>
					<tr>
						<td align="left">OT Amount @ 1.5</td>
						<td></td>
						<td>'.$this->Xin_model->currency_sign(change_num_format($ot_night_cal_amount),$user[0]->user_id).'</td>
					</tr>
					<tr>
						<td align="left">OT Amount @ PH</td>
						<td></td>
						<td>'.$this->Xin_model->currency_sign(change_num_format($ot_holiday_cal_amount),$user[0]->user_id).'</td>
					</tr>
					
					
					'.$add_salary.'
					<tr style="background-color:#9F9;">
						<td align="left">Total Earnings</td>
						<td></td>
						<td>'.$this->Xin_model->currency_sign(change_num_format($final_settlement['total_earnings']),$user[0]->user_id).'</td>
					</tr>
					<tr>
						<td colspan="3" align="left"><strong>Deductions</strong></td>
					
					</tr>
					'.$ded_salary.'
					<tr style="background-color:#ff7575;">
						<td align="left">Total Deduction</td>
						<td></td>
						<td>'.$this->Xin_model->currency_sign(change_num_format($final_settlement['total_deductions']),$user[0]->user_id).'</td>
					</tr>
					<tr style="background-color:#9F9;">
						<td align="left">Net Payable</td>
						<td></td>
						<td>'.$this->Xin_model->currency_sign(change_num_format($final_settlement['payment_amount_to_employee']),$user[0]->user_id).'</td>
					</tr>
					
					</table></td>
						
					</tr></table>';
				$pdf->writeHTML($tbl, true, false, false, false, '');
			}
			else
			{
				$tbl = '
					<table cellpadding="1" cellspacing="1" border="0">
					<tr>
						<td align="center"><h1>Clearance Form <br>(Employee/Contract)</h1></td>
					</tr>
				
					<tr>
						<td align="center"><strong>Date:</strong> '.date("d F Y",strtotime($result_exit[0]->created_at)).'</td>
					</tr>
					
					</table>';

				 $pdf->writeHTML($tbl, true, false, false, false, '');

				 if($final_settlement['cessation_of_employement']=='Resignation'){ $resignation='<span style="float: right;padding-right: 2em;">&#10003;</span>';}else{$resignation='';}
				 if($final_settlement['cessation_of_employement']=='Termination'){ $termination='<span style="float: right;padding-right: 2em;">&#10003;</span>';}else{$termination='';}
				 if($final_settlement['cessation_of_employement']=='Expiry of Contract'){ $expiryofcontact='<span style="float: right;padding-right: 2em;">&#10003;</span>';}else{$expiryofcontact='';}
				 if($final_settlement['cessation_of_employement']=='Other/s pls. specify'){ $other_sp='<span style="float: right;padding-right: 2em;">&#10003;       ['.$final_settlement['cessation_of_employement_other'].']</span>';}else{$other_sp='';}
				 $tbl = '
				<table cellpadding="5" cellspacing="0" border="1">
					<tr>
						<td><strong>Employee Name</strong></td>
						<td colspan="3">'.$final_settlement['employee_name'].'</td>
						<td><strong>Entity/Agency</strong></td>
						<td>'.$final_settlement['entity_agency'].'</td>
					</tr>
					<tr>
						<td><strong>Designation</strong></td>
						<td colspan="2">'.$final_settlement['employee_designation'].'</td>
						<td><strong>Department</strong></td>
						<td colspan="2">'.$final_settlement['employee_department'].'</td>
					</tr>
					<tr>
						<td><strong>Joining Date</strong></td>
						<td>'.date("d F Y",strtotime($final_settlement['joining_date'])).'</td>
						<td><strong>Resignation Date</strong></td>
						<td>'.date("d F Y",strtotime($final_settlement['resignation_date'])).'</td>
						<td><strong>Last Working Day</strong></td>
						<td>'.date("d F Y",strtotime($final_settlement['last_working_day'])).'</td>
					</tr>
					<tr>
						<td><strong>Nature of Cessation of Employement</strong><br>**pls.check applicable box**</td>
						<td colspan="2">Resignation  '.$resignation.'<br><hr>Expiry of Contract '.$expiryofcontact.'</td>
						<td colspan="3">Termination  '.$termination.'<br><hr>Other/s pls. specify '.$other_sp.'</td>
					</tr>
					<tr>
						<td colspan="6">Persons in charge are required to sign in the respective sections and/or department below:</td>
					</tr>
				</table>
				';
				$pdf->writeHTML($tbl, true, false, true, false, '');

				//print_r($tbl);

				if($final_settlement['department_date']!=''){
				$department_date=date("d F Y",strtotime($final_settlement['department_date']));
				}else{$department_date='';}

				if($final_settlement['it_date']!=''){
				$it_date=date("d F Y",strtotime($final_settlement['it_date']));
				}else{$it_date='';}
				if($final_settlement['hr_date']!=''){
				$hr_date=date("d F Y",strtotime($final_settlement['hr_date']));
				}else{$hr_date='';}
				if($final_settlement['account_date']!=''){
				$account_date=date("d F Y",strtotime($final_settlement['account_date']));
				}else{$account_date='';}

				$tbl = '<table cellpadding="4" cellspacing="0" border="0">
				<tr>
					<td><table cellpadding="5" cellspacing="0" border="1">
				<tr style="background-color:#9F9;">
					<td  colspan="6"><strong>1. OWN DEPARTMENT\'S CLEARANCE</strong></td>
					
				</tr>
				
				<tr>
					<td colspan="4">Bitrix Account</td>
					<td colspan="2">'.$final_settlement['department_bitrix_account'].'</td>
				</tr>
				<tr>
								<td colspan="4">Awok Logistics System (ALS)</td>
								<td colspan="2">'.$final_settlement['department_awok_logistics_system'].'</td>
							</tr>
				<tr>
								<td colspan="4">RTA Fine</td>
								<td colspan="2">'.$final_settlement['department_rta_fine'].'</td>
							</tr>
				<tr>
								<td colspan="4">Vehicle Damage / Fines</td>
								<td colspan="2">'.$final_settlement['department_vehicle_damage_fines'].'</td>
							</tr>
				<tr>
								<td colspan="4">Etisalat Bills Outstanding</td>
								<td colspan="2">'.$final_settlement['department_etisalat_bills_outstanding'].'</td>
							</tr>
				
				<tr style=" height:12em; "><td colspan="6"></td></tr>
				
				
				<tr>
								<td colspan="6">Remarks : '.$final_settlement['department_remarks'].'</td>
								
							</tr>
							
						<tr>
								<td colspan="6">Name : '.$final_settlement['department_name'].'</td>
								
							</tr>
				<tr>
								<td colspan="2">Signature</td>
								<td><img src="'.$final_settlement['department_signature'].'"/></td>
				<td colspan="2">Date</td>
								<td>'.$department_date.'</td>
							</tr>
							
						</table></td>
								<td><table cellpadding="5" cellspacing="0" border="1">
							<tr style="background-color:#9F9;">
								<td  colspan="6"><strong>2. IT CLEARANCE</strong></td>
								
							</tr>
					
							<tr>
								<td colspan="4">Bitrix</td>
								<td colspan="2">'.$final_settlement['it_bitrix'].'</td>
							</tr>
				<tr>
								<td colspan="4">Email</td>
								<td colspan="2">'.$final_settlement['it_email'].'</td>
							</tr>
				<tr>
								<td colspan="4">Skype (if official)</td>
								<td colspan="2">'.$final_settlement['it_skype'].'</td>
							</tr>
				<tr>
								<td colspan="4">1C (if applicable)</td>
								<td colspan="2">'.$final_settlement['it_ic'].'</td>
							</tr>
				<tr>
								<td colspan="4">Awok.com Access</td>
								<td colspan="2">'.$final_settlement['it_awok_com_access'].'</td>
							</tr>
				<tr>
								<td colspan="4">Other\s (pls specify)</td>
								<td colspan="2">'.$final_settlement['it_other_specify'].'</td>
							</tr>
				<tr>
								<td colspan="4">Desktop</td>
								<td colspan="2">'.$final_settlement['it_desktop'].'</td>
							</tr>
				<tr>
								<td colspan="4">Laptop</td>
								<td colspan="2">'.$final_settlement['it_laptop'].'</td>
							</tr>
				<tr>
								<td colspan="4">Mouse</td>
								<td colspan="2">'.$final_settlement['it_mouse'].'</td>
							</tr>
				<tr>
								<td colspan="4">Headset</td>
								<td colspan="2">'.$final_settlement['it_headset'].'</td>
							</tr>
				<tr>
								<td colspan="4">Keyboard</td>
								<td colspan="2">'.$final_settlement['it_keyboard'].'</td>
							</tr>
				<tr>
								<td colspan="6">Remarks : '.$final_settlement['it_remarks'].'</td>
								
							</tr>
							
						<tr>
								<td colspan="6">Name : '.$final_settlement['it_name'].'</td>
								
							</tr>
				
				<tr>
								<td colspan="2">Signature</td>
								<td><img  src="'.$final_settlement['it_signature'].'"/></td>
				<td colspan="2">Date</td>
								<td>'.$it_date.'</td>
							</tr>
							
						</table></td>
							</tr>
						</table>		
							';
				$pdf->writeHTML($tbl, true, false, true, false, '');


				$tbl ='<table cellpadding="4" cellspacing="0" border="0">
				<tr>
					<td><table cellpadding="5" cellspacing="0" border="1">
				<tr style="background-color:#9F9;">
					<td  colspan="6"><strong>3. HR CLEARANCE</strong></td>
					
				</tr>
				
				<tr>
					<td colspan="4">Labour Card</td>
					<td colspan="2">'.$final_settlement['hr_labour_card'].'</td>
				</tr>
				<tr>
								<td colspan="4">Emirates Card</td>
								<td colspan="2">'.$final_settlement['hr_emirates_card'].'</td>
							</tr>
				<tr>
								<td colspan="4">Medical Card</td>
								<td colspan="2">'.$final_settlement['hr_medical_card'].'</td>
							</tr>
				
				<tr>				<td colspan="4">Exit Interview</td>
								<td colspan="2">'.$final_settlement['hr_exit_interview'].'</td>
							</tr>
				<tr>
								<td colspan="6">Remarks : '.$final_settlement['hr_remarks'].'</td>
								
							</tr>
							
						<tr>
								<td colspan="6">Name : '.$final_settlement['hr_name'].'</td>
								
							</tr>
				
				
				<tr>
								<td colspan="2">Signature</td>
								<td><img src="'.$final_settlement['hr_signature'].'"/></td>
				<td colspan="2">Date</td>
								<td>'.$hr_date.'</td>
							</tr>
							
						</table></td>
				<td><table cellpadding="5" cellspacing="0" border="1">
				<tr style="background-color:#9F9;">
					<td  colspan="6"><strong>4. ACCOUNT\'s CLEARANCE</strong></td>
					
				</tr>
		
				<tr>
					<td colspan="4">1.Claims Settlement (1511)</td>
					<td colspan="2">'.$final_settlement['account_claims_settlement_1511'].'</td>
				</tr>
				<tr>
								<td colspan="4">2.Advances to Employee for Company Purpose (3630)<br>3630.01(AED)/3630.02(Other Currency)</td>
								<td colspan="2">'.$final_settlement['account_advance_to_employee_for_company_purpose'].'</td>
							</tr>
				<tr>
								<td colspan="4">3.Settlement with Personnel On Payment (3520)</td>
								<td colspan="2">'.$final_settlement['account_settlement_with_personnel_on_payment_3520'].'</td>
							</tr>
				<tr>
								<td colspan="4">4.Settlement with Personnel Outsources (3523)</td>
								<td colspan="2">'.$final_settlement['account_settlement_with_personnel_outsources_3523'].'</td>
							</tr>
				<tr>
								<td colspan="4">Other/s (if any)</td>
								<td colspan="2">'.$final_settlement['account_other_specify'].'</td>
							</tr>
				<tr>
								<td colspan="4">Total Amount Payable</td>
								<td colspan="2">'.$final_settlement['account_total_amount_payable'].'</td>
							</tr>
				<tr>
								<td colspan="2">Prepared By</td>
								<td>'.$final_settlement['account_prepardby'].'</td>
				<td colspan="2">Checked By</td>
								<td>'.$final_settlement['account_checkedby'].'</td>
							</tr>
				<tr>
								<td colspan="6">Remarks : '.$final_settlement['account_remarks'].'</td>
								
							</tr>
							
						<tr>
								<td colspan="6">Name : '.$final_settlement['account_name'].'</td>
								
							</tr>
				
				<tr>
								<td colspan="2">Signature</td>
								<td><img src="'.$final_settlement['account_signature'].'"/></td>
				<td colspan="2">Date</td>
								<td>'.$account_date.'</td>
				</tr>
				
				</table></td>
					</tr>
				</table>		
					';
				$pdf->writeHTML($tbl, true, false, false, false, '');
		
		 	}

			ob_end_clean();
			$fname = strtolower($fname);
			$strtotime =strtotime(date('Y-m-d H:i:s'));			
			$app_name=str_replace(' ','_',$result_exit[0]->approval_form);
			//Close and output PDF document
			 $pdf->Output($app_name.$fname.'_'.$strtotime.'.pdf', 'D');
		}
	 }

	public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('exit_id');
		$result = $this->Employee_exit_model->read_exit_information($id);
		$data = array(
				'exit_id' => $result[0]->exit_id,
				'employee_id' => $result[0]->employee_id,
				'exit_date' => format_date('d F Y',$result[0]->exit_date),
				'exit_type_id' => $result[0]->exit_type_id,
				'exit_interview' => $result[0]->exit_interview,
				'is_inactivate_account' => $result[0]->is_inactivate_account,
				'cancellation_expense' => $result[0]->cancellation_expense,
				'created_at' => $result[0]->created_at,
				//'final_settlement'=>json_decode($result[0]->final_settlement),
				'reason' => $result[0]->reason,
				'all_employees' => $this->Xin_model->all_employees_with_date_of_leaving(),
				'all_exit_types' => $this->Employee_exit_model->all_exit_types(),
				);
		if(!empty($this->userSession)){
			$this->load->view('exit/dialog_exit', $data);
		} else {
			redirect('');
		}
	}

	public function add_exit() {    		
		if($this->input->post('add_type')=='exit') {
		$Return = array('result'=>'', 'error'=>'', 'message'=>'');
		if($this->input->post('approval_form')=='Final Settlement'){	
			$final_settlement=array('employee_code'=>$this->input->post('employee_code'),'employee_name'=>$this->input->post('employee_name'),'employee_designation'=>$this->input->post('employee_designation'),'entity_agency'=>$this->input->post('entity_agency'),'date_of_joining'=>$this->input->post('date_of_joining'),'contract_date'=>$this->input->post('contract_date'),'last_working_day'=>$this->input->post('last_working_day'),'type_of_separtion'=>$this->input->post('type_of_separtion'),'no_of_days_worked'=>$this->input->post('no_of_days_worked'),'no_of_years_worked'=>$this->input->post('no_of_years_worked'),'no_of_absence'=>$this->input->post('no_of_absence'),'net_total_years_worked'=>$this->input->post('net_total_years_worked'),'total_leave_accrued'=>$this->input->post('total_leave_accrued'),'leave_availed'=>$this->input->post('leave_availed'),'balance_leave'=>$this->input->post('balance_leave'),'basic_salary'=>$this->input->post('basic_salary'),'house_rent_allowance'=>$this->input->post('house_rent_allowance'),'transportation'=>$this->input->post('transportation'),'food_allowance'=>$this->input->post('food_allowance'),'additional_benefits' => $this->input->post("additional_benefits"),
			'bonus' => $this->input->post("bonus"),'total_salary'=>$this->input->post('total_salary'),'normal_working_hours'=>$this->input->post('normal_working_hours'),'bonus'=>$this->input->post('bonus'),'working_hours_for_the_month'=>$this->input->post('working_hours_for_the_month'),'actual_working_hour'=>$this->input->post('actual_working_hour'),'ot_day_hours'=>$this->input->post('ot_day_hours'),'ot_night_hours'=>$this->input->post('ot_night_hours'),'ot_holiday_hours'=>$this->input->post('ot_holiday_hours'),'current_month_salary'=>$this->input->post('current_month_salary'),'total_days_of_the_month'=>$this->input->post('total_days_of_the_month'),'salary_of_the_month'=>$this->input->post('salary_of_the_month'),'leave_salary'=>$this->input->post('leave_salary'),'leave_salary_days'=>$this->input->post('leave_salary_days'),'ot_day_rate'=>$this->input->post('ot_day_rate'),'ot_night_rate'=>$this->input->post('ot_night_rate'),'ot_holiday_rate'=>$this->input->post('ot_holiday_rate'),'total_earnings'=>$this->input->post('total_earnings'),'total_deductions'=>$this->input->post('total_deductions'),'net_payable'=>$this->input->post('net_payable'),'parent_type_name'=>$this->input->post('parent_type_name'),'child_type_name'=>$this->input->post('child_type_name'),'adjustment_id'=>$this->input->post('adjustment_id'),'parent_type'=>$this->input->post('parent_type'),'child_type'=>$this->input->post('child_type'),'amount'=>$this->input->post('amount'),'salary_comments'=>$this->input->post('salary_comments'),'ot_hours_amount'=>$this->input->post('ot_hours_amount'),'driver_delivery_details'=>$this->input->post('driver_delivery_details'),'payment_amount_to_employee'=>$this->input->post('payment_amount_to_employee'));
		}
		else{
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
			'department_signature'=>$this->input->post('department_signature'),
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
			'it_signature'=>$this->input->post('it_signature'),
			'it_date'=>$this->input->post('it_date'),			
			'hr_labour_card'=>$this->input->post('hr_labour_card'),
			'hr_emirates_card'=>$this->input->post('hr_emirates_card'),
			'hr_medical_card'=>$this->input->post('hr_medical_card'),
			'hr_exit_interview'=>$this->input->post('hr_exit_interview'),
			'hr_remarks'=>$this->input->post('hr_remarks'),
			'hr_name'=>$this->input->post('hr_name'),
			'hr_signature'=>$this->input->post('hr_signature'),
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
			'account_signature'=>$this->input->post('account_signature'),
			'account_date'=>$this->input->post('account_date'),
			);
		}
		$reason = $this->input->post('reason');
		$qt_reason = htmlspecialchars(addslashes($reason), ENT_QUOTES);
		
		if($this->input->post('employee_id')==='') {
       		 $Return['error'] = "The employee field is required.";
		} else if($this->input->post('exit_type')==='') {
			 $Return['error'] = "The exit type field is required.";
		} else if($this->input->post('approval_form')==='') {
			 $Return['error'] = "The type of form field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'employee_id' => $this->input->post('employee_id'),
		'reason' => $qt_reason,
		'exit_type_id' => $this->input->post('exit_type'),
		'approval_form' => $this->input->post('approval_form'),
		'final_settlement'=> json_encode($final_settlement),
		'added_by' => $this->input->post('user_id'),
		'created_at' => date('d-m-Y'),
		);
		
		$emp_last_data=array('date_of_leaving'=>$this->input->post('last_working_day'),'is_active'=>0);
		$this->Employees_model->update_record($emp_last_data,$this->input->post('employee_id'));
		 
		$check_employee_exit_there=$this->Employees_model->check_if_eos($this->input->post('employee_id'),$this->input->post('approval_form'));
		if($check_employee_exit_there==0){
			$result = $this->Employee_exit_model->add($data);
		}else{
			$Return['error'] = 'Employee Exit already added.';
			$this->output($Return);
		}		
		/*User Logs*/
		$affected_id= table_max_id('xin_employee_exit','exit_id');
		userlogs('Employees-Employee Exit-Add','Employee Exit Added',$affected_id['field_id'],$affected_id['datas']);
		/*User Logs*/
	
	    foreach($final_settlement as $key=>$value){
			if(is_array($value)){
				$value=json_encode($value);
			}else{
				$value=$value;
			}
			$field_data=array('employee_id'=>$this->input->post('employee_id'),'table_name'=>'xin_employee_exit','field_name'=>$key,'field_value'=>$value,'field_id_from_table'=>$affected_id['field_id']);
			$this->Employee_exit_model->add_fields($field_data);
		}
	
		$leave_salary_arr=json_decode($this->input->post('leave_salary_arr'));
	    $leave_salary_paid_arr=$this->input->post('leave_salary_paid_arr');
		$final_leave_salary_arr=array(array('Final-Leave-settlement'=>array('days'=>$this->input->post('leave_salary_days'),'amount'=>$this->input->post('leave_salary'),'total_rest'=>$this->input->post('leave_salary_days')*$this->input->post('normal_working_hours'))));
			
		$leave_salary_arr=json_encode(array_merge($leave_salary_arr,$final_leave_salary_arr));
		$employee_id=$this->input->post("employee_id");
		$field_id=$affected_id['field_id'];
		$created_by=$this->input->post("user_id");
		$user = $this->Xin_model->read_user_info($employee_id);
		$department_id=$user[0]->department_id;
		$office_location_id=$user[0]->office_location_id;
		$designation_id=$user[0]->designation_id;		
		$location = $this->Xin_model->read_location_info($user[0]->office_location_id);
	
		if($this->input->post('approval_form')=='Final Settlement'){
			$payment_amount_with_tax=$this->input->post("net_payable");
			$visa_id=$this->input->post('visa_id');
			$tax_type=get_tax_info($visa_id);
			$total_tax_amount=0;
			$tax_value=[];
			if($tax_type){
				foreach($tax_type as $tax_t){
					$tax_amount=($this->input->post("net_payable")*($tax_t->type_symbol/100));
					$total_tax_amount+=$tax_amount;
					$tax_value[]=array('tax_name'=>$tax_t->type_name,'tax_percentage'=>$tax_t->type_symbol,'tax_amount'=>$tax_amount);
				}
				$payment_amount_with_tax=$this->input->post("net_payable")+$total_tax_amount;
			}
			/*Tax*/
			$driver_delivery_details=json_decode($this->input->post('driver_delivery_details'));
			$data1 = array(
			'employee_id' => $employee_id,
			'department_id' => $department_id,
			'company_id' => $location[0]->company_id,
			'location_id' => $office_location_id,
			'designation_id' => $designation_id,
			'basic_salary' => $this->input->post("basic_salary"),
			'house_rent_allowance' => $this->input->post("house_rent_allowance"),
			'other_allowance' => $this->input->post("other_allowance"),
			'travelling_allowance' => $this->input->post("transportation"),
			'food_allowance' => $this->input->post("food_allowance"),
			'additional_benefits' => $this->input->post("additional_benefits"),
			'bonus' => $this->input->post("bonus"),
			'required_working_hours' => $this->input->post("working_hours_for_the_month"),
			'total_working_hours' => $this->input->post("actual_working_hour"),
			'gross_salary' => $this->input->post("total_salary"),
			'net_salary' => $this->input->post("total_salary"),
			'total_salary' => $this->input->post("salary_of_the_month"),
			'payment_date' => $this->input->post("payment_date"),
			'late_working_hours' =>  $this->input->post("late_working_hours"),
			'payment_amount' => $this->input->post("net_payable"),
			'payment_amount_to_employee' => $this->input->post("payment_amount_to_employee"),
			'salary_template_id' => $this->input->post("salary_template_id"),
			'rate_per_hour_contract_bonus' => $this->input->post("rate_per_hour_contract_bonus"),
			'rate_per_hour_contract_only' => $this->input->post("rate_per_hour_contract_only"),
			'rate_per_hour_basic_only' => $this->input->post("rate_per_hour_basic_only"),
			'ot_day_rate' => $this->input->post("ot_day_rate"),
			'ot_night_rate' => $this->input->post("ot_night_rate"),
			'ot_holiday_rate' => $this->input->post("ot_holiday_rate"),
			'leave_salary_paid' => $leave_salary_paid_arr,
			'leave_salary_amount' =>  $leave_salary_arr,
			'ot_hours_amount' => $this->input->post("ot_hours_amount"),
			'actual_days_worked' => $this->input->post("total_days_of_the_month"),
			'is_payment' => '1',
			'payment_method' => 150,
			'comments' => 'Final Settlement-'.$affected_id['field_id'],
			'status' => '2',
			'created_at' => date('d-m-Y h:i:s'),
			'currency' => $this->Xin_model->currency_sign('',$location_id='',$employee_id),
			'leave_settlement_start_date'=>'',
			'leave_settlement_end_date'=>'',
			'leave_start_date'=>'',
			'leave_end_date'=>'',
			'tax_amount' => json_encode($tax_value),
			'payment_amount_with_tax' => $payment_amount_with_tax,
			'driver_delivery_details'=>json_encode($driver_delivery_details)
			);


			$adjustment_id=$this->input->post("adjustment_id");
			$parent_type=$this->input->post("parent_type");
			$child_type=$this->input->post("child_type");
			$amount=$this->input->post("amount");
			$comments=$this->input->post("salary_comments");

			$month_year=$this->input->post("payment_date");
			$s_start_date='';
			$s_end_date='';
			$remove_payment_hold=$this->Payroll_model->remove_payment_leave_hold($employee_id,$month_year,$s_start_date,$s_end_date);

			$this->db->where('employee_id', $employee_id);
			$this->db->where('type_of_approval', 'Final Settlement');
			//$this->db->where('pay_date', $month_year);
			$this->db->delete('xin_employees_approval');

			if($adjustment_id){
				foreach($adjustment_id as $adjus){
					$this->Payroll_model->update_salary_adjustments_status($adjus,1);
				}
			}

			$resultid = $this->Payroll_model->add_monthly_payment_payslip($data1);
			if($parent_type){
				$count_type=count($parent_type);
				$payment_return_id=$resultid;
				$created_at=date('d-m-Y h:i:s');
				$parent_type=$parent_type;
				$child_type=$child_type;
				$amount=$amount;
				$comments=$comments;
				for($i=0;$i<$count_type;$i++){
					$data_options=array('make_payment_id'=>$payment_return_id,'parent_type'=>$parent_type[$i],'child_type'=>$child_type[$i],'amount'=>$amount[$i],'comments'=>$comments[$i],'created_at'=>$created_at,'payment_date'=>$month_year);
					$result1 = $this->Payroll_model->add_payment_options($data_options);
				}
			}
		}

		if ($result == TRUE) {
			 $type_of_approval=$this->input->post('approval_form');
			 //*Mail Trigger*//
			 if($this->input->post('approval_form')=='Final Settlement'){
				 $type_of_exit=$this->input->post('type_of_separtion');
				 $head_id='';
				 $created_at = date('Y-m-d');
				 $all_department_heads=$this->Employees_model->all_department_heads_with_it($department_id,'',$head_id,$type_of_exit,1);
				 $count_of_dept=count($all_department_heads);
				 $setting = $this->Xin_model->read_setting_info(1);
				 $this->email->set_mailtype("html");

				 if($all_department_heads){
				 $insert_approvals=$this->Employees_model->insert_approvals($employee_id,$field_id,$created_by,$all_department_heads,$type_of_approval);

				 $designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
				 $emp_full_name = change_fletter_caps($user[0]->first_name.' '.$user[0]->last_name);

				 $html_structure='';
				 $currency=$this->Xin_model->currency_sign($number=null,$location_id='',$employee_id);


				$cinfo = $this->Xin_model->read_company_setting_info(1);

					foreach($all_department_heads as $head_of_dep){

					 $head_info = $this->Xin_model->read_user_info($head_of_dep->head_id);
					 $approve_link=$_SERVER['HTTP_HOST'].base_url().'index/approval/'.base64_encode($head_of_dep->head_id.'/'.$employee_id.'/1/'.$type_of_approval.'/'.$created_by.'/'.$field_id.'/'.$resultid);
					 $decline_link=$_SERVER['HTTP_HOST'].base_url().'index/approval/'.base64_encode($head_of_dep->head_id.'/'.$employee_id.'/2/'.$type_of_approval.'/'.$created_by.'/'.$field_id.'/'.$resultid);

					 //if($setting[0]->enable_email_notification == 'yes') {

						//get email template
						$template = $this->Xin_model->read_email_template_info_bycode('Final Settlement');
						$html_structure='';

						if($final_settlement['contract_date']!=''){
						$contract_d=$final_settlement['contract_date'];
						}else{
						$contract_d='N/A';
						}

						$html_structure.='<div class="panel panel-flat"><div class="panel-heading text-center"><h5 class="panel-title">					 
						<strong>Final Settlement Computation</strong></h5><div class="heading-elements">Date: '.date('d F Y',strtotime($created_at)).'</div>	</div><br>
						<div class="panel-body">
						<div class="col-lg-6 table-responsive"><table class="table table-md"><tbody>
						<tr class="bg-slate-600 text-center"><td colspan="3">Basic Details</td></tr>
						<tr class="success"><td>Employee Code</td><td>:</td><td>'.$final_settlement['employee_code'].'</td></tr>	
						<tr class=""><td>Employee Name</td><td>:</td><td>'.$final_settlement['employee_name'].'</td></tr>	
						<tr class="danger"><td>Designation</td><td>:</td><td>'.$final_settlement['employee_designation'].'</td></tr>
						<tr class=""><td>Entity/Agency</td><td>:</td><td>'.$final_settlement['entity_agency'].'</td></tr>
						<tr class="warning"><td>Date of Join</td><td>:</td><td>'.date('d F Y',strtotime($final_settlement['date_of_joining'])).'</td></tr>
						<tr class=""><td>Contract Date</td><td>:</td><td>'.$contract_d.'</td></tr>
						<tr class="info"><td>Last Working Day</td><td>:</td><td>'.date('d F Y',strtotime($final_settlement['last_working_day'])).'</td></tr>
						<tr class=""><td>Type of Seperation</td><td>:</td><td>'.$final_settlement['type_of_separtion'].'</td></tr>		
						</tbody></table></div><br>
						<div class="col-lg-6 table-responsive"><table class="table table-md"><tbody>
						<tr class="bg-slate-600 text-center"><td colspan="3">Years of Work & Leave Balance</td></tr>	
						<tr class=""><td>Number of Days worked</td><td>:</td><td>'.$final_settlement['no_of_days_worked'].'</td></tr>	
						<tr class="danger"><td>Number of Years worked</td><td>:</td><td>'.$final_settlement['no_of_years_worked'].'</td></tr>
						<tr class=""><td>No of Absence</td><td>:</td><td>'.$final_settlement['no_of_absence'].'</td></tr>
						<tr class="warning"><td>Net Total Years worked</td><td>:</td><td>'.$final_settlement['net_total_years_worked'].'</td></tr>
						<tr class=""><td>Total Leave Accrued</td><td>:</td><td>'.$final_settlement['total_leave_accrued'].'</td></tr>
						<tr class="info"><td>Leave Availed</td><td>:</td><td>'.$final_settlement['leave_availed'].'</td></tr>
						<tr class=""><td>Balance Leave </td><td>:</td><td>'.$final_settlement['balance_leave'].'</td></tr>		
						</tbody></table></div><br>
				
				
						<div class="col-lg-6 table-responsive"><table class="table table-md"><tbody>
						<tr class="bg-slate-600 text-center"><td colspan="3">Monthly Salary</td></tr>	
						<tr class=""><td>Basic Salary</td><td>:</td><td>'.$final_settlement['basic_salary'].'</td></tr>
						<tr class="danger"><td>House Rent Allowance</td><td>:</td><td>'.$final_settlement['house_rent_allowance'].'</td></tr>
						<tr class=""><td>Transp. Allowance</td><td>:</td><td>'.$final_settlement['transportation'].'</td></tr>
						<tr class="warning"><td>Food Allowance</td><td>:</td><td>'.$final_settlement['food_allowance'].'</td></tr>
						<tr class=""><td>Other Allowance</td><td>:</td><td>'.$final_settlement['other_allowance'].'</td></tr>
						<tr class="info"><td>Additional Benefit</td><td>:</td><td>'.$final_settlement['additional_benefits'].'</td></tr>
						<tr class=""><td>Bonus</td><td>:</td><td>'.$final_settlement['bonus'].'</td></tr>
						<tr class="success"><td>Total Salary </td><td>:</td><td>'.$final_settlement['total_salary'].'</td></tr>			
						</tbody></table></div><br>
						
						
						<div class="col-lg-6 table-responsive"><table class="table table-md"><tbody>
						<tr class="bg-slate-600 text-center"><td colspan="3">Working Hours & Over Time</td></tr>	
						<tr class="danger"><td>Normal Working Hours</td><td>:</td><td>'.$final_settlement['normal_working_hours'].'</td></tr>	
						<tr class=""><td>Working Hours for the month</td><td>:</td><td>'.$final_settlement['working_hours_for_the_month'].'</td></tr>
						<tr class="warning"><td>Actual Working Hours</td><td>:</td><td>'.round($final_settlement['actual_working_hour'],2).'</td></tr>
						<tr class=""><td>OT Hours @ 1.25</td><td>:</td><td>'.$final_settlement['ot_day_hours'].'</td></tr>
						<tr class="success"><td>OT Hours @ 1.5</td><td>:</td><td>'.$final_settlement['ot_night_hours'].'</td></tr>
						<tr class=""><td>OT Hours @ PH</td><td>:</td><td>'.$final_settlement['ot_holiday_hours'].'</td></tr>	
						</tbody></table></div><br>
						
					
							
						<div class="col-lg-12 table-responsive"><table class="table table-md"><tbody>
						<tr class="bg-slate-600"><td>Elements</td><td>Days</td><td>Amount</td></tr>	
						<tr class=""><td colspan="3"><b>Earnings</b></td></tr>	
						<tr class=""><td>Current Month Salary - '.date('F',strtotime($final_settlement['current_month_salary'])).'</td><td>'.round($final_settlement['total_days_of_the_month'],2).'</td><td>'.change_num_format($final_settlement['salary_of_the_month']).'</td></tr>
						<tr class=""><td>Leave Salary</td><td>'.$final_settlement['leave_salary_days'].'</td><td>'.$final_settlement['leave_salary'].'</td></tr>
						<tr class=""><td>OT  Amount @ 1.25</td><td></td><td>'.$final_settlement['ot_day_rate'].'</td></tr>
						<tr class=""><td>OT Amount @ 1.5</td><td></td><td>'.$final_settlement['ot_night_rate'].'</td></tr>
						<tr class=""><td>OT  Amount@ PH</td><td></td><td>'.$final_settlement['ot_holiday_rate'].'</td></tr>				
						<tr class=""><td>Total Earnings</td><td></td><td>'.change_num_format($final_settlement['total_earnings']).'</td></tr>';

						if($final_settlement['parent_type_name']){
							$i=0;
							foreach($final_settlement['parent_type_name'] as $earnings){
								if($earnings=='Addition'){
									$html_structure.='<tr class=""><td>'.$final_settlement['child_type_name'][$i].'</td><td>'.$final_settlement['salary_comments'][$i].'</td><td>'.$final_settlement['amount'][$i].'</td></tr>';
								}
							$i++;
							}
						}

						$html_structure.='<tr class=""><td colspan="3" ><b>Deductions</b></td></tr>';

						if($final_settlement['parent_type_name']){
							$j=0;
							foreach($final_settlement['parent_type_name'] as $deductions){
								if($deductions=='Deduction'){
									$html_structure.='<tr class=""><td>'.$final_settlement['child_type_name'][$j].'</td><td>'.$final_settlement['salary_comments'][$j].'</td><td>'.$final_settlement['amount'][$j].'</td></tr>';
								 }
							$j++;
							}
						}

						$html_structure.='<tr class=""><td>Total Deductions</td><td></td><td>'.$final_settlement['total_deductions'].'</td></tr>
						<tr class=""><td>Net Payable</td><td></td><td>'.change_num_format($final_settlement['net_payable']).'</td></tr>
						<tr class=""><td>Payment Amount to Employee</td><td></td><td>'.change_num_format($final_settlement['payment_amount_to_employee']).'</td></tr>
								</tbody></table></div></div></div>';


						$html_structure=str_replace(array(
						'<table',
						'<tr class="bg-slate-600"',
						'<tr class="bg-slate-600 text-center"',
						'<td',
						),
						array(

						'<table border="1" style="padding:10px;width: 100%;text-align:center;font-size:14px;line-height:20px;" ',
						'<tr style="text-align:center;background:#cc6076;color:white;font-size:13px;" ',
						'<tr style="text-align:center; background-color: #cc6076; border-color: #cc6076; color: #fff;" ',
						'<td style="padding:6px;" ',
						),
						htmlspecialchars_decode(stripslashes($html_structure)));



						if($count_of_dept!=1){
						$head_title='Final Settlement';
						$subject = $template[0]->subject;
						}else{
						$head_title='Final Settlement ReApproval';
						$subject = $head_title;
						}

						$sender = $this->Xin_model->read_user_info($created_by);

						$sender_designation = $this->Designation_model->read_designation_information($sender[0]->designation_id);
						$sender_full_name = change_fletter_caps($sender[0]->first_name.' '.$sender[0]->middle_name.' '.$sender[0]->last_name);



						if($user[0]->gender=='Male'){
							$title='Mr ';
						}else{
							$title='Ms ';
						}
						$message = '
						<div style="background: #f7eaea;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin: 0 auto;padding:20px;max-width: 65em;border: 2px solid #D40732;">
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
						$head_of_dep->head_name,
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
				}
				//*Mail Trigger*//
			 }
			 else{
				 /**/
				 $type_of_exit=$this->input->post('type_of_separtion');
				 $head_id='';
				 $created_at = date('Y-m-d');
				 $all_department_heads=$this->Employees_model->all_department_heads_with_it($department_id,'',$head_id,'Resignation45 ( + )',1);
				 //echo "<pre>";print_r($all_department_heads);die;
				 $count_of_dept=count($all_department_heads);
				 $setting = $this->Xin_model->read_setting_info(1);
				 $this->email->set_mailtype("html");

				 if($all_department_heads){
					 $insert_approvals=$this->Employees_model->insert_approvals($employee_id,$field_id,$created_by,$all_department_heads,$type_of_approval);

					 $designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
					 $emp_full_name = change_fletter_caps($user[0]->first_name.' '.$user[0]->middle_name.' '.$user[0]->last_name);

					 $html_structure='';
					 $currency=$this->Xin_model->currency_sign($number=null,$location_id='',$employee_id);


					 $cinfo = $this->Xin_model->read_company_setting_info(1);

					 foreach($all_department_heads as $head_of_dep){

					 $head_info = $this->Xin_model->read_user_info($head_of_dep->head_id);


					 $approve_link=$_SERVER['HTTP_HOST'].base_url().'index/clearance_form_approval/'.base64_encode($head_of_dep->head_id.'/'.$employee_id.'/1/'.$type_of_approval.'/'.$created_by.'/'.$field_id.'/'.$resultid);
					 $decline_link=$_SERVER['HTTP_HOST'].base_url().'index/clearance_form_approval/'.base64_encode($head_of_dep->head_id.'/'.$employee_id.'/1/'.$type_of_approval.'/'.$created_by.'/'.$field_id.'/'.$resultid);

					 //if($setting[0]->enable_email_notification == 'yes') {
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
						'<tr style="text-align:center; background-color: #cc6076; border-color: #cc6076; color: #fff;" ',
						'<td style="padding:6px;" ',
						'<div class="panel-heading text-center" style="text-align: center;margin-bottom: 1em;font-weight: bold;">'
						),
						htmlspecialchars_decode(stripslashes($html_structure)));

						if($count_of_dept!=1){
							$head_title='Clearance Form Approval';
							$subject = 'Clearance Form Approval';
						}else{
							$head_title='Clearance Form ReApproval';
							$subject = 'Clearance Form ReApproval';
						}

						$sender = $this->Xin_model->read_user_info($created_by);
						$sender_designation = $this->Designation_model->read_designation_information($sender[0]->designation_id);
						$sender_full_name = change_fletter_caps($sender[0]->first_name.' '.$sender[0]->middle_name.' '.$sender[0]->last_name);


						if($user[0]->gender=='Male'){
							$title='Mr ';
						}else{
							$title='Ms ';
						}
						$message = '<html><head>
						</head><body>
						<div style="background: #f7eaea;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin: 0 auto;padding:20px;max-width: 65em;border: 2px solid #D40732;">
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
						$head_of_dep->head_name,
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
						//$this->email->send();

					//}

					}
				 }
			 }
			$Return['result'] = 'Employee Exit added and send for approval.';
		}
		else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}

	public function update() {
	
		if($this->input->post('edit_type')=='exit') {
			
		$id = $this->uri->segment(3);

		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		$reason = $this->input->post('reason');
		$qt_reason = htmlspecialchars(addslashes($reason), ENT_QUOTES);
		
		if($this->input->post('employee_id')==='') {
       		 $Return['error'] = "The employee field is required.";
		} else if($this->input->post('exit_date')==='') {
			$Return['error'] = "The exit date field is required.";
		} else if($this->input->post('type')==='') {
			 $Return['error'] = "The exit type field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'employee_id' => $this->input->post('employee_id'),
		//'exit_date' => format_date('Y-m-d',$this->input->post('exit_date')),
		'reason' => $qt_reason,
		'exit_type_id' => $this->input->post('type'),
		'exit_interview' => 0,
		'is_inactivate_account' => 0,
		'cancellation_expense' => 0,
		 
		);
	
		$result = $this->Employee_exit_model->update_record($data,$id);		
		/*User Logs*/
		$affected_id= table_update_id('xin_employee_exit','exit_id',$id);
		userlogs('Employees-Employee Exit-Update','Employee Exit Updated',$id,$affected_id['datas']);
		/*User Logs*/
		if ($result == TRUE) {
			$Return['result'] = 'Employee Exit updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	public function delete() {
		$Return = array('result'=>'', 'error'=>'');
		$id = $this->uri->segment(3);

		$result_val = $this->db->query('select * from xin_employee_exit where exit_id='.$id.' limit 1');
		$result_val = $result_val->result();

		
		$payment_data = $this->db->query('select make_payment_id from xin_make_payment where comments="Final Settlement-'.$id.'" AND employee_id="'.$result_val[0]->employee_id.'" limit 1');
		$payment_val = $payment_data->result();
		$payment_id=$payment_val[0]->make_payment_id;
		
		/*User Logs*/
		$affected_row= table_deleted_row('xin_employee_exit','exit_id',$id);
		userlogs('Employees-Employee Exit-Delete','Employee Exit Deleted',$id,$affected_row);		
		/*User Logs*/
		$result = $this->Employee_exit_model->delete_record($id);
		
		$this->Employee_exit_model->delete_field_record($id);
		
		
		$this->db->where('field_id', $id);
		$this->db->where('employee_id', $result_val[0]->employee_id);
		$this->db->where('type_of_approval', $result_val[0]->approval_form);
		$this->db->delete('xin_employees_approval');
		
		
		$this->db->where('make_payment_id', $payment_id);
		$this->db->where('employee_id', $result_val[0]->employee_id);
		$this->db->delete('xin_make_payment');
		
		$this->db->where('make_payment_id', $payment_id);
		$this->db->delete('xin_payment_adjustments');
		
		$emp_last_data=array('date_of_leaving'=>'','is_active'=>1);
		$this->Employees_model->update_record($emp_last_data,$result_val[0]->employee_id);
		
		if(isset($id)) {
			$Return['result'] = 'Employee Exit ('.$affected_row['approval_form'].') deleted.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
	}

}
