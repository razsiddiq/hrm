<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class policy_model extends CI_Model {
 
	public function __construct()
	{
			parent::__construct();
			$this->load->database();
	}
 
	public function get_policies()
	{
	  return $this->db->get("xin_company_policy");
	}
	 
	public function read_policy_information($id) {	
		$condition = "policy_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_company_policy');
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
		$this->db->insert('xin_company_policy', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function delete_record($id){
		$this->db->where('policy_id', $id);
		$this->db->delete('xin_company_policy');		
	}
	
	public function update_record($data, $id){
		$this->db->where('policy_id', $id);
		if( $this->db->update('xin_company_policy',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
}
?>