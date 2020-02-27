<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class xin_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	// get single location
	public function read_location_info($id) {
		$condition = "location_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_office_location');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	// is logged in to system
	public function is_logged_in($id)
	{
		$CI =& get_instance();
		$is_logged_in = $CI->session->userdata($id);
		return $is_logged_in;
	}

	public function get_empoloyee_byrole($user_id,$user_role_name) {
		$head_id=get_ceo_only();
		$r_m_role=R_M_ROLE;
		$HR_L_ROLE = unserialize(HR_L_ROLE);
		if($user_role_name == AD_ROLE || get_ceo_only()) {
			$query = $this->db->query("SELECT user_id,first_name,middle_name,last_name from xin_employees where is_active='1' order by first_name ASC");
			return $query->result();
		}else if($user_role_name == R_M_ROLE || reporting_manager_access()) {
			$query = $this->db->query("SELECT user_id,first_name,middle_name,last_name from xin_employees where reporting_manager='".$user_id."' AND is_active='1' order by first_name ASC");
			return $query->result();
		}else if ($user_role_name == 'Drivers Admin'){
			$query = $this->db->query("SELECT user_id,first_name,middle_name,last_name from xin_employees emp, xin_designations des where des.designation_id=emp.designation_id AND des.designation_name like '%driver%' AND is_active='1' order by first_name ASC");
			return $query->result();
		}
		else {
			return '';
		}
	}

	public function get_empoloyee_byrole_with_con($user_id,$user_role_name,$department_id='',$reporting_manager_id='') {
		$head_id = get_ceo_only();
		$r_m_role = R_M_ROLE;
		$HR_L_ROLE = unserialize(HR_L_ROLE);
		if($user_role_name == AD_ROLE || get_ceo_only()) {

			$con = '';
			$con1 = '';
			if(visa_wise_role_ids() != ''){

				$con = "INNER JOIN xin_employee_immigration ON xin_employee_immigration.employee_id = xin_employees.user_id";
				$con1 = "WHERE xin_employee_immigration.type = ".visa_wise_role_ids();
			}

			$query = $this->db->query("SELECT user_id,first_name,middle_name,last_name from xin_employees $con $con1 where is_active='1' order by first_name ASC");
			return $query->result();
		}else if($user_role_name == R_M_ROLE || reporting_manager_access() || hod_manager_access()) {
			
			$con_visa = '';
			$con_visa1 = '';
			if(visa_wise_role_ids() != ''){

				$con_visa = "INNER JOIN xin_employee_immigration ON xin_employee_immigration.employee_id = xin_employees.user_id";
				$con_visa1 = "AND xin_employee_immigration.type = ".visa_wise_role_ids();
			}

			if($department_id != ''){
				$con = "OR department_id='".$department_id."'";
			}elseif($reporting_manager_id != ''){
				$con = "OR reporting_manager='".$user_id."'";
			}else{
				$con = "";
			}
			$query = $this->db->query("SELECT user_id,first_name,middle_name,last_name from xin_employees $con_visa where user_id='".$user_id."' $con AND is_active='1' $con_visa1 order by first_name ASC");
			return $query->result();
		}else if ($user_role_name == 'Drivers Admin'){

			$con_visa = '';
			$con_visa1 = '';
			if(visa_wise_role_ids() != ''){

				$con_visa = "INNER JOIN xin_employee_immigration ON xin_employee_immigration.employee_id = xin_employees.user_id";
				$con_visa1 = "AND xin_employee_immigration.type = ".visa_wise_role_ids();
			}

			$query = $this->db->query("SELECT user_id,first_name,middle_name,last_name from xin_employees emp, xin_designations des $con_visa where des.designation_id=emp.designation_id AND des.designation_name $con_visa1 like '%driver%' AND is_active='1' order by first_name ASC");
			return $query->result();
		}
		else {
			return '';
		} 
	}

	// generate random string
	public function generate_random_string($length = 7) {
		$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	public function update_country_data($data,$country_id){

		$this->db->where('country_id', $country_id);
		$this->db->update('xin_countries', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function get_countries()
	{
		$query = $this->db->query("SELECT * from xin_countries where phonecode!=0");
		return $query->result();
	}

	// get single country
	public function read_country_info($id) {
		$condition = "country_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_countries');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	public function current_attendance_info($biometric_id,$emailid,$status,$ip_address = ''){
		$condition='';
		$emailid = strtolower($emailid);
		if($status=='D'){ // DIP drivers
			$condition = " where emp.email ='" . $emailid . "'";
		}
		// else if($status=='0' && ($ip_address == '192.168.1.22' || $ip_address == '192.168.1.21')){ // JLT employees
		// 	$condition = " where emp.is_active = 1 AND emp.office_location_id = 1 AND biometric.biometric_id ='".$biometric_id."'";
		// }
		// else if($status=='0' && $ip_address == '192.168.3.23'){ // DIERA employees
		// 	$condition = " where emp.is_active = 1 AND emp.office_location_id = 4 AND biometric.biometric_id ='".$biometric_id."'";
		// }
		// else if(($status=='P1' || $status=='P2') && ($ip_address == '192.168.0.4') ){ // DIP employees
		// 	$condition = " where emp.is_active = 1 AND emp.office_location_id = 3 AND biometric.biometric_id ='".$biometric_id."'";
		// }
		else{
			$condition = " where emp.is_active = 1 AND biometric.biometric_id ='".$biometric_id."'";
		}

		$query = $this->db->query("SELECT emp.is_break_included, emp.user_id,emp.office_shift_id,emp.first_name,emp.email,emp.middle_name,emp.last_name,emp.employee_id,biometric.biometric_id,emp.office_location_id,emp.department_id,emp.date_of_joining,emp.designation_id,emp.working_hours FROM xin_employees as emp left join xin_biometric_users_list as biometric on biometric.employee_id = emp.user_id  $condition limit 1");

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// get single user
	public function read_bus_lateness($atn_date,$location_id){
		$condition = "bus_late_date =" . "'" . $atn_date . "' AND location_id =" . "'" . $location_id . "'";
		$this->db->select('bus_late_date,bus_scheduled_time,bus_late_time');
		$this->db->from('xin_bus_late_timings');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		else{
			return null;
		}
	}

	public function read_user_info($id) {
		$condition = "user_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_employees');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function read_user_contacts_info($id) {
		$condition = "employee_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_employee_contacts');
		$this->db->where($condition);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return null;
		}
	}

	public function location_read_user_info($id) {
		$condition = "user_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_employees');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function add_schedule($data){
		$this->db->insert('xin_calendar_schedule', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function update_schedule($data,$schedule_id){
		$this->db->where('schedule_id', $schedule_id);
		$this->db->update('xin_calendar_schedule', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function get_my_schedule($user_id){
		$query=$this->db->query("SELECT * FROM `xin_calendar_schedule` WHERE `user_id` = '".$user_id."'");
		return $result=$query->result();
	}

	public function delete_my_schedule($delete_id){
		$this->db->where('schedule_id', $delete_id);
		if ($this->db->delete('xin_calendar_schedule')) {
			return true;
		} else {
			return false;
		}
	}

	public function read_schedule_information($id) {
		$condition = "schedule_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_calendar_schedule');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function get_attn_emp($user_id){
		$curmonth=date('Y-m');
		$prevmonth=date('m')-1;
		$prevmonth=date('Y').'-'.$prevmonth;
		$query=$this->db->query("SELECT * FROM `xin_attendance_time` WHERE `employee_id` = '31' AND (`attendance_date` LIKE '%".$prevmonth."%' OR `attendance_date` LIKE '%".$curmonth."%') order by attendance_date asc");
		return $query->result();
	}

	public function read_user_info_attendance($id,$department_value,$location_value,$visa_value='',$user_type=1) {
		$slug='';
		if($department_value!=0){
			$slug.=' AND emp.department_id="'.$department_value.'"';
		}
		if($location_value!=0){
			$slug.=' AND emp.office_location_id="'.$location_value.'"';
		}
		if($visa_value!='' && $visa_value!=0){
			$slug.= ' AND modul.type_id="'.$visa_value.'"';
		}

		if($id!='all'){
			$condition = " where emp.user_id =" . "'" . $id . "' $slug group by emp.user_id limit 1";
		}
		else{

			if($user_type == '1'){
				$condition = " where emp.is_active=1 $slug group by emp.user_id";
			}else{
				$condition = " where emp.is_active=0 $slug group by emp.user_id";
			}
		}
	  $sql = "SELECT emp.is_break_included, emp.user_id,emp.office_shift_id,emp.first_name,emp.email,emp.middle_name,emp.last_name,emp.employee_id,emp.office_location_id,GROUP_CONCAT(biometric.biometric_id) as biometric_id,emp.department_id,emp.date_of_joining,modul.type_name as visa_type,emp.designation_id,emp.working_hours,loc.country as country_id FROM xin_employees as emp left join xin_office_location as loc on loc.location_id=emp.office_location_id left join xin_employee_immigration as doc on doc.employee_id = emp.user_id AND doc.document_type_id=3 left join xin_module_types as modul on modul.type_id = doc.type INNER join xin_biometric_users_list as biometric on biometric.employee_id = emp.user_id $condition";
		$query = $this->db->query($sql);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function manual_attendance_list_by_employee($employee_id,$month_year) {
		$condition = " where emp.user_id =" . "'" . $employee_id . "'";	
		$query = $this->db->query("select c_s.start_date,c_s.end_date,c_s.start_time,c_s.end_time,c_s.total_hours,c_s.unique_code,emp.first_name,emp.middle_name,emp.last_name,emp.employee_id as e_id,emp.office_location_id,emp.department_id,emp.user_id,c_s.attendance_status,c_s.hr_head_status,c_s.reporting_manager_status from xin_manual_attendance as c_s left join xin_employees as emp on emp.user_id=c_s.employee_id  AND (c_s.start_date like '%".$month_year."%'  OR c_s.end_date like '%".$month_year."%') $condition ORDER BY c_s.manual_attendance_id DESC");
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function read_user_info_manual_attendance_list($id,$department_value,$ob_type,$location_value,$month_year,$sess_department) {
		$hod_id = hod_manager_access();
		$reporting_manager_id =rp_manager_access();	
		$slug='';
		if(in_array('31v',role_resource_ids())) {
			$slug.= "";
		}
		else if($hod_id!= ''){
			$slug.= " AND emp.department_id='".$sess_department."'";
		}
		else if($reporting_manager_id != ''){
			$slug.= " AND emp.reporting_manager='".$reporting_manager_id."'";
		}

		if($department_value!=0){
			$slug.=' AND emp.department_id="'.$department_value.'"';
		}

		if($location_value!=0){
			$slug.=' AND emp.office_location_id="'.$location_value.'"';
		}

		if($ob_type!=0){
			$slug.=' AND c_s.ob_type_id="'.$ob_type.'"';
		}

		if($id!='all'){
			$condition = " where emp.user_id =" . "'" . $id . "' $slug";
		}else{
			$condition = " where emp.is_active=1 $slug";
		}

		$con = '';
		$con1 = '';
		if(visa_wise_role_ids() != ''){

			$con = "INNER JOIN xin_employee_immigration ON xin_employee_immigration.employee_id = emp.user_id";
			$con1 = "AND xin_employee_immigration.type = ".visa_wise_role_ids();
		}
	
		$query = $this->db->query("select c_s.start_date,c_s.end_date,c_s.start_time,c_s.end_time,c_s.total_hours,c_s.unique_code,emp.first_name,emp.middle_name,emp.last_name,emp.employee_id as e_id,emp.office_location_id,emp.department_id,emp.user_id,c_s.attendance_status,c_s.hr_head_status,c_s.reporting_manager_status,mt.type_name
			from xin_manual_attendance as c_s left join xin_employees as emp on emp.user_id=c_s.employee_id 
			left join xin_module_types as mt on c_s.ob_type_id = mt.type_id
		 	AND (c_s.start_date like '%".$month_year."%'  OR c_s.end_date like '%".$month_year."%') $con $condition $con1 ORDER BY c_s.manual_attendance_id DESC");

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function read_al_expiry_leaves_list($employee_id,$department_value,$location_value,$adjust_type_id) {
		$slug='';
		if($department_value!=0){
			$slug.=' AND emp.department_id="'.$department_value.'"';
		}

		if($location_value!=0){
			$slug.=' AND emp.office_location_id="'.$location_value.'"';
		}

		if($adjust_type_id!=0){
			$slug.=' AND adj.adjust_type_id="'.$adjust_type_id.'"';
		}

		if($employee_id!='all'){
			$condition = " where emp.user_id =" . "'" . $employee_id . "' $slug";
		}else{
			$condition = " where emp.is_active=1 $slug";
		}

		$con = '';
		$con1 = '';
		if(visa_wise_role_ids() != ''){

			$con = "INNER JOIN xin_employee_immigration ON xin_employee_immigration.employee_id = adj.adjust_employee_id";
			$con1 = "AND xin_employee_immigration.type = ".visa_wise_role_ids();
		}
		 
		$query = $this->db->query("select adj.adjust_id,adj.adjust_days,adjust_description,mt.type_name,emp.first_name,emp.middle_name,emp.last_name,adj.created_at,ctd.first_name as c_first_name,ctd.middle_name as c_middle_name,ctd.last_name as c_last_name,emp.office_location_id,emp.department_id,adj.created_by from 	xin_employee_adjustments as adj left join xin_module_types as mt on mt.type_id=adj.adjust_type_id
									left join xin_employees as emp on emp.user_id=adj.adjust_employee_id
									left join xin_employees as ctd on ctd.user_id=adj.created_by $con $condition $con1 order by adj.adjust_id DESC");

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}


	public function read_user_info_attendance_shift_list($id,$department_value,$location_value,$sess_department) {
		$hod_id = hod_manager_access();
		$reporting_manager_id =rp_manager_access();	
		$slug='';
		if(in_array('34v',role_resource_ids())) {
			$slug.= "";
		}
		else if($hod_id!= ''){
			$slug.= " AND emp.department_id='".$sess_department."'";
		}
		else if($reporting_manager_id != ''){
			$slug.= " AND emp.reporting_manager='".$reporting_manager_id."'";
		}

		if($department_value!=0){
			$slug.=' AND emp.department_id="'.$department_value.'"';
		}

		if($location_value!=0){
			$slug.=' AND emp.office_location_id="'.$location_value.'"';
		}

		if($id!='all'){
			$condition = " where emp.user_id =" . "'" . $id . "' $slug";
		}else{
			$condition = " where emp.is_active=1 $slug";
		}

		$con = '';
		$con1 = '';
		if(visa_wise_role_ids() != ''){

			$con = "INNER JOIN xin_employee_immigration ON xin_employee_immigration.employee_id = c_s.employee_id";
			$con1 = "AND xin_employee_immigration.type = ".visa_wise_role_ids();
		}

		$query = $this->db->query("select emp.is_break_included, c_s.shift_in_time,c_s.shift_out_time,emp.first_name,emp.middle_name,emp.last_name,emp.employee_id as e_id,emp.office_location_id,emp.department_id,emp.user_id,c_s.week_off from xin_change_schedule as c_s left join xin_employees as emp on emp.user_id=c_s.employee_id $con $condition $con1 ORDER BY c_s.change_schedule_id DESC");

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function read_user_info_attendance_shift($department_value,$location_value,$sess_department) {

		$hod_id = hod_manager_access();
		$reporting_manager_id =rp_manager_access();	
		$slug='';
		if(in_array('34v',role_resource_ids())) {
			$slug.= "";
		}
		else if($hod_id!= ''){
			$slug.= " AND emp.department_id='".$sess_department."'";
		}
		else if($reporting_manager_id != ''){
			$slug.= " AND emp.reporting_manager='".$reporting_manager_id."'";
		}

		if($department_value!=0){
			$slug.=' AND emp.department_id="'.$department_value.'"';
		}

		if($location_value!=0){
			$slug.=' AND emp.office_location_id="'.$location_value.'"';
		}

		$con = '';
		$con1 = '';
		if(visa_wise_role_ids() != ''){

			$con = "INNER JOIN xin_employee_immigration ON xin_employee_immigration.employee_id = emp.user_id";
			$con1 = "AND xin_employee_immigration.type = ".visa_wise_role_ids();
		}

		$query = $this->db->query("SELECT emp.employee_id,emp.first_name,emp.middle_name,emp.last_name,emp.user_id,shift.shift_in_time,shift.shift_out_time,shift.week_off
    FROM xin_employees as emp left join xin_office_default_shift as shift on shift.location_id=emp.office_location_id AND shift.department_id=emp.department_id
    WHERE NOT EXISTS(SELECT c_s.employee_id
                         FROM xin_change_schedule as c_s
                         WHERE c_s.employee_id = emp.user_id) AND emp.is_active=1  $slug");

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function read_user_info_manual_attendance_shift($department_value,$location_value) {
		$loc='';
		$dep='';
		if($location_value!=''){
			$loc=" AND emp.office_location_id='".$location_value."'";
		}

		if($department_value!=0){
			$dep=" AND emp.department_id='".$department_value."'";
		}

		$query = $this->db->query("SELECT emp.reporting_manager, emp.employee_id,emp.first_name,emp.middle_name,emp.last_name,emp.user_id
    FROM xin_employees as emp where emp.is_active=1  $dep $loc");

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function getEmployeesLevel($slug='',$reporting_manager_id='',$department_id='') {			
		$join = '';
		if($slug == 'hod'){
			$join= " AND emp.department_id='".$department_id."'";
		}
		else if($slug == 'reportingmanager'){
			$join= " AND emp.reporting_manager='".$reporting_manager_id."'";
		}

		$con = '';
		$con1 = '';
		if(visa_wise_role_ids() != ''){

			$con = "INNER JOIN xin_employee_immigration ON xin_employee_immigration.employee_id = emp.user_id";
			$con1 = "AND xin_employee_immigration.type = ".visa_wise_role_ids();
		}

		$query = $this->db->query("SELECT emp.employee_id, concat(emp.first_name,' ',emp.middle_name,' ',emp.last_name) as full_name,emp.user_id FROM xin_employees as emp $con where emp.is_active=1 $con1 $join");

		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return null;
		}
	}

	// get single user > by email
	public function read_user_info_byemail($email) {
		$condition = "email =" . "'" . $email . "'";
		$this->db->select('*');
		$this->db->from('xin_employees');
		$this->db->where($condition);
		$this->db->limit(1);
		return $query = $this->db->get();
		//return $query->num_rows();
	}

	// get last user attendance > check if loged in-
	public function attendance_time_checks($id) {
		$session = $this->session->userdata('username');
		return $query = $this->db->query("SELECT * FROM xin_attendance_time where `employee_id` = '".$id."' and clock_out = '' order by time_attendance_id desc limit 1");
	}

	// get single user > by designation
	public function read_user_info_bydesignation($id) {
		$condition = "designation_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_employees');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	// get single user > by designation
	public function read_user_info_bydepartment($id,$country_id) {
		$condition = "emp.department_id =" . "'" . $id . "' and emp.is_active=1 AND loc.country='".$country_id."' AND emp.email!=''";
		$this->db->select('emp.first_name,emp.last_name,emp.email,dep.department_name');
		$this->db->from('xin_employees as emp');
		$this->db->join('xin_office_location as loc','loc.location_id=emp.office_location_id','left');
		$this->db->join('xin_departments as dep','dep.department_id=emp.department_id','left');
		$this->db->where($condition);
		$query = $this->db->get();
		return $query->result();
	}

	public function read_user_info_bycountry($country_id) {
		$condition = "emp.is_active=1 AND loc.country='".$country_id."' AND emp.email!=''";
		$this->db->select('emp.first_name,emp.last_name,emp.email,dep.department_name');
		$this->db->from('xin_employees as emp');
		$this->db->join('xin_office_location as loc','loc.location_id=emp.office_location_id','left');
		$this->db->join('xin_departments as dep','dep.department_id=emp.department_id','left');
		$this->db->where($condition);
		$query = $this->db->get();
		return $query->result();
	}

	// get single company
	public function read_company_info($id) {
		$condition = "company_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_companies');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	//check_notification_view
	/*public function check_notification_view($user_id,$user_role_name,$count){
	$session = $this->session->userdata('username');
	$md_id=$session['user_id'];
	$c='';
    if($count!=''){
		$c=' limit 5';
	}
	$r_m_role=R_M_ROLE;
	$HR_L_ROLE = unserialize(HR_L_ROLE);

	$columns='lv.*,emp.first_name,emp.middle_name,emp.last_name,emp.user_id';
	if($user_role_name == AD_ROLE) {
		$query1= $this->db->query("SELECT $columns from xin_employees as emp join xin_leave_applications as lv on lv.employee_id=emp.user_id where (lv.hr_notification=1) AND (lv.reporting_manager_notification!=1) group by lv.applied_on $c");
		return @$query1->result();
	}
	else if($user_role_name == R_M_ROLE || reporting_manager_access()) {
				$query = $this->db->query("SELECT $columns from xin_employees as emp join xin_leave_applications as lv on lv.employee_id=emp.user_id where (emp.reporting_manager='".$user_id."' and lv.reporting_manager_notification=1) group by lv.applied_on UNION ALL SELECT $columns from xin_employees as emp join xin_leave_applications as lv on lv.employee_id=".$md_id." where lv.employee_notification=1 group by lv.applied_on $c ");
				return $query->result();

	}else if(($user_role_name != R_M_ROLE || $user_role_name != AD_ROLE) && (in_array($user_role_name,$HR_L_ROLE) || (in_array('32m',role_resource_ids())))){
		$query2 = $this->db->query("SELECT $columns from xin_employees as emp join xin_leave_applications as lv on lv.employee_id=emp.user_id where lv.hr_notification=1 group by lv.applied_on UNION ALL SELECT $columns from xin_employees as emp join xin_leave_applications as lv on lv.employee_id=".$md_id." where lv.employee_notification=1 group by lv.applied_on $c");
		return $query2->result();
	}
	else if($user_role_name == E_M_ROLE){
		$query3 = $this->db->query("SELECT $columns from xin_employees as emp join xin_leave_applications as lv on lv.employee_id=emp.user_id where lv.employee_notification=1 and lv.employee_id='".$user_id."' group by lv.applied_on $c");
		return $query3->result();
	}else{
		return '';
	}
	}*/

	//updated notification
	public function updated_notification($data,$leave_id){
		$this->db->where('leave_id', $leave_id);
		if( $this->db->update('xin_leave_applications',$data)) {
			return true;
		} else {
			return false;
		}
	}

	public function get_employee_officeshift($id) {
		return $query = $this->db->query("SELECT * from xin_attendance_time where employee_id = '".$id."'");
	}

	// get single user role info
	public function read_user_role_info($id) {
		$condition = "role_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_user_roles');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_all_active_users($office_location,$department) {
		$condition = "is_active = 1 AND department_id=".$department." AND office_location_id =" . $office_location;
		$this->db->select('user_id');
		$this->db->from('xin_employees');
		$this->db->where($condition);
		$query = $this->db->get();
		return $query->result();
	}

	public function isNull($item){
		return (is_null($item)? 0 : $item);
	}

	// get single user role info
	public function read_user_logs($currentmonth) {
		$condition = "updated_date like '%".$currentmonth."%'";
		$this->db->select('*');
		$this->db->from('xin_user_logs');
		$this->db->where($condition);
		$this->db->order_by('updated_date','desc');
		$query = $this->db->get();
		return $query->result();
	}

	public function read_system_logs($currentmonth,$system_module='') {
		$condition = "update_date like '%".$currentmonth."%'";
		if(!empty($system_module)){
			$condition .= "AND system_module like '%".$system_module."%'";
		}
		$this->db->select('*');
		$this->db->from('xin_system_logs');
		$this->db->where($condition);
		$this->db->order_by('update_date','desc');
		$query = $this->db->get();
		return $query->result();
	}

	// get setting info
	public function read_setting_info($id) {
		$condition = "setting_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_system_setting');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	// get setting layout
	public function system_layout() {
		// get details of layout
		$system = $this->read_setting_info(1);

		if($system[0]->compact_sidebar!=''){
			// if compact sidebar
			$compact_sidebar = 'compact-sidebar';
		} else {
			$compact_sidebar = '';
		}
		if($system[0]->fixed_header!=''){
			// if fixed header
			$fixed_header = 'fixed-header';
		} else {
			$fixed_header = '';
		}
		if($system[0]->fixed_sidebar!=''){
			// if fixed sidebar
			$fixed_sidebar = 'fixed-sidebar';
		} else {
			$fixed_sidebar = '';
		}
		if($system[0]->boxed_wrapper!=''){
			// if boxed wrapper
			$boxed_wrapper = 'boxed-wrapper';
		} else {
			$boxed_wrapper = '';
		}
		if($system[0]->layout_static!=''){
			// if static layout
			$static = 'static';
		} else {
			$static = '';
		}
		return $layout = $compact_sidebar.' '.$fixed_header.' '.$fixed_sidebar.' '.$boxed_wrapper.' '.$static;
	}

	// get company setting info
	public function read_company_setting_info($id) {
		$condition = "company_info_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_company_info');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	// get title
	public function site_title() {
		$system = $this->read_setting_info(1);
		return $system[0]->application_name;
	}

	// get all companies
	public function get_companies()
	{
		$query = $this->db->query("SELECT * from xin_companies");
		return $query->result();
	}

	// get all leave applications
	public function get_leave_applications()
	{
		$query = $this->db->query("SELECT * from xin_leave_applications");
		return $query->result();
	}

	// get last 5 applications
	public function get_last_leave_applications()
	{
		$query = $this->db->query("SELECT * from xin_leave_applications order by leave_id desc limit 5");
		return $query->result();
	}

	//set currency sign
	public function currency_sign($number,$location_id='',$user_id='') {
		$system_setting = $this->read_setting_info(1);
		if($user_id!=''){
			$userquery = $this->db->query("SELECT currency_id from xin_employees WHERE user_id='".$user_id."' limit 1");
			$userresult=$userquery->row();
			if($userresult){
				$currency_id = $userresult->currency_id;
				$query = $this->db->query("SELECT cur.type_id,cur.type_name,cur.type_code,cur.type_symbol FROM `xin_module_types` cur WHERE cur.type_of_module='currency_type' AND cur.type_id='".$currency_id."' limit 1");
				$result=$query->row();
			}
		}
		/*if($location_id!='' && $location_id!=0){
        $query = $this->db->query("SELECT cur.currency_id,cur.name,cur.code,cur.symbol FROM `xin_currencies` cur left join xin_office_location loc on loc.currency_id=cur.currency_id WHERE loc.location_id='".$location_id."' limit 1");
  	    $result=$query->row();
        }*/
		if($system_setting[0]->show_currency=='code'){
			$ar_sc = explode(' -',$system_setting[0]->default_currency_symbol);

			if(@$result){
				$sc_show = @$result->type_code;
			}else{
				$sc_show = $ar_sc[0];
			}

		} else {
			$ar_sc = explode('- ',$system_setting[0]->default_currency_symbol);
			if(@$result){
				$sc_show = @$result->type_symbol;
			}else{
				$sc_show = $ar_sc[1];
			}
		}

		if($system_setting[0]->currency_position=='Prefix'){
			$sign_value = $sc_show.''.$number;
		} else {
			$sign_value = $number.''.$sc_show;
		}
		return $sign_value;
	}

	// get all locations
	public function all_locations($id='')
	{
		if($id==''){
			$query = $this->db->query("SELECT * from xin_office_location");
		}else{
			$query = $this->db->query("SELECT * from xin_office_location where country='".$id."'");
		}
		return $query->result();
	}

	public function getLocationsWithCountry()
	{
		$query = $this->db->query("SELECT c.country_name,l.* FROM `xin_office_location` l, xin_countries c WHERE c.country_id = l.country");
		return $query->result();
	}

	//set currency sign
	public function set_date_format_js() {
		// get details
		$system_setting = $this->read_setting_info(1);
		// date format
		if($system_setting[0]->date_format_xi=='d-m-Y'){
			$d_format = 'dd-mm-yy';
		} else if($system_setting[0]->date_format_xi=='m-d-Y'){
			$d_format = 'mm-dd-yy';
		} else if($system_setting[0]->date_format_xi=='d-M-Y'){
			$d_format = 'dd-M-yy';
		} else if($system_setting[0]->date_format_xi=='M-d-Y'){
			$d_format = 'M-dd-yy';
		}
		return $d_format;
	}

	public function read_designation_info($id) {
		$condition = "designation_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_designations');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}

	// get all employees
	public function all_employees()
	{	
		$con = '';
		$con1 = '';
		if(visa_wise_role_ids() != ''){

			$con = "INNER JOIN xin_employee_immigration ON xin_employee_immigration.employee_id = xin_employees.user_id";
			$con1 = "WHERE xin_employee_immigration.type = ".visa_wise_role_ids();
		}

		$query = $this->db->query("SELECT * from xin_employees $con $con1 order by first_name ASC");
		return $query->result();
	}

	public function all_employees_loc()
	{
		$query = $this->db->query("SELECT * from xin_employees where office_location_id NOT IN('1','3','4') order by first_name ASC");
		return $query->result();
	}

	public function all_employees_with_date_of_leaving()
	{
		$query = $this->db->query("SELECT * from xin_employees where date_of_leaving!='' order by first_name ASC");
		return $query->result();
	}

	public function all_active_employees()
	{	
		$con = '';
		$con1 = '';
		if(visa_wise_role_ids() != ''){

			$con = "INNER JOIN xin_employee_immigration ON xin_employee_immigration.employee_id = xin_employees.user_id";
			$con1 = "AND xin_employee_immigration.type = ".visa_wise_role_ids();
		}

		$query = $this->db->query("SELECT * from xin_employees $con where is_active=1 $con1 order by first_name ASC");
		return $query->result();
	}

	public function all_employees_in_active()
	{
		$query = $this->db->query("SELECT * from xin_employees where is_active!=1 order by first_name ASC");
		return $query->result();
	}
	public function all_employees_dept_head()
	{
		$query = $this->db->query("SELECT * from xin_employees order by first_name ASC"); //where user_role_id=20
		return $query->result();
	}

	//set currency sign
	public function set_date_format($date) {
		// get details
		$system_setting = $this->read_setting_info(1);
		// date formate
		if($system_setting[0]->date_format_xi=='d-m-Y'){
			$d_format = date("d-m-Y", strtotime($date));
		} else if($system_setting[0]->date_format_xi=='m-d-Y'){
			$d_format = date("m-d-Y", strtotime($date));
		} else if($system_setting[0]->date_format_xi=='d-M-Y'){
			$d_format = date("d-M-Y", strtotime($date));
		} else if($system_setting[0]->date_format_xi=='M-d-Y'){
			$d_format = date("M-d-Y", strtotime($date));
		} else if($system_setting[0]->date_format_xi=='F-j-Y'){
			$d_format = date("F-j-Y", strtotime($date));
		} else if($system_setting[0]->date_format_xi=='j-F-Y'){
			$d_format = date("j-F-Y", strtotime($date));
		} else if($system_setting[0]->date_format_xi=='m.d.y'){
			$d_format = date("m.d.y", strtotime($date));
		} else if($system_setting[0]->date_format_xi=='d.m.y'){
			$d_format = date("d.m.y", strtotime($date));
		} else {
			$d_format = $system_setting[0]->date_format_xi;
		}
		if($d_format!='01-Jan-1970'){
			return $d_format;
		}else{
			return 'N/A';
		}

	}

	// get all table rows
	public function all_policies() {
		$query = $this->db->query("SELECT * from xin_company_policy");
		return $query->result();
	}

	// Function to update record in table > company information
	public function update_company_info_record($data, $id){
		$this->db->where('company_info_id', $id);
		if( $this->db->update('xin_company_info',$data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table > company information
	public function update_setting_info_record($data, $id){
		$this->db->where('setting_id', $id);
		if( $this->db->update('xin_system_setting',$data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to add record in table
	public function add_backup($data){
		$this->db->insert('xin_database_backup', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// get all db backup/s
	public function all_db_backup() {
		return  $query = $this->db->query("SELECT * from xin_database_backup");
	}

	// Function to Delete selected record from table
	public function delete_single_backup_record($id){
		$this->db->where('backup_id', $id);
		$this->db->delete('xin_database_backup');

	}
	// Function to Delete selected record from table
	public function delete_all_backup_record(){
		$this->db->empty_table('xin_database_backup');

	}

	// get all email templates
	public function get_email_templates() {
		return  $query = $this->db->query("SELECT * from xin_email_template");
	}

	// get email template info
	public function read_email_template_info($id) {
		$condition = "template_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_email_template');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	public function read_email_template_info_bycode($name) {
		$condition = "template_code =" . "'" . $name . "'";
		$this->db->select('*');
		$this->db->from('xin_email_template');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	// Function to update record in table > email template
	public function update_email_template_record($data, $id){
		$this->db->where('template_id', $id);
		if( $this->db->update('xin_email_template',$data)) {
			return true;
		} else {
			return false;
		}
	}

	// functoin chat count
	function check_chat_counts($user_id,$today_date){
		$condition = "to_id =" . "'" . $user_id . "' AND notification=1 AND birthday_date='".$today_date."'";
		$this->db->select('*');
		$this->db->from('xin_chat_messages');
		$this->db->where($condition);
		$query = $this->db->get();
		return $query->result();
	}

	/*  ALL CONSTATNS */

	// get all table rows
	public function get_contract_types() {
		return  $query = $this->db->query("SELECT * from xin_module_types where type_of_module='contract_type'");
	}

	// get all table rows
	public function get_qualification_education() {
		return  $query = $this->db->query("SELECT * from xin_module_types where type_of_module='education_level'");
	}

	// get all table rows
	public function get_qualification_language() {
		return  $query = $this->db->query("SELECT * from xin_module_types where type_of_module='qualification_language'");
	}

	// get all table rows
	public function get_qualification_skill() {
		return  $query = $this->db->query("SELECT * from xin_module_types where type_of_module='qualification_skill'");
	}

	// get all table rows
	public function get_document_type() {
		return  $query = $this->db->query("SELECT * from xin_module_types where type_of_module='document_type'");
	}

	// get all table rows
	public function get_visa_under() {
		return  $query = $this->db->query("SELECT * from xin_module_types where type_of_module='visa_under'");
	}
	// get all table rows
	public function get_medical_card_type() {
		return  $query = $this->db->query("SELECT * from xin_module_types where type_of_module='medical_card_type'");
	}

	// get all table rows
	public function get_award_type() {
		return  $query = $this->db->query("SELECT * from xin_module_types where type_of_module='award_type'");
	}

	// get all table rows
	public function get_ob_type() {
		return  $query = $this->db->query("SELECT * from xin_module_types where type_of_module='ob_type'");
	}

	// get all table rows
	public function get_leave_type() {
		return  $query = $this->db->query("SELECT * from xin_module_types where type_of_module='leave_type'");
	}

	// get all table rows
	public function get_salary_type() {
		return  $query = $this->db->query("select s_ty.type_id, s_ty.type_name,s_ty.adjustment_type, (CASE WHEN s_ty.type_parent=0 THEN 'Parent' ELSE (select t.type_name from xin_salary_types t where s_ty.type_parent=t.type_id) END) as is_parent from xin_salary_types as s_ty");
	}

	// get all table rows
	public function get_warning_type() {
		return  $query = $this->db->query("SELECT * from xin_module_types where type_of_module='warning_type'");
	}

	// get all table rows
	public function get_termination_type() {
		return  $query = $this->db->query("SELECT * from xin_module_types where type_of_module='termination_type'");
	}

	// get all table rows
	public function get_expense_type() {
		return  $query = $this->db->query("SELECT * from xin_module_types where type_of_module='expense_type'");
	}

	// get all table rows
	public function get_job_type() {
		return  $query = $this->db->query("SELECT * from xin_module_types where type_of_module='job_type'");
	}
	public function get_company_type() {
		return  $query = $this->db->query("SELECT * from xin_module_types where type_of_module='company_type'");
	}

	// get all table rows
	public function get_exit_type() {
		return  $query = $this->db->query("SELECT * from xin_module_types where type_of_module='exit_type'");
	}

	// get all table rows
	public function get_tax_type() {
		return  $query = $this->db->query("SELECT * from xin_module_types where type_of_module='tax_type'");
	}

	// get all table rows
	public function get_travel_type() {
		return  $query = $this->db->query("SELECT * from xin_module_types where type_of_module='travel_arrangement_type'");
	}

	// get all table rows
	public function get_payment_method() {
		return  $query = $this->db->query("SELECT * from xin_module_types where type_of_module='payment_method'");
	}

	// get all table rows
	public function get_currency_types() {
		return  $query = $this->db->query("SELECT * from xin_module_types where type_of_module='currency_type'");
	}
	/*  ADD CONSTANTS */

	// Function to add record in table
	public function add_document_type($data){
		$this->db->insert('xin_module_types', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	/*  DELETE CONSTANTS */
	// Function to Delete selected record from table

	// Function to Delete selected record from table
	public function delete_document_type_record($id,$type_of_module){
		$this->db->where('type_id', $id);
		$this->db->where('type_of_module', $type_of_module);
		$this->db->delete('xin_module_types');
		return debug_error();
	}

	// get all last 5 employees
	public function last_four_employees()
	{
		$query = $this->db->query("SELECT * from xin_employees order by user_id desc limit 4");
		return $query->result();
	}

	// get all last jobs
	/*public function last_jobs()
	{
	  $query = $this->db->query("SELECT * FROM xin_job_applications order by application_id desc limit 4");
  	  return $query->result();
	}*/

	// get total number of salaries paid
	public function get_total_salaries_paid() {
		$query = $this->db->query("SELECT SUM(payment_amount) as paid_amount FROM xin_make_payment");
		return $query->result();
	}

	// get company wise salary > chart
	public function all_companies_chart()
	{
		// $query = $this->db->query("SELECT m.*, c.* FROM xin_make_payment as m, xin_companies as c where m.company_id = c.company_id group by m.company_id");
		$query = $this->db->query("SELECT c.* FROM xin_companies as c group by c.company_id");
		return $query->result();
	}

	// get company wise salary > chart > make payment
	public function get_company_make_payment($id) {
		//$query = $this->db->query("SELECT SUM(payment_amount) as paidAmount FROM xin_make_payment where company_id='".$id."'");
		$query = $this->db->query("SELECT SUM(st.gross_salary) as paidAmount FROM xin_salary_templates st left join xin_employees as emp on emp.user_id=st.employee_id where emp.company_id='".$id."'  and emp.company_id!=0");
		return $query->result();
	}

	// get all currencies
	public function get_currencies() {
		$query = $this->db->query("SELECT * from xin_module_types where type_of_module='currency_type'");
		return $query->result();
	}

	// get location wise salary > chart
	public function all_location_chart()
	{
		//$query = $this->db->query("SELECT m.*, l.* FROM xin_make_payment as m, xin_office_location as l where m.location_id = l.location_id group by m.location_id");
		$query = $this->db->query("SELECT c.* FROM xin_office_location as c group by c.location_id");
		return $query->result();
	}

	// get location wise salary > chart > make payment
	public function get_location_make_payment($id) {
		//$query = $this->db->query("SELECT SUM(payment_amount) as paidAmount FROM xin_make_payment where location_id='".$id."'");
		$query = $this->db->query("SELECT SUM(st.gross_salary) as paidAmount,count(emp.department_id) as total_count  FROM xin_salary_templates st left join xin_employees as emp on emp.user_id=st.employee_id where emp.office_location_id='".$id."' and emp.office_location_id!=0");
		return $query->result();
	}

	// get location wise salary > chart
	public function all_departments_chart()
	{
		//$query = $this->db->query("SELECT m.*, d.* FROM xin_make_payment as m, xin_departments as d where m.department_id = d.department_id group by m.department_id");
		$query = $this->db->query("SELECT c.* FROM xin_departments as c group by c.department_id");
		return $query->result();
	}

	public function all_departments_location()
	{

		$loc_query = $this->db->query("SELECT l.location_id,l.location_name FROM xin_office_location as l group by l.location_name ORDER BY (l.location_name = 'JLT') DESC");
		$loc_result=$loc_query->result();
		$array=[];
		if($loc_result){
			foreach($loc_result as $loc){
				$query = $this->db->query("SELECT c.department_id,c.department_name FROM xin_departments as c group by c.department_id");
				$result = $query->result();
				if($result){
					foreach($result as $res){
						$array[$loc->location_id.'-'.$res->department_id]=$loc->location_name.' - '.$res->department_name;
					}
				}
			}

		}
		return $array;
	}

	//all_employee_roles
	public function all_employee_roles(){
		$query=$this->db->query("SELECT emp.user_role_id,role.role_name FROM xin_employees as emp left join xin_user_roles as role on role.role_id=emp.user_role_id group by emp.user_role_id");
		return $query->result();
	}

	public function get_reporting_managers(){
		$query=$this->db->query("SELECT child.first_name,child.last_name,child.user_id FROM xin_employees parent, xin_employees child
								where parent.reporting_manager = child.user_id and parent.is_active = 1
								group by child.user_id
								limit 100");
		return $query->result();
	}

	// get department wise salary > chart > make payment
	public function get_department_make_payment($id) {
		//$query = $this->db->query("SELECT SUM(payment_amount) as paidAmount FROM xin_make_payment where department_id='".$id."'");
		$query = $this->db->query("SELECT SUM(st.gross_salary) as paidAmount,count(emp.department_id) as total_count FROM xin_salary_templates st left join xin_employees as emp on emp.user_id=st.employee_id where emp.department_id='".$id."' and emp.department_id!=0 and emp.is_active=1");
		return $query->result();
	}

	// get designation wise salary > chart
	public function all_designations_chart($id)
	{
		if($id!='' && $id!='top'){
			$id='where emp.department_id="'.$id.'" and  c.designation_id!=""';
			$val='';
		}else{
			$id='';
			//$val='limit 10';
			$val='order by convert(st.gross_salary,decimal) desc limit 10';
		}
		// $query = $this->db->query("SELECT m.*, d.* FROM xin_make_payment as m, xin_designations as d where m.designation_id = d.designation_id group by m.designation_id");
		//$query = $this->db->query("SELECT c.* FROM xin_designations  as c $id group by c.designation_name $val");
		$query=$this->db->query("SELECT st.gross_salary,c.designation_name,c.designation_id FROM xin_salary_templates st left join xin_employees as emp on emp.user_id=st.employee_id left join xin_designations as c on c.designation_id=emp.designation_id and emp.is_active=1 $id group by c.designation_name $val");
		return $query->result();
	}

	// get designation wise salary > chart > make payment
	public function get_designation_make_payment($id) {
		//$query = $this->db->query("SELECT SUM(payment_amount) as paidAmount FROM xin_make_payment where designation_id='".$id."'");
		$query = $this->db->query("SELECT SUM(st.gross_salary) as paidAmount,count(emp.department_id) as total_count FROM xin_salary_templates st left join xin_employees as emp on emp.user_id=st.employee_id where emp.designation_id='".$id."' and emp.designation_id!=0 and emp.is_active=1");
		return $query->result();
	}

	// get all departments
	public function get_all_departments() {
		$query = $this->db->get("xin_departments");
		return $query->num_rows();
	}

	public function get_all_departments_emp() {
		$query = $this->db->query("select dept.department_name,dept.department_id,count(emp.department_id) as total_count from xin_employees as emp left join xin_departments as dept on dept.department_id=emp.department_id group by dept.department_id");
		return $query->result();
	}

	public function get_all_location_emp() {
		$query = $this->db->query("select loc.location_name,count(emp.office_location_id) as total_count from  xin_office_location as loc left join xin_employees as emp on emp.office_location_id=loc.location_id group by loc.location_id");
		return $query->result();
	}
	public function get_all_company_emp() {
		$query = $this->db->query("select com.name as company_name,count(emp.company_id) as total_count from   xin_companies as com left join xin_employees as emp on emp.company_id=com.company_id group by com.company_id");
		return $query->result();
	}

	// get all projects
	public function get_all_projects() {
		$query = $this->db->get("xin_projects");
		return $query->num_rows();
	}

	// get all locations
	public function get_all_locations() {
		$query = $this->db->get("xin_office_location");
		return $query->num_rows();
	}

	// get all companies
	public function get_all_companies() {
		$query = $this->db->get("xin_companies");
		return $query->num_rows();
	}

	// get single record > db table > constant
	public function read_document_type($id,$type_of_module) {
		$condition = "type_id =" . "'" . $id . "' AND type_of_module='".$type_of_module."' ";
		$this->db->select('*');
		$this->db->from('xin_module_types');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	/* UPDATE CONSTANTS */
	// Function to update record in table
	public function update_document_type_record($data,$id){
		$this->db->where('type_id', $id);
		//$this->db->where('type_of_module','document_type');
		if( $this->db->update('xin_module_types',$data)) {
			return true;
		} else {
			return false;
		}
	}

	// get email template
	public function single_email_template($id){
		$query = $this->db->query("SELECT * from xin_email_template where template_id = '".$id."'");
		return $query->result();
	}

	// get single record > db table > email template
	public function read_email_template($id) {
		$condition = "template_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_email_template');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	// get current month attendance
	public function current_month_attendance() {
		$current_month = date('Y-m');
		$session = $this->session->userdata('username');
		$query = $this->db->query("SELECT * from xin_attendance_time where attendance_date like '%".$current_month."%' and `employee_id` = '".$session['user_id']."'  group by attendance_date");
		return $query->num_rows();
	}

	public function paystructure_notification() {
		$session = $this->session->userdata('username');
		$md_id=$session['user_id'];
		$query=$this->db->query("SELECT emp.user_id,emp.email FROM `xin_departments` as dep left join xin_employees as emp on emp.user_id=dep.employee_id WHERE dep.department_name='MD' limit 1");
		$result=$query->result();
		$notify_query=$this->db->query("SELECT st.*,emp.first_name,emp.middle_name,emp.last_name,emp.user_id FROM `xin_salary_templates` st left join xin_employees as emp on emp.user_id=st.employee_id WHERE st.is_approved=0");
		$notify_result=$notify_query->result();
		if($md_id==$result[0]->user_id){
			return $notify_result;
		}	else{
			return $notify_result;
		}
	}

	// get total employee awards
	public function total_employee_awards() {
		$session = $this->session->userdata('username');
		$id = $session['user_id'];
		$query = $this->db->query("SELECT * FROM xin_awards where employee_id IN($id) order by award_id desc");
		return $query->num_rows();
	}

	// get current employee awards
	public function get_employee_awards() {
		$session = $this->session->userdata('username');
		$id = $session['user_id'];
		$query = $this->db->query("SELECT * FROM xin_awards where employee_id IN($id) order by award_id desc");
		return $query->result();
	}

	// get user role > links > all
	public function user_role_resource($visa_wise = ''){
		// get session
		$session = $this->session->userdata('username');
		if(empty($session)){
			redirect('');
		}
		// get userinfo and role
		$user = $this->read_user_info($session['user_id']);
		$role_user = $this->read_user_role_info($user[0]->user_role_id);
		$custom_roles_ids=[];
		if($user[0]->custom_roles!=''){
			$custom_roles = json_decode($user[0]->custom_roles);
			$custom_roles_ids = explode(',',$custom_roles->role_resources);
		}

		if($visa_wise != ''){

			$role_resources_ids = $custom_roles->visa_wise_role;

		}else{

			$role_resources_ids = explode(',',$role_user[0]->role_resources);
			$role_resources_ids=array_merge($role_resources_ids,$custom_roles_ids);
		}
		
		return $role_resources_ids;
	}

	// get all opened tickets
	public function all_open_tickets() {
		$query = $this->db->query("SELECT * FROM xin_support_tickets WHERE ticket_status ='1'");
		return $query->num_rows();
	}

	// get all closed tickets
	public function all_closed_tickets() {
		$query = $this->db->query("SELECT * FROM xin_support_tickets WHERE ticket_status ='2'");
		return $query->num_rows();
	}

	//get salary types
	public function ready_salary_types(){
		$this->db->select('*');
		$this->db->where('type_parent',0);
		$this->db->from('xin_salary_types');
		$query = $this->db->get();
		return $query->result();
	}

	// Function to add record in table
	public function add_salary_type($data){
		$this->db->insert('xin_salary_types', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function get_action_type($parent){
		$this->db->select('action');
		$this->db->where('type_id',$parent);
		$this->db->where('type_parent',0);
		$this->db->from('xin_salary_types');
		$this->db->limit(1);
		$query = $this->db->get();
		$value = $query->row();
		return $value->action;
	}

	public function check_salary_type($type_name,$type_parent,$type_id){
		if($type_id==''){
			$condition = 'type_name ="'.$this->db->escape($type_name).'" AND type_parent="'.$type_parent.'"';
		}else{
			$condition = 'type_name ="'.$this->db->escape($type_name).'" AND type_parent="'.$type_parent.'" AND type_id!="'.$type_id.'"';
		}
		$this->db->select('type_name');
		$this->db->from('xin_salary_types');
		$this->db->where($condition);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function read_salary_type($id) {
		$condition = "type_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_salary_types');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();

		return $query->result();
	}

// Function to update record in table
	public function update_salary_type_record($data, $id){
		$this->db->where('type_id', $id);
		if( $this->db->update('xin_salary_types',$data)) {
			return true;
		} else {
			return false;
		}
	}

// Function to Delete selected record from table
	public function delete_salary_type_record($id){
		$this->db->where('type_id', $id);
		$this->db->delete('xin_salary_types');
	}

	public function check_unique_type_names($db_name,$field_name,$field_id,$name_value,$id_value,$type_of_module){
		if($id_value==''){
			$condition = "$field_name =" . "'" . $name_value . "'";
		}else{
			$condition = "$field_name =" . "'" . $name_value . "' AND $field_id !=" . "'" . $id_value . "'";
		}
		$this->db->select($field_name);
		$this->db->from($db_name);
		$this->db->where('type_of_module',$type_of_module);
		$this->db->where($condition);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function check_unique_names($db_name,$field_name,$field_id,$name_value,$id_value){
		if($id_value==''){
			$condition = "$field_name =" . "'" . $name_value . "'";
		}else{
			$condition = "$field_name =" . "'" . $name_value . "' AND $field_id !=" . "'" . $id_value . "'";
		}
		$this->db->select($field_name);
		$this->db->from($db_name);
		$this->db->where($condition);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function check_unique_location_department($location_id,$department_id,$default_id){
		if($default_id==''){
			$condition = "location_id =" . "'" . $location_id . "' AND department_id =" . "'" . $department_id . "'";
		}else{
			$condition = "location_id =" . "'" . $location_id . "' AND department_id =" . "'" . $department_id . "' AND office_shift_id !=" . "'" . $default_id . "'";
		}

		$this->db->select('location_id');
		$this->db->from('xin_office_default_shift');
		$this->db->where($condition);
		$query = $this->db->get();
		return $query->num_rows();
	}


	public function check_expiry_contracts($today_date,$date_after_twomonths){
		$query = $this->db->query("SELECT cont.*,ctype.type_name,emp.user_id,emp.employee_id,emp.first_name,emp.middle_name,emp.last_name,emp.email FROM `xin_employee_contract` as cont left join xin_employees as emp on emp.user_id=cont.employee_id left join xin_module_types as ctype on ctype.type_id=cont.contract_type_id AND ctype.type_of_module='contract_type' WHERE cont.to_date >='".$today_date."' AND cont.to_date <= '".$date_after_twomonths."'");
		return $query->result();
	}

	public function get_hr_mail_address(){
		$query = $this->db->query("select role.role_id,role.role_name,emp.first_name,emp.user_id,emp.email from xin_user_roles as role
		left join xin_employees as emp on emp.user_role_id=role.role_id  where role_resources like '%13e%' OR emp.custom_roles like '%13e%'");
		return $query->result();
	}

	public function get_hr_employee($hr_role_id){
		$query = $this->db->query("select emp.first_name,emp.middle_name,emp.last_name,emp.user_id,emp.email from xin_employees as emp where user_role_id='".$hr_role_id."' AND is_active=1");
		return $query->result();
	}
	public function check_expiry_documents($today_date,$date_after_onemonths,$date_after_twomonths){
		$query = $this->db->query("SELECT doc.*,docs.type_name,emp.user_id,emp.employee_id,emp.first_name,emp.middle_name,emp.last_name,emp.email FROM `xin_employee_immigration` as doc left join xin_employees as emp on emp.user_id=doc.employee_id left join xin_module_types as docs on docs.type_id=doc.document_type_id AND docs.type_of_module='document_type' WHERE  (docs.type_id = 2 AND doc.expiry_date >='".$today_date."' AND doc.expiry_date <= '".$date_after_twomonths."') OR (docs.type_id != 2 AND doc.expiry_date >='".$today_date."' AND doc.expiry_date <= '".$date_after_onemonths."'
       )");
		return $query->result();
	}

	public function missed_loginout_employees($previous_date){
		$query = $this->db->query("SELECT * from xin_attendance_time where attendance_date='".$previous_date."' and clock_in!='' and clock_out=''");
		$result=$query->result();
		return $result;
	}

	public function get_country_grouped(){
		$this->db->select('ctry.country_name,ctry.country_id');
		$this->db->from('xin_office_location as loc');
		$this->db->join('xin_countries as ctry','ctry.country_id=loc.country','left');
		$this->db->group_by('loc.country');
		$query = $this->db->get();
		return $query;
	}

	public function read_salary_fields($country_id){
		$this->db->select('*');
		$this->db->from('xin_salary_fields_bycountry');
		$this->db->order_by('salary_field_order');
		$this->db->where('country_id',$country_id);
		$query = $this->db->get();
		return $query->result();
	}

	public function insert_salary_fields($data){
		$this->db->insert('xin_salary_fields_bycountry', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function update_salary_fields($data,$id){
		$this->db->where('salary_field_id', $id);
		$this->db->update('xin_salary_fields_bycountry', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return true;
		}
	}

	public function biometric_users_list($employee_id,$department_value,$location_value) {
		$slug='';
		if($department_value!=0){
			$slug.=' AND emp.department_id="'.$department_value.'"';
		}

		if($location_value!=0){
			$slug.=' AND b_s.office_location_id="'.$location_value.'"';
		}

		if($employee_id!='all'){
			$condition = " where emp.user_id =" . "'" . $employee_id . "' $slug";
		}else{
			$condition = " where 1 $slug";
		}

		$con = '';
		$con1 = '';
		if(visa_wise_role_ids() != ''){

			$con = "INNER JOIN xin_employee_immigration ON xin_employee_immigration.employee_id = b_s.employee_id";
			$con1 = "AND xin_employee_immigration.type = ".visa_wise_role_ids();
		}
		
		$query = $this->db->query("select b_s.id,b_s.biometric_id,b_s.updated_date,loc.location_name,emp.first_name,emp.middle_name,emp.last_name,
																emp.employee_id
																from xin_biometric_users_list as b_s $con
																left join xin_office_location loc on loc.location_id=b_s.office_location_id
																left join xin_employees emp on emp.user_id = b_s.employee_id $condition $con1");

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}

	}

	public function concat_biometric_id($user_id){
		$query = $this->db->query("select GROUP_CONCAT(b_s.biometric_id) as biometric_id
		from xin_biometric_users_list as b_s
		left join xin_employees emp on emp.user_id = b_s.employee_id where b_s.employee_id='".$user_id."' limit 1");
		if ($query->num_rows() > 0) {
			$result= $query->result();
			return $result[0]->biometric_id;
		}
	}

	public function getModuleList($type_name){
		if($type_name!=''){
			$this->db->select('type_id,type_name');
			$this->db->from('xin_module_types');
			$this->db->where('type_of_module',$type_name);
			$query = $this->db->get();
			return $query->result_array();
		}
		else{
			return array();
		}
		
	}

	public function getActionList(){

		$this->db->select('system_module');
		$this->db->from('xin_system_logs');
		$this->db->group_by('system_module');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function all_leave_applications($id='',$start_date='',$end_date='',$status=''){
		
		$start_date = date('Y-m-d',strtotime($start_date));
		$end_date = date('Y-m-d',strtotime($end_date));
		
		if($status != 0){
			$status_con = "and xin_leave_applications.status=".$status;
		}else{
			$status_con = '';
		}

		if($id != 0){
			$condition = "and xin_leave_applications.from_date >= '".$start_date."' AND xin_leave_applications.to_date <= '".$end_date."'";
			$query=$this->db->query("select SUM(count_of_days) as count_sum, COUNT(*) as total_count, 
								leave_status_code, leave_type_id
								from xin_leave_applications 
								inner join xin_employees on 
								xin_employees.user_id = xin_leave_applications.employee_id 
								where xin_employees.department_id = $id $condition $status_con
								GROUP BY leave_status_code");
		}else{
			$condition = "where from_date >= '".$start_date."' AND to_date <= '".$end_date."'";
			$query=$this->db->query("select SUM(count_of_days) as count_sum, COUNT(*) as total_count, 
								leave_status_code, leave_type_id
								from xin_leave_applications $condition $status_con GROUP BY leave_status_code");
		}

		return $query->result();
	}

	public function all_leave_applications_per_employee($id='',$start_date='',$end_date='',$type_id='',$status='',$emp_id=''){
		
		$start_date = date('Y-m-d',strtotime($start_date));
		$end_date = date('Y-m-d',strtotime($end_date));
		if($id != 0){
			$con = "where emp.department_id=".$id;
		}else{
			$con = '';
		}

		if($status != 0){
			$status_con = "and la.status=".$status;
		}else{
			$status_con = '';
		}

		if($emp_id != 0){
			$emp_con = "and la.employee_id=".$emp_id;
		}else{
			$emp_con = '';
		}

		if($type_id != ''){

			if($type_id == 222){
				$con_type = "and la.leave_status_code like '%SL-UP%'";
			}elseif($type_id == 174){
				$con_type = "and la.leave_status_code not like '%SL-UP%' and la.leave_type_id = ".$type_id;
			}else{
				$con_type = "and la.leave_type_id IN (".$type_id.")";
			}
		}else{
			$con_type = '';
		}
		$condition = "and la.from_date >= '".$start_date."' AND la.to_date <= '".$end_date."'";
		$query = $this->db->query("select emp.*,SUM(la.count_of_days) as count_sum,la.leave_type_id,
									COUNT(la.leave_id) as leave_application_count
									from xin_employees as emp 
									inner join xin_leave_applications as la 
									on la.employee_id = emp.user_id $con $condition $con_type $status_con $emp_con
									GROUP BY la.employee_id");
		
		return $query->result();
	}

	public function get_leave_list_employees($dep,$start_date,$end_date){

		$start_date = date('Y-m-d',strtotime($start_date));
		$end_date = date('Y-m-d',strtotime($end_date));
		$status_con = "and la.status=2";

		if($id != 0){
			$con = "where emp.department_id=".$id;
		}else{
			$con = '';
		}

		$condition = "and la.from_date >= '".$start_date."' AND la.to_date <= '".$end_date."'";
		$query = $this->db->query("select *
									from xin_employees as emp 
									inner join xin_leave_applications as la 
									on la.employee_id = emp.user_id $con $condition $status_con
									GROUP BY la.employee_id");
		
		return $query->result();
	}

	public function checkEmailSettingsData($settings_type='',$settings_name=''){

		$this->db->select('*');
		$this->db->from('xin_settings');
		if($settings_type != ''){
			$this->db->where('settings_type',$settings_type);
		}
		if($settings_name != ''){
			$this->db->where('settings_name',$settings_name);
		}
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return '';
		}

	}

	public function update_email_settings($data, $id){
		$this->db->where('settings_id', $id);
		if( $this->db->update('xin_settings',$data)) {
			return true;
		} else {
			return false;
		}
	}

}
?>
