<?php
/**
 * Author Siddiqkhan
 *
 * Attendance Controller
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance extends MY_Controller {

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
		$this->load->model("Timesheet_model");
		$this->load->model("Employees_model");
		$this->load->model("Xin_model");
		$this->load->model("Department_model");
		$this->load->model("Designation_model");
		$this->load->model("Roles_model");
		$this->load->model("Location_model");
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
		$data['breadcrumbs'] = 'Attendance';
		$data['path_url'] = 'user/user_attendance';
		if(!empty($this->userSession)){
			$data['subview'] = $this->load->view("user/attendance", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
		} else {
			redirect('');
		}

	}

	public function user_date_list() {
		$data['title'] = $this->Xin_model->site_title();
		if(empty($this->userSession)){
			redirect('');
		}

		$month_year=$this->input->get('month_year');
		$current_month=date('Y-m');
		$prev_month=date('Y-m',strtotime('-1 month',strtotime(date('Y-m'))));
		$next_month=date('Y-m',strtotime('+1 month',strtotime(date('Y-m'))));
		$current_m=salary_start_end_date($current_month);
		$prev_m=salary_start_end_date($prev_month);
		$next_m=salary_start_end_date($next_month);

		  if(strtotime($current_m['exact_date_start']) <= strtotime(TODAY_DATE) && strtotime($current_m['exact_date_end']) >= strtotime(TODAY_DATE)){
			  $current_month_st=$current_m['exact_date_start'];
			  $current_month_ed=$current_m['exact_date_end'];
			  if(strtotime($current_m['exact_date_end']) < strtotime(TODAY_DATE)){
				$current_month_ed=$current_m['exact_date_end'];
			  }else{
				$current_month_ed=TODAY_DATE;
			  }
			  $prev_month_st=$prev_m['exact_date_start'];
			  $prev_month_ed=$prev_m['exact_date_end'];
		  }else {
			  $current_month_st=$next_m['exact_date_start'];
			  if(strtotime($next_m['exact_date_end']) < strtotime(TODAY_DATE)){
				$current_month_ed=$next_m['exact_date_end'];
			  }else{
				$current_month_ed=TODAY_DATE;
			  }
			  $prev_month_st=$current_m['exact_date_start'];
			  $prev_month_ed=$current_m['exact_date_end'];
		  }
		if($month_year=='current'){
		$start_date=new DateTime($current_month_st);
		$end_date=new DateTime($current_month_ed);
		}else{
		$start_date=new DateTime($prev_month_st);
		$end_date=new DateTime($prev_month_ed);
		}

		$start_range = date_format($start_date,"Y-m-d");
		$end_range 	= date_format($end_date,"Y-m-d");
		$diff = abs(strtotime($end_range) - strtotime($start_range));
		$range_days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

		// pr($start_range);
		// pr($end_range);
		// pr($range_days);die;

		$end_date = $end_date->modify( '+1 day' );
		$interval_re = new DateInterval('P1D');
		$date_range = new DatePeriod($start_date, $interval_re ,$end_date);
		$data = '';
		$total_late_h=0;
		$user_id=$this->userSession['user_id'];
		$user = $this->Xin_model->read_user_info($user_id);

		$flexibleEmployees = getFlexibleEmployees();
		$isFlexible = in_array($user_id,$flexibleEmployees);

		if($user[0]->is_break_included == 1){
			$working_hours_per_day = date("H:i",strtotime($user[0]->working_hours) - 60*60);
		}else{
			$working_hours_per_day = $user[0]->working_hours;
		}		
		
		if($range_days > 0){
			if($end_range == date('Y-m-d')){
				$required_working_hours  = $working_hours_per_day * ($range_days);
			}else{
				$required_working_hours  = $working_hours_per_day * ($range_days + 1);
			}
		}else{
			$required_working_hours = $working_hours_per_day;
		}
		
		$total_working_hours_range = '';

		$country = $this->Location_model->read_location_information($user[0]->office_location_id);
        $country_id = $country[0]->country;
		foreach($date_range as $date) {

			$attendance_date =  $date->format("Y-m-d");
			$attendance_details = $this->Timesheet_model->attendance_details($user_id,$attendance_date);

			if(@$attendance_details[0]->clock_in==''){ $clock_in2 = '-';	}	else {
			$clock_in = new DateTime($attendance_details[0]->clock_in);
			$clock_in2 = $clock_in->format('h:i a');
			}
			if(@$attendance_details[0]->clock_out==''){ $clock_out2 = '-';	}	else {
			$clock_out = new DateTime($attendance_details[0]->clock_out);
			$clock_out2 = $clock_out->format('h:i a');
			}
			if(@$attendance_details[0]->time_late==''){ $time_late1 = '0h 0m';	}	else {
			$tim_l=str_replace('m','',str_replace('h ',':',$attendance_details[0]->time_late));
			$total_late_h+=decimalHourswithoutround($tim_l);
			if($tim_l!='00:00'){
				$tim_l=' ('.decimalHours($tim_l).')';
			}else{
				$tim_l='';
			}

			$time_late1=$attendance_details[0]->time_late;
			if($time_late1=='00:00'){$time_late1='0h 0m';}
			}
			if(@$attendance_details[0]->early_leaving==''){ $early_leaving1 = '0h 0m';	}	else { $early_leaving1=$attendance_details[0]->early_leaving;}
			if(@$attendance_details[0]->overtime==''  || @$attendance_details[0]->overtime=='00:00'){ $overtime1 = '0h 0m';	}	else {
			$overtime1=$attendance_details[0]->overtime;
			}

			if(@$attendance_details[0]->overtime_night=='' || @$attendance_details[0]->overtime_night=='00:00'){ $overtime_night = '0h 0m';	}	else {

			$overtime_night=$attendance_details[0]->overtime_night;

			}

			if(@$attendance_details[0]->total_work==''){ $total_work1 = '00:00';	}	else { $total_work1=$attendance_details[0]->total_work;}
			if(@$attendance_details[0]->attendance_status==''){ $status = '';	}	else{ $status=$attendance_details[0]->attendance_status;}
			$tdate = $this->Xin_model->set_date_format($attendance_date);

			$change_title=$tdate;

			@$real_status=$attendance_details[0]->attendance_status;
			$real_status=check_leave_status($attendance_date,$user_id,$attendance_details[0]->attendance_status,$attendance_details[0]->clock_in,$attendance_details[0]->clock_out,$attendance_details[0]->shift_start_time,$attendance_details[0]->shift_end_time,$attendance_details[0]->week_off,$user[0]->department_id,$country_id);

			$real_status1=$real_status;
			if($real_status){
			$real_status=$this->Timesheet_model->get_status_name($real_status);
			if($real_status=='Present')
			$real_status='<span class="label label-success">'.$real_status.'</span>';
			else if($real_status=='Absent')
			 if($attendance_date==TODAY_DATE)
			 $real_status='<span class="label label-info">Waiting to fetch..</span>';
			 else
			 $real_status='<span class="label label-danger">'.$real_status.'</span>';
			else if($real_status=='New Joinee')
			$real_status='<span class="label label-info">'.$real_status.'</span>';
			else if($real_status=='Late')
			$real_status='<span class="label label-warning">'.$real_status.'</span>';
			else
			$real_status='<span class="label bg-purple-400">'.$real_status.'</span>';

			}

			if($real_status=='<span class="label bg-purple-400"></span>'){
				$real_status='<span class="label bg-purple-400">'.$real_status1.'</span>';
			}

			$bus_lateness= get_bus_users_late($user_id,$attendance_date,$r->office_location_id);
			if($bus_lateness){
				if($bus_lateness['late_rest_value']!=0){
				$bus_late='<br><span class="label bg-teal-400 mt-5">Bus LT-'.$bus_lateness['late_hours'].'</span>';
				}else{$bus_late='';}
			}else{
				$bus_late='';
			}

			$result_manual= check_OB_Hours($user_id,$attendance_date);
			if($result_manual){
				$ob_hours='<br><span class="label bg-teal-400 mt-5" title="OB ( '.$result_manual[0]->attendance_status.' ) - '.$result_manual[0]->reason.'">OB ( '.substr($result_manual[0]->attendance_status,0,1).' ) - '.$result_manual[0]->total_hours.'</span>';
				$total_working_hours_range += $result_manual[0]->total_hours;
			}else{
				$ob_hours='';
			}

			if($real_status1 == 'P' || $real_status1 == 'LT'){
				$total_working_hours_range += $attendance_details[0]->total_rest;
			}elseif($real_status1 == 'WO' || $real_status1 == 'PH' || $real_status1 == 'AL' || $real_status1 == 'SL-1' || $real_status1 == 'ML-1' || $real_status1 == 'BL'){
				$total_working_hours_range += $working_hours_per_day;
			}elseif($real_status1 == 'SL-2' || $real_status1 == 'ML-2'){
				$total_working_hours_range += ($working_hours_per_day/2);
			}else{
				$total_working_hours_range += 0;
			}

			if(($attendance_details[0]->clock_in!='') && ($attendance_details[0]->clock_out=='') && (strtotime($attendance_date)!=strtotime(TODAY_DATE))){
				$login_entry=date("H:i", strtotime($attendance_details[0]->clock_in));
				$shift_end_b_1_hour=date("H:i", strtotime('0 hours', strtotime($attendance_details[0]->shift_end_time)));
				if(strtotime($login_entry) >= strtotime($shift_end_b_1_hour)){
				$clock_out2=$clock_in2;
				$clock_in2='-';
				}
			}

			if($isFlexible){
				$temp_total_work1 = explode('h ', $total_work1);
				if(intval($working_hours_per_day) <= $temp_total_work1[0] && $real_status1 == 'LT'){
					$real_status = '<span class="label label-success">Present</span>';
				}
			}

			if($real_status!='<span class="label bg-purple-400">Week OFF</span>'){
				$data.='<tr>
					  <td>'.$change_title.'</td>
					  <td>'.date('l', strtotime($attendance_date)).'</td>
					  <td>'.$clock_in2.'</td>
					  <td>'.$clock_out2.'</td>
					  <td>'.$time_late1.' / '.$early_leaving1.'</td>
					  <td>'.$real_status.$ob_hours.$bus_late.'</td>	
				</tr>';
			}
		}

		if($isFlexible){

			$delta_working = round($total_working_hours_range - $required_working_hours,2);
			$delta_working = sprintf('%02d:%02d', (int) $delta_working, fmod($delta_working, 1) * 60);
			$total_working_hours_range = sprintf('%02d:%02d', (int) $total_working_hours_range, fmod($total_working_hours_range, 1) * 60);
			$required_working_hours = sprintf('%02d:%02d', (int) $required_working_hours, fmod($required_working_hours, 1) * 60);

			if($delta_working == '00:00'){
				$delta_working_hours = 'No delta hours';
			}elseif(count(explode('-', $delta_working)) > 1){
				$delta_working_hours = '-'.decimalHoursFormat(str_replace('-', '', $delta_working)).' less delta hours';
			}else{
				$delta_working_hours = '+'.decimalHoursFormat($delta_working).' excess delta hours';
			}

			$data.='<tr>
				  <td colspan="4"></td>
				  <td><b>Required working hours : </b></td>
				  <td>'.decimalHoursFormat($required_working_hours).'</td>	
			</tr>';
			$data.='<tr>
				  <td colspan="4"></td>
				  <td><b>Total working hours : </b></td>
				  <td>'.decimalHoursFormat($total_working_hours_range).'</td>	
			</tr>';
			$data.='<tr>
				  <td colspan="4"></td>
				  <td><b>Delta working hours : </b></td>
				  <td>'.$delta_working_hours.'</td>	
			</tr>';
		}else{
			$data.='<tr>
				  <td colspan="4"></td>
				  <td><b>Total Lateness : </b></td>
				  <td>'.decimalHoursFormat_h($total_late_h).'</td>	
			</tr>';
		}
	 echo $data;
	}
	
	public function user_ob_hours()
	{
		$data['title'] = $this->Xin_model->site_title();
		$data['all_employees'] = $this->Xin_model->all_employees();
		$data['breadcrumbs'] = 'OB Hours';
		$data['path_url'] = 'user/user_ob_hours';
		if(!empty($this->userSession)){
			$data['subview'] = $this->load->view("user/user_ob_hours", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
		} else {
			redirect('');
		}

	}

	public function user_leave_card(){

		// pr($this->userSession);die;

			$employee_id=$this->userSession['user_id'];
			$user = $this->Xin_model->read_user_info($employee_id);
			$designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
			$department = $this->Department_model->read_department_information($user[0]->department_id);
			$location = $this->Location_model->read_location_information($user[0]->office_location_id);
			$date_of_joining=$user[0]->date_of_joining;
			$date_of_leaving=$user[0]->date_of_leaving;
			$_9months=date('Y-m-d',strtotime("+9 months",strtotime($date_of_joining)));
			$_6months=date('Y-m-d',strtotime("+6 months",strtotime($date_of_joining)));
			$_12months=date('Y-m-d',strtotime("+12 months",strtotime($date_of_joining)));
			$doj='N/A';
			$_6th='N/A';
			$_9th='N/A';
			$_12th='N/A';
			if($date_of_joining!=''){
				$doj=$this->Xin_model->set_date_format($date_of_joining);
			}
			if($_6months!=''){
				$_6th=$this->Xin_model->set_date_format($_6months);
			}
			if($_9months!=''){
				$_9th=$this->Xin_model->set_date_format($_9months);
			}
			if($_12months!=''){
				$_12th=$this->Xin_model->set_date_format($_12months);
			}
			/*$annual_leave_start_date=date('Y-m-d',strtotime(ANNUAL_LEAVE_ALLOW_EOS,strtotime($date_of_joining)));
            $annual_leave_full_start_date=date('Y-m-d',strtotime(ANNUAL_LEAVE_ALLOW_FULL,strtotime($date_of_joining)));
            */
			//Annual Leave
			$annual_leave='Annual Leave';
			$annual_leave_id=$this->Timesheet_model->read_leave_type_id($annual_leave);
			$annual_query = $this->db->query("SELECT * FROM `xin_leave_applications` WHERE `employee_id` = '".$employee_id."' AND 
		   `leave_type_id`='".$annual_leave_id."' AND status=2 AND reporting_manager_status=2 order by from_date ASC");
			$annual_result = $annual_query->result();
			//Annual Leave
			$balance_leave=0;

			if($date_of_leaving!=''){
				$calculate_date=$date_of_leaving;
			}else{
				$calculate_date=TODAY_DATE;
			}

			$an_leave_bal=annual_leave_balance($employee_id,$date_of_joining,$calculate_date);
			$balance_leave=$an_leave_bal['balance_leave'];
			$total_leave_annual=$an_leave_bal['total_leave_accrued'];
			$total_leave_availed=$an_leave_bal['leave_availed'];
			//Sick Leave
			$sick_leave='Sick Leave';
			$sick_leave_id=$this->Timesheet_model->read_leave_type_id($sick_leave);
			$sick_query = $this->db->query("SELECT * FROM `xin_leave_applications` WHERE `employee_id` = '".$employee_id."' AND 
		   `leave_type_id`='".$sick_leave_id."' AND status=2 AND reporting_manager_status=2 order by from_date ASC");
			$sick_result = $sick_query->result();
			//Sick Leave


			//Leave Coversion
			$leave_conversion_query = $this->db->query("SELECT * FROM `xin_leave_conversion_count` WHERE `employee_id` = '".$employee_id."' AND 
		   leave_conversion_count!=0 AND leave_conversion_count!=''  AND approved_status=1 order by added_date ASC");
			$leave_conversion_result = $leave_conversion_query->result();

			//Leave Coversion
			$expiry_leave_query = $this->db->query("SELECT adj.*,tp.type_name FROM `xin_employee_adjustments` as adj left join xin_module_types as tp on tp.type_id=adj.adjust_type_id WHERE adj.adjust_employee_id = '".$employee_id."' order by adj.adjust_id ASC");
			$expiry_leave_result = $expiry_leave_query->result();


			//Leave Coversion
			
			//Maternity Leave
			$maternity_leave='Maternity Leave';
			$maternity_leave_id=$this->Timesheet_model->read_leave_type_id($maternity_leave);
			$maternity_query = $this->db->query("SELECT * FROM `xin_leave_applications` WHERE `employee_id` = '".$employee_id."' AND 
		   `leave_type_id`='".$maternity_leave_id."' AND status=2 AND reporting_manager_status=2 order by from_date ASC");
			$maternity_result = $maternity_query->result();
			//Maternity Leave


			//Emergency Leave
			$emergency_leave='Emergency Leave';
			$emergency_leave_id=$this->Timesheet_model->read_leave_type_id($emergency_leave);
			$emergency_query = $this->db->query("SELECT * FROM `xin_leave_applications` WHERE `employee_id` = '".$employee_id."' AND 
		   `leave_type_id`='".$emergency_leave_id."' AND status=2 AND reporting_manager_status=2 order by from_date ASC");
			$emergency_result = $emergency_query->result();
			//Emergency Leave


			//Authorised Leave
			$authorise_leave='Authorised Absence';
			$authorise_leave_id=$this->Timesheet_model->read_leave_type_id($authorise_leave);
			$authorise_query = $this->db->query("SELECT * FROM `xin_leave_applications` WHERE `employee_id` = '".$employee_id."' AND 
		   `leave_type_id`='".$authorise_leave_id."' AND status=2 AND reporting_manager_status=2 order by from_date ASC");
			$authorise_result = $authorise_query->result();
			//Authorised Leave

			$html='';
			$html.='<div class="table-responsive"><table class="table table-md table-bordered"><tbody>
		<tr class="bg-slate-600 text-center"><td colspan="4"><strong>'.change_fletter_caps(@$user[0]->first_name.' '.@$user[0]->middle_name.' '.@$user[0]->last_name).'\'s Leave Card</strong></td></tr>
		<tr class="success"><td>Joining Date</td><td><strong>'.@$doj.'</strong></td><td>6<sup>th</sup> Month</td><td><strong>'.@$_6th.'</strong></td></tr>	
		<tr class="success"><td>9<sup>th</sup> Month</td><td><strong>'.@$_9th.'</strong></td><td>12<sup>th</sup> Month</td><td><strong>'.@$_12th.'</strong></td></tr>
		<tr class="success"><td>Department</td><td><strong>'.$department[0]->department_name.'</strong></td><td>Designation</td><td><strong>'.$designation[0]->designation_name.'</strong></td></tr><tr class="success"><td >Location</td><td colspan=3><strong>'.$location[0]->location_name.'</strong></td></tr>
		</tbody></table></div>';

			$html.='<div class="no-padding col-lg-6 table-responsive"><table class="table table-md table-bordered"><tbody>
		<tr class="danger text-center"><td colspan="3"><strong>Annual Leave</strong></td></tr>		
		<tr class=""><td><strong>From Date</strong></td><td><strong>To date</strong></td><td><strong>Total</strong></td></tr>';
			$last_balance=0;
			if($annual_result){
				foreach($annual_result as $a_result){
					$html.='<tr class=""><td>'.$this->Xin_model->set_date_format($a_result->from_date).'</td><td>'.$this->Xin_model->set_date_format($a_result->to_date).'</td><td>'.$a_result->count_of_days.'</td></tr>';
					$last_balance=($a_result->available_leave_days-$a_result->count_of_days);
				}
				$leave_conversion_count=0;
				if($leave_conversion_result){
					$html.='<tr class="text-center danger"><td colspan="3"><strong>Leave Conversion</strong></td></tr>';
					$html.='<tr><td><strong>Added Date</strong></td><td><strong>Comments</strong></td><td><strong>Converted Days</strong></td></tr>';
					foreach($leave_conversion_result as $l_c_res){
						$html.='<tr class=""><td>'.$this->Xin_model->set_date_format($l_c_res->added_date).'</td><td>'.$l_c_res->conversion_comments.'</td><td>'.$l_c_res->leave_conversion_count.'</td></tr>';
						$leave_conversion_count+=$l_c_res->leave_conversion_count;
					}
				}


				$adjust_days=0;
				$expired_html='';
				if($expiry_leave_result){					
					foreach($expiry_leave_result as $exp_res){
						$expired_html.='<tr><td></td><td><strong>'.$exp_res->type_name.'</strong></td><td><strong>'.$exp_res->adjust_days.' Day/s</strong></td></tr>';
						$adjust_days+=$exp_res->adjust_days;
					}
				}

				$last_balance=$balance_leave;			
			}
			else{
				$leave_conversion_count=0;
				if($leave_conversion_result){
					$html.='<tr class="text-center danger"><td colspan="4"><strong>Leave Conversion</strong></td></tr>';
					$html.='<tr><td></td><td><strong>Days Count</strong></td><td><strong>Comments</strong></td><td><strong>Added Date</strong></td></tr>';
					foreach($leave_conversion_result as $l_c_res){
						$html.='<tr class=""><td></td><td>'.$l_c_res->leave_conversion_count.'</td><td>'.$l_c_res->conversion_comments.'</td><td>'.$this->Xin_model->set_date_format($l_c_res->added_date).'</td></tr>';
						$leave_conversion_count+=$l_c_res->leave_conversion_count;
					}
				}

				$adjust_days=0;
				$expired_html='';
				if($expiry_leave_result){					
					foreach($expiry_leave_result as $exp_res){
						$expired_html.='<tr><td></td><td><strong>'.$exp_res->type_name.'</strong></td><td><strong>'.$exp_res->adjust_days.' Day/s</strong></td></tr>';
						$adjust_days+=$exp_res->adjust_days;
					}
				}

				$last_balance=$balance_leave;
				
			}
			$total_leave_availed = $total_leave_availed-$adjust_days;
			if($total_leave_availed < 0) {
				$total_leave_availed = 0;
			}
			$html.='<tr><td></td><td><strong>Total Earned Leaves</strong></td><td><strong>'.$total_leave_annual.' Day/s</strong></td></tr><tr><td></td><td><strong>Total Leaves Availed</strong></td><td><strong>'.$total_leave_availed.' Day/s</strong></td></tr>'.$expired_html.'<tr><td></td><td><strong>Balance Earned Leaves</strong></td><td><strong>'.$last_balance.' Day/s</strong></td></tr>';
			$html.='</tbody></table></div>';

			$html.='<div class="no-padding col-lg-6 table-responsive"><table class="table table-md table-bordered"><tbody>
                    <tr class="warning text-center"><td colspan="6"><strong>Sick Leave</strong></td></tr>		
                    <tr class=""><td><strong>Applied Leave Date/s</strong></td><td><strong>Total Leave</strong></td></tr>';

			if($sick_result){
				foreach($sick_result as $s_result){
					if($s_result->leave_status_code == 'SL-1'){
						$status='<strong class="text-success">Full Payment</strong>';
					}else if($s_result->leave_status_code == 'SL-2'){
						$status='<strong class="text-warning">Half Payment</strong>';
					}else{
						$status='<strong class="text-danger">No Payment</strong>';
					}
					$html.='<tr class=""><td>'.$this->Xin_model->set_date_format($s_result->from_date).' to '.$this->Xin_model->set_date_format($s_result->to_date).' ('.$status.')'.'</td><td>'.($s_result->count_of_days).'</td></tr>';
				}
			}
			else{
				$html.='<tr class="text-center"><td colspan="6">N/A</td></tr>';
			}
			$html.='</tbody></table></div>';
			if($maternity_result){
				$html.='<div class="no-padding col-lg-6 table-responsive"><table class="table table-md table-bordered"><tbody>
                        <tr class="success text-center"><td colspan="6"><strong>Maternity Leave</strong></td></tr>		
                        <tr class=""><td><strong>Applied Leave Date/s</strong></td><td><strong>Total Leave</strong></td></tr>';
				foreach($maternity_result as $m_result){
					if($m_result->leave_status_code == 'ML-1'){
						$status='<strong class="text-success">Full Payment</strong>';
					}/*else if($m_result->leave_status_code == 'ML-2'){
                     $status='<strong class="text-warning">Half Payment</strong>';
                    }*/else{
						$status='<strong class="text-danger">No Payment</strong>';
					}
					$html.='<tr class=""><td>'.$this->Xin_model->set_date_format($m_result->from_date).' to '.$this->Xin_model->set_date_format($m_result->to_date).' ('.$status.')'.'</td><td>'.($m_result->count_of_days).'</td></tr>';
                }
				$html.='</tbody></table></div>';
			}

			if($emergency_result){
				$html.='<div class="no-padding col-lg-6 table-responsive"><table class="table table-md table-bordered"><tbody>
                        <tr class="info text-center"><td colspan="6"><strong>Emergency Leave</strong></td></tr>		
                        <tr class=""><td><strong>Applied Leave Date/s</strong></td><td><strong>Total Leave</strong></td></tr>';

				foreach($emergency_result as $e_result){
					$html.='<tr class=""><td>'.$this->Xin_model->set_date_format($e_result->from_date).' to '.$this->Xin_model->set_date_format($e_result->to_date).'
                            </td><td>'.($e_result->count_of_days).'
                            </td></tr>';
				}
				$html.='</tbody></table></div>';
			}

			if($authorise_result){
				$html.='<div class="no-padding col-lg-6 table-responsive"><table class="table table-md table-bordered"><tbody>
                        <tr class="warning text-center"><td colspan="6"><strong>Authorised Absence Leave</strong></td></tr>		
                        <tr class=""><td><strong>Applied Leave Date/s</strong></td><td><strong>Total Leave</strong></td></tr>';

				foreach($authorise_result as $a_result){
					$html.='<tr class=""><td>'.$this->Xin_model->set_date_format($a_result->from_date).' to '.$this->Xin_model->set_date_format($a_result->to_date).'
                    </td><td>'.($a_result->count_of_days).'
                    </td></tr>';
				}
				$html.='</tbody></table></div>';
			}
			
			$data['html'] = $html;
			$data['title'] = $this->Xin_model->site_title();
			$data['breadcrumbs'] = "Employee's Leave Card";
			$data['path_url'] = 'employee_leavecard';
			if(!empty($this->userSession)){
				$data['subview'] = $this->load->view("timesheet/leavecard_emp", $data, TRUE);
				$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}

	}

	public function employee_manual_attendance_lists()
	{	
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("user/user_ob_hours", $data);
		} else {
			redirect('');
		}

		$draw = intval($this->input->get("draw"));
		$employee_id = $this->userSession['user_id'];
		$month_year = format_date('Y-m',$this->input->get("month_year"));
		$employee = $this->Xin_model->manual_attendance_list_by_employee($employee_id,$month_year);
		$data = array();
		foreach($employee as $r) {
		
		
			$start_date = $this->Xin_model->set_date_format($r->start_date);
			$end_date = $this->Xin_model->set_date_format($r->end_date);
			$start_time = $r->start_time;
			$end_time = $r->end_time;
			
			if(strtoupper($r->attendance_status) == 'PRESENT'){
				$attendance_status='<span class="label" style="border: 1px solid #4CAF50;color: #4CAF50;">'.$r->attendance_status.'</span>';
			}else{
				$attendance_status='<span class="label" style="border: 1px solid #FF5722;color: #FF5722;">'.$r->attendance_status.'</span>';
			}

			if($r->hr_head_status==0){
				$hr_head_status='<span class="label bg-info">Not Approved</span>';
			}else if($r->hr_head_status==2){
				$hr_head_status='<span class="label bg-danger">Declined</span>';
			}else{
				$hr_head_status='<span class="label bg-success">Approved</span>';
			}
			if($r->reporting_manager_status==0){
				$reporting_manager_status='<span class="label bg-info">Not Approved</span>';

			}else if($r->reporting_manager_status==2){
				$reporting_manager_status='<span class="label bg-danger">Declined</span>';
			}else{

				$reporting_manager_status='<span class="label bg-success">Approved</span>';
			}
			$shift_time = $start_date . ' to '.$end_date.' <br> ('.$start_time . ' to '.$end_time.') - '.$r->total_hours;
			$data[] = array(
				$shift_time,
				$attendance_status,
				$hr_head_status,
			);
		}
		$output = array(
			"draw" => $draw,
			"recordsTotal" => count($employee),
			"recordsFiltered" => count($employee),
			"data" => $data
		);
		$this->output($output);
	}

	public function add_manual_attendance() {
		if($this->input->post('add_type')=='add_manual_attendance') {
			$Return = array('result'=>'', 'error'=>'', 'message'=>'');
			$start_date = format_date('Y-m-d',$this->input->post('start_date'));
			$end_date = format_date('Y-m-d',$this->input->post('end_date'));
			$attendance_status=$this->input->post('attendance_status');
			$reason=$this->input->post('reason');
			$start_time=$this->input->post('start_time');
			$end_time=$this->input->post('end_time');
			
			if(!$this->input->post('attendance_status')) {
				$Return['error'] = "The attendance status field is required.";
			} else if($this->input->post('start_date')==='') {
				$Return['error'] = "The start date field is required.";
			} else if($this->input->post('end_date')==='') {
				$Return['error'] = "The end_date field is required.";
			} else if(strtotime($start_date) >  strtotime($end_date)) {
				$Return['error'] = "The end date should be greater than start date.";
			} else if($this->input->post('reason')==='') {
				$Return['error'] = "The reason field is required.";
			} else if($this->input->post('start_time')==='') {
				$Return['error'] = "The start time field is required.";
			} else if($this->input->post('end_time')==='') {
				$Return['error'] = "The end time field is required.";
			} else if(strtotime($start_time) >  strtotime($end_time)) {
				$Return['error'] = "The end time should be greater than start time.";
			}

			$c_in = new DateTime($start_time);
			$c_out = new DateTime($end_time);
			$interval = $c_in->diff($c_out);
			$hours   = $interval->format('%h');
			$minutes = $interval->format('%i');
			$total_hours=$hours.'h '.$minutes.'m';
			$total_work=$hours.':'.$minutes;
			$total_rest = decimalHourswithoutround($total_work);
			$rand=strtotime(date('Y-m-d H:i:s'));			
			$employee_id=$this->userSession['user_id'];
			$user = $this->Xin_model->read_user_info($employee_id);
			$reporting_manager=$user[0]->reporting_manager;
			$date_of_joining=$user[0]->date_of_joining;
			$department_id=$user[0]->department_id;
			$location_id=$user[0]->office_location_id;

			if($reporting_manager=='' || $reporting_manager==0){
				$Return['error'].= "No reporting manager assign to ".change_fletter_caps($user[0]->first_name.' '.$user[0]->middle_name.' '.$user[0]->last_name).".<br>";
			}
						
			if($Return['error']!=''){
				$this->output($Return);
			}

			$data = array(
				'manual_attendance_to' => 'employee',
				'employee_id' => $employee_id,
				'location_id' => $location_id,
				'department_id' => $department_id,
				'start_date' => $start_date,
				'end_date' => $end_date,
				'total_hours' => $total_hours,
				'end_time' => $end_time,
				'start_time' => $start_time,
				'total_rest' => $total_rest,
				'unique_code' => $rand,
				'attendance_status' => $attendance_status,
				'created_by' => $employee_id,
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
				'reason' => htmlspecialchars($reason,ENT_QUOTES),
				'reporting_manager_id'=>$reporting_manager,
			);
		
			$result = $this->Timesheet_model->add_manual_attendance($data);
			/*User Logs*/
			$affected_id= table_max_id('xin_manual_attendance','manual_attendance_id');
			userlogs('Timesheet-Manual Attendance-Add','New Manual Attendance Added',$affected_id['field_id'],$affected_id['datas']);
			/*User Logs*/
			if ($result == TRUE) {
				$Return['result'] = 'Manual Attendance added.';			
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}
	//Add Manual Attendance

}
