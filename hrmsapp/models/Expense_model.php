<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class expense_model extends CI_Model
{ 
	public function __construct()
	{
			parent::__construct();
			$this->load->database();
	}
 
	public function get_expenses() {
	  return $this->db->get("xin_expenses");
	}
	
	public function get_total_expenses() {
	  $query = $this->db->query("SELECT SUM(amount) as exp_amount FROM xin_expenses");
  	return $query->result();
	}
	 
	public function read_expense_information($id) {
		$condition = "expense_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_expenses');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();		
		return $query->result();
	}
	
	public function read_expense_type_information($id) {	
		$condition = "type_id =" . "'" . $id . "' and type_of_module='expense_type'";
		$this->db->select('*');
		$this->db->from('xin_module_types');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();		
		return $query->result();
	}
	
	public function all_expense_types()
	{
	  $query = $this->db->query("SELECT * from xin_module_types where type_of_module='expense_type'");
  	return $query->result();
	}
	
	public function add($data){
		$this->db->insert('xin_expenses', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function delete_record($id){
		$this->db->where('expense_id', $id);
		$this->db->delete('xin_expenses');		
	}
	
	public function update_record($data, $id){
		$this->db->where('expense_id', $id);
		if( $this->db->update('xin_expenses',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	public function update_record_no_logo($data, $id){
		$this->db->where('expense_id', $id);
		if( $this->db->update('xin_expenses',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
}
?>