<?php
/**
 * @Author Siddiqkhan
 *
 * @Awards Controller
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Awards extends MY_Controller {

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
		$this->load->model("Awards_model");
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
		$data['all_employees'] = $this->Xin_model->all_employees();
		$data['all_award_types'] = $this->Awards_model->all_award_types();
		$data['breadcrumbs'] = 'Awards';
		$data['path_url'] = 'awards';
		if(in_array('15',role_resource_ids())) {
			if(!empty($this->userSession)){
			$data['subview'] = $this->load->view("awards/award_list", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}		  
    }
 
    public function award_list()
    {
		$data['title'] = $this->Xin_model->site_title();
		if(!empty($this->userSession)){
			$this->load->view("awards/award_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$award = $this->Awards_model->get_awards();
		$data = array();
		foreach($award->result() as $r) {
            // get user > added by
            $user = $this->Xin_model->read_user_info($r->employee_id);
            // user full name
            $full_name = $user[0]->first_name.' '.$user[0]->last_name;
            // get award type
            $award_type = $this->Awards_model->read_award_type_information($r->award_type_id);

            $d = explode('-',$r->award_month_year);
            $get_month = date('F', mktime(0, 0, 0, $d[1], 10));
            $award_date = $get_month.', '.$d[0];
            // get currency
            $currency = $this->Xin_model->currency_sign($r->cash_price);

            $data[] = array(
                $user[0]->employee_id,
                $full_name,
                $award_type[0]->award_type,
                $r->gift_item,
                $currency,
                date('Y F',strtotime($r->award_month_year)),
                '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">
                <li><a data-toggle="modal" data-target=".edit-modal-data"  data-award_id="'. $r->award_id . '"><i class="icon-pencil4"></i> Edit</a></li>
                <li><a data-toggle="modal" data-target=".view-modal-data"  data-award_id="'. $r->award_id . '"><i class="icon-eye4"></i> View</a></li>
                <li><a class="delete" href="#"  data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->award_id . '"><i class="icon-trash"></i> Delete</a></li>
                </ul></li></ul>',
            );
        }

	    $output = array(
		        "draw" => $draw,
			    "recordsTotal" => $award->num_rows(),
			    "recordsFiltered" => $award->num_rows(),
			    "data" => $data
		);
	    $this->output($output);
    }
	 
	public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('award_id');
		$result = $this->Awards_model->read_award_information($id);
		$data = array(
				'award_id' => $result[0]->award_id,
				'employee_id' => $result[0]->employee_id,
				'award_type_id' => $result[0]->award_type_id,
				'gift_item' => $result[0]->gift_item,
				'award_photo' => $result[0]->award_photo,
				'cash_price' => $result[0]->cash_price,
				'award_month_year' => $result[0]->award_month_year,
				'award_information' => $result[0]->award_information,
				'description' => $result[0]->description,
				'created_at' => format_date('d F Y',$result[0]->created_at),
				'all_employees' => $this->Xin_model->all_employees(),
				'all_award_types' => $this->Awards_model->all_award_types()
        );
		if(!empty($this->userSession)){
			$this->load->view('awards/dialog_award', $data);
		} else {
			redirect('');
		}
	}

	public function add_award() {
		if($this->input->post('add_type')=='award') {
            $Return = array('result'=>'', 'error'=>'');
            /* Server side PHP input validation */
            $description = $this->input->post('description');
            $qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);

            if($this->input->post('employee_id')==='') {
            $Return['error'] = "The employee field is required.";
            } else if($this->input->post('award_type_id')==='') {
                $Return['error'] = "The award type field is required.";
            } else if($this->input->post('award_date')==='') {
                $Return['error'] = "The award date field is required.";
            } else if($this->input->post('month_year')==='') {
                $Return['error'] = "The award month & year field is required.";
            }  else if($_FILES['award_picture']['size'] == 0) {
                $Return['error'] = "Select award photo.";
            }
            else {
                if(is_uploaded_file($_FILES['award_picture']['tmp_name'])) {
                //checking image type
                $allowed =  array('png','jpg','jpeg','pdf','gif');
                $filename = $_FILES['award_picture']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);

                if(in_array($ext,$allowed)){
                    $tmp_name = $_FILES["award_picture"]["tmp_name"];
                    $profile = "uploads/award/";
                    $set_img = base_url()."uploads/award/";
                    // basename() may prevent filesystem traversal attacks;
                    // further validation/sanitation of the filename may be appropriate
                    $name = basename($_FILES["award_picture"]["name"]);
                    $newfilename = 'award_'.round(microtime(true)).'.'.$ext;
                    move_uploaded_file($tmp_name, $profile.$newfilename);
                    $fname = $newfilename;

                    $data = array(
                        'employee_id' => $this->input->post('employee_id'),
                        'award_type_id' => $this->input->post('award_type_id'),
                        'created_at' => format_date('Y-m-d',$this->input->post('award_date')),
                        'award_photo' => $fname,
                        'award_month_year' => $this->input->post('month_year'),
                        'gift_item' => $this->input->post('gift'),
                        'cash_price' => $this->input->post('cash'),
                        'description' => $qt_description,
                        'award_information' => $this->input->post('award_information'),
                    );
                    $result = $this->Awards_model->add($data);
                    userlogs('Employees','Award Added For Employee',$this->input->post('employee_id'));
                    if ($result == TRUE) {
                    $Return['result'] = 'Award added.';

                    //get setting info
                    /*$setting = $this->Xin_model->read_setting_info(1);
                    if($setting[0]->enable_email_notification == 'yes') {

                    $this->load->library('email');
                    $this->email->set_mailtype("html");
                    //get company info
                    $cinfo = $this->Xin_model->read_company_setting_info(1);
                    //get email template
                    $template = $this->Xin_model->read_email_template(10);
                    //get employee info
                    $user_info = $this->Xin_model->read_user_info($this->input->post('employee_id'));

                    $full_name = $user_info[0]->first_name.' '.$user_info[0]->last_name;
                    // get award type
                    $award_type = $this->Awards_model->read_award_type_information($this->input->post('award_type_id'));

                    $subject = $template[0]->subject.' - '.$cinfo[0]->company_name;
                    $logo = base_url().'uploads/logo/'.$cinfo[0]->logo;

                    $d = explode('-',$this->input->post('month_year'));
                    $get_month = date('F', mktime(0, 0, 0, $d[1], 10));
                    $award_date = $get_month.', '.$d[0];

                    $message = '
                <div style="background:#f6f6f6;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;padding: 20px;">
                <img src="'.$logo.'" title="'.$cinfo[0]->company_name.'"><br>'.str_replace(array("{var site_name}","{var site_url}","{var employee_name}","{var award_name}","{var award_month}"),array($cinfo[0]->company_name,site_url(),$full_name,$award_type[0]->award_type,$award_date),htmlspecialchars_decode(stripslashes($template[0]->message))).'</div>';

                    $this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
                    $this->email->to($user_info[0]->email);

                    $this->email->subject($subject);
                    $this->email->message($message);

                    $this->email->send();
                        }*/
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

	public function update() {
		if($this->input->post('edit_type')=='award') {
		$id = $this->uri->segment(3);
		$Return = array('result'=>'', 'error'=>'');
		/* Server side PHP input validation */
		$description = $this->input->post('description');
		$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
		if($this->input->post('employee_id')==='') {
        $Return['error'] = "The employee field is required.";
		} else if($this->input->post('award_type_id')==='') {
			$Return['error'] = "The award type field is required.";
		} else if($this->input->post('award_date')==='') {
			$Return['error'] = "The award date field is required.";
		} else if($this->input->post('month_year')==='') {
			$Return['error'] = "The award month & year field is required.";
		}
		else if($_FILES['award_picture']['size'] == 0) {
			 $fname = [];
			 $data = array(
			'employee_id' => $this->input->post('employee_id'),
			'award_type_id' => $this->input->post('award_type_id'),
			'created_at' => format_date('Y-m-d',$this->input->post('award_date')),
			'award_month_year' => $this->input->post('month_year'),
			'gift_item' => $this->input->post('gift'),
			'cash_price' => $this->input->post('cash'),
			'description' => $qt_description,
			'award_information' => $this->input->post('award_information'),		
			);
			 $result = $this->Awards_model->update_record($data,$id);
		}
		else {
			if(is_uploaded_file($_FILES['award_picture']['tmp_name'])) {
				//checking image type
				$allowed =  array('png','jpg','jpeg','gif');
				$filename = $_FILES['award_picture']['name'];
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				
				if(in_array($ext,$allowed)){
					$tmp_name = $_FILES["award_picture"]["tmp_name"];
					$bill_copy = "uploads/award/";
					// basename() may prevent filesystem traversal attacks;
					// further validation/sanitation of the filename may be appropriate
					$lname = basename($_FILES["award_picture"]["name"]);
					$newfilename = 'award_'.round(microtime(true)).'.'.$ext;
					move_uploaded_file($tmp_name, $bill_copy.$newfilename);
					$fname = $newfilename;
					 $data = array(
					'employee_id' => $this->input->post('employee_id'),
					'award_type_id' => $this->input->post('award_type_id'),
					'created_at' => $this->input->post('award_date'),
					'award_photo' => $fname,
					'award_month_year' => $this->input->post('month_year'),
					'gift_item' => $this->input->post('gift'),
					'cash_price' => $this->input->post('cash'),
					'description' => $qt_description,
					'award_information' => $this->input->post('award_information'),		
					);
					// update record > model
					$result = $this->Awards_model->update_record($data,$id);
				} else {
					$Return['error'] = $this->lang->line('xin_error_attatchment_type');
				}
			}
		}
		
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		userlogs('Employees','Award Updated For Employee',$this->input->post('employee_id'));
		if ($result == TRUE) {
			$Return['result'] = 'Award updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	public function delete() {
		$Return = array('result'=>'', 'error'=>'');
		$id = $this->uri->segment(3);
		$this->Awards_model->delete_record($id);
		if(isset($id)) {
			$Return['result'] = 'Award deleted.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
	}

}
