<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class warning_model extends CI_Model {
 
	public function __construct()
	{
			parent::__construct();
			$this->load->database();
	}
 
	public function get_warning() {
	  return $this->db->get("xin_employee_warnings");
	}
	
	public function get_employee_warning($id) {
	 	return $query = $this->db->query("SELECT * from xin_employee_warnings where warning_to = '".$id."'");
	}
	 
	public function read_warning_information($id) {	
		$condition = "warning_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_employee_warnings');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();		
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	public function read_warning_type_information($id) {	
		$condition = "type_id =" . "'" . $id . "' AND type_of_module='warning_type'";
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
	
	public function all_warning_types() {
	  $query = $this->db->query("SELECT * from xin_module_types where type_of_module='warning_type'");
  	return $query->result();
	}
	
	public function add($data){
		$this->db->insert('xin_employee_warnings', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function delete_record($id){
		$this->db->where('warning_id', $id);
		$this->db->delete('xin_employee_warnings');		
	}
	
	// Function to update record in table
	public function update_record($data, $id){
		$this->db->where('warning_id', $id);
		if( $this->db->update('xin_employee_warnings',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
}
?>