<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {
	
	protected $userSession = null;

	public function __construct()
    {
          parent::__construct();
          $this->load->library('session');
          $this->load->helper('form');
          $this->load->helper('url');
          $this->load->helper('html');
          $this->load->database();
          $this->load->library('form_validation');
          //load the models
          $this->load->model('Login_model');
		  $this->load->model('Designation_model');
		  $this->load->model('Department_model');
		  $this->load->model('Employees_model');
		  $this->load->model('Xin_model');
		  $this->load->model('Expense_model');
		  $this->load->model('Timesheet_model');
		  $this->load->model('Project_model');
		  $this->load->model('Awards_model');
		  $this->load->model('Announcement_model');	
		  $this->userSession = $this->session->userdata('username');	  
    }
	
	/*Function to set JSON output*/
	public function output($Return=array()){
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	} 
	
	public function index()
	{   	
		if($this->userSession['role_name']==E_M_ROLE){
		if(!empty($this->userSession)){ 
			redirect('/employee/leave/');
		} else {
			redirect('');
		}	
		}else{
		if(!empty($this->userSession)){ 
			
		} else {
			redirect('');
		}
		}
		// get user > added by
		$user = $this->Xin_model->read_user_info($this->userSession['user_id']);
		// get designation
		$_designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
		    $data = array(
			'title' => $this->Xin_model->site_title(),
			'breadcrumbs' => $this->lang->line('dashboard_title'),
			'path_url' => 'dashboard',
			'first_name' => $user[0]->first_name,
			'middle_name' => $user[0]->middle_name,
			'last_name' => $user[0]->last_name,
			'employee_id' => $user[0]->employee_id,
			'username' => $user[0]->username,
			'email' => $user[0]->email,
			'designation_name' => $_designation[0]->designation_name, 
			'date_of_birth' => $user[0]->date_of_birth,
			'date_of_joining' => $user[0]->date_of_joining,
			'contact_no' => $user[0]->contact_no,
			'last_four_employees' => $this->Xin_model->last_four_employees(),
			'last_jobs' => '',//$this->Xin_model->last_jobs()
			'all_countries' => $this->Xin_model->get_countries(),
			);
			$data['subview'] = $this->load->view('dashboard/index', $data, TRUE);
			$this->load->view('layout_main', $data); //page load
	}
	
	// get opened and closed tickets for chart
	public function tickets_data()
	{
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('opened'=>'', 'closed'=>'');
		// open
		$Return['opened'] = $this->Xin_model->all_open_tickets();
		// closed
		$Return['closed'] = $this->Xin_model->all_closed_tickets();
		$this->output($Return);
		exit;
	}
	
	// get company wise salary
	public function payroll_company_wise()
	{
		$Return = array('chart_data'=>'', 'd_name'=>'','d_count'=>'', 'c_am'=>'','c_color'=>'');
		$d_name = array();
		$d_count = array();
		$c_am = array();	
		$c_color = array();
		$someArray = array();
	
	    foreach($this->Xin_model->all_departments_chart() as $department) {
		
			$department_pay = $this->Xin_model->get_department_make_payment($department->department_id);
			$d_name[] = htmlspecialchars_decode($department->department_name);
			$d_count[] = htmlspecialchars_decode($department_pay[0]->total_count);
			$c_am[] = $department_pay[0]->paidAmount;
			$someArray[] = array(
			'label'   => htmlspecialchars_decode($department->department_name),		  
			'value' =>   $department_pay[0]->paidAmount,
			'count'   => $department_pay[0]->total_count,
			'name'   =>  htmlspecialchars_decode($department->department_detail),	
			);
			
		}		
		$sorted = $this->array_orderby($someArray, 'value', SORT_DESC);	
		$Return['d_name'] = $d_name;
		$Return['d_count'] = $d_count;
		$Return['c_am'] = $c_am;
		$Return['chart_data'] = $sorted;
		$this->output($Return);
		exit;
	}
	

    public function time_attn()
	{
		$Return = array('chart_data'=>'', 'd_name'=>'','d_count'=>'', 'c_am'=>'','c_color'=>'');
		$d_name = array();
		$d_count = array();
		$c_am = array();	
		$c_color = array();
		$someArray = array();
	
	    foreach($this->Xin_model->get_attn_emp($this->userSession['user_id']) as $attn_emp) {
	    if($attn_emp->total_rest!=''){
        $clock_in=$attn_emp->total_rest;
        }else{
        $clock_in=0;
        }

        if($attn_emp->total_work!=''){
        $total_work=$attn_emp->total_work;
        }else{
        $total_work=0;
        }

        if($attn_emp->clock_in!=''){
			$punch_in=strtotime($attn_emp->clock_in);
			//$punch_in=str_replace(':','.',date('H:i',strtotime($attn_emp->clock_in)));
		}else{
			$punch_in=0;
			}

     	if($attn_emp->clock_out!=''){
		$punch_out=strtotime($attn_emp->clock_out);
		//$punch_out=str_replace(':','.',date('H:i',strtotime($attn_emp->clock_out)));
		}else{
		$punch_out=0;
		}
		$someArray[] = array(
		  'label'   => $total_work,		  
		  'value' =>   $clock_in,
		  'punch_in' =>   $punch_in,
		  'punch_out' =>   $punch_out,
		  'count'   => $attn_emp->attendance_status,
		  'name'   =>  format_date('d M Y',$attn_emp->attendance_date).' ('.$attn_emp->attendance_status.')',
		  );		
		}		
	    $Return['d_name'] = count($someArray);
		$Return['d_count'] = '';
		$Return['c_am'] = '';
		$Return['chart_data'] = $someArray;
		$this->output($Return);
		exit;
	}

	public function array_orderby()
	{
		$args = func_get_args();
		$data = array_shift($args);
		foreach ($args as $n => $field) {
			if (is_string($field)) {
				$tmp = array();
				foreach ($data as $key => $row)
					$tmp[$key] = $row[$field];
				$args[$n] = $tmp;
				}
		}
		$args[] = &$data;
		call_user_func_array('array_multisort', $args);
		return array_pop($args);
	}
	
	// get location|station wise salary
	public function payroll_location_wise()
	{
		$Return = array('chart_data'=>'', 'l_name'=>'','l_count'=>'', 'l_am'=>'','l_color'=>'');
		$l_name = array();
		$l_count = array();
		$l_am = array();	
		$l_color = array('#3e70c9','#f59345','#f44236','#8A2BE2','#D2691E','#6495ED','#DC143C','#006400','#556B2F','#9932CC');
		$someArray = array();
		//$j=0;
		foreach($this->Xin_model->all_location_chart() as $location) {
			//if($j==9){$j=0;}	
			$location_pay = $this->Xin_model->get_location_make_payment($location->location_id);
			$l_name[] = htmlspecialchars_decode($location->location_name);
			$l_am[] = $location_pay[0]->paidAmount;
			$someArray[] = array(
			'label'   => htmlspecialchars_decode($location->location_name),
			'value' => $location_pay[0]->paidAmount,
			'count'   => $location_pay[0]->total_count,
			'name' =>  htmlspecialchars_decode($location->location_name),
			
			);
		 // $j++;
		}
		
		$sorted = $this->array_orderby($someArray, 'value', SORT_DESC);
		$Return['l_name'] = $l_name;
		$Return['l_am'] = $l_am;
		$Return['l_count'] = $l_count;
		$Return['chart_data'] = $sorted;
		$this->output($Return);
		exit;
	}
	
	// get department wise salary
	public function payroll_department_wise()
	{
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('chart_data'=>'', 'c_name'=>'', 'c_am'=>'','c_color'=>'');
		$c_name = array();
		$c_am = array();	
		$c_color = array('#3e70c9','#f59345','#f44236','#8A2BE2','#D2691E','#6495ED','#DC143C','#006400','#556B2F','#9932CC');
		$someArray = array();
		$j=0;
		foreach($this->Xin_model->all_departments_chart() as $department) {
		if($j==9){$j=0;}	
		$department_pay = $this->Xin_model->get_department_make_payment($department->department_id);
		$c_name[] = htmlspecialchars_decode($department->department_name);
		$c_am[] = $department_pay[0]->paidAmount;
		$someArray[] = array(
		  'label'   => htmlspecialchars_decode($department->department_name.'  ('.$this->Xin_model->currency_sign(change_num_format($department_pay[0]->paidAmount)).')'),
		  'value' => $department_pay[0]->paidAmount,
		  'bgcolor' => $c_color[$j]
		  );
		  $j++;
		}
		$sorted = $this->array_orderby($someArray, 'value', SORT_DESC);
		$Return['c_name'] = $c_name;
		$Return['c_am'] = $c_am;
		$Return['chart_data'] = $sorted;
		$this->output($Return);
		exit;
	}
	
	// get designation wise salary
	public function payroll_designation_wise()
	{
		
		$id=$this->uri->segment(3);
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('chart_data'=>'', 'd_name'=>'','d_count'=>'', 'c_am'=>'','c_color'=>'');
    	$d_name = array();
		$d_count = array();
		$c_am = array();	
		$c_color = array('#1AAF5D','#F2C500','#F45B00','#8E0000','#0E948C','#6495ED','#DC143C','#006400','#556B2F','#9932CC',);
		$someArray = array();
		//$j=0;
		foreach($this->Xin_model->all_designations_chart($id) as $designation) {
			//if($j==9){$j=0;}	
		
			$result = $this->Xin_model->get_designation_make_payment($designation->designation_id);
			$d_name[] = htmlspecialchars_decode($designation->designation_name);
			$d_count[] = htmlspecialchars_decode($result[0]->total_count);
			$c_am[] = $result[0]->paidAmount;
			$someArray[] = array(		  
			'label'   => htmlspecialchars_decode($designation->designation_name),
			'value' => $result[0]->paidAmount,
			'count'   => $result[0]->total_count,
			'name'   =>  htmlspecialchars_decode($designation->designation_name),	

			);
		//  $j++;
		}
		$sorted = $this->array_orderby($someArray, 'value', SORT_DESC);
		$Return['d_name'] = $d_name;
		$Return['d_count'] = $d_count;
		$Return['c_am'] = $c_am;
		$Return['chart_data'] = $sorted;
		$this->output($Return);
		exit;
	}

	public function dashboard_leave_chart(){
		
		if($this->input->get('id') != 0){
			$dep = $this->input->get('id');
		}else{
			$dep = '';
		}

		/*if($this->input->get('type_id') != 0 || $this->input->get('type_id') != ''){
			$type_id = explode(',', $this->input->get('type_id'));
		}else{
			$type_id = '';
		}*/

		if($this->input->get('type_id') != ''){
			$type_id = $this->input->get('type_id');
		}else{
			$type_id = '';
		}

		if($this->input->get('status') != 0){
			$status = $this->input->get('status');
		}else{
			$status = '';
		}

		if($this->input->get('emp_id') != 0){
			$emp_id = $this->input->get('emp_id');
		}else{
			$emp_id = '';
		}

		if($this->input->get('start_date') == ''){
			$start_date = date('d F Y',strtotime('-30 Day',strtotime(date('d F Y'))));
			$end_date 	= date('d F Y');
		}else{
			$start_date = $this->input->get('start_date');
			$end_date = $this->input->get('end_date');
		}
		// pr($start_date);die;

		/* Define return | here result is used to return user data and error for error message */

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

		// pr($employeeUaaArray);

		$Return = array('chart_data'=>'', 'd_name'=>'','d_count'=>'', 'c_am'=>'','c_color'=>'');
    	$d_name = array();
		$d_count = array();
		$c_am = array();	
		$data = array();	
		$c_color = array('#1AAF5D','#F2C500','#F45B00','#8E0000','#0E948C','#6495ED','#DC143C','#006400','#556B2F','#9932CC',);
		$someArray = array();
		$typeArray = array("EL","AL","SL","SL-UP","AA","BL","ML");
		$emp_array = array();
		foreach($this->Xin_model->all_leave_applications($dep,$start_date,$end_date,$status) as $leave) {
			
			if($leave->leave_type_id == "172"){
  				$title = 'Annual Leave'; 
  				$color = '#006600';
  			}elseif($leave->leave_type_id == "173"){
  				$title = 'Emergency Leave'; 
  				$color = '#8181ff';
  			}elseif($leave->leave_type_id == "174"){
  				$color = '#ffbf82';
  				$title = 'Sick Leave';
  			}elseif($leave->leave_type_id == "176"){ 
  				$color = '#e65481';
  				$title = 'Authorised Absence'; 
  			}elseif($leave->leave_type_id == "208"){ 
  				$color = '#8765c4';
  				$title = 'Bereavement Leave';
  			}elseif($leave->leave_type_id == "175"){ 
  				$color = '#29a69a';
  				$title = 'Maternity Leave';
  			}elseif($leave->leave_type_id == "211"){ 
  				$color = '#faeb82';
  				$title = 'Sick Leave Unpaid';
  			}

			$d_name[] = htmlspecialchars_decode($leave->leave_status_code);
			$d_count[] = htmlspecialchars_decode($leave->count_sum);
			$c_am[] = $leave->total_count;
			$someArray[$leave->leave_status_code] = array(		  
			'label'   => htmlspecialchars_decode($leave->leave_status_code),
			'value' => $leave->count_sum,
			'count'   => $leave->total_count,
			'name'   =>  htmlspecialchars_decode($title),	
			'color'   =>  htmlspecialchars_decode($color),	
			);
		}

		if(empty($someArray['EL'])){
			$someArray['EL']['label'] = 'EL';
            $someArray['EL']['value'] = 0;
            $someArray['EL']['count'] = 0;
            $someArray['EL']['name'] = '';
            $someArray['EL']['color'] = '#8181ff';
            array_push($d_name, 'EL');
            array_push($d_count,0);
            array_push($c_am,0);
		}
		if(empty($someArray['AL'])){
			$someArray['AL']['label'] = 'AL';
            $someArray['AL']['value'] = 0;
            $someArray['AL']['count'] = 0;
            $someArray['AL']['color'] = '#006600';
            $someArray['AL']['name'] = '';
            array_push($d_name, 'AL');
            array_push($d_count,0);
            array_push($c_am,0);
		}
		if(empty($someArray['SL-1'])){
			$someArray['SL-1']['label'] = 'SL-1';
            $someArray['SL-1']['value'] = 0;
            $someArray['SL-1']['count'] = 0;
            $someArray['SL-1']['color'] = '#ffbf82';
            $someArray['SL-1']['name'] = '';
            array_push($d_name, 'SL-1');
            array_push($d_count,0);
            array_push($c_am,0);
		}
		if(empty($someArray['SL-2'])){
			$someArray['SL-2']['label'] = 'SL-2';
            $someArray['SL-2']['value'] = 0;
            $someArray['SL-2']['count'] = 0;
            $someArray['SL-2']['color'] = '#ffbf82';
            $someArray['SL-2']['name'] = '';
            array_push($d_name, 'SL-2');
            array_push($d_count,0);
            array_push($c_am,0);
		}
		if(empty($someArray['SL-UP'])){
			$someArray['SL-UP']['label'] = 'SL-UP';
            $someArray['SL-UP']['value'] = 0;
            $someArray['SL-UP']['count'] = 0;
            $someArray['SL-UP']['color'] = '#faeb82';
            $someArray['SL-UP']['name'] = '';
            array_push($d_name, 'SL-UP');
            array_push($d_count,0);
            array_push($c_am,0);
		}
		if(empty($someArray['AA'])){
			$someArray['AA']['label'] = 'AA';
            $someArray['AA']['value'] = 0;
            $someArray['AA']['count'] = 0;
            $someArray['AA']['color'] = '#e65481';
            $someArray['AA']['name'] = '';
            array_push($d_name, 'AA');
            array_push($d_count,0);
            array_push($c_am,0);
		}
		if(empty($someArray['BL'])){
			$someArray['BL']['label'] = 'BL';
            $someArray['BL']['value'] = 0;
            $someArray['BL']['count'] = 0;
            $someArray['BL']['color'] = '#8765c4';
            $someArray['BL']['name'] = '';
            array_push($d_name, 'BL');
            array_push($d_count,0);
            array_push($c_am,0);
		}
		if(empty($someArray['ML'])){
			$someArray['ML']['label'] = 'ML';
            $someArray['ML']['value'] = 0;
            $someArray['ML']['count'] = 0;
            $someArray['ML']['color'] = '#29a69a';
            $someArray['ML']['name'] = '';
            array_push($d_name, 'ML');
            array_push($d_count,0);
            array_push($c_am,0);
		}
		if(empty($someArray['UAA'])){
			$someArray['UAA']['label'] = 'UAA';
            $someArray['UAA']['value'] = count($real_status_array);
            $someArray['UAA']['count'] = count($real_status_array);
            $someArray['UAA']['color'] = '#c254e6';
            $someArray['UAA']['name'] = '';
            array_push($d_name, 'UAA');
            array_push($d_count,0);
            array_push($c_am,0);
		}

	

		if($this->input->get('type_id') != ''){
			$type_id_array = explode(',', $this->input->get('type_id'));
			$title = '';
			if(in_array(172, $type_id_array)){
  				$title .= 'AL, '; 
  			}
  			if(in_array(173, $type_id_array)){
  				$title .= 'EL, '; 
  			}
  			if(in_array(174, $type_id_array)){
  				$title .= 'SL, ';
  			}
  			if(in_array(176, $type_id_array)){ 
  				$title .= 'AA, '; 
  			}
  			if(in_array(208, $type_id_array)){ 
  				$title .= 'BL, ';
  			}
  			if(in_array(175, $type_id_array)){ 
  				$title .= 'ML, ';
  			}
  			if(in_array(222, $type_id_array)){ 
  				$title .= 'SL-UP, ';
  			}
  			if(in_array('UAA', $type_id_array)){ 
  				$title .= 'UAA, ';
  			}
  		}else{
  			$title = 'All';
  		}

		$type_id_ex = explode(',', $type_id);

		if($type_id_ex[0] == 'UAA' && count($type_id_ex) == 1){

			foreach ($employeeUaaArray as $key => $value) {
				
				$data[] = array(
							$value[0],
							'UAA',
							count($value),
							count($value)
					);
				$emp_array[$key] = $value[0];
			}

		}else{

			if(!empty($type_id_array)){

				$emp_array_sess = $this->session->userdata('emp_array_sess');

				if($emp_id != ''){
					$emp_array_sess = array($emp_id => $emp_array_sess[$emp_id]);
				}

				foreach ($emp_array_sess as $key => $value) {
					
					$tempCountSum = 0;
					$tempLeaveApplicationCount = 0;

					for ($t=0; $t < count($type_id_array); $t++) { 
						if($type_id_array[$t] != 'UAA'){

							$leave_per_employee = $this->Xin_model->all_leave_applications_per_employee($dep,$start_date,$end_date,$type_id_array[$t],$status,$key);

							if(!empty($leave_per_employee)){
								foreach($leave_per_employee as $r) {

									$title = $title;
									$count_sum = $r->count_sum;
									$leave_application_count = $r->leave_application_count;
									
								}
							}else{
							
								$title = $title;
								$count_sum = 0;
								$leave_application_count = 0;
							}

							$tempLeaveApplicationCount += $leave_application_count;
							$tempCountSum += $count_sum;

						}

						
					}

					if(!empty($employeeUaaArray[$key]) && in_array('UAA', $type_id_array)){
						$tempCountSum += count($employeeUaaArray[$key]);
						$tempLeaveApplicationCount += count($employeeUaaArray[$key]);
					}

					$data[] = array(
									$value,
									rtrim($title, ", "),
									$tempCountSum,
									$tempLeaveApplicationCount
									);

					$emp_array[$key] = $value;
				}
			}else{

				$leave_per_employee = $this->Xin_model->all_leave_applications_per_employee($dep,$start_date,$end_date,$type_id,$status,$emp_id);

				foreach($leave_per_employee as $r) {

					if(!empty($employeeUaaArray[$r->user_id])){
						$count_sum = $r->count_sum + count($employeeUaaArray[$r->user_id]);
						$leave_application_count = $r->leave_application_count + count($employeeUaaArray[$r->user_id]);
					}else{ 
						$title = $title;
						$count_sum = $r->count_sum;
						$leave_application_count = $r->leave_application_count;
					}
					$data[] = array(
							$r->first_name.' '.$r->middle_name.' '.$r->last_name,
							rtrim($title, ", "),
							$count_sum,
							$leave_application_count
					);
					$emp_array[$r->user_id] = $r->first_name.' '.$r->middle_name.' '.$r->last_name;
				}
				$this->session->set_userdata('emp_array_sess',$emp_array);	
			}
		}

		$options = '';
		if(!empty($emp_array)){
			$options='<select class="form-control" onchange="leave_list_by_type()" name="emp_id" id="emp_id" data-plugin="select_hrm">';
			$options.='<option value="0">All</option>';
			foreach($emp_array as $key => $emp){
				$options.='<option value="'.$key.'">'.$emp.'</option>';
			}
			$options.='</select>';
		}

		$sorted = $this->array_orderby($someArray, 'label', SORT_DESC);
		$Return['d_name'] = $d_name;
		$Return['d_count'] = $d_count;
		$Return['c_am'] = $c_am;
		$Return['chart_data'] = $sorted;
		if(!empty($data)){
			$Return["data"] = $data;
		}else{
			$Return["data"] = array();
		}
		$Return["emp_list"] = $options;

		$this->output($Return);
		exit;
	}
	
	// set new language
	public function set_language($language = "") {
        
        $language = ($language != "") ? $language : "english";
        $this->session->set_userdata('site_lang', $language);
        redirect($_SERVER['HTTP_REFERER']);
        
    }
	
	
	//birth day message
	public function birthday_wishes(){	
		
		$user_id=base64_decode($this->uri->segment(3));
		$users=$this->Xin_model->read_user_info($user_id);
		
		$current_month=date('m');
		$today_d_only=date('d',strtotime(TODAY_DATE));

			
		$data=array(
		'title' => $this->Xin_model->site_title(),
		'breadcrumbs' => 'Birthday',
		'path_url' => 'birthday',
		'user_info_id'=>$users[0]->user_id,
		'birday_date'=>date('Y').'-'.$current_month.'-'.$today_d_only,
	    );
	
		if(!empty($this->userSession)){ 			
		}
		else {
			redirect('dashboard');
		}
		if($users[0]->date_of_birth==''){
			redirect('dashboard');	
		}  
		
		$this->db->where('to_id',$this->userSession['user_id']);
		$this->db->where('birthday_date',TODAY_DATE);
		$data_up=array('notification'=>0);
		$this->db->update('xin_chat_messages',$data_up);

		
		if($today_d_only.$current_month!=date('dm',strtotime($users[0]->date_of_birth))){
			redirect('dashboard');	
		} 
		

		$data['subview'] = $this->load->view('dashboard/birthday_wishes', $data, TRUE);
		$this->load->view('layout_main', $data); //page load
	
		
	}
	
	
	//comments_list
	public function comments_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		//$id = $this->input->get('ticket_id');
		$id = $this->uri->segment(3);
		$ses_user = $this->Xin_model->read_user_info($this->userSession['user_id']);
		if(empty($this->userSession)){ 
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		$birthday_id=$this->uri->segment(3);
		$birthday_date=$this->uri->segment(4);

		$comments = $this->Employees_model->get_comments($birthday_id,$birthday_date,0);
	
		$data = array();

        foreach($comments as $r) {
			
		// get user > employee_
		$employee = $this->Xin_model->read_user_info($r->from_id);
		// employee full name
		$employee_name = $employee[0]->first_name.' '.$employee[0]->middle_name.' '.$employee[0]->last_name;
		// get designation
		$_designation = $this->Designation_model->read_designation_information($employee[0]->designation_id);
		// created at
		$created_at = date('h:i A', strtotime($r->message_date));
		$_date = explode(' ',$r->message_date);
		$date = $this->Xin_model->set_date_format($_date[0]);
		
		// profile picture
		
		if($employee[0]->profile_picture!='' && $employee[0]->profile_picture!='no file') {
			$u_file = base_url().'uploads/profile/'.$employee[0]->profile_picture;
        } else {
			if($employee[0]->gender=='Male') { 
				$u_file = base_url().'uploads/profile/default_male.jpg';
			} else {
				$u_file = base_url().'uploads/profile/default_female.jpg';
			}
        } 	
		if($ses_user[0]->user_role_id==1){
			//$link = '<a class="c-user text-black" href="'.site_url().'employees/detail/'.$r->from_id.'"><span class="underline">'.$employee_name.' ('.$_designation[0]->designation_name.')</span></a>';
			$link = '<span class="underline">'.$employee_name.' ('.$_designation[0]->designation_name.')</span>';
		} else{
			$link = '<span class="underline">'.$employee_name.' ('.$_designation[0]->designation_name.')</span>';
		}
		if(($ses_user[0]->user_role_id==1 || $ses_user[0]->user_id==$r->from_id) && $r->status==1){
			$dlink = '<div class="media-right">
							<div class="c-rating">
							<span data-toggle="tooltip" data-placement="top" title="Delete">
								<a class="btn btn-danger btn-sm delete" href="#" data-toggle="modal" data-target=".delete-modal" data-record-id="'.$r->message_id.'">
			  <i class="ti-trash m-r-0-5"></i>Delete</a></span>
							</div>
						</div>';
		} else {
			$dlink = '';
		}
		
		$dlink.= '<div class="media-right"><div class="c-rating"><span data-toggle="tooltip" data-placement="top" title="Reply">
		<button class="btn btn-info btn-sm" onclick="reply_comments('.$r->message_id.');"><i class="fa fa-reply m-r-0-5"></i>&nbsp;Reply</button></span></div></div>';
		if($r->status==1){
			$comments=$r->message_content;
		}else{
		    $comments='<p style="color:red;">Message Deleted.</p>';
		}
		
		
		
		$function = '<div class="c-item">
					<div class="media">
						<div class="media-left">
							<div class="avatar box-48">
							<img class="b-a-radius-circle" src="'.$u_file.'">
							</div>
						</div>
						<div class="media-body">
							<div class="mb-0-5">
								'.$link.'
								<span class="font-90 text-muted">'.$date.' '.$created_at.'</span>
							</div>
							<div class="c-text">'.$comments.'</div>
						</div>
						'.$dlink.'
					</div>
		</div>';
		$function.='<form  method="post" name="set_comment" onsubmit="return show_comments('.$r->message_id.');" class="show_comments" id="show_comments_'.$r->message_id.'" style="display:none;">
                   <input type="hidden" name="birthday_id" id="birthday_id" value="'.$birthday_id.'"/>
		           <input type="hidden" name="birthday_date" id="birthday_date" value="'.$birthday_date.'"/>
				   <input type="hidden" name="parent" value="'.$r->message_id.'"/>			  
			       <input type="hidden" name="user_id" id="user_id" value="'.$this->userSession['user_id'].'">	
				   <input type="hidden" name="to_id" class="to_id_'.$r->message_id.'" value="'.$r->from_id.'"/>				   
              <div class="box-block">                
                <textarea style="margin-top:1em;border: 1px solid #ece;" name="xin_comment" class="col-lg-11 pull-right xin_comments_'.$r->message_id.'" rows="4" placeholder="Reply" required></textarea>
                <br><br><br><br><br><br>
                <button type="submit" class="btn bg-teal-400 pull-right save">Save</button><br><br>
              </div>
            </form>';

        $reply_comments = $this->Employees_model->get_comments($birthday_id,$birthday_date,$r->message_id);  
       
 	    foreach($reply_comments as $re_com){
		$employee_r = $this->Xin_model->read_user_info($re_com->from_id);
		// employee full name
		$employee_name_r = $employee_r[0]->first_name;
		// get designation
		$_designation_r = $this->Designation_model->read_designation_information($employee_r[0]->designation_id);
		// created at
		$created_at1 = date('h:i A', strtotime($re_com->message_date));
		$_date = explode(' ',$re_com->message_date);
		$date1 = $this->Xin_model->set_date_format($_date[0]);
		
		// profile picture
		
		if($employee_r[0]->profile_picture!='' && $employee_r[0]->profile_picture!='no file') {
			$u_file1 = base_url().'uploads/profile/'.$employee_r[0]->profile_picture;
        } else {
			if($employee_r[0]->gender=='Male') { 
				$u_file1 = base_url().'uploads/profile/default_male.jpg';
			} else {
				$u_file1 = base_url().'uploads/profile/default_female.jpg';
			}
        } 	
		if($ses_user[0]->user_role_id==1){
			$link1 = '<span class="underline">'.$employee_name_r.' ('.$_designation_r[0]->designation_name.')</span>';
		} else{
			$link1 = '<span class="underline">'.$employee_name_r.' ('.$_designation_r[0]->designation_name.')</span>';
		}
		if(($ses_user[0]->user_role_id==1 || $ses_user[0]->user_id==$re_com->from_id) && $re_com->status==1){
			$dlink1 = '<div class="media-right">
							<div class="c-rating">
							<span data-toggle="tooltip" data-placement="top" title="Delete">
								<a class="btn btn-danger btn-sm delete" href="#" data-toggle="modal" data-target=".delete-modal" data-record-id="'.$re_com->message_id.'">
			  <i class="ti-trash m-r-0-5"></i>Delete</a></span>
							</div>
						</div>';
		} else {
			$dlink1 = '';
		}
		
		if($re_com->status==1){
			$comments1=$re_com->message_content;
		}else{
		    $comments1='<p style="color:red;">Message Deleted.</p>';
		}
		
		
		
		$function.= '<div class="c-item" style="margin-left:3.5em;margin-top:1em;">
					<div class="media">
						<div class="media-left">
							<div class="avatar box-48">
							<img class="b-a-radius-circle" src="'.$u_file1.'">
							</div>
						</div>
						<div class="media-body">
							<div class="mb-0-5">
								'.$link1.'
								<span class="font-90 text-muted">'.$date1.' '.$created_at1.'</span>
							</div>
							<div class="c-text">'.$comments1.'</div>
						</div>
						'.$dlink1.'
					</div>
		</div>';
		}
		
		$data[] = array(
		    $r->message_id,
			$function
		);
       }

	    $output = array(
		   "draw" => $draw,
			 "recordsTotal" => '',
			 "recordsFiltered" =>'',
			 "data" => $data
		);
	  echo json_encode($output);
	  exit();
     }
	 
	 
	public function delete() {
		if($this->input->post('is_ajax') ==6) {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);		
			$result = $this->Employees_model->hide_comment_record($id);
			if(isset($result)) {
				$Return['result'] = 'Comment deleted.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
		}
	}
	 
	public function set_comment() {	
		if($this->input->post('add_type')=='set_comment') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */		
        if($this->input->post('parent')==0){
		if($this->input->post('xin_comment')==='') {
       		 $Return['error'] = "The comment field is required.";
		} }


		$xin_comment = $this->input->post('xin_comment');
		$qt_xin_comment = htmlspecialchars(addslashes($xin_comment), ENT_QUOTES);				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'message_content' => $qt_xin_comment,
		'from_id' => $this->input->post('user_id'),
		'to_id' => $this->input->post('to_id'),
		'birthday_id' => $this->input->post('birthday_id'),
		'birthday_date' => $this->input->post('birthday_date'),
		'parent'=>$this->input->post('parent'),
		'notification'=>1,
		);	
		$result = $this->Employees_model->add_comment($data);
		if ($result == TRUE) {
			$Return['result'] = 'Comment added.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}


    public function add_schedule(){
        if($this->input->post('add_type')=='add_schedule') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		$schedule_title = $this->input->post('schedule_title');
        $schedule_start = date('Y-m-d H:i:s',strtotime($this->input->post('schedule_start')));
	    $schedule_end = date('Y-m-d H:i:s',strtotime($this->input->post('schedule_end')));
	    $st_date = strtotime($schedule_start);
        $et_date = strtotime($schedule_end);
		$schedule_color = $this->input->post('schedule_color');
        $user_id=$this->input->post('user_id');
		
		if($this->input->post('schedule_title')==='') {
       		 $Return['error'] = "The schedule title field is required.";
		} else if($this->input->post('schedule_start')==='') {
			$Return['error'] = "The schedule start date field is required.";
		} 
		if($this->input->post('schedule_end')!='') {
        if($st_date > $et_date) {
        	$Return['error'] = "Schedule Start Date should be less than or equal to End Date.";
		} 	
		} 

		if($Return['error']!=''){
       		$this->output($Return);
    	}

		
        if($this->input->post('schedule_end')!='')
        $schedule_end = $schedule_end;
        else
        $schedule_end='';
	
		$data = array(
		'user_id' => $this->input->post('user_id'),
		'schedule_title' => $schedule_title,
		'schedule_start' => $schedule_start,
		'schedule_end' => $schedule_end,
		'schedule_color' => $this->input->post('schedule_color'),
		);
		$result = $this->Xin_model->add_schedule($data);
		if ($result == TRUE) {
			$Return['result'] = 'Schedule added.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}

    }


	public function update_schedule(){

        if($this->input->post('add_type')=='update_schedule') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		$schedule_id = $this->input->post('e_schedule_id');
		$schedule_title = $this->input->post('e_schedule_title');
        $schedule_start = date('Y-m-d H:i:s',strtotime($this->input->post('e_schedule_start')));
	    $schedule_end = date('Y-m-d H:i:s',strtotime($this->input->post('e_schedule_end')));
	    $st_date = strtotime($schedule_start);
        $et_date = strtotime($schedule_end);
		$schedule_color = $this->input->post('e_schedule_color');
       
	
		if($this->input->post('e_schedule_title')==='') {
       		 $Return['error'] = "The schedule title field is required.";
		} else if($this->input->post('e_schedule_start')==='') {
			$Return['error'] = "The schedule start date field is required.";
		} 
		if($this->input->post('e_schedule_end')!='') {
        if($st_date > $et_date) {
        	$Return['error'] = "Schedule Start Date should be less than or equal to End Date.";
		} 	
		} 

		if($Return['error']!=''){
       		$this->output($Return);
    	}

		
        if($this->input->post('e_schedule_end')!='')
        $schedule_end = $schedule_end;
        else
        $schedule_end='';
	
		$data = array(
		'schedule_title' => $schedule_title,
		'schedule_start' => $schedule_start,
		'schedule_end' => $schedule_end,
		'schedule_color' => $this->input->post('e_schedule_color'),
		);
		$result = $this->Xin_model->update_schedule($data,$schedule_id);
		if ($result == TRUE) {
			$Return['result'] = 'Schedule Updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}

    }
	
	public function get_my_schedule(){
     $user_id=$this->uri->segment(3);
     $my_schedule='';
     $schedule = $this->Xin_model->get_my_schedule($user_id);
	 if($schedule){
		foreach($schedule as $sch){
  
         $my_schedule[]=array('title'=>$sch->schedule_title,'start'=>$sch->schedule_start,'end'=>$sch->schedule_end,'color'=>$sch->schedule_color,'id'=>$sch->schedule_id);


		}
     }

       echo json_encode($my_schedule);
	}
	
	public function delete_my_schedule(){
     $delete_id=$this->uri->segment(3);   
     $result = $this->Xin_model->delete_my_schedule($delete_id);
	 $Return['result'] = 'Schedule deleted.';
     $this->output($Return);
	 exit;
    }

    public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('event_id');
       // $data['all_countries'] = $this->xin_model->get_countries();
		$result = $this->Xin_model->read_schedule_information($id);
		$data = array(
				'schedule_title' => $result[0]->schedule_title,
				'schedule_start' => $result[0]->schedule_start,
				'schedule_end' => $result[0]->schedule_end,
				'schedule_color' => $result[0]->schedule_color,
	            'schedule_id' => $result[0]->schedule_id,
				);
		if(!empty($this->userSession)){ 
			$this->load->view('dashboard/dialog_dashboard', $data);
		} else {
			redirect('');
		}
	}

	
	function test_uaa_function(){
	
		$start_date = date('Y-m-d',strtotime('2019-01-01'));
		$end_date = date('Y-m-d',strtotime('2019-12-31'));

		$this->db->join('xin_employees',"xin_employees.user_id=xin_attendance_time.employee_id");
		$this->db->join('xin_office_location',"xin_employees.office_location_id=xin_office_location.location_id");
		$this->db->where('xin_employees.is_active', 1);
		$this->db->where('xin_employees.residing_country', 224);
		$this->db->where('xin_employees.user_id', 2);
		$this->db->where('attendance_date >=', $start_date);
		$this->db->where('attendance_date <=', $end_date);
		
		$this->db->select('*');
		$this->db->from('xin_attendance_time');
		$this->db->where('xin_attendance_time.attendance_status','Absent');
		
		$query = $this->db->get();
		$attendance_details = $query->result();

		// pr($attendance_details);die;

		$real_status_array = array();
		for ($a=0; $a < count($attendance_details); $a++) { 
			
			$real_status = check_leave_status($attendance_details[$a]->attendance_date,$attendance_details[$a]->user_id,$attendance_details[$a]->attendance_status,$attendance_details[$a]->clock_in,$attendance_details[$a]->clock_out,$attendance_details[$a]->shift_start_time,$attendance_details[$a]->shift_end_time,$attendance_details[$a]->week_off,$attendance_details[$a]->department_id,$attendance_details[$a]->country);

			if($real_status == 'Absent'){

				$temp_var = $attendance_details[$a]->attendance_date.'---'.$real_status;
				array_push($real_status_array, $temp_var);
			}


		}

		pr($real_status_array);

	}
}
