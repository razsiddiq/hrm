<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class roles_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
	public function get_user_roles()
	{
	  return $this->db->get("xin_user_roles");
	}
	 
	public function read_role_information($id) {	
		$condition = "role_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_user_roles');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();		
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	public function all_active_employees_custom_roles(){
      $query = $this->db->query("SELECT * from xin_employees where is_active=1 AND custom_roles='' order by first_name ASC");
  	  return $query->result();		
	}
	
	public function get_custom_user_roles(){
     return $this->db->query("SELECT * from xin_employees where is_active=1 AND custom_roles!='' order by first_name ASC");		
	}
	
	public function add($data){
		$this->db->insert('xin_user_roles', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function delete_record($id){
		$this->db->where('role_id', $id);
		$this->db->delete('xin_user_roles');		
	}
	
	public function update_record($data, $id){
		$this->db->where('role_id', $id);
		if( $this->db->update('xin_user_roles',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	

	public function update_custom_record($cdata, $emp_id){
		$data=array('custom_roles'=>$cdata);
		$this->db->where('user_id', $emp_id);
		if( $this->db->update('xin_employees',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	public function all_user_roles()
	{
	  $query = $this->db->query("SELECT * from xin_user_roles");
  	  return $query->result();
	}
	
	public function check_rolename($role_name,$role_id){
        if($role_id==''){
		$condition = "role_name =" . "'" . $role_name . "'";
        }else{
		$condition = "role_name =" . "'" . $role_name . "' AND role_id !=" . "'" . $role_id . "'";
		}
		$this->db->select('role_name');
		$this->db->from('xin_user_roles');
		$this->db->where($condition);
		$query = $this->db->get();
		return $query->num_rows();
	}
		
	public function get_role_id($name){
		$condition = "role_name =" . "'" . $name . "'";
		$this->db->select('*');
		$this->db->from('xin_user_roles');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		$result=$query->result();
		return $result[0]->role_id;		
	}
		
}
?>