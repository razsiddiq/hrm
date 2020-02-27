<style type="text/css">

.radio-item {
  display: inline-block;
  position: relative;
  padding: 0 6px;
  margin: 10px 0 0;
}

.radio-item input[type='radio'] {
  display: none;
}

.radio-item label {
  color: #666;
  font-weight: normal;
}

.radio-item label:before {
  content: " ";
  display: inline-block;
  position: relative;
  top: 5px;
  margin: 0 5px 0 0;
  width: 20px;
  height: 20px;
  border-radius: 11px;
  background-color: transparent;
}

.radio-item-green label:before { border: 2px solid green !important;}
.radio-item-red label:before { border: 2px solid red !important; }

.radio-item input[type=radio]:checked + label:after {
  border-radius: 11px;
  width: 12px;
  height: 12px;
  position: absolute;
  top: 9px;
  left: 10px;
  content: " ";
  display: block;
  background: green;
}

.rej input[type=radio]:checked + label:after {
  background: red !important;
}

canvas {
	display: block;
	position: relative;
	background: #7b7b7b0d;
}

</style>
<?php
$session 		= $this->session->userdata('username');
$company 		= $this->Xin_model->read_company_setting_info(1);
$user 			= $this->Xin_model->read_user_info($session['user_id']);
$system 		= $this->Xin_model->read_setting_info(1);
$user_contacts 	= $this->Xin_model->read_user_contacts_info($session['user_id']);
?>
<form class="delete_record" name="delete_record" role="form" action="" method="post">
	<input name="_method" type="hidden" value="DELETE">
	<input name="_token" type="hidden" value="">
	<input name="token_type" id="token_type" type="hidden" value="">
</form>
<div class="modal fade down-modal-data animated <?php echo $system[0]->animation_effect_modal;?>" id="edit-modal-data" role="dialog" aria-labelledby="edit-modal-data" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" id="ajax_modal_down"></div>
	</div>
</div>
<div class="modal fade edit-modal-data-payrol animated <?php echo $system[0]->animation_effect_modal;?>" id="edit-modal-data-payrol" role="dialog" aria-labelledby="edit-modal-data-payrol" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" id="ajax_modal_payview"></div>
	</div>
</div>
<div class="modal fade edit-modal-data animated <?php echo $system[0]->animation_effect_modal;?>" id="edit-modal-data" role="dialog" aria-labelledby="edit-modal-data" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" id="ajax_modal"></div>
	</div>
</div>
<div class="modal fade edit-modal-data-md animated <?php echo $system[0]->animation_effect_modal;?>" id="edit-modal-data-md" role="dialog" aria-labelledby="edit-modal-data" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content" id="ajax_modal-md"></div>
	</div>
</div>
<div class="modal fade view-modal-data animated <?php echo $system[0]->animation_effect_modal;?>" id="view-modal-data"  role="dialog" aria-labelledby="view-modal-data" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" id="ajax_modal_view"></div>
	</div>
</div>
<div class="modal fade view-modal-data-md animated <?php echo $system[0]->animation_effect_modal;?>" id="view-modal-data-md"  role="dialog" aria-labelledby="view-modal-data" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content" id="ajax_modal_view-md"></div>
	</div>
</div>
<div class="modal fade payroll_template_modal default-modal animated <?php echo $system[0]->animation_effect_modal;?>" id="payroll_template_modal"  role="dialog" aria-labelledby="detail-modal-data" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content" id="ajax_modal_payroll"></div>
	</div>
</div>
<div class="modal fade hourlywages_template_modal default-modal animated <?php echo $system[0]->animation_effect_modal;?>" id="hourlywages_template_modal"  role="dialog" aria-labelledby="detail-modal-data" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content" id="ajax_modal_hourlywages"></div>
	</div>
</div>
<div class="modal fade detail_modal_data default-modal animated <?php echo $system[0]->animation_effect_modal;?>" id="detail_modal_data"  role="dialog" aria-labelledby="detail-modal-data" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content" id="ajax_modal_details"></div>
	</div>
</div>
<div class="modal fade emo_monthly_pay animated <?php echo $system[0]->animation_effect_modal;?>" id="emo_monthly_pay"  role="dialog" aria-labelledby="emo_monthly_pay" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" id="emo_monthly_pay_aj"></div>
	</div>
