<?php
/**
 * @Author Siddiqkhan
 *
 * @Roles Controller 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles extends MY_Controller {

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
		$this->load->model("Roles_model");
		$this->load->model("Xin_model");
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
		$data['all_employees'] = $this->Roles_model->all_active_employees_custom_roles();
		$data['breadcrumbs'] = 'User Roles';
		$data['path_url'] = 'roles';
        if(in_array('14',role_resource_ids()) || in_array('14a',role_resource_ids()) || in_array('14e',role_resource_ids()) || in_array('14d',role_resource_ids()) || in_array('14v',role_resource_ids())) {
			if(!empty($this->userSession)){
                $data['subview'] = $this->load->view("roles/role_list", $data, TRUE);
                $this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}		  
     }
 
    public function role_list()
     {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("roles/role_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$role = $this->Roles_model->get_user_roles();
		$data = array();
        foreach($role->result() as $r) {
			if($r->role_access==1):
                $r_access = 'All Menu Access';
			elseif($r->role_access==2):
                $r_access = 'Custom Menu Access';
			endif;

			$created_at = $this->Xin_model->set_date_format($r->created_at);
			$edit_perm='';
			$delete_perm='';
		    if(in_array('14e',role_resource_ids())) {
				$edit_perm='<li><a data-toggle="modal" data-target=".edit-modal-data"  data-role_id="'. $r->role_id . '"><i class="icon-pencil4"></i> Edit</a></li>'; 
			}
		
			if(in_array('14d',role_resource_ids()) && ($r->role_name!=AD_ROLE)) {
				$delete_perm=' <li><a class="delete" href="#"  data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->role_id . '"><i class="icon-trash"></i> Delete</a></li>';  
			}
			$data[] = array(
	            $r->role_id,
				$r->role_name,
				$r_access,
				$created_at,
				'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$delete_perm.'</ul></li></ul>',
		    );
        }
        $output = array(
                 "draw" => $draw,
                 "recordsTotal" => $role->num_rows(),
                 "recordsFiltered" => $role->num_rows(),
                 "data" => $data
        );
        $this->output($output);
     }
	 
	public function custom_role_list()
    {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("roles/role_list", $data);
		} else {
			redirect('');
		}
		$draw = intval($this->input->get("draw"));
		$role = $this->Roles_model->get_custom_user_roles();
		$data = array();
		foreach($role->result() as $r) {
              $custom_role=json_decode($r->custom_roles);
			  $added_by=$custom_role->added_by;
		      $created_at=$this->Xin_model->set_date_format($custom_role->added_date).' '.format_date('H:i:s',$custom_role->added_date);
			  $edit_perm='';
			  $delete_perm='';
			  if(in_array('14e',role_resource_ids())) {
				$edit_perm='<li class="edit_custom_role" rel='. $r->user_id .'><a><i class="icon-pencil4"></i> Edit</a></li>'; 
			  }
		
			  if(in_array('14d',role_resource_ids())) {
				 $delete_perm=' <li><a class="delete_custom_role" href="#"  data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->user_id . '"><i class="icon-trash"></i> Delete</a></li>';  
			  }
			  
			  if($edit_perm=='' && $delete_perm==''){
				$edit_perm='<li><a class="text-danger">No Permission</a></li>';  
			  }
			  $data[] = array(
				$r->user_id,
				change_fletter_caps($r->first_name.' '.$r->middle_name.' '.$r->last_name),
				$created_at,
				'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$edit_perm.$delete_perm.'</ul></li></ul>',
              );
		}
        $output = array(
               "draw" => $draw,
                 "recordsTotal" => $role->num_rows(),
                 "recordsFiltered" => $role->num_rows(),
                 "data" => $data
        );
		$this->output($output);
    }

    public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$visa_type = $this->Xin_model->get_visa_under();
		$data['visa_type'] = $visa_type->result();

		$id = $this->input->get('role_id');
		$result = $this->Roles_model->read_role_information($id);
		if($result){
		        $data = array(
                    'role_id' => $result[0]->role_id,
                    'role_name' => $result[0]->role_name,
                    'role_access' => $result[0]->role_access,
                    'role_resources' => $result[0]->role_resources,
				);
		}
		if(!empty($this->userSession)){
			$this->load->view('roles/dialog_role', $data);
		} else {
			redirect('');
		}
	}	

	public function add_role() {
		
		if($this->input->post('add_type')=='role') {
            $Return = array('result'=>'', 'error'=>'');
            $check_rolename = $this->Roles_model->check_rolename($this->input->post('role_name'),'');
            if($this->input->post('role_name')==='') {
                $Return['error'] = "The role name field is required.";
            } else if($check_rolename!=0){
                $Return['error'] = 'The role name already exist.Enter different one.';
            } else if($this->input->post('role_access')==='') {
                $Return['error'] = "The access field is required.";
            }
            $role_resources = implode(',',$this->input->post('role_resources'));
            if($Return['error']!=''){
                $this->output($Return);
            }
            $data = array(
                'role_name' => $this->input->post('role_name'),
                'role_access' => $this->input->post('role_access'),
                'role_resources' => $role_resources,
                'created_at' => date('d-m-Y'),
            );
            $result = $this->Roles_model->add($data);
            /*User Logs*/
            $affected_id= table_max_id('xin_user_roles','role_id');
            userlogs('Roles-Set Roles-Add','New Roles Added',$affected_id['field_id'],$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'User Role added.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function update() {
		if($this->input->post('edit_type')=='role') {
            $id = $this->uri->segment(3);
            $Return = array('result'=>'', 'error'=>'');
            $check_rolename = $this->Roles_model->check_rolename($this->input->post('role_name'),$id);
            if($this->input->post('role_name')==='') {
                $Return['error'] = "The role name field is required.";
            }  else if($check_rolename!=0){
                $Return['error'] = 'The role name already exist.Enter different one.';
            }  else if($this->input->post('role_access')==='') {
                $Return['error'] = "The access field is required.";
            }
            $role_resources = implode(',',$this->input->post('role_resources'));
            if($Return['error']!=''){
                $this->output($Return);
            }
            $data = array(
                'role_name' => $this->input->post('role_name'),
                'role_access' => $this->input->post('role_access'),
                'role_resources' => $role_resources,
            );
            $result = $this->Roles_model->update_record($data,$id);
            /*User Logs*/
            $affected_id= table_update_id('xin_user_roles','role_id',$id);
            userlogs('Roles-Set Roles-Update','New Roles Updated',$id,$affected_id['datas']);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'User Role updated.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}
	
	public function custom_update(){
		
		if($this->input->post('edit_type')=='custom_role') {
            $emp_id = $this->uri->segment(3);
            $Return = array('result'=>'', 'error'=>'');
            $role_resources = implode(',',$this->input->post('role_resources'));
            if(!$this->input->post('role_resources') && !$this->input->post('visa_wise_role')){
                $Return['error'] = 'Select atleast one checkbox';
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'added_by' => $this->input->post('added_by'),
                'added_date' => $this->input->post('added_date'),
                'role_resources' => $role_resources,
                'visa_wise_role'	=> $this->input->post('visa_wise_role'),
            );
            $result = $this->Roles_model->update_custom_record(json_encode($data),$emp_id);
            /*User Logs*/
            userlogs('Roles-Custom Roles-Update','Custom Roles Updated',$emp_id,$role_resources);
            /*User Logs*/
            if ($result == TRUE) {
                $Return['result'] = 'Custom Role updated.';
            } else {
                $Return['error'] = 'Bug. Something went wrong, please try again.';
            }
            $this->output($Return);
            exit;
		}
	}

	public function get_role_employees(){
		$data='';
		$data.='<select name="employee_id" id="employee_id" class="form-control" data-plugin="select_hrm" data-placeholder="Choose Employees..."><option value="0">All Employees</option>';
		$role = $this->Roles_model->all_active_employees_custom_roles();
		foreach($role as $employee) {
           $data.='<option value="'.$employee->user_id.'">'.change_fletter_caps($employee->first_name.' '.$employee->middle_name.' '.$employee->last_name).'</option>';
		} 
		$data.='</select>';
		echo  $data;
	}

	public function delete() {
		$Return = array('result'=>'', 'error'=>'');
		$id = $this->uri->segment(3);
		/*User Logs*/
		$affected_row= table_deleted_row('xin_user_roles','role_id',$id);		
		userlogs('Roles-Set Roles-Delete','Roles Deleted',$id,$affected_row);
		/*User Logs*/
		$this->Roles_model->delete_record($id);
		if(isset($id)) {
			$Return['result'] = 'User Role deleted.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
	}
	
	public function delete_custom_role() {
		$Return = array('result'=>'', 'error'=>'');
		$id = $this->uri->segment(3);
		/*User Logs*/
		userlogs('Roles-Custom Roles-Delete','Custom Roles Deleted',$id,'');
		/*User Logs*/
		$this->Roles_model->update_custom_record('',$id);
		if(isset($id)) {
			$Return['result'] = 'Custom Role deleted.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
	}
	
}