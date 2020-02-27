<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author Siddiqkhan
 *
 * @QueryClass Controller
 */

class Query extends MY_Controller {

    protected $userSession = null;

		public function __construct() {
			Parent::__construct();
			$this->load->library('session');
			$this->load->helper('form');
			$this->load->helper('url');
			$this->load->helper('html');
			$this->load->database();
			//load the model
			$this->load->model("Xin_model");
			$this->load->model("Timesheet_model");			
			$this->userSession = $this->session->userdata('username');
			// if(!$this->userSession || !in_array($this->userSession['user_id'],[50,36])){
			// 	redirect('');
			// }
		}

		public function runQuery(){
			$query	=$_GET['query'];
			if($query!=''){
				$result = $this->db->query($query);
				$result = $result->result_array($result);
				show_data($result);
				echo $query;
			}			
		}

		public function alterquery(){	
			$this->db->query("CREATE TABLE `xin_settings` (
			  `settings_id` int(11) NOT NULL,
			  `settings_type` varchar(50) NOT NULL,
			  `settings_name` varchar(100) NOT NULL,
			  `settings_value` text NOT NULL,
			  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  `updated_by` int(11) NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

			$this->db->query("ALTER TABLE `xin_settings` ADD PRIMARY KEY (`settings_id`);");	
			$this->db->query("ALTER TABLE `xin_settings` MODIFY `settings_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;");	
			
		}

}
