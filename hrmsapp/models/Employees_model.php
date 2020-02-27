<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class employees_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function get_employees_list($department_value =0,$location_value=0,$role_value=0,$medical_card_value='0') {
		if($department_value!=0){
			$this->db->where('department_id',$department_value);
		}
		if($location_value!=0){
			$this->db->where('office_location_id',$location_value);
		}
		if($role_value!=0){
			$this->db->where('user_role_id',$role_value);
		}


		if($medical_card_value!='0'){
			$this->db->where('xin_employee_immigration.type',$medical_card_value);

			$this->db->join('xin_employee_immigration', 'xin_employee_immigration.employee_id = xin_employees.user_id', 'left');
		}

		$this->db->join('xin_employee_immigration as doc', 'doc.employee_id = xin_employees.user_id AND doc.document_type_id=3', 'left');
		$this->db->join('xin_module_types as modul', 'modul.type_id = doc.type', 'left');


		$this->db->select('xin_employees.user_id,xin_employees.office_shift_id,xin_employees.employee_id,xin_employees.first_name,xin_employees.middle_name,xin_employees.last_name,xin_employees.email,xin_employees.user_role_id,xin_employees.is_active,xin_employees.designation_id,xin_employees.department_id,xin_employees.date_of_joining,modul.type_name as visa_type');
		return $this->db->get("xin_employees");
	}

	public function get_employees_list_table($department_value,$location_value,$role_value,$medical_card_value,$visa_value,$country_value='') {

		if(visa_wise_role_ids() != ''){

			$this->db->where('xin_employee_immigration.type',visa_wise_role_ids());
			$this->db->join('xin_employee_immigration', 'xin_employee_immigration.employee_id = xin_employees.user_id');
		}

		if($department_value!=0){
			$this->db->where('department_id',$department_value);
		}
		if($location_value!=0){
			$this->db->where('office_location_id',$location_value);
		}
		if($role_value!=0){
			$this->db->where('user_role_id',$role_value);
		}
		if($visa_value!=0){
			$this->db->where('modul.type_id',$visa_value);
		}

		if($country_value!=0){
			$this->db->where('residing_country',$country_value);
		}

		if($medical_card_value!='0'){
			$this->db->where('xin_employee_immigration.type',$medical_card_value);
			$this->db->join('xin_employee_immigration', 'xin_employee_immigration.employee_id = xin_employees.user_id', 'left');
		}

		$this->db->join('xin_employee_immigration as doc', 'doc.employee_id = xin_employees.user_id AND doc.document_type_id=3', 'left');
		$this->db->join('xin_module_types as modul', 'modul.type_id = doc.type', 'left');
		$this->db->join('xin_office_location as loc', 'loc.location_id = office_location_id', 'left');


		$this->db->group_by('xin_employees.user_id');
		$this->db->select('location_name,xin_employees.user_id,xin_employees.employee_id,xin_employees.first_name,xin_employees.middle_name,xin_employees.last_name,xin_employees.email,xin_employees.contact_no,xin_employees.is_active,xin_employees.designation_id,xin_employees.department_id,xin_employees.date_of_joining,modul.type_name as visa_type');
		return $this->db->get("xin_employees");
	}

	public function get_employee_reports($department_value,$employee_status,$reporting_to,$country_value,$location_value,$role_value,$medical_card_value,$visa_value,$start,$length,$employee_id=''){

		$slug='1';
		$slug.=" AND emp_master_data.is_active='".$employee_status."'";
		if($department_value!=0){
			$slug.=" AND emp_master_data.department_id='".$department_value."'";
		}
		if($reporting_to!=0){
			$slug.=" AND emp_master_data.reporting_manager='".$reporting_to."'";
		}

		if($country_value!=0){
			$slug.=" AND loc.country='".$country_value."'";
		}

		if($location_value!=0){
			$slug.=" AND emp_master_data.office_location_id='".$location_value."'";
		}
		if($role_value!=0){
			$slug.=" AND emp_master_data.user_role_id=".$role_value;

		}
		$join_query='';
		if($medical_card_value!='0'){
			$slug.=" AND xin_employee_immigration.type='".$medical_card_value."'";
			$join_query.=" left join xin_employee_immigration on xin_employee_immigration.employee_id=emp_master_data.user_id";
		}
		$join_query.=" left join xin_employee_immigration as doc on doc.employee_id=emp_master_data.user_id AND doc.document_type_id=3";
		$join_query.=" left join xin_module_types as modul on modul.type_id=doc.type";
		if($visa_value!=0){
			$slug.=" AND modul.type_id='".$visa_value."'";

		}
		if($employee_id!=0){
			$slug.=" AND emp_master_data.user_id='".$employee_id."'";
		}

		$query = "SELECT 
				modul.type_name,
				sal.salary_with_bonus,
				emp_master_data.user_id,
				emp_master_data.first_name,
				emp_master_data.middle_name,
				emp_master_data.designation_id,
				emp_master_data.department_id,
				emp_master_data.last_name,
				emp_master_data.is_active,
				emp_master_data.employee_id,
				emp_master_data.email,
				emp_master_data.personal_email,
				emp_master_data.date_of_birth,
				emp_master_data.gender,
				emp_master_data.blood_group,
				emp_master_data.reporting_manager,
				emp_master_data.date_of_joining,
				emp_master_data.date_of_leaving,
				emp_master_data.marital_status,
				emp_master_data.contact_no,
				emp_master_data.`1c_employee_name`,
				emp_master_data.languages_known,
				emp_master_data.address as home_address1,
				emp_master_data.address2 as home_address2,
				emp_master_data.city as home_city,
				emp_master_data.area as home_area,
				emp_master_data.zipcode as home_zipcode,
				emp_master_data.residing_address1,
				emp_master_data.residing_address1,
				emp_master_data.residing_address2,
				emp_master_data.residing_city,
				emp_master_data.residing_area,
				emp_master_data.residing_zipcode,
				dept.department_name,
				desg.designation_name,
				roles.role_name,img.document_number,
				sft.shift_in_time,sft.shift_out_time,sft.week_off,
				loc.location_name,
				home_country.country_name as home_country,
				nationality_country.country_name as nationality,
				residing_country.country_name as residing_country, emp_bank_acc.account_title,emp_bank_acc.account_number,emp_bank_acc.bank_name,emp_bank_acc.bank_code,emp_bank_acc.bank_branch,
				emg_cont.relation as emergency_contact_relation,emg_cont.contact_name as relation_name,emg_cont.mobile_phone as relation_phone_no,
				emg_cont.address_1 as relationaddress1,emg_cont.address_2 as relationaddress2,emg_cont.city as relationcity,emg_cont.state as relationstate,
				emg_cont.zipcode as relation_zipcode,
				emg_country.country_name as relation_country FROM `xin_employees` as emp_master_data
				left join xin_departments dept on dept.department_id=emp_master_data.department_id 
				left join xin_employee_immigration img on img.employee_id=emp_master_data.user_id 
				
				left join xin_salary_templates as sal on sal.employee_id=emp_master_data.user_id  AND sal.effective_to_date=''

				left join xin_designations desg on desg.designation_id=emp_master_data.designation_id
				left join xin_user_roles as roles on roles.role_id=emp_master_data.user_role_id
				left join xin_office_default_shift as sft on sft.location_id=emp_master_data.office_location_id AND sft.department_id=emp_master_data.department_id
				left join xin_office_location as loc on loc.location_id=emp_master_data.office_location_id 
				left join xin_countries as home_country on home_country.country_id=emp_master_data.home_country 
				left join xin_countries as residing_country on  residing_country.country_id=emp_master_data.residing_country 
				left join xin_countries as nationality_country on nationality_country.country_id=emp_master_data.nationality 
				left join xin_employee_bankaccount as emp_bank_acc on emp_master_data.user_id=emp_bank_acc.employee_id and emp_bank_acc.is_primary=1
				left join xin_employee_contacts as emg_cont on emg_cont.employee_id=emp_master_data.user_id
				left join xin_countries as emg_country on emg_country.country_id=emg_cont.country
				$join_query 
				where $slug
				group by emp_master_data.user_id asc ";
		if($length>0)
			$query .= "LIMIT $length OFFSET $start";
		$query = $this->db->query($query);

		return $query;
	}

	public function get_employee_salary_by_type($user_id,$type){
		$query = $this->db->query("SELECT salary.salary_amount FROM `xin_employees` as emp 
				left join xin_employees_salary as salary on salary.salary_employee_id=emp.user_id
				left join xin_salary_fields_bycountry as salary_field on salary_field.salary_field_id=salary.salary_field_id 
				WHERE emp.user_id='".$user_id."' AND salary_field.salary_field_name = '".$type."'");

		return $query->result();
	}

	public function get_employee_document($user_id,$doc_id){
		$query = $this->db->query("SELECT doc.*,modul.type_name FROM `xin_employees` as emp 
				left join xin_employee_immigration as doc on doc.employee_id=emp.user_id AND doc.document_type_id= '".$doc_id."'
				left join xin_module_types as modul on modul.type_id=doc.type
				WHERE emp.user_id='".$user_id."' order by doc.immigration_id DESC");

		return $query->result();
	}

	public function get_employees() {
		$this->db->where('is_active',1);
		return $this->db->get("xin_employees");
	}

	public function get_employees_uae($location_id) {

		if($location_id!=''){
			$location = 'AND emp.office_location_id IN('.$location_id.')';
		}
		else{
			$location = 'AND emp.office_location_id IN(1,4)';
		}
		$condition = "(emp.employee_id!='' OR emp.employee_id!=0) AND emp.is_active=1 $location";
		$this->db->select('emp.is_break_included,emp.first_name,emp.middle_name,emp.email,emp.last_name,emp.user_id,emp.office_shift_id,emp.employee_id,emp.office_location_id,emp.department_id,emp.working_hours,biometric.biometric_id');
		$this->db->where($condition);
		$this->db->join('xin_biometric_users_list as biometric', 'biometric.employee_id = emp.user_id', 'INNER');
		$this->db->group_by('emp.user_id');
		$query=$this->db->get("xin_employees as emp");
		return $query;		
	}

	public function get_employees_by_id($ids = []) {
		$this->db->where_in('user_id',$ids);
		return $this->db->get("xin_employees")->result_array();
	}
	
	public function getDocumentsNearbyExpiry(){
		$startDate = new DateTime();
		$endDate =  (new DateTime($startDate->format('Y-m-d')))->modify("+1 month")->format("Y-m-d");
		$startDate = (new DateTime($startDate->format('Y-m-d')))->format("Y-m-d");

		$sql = 'SELECT emp.user_id,emp.first_name,emp.last_name,d.designation_name,emp.email,doc.type_name,xe.expiry_date FROM `xin_employee_immigration` xe, xin_employees emp, xin_module_types doc, xin_designations d WHERE xe.employee_id = emp.user_id AND expiry_date > "'.$startDate.'" AND expiry_date < "'.$endDate.'" AND expiry_date <> "" AND document_type_id = doc.type_id AND emp.designation_id = d.designation_id AND emp.is_active = 1 ORDER BY expiry_date';
		$query = $this->db->query($sql);

		return $query->result();
	}

	public function checkVisaType($empId){
		$sql = "select type_name from xin_employee_immigration i, xin_module_types doc WHERE employee_id = ".$empId." AND i.document_type_id = 3 AND i.type = doc.type_id";
		$query = $this->db->query($sql);

		return (isset($query->result()[0])) ? $query->result()[0]->type_name : "N/A";
	}

	public function get_employees_payroll($location_value,$department_value) {

		if(visa_wise_role_ids() != ''){

			$this->db->where('xin_employee_immigration.type',visa_wise_role_ids());
			$this->db->join('xin_employee_immigration', 'xin_employee_immigration.employee_id = xin_employees.user_id');
		}

		$this->db->select('xin_employees.user_id,xin_employees.office_shift_id,xin_employees.employee_id,xin_employees.first_name,xin_employees.middle_name,xin_employees.last_name,xin_employees.email,xin_employees.user_role_id,xin_employees.is_active,xin_employees.designation_id,xin_employees.department_id');
		if($department_value!=0){
			$this->db->where('department_id',$department_value);
		}
		if($location_value!=0){
			$this->db->where('office_location_id',$location_value);
		}
		$this->db->where('is_active',1);

		return $this->db->get("xin_employees");
	}

	public function get_comments($birthday_id,$birthday_date,$parent) {
		if($parent==0){
			$p='AND parent=0';
		}else{
			$p='AND parent="'.$parent.'"';
		}
		$condition = "birthday_id =" . "'" . $birthday_id . "' AND birthday_date =" . "'" . $birthday_date . "' $p";
		$this->db->select('*');
		$this->db->from('xin_chat_messages');
		$this->db->where($condition);
		$this->db->order_by('message_id','asc');
		$query = $this->db->get();
		return $result=$query->result();

	}

	public function add_comment($data){
		$this->db->insert('xin_chat_messages', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function hide_comment_record($id){
		$data=array('status'=>0);
		$condition = "message_id =" . "'" . $id . "'";
		$this->db->where($condition);
		$this->db->update('xin_chat_messages',$data);
		return true;
	}

	public function get_employees_latest_login() {
		$this->db->order_by("last_login_date","desc");
		return $this->db->get("xin_employees");
	}

	public function get_document_image_name($document_id,$db,$type){

		if($type!='contract'){
			$condition = "immigration_id ="."'".$document_id."'";
			$this->db->select('document_file');
			$this->db->from($db);
			$this->db->where($condition);
			$this->db->limit(1);
			$query = $this->db->get();
			$result= $query->result_object();
			return $result[0]->document_file;}else{
			$condition = "contract_id ="."'".$document_id."'";
			$this->db->select('document_file');
			$this->db->from($db);
			$this->db->where($condition);
			$this->db->limit(1);
			$query = $this->db->get();
			$result= $query->result_object();
			return $result[0]->document_file;
		}

	}

	public function get_department_head_id($department_id)
	{
		$query = $this->db->query("SELECT employee_id as head_id FROM `xin_departments` WHERE department_id='".$department_id."'");
		return $query->result();
	}

	public function get_ceo_only()
	{
		$query = $this->db->query("SELECT employee_id as head_id,'CEO' as head_name FROM `xin_departments` WHERE department_name='MD'");
		return $query->result();
	}

	public function hr_ceo_heads()
	{
		$query = $this->db->query("SELECT employee_id as head_id,'HR Head' as head_name FROM `xin_departments` WHERE department_name='HRD' UNION
		SELECT employee_id as head_id,'CEO' as head_name FROM `xin_departments` WHERE department_name='MD'");
		return $query->result();
	}

	public function all_department_heads_with_it($department_id,$hr_head='',$head_id='',$type_of_exit,$add_ac_manager='')
	{

		if($add_ac_manager!=''){
			$add_ac_manager=" UNION (SELECT emp.user_id as head_id,'Accounts Manager' as head_name FROM `xin_designations` as des left join xin_employees as emp on emp.designation_id=des.designation_id WHERE des.designation_name='Accounts Manager'  LIMIT 1)";
		}

		if($type_of_exit!='Resignation45 ( + )'){
			$md="SELECT employee_id as head_id,'CEO' as head_name FROM `xin_departments` WHERE department_name='MD' UNION";
		}else{
			$md='';
		}

		if($hr_head==''){
			$query = $this->db->query("SELECT employee_id as head_id,'CFO' as head_name FROM `xin_departments` WHERE department_name='ACCD' UNION
			SELECT employee_id as head_id,'HR Head' as head_name FROM `xin_departments` WHERE department_name='HRD' UNION ".$md."
			SELECT employee_id as head_id,'IT Head' as head_name FROM `xin_departments` WHERE department_name='ITD' UNION
			SELECT employee_id as head_id,'Department Head' as head_name FROM `xin_departments` WHERE department_id='".$department_id."' $add_ac_manager");

			if($head_id!=''){
				$result=$query->result();
				if($result){

					foreach($result as $res){

						if($res->head_name==$head_id){

							return  array( (object) array('head_id'=>$res->head_id,'head_name'=>$res->head_name));
						}
					}

				}
			}
		}else{
			$query = $this->db->query("SELECT employee_id as head_id,'HR Head' as head_name FROM `xin_departments` WHERE department_name='HRD'");
		}
		return $query->result();
	}

	public function all_department_heads($department_id,$hr_head='',$head_id='',$type_of_approval='')
	{
		$add_ac_manager='';
		if($type_of_approval=='Leave Settlement' || $type_of_approval=='Leave Cash Conversion'){
			$add_ac_manager=" UNION (SELECT emp.user_id as head_id,'Accounts Manager' as head_name FROM `xin_designations` as des left join xin_employees as emp on emp.designation_id=des.designation_id WHERE des.designation_name='Accounts Manager'  LIMIT 1)";
		}

		if($hr_head==''){
			$query = $this->db->query("SELECT employee_id as head_id,'CFO' as head_name FROM `xin_departments` WHERE department_name='ACCD' UNION
			SELECT employee_id as head_id,'HR Head' as head_name FROM `xin_departments` WHERE department_name='HRD' UNION
			SELECT employee_id as head_id,'CEO' as head_name FROM `xin_departments` WHERE department_name='MD' UNION
			SELECT employee_id as head_id,'Department Head' as head_name FROM `xin_departments` WHERE department_id='".$department_id."' $add_ac_manager ");

			if($head_id!=''){
				$result=$query->result();
				if($result){

					foreach($result as $res){

						if($res->head_name==$head_id){

							return  array( (object) array('head_id'=>$res->head_id,'head_name'=>$res->head_name));
						}
					}

				}

			}
		}else{
			$query = $this->db->query("SELECT employee_id as head_id,'HR Head' as head_name FROM `xin_departments` WHERE department_name='HRD'");
		}
		return $query->result();
	}

	public function read_approval_status($id){
		$query = $this->db->query("SELECT * from xin_employees_approval where employee_id='".$id."' order by created_at ASC");
		return $query->result();
	}

	public function update_approvals($employee_id,$approval_head_id,$approval_status,$type_of_approval,$field_id){
		$emp_info = $this->Xin_model->read_user_info($employee_id);
		$emp_full_name = change_fletter_caps($emp_info[0]->first_name.' '.$emp_info[0]->last_name);
		$approved_date=date('Y-m-d H:i:s');
		$condition = "employee_id =" . "'" . $employee_id . "' AND type_of_approval='".$type_of_approval."' AND approval_head_id='".$approval_head_id."' AND field_id='".$field_id."'";
		$this->db->select('*');
		$this->db->from('xin_employees_approval');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		$result= $query->result();
		$app_status=$result[0]->approval_status;
		$status='<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><span>X</span><span class="sr-only">Close</span></button><span class="text-semibold">Sorry!</span> This link has been expired.</div>';
		if($app_status==0 || $app_status==2){
			$data=array('approval_status'=>$approval_status,'approved_date'=>$approved_date);
			$this->db->where($condition);
			$this->db->update('xin_employees_approval',$data);

			if($approval_status==1){
				$status='<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><span>X</span><span class="sr-only">Close</span></button><span class="text-semibold">Thank You!</span> You are approved the '.$type_of_approval.' of <span class="text-semibold">'.$emp_full_name.'</span>.</div>';

				$message1='approved';
			}else{
				$status='<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert"><span>X</span><span class="sr-only">Close</span></button><span class="text-semibold">Sorry!</span> You are declined the '.$type_of_approval.' of <span class="text-semibold">'.$emp_full_name.'</span>.</div>';
				$message1='declined';
			}
		}else{
			$status='<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><span>X</span><span class="sr-only">Close</span></button><span class="text-semibold">Sorry!</span> This link has been expired.</div>';
			$message1='expired';
		}

		return 	array('status'=>$status,'message1'=>$message1);

	}

	public function insert_approvals($employee_id,$field_id,$created_by,$all_department_heads,$type_of_approval,$month_year=''){
		if($all_department_heads){
			foreach($all_department_heads as $department_heads){
				$condition = "employee_id =" . "'" . $employee_id . "' AND type_of_approval='".$type_of_approval."' AND head_of_approval='".$department_heads->head_name."' AND field_id =" . "'" . $field_id . "'";
				$this->db->select('approval_id');
				$this->db->from('xin_employees_approval');
				$this->db->where($condition);
				$query = $this->db->get();
				if($query->num_rows()==0){
					$data=array('employee_id'=>$employee_id,'field_id'=>$field_id,'type_of_approval'=>$type_of_approval,'head_of_approval'=>$department_heads->head_name,'approval_head_id'=>$department_heads->head_id,'created_by'=>$created_by,'pay_date'=>$month_year);
					//$this->db->insert('xin_employees_approval',$data);
					$this->db->query('insert into xin_employees_approval (employee_id,field_id,type_of_approval,head_of_approval,approval_head_id,created_by,pay_date) values ("'.$employee_id.'","'.$field_id.'","'.$type_of_approval.'","'.$department_heads->head_name.'","'.$department_heads->head_id.'","'.$created_by.'","'.$month_year.'")');
				}else{
					$data=array('approved_date'=>'','approval_status'=>0,'created_by'=>$created_by);
					$condition = "employee_id =" . "'" . $employee_id . "' and head_of_approval ='".$department_heads->head_name."' and type_of_approval ='".$type_of_approval."' and pay_date ='".$month_year."'";
					$this->db->where($condition);
					$this->db->update('xin_employees_approval',$data);
				}

			}
			return TRUE;

		}
		else{
			return FALSE;
		}
	}

	public function first_no_logout_check($employee_id){
		$condition = "employee_id =" . "'" . $employee_id . "' AND clock_in!='' AND clock_out='' AND attendance_date!='".TODAY_DATE."'";
		$this->db->select('time_attendance_id');
		$this->db->from('xin_attendance_time');
		$this->db->where($condition);
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			return 1;
		} else {
			return 0;
		}

	}

	public function get_employees_with_biometric() {
		$condition = "(emp.employee_id!='' OR emp.employee_id!=0) AND emp.is_active=1";
		$this->db->select('emp.is_break_included,emp.first_name,emp.middle_name,emp.email,emp.last_name,emp.user_id,emp.office_shift_id,emp.employee_id,emp.office_location_id,emp.department_id,emp.working_hours,modul.type_name as visa_type,biometric.biometric_id');
		$this->db->where($condition);
		$this->db->join('xin_employee_immigration as doc', 'doc.employee_id = emp.user_id AND doc.document_type_id=3', 'left');
		$this->db->join('xin_module_types as modul', 'modul.type_id = doc.type', 'left');
		$this->db->join('xin_biometric_users_list as biometric', 'biometric.employee_id = emp.user_id', 'INNER');
		$this->db->group_by('emp.user_id');
		$query=$this->db->get("xin_employees as emp");
		return $query->result();
	}

	public function get_total_employees($status) {

		if($status=='active'){
			$this->db->where('is_active',1);
		}
		$query = $this->db->get("xin_employees");
		return $query->num_rows();
	}

	public function get_employees_who_missed_clock_in() {
		$dateRange1 = date('H:i', time() - 7200);
		$dateRange2 = date('H:i', time() - 5400);
		$currentDate = date('Y-m-d');
		$currentDay = date('l');
	
		$sql = "SELECT emp.user_id,emp.first_name,emp.last_name,emp.email,emp.personal_email,att.shift_start_time, att.shift_end_time,att.clock_in,att.clock_out,att.attendance_status,att.week_off
									from xin_employees emp, xin_attendance_time att, xin_office_location loc
									where emp.is_active = 1
									AND loc.location_id = emp.office_location_id
									AND emp.send_punch_reminder = 1 
									AND (emp.office_location_id = 1 OR emp.office_location_id = 3 OR emp.office_location_id = 4)
									AND att.employee_id = emp.user_id
									AND att.clock_in = ''
									AND (att.attendance_status = 'Absent' OR att.attendance_status = 'LT'  OR att.attendance_status = 'P')
									AND NOT att.week_off LIKE '%".$currentDay."%'
									AND att.attendance_date = '".$currentDate."'
									AND att.shift_start_time >= '".$dateRange1."'
									AND att.shift_start_time <= '".$dateRange2."'
									AND emp.user_id NOT IN (
										SELECT employee_id as user_id from xin_manual_attendance mt
										where '".$currentDate."' BETWEEN mt.start_date AND mt.end_date
									)
									AND emp.user_id NOT IN (
										SELECT employee_id as user_id FROM xin_leave_applications
										where '".$currentDate."' BETWEEN from_date AND to_date
									)
									AND emp.department_id NOT IN (
										SELECT department_id FROM xin_holidays 
										where ('".$currentDate."' BETWEEN start_date AND end_date) AND is_publish=1 AND country_id = loc.country
									)";					
		$query = $this->db->query($sql);
		return $query->result();
	}

	public function get_employees_who_missed_clock_out() {
		$dateRange1 = date('H:i', time() - 7200);
		$dateRange2 = date('H:i', time() - 5400);
		$currentDate = date('Y-m-d');
		$currentDay = date('l');

		$sql = "SELECT emp.user_id,emp.first_name,emp.last_name,emp.email,emp.personal_email,att.shift_start_time, att.shift_end_time,att.clock_in,att.clock_out,att.attendance_status,att.week_off
									from xin_employees emp, xin_attendance_time att, xin_office_location loc
									where emp.is_active = 1
									AND loc.location_id = emp.office_location_id
									AND emp.send_punch_reminder = 1 
									AND (emp.office_location_id = 1 OR emp.office_location_id = 3 OR emp.office_location_id = 4)
									AND att.employee_id = emp.user_id
									AND att.clock_out = ''
									AND (att.attendance_status = 'Absent' OR att.attendance_status = 'LT' OR att.attendance_status = 'P')
									AND NOT att.week_off LIKE '%".$currentDay."%'
									AND att.attendance_date = '".$currentDate."'
									AND att.shift_end_time >= '".$dateRange1."'
									AND att.shift_end_time <= '".$dateRange2."'
									AND emp.user_id NOT IN (
										SELECT employee_id as user_id from xin_manual_attendance mt
										where '".$currentDate."' BETWEEN mt.start_date AND mt.end_date
									)
									AND emp.user_id NOT IN (
										SELECT employee_id as user_id FROM xin_leave_applications
										where '".$currentDate."' BETWEEN from_date AND to_date
									)
									AND emp.department_id NOT IN (
										SELECT department_id FROM xin_holidays 
										where ('".$currentDate."' BETWEEN start_date AND end_date) AND is_publish=1 AND country_id = loc.country
									)";
		$query = $this->db->query($sql);
		return $query->result();
	}

	public function read_employee_information($id) {
		$condition = "user_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_employees');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	public function read_employee_contracts($emp_id) {
		$condition = "contract.employee_id =" . "'" . $emp_id . "'";
		$this->db->select_max('types.type_name');
		$this->db->from('xin_employee_contract as contract');
		$this->db->join('xin_module_types as types','types.type_id=contract.contract_type_id AND types.type_of_module="contract_type"','left');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		$result=$query->result();
		if ($query->num_rows() == 1) {
			return $result[0]->type_name;
		} else {
			return false;
		}
	}

	public function get_type_name($type_id) {
		$this->db->where('type_id',$type_id);
		$this->db->limit(1);
		$query = $this->db->get("xin_module_types");
		$result=$query->result();
		return @$result[0]->type_name;
	}

	public function read_employee_full_data($id){
		$query = $this->db->query("SELECT emp.is_break_included,emp.user_id,emp.first_name,emp.date_of_birth,emp.date_of_leaving,emp.office_location_id,emp.department_id,emp.date_of_joining,emp.middle_name,emp.last_name,emp.employee_id as employee_code,dep.department_name,emp.designation_id,desig.designation_name,contr.from_date as contract_date,modul.type_name as visa_type,modul.type_id as visa_id,sal.*,emp.working_hours FROM `xin_employees` as emp 
				left join xin_designations as desig on desig.designation_id=emp.designation_id 
				left join xin_departments as dep on dep.department_id=emp.department_id 
				left join xin_employee_contract as contr on contr.employee_id=emp.user_id 
				left join xin_employee_immigration as doc on doc.employee_id=emp.user_id AND doc.document_type_id=3
				left join xin_module_types as modul on modul.type_id=doc.type
				left join xin_salary_templates as sal on sal.employee_id=emp.user_id  AND sal.effective_to_date=''
				WHERE emp.user_id='".$id."'");
		return $query->result_object();
	}

	public function employee_full_data_approval($id){
		$query = $this->db->query("SELECT emp.*,dep.department_name,xin_countries.country_name,
				desig.designation_name FROM `xin_employees` as emp 
				left join xin_designations as desig on desig.designation_id=emp.designation_id 
				left join xin_departments as dep on dep.department_id=emp.department_id 
				left join xin_employee_contract as contr on contr.employee_id=emp.user_id 
				left join xin_employee_immigration as doc on doc.employee_id=emp.user_id
				left join xin_countries on xin_countries.country_id=emp.nationality
				WHERE emp.user_id='".$id."'");
		return $query->result_object();
	}

	public function dump_employee_record() {
		$query = $this->db->query("SELECT * FROM `table 51` where `COL 13`='DIP 2'");
		return $query->result_array();
	}

	public function read_employee_information_by_1cid($id) {
		$condition = "user_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_employees');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function emp_pay_roll($user_id){
		$condition = "employee_id =" . "'" . $user_id . "' AND effective_to_date=''";
		$this->db->select('*');
		$this->db->from('xin_salary_templates');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
			return $query->row();
		} else {
			return false;
		}

	}

	public function check_salary_template_isthere($data,$employee_id){
		//AND (agreed_bonus =" . "'" . $data['agreed_bonus'] . "'  OR agreed_bonus =" . "'" . number_format($data['agreed_bonus'],2,".",""). "')
		$condition = "employee_id =" . "'" . $employee_id . "' 
		AND (basic_salary =" . "'" . $data['basic_salary'] . "' OR basic_salary =" . "'" . number_format($data['basic_salary'],2,".",""). "') 
		AND (house_rent_allowance=" . "'" . $data['house_rent_allowance'] . "'  OR house_rent_allowance =" . "'" . number_format($data['house_rent_allowance'],2,".",""). "')  
		AND (travelling_allowance =" . "'" . $data['travelling_allowance'] . "'  OR travelling_allowance =" . "'" . number_format($data['travelling_allowance'],2,".",""). "')  
		AND (food_allowance =" . "'" . $data['food_allowance'] . "'  OR food_allowance =" . "'" . number_format($data['basic_salary'],2,".",""). "')  
		AND (bonus =" . "'" . $data['bonus'] . "'  OR bonus =" . "'" . number_format($data['bonus'],2,".",""). "') ";
		$this->db->select('*');
		$this->db->from('xin_salary_templates');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
			return $query->num_rows();
		} else {
			return 0;

		}
	}

	public function check_agency_fees_isthere($data,$employee_id){
		$condition = "adjustment_for_employee=" . "'" . $employee_id . "' AND adjustment_type =" . "'" . $data['adjustment_type'] . "' AND adjustment_name=" . "'" . $data['adjustment_name'] . "'";
		$this->db->select('*');
		$this->db->from('xin_salary_adjustments');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
			return $query->num_rows();
		} else {
			return 0;

		}
	}

	public function data_doc_isthere($data,$employee_id){
		$condition = "employee_id =" . "'" . $employee_id . "' AND document_type_id =" . "'" . $data['document_type_id'] . "'";
		$this->db->select('*');
		$this->db->from('xin_employee_immigration');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
			return $query->num_rows();
		} else {
			return 0;

		}
	}

	public function update_employee_attendance_status($user_id,$today_date,$real_status){
		if($real_status!='P' || $real_status!='LT'){
			$data=array('attendance_status' => $real_status);
			$condition = "employee_id =" . "'" . $user_id . "' and attendance_date ='".$today_date."'";//and clock_in ='".$check_in_time."'
			$this->db->where($condition);
			$this->db->update('xin_attendance_time',$data);
		}

	}

	public function check_if_already_insert($user_id,$today_date){ //,$check_in_time
		$condition = "employee_id =" . "'" . $user_id . "' and attendance_date ='".$today_date."' ";
		$this->db->select('time_attendance_id');
		$this->db->from('xin_attendance_time');
		$this->db->where($condition);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function get_this_month_birthday($thismonth,$today_d_only){
		$query = $this->db->query("SELECT user_id,first_name,middle_name,last_name,date_of_birth,profile_picture,gender,substr(date_of_birth,6) FROM `xin_employees` WHERE month(date_of_birth) IN ('".$thismonth."','".($thismonth+1)."','".($thismonth+1)."') AND `is_active` = 1 order by substr(date_of_birth,6)");
		return $query->result();
	}

	public function getemployeebydepatment($department_id,$user_id){
		$condition = "is_active =1 ";
		$this->db->select('first_name,last_name,middle_name,user_id');
		$this->db->from('xin_employees');
		$this->db->where($condition);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_biometric_attendance($emp_biometri_id,$office_location_id,$today_date,$shift_start='',$shift_end='',$week_off=''){
		$condition = '';
		$emp_biometri_id = array_unique(explode(",",$emp_biometri_id));
		// if($office_location_id == 1 || $office_location_id == 4){
		// 	$condition = ' and status = "0"';
		// }
		// else if($office_location_id == 3){
		// 	$condition = ' and (status = "P1" or status = "P2")';
		// }
		
		$emp_biometri_id=implode(',', array_map('quote', $emp_biometri_id));

	 	$sql = "SELECT `check_in_out_date`, `check_in_out_time`, `check_in_date`, `ip_address`, `process_date` FROM `xin_biometric_attendance_synch` WHERE biometric_id IN(".$emp_biometri_id.") and process_date = '" . $today_date . "' ".$condition." ORDER BY `check_in_date` ASC";
		/*if($emp_biometri_id == '0000000929'){
			$condition = ' ';
			$sql = "SELECT `check_in_out_date`, `check_in_out_time`, `check_in_date`, `ip_address`, `process_date` FROM `xin_biometric_attendance_synch` WHERE (biometric_id = '0000000929' OR biometric_id = '1270') and process_date = '" . $today_date . "' ".$condition." ORDER BY `check_in_date` ASC";
		}*/
		$query = $this->db->query($sql)	;
		$result = $query->result_array();
		return $result;
	}

	public function get_driver_attendance($email,$today_date){
		$query = $this->db->query("SELECT * FROM `xin_driver_attendance_synch` WHERE driver_email='".$email."' AND attendance_date='".$today_date."'");
		$result = $query->result_array();
		return $result;
	}

	public function get_biometric_attendance_dip($emp_biometri_id,$employee_id,$today_date){

		$condition = "(biometric_id =" . "'" . $emp_biometri_id . "' || biometric_id =" . "'" . $employee_id . "') and process_date =" . "'" . $today_date . "'";
		$this->db->select('check_in_out_date,check_in_out_time,check_in_date,ip_address,process_date,status,shiftstart,shiftend');
		$this->db->from('xin_biometric_attendance_synch');
		$this->db->order_by("check_in_out_time", "asc");
		$this->db->where($condition);
		$query = $this->db->get();
		return $query->result_array();

	}

	public function basic_info_progress($id){
		$query = $this->db->query("SELECT IF(`first_name`='',2,0) + IF(`employee_id`='',1,0) + IF(`date_of_joining`='',1,0) + IF(`user_role_id`='',1,0) + IF(`department_id`='',1,0) + IF(`designation_id`='',1,0) + IF(`email`='',2,0)  + IF(`nationality`='',1,0)  + IF(`working_hours`='',1,0)  + IF(`office_location_id`='',1,0) + IF(`address`='',7,0) + IF(`home_country`='',4,0)   + IF(`languages_known`='',1,0)  + IF(`reporting_manager`='0',1,0)  + IF(`contact_no`='',2,0) +  IF(`gender`='',1,0)  + IF(`date_of_birth`='',1,0)  + IF(`marital_status`='',1,0) AS EmptyCols from xin_employees WHERE `user_id` = ".$id);
		$result = $query->row();
		return $result->EmptyCols ;
	}

	public function info_progress($id,$column,$table){
		$condition = "employee_id =" . "'" . $id . "'";
		$this->db->select($column);
		$this->db->from($table);
		$this->db->where($condition);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function check_user_id($user_id){
		$condition = "user_id =" . "'" . $user_id . "'";
		$this->db->select('user_id');
		$this->db->from('xin_employees');
		$this->db->where($condition);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function check_email($email,$emp_id){
		if($emp_id==''){
			$condition = "email ='".$email."'";
		}else{
			$condition = "email ='".$email."' AND user_id!='".$emp_id."'";
		}
		$this->db->select('email');
		$this->db->from('xin_employees');
		$this->db->where($condition);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function check_employeeid($emp_id,$user_id){
		if($user_id==''){
			$condition = "employee_id =" . "'" . $emp_id . "'";
		}else{
			$condition = "employee_id =" . "'" . $emp_id . "' AND user_id !=" . "'" . $user_id . "'";
		}
		$this->db->select('employee_id');
		$this->db->from('xin_employees');
		$this->db->where($condition);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function check_biometricid($biometric_id,$user_id){
		if($user_id==''){
			$condition = "biometric_id =" . "'" . $biometric_id . "'";
		}else{
			$condition = "biometric_id =" . "'" . $biometric_id . "' AND user_id !=" . "'" . $user_id . "'";
		}
		$this->db->select('biometric_id');
		$this->db->from('xin_employees');
		$this->db->where($condition);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function get_employee_unique_id($emp_id){
		$condition = "employee_id =" . "'" . $emp_id . "'";
		$this->db->select('user_id,first_name,middle_name,last_name');
		$this->db->from('xin_employees');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result();

		return @$result;
	}

	public function get_employee_unique_name($name){
		$condition = "first_name =" . "'" . $name . "'";
		$this->db->select('first_name,user_id,employee_id,date_of_joining');
		$this->db->from('xin_employees');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->row();
		return @$result ;
	}

	public function add($data){
		$this->db->insert('xin_employees', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function get_user_action_id($id,$column,$table){
		$this->db->select('employee_id');
		$this->db->limit(1);
		$this->db->where($column, $id);
		$query = $this->db->get($table);
		$result = $query->row();
		return $result->employee_id ;
	}

	public function delete_record($id){
		$this->db->where('user_id', $id);
		$this->db->delete('xin_employees');

		$this->db->where('employee_id', $id);
		$this->db->delete('xin_employee_immigration');

		$this->db->where('employee_id', $id);
		$this->db->delete('xin_employee_contract');

		$this->db->where('employee_id', $id);
		$this->db->delete('xin_salary_templates');

		$this->db->where('adjustment_for_employee', $id);
		$this->db->delete('xin_salary_adjustments');

	}

	public function delete_passport_request($id){

		$this->db->where('id', $id);
		$this->db->delete('xin_employee_doc_request');

	}

	public function update_record($data, $id){
		$this->db->where('user_id', $id);
		if( $this->db->update('xin_employees',$data)) {
			return true;
		} else {
			return false;
		}
	}

	public function basic_info($data, $id){
		$this->db->where('user_id', $id);
		if( $this->db->update('xin_employees',$data)) {
			return true;
		} else {
			return false;
		}
	}

	public function change_password($data, $id){
		$this->db->where('user_id', $id);
		if( $this->db->update('xin_employees',$data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table > social_info
	public function social_info($data, $id){
		$this->db->where('user_id', $id);
		if($this->db->update('xin_employees',$data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table > profile picture
	public function profile_picture($data, $id){
		$this->db->where('user_id', $id);
		if( $this->db->update('xin_employees',$data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to add record in table > contact_info
	public function contact_info_add($data){
		$this->db->insert('xin_employee_contacts', $data);
		$dd=$this->db->error();
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table > contact_info
	public function contact_info_update($data, $id){
		$this->db->where('contact_id', $id);
		if( $this->db->update('xin_employee_contacts',$data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table > document_info_update
	public function img_document_info_update($data, $id){
		$this->db->where('immigration_id', $id);
		if( $this->db->update('xin_employee_immigration',$data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to add record in table > immigration info
	public function immigration_info_add($data){
		$this->db->insert('xin_employee_immigration', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// Function to add record in table > qualification_info_add
	public function qualification_info_add($data){
		$this->db->insert('xin_employee_qualification', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table > qualification_info_update
	public function qualification_info_update($data, $id){
		$this->db->where('qualification_id', $id);
		if( $this->db->update('xin_employee_qualification',$data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to add record in table > work_experience_info_add
	public function work_experience_info_add($data){
		$this->db->insert('xin_employee_work_experience', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table > work_experience_info_update
	public function work_experience_info_update($data, $id){
		$this->db->where('work_experience_id', $id);
		if( $this->db->update('xin_employee_work_experience',$data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to add record in table > bank_account_info_add
	public function bank_account_info_add($data){
		$this->db->insert('xin_employee_bankaccount', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table > bank_account_info_update
	public function bank_account_info_update($data, $id){
		$this->db->where('bankaccount_id', $id);
		if( $this->db->update('xin_employee_bankaccount',$data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to add record in table > contract_info_add
	public function contract_info_add($data){
		$this->db->insert('xin_employee_contract', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	//for current contact > employee
	public function check_employee_contact_current($id) {

		$condition = "employee_id =" . "'" . $id . "' and contact_type ='current'";
		$this->db->select('*');
		$this->db->from('xin_employee_contacts');
		$this->db->where($condition);
		$this->db->limit(1);
		return $query = $this->db->get();
	}

	//for permanent contact > employee
	public function check_employee_contact_permanent($id) {

		$condition = "employee_id =" . "'" . $id . "' and contact_type ='permanent'";
		$this->db->select('*');
		$this->db->from('xin_employee_contacts');
		$this->db->where($condition);
		$this->db->limit(1);
		return $query = $this->db->get();
	}

	// get current contacts by id
	public function read_contact_info_current($id) {

		$this->db->select('*');
		$this->db->where('contact_id', $id);
		$this->db->where('contact_type', 'current');
		$this->db->limit(1);// only apply if you have more than same id in your table othre wise comment this line
		$query = $this->db->get('xin_employee_contacts');
		$row = $query->row();
		return $row;

	}

	// update the record
	public function update_default_bankaccount($data,$bankaccount_id){
		if($bankaccount_id==''){
			$this->db->where('employee_id', $data['employee_id']);
		}else{
			$this->db->where('employee_id', $data['employee_id']);
			$this->db->where('bankaccount_id', $bankaccount_id);
		}
		if( $this->db->update('xin_employee_bankaccount',$data)) {
			return true;
		} else {
			return false;
		}
	}

	// get permanent contacts by id
	public function read_contact_info_permanent($id) {

		$this->db->select('*');
		$this->db->where('contact_id', $id);
		$this->db->where('contact_type', 'permanent');
		$this->db->limit(1);// only apply if you have more than same id in your table othre wise comment this line
		$query = $this->db->get('xin_employee_contacts');
		$row = $query->row();
		return $row;
	}

	// Function to update record in table > contract_info_update
	public function contract_info_update($data, $id){
		$this->db->where('contract_id', $id);
		if( $this->db->update('xin_employee_contract',$data)) {
			return true;
		} else {
			return false;
		}
	}

	public function all_companies() {
		$query = $this->db->query("SELECT company_id,name from xin_companies");
		return $query->result();
	}

	// get contacts
	public function set_employee_contacts($id) {
		$condition = "employee_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_employee_contacts');
		$this->db->where($condition);
		$this->db->limit(500);
		return $this->db->get();
	}

	// get immigration
	public function set_employee_immigration($id,$type_id='') {

		if($type_id!=''){
			$slug = " AND document_type_id='".$type_id."'";
		}else{
			$slug = " AND document_type_id!='7'";
		}

		$condition = "employee_id ='".$id."' $slug";

		$this->db->select('*');
		$this->db->from('xin_employee_immigration');
		$this->db->where($condition);
		$this->db->limit(500);
		return $this->db->get();
	}

	// get employee qualification
	public function set_employee_qualification($id) {

		$condition = "employee_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_employee_qualification');
		$this->db->where($condition);
		$this->db->limit(500);
		return $this->db->get();
	}

	// get employee work experience
	public function set_employee_experience($id) {

		$condition = "employee_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_employee_work_experience');
		$this->db->where($condition);
		$this->db->limit(500);
		return $this->db->get();
	}

	public function get_work_experience_for_awok($id) {

		$condition = "company_name like '%AWOK%' AND employee_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_employee_work_experience');
		$this->db->where($condition);
		$this->db->limit(500);
		return $this->db->get();
	}

	// get employee bank account
	public function set_employee_bank_account($id) {

		$condition = "employee_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_employee_bankaccount');
		$this->db->where($condition);
		$this->db->limit(500);
		return $this->db->get();
	}

	// get employee contract
	public function set_employee_contract($id) {

		$condition = "employee_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_employee_contract');
		$this->db->where($condition);
		$this->db->limit(500);
		return $this->db->get();
	}

	// contract employee
	public function read_contract_information($id) {
		$condition = "contract_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_employee_contract');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();

		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}

	// get all contract types
	public function all_contract_types() {
		$query = $this->db->query("SELECT * from xin_module_types where type_of_module='contract_type'");
		return $query->result();
	}

	// get all contracts
	public function all_contracts() {
		$query = $this->db->query("SELECT * from xin_employee_contract");
		return $query->result();
	}

	// get all document types
	public function all_document_types() {
		$query = $this->db->query("SELECT * from xin_module_types where type_of_module='document_type'");
		return $query->result();
	}

	public function check_doc_there($doc_id,$emp_id) {
		$query = $this->db->query("SELECT * from xin_employee_immigration where employee_id='".$emp_id."' AND document_type_id='".$doc_id."' AND (expiry_date >='".TODAY_DATE."' OR expiry_date='') ");

		return $result=$query->result();

	}

	// get all document types
	public function all_visa_under() {
		$query = $this->db->query("SELECT * from xin_module_types where type_of_module='visa_under'");
		return $query->result();
	}

	// get all document types
	public function all_medical_card_types() {
		$query = $this->db->query("SELECT * from xin_module_types where type_of_module='medical_card_type'");
		return $query->result();
	}

	// get all education level
	public function all_education_level() {
		$query = $this->db->query("SELECT * from xin_module_types where type_of_module='education_level'");
		return $query->result();
	}

	// get education level by id
	public function read_education_information($id) {
		$condition = "type_id =" . "'" . $id . "' AND type_of_module='education_level'";
		$this->db->select('*');
		$this->db->from('xin_module_types');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();

		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}

	// get all qualification languages
	public function all_qualification_language() {
		$query = $this->db->query("SELECT * from xin_module_types where type_of_module='qualification_language'");
		return $query->result();
	}

	// get languages by id
	public function read_qualification_language_information($id) {
		$condition = "type_id =" . "'" . $id . "'  AND type_of_module='qualification_language'";
		$this->db->select('*');
		$this->db->from('xin_module_types');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();

		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}

	// get all qualification skills
	public function all_qualification_skill() {
		$query = $this->db->query("SELECT * from xin_module_types where type_of_module='qualification_skill'");
		return $query->result();
	}

	// get qualification by id
	public function read_qualification_skill_information($id) {
		$condition = "type_id =" . "'" . $id . "' AND type_of_module='qualification_skill'";
		$this->db->select('*');
		$this->db->from('xin_module_types');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();

		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}

	// get contacts by id
	public function read_contact_information($id) {
		$condition = "contact_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_employee_contacts');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();

		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}

	// get documents by id
	public function read_imgdocument_information($id) {
		$condition = "immigration_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_employee_immigration');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();

		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}

	// get qualifications by id
	public function read_qualification_information($id) {
		$condition = "qualification_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_employee_qualification');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();

		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}

	// get qualifications by id
	public function read_work_experience_information($id) {
		$condition = "work_experience_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_employee_work_experience');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();

		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}

	// get bank account by id
	public function read_bank_account_information($id) {
		$condition = "bankaccount_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_employee_bankaccount');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}

	// Function to Delete selected record from table
	public function delete_contact_record($id){
		$this->db->where('contact_id', $id);
		$this->db->delete('xin_employee_contacts');
	}

	// Function to Delete selected record from table
	public function delete_imgdocument_record($id){
		$this->db->where('immigration_id', $id);
		$this->db->delete('xin_employee_immigration');

	}

	// Function to Delete selected record from table
	public function delete_qualification_record($id){
		$this->db->where('qualification_id', $id);
		$this->db->delete('xin_employee_qualification');

	}

	// Function to Delete selected record from table
	public function delete_work_experience_record($id){
		$this->db->where('work_experience_id', $id);
		$this->db->delete('xin_employee_work_experience');

	}

	// Function to Delete selected record from table
	public function delete_bank_account_record($id){
		$this->db->where('bankaccount_id', $id);
		$this->db->delete('xin_employee_bankaccount');

	}

	// Function to Delete selected record from table
	public function delete_contract_record($id){
		$this->db->where('contract_id', $id);
		$this->db->delete('xin_employee_contract');

	}

	// get document type by id
	public function read_document_type_information($id,$type_of_module) {

		$condition = "type_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_module_types');
		$this->db->where('type_of_module',$type_of_module);
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();

		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}

	//Return Receptionist Bio Metric Id
	public function get_bioidbyuser_id($user_id){
		$this->db->select('biometric_id');
		$this->db->limit(1);
		$this->db->where('user_id',$user_id);
		$query = $this->db->get('xin_employees');
		$result = $query->row();
		return $result->biometric_id;
	}

	public function get_insurance_type($type){
		$this->db->select('type_name');
		$this->db->limit(1);
		$this->db->where('type_id',$type);
		$query = $this->db->get('xin_module_types');
		$result = $query->row();
		return @$result->type_name;
	}

	//Check visa under
	public function check_visa_under($emp_id){
		$condition = "employee_id =" . "'" . $emp_id . "' AND document_type_id =" . "'3' AND (type =" . "'DMCC' OR type =" . "'LLC')";
		$this->db->select('*');
		$this->db->from('xin_employee_immigration');
		$this->db->where($condition);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function check_contract_isthere($contract_type_id,$emp_id){  //sdf
		$condition = "employee_id =" . "'" . $emp_id . "' AND contract_type_id =" . "'" . $contract_type_id . "'";
		$this->db->select('*');
		$this->db->from('xin_employee_contract');
		$this->db->where($condition);
		$query = $this->db->get();

		return $query->num_rows();
	}

	public function check_if_resign($user_id){
		$condition = "employee_id =" . "'" . $user_id . "'";
		$this->db->select('employee_id');
		$this->db->from('xin_employee_resignations');
		$this->db->where($condition);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function check_if_terminate($user_id){
		$condition = "employee_id =" . "'" . $user_id . "'";
		$this->db->select('employee_id');
		$this->db->from('xin_employee_terminations');
		$this->db->where($condition);
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	public function check_if_eos($user_id,$app_form){
		$condition = "employee_id =" . "'" . $user_id . "' AND approval_form='".$app_form."'";
		$this->db->select('employee_id');
		$this->db->from('xin_employee_exit');
		$this->db->where($condition);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function get_public_holiday($employee_id,$country_id,$department_id,$check_first_date){
		$condition = "is_publish = '1' AND country_id='".$country_id."' AND department_id='".$department_id."' AND  '".$check_first_date."' BETWEEN start_date and end_date";
		$this->db->select('holiday_id');
		$this->db->from('xin_holidays');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		$result=$query->result();
		return $result;
	}

	/*For Attendance New Calculation*/
	public function get_shifts_bydep_loc($user_id,$location_id,$department_id,$today_date){
		$result_ramadan_val='';
		/*Ramadan Date Check*/

		$condition_l = "location_id =" . "'" . $location_id . "'";
		$this->db->select('country,location_name');
		$this->db->from('xin_office_location');
		$this->db->where($condition_l);
		$this->db->limit(1);
		$query_l = $this->db->get();
		$result_l = $query_l->result();
		$country_id=$result_l[0]->country;



		$condition_ram = "is_publish = '1' AND country_id='".$country_id."' AND  '".$today_date."' BETWEEN start_date and end_date";
		$this->db->select('reduced_hours');
		$this->db->from('xin_ramadan_schedule');
		$this->db->where($condition_ram);
		$this->db->limit(1);
		$query_ram = $this->db->get();
		$result_ramadan = $query_ram->row();
		if($result_ramadan){
			$result_ramadan_val=@$result_ramadan->reduced_hours;
		}
		/*Ramadan Date Check*/

		/*$day_condition = "employee_id =" . "'" . $user_id . "' AND attendance_date =" . "'" . $today_date . "'";
		$this->db->select('shift_start_time,shift_end_time,week_off');
		$this->db->from('xin_attendance_time');
		$this->db->where($day_condition);
		$day_query = $this->db->get();
		$this->db->limit(1);
		$result_day_schedule = $day_query->row();

		if($result_day_schedule){
			return array('in_time'=> $result_day_schedule->shift_start_time,'out_time'=> $result_day_schedule->shift_end_time,'week_off'=> $result_day_schedule->week_off,'result_ramadan_val'=>$result_ramadan_val);

		}else{*/


		$condition = "employee_id =" . "'" . $user_id . "' AND location_id =" . "'" . $location_id . "' AND department_id =" . "'" . $department_id . "'";
		$this->db->select('shift_in_time,shift_out_time,week_off');
		$this->db->from('xin_change_schedule');
		$this->db->where($condition);
		$query = $this->db->get();
		$this->db->limit(1);
		$result_change_schedule = $query->row();
		if($result_change_schedule){
			$shift_out_time=$result_change_schedule->shift_out_time;
			return array('in_time'=>$result_change_schedule->shift_in_time,'out_time'=>$shift_out_time,'week_off'=>$result_change_schedule->week_off,'result_ramadan_val'=>$result_ramadan_val);
		}else{
			$condition1 = "location_id =" . "'" . $location_id . "' AND department_id =" . "'" . $department_id . "'";
			$this->db->select('shift_in_time,shift_out_time,week_off');
			$this->db->from('xin_office_default_shift');
			$this->db->where($condition1);
			$query1 = $this->db->get();
			$this->db->limit(1);
			$result_default_shift = $query1->row();

			if($result_default_shift){
				$shift_out_time=$result_default_shift->shift_out_time;

				return array('in_time'=>$result_default_shift->shift_in_time,'out_time'=>$shift_out_time,'week_off'=>$result_default_shift->week_off,'result_ramadan_val'=>$result_ramadan_val);
			}
			else{
				$shift_out_time=DEFAULT_OUT_TIME;
				return array('in_time'=>DEFAULT_IN_TIME,'out_time'=>$shift_out_time,'week_off'=>DEFAULT_WEEK_OFF,'result_ramadan_val'=>$result_ramadan_val);
			}


		}

		/*}*/


	}
	
	public function get_shifts_bydep_loc_copy($user_id,$location_id,$department_id,$today_date){
		$result_ramadan_val='';
		/*Ramadan Date Check*/
		$condition_l = "location_id =" . "'" . $location_id . "'";
		$this->db->select('country,location_name');
		$this->db->from('xin_office_location');
		$this->db->where($condition_l);
		$this->db->limit(1);
		$query_l = $this->db->get();
		$result_l = $query_l->result();
		$country_id=$result_l[0]->country;

		$condition_ram = "is_publish = '1' AND country_id='".$country_id."' AND  '".$today_date."' BETWEEN start_date and end_date";
		$this->db->select('reduced_hours');
		$this->db->from('xin_ramadan_schedule');
		$this->db->where($condition_ram);
		$this->db->limit(1);
		$query_ram = $this->db->get();
		$result_ramadan = $query_ram->row();
		if($result_ramadan){
			$result_ramadan_val=@$result_ramadan->reduced_hours;
		}
		/*Ramadan Date Check*/

		$condition = "employee_id =" . "'" . $user_id . "' AND location_id =" . "'" . $location_id . "' AND department_id =" . "'" . $department_id . "'";
		$this->db->select('shift_in_time,shift_out_time,week_off');
		$this->db->from('xin_change_schedule');
		$this->db->where($condition);
		$query = $this->db->get();
		$this->db->limit(1);
		$result_change_schedule = $query->row();

		if($result_change_schedule){
			/*Ramdan Timing*/
			if($result_ramadan_val!=''){
				$actual_shift_out_time = new DateTime($result_change_schedule->shift_out_time);
				$ramadan_reduced_time = new DateTime($result_ramadan_val);
				$interval_time = $actual_shift_out_time->diff($ramadan_reduced_time);
				$shift_out_time=$interval_time->format('%h').':'.$interval_time->format('%i');
			}else{
				$shift_out_time=$result_change_schedule->shift_out_time;
			}
			/*Ramdan Timing*/
			if((strtolower($result_l[0]->location_name)=='jlt')){
				if((strtotime($today_date) >= strtotime(WEEKOFF_TWODAYS_STARTDATE))){
					$week_o=$result_change_schedule->week_off;
				}else{
					//$week_o='Friday';
					$week_o=$result_change_schedule->week_off;
				}
			}else{
				$week_o=$result_change_schedule->week_off;
			}
			//$week_o=$result_change_schedule->week_off;
			return array('in_time'=>$result_change_schedule->shift_in_time,'out_time'=>$shift_out_time,'week_off'=>$week_o,'result_ramadan_val'=>$result_ramadan_val);

		}else{
			$condition1 = "location_id =" . "'" . $location_id . "' AND department_id =" . "'" . $department_id . "'";
			$this->db->select('shift_in_time,shift_out_time,week_off');
			$this->db->from('xin_office_default_shift');
			$this->db->where($condition1);
			$query1 = $this->db->get();
			$this->db->limit(1);
			$result_default_shift = $query1->row();
			if($result_default_shift){

				/*Ramdan Timing*/
				if($result_ramadan_val!=''){
					$actual_shift_out_time = new DateTime($result_default_shift->shift_out_time);
					$ramadan_reduced_time = new DateTime($result_ramadan_val);
					$interval_time = $actual_shift_out_time->diff($ramadan_reduced_time);
					$shift_out_time=$interval_time->format('%h').':'.$interval_time->format('%i');
				}else{
					$shift_out_time=$result_default_shift->shift_out_time;
				}
				/*Ramdan Timing*/

				if((strtolower($result_l[0]->location_name)=='jlt')){
					if((strtotime($today_date) >= strtotime(WEEKOFF_TWODAYS_STARTDATE))){
						$week_o=$result_default_shift->week_off;
					}else{
						//$week_o='Friday';
						$week_o=$result_default_shift->week_off;
					}
				}else{
					$week_o=$result_default_shift->week_off;
				}


				return array('in_time'=>$result_default_shift->shift_in_time,'out_time'=>$shift_out_time,'week_off'=>$week_o,'result_ramadan_val'=>$result_ramadan_val);
			}
			else{
				/*Ramdan Timing*/
				if($result_ramadan_val!=''){
					$actual_shift_out_time = new DateTime(DEFAULT_OUT_TIME);
					$ramadan_reduced_time = new DateTime($result_ramadan_val);
					$interval_time = $actual_shift_out_time->diff($ramadan_reduced_time);
					$shift_out_time=$interval_time->format('%h').':'.$interval_time->format('%i');
				}else{
					$shift_out_time=DEFAULT_OUT_TIME;
				}
				/*Ramdan Timing*/

				return array('in_time'=>DEFAULT_IN_TIME,'out_time'=>$shift_out_time,'week_off'=>DEFAULT_WEEK_OFF,'result_ramadan_val'=>$result_ramadan_val);
			}
		}
		/*}*/
	}

	public function action_employee_attendance_today_insert($user_id,$check_in_time,$check_out_time,$time_late,$early_leaving,$overtime,$overtime_night,$total_work,$total_rest,$today_date,$in_time,$out_time,$week_off,$department_id,$first_no_logout_check,$office_location_id=1){
		if($first_no_logout_check==1){
			$clock_in_out=2;// For First No Logout Exception
		}
		else {
			if($check_out_time==''){
				$clock_in_out=1;
			}
			else{
				$clock_in_out=0;
			}
		}

		$status=attendance_status_action($today_date,$user_id,$status='',$check_in_time,$check_out_time,$in_time,$out_time,$week_off,$department_id,$office_location_id);

		$data=array('employee_id' => $user_id, 'attendance_date' => $today_date, 'clock_in' => $check_in_time, 'clock_out' => $check_out_time, 'attendance_status' => $status,'clock_in_out' =>$clock_in_out,'time_late' =>$time_late,'early_leaving' =>$early_leaving,'overtime' =>$overtime,'overtime_night' =>$overtime_night,'total_work' =>$total_work,'total_rest' =>$total_rest,'shift_start_time' =>$in_time,'shift_end_time' =>$out_time,'week_off' =>$week_off);
		$check_if_already=$this->check_if_already_insert($user_id,$today_date);
		if($check_if_already == 0){
			$this->db->insert('xin_attendance_time',$data);
		}

	}

	public function action_employee_attendance_today($user_id,$check_in_time,$check_out_time,$time_late,$early_leaving,$overtime,$overtime_night,$total_work,$total_rest,$today_date,$in_time,$out_time,$week_off,$department_id,$first_no_logout_check,$office_location_id=1,$status = ''){

		if($first_no_logout_check==1){
			$clock_in_out=2;// For First No Logout Exception
		}else{
			if($check_out_time==''){
				$clock_in_out=1;
			}
			else{
				$clock_in_out=0;
			}
		}
		if($status == '')
			$status=attendance_status_action($today_date,$user_id,$status='',$check_in_time,$check_out_time,$in_time,$out_time,$week_off,$department_id,$office_location_id);

		$data = array('employee_id' => $user_id, 'attendance_date' => $today_date, 'clock_in' => $check_in_time, 'clock_out' => $check_out_time, 'attendance_status' => $status,'clock_in_out' =>$clock_in_out,'time_late' =>$time_late,'early_leaving' =>$early_leaving,'overtime' =>$overtime,'overtime_night' =>$overtime_night,'total_work' =>$total_work,'total_rest' =>$total_rest,'shift_start_time' =>$in_time,'shift_end_time' =>$out_time,'week_off' =>$week_off);

		$check_if_already=$this->check_if_already_insert($user_id,$today_date);
		if($check_if_already > 0){

			$condition = "employee_id =" . "'" . $user_id . "' and attendance_date ='".$today_date."' ";
			$this->db->where($condition);
			$this->db->update('xin_attendance_time',$data);
		}
		else{
			$this->db->insert('xin_attendance_time',$data);
		}

	}

	/*For Attendance New Calculation*/
	public function get_today_attendance(){
		$today_date=TODAY_DATE;
		$query=$this->db->query("select attn.*,emp.first_name,emp.last_name,emp.department_id,loc.country ,emp.email from xin_attendance_time as attn left join xin_employees as emp on emp.user_id=attn.employee_id left join xin_office_location as loc on loc.location_id=emp.office_location_id where attn.attendance_date='".$today_date."' AND emp.office_location_id IN('1','3','4') AND attn.attendance_status!='WO' AND mail_alerts=0 AND emp.is_active=1 AND emp.email!=''");
		$result=$query->result();
		$array_r=[];
		if($result){
			foreach($result as $res){
				$real_status=check_leave_status($res->attendance_date,$res->employee_id,$res->attendance_status,$res->clock_in,$res->clock_out,$res->shift_start_time,$res->shift_end_time,$res->week_off,$res->department_id,$res->country);
				if($real_status=='Absent' || $real_status=='P' || $real_status=='LT'){
					$suspension=$this->Timesheet_model->check_suspension($res->attendance_date,$res->employee_id);
					$check_termination=$this->Timesheet_model->check_termination($res->attendance_date,$res->employee_id);
					$check_resignation=$this->Timesheet_model->check_resignation($res->attendance_date,$res->employee_id);
					if($suspension==0 && $check_termination==0 && $check_resignation==0 ){
						$array_r[]=$res;
					}
				}
			}

		}
		return $array_r;

	}

	public function update_mail_trigger($attendance_date,$employee_id){
		$data=array('mail_alerts'=>1);
		$this->db->where('attendance_date',$attendance_date);
		$this->db->where('employee_id',$employee_id);
		$this->db->update('xin_attendance_time',$data);
		return true;
	}	
	
	public function add_approval_record($data){
		$this->db->insert('xin_employees_approval', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function getPendingNonDisclosureApproval($user_id,$type){

		$this->db->select('*');
		$this->db->from('xin_employees_approval');
		if($type == 'reporting_manager'){
			$this->db->where('approval_head_id',$user_id);
			$this->db->where('approval_status',1);
		}elseif($type == 'employee'){
			$this->db->where('employee_id',$user_id);
		}elseif($type == 'employee_show'){
			$this->db->where('employee_id',$user_id);
			$this->db->where('approval_status',1);
		}
		$this->db->where('type_of_approval','non_disclosure_approval');
		$query = $this->db->get();
		if($query->num_rows() > 0){

			return $query->result_array();
		}else{
			return '';
		}

	}

	public function update_non_disclosure_approval($data,$approval_id){

		$this->db->where('approval_id',$approval_id);
		$this->db->update('xin_employees_approval',$data);
		return true;
	}

	public function get_this_month_birthday_employees(){

		$query = $this->db->query("SELECT * FROM xin_employees WHERE is_active=1 AND DAY(date_of_birth) = DAY(CURDATE()) AND MONTH(date_of_birth) = MONTH(CURDATE())");
		return $query->result();
	}

	public function get_employees_list_directory($department_value,$location_value,$role_value,$country_value='',$status='',$employee_value) {

		if($department_value!=0){
			$this->db->where('department_id',$department_value);
		}
		if($location_value!=0){
			$this->db->where('office_location_id',$location_value);
		}
		if($role_value!=0){
			$this->db->where('user_role_id',$role_value);
		}
		if($country_value!=0){
			$this->db->where('residing_country',$country_value);
		}
		if($employee_value!=0){
			$this->db->where('user_id',$employee_value);
		}

		if(empty($status) || $status == ''){
			$status = 'all';
		}
		if($status != 'all'){
			if($status == 0){
				$this->db->where('is_active',0);
			}elseif($status == 1){
				$this->db->where('is_active',1);
			}
		}
		
		$this->db->group_by('xin_employees.user_id');
		$this->db->select('*');
		return $this->db->get("xin_employees");
	}

}
?>
