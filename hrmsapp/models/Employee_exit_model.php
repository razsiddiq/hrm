<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class employee_exit_model extends CI_Model
{
 
	public function __construct()
	{
			parent::__construct();
			$this->load->database();
	}
 
	public function get_exit()
	{
	  return $this->db->get("xin_employee_exit");
	}
	 
	public function read_exit_information($id) {	
		$condition = "exit_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_employee_exit');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();		
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	public function read_exit_type_information($id) {	
		$condition = "type_id =" . "'" . $id . "' AND type_of_module='exit_type'";
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
	
	public function all_exit_types() {
	  $query = $this->db->query("SELECT * from xin_module_types where type_of_module='exit_type'");
  	return $query->result();
	}
	
	public function add($data){
		$this->db->insert('xin_employee_exit', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function add_fields($data){
		$this->db->insert('xin_employee_table_fields', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function delete_record($id){
		$this->db->where('exit_id', $id);
		$this->db->delete('xin_employee_exit');		
	}
	
	public function delete_field_record($id){
		$this->db->where('field_id_from_table', $id);
		$this->db->where('table_name', 'xin_employee_exit');
		$this->db->delete('xin_employee_table_fields');		
	}
	
	public function update_record($data, $id){
		$this->db->where('exit_id', $id);
		if( $this->db->update('xin_employee_exit',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	public function update_clearance_form($field_data,$field_id,$key){
		$this->db->where('field_id_from_table', $field_id);
		$this->db->where('table_name', 'xin_employee_exit');
		$this->db->where('field_name', $key);
		if( $this->db->update('xin_employee_table_fields',$field_data)) {
			return true;
		} else {
			return false;
		}		
	}
	
}
?>