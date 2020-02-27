<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Common_helper
 *
 * @author Parimal
 */

define('csrf_name',$this->security->get_csrf_token_name()); 
define('csrf_hash',$this->security->get_csrf_hash());

$CI_setting =& get_instance();
$CI_setting->load->model('Xin_model');
$system_settings = $CI_setting->Xin_model->read_setting_info(1);
define('APPLICATION_NAME',$system_settings[0]->application_name);
define('DEDUCT_HOURS',$system_settings[0]->deduct_hours);
define('LUNCH_HOURS',$system_settings[0]->lunch_hours);
define('MIN_DELIVERY_COUNT',$system_settings[0]->minimum_delivery_counts);
$grace_total_hours=explode(':',$system_settings[0]->grace_hours);
$grace_total_hours=@($grace_total_hours[0] * 60) + @$grace_total_hours[1];
define('GRACE_PERIOD_MINUTES',($grace_total_hours*60));

//User Logs Storing
function userlogs($module_name,$action,$update_to,$datas='') {
	$CI =& get_instance();
	$CI->load->library('session');
	$username=$CI->session->userdata('username');
	if(@$username){$u_id=$username['user_id'];}else{$u_id=0;}
	$data=array('user_id' => $u_id,'module' => $module_name,'action' => $action,'update_to'=> $update_to,'datas'=> $datas);


	$CI->db->insert('xin_user_logs', $data);
}
//System Logs Storing
function systemlogs($module_name,$module_type,$track_id,$details='') {
	$CI =& get_instance();  
	$data=array('system_module' => $module_name,'module_type' => $module_type,'track_id' => $track_id,'details'=> $details );
	$CI->db->insert('xin_system_logs', $data);
}
function language_lists(){
	return array("Acholi","Afrikaans","Akan","Albanian","Amharic","Arabic","Ashante","Asl","Assyrian","Azerbaijani","Azeri","Bajuni","Basque","Behdini","Belorussian","Bengali","Berber","Bosnian","Bravanese","Bulgarian","Burmese","Cakchiquel","Cambodian","Cantonese","Catalan","Chaldean","Chamorro","Chao-chow","Chavacano","Chin","Chuukese","Cree","Croatian","Czech","Dakota","Danish","Dari","Dinka","Diula","Dutch","Edo","English","Estonian","Ewe","Fante","Farsi","Fijian Hindi","Finnish","Flemish","French","French Canadian","Fukienese","Fula","Fulani","Fuzhou","Ga","Gaddang","Gaelic","Gaelic-irish","Gaelic-scottish","Georgian","German","Gorani","Greek","Gujarati","Haitian Creole","Hakka","Hakka-chinese","Hausa","Hebrew","Hindi","Hmong","Hungarian","Ibanag","Ibo","Icelandic","Igbo","Ilocano","Indonesian","Inuktitut","Italian","Jakartanese","Japanese","Javanese","Kanjobal","Karen","Karenni","Kashmiri","Kazakh","Kikuyu","Kinyarwanda","Kirundi","Korean","Kosovan","Kotokoli","Krio","Kurdish","Kurmanji","Kyrgyz","Lakota","Laotian","Latvian","Lingala","Lithuanian","Luganda","Luo","Maay","Macedonian","Malay","Malayalam","Maltese","Mandarin","Mandingo","Mandinka","Marathi","Marshallese","Mien","Mina","Mirpuri","Mixteco","Moldavan","Mongolian","Montenegrin","Navajo","Neapolitan","Nepali","Nigerian Pidgin","Norwegian","Oromo","Pahari","Papago","Papiamento","Pashto","Patois","Pidgin English","Polish","Portug.creole","Portuguese","Pothwari","Pulaar","Punjabi","Putian","Quichua","Romanian","Russian","Samoan","Serbian","Shanghainese","Shona","Sichuan","Sicilian","Sinhalese","Slovak","Somali","Sorani","Spanish","Sudanese Arabic","Sundanese","Susu","Swahili","Swedish","Sylhetti","Tagalog","Taiwanese","Tajik","Tamil","Telugu","Thai","Tibetan","Tigre","Tigrinya","Toishanese","Tongan","Toucouleur","Trique","Tshiluba","Turkish","Twi","Ukrainian","Urdu","Uyghur","Uzbek","Vietnamese","Visayan","Welsh","Wolof","Yiddish","Yoruba","Yupik");
}
function blood_group(){
	return array('A+','A−','B+','B-','AB+','AB-','O+','O-');
}
function table_max_id($table_name,$field_name,$type_of_module=''){
	$CI =& get_instance();
	if($type_of_module!=''){
		$CI->db->where('type_of_module',$type_of_module);
	}
	$CI->db->select_max($field_name);
	$CI->db->limit(1);
	$query = $CI->db->get($table_name);
	$result = $query->row();
	$res=base64_encode(json_encode(array('action'=>'add','table_name'=>$table_name,'field_name'=>$field_name,'field_value'=>$result->$field_name)));

	return array('field_id'=>$result->$field_name,'datas'=>$res);
}
function table_update_id($table_name,$field_name,$id){

	$res=base64_encode(json_encode(array('action'=>'update','table_name'=>$table_name,'field_name'=>$field_name,'field_value'=>$id)));

	return array('field_id'=>$id,'datas'=>$res);
}
function table_deleted_row($table_name,$field_name,$id,$type_of_module=''){
	$CI =& get_instance();
	$result     = $CI->db->query('show COLUMNS from '.$table_name);
	$result = $result->result_object();

	if($type_of_module!=''){
		$slug=' AND type_of_module="'.$type_of_module.'"';
	}else{
		$slug='';
	}

	$result_val = $CI->db->query('select * from '.$table_name.' where '.$field_name.'='.$id.' '.$slug.' limit 1');
	$result_val = $result_val->result_object();
	$arr=array('action'=>'delete','table_name'=>$table_name,'field_name'=>$field_name,'field_value'=>$id);

	foreach($result as $res){
		$field_name=$res->Field;
		$arr['data'][$field_name]=$result_val[0]->$field_name;
	}
	return base64_encode(json_encode($arr));
}
//User Logs Storing
//Weekoff Days
function get_weekoff_days(){
	return array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
}
//Weekoff Days
//Phone No Format
function phone_numbers_code() {
	$CI =& get_instance();
	$CI->load->library('session');
	$CI->load->model('Xin_model');
	$countries=$CI->Xin_model->get_countries();
	$data=[];
	if($countries){
		foreach($countries as $count){
			$data['+'.$count->phonecode] = ['country_name' => $count->country_name , 'length' => $count->numcode ];
		}

	}
	return $data;
}
function return_country_name($id) {
	$CI =& get_instance();
	$query = $CI->db->query("SELECT country_name from xin_countries where country_id='".$id."'");
	$result=$query->result();
	if($result){
		return $result[0]->country_name;
	}else{
		return '';
	}
}
function visa_lists() {
	$CI =& get_instance();
	$CI->load->library('session');
	$CI->load->model('Xin_model');
	$visa_type = $CI->Xin_model->get_visa_under();
	return $visa_type->result();
}


function visa_wise_role_ids(){
    $CI =& get_instance();
    $CI->load->model('Xin_model');
    return $visa_wise_role_ids = $CI->Xin_model->user_role_resource($visa_wise = 1);
}


/**
 * Used in employees listing for dynamic columns
 * @return array
 */
function employees_columns($type){
	$columns = [];
	switch ($type){
		case 'profile':
			$columns = [
				'employee_id'=>['text'=>'Employee Id','selected'=>false],
				'date_of_joining'=>['text'=>'Date of Joining','selected'=>false],
				'email'=>['text'=>'Official Email','selected'=>true],
				'nationality'=>['text'=>'Nationality','selected'=>true],
				'home_address'=>['text'=>'Home Address','selected'=>false],
				'residing_address'=>['text'=>'Residing Address','selected'=>false],
				'personal_email'=> ['text'=>'Personal Email','selected'=>false],
				'contact_number'=> ['text'=>'Contact Number','selected'=>true],
				'emergency_contact'=> ['text'=>'Emergency Contact','selected'=>false],
				'date_of_birth'=> ['text'=>'Date of Birth','selected'=>false],
				'gender'=> ['text'=>'Gender','selected'=>false],
				'designation'=> ['text'=>'Designation','selected'=>true],
				'department'=> ['text'=>'Department','selected'=>true],
				'location_name'=> ['text'=>'Location','selected'=>false],
				'visa_name'=> ['text'=>'Visa Type','selected'=>false],
				'reporting_manager'=> ['text'=>'Reporting Manager','selected'=>false],
				'contract_type'=> ['text'=>'Contract Type','selected'=>false],
			];
			break;
		case 'documents':
			$columns = [
				'visa_type'=> ['text'=>'Visa','selected'=>false],
				'contract'=> ['text'=>'Contract','selected'=>false],
//				'driving_license'=> ['text'=>'Driving License','selected'=>false],
				'passport'=> ['text'=>'Passport','selected'=>false],
				'eid'=> ['text'=>'Emirates ID','selected'=>false],
				'labor_card'=> ['text'=>'Labor Card','selected'=>false],
				'medical_card'=> ['text'=>'Medical Card','selected'=>false],
				'offer_letter'=> ['text'=>'Offer Letter','selected'=>false],
			];
			break;
		case 'salary':
			$columns = [
				'basic_salary'=> ['text'=>'Basic Salary','selected'=>false],
				'house_rent_allowance'=> ['text'=>'House Rent Allowance','selected'=>false],
				'travelling_allowance'=> ['text'=>'Travelling Allowance','selected'=>false],
				'food_allowance'=> ['text'=>'Food Allowance','selected'=>false],
				'other_allowance'=> ['text'=>'Other Allowance','selected'=>false],
				'additional_benefits'=> ['text'=>'Additional Benefits','selected'=>false],
				'bonus'=> ['text'=>'Bonus','selected'=>false],
				'total_salary'=> ['text'=>'Total Salary','selected'=>false],
			];
			break;
	}
	return $columns;
}