</div>
<div class="modal fade emo_hourly_pay animated <?php echo $system[0]->animation_effect_modal;?>" id="emo_hourly_pay"  role="dialog" aria-labelledby="emo_hourly_pay" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content" id="emo_hourly_pay_aj"></div>
	</div>
</div>
<div class="modal fade small-view-modal default-modal animated <?php echo $system[0]->animation_effect_modal;?>" id="small-view-modal"  role="dialog" aria-labelledby="detail-modal-data" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content" id="ajax_small_modal"></div>
	</div>
</div>
<div class="modal fade hourlywages_template_modal default-modal animated <?php echo $system[0]->animation_effect_modal;?>" id="hourlywages_template_modal"  role="dialog" aria-labelledby="detail-modal-data" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content" id="ajax_modal_hourlywages"></div>
	</div>
</div>
<div class="modal fade add-modal-data animated <?php echo $system[0]->animation_effect_modal;?>" id="add-modal-data"  role="dialog" aria-labelledby="add-modal-data" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content" id="add_ajax_modal"></div>
	</div>
</div>

<div class="modal fade edit-modal-data-1 animated <?php echo $system[0]->animation_effect_modal;?>" id="edit-modal-data-1" role="dialog" aria-labelledby="edit-modal-data-1" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" id="ajax_modal-1"></div>
	</div>
</div>


<form id="delete_record_f" name="delete_record_f" role="form" action="" method="post">
	<input name="_method" type="hidden" value="DELETE">
	<input name="_token_del_file" type="hidden" value="">
