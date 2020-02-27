<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class timesheet_model extends CI_Model{
	
	public function __construct(){
		parent::__construct();
		$this->load->database();
	}

	public function get_office_default_shifts(){
		return $this->db->get("xin_office_default_shift");
	}

	// get all tasks
	public function get_tasks() {
		return $this->db->get("xin_tasks");
	}

	// check if check-in available
	public function attendance_first_in_check($employee_id,$attendance_date) {
		$condition = "employee_id =" . "'" . $employee_id . "' and attendance_date =" . "'" . $attendance_date . "'";
		$this->db->select('*');
		$this->db->from('xin_attendance_time');
		$this->db->where($condition);
		$this->db->limit(1);
		return $query = $this->db->get();
	}

	// get user attendance
	public function attendance_time_check($employee_id) {
		$condition = "employee_id =" . "'" . $employee_id . "'";
		$this->db->select('*');
		$this->db->from('xin_attendance_time');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	// check if check-in available
	public function attendance_first_in($employee_id,$attendance_date) {
		$condition = array('employee_id' => $employee_id, 'attendance_date' => $attendance_date);
		$this->db->select('*');
		$this->db->from('xin_attendance_time');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_bus_user_lates($user_id,$from_date,$to_date){
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
		$result_sick_query=$this->Payroll_model->check_leaves_occupied($sick_id,$user_id,$exact_date_start,$exact_date_end);
		if($result_sick_query!=''){
			foreach($date_range as $date){
				$attendance_date = $date->format("Y-m-d");
				if($result_sick_query!=''){
					$sick_res_arr[]= $this->check_sick_leave_type_id($attendance_date,$user_id,$sick_id,'');
				}
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

		if($sick_d){
			$sick_in="and attendance_date NOT IN (".implode(',', array_map('quote', $sick_d)).")";
		}else{
			$sick_in='';
		}
		$query = $this->db->query("SELECT *,TIME_TO_SEC(replace(replace(time_late,'h ',':'),'m','')) as late_count FROM `xin_attendance_time` 
								   WHERE `employee_id`= '".$user_id."' AND `attendance_date` >= '".$from_date."' AND `attendance_date` <= '".$to_date."' AND attendance_status ='LT' $sick_in order by attendance_date");
		return $result = $query->result();
	}

	public function add_bus_lateness($data){
		$this->db->insert('xin_bus_late_timings', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		else {
			return false;
		}
	}

	public function attendance_details($employee_id,$attendance_date) {
		$condition = array('employee_id' => $employee_id, 'attendance_date' => $attendance_date);
		$this->db->select('*');
		$this->db->from('xin_attendance_time');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();

		return $query->result();
	}

	// check if check-out available
	public function attendance_first_out_check($employee_id,$attendance_date) {
		$this->db->order_by("time_attendance_id","desc");
		$condition = "employee_id =" . "'" . $employee_id . "' and attendance_date =" . "'" . $attendance_date . "'";
		$this->db->select('*');
		$this->db->from('xin_attendance_time');
		$this->db->where($condition);
		$this->db->limit(1);
		return $query = $this->db->get();
	}

	public function bus_lateness_list(){
		$this->db->order_by("bus.added_date","desc");
		$this->db->select('bus.*,loc.location_name,emp.first_name,emp.middle_name,emp.last_name');
		$this->db->from('xin_bus_late_timings as bus');
		$this->db->join('xin_office_location as loc',"loc.location_id=bus.location_id",'left');
		$this->db->join('xin_employees as emp',"emp.user_id=bus.added_by",'left');
		$result=$query = $this->db->get();
		return $result->result();
	}

	public function read_bus_lateness($bus_late_id){
		$this->db->select('*');
		$this->db->from('xin_bus_late_timings');
		$this->db->where('bus_late_id',$bus_late_id);
		$this->db->limit(1);
		$result= $this->db->get();
		return $result->result();
	}

	// get leave types
	public function all_leave_types() {
		$this->db->where("type_of_module","leave_type");
		$query = $this->db->get("xin_module_types");
		return $query->result();
	}

	// check if check-out available
	public function attendance_first_out($employee_id,$attendance_date) {
		$this->db->order_by("time_attendance_id","desc");
		$condition = array('employee_id' => $employee_id, 'attendance_date' => $attendance_date);
		$this->db->select('*');
		$this->db->from('xin_attendance_time');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	// get total hours work > attendance
	public function total_hours_worked_attendance($id,$attendance_date) {
		return $query = $this->db->query("SELECT * from xin_attendance_time where employee_id='".$id."' and attendance_date='".$attendance_date."' and total_work!=''");
	}

	// get total rest > attendance
	public function total_rest_attendance($id,$attendance_date) {
		return $query = $this->db->query("SELECT * from xin_attendance_time where employee_id='".$id."' and attendance_date='".$attendance_date."' and total_rest!=''");
	}

	// check if holiday available
	public function holiday_date_check($attendance_date) {
		$condition = "(start_date between start_date and end_date) or (start_date = '".$attendance_date."' or end_date = '".$attendance_date."')";
		$this->db->select('*');
		$this->db->from('xin_holidays');
		$this->db->where($condition);
		$this->db->limit(1);
		return $query = $this->db->get();
	}

	public function get_conversion_lists($user_id,$user_role_name) {
		$r_m_role=R_M_ROLE;
		$HR_L_ROLE = unserialize(HR_L_ROLE);
		$columns='lv.*';
		if($user_role_name == AD_ROLE) {
			return $this->db->get("xin_leave_conversion_count");
		}
		else if($user_role_name == R_M_ROLE || reporting_manager_access()) {
			$query = $this->db->query("SELECT $columns from xin_employees as emp join xin_leave_conversion_count as lv on lv.employee_id=emp.user_id where (emp.reporting_manager='".$user_id."')");
			return $query;
		}else if(($user_role_name != R_M_ROLE || $user_role_name != AD_ROLE) && (in_array($user_role_name,$HR_L_ROLE)) || (in_array('32m',role_resource_ids()))){
			return $this->db->get("xin_leave_conversion_count");
		}
		else{
			$this->db->where('employee_id',$user_id);
			return $this->db->get("xin_leave_conversion_count");
		}
	}

	public function get_leaves_by_filter($user_id,$user_role_name,$filter = [],$calendar_list='') {
		$head_id=get_ceo_only();
		$r_m_role=R_M_ROLE;
		$HR_L_ROLE = unserialize(HR_L_ROLE);

		$columns='lv.*';
		if($user_role_name == AD_ROLE) {
			if($filter['department_value']!=0)
				$this->db->where('emp.department_id',$filter['department_value']);
			if($filter['location_value']!=0)
				$this->db->where('emp.office_location_id',$filter['location_value']);
			if($filter['leave_type_value']!=0)
				$this->db->where('lv.leave_type_id',$filter['leave_type_value']);
			if($filter['status_value']!=0)
				$this->db->where('lv.status',$filter['status_value']);
			if($filter['date_from'] != '')
				$this->db->where('lv.from_date >=',$filter['date_from']);
			if($filter['date_to'] != '')
				$this->db->where('lv.to_date <=',$filter['date_to']);


			if($filter['visa_value'] != 0){
				$this->db->where('emi.type',$filter['visa_value']);
				$this->db->join('xin_employee_immigration as emi','emi.employee_id = lv.employee_id');
			}

			$this->db->select('lv.*');
			$this->db->from("xin_leave_applications as lv");
			$this->db->join("xin_employees as emp",'emp.user_id=lv.employee_id','left');
			$this->db->group_by('lv.applied_on');
			$this->db->order_by('lv.created_at','desc');
			return $this->db->get();
		}
		else if(reporting_manager_access() && (!in_array('32m',role_resource_ids())) || visa_wise_role_ids() != ''){

			$con = '';
			$con1 = '';
			if(visa_wise_role_ids() != ''){

				$con = "INNER JOIN xin_employee_immigration ON xin_employee_immigration.employee_id = emp.user_id";
				$con1 = "AND xin_employee_immigration.type = ".visa_wise_role_ids();
			}

			if($calendar_list){

				$calendar_con = 'AND lv.status = 1';
			}

			if($head_id!=$user_id){

				$query = $this->db->query("SELECT $columns from xin_employees as emp $con join xin_leave_applications as lv on lv.employee_id=emp.user_id 
		  left join xin_module_types as types on types.type_id=lv.leave_type_id
		  where emp.reporting_manager='".$user_id."' $con1 AND types.type_name!='Sick Leave' ".$calendar_con." group by lv.applied_on UNION ALL SELECT lv.* from xin_leave_applications as lv join xin_employees_approval appr on appr.field_id=lv.applied_on AND appr.employee_id=lv.employee_id where appr.approval_head_id='".$user_id."' AND appr.head_of_approval='HOD' group by lv.applied_on order by created_at DESC");
			}else{


			if($calendar_list){

				$calendar_con = 'AND lv.status = 1';
			}

			$con = '';
			$con1 = '';
			if(visa_wise_role_ids() != ''){

				$con = "INNER JOIN xin_employee_immigration ON xin_employee_immigration.employee_id = emp.user_id";
				$con1 = "AND xin_employee_immigration.type = ".visa_wise_role_ids();
			}

				$query = $this->db->query("SELECT $columns from xin_employees as emp join xin_leave_applications as lv on lv.employee_id=emp.user_id 
			left join xin_module_types as types on types.type_id=lv.leave_type_id
			where emp.reporting_manager='".$user_id."' AND types.type_name!='Sick Leave' ".$calendar_con." group by lv.applied_on UNION ALL SELECT lv.* from xin_leave_applications as lv 
			left join xin_employees_approval appr on appr.field_id=lv.applied_on where appr.approval_head_id='".$user_id."' AND appr.head_of_approval='CEO' AND (SELECT IF(EXISTS(SELECT * FROM xin_employees_approval
             WHERE `applied_on` =  lv.applied_on AND head_of_approval = 'HOD' AND approval_status = 2), 1, 1)) AND (SELECT IF( EXISTS(
             SELECT * FROM xin_employees_approval WHERE `applied_on` =  lv.applied_on AND head_of_approval = 'Reporting Manager' AND approval_status = 2), 1, 1)) group by lv.applied_on order by created_at DESC");
			}
			return $query;
		}else if(hod_manager_access()){

			if($calendar_list){

				$calendar_con = 'AND lv.status = 1';
			}

			$query = $this->db->query("SELECT $columns from xin_employees as emp join xin_leave_applications as lv on lv.employee_id=emp.user_id 
		  left join xin_module_types as types on types.type_id=lv.leave_type_id
		  where emp.reporting_manager='".$user_id."' AND types.type_name!='Sick Leave' ".$calendar_con." group by lv.applied_on UNION ALL SELECT lv.* from xin_leave_applications as lv join xin_employees_approval appr on appr.field_id=lv.applied_on AND appr.employee_id=lv.employee_id where appr.approval_head_id='".$user_id."' AND appr.head_of_approval='HOD' group by lv.applied_on order by created_at DESC");
			return $query;
		}else if(get_ceo_only()){

			if($calendar_list){

				$calendar_con = 'AND lv.status = 1';
			}

			$query = $this->db->query("SELECT $columns from xin_employees as emp join xin_leave_applications as lv on lv.employee_id=emp.user_id 
			left join xin_module_types as types on types.type_id=lv.leave_type_id
			where emp.reporting_manager='".$user_id."' AND types.type_name!='Sick Leave' ".$calendar_con." group by lv.applied_on UNION ALL SELECT lv.* from xin_leave_applications as lv 
				left join xin_employees_approval appr on appr.field_id=lv.applied_on where appr.approval_head_id='".$user_id."' AND appr.head_of_approval='CEO' AND (SELECT IF(EXISTS(SELECT * FROM xin_employees_approval
             WHERE `applied_on` =  lv.applied_on AND head_of_approval = 'HOD' AND approval_status = 2), 1, 1)) AND (SELECT IF( EXISTS(
             SELECT * FROM xin_employees_approval WHERE `applied_on` =  lv.applied_on AND head_of_approval = 'Reporting Manager' AND approval_status = 2), 1, 1)) group by lv.applied_on order by created_at DESC");

			return $query;
		}
		else if(($user_role_name != R_M_ROLE || $user_role_name != AD_ROLE) && (in_array($user_role_name,$HR_L_ROLE)) || (in_array('32m',role_resource_ids()))){
			if($filter['department_value']!=0)
				$this->db->where('emp.department_id',$filter['department_value']);
			if($filter['location_value']!=0)
				$this->db->where('emp.office_location_id',$filter['location_value']);
			if($filter['leave_type_value']!=0)
				$this->db->where('lv.leave_type_id',$filter['leave_type_value']);
			if($filter['status_value']!=0)
				$this->db->where('lv.status',$filter['status_value']);
			if($filter['date_from'] != '')
				$this->db->where('lv.from_date >=',$filter['date_from']);
			if($filter['date_to'] != '')
				$this->db->where('lv.to_date <=',$filter['date_to']);

			if($filter['visa_value'] != 0){
				$this->db->where('emi.type',$filter['visa_value']);
				$this->db->join('xin_employee_immigration as emi','emi.employee_id = lv.employee_id');
			}

			$this->db->select('lv.*');
			$this->db->from("xin_leave_applications as lv");
			$this->db->join("xin_employees as emp",'emp.user_id=lv.employee_id','left');
			$this->db->group_by('lv.applied_on');
			$this->db->order_by('lv.created_at','desc');
			return $this->db->get();
		}
		else if($user_role_name == 'Drivers Admin'){
			$this->db->where('des.designation_name like "%driver%"');
			$this->db->select('lv.*');
			$this->db->from("xin_leave_applications as lv");
			$this->db->join("xin_employees as emp",'emp.user_id=lv.employee_id','left');
			$this->db->join("xin_designations as des",'des.designation_id=emp.designation_id','left');
			$this->db->group_by('lv.applied_on');
			$this->db->order_by('lv.created_at','desc');
			return $this->db->get();
		}
		else{
			if(in_array('32v',role_resource_ids())){
				$this->db->where('employee_id!='.$user_id);
			}
			else{
				$this->db->where('employee_id',$user_id);
			}

			$this->db->group_by('applied_on');
			$this->db->order_by('created_at','desc');
			return $this->db->get("xin_leave_applications");
		}
	}

	// get all leaves
	public function get_leaves($user_id,$user_role_name,$department_value,$location_value) {
		$head_id=get_ceo_only();
		$r_m_role=R_M_ROLE;
		$HR_L_ROLE = unserialize(HR_L_ROLE);
		$columns='lv.*';
		if($user_role_name == AD_ROLE) {
			if($department_value!=0){$this->db->where('emp.department_id',$department_value);}
			if($location_value!=0){$this->db->where('emp.office_location_id',$location_value);}
			$this->db->select('lv.*');
			$this->db->from("xin_leave_applications as lv");
			$this->db->join("xin_employees as emp",'emp.user_id=lv.employee_id','left');
			$this->db->group_by('lv.applied_on');
			$this->db->order_by('lv.created_at','desc');
			return $this->db->get();
		}
		else if(reporting_manager_access() && (!in_array('32m',role_resource_ids()))){
			if($head_id!=$user_id){
				$query = $this->db->query("SELECT $columns from xin_employees as emp join xin_leave_applications as lv on lv.employee_id=emp.user_id 
		  left join xin_module_types as types on types.type_id=lv.leave_type_id
		  where emp.reporting_manager='".$user_id."' AND types.type_name!='Sick Leave' group by lv.applied_on UNION ALL SELECT lv.* from xin_leave_applications as lv join xin_employees_approval appr on appr.field_id=lv.applied_on AND appr.employee_id=lv.employee_id where appr.approval_head_id='".$user_id."' AND appr.head_of_approval='HOD' group by lv.applied_on order by created_at DESC");
			}else{
				$query = $this->db->query("SELECT $columns from xin_employees as emp join xin_leave_applications as lv on lv.employee_id=emp.user_id 
			left join xin_module_types as types on types.type_id=lv.leave_type_id
			where emp.reporting_manager='".$user_id."' AND types.type_name!='Sick Leave' group by lv.applied_on UNION ALL SELECT lv.* from xin_leave_applications as lv 
			left join xin_employees_approval appr on appr.field_id=lv.applied_on where appr.approval_head_id='".$user_id."' AND appr.head_of_approval='CEO' AND (SELECT IF(EXISTS(SELECT * FROM xin_employees_approval
             WHERE `applied_on` =  lv.applied_on AND head_of_approval = 'HOD' AND approval_status = 2), 1, 1)) AND (SELECT IF( EXISTS(
             SELECT * FROM xin_employees_approval WHERE `applied_on` =  lv.applied_on AND head_of_approval = 'Reporting Manager' AND approval_status = 2), 1, 1)) group by lv.applied_on order by created_at DESC");
			}
			return $query;
		}else if(hod_manager_access()){
			$query = $this->db->query("SELECT $columns from xin_employees as emp join xin_leave_applications as lv on lv.employee_id=emp.user_id 
		  left join xin_module_types as types on types.type_id=lv.leave_type_id
		  where emp.reporting_manager='".$user_id."' AND types.type_name!='Sick Leave' group by lv.applied_on UNION ALL SELECT lv.* from xin_leave_applications as lv join xin_employees_approval appr on appr.field_id=lv.applied_on AND appr.employee_id=lv.employee_id where appr.approval_head_id='".$user_id."' AND appr.head_of_approval='HOD' group by lv.applied_on order by created_at DESC");
			return $query;
		}else if(get_ceo_only()){
			$query = $this->db->query("SELECT $columns from xin_employees as emp join xin_leave_applications as lv on lv.employee_id=emp.user_id 
			left join xin_module_types as types on types.type_id=lv.leave_type_id
			where emp.reporting_manager='".$user_id."' AND types.type_name!='Sick Leave' group by lv.applied_on UNION ALL SELECT lv.* from xin_leave_applications as lv 
			left join xin_employees_approval appr on appr.field_id=lv.applied_on where appr.approval_head_id='".$user_id."' AND appr.head_of_approval='CEO' AND (SELECT IF(EXISTS(SELECT * FROM xin_employees_approval
             WHERE `applied_on` =  lv.applied_on AND head_of_approval = 'HOD' AND approval_status = 2), 1, 1)) AND (SELECT IF( EXISTS(
             SELECT * FROM xin_employees_approval WHERE `applied_on` =  lv.applied_on AND head_of_approval = 'Reporting Manager' AND approval_status = 2), 1, 1)) group by lv.applied_on order by created_at DESC");

			return $query;
		}
		else if(($user_role_name != R_M_ROLE || $user_role_name != AD_ROLE) && (in_array($user_role_name,$HR_L_ROLE)) || (in_array('32m',role_resource_ids()))){
			/*$query = $this->db->query("SELECT lv.*,types.type_name as leave_name from xin_leave_applications as lv
            left join xin_employees as emp on emp.user_id=lv.employee_id
				left join xin_module_types as types on types.type_id=lv.leave_type_id
				group by lv.applied_on  order by created_at DESC");*/
			//where (CASE WHEN types.type_name!='Annual Leave' && types.type_name!='Sick Leave' THEN reporting_manager_status=2 ELSE 1 END)
			if($department_value!=0){$this->db->where('emp.department_id',$department_value);}
			if($location_value!=0){$this->db->where('emp.office_location_id',$location_value);}
			$this->db->select('lv.*');
			$this->db->from("xin_leave_applications as lv");
			$this->db->join("xin_employees as emp",'emp.user_id=lv.employee_id','left');
			$this->db->group_by('lv.applied_on');
			$this->db->order_by('lv.created_at','desc');
			return $this->db->get();
		}
		else if($user_role_name == 'Drivers Admin'){
			$this->db->where('des.designation_name like "%driver%"');
			$this->db->select('lv.*');
			$this->db->from("xin_leave_applications as lv");
			$this->db->join("xin_employees as emp",'emp.user_id=lv.employee_id','left');
			$this->db->join("xin_designations as des",'des.designation_id=emp.designation_id','left');
			$this->db->group_by('lv.applied_on');
			$this->db->order_by('lv.created_at','desc');
			return $this->db->get();
		}
		else{
			if(in_array('32v',role_resource_ids())){
				$this->db->where('employee_id!='.$user_id);
			}
			else{
				$this->db->where('employee_id',$user_id);
			}

			$this->db->group_by('applied_on');
			$this->db->order_by('created_at','desc');
			return $this->db->get("xin_leave_applications");
		}
	}

	public function check_if_any_secondary_leave($leave_id,$employee_id,$applied_on){
		$condition = "leave_id != '".$leave_id."' AND employee_id = '".$employee_id."' AND applied_on = '".$applied_on."'";
		$this->db->select('*');
		$this->db->from('xin_leave_applications');
		$this->db->where($condition);
		//$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	// get all employee leaves
	public function get_employee_leaves($id) {
		return $query = $this->db->query("SELECT * from xin_leave_applications where employee_id='".$id."' order by from_date desc");
	}

	// check if holiday available
	public function holiday_date($attendance_date) {
		$condition = "(start_date between start_date and end_date) or (start_date = '".$attendance_date."' or end_date = '".$attendance_date."')";
		$this->db->select('*');
		$this->db->from('xin_holidays');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	// get all holidays
	public function get_ramadan_schedule() {
		return $query = $this->db->query("SELECT ram.*,country.country_name FROM `xin_ramadan_schedule` as ram left join xin_countries as country on country.country_id=ram.country_id  ORDER BY ram.created_at DESC");
	}

	public function get_holidays() {
		return $query = $this->db->query("SELECT hol.*,country.country_name FROM `xin_holidays` as hol left join xin_countries as country on country.country_id=hol.country_id GROUP BY hol.unique_id ORDER BY hol.created_at DESC");
	}

	// check if leave available
	public function leave_date_check($emp_id,$attendance_date) {
		$condition = "(from_date between from_date and to_date) and employee_id = '".$emp_id."' or from_date = '".$attendance_date."' and to_date = '".$attendance_date."'";
		$this->db->select('*');
		$this->db->from('xin_leave_applications');
		$this->db->where($condition);
		$this->db->limit(1);
		return $query = $this->db->get();
	}

	// check if leave available
	public function leave_date($emp_id,$attendance_date) {
		$condition = "(from_date between from_date and to_date) and employee_id = '".$emp_id."' or from_date = '".$attendance_date."' and to_date = '".$attendance_date."'";
		$this->db->select('*');
		$this->db->from('xin_leave_applications');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	// get total number of leave > employee
	public function count_total_leaves($leave_type_id,$employee_id,$all_leave_types) {
		$date_of_joining=$this->check_date_of_joining($employee_id);
		$sick_leave_start_date=date('Y-m-d',strtotime(JOINEE_SICK_LEAVE_NOT_ALLOW,strtotime($date_of_joining)));
		$maternity_leave_start_date=date('Y-m-d',strtotime(MATERNITY_LEAVE_ALLOW,strtotime($date_of_joining)));
		//$contract_dates=$this->get_contract_dates($employee_id);
		$current_date=TODAY_DATE;
		if($date_of_joining!=''){
			if($all_leave_types[0]->type_name=='Sick Leave'){
				$contract_start_date=$sick_leave_start_date;
			}else if($all_leave_types[0]->type_name=='MATERNITY Leave'){
				$contract_start_date=$maternity_leave_start_date;
			}else{
				$contract_start_date=format_date('Y-m-d',$date_of_joining);
			}
			$contract_end_date=format_date('Y-m-d',$current_date);
			$begin = new DateTime($contract_start_date);
			$end   = new DateTime($contract_end_date);
			$end_date='';
			for($i = $begin; $i <= $end;){
				$year_start_date=$i->format("Y-m-d");
				$year_end_date=$i->modify('+1 year');
				$year_end_date=date('Y-m-d', strtotime("-1 days",strtotime($year_end_date->format("Y-m-d"))));

				$due_dates[$year_start_date] = $year_end_date;
			}

			$data=[];

			if(@$due_dates){
				$available_leave=0;
				foreach($due_dates as $start_y_d=>$end_y_d){
					$start_date=$start_y_d;
					$end_date=$end_y_d;
					if(strtotime($current_date) > strtotime($start_date)){
						$gender_type=$this->find_gender($employee_id);
						$check_maternity_id='';
						if(@$gender_type=='Male'){
							$check_maternity_id='Maternity Leave';
						}
						foreach($all_leave_types as $types){
							$counts=0;
							if(@$check_maternity_id!=$types->type_name){

								$query = $this->db->query("SELECT from_date,to_date from xin_leave_applications where employee_id = '".$employee_id."' and leave_type_id='".$types->type_id."' and reporting_manager_status=2 and status=2 and 
						((from_date BETWEEN '".$start_date."' AND '".$end_date."') OR 
						 (to_date BETWEEN '".$start_date."' AND '".$end_date."') OR (from_date <= '".$start_date."' AND to_date >= '".$end_date."'))");


								$result=$query->result();
								foreach($result as $res){

									if($types->type_name=='Annual Leave'){
										if(strtotime($start_date) <= strtotime($res->from_date)){
											$start = new DateTime($res->from_date);
										}else{
											$start = new DateTime($start_date);
										}


										if(strtotime($end_date) >= strtotime($res->to_date)){
											$end = new DateTime($res->to_date);
										}else{
											$end = new DateTime($end_date);
										}
									}else{
										$start = new DateTime($res->from_date);
										$end = new DateTime($res->to_date);
									}
									$days = $start->diff($end, true)->days;
									$counts+=$days+1;

								}
								$data[]=array('counts'=>$counts,'leave_type'=>$types->type_name,'leave_type_id'=>$types->type_id,'available_leave'=>$types->days_per_year,'start_year_date'=>$start_date,'end_year_date'=>$end_date);
							}
						}
					}
					$data+=$data;
				}

				return @$data;
			}
		}
	}

	//find gender_typ
	public function find_gender($employee_id){
		$query1=$this->db->query("select gender from xin_employees where user_id='".$employee_id."' limit 1");
		$result1=$query1->row();
		return $result1->gender;
	}

	//get contracat dates
	public function get_contract_dates($employee_id){
		$query1=$this->db->query("select from_date from xin_employee_contract where employee_id='".$employee_id."' and from_date!='' 
		and to_date!='' order by contract_id asc limit 1 ");
		$result1=$query1->row();
		$contract_start_date=$result1->from_date;
		$query2=$this->db->query("select to_date from xin_employee_contract where employee_id='".$employee_id."' and from_date!='' 
		and to_date!='' order by contract_id desc limit 1 ");
		$result2=$query2->row();
		$contract_to_date=$result2->to_date;
		return array('from_date'=>$contract_start_date,'to_date'=>$contract_to_date);
	}

	// get payroll templates > NOT USED
	public function attendance_employee_with_date($emp_id,$attendance_date) {
		$dates=salary_start_end_date($attendance_date);
		$exact_date_start=$dates['exact_date_start'];
		$exact_date_end=$dates['exact_date_end'];
		$today_date=TODAY_DATE;
		if(strtotime($today_date) < strtotime($exact_date_end)){
			$end_date=$today_date;
		}else{
			$end_date=$exact_date_end;
		}
		return $query = $this->db->query("SELECT * from xin_attendance_time where attendance_date >= '".$exact_date_start."' and attendance_date <= '".$end_date."' and employee_id = '".$emp_id."' and attendance_status IN ('','UA','ABSC','Absent')");
	}

	public function read_office_shift_information($id) {
		$condition = "office_shift_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_office_default_shift');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	// get record of leave > by id
	public function read_leave_information($id) {
		$condition = "lv.leave_id =" . "'" . $id . "'";
		$this->db->select('lv.*,types.type_name');
		$this->db->from('xin_leave_applications as lv');
		$this->db->join('xin_module_types as types','types.type_id=lv.leave_type_id','left');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	// get leave type by id
	public function read_leave_type_information($id) {
		$condition = "type_id =" . "'" . $id . "' AND type_of_module='leave_type'";
		$this->db->select('*');
		$this->db->from('xin_module_types');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	public function read_leave_type_id($name) {
		$condition = "type_name ='" . $name . "' AND type_of_module='leave_type'";
		$this->db->select('type_id');
		$this->db->from('xin_module_types');
		$this->db->where($condition);
		$this->db->limit(1);
		$query  = $this->db->get();
		$result = $query->result();
		return $result[0]->type_id;
	}

	// Function to add record in table
	public function add_employee_attendance($data){
		$this->db->insert('xin_attendance_time', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// Function to add record in table
	public function add_leave_record($data){
		$this->db->insert('xin_leave_applications', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// Function to add record in table
	public function add_task_record($data){
		$this->db->insert('xin_tasks', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// Function to add record in table
	public function add_office_shift_record($data){
		$this->db->insert('xin_office_default_shift', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// Function to add record in table
	public function add_holiday_record($data){
		$this->db->insert('xin_holidays', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// Function to add record in table
	public function add_ramadan_schedule($data){
		$this->db->insert('xin_ramadan_schedule', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// get record of task by id
	public function read_task_information($id) {
		$condition = "task_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_tasks');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	// get record of holiday by id
	public function read_holiday_information($id) {
		$condition = "unique_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_holidays');
		$this->db->where($condition);
		$query = $this->db->get();
		return $query->result_array();
	}

	// get record of ramadan by id
	public function read_ramadan_information($id) {
		$condition = "ramadan_schedule_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_ramadan_schedule');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function read_holiday_all_information($id) {
		$condition = "holiday_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_holidays');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result_array();
	}

	// get record of attendance by id
	public function read_attendance_information($id) {
		$condition = "time_attendance_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_attendance_time');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	// Function to delete_manual_attendance_record
	public function delete_manual_attendance_record($employee_id,$unique_code){
		$this->db->where('employee_id', $employee_id);
		$this->db->where('unique_code', $unique_code);
		$this->db->delete('xin_manual_attendance');
	}

	public function delete_change_schedule_record($employee_id){
		$this->db->where('employee_id', $employee_id);
		$this->db->delete('xin_change_schedule');
	}

	public function status_manual_attendance_record($employee_id,$unique_code,$change_status,$user_id){
		if($change_status=='approve'){
			$hr_head_status = 1;
			$reporting_manager_status = 1;
		}else if($change_status=='decline'){
			$hr_head_status = 2;
			$reporting_manager_status = 2;
		}else{
			$hr_head_status = 0;
			$reporting_manager_status = 0;
		}
		$data=array('hr_head_status'=>$hr_head_status,'reporting_manager_status'=>$reporting_manager_status,'updated_by'=>$user_id);

		$condition = "employee_id =" . "'" . $employee_id . "' and unique_code =" . "'" . $unique_code . "'";
		$this->db->where($condition);
		$this->db->update('xin_manual_attendance',$data);

		$this->db->where($condition);
		$this->db->from('xin_manual_attendance');
		$this->db->limit(1);
		$query = $this->db->get()->row_array()['manual_attendance_id'];
		return $query;
	}

	// Function to Delete selected record from table
	public function delete_task_record($id){
		$this->db->where('task_id', $id);
		$this->db->delete('xin_tasks');
	}

	// Function to Delete selected record from table
	public function delete_holiday_record($id){
		$this->db->where('unique_id', $id);
		$this->db->delete('xin_holidays');
	}

	/// Function to Delete selected record from table
	public function delete_ramadan_schedule($id){
		$this->db->where('ramadan_schedule_id', $id);
		$this->db->delete('xin_ramadan_schedule');
	}

	// Function to Delete selected record from table
	public function delete_shift_record($id){
		$this->db->where('office_shift_id', $id);
		$this->db->delete('xin_office_default_shift');
	}

	// Function to Delete selected record from table
	public function delete_leave_record($id){
		$this->db->where('leave_id', $id);
		$this->db->delete('xin_leave_applications');
	}

	public function delete_leave_approval_record($applied_on,$employee_id,$type_of_approval){
		$this->db->where('field_id', $applied_on);
		$this->db->where('employee_id', $employee_id);
		$this->db->where('type_of_approval', $type_of_approval);
		$this->db->delete('xin_employees_approval');
	}

  // Function to Delete selected record from table
	public function delete_leave_conversion_record($id){
		$this->db->where('conversion_id', $id);
		$this->db->delete('xin_leave_conversion_count');
		$this->db->where('conversion_id', $id);
		$this->db->delete('xin_salary_adjustments');
	}

	public function get_total_approval($applied_on,$status=''){
		$this->db->where('field_id',$applied_on);
		if($status!=''){
			$this->db->where('approval_status',$status);
		}
		$this->db->select('*');
		$this->db->from('xin_employees_approval');
		$query = $this->db->get();
		return $query->result();
	}

	public function check_next_approval_id($applied_on,$pay_date){
		$this->db->where('field_id',$applied_on);
		$this->db->where('pay_date',$pay_date);
		$this->db->select('*');
		$this->db->from('xin_employees_approval');
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	// Function to update record in table
	public function update_approval_status($applied_on, $type_of_approval, $approval_data,$approval_head_id){
		$this->db->where('field_id', $applied_on);
		$this->db->where('type_of_approval', $type_of_approval);
		if($approval_head_id!=''){
			$this->db->where('approval_head_id', $approval_head_id);
		}
		if( $this->db->update('xin_employees_approval',$approval_data)) {
			if($approval_head_id==''){
				return true;
			}else{
				$this->db->where('approval_head_id', $approval_head_id);
				$this->db->where('field_id',$applied_on);
				$this->db->select('*');
				$this->db->limit(1);
				$this->db->from('xin_employees_approval');
				$query = $this->db->get();
				return $query->result();
			}

		} else {
			return false;
		}
	}

	// Function to update record in table
	public function update_task_record($data, $id){
		$this->db->where('task_id', $id);
		if( $this->db->update('xin_tasks',$data)) {
			return true;
		} else {
			return false;
		}
	}

	public function update_bus_lateness($data, $bus_late_id){
		$this->db->where('bus_late_id', $bus_late_id);
		if($this->db->update('xin_bus_late_timings',$data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table
	public function update_leave_record($data, $id){
		$this->db->where('leave_id', $id);
		if( $this->db->update('xin_leave_applications',$data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table
	public function update_holiday_record($data, $id, $department_id){
		//echo "update xin_holidays set event_name='".$data['event_name']."',description='".$data['description']."',start_date='".$data['start_date']."',end_date='".$data['end_date']."',is_publish='".$data['is_publish']."' where unique_id='".$id."' AND department_id='".$department_id."'";echo "<br>";
		$this->db->where('unique_id', $id);
		$this->db->where('department_id', $department_id);
		if( $this->db->update('xin_holidays',$data)) {
			return true;
		} else {
			return false;
		}
	}

	public function update_ramadan_schedule($data, $id){
		$this->db->where('ramadan_schedule_id', $id);
		if( $this->db->update('xin_ramadan_schedule',$data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table
	public function update_shift_record($data, $id){
		$this->db->where('office_shift_id', $id);
		if( $this->db->update('xin_office_default_shift',$data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table
	public function assign_task_user($data, $id){
		$this->db->where('task_id', $id);
		if( $this->db->update('xin_tasks',$data)) {
			return true;
		} else {
			return false;
		}
	}

	// get comments
	public function get_comments($id) {
		return $query = $this->db->query("SELECT * from xin_tasks_comments where task_id = '".$id."'");
	}

	// get comments
	public function get_attachments($id) {
		return $query = $this->db->query("SELECT * from xin_tasks_attachment where task_id = '".$id."'");
	}

	// Function to add record in table > add comment
	public function add_comment($data){
		$this->db->insert('xin_tasks_comments', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// Function to Delete selected record from table
	public function delete_comment_record($id){
		$this->db->where('comment_id', $id);
		$this->db->delete('xin_tasks_comments');
	}

	// Function to Delete selected record from table
	public function delete_bus_late_record($id){
		$this->db->where('bus_late_id', $id);
		$this->db->delete('xin_bus_late_timings');
	}

	// Function to Delete selected record from table
	public function delete_attachment_record($id){
		$this->db->where('task_attachment_id', $id);
		$this->db->delete('xin_tasks_attachment');
	}

	// Function to add record in table > add attachment
	public function add_new_attachment($data){
		$this->db->insert('xin_tasks_attachment', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	//get status name from status code
	public function get_status_name($status_code){
		$condition = "type_code =" . "'" . $status_code . "' AND type_of_module='attendance_status_type'";
		$this->db->select('type_name');
		$this->db->from('xin_module_types');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->row();
		$status_name=$result->type_name;
		return $status_name;
	}

	//has reporting manager
	public function has_reporting_manager($user_id){
		$condition = "user_id = '".$user_id."' and reporting_manager!=0";
		$this->db->select('reporting_manager');
		$this->db->from('xin_employees');
		$this->db->where($condition);
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			return 0;
		} else {
			$result = $query->row();
			$reporting_manager=$result->reporting_manager;
			return $reporting_manager;
		}
	}

	// check user attendance
	public function check_user_attendance() {
		$today_date = date('Y-m-d');
		$session = $this->session->userdata('username');
		return $query = $this->db->query("SELECT * FROM xin_attendance_time where `employee_id` = '".$session['user_id']."' and `attendance_date` = '".$today_date."' order by time_attendance_id desc limit 1");
	}

	// check user attendance
	public function check_user_attendance_clockout() {
		$today_date = date('Y-m-d');
		$session = $this->session->userdata('username');
		return $query = $this->db->query("SELECT * FROM xin_attendance_time where `employee_id` = '".$session['user_id']."' and `attendance_date` = '".$today_date."' and clock_out='' order by time_attendance_id desc limit 1");
	}

	//  set clock in- attendance > user
	public function add_new_attendance($data){
		$this->db->insert('xin_attendance_time', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		else {
			return false;
		}
	}
	
	// get last user attendance
	public function get_last_user_attendance() {
		$session = $this->session->userdata('username');
		$query = $this->db->query("SELECT * FROM xin_attendance_time where `employee_id` = '".$session['user_id']."' order by time_attendance_id desc limit 1");
		return $query->result();
	}

	// get last user attendance > check if loged in-
	public function attendance_time_checks($id) {
		$session = $this->session->userdata('username');
		return $query = $this->db->query("SELECT * FROM xin_attendance_time where `employee_id` = '".$id."' and clock_out = '' order by time_attendance_id desc limit 1");
	}

	// Function to update record in table > update attendace.
	public function update_attendance_clockedout($data,$id,$emp){
		$condition = "time_attendance_id !='' and employee_id ='".$emp."'";
		$this->db->where($condition);
		if( $this->db->update('xin_attendance_time1',$data)) {
			return true;
		}
		else{
			return false;
		}
	}

	//Holidays when calculating salary days
	public function check_holidays_in_salary($start_date,$end_date){
		$condition = "is_publish = '1' and start_date >= '".$start_date."' and end_date <= '".$end_date."'";
		$this->db->select('*');
		$this->db->from('xin_holidays');
		$this->db->where($condition);
		$query = $this->db->get();
		return $query->result();
	}

	//Check if Public Holidays
	public function check_public_holiday($attendance_date,$department_id,$country_id){
		$condition = "is_publish = '1' and department_id='".$department_id."' and country_id='".$country_id."' and '".$attendance_date."' between start_date and end_date";
		$this->db->select('holiday_id');
		$this->db->from('xin_holidays');
		$this->db->where($condition);
		$query = $this->db->get();


		if ($query->num_rows() == 0) {
			return 0;
		} else {
			return 1;
		}
	}

	public function check_leave_type_status($attendance_date,$employee_id,$leave_type_id,$exclude_wo_ph=''){
		//a_t.clock_in='' and a_t.clock_out='' and
		$condition = "a_t.employee_id = '".$employee_id."' AND a_t.attendance_date='".$attendance_date."' AND l_a.status=2 and l_a.reporting_manager_status=2 and l_a.leave_type_id='".$leave_type_id."'  and '".$attendance_date."' between l_a.from_date and l_a.to_date";
		$this->db->select('l_a.leave_id');
		$this->db->from('xin_attendance_time as a_t');
		$this->db->join('xin_leave_applications as l_a',"a_t.employee_id=l_a.employee_id",'left');
		$this->db->where($condition);
		$this->db->limit(1);
		if($exclude_wo_ph!=''){
			$this->db->where_in('a_t.attendance_status',array('WO'));	//'PH',
		}
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			return 0;
		} else {
			return 1;

		}
	}

	public function check_leave_result($attendance_date,$employee_id,$leave_type_id){
		$condition = "l_a.employee_id = '".$employee_id."' and l_a.status=2 and l_a.reporting_manager_status=2 and l_a.leave_type_id='".$leave_type_id."' and '".$attendance_date."' between l_a.from_date and l_a.to_date";
		$this->db->select('l_a.leave_id,l_t.type_name');
		$this->db->from('xin_leave_applications as l_a');
		$this->db->join('xin_module_types as l_t','l_t.type_id=l_a.leave_type_id','left');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			return array('value'=>0,'l_date'=>'','l_name'=>'');
		} else {
			$result = $query->result();
			return array('value'=>1,'l_date'=>$attendance_date,'l_name'=>$result[0]->type_name);
		}
	}

	public function check_leave_type_paid($attendance_date,$employee_id,$leave_type_id,$exclude_wo_ph=''){
		$query_chk = $this->db->query("SELECT make_payment_id FROM `xin_make_payment` WHERE `employee_id` = '".$employee_id."' AND `leave_salary_paid` LIKE '%".$attendance_date."%'  AND status=1 limit 1");
		$result_chk = $query_chk->result();
		if($result_chk){
			return 1;
		}else{
			return 0;
		}

	}

	//Check If Annual Leave / EMergency Leave
	public function check_leave_type($attendance_date,$employee_id,$leave_type_id,$exclude_wo_ph=''){	//1
		//AND  a_t.clock_in='' and a_t.clock_out=''
		$condition = "a_t.employee_id = '".$employee_id."'   and l_a.status=2 and l_a.reporting_manager_status=2 and l_a.leave_type_id='".$leave_type_id."' and '".$attendance_date."' between l_a.from_date and l_a.to_date";
		$this->db->select('l_a.leave_id');
		$this->db->from('xin_attendance_time as a_t');
		$this->db->join('xin_leave_applications as l_a',"a_t.employee_id=l_a.employee_id",'left');
		$this->db->where($condition);
		$this->db->limit(1);
		if($exclude_wo_ph!=''){
			$this->db->where('a_t.attendance_status','WO');
		}
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			return 0;
		}
		else {
			return 1;
		}
	}

	public function check_leave_type_occur($attendance_date,$employee_id,$leave_type_id,$exclude_wo_ph=''){
		$condition = "a_t.employee_id = '".$employee_id."' and l_a.status=2 and l_a.reporting_manager_status=2 and l_a.leave_type_id='".$leave_type_id."' and '".$attendance_date."' between l_a.from_date and l_a.to_date";
		$this->db->select('l_a.leave_id');
		$this->db->from('xin_attendance_time as a_t');
		$this->db->join('xin_leave_applications as l_a',"a_t.employee_id=l_a.employee_id",'left');
		$this->db->where($condition);
		$this->db->limit(1);
		if($exclude_wo_ph!=''){
			$this->db->where('a_t.attendance_status','WO');
		}
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			return 0;
		} else {
			return 1;
		}
	}

	//public holiday
	public function check_public_holiday_type_occur($attendance_date,$employee_id,$department_id,$country_id,$exclude_wo_ph=''){
		$wo_slug='';
		if($exclude_wo_ph!=''){
			$wo_slug= " AND a_t.attendance_status='WO'";
		}
		$query=$this->db->query("SELECT `h_d`.`holiday_id`,`a_t`.`clock_in`
		FROM `xin_attendance_time` as `a_t`
		LEFT JOIN `xin_holidays` as `h_d` ON `h_d`.`department_id`='".$department_id."' AND `h_d`.`country_id`='".$country_id."'
		WHERE `a_t`.`employee_id` = '".$employee_id."' AND `a_t`.`attendance_date` = '".$attendance_date."' AND `h_d`.`is_publish` = 1  and ('".$attendance_date."' BETWEEN `h_d`.`start_date` and h_d.end_date) $wo_slug LIMIT 1");
		if ($query->num_rows() == 0) {
			return 0;
		} else {
			$query_leave_chk = $this->db->query("SELECT leave_id FROM `xin_leave_applications` WHERE `employee_id` = '".$employee_id."' AND '".$attendance_date."'  BETWEEN from_date and to_date and status=2 and reporting_manager_status=2 limit 1");
			$result_leave_chk = $query_leave_chk->result();
			if($result_leave_chk){
				return 0;
			}else{
				return 1;

			}
		}
	}

	//public holiday
	public function check_public_holiday_type($attendance_date,$employee_id,$department_id,$country_id,$exclude_wo_ph=''){
		$wo_slug='';
		if($exclude_wo_ph!=''){
			$wo_slug= " AND a_t.attendance_status='WO'";
		}
		$query=$this->db->query("SELECT `h_d`.`holiday_id`,`a_t`.`clock_in`
		FROM `xin_attendance_time` as `a_t`
		LEFT JOIN `xin_holidays` as `h_d` ON `h_d`.`department_id`='".$department_id."' AND `h_d`.`country_id`='".$country_id."'
		WHERE `a_t`.`employee_id` = '".$employee_id."' AND `a_t`.`attendance_date` = '".$attendance_date."' AND `h_d`.`is_publish` = 1  and ('".$attendance_date."' BETWEEN `h_d`.`start_date` and h_d.end_date) $wo_slug LIMIT 1");
		if ($query->num_rows() == 0) {
			return 0;
		} else {
			$query_leave_chk = $this->db->query("SELECT leave_id FROM `xin_leave_applications` WHERE `employee_id` = '".$employee_id."' AND '".$attendance_date."'  BETWEEN from_date and to_date and status=2 and reporting_manager_status=2 limit 1");
			$result_leave_chk = $query_leave_chk->result();
			if($result_leave_chk){
				return 0;
			}else{
				$query_chk = $this->db->query("SELECT make_payment_id FROM `xin_make_payment` WHERE `employee_id` = '".$employee_id."' AND `leave_salary_paid` LIKE '%".$attendance_date."%'  AND status=1 limit 1");
				$result_chk = $query_chk->result();
				if($result_chk){
					return 0;
				}else{
					$result_query = $query->result();
					if($result_query[0]->clock_in==''){
						$leave_array=sandwich_leaves($employee_id,$department_id,$country_id,array($attendance_date));
						if($leave_array){
							return 0;
						}else{
							return 1;
						}
					}else{
						return 1;
					}



				}

			}
		}
	}

	//public holiday
	public function check_public_holiday_type_id($attendance_date,$employee_id,$department_id,$country_id,$exclude_wo_ph=''){
		$wo_slug='';
		if($exclude_wo_ph!=''){
			$wo_slug= " AND a_t.attendance_status='WO'";
		}
		$query=$this->db->query("SELECT `h_d`.`holiday_id`
		FROM `xin_attendance_time` as `a_t`
		LEFT JOIN `xin_holidays` as `h_d` ON `h_d`.`department_id`='".$department_id."' AND `h_d`.`country_id`='".$country_id."'
		WHERE `a_t`.`employee_id` = '".$employee_id."' AND `a_t`.`attendance_date` = '".$attendance_date."' AND `h_d`.`is_publish` = 1  and ('".$attendance_date."' BETWEEN `h_d`.`start_date` and h_d.end_date) $wo_slug LIMIT 1");
		$val_array=[];
		if ($query->num_rows() != 0) {
			$query_chk = $this->db->query("SELECT make_payment_id FROM `xin_make_payment` WHERE `employee_id` = '".$employee_id."' AND `leave_salary_paid` LIKE '%".$attendance_date."%' AND status=1 limit 1");
			$result_chk = $query_chk->result();
			if(!$result_chk){
				$result = $query->result();
				$val_array[$attendance_date]=array('leave_id'=>$result[0]->holiday_id,'attendance_date'=>$attendance_date,'leave_status_code'=>'PH','leave_type_id'=>0,'type_name'=>'Public-Holiday');
			}
		}
		return $val_array;
	}

	public function get_location_name($emp_id){
		$condition = "emp.user_id =" . "'" . $emp_id . "'";
		$this->db->select_max('loc.location_name');
		$this->db->from('xin_employees as emp');
		$this->db->join('xin_office_location as loc','loc.location_id=emp.office_location_id','left');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		$result=$query->result();
		if ($query->num_rows() == 1) {
			return $result[0]->location_name;
		} else {
			return false;
		}
	}

	public function check_leave_type_id($attendance_date,$employee_id,$leave_type_id,$exclude_wo_ph=''){
		$condition = "a_t.employee_id = '".$employee_id."' and l_a.status=2 and l_a.reporting_manager_status=2 and l_a.leave_type_id='".$leave_type_id."' and '".$attendance_date."' between l_a.from_date and l_a.to_date";
		$this->db->select('l_a.leave_id,l_t.type_name,l_a.leave_status_code');
		$this->db->from('xin_attendance_time as a_t');
		$this->db->join('xin_leave_applications as l_a',"a_t.employee_id=l_a.employee_id",'left');
		$this->db->join('xin_module_types as l_t',"l_t.type_id=l_a.leave_type_id",'left');
		$this->db->where($condition);
		$this->db->limit(1);
		$val_array=[];
		if($exclude_wo_ph!=''){
			$this->db->where_in('a_t.attendance_status',array('WO'));//'PH',
		}
		$query = $this->db->get();
		if ($query->num_rows() != 0) {
			$query_chk = $this->db->query("SELECT make_payment_id FROM `xin_make_payment` WHERE `employee_id` = '".$employee_id."' AND `leave_salary_paid` LIKE '%".$attendance_date."%' AND status=1 limit 1");
			$result_chk = $query_chk->result();
			if(!$result_chk){
				$result = $query->result();
				foreach($result as $res){
					$val_array[$attendance_date]=array('leave_id'=>$res->leave_id,'leave_status_code'=>$res->leave_status_code,'attendance_date'=>$attendance_date,'leave_type_id'=>$leave_type_id,'type_name'=>str_replace(' ','-',$res->type_name));
				}
			}
		}
		return $val_array;
	}

	// No use Now

	public function check_maternity_leave_type_id($attendance_date,$employee_id,$leave_type_id,$exclude_wo_ph=''){
		$check_date_of_joining=$this->Timesheet_model->check_date_of_joining($employee_id);
		$maternityleave_final_date=date('Y-m-d', strtotime(MATERNITY_LEAVE_ALLOW, strtotime($check_date_of_joining)));
		// echo $attendance_date.$newjoinee_sickleave_final_date;die;
		if(strtotime($attendance_date) >= strtotime($maternityleave_final_date)){
			$condition = "a_t.employee_id = '".$employee_id."'  AND  a_t.clock_in='' and a_t.clock_out='' and l_a.status=2 and l_a.reporting_manager_status=2 and l_a.leave_type_id='".$leave_type_id."' and '".$attendance_date."' between l_a.from_date and l_a.to_date";
			$this->db->select('l_a.leave_id,l_t.type_name');
			$this->db->from('xin_attendance_time as a_t');
			$this->db->join('xin_leave_applications as l_a',"a_t.employee_id=l_a.employee_id",'left');
			$this->db->join('xin_module_types as l_t',"l_t.type_id=l_a.leave_type_id",'left');
			$this->db->where($condition);
			$this->db->limit(1);
			$val_array=[];
			if($exclude_wo_ph!=''){
				$this->db->where_in('a_t.attendance_status',array('WO'));	//'PH',
			}
			$query = $this->db->get();
			if ($query->num_rows() != 0) {
				$query_chk = $this->db->query("SELECT make_payment_id FROM `xin_make_payment` WHERE `employee_id` = '".$employee_id."' AND `leave_salary_paid` LIKE '%".$attendance_date."%'  AND status=1 limit 1");
				$result_chk = $query_chk->result();
				if(!$result_chk){
					$result = $query->result();
					foreach($result as $res){
						$val_array[$attendance_date]=array('leave_id'=>$res->leave_id,'attendance_date'=>$attendance_date,'leave_type_id'=>$leave_type_id,'type_name'=>$res->type_name);
					}
				}
			}
			return $val_array;}
		else{
			return '';
		}
	}

	public function check_sick_leave_type_id($attendance_date,$employee_id,$leave_type_id,$exclude_wo_ph=''){
		$condition = "a_t.employee_id = '".$employee_id."' and l_a.status=2 and l_a.reporting_manager_status=2 and l_a.leave_type_id='".$leave_type_id."'  and '".$attendance_date."' between l_a.from_date and l_a.to_date";
		$this->db->select('l_a.leave_id,l_t.type_name,l_t.type_name');
		$this->db->from('xin_attendance_time as a_t');
		$this->db->join('xin_leave_applications as l_a',"a_t.employee_id=l_a.employee_id",'left');
		$this->db->join('xin_module_types as l_t',"l_t.type_id=l_a.leave_type_id",'left');
		$this->db->where($condition);
		$this->db->limit(1);
		$val_array=[];
		if($exclude_wo_ph!=''){
			$this->db->where_in('a_t.attendance_status',array('WO'));
		}
		$query = $this->db->get();
		if ($query->num_rows() != 0) {
			$query_chk = $this->db->query("SELECT make_payment_id FROM `xin_make_payment` WHERE `employee_id` = '".$employee_id."' AND `leave_salary_paid` LIKE '%".$attendance_date."%'  AND status=1 limit 1");
			$result_chk = $query_chk->result();
			if(!$result_chk){
				$result = $query->result();
				foreach($result as $res){
					$val_array[$attendance_date]=array('leave_id'=>$res->leave_id,'attendance_date'=>$attendance_date,'leave_type_id'=>$leave_type_id,'type_name'=>$res->type_name);
				}
			}
		}
		return $val_array;
	}


	public function check_maternity_leave_type($attendance_date,$employee_id,$leave_type_id,$exclude_wo_ph=''){//1
		$check_date_of_joining=$this->Timesheet_model->check_date_of_joining($employee_id);
		$maternityleave_final_date=date('Y-m-d', strtotime(MATERNITY_LEAVE_ALLOW, strtotime($check_date_of_joining)));
		// echo $attendance_date.$newjoinee_sickleave_final_date;die;
		if(strtotime($attendance_date) >= strtotime($maternityleave_final_date)){
			$condition = "a_t.employee_id = '".$employee_id."' AND  a_t.clock_in='' and a_t.clock_out='' and l_a.status=2 and l_a.reporting_manager_status=2 and l_a.leave_type_id='".$leave_type_id."' and '".$attendance_date."' between l_a.from_date and l_a.to_date";
			$this->db->select('l_a.leave_id');
			$this->db->from('xin_attendance_time as a_t');
			$this->db->join('xin_leave_applications as l_a',"a_t.employee_id=l_a.employee_id",'left');
			$this->db->where($condition);
			$this->db->limit(1);
			if($exclude_wo_ph!=''){
				$this->db->where_in('a_t.attendance_status',array('WO'));//'PH',
			}
			$query = $this->db->get();
			if ($query->num_rows() == 0) {
				return 0;
			} else {
				$query_chk = $this->db->query("SELECT make_payment_id FROM `xin_make_payment` WHERE `employee_id` = '".$employee_id."' AND `leave_salary_paid` LIKE '%".$attendance_date."%'  AND status=1 limit 1");
				$result_chk = $query_chk->result();
				if($result_chk){
					return 0;
				}else{
					return 1;
				}
			}
		}else{
			return 0;
		}
	}
	
	// No use Now
	//1
	public function check_sick_leave_type_paid($attendance_date,$employee_id,$leave_type_id,$exclude_wo_ph='',$hours,$rate_per_hour=0){
		$condition = "a_t.employee_id = '".$employee_id."'  and l_a.status=2 and l_a.reporting_manager_status=2 and l_a.leave_type_id='".$leave_type_id."' and '".$attendance_date."' between l_a.from_date and l_a.to_date";
		$this->db->select('l_a.leave_id,l_a.leave_status_code');
		$this->db->from('xin_attendance_time as a_t');
		$this->db->join('xin_leave_applications as l_a',"a_t.employee_id=l_a.employee_id",'left');
		$this->db->where($condition);
		$this->db->limit(1);

		if($exclude_wo_ph!=''){
			$this->db->where_in('a_t.attendance_status',array('WO'));//'PH',
		}
		$query = $this->db->get();
		if ($query->num_rows() == 0) {

		} else {
			$result = $query->result();

			$query_chk = $this->db->query("SELECT make_payment_id FROM `xin_make_payment` WHERE `employee_id` = '".$employee_id."' AND `leave_salary_paid` LIKE '%".$attendance_date."%' AND status=1 limit 1");
			$result_chk = $query_chk->result();
			if($result_chk){
				return array('attendance_status'=>$result[0]->leave_status_code,'attendance_date'=>$attendance_date,'total_hours'=>$hours,'salary_amount'=>($hours*$rate_per_hour));
			}
		}

	}

	public function check_sick_leave_type($attendance_date,$employee_id,$leave_type_id,$exclude_wo_ph='',$hours,$rate_per_hour=0){
		$condition = "a_t.employee_id = '".$employee_id."' and l_a.status=2 and l_a.reporting_manager_status=2 and l_a.leave_type_id='".$leave_type_id."' and '".$attendance_date."' between l_a.from_date and l_a.to_date";
		$this->db->select('l_a.leave_id,l_a.leave_status_code');
		$this->db->from('xin_attendance_time as a_t');
		$this->db->join('xin_leave_applications as l_a',"a_t.employee_id=l_a.employee_id",'left');
		$this->db->where($condition);
		$this->db->limit(1);

		if($exclude_wo_ph!=''){
			$this->db->where_in('a_t.attendance_status',array('WO'));
		}
		$query = $this->db->get();
		if ($query->num_rows() == 0) {

		} else {
			$result = $query->result();

			$query_chk = $this->db->query("SELECT make_payment_id FROM `xin_make_payment` WHERE `employee_id` = '".$employee_id."' AND `leave_salary_paid` LIKE '%".$attendance_date."%' AND status=1 limit 1");
			$result_chk = $query_chk->result();
			if($result_chk){

			}else{
				return array('attendance_status'=>$result[0]->leave_status_code,'attendance_date'=>$attendance_date,'total_hours'=>$hours,'salary_amount'=>($hours*$rate_per_hour));
			}
		}

	}

	//Check If Annual Leave / EMergency Leave
	public function check_leave_unpaid_type($attendance_date,$employee_id,$annual_id,$maternity_id,$sick_id,$exclude_wo_ph=''){
		$condition = "a_t.employee_id='".$employee_id."' and a_t.attendance_date='".$attendance_date."'  AND a_t.attendance_status='Absent'";
		$this->db->select('a_t.attendance_status');
		$this->db->limit(1);
		$this->db->from('xin_attendance_time as a_t');
		$this->db->where($condition);
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			return 0;
		}
		else{
			$query_chk1 = $this->db->query("SELECT make_payment_id FROM `xin_make_payment` WHERE `employee_id` = '".$employee_id."' AND `leave_salary_paid` LIKE '%".$attendance_date."%' AND status=1 limit 1");
			$result_chk1 = $query_chk1->result();
			if($result_chk1){
				return 0;
			}else{
				$query_chk_ob = $this->db->query("select attendance_status from xin_manual_attendance where employee_id = '".$employee_id."' AND '".$attendance_date."' between start_date and end_date AND hr_head_status=1 AND reporting_manager_status=1 limit 1");
				$result_chk_ob=$query_chk_ob->result();
				if($result_chk_ob){
					return 0;
				}else{
					return 1;
				}
			}

		}
	}

	//Check If check_leave_type_unauthorised
	public function check_leave_type_unauthorised($attendance_date,$employee_id,$leave_type_id){
		$condition = "employee_id = '".$employee_id."' and status!=2 and leave_type_id='".$leave_type_id."' and '".$attendance_date."' between from_date and to_date ";
		$this->db->select('leave_id');
		$this->db->from('xin_leave_applications');
		$this->db->where($condition);
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			return 0;
		} else {
			return 1;
		}
	}

	//Check If Termination
	public function check_termination($attendance_date,$employee_id){
		$condition = "employee_id = '".$employee_id."' and status=1 and termination_date <='".$attendance_date."'";
		$this->db->select('termination_id');
		$this->db->from('xin_employee_terminations');
		$this->db->where($condition);
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			return 0;
		} else {
			return 1;
		}
	}

	//Check If Suspension
	public function check_suspension($attendance_date,$employee_id){
		$condition = "employee_id = '".$employee_id."' and status=1 and suspension_start_date <='".$attendance_date."' and suspension_end_date >='".$attendance_date."'";
		$this->db->select('suspension_id');
		$this->db->from('xin_employee_suspension');
		$this->db->where($condition);
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			return 0;
		} else {
			return 1;
		}
	}

	//Check If Resignation
	public function check_resignation($attendance_date,$employee_id){
		$condition = "employee_id = '".$employee_id."' and resignation_date <='".$attendance_date."'";
		$this->db->select('resignation_id');
		$this->db->from('xin_employee_resignations');
		$this->db->where($condition);
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			return 0;
		} else {
			return 1;
		}
	}

	//List of attendance status
	public function list_of_attendace_status(){
		$this->db->select('type_name,type_code');
		$this->db->from('xin_module_types');
		$query = $this->db->get();
		return $query->result();
	}

	//Check employee date of joining
	public function check_date_of_joining($employee_id){
		$condition = "user_id = '".$employee_id."'";
		$this->db->select('date_of_joining');
		$this->db->from('xin_employees');
		$this->db->where($condition);
		$query = $this->db->get();
		$result = $query->row();
		$date_of_joining=$result->date_of_joining;
		return $date_of_joining;
	}

	//count of sick leave
	public function count_of_sick_leave($employee_id,$leave_type_id){
		$condition = "employee_id = '".$employee_id."' and status=2 and reporting_manager_status=2 and leave_type_id='".$leave_type_id."'";
		$this->db->select('*');
		$this->db->from('xin_leave_applications');
		$this->db->where($condition);
		$query = $this->db->get();
		$result = $query->result_array();
		$fridays_date_not='';
		foreach($result as $value){

			$begin=new DateTime($value['from_date']);
			$end=new DateTime($value['to_date']);

			for($i = $begin; $i <= $end; $i->modify('+1 days')){
				$current_date=$i->format("Y-m-d");
				$days = date('l',strtotime($current_date));
				if($days != 'Friday') {
					$fridays_date_not[]=$current_date;
				}
			}

		}
		return count($fridays_date_not);

	}

	// Function to add record in table
	public function add_manual_attendance($data){
		$this->db->insert('xin_manual_attendance', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// Function to add record in table
	public function add_change_schedule($data){
		$employee_id = $data['employee_id'];
		$update_data = $this->read_change_schedule_information($employee_id);
		if($update_data){
			$this->db->where('employee_id', $employee_id);
			if( $this->db->update('xin_change_schedule',$data)) {
				return true;
			} else {
				return false;
			}
		}else{
			$this->db->insert('xin_change_schedule', $data);
			if ($this->db->affected_rows() > 0) {
				return true;
			} else {
				return false;
			}
		}	
	}

	// get office shifts
	public function get_change_schedule() {
		return $this->db->get("xin_change_schedule");
	}

	public function update_employee_shift_hours($user_id,$today_date,$shift_start_time,$shift_end_time){
		$data=array('employee_id' => $user_id, 'attendance_date' => $today_date, 'shift_start_time' => $shift_start_time, 'shift_end_time' => $shift_end_time);
		$check_if_already=$this->Employees_model->check_if_already_insert($user_id,$today_date);
		if($check_if_already > 0){
			$condition = "employee_id =" . "'" . $user_id . "' and attendance_date ='".$today_date."'";
			$this->db->where($condition);
			$this->db->update('xin_attendance_time',$data);
		}else{
			$this->db->insert('xin_attendance_time',$data);
		}
	}
	// get single user > by designation
	public function read_user_info_bydepartment($id) {
		$condition = "department_id =" . "'" . $id . "' and is_active=1";
		$this->db->select('user_id');
		$this->db->from('xin_employees');
		$this->db->where($condition);
		$query = $this->db->get();
		$result= $query->result();

		$userid='';
		if($result){
			foreach($result as $res){

				$userid[]=$res->user_id;
			}}
		return $userid;
	}

	public function read_change_manual_attendance_information($id,$unique_code) {
		$condition = "employee_id=" . "'" . $id . "' AND unique_code=" . "'" . $unique_code . "'";
		$this->db->select('*');
		$this->db->from('xin_manual_attendance');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	public function read_change_schedule_information($id) {
		$condition = "employee_id=" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_change_schedule');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	public function attendance_chart_view($schedule_user_id,$start_dt,$end_dt) {
		$this->db->order_by("attendance_date","ASC");
		$condition = "employee_id =" . "'" . $schedule_user_id . "' and attendance_date >=" . "'" . $start_dt . "' and attendance_date <=" . "'" . $end_dt . "'";
		$this->db->select('*');
		$this->db->from('xin_attendance_time');
		$this->db->where($condition);
		$query = $this->db->get();
		return $query->result();
	}

	public function update_manual_attendance($data, $id,$unique_code){
		$condition = "employee_id =" . "'" . $id . "' and unique_code =" . "'" . $unique_code . "'";
		$this->db->where($condition);
		if( $this->db->update('xin_manual_attendance',$data)) {
			$this->db->where($condition);
			$this->db->from('xin_manual_attendance');
			$this->db->limit(1);
			$query = $this->db->get()->row_array()['manual_attendance_id'];
			return $query;
		} else {
			return false;
		}
	}

	public function update_leave_conversion($data, $id){
		$this->db->where('conversion_id', $id);
		if( $this->db->update('xin_leave_conversion_count',$data)) {
			return true;
		} else {
			return false;
		}
	}

	public function update_change_schedule($data, $id){
		$this->db->where('employee_id', $id);
		if( $this->db->update('xin_change_schedule',$data)) {
			return true;
		} else {
			return false;
		}
	}

	public function add_leave_conversion($data){
		$this->db->insert('xin_leave_conversion_count', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	public function read_all_conversion_lists($id) {
		$this->db->select('*');
		$this->db->from('xin_leave_conversion_count');
		if($id!='0'){
			$this->db->where('employee_id',$id);
		}
		$query = $this->db->get();
		return $query;
	}

	public function read_conversion_lists($id) {
		$this->db->select('*');
		$this->db->from('xin_leave_conversion_count');
		$this->db->where('conversion_id',$id);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_current_leave_list($today_date,$previous_date){
		$query = $this->db->query("select lv.*,emp.reporting_manager from xin_leave_applications lv left join xin_employees as emp on emp.user_id=lv.employee_id where lv.reporting_manager_status=1 AND lv.status=1 AND unix_timestamp(lv.created_at) >='".strtotime($previous_date)."'  AND unix_timestamp(lv.created_at) <='".strtotime($today_date)."' AND emp.reporting_manager!=0 AND emp.reporting_manager!=''");
		return $result= $query->result();
	}

	public function leave_approval_insert_update($employee_id,$applied_on,$reporting_manager,$leave_type_id){
		$type = $this->read_leave_type_information($leave_type_id);
		$leave_name=$type[0]->type_name;
		$this->db->select('approval_id');
		$this->db->from('xin_employees_approval');
		$this->db->where('employee_id',$employee_id);
		$this->db->where('field_id',$applied_on);
		$this->db->where('pay_date',1);
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			approval_creation($employee_id,'leave_request',$leave_name,$applied_on,'first');
		}
	}

	public function get_current_approval_list($today_date,$previous_date,$state){
		$query = $this->db->query("select appr.* from xin_employees_approval appr where appr.type_of_approval='leave_request' AND unix_timestamp(appr.updated_at) >='".strtotime($previous_date)."'  AND unix_timestamp(appr.updated_at) <='".strtotime($today_date)."' AND appr.pay_date='".$state."' AND approval_status=1");
		return $result= $query->result();
	}

	public function get_leaves_appliedon($employee_id,$field_id){
		$this->db->select('lv.*,emp.first_name,emp.middle_name,emp.last_name,dept.department_name,mod.type_name');
		$this->db->from('xin_leave_applications as lv');
		$this->db->join('xin_employees as emp','emp.user_id=lv.employee_id','left');
		$this->db->join('xin_departments as dept','dept.department_id=emp.department_id','left');
		$this->db->join('xin_module_types as mod','mod.type_id=lv.leave_type_id','left');
		$this->db->where('lv.employee_id',$employee_id);
		$this->db->where('lv.applied_on',$field_id);
		$query = $this->db->get();
		return $query->result();
	}
	public function check_biometric($biometric_id,$inc_id){
		if($inc_id==''){
			$condition = "biometric_id =" . "'" . $biometric_id . "'";
		}else{
			$condition = "biometric_id ='".$biometric_id."' AND id!='".$inc_id."'";
		}
		
		$this->db->select('biometric_id');
		$this->db->from('xin_biometric_users_list');
		$this->db->where($condition);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function add_biometric_user($data){
		$this->db->insert('xin_biometric_users_list', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		else {
			return false;
		}
	}

	public function biometric_users_list_delete($id){
		$this->db->where('id', $id);
		$this->db->delete('xin_biometric_users_list');
	}

	public function read_biometric_user($id){
		$this->db->select('bio.*,emp.first_name,emp.middle_name,emp.last_name');
		$this->db->from('xin_biometric_users_list as bio');
		$this->db->join('xin_employees as emp',"emp.user_id=bio.employee_id",'left');
		$this->db->where('id',$id);
		$this->db->limit(1);
		$result= $this->db->get();
		return $result->result();
	}

	public function update_biometric_user($data, $id){
		$this->db->where('id', $id);
		if( $this->db->update('xin_biometric_users_list',$data)) {
			return true;
		} else {
			return false;
		}
	}

	public function add_al_expiry_adjustments($data){
		$this->db->insert('xin_employee_adjustments', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	public function common_delete_data($id,$table,$field_name){
		if($table!='' && $id!='' && $field_name!=''){
			$this->db->where($field_name, $id);
			$this->db->delete($table);
		}		
	}

	public function read_al_expiry_data($id){
		$this->db->select('adj.*,emp.first_name,emp.middle_name,emp.last_name');
		$this->db->from('xin_employee_adjustments as adj');
		$this->db->join('xin_employees as emp',"emp.user_id=adj.adjust_employee_id",'left');
		$this->db->where('adjust_id',$id);
		$this->db->limit(1);
		$result= $this->db->get();
		return $result->result();
	}

	public function common_update_data($id,$table,$field_name,$data){ 
		if($table!='' && $id!='' && $field_name!=''  && $data){
			$this->db->where($field_name, $id);
			if( $this->db->update($table,$data)) {
				return true;
			} else {
				return false;
			}
		}
	}
	
	public function getLeaveDetails($leave_id){

		$this->db->select('xin_leave_applications.*,xin_module_types.type_name');
		$this->db->from('xin_leave_applications');
		$this->db->where('leave_id',$leave_id);
		$this->db->join('xin_module_types','xin_leave_applications.leave_type_id = xin_module_types.type_id');
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return false;
		}
	}

	public function getEmployeeData($employee_id){

		$this->db->select('first_name,middle_name,last_name');
		$this->db->from('xin_employees');
		$this->db->where('user_id',$employee_id);
		$query = $this->db->get();
		if($query->num_rows() > 0){

			return $query->result_array();
		}
	}

	public function getDepartmentById($department_id){

		$this->db->select('department_name');
		$this->db->from('xin_departments');
		$this->db->where('department_id',$department_id);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getDepartmentByName($department_name){

		$this->db->select('department_id');
		$this->db->from('xin_departments');
		$this->db->where('department_name',$department_name);
		$query = $this->db->get();
		return $query->result_array();
	}

	function get_uaa_list($attendance_date,$dep='',$emp_id=''){
	
		$start_date = date('Y-m-d',strtotime($attendance_date['start_date']));
		$end_date = date('Y-m-d',strtotime($attendance_date['end_date']));

		$this->db->join('xin_employees',"xin_employees.user_id=xin_attendance_time.employee_id");
		$this->db->join('xin_office_location',"xin_employees.office_location_id=xin_office_location.location_id");
		$this->db->where('xin_employees.is_active', 1);
		if($dep != ''){
			$this->db->where('xin_employees.department_id', $dep);
		}
		if($emp_id != ''){
			$this->db->where('xin_employees.user_id', $emp_id);
		}
		$this->db->where('xin_employees.residing_country', 224);
		$this->db->where('attendance_date >=', $start_date);
		$this->db->where('attendance_date <=', $end_date);
		
		$this->db->select('*');
		$this->db->from('xin_attendance_time');
		$this->db->where('xin_attendance_time.attendance_status','Absent');
		
		$query = $this->db->get();
		return $query->result();

	}

	function get_passport_request($id='',$emp_id='',$list='',$type='',$status=''){

		$this->db->select('*');
		$this->db->from('xin_employee_doc_request');
		if($id != ''){
			$this->db->where('id',$id);
		}else{

			if($type == 'passport_request'){
				$this->db->where('request_type','passport_request');
			}else{
				$this->db->where('request_type !=','passport_request');
			}
		}

		if($emp_id != ''){
			$this->db->where('user_id',$emp_id);
			if($list == ''){
				if($type == 'passport_request'){
					$status_list = array('Request raised', 'Approve', 'Ready for shipment', 'Delivered');
					$this->db->where_in('status', $status_list );
					// $this->db->where('status',$status);
				}else{
					$this->db->where('status',$status);
				}
			}else{
				$this->db->order_by('id','desc');
			}
		}
		$query = $this->db->get();		
		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return '';
		}
	}

	public function get_passport_request_reporting_manager($emp_id,$role_name,$type=''){

		$session = $this->session->userdata('username');
		
		if($type == 'passport_request'){

			$condition = "where edr.request_type =" . "'" . $type . "'";

			if($role_name == AD_ROLE || (in_array('request-passport-delete',role_resource_ids()) && in_array('request-passport-view',role_resource_ids())) ) {
				$query = $this->db->query("select * from xin_employees as emp inner join xin_employee_doc_request as edr on emp.user_id=edr.user_id $condition ORDER BY edr.id desc");

			}else{
				$query = $this->db->query("select * from xin_employees as emp inner join xin_employee_doc_request as edr on emp.user_id=edr.user_id $condition and emp.user_id = $emp_id or emp.reporting_manager =  $emp_id ORDER BY edr.id desc");
			}

		}else{
			$condition = "where edr.request_type != 'passport_request'";

			if($role_name == AD_ROLE || in_array($session['user_id'], email_settings_data()) || (in_array('request-others-delete',role_resource_ids()) && in_array('request-others-view',role_resource_ids())) ) {
				$query = $this->db->query("select * from xin_employees as emp inner join xin_employee_doc_request as edr on emp.user_id=edr.user_id $condition ORDER BY edr.id desc");

			}else{
				$query = $this->db->query("select * from xin_employees as emp inner join xin_employee_doc_request as edr on emp.user_id=edr.user_id $condition and emp.user_id = $emp_id or emp.reporting_manager =  $emp_id ORDER BY edr.id desc");
			}
		}

		return $result= $query->result();

	}

	public function update_passport_request_status($data, $id){
		$this->db->where('id', $id);
		if( $this->db->update('xin_employee_doc_request',$data)) {
			return true;
		} else {
			return false; 
		}
	}

}
?>