//Phone No Format
function grace_eligible($department_id,$location_id){
	$CI_setting =& get_instance();
	$CI_setting->load->model('Xin_model');
	$system_settings = $CI_setting->Xin_model->read_setting_info(1);

	$grace_array=json_decode($system_settings[0]->grace_departments);
	$loc=$location_id.'-'.$department_id;
	if(in_array($loc,$grace_array)){
		return 1;
	}else{
		return 0;
	}

}
//Roles Permission Supply form here
function role_resource_ids(){
	$CI =& get_instance();
	$CI->load->model('Xin_model');
	return $role_resources_ids = $CI->Xin_model->user_role_resource();
}
//Roles Permission Supply form here
//Profile Percentage
function get_profile_complete_progress($id,$returnPercentageOnly = false){
	$CI =& get_instance();
	$CI->load->model('Employees_model');
	$msg_of_progress='';
	//Basic Info
	$total_basic=30;
	$basic_info_progress=$CI->Employees_model->basic_info_progress($id);
	$basic_info_progress=$total_basic-$basic_info_progress;
	if($basic_info_progress!=30){
		$msg_of_progress.='Basic Information |';
	}

	//Basic Info

	//Bank Info
	$bank_account=10;
	$bank_info_progress=$CI->Employees_model->info_progress($id,'bankaccount_id','xin_employee_bankaccount');
	if($bank_info_progress==0){
		$bank_account=0;
		$msg_of_progress.=' Bank Details |';
	}
	//Bank Info

	//Contact Info
	$contact_det=10;
	$contact_info_progress=$CI->Employees_model->info_progress($id,'contact_id','xin_employee_contacts');
	if($contact_info_progress==0){
		$contact_det=0;
		$msg_of_progress.=' Emergency Contact Details |';
	}
	//Contact Info

	//Contract Info
	$contract_det=10;
	$contract_info_progress=$CI->Employees_model->info_progress($id,'contract_id','xin_employee_contract');
	if($contract_info_progress==0){
		$contract_det=0;
		$msg_of_progress.=' Contract Details |';
	}
	//Contract Info

	//Document(imigration) Info
	$document_det=10;
	$document_info_progress=$CI->Employees_model->info_progress($id,'immigration_id','xin_employee_immigration');
	if($document_info_progress==0){
		$document_det=0;
		$msg_of_progress.=' Document Details |';
	}
	//Document Info
	//Qualification Info
	$qualification_det=10;
	$qualification_info_progress=$CI->Employees_model->info_progress($id,'qualification_id','xin_employee_qualification');
	if($qualification_info_progress==0){
		$qualification_det=0;
		$msg_of_progress.=' Qualification Details |';
	}
	//Qualification Info

	//experience Info
	$exp_det=10;
	$exp_info_progress=$CI->Employees_model->info_progress($id,'work_experience_id','xin_employee_work_experience');
	if($exp_info_progress==0){
		$exp_det=0;
		$msg_of_progress.=' Working Experience Details |';
	}
	//experience Info

	//Salary Info
	$salary_template=10;
	$salary_info_progress=$CI->Employees_model->info_progress($id,'employee_id','xin_salary_templates');
	if($salary_info_progress==0){
		$salary_template=0;
		$msg_of_progress.=' Payroll Template |';
	}
	//Salary Info

	$msg_of_progress=substr_replace($msg_of_progress, "", -1);
	if($msg_of_progress!=''){
		$msg_of_progress.=' Pending to be filled.';
	}
	//SELECT IF(`first_name`='',1,0) + IF(`middle_name`='',1,0) + IF(`address2`='',1,0) AS EmptyCols from xin_employees WHERE `user_id` = 31;
	$total_count_of_progress=$basic_info_progress+$bank_account+$contact_det+$contract_det+$document_det+$salary_template+$qualification_det+$exp_det;

	$task_progress=$total_count_of_progress;
	// task progress
	if($task_progress <= 20) {
		$progress_class = 'progress-bar-danger';
	}
	else if($task_progress >= 21 && $task_progress <= 49){
		$progress_class = 'progress-bar-warning';
	}
	else if($task_progress >= 50 && $task_progress <= 69){
		$progress_class = 'progress-bar-info';
	}
	else if($task_progress >= 70) {
		$progress_class = 'progress-bar-success';
	}
	$progress_bar='<div class="progress progress progress-xxs mt-5" data-toggle="tooltip" data-html="true" data-placement="top" title="( Profile Completion - '.$task_progress.'% )<br>'.$msg_of_progress.'">
						<div class="progress-bar '.$progress_class.' progress-bar-striped" style="width: '.$task_progress.'%">
						</div>
				   </div>';

	if($returnPercentageOnly)
		return $task_progress;
	return $progress_bar;

}
//Profile Percentage
//Biometric Address JLT
function biometric_ip(){
	return array(
		'192.168.1.21'=>'http://www.zksoftware/Service/message/',
		'192.168.1.22'=>'http://www.zksoftware/Service/message/',
		'192.168.3.23'=>'http://www.zksoftware/Service/message/');
}
//Biometric Address JLT

function get_driver_report($employee_id,$start_dt,$end_dt,$type=''){
	$CI =& get_instance();
	if($type==''){
		$query=$CI->db->query("select sum(a_t.assigned_count) as assigned_count,sum(a_t.achieved_delivery_count) as delivery_count,sum(a_t.cancellation_count) as cancellation_count,sum(a_t.cancellation_rate)cancellation_rate from xin_attendance_time as a_t where a_t.employee_id='".$employee_id."' AND a_t.attendance_date between '".$start_dt."' AND '".$end_dt."' limit 1");
		$result=$query->result();
		if($result[0]->assigned_count!=NULL){
			return array('assigned_count'=>$result[0]->assigned_count,'delivery_count'=>$result[0]->delivery_count,'cancellation_count'=>$result[0]->cancellation_count,'cancellation_rate'=>
				round(($result[0]->cancellation_count/($result[0]->delivery_count+$result[0]->cancellation_count))*100,2)

			);
		}
	}else if($type=='P'){
		$query=$CI->db->query("select count(a_t.attendance_status) as attendance_count,sum(a_t.assigned_count) as assigned_count,sum(a_t.achieved_delivery_count) as delivery_count,sum(a_t.cancellation_count) as cancellation_count,sum(a_t.cancellation_rate)cancellation_rate from xin_attendance_time as a_t where a_t.employee_id='".$employee_id."' AND a_t.attendance_date between '".$start_dt."' AND '".$end_dt."' and a_t.attendance_status='P' limit 1");
		$result=$query->result();
		if($result[0]->assigned_count!=NULL){
			return array('attendance_count'=>$result[0]->attendance_count,'assigned_count'=>$result[0]->assigned_count,'delivery_count'=>$result[0]->delivery_count,'cancellation_count'=>$result[0]->cancellation_count,'cancellation_rate'=>round(($result[0]->cancellation_count/($result[0]->delivery_count+$result[0]->cancellation_count))*100,2));
		}
	}else if($type=='attn'){
		$query=$CI->db->query("select a_t.assigned_count,a_t.achieved_delivery_count ,a_t.cancellation_count,a_t.cancellation_rate from xin_attendance_time as a_t  where a_t.employee_id='".$employee_id."' AND a_t.attendance_date='".$start_dt."' limit 1");
		return  $result=$query->result();
	}
}

function get_in_out_time_for_receptionist($check_in_time,$today_date){
	$first_shift_start_time='08:00';
	$second_shift_start_time='10:00';
	$first_shift_end_time='18:00';
	$second_shift_end_time='20:00';
	$clock_in = new DateTime($check_in_time);
	$clock_in2 = $clock_in->format('h:i');
	$before1hours=date('H:i',strtotime("-60 minute",strtotime($first_shift_start_time)));
	$after1hours=date('H:i',strtotime("+60 minute",strtotime($first_shift_start_time)));
	if((strtotime($before1hours.' '.$today_date) < strtotime($clock_in2.' '.$today_date)) && (strtotime($after1hours.' '.$today_date) > strtotime($clock_in2.' '.$today_date))){
		$in_time=$first_shift_start_time;
		$out_time=$first_shift_end_time;
	}else{
		$in_time=$second_shift_start_time;
		$out_time=$second_shift_end_time;
	}
	return array('in_time'=>$in_time,'out_time'=>$out_time);
}
//Currency Sign
function currency_sign($number,$currency) {
	$CI =& get_instance();
	$CI->load->model('Xin_model');
	$system_setting = $CI->Xin_model->read_setting_info(1);
	if($system_setting[0]->currency_position=='Prefix'){
		$sign_value = $currency.''.$number;
	} else {
		$sign_value = $number.''.$currency;
	}
	return $sign_value;
}
//Currency Sign
//Exceptional Shift
function hours_with_exceptional_date($start_date,$end_date,$check_ramadan_date,$shift_hours){
	$w_of_date=[];
	$w_of_hours='';
	if($check_ramadan_date){
		foreach($check_ramadan_date as $ramadan_date){
			$w_of_date[]=$ramadan_date['attendance_date'];
			$w_of_hours[$ramadan_date['attendance_date']]=$ramadan_date['reduced_hours'];
		}

	}

	$days_count=[];
	$start_date = new DateTime($start_date);
	$end_date = new DateTime($end_date);
	$end_date = $end_date->modify( '+1 day' );
	$interval_re = new DateInterval('P1D');
	$date_range = new DatePeriod($start_date, $interval_re ,$end_date);
	$ramadan_date=array();
	foreach($date_range as $date) {
		$attendance_date = $date->format("Y-m-d");
		if(in_array($attendance_date,$w_of_date)){
			$days_count[]=$shift_hours-$w_of_hours[$attendance_date];
		}else{
			$days_count[]=$shift_hours;
		}
	}

	return array_sum($days_count);
}
//Exceptional Shift



