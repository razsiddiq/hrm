<?php
/**
 * @Author Siddiqkhan
 *
 * @Settings Controller
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends MY_Controller {

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
		$this->load->model("Employee_exit_model");
		$this->load->model("Xin_model");
		$this->load->model("Employees_model");
		$this->load->model("Timesheet_model");
		$this->load->model("payroll_model");
		$this->userSession = $this->session->userdata('username');
	}

	public function output($Return=array()){
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}

	public function index()
	{
		$data['title'] = $this->Xin_model->site_title();
		$setting = $this->Xin_model->read_setting_info(1);
		$company_info = $this->Xin_model->read_company_setting_info(1);
		$this->load->model("Department_model");
		$grace_dep_loc=$this->Xin_model->all_departments_location();
		$data = array(
			'title' => $this->Xin_model->site_title(),
			'company_info_id' => $company_info[0]->company_info_id,
			'logo' => $company_info[0]->logo,
			'logo_second' => $company_info[0]->logo_second,
			'sign_in_logo' => $company_info[0]->sign_in_logo,
			'job_logo' => $setting[0]->job_logo,
			'payroll_logo' => $setting[0]->payroll_logo,
			'company_name' => $company_info[0]->company_name,
			'contact_person' => $company_info[0]->contact_person,
			'website_url' => $company_info[0]->website_url,
			'starting_year' => $company_info[0]->starting_year,
			'company_email' => $company_info[0]->company_email,
			'company_contact' => $company_info[0]->company_contact,
			'email' => $company_info[0]->email,
			'phone' => $company_info[0]->phone,
			'address_1' => $company_info[0]->address_1,
			'address_2' => $company_info[0]->address_2,
			'city' => $company_info[0]->city,
			'state' => $company_info[0]->state,
			'zipcode' => $company_info[0]->zipcode,
			'country' => $company_info[0]->country,
			'updated_at' => $company_info[0]->updated_at,
			'application_name' => $setting[0]->application_name,
			'default_currency_symbol' => $setting[0]->default_currency_symbol,
			'show_currency' => $setting[0]->show_currency,
			'currency_position' => $setting[0]->currency_position,
			'date_format_xi' => $setting[0]->date_format_xi,
			'animation_effect' => $setting[0]->animation_effect,
			'animation_effect_topmenu' => $setting[0]->animation_effect_topmenu,
			'animation_effect_modal' => $setting[0]->animation_effect_modal,
			'notification_position' => $setting[0]->notification_position,
			'notification_close_btn' => $setting[0]->notification_close_btn,
			'notification_bar' => $setting[0]->notification_bar,
			'employee_manage_own_bank_account' => $setting[0]->employee_manage_own_bank_account,
			'employee_manage_own_contact' => $setting[0]->employee_manage_own_contact,
			'employee_manage_own_profile' => $setting[0]->employee_manage_own_profile,
			'employee_manage_own_qualification' => $setting[0]->employee_manage_own_qualification,
			'employee_manage_own_work_experience' => $setting[0]->employee_manage_own_work_experience,
			'employee_manage_own_document' => $setting[0]->employee_manage_own_document,
			'employee_manage_own_picture' => $setting[0]->employee_manage_own_picture,
			'employee_manage_own_social' => $setting[0]->employee_manage_own_social,
			'enable_email_notification' => json_decode($setting[0]->enable_email_notification),
			'enable_job_application_candidates' => $setting[0]->enable_job_application_candidates,
			'job_application_format' => $setting[0]->job_application_format,
			'footer_text' => $setting[0]->footer_text,
			'enable_page_rendered' => $setting[0]->enable_page_rendered,
			'enable_current_year' => $setting[0]->enable_current_year,
			'enable_tax_calculation' => $setting[0]->enable_tax_calculation,
			'maximum_working_hours' => $setting[0]->maximum_working_hours,
			'lunch_hours' => $setting[0]->lunch_hours,
			'grace_hours' => $setting[0]->grace_hours,
			'deduct_hours' => $setting[0]->deduct_hours,
			'minimum_delivery_counts' => $setting[0]->minimum_delivery_counts,
			'order_cancellation_percentage'=>$setting[0]-> order_cancellation_percentage,
			'monthly_target_count'=>$setting[0]->monthly_target_count,
			'grace_departments' => json_decode($setting[0]->grace_departments),
			'eligible_visa_under' => json_decode($setting[0]->eligible_visa_under),
			'exceptional_employees' => json_decode($setting[0]->exceptional_employees),
			'flexible_employees' => json_decode($setting[0]->flexible_employees),
			'employee_approval_to_ceo' => json_decode($setting[0]->employee_approval_to_ceo),
			'all_countries' => $this->Xin_model->get_countries(),
			'grace_dep_loc'=>$grace_dep_loc,
			'login_alerts' => json_decode($setting[0]->alerts),
			'email_settings'=>$this->Xin_model->checkEmailSettingsData('Email Settings'),
			);
		$data['breadcrumbs'] = 'Settings';
		$data['path_url'] = 'settings';
		if(in_array('53',role_resource_ids()) || in_array('53e',role_resource_ids()) || in_array('53v',role_resource_ids())){
			if(!empty($this->userSession)){
			$data['subview'] = $this->load->view("settings/settings", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
    }

    public function theme()
    {
		$data['title'] = $this->Xin_model->site_title();
		$setting = $this->Xin_model->read_setting_info(1);
		$company_info = $this->Xin_model->read_company_setting_info(1);
		$data = array(
			'title' => $this->Xin_model->site_title(),
			'company_info_id' => $company_info[0]->company_info_id,
			'logo' => $company_info[0]->logo,
			'company_name' => $company_info[0]->company_name,
			'contact_person' => $company_info[0]->contact_person,
			'website_url' => $company_info[0]->website_url,
			'starting_year' => $company_info[0]->starting_year,
			'company_email' => $company_info[0]->company_email,
			'company_contact' => $company_info[0]->company_contact,
			'email' => $company_info[0]->email,
			'phone' => $company_info[0]->phone,
			'address_1' => $company_info[0]->address_1,
			'address_2' => $company_info[0]->address_2,
			'city' => $company_info[0]->city,
			'state' => $company_info[0]->state,
			'zipcode' => $company_info[0]->zipcode,
			'country' => $company_info[0]->country,
			'updated_at' => $company_info[0]->updated_at,
			'application_name' => $setting[0]->application_name,
			'default_currency_symbol' => $setting[0]->default_currency_symbol,
			'show_currency' => $setting[0]->show_currency,
			'currency_position' => $setting[0]->currency_position,
			'date_format_xi' => $setting[0]->date_format_xi,
			'animation_effect' => $setting[0]->animation_effect,
			'animation_effect_topmenu' => $setting[0]->animation_effect_topmenu,
			'animation_effect_modal' => $setting[0]->animation_effect_modal,
			'notification_position' => $setting[0]->notification_position,
			'employee_manage_own_contact' => $setting[0]->employee_manage_own_contact,
			'employee_manage_own_profile' => $setting[0]->employee_manage_own_profile,
			'employee_manage_own_qualification' => $setting[0]->employee_manage_own_qualification,
			'employee_manage_own_work_experience' => $setting[0]->employee_manage_own_work_experience,
			'employee_manage_own_document' => $setting[0]->employee_manage_own_document,
			'employee_manage_own_picture' => $setting[0]->employee_manage_own_picture,
			'employee_manage_own_social' => $setting[0]->employee_manage_own_social,
			'enable_email_notification' => json_decode($setting[0]->enable_email_notification),
			'enable_job_application_candidates' => $setting[0]->enable_job_application_candidates,
			'job_application_format' => $setting[0]->job_application_format,
			'all_countries' => $this->Xin_model->get_countries()
			);
		$data['breadcrumbs'] = 'Settings';
		$data['path_url'] = 'settings';
		if(in_array('53',role_resource_ids())) {
			if(!empty($this->userSession)){
			$data['subview'] = $this->load->view("settings/theme", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
    }

	public function database_backup()
    {
		$data['title'] = $this->Xin_model->site_title();
		$data['breadcrumbs'] = 'Database Backup';
		$data['path_url'] = 'database_backup';
		if(in_array('56',role_resource_ids())) {
			if(!empty($this->userSession)){
			$data['subview'] = $this->load->view("settings/database_backup", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
    }

	public function user_logs()
    {
		$data['title'] = $this->Xin_model->site_title();
		if($this->input->post('month_year')){
			$data['month_year']=$this->input->post('month_year');
			$current_month=format_date('Y-m',$data['month_year']);
		}else{
			$data['month_year']=format_date('Y F',date('Y-m'));
			$current_month=date('Y-m');
		}
		$data['user_logs'] = $this->Xin_model->read_user_logs($current_month);
		$data['breadcrumbs'] = 'Activity Logs';
		$data['path_url'] = 'user_logs';
		if(in_array('56',role_resource_ids())) {
			if(!empty($this->userSession)){
			$data['subview'] = $this->load->view("settings/user_logs", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
    }

    public function system_logs(){

		$data['title'] = $this->Xin_model->site_title();
		if($this->input->post('month_year')){
			$data['month_year']=$this->input->post('month_year');
			$current_month=format_date('Y-m',$data['month_year']);
		}else{
			$data['month_year']=format_date('Y F',date('Y-m'));
			$current_month=date('Y-m');
		}
		if($this->input->post('system_module') != ''){
			$data['system_module'] = $this->input->post('system_module');
		}else{
			$data['system_module'] = '';
		}
		$data['system_logs'] = $this->Xin_model->read_system_logs($current_month,$data['system_module']);
		$data['breadcrumbs'] = 'System Logs';
		$data['path_url'] = 'system_logs';
		if(in_array('system-logs',role_resource_ids())) {
			if(!empty($this->userSession)){
				$data['action_list'] = $this->Xin_model->getActionList();
				$data['subview'] = $this->load->view("settings/system_logs", $data, TRUE);
				$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
    }

    public function backup_database( $directory, $outname , $dbhost, $dbuser, $dbpass ,$dbname ) {
		if( ! function_exists('mysqli_connect') ) {
			die(' This scripts need mysql extension to be running properly ! please resolve!!');
		}
		$mysqli = @new mysqli($dbhost, $dbuser, $dbpass, $dbname);

		if( $mysqli->connect_error ) {
			print_r( $mysqli->connect_error );
			return false;
		}
		$dir = $directory;
		$result = '<p> Could not create backup directory on :'.$dir.' Please Please make sure you have set Directory on 755 or 777 for a while.</p>';
		$res = true;
		if( ! is_dir( $dir ) ) {
		  if( ! @mkdir( $dir, 755 )) {
			$res = false;
		  }
		}
		$n = 1;
		if( $res ) {
		$name     = $outname;
		# counts
		if( file_exists($dir.'/'.$name.'.sql.gz' ) ) {
		  for($i=1;@count( file($dir.'/'.$name.'_'.$i.'.sql.gz') );$i++){
			$name = $name;
			if( ! file_exists( $dir.'/'.$name.'_'.$i.'.sql.gz') ) {
			  $name = $name.'_'.$i;
			  break;
			}
		  }
		}
		$fullname = $dir.'/'.$name.'.sql.gz'; # full structures
		if( ! $mysqli->error ) {
		  $sql = "SHOW TABLES";
		  $show = $mysqli->query($sql);
		  while ( $r = $show->fetch_array() ) {
			//if (strpos($r[0], 'table') != FALSE)
			//{
			$tables[] = $r[0];
			//}


		}

		if( ! empty( $tables ) ) {
		//cycle through
		$return = '';
		foreach( $tables as $table )
		{
		$result     = $mysqli->query('SELECT * FROM '.$table);
		$num_fields = $result->field_count;
		$row2       = $mysqli->query('SHOW CREATE TABLE '.$table );
		$row2       = $row2->fetch_row();
		$return    .=
		"\n
		-- ---------------------------------------------------------
		--
		-- Table structure for table : `{$table}`
		--
		-- ---------------------------------------------------------
		".$row2[1].";\n";
		for ($i = 0; $i < $num_fields; $i++)
		{
		  $n = 1 ;
		  while( $row = $result->fetch_row() )
		  {

			if( $n++ == 1 ) { # set the first statements
			  $return .=
		"
		--
		-- Dumping data for table `{$table}`
		--
		";
			/**
			 * Get structural of fields each tables
			 */
			$array_field = array(); #reset ! important to resetting when loop
			 while( $field = $result->fetch_field() ) # get field
			{
			  $array_field[] = '`'.$field->name.'`';

			}
			$array_f[$table] = $array_field;
			// $array_f = $array_f;
			# endwhile
			$array_field = implode(', ', $array_f[$table]); #implode arrays
			  $return .= "INSERT INTO `{$table}` ({$array_field}) VALUES\n(";
			} else {
			  $return .= '(';
			}
			for($j=0; $j<$num_fields; $j++)
			{

			  $row[$j] = str_replace('\'','\'\'', preg_replace("/\n/","\\n", $row[$j] ) );
			  if ( isset( $row[$j] ) ) { $return .= is_numeric( $row[$j] ) ? $row[$j] : '\''.$row[$j].'\'' ; } else { $return.= '\'\''; }
			  if ($j<($num_fields-1)) { $return.= ', '; }
			}
			  $return.= "),\n";
		  }
		  # check matching
		  @preg_match("/\),\n/", $return, $match, false, -3); # check match
		  if( isset( $match[0] ) )
		  {
			$return = substr_replace( $return, ";\n", -2);
		  }
		}

		  $return .= "\n";
		}
		$return =
		"-- ---------------------------------------------------------
		--
		-- SIMPLE SQL Dump
		-- 
		-- nawa (at) yahoo (dot) com
		--
		-- Host Connection Info: ".$mysqli->host_info."
		-- Generation Time: ".date('F d, Y \a\t H:i A ( e )')."
		-- PHP Version: ".PHP_VERSION."
		--
		-- ---------------------------------------------------------\n\n
		SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";
		SET time_zone = \"+00:00\";
		/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
		/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
		/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
		/*!40101 SET NAMES utf8 */;
		".$return."
		/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
		/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
		/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;";
		# end values result
		@ini_set('zlib.output_compression','Off');

		$gzipoutput = gzencode( $return, 9);
		if(  @ file_put_contents( $fullname, $gzipoutput  ) ) { # 9 as compression levels

		$result = $name.'.sql.gz'; # show the name

		} else { # if could not put file , automaticly you will get the file as downloadable
		$result = false;
		// various headers, those with # are mandatory
		header('Content-Type: application/x-gzip'); // change it to mimetype
		header("Content-Description: File Transfer");
		header('Content-Encoding: gzip'); #
		header('Content-Length: '.strlen( $gzipoutput ) ); #
		header('Content-Disposition: attachment; filename="'.$name.'.sql.gz'.'"');
		header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
		header('Connection: Keep-Alive');
		header("Content-Transfer-Encoding: binary");
		header('Expires: 0');
		header('Pragma: no-cache');

		echo $gzipoutput;
		}
		   } else {
			 $result = '<p>Error when executing database query to export.</p>'.$mysqli->error;

		   }
		 }
		} else {
		  $result = '<p>Wrong mysqli input</p>';
		}

		if( $mysqli && ! $mysqli->error ) {
		  @$mysqli->close();
		}
		return $result;
	}

	public function create_database_backup()
    {
		$data['title'] = $this->Xin_model->site_title();
		if($this->input->post('type')==='backup') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'');

			$db = array('default' => array());
			// get db credentials

			require 'hrmsapp/config/database.php';
			$hostname = $db['default']['hostname'];
			$username = $db['default']['username'];
			$password = $db['default']['password'];
			$database = $db['default']['database'];

			$dir  = 'uploads/dbbackup/'; // directory files
			$name = 'backup_'.date('d-m-Y_H_i_s'); // name sql backup
			$this->backup_database( $dir, $name, $hostname, $username, $password, $database); // execute

			$fname = $name.'.sql.gz';

			$data = array(
			'backup_file' => $fname,
			'created_at' => date('d-m-Y H:i:s')
			);

			$result = $this->Xin_model->add_backup($data);

			/*User Logs*/
			$affected_id= table_max_id('xin_database_backup','backup_id');
			userlogs('Settings-Database Backup-Create','Database Backup Created',$affected_id['field_id'],$affected_id['datas']);
			/*User Logs*/
			if ($result == TRUE) {
				$Return['result'] = 'Database Backup Generated.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
    }

	public function delete_db_backup()
    {
		if($this->input->post('type')==='delete_old_backup') {
			$Return = array('result'=>'', 'error'=>'');
			$this->Xin_model->delete_all_backup_record();
			userlogs('Settings','Delete All Backup Database Records','');
			$files = glob('uploads/dbbackup/*'); //get all file names
			foreach($files as $file){
				if(is_file($file))
				unlink($file); //delete file
			}
			$Return['result'] = 'Database Old Backup Deleted.';
			$this->output($Return);
			exit;
		}
    }

	public function database_backup_list()
    {

		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("settings/database_backup", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$db_backup = $this->Xin_model->all_db_backup();
		$data = array();
        foreach($db_backup->result() as $r) {

			$created_at = $this->Xin_model->set_date_format($r->created_at);

			$data[] = array(
				$r->backup_file,
				$created_at,
				'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right"><li><a href="'.site_url().'download?type=dbbackup&filename='.$r->backup_file.'"><i class="icon-file-download"></i> Download</a></li><li><a class="delete" href="#" data-toggle="modal" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->backup_id . '" data-token_type="currency_type"><i class="icon-trash"></i> Delete</a></li></ul></li></ul>'
				);
			}

	  	$output = array(
		   		"draw" => $draw,
			 	"recordsTotal" => $db_backup->num_rows(),
			 	"recordsFiltered" => $db_backup->num_rows(),
			 	"data" => $data
		);
		$this->output($output);
    }

	public function email_template() {
		$data['title'] = $this->Xin_model->site_title();
		$data['breadcrumbs'] = 'Email Templates';
		$data['path_url'] = 'email_template';
		if(in_array('55',role_resource_ids())) {
			if(!empty($this->userSession)){
			$data['subview'] = $this->load->view("settings/email_template", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
	}

	public function email_template_list()
    {

		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("settings/email_template", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$email_template = $this->Xin_model->get_email_templates();

		$data = array();

        foreach($email_template->result() as $r) {

			if($r->status==1){
				$status = '<span class="label label-success">Active</span>';
			} else {
				$status = '<span class="label label-warning">Inactive</span>';
			}

			$data[] = array(
				$r->name,
				$r->subject,
				$status,
				'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right"><li><a href="#" data-toggle="modal" data-target=".edit-modal-data"  data-template_id="'. $r->template_id . '"><i class="icon-pencil7"></i> Edit</a></li></ul></li></ul>',
			);
     	}

	  	$output = array(
		   "draw" => $draw,
			 "recordsTotal" => $email_template->num_rows(),
			 "recordsFiltered" => $email_template->num_rows(),
			 "data" => $data
		);

	  	$this->output($output);
    }

	public function read_tempalte()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('template_id');
		$result = $this->Xin_model->read_email_template_info($id);
		$data = array(
				'template_id' => $result[0]->template_id,
				'template_code' => $result[0]->template_code,
				'name' => $result[0]->name,
				'subject' => $result[0]->subject,
				'message' => htmlspecialchars_decode(stripslashes($result[0]->message),ENT_QUOTES),
				'status' => $result[0]->status
				);
		if(!empty($this->userSession)){
			$this->load->view('settings/dialog_email_template', $data);
		} else {
			redirect('');
		}
	}

	public function password_read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('user_id');
		$result = $this->Xin_model->read_user_info($id);
		$data = array(
				'user_id' => $result[0]->user_id,
				);
		if(!empty($this->userSession)){
			$this->load->view('settings/dialog_constants', $data);
		} else {
			redirect('');
		}
	}

	public function policy_read()
	{
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view('settings/dialog_constants', $data);
		} else {
			redirect('');
		}
	}

	public function update_template() {

		if($this->input->post('edit_type')=='update_template') {
		$Return = array('result'=>'', 'error'=>'');
		$id = $this->uri->segment(3);

		if($this->input->post('name')==='') {
       		$Return['error'] = "The name field is required.";
		} else if($this->input->post('subject')==='') {
			$Return['error'] = "The subject field is required.";
		} else if($this->input->post('status')==='') {
			$Return['error'] = "The status field is required.";
		} else if($this->input->post('message')==='') {
			$Return['error'] = "The message field is required.";
		}

		if($Return['error']!=''){
       		$this->output($Return);
    	}

		$message = $this->input->post('message');
		$new_message = htmlspecialchars(addslashes($message),ENT_QUOTES);

		$data = array(
			'name' => $this->input->post('name'),
			'subject' => $this->input->post('subject'),
			'status' => $this->input->post('status'),
			'message' => $new_message
		);

		$result = $this->Xin_model->update_email_template_record($data,$id);

		/*User Logs*/
		$affected_id= table_update_id('xin_system_setting','setting_id',$id);
		userlogs('Settings-Email Template-Update','Email Template Updated',$id,$affected_id['datas']);
		/*User Logs*/

		if ($result == TRUE) {
			$Return['result'] = 'Email Template updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}

	public function constants()
    {
		$data['title'] = $this->Xin_model->site_title();
		$visa_type = $this->Xin_model->get_visa_under();
		$data['visa_type'] = $visa_type->result();
		$data['all_countries'] = $this->Xin_model->get_countries();
		$data['breadcrumbs'] = 'Constants';
		$data['path_url'] = 'constants';
        $data['parent_type'] = $this->Xin_model->ready_salary_types();
		if(in_array('54',role_resource_ids()) || in_array('54a',role_resource_ids()) || in_array('54e',role_resource_ids()) || in_array('54d',role_resource_ids()) || in_array('54v',role_resource_ids())) {
			if(!empty($this->userSession)){
			$data['subview'] = $this->load->view("settings/constants", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
    }

	public function company_info() {
		if($this->input->post('type')=='company_info') {
			$Return = array('result'=>'', 'error'=>'');
			$id = 1;

			if($this->input->post('company_name')==='') {
				$Return['error'] = "The company name field is required.";
			} else if($this->input->post('website')==='') {
				$Return['error'] = "The website field is required.";
			} else if($this->input->post('contact_person')==='') {
				$Return['error'] = "The contact person field is required.";
			} else if($this->input->post('email')==='') {
				$Return['error'] = "The email field is required.";
			} else if (!filter_var($this->input->post('email'), FILTER_VALIDATE_EMAIL)) {
				$Return['error'] = "Invalid email format.";
			} else if($this->input->post('phone')==='') {
				$Return['error'] = "The phone field is required.";
			}

			if($Return['error']!=''){
				$this->output($Return);
			}

			$data = array(
			'company_name' => $this->input->post('company_name'),
			'contact_person' => $this->input->post('contact_person'),
			'website_url' => $this->input->post('website'),
			'starting_year' => $this->input->post('starting_year'),
			'company_email' => $this->input->post('company_email'),
			'company_contact' => $this->input->post('company_contact'),
			'email' => $this->input->post('email'),
			'phone' => $this->input->post('country_code').$this->input->post('phone'),
			'address_1' => $this->input->post('address_1'),
			'address_2' => $this->input->post('address_2'),
			'city' => $this->input->post('city'),
			'state' => $this->input->post('state'),
			'zipcode' => $this->input->post('zipcode'),
			'country' => $this->input->post('country'),
			);

			$result = $this->Xin_model->update_company_info_record($data,$id);
			/*User Logs*/
			$affected_id= table_update_id('xin_system_setting','setting_id',$id);
			userlogs('Settings-General-Update','General configuration Updated',$id,$affected_id['datas']);
			/*User Logs*/
			if ($result == TRUE) {
				$Return['result'] = 'Company Information updated.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function logo_info() {
		if($this->input->post('type')=='logo_info') {
			$Return = array('result'=>'', 'error'=>'');
			$id = 1;

			if($_FILES['p_file']['size'] == 0) {
				$Return['error'] = "Select First Logo.";
			}
			else if($_FILES['p_file2']['size'] == 0) {
				$Return['error'] = "Select Second Logo.";
			}
			else if($_FILES['favicon']['size'] == 0) {
				$Return['error'] = "Select Favicon.";
			}
			if($Return['error']!=''){
				$this->output($Return);
			}

			if(is_uploaded_file($_FILES['p_file']['tmp_name'])) {
				//checking image type
				$allowed =  array('png','jpg','jpeg','pdf','gif');
				$filename = $_FILES['p_file']['name'];
				$ext = pathinfo($filename, PATHINFO_EXTENSION);

				if(in_array($ext,$allowed)){
					$tmp_name = $_FILES["p_file"]["tmp_name"];
					$profile = "uploads/logo/";
					$set_img = base_url()."uploads/logo/";
					$newfilename = 'logo_'.round(microtime(true)).'.'.$ext;
					move_uploaded_file($tmp_name, $profile.$newfilename);
					$fname = $newfilename;

				}
				else {
						$Return['error'] = "The attachment must be a file of type: png, jpg, jpeg, gif for logo first";
				}
			}

			if($Return['error']!=''){
				$this->output($Return);
			}
			if(is_uploaded_file($_FILES['p_file2']['tmp_name'])) {
				//checking image type
				$allowed2 =  array('png','jpg','jpeg','pdf','gif');
				$filename2 = $_FILES['p_file2']['name'];
				$ext2 = pathinfo($filename2, PATHINFO_EXTENSION);
				if(in_array($ext2,$allowed2)){
					$tmp_name2 = $_FILES["p_file2"]["tmp_name"];
					$profile2 = "uploads/logo/";
					$set_img2 = base_url()."uploads/logo/";
					$newfilename2 = 'logo2_'.round(microtime(true)).'.'.$ext2;
					move_uploaded_file($tmp_name2, $profile2.$newfilename2);
					$fname2 = $newfilename2;

				} else {
						$Return['error'] = "The attachment must be a file of type: png, jpg, jpeg, gif for logon second.";
				}
			}

			if(is_uploaded_file($_FILES['favicon']['tmp_name'])) {
			//checking image type
			$allowed3 =  array('png','gif','ico');
			$filename3 = $_FILES['favicon']['name'];
			$ext3 = pathinfo($filename3, PATHINFO_EXTENSION);
				if(in_array($ext3,$allowed3)){
					$tmp_name3 = $_FILES["favicon"]["tmp_name"];
					$profile3 = "uploads/logo/favicon/";
					$set_img3 = base_url()."uploads/logo/favicon/";
					$newfilename3 = 'favicon_'.round(microtime(true)).'.'.$ext2;
					move_uploaded_file($tmp_name3, $profile3.$newfilename3);
					$fname3 = $newfilename3;

				} else {
					$Return['error'] = "The attachment must be a file of type: png, jpg, jpeg, gif for logon second.";
				}
			}


			$data = array(
				'logo' => $fname,
				'logo_second' => $fname2,
				'favicon' => $fname3
			);
			$result = $this->Xin_model->update_company_info_record($data,$id);

			/*User Logs*/
			$affected_id= table_update_id('xin_system_setting','setting_id',$id);
			userlogs('Settings-Logos-Update','System Logo Updated',$id,$affected_id['datas']);
			/*User Logs*/
			if ($result == TRUE) {
				$Return['img'] = $set_img.$fname;
				$Return['img2'] = $set_img2.$fname2;
				$Return['img3'] = $set_img3.$fname3;
				$Return['result'] = 'Logo updated.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function profile_background() {
		if($this->input->post('type')=='profile_background') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->input->post('user_id');
			if($_FILES['p_file']['size'] == 0) {
				$Return['error'] = "Select Profile cover.";
			}
			else {
				if(is_uploaded_file($_FILES['p_file']['tmp_name'])) {
				//checking image type
				$allowed =  array('png','jpg','jpeg','pdf','gif');
				$filename = $_FILES['p_file']['name'];
				$ext = pathinfo($filename, PATHINFO_EXTENSION);

					if(in_array($ext,$allowed)){
						$tmp_name = $_FILES["p_file"]["tmp_name"];
						$profile = "uploads/profile/background/";
						$set_img = base_url()."uploads/profile/background/";
						$newfilename = 'profile_background_'.round(microtime(true)).'.'.$ext;
						move_uploaded_file($tmp_name, $profile.$newfilename);
						$fname = $newfilename;

						$data = array(
						'profile_background' => $fname
						);
						$result = $this->Employees_model->basic_info($data,$id);
						if ($result == TRUE) {
							$Return['profile_background'] = $set_img.$fname;
							$Return['result'] = 'Profile Background updated.';
						} else {
							$Return['error'] = 'Bug. Something went wrong, please try again.';
						}
						$this->output($Return);
						exit;

					} else {
						$Return['error'] = "The attachment must be a file of type: png, jpg, jpeg, gif";
					}
				}
			}
			if($Return['error']!=''){
			$this->output($Return);
			}
		}
	}

	public function sign_in_logo() {
		if($this->input->post('type')=='singin_logo') {
			$Return = array('result'=>'', 'error'=>'');
			$id = 1;

			if($_FILES['p_file3']['size'] == 0) {
				$Return['error'] = "Select sign in page logo.";
			}
			else {
				if(is_uploaded_file($_FILES['p_file3']['tmp_name'])) {
					//checking image type
					$allowed =  array('png','jpg','jpeg','pdf','gif');
					$filename = $_FILES['p_file3']['name'];
					$ext = pathinfo($filename, PATHINFO_EXTENSION);

					if(in_array($ext,$allowed)){
						$tmp_name = $_FILES["p_file3"]["tmp_name"];
						$profile = "uploads/logo/signin/";
						$set_img = base_url()."uploads/logo/signin/";
						$newfilename = 'signin_logo_'.round(microtime(true)).'.'.$ext;
						move_uploaded_file($tmp_name, $profile.$newfilename);
						$fname = $newfilename;
						$data = array(
							'sign_in_logo' => $fname
						);
						$result = $this->Xin_model->update_company_info_record($data,$id);

						/*User Logs*/
						$affected_id= table_update_id('xin_system_setting','setting_id',$id);
						userlogs('Settings-Logos-Update','Sign In Page Logo Updated',$id,$affected_id['datas']);
						/*User Logs*/

						if ($result == TRUE) {
							$Return['img'] = $set_img.$fname;
							$Return['result'] = 'Sign-in page logo updated.';
						} else {
							$Return['error'] = 'Bug. Something went wrong, please try again.';
						}
						$this->output($Return);
						exit;

					}
					else {
						$Return['error'] = "The attachment must be a file of type: png, jpg, jpeg, gif";
					}
				}
			}

			if($Return['error']!=''){
				$this->output($Return);
			}
		}
	}

	public function job_logo() {
		if($this->input->post('type')=='job_logo') {
			$Return = array('result'=>'', 'error'=>'');
			$id = 1;
			if($_FILES['p_file4']['size'] == 0) {
				$Return['error'] = "Select job logo.";
			}
			else {
				if(is_uploaded_file($_FILES['p_file4']['tmp_name'])) {
					//checking image type
					$allowed =  array('png','jpg','jpeg','pdf','gif');
					$filename = $_FILES['p_file4']['name'];
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
						if(in_array($ext,$allowed)){
							$tmp_name = $_FILES["p_file4"]["tmp_name"];
							$profile = "uploads/logo/job/";
							$set_img = base_url()."uploads/logo/job/";
							$newfilename = 'job_logo_'.round(microtime(true)).'.'.$ext;
							move_uploaded_file($tmp_name, $profile.$newfilename);
							$fname = $newfilename;

							$data = array(
							'job_logo' => $fname
							);
							$result = $this->Xin_model->update_setting_info_record($data,$id);
							/*User Logs*/
							$affected_id= table_update_id('xin_system_setting','setting_id',$id);
							userlogs('Settings-Recruitment-Update','Job Logo Updated',$id,$affected_id['datas']);
							/*User Logs*/
							if ($result == TRUE) {
								$Return['img'] = $set_img.$fname;
								$Return['result'] = 'Recruitment logo updated.';
							} else {
								$Return['error'] = 'Bug. Something went wrong, please try again.';
							}
							$this->output($Return);
							exit;

						}
						else {
							$Return['error'] = "The attachment must be a file of type: png, jpg, jpeg, gif";
						}
					}
			}

			if($Return['error']!=''){
				$this->output($Return);
			}
		}
	}

	public function payroll_logo() {

		if($this->input->post('type')=='payroll_logo') {
			$Return = array('result'=>'', 'error'=>'');
			$id = 1;
			if($_FILES['p_file5']['size'] == 0) {
				$Return['error'] = "Select payroll logo.";
			}
			else {
				if(is_uploaded_file($_FILES['p_file5']['tmp_name'])) {
					//checking image type
					$allowed =  array('png','jpg','jpeg','pdf','gif');
					$filename = $_FILES['p_file5']['name'];
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
					if(in_array($ext,$allowed)){
						$tmp_name = $_FILES["p_file5"]["tmp_name"];
						$profile = "uploads/logo/payroll/";
						$set_img = base_url()."uploads/logo/payroll/";
						$newfilename = 'payroll_logo_'.round(microtime(true)).'.'.$ext;
						move_uploaded_file($tmp_name, $profile.$newfilename);
						$fname = $newfilename;

						$data = array(
						'payroll_logo' => $fname
						);
						$result = $this->Xin_model->update_setting_info_record($data,$id);
						/*User Logs*/
						$affected_id= table_update_id('xin_system_setting','setting_id',$id);
						userlogs('Settings-Payroll-Update','Payroll Logo Updated',$id,$affected_id['datas']);
						/*User Logs*/
						if ($result == TRUE) {
							$Return['img'] = $set_img.$fname;
							$Return['result'] = 'Payroll logo updated.';
						} else {
							$Return['error'] = 'Bug. Something went wrong, please try again.';
						}
						$this->output($Return);
						exit;
					}
					else {
						$Return['error'] = "The attachment must be a file of type: png, jpg, jpeg, gif";
					}
				}
			}
			if($Return['error']!=''){
				$this->output($Return);
			}
		}
	}

	public function system_info() {
		if($this->input->post('type')=='system_info') {
			$Return = array('result'=>'', 'error'=>'');
			$id = 1;

			if(trim($this->input->post('application_name'))==='') {
				 $Return['error'] = "The application name field is required.";
			} else if($this->input->post('default_currency_symbol')==='') {
				$Return['error'] = "The default currency field is required.";
			} else if($this->input->post('show_currency')==='') {
				$Return['error'] = "The default currency symbol field is required.";
			} else if($this->input->post('currency_position')==='') {
				$Return['error'] = "The currency position field is required.";
			} else if($this->input->post('date_format')==='') {
				$Return['error'] = "The date format field is required.";
			} else if($this->input->post('footer_text')==='') {
				$Return['error'] = "The footer text field is required.";
			}

			if($Return['error']!=''){
				$this->output($Return);
			}

			$data = array(
				'application_name' => $this->input->post('application_name'),
				'show_currency' => $this->input->post('show_currency'),
				'currency_position' => $this->input->post('currency_position'),
				'date_format_xi' => $this->input->post('date_format'),
				'footer_text' => $this->input->post('footer_text'),
				'enable_page_rendered' => $this->input->post('enable_page_rendered'),
				'enable_current_year' => $this->input->post('enable_current_year'),
				'enable_tax_calculation' => $this->input->post('enable_tax_calculation'),
			);

			$result = $this->Xin_model->update_setting_info_record($data,$id);
			/*User Logs*/
			$affected_id= table_update_id('xin_system_setting','setting_id',$id);
			userlogs('Settings-System-Update','System Configuration Updated',$id,$affected_id['datas']);
			/*User Logs*/
			if ($result == TRUE) {
				$Return['result'] = 'System Configuration updated.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function layout_skin_info() {
		if($this->input->post('type')=='layout_skin_info') {
			$Return = array('result'=>'', 'error'=>'');
			$id = 1;

			$data = array(
				'fixed_header' => $this->input->post('fixed-header'),
				'fixed_sidebar' => $this->input->post('fixed-sidebar'),
				'boxed_wrapper' => $this->input->post('boxed-wrapper'),
				'layout_static' => $this->input->post('static'),
				'system_skin' => $this->input->post('skin'),
			);

			$result = $this->Xin_model->update_setting_info_record($data,$id);
			if ($result == TRUE) {
				$Return['result'] = 'System Layout updated.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function role_info() {

		if($this->input->post('type')=='role_info') {
			$Return = array('result'=>'', 'error'=>'');
			$id = 1;
			$data = array(
				'employee_manage_own_contact' => $this->input->post('employee_manage_own_contact'),
				'employee_manage_own_social' => $this->input->post('employee_manage_own_social'),
				'employee_manage_own_bank_account' => $this->input->post('employee_manage_own_bank_account'),
				'employee_manage_own_qualification' => $this->input->post('employee_manage_own_qualification'),
				'employee_manage_own_work_experience' => $this->input->post('employee_manage_own_work_experience'),
				'employee_manage_own_document' => $this->input->post('employee_manage_own_document'),
				'employee_manage_own_picture' => $this->input->post('employee_manage_own_picture'),
				'employee_manage_own_profile' => $this->input->post('employee_manage_own_profile'),
			);
			$result = $this->Xin_model->update_setting_info_record($data,$id);
			/*User Logs*/
			$affected_id= table_update_id('xin_system_setting','setting_id',$id);
			userlogs('Settings-Role-Update','Role Configuration Updated',$id,$affected_id['datas']);
			/*User Logs*/
			if ($result == TRUE) {
				$Return['result'] = 'Role Configuration updated.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function sidebar_setting_info() {
		if($this->input->post('type')=='other_settings') {
			$Return = array('result'=>'', 'error'=>'');
			$id = 1;

			$data = array(
				'enable_attendance' => $this->input->post('enable_attendance'),
				'enable_job_application_candidates' => $this->input->post('enable_job'),
				'enable_profile_background' => $this->input->post('enable_profile_background'),
				'enable_email_notification' => json_decode($this->input->post('role_email_notification')),
				'notification_close_btn' => $this->input->post('close_btn'),
				'notification_bar' => $this->input->post('notification_bar'),
				'enable_policy_link' => $this->input->post('role_policy_link'),
				'enable_layout' => $this->input->post('enable_layout'),
			);

			$result = $this->Xin_model->update_setting_info_record($data,$id);

			if ($result == TRUE) {
				$Return['result'] = 'Setting Configuration updated.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function attendance_info() {
		if($this->input->post('type')=='attendance_info') {
			$Return = array('result'=>'', 'error'=>'');
			$id = 1;
			$data = array(
				'maximum_working_hours' => $this->input->post('maximum_working_hours'),
				'deduct_hours' => $this->input->post('deduct_hours'),
				'lunch_hours' => $this->input->post('lunch_hours'),
				'grace_hours' => $this->input->post('grace_hours'),
				'minimum_delivery_counts' => $this->input->post('minimum_delivery_counts'),
				'order_cancellation_percentage' => $this->input->post('order_cancellation_percentage'),
				'monthly_target_count' => $this->input->post('monthly_target_count'),
				'exceptional_employees' =>json_encode($this->input->post('exceptional_employees')),
				'flexible_employees' =>json_encode($this->input->post('flexible_employees')),
				'employee_approval_to_ceo' =>json_encode($this->input->post('employee_approval_to_ceo')),
				'grace_departments' =>json_encode($this->input->post('grace_departments')),
				'eligible_visa_under' => json_encode($this->input->post('eligible_visa_under')),
			);
			$result = $this->Xin_model->update_setting_info_record($data,$id);
			/*User Logs*/
			$affected_id= table_update_id('xin_system_setting','setting_id',$id);
			userlogs('Settings-Attendance-Update','Attendance Configuration Updated',$id,$affected_id['datas']);
			/*User Logs*/
			if ($result == TRUE) {
				$Return['result'] = 'Attendance Configuration updated.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function email_settings(){

		if($this->input->post('type')=='email_settings') {

			$Return = array('result'=>'', 'error'=>'');
			
			$checkDataSalaryCertificate = $this->Xin_model->checkEmailSettingsData('Email Settings','Salary Certificate');

			$data['settings_type'] = 'Email Settings';
			$data['settings_name'] = 'Salary Certificate';
			$data['settings_value'] = json_encode($this->input->post('salary_certificate_emails'));
			$data['updated_by'] = $this->userSession['user_id'];
			if(!empty($checkDataSalaryCertificate)){

				$id = $checkDataSalaryCertificate[0]->settings_id;
				$result = $this->Xin_model->update_email_settings($data,$id);
				/*User Logs*/
				$affected_id= table_update_id('xin_system_setting','setting_id',$id);
				userlogs('Settings-Email-Settngs-Update','Email Settings Updated',$id,$affected_id['datas']);
				/*User Logs*/
			}else{
				$this->db->insert('xin_settings',$data);
				$result = $this->db->insert_id();
			}
			
			if ($result == TRUE) {
				$Return['result'] = 'Email settings updated.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}

	}

	public function email_info() {
		if($this->input->post('type')=='email_info') {
			$Return = array('result'=>'', 'error'=>'');
			$id = 1;
			$missed_login_notification=$this->input->post('missed_login_notification');
			$login_alert_hours=$this->input->post('login_alert_hours');
			$login_alerts=json_encode(array('missed_login_notification'=>$missed_login_notification,'login_alert_hours'=>$login_alert_hours));


			if($this->input->post('welcome_email')){
				$welcome_email=$this->input->post('welcome_email');
			}else{
				$welcome_email='';
			}
			if($this->input->post('forgot_password')){
				$forgot_password=$this->input->post('forgot_password');
			}else{
				$forgot_password='';
			}
			if($this->input->post('missed_loginout')){
				$missed_loginout=$this->input->post('missed_loginout');
			}else{
				$missed_loginout='';
			}
			if($this->input->post('leave_email')){
				$leave_email=$this->input->post('leave_email');
			}else{
				$leave_email='';
			}
			if($this->input->post('birthday_email')){
				$birthday_email=$this->input->post('birthday_email');
			}else{
				$birthday_email='';
			}

			$email_notification=json_encode(array('welcome_email'=>$welcome_email,'forgot_password'=>$forgot_password,
				'birthday_email'=>$birthday_email,'missed_loginout'=>$missed_loginout,'leave_email'=>$leave_email));

			$data = array(
				'enable_email_notification' => $email_notification,
				'alerts'=>$login_alerts
			);

			$result = $this->Xin_model->update_setting_info_record($data,$id);
			/*User Logs*/
			$affected_id= table_update_id('xin_system_setting','setting_id',$id);
			userlogs('Settings-Email Notifications / Alerts-Update','Email notification / Alerts Configuration Updated',$id,$affected_id['datas']);
			/*User Logs*/
			if ($result == TRUE) {
				$Return['result'] = 'Email Notification & Alerts Configuration updated.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function job_info() {
		if($this->input->post('type')=='job_info') {
			$Return = array('result'=>'', 'error'=>'');
			if($this->input->post('job_application_format')==='') {
				$Return['error'] = "The job application format field is required.";
			}

			if($Return['error']!=''){
				$this->output($Return);
			}
			$id = 1;

			$data = array(
				'enable_job_application_candidates' => $this->input->post('enable_job'),
				'job_application_format' => implode(',',$this->input->post('job_application_format'))
			);

			$result = $this->Xin_model->update_setting_info_record($data,$id);
			/*User Logs*/
			$affected_id= table_update_id('xin_system_setting','setting_id',$id);
			userlogs('Settings-Recruitment-Update','Job Configuration Updated',$id,$affected_id['datas']);
			/*User Logs*/
			if ($result == TRUE) {
				$Return['result'] = 'Job Configuration updated.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function animation_effect_info() {

		if($this->input->post('type')=='animation_effect_info') {
			$Return = array('result'=>'', 'error'=>'');
			$id = 1;

			$data = array(
				'animation_effect' => $this->input->post('animation_effect'),
				'animation_effect_topmenu' => $this->input->post('animation_effect_topmenu'),
				'animation_effect_modal' => $this->input->post('animation_effect_modal')
			);

			$result = $this->Xin_model->update_setting_info_record($data,$id);
			/*User Logs*/
			$affected_id= table_update_id('xin_system_setting','setting_id',$id);
			userlogs('Settings-Animation Effect-Update','Animation Effect Configuration Updated',$id,$affected_id['datas']);
			/*User Logs*/
			if ($result == TRUE) {
				$Return['result'] = 'Animation Effect Configuration updated.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function notification_position_info() {

		if($this->input->post('type')=='notification_position_info') {
			$Return = array('result'=>'', 'error'=>'');

			if($this->input->post('notification_position')==='') {
				$Return['error'] = "The notification position field is required.";
			}

			if($Return['error']!=''){
				$this->output($Return);
			}
			$id = 1;

			$data = array(
				'notification_position' => $this->input->post('notification_position'),
				'notification_close_btn' => $this->input->post('notification_close_btn'),
				'notification_bar' => $this->input->post('notification_bar')
			);

			$result = $this->Xin_model->update_setting_info_record($data,$id);
			/*User Logs*/
			$affected_id= table_update_id('xin_system_setting','setting_id',$id);
			userlogs('Settings-Notification Position-Update','Notification Position Updated',$id,$affected_id['datas']);
			/*User Logs*/
			if ($result == TRUE) {
				$Return['result'] = 'Notification Position Configuration updated.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
			exit;
		}
	}

	public function delete_single_backup() {
		$Return = array('result'=>'', 'error'=>'');
		$id = $this->uri->segment(3);
		/*User Logs*/
		$affected_row= table_deleted_row('xin_departments','department_id',$id);
		userlogs('Settings-Database Backup-Delete','Database Backup Deleted',$id,$affected_row);
		/*User Logs*/
		$this->Xin_model->delete_single_backup_record($id);
		if(isset($id)) {
			$Return['result'] = 'Database Backup deleted.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
	}

	/*  ALL CONSTANTS */
	public function contract_type_list()
    {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$contract_type = $this->Xin_model->get_contract_types();
		$data = array();
        foreach($contract_type->result() as $r) {
		  $edit_perm='';
		  $delete_perm='';
		  if(in_array('54e',role_resource_ids())) {
			$edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->type_id . '" data-field_type="contract_type"><i class="icon-pencil7"></i> Edit</a></li>';
		  }
		  if(in_array('54d',role_resource_ids())) {
			$delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->type_id . '" data-token_type="contract_type"><i class="icon-trash"></i> Delete</a></li>';
		  }
		  $data[] = array(
				$r->type_name,
				'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$delete_perm.'</ul></li></ul>'
		  );
      	}
      	$output = array(
					"draw" => $draw,
					 "recordsTotal" => $contract_type->num_rows(),
					 "recordsFiltered" => $contract_type->num_rows(),
					 "data" => $data
		);
        $this->output($output);
    }

	public function education_level_list()
    {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$constant = $this->Xin_model->get_qualification_education();
		$data = array();
        foreach($constant->result() as $r) {
				$edit_perm='';
			 	$delete_perm='';
				if(in_array('54e',role_resource_ids())) {
					$edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->type_id . '" data-field_type="education_level"><i class="icon-pencil7"></i> Edit</a></li>';
			  	}
			  	if(in_array('54d',role_resource_ids())) {
					$delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->type_id . '" data-token_type="education_level"><i class="icon-trash"></i> Delete</a></li>';
			  	}
				$data[] = array(
					$r->type_name,
					'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$delete_perm.'</ul></li></ul>'
				);
    	}

	 	$output = array(
		   		"draw" => $draw,
			 	"recordsTotal" => $constant->num_rows(),
			 	"recordsFiltered" => $constant->num_rows(),
			 	"data" => $data
		);
		$this->output($output);
    }

	public function qualification_language_list()
    {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$constant = $this->Xin_model->get_qualification_language();
		$data = array();
        foreach($constant->result() as $r) {
				$edit_perm='';
				 $delete_perm='';
			  	if(in_array('54e',role_resource_ids())) {
					$edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->type_id . '" data-field_type="qualification_language"><i class="icon-pencil7"></i> Edit</a></li>';
			  	}
			 	if(in_array('54d',role_resource_ids())) {
					$delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->type_id . '" data-token_type="qualification_language"><i class="icon-trash"></i> Delete</a></li>';
			 	}
				$data[] = array(
					$r->type_name,
					'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$delete_perm.'</ul></li></ul>'
				);
        }
	  	$output = array(
		   		"draw" => $draw,
			 	"recordsTotal" => $constant->num_rows(),
			 	"recordsFiltered" => $constant->num_rows(),
			 	"data" => $data
		);
        $this->output($output);
    }

    public function qualification_skill_list()
    {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$constant = $this->Xin_model->get_qualification_skill();
		$data = array();

        foreach($constant->result() as $r) {
			  $edit_perm='';
			  $delete_perm='';
			  if(in_array('54e',role_resource_ids())) {
				$edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->type_id . '" data-field_type="qualification_skill"><i class="icon-pencil7"></i> Edit</a></li>';
			  }
			  if(in_array('54d',role_resource_ids())) {
				$delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->type_id . '" data-token_type="qualification_skill"><i class="icon-trash"></i> Delete</a></li>';
			  }
			  $data[] = array(
				$r->type_name,
				'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$delete_perm.'</ul></li></ul>'
			  );
        }
	  	$output = array(
				 "draw" => $draw,
				 "recordsTotal" => $constant->num_rows(),
				 "recordsFiltered" => $constant->num_rows(),
			 	 "data" => $data
		);
		$this->output($output);
    }

	public function document_type_list()
	{
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$constant = $this->Xin_model->get_document_type();
		$data = array();
        foreach($constant->result() as $r) {
			 $edit_perm='';
			 $delete_perm='';
			 if(in_array('54e',role_resource_ids())) {
				$edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->type_id . '" data-field_type="document_type"><i class="icon-pencil7"></i> Edit</a></li>';
			 }
			 if(in_array('54d',role_resource_ids())) {
				$delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->type_id . '" data-token_type="document_type"><i class="icon-trash"></i> Delete</a></li>';
			 }
			 $type_symbol=explode(',',$r->type_code);
			 $docs_t='';

			 foreach($type_symbol as $typ){
				$docs_t.='<span class="label label-info mr-5 mt-5">'.return_country_name($typ).'</span>';
			 }

			 $data[] = array(
				$r->type_name,
				$docs_t,
				'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$delete_perm.'</ul></li></ul>'
			);
        }

	  	$output = array(
		   		"draw" => $draw,
			 	"recordsTotal" => $constant->num_rows(),
				"recordsFiltered" => $constant->num_rows(),
				"data" => $data
		);
        $this->output($output);
    }

	
	public function visa_under_list()
    {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}

		$draw = intval($this->input->get("draw"));
		$constant = $this->Xin_model->get_visa_under();
		$data = array();
        foreach($constant->result() as $r) {
			  $edit_perm='';
			  $delete_perm='';
			  if(in_array('54e',role_resource_ids())) {
				$edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->type_id . '" data-field_type="visa_under_type"><i class="icon-pencil7"></i> Edit</a></li>';
			  }
			  if(in_array('54d',role_resource_ids())) {
				$delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->type_id . '" data-token_type="visa_under"><i class="icon-trash"></i> Delete</a></li>';
			  }
			  $data[] = array(
					$r->type_name,
					'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$delete_perm.'</ul></li></ul>'
			  );
		}
	  	$output = array(
		   		"draw" => $draw,
			 	"recordsTotal" => $constant->num_rows(),
			 	"recordsFiltered" => $constant->num_rows(),
			 	"data" => $data
		);
		$this->output($output);
    }

    public function medical_card_type_list()
    {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		$draw = intval($this->input->get("draw"));
		$constant = $this->Xin_model->get_medical_card_type();
		$data = array();
        foreach($constant->result() as $r) {
            $edit_perm='';
		    $delete_perm='';
		    if(in_array('54e',role_resource_ids())) {
			    $edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->type_id . '" data-field_type="medical_card_type"><i class="icon-pencil7"></i> Edit</a></li>';
		    }
		    if(in_array('54d',role_resource_ids())) {
			    $delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->type_id . '" data-token_type="medical_card_type"><i class="icon-trash"></i> Delete</a></li>';
		    }


		    $data[] = array(
                //'<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" onclick="edit_medical_card_type('.$r->medical_card_type_id.');"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal2" data-record-id="'.$r->medical_card_type_id.'" title="Delete" data-token_type="medical_card_type"><i class="fa fa-trash-o"></i></button></span>',
                $r->type_name,
                $r->no_of_dependant,
				'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$delete_perm.'</ul></li></ul>'
		    );

            /*

            $data[] = array('<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->visa_under_id . '" data-field_type="visa_under_type"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->visa_under_id . '" title="Delete" data-token_type="visa_under_type"><i class="fa fa-trash-o"></i></button></span>',
                $r->visa_under,
            );*/
		}
	    $output = array(
		        "draw" => $draw,
			    "recordsTotal" => $constant->num_rows(),
			    "recordsFiltered" => $constant->num_rows(),
			    "data" => $data
		);
        $this->output($output);
    }

    public function award_type_list()
    {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		$draw = intval($this->input->get("draw"));
		$constant = $this->Xin_model->get_award_type();
		$data = array();
        foreach($constant->result() as $r) {
		    $edit_perm='';
		    $delete_perm='';
		    if(in_array('54e',role_resource_ids())) {
			    $edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->type_id . '" data-field_type="award_type"><i class="icon-pencil7"></i> Edit</a></li>';
		    }
		    if(in_array('54d',role_resource_ids())) {
			    $delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->type_id . '" data-token_type="award_type"><i class="icon-trash"></i> Delete</a></li>';
		    }
            $data[] = array(
                $r->type_name,
                '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$delete_perm.'</ul></li></ul>'
            );
        }

	    $output = array(
		        "draw" => $draw,
			    "recordsTotal" => $constant->num_rows(),
			    "recordsFiltered" => $constant->num_rows(),
			    "data" => $data
		);
        $this->output($output);
    }


	public function ob_type_list()
    {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		$draw = intval($this->input->get("draw"));
		$constant = $this->Xin_model->get_ob_type();
		$data = array();
        foreach($constant->result() as $r) {
		    $edit_perm='';
		    $delete_perm='';
		    if(in_array('54e',role_resource_ids())) {
			    $edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->type_id . '" data-field_type="ob_type"><i class="icon-pencil7"></i> Edit</a></li>';
		    }
		    if(in_array('54d',role_resource_ids())) {
			    $delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->type_id . '" data-token_type="ob_type"><i class="icon-trash"></i> Delete</a></li>';
		    }
            $data[] = array(
                $r->type_name,
                '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$delete_perm.'</ul></li></ul>'
            );
        }

	    $output = array(
		        "draw" => $draw,
			    "recordsTotal" => $constant->num_rows(),
			    "recordsFiltered" => $constant->num_rows(),
			    "data" => $data
		);
        $this->output($output);
	}
	

	public function update_country(){

		$country_id = $this->input->post('country_id');
		$numcode = $this->input->post('numcode');
	
		$Return = array('result'=>'', 'error'=>'');
    
		if($country_id=='') {
			$Return['error'] = "The Country ID field is required.";
		} else if($numcode =='' || $numcode < 9){
			$Return['error'] = 'The Length of digits field is required and should be more than 8.';
		}

		if($Return['error']!=''){
			$this->output($Return);
		}
			

		$data = array(
			'numcode' => $numcode,
		);

		$result = $this->Xin_model->update_country_data($data,$country_id);	
		
		if ($result == TRUE) {
			$Return['result'] = 'Country data updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;

		

	}


	public function get_country_list()
    {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		$draw = intval($this->input->get("draw"));
		$country = $this->Xin_model->get_countries();
		$data = array();
        foreach($country as $r) {
		    $edit_perm='';
		  
		    if(in_array('54e',role_resource_ids())) {
			    $edit_perm='<button type="button" class="btn bg-teal-400 numcode_'.$r->country_id.'" onclick="updateCountry('.$r->country_id.');">Update</button>';
		    }
		   
            $data[] = array(
				$r->country_name,
				$r->country_code,
				'+'.$r->phonecode,
				'<input type="number" value='.$r->numcode.' class="form-control" name="numcode" id="numcode_'.$r->country_id.'"/>',
                $edit_perm,
            );
        }

	    $output = array(
		        "draw" => $draw,
			    "recordsTotal" => count($country),
			    "recordsFiltered" => count($country),
			    "data" => $data
		);
        $this->output($output);
	}

	public function leave_type_list()
    {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		$draw = intval($this->input->get("draw"));
		$constant = $this->Xin_model->get_leave_type();
		$data = array();
        foreach($constant->result() as $r) {
              $edit_perm='';
              $delete_perm='';
              if(in_array('54e',role_resource_ids())) {
                $edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->type_id . '" data-field_type="leave_type"><i class="icon-pencil7"></i> Edit</a></li>';
              }
              if(in_array('54d',role_resource_ids())) {
                $delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->type_id . '" data-token_type="leave_type"><i class="icon-trash"></i> Delete</a></li>';
              }
		      $data[] = array(
                    $r->type_name,
			        $r->days_per_year,
			        '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$delete_perm.'</ul></li></ul>'
		      );
        }

	    $output = array(
		        "draw" => $draw,
			    "recordsTotal" => $constant->num_rows(),
			    "recordsFiltered" => $constant->num_rows(),
			    "data" => $data
		);
        $this->output($output);
    }

	
	public function salary_type_list()
    {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		$draw = intval($this->input->get("draw"));
		$constant = $this->Xin_model->get_salary_type();
		$data = array();
        foreach($constant->result() as $r) {
              $edit_perm='';
              $delete_perm='';
              if(in_array('54e',role_resource_ids())) {
                $edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->type_id . '" data-field_type="salary_type"><i class="icon-pencil7"></i> Edit</a></li>';
              }
              if(in_array('54d',role_resource_ids())) {
                $delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->type_id . '" data-token_type="salary_type"><i class="icon-trash"></i> Delete</a></li>';
              }
              if($r->adjustment_type==0){
                $type='<span class="label label-success">Internal</span>';
              }else{
                $type='<span class="label label-info">External</span>';
              }

		      $data[] = array(
                $r->type_name,
                $r->is_parent,
                $type,
                '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$delete_perm.'</ul></li></ul>'
		     );
        }
	    $output = array(
		        "draw" => $draw,
			    "recordsTotal" => $constant->num_rows(),
			    "recordsFiltered" => $constant->num_rows(),
			    "data" => $data
		);
        $this->output($output);
    }

    public function warning_type_list()
    {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}

		$draw = intval($this->input->get("draw"));
		$constant = $this->Xin_model->get_warning_type();
		$data = array();
		foreach($constant->result() as $r) {
              $edit_perm='';
              $delete_perm='';
              if(in_array('54e',role_resource_ids())) {
                $edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->type_id . '" data-field_type="warning_type"><i class="icon-pencil7"></i> Edit</a></li>';
              }
              if(in_array('54d',role_resource_ids())) {
                $delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->type_id . '" data-token_type="warning_type"><i class="icon-trash"></i> Delete</a></li>';
              }
              $data[] = array(
                      $r->type_name,
                      '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$delete_perm.'</ul></li></ul>'
              );
        }

	    $output = array(
		        "draw" => $draw,
			    "recordsTotal" => $constant->num_rows(),
			    "recordsFiltered" => $constant->num_rows(),
			    "data" => $data
		);
		$this->output($output);
    }

	public function termination_type_list()
    {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		$draw = intval($this->input->get("draw"));
		$constant = $this->Xin_model->get_termination_type();
		$data = array();
        foreach($constant->result() as $r) {
                $edit_perm='';
                $delete_perm='';
                if(in_array('54e',role_resource_ids())) {
                    $edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->type_id . '" data-field_type="termination_type"><i class="icon-pencil7"></i> Edit</a></li>';
                }
                if(in_array('54d',role_resource_ids())) {
                    $delete_perm=' <li><a class="delete" href="#" data-toggle="modal" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->
                    type_id . '" data-token_type="termination_type"><i class="icon-trash"></i> Delete</a></li>';
                }
                $data[] = array(
                $r->type_name,
                '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$delete_perm.'</ul></li></ul>'
                );
        }
	    $output = array(
		        "draw" => $draw,
			    "recordsTotal" => $constant->num_rows(),
			    "recordsFiltered" => $constant->num_rows(),
			    "data" => $data
		);
	    $this->output($output);
    }

	public function expense_type_list()
    {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		$draw = intval($this->input->get("draw"));
		$constant = $this->Xin_model->get_expense_type();
		$data = array();
        foreach($constant->result() as $r) {
                 $edit_perm='';
                 $delete_perm='';
                 if(in_array('54e',role_resource_ids())) {
                    $edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->type_id . '" data-field_type="expense_type"><i class="icon-pencil7"></i> Edit</a></li>';
                 }
                 if(in_array('54d',role_resource_ids())) {
                    $delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->type_id . '" data-token_type="expense_type"><i class="icon-trash"></i> Delete</a></li>';
                 }
                 $data[] = array(
                        $r->type_name,
                        '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$delete_perm.'</ul></li></ul>'
                 );
        }
	    $output = array(
		        "draw" => $draw,
			    "recordsTotal" => $constant->num_rows(),
			    "recordsFiltered" => $constant->num_rows(),
			    "data" => $data
		);
        $this->output($output);
    }

	
	public function job_type_list()
    {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		$draw = intval($this->input->get("draw"));
		$constant = $this->Xin_model->get_job_type();
		$data = array();
        foreach($constant->result() as $r) {
              $edit_perm='';
              $delete_perm='';
              if(in_array('54e',role_resource_ids())) {
                $edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->type_id . '" data-field_type="job_type"><i class="icon-pencil7"></i> Edit</a></li>';
              }
              if(in_array('54d',role_resource_ids())) {
                $delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->type_id . '" data-token_type="job_type"><i class="icon-trash"></i> Delete</a></li>';
              }
              $data[] = array(
                    $r->type_name,
                    '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$delete_perm.'</ul></li></ul>'
              );
        }
	    $output = array(
		        "draw" => $draw,
			    "recordsTotal" => $constant->num_rows(),
			    "recordsFiltered" => $constant->num_rows(),
			    "data" => $data
		);
        $this->output($output);
    }

    public function company_type_list()
    {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$constant = $this->Xin_model->get_company_type();
		$data = array();
        foreach($constant->result() as $r) {
              $edit_perm='';
              $delete_perm='';
              if(in_array('54e',role_resource_ids())) {
                $edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->type_id . '" data-field_type="company_type"><i class="icon-pencil7"></i> Edit</a></li>';
              }
              if(in_array('54d',role_resource_ids())) {
                $delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->type_id . '" data-token_type="company_type"><i class="icon-trash"></i> Delete</a></li>';
              }
              $data[] = array(
                    $r->type_name,
                    '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$delete_perm.'</ul></li></ul>'
              );
        }
        $output = array(
		        "draw" => $draw,
			    "recordsTotal" => $constant->num_rows(),
			    "recordsFiltered" => $constant->num_rows(),
			    "data" => $data
		);
        $this->output($output);
    }

    public function tax_type_list()
    {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		$draw = intval($this->input->get("draw"));
		$tax = $this->Xin_model->get_tax_type();
		$data = array();
        foreach($tax->result() as $r) {
		    $edit_perm='';
		    $delete_perm='';
		    if(in_array('54e',role_resource_ids())) {
			    $edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->type_id . '" data-field_type="tax_type"><i class="icon-pencil7"></i> Edit</a></li>';
		    }
		    if(in_array('54d',role_resource_ids())) {
			    $delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->type_id . '" data-token_type="tax_type"><i class="icon-trash"></i> Delete</a></li>';
		    }

            $type_symbol=explode(',',$r->type_code);
            $tax_t='';
            foreach($type_symbol as $typ){
                $tax_type=$this->Xin_model->read_document_type($typ,'visa_under');
                $tax_t.='<span class="label label-info mr-5 mt-5">'.$tax_type[0]->type_name.'</span>';
            }
            $data[] = array(
                $r->type_name,
                $tax_t,
                $r->type_symbol,
                '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$delete_perm.'</ul></li></ul>'
            );
      }
	  $output = array(
		        "draw" => $draw,
			    "recordsTotal" => $tax->num_rows(),
			    "recordsFiltered" => $tax->num_rows(),
			    "data" => $data
      );
      $this->output($output);
    }

	public function exit_type_list()
    {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		$draw = intval($this->input->get("draw"));
		$constant = $this->Xin_model->get_exit_type();
		$data = array();
		foreach($constant->result() as $r) {
              $edit_perm='';
              $delete_perm='';
              if(in_array('54e',role_resource_ids())) {
                $edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->type_id . '" data-field_type="exit_type"><i class="icon-pencil7"></i> Edit</a></li>';
              }
              if(in_array('54d',role_resource_ids())) {
                $delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->type_id . '" data-token_type="exit_type"><i class="icon-trash"></i> Delete</a></li>';
              }
              $data[] = array(
                $r->type_name,
                '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$delete_perm.'</ul></li></ul>'
              );
        }
        $output = array(
		     "draw" => $draw,
			 "recordsTotal" => $constant->num_rows(),
			 "recordsFiltered" => $constant->num_rows(),
			 "data" => $data
		);
        $this->output($output);
    }

	public function travel_arr_type_list()
    {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		$draw = intval($this->input->get("draw"));
		$constant = $this->Xin_model->get_travel_type();
		$data = array();
        foreach($constant->result() as $r) {
		  $edit_perm='';
		  $delete_perm='';
		  if(in_array('54e',role_resource_ids())) {
			$edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->type_id . '" data-field_type="travel_arr_type"><i class="icon-pencil7"></i> Edit</a></li>';
		  }
		  if(in_array('54d',role_resource_ids())) {
			$delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->type_id . '" data-token_type="travel_arr_type"><i class="icon-trash"></i> Delete</a></li>';
		  }
		  $data[] = array(
			$r->type_name,
			'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$delete_perm.'</ul></li></ul>'
		  );
        }
	    $output = array(
		     "draw" => $draw,
			 "recordsTotal" => $constant->num_rows(),
			 "recordsFiltered" => $constant->num_rows(),
			 "data" => $data
		);
        $this->output($output);
    }

	public function payment_method_list()
    {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		$draw = intval($this->input->get("draw"));
		$constant = $this->Xin_model->get_payment_method();
		$data = array();
        foreach($constant->result() as $r) {
              $edit_perm='';
              $delete_perm='';
              if(in_array('54e',role_resource_ids())) {
                $edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->type_id . '" data-field_type="payment_method"><i class="icon-pencil7"></i> Edit</a></li>';
              }
              if(in_array('54d',role_resource_ids())) {
                $delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->type_id . '" data-token_type="payment_method"><i class="icon-trash"></i> Delete</a></li>';
              }
              $data[] = array(
                $r->type_name,
                '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$delete_perm.'</ul></li></ul>'
              );
        }
	    $output = array(
		     "draw" => $draw,
			 "recordsTotal" => $constant->num_rows(),
			 "recordsFiltered" => $constant->num_rows(),
			 "data" => $data
		);
        $this->output($output);
    }

    public function currency_type_list()
    {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		$draw = intval($this->input->get("draw"));
		$constant = $this->Xin_model->get_currency_types();
		$data = array();
        foreach($constant->result() as $r) {
              $edit_perm='';
              $delete_perm='';
              if(in_array('54e',role_resource_ids())) {
                    $edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->type_id . '" data-field_type="currency_type"><i class="icon-pencil7"></i> Edit</a></li>';
              }
              if(in_array('54d',role_resource_ids())) {
                    $delete_perm='<li><a class="delete" href="#" data-toggle="modal" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->type_id . '" data-token_type="currency_type"><i class="icon-trash"></i> Delete</a></li>';
              }

              if($r->type_code=='AED'){
                    $function='<span class="label label-info">Default</span>';
              }else{
                    $function='<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$delete_perm.'</ul></li></ul>';
              }

              $data[] = array(
                    //'<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="'. $r->currency_id . '" data-field_type="currency_type"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->currency_id . '" title="Delete" data-token_type="currency_type"><i class="fa fa-trash-o"></i></button></span>',data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->currency_id . '" title="Delete" data-token_type="currency_type"><i class="fa fa-trash-o"></i></button></span>',
                    $r->type_name,
                    $r->type_code,
                    $r->type_symbol,
                    $function,
              );
        }
	    $output = array(
		     "draw" => $draw,
			 "recordsTotal" => $constant->num_rows(),
			 "recordsFiltered" => $constant->num_rows(),
			 "data" => $data
		);
        $this->output($output);
    }

	/*  Add constant data */
	public function contract_type_info() {
		if($this->input->post('type')=='contract_type_info') {
            $Return = array('result'=>'', 'error'=>'');
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('contract_type'),'','contract_type');
            if($this->input->post('contract_type')==='') {
                $Return['error'] = "The contract type field is required.";
            } else if($check_unique_names!=0) {
                $Return['error'] = "The contract type already exist.Enter different one..";
            }
            if($Return['error']!=''){
                $this->output($Return);
            }
            $data = array(
                'type_name' => $this->input->post('contract_type'),
                'type_of_module' => 'contract_type',
                'created_at' => date('d-m-Y h:i:s')
            );
            $result = $this->Xin_model->add_document_type($data);
            /*User Logs*/
            $affected_id= table_max_id('xin_module_types','type_id','contract_type');
            userlogs('Constants-Contract Type-Add','Contract Type Added',$affected_id['field_id'],$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Contract type added.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function document_type_info() {
		if($this->input->post('type')=='document_type_info') {
            $Return = array('result'=>'', 'error'=>'');
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('document_type'),'','document_type');
            if($this->input->post('document_type')==='') {
                $Return['error'] = "The document type field is required.";
            } else if($check_unique_names!=0) {
                $Return['error'] = "The document type already exist.Enter different one..";
            }
            if($Return['error']!=''){
                $this->output($Return);
            }
            $data = array(
                'type_name' => $this->input->post('document_type'),
                'type_of_module' => 'document_type',
                'type_code' => implode(',',$this->input->post('country_doc')),
                'created_at' => date('d-m-Y h:i:s')
            );
            $result = $this->Xin_model->add_document_type($data);
            /*User Logs*/
            $affected_id= table_max_id('xin_module_types','type_id','document_type');
            userlogs('Constants-Document Type-Add','Document Type Added',$affected_id['field_id'],$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Document type added.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function visa_under_info() {
		//if($this->input->post('add_type')=='visa_under_info') {
		$Return = array('result'=>'', 'error'=>'');
		$check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('visa_under'),'','visa_under');
		if($this->input->post('visa_under')==='') {
        	$Return['error'] = "The visa under field is required.";
		} else if($check_unique_names!=0) {
        	$Return['error'] = "The visa under already exist.Enter different one..";
		}

		if($Return['error']!=''){
       		$this->output($Return);
    	}

		$data = array(
            'type_name' => $this->input->post('visa_under'),
            'type_of_module' => 'visa_under',
            'created_at' => date('d-m-Y h:i:s')
		);
		$result = $this->Xin_model->add_document_type($data);
		/*User Logs*/
		$affected_id= table_max_id('xin_module_types','type_id','visa_under');
		userlogs('Constants-Visa Type-Add','Visa Type Added',$affected_id['field_id'],$affected_id['datas']);
		/*User Logs*/
		if ($result == TRUE) {
			$Return['result'] = 'Visa under added.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		//}
	}

	public function medical_card_type_info() {
		//if($this->input->post('add_type')=='visa_under_info') {
        $Return = array('result'=>'', 'error'=>'');
		$check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('medical_card_type'),'','medical_card_type');
		if($this->input->post('medical_card_type')==='') {
        	$Return['error'] = "The medical card type field is required.";
		}else if($check_unique_names!=0) {
        	$Return['error'] = "The medical card type already exist.Enter different one..";
		}

		if($Return['error']!=''){
       		$this->output($Return);
    	}

		$data = array(
            'type_name' => $this->input->post('medical_card_type'),
            'no_of_dependant' => $this->input->post('no_of_dependant'),
            'type_of_module' => 'medical_card_type',
            'created_at' => date('d-m-Y h:i:s')
		);
		$result = $this->Xin_model->add_document_type($data);
		/*User Logs*/
		$affected_id= table_max_id('xin_module_types','type_id','medical_card_type');
		userlogs('Constants-Medical Card Type-Add','Medical Card Type Added',$affected_id['field_id'],$affected_id['datas']);
		/*User Logs*/
		if ($result == TRUE) {
			$Return['result'] = 'Medical card type added.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		//}
	}

	public function edu_level_info() {
		if($this->input->post('type')=='edu_level_info') {
            $Return = array('result'=>'', 'error'=>'');
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('name'),'','education_level');
            if($this->input->post('name')==='') {
                $Return['error'] = "The education level field is required.";
            } else if($check_unique_names!=0){
                $Return['error'] = 'The education level name already exist.Enter different one.';
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'type_name' => $this->input->post('name'),
                'type_of_module' => 'education_level',
                'created_at' => date('d-m-Y h:i:s')
            );
            $result = $this->Xin_model->add_document_type($data);
            /*User Logs*/
            $affected_id= table_max_id('xin_module_types','type_id','education_level');
            userlogs('Constants-Education Level-Add','Education Level Added',$affected_id['field_id'],$affected_id['datas']);
            /*User Logs*/

            if ($result == TRUE) {
                $Return['result'] = 'Education Level added.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function edu_language_info() {
		if($this->input->post('type')=='edu_language_info') {
            $Return = array('result'=>'', 'error'=>'');

            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('name'),'','qualification_language');
            if($this->input->post('name')==='') {
                $Return['error'] = "The education language field is required.";
            }  else if($check_unique_names!=0){
                $Return['error'] = 'The education language already exist.Enter different one.';
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'type_name' => $this->input->post('name'),
                'type_of_module' => 'qualification_language',
                'created_at' => date('d-m-Y h:i:s')
            );
            $result = $this->Xin_model->add_document_type($data);
            /*User Logs*/
            $affected_id= table_max_id('xin_module_types','type_id','qualification_language');
            userlogs('Constants-Education Language-Add','Education Language Added',$affected_id['field_id'],$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Education Language added.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function edu_skill_info() {
		if($this->input->post('type')=='edu_skill_info') {
            $Return = array('result'=>'', 'error'=>'');

            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('name'),'','qualification_skill');
            if($this->input->post('name')==='') {
                $Return['error'] = "The education skill field is required.";
            } else if($check_unique_names!=0){
                $Return['error'] = 'The education skill already exist.Enter different one.';
            }
            if($Return['error']!=''){
                $this->output($Return);
            }
            $data = array(
                'type_name' => $this->input->post('name'),
                'type_of_module' => 'qualification_skill',
                'created_at' => date('d-m-Y h:i:s')
            );

            $result = $this->Xin_model->add_document_type($data);
            /*User Logs*/
            $affected_id= table_max_id('xin_module_types','type_id','qualification_skill');
            userlogs('Constants-Education Skill-Add','Education Skill Added',$affected_id['field_id'],$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Education Skill added.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function payment_method_info() {
		if($this->input->post('type')=='payment_method_info') {
            $Return = array('result'=>'', 'error'=>'');
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('payment_method'),'','payment_method');
            if($this->input->post('payment_method')==='') {
                $Return['error'] = "The payment method field is required.";
            } else if($check_unique_names!=0){
              $Return['error'] = 'The payment method already exist.Enter different one.';
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'type_name' => $this->input->post('payment_method'),
                'type_of_module' => 'payment_method',
                'created_at' => date('d-m-Y h:i:s')
            );

            $result = $this->Xin_model->add_document_type($data);
            /*User Logs*/
            $affected_id= table_max_id('xin_module_types','type_id','payment_method');
            userlogs('Constants-Payment Method-Add','Payment Method Added',$affected_id['field_id'],$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Payment Method added.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function award_type_info() {
		if($this->input->post('type')=='award_type_info') {
            $Return = array('result'=>'', 'error'=>'');
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('award_type'),'','award_type');
            if($this->input->post('award_type')==='') {
              $Return['error'] = "The award type field is required.";
            } else if($check_unique_names!=0){
              $Return['error'] = 'The award type already exist.Enter different one.';
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'type_name' => $this->input->post('award_type'),
                'type_of_module' => 'award_type',
                'created_at' => date('d-m-Y h:i:s')
            );
            $result = $this->Xin_model->add_document_type($data);
            /*User Logs*/
            $affected_id= table_max_id('xin_module_types','type_id','award_type');
            userlogs('Constants-Award Type-Add','Award Type Added',$affected_id['field_id'],$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Award Type added.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function ob_type_info() {
		if($this->input->post('type')=='ob_type_info') {
            $Return = array('result'=>'', 'error'=>'');
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('ob_type'),'','ob_type');
            if($this->input->post('ob_type')==='') {
              $Return['error'] = "The OB type field is required.";
            } else if($check_unique_names!=0){
              $Return['error'] = 'The OB type already exist.Enter different one.';
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'type_name' => $this->input->post('ob_type'),
                'type_of_module' => 'ob_type',
                'created_at' => date('d-m-Y h:i:s')
            );
            $result = $this->Xin_model->add_document_type($data);
            /*User Logs*/
            $affected_id= table_max_id('xin_module_types','type_id','ob_type');
            userlogs('Constants-OB Type-Add','OB Type Added',$affected_id['field_id'],$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'OB Type added.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}


	public function leave_type_info() {
		if($this->input->post('type')=='leave_type_info') {
            $Return = array('result'=>'', 'error'=>'');
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('leave_type'),'','leave_type');
            if($this->input->post('leave_type')==='') {
                $Return['error'] = "The leave type field is required.";
            } else if($check_unique_names!=0){
                $Return['error'] = 'The leave type already exist.Enter different one.';
            } else if($this->input->post('days_per_year')==='') {
                $Return['error'] = "The days per year field is required.";
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'type_name' => $this->input->post('leave_type'),
                'days_per_year' => $this->input->post('days_per_year'),
                'type_of_module' => 'leave_type',
                'created_at' => date('d-m-Y h:i:s')
            );
            $result = $this->Xin_model->add_document_type($data);
            /*User Logs*/
            $affected_id= table_max_id('xin_module_types','type_id','leave_type');
            userlogs('Constants-Leave Type-Add','Leave Type Added',$affected_id['field_id'],$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Leave Type added.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function salary_type_info() {
		if($this->input->post('type')=='salary_type_info') {
            $Return = array('result'=>'', 'error'=>'');
            $check_is_salarytype = $this->Xin_model->check_salary_type($this->input->post('type_name'),$this->input->post('type_parent'),'');

            if($this->input->post('type_name')==='') {
                $Return['error'] = "The salary type field is required.";
            }else if($this->input->post('type_parent')==='') {
                $Return['error'] = "The parent type field is required.";
            }else if($check_is_salarytype!=0){
                 $Return['error'] = "The Salary type already added.";
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $parent=$this->input->post('type_parent');
            if($parent!=0){
                $get_action_type=$this->Xin_model->get_action_type($parent);
            }else{
                $get_action_type=$this->input->post('action_type');
            }
            $data = array(
                'type_name' => $this->input->post('type_name'),
                'type_parent' => $this->input->post('type_parent'),
                'created_by' => $this->userSession['user_id'],
                'action' => $get_action_type,
                'adjustment_type' => $this->input->post('adjustment_type'),
                'created_date' => date('Y-m-d h:i:s')
            );

            $result = $this->Xin_model->add_salary_type($data);

            /*User Logs*/
            $affected_id= table_max_id('xin_salary_types','type_id');
            userlogs('Constants-Salary Type-Add','Salary Type Added',$affected_id['field_id'],$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Salary Type added.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function tax_type_info() {
		if($this->input->post('type')=='tax_type_info') {
            $Return = array('result'=>'', 'error'=>'');
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('tax_name'),'','tax_type');
            if($this->input->post('tax_name')==='') {
                $Return['error'] = "The tax name field is required.";
            } else if($check_unique_names!=0){
                $Return['error'] = 'The tax name already exist.Enter different one.';
            } else if($this->input->post('tax_percentage')==='') {
                $Return['error'] = "The tax percentage field is required.";
            } else if($this->input->post('visa_type')==='') {
                $Return['error'] = "The visa type field is required.";
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'type_name' => $this->input->post('tax_name'),
                'type_code' => implode(',',$this->input->post('visa_type')),
                'type_symbol' => $this->input->post('tax_percentage'),
                'type_of_module' => 'tax_type',
                'created_at' => date('d-m-Y h:i:s')
            );
            $result = $this->Xin_model->add_document_type($data);
            /*User Logs*/
            $affected_id= table_max_id('xin_module_types','type_id','tax_type');
            userlogs('Constants-Tax Type-Add','Tax Type Added',$affected_id['field_id'],$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Tax Type added.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

    public function update_tax_type() {
		if($this->input->post('type')=='edit_record') {
            $Return = array('result'=>'', 'error'=>'');
            $id = $this->uri->segment(3);
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('tax_name'),$id,'tax_type');
            if($this->input->post('tax_name')==='') {
                $Return['error'] = "The tax name field is required.";
            } else if($check_unique_names!=0){
                $Return['error'] = 'The tax name already exist.Enter different one.';
            } else if($this->input->post('tax_percentage')==='') {
                $Return['error'] = "The tax percentage field is required.";
            } else if($this->input->post('visa_type')==='') {
                $Return['error'] = "The visa type field is required.";
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'type_name' => $this->input->post('tax_name'),
                'type_code' => implode(',',$this->input->post('visa_type')),
                'type_symbol' => $this->input->post('tax_percentage'),
            );
            $result = $this->Xin_model->update_document_type_record($data,$id);
            /*User Logs*/
            $affected_id= table_max_id('xin_module_types','type_id','tax_type');
            userlogs('Constants-Tax Type-Update','Tax Type Updated',$affected_id['field_id'],$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Tax Type updated.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
    }

	public function update_salary_type() {
		if($this->input->post('type')=='ed_salary_type_info') {
            $id = $this->uri->segment(3);
            $Return = array('result'=>'', 'error'=>'');
            $check_is_salarytype = $this->Xin_model->check_salary_type($this->input->post('type_name'),$this->input->post('type_parent'),$id);
            if($this->input->post('type_name')==='') {
                $Return['error'] = "The salary type field is required.";
            }else if($this->input->post('type_parent')==='') {
                $Return['error'] = "The parent type field is required.";
            }else if($check_is_salarytype!=0){
                 $Return['error'] = "The Salary type already added.";
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $parent=$this->input->post('type_parent');
            if($parent!=0){
                $get_action_type=$this->Xin_model->get_action_type($parent);
            }else{
                $get_action_type=$this->input->post('action_type');
            }
            $data = array(
            'type_name' => $this->input->post('type_name'),
            'type_parent' => $this->input->post('type_parent'),
            'created_by' => $this->userSession['user_id'],
            'action' => $get_action_type,
            'adjustment_type' => $this->input->post('adjustment_type'),
            'created_date' => date('Y-m-d h:i:s')
            );

            $result = $this->Xin_model->update_salary_type_record($data,$id);
            /*User Logs*/
            $affected_id= table_update_id('xin_salary_types','type_id',$id);
            userlogs('Constants-Salary Type-Update','Salary Type Updated',$id,$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Salary Type Updated.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function salary_parent_type(){
         $parent_type = $this->Xin_model->ready_salary_types();
         $datas='<select name="type_parent"  id="select2-demo-6" class="form-control salary_parent_type" onchange="salary_parent_type();" data-plugin="select_hrm" data-placeholder="Choose Parent Type..."><option value="0">Parent</option>';
		 foreach($parent_type as $parent) {
          $datas.='<option value="'.$parent->type_id.'">'.$parent->type_name.'</option>';
		 }
		 $datas.='</select>';
         echo $datas;
	}

	public function warning_type_info() {
		if($this->input->post('type')=='warning_type_info') {
            $Return = array('result'=>'', 'error'=>'');
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('warning_type'),'','warning_type');
            /* Server side PHP input validation */
            if($this->input->post('warning_type')==='') {
                $Return['error'] = "The warning type field is required.";
            } else if($check_unique_names!=0){
                $Return['error'] = 'The warning type already exist.Enter different one.';
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'type_name' => $this->input->post('warning_type'),
                'type_of_module' => 'warning_type',
                'created_at' => date('d-m-Y h:i:s')
            );

            $result = $this->Xin_model->add_document_type($data);
            /*User Logs*/
            $affected_id= table_max_id('xin_module_types','type_id','warning_type');
            userlogs('Constants-Warning Type-Add','Warning Type Added',$affected_id['field_id'],$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Warning Type added.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function termination_type_info() {
		if($this->input->post('type')=='termination_type_info') {
            $Return = array('result'=>'', 'error'=>'');
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('termination_type'),'','termination_type');
            if($this->input->post('termination_type')==='') {
                $Return['error'] = "The termination type field is required.";
            }  else if($check_unique_names!=0){
                $Return['error'] = 'The termination type already exist.Enter different one.';
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'type_name' => $this->input->post('termination_type'),
                'type_of_module' => 'termination_type',
                'created_at' => date('d-m-Y h:i:s')
            );
            $result = $this->Xin_model->add_document_type($data);
            /*User Logs*/
            $affected_id= table_max_id('xin_module_types','type_id','termination_type');
            userlogs('Constants-Termination Type-Add','Termination Type Added',$affected_id['field_id'],$affected_id['datas']);
            /*User Logs*/

            if ($result == TRUE) {
                $Return['result'] = 'Termination Type added.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function expense_type_info() {
		if($this->input->post('type')=='expense_type_info') {
            $Return = array('result'=>'', 'error'=>'');
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('expense_type'),'','expense_type');
            if($this->input->post('expense_type')==='') {
                $Return['error'] = "The expense type field is required.";
            } else if($check_unique_names!=0){
                $Return['error'] = 'The expense type already exist.Enter different one.';
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'type_name' => $this->input->post('expense_type'),
                'type_of_module' => 'expense_type',
                'created_at' => date('d-m-Y h:i:s')
            );

            $result = $this->Xin_model->add_document_type($data);
            /*User Logs*/
            $affected_id= table_max_id('xin_module_types','type_id','expense_type');
            userlogs('Constants-Expense Type-Add','Expense Type Added',$affected_id['field_id'],$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Expense Type added.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function job_type_info() {
		if($this->input->post('type')=='job_type_info') {
            $Return = array('result'=>'', 'error'=>'');
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('job_type'),'','job_type');
            if($this->input->post('job_type')==='') {
                $Return['error'] = "The job type field is required.";
            } else if($check_unique_names!=0){
                $Return['error'] = 'The job type already exist.Enter different one.';
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'type_name' => $this->input->post('job_type'),
                'type_of_module' => 'job_type',
                'created_at' => date('d-m-Y h:i:s')
            );
            $result = $this->Xin_model->add_document_type($data);
            /*User Logs*/
            $affected_id= table_max_id('xin_module_types','type_id','job_type');
            userlogs('Constants-Job Type-Add','Job Type Added',$affected_id['field_id'],$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Job Type added.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function company_type_info() {
		if($this->input->post('type')=='company_type_info') {
            $Return = array('result'=>'', 'error'=>'');
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('company_type'),'','company_type');
            if($this->input->post('company_type')==='') {
                $Return['error'] = "The company type field is required.";
            } else if($check_unique_names!=0){
                $Return['error'] = 'The company type already exist.Enter different one.';
            }
            if($Return['error']!=''){
                $this->output($Return);
            }
            $data = array(
                'type_name' => $this->input->post('company_type'),
                'type_of_module' => 'company_type',
                'created_at' => date('d-m-Y h:i:s')
            );
            $result = $this->Xin_model->add_document_type($data);
            /*User Logs*/
            $affected_id= table_max_id('xin_module_types','type_id','company_type');
            userlogs('Constants-Job Type-Add','Company Type Added',$affected_id['field_id'],$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Company Type added.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function exit_type_info() {
		if($this->input->post('type')=='exit_type_info') {
            $Return = array('result'=>'', 'error'=>'');
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('exit_type'),'','exit_type');
            if($this->input->post('exit_type')==='') {
                $Return['error'] = "The exit type field is required.";
            } else if($check_unique_names!=0){
                $Return['error'] = 'The exit type already exist.Enter different one.';
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'type_name' => $this->input->post('exit_type'),
                'type_of_module' => 'exit_type',
                'created_at' => date('d-m-Y h:i:s')
            );

            $result = $this->Xin_model->add_document_type($data);
            /*User Logs*/
            $affected_id= table_max_id('xin_module_types','type_id','exit_type');
            userlogs('Constants-Exit Type-Add','Exit Type Added',$affected_id['field_id'],$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Exit Type added.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function travel_arr_type_info() {
		if($this->input->post('type')=='travel_arr_type_info') {
            $Return = array('result'=>'', 'error'=>'');
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('travel_arr_type'),'','travel_arrangement_type');
            if($this->input->post('travel_arr_type')==='') {
                $Return['error'] = "The travel arrangement type field is required.";
            } else if($check_unique_names!=0){
                $Return['error'] = 'The travel type already exist.Enter different one.';
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'type_name' => $this->input->post('travel_arr_type'),
                'type_of_module' => 'travel_arrangement_type',
                'created_at' => date('d-m-Y h:i:s')
            );
            $result = $this->Xin_model->add_document_type($data);
            /*User Logs*/
            $affected_id= table_max_id('xin_module_types','type_id','travel_arrangement_type');
            userlogs('Constants-Travel Arrangement Type-Add','Travel Arrangement Type Added',$affected_id['field_id'],$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Travel Arrangement Type added.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function currency_type_info() {
		if($this->input->post('type')=='currency_type_info') {
            $Return = array('result'=>'', 'error'=>'');
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('name'),'','currency_type');$check_unique_names1= $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('code'),'','currency_type');

            if($this->input->post('name')==='') {
                $Return['error'] = "The currency name field is required.";
            } else if($check_unique_names!=0){
                $Return['error'] = 'The currency name already exist.Enter different one.';
            } else if($this->input->post('code')==='') {
                $Return['error'] = "The currency code field is required.";
            } else if($check_unique_names1!=0){
                $Return['error'] = 'The currency code already exist.Enter different one.';
            }  else if($this->input->post('symbol')==='') {
                $Return['error'] = "The currency symbol field is required.";
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'type_name' => $this->input->post('name'),
                'type_code' => $this->input->post('code'),
                'type_symbol' => $this->input->post('symbol'),
                'type_of_module' => 'currency_type',
                'created_at' => date('d-m-Y h:i:s')
            );

            $result = $this->Xin_model->add_document_type($data);
            /*User Logs*/
            $affected_id= table_max_id('xin_module_types','type_id','currency_type');
            userlogs('Constants-Currency-Add','Currency Added',$affected_id['field_id'],$affected_id['datas']);
            /*User Logs*/

            if ($result == TRUE) {
                $Return['result'] = 'Currency added.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}
    /*  Add constant data */

	/*  DELETE CONSTANTS */
	public function delete_contract_type() {
		if($this->input->post('form')=='delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			/*User Logs*/
			$affected_row= table_deleted_row('xin_module_types','type_id',$id,'contract_type');
			userlogs('Constants-Contract Type-Delete','Contract Type Deleted',$id,$affected_row);
			/*User Logs*/
			$result = $this->Xin_model->delete_document_type_record($id,'contract_type');
			if($result=='') {
				$Return['result'] = 'Contract Type deleted.';
			} else {
				$Return['error'] = $result;
			}
			$this->output($Return);
		}
	}

	public function delete_document_type() {
		if($this->input->post('form')=='delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			/*User Logs*/
			$affected_row= table_deleted_row('xin_module_types','type_id',$id,'document_type');
			userlogs('Constants-Document Type-Delete','Document Type Deleted',$id,$affected_row);
			/*User Logs*/
			$result = $this->Xin_model->delete_document_type_record($id,'document_type');
			if($result=='') {
				$Return['result'] = 'Document Type deleted.';
			} else {
				$Return['error'] = $result;
			}
			$this->output($Return);
		}
	}

	public function delete_visa_under() {
		if($this->input->post('form')=='delete_record') {
			$Return = array('result'=>'', 'error'=>'');
		    $id = $this->input->post('_token');
			/*User Logs*/
			$affected_row= table_deleted_row('xin_module_types','type_id',$id,'visa_under');
			userlogs('Constants-Visa Type-Delete','Visa Type Deleted',$id,$affected_row);
			/*User Logs*/
			$result = $this->Xin_model->delete_document_type_record($id,'visa_under');
			if($result=='') {
				$Return['result'] = 'Visa under deleted.';
			} else {
				$Return['error'] = $result;
			}
			$this->output($Return);
		}
	}

	public function delete_medical_card_type() {
		if($this->input->post('form')=='delete_record') {
			$Return = array('result'=>'', 'error'=>'');
		    $id = $this->input->post('_token');
			/*User Logs*/
			$affected_row= table_deleted_row('xin_module_types','type_id',$id,'medical_card_type');
			userlogs('Constants-Medical Card Type-Delete','Medical Card Type Deleted',$id,$affected_row);
			/*User Logs*/
			$result = $this->Xin_model->delete_document_type_record($id,'medical_card_type');
			if($result=='') {
				$Return['result'] = 'Medical Card Type deleted.';
			} else {
				$Return['error'] = $result;
			}
			$this->output($Return);
		}
	}

	public function delete_payment_method() {
		if($this->input->post('form')=='delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			/*User Logs*/
			$affected_row= table_deleted_row('xin_module_types','type_id',$id,'payment_method');
			userlogs('Constants-Payment Method-Delete','Payment Method Deleted',$id,$affected_row);
			/*User Logs*/
            $result = $this->Xin_model->delete_document_type_record($id,'payment_method');
			if($result=='') {
				$Return['result'] = 'Payment Method deleted.';
			} else {
				$Return['error'] = $result;
			}
			$this->output($Return);
		}
	}

	public function delete_education_level() {
		if($this->input->post('form')=='delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			/*User Logs*/
			$affected_row= table_deleted_row('xin_module_types','type_id',$id,'education_level');
			userlogs('Constants-Education Level-Delete','Education Level Deleted',$id,$affected_row);
			/*User Logs*/
		    $result = $this->Xin_model->delete_document_type_record($id,'education_level');
			if($result=='') {
				$Return['result'] = 'Education Level deleted.';
			} else {
				$Return['error'] = $result;
			}
			$this->output($Return);
		}
	}

	public function delete_qualification_language() {
		if($this->input->post('form')=='delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			/*User Logs*/
		    $affected_row= table_deleted_row('xin_module_types','type_id',$id,'qualification_language');
			userlogs('Constants-Education Language-Delete','Education Language Deleted',$id,$affected_row);
			/*User Logs*/
			$result = $this->Xin_model->delete_document_type_record($id,'qualification_language');
			if($result=='') {
				$Return['result'] = 'Qualification Language deleted.';
			} else {
				$Return['error'] = $result;
			}
			$this->output($Return);
		}
	}

	public function delete_qualification_skill() {
		if($this->input->post('form')=='delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			/*User Logs*/
			$affected_row= table_deleted_row('xin_module_types','type_id',$id,'qualification_skill');
			userlogs('Constants-Education Skill-Delete','Education Skill Deleted',$id,$affected_row);
			/*User Logs*/
			$result = $this->Xin_model->delete_document_type_record($id,'qualification_skill');
			if($result=='') {
				$Return['result'] = 'Qualification Skill deleted.';
			} else {
				$Return['error'] = $result;
			}
			$this->output($Return);
		}
	}

	public function delete_award_type() {
		if($this->input->post('form')=='delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			/*User Logs*/
			$affected_row= table_deleted_row('xin_module_types','type_id',$id,'award_type');
			userlogs('Constants-Award Type-Delete','Award Type Deleted',$id,$affected_row);
			/*User Logs*/
			$result = $this->Xin_model->delete_document_type_record($id,'award_type');
			if($result=='') {
				$Return['result'] = 'Award Type deleted.';
			} else {
				$Return['error'] = $result;
			}
			$this->output($Return);
		}
	}

	public function delete_ob_type() {
		if($this->input->post('form')=='delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			/*User Logs*/
			$affected_row= table_deleted_row('xin_module_types','type_id',$id,'ob_type');
			userlogs('Constants-OB Type-Delete','OB Type Deleted',$id,$affected_row);
			/*User Logs*/
			$result = $this->Xin_model->delete_document_type_record($id,'ob_type');
			if($result=='') {
				$Return['result'] = 'OB Type deleted.';
			} else {
				$Return['error'] = $result;
			}
			$this->output($Return);
		}
	}

	public function delete_leave_type() {
		if($this->input->post('form')=='delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			/*User Logs*/
			$affected_row= table_deleted_row('xin_module_types','type_id',$id,'leave_type');
			userlogs('Constants-Leave Type-Delete','Leave Type Deleted',$id,$affected_row);
			/*User Logs*/
			$result = $this->Xin_model->delete_document_type_record($id,'leave_type');
			if($result=='') {
				$Return['result'] = 'Leave Type deleted.';
			} else {
				$Return['error'] = $result;
			}
			$this->output($Return);
		}
	}

	public function delete_salary_type() {
		if($this->input->post('form')=='delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			/*User Logs*/
			$affected_row= table_deleted_row('xin_salary_types','type_id',$id);
			userlogs('Constants-Salary Type-Delete','Salary Type Deleted',$id,$affected_row);
			/*User Logs*/
			$this->Xin_model->delete_salary_type_record($id);
			if(isset($id)) {
				$Return['result'] = 'Salary Type deleted.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
		}
	}

	public function delete_warning_type() {
		if($this->input->post('form')=='delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			/*User Logs*/
			$affected_row= table_deleted_row('xin_module_types','type_id',$id,'warning_type');
			userlogs('Constants-Warning Type-Delete','Warning Type Deleted',$id,$affected_row);
			/*User Logs*/
		    $result = $this->Xin_model->delete_document_type_record($id,'warning_type');
			if($result=='') {
				$Return['result'] = 'Warning Type deleted.';
			} else {
				$Return['error'] = $result;
			}
			$this->output($Return);
		}
	}

	public function delete_termination_type() {
		if($this->input->post('form')=='delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			/*User Logs*/
			$affected_row= table_deleted_row('xin_module_types','type_id',$id,'termination_type');
			userlogs('Constants-Termination Type-Delete','Termination Type Deleted',$id,$affected_row);
			/*User Logs*/
			$result = $this->Xin_model->delete_document_type_record($id,'termination_type');
			if($result=='') {
				$Return['result'] = 'Termination Type deleted.';
			} else {
				$Return['error'] = $result;
			}
			$this->output($Return);
		}
	}

	public function delete_expense_type() {
		if($this->input->post('form')=='delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			/*User Logs*/
			$affected_row= table_deleted_row('xin_module_types','type_id',$id,'expense_type');
			userlogs('Constants-Expense Type-Delete','Expense Type Deleted',$id,$affected_row);
			/*User Logs*/
			$result = $this->Xin_model->delete_document_type_record($id,'expense_type');
			if($result=='') {
				$Return['result'] = 'Expense Type deleted.';
			} else {
				$Return['error'] = $result;
			}
			$this->output($Return);
		}
	}

	public function delete_job_type() {
		if($this->input->post('form')=='delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			/*User Logs*/
			$affected_row= table_deleted_row('xin_module_types','type_id',$id,'job_type');
			userlogs('Constants-Job Type-Delete','Job Type Deleted',$id,$affected_row);
			/*User Logs*/
	    	$result = $this->Xin_model->delete_document_type_record($id,'job_type');
			if($result=='') {
				$Return['result'] = 'Job Type deleted.';
			} else {
				$Return['error'] = $result;
			}
			$this->output($Return);
		}
	}

	public function delete_company_type() {
		if($this->input->post('form')=='delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			/*User Logs*/
			$affected_row= table_deleted_row('xin_module_types','type_id',$id,'company_type');
			userlogs('Constants-Company Type-Delete','Job Type Deleted',$id,$affected_row);
			/*User Logs*/
	    	$result = $this->Xin_model->delete_document_type_record($id,'company_type');
			if($result=='') {
				$Return['result'] = 'Company Type deleted.';
			} else {
				$Return['error'] = $result;
			}
			$this->output($Return);
		}
	}

	public function delete_exit_type() {
		if($this->input->post('form')=='delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			/*User Logs*/
			$affected_row= table_deleted_row('xin_module_types','type_id',$id,'exit_type_id');
			userlogs('Constants-Exit Type-Delete','Exit Type Deleted',$id,$affected_row);
			/*User Logs*/
			$result = $this->Xin_model->delete_document_type_record($id,'exit_type');
			if($result=='') {
				$Return['result'] = 'Exit Type deleted.';
			} else {
				$Return['error'] = $result;
			}
			$this->output($Return);
		}
	}

	public function delete_tax_type() {
		if($this->input->post('form')=='delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			/*User Logs*/
			$affected_row= table_deleted_row('xin_module_types','type_id',$id,'tax_type_id');
			userlogs('Constants-Tax Type-Delete','Tax Type Deleted',$id,$affected_row);
			/*User Logs*/
			$result = $this->Xin_model->delete_document_type_record($id,'tax_type');
			if($result=='') {
				$Return['result'] = 'Tax Type deleted.';
			} else {
				$Return['error'] = $result;
			}
			$this->output($Return);
		}
	}

	public function delete_travel_arr_type() {
		if($this->input->post('form')=='delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			/*User Logs*/
			$affected_row= table_deleted_row('xin_module_types','type_id',$id,'travel_arrangement_type');
			userlogs('Constants-Travel Arrangement Type-Delete','Travel Arrangement Type Deleted',$id,$affected_row);
			/*User Logs*/
			$result = $this->Xin_model->delete_document_type_record($id,'travel_arrangement_type');
    		if($result=='') {
				$Return['result'] = 'Travel Arrangement Type deleted.';
			} else {
				$Return['error'] = $result;
			}
			$this->output($Return);
		}
	}

	public function delete_currency_type() {
		if($this->input->post('form')=='delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$id = $this->uri->segment(3);
			/*User Logs*/
			$affected_row= table_deleted_row('xin_module_types','type_id',$id,'currency_type');
			userlogs('Constants-Currency-Delete','Currency Deleted',$id,$affected_row);
			/*User Logs*/
			$result = $this->Xin_model->delete_document_type_record($id,'currency_type');
			if($result=='') {
				$Return['result'] = 'Currency deleted.';
			} else {
				$Return['error'] = $result;
			}
			$this->output($Return);
		}
	}

	public function constants_read()
	{
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view('settings/dialog_constants', $data);
		} else {
			redirect('');
		}
	}
    /*  DELETE CONSTANTS */

	/*  UPDATE RECORD > CONSTANTS  */
	public function update_document_type() {
		if($this->input->post('type')=='edit_record') {
            $id = $this->uri->segment(3);
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('name'),$id,'document_type');
            $Return = array('result'=>'', 'error'=>'');
            if($this->input->post('name')==='') {
                $Return['error'] = "The document type field is required.";
            } else if($check_unique_names!=0){
                $Return['error'] = 'The document type already exist.Enter different one.';
            }
            if($Return['error']!=''){
                $this->output($Return);
            }
            $data = array(
                'type_name' => $this->input->post('name'),
                'type_code' => implode(',',$this->input->post('country_doc')),
            );
            $result = $this->Xin_model->update_document_type_record($data,$id);
            /*User Logs*/
            $affected_id= table_update_id('xin_module_types','type_id',$id);
            userlogs('Constants-Document Type-Update','Document Type Updated',$id,$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Document type updated.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function update_visa_under() {
		if($this->input->post('type')=='edit_record') {
            $id = $this->uri->segment(3);
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('name'),$id,'visa_under');
            $Return = array('result'=>'', 'error'=>'');
            if($this->input->post('name')==='') {
                $Return['error'] = "The visa under field is required.";
            }  else if($check_unique_names!=0) {
                $Return['error'] = "The visa under already exist.Enter different one..";
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'type_name' => $this->input->post('name')
            );
            $result = $this->Xin_model->update_document_type_record($data,$id);
            /*User Logs*/
            $affected_id= table_update_id('xin_module_types','type_id',$id);
            userlogs('Constants-Visa Type-Update','Visa Type Updated',$id,$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Visa under updated.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function update_medical_card_type() {
		if($this->input->post('type')=='edit_record') {
            $id = $this->uri->segment(3);
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('name'),$id,'medical_card_type');
            $Return = array('result'=>'', 'error'=>'');
            /* Server side PHP input validation */
            if($this->input->post('name')==='') {
                $Return['error'] = "The Medical card type field is required.";
            }  else if($check_unique_names!=0) {
                $Return['error'] = "The Medical card type already exist.Enter different one..";
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'type_name' => $this->input->post('name'),
                'no_of_dependant' => $this->input->post('no_of_dependant')
            );
            $result = $this->Xin_model->update_document_type_record($data,$id);
            /*User Logs*/
            $affected_id= table_update_id('xin_module_types','type_id',$id);
            userlogs('Constants-Medical Card Type-Update','Medical Card Type Updated',$id,$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Medical card type updated.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function update_contract_type() {
		if($this->input->post('type')=='edit_record') {
            $id = $this->uri->segment(3);
            $Return = array('result'=>'', 'error'=>'');
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('name'),$id,'contract_type');
            if($this->input->post('name')==='') {
                $Return['error'] = "The contract type field is required.";
            } else if($check_unique_names!=0){
                $Return['error'] = 'The contract type already exist.Enter different one.';
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
            'type_name' => $this->input->post('name')
            );

            $result = $this->Xin_model->update_document_type_record($data,$id);
            /*User Logs*/
            $affected_id= table_update_id('xin_module_types','type_id',$id);
            userlogs('Constants-Contract Type-Update','Contract Type Updated',$id,$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Contract type updated.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function update_payment_method() {
		if($this->input->post('type')=='edit_record') {
            $id = $this->uri->segment(3);
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('name'),$id,'payment_method');
            $Return = array('result'=>'', 'error'=>'');
            if($this->input->post('name')==='') {
                $Return['error'] = "The payment method field is required.";
            } else if($check_unique_names!=0){
                $Return['error'] = 'The payment method already exist.Enter different one.';
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
            'type_name' => $this->input->post('name')
            );

            $result = $this->Xin_model->update_document_type_record($data,$id);
            /*User Logs*/
            $affected_id= table_update_id('xin_payment_method','payment_method_id',$id);
            userlogs('Constants-Payment Method-Update','Payment Method Updated',$id,$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Payment Method updated.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function update_education_level() {
		if($this->input->post('type')=='edit_record') {
            $id = $this->uri->segment(3);
            $Return = array('result'=>'', 'error'=>'');
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('name'),$id,'education_level');
            if($this->input->post('name')==='') {
                $Return['error'] = "The education level field is required.";
            } else if($check_unique_names!=0){
                $Return['error'] = 'The education level name already exist.Enter different one.';
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
             'type_name' => $this->input->post('name')
            );
            $result = $this->Xin_model->update_document_type_record($data,$id);
            /*User Logs*/
            $affected_id= table_update_id('xin_module_types','type_id',$id);
            userlogs('Constants-Education Level-Update','Education Level Updated',$id,$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Education Level updated.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function update_qualification_language() {
		if($this->input->post('type')=='edit_record') {
            $id = $this->uri->segment(3);
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('name'),$id,'qualification_language');
            $Return = array('result'=>'', 'error'=>'');
            if($this->input->post('name')==='') {
                $Return['error'] = "The qualification language field is required.";
            } else if($check_unique_names!=0){
                $Return['error'] = 'The qualification language already exist.Enter different one.';
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'type_name' => $this->input->post('name')
            );

            $result = $this->Xin_model->update_document_type_record($data,$id);
            /*User Logs*/
            $affected_id= table_update_id('xin_qualification_language','language_id',$id);
            userlogs('Constants-Education Language-Update','Education Language Updated',$id,$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Qualification Language Updated.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function update_qualification_skill() {
		if($this->input->post('type')=='edit_record') {
            $id = $this->uri->segment(3);
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('name'),$id,'qualification_skill');
            $Return = array('result'=>'', 'error'=>'');
            if($this->input->post('name')==='') {
                $Return['error'] = "The qualification skill field is required.";
            } else if($check_unique_names!=0){
                $Return['error'] = 'The qualification skill already exist.Enter different one.';
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                 'type_name' => $this->input->post('name')
            );
            $result = $this->Xin_model->update_document_type_record($data,$id);
            /*User Logs*/
            $affected_id= table_update_id('xin_module_types','type_id',$id);
            userlogs('Constants-Education Skill-Update','Education Skill Updated',$id,$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Qualification Skill Updated.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function update_award_type() {
		if($this->input->post('type')=='edit_record') {
            $id = $this->uri->segment(3);
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('name'),$id,'award_type');
            $Return = array('result'=>'', 'error'=>'');
            if($this->input->post('name')==='') {
                $Return['error'] = "The award type field is required.";
            } else if($check_unique_names!=0){
                $Return['error'] = 'The award type already exist.Enter different one.';
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'type_name' => $this->input->post('name')
            );
            $result = $this->Xin_model->update_document_type_record($data,$id);
            /*User Logs*/
            $affected_id= table_update_id('xin_module_types','type_id',$id);
            userlogs('Constants-Award Type-Update','Award Type Updated',$id,$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Award Type Updated.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function update_ob_type() {
		if($this->input->post('type')=='edit_record') {
            $id = $this->uri->segment(3);
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('name'),$id,'ob_type');
            $Return = array('result'=>'', 'error'=>'');
            if($this->input->post('name')==='') {
                $Return['error'] = "The OB type field is required.";
            } else if($check_unique_names!=0){
                $Return['error'] = 'The OB type already exist.Enter different one.';
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'type_name' => $this->input->post('name')
            );
            $result = $this->Xin_model->update_document_type_record($data,$id);
            /*User Logs*/
            $affected_id= table_update_id('xin_module_types','type_id',$id);
            userlogs('Constants-OB Type-Update','OB Type Updated',$id,$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'OB Type Updated.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}


	public function update_leave_type() {
		if($this->input->post('type')=='edit_record') {
            $id = $this->uri->segment(3);
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('name'),$id,'leave_type');
            $Return = array('result'=>'', 'error'=>'');
            if($this->input->post('name')==='') {
                $Return['error'] = "The leave type field is required.";
            } else if($check_unique_names!=0){
                $Return['error'] = 'The leave type already exist.Enter different one.';
            } else if($this->input->post('days_per_year')==='') {
                $Return['error'] = "The days per year field is required.";
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'type_name' => $this->input->post('name'),
                'days_per_year' => $this->input->post('days_per_year')
            );
            $result = $this->Xin_model->update_document_type_record($data,$id);
            /*User Logs*/
            $affected_id= table_update_id('xin_module_types','type_id',$id);
            userlogs('Constants-Leave Type-Update','Leave Type Updated',$id,$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Leave type updated.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function update_warning_type() {
		if($this->input->post('type')=='edit_record') {
            $id = $this->uri->segment(3);
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('name'),$id,'warning_type');
            $Return = array('result'=>'', 'error'=>'');
            if($this->input->post('name')==='') {
                $Return['error'] = "The warning type field is required.";
            } else if($check_unique_names!=0){
                $Return['error'] = 'The warning type already exist.Enter different one.';
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
            'type_name' => $this->input->post('name')
            );

            $result = $this->Xin_model->update_document_type_record($data,$id);
            /*User Logs*/
            $affected_id= table_update_id('xin_module_types','type_id',$id);
            userlogs('Constants-Warning Type-Update','Warning Type Updated',$id,$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Warning type updated.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function update_termination_type() {
		if($this->input->post('type')=='edit_record') {
            $id = $this->uri->segment(3);
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('name'),$id,'termination_type');
            $Return = array('result'=>'', 'error'=>'');
            if($this->input->post('name')==='') {
                $Return['error'] = "The termination type field is required.";
            }  else if($check_unique_names!=0){
                $Return['error'] = 'The termination type already exist.Enter different one.';
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
            'type_name' => $this->input->post('name')
            );

            $result = $this->Xin_model->update_document_type_record($data,$id);
            /*User Logs*/
            $affected_id= table_update_id('xin_module_types','type_id',$id);
            userlogs('Constants-Termination Type-Update','Termination Type Updated',$id,$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Termination type updated.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function update_expense_type() {
		if($this->input->post('type')=='edit_record') {
            $id = $this->uri->segment(3);
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('name'),$id,'expense_type');
            $Return = array('result'=>'', 'error'=>'');
            if($this->input->post('name')==='') {
                $Return['error'] = "The expense type field is required.";
            } else if($check_unique_names!=0){
                $Return['error'] = 'The expense type already exist.Enter different one.';
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
            'type_name' => $this->input->post('name')
            );

            $result = $this->Xin_model->update_document_type_record($data,$id);
            /*User Logs*/
            $affected_id= table_update_id('xin_module_types','type_id',$id);
            userlogs('Constants-Expense Type-update','Expense Type Updated',$id,$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Expense type updated.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function update_job_type() {
		if($this->input->post('type')=='edit_record') {
            $id = $this->uri->segment(3);
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('name'),$id,'job_type');
            $Return = array('result'=>'', 'error'=>'');
            /* Server side PHP input validation */
            if($this->input->post('name')==='') {
                $Return['error'] = "The job type field is required.";
            } else if($check_unique_names!=0){
                $Return['error'] = 'The job type already exist.Enter different one.';
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
            'type_name' => $this->input->post('name')
            );

            $result = $this->Xin_model->update_document_type_record($data,$id);
            /*User Logs*/
            $affected_id= table_update_id('xin_module_types','type_id',$id);
            userlogs('Constants-Job Type-Update','Job Type Updated',$id,$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Job type updated.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function update_company_type() {
		if($this->input->post('type')=='edit_record') {
            $id = $this->uri->segment(3);
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('name'),$id,'company_type');
            $Return = array('result'=>'', 'error'=>'');
            if($this->input->post('name')==='') {
                $Return['error'] = "The company type field is required.";
            } else if($check_unique_names!=0){
                $Return['error'] = 'The company type already exist.Enter different one.';
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
            'type_name' => $this->input->post('name')
            );

            $result = $this->Xin_model->update_document_type_record($data,$id);
            /*User Logs*/
            $affected_id= table_update_id('xin_module_types','type_id',$id);
            userlogs('Constants-Company Type-Update','Company Type Updated',$id,$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Company type updated.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function update_exit_type() {
		if($this->input->post('type')=='edit_record') {
            $id = $this->uri->segment(3);
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('name'),$id,'exit_type');
            $Return = array('result'=>'', 'error'=>'');
            if($this->input->post('name')==='') {
                $Return['error'] = "The exit type field is required.";
            }  else if($check_unique_names!=0){
                $Return['error'] = 'The exit type already exist.Enter different one.';
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
            'type_name' => $this->input->post('name')
            );

            $result = $this->Xin_model->update_document_type_record($data,$id);
            /*User Logs*/
            $affected_id= table_update_id('xin_module_types','type_id',$id);
            userlogs('Constants-Exit Type-Update','Exit Type Updated',$id,$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Exit type updated.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function update_travel_arr_type() {
		if($this->input->post('type')=='edit_record') {
            $id = $this->uri->segment(3);
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('name'),$id,'travel_arrangement_type');
            $Return = array('result'=>'', 'error'=>'');
            /* Server side PHP input validation */
            if($this->input->post('name')==='') {
                $Return['error'] = "The travel arrangement type field is required.";
            }  else if($check_unique_names!=0){
                $Return['error'] = 'The travel arrangement type already exist.Enter different one.';
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
            'type_name' => $this->input->post('name')
            );

            $result = $this->Xin_model->update_document_type_record($data,$id);
            /*User Logs*/
            $affected_id= table_update_id('xin_module_types','type_id',$id);
            userlogs('Constants-Travel Arrangement Type-Update','Travel Arrangement Type Updated',$id,$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Travel Arrangement type updated.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function update_currency_type() {
		if($this->input->post('type')=='edit_record') {
            $id = $this->uri->segment(3);
            $check_unique_names = $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('name'),$id,'currency_type');$check_unique_names1= $this->Xin_model->check_unique_type_names('xin_module_types','type_name','type_id',$this->input->post('code'),$id,'currency_type');
            $Return = array('result'=>'', 'error'=>'');

            /* Server side PHP input validation */
            if($this->input->post('name')==='') {
                $Return['error'] = "The currency name field is required.";
            }  else if($check_unique_names!=0){
                $Return['error'] = 'The currency name already exist.Enter different one.';
            }  else if($this->input->post('code')==='') {
                $Return['error'] = "The currency code field is required.";
            }  else if($check_unique_names1!=0){
                $Return['error'] = 'The currency code already exist.Enter different one.';
            }   else if($this->input->post('symbol')==='') {
                $Return['error'] = "The currency symbol field is required.";
            }  else if($check_unique_names!=0){
                $Return['error'] = 'The currency name already exist.Enter different one.';
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'type_name' => $this->input->post('name'),
                'type_code' => $this->input->post('code'),
                'type_symbol' => $this->input->post('symbol')
            );

            $result = $this->Xin_model->update_document_type_record($data,$id);
            /*User Logs*/
            $affected_id= table_update_id('xin_module_types','type_id',$id);
            userlogs('Constants-Currency-Update','Currency Updated',$id,$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Currency updated.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}
	
	/*  UPDATE RECORD > CONSTANTS  */
	public function leaves_upload()
    {
		$data['title'] = $this->Xin_model->site_title();
		$data['breadcrumbs'] = 'Import Data';
		$data['path_url'] = 'leaves_upload';
		if(in_array('57',role_resource_ids())) {
			if(!empty($this->userSession)){
			$data['subview'] = $this->load->view("settings/leaves_upload", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
    }

    public function exports_leave_conversion_data(){
        $data[] = array('a'=> 'Employee Id', 'b'=> 'Leave Conversion Days', 'c'=> 'Leave Conversion Comments');
        $data[] = array('a'=> addslashes('0000000xxx'), 'b'=> '30', 'c'=> 'LEAVE CONVERSION');
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"leave_cash_conversion".".csv\"");
        header("Content-type: application/octet-stream");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        foreach ($data as $data) {
            fputcsv($handle, $data);
        }
        fclose($handle);
        exit;
    }

    public function exports_employee_data(){
            $data[] = array('a'=> 'Employee Id(1c ID)', 'b'=> 'Name', 'c'=> 'NATIONALITY','d'=> 'GENDER', 'e'=> 'MARITAL STATUS', 'f'=> 'Date of Birth','g'=> 'Designation per Contract', 'h'=> 'Designation Per Offer Letter/ Internal Designation', 'i'=> 'Department','j'=> 'Visa Under', 'k'=> 'Area', 'l'=> 'NO. OF WORK SCHEDULE(Include Lunch)', 'm'=> 'Status', 'n'=> 'Hire Date', 'o'=> 'Last Date', 'p'=> 'Visa Issue Date', 'q'=> 'Visa Expiry Date', 'r'=> 'Visa Number', 's'=> 'Contract Start Date', 't'=> 'Contract End Date', 'u'=> 'Emirates ID', 'v'=> 'Labour Card No', 'w'=> 'Labour Expiry Date', 'x'=> 'Passport Number', 'y'=> 'Passport Date', 'z'=> 'Passport Expiration','aa'=> 'Basic Salary', 'ab'=> 'Accomodaton', 'ac'=> 'Transport Allowance','ad'=> 'Food Allowance', 'ae'=> 'Additional Benefits', 'af'=> 'Other', 'ag'=> 'Bonus', 'ag1'=> 'Agreed Bonus(For Drivers)','ah'=> 'Salary Based On Contract', 'ai'=> 'Salary With Bonus', 'aj'=> 'Effective From Date', 'ak'=> 'Biometric Id','al'=> 'Agency Fees', 'am'=> 'Agency Fees Compute With Hours(Yes-1,No-0)', 'an'=> 'Email');
            $data[] = array('a'=> '0000000XXX', 'b'=> 'Mohamed', 'c'=> 'Indian','d'=> 'Male', 'e'=> 'Single', 'f'=> '01-02-1970','g'=> 'Web Developer', 'h'=> 'PHP Developer', 'i'=> 'WDD','j'=> 'DMCC', 'k'=> 'JLT', 'l'=> '9', 'm'=> 'Active', 'n'=> '01-01-2017', 'o'=> '01-01-2018', 'p'=> '10-01-2018', 'q'=> '09-01-2021', 'r'=> '784-1988-999999-1', 's'=> '01-01-2017', 't'=> '01-01-2020', 'u'=> '784-1977-2602695-4', 'v'=> '222879', 'w'=> '20-Aug-18', 'x'=> 'A16889541', 'y'=> '29-Nov-15', 'z'=> '28-Nov-22','aa'=> '1000', 'ab'=> '1000', 'ac'=> '1200','ad'=> '1200', 'ae'=> '', 'af'=> '', 'ag'=> '5000','ag1'=> '500', 'ah'=> '4600', 'ai'=> '9600', 'aj'=> '01-01-2018','ak'=> '1111','al'=> '1200', 'am'=> '1', 'an'=> 'abc@gmail.com');
            header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=\"employee_details".".csv\"");
			header("Content-type: application/octet-stream");
            header("Pragma: no-cache");
            header("Expires: 0");
            $handle = fopen('php://output', 'w');
            foreach ($data as $data) {
                fputcsv($handle, $data);
            }
            fclose($handle);
            exit;
    }

    public function exports_leaves_data(){
            $data[] = array('a'=> 'Employee Id', 'c'=> 'Count of Leaves', 'd'=> 'Start Date', 'e'=> 'End Date', 'f'=> 'Leave Status Code');
			$data[] = array('a'=> addslashes('0000000xxx'), 'c'=> '10', 'd'=> '01-01-2017', 'e'=> '10-01-2017', 'f'=> 'AL');
			$data[] = array('a'=> addslashes('0000000xxx'), 'c'=> '8', 'd'=> '01-01-2017', 'e'=> '08-01-2017' ,'f'=> 'SL-1');
			$data[] = array('a'=> addslashes('0000000xxx'), 'c'=> '10', 'd'=> '01-01-2017', 'e'=> '10-01-2017', 'f'=> 'SL-2');
			$data[] = array('a'=> addslashes('0000000xxx'), 'c'=> '22', 'd'=> '01-01-2017', 'e'=> '22-01-2017', 'f'=> 'SL-UP');
			$data[] = array('a'=> addslashes('0000000xxx'), 'c'=> '12', 'd'=> '01-01-2017', 'e'=> '12-01-2017' ,'f'=> 'EL');
			$data[] = array('a'=> addslashes('0000000xxx'), 'c'=> '2', 'd'=> '01-01-2017', 'e'=> '02-01-2017' ,'f'=> 'AA');
			$data[] = array('a'=> addslashes('0000000xxx'), 'c'=> '4', 'd'=> '01-01-2017', 'e'=> '04-01-2017' ,'f'=> 'ML-1');
			$data[] = array('a'=> addslashes('0000000xxx'), 'c'=> '5', 'd'=> '01-01-2017', 'e'=> '05-01-2017', 'f'=> 'ML-2');
			$data[] = array('a'=> addslashes('0000000xxx'), 'c'=> '9', 'd'=> '01-01-2017', 'e'=> '09-01-2017' ,'f'=> 'ML-UP');

            header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=\"leaves".".csv\"");
			header("Content-type: application/octet-stream");
            header("Pragma: no-cache");
            header("Expires: 0");
            $handle = fopen('php://output', 'w');

            foreach ($data as $data) {
                fputcsv($handle, $data);
            }
            fclose($handle);
            exit;
    }
	
	public function preview_upload() {
		if($this->input->post('add_type')=='leaves_upload') {
		$Return = array('result'=>'', 'error'=>'', 'message'=>'');
        if($this->input->post('leave_type')==''){
			$Return['error'] = 'Import type field is required.';
		}
		if($Return['error']!=''){
       		$this->output($Return);
    	}
   		$csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
		if(empty($_FILES['file']['name'])) {
			$Return['error'] = 'Please upload csv file.';
		}
		else{
			if(in_array($_FILES['file']['type'],$csvMimes)){
				if(is_uploaded_file($_FILES['file']['tmp_name'])){
					// check file size
					//if(filesize($_FILES['file']['size']) > 512000) {
						//$Return['error'] = 'File size is greater than 500 KB';
					//} else {

					//open uploaded csv file with read only mode
					$csvFile = fopen($_FILES['file']['tmp_name'], 'r');

					//skip first line
					fgetcsv($csvFile);
					if($this->input->post('leave_type')=='Leave'){
					if($this->input->post('preview_type')=='preview'){
					$html='<div class="table-responsive panel-body">
					<small class="help-block text-success">* Success Rows (Only this rows will be upload.)</small>
					<small class="help-block text-danger">* Error Rows (Check the rows & rectify it.)</small>
					<table class="table table-md table-bordered"><tbody><tr class="bg-slate-600 text-center"><th>Name</th><th>Employee Id</th><th>Availed Leaves</th><th>Start Date</th><th>End Date</th><th>Leave Status Code</th></tr>';
					//parse data from csv file line by line

					while(($line = fgetcsv($csvFile)) !== FALSE){
                           $employee_id=$line[0];
                           $available_leaves=$line[1];
                           $total_count_of_leaves=$line[1];
                           $start_date=$line[2];
                           $end_date=$line[3];
                           $leave_status_code=@$line[4];
                           $count_of_line=count($line);
                           $user_check=$this->Employees_model->get_employee_unique_id($employee_id);
                           $leave_name='';

                           if($leave_status_code=='AL'){
                                $leave_name='Annual Leave';
                           }else if($leave_status_code=='EL'){
                                $leave_name='Emergency Leave';
                           }else if($leave_status_code=='ML-1' || $leave_status_code=='ML-2' || $leave_status_code=='ML-UP'){
                                $leave_name='Maternity Leave';
                           }else if($leave_status_code=='SL-1' || $leave_status_code=='SL-2' || $leave_status_code=='SL-UP'){
                                $leave_name='Sick Leave';
                           }else if($leave_status_code=='AA'){
                                $leave_name='Authorised Absence';
                           }

                           $leave_id=$this->Timesheet_model->read_leave_type_id($leave_name);
                           if($count_of_line!=5){
                                $Return['error'] = 'Download the correct leave format. It should be 5 columns';
                           }

                           if($employee_id=='' || $total_count_of_leaves=='' || $start_date=='' || $end_date=='' || $leave_status_code=='' || $leave_id=='' || $count_of_line!=5){
                            $class="class='danger'";
                           }else{
                                if($user_check){
                                    $class="class='success'";
                                }
                                else{
                                    $class="class='danger'";
                                }
                           }
                           $html.='<tr '.$class.'><td>'.$user_check[0]->first_name.' '.$user_check[0]->middle_name.' '.$user_check[0]->last_name.' ('.$user_check[0]->user_id.')</td><td>'.$employee_id.'</td><td>'.$total_count_of_leaves.'</td><td>'.$this->Xin_model->set_date_format($start_date).'</td><td>'.$this->Xin_model->set_date_format($end_date).'</td><td>'.$leave_name.' ('.$leave_status_code.')</td></tr>';
				    }

                           $html.='</tbody></table><button type="submit" onclick="change_slug();"  class="btn bg-teal-400 save save1 mt-10 pull-right">Upload</button></div>';
                           //close opened csv file
                           $Return['message'] = $html;
                           $Return['result'] = '';
				}
				else{

					$updated_count=[];
					$not_updated_count=[];
					$no_count=[];

					$i=0;
					while(($line = fgetcsv($csvFile)) !== FALSE){
	                   $employee_id=$line[0];
					   $available_leaves=$line[1];
					   $total_count_of_leaves=$line[1];
					   $start_date=format_date('Y-m-d',$line[2]);
					   $end_date=format_date('Y-m-d',$line[3]);
					   $user_check=$this->Employees_model->get_employee_unique_id($employee_id);
					   $rand=strtotime(date('Y-m-d H:i:s')).$i;
					   $leave_status_code=@$line[4];
					   $emp_id_len=strlen($employee_id);
					   if($leave_status_code=='AL'){
						   $leave_name='Annual Leave';
					   }else if($leave_status_code=='EL'){
						   $leave_name='Emergency Leave';
					   }else if($leave_status_code=='ML-1' || $leave_status_code=='ML-2' || $leave_status_code=='ML-UP'){
						   $leave_name='Maternity Leave';
					   }else if($leave_status_code=='SL-1' || $leave_status_code=='SL-2' || $leave_status_code=='SL-UP'){
						   $leave_name='Sick Leave';
					   }else if($leave_status_code=='AA'){
						   $leave_name='Authorised Absence';
					   }
					   $leave_id=$this->Timesheet_model->read_leave_type_id($leave_name);
					   if($employee_id!='' && $available_leaves!='' && $total_count_of_leaves!='' && $start_date!='' && $end_date!='' && $leave_status_code!='' && $leave_id!=''){
					   if($user_check){
						$user_id =$user_check[0]->user_id;
						$condition = "l_a.employee_id = '".$user_id."' AND l_a.leave_type_id='".$leave_id."' AND l_a.leave_status_code='".$leave_status_code."'  AND ((l_a.from_date BETWEEN '".$start_date."' AND '".$end_date."') OR (l_a.to_date BETWEEN '".$start_date."' AND '".$end_date."') OR (l_a.from_date <= '".$start_date."' AND l_a.to_date >= '".$end_date."'))";
						$this->db->select('l_a.leave_id');
						$this->db->from('xin_leave_applications as l_a');
						$this->db->where($condition);
						$this->db->limit(1);
						$query = $this->db->get();

						if ($query->num_rows() == 0) {
							$data = array(
							'employee_id' => $user_id,
							'leave_type_id' => $leave_id,
							'from_date' => $start_date,
							'to_date' => $end_date,
							'available_leave_days' => $available_leaves,
							'count_of_days' => $total_count_of_leaves,
							'reporting_manager_status' => 2,
							'status' => 2,
							'leave_status_code' => $leave_status_code,
							'reason' => $leave_status_code,
							'created_at' => date('Y-m-d h:i:s'),
							'applied_on' => $rand,
							);

						$result = $this->Timesheet_model->add_leave_record($data);
						$updated_count[]=$user_id;
						} else {
							$result=$query->result();
							$updated_leave_id=$result[0]->leave_id;
							$data = array(
							'employee_id' => $user_id,
							'leave_type_id' => $leave_id,
							'from_date' => $start_date,
							'to_date' => $end_date,
							'available_leave_days' => $available_leaves,
							'count_of_days' => $total_count_of_leaves,
							'reporting_manager_status' => 2,
							'status' => 2,
							'leave_status_code' => $leave_status_code,
							'reason' => $leave_status_code,
							'created_at' => date('Y-m-d h:i:s'),
							);
						$result = $this->Timesheet_model->update_leave_record($data,$updated_leave_id);
						$not_updated_count[]=$user_id;
						}
    	                }
					   }else{
					   $no_count[]=$i;
					   }
					$i++;
				    }
					$message='';
					$total_updated_count=count($updated_count);
					if($total_updated_count!=0){
						$message.='<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered">
										<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button><span class="text-semibold">'.$total_updated_count.'</span> new record/s uploaded into database. '.'</div>';
					}
					$total_not_updated_count=count($not_updated_count);
					if($total_not_updated_count!=0){
						$message.='<div class="alert alert-info alert-styled-left alert-bordered">
										<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
										<span class="text-semibold">'.$total_not_updated_count.'</span>  record/s already exists in database.'.'</div>';
					}
					$total_no_count=count($no_count);
					if($total_no_count!=0){
						$message.='<div class="alert alert-danger alert-styled-left alert-bordered">
										<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
										<span class="text-semibold">'.$total_no_count.'</span>  record/s not insert into database.'.'</div>';
					}




                    $Return['message'] = $message;
                    if($total_updated_count==0 && $total_not_updated_count==0){
                    $Return['error'] = 'File not imported.Check your file that all fields are filled.';
                    }else if($total_updated_count!=0){
                    $Return['result'] = 'File imported successfully.';
                    }
                    }
				    fclose($csvFile);
				    //}
					}
					else if($this->input->post('leave_type')=='Leave_Cash_Conversion'){ //leave cash Conversion

					if($this->input->post('preview_type')=='preview'){
					$html='<div class="table-responsive panel-body">
					<small class="help-block text-success">* Success Rows (Only this rows will be upload.)</small>
					<small class="help-block text-danger">* Error Rows (Check the rows & rectify it.)</small>
					<table class="table table-md table-bordered"><tbody><tr class="bg-slate-600 text-center"><th>Name</th><th>Employee Id</th><th>Leave Conversion Count</th><th>Conversion Comments</th></tr>';
					//parse data from csv file line by line

					while(($line = fgetcsv($csvFile)) !== FALSE){
	                   $employee_id=$line[0];
					   $cash_conversion_counts=$line[1];
					   $cash_conversion_comments=htmlspecialchars($line[2]);
					   $count_of_line=count($line);
					   $user_check=$this->Employees_model->get_employee_unique_id($employee_id);

					   if($count_of_line!=3){
						$Return['error'] = 'Download the correct leave conversion format. It should be 3 columns';
					   }

					if($employee_id=='' || $cash_conversion_counts=='' || $count_of_line!=3){
						$class="class='danger'";
						}else{
						if($user_check){
						$class="class='success'";
						}
						else{
						$class="class='danger'";
						}
						}
       				   $html.='<tr '.$class.'><td>'.$user_check[0]->first_name.' '.$user_check[0]->middle_name.' '.$user_check[0]->last_name.' ('.$user_check[0]->user_id.')</td><td>'.$employee_id.'</td><td>'.$cash_conversion_counts.'</td><td>'.$cash_conversion_comments.'</td></tr>';


				}
				$html.='</tbody></table><button type="submit" onclick="change_slug();"  class="btn bg-teal-400 save save1 mt-10 pull-right">Upload</button></div>';
				//close opened csv file
				$Return['message'] = $html;
				$Return['result'] = '';
				}else{

					$updated_count=[];
					$not_updated_count=[];
					$no_count=[];

                    $i=0;
					while(($line = fgetcsv($csvFile)) !== FALSE){
	                   $employee_id=$line[0];
					   $cash_conversion_counts=$line[1];
					   $cash_conversion_comments=htmlspecialchars($line[2]);
					   $user_check=$this->Employees_model->get_employee_unique_id($employee_id);
					   $emp_id_len=strlen($employee_id);
					   if($employee_id!='' && $cash_conversion_counts!='' && $emp_id_len==10){
					   if($user_check){

						$user_id =$user_check[0]->user_id;
						$condition = "l_a.employee_id = '".$user_id."' AND l_a.leave_conversion_count='".$cash_conversion_counts."' AND l_a.conversion_comments='".$cash_conversion_comments."'";
						$this->db->select('l_a.conversion_id');
						$this->db->from('xin_leave_conversion_count as l_a');
						$this->db->where($condition);
						$this->db->limit(1);
						$query = $this->db->get();

						if ($query->num_rows() == 0) {
							$data = array(
							'employee_id' => $user_id,
							'leave_conversion_count' => $cash_conversion_counts,
							'conversion_comments' => $cash_conversion_comments,
							'added_date' => date('Y-m-d'),
							'added_by' => $this->userSession['user_id'],
							'updated_by' => $this->userSession['user_id'],
							'created_at' => date('Y-m-d h:i:s'),
							'leave_conversion_type' => 0,
							'hr_notification' => 0,
							'approved_status' => 1,
							);

						$result = $this->Timesheet_model->add_leave_conversion($data);
						$updated_count[]=$user_id;
						} else {
						$not_updated_count[]=$user_id;
						}
    	                }
					   }else{
					   $no_count[]=$i;
					   }
					$i++;
				    }
					$message='';
					$total_updated_count=count($updated_count);
					if($total_updated_count!=0){
						$message.='<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered">
										<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button><span class="text-semibold">'.$total_updated_count.'</span> new record/s uploaded into database. '.'</div>';
					}
					$total_not_updated_count=count($not_updated_count);
					if($total_not_updated_count!=0){
						$message.='<div class="alert alert-info alert-styled-left alert-bordered">
										<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
										<span class="text-semibold">'.$total_not_updated_count.'</span>  record/s already exists in database.'.'</div>';
					}
					$total_no_count=count($no_count);
					if($total_no_count!=0){
						$message.='<div class="alert alert-danger alert-styled-left alert-bordered">
										<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
										<span class="text-semibold">'.$total_no_count.'</span>  record/s not insert into database.'.'</div>';
					}




                    $Return['message'] = $message;
                    if($total_updated_count==0 && $total_not_updated_count==0){
                    $Return['error'] = 'File not imported.Check your file that all fields are filled.';
                    }else if($total_updated_count!=0){
                    $Return['result'] = 'File imported successfully.';
                    }
                    }
				    fclose($csvFile);
					}
					else{ //Employee
					if($this->input->post('preview_type')=='preview'){
					$html='<div class="table-responsive panel-body">
					<small class="help-block text-success"><span style="background-color: #E8F5E9;border: 1px solid;padding: 2px 9px;margin-right: 9px;"></span> Success Cells (Only this rows will be upload.)</small>
					<small class="help-block text-danger"><span style="background-color: #FBE9E7;border: 1px solid;padding: 2px 9px;margin-right: 9px;"></span> Error Cells (Check the cells & rectify it.)</small>
					<small class="help-block text-warning"><span style="background-color: #FFF3E0;border: 1px solid;padding: 2px 9px;margin-right: 9px;"></span> Not Mandatory/Priority Fields</small>
					<table class="table table-md table-bordered"><tbody><tr class="bg-slate-600 text-center">
					<th>Name *</th>
					<th>Employee Id *<i style="cursor:pointer;" class="ml-10 icon-bubble-dots4 position-right text-danger" data-popup="popover-custom" data-placement="top" title="Employee ID (Mandatory Field)" data-trigger="hover" data-content="It should be numbers & length is 10 digits"></i></th>
					<th>NATIONALITY</th><th>GENDER</th><th>MARITAL STATUS</th><th>Date of Birth</th><th>Designation Per Contract *</th><th>Designation Per Offer *</th><th>Department *</th><th>Visa Under *</th><th>Area *</th><th>Working Hours *<i style="cursor:pointer;" class="ml-10 icon-bubble-dots4 position-right text-danger" data-popup="popover-custom" data-placement="top" title="Working Hours (Mandatory Field)" data-trigger="hover" data-content="It should be include with lunch hours and minimum hours should be 9."></i></th><th>Status</th><th>Hire Date *</th><th>Last Date</th><th>Visa Issue Date</th><th>Visa Expiry Date</th><th>Visa Number</th>
					<th>Contract Start Date</th><th>Contract End Date</th><th>Emirates ID</th><th>Labour Card No</th><th>Labour Expiry Date</th><th>Passport Number</th>
					<th>Passport Date</th><th>Passport Expiration</th><th>Basic Salary</th><th>Accomodaton</th><th>Transport Allowance</th><th>Food Allowance</th>
					<th>Additional Benefits</th><th>Other</th><th>Bonus</th><th>Agreed Bonus (For Drivers)</th><th>Salary Based On Contract</th><th>Salary With Bonus</th><th>Effective From Date</th><th>Biometric ID</th><th>Agency Fees</th><th>Agency Fees Compute With Hours</th><th>Email</th>
					</tr>';
					//parse data from csv file line by line
					while(($line = fgetcsv($csvFile)) !== FALSE){
	                   $employee_id=$line[0];
					   $name=change_fletter_caps($line[1]);
					   $nationality=$line[2];
					   $gender=$line[3];
					   $marital_status=$line[4];
					   if($line[5]!=''){
					   $date_of_birth=format_date('Y-m-d',$line[5]);
					   }else if($line[5]=='1970-01-01'){
					   $date_of_birth='';
					   }else{
					   $date_of_birth='';
					   }

					   if($date_of_birth=='1970-01-01'){
					   $date_of_birth='';
					   }

					   $designation_per_contract=$line[6];
					   $designation_per_offer=$line[7];
					   $department=$line[8];

					   $visa_under=$line[9];
					   $area=$line[10];
					   $working_hours=$line[11];

					   $status=$line[12];

					   if($line[13]!=''){
					   $hire_date=format_date('Y-m-d',$line[13]);
					   }else if($line[13]=='1970-01-01'){
					   $hire_date='';
					   }else{
					   $hire_date='';
					   }

					   if($hire_date=='1970-01-01'){
					   $hire_date='';
					   }

					   if($line[14]!=''){
					   $last_date=format_date('Y-m-d',$line[14]);
					   }else if($line[14]=='1970-01-01'){
					   $last_date='';
					   }else{
					   $last_date='';
					   }
					   if($last_date=='1970-01-01'){
					   $last_date='';
					   }
					   if($line[15]!=''){
					   $visa_issue_date=format_date('Y-m-d',$line[15]);
					   }else if($line[15]=='1970-01-01'){
					   $visa_issue_date='';
					   }else{
					   $visa_issue_date='';
					   }
					   if($visa_issue_date=='1970-01-01'){
					   $visa_issue_date='';
					   }
					   if($line[16]!=''){
					   $visa_expiry_date=format_date('Y-m-d',$line[16]);
					   }else if($line[16]=='1970-01-01'){
					   $visa_expiry_date='';
					   }else{
					   $visa_expiry_date='';
					   }
					   if($visa_expiry_date=='1970-01-01'){
					   $visa_expiry_date='';
					   }
					   $visa_no=$line[17];


					   if($line[18]!=''){
					   $contract_start_date=format_date('Y-m-d',$line[18]);
					   }else{
					   $contract_start_date='';
					   }
					   if($contract_start_date=='1970-01-01'){
					   $contract_start_date='';
					   }

					   if($line[19]!='' && strtolower($line[19])!=trim('unlimited')){
					   $contract_end_date=format_date('Y-m-d',$line[19]);
					   }else if(strtolower($line[19])==trim('unlimited')){
					   $contract_end_date='Unlimited';
					   }else{
					   $contract_end_date='';
					   }

					   if($contract_end_date=='1970-01-01'){
					   $contract_end_date='';
					   }


					   if($line[20]!='' && strtolower($line[20])!=trim('in process') && strtolower($line[20])!=trim('missing')){
					   $emirates_id=trim($line[20]);
					   }else{
					   $emirates_id='';
					   }

					    if($line[21]!=''  && strtolower($line[21])!=trim('in process') && strtolower($line[21])!=trim('missing')){
					   $labour_card_no=trim($line[21]);
					   }else{
					   $labour_card_no='';
					   }


					   if($line[22]!=''){
					   $labout_expiry_date=format_date('Y-m-d',$line[22]);
					   }else{
					   $labout_expiry_date='';
					   }
					   if($labout_expiry_date=='1970-01-01'){
					   $labout_expiry_date='';
					   }

					   if($line[23]!=''){
					   $passport_number=trim($line[23]);
					   }else{
					   $passport_number='';
					   }

					    if($line[24]!=''){
					   $passport_date=format_date('Y-m-d',$line[24]);
					   }else{
					   $passport_date='';
					   }
					   if($passport_date=='1970-01-01'){
					   $passport_date='';
					   }

					   if($line[25]!=''){
					   $passport_expiration_date=format_date('Y-m-d',$line[25]);
					   }else{
					   $passport_expiration_date='';
					   }
					   if($passport_expiration_date=='1970-01-01'){
					   $passport_expiration_date='';
					   }


					  $basic_salary=trim($line[26]);
					  $accomodation=trim($line[27]);
					  $transport_allowance=trim($line[28]);
					  $food_allowance=trim($line[29]);
					  $additional_benefits=trim($line[30]);
					  $other=trim($line[31]);
					  $bonus=trim($line[32]);
					  $agreed_bonus=trim($line[33]);
					  $salary_based_on_contract=trim($line[34]);
					  $salary_with_bonus=trim($line[35]);
					  if($line[36]!=''){
					   $effective_from_date=format_date('Y-m-d',$line[36]);
					   }else{
					   $effective_from_date='';
					   }
					   if($effective_from_date=='1970-01-01'){
					   $effective_from_date='';
					   }
					   $biometric_id=trim($line[37]);
					   $count_of_line=count($line);	//39
					   $user_check=$this->Employees_model->get_employee_unique_id($employee_id);
					   $agency_fees=trim($line[38]);
					   $agency_fees_computation=trim($line[39]);
					   $email=trim($line[40]);
					   if($count_of_line!=41){
						$Return['error'] = 'Download the correct employee information format. It should be 41 columns';
					   }

                       if($employee_id=='' || strlen($employee_id)!=10){$empid_class="class='danger'";}else{$empid_class="class='success'";}
                       if($name==''){$name_class="class='danger'";}else{$name_class="class='success'";}

					   /* Nationality */
					   if($nationality==''){$nation_result = '';
					   $span_nationality='';}else{

					   if(strtolower($nationality)=='indian' || strtolower($nationality)=='india'){$nation='country_name="india"';}else{$nation='country_name like "%'.substr($nationality,0,4).'%"';}
					   $nation_query=$this->db->query('select country_id,country_name from xin_countries where '.$nation.' limit 1');
					   $nation_result = $nation_query->result();
					   $span_nationality='<span class="text-danger">'.$nationality.' is not in db.</span>';
					   }
					   if($nation_result){
						  $nationality='<input type="hidden" name="country[]" value="'.$nation_result[0]->country_id.'"/><td class="success">'.$nation_result[0]->country_name.'</td>';
					   }else{
						  $nation_query_data=$this->db->query('select country_id,country_name from xin_countries');
						  $nation_data = $nation_query_data->result();
						  $country_data='';
						  if($nation_data){
							  foreach($nation_data as $n_data){
							 $country_data.='<option value="'.$n_data->country_id.'">'.$n_data->country_name.'</option>';
							  }
						  }
						  $nationality='<td class="warning">'.$span_nationality.'
						  <select class="form-control change_colors" name="country[]">
						  <option value="0">Select Country</option>
						  '.$country_data.'
						  </select>
						  </td>';
					   }
					   /* Nationality */


					   if(strtolower(substr($gender,0,1))=='m'){
						   $gender='Male';
					   }else if(strtolower(substr($gender,0,1))=='f'){
						   $gender='Female';
					   }else{$gender='Male';
					   }

					   if(strtolower(substr($marital_status,0,1))=='s'){
						   $marital_status='Single';
					   }else if(strtolower(substr($marital_status,0,1))=='m'){
						   $marital_status='Married';
					   }else if(strtolower(substr($marital_status,0,1))=='w'){
						   $marital_status='Widowed';
					   }else if(strtolower(substr($marital_status,0,1))=='d'){
						   $marital_status='Divorced or Separated';
					   }else{$marital_status='Single';
					   }

					  if($date_of_birth!=''){
						$dob_class="class='success'";
					  }else{
					     $dob_class="class='warning'";
					  }

			         if($hire_date!=''){
						$hire_date_class="class='success'";
					  }else{
					     $hire_date_class="class='danger'";
					  }

					  if($last_date!=''){
						$last_date_class="class='success'";
					  }else{
					     $last_date_class="class='warning'";
					  }

					  if($visa_issue_date!=''){
						$visa_issue_class="class='success'";
					  }else{
					    $visa_issue_class="class='warning'";
					  }

					   if($visa_expiry_date!=''){
						$visa_expiry_class="class='success'";
					  }else{
					    $visa_expiry_class="class='warning'";
					  }

					   if($visa_no!=''){
						$visa_no_class="class='success'";
					  }else{
					    $visa_no_class="class='warning'";
					  }

					   if($biometric_id!=''){
						$biometric_id_class="class='success'";
					  }else{
					    $biometric_id_class="class='warning'";
					  }



					  if(strtolower(substr($status,0,1))=='a'){
						   $status='Active';
						   $status1='1';
					   }else{$status='Inactive';
						   $status1='0';
					   }

					    if($working_hours >=9){
					   $working_hours='<td class="success"><input type="hidden" name="working_hours[]" value="'.$working_hours.'"/>'.$working_hours.'</td>';
					   }else{
					      $working_hours='<td class="danger">
						  <select class="form-control change_colors" name="working_hours[]" style="width: 64px;">
						  <option value="">Select Working Hours</option>
						   <option value="9">9</option>
						   <option value="10">10</option>
						   <option value="11">11</option>
						   <option value="12">12</option>
						   <option value="13">13</option>
						   <option value="14">14</option>
						  </select>
						  </td>';
					   }


						/* Department */
					   $dep_query=$this->db->query('select department_id,department_name from xin_departments where department_name="'.$department.'" limit 1');
					   $dep_result = $dep_query->result();
					   if($dep_result){
						  $department='<input type="hidden" name="department[]" value="'.$dep_result[0]->department_id.'"/><td class="success">'.$dep_result[0]->department_name.'</td>';
					   }else{
						  $deps_query_data=$this->db->query('select department_id,department_name from xin_departments');;
						  $deps_data = $deps_query_data->result();
						  $department_data='';
						  if($deps_data){
							  foreach($deps_data as $de_data){
							 $department_data.='<option value="'.$de_data->department_id.'">'.$de_data->department_name.'</option>';
							  }
						  }
						  $department='<td class="danger"><span class="text-danger">'.$department.' is not in db.</span>
						  <select class="form-control change_colors" name="department[]">
						  <option value="">Select Department</option>
						  '.$department_data.'
						  </select>
						  </td>';
					   }
					   /* Department */


					   /* Designation Per Contract */
					   $des_query=$this->db->query('select designation_id,designation_name from xin_designations where designation_name="'.$designation_per_contract.'" limit 1');
					   $des_result = $des_query->result();
					   if($des_result){
						  $designation_per_contract='<input type="hidden" name="designation[]" value="'.$des_result[0]->designation_id.'"/><td class="success">'.$des_result[0]->designation_name.'</td>';
					   }else{
						  $desg_query_data=$this->db->query('select designation_id,designation_name from xin_designations');;
						  $desg_data = $desg_query_data->result();
						  $designation_data='';
						  if($desg_data){
							  foreach($desg_data as $des_data){
							 $designation_data.='<option value="'.$des_data->designation_id.'">'.$des_data->designation_name.'</option>';
							  }
						  }
						  $designation_per_contract='<td class="danger"><span class="text-danger">'.$designation_per_contract.' is not in db.</span>
						  <select class="form-control change_colors" name="designation[]">
						  <option value="">Select Designation</option>
						  '.$designation_data.'
						  </select>
						  </td>';
					   }
					   /* Designation Per Contract */

					   /* Designation Per Offer */
					   $des_query1=$this->db->query('select designation_id,designation_name from xin_designations where designation_name="'.$designation_per_offer.'" limit 1');
					   $des_result1 = $des_query1->result();
					   if($des_result1){
						  $designation_per_offer='<input type="hidden" name="visa_occupation[]" value="'.$des_result1[0]->designation_id.'"/><td class="success">'.$des_result1[0]->designation_name.'</td>';
					   }else{
						  $desg_query_data1=$this->db->query('select designation_id,designation_name from xin_designations');;
						  $desg_data1 = $desg_query_data1->result();
						  $designation_data1='';
						  if($desg_data1){
							  foreach($desg_data1 as $des_data1){
							 $designation_data1.='<option value="'.$des_data1->designation_id.'">'.$des_data1->designation_name.'</option>';
							  }
						  }
						  $designation_per_offer='<td class="danger"><span class="text-danger">'.$designation_per_offer.' is not in db.</span>
						  <select class="form-control change_colors" name="visa_occupation[]">
						  <option value="">Select Designation</option>
						  '.$designation_data1.'
						  </select>
						  </td>';
					   }
					   /* Designation Per Offer */


					   /* office location */
					   $loc_query1=$this->db->query('select location_id,location_name from xin_office_location where location_name="'.$area.'" limit 1');
					   $loc_result1 = $loc_query1->result();
					   if($loc_result1){
						  $area='<input type="hidden" name="office_location[]" value="'.$loc_result1[0]->location_id.'"/><td class="success">'.$loc_result1[0]->location_name.'</td>';
					   }else{
						  $loc_query_data1=$this->db->query('select location_id,location_name from xin_office_location');;
						  $loc_data1 = $loc_query_data1->result();
						  $locas_data1='';
						  if($loc_data1){
							  foreach($loc_data1 as $loca_data1){
							 $locas_data1.='<option value="'.$loca_data1->location_id.'">'.$loca_data1->location_name.'</option>';
							  }
						  }
						  $area='<td class="danger"><span class="text-danger">'.$area.' is not in db.</span>
						  <select style="width: 77px;" class="form-control change_colors" name="office_location[]">
						  <option value="">Select Location</option>
						  '.$locas_data1.'
						  </select>
						  </td>';
					   }
					   /* office location */


					   /* office location */
					   $visa_query1=$this->db->query('select type_id,type_name from xin_module_types where type_name="'.trim($visa_under).'" AND type_of_module="visa_under" limit 1');
					   $visa_result1 = $visa_query1->result();
					   if($visa_result1){
						  $visa_under='<input type="hidden" name="visa_under[]" value="'.$visa_result1[0]->type_id.'"/><td class="success">'.$visa_result1[0]->type_name.'</td>';
					   }else{
						  $visa_query_data1=$this->db->query('select type_id,type_name from xin_module_types where type_of_module="visa_under"');;
						  $visa_data1 = $visa_query_data1->result();
						  $visas_data1='';
						  if($visa_data1){
							  foreach($visa_data1 as $vissa_data1){
							 $visas_data1.='<option value="'.$vissa_data1->type_id.'">'.$vissa_data1->type_name.'</option>';
							  }
						  }
						  $visa_under='<td class="danger"><span class="text-danger">'.$visa_under.' is not in db.</span>
						  <select style="width: 77px;" class="form-control change_colors" name="visa_under[]">
						  <option value="">Select Visa</option>
						  '.$visas_data1.'
						  </select>
						  </td>';
					   }
					   /* office location */

					  if($contract_start_date!=''){

						$contract_start_date='<input type="hidden" name="contract_start_date[]" value="'.$contract_start_date.'"/><td class="success">'.$this->Xin_model->set_date_format($contract_start_date).'</td>';
					  }else{

						$contract_start_date='<td class="warning"><input class="date" type="text" name="contract_start_date[]" value=""/></td>';
					  }
					  if($contract_end_date!=''){
					  $contract_end_date='<input type="hidden" name="contract_end_date[]" value="'.$contract_end_date.'"/><td class="success">'.$this->Xin_model->set_date_format($contract_end_date).'</td>';
					  }else{

						$contract_end_date='<td class="warning"><input class="date" type="text" name="contract_end_date[]" value=""/></td>';
					  }
					   if($emirates_id!=''){
						$emirates_id_class="class='success'";
					   }else{
					    $emirates_id_class="class='warning'";
					   }

					   if($labour_card_no!=''){
						$labour_card_class="class='success'";
					   }else{
					    $labour_card_class="class='warning'";
					   }

					   if($labout_expiry_date!=''){
					  $labout_expiry_date='<input type="hidden" name="labout_expiry_date[]" value="'.$labout_expiry_date.'"/><td class="success">'.$this->Xin_model->set_date_format($labout_expiry_date).'</td>';
					  }else{

						$labout_expiry_date='<td class="warning"><input class="date" type="text" name="labout_expiry_date[]" value=""/></td>';
					  }

					   if($passport_number!=''){
						$passport_number_class="class='success'";
					   }else{
					    $passport_number_class="class='warning'";
					   }


					    if($passport_date!=''){
					  $passport_date='<input type="hidden" name="passport_date[]" value="'.$passport_date.'"/><td class="success">'.$this->Xin_model->set_date_format($passport_date).'</td>';
					  }else{

						$passport_date='<td class="warning"><input class="date" type="text" name="passport_date[]" value=""/></td>';
					  }
					   if($passport_expiration_date!=''){
					  $passport_expiration_date='<input type="hidden" name="passport_expiration_date[]" value="'.$passport_expiration_date.'"/><td class="success">'.$this->Xin_model->set_date_format($passport_expiration_date).'</td>';
					  }else{

						$passport_expiration_date='<td class="warning"><input class="date" type="text" name="passport_expiration_date[]" value=""/></td>';
					  }


					   if($basic_salary!='' && is_numeric($basic_salary)){
						$basic_salary_class="class='success'";
					   }else{
					    $basic_salary_class="class='warning'";
					   }
					   if($accomodation!='' && is_numeric($accomodation)){
						$accomodation_class="class='success'";
					   }else{
					    $accomodation_class="class='warning'";
					   }
					   if($transport_allowance!='' && is_numeric($transport_allowance)){
						$transport_allowance_class="class='success'";
					   }else{
					    $transport_allowance_class="class='warning'";
					   }
					   if($food_allowance!='' && is_numeric($food_allowance)){
						$food_allowance_class="class='success'";
					   }else{
					    $food_allowance_class="class='warning'";
					   }

					    if($additional_benefits!='' && is_numeric($additional_benefits)){
						$additional_benefits_class="class='success'";
					   }else{
					    $additional_benefits_class="class='warning'";
					   }

					    if($other!='' && is_numeric($other)){
						$other_class="class='success'";
					   }else{
					    $other_class="class='warning'";
					   }
					   if($bonus!='' && is_numeric($bonus)){
						$bonus_class="class='success'";
					   }else{
					    $bonus_class="class='warning'";
					   }

					    if($agreed_bonus!='' && is_numeric($agreed_bonus)){
						$agreed_bonus_class="class='success'";
					   }else{
					    $agreed_bonus_class="class='warning'";
					   }

					   if($salary_based_on_contract!='' && is_numeric($salary_based_on_contract)){
						$salary_based_on_contract_class="class='success'";
					   }else{
					    $salary_based_on_contract_class="class='warning'";
					   }

					    if($salary_with_bonus!='' && is_numeric($salary_with_bonus)){
						$salary_with_bonus_class="class='success'";
					   }else{
					    $salary_with_bonus_class="class='warning'";
					   }


					   if($effective_from_date!=''){
					  $effective_from_date='<input type="hidden" name="effective_from_date[]" value="'.$effective_from_date.'"/><td class="success">'.$this->Xin_model->set_date_format($effective_from_date).'</td>';
					  }else{

						$effective_from_date='<td class="warning"><input class="date" type="text" name="effective_from_date[]" value=""/></td>';
					  }


					   if($agency_fees!='' && is_numeric($agency_fees)){
						$agency_fees_class="class='success'";
					   }else{
					    $agency_fees_class="class='warning'";
					   }
					    if($agency_fees_computation==1){
						  $agency_fees_c_class='<input type="hidden" name="agency_fees_computation[]" value="1"/><td class="success">Yes</td>';
					   }else{
						   $agency_fees_c_class='<input type="hidden" name="agency_fees_computation[]" value="0"/><td class="warning">No</td>';
					   }


       				   $html.='<tr>
					   <input type="hidden" name="emp_name[]" value="'.$name.'"/><td '.$name_class.'>'.$name.'</td>
					  <input type="hidden" name="emp_id[]" value="'.$employee_id.'"/> <td '.$empid_class.'>'.$employee_id.'</td>
					   '.$nationality.'
					   <td class="success"><input type="hidden" name="gender[]" value="'.$gender.'"/>'.$gender.'</td>
					   <td class="success"><input type="hidden" name="marital_status[]" value="'.$marital_status.'"/>'.$marital_status.'</td>
					   <td '.$dob_class.'><input type="hidden" name="date_of_birth[]" value="'.$date_of_birth.'"/>'.$this->Xin_model->set_date_format($date_of_birth).'</td>
					   '.$designation_per_contract.'
					   '.$designation_per_offer.'
					   '.$department.'
					   '.$visa_under.'
					   '.$area.'
					   '.$working_hours.'
					   <td class="success"><input type="hidden" name="status[]" value="'.$status1.'"/>'.$status.'</td>
					   <td '.$hire_date_class.'><input type="hidden" name="date_of_joining[]" value="'.$hire_date.'"/>'.$this->Xin_model->set_date_format($hire_date).'</td>
					   <td '.$last_date_class.'><input type="hidden" name="date_of_leaving[]" value="'.$last_date.'"/>'.$this->Xin_model->set_date_format($last_date).'</td>
					   <td '.$visa_issue_class.'><input type="hidden" name="visa_issue_date[]" value="'.$visa_issue_date.'"/>'.$this->Xin_model->set_date_format($visa_issue_date).'</td>
					   <td '.$visa_expiry_class.'><input type="hidden" name="visa_expiry_date[]" value="'.$visa_expiry_date.'"/>'.$this->Xin_model->set_date_format($visa_expiry_date).'</td>
					   <td '.$visa_no_class.'><input type="hidden" name="visa_number[]" value="'.$visa_no.'"/>'.$visa_no.'</td>
					   '.$contract_start_date.'
					   '.$contract_end_date.'
					   <td '.$emirates_id_class.'><input type="hidden" name="emirates_id[]" value="'.$emirates_id.'"/>'.$emirates_id.'</td>
					   <td '.$labour_card_class.'><input type="hidden" name="labour_card_no[]" value="'.$labour_card_no.'"/>'.$labour_card_no.'</td>
					   '.$labout_expiry_date.'
					   <td '.$passport_number_class.'><input type="hidden" name="passport_number[]" value="'.$passport_number.'"/>'.$passport_number.'</td>
					   '.$passport_date.'
					   '.$passport_expiration_date.'
					   <td '.$basic_salary_class.'><input type="hidden" name="basic_salary[]" value="'.$basic_salary.'"/>'.$basic_salary.'</td>
					   
					   <td '.$accomodation_class.'><input type="hidden" name="accomodation[]" value="'.$accomodation.'"/>'.$accomodation.'</td>
					   <td '.$transport_allowance_class.'><input type="hidden" name="transport_allowance[]" value="'.$transport_allowance.'"/>'.$transport_allowance.'</td>
					   <td '.$food_allowance_class.'><input type="hidden" name="food_allowance[]" value="'.$food_allowance.'"/>'.$food_allowance.'</td>
					   <td '.$additional_benefits_class.'><input type="hidden" name="additional_benefits[]" value="'.$additional_benefits.'"/>'.$additional_benefits.'</td>
					   <td '.$other_class.'><input type="hidden" name="other[]" value="'.$other.'"/>'.$other.'</td>
					   <td '.$bonus_class.'><input type="hidden" name="bonus[]" value="'.$bonus.'"/>'.$bonus.'</td>
					   <td '.$agreed_bonus_class.'><input type="hidden" name="agreed_bonus[]" value="'.$agreed_bonus.'"/>'.$agreed_bonus.'</td>
					   <td '.$salary_based_on_contract_class.'><input type="hidden" name="salary_based_on_contract[]" value="'.$salary_based_on_contract.'"/>'.$salary_based_on_contract.'</td>
					    <td '.$salary_with_bonus_class.'><input type="hidden" name="salary_with_bonus[]" value="'.$salary_with_bonus.'"/>'.$salary_with_bonus.'</td>
					   '.$effective_from_date.'
						<td '.$biometric_id_class.'><input type="hidden" name="biometric_id[]" value="'.$biometric_id.'"/>'.$biometric_id.'</td>
						<td '.$agency_fees_class.'><input type="hidden" name="agency_fees[]" value="'.$agency_fees.'"/>'.$agency_fees.'</td>
						'.$agency_fees_c_class.'
						<td><input type="hidden" name="email[]" value="'.$email.'"/>'.$email.'</td>					 
					   </tr>';

				}
				$html.='</tbody></table><button type="submit" onclick="change_slug();"  class="btn bg-teal-400 save1 save mt-10 pull-right">Upload</button></div>';
				//close opened csv file
				$Return['message'] = $html;
				$Return['result'] = '';
				}
				else{


					//echo "<pre>";print_r($this->input->post());die;
					$updated_count=[];
					$not_updated_count=[];
					$no_count=[];

					$name=$this->input->post('emp_name');
					$employee_id=$this->input->post('emp_id');
					$nationality=$this->input->post('country');
					$gender=$this->input->post('gender');
					$marital_status=$this->input->post('marital_status');
					$date_of_birth=$this->input->post('date_of_birth');
					$designation=$this->input->post('visa_occupation');
					$visa_occupation=$this->input->post('designation');
					$department=$this->input->post('department');
					$visa_under=$this->input->post('visa_under');
					$office_location=$this->input->post('office_location');
					$working_hours=$this->input->post('working_hours');
					$status=$this->input->post('status');
					$date_of_joining=$this->input->post('date_of_joining');
					$date_of_leaving=$this->input->post('date_of_leaving');
					$visa_issue_date=$this->input->post('visa_issue_date');
					$visa_expiry_date=$this->input->post('visa_expiry_date');
					$visa_number=$this->input->post('visa_number');

					$contract_start_date=$this->input->post('contract_start_date');
					$contract_end_date=$this->input->post('contract_end_date');



					$emirates_id=$this->input->post('emirates_id');
					$passport_number=$this->input->post('passport_number');
					$passport_date=$this->input->post('passport_date');
					$passport_expiration_date=$this->input->post('passport_expiration_date');
					$labour_card_no=$this->input->post('labour_card_no');
					$labout_expiry_date=$this->input->post('labout_expiry_date');

					$basic_salary=$this->input->post('basic_salary');
					$accomodation=$this->input->post('accomodation');
					$transport_allowance=$this->input->post('transport_allowance');
					$food_allowance=$this->input->post('food_allowance');
					$additional_benefits=$this->input->post('additional_benefits');
					$other=$this->input->post('other');
					$bonus=$this->input->post('bonus');
					$agreed_bonus=$this->input->post('agreed_bonus');
					$email=$this->input->post('email');
					$salary_based_on_contract=$this->input->post('salary_based_on_contract');
					$salary_with_bonus=$this->input->post('salary_with_bonus');
					$effective_from_date=$this->input->post('effective_from_date');
					$agency_fees=$this->input->post('agency_fees');
					$biometric_id=$this->input->post('biometric_id');
					$agency_fees_computation=$this->input->post('agency_fees_computation');

					$emp_count=count($name);

					   foreach($employee_id as $emp_check){
						   $str_len=strlen($emp_check);
						   if($str_len!=10){
							$Return['error']= 'Employee id shoule be 10 digits';
						   }
					   }

					   if(count(array_filter($name)) != $emp_count) {
							$Return['error'] = 'Employee name field is required';
					   }else if(count(array_filter($employee_id)) != $emp_count) {
							$Return['error'] = 'Employee id field is required';
					   }else if(count(array_filter($designation)) != $emp_count) {
							$Return['error'] = 'Designation per contract field is required';
					   }else if(count(array_filter($visa_occupation)) != $emp_count) {
							$Return['error'] = 'Designation per offer field is required';
					   }else if(count(array_filter($department)) != $emp_count) {
							$Return['error'] = 'Department field is required';
					   }else if(count(array_filter($visa_under)) != $emp_count) {
							$Return['error'] = 'Visa Under field is required';
					   }else if(count(array_filter($office_location)) != $emp_count) {
							$Return['error'] = 'Area field is required';
					   }else if(count(array_filter($working_hours)) != $emp_count) {
							$Return['error'] = 'No.of working hours field is required';
					   }else if(count(array_filter($date_of_joining)) != $emp_count) {
							$Return['error'] = 'Hire date field is required';
					   }else if(count(array_filter($effective_from_date)) != $emp_count) {
							$Return['error'] = 'Salary effective from date field is required';
					   }else if(count(array_filter($basic_salary)) != $emp_count) {
							$Return['error'] = 'basic salary field is required and it should be numeric';
					   }



					   foreach ($working_hours as $value) {
							if($value < 9){
							$Return['error'] = 'Working hours should be minimum 9 hours.';
							}
						}

					   if($Return['error']!=''){
							$this->output($Return);
					   }
					   $agency_f_id=$this->payroll_model->read_salary_type_name('Agency Fees');


					  for($i=0;$i<$emp_count;$i++){

						$full_name=explode(' ',$name[$i]);
					 	$user_check=$this->Employees_model->get_employee_unique_id($employee_id[$i]);

						if(@$full_name[0]){$first_name=$full_name[0];}else{$first_name='';}
						if(@$full_name[1]){$middle_name=$full_name[1];}else{$middle_name='';}
						if(@$full_name[2]){$last_name=$full_name[2];}else{$last_name='';}

						if(trim(strtolower($contract_end_date[$i]))=='unlimited'){
						$contract_end_date[$i]='Unlimited';
						$contract_type_id=18;
						}else{
						$contract_end_date[$i]=format_date('Y-m-d',$contract_end_date[$i]);
						$contract_type_id=16;
						}



								   $data_contract=array(
										'from_date'=>format_date('Y-m-d',$contract_start_date[$i]),
										'contract_type_id' => $contract_type_id,
										'to_date'=>$contract_end_date[$i],
										'designation_id'=>0,
										'created_at' => date('d-m-Y'),
									);

									 $data_visa=array(
										'issue_date'=>format_date('Y-m-d',$visa_issue_date[$i]),
										'expiry_date'=>format_date('Y-m-d',$visa_expiry_date[$i]),
										'document_type_id'=>3,
										'document_number' => $visa_number[$i],
										'type'=>$visa_under[$i],
										'cost'=>0,
										'visa_renewal_cost'=>0,
										'visa_cancellation_cost'=>0,
										'created_at' => date('d-m-Y h:i:s'),
									);

									 $data_emirates=array(
										'expiry_date'=>format_date('Y-m-d',$visa_expiry_date[$i]),
										'document_type_id'=>4,
										'document_number' => $emirates_id[$i],
										'type'=>0,
										'cost'=>0,
										'visa_renewal_cost'=>0,
										'visa_cancellation_cost'=>0,
										'created_at' => date('d-m-Y h:i:s'),
									);

									 $data_labour=array(
										'expiry_date'=>format_date('Y-m-d',$labout_expiry_date[$i]),
										'document_type_id'=>5,
										'document_number' => $labour_card_no[$i],
										'type'=>0,
										'cost'=>0,
										'visa_renewal_cost'=>0,
										'visa_cancellation_cost'=>0,
										'created_at' => date('d-m-Y h:i:s'),
									);

									 $data_pass=array(
										'issue_date'=>format_date('Y-m-d',$passport_date[$i]),
										'expiry_date'=>format_date('Y-m-d',$passport_expiration_date[$i]),
										'document_type_id'=>2,
										'document_number' => $passport_number[$i],
										'type'=>0,
										'cost'=>0,
										'visa_renewal_cost'=>0,
										'visa_cancellation_cost'=>0,
										'created_at' => date('d-m-Y h:i:s'),
									);

									$data_agency = array(
									'adjustment_type' => $agency_f_id[0]->type_parent,
									'adjustment_name' => $agency_f_id[0]->type_id,
									'adjustment_amount' => $agency_fees[$i],
									'adjustment_perpared_by' => $this->userSession['user_id'],
									'salary_type' => 'external_adjustments',
									'end_date' => '',
									'comments' => 'Agency Fees',
									'status' => '1',
									'compute_amount' => $agency_fees_computation[$i],
									'created_by' => $this->userSession['user_id'],
									'created_at' => date('Y-m-d H:i:s'),
									);

							$data_salary=array(
								'salary_grades'=> $first_name.$middle_name.$last_name,
								'basic_salary'=>str_replace(',','',$basic_salary[$i]),
								'house_rent_allowance'=>str_replace(',','',$accomodation[$i]),
								'travelling_allowance'=>str_replace(',','',$transport_allowance[$i]),
								'food_allowance'=>str_replace(',','',$food_allowance[$i]),
								'additional_benefits'=>str_replace(',','',$additional_benefits[$i]),
								'other_allowance'=>str_replace(',','',$other[$i]),
								'bonus'=>str_replace(',','',$bonus[$i]),
								'agreed_bonus'=>str_replace(',','',$agreed_bonus[$i]),
								'created_at' => date('d-m-Y h:i:s'),
								'added_by' => $this->userSession['user_id'],
								'gross_salary'=>str_replace(',','',$salary_with_bonus[$i]),
								'net_salary'=>str_replace(',','',$salary_with_bonus[$i]),
								'salary_based_on_contract'=>str_replace(',','',$salary_based_on_contract[$i]),
								'effective_from_date'=>format_date('Y-m-d',$effective_from_date[$i]),
								'is_approved'=>1
							);





					    if($user_check){//update

						   $user_id=$user_check[0]->user_id;

							$data = array(
							'first_name' => $first_name,
							'middle_name' => $middle_name,
							'last_name' => $last_name,
							'nationality' => $nationality[$i],
							'email' => $email[$i],
							'gender' => $gender[$i],
							'marital_status' => $marital_status[$i],
							'date_of_birth' => format_date('Y-m-d',$date_of_birth[$i]),
							'designation_id' => $designation[$i],
							'visa_occupation' => $visa_occupation[$i],
							'department_id' => $department[$i],
							'office_location_id' => $office_location[$i],
							'working_hours' => decimalHours_reversewith_symbol($working_hours[$i]),
							'is_active' => $status[$i],
							'date_of_joining' => format_date('Y-m-d',$date_of_joining[$i]),
							'date_of_leaving' => format_date('Y-m-d',$date_of_leaving[$i]),'biometric_id'=>$biometric_id[$i],
							);
                            $this->Employees_model->update_record($data, $user_id);


							if($contract_start_date[$i]!='' && $contract_end_date[$i]!=''){
							$this->add_contracts($data_contract,$user_id);
							}
							if($visa_issue_date[$i]!='' && $visa_expiry_date[$i]!=''){
							$this->insert_visa_type($data_visa,$user_id,3);//visa
							}
							if($visa_expiry_date[$i]!='' && $emirates_id[$i]!=''){
							$this->insert_visa_type($data_emirates,$user_id,4);//emirates
							}
							if($labout_expiry_date[$i]!='' && $labour_card_no[$i]!=''){
							$this->insert_visa_type($data_labour,$user_id,5);//labour
							}
							if($passport_date[$i]!='' && $passport_expiration_date[$i]!=''){
							$this->insert_visa_type($data_pass,$user_id,2);//Passport
							}
							if($basic_salary[$i]!=''){
							//$this->add_salary_template($data_salary,$user_id);
							}

							if($agency_fees[$i]!=''){
							$this->add_agency_fees($data_agency,$user_id);
							}

							//Immigration


				            $updated_count[]=$i;
					    }
						else {	//insert

						$data = array(
							'employee_id' => $employee_id[$i],
							'first_name' => $first_name,
							'middle_name' => $middle_name,
							'last_name' => $last_name,
							'nationality' => $nationality[$i],
							'email' => $email[$i],
							'gender' => $gender[$i],
							'marital_status' => $marital_status[$i],
							'date_of_birth' => format_date('Y-m-d',$date_of_birth[$i]),
							'designation_id' => $designation[$i],
							'visa_occupation' => $visa_occupation[$i],
							'department_id' => $department[$i],
							'office_location_id' => $office_location[$i],
							'working_hours' => decimalHours_reversewith_symbol($working_hours[$i]),
							'is_active' => $status[$i],
							'date_of_joining' => format_date('Y-m-d',$date_of_joining[$i]),
							'date_of_leaving' => format_date('Y-m-d',$date_of_leaving[$i]),
							'created_at' => date('d-m-Y'),
							'company_id' => 4,
							'currency_id' => 180,
							'user_role_id' => 9,
							'is_logged_in' => 0,
							'online' => 0,
							'password' => md5(123456),
							'biometric_id'=>$biometric_id[$i],
							);


							$this->Employees_model->add($data);
							$affected_id=table_max_id('xin_employees','user_id');


							if($contract_start_date[$i]!='' && $contract_end_date!=''){
							$this->add_contracts($data_contract,$affected_id['field_id']);
							}
							if($visa_issue_date[$i]!='' && $visa_expiry_date[$i]!=''){
							$this->insert_visa_type($data_visa,$affected_id['field_id'],3);//visa
							}
							if($visa_expiry_date[$i]!='' && $emirates_id[$i]!=''){
							$this->insert_visa_type($data_emirates,$affected_id['field_id'],4);//emirates
							}
							if($labout_expiry_date[$i]!='' && $labour_card_no[$i]!=''){
							$this->insert_visa_type($data_labour,$affected_id['field_id'],5);//labour
							}
							if($passport_date[$i]!='' && $passport_expiration_date[$i]!=''){
							$this->insert_visa_type($data_pass,$affected_id['field_id'],2);//Passport
							}

							if($basic_salary[$i]!=''){
							//$this->add_salary_template($data_salary,$affected_id['field_id']);
							}

							if($agency_fees[$i]!=''){
							$this->add_agency_fees($data_agency,$affected_id['field_id']);
							}



							$not_updated_count[]=$i;


						}



				    }


					$message='';
					$total_updated_count=count($updated_count);
					if($total_updated_count!=0){

										$message.='<div class="alert alert-info alert-styled-left alert-bordered">
										<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
										<span class="text-semibold">'.$total_updated_count.'</span>  record/s updated into database.'.'</div>';
					}
					$total_not_updated_count=count($not_updated_count);
					if($total_not_updated_count!=0){
						$message.='<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered">
										<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button><span class="text-semibold">'.$total_not_updated_count.'</span> new record/s uploaded into database. '.'</div>';
					}





				$Return['message'] = $message;
				if($total_updated_count==0 && $total_not_updated_count==0){
				$Return['error'] = 'File not imported.Check your file that all fields are filled.';
				}else if($total_updated_count!=0 || $total_not_updated_count!=0){
				$Return['result'] = 'File imported successfully.';
				}
				}
				    fclose($csvFile);
			}

			}else{
				 $Return['error'] = 'File not imported.';
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

	public function add_contracts($data_contract,$employee_id){
		   $contract_isthere=$this->Employees_model->check_contract_isthere($data_contract['contract_type_id'],$employee_id);
	       if($contract_isthere==0){
                $data_contract1=$data_contract+array('employee_id'=>$employee_id);
                $this->Employees_model->contract_info_add($data_contract1);
		   }else{
			    $this->db->where('employee_id',$employee_id);
		        $this->db->update('xin_employee_contract',$data_contract);
		   }
	}

	public function add_agency_fees($data_agency,$employee_id){
		   $agencyfees_isthere=$this->Employees_model->check_agency_fees_isthere($data_agency,$employee_id);
		   if($agencyfees_isthere==0){
                $data_agency1=$data_agency+array('adjustment_for_employee'=>$employee_id);
                $this->payroll_model->add_adjustments($data_agency1);
		   }else{
			    $this->db->where('adjustment_for_employee',$employee_id);
		        $this->db->update('xin_salary_adjustments',$data_agency);
		   }
	}
	
	public function add_salary_template($data_salary,$employee_id){
		   $salary_isthere=$this->Employees_model->check_salary_template_isthere($data_salary,$employee_id);
		   if($salary_isthere==0){
                $data_salary1=$data_salary+array('employee_id'=>$employee_id);
                $this->payroll_model->add_template($data_salary1);
		   }else{
			    $this->db->where('employee_id',$employee_id);
		        $this->db->update('xin_salary_templates',$data_salary);
		   }
	}

	public function insert_visa_type($visa_under,$emp_id,$document_type_id){
        $this->db->select('immigration_id');
        $this->db->from('xin_employee_immigration');
        $this->db->limit(1);
        $this->db->where('document_type_id',$document_type_id);
        $this->db->where('employee_id',$emp_id);
        $query = $this->db->get();
        $result=$query->row();
        if($result){
            $this->db->where('document_type_id',$document_type_id);
            $this->db->where('employee_id',$emp_id);
            $this->db->update('xin_employee_immigration',$visa_under);
        }else{
            $visa_under1=$visa_under+array('employee_id'=>$emp_id);
            $this->db->insert('xin_employee_immigration', $visa_under1);
        }
	}

	public function country_grouped()
    {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("settings/constants", $data);
		} else {
			redirect('');
		}
		$draw = intval($this->input->get("draw"));
		$constant = $this->Xin_model->get_country_grouped();
		$data = array();
        foreach($constant->result() as $r) {
              $edit_perm='';
              $delete_perm='';
              if(in_array('54e',role_resource_ids())) {
                    $edit_perm='<li><a href="#" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'.$r->country_id. '" data-field_name="'.$r->country_name. '" data-field_type="salary_field_structure"><i class="icon-pencil7"></i> Edit</a></li>';
              }
              $data[] = array(
                    $r->country_name,
                    '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$delete_perm.'</ul></li></ul>'
              );
        }
	    $output = array(
		        "draw" => $draw,
			    "recordsTotal" => $constant->num_rows(),
			    "recordsFiltered" => $constant->num_rows(),
			    "data" => $data
		);
        $this->output($output);
	}

	public function dynamic_fields(){
        $rand=rand();
		if($this->input->post('data')=='dynamic_fields'){
			echo '<div class="col-lg-12" id="remove_field_'.$rand.'">
                   <div class="col-lg-4">
            <input name="salary_field_id[]" value="" class="form-control" type="hidden">
                            <div class="form-group">
                                <input value="" class="form-control  field_name_'.$rand.'" onkeyup="string_change(this.value,'.$rand.');" type="text" maxlength="100" title="Alphabets, spaces only allowed" required="required" pattern="^[A-Za-z ]+$">
                            </div>
                        </div>
            
                        <div class="col-lg-3">
                            <div class="form-group">
                             <input name="salary_field_name[]" value="" class="form-control field_n_'.$rand.'" type="text" readonly>
                            </div>
                        </div>
            <div class="col-lg-3">
                            <div class="form-group text-center">
            <select name="salary_calculate[]" data-plugin="select_hrm">
            <option value="0">No</option>
            <option value="1">Yes</option>
            </select>
                            </div>
                        </div>
            <div class="col-lg-1">
                            <div class="form-group">
                             <input name="salary_field_order[]" value="0" class="form-control" type="text"> 
                            </div></div>
            <div class="col-lg-1 mt-10" style="cursor:pointer;" onclick="remove_field('.$rand.');">X</div>
                    </div>	
                    </div>';
		}
	}

    public function update_salary_field(){
		if($this->input->post('data')=='ed_salary_field') {
            $Return = array('result'=>'', 'error'=>'');
            $country_id =$this->input->post('country_id');
            $salary_field_count=count($this->input->post('salary_field_name'));
            $salary_field_id=$this->input->post('salary_field_id');
            $salary_field_name=$this->input->post('salary_field_name');
            $salary_calculate=$this->input->post('salary_calculate');
            $salary_field_order=$this->input->post('salary_field_order');
            $salary_field_order_count=count(array_filter($this->input->post('salary_field_order')));
            if(empty($this->input->post('salary_field_name'))) {
                $Return['error'] = "The salary name field is required.";
            }else if($salary_field_count!=$salary_field_order_count){
                $Return['error'] = "The sort order field is required and should not be zero.";
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            for($i=0;$i<$salary_field_count;$i++){
                    $s_field_id=@$salary_field_id[$i];
                    $s_field_name=$salary_field_name[$i];
                    $s_calculate=@$salary_calculate[$i];
                    $s_field_order=$salary_field_order[$i];
                    $insert_data = array(
                    'salary_field_name' => $s_field_name,
                    'salary_calculate' => $s_calculate,
                    'salary_field_order' => $s_field_order,
                    'updated_by' => $this->userSession['user_id'],
                    'country_id' => $country_id,
                    );
                    if(@$s_field_id!=''){
                    $result = $this->Xin_model->update_salary_fields($insert_data,$s_field_id);
                    }else{
                    $result = $this->Xin_model->insert_salary_fields($insert_data);
                    }
            }
            /*User Logs*/
            $affected_id= table_max_id('xin_salary_fields_bycountry','salary_field_id');
            userlogs('Constants-Salary Field-Update','Salary Field Updated',$affected_id['field_id'],$affected_id['datas']);
            /*User Logs*/
            if ($result == true) {
                $Return['result'] = 'Salary Field updated.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
    }
}
