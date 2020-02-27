<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author Siddiqkhan
 *
 * @Timesheet Controller
 */
class Timesheet extends MY_Controller {

    protected $userSession = null;

	public function __construct() {
		Parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->database();
		$this->load->library('form_validation');
		//load the model
		$this->load->library('email');
		$this->load->model("Timesheet_model");
		$this->load->model("Employees_model");
		$this->load->model("Xin_model");
		$this->load->model("Department_model");
		$this->load->model("Designation_model");
		$this->load->model("Roles_model");
		$this->load->model("Location_model");
		$this->load->model("Payroll_model");
        $this->userSession = $this->session->userdata('username');
	}

	public function output($Return=array()){
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}

	public function attendance()
	{
		$data['title'] = $this->Xin_model->site_title();
		$data['breadcrumbs'] = 'Attendance';
		$data['path_url'] = 'attendance';
		if(in_array('29',role_resource_ids())) {
			if(!empty($this->userSession)){
				$data['subview'] = $this->load->view("timesheet/attendance_list", $data, TRUE);
				$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
	}

	public function read_leave_chk_avail(){
		$data['title'] = $this->Xin_model->site_title();
		$data['all_leave_types'] = $this->Timesheet_model->all_leave_types();
		if($this->userSession['role_name'] == 'Drivers Admin'){
			$data['all_employees']=$this->Xin_model->get_empoloyee_byrole($this->userSession['user_id'],$this->userSession['role_name']);
		}
		else if(in_array('32a',role_resource_ids())) {

			$hod_id = hod_manager_access();
			$reporting_manager_id = reporting_manager_access();
			if($hod_id != ''){
				$data['all_employees'] = $this->Xin_model->get_empoloyee_byrole_with_con($this->userSession['user_id'],$this->userSession['role_name'],$user_info[0]->department_id);
			}elseif($reporting_manager_id){
				$data['all_employees'] = $this->Xin_model->get_empoloyee_byrole_with_con($this->userSession['user_id'],$this->userSession['role_name'],'',$reporting_manager_id);
			}else{
				$data['all_employees']=$this->Xin_model->get_empoloyee_byrole($this->userSession['user_id'],AD_ROLE);
			}
		}
		else{
			$data['all_employees']=$this->Xin_model->get_empoloyee_byrole($this->userSession['user_id'],$this->userSession['role_name']);
		}
		$data['btn_type'] =$this->input->get('btn_type');
		if(!empty($this->userSession)){
			$this->load->view('timesheet/dialog_leave', $data);
		} else {
			redirect('');
		}
	}

	public function missed_login_alerts(){
		if($this->input->get('data')=='missed_login_alerts'){
			$user_id=$this->input->get('user_id');
			$setting = $this->Xin_model->read_setting_info(1);
			$login_alerts = json_decode($setting[0]->alerts);
			if($login_alerts->missed_login_notification=='yes'){
				$query = $this->db->query("SELECT attendance_status,shift_start_time from xin_attendance_time where employee_id='".$user_id."' AND attendance_date='".TODAY_DATE."' AND attendance_status='Absent' AND login_alerts=0 limit 1");
				$result=$query->result();
				if($result){
					$current_time=date('H:i');
					$shift_start_time=$result[0]->shift_start_time;
					$alert_time=convert_hours_seconds($login_alerts->login_alert_hours)/60;
					$alert_trigger_time=date('H:i',strtotime('+'.$alert_time.' minutes',strtotime($shift_start_time)));
					if(strtotime($alert_trigger_time)<=strtotime($current_time)){
						echo 1;
					}
				}
			}
		}
	}

	public function update_login_alerts(){
		if($this->input->post('data')=='update_login_alerts'){
			$user_id=$this->input->post('user_id');
			$this->db->query("update xin_attendance_time set login_alerts=1 where employee_id='".$user_id."' AND attendance_date='".TODAY_DATE."'");
		}
	}

	public function date_wise_attendance()
	{
		$data['title'] = $this->Xin_model->site_title();
		$data['all_employees'] = $this->Xin_model->all_active_employees(); // Xin_model->all_employees
		$data['breadcrumbs'] = 'Date Wise Attendance';
		$data['path_url'] = 'date_wise_attendance';
		if(in_array('30',role_resource_ids()) || visa_wise_role_ids() != '') {
			if(!empty($this->userSession)){
				$data['subview'] = $this->load->view("timesheet/date_wise", $data, TRUE);
				$this->load->view('layout_main', $data); //page load
			}
			else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
	}

	public function update_attendance()
	{
		$data['title'] = $this->Xin_model->site_title();
		$data['breadcrumbs'] = 'Manual Attendance (OB)';
		$data['path_url'] = 'update_attendance';
		if(in_array('31',role_resource_ids()) || in_array('31a',role_resource_ids()) || in_array('31e',role_resource_ids()) || in_array('31d',role_resource_ids()) || in_array('31v',role_resource_ids())) {
			if(!empty($this->userSession)){
				$data['subview'] = $this->load->view("timesheet/update_attendance", $data, TRUE);
				$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
	}

	public function add_manual_ob_hours()
	{
		$data['title'] = $this->Xin_model->site_title();
		$data['breadcrumbs'] = 'Add Manual OB Hours';
		$data['path_url'] = 'update_attendance';
		$user_info 			= $this->Xin_model->read_user_info($this->userSession['user_id']);
		if((rp_manager_access() || hod_manager_access() || (in_array('31v',role_resource_ids()))) || visa_wise_role_ids() != '') {
			$hod_id = hod_manager_access();
			$reporting_manager_id =rp_manager_access();

			if(in_array('31v',role_resource_ids()) || visa_wise_role_ids() != '') {
				$employeesArray = $this->Xin_model->getEmployeesLevel();			
			}
			else if($hod_id!=''){
				$employeesArray = $this->Xin_model->getEmployeesLevel('hod',$hod_id,$user_info[0]->department_id);				
			}else if($reporting_manager_id!=''){
				$employeesArray = $this->Xin_model->getEmployeesLevel('reportingmanager',$reporting_manager_id,$user_info[0]->department_id);
			}else{
				$employeesArray = $this->Xin_model->getEmployeesLevel();
			}			

			$data['obList'] = $this->Xin_model->getModuleList('ob_type');
			//pr($employeesArray);die;
			$data['employeesArray'] = $employeesArray;
			$data['subview'] = $this->load->view("timesheet/add_ob_hours", $data, TRUE);
			$this->load->view('layout_main', $data); //page load		
		}
		else {
			redirect('');
		}
	}

	public function leave_expiry_adjustments()
	{
		$data['title'] = $this->Xin_model->site_title();
		$data['breadcrumbs'] = 'Annual Leave Expiry Adjustments';
		$data['path_url'] = 'leave_expiry_adjustments';
		$user_info 			= $this->Xin_model->read_user_info($this->userSession['user_id']);
		if(in_array('al-expiry-view',role_resource_ids()) || visa_wise_role_ids() != '') {
			$employeesArray = $this->Xin_model->getEmployeesLevel();
			$data['expireList'] = $this->Xin_model->getModuleList('leave_expired_type');
			//pr($employeesArray);die;
			$data['employeesArray'] = $employeesArray;
			$data['subview'] = $this->load->view("timesheet/leave_expiry_adjustments", $data, TRUE);
			$this->load->view('layout_main', $data); //page load		
		}
		else {
			redirect('');
		}
	}

	public function bus_lateness()
	{
		$data['title'] = $this->Xin_model->site_title();
		$data['breadcrumbs'] = 'Bus Lateness';
		$data['path_url'] = 'bus_lateness';
		if(in_array('31',role_resource_ids()) || in_array('31a',role_resource_ids()) || in_array('31e',role_resource_ids()) || in_array('31d',role_resource_ids()) || in_array('31v',role_resource_ids()) || visa_wise_role_ids() != '') {
			if(!empty($this->userSession)){
				$data['subview'] = $this->load->view("timesheet/bus_lateness", $data, TRUE);
				$this->load->view('layout_main', $data);
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
	}

	public function bus_lateness_timing(){
		if($this->input->post('add_type')=='bus_lateness_timing') {
			$Return = array('result'=>'', 'error'=>'', 'message'=>'');
			$added_by=$this->input->post('user_id');
			$bus_late_date=format_date('Y-m-d',$this->input->post('select_date'));
			$bus_late_time=$this->input->post('bus_arrived_time');
			$bus_scheduled_time=$this->input->post('bus_scheduled_time');
			$location_id=$this->input->post('location_id');

			/*Check Hours*/
			$shift_starts = new DateTime($bus_scheduled_time);
			$bus_late_times = new DateTime($bus_late_time);
			$interval = $shift_starts->diff($bus_late_times);
			$check_hours   = decimalHourswithoutround($interval->format('%h').':'.$interval->format('%i'));
			/*Check Hours*/

			if($location_id===''){
				$Return['error'] = 'Location field is required';
			}else if($this->input->post('select_date')===''){
				$Return['error'] = 'Select date field is required';
			}else if($bus_late_time===''){
				$Return['error'] = 'Bus actual arrived time field is required';
			}else if($bus_scheduled_time===''){
				$Return['error'] = 'Bus sheduled time field is required';
			}else if(strtotime($bus_scheduled_time) > strtotime($bus_late_time)){
				$Return['error'] = 'The bus arrived time should be greater than or equal to scheduled time.';
			}else if($check_hours > 1){
				$Return['error'] = 'The bus arrived time should not be greater than 1 hour.';
			}

			if($Return['error']!=''){
				$this->output($Return);
			}

			$data=array(
				'location_id'=>$location_id,
				'bus_late_date'=>$bus_late_date,
				'bus_late_time'=>$bus_late_time,
				'bus_scheduled_time'=>$bus_scheduled_time,
				'added_by'=>$added_by
			);

			$result=$this->Timesheet_model->add_bus_lateness($data);

			if($result==true){
				$Return['result'] = 'Bus Lateness Added.';
			}else{
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function update_bus_lateness(){
		if($this->input->post('edit_type')=='update_bus_lateness') {
			$Return = array('result'=>'', 'error'=>'', 'message'=>'');
			$bus_late_date=format_date('Y-m-d',$this->input->post('select_date'));
			$bus_late_time=$this->input->post('bus_arrived_time');
			$bus_scheduled_time=$this->input->post('bus_scheduled_time');
			$location_id=$this->input->post('location_id');
			$bus_late_id=$this->input->post('_token');

			/*Check Hours*/
			$shift_starts = new DateTime($bus_scheduled_time);
			$bus_late_times = new DateTime($bus_late_time);
			$interval = $shift_starts->diff($bus_late_times);
			$check_hours   = decimalHourswithoutround($interval->format('%h').':'.$interval->format('%i'));
			/*Check Hours*/

			if($location_id===''){
				$Return['error'] = 'Location field is required';
			}else if($this->input->post('select_date')===''){
				$Return['error'] = 'Select date field is required';
			}else if($bus_late_time===''){
				$Return['error'] = 'Bus actual arrived time field is required';
			}else if($bus_scheduled_time===''){
				$Return['error'] = 'Bus sheduled time field is required';
			}else if(strtotime($bus_scheduled_time) > strtotime($bus_late_time)){
				$Return['error'] = 'The bus arrived time should be greater than or equal to scheduled time.';
			}else if($check_hours > 1){
				$Return['error'] = 'The bus arrived time should not be greater than 1 hour.';
			}
			if($Return['error']!=''){
				$this->output($Return);
			}
			$data=array(
				'location_id'=>$location_id,
				'bus_late_date'=>$bus_late_date,
				'bus_late_time'=>$bus_late_time,
				'bus_scheduled_time'=>$bus_scheduled_time,
				'added_by'=>$this->userSession['user_id']
			);

			$result=$this->Timesheet_model->update_bus_lateness($data,$bus_late_id);
			if($result==true){
				$Return['result'] = 'Bus lateness updated.';
			}else{
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function bus_lateness_list(){
		$data['title'] = $this->Xin_model->site_title();
		$draw = intval($this->input->get("draw"));
		$bus_lists = $this->Timesheet_model->bus_lateness_list();
		$data = array();
		foreach($bus_lists as $list) {
			/*Check Hours*/
			$bus_scheduled_time = new DateTime($list->bus_scheduled_time);
			$bus_late_times = new DateTime($list->bus_late_time);
			$interval = $bus_scheduled_time->diff($bus_late_times);
			$check_hours   = $interval->format('%h').' h '.$interval->format('%i').' m';
			/*Check Hours*/
			$full_name = change_fletter_caps($list->first_name.' '.$list->middle_name.' '.$list->last_name);
			$edit_perm='';
			$delete_perm='';
			if(in_array('31e',role_resource_ids())) {
				$edit_perm='<li><a class="edit-data" href="#" data-toggle="modal" data-target=".edit-modal-data" data-bus_late_id="'. $list->bus_late_id.'" title="Edit"><i class="icon-pencil7"></i> Edit</a></li>';
			}

			if(in_array('31d',role_resource_ids())) {
				$delete_perm='<li><a class="delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $list->bus_late_id . '" title="Delete"><i class="icon-trash"></i> Delete</a></li>';
			}

			if($edit_perm=='' && $delete_perm==''){
				$edit_perm='<li><a class="text-danger">No Permission</a></li>';
			}


			$functions = '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right"> '.$edit_perm.$delete_perm.'</ul></li></ul>';

    		$data[] = array(
				$list->location_name,
				$this->Xin_model->set_date_format($list->bus_late_date),
				$list->bus_scheduled_time,
				$list->bus_late_time,
				$check_hours,
				$full_name,
				format_date('d-M-Y H:i:s',$list->added_date),
				format_date('d-M-Y H:i:s',$list->updated_date),
				$functions,
			);
		}
		$output = array(
                "draw" => $draw,
                "recordsTotal" => count($data),
                "recordsFiltered" => count($data),
                "data" => $data
		);
		$this->output($output);
	}

	public function count_lateness($late_array){
		if($late_array){
			foreach($late_array as $arr){
				$value = array_sum(array_column($arr,'late_count'));
			}
		}else{
			return 0;
		}
	}

	public function import()
	{
		$data['title'] = $this->Xin_model->site_title();
		$data['breadcrumbs'] = 'Import Attendance';
		$data['path_url'] = 'import_attendance';
		$data['all_employees'] = $this->Xin_model->all_employees();
		if(in_array('31',role_resource_ids())) {
			if(!empty($this->userSession)){
				$data['subview'] = $this->load->view("timesheet/attendance_import", $data, TRUE);
				$this->load->view('layout_main', $data);
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
	}

	public function import_attendance() {
		if($this->input->post('is_ajax')=='3') {
			$Return = array('result'=>'', 'error'=>'');
			$csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');

			if(empty($_FILES['file']['name'])) {
				$Return['error'] = 'Please upload csv or excel file.';
			} else {
				if(in_array($_FILES['file']['type'],$csvMimes)){
					if(is_uploaded_file($_FILES['file']['tmp_name'])){

						// check file size
						if(filesize($_FILES['file']['tmp_name']) > 512000) {
							$Return['error'] = 'File size is greater than 500 KB';
						} else {

							//open uploaded csv file with read only mode
							$csvFile = fopen($_FILES['file']['tmp_name'], 'r');

							//skip first line
							fgetcsv($csvFile);

							//parse data from csv file line by line
							while(($line = fgetcsv($csvFile)) !== FALSE){

								$attendance_date = $line[1];
								$clock_in = $line[2];
								$clock_out = $line[3];
								$clock_in2 = $attendance_date.' '.$clock_in;
								$clock_out2 = $attendance_date.' '.$clock_out;

								//total work
								$total_work_cin =  new DateTime($clock_in2);
								$total_work_cout =  new DateTime($clock_out2);

								$interval_cin = $total_work_cout->diff($total_work_cin);
								$hours_in   = $interval_cin->format('%h');
								$minutes_in = $interval_cin->format('%i');
								$total_work = $hours_in .":".$minutes_in;

								$data = array(
									'employee_id' => $line[0],
									'attendance_date' => $attendance_date,
									'clock_in' => $clock_in2,
									'clock_out' => $clock_out2,
									'time_late' => $clock_in2,
									'total_work' => $total_work,
									'early_leaving' => $clock_out2,
									'overtime' => $clock_out2,
									'attendance_status' => 'Present',
									'clock_in_out' => '0'
								);
								$result = $this->Timesheet_model->add_employee_attendance($data);
							}
							//close opened csv file
							fclose($csvFile);

							$Return['result'] = 'Attendance Data Imported Successfully.';
						}
					}else{
						$Return['error'] = 'Attendance not imported.';
					}
				}else{
					$Return['error'] = 'Invalid File';
				}
			} // file empty

			if($Return['error']!=''){
				$this->output($Return);
			}
			$this->output($Return);
			exit;
		}
	}

	public function office_shift() {
		$data['title'] = $this->Xin_model->site_title();
		$data['breadcrumbs'] = 'Office Shift';
		$data['path_url'] = 'office_shift';
		if(in_array('34',role_resource_ids()) || in_array('34a',role_resource_ids()) || in_array('34e',role_resource_ids()) || in_array('34d',role_resource_ids()) || in_array('34v',role_resource_ids())) {
			if(!empty($this->userSession)){
				$data['subview'] = $this->load->view("timesheet/office_shift", $data, TRUE);
				$this->load->view('layout_main', $data);
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
	}

	public function change_schedule() {
		$data['title'] = $this->Xin_model->site_title();
		$data['breadcrumbs'] = 'Change Schedule';
		$data['path_url'] = 'change_schedule';	

		$user_info 		= $this->Xin_model->read_user_info($this->userSession['user_id']);
		if((rp_manager_access() || hod_manager_access() || (in_array('34v',role_resource_ids()))) ) {
			$hod_id = hod_manager_access();
			$reporting_manager_id =rp_manager_access();

			if(in_array('31v',role_resource_ids())) {
				$employeesArray = $this->Xin_model->getEmployeesLevel();			
			}
			else if($hod_id!=''){
				$employeesArray = $this->Xin_model->getEmployeesLevel('hod',$hod_id,$user_info[0]->department_id);				
			}else if($reporting_manager_id!=''){
				$employeesArray = $this->Xin_model->getEmployeesLevel('reportingmanager',$reporting_manager_id,$user_info[0]->department_id);
			}else{
				$employeesArray = $this->Xin_model->getEmployeesLevel();
			}			
			//show_data($employeesArray);die;
			$data['employeesArray'] = $employeesArray;
			$data['subview'] = $this->load->view("timesheet/change_schedule", $data, TRUE);
			$this->load->view('layout_main', $data); //page load	
		}
		else {
			redirect('');
		}

	}

	public function holidays() {
		$data['title'] = $this->Xin_model->site_title();
		$data['breadcrumbs'] = 'Public Holidays';
		$data['path_url'] = 'holidays';
		if(in_array('35',role_resource_ids()) || in_array('35a',role_resource_ids()) || in_array('35e',role_resource_ids()) || in_array('35d',role_resource_ids()) || in_array('35v',role_resource_ids()) || visa_wise_role_ids() != '') {
			if(!empty($this->userSession)){
				$data['subview'] = $this->load->view("timesheet/holidays", $data, TRUE);
				$this->load->view('layout_main', $data);
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
	}

	public function ramadan_schedule() {
		$data['title'] = $this->Xin_model->site_title();
		$data['breadcrumbs'] = 'Exceptional Schedule';
		$data['path_url'] = 'ramadan_schedule';
		if(in_array('34',role_resource_ids()) || in_array('34a',role_resource_ids()) || in_array('34e',role_resource_ids()) || in_array('34d',role_resource_ids()) || in_array('34v',role_resource_ids())) {
			if(!empty($this->userSession)){
				$data['subview'] = $this->load->view("timesheet/ramadan_schedule", $data, TRUE);
				$this->load->view('layout_main', $data);
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
	}

	public function dynamic_depatment_add(){
		$count_id=$_GET['count_id'];
		$dept_id=$_GET['department_id'];
		$html='';
		$html.='<div class="row" id="parent_div_'.$count_id.'">
		<div class="col-md-5">
		 <div class="form-group" >
          <label for="name" >'.$this->lang->line('xin_department').'</label>
          <select class="select2 selected_dept" data-plugin="select_hrm" data-placeholder="'.$this->lang->line('xin_select_department').'..." name="department_id_'.$count_id.'[]" multiple="multiple">';
		    foreach($this->Department_model->all_departments_not($dept_id) as $deparment) {
			    $html.='<option  value="'.$deparment->department_id.'">'.$deparment->department_name.'</option>';
		    }
		$html.='</select>
        </div>
		</div>
          <div class="col-md-5">
            <div class="form-group">
              <label for="start_date">Select Date</label>
               <input type="text" class="form-control daterange-basic" name="select_date[]" value="'.date('d F Y').'-'.date('d F Y').'" readonly> 
            </div>
          </div>	  
		  <div class="col-md-2">
		   <div class="form-group">
		       <label style="visibility:hidden;">Delete</label>
			   <div class="clearfix"></div>
            <input type="button" class="btn bg-danger" value="X" onclick="delete_append_div('.$count_id.')">
            </div>		  
		  </div>
        </div>';
		echo $html;
	}

	public function leave() {
		
		$data['title'] = $this->Xin_model->site_title();
		$data['all_employees']=$this->Xin_model->get_empoloyee_byrole($this->userSession['user_id'],$this->userSession['role_name']);
		$data['all_leave_types'] = $this->Timesheet_model->all_leave_types();
		$data['breadcrumbs'] = 'Leave';
		$data['path_url'] = 'leave';
		if(in_array('32',role_resource_ids()) || in_array('32a',role_resource_ids()) || in_array('32e',role_resource_ids()) || in_array('32d',role_resource_ids()) || in_array('32v',role_resource_ids()) || reporting_manager_access() || get_ceo_only() || hod_manager_access() || visa_wise_role_ids() != '') {
			if(!empty($this->userSession)){
				$data['subview'] = $this->load->view("timesheet/leave", $data, TRUE);
				$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
	}

	public function leavecard() {

		$user_info 	= $this->Xin_model->read_user_info($this->userSession['user_id']);
		if(in_array('32lv',role_resource_ids()) || visa_wise_role_ids() != '') { 
			$data['all_employees'] = $this->Xin_model->all_employees();
		}else{

			$hod_id = hod_manager_access();
			$reporting_manager_id = reporting_manager_access();
			if($hod_id != ''){
				$data['all_employees'] = $this->Xin_model->get_empoloyee_byrole_with_con($this->userSession['user_id'],$this->userSession['role_name'],$user_info[0]->department_id);
			}elseif($reporting_manager_id){
				$data['all_employees'] = $this->Xin_model->get_empoloyee_byrole_with_con($this->userSession['user_id'],$this->userSession['role_name'],'',$reporting_manager_id);
			}else{
				$data['all_employees'] = $this->Xin_model->get_empoloyee_byrole_with_con($this->userSession['user_id'],$this->userSession['role_name']);
			}

		}

		$data['title'] = $this->Xin_model->site_title();
		$data['breadcrumbs'] = "Employee's Leave Card";
		$data['path_url'] = 'leavecard';
		if(in_array('32lv',role_resource_ids()) || visa_wise_role_ids() != '' || reporting_manager_access() || hod_manager_access()) {
			if(!empty($this->userSession)){
				$data['subview'] = $this->load->view("timesheet/leavecard", $data, TRUE);
				$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
	}

	public function read_leave_card(){
		if($this->input->get('type')=='read_leave_card') {
			$employee_id=$this->input->get('employee_id');
			$user = $this->Xin_model->read_user_info($employee_id);
			$designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
			$department = $this->Department_model->read_department_information($user[0]->department_id);
			$location = $this->Location_model->read_location_information($user[0]->office_location_id);
			$date_of_joining=$user[0]->date_of_joining;
			$date_of_leaving=$user[0]->date_of_leaving;
			$_9months=date('Y-m-d',strtotime("+9 months",strtotime($date_of_joining)));
			$_6months=date('Y-m-d',strtotime("+6 months",strtotime($date_of_joining)));
			$_12months=date('Y-m-d',strtotime("+12 months",strtotime($date_of_joining)));
			$doj='N/A';
			$_6th='N/A';
			$_9th='N/A';
			$_12th='N/A';
			if($date_of_joining!=''){
				$doj=$this->Xin_model->set_date_format($date_of_joining);
			}
			if($_6months!=''){
				$_6th=$this->Xin_model->set_date_format($_6months);
			}
			if($_9months!=''){
				$_9th=$this->Xin_model->set_date_format($_9months);
			}
			if($_12months!=''){
				$_12th=$this->Xin_model->set_date_format($_12months);
			}
			/*$annual_leave_start_date=date('Y-m-d',strtotime(ANNUAL_LEAVE_ALLOW_EOS,strtotime($date_of_joining)));
            $annual_leave_full_start_date=date('Y-m-d',strtotime(ANNUAL_LEAVE_ALLOW_FULL,strtotime($date_of_joining)));
            */
			//Annual Leave
			$annual_leave='Annual Leave';
			$annual_leave_id=$this->Timesheet_model->read_leave_type_id($annual_leave);
			$annual_query = $this->db->query("SELECT * FROM `xin_leave_applications` WHERE `employee_id` = '".$employee_id."' AND 
		   `leave_type_id`='".$annual_leave_id."' AND status=2 AND reporting_manager_status=2 order by from_date ASC");
			$annual_result = $annual_query->result();
			//Annual Leave
			$balance_leave=0;

			if($date_of_leaving!=''){
				$calculate_date=$date_of_leaving;
			}else{
				$calculate_date=TODAY_DATE;
			}

			$an_leave_bal=annual_leave_balance($employee_id,$date_of_joining,$calculate_date);
			$balance_leave=$an_leave_bal['balance_leave'];
			$total_leave_annual=$an_leave_bal['total_leave_accrued'];
			$total_leave_availed=$an_leave_bal['leave_availed'];
			//Sick Leave
			$sick_leave='Sick Leave';
			$sick_leave_id=$this->Timesheet_model->read_leave_type_id($sick_leave);
			$sick_query = $this->db->query("SELECT * FROM `xin_leave_applications` WHERE `employee_id` = '".$employee_id."' AND 
		   `leave_type_id`='".$sick_leave_id."' AND status=2 AND reporting_manager_status=2 order by from_date ASC");
			$sick_result = $sick_query->result();
			//Sick Leave


			//Leave Coversion
			$leave_conversion_query = $this->db->query("SELECT * FROM `xin_leave_conversion_count` WHERE `employee_id` = '".$employee_id."' AND 
		   leave_conversion_count!=0 AND leave_conversion_count!=''  AND approved_status=1 order by added_date ASC");
			$leave_conversion_result = $leave_conversion_query->result();

			//Leave Coversion
			$expiry_leave_query = $this->db->query("SELECT adj.*,tp.type_name FROM `xin_employee_adjustments` as adj left join xin_module_types as tp on tp.type_id=adj.adjust_type_id WHERE adj.adjust_employee_id = '".$employee_id."' order by adj.adjust_id ASC");
			$expiry_leave_result = $expiry_leave_query->result();


			//Leave Coversion
			
			//Maternity Leave
			$maternity_leave='Maternity Leave';
			$maternity_leave_id=$this->Timesheet_model->read_leave_type_id($maternity_leave);
			$maternity_query = $this->db->query("SELECT * FROM `xin_leave_applications` WHERE `employee_id` = '".$employee_id."' AND 
		   `leave_type_id`='".$maternity_leave_id."' AND status=2 AND reporting_manager_status=2 order by from_date ASC");
			$maternity_result = $maternity_query->result();
			//Maternity Leave


			//Emergency Leave
			$emergency_leave='Emergency Leave';
			$emergency_leave_id=$this->Timesheet_model->read_leave_type_id($emergency_leave);
			$emergency_query = $this->db->query("SELECT * FROM `xin_leave_applications` WHERE `employee_id` = '".$employee_id."' AND 
		   `leave_type_id`='".$emergency_leave_id."' AND status=2 AND reporting_manager_status=2 order by from_date ASC");
			$emergency_result = $emergency_query->result();
			//Emergency Leave


			//Authorised Leave
			$authorise_leave='Authorised Absence';
			$authorise_leave_id=$this->Timesheet_model->read_leave_type_id($authorise_leave);
			$authorise_query = $this->db->query("SELECT * FROM `xin_leave_applications` WHERE `employee_id` = '".$employee_id."' AND 
		   `leave_type_id`='".$authorise_leave_id."' AND status=2 AND reporting_manager_status=2 order by from_date ASC");
			$authorise_result = $authorise_query->result();
			//Authorised Leave

			$html='';
			$html.='<div class="table-responsive"><table class="table table-md table-bordered"><tbody>
		<tr class="bg-slate-600 text-center"><td colspan="4"><strong>'.change_fletter_caps(@$user[0]->first_name.' '.@$user[0]->middle_name.' '.@$user[0]->last_name).'\'s Leave Card</strong></td></tr>
		<tr class="success"><td>Joining Date</td><td><strong>'.@$doj.'</strong></td><td>6<sup>th</sup> Month</td><td><strong>'.@$_6th.'</strong></td></tr>	
		<tr class="success"><td>9<sup>th</sup> Month</td><td><strong>'.@$_9th.'</strong></td><td>12<sup>th</sup> Month</td><td><strong>'.@$_12th.'</strong></td></tr>
		<tr class="success"><td>Department</td><td><strong>'.$department[0]->department_name.'</strong></td><td>Designation</td><td><strong>'.$designation[0]->designation_name.'</strong></td></tr><tr class="success"><td >Location</td><td colspan=3><strong>'.$location[0]->location_name.'</strong></td></tr>
		</tbody></table></div>';

			$html.='<div class="no-padding col-lg-6 table-responsive"><table class="table table-md table-bordered"><tbody>
		<tr class="danger text-center"><td colspan="3"><strong>Annual Leave</strong></td></tr>		
		<tr class=""><td><strong>From Date</strong></td><td><strong>To date</strong></td><td><strong>Total</strong></td></tr>';
			$last_balance=0;
			if($annual_result){
				foreach($annual_result as $a_result){
					$html.='<tr class=""><td>'.$this->Xin_model->set_date_format($a_result->from_date).'</td><td>'.$this->Xin_model->set_date_format($a_result->to_date).'</td><td>'.$a_result->count_of_days.'</td></tr>';
					$last_balance=($a_result->available_leave_days-$a_result->count_of_days);
				}
				$leave_conversion_count=0;
				if($leave_conversion_result){
					$html.='<tr class="text-center danger"><td colspan="3"><strong>Leave Conversion</strong></td></tr>';
					$html.='<tr><td><strong>Added Date</strong></td><td><strong>Comments</strong></td><td><strong>Converted Days</strong></td></tr>';
					foreach($leave_conversion_result as $l_c_res){
						$html.='<tr class=""><td>'.$this->Xin_model->set_date_format($l_c_res->added_date).'</td><td>'.$l_c_res->conversion_comments.'</td><td>'.$l_c_res->leave_conversion_count.'</td></tr>';
						$leave_conversion_count+=$l_c_res->leave_conversion_count;
					}
				}


				$adjust_days=0;
				$expired_html='';
				if($expiry_leave_result){					
					foreach($expiry_leave_result as $exp_res){
						$expired_html.='<tr><td></td><td><strong>'.$exp_res->type_name.'</strong></td><td><strong>'.$exp_res->adjust_days.' Day/s</strong></td></tr>';
						$adjust_days+=$exp_res->adjust_days;
					}
				}

				$last_balance=$balance_leave;			
			}
			else{
				$leave_conversion_count=0;
				if($leave_conversion_result){
					$html.='<tr class="text-center danger"><td colspan="4"><strong>Leave Conversion</strong></td></tr>';
					$html.='<tr><td></td><td><strong>Days Count</strong></td><td><strong>Comments</strong></td><td><strong>Added Date</strong></td></tr>';
					foreach($leave_conversion_result as $l_c_res){
						$html.='<tr class=""><td></td><td>'.$l_c_res->leave_conversion_count.'</td><td>'.$l_c_res->conversion_comments.'</td><td>'.$this->Xin_model->set_date_format($l_c_res->added_date).'</td></tr>';
						$leave_conversion_count+=$l_c_res->leave_conversion_count;
					}
				}

				$adjust_days=0;
				$expired_html='';
				if($expiry_leave_result){					
					foreach($expiry_leave_result as $exp_res){
						$expired_html.='<tr><td></td><td><strong>'.$exp_res->type_name.'</strong></td><td><strong>'.$exp_res->adjust_days.' Day/s</strong></td></tr>';
						$adjust_days+=$exp_res->adjust_days;
					}
				}

				$last_balance=$balance_leave;
				
			}
			$total_leave_availed = $total_leave_availed-$adjust_days;
			if($total_leave_availed < 0) {
				$total_leave_availed = 0;
			}
			$html.='<tr><td></td><td><strong>Total Earned Leaves</strong></td><td><strong>'.$total_leave_annual.' Day/s</strong></td></tr><tr><td></td><td><strong>Total Leaves Availed</strong></td><td><strong>'.$total_leave_availed.' Day/s</strong></td></tr>'.$expired_html.'<tr><td></td><td><strong>Balance Earned Leaves</strong></td><td><strong>'.$last_balance.' Day/s</strong></td></tr>';
			$html.='</tbody></table></div>';

			$html.='<div class="no-padding col-lg-6 table-responsive"><table class="table table-md table-bordered"><tbody>
                    <tr class="warning text-center"><td colspan="6"><strong>Sick Leave</strong></td></tr>		
                    <tr class=""><td><strong>Applied Leave Date/s</strong></td><td><strong>Total Leave</strong></td></tr>';

			if($sick_result){
				foreach($sick_result as $s_result){
					if($s_result->leave_status_code == 'SL-1'){
						$status='<strong class="text-success">Full Payment</strong>';
					}else if($s_result->leave_status_code == 'SL-2'){
						$status='<strong class="text-warning">Half Payment</strong>';
					}else{
						$status='<strong class="text-danger">No Payment</strong>';
					}
					$html.='<tr class=""><td>'.$this->Xin_model->set_date_format($s_result->from_date).' to '.$this->Xin_model->set_date_format($s_result->to_date).' ('.$status.')'.'</td><td>'.($s_result->count_of_days).'</td></tr>';
				}
			}
			else{
				$html.='<tr class="text-center"><td colspan="6">N/A</td></tr>';
			}
			$html.='</tbody></table></div>';
			if($maternity_result){
				$html.='<div class="no-padding col-lg-6 table-responsive"><table class="table table-md table-bordered"><tbody>
                        <tr class="success text-center"><td colspan="6"><strong>Maternity Leave</strong></td></tr>		
                        <tr class=""><td><strong>Applied Leave Date/s</strong></td><td><strong>Total Leave</strong></td></tr>';
				foreach($maternity_result as $m_result){
					if($m_result->leave_status_code == 'ML-1'){
						$status='<strong class="text-success">Full Payment</strong>';
					}/*else if($m_result->leave_status_code == 'ML-2'){
                     $status='<strong class="text-warning">Half Payment</strong>';
                    }*/else{
						$status='<strong class="text-danger">No Payment</strong>';
					}
					$html.='<tr class=""><td>'.$this->Xin_model->set_date_format($m_result->from_date).' to '.$this->Xin_model->set_date_format($m_result->to_date).' ('.$status.')'.'</td><td>'.($m_result->count_of_days).'</td></tr>';
                }
				$html.='</tbody></table></div>';
			}

			if($emergency_result){
				$html.='<div class="no-padding col-lg-6 table-responsive"><table class="table table-md table-bordered"><tbody>
                        <tr class="info text-center"><td colspan="6"><strong>Emergency Leave</strong></td></tr>		
                        <tr class=""><td><strong>Applied Leave Date/s</strong></td><td><strong>Total Leave</strong></td></tr>';

				foreach($emergency_result as $e_result){
					$html.='<tr class=""><td>'.$this->Xin_model->set_date_format($e_result->from_date).' to '.$this->Xin_model->set_date_format($e_result->to_date).'
                            </td><td>'.($e_result->count_of_days).'
                            </td></tr>';
				}
				$html.='</tbody></table></div>';
			}

			if($authorise_result){
				$html.='<div class="no-padding col-lg-6 table-responsive"><table class="table table-md table-bordered"><tbody>
                        <tr class="warning text-center"><td colspan="6"><strong>Authorised Absence Leave</strong></td></tr>		
                        <tr class=""><td><strong>Applied Leave Date/s</strong></td><td><strong>Total Leave</strong></td></tr>';

				foreach($authorise_result as $a_result){
					$html.='<tr class=""><td>'.$this->Xin_model->set_date_format($a_result->from_date).' to '.$this->Xin_model->set_date_format($a_result->to_date).'
                    </td><td>'.($a_result->count_of_days).'
                    </td></tr>';
				}
				$html.='</tbody></table></div>';
			}
			echo $html;

		}else{
			$Return['error'] = 'Bug. Something went wrong, please try again.';
			$this->output($Return);
			exit;
		}
	}

	public function read_remaining_annual_leaves(){
		$employee_id=$this->input->get('employee_id');
		$user = $this->Xin_model->read_user_info($employee_id);
		$date_of_joining=$user[0]->date_of_joining;
		$date_of_leaving=$user[0]->date_of_leaving;
		if($date_of_leaving!=''){
			$calculate_date=$date_of_leaving;
		}else{
			$calculate_date=TODAY_DATE;
		}
		$an_leave_bal=annual_leave_balance($employee_id,$date_of_joining,$calculate_date);
        $balance_leave=floor($an_leave_bal['balance_leave']);

		if($balance_leave==0){
		    $d_ys=' day.';
		}else{
		    $d_ys=' days.';
		}

		$message_status='<div class="alert alert-info alert-bordered  mt-10">
										<button type="button" class="close" data-dismiss="alert"><span></span><span class="sr-only">Close</span></button>
										<span class="text-semibold"></span> Your annual balance leave is '.$balance_leave.$d_ys.'</div>
										';
		$options='';
		if($balance_leave!=0){
			$options='<select class="form-control" name="leave_balance" id="leave_balance" data-plugin="select_hrm">';
			$options.='<option value="">Choose Days...</option>';
			for($jk=1;$jk<=$balance_leave;$jk++){
				$options.='<option value="'.$jk.'">'.$jk.'</option>';
			}
			$options.='</select>';
		}
		echo json_encode(array('leave_count'=>$balance_leave,'message_status'=>$options.$message_status));
	}

	public function add_leave_conversion(){
		if($this->input->post('type')=='leave_conversion') {
			$Return = array('result'=>'', 'error'=>'', 'message'=>'');
			$employee_id=$this->input->post('employee_id');
			$leave_conversion_count=$this->input->post('leave_balance');
			$conversion_comments=$this->input->post('conversion_comments');
			$added_date=date('Y-m-d');//format_date('Y-m-d',$this->input->post('added_date'));
			$user_info = $this->Xin_model->read_user_info($employee_id);

			/*Get HR Administrator Mails BY Location Wise*/
			$hr_mails=get_hr_mail_bylocation($user_info[0]->office_location_id,$employee_id);
			if(!$hr_mails){
				$Return['error'] = 'There is no HR Administrator Assigned for this location.Contact Your HR Team for further assist.';
				$this->output($Return);
				exit;
			}
			/*Get HR Administrator Mails BY Location Wise*/


			if($leave_conversion_count==0){
				$Return['error'] = 'Conversion days should be greater than 0 day.';
				$this->output($Return);
				exit;
			}

			$data=array(
                    'employee_id'=>$employee_id,
                    'leave_conversion_count'=>$leave_conversion_count,
                    'conversion_comments'=>$conversion_comments,
                    'added_date'=>$added_date,
                    'added_by'=>$this->userSession['user_id'],
                    'hr_notification'=>1,
			);

			$result=$this->Timesheet_model->add_leave_conversion($data);
			$link_url=$_SERVER['HTTP_HOST'].base_url().base64_encode('timesheet/leave/?load_detail='.$result);
			$hr_array_merge=$hr_mails;
			if ($result != FALSE) {
				$Return['result'] = 'Leave conversion added and send for approval';
				//get setting info
				$setting = $this->Xin_model->read_setting_info(1);
				//if($setting[0]->enable_email_notification == 'yes') {
				$this->email->set_mailtype("html");
				//get email template
				$template = $this->Xin_model->read_email_template_info_bycode('Leave Conversion');
				//get employee info
				$user_info = $this->Xin_model->read_user_info($employee_id);
				$full_name = change_fletter_caps($user_info[0]->first_name.' '.$user_info[0]->middle_name.' '.$user_info[0]->last_name);
				$designation = $this->Designation_model->read_designation_information($user_info[0]->designation_id);
				$session_user=$this->Xin_model->read_user_info($this->userSession['user_id']);
				$session_user_name=change_fletter_caps($session_user[0]->first_name.' '.$session_user[0]->middle_name.' '.$session_user[0]->last_name);

				if($user_info[0]->gender=='Male'){
					$title='Mr ';
				}else{
					$title='Ms ';
				}

				/*HR Dept For Further Approval*/
				$subject = $template[0]->subject.' from '.$full_name;
				$days=$leave_conversion_count.' day/s';
				$content='I would like to send a request for leave cash conversion of '.$days.' from my annual leave.';
				$link_name="You can view this request by logging in to the portal using the link below.";

				foreach($hr_array_merge as $hr_e){
					$message = '<div style="background: #f7eaea;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin: 0 auto;padding:20px;max-width: 65em;border: 2px solid #D40732;">'.
						str_replace(
							array(
								"{var head_title}",
								"{var hr_name}",
								"{var title}",
								"{var content}",
								"{var link_name}",
								"{var employee_name}",
								"{var designation_name}",
								"{var days}",
								"{var html_structure}",
								"{var link_url}",
							),
							array(
								'Leave Cash Conversion Request',
								change_fletter_caps($hr_e->first_name.' '.$hr_e->middle_name.' '.$hr_e->last_name),
								$title,
								$content,
								$link_name,
								$full_name,
								$designation[0]->designation_name,
								$days,
								'',
								'<a href="'.$link_url.'" target="_blank">View Application</a>',
							),htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';
					if(TESTING_MAIL==TRUE){
						$this->email->from(FROM_MAIL, $session_user_name);//$session_user[0]->email
						$this->email->to(TO_MAIL);
					}else{
						$this->email->from($session_user[0]->email, $session_user_name);
						$this->email->to($hr_e->email);
					}
					$this->email->subject($subject);
					$this->email->message($message);
					//$this->email->send();
				}
				/*HR Dept For Further Approval*/
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function user_leave_conversion_lists()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get("employee_id");
		if(empty($this->userSession)){
			redirect('');
		}

		$draw = intval($this->input->get("draw"));
		$conversion_lists = $this->Timesheet_model->read_all_conversion_lists($id);
		$data = array();
		foreach($conversion_lists->result() as $r) {
			if($r->approved_status==2){
				$status = '<span class="label label-danger">Declined</span>';
			}else if($r->approved_status==1){
				$status = '<span class="label label-success">Approved</span>';
			}else{
				$status = '<span class="label label-warning">Pending</span>';
			}
			$user = $this->Xin_model->read_user_info($r->employee_id);
			$added_by = $this->Xin_model->read_user_info($r->added_by);
			$data[] = array(
				change_fletter_caps($user[0]->first_name.' '.$user[0]->middle_name.' '.$user[0]->last_name),
				$r->leave_conversion_count,
				$r->conversion_comments,
				format_date('d M Y',$r->added_date),
				change_fletter_caps($added_by[0]->first_name.' '.$added_by[0]->middle_name.' '.$added_by[0]->last_name),
				$r->created_at,
				$status,
				'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right"><li><a href="#" data-toggle="modal" data-target=".edit-modal-data-payrol"  data-leave_conversion_id="'. $r->conversion_id . '"><i class="icon-eye4"></i> View Details</a></li></ul></li></ul>',
            );
		}
		//<li><a class="delete_leave_card" href="#" data-record-id="'. $r->conversion_id . '"><i class="icon-trash"></i> Delete</a></li>
		$output = array(
			"draw" => $draw,
			"recordsTotal" => $conversion_lists->num_rows(),
			"recordsFiltered" => $conversion_lists->num_rows(),
			"data" => $data
		);
        $this->output($output);
	}

	public function leave_conversion_lists()
	{
		$data['title'] = $this->Xin_model->site_title();
		if(empty($this->userSession)){
			redirect('');
		}
		$draw = intval($this->input->get("draw"));
		$conversion_lists = $this->Timesheet_model->get_conversion_lists($this->userSession['user_id'],$this->userSession['role_name']);
		$data = array();
		foreach($conversion_lists->result() as $r) {
			if($r->approved_status==2){
				$status = '<span class="label label-danger">Declined</span>';
			}else if($r->approved_status==1){
				$status = '<span class="label label-success">Approved</span>';
			}else{
				$status = '<span class="label label-warning">Pending</span>';
			}
			$user = $this->Xin_model->read_user_info($r->employee_id);
			$added_by = $this->Xin_model->read_user_info($r->added_by);
			$data[] = array(
				change_fletter_caps($user[0]->first_name.' '.$user[0]->middle_name.' '.$user[0]->last_name),
				$r->leave_conversion_count,
				$r->conversion_comments,
				format_date('d M Y',$r->added_date),
				change_fletter_caps($added_by[0]->first_name.' '.$added_by[0]->middle_name.' '.$added_by[0]->last_name),
				$r->created_at,
				$status,
				'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right"><li><a href="#" data-toggle="modal" data-target=".edit-modal-data-payrol"  data-leave_conversion_id="'. $r->conversion_id . '"><i class="icon-eye4"></i> View Details</a></li><li><a class="delete_leave_card" href="#" data-record-id="'. $r->conversion_id . '"><i class="icon-trash"></i> Delete</a></li></ul></li></ul>',
			);
		}
		$output = array(
                "draw" => $draw,
                "recordsTotal" => $conversion_lists->num_rows(),
                "recordsFiltered" => $conversion_lists->num_rows(),
                "data" => $data
		);
        $this->output($output);
	}

	public function leave_details() {

		if(visa_wise_role_ids() == ''){
			$data['title'] = $this->Xin_model->site_title();
			$leave_id = $this->uri->segment(4);
			// leave applications
			$result = $this->Timesheet_model->read_leave_information($leave_id);
			if(!$result){
				redirect('timesheet/leave/');
			}
			$type = $this->Timesheet_model->read_leave_type_information($result[0]->leave_type_id);
			$user = $this->Xin_model->read_user_info($result[0]->employee_id);
			//FOR NOTIFICATION
			$data = array(
	                'title' => $this->Xin_model->site_title(),
	                'type' => $type[0]->type_name,
	                'role_id' => $user[0]->user_role_id,
	                'first_name' => $user[0]->first_name,
	                'middle_name' => $user[0]->middle_name,
	                'last_name' => $user[0]->last_name,
	                'leave_id' => $result[0]->leave_id,
	                'employee_id' => $result[0]->employee_id,
	                'leave_type_id' => $result[0]->leave_type_id,
	                'from_date' => $result[0]->from_date,
	                'to_date' => $result[0]->to_date,
	                'applied_on' => $result[0]->applied_on,
	                'approve_administrator_id' => $result[0]->approve_administrator_id,
	                'updated_at' => $result[0]->updated_at,
	                'reporting_manager_status' => $result[0]->reporting_manager_status,
	                'reporting_manager_remarks' => $result[0]->reporting_manager_remarks,
	                'applied_on' => $result[0]->applied_on,
	                'documentfile' => $result[0]->documentfile,
	                'reason' => $result[0]->reason,
	                'remarks' => $result[0]->remarks,
	                'status' => $result[0]->status,
	                'leave_status_code' => $result[0]->leave_status_code,
	                'created_at' => $result[0]->created_at,
	                'all_employees' => $this->Xin_model->all_employees(),
	                'all_leave_types' => $this->Timesheet_model->all_leave_types(),
			);
			$data['breadcrumbs'] = 'Leave Detail';
			$data['path_url'] = 'leave_details';
			if(!empty($this->userSession)){
				$data['subview'] = $this->load->view("timesheet/leave_details", $data, TRUE);
				$this->load->view('layout_main', $data);
			} else {
				redirect('');
			}
		}else{
			redirect('timesheet/leave/');
		}

	}

	public function task_details() {
		$data['title'] = $this->Xin_model->site_title();
		$task_id = $this->uri->segment(4);
		$result = $this->Timesheet_model->read_task_information($task_id);
		/* get User info*/
		$u_created = $this->Xin_model->read_user_info($result[0]->created_by);
		$f_name = $u_created[0]->first_name.' '.$u_created[0]->middle_name.' '.$u_created[0]->last_name;
		$data = array(
                'title' => $this->Xin_model->site_title(),
                'task_id' => $result[0]->task_id,
                'created_by' => $f_name,
                'task_name' => $result[0]->task_name,
                'assigned_to' => $result[0]->assigned_to,
                'start_date' => $result[0]->start_date,
                'end_date' => $result[0]->end_date,
                'task_hour' => $result[0]->task_hour,
                'task_status' => $result[0]->task_status,
                'task_note' => $result[0]->task_note,
                'progress' => $result[0]->task_progress,
                'description' => $result[0]->description,
                'created_at' => $result[0]->created_at,
                'all_employees' => $this->Xin_model->all_employees()
		);
		$data['breadcrumbs'] = 'Task Detail';
		$data['path_url'] = 'task_details';
		$data['all_employees'] = $this->Xin_model->all_employees();
		$data['all_leave_types'] = $this->Timesheet_model->all_leave_types();
		if(!empty($this->userSession)){
			$data['subview'] = $this->load->view("timesheet/tasks/task_details", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
		} else {
			redirect('');
		}
	}

	public function tasks() {
		$data['title'] = $this->Xin_model->site_title();
		$data['all_employees'] = $this->Xin_model->all_employees();
		$data['breadcrumbs'] = 'Worksheet (Tasks)';
		$data['path_url'] = 'tasks';
		if(in_array('33',role_resource_ids())) {
			if(!empty($this->userSession)){
				$data['subview'] = $this->load->view("timesheet/tasks/task_list", $data, TRUE);
				$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
	}

	public function assign_task() {
		if($this->input->post('type')=='task_user') {
			$Return = array('result'=>'', 'error'=>'');
			if(null!=$this->input->post('assigned_to')) {
				$assigned_ids = implode(',',$this->input->post('assigned_to'));
				$employee_ids = $assigned_ids;
			} else {
				$employee_ids = '';
			}

			$data = array(
				'assigned_to' => $employee_ids
			);
			$id = $this->input->post('task_id');
			$result = $this->Timesheet_model->assign_task_user($data,$id);
			userlogs('Tasks','Task Assign To Employees Updated',count($this->input->post('assigned_to')));// count of employees
			if ($result == TRUE) {
				$Return['result'] = 'Task employees has been updated.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function task_users() {
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->uri->segment(3);
		$data = array(
			'task_id' => $id,
			'all_employees' => $this->Xin_model->all_employees(),
		);
		if(!empty($this->userSession)){
			$this->load->view("timesheet/tasks/get_task_users", $data);
		} else {
			redirect('');
		}
	}

	public function update_task_status() {
		if($this->input->post('type')=='update_status') {
			$Return = array('result'=>'', 'error'=>'');
			$data = array(
				'task_progress' => $this->input->post('progres_val'),
				'task_status' => $this->input->post('status'),
			);
			$id = $this->input->post('task_id');
			$result = $this->Timesheet_model->update_task_record($data,$id);
			userlogs('Tasks','Task Status Updated','');// count of employees
			if ($result == TRUE) {
				$Return['result'] = 'Task status updated.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function task_list() {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("timesheet/leave", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$task = $this->Timesheet_model->get_tasks();
		$data = array();
		foreach($task->result() as $r) {
			if($r->assigned_to == '' || $r->assigned_to == 'None') {
				$ol = 'None';
			} else {
				$ol = '';
				foreach(explode(',',$r->assigned_to) as $uid) {
					$assigned_to = $this->Xin_model->read_user_info($uid);
					$assigned_name = $assigned_to[0]->first_name.' '.$assigned_to[0]->middle_name.' '.$assigned_to[0]->last_name;
					if($assigned_to[0]->profile_picture!='' && $assigned_to[0]->profile_picture!='no file') {
						$ol .= '<a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="'.$assigned_name.'"><span class="avatar box-32"><img style="width:100%;" src="'.base_url().'uploads/profile/'.$assigned_to[0]->profile_picture.'" class="img-circle" alt=""></span></a>';
					} else {
						if($assigned_to[0]->gender=='Male') {
							$de_file = base_url().'uploads/profile/default_male.jpg';
						} else {
							$de_file = base_url().'uploads/profile/default_female.jpg';
						}
						$ol .= '<a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="'.$assigned_name.'"><span class="avatar box-32"><img src="'.$de_file.'"  style="width:100%" class="img-circle" alt=""></span></a>';
					}
				}
				$ol .= '';
			}
			/* get User info*/
			$u_created = $this->Xin_model->read_user_info($r->created_by);
			$f_name = $u_created[0]->first_name.' '.$u_created[0]->last_name;
			/// set task progress
			if($r->task_progress=='' || $r->task_progress==0): $progress = 0; else: $progress = $r->task_progress; endif;

			// task progress
			if($r->task_progress <= 20) {
				$progress_class = 'progress-bar-danger';
			} else if($r->task_progress > 20 && $r->task_progress <= 50){
				$progress_class = 'progress-bar-warning';
			} else if($r->task_progress > 50 && $r->task_progress <= 75){
				$progress_class = 'progress-bar-info';
			} else {
				$progress_class = 'progress-bar-success';
			}

			$progress_bar='<div class="progress content-group-sm">
									<div class="progress-bar '.$progress_class.' progress-bar-striped" style="width: '.$r->task_progress.'%">
										<span>'.$r->task_progress.'%</span>
									</div>
								</div>';

			if($r->task_status == 0) {
				$status = 'Not Started';
			} else if($r->task_status ==1){
				$status = 'In Progress';
			} else if($r->task_status ==2){
				$status = 'Completed';
			} else {
				$status = 'Deferred';
			}
			// task end date
			$tdate = $this->Xin_model->set_date_format($r->end_date);
			$data[] = array(
				$r->task_name,
				$tdate,
				$status,
				$ol,
				$f_name,
				$progress_bar,
				'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right"><li><a href="#" class="edit-data" data-toggle="modal" data-target=".edit-modal-data"  data-task_id="'. $r->task_id . '"><i class="icon-pencil7"></i> Edit</a></li><li><a href="'.site_url().'timesheet/task_details/id/'.$r->task_id.'/"><i class="icon-eye4"></i> View Details</a></li><li><a class="delete" href="#" data-record-id="'. $r->task_id . '"><i class="icon-trash"></i> Delete</a></li></ul></li></ul>',
			);
		}
		$output = array(
			"draw" => $draw,
			"recordsTotal" => $task->num_rows(),
			"recordsFiltered" => $task->num_rows(),
			"data" => $data
		);
		$this->output($output);
	}

	public function comments_list()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->uri->segment(3);
		$ses_user = $this->Xin_model->read_user_info($this->userSession['user_id']);
		if(!empty($this->userSession)){
			$this->load->view("timesheet/tasks/task_details", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$comments = $this->Timesheet_model->get_comments($id);
		$data = array();
		foreach($comments->result() as $r) {
			// get user > employee_
			$employee = $this->Xin_model->read_user_info($r->user_id);
			// employee full name
			$employee_name = $employee[0]->first_name.' '.$employee[0]->last_name;
			// get designation
			$_designation = $this->Designation_model->read_designation_information($employee[0]->designation_id);
			// created at
			$created_at = date('h:i A', strtotime($r->created_at));
			$_date = explode(' ',$r->created_at);
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
				$link = '<a class="c-user text-black" href="'.site_url().'employees/detail/'.$r->user_id.'"><span class="underline">'.$employee_name.' ('.$_designation[0]->designation_name.')</span></a>';
			} else {
				$link = '<span class="underline">'.$employee_name.' ('.$_designation[0]->designation_name.')</span>';
			}

			if($ses_user[0]->user_role_id==1 || $ses_user[0]->user_id==$r->user_id){
				$dlink = '<div class="media-right">
							<div class="c-rating">
							<span data-toggle="tooltip" data-placement="top" title="Delete">
								<a class="btn btn-danger btn-sm delete" href="#" data-toggle="modal" data-target=".delete-modal" data-record-id="'.$r->comment_id.'">
			  <i class="icon-trash position-left"></i>Delete</a></span>
							</div>
						</div>';
			} else {
				$dlink = '';
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
							<div class="c-text">'.$r->task_comments.'</div>
						</div>
						'.$dlink.'
					</div>
				</div>';

			$data[] = array(
				$function
			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $comments->num_rows(),
			"recordsFiltered" => $comments->num_rows(),
			"data" => $data
		);
		$this->output($output);
	}

	public function set_comment() {
		if($this->input->post('add_type')=='set_comment') {
			$Return = array('result'=>'', 'error'=>'');
			if($this->input->post('xin_comment')==='') {
				$Return['error'] = "The comment field is required.";
			}
			$xin_comment = $this->input->post('xin_comment');
			$qt_xin_comment = htmlspecialchars(addslashes($xin_comment), ENT_QUOTES);

			if($Return['error']!=''){
				$this->output($Return);
			}

			$data = array(
				'task_comments' => $qt_xin_comment,
				'task_id' => $this->input->post('comment_task_id'),
				'user_id' => $this->input->post('user_id'),
				'created_at' => date('d-m-Y h:i:s')
			);
			$result = $this->Timesheet_model->add_comment($data);
			if ($result == TRUE) {
				$Return['result'] = 'Task comment added.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function comment_delete() {
		if($this->input->post('form') == 'delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			$this->Timesheet_model->delete_comment_record($id);
			if(isset($id)) {
				$Return['result'] = 'Task comment deleted.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
		}
	}

	public function bus_list_delete() {
		if($this->input->post('form') == 'delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			/*User Logs*/
			$affected_row= table_deleted_row('xin_bus_late_timings','bus_late_id',$id);
			userlogs('Timesheet-BusLateness-Delete','Bus Lateness Deleted',$id,$affected_row);
			/*User Logs*/
			$this->Timesheet_model->delete_bus_late_record($id);
			if(isset($id)) {
				$Return['result'] = 'Bus lateness deleted.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
		}
	}

	public function add_attachment() {
		if($this->input->post('add_type')=='dfile_attachment') {
			$Return = array('result'=>'', 'error'=>'');
			if($this->input->post('file_name')==='') {
				$Return['error'] = "The file name field is required.";
			} else if($_FILES['attachment_file']['size'] == 0) {
				$Return['error'] = 'Select file.';
			} else if($this->input->post('file_description')==='') {
				$Return['error'] = 'The description field is required.';
			}
			$description = $this->input->post('file_description');
			$file_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
			if($Return['error']!=''){
				$this->output($Return);
			}
            $fname = '';
			// is file upload
			if(is_uploaded_file($_FILES['attachment_file']['tmp_name'])) {
				//checking image type
				$allowed =  array('png','jpg','jpeg','pdf','doc','docx','xls','xlsx','txt');
				$filename = $_FILES['attachment_file']['name'];
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				if(in_array($ext,$allowed)){
					$tmp_name = $_FILES["attachment_file"]["tmp_name"];
					$attachment_file = "uploads/task/";
					$newfilename = 'task_'.round(microtime(true)).'.'.$ext;
					move_uploaded_file($tmp_name, $attachment_file.$newfilename);
					$fname = $newfilename;
				} else {
					$Return['error'] = "The attachment must be a file of type: png, jpg, jpeg, pdf, doc, docx, xls, xlsx, txt";
				}
			}

			$data = array(
				'task_id' => $this->input->post('c_task_id'),
				'upload_by' => $this->input->post('user_id'),
				'file_title' => $this->input->post('file_name'),
				'file_description' => $file_description,
				'attachment_file' => $fname,
				'created_at' => date('d-m-Y h:i:s')
			);
			$result = $this->Timesheet_model->add_new_attachment($data);
			if ($result == TRUE) {
				$Return['result'] = 'Task attachment added.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function attachment_list()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->uri->segment(3);
		if(!empty($this->userSession)){
			$this->load->view("timesheet/tasks/task_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$attachments = $this->Timesheet_model->get_attachments($id);
		if($attachments->num_rows() > 0) {
			$data = array();
			foreach($attachments->result() as $r) {
				$data[] = array(
					$r->file_title,
					$r->file_description,
					$r->created_at,
					'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right"><li><a  href="'.site_url().'download?type=task&filename='.$r->attachment_file.'"><i class="icon-download7"></i> Download</a></li><li><a class="delete-file" href="#" data-record-id="'. $r->task_attachment_id . '"><i class="icon-trash"></i> Delete</a></li></ul></li></ul>',
                );
			}
			$output = array(
                    "draw" => $draw,
                    "recordsTotal" => $attachments->num_rows(),
                    "recordsFiltered" => $attachments->num_rows(),
                    "data" => $data
			);
		}
		else {
			$data[] = array('','','','');
			$output = array(
				"draw" => $draw,
				"recordsTotal" => 0,
				"recordsFiltered" => 0,
				"data" => $data
			);
		}
		$this->output($output);
	}

	public function attachment_delete() {
		if($this->input->post('form') == 'delete_record_f') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			$this->Timesheet_model->delete_attachment_record($id);
			if(isset($id)) {
				$Return['result'] = 'Task attachment deleted.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
		}
	}

	public function attendance_list()
	{
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("timesheet/attendance_list", $data);
		}
		else {
			redirect('');
		}
		$draw = intval($this->input->get("draw"));
		$department_value=$this->input->get('department_value');
		$location_value=$this->input->get('location_value');
		$visa_value=$this->input->get('visa_value');
		$employee = $this->Xin_model->read_user_info_attendance('all',$department_value,$location_value,$visa_value);
		$attendance_date = date('Y-m-d');
		$data = array();
		foreach($employee as $r) {
			//if($r->office_shift_id!=0){
			$full_name = change_fletter_caps($r->first_name.' '.$r->middle_name.' '.$r->last_name);
			$designation = $this->Designation_model->read_designation_information($r->designation_id);
			// department
			$department = $this->Department_model->read_department_information($r->department_id);
			$department_designation = @$designation[0]->designation_name.'('.@$department[0]->department_name.')';
			if($r->visa_type!=''){
				$visa_type=$r->visa_type;
			}else{
				$visa_type='N/A';
			}
			$condition_l = "location_id =" . "'" . $r->office_location_id . "'";
			$this->db->select('country,location_name');
			$this->db->from('xin_office_location');
			$this->db->where($condition_l);
			$this->db->limit(1);
			$query_l = $this->db->get();
			$result_l = $query_l->result();
			$location_name=$result_l[0]->location_name;
			$brief_details="<i style='cursor:pointer;' data-html='true' class='ml-10 icon-bubble-dots4 position-right text-teal-400 pull-right' data-popup='popover-custom' data-placement='right' title='Breif Details Of Employee' data-trigger='hover' data-content='<table><tr>
				<td>Department</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>".$department_designation."</td></tr>
				<tr>
				<td>DOJ</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>".$this->Xin_model->set_date_format($r->date_of_joining)."</td></tr>
				<tr>
				<td>Working Hours</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>".$r->working_hours." H</td></tr>
				<tr>
				<td>Visa Type</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>".$visa_type."</td></tr>
				<tr>
				<td>Office Location</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>".$location_name."</td></tr>
				</table>'></i>";


			$attendance_details = $this->Timesheet_model->attendance_details($r->user_id,$attendance_date);

			if(@$attendance_details[0]->clock_in==''){ $clock_in2 = '-';	}	else {
				$clock_in = new DateTime($attendance_details[0]->clock_in);
				$clock_in2 = $clock_in->format('h:i a');
			}
			if(@$attendance_details[0]->clock_out==''){ $clock_out2 = '-';	}	else {
				$clock_out = new DateTime($attendance_details[0]->clock_out);
				$clock_out2 = $clock_out->format('h:i a');
			}
			if(@$attendance_details[0]->time_late==''){ $time_late1 = '00:00';	}	else { $time_late1=$attendance_details[0]->time_late;}
			if(@$attendance_details[0]->early_leaving==''){ $early_leaving1 = '0h 0m';	}	else { $early_leaving1=$attendance_details[0]->early_leaving;}
			if(@$attendance_details[0]->total_work==''){ $total_work1 = '00:00';	}	else { $total_work1=$attendance_details[0]->total_work;}
			if(@$attendance_details[0]->attendance_status==''){ $status = '';	}	else{ $status=$attendance_details[0]->attendance_status;}

			@$real_status=$attendance_details[0]->attendance_status;
			@$real_status=check_leave_status($attendance_date,$r->user_id,$attendance_details[0]->attendance_status,$attendance_details[0]->clock_in,$attendance_details[0]->clock_out,$attendance_details[0]->shift_start_time,$attendance_details[0]->shift_end_time,$attendance_details[0]->week_off,$r->department_id,$r->country_id);

			if($real_status){
				$real_status=$this->Timesheet_model->get_status_name($real_status);
				if($real_status=='Present')
					$real_status='<span class="label label-success">'.$real_status.'</span>';
				else if($real_status=='Absent')
					$real_status='<span class="label label-danger">'.$real_status.'</span>';
				else if($real_status=='New Joinee')
					$real_status='<span class="label label-info">'.$real_status.'</span>';
				else if($real_status=='Late')
					$real_status='<span class="label label-warning">'.$real_status.'</span>';
				else
					$real_status='<span class="label bg-purple-400">'.$real_status.'</span>';

			}

			$bus_lateness= get_bus_users_late($r->user_id,$attendance_date,$r->office_location_id);
			if($bus_lateness){
				if($bus_lateness['late_rest_value']!=0){
					$bus_late='<br><span class="label bg-teal-400 mt-5">Bus LT-'.$bus_lateness['late_hours'].'</span>';
				}else{$bus_late='';}
			}else{
				$bus_late='';
			}

			$result_manual= check_OB_Hours($r->user_id,$attendance_date);
			if($result_manual){
				$ob_hours='<br><span class="label bg-teal-400 mt-5" title="OB ( '.$result_manual[0]->attendance_status.' ) - '.$result_manual[0]->reason.'">OB ( '.substr($result_manual[0]->attendance_status,0,1).' ) - '.$result_manual[0]->total_hours.'</span>';
				$total_work1=add_ob_hours(array($total_work1,$result_manual[0]->total_hours),$result_manual[0]->attendance_status);
			}else{
				$ob_hours='';
			}

			$shift_in_time = new DateTime(@$attendance_details[0]->shift_start_time);
			$shift_out_time = new DateTime(@$attendance_details[0]->shift_end_time);

			$week_offs=explode(',',@$attendance_details[0]->week_off);
			$week_off='';
			foreach($week_offs as $w_o){
				$week_off.='<span class="label bg-teal-400 ml-5">'.$w_o.'</span>';
			}

			if(@$attendance_details[0]->shift_start_time == '') {
				$shift_time = '-';
			} else {
				$shift_time = $shift_in_time->format('h:i a') . ' to '.$shift_out_time->format('h:i a');
			}


			if(($department[0]->department_name=='TD') && (strtoupper($location_name)=='DIP 2') && ($attendance_date==TODAY_DATE)){
				$clock_out2='';
			}

			$data[] = array(
				@$attendance_date,
				@$real_status.$ob_hours.$bus_late,
				change_fletter_caps($full_name).' '.$brief_details,
				$clock_in2,
				$clock_out2,
				@$time_late1,
				@$early_leaving1,
				@$total_work1,
				$shift_time.' '.$week_off,
			);
		}
		// }
		$output = array(
			"draw" => $draw,
			"recordsTotal" => count($data),
			"recordsFiltered" => count($data),
			"data" => $data
		);
		$this->output($output);
	}

	public function date_wise_list_with_shift()
	{
	    $data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("timesheet/date_wise", $data);
		} else {
			redirect('');
		}
		$draw = intval($this->input->get("draw"));
		$employee_id = $this->input->get("user_id");
		$department_value = $this->input->get("department_value");
		$user_type = $this->input->get("user_type");

		if(!$department_value)
			$department_value=0;

		$location_value = $this->input->get("location_value");
		$employee = $this->Xin_model->read_user_info_attendance($employee_id,$department_value,$location_value,'',$user_type);

		if($employee[0]->is_break_included == 1){
			$working_hours_per_day = date("H:i",strtotime($employee[0]->working_hours) - 60*60);
		}else{
			$working_hours_per_day = $employee[0]->working_hours;
		}

		$start_range = date('Y-m-d',strtotime($this->input->get("start_date")));
		$end_range 	= date('Y-m-d',strtotime($this->input->get("end_date")));
		$diff = abs(strtotime($end_range) - strtotime($start_range));
		$range_days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

		if($range_days > 0){
			if($end_range == date('Y-m-d')){
				$required_working_hours  = $working_hours_per_day * ($range_days + 0);
			}else{
				$required_working_hours  = $working_hours_per_day * ($range_days + 1);
			}
		}else{
			$required_working_hours = $working_hours_per_day;
		}

		$start_date = new DateTime( $this->input->get("start_date"));
		$end_date = new DateTime( $this->input->get("end_date") );
		$end_date = $end_date->modify( '+1 day' );
		$interval_re = new DateInterval('P1D');
		$date_range = new DatePeriod($start_date, $interval_re ,$end_date);
		$attendance_arr = array();
		$lateTotal = "00:00";
		$earlyLeaveTotal = "00:00";
		$data = array();

		$total_worked_hours = '0h 0m';

		$total_working_hours_range = '';

		foreach($date_range as $date) {
			$attendance_date =  $date->format("Y-m-d");
			foreach($employee as $r) {
				$attendance_details = $this->Timesheet_model->attendance_details($r->user_id,$attendance_date);				
				$department = $this->Department_model->read_department_information($r->department_id);
				$condition_l = "location_id =" . "'" . $r->office_location_id . "'";
				$this->db->select('country,location_name');
				$this->db->from('xin_office_location');
				$this->db->where($condition_l);
				$this->db->limit(1);
				$query_l = $this->db->get();
				$result_l = $query_l->result();
				$location_name=$result_l[0]->location_name;

				if(@$attendance_details[0]->clock_in=='')
					$clock_in2 = '-';
				else {
					$clock_in = new DateTime($attendance_details[0]->clock_in);
					$clock_in2 = $clock_in->format('h:i a');
				}
				if(@$attendance_details[0]->clock_out=='')
					$clock_out2 = '-';
				else {
					$clock_out = new DateTime($attendance_details[0]->clock_out);
					$clock_out2 = $clock_out->format('h:i a');
				}

				if(@$attendance_details[0]->overtime==''  || @$attendance_details[0]->overtime=='00:00'){ $overtime1 = '0h 0m';	}	else {
					$overtime1=$attendance_details[0]->overtime;
				}

				if(@$attendance_details[0]->overtime_night=='' || @$attendance_details[0]->overtime_night=='00:00'){ $overtime_night = '0h 0m';	}	else {
					$overtime_night=$attendance_details[0]->overtime_night;
				}

				if(@$attendance_details[0]->total_work==''){
					$total_work1 = '0h 0m';
				}
				else{
					$total_work1=$attendance_details[0]->total_work;
				}
				if(@$attendance_details[0]->attendance_status=='')
					$status = '';
				else
					$status=$attendance_details[0]->attendance_status;

				$tdate = $this->Xin_model->set_date_format($attendance_date);

				if($employee_id!='all'){
					$change_title=$tdate.' <span class="text-teal-400">('.date('l', strtotime($attendance_date)).')</span> ';
				}else{
					$change_title=change_fletter_caps($r->first_name.' '.$r->middle_name.' '.$r->last_name);
				}

				@$real_status=$attendance_details[0]->attendance_status;
				$real_status=check_leave_status($attendance_date,$r->user_id,$attendance_details[0]->attendance_status,$attendance_details[0]->clock_in,$attendance_details[0]->clock_out,$attendance_details[0]->shift_start_time,$attendance_details[0]->shift_end_time,$attendance_details[0]->week_off,$r->department_id,$r->country_id);
				$real_status1=$real_status;

				if($real_status){
					$real_status=$this->Timesheet_model->get_status_name($real_status);
					if($real_status=='Present')
						$real_status='<span class="label label-success">'.$real_status.'</span>';
					else if($real_status=='Absent')
						if($attendance_date==TODAY_DATE)
							$real_status='<span class="label label-info">Waiting to fetch..</span>';
						else
							$real_status='<span class="label label-danger">'.$real_status.'</span>';
					else if($real_status=='New Joinee')
						$real_status='<span class="label label-info">'.$real_status.'</span>';
					else if($real_status=='Late')
						$real_status='<span class="label label-warning">'.$real_status.'</span>';
					else
						$real_status='<span class="label bg-purple-400">'.$real_status.'</span>';

				}

				if($real_status=='<span class="label bg-purple-400"></span>'){
					$real_status='<span class="label bg-purple-400">'.$real_status1.'</span>';
				}

				$bus_lateness= get_bus_users_late($r->user_id,$attendance_date,$r->office_location_id);
				if($bus_lateness){
					if($bus_lateness['late_rest_value']!=0){
						$bus_late='<br><span class="label bg-teal-400 mt-5">Bus LT-'.$bus_lateness['late_hours'].'</span>';
					}else{$bus_late='';}
				}else{
					$bus_late='';
				}

				$result_manual= check_OB_Hours($r->user_id,$attendance_date);
				if($result_manual){
					$ob_hours = '';
					foreach ($result_manual as $ob){
						$ob_hours .='<br><span class="label bg-teal-400 mt-5" title="OB ( '.$ob->attendance_status.' ) - '.$ob->reason.'">OB ( '.substr($ob->attendance_status,0,1).' ) - '.$ob->total_hours.'</span>';
						$total_work1=add_ob_hours(array($total_work1,$ob->total_hours),$ob->attendance_status);

						$total_working_hours_range += $ob->total_hours;
					}
					//$ob_hours='<br><span class="label bg-teal-400 mt-5" title="OB ( '.$result_manual[0]->attendance_status.' ) - '.$result_manual[0]->reason.'">OB ( '.substr($result_manual[0]->attendance_status,0,1).' ) - '.$result_manual[0]->total_hours.'</span>';
				}else{
					$ob_hours='';
				}

				if($real_status1 == 'P' || $real_status1 == 'LT'){
					$total_working_hours_range += $attendance_details[0]->total_rest;
				}elseif($real_status1 == 'WO' || $real_status1 == 'PH' || $real_status1 == 'AL' || $real_status1 == 'SL-1' || $real_status1 == 'ML-1' || $real_status1 == 'BL'){
					$total_working_hours_range += $working_hours_per_day;
				}elseif($real_status1 == 'SL-2' || $real_status1 == 'ML-2'){
					$total_working_hours_range += ($working_hours_per_day/2);
				}else{
					$total_working_hours_range += 0;
				}

				$shift_in_time = new DateTime($attendance_details[0]->shift_start_time);
				$shift_out_time = new DateTime($attendance_details[0]->shift_end_time);

				$week_offs=explode(',',$attendance_details[0]->week_off);
				$week_off='';
				foreach($week_offs as $w_o){
					$week_off.='<span class="label bg-teal-400 ml-5">'.$w_o.'</span>';
				}

				if($attendance_details[0]->shift_start_time == '') {
					$shift_time = '-';
				} else {
					$shift_time = $shift_in_time->format('h:i a') . ' to '.$shift_out_time->format('h:i a');
				}

				if(($department[0]->department_name=='TD') && (strtoupper($location_name)=='DIP 2') && ($attendance_date==TODAY_DATE)){
					$clock_out2='';
				}


				if(($attendance_details[0]->clock_in!='') && ($attendance_details[0]->clock_out=='') && (strtotime($attendance_date)!=strtotime(TODAY_DATE))){
					$login_entry=date("H:i", strtotime($attendance_details[0]->clock_in));
					$shift_end_b_1_hour=date("H:i", strtotime('0 hours', strtotime($attendance_details[0]->shift_end_time)));
					if(strtotime($login_entry) >= strtotime($shift_end_b_1_hour)){
						$clock_out2=$clock_in2;
						$clock_in2='-';
					}
				}
				$earlyLeave = '00:00';
				$lateComing = '00:00';
				if(@$attendance_details[0]->time_late=='') {
					$time_late1 = '00:00';
				}
				else {
					$tim_l=str_replace('m','',str_replace('h ',':',$attendance_details[0]->time_late));
					$lateComing = $tim_l;
					if($lateComing != ''){
						$lateComing = explode(":",$lateComing);
						$lateComing[0] = (strlen($lateComing[0])==1) ? ('0'.$lateComing[0]) : $lateComing[0];
						$lateComing[1] = (strlen($lateComing[1])==1) ? ('0'.$lateComing[1]) : $lateComing[1];
						$lateComing = implode(":",$lateComing);
					}

					if($tim_l!='00:00'){
						$tim_l=' ('.decimalHours($tim_l).')';
					}
					else{
						$tim_l='';
					}
					$time_late1=$attendance_details[0]->time_late.$tim_l;
				}


				if(@$attendance_details[0]->early_leaving=='')
					$early_leaving1 = '00:00';
				else{
					$early_leaving1=$attendance_details[0]->early_leaving;
					$earlyLeave=str_replace('m','',str_replace('h ',':',$attendance_details[0]->early_leaving));
					if($earlyLeave != ''){
						$earlyLeave = explode(":",$earlyLeave);
						$earlyLeave[0] = (strlen($earlyLeave[0])==1) ? ('0'.$earlyLeave[0]) : $earlyLeave[0];
						$earlyLeave[1] = (strlen($earlyLeave[1])==1) ? ('0'.$earlyLeave[1]) : $earlyLeave[1];
						$earlyLeave = implode(":",$earlyLeave);
					}

				}
				if($total_work1 == '8h 0m' || $total_work1 == '9h 0m'){
					$time_late1 = '00:00';
					$early_leaving1 = '00:00';
					//$real_status='<span class="label label-success">Present</span>';
				}
				else if($total_work1 == '7h 0m'){
					if($clock_in2 == '-' && $clock_out2!='-')
						$time_late1 = '1h 0m';
					else if($clock_in2 != '-' && $clock_out2=='-')
						$early_leaving1 = '1h 0m';
					//$real_status='<span class="label label-warning">Late</span>';
				}

				$lateComing = 	DateTime::createFromFormat("H:i",($time_late1=='00:00')? $time_late1 : $lateComing)->format("H:i");
				$lateTotal  =   addDateTime($lateTotal,$lateComing);
				$earlyLeave = 	DateTime::createFromFormat("H:i",($early_leaving1=='00:00')? $early_leaving1 : $earlyLeave)->format("H:i");
				$earlyLeaveTotal = addDateTime($earlyLeaveTotal,$earlyLeave);

				$flexibleEmployees = getFlexibleEmployees();
				$isFlexible = in_array($r->user_id,$flexibleEmployees);

				// sajith
				if($isFlexible){
					$time_late1 = '00:00';
					$early_leaving1 = '00:00';

					$temp_total_work1 = explode('h ', $total_work1);
					if(intval($working_hours_per_day) <= $temp_total_work1[0] && $real_status1 == 'LT'){
						$real_status = '<span class="label label-success">Present</span>';
					}
				}

				$total_rest_hidden = '<input type = "hidden" name="total_rest_hidden" value="'.$attendance_details[0]->total_rest.'">';

				$item = array(
					@$attendance_date,
					$real_status.$ob_hours.$bus_late,
					$change_title,
					$clock_in2,
					$clock_out2,
					@$time_late1,
					@$early_leaving1,
					@$overtime1.' / '.$overtime_night,
					@$total_work1.$total_rest_hidden,
					$shift_time.' '.$week_off,
					$lateComing
					//@$real_status,
				);
				$data[] = $item;
			}

		}

		$total_working_hours_range_hidden = '<input type = "hidden" name="total_working_hours_range_hidden" value="'.$total_working_hours_range.'">';
		$required_working_hours_hidden = '<input type = "hidden" name="required_working_hours_hidden" value="'.$required_working_hours.'">';

		$delta_working = round($total_working_hours_range - $required_working_hours,2);
		$delta_working_hidden = '<input type = "hidden" name="delta_working_hidden" value="'.($total_working_hours_range - $required_working_hours).'">';

		$delta_working = sprintf('%02d:%02d', (int) $delta_working, fmod($delta_working, 1) * 60);
		$total_working_hours_range = sprintf('%02d:%02d', (int) $total_working_hours_range, fmod($total_working_hours_range, 1) * 60);
		$required_working_hours = sprintf('%02d:%02d', (int) $required_working_hours, fmod($required_working_hours, 1) * 60);

		if($delta_working == '00:00'){
			$delta_working_hours = 'No delta hours';
		}elseif(count(explode('-', $delta_working)) > 1){
			$delta_working_hours = '-'.decimalHoursFormat(str_replace('-', '', $delta_working)).' less delta hours';
		}else{
			$delta_working_hours = '+'.decimalHoursFormat($delta_working).' excess delta hours';
		}

		$sumAfterGrace = (addDateTime($earlyLeaveTotal,$lateTotal));
		$sumAfterGrace = isLateCrossedGraceTime($sumAfterGrace);
		$output = array(
			"draw" => $draw,
			"recordsTotal" => count($date_range),
			"recordsFiltered" => count($date_range),
			"data" => $data,
			"lateTotal" => decimalHoursFormat($lateTotal),
			"earlyLeaveTotal" => decimalHoursFormat($earlyLeaveTotal),
			"sum_late_early" => decimalHoursFormat((addDateTime($earlyLeaveTotal,$lateTotal))),
			"sum_after_grace" => decimalHoursFormat($sumAfterGrace),
			"delta_working_hours" => $delta_working_hours.$delta_working_hidden,
			"required_working_hours" => decimalHoursFormat($required_working_hours).$required_working_hours_hidden,
			"total_working_hours_range" => decimalHoursFormat($total_working_hours_range).$total_working_hours_range_hidden,
		);
		$this->output($output);
	}

	public function date_wise_list()
	{
	    $data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("timesheet/date_wise", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$employee_id = $this->input->get("user_id");
		$department_value = $this->input->get("department_value");
		$location_value = $this->input->get("location_value");
		$employee = $this->Xin_model->read_user_info_attendance($employee_id,$department_value,$location_value);
		$start_date = new DateTime( $this->input->get("start_date"));
		$end_date = new DateTime( $this->input->get("end_date") );
		$end_date = $end_date->modify( '+1 day' );
		$interval_re = new DateInterval('P1D');
		$date_range = new DatePeriod($start_date, $interval_re ,$end_date);
		$attendance_arr = array();
		$data = array();
		foreach($date_range as $date) {
			$attendance_date =  $date->format("Y-m-d");
			foreach($employee as $r) {
				$attendance_details = $this->Timesheet_model->attendance_details($r->user_id,$attendance_date);

				if(@$attendance_details[0]->clock_in==''){ $clock_in2 = '-';	}	else {
					$clock_in = new DateTime($attendance_details[0]->clock_in);
					$clock_in2 = $clock_in->format('h:i a');
				}
				if(@$attendance_details[0]->clock_out==''){ $clock_out2 = '-';	}	else {
					$clock_out = new DateTime($attendance_details[0]->clock_out);
					$clock_out2 = $clock_out->format('h:i a');
				}
				if(@$attendance_details[0]->time_late==''){ $time_late1 = '00:00';	}	else {
					$tim_l=str_replace('m','',str_replace('h ',':',$attendance_details[0]->time_late));
					if($tim_l!='00:00'){
						$tim_l=' ('.decimalHours($tim_l).')';
					}else{
						$tim_l='';
					}
					$time_late1=$attendance_details[0]->time_late;//.$tim_l;
				}
				if(@$attendance_details[0]->early_leaving==''){ $early_leaving1 = '0h 0m';	}	else { $early_leaving1=$attendance_details[0]->early_leaving;}
				if(@$attendance_details[0]->overtime==''  || @$attendance_details[0]->overtime=='00:00'){ $overtime1 = '0h 0m';	}	else {
					$overtime1=$attendance_details[0]->overtime;
				}

				if(@$attendance_details[0]->overtime_night=='' || @$attendance_details[0]->overtime_night=='00:00'){ $overtime_night = '0h 0m';	}	else {
					$overtime_night=$attendance_details[0]->overtime_night;
				}

				if(@$attendance_details[0]->total_work==''){ $total_work1 = '00:00';	}	else { $total_work1=$attendance_details[0]->total_work;}
				if(@$attendance_details[0]->attendance_status==''){ $status = '';	}	else{ $status=$attendance_details[0]->attendance_status;}
				$tdate = $this->Xin_model->set_date_format($attendance_date);
				if($employee_id!='all'){
					$change_title=$tdate.'<br><span class="text-teal-400">('.date('l', strtotime($attendance_date)).')</span> ';
				}else{
					$change_title=change_fletter_caps($r->first_name.' '.$r->middle_name.' '.$r->last_name);
				}

				@$real_status=$attendance_details[0]->attendance_status;
				$real_status=check_leave_status($attendance_date,$r->user_id,$attendance_details[0]->attendance_status,$attendance_details[0]->clock_in,$attendance_details[0]->clock_out,$attendance_details[0]->shift_start_time,$attendance_details[0]->shift_end_time,$attendance_details[0]->week_off,$r->department_id,$r->country_id);
                $real_status1=$real_status;

				if($real_status){
					$real_status=$this->Timesheet_model->get_status_name($real_status);
					if($real_status=='Present')
						$real_status='<span class="label label-success">'.$real_status.'</span>';
					else if($real_status=='Absent')
						if($attendance_date==TODAY_DATE)
							$real_status='<span class="label label-info">Waiting to fetch..</span>';
						else
							$real_status='<span class="label label-danger">'.$real_status.'</span>';
					else if($real_status=='New Joinee')
						$real_status='<span class="label label-info">'.$real_status.'</span>';
					else if($real_status=='Late')
						$real_status='<span class="label label-warning">'.$real_status.'</span>';
					else
						$real_status='<span class="label bg-purple-400">'.$real_status.'</span>';

				}

				if($real_status=='<span class="label bg-purple-400"></span>'){
					$real_status='<span class="label bg-purple-400">'.$real_status1.'</span>';
				}

				$bus_lateness= get_bus_users_late($r->user_id,$attendance_date,$r->office_location_id);
				if($bus_lateness){
					if($bus_lateness['late_rest_value']!=0){
						$bus_late='<br><span class="label bg-teal-400 mt-5">Bus LT-'.$bus_lateness['late_hours'].'</span>';
					}else{$bus_late='';}
				}else{
					$bus_late='';
				}

				$result_manual= check_OB_Hours($r->user_id,$attendance_date);
				if($result_manual){
					$ob_hours='<br><span class="label bg-teal-400 mt-5" title="OB ( '.$result_manual[0]->attendance_status.' ) - '.$result_manual[0]->reason.'">OB ( '.substr($result_manual[0]->attendance_status,0,1).' ) - '.$result_manual[0]->total_hours.'</span>';
					$total_work1=add_ob_hours(array($total_work1,$result_manual[0]->total_hours),$result_manual[0]->attendance_status);
				}else{
					$ob_hours='';
				}

				if(($attendance_details[0]->clock_in!='') && ($attendance_details[0]->clock_out=='') && (strtotime($attendance_date)!=strtotime(TODAY_DATE))){
					$login_entry=date("H:i", strtotime($attendance_details[0]->clock_in));
					$shift_end_b_1_hour=date("H:i", strtotime('0 hours', strtotime($attendance_details[0]->shift_end_time)));
					if(strtotime($login_entry) >= strtotime($shift_end_b_1_hour)){
						$clock_out2=$clock_in2;
						$clock_in2='-';
					}
				}

				$data[] = array(
					@$attendance_date,
					$real_status.$ob_hours.$bus_late,
					$change_title,
					$clock_in2,
					$clock_out2,
					@$time_late1,
					@$early_leaving1,
					@$overtime1.' / '.$overtime_night,
					@$total_work1,
				);

			}
		}
		$output = array(
			"draw" => $draw,
			"recordsTotal" => count($date_range),
			"recordsFiltered" => count($date_range),
			"data" => $data
		);
		$this->output($output);
	}

	public function update_attendance_list() {
		$data['title'] = $this->Xin_model->site_title();
		$attendance_date = $this->input->get("attendance_date");
		// get employee id
		$employee_id = $this->input->get("employee_id");
		if(!empty($this->userSession)){
			$this->load->view("timesheet/update_attendance", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$attendance_employee = $this->Timesheet_model->attendance_employee_with_date($employee_id,$attendance_date);
		$data = array();
		foreach($attendance_employee->result() as $r) {
			$employee_id=$r->employee_id;
			$attendance_date=$r->attendance_date;
			$attendance_status=$r->attendance_status;
			$attendance_id=$r->time_attendance_id;
			$list_of_attendace_status=$this->Timesheet_model->list_of_attendace_status();
			$options='<select style="padding: 5px;border: 1px solid #ECEEEF;" id="status_attendace_id_'.$attendance_id.'">';
			$options.='<option value="">Choose Status...</option>';
			foreach($list_of_attendace_status as $list_status){
				if($attendance_status==$list_status->type_code){$st="selected";}else{$st='';}
				$options.='<option '.$st.' value="'.$list_status->type_code.'">'.$list_status->type_name.'</option>';
			}
			$options.='</select>';

			if(in_array('31e',role_resource_ids())) {
				$button='<input type="hidden" id="employee_id_'.$attendance_id.'" value="'.$employee_id.'"/><button onclick="status_attendace_id('.$attendance_id.',1);" type="button" class="btn bg-teal-400 btn-sm">Update</button>';
			} else {
				$button='';
			}
			$data[] = array(
				$this->Xin_model->set_date_format($attendance_date),
				$attendance_status,
				$options,
				$button,
			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $attendance_employee->num_rows(),
			"recordsFiltered" => $attendance_employee->num_rows(),
			"data" => $data
		);
		$this->output($output);
	}

	public function update_attendance_status_list(){
		$employee_id = $this->input->get("employee_id");
		$attendance_id = $this->input->get("attendance_id");
		$status = $this->input->get("status");
		$data=array('attendance_status'=>$status);
		$update_attendace=$this->Timesheet_model->update_attendance_record($data,$attendance_id);
		if($update_attendace){
			echo 1;
		}
	}

	public function office_shift_list() {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("timesheet/office_shift", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$office_shift = $this->Timesheet_model->get_office_default_shifts();
		$data = array();
		foreach($office_shift->result() as $r) {
			if($r->shift_name!=''){
				$shift_name=$r->shift_name;
			}else{
				$shift_name='N/A';
			}
			$department = $this->Department_model->read_department_information($r->department_id);
			$location = $this->Xin_model->read_location_info($r->location_id);
			$changed_id= '<label class="label bg-teal-400">'.$location[0]->location_name.' >> '.$department[0]->department_name.'<label>';
			$shift_in_time = new DateTime($r->shift_in_time);
			$shift_out_time = new DateTime($r->shift_out_time);

			$week_offs=explode(',',$r->week_off);
			$week_off='';
			foreach($week_offs as $w_o){
				$week_off.='<span class="label bg-teal-400 mr-5">'.$w_o.'</span>';
			}

			if($r->shift_in_time == '') {
				$shift_time = '-';
			} else {
				$shift_time = $shift_in_time->format('h:i a') . ' to '.$shift_out_time->format('h:i a');
			}

			$edit_perm='';
			$delete_perm='';
			if(in_array('34e',role_resource_ids())) {
				$edit_perm='<li><a class="edit-data" href="#" data-toggle="modal" data-target=".edit-modal-data" data-office_shift_id="'. $r->office_shift_id.'" title="Edit"><i class="icon-pencil7"></i> Edit</a></li>';
			}

			if(in_array('34d',role_resource_ids())) {
				$delete_perm='<li><a class="delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->office_shift_id . '" title="Delete"><i class="icon-trash"></i> Delete</a></li>';
			}

			if($edit_perm=='' && $delete_perm==''){
				$edit_perm='<li><a class="text-danger">No Permission</a></li>';
			}

			$functions = '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right"> '.$edit_perm.$delete_perm.'</ul></li></ul>';
			$data[] = array(
				$shift_name,
				$location[0]->location_name,
				$department[0]->department_name,
				$shift_time,
				$week_off,
				$functions,
			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $office_shift->num_rows(),
			"recordsFiltered" => $office_shift->num_rows(),
			"data" => $data
		);
		$this->output($output);
	}

	public function holidays_list() {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("timesheet/holidays", $data);
		}
		else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$holidays = $this->Timesheet_model->get_holidays();
		$data = array();
		foreach($holidays->result() as $r) {
			if($r->is_publish==1): $publish = '<span class="label bg-success">Published</span>'; else: $publish = '<span class="label bg-warning">Un Published</span>'; endif;
			// get start date and end date
			$sdate = $this->Xin_model->set_date_format($r->start_date);
			$edate = $this->Xin_model->set_date_format($r->end_date);
			$edit_perm='';
			$view_perm='';
			$delete_perm='';
			if(in_array('35e',role_resource_ids())) {
				$edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit-modal-data"  data-holiday_id="'. $r->unique_id . '"><i class="icon-pencil7"></i> Edit</a></li>';
			}
			if(in_array('35v',role_resource_ids()) || visa_wise_role_ids() != '') {
				$view_perm='<li><a href="#" data-toggle="modal" data-target=".view-modal-data" data-holiday_id="'. $r->unique_id . '"><i class="icon-eye4"></i> View</a></li>';
			}
			if(in_array('35d',role_resource_ids())) {
				$delete_perm='<li><a class="delete" href="#" data-record-id="'. $r->unique_id . '"><i class="icon-trash"></i> Delete</a></li>';
			}
			$data[] = array(
				$r->event_name,
				$r->country_name,
				$publish,
				$sdate,
				$edate,
				'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$view_perm.$delete_perm.'</ul></li></ul>',
			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $holidays->num_rows(),
			"recordsFiltered" => $holidays->num_rows(),
			"data" => $data
		);
		$this->output($output);
	}

	public function ramadan_list() {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("timesheet/ramadan_schedule", $data);
		}
		else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$ramadan_schedule = $this->Timesheet_model->get_ramadan_schedule();
		$data = array();
		foreach($ramadan_schedule->result() as $r) {
			if($r->is_publish==1): $publish = '<span class="label bg-success">Published</span>'; else: $publish = '<span class="label bg-warning">Un Published</span>'; endif;
			// get start date and end date
			$sdate = $this->Xin_model->set_date_format($r->start_date);
			$edate = $this->Xin_model->set_date_format($r->end_date);

			$edit_perm='';
			$view_perm='';
			$delete_perm='';
			if(in_array('34e',role_resource_ids())) {
				$edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit-modal-data"  data-ramadan_schedule_id="'. $r->ramadan_schedule_id . '"><i class="icon-pencil7"></i> Edit</a></li>';
			}
			if(in_array('34v',role_resource_ids())) {
				$view_perm='<li><a href="#" data-toggle="modal" data-target=".view-modal-data" data-ramadan_schedule_id="'. $r->ramadan_schedule_id . '"><i class="icon-eye4"></i> View</a></li>';
			}
			if(in_array('34d',role_resource_ids())) {
				$delete_perm='<li><a class="delete" href="#" data-record-id="'. $r->ramadan_schedule_id . '"><i class="icon-trash"></i> Delete</a></li>';
			}

			$data[] = array(
				$r->event_name,
				$r->country_name,
				$publish,
				$sdate,
				$edate,
				'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$view_perm.$delete_perm.'</ul></li></ul>',
			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $ramadan_schedule->num_rows(),
			"recordsFiltered" => $ramadan_schedule->num_rows(),
			"data" => $data
		);
		$this->output($output);
	}

	public function leave_list() {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("timesheet/leave", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$visa_value = $this->input->get('visa_value');
		$department_value=$this->input->get('department_value');
		$location_value=$this->input->get('location_value');
		$leave_type_value=$this->input->get('leave_type_value');
		$status_value=$this->input->get('status_value');
		$date_from = $this->input->get("date_from");
		if($date_from){
			$date_from = new DateTime( $date_from);
			$date_from = $date_from->format('Y-m-d');
		}
		$date_to = $this->input->get("date_to");
		if($date_to){
			$date_to = new DateTime( $date_to);
			$date_to = $date_to->format('Y-m-d');
		}
		$leave = $this->Timesheet_model->get_leaves_by_filter($this->userSession['user_id'],$this->userSession['role_name'],
			compact('department_value','location_value','leave_type_value','status_value','date_from','date_to','visa_value'));


		$data = array();
		foreach($leave->result() as $r) {
			$user = $this->Xin_model->read_user_info($r->employee_id);
			$full_name = change_fletter_caps($user[0]->first_name. ' '.$user[0]->middle_name. ' '.$user[0]->last_name);
			$leave_id=$r->leave_id;
			$employee_id=$r->employee_id;
			$applied_on=$r->applied_on;
			$check_if_any_secondary_leave= $this->Timesheet_model->check_if_any_secondary_leave($leave_id,$employee_id,$applied_on);
			$secondary_leave_name ='';
			$secondary_duration ='';
			$location_name=$this->Timesheet_model->get_location_name($r->employee_id);
			$departmentDetails = $this->Timesheet_model->getDepartmentById($user[0]->department_id);

			if($check_if_any_secondary_leave){
				foreach($check_if_any_secondary_leave as $secondary){
					if($secondary->count_of_days==1){
						$s_dy=' ('.$secondary->count_of_days.' day.)';
					}else{
						$s_dy=' ('.$secondary->count_of_days.' days.)';
					}
					$secondary_leave_type = $this->Timesheet_model->read_leave_type_information($secondary->leave_type_id);
					$secondary_leave_name.= '<br><hr>'.$secondary_leave_type[0]->type_name.' ('.$secondary->leave_status_code.')';
					$secondary_duration.= '<br><hr>'.$this->Xin_model->set_date_format($secondary->from_date).' to '.$this->Xin_model->set_date_format($secondary->to_date).$s_dy;
				}

			}

			$leave_type = $this->Timesheet_model->read_leave_type_information($r->leave_type_id);
			$applied_on = $this->Xin_model->set_date_format($r->applied_on);

			if($r->count_of_days==1){
				$f_dy='';
				// $f_dy=' ('.$r->count_of_days.' day.)';
			}else{
				$f_dy='';
				// $f_dy=' ('.$r->count_of_days.' days.)';
			}

			$duration = $this->Xin_model->set_date_format($r->from_date).' to '.$this->Xin_model->set_date_format($r->to_date).$f_dy;
			$edit_perm='';
			$view_perm='';
			$delete_perm='';
			if(in_array('32e',role_resource_ids())) {
				//$edit_perm='<li><a data-toggle="modal" data-target=".edit-modal-data" data-leave_id="'. $r->leave_id.'" title="Edit"><i class="icon-pencil7"></i> Edit</a></li>';
			}
			if(in_array('32v',role_resource_ids()) || reporting_manager_access() || get_ceo_only() || hod_manager_access()) {
				$view_perm='<li><a href="'.site_url().'timesheet/leave_details/id/'.$r->leave_id.'/"><i class="icon-eye4"></i> Edit/View Details</a></li>';
				$href=	site_url().'timesheet/leave_details/id/'.$r->leave_id;
			}else{
				$href='#';
			}

			$approval_query=$this->db->query("select * from xin_employees_approval where type_of_approval='leave_request' AND field_id='".$r->applied_on."' AND approval_head_id='".$this->userSession['user_id']."' order by approval_id desc limit 1");
			$approval_result=$approval_query->result();
			if($approval_result){
				if($approval_result[0]->approval_status==3){
					$status = '<span class="label label-danger" style="float:right;">Rejected</span>';
				} else if($approval_result[0]->approval_status==2){
					$status = '<span class="label label-success" style="float:right;">Approved</span>';
				} else{
					$status = '<span class="label label-warning" style="float:right;">Pending</span>';
				}
			}else{
				if($r->status==1 || $r->status==4 ): $status = '<span class="label label-warning" style="float:right;">Pending</span>';
				elseif($r->status==2): $status = '<span class="label label-success" style="float:right;">Approved</span>';
				elseif($r->status==3 ): $status = '<span class="label label-danger" style="float:right;">Rejected</span>';
				endif;
			}



			if(in_array('32d',role_resource_ids())) {
				if(($r->reporting_manager_status==2 && $r->status==2) && ($this->userSession['role_name']!=AD_ROLE)){
					$delete_perm='<li><a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Approved status cannot be deleted." style="color:#aba4a4;"><i class="icon-trash"></i> Delete</a></li>';
				}else{
					$delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->leave_id . '" data-token_type="contract"><i class="icon-trash"></i> Delete</a></li>';
				}
			}
			$data[] = array(
				$r->leave_id,
				strtotime($r->from_date),
				$full_name,
				$departmentDetails[0]['department_name'],
				$location_name,
				$leave_type[0]->type_name.' ('.$r->leave_status_code.')'.$secondary_leave_name,
				$duration.$secondary_duration,
				$r->count_of_days.' days',
				$this->Xin_model->set_date_format($r->created_at),
				'<a href='.$href.'>'.$status.'</a>',
				'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$view_perm.$delete_perm.'</ul></li></ul>',
			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $leave->num_rows(),
			"recordsFiltered" => $leave->num_rows(),
			"data" => $data
		);
		$this->output($output);
	}

	public function leave_list_calendar(){
     	// $user_id = $this->uri->segment(3);
     	$my_schedule = array();

     	$visa_value = $this->input->get('visa_value');
		$department_value=$this->input->get('department_value');
		$location_value=$this->input->get('location_value');
		$leave_type_value=$this->input->get('leave_type_value');
		$status_value=$this->input->get('status_value');
		$date_from = $this->input->get("date_from");
		if($date_from){
			$date_from = new DateTime( $date_from);
			$date_from = $date_from->format('Y-m-d');
		}
		$date_to = $this->input->get("date_to");
		if($date_to){
			$date_to = new DateTime( $date_to);
			$date_to = $date_to->format('Y-m-d');
		}

     	$leave = $this->Timesheet_model->get_leaves_by_filter($this->userSession['user_id'],$this->userSession['role_name'],
			compact('department_value','location_value','leave_type_value','status_value','date_from','date_to','visa_value'),1);
     	
     	// pr($leave->result());die;

	 	if($leave){
			foreach($leave->result() as $r) {
	  			
	  			if($r->status != 3){
	  				
		  			$employeeData = $this->Timesheet_model->getEmployeeData($r->employee_id);

		  			if($r->leave_type_id == "172"){
		  				$title = $employeeData[0]['first_name'].$employeeData[0]['middle_name'].$employeeData[0]['last_name'].' ( Annual Leave - '.$r->count_of_days.' days )'; 
		  				if($r->status == 2){
		  					$color = '#006600';
		  				}else{
		  					$color = '#90ee90'; 
		  				}
		  			}elseif($r->leave_type_id == "173"){
		  				$title = $employeeData[0]['first_name'].$employeeData[0]['middle_name'].$employeeData[0]['last_name'].' ( Emergency Leave - '. $r->count_of_days .' days )'; 
		  				if($r->status == 2){
		  					$color = '#8181ff';
		  				}else{
		  					$color = '#9a9aff'; 
		  				}
		  			}elseif($r->leave_type_id == "174"){
		  				$title = $employeeData[0]['first_name'].$employeeData[0]['middle_name'].$employeeData[0]['last_name'].' ( Sick Leave - '. $r->count_of_days .' days )';
		  				if($r->status == 2){
		  					$color = '#ffbf82';
		  				}else{
		  					$color = '#ffe6ce';
		  				}
		  			}elseif($r->leave_type_id == "176"){ 
		  				$title = $employeeData[0]['first_name'].$employeeData[0]['middle_name'].$employeeData[0]['last_name'].' ( Authorised Absence - '. $r->count_of_days .' days )'; 
		  				if($r->status == 2){
		  					$color = '#e65481';
		  				}else{
		  					$color = '#f097b2'; 
		  				}
		  			}elseif($r->leave_type_id == "208"){ 
		  				$title = $employeeData[0]['first_name'].$employeeData[0]['middle_name'].$employeeData[0]['last_name'].' ( Bereavement Leave - '. $r->count_of_days .' days )'; 
		  				if($r->status == 2){
		  					$color = '#8765c4';
		  				}else{
		  					$color = '#ddd3ee'; 
		  				}
		  			}
		  			
		  			$to_date = date('Y-m-d', strtotime($r->to_date . ' +1 day'));

		  			if(visa_wise_role_ids() == ''){
	         			$my_schedule[]=array('title'=>$title,'start'=>$r->from_date,'end'=>$to_date,'color'=>$color,'id'=>$r->leave_id,'clickfun'=>'clickfun($r->leave_id)');
	         		}else{
	         			$my_schedule[]=array('title'=>$title,'start'=>$r->from_date,'end'=>$to_date,'color'=>$color,'id'=>$r->leave_id);
	         		}
	         	}

			}
     	}

       	echo json_encode($my_schedule);
	}

	public function update_attendance_add()
	{
		$data['title'] = $this->Xin_model->site_title();
		$employee_id = $this->input->get('employee_id');
		$data = array(
			'employee_id' => $employee_id,
		);
		if(!empty($this->userSession)){
			$this->load->view('timesheet/dialog_attendance', $data);
		} else {
			redirect('');
		}
	}

	public function add_task() {
		if($this->input->post('add_type')=='task') {
			$Return = array('result'=>'', 'error'=>'');
			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');
			$description = $this->input->post('description');
			$st_date = strtotime($start_date);
			$ed_date = strtotime($end_date);
			$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
			/* Server side PHP input validation */
			if($this->input->post('task_name')==='') {
				$Return['error'] = "The task name field is required.";
			} else if($this->input->post('start_date')==='') {
				$Return['error'] = "The start date field is required.";
			} else if($this->input->post('end_date')==='') {
				$Return['error'] = "The end date field is required.";
			} else if($st_date >= $ed_date) {
				$Return['error'] = "Start Date should be less than or equal to End Date.";
			} else if($this->input->post('task_hour')==='') {
				$Return['error'] = "The task hour field is required.";
			} else if(empty($this->input->post('assigned_to'))) {
				$Return['error'] = "The assigned to field is required.";
			}

			if($Return['error']!=''){
				$this->output($Return);
			}

			$assigned_ids = implode(',',$this->input->post('assigned_to'));

			$data = array(
				'created_by' => $this->input->post('user_id'),
				'task_name' => $this->input->post('task_name'),
				'assigned_to' => $assigned_ids,
				'start_date' => $this->input->post('start_date'),
				'end_date' => $this->input->post('end_date'),
				'task_hour' => $this->input->post('task_hour'),
				'task_progress' => '0',
				'description' => $qt_description,
				'created_at' => date('Y-m-d h:i:s')
			);
			$result = $this->Timesheet_model->add_task_record($data);
			userlogs('Tasks','Task Added To Employees',count($this->input->post('assigned_to')));// count of employees
			if ($result == TRUE) {
				$Return['result'] = 'Task added.';
				//get setting info
				$setting = $this->Xin_model->read_setting_info(1);
				if($setting[0]->enable_email_notification == 'yes') {
					//load email library
					$this->email->set_mailtype("html");
					$to_email = array();
					foreach($this->input->post('assigned_to') as $p_employee) {
						// assigned by
						$user_info = $this->Xin_model->read_user_info($this->input->post('user_id'));
						$full_name = change_fletter_caps($user_info[0]->first_name.' '.$user_info[0]->middle_name.' '.$user_info[0]->last_name);

						// assigned to
						$user_to = $this->Xin_model->read_user_info($p_employee);
						//get company info
						$cinfo = $this->Xin_model->read_company_setting_info(1);
						//get email template
						$template = $this->Xin_model->read_email_template(14);

						$subject = $template[0]->subject.' - '.$cinfo[0]->company_name;
						$logo = base_url().'uploads/logo/'.$cinfo[0]->logo;
						$message = '
			<div style="background:#f6f6f6;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;padding: 20px;">
			<img src="'.$logo.'" title="'.$cinfo[0]->company_name.'"><br>'.str_replace(array("{var site_name}","{var site_url}","{var task_name}","{var task_assigned_by}"),array($cinfo[0]->company_name,site_url(),$this->input->post('task_name'),$full_name),htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';

						$this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
						$this->email->to($user_to[0]->email);

						$this->email->subject($subject);
						$this->email->message($message);
						//$this->email->send();
					}
				}

			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function add_leave(){

		if($this->input->post('add_type')=='leave') {
			$Return = array('result'=>'', 'error'=>'', 'message'=>'');
			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');
			$leave_type=$this->input->post('leave_type');
			$employee_id=$this->input->post('employee_id');
			$user_info=$this->Xin_model->read_user_info($employee_id);
			$location_name=strtolower($this->Timesheet_model->get_location_name($employee_id));

			$mandatory_f=$this->input->post('mandatory_f');
			$department = $this->Department_model->read_department_information($user_info[0]->department_id);
			$department_name=$department[0]->department_name;

			/*Actual Leaves*/
			$actual_leave_available=$this->Timesheet_model->read_leave_type_information($leave_type);
			$actual_leave_name=$actual_leave_available[0]->type_name;
			$actual_leave_count=$actual_leave_available[0]->days_per_year;
			/*Actual Leaves*/

			$st_date = strtotime($start_date);
			$ed_date = strtotime($end_date);

			$has_reporting_manager=$this->Timesheet_model->has_reporting_manager($employee_id);
			/* Server side PHP input validation */

			if($this->input->post('leave_type')==='') {
				$Return['error'] = "The leave type field is required.";
			} else if($this->input->post('start_date')==='') {
				$Return['error'] = "The start date field is required.";
			} else if($this->input->post('end_date')==='') {
				$Return['error'] = "The end date field is required.";
			} else if($st_date > $ed_date) {
				$Return['error'] = "Start Date should be less than or equal to End Date.";
			} else if($employee_id==='') {
				$Return['error'] = "The employee field is required.";
			} else if($has_reporting_manager=='0') {
				$Return['error'] = "There is no reporting manager assigned to this employer.";
			} else if($this->input->post('reason')==='') {
				if($actual_leave_name=='Authorised Absence' || $actual_leave_name=='Emergency Leave'){
					$Return['error'] = "The leave reason field is required.";
				}
			}

			if($actual_leave_name=='Sick Leave' && $mandatory_f==1) {
				// && strtoupper(($department_name)!='WH')
				if($_FILES['document_file']['size'][0]== 0) {
					$Return['error'] = "For sick leaves you should upload the medical documents";
				}
			}

			$get_shifts_bydep_loc=$this->Employees_model->get_shifts_bydep_loc($employee_id,$user_info[0]->office_location_id,$user_info[0]->department_id,$start_date);
			$week_off_count=count(explode(',',$get_shifts_bydep_loc['week_off']));
			//$location_name=='jlt'
			if(($week_off_count > 1) && $start_date!='' && $end_date!='' && (strtotime($start_date) >=strtotime(ANNUAL_LEAVE_POLICY_STARTDATE))){
				$country=$this->Location_model->read_location_information($user_info[0]->office_location_id);
				$country_id=$country[0]->country;
				//check leave one day before leave start day
				$check_pre_date=date('Y-m-d', strtotime("-1 days",strtotime($start_date)));
				$check_pre_day = date('l',strtotime($check_pre_date));
				$shift_data=$this->Employees_model->get_shifts_bydep_loc($employee_id,$user_info[0]->office_location_id,$user_info[0]->department_id,$check_pre_date);
				$weak_off_dates=explode(',',$shift_data['week_off']);
				$public_holiday=$this->Employees_model->get_public_holiday($employee_id,$country_id,$user_info[0]->department_id,$check_pre_date);
				//check leave one day before leave start day
				//check leave one day after leave start day
				$check_post_date=date('Y-m-d', strtotime("+1 days",strtotime($end_date)));
				$check_post_day = date('l',strtotime($check_post_date));
				$shift_data_post=$this->Employees_model->get_shifts_bydep_loc($employee_id,$user_info[0]->office_location_id,$user_info[0]->department_id,$check_post_date);
				$weak_off_dates_post=explode(',',$shift_data_post['week_off']);
				$public_holiday_post=$this->Employees_model->get_public_holiday($employee_id,$country_id,$user_info[0]->department_id,$check_post_date);
				//check leave one day after leave start day

				//$prev_date=$this->bring_prev_next_date($check_pre_date,'Prev',$user_info[0]->office_location_id,$user_info[0]->department_id,$country_id);
				$next_date=$this->bring_prev_next_date($check_post_date,'Next',$user_info[0]->office_location_id,$user_info[0]->department_id,$country_id,$user_info[0]->user_id);

				/*if((in_array($check_pre_day,$weak_off_dates)) && (in_array($check_post_day,$weak_off_dates)) && ($actual_leave_name!='Sick Leave')){
                $Return['error'] = "Your Leave start date might be ".$prev_date." and end date might be ".$next_date.".";
                }
                else if(($public_holiday) && ($public_holiday_post) && ($actual_leave_name!='Sick Leave')){
                $Return['error'] = "Your Leave start date might be ".$prev_date." and end date might be ".$next_date.".";
                }
                else if(in_array($check_pre_day,$weak_off_dates) && ($actual_leave_name!='Sick Leave')){
                $Return['error'] = "Your Leave start date might be ".$prev_date.".";
                }else if($public_holiday){
                $Return['error'] = "Your Leave start date might be ".$prev_date.".";
                }else*/
				if(in_array($check_post_day,$weak_off_dates_post) && ($actual_leave_name!='Sick Leave') && ($actual_leave_name!='Sick Leave Unpaid')){
					$Return['error'] = "Your Leave end date might be '".$next_date." along with weekend";
				}
                /*else if($public_holiday_post && ($actual_leave_name!='Sick Leave')){
                	$Return['error'] = "Your Leave end date might be ".$next_date.".";

                }*/
			}

			/*Get HR Administror Mails BY Location Wise*/
			$hr_mails=get_hr_mail_bylocation($user_info[0]->office_location_id,$employee_id);
			if(!$hr_mails){
				$Return['error'] = 'There is no HR Administartor Assigned for this location.Contact Your HR Team for further assist.';
			}
			/*Get HR Administror Mails BY Location Wise*/


			if($Return['error']!=''){
				$this->output($Return);
			}

			$fname = [];
			if($_FILES['document_file']['size'][0]!= 0) {
				$allowed =  array('png','jpg','jpeg','pdf','gif');
				$count=count($_FILES['document_file']['name']);
				for($i=0;$i<$count;$i++){
					$filename = strtolower($_FILES['document_file']['name'][$i]);
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
					if(in_array($ext,$allowed)){
						$tmp_name = $_FILES["document_file"]["tmp_name"][$i];
						$documentd = "uploads/leavedocument/";
						$name = basename($_FILES["document_file"]["name"][$i]);
						$newfilename = 'document_'.round(microtime(true)).'_'.$i.'.'.$ext;
						move_uploaded_file($tmp_name, $documentd.$newfilename);
						$fname[] = $newfilename;
					}else {
						$Return['error'] = $this->lang->line('xin_employee_picture_type');
					}
				}
			}
			$fname=implode(',',$fname);
			$rand=strtotime(date('Y-m-d H:i:s'));
			$leave_dates=json_decode($this->input->post('leave_dates'));
			if($leave_dates){
				foreach($leave_dates as $leave_dt){
					foreach($leave_dt as $leave_dat){
						$read_leave_type_id=$this->Timesheet_model->read_leave_type_id($leave_dat->leave_name);
						$start_date=format_date('Y-m-d',$leave_dat->start_date);
						$end_date=format_date('Y-m-d',$leave_dat->end_date);
						$start = new DateTime($start_date);
						$end = new DateTime($end_date);
						$count_of_days = ($start->diff($end,true)->days)+1;
						$ends = $end->modify( '+1 day' );
						$interval_re = new DateInterval('P1D');
						$date_range = new DatePeriod($start, $interval_re ,$ends);
						$leave_result=0;
						$leave_result_array='';
						$leave_array='';
						foreach($date_range as $date1) {
							$leave_date = $date1->format("Y-m-d");
							$leave_result_array= $this->Timesheet_model->check_leave_result($leave_date,$this->input->post('employee_id'),$read_leave_type_id);
							$leave_result+=$leave_result_array['value'];
							if($leave_result_array['l_date']!='')
								$leave_array.= format_date('d F Y',$leave_result_array['l_date']).' - '.$leave_result_array['l_name'].'<br>';
						}
						if($leave_dat->available_leave_days){
							$available_leave_days=$leave_dat->available_leave_days;
						}else{$available_leave_days=0;}

						if($leave_dat->leave_status_code){
							$leave_status_code=$leave_dat->leave_status_code;
						}else{$leave_status_code=0;}

						if($leave_result==0){
							$data = array(
								'employee_id' => $this->input->post('employee_id'),
								'leave_type_id' => $read_leave_type_id,
								'from_date' => $start_date,
								'to_date' => $end_date,
								'applied_on' => $rand,
								'reason' => $this->input->post('reason'),
								'documentfile' => $fname,
								'available_leave_days'=> $available_leave_days,
								'leave_status_code'=> $leave_status_code,
								'count_of_days' => $count_of_days,
								'status' => '1',
								'created_at' => date('Y-m-d H:i:s')
							);
							$result = $this->Timesheet_model->add_leave_record($data);
						}else{
							$Return['error'] = 'You already applied leaves on below date.<br>'.$leave_array;
							$this->output($Return);
							exit;
						}
					}
				}
			}
			$get_least_id=$this->db->query("select leave_id from xin_leave_applications where applied_on='".$rand."' order by leave_id asc limit 1");
			$result_least_id=$get_least_id->result();
			$link_id_send=$result_least_id[0]->leave_id;
			/*User Logs*/
			$affected_id= table_max_id('xin_leave_applications','leave_id');
			userlogs('Timesheet-Employee Leave-Add','Sent A Leave  Request',$affected_id['field_id'],$affected_id['datas']);
			/*User Logs*/
			if ($result == TRUE) {
				$Return['result'] = 'Leave added.';
				$approval_creation=approval_creation($this->input->post('employee_id'),'leave_request',$actual_leave_name,$rand,'first');
				/*$setting = $this->Xin_model->read_setting_info(1);
                if($setting[0]->enable_email_notification == 'yes') {
                $cinfo = $this->Xin_model->read_company_setting_info(1);
                $template = $this->Xin_model->read_email_template(5);
                $user_info = $this->Xin_model->read_user_info($this->input->post('employee_id'));
                $full_name = change_fletter_caps($user_info[0]->first_name.' '.$user_info[0]->middle_name.' '.$user_info[0]->last_name);
                $designation = $this->Designation_model->read_designation_information($user_info[0]->designation_id);
                $department = $this->Department_model->read_department_information($user_info[0]->department_id);
                $department_designation = $designation[0]->designation_name.'('.$department[0]->department_name.')';

                $subject = $full_name.' '.$template[0]->subject;
                $leave_name=$leave_dates[0][0]->leave_name;
                if($leave_name==''){$leave_name='Leave';}
                $link_url=$_SERVER['HTTP_HOST'].base_url().base64_encode('MAILREDIRECT-timesheet/leave_details/id/'.$link_id_send);
                if($approval_creation){
                    foreach($approval_creation as $app_cr){
                        $recp_info = $this->Xin_model->read_user_info($app_cr['head_id']);
                        $recp_full_name = change_fletter_caps($recp_info[0]->first_name.' '.$recp_info[0]->middle_name.' '.$recp_info[0]->last_name);
                        $recp_designation = $this->Designation_model->read_designation_information($recp_info[0]->designation_id);
                        $recp_department = $this->Department_model->read_department_information($recp_info[0]->department_id);
                        $recp_dep_des = $recp_designation[0]->designation_name.'('.$recp_department[0]->department_name.')';

                        $message = '<div style="'.MAIL_OUTER_CSS.'">'.str_replace(array("{var rm_name}","{var site_name}","{var site_url}","{var employee_name}","{var leave_name}","{var dep_name}"),array($recp_full_name,$cinfo[0]->company_name,$link_url,$full_name,$leave_name,$department_designation),htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';
                        if(TESTING_MAIL==TRUE){
                        $this->email->from(FROM_MAIL, $full_name);
                        $this->email->to(TO_MAIL);
                        }else{
                        $this->email->from(FROM_MAIL, $full_name);
                        $this->email->to($recp_info[0]->email);
                        }
                        $this->email->subject($subject);
                        $this->email->message($message);
                        $this->email->send();
                    }
			}$Return['message']=$message;
			}*/
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function return_exact_date_leave($apply_date,$employee_leaves){
		$leave=0;
		$counts=0;
		$beginning_date='';
		$finished_date='';
		foreach($employee_leaves as $leaves){
			if((strtotime($apply_date) >=strtotime($leaves['start_year_date'])) && (strtotime($apply_date) <=strtotime($leaves['end_year_date']))){
				$beginning_date=$leaves['start_year_date'];
				$finished_date=$leaves['end_year_date'];
				$leave=$leaves['available_leave'];
				$counts=$leaves['counts'];
			}
		}
		if(($leave-$counts) < 0 ){
			$count=0;
		}else{
			$count=$leave-$counts;
		}
		return array('counts'=>($count),'beginning_date'=>$beginning_date,'finished_date'=>$finished_date);
	}

	public function bring_prev_next_date($check_date,$status,$location,$department,$country,$employee_id){
		if($status=='Prev'){
			while($check_date){
				$check_pre_day = date('l',strtotime($check_date));
				$shift_data=$this->Employees_model->get_shifts_bydep_loc($employee_id,$location,$department,$check_date);
				$weak_off_dates=explode(',',$shift_data['week_off']);
				$public_holiday=$this->Employees_model->get_public_holiday($employee_id,$country,$department,$check_date);
				if(in_array($check_pre_day,$weak_off_dates)){//echo $i+=1;echo "<br>";
					$check_date=date('Y-m-d', strtotime("-1 days",strtotime($check_date)));
					continue;
				}else if($public_holiday){//echo $i+=1;echo "<br>";
					$check_date=date('Y-m-d', strtotime("-1 days",strtotime($check_date)));
					continue;
				}else {
					return date('d F Y', strtotime("+1 days",strtotime($check_date)));
				}
			}
		}
		else if($status=='Next'){
			while($check_date){
				$check_pre_day = date('l',strtotime($check_date));
				$shift_data=$this->Employees_model->get_shifts_bydep_loc($employee_id,$location,$department,$check_date);
				$weak_off_dates=explode(',',$shift_data['week_off']);

				$public_holiday=$this->Employees_model->get_public_holiday($employee_id,$country,$department,$check_date);

				if(in_array($check_pre_day,$weak_off_dates)){//echo $i+=1;echo "<br>";
					$check_date=date('Y-m-d', strtotime("+1 days",strtotime($check_date)));
					continue;
				}else if($public_holiday){//echo $i+=1;echo "<br>";
					$check_date=date('Y-m-d', strtotime("+1 days",strtotime($check_date)));
					continue;
				}else {
					return date('d F Y', strtotime("-1 days",strtotime($check_date)));
				}
			}
		}
	}

	/*check_leave_availability start*/
	public function check_leave_availability(){
		if($this->input->get('type')=='availability'){
			$msg_show='Yes';
			$employee_id=$this->input->get('employee_id');
			$leave_type=$this->input->get('leave_type');
			$start_date=format_date('Y-m-d',$this->input->get('start_date'));
			$end_date=format_date('Y-m-d',$this->input->get('end_date'));
			$user_info=$this->Xin_model->read_user_info($employee_id);
			/*Actual Leaves*/
			$actual_leave_available=$this->Timesheet_model->read_leave_type_information($leave_type);
			$actual_leave_name=$actual_leave_available[0]->type_name;
			$actual_leave_count=$actual_leave_available[0]->days_per_year;
			/*Actual Leaves*/
			$date_of_joining=$this->Timesheet_model->check_date_of_joining($employee_id);
			$country=$this->Location_model->read_location_information($user_info[0]->office_location_id);
			$country_id=$country[0]->country;
			$country_name=$country[0]->country_name;
			$visa_query=$this->Payroll_model->get_employee_template($employee_id,0,0);
			$visa_result=$visa_query->result();
			$visa_type=@$visa_result[0]->type;

			if(!$visa_result && $country_name=='United Arab Emirates'){
				$disable_status=1;
				$date_status='<div class="alert alert-info alert-bordered ">Check the visa type to this employee.</div>';
				echo json_encode(array('date_status'=>$date_status,'message_status'=>$date_status,'disable_status'=>$disable_status,'counts'=>0));
				exit;
			}

			$system_settings = $this->Xin_model->read_setting_info(1);
			$eligible_visa_under=json_decode($system_settings[0]->eligible_visa_under);
			$sick_leave_start_date=date('Y-m-d',strtotime(JOINEE_SICK_LEAVE_NOT_ALLOW,strtotime($date_of_joining)));
			$sick_leave_unpaid_start_date=date('Y-m-d',strtotime(SICKLEAVE_NOPAID,strtotime($date_of_joining)));
			$maternity_leave_start_date=date('Y-m-d',strtotime(MATERNITY_LEAVE_ALLOW,strtotime($date_of_joining)));
			$annual_leave_start_date=date('Y-m-d',strtotime(ANNUAL_LEAVE_ALLOW,strtotime($date_of_joining)));
			$annual_leave_full_start_date=date('Y-m-d',strtotime(ANNUAL_LEAVE_ALLOW_FULL,strtotime($date_of_joining)));

			$contract_dates=$this->Timesheet_model->get_contract_dates($employee_id);
			$location_name=strtolower($this->Timesheet_model->get_location_name($employee_id));

			if($contract_dates['to_date']!='' && $contract_dates['to_date']!='Unlimited'){
				$current_date=$contract_dates['to_date'];
			}else if($contract_dates['to_date']=='Unlimited'){
				$current_date=date('Y-m-d',strtotime('+1 year',strtotime(TODAY_DATE)));
			}else{
				$current_date=TODAY_DATE;
			}
			$contract_to_dates=date('Y-m-d',strtotime('+2 month',strtotime($contract_dates['to_date'])));
			$get_shifts_bydep_loc=$this->Employees_model->get_shifts_bydep_loc($employee_id,$user_info[0]->office_location_id,$user_info[0]->department_id,$start_date);
			$week_off_count=count(explode(',',$get_shifts_bydep_loc['week_off']));

			if(!$this->input->get('message_counts')){
				/*For JLT Employee's Leave Restrictions$location_name=='jlt' */
				if(($week_off_count > 1) && $start_date!='' && $end_date!='' && (strtotime($start_date) >=strtotime(ANNUAL_LEAVE_POLICY_STARTDATE))){

					//check leave one day before leave start day
					/*$check_pre_date=date('Y-m-d', strtotime("-1 days",strtotime($start_date)));
                    $check_pre_day = date('l',strtotime($check_pre_date));
                    $shift_data=$this->Employees_model->get_shifts_bydep_loc($employee_id,$user_info[0]->office_location_id,$user_info[0]->department_id,$check_pre_date);
                    $weak_off_dates=explode(',',$shift_data['week_off']);
                    $public_holiday=$this->Employees_model->get_public_holiday($employee_id,$country_id,$user_info[0]->department_id,$check_pre_date);*/
					//check leave one day before leave start day
					//check leave one day after leave start day
					$check_post_date=date('Y-m-d', strtotime("+1 days",strtotime($end_date)));
					$check_post_day = date('l',strtotime($check_post_date));
					$shift_data_post=$this->Employees_model->get_shifts_bydep_loc($employee_id,$user_info[0]->office_location_id,$user_info[0]->department_id,$check_post_date);
					$weak_off_dates_post=explode(',',$shift_data_post['week_off']);
					$public_holiday_post=$this->Employees_model->get_public_holiday($employee_id,$country_id,$user_info[0]->department_id,$check_post_date);
					//check leave one day after leave start day

					if($actual_leave_name!='Sick Leave' || $actual_leave_name!='Bereavement Leave'){
						$message_status='<div class="alert alert-info alert-bordered ">Leave Starts or Ends before or after the off-days.the off days shall be deedmed as Leave.</div>';
					}

					//$prev_date=$this->bring_prev_next_date($check_pre_date,'Prev',$user_info[0]->office_location_id,$user_info[0]->department_id,$country_id);
					$next_date=$this->bring_prev_next_date($check_post_date,'Next',$user_info[0]->office_location_id,$user_info[0]->department_id,$country_id,$user_info[0]->user_id);
					/*if((in_array($check_pre_day,$weak_off_dates)) && (in_array($check_post_day,$weak_off_dates)) && ($actual_leave_name!='Sick Leave')){
                    $disable_status=1;
                    $date_status='<div class="alert alert-info alert-bordered ">

                                                    Your Leave start date might be '.$prev_date.' and end date might be '.$next_date.'.</div>';
                    echo json_encode(array('date_status'=>$date_status,'message_status'=>$message_status,'disable_status'=>$disable_status));
                    exit;
                    }
                    else if(($public_holiday) && ($public_holiday_post)  && ($actual_leave_name!='Sick Leave')){
                    $disable_status=1;
                    $date_status='<div class="alert alert-info alert-bordered ">

                                                    Your Leave start date might be '.$prev_date.' and end date might be '.$next_date.'.</div>';
                    echo json_encode(array('date_status'=>$date_status,'message_status'=>$message_status,'disable_status'=>$disable_status));
                    exit;
                    }
                    else if(in_array($check_pre_day,$weak_off_dates)  && ($actual_leave_name!='Sick Leave')){

                    $disable_status=1;
                    $date_status='<div class="alert alert-info alert-bordered ">

                                                    Your Leave start date might be '.$prev_date.'.</div>';
                    echo json_encode(array('date_status'=>$date_status,'message_status'=>$message_status,'disable_status'=>$disable_status));
                    exit;
                    }
                    else if($public_holiday  && ($actual_leave_name!='Sick Leave')){
                    $disable_status=1;
                    $date_status='<div class="alert alert-info alert-bordered ">

                                                    Your Leave start date might be '.$prev_date.'.</div>';
                    echo json_encode(array('date_status'=>$date_status,'message_status'=>$message_status,'disable_status'=>$disable_status));
                    exit;
                    }
                    else */
					if(in_array($check_post_day,$weak_off_dates_post)  && ($actual_leave_name!='Sick Leave') && ($actual_leave_name!='Sick Leave Unpaid') && ($actual_leave_name!='Bereavement Leave')){

						$disable_status=1;
						$date_status='<div class="alert alert-info alert-bordered ">

										Your Leave end date might be '.$next_date.'.</div>';
						echo json_encode(array('date_status'=>$date_status,'message_status'=>$message_status,'disable_status'=>$disable_status));
						exit;
					}
					// else if($public_holiday_post  && ($actual_leave_name!='Sick Leave') && ($actual_leave_name!='Bereavement Leave')){

					// 	$disable_status=1;
					// 	$date_status='<div class="alert alert-info alert-bordered ">

					// 					Your Leave end date might be '.$next_date.'.</div>';
					// 	echo json_encode(array('date_status'=>$date_status,'message_status'=>$message_status,'disable_status'=>$disable_status));
					// 	exit;

					// }
					// else{
					// 	$message_status='';
					// 	$disable_status=0;
					// }
					$message_status='';
					$disable_status=0;
				}
			}
			/*For JLT Employee's Leave Restrictions*/
			$authorised_absent='Authorised Absence';
			$endDate = DateTime::createFromFormat('Y-m-d',$contract_dates['to_date']);
			$currentDate = new DateTime();
			if(strtotime($start_date) > strtotime($end_date)){
				$date_status='<div class="alert alert-info alert-bordered ">
		 				<span class="text-semibold"></span> Start date should be less than or equal to End date..
			     </div>';
				$disable_status=1;
				echo json_encode(array('date_status'=>$date_status,'message_status'=>$message_status,'disable_status'=>$disable_status,'counts'=>0));
			}
			else if(((strtotime($start_date) > strtotime($contract_to_dates)) || (strtotime($end_date) > strtotime($contract_to_dates))) && $contract_dates['to_date']!='' && $contract_dates['to_date']!='Unlimited' && $currentDate < $endDate){
				$date_status='<div class="alert alert-info alert-bordered ">
		 			<span class="text-semibold"></span> Contract date ends on '.format_date('d F Y',$contract_dates['to_date']).'..
			     </div>';
				$message_status='<div class="alert alert-info alert-bordered ">
		 			<span class="text-semibold"></span> Contract date has expired. You can\'t apply any leaves now. Contact HR Administartor.
			     </div>';
				$disable_status=1;
				echo json_encode(array('date_status'=>$date_status,'message_status'=>$message_status,'disable_status'=>$disable_status,'counts'=>0));
			}
			else{
				//Counts Of Leaves
				if($date_of_joining!=''){
					if((!in_array($visa_type,$eligible_visa_under)) && $actual_leave_name=='Sick Leave'){
						$leave_array[]=array(array('start_date'=>$start_date,'end_date'=>$end_date,'leave_name'=>$actual_leave_name,'available_leave_days'=>0,'leave_status_code'=>'SL-UP'));
						$date_status='<div class="alert alert-info alert-bordered ">
		 				<span class="text-semibold"></span> '.format_date('d F Y',$start_date).' to '.format_date('d F Y',$end_date).return_count_days($start_date,$end_date).' <span class="text-semibold">Sick Leave Unpaid.</span>.
			     		</div>';
						$message_status='<div class="alert alert-info alert-bordered ">You are not eligible to take '.$actual_leave_name.'. This should be mark it as '.$actual_leave_name.' Unpaid (SL-UP).</div>';
						echo json_encode(array('date_status'=>$date_status,'message_status'=>$message_status,'disable_status'=>0,'leave_array'=>json_encode($leave_array),'counts'=>1));
						exit;
					}

					if($actual_leave_name=='Sick Leave'){
						$contract_start_date=$sick_leave_start_date;
						$leave_start_date=$sick_leave_start_date;
						$months=str_replace('+','',JOINEE_SICK_LEAVE_NOT_ALLOW);
					}elseif($actual_leave_name=='Sick Leave Unpaid'){

						// $contract_start_date=$sick_leave_start_date;
						// $leave_start_date=$sick_leave_start_date;
						// $months=str_replace('+','',SICKLEAVE_NOPAID);

						$leave_array[]=array(array('start_date'=>$start_date,'end_date'=>$end_date,'leave_name'=>$actual_leave_name,'available_leave_days'=>0,'leave_status_code'=>'SL-UP'));
						$date_status='<div class="alert alert-info alert-bordered ">
		 				<span class="text-semibold"></span> '.format_date('d F Y',$start_date).' to '.format_date('d F Y',$end_date).return_count_days($start_date,$end_date).' <span class="text-semibold">Sick Leave Unpaid.</span>.
			     		</div>';
						$message_status='<div class="alert alert-info alert-bordered ">This should be mark it as '.$actual_leave_name.' (SL-UP).</div>';
						echo json_encode(array('date_status'=>$date_status,'message_status'=>$message_status,'disable_status'=>0,'leave_array'=>json_encode($leave_array),'counts'=>1));
						exit;

					}
					else if($actual_leave_name=='Maternity Leave'){
						$contract_start_date=format_date('Y-m-d',$date_of_joining);
						$leave_start_date=$contract_start_date;
						$gender_type=$this->Timesheet_model->find_gender($employee_id);
						$months='0 months';
						if(@$gender_type=='Male'){
							$message_status='<div class="alert alert-info alert-bordered ">
								 	<span class="text-semibold"></span> You are not avail to take '.$actual_leave_name.'. Contact your Reporting Manager.
								 </div>';
							$disable_status=1;
							echo json_encode(array('date_status'=>'','message_status'=>$message_status,'disable_status'=>$disable_status,'counts'=>0));
							exit;
						}
					}
					else if($actual_leave_name=='Annual Leave'){
						$contract_start_date=$annual_leave_start_date;
						$leave_start_date=$annual_leave_start_date;
						$months=str_replace('+','',ANNUAL_LEAVE_ALLOW);
					}
					else{
						$contract_start_date=format_date('Y-m-d',$date_of_joining);
						$leave_start_date=$contract_start_date;
						$months='0 months';
					}

					$contract_end_date=date('Y-m-d', strtotime("+1 year",strtotime($current_date)));

					$begin = new DateTime($contract_start_date);
					$end   = new DateTime($contract_end_date);

					for($i = $begin; $i <= $end;){
						$year_start_date=$i->format("Y-m-d");
						$year_end_date=$i->modify('+1 year');
						$year_end_date=date('Y-m-d', strtotime("-1 days",strtotime($year_end_date->format("Y-m-d"))));
						$due_dates[$year_start_date] = $year_end_date;
					}
					$data=[];
					$available_leave=0;
					foreach($due_dates as $start_y_d=>$end_y_d){
						$counts=0;
						$query = $this->db->query("SELECT from_date,to_date from xin_leave_applications where employee_id = '".$employee_id."' and leave_type_id='".$leave_type."' and reporting_manager_status=2 and status=2 and ((from_date BETWEEN '".$start_y_d."' and '".$end_y_d."') OR (to_date='".$start_y_d."'))");
						$result=$query->result();
						foreach($result as $res){
							$start = new DateTime($res->from_date);
							$end = new DateTime($res->to_date);
							$days = $start->diff($end, true)->days;
							$counts+=$days+1;
						}
						$data[]=array('counts'=>$counts,'leave_type'=>$actual_leave_name,'leave_type_id'=>$leave_type,'available_leave'=>$actual_leave_count,'start_year_date'=>$start_y_d,'end_year_date'=>$end_y_d);
					}
					$apply_start_date=$start_date;
					$apply_end_date=$end_date;
					$leave_array=[];
					if($apply_start_date!='' && $apply_end_date!=''){
						if($actual_leave_name=='Authorised Absence'){
							$first_date_array=$this->return_exact_date_leave($apply_start_date,$data);

							if((strtotime($apply_start_date)  < strtotime($annual_leave_start_date)) && (strtotime($apply_end_date) < strtotime($annual_leave_start_date))){

								$a_counts=1;
								$date_status='<div class="alert alert-info alert-bordered "><span class="text-semibold"></span> '.format_date('d F Y',$apply_start_date).' to '.format_date('d F Y',$apply_end_date).return_count_days($apply_start_date,$apply_end_date).' <span class="text-semibold">'.$authorised_absent.'</span>.</div>';
								$message_status='<div class="alert alert-info alert-bordered ">You are entitled to take '.$authorised_absent.' when you are on Probation period (6 months), which is unpaid.</div>';
								$leave_array[]=array(array('start_date'=>$apply_start_date,'end_date'=>$apply_end_date,'leave_name'=>'Authorised Absence','available_leave_days'=>$first_date_array['counts'],'leave_status_code'=>'AA'));

							}
							else{
								if(($visa_type) == 151 || ($visa_type) == 152 || ($visa_type) == 189  )
									$message_status='<div class="alert alert-info alert-bordered ">You are not entitled to take '.$authorised_absent.' when you are completed Probation period (6 months). Select Annual Leave to continue.</div>';
								else{
									$message_status='<div class="alert alert-info alert-bordered ">You are entitled to take '.$authorised_absent.' which is unpaid.</div>';
									$date_status='<div class="alert alert-info alert-bordered "><span class="text-semibold"></span> '.format_date('d F Y',$apply_start_date).' to '.format_date('d F Y',$apply_end_date).return_count_days($apply_start_date,$apply_end_date).' <span class="text-semibold">'.$authorised_absent.'</span>.</div>';
									$a_counts=1;
									$leave_array[]=array(array('start_date'=>$apply_start_date,'end_date'=>$apply_end_date,'leave_name'=>'Authorised Absence','available_leave_days'=>$first_date_array['counts'],'leave_status_code'=>'AA'));
								}

							}
						}
						else if($actual_leave_name=='Sick Leave' || $actual_leave_name=='Sick Leave Unpaid' || $actual_leave_name=='Maternity Leave'  || $actual_leave_name=='Emergency Leave'  || $actual_leave_name=='Annual Leave' || $actual_leave_name=='Bereavement Leave'){
							if((strtotime($apply_start_date)  < strtotime($leave_start_date)) && (strtotime($apply_end_date) < strtotime($leave_start_date))){
								if($actual_leave_name=='Annual Leave'){
									$message_status='<div class="alert alert-info alert-bordered ">You are entitled for '.$actual_leave_name.' post completion of '.$months.' from DOJ. For now you can apply for Authorized leave which is Unpaid. Click on Apply Now, it automatically takes you to unpaid Authorized Leave.</div>';
								}
								else{
									if($actual_leave_name=='Sick Leave'){
										$message_status='<div class="alert alert-info alert-bordered ">	
										<span class="text-semibold">Sorry!</span> Employees are not entitled to any paid '.$actual_leave_name.' until '.$months.' in the organization from date of joining. Such leaves are marked as SL-UP (Unpaid).</div>';
									}
								}

								if($actual_leave_name=='Sick Leave'){
									$date_status='<div class="alert alert-info alert-bordered ">		 
									 '.format_date('d F Y',$apply_start_date).' to '.format_date('d F Y',$apply_end_date).return_count_days($apply_start_date,$apply_end_date).' <span class="text-semibold">Sick Leave Unpaid</span>.
											 </div>';

									$leave_array[]=array(array('start_date'=>$apply_start_date,'end_date'=>$apply_end_date,'leave_name'=>$actual_leave_name,'leave_status_code'=>'SL-UP','counts'=>1)
									);
									$a_counts=1;
								}
								else{
									$date_status='<div class="alert alert-info alert-bordered ">'.format_date('d F Y',$apply_start_date).' to '.format_date('d F Y',$apply_end_date).return_count_days($apply_start_date,$apply_end_date).' <span class="text-semibold">'.$authorised_absent.'</span>.</div>';

									$leave_array[]=array(array('start_date'=>$apply_start_date,'end_date'=>$apply_end_date,'leave_name'=>'Authorised Absence','leave_status_code'=>'AA'));
									$a_counts=1;
								}
							}
							else{
								if($actual_leave_name=='Annual Leave'){
									if((strtotime($apply_start_date)  >= strtotime($leave_start_date)) && (strtotime($apply_end_date) >= strtotime($leave_start_date))){
										$an_leave_bal=annual_leave_balance($employee_id,$date_of_joining,$apply_start_date);
										$balance_leave=floor($an_leave_bal['balance_leave']);
										$start = new DateTime($apply_start_date);
										$end = new DateTime($apply_end_date);
										$days = ($start->diff($end,true)->days)+1;
										if($balance_leave==0){
											$a_counts=1;
											$date_status='<div class="alert alert-info alert-bordered "><span class="text-semibold"></span> '.format_date('d F Y',$apply_start_date).' to '.format_date('d F Y',$apply_end_date).return_count_days($apply_start_date,$apply_end_date).' <span class="text-semibold">'.$authorised_absent.'</span>.</div>';
											$message_status='<div class="alert alert-info alert-bordered ">
										
										<span class="text-semibold">Sorry!</span> You are not avail to take '.$actual_leave_name.'.There is no balance leave you have.This should be mark it as an '.$authorised_absent.'.</div>';
											$leave_array[]=array(array('start_date'=>$apply_start_date,'end_date'=>$apply_end_date,'leave_name'=>'Authorised Absence','leave_status_code'=>'AA'));
										}
										else if($days <= $balance_leave){
											$a_counts=1;
											$date_status='<div class="alert alert-info alert-bordered ">
		 									<span class="text-semibold"></span>'.format_date('d F Y',$apply_start_date).' to '.format_date('d F Y',$apply_end_date).return_count_days($apply_start_date,$apply_end_date).' <span class="text-semibold">'.$actual_leave_name.'</span></div>';

											//if(strtotime($apply_start_date) > strtotime("2019-06-30") || strtotime($apply_end_date) > strtotime("2019-06-30"))
											
											$message_status='<div class="alert alert-info alert-bordered "><span class="text-semibold"></span> Your annual leave balance is '.$balance_leave.' days.</div>';
										

											//Now remaining is '.($balance_leave-$days).' day/s.
											$leave_array[]=array(array('start_date'=>$apply_start_date,'end_date'=>$apply_end_date,'leave_name'=>$actual_leave_name,'available_leave_days'=>$balance_leave,'leave_status_code'=>'AL')

											);




										}
										else if($days > $balance_leave){
											$a_counts=0;
											$mentioned_leave_end=date('Y-m-d', strtotime("+".($balance_leave-1)." days", strtotime($apply_start_date)));
											$absent_leave_start=date('Y-m-d', strtotime("+1 days", strtotime($mentioned_leave_end)));
											$date_status='<div class="alert alert-info alert-bordered ">'.format_date('d F Y',$apply_start_date).' to '.format_date('d F Y',$mentioned_leave_end).return_count_days($mentioned_leave_end,$apply_start_date).' <span class="text-semibold">'.$actual_leave_name.'</span> <br>'.format_date('d F Y',$absent_leave_start).' to '.format_date('d F Y',$apply_end_date).return_count_days($absent_leave_start,$apply_end_date).' <span class="text-semibold">'.$authorised_absent.'</span>.</div>';
											//if(strtotime($apply_start_date) > strtotime("2019-06-30") || strtotime($apply_end_date) > strtotime("2019-06-30"))
											

											$message_status='<div class="alert alert-info alert-bordered ">
										
										<span class="text-semibold"></span> Your annual leave balance is '.$balance_leave.' days. </div>';
										

											$leave_array[]=array(array('start_date'=>$apply_start_date,'end_date'=>$mentioned_leave_end,'leave_name'=>$actual_leave_name,'available_leave_days'=>$balance_leave,'leave_status_code'=>'AL'),
												array('start_date'=>$absent_leave_start,'end_date'=>$apply_end_date,'leave_name'=>'Authorised Absence','leave_status_code'=>'AA')
											);
										}
									}
									else{
										$message_status='<div class="alert alert-info alert-bordered ">
										
										<span class="text-semibold">Sorry!</span> You are not avail to take '.$actual_leave_name.'.Employee service should be more than or equal to '.$months.'.This leave should be mark it as an Absent.</div>';
										$date_status='<div class="alert alert-info alert-bordered ">'.format_date('d F Y',$apply_start_date).' to '.format_date('d F Y',$apply_end_date).return_count_days($apply_start_date,$apply_end_date).' <span class="text-semibold">'.$authorised_absent.'</span>.</div>';


										$leave_array[]=array(array('start_date'=>$apply_start_date,'end_date'=>$apply_end_date,'leave_name'=>'Authorised Absence','leave_status_code'=>'AA')
										);
									}
								}
								else if($actual_leave_name=='Maternity Leave'){
									$start = new DateTime($apply_start_date);
									$end = new DateTime($apply_end_date);
									$days = ($start->diff($end,true)->days)+1;
									$first_date_array=$this->return_exact_date_leave($apply_start_date,$data);
									$first_array_leave_count=$first_date_array['counts'];
                                    $sick_leave_message = '';
									if(strtotime($apply_start_date) < strtotime($maternity_leave_start_date)){
										$a_counts=1;
										$date_status='<div class="alert alert-info alert-bordered "><span class="text-semibold"></span> '.format_date('d F Y',$apply_start_date).' to '.format_date('d F Y',$apply_end_date).return_count_days($apply_start_date,$apply_end_date).' <span class="text-semibold">'.$actual_leave_name.' Unpaid (ML-UP).</span></div>';
										$message_status='<div class="alert alert-info alert-bordered ">
										
										<span class="text-semibold">Sorry!</span> You are not eligible for '.$actual_leave_name.'.This should be mark it as an unpaid (ML-UP).</div>';
										$leave_array[]=array(array('start_date'=>$apply_start_date,'end_date'=>$apply_end_date,'leave_name'=>$actual_leave_name,'leave_status_code'=>'ML-UP'));
									}
									else{
										if($first_array_leave_count==0){
											$a_counts=1;
											$date_status='<div class="alert alert-info alert-bordered "><span class="text-semibold"></span> '.format_date('d F Y',$apply_start_date).' to '.format_date('d F Y',$apply_end_date).return_count_days($apply_start_date,$apply_end_date).' <span class="text-semibold">'.$actual_leave_name.' Unpaid (ML-UP)</span>.</div>';
											$message_status='<div class="alert alert-info alert-bordered ">
										
										<span class="text-semibold">Sorry!</span> You are not eligible for '.$actual_leave_name.'.There is no balance leave you have.This should be mark it as an Unpaid (ML-UP).</div>';
											$leave_array[]=array(array('start_date'=>$apply_start_date,'end_date'=>$apply_end_date,'leave_name'=>$actual_leave_name,'leave_status_code'=>'ML-UP'));
										}
										else if($days <= $first_array_leave_count){
											$a_counts=1;
											$used_leaves=$actual_leave_count-$first_array_leave_count;
											$used_apply_leaves=$used_leaves+$days;
											if($used_apply_leaves <= MATERNITYLEAVE_PAID){
												$sick_leave_message='<strong class="text-success"> (ML-1)</strong>';
												//Leave Array
												$leave_array[]=array(array('start_date'=>$apply_start_date,'end_date'=>$apply_end_date,'leave_name'=>$actual_leave_name,'available_leave_days'=>$first_array_leave_count,'leave_status_code'=>'ML-1'));
												//Leave  Array

												$date_status='<div class="alert alert-info alert-bordered "><span class="text-semibold"></span>'.format_date('d F Y',$apply_start_date).' to '.format_date('d F Y',$apply_end_date).return_count_days($apply_start_date,$apply_end_date).' <span class="text-semibold">'.$actual_leave_name.' '.$sick_leave_message.'</span>.
			     </div>';
												$message_status='<div class="alert alert-info alert-bordered "><span class="text-semibold"></span> Your maternity leave balance is '.$first_array_leave_count.' days.</div>';

											}
											else if(($used_apply_leaves > MATERNITYLEAVE_PAID)){
												$no_paid_leave_count=($used_apply_leaves-MATERNITYLEAVE_PAID)+$used_leaves;
												$half_paid_leave_count=$used_apply_leaves-$no_paid_leave_count;

												$half_45_finish_date=date('Y-m-d', strtotime("+".($half_paid_leave_count-1)." days", strtotime($apply_start_date)));
												$no_paid_start_date=date('Y-m-d', strtotime("+1 days", strtotime($half_45_finish_date)));
												$sick_leave_message.='<br><strong class="text-success">('.$apply_start_date.' to '.$half_45_finish_date.' is ML-1)</strong>';
												$sick_leave_message.='<br><strong class="text-warning">('.$no_paid_start_date.' to '.$apply_end_date.' is ML-UP)</strong>';
												$start = new DateTime($apply_start_date);
												$end = new DateTime($half_45_finish_date);
												$count_of_days = ($start->diff($end,true)->days)+1;
												$leave_array[]=array(array('start_date'=>$apply_start_date,'end_date'=>$half_45_finish_date,'leave_name'=>$actual_leave_name,'available_leave_days'=>($first_array_leave_count-$count_of_days),'leave_status_code'=>'ML-1'),array('start_date'=>$no_paid_start_date,'end_date'=>$apply_end_date,'leave_name'=>$actual_leave_name,'available_leave_days'=>($first_array_leave_count-($count_of_days)),'leave_status_code'=>'ML-UP'));
												$date_status='<div class="alert alert-info alert-bordered "><span class="text-semibold"></span>'.format_date('d F Y',$apply_start_date).' to '.format_date('d F Y',$apply_end_date).return_count_days($apply_start_date,$apply_end_date).' <span class="text-semibold">'.$actual_leave_name.' '.$sick_leave_message.'</span>.</div>';
												$message_status='<div class="alert alert-info alert-bordered "><span class="text-semibold"></span> Your maternity leave balance is '.$first_array_leave_count-$count_of_days.' days.</div>';
											}
										}
										else if($days > $first_array_leave_count){
											$used_leaves=$actual_leave_count-$first_array_leave_count;
											$used_apply_leaves=$used_leaves+$days;
											$no_paid_leave_count=($used_apply_leaves-MATERNITYLEAVE_PAID)+$used_leaves;
											$authorised_leave_count=$used_apply_leaves-$actual_leave_count;
											$paid_leave_count=$used_apply_leaves-$no_paid_leave_count;
											$unpaid_leave_count=$no_paid_leave_count-$authorised_leave_count;
											$half_45_finish_date=date('Y-m-d', strtotime("+".($paid_leave_count-1)." days", strtotime($apply_start_date)));
											$un_paid_start_date=date('Y-m-d', strtotime("+1 days", strtotime($half_45_finish_date)));
											$un_paid_finish_date=date('Y-m-d', strtotime("+".($unpaid_leave_count-1)." days", strtotime($un_paid_start_date)));
											$sick_leave_message.='<br><strong class="text-success">('.$apply_start_date.' to '.$half_45_finish_date.' is ML-1)</strong>';
											$sick_leave_message.='<br><strong class="text-warning">('.$un_paid_start_date.' to '.$apply_end_date.' is ML-UP)</strong>'; $start = new DateTime($apply_start_date);
											$end = new DateTime($half_45_finish_date);
											$count_of_days = ($start->diff($end,true)->days)+1;
											$start1 = new DateTime($un_paid_start_date);
											$end1 = new DateTime($apply_end_date);
											$count_of_days1 = ($start1->diff($end1,true)->days)+1;
											$leave_array[]=array(array('start_date'=>$apply_start_date,'end_date'=>$half_45_finish_date,'leave_name'=>$actual_leave_name,'available_leave_days'=>($first_array_leave_count-$count_of_days),'leave_status_code'=>'ML-1'),array('start_date'=>$un_paid_start_date,'end_date'=>$apply_end_date,'leave_name'=>$actual_leave_name,'available_leave_days'=>0,'leave_status_code'=>'ML-UP'));
											$date_status='<div class="alert alert-info alert-bordered "><span class="text-semibold"></span>'.format_date('d F Y',$apply_start_date).' to '.format_date('d F Y',$apply_end_date).return_count_days($apply_start_date,$apply_end_date).' <span class="text-semibold">'.$actual_leave_name.' '.$sick_leave_message.'</span>.</div>';
										}
									}
								}
								else if($actual_leave_name=='Sick Leave'){
									$start = new DateTime($apply_start_date);
									$end = new DateTime($apply_end_date);
									$days = ($start->diff($end,true)->days)+1;
									$first_date_array=$this->return_exact_date_leave($apply_start_date,$data);
									$second_array_leave_count=$first_date_array['counts'];
									//
									$a_counts=1;

									$sick_leave_start=$apply_start_date;
									$sick_leave_end=$apply_end_date;
									if($second_array_leave_count==0){
										$date_status='<div class="alert alert-info alert-bordered "><span class="text-semibold"></span> '.format_date('d F Y',$sick_leave_start).' to '.format_date('d F Y',$sick_leave_end).return_count_days($sick_leave_start,$sick_leave_end).' <span class="text-semibold">'.$actual_leave_name.' Unpaid</span>.</div>';
										$message_status='<div class="alert alert-info alert-bordered ">
											
											<span class="text-semibold">Sorry!</span> You are not avail to take '.$actual_leave_name.'.There is no balance leave you have.This should be mark it as an unpaid.</div>';										$leave_array[]=array(array('start_date'=>$sick_leave_start,'end_date'=>$sick_leave_end,'leave_name'=>$actual_leave_name,'leave_status_code'=>'SL-UP','available_leave_days'=>$second_array_leave_count,'counts'=>$a_counts));
									}
									else if($days <= $second_array_leave_count){
										//Sick Leave Message
										$sick_leave_message='';
										$used_leaves=SICKLEAVE_NOPAID-$second_array_leave_count;
										$used_apply_leaves=$used_leaves+$days;
										if($used_apply_leaves <= SICKLEAVE_FULLPAID){
											$sick_leave_message='<strong class="text-success"> (SL-1)</strong>';
											//Leave Array
											$leave_array[]=array(array('start_date'=>$sick_leave_start,'end_date'=>$sick_leave_end,'leave_name'=>$actual_leave_name,'available_leave_days'=>$second_array_leave_count,'leave_status_code'=>'SL-1'));
											//Leave  Array
											$message_status='<div class="alert alert-info alert-bordered ">
											
											You are eligible for full paid Sick Leave . Your sick leave balance is '.(SICKLEAVE_FULLPAID-$used_leaves).'.		 </div>';
										}
										else if(($used_apply_leaves > SICKLEAVE_FULLPAID) && ($used_apply_leaves <= SICKLEAVE_HALFPAID)){
											$half_paid_leave_count=($used_apply_leaves-SICKLEAVE_FULLPAID)+$used_leaves;
											$full_paid_leave_count=$used_apply_leaves-$half_paid_leave_count;
											$first_15_finish_date=date('Y-m-d', strtotime("+".($full_paid_leave_count-1)." days", strtotime($sick_leave_start)));
											$half_30_start_date=date('Y-m-d', strtotime("+1 days", strtotime($first_15_finish_date)));

											if(strtotime($first_15_finish_date) >= strtotime($sick_leave_start)){
												$sick_leave_message.='<br><strong class="text-success">('.$sick_leave_start.' to '.$first_15_finish_date.' is SL-1)</strong>';
												$sick_leave_message.='<br><strong class="text-warning">('.$half_30_start_date.' to '.$sick_leave_end.' is SL-2)</strong>';
												//Leave Salary Array
												$leave_array[]=array(array('start_date'=>$sick_leave_start,'end_date'=>$first_15_finish_date,'leave_name'=>$actual_leave_name,'available_leave_days'=>$second_array_leave_count,'leave_status_code'=>'SL-1'));

												$start = new DateTime($sick_leave_start);
												$end = new DateTime($first_15_finish_date);
												$count_of_days = ($start->diff($end,true)->days)+1;

												$leave_array[]=array(array('start_date'=>$half_30_start_date,'end_date'=>$sick_leave_end,'leave_name'=>$actual_leave_name,'available_leave_days'=>($second_array_leave_count-$count_of_days),'leave_status_code'=>'SL-2'));
												//Leave Salary Array
											}else{
												$sick_leave_message.='<br><strong class="text-warning">('.$sick_leave_start.' to '.$sick_leave_end.' is SL-2)</strong>';
												//Leave Salary Array
												$leave_array[]=array(array('start_date'=>$sick_leave_start,'end_date'=>$sick_leave_end,'leave_name'=>$actual_leave_name,'available_leave_days'=>$second_array_leave_count,'leave_status_code'=>'SL-2'));
												//Leave Salary Array
											}
											$msg_show='No';
											$message_status='<div class="alert alert-info alert-bordered ">
											
											You are eligible for half paid Sick Leave. Your sick leave balance is '.(SICKLEAVE_HALFPAID-$used_leaves).'.</div>';
										}
										else{
											$no_paid_leave_count=($used_apply_leaves-SICKLEAVE_HALFPAID);
											$half_paid_leave_count=($used_apply_leaves-(SICKLEAVE_FULLPAID+$no_paid_leave_count));
											$full_paid_leave_count=$days-($half_paid_leave_count+$no_paid_leave_count);
											$first_15_finish_date=date('Y-m-d', strtotime("+".($full_paid_leave_count-1)." days", strtotime($sick_leave_start)));
											$half_30_start_date=date('Y-m-d', strtotime("+1 days", strtotime($first_15_finish_date)));
											$half_30_finish_date=date('Y-m-d', strtotime("+".($half_paid_leave_count-1)." days", strtotime($half_30_start_date)));

											$no_paid_start_date=date('Y-m-d', strtotime("+1 days", strtotime($half_30_finish_date)));
											if((strtotime($first_15_finish_date) >= strtotime($sick_leave_start))){
												$sick_leave_message.='<br><strong class="text-success">('.$sick_leave_start.' to '.$first_15_finish_date.' is SL-1)</strong>';
												$sick_leave_message.='<br><strong class="text-warning">('.$half_30_start_date.' to '.$half_30_finish_date.' is SL-2)</strong>';
												$sick_leave_message.='<br><strong class="text-danger">('.$no_paid_start_date.' to '.$sick_leave_end.' is SL-UP)</strong>';


												//Leave Salary Array
												$leave_array[]=array(array('start_date'=>$sick_leave_start,'end_date'=>$first_15_finish_date,'leave_name'=>$actual_leave_name,'available_leave_days'=>$second_array_leave_count,'leave_status_code'=>'SL-1'));

												$start = new DateTime($sick_leave_start);
												$end = new DateTime($first_15_finish_date);
												$count_of_days = ($start->diff($end,true)->days)+1;

												$leave_array[]=array(array('start_date'=>$half_30_start_date,'end_date'=>$half_30_finish_date,'leave_name'=>$actual_leave_name,'available_leave_days'=>($second_array_leave_count-$count_of_days),'leave_status_code'=>'SL-2'));


												$start1 = new DateTime($half_30_start_date);
												$end1 = new DateTime($half_30_finish_date);
												$count_of_days1 = ($start1->diff($end1,true)->days)+1;

												$leave_array[]=array(array('start_date'=>$no_paid_start_date,'end_date'=>$sick_leave_end,'leave_name'=>$actual_leave_name,'available_leave_days'=>($second_array_leave_count-$count_of_days)-$count_of_days1,'leave_status_code'=>'SL-UP'));

												//Leave Salary Array





											}else if((strtotime($half_30_finish_date) >= strtotime($sick_leave_start))){
												$sick_leave_message.='<br><strong class="text-warning">('.$sick_leave_start.' to '.$half_30_finish_date.' is SL-2)</strong>';
												$sick_leave_message.='<br><strong class="text-danger">('.$no_paid_start_date.' to '.$sick_leave_end.' is SL-UP)</strong>';

												//Leave Salary Array
												$leave_array[]=array(array('start_date'=>$sick_leave_start,'end_date'=>$half_30_finish_date,'leave_name'=>$actual_leave_name,'available_leave_days'=>$second_array_leave_count,'leave_status_code'=>'SL-2'));

												$start = new DateTime($sick_leave_start);
												$end = new DateTime($half_30_finish_date);
												$count_of_days = ($start->diff($end,true)->days)+1;

												$leave_array[]=array(array('start_date'=>$no_paid_start_date,'end_date'=>$sick_leave_end,'leave_name'=>$actual_leave_name,'available_leave_days'=>($second_array_leave_count-$count_of_days),'leave_status_code'=>'SL-UP'));
												//Leave Salary Array



											}
											else if((strtotime($no_paid_start_date) >= strtotime($sick_leave_start))){
												$sick_leave_message.='<br><strong class="text-danger">('.$no_paid_start_date.' to '.$sick_leave_end.' is SL-UP)</strong>';//Leave //Leave Salary Array
												$leave_array[]=array(array('start_date'=>$no_paid_start_date,'end_date'=>$sick_leave_end,'leave_name'=>$actual_leave_name,'available_leave_days'=>0,'leave_status_code'=>'SL-UP'));
												//Leave Salary Array
											}else{
												$sick_leave_message.='<br><strong class="text-danger">('.$no_paid_start_date.' to '.$sick_leave_end.' is SL-UP)</strong>';//Leave //Leave Salary Array
												$leave_array[]=array(array('start_date'=>$no_paid_start_date,'end_date'=>$sick_leave_end,'leave_name'=>$actual_leave_name,'available_leave_days'=>0,'leave_status_code'=>'SL-UP'));
												//Leave Salary Array
											}
											$msg_show='No';
											$message_status='<div class="alert alert-info alert-bordered ">
											
											You are not eligible to take Sick Leave. This should be mark it as an unpaid(SL-UP).</div>';
										}

										//Sick Leave Message
										$date_status='<div class="alert alert-info alert-bordered "><span class="text-semibold"></span>'.format_date('d F Y',$sick_leave_start).' to '.format_date('d F Y',$sick_leave_end).return_count_days($sick_leave_start,$sick_leave_end).' <span class="text-semibold">'.$actual_leave_name.' '.$sick_leave_message.'</span>.</div>';

									}
									else if($days > $second_array_leave_count){
										$mentioned_leave_end=date('Y-m-d', strtotime("+".($second_array_leave_count-1)." days", strtotime($sick_leave_start)));
										$absent_leave_start=date('Y-m-d', strtotime("+1 days", strtotime($mentioned_leave_end)));
										//Sick Leave Message
										$sick_leave_message='';
										$used_leaves=SICKLEAVE_NOPAID-$second_array_leave_count;
										$used_apply_leaves=$used_leaves+$days;
										if($used_apply_leaves <= SICKLEAVE_FULLPAID){
											$sick_leave_message='<strong class="text-success"> (SL-1)</strong>';
											// Leave Array
											$leave_array[]=array(array('start_date'=>$sick_leave_start,'end_date'=>$mentioned_leave_end,'leave_name'=>$actual_leave_name,'available_leave_days'=>$second_array_leave_count,'leave_status_code'=>'SL-1'));
											// Leave Array
										}else if(($used_apply_leaves > SICKLEAVE_FULLPAID) && ($used_apply_leaves <= SICKLEAVE_HALFPAID)){
											$half_paid_leave_count=($used_apply_leaves-SICKLEAVE_FULLPAID)+$used_leaves;
											$full_paid_leave_count=$used_apply_leaves-$half_paid_leave_count;
											$first_15_finish_date=date('Y-m-d', strtotime("+".($full_paid_leave_count-1)." days", strtotime($sick_leave_start)));
											$half_30_start_date=date('Y-m-d', strtotime("+1 days", strtotime($first_15_finish_date)));
											if(strtotime($first_15_finish_date) >= strtotime($sick_leave_start)){
												$sick_leave_message.='<br><strong class="text-success">('.$sick_leave_start.' to '.$first_15_finish_date.' is SL-1)</strong>';
												$sick_leave_message.='<br><strong class="text-warning">('.$half_30_start_date.' to '.$mentioned_leave_end.' is SL-2)</strong>';

												//Leave Salary Array
												$leave_array[]=array(array('start_date'=>$sick_leave_start,'end_date'=>$first_15_finish_date,'leave_name'=>$actual_leave_name,'available_leave_days'=>$second_array_leave_count,'leave_status_code'=>'SL-1'));

												$start = new DateTime($sick_leave_start);
												$end = new DateTime($first_15_finish_date);
												$count_of_days = ($start->diff($end,true)->days)+1;

												$leave_array[]=array(array('start_date'=>$half_30_start_date,'end_date'=>$mentioned_leave_end,'leave_name'=>$actual_leave_name,'available_leave_days'=>($second_array_leave_count-$count_of_days),'leave_status_code'=>'SL-2'));
												//Leave Salary Array



											}else{
												$sick_leave_message.='<br><strong class="text-warning">('.$sick_leave_start.' to '.$mentioned_leave_end.' is SL-2)</strong>';
												//Leave Salary Array
												$leave_array[]=array(array('start_date'=>$sick_leave_start,'end_date'=>$mentioned_leave_end,'leave_name'=>$actual_leave_name,'available_leave_days'=>$second_array_leave_count,'leave_status_code'=>'SL-2'));
												//Leave Salary Array
											}
										}else{
											$no_paid_leave_count=($used_apply_leaves-SICKLEAVE_HALFPAID);
											$half_paid_leave_count=($used_apply_leaves-(SICKLEAVE_FULLPAID+$no_paid_leave_count));
											$full_paid_leave_count=$days-($half_paid_leave_count+$no_paid_leave_count);
											$first_15_finish_date=date('Y-m-d', strtotime("+".($full_paid_leave_count-1)." days", strtotime($sick_leave_start)));
											$half_30_start_date=date('Y-m-d', strtotime("+1 days", strtotime($first_15_finish_date)));
											$half_30_finish_date=date('Y-m-d', strtotime("+".($half_paid_leave_count-1)." days", strtotime($half_30_start_date)));
											$no_paid_start_date=date('Y-m-d', strtotime("+1 days", strtotime($half_30_finish_date)));
											if((strtotime($first_15_finish_date) >= strtotime($sick_leave_start))){
												$sick_leave_message.='<br><strong class="text-success">('.$sick_leave_start.' to '.$first_15_finish_date.' is SL-1)</strong>';
												$sick_leave_message.='<br><strong class="text-warning">('.$half_30_start_date.' to '.$half_30_finish_date.' is SL-2)</strong>';
												$sick_leave_message.='<br><strong class="text-danger">('.$no_paid_start_date.' to '.$mentioned_leave_end.' is SL-UP)</strong>';
												//Leave Salary Array
												$leave_array[]=array(array('start_date'=>$sick_leave_start,'end_date'=>$first_15_finish_date,'leave_name'=>$actual_leave_name,'available_leave_days'=>$second_array_leave_count,'leave_status_code'=>'SL-1'));

												$start = new DateTime($sick_leave_start);
												$end = new DateTime($first_15_finish_date);
												$count_of_days = ($start->diff($end,true)->days)+1;

												$leave_array[]=array(array('start_date'=>$half_30_start_date,'end_date'=>$half_30_finish_date,'leave_name'=>$actual_leave_name,'available_leave_days'=>($second_array_leave_count-$count_of_days),'leave_status_code'=>'SL-2'));


												$start1 = new DateTime($half_30_start_date);
												$end1 = new DateTime($half_30_finish_date);
												$count_of_days1 = ($start1->diff($end1,true)->days)+1;

												$leave_array[]=array(array('start_date'=>$no_paid_start_date,'end_date'=>$mentioned_leave_end,'leave_name'=>$actual_leave_name,'available_leave_days'=>($second_array_leave_count-$count_of_days)-$count_of_days1,'leave_status_code'=>'SL-UP'));

												//Leave Salary Array



											}else if((strtotime($half_30_finish_date) >= strtotime($sick_leave_start))){
												$sick_leave_message.='<br><strong class="text-warning">('.$sick_leave_start.' to '.$half_30_finish_date.' is SL-2)</strong>';
												$sick_leave_message.='<br><strong class="text-danger">('.$no_paid_start_date.' to '.$mentioned_leave_end.' is SL-UP)</strong>';

												//Leave Salary Array
												$leave_array[]=array(array('start_date'=>$sick_leave_start,'end_date'=>$half_30_finish_date,'leave_name'=>$actual_leave_name,'available_leave_days'=>$second_array_leave_count,'leave_status_code'=>'SL-2'));

												$start = new DateTime($sick_leave_start);
												$end = new DateTime($half_30_finish_date);
												$count_of_days = ($start->diff($end,true)->days)+1;

												$leave_array[]=array(array('start_date'=>$no_paid_start_date,'end_date'=>$mentioned_leave_end,'leave_name'=>$actual_leave_name,'available_leave_days'=>($second_array_leave_count-$count_of_days),'leave_status_code'=>'SL-UP'));
												//Leave Salary Array

											}
											else if((strtotime($no_paid_start_date) >= strtotime($sick_leave_start))){
												$sick_leave_message.='<br><strong class="text-danger">('.$no_paid_start_date.' to '.$mentioned_leave_end.' is SL-UP)</strong>';	//Leave //Leave Salary Array
												$leave_array[]=array(array('start_date'=>$no_paid_start_date,'end_date'=>$mentioned_leave_end,'leave_name'=>$actual_leave_name,'available_leave_days'=>0,'leave_status_code'=>'SL-UP'));
												//Leave Salary Array
											}else{
												$sick_leave_message.='<br><strong class="text-danger">('.$no_paid_start_date.' to '.$mentioned_leave_end.' is SL-UP)</strong>';	//Leave //Leave Salary Array
												$leave_array[]=array(array('start_date'=>$no_paid_start_date,'end_date'=>$mentioned_leave_end,'leave_name'=>$actual_leave_name,'available_leave_days'=>0,'leave_status_code'=>'SL-UP'));
												//Leave Salary Array
											}
										}



										$date_status='<div class="alert alert-info alert-bordered ">'.format_date('d F Y',$sick_leave_start).' to '.format_date('d F Y',$mentioned_leave_end).return_count_days($sick_leave_start,$mentioned_leave_end).' <span class="text-semibold">'.$actual_leave_name.'  '.$sick_leave_message.'</span> <br>'.format_date('d F Y',$absent_leave_start).' to '.format_date('d F Y',$sick_leave_end).return_count_days($absent_leave_start,$sick_leave_end).' <span class="text-semibold">'.$authorised_absent.'</span>.</div>';
										$leave_array[]=array(array('start_date'=>$absent_leave_start,'end_date'=>$sick_leave_end,'leave_name'=>'Authorised Absence','leave_status_code'=>'AA')
										);

										$msg_show='No';
										$message_status='<div class="alert alert-info alert-bordered ">
											
											You are not eligible to take Sick Leave. This should be mark it as an unpaid(SL-UP).</div>';
									}

									//
								}
								else if($actual_leave_name=='Bereavement Leave'){
									////

									$start = new DateTime($apply_start_date);
									$end = new DateTime($apply_end_date);
									$days = ($start->diff($end,true)->days)+1;
									$first_date_array=$this->return_exact_date_leave($apply_start_date,$data);
									$first_array_leave_count=$first_date_array['counts'];
									if($first_array_leave_count==0){
										$a_counts=0;
										$message_status='<div class="alert alert-info alert-bordered ">										
										<span class="text-semibold">Sorry!</span> You are not eligible for '.$actual_leave_name.'.There is no balance leave you have. Select annual leave to go. </div>';
									}
									else if($days <= $first_array_leave_count){
										$a_counts=1;
										$used_leaves=$actual_leave_count-$first_array_leave_count;
										$used_apply_leaves=$used_leaves+$days;

										$leave_message='<strong class="text-success"> (BL)</strong>';
										//Leave Array
										$leave_array[]=array(array('start_date'=>$apply_start_date,'end_date'=>$apply_end_date,'leave_name'=>$actual_leave_name,'available_leave_days'=>$first_array_leave_count,'leave_status_code'=>'BL'));
										//Leave  Array

										$date_status='<div class="alert alert-info alert-bordered "><span class="text-semibold"></span>'.format_date('d F Y',$apply_start_date).' to '.format_date('d F Y',$apply_end_date).return_count_days($apply_start_date,$apply_end_date).' <span class="text-semibold">'.$actual_leave_name.' '.$leave_message.'</span>.  </div>';
										$message_status='<div class="alert alert-info alert-bordered "><span class="text-semibold"></span> Your '.$actual_leave_name.' balance is '.$first_array_leave_count.' day.</div>';

									}
									else if($days > $first_array_leave_count){
										$first_array_leave_count;
										$used_leaves=$actual_leave_count-$first_array_leave_count;
										$used_apply_leaves=$used_leaves+$days;

										$bl_paid_leave_count=abs($first_array_leave_count-$used_leaves);

										$other_leave_count=$used_apply_leaves-$actual_leave_count;


										$al_leave_start_date=date('Y-m-d', strtotime("+".$bl_paid_leave_count." days", strtotime($apply_start_date)));
										
										$apply_bl_date_end = date('Y-m-d', strtotime("-1 days", strtotime($al_leave_start_date)));;
										
										
										$an_leave_bal=annual_leave_balance($employee_id,$date_of_joining,$al_leave_start_date);
										$balance_leave=floor($an_leave_bal['balance_leave']);
										$start = new DateTime($al_leave_start_date);
										$end = new DateTime($apply_end_date);
										$days = ($start->diff($end,true)->days)+1;
										if($balance_leave==0){ 
											$a_counts=1;
											$date_status='<div class="alert alert-info alert-bordered ">'.format_date('d F Y',$apply_start_date).' to '.format_date('d F Y',$apply_bl_date_end).return_count_days($apply_start_date,$apply_bl_date_end).' <span class="text-semibold">'.$actual_leave_name.'</span>.<br>'.format_date('d F Y',$al_leave_start_date).' to '.format_date('d F Y',$apply_end_date).return_count_days($al_leave_start_date,$apply_end_date).' <span class="text-semibold">'.$authorised_absent.'</span>.</div>';

											$leave_array[]=array(array('start_date'=>$apply_start_date,'end_date'=>$apply_bl_date_end,'leave_name'=>$actual_leave_name,'leave_status_code'=>'BL'),array('start_date'=>$al_leave_start_date,'end_date'=>$apply_end_date,'leave_name'=>$authorised_absent,'leave_status_code'=>'AA'));
										}
										else if($days <= $balance_leave){
											$a_counts=1;
											$date_status='<div class="alert alert-info alert-bordered ">'.format_date('d F Y',$apply_start_date).' to '.format_date('d F Y',$apply_bl_date_end).return_count_days($apply_start_date,$apply_bl_date_end).' <span class="text-semibold">'.$actual_leave_name.'</span>.<br>'.format_date('d F Y',$al_leave_start_date).' to '.format_date('d F Y',$apply_end_date).return_count_days($al_leave_start_date,$apply_end_date).' <span class="text-semibold">Annual Leave</span></div>';

											//Now remaining is '.($balance_leave-$days).' day/s.
											$leave_array[]=array(array('start_date'=>$apply_start_date,'end_date'=>$apply_bl_date_end,'leave_name'=>$actual_leave_name,'leave_status_code'=>'BL'),array('start_date'=>$al_leave_start_date,'end_date'=>$apply_end_date,'leave_name'=>'Annual Leave','available_leave_days'=>$balance_leave,'leave_status_code'=>'AL'));
										}
										else if($days > $balance_leave){
											$a_counts=0;
											$mentioned_leave_end=date('Y-m-d', strtotime("+".($balance_leave-1)." days", strtotime($al_leave_start_date)));
											$absent_leave_start=date('Y-m-d', strtotime("+1 days", strtotime($mentioned_leave_end)));
											$date_status='<div class="alert alert-info alert-bordered ">'.format_date('d F Y',$apply_start_date).' to '.format_date('d F Y',$apply_start_date).return_count_days($apply_start_date,$apply_start_date).' <span class="text-semibold">'.$actual_leave_name.'</span> <br>'.format_date('d F Y',$al_leave_start_date).' to '.format_date('d F Y',$mentioned_leave_end).return_count_days($mentioned_leave_end,$al_leave_start_date).' <span class="text-semibold">Annual Leave</span> <br>'.format_date('d F Y',$absent_leave_start).' to '.format_date('d F Y',$apply_end_date).return_count_days($absent_leave_start,$apply_end_date).' <span class="text-semibold">'.$authorised_absent.'</span>. </div>';

											$leave_array[]=array(array('start_date'=>$apply_start_date,'end_date'=>$apply_start_date,'leave_name'=>$actual_leave_name,'leave_status_code'=>'BL'),array('start_date'=>$al_leave_start_date,'end_date'=>$mentioned_leave_end,'leave_name'=>'Annual Leave','available_leave_days'=>$balance_leave,'leave_status_code'=>'AL'),
												array('start_date'=>$absent_leave_start,'end_date'=>$apply_end_date,'leave_name'=>'Authorised Absence','leave_status_code'=>'AA')
											);
										}

									}


									////
								}
								else{
									$a_counts=1;
									$start = new DateTime($apply_start_date);
									$end = new DateTime($apply_end_date);
									$days = ($start->diff($end,true)->days)+1;
									$first_date_array=$this->return_exact_date_leave($apply_start_date,$data);
									$first_array_leave_count=$first_date_array['counts'];

									if($actual_leave_name=='Emergency Leave'){
										$leave_status_code='EL';
									}else{
										$leave_status_code='AA';
									}

									$leave_array[]=array(array('start_date'=>$apply_start_date,'end_date'=>$apply_end_date,'leave_name'=>$actual_leave_name,'available_leave_days'=>$first_array_leave_count,'leave_status_code'=>$leave_status_code));
									$message_status='<div class="alert alert-info alert-bordered ">
										
										You are entitled to take '.$actual_leave_name.'.</div>';
									$date_status='<div class="alert alert-info alert-bordered ">
		 <span class="text-semibold"></span> '.format_date('d F Y',$apply_start_date).' to '.format_date('d F Y',$apply_end_date).return_count_days($apply_start_date,$apply_end_date).' <span class="text-semibold">'.$actual_leave_name.'</span>.
			     </div>';

								}
							}

						}
					}
					echo json_encode(array('date_status'=>$date_status,'message_status'=>$message_status,'disable_status'=>$disable_status,'leave_array'=>json_encode($leave_array),'counts'=>$a_counts,'msg_show'=>$msg_show));
				}	//dob end
				//Counts Of Leaves
				else{
					$message_status='<div class="alert alert-info alert-bordered "><span class="text-semibold">Sorry!</span> Employee\'s Date of joining is not in our database. Contact HR Administartor.</div>';
					echo json_encode(array('date_status'=>'','message_status'=>$message_status,'disable_status'=>0,'leave_array'=>'','counts'=>0));
				}
			}
		}


	}
	/*check_leave_availability end*/


	public function add_attendance() {
		if($this->input->post('add_type')=='attendance') {
			$Return = array('result'=>'', 'error'=>'');
			/* Server side PHP input validation */
			if($this->input->post('attendance_date_m')==='') {
				$Return['error'] = "The attendance date field is required.";
			} else if($this->input->post('clock_in_m')==='') {
				$Return['error'] = "The office In Time field is required.";
			} else if($this->input->post('clock_out_m')==='') {
				$Return['error'] = "The office Out Time field is required.";
			}

			if($Return['error']!=''){
				$this->output($Return);
			}

			$attendance_date = $this->input->post('attendance_date_m');
			$user_id=$this->input->post('employee_id_m');
			$clock_in = $this->input->post('clock_in_m');
			$clock_out = $this->input->post('clock_out_m');

			$clock_in2 = $attendance_date.' '.$clock_in.':00';
			$clock_out2 = $attendance_date.' '.$clock_out.':00';

			//total work
			$total_work_cin =  new DateTime($clock_in2);
			$total_work_cout =  new DateTime($clock_out2);

			$interval_cin = $total_work_cout->diff($total_work_cin);
			$hours_in   = $interval_cin->format('%h');
			$minutes_in = $interval_cin->format('%i');
			$total_work = $hours_in .":".$minutes_in;
			$emp_biometri_id=$this->Employees_model->get_bioidbyuser_id($user_id);

			if($emp_biometri_id!=''){
				$timesheet_cal=timesheets_calc($emp_biometri_id,$clock_in2,$clock_out2,$user_id,$attendance_date);
				$data = array(
                        'employee_id' => $this->input->post('employee_id_m'),
                        'attendance_date' => $attendance_date,
                        'clock_in' => $clock_in2,
                        'clock_out' => $clock_out2,
                        'time_late' => $timesheet_cal['time_late'],
                        'total_work' => $timesheet_cal['total_work_s'],
                        'early_leaving' => $timesheet_cal['early_leaving'],
                        'overtime' => $timesheet_cal['overtime'],
                        'total_rest' => $timesheet_cal['total_rest'],
                        'attendance_status' => 'Present',
                        'clock_in_out' => '0'
				);

				$check_if_already=$this->Employees_model->check_if_already_insert($this->input->post('employee_id_m'),$attendance_date);
				if($check_if_already==0){
					$result = $this->Timesheet_model->add_employee_attendance($data);
					if ($result == TRUE) {
						$Return['result'] = 'Employee Attendance added.';
					} else {
						$Return['error'] = 'Bug. Something went wrong, please try again.';
					}
				}else{
					$Return['error'] = 'Bug. Attendance already added in this date.';

				}
			}
			else{
				$Return['error'] = 'Bug. Biometric id not found for this user.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function add_holiday() {
		if($this->input->post('add_type')=='holiday') {
			$Return = array('result'=>'', 'error'=>'');
			$description = $this->input->post('description');

			$select_date_count = count($this->input->post('select_date'));

			$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
			/*Server side PHP input validation*/
			if($this->input->post('event_name')==='') {
				$Return['error'] = "The event name field is required.";
			}  else if($this->input->post('country')==='') {
				$Return['error'] = "The Country field is required.";
			}  else if($this->input->post('description')==='') {
				$Return['error'] = "The description field is required.";
			}


			$select_date=$this->input->post('select_date');


			$dept_id_s=[];
			for($i=0;$i<$select_date_count;$i++){
				if($i==0){
					$department_ids=$this->input->post('department_id');
					$start_date=explode('-',$select_date[$i]);
					foreach($department_ids as $dept_id){
						$dept_id_s[]=array('department_id'=>$dept_id,'start_date'=>format_date('Y-m-d',$start_date[0]),'end_date'=>format_date('Y-m-d',$start_date[1]));
					}

				}else{
					$department_ids=$this->input->post("department_id_".$i);
					$start_date1=explode('-',$select_date[$i]);
					foreach($department_ids as $dept_id){
						$dept_id_s[]=array('department_id'=>$dept_id,'start_date'=>format_date('Y-m-d',$start_date1[0]),'end_date'=>format_date('Y-m-d',$start_date1[1]));
					}
				}

			}

			$check_unique_array_values=$this->has_dupes($dept_id_s);
			if($check_unique_array_values==1) {
				$Return['error'] = "Department id should be unique.";
			}



			if($Return['error']!=''){
				$this->output($Return);
			}
			$country_id=$this->input->post('country');

			$rand_val=rand();
			foreach($dept_id_s as $insert_val){

				$data = array(
					'event_name' => $this->input->post('event_name'),
					'description' => $qt_description,
					'unique_id' => $rand_val,
					'country_id' => $country_id,
					'department_id' => $insert_val['department_id'],
					'start_date' => $insert_val['start_date'],
					'end_date' => $insert_val['end_date'],
					'is_publish' => $this->input->post('is_publish'),
					'created_at' => date('Y-m-d H:i:s')
				);
				$result = $this->Timesheet_model->add_holiday_record($data);

				/*User Logs*/
				$affected_id= table_max_id('xin_holidays','holiday_id');
				userlogs('Timesheet-Holiday-Add','Public Holiday Added',$affected_id['field_id'],$affected_id['datas']);
				/*User Logs*/


			}
			if ($result == TRUE) {
				$Return['result'] = 'Holiday added.';
				$setting = $this->Xin_model->read_setting_info(1);
				if($setting[0]->enable_email_notification == 'yes') {
					if($this->input->post('is_publish') == 1){
						$this->email->set_mailtype("html");
						$cinfo = $this->Xin_model->read_company_setting_info(1);
						//get email template
						$template = $this->Xin_model->read_email_template(17);
						//get employee info

						foreach($dept_id_s as $insert_val){
							$holiday = $this->Timesheet_model->read_holiday_all_information($affected_id['field_id']);
							$holiday_name=$holiday[0]['event_name'];
							$holiday_content=htmlspecialchars_decode(stripslashes($holiday[0]['description']));
							$holiday_start_date=$insert_val['start_date'];
							$holiday_end_date=$insert_val['end_date'];

							$user_info = $this->Xin_model->read_user_info_bydepartment($insert_val['department_id'],$country_id);
							if($user_info){
								foreach($user_info as $user_det){

									$full_name = change_fletter_caps($user_det->first_name.' '.$user_det->middle_name.' '.$user_det->last_name);
									$dept=$user_det->deparment_name;
									$from_date = $this->Xin_model->set_date_format($holiday_start_date);
									$to_date = $this->Xin_model->set_date_format($holiday_end_date);

									$subject = $template[0]->subject.' - '.$cinfo[0]->company_name.' - '.$holiday_name;


									$message = '<div style="background:#f7eaea;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin:0 auto;padding:20px;max-width:65em;border:2px solid #D40732;">
			<br>'.str_replace(array("{var employee_name}","{var site_name}","{var site_url}","{var description}"),array($full_name,$cinfo[0]->company_name,site_url(),$holiday_content),htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';

									if(TESTING_MAIL==TRUE){
										$this->email->from(FROM_MAIL, $cinfo[0]->company_name);
										$this->email->to(TO_MAIL);
									}else{
										$this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
										$this->email->to($user_det->email);
									}

									$this->email->subject($subject);
									$this->email->message($message);
									//$this->email->send();
									//die;
								}
							}
						}
					}
				}
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function add_ramadan_schedule() {
		if($this->input->post('add_type')=='ramadan_schedule') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');
			$description = $this->input->post('description');

			$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
			/*Server side PHP input validation*/
			if($this->input->post('event_name')==='') {
				$Return['error'] = "The event name field is required.";
			}  else if($this->input->post('country')==='') {
				$Return['error'] = "The Country field is required.";
			}  else if($this->input->post('select_date')==='') {
				$Return['error'] = "The Date field is required.";
			}  else if($this->input->post('reduced_hours')==='') {
				$Return['error'] = "The reduced hours field is required.";
			}

			$select_date=explode('-',$this->input->post('select_date'));
			$start_date=format_date('Y-m-d',$select_date[0]);
			$end_date=format_date('Y-m-d',$select_date[1]);
			$country_id=$this->input->post('country');
			$reduced_hours=$this->input->post('reduced_hours');


			if($Return['error']!=''){
				$this->output($Return);
			}
			$data = array(
				'event_name' => $this->input->post('event_name'),
				'description' => $qt_description,
				'country_id' => $country_id,
				'reduced_hours' => $reduced_hours,
				'start_date' => $start_date,
				'end_date' => $end_date,
				'is_publish' => $this->input->post('is_publish'),
				'created_by' => $this->userSession['user_id'],
				'created_at' => date('Y-m-d H:i:s')
			);

			$result = $this->Timesheet_model->add_ramadan_schedule($data);

			/*User Logs*/
			$affected_id= table_max_id('xin_ramadan_schedule','ramadan_schedule_id');
			userlogs('Timesheet-Exceptional Schedule-Add','Exceptional Schedule Added',$affected_id['field_id'],$affected_id['datas']);
			/*User Logs*/

			if ($result == TRUE) {
				$Return['result'] = 'Exceptional Schedule added.';
				$setting = $this->Xin_model->read_setting_info(1);
				if($setting[0]->enable_email_notification == 'yes') {
					if($this->input->post('is_publish') == 1){

						$this->email->set_mailtype("html");
						$cinfo = $this->Xin_model->read_company_setting_info(1);
						//get email template
						$template = $this->Xin_model->read_email_template(17);
						//get employee info
						$event_name=$this->input->post('event_name');
						$event_content=htmlspecialchars_decode(stripslashes($qt_description));
						$event_start_date=$start_date;
						$event_end_date=$end_date;
						$reduced_hours=$reduced_hours;
						$user_info = $this->Xin_model->read_user_info_bycountry($country_id);
						if($user_info){
							foreach($user_info as $user_det){
								$full_name = change_fletter_caps($user_det->first_name.' '.$user_det->middle_name.' '.$user_det->last_name);
								$dept=$user_det->deparment_name;
								$from_date = $this->Xin_model->set_date_format($event_start_date);
								$to_date = $this->Xin_model->set_date_format($event_end_date);

								$subject = 'Exceptional Schedule Announcement'.' - '.$cinfo[0]->company_name.' - '.$event_name;

								$event_content.= $from_date. ' to '.$to_date.' Shift hours reduced by '.$reduced_hours.' hour/s';
								$message = '
			<div style="background:#f7eaea;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin:0 auto;padding:20px;max-width:65em;border:2px solid #D40732;">
			<br>'.str_replace(array("{var employee_name}","{var site_name}","{var site_url}","{var description}"),array($full_name,$cinfo[0]->company_name,site_url(),$event_content),htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';

								// print_r($message);
								// die;
								if(TESTING_MAIL==TRUE){
									$this->email->from(FROM_MAIL, $cinfo[0]->company_name);
									$this->email->to(TO_MAIL);
								}else{
									$this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
									$this->email->to($user_det->email);
								}
								$this->email->subject($subject);
								$this->email->message($message);
								//$this->email->send();
								//die;
							}
						}
					}
				}
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}

			$this->output($Return);
			exit;
		}
	}

	public function edit_ramadan() {
		if($this->input->post('edit_type')=='ramadan') {
			$id = $this->uri->segment(3);
			$Return = array('result'=>'', 'error'=>'');
			$description = $this->input->post('description');

			$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
			/*Server side PHP input validation*/
			if($this->input->post('event_name')==='') {
				$Return['error'] = "The event name field is required.";
			}  else if($this->input->post('country')==='') {
				$Return['error'] = "The Country field is required.";
			}  else if($this->input->post('select_date')==='') {
				$Return['error'] = "The Date field is required.";
			}  else if($this->input->post('reduced_hours')==='') {
				$Return['error'] = "The reduced hours field is required.";
			}

			$select_date=explode('-',$this->input->post('select_date'));
			$start_date=format_date('Y-m-d',$select_date[0]);
			$end_date=format_date('Y-m-d',$select_date[1]);
			$country_id=$this->input->post('country');
			$reduced_hours=$this->input->post('reduced_hours');

			if($Return['error']!=''){
				$this->output($Return);
			}

			$data = array(
				'event_name' => $this->input->post('event_name'),
				'description' => $qt_description,
				'reduced_hours' => $reduced_hours,
				'start_date' => $start_date,
				'end_date' => $end_date,
				'is_publish' => $this->input->post('is_publish'),
				'created_by' => $this->userSession['user_id'],
				'created_at' => date('Y-m-d H:i:s')
			);

			$result = $this->Timesheet_model->update_ramadan_schedule($data,$id);
			/*User Logs*/
			$affected_id= table_update_id('xin_ramadan_schedule','ramadan_schedule_id',$id);
			userlogs('Timesheet-Exceptional Schedule-Update','Exceptional Schedule Updated',$affected_id['field_id'],$affected_id['datas']);
			/*User Logs*/

			if ($result == TRUE) {
				$Return['result'] = 'Exceptional Schedule updated.';
				$setting = $this->Xin_model->read_setting_info(1);
				if($setting[0]->enable_email_notification == 'yes') {
					if($this->input->post('is_publish') == 1){

						$this->email->set_mailtype("html");
						$cinfo = $this->Xin_model->read_company_setting_info(1);
						//get email template
						$template = $this->Xin_model->read_email_template(17);
						//get employee info

						$event_name=$this->input->post('event_name');
						$event_content=htmlspecialchars_decode(stripslashes($qt_description));
						$event_start_date=$start_date;
						$event_end_date=$end_date;
						$reduced_hours=$reduced_hours;

						$user_info = $this->Xin_model->read_user_info_bycountry($country_id);

						if($user_info){
							foreach($user_info as $user_det){

								$full_name = change_fletter_caps($user_det->first_name.' '.$user_det->middle_name.' '.$user_det->last_name);
								$dept=$user_det->deparment_name;
								$from_date = $this->Xin_model->set_date_format($event_start_date);
								$to_date = $this->Xin_model->set_date_format($event_end_date);

								$subject = 'Exceptional Schedule Announcement'.' - '.$cinfo[0]->company_name.' - '.$event_name;

								$event_content.= $from_date. ' to '.$to_date.' Shift hours reduced by '.$reduced_hours.' hour/s';
								$message = '<div style="background:#f7eaea;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin:0 auto;padding:20px;max-width:65em;border:2px solid #D40732;">
			<br>'.str_replace(array("{var employee_name}","{var site_name}","{var site_url}","{var description}"),array($full_name,$cinfo[0]->company_name,site_url(),$event_content),htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';


								if(TESTING_MAIL==TRUE){
									$this->email->from(FROM_MAIL, $cinfo[0]->company_name);
									$this->email->to(TO_MAIL);
								}else{
									$this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
									$this->email->to($user_det->email);
								}

								$this->email->subject($subject);
								$this->email->message($message);
								//$this->email->send();
								//die;

							}
						}
					}
				}
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}

			$this->output($Return);
			exit;
		}
	}

	public function has_dupes($array) {
		$dupe_array = array();
		foreach ($array as $val) {
			if (++$dupe_array[$val['department_id']] > 1) {
				return true;
			}
		}
		return false;
	}

	public function edit_holiday() {
		if($this->input->post('edit_type')=='holiday') {
			$id = $this->uri->segment(3);
			$Return = array('result'=>'', 'error'=>'');
			$description = $this->input->post('description');

			$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);

			/* Server side PHP input validation */
			if($this->input->post('event_name')==='') {
				$Return['error'] = "The event name field is required.";
			}  else if($this->input->post('description')==='') {
				$Return['error'] = "The description field is required.";
			}

			if($Return['error']!=''){
				$this->output($Return);
			}

			$dept_id_s=$this->input->post('department_id');
			$select_date=$this->input->post('select_date');
			$country_id=$this->input->post('country');
			$i=0;
			foreach($dept_id_s as $insert_val){
				$start_date=explode('-',$select_date[$i]);
				$data = array(
					'event_name' => $this->input->post('event_name'),
					'description' => $qt_description,
					'department_id' => $dept_id_s[$i],
					'start_date' => format_date('Y-m-d',$start_date[0]),
					'end_date' => format_date('Y-m-d',$start_date[1]),
					'is_publish' => $this->input->post('is_publish'),
				);
				$result = $this->Timesheet_model->update_holiday_record($data,$id,$dept_id_s[$i]);
				/*User Logs*/
				$affected_id= table_update_id('xin_holidays','holiday_id',$id);
				userlogs('Timesheet-Holiday-Update','Public Holiday Updated',$dept_id_s[$i],$affected_id['datas']);
				/*User Logs*/
				$i++;

			}
			if ($result == TRUE) {
				$Return['result'] = 'Holiday updated.';
				$setting = $this->Xin_model->read_setting_info(1);
				if($setting[0]->enable_email_notification == 'yes') {
					if($this->input->post('is_publish') == 1){
						$this->email->set_mailtype("html");
						$cinfo = $this->Xin_model->read_company_setting_info(1);
						//get email template
						$template = $this->Xin_model->read_email_template(17);
						//get employee info
						$i=0;
						foreach($dept_id_s as $insert_val){
							$start_date=explode('-',$select_date[$i]);
							//$holiday = $this->Timesheet_model->read_holiday_all_information($affected_id['field_id']);
							$holiday_name=$this->input->post('event_name');
							$holiday_content=htmlspecialchars_decode(stripslashes($qt_description));
							$holiday_start_date=$start_date[0];
							$holiday_end_date=$start_date[1];

							$user_info = $this->Xin_model->read_user_info_bydepartment($dept_id_s[$i],$country_id);
							if($user_info){
								foreach($user_info as $user_det){

									$full_name = change_fletter_caps($user_det->first_name.' '.$user_det->middle_name.' '.$user_det->last_name);
									$dept=$user_det->deparment_name;
									$from_date = $this->Xin_model->set_date_format($holiday_start_date);
									$to_date = $this->Xin_model->set_date_format($holiday_end_date);

									$subject = $template[0]->subject.' - '.$cinfo[0]->company_name.' - '.$holiday_name;

									$message = '
			<div style="background:#f7eaea;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin:0 auto;padding:20px;max-width:65em;border:2px solid #D40732;">
			<br>'.str_replace(array("{var employee_name}","{var site_name}","{var site_url}","{var description}"),array($full_name,$cinfo[0]->company_name,site_url(),$holiday_content),htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';


									if(TESTING_MAIL==TRUE){
										$this->email->from(FROM_MAIL, $cinfo[0]->company_name);
										$this->email->to(TO_MAIL);
									}else{
										$this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
										$this->email->to($user_det->email);
									}

									$this->email->subject($subject);
									$this->email->message($message);
									//$this->email->send();
									$i++;
								}
							}
						}
					}
				}
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function edit_leave() {
		if($this->input->post('edit_type')=='leave') {
			$id = $this->uri->segment(3);
			$Return = array('result'=>'', 'error'=>'');

			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');
			$remarks = $this->input->post('remarks');

			$st_date = strtotime($start_date);
			$ed_date = strtotime($end_date);
			$qt_remarks = htmlspecialchars(addslashes($remarks), ENT_QUOTES);
			$fname = [];
			/* Server side PHP input validation */
			if($this->input->post('leave_type')==='') {
				$Return['error'] = "The leave type field is required.";
			} else if($this->input->post('start_date')==='') {
				$Return['error'] = "The start date field is required.";
			} else if($this->input->post('end_date')==='') {
				$Return['error'] = "The end date field is required.";
			} else if($st_date > $ed_date) {
				$Return['error'] = "Start Date should be less than or equal to End Date.";
			} else if($this->input->post('employee_id')==='') {
				$Return['error'] = "The employee field is required.";
			} else if($this->input->post('reason')==='') {
				$Return['error'] = "The leave reason field is required.";
			} else if($_FILES['document_file']['size'] != 0) {
				if(is_uploaded_file($_FILES['document_file']['tmp_name'])) {
					//checking image type
					$allowed =  array('png','jpg','jpeg','pdf','gif','txt','pdf','xls','xlsx','doc','docx');
					$filename = $_FILES['document_file']['name'];
					$ext = pathinfo($filename, PATHINFO_EXTENSION);

					if(in_array($ext,$allowed)){
						$tmp_name = $_FILES["document_file"]["tmp_name"];
						$documentd = "uploads/leavedocument/";
						$name = basename($_FILES["document_file"]["name"]);
						$newfilename = 'document_'.round(microtime(true)).'.'.$ext;
						move_uploaded_file($tmp_name, $documentd.$newfilename);
						$fname = $newfilename;
					} else {
						$Return['error'] = $this->lang->line('xin_employee_document_file_type');
					}
				}
			}

			if($Return['error']!=''){
				$this->output($Return);
			}

			$data = array(
				'employee_id' => $this->input->post('employee_id'),
				'leave_type_id' => $this->input->post('leave_type'),
				'from_date' => format_date('Y-m-d',$this->input->post('start_date')),
				'to_date' => format_date('Y-m-d',$this->input->post('end_date')),
				'reason' => $this->input->post('reason'),
				'remarks' => $qt_remarks
			);

			if($fname!=''){
				$data+=array('documentfile'=>$fname);
			}
			$result = $this->Timesheet_model->update_leave_record($data,$id);

			/*User Logs*/
			$affected_id= table_update_id('xin_leave_applications','leave_id',$id);
			userlogs('Timesheet-Employee Leave-Update','Employee Leave Updated',$id,$affected_id['datas']);
			/*User Logs*/

			if ($result == TRUE) {
				$Return['result'] = 'Leave updated.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function edit_leave_employee(){

		// echo "string";die;		
		if($this->input->post('edit_type')=='leave') {

			$id = $this->input->post('leave_id');

			$Return = array('result'=>'', 'error'=>'', 'message'=>'');
			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');
			$leave_type=$this->input->post('leave_type');
			$applied_on=$this->input->post('applied_on');
			$employee_id=$this->input->post('employee_id');
			$user_info=$this->Xin_model->read_user_info($employee_id);
			$location_name=strtolower($this->Timesheet_model->get_location_name($employee_id));

			$mandatory_f=$this->input->post('mandatory_f');
			$department = $this->Department_model->read_department_information($user_info[0]->department_id);
			$department_name=$department[0]->department_name;

			/*Actual Leaves*/
			$actual_leave_available=$this->Timesheet_model->read_leave_type_information($leave_type);
			$actual_leave_name=$actual_leave_available[0]->type_name;
			$actual_leave_count=$actual_leave_available[0]->days_per_year;
			/*Actual Leaves*/

			$st_date = strtotime($start_date);
			$ed_date = strtotime($end_date);

			$has_reporting_manager=$this->Timesheet_model->has_reporting_manager($employee_id);
			/* Server side PHP input validation */

			// if($this->input->post('leave_type')==='') {
			// 	$Return['error'] = "The leave type field is required.";
			// } else if($this->input->post('start_date')==='') {
			// 	$Return['error'] = "The start date field is required.";
			// } else if($this->input->post('end_date')==='') {
			// 	$Return['error'] = "The end date field is required.";
			// } else if($st_date > $ed_date) {
			// 	$Return['error'] = "Start Date should be less than or equal to End Date.";
			// } else if($employee_id==='') {
			// 	$Return['error'] = "The employee field is required.";
			// } else if($has_reporting_manager=='0') {
			// 	$Return['error'] = "There is no reporting manager assigned to this employer.";
			// } else if($this->input->post('reason')==='') {
			// 	if($actual_leave_name=='Authorised Absence' || $actual_leave_name=='Emergency Leave'){
			// 		$Return['error'] = "The leave reason field is required.";
			// 	}
			// }

			if($actual_leave_name=='Sick Leave' && $mandatory_f==1) {
				// && strtoupper(($department_name)!='WH')
				if($_FILES['document_file']['size'][0]== 0) {
					$Return['error'] = "For sick leaves you should upload the medical documents";
				}
			}

			$get_shifts_bydep_loc=$this->Employees_model->get_shifts_bydep_loc($employee_id,$user_info[0]->office_location_id,$user_info[0]->department_id,$start_date);
			$week_off_count=count(explode(',',$get_shifts_bydep_loc['week_off']));
			//$location_name=='jlt'
			if(($week_off_count > 1) && $start_date!='' && $end_date!='' && (strtotime($start_date) >=strtotime(ANNUAL_LEAVE_POLICY_STARTDATE))){
				$country=$this->Location_model->read_location_information($user_info[0]->office_location_id);
				$country_id=$country[0]->country;
				//check leave one day before leave start day
				$check_pre_date=date('Y-m-d', strtotime("-1 days",strtotime($start_date)));
				$check_pre_day = date('l',strtotime($check_pre_date));
				$shift_data=$this->Employees_model->get_shifts_bydep_loc($employee_id,$user_info[0]->office_location_id,$user_info[0]->department_id,$check_pre_date);
				$weak_off_dates=explode(',',$shift_data['week_off']);
				$public_holiday=$this->Employees_model->get_public_holiday($employee_id,$country_id,$user_info[0]->department_id,$check_pre_date);
				//check leave one day before leave start day
				//check leave one day after leave start day
				$check_post_date=date('Y-m-d', strtotime("+1 days",strtotime($end_date)));
				$check_post_day = date('l',strtotime($check_post_date));
				$shift_data_post=$this->Employees_model->get_shifts_bydep_loc($employee_id,$user_info[0]->office_location_id,$user_info[0]->department_id,$check_post_date);
				$weak_off_dates_post=explode(',',$shift_data_post['week_off']);
				$public_holiday_post=$this->Employees_model->get_public_holiday($employee_id,$country_id,$user_info[0]->department_id,$check_post_date);
				//check leave one day after leave start day

				$next_date=$this->bring_prev_next_date($check_post_date,'Next',$user_info[0]->office_location_id,$user_info[0]->department_id,$country_id,$user_info[0]->user_id);
				
				if(in_array($check_post_day,$weak_off_dates_post) && ($actual_leave_name!='Sick Leave')){
					$Return['error'] = "Your Leave end date might be '".$next_date." along with weekend";
				}
                
			}

			/*Get HR Administror Mails BY Location Wise*/
			// $hr_mails=get_hr_mail_bylocation($user_info[0]->office_location_id,$employee_id);
			// if(!$hr_mails){
			// 	$Return['error'] = 'There is no HR Administartor Assigned for this location.Contact Your HR Team for further assist.';
			// }
			/*Get HR Administror Mails BY Location Wise*/


			// if($Return['error']!=''){
			// 	$this->output($Return);
			// }

			$fname = [];
			if($_FILES['document_file']['size'][0]!= 0) {
				$allowed =  array('png','jpg','jpeg','pdf','gif');
				$count=count($_FILES['document_file']['name']);
				for($i=0;$i<$count;$i++){
					$filename = strtolower($_FILES['document_file']['name'][$i]);
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
					if(in_array($ext,$allowed)){
						$tmp_name = $_FILES["document_file"]["tmp_name"][$i];
						$documentd = "uploads/leavedocument/";
						$name = basename($_FILES["document_file"]["name"][$i]);
						$newfilename = 'document_'.round(microtime(true)).'_'.$i.'.'.$ext;
						move_uploaded_file($tmp_name, $documentd.$newfilename);
						$fname[] = $newfilename;
					}else {
						$Return['error'] = $this->lang->line('xin_employee_picture_type');
					}
				}
			}
			$fname=implode(',',$fname);

			// pr($this->input->post());
			// pr($this->input->post('leave_dates'));die;
			$leave_dates=json_decode($this->input->post('leave_dates'));

			if($leave_dates){
				foreach($leave_dates as $leave_dt){

					$i = 0;
					foreach($leave_dt as $leave_dat){
						$read_leave_type_id=$this->Timesheet_model->read_leave_type_id($leave_dat->leave_name);
						$start_date=format_date('Y-m-d',$leave_dat->start_date);
						$end_date=format_date('Y-m-d',$leave_dat->end_date);
						$start = new DateTime($start_date);
						$end = new DateTime($end_date);
						$count_of_days = ($start->diff($end,true)->days)+1;
						$ends = $end->modify( '+1 day' );
						$interval_re = new DateInterval('P1D');
						$date_range = new DatePeriod($start, $interval_re ,$ends);
						$leave_result=0;
						$leave_result_array='';
						$leave_array='';
						foreach($date_range as $date1) {
							$leave_date = $date1->format("Y-m-d");
							$leave_result_array= $this->Timesheet_model->check_leave_result($leave_date,$this->input->post('employee_id'),$read_leave_type_id);

							$leave_result+=$leave_result_array['value'];
							if($leave_result_array['l_date']!='')
								$leave_array.= format_date('d F Y',$leave_result_array['l_date']).' - '.$leave_result_array['l_name'].'<br>';
						}
						if($leave_dat->available_leave_days){
							$available_leave_days=$leave_dat->available_leave_days;
						}else{$available_leave_days=0;}

						if($leave_dat->leave_status_code){
							$leave_status_code=$leave_dat->leave_status_code;
						}else{$leave_status_code=0;}


						if($leave_result==0){

							if($i==0){

								$data = array(
									'leave_type_id' => $read_leave_type_id,
									'from_date' => $start_date,
									'to_date' => $end_date,
									'reason' => $this->input->post('reason'),
									'documentfile' => $fname,
									'available_leave_days'=> $available_leave_days,
									'leave_status_code'=> $leave_status_code,
									'count_of_days' => $count_of_days
								);
								$result = $this->Timesheet_model->update_leave_record($data,$id);
							}else{

								$data = array(
									'employee_id' => $this->input->post('employee_id'),
									'leave_type_id' => $read_leave_type_id,
									'from_date' => $start_date,
									'to_date' => $end_date,
									'applied_on' => $applied_on,
									'reason' => $this->input->post('reason'),
									'documentfile' => $fname,
									'available_leave_days'=> $available_leave_days,
									'leave_status_code'=> $leave_status_code,
									'count_of_days' => $count_of_days,
									'status' => '1',
									'created_at' => date('Y-m-d H:i:s')
								);

								$result = $this->Timesheet_model->add_leave_record($data);
							}

						}else{
							$Return['error'] = 'You already applied leaves on below date.<br>'.$leave_array;
							$this->output($Return);
							exit;
						}

						$i++;
					}
				}
			}else{

				$data = array(
								'reason' => $this->input->post('reason'),
								'documentfile' => $fname
							);

				$result = $this->Timesheet_model->update_leave_record($data,$id);

			}
			
			$get_least_id=$this->db->query("select leave_id from xin_leave_applications where applied_on='".$rand."' order by leave_id asc limit 1");
			$result_least_id=$get_least_id->result();
			$link_id_send=$result_least_id[0]->leave_id;
			/*User Logs*/
			$affected_id= table_max_id('xin_leave_applications','leave_id');
			userlogs('Timesheet-Employee Leave-Edit','Update Leave  Request',$affected_id['field_id'],$affected_id['datas']);
			/*User Logs*/
			if ($result == TRUE) {
				$Return['result'] = 'Leave Updated.';
				// $approval_creation=approval_creation($this->input->post('employee_id'),'leave_request',$actual_leave_name,$rand,'first');
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function update_leave_status() {
		if($this->input->post('update_type')=='leave') {
			$id = $this->uri->segment(3);
			$counts=count($this->input->post('update_leave_id'));
			$from_date=$this->input->post('start_date');
			$to_date=$this->input->post('end_date');
			$update_leave_id=$this->input->post('update_leave_id');
			$employee_id=$this->input->post('employee_id');
			$leave_type=$this->input->post('leave_type');
			$Return = array('result'=>'', 'error'=>'', 'message'=>'');
			$timesheet = $this->Timesheet_model->read_leave_information($id);
			$leave_name=$timesheet[0]->type_name;
			$remarks = $this->input->post('remarks');
			$applied_on=$timesheet[0]->applied_on;
			$approval_creation=[];
			$update_approval_id='';
			if($this->input->post('status')){ //status
				$approval_data=array('approval_status' => $this->input->post('status'),'remarks' => $remarks,'approved_date' => date('Y-m-d H:i:s'));
				$update_approval_status=$this->Timesheet_model->update_approval_status($applied_on,'leave_request',$approval_data,$this->userSession['user_id']);
				if($leave_name=='Sick Leave'){
					$this->Timesheet_model->update_approval_status($applied_on,'leave_request',$approval_data,'');
				}
				$update_approval_id=$update_approval_status;
				$result = TRUE;
				if($this->input->post('status')==2){
					if($update_approval_id[0]->head_of_approval=='Reporting Manager' || $update_approval_id[0]->head_of_approval=='HOD'){
						$approval_creation=approval_creation($employee_id,'leave_request',$leave_name,$applied_on,'');
					}

				}
			}
			else if($this->input->post('admin_status')){//admin_status
				for($i=0;$i<$counts;$i++){
					$data = array(
						'from_date'=>format_date('Y-m-d',$from_date[$i]),
						'to_date'=>format_date('Y-m-d',$to_date[$i]),
						'reporting_manager_status' => $this->input->post('admin_status'),
						'status' => $this->input->post('admin_status'),
						'approve_administrator_id' => @$this->userSession['user_id'],
						'remarks' => $remarks,
					);
					$result = $this->Timesheet_model->update_leave_record($data,$update_leave_id[$i]);
					$approval_data=array('approval_status' => $this->input->post('admin_status'),'approved_date' => date('Y-m-d H:i:s'));
					$update_approval_status=$this->Timesheet_model->update_approval_status($applied_on,'leave_request',$approval_data,'');
				}
			}

			$total_approval=$this->Timesheet_model->get_total_approval($applied_on,'');
			$total_approval_count=count($total_approval);
			$total_approval_success=$this->Timesheet_model->get_total_approval($applied_on,2);
			$total_approval_success_count=count($total_approval_success);
			$total_approval_reject=$this->Timesheet_model->get_total_approval($applied_on,3);
			$total_approval_reject_count=count($total_approval_reject);

			if($total_approval_count==$total_approval_success_count){
				for($i=0;$i<$counts;$i++){
					$data = array('status' => 2,'reporting_manager_status' => 2);$this->Timesheet_model->update_leave_record($data,$update_leave_id[$i]);
				}
			}else if($total_approval_count==$total_approval_reject_count){
				for($i=0;$i<$counts;$i++){
					$data = array('status' => 3,'reporting_manager_status' => 3);$this->Timesheet_model->update_leave_record($data,$update_leave_id[$i]);
				}
			}

			$link_url_emp=base_url().base64_encode('MAILREDIRECT-employee/leave');
			$link_url=base_url().base64_encode('MAILREDIRECT-timesheet/leave');
			$user_info = $this->Xin_model->read_user_info($timesheet[0]->employee_id);

			/*Get HR Administror Mails BY Location Wise*/
			$hr_mails=get_hr_mail_bylocation($user_info[0]->office_location_id,$employee_id);
			/*Get HR Administror Mails BY Location Wise*/

			/*User Logs*/
			$affected_id= table_update_id('xin_leave_applications','leave_id',$id);
			userlogs('Timesheet-Employee Leave-Update','Employee Leave Status Updated',$id,$affected_id['datas']);
			/*User Logs*/


			if ($result == TRUE) {
				$Return['result'] = 'Leave status updated.';
				if(email_notification('leave_email') == 'yes'){
					$cinfo = $this->Xin_model->read_company_setting_info(1);
					$template_approval = $this->Xin_model->read_email_template(6);
					$template_reject = $this->Xin_model->read_email_template(7);

					$full_name = change_fletter_caps($user_info[0]->first_name.' '.$user_info[0]->middle_name.' '.$user_info[0]->last_name);

					$reporting_manager_mail=$this->Xin_model->read_user_info($user_info[0]->reporting_manager);
					$rep_name = change_fletter_caps($reporting_manager_mail[0]->first_name.' '.$reporting_manager_mail[0]->middle_name.' '.$reporting_manager_mail[0]->last_name);
					$rep_designation = $this->Designation_model->read_designation_information($reporting_manager_mail[0]->designation_id);
					$rep_designation_name=$rep_designation[0]->designation_name;

					$session_user=$this->Xin_model->read_user_info($this->userSession['user_id']);
					$session_user_name=change_fletter_caps($session_user[0]->first_name.' '.$session_user[0]->middle_name.' '.$session_user[0]->last_name);
					$designation = $this->Designation_model->read_designation_information($session_user[0]->designation_id);
					$designation_name=$designation[0]->designation_name;

					//Admin Approval
					if($this->input->post('admin_status')==2){
						$subject = $template_approval[0]->subject.' by Administrator  <'.$session_user_name.'>';
						$content="Your <strong>".$leave_name."</strong> request has been approved by Administrator.";
						if($remarks!=''){
							$content.='<br><br><b>Reason : </b><br>'.$remarks;
						}
						$message = '
				<div>'.str_replace(array("{var site_name}","{var site_url}","{var emp_name}","{var rep_name}","{var link_url}","{var content}"),array($cinfo[0]->company_name,site_url(),$full_name,$session_user_name.'<br>'.$designation_name,str_replace('http://','',$link_url_emp),$content),htmlspecialchars_decode(stripslashes($template_approval[0]->message))).'</div>';

						$subject_rm = 'Your team member leave request has been approved by Administrator  <'.$session_user_name.'>';
						$content_rm="Your team member ".$full_name.' '.$leave_name." request has been approved by Administrator.";
						$message_rm = '
				<div>'.str_replace(array("{var site_name}","{var site_url}","{var emp_name}","{var rep_name}","{var link_url}","{var content}"),array($cinfo[0]->company_name,site_url(),$rep_name,$session_user_name.'<br>'.$designation_name,str_replace('http://','',$link_url),$content_rm),htmlspecialchars_decode(stripslashes($template_approval[0]->message))).'</div>';

						$usermail=$this->email;
						$usermail->from($cinfo[0]->email, $cinfo[0]->company_name);
						if(TESTING_MAIL==TRUE){
							$usermail->to(TO_MAIL);
						}else{
							$usermail->to($user_info[0]->email);
							$usermail->bcc(TO_MAIL);
						}
						$usermail->subject($subject);
						$usermail->message($message);
						$usermail->send();
						$reportingmail=$this->email;
						if(TESTING_MAIL==TRUE){
							$reportingmail->to(TO_MAIL);
						}else{
							$reportingmail->to($reporting_manager_mail[0]->email);
             				//	$reportingmail->bcc(TO_MAIL);
						}
						$reportingmail->subject($subject_rm);
						$reportingmail->message($message_rm);
						//$reportingmail->send();
					}
					else if($this->input->post('admin_status')==3){
						$subject = $template_reject[0]->subject.' by Administrator <'.$session_user_name.'>';
						$content="Your <strong>".$leave_name."</strong> request has been rejected by administrator";
						if($remarks!=''){
							$content.='<br><br><b>Reason : </b><br>'.$remarks;
						}
						$message = '
				<div>'.str_replace(array("{var site_name}","{var site_url}","{var emp_name}","{var rep_name}","{var link_url}","{var content}"),array($cinfo[0]->company_name,site_url(),$full_name,$session_user_name.'<br>'.$designation_name,str_replace('http://','',$link_url_emp),$content),htmlspecialchars_decode(stripslashes($template_reject[0]->message))).'</div>';

						$subject_rm = 'Your team member leave request has been rejected by Administrator  <'.$session_user_name.'>';
						$content_rm="Your team member ".$full_name.' '.$leave_name." request has been rejected by Administrator.";
						$message_rm = '
				<div>'.str_replace(array("{var site_name}","{var site_url}","{var emp_name}","{var rep_name}","{var link_url}","{var content}"),array($cinfo[0]->company_name,site_url(),$rep_name,$session_user_name.'<br>'.$designation_name,str_replace('http://','',$link_url),$content_rm),htmlspecialchars_decode(stripslashes($template_reject[0]->message))).'</div>';

						$usermail=$this->email;
						$usermail->from($cinfo[0]->email, $cinfo[0]->company_name);
						if(TESTING_MAIL==TRUE){
							$usermail->to(TO_MAIL);
						}else{
							$usermail->to($user_info[0]->email);
							//$usermail->bcc(TO_MAIL);
						}
						$usermail->subject($subject);
						$usermail->message($message);
						$usermail->send();
						$reportingmail=$this->email;
						if(TESTING_MAIL==TRUE){
							$reportingmail->to('siddiq.awok@gmail.com');
						}else{
							$reportingmail->to($reporting_manager_mail[0]->email);
							//$reportingmail->bcc(TO_MAIL);
						}
						$reportingmail->subject($subject_rm);
						$reportingmail->message($message_rm);
						//$reportingmail->send();
					}
					//Admin Approval

					if((empty($approval_creation)) && ($this->input->post('status')==2)){
						$subject = $template_approval[0]->subject.' by your '.$update_approval_id[0]->head_of_approval;
						$content="Your <strong>".$leave_name."</strong> request has been approved by your ".$update_approval_id[0]->head_of_approval.'.';
						if($update_approval_id[0]->remarks!=''){
							$content.='<br><br><b>Reason : </b><br>'.$update_approval_id[0]->remarks;
						}
						$message = '
				<div>'.str_replace(array("{var site_name}","{var site_url}","{var emp_name}","{var rep_name}","{var link_url}","{var content}"),array($cinfo[0]->company_name,site_url(),$full_name,$session_user_name.'<br>'.$designation_name,str_replace('http://','',$link_url_emp),$content),htmlspecialchars_decode(stripslashes($template_approval[0]->message))).'</div>';

						$usermail=$this->email;
						$usermail->from($cinfo[0]->email, $cinfo[0]->company_name);
						if(TESTING_MAIL==TRUE){
							$usermail->to(TO_MAIL);
						}else{
							$usermail->to($user_info[0]->email);
    						//$usermail->bcc(TO_MAIL);
						}
						$usermail->subject($subject);
						$usermail->message($message);
						$usermail->send();
					}
					else if((empty($approval_creation)) && ($this->input->post('status')==3)){
						$subject = $template_reject[0]->subject.' by your '.$update_approval_id[0]->head_of_approval;
						$content="Your <strong>".$leave_name."</strong> request has been rejected by your ".$update_approval_id[0]->head_of_approval.'.';
						if($update_approval_id[0]->remarks!=''){
							$content.='<br><br><b>Reason : </b><br>'.$update_approval_id[0]->remarks;
						}

						$message = '
				<div>'.str_replace(array("{var site_name}","{var site_url}","{var emp_name}","{var rep_name}","{var link_url}","{var content}"),array($cinfo[0]->company_name,site_url(),$full_name,$session_user_name.'<br>'.$designation_name,str_replace('http://','',$link_url_emp),$content),htmlspecialchars_decode(stripslashes($template_reject[0]->message))).'</div>';

						$usermail=$this->email;
						$usermail->from($cinfo[0]->email, $cinfo[0]->company_name);
						if(TESTING_MAIL==TRUE){
							$usermail->to(TO_MAIL);
						}else{
							$usermail->to($user_info[0]->email);
							//$usermail->bcc(TO_MAIL);
						}
						$usermail->subject($subject);
						$usermail->message($message);
						$usermail->send();
					}
					$Return['message']='';
				}
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function eligibility_check(){
		$employee_id=$this->input->get('employee_id');
		$start_date=format_date('Y-m-d',$this->input->get('start_date'));
		$end_date=format_date('Y-m-d',$this->input->get('end_date'));
		$user_info=$this->Xin_model->read_user_info($employee_id);
		$date_of_joining=$this->Timesheet_model->check_date_of_joining($employee_id);
		$sick_leave_start_date=date('Y-m-d',strtotime(JOINEE_SICK_LEAVE_NOT_ALLOW,strtotime($date_of_joining)));
		if((strtotime($sick_leave_start_date) > strtotime($start_date))){
			$date1 =new DateTime($start_date);
			$date2 =new DateTime($end_date);
			$diff =  date_diff($date1,$date2);
			$range = range($date1->format("w"),$date1->format("w") + $diff->days);
			array_walk($range, function(&$a,$b) { $a = $a % 7; });
			if(in_array(4,$range)) {
				echo 1;
			}else{
				echo 0;
			}
		}else{
			echo 1;
		}
	}

	public function edit_task() {
		if($this->input->post('edit_type')=='task') {
			$id = $this->uri->segment(3);
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');

			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');
			$description = $this->input->post('description');

			$st_date = strtotime($start_date);
			$ed_date = strtotime($end_date);
			$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);

			/* Server side PHP input validation */
			if($this->input->post('task_name')==='') {
				$Return['error'] = "The task name field is required.";
			} else if($this->input->post('start_date')==='') {
				$Return['error'] = "The start date field is required.";
			} else if($this->input->post('end_date')==='') {
				$Return['error'] = "The end date field is required.";
			} else if($st_date >= $ed_date) {
				$Return['error'] = "Start Date should be less than or equal to End Date.";
			} else if($this->input->post('task_hour')==='') {
				$Return['error'] = "The task hour field is required.";
			}

			if($Return['error']!=''){
				$this->output($Return);
			}

			if(null!=$this->input->post('assigned_to')) {
				$assigned_ids = implode(',',$this->input->post('assigned_to'));
			} else {
				$assigned_ids = 'None';
			}

			$data = array(
				'task_name' => $this->input->post('task_name'),
				'assigned_to' => $assigned_ids,
				'start_date' => $this->input->post('start_date'),
				'end_date' => $this->input->post('end_date'),
				'task_hour' => $this->input->post('task_hour'),
				'description' => $qt_description
			);

			$result = $this->Timesheet_model->update_task_record($data,$id);
			if ($result == TRUE) {
				$Return['result'] = 'Task updated.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function read_task_record()
	{
		$data['title'] = $this->Xin_model->site_title();
		$task_id = $this->input->get('task_id');
		$result = $this->Timesheet_model->read_task_information($task_id);
		$data = array(
			'task_id' => $result[0]->task_id,
			'created_by' => $result[0]->created_by,
			'task_name' => $result[0]->task_name,
			'assigned_to' => $result[0]->assigned_to,
			'start_date' => $result[0]->start_date,
			'end_date' => $result[0]->end_date,
			'task_hour' => $result[0]->task_hour,
			'task_progress' => $result[0]->task_progress,
			'description' => $result[0]->description,
			'created_at' => $result[0]->created_at,
			'all_employees' => $this->Xin_model->all_employees()
		);
		if(!empty($this->userSession)){
			$this->load->view('timesheet/tasks/dialog_task', $data);
		} else {
			redirect('');
		}
	}

	public function calculate_amount_of_leave_conversion(){
		if($this->input->post('type')=='calculate_amount') {
			$employee_id=$this->input->post('employee_id');
			$calculation_type=$this->input->post('calculation_type');
			$leave_conversion_count=$this->input->post('leave_conversion_count');
			$query = $this->db->query("SELECT * FROM `xin_salary_templates` WHERE `employee_id` = '".$employee_id."' AND effective_to_date='' AND is_approved=1 limit 1");//
			$result = $query->result();
			$basic_salary=$result[0]->basic_salary;
			$house_rent_allowance=$result[0]->house_rent_allowance;
			if($calculation_type==0){
				$total_salary_amount=((($basic_salary)*12)/365)*$leave_conversion_count;
				$am=round($total_salary_amount,2);
			}else{
				$total_salary_amount=((($basic_salary+$house_rent_allowance)*12)/365)*$leave_conversion_count;
				$am=round($total_salary_amount,2);
			}
		}else{
			$am=0;}
		echo $am;
	}

	public function approve_leave_conversion(){
		if($this->input->post('edit_type')=='approve_leave_conversion') {
			$Return = array('result'=>'', 'error'=>'', 'message'=>'');
			$redirect = $this->input->post('redirect');
			$leave_conversion_id = $this->input->post('leave_conversion_id');
			$employee_id=$this->input->post('employee_id');
			$leave_conversion_count=$this->input->post('leave_conversion_count');
			$conversion_comments=$this->input->post('conversion_comments');
			$added_date=format_date('Y-m-d',$this->input->post('added_date'));
			$calculation_type=$this->input->post('calculation_type');
			$calculated_amount=$this->input->post('calculated_amount');
			$approved_status=$this->input->post('approved_status');
			$approve_link = $this->input->post('approve_link');
			$decline_link = $this->input->post('decline_link');

			$data=array(
				'leave_conversion_type'=>$calculation_type,
				'amount'=>$calculated_amount,
			);

			$result=$this->Timesheet_model->update_leave_conversion($data,$leave_conversion_id);

			if($redirect=='yes'){
				if($approved_status==1){
					$Return['message'] = $approve_link;
				}else if($approved_status==2){
					$Return['message'] = $decline_link;
				}
			}else{
				if($result){
					$Return['result'] = 'Leave cash conversion saved successfully.';
				}else{
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
			}
			$this->output($Return);
			exit;
		}
	}

	public function update_leave_conversion(){
		if($this->input->post('edit_type')=='update_leave_conversion') {
			$Return = array('result'=>'', 'error'=>'', 'message'=>'');
			$leave_conversion_id = $this->input->post('leave_conversion_id');
			$employee_id=$this->input->post('employee_id');
			$leave_conversion_count=$this->input->post('leave_conversion_count');
			$conversion_comments=$this->input->post('conversion_comments');
			$added_date=format_date('Y-m-d',$this->input->post('added_date'));
			$calculation_type=$this->input->post('calculation_type');
			$calculated_amount=$this->input->post('calculated_amount');
			if($this->input->post('approved_status')){
				$data=array(
					'leave_conversion_count'=>$leave_conversion_count,
					'conversion_comments'=>$conversion_comments,
					'added_date'=>$added_date,
					'updated_by'=>$this->userSession['user_id'],
					'leave_conversion_type'=>$calculation_type,
					'amount'=>$calculated_amount,
					'hr_notification'=>0,
					'approved_status'=>$this->input->post('approved_status'),
				);
			}else{
				$data=array(
					'leave_conversion_count'=>$leave_conversion_count,
					'conversion_comments'=>$conversion_comments,
					'added_date'=>$added_date,
					'updated_by'=>$this->userSession['user_id'],
					'leave_conversion_type'=>$calculation_type,
					'amount'=>$calculated_amount,
					'hr_notification'=>0,
				);
			}

			$result=$this->Timesheet_model->update_leave_conversion($data,$leave_conversion_id);
			$field_id=$leave_conversion_id;
			$created_by=$this->userSession['user_id'];
			$type_of_approval='Leave Cash Conversion';
			$emp_info = $this->Xin_model->read_user_info($employee_id);
			$all_department_heads=$this->Employees_model->get_ceo_only($emp_info[0]->department_id,'','',$type_of_approval);

			$count_of_dept=count($all_department_heads);
			$setting = $this->Xin_model->read_setting_info(1);
			$this->email->set_mailtype("html");
			if($all_department_heads){
				$insert_approvals=$this->Employees_model->insert_approvals($employee_id,$field_id,$created_by,$all_department_heads,$type_of_approval,'');
				$designation = $this->Designation_model->read_designation_information($emp_info[0]->designation_id);
				$emp_full_name = change_fletter_caps($emp_info[0]->first_name.' '.$emp_info[0]->middle_name.' '.$emp_info[0]->last_name);

				$cinfo = $this->Xin_model->read_company_setting_info(1);

				$sender = $this->Xin_model->read_user_info($created_by);
				$sender_designation = $this->Designation_model->read_designation_information($sender[0]->designation_id);
				$sender_full_name = change_fletter_caps($sender[0]->first_name.' '.$sender[0]->middle_name.' '.$sender[0]->last_name);
				foreach($all_department_heads as $head_of_dep){

					$head_info = $this->Xin_model->read_user_info($head_of_dep->head_id);

					$approve_link=$_SERVER['HTTP_HOST'].base_url().'index/leaveconversion_approval/'.base64_encode($head_of_dep->head_id.'/'.$employee_id.'/1/'.$type_of_approval.'/'.$created_by.'/'.$field_id);
					$decline_link=$_SERVER['HTTP_HOST'].base_url().'index/leaveconversion_approval/'.base64_encode($head_of_dep->head_id.'/'.$employee_id.'/2/'.$type_of_approval.'/'.$created_by.'/'.$field_id);

					//if($setting[0]->enable_email_notification == 'yes') {
					//get email template
					$template = $this->Xin_model->read_email_template_info_bycode('Leave Conversion');

					if($count_of_dept!=1){
						$head_title='Leave Cash Conversion Approval';
						$subject = $template[0]->subject;
					}else{
						$head_title='Leave Cash Conversion ReApproval';
						$subject = $head_title;
					}

					if($emp_info[0]->gender=='Male'){
						$title='Mr ';
					}else{
						$title='Ms ';
					}

					$subject = $subject.' for '.$emp_full_name;
					$days=$leave_conversion_count.' day/s';
					$content='On leave conversion approval for '.$title.'. '.$emp_full_name.' [1C ID : '.$emp_info[0]->employee_id.']  [Designation : '.$designation[0]->designation_name.'].';
					$html_structure='';
					$html_structure.='<div class="table-responsive" style="margin-bottom: 1em;"><table border="1" style="padding:10px;width: 100%;text-align:center;font-size:14px;line-height:20px;"><tbody>
			<tr style="background:#cc6076;color: white;font-size: 13px;"><td colspan="3">Leave Cash Conversion For '.$emp_full_name.'</td></tr>
			<tr class="success"><td>Employee Code</td><td>:</td><td>'.$emp_info[0]->employee_id.'</td></tr>	
			<tr class=""><td>Employee Name</td><td>:</td><td>'.change_fletter_caps($emp_info[0]->first_name.' '.$emp_info[0]->middle_name.' '.$emp_info[0]->last_name).'</td></tr>	
			<tr class="danger"><td>Designation</td><td>:</td><td>'.$designation[0]->designation_name.'</td></tr>
			<tr class=""><td>Leave Conversion Days</td><td>:</td><td>'.$days.'</td></tr>			';
					$html_structure.='</tbody></table></div>';
					$message='<div style="background: #f7eaea;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin: 0 auto;padding:20px;max-width: 65em;border: 2px solid #D40732;">'.
						str_replace(
							array(
								"{var head_title}",
								"{var hr_name}",
								"{var title}",
								"{var content}",
								"{var link_name}",
								"{var employee_name}",
								"{var designation_name}",
								"{var days}",
								"{var html_structure}",
								"{var link_url}",
							),
							array(
								$head_title,
								$head_of_dep->head_name,
								$title,
								$content,
								'',
								$sender_full_name,
								$sender_designation[0]->designation_name,
								$days,
								$html_structure,
								'<a href="'.$approve_link.'" target="_blank" style="background-color:green;padding: 8px;color: white;font-weight: bold;">Approve</a>   <a href="'.$decline_link.'" target="_blank" style="background-color:red;padding: 8px;color: white;font-weight: bold;">Decline</a>',
							),htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';


					if(TESTING_MAIL==TRUE){
						$this->email->from(FROM_MAIL, $sender_full_name);//$sender[0]->email
						$this->email->to(TO_MAIL);
					}else{
						$this->email->from(FROM_MAIL, $sender_full_name);//$sender[0]->email
						$this->email->to($head_info[0]->email);
					}
					$this->email->subject($subject);
					$this->email->message($message);
					//$this->email->send();
					//}
				}
				if($insert_approvals){
					$Return['result'] = 'Leave cash conversion approval send successfully.';
				}else{
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
			}else{
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	public function read_leave_conversion()
	{
		$data['title'] = $this->Xin_model->site_title();
		$leave_conversion_id = $this->input->get('leave_conversion_id');
		$result = $this->Timesheet_model->read_conversion_lists($leave_conversion_id);
		$data = array(
			'conversion_id' => $result[0]->conversion_id,
			'employee_id' => $result[0]->employee_id,
			'leave_conversion_count' => $result[0]->leave_conversion_count,
			'conversion_comments' => $result[0]->conversion_comments,
			'added_date' => $result[0]->added_date,
			'added_by' => $result[0]->added_by,
			'approved_status' => $result[0]->approved_status,
			'leave_conversion_type' => $result[0]->leave_conversion_type,
			'amount' => $result[0]->amount,
			'all_employees' => $this->Xin_model->all_employees(),
			'all_leave_types' => $this->Timesheet_model->all_leave_types(),
		);
		if(!empty($this->userSession)){
			$this->load->view('timesheet/dialog_leave', $data);
		} else {
			redirect('');
		}
	}

	public function read_leave_record()
	{
		$data['title'] = $this->Xin_model->site_title();
		$leave_id = $this->input->get('leave_id');
		$result = $this->Timesheet_model->read_leave_information($leave_id);
		$data = array(
			'leave_id' => $result[0]->leave_id,
			'employee_id' => $result[0]->employee_id,
			'leave_type_id' => $result[0]->leave_type_id,
			'from_date' => format_date('d F Y',$result[0]->from_date),
			'to_date' =>format_date('d F Y', $result[0]->to_date),
			'applied_on' => $result[0]->applied_on,
			'reason' => $result[0]->reason,
			'remarks' => $result[0]->remarks,
			'documentfile' => $result[0]->documentfile,
			'status' => $result[0]->status,
			'created_at' => $result[0]->created_at,
			'all_employees' => $this->Xin_model->all_employees(),
			'all_leave_types' => $this->Timesheet_model->all_leave_types(),
		);
		if(!empty($this->userSession)){
			$this->load->view('timesheet/dialog_leave', $data);
		} else {
			redirect('');
		}
	}

	public function read_bus_lateness()
	{
		$data['title'] = $this->Xin_model->site_title();
		$bus_late_id = $this->input->get('bus_late_id');
		$result = $this->Timesheet_model->read_bus_lateness($bus_late_id);
		$data = array(
			'bus_late_id' => $result[0]->bus_late_id,
			'location_id' => $result[0]->location_id,
			'bus_late_date' => $result[0]->bus_late_date,
			'bus_scheduled_time' => $result[0]->bus_scheduled_time,
			'bus_late_time' => $result[0]->bus_late_time,
		);
		if(!empty($this->userSession)){
			$this->load->view('timesheet/dialog_attendance', $data);
		} else {
			redirect('');
		}
	}

	public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$attendance_id = $this->input->get('attendance_id');
		$result = $this->Timesheet_model->read_attendance_information($attendance_id);
		$user = $this->Xin_model->read_user_info($result[0]->employee_id);
		// user full name
		$full_name = change_fletter_caps($user[0]->first_name.' '.$user[0]->middle_name.' '.$user[0]->last_name);
		$in_time = new DateTime($result[0]->clock_in);
		$out_time = new DateTime($result[0]->clock_out);
		$clock_in = $in_time->format('H:i');
		if($result[0]->clock_out == '') {
			$clock_out = '';
		} else {
			$clock_out = $out_time->format('H:i');
		}

		$data = array(
			'time_attendance_id' => $result[0]->time_attendance_id,
			'employee_id' => $result[0]->employee_id,
			'full_name' => $full_name,
			'attendance_date' => $result[0]->attendance_date,
			'clock_in' => $clock_in,
			'clock_out' => $clock_out
		);
		if(!empty($this->userSession)){
			$this->load->view('timesheet/dialog_attendance', $data);
		} else {
			redirect('');
		}
	}

	public function read_holiday_record()
	{
		$data['title'] = $this->Xin_model->site_title();
		$holiday_id = $this->input->get('holiday_id');
		$result = $this->Timesheet_model->read_holiday_information($holiday_id);
		$data = array(
			'holiday_id' => $result[0]['holiday_id'],
			'unique_id' => $result[0]['unique_id'],
			'country_id' => $result[0]['country_id'],
			'department_id' => $result[0]['department_id'],
			'event_name' => $result[0]['event_name'],
			'start_date' => format_date('d F Y',$result[0]['start_date']),
			'end_date' => format_date('d F Y',$result[0]['end_date']),
			'is_publish' => $result[0]['is_publish'],
			'description' => $result[0]['description'],
			'all_data'=>$result,
		);
		if(!empty($this->userSession)){
			$this->load->view('timesheet/dialog_holiday', $data);
		} else {
			redirect('');
		}
	}

	public function read_ramadan_schedule_record()
	{
		$data['title'] = $this->Xin_model->site_title();
		$ramadan_schedule_id = $this->input->get('ramadan_schedule_id');
		$result = $this->Timesheet_model->read_ramadan_information($ramadan_schedule_id);
		$data = array(
			'ramadan_schedule_id' => $result[0]['ramadan_schedule_id'],
			'reduced_hours' => $result[0]['reduced_hours'],
			'country_id' => $result[0]['country_id'],
			'created_by' => $result[0]['created_by'],
			'event_name' => $result[0]['event_name'],
			'start_date' => format_date('d F Y',$result[0]['start_date']),
			'end_date' => format_date('d F Y',$result[0]['end_date']),
			'is_publish' => $result[0]['is_publish'],
			'description' => $result[0]['description'],
		);
		if(!empty($this->userSession)){
			$this->load->view('timesheet/dialog_ramadan', $data);
		} else {
			redirect('');
		}
	}

	public function read_shift_record()
	{
		$data['title'] = $this->Xin_model->site_title();
		$office_shift_id = $this->input->get('office_shift_id');
		$result = $this->Timesheet_model->read_office_shift_information($office_shift_id);
		$data = array(
			'office_shift_id' => $result[0]->office_shift_id,
			'shift_name' => $result[0]->shift_name,
			'location_id' => $result[0]->location_id,
			'department_id' => $result[0]->department_id,
			'shift_in_time' => $result[0]->shift_in_time,
			'shift_out_time' => $result[0]->shift_out_time,
			'week_off' => $result[0]->week_off,
		);
		if(!empty($this->userSession)){
			$this->load->view('timesheet/dialog_office_shift', $data);
		} else {
			redirect('');
		}
	}

	public function add_office_shift() {
		if($this->input->post('add_type')=='office_shift') {
			$Return = array('result'=>'', 'error'=>'');
			/* Server side PHP input validation */
			$check_unique_location_department=$this->Xin_model->check_unique_location_department($this->input->post('location_id'),$this->input->post('department_id'),'');
			/*Check Hours*/
			$shift_in_time = new DateTime($this->input->post('shift_in_time'));
			$shift_out_time = new DateTime($this->input->post('shift_out_time'));
			$shift_out_time1 = $shift_out_time->diff($shift_in_time);
			$check_hours   = $shift_out_time1->format('%h');
			/*Check Hours*/

			/*$check_name=$this->Xin_model->check_unique_names('xin_office_default_shift','shift_name','office_shift_id',$this->input->post('shift_name'),'');
            if($this->input->post('shift_name')==='') {
                $Return['error'] = "The shift name field is required.";
            } else if($check_name!=0) {
                $Return['error'] = "The shift name already exist. Enter different one.";
            } else */ if($this->input->post('location_id')==='') {
				$Return['error'] = "The location field is required.";
			} else if($this->input->post('department_id')==='') {
				$Return['error'] = "The department field is required.";
			} else if($this->input->post('shift_in_time')==='') {
				$Return['error'] = "The shift in time field is required.";
			} else if($this->input->post('shift_out_time')==='') {
				$Return['error'] = "The shift out time field is required.";
			} else if(!$this->input->post('week_off')) {//===''
				$Return['error'] = "The week off field is required.";
			} else if($check_unique_location_department!=0) {
				$Return['error'] = "The shift already created.Select different one.";
			} else if($check_hours < 8) {
				$Return['error'] = "The shift should be minimum 8 hours.";
			}
			if($Return['error']!=''){
				$this->output($Return);
			}
			$data = array(
				'shift_name' => $this->input->post('shift_name'),
				'location_id' => $this->input->post('location_id'),
				'department_id' => $this->input->post('department_id'),
				'shift_in_time' => $this->input->post('shift_in_time'),
				'shift_out_time' => $this->input->post('shift_out_time'),
				'week_off' => implode(',',$this->input->post('week_off')),
				'created_by'  => $this->input->post('user_id')
			);
			$result = $this->Timesheet_model->add_office_shift_record($data);
			/*User Logs*/
			$affected_id= table_max_id('xin_office_default_shift','office_shift_id');
			userlogs('Timesheet-Office Shift-Add','New Office Shift Added',$affected_id['field_id'],$affected_id['datas']);
			/*User Logs*/
			if ($result == TRUE) {
				$Return['result'] = 'Office shift added.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function edit_office_shift() {
		if($this->input->post('edit_type')=='shift') {
			$id = $this->uri->segment(3);
			$Return = array('result'=>'', 'error'=>'');
			$check_unique_location_department=$this->Xin_model->check_unique_location_department($this->input->post('location_id'),$this->input->post('department_id'),$id);
			/*Check Hours*/
			$shift_in_time = new DateTime($this->input->post('shift_in_time'));
			$shift_out_time = new DateTime($this->input->post('shift_out_time'));
			$shift_out_time1 = $shift_out_time->diff($shift_in_time);
			$check_hours   = $shift_out_time1->format('%h');
			/*Check Hours*/

			/*$check_name=$this->Xin_model->check_unique_names('xin_office_default_shift','shift_name','office_shift_id',$this->input->post('shift_name'),$id);
            if($this->input->post('shift_name')==='') {
                $Return['error'] = "The shift name field is required.";
            } else if($check_name!=0) {
                $Return['error'] = "The shift name already exist. Enter different one.";
            } else */if($this->input->post('location_id')==='') {
				$Return['error'] = "The location field is required.";
			} else if($this->input->post('department_id')==='') {
				$Return['error'] = "The department field is required.";
			} else if($this->input->post('shift_in_time')==='') {
				$Return['error'] = "The shift in time field is required.";
			} else if($this->input->post('shift_out_time')==='') {
				$Return['error'] = "The shift out time field is required.";
			} else if(!$this->input->post('week_off')) {
				$Return['error'] = "The week off field is required.";
			} else if($check_unique_location_department!=0) {
				$Return['error'] = "The shift already created.Select different one.";
			} else if($check_hours < 8) {
				$Return['error'] = "The shift should be minimum 8 hours.";
			}

			if($Return['error']!=''){
				$this->output($Return);
			}

			$data = array(
				'shift_name' => $this->input->post('shift_name'),
				'location_id' => $this->input->post('location_id'),
				'department_id' => $this->input->post('department_id'),
				'shift_in_time' => $this->input->post('shift_in_time'),
				'shift_out_time' => $this->input->post('shift_out_time'),
				'week_off' => implode(',',$this->input->post('week_off')),
				'created_by'  => $this->input->post('user_id')
			);
			$result = $this->Timesheet_model->update_shift_record($data,$id);
			/*User Logs*/
			$affected_id= table_update_id('xin_office_default_shift','office_shift_id',$id);
			userlogs('Timesheet-Office Shift-Update','Office Shift Updated',$id,$affected_id['datas']);
			/*User Logs*/
			if ($result == TRUE) {
				$Return['result'] = 'Office Shift updated.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}
	
	public function delete_manual_attendance() {
		if($this->input->post('type')=='delete_manual_attendance') {
			$Return = array('result'=>'', 'error'=>'');
			$manual_attendance = $this->input->post('manual_attendance_id');
			$manual_attendance=explode(',',$manual_attendance);
			foreach($manual_attendance as $manual_a){
				$e_att=explode('-',$manual_a);
				$result = $this->Timesheet_model->read_change_manual_attendance_information($e_att[0],$e_att[1]);
				/*User Logs*/
				$affected_row= table_deleted_row('xin_manual_attendance','manual_attendance_id',$result[0]->manual_attendance_id);
				/*User Logs*/				
				$this->Timesheet_model->delete_manual_attendance_record($e_att[0],$e_att[1]);
				/*User Logs*/
				userlogs('Timesheet-Manual Attendance-Delete','Manual Attendance Deleted',$result[0]->manual_attendance_id,$affected_row);
				/*User Logs*/
			}
			if(isset($manual_attendance)) {
				$Return['result'] = 'Manual Attendance deleted.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
		}
	}
	
	public function delete_change_schedule() {
		if($this->input->post('type')=='delete_change_schedule') {
			$Return = array('result'=>'', 'error'=>'');
			$change_schedule = $this->input->post('change_schedule_id');
			$change_schedule=explode(',',$change_schedule);
			foreach($change_schedule as $manual_a){
				$result = $this->Timesheet_model->read_change_schedule_information($manual_a);
				/*User Logs*/
				$affected_row= table_deleted_row('xin_change_schedule','change_schedule_id',$result[0]->change_schedule_id);
				/*User Logs*/
				$this->Timesheet_model->delete_change_schedule_record($manual_a);
				/*User Logs*/
				userlogs('Timesheet-Change Schedule-Delete','Change Schedule Deleted',$result[0]->change_schedule_id,$affected_row);
				/*User Logs*/
			}
			if(isset($change_schedule)) {
				$Return['result'] = 'Change Schedule deleted.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
		}
	}

	public function status_manual_attendance() {
		if($this->input->post('type')=='status_manual_attendance') {
			$Return = array('result'=>'', 'error'=>'');
			$manual_attendance = $this->input->post('manual_attendance_id');
			$change_status= $this->input->post('change_status');
			$manual_attendance=explode(',',$manual_attendance);
	
			foreach($manual_attendance as $manual_a){
				$e_att=explode('-',$manual_a);
				$result = $this->Timesheet_model->status_manual_attendance_record($e_att[0],$e_att[1],$change_status,$this->userSession['user_id']);
				
				if($result!=null){
					/*User Logs*/
					$affected_id= table_update_id('xin_manual_attendance','manual_attendance_id',$result);
					userlogs('Timesheet-Manual Attendance-Update','Manual Attendance Status Updated',$result,$affected_id['datas']);
					/*User Logs*/
				}			
			}					

			if(isset($manual_attendance)) {
				$Return['result'] = 'Manual Attendance status changed.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
		}
	}

	public function delete_holiday() {
		if($this->input->post('form')=='delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$unique_id = $this->uri->segment(3);
			
			/*User Logs*/
			$affected_row= table_deleted_row('xin_holidays','unique_id',$unique_id);
			userlogs('Employees-Public Holiday-Delete','Public Holiday Deleted',$unique_id,$affected_row);
			/*User Logs*/

            $this->Timesheet_model->delete_holiday_record($unique_id);
			if(isset($unique_id)) {
				$Return['result'] = 'Holiday deleted.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
		}
	}

	public function delete_ramadan_schedule() {
		if($this->input->post('form')=='delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			/*User Logs*/
			$affected_row= table_deleted_row('xin_ramadan_schedule','ramadan_schedule_id',$id);
			userlogs('Employees-Exceptional Schedule-Delete','Exceptional Schedule Deleted',$id,$affected_row);
			/*User Logs*/
			$this->Timesheet_model->delete_ramadan_schedule($id);
			if(isset($id)) {
				$Return['result'] = 'Ramadan Schedule deleted.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
		}
	}

	public function delete_shift() {
		if($this->input->post('form')=='delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			/*User Logs*/
			$affected_row= table_deleted_row('xin_office_default_shift','office_shift_id',$id);
			userlogs('Timesheet-Office Shift-Delete','Office Shift Deleted',$id,$affected_row);
			/*User Logs*/
			$this->Timesheet_model->delete_shift_record($id);
			if(isset($id)) {
				$Return['result'] = 'Office Shift deleted.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
		}
	}

	public function delete_leave() {
		if($this->input->post('form')=='delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			$leave = $this->Timesheet_model->read_leave_information($id);
			$check_if_any_secondary_leave= $this->Timesheet_model->check_if_any_secondary_leave($leave[0]->leave_id,$leave[0]->employee_id,$leave[0]->applied_on);
			/*User Logs*/
			$affected_row= table_deleted_row('xin_leave_applications','leave_id',$id);
			userlogs('Timesheet-Employee Leave-Delete','Employee Leave Deleted',$id,$affected_row);
			/*User Logs*/
			$this->Timesheet_model->delete_leave_record($id);
			if($check_if_any_secondary_leave){
				foreach($check_if_any_secondary_leave as $secondary){
					$this->Timesheet_model->delete_leave_record($secondary->leave_id);
				}
			}
			$this->Timesheet_model->delete_leave_approval_record($leave[0]->applied_on,$leave[0]->employee_id,'leave_request');
			if(isset($id)) {
				$Return['result'] = 'Leave deleted.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
		}
	}

	public function delete_leave_conversion() {
		if($this->input->post('form')=='delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
            $this->Timesheet_model->read_conversion_lists($id);
			/*User Logs*/
			$affected_row= table_deleted_row('xin_leave_conversion_count','conversion_id',$id);
			userlogs('Timesheet-Employee Leave Conversion-Delete','Employee Leave Conversion Deleted',$id,$affected_row);
			/*User Logs*/
            $this->Timesheet_model->delete_leave_conversion_record($id);
			if($id) {
				$Return['result'] = 'Leave Conversion data deleted.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
		}
	}

	public function delete_task() {
		if($this->input->post('form')=='delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			$this->Timesheet_model->delete_task_record($id);
			if(isset($id)) {
				$Return['result'] = 'Task deleted.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
		}
	}

	public function add_note() {
		if($this->input->post('type')=='add_note') {
			$Return = array('result'=>'', 'error'=>'');
			$data = array(
				'task_note' => $this->input->post('task_note')
			);
			$id = $this->input->post('note_task_id');
			$result = $this->Timesheet_model->update_task_record($data,$id);
			if ($result == TRUE) {
				$Return['result'] = 'Task note updated.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function set_clocking() {
		if($this->input->post('type')=='set_clocking') {
			$Return = array('result'=>'', 'error'=>'');
			$employee_id = $this->userSession['user_id'];
			$clock_state = $this->input->post('clock_state');
			// set time
			$nowtime = date("Y-m-d H:i:s");
			$date = date('Y-m-d H:i:s', strtotime($nowtime . ' + 4 hours'));
			$curtime = $date;
			$today_date = date('Y-m-d');
			if($clock_state=='clock_in') {
				$query = $this->Timesheet_model->check_user_attendance();
				$result = $query->result();
				if($query->num_rows() < 1) {
					$total_rest = '';
				} else {
					$cout =  new DateTime($result[0]->clock_out);
					$cin =  new DateTime($curtime);
					$interval_cin = $cin->diff($cout);
					$hours_in   = $interval_cin->format('%h');
					$minutes_in = $interval_cin->format('%i');
					$total_rest = $hours_in .":".$minutes_in;
				}

				$data = array(
					'employee_id' => $employee_id,
					'attendance_date' => $today_date,
					'clock_in' => $curtime,
					'time_late' => $curtime,
					'early_leaving' => $curtime,
					'overtime' => $curtime,
					'total_rest' => $total_rest,
					'attendance_status' => 'Present',
					'clock_in_out' => '1'
				);
				$result = $this->Timesheet_model->add_new_attendance($data);
				if ($result == TRUE) {
					$Return['result'] = 'You have CLOCKED-IN.';
				} else {
					$Return['error'] = 'Bug. Something went wrong, please try again.';
				}
			}
			else if($clock_state=='clock_out') {
				$query = $this->Timesheet_model->check_user_attendance_clockout();
				$clocked_out = $query->result();
				$total_work_cin =  new DateTime($clocked_out[0]->clock_in);
				$total_work_cout =  new DateTime($curtime);
				$interval_cin = $total_work_cout->diff($total_work_cin);
				$hours_in   = $interval_cin->format('%h');
				$minutes_in = $interval_cin->format('%i');
				$total_work = $hours_in .":".$minutes_in;
				$data = array(
					'clock_out' => $curtime,
					'clock_in_out' => '0',
					'early_leaving' => $curtime,
					'overtime' => $curtime,
					'total_work' => $total_work
				);
				$id = $this->input->post('time_id');
				$resuslt2 = $this->Timesheet_model->update_attendance_clockedout($data,$id,$employee_id);
				if ($resuslt2 == TRUE) {
					$Return['result'] = 'You have CLOCKED-OUT.';
					$Return['time_id'] = '';
				} else {
					$Return['error'] = 'Bug. Something went wrong, please try again.';
				}
			}
			$this->output($Return);
			exit;
		}
	}

	//Add Manual Attendance
	public function add_manual_attendance() {
		if($this->input->post('add_type')=='add_manual_attendance') {
			$Return = array('result'=>'', 'error'=>'', 'message'=>'');
			$start_date = format_date('Y-m-d',$this->input->post('start_date'));
			$end_date = format_date('Y-m-d',$this->input->post('end_date'));
			$attendance_status=$this->input->post('attendance_status');
			$reason=$this->input->post('reason');
			$start_time=$this->input->post('start_time');
			$end_time=$this->input->post('end_time');
			$ob_type = $this->input->post('ob_type');
			if($this->input->post('schedule_to')=='department') {
				if(!$this->input->post('location_id')) {
					$Return['error'] = "The location field is required.";
				} /*else if(!$this->input->post('department_id')) {
				$Return['error'] = "The department field is required.";
		        } */else if($this->input->post('start_date')==='') {
					$Return['error'] = "The start date field is required.";
				} else if($this->input->post('end_date')==='') {
					$Return['error'] = "The end_date field is required.";
				} else if(!$this->input->post('attendance_status')) {
					$Return['error'] = "The attendance status field is required.";
				} else if(!$this->input->post('employee_id')){
					$Return['error'] = "Select atleast one employee.";
				} else if(strtotime($start_date) >  strtotime($end_date)) {
					$Return['error'] = "The end date should be greater than start date.";
				} else if($this->input->post('reason')==='') {
					$Return['error'] = "The reason field is required.";
				} else if($this->input->post('start_time')==='') {
					$Return['error'] = "The start time field is required.";
				} else if($this->input->post('end_time')==='') {
					$Return['error'] = "The end time field is required.";
				} else if(strtotime($start_time) >  strtotime($end_time)) {
					$Return['error'] = "The end time should be greater than start time.";
				}else if($this->input->post('ob_type')==='') {
					$Return['error'] = "Ob type field is required.";
				}
			}
			else if($this->input->post('schedule_to')=='employee') {
				if(!$this->input->post('employee_id')) {
					$Return['error'] = "The employees field is required.";
				} else if($this->input->post('start_date')==='') {
					$Return['error'] = "The start date field is required.";
				} else if($this->input->post('end_date')==='') {
					$Return['error'] = "The end_date field is required.";
				} else if(!$this->input->post('attendance_status')) {
					$Return['error'] = "The attendance status field is required.";
				} else if(strtotime($start_date) >  strtotime($end_date)) {
					$Return['error'] = "The end date should be greater than start date.";
				} else if($this->input->post('reason')==='') {
					$Return['error'] = "The reason field is required.";
				} else if($this->input->post('start_time')==='') {
					$Return['error'] = "The start time field is required.";
				} else if($this->input->post('end_time')==='') {
					$Return['error'] = "The end time field is required.";
				} else if(strtotime($start_time) >  strtotime($end_time)) {
					$Return['error'] = "The end time should be greater than start time.";
				}else if($this->input->post('ob_type')==='') {
					$Return['error'] = "Ob type field is required.";
				}
			}
			$employee_ids=$this->input->post('employee_id');
			$location_id=0;
			if($this->input->post('location_id')){
				$location_id=$this->input->post('location_id');
			}
			$department_id=0;
			if($this->input->post('department_id')){
				$department_id=$this->input->post('department_id');
			}

			$c_in = new DateTime($start_time);
			$c_out = new DateTime($end_time);
			$interval = $c_in->diff($c_out);
			$hours   = $interval->format('%h');
			$minutes = $interval->format('%i');
			$total_hours=$hours.'h '.$minutes.'m';
			$total_work=$hours.':'.$minutes;
			$total_rest = decimalHourswithoutround($total_work);
			$rand=strtotime(date('Y-m-d H:i:s'));
			foreach($employee_ids as $emp){
				$user = $this->Xin_model->read_user_info($emp);
				$reporting_manager=$user[0]->reporting_manager;
				if($reporting_manager=='' || $reporting_manager==0){
					$Return['error'].= "No reporting manager assign to ".change_fletter_caps($user[0]->first_name.' '.$user[0]->middle_name.' '.$user[0]->last_name).".<br>";
				}
			}

			if($Return['error']!=''){
				$this->output($Return);
			}

			foreach($employee_ids as $emp){
				$user = $this->Xin_model->read_user_info($emp);
				$reporting_id=$user[0]->reporting_manager;
				if($location_id==0){
					$location_id=$user[0]->office_location_id;
				}
				$date_of_joining=$user[0]->date_of_joining;
				$department_id=$user[0]->department_id;
				$data = array(
					'manual_attendance_to' 		=> $this->input->post('schedule_to'),
					'employee_id' 				=> $emp,
					'location_id' 				=> $location_id,
					'department_id' 			=> $department_id,
					'start_date' 				=> $start_date,
					'end_date' 					=> $end_date,
					'ob_type_id' 				=> $ob_type,
					'total_hours' 				=> $total_hours,
					'end_time' 					=> $end_time,
					'start_time' 				=> $start_time,
					'total_rest' 				=> $total_rest,
					'unique_code'				=> $rand,
					'attendance_status' 		=> $attendance_status,
					'created_by' 				=> $this->input->post('user_id'),
					'created_at' 				=> date('Y-m-d H:i:s'),
					'updated_at' 				=> date('Y-m-d H:i:s'),
					'reason' 					=> htmlspecialchars($reason,ENT_QUOTES),
					'reporting_manager_id' 		=>$reporting_id,
					'hr_head_status' 			=> 1,
					'reporting_manager_status'	=> 1
				);
				$result = $this->Timesheet_model->add_manual_attendance($data);
				/*User Logs*/
				$affected_id= table_max_id('xin_manual_attendance','manual_attendance_id');
				userlogs('Timesheet-Manual Attendance-Add','New Manual Attendance Added',$affected_id['field_id'],$affected_id['datas']);
				/*User Logs*/
			}

			if ($result == TRUE) {
				$Return['result'] = 'Manual Attendance added.';
				/*$Return['result'] = 'Manual Attendance added and send for approval.';
				$query_ml = $this->db->query("select emp.first_name,emp.middle_name,emp.last_name,emp.user_id,emp.email from xin_user_roles as role left join xin_employees as emp on emp.user_role_id=role.role_id  where role_resources like '%32m%' OR emp.custom_roles like '%32m%'");
				$result_ml= $query_ml->result();
				$mail_res=[];
				if($result_ml){
					foreach($result_ml as $res_ml){
						$mail_res[$res_ml->user_id]=$res_ml;
					}
				}
				$hr_array_merge=$mail_res;
				//HR MAIL RECIEVER
				$rep_manager_id=[];
				foreach($employee_ids as $emps){
					$user1 = $this->Xin_model->read_user_info($emps);
					$reporting_manager=$user1[0]->reporting_manager;
					$rep_manager_id[$reporting_manager][]=$emps;
				}

				$setting = $this->Xin_model->read_setting_info(1);
				$this->email->set_mailtype("html");
				if($rep_manager_id){
					$sender_info = $this->Xin_model->read_user_info($this->input->post('user_id'));
					$cinfo = $this->Xin_model->read_company_setting_info(1);
					//get email template
					$template = $this->Xin_model->read_email_template_info_bycode('Manual Attendance');
					$head_title=$template[0]->subject;
					$subject=$template[0]->subject;
					$sender_name=change_fletter_caps($sender_info[0]->first_name.' '.$sender_info[0]->middle_name.' '.$sender_info[0]->last_name);
					$sender_designation = $this->Designation_model->read_designation_information($sender_info[0]->designation_id);
					$content=$reason;
					//Reporting Manager
					foreach($rep_manager_id as $key=>$rep){
						$rp_data = $this->Xin_model->read_user_info($key);
						$rp_email=$rp_data[0]->email;
						$rp_name=change_fletter_caps($rp_data[0]->first_name.' '.$rp_data[0]->middle_name.' '.$rp_data[0]->last_name);
						$manual_attn_structure='';
						$manual_attn_structure.='<div><table border="1" style="padding:10px;width: 100%;text-align:center;font-size: 13px;line-height: 20px;"><tbody>
                        <tr><td colspan="7" style="text-align:center;background: #cc6076;color: #ffffff;"><strong>Manual Attendance Approvals for below employees</strong></td></tr>
                        <tr style="background: #cc6076;color: #ffffff;"><td><strong>Employee Name</strong></td><td><strong>Location</strong><td><strong>Department</strong></td><td><strong>Date</strong></td><td><strong>Time (hours)</strong></td><td><strong>Attendance Status</strong></td><td><strong>Created At</strong></td></td></tr>';
						if($start_date==$end_date){
							$date_sl=format_date('d F Y',$start_date);
						}else{
							$date_sl=format_date('d F Y',$start_date).' to '.format_date('d F Y',$end_date);
						}

						$timing=$start_time.' to '.$end_time.' ('.$total_hours.')';
						foreach($rep as $rp_emp){
							$user = $this->Xin_model->read_user_info($rp_emp);
							$department = $this->Department_model->read_department_information($user[0]->department_id);
							$location = $this->Xin_model->read_location_info($user[0]->office_location_id);

							$manual_attn_structure.='<tr class=""><td>'.change_fletter_caps($user[0]->first_name.' '.$user[0]->middle_name.' '.$user[0]->last_name).'</td><td>'.$location[0]->location_name.'</td><td>'.$department[0]->department_name.'</td><td>'.$date_sl.'</td><td>'.$timing.'</td><td>'.$attendance_status.'</td><td>'.date('Y-m-d H:i:s').'</td></tr>';
						}
						$manual_attn_structure.='</tbody></table></div>';
						$approve_link=$_SERVER['HTTP_HOST'].base_url().'index/attn_approval/'.base64_encode($key.'/'.$rand.'/1/'.$this->input->post('user_id').'/Reporting-Manager');
						$decline_link=$_SERVER['HTTP_HOST'].base_url().'index/attn_approval/'.base64_encode($key.'/'.$rand.'/2/'.$this->input->post('user_id').'/Reporting-Manager');
						//if($setting[0]->enable_email_notification == 'yes') {
						$message = '<div style="background: #f7eaea;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin: 0 auto;padding:20px;max-width: 65em;border: 2px solid #D40732;">'.
							str_replace(array(
								"{var head_title}",
								"{var head_name}",
								"{var content}",
								"{var sender_name}",
								"{var sender_designation}",
								"{var html_structure}",
								"{var site_url}",
								"{var approve_link}",
								"{var decline_link}",
							),
								array(
									$head_title,
									$rp_name.' [Reporting Manager]',
									$content,
									$sender_name,
									$sender_designation[0]->designation_name,
									$manual_attn_structure,
									'',
									'<a href="http://'.$approve_link.'" target="_blank" style="background-color:green;padding: 8px;color: white;font-weight: bold;">Approve</a>',
									'<a href="http://'.$decline_link.'" target="_blank" style="background-color:red;padding: 8px;color: white;font-weight: bold;">Decline</a>',
								),
								htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';

						if(TESTING_MAIL==TRUE){
							$this->email->from(FROM_MAIL, $sender_info[0]->first_name.' '.$sender_info[0]->middle_name.' '.$sender_info[0]->last_name);
							$this->email->to(TO_MAIL);
						}else{
							$this->email->from($sender_info[0]->email, $sender_info[0]->first_name.' '.$sender_info[0]->middle_name.' '.$sender_info[0]->last_name);
							$this->email->to($rp_email);
						}

						$this->email->subject($subject);
						$this->email->message($message);
						//$this->email->send();
						//$Return['message']=$message;
						//}
					}
					//Reporting Manager

					//HR Administartor
					foreach($hr_array_merge as $hr_admin){
						$hr_id=$hr_admin->user_id;
						$hr_email=$hr_admin->email;
						$hr_name=change_fletter_caps($hr_admin->first_name.' '.$hr_admin->middle_name.' '.$hr_admin->last_name);
						$manual_attn_structure='';
						$manual_attn_structure.='<div><table border="1" style="padding:10px;width: 100%;text-align:center;font-size: 13px;line-height: 20px;"><tbody>
                        <tr><td colspan="7" style="text-align:center;background: #cc6076;color: #ffffff;"><strong>Manual Attendance Approvals for below employees</strong></td></tr>
                        <tr style="background: #cc6076;color: #ffffff;"><td><strong>Employee Name</strong></td><td><strong>Location</strong><td><strong>Department</strong></td><td><strong>Date</strong></td><td><strong>Time (hours)</strong></td><td><strong>Attendance Status</strong></td><td><strong>Created At</strong></td></td></tr>';

						if($start_date==$end_date){
							$date_sl=format_date('d F Y',$start_date);
						}else{
							$date_sl=format_date('d F Y',$start_date).' to '.format_date('d F Y',$end_date);
						}
						$timing=$start_time.' to '.$end_time.' ('.$total_hours.')';
						foreach($employee_ids as $emp){
							$user = $this->Xin_model->read_user_info($emp);
							$department = $this->Department_model->read_department_information($user[0]->department_id);
							$location = $this->Xin_model->read_location_info($user[0]->office_location_id);
							$manual_attn_structure.='<tr class=""><td>'.change_fletter_caps($user[0]->first_name.' '.$user[0]->middle_name.' '.$user[0]->last_name).'</td><td>'.$location[0]->location_name.'</td><td>'.$department[0]->department_name.'</td><td>'.$date_sl.'</td><td>'.$timing.'</td><td>'.$attendance_status.'</td><td>'.date('Y-m-d H:i:s').'</td></tr>';
						}
						$manual_attn_structure.='</tbody></table></div>';


						$approve_link=$_SERVER['HTTP_HOST'].base_url().'index/attn_approval/'.base64_encode($hr_id.'/'.$rand.'/1/'.$this->input->post('user_id').'/HR-Administartor');
						$decline_link=$_SERVER['HTTP_HOST'].base_url().'index/attn_approval/'.base64_encode($hr_id.'/'.$rand.'/2/'.$this->input->post('user_id').'/HR-Administartor');
						//if($setting[0]->enable_email_notification == 'yes') {
						$message = '<div style="background: #f7eaea;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin: 0 auto;padding:20px;max-width: 65em;border: 2px solid #D40732;">'.
							str_replace(array(
								"{var head_title}",
								"{var head_name}",
								"{var content}",
								"{var sender_name}",
								"{var sender_designation}",
								"{var html_structure}",
								"{var site_url}",
								"{var approve_link}",
								"{var decline_link}",
							),
								array(
									$head_title,
									$hr_name.' [HR Administartor]',
									$content,
									$sender_name,
									$sender_designation[0]->designation_name,
									$manual_attn_structure,
									'',
									'<a href="http://'.$approve_link.'" target="_blank" style="background-color:green;padding: 8px;color: white;font-weight: bold;">Approve</a>',
									'<a href="http://'.$decline_link.'" target="_blank" style="background-color:red;padding: 8px;color: white;font-weight: bold;">Decline</a>',
								),
								htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';

						if(TESTING_MAIL==TRUE){
							$this->email->from(FROM_MAIL, $sender_info[0]->first_name.' '.$sender_info[0]->middle_name.' '.$sender_info[0]->last_name);
							$this->email->to(TO_MAIL);
						}else{
							$this->email->from($sender_info[0]->email, $sender_info[0]->first_name.' '.$sender_info[0]->middle_name.' '.$sender_info[0]->last_name);
							$this->email->to($hr_email);
						}

						$this->email->subject($subject);
						$this->email->message($message);
						//$this->email->send();
						//$Return['message']=$message;
						//}
					}
					//HR Administartor
				}else{
					$Return['error'] = $this->lang->line('xin_error_msg');
				}*/
				/*Email Should be place Here*/
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}
	//Add Manual Attendance


	public function add_al_expiry_leaves() {
		if($this->input->post('add_type')=='add_al_expiry_leaves') {
			$Return = array('result'=>'', 'error'=>'');
			
			$adjust_employee_id=$this->input->post('adjust_employee_id');
			$adjust_days=$this->input->post('adjust_days');
			$adjust_type_id=$this->input->post('adjust_type_id');
			$adjust_description=$this->input->post('adjust_description');
			
			if(!$adjust_employee_id) {
				$Return['error'] = "The employee field is required.";
			} else if(!$adjust_type_id) {
				$Return['error'] = "The adjust type field is required.";
			} else if(!$adjust_days) {
				$Return['error'] = "The days field is required.";
			} 		

			if($Return['error']!=''){
				$this->output($Return);
			}

			$data = array(
				'adjust_employee_id' => $adjust_employee_id,
				'adjust_days' => $adjust_days,
				'adjust_type_id' => $adjust_type_id,
				'adjust_description' => $adjust_description,
				'created_by' => $this->userSession['user_id'],
				'adjust_name' => 'leave_expired_type'
			);
			$result = $this->Timesheet_model->add_al_expiry_adjustments($data);
			/*User Logs*/
			$affected_id= table_max_id('xin_employee_adjustments','adjust_id');
			userlogs('Timesheet-AL Expiry Adjustments-Add','New AL Expiry Adjustments Added',$affected_id['field_id'],$affected_id['datas']);
			/*User Logs*/	
			if ($result == TRUE) {
				$Return['result'] = 'AL expiry adjustments added.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}


	//change shift schedule
	public function add_change_schedule() {
		if($this->input->post('add_type')=='change_schedule') {
			$Return = array('result'=>'', 'error'=>'');
			/*Check Hours*/
			$shift_in_time = new DateTime($this->input->post('shift_in_time'));
			$shift_out_time = new DateTime($this->input->post('shift_out_time'));
			$shift_out_time1 = $shift_out_time->diff($shift_in_time);
			$check_hours   = $shift_out_time1->format('%h');
			/*Check Hours*/
			if($this->input->post('schedule_to')=='department') {
				if(!$this->input->post('location_id')) {
					$Return['error'] = "The location field is required.";
				} else if(!$this->input->post('department_id')) {
					$Return['error'] = "The department field is required.";
				} else if($this->input->post('shift_in_time')==='') {
					$Return['error'] = "The shift in time field is required.";
				} else if($this->input->post('shift_out_time')==='') {
					$Return['error'] = "The shift out time field is required.";
				} else if(!$this->input->post('week_off')) {
					$Return['error'] = "The week off field is required.";
				} else if(!$this->input->post('employee_id')){
					$Return['error'] = "Select atleast one employee.";
				} else if($check_hours < 8) {
					$Return['error'] = "The shift should be minimum 8 hours.";
				}
			}
			else if($this->input->post('schedule_to')=='employee') {
				if(!$this->input->post('employee_id')) {
					$Return['error'] = "The employees field is required.";
				} else if($this->input->post('shift_in_time')==='') {
					$Return['error'] = "The shift in time field is required.";
				} else if($this->input->post('shift_out_time')==='') {
					$Return['error'] = "The shift out time field is required.";
				} else if(!$this->input->post('week_off')) {
					$Return['error'] = "The week off field is required.";
				}  else if($check_hours < 8) {
					$Return['error'] = "The shift should be minimum 8 hours.";
				}
			}

			$employee_ids=$this->input->post('employee_id');
			if($Return['error']!=''){
				$this->output($Return);
			}

			$location_id=0;
			if($this->input->post('location_id')){
				$location_id=$this->input->post('location_id');
			}

			$department_id=0;
			if($this->input->post('department_id')){
				$department_id=$this->input->post('department_id');
			}

			foreach($employee_ids as $emp){
				$user = $this->Xin_model->read_user_info($emp);
				if($location_id==0){
					$location_id=$user[0]->office_location_id;
				}
				if($department_id==0){
					$department_id=$user[0]->department_id;
				}
				$data = array(
					'change_schedule_to' => $this->input->post('schedule_to'),
					'employee_id' => $emp,
					'location_id' => $location_id,
					'department_id' => $department_id,
					'shift_in_time' => $this->input->post('shift_in_time'),
					'shift_out_time' => $this->input->post('shift_out_time'),
					'week_off' => implode(',',$this->input->post('week_off')),
					'created_by' => $this->input->post('user_id'),
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s')
				);
				$result = $this->Timesheet_model->add_change_schedule($data);
				/*User Logs*/
				$affected_id= table_max_id('xin_change_schedule','change_schedule_id');
				userlogs('Timesheet-Change Schedule-Add','New Schedule Added',$affected_id['field_id'],$affected_id['datas']);
				/*User Logs*/
			}
			if ($result == TRUE) {
				$Return['result'] = 'Schedule change added.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function get_update_employees(){
		$user_info 			= $this->Xin_model->read_user_info($this->userSession['user_id']);
		$data='';
		$data.='<label for="name">Choose the Employees</label><select name="employee_id[]" id="employee_id" multiple class="form-control" data-plugin="select_hrm" data-placeholder="Choose the Employees...">
                <option value="">&nbsp;</option>';
		foreach($this->Xin_model->read_user_info_attendance_shift('','',$user_info[0]->department_id) as $employee) {
			$data.='<option value="'.$employee->user_id.'">'.change_fletter_caps($employee->first_name.' '.$employee->middle_name.' '.$employee->last_name).'</option>';
		}
		$data.='</select>';
		echo  $data;
	}

	public function edit_manual_attendance() {
		if($this->input->post('edit_type')=='edit_manual_attendance') {
			$start_date = format_date('Y-m-d',$this->input->post('start_date'));
			$end_date = format_date('Y-m-d',$this->input->post('end_date'));
			$attendance_status=$this->input->post('attendance_status');
			$ob_type=$this->input->post('ob_type');
			$start_time=$this->input->post('start_time');
			$end_time=$this->input->post('end_time');
			$Return = array('result'=>'', 'error'=>'');

			if(!$this->input->post('employee_id')){
				$Return['error'] = "Select atleast one employee.";
			} else if($this->input->post('start_date')==='') {
				$Return['error'] = "The start date field is required.";
			} else if($this->input->post('end_date')==='') {
				$Return['error'] = "The end date field is required.";
			} else if($this->input->post('attendance_status')==='') {
				$Return['error'] = "The attendance status field is required.";
			} else if(strtotime($start_date) >  strtotime($end_date)) {
				$Return['error'] = "The end date should be greater than start date.";
			} else if($this->input->post('start_time')==='') {
				$Return['error'] = "The start time field is required.";
			} else if($this->input->post('end_time')==='') {
				$Return['error'] = "The end time field is required.";
			} else if(strtotime($start_time) >  strtotime($end_time)) {
				$Return['error'] = "The end time should be greater than start time.";
			}else if($this->input->post('ob_type') === '') {
				$Return['error'] = "The ob type field is required.";
			}

			$employee_ids=explode(',',$this->input->post('employee_id'));
			$c_in = new DateTime($start_time);
			$c_out = new DateTime($end_time);
			$interval = $c_in->diff($c_out);
			$hours   = $interval->format('%h');
			$minutes = $interval->format('%i');
			$total_hours=$hours.'h '.$minutes.'m';
			$total_work=$hours.':'.$minutes;
			$total_rest = decimalHourswithoutround($total_work);

			if($Return['error']!=''){
				$this->output($Return);
			}

			foreach($employee_ids as $emp){
				$em_id=explode('-',$emp);
				$data = array(
					'start_date' => $start_date,
					'end_date' => $end_date,
					'total_hours' => $total_hours,
					'end_time' => $end_time,
					'start_time' => $start_time,
					'total_rest' => $total_rest,
					'ob_type_id' => $ob_type,
					'attendance_status'=> $this->input->post('attendance_status'),
					'updated_at' => date('Y-m-d H:i:s'),
					'updated_by' => $this->userSession['user_id']
				);
				$result = $this->Timesheet_model->update_manual_attendance($data,$em_id[0],$em_id[1]);
				if($result!=null){
					/*User Logs*/
					$affected_id= table_update_id('xin_manual_attendance','manual_attendance_id',$result);
					userlogs('Timesheet-Manual Attendance-Update','Manual Attendance Updated',$result,$affected_id['datas']);
					/*User Logs*/
				}			
			}
			if ($result != False) {
				$Return['result'] = 'Manual Attendance updated.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function edit_change_schedule() {
		if($this->input->post('edit_type')=='edit_change_schedule') {
			/*Check Hours*/
			$shift_in_time = new DateTime($this->input->post('shift_in_time'));
			$shift_out_time = new DateTime($this->input->post('shift_out_time'));
			$shift_out_time1 = $shift_out_time->diff($shift_in_time);
			$check_hours   = $shift_out_time1->format('%h');
			/*Check Hours*/
			$Return = array('result'=>'', 'error'=>'');
			if(!$this->input->post('employee_id')){
				$Return['error'] = "Select atleast one employee.";
			} else if($this->input->post('shift_in_time')==='') {
				$Return['error'] = "The shift in time field is required.";
			} else if($this->input->post('shift_out_time')==='') {
				$Return['error'] = "The shift out time field is required.";
			} else if(!$this->input->post('week_off')) {
				$Return['error'] = "The week off field is required.";
			} else if($check_hours < 8) {
				$Return['error'] = "The shift should be minimum 8 hours.";
			}

			$employee_ids=explode(',',$this->input->post('employee_id'));
			if($Return['error']!=''){
				$this->output($Return);
			}

			foreach($employee_ids as $emp){
				$user_info=$this->Xin_model->read_user_info($emp);
				$data = array(
					'shift_in_time' => $this->input->post('shift_in_time'),
					'shift_out_time' => $this->input->post('shift_out_time'),
					'week_off' => implode(',',$this->input->post('week_off')),
					'created_by' => $this->input->post('user_id'),
					'updated_at' => date('Y-m-d H:i:s'),
					'location_id' =>$user_info[0]->office_location_id,
					'department_id' =>$user_info[0]->department_id,
				);
				$result = $this->Timesheet_model->update_change_schedule($data,$emp);
				/*User Logs*/
				$affected_id= table_update_id('xin_change_schedule','change_schedule_id',$emp);
				userlogs('Timesheet-Change Schedule-Update','Schedule Updated',$emp,$affected_id['datas']);
				/*User Logs*/
			}
			if ($result == TRUE) {
				$Return['result'] = 'Schedule change updated.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function read_manual_attendance()
	{
		$data['title'] = $this->Xin_model->site_title();
		$m_id=explode(',',$this->input->get('manual_attendance_id'));
		$id = explode('-',$m_id[0]);
		$manual_attendance_id =$id[0];
		$unique_id =$id[1];
		$result = $this->Timesheet_model->read_change_manual_attendance_information($manual_attendance_id,$unique_id);
		$data = array(
			'manual_attendance_id' => $result[0]->manual_attendance_id,
			'start_date' => $result[0]->start_date,
			'end_date' => $result[0]->end_date,
			'start_time' => $result[0]->start_time,
			'end_time' => $result[0]->end_time,
			'attendance_status' => $result[0]->attendance_status,
			'employee_id' => $result[0]->employee_id,
			'ob_type_id' => $result[0]->ob_type_id,
		);
		$data['obList'] = $this->Xin_model->getModuleList('ob_type');
		if(!empty($this->userSession)){
			$this->load->view('timesheet/dialog_office_shift', $data);
		} else {
			redirect('');
		}
	}

	public function read_change_schedule()
	{
		$data['title'] = $this->Xin_model->site_title();
		$change_schedule_id = $this->input->get('change_schedule_id');
		$result = $this->Timesheet_model->read_change_schedule_information($change_schedule_id);
		$data = array(
			'change_schedule_id' => $result[0]->change_schedule_id,
			'shift_in_time' => $result[0]->shift_in_time,
			'shift_out_time' => $result[0]->shift_out_time,
			'week_off_date' => $result[0]->week_off,
			'employee_id' => $result[0]->employee_id,
		);
		if(!empty($this->userSession)){
			$this->load->view('timesheet/dialog_office_shift', $data);
		} else {
			redirect('');
		}
	}

	public function read_change_schedule_view()
	{
		$data['title'] = $this->Xin_model->site_title();
		if($this->input->post('form')=='dt_search'){
			$schedule_user_id = $this->input->post('schedule_user_id');
			$start_dt= date('Y-m-d',strtotime($this->input->post('start_date_t')));
			$end_dt= date('Y-m-d',strtotime($this->input->post('end_date_t')));
			$result = $this->Timesheet_model->attendance_chart_view($schedule_user_id,$start_dt,$end_dt);
			$dats='';
			$dats.='<table class="table">
								<thead>
									<tr>
										<th>Date</th>
										<th>Shift Start Time</th>
										<th>Shift End Time</th>
									</tr>
								</thead>
								<tbody>';
			if($result){
				foreach($result as $res){
					$tdate = $this->Xin_model->set_date_format($res->attendance_date);
					if($res->shift_start_time!=''){
						$shift_start_time_a = new DateTime($res->shift_start_time);
						$shift_start_time_a=$shift_start_time_a->format('h:i a');
					}else{
						$shift_start_time_a='N/A';
					}
					if($res->shift_end_time!=''){
						$shift_end_time_a = new DateTime($res->shift_end_time);
						$shift_end_time_a=$shift_end_time_a->format('h:i a');
					}else{
						$shift_end_time_a='N/A';
					}
					$dats.='<tr>
										<td>'.$tdate.'</td>
										<td><span class="label bg-teal-400">'.$shift_start_time_a.'</span></td>
										<td><span class="label bg-teal-400">'.$shift_end_time_a.'</span></td>						
									</tr>';
				}
			}else{
				$dats.='<tr><td>No Data Found.</td></tr>';
			}
			$dats.='</tbody></table>';
			echo $dats;
		}
		else{
			$schedule_user_id = $this->input->get('schedule_user_id');
			$this_month=date('Y-m');
			$attn_date=salary_start_end_date($this_month);
			$start_dt=$attn_date['exact_date_start'];
			$end_dt=$attn_date['exact_date_end'];
			$result = $this->Timesheet_model->attendance_chart_view($schedule_user_id,$start_dt,$end_dt);
			$data = array(
				'schedule_user_id' => $result[0]->schedule_user_id,
				'start_dt'=>$start_dt,
				'end_dt'=>$end_dt,
				'result'=>$result
			);
			if(!empty($this->userSession)){
				$this->load->view('timesheet/dialog_office_shift', $data);
			} else {
				redirect('');
			}
		}
	}

	public function employee_manual_attendance_lists()
	{	$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("timesheet/update_attendance", $data);
		} else {
			redirect('');
		}
		$user_info 			= $this->Xin_model->read_user_info($this->userSession['user_id']);
		$draw = intval($this->input->get("draw"));
		$employee_id = $this->input->get("user_id");
		$department_value = ($this->input->get("department_value") == 'undefined') ? 0 : $this->input->get("department_value");
		$location_value   = ($this->input->get("location_value") == 'undefined') ? 0 : $this->input->get("location_value"); 
		$ob_type   	= ($this->input->get("ob_type") == 'undefined') ? 0 : $this->input->get("ob_type"); 
		$month_year = format_date('Y-m',$this->input->get("month_year"));
		$employee = $this->Xin_model->read_user_info_manual_attendance_list($employee_id,$department_value,$ob_type,$location_value,$month_year,$user_info[0]->department_id);

		$data = array();
		foreach($employee as $r) {
			$full_name = change_fletter_caps($r->first_name.' '.$r->middle_name.' '.$r->last_name);
			$department = $this->Department_model->read_department_information($r->department_id);
			$location = $this->Xin_model->read_location_info($r->office_location_id);
			$changed_id= '<label class="label bg-teal-400">'.$location[0]->location_name.' >> '.$department[0]->department_name.'<label>';
			$start_date = $this->Xin_model->set_date_format($r->start_date);
			$end_date = $this->Xin_model->set_date_format($r->end_date);
			$start_time = $r->start_time;
			$end_time = $r->end_time;
			
			if(strtoupper($r->attendance_status) == 'PRESENT'){
				$attendance_status='<span class="label" style="border: 1px solid #4CAF50;color: #4CAF50;">'.$r->attendance_status.'</span>';
			}else{
				$attendance_status='<span class="label" style="border: 1px solid #FF5722;color: #FF5722;">'.$r->attendance_status.'</span>';
			}

			if($r->hr_head_status==0){
				$hr_head_status='<span class="label bg-info">Not Approved</span>';
			}else if($r->hr_head_status==2){
				$hr_head_status='<span class="label bg-danger">Declined</span>';
			}else{
				$hr_head_status='<span class="label bg-success">Approved</span>';
			}
			if($r->reporting_manager_status==0){
				$reporting_manager_status='<span class="label bg-info">Not Approved</span>';

			}else if($r->reporting_manager_status==2){
				$reporting_manager_status='<span class="label bg-danger">Declined</span>';
			}else{

				$reporting_manager_status='<span class="label bg-success">Approved</span>';
			}
			$shift_time = $start_date . ' to '.$end_date.' <br> ('.$start_time . ' to '.$end_time.') - '.$r->total_hours;
			$data[] = array(
				$r->user_id.'-'.$r->unique_code,
				$full_name,
				$r->e_id,
				$r->type_name,
				$changed_id,
				$shift_time,
				$attendance_status,
				$hr_head_status,
				//$reporting_manager_status,
			);
		}
		$output = array(
			"draw" => $draw,
			"recordsTotal" => count($employee),
			"recordsFiltered" => count($employee),
			"data" => $data
		);
		$this->output($output);
	}


	public function al_expiry_leaves_list()
	{	$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("timesheet/leave_expiry_adjustments", $data);
		} else {
			redirect('');
		}

		
		$draw = intval($this->input->get("draw"));
		$employee_id = $this->input->get("user_id");
		$department_value = ($this->input->get("department_value") == 'undefined') ? 0 : $this->input->get("department_value");
		$location_value   = ($this->input->get("location_value") == 'undefined') ? 0 : $this->input->get("location_value"); 
		$adjust_type_id   	= ($this->input->get("adjust_type_id") == 'undefined') ? 0 : $this->input->get("adjust_type_id"); 

		$employee = $this->Xin_model->read_al_expiry_leaves_list($employee_id,$department_value,$location_value,$adjust_type_id);

		$data = array();
		foreach($employee as $r) {
			
			$full_name = change_fletter_caps($r->first_name.' '.$r->middle_name.' '.$r->last_name);
			$department = $this->Department_model->read_department_information($r->department_id);
			$location = $this->Xin_model->read_location_info($r->office_location_id);
			$changed_id= '<label class="label bg-teal-400">'.$location[0]->location_name.' >> '.$department[0]->department_name.'<label>';			
			$days = ($r->adjust_days > 1) ? $r->adjust_days.'Days' : $r->adjust_days.'Day';
			$c_full_name = change_fletter_caps($r->c_first_name.' '.$r->c_middle_name.' '.$r->c_last_name);
			$created_date = format_date('Y-M-d H:i:s',$r->created_at);
				
			$edit_perm='';
			$delete_perm='';
			
			if(in_array('al-expiry-edit',role_resource_ids())) {
				$edit_perm='<li><a class="edit-data" href="#" data-toggle="modal" data-target=".edit-modal-data" data-adjust_id="'. $r->adjust_id.'" title="Edit"><i class="icon-pencil7"></i> Edit</a></li>';
			}

			if(in_array('al-expiry-delete',role_resource_ids())) {
				$delete_perm='<li><a class="delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->adjust_id . '" title="Delete"><i class="icon-trash"></i> Delete</a></li>';
			}

			if($edit_perm === '' && $delete_perm === ''){
				$delete_perm='<li><a class="text-danger">No Permission</a></li>';
			}

			$functions = '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right"> '.$edit_perm.$delete_perm.'</ul></li></ul>';

			$data[] = array(
				$full_name,
				$r->type_name,
				$r->adjust_description,
				$days,
				$changed_id,
				$c_full_name,
				$created_date,
				$functions
				//$reporting_manager_status,
			);
		}
		$output = array(
			"draw" => $draw,
			"recordsTotal" => count($employee),
			"recordsFiltered" => count($employee),
			"data" => $data
		);
		$this->output($output);
	}


	public function employee_shift_lists()
	{
	    $data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("timesheet/change_schedule", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$user_info 			= $this->Xin_model->read_user_info($this->userSession['user_id']);
		$draw = intval($this->input->get("draw"));
		$employee_id = $this->input->get("user_id");
		$department_value = ($this->input->get("department_value") == 'undefined') ? 0 : $this->input->get("department_value");
		$location_value = ($this->input->get("location_value") == 'undefined') ? 0 : $this->input->get("location_value");
	
		$employee = $this->Xin_model->read_user_info_attendance_shift_list($employee_id,$department_value,$location_value,$user_info[0]->department_id);
		$data = array();
		foreach($employee as $r) {
			$full_name = change_fletter_caps($r->first_name.' '.$r->middle_name.' '.$r->last_name);
			$department = $this->Department_model->read_department_information($r->department_id);
			$location = $this->Xin_model->read_location_info($r->office_location_id);
			$changed_id= '<label class="label bg-teal-400">'.$location[0]->location_name.' >> '.$department[0]->department_name.'<label>';
			$shift_in_time = new DateTime($r->shift_in_time);
			$shift_out_time = new DateTime($r->shift_out_time);
			$week_offs=explode(',',$r->week_off);
			$week_off='';
			foreach($week_offs as $w_o){
				$week_off.='<span class="label bg-teal-400 mr-5">'.$w_o.'</span>';
			}
			if($r->shift_in_time == '') {
				$shift_time = '-';
			} else {
				$shift_time = $shift_in_time->format('h:i a') . ' to '.$shift_out_time->format('h:i a');
			}
			$data[] = array(
				$r->user_id,
				$full_name,
				$r->e_id,
				$changed_id,
				$shift_time,
				$week_off,
			);
		}
		$output = array(
			"draw" => $draw,
			"recordsTotal" => count($employee),
			"recordsFiltered" => count($employee),
			"data" => $data
		);
        $this->output($output);
	}

	public function employee_list_by_location_department_for_manual()
	{
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("timesheet/change_schedule", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$user_info 			= $this->Xin_model->read_user_info($this->userSession['user_id']);
		$draw = intval($this->input->get("draw"));
		$location_id = $this->input->get("location_id");
		$department_id = $this->input->get("department_id");
		$employee = $this->Xin_model->read_user_info_attendance_shift($department_id,$location_id,$user_info[0]->department_id);
		$department = $this->Department_model->read_department_information($department_id);
		$location = $this->Xin_model->read_location_info($location_id);
		$changed_id= '<label class="label bg-teal-400">'.$location[0]->location_name.' >> '.$department[0]->department_name.'<label>';
		$data = array();
		foreach($employee as $r) {
			$shift_in_time = new DateTime($r->shift_in_time);
			$shift_out_time = new DateTime($r->shift_out_time);
			$week_off='<span class="label bg-teal-400">'.$r->week_off.'</span>';
			if($r->shift_in_time == '') {
				$shift_time = '-';
			} else {
				$shift_time = $shift_in_time->format('h:i a') . ' to '.$shift_out_time->format('h:i a').' '.$week_off;
			}
			$user_id=$r->user_id;
			$full_name = change_fletter_caps($r->first_name.' '.$r->middle_name.' '.$r->last_name);
			$employee_id = $r->employee_id;
			$data[] = array(
				@$user_id,
				$employee_id,
				$full_name,
				$changed_id,
				$shift_time,
			);
		}
		$output = array(
			"draw" => $draw,
			"recordsTotal" => count($employee),
			"recordsFiltered" => count($employee),
			"data" => $data
		);
		$this->output($output);
	}

	public function employee_list_by_location_department()
	{
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("timesheet/change_schedule", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$user_info 			= $this->Xin_model->read_user_info($this->userSession['user_id']);
		$draw = intval($this->input->get("draw"));
		$location_id = $this->input->get("location_id");
		$department_id = $this->input->get("department_id");
		$employee = $this->Xin_model->read_user_info_attendance_shift($department_id,$location_id,$user_info[0]->department_id);
		$department = $this->Department_model->read_department_information($department_id);
		$location = $this->Xin_model->read_location_info($location_id);
		$changed_id= '<label class="label bg-teal-400">'.$location[0]->location_name.' >> '.$department[0]->department_name.'<label>';
		$data = array();
		foreach($employee as $r) {
			$shift_in_time = new DateTime($r->shift_in_time);
			$shift_out_time = new DateTime($r->shift_out_time);
			$week_off='<span class="label bg-teal-400">'.$r->week_off.'</span>';
			if($r->shift_in_time == '') {
				$shift_time = '-';
			} else {
				$shift_time = $shift_in_time->format('h:i a') . ' to '.$shift_out_time->format('h:i a').' '.$week_off;
			}
			$user_id=$r->user_id;
			$full_name = change_fletter_caps($r->first_name.' '.$r->middle_name.' '.$r->last_name);
			$employee_id = $r->employee_id;
			$data[] = array(
				@$user_id,
				$employee_id,
				$full_name,
				$changed_id,
				$shift_time,
			);
		}
		$output = array(
			"draw" => $draw,
			"recordsTotal" => count($employee),
			"recordsFiltered" => count($employee),
			"data" => $data
		);
		$this->output($output);
	}
	//change shift schedule

	public function biometric_users_list()
	{
		$data['title'] = $this->Xin_model->site_title();
		$data['breadcrumbs'] = 'Biometric Users List';
		$data['path_url'] = 'biometric_users_list';
		 if(in_array('bio-user-view',role_resource_ids()) || visa_wise_role_ids() != '') {
			if(!empty($this->userSession)){
				$data['subview'] = $this->load->view("timesheet/biometric_users_list", $data, TRUE);
				$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		 } else {
		 	redirect('dashboard/');
		 }
	}

	public function biometric_users()
	{	
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("timesheet/biometric_users_list", $data);
		} else {
			redirect('');
		}
		$draw = intval($this->input->get("draw"));

		$employee_id = $this->input->get("user_id");
		$department_value = $this->input->get("department_value");
		$location_value = $this->input->get("location_value");		
		$employee = $this->Xin_model->biometric_users_list($employee_id,$department_value,$location_value);
		$data = array();
		foreach($employee as $r) {
			$full_name = change_fletter_caps($r->first_name.' '.$r->middle_name.' '.$r->last_name);
			$edit_perm='';
			$delete_perm='';
			
			if(in_array('bio-user-edit',role_resource_ids())) {
				$edit_perm='<li><a class="edit-data" href="#" data-toggle="modal" data-target=".edit-modal-data" data-biometric_user_id="'. $r->id.'" title="Edit"><i class="icon-pencil7"></i> Edit</a></li>';
			}

			if(in_array('bio-user-delete',role_resource_ids())) {
				$delete_perm='<li><a class="delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->id . '" title="Delete"><i class="icon-trash"></i> Delete</a></li>';
			}

			if($edit_perm === '' && $delete_perm === ''){
				$edit_perm='<li><a class="text-danger">No Permission</a></li>';
			}

			$functions = '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right"> '.$edit_perm.$delete_perm.'</ul></li></ul>';			
		
			$data[] = array(
				$r->employee_id,
				$full_name,
				$r->location_name,
				$r->biometric_id,
				$functions,
			);
		}
		$output = array(
			"draw" => $draw,
			"recordsTotal" => count($employee),
			"recordsFiltered" => count($employee),
			"data" => $data
		);
		$this->output($output);
	}

	public function add_biometric_user() {
		if($this->input->post('form')=='add_biometric_user') {
			$Return = array('result'=>'', 'error'=>'', 'message'=>'');	
			$user_id=$this->input->post('user_id');
			$location_id=$this->input->post('location_id');
			$employee_id=$this->input->post('employee_id');
			$biometric_id=strip_tags(trim($this->input->post('biometric_id')));

			if($biometric_id != '') {
				$check_biometricid = $this->Timesheet_model->check_biometric($biometric_id,'');
			}

			if($location_id === '') {
				$Return['error'] = "Location field is required.";
			}
			else if($employee_id === '') {
				$Return['error'] = "Employee field is required.";
			}
			else if($biometric_id === '') {
				$Return['error'] = "Biometric ID field is required.";
			}
			else if(!ctype_alnum($biometric_id)) {
				$Return['error'] = "Biometric ID should be alphanumeric.";
			}
			else if($check_biometricid!=0){
				$Return['error'] = "Biometric ID already exist. Choose different one.";
			}

			if($Return['error']!=''){
				$this->output($Return);
			}
			
			$data = array(
				'employee_id' => $employee_id,
				'biometric_id' => $biometric_id,
				'office_location_id' => $location_id,
				'added_by' => $user_id,
			);

			$result = $this->Timesheet_model->add_biometric_user($data);
			
			/*User Logs*/
			$affected_id= table_max_id('xin_biometric_users_list','id');
			userlogs('Timesheet-Biometric Users List-Add','New biometric User Added',$affected_id['field_id'],$affected_id['datas']);
			/*User Logs*/		

			if ($result == TRUE) {
				$Return['result'] = 'Biometric user added.';		
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function biometric_users_list_delete() {
		if($this->input->post('form') == 'delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			/*User Logs*/
			$affected_row= table_deleted_row('xin_biometric_users_list','id',$id);
			userlogs('Timesheet-Biometric Users List-Delete','Biometric User Data Deleted',$id,$affected_row);
			/*User Logs*/
			$this->Timesheet_model->biometric_users_list_delete($id);
			if(isset($id)) {
				$Return['result'] = 'Biometric user data deleted.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
		}
	}

	public function al_expiry_delete() {
		if($this->input->post('form') == 'delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			/*User Logs*/
			$affected_row= table_deleted_row('xin_employee_adjustments','adjust_id',$id);
			userlogs('Timesheet-AL Expiry Adjustments-Delete','AL Expiry Adjustments Delete',$id,$affected_row);
			/*User Logs*/
			$this->Timesheet_model->common_delete_data($id,'xin_employee_adjustments','adjust_id');
			if(isset($id)) {
				$Return['result'] = 'AL expiry adjustments deleted.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
		}
	}

	public function read_biometric_user()
	{	
		$biometric_user_id = $this->input->get('biometric_user_id');
		$result = $this->Timesheet_model->read_biometric_user($biometric_user_id);
		$data = array(
			'id' => $result[0]->id,
			'employee_id' => $result[0]->employee_id,
			'biometric_id' => $result[0]->biometric_id,
			'office_location_id' => $result[0]->office_location_id,
			'ful_name' => $result[0]->first_name. ' '.$result[0]->middle_name. ' '.$result[0]->last_name,
			'added_by' => $result[0]->added_by,
		);
		if(!empty($this->userSession)){
			$this->load->view('timesheet/dialog_attendance', $data);
		} else {
			redirect('');
		}
	}

	public function read_al_expiry_data()
	{	
		$adjust_id = $this->input->get('adjust_id');
		$result = $this->Timesheet_model->read_al_expiry_data($adjust_id);
		$data = array(
			'adjust_id' => $result[0]->adjust_id,
			'adjust_employee_id' => $result[0]->adjust_employee_id,
			'adjust_type_id' => $result[0]->adjust_type_id,
			'adjust_days' => $result[0]->adjust_days,
			'adjust_description' => $result[0]->adjust_description,
			'full_name' => $result[0]->first_name. ' '.$result[0]->middle_name. ' '.$result[0]->last_name,
			'created_by' => $result[0]->created_by,
		);
		$data['expireList'] = $this->Xin_model->getModuleList('leave_expired_type');
		if(!empty($this->userSession)){
			$this->load->view('timesheet/dialog_attendance', $data);
		} else {
			redirect('');
		}
	}

	public function update_biometric_user_list() {
		
		if($this->input->post('edit_type')=='update_biometric_user_list') {
			$Return = array('result'=>'', 'error'=>'', 'message'=>'');			
			$biometric_inc_id=$this->input->post('biometric_inc_id');
			$location_id=$this->input->post('location_id');
			$employee_id=$this->input->post('employee_id');
			$biometric_id=strip_tags(trim($this->input->post('biometric_id')));
			$updated_by=$this->userSession['user_id'];

			if($biometric_id != '') {
				$check_biometricid = $this->Timesheet_model->check_biometric($biometric_id,$biometric_inc_id);
			}

			if($location_id === '') {
				$Return['error'] = "Location field is required.";
			}
			else if($employee_id === '') {
				$Return['error'] = "Employee field is required.";
			}
			else if($biometric_id === '') {
				$Return['error'] = "Biometric ID field is required.";
			}
			else if(!ctype_alnum($biometric_id)) {
				$Return['error'] = "Biometric ID should be alphanumeric.";
			}
			else if($check_biometricid!=0){
				$Return['error'] = "Biometric ID already exist. Choose different one.";
			}

			if($Return['error']!=''){
				$this->output($Return);
			}
			
			$data = array(
				'biometric_id' => $biometric_id,
				'office_location_id' => $location_id,
				'updated_by' => $updated_by,
			);

			$result = $this->Timesheet_model->update_biometric_user($data,$biometric_inc_id);
						
			/*User Logs*/
			$affected_id= table_update_id('xin_biometric_users_list','id',$biometric_inc_id);
			userlogs('Timesheet-Biometric Users List-Update','Biometric Users List Updated',$affected_id['field_id'],$affected_id['datas']);
			/*User Logs*/

			if ($result == TRUE) {
				$Return['result'] = 'Biometric user updated.';		
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}


	public function update_al_expiry_list() {
		
		if($this->input->post('edit_type')=='update_al_expiry_list') {
			$Return = array('result'=>'', 'error'=>'', 'message'=>'');			
			$adjust_id=$this->input->post('adjust_id');
			$adjust_days=$this->input->post('adjust_days');
			$adjust_type_id=$this->input->post('adjust_type_id');
			$adjust_description=$this->input->post('adjust_description');
			$updated_by=$this->userSession['user_id'];

			if(!$adjust_type_id) {
				$Return['error'] = "The adjust type field is required.";
			} else if(!$adjust_days) {
				$Return['error'] = "The days field is required.";
			} else if(!$adjust_id) {
				$Return['error'] = "The adjust id field is required.";
			} 

			if($Return['error']!=''){
				$this->output($Return);
			}
			
			$data = array(
				'adjust_days' => $adjust_days,
				'adjust_type_id' => $adjust_type_id,
				'adjust_description' => $adjust_description,
				'updated_by' => $this->input->post('updated_by')
			);

			$result = $this->Timesheet_model->common_update_data($adjust_id,'xin_employee_adjustments','adjust_id',$data);
						
			/*User Logs*/
			$affected_id= table_update_id('xin_employee_adjustments','adjust_id',$adjust_id);
			userlogs('Timesheet-AL Expiry Adjustments-update','New AL Expiry Adjustments Updated',$affected_id['field_id'],$affected_id['datas']);
			/*User Logs*/

			if ($result == TRUE) {
				$Return['result'] = 'AL expiry adjustments updated.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}


	public function getUserList(){

		$user_type = $this->input->post('user_type');
		if($user_type == 1){
			$all_employees = $this->Xin_model->all_active_employees();
		}else{
			$all_employees = $this->Xin_model->all_employees_in_active();
		}


		$html = '<select name="employee_id" id="employee_id" onChange="select_employee(this.value);" class="form-control" data-plugin="select_hrm" data-placeholder="Choose an Employee...">
				<option value="all">All Employees</option>';
			for($i=0;$i<count($all_employees);$i++) {
				$html.=	'<option value="'.$all_employees[$i]->user_id.'" > '.$all_employees[$i]->first_name.' '.$all_employees[$i]->middle_name.' '.$all_employees[$i]->last_name.'</option>';
			}					
										
		$html.=	'</select>';

		$data['view'] = $html;
		echo json_encode($data);

	}
}