</form>
<div class="modal fade default-modal animated <?php echo $system[0]->animation_effect_modal;?>" id="personal_info_modal"  role="dialog" aria-labelledby="detail-modal-data" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="edit-modal-data">Personnel Update Form</h4>
			</div>
			<form id="update_personal_form" action="<?php echo site_url("employees/update_personal_form") ?>" name="update_personal_form" method="post">
			<input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
			<div class="modal-body">
				<div class="row">
					<div  class="col-md-6">
						<div class="form-group">
							<label for="address">Present Address (In UAE)</label>
							<input class="form-control" value="<?php echo $user[0]->address;?>" placeholder="Building Name, Flat No" name="address" type="text">
							<br>
							<div class="row">
								<div class="col-xs-6">
									<input class="form-control" placeholder="<?php echo $this->lang->line('xin_city');?>" value="<?php echo $user[0]->city;?>"  name="p_city" type="text">
								</div>
								<div class="col-xs-6">
									<input class="form-control" placeholder="<?php echo $this->lang->line('xin_state');?>" value="<?php echo $user[0]->area;?>" name="p_area" type="area">
								</div>
							</div>
							<br>
							<select class="form-control" name="home_country" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_country');?>">
								<option value="">Select One</option>
								<?php foreach($all_countries as $scountry) {?>
									<option <?php if($user[0]->home_country == $scountry->country_id) { ?> selected <?php } ?> value="<?php echo $scountry->country_id;?>"> <?php echo $scountry->country_name;?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div  class="col-md-6">
						<div class="form-group">
							<label for="address">Permanent Address (Home Country)</label>
							<input class="form-control" placeholder="Building Name, Flat No" name="residing_address1" value="<?php echo $user[0]->residing_address1;?>" type="text">
							<br>
							<div class="row">
								<div class="col-xs-6">
									<input class="form-control" placeholder="<?php echo $this->lang->line('xin_city');?>" name="residing_city" value="<?php echo $user[0]->residing_city;?>" type="text">
								</div>
								<div class="col-xs-6">
									<input class="form-control" placeholder="<?php echo $this->lang->line('xin_state');?>" name="residing_area"  value="<?php echo $user[0]->residing_area;?>" type="text">
								</div>
							</div>
							<br>
							<select class="form-control" name="residing_country" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_country');?>">
								<option value="">Select One</option>
								<?php foreach($all_countries as $scountry) {?>
									<option <?php if($user[0]->residing_country == $scountry->country_id) { ?> selected <?php } ?> value="<?php echo $scountry->country_id;?>"> <?php echo $scountry->country_name;?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>
				<hr/>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="marital_status" class="control-label"><?php echo $this->lang->line('xin_employee_mstatus');?></label>
							<select class="form-control" name="marital_status" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee_mstatus');?>">
								<option value="Single"  <?php if($user[0]->marital_status=='Single'):?>  selected <?php endif; ?>>Single</option>
								<option value="Married" <?php if($user[0]->marital_status=='Married'):?> selected <?php endif; ?>>Married</option>
								<option value="Widowed" <?php if($user[0]->marital_status=='Widowed'):?> selected <?php endif; ?>>Widowed</option>
								<option value="Divorced or Separated" <?php if($user[0]->marital_status=='Divorced or Separated'):?> selected <?php endif; ?>>Divorced or Separated</option>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="email" class="control-label">Spouse Name (If Married) <?php echo REQUIRED_FIELD;?></label>
							<input class="form-control" placeholder="Spouse Name" name="spouse_name" type="text" value="<?php echo $user[0]->spouse_name;?>" >
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<?php $contact_no=@explode('-',@$user[0]->contact_no);?>
						<div class="form-group">
							<label for="email" class="control-label">Personal Number  <?php echo REQUIRED_FIELD;?></label>
							<div class="input-group">
	  							 <span class="input-group-addon-custom">
									  <select class="form-control change_country_code js-example-templating_diag" name="p_country_code">
									  <?php foreach(phone_numbers_code() as $keys=>$phone_code){?>
										  <option <?php if($keys==@$contact_no[0]){echo 'selected';}?> value="<?php echo $keys; ?>-" data-len ="<?php echo $phone_code['length'];?>"   rel="<?php echo $phone_code['country_name'];?>"><?php echo $keys; ?></option>
									  <?php } ?>
									  </select>
			 					 </span>
								<input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_mobile');?>" name="p_mobile_phone" pattern="\d*" maxlength="<?php echo MAX_PHONE_DIGITS;?>"  type="text" value="<?php echo @$contact_no[1];?>">
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="email" class="control-label">Personal Email  <?php echo REQUIRED_FIELD;?></label>
							<input class="form-control" placeholder="Personal Email" name="personal_email" type="email" value="<?php echo $user[0]->personal_email;?>" >
						</div>
					</div>
				</div>
				<hr/>
				<div class="row">
					<div>
						<label for="name" class="control-label ml-10">Person to be contacted in case of emergency  <?php echo REQUIRED_FIELD;?></label>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<input type="hidden" name="user_contact_id" value="<?=($user_contacts[0]['contact_id']) ? $user_contacts[0]['contact_id'] : '';?>"/>
							<input class="form-control" placeholder="<?php echo $this->lang->line('xin_name');?>" name="contact_name" type="text"  value="<?=($user_contacts[0]['contact_name']) ? $user_contacts[0]['contact_name'] : '';?>">
						</div>
						<div class="form-group">
							<select class="form-control" name="relation" data-plugin="select_hrm" data-placeholder="Relation">
								<option value=""></option>
								<option value="Parent" 	<?php if($user_contacts[0]['relation']=='Parent'){?> selected="selected" <?php }?>>Parent</option>
								<option value="Spouse" 	<?php if($user_contacts[0]['relation']=='Spouse'){?> selected="selected" <?php }?>>Spouse</option>
								<option value="Child"	<?php if($user_contacts[0]['relation']=='Child'){?> selected="selected" <?php }?>>Child</option>
								<option value="Sibling" <?php if($user_contacts[0]['relation']=='Sibling'){?> selected="selected" <?php }?>>Sibling</option>
								<option value="In Laws" <?php if($user_contacts[0]['relation']=='In Laws'){?> selected="selected" <?php }?>>In Laws</option>
								<option value="Friend" 	<?php if($user_contacts[0]['relation']=='Friend'){?> selected="selected" <?php }?>>Friend</option>
								<option value="Self" 	<?php if($user_contacts[0]['relation']=='Self'){?> selected="selected" <?php }?>>Self</option>
							</select>
						</div>
						<div class="form-group">
							<div class="input-group">
								<?php $user_contacts_phone = ($user_contacts[0]['mobile_phone']) ? explode('-',$user_contacts[0]['mobile_phone']) : []; ?>
	  							 <span class="input-group-addon-custom">
									  <select class="form-control change_country_code js-example-templating_diag" name="contact_country_code">
									  <?php foreach(phone_numbers_code() as $keys=>$phone_code){?>
										  <option <?php if($keys==@$user_contacts_phone[0]){echo 'selected';}?> value="<?php echo $keys; ?>-" data-len ="<?php echo $phone_code['length'];?>"   rel="<?php echo $phone_code['country_name'];?>"><?php echo $keys; ?></option>
									  <?php } ?>
									  </select>
			 					 </span>
								<input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_mobile');?>" name="contact_mobile_phone"  pattern="\d*" maxlength="<?php echo MAX_PHONE_DIGITS;?>" type="text"  value="<?=($user_contacts_phone[1]) ? $user_contacts_phone[1] : '' ;?>">
							</div>
						</div>

					</div>
					<div class="col-md-6">
						<div class="form-group">
							<input class="form-control" placeholder="Building Name, Flat No" name="address_1" type="text" value="<?=($user_contacts[0]['address_1']) ? $user_contacts[0]['address_1'] : '';?>">
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-xs-6">
									<input class="form-control" placeholder="<?php echo $this->lang->line('xin_city');?>" name="contact_city" type="text" value="<?=($user_contacts[0]['city']) ? $user_contacts[0]['city'] : '';?>">
								</div>
								<div class="col-xs-6">
									<input class="form-control" placeholder="<?php echo $this->lang->line('xin_state');?>" name="contact_state" type="text" value="<?=($user_contacts[0]['state']) ? $user_contacts[0]['state'] : '';?>">
								</div>
							</div>
						</div>
						<div class="form-group">
							<select name="contact_country" data-plugin="select_hrm" class="form-control" data-placeholder="<?php echo $this->lang->line('xin_country');?>">
								<option value=""></option>
								<?php foreach($all_countries as $country) {?>
									<option value="<?php echo $country->country_id;?>" <?php if($country->country_id == $user_contacts[0]['country']){?> selected <?php } ?> ><?php echo $country->country_name;?></option>
								<?php } ?>
							</select>
						</div>

					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn bg-teal-400 update_contacts"><?php echo $this->lang->line('xin_update');?></button>
			</div>
		</form>
		
		<?php
		$filled_personal_details = 0;
		if($user[0]->address == '' || $user[0]->city == '' || $user[0]->home_country == '' || 
			$user[0]->residing_address1 == '' || $user[0]->residing_city == '' || $user[0]->residing_country == '' 
			|| ($user[0]->marital_status == 'Married' && $user[0]->spouse_name == '') || 
			$contact_no[1] == '' || $user[0]->personal_email == '' || $user_contacts[0]['contact_name'] ==  '' || 
			$user_contacts[0]['relation'] == '' || $user_contacts_phone[1] == '' || $user_contacts[0]['address_1'] == '' || 
			$user_contacts[0]['city'] ==  '' || $user_contacts[0]['country'] ==  '' ){
				$filled_personal_details = 1; 
		}		
		?>

		</div>
	</div>
</div>

<div class="modal fade default-modal animated <?php echo $system[0]->animation_effect_modal;?>" id="disclosure_agreement_modal"  role="dialog" aria-labelledby="detail-modal-data" aria-hidden="true">
	<div class="modal-dialog  modal-lg">
		<div class="modal-content" id="converting_div">
			<div class="modal-header">
				<button type="button" class="close" id="close_disclosure" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
				<h4 class="modal-title" id="edit-modal-data">CONFIDENTIALITY AGREEMENT</h4>
			</div>
			<form id="update_non_disclosure_form" action="<?php echo site_url("employees/update_non_disclosure_approval") ?>" name="update_personal_form" method="post">

			<input type="hidden" id="theimage" value="<?php echo base_url()?>uploads/document/immigration">
			<div class="modal-body">

				<?php 
					$this->load->model("Employees_model");
					$pendingNonDisclosureApproval = $this->Employees_model->getPendingNonDisclosureApproval($session['user_id'],'employee_show');

					// pr($pendingNonDisclosureApproval);die;

					if(!empty($pendingNonDisclosureApproval)){

						$show_disclosure_agreement_modal = count($pendingNonDisclosureApproval);
					}else{
						$show_disclosure_agreement_modal = 0;
					}
				?>

				<input type="hidden" id="show_disclosure_agreement_modal" value="<?php echo $show_disclosure_agreement_modal;?>">
				<?php 
				for ($i=0; $i < count($pendingNonDisclosureApproval); $i++) { 

				$employee_id = $pendingNonDisclosureApproval[$i]['employee_id'];

				$employee_full_data = $this->Employees_model->employee_full_data_approval($employee_id);

				$visa_details = $this->Employees_model->set_employee_immigration($employee_id,3);
				$visa_details = $visa_details->result_array();

				$passport_details = $this->Employees_model->set_employee_immigration($employee_id,2);
				$passport_details = $passport_details->result_array();

				// pr($employee_full_data);
				// pr($passport_details);
				// pr($visa_details);die;
				?>	

					<input type="hidden" name="approval_id[]" value="<?php echo $pendingNonDisclosureApproval[$i]['approval_id'];?>">
					<div class="row">
						<div  class="col-md-12">
							<div class="form-group" style="text-align: justify;">
								<p>
									On this <b><?php echo date('d-m-Y'); ?></b>, I, the undersigned  <b><?=$employee_full_data[0]->first_name.' '.$employee_full_data[0]->middle_name.' '.$employee_full_data[0]->last_name?></b> of <b><?=$employee_full_data[0]->country_name?></b> nationality, holder of Passport No <b><?=$passport_details[0]['document_number']?></b>, working for “<b><?=$visa_details[0]['document_number']?></b>” in the capacity of <b><?=$employee_full_data[0]->designation_name?></b> as per the Employment Contract signed between the employer and me on <b><?=date('d-m-Y',strtotime($visa_details[0]['issue_date']))?></b> and as far as the work relationship in usually built on mutual trust between the employee and the employer and due to this trust the employee will be aware of most of the secrets and details of the work, he is entitled to do and will be acquainted with most of the customers and employees of the employer. 
								</p>
								<p>
									That, a part of my job assignment is to acquire and get data of salaries of each/some of the employee of the company. 
								</p>
								<p>
									Therefore, as per Article No. 127 of the Federal Labor Law No.8 for the year 1981 and Article No.909 of the Federal Civil Procedures, I, The undersigned, do hereby undertake to:  
								</p>
								<p>
									1) Not in any way nor for whatever means, I will disclose, share any information related to salaries of employees. <br>

									2) In cases of violating this Confidentiality Agreement,the company can impose my immediate termination
									   applying the rule in UAE Labour Law under Article 120 number 6. 
								</p>
								<p>
									This Confidentiality Agreement is made of two copies, a copy is given to both parties, the employee and employer. 
								</p>
								
								<div class="wrapper" style="float:right;">
									<label for="ritemb">Signature : </label>
  									<canvas id="signature-pad" name="signature_pad" width="400" height="200"></canvas>
									  <div>
									    <a class="btn btn-default" style="float:right;" id="clear">Clear</a>
									    <button class="hide" id="save">Save</button>
									    <button class="hide" id="showPointsToggle">Show points?</button>
									  </div>
								</div>

							</div>
						</div>
					</div>
					<hr/>

				<?php }?>
				
			</div>
			<div class="modal-footer" id="update_disclosure_footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
				<button type="submit" id="update_disclosure_btn" class="btn bg-teal-400 update_disclosure_approval"><?php echo $this->lang->line('xin_update');?></button>
			</div>
		</form>
		
		</div>
	</div>
