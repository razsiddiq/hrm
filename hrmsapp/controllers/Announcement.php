<?php
/**
 * @Author Siddiqkhan
 *
 * @Announcement Controller
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Announcement extends MY_Controller {

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
		$this->load->model("Announcement_model");
		$this->load->model("Xin_model");
		$this->load->model("Company_model");
		$this->load->model("Location_model");
		$this->load->model("Department_model");
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
		$data['breadcrumbs'] = $this->lang->line('xin_announcements');
		$data['path_url'] = 'announcements';
		$data['get_all_companies'] = $this->Company_model->get_all_companies();
		$data['all_office_locations'] = $this->Location_model->all_office_locations();
		$data['all_departments'] = $this->Department_model->all_departments();
		if(in_array('8',role_resource_ids())) {
			if(!empty($this->userSession)){
			$data['subview'] = $this->load->view("announcement/announcement_list", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}		  
    }
 
    public function announcement_list()
     {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("announcement/announcement_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$announcement = $this->Announcement_model->get_announcements();
		$data = array();
        foreach($announcement->result() as $r) {
		$user = $this->Xin_model->read_user_info($r->published_by);
		// user full name
		$full_name = change_fletter_caps($user[0]->first_name);
		// get date
		$sdate = $this->Xin_model->set_date_format($r->start_date);
		$edate = $this->Xin_model->set_date_format($r->end_date);
		
		$str_dep='';
		if($r->department_id!=''){
		$department_id=explode(',',$r->department_id);
		foreach($department_id as $dep_id){
		$de_name = $this->Department_model->read_department_information($dep_id);	

		$str_dep[]= '<span class="label bg-teal-400 mr-5">'.$de_name[0]->department_name.'</span>';
		}
		$str_dep=implode('',$str_dep);
		}

		$data[] = array(
			//'<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-announcement_id="'. $r->announcement_id . '"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-announcement_id="'. $r->announcement_id . '"><i class="fa fa-eye"></i></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->announcement_id . '"><i class="fa fa-trash-o"></i></button></span>',
			$r->title,
			$r->summary,
			$str_dep,
			$sdate,
			$edate,
			$full_name,
			'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right"><li><a href="#" data-toggle="modal" data-target=".edit-modal-data"  data-announcement_id="'. $r->announcement_id . '"><i class="icon-pencil7"></i> Edit</a></li><li><a href="#" data-toggle="modal" data-target=".view-modal-data" data-announcement_id="'. $r->announcement_id . '"><i class="icon-eye4"></i> View</a></li><li><a class="delete" href="#" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->announcement_id . '"><i class="icon-trash"></i> Delete</a></li></ul></li></ul>',
		);
      }
	  $output = array(
		        "draw" => $draw,
			    "recordsTotal" => $announcement->num_rows(),
			    "recordsFiltered" => $announcement->num_rows(),
			    "data" => $data
		);
	  $this->output($output);
    }
	 
	public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('announcement_id');
		$result = $this->Announcement_model->read_announcement_information($id);
		$data = array(
				'announcement_id' => $result[0]->announcement_id,
				'title' => $result[0]->title,
				'start_date' => format_date('d F Y',$result[0]->start_date),
				'end_date' => format_date('d F Y',$result[0]->end_date),
				'company_id' => $result[0]->company_id,
				'location_id' => $result[0]->location_id,
				'department_id' => $result[0]->department_id,
				'published_by' => $result[0]->published_by,
				'summary' => $result[0]->summary,
				'description' => $result[0]->description,
				'get_all_companies' => $this->Company_model->get_all_companies(),
				'all_office_locations' => $this->Location_model->all_office_locations(),
				'all_departments' => $this->Department_model->all_departments()
        );
		if(!empty($this->userSession)){
			$this->load->view('announcement/dialog_announcement', $data);
		} else {
			redirect('');
		}
	}

	public function add_announcement() {
		if($this->input->post('add_type')=='announcement') {
            $Return = array('result'=>'', 'error'=>'');
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $description = $this->input->post('description');
            $st_date = strtotime($start_date);
            $ed_date = strtotime($end_date);
            $qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);

            if($this->input->post('title')==='') {
                $Return['error'] = $this->lang->line('xin_error_title');
            } else if($this->input->post('start_date')==='') {
                $Return['error'] = $this->lang->line('xin_error_start_date');
            } else if($this->input->post('end_date')==='') {
                $Return['error'] = $this->lang->line('xin_error_end_date');
            } else if($st_date > $ed_date) {
                $Return['error'] = $this->lang->line('xin_error_start_end_date');
            } else if($this->input->post('company_id')==='') {
                $Return['error'] = $this->lang->line('error_company_field');
            } else if($this->input->post('location_id')==='') {
                $Return['error'] = $this->lang->line('error_location_dept_field');
            } else if(empty($this->input->post('department_id'))) {
                $Return['error'] = $this->lang->line('error_department_field');
            } else if($this->input->post('summary')==='') {
                $Return['error'] = $this->lang->line('xin_error_summary_field');
            }

            if($Return['error']!=''){
                $this->output($Return);
            }

            $data = array(
                'title' => $this->input->post('title'),
                'start_date' => format_date('Y-m-d',$this->input->post('start_date')),
                'end_date' => format_date('Y-m-d',$this->input->post('end_date')),
                'company_id' => $this->input->post('company_id'),
                'location_id' => $this->input->post('location_id'),
                'department_id' => implode(',',$this->input->post('department_id')),
                'description' => $qt_description,
                'summary' => $this->input->post('summary'),
                'published_by' => $this->input->post('user_id'),
                'created_at' => date('d-m-Y'),
            );
            $result = $this->Announcement_model->add($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_add_announcement');
                $setting = $this->Xin_model->read_setting_info(1);
                /*if($setting[0]->enable_email_notification == 'yes') {
                    // load email library
                    $this->load->library('email');
                    $this->email->set_mailtype("html");
                    $to_email = array();
                    foreach($this->input->post('department_id') as $department_id) {

                        $user_info = $this->Xin_model->read_user_info_bydepartment($department_id);
                            //get company info
                        $cinfo = $this->Xin_model->read_company_setting_info(1);
                        //get email template
                        $template = $this->Xin_model->read_email_template(4);

                        $subject = $template[0]->subject.' - '.$cinfo[0]->company_name;
                        $logo = base_url().'uploads/logo/'.$cinfo[0]->logo;

                        $message = '
                <div style="background:#f6f6f6;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;padding: 20px;">
                <img src="'.$logo.'" title="'.$cinfo[0]->company_name.'"><br>'.str_replace(array("{var site_name}","{var site_url}","{var name}"),array($cinfo[0]->company_name,site_url(),'User'),htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';

                        $this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
                        $this->email->to($user_info[0]->email);//

                        $this->email->subject($subject);
                        $this->email->message($message);
                        $this->email->send();
                    }
                }*/
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
		}
	}
	
	public function attachment_upload(){
            if(empty($_FILES['file']))
            {
                exit();
            }
            $errorImgFile = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].base_url()."uploads/summernote/img_upload_error.jpg";
            $file_name=strtotime(date('Y-m-d H:i:s')).$_FILES['file']['name'];
            $destinationFilePath = $_SERVER['DOCUMENT_ROOT'].base_url().'uploads/summernote/'.$file_name;
            if(!move_uploaded_file($_FILES['file']['tmp_name'], $destinationFilePath)){
                echo $errorImgFile;
            }
            else{
                echo $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].base_url().'uploads/summernote/'.$file_name;
            }
	}

	public function update() {
		if($this->input->post('edit_type')=='announcement') {
		$id = $this->uri->segment(3);
		$Return = array('result'=>'', 'error'=>'');

		$start_date = $this->input->post('start_date_modal');
		$end_date = $this->input->post('end_date_modal');
		$description = $this->input->post('description');
		$st_date = strtotime($start_date);
		$ed_date = strtotime($end_date);
		$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
		
		if($this->input->post('title')==='') {
       		$Return['error'] = $this->lang->line('xin_error_title');
		} else if($this->input->post('start_date')==='') {
			$Return['error'] = $this->lang->line('xin_error_start_date');
		} else if($this->input->post('end_date')==='') {
			$Return['error'] = $this->lang->line('xin_error_end_date');
		} else if($st_date > $ed_date) {
			$Return['error'] = $this->lang->line('xin_error_start_end_date');
		} else if($this->input->post('company_id')==='') {
       		$Return['error'] = $this->lang->line('error_company_field');
		} else if($this->input->post('location_id')==='') {
       		$Return['error'] = $this->lang->line('error_location_dept_field');
		} else if(empty($this->input->post('department_id'))) {
       		$Return['error'] = $this->lang->line('error_department_field');
		} else if($this->input->post('summary')==='') {
       		$Return['error'] = $this->lang->line('xin_error_summary_field');
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
				
	
		$data = array(
            'title' => $this->input->post('title'),
            'start_date' => format_date('Y-m-d',$this->input->post('start_date_modal')),
            'end_date' => format_date('Y-m-d',$this->input->post('end_date_modal')),
            'company_id' => $this->input->post('company_id'),
            'location_id' => $this->input->post('location_id'),
            'department_id' =>  implode(',',$this->input->post('department_id')),
            'description' => $qt_description,
            'summary' => $this->input->post('summary')
		);
		$result = $this->Announcement_model->update_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = $this->lang->line('xin_success_update_announcement');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;
		}
	}
	
	public function delete() {
		$Return = array('result'=>'', 'error'=>'');
		$id = $this->uri->segment(3);
		$this->Announcement_model->delete_record($id);
		if(isset($id)) {
			$Return['result'] = $this->lang->line('xin_success_delete_announcement');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
	}

}