function sandwich_leaves($id,$department_id,$country_id,$calculated_date){
	$CI =& get_instance();
	$leave_array=[];

	foreach($calculated_date as $c_date){
		//Prev Date
		for($i=1;$i<5;$i++){
			$prev_date=date('Y-m-d',strtotime('-'.$i.' day',strtotime($c_date)));
			$sql = "SELECT (CASE WHEN (attendance_status = 'Absent') THEN (SELECT(CASE WHEN EXISTS(SELECT leave_id FROM xin_leave_applications where employee_id = '".$id."' and reporting_manager_status = 2 and status = 2
			and '".$prev_date."' between from_date and to_date limit 1) THEN CONCAT('leave','-','0') ELSE (SELECT (
		CASE WHEN EXISTS(SELECT holiday_id FROM xin_holidays where country_id = '".$country_id."' and department_id = '".$department_id."' and is_publish = 1 and '".$prev_date."' between start_date and end_date limit 1) THEN CONCAT('holiday','-','0') ELSE (SELECT (
		CASE WHEN EXISTS(SELECT manual_attendance_id FROM xin_manual_attendance where employee_id = '".$id."' and hr_head_status = 1  and reporting_manager_status = 1 and '".$prev_date."' between start_date and end_date limit 1) THEN CONCAT('manualatten','-','0') ELSE CONCAT('manualatten','-','1') END )) END )) END ))	
		WHEN (attendance_status = 'WO') THEN (SELECT(CASE WHEN EXISTS(SELECT leave_id FROM xin_leave_applications where employee_id = '".$id."' and reporting_manager_status = 2 and status = 2
			and '".$prev_date."' between from_date and to_date limit 1) THEN CONCAT('leave','-','0') ELSE (SELECT (
		CASE WHEN EXISTS(SELECT holiday_id FROM xin_holidays where country_id = '".$country_id."' and department_id = '".$department_id."' and is_publish = 1 and '".$prev_date."' between start_date and end_date limit 1) THEN CONCAT('holiday','-','0') ELSE CONCAT('holiday','-','0') END )) END ))	
		 ELSE CONCAT('attendance','-','0') END ) as 'attendance_status' FROM xin_attendance_time where employee_id = '".$id."' and  attendance_date = '".$prev_date."' limit 1";
			$prev_query=$CI->db->query($sql);
			$prev_result=$prev_query->result();
			if($prev_result){
				$prev_res=explode('-',$prev_result[0]->attendance_status);
				$prev_status=$prev_res[0];
				$prev_value=$prev_res[1];
				if($prev_value==1){
					$leave_array[]=$c_date;
					break;
				}else{
					if($prev_status!='holiday'){
						break;
					}else if($prev_status=='manualatten'){
						break;
					}
				}
			}
		}
		//Prev Date
		//Next Date

		if(!$leave_array){
			for($i=1;$i<5;$i++){
				$next_date=date('Y-m-d',strtotime('+'.$i.' day',strtotime($c_date)));
				$sql = "SELECT (CASE WHEN (attendance_status = 'Absent') THEN (SELECT(CASE WHEN EXISTS(SELECT leave_id FROM xin_leave_applications where employee_id = '".$id."' and reporting_manager_status = 2 and status = 2
			and '".$next_date."' between from_date and to_date limit 1) THEN CONCAT('leave','-','0') ELSE (SELECT (
		CASE WHEN EXISTS(SELECT holiday_id FROM xin_holidays where country_id = '".$country_id."' and department_id = '".$department_id."' and is_publish = 1 and '".$next_date."' between start_date and end_date limit 1) THEN CONCAT('holiday','-','0') ELSE (SELECT (
		CASE WHEN EXISTS(SELECT manual_attendance_id FROM xin_manual_attendance where employee_id = '".$id."' and hr_head_status = 1  and reporting_manager_status = 1 and '".$next_date."' between start_date and end_date limit 1) THEN CONCAT('manualatten','-','0') ELSE CONCAT('manualatten','-','1') END )) END )) END ))	
		WHEN (attendance_status = 'WO') THEN (SELECT(CASE WHEN EXISTS(SELECT leave_id FROM xin_leave_applications where employee_id = '".$id."' and reporting_manager_status = 2 and status = 2
			and '".$next_date."' between from_date and to_date limit 1) THEN CONCAT('leave','-','0') ELSE (SELECT (
		CASE WHEN EXISTS(SELECT holiday_id FROM xin_holidays where country_id = '".$country_id."' and department_id = '".$department_id."' and is_publish = 1 and '".$next_date."' between start_date and end_date limit 1) THEN CONCAT('holiday','-','0') ELSE CONCAT('holiday','-','0') END )) END ))	
		 ELSE CONCAT('attendance','-','0') END ) as 'attendance_status' FROM xin_attendance_time where employee_id = '".$id."'
	  and attendance_date = '".$next_date."' limit 1";
				$next_query=$CI->db->query($sql);
				$next_result=$next_query->result();
				if($next_result){
					$next_res=explode('-',$next_result[0]->attendance_status);
					$next_status=$next_res[0];
					$next_value=$next_res[1];
					if($next_value==1 && $next_status!='manualatten'){
						$leave_array[]=$c_date;//$next_date
						break;
					}else{
						if($next_status!='holiday'){
							break;
						}else if($next_status=='manualatten'){
							break;
						}
					}
				}
			}
		}
		//Next Date

	}

	return $leave_array;

}

function required_working_hours($shift_hours,$p_date,$check_ramadan_date){
	$ramadan_days_count=count($check_ramadan_date);
	$attendance=explode('-',$p_date);
	$days_count_per_month=cal_days_in_month(CAL_GREGORIAN,$attendance[1],$attendance[0]);
	if($ramadan_days_count==0){
		return $days_count_per_month*$shift_hours;
	}else{
		$attendance=salary_start_end_date($p_date);
		$start_date_a = $attendance['exact_date_start'];
		$end_date_a = $attendance['exact_date_end'];
		$add_count=hours_with_exceptional_date($start_date_a,$end_date_a,$check_ramadan_date,$shift_hours);
		return $add_count;
	}
}

function check_if_approval($approval,$type_of_approval){
	foreach($approval as $ap){
		if($ap->type_of_approval==$type_of_approval){
			return 1;
		}
	}

}

function randomPassword() {
	$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
	$pass = array(); //remember to declare $pass as an array
	$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	for ($i = 0; $i < 8; $i++) {
		$n = rand(0, $alphaLength);
		$pass[] = $alphabet[$n];
	}
	return implode($pass); //turn the array into a string
}

function convert_hours_seconds($time){
	$hms = explode(":",$time);
	$res = (($hms[0]*60*60) + ($hms[1]*60));// + ($hms[2]/3600)
	return $res;

}

function decimalHourswithoutround($time){
	$hms = explode(":", $time);
	$res = ($hms[0] + (@$hms[1]/60));// + ($hms[2]/3600)
	return $res;
}

function decimalHours_reverse($time){
	$hms = explode(".", $time);
	$res = ($hms[0].'.'.((@$hms[1]*60)/100));// + ($hms[2]/3600)
	return round($res,2);
}

function decimalHours_reversewith_symbol($time){
	$hms = explode(".", @$time);
	$r =round(((@$hms[1]*60)/100));
	$res = (@$hms[0].':'.$r);// + ($hms[2]/3600)
	return $res;
}

function decimalHours($time){
	$hms = explode(":", $time);
	$res = (@$hms[0] + (@$hms[1]/60));// + ($hms[2]/3600)
	return round(@$res,2);
}

function decimalHoursFormat($time){
	$hms = explode(":", $time);
	if($hms[0]==''){$hrms1=0;}else{$hrms1=$hms[0];}
	if($hms[1]==''){$hrms2=0;}else{$hrms2=$hms[1];}
	return $res = $hrms1.'h '.$hrms2.'m';
}

function decimalHoursFormatDot($time){
	$hms = explode(".", $time);
	if($hms[0]==''){$hrms1=0;}else{$hrms1=$hms[0];}
	if($hms[1]==''){$hrms2=0;}else{$hrms2=$hms[1];}
	return $res = $hrms1.'h '.$hrms2.'m';
}

function addDateTime($time1,$time2){
	$x = new DateTime($time1);
	$y = new DateTime($time2);

	$interval1 = $x->diff(new DateTime('00:00')) ;
	$interval2 = $y->diff(new DateTime('00:00')) ;

	$e = new DateTime('00:00');
	$f = clone $e;
	$e->add($interval1);
	$e->add($interval2);
	$total = $f->diff($e)->format("%H:%i");
	return $total;
}
function isLateCrossedGraceTime($time){
	$graceTime = new DateTime('00:30');
	$x = new DateTime($time);
	return ($x > $graceTime) ? $x->format("H:i") : "00:00" ;
}
function decimalHoursFormat_h($time){
	$hms = explode(".", $time);
	if(@$hms[0]==''){$hrms1=0;}else{$hrms1=$hms[0];}
	if(@$hms[1]==''){$hrms2=0;}else{
		$hrms2=round(((($time-$hms[0])*3600)/60));
	}
	return $res = $hrms1.'h '.$hrms2.'m';
}
function rate_per_hour_contract_bonus($user_id,$exact_date_start,$exact_date_end,$required_working_hours){
	$CI =& get_instance();
	$CI->load->model('Payroll_model');
	$check_end_effective_date = $CI->Payroll_model->read_template_end_effective_date($user_id);
	$rate_per_hour_contract_bonus=[];
	foreach($check_end_effective_date as $salary_d){
		$salary_effective_from_date=$salary_d->effective_from_date;
		if($salary_d->effective_to_date!=''){
			$salary_effective_to_date=$salary_d->effective_to_date;
		}else{
			$salary_effective_to_date=date('Y-m-d',strtotime('+2 month',strtotime(TODAY_DATE)));
		}
		if(strtotime($salary_effective_from_date) > strtotime($exact_date_start)){
			$from_date=$salary_effective_from_date;
		}else{
			$from_date=$exact_date_start;
		}
		if(strtotime($salary_effective_to_date) < strtotime($exact_date_end)){
			$to_date=$salary_effective_to_date;
		}else{
			$to_date=$exact_date_end;
		}
		if(strtotime($from_date) < strtotime($to_date)){
			$salary_with_bonus=$salary_d->salary_with_bonus;
			$rate_per_hour_contract_bonus[]=array('from_date'=>$from_date,'to_date'=>$to_date,'rate_per_hour'=>($salary_with_bonus/$required_working_hours));
		}
	}
	return $rate_per_hour_contract_bonus;
}

function get_rate_per_hours($attendance_date,$rate_per_hour_contract_bonus){

	if($rate_per_hour_contract_bonus){
		foreach($rate_per_hour_contract_bonus as $rate_per_hour){
			if((strtotime($rate_per_hour['from_date']) <= strtotime($attendance_date)) && (strtotime($rate_per_hour['to_date']) >= strtotime($attendance_date))){
				return $rate_per_hour['rate_per_hour'];
			}

		}
	}



}

