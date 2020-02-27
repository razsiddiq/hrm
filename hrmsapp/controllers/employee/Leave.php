<?php
/**
 * Author Siddiqkhan
 *
 * Leave Controller
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Leave extends MY_Controller {

	public $userSession = null;

	public function __construct() {
		Parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->database();
		$this->load->library('form_validation');
		//load the model
		$this->load->model("Timesheet_model");
		$this->load->model("Employees_model");
		$this->load->model("Xin_model");
		$this->load->model("Department_model");
		$this->load->model("Designation_model");
		$this->load->model("Roles_model");
		$this->load->model("Location_model");
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
		$data['all_leave_types'] = $this->Timesheet_model->all_leave_types();
		$data['breadcrumbs'] = 'Leave';
		$data['path_url'] = 'user/user_leave';
		$data['all_countries'] = $this->Xin_model->get_countries();
		if(!empty($this->userSession)){
			$data['subview'] = $this->load->view("user/leave", $data, TRUE);
			$this->load->view('layout_main', $data);
		} else {
			redirect('');
		}
	}

	public function leave_conversion(){
		$data['title'] = $this->Xin_model->site_title();
		$data['all_employees'] = $this->Xin_model->all_employees();
		$data['all_leave_types'] = $this->Timesheet_model->all_leave_types();
		$data['breadcrumbs'] = 'Leave';
		$data['path_url'] = 'user/user_leave_conversion';
		if(!empty($this->userSession)){
			$data['subview'] = $this->load->view("user/leave_conversion", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
		}
		else{
			redirect('');
		}
	}

	public function leave_list() {
		$data['title'] = $this->Xin_model->site_title();
		if(empty($this->userSession)){
			redirect('');
		}
		$leave = $this->Timesheet_model->get_employee_leaves($this->userSession['user_id']);
		$data = '';

		if(!empty($leave->result())){
			foreach($leave->result() as $r) {
				$leave_type = $this->Timesheet_model->read_leave_type_information($r->leave_type_id);
				$applied_on = $this->Xin_model->set_date_format($r->created_at);
				if($r->count_of_days==1){
					$f_dy=$r->count_of_days.' day';
				}
				else{
					$f_dy=$r->count_of_days.' days';
				}
				
				$approval_query=$this->db->query("select * from xin_employees_approval where type_of_approval='leave_request' AND field_id='".$r->applied_on."' order by approval_id desc limit 1");
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
					if($r->status==1 && $r->reporting_manager_status==1 ): $status = '<span class="label label-warning" style="float:right;">Pending</span>'; elseif($r->status==2  && $r->reporting_manager_status==2 ): $status = '<span class="label label-success" style="float:right;">Approved</span>';
					elseif($r->status==3  && $r->reporting_manager_status==3 ): $status = '<span class="label label-danger" style="float:right;">Rejected</span>';
					elseif($r->status==4  && $r->reporting_manager_status==4 ): $status = '<span class="label label-warning"style="float:right;">Pending</span>';
					endif;
				}

				if($r->status==1 && $r->reporting_manager_status==1 ){ 

					$edit_perm='<li><a class="edit-data" data-backdrop="static" data-keyboard="false" href="#" data-leave_id="'. $r->leave_id . '" data-toggle="modal" data-target=".edit-modal-data-1" ><i class="icon-pencil7"></i> Edit</a></li>';
					
					$delete_perm='<li><a class="delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->leave_id . '" title="Delete"><i class="icon-trash"></i> Delete</a></li>';

					$action = '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$delete_perm.'</ul></li></ul>';

				}else{
					$action = '<ul class="icons-list" style="cursor:not-allowed;"><i class="icon-cross"></i></ul>';	
				}

				$data.='<tr><td>'.$applied_on.'</td>
				  <td>'.$leave_type[0]->type_name.' '.$status.'</td>
				  <td>'.$this->Xin_model->set_date_format($r->from_date).'</td>
				  <td>'.$this->Xin_model->set_date_format($r->to_date).'</td>
				  <td>'.$f_dy.'</td>	
				  <td>'.$action.'</td>
			</tr>';
			}
		}else{
			$data.='<tr align="center"><td colspan="5">No Data Found...</td></tr>';
		}
		echo $data;

	}

	public function editLeaveForm(){

		// $leave_id = $this->input->post('leave_id');
		// $data['leave_details'] = $this->Timesheet_model->getLeaveDetails($leave_id);		
		// $data['all_leave_types'] = $this->Timesheet_model->all_leave_types();
		// $view = $this->load->view('timesheet/dialog_edit_leave',$data,true);
		// $result['view'] = $view;

		// echo json_encode($result);


		$leave_id = $this->input->get('leave_id');
		$result = $this->Timesheet_model->getLeaveDetails($leave_id);
		
		$data = array(
			'leave_id' => $result[0]->leave_id,
			'employee_id' => $result[0]->employee_id,
			'leave_type_id' => $result[0]->leave_type_id,
			'from_date' => $result[0]->from_date,
			'to_date' => $result[0]->to_date,
			'documentfile' => $result[0]->documentfile,
			'reason' => $result[0]->reason,
			'applied_on' => $result[0]->applied_on,
		);
	
		if(!empty($this->userSession)){
			$this->load->view('timesheet/dialog_leave', $data);
		} else {
			redirect('');
		}


	}

	public function delete_leave() {
		if($this->input->post('form')=='delete_record') {
			$Return = array('result'=>'', 'error'=>'');
			$unique_id = $this->uri->segment(4);
			
			/*User Logs*/
			$affected_row= table_deleted_row('xin_leave_applications','leave_id',$unique_id);
			userlogs('Employees-Leave Request-Delete','Leave Request Deleted',$unique_id,$affected_row);
			/*User Logs*/

            $this->Timesheet_model->delete_leave_record($unique_id);
			if(isset($unique_id)) {
				$Return['result'] = 'Leave request deleted.';
			} else {
				$Return['error'] = 'Bug. Something went wrong, please try again.';
			}
			$this->output($Return);
		}
	}

}
