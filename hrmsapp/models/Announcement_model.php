<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class announcement_model extends CI_Model {
 
  public function __construct()
  {
        parent::__construct();
        $this->load->database();
  }
 
	public function get_announcements()
	{
	  return $this->db->get("xin_announcements");
	}
	 
	public function read_announcement_information($id) {	
		$condition = "announcement_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_announcements');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();		
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	public function get_user_department($id){		
		$condition = "user_id =" . "'" . $id . "'";
		$this->db->select('department_id');
		$this->db->from('xin_employees');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();		
		if ($query->num_rows() == 1) {
			$result= $query->row();
			return $result->department_id;
		} else {
			return false;
		}		
	}

	public function add($data){
		$this->db->insert('xin_announcements', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function delete_record($id){
		$this->db->where('announcement_id', $id);
		$this->db->delete('xin_announcements');
		
	}
	
	public function update_record($data, $id){
		$this->db->where('announcement_id', $id);
		if( $this->db->update('xin_announcements',$data)) {
			return true;
		} else {
			return false;
		}		
	}

}
?>