<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class termination_model extends CI_Model {
 
	public function __construct()
	{
			parent::__construct();
			$this->load->database();
	}
 
	public function get_terminations()
	{
	  return $this->db->get("xin_employee_terminations");
	}
	 
	public function read_termination_information($id) {	
		$condition = "termination_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_employee_terminations');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();		
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	public function read_termination_type_information($id) {	
		$condition = "type_id =" . "'" . $id . "' AND type_of_module='termination_type'";
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
	
	public function all_termination_types() {
	  $query = $this->db->query("SELECT * from xin_module_types where type_of_module='termination_type'");
  	return $query->result();
	}
	
	public function add($data){
		$this->db->insert('xin_employee_terminations', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function delete_record($id){
		$this->db->where('termination_id', $id);
		$this->db->delete('xin_employee_terminations');		
	}
	
	public function update_record($data, $id){
		$this->db->where('termination_id', $id);
		if( $this->db->update('xin_employee_terminations',$data)) {
			return true;
		} else {
			return false;
		}		
	}

}
?>