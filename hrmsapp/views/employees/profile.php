<?php
/* Profile view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $user = $this->Xin_model->read_user_info($session['user_id']);?>
<?php $system = $this->Xin_model->read_setting_info(1);?>

<?php if($user[0]->profile_background!=''){
	$bg_img=$user[0]->profile_background;
}else{
	$bg_img='profile_default.jpg';
}
?>
<?php if($profile_picture!='' && $profile_picture!='no file') {
	$de_file = base_url().'uploads/profile/'.$profile_picture;
} else {?>
	<?php if($gender=='Male') { ?>
		<?php $de_file = base_url().'uploads/profile/default_male.jpg';?>
	<?php } else { ?>
		<?php $de_file = base_url().'uploads/profile/default_female.jpg';?>
	<?php } ?>
<?php } ?>
	<!-- Cover area -->
	<div class="profile-cover">
		<!--<div class="profile-cover-img" style="background-image: url(<?php //echo base_url().'uploads/profile/background/'.$bg_img;?>)"></div>
					<div class="media">
						<div class="media-left">
							<a href="#" class="profile-thumb">
								<img id="u_file1" src="<?php //echo $de_file;?>" class="img-circle" alt="">
							</a>
						</div>

						<div class="media-body">
				    		<h1><?php //echo change_fletter_caps($first_name.' '.$middle_name.' '.$last_name);?> <small class="display-block"><?php //echo $designation;?></small></h1>
						</div>



                    <?php //if($system[0]->enable_profile_background=='yes'):?>
					 <form name="profile_background" id="profile_background" enctype="multipart/form-data">
                             <input type="hidden" name="user_id" value="<?php //echo $session['user_id'];?>">
						<div class="media-right pull-right media-middle">
							<ul class="list-inline list-inline-condensed no-margin-bottom text-nowrap">
								<li><span class="btn bg-teal-400 btn-file"><i class="icon-file-picture position-left"></i> Browse
          <input type="file" name="p_file" id="p_file">
          </span></li>
								<li><span>
          <button type="submit" class="btn bg-teal-400 save"><?php //echo $this->lang->line('xin_save');?></button>
          </span></li>
							</ul>
							 </form>

           	<?php //endif;?>

						</div>

					</div>
				--></div>
	<!-- /cover area -->
<?php
$gdate = explode(' ',$last_login_date);
$login_date = $this->Xin_model->set_date_format($gdate[0]);
?>

	<!-- Toolbar -->
	<div class="navbar navbar-default navbar-xs content-group">
		<ul class="nav navbar-nav visible-xs-block">
			<li class="full-width text-center"><a data-toggle="collapse" data-target="#navbar-filter"><i class="icon-menu7"></i></a></li>
		</ul>

		<div class="navbar-collapse collapse" id="navbar-filter">
			<ul class="nav navbar-nav">

				<h5>Profile Completion</h5>
				<?php echo $progress_bar;?>
			</ul>

			<div class="navbar-right" style="margin: 3em 0em 1em 1em;">
				<i class="icon-history position-left"></i> <?php echo $this->lang->line('dashboard_last_login');?>  <span class="text-semibold"><?php
					echo $login_date.' '.date('h:i A', strtotime($last_login_date));?></span>, <?php echo $this->lang->line('xin_e_details_from');?> <?php echo $last_login_ip;?>
			</div>
		</div>



	</div>
	<!-- /toolbar -->
<?php if($system[0]->employee_manage_own_profile=='yes'){?>
	<div class="tabbable nav-tabs-vertical nav-tabs-left">
		<ul class="nav nav-tabs nav-tabs-highlight">
			<?php if($system[0]->employee_manage_own_profile=='yes'){?>
				<li id="user_details_1"  class="active"> <a href="#user_basic_info"  data-toggle="tab"> <i class="icon-user position-left"></i> <?php echo $this->lang->line('xin_e_details_basic');?> </a> </li>
			<?php } ?>
			<?php if($system[0]->employee_manage_own_document=='yes'){?>
				<li id="user_details_15"> <a href="#immigration"  data-toggle="tab"> <i class="icon-file-empty2 position-left"></i> <?php echo $this->lang->line('xin_e_details_document'); ?> </a> </li>
			<?php } ?>
			<?php if($system[0]->employee_manage_own_picture=='yes'){?>
				<li id="user_details_2"> <a href="#profile-picture"  data-toggle="tab"> <i class="icon-camera position-left"></i> <?php echo $this->lang->line('xin_e_details_profile_picture');?> </a> </li>
			<?php } ?>

			<?php if($system[0]->employee_manage_own_contact=='yes'){?>
				<li id="user_details_3"> <a href="#contact"  data-toggle="tab"> <i class="icon-phone position-left"></i> Emergency <?php echo $this->lang->line('xin_e_details_contact');?>s </a> </li>
			<?php } ?>
			<?php //if($system[0]->employee_manage_own_social=='yes'){?>
			<!--<li id="user_details_4"> <a href="#social_networking"  data-toggle="tab"> <i class="icon-users4 position-left"></i> <?php echo $this->lang->line('xin_e_details_social');?> </a> </li>-->
			<?php //} ?>


			<?php if($system[0]->employee_manage_own_qualification=='yes'){?>
				<li id="user_details_6"> <a  href="#qualification"  data-toggle="tab"> <i class="icon-graduation2 position-left"></i> <?php echo $this->lang->line('xin_e_details_qualification');?> </a> </li>
			<?php } ?>
			<?php if($system[0]->employee_manage_own_work_experience=='yes'){?>
				<li id="user_details_7"> <a href="#work_experience"  data-toggle="tab"> <i class="icon-flip-vertical3 position-left"></i> <?php echo $this->lang->line('xin_e_details_w_experience');?> </a> </li>
			<?php } ?>
			<?php if($system[0]->employee_manage_own_bank_account=='yes'){?>
				<li id="user_details_8"> <a href="#bank_account"  data-toggle="tab"> <i class="icon-book position-left"></i> <?php echo $this->lang->line('xin_e_details_baccount');?> </a> </li>
			<?php } ?>
			<?php if($system[0]->employee_manage_own_document=='yes'){?>
				<li id="user_details_9"> <a href="#contract"  data-toggle="tab"> <i class="icon-pen2 position-left"></i> <?php echo $this->lang->line('xin_e_details_contract');?> </a> </li>
			<?php } ?>

			<?php if($system[0]->employee_manage_own_document=='yes'){?>
				<li id="user_details_16" onclick="check_visa_under('<?php echo $this->uri->segment(3);?>');"> <a href="#pay_structure"  data-toggle="tab"> <i class="icon-coins position-left"></i> <?php echo $this->lang->line('xin_e_details_pay');?> </a> </li>
			<?php } ?>
			<li id="user_details_14"> <a href="#change_password"  data-toggle="tab"> <i class="icon-key position-left"></i> <?php echo $this->lang->line('xin_e_details_cpassword');?> </a></li>
		</ul>

		<div class="tab-content">
			<div class="tab-pane active has-padding animated fadeInRight" id="user_basic_info">
				<?php if($session['role_name']==AD_ROLE){?>
				<form id="basic_info" action="<?php echo site_url("profile/user_basic_info") ?>" name="basic_info" method="post">
					<?php }else{ ?>
					<form id="basic_info">
						<?php } ?>
						<input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
						<input type="hidden" name="u_basic_info" value="UPDATE">
						<div class="panel panel-flat">

							<div class="panel-heading">
								<h5 class="panel-title">

									<strong><?php echo $this->lang->line('xin_e_details_basic_info');?></strong>

								</h5>

							</div>

							<div class="panel-body">
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label for="first_name"><?php echo $this->lang->line('xin_employee_first_name');?></label>
											<input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_first_name');?>" name="first_name" type="text" value="<?php echo $first_name;?>">
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="middle_name"><?php echo $this->lang->line('xin_employee_middle_name');?></label>
											<input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_middle_name');?>" name="middle_name" type="text" value="<?php echo $middle_name;?>">
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="first_name"><?php echo $this->lang->line('xin_employee_last_name');?></label>
											<input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_last_name');?>" name="last_name" type="text" value="<?php echo $last_name;?>">
										</div>
									</div>


								</div>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label for="email" class="control-label"><?php echo $this->lang->line('dashboard_email');?></label>
											<input name="email" class="form-control" placeholder="<?php echo $this->lang->line('dashboard_email');?>"  type="text" value="<?php echo $email;?>">

										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="email" class="control-label">Personal Email</label>
											<input class="form-control" placeholder="Personal Email" name="personal_email" type="email" value="<?php echo $personal_email;?>" >
										</div>
									</div>


									<div class="col-md-4">
										<div class="form-group">
											<label for="date_of_birth"><?php echo $this->lang->line('xin_employee_dob');?></label>


											<div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
												<input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_dob');?>" name="date_of_birth" size="16" type="text" value="<?php echo format_date('d M Y',$date_of_birth);?>" readonly>
												<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>

											</div>

										</div>
									</div>

									<input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_tenure');?>" name="tenure" id="tenure" type="hidden" value="<?php //echo $tenure;?>" readonly>

								</div>

								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label for="employee_id"><?php echo $this->lang->line('dashboard_employee_id');?></label>
											<input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_employee_id');?>" type="text" value="<?php echo $employee_id;?>" disabled>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="Company" class="control-label">Company</label>
											<select class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_one');?>" >

												<?php foreach($all_companies as $cmpnys) {?>
													<?php if($cmpnys->company_id==$company_id){?>
														<option value="<?php echo $cmpnys->company_id;?>"><?php echo $cmpnys->name?></option>
													<?php } }	?>
											</select>
										</div>
									</div>


									<div class="col-md-4">
										<div class="form-group">
											<label for="status" class="control-label">Working Hours </label>
											<input class="form-control" type="text" value="<?php echo $working_hours;?> H" disabled>

										</div>
									</div>


								</div>

								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label for="gender" class="control-label"><?php echo $this->lang->line('xin_departments');?></label>
											<select class="form-control" name="department_id" id="aj_department" onchange="getDepartmentBasedEmployees(this.value,'<?php echo $user_id;?>','')" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee_department');?>">
												<label for="department"><?php echo $this->lang->line('xin_employee_department');?></label>
												<option value=""></option>
												<?php foreach($all_departments as $department) {
													if($department_id==$department->department_id){
														?>
														<option value="<?php echo $department->department_id?>" <?php if($department_id==$department->department_id):?> selected <?php endif;?>><?php echo $department->department_name?></option>
													<?php }} ?>
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="marital_status" class="control-label"><?php echo $this->lang->line('xin_designation');?></label>
											<select class="form-control"  data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_designation');?>" readonly>
												<option value=""></option>
												<?php foreach($all_designations as $designation) {
													if($designation_id==$designation->designation_id){
														?>
														<option value="<?php echo $designation->designation_id?>" <?php if($designation_id==$designation->designation_id):?> selected <?php endif;?>><?php echo $designation->designation_name?></option>
													<?php } } ?>
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="send_mail"><?php echo $this->lang->line('xin_employee_reporting_manager');?></label>
											<select class="form-control" id="reportingmanager" name="reporting_manager" data-plugin="select_hrm" data-placeholder="Select <?php echo $this->lang->line('xin_employee_reporting_manager');?>" >


											</select>
										</div>
									</div>
								</div>



								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label for="date_of_joining" class="control-label"><?php echo $this->lang->line('xin_employee_doj');?></label>
											<input class="form-control "  placeholder="<?php echo $this->lang->line('xin_employee_doj');?>"  id="joiningdate" type="text" value="<?php echo format_date('d M Y',$date_of_joining);?>">

											<input class="form-control " id="date_of_leaving" type="hidden" value="<?php echo format_date('d M Y',$date_of_leaving);?>">

										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">

											<label for="role"><?php echo $this->lang->line('xin_employee_languages_known');?><?php echo REQUIRED_FIELD;?></label>
											<?php $languages_known=explode(',',$languages_known);?>
											<select class="form-control" multiple="multiple" name="languages_known[]" data-plugin="select_hrm" data-placeholder="Select Language">
												<option value=""></option>

												<?php foreach(language_lists() as $language) {
													?>
													<option value="<?php echo $language;?>" <?php if(in_array($language,$languages_known)):?> selected <?php endif;?>><?php echo $language;?>
													</option>
													<?php
												} ?>
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<label for="first_name">Nationality</label>
										<select class="form-control" name="nationality" id="nationality" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_country');?>">
											<option value="">Select One</option>
											<?php foreach($all_countries as $scountry) {?>
												<option <?php if($scountry->country_id==$nationality):?> selected <?php endif; ?> value="<?php echo $scountry->country_id;?>"> <?php echo $scountry->country_name;?></option>
											<?php } ?>
										</select>
									</div>

								</div>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label for="gender" class="control-label"><?php echo $this->lang->line('xin_employee_gender');?></label>
											<select class="form-control" name="gender" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee_gender');?>">
												<option value="Male" <?php if($gender=='Male'):?> selected <?php endif; ?>>Male</option>
												<option value="Female" <?php if($gender=='Female'):?> selected <?php endif; ?>>Female</option>
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="marital_status" class="control-label"><?php echo $this->lang->line('xin_employee_mstatus');?></label>
											<select class="form-control" name="marital_status" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee_mstatus');?>">
												<option value="Single" <?php if($marital_status=='Single'):?> selected <?php endif; ?>>Single</option>
												<option value="Married" <?php if($marital_status=='Married'):?> selected <?php endif; ?>>Married</option>
												<option value="Widowed" <?php if($marital_status=='Widowed'):?> selected <?php endif; ?>>Widowed</option>
												<option value="Divorced or Separated" <?php if($marital_status=='Divorced or Separated'):?> selected <?php endif; ?>>Divorced or Separated</option>
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<?php $contact_no=@explode('-',@$contact_no);?>
										<div class="form-group">
											<label for="contact_no" class="control-label"><?php echo $this->lang->line('xin_contact_number');?></label>
											<div class="clearfix"></div>
											<div class="input-group">
												<span class="input-group-addon-custom">


			   <select class="form-control change_country_code  js-example-templating" name="country_code" data-placeholder="<?php echo $this->lang->line('xin_contact_number');?>">
			  <?php foreach(phone_numbers_code() as $keys=>$phone_code){?>
				  <option <?php if($keys==@$contact_no[0]){echo 'selected';}?> value="<?php echo $keys; ?>-" data-len ="<?php echo $phone_code['length'];?>"   rel="<?php echo $phone_code['country_name'];?>"><?php echo $keys; ?></option>
			  <?php } ?>
			  </select></span>
												<input class="form-control " placeholder="<?php echo $this->lang->line('xin_contact_number');?>" name="contact_no"   type="text" pattern="\d*" maxlength="<?php echo MAX_PHONE_DIGITS;?>"  value="<?php echo @$contact_no[1];?>" title="Should be Numbers only">
											</div>


										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label for="spouse">Spouse Name (If Married)</label>
											<input class="form-control" placeholder="Spouse Name" name="spouse_name" type="text" value="<?php echo $user[0]->spouse_name;?>" />
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="Tenure">Tenure</label>
											<input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_tenure');?>"  id="tenure1" type="text" value="<?php echo $tenure;?>" readonly>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="location_id" class="control-label"><?php echo $this->lang->line('xin_e_details_office_location');?> (Area Of Assignment)</label>
											<select class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_one');?>" readonly>
												<option value="">Not Available</option>
												<?php foreach($all_office_locations as $location) {?>
													<?php if($location->location_id==$office_location_id){?>
														<option <?php if($location->location_id==$office_location_id){?> selected <?php } ?> value="<?php echo $location->location_id?>"><?php echo $location->location_name?></option>
													<?php } ?>
												<?php } ?>
											</select>
										</div>
									</div>


								</div>

								<div class="row">
									<div  class="col-md-6">
										<div class="form-group">
											<label for="address"><?php echo $this->lang->line('xin_homeaddress');?></label>
											<input class="form-control" value="<?php echo $address;?>" placeholder="<?php echo $this->lang->line('xin_address_1');?>" name="address" type="text">

											<br>
											<input class="form-control" value="<?php echo $address2;?>"  placeholder="<?php echo $this->lang->line('xin_address_2');?>" name="address2" type="text">
											<br>
											<div class="row">
												<div class="col-xs-5">
													<input class="form-control" placeholder="<?php echo $this->lang->line('xin_city');?>" value="<?php echo $city;?>"  name="city" type="text">
												</div>
												<div class="col-xs-4">
													<input class="form-control" placeholder="<?php echo $this->lang->line('xin_state');?>" name="area" value="<?php echo $area;?>"  type="area">
												</div>
												<div class="col-xs-3">
													<input class="form-control" placeholder="<?php echo $this->lang->line('xin_zipcode');?>" name="zipcode" value="<?php echo $zipcode;?>"  type="text">
												</div>
											</div>
											<br>

											<select class="form-control" name="home_country" id="home_country" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_country');?>">
												<option value="">Select One</option>
												<?php foreach($all_countries as $scountry) {?>
													<option <?php if($scountry->country_id==$home_country):?> selected <?php endif; ?> value="<?php echo $scountry->country_id;?>"> <?php echo $scountry->country_name;?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div  class="col-md-6">
										<div class="form-group">
											<label for="address"><?php echo $this->lang->line('xin_residingaddress');?>&nbsp;&nbsp;( Choose the home address as same <span class="pull-xs-left  ml-5 click_same_ad">
			  <input type="checkbox" class="styled"  id="same_as_home_address" value="yes" <?php if($address==$residing_address1  && $address!=''){echo 'checked="checked"';}?>/>
			</span> )</label>
											<input class="form-control" placeholder="<?php echo $this->lang->line('xin_address_1');?>" name="residing_address1" id="residing_address1" value="<?php echo $residing_address1;?>" type="text">
											<br>
											<input class="form-control" placeholder="<?php echo $this->lang->line('xin_address_2');?>" name="residing_address2" id="residing_address2" value="<?php echo $residing_address2;?>" type="text">
											<br>
											<div class="row">
												<div class="col-xs-5">
													<input class="form-control" placeholder="<?php echo $this->lang->line('xin_city');?>" name="residing_city" id="residing_city" value="<?php echo $residing_city;?>" type="text">
												</div>
												<div class="col-xs-4">
													<input class="form-control" placeholder="<?php echo $this->lang->line('xin_state');?>" name="residing_area"  id="residing_area"value="<?php echo $residing_area;?>" type="text">
												</div>
												<div class="col-xs-3">
													<input class="form-control" value="<?php echo $residing_zipcode;?>" placeholder="<?php echo $this->lang->line('xin_zipcode');?>" name="residing_zipcode" id="residing_zipcode" type="text">
												</div>
											</div>
											<br>

											<select class="form-control" name="residing_country" id="residing_country" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_country');?>">
												<option value="">Select One</option>
												<?php foreach($all_countries as $scountry) {?>
													<option <?php if($scountry->country_id==$residing_country):?> selected <?php endif; ?> value="<?php echo $scountry->country_id;?>"> <?php echo $scountry->country_name;?></option>
												<?php } ?>
											</select>


										</div>
									</div>

								</div>


								<?php if($session['role_name']==AD_ROLE){?>
									<button type="submit" class="btn bg-teal-400 pull-right save"><?php echo $this->lang->line('xin_save');?></button>
								<?php } ?>


							</div>
						</div>
						<?php if($session['role_name']==AD_ROLE){?></form>									   <?php }else{ ?>
				</form>
			<?php } ?>



			</div>

			<div class="tab-pane has-padding animated fadeInRight" id="immigration">



				<div class="panel panel-flat">
					<div class="panel-heading">
						<h5 class="panel-title">

							<strong>List All</strong> Documents

						</h5>

					</div>

					<div class="panel-body">

						<table class="table table-striped" id="xin_table_imgdocument">
							<thead>
							<tr>
								<th>Document</th>
								<th>Issued Date</th>
								<th>Expiry Date</th>
								<th>Details</th>
								<th>File</th>
							</tr>
							</thead>
						</table>
					</div>
				</div>

			</div>


			<div class="tab-pane has-padding animated fadeInRight" id="profile-picture">
				<form id="f_profile_picture" action="<?php echo site_url("employees/profile_picture") ?>" name="profile_picture" method="post">
					<input type="hidden" name="user_id" id="user_id" value="<?php echo $session['user_id'];?>">
					<input type="hidden" name="session_id" id="session_id" value="<?php echo $session['user_id'];?>">
					<input type="hidden" name="u_profile_picture" value="UPDATE">
					<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">

								<strong><?php echo $this->lang->line('xin_e_details_profile_picture');?></strong>

							</h5>

						</div>

						<div class="panel-body">
							<div class="row">
								<div class="col-md-12">
									<div class='form-group'>
										<input type="file" class="file-input" name="p_file" id="p_file">
										<br>
										<?php if($profile_picture!='' && $profile_picture!='no file') {?>
											<img src="<?php echo site_url().'uploads/profile/'.$profile_picture;?>" width="100px" style="border: 1px solid #249E92;" id="u_file">
										<?php } else {?>
											<?php if($gender=='Male') { ?>
												<?php $de_file = site_url().'uploads/profile/default_male.jpg';?>
											<?php } else { ?>
												<?php $de_file = site_url().'uploads/profile/default_female.jpg';?>
											<?php } ?>
											<img src="<?php echo $de_file;?>" width="50px"  id="u_file">
										<?php } ?>
										<br>
										<small class="help-block"><?php echo $this->lang->line('xin_e_details_picture_type');?></small>
										<?php if($profile_picture!='' && $profile_picture!='no file') {?>
											<br />

										<?php } ?>
									</div>
								</div>
							</div>
							<button type="submit" class="btn bg-teal-400 pull-right save"><?php echo $this->lang->line('xin_save');?></button>


						</div>
					</div>
				</form>

			</div>



			<?php if($system[0]->employee_manage_own_contact=='yes'){?>
				<div class="tab-pane has-padding animated fadeInRight" id="contact">
					<div class="add-form" style="display:none;">
						<div class="panel panel-flat">
							<div class="panel-heading">
								<h5 class="panel-title">

									<strong><?php echo $this->lang->line('xin_add_new');?></strong>  <?php echo $this->lang->line('xin_e_details_contact');?>

								</h5>
								<div class="heading-elements">
									<div class="add-record-btn">
										<button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_hide');?></button>
									</div>
								</div>
							</div>
							<form id="contact_info" action="<?php echo site_url("employees/contact_info") ?>" name="contact_info" method="post">
								<input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id;?>">
								<input type="hidden" name="u_basic_info" value="ADD">
								<div class="panel-body">
									<div class="row">
										<div class="col-md-6">


											<div class="form-group">
												<label for="name" class="control-label"><?php echo $this->lang->line('xin_name');?><?php echo REQUIRED_FIELD;?></label>
												<input class="form-control" placeholder="<?php echo $this->lang->line('xin_name');?>" name="contact_name" type="text">
											</div>
										</div>


										<div class="col-md-6">


											<div class="form-group">
												<label for="relation"><?php echo $this->lang->line('xin_e_details_relation');?><?php echo REQUIRED_FIELD;?></label>
												<select class="form-control" name="relation" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_one');?>">
													<option value=""><?php echo $this->lang->line('xin_select_one');?></option>
													<option value="Self">Self</option>
													<option value="Parent">Parent</option>
													<option value="Spouse">Spouse</option>
													<option value="Child">Child</option>
													<option value="Sibling">Sibling</option>
													<option value="In Laws">In Laws</option>
													<option value="Friend">Friend</option>
												</select>
											</div>


										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<div class="input-group">
												<span class="input-group-addon-custom">


						  <select class="form-control change_country_code js-example-templating" name="country_code">
						  <?php foreach(phone_numbers_code() as $keys=>$phone_code){?>
							  <option value="<?php echo $keys; ?>-" data-len ="<?php echo $phone_code['length'];?>"   rel="<?php echo $phone_code['country_name'];?>"><?php echo $keys; ?></option>
						  <?php } ?>
						  </select>
						  </span>
													<input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_mobile');?>" name="mobile_phone"  type="text" maxlength="<?php echo MAX_PHONE_DIGITS;?>">
												</div>


											</div>
										</div>

										<div class="col-md-6">

											<div class="form-group mt-5">
												<label class="custom-control custom-checkbox">
													<input type="checkbox" class="styled" id="is_primary" value="1" name="is_primary">
													<span class="custom-control-indicator ml-5"></span> <span class="custom-control-description"><?php echo $this->lang->line('xin_e_details_pcontact');?></span> </label>
												&nbsp;
												<label class="custom-control custom-checkbox">
													<input type="checkbox" class="styled" id="is_secondary" value="1" name="is_secondary">
													<span class="custom-control-indicator ml-5"></span> <span class="custom-control-description"><?php echo $this->lang->line('xin_e_details_scontact');?></span> </label>
											</div>



										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group" id="designation_ajax">
												<label for="address_1" class="control-label"><?php echo $this->lang->line('xin_address');?></label>
												<input class="form-control" placeholder="<?php echo $this->lang->line('xin_address_1');?>" name="address_1" type="text">
											</div>

										</div>
										<div class="col-md-6">
											<label for="name" class="control-label" style="visibility:hidden;">-</label>
											<div class="form-group">
												<div class="row">
													<div class="col-xs-5">
														<input class="form-control" placeholder="<?php echo $this->lang->line('xin_city');?>" name="city" type="text">
													</div>
													<div class="col-xs-4">
														<input class="form-control" placeholder="<?php echo $this->lang->line('xin_state');?>" name="state" type="text">
													</div>
													<div class="col-xs-3">
														<input class="form-control" placeholder="<?php echo $this->lang->line('xin_zipcode');?>" name="zipcode" type="text">
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">

											<div class="form-group">
												<input class="form-control" placeholder="<?php echo $this->lang->line('xin_address_2');?>" name="address_2" type="text">
											</div>


										</div>
										<div class="col-md-6">
											<div class="form-group">
												<select name="country" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_country');?>">
													<option value=""></option>
													<?php foreach($all_countries as $country) {?>
														<option value="<?php echo $country->country_id;?>"> <?php echo $country->country_name;?></option>
													<?php } ?>
												</select>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-5">

										</div>

									</div>

									<button type="submit" class="btn bg-teal-400 pull-right save"><?php echo $this->lang->line('xin_save');?></button>
								</div>

							</form>


						</div>
					</div>
					<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">

								<strong><?php echo $this->lang->line('xin_list_all');?></strong>  <?php echo $this->lang->line('xin_e_details_contacts');?>

							</h5>

							<div class="heading-elements">

								<div class="add-record-btn">
									<button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_add_new');?></button>
								</div>

							</div>
						</div>
						<div class="panel-body">
							<table class="table table-striped" id="xin_table_contact">
								<thead>
								<tr>
									<th><?php echo $this->lang->line('xin_employees_full_name');?></th>
									<th><?php echo $this->lang->line('xin_e_details_relation');?></th>
									<th>City</th>
									<th><?php echo $this->lang->line('xin_e_details_mobile');?></th>
									<th><?php echo $this->lang->line('xin_action');?></th>
								</tr>
								</thead>
							</table>
						</div>
					</div>

				</div>
			<?php } ?>

			<?php if($system[0]->employee_manage_own_qualification=='yes'){?>
				<div class="tab-pane has-padding animated fadeInRight" id="qualification">
					<div class="add-form" style="display:none;">
						<div class="panel panel-flat">

							<div class="panel-heading">
								<h5 class="panel-title">
									<strong><?php echo $this->lang->line('xin_add_new');?></strong> <?php echo $this->lang->line('xin_e_details_qualification');?>
								</h5>
								<div class="heading-elements">
									<div class="add-record-btn">
										<button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_hide');?></button>
									</div>
								</div>
							</div>

							<div class="panel-body">
								<form id="qualification_info" action="<?php echo site_url("employees/qualification_info") ?>" name="qualification_info" method="post">
									<input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
									<input type="hidden" name="u_basic_info" value="UPDATE">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="name"><?php echo $this->lang->line('xin_e_details_inst_name');?><?php echo REQUIRED_FIELD;?></label>
												<input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_inst_name');?>" name="name" type="text">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="education_level" class="control-label"><?php echo $this->lang->line('xin_e_details_edu_level');?></label>
												<select class="form-control" name="education_level" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_edu_level');?>">
													<?php foreach($all_education_level as $education_level) {?>
														<option value="<?php echo $education_level->type_id;?>"><?php echo $education_level->type_name?></option>
													<?php } ?>
												</select>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label for="from_year" class="control-label"><?php echo $this->lang->line('xin_e_details_timeperiod');?><?php echo REQUIRED_FIELD;?></label>
												<div class="row">
													<div class="col-md-6">

														<div class="input-group date form_year" data-date="" data-date-format="yyyy"  data-link-format="yyyy">
															<input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_from');?>" name="from_year" size="16" type="text" value="<?php echo date('Y');?>" readonly>
															<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
														</div>

													</div>
													<div class="col-md-6">


														<div class="input-group date form_year" data-date="" data-date-format="yyyy"  data-link-format="yyyy">
															<input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_to');?>" name="to_year" size="16" type="text" value="<?php echo date('Y');?>" readonly>
															<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
														</div>

													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="language" class="control-label"><?php echo $this->lang->line('xin_e_details_language');?></label>
												<select class="form-control" name="language" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_language');?>">
													<?php foreach($all_qualification_language as $qualification_language) {?>
														<option value="<?php echo $qualification_language->type_id?>"><?php echo $qualification_language->type_name?></option>
													<?php } ?>
												</select>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="skill" class="control-label"><?php echo $this->lang->line('xin_e_details_skill');?></label>
												<select class="form-control" name="skill" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_skill');?>">
													<option value=""></option>
													<?php foreach($all_qualification_skill as $qualification_skill) {?>
														<option value="<?php echo $qualification_skill->type_id?>"><?php echo $qualification_skill->type_name?></option>
													<?php } ?>
												</select>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label for="to_year" class="control-label"><?php echo $this->lang->line('xin_description');?></label>
												<textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_description');?>" data-show-counter="1" data-limit="300" name="description" cols="30" rows="3" id="d_description"></textarea>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<div class="text-right">
													<button type="submit" class="btn bg-teal-400 save"><?php echo $this->lang->line('xin_save');?><i class="icon-spinner3 spinner position-left"></i></button>
												</div>
											</div>
										</div>
									</div>
							</div>

							</form>
						</div>
					</div>

					<div class="panel panel-flat">

						<div class="panel-heading">
							<h5 class="panel-title">
								<strong><?php echo $this->lang->line('xin_list_all');?></strong> <?php echo $this->lang->line('xin_e_details_qualification');?>
							</h5>
							<div class="heading-elements">

								<div class="add-record-btn">
									<button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_add_new');?></button>
								</div>

							</div>
						</div>

						<div class="panel-body">
							<table class="table table-striped" id="xin_table_qualification">
								<thead>
								<tr>
									<th><?php echo $this->lang->line('xin_e_details_inst_name');?></th>
									<th><?php echo $this->lang->line('xin_e_details_timeperiod');?></th>
									<th><?php echo $this->lang->line('xin_e_details_edu_level');?></th>
									<th><?php echo $this->lang->line('xin_action');?></th>
								</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			<?php } ?>


			<?php if($system[0]->employee_manage_own_work_experience=='yes'){?>
				<div class="tab-pane has-padding animated fadeInRight" id="work_experience">
					<div class="add-form" style="display:none;">
						<div class="panel panel-flat">
							<form id="work_experience_info" action="<?php echo site_url("employees/work_experience_info") ?>" name="work_experience_info" method="post">
								<input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
								<input type="hidden" name="u_basic_info" value="UPDATE">
								<div class="panel-heading">
									<h5 class="panel-title">
										<strong><?php echo $this->lang->line('xin_add_new');?></strong> <?php echo $this->lang->line('xin_e_details_w_experience');?>
									</h5>
									<div class="heading-elements">
										<div class="add-record-btn">
											<button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_hide');?></button>
										</div>
									</div>
								</div>
								<div class="panel-body">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="company_name"><?php echo $this->lang->line('xin_company_name');?><?php echo REQUIRED_FIELD;?></label>
												<input class="form-control" placeholder="<?php echo $this->lang->line('xin_company_name');?>" name="company_name" type="text" value="" id="company_name">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="post"><?php echo $this->lang->line('xin_e_details_post');?> (Designation)<?php echo REQUIRED_FIELD;?></label>
												<input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_post');?>" name="post" type="text" value="" id="post">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label for="from_year" class="control-label"><?php echo $this->lang->line('xin_e_details_timeperiod');?><?php echo REQUIRED_FIELD;?></label>
												<div class="row">
													<div class="col-md-6">


														<div class="input-group date form_month_year" data-date="" data-date-format="yyyy MM"  data-link-format="yyyy MM">
															<input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_from');?>" name="from_date" size="16" type="text" value="" readonly>
															<span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>

														</div>

													</div>
													<div class="col-md-4">

														<div class="input-group date form_month_year" data-date="" data-date-format="yyyy MM"  data-link-format="yyyy MM">
															<input onchange="jQuery('.is_current_exp').closest('span').removeClass('checked');jQuery('.is_current_exp').prop('checked',false);" class="form-control exp_to_date" placeholder="<?php echo $this->lang->line('dashboard_to');?>" name="to_date" size="16" type="text" value="" readonly>
															<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>

														</div>
													</div>
													<div class="col-md-2">
														<div class="form-group mt-10">
															<label class="custom-control custom-checkbox">
																<input type="checkbox" class="styled is_current_exp" id="is_current_exp" value="1" name="is_current_exp" onclick="jQuery('.exp_to_date').val('');">
																<span class="custom-control-indicator ml-5"></span> <span class="custom-control-description">Current</span> </label>
														</div>

													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label for="description"><?php echo $this->lang->line('xin_description');?></label>
												<textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_description');?>" data-show-counter="1" data-limit="300" name="description" cols="30" rows="4" id="description"></textarea>
												<span class="countdown"></span> </div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<div class="text-right">
													<button type="submit" class="btn bg-teal-400 save"><?php echo $this->lang->line('xin_save');?><i class="icon-spinner3 spinner position-left"></i></button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div><div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
								<strong><?php echo $this->lang->line('xin_list_all');?></strong> <?php echo $this->lang->line('xin_e_details_w_experience');?>
							</h5>



							<div class="heading-elements">
								<div class="add-record-btn">
									<button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_add_new');?></button>
								</div>

							</div>
						</div>

						<div class="panel-body">

							<table class="table table-striped" id="xin_table_work_experience">
								<thead>
								<tr>
									<th><?php echo $this->lang->line('xin_company_name');?></th>
									<th><?php echo $this->lang->line('xin_e_details_frm_date');?></th>
									<th><?php echo $this->lang->line('xin_e_details_to_date');?></th>
									<th><?php echo $this->lang->line('xin_e_details_post');?></th>
									<th><?php echo $this->lang->line('xin_description');?></th>
									<th><?php echo $this->lang->line('xin_action');?></th>
								</tr>
								</thead>
							</table>
						</div>
					</div>

				</div>
			<?php } ?>


			<?php if($system[0]->employee_manage_own_bank_account=='yes'){?>
				<div class="tab-pane has-padding animated fadeInRight" id="bank_account">
					<div class="add-form" style="display:none;">
						<div class="panel panel-flat">
							<form id="bank_account_info" action="<?php echo site_url("employees/bank_account_info") ?>" name="bank_account_info" method="post">
								<input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
								<input type="hidden" name="u_basic_info" value="UPDATE">
								<div class="panel-heading">
									<h5 class="panel-title">
										<strong><?php echo $this->lang->line('xin_add_new');?></strong> <?php echo $this->lang->line('xin_e_details_baccount');?>
									</h5>
									<div class="heading-elements">
										<div class="add-record-btn">
											<button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_hide');?></button>
										</div>
									</div>
								</div>
								<div class="panel-body">
									<div class="col-sm-6">
										<input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_acc_title');?>" name="account_title" type="hidden" value="Current Account" id="account_name">

										<div class="form-group">
											<label for="account_number"><?php echo $this->lang->line('xin_e_details_acc_number');?></label>
											<input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_acc_number');?>" name="account_number" type="text" value="" id="account_number" style="text-transform:uppercase;">
										</div>
										<div class="form-group">
											<label for="bank_code"><?php echo $this->lang->line('xin_e_details_bank_code');?></label>
											<input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_bank_code');?>" name="bank_code" type="text" value="" id="bank_code" style="text-transform:uppercase;">
										</div>

									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label for="bank_name"><?php echo $this->lang->line('xin_e_details_bank_name');?></label>
											<input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_bank_name');?>" name="bank_name" type="text" value="" id="bank_name" style="text-transform:uppercase;">
										</div>
										<div class="form-group">
											<label for="bank_branch"><?php echo $this->lang->line('xin_e_details_bank_branch');?></label>
											<input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_bank_branch');?>" name="bank_branch" type="text" value="" id="bank_branch" style="text-transform:uppercase;">
										</div>
									</div>
									<div class="col-sm-12">

									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<div class="text-right">

													<button type="submit" class="btn bg-teal-400 save"><?php echo $this->lang->line('xin_save');?><i class="icon-spinner3 spinner position-left"></i></button>

												</div>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div> <div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
								<strong><?php echo $this->lang->line('xin_list_all');?></strong> <?php echo $this->lang->line('xin_e_details_baccount');?>
							</h5>
							<div class="heading-elements">

								<div class="add-record-btn">
									<button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_add_new');?></button>
								</div>
							</div>
						</div>


						<div class="panel-body">
							<table class="table table-striped" id="xin_table_bank_account">
								<thead>
								<tr>
									<th><?php echo $this->lang->line('xin_e_details_acc_title');?></th>
									<th><?php echo $this->lang->line('xin_e_details_acc_number');?></th>
									<th><?php echo $this->lang->line('xin_e_details_bank_name');?></th>
									<th><?php echo $this->lang->line('xin_e_details_bank_code');?></th>
									<th><?php echo $this->lang->line('xin_e_details_bank_branch');?></th>
								</tr>
								</thead>
							</table>
						</div>
					</div>

				</div>
			<?php } ?>



			<div class="tab-pane has-padding animated fadeInRight" id="contract">
				<div class="panel panel-flat">
					<div class="panel-heading">
						<h5 class="panel-title">
							<strong><?php echo $this->lang->line('xin_list_all');?></strong> <?php echo $this->lang->line('xin_e_details_contracts');?>
						</h5>
					</div>

					<div class="panel-body">
						<table class="table table-striped" id="xin_table_contract" style="width:100%;">
							<thead>
							<tr>
								<th><?php echo $this->lang->line('xin_e_details_duration');?></th>
								<th><?php echo $this->lang->line('xin_e_details_contract_type');?></th>
								<th>File</th>
							</tr>
							</thead>
						</table>
					</div>
				</div>

			</div>



			<div class="tab-pane has-padding animated fadeInRight" id="change_password">
				<div class="panel panel-flat">
					<form id="e_change_password" action="<?php echo site_url("employees/change_password");?>" name="e_change_password" method="post">
						<input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
						<input type="hidden" name="u_basic_info" value="UPDATE">

						<div class="panel-heading">
							<h5 class="panel-title">
								<strong><?php echo $this->lang->line('xin_e_details_eforce');?></strong> <?php echo $this->lang->line('header_change_password');?>
							</h5>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="new_password"><?php echo $this->lang->line('xin_e_details_enpassword');?></label>
										<input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_enpassword');?>" name="new_password" type="password">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="new_password_confirm" class="control-label"><?php echo $this->lang->line('xin_e_details_ecnpassword');?></label>
										<input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_ecnpassword');?>" name="new_password_confirm" type="password">
									</div>
								</div>
								<div class="col-md-12">
									<div class="text-danger">Password must be atleast one lowercase letter, one uppercase letter, one number & one special character.</div>

								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<button type="submit" class="btn bg-teal-400 save pull-right"><?php echo $this->lang->line('xin_save');?></button>
									</div>
								</div>
							</div>
						</div> </form>
				</div>

			</div>


			<!--<div class="tab-pane has-padding animated fadeInRight" id="pay_structure">
						  <div class="panel panel-flat">
  	<div class="panel-heading">
							<h5 class="panel-title">

							<strong><?php echo $this->lang->line('xin_e_details_pay');?></strong>

							</h5>

						</div>
		   <div class="panel-body">
                <div class="row">
                <div class="col-md-12">
				<div class="row">
				   <div class="col-md-2">
                      <div class="form-group">
                        <label for="basic_salary" class="control-label">Basic Salary</label>
                        <input class="form-control salary" placeholder="Basic Salary" name="basic_salary" value="<?php echo @$emp_pay_roll->basic_salary;?>" type="text">
                      </div>
                    </div>
					<div class="col-md-2">
                      <div class="form-group">
                        <label for="house_rent_allowance">Accomodaton</label>
                        <input class="form-control salary allowance" placeholder="Amount" name="house_rent_allowance" value="<?php echo @$emp_pay_roll->house_rent_allowance;?>" type="text">
                      </div>
                    </div>
					 <div class="col-md-2">
                      <div class="form-group">
                        <label for="travelling_allowance">Transport Allowance</label>
                        <input class="form-control salary allowance" placeholder="Amount" name="travelling_allowance" value="<?php echo @$emp_pay_roll->travelling_allowance;?>" type="text">
                      </div>
                     </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="overtime_rate" class="control-label">Food Allowance</label>
						<input class="form-control salary allowance" placeholder="Food Allowance" name="food_allowance" value="<?php echo @$emp_pay_roll->food_allowance;?>" type="text">
                        <input class="form-control" placeholder="Overtime Rate ( Per Hour)" name="overtime_rate" value="" type="hidden">
                      </div>
                    </div>
					<div class="col-md-2">
                      <div class="form-group">
                        <label for="travelling_allowance">Additional Benefits</label>
                        <input class="form-control salary allowance" placeholder="Amount" name="additional_benefits" value="<?php echo @$emp_pay_roll->additional_benefits;?>" type="text">
                      </div>
                    </div>
					<div class="col-md-2">
                      <div class="form-group">
                        <label for="other_allowance">Other</label>
                        <input class="form-control salary allowance" placeholder="Amount" name="other_allowance" value="<?php echo @$emp_pay_roll->other_allowance;?>" type="text">
                      </div>
                    </div>
				</div>

                  <div class="row">  <div class="col-md-2">
                      <div class="form-group">
                        <label for="dearness_allowance">Bonus</label>
                        <input class="form-control salary allowance" placeholder="Amount" name="bonus" value="<?php echo @$emp_pay_roll->bonus;?>" type="text">
                      </div>
                    </div>

					<div class="col-md-2">
                      <div class="form-group">
                        <label for="dearness_allowance">Salary Based On Contract </label>
                        <span class="hidden-print"><input type="text" readonly  value="<?php echo @$emp_pay_roll->salary_based_on_contract;?>" class="form-control"></span>
                      </div>
                    </div>

					<div class="col-md-2">
                      <div class="form-group">
                        <label for="dearness_allowance">Salary With Bonus </label>
                        <span class="hidden-print"><input type="text" readonly  value="<?php echo @$emp_pay_roll->gross_salary;?>" class="form-control"></span>
                      </div>
                    </div>

					<div class="col-md-2">
                      <div class="form-group">
                        <label for="dearness_allowance">Agency Fee</label>
                        <span class="hidden-print"><input type="text" value="<?php echo @$emp_pay_roll->agency_fee;?>" class="form-control"></span>
                      </div>
                    </div>


					 <div class="col-md-2">
            <div class="form-group">
              <label for="effective_from_date">Effective From Date</label>
              <input class="form-control "  placeholder="Effective From Date" name="effective_from_date" type="text" value="<?php echo format_date('d M Y',$emp_pay_roll->effective_from_date);?>">
            </div>
          </div>


		    <input class="form-control" readonly placeholder="Effective To Date" name="effective_to_date" type="hidden" value="" readonly>

					</div>





                </div>
             </div>


          </div>


          </div>
	 </div>-->






		</div>
	</div>


<?php } ?>

	<script>
		<?php //if($reporting_manager!=''){?>
		getDepartmentBasedEmployees('<?php echo $department_id;?>','<?php echo $user_id;?>','<?php echo $reporting_manager;?>');
		<?php //} ?>


		function getDepartmentBasedEmployees(departmentid,uemployeeid,manager_id){

			if (departmentid == "") {
				document.getElementById("reportingmanager").innerHTML = "";
				return;
			} else {
				if (window.XMLHttpRequest) {
					// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				} else {
					// code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {

					if (this.readyState == 4 && this.status == 200) {
						document.getElementById("reportingmanager").innerHTML = this.responseText;

					}
				};

				xmlhttp.open("GET","<?php echo site_url("employees/getdeptemployee/");?>"+departmentid+"/"+uemployeeid+"/"+manager_id,true);
				xmlhttp.send();
			}



		}
		function change_immigration_doc(val){
			//alert(val);
			var htm=$("#document_type_id option:selected").text();
			//alert(htm);
			$('.change_type').val('');
			$('.change_cost').val('');

			if(htm =='Visa'){
				$('#for_visa_select').show();
				$('#for_medical_card_select').hide();
				$('#country_show').hide();
				$('#entry_stamp_date').show();
			}else if(htm =='Medical Card'){
				$('#for_medical_card_select').show();
				$('#for_visa_select').hide();
				$('#country_show').hide();
				$('#entry_stamp_date').hide();
			}else if(htm =='Passport'){
				$('#country_show').show();
				$('#for_medical_card_select').hide();
				$('#for_visa_select').hide();
				$('#entry_stamp_date').hide();
			}else{
				$('#for_medical_card_select').hide();
				$('#for_visa_select').hide();
				$('#country_show').hide();
				$('#entry_stamp_date').hide();
			}
		}

		getTenure();
		function getTenure(){
			// Here are the two dates to compare dd/mm/yy
			var joiningdate = document.getElementById("joiningdate").value;
			var leavingdate = document.getElementById("date_of_leaving").value;
			var myDate = new Date(joiningdate);  //1991-05-26
			var date1=myDate.getFullYear() + "-" +  (myDate.getMonth() + 1) + "-" +  myDate.getDate();
			if(joiningdate==''){
				document.getElementById("tenure").value='0';
				document.getElementById("tenure1").value='0';

				return false;
			}
			if(leavingdate==''){
				var today = new Date();
			}else{
				var today=new Date(leavingdate);
			}
			var dd = today.getDate();
			var mm = today.getMonth()+1; //January is 0!

			var yyyy = today.getFullYear();
			if(dd<10){
				dd='0'+dd;
			}
			if(mm<10){
				mm='0'+mm;
			}
			var date2 = yyyy+'-'+mm+'-'+dd;


			// First we split the values to arrays date1[0] is the year, [1] the month and [2] the day
			date1 = date1.split('-');
			date2 = date2.split('-');

			// Now we convert the array to a Date object, which has several helpful methods
			date1 = new Date(date1[0], date1[1], date1[2]);
			date2 = new Date(date2[0], date2[1], date2[2]);
			//console.log(date1);
			//console.log(date2);
			// We use the getTime() method and get the unixtime (in milliseconds, but we want seconds, therefore we divide it through 1000)
			date1_unixtime = parseInt(date1.getTime() / 1000);
			date2_unixtime = parseInt(date2.getTime() / 1000);
			var showmsg=Noofmonths(date1,date2);
			// This is the calculated difference in seconds
			var timeDifference = date2_unixtime - date1_unixtime;

			// in Hours
			var timeDifferenceInHours = timeDifference / 60 / 60;

			// and finaly, in days :)
			var timeDifferenceInDays = timeDifferenceInHours  / 24;
			var tenure = timeDifferenceInDays/365;
			document.getElementById("tenure").value=tenure;
			document.getElementById("tenure1").value=showmsg;
			var user_id=<?php echo $user_id;?>;

			if (window.XMLHttpRequest) {
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp = new XMLHttpRequest();
			} else {
				// code for IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {

				/*if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("reportingmanager").innerHTML = this.responseText;

                }*/
			};

			xmlhttp.open("GET","<?php echo site_url("employees/updateemployeetenure/");?>"+user_id+"/"+tenure,true);
			xmlhttp.send();

		}

		function Noofmonths(date1,date2) {
			var df= date1;
			var dt = date2;
			var allMonths= dt.getMonth() - df.getMonth() + (12 * (dt.getFullYear() - df.getFullYear()));
			var allYears= dt.getFullYear() - df.getFullYear();
			var partialMonths = dt.getMonth() - df.getMonth();
			if (partialMonths < 0) {
				allYears--;
				partialMonths = partialMonths + 12;
			}
			var total = allYears + " years & " + partialMonths + " months";
			//var totalMonths = "A total of " + allMonths + " between the dates.";
			return total;
			//console.log(totalMonths);
		}

	</script>
	<style>
		#for_visa_select,#for_medical_card_select,#country_show, #entry_stamp_date{display:none;}
	</style>

<?php if($session['role_name']!=AD_ROLE){?>
	<script>
		$(document).ready(function(){
			$('form#basic_info input,form#basic_info select').attr("disabled", true);
		});
	</script>
<?php }?>
