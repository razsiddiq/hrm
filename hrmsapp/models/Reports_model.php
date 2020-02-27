<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class reports_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function uploaded_document_list() {
		$sql = "SELECT a.type_name,a.type_id,
		(SELECT COUNT(ei.employee_id) FROM xin_employee_immigration ei,xin_employees xe WHERE type=a.type_id AND xe.user_id = ei.employee_id AND xe.is_active = 1) as total_employees,
		(SELECT GROUP_CONCAT(ei.employee_id SEPARATOR ',') FROM xin_employee_immigration ei,xin_employees xe WHERE type=a.type_id AND xe.user_id = ei.employee_id AND xe.is_active = 1) as emps_id,
		(SELECT COUNT(ei.employee_id) FROM xin_employee_immigration ei,xin_employees xe WHERE type=a.type_id AND document_type_id=3 AND document_file!='' AND xe.user_id = ei.employee_id AND xe.is_active = 1) as visa_page,
		(SELECT COUNT(emp.user_id) FROM xin_employees as emp left join xin_employee_immigration imi on imi.employee_id=emp.user_id WHERE imi.type=a.type_id AND emp.profile_picture!='' AND emp.is_active = 1) as photo_copy,
		(SELECT GROUP_CONCAT(emp.user_id SEPARATOR ',') FROM xin_employees as emp left join xin_employee_immigration imi on imi.employee_id=emp.user_id WHERE imi.type=a.type_id AND emp.profile_picture = '' AND emp.is_active = 1) as photo_copy_emps,
		(SELECT COUNT(emp.user_id) FROM xin_employees as emp left join xin_employee_immigration imi on imi.employee_id=emp.user_id left join xin_employee_contract as con on con.employee_id=emp.user_id WHERE imi.type=a.type_id AND con.document_file!='' AND emp.is_active = 1) as contract,
		(SELECT GROUP_CONCAT(emp.user_id SEPARATOR ',') FROM xin_employees as emp left join xin_employee_immigration imi on imi.employee_id=emp.user_id left join xin_employee_contract as con on con.employee_id=emp.user_id WHERE imi.type=a.type_id AND con.document_file = '' AND emp.is_active = 1) as contract_emps
		FROM (SELECT DISTINCT type_name,type_id FROM xin_module_types where type_of_module='visa_under' order by type_name ASC) a";
		$query = $this->db->query($sql);
		return $query->result();
	}

	public function expiry_document_list() {
		$query = $this->db->query("SELECT emp.first_name,emp.middle_name,emp.last_name,emp.user_id,max(module.type_name) as visa_name,
		max(if (imi.document_type_id=3,imi.issue_date , null)) as visa_issue_date,
		max(if (imi.document_type_id=3,imi.expiry_date , null)) as visa_expiry_date,
		max(if (con.from_date!='',con.from_date , null)) as contract_issue_date,
		max(if (con.to_date!='',con.to_date , null)) as contract_expiry_date
		from xin_employees as emp
		left join xin_employee_immigration as imi on emp.user_id=imi.employee_id
		left join xin_employee_contract as con on con.employee_id=emp.user_id 
		left join xin_module_types as module on module.type_id=imi.type 
		where emp.is_active = 1
		group by 1");
		return $query->result();
	}

	public function employee_reports($department_value,$location_value,$medical_card_value,$visa_value,$country_id=''){
		$slug='1';
		if($department_value!=0){
			$slug.=" AND emp_master_data.department_id='".$department_value."'";
		}
		if($location_value!=0){
			$slug.=" AND emp_master_data.office_location_id='".$location_value."'";
		}
		$join_query='';
		if($medical_card_value!='0'){
			$slug.=" AND xin_employee_immigration.type='".$medical_card_value."'";
			$join_query.=" left join xin_employee_immigration on xin_employee_immigration.employee_id=emp_master_data.user_id";
		}

		if($visa_value!=0){
			$slug.=" AND modul.type_id='".$visa_value."'";
			$join_query.=" left join xin_employee_immigration as doc on doc.employee_id=emp_master_data.user_id AND doc.document_type_id=3";
			$join_query.=" left join xin_module_types as modul on modul.type_id=doc.type ";
		}
		if($country_id!=''){
			$join_query.=" left join xin_office_location on xin_office_location.location_id=emp_master_data.office_location_id";
			$slug.=" AND xin_office_location.country='".$country_id."'";
		}
		$query = "SELECT 
				emp_master_data.user_id,
				emp_master_data.first_name,
				emp_master_data.middle_name,
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
				roles.role_name,
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
				group by emp_master_data.user_id asc";
		$query = $this->db->query($query);
		return $query->result();
	}

	public function get_document_details($user_id,$document_type_id){
		$query = $this->db->query("select module.type_name as type,imi.document_number,imi.issue_date,imi.expiry_date from xin_employee_immigration as imi left join xin_module_types as module on module.type_id=imi.type where imi.document_type_id='".$document_type_id."' and imi.employee_id='".$user_id."' limit 1");
		return $query->result();
	}

	public function get_document_count($emps_id,$document_type_id){
		if($emps_id=='')
			return 0;

		$emp_id = explode(',', $emps_id);
		$this->db->select('employee_id');
		$this->db->from('xin_employee_immigration');
		$this->db->where('document_type_id', $document_type_id);
		$this->db->where('document_file!=""');
		$this->db->where_in('employee_id', $emp_id);
		$query = $this->db->get();
		return ['rows'=>$query->num_rows(),'result'=>$query->result_array()];
	}

	public function getDriverEmployees(){
		$designations = $this->getDriverDesignations();
		$this->db->select('user_id');
		$this->db->from('xin_employees');
		$this->db->where_in('designation_id', $designations);
		$query = $this->db->get();
		$drivers = $query->result();
		$result = [];
		foreach ($drivers as $row)
			$result[] = $row->user_id;
		return $result;
	}
	public function getDriverDesignations(){
		$query = $this->db->query("SELECT GROUP_CONCAT(designation_id SEPARATOR ',') as drivers from xin_designations where designation_name like '%driver%'");
		return $query->result()[0]->drivers;
	}

	public function get_contract_details($user_id){
		$query = $this->db->query("select max(contract_id),module.type_name as contract_name,cont.from_date,cont.to_date,cont.document_file from xin_employee_contract as cont left join xin_module_types as module on module.type_id = cont.contract_type_id where cont.employee_id='".$user_id."' limit 1");
		return $query->result();
	}

	public function getsupport_documents($country_id){
		if($country_id!=''){
			$conditions=" type_code like '%".$country_id."%'";
			$this->db->select('*');
			$this->db->from('xin_module_types');
			$this->db->where('type_of_module','document_type');
			$this->db->where($conditions);
			$this->db->order_by('type_id','ASC');
			$query = $this->db->get();
			return $result= $query->result();
		}else{
			return '';
		}
	}

}
?>
