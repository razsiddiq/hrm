<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class location_model extends CI_Model
{ 
	public function __construct()
	{
			parent::__construct();
			$this->load->database();
	}
 
	public function get_locations()
	{
	  return $this->db->get("xin_office_location");
	}
	 
	public function read_location_information($id) {	
		$condition = "loc.location_id =" . "'" . $id . "'";
		$this->db->select('loc.*,country.country_name');		
		$this->db->where($condition);
		$this->db->from('xin_office_location as loc');
		$this->db->join('xin_countries as country','country.country_id=loc.country','left');
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	public function add($data){
		$this->db->insert('xin_office_location', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function delete_record($id){
		$this->db->where('location_id', $id);
		$this->db->delete('xin_office_location');
		
	}

	public function update_record($data, $id){
		$this->db->where('location_id', $id);
		if( $this->db->update('xin_office_location',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	public function update_record_no_logo($data, $id){
		$this->db->where('location_id', $id);
		if( $this->db->update('xin_office_location',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	public function all_office_locations() {
	  $query = $this->db->query("SELECT * from xin_office_location");
  	return $query->result();
	}
	
	public function all_locations_bycountry() {
	  $query = $this->db->query("SELECT loc.country as country_id,country.country_name FROM `xin_office_location` as loc left join xin_countries as country on country.country_id=loc.country GROUP BY loc.country ORDER BY (country.country_name = 'United Arab Emirates') DESC	");
  	return $query->result();
	}	

  public function get_all_currency($id='') {
		$slug='';
		if($id!=''){
			$slug=" where type_id='".$id."'";
		}
	  $query = $this->db->query("SELECT type_id as currency_id,type_name as name,type_code as code from xin_module_types where type_of_module='currency_type' $slug");
  	return $query->result();
	}
	
}
?>