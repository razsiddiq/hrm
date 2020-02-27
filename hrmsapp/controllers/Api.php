<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	 // protected $userSession = null;

	public function __construct() {
		Parent::__construct();
		// $this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->library('email');
		$this->load->database();
		//load the model
		$this->load->model("Timesheet_model");	    
		$this->load->model('Employees_model');		
		$this->load->model('Xin_model');
		// $this->userSession = $this->session->userdata('username');
		  
	}

	public function bradford_factor_calculator(){

		$type_id = '';
		$emp_id = '';
		$status = 2;
		$access_token = '3cyVGtWYY9hAZn0v';
		$request_headers = apache_request_headers();

		if($request_headers['x-api-key'] == $access_token){

			if($this->input->get('department_id') != ''){
				$department = $this->Timesheet_model->getDepartmentByName($this->input->get('department_id'))[0];
				$dep = $department['department_id'];
			}else{
				$dep = '';
			}

			/*if($this->input->get('status') != 0){
				$status = $this->input->get('status');
			}else{
				$status = '';
			}*/


			if($this->input->get('start_date') == ''){
				$start_date = date('d F Y',strtotime('-30 Day',strtotime(date('d F Y'))));
				$end_date 	= date('d F Y');
			}else{
				$start_date = $this->input->get('start_date');
				$end_date = $this->input->get('end_date');
			}

			$attendance_date['start_date'] = $start_date;
			$attendance_date['end_date'] = $end_date;
			$attendance_details = $this->Timesheet_model->get_uaa_list($attendance_date,$dep,$emp_id);

			// pr($this->db->last_query());
			
			$real_status_array = array();
			$employeeUaaArray = array();
			for ($a=0; $a < count($attendance_details); $a++) { 
				
				$employee = $this->Xin_model->read_user_info($attendance_details[$a]->user_id);
				if($employee[0]->is_active == 1){
					
					$real_status = check_leave_status($attendance_details[$a]->attendance_date,$attendance_details[$a]->user_id,$attendance_details[$a]->attendance_status,$attendance_details[$a]->clock_in,$attendance_details[$a]->clock_out,$attendance_details[$a]->shift_start_time,$attendance_details[$a]->shift_end_time,$attendance_details[$a]->week_off,$attendance_details[$a]->department_id,$attendance_details[$a]->country);

					if($real_status == 'Absent'){
						array_push($real_status_array, $real_status);
						$employeeUaaArray[$employee[0]->user_id][] = $employee[0]->first_name.' '.$employee[0]->middle_name.' '.$employee[0]->last_name;
					}
				}
			}

			// pr($employeeUaaArray);die;

			$data = array();	

			/*foreach($this->Xin_model->get_leave_type()->result() as $lvs){

				$title = $lvs->type_name;
				$leave_per_employee = $this->Xin_model->all_leave_applications_per_employee($dep,$start_date,$end_date,$lvs->type_id,$status,$emp_id);



				if(!empty($leave_per_employee)){
					
					// pr($leave_per_employee);
					
					foreach($leave_per_employee as $r) {

						// pr($leave_per_employee);die;

						$department = $this->Timesheet_model->getDepartmentById($r->department_id)[0];

						if(!empty($employeeUaaArray[$r->user_id])){
							$count_sum = $r->count_sum + count($employeeUaaArray[$r->user_id]);
							$leave_application_count = $r->leave_application_count + count($employeeUaaArray[$r->user_id]);
						}else{ 
							$title = $title;
							$count_sum = $r->count_sum;
							$leave_application_count = $r->leave_application_count;
						}

						$data_leave['type'] = $title; 
						$data_leave['leave_count'] = $leave_count; 
						$data_leave['leave_request_count'] = $leave_request_count; 

						$data[] = array(
								"user_id" => $r->user_id,
								"employee_id" => $r->employee_id,
								"employee_name" => $r->first_name.' '.$r->middle_name.' '.$r->last_name,
								"department" => $department['department_name'],
								"leave_count" => $count_sum,
								"leave_request_count" => $leave_application_count,
								"leave_type" => $data_leave
						);

					}
				}
			}*/

			$emp_array_temp = $this->Xin_model->get_leave_list_employees($dep,$start_date,$end_date);

			// pr($emp_array_temp);die;

			foreach ($emp_array_temp as $key => $value) {
				
				$tempCountSum = 0;
				$tempLeaveApplicationCount = 0;

				$department = $this->Timesheet_model->getDepartmentById($value->department_id)[0];
				$type_id_array = $this->Xin_model->get_leave_type()->result_array();

				$title = '';
				$leave_type_count = '';
	  			$leave_type_application_count = '';

				for ($t=0; $t < count($type_id_array); $t++) { 
					if($type_id_array[$t]['type_id'] != 'UAA'){

						$leave_per_employee = $this->Xin_model->all_leave_applications_per_employee($dep,$start_date,$end_date,$type_id_array[$t]['type_id'],$status,$value->user_id);

						// pr($title);die;

						if(!empty($leave_per_employee)){

							if( $type_id_array[$t]['type_id'] == 172){
			  					$title = 'AL'; 
				  			}
				  			if( $type_id_array[$t]['type_id'] == 173){
				  				$title = 'EL'; 
				  			}
				  			if( $type_id_array[$t]['type_id'] == 174){
				  				$title = 'SL';
				  			}
				  			if( $type_id_array[$t]['type_id'] == 176){ 
				  				$title = 'AA'; 
				  			}
				  			if( $type_id_array[$t]['type_id'] == 208){ 
				  				$title = 'BL';
				  			}
				  			if( $type_id_array[$t]['type_id'] == 175){ 
				  				$title = 'ML';
				  			}
				  			if( $type_id_array[$t]['type_id'] == 222){ 
				  				$title = 'SL-UP';
				  			}

				  			// pr($leave_per_employee);die;

							foreach($leave_per_employee as $r) {

								$title = $title;
								$count_sum = $r->count_sum;
								$leave_application_count = $r->leave_application_count;
								$leave_type_count += $r->count_sum;
								$leave_type_application_count += $r->leave_application_count;
								$temp_data[$title]['leave_type_count'] = $leave_type_count;
								$temp_data[$title]['leave_application_count'] = $leave_type_application_count;
								$temp_data[$title]['title'] = $title;

								// $data[$r->user_id]['leave_type'] = $temp_data;
								// array_push($data, $temp_data);
								
							}

							// pr($data);die;

							// array_push($data, $temp_data);
							$tempLeaveApplicationCount += $leave_application_count;
							$tempCountSum += $count_sum;

						}


					}

					
				}

				if(!empty($employeeUaaArray[$value->user_id])){

					$temp_data['UAA']['leave_type_count'] = count($employeeUaaArray[$value->user_id]);
					$temp_data['UAA']['leave_application_count'] = count($employeeUaaArray[$value->user_id]);
					$temp_data['UAA']['title'] = 'UAA';

					$tempCountSum += count($employeeUaaArray[$value->user_id]);
					$tempLeaveApplicationCount += count($employeeUaaArray[$value->user_id]);
				}

				$data[] = array ( $value->user_id => array(
								// $value->first_name.' '.$value->middle_name.' '.$value->last_name,
								// rtrim($title, ", "),
								// $tempCountSum,
								// $tempLeaveApplicationCount

								"user_id" => $value->user_id,
								"employee_id" => $value->employee_id,
								"employee_name" => $value->first_name.' '.$value->middle_name.' '.$value->last_name,
								"department" => $department['department_name'],
								"leave_count" => $tempCountSum,
								"leave_request_count" => $tempLeaveApplicationCount,
								"leave_type" =>$temp_data
								),
								);

				// $emp_array[$key] = $value;
			}

			echo json_encode($data);die;


		}else{

			$data['message'] = 'Authentication failed.';
			echo json_encode($data);die;
		}

	}

}