</div>

<!-- Vendor JS -->
<?php if((in_array('0d',role_resource_ids())) && ($path_url=='dashboard')) { ?>
	<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/morris/morris.css">
	<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/chartjs/Chart.bundle.min.js"></script>
<?php } ?>
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/jquery/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/tether/js/tether.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/detectmobilebrowser/detectmobilebrowser.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/selects/select2.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/toastr/toastr.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/editors/summernote/summernote.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/pickers/pickadate/picker.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/pickers/pickadate/picker.date.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/bootstrap-datepicker/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/extensions/session_timeout.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/styling/uniform.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/styling/switchery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/styling/switch.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/notifications/sweet_alert.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/notifications/pnotify.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/notifications/bootbox.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/pickers/anytime.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/clockpicker/dist/jquery-clockpicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/kendo/kendo.all.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/uploaders/fileinput.min.js"></script>
<?php //if($path_url=='dashboard'){?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/pickers/color/spectrum.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/ui/moment/moment.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/ui/fullcalendar/fullcalendar.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/pickers/daterangepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/visualization/echarts/echarts.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/media/fancybox.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/zoom/jquery.elevateZoom-3.0.8.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/signature/signature_pad.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
<?php //}?>
<!-- JS -->
<script type="text/javascript">
	var day_n = new Date();
	function sendFile($editor, data) {
		$.ajax({
			url: site_url+'announcement/attachment_upload/',
			method: 'POST',
			data: data,
			processData: false,
			contentType: false,
			success: function(response) {
				console.log(response);
				$editor.summernote('insertImage', response);
			}
		});
	}
	$(document).ready(function(){
		$("#update_personal_form").submit(function(e){
			e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.update_contacts').prop('disabled', true);
			$.ajax({
				type	: "POST",
				url		: e.target.action,
				data	: obj.serialize()+"&form="+action,
				cache	: false,
				success	: function (JSON) {
					if (JSON.error != '') {
						toastr.error(JSON.error);
						$('.update_contacts').prop('disabled', false);
					} else {
						toastr.success(JSON.result);
						$('.update_contacts').prop('disabled', false);
						$('#personal_info_modal').modal('hide');

					}
				}
			});
		});

		toastr.options.closeButton = <?php echo $system[0]->notification_close_btn;?>;
		toastr.options.progressBar = <?php echo $system[0]->notification_bar;?>;
		toastr.options.timeOut = 3000;
		toastr.options.preventDuplicates = true;
		toastr.options.positionClass = "<?php echo $system[0]->notification_position;?>";
		$(".add-new-form").click(function(){
			$(".add-form").slideToggle('slow');
			var btntext=$(this).text();
			var icontext=$(this).find('i').attr('class');
			if(btntext!=''){
				if(btntext=='Add New'){
					$(this).hide();
					$(".add-new-form:first").show();
				}else{
					$(this).hide();
					$(".add-new-form").show();
				}
			}else if(icontext!=''){
				if(icontext=='icon-plus-circle2'){
					$(this).hide();
					$(".add-new-form:first").show();
				}else{
					$(this).hide();
					$(".add-new-form").show();
				}
			}
		});
		$(".add-new-form1").click(function(){
			$(".add-form1").slideToggle('slow');

			var btntext=$(this).text();
			var icontext=$(this).find('i').attr('class');

			if(btntext!=''){
				if(btntext=='Add New'){
					$(this).hide();
					$(".add-new-form1:first").show();
				}else{
					$(this).hide();
					$(".add-new-form1").show();
				}
			}else if(icontext!=''){
				if(icontext=='icon-plus-circle2'){
					$(this).hide();
					$(".add-new-form1:first").show();
				}else{

					$(this).hide();
					$(".add-new-form1").show();
				}
			}
		});
		$('.form_date').datetimepicker({
			weekStart: 1,
			todayBtn:  1,
			autoclose: 1,
			todayHighlight: 1,
			startView: 2,
			minView: 2,
			forceParse: 0,
			pickerPosition: "bottom-left"
		});
		
		$('.form_year').datetimepicker({
			weekStart: 1,
			todayBtn:  1,
			autoclose: 1,
			todayHighlight: 1,
			startView: 4,
			minView: 4,
			forceParse: 0,
			pickerPosition: "bottom-left"
		});
		$('.form_month_year').datetimepicker({
			weekStart: 1,
			todayBtn:  1,
			autoclose: 1,
			todayHighlight: 1,
			startView: 3,
			minView: 4,
			forceParse: 0,
			pickerPosition: "bottom-left"
		});
		$(".form_month_year_1").datetimepicker({format: 'yyyy MM', weekStart: 1,
			todayBtn:  1,
			autoclose: 1,
			todayHighlight: 1,
			startView: 3,
			minView: 4,
			forceParse: 0,
			pickerPosition: "bottom-left"});
		$(".js-example-templating").select2({
			templateResult: formatState,
			matcher: matchStart,
			dropdownAutoWidth : true
		});
		$('table').wrap('<div class="table-responsive"></div>');
		$('form').attr('autocomplete', 'off');
	});
	function matchStart(params, data) {
		if ($.trim(params.term) === '') {
			return data;
		}
		var vals=$(data.element).attr("rel").toUpperCase();
		if(vals.includes(params.term.toUpperCase()) == true){
			return data;
		}
		return null;
	}
	function formatState (state) {
		if (!state.id) {
			return $(state.element).val();
		}
		var $state = $(
			'<span>' + $(state.element).attr("rel") + ' (' +  state.element.value.toLowerCase().slice(0, -1) + ')</span>'
		);
		return $state;
	};
