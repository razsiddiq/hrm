<?php
/* Employees view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php
$locations = [];
$countries = [];
$defaultLocations = $this->Xin_model->all_locations();
foreach ($this->Xin_model->getLocationsWithCountry() as $element) {
	$locations[$element->country][] = $element;
	$countries[$element->country] = (object) [
		'country_id'=>$element->country,
		'country_name'=>$element->country_name
	];
}
?>
<script type='text/javascript'>
	<?php
	$js_array = json_encode($locations);
	echo "let locations = ". $js_array . ";\n";
	?>
	<?php
	$js_array = json_encode($defaultLocations);
	echo "let defaultLocations = ". $js_array . ";\n";
	?>
</script>
<style>
	.select2-results__option[aria-selected=true] { display: none;}
</style>
<?php if(in_array('60c-view',role_resource_ids())) {?>
	<form id="list_employees" method="GET">

		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title">

					<strong>Filter By</strong> </strong>

				</h5>
				<div class="heading-elements">
					<div class="add-record-btn">
						<button type="button" onclick="employees_report();" class="btn bg-teal-400">Apply Filter</button>
					</div>
				</div>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">

						<div class="col-md-2">
							<div class="form-group">
								<select name="employee_id" id="employee_id" class="form-control" data-plugin="select_hrm">
									<option value="0">All Employees</option>
									<?php foreach($all_employees as $employee) {
										if(isset($_GET['loc']) && $employee->office_location_id == $_GET['loc']){?>
										<option value="<?php echo $employee->user_id;?>" > <?php echo $employee->first_name.' '.$employee->middle_name.' '.$employee->last_name;?></option>
									<?php }
										if(!isset($_GET['loc'])){?>
											<option value="<?php echo $employee->user_id;?>" > <?php echo $employee->first_name.' '.$employee->middle_name.' '.$employee->last_name;?></option>
										<?php }
									} ?>
								</select>	
								<label class="text-small text-grey mt-5">Employees</label>
							</div>
						</div>

						<div class="col-md-2">
							<div class="form-group">
								<select name="visa_value" class="visa_value" class="form-control" data-plugin="select_hrm" data-placeholder="Choose the Status...">
									<option value="0">All Visas</option>
									<?php foreach(visa_lists() as $vis) {?>
										<option value="<?php echo $vis->type_id;?>"><?php echo $vis->type_name;?></option>
									<?php } ?>
								</select>
								<label class="text-small text-grey mt-5">Visa Type</label>
							</div>
						</div>

						<div class="col-md-2">
							<div class="form-group">
								<select  class="country_value" name="country_value" class="form-control" data-plugin="select_hrm">
									<option value="0">All Regions</option>
									<?php foreach($countries as $item) {?>
										<option value="<?php echo $item->country_id;?>"><?php echo $item->country_name;?></option>
									<?php } ?>
								</select>
								<label class="text-small text-grey mt-5">Region</label>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<select name="location_value" class="location_value" class="form-control" data-plugin="select_hrm">
									<option value="0">All Locations</option>
									<?php foreach($defaultLocations as $locs) {?>
										<option value="<?php echo $locs->location_id;?>" <?php echo ($locs->location_id == 1)? 'selected' : ''?> ><?php echo $locs->location_name;?></option>
									<?php } ?>
								</select>
								<label class="text-small text-grey mt-5">Location</label>
							</div>
						</div>


						<div class="col-md-3">
							<div class="form-group">
								<select name="department_value" class="department_value" class="form-control" data-plugin="select_hrm">
									<option value="0">All Departments</option>
									<?php foreach($this->Xin_model->all_departments_chart() as $dept) {?>
										<option value="<?php echo $dept->department_id;?>"><?php echo $dept->department_name;?></option>
									<?php } ?>
								</select>
								<label class="text-small text-grey mt-5">Department</label>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<select name="role_value" class="role_value" class="form-control" data-plugin="select_hrm">
									<option value="0">All Roles</option>
									<?php foreach($this->Xin_model->all_employee_roles() as $emprole) {?>
										<option value="<?php echo $emprole->user_role_id;?>"><?php echo $emprole->role_name;?></option>
									<?php } ?>
								</select>
								<label class="text-small text-grey mt-5">Role</label>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<select name="reporting_to" class="reporting_to" class="form-control" data-plugin="select_hrm">
									<option value="0">Employees Reporting To</option>
									<?php foreach($this->Xin_model->get_reporting_managers() as $emp) {?>
										<option value="<?php echo $emp->user_id;?>"><?php echo  change_fletter_caps($emp->first_name.' '.$emp->middle_name.' '.$emp->last_name);?></option>
									<?php } ?>
								</select>
								<label class="text-small text-grey mt-5">Reporting Manager</label>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<select name="medical_card_value" class="medical_card_type" class="form-control" data-plugin="select_hrm">
									<option value="0">All Medical Card Plan</option>
									<?php $mecard=$this->Xin_model->get_medical_card_type();foreach($mecard->result() as $medical_card_type) {

										?>
										<option value="<?php echo $medical_card_type->type_id;?>"><?php echo $medical_card_type->type_name;?></option>
									<?php } ?>
								</select>
								<label class="text-small text-grey mt-5">Medical Card Plan</label>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<select name="employee_status" class="employee_status" class="form-control" data-plugin="select_hrm">
									<option value="1">Active</option>
									<option value="0">In Active</option>
								</select>
								<label class="text-small text-grey mt-5">Employee Status</label>
							</div>
						</div>
					</div>

				</div></div>


		</div>
		<?php if(in_array('60c-salary',role_resource_ids()) || in_array('60c-employee',role_resource_ids()) || in_array('60c-document',role_resource_ids())){ ?>
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title">
					<strong>Select Column(s)</strong> </strong>
				</h5>
				<div class="heading-elements">
					<div class="add-record-btn">
						<button type="button" onclick="employees_report();" class="btn bg-teal-400">Apply Filter</button>
					</div>
				</div>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						<?php if(in_array('60c-employee',role_resource_ids())){ ?>
						<div class="col-md-4 no-padding-left">
							<div class="form-group">
								<div class="multi-select-full">
										<select class="multiselect-select-all employees_column" multiple="multiple" name="employees_column[]" id="employees_column" >
										<?php foreach(employees_columns('profile') as $key=>$value) {?>
											<option value="<?php echo $key;?>" <?php echo ($value['selected'])? 'selected' : ''?> ><?php echo $value['text'];?></option>
										<?php } ?>
									</select>
								</div>
								<label class="text-bold mt-5">Employee Information</label>
							</div>
						</div>
						<?php } ?>
						<?php if(in_array('60c-document',role_resource_ids())){ ?>
						<div class="col-md-4 no-padding-left">
							<div class="form-group">
								<div class="multi-select-full">
									<select class="multiselect-select-all employees_column" multiple="multiple" name="employees_column[]" id="employees_column" >
										<?php foreach(employees_columns('documents') as $key=>$value) {?>
											<option value="<?php echo $key;?>" <?php echo ($value['selected'])? 'selected' : ''?> ><?php echo $value['text'];?></option>
										<?php } ?>
									</select>
								</div>
								<label class="text-bold mt-5">Employee Documents</label>
							</div>
						</div>
						<?php } ?>
						<?php if(in_array('60c-salary',role_resource_ids())){ ?>
						<div class="col-md-4 no-padding-left">
							<div class="form-group">
								<div class="multi-select-full">
									<select class="multiselect-select-all employees_column" multiple="multiple" name="employees_column[]" id="employees_column" >
										<?php foreach(employees_columns('salary') as $key=>$value) {?>
											<option value="<?php echo $key;?>" <?php echo ($value['selected'])? 'selected' : ''?> ><?php echo $value['text'];?></option>
										<?php } ?>
									</select>
								</div>
								<label class="text-bold mt-5">Salary Information</label>
							</div>
						</div>
						<?php } ?>
					</div>

				</div>
			</div>

		</div>
		<?php } ?>
	</form>

	<?php if(in_array('60c-view',role_resource_ids())) {?>
		<div class="add-form" style="display:none;">
			<div class="panel panel-flat">
				<div class="panel-heading">
					<h5 class="panel-title">

						<strong><?php echo $this->lang->line('xin_add_new');?></strong> <?php echo $this->lang->line('xin_employee');?>

					</h5>
					<div class="heading-elements">
						<div class="add-record-btn">
							<button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_hide');?></button>
						</div>
					</div>
				</div>



				<div class="row m-b-1">
					<div class="col-md-12">
						<form action="<?php echo site_url("employees/add_employee") ?>" method="post" name="add_employee" id="xin-form">
							<input type="hidden" name="<?= csrf_name;?>" value="<?= csrf_hash;?>" />
							<input type="hidden" name="_user" value="<?php echo $session['user_id'];?>">
							<div class="panel-body">

								<div class="row">
									<div class="col-md-6">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="first_name"><?php echo $this->lang->line('xin_employee_first_name');?><?php echo REQUIRED_FIELD;?>&nbsp;<span data-toggle="tooltip" data-placement="top" title="" data-original-title="As Per In Passport"><i class="fa fa-info-circle"></i></span></label>
													<input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_first_name');?>" name="first_name" type="text" value="">
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label for="last_name" class="control-label"><?php echo $this->lang->line('xin_employee_middle_name');?></label>
													<input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_middle_name');?>" name="middle_name" type="text" value="">
												</div>
											</div>
										</div>

										<div class="row">

											<div class="col-md-6">
												<div class="form-group">
													<label for="last_name" class="control-label"><?php echo $this->lang->line('xin_employee_last_name');?></label>
													<input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_last_name');?>" name="last_name" type="text" value="">
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label for="company"><?php echo $this->lang->line('xin_company_name');?><?php echo REQUIRED_FIELD;?></label>
													<select class="form-control" name="company_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_company_name');?>">
														<!--<option value=""></option>-->
														<?php foreach($all_companies as $all_comp) {?>
															<option value="<?php echo $all_comp->company_id;?>"><?php echo $all_comp->name;?></option>
														<?php } ?>
													</select>
												</div>
											</div>

										</div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="department"><?php echo $this->lang->line('xin_employee_department');?><?php echo REQUIRED_FIELD;?></label>
													<select class="form-control" name="department_id" id="aj_department" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee_department');?>">
														<option value=""></option>
														<?php foreach($all_departments as $department) {?>
															<option value="<?php echo $department->department_id?>"><?php echo $department->department_name?></option>
														<?php } ?>
													</select>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group" id="designation_ajax">
													<label for="designation"><?php echo $this->lang->line('xin_designation');?><?php echo REQUIRED_FIELD;?></label>
													<select class="form-control" name="designation_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_designation');?>">
														<option value=""></option>
														<?php foreach($all_designations as $designation) {?>
															<option value="<?php echo $designation->designation_id?>"><?php echo $designation->designation_name?></option>
														<?php }  ?>
													</select>
												</div>
											</div>
										</div>
										<div class="row">

											<div class="col-md-6">
												<div class="form-group">
													<label for="first_name"><?php echo $this->lang->line('xin_employee_biometric_id');?><?php echo REQUIRED_FIELD;?></label>
													<input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_biometric_id');?>" name="biometric_id" type="text" value="" pattern="\d*" title="<?php echo $this->lang->line('xin_use_numbers');?>">
												</div>
											</div>


											<div class="col-md-6">
												<div class="form-group">
													<label for="email" class="control-label"><?php echo $this->lang->line('dashboard_email');?><?php echo REQUIRED_FIELD;?></label>
													<input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_email');?>" name="email" type="text" value="">
												</div>
											</div>
										</div>

									</div>
									<div class="col-md-6">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="employee_id"><?php echo $this->lang->line('dashboard_employee_id');?><?php echo REQUIRED_FIELD;?></label>
													<input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_employee_id');?>" name="employee_id" type="text" value="">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="date_of_joining" class="control-label"><?php echo $this->lang->line('xin_employee_doj');?><?php echo REQUIRED_FIELD;?></label>


													<div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
														<input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_doj');?>" name="date_of_joining"   size="16" type="text"  value="" readonly>
														<span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>

													</div>

												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-md-6">

												<div class="form-group">
													<label for="location_id" class="control-label"><?php echo $this->lang->line('xin_e_details_office_location');?> (Area Of Assignment)<?php echo REQUIRED_FIELD;?></label>
													<select class="form-control" name="office_location_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_one');?>">
														<option value=""><?php echo $this->lang->line('xin_select_one');?></option>
														<?php foreach($all_office_locations as $location) {?>
															<option value="<?php echo $location->location_id?>"><?php echo $location->location_name?></option>
														<?php } ?>
													</select>
												</div>



											</div>


											<div class="col-md-6">
												<label for="office_shift_id" class="control-label">Nationality<?php echo REQUIRED_FIELD;?></label>
												<select class="form-control" name="nationality" id="nationality" data-plugin="select_hrm" data-placeholder="Nationality">
													<option value=""><?php echo $this->lang->line('xin_select_one');?></option>
													<?php foreach($all_countries as $country) {?>
														<option value="<?php echo $country->country_id;?>"> <?php echo $country->country_name;?></option>
													<?php } ?>
												</select>

											</div>

										</div>

										<div class="row">

											<div class="col-md-6">
												<div class="form-group">
													<label for="visa_occupation"><?php echo 'Visa Designation';?></label>
													<select class="form-control" name="visa_occupation" data-plugin="select_hrm" data-placeholder="<?php echo 'Visa Designation';?>">
														<option value=""></option>
														<?php foreach($all_designations as $designation) {?>
															<option value="<?php echo $designation->designation_id?>"><?php echo $designation->designation_name?></option>
														<?php } ?>
													</select>
												</div>

											</div>

											<div class="col-md-6" style="display:none;">
												<div class="form-group">
													<label for="role"><?php echo $this->lang->line('xin_employee_role');?><?php echo REQUIRED_FIELD;?></label>
													<select class="form-control" name="role" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee_role');?>">
														<option value=""></option>

														<?php foreach($all_user_roles as $role) {
															if($session['role_name']!=AD_ROLE){
																if($role->role_name!=AD_ROLE){?>
																	<option <?php if($role->role_name=='Employee'){echo 'selected';}?> value="<?php echo $role->role_id?>"><?php echo $role->role_name;?>
																	</option>
																<?php } }else {?>
																<option <?php if($role->role_name=='Employee'){echo 'selected';}?> value="<?php echo $role->role_id?>"><?php echo $role->role_name;?>
																</option>

															<?php }


														} ?>
													</select>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="gender" class="control-label"><?php echo $this->lang->line('xin_employee_gender');?><?php echo REQUIRED_FIELD;?></label>
													<select class="form-control" name="gender" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee_gender');?>">
														<option value="Male">Male</option>
														<option value="Female">Female</option>
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="date_of_birth"><?php echo $this->lang->line('xin_employee_dob');?></label>


													<div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
														<input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_dob');?>" name="date_of_birth"   size="16" type="text"  value="" readonly>
														<span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>

													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="contact_no" class="control-label"><?php echo $this->lang->line('xin_contact_number');?><?php echo REQUIRED_FIELD;?></label>


													<div class="clearfix"></div>

													<div class="input-group">
												<span class="input-group-addon-custom">
					 <select class="form-control change_country_code js-example-templating" name="country_code">
						  <?php foreach(phone_numbers_code() as $keys=>$phone_code){?>
							  <option value="<?php echo $keys; ?>-" data-len ="<?php echo $phone_code['length'];?>"   rel="<?php echo $phone_code['country_name'];?>"><?php echo $keys; ?></option>
						  <?php } ?>
						  </select>
						  </span>
														<input class="form-control" placeholder="<?php echo $this->lang->line('xin_contact_number');?>" name="contact_no"  type="text" pattern="\d*" maxlength="<?php echo MAX_PHONE_DIGITS;?>" title="<?php echo $this->lang->line('xin_use_numbers');?>">
													</div>




												</div>
											</div>
										</div>
										<!--<div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="xin_employee_password"><?php echo $this->lang->line('xin_employee_password');?><?php echo REQUIRED_FIELD;?></label>
                        <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_password');?>" name="password" type="password" value="">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="confirm_password" class="control-label"><?php echo $this->lang->line('xin_employee_cpassword');?><?php echo REQUIRED_FIELD;?></label>
                        <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_cpassword');?>" name="confirm_password" type="password" value="">
                      </div>
                    </div>
                  </div>-->

									</div>
								</div>

								<div class="row">
									<div  class="col-md-6">
										<div class="form-group">
											<label for="address"><?php echo $this->lang->line('xin_homeaddress');?></label>
											<input class="form-control" placeholder="<?php echo $this->lang->line('xin_address_1');?>" name="address" type="text">

											<br>
											<input class="form-control" placeholder="<?php echo $this->lang->line('xin_address_2');?>" name="address2" type="text">
											<br>
											<div class="row">
												<div class="col-xs-5">
													<input class="form-control" placeholder="<?php echo $this->lang->line('xin_city');?>" name="city" type="text">
												</div>
												<div class="col-xs-4">
													<input class="form-control" placeholder="<?php echo $this->lang->line('xin_state');?>" name="area" type="text">
												</div>
												<div class="col-xs-3">
													<input class="form-control" placeholder="<?php echo $this->lang->line('xin_zipcode');?>" name="zipcode" type="text">
												</div>
											</div>
											<br>
											<select class="form-control" name="home_country" id="home_country" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_country');?>">
												<option value=""><?php echo $this->lang->line('xin_select_one');?></option>
												<?php foreach($all_countries as $country) {?>
													<option value="<?php echo $country->country_id;?>"> <?php echo $country->country_name;?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div  class="col-md-6">
										<div class="form-group">
											<label for="address"><?php echo $this->lang->line('xin_residingaddress');?>&nbsp;&nbsp;( Choose the home address as same <span class="pull-xs-left m-r-1 click_same_ad">
              <input type="checkbox" class="styled" data-size="small" data-color="#3e70c9" data-secondary-color="#ddd" id="same_as_home_address" value="yes" />
            </span> )</label>
											<input class="form-control" placeholder="<?php echo $this->lang->line('xin_address_1');?>" name="residing_address1" id="residing_address1" type="text">
											<br>
											<input class="form-control" placeholder="<?php echo $this->lang->line('xin_address_2');?>" name="residing_address2" id="residing_address2" type="text">
											<br>
											<div class="row">
												<div class="col-xs-5">
													<input class="form-control" placeholder="<?php echo $this->lang->line('xin_city');?>" name="residing_city" id="residing_city" type="text">
												</div>
												<div class="col-xs-4">
													<input class="form-control" placeholder="<?php echo $this->lang->line('xin_state');?>" name="residing_area"  id="residing_area"type="text">
												</div>
												<div class="col-xs-3">
													<input class="form-control" placeholder="<?php echo $this->lang->line('xin_zipcode');?>" name="residing_zipcode" id="residing_zipcode" type="text">
												</div>
											</div>
											<br>
											<select class="form-control" name="residing_country" id="residing_country" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_country');?>">
												<option value=""><?php echo $this->lang->line('xin_select_one');?></option>
												<?php foreach($all_countries as $country) {?>
													<option value="<?php echo $country->country_id;?>"> <?php echo $country->country_name;?></option>
												<?php } ?>
											</select>
										</div>
									</div>

								</div>


								<div class="clearfix"></div>
								<div class="">

									<button type="submit" class="btn pull-right bg-teal-400 save"><?php echo $this->lang->line('xin_save');?></button>
								</div>



							</div>

						</form>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>

	<div class="panel panel-flat">
		<div class="panel-heading">
			<h5 class="panel-title">

				<strong><?php echo $this->lang->line('xin_list_all');?></strong> <?php echo $this->lang->line('xin_employees');?>

			</h5>
			<div class="heading-elements">
				<?php if(in_array('60c-view',role_resource_ids())) {?>
					<div class="add-record-btn">
						<button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_add_new');?></button>
					</div>
				<?php } ?>
			</div>
		</div>


		<div data-pattern="priority-columns">

			<table class="table" id="xin_report_table">

				<thead>
				<tr class="table_columns">
					<th>Status</th>
				</tr>
				</thead>

				<tbody>

				</tbody>
			</table>

		</div>
	</div>
	<?php } else { ?>
	<div class="panel panel-flat">
		<div class="panel-heading">
			<h5 class="panel-title text-danger">
				<?php echo $this->lang->line('xin_permission');?>
			</h5>
		</div>
	</div>
<?php } ?>
