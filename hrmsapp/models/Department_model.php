<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class department_model extends CI_Model
{ 
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function get_departments()
	{
		return $this->db->get("xin_departments");
	}
	
	public function read_department_information($id) {	
		$condition = "department_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_departments');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();		
		return $query->result();
	}
	
	public function add($data){
		$this->db->insert('xin_departments', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function delete_record($id){
		$this->db->where('department_id', $id);
		$this->db->delete('xin_departments');		
	}
	
	public function update_record($data, $id){
		$this->db->where('department_id', $id);
		if( $this->db->update('xin_departments',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	public function all_departments()
	{
	  $query = $this->db->query("SELECT * from xin_departments  ");
  	return $query->result();
	}	
	
	public function all_departments_not($id='')
	{
	  $id=explode(',',$id);
	  $res = "'" . implode ( "', '", $id ) . "'";	  
    if($id==''){
			$slug='';
		}	
		else{
			$slug=' where department_id NOT IN('.$res.')';
	  }	  
		$query = $this->db->query("SELECT * from xin_departments $slug");
		return $query->result();
	}	
	
}
?>