</script>
<style type="text/css"></style>
<script type="text/javascript">
	var user_role = '<?php echo $user[0]->user_role_id;?>';
	<?php if(in_array('0d',role_resource_ids())) { ?>
		var user_resource_role = 'YES';
	<?php } else {?>
		var user_resource_role = 'NO';
	<?php } ?>
</script>
<script type="text/javascript">
	var js_date_format = '<?php echo $this->Xin_model->set_date_format_js();?>';
	var site_url = '<?php echo base_url(); ?>';
	var base_url = '<?php echo base_url().$this->router->fetch_class(); ?>';
	var path_url = '<?php echo $path_url; ?>';
</script>
<script type="text/javascript" src="<?php echo base_url().'skin/js_module/'.$path_url.'.js?version='.strtotime(date('Y-m-d H:i:s')); ?>"></script>
<script type="text/javascript" src="<?php echo base_url().'skin/js_module/custom.js?version='.strtotime(date('Y-m-d H:i:s')); ?>"></script>
<?php if($this->router->fetch_method() =='task_details' || $this->router->fetch_class() =='project'){ ?>
	<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/ion.rangeSlider/js/ion-rangeSlider/ion.rangeSlider.min.js"></script>
<?php } ?>
<?php if($this->router->fetch_method() =='task_details' || $this->router->fetch_class() =='project'){ ?>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#range_grid").ionRangeSlider({
				type: "single",
				min: 0,
				max: 100,
				from: '<?php echo $progress;?>',
				grid: true,
				force_edges: true,
				onChange: function (data) {
					$('#progres_val').val(data.from);
				}
			});
		});
	</script>
