<?php
/* Settings view
*/

foreach ($email_settings as $em) {
  
  if($em->settings_name == 'Salary Certificate'){
    $salary_certificate_email = json_decode($em->settings_value,1);
  }
}

?>
<?php $session = $this->session->userdata('username');?>

<?php if(in_array('53v',role_resource_ids())) {?>

<div class="row">
						<div class="col-md-12">
							<div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>System Settings</strong>
									</h6>
									<!--<div class="heading-elements">
										<ul class="icons-list">
					                		<li><a data-action="collapse"></a></li>

					                	</ul>
				                	</div>-->
								</div>

								<div class="panel-body">
									<div class="tabbable nav-tabs-vertical nav-tabs-left">
										<ul class="nav nav-tabs nav-tabs-highlight">
					<li id="config_1" class="active"><a href="#general" data-toggle="tab"><i class="icon-cog position-left"></i>General</a></li>
					<li id="config_2"><a href="#company_logo" data-toggle="tab"><i class="icon-camera position-left"></i>Logos</a></li>
					<li id="config_3"><a href="#system" data-toggle="tab"><i class="icon-cogs position-left"></i>System</a></li>
					<li id="config_4"><a href="#role" data-toggle="tab"><i class="icon-cog position-left"></i>Role</a></li>
					<li id="config_5"><a href="#attendance" data-toggle="tab"><i class="icon-calendar3 position-left"></i>Attendance</a></li>
					<li id="config_10"><a href="#payroll" data-toggle="tab"><i class="icon-calculator3 position-left"></i>Payroll</a></li>
					<li id="config_6"><a href="#job" data-toggle="tab"><i class="icon-megaphone position-left"></i>Recruitment</a></li>
					<li id="config_7"><a href="#email" data-toggle="tab"><i class="icon-mail5 position-left"></i>Email Notifications & Alerts</a></li>
					<li id="config_8"><a href="#animation" data-toggle="tab"><i class="icon-diamond position-left"></i>Animation Effects</a></li>
          <li id="config_9"><a href="#notification" data-toggle="tab"><i class="icon-bubble-notification position-left"></i>Notification Position</a></li>
					<li id="config_9"><a href="#email_settings" data-toggle="tab"><i class="icon-mail-read position-left"></i>Email Settings</a></li>





						</ul>

										<div class="tab-content">
											<div class="tab-pane active has-padding animated fadeInRight" id="general">

   <form id="company_info" action="<?php echo site_url("settings/company_info").'/'.$company_info_id ?>/" name="company_info" method="post">
      <input type="hidden" name="u_company_info" value="UPDATE">
      <div class="panel panel-flat">

  	<div class="panel-heading">
							<h5 class="panel-title">

							<strong>General</strong> Configuration

							</h5>

						</div>

        <div class="panel-body">
          <div class="col-sm-6 no-padding-left">
            <div class="form-group">
              <label for="company_name">Company Name</label>
              <input class="form-control" placeholder="Company Name" name="company_name" type="text" value="<?php echo $company_name;?>">
            </div>
            <div class="form-group">
              <label for="contact_person">Contact Person</label>
              <input class="form-control" placeholder="Contact Person" name="contact_person" type="text" value="<?php echo $contact_person;?>">
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input class="form-control" placeholder="Email" name="email" type="email" value="<?php echo $email;?>">
            </div>

			<?php $phone=@explode('-',@$phone);?>

			  <div class="form-group">
			  <label for="contact_no" class="control-label">Phone</label>
			  <div class="clearfix"></div>
		   <div class="input-group">
												<span class="input-group-addon-custom">


						 <select class="form-control change_country_code js-example-templating" name="country_code" required>
						  <?php foreach(phone_numbers_code() as $keys=>$phone_code){?>
						  <option <?php if($keys==@$phone[0]){echo 'selected';}?> value="<?php echo $keys; ?>-" data-len ="<?php echo $phone_code['length'];?>"   rel="<?php echo $phone_code['country_name'];?>"><?php echo $keys; ?></option>
						  <?php } ?>
						  </select>
						  </span>
              <input class="form-control" placeholder="Phone" name="phone"  type="text" value="<?php echo $phone[1];?>" pattern="\d*" maxlength="9">
			  </div>


            </div>


          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="address">Address</label>
              <input class="form-control" placeholder="Address Line 1" name="address_1" type="text" value="<?php echo $address_1;?>">
              <br>
              <input class="form-control" placeholder="Address Line 2" name="address_2" type="text" value="<?php echo $address_2;?>">
              <br>
              <div class="row">
                <div class="col-xs-5">
                  <input class="form-control" placeholder="City" name="city" type="text" value="<?php echo $city;?>">
                </div>
                <div class="col-xs-4">
                  <input class="form-control" placeholder="State" name="state" type="text" value="<?php echo $state;?>">
                </div>
                <div class="col-xs-3">
                  <input class="form-control" placeholder="Zipcode" name="zipcode" type="text" value="<?php echo $zipcode;?>">
                </div>
              </div>
              <br>
              <select class="form-control" name="country" data-plugin="select_hrm" data-placeholder="Country">
                <option value="">Select One</option>
                <?php foreach($all_countries as $scountry) {?>
                <option value="<?php echo $scountry->country_id;?>" <?php if($country==$scountry->country_id):?> selected <?php endif;?>> <?php echo $scountry->country_name;?></option>
                <?php } ?>
              </select>
            </div>
            <input name="config_type" type="hidden" value="general">
          </div>

		  <?php if(in_array('53e',role_resource_ids())){ ?>

		  <div class="col-lg-12">
		    <button type="submit" class="btn bg-teal-400 save pull-right"><?php echo $this->lang->line('xin_save');?></button>
		  </div>

		  <?php } ?>

        </div>

      </div>
    </form>

   									</div>

			<div class="tab-pane has-padding animated fadeInRight" id="company_logo">
			     <div class="panel panel-flat">

  	<div class="panel-heading">
							<h5 class="panel-title">

							<strong>System Logo</strong>

							</h5>

						</div>

      <form id="logo_info" action="<?php echo site_url("settings/logo_info").'/'.$company_info_id ?>/" name="logo_info" method="post">
        <input type="hidden" name="company_logo" value="UPDATE">
       <div class="panel-body">

          <div class="col-md-6 no-padding-left">
            <div class='form-group'>
              <h6>First Logo</h6>

              <input type="file" name="p_file" class="file-input" id="p_file"><br>

              <?php if($logo!='' && $logo!='no file') {?>
              <img src="<?php echo base_url().'uploads/logo/'.$logo;?>"  id="u_file">
              <?php } else {?>
              <img src="<?php echo base_url().'uploads/logo/no_logo.png';?>"  id="u_file">
              <?php } ?>
              <br><br>
              <small>- Upload files only: gif,png,jpg,jpeg</small><br />
              <small>- Best Size: 160x40</small><br />
              <small>- White background with black text</small> </div>
          </div>
          <div class="col-md-6">
            <div class='form-group'>
              <h6>Second Logo</h6>

              <input type="file" name="p_file2" class="file-input" id="p_file2"><br>

              <?php if($logo_second!='' && $logo_second!='no file') {?>
              <img src="<?php echo base_url().'uploads/logo/'.$logo_second;?>" id="u_file2">
              <?php } else {?>
              <img src="<?php echo base_url().'uploads/logo/no_logo.png';?>"  id="u_file2">
              <?php } ?>
              <br><br>
              <small>- Upload files only: gif,png,jpg,jpeg</small><br />
              <small>- Best Size: 160x40</small><br />
              <small>- Transparent background with white text</small> </div>
          </div>
          <div class="col-md-6 no-padding-left">
            <div class='form-group'>
              <h6>Favicon</h6>

              <input type="file" class="file-input" name="favicon" id="favicon"><br>

              <?php if($logo_second!='' && $logo_second!='no file') {?>
              <img src="<?php echo base_url().'uploads/logo/'.$logo_second;?>"  id="favicon1">
              <?php } else {?>
              <img src="<?php echo base_url().'uploads/logo/no_logo.png';?>" id="favicon1">
              <?php } ?>
              <br><br>
              <small>- Upload files only: gif,ico,png</small><br />
              <small>- Best Size: 16x16</small></div>
          </div>
       <?php if(in_array('53e',role_resource_ids())){ ?>
		     <button type="submit" class="btn pull-right bg-teal-400 save"><?php echo $this->lang->line('xin_save');?><i class="icon-spinner3 spinner position-left"></i></button>
	   <?php } ?>
		</div>


      </form>
    </div>
      <div class="panel panel-flat">

  	<div class="panel-heading">
							<h5 class="panel-title">

							<strong>Sign In Page</strong> Logo

							</h5>


	</div>
	<div class="panel-body">
      <form id="singin_logo" name="singin_logo" method="post" enctype="multipart/form-data">
        <input type="hidden" name="company_logo" value="UPDATE">

          <div class="col-md-6 no-padding-left">
            <div class='form-group'>
              <h6>Logo</h6>

              <input type="file" name="p_file3" class="file-input" id="p_file3">
              <br>
              <?php if($sign_in_logo!='' && $sign_in_logo!='no file') {?>
              <img src="<?php echo base_url().'uploads/logo/signin/'.$sign_in_logo;?>"  id="u_file3">
              <?php } else {?>
              <img src="<?php echo base_url().'uploads/logo/no_logo.png';?>"  id="u_file3">
              <?php } ?>
              <br><br>
              <small>- Upload files only: gif,png,jpg,jpeg</small><br />
              <small>- Best Size: 160x40</small><br />
              <small>- Transparent background with white text</small> </div>
          </div>

  <?php if(in_array('53e',role_resource_ids())){ ?>
         <button type="submit" class="btn pull-right bg-teal-400 save"><?php echo $this->lang->line('xin_save');?><i class="icon-spinner3 spinner position-left"></i></button>
  <?php } ?>


      </form>


	</div>
	</div>

			<div>
								</div>
									</div>

					<div class="tab-pane has-padding animated fadeInRight" id="system">
					   <form id="system_info" action="<?php echo site_url("settings/system_info").'/'.$company_info_id ?>/" name="system_info" method="post">
      <input type="hidden" name="u_basic_info" value="UPDATE">
      <div class="panel panel-flat">

  	<div class="panel-heading">
							<h5 class="panel-title">

							<strong>System</strong> Configuration

							</h5>

						</div>
<div class="panel-body">
        <div class="col-sm-6 no-padding-left">
          <div class="form-group">
            <label for="company_name">Application Name</label>
            <input class="form-control" placeholder="Application Name" name="application_name" type="text" value="<?php echo $application_name;?>" id="application_name">
          </div>
          <!--<div class="form-group">
            <label for="email">Default Currency</label>
            <select class="form-control select2-hidden-accessible" name="default_currency_symbol" data-plugin="select_hrm" data-placeholder="Default Currency Symbol" tabindex="-1" aria-hidden="true">
              <option value="">Select One</option>
              <?php foreach($this->Xin_model->get_currencies() as $currency){?>
              <?php $_currency = $currency->code.' - '.$currency->symbol;?>
              <option value="<?php echo $_currency;?>" <?php if($default_currency_symbol==$_currency):?> selected <?php endif;?>> <?php echo $_currency;?></option>
              <?php } ?>
            </select>
          </div>-->
          <div class="form-group">
            <label for="phone">Default Currency (Symbol/Code)</label>
            <select class="form-control" name="show_currency" data-plugin="select_hrm" data-placeholder="Show Currency">
              <option value="">Select One</option>
              <option value="code" <?php if($show_currency=='code'){?> selected <?php }?>>Currency Code</option>
              <option value="symbol" <?php if($show_currency=='symbol'){?> selected <?php }?>>Currency Symbol</option>
            </select>
          </div>
          <div class="form-group">
            <label for="phone">Currency Position</label>
            <input type="hidden" name="notification_position" value="Bottom Left">
            <input type="hidden" name="enable_registration" value="no">
            <input type="hidden" name="login_with" value="username">
            <select class="form-control" name="currency_position" data-plugin="select_hrm" data-placeholder="Currency Position">
              <option value="">Select One</option>
              <option value="Prefix" <?php if($currency_position=='Prefix'){?> selected <?php }?>>Prefix</option>
              <option value="Suffix" <?php if($currency_position=='Suffix'){?> selected <?php }?>>Suffix</option>
            </select>
          </div>
          <div class="form-group">
            <label for="contact_role">Enable CodeIgniter page rendered on footer</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="switchery" id="enable_page_rendered" <?php if($enable_page_rendered=='yes'):?> checked="checked" <?php endif;?> value="yes" />
            </div>
          </div>


        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label for="company_name">Date Format</label>
            <br>
            <label class="custom-control custom-radio">
              <input id="date_format" name="date_format" type="radio" class="styled" value="d-m-Y" <?php if($date_format_xi=='d-m-Y'){?> checked <?php }?>>
              <span class="custom-control-indicator"></span> <span class="custom-control-description">dd-mm-YYYY (<?php echo date('d-m-Y');?>)</span> </label>
            <br>
            <label class="custom-control custom-radio">
              <input id="date_format" name="date_format" type="radio" class="styled" value="m-d-Y" <?php if($date_format_xi=='m-d-Y'){?> checked <?php }?>>
              <span class="custom-control-indicator"></span> <span class="custom-control-description">mm-dd-YYYY (<?php echo date('m-d-Y');?>)</span> </label>
            <br>
            <label class="custom-control custom-radio">
              <input id="date_format" name="date_format" type="radio" class="styled" value="d-M-Y" <?php if($date_format_xi=='d-M-Y'){?> checked <?php }?>>
              <span class="custom-control-indicator"></span> <span class="custom-control-description">dd-MM-YYYY (<?php echo date('d-M-Y');?>)</span> </label>
            <br>
            <label class="custom-control custom-radio">
              <input id="date_format" name="date_format" type="radio" class="styled" value="M-d-Y" <?php if($date_format_xi=='M-d-Y'){?> checked <?php }?>>
              <span class="custom-control-indicator"></span> <span class="custom-control-description">MM-dd-YYYY (<?php echo date('M-d-Y');?>)</span> </label>
          </div>
          <div class="form-group">
            <label for="footer_text">Footer Text</label>
            <input class="form-control" placeholder="Footer Text" name="footer_text" type="text" value="<?php echo $footer_text;?>">
          </div>
          <div class="form-group">
            <label for="contact_role">Enable current year on footer</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="switchery" id="enable_current_year" <?php if($enable_current_year=='yes'):?> checked="checked" <?php endif;?> value="yes" />
            </div>
          </div>
		  <div class="form-group">
            <label for="contact_role">Enable Tax Calculation</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="switchery" id="enable_tax_calculation" <?php if($enable_tax_calculation=='yes'):?> checked="checked" <?php endif;?> value="yes" />
            </div>
          </div>


        </div>

		<?php if(in_array('53e',role_resource_ids())){ ?>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <div class="text-right">
                <button type="submit" class="btn bg-teal-400 save"><?php echo $this->lang->line('xin_save');?><i class="icon-spinner3 spinner position-left"></i></button>
              </div>
            </div>
          </div>
        </div>
		<?php } ?>
	    </div>
	  </div>
    </form>

					</div>
					<div class="tab-pane has-padding animated fadeInRight" id="role">
					    <form id="role_info" action="<?php echo site_url("settings/role_info").'/'.$company_info_id ?>/" name="role_info" method="post">
      <input type="hidden" name="u_basic_info" value="UPDATE">
       <div class="panel panel-flat">

  	<div class="panel-heading">
							<h5 class="panel-title">

							<strong>Role</strong> Configuration

							</h5>


	</div>
<div class="panel-body">
        <div class="col-sm-6 no-padding-left">
          <div class="form-group">
            <label for="contact_role">Employee can manage own contact information</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="switchery" data-size="small" data-color="#3e70c9" data-secondary-color="#ddd" id="contact_role" <?php if($employee_manage_own_contact=='yes'):?> checked="checked" <?php endif;?> value="yes" />
            </div>
          </div>
          <div class="form-group">
            <label for="bank_account_role">Employee can manage own bank account</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="switchery" data-size="small" data-color="#3e70c9" data-secondary-color="#ddd" id="bank_account_role" <?php if($employee_manage_own_bank_account=='yes'):?> checked="checked" <?php endif;?> value="yes">
            </div>
          </div>
          <div class="form-group">
            <label for="edu_role">Employee can manage own qualification</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="switchery" data-size="small" data-color="#3e70c9" data-secondary-color="#ddd" id="edu_role" <?php if($employee_manage_own_qualification=='yes'):?> checked="checked" <?php endif;?> value="yes">
            </div>
          </div>
          <div class="form-group">
            <label for="work_role">Employee can manage own work experience</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="switchery" data-size="small" data-color="#3e70c9" data-secondary-color="#ddd" id="work_role" <?php if($employee_manage_own_work_experience=='yes'):?> checked="checked" <?php endif;?> value="yes">
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label for="doc_role">Employee can manage own documents, Contracts & Paystructure</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="switchery" data-size="small" data-color="#3e70c9" data-secondary-color="#ddd" id="doc_role" <?php if($employee_manage_own_document=='yes'):?> checked="checked" <?php endif;?> value="yes">
            </div>
          </div>
          <div class="form-group">
            <label for="pic_role">Employee can manage own profile picture</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="switchery" data-size="small" data-color="#3e70c9" data-secondary-color="#ddd" id="pic_role" <?php if($employee_manage_own_picture=='yes'):?> checked="checked" <?php endif;?> value="yes">
            </div>
          </div>
          <div class="form-group">
            <label for="profile_role">Employee can manage own profile information</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="switchery" data-size="small" data-color="#3e70c9" data-secondary-color="#ddd" id="profile_role" <?php if($employee_manage_own_profile=='yes'):?> checked="checked" <?php endif;?> value="yes">
            </div>
          </div>
          <!--<div class="form-group">
            <label for="social_role">Employee can manage own social information</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="switchery" data-size="small" data-color="#3e70c9" data-secondary-color="#ddd" id="social_role" <?php //if($employee_manage_own_social=='yes'):?> checked="checked" <?php //endif;?> value="yes">
            </div>
          </div>-->
        </div>

		<?php if(in_array('53e',role_resource_ids())){ ?>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <div class="text-right">
                <button type="submit" class="btn bg-teal-400 save"><?php echo $this->lang->line('xin_save');?><i class="icon-spinner3 spinner position-left"></i></button>
              </div>
            </div>
          </div>
        </div>

		<?php } ?>

 </div>
	 </div>
    </form>

					</div>
					<div class="tab-pane has-padding animated fadeInRight" id="attendance">
					    <form id="attendance_info" action="<?php echo site_url("settings/attendance_info").'/'.$company_info_id ?>/" name="attendance_info" method="post">
      <input type="hidden" name="u_basic_info" value="UPDATE">
         <div class="panel panel-flat">

  	<div class="panel-heading">
							<h5 class="panel-title">

							<strong>Attendance Configuration</strong>

							</h5>


	</div>
      	<div class="panel-body">


<div class="col-sm-6 no-padding-left">
		   <div class="form-group">
		   <label for="phone">Maximum Working Hour(s)</label>
            <input class="form-control timepicker" placeholder="Maximum Working Hour(s)" readonly name="maximum_working_hours" type="text" value="<?php echo $maximum_working_hours;?>">
             </div>


		   <div class="form-group">
<label class="control-label" for="tooltip">Deduct Hour(s) <span data-toggle="tooltip" data-placement="top" title="" data-original-title="When there is no logout time this should be deduct from the employee total working hours."><i class="icon-bubble-notification" style="margin-top: -10px;"></i></span></label>
 <input class="form-control timepicker" placeholder="Deduct Hour(s)" readonly name="deduct_hours" type="text" value="<?php echo $deduct_hours;?>">

          </div>

		  	  <div class="form-group">
          <label for="name">Leadership Group <span data-toggle="tooltip" data-placement="top" title="" data-original-title="Late, EarlyLeaving, OT not calculating to these employees"><i class="icon-bubble-notification" style="margin-top: -10px;"></i></span></label>
          <select name="exceptional_employees[]" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="Choose..." multiple="multiple">
            <option value=""></option>
            <?php
			 foreach($this->Employees_model->get_employees_with_biometric() as $employee) {
				if(in_array($employee->user_id,$exceptional_employees)){
					$selected="selected";
				}else{
					$selected='';

				}
				?>
            <option value="<?php echo $employee->user_id;?>" <?php echo $selected;?>> <?php echo change_fletter_caps($employee->first_name.' '.$employee->middle_name.' '.$employee->last_name);?></option>
            <?php } ?>
          </select>
        </div>

	<div class="form-group">
		<label for="name">Flexible Employees <span data-toggle="tooltip" data-placement="top" title="" data-original-title="Timings are flexible, can come late or come early but have to complete working hours"><i class="icon-bubble-notification" style="margin-top: -10px;"></i></span></label>
		<select name="flexible_employees[]" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="Choose..." multiple="multiple">
			<option value=""></option>
			<?php
			foreach($this->Employees_model->get_employees_with_biometric() as $employee) {
				if(in_array($employee->user_id,$flexible_employees)){
					$selected="selected";
				}else{
					$selected='';

				}
				?>
				<option value="<?php echo $employee->user_id;?>" <?php echo $selected;?>> <?php echo change_fletter_caps($employee->first_name.' '.$employee->middle_name.' '.$employee->last_name);?></option>
			<?php } ?>
		</select>
	</div>
		    </div>

			<div class="col-sm-6">
		  		   <div class="form-group">
				   <label for="phone">Lunch Hour(s)</label>
            <input class="form-control timepicker" placeholder="Lunch Hour(s)" readonly name="lunch_hours" type="text" value="<?php echo $lunch_hours;?>">

          </div>
		 </div>

		 <div class="col-sm-6">
		  		   <div class="form-group">
				   <label for="phone">Grace Hour(s)</label>
            <input class="form-control timepicker" placeholder="Grace Hour(s)" readonly name="grace_hours" type="text" value="<?php echo $grace_hours;?>">

          </div>
		 </div>

		 <div class="col-sm-6">
                  <div class="form-group">
				  <label for="name">Department Eligible For Grace Hour <span data-toggle="tooltip" data-placement="top" title="" data-original-title="Eligible for <?php echo $grace_hours;?> mins grace period for these departments."><i class="icon-bubble-notification" style="margin-top: -10px;"></i></span></label>
                    <select name="grace_departments[]" class="form-control" data-placeholder="Choose..." data-plugin="select_hrm" multiple="multiple">
                      <option value=""></option>
                      <?php foreach($grace_dep_loc as $key=>$dept) {
						  if(in_array($key,$grace_departments)){
								$selected1="selected";
							}else{
								$selected1='';

							}
						  ?>
                      <option value="<?php echo $key;?>" <?php echo $selected1;?>><?php echo $dept;?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>


				<div class="col-lg-6">
				<div class="form-group">
		   <label for="phone">Minimum Delivery Counts <span data-toggle="tooltip" data-placement="top" title="" data-original-title="Minimum delivery counts for calculating drivers attendance."><i class="icon-bubble-notification" style="margin-top: -10px;"></i></span></label>
            <input class="form-control" placeholder="Minimum Delivery Count(s)"  name="minimum_delivery_counts" min="0" type="number" value="<?php echo $minimum_delivery_counts;?>">
             </div>
				</div>

				<div class="col-lg-6">
				<div class="form-group">
		   <label for="phone">Order Cancellation Percentage (%)</label>
            <input class="form-control" placeholder="Order Cancellation Percentage (%)"  name="order_cancellation_percentage" min="0" step="any" type="number" value="<?php echo $order_cancellation_percentage;?>">
             </div>
				</div>

				<div class="col-lg-6">
				<div class="form-group">
		   <label for="phone">Monthly Target Count</label>
            <input class="form-control" placeholder="Monthly Target Count"  name="monthly_target_count" min="0" step="1" type="number" value="<?php echo $monthly_target_count;?>">
             </div>
				</div>

    </div>


		<div class="panel-heading">
							<h5 class="panel-title">

							<strong>Leave Request CEO</strong>

							</h5>


	</div>
	<div class="panel-body">
	 <div class="col-sm-6 no-padding-left">
	 <div class="form-group">
          <label for="name">Leave Approval Employees <span data-toggle="tooltip" data-placement="top" title="" data-original-title="These employees leave request goes to CEO for further approval"><i class="icon-bubble-notification" style="margin-top: -10px;"></i></span></label>
          <select name="employee_approval_to_ceo[]" id="select2-demo-7" class="form-control" data-plugin="select_hrm" data-placeholder="Choose..." multiple="multiple">
            <option value=""></option>
            <?php
			 foreach($this->Employees_model->get_employees_with_biometric() as $employee) {
				if(in_array($employee->user_id,$employee_approval_to_ceo)){
					$selected="selected";
				}else{
					$selected='';

				}
				?>
            <option value="<?php echo $employee->user_id;?>" <?php echo $selected;?>> <?php echo change_fletter_caps($employee->first_name.' '.$employee->middle_name.' '.$employee->last_name);?></option>
            <?php } ?>
          </select>
        </div>

	 </div>
	</div>


	<div class="panel-heading">
							<h5 class="panel-title">

							<strong>Final & Leave Settlement Configuration</strong>

							</h5>


	</div>
      	<div class="panel-body">
		 <div class="col-sm-6 no-padding-left">
                  <div class="form-group">
				  <label for="name">Visa Eligible For Final & Leave Settlement <span data-toggle="tooltip" data-placement="top" title="" data-original-title="Those who are under this visa eligible for getting Final & Leave Settlement."><i class="icon-bubble-notification" style="margin-top: -10px;"></i></span></label>
                    <select name="eligible_visa_under[]" class="form-control" data-placeholder="Choose..." data-plugin="select_hrm" multiple="multiple">
                      <option value=""></option>
                      <?php foreach($this->Xin_model->get_visa_under()->result() as $visa_under) {
						  if(in_array($visa_under->type_id,$eligible_visa_under)){
								$selected1="selected";
							}else{
								$selected1='';

							}
						  ?>
                      <option value="<?php echo $visa_under->type_id;?>" <?php echo $selected1;?>><?php echo $visa_under->type_name;?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>




		<?php if(in_array('53e',role_resource_ids())){ ?>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <div class="text-right">
                <button type="submit" class="btn bg-teal-400 save"><?php echo $this->lang->line('xin_save');?><i class="icon-spinner3 spinner position-left"></i></button>
              </div>
            </div>
          </div>
        </div>
		  <?php } ?>

    </div>



	</div>
    </form>

					</div>
					<div class="tab-pane has-padding animated fadeInRight" id="payroll">
					 <div class="panel panel-flat">

  	<div class="panel-heading">
							<h5 class="panel-title">

							<strong>Payroll</strong>Logo <small>(for pdf)</small>

							</h5>


	</div>
      <form id="payroll_logo" name="payroll_logo" method="post" enctype="multipart/form-data">
        <input type="hidden" name="payroll_logo" value="UPDATE">

        <div class="panel-body">
          <div class="col-md-6 no-padding-left">
            <div class='form-group'>
              <h6>Logo</h6>

              <input type="file" name="p_file5" class="file-input" id="p_file5">
            <br>
              <?php if($payroll_logo!='' && $payroll_logo!='no file') {?>
              <img src="<?php echo base_url().'uploads/logo/payroll/'.$payroll_logo;?>" id="u_file5">
              <?php } else {?>
              <img src="<?php echo base_url().'uploads/logo/no_logo.png';?>" id="u_file5">
              <?php } ?>
              <br><br>
              <small>- Upload files only: gif,png,jpg,jpeg</small><br />
              <small>- Best Size: 160x40</small><br />
              <small>- White background with black text</small> </div>
          </div>

          <div class="col-md-12">
		  <?php if(in_array('53e',role_resource_ids())){ ?>
            <div class="text-right">
              <button type="submit" class="btn bg-teal-400 save"><?php echo $this->lang->line('xin_save');?><i class="icon-spinner3 spinner position-left"></i></button>
            </div>
          <?php } ?>
        </div>
	  </div>


      </form>
    </div>

					</div>
					<div class="tab-pane has-padding animated fadeInRight" id="job">
					  	 <div class="panel panel-flat">

  	<div class="panel-heading">
							<h5 class="panel-title">

							<strong>Recruitment</strong> Configuration

							</h5>


	</div>
      <form id="job_info" action="<?php echo site_url("settings/job_info").'/'.$company_info_id ?>/" name="job_info" method="post">
        <input type="hidden" name="user_id" value="<?php //echo $row['user_id'];?>">
        <input type="hidden" name="u_basic_info" value="UPDATE">
   <div class="panel-body">
        <div class="col-sm-12 no-padding-left">
          <div class="form-group">
            <label for="enable_job">Enable Jobs for employees</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="switchery" data-size="small" data-color="#3e70c9" data-secondary-color="#ddd" id="enable_job2" <?php if($enable_job_application_candidates=='yes'):?> checked="checked" <?php endif;?> value="yes">
            </div>
          </div>
          <div class="form-group">
            <label for="job_application_format">Job Application file format</label>
            <br>

			<?php
					$job_application_format=explode(',',$job_application_format);

					?>
			<!--<input type="text" value="<?php echo $job_application_format;?>" data-role="tagsinput" name="job_application_format">-->
			<select multiple class="form-control" name="job_application_format[]" data-plugin="select_hrm"
			data-placeholder="file format">

													<option <?php if(in_array('pdf',$job_application_format)){ echo "selected='selected'";} ?> value="pdf">PDF</option>
													<option <?php if(in_array('doc',$job_application_format)){ echo "selected='selected'";} ?> value="doc">DOC</option>
													<option <?php if(in_array('jpeg',$job_application_format)){ echo "selected='selected'";} ?> value="jpeg">JPEG</option>
													<option <?php if(in_array('txt',$job_application_format)){ echo "selected='selected'";} ?>  value="txt">TXT</option>
													<option <?php if(in_array('excel',$job_application_format)){ echo "selected='selected'";} ?> value="excel">EXCEL</option>
												    <option <?php if(in_array('jpg',$job_application_format)){ echo "selected='selected'";} ?> value="jpg">JPG</option>
													<option <?php if(in_array('docx',$job_application_format)){ echo "selected='selected'";} ?> value="docx">DOCX</option>

											</select>
          </div>
        </div>

		<?php if(in_array('53e',role_resource_ids())){ ?>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <div class="text-right">
                <button type="submit" class="btn bg-teal-400 save"><?php echo $this->lang->line('xin_save');?><i class="icon-spinner3 spinner position-left"></i></button>
              </div>
            </div>
          </div>
        </div>

		<?php }
		?>
     </div>

	 </form>
    </div>
     <div class="panel panel-flat">

  	<div class="panel-heading">
							<h5 class="panel-title">

							<strong>Job Listing</strong> Logo <small>(frontend)</small>

							</h5>


	</div>
      <form id="job_logo" name="job_logo" method="post" enctype="multipart/form-data">
        <input type="hidden" name="job_logo" value="UPDATE">
 <div class="panel-body">
        <div class="row">
          <div class="col-md-6">
            <div class='form-group'>
              <h6>Logo</h6>

              <input type="file" class="file-input" name="p_file4" id="p_file4">
               <br>
              <?php if($job_logo!='' && $job_logo!='no file') {?>
              <img src="<?php echo base_url().'uploads/logo/job/'.$job_logo;?>" width="70px" style="margin-left:30px;" id="u_file4">
              <?php } else {?>
              <img src="<?php echo base_url().'uploads/logo/no_logo.png';?>" width="70px" style="margin-left:30px;" id="u_file4">
              <?php } ?>
              <br>  <br>
              <small>- Upload files only: gif,png,jpg,jpeg</small><br />
              <small>- Best Size: 230x60</small><br />
              <small>- White background with black text</small> </div>
          </div>
        </div>
		<?php if(in_array('53e',role_resource_ids())){ ?>
        <div class="row">
          <div class="col-md-12">
            <div class="text-right">
              <button type="submit" class="btn bg-teal-400 save"><?php echo $this->lang->line('xin_save');?><i class="icon-spinner3 spinner position-left"></i></button>
            </div>
          </div>
        </div>
		<?php } ?>
  </div>
	 </form>
    </div>

					</div>
					<div class="tab-pane has-padding animated fadeInRight" id="email">
					   <form id="email_info" action="<?php echo site_url("settings/email_info").'/'.$company_info_id ?>/" name="email_info" method="post">
      <input type="hidden" name="u_basic_info" value="UPDATE">
      <div class="box box-block bg-white">
        <h2>Email Notifications Configuration</h2>
        <div class="col-sm-6 no-padding-left">
          <div class="form-group">
            <label for="company_name">Welcome email notifications</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="switchery" data-size="small" data-color="#3e70c9" name="welcome_email" data-secondary-color="#ddd" id="srole_email_notification" <?php if($enable_email_notification->welcome_email=='yes'):?> checked="checked" <?php endif;?> value="yes">
            </div>
          </div>
        </div>
		<div class="col-sm-6">
          <div class="form-group">
            <label for="company_name">Forgot password email notifications</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="switchery" data-size="small" data-color="#3e70c9" name="forgot_password" data-secondary-color="#ddd" <?php if($enable_email_notification->forgot_password=='yes'):?> checked="checked" <?php endif;?> value="yes">
            </div>
          </div>
        </div>
		<div class="col-sm-6 no-padding-left">
          <div class="form-group">
            <label for="company_name">Missed login/logout email notifications</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="switchery" data-size="small" data-color="#3e70c9" name="missed_loginout" data-secondary-color="#ddd" <?php if($enable_email_notification->missed_loginout=='yes'):?> checked="checked" <?php endif;?> value="yes">
            </div>
          </div>
        </div>
		<div class="col-sm-6">
          <div class="form-group">
            <label for="company_name">Leave email notifications</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="switchery" data-size="small" data-color="#3e70c9" name="leave_email" data-secondary-color="#ddd" <?php if($enable_email_notification->leave_email=='yes'):?> checked="checked" <?php endif;?> value="yes">
            </div>
          </div>
        </div>

        <div class="col-sm-6 no-padding-left">
          <div class="form-group">
            <label for="company_name">Birthday email notifications</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="switchery" data-size="small" data-color="#3e70c9" name="birthday_email" data-secondary-color="#ddd" <?php if($enable_email_notification->birthday_email=='yes'):?> checked="checked" <?php endif;?> value="yes">
            </div>
          </div>
        </div>


		<div class="clearfix"></div>
		 <h2>Alerts</h2>
        <div class="col-sm-6 no-padding-left">
          <div class="form-group">
            <label for="company_name">Enable missed login notifications</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="switchery" data-size="small" data-color="#3e70c9" data-secondary-color="#ddd" id="srole_missed_login_notification" <?php if(@$login_alerts->missed_login_notification=='yes'):?> checked="checked" <?php endif;?> value="yes">
            </div>
          </div>
        </div>
		 <div class="col-sm-6">
          <div class="form-group">
            <label for="company_name">Alert Time (Hours from shift start)</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input class="form-control timepicker" placeholder="Alert Hour(s)" readonly name="login_alert_hours" type="text" value="<?php echo $login_alerts->login_alert_hours;?>">
            </div>
          </div>
        </div>
		<?php if(in_array('53e',role_resource_ids())){ ?>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <div class="text-right">
                <button type="submit" class="btn bg-teal-400 save"><?php echo $this->lang->line('xin_save');?><i class="icon-spinner3 spinner position-left"></i></button>
              </div>
            </div>
          </div>
        </div>
		<?php } ?>
      </div>
    </form>

					</div>
					<div class="tab-pane has-padding animated fadeInRight" id="animation">
					     <div class="panel panel-flat">

  	<div class="panel-heading">
							<h5 class="panel-title">

							<strong>Animation Effects</strong> Configuration

							</h5>


	</div>

      <form id="animation_effect_info" action="<?php echo site_url("settings/animation_effect_info");?>" name="animation_effect_info" method="post">
        <input type="hidden" name="u_basic_info" value="UPDATE">
        <div class="row">
          <div class="panel-body">
            <div class="col-sm-6">
              <input name="employee_manage_own_bank_account" type="hidden" value="yes">
              <div class="form-group">
                <label for="animation_effect_topmenu">Animation Effect</label>
                <br>
                <select class="form-control" name="animation_effect_topmenu" data-plugin="select_hrm" data-placeholder="Animation Effect">
                  <option value="">Select One</option>
                  <option value="fadeInDown" <?php if($animation_effect_topmenu=='fadeInDown'){?> selected <?php }?>>fadeInDown</option>
                  <option value="fadeInUp" <?php if($animation_effect_topmenu=='fadeInUp'){?> selected <?php }?>>fadeInUp</option>
                  <option value="fadeInLeft" <?php if($animation_effect_topmenu=='fadeInLeft'){?> selected <?php }?>>fadeInLeft</option>
                  <option value="fadeInRight" <?php if($animation_effect_topmenu=='fadeInRight'){?> selected <?php }?>>fadeInRight</option>
                  <option value="fadeIn" <?php if($animation_effect_topmenu=='fadeIn'){?> selected <?php }?>>fadeIn</option>
                  <option value="growIn" <?php if($animation_effect_topmenu=='growIn'){?> selected <?php }?>>growIn</option>
                  <option value="rotateIn" <?php if($animation_effect_topmenu=='rotateIn'){?> selected <?php }?>>rotateIn</option>
                  <option value="rotateInUpLeft" <?php if($animation_effect_topmenu=='rotateInUpLeft'){?> selected <?php }?>>rotateInUpLeft</option>
                  <option value="rotateInDownLeft" <?php if($animation_effect_topmenu=='rotateInDownLeft'){?> selected <?php }?>>rotateInDownLeft</option>
                  <option value="rotateInUpRight" <?php if($animation_effect_topmenu=='rotateInUpRight'){?> selected <?php }?>>rotateInUpRight</option>
                  <option value="rotateInDownRight" <?php if($animation_effect_topmenu=='rotateInDownRight'){?> selected <?php }?>>rotateInDownRight</option>
                  <option value="rollIn" <?php if($animation_effect_topmenu=='rollIn'){?> selected <?php }?>>rollIn</option>
                  <option value="swing" <?php if($animation_effect_topmenu=='swing'){?> selected <?php }?>>swing</option>
                  <option value="tada" <?php if($animation_effect_topmenu=='tada'){?> selected <?php }?>>tada</option>
                  <option value="pulse" <?php if($animation_effect_topmenu=='pulse'){?> selected <?php }?>>pulse</option>
                  <option value="flipInX" <?php if($animation_effect_topmenu=='flipInX'){?> selected <?php }?>>flipInX</option>
                  <option value="flipInY" <?php if($animation_effect_topmenu=='flipInY'){?> selected <?php }?>>flipInY</option>
                </select>
                <br />
                <p class="help-block"><i class="icon-arrow-up8"></i> Set animation effect for top menu.</p>
                <input type="hidden" name="animation_effect" id="animation_effect" value="fadeInDown" />
              </div>
            </div>
            <div class="col-sm-6">
              <input name="employee_manage_own_bank_account" type="hidden" value="yes">
              <div class="form-group">
                <label for="animation_effect_modal">Animation Effect</label>
                <br>
                <select class="form-control" name="animation_effect_modal" data-plugin="select_hrm" data-placeholder="Animation Effect">
                  <option value="">Select One</option>
                  <option value="fadeInDown" <?php if($animation_effect_modal=='fadeInDown'){?> selected <?php }?>>fadeInDown</option>
                  <option value="fadeInUp" <?php if($animation_effect_modal=='fadeInUp'){?> selected <?php }?>>fadeInUp</option>
                  <option value="fadeInLeft" <?php if($animation_effect_modal=='fadeInLeft'){?> selected <?php }?>>fadeInLeft</option>
                  <option value="fadeInRight" <?php if($animation_effect_modal=='fadeInRight'){?> selected <?php }?>>fadeInRight</option>
                  <option value="fadeIn" <?php if($animation_effect_modal=='fadeIn'){?> selected <?php }?>>fadeIn</option>
                  <option value="growIn" <?php if($animation_effect_modal=='growIn'){?> selected <?php }?>>growIn</option>
                  <option value="rotateIn" <?php if($animation_effect_modal=='rotateIn'){?> selected <?php }?>>rotateIn</option>
                  <option value="rotateInUpLeft" <?php if($animation_effect_modal=='rotateInUpLeft'){?> selected <?php }?>>rotateInUpLeft</option>
                  <option value="rotateInDownLeft" <?php if($animation_effect_modal=='rotateInDownLeft'){?> selected <?php }?>>rotateInDownLeft</option>
                  <option value="rotateInUpRight" <?php if($animation_effect_modal=='rotateInUpRight'){?> selected <?php }?>>rotateInUpRight</option>
                  <option value="rotateInDownRight" <?php if($animation_effect_modal=='rotateInDownRight'){?> selected <?php }?>>rotateInDownRight</option>
                  <option value="rollIn" <?php if($animation_effect_modal=='rollIn'){?> selected <?php }?>>rollIn</option>
                  <option value="swing" <?php if($animation_effect_modal=='swing'){?> selected <?php }?>>swing</option>
                  <option value="tada" <?php if($animation_effect_modal=='tada'){?> selected <?php }?>>tada</option>
                  <option value="pulse" <?php if($animation_effect_modal=='pulse'){?> selected <?php }?>>pulse</option>
                  <option value="flipInX" <?php if($animation_effect_modal=='flipInX'){?> selected <?php }?>>flipInX</option>
                  <option value="flipInY" <?php if($animation_effect_modal=='flipInY'){?> selected <?php }?>>flipInY</option>
                </select>
                <br />
                <p class="help-block"><i class="icon-arrow-up8"></i> Set animation effect for modal dialogs.</p>
              </div>
			  <?php if(in_array('53e',role_resource_ids())){ ?>
			    <div class="form-group">
                  <div class="text-right">
                    <button type="submit" class="btn bg-teal-400 save col-right"><?php echo $this->lang->line('xin_save');?><i class="icon-spinner3 spinner position-left"></i></button>
                  </div>
                </div>
			  <?php } ?>
            </div>

          </div>
        </div>
      </form>
    </div>

					</div>
					<div class="tab-pane has-padding animated fadeInRight" id="notification">
					   <form id="notification_position_info" action="<?php echo site_url("settings/notification_position_info");?>" name="notification_position_info" method="post">
      <input type="hidden" name="u_basic_info" value="UPDATE">
      	     <div class="panel panel-flat">

  	<div class="panel-heading">
							<h5 class="panel-title">

							<strong>Notification Position</strong> Configuration

							</h5>


	</div>
       <div class="panel-body">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="notification_position">Position</label>
              <select class="form-control" name="notification_position" data-plugin="select_hrm" data-placeholder="Position">
                <option value="">Select One</option>
                <option value="toast-top-right" <?php if($notification_position=='toast-top-right'){?> selected <?php }?>>Top Right</option>
                <option value="toast-bottom-right" <?php if($notification_position=='toast-bottom-right'){?> selected <?php }?>>Bottom Right</option>
                <option value="toast-bottom-left" <?php if($notification_position=='toast-bottom-left'){?> selected <?php }?>>Bottom Left</option>
                <option value="toast-top-left" <?php if($notification_position=='toast-top-left'){?> selected <?php }?>>Top Left</option>
                <option value="toast-top-center" <?php if($notification_position=='toast-top-center'){?> selected <?php }?>>Top Center</option>
              </select>
              <br />
              <small class="help-block"><i class="icon-arrow-up8"></i> Set position for notifications .</small> </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="company_name">Enable Close Button</label>
              <br>
              <div class="pull-xs-left m-r-1">
                <input type="checkbox" class="switchery" data-size="small" data-color="#3e70c9" data-secondary-color="#ddd" id="sclose_btn" <?php if($notification_close_btn=='true'):?> checked="checked" <?php endif;?> value="true" />
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="company_name">Progress Bar</label>
              <br>
              <div class="pull-xs-left m-r-1">
                <input type="checkbox" class="switchery" data-size="small" data-color="#3e70c9" data-secondary-color="#ddd" id="snotification_bar" <?php if($notification_bar=='true'):?> checked="checked" <?php endif;?> value="true" />
              </div>
            </div>
          </div>
        </div>

		<?php if(in_array('53e',role_resource_ids())){ ?>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <div class="text-right">
                <button type="submit" class="btn bg-teal-400 save"><?php echo $this->lang->line('xin_save');?><i class="icon-spinner3 spinner position-left"></i></button>
              </div>
            </div>
          </div>
        </div>
		<?php } ?>
 </div>
	 </div>
    </form>

					</div>

              <div class="tab-pane has-padding animated fadeInRight" id="email_settings">
                <form id="email_settings_form" action="<?php echo site_url("settings/email_settings")?>/" name="email_settings" method="post">
                    <input type="hidden" name="u_basic_info" value="UPDATE">
                    <div class="panel panel-flat">

                        <div class="panel-heading">
                            <h5 class="panel-title">
                      <strong>Email Settings</strong>
                      </h5>
                        </div>
                        <div class="panel-body">

                            <div class="col-sm-6 no-padding-left">
                                <div class="form-group">
                                    <label for="name">Salary Certificate <span data-toggle="tooltip" data-placement="top" title="" data-original-title="They will get the email if someone is raising request for salary certificate."><i class="icon-bubble-notification" style="margin-top: -10px;"></i></span></label>
                                    <select name="salary_certificate_emails[]" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="Choose..." multiple="multiple">
                                        <option value=""></option>
                                        <?php
                                        foreach($this->Employees_model->get_employees()->result() as $employee) {

                                          if(in_array($employee->user_id.'//'.$employee->email,$salary_certificate_email)){
                                            $selected_salary="selected";
                                          }else{
                                            $selected_salary='';
                                          }
                                        ?>
                                          <option value="<?php echo $employee->user_id.'//'.$employee->email;?>" <?php echo $selected_salary;?>>
                                              <?php echo change_fletter_caps($employee->first_name.' '.$employee->middle_name.' '.$employee->last_name);?>
                                          </option>
                                          <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="panel-body">
                            <?php if(in_array('53e',role_resource_ids())){ ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="text-right">
                                                <button type="submit" class="btn bg-teal-400 save">
                                                    <?php echo $this->lang->line('xin_save');?><i class="icon-spinner3 spinner position-left"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>

                        </div>

                    </div>
                </form>
              </div>


						  </div>
							</div>
						</div>

					</div>


</div>
</div>

<style>
.kv-file-zoom.btn.btn-link.btn-xs.btn-icon {
    display: none !important;
}



</style>
<?php if(!in_array('53e',role_resource_ids())){ ?>
<script>
$(document).ready(function(){
	$('input,select,checkbox').prop('disabled',true);
	//$('.dataTable').find('ul.icons-list').append('-'); class="hide_vi"

	//$('th.hide_vi,ul.icons-list').hide();
});

</script>
<?php } ?>

<?php } else { ?>
		<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title text-danger">

							 <?php echo $this->lang->line('xin_permission');?>

							</h5>

						</div>
				</div>

		<?php } ?>

<style>
.custom-control.custom-radio {
    line-height: 1.75em;
}
</style>
