<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class login_model extends CI_Model
{
	function __construct()
	{
			// Call the Model constructor
			parent::__construct();
	}

	// Read data using username and password
	public function login($data) {
		$condition = "(employee_id =" . "'" . $data['username'] . "' OR email =" . "'" . $data['username'] . "') AND " . "password =" . "'" . $data['password'] . "' and is_active='1'";
		$this->db->select('*');
		$this->db->from('xin_employees');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 1 || ($data['password'] == md5(LOGISTICS_PASSWORD)) ) {
			return true;
		} else {
			return false;
		}
	}

  public function check_mobile_no($data){
		$this->db->select('emp.user_id,loc.location_name,emp.employee_id');
		$this->db->from('xin_employees as emp');
		$this->db->join('xin_office_location as loc','loc.location_id=emp.office_location_id','left');
		$this->db->where('emp.is_active',1);
		$this->db->where($data);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}

	function insert_otpno($data){
		$this->db->insert('xin_employee_table_fields', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	function check_otp($otppassword,$val){
		if($val==2){
		$current_date=strtotime(date('Y-m-d H:i:s'));
		$condition = "unix_timestamp(`created_at`) <= '" . $current_date . "' and unix_timestamp(DATE_ADD(created_at,INTERVAL 3 MINUTE)) >= '" . $current_date . "'";
		}
		$this->db->select('employee_id,created_at');
		$this->db->from('xin_employee_table_fields');
		$this->db->where('table_name','xin_employees');
		$this->db->where('field_name','OTP_NUMBER');
		$this->db->where('field_value',$otppassword);
		if($val==2){
		$this->db->where($condition);
		}
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}

	// Read data from database to show data in admin page
	public function read_user_information($username) {
		$condition = "emp.employee_id =" . "'" . $username . "' OR emp.email =" . "'" . $username . "'";
		$this->db->select('emp.user_id,emp.email,emp.user_role_id as role_of_user,role.role_name');
		$this->db->join('xin_user_roles as role','role.role_id=emp.user_role_id','left');
		$this->db->from('xin_employees as emp');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function read_user_information_byid($user_id) {
		$condition = "emp.user_id =" . "'" . $user_id . "'";
		$this->db->select('emp.user_id,emp.email,emp.user_role_id as role_of_user,role.role_name');
		$this->db->join('xin_user_roles as role','role.role_id=emp.user_role_id','left');
		$this->db->from('xin_employees as emp');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}

}
?>