<?php } ?>
<script>
	<?php
	$this->load->model("Department_model");
	$user = $this->Xin_model->read_user_info($session['user_id']);
	$department = $this->Department_model->read_department_information($user[0]->department_id);
	$department_name=$department[0]->department_name;
	if($session['role_name']==E_M_ROLE && $department_name!='HRD'){?>
	document.addEventListener('contextmenu', function(e) {
		e.preventDefault();
	});
	$(document).keydown(function(e){
		if(e.which === 123){
			return false;
		}
	});

	$(document).ready(function(){
		$.sessionTimeout({
			heading: 'h5',
			title: 'Idle Timeout',
			message: 'Your session is about to expire. Do you want to stay connected?',
			warnAfter: 120000,
			redirAfter: 180000,
			keepAliveUrl: '/',
			keepBtnClass:'btn btn-default',
			redirUrl: site_url+'logout',
			logoutUrl: site_url+'logout'
		});
	});
	<?php } ?>
	$(document).ready(function(){
		$(".js-example-templating_diag").select2({
			templateResult: formatState,
			matcher: matchStart,
			dropdownAutoWidth : true
		});
		$(".change_country_code").change(function () {
			//var rel=<?php echo MAX_PHONE_DIGITS;?>;
			var rel=$(this).find(':selected').data('len');
			$(this).parent().next('input').val('');
			$(this).parent().next('input').attr('maxlength',rel);
		});
		$("ul.nav-tabs-highlight li").click(function () {
			$('div.add-form').hide();
			$(".add-new-form").show();
		});
	});
	<?php if($session['user_id']!=''){?>
	//missed_login_alerts(<?php echo $session['user_id'];?>);
	function missed_login_alerts(user_id){
		$.ajax({
			type: "GET",
			url: site_url+'timesheet/missed_login_alerts/',
			data: "data=missed_login_alerts&user_id="+user_id,
			cache: false,
			success: function (val) {
				if(val==1){
					var notice = new PNotify({
						title: 'Login Alert',
						text: 'You are missed to login today.',
						addclass: 'bg-warning',
						hide: false,
						buttons: {
							sticker: false
						}});
					notice.get().click(function() {
						$.ajax({
							type: "POST",
							url: site_url+'timesheet/update_login_alerts/',
							data: "data=update_login_alerts&user_id="+user_id,
							cache: false,
							success: function (val1) {
							}
						});
					});
				}


			}
		});
	}
	<?php } ?>
	function check_visa_under(user_id){
		$.ajax({
			type: "GET",
			url: site_url+'employees/check_visa_under/',
			data: "user_id="+user_id,
			cache: false,
			success: function (val) {
				if (val == 0) {
					$('.agency_fee').show();
				} else {
					$('.agency_fee').hide();
				}
			}
		});
	}
	<?php
	if(@$_GET['load_detail']!=''){	?>
	$.ajax({
		url : "<?php echo base_url(); ?>/timesheet/read_leave_conversion/",
		type: "GET",
		data: 'jd=1&is_ajax=1&mode=modal&data=leave_conversion&leave_conversion_id=<?php echo @$_GET['load_detail'];?>&hr_access=Yes',
		success: function (response) {
			if(response) {
				$("#ajax_modal_payview").html(response);
			}
		}
	});
	$('.edit-modal-data-payrol').modal('show');
	<?php } ?>
	
	var filled_personal_details = '<?=$filled_personal_details;?>';	
	<?php if($_SESSION['profile_popup'] == ''){ ?>
	if((path_url == 'profile' || path_url == 'user/user_leave' || path_url == 'dashboard') && filled_personal_details == 1 ){
		$('#personal_info_modal').modal({
			backdrop: 'static',
			keyboard: true
			},'show');		
	}
	<?php //$_SESSION['profile_popup'] = '1'; 
	}	?>
