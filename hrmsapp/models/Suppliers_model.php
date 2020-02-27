<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Suppliers_model extends CI_Model {
 
	public function __construct()
	{
			parent::__construct();
			$this->load->database();
	}
 
	public function get_suppliers()
	{
	  return $this->db->get("xin_suppliers");
	}
	 
	public function read_supplier_information($id) {	
		$condition = "supplier_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_suppliers');
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
		$this->db->insert('xin_suppliers', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function delete_record($id){
		$this->db->where('supplier_id', $id);
		$this->db->delete('xin_suppliers');		
	}
	
	public function all_suppliers() {
	  $query = $this->db->get("xin_suppliers");
	  return $query->result();
	}
	
	public function update_record($data, $id){
		$this->db->where('supplier_id', $id);
		if( $this->db->update('xin_suppliers',$data)) {
			return true;
		} else {
			return false;
		}		
	}

}
?>