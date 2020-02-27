<?php
defined('BASEPATH') OR exit('No direct script access allowed');
	
class company_model extends CI_Model
{ 
	public function __construct()
	{
			parent::__construct();
			$this->load->database();
	}
 
	public function get_companies() {
	  return $this->db->get("xin_companies");
	}
	
	public function get_company_types() {
		$this->db->where('type_of_module','company_type');
		$query = $this->db->get("xin_module_types");
		return $query->result();
	}
	
	public function get_all_companies() {
	  $query = $this->db->get("xin_companies");
	  return $query->result();
	}
	
	public function read_company_type_information($id) {	
		$condition = "type_id =" . "'" . $id . "' AND type_of_module='company_type'";
		$this->db->select('*');
		$this->db->from('xin_module_types');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();		
		if ($query->num_rows() == 1) {
			$result=$query->result();
			return $result[0]->type_name;
		} else {
			return false;
		}
	}
	
	public function read_company_information($id) {	
		$condition = "company_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_companies');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();		
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	public function add($data){
		$this->db->insert('xin_companies', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function delete_record($id){
		$this->db->where('company_id', $id);
		$this->db->delete('xin_companies');
		
	}
	
	public function update_record($data, $id){
		$this->db->where('company_id', $id);
		if( $this->db->update('xin_companies',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	public function update_record_no_logo($data, $id){
		$this->db->where('company_id', $id);
		if( $this->db->update('xin_companies',$data)) {
			return true;
		} else {
			return false;
		}		
	}

}
?>