</script>

<?php if(in_array('31e',role_resource_ids())) {?>
	<script> var minDate = ''; </script>
<?php }else{?>
	<script> var minDate = moment().subtract(7, 'days').format('DD MMMM YYYY'); </script>
<?php }?>
<script>
var minDateCurr = moment().subtract(0, 'days').format('DD MMMM YYYY');
$(document).ready(function(){

	$('.form_date_five_day').datetimepicker({
		weekStart: 1,
		todayBtn:  1,
		autoclose: 1,
		startDate: minDate,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0,
		pickerPosition: "bottom-left"
	});

	$('.form_date_curr').datetimepicker({
		weekStart: 1,
		todayBtn:  1,
		autoclose: 1,
		startDate: minDateCurr,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0,
		pickerPosition: "bottom-left"
	});

});

</script>

<?php 
$dashboard_indi = $this->uri->segment(2);
if($dashboard_indi == 'leave'){
?>

<script type="text/javascript">
$(document).ready(function(){
	var user_id_agreement = $('#show_disclosure_agreement_modal').val();
	if(user_id_agreement != 0){
		$('#disclosure_agreement_modal').modal({
			backdrop: 'static',
			keyboard: true
		},'show');
	}

	$("#update_non_disclosure_form").submit(function(e){
		e.preventDefault();

		if (signaturePad.isEmpty()) {
			return alert("Please provide a signature first.");
	  	}

	  	$('#clear').hide();
	  	$('#update_disclosure_footer').hide();
	  	$('#close_disclosure').hide();

	 	var data_signature = signaturePad.toDataURL('image/jpeg');
	    // console.log(data_signature);
		var obj = $(this), action = obj.attr('name');
	    html2canvas([document.getElementById('converting_div')], {

		    onrendered: function(canvas) {
		        document.body.appendChild(canvas);
		        var converted_image = canvas.toDataURL('image/png');

				$('.update_disclosure_approval').prop('disabled', true);
				$.ajax({
					type	: "POST",
					url		: e.target.action,
					data	: obj.serialize()+"&form="+action+"&data_signature="+data_signature+"&converted_image="+converted_image,
					cache	: false,
					success	: function (JSON) {

						toastr.success(JSON.result);
						$('.update_disclosure_approval').prop('disabled', true);
						$('#disclosure_agreement_modal').modal('hide');
						canvas.style.display = "none";
					}
				});

		    }
		});

	    
	});

});
</script>

<?php }?>

<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/form_multiselect.js"></script>
