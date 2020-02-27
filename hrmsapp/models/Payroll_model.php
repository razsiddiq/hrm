<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 

class payroll_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function get_templates() {
		return $this->db->get("xin_salary_templates");
	}

	public function all_employees_salary_new(){
		$query=$this->db->query("select user_id,first_name,middle_name,last_name from xin_employees as emp left join xin_salary_templates as temp on temp.employee_id=emp.user_id where temp.employee_id is null");
		return $query->result();
	}

	public function get_templates_latest() {

		if(visa_wise_role_ids() != ''){

			$this->db->where('xin_employee_immigration.type',visa_wise_role_ids());
			$this->db->join('xin_employee_immigration', 'xin_employee_immigration.employee_id = sal.employee_id');
		}

		$this->db->where("sal.effective_to_date",'');
		$this->db->where("emp.is_active",1);
		$this->db->select('sal.*,loc.country as country_id,loc.location_name');
		$this->db->from('xin_salary_templates as sal');
		$this->db->join('xin_employees as emp','emp.user_id=sal.employee_id','left');
		$this->db->join('xin_office_location as loc','loc.location_id=emp.office_location_id','left');
		$this->db->order_by('sal.salary_template_id','DESC');
		return $this->db->get();
	}

	public function get_template_byemployee($id) {
		$condition = "sal.employee_id ='".$id."' AND emp.office_location_id!=''  AND emp.office_location_id!=0";
		$this->db->select('sal.*');
		$this->db->from('xin_salary_templates as sal');
		$this->db->join('xin_employees as emp','emp.user_id=sal.employee_id','left');
		$this->db->where($condition);
		$this->db->limit(50);
		$this->db->order_by('sal.salary_template_id','DESC');
		return $query=$this->db->get();
	}
	
	// get payroll templates
	public function get_employee_template($id,$location_value,$department_value,$status='') {

		// if(visa_wise_role_ids() != ''){

		// 	$this->db->where('xin_employee_immigration.type',visa_wise_role_ids());
		// 	$this->db->join('xin_employee_immigration', 'xin_employee_immigration.employee_id = emp.user_id');
		// }

		$this->db->select('emp.is_break_included,emp.user_id,emp.office_shift_id,emp.employee_id,emp.first_name,emp.middle_name,emp.last_name,emp.email,emp.user_role_id,emp.is_active,emp.designation_id,emp.department_id,emp.company_id,emp.office_location_id,emp.date_of_joining,emp.date_of_leaving,emp.working_hours,loc.country as country_id,doc.type,mod.type_name as visa_name');
		if($department_value!=0){
			$this->db->where('emp.department_id',$department_value);
		}
		if($location_value!=0){
			$this->db->where('emp.office_location_id',$location_value);
		}
		if($id!=''){
			$this->db->where('emp.user_id',$id);
		}
		if($status==''){
			$this->db->where('emp.is_active',1);
		}
		$this->db->group_by('emp.user_id');
		$this->db->from('xin_employees as emp');
		$this->db->join('xin_office_location as loc','loc.location_id=emp.office_location_id','left');
		$this->db->join('xin_employee_immigration as doc','doc.employee_id=emp.user_id AND doc.document_type_id=3','left');
		$this->db->join('xin_module_types as mod','mod.type_id=doc.type','left');
		return $this->db->get();
	}

	public function check_leaves_occupiedwithdate_counts($leave_id,$user_id,$exact_date_start,$exact_date_end){
		$check_annual_query=$this->db->query("SELECT lv.leave_id,lv.from_date,lv.to_date,
        sum(count_of_days) AS count_of_days FROM xin_leave_applications as lv WHERE lv.leave_type_id = '".$leave_id."' AND lv.employee_id = '".$user_id."' AND ((lv.from_date BETWEEN '".$exact_date_start."' AND '".$exact_date_end."') OR (lv.to_date BETWEEN '".$exact_date_start."' AND '".$exact_date_end."') OR (lv.from_date <= '".$exact_date_start."' AND lv.to_date >= '".$exact_date_end."')) AND lv.status = 2 AND lv.reporting_manager_status = 2 LIMIT 1");
		$result_annual_query=$check_annual_query->result();
		if($result_annual_query){
			return array('total_days'=>$result_annual_query[0]->count_of_days,'leave_start_date'=>$result_annual_query[0]->from_date,'leave_end_date'=>$result_annual_query[0]->to_date);
		}else{
			return array('total_days'=>0,'leave_start_date'=>'','leave_end_date'=>'');
		}
	}

	public function check_leaves_occupiedwithdates($leave_id,$user_id,$exact_date_start,$exact_date_end){
		$check_annual_query=$this->db->query("SELECT  lv.leave_id,lv.from_date,lv.to_date,lv.count_of_days
												FROM
													xin_leave_applications as lv 
												WHERE
													lv.leave_type_id = '".$leave_id."' 
													AND lv.employee_id = '".$user_id."' 
													AND ((lv.from_date BETWEEN '".$exact_date_start."' AND '".$exact_date_end."') OR (lv.to_date BETWEEN '".$exact_date_start."' AND '".$exact_date_end."') OR (lv.from_date <= '".$exact_date_start."' AND lv.to_date >= '".$exact_date_end."'))        
													AND lv.status = 2 
													AND lv.reporting_manager_status = 2");

		$result_annual_query=$check_annual_query->result();
		$value=[];
		if($result_annual_query){
			foreach($result_annual_query as $res){
				$value[]=array('total_days'=>$res->count_of_days,'leave_start_date'=>$res->from_date,'leave_end_date'=>$res->to_date);

			}
			return $value;
		}
	}

	public function check_leaves_occupied($leave_id,$user_id,$exact_date_start,$exact_date_end){
		$check_annual_query=$this->db->query("SELECT lv.leave_id,sum(count_of_days) AS count_of_days FROM xin_leave_applications as lv WHERE lv.leave_type_id = '".$leave_id."' AND lv.employee_id = '".$user_id."' AND ((lv.from_date BETWEEN '".$exact_date_start."' AND '".$exact_date_end."') OR (lv.to_date BETWEEN '".$exact_date_start."' AND '".$exact_date_end."') OR (lv.from_date <= '".$exact_date_start."' AND lv.to_date >= '".$exact_date_end."')) AND lv.status = 2 
        AND lv.reporting_manager_status = 2 LIMIT 1");
		$result_annual_query=$check_annual_query->result();
		return @$result_annual_query[0]->count_of_days;
	}

	public function check_public_holiday_occupiedwithdate($department_id,$country_id,$exact_date_start,$exact_date_end){
		$check_ph_query=$this->db->query("SELECT
        xin_holidays.holiday_id,xin_holidays.start_date,xin_holidays.end_date
		FROM
			xin_holidays 
		WHERE
			xin_holidays.country_id = '".$country_id."' 
			AND xin_holidays.department_id = '".$department_id."' 
			AND ((xin_holidays.start_date BETWEEN '".$exact_date_start."' AND '".$exact_date_end."') OR (xin_holidays.end_date BETWEEN '".$exact_date_start."' AND '".$exact_date_end."') OR (xin_holidays.start_date <= '".$exact_date_start."' AND xin_holidays.end_date >= '".$exact_date_end."'))
			AND xin_holidays.is_publish = 1 
		");

		$result_ph_query=$check_ph_query->result();
		$value=[];
		if($result_ph_query){
			foreach($result_ph_query as $res){
				$value[]= array('holiday_id'=>$res->holiday_id,'ph_from_date'=>$res->start_date,'ph_to_date'=>$res->end_date);
			}
			return $value;
		}
	}

	public function check_public_holiday_occupied($department_id,$country_id,$exact_date_start,$exact_date_end){
		$check_ph_query=$this->db->query("SELECT holi.holiday_id FROM xin_holidays as holi
		WHERE holi.country_id = '".$country_id."' AND holi.department_id = '".$department_id."' AND ((holi.start_date BETWEEN '".$exact_date_start."' AND '".$exact_date_end."') OR (holi.end_date BETWEEN '".$exact_date_start."' AND '".$exact_date_end."') OR (holi.start_date <= '".$exact_date_start."' AND holi.end_date >= '".$exact_date_end."')) AND holi.is_publish = 1 LIMIT 1");
		$result_ph_query=$check_ph_query->result();
		return @$result_ph_query[0]->holiday_id;
	}

	public function late_hours_worked($id,$exact_date_start,$exact_date_end,$date_of_joining='',$rate_per_hour_contract_bonus,$department_id,$location_id) {
		$exact_date_start=$exact_date_start;
		$exact_date_end=$exact_date_end;
		$sick_id=$this->Timesheet_model->read_leave_type_id('Sick Leave');
		$sick_res_arr=[];
		$sick_d=[];
		$start_date = new DateTime($exact_date_start);
		$end_date = new DateTime($exact_date_end);
		$end_date = $end_date->modify( '+1 day' );
		$interval_re = new DateInterval('P1D');
		$date_range = new DatePeriod($start_date, $interval_re ,$end_date);
		$result_annual_query=$this->check_leaves_occupied($sick_id,$id,$exact_date_start,$exact_date_end);
		if($result_annual_query!=''){
			foreach($date_range as $date){
				$attendance_date = $date->format("Y-m-d");
				$sick_res_arr[]= $this->Timesheet_model->check_sick_leave_type_id($attendance_date,$id,$sick_id,'');
			}
			$sick_res_arr=array_filter($sick_res_arr);
			if($sick_res_arr){
				foreach($sick_res_arr as $sick_res){
					$sick_keys = array_keys($sick_res);
					$sick_d[]=$sick_keys[0];

				}
			}
		}
		$time_late_sec=0;
		foreach($date_range as $date_bus){
			$attendance_date_bus = $date_bus->format("Y-m-d");
			$bus_lateness_sec=get_bus_users_late($id,$attendance_date_bus,$location_id);
			if($bus_lateness_sec){
				$time_late_sec+=$bus_lateness_sec['time_to_sec'];
			}
		}
		// 2nd step for query optimization
		foreach($date_range as $date){
			$ob_date = $date->format("Y-m-d");
			$query=$this->db->query("SELECT r_s.attendance_status,r_s.total_rest, REPLACE(REPLACE(time_late, 'h ', ':'),'m','') as time_late,TIME_TO_SEC(replace(replace(time_late,'h ',':'),'m',''))  as time_late_sec from `xin_manual_attendance` as `r_s` left join xin_attendance_time as a_t on a_t.attendance_date='".$ob_date."' AND a_t.employee_id='".$id."'  Where  `r_s`.`employee_id`='".$id."' and `r_s`.`location_id`='".$location_id."' and `r_s`.`department_id`='".$department_id."' and `r_s`.`hr_head_status` = 1 and `r_s`.`reporting_manager_status` = 1  and ('".$ob_date."' BETWEEN `r_s`.`start_date` and r_s.end_date) AND a_t.attendance_status='LT'  LIMIT 1");
			$result=$query->result();
			if($result){
				$result_val=$result[0]->attendance_status;
				if($result_val=='Present'){
					$time_late=decimalHours($result[0]->time_late);
					$result_rest=round($result[0]->total_rest,2);
					if($time_late==$result_rest){
						$time_late_sec+=$result[0]->time_late_sec;
					}
				}
			}
		}
		if($sick_d){
			$sick_in="and attendance_date NOT IN (".implode(',', array_map('quote', $sick_d)).")";
		}else{
			$sick_in='';
		}
		if((strtotime($exact_date_start) <= strtotime($date_of_joining)) && (strtotime($exact_date_end) >= strtotime($date_of_joining))){
			$query = $this->db->query("SELECT SUM(TIME_TO_SEC(replace(replace(time_late,'h ',':'),'m','')))  as time_late from xin_attendance_time where employee_id='".$id."' and attendance_date > '".$exact_date_start."' and attendance_date <='".$exact_date_end."' and attendance_status IN ('P','LT') $sick_in");
		}else{
			$query = $this->db->query("SELECT SUM(TIME_TO_SEC(replace(replace(time_late,'h ',':'),'m','')))  as time_late from xin_attendance_time where employee_id='".$id."' and attendance_date >='".$exact_date_start."' and attendance_date <='".$exact_date_end."' and attendance_status IN ('P','LT') $sick_in");
		}
		$result=$query->result();
		if($result){
			if($result[0]->time_late==''){$late=0;}else{$late=($result[0]->time_late-$time_late_sec);}
			$late_amount=secondsToTime($late);
			$late_amounts=decimalHourswithoutround($late_amount['h'].':'.$late_amount['m']);
			$late_s=array('time_late'=>$late,'time_late_amount'=>$late_amounts*$rate_per_hour_contract_bonus,'late_per_hour_amount'=>$rate_per_hour_contract_bonus);
		}else{
			$late_s=array('time_late'=>0,'time_late_amount'=>0,'late_per_hour_amount'=>$rate_per_hour_contract_bonus);
		}
		return $late_s;
	}

	// get total hours work > hourly template > payroll generate
	public function total_hours_worked($id,$exact_date_start,$exact_date_end,$date_of_joining='') {
		$exact_date_start=$exact_date_start;
		$exact_date_end=$exact_date_end;
		$sick_id=$this->Timesheet_model->read_leave_type_id('Sick Leave');
		$sick_res_arr=[];
		$sick_d=[];
		$start_date = new DateTime($exact_date_start);
		$end_date = new DateTime($exact_date_end);
		$end_date = $end_date->modify( '+1 day' );
		$interval_re = new DateInterval('P1D');
		$date_range = new DatePeriod($start_date, $interval_re ,$end_date);
		// 2nd step for query optimization
		$result_annual_query=$this->check_leaves_occupied($sick_id,$id,$exact_date_start,$exact_date_end);
		if($result_annual_query!=''){
			foreach($date_range as $date){
				$attendance_date = $date->format("Y-m-d");
				$sick_res_arr[]= $this->Timesheet_model->check_sick_leave_type_id($attendance_date,$id,$sick_id,'');
			}
			$sick_res_arr=array_filter($sick_res_arr);
			if($sick_res_arr){
				foreach($sick_res_arr as $sick_res){
					$sick_keys = array_keys($sick_res);
					$sick_d[]=$sick_keys[0];
				}
			}
		}
		// 2nd step for query optimization
		if($sick_d){
			$sick_in="and attendance_date NOT IN (".implode(',', array_map('quote', $sick_d)).")";
		}else{
			$sick_in='';
		}

		if((strtotime($exact_date_start) <= strtotime($date_of_joining)) && (strtotime($exact_date_end) >= strtotime($date_of_joining))){
			$query = $this->db->query("SELECT sum(total_rest) as actual_working_hours,SUM(TIME_TO_SEC(replace(replace(time_late,'h ',':'),'m','')))  as time_late,SUM(TIME_TO_SEC(replace(replace(early_leaving,'h ',':'),'m',''))) as early_leaving,SUM(TIME_TO_SEC(replace(replace(total_work,'h ',':'),'m',''))) as total_work from xin_attendance_time where employee_id='".$id."' and attendance_date > '".$exact_date_start."' and attendance_date <='".$exact_date_end."' and attendance_status IN ('P','LT') $sick_in");
		}else{
			$query = $this->db->query("SELECT sum(total_rest) as actual_working_hours,SUM(TIME_TO_SEC(replace(replace(time_late,'h ',':'),'m','')))  as time_late,SUM(TIME_TO_SEC(replace(replace(early_leaving,'h ',':'),'m',''))) as early_leaving,SUM(TIME_TO_SEC(replace(replace(total_work,'h ',':'),'m',''))) as total_work from xin_attendance_time where employee_id='".$id."' and attendance_date >='".$exact_date_start."' and attendance_date <='".$exact_date_end."' and attendance_status IN ('P','LT') $sick_in");
		}
		return $query->result();
	}

	// get total hours work > hourly template > payroll generate
	public function total_missed_punchout($id,$exact_date_start,$exact_date_end) {
		$exact_date_start=$exact_date_start;
		$exact_date_end=$exact_date_end;
		$query = $this->db->query("SELECT (count(clock_out)*3600) as counts from xin_attendance_time where employee_id='".$id."' and clock_out='' and attendance_date >='".$exact_date_start."' and attendance_date <='".$exact_date_end."' and attendance_status IN ('P','LT') and clock_in_out!=2 and attendance_date!='".TODAY_DATE."' limit 1");
		$result=$query->row();
		return $result->counts;
	}

	//get salary addition and deduction
	public function salary_addition_deduction_list($parent_type,$child_type,$user_id,$payment_month) {
		$slug='';
		if($parent_type!=0){
			$slug.='AND p_ty.parent_type="'.$parent_type.'"';
		}
		if($child_type!=0){
			$slug.='AND p_ty.child_type="'.$child_type.'"';
		}
		if($user_id!=0){
			$slug.='AND m_p.employee_id="'.$user_id.'"';
		}
		if($payment_month!=0){
			$slug.='AND m_p.payment_date="'.$payment_month.'"';
		}
		$query = $this->db->query("SELECT p_ty.*,s_ty.type_name as parent_name,s_ty1.type_name as child_name,m_p.employee_id,m_p.payment_date,m_p.is_payment,emp.first_name,emp.employee_id FROM `xin_payment_adjustments` as p_ty left join xin_salary_types s_ty on s_ty.type_id=p_ty.parent_type left join xin_salary_types s_ty1 on s_ty1.type_id=p_ty.child_type left join xin_make_payment m_p on m_p.make_payment_id=p_ty.make_payment_id left join xin_employees emp on emp.user_id=m_p.employee_id where m_p.make_payment_id!='' $slug");
		return $result=$query;
	}

	//Sandwich Status
	public function sandwich_status($id,$exact_date_start,$exact_date_end,$department_id,$country_id) {
		/*WO*/
		$query = $this->db->query("SELECT count(attendance_status) as actual_working_hour,(SELECT GROUP_CONCAT(attendance_date SEPARATOR ',') from xin_attendance_time where employee_id='".$id."' and attendance_date >='".$exact_date_start."' and attendance_date <='".$exact_date_end."' and attendance_status='WO') as cal_date from xin_attendance_time where employee_id='".$id."' and attendance_date >='".$exact_date_start."' and attendance_date <='".$exact_date_end."' and attendance_status='WO' limit 1");// IN ('PH','WO')
		$result=$query->result();
		$calculated_date=explode(',',$result[0]->cal_date);

		$start_date_a = new DateTime($exact_date_start);
		$end_date_a = new DateTime($exact_date_end);
		$end_date_a = $end_date_a->modify( '+1 day' );
		$interval_re_a = new DateInterval('P1D');
		$date_range_a = new DatePeriod($start_date_a, $interval_re_a ,$end_date_a);
		$ph_date=[];
		foreach($date_range_a as $date_a) {
			$attendance_date_a = $date_a->format("Y-m-d");
			$ph_query=$this->db->query("SELECT `h_d`.`holiday_id` FROM `xin_attendance_time` as `a_t` LEFT JOIN `xin_holidays` as `h_d` ON `h_d`.`department_id`='".$department_id."' AND `h_d`.`country_id`='".$country_id."' WHERE `a_t`.`employee_id` = '".$id."' and `a_t`.`clock_in`='' AND `a_t`.`attendance_date` = '".$attendance_date_a."' AND `h_d`.`is_publish` = 1  and ('".$attendance_date_a."' BETWEEN `h_d`.`start_date` and h_d.end_date) LIMIT 1");
			$ph_result=$ph_query->result();
			if($ph_result){
				$ph_date[]=$attendance_date_a;
			}
		}
		// echo "<pre>"; print_r($ph_date);
		$calculated_date=array_merge($calculated_date,$ph_date);
		$leave_array=[];
		$sandwich_array=[];
		$total_absent_next=0;
		$count_of_leaves1=0;
		foreach($calculated_date as $cal){
			$leave_status1=$this->count_of_leave_status($cal,$cal,$id,$department_id,$country_id,1,'',$shift_hours='');
			$cal1=array($cal);
			if($leave_status1){
				foreach($leave_status1 as $st_leave1){
					$count_of_leaves1+=$st_leave1->count_attendance_status;
				}
			}

			if($count_of_leaves1==0){
				$leave_array=sandwich_leaves($id,$department_id,$country_id,$cal1);
				if($leave_array){
					foreach($leave_array as $key_ar=>$val_ar){
						$sandwich_array[]=$key_ar;
						$sandwich_array[]=$val_ar;
					}

				}
			}
			//print_r($sandwich_array);die;
		}
		/*if($ph_date){
			$leave_array_ph=sandwich_leaves($id,$department_id,$country_id,$ph_date);
			if($leave_array_ph){
			foreach($leave_array_ph as $key_arp=>$val_arp){
				$sandwich_array[]=$key_arp;
				$sandwich_array[]=$val_arp;
			}

		}
		}*/

		// echo "<pre>";print_r($sandwich_array);print_r($leave_array);
		return $sandwich_array;
		/*$total_absent_next=count($leave_array);
		// echo $total_absent_next;
		$leave_status=$this->count_of_leave_status($exact_date_start,$exact_date_end,$id,$department_id,$country_id,1,'',$shift_hours);
		$count_of_leaves=0;
		if($leave_status){
			foreach($leave_status as $st_leave){
				 $count_of_leaves+=$st_leave->count_attendance_status;
			}
		}

		$weak_of_days=($result[0]->actual_working_hour-$total_absent_next);
		$count_of_leaves=$count_of_leaves;

		*/
	}
	//Sandwich Status

	//get_salary_employees
	public function get_salary_employees(){
		$query = $this->db->query("SELECT emp.first_name,emp.employee_id,emp.user_id FROM `xin_payment_adjustments` as p_ty left join xin_make_payment m_p on m_p.make_payment_id=p_ty.make_payment_id left join xin_employees emp on emp.user_id=m_p.employee_id where m_p.make_payment_id!='' group by emp.user_id");
		return $result=$query->result();
	}

	//get_salary_payment_month
	public function get_salary_payment_month(){
		$query = $this->db->query("SELECT m_p.payment_date FROM `xin_payment_adjustments` as p_ty  left join xin_make_payment m_p on m_p.make_payment_id=p_ty.make_payment_id where m_p.make_payment_id!='' group by m_p.payment_date");
		return $result=$query->result();
	}

	public function weakoff_hours($id,$exact_date_start,$exact_date_end,$shift_hours,$department_id,$country_id,$check_ramadan_date) {
		$exact_date_start=$exact_date_start;
		$exact_date_end=$exact_date_end;
		$ramadan_days_count=count($check_ramadan_date);
		/*WO*/
		$query = $this->db->query("SELECT count(attendance_status) as actual_working_hour,(SELECT GROUP_CONCAT(attendance_date SEPARATOR ',') from xin_attendance_time where employee_id='".$id."' and attendance_date >='".$exact_date_start."' and attendance_date <='".$exact_date_end."' and attendance_status='WO' and clock_in='' ) as cal_date from xin_attendance_time where employee_id='".$id."' and attendance_date >='".$exact_date_start."' and attendance_date <='".$exact_date_end."' and attendance_status='WO' limit 1");// IN ('PH','WO')
		$result=$query->result();
		/*Sandwich Leaves start*/
		$calculated_date=explode(',',$result[0]->cal_date);
		$leave_array=[];
		$total_absent_next=0;
		$leave_array=sandwich_leaves($id,$department_id,$country_id,$calculated_date);
		/*Sandwich Leaves End*/

		$total_absent_next=count($leave_array);
		// echo $total_absent_next;
		$leave_status=$this->count_of_leave_status($exact_date_start,$exact_date_end,$id,$department_id,$country_id,1,'',$shift_hours);
		$count_of_leaves=0;
		if($leave_status){
			foreach($leave_status as $st_leave){
				$count_of_leaves+=$st_leave->count_attendance_status;
			}
		}

		$weak_of_days=($result[0]->actual_working_hour-$total_absent_next);
		$count_of_leaves=$count_of_leaves;

		if($ramadan_days_count==0){
			return (($weak_of_days-$count_of_leaves)*$shift_hours);
		}else{
			$w_of_date='';
			$w_of_hours='';

			foreach($check_ramadan_date as $ramadan_date){
				$w_of_date[]=$ramadan_date['attendance_date'];
				$w_of_hours[$ramadan_date['attendance_date']]=$ramadan_date['reduced_hours'];

			}
			if($w_of_date){
				$where_in=implode(',', array_map('quote', $w_of_date));
			}else{
				$where_in='';
			}

			/*WO*/
			$query_rdn = $this->db->query("SELECT attendance_date from xin_attendance_time where employee_id='".$id."' and attendance_date >='".$exact_date_start."' and attendance_date <='".$exact_date_end."' and attendance_status='WO' AND attendance_date IN (".$where_in.")");// IN ('PH','WO')	   limit 1
			$result_rdn=$query_rdn->result();
			/*WO*/


			// 2 wo
			$leave_status_rdn=$this->count_of_leave_status_only_ramadan($exact_date_start,$exact_date_end,$id,$department_id,$country_id,1,$check_ramadan_date,$shift_hours);
			//echo "<pre>";print_r($leave_status_rdn);
			$count_of_leaves_rdn=count($leave_status_rdn);
			/*if($leave_status_rdn){
                foreach($leave_status_rdn as $st_leave_rdn){
                     $count_of_leaves_rdn+=$st_leave_rdn->count_attendance_status;
                }
            }*/
			$ramadan_weak_of_days=count($result_rdn);//$result_rdn[0]->actual_working_hour;

			$total_remaining_wo_days=$weak_of_days-$ramadan_weak_of_days;


			if($total_remaining_wo_days < 0){
				$total_remaining_wo_days=0;
			}

			$total_remaining_count_of_leaves=$count_of_leaves-$count_of_leaves_rdn;

			if($total_remaining_count_of_leaves < 0){
				$total_remaining_count_of_leaves=0;
			}

			$add_count=0;
			$add_count+=($total_remaining_wo_days-$total_remaining_count_of_leaves)*$shift_hours;
			$reduced_hours='';
			foreach($result_rdn as $key=>$res_rdn){

				if(!in_array($res_rdn->attendance_date,$leave_status_rdn)){
					$reduced_hours[$res_rdn->attendance_date]=$shift_hours-$w_of_hours[$res_rdn->attendance_date];
				}
			}

			$ramdan_hours=array_sum($reduced_hours);
			$add_count+=$ramdan_hours;
			return $add_count;
		}
	}

	public function check_ramadan_date($country_id,$start_date,$end_date){
		$exact_date_end=$end_date;
		$exact_date_start=$start_date;
		$start_date = new DateTime($start_date);
		$end_date = new DateTime($end_date);
		$end_date = $end_date->modify( '+1 day' );
		$interval_re = new DateInterval('P1D');
		$date_range = new DatePeriod($start_date, $interval_re ,$end_date);
		$ramadan_date=array();
		// 3rd step for query optimization
		$check_annual_query=$this->db->query("SELECT ram.ramadan_schedule_id FROM  xin_ramadan_schedule as ram WHERE ram.country_id = '".$country_id."'  AND ((ram.start_date BETWEEN '".$exact_date_start."' AND '".$exact_date_end."') OR (ram.end_date BETWEEN '".$exact_date_start."' AND '".$exact_date_end."') OR (ram.start_date <= '".$exact_date_start."' AND ram.end_date >= '".$exact_date_end."')) AND ram.is_publish = 1 LIMIT 1");
		$result_annual_query=$check_annual_query->result();
		if($result_annual_query){
			if($result_annual_query[0]->ramadan_schedule_id!=''){
				foreach($date_range as $date) {
					$attendance_date = $date->format("Y-m-d");
					$query=$this->db->query("SELECT reduced_hours from `xin_ramadan_schedule` as `r_s` Where  `r_s`.`country_id`='".$country_id."' and `r_s`.`is_publish` = 1  and ('".$attendance_date."' BETWEEN `r_s`.`start_date` and r_s.end_date)  LIMIT 1");
					$result = $query->result();
					if($result){
						$ramadan_date[]=array('attendance_date'=>$attendance_date,'reduced_hours'=>decimalHours($result[0]->reduced_hours));
					}
				}
			}
		}
		// 3rd step for query optimization
		return $ramadan_date;
	}

	public function check_weekoff_hours($id,$exact_date_start,$exact_date_end,$department_id,$country_id,$shift_hours) {
		$query = $this->db->query("SELECT count(attendance_status) as actual_working_hour,(SELECT GROUP_CONCAT(attendance_date SEPARATOR ',')  from xin_attendance_time where employee_id='".$id."' and attendance_date >='".$exact_date_start."' and attendance_date <='".$exact_date_end."' and attendance_status='WO') as cal_date from xin_attendance_time where employee_id='".$id."' and attendance_date >='".$exact_date_start."' and attendance_date <='".$exact_date_end."' and attendance_status='WO' limit 1");
		$result=$query->result();
		$calculated_date=explode(',',$result[0]->cal_date);
		$leave_arrays=[];
		$total_absent_next=0;
		$count_of_leaves1=0;
		foreach($calculated_date as $cal){
			$leave_status1=$this->count_of_leave_status_new($cal,$cal,$id,$department_id,$country_id,1,'',$shift_hours);
			$cal1=array($cal);
			if($leave_status1){
				foreach($leave_status1 as $st_leave1){
					$count_of_leaves1+=$st_leave1->count_attendance_status;
				}
			}
			if($count_of_leaves1==0){
				$leave_array=sandwich_leaves($id,$department_id,$country_id,$cal1);
				if($leave_array){
					foreach($leave_array as $val_ar){
						$leave_arrays[]=$val_ar;
					}
				}
			}
		}
		$total_absent_next=count($leave_arrays);
		$weak_of_days=$result[0]->actual_working_hour-$total_absent_next;
		$count_of_leaves=$count_of_leaves1;
		$total_rest=$weak_of_days-$count_of_leaves;
		$wo_array=array('attendance_status'=> 'WO','count_attendance_status'=> $total_rest,'total_rest'=>$total_rest*$shift_hours);
		$wo_object[] = (object) $wo_array;
		return $wo_object;
	}

	public function sick_leave_half_hours($id,$attendance_date,$shift_hours) {
		$salary_date=salary_start_end_date($attendance_date);
		$exact_date_start=$salary_date['exact_date_start'];
		$exact_date_end=$salary_date['exact_date_end'];
		$query = $this->db->query("SELECT count(attendance_status) as actual_working_hour from xin_attendance_time where employee_id='".$id."' and attendance_date >='".$exact_date_start."' and attendance_date <='".$exact_date_end."' and attendance_status IN ('SL-2')");
 		$result=$query->result();
		return (($result[0]->actual_working_hour*$shift_hours)/2);
	}

	public function get_cumulative_amount($make_payment_id){
		$query = $this->db->query("SELECT sum(p_t.amount) as amount,s_t.type_name FROM `xin_payment_adjustments` as p_t left join xin_salary_types as s_t on s_t.type_id=p_t.parent_type WHERE p_t.make_payment_id='".$make_payment_id."' group by p_t.parent_type");
		return $result=$query->result();
	}

	public function all_payment_history($department_id,$location_id,$month_year,$visa_id) {

		if(visa_wise_role_ids() != ''){

			$this->db->where('xin_employee_immigration.type',visa_wise_role_ids());
			$this->db->join('xin_employee_immigration', 'xin_employee_immigration.employee_id = xin_make_payment.employee_id');
		}

		if($department_id!=0){
			$this->db->where('department_id',$department_id);
		}
		if($location_id!=0){
			$this->db->where('location_id',$location_id);
		}

		$this->db->where('payment_date',$month_year);
		if($visa_id==0){
			return $this->db->get("xin_make_payment");
		}
		else{
			$this->db->select('m_p.*');
			$this->db->from('xin_make_payment as m_p');
			$this->db->join('xin_employee_immigration as im','im.employee_id=m_p.employee_id','right');
			$this->db->where('im.document_type_id',3);
			$this->db->where('im.type',$visa_id);
			return $query=$this->db->get();
		}
	}

	public function all_agency_payment_history($department_id,$location_id,$month_year,$visa_id) {
		$slug="";
		if($department_id!=0){
			$slug.=" AND mp.department_id='".$department_id."'";
		}
		if($location_id!=0){
			$slug.=" AND mp.location_id='".$location_id."'";
		}

		if($visa_id!=0){
			$slug.=" AND im.type='".$visa_id."'";
		}
		
		$con = '';
		$con1 = '';
		if(visa_wise_role_ids() != ''){

			$con = "INNER JOIN xin_employee_immigration ON xin_employee_immigration.employee_id = mp.employee_id";
			$con1 = "AND xin_employee_immigration.type = ".visa_wise_role_ids();
		}

		return $query=$this->db->query("SELECT mp.*,pt.*,mods.type_name as agency_name,pt.amount as agency_fees from xin_make_payment as mp $con
										left join xin_payment_adjustments as pt on pt.make_payment_id=mp.make_payment_id 
										left join xin_employee_immigration as im on im.employee_id=mp.employee_id AND im.document_type_id=3 left join xin_module_types as mods on mods.type_id=im.type 
										where mp.payment_date='".$month_year."' $con1 AND pt.child_type=52 $slug GROUP By mp.employee_id  ");

	}

	// get payslips of single employee
	public function get_payroll_slip($id) {
		return $query = $this->db->query("SELECT * from xin_make_payment where employee_id='".$id."'");
	}

	public function update_effective_to_date($data,$id){
		$this->db->where('salary_template_id', $id);
		if( $this->db->update('xin_salary_templates',$data)) {
			return true;
		} else {
			return false;
		}
	}

	public function if_pay_add_already($employee_id){
		$condition = "employee_id =" . "'" . $employee_id . "' AND is_approved=1";
		$this->db->select_max('salary_template_id');
		$this->db->select('effective_from_date,effective_to_date');
		$this->db->from('xin_salary_templates');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();

	}

	public function read_template_information($id) {
		$condition = "salary_template_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_salary_templates');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_max_payment_id(){
		$this->db->select_max('make_payment_id');
		$this->db->limit(1);
		$query = $this->db->get('xin_make_payment');
		return $query->row();
	}

	public function read_template_information_byempid($id) {
		$condition = "employee_id ='" . $id . "' AND effective_to_date =''";
		$this->db->select('*');
		$this->db->from('xin_salary_templates');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	public function read_template_end_effective_date($id) {
		$query = $this->db->query("SELECT * FROM `xin_salary_templates` WHERE `employee_id` = '".$id."' AND is_approved=1 order by effective_from_date asc");
		$result = $query->result();
		return $result;
	}

	public function read_previous_pay_structure($employee_id){
		$query = $this->db->query("SELECT xin_salary_templates.*,MAX(`salary_template_id`) AS `salary_template_id` 
		FROM `xin_salary_templates` WHERE `employee_id` = '".$employee_id."' AND effective_to_date!='' AND `is_approved` = 1 LIMIT 1");//
		$result = $query->result();
		return $result;
	}

	public function get_salary_fields($salary_template_id){
		$this->db->select('empsal.*,sal.salary_field_name');
		$this->db->where('sal.salary_calculate',1);
		$this->db->where('empsal.salary_template_id',$salary_template_id);
		$this->db->from('xin_employees_salary as empsal');
		$this->db->join('xin_salary_fields_bycountry as sal','sal.salary_field_id=empsal.salary_field_id','left');
		$this->db->order_by('sal.salary_field_order','ASC');
		$query = $this->db->get();
		$result= $query->result();
		$value=[];
		if($result){
			foreach($result as $res){
				$value[$res->salary_field_name]=$res;
			}
		}
		return $value;
	}

	public function count_of_all_status($from_date,$to_date,$user_id,$shift_hours,$check_ramadan_date,$department_id,$country_id,$location_id){
		$exceptional_employees=exceptional_employees($user_id);
		$sick_id=$this->Timesheet_model->read_leave_type_id('Sick Leave');
		$sick_res_arr=[];
		$exact_date_start=$from_date;
		$exact_date_end=$to_date;
		$start_date = new DateTime($from_date);
		$end_date = new DateTime($to_date);
		$end_date = $end_date->modify( '+1 day' );
		$interval_re = new DateInterval('P1D');
		$date_range = new DatePeriod($start_date, $interval_re ,$end_date);
		$ph_result=[];
		$result_sick_query=$this->check_leaves_occupied($sick_id,$user_id,$exact_date_start,$exact_date_end);
		$result_pholiday_query=$this->check_public_holiday_occupied($department_id,$country_id,$exact_date_start,$exact_date_end);
		if($result_sick_query!='' || $result_pholiday_query!=''){
			foreach($date_range as $date){
				$attendance_date = $date->format("Y-m-d");
				if($result_pholiday_query!=''){
					$ph_result[]= $this->Timesheet_model->check_public_holiday_type_id($attendance_date,$user_id,$department_id,$country_id,'');
				}
				if($result_sick_query!=''){
					$sick_res_arr[]= $this->Timesheet_model->check_sick_leave_type_id($attendance_date,$user_id,$sick_id,'');
				}
			}
		}
		$p_of_date=[];
		$ph_result=array_filter($ph_result);
		if($ph_result){
			foreach($ph_result as $pub_holiday_date){
				$holiday_keys = array_keys($pub_holiday_date);
				$p_of_date[]=$holiday_keys[0];
			}
		}
		$sick_d=[];
		$sick_res_arr=array_filter($sick_res_arr);
		if($sick_res_arr){
			foreach($sick_res_arr as $sick_res){
				$sick_keys = array_keys($sick_res);
				$sick_d[]=$sick_keys[0];
			}
		}
		if($p_of_date){
			$ph_in="and attendance_date NOT IN (".implode(',', array_map('quote', $p_of_date)).")";
		}else{
			$ph_in='';
		}
		if($sick_d){
			$sick_in="and attendance_date NOT IN (".implode(',', array_map('quote', $sick_d)).")";
		}else{
			$sick_in='';
		}
		$w_of_date=[];
		$w_of_hours=[];
		foreach($check_ramadan_date as $ramadan_date){
			$w_of_date[]=$ramadan_date['attendance_date'];
			$w_of_hours[$ramadan_date['attendance_date']]=$ramadan_date['reduced_hours'];
		}
		$query = $this->db->query("SELECT attendance_status,count(attendance_status) as count_attendance_status,sum(total_rest) as total_rest,SUM(TIME_TO_SEC(replace(replace(overtime,'h ',':'),'m',''))) as overtime,SUM(TIME_TO_SEC(replace(replace(overtime_night,'h ',':'),'m',''))) as overtime_night,GROUP_CONCAT(attendance_date SEPARATOR ',') as attendance_date  FROM `xin_attendance_time` WHERE `employee_id`= '".$user_id."' AND `attendance_date` >= '".$from_date."' AND `attendance_date` <= '".$to_date."' AND attendance_status IN('P','LT') $ph_in $sick_in GROUP BY attendance_status");
		$result = $query->result();
		$return_array=[];
		if($result){
			$total_rest_d=0;
			$present_r_hour=0;
			$late_r_hour=0;
			$bus_lateness_rest=0;
			foreach($result as $all_status){
				$attendance_status=$all_status->attendance_status;
				$count_attendance_status=$all_status->count_attendance_status;
				$total_rest=$all_status->total_rest;
				$overtime_day=$all_status->overtime;
				$overtime_night=$all_status->overtime_night;
				$attn_date=explode(',',$all_status->attendance_date);
				if($attendance_status=='P'){
					if($exceptional_employees=='Yes'){
						$total_rest_d=(($count_attendance_status*$shift_hours));
					}else{
						foreach($attn_date as $atn_d){
							if(in_array($atn_d,$w_of_date)){
								$present_r_hour+=$w_of_hours[$atn_d];
							}
						}
						$total_rest_d=$total_rest+$present_r_hour;
					}
				}
				else if($attendance_status=='LT'){
					if($exceptional_employees=='Yes'){
						$total_rest_d=(($count_attendance_status*$shift_hours));
					}else{
						foreach($attn_date as $atn_d){
							$bus_users=get_bus_users_late($user_id,$atn_d,$location_id);
							if($bus_users){
								$bus_lateness_rest+=$bus_users['late_rest_value'];
							}
							if(in_array($atn_d,$w_of_date)){
								$late_r_hour+=$w_of_hours[$atn_d];
							}
						}
						$total_rest_d=$total_rest+$late_r_hour+$bus_lateness_rest;
					}
				}
				$return_array[]=(object) array('attendance_status'=>$attendance_status,'count_attendance_status'=>$count_attendance_status,'total_rest'=>$total_rest_d,'overtime_day'=>$overtime_day,'overtime_night'=>$overtime_night);
			}
		}
		return  $return_array;
	}

	public function count_of_leave_status_only_ramadan($from_date,$to_date,$user_id,$department_id,$country_id,$exclude='',$ramadan_date='',$shift_hours){
		$this->load->model("Timesheet_model");
		$annual_id=$this->Timesheet_model->read_leave_type_id('Annual Leave');
		$sick_id=$this->Timesheet_model->read_leave_type_id('Sick Leave');
		$maternity_id=$this->Timesheet_model->read_leave_type_id('Maternity Leave');
		$emergency_id=$this->Timesheet_model->read_leave_type_id('Emergency Leave');
		$authorised_id=$this->Timesheet_model->read_leave_type_id('Authorised Absence');
		$start_date = new DateTime($from_date);
		$end_date = new DateTime($to_date);
		$end_date = $end_date->modify( '+1 day' );
		$interval_re = new DateInterval('P1D');
		$date_range = new DatePeriod($start_date, $interval_re ,$end_date);
		$annual_result=0;
		$annual_result1=0;
		$sick_result=0;
		$sick_result1=0;
		$maternity_result=0;
		$maternity_result1=0;
		$ph_result=0;
		$ph_result1=0;
		//$ramdan_hours=$shift_hours-$ramadan_date[0]['reduced_hours'];
		$ph_total_rest=0;
		$annual_total_rest=0;
		$sick_total_rest=0;
		$maternity_total_rest=0;
		$auth_result=0;
		$emerg_result=0;
		$auth_result1=0;
		$emerg_result1=0;
		$leave_dates='';
		$w_of_hours='';
		$w_of_date='';
		foreach($ramadan_date as $chramadan_date){
			$w_of_date[]=$chramadan_date['attendance_date'];
			$w_of_hours[$chramadan_date['attendance_date']]=$chramadan_date['reduced_hours'];
		}

		$sick_res_arr=[];
		$maternity_res_arr=[];

		foreach($date_range as $date) {
			$attendance_date = $date->format("Y-m-d");
			if(in_array($attendance_date, $w_of_date))
			{
				$annual_result1= $this->Timesheet_model->check_leave_type($attendance_date,$user_id,$annual_id,$exclude);
				if($annual_result1==1){
					$leave_dates[]=$attendance_date;
				}
				$annual_result+=$annual_result1;

				$auth_result1= $this->Timesheet_model->check_leave_type($attendance_date,$user_id,$authorised_id,$exclude);

				if($auth_result1==1){
					$leave_dates[]=$attendance_date;
				}
				$auth_result+=$auth_result1;

				$emerg_result1= $this->Timesheet_model->check_leave_type($attendance_date,$user_id,$emergency_id,$exclude);

				if($emerg_result1==1){
					$leave_dates[]=$attendance_date;
				}
				$emerg_result+=$emerg_result1;


				$ph_result1= $this->Timesheet_model->check_public_holiday_type($attendance_date,$user_id,$department_id,$country_id,$exclude);
				if($ph_result1==1){
					$leave_dates[]=$attendance_date;
				}
				$ph_result+=$ph_result1;

				$ramdan_hours=$shift_hours-$w_of_hours[$attendance_date];
				if($annual_result1!=0){$annual_total_rest+=$ramdan_hours;}
				//if($maternity_result1!=0){$maternity_total_rest+=$ramdan_hours;}
				if($ph_result1!=0){$ph_total_rest+=$ramdan_hours;}
				$sick_res_arr[]= $this->Timesheet_model->check_sick_leave_type($attendance_date,$user_id,$sick_id,$exclude,$ramdan_hours);
				$maternity_res_arr[]= $this->Timesheet_model->check_sick_leave_type($attendance_date,$user_id,$maternity_id,$exclude,$ramdan_hours);

			}
		}

		//For Annual Leave
		$annual_array=array('attendance_status'=> 'AL','count_attendance_status'=> $annual_result,'total_rest'=>$annual_total_rest);
		$annual_array_object[] = (object) $annual_array;
		$annual_res=$annual_array_object;
		//For Annual Leave

		//For Public Leave
		$public_array=array('attendance_status'=> 'PH','count_attendance_status'=> $ph_result,'total_rest'=>$ph_total_rest);
		$public_array_object[] = (object) $public_array;
		$public_res=$public_array_object;
		//For Public Leave

		//For Authorised Absence Leave
		$auth_array=array('attendance_status'=> 'AA','count_attendance_status'=> $auth_result,'total_rest'=>0);
		$auth_array_object[] = (object) $auth_array;
		$auth_res=$auth_array_object;
		//For Authorised Absence Leave

		//For Emergency Leave
		$emerg_array=array('attendance_status'=> 'EL','count_attendance_status'=> $emerg_result,'total_rest'=>0);
		$emerg_array_object[] = (object) $emerg_array;
		$emerg_res=$emerg_array_object;
		//For Emergency Leave

		$sick_array_full_object=[];
		$sick_array_half_object=[];
		$sick_array_nopaid_object=[];
		if($sick_res_arr){
			$sick_leave_st=[];
			foreach($sick_res_arr as $sick_res){
				if($sick_res){
					$leave_dates[]=$sick_res['attendance_date'];
				}
			}
		}

		if($maternity_res_arr){
			foreach($maternity_res_arr as $maternity_res){
				if($maternity_res){
					$leave_dates[]=$maternity_res['attendance_date'];
				}
			}
		}
		$all_array=$leave_dates;
		return $all_array;
	}

	public function count_of_leave_status_paid($from_date,$to_date,$user_id,$department_id,$country_id,$exclude='',$ramadan_date='',$shift_hours,$required_working_hours){
		$this->load->model("Timesheet_model");
		$annual_id=$this->Timesheet_model->read_leave_type_id('Annual Leave');
		$sick_id=$this->Timesheet_model->read_leave_type_id('Sick Leave');
		$maternity_id=$this->Timesheet_model->read_leave_type_id('Maternity Leave');
		$emergency_id=$this->Timesheet_model->read_leave_type_id('Emergency Leave');
		$authorised_id=$this->Timesheet_model->read_leave_type_id('Authorised Absence');
		$bereavement_id=$this->Timesheet_model->read_leave_type_id('Bereavement Leave');
		$start_date = new DateTime($from_date);
		$end_date = new DateTime($to_date);
		$end_date = $end_date->modify( '+1 day' );
		$interval_re = new DateInterval('P1D');
		$date_range = new DatePeriod($start_date, $interval_re ,$end_date);
		$annual_result = $annual_result1 = $bereavement_result = $bereavement_result1 = $sick_result = $sick_result1 = $maternity_result = $auth_result = $emerg_result = $auth_result1 = $emerg_result1 = $maternity_result1 = $ph_result = $ph_result1 = $ph_total_rest = $annual_total_rest = $sick_total_rest = $maternity_total_rest = $annual_total_amount = $ph_total_amount = $sick_total_amount = $maternity_total_amount = $el_total_amount = $aa_total_amount = $el_total_rest = $aa_total_rest = $bl_total_rest = $bl_total_amount = 0;
		$sick_res_arr = $maternity_res_arr=[];

		$result_annual_query=$this->check_leaves_occupied($annual_id,$user_id,$from_date,$to_date);
		$result_authorized_query=$this->check_leaves_occupied($authorised_id,$user_id,$from_date,$to_date);
		$result_emergency_query=$this->check_leaves_occupied($emergency_id,$user_id,$from_date,$to_date);
		$result_sick_query=$this->check_leaves_occupied($sick_id,$user_id,$from_date,$to_date);
		$result_maternity_query=$this->check_leaves_occupied($maternity_id,$user_id,$from_date,$to_date);
		$result_pholiday_query=$this->check_public_holiday_occupied($department_id,$country_id,$from_date,$to_date);
		$result_bereavment_query=$this->check_leaves_occupied($bereavement_id,$user_id,$from_date,$to_date);
		$rate_per_hour_contract_bonus=rate_per_hour_contract_bonus($user_id,$from_date,$to_date,$required_working_hours);
		$rate_per_hours=0;
		$rate_per_hour_count=count($rate_per_hour_contract_bonus);
		if($rate_per_hour_count==1){
			$rate_per_hours=$rate_per_hour_contract_bonus[0]['rate_per_hour'];
		}

		foreach($date_range as $date) {
			$attendance_date = $date->format("Y-m-d");

			if($result_annual_query!=''){
				$annual_result1= $this->Timesheet_model->check_leave_type_paid($attendance_date,$user_id,$annual_id,$exclude);
				$annual_result+=$annual_result1;
			}

			if($result_bereavment_query!=''){
				$bereavment_result1= $this->Timesheet_model->check_leave_type_paid($attendance_date,$user_id,$bereavement_id,$exclude);
				$bereavment_result+=$bereavment_result1;
			}
			if($result_authorized_query!=''){
				$auth_result1= $this->Timesheet_model->check_leave_type_paid($attendance_date,$user_id,$authorised_id,$exclude);
				$auth_result+=$auth_result1;
			}
			if($result_emergency_query!=''){
				$emerg_result1= $this->Timesheet_model->check_leave_type_paid($attendance_date,$user_id,$emergency_id,$exclude);
				$emerg_result+=$emerg_result1;
			}
			if($result_pholiday_query!=''){
				$ph_result1= $this->Timesheet_model->check_leave_type_paid($attendance_date,$user_id,$department_id,$country_id,$exclude);
				$ph_result+=$ph_result1;
			}

			if($rate_per_hour_count==1){
				$salary_amount=$rate_per_hours*$shift_hours;
				if($annual_result1!=0){$annual_total_rest+=$shift_hours;
					$annual_total_amount+=$salary_amount;}

				if($bereavment_result1!=0){$bl_total_rest+=$shift_hours;
					$bl_total_amount+=$salary_amount;}

				if($ph_result1!=0){$ph_total_rest+=$shift_hours;
					$ph_total_amount+=$salary_amount;}

				if($auth_result1!=0){$aa_total_rest+=$shift_hours;
					$aa_total_amount+=$salary_amount;
				}
				if($emerg_result1!=0){$el_total_rest+=$shift_hours;
					$el_total_amount+=$salary_amount;
				}

				if($result_sick_query!=''){
					$sick_res_arr[]= $this->Timesheet_model->check_sick_leave_type_paid($attendance_date,$user_id,$sick_id,$exclude,$shift_hours,$rate_per_hours);	}

				if($result_maternity_query!=''){
					$maternity_res_arr[]= $this->Timesheet_model->check_sick_leave_type_paid($attendance_date,$user_id,$maternity_id,$exclude,$shift_hours,$rate_per_hours);
				}
			}else{

				$rate_per_hours1=get_rate_per_hours($attendance_date,$rate_per_hour_contract_bonus);
				$salary_amount=$rate_per_hours1*$shift_hours;
				if($annual_result1!=0){$annual_total_rest+=$shift_hours;
					$annual_total_amount+=$salary_amount;}
				if($bereavment_result1!=0){$bl_total_rest+=$shift_hours;
					$bl_total_amount+=$salary_amount;}
				if($ph_result1!=0){$ph_total_rest+=$shift_hours;
					$ph_total_amount+=$salary_amount;}
				if($auth_result1!=0){$aa_total_rest+=$shift_hours;
					$aa_total_amount+=$salary_amount;
				}
				if($emerg_result1!=0){$el_total_rest+=$shift_hours;
					$el_total_amount+=$salary_amount;
				}

				if($result_sick_query!=''){
					$sick_res_arr[]= $this->Timesheet_model->check_sick_leave_type_paid($attendance_date,$user_id,$sick_id,$exclude,$shift_hours,$rate_per_hours1);	}

				if($result_maternity_query!=''){
					$maternity_res_arr[]= $this->Timesheet_model->check_sick_leave_type_paid($attendance_date,$user_id,$maternity_id,$exclude,$shift_hours,$rate_per_hours1);
				}

			}

		}

		//For Annual Leave
		$annual_array=array('attendance_status'=> 'AL','count_attendance_status'=> $annual_result,'total_rest'=>$annual_total_rest,'salary_count'=>$annual_total_amount);
		$annual_array_object[] = (object) $annual_array;
		$annual_res=$annual_array_object;
		//For Annual Leave

		//For Authorised Absence Leave
		$auth_array=array('attendance_status'=> 'AA','count_attendance_status'=> $auth_result,'total_rest'=>$aa_total_rest,'salary_count'=>$aa_total_amount);
		$auth_array_object[] = (object) $auth_array;
		$auth_res=$auth_array_object;
		//For Authorised Absence Leave

		//For Emergency Leave
		$emerg_array=array('attendance_status'=> 'EL','count_attendance_status'=> $emerg_result,'total_rest'=>$el_total_rest,'salary_count'=>$el_total_amount);
		$emerg_array_object[] = (object) $emerg_array;
		$emerg_res=$emerg_array_object;
		//For Emergency Leave

		//For Bereavment Leave
		$bereavment_array=array('attendance_status'=> 'BL','count_attendance_status'=> $bereavment_result,'total_rest'=>$bl_total_rest,'salary_count'=>$bl_total_amount);
		$bereavment_array_object[] = (object) $bereavment_array;
		$bereavment_res=$bereavment_array_object;
		//For Bereavment Leave


		//For Public Leave
		$public_array=array('attendance_status'=> 'PH','count_attendance_status'=> $ph_result,'total_rest'=>$ph_total_rest,'salary_count'=>$ph_total_amount);
		$public_array_object[] = (object) $public_array;
		$public_res=$public_array_object;
		//For Public Leave

		$sick_array_full_object=[];
		$sick_array_half_object=[];
		$sick_array_nopaid_object=[];
		if($sick_res_arr){
			$sick_leave_st=[];
			$sick_leave_amount=[];
			foreach($sick_res_arr as $sick_res){
				$sick_leave_st[$sick_res['attendance_status']][]=$sick_res['total_hours'];
				$sick_leave_amount[$sick_res['attendance_status']][]=$sick_res['salary_amount'];
			}

			if(@$sick_leave_st['SL-1']){
				$sick_array_full=array('attendance_status'=> 'SL-1','count_attendance_status'=> count($sick_leave_st['SL-1']),'total_rest'=>array_sum($sick_leave_st['SL-1']),'salary_count'=>array_sum($sick_leave_amount['SL-1']));
				$sick_array_full_object[] = (object) $sick_array_full;
			}

			if(@$sick_leave_st['SL-2']){
				$sick_array_half=array('attendance_status'=> 'SL-2','count_attendance_status'=> count($sick_leave_st['SL-2']),'total_rest'=>(array_sum($sick_leave_st['SL-2'])/2),'salary_count'=>array_sum($sick_leave_amount['SL-2'])/2);
				$sick_array_half_object[] = (object) $sick_array_half;
			}

			if(@$sick_leave_st['SL-UP']){
				$sick_array_nopaid=array('attendance_status'=> 'SL-UP','count_attendance_status'=> count($sick_leave_st['SL-UP']),'total_rest'=>0,'salary_count'=>0
				);
				$sick_array_nopaid_object[] = (object) $sick_array_nopaid;
			}
		}


		$maternity_array_full_object=[];
		$maternity_array_half_object=[];
		$maternity_array_nopaid_object=[];
		if($maternity_res_arr){
			$maternity_leave_st=[];
			$maternity_leave_amount=[];
			foreach($maternity_res_arr as $maternity_res){
				$maternity_leave_st[$maternity_res['attendance_status']][]=$maternity_res['total_hours'];
				$maternity_leave_amount[$maternity_res['attendance_status']][]=$maternity_res['salary_amount'];
			}
			if(@$maternity_leave_st['ML-1']){
				$maternity_array_full=array('attendance_status'=> 'ML-1','count_attendance_status'=> count($maternity_leave_st['ML-1']),'total_rest'=>array_sum($maternity_leave_st['ML-1']),'salary_count'=>array_sum($maternity_leave_amount['ML-1']));
				$maternity_array_full_object[] = (object) $maternity_array_full;
			}

			/*if(@$maternity_leave_st['ML-2']){
			$maternity_array_half=array('attendance_status'=> 'ML-2','count_attendance_status'=> count($maternity_leave_st['ML-2']),'total_rest'=>(array_sum($maternity_leave_st['ML-2'])/2),'salary_count'=>array_sum($maternity_leave_amount['ML-2'])/2);
			$maternity_array_half_object[] = (object) $maternity_array_half;
			}*/

			if(@$maternity_leave_st['ML-UP']){
				$maternity_array_nopaid=array('attendance_status'=> 'ML-UP','count_attendance_status'=> count($maternity_leave_st['ML-UP']),'total_rest'=>0,'salary_count'=>0
				);
				$maternity_array_nopaid_object[] = (object) $maternity_array_nopaid;
			}
		}
		//For Sick Leave
		$all_array=array_merge($annual_res,$sick_array_full_object,$sick_array_half_object,$sick_array_nopaid_object,$maternity_array_full_object,$maternity_array_half_object,$maternity_array_nopaid_object,$public_res,$auth_res,$emerg_res,$bereavment_res);
		return $all_array;
	}

	public function count_of_leave_status_new($from_date,$to_date,$user_id,$department_id,$country_id,$exclude='',$ramadan_date='',$shift_hours,$required_working_hours=''){
		$this->load->model("Timesheet_model");
		$annual_id=$this->Timesheet_model->read_leave_type_id('Annual Leave');
		$sick_id=$this->Timesheet_model->read_leave_type_id('Sick Leave');
		$maternity_id=$this->Timesheet_model->read_leave_type_id('Maternity Leave');
		$emergency_id=$this->Timesheet_model->read_leave_type_id('Emergency Leave');
		$authorised_id=$this->Timesheet_model->read_leave_type_id('Authorised Absence');
		$bereavement_id=$this->Timesheet_model->read_leave_type_id('Bereavement Leave');
		$start_date = new DateTime($from_date);
		$end_date = new DateTime($to_date);
		$end_date = $end_date->modify( '+1 day' );
		$interval_re = new DateInterval('P1D');
		$date_range = new DatePeriod($start_date, $interval_re ,$end_date);
		$annual_result = $annual_result1 = $bereavment_result = $bereavment_result1 = $sick_result = $sick_result1 = $maternity_result = $auth_result = $emerg_result = $auth_result1 = $emerg_result1 = $aa_total_rest = $el_total_rest = $aa_total_amount = $el_total_amount = $maternity_result1 = $ph_result = $ph_result1 = $ph_total_rest = $annual_total_rest = $annual_total_amount = $ph_total_amount = $sick_total_amount = $maternity_total_amount = $bl_total_rest = $bl_total_amount=0;
		$sick_res_arr = $maternity_res_arr=[];
		$result_annual_query=$this->check_leaves_occupied($annual_id,$user_id,$from_date,$to_date);
		$result_authorized_query=$this->check_leaves_occupied($authorised_id,$user_id,$from_date,$to_date);
		$result_emergency_query=$this->check_leaves_occupied($emergency_id,$user_id,$from_date,$to_date);
		$result_sick_query=$this->check_leaves_occupied($sick_id,$user_id,$from_date,$to_date);
		$result_maternity_query=$this->check_leaves_occupied($maternity_id,$user_id,$from_date,$to_date);
		$result_bereavment_query=$this->check_leaves_occupied($bereavement_id,$user_id,$from_date,$to_date);
		$result_pholiday_query=$this->check_public_holiday_occupied($department_id,$country_id,$from_date,$to_date);
		$rate_per_hour_contract_bonus=rate_per_hour_contract_bonus($user_id,$from_date,$to_date,$required_working_hours);
		$rate_per_hours=0;
		$rate_per_hour_count=count($rate_per_hour_contract_bonus);
		if($rate_per_hour_count==1){
			$rate_per_hours=$rate_per_hour_contract_bonus[0]['rate_per_hour'];
		}
		foreach($date_range as $date) {
			$attendance_date = $date->format("Y-m-d");
			if($result_annual_query!=''){
				$annual_result1= $this->Timesheet_model->check_leave_type_occur($attendance_date,$user_id,$annual_id,$exclude);
				$annual_result+=$annual_result1;
			}
			if($result_authorized_query!=''){
				$auth_result1= $this->Timesheet_model->check_leave_type_occur($attendance_date,$user_id,$authorised_id,$exclude);
				$auth_result+=$auth_result1;
			}
			if($result_emergency_query!=''){
				$emerg_result1= $this->Timesheet_model->check_leave_type_occur($attendance_date,$user_id,$emergency_id,$exclude);
				$emerg_result+=$emerg_result1;
			}
			if($result_bereavment_query!=''){
				$bereavment_result1= $this->Timesheet_model->check_leave_type_occur($attendance_date,$user_id,$bereavement_id,$exclude);
				$bereavment_result+=$bereavment_result1;
			}
			if($result_pholiday_query!=''){
				$ph_result1= $this->Timesheet_model->check_public_holiday_type_occur($attendance_date,$user_id,$department_id,$country_id,$exclude);
				$ph_result+=$ph_result1;
			}
			if($rate_per_hour_count==1){
				$salary_amount=$rate_per_hours*$shift_hours;
				if($annual_result1!=0){$annual_total_rest+=$shift_hours;
					$annual_total_amount+=$salary_amount;}
				if($ph_result1!=0){$ph_total_rest+=$shift_hours;
					$ph_total_amount+=$salary_amount;}
				if($auth_result1!=0){$aa_total_rest+=$shift_hours;
					$aa_total_amount+=$salary_amount;}
				if($emerg_result1!=0){$el_total_rest+=$shift_hours;
					$el_total_amount+=$salary_amount;}
				if($bereavment_result1!=0){$bl_total_rest+=$shift_hours;
					$bl_total_amount+=$salary_amount;}
				if($result_sick_query!=''){
					$sick_res_arr[]= $this->Timesheet_model->check_sick_leave_type($attendance_date,$user_id,$sick_id,$exclude,$shift_hours,$rate_per_hours);
				}
				if($result_maternity_query!=''){
					$maternity_res_arr[]= $this->Timesheet_model->check_sick_leave_type($attendance_date,$user_id,$maternity_id,$exclude,$shift_hours,$rate_per_hours);
				}
			}
			else{
				$rate_per_hours1=get_rate_per_hours($attendance_date,$rate_per_hour_contract_bonus);
				$salary_amount=$rate_per_hours1*$shift_hours;
				if($annual_result1!=0){$annual_total_rest+=$shift_hours;
					$annual_total_amount+=$salary_amount;}
				if($ph_result1!=0){$ph_total_rest+=$shift_hours;
					$ph_total_amount+=$salary_amount;}
				if($auth_result1!=0){$aa_total_rest+=$shift_hours;
					$aa_total_amount+=$salary_amount;}
				if($emerg_result1!=0){$el_total_rest+=$shift_hours;
					$el_total_amount+=$salary_amount;}
				if($bereavment_result1!=0){$bl_total_rest+=$shift_hours;
					$bl_total_amount+=$salary_amount;}
				if($result_sick_query!=''){
					$sick_res_arr[]= $this->Timesheet_model->check_sick_leave_type($attendance_date,$user_id,$sick_id,$exclude,$shift_hours,$rate_per_hours1);
				}
				if($result_maternity_query!=''){
					$maternity_res_arr[]= $this->Timesheet_model->check_sick_leave_type($attendance_date,$user_id,$maternity_id,$exclude,$shift_hours,$rate_per_hours1);
				}
			}
		}
		//For Annual Leave
		$annual_array=array('attendance_status'=> 'AL','count_attendance_status'=> $annual_result,'total_rest'=>$annual_total_rest,'salary_count'=>$annual_total_amount);
		$annual_array_object[] = (object) $annual_array;
		$annual_res=$annual_array_object;
		//For Annual Leave

		//For Authorised Absence Leave
		$auth_array=array('attendance_status'=> 'AA','count_attendance_status'=> $auth_result,'total_rest'=>$aa_total_rest,'salary_count'=>$aa_total_amount);
		$auth_array_object[] = (object) $auth_array;
		$auth_res=$auth_array_object;
		//For Authorised Absence Leave

		//For Emergency Leave
		$emerg_array=array('attendance_status'=> 'EL','count_attendance_status'=> $emerg_result,'total_rest'=>$el_total_rest,'salary_count'=>$el_total_amount);
		$emerg_array_object[] = (object) $emerg_array;
		$emerg_res=$emerg_array_object;
		//For Emergency Leave

		//For Bereavment Leave
		$bereavment_array=array('attendance_status'=> 'BL','count_attendance_status'=> $bereavment_result,'total_rest'=>$bl_total_rest,'salary_count'=>$bl_total_amount);
		$bereavment_array_object[] = (object) $bereavment_array;
		$bereavment_res=$bereavment_array_object;
		//For Bereavment Leave

		//For Public Leave
		$public_array=array('attendance_status'=> 'PH','count_attendance_status'=> $ph_result,'total_rest'=>$ph_total_rest,'salary_count'=>$ph_total_amount);
		$public_array_object[] = (object) $public_array;
		$public_res=$public_array_object;
		//For Public Leave

		$sick_array_full_object=[];
		$sick_array_half_object=[];
		$sick_array_nopaid_object=[];
		if($sick_res_arr){
			$sick_leave_st=[];
			$sick_leave_amount=[];
			foreach($sick_res_arr as $sick_res){
				$sick_leave_st[$sick_res['attendance_status']][]=$sick_res['total_hours'];
				$sick_leave_amount[$sick_res['attendance_status']][]=$sick_res['salary_amount'];
			}
			if(@$sick_leave_st['SL-1']){
				$sick_array_full=array('attendance_status'=> 'SL-1','count_attendance_status'=> count($sick_leave_st['SL-1']),'total_rest'=>array_sum($sick_leave_st['SL-1']),'salary_count'=>array_sum($sick_leave_amount['SL-1']));
				$sick_array_full_object[] = (object) $sick_array_full;
			}

			if(@$sick_leave_st['SL-2']){
				$sick_array_half=array('attendance_status'=> 'SL-2','count_attendance_status'=> count($sick_leave_st['SL-2']),'total_rest'=>(array_sum($sick_leave_st['SL-2'])/2),'salary_count'=>array_sum($sick_leave_amount['SL-2'])/2);
				$sick_array_half_object[] = (object) $sick_array_half;
			}

			if(@$sick_leave_st['SL-UP']){
				$sick_array_nopaid=array('attendance_status'=> 'SL-UP','count_attendance_status'=> count($sick_leave_st['SL-UP']),'total_rest'=>array_sum($sick_leave_st['SL-UP']),'salary_count'=>array_sum($sick_leave_amount['SL-UP'])
				);
				$sick_array_nopaid_object[] = (object) $sick_array_nopaid;
			}
		}

		$maternity_array_full_object=[];
		$maternity_array_half_object=[];
		$maternity_array_nopaid_object=[];
		if($maternity_res_arr){
			$maternity_leave_st=[];
			$maternity_leave_amount=[];
			foreach($maternity_res_arr as $maternity_res){
				$maternity_leave_st[$maternity_res['attendance_status']][]=$maternity_res['total_hours'];
				$maternity_leave_amount[$maternity_res['attendance_status']][]=$maternity_res['salary_amount'];
			}
			if(@$maternity_leave_st['ML-1']){
				$maternity_array_full=array('attendance_status'=> 'ML-1','count_attendance_status'=> count($maternity_leave_st['ML-1']),'total_rest'=>array_sum($maternity_leave_st['ML-1']),'salary_count'=>array_sum($maternity_leave_amount['ML-1']));
				$maternity_array_full_object[] = (object) $maternity_array_full;
			}

			/*if(@$maternity_leave_st['ML-2']){
			$maternity_array_half=array('attendance_status'=> 'ML-2','count_attendance_status'=> count($maternity_leave_st['ML-2']),'total_rest'=>(array_sum($maternity_leave_st['ML-2'])/2),'salary_count'=>array_sum($maternity_leave_amount['ML-2'])/2);
			$maternity_array_half_object[] = (object) $maternity_array_half;
			}*/

			if(@$maternity_leave_st['ML-UP']){
				$maternity_array_nopaid=array('attendance_status'=> 'ML-UP','count_attendance_status'=> count($maternity_leave_st['ML-UP']),'total_rest'=>array_sum($maternity_leave_st['ML-UP']),'salary_count'=>array_sum($maternity_leave_amount['ML-UP'])
				);
				$maternity_array_nopaid_object[] = (object) $maternity_array_nopaid;
			}
		}

		$all_array=array_merge($annual_res,$sick_array_full_object,$sick_array_half_object,$sick_array_nopaid_object,$maternity_array_full_object,$maternity_array_half_object,$maternity_array_nopaid_object,$public_res,$auth_res,$emerg_res,$bereavment_res);
		return $all_array;
	}

	public function count_of_leave_status($from_date,$to_date,$user_id,$department_id,$country_id,$exclude='',$ramadan_date='',$shift_hours){
		$this->load->model("Timesheet_model");
		$annual_id=$this->Timesheet_model->read_leave_type_id('Annual Leave');
		$sick_id=$this->Timesheet_model->read_leave_type_id('Sick Leave');
		$maternity_id=$this->Timesheet_model->read_leave_type_id('Maternity Leave');
		$emergency_id=$this->Timesheet_model->read_leave_type_id('Emergency Leave');
		$authorised_id=$this->Timesheet_model->read_leave_type_id('Authorised Absence');
		$bereavement_id=$this->Timesheet_model->read_leave_type_id('Bereavement Leave');
		$start_date = new DateTime($from_date);
		$end_date = new DateTime($to_date);
		$end_date = $end_date->modify('+1 day');
		$interval_re = new DateInterval('P1D');
		$date_range = new DatePeriod($start_date, $interval_re ,$end_date);
		$annual_result = $annual_result1 = $bereavment_result = $bereavment_result1 =$sick_result = $sick_result1 = $maternity_result = $auth_result = $emerg_result = $maternity_result1 = $ph_result = $ph_result1 = $ph_total_rest = $annual_total_rest = $bereavment_total_rest = 0;
		$sick_res_arr = $maternity_res_arr=[];

		$result_annual_query=$this->check_leaves_occupied($annual_id,$user_id,$from_date,$to_date);
		$result_authorized_query=$this->check_leaves_occupied($authorised_id,$user_id,$from_date,$to_date);
		$result_emergency_query=$this->check_leaves_occupied($emergency_id,$user_id,$from_date,$to_date);
		$result_sick_query=$this->check_leaves_occupied($sick_id,$user_id,$from_date,$to_date);
		$result_maternity_query=$this->check_leaves_occupied($maternity_id,$user_id,$from_date,$to_date);
		$result_bereavment_query=$this->check_leaves_occupied($bereavement_id,$user_id,$from_date,$to_date);
		$result_pholiday_query=$this->check_public_holiday_occupied($department_id,$country_id,$from_date,$to_date);

		foreach($date_range as $date) {
			$attendance_date = $date->format("Y-m-d");

			if($result_annual_query!=''){
				$annual_result1= $this->Timesheet_model->check_leave_type($attendance_date,$user_id,$annual_id,$exclude);
				$annual_result+=$annual_result1;
			}
			if($result_bereavment_query!=''){
				$bereavment_result1= $this->Timesheet_model->check_leave_type($attendance_date,$user_id,$bereavement_id,$exclude);
				$bereavment_result+=$bereavment_result1;
			}
			if($result_authorized_query!=''){
				$auth_result+= $this->Timesheet_model->check_leave_type($attendance_date,$user_id,$authorised_id,$exclude);
			}
			if($result_emergency_query!=''){
				$emerg_result+= $this->Timesheet_model->check_leave_type($attendance_date,$user_id,$emergency_id,$exclude);
			}

			if($result_pholiday_query!=''){
				$ph_result1= $this->Timesheet_model->check_public_holiday_type($attendance_date,$user_id,$department_id,$country_id,$exclude);
				$ph_result+=$ph_result1;
			}

			if($annual_result1!=0){$annual_total_rest+=$shift_hours;}
			if($bereavment_result1!=0){$bereavment_total_rest+=$shift_hours;}
			if($ph_result1!=0){$ph_total_rest+=$shift_hours;}
			if($result_sick_query!=''){
				$sick_res_arr[]= $this->Timesheet_model->check_sick_leave_type($attendance_date,$user_id,$sick_id,$exclude,$shift_hours);}
			if($result_maternity_query!=''){
				$maternity_res_arr[]= $this->Timesheet_model->check_sick_leave_type($attendance_date,$user_id,$maternity_id,$exclude,$shift_hours);
			}
		}


		//For Annual Leave
		$annual_array=array('attendance_status'=> 'AL','count_attendance_status'=> $annual_result,'total_rest'=>$annual_total_rest);
		$annual_array_object[] = (object) $annual_array;
		$annual_res=$annual_array_object;
		//For Annual Leave

		//For Authorised Absence Leave
		$auth_array=array('attendance_status'=> 'AA','count_attendance_status'=> $auth_result,'total_rest'=>0);
		$auth_array_object[] = (object) $auth_array;
		$auth_res=$auth_array_object;
		//For Authorised Absence Leave

		//For Emergency Leave
		$emerg_array=array('attendance_status'=> 'EL','count_attendance_status'=> $emerg_result,'total_rest'=>0);
		$emerg_array_object[] = (object) $emerg_array;
		$emerg_res=$emerg_array_object;
		//For Emergency Leave

		//For Bereavment Leave
		$bereavment_array=array('attendance_status'=> 'BL','count_attendance_status'=> $bereavment_result,'total_rest'=>$bereavment_total_rest);
		$bereavment_array_object[] = (object) $bereavment_array;
		$bereavment_res=$bereavment_array_object;
		//For Bereavment Leave

		//For Public Leave
		$public_array=array('attendance_status'=> 'PH','count_attendance_status'=> $ph_result,'total_rest'=>$ph_total_rest);
		$public_array_object[] = (object) $public_array;
		$public_res=$public_array_object;
		//For Public Leave

		$sick_array_full_object=[];
		$sick_array_half_object=[];
		$sick_array_nopaid_object=[];
		if($sick_res_arr){
			$sick_leave_st=[];
			foreach($sick_res_arr as $sick_res){
				$sick_leave_st[$sick_res['attendance_status']][]=$sick_res['total_hours'];
			}
			if(@$sick_leave_st['SL-1']){
				$sick_array_full=array('attendance_status'=> 'SL-1','count_attendance_status'=> count($sick_leave_st['SL-1']),'total_rest'=>array_sum($sick_leave_st['SL-1']));
				$sick_array_full_object[] = (object) $sick_array_full;
			}

			if(@$sick_leave_st['SL-2']){
				$sick_array_half=array('attendance_status'=> 'SL-2','count_attendance_status'=> count($sick_leave_st['SL-2']),'total_rest'=>(array_sum($sick_leave_st['SL-2'])/2));
				$sick_array_half_object[] = (object) $sick_array_half;
			}

			if(@$sick_leave_st['SL-UP']){
				$sick_array_nopaid=array('attendance_status'=> 'SL-UP','count_attendance_status'=> count($sick_leave_st['SL-UP']),'total_rest'=>0
				);
				$sick_array_nopaid_object[] = (object) $sick_array_nopaid;
			}
		}


		$maternity_array_full_object=[];
		$maternity_array_half_object=[];
		$maternity_array_nopaid_object=[];
		if($maternity_res_arr){
			$maternity_leave_st='';
			foreach($maternity_res_arr as $maternity_res){
				if(is_array($maternity_res['attendance_status']) && is_array($maternity_res['total_hours']))
					$maternity_leave_st[$maternity_res['attendance_status']][]=$maternity_res['total_hours'];
			}
			if(@$maternity_leave_st['ML-1']){
				$maternity_array_full=array('attendance_status'=> 'ML-1','count_attendance_status'=> count($maternity_leave_st['ML-1']),'total_rest'=>array_sum($maternity_leave_st['ML-1']));
				$maternity_array_full_object[] = (object) $maternity_array_full;
			}

			/*if(@$maternity_leave_st['ML-2']){
			$maternity_array_half=array('attendance_status'=> 'ML-2','count_attendance_status'=> count($maternity_leave_st['ML-2']),'total_rest'=>(array_sum($maternity_leave_st['ML-2'])/2));
			$maternity_array_half_object[] = (object) $maternity_array_half;
			}*/

			if(@$maternity_leave_st['ML-UP']){
				$maternity_array_nopaid=array('attendance_status'=> 'ML-UP','count_attendance_status'=> count($maternity_leave_st['ML-UP']),'total_rest'=>0
				);
				$maternity_array_nopaid_object[] = (object) $maternity_array_nopaid;
			}
		}

		//For Sick Leave
		$all_array=array_merge($annual_res,$sick_array_full_object,$sick_array_half_object,$sick_array_nopaid_object,$maternity_array_full_object,$maternity_array_half_object,$maternity_array_nopaid_object,$public_res,$auth_res,$emerg_res,$bereavment_res);
		//print_r($all_array);
		return $all_array;
	}

	public function count_of_leave_id_cal($from_date,$to_date,$calendar_end_date,$user_id,$department_id,$country_id,$exclude='',$check_ramadan_date='',$shift_hours='',$ann_query=''){
		$this->load->model("Timesheet_model");
		$annual_id=$this->Timesheet_model->read_leave_type_id('Annual Leave');
		$sick_id=$this->Timesheet_model->read_leave_type_id('Sick Leave');
		$maternity_id=$this->Timesheet_model->read_leave_type_id('Maternity Leave');
		$emergency_id=$this->Timesheet_model->read_leave_type_id('Emergency Leave');
		$authorised_id=$this->Timesheet_model->read_leave_type_id('Authorised Absence');
		$bereavement_id=$this->Timesheet_model->read_leave_type_id('Bereavement Leave');
		$annual_result = $sick_result = $maternity_result = $ph_result = $auth_result = $emerg_result = $bereavement_result=[];
		$ph_to_date=$calendar_end_date;

		$result_annual_query=$this->check_leaves_occupiedwithdates($annual_id,$user_id,$from_date,$to_date);
		$result_sick_query=$this->check_leaves_occupiedwithdates($sick_id,$user_id,$from_date,$ph_to_date);
		$result_maternity_query=$this->check_leaves_occupiedwithdates($maternity_id,$user_id,$from_date,$ph_to_date);
		$result_pholiday_query=$this->check_public_holiday_occupiedwithdate($department_id,$country_id,$from_date,$ph_to_date);
		$result_authorized_query=$this->check_leaves_occupiedwithdates($authorised_id,$user_id,$from_date,$ph_to_date);
		$result_emergency_query=$this->check_leaves_occupiedwithdates($emergency_id,$user_id,$from_date,$ph_to_date);
		$result_bereavment_query=$this->check_leaves_occupiedwithdates($bereavement_id,$user_id,$from_date,$ph_to_date);
		if($result_annual_query){
			foreach($result_annual_query as $r_annual_query){
				$start_date = new DateTime($r_annual_query['leave_start_date']);
				$end_date = new DateTime($r_annual_query['leave_end_date']);
				$end_date = $end_date->modify( '+1 day' );
				$interval_re = new DateInterval('P1D');
				$date_range = new DatePeriod($start_date,$interval_re,$end_date);
				foreach($date_range as $date) {
					$attendance_date = $date->format("Y-m-d");
					$annual_result[]= $this->Timesheet_model->check_leave_type_id($attendance_date,$user_id,$annual_id,$exclude);
				}
			}
		}
		if($result_bereavment_query){
			foreach($result_bereavment_query as $bl_annual_query){
				$start_date_bl = new DateTime($bl_annual_query['leave_start_date']);
				$end_date_bl = new DateTime($bl_annual_query['leave_end_date']);
				$end_date_bl = $end_date->modify( '+1 day' );
				$interval_bl = new DateInterval('P1D');
				$date_range_bl = new DatePeriod($start_date_bl,$interval_bl,$end_date_bl);
				foreach($date_range_bl as $date_bl) {
					$attendance_date_bl = $date_bl->format("Y-m-d");
					$bereavement_result[]= $this->Timesheet_model->check_leave_type_id($attendance_date_bl,$user_id,$bereavement_id,$exclude);
				}
			}
		}
		if($result_sick_query){
			foreach($result_sick_query as $r_sick_query){
				$start_date_s = new DateTime($r_sick_query['leave_start_date']);
				$end_date_s = new DateTime($r_sick_query['leave_end_date']);
				$end_date_s = $end_date_s->modify( '+1 day' );
				$interval_re_s = new DateInterval('P1D');
				$date_range_s = new DatePeriod($start_date_s,$interval_re_s,$end_date_s);
				foreach($date_range_s as $date_s) {
					$attendance_date_s = $date_s->format("Y-m-d");
					$sick_result[]= $this->Timesheet_model->check_leave_type_id($attendance_date_s,$user_id,$sick_id,$exclude);
				}
			}
		}
		if($result_maternity_query){
			foreach($result_maternity_query as $r_maternity_query){
				$start_date_m = new DateTime($r_maternity_query['leave_start_date']);
				$end_date_m  = new DateTime($r_maternity_query['leave_end_date']);
				$end_date_m  = $end_date_m->modify( '+1 day' );
				$interval_re_m  = new DateInterval('P1D');
				$date_range_m  = new DatePeriod($start_date_m,$interval_re_m,$end_date_m);
				foreach($date_range_m  as $date_m) {
					$attendance_date_m = $date_m->format("Y-m-d");
					$maternity_result[]= $this->Timesheet_model->check_leave_type_id($attendance_date_m,$user_id,$maternity_id,$exclude);
				}
			}
		}
		if($result_authorized_query){
			foreach($result_authorized_query as $r_authorized_query){
				$start_date_r = new DateTime($r_authorized_query['leave_start_date']);
				$end_date_r  = new DateTime($r_authorized_query['leave_end_date']);
				$end_date_r  = $end_date_r->modify( '+1 day' );
				$interval_re_r  = new DateInterval('P1D');
				$date_range_r  = new DatePeriod($start_date_r,$interval_re_r,$end_date_r);
				foreach($date_range_r  as $date_r) {
					$attendance_date_r = $date_r->format("Y-m-d");
					$auth_result[]= $this->Timesheet_model->check_leave_type_id($attendance_date_r,$user_id,$authorised_id,$exclude);
				}
			}
		}

		if($result_emergency_query){
			foreach($result_emergency_query as $r_emergency_query){
				$start_date_e = new DateTime($r_emergency_query['leave_start_date']);
				$end_date_e  = new DateTime($r_emergency_query['leave_end_date']);
				$end_date_e  = $end_date_e->modify( '+1 day' );
				$interval_re_e  = new DateInterval('P1D');
				$date_range_e  = new DatePeriod($start_date_e,$interval_re_e,$end_date_e);
				foreach($date_range_e  as $date_e) {
					$attendance_date_e = $date_e->format("Y-m-d");
					$emerg_result[]= $this->Timesheet_model->check_leave_type_id($attendance_date_e,$user_id,$emergency_id,$exclude);
				}
			}
		}

		if($result_pholiday_query){
			foreach($result_pholiday_query as $r_pholiday_query){
				$start_date_p = new DateTime($r_pholiday_query['ph_from_date']);
				$end_date_p  = new DateTime($r_pholiday_query['ph_to_date']);
				$end_date_p  = $end_date_p->modify( '+1 day' );
				$interval_re_p  = new DateInterval('P1D');
				$date_range_p  = new DatePeriod($start_date_p,$interval_re_p,$end_date_p);
				foreach($date_range_p  as $date_p) {
					$attendance_date_p = $date_p->format("Y-m-d");
					$ph_result[]= $this->Timesheet_model->check_public_holiday_type_id($attendance_date_p,$user_id,$department_id,$country_id,$exclude);
				}
			}
		}

		$annual_result=array_filter($annual_result);
		$bereavement_result=array_filter($bereavement_result);
		$maternity_result=array_filter($maternity_result);
		$sick_result=array_filter($sick_result);
		$ph_result=array_filter($ph_result);
		$emerg_result=array_filter($emerg_result);
		$auth_result=array_filter($auth_result);
		$all_array=array_merge($annual_result,$maternity_result,$sick_result,$ph_result,$auth_result,$emerg_result,$bereavement_result);
		return $all_array;
	}

	public function count_of_leave_id($from_date,$to_date,$user_id,$department_id,$country_id,$exclude='',$check_ramadan_date='',$shift_hours='',$ann_query=''){
		$this->load->model("Timesheet_model");
		$annual_id=$this->Timesheet_model->read_leave_type_id('Annual Leave');
		$sick_id=$this->Timesheet_model->read_leave_type_id('Sick Leave');
		$maternity_id=$this->Timesheet_model->read_leave_type_id('Maternity Leave');
		$emergency_id=$this->Timesheet_model->read_leave_type_id('Emergency Leave');
		$authorised_id=$this->Timesheet_model->read_leave_type_id('Authorised Absence');
		$bereavement_id=$this->Timesheet_model->read_leave_type_id('Bereavement Leave');
		$start_date = new DateTime($from_date);
		$end_date = new DateTime($to_date);
		$end_date = $end_date->modify( '+1 day' );
		$interval_re = new DateInterval('P1D');
		$date_range = new DatePeriod($start_date, $interval_re ,$end_date);
		$annual_result = $sick_result = $maternity_result = $ph_result = $auth_result = $emerg_result = $bereavement_result=[];
		$result_annual_query=$this->check_leaves_occupied($annual_id,$user_id,$from_date,$to_date);
		$result_sick_query=$this->check_leaves_occupied($sick_id,$user_id,$from_date,$to_date);
		$result_maternity_query=$this->check_leaves_occupied($maternity_id,$user_id,$from_date,$to_date);
		$result_pholiday_query=$this->check_public_holiday_occupied($department_id,$country_id,$from_date,$to_date);
		$result_authorized_query=$this->check_leaves_occupied($authorised_id,$user_id,$from_date,$to_date);
		$result_emergency_query=$this->check_leaves_occupied($emergency_id,$user_id,$from_date,$to_date);
		$result_bereavment_query=$this->check_leaves_occupied($bereavement_id,$user_id,$from_date,$to_date);
		foreach($date_range as $date) {
			$attendance_date = $date->format("Y-m-d");
			if($result_annual_query!=''){
				$annual_result[]= $this->Timesheet_model->check_leave_type_id($attendance_date,$user_id,$annual_id,$exclude);
			}
			if($result_bereavment_query!=''){
				$bereavement_result[]= $this->Timesheet_model->check_leave_type_id($attendance_date,$user_id,$bereavement_id,$exclude);
			}
			if($result_maternity_query!=''){
				$maternity_result[]= $this->Timesheet_model->check_leave_type_id($attendance_date,$user_id,$maternity_id,$exclude);
			}
			if($result_sick_query!=''){
				$sick_result[]= $this->Timesheet_model->check_leave_type_id($attendance_date,$user_id,$sick_id,$exclude);
			}
			if($result_pholiday_query!=''){
				$ph_result[]= $this->Timesheet_model->check_public_holiday_type_id($attendance_date,$user_id,$department_id,$country_id,$exclude);
			}

			if($result_authorized_query!=''){
				$auth_result[]= $this->Timesheet_model->check_leave_type_id($attendance_date,$user_id,$authorised_id,$exclude);
			}
			if($result_emergency_query!=''){
				$emerg_result[]= $this->Timesheet_model->check_leave_type_id($attendance_date,$user_id,$emergency_id,$exclude);
			}

		}

		if($ann_query=='annual_leave'){
			$all_array=array_merge(array_filter($annual_result));
		}else{
			$annual_result=array_filter($annual_result);
			$bereavement_result=array_filter($bereavement_result);
			$maternity_result=array_filter($maternity_result);
			$sick_result=array_filter($sick_result);
			$ph_result=array_filter($ph_result);
			$emerg_result=array_filter($emerg_result);
			$auth_result=array_filter($auth_result);
			$all_array=array_merge($annual_result,$maternity_result,$sick_result,$ph_result,$auth_result,$emerg_result,$bereavement_result);
		}
		//print_r($all_array);
		return $all_array;
	}

	public function count_of_ot_holiday_status($from_date,$to_date,$user_id,$department_id,$country_id){
		$start_date = new DateTime($from_date);
		$end_date = new DateTime($to_date);
		$end_date = $end_date->modify( '+1 day' );
		$interval_re = new DateInterval('P1D');
		$date_range = new DatePeriod($start_date, $interval_re ,$end_date);
		$actual_working_hours=0;
		$ot_holiday_day=0;
		$ot_holiday_night=0;
		$total_work=0;
		foreach($date_range as $date) {
			$attendance_date = $date->format("Y-m-d");
			$query=$this->db->query("SELECT a_t.total_rest as actual_working_hours,TIME_TO_SEC(replace(replace(a_t.total_work,'h ',':'),'m','')) as total_work,TIME_TO_SEC(replace(replace(a_t.overtime,'h ',':'),'m','')) as overtime,TIME_TO_SEC(replace(replace(a_t.overtime_night,'h ',':'),'m','')) as overtime_night 
			FROM `xin_attendance_time` as `a_t`
			LEFT JOIN `xin_holidays` as `h_d` ON `h_d`.`department_id`='".$department_id."' AND  `h_d`.`country_id`='".$country_id."'
			WHERE `a_t`.`employee_id` = '".$user_id."' AND `a_t`.attendance_date ='".$attendance_date."'  and `h_d`.`is_publish` = 1  and ('".$attendance_date."' BETWEEN `h_d`.`start_date` and h_d.end_date)  LIMIT 1");
			$result = $query->result();
			if($result){
				if($result[0]->actual_working_hours){
					$actual_working_hours+=$result[0]->actual_working_hours;
					$ot_holiday_day+=$result[0]->overtime;
					$ot_holiday_night+=$result[0]->overtime_night;
					$total_work+=$result[0]->total_work;
				}
			}
		}
		return json_encode(array('actual_working_hours'=>$actual_working_hours,'ot_holiday_day'=>$ot_holiday_day,'ot_holiday_night'=>$ot_holiday_night,'total_work'=>$total_work));

	}


	public function get_salaray_type($parrent){
		if($parrent=='parent'){
			$condition = "type_parent=0";
		}
		else{
			$condition ="type_parent='".$parrent."'";
		}
		$this->db->select('*');
		$this->db->from('xin_salary_types');
		$this->db->where($condition);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_salaray_type_slug($parrent,$slug){
		$condition ="type_parent!='0'";
		$this->db->select('*');
		$this->db->from('xin_salary_types');
		if($slug==1){
			$this->db->where('action',1);
		}else{
			$this->db->where('action',0);
		}
		$this->db->where($condition);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_action_type($action){
		$condition ="type_parent=0 AND type_id='".$action."'";
		$this->db->select('action');
		$this->db->from('xin_salary_types');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		$result=$query->result();
		return $result[0]->action;
	}

	public function read_make_payment_information($id) {
		$condition = "make_payment_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_make_payment');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	public function add_template($data){
		$this->db->insert('xin_salary_templates', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	public function insert_employee_salary($data){
		$this->db->insert('xin_employees_salary', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function add_monthly_payment_payslip($data){
		$query=$this->db->insert('xin_make_payment', $data);
		return  $this->db->insert_id();
	}

	public function add_payment_options($data){
		$this->db->insert('xin_payment_adjustments', $data);
		return  $this->db->insert_id();
		/*if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}*/
	}

	public function get_additional_salary($id,$type){
		/*if($type=='add'){
		$query = $this->db->query('SELECT st.type_name,pt.amount FROM `xin_salary_types` as st left join xin_payment_adjustments as pt on pt.child_type=st.type_id
		left join xin_make_payment as mp on mp.make_payment_id=pt.make_payment_id
		where action=1 and type_parent!=0 OR (pt.make_payment_id="'.$id.'" && action=1)');
				}else{
			$query = $this->db->query('SELECT st.type_name,pt.amount FROM `xin_salary_types` as st left join xin_payment_adjustments as pt on pt.child_type=st.type_id
		left join xin_make_payment as mp on mp.make_payment_id=pt.make_payment_id
		where action=0 and type_parent!=0 OR (pt.make_payment_id="'.$id.'" && action=0)');
		}	*/

		$query=$this->db->query("SELECT pt.*,s_t.type_name as 'parent_type_name',s_t1.type_name as 'child_type_name' FROM `xin_payment_adjustments` as pt left join xin_salary_types as s_t on s_t.type_id=pt.parent_type left join xin_salary_types as s_t1 on s_t1.type_id=pt.child_type WHERE pt.make_payment_id='".$id."' HAVING parent_type_name='".$type."'");
 		$result = $query->result();
		return $result;
	}

	public function remove_payment_hold($all_id,$paydate){
		$this->db->select('make_payment_id');
		$this->db->where('status', 2);
		$this->db->where('employee_id', $all_id);
		$this->db->where('payment_date', $paydate);
		$this->db->limit(1);
		$query = $this->db->get('xin_make_payment');
		$result = $query->result();
		if($result){
			$this->db->where('make_payment_id', $result[0]->make_payment_id);
			$this->db->delete('xin_make_payment');
			$this->db->where('make_payment_id', $result[0]->make_payment_id);
			$this->db->delete('xin_payment_adjustments');
		}
		$sal_date=salary_start_end_date($paydate);
		$start_date_a = new DateTime($sal_date['exact_date_start']);
		$end_date_a = new DateTime($sal_date['exact_date_end']);
		$end_date_a = $end_date_a->modify( '+1 day' );
		$interval_re_a = new DateInterval('P1D');
		$date_range_a = new DatePeriod($start_date_a, $interval_re_a ,$end_date_a);
		$this->db->where('status', 1);
		$this->db->where('adjustment_for_employee', $all_id);
		$this->db->where('end_date', $paydate.'-01');
		$this->db->where('comments', 'Top Delivery Bonus');
		$this->db->where('salary_type', 'internal_adjustments');
		$this->db->delete('xin_salary_adjustments');
		$data=array('status'=>0);
		foreach($date_range_a as $date_a) {
			$attendance_date_a = $date_a->format("Y-m-d");
			$this->db->where('adjustment_for_employee', $all_id);
			$this->db->where('end_date', $attendance_date_a);
			$this->db->where('salary_type', 'internal_adjustments');
			$this->db->update('xin_salary_adjustments',$data);
		}
	}

	public function remove_payment_leave_hold($all_id,$paydate,$start_date,$end_date){
		$this->db->select('make_payment_id');
		$this->db->where('status', 2);
		$this->db->where('employee_id', $all_id);
		$this->db->where('payment_date', $paydate);
		$this->db->limit(1);
		$query = $this->db->get('xin_make_payment');
		$result = $query->result();
		if($result){
			$this->db->where('make_payment_id', $result[0]->make_payment_id);
			$this->db->delete('xin_make_payment');
			$this->db->where('make_payment_id', $result[0]->make_payment_id);
			$this->db->delete('xin_payment_adjustments');
		}

		$start_date_a = new DateTime($start_date);
		$end_date_a = new DateTime($end_date);
		$end_date_a = $end_date_a->modify( '+1 day' );
		$interval_re_a = new DateInterval('P1D');
		$date_range_a = new DatePeriod($start_date_a, $interval_re_a ,$end_date_a);
		$data=array('status'=>0);
		foreach($date_range_a as $date_a) {
			$attendance_date_a = $date_a->format("Y-m-d");
			$this->db->where('adjustment_for_employee', $all_id);
			$this->db->where('end_date', $attendance_date_a);
			$this->db->where('salary_type', 'internal_adjustments');
			$this->db->update('xin_salary_adjustments',$data);
		}
	}

	public function leave_salary_pre_date($employee_id,$month_year){
		$this->db->select('leave_end_date,leave_start_date,leave_settlement_end_date,payment_date');
		$this->db->where('employee_id', $employee_id);
		$this->db->where('payment_date', $month_year);
		$this->db->limit(1);
		$query = $this->db->get('xin_make_payment');
		$return = $query->result();
		if($return){
			return $return;
		}else{
			return '';
		}
	}

	public function read_all_leave_settlement_list($id) {

		if(visa_wise_role_ids() != ''){

			$this->db->where('xin_employee_immigration.type',visa_wise_role_ids());
			$this->db->join('xin_employee_immigration', 'xin_employee_immigration.employee_id = mp.employee_id');
		}

		$condition = "mp.leave_settlement_start_date != '' and mp.leave_settlement_start_date !=''";
		$this->db->select('mp.*,appr.created_by');
		$this->db->where($condition);
		$this->db->from('xin_make_payment as mp');
		$this->db->join('xin_employees_approval as appr','appr.field_id=mp.make_payment_id AND mp.payment_date=appr.pay_date','left');

		$this->db->group_by('mp.payment_date');
		$this->db->group_by('mp.employee_id');
		if($id!=''){
			$this->db->where('mp.employee_id',$id);
		}
		$query = $this->db->get();
		return $query;
	}

	public function check_already_paid_or_not_not_hold($all_id,$paydate){
		$this->db->select('status');
		$this->db->where('status',2);
		$this->db->where('employee_id', $all_id);
		$this->db->where('payment_date', $paydate);
		$this->db->limit(1);
		$query = $this->db->get('xin_make_payment');
		$return = $query->result();
		if($return){
			return $return[0]->status;
		}else{
			return '';
		}
	}

	public function check_already_paid_or_not($all_id,$paydate){
		$this->db->select('make_payment_id');
		$this->db->where_in('status',array('1','2'));
		$this->db->where('employee_id', $all_id);
		$this->db->where('payment_date', $paydate);
		$this->db->limit(1);
		$query = $this->db->get('xin_make_payment');
		$return = $query->result();
		if($return){
			return $return[0]->make_payment_id;
		}else{
			return '';
		}
	}
	
	// Function to add record in table
	public function add_hourly_payment_payslip($data){
		$this->db->insert('xin_make_payment', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// Function to Delete selected record from table
	public function delete_template_record($id){
		$this->db->where('salary_template_id', $id);
		$this->db->delete('xin_salary_templates');
		$this->db->where('salary_template_id', $id);
		$this->db->delete('xin_employees_salary');
	}

	// Function to Delete selected record from table
	public function delete_hourly_wage_record($id){
		$this->db->where('hourly_rate_id', $id);
		$this->db->delete('xin_hourly_templates');
	}

	// Function to update record in table
	public function update_template_record($data, $id){
		$this->db->where('salary_template_id', $id);
		if( $this->db->update('xin_salary_templates',$data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table
	public function update_employee_salary($data,$field_id,$id,$insert_data){
		$this->db->where('salary_template_id', $id);
		$this->db->where('salary_field_id', $field_id);
		$query = $this->db->get('xin_employees_salary');
		if ($query->num_rows() == 0) {
			$this->db->insert('xin_employees_salary',$insert_data);
			return true;
		}else{
			$this->db->where('salary_template_id', $id);
			$this->db->where('salary_field_id', $field_id);
			if($this->db->update('xin_employees_salary',$data)) {
				return true;
			} else {
				return false;
			}
		}
	}

	public function template_max_id(){
		$this->db->select_max('salary_template_id');
		$this->db->limit(1);
		$query = $this->db->get('xin_salary_templates');
		return $query->row();
	}

	// get all salary tempaltes > payroll templates
	public function all_salary_templates()
	{
		$query = $this->db->query("SELECT * from xin_salary_templates");
		return $query->result();
	}

	// Function to update record in table > empty grade status
	public function update_empty_salary_template($data, $id){
		$this->db->where('user_id', $id);
		if( $this->db->update('xin_employees',$data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table > set hourly grade
	public function update_hourlygrade_salary_template($data, $id){
		$this->db->where('user_id', $id);
		if( $this->db->update('xin_employees',$data)) {
			return true;
		} else {
			return false;
		}
	}
	// Function to update record in table > set monthly grade

	// Function to update record in table > zero monthly grade
	public function update_monthlygrade_zero($data, $id){
		$this->db->where('user_id', $id);
		if( $this->db->update('xin_employees',$data)) {
			return true;
		} else {
			return false;
		}
	}

	public function read_make_payment_payslip_check($employee_id,$p_date) {
		$condition = "employee_id =" . "'" . $employee_id . "' and payment_date =" . "'" . $p_date . "'";
		$this->db->select('make_payment_id');
		$this->db->from('xin_make_payment');
		$this->db->where($condition);
		$this->db->limit(1);
		return $query = $this->db->get();
	}

	public function read_make_payment_payslip($employee_id,$p_date) {
		$condition = "employee_id =" . "'" . $employee_id . "' and payment_date =" . "'" . $p_date . "'";
		$this->db->select('*');
		$this->db->from('xin_make_payment');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	//external & internal adjustments
	public function get_adjustments($parent,$type){
		if($parent=='parent'){
			$condition = "type_parent=0";
		}
		else{
			$condition ="type_parent='".$parent."'";
		}

		if($type=='internal'){
			$this->db->where('adjustment_type',0);
		}else{
			$this->db->where('adjustment_type',1);
		}
		$this->db->select('*');
		$this->db->from('xin_salary_types');
		$this->db->where($condition);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_adjustments_name($type){
		$condition = "type_parent!=0";
		if($type=='internal'){
			$this->db->where('adjustment_type',0);
		}else{
			$this->db->where('adjustment_type',1);
		}
		$this->db->select('*');
		$this->db->from('xin_salary_types');
		$this->db->where($condition);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_employees_nameuserid($type){
		$slug='';
		if($type!=''){
			$slug=' Where user_role_id!=9';
		}
		$query = $this->db->query("SELECT user_id,first_name,middle_name,last_name from xin_employees $slug order by first_name ASC");
		return $query->result();
	}

	public function add_adjustments($data){
		$this->db->insert('xin_salary_adjustments', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function get_adjustments_list($type,$department_id,$location_id,$month_year,$adjustment_type,$adjustment_name,$visa_value){
		$slug='';
		if($location_id!=0){
			$slug.=" AND emp.office_location_id='".$location_id."'";
		}
		if($department_id!=0){
			$slug.=" AND emp.department_id='".$department_id."'";
		}

		if($adjustment_type!=0){
			$slug.=" AND s_a.adjustment_type='".$adjustment_type."'";
		}

		if($adjustment_name!=0){
			$slug.=" AND s_a.adjustment_name='".$adjustment_name."'";
		}
		$dates='';
		if($month_year!=''){
			$dates.=" AND s_a.end_date LIKE '%".$month_year."%'";
		}

		$con = '';
		$con1 = '';
		if(visa_wise_role_ids() != ''){

			$con = "INNER JOIN xin_employee_immigration ON xin_employee_immigration.employee_id = s_a.adjustment_for_employee";
			$con1 = "AND xin_employee_immigration.type = ".visa_wise_role_ids();
		}

		if($visa_value==0){
			$query = $this->db->query("SELECT s_a.*,emp.* from xin_salary_adjustments as s_a $con left join xin_employees as emp on emp.user_id=s_a.adjustment_for_employee where s_a.salary_type='".$type."' $con1 $dates $slug");
		}else{
			$query = $this->db->query("SELECT s_a.*,emp.* from xin_salary_adjustments as s_a 
			left join xin_employees as emp on emp.user_id=s_a.adjustment_for_employee 
			left join xin_employee_immigration as doc on doc.employee_id=emp.user_id AND doc.document_type_id=3
			left join xin_module_types as modul on modul.type_id=doc.type where s_a.salary_type='".$type."' $dates $slug AND modul.type_id='".$visa_value."'");
		}
		return $query;
	}

	public function read_salary_type($id) {
		$condition = "type_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_salary_types');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function read_salary_child_type_name($other_adjustment_p_type_id){
		$condition = "type_parent ='".$other_adjustment_p_type_id."' AND type_name='Others'";
		$this->db->select('*');
		$this->db->from('xin_salary_types');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function read_salary_type_name($type_name) {
		$condition = "type_name =" . "'" . $type_name . "'";
		$this->db->select('*');
		$this->db->from('xin_salary_types');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function get_adjustment_id($id){
		$this->db->select('*');
		$this->db->from('xin_salary_adjustments');
		$this->db->where('adjustment_id',$id);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	public function update_adjustments($data,$adjustment_id){
		$this->db->where('adjustment_id', $adjustment_id);
		if( $this->db->update('xin_salary_adjustments',$data)) {
			return true;
		} else {
			return false;
		}
	}

	public function adjustments_delete($id){
		$this->db->where('adjustment_id', $id);
		$this->db->delete('xin_salary_adjustments');
	}

	public function update_salary_adjustments_status($adjus,$status){
		$data=array('status'=>$status);
		$this->db->where('adjustment_id', $adjus);
		if( $this->db->update('xin_salary_adjustments',$data)) {
			return true;
		} else {
			return false;
		}
	}

	public function payment_adjustments($employee_id,$make_payment_id,$p_type,$payment_date){
		$query = $this->db->query("select p_a.*,s_t.type_name as 'parent_type_name',s_t_c.type_name 'child_type_name' from xin_payment_adjustments as p_a 
		left join xin_make_payment as m_k on m_k.make_payment_id=p_a.make_payment_id AND m_k.employee_id='".$employee_id."' 
		left join xin_salary_types as s_t on s_t.type_id=p_a.parent_type
		left join xin_salary_types as s_t_c on s_t_c.type_id=p_a.child_type
		where p_a.make_payment_id='".$make_payment_id."' and p_a.payment_date='".$payment_date."' HAVING parent_type_name='".$p_type."' order by p_a.parent_type asc");
		return $query->result();
	}

	public function find_adjustments_anystatus($employee_id,$start_dt,$end_dt,$type_of_adjustments,$p_type){
		$query = $this->db->query("SELECT s_a.*,s_t.type_name as 'parent_type_name',s_t.action as 'parent_type_action',s_t_c.type_name 'child_type_name' FROM `xin_salary_adjustments` as s_a left join xin_salary_types as s_t on s_t.type_id=s_a.adjustment_type left join xin_salary_types as s_t_c on s_t_c.type_id=s_a.adjustment_name  WHERE s_a.adjustment_for_employee='".$employee_id."' and s_a.end_date >='".$start_dt."' and s_a.end_date<='".$end_dt."' and s_a.salary_type='".$type_of_adjustments."' having parent_type_name='".$p_type."' order by s_a.adjustment_type asc");
		return $query->result();
	}

	public function find_adjustments($employee_id,$start_dt,$end_dt,$type_of_adjustments,$p_type){
		$sql = "SELECT s_a.*,s_t.type_name as 'parent_type_name',s_t.action as 'parent_type_action',s_t_c.type_name 'child_type_name' FROM `xin_salary_adjustments` as s_a left join xin_salary_types as s_t on s_t.type_id=s_a.adjustment_type left join xin_salary_types as s_t_c on s_t_c.type_id=s_a.adjustment_name  WHERE s_a.adjustment_for_employee='".$employee_id."' and s_a.end_date >='".$start_dt."' and s_a.end_date<='".$end_dt."' and s_a.status=0 and s_a.salary_type='".$type_of_adjustments."' having parent_type_name='".$p_type."' order by s_a.adjustment_type asc";
		$query = $this->db->query($sql);
		return $query->result();
	}

	public function find_ext_adjustments($employee_id,$start_dt,$end_dt,$type_of_adjustments,$p_type){
		if($p_type=='Perpetual'){
			$query = $this->db->query("SELECT s_a.*,s_t.type_name as 'parent_type_name',s_t.action as 'parent_type_action',s_t_c.type_name 'child_type_name'
				FROM `xin_salary_adjustments` as s_a left join xin_salary_types as s_t on s_t.type_id=s_a.adjustment_type left join xin_salary_types as s_t_c on s_t_c.type_id=s_a.adjustment_name INNER JOIN  (SELECT adjustment_name, MAX(adjustment_id) AS MaxId FROM xin_salary_adjustments where adjustment_for_employee='".$employee_id."' GROUP BY adjustment_name) groupedtt 
				ON s_a.adjustment_name = groupedtt.adjustment_name AND s_a.adjustment_id = groupedtt.MaxId and s_a.status=1 and s_a.salary_type='".$type_of_adjustments."' and s_a.adjustment_for_employee='".$employee_id."' HAVING parent_type_name='".$p_type."'");
		}else{
			$query = $this->db->query("SELECT s_a.*,s_t.type_name as 'parent_type_name',s_t.action as 'parent_type_action',s_t_c.type_name 'child_type_name' FROM `xin_salary_adjustments` as s_a left join xin_salary_types as s_t on s_t.type_id=s_a.adjustment_type left join xin_salary_types as s_t_c on s_t_c.type_id=s_a.adjustment_name INNER JOIN (SELECT adjustment_name, MAX(adjustment_id) AS MaxId FROM xin_salary_adjustments where adjustment_for_employee='".$employee_id."' GROUP BY adjustment_name) groupedtt ON s_a.adjustment_name = groupedtt.adjustment_name AND s_a.adjustment_id = groupedtt.MaxId and s_a.status=1 and s_a.salary_type='".$type_of_adjustments."' and s_a.adjustment_for_employee='".$employee_id."' and (s_a.end_date='' || (s_a.end_date >='".$start_dt."' && s_a.end_date<='".$end_dt."')) HAVING parent_type_name='".$p_type."'");
		}
		return $query->result();
	}

	public function check_unique_adjustments($parent_type,$child_type,$empoloyee_id,$id){
		if($id==''){
			$condition = "adjustment_type =" . "'" . $parent_type . "' AND adjustment_name =" . "'" . $child_type . "' AND adjustment_for_employee =" . "'" . $empoloyee_id . "' AND adjustment_type!=52";
		}else{
			$condition = "adjustment_type =" . "'" . $parent_type . "' AND adjustment_name =" . "'" . $child_type . "' AND adjustment_for_employee =" . "'" . $empoloyee_id . "' AND adjustment_id !=" . "'" . $id . "'  AND adjustment_type!=52";
		}
		$this->db->select('adjustment_id');
		$this->db->from('xin_salary_adjustments');
		$this->db->where($condition);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function check_any_increments($user_id,$calendar_start_date,$calendar_end_date){
		$query = $this->db->query("SELECT salary_template_id,employee_id,effective_from_date,effective_to_date,salary_based_on_contract,salary_with_bonus FROM `xin_salary_templates` WHERE `employee_id` = '".$user_id."' AND is_approved=1 AND unix_timestamp(effective_from_date) BETWEEN '".strtotime($calendar_start_date)."' AND '".strtotime($calendar_end_date)."'  order by effective_from_date asc");
		$result = $query->result();
		return $result;
	}

}
?>