function annual_leave_balance($emp_id,$joining_date,$finished_date){
	$finished_date = new DateTime('now');
	$finished_date = $finished_date->format('Y-m-d');
	$CI =& get_instance();
	$CI->load->model('Timesheet_model');
	$annual_leave_start_date=date('Y-m-d',strtotime(ANNUAL_LEAVE_ALLOW,strtotime($joining_date)));
	$annual_leave_full_start_date=date('Y-m-d',strtotime(ANNUAL_LEAVE_ALLOW_FULL,strtotime($joining_date)));
	//Annual Leave
	$annual_leave='Annual Leave';
	$annual_leave_id=$CI->Timesheet_model->read_leave_type_id($annual_leave);
	$sql = "SELECT * FROM `xin_leave_applications` WHERE `employee_id` = '".$emp_id."' AND 
			   `leave_type_id`='".$annual_leave_id."' AND (status=2 OR status=1) AND (reporting_manager_status=2 OR reporting_manager_status=1) order by created_at ASC";
	$annual_query = $CI->db->query($sql);
	$annual_result = $annual_query->result();
	//Annual Leave
	$leave_availed=0;
	$d1 = new DateTime($joining_date);
	$d2 = new DateTime($finished_date);
	if(strtotime($finished_date)  >= strtotime($annual_leave_start_date)){
		if(strtotime($finished_date)  >= strtotime($annual_leave_full_start_date)){
			$add_value=2.5;
			$total_leave_annual=round((($d1->diff($d2)->days/365)*30),2);
		}
		else{
			$add_value=2;
			$total_leave_annual=round((($d1->diff($d2)->days/365)*24),2);
		}

		$query = $CI->db->query("SELECT sum(count_of_days) as spent_annual_days from xin_leave_applications where employee_id = '".$emp_id."' and leave_type_id='".$annual_leave_id."' and (status=2 OR status=1) AND (reporting_manager_status=2 OR reporting_manager_status=1) limit 1");
		$result=$query->result();
		if($result[0]->spent_annual_days!=''){
			$used_annual_days=$result[0]->spent_annual_days;
		}else{
			$used_annual_days=0;
		}
		//you have to include conversion leaves count remember

		//Conversion leave counts
		$leave_conversion_query = $CI->db->query("SELECT * FROM `xin_leave_conversion_count` WHERE `employee_id` = '".$emp_id."' AND 
			leave_conversion_count!=0 AND leave_conversion_count!=''  AND approved_status=1 order by added_date ASC");
		$leave_conversion_result = $leave_conversion_query->result();
		//Conversion leave counts
		$leave_conversion_count=0;
		if($leave_conversion_result){
			foreach($leave_conversion_result as $l_c_res){
				$leave_conversion_count+=$l_c_res->leave_conversion_count;
			}
		}

		//Expired Leaves Counts
		$expquery = $CI->db->query("SELECT sum(adjust_days) as adjust_days from xin_employee_adjustments where adjust_employee_id = '".$emp_id."' limit 1");
		$expresult=$expquery->result();
		if($expresult[0]->adjust_days!=''){
			$expired_leaves=$expresult[0]->adjust_days;
		}else{
			$expired_leaves=0;
		}
		//Expired Leaves Counts

		$leave_availed=$total_leave_annual-($used_annual_days+$leave_conversion_count+$expired_leaves);
	}

	$total_leave_accrued=$total_leave_annual;
	$leave_availed=$total_leave_accrued-$leave_availed;
	$balance_leave=$total_leave_accrued-$leave_availed;

	if($balance_leave < 0){
		return array('total_leave_accrued'=>$total_leave_accrued,'leave_availed'=>$leave_availed,'balance_leave'=>0);
	}else{
		return array('total_leave_accrued'=>$total_leave_accrued,'leave_availed'=>$leave_availed,'balance_leave'=>$balance_leave);
	}
}

function return_count_days($start_date,$end_date){
	$start_c = new DateTime($start_date);
	$end_c = new DateTime($end_date);
	$days_c = $start_c->diff($end_c, true)->days;
	$counts_c=$days_c+1;
	if($counts_c==1){return ' ('.$counts_c.' day.)';}else if($counts_c > 1){return ' ('.$counts_c.' days.)';}else{return ' (0 day.)';}

}
function salary_start_end_date($attendance_date){
	if(strtotime($attendance_date) < strtotime('2016-05')){
		$start_date= '2012-04';
		$salary_start_date='2012-03-31'; //Give 1 day previous
	}
	else{
		$salary_start_date='2016-04-19';
		$start_date= '2016-05';
	}
	$begin = new DateTime($start_date);
	$end   = new DateTime($attendance_date);
	$attendance_date_str=strtotime($attendance_date);
	$attendance_start_date_str=strtotime($start_date);
	$due_dates=[];

	if($attendance_date_str >= $attendance_start_date_str){
		$time="";
		for($i = $begin; $i <= $end; $i->modify('+1 month')){
			$this_month=$i->format("Y-m");
			$attendance=explode('-',$this_month);
			$days=cal_days_in_month(CAL_GREGORIAN,$attendance[1],$attendance[0]);
			if($time == ""){
				$start_date=date('Y-m-d', strtotime("+1 days", strtotime($salary_start_date)));
				$time = date('Y-m-d', strtotime("+".$days." days", strtotime($salary_start_date)));
			}
			else{
				$start_date=date('Y-m-d', strtotime("+1 days", strtotime($time)));
				$time = date('Y-m-d', strtotime("+".$days." days", strtotime($time)));
			}
			$due_dates[$start_date] = $time;
		}
	}

	$exact_date_start = key( array_slice( $due_dates, -1, 1, TRUE ) );
	$exact_date_end=array_pop($due_dates);
	return array('exact_date_start'=>$exact_date_start,'exact_date_end'=>$exact_date_end);

}


function total_shift_hours($shift_id){

	if($shift_id==10){
		$shift_hours=10-LUNCH_HOURS;
	}else{
		$shift_hours=9-LUNCH_HOURS;
	}
	return $shift_hours;
}


function format_date($format,$date){
	//Y-m-d
	//d M Y
	if($date!='' && $date!='1970-01-01'){
		return date($format,strtotime($date));	//format 10 Sep 2017
	}else{
		return '';
	}
}

function get_tax_info($visa_type){
	$CI =& get_instance();
	$CI->load->model('Xin_model');
	$system_settings = $CI->Xin_model->read_setting_info(1);
	$enable_tax_calculation=$system_settings[0]->enable_tax_calculation;
	if($enable_tax_calculation=='yes'){
		$query = $CI->db->query("SELECT * from xin_module_types where type_of_module='tax_type' and type_code like '%".$visa_type."%'");
		return $result=$query->result();
	}else{
		return '';
	}
}

function attendance_status_action($attendance_date,$employee_id,$status='',$check_in_time='',$check_out_time='',$shift_start_time='',$shift_end_time='',$week_off='',$department_id='',$country_id = 1){
	$CI =& get_instance();
	$CI->load->model('Timesheet_model');
	$get_day = strtotime($attendance_date);
	$day = date('l', $get_day);
	$week_off=explode(',',$week_off);
	$check_friday=date('l', strtotime($attendance_date)); // check if Friday
	$check_date_of_joining=$CI->Timesheet_model->check_date_of_joining($employee_id); // check if Check Date Of Joining

	if(strtotime($check_date_of_joining) > strtotime($attendance_date)){ // Priority 10
		$status='NJ';
	}
	else if((in_array($day,$week_off)) && strtotime($check_date_of_joining) < strtotime($attendance_date)){	//($week_off==$day)
		$status='WO';
		$index = array_search($day,$week_off);
		$previousIndex = $index + 1;
		$previousDay =  DateTime::createFromFormat('Y-m-d', $attendance_date)->modify("-".$previousIndex." days")->format("Y-m-d");
		if(checkAbsenceOnGivenDate($previousDay,$employee_id,$department_id,$country_id))
			$status='Absent';
	}
	else if($check_in_time!=''){
		$check_in_time=date("Y-m-d H:i",strtotime($check_in_time)).':00';
		$clock_in = new DateTime($check_in_time);
		$check_out_time=date("Y-m-d H:i",strtotime($check_out_time)).':00';
		$clock_out = new DateTime($check_out_time);
		$clock_in2 = $clock_in->format('h:i a');
		$office_time =  new DateTime($shift_start_time.' '.$attendance_date);
		$office_time_new = strtotime($shift_start_time.' '.$attendance_date);
		$clock_in_time_new = strtotime($check_in_time);
		if($clock_in_time_new <= $office_time_new) {
			$status = 'P';
		}
		else if($clock_in_time_new >= $office_time_new){
			$status = 'LT';
		}
	}
	else { //if($check_authirised_leave==0 && $check_unauthirised_leave==0  && $check_sick_leave==0){ // Priority 13

		$status='Absent';
	}


	return $status;

}

function check_leave_status($attendance_date,$employee_id,$initialStatus='',$check_in_time='',$check_out_time='',$shift_start_time='',$shift_end_time='',$week_off='',$department_id='',$country_id=''){

	$CI =& get_instance();
	$CI->load->model('Timesheet_model');
	$get_day = strtotime($attendance_date);
	$day = date('l', $get_day);

	$locationName = $CI->Timesheet_model->get_location_name($employee_id);
	if($locationName=='Egypt')
		$week_off = 'Friday,Saturday';
	$week_off=explode(',',$week_off);

	$check_public_holiday=$CI->Timesheet_model->check_public_holiday($attendance_date,$department_id,$country_id); // check if Public Hoiday  return 0 or 1
	//Check Date Of Joining
	$check_date_of_joining=$CI->Timesheet_model->check_date_of_joining($employee_id); // check
	$result_val = $CI->db->query("select leave_status_code from xin_leave_applications where employee_id = '".$employee_id."' AND '".$attendance_date."' between from_date and to_date AND status=2 AND reporting_manager_status=2 limit 1");
	$status = $initialStatus;
	$result_val = $result_val->result_object();
	if($result_val){
		$status=$result_val[0]->leave_status_code;
	}
	else if(strtotime($check_date_of_joining) > strtotime($attendance_date)){ // Priority 10
		$status='NJ';
	}
	else if($check_public_holiday==1){ // Priority 12
		$status='PH';
	}
	else if((in_array($day,$week_off)) && strtotime($check_date_of_joining) < strtotime($attendance_date) ){	//&& $check_in_time==''
		$status='WO';

		$get_day_doj = strtotime($check_date_of_joining);
		$day_doj = date('l', $get_day_doj);
		$index = array_search($day,$week_off);
		$previousIndex = $index + 1;
		$previousDay =  DateTime::createFromFormat('Y-m-d', $attendance_date)->modify("-".$previousIndex." days")->format("Y-m-d");
		if(checkAbsenceOnGivenDate($previousDay,$employee_id,$department_id,$country_id) && !(in_array($day_doj,$week_off)))
			$status='Absent';
	}
	else if($check_in_time!=''){
		$check_in_time=date("Y-m-d H:i",strtotime($check_in_time)).':00';
		$clock_in = new DateTime($check_in_time);
		$clock_in2 = $clock_in->format('h:i a');
		$office_time =  new DateTime($shift_start_time.' '.$attendance_date);
		$office_time_new = strtotime($shift_start_time.' '.$attendance_date);
		$clock_in_time_new = strtotime($check_in_time);

		if($clock_in_time_new <= $office_time_new) {
			$status = 'P';
		}
		else if($clock_in_time_new >= $office_time_new){
			$status = 'LT';
		}
	}
	else {
		$result_manual = check_OB_Hours($employee_id,$attendance_date);
		if($result_manual){
			$status=$result_manual[0]->attendance_status;
		}
		else{
			$status='Absent';
		}
	}
	if(($initialStatus == 'P' || $initialStatus == 'LT') && ($status!= 'PH'))
		$status = $initialStatus;
	return $status;
}

function checkAbsenceOnGivenDate($date,$employeeId,$department_id,$country_id){
	$CI =& get_instance();
	$CI->load->model('Timesheet_model');
	$checkAttendance = $CI->Timesheet_model->attendance_details($employeeId,$date);
	$lateArrival = false;
	$wasOnLeave = false;
	$result_val = $CI->db->query("select leave_status_code from xin_leave_applications where employee_id = '".$employeeId."' AND '".$date."' between from_date and to_date AND status=2 AND reporting_manager_status=2 limit 1");
	$result_val = $result_val->result_object();
	if($result_val)
		$wasOnLeave = true;

	if($checkAttendance){
		$check_in_time=date("Y-m-d H:i",strtotime($checkAttendance[0]->clock_in)).':00';
		$clock_in = new DateTime($check_in_time);
		$clock_in2 = $clock_in->format('h:i a');
		$office_time =  new DateTime($checkAttendance[0]->shift_start_time.' '.$date);
		$office_time_new = strtotime($checkAttendance[0]->shift_start_time.' '.$date);
		$clock_in_time_new = strtotime($check_in_time);
		if($clock_in_time_new >= $office_time_new){
			$lateArrival = true;
		}
	}

	if($CI->Timesheet_model->check_public_holiday($date,$department_id,$country_id) != 1
		&& ((!$checkAttendance || $checkAttendance[0]->clock_in == ''))
		&& !check_OB_Hours($employeeId,$date)
		&& ($lateArrival == false)
		&& ($wasOnLeave == false)
	)
		return true;
	return false;
}
function secondsToTime($seconds) {

	// extract hours
	$hours = floor($seconds / (60 * 60));

	// extract minutes
	$divisor_for_minutes = $seconds % (60 * 60);
	$minutes = floor($divisor_for_minutes / 60);

	// extract the remaining seconds
	$divisor_for_seconds = $divisor_for_minutes % 60;
	$seconds = ceil($divisor_for_seconds);

	// return the final array
	$obj = array(
		"h" => (int) $hours,
		"m" => (int) $minutes,
		"s" => (int) $seconds,
	);

	return $obj;
}
function check_OB_Hours($employee_id,$attendance_date){
	$CI =& get_instance();
	$CI->load->model('Timesheet_model');
	$sql = "select attendance_status,reason,total_hours from xin_manual_attendance where employee_id = '".$employee_id."' AND '".$attendance_date."' between start_date and end_date AND hr_head_status=1 AND reporting_manager_status=1";
	$result_manual = $CI->db->query($sql);
	return $result_manual = $result_manual->result_object();
}


function attendance_status($attendance_date,$employee_id,$status='',$check_in_time='',$check_out_time='',$shift_start_time='',$shift_end_time='',$week_off='',$department_id=''){

	$CI =& get_instance();
	$CI->load->model('Timesheet_model');
	$get_day = strtotime($attendance_date);
	$day = date('l', $get_day);

	//if($status==''){
	//Check Friday
	$check_friday=date('l', strtotime($attendance_date)); // check if Friday
	//Check Public Hoiday
	$check_public_holiday=$CI->Timesheet_model->check_public_holiday($attendance_date,$department_id); // check if Public Hoiday  return 0 or 1
	//Check Leave   3 For Annual Leave
	$check_annual_leave=$CI->Timesheet_model->check_leave_type($attendance_date,$employee_id,3); // check if Annual Leave and also approved or not return 0 or 1
	//Check Termination
	//-$check_termination=$CI->Timesheet_model->check_termination($attendance_date,$employee_id); // check if Termination and also approved or not return 0 or 1
	//Check Resignation
	//-$check_resignation=$CI->Timesheet_model->check_resignation($attendance_date,$employee_id); // check if Resignation and also approved or not return 0 or 1
	//Check Leave   4 For Emergency Leave
	//-$check_emergency_leave=$CI->Timesheet_model->check_leave_type($attendance_date,$employee_id,4); // check if Emergency Leave and also approved or not return 0 or 1
	//Check Leave   5 For Sick Leave
	$check_sick_leave=$CI->Timesheet_model->check_leave_type($attendance_date,$employee_id,5); // check if Sick Leave and also approved or not return 0 or 1
	//Check Leave   6 For Maternity Leave
	$check_maternity_leave=$CI->Timesheet_model->check_leave_type($attendance_date,$employee_id,6); // check if Maternity Leave and also approved or not return 0 or 1
	//Check Leave   7 For Authorised Leave  status=2
	$check_authirised_leave=$CI->Timesheet_model->check_leave_type($attendance_date,$employee_id,7); // check if Authorised Leave and also approved or not return 0 or 1
	//Check Leave   7 For UnAuthorised Leave status!=2
	$check_unauthirised_leave=$CI->Timesheet_model->check_leave_type_unauthorised($attendance_date,$employee_id,7); // check if Authorised Leave and also approved or not return 0 or 1
	$check_unauthirised_sick_leave=$CI->Timesheet_model->check_leave_type_unauthorised($attendance_date,$employee_id,5); // check if Authorised Leave and also approved or not return 0 or 1
	//Check Date Of Joining
	$check_date_of_joining=$CI->Timesheet_model->check_date_of_joining($employee_id); // check if Check Date Of Joining
	//Check Suspended
	//-$check_suspeneded= $CI->Timesheet_model->check_suspension($attendance_date,$employee_id); // check if Suspended and also approved or not return 0 or 1



	/*if($check_termination==1){	// Priority 1
    $status='TERM';
    } else if($check_resignation==1){ // Priority 2
    $status='RESG';
    } else*/ if($check_annual_leave==1){ // Priority 3
		$status='AL';
	} else if($check_maternity_leave==1){ // Priority 4
		$status='ML';
	} else if($check_emergency_leave==1){ // Priority 5
		$status='EL';
	} /*else if($check_suspeneded==1){ // Priority 6
			$status='SUS';
			} */else if($check_sick_leave==1){ // Priority 7,8,9
		$newjoinee_sickleave_final_date=date('Y-m-d', strtotime(JOINEE_SICK_LEAVE_NOT_ALLOW, strtotime($check_date_of_joining)));
		if(strtotime($attendance_date) < strtotime($newjoinee_sickleave_final_date)){
			$status='SL-UP';
		}else{
			$count_of_sick_leave=$CI->Timesheet_model->count_of_sick_leave($employee_id,5);
			if($count_of_sick_leave <= SICKLEAVE_FULLPAID){
				$status='SL-1';
			}else if(($count_of_sick_leave > SICKLEAVE_FULLPAID) && ($count_of_sick_leave <= SICKLEAVE_HALFPAID)){
				$status='SL-2';
			}else{
				$status='SL-UP';
			}
		}
	} else if($check_authirised_leave==1){ // Priority 7,8,9
		$status='AA';
	}  else if(($check_unauthirised_leave==1) || ($check_unauthirised_sick_leave==1)){ // Priority 7,8,9
		$status='UA';
	} else if(strtotime($check_date_of_joining) > strtotime($attendance_date)){ // Priority 10
		$status='NJ';
	} else if($check_public_holiday==1){ // Priority 12
		$status='PH';
	} else if((($week_off==$day)) && strtotime($check_date_of_joining) < strtotime($attendance_date)){
		$status='WO';
	} else if($check_in_time!=''){
		$check_in_time=date("Y-m-d H:i",strtotime($check_in_time)).':00';
		$clock_in = new DateTime($check_in_time);
		$clock_in2 = $clock_in->format('h:i a');
		$office_time =  new DateTime($shift_start_time.' '.$attendance_date);
		$office_time_new = strtotime($shift_start_time.' '.$attendance_date);
		$clock_in_time_new = strtotime($check_in_time);

		if($clock_in_time_new <= $office_time_new) {
			$status = 'P';
		} else if($clock_in_time_new >= $office_time_new){
			$status = 'LT';
		}
	} else if($check_authirised_leave==0 && $check_unauthirised_leave==0  && $check_sick_leave==0){ // Priority 13
		$status='Absent';
	}
	return $status;
}

function change_to_caps($str){
	if($str!=''){
		return ucwords($str);
	}else{
		return '';
	}

}

function change_fletter_caps($str){
	if($str!=''){
		return ucwords(strtolower($str));
	}else{
		return '';
	}

}


function change_num_format($number){
	if($number!=''){
		return number_format($number, 2, '.', ',');
	}else{
		return number_format(0, 2, '.', ',');
	}

}

function check_manual_attendance_hours($employee_id,$exact_date_start,$exact_date_end,$shift_hours,$department_id,$location_id,$country_id,$rate_per_hour_contract_bonus){
	$CI_setting =& get_instance();
	$CI_setting->load->model('Timesheet_model');
	$checkquery=$CI_setting->db->query("SELECT attendance_status from `xin_manual_attendance` as `r_s` Where  `r_s`.`employee_id`='".$employee_id."' and `r_s`.`location_id`='".$location_id."' and `r_s`.`department_id`='".$department_id."' and `r_s`.`hr_head_status` = 1 and `r_s`.`reporting_manager_status` = 1  AND (r_s.start_date BETWEEN '".$exact_date_start."' AND '".$exact_date_end."') OR (r_s.end_date BETWEEN '".$exact_date_start."' AND '".$exact_date_end."') OR (r_s.start_date <= '".$exact_date_start."' AND r_s.end_date >= '".$exact_date_end."') LIMIT 1");
	$chkresult=$checkquery->result();
	if($chkresult){
		$start_date = new DateTime($exact_date_start);
		$end_date = new DateTime($exact_date_end);
		$end_date = $end_date->modify( '+1 day' );
		$interval_re = new DateInterval('P1D');
		$date_range = new DatePeriod($start_date, $interval_re ,$end_date);
		$add_hours=[];
		$ded_hours=[];
		$annual_id=$CI_setting->Timesheet_model->read_leave_type_id('Annual Leave');
		$sick_id=$CI_setting->Timesheet_model->read_leave_type_id('Sick Leave');
		$maternity_id=$CI_setting->Timesheet_model->read_leave_type_id('Maternity Leave');
		$auth_id=$CI_setting->Timesheet_model->read_leave_type_id('Authorised Absence');
		$emerg_id=$CI_setting->Timesheet_model->read_leave_type_id('Emergency Leave');
		$bereavement_id=$CI_setting->Timesheet_model->read_leave_type_id('Bereavement Leave');
		$annual_result = $sick_result = $maternity_result = $auth_result = $emerg_result = $ph_result = $bereavement_result=[];

		foreach($date_range as $date){
			$attendance_date = $date->format("Y-m-d");
			$query=$CI_setting->db->query("SELECT attendance_status,total_rest from `xin_manual_attendance` as `r_s` Where  `r_s`.`employee_id`='".$employee_id."' and `r_s`.`location_id`='".$location_id."' and `r_s`.`department_id`='".$department_id."' and `r_s`.`hr_head_status` = 1 and `r_s`.`reporting_manager_status` = 1  and ('".$attendance_date."' BETWEEN `r_s`.`start_date` and r_s.end_date)  LIMIT 1");
			$result=$query->result();
			if($result){
				$result_val=$result[0]->attendance_status;
				$result_rest=$result[0]->total_rest;
				/*check any leaves*/
				$annual_result= $CI_setting->Timesheet_model->check_leave_type_id($attendance_date,$employee_id,$annual_id,'');
				$bereavement_result= $CI_setting->Timesheet_model->check_leave_type_id($attendance_date,$employee_id,$bereavement_id,'');
				$maternity_result= $CI_setting->Timesheet_model->check_leave_type_id($attendance_date,$employee_id,$maternity_id,'');
				$sick_result= $CI_setting->Timesheet_model->check_leave_type_id($attendance_date,$employee_id,$sick_id,'');
				$auth_result= $CI_setting->Timesheet_model->check_leave_type_id($attendance_date,$employee_id,$auth_id,'');
				$emerg_result= $CI_setting->Timesheet_model->check_leave_type_id($attendance_date,$employee_id,$emerg_id,'');
				$ph_result= $CI_setting->Timesheet_model->check_public_holiday_type_id($attendance_date,$employee_id,$department_id,$country_id,'');
				/*check any leaves*/

				if(empty($annual_result) && empty($maternity_result) && empty($sick_result) && empty($ph_result) && empty($auth_result) && empty($emerg_result) && empty($bereavement_result)){
					if($result_val=='Present'){
						$add_hours[]=$result_rest;
					}
					else if($result_val=='Absent'){
						$ded_hours[]=$result_rest;
					}
				}
			}
		}
		$total_add_hours=array_sum($add_hours);
		$total_ded_hours=array_sum($ded_hours);
		$rest_hours=$total_add_hours-$total_ded_hours;
	}else{
		$rest_hours=0;
		$total_add_hours=0;
	}
	$total_rest_hours=array('rest_hours'=>$rest_hours,'tally_hours'=>$total_add_hours,'ob_salary'=>($rest_hours*$rate_per_hour_contract_bonus));
	return $total_rest_hours;
}

function get_bus_users_late($user_id,$atn_date,$location_id){
	$CI_setting =& get_instance();
	$CI_setting->load->model('Xin_model');
	$bus_transport_q = $CI_setting->db->query("SELECT company_transport from xin_employees where company_transport=1 AND user_id='".$user_id."' limit 1");
	$bus_trans_result = $bus_transport_q->result();
	$bus_users_list=@$bus_trans_result[0]->company_transport;
	$bus_lateness = $CI_setting->Xin_model->read_bus_lateness($atn_date,$location_id);
	$late_value=0;
	$late_hours=0;
	$bus_status='LT';
	if(($bus_lateness) && ($bus_users_list==1)){
		$bus_late_date=$bus_lateness[0]->bus_late_date;
		$bus_scheduled_time=$bus_lateness[0]->bus_scheduled_time;
		$bus_late_time=$bus_lateness[0]->bus_late_time;
		/*Check Hours*/
		$shift_starts = new DateTime($bus_scheduled_time);
		$bus_late_times = new DateTime($bus_late_time);
		$interval = $shift_starts->diff($bus_late_times);
		$differences=$interval->format('%h').':'.$interval->format('%i');
		$differences_h=$interval->format('%h').' h '.$interval->format('%i').' m';
		$differences_plusmin=decimalHours(date('H:i',strtotime('+'.BUS_LATE_ADJUSTMENTS.' mins',strtotime($differences))));
		$differences_minussmin=decimalHours(date('H:i',strtotime('-'.BUS_LATE_ADJUSTMENTS.' mins',strtotime($differences))));
		/*Check Hours*/
		$query = $CI_setting->db->query("SELECT replace(replace(time_late,'h ',':'),'m','') as time_late,time_late as time_late_h FROM `xin_attendance_time` WHERE `employee_id`= '".$user_id."' AND `attendance_date` = '".$bus_late_date."' AND attendance_status ='LT' limit 1");
		$result = $query->result();
		if($result){
			$user_late=decimalHours($result[0]->time_late);
			if(($differences_minussmin <=$user_late) && ($differences_plusmin >=$user_late)){
				$late_value=decimalHourswithoutround($result[0]->time_late);
				$bus_status='P';
				$late_hours=$result[0]->time_late_h;
			}else if($differences_plusmin >=$user_late){
				$late_value=decimalHourswithoutround($result[0]->time_late);
				$bus_status='P';
				$late_hours=$result[0]->time_late_h;
			}else{
				$late_value=decimalHourswithoutround($differences);
				$bus_status='LT';
				$late_hours=$differences_h;
			}
		}

	}
	$late_time=str_replace('h ',':',str_replace('m','',$late_hours));
	$TimeToSec=TimeToSec($late_time.':00');
	return array('late_rest_value'=>$late_value,'bus_status'=>$bus_status,'late_hours'=>$late_hours,'time_to_sec'=>$TimeToSec);
}

function TimeToSec($time) {
	$sec = 0;
	foreach (array_reverse(explode(':', $time)) as $k => $v) $sec += pow(60, $k) * $v;
	return $sec;
}

function calculate_salary($user_id,$shift_hours,$count_of_all_status,$rate_per_hour_contract_bonus,$rate_per_hour_contract_only,$basic_salary,$accomodation,$ann_calculation){
	$total_salary_amount = $total_empty_status = $overtime_day = $overtime_night = $total_rest_d = $count_of_attendance = 0;
	$leave_salary=[];

	foreach($count_of_all_status as $all_status){
		$attendance_status=$all_status->attendance_status;  //LT P PH WO
		$count_attendance_status=$all_status->count_attendance_status; // 2 Total count of each (LT, P ..)
		$total_rest=$all_status->total_rest; // count of all P,LT
		$overtime_day+=@$all_status->overtime_day;
		$overtime_night+=@$all_status->overtime_night;
		if($attendance_status=='P' || $attendance_status=='LT' || $attendance_status=='WO' || $attendance_status=='PH' || $attendance_status=='SL-1' || $attendance_status=='SL-2' || $attendance_status=='ML-1'  || $attendance_status=='BL'){// || $attendance_status=='ML-2'

			$total_salary_amount+=($total_rest*$rate_per_hour_contract_bonus);

			if($attendance_status=='P' && $count_attendance_status!=0){
				$leave_salary['P']=array('days'=>$count_attendance_status,'amount'=>($total_rest*$rate_per_hour_contract_bonus),'total_rest'=>$total_rest);
			}

			if($attendance_status=='LT' && $count_attendance_status!=0){
				$leave_salary['LT']=array('days'=>$count_attendance_status,'amount'=>($total_rest*$rate_per_hour_contract_bonus),'total_rest'=>$total_rest);
			}

			if($attendance_status=='WO' && $count_attendance_status!=0){
				$leave_salary['WO']=array('days'=>$count_attendance_status,'amount'=>($total_rest*$rate_per_hour_contract_bonus),'total_rest'=>$total_rest);
			}

			if($attendance_status=='PH' && $count_attendance_status!=0){
				$leave_salary['PH']=array('days'=>$count_attendance_status,'amount'=>($total_rest*$rate_per_hour_contract_bonus),'total_rest'=>$total_rest);
			}

			if($attendance_status=='BL' && $count_attendance_status!=0 &&  $ann_calculation==1){
				$leave_salary['BL']=array('days'=>$count_attendance_status,'amount'=>($total_rest*$rate_per_hour_contract_bonus),'total_rest'=>$total_rest);
			}

			if($attendance_status=='SL-1' && $count_attendance_status!=0 && $ann_calculation==1){
				$leave_salary['SL-1']=array('days'=>$count_attendance_status,'amount'=>($total_rest*$rate_per_hour_contract_bonus),'total_rest'=>$total_rest);
			}

			if($attendance_status=='SL-2' && $count_attendance_status!=0 && $ann_calculation==1){
				$leave_salary['SL-2']=array('days'=>$count_attendance_status,'amount'=>($total_rest*$rate_per_hour_contract_bonus),'total_rest'=>$total_rest);
			}

			if($attendance_status=='ML-1' && $count_attendance_status!=0 && $ann_calculation==1){
				$leave_salary['ML-1']=array('days'=>$count_attendance_status,'amount'=>($total_rest*$rate_per_hour_contract_bonus),'total_rest'=>$total_rest);
			}
			/*if($attendance_status=='ML-2' && $count_attendance_status!=0 && $ann_calculation==1){				$leave_salary['ML-2']=array('days'=>$count_attendance_status,'amount'=>($total_rest*$rate_per_hour_contract_bonus),'total_rest'=>$total_rest);
            }*/
			$total_rest_d+=$total_rest;
			$count_of_attendance+=$count_attendance_status;
		}
		else if($attendance_status=='AL'  && $ann_calculation==1){ //&& 			$annual_result_a_count==1					//$total_salary_amount+=((($basic_salary+$accomodation)*12)/365)*$count_attendance_status;
			if($count_attendance_status!=0){
				$leave_salary['AL']=array('days'=>$count_attendance_status,'amount'=>(((($basic_salary+$accomodation)*12)/365)*$count_attendance_status),'total_rest'=>$total_rest);
			}

			$total_rest_d+=0;
			$count_of_attendance+=$count_attendance_status;
		}
		else if($attendance_status==''){
			$total_empty_status+=1;
		}
	}
	return array('total_salary_amount'=>$total_salary_amount,'total_empty_status'=>$total_empty_status,'leave_salary'=>$leave_salary,'total_rest_hours'=>$total_rest_d,'total_count_of_attendance'=>$count_of_attendance,'overtime_day'=>$overtime_day,'overtime_night'=>$overtime_night);
}

function debug_error(){
	$CI =& get_instance();

	// but we can check for an error, anyway.
	$error = $CI->db->error();
	// If an error occurred, $error will now have 'code' and 'message' keys...
	if (isset($error['message'])) {
		return $error['message'];
	}
	// No error returned by the DB driver...
	return null;
}

function quote($str) {
	return sprintf("'%s'", $str);
}


function get_ceo_only()
{
	$CI_setting =& get_instance();
	$CI_setting->load->model('Employees_model');
	$CI_setting->load->library('session');
	$username=$CI_setting->session->userdata('username');
	$ceo_data=$CI_setting->Employees_model->get_ceo_only();
	$head_id=$ceo_data[0]->head_id;
	if($username['user_id']==$head_id){
		return $head_id;
	}else{
		return '';
	}

}

function hod_manager_access(){
	$CI =& get_instance();
	$CI->load->library('session');
	$username=$CI->session->userdata('username');
	/*For HOD Manager*/
	$hod_query = $CI->db->query("SELECT dept.employee_id from xin_employees as emp left join xin_departments as dept on dept.department_id=emp.department_id where dept.employee_id='".$username['user_id']."' AND dept.department_name!='MD' limit 1");
	$hod_result=$hod_query->result();
	if($hod_result){
		return $hod_result[0]->employee_id;
	}else{
		return '';
	}
	/*For HOD Manager*/
}

function rp_manager_access(){
	$CI =& get_instance();
	$CI->load->library('session');
	$username=$CI->session->userdata('username');
	/*For Reporting Manager*/
	$rp_query = $CI->db->query("SELECT DISTINCT(emp.reporting_manager) as reporting_manager from xin_employees as emp where emp.reporting_manager= '".$username['user_id']."' AND emp.is_active=1 limit 1");
	$rp_result=$rp_query->result();
	if($rp_result){
		return $rp_result[0]->reporting_manager;
	}else{
		return '';
	}
	/*For Reporting Manager*/
}


function reporting_manager_access(){
	$CI =& get_instance();
	$CI->load->library('session');
	$username=$CI->session->userdata('username');
	/*For Reporting Manager*/
	$rep_query = $CI->db->query("SELECT emp.user_id from xin_employees as emp join xin_leave_applications as lv on lv.employee_id=emp.user_id where (emp.reporting_manager='".$username['user_id']."') AND emp.is_active=1 group by lv.applied_on");
	$rep_result=$rep_query->result();
	$r_manager=[];
	if($rep_result){
		foreach($rep_result as $res){
			$r_manager[]=$res->user_id;
		}
	}
	return $r_manager;
	/*For Reporting Manager*/
}
/*For Reporting Manager*/
$rep_result=reporting_manager_access();
/*For Reporting Manager*/

function employee_default_bankaccount($id){
	$CI =& get_instance();

	$query_ml = $CI->db->query("select * from xin_employee_bankaccount where employee_id =" . "'" . $id . "' AND is_primary=1 limit 1");
	$result_ml= $query_ml->result();
	if($result_ml)
	{return $result_ml[0]->account_number;
	}else{
		return 'N/A';
	}
}

function search_array($array, $term)
{
	foreach ($array AS $key => $value) {
		if ($term==$key){
			return TRUE;
		} else {
			continue;
		}
	}

	return FALSE;
}

function check_password_note($email_id,$type,$uniqueid,$randomid){
	$CI =& get_instance();
	$oneMonth=strtotime(date('Y-m-d',strtotime('+1 month',strtotime(TODAY_DATE))));
	if($type=='reset' && $uniqueid==strtotime(TODAY_DATE)){
		$query_ml = $CI->db->query("select user_id from xin_employees as emp where emp.password='".$randomid."' AND emp.email='".$email_id."' limit 1");
		$result_ml= $query_ml->result();
		if(!$result_ml){
			return '<div class="alert alert-danger alert-bordered text-center"><span class="text-semibold ">Sorry!</span> This link was broken. Try Again!</div>';
		}

	}else if($type=='new' && $uniqueid>=strtotime(TODAY_DATE) && $uniqueid <=strtotime(TODAY_DATE)){
		$query_ml = $CI->db->query("select user_id from xin_employees as emp where emp.password='".$randomid."' AND emp.email='".$email_id."' limit 1");
		$result_ml= $query_ml->result();
		if(!$result_ml){
			return '<div class="alert alert-danger alert-bordered text-center"><span class="text-semibold ">Sorry!</span> This link was broken. Try Again!</div>';
		}

	} else{
		return '<div class="alert alert-danger alert-bordered text-center"><span class="text-semibold ">Sorry!</span> This link has been expired.</div>';
	}




}

function get_hr_mail_bylocation($location_id,$employee_id=''){
	$CI =& get_instance();
	//HR MAIL RECIEVER
	if($employee_id!=''){
		$query_ml = $CI->db->query("select emp.first_name,emp.middle_name,emp.last_name,emp.user_id,emp.email from xin_user_roles as role left join xin_employees as emp on emp.user_role_id=role.role_id  where (role_resources like '%32m%' OR emp.custom_roles like '%32m%') AND emp.office_location_id='".$location_id."' AND emp.email!='' AND emp.is_active=1 ");
		$result_ml= $query_ml->result();
		$mail_res=[];
		if($result_ml){
			foreach($result_ml as $res_ml){
				$mail_res[$res_ml->user_id]=$res_ml;
			}


			$search_array=search_array($mail_res,$employee_id);
			if($search_array==1){
				$mail_res=[];
				$query_m2 = $CI->db->query("select emp.first_name,emp.middle_name,emp.last_name,emp.user_id,emp.email from xin_departments as dept left join xin_employees as emp on emp.user_id=dept.employee_id WHERE dept.department_name='HRD' AND emp.email!='' limit 1");
				$result_m2= $query_m2->result();
				if($result_m2){
					foreach($result_m2 as $res_m2){
						$mail_res[$res_m2->user_id]=$res_m2;
					}
				}
			}
		}
		else{
			$query_m2 = $CI->db->query("select emp.first_name,emp.middle_name,emp.last_name,emp.user_id,emp.email from xin_departments as dept left join xin_employees as emp on emp.user_id=dept.employee_id WHERE dept.department_name='HRD' AND emp.email!='' AND emp.is_active=1 limit 1");
			$result_m2= $query_m2->result();
			if($result_m2){
				foreach($result_m2 as $res_m2){
					$mail_res[$res_m2->user_id]=$res_m2;
				}
			}
		}
	}
	else{
		$query_ml = $CI->db->query("select emp.first_name,emp.middle_name,emp.last_name,emp.user_id,emp.email from xin_user_roles as role left join xin_employees as emp on emp.user_role_id=role.role_id  where (role_resources like '%32m%' OR emp.custom_roles like '%32m%') AND emp.office_location_id='".$location_id."' AND emp.email!='' AND emp.is_active=1 ");
		$result_ml= $query_ml->result();
		$mail_res=[];
		if($result_ml){
			foreach($result_ml as $res_ml){
				$mail_res[$res_ml->user_id]=$res_ml;
			}
		}

	}
	return $hr_mails=$mail_res;
	//HR MAIL RECIEVER
}
function exceptional_employees($user_id){
	$CI_setting =& get_instance();
	$CI_setting->load->model('Xin_model');
	$system_settings = $CI_setting->Xin_model->read_setting_info(1);
	$exceptional_employees=json_decode($system_settings[0]->exceptional_employees);
	if(in_array($user_id,$exceptional_employees)){return 'Yes';}else{return 'No';}
}

function getFlexibleEmployees(){
	$CI_setting =& get_instance();
	$CI_setting->load->model('Xin_model');
	$settings = $CI_setting->Xin_model->read_setting_info(1);
	return json_decode($settings[0]->flexible_employees);
}
//http://94.56.94.242/api/api_http.php?username=hrms&password=qW8(e52ju(s!&senderid=AWOK&to=971553779287&text=Hello%20world&type=text&datetime=2018-07-29%2012%3A29%3A16
function sms_code_send($number='',$message='')
{
	if($number!='' && $message!=''){
		$username   = SMS_USERNAME;
		$password   = SMS_PASSWORD;
		$senderid = SMS_SENDERID;
		$text    = 'AWOK.com HR OTP code is : '.$message;
		$url = SMS_URL;
		$fields = array(
			'username'   => urlencode($username),
			'password'   => urlencode($password),
			'senderid' => urlencode($senderid),
			'to'      => urlencode($number),
			'text'    => urlencode($text),
			'type'    => 'text',
			'datetime'    => '2018-07-29%2012%3A29%3A16'
		);
		$fields_string = '';
		//url-ify the data for the POST
		foreach($fields as $key=>$value)
		{
			$fields_string .= $key.'='.$value.'&';
		}

		rtrim($fields_string,'&');

		//open connection
		$ch = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_POST,count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//execute post
		$result = curl_exec($ch);

		//close connection
		curl_close($ch);
		return $result;
	}
}

function approval_creation($employee_id,$type_of_approval='',$sub_type='',$field_id='',$state=''){
	if($employee_id!=''){//employee_id
		$CI =& get_instance();
		$CI->load->library('session');
		$session = $CI->session->userdata('username');
		$CI->load->model('Xin_model');
		$CI->load->model('Employees_model');
		$CI->load->model('Designation_model');
		$user_info=$CI->Xin_model->read_user_info($employee_id);
		$designation = $CI->Designation_model->read_designation_information($user_info[0]->designation_id);
		$designation_name=$designation[0]->designation_name;
		$ceo_data=$CI->Employees_model->get_ceo_only();
		$department_head=$CI->Employees_model->get_department_head_id($user_info[0]->department_id);
		$hr_mails=get_hr_mail_bylocation($user_info[0]->office_location_id,$employee_id);
		$reporting_manager_id=$user_info[0]->reporting_manager;//RM
		$department_head_id=$department_head[0]->head_id;//HOD
		$ceo_id=$ceo_data[0]->head_id;//CEO

		$system_settings = $CI->Xin_model->read_setting_info(1);
		$employee_approval_to_ceo=json_decode($system_settings[0]->employee_approval_to_ceo);
		$manager_level=0;
		$MANAGER_LEVEL_DESIG=unserialize(MANAGER_LEVEL_DESIG);
		if($MANAGER_LEVEL_DESIG && (!in_array($employee_id,$employee_approval_to_ceo))){
			foreach($MANAGER_LEVEL_DESIG as $manag){
				if (strpos(strtolower($designation_name),strtolower($manag)) != false) {
					$manager_level=1;
				}
			}
		}else if(in_array($employee_id,$employee_approval_to_ceo)){
			$manager_level=1;
		}

		$return_arrays=[];
		if($type_of_approval=='leave_request'){//type_of_approval
			if($sub_type=='Sick Leave' || $sub_type=='Sick Leave Unpaid'){
				//HR Only
				if($hr_mails){
					foreach($hr_mails as $key=>$value){
						$CI->db->query('insert into xin_employees_approval (employee_id,field_id,type_of_approval,head_of_approval,approval_head_id,created_by,pay_date,approval_status) values ("'.$employee_id.'","'.$field_id.'","'.$type_of_approval.'","HR Administrator","'.$key.'","'.$session['user_id'].'",1,1)');
						$return_arrays[]=array('head_id'=>$key,'head_name'=>'HR Administrator');
					}
				}
			}else{

				if($state=='first'){
					if($reporting_manager_id!=''){
						$CI->db->query('insert into xin_employees_approval (employee_id,field_id,type_of_approval,head_of_approval,approval_head_id,created_by,pay_date,approval_status) values ("'.$employee_id.'","'.$field_id.'","'.$type_of_approval.'","Reporting Manager","'.$reporting_manager_id.'","'.$session['user_id'].'",1,1)');
						$return_arrays[]=array('head_id'=>$reporting_manager_id,'head_name'=>'Reporting Manager');
					}
				}
				else{
					if(($department_head_id!=$reporting_manager_id) && ($reporting_manager_id!=$ceo_id) && ($department_head_id!=$employee_id) && ($department_head_id!=$session['user_id'])){
						$check_query=$CI->db->query('select * from xin_employees_approval where employee_id="'.$employee_id.'" AND field_id="'.$field_id.'" AND approval_head_id="'.$department_head_id.'" AND type_of_approval="leave_request"');
						$check_result=$check_query->result();
						if(empty($check_result)){
							$CI->db->query('insert into xin_employees_approval (employee_id,field_id,type_of_approval,head_of_approval,approval_head_id,created_by,pay_date,approval_status) values ("'.$employee_id.'","'.$field_id.'","'.$type_of_approval.'","HOD","'.$department_head_id.'","'.$session['user_id'].'",2,1)');
						}else{
							$CI->db->query('update xin_employees_approval set approval_status="1" where employee_id="'.$employee_id.'" AND field_id="'.$field_id.'" AND approval_head_id="'.$department_head_id.'" AND type_of_approval="leave_request"');
						}
						$return_arrays[]=array('head_id'=>$department_head_id,'head_name'=>'HOD');
					}

					if(($reporting_manager_id!=$ceo_id) && ($department_head_id!=$ceo_id) && ($manager_level==1)){

						$check_query=$CI->db->query('select * from xin_employees_approval where employee_id="'.$employee_id.'" AND field_id="'.$field_id.'" AND approval_head_id="'.$ceo_id.'" AND type_of_approval="leave_request"');
						$check_result=$check_query->result();
						if(empty($check_result)){
							$CI->db->query('insert into xin_employees_approval (employee_id,field_id,type_of_approval,head_of_approval,approval_head_id,created_by,pay_date,approval_status) values ("'.$employee_id.'","'.$field_id.'","'.$type_of_approval.'","CEO","'.$ceo_id.'","'.$session['user_id'].'",3,1)');
						}else{
							$CI->db->query('update xin_employees_approval set approval_status="1" where employee_id="'.$employee_id.'" AND field_id="'.$field_id.'" AND approval_head_id="'.$ceo_id.'" AND type_of_approval="leave_request"');
						}
						$return_arrays[]=array('head_id'=>$ceo_id,'head_name'=>'CEO');
					}
				}
			}
			return $return_arrays;
		}//type_of_approval

	}//employee_id
}
function email_notification($type){
	$CI_setting =& get_instance();
	$CI_setting->load->model('Xin_model');
	$system_settings = $CI_setting->Xin_model->read_setting_info(1);
	$enable_notifiation=json_decode($system_settings[0]->enable_email_notification);
	if($enable_notifiation){
		return $enable_notifiation->$type;
	}
}


function get_session_field($type=''){
	$CIS =& get_instance();
	$CIS->load->library('session');
	$CIS->load->model('Xin_model');
	$username=$CIS->session->userdata('username');
	$user_info=$CIS->Xin_model->read_user_info($username['user_id']);
	if($type=='location'){
		return $user_info[0]->office_location_id;
	}else{
		return $user_info[0]->user_id;
	}

}
function get_salary_fields($salary_template_id,$country_id){
	$CIS =& get_instance();
	$CIS->db->select('sal.salary_id,sal.salary_template_id,sal.salary_employee_id,sal.salary_amount,sal.updated_date,salfield.salary_field_id,salfield.salary_field_name,salfield.salary_calculate');
	$CIS->db->from('xin_employees_salary as sal');
	$CIS->db->join('xin_salary_fields_bycountry as salfield','salfield.salary_field_id=sal.salary_field_id AND sal.salary_template_id='.$salary_template_id,'right');
	//$CIS->db->where('sal.salary_template_id',$salary_template_id);
	$CIS->db->where('salfield.country_id',$country_id);
	$CIS->db->order_by('salfield.salary_field_order','ASC');
	$query=$CIS->db->get();
	return $result=$query->result();
}

function get_salary_fields_byname($salary_template_id,$salary_field){
	$CIS =& get_instance();
	$CIS->db->select('sal.*,salfield.salary_field_name,salfield.salary_calculate,salfield.country_id');$CIS->db->from('xin_employees_salary as sal');
	$CIS->db->join('xin_salary_fields_bycountry as salfield','salfield.salary_field_id=sal.salary_field_id','left');
	$CIS->db->where('sal.salary_template_id',$salary_template_id);
	$CIS->db->where('salfield.salary_field_name',$salary_field);
	$CIS->db->limit(1);
	$CIS->db->order_by('salfield.salary_field_order','ASC');
	$query=$CIS->db->get();
	return $result=$query->result();
}

function get_salary_fields_bycountry($country_id){
	$CIS =& get_instance();
	$CIS->db->select('sal.*');
	$CIS->db->from('xin_salary_fields_bycountry as sal');
	$CIS->db->where('sal.country_id',$country_id);
	$CIS->db->order_by('sal.salary_field_order','ASC');
	$query=$CIS->db->get();
	return $result=$query->result();
}

function salary_title_change($name){
	if($name!=''){
		return change_fletter_caps(str_replace('_',' ',$name));
	}
}
function decodeHumanDateTime($dateTime){
	$dateTime =str_replace('m','',str_replace('h ',':',$dateTime));
	return new DateTime($dateTime);
}
function add_ob_hours($array,$attendance_status='Present'){
	$sum=0;
	$minus=0;
	if($array){
		foreach($array as $ar){
			$ars=str_replace('m','',str_replace('h ',':',$ar));
			if($attendance_status=='Present'){
				$sum+=decimalHourswithoutround($ars);
			}else{
				$minus=decimalHourswithoutround($ars)-$minus;
			}
		}
	}
	if($attendance_status=='Present'){
		return decimalHoursFormat_h($sum);
	}else{
		return decimalHoursFormat_h(abs($minus));
	}
}


function check_365days_enabled($calendar_start_date,$calendar_end_date,$user_id,$shift_hours,$check_ramadan_date,$department_id,$country_id,$location_id,$date_of_joining,$date_of_leaving){
	// AA > 4 , EL, AL,ABSC,ML
	//RESG,TERM,SUS,NJ,EOS
	$CI =& get_instance();
	$CI->load->model('Timesheet_model');
	$annual_id=$CI->Timesheet_model->read_leave_type_id('Annual Leave');
	$maternity_id=$CI->Timesheet_model->read_leave_type_id('Maternity Leave');
	$emergency_id=$CI->Timesheet_model->read_leave_type_id('Emergency Leave');
	//$authorised_id=$CI->Timesheet_model->read_leave_type_id('Authorised Absence');
	$bereavement_id=$CI->Timesheet_model->read_leave_type_id('Bereavement Leave');
	$calendar_start_date1=strtotime($calendar_start_date);
	$calendar_end_date1=strtotime($calendar_end_date);
	$resignation_query=$CI->db->query("select resignation_id from xin_employee_resignations where employee_id='".$user_id."' and unix_timestamp(resignation_date) >='".$calendar_start_date1."'
			UNION ALL 
			select suspension_id from xin_employee_suspension where employee_id='".$user_id."' AND ((unix_timestamp(suspension_start_date) BETWEEN '".$calendar_start_date1."' AND '".$calendar_end_date1."') OR (unix_timestamp(suspension_end_date) BETWEEN '".$calendar_start_date1."' AND '".$calendar_end_date1."') OR (unix_timestamp(suspension_start_date) <= '".$calendar_start_date1."' AND unix_timestamp(suspension_end_date) >= '".$calendar_end_date1."'))	
			and status=1
			UNION ALL 
			select termination_id from xin_employee_terminations where employee_id='".$user_id."' and unix_timestamp(termination_date) >='".$calendar_start_date1."' and status=1
			UNION ALL 
			SELECT leave_id FROM xin_leave_applications WHERE employee_id = '".$user_id."' AND ((unix_timestamp(from_date) BETWEEN '".$calendar_start_date1."' AND '".$calendar_end_date1."') OR (unix_timestamp(to_date) BETWEEN '".$calendar_start_date1."' AND '".$calendar_end_date1."') OR (unix_timestamp(from_date) <= '".$calendar_start_date1."' AND unix_timestamp(to_date) >= '".$calendar_end_date1."')) AND status = 2 AND reporting_manager_status = 2 AND leave_type_id IN('".$annual_id."','".$maternity_id."','".$emergency_id."','".$bereavement_id."')");
	$resignation_result=$resignation_query->result();
	if((strtotime($calendar_start_date) <= strtotime($date_of_joining)) && (strtotime($calendar_end_date) >= strtotime($date_of_joining))){
		return 0; //Old
	}else if((strtotime($calendar_start_date) <= strtotime($date_of_leaving)) && (strtotime($calendar_end_date) >= strtotime($date_of_leaving)) && $date_of_leaving!=''){
		return 0; //Old
	}else if($resignation_result){
		return 0; //Old
	}else{
		return 1; //New
	}
}

function show_data( $data ){
	echo "<pre>";
	print_r( $data );
	echo "</pre>";
}

function wh_log($log_msg)
{
    $log_filename = "log";
    if (!file_exists($log_filename)) 
    {
        // create directory/folder uploads.
        mkdir($log_filename, 0777, true);
    }
    $log_file_data = $log_filename.'/log_' . date('d-M-Y') . '.log';
    // if you don't add `FILE_APPEND`, the file will be erased each time you add a log
    file_put_contents($log_file_data, $log_msg . "\n", FILE_APPEND);
}

function email_settings_data(){

	$mail_settings_array = array();
	$CI =& get_instance();
	$CI->load->model('Xin_model');
	$email_settings_mails = $CI->Xin_model->checkEmailSettingsData('Email Settings','Salary Certificate');
	if(!empty($email_settings_mails)){
		$mail_ids = json_decode($email_settings_mails[0]->settings_value,1);

		foreach ($mail_ids as $scm) {
			$mail_val = explode('//',$scm);
			array_push($mail_settings_array, $mail_val[0]);
		}
	}

	return $mail_settings_array;
}