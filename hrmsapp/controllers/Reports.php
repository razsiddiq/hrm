<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author Siddiqkhan
 *
 * @Reports Controller
 */
class Reports extends MY_Controller {

	protected $userSession = null;

	public function __construct() {
		Parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->helper('cookie');
		$this->load->database();
		$this->load->library('email');
		$this->load->library('form_validation');
		//load the models
		$this->load->model("Employees_model");
		$this->load->model("Xin_model");
		$this->load->model("Department_model");
		$this->load->model("Designation_model");
		$this->load->model("Roles_model");
		$this->load->model("Location_model");
		$this->load->model("Reports_model");
		$this->userSession = $this->session->userdata('username');


	}

	public function output($Return=array()){
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}

	public function index(){
		$cookie_check=json_decode(get_cookie('employee_reports_cookie',true));
		if($cookie_check->country_details==''){
			$e_r_cookie= array(
				'name'   => 'employee_reports_cookie',
				'value'  => json_encode(array('country_details'=>224)),
				'expire' => '3600',
			);
			set_cookie($e_r_cookie);
		}
		redirect('reports/employee_reports/');
	}

	public function uploaded_documents(){
		$data['title'] = $this->Xin_model->site_title();
		$data['all_departments'] = $this->Department_model->all_departments();
		$data['all_designations'] = $this->Designation_model->all_designations();
		$data['all_user_roles'] = $this->Roles_model->all_user_roles();
		$data['all_office_shifts'] = '';
		$data['all_office_locations']= $this->Location_model->all_office_locations();
		$data['breadcrumbs'] = 'Uploaded Documents';
		$data['path_url'] = 'uploaded_documents';
		$data['all_countries'] = $this->Xin_model->get_countries();
		$data['phone_numbers_code'] =phone_numbers_code();
		$data['all_companies']=$this->Employees_model->all_companies();
		if(in_array('60',role_resource_ids()) || in_array('60b',role_resource_ids())) {

			if(!empty($this->userSession)){
				$data['subview'] = $this->load->view("reports/uploaded_documents", $data, TRUE);
				$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
	}

	public function employee_reports(){

		$data['title'] = $this->Xin_model->site_title();
		$data['breadcrumbs'] = 'Employee Reports';
		$data['path_url'] = 'uploaded_documents';
		if(in_array('60',role_resource_ids()) || in_array('60a',role_resource_ids())) {

			if(!empty($this->userSession)){
				$data['subview'] = $this->load->view("reports/employee_reports", $data, TRUE);
				$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
	}

	public function cookie_change(){
		$e_r_cookie= array(
			'name'   => 'employee_reports_cookie',
			'value'  => json_encode($this->input->post()),
			'expire' => '3600',
		);
		set_cookie($e_r_cookie);
		redirect('reports/employee_reports/');
	}

	public function uploaded_document_list(){
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("reports/uploaded_documents", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$uploaded_document_list = $this->Reports_model->uploaded_document_list();
		$allDrivers = $this->Reports_model->getDriverEmployees();
		$data = array();
		foreach($uploaded_document_list as $r) {
			$visa_name=$r->type_name;
			$visa_type_id=$r->type_id;
			$total_employees=$r->total_employees;
			$photo_copy=$r->photo_copy;
			$contract=$r->contract;
			$visa_page=$r->visa_page;
			$emps_id=$r->emps_id;
			$employeesCollection = ($r->emps_id)? explode(",",$r->emps_id) : [];
			$photoCopyEmpsCollection = ($r->photo_copy_emps)? explode(",",$r->photo_copy_emps) : [];
			$contractEmpsCollection = ($r->contract_emps)? explode(",",$r->contract_emps):[];
			$driversForCurrentVisaType = array_intersect($allDrivers,$employeesCollection);

			$drivingLicenseResults = $this->Reports_model->get_document_count(implode(",",$driversForCurrentVisaType),1);
			$visaPageResults = $this->Reports_model->get_document_count($emps_id,3);
			$passportResults = $this->Reports_model->get_document_count($emps_id,2);
			$emiratesIdResults = $this->Reports_model->get_document_count($emps_id,4);
			$labourCardResults = $this->Reports_model->get_document_count($emps_id,5);
			$offerLetterResults = $this->Reports_model->get_document_count($emps_id,7);

			$driverNotAvailable = [];
			$passportNotAvailable = [];
			$emiratesIdNotAvailable = [];
			$labourCardNotAvailable = [];
			$offerLetterNotAvailable = [];
			$visaPageNotAvailable = [];

			foreach ($driversForCurrentVisaType as $id){
				if($drivingLicenseResults['result'] && !in_array($id,explode(",",$drivingLicenseResults['result'])))
					$driverNotAvailable[] = $id;
			}

			foreach ($employeesCollection as $id){
				if($passportResults['result'] && !in_array($id,array_column($passportResults['result'],'employee_id')))
					$passportNotAvailable[] = $id;
				if($emiratesIdResults['result'] && !in_array($id,array_column($emiratesIdResults['result'],'employee_id')))
					$emiratesIdNotAvailable[] = $id;
				if($labourCardResults['result'] && !in_array($id,array_column($labourCardResults['result'],'employee_id')))
					$labourCardNotAvailable[] = $id;
				if($offerLetterResults['result'] && !in_array($id,array_column($offerLetterResults['result'],'employee_id')))
					$offerLetterNotAvailable[] = $id;
				if($visaPageResults['result'] && !in_array($id,array_column($visaPageResults['result'],'employee_id')))
					$visaPageNotAvailable[] = $id;
			}
			$allEmpIdsCollection = array_unique(array_merge($photoCopyEmpsCollection,$driverNotAvailable,$passportNotAvailable,$emiratesIdNotAvailable,$labourCardNotAvailable,$offerLetterNotAvailable,$visaPageNotAvailable));
			$allEmpIdsCollection = $this->Employees_model->get_employees_by_id(($allEmpIdsCollection)? $allEmpIdsCollection : [0]);

			$noDocsAvailableEmployees = [];
			foreach ($allEmpIdsCollection as $item){
				$noDocsAvailableEmployees[$item['user_id']] = $item;
			}
			$driverNotAvailable = array_unique($driverNotAvailable);
			if(sizeof($driverNotAvailable) > 0){
				$driverPopOver = '<tr>
								<td><strong>Emp Id</strong></td>
								<td><strong>Name</strong></td>
								<td><strong>Email</strong></td>
							</tr>';
				$sizeOf = 0;
				foreach ($driverNotAvailable as $id){
					$emp = $noDocsAvailableEmployees[$id];
					if($emp){
						$sizeOf++;
							$driverPopOver .= '<tr>
								<td>'.$emp["employee_id"].'</td>
								<td>'.$emp["first_name"].'</td>
								<td>'.$emp["email"].'</td>
								</tr>';
					}
				}
				$driving_col='<span class="label label-danger"  class="btn btn-default btn-sm" data-popup="popover-custom" data-html="true" data-trigger="focus" tabindex="0"  data-placement="left" title="No license" data-content="<table class=table >'.$driverPopOver.'</table>">'.$sizeOf.'</span>';
			}
			else{
				$driving_col='<span class="label label-info">0</span>';
			}

			if(sizeof($passportNotAvailable) > 0){
				$passportNotAvailable = array_unique($passportNotAvailable);
				$passportPopOver = '<tr>
								<td><strong>Emp Id</strong></td>
								<td><strong>Name</strong></td>
								<td><strong>Email</strong></td>
							</tr>';
				$sizeOf = 0;
				foreach ($passportNotAvailable as $id){
					$emp = $noDocsAvailableEmployees[$id];
					if($emp){
						$sizeOf++;
							$passportPopOver .= '<tr>
								<td>'.$emp["employee_id"].'</td>
								<td>'.$emp["first_name"].'</td>
								<td>'.$emp["email"].'</td>
								</tr>';
					}
				}
				$passport_col='<span class="label label-danger"  data-popup="popover-custom" data-html="true" data-trigger="focus" tabindex="0"  data-placement="left" title="No Passport" data-content="<table class=table >'.$passportPopOver.'</table>">'.$sizeOf.'</span>';
			}
			else{
				$passport_col='<span class="label label-info">0</span>';
			}

			if(sizeof($visaPageNotAvailable) > 0){
				$visaPageNotAvailable = array_unique($visaPageNotAvailable);
				$visaPopOver = '<tr>
								<td><strong>Emp Id</strong></td>
								<td><strong>Name</strong></td>
								<td><strong>Email</strong></td>
							</tr>';
				$sizeOf = 0;
				foreach ($visaPageNotAvailable as $id){
					$emp = $noDocsAvailableEmployees[$id];
					if($emp){
						$sizeOf++;
							$visaPopOver .= '<tr>
									<td>'.$emp["employee_id"].'</td>
									<td>'.$emp["first_name"].'</td>
									<td>'.$emp["email"].'</td>
									</tr>';
					}
				}
				$visa_page_col='<span class="label label-danger"  data-popup="popover-custom" data-html="true" data-trigger="focus" tabindex="0"  data-placement="left" title="No Visa" data-content="<table class=table >'.$visaPopOver.'</table>">'.$sizeOf.'</span>';
			}
			else{
				$visa_page_col='<span class="label label-info">0</span>';
			}

			if(sizeOf($photoCopyEmpsCollection) > 0){
				$photoCopyEmpsCollection = array_unique($photoCopyEmpsCollection);
				$photoPopOver = '<tr>
									<td><strong>Emp Id</strong></td>
									<td><strong>Name</strong></td>
									<td><strong>Email</strong></td>
								</tr>';
				$sizeOf = 0;
				foreach ($photoCopyEmpsCollection as $id){
					$emp = $noDocsAvailableEmployees[$id];
					if($emp){
						$sizeOf++;
							$photoPopOver .= '<tr>
								<td>'.$emp["employee_id"].'</td>
								<td>'.$emp["first_name"].'</td>
								<td>'.$emp["email"].'</td>
								</tr>';
					}
				}
				$photo_copy_col='<span class="label label-danger"  data-popup="popover-custom" data-html="true" data-trigger="focus" tabindex="0"  data-placement="left" title="No Photo" data-content="<table class=table >'.$photoPopOver.'</table>">'.$sizeOf.'</span>';
			}
			else{
				$photo_copy_col='<span class="label label-info">0</span>';
			}

			if(sizeof($labourCardNotAvailable) > 0){
				$labourCardNotAvailable = array_unique($labourCardNotAvailable);
				$labourPopOver = '<tr>
								<td><strong>Emp Id</strong></td>
								<td><strong>Name</strong></td>
								<td><strong>Email</strong></td>
							</tr>';
				$sizeOf = 0;
				foreach ($labourCardNotAvailable as $id){
					$emp = $noDocsAvailableEmployees[$id];
					if($emp){
						$sizeOf++;
							$labourPopOver .= '<tr>
								<td>'.$emp["employee_id"].'</td>
								<td>'.$emp["first_name"].'</td>
								<td>'.$emp["email"].'</td>
								</tr>';
					}
				}
				$labour_card_col='<span class="label label-danger"  data-popup="popover-custom" data-html="true" data-trigger="focus" tabindex="0"  data-placement="left" title="No Labour Card" data-content="<table class=table >'.$labourPopOver.'</table>">'.$sizeOf.'</span>';
			}
			else{
				$labour_card_col='<span class="label label-info">0</span>';
			}
			$contractEmpsCollection = array_unique($contractEmpsCollection);
			if(sizeof($contractEmpsCollection) > 0){
				$contractPopOver = '<tr>
								<td><strong>Emp Id</strong></td>
								<td><strong>Name</strong></td>
								<td><strong>Email</strong></td>
							</tr>';
				$sizeOf = 0;
				foreach ($contractEmpsCollection as $id){
					$emp = $noDocsAvailableEmployees[$id];
					if($emp){
						$sizeOf++;
							$contractPopOver .= '<tr>
								<td>'.$emp["employee_id"].'</td>
								<td>'.$emp["first_name"].'</td>
								<td>'.$emp["email"].'</td>
								</tr>';
					}
				}
				$contract_col='<span class="label label-danger" data-popup="popover-custom" data-html="true" data-trigger="focus" tabindex="0"  data-placement="left" title="No Contract" data-content="<table class=table >'.$contractPopOver.'</table>">'.$sizeOf.'</span>';
			}
			else{
				$contract_col='<span class="label label-info">0</span>';
			}

			if(sizeof($emiratesIdNotAvailable) > 0){
				$emiratesIdNotAvailable = array_unique($emiratesIdNotAvailable);
				$eidPopOver = '<tr>
								<td><strong>Emp Id</strong></td>
								<td><strong>Name</strong></td>
								<td><strong>Email</strong></td>
							</tr>';
				$sizeOf = 0;
				foreach ($emiratesIdNotAvailable as $id){
					$emp = $noDocsAvailableEmployees[$id];
					if($emp){
						$sizeOf++;
							$eidPopOver .= '<tr>
								<td>'.$emp["employee_id"].'</td>
								<td>'.$emp["first_name"].'</td>
								<td>'.$emp["email"].'</td>
								</tr>';
					}
				}
				$emirates_id_col='<span class="label label-danger" data-popup="popover-custom" data-html="true" data-trigger="focus" tabindex="0"  data-placement="left" title="No EID" data-content="<table class=table >'.$eidPopOver.'</table>">'.$sizeOf.'</span>';
			}
			else {
				$emirates_id_col='<span class="label label-info">0</span>';
			}

			if(sizeof($offerLetterNotAvailable) > 0){
				$offerLetterNotAvailable = array_unique($offerLetterNotAvailable);
				$offerPopOver = '<tr>
								<td><strong>Emp Id</strong></td>
								<td><strong>Name</strong></td>
								<td><strong>Email</strong></td>
							</tr>';
				$sizeOf = 0;
				foreach ($offerLetterNotAvailable as $id){
					$emp = $noDocsAvailableEmployees[$id];
					if($emp){
						$sizeOf++;
							$offerPopOver .= '<tr>
								<td>'.$emp["employee_id"].'</td>
								<td>'.$emp["first_name"].'</td>
								<td>'.$emp["email"].'</td>
								</tr>';
					}
				}
				$signed_col='<span class="label label-danger" data-popup="popover-custom" data-html="true" data-trigger="focus" tabindex="0"  data-placement="left" title="No Offer Letter" data-content="<table class=table >'.$offerPopOver.'</table>">'.$sizeOf.'</span>';
			}
			else {
				$signed_col='<span class="label label-info">0</span>';
			}
			$data[] = array(
				$visa_name,
				$total_employees,
				$passport_col,
				$visa_page_col,
				$photo_copy_col,
				$labour_card_col,
				$contract_col,
				$driving_col,
				$emirates_id_col,
				$signed_col,
			);

		}
		$output = array(
			"draw" => $draw,
			"recordsTotal" => count($uploaded_document_list),
			"recordsFiltered" =>count($uploaded_document_list),
			"data" => $data
		);
		$this->output($output);
	}

	public function expiry_document_list(){
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("reports/uploaded_documents", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$expiry_document_list = $this->Reports_model->expiry_document_list();
		$current_month=date('Y-m');
		$next_month=date('Y-m',strtotime("+1 month",strtotime($current_month)));
		$secondNextMonth =date('Y-m',strtotime("+2 month",strtotime($current_month)));
		$thirdNextMonth =date('Y-m',strtotime("+3 month",strtotime($current_month)));
		$prev_month=date('Y-m',strtotime("-1 month",strtotime($current_month)));
		$expiry_status = $_GET['custom_filter'];

		$data = array();
		foreach($expiry_document_list as $r) {
			$full_name=change_fletter_caps($r->first_name.' '.$r->middle_name.' '.$r->last_name);
			$visa_name=$r->visa_name;
			$visa_issue_date=$r->visa_issue_date;
			$visa_expiry_date=$r->visa_expiry_date;


			$dri_details=$this->Reports_model->get_document_details($r->user_id,1);
			$d_issue_date=@$dri_details[0]->issue_date;
			$d_expiry_date=@$dri_details[0]->expiry_date;

			$pass_details=$this->Reports_model->get_document_details($r->user_id,2);
			$passport_issue_date=$pass_details[0]->issue_date;
			$passport_expiry_date=$pass_details[0]->expiry_date;


			$eid_details=$this->Reports_model->get_document_details($r->user_id,4);
			$eid_expiry_date=$eid_details[0]->expiry_date;


			$labour_details=$this->Reports_model->get_document_details($r->user_id,5);

			$labour_issue_date=@$labour_details[0]->issue_date;
			$labour_expiry_date=@$labour_details[0]->expiry_date;

			$contract_issue_date=$r->contract_issue_date;
			$contract_expiry_date=$r->contract_expiry_date;

			if($visa_issue_date!=''){
				$visa_col_issue=$this->Xin_model->set_date_format($visa_issue_date);
			}else{
				$visa_col_issue='';
			}
			if($d_issue_date!=''){
				$dri_col_issue=$this->Xin_model->set_date_format($d_issue_date);
			}else{
				$dri_col_issue='';
			}
			if($passport_issue_date!=''){
				$pass_col_issue=$this->Xin_model->set_date_format($passport_issue_date);
			}else{
				$pass_col_issue='';
			}

			if($labour_issue_date!=''){
				$labour_col_issue=$this->Xin_model->set_date_format($labour_issue_date);
			}else{
				$labour_col_issue='';
			}
			if($contract_issue_date!=''){
				$contract_col_issue=$this->Xin_model->set_date_format($contract_issue_date);
			}else{
				$contract_col_issue='';
			}

			if($visa_expiry_date!=''){
				if(date('Y-m',strtotime($visa_expiry_date))==$current_month && ($expiry_status == 'all' || $expiry_status == 'current')){
					$visa_col_exp='<span class="label label-danger">'.$this->Xin_model->set_date_format($visa_expiry_date).'</span>';
				}else if((date('Y-m',strtotime($visa_expiry_date))==$next_month
						|| date('Y-m',strtotime($visa_expiry_date))==$secondNextMonth
						|| date('Y-m',strtotime($visa_expiry_date))== $thirdNextMonth
					)&& ($expiry_status == 'all' || $expiry_status == 'next')
				){
					$visa_col_exp='<span class="label label-info">'.$this->Xin_model->set_date_format($visa_expiry_date).'</span>';
				}else if(date('Y-m',strtotime($visa_expiry_date))<=$prev_month && ($expiry_status == 'all' || $expiry_status == 'expired')){
					$visa_col_exp='<span class="label label-warning">'.$this->Xin_model->set_date_format($visa_expiry_date).'</span>';
				}
				else{
					$visa_col_exp='';
				}
			}else{
				$visa_col_exp='';
			}


			if($d_expiry_date!=''){
				if(date('Y-m',strtotime($d_expiry_date))==$current_month  && ($expiry_status == 'all' || $expiry_status == 'current')){
					$dri_col_exp='<span class="label label-danger">'.$this->Xin_model->set_date_format($d_expiry_date).'</span>';
				}else if((date('Y-m',strtotime($d_expiry_date))==$next_month
						|| date('Y-m',strtotime($d_expiry_date))==$secondNextMonth
						|| date('Y-m',strtotime($d_expiry_date))== $thirdNextMonth
					)&& ($expiry_status == 'all' || $expiry_status == 'next')
				){
					$dri_col_exp='<span class="label label-info">'.$this->Xin_model->set_date_format($d_expiry_date).'</span>';
				}else if(date('Y-m',strtotime($d_expiry_date))<=$prev_month && ($expiry_status == 'all' || $expiry_status == 'expired')){
					$dri_col_exp='<span class="label label-warning">'.$this->Xin_model->set_date_format($d_expiry_date).'</span>';
				}
				else{
					$dri_col_exp='';
				}
			}else{
				$dri_col_exp='';
			}

			if($passport_expiry_date!=''){
				if(date('Y-m',strtotime($passport_expiry_date))==$current_month  && ($expiry_status == 'all' || $expiry_status == 'current')){
					$pass_col_exp='<span class="label label-danger">'.$this->Xin_model->set_date_format($passport_expiry_date).'</span>';
				}else if((date('Y-m',strtotime($passport_expiry_date))==$next_month
						|| date('Y-m',strtotime($passport_expiry_date))==$secondNextMonth
						|| date('Y-m',strtotime($passport_expiry_date))== $thirdNextMonth
					)&& ($expiry_status == 'all' || $expiry_status == 'next')
				){
					$pass_col_exp='<span class="label label-info">'.$this->Xin_model->set_date_format($passport_expiry_date).'</span>';
				}else if(date('Y-m',strtotime($passport_expiry_date))<=$prev_month && ($expiry_status == 'all' || $expiry_status == 'expired')){
					$pass_col_exp='<span class="label label-warning">'.$this->Xin_model->set_date_format($passport_expiry_date).'</span>';
				}else{
					$pass_col_exp='';
				}
			}else{
				$pass_col_exp='';
			}

			if($eid_expiry_date!=''){
				if(date('Y-m',strtotime($eid_expiry_date))==$current_month  && ($expiry_status == 'all' || $expiry_status == 'current')){
					$eid_col_exp='<span class="label label-danger">'.$this->Xin_model->set_date_format($eid_expiry_date).'</span>';
				}else if((date('Y-m',strtotime($eid_expiry_date))==$next_month
						|| date('Y-m',strtotime($eid_expiry_date))==$secondNextMonth
						|| date('Y-m',strtotime($eid_expiry_date))== $thirdNextMonth
					)&& ($expiry_status == 'all' || $expiry_status == 'next')
				){
					$eid_col_exp='<span class="label label-info">'.$this->Xin_model->set_date_format($eid_expiry_date).'</span>';
				}else if(date('Y-m',strtotime($eid_expiry_date))<=$prev_month && ($expiry_status == 'all' || $expiry_status == 'expired')){
					$eid_col_exp='<span class="label label-warning">'.$this->Xin_model->set_date_format($eid_expiry_date).'</span>';
				}else{
					$eid_col_exp='';
				}

			}else{
				$eid_col_exp='';
			}

			if($labour_expiry_date!=''){
				if(date('Y-m',strtotime($labour_expiry_date))==$current_month  && ($expiry_status == 'all' || $expiry_status == 'current')){
					$labour_col_exp='<span class="label label-danger">'.$this->Xin_model->set_date_format($labour_expiry_date).'</span>';
				}else if((date('Y-m',strtotime($labour_expiry_date))==$next_month
						|| date('Y-m',strtotime($labour_expiry_date))==$secondNextMonth
						|| date('Y-m',strtotime($labour_expiry_date))== $thirdNextMonth
					)&& ($expiry_status == 'all' || $expiry_status == 'next')
				){
					$labour_col_exp='<span class="label label-info">'.$this->Xin_model->set_date_format($labour_expiry_date).'</span>';
				}
				else if(date('Y-m',strtotime($labour_expiry_date))<=$prev_month && ($expiry_status == 'all' || $expiry_status == 'expired')){
					$labour_col_exp='<span class="label label-warning">'.$this->Xin_model->set_date_format($labour_expiry_date).'</span>';
				}
				else{
					$labour_col_exp='';
				}
			}else{
				$labour_col_exp='';
			}
			if(strpos("N/A",$labour_col_exp)>0){
				$labour_col_exp='';

			}

			if($contract_expiry_date!=''){
				$c_expiry_date = $this->Xin_model->set_date_format($contract_expiry_date);
				if($c_expiry_date == 'N/A')
					$cont_col_exp='';
				else {
					if (date('Y-m', strtotime($contract_expiry_date)) == $current_month  && ($expiry_status == 'all' || $expiry_status == 'current')) {
						$cont_col_exp = '<span class="label label-danger">' . $this->Xin_model->set_date_format($contract_expiry_date) . '</span>';
					} else if ((date('Y-m', strtotime($contract_expiry_date)) == $next_month
							|| date('Y-m', strtotime($contract_expiry_date)) == $secondNextMonth
							|| date('Y-m', strtotime($contract_expiry_date)) == $thirdNextMonth
						)&& ($expiry_status == 'all' || $expiry_status == 'next')
					){
						$cont_col_exp = '<span class="label label-info">' . $this->Xin_model->set_date_format($contract_expiry_date) . '</span>';
					} else if (date('Y-m', strtotime($contract_expiry_date)) <= $prev_month && ($expiry_status == 'all' || $expiry_status == 'expired')) {
						$cont_col_exp = '<span class="label label-warning">' . $this->Xin_model->set_date_format($contract_expiry_date) . '</span>';
					} else {
						$cont_col_exp = '';
					}
				}
			}else{
				$cont_col_exp='';
			}
			if($cont_col_exp!='' || $labour_col_exp!='' || $eid_col_exp!='' || $pass_col_exp!='' || $dri_col_exp!='' || $visa_col_exp!=''){
				$data[] = array(
					$full_name,
					$visa_name,
//					$visa_col_issue,
					$visa_col_exp,
//					$dri_col_issue,
					$dri_col_exp,
//					$pass_col_issue,
					$pass_col_exp,
					$eid_col_exp,
//					$labour_col_issue,
					$labour_col_exp,
//					$cont_col_issue,
					$cont_col_exp,
				);
			}

		}
		$output = array(
			"draw" => $draw,
			"recordsTotal" => count($expiry_document_list),
			"recordsFiltered" =>count($expiry_document_list),
			"data" => $data
		);
		$this->output($output);
	}

	public function employees_report_list(){
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("reports/uploaded_documents", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		$datas = [];
		$department_value=$this->input->get('department_value');
		$location_value=$this->input->get('location_value');
		$medical_card_value=$this->input->get('medical_card_value');
		$visa_value=$this->input->get('visa_value');
		$cookie_detail1=json_decode(get_cookie('employee_reports_cookie',true));
		$employee_reports_list = $this->Reports_model->employee_reports($department_value,$location_value,$medical_card_value,$visa_value,@$cookie_detail1->country_details);
		$cookie_detail1->country_details;
		$country_name=return_country_name($cookie_detail1->country_details);
		$data = array();
		foreach($employee_reports_list as $r) {
			$full_name=change_fletter_caps($r->first_name.' '.$r->middle_name.' '.$r->last_name);
			$biometric_id='';
			$is_active=$r->is_active;
			$employee_id=$r->employee_id;
			$email=$r->email;
			$personal_email=$r->personal_email;
			$date_of_birth=$this->Xin_model->set_date_format($r->date_of_birth);
			$gender=$r->gender;
			$date_of_joining=$this->Xin_model->set_date_format($r->date_of_joining);
			$date_of_leaving=$this->Xin_model->set_date_format($r->date_of_leaving);
			$marital_status=$r->marital_status;
			$contact_no=$r->contact_no;
			$languages_known=$r->languages_known;
			$nationality=$r->nationality;
			if($r->reporting_manager!=0){
				$r_p_user = $this->Xin_model->read_user_info($r->reporting_manager);
				$reporting_manager=change_fletter_caps($r_p_user[0]->first_name.' '.$r_p_user[0]->middle_name.' '.$r_p_user[0]->last_name);
			}else{
				$reporting_manager='--';
			}
			$home_address1=$r->home_address1;
			$home_address2=$r->home_address2;
			$home_city=$r->home_city;
			$home_area=$r->home_area;
			$home_zipcode=$r->home_zipcode;
			$home_country=$r->home_country;
			$residing_address1=$r->residing_address1;
			$residing_address2=$r->residing_address2;
			$residing_city=$r->residing_city;
			$residing_area=$r->residing_area;
			$residing_zipcode=$r->residing_zipcode;
			$residing_country=$r->residing_country;
			$blood_group=$r->blood_group;
			$progress_bar=get_profile_complete_progress($r->user_id);
			$progress_bar=substr($progress_bar,138,5);
			if($is_active==1){$status='<span class="label label-success">Active</span>';}else{$status='<span class="label label-danger">In-Active</span>';}
			$department_name=$r->department_name;
			$designation_name=$r->designation_name;
			$role_name=$r->role_name;
			$shift_in_time=$r->shift_in_time;
			$shift_out_time=$r->shift_out_time;
			$week_off=$r->week_off;
			$location_name=$r->location_name;

			$account_title=$r->account_title;
			$account_number=$r->account_number;
			$bank_name=$r->bank_name;
			$bank_code=$r->bank_code;
			$bank_branch=$r->bank_branch;
			$emergency_contact_relation=$r->emergency_contact_relation;
			$relation_name=$r->relation_name;
			$relation_phone_no=$r->relation_phone_no;
			$relationaddress1=$r->relationaddress1;
			$relationaddress2=$r->relationaddress2;
			$relationcity=$r->relationcity;
			$relationstate=$r->relationstate;
			$relation_zipcode=$r->relation_zipcode;
			$relation_country=$r->relation_country;
			$basic_salary=$r->basic_salary;
			$house_rent_allowance=$r->house_rent_allowance;
			$travelling_allowance=$r->travelling_allowance;
			$food_allowance=$r->food_allowance;
			$other_allowance=$r->other_allowance;
			$additional_benefits=$r->additional_benefits;
			$bonus=$r->bonus;
			$effective_from_date=$this->Xin_model->set_date_format($r->effective_from_date);
			$agreed_bonus=$r->agreed_bonus;
			$salary_based_on_contract=$r->salary_based_on_contract;
			$salary_with_bonus=$r->salary_with_bonus;
			$agency_fee=$r->agency_fee;

			$support_documents=$this->Reports_model->getsupport_documents($cookie_detail1->country_details);
			//print_r($support_documents);

			if($country_name=='United Arab Emirates'){
				$cont_details=$this->Reports_model->get_contract_details($r->user_id);
				$contract_name=@$cont_details[0]->contract_name;
				$contract_start_date=@$this->Xin_model->set_date_format($cont_details[0]->from_date);
				if($cont_details[0]->to_date!='Unlimited'){
					$contract_end_date=@$this->Xin_model->set_date_format($cont_details[0]->to_date);
				}else{
					$contract_end_date='Unlimited';
				}
			}

			/*$visa_details=$this->Reports_model->get_document_details($r->user_id,3);
            $visa_name=@$visa_details[0]->type;
            $visa_number=@$visa_details[0]->document_number;
            $visa_issue_date=@$this->Xin_model->set_date_format($visa_details[0]->issue_date);
            $visa_expiry_date=@$this->Xin_model->set_date_format($visa_details[0]->expiry_date);
            $dri_details=$this->Reports_model->get_document_details($r->user_id,1);
            $driv_lic_number=@$dri_details[0]->document_number;
            $driv_issue_date=@$this->Xin_model->set_date_format($dri_details[0]->issue_date);
            $driv_expiry_date=@$this->Xin_model->set_date_format($dri_details[0]->expiry_date);
            $pass_details=$this->Reports_model->get_document_details($r->user_id,2);
            $pass_number=@$pass_details[0]->document_number;
            $pass_issue_date=@$this->Xin_model->set_date_format($pass_details[0]->issue_date);
            $pass_expiry_date=@$this->Xin_model->set_date_format($pass_details[0]->expiry_date);
            $eid_details=$this->Reports_model->get_document_details($r->user_id,4);
            $eid_number=@$eid_details[0]->document_number;
            $eid_expiry_date=@$this->Xin_model->set_date_format($eid_details[0]->expiry_date);
            $labour_details=$this->Reports_model->get_document_details($r->user_id,5);
            $lab_number=@$labour_details[0]->document_number;
            $lab_issue_date=@$this->Xin_model->set_date_format($labour_details[0]->issue_date);
            $lab_expiry_date=@$this->Xin_model->set_date_format($labour_details[0]->expiry_date);

            $med_details=$this->Reports_model->get_document_details($r->user_id,6);
            $med_name=@$med_details[0]->type;
            $med_number=@$med_details[0]->document_number;
            $med_issue_date=@$this->Xin_model->set_date_format($med_details[0]->issue_date);
            $med_expiry_date=@$this->Xin_model->set_date_format($med_details[0]->expiry_date);
            */


			$data = array(
				$status,
				$full_name,
				$employee_id,
				$date_of_joining,
				$designation_name.' ('.$department_name.')',
			);
			if($cookie_detail1->basic_details=='yes'){
				array_push($data,$date_of_leaving);
				array_push($data,$biometric_id);
				array_push($data,$email);
				array_push($data,$personal_email);
				array_push($data,$date_of_birth);
				array_push($data,$gender);
				array_push($data,$marital_status);
				array_push($data,$blood_group);
				array_push($data,$contact_no);
				array_push($data,$languages_known);
				array_push($data,$nationality);
				array_push($data,$reporting_manager);
			}
			if($country_name=='United Arab Emirates'){
				array_push($data,$contract_name);
				array_push($data,$contract_start_date);
				array_push($data,$contract_end_date);
			}
			if($support_documents){
				foreach($support_documents as $sup_doc){
					$type_name=str_replace(' ','-',str_replace("'","-",$sup_doc->type_name));
					$label_name='doc_'.$type_name;
					$label_names=$this->Reports_model->get_document_details($r->user_id,$sup_doc->type_id);

					if($label_names){
						$label_type=@$label_names[0]->type;
						$label_number=@$label_names[0]->document_number;
						$label_issue_date=@$this->Xin_model->set_date_format($label_names[0]->issue_date);
						$label_expiry_date=@$this->Xin_model->set_date_format($label_names[0]->expiry_date);
					}else{
						$label_type='';$label_number='';$label_issue_date='';$label_expiry_date='';
					}
					if($sup_doc->type_name=='Visa' || $sup_doc->type_name=='Medical Card'){
						array_push($data,$label_type);
					}
					array_push($data,$label_number);
					array_push($data,$label_issue_date);
					array_push($data,$label_expiry_date);
				}
			}


			/*
            array_push($data,$visa_name);
            array_push($data,$visa_number);
            array_push($data,$visa_issue_date);
            array_push($data,$visa_expiry_date);
            array_push($data,$driv_lic_number);
            array_push($data,$driv_issue_date);
            array_push($data,$driv_expiry_date);
            array_push($data,$pass_number);
            array_push($data,$pass_issue_date);
            array_push($data,$pass_expiry_date);
            array_push($data,$eid_number);
            array_push($data,$eid_expiry_date);
            array_push($data,$lab_number);
            array_push($data,$lab_issue_date);
            array_push($data,$lab_expiry_date);
            array_push($data,$med_name);
            array_push($data,$med_number);
            array_push($data,$med_issue_date);
            array_push($data,$med_expiry_date);
            }*/

			if($cookie_detail1->paystructure=='yes'){
				array_push($data,$basic_salary);
				array_push($data,$house_rent_allowance);
				array_push($data,$travelling_allowance);
				array_push($data,$food_allowance);
				array_push($data,$other_allowance);
				array_push($data,$additional_benefits);
				array_push($data,$bonus);
				array_push($data,$agreed_bonus);
				array_push($data,$agency_fee);
				array_push($data,$salary_based_on_contract);
				array_push($data,$salary_with_bonus);
				array_push($data,$effective_from_date);
			}
			if($cookie_detail1->emergency_contact=='yes'){
				array_push($data,$emergency_contact_relation);
				array_push($data,$relation_name);
				array_push($data,$relation_phone_no);
				array_push($data,$relationaddress1);
				array_push($data,$relationaddress2);
				array_push($data,$relationcity);
				array_push($data,$relationstate);
				array_push($data,$relation_zipcode);
				array_push($data,$relation_country);
			}

			if($cookie_detail1->bank_details=='yes'){
				array_push($data,$account_number);
				array_push($data,$bank_name);
				array_push($data,$bank_code);
				array_push($data,$bank_branch);
			}


			if($cookie_detail1->address_details=='yes'){
				array_push($data,$home_address1);
				array_push($data,$home_address2);
				array_push($data,$home_city);
				array_push($data,$home_area);
				array_push($data,$home_zipcode);
				array_push($data,$home_country);
				array_push($data,$residing_address1);
				array_push($data,$residing_address2);
				array_push($data,$residing_city);
				array_push($data,$residing_area);
				array_push($data,$residing_zipcode);
				array_push($data,$residing_country);
			}

			if($cookie_detail1->shift_details=='yes'){
				array_push($data,$shift_in_time);
				array_push($data,$shift_out_time);
				array_push($data,$week_off);
				array_push($data,$location_name);
			}
			if($cookie_detail1->basic_details=='yes'){
				array_push($data,$progress_bar);
			}

			$datas[]=$data;

		}
		$output = array(
			"draw" => $draw,
			"recordsTotal" => count($employee_reports_list),
			"recordsFiltered" =>count($employee_reports_list),
			"data" => $datas
		);
		$this->output($output);
	}

}
