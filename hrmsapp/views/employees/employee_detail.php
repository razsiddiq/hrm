<?php
/* Employee Details view 
*/
?>
<?php $session = $this->session->userdata('username');?>
<!-- Vertical tabs -->
<input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id;?>">
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-flat">
      <div class="panel-heading">
        <h6 class="panel-title text-teal-400"><?php echo change_fletter_caps($first_name.' '.$middle_name.' '.$last_name);?>
        </h6>
      </div>

      <div class="panel-body">
        <div class="tabbable nav-tabs-vertical nav-tabs-left">
          <ul class="nav nav-tabs nav-tabs-highlight">
            <?php if(in_array('basic-info-view',role_resource_ids()) || visa_wise_role_ids() != '') {?>	
            <li id="user_details_1" class="active"><a href="#user_basic_info" data-toggle="tab"><i class="icon-user position-left"></i> <?php echo $this->lang->line('xin_e_details_basic');?></a></li>
            <?php } ?>
            <?php if(in_array('documents-view',role_resource_ids())  || visa_wise_role_ids() != '') {?>	
            <li id="user_details_15"><a href="#immigration" data-toggle="tab"><i class="icon-file-empty2 position-left"></i> <?php echo $this->lang->line('xin_e_details_document');?></a></li>
            <?php } ?>
            <?php if(in_array('offer-letter-view',role_resource_ids())  || visa_wise_role_ids() != '') {?>	
            <li id="user_details_15a"><a href="#offer_letter" data-toggle="tab"><i class="icon-files-empty position-left"></i> Offer Letter</a></li>
            <?php } ?>
            <?php if(in_array('contract-view',role_resource_ids())  || visa_wise_role_ids() != '') {?>	
            <li id="user_details_9"><a href="#contract" data-toggle="tab"><i class="icon-pen2 position-left"></i> <?php echo $this->lang->line('xin_e_details_contract');?></a></li>
            <?php } ?>
            <?php if(in_array('pay-structure-view',role_resource_ids())) {?>	          
            <li id="user_details_16" onclick="check_visa_under('<?php echo $this->uri->segment(3);?>');"><a href="#pay_structure" data-toggle="tab"><i class="icon-coins position-left"></i> <?php echo $this->lang->line('xin_e_details_pay');?></a></li>
            <?php } ?>
            <?php if(in_array('bank-account-view',role_resource_ids()) || visa_wise_role_ids() != '') {?>	
            <li id="user_details_8"><a href="#bank_account" data-toggle="tab"><i class="icon-book position-left"></i> <?php echo $this->lang->line('xin_e_details_baccount');?></a></li>
            <?php } ?>
            <?php if(in_array('emergency-contacts-view',role_resource_ids()) || visa_wise_role_ids() != '') {?>	
            <li id="user_details_3"><a href="#contact" data-toggle="tab"><i class="icon-phone position-left"></i> Emergency <?php echo $this->lang->line('xin_e_details_contact');?>s</a></li>
            <?php } ?>	
            <!--<li id="user_details_2"><a href="#profile-picture" data-toggle="tab"><i class="icon-camera position-left"></i> <?php echo $this->lang->line('xin_e_details_profile_picture');?></a></li>-->
            <?php if(in_array('qualification-view',role_resource_ids()) || visa_wise_role_ids() != '') {?>	
            <li id="user_details_6"><a href="#qualification" data-toggle="tab"><i class="icon-graduation2 position-left"></i> <?php echo $this->lang->line('xin_e_details_qualification');?></a></li>
            <?php } ?>	
            <?php if(in_array('work-experience-view',role_resource_ids()) || visa_wise_role_ids() != '') {?>	
            <li id="user_details_7"><a href="#work_experience" data-toggle="tab"><i class="icon-flip-vertical3 position-left"></i> <?php echo $this->lang->line('xin_e_details_w_experience');?></a></li>
            <?php } ?>	
            <?php if(in_array('change-password',role_resource_ids()) || visa_wise_role_ids() != '') {?>	
            <li id="user_details_14"><a href="#change_password" data-toggle="tab"><i class="icon-key position-left"></i> <?php echo $this->lang->line('xin_e_details_cpassword');?></a></li>
            <?php } ?>	
            <!--<li id="user_details_17"><a href="#approval_status" data-toggle="tab"><i class="icon-clipboard5 position-left"></i> Approvals</a></li>-->

            <?php if(in_array('request-approval',role_resource_ids()) && visa_wise_role_ids() == '') {?>  
            <li id="user_details_17"><a href="#approval_status" data-toggle="tab"><i class="icon-clipboard5 position-left"></i> Undertakings</a></li>
            <?php } ?>  
          </ul>

          <div class="tab-content">
            <div id="progress_div">
            </div>
            <?php if(in_array('basic-info-view',role_resource_ids()) || visa_wise_role_ids() != '') {?>	
            <div class="tab-pane active has-padding animated fadeInRight" id="user_basic_info">
              <form id="basic_info" action="<?php echo site_url("employees/basic_info") ?>" name="basic_info" method="post">
                <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
                <input type="hidden" name="u_basic_info" value="UPDATE">
                <div class="panel panel-flat">
                  <div class="panel-heading">
                    <h5 class="panel-title">
                      <strong><?php echo $this->lang->line('xin_e_details_basic_info');?></strong>
                    </h5>
                  </div>

                  <div class="panel-body">
                    <div class="row">
                      <div class="col-lg-8 no-padding">                    
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="first_name"><?php echo $this->lang->line('xin_employee_first_name');?><?php echo REQUIRED_FIELD;?></label>
                            <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_first_name');?>" name="first_name" type="text" value="<?php echo $first_name;?>">
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="middle_name"><?php echo $this->lang->line('xin_employee_middle_name');?></label>
                            <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_middle_name');?>" name="middle_name" type="text" value="<?php echo $middle_name;?>">
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="first_name"><?php echo $this->lang->line('xin_employee_last_name');?></label>
                            <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_last_name');?>" name="last_name" type="text" value="<?php echo $last_name;?>">
                          </div>
                        </div>
      
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="employee_id"><?php echo $this->lang->line('dashboard_employee_id');?><?php echo REQUIRED_FIELD;?></label>
                            <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_employee_id');?>" name="employee_id" type="text" value="<?php echo $employee_id;?>">
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="Company" class="control-label">Company</label>
                            <select class="form-control" name="company_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_one');?>">
                              <?php foreach($all_companies as $cmpnys) {?>
                              <option <?php if($cmpnys->company_id==$company_id){?> selected <?php } ?> value="<?php echo $cmpnys->company_id;?>"><?php echo $cmpnys->name?></option>
                              <?php } ?>
                            </select>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="status" class="control-label"><?php echo $this->lang->line('dashboard_xin_status');?></label>
                            <select class="form-control" name="status" onchange="emp_inactive_status(this.value);" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('dashboard_xin_status');?>">
                              <option value="0" <?php if($is_active=='0'):?> selected <?php endif; ?>>In-Active</option>
                              <option value="1" <?php if($is_active=='1'):?> selected <?php endif; ?>>Active</option>
                            </select>
                          </div>          
                        </div>
                      </div>
        
                      <div class="col-lg-4 text-center">
                        <div class="media">
                          <?php if($profile_picture!='' && $profile_picture!='no file') {?>
                          <img style=" width: 160px;height:160px;" src="<?php echo base_url().'uploads/profile/'.$profile_picture;?>"  id="img-circle_a" class="img-circle">
                          <?php } else {?>
                          <?php if($gender=='Male') { ?>
                          <?php $de_file = base_url().'uploads/profile/default_male.jpg';?>
                          <?php } else { ?>
                          <?php $de_file = base_url().'uploads/profile/default_female.jpg';?>
                          <?php } ?>
                          <img style="width: 160px;height:160px;" src="<?php echo $de_file;?>" id="img-circle_a" class="img-circle">
                          <?php } ?>						
                        </div>
                        <input type="file" name="p_file" id="p_file" style="width: 100%;height: 100%;position: absolute;top: 0;opacity: 0;
                        cursor:pointer;" onchange="loadFile(event)">
                        <input type="hidden" name="p_uploaded_file" value="<?php echo $profile_picture;?>">
                        <small class="help-block"><?php echo $this->lang->line('xin_e_details_picture_type');?><?php echo REQUIRED_FIELD;?></small>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="email" class="control-label"><?php echo $this->lang->line('dashboard_email');?><?php echo REQUIRED_FIELD;?></label>
                          <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_email');?>" name="email" type="email" value="<?php echo $email;?>">
                        </div>
                      </div>
                      
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="email" class="control-label">Personal Email</label>
                          <input class="form-control" placeholder="Personal Email" name="personal_email" type="email" value="<?php echo @$personal_email;?>">
                        </div>
                      </div>


                      <div class="col-md-4">
                        <label for="nationality">Nationality<?php echo REQUIRED_FIELD;?></label>
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
                          <label for="department" class="control-label"><?php echo $this->lang->line('xin_departments');?><?php echo REQUIRED_FIELD;?></label>
                          <select class="form-control" name="department_id" id="aj_department" onchange="getDepartmentBasedEmployees(this.value,'<?php echo $user_id;?>','')" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee_department');?>">
                            <option value=""></option>
                            <?php foreach($all_departments as $department) {?>
                            <option value="<?php echo $department->department_id?>" <?php if($department_id==$department->department_id):?> selected <?php endif;?>><?php echo $department->department_name?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                      
                      <div class="col-md-4">
                        <div class="form-group" id="designation_ajax">
                          <label for="designation"><?php echo $this->lang->line('xin_designation');?><?php echo REQUIRED_FIELD;?></label>
                          <select class="form-control designation_id" name="designation_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_designation');?>">
                            <option value=""></option>
                            <?php foreach($all_designations as $designation) {?>
                            <option value="<?php echo $designation->designation_id?>" <?php if($designation_id==$designation->designation_id):?> selected <?php endif;?>><?php echo $designation->designation_name?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="location_id" class="control-label"><?php echo $this->lang->line('xin_e_details_office_location');?> (Area Of Assignment)<?php echo REQUIRED_FIELD;?></label>
                          <select class="form-control" name="office_location_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_one');?>">
                            <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
                            <?php foreach($all_office_locations as $location) {?>
                            <option <?php if($location->location_id==$office_location_id){?> selected <?php } ?> value="<?php echo $location->location_id?>"><?php echo $location->location_name?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                    </div>          
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="date_of_birth"><?php echo $this->lang->line('xin_employee_dob');?><?php echo REQUIRED_FIELD;?></label>
                          <div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                              <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_dob');?>" name="date_of_birth" size="16" type="text" value="<?php echo format_date('d F Y',$date_of_birth);?>" readonly>
                              <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="date_of_joining" class="control-label"><?php echo $this->lang->line('xin_employee_doj');?><?php echo REQUIRED_FIELD;?></label>
                          <div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                            <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_doj');?>" name="date_of_joining" id="joiningdate"  size="16" type="text" onchange="getTenure()" value="<?php echo format_date('d F Y',$date_of_joining);?>" readonly>
                            <span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="date_of_leaving" class="control-label"><?php echo $this->lang->line('xin_employee_dol');?></label>
                          <div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                            <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_dol');?>" onchange="getTenure()" name="date_of_leaving" id="date_of_leaving"   size="16" type="text"  value="<?php echo format_date('d F Y',$date_of_leaving);?>" readonly>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="gender" class="control-label"><?php echo $this->lang->line('xin_employee_gender');?><?php echo REQUIRED_FIELD;?></label>
                          <select class="form-control" name="gender" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee_gender');?>">
                            <option value="Male" <?php if($gender=='Male'):?> selected <?php endif; ?>>Male</option>
                            <option value="Female" <?php if($gender=='Female'):?> selected <?php endif; ?>>Female</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="marital_status" class="control-label"><?php echo $this->lang->line('xin_employee_mstatus');?><?php echo REQUIRED_FIELD;?></label>
                          <select class="form-control" name="marital_status" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee_mstatus');?>">
                            <option value="Single" <?php if($marital_status=='Single'):?> selected <?php endif; ?>>Single</option>
                            <option value="Married" <?php if($marital_status=='Married'):?> selected <?php endif; ?>>Married</option>
                            <option value="Widowed" <?php if($marital_status=='Widowed'):?> selected <?php endif; ?>>Widowed</option>
                            <option value="Divorced or Separated" <?php if($marital_status=='Divorced or Separated'):?> selected <?php endif; ?>>Divorced or Separated</option>
                          </select>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="spouse">Spouse Name (If Married)</label>
                          <input class="form-control" placeholder="Spouse Name" name="spouse_name" type="text" value="<?php echo $spouse_name;?>" />             
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="tenure" class="control-label"><?php echo $this->lang->line('xin_employee_tenure');?></label>
                          <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_tenure');?>" id="tenure1" type="text" value="<?php //echo $tenure;?>" readonly>
                          <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_tenure');?>" name="tenure" id="tenure" type="hidden" value="<?php //echo $tenure;?>" readonly>
                        </div>
                      </div>

                      <div class="col-md-4 self_manager_show">
                        <div class="form-group">
                          <label for="send_mail"><?php echo $this->lang->line('xin_employee_reporting_manager');?><?php echo REQUIRED_FIELD;?></label>
                          <input type="hidden" class="self_manager_class" name=""  value="<?php echo $user_id;?>"/>
                          <div class="clearfix"></div>
                          <span style="font-size: 20px;">Self Reporting</span>
                        </div>
                      </div>

                      <div class="col-md-4 reporting_manager_show">
                        <div class="form-group">
                          <label for="send_mail"><?php echo $this->lang->line('xin_employee_reporting_manager');?><?php echo REQUIRED_FIELD;?></label>
                          <select class="form-control reporting_manager_class"   name="reporting_manager" id="reportingmanager" data-plugin="select_hrm" data-placeholder="Select <?php echo $this->lang->line('xin_employee_reporting_manager');?>">
                          </select>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="role"><?php echo $this->lang->line('xin_employee_role');?><?php echo REQUIRED_FIELD;?></label>
                          <select class="form-control" name="role" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee_role');?>">
                            <option value=""></option>
                            <?php foreach($all_user_roles as $role) {
                            if($session['role_name']!=AD_ROLE){
                            if($role->role_name!=AD_ROLE){?>
                            <option value="<?php echo $role->role_id?>" <?php if($user_role_id==$role->role_id):?> selected <?php endif;?>><?php echo $role->role_name;?>
                            </option>                            
                            <?php } }else {?>
                            <option value="<?php echo $role->role_id?>" <?php if($user_role_id==$role->role_id):?> selected <?php endif;?>><?php echo $role->role_name;?>
                            </option>
                            <?php }  } ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <?php $contact_no=@explode('-',@$contact_no);?>
                        <div class="form-group">
                          <label for="contact_no" class="control-label"><?php echo $this->lang->line('xin_contact_number');?><?php echo REQUIRED_FIELD;?></label>
                          <div class="clearfix"></div>
                          <div class="input-group">
                            <span class="input-group-addon-custom">
                              <select class="form-control change_country_code js-example-templating" name="country_code">
                                <?php foreach(phone_numbers_code() as $keys=>$phone_code){?>
                                <option <?php if($keys==@$contact_no[0]){echo 'selected';}?> value="<?php echo $keys; ?>-" data-len ="<?php echo $phone_code['length'];?>"   rel="<?php echo $phone_code['country_name'];?>"><?php echo $keys; ?></option>
                                <?php } ?>
                              </select>
                            </span>
                            <input class="form-control " placeholder="<?php echo $this->lang->line('xin_contact_number');?>" name="contact_no"   type="text" pattern="\d*" maxlength="<?php echo MAX_PHONE_DIGITS;?>"  value="<?php echo @$contact_no[1];?>" title="<?php echo $this->lang->line('xin_use_numbers');?>">
                          </div>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="role"><?php echo $this->lang->line('xin_employee_languages_known');?><?php echo REQUIRED_FIELD;?></label>
                          <?php $languages_known=explode(',',$languages_known);?>
                          <select class="form-control" multiple="multiple" name="languages_known[]" data-plugin="select_hrm" data-placeholder="Select Language">
                            <option value=""></option>
                            <?php foreach(language_lists() as $language) { ?>
                                <option value="<?php echo $language;?>" <?php if(in_array($language,$languages_known)):?> selected <?php endif;?>><?php echo $language;?>
                                </option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <label for="name"><?php echo $this->lang->line('xin_location_currency');?></label>
                        <select name="currency_id" data-plugin="select_hrm" >
                          <?php foreach($this->Location_model->get_all_currency() as $currency){?>
                          <option <?php if($currency->currency_id==$currency_id){?> selected <?php } ?> value="<?php echo $currency->currency_id;?>"><?php echo $currency->name.' ( '.$currency->code.' ) ';?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="status" class="control-label">Working Hours <?php echo REQUIRED_FIELD;?><span data-toggle="tooltip" data-placement="top" title="" data-original-title="As per contract include lunch hours & exclude OT."><i class=" icon-bubble-notification" style="margin-top: -10px;"></i></span></label>
                          <?php
                          $system_setting = $this->Xin_model->read_setting_info(1);
                          $maximum_working_hours=	$system_setting[0]->maximum_working_hours;?>
                          <input class="form-control timepicker" placeholder="Working Hour(s)" readonly name="working_hours" type="text" value="<?php echo $working_hours;?>">
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="visa_occupation"><?php echo 'Visa Designation';?></label>
                          <select class="form-control" name="visa_occupation" data-plugin="select_hrm" data-placeholder="<?php echo 'Visa Designation';?>">
                            <option value=""></option>
                            <?php foreach($all_designations as $designation) {?>
                            <option value="<?php echo $designation->designation_id?>" <?php if($visa_occupation==$designation->designation_id):?> selected <?php endif;?>><?php echo $designation->designation_name;?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="blood_group"><?php echo 'Blood Group';?></label>
                          <select class="form-control" name="blood_group" data-plugin="select_hrm" data-placeholder="<?php echo 'Blood Group';?>">
                            <option value=""></option>
                            <?php foreach(blood_group() as $blood_g) {?>
                            <option value="<?php echo $blood_g?>" <?php if($blood_group==$blood_g):?> selected <?php endif;?>><?php echo $blood_g;?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="company_transport">Send Missing Punch Emails?</label>
                          <select class="form-control" name="send_punch_reminder" data-plugin="select_hrm" data-placeholder="Send Missing Punch Emails?">
                            <option <?php if($send_punch_reminder==0):?> selected <?php endif;?>value="0">No</option>
                            <option <?php if($send_punch_reminder==1):?> selected <?php endif;?>value="1">Yes</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="company_transport">Is break Included?</label>
                          <select class="form-control" name="is_break_included" data-plugin="select_hrm" data-placeholder="Is break Included">
                            <option <?php if($is_break_included==0):?> selected <?php endif;?>value="0">No</option>
                            <option <?php if($is_break_included==1):?> selected <?php endif;?>value="1">Yes</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="company_transport"><?php echo 'Company Transport';?></label>
                          <select class="form-control" name="company_transport" data-plugin="select_hrm" data-placeholder="<?php echo 'Company Transport';?>">
                            <option <?php if($company_transport==0):?> selected <?php endif;?>value="0">No</option>
                            <option <?php if($company_transport==1):?> selected <?php endif;?>value="1">Yes</option>
                          </select>
                        </div>

                      </div>
                    </div>
                    <div class="row">
                      <div  class="col-md-6">
                        <div class="form-group">
                          <label for="address"><?php echo $this->lang->line('xin_homeaddress');?><?php echo REQUIRED_FIELD;?></label>
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

                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="address"><?php echo $this->lang->line('xin_residingaddress');?>&nbsp;&nbsp;( Choose the home address as same <span class="pull-xs-left ml-5 click_same_ad">
                          <input type="checkbox" class="styled" id="same_as_home_address" value="yes" <?php if($address==$residing_address1 && $address!=''){echo 'checked="checked"';}?>/>
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
                    <?php if(in_array('basic-info-edit',role_resource_ids()) && visa_wise_role_ids() == '') {?>	                    
                    <button type="submit" class="btn bg-teal-400 save pull-right"><?php echo $this->lang->line('xin_save');?></button>
                    <?php } ?>
                  </div>
                </div>
              </form>
            </div>
            <?php } ?>
            <?php if(in_array('documents-view',role_resource_ids()) || visa_wise_role_ids() != '') {?>
						<div class="tab-pane has-padding animated fadeInRight" id="immigration">
              <?php if(in_array('documents-add',role_resource_ids())) {?>
              <div class="add-form" style="display:none;">
                <div class="panel panel-flat">
                  <div class="panel-heading">
                    <h5 class="panel-title">
                      <strong><?php echo $this->lang->line('xin_add_new');?></strong> <?php echo $this->lang->line('xin_e_details_document');?>
                    </h5>

                    <div class="heading-elements">
                      <div class="add-record-btn">
                        <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_hide');?></button>
                      </div>
                    </div>
						      </div>

                  <form id="immigration_info" action="<?php echo site_url("employees/immigration_info") ?>" enctype="multipart/form-data" name="immigration_info" method="post">
                    <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
                    <input type="hidden" name="u_document_info" value="UPDATE">
                    <div class="panel-body">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="relation">Choose Document<span data-toggle="tooltip" data-placement="top" title="" data-original-title="Document Uploaded"><i class="icon-checkmark4 ml-10"></i></span><span data-toggle="tooltip" data-placement="top" title="" data-original-title="Not Yet Uploaded or Expired"><i class=" icon-warning ml-10"></i></span></label>
                            <select name="document_type_id" id="document_type_id" onchange="change_immigration_doc(this.value);" class="select-icons" data-placeholder="<?php echo $this->lang->line('xin_e_details_choose_dtype');?>">
                              <option value="" data-icon="">Select Document</option>
                              <?php foreach($all_document_types as $document_type) {
                              $check_doc_there=$this->Employees_model->check_doc_there($document_type->type_id,$user_id);
                              if($check_doc_there){$icon_data='data-icon="checkmark4"';}else{$icon_data='data-icon="warning"';}
                              ?>
                              <option value="<?php echo $document_type->type_id;?>" <?php echo $icon_data;?>><?php echo $document_type->type_name;?></option>
                              <?php } ?>
                            </select>
                          </div>
                        </div>
                        
                        <div class="col-md-6" id="doc_number">
                          <div class="form-group">
                            <label for="document_number" class="control-label"><span id="name_of_doc">Document</span> Number  <span data-toggle="tooltip" data-placement="top" title="" data-original-title="Documents number such as Passport Number, Visa Number, Emirates ID Number, Driving License Number and etc..."><i class="fa fa-info-circle"></i></span></label>
                            <div class="input-group input-group-st" style="display: inline;">
                              <span class="input-group-addon-custom visa_t_hide" style="display:none;">
                                <select class="form-control change_visa_t">
                                <option value="0">General</option>
                                <option value="1">Spouse</option>
                                </select>
                              </span>
                              <input class="form-control" placeholder="Document Number" name="document_number" onkeyup="check_digits(this.value);" id="document_number" type="text">
                            </div>
                          </div>
                        </div>
                      </div>
		  
                      <div class="row" id="for_visa_select">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="issue_date" class="control-label">Visa Under</label>
                            <select name="visacard" class="change_type form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_choose_visa_under');?>">
                              <option value=""></option>
                              <?php foreach($all_visa_under as $visa_under) {?>
                              <option value="<?php echo $visa_under->type_id;?>"> <?php echo $visa_under->type_name;?></option>
                              <?php } ?>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="row" id="for_medical_card_select">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="issue_date" class="control-label">Medical Card Type</label>
                            <select name="medicalcard" class="change_type form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_choose_medical_card_type');?>">
                              <option value=""></option>
                              <?php foreach($all_medical_card_types as $medical_card_types) {?>
                              <option value="<?php echo $medical_card_types->type_id;?>"> <?php echo $medical_card_types->type_name;?></option>
                              <?php } ?>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="issue_date" class="control-label">Issue Date</label>
                            <div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                              <input class="form-control" placeholder="Issue Date" name="issue_date"   size="16" type="text"  value="" readonly>
                              <span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>
                            </div>
                          </div>
                        </div>
                   
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="expiry_date" class="control-label">Date of Expiry</label>
                            <div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                              <input class="form-control" placeholder="Expiry Date" name="expiry_date"   size="16" type="text"  value="" readonly>
                              <span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-6 entry_stamp_date">
                          <input class="form-control" placeholder="Stamped Date" name="eligible_review_date"   size="16" type="hidden"  value="" readonly>
                          <div class="form-group">
                            <label for="date_of_cancellation" class="control-label">Date of Cancellation</label>
                            <div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                              <input class="form-control" placeholder="Date of Cancellation" name="date_of_cancellation"   size="16" type="text"  value="" readonly>
                              <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12" id="country_show">
                          <div class="form-group">
                            <label for="send_mail">Passport Issued Country</label>
                            <select class="form-control" name="country" data-plugin="select_hrm" data-placeholder="Country">
                              <option value="">Select One</option>
                              <?php foreach($all_countries as $scountry) {?>
                              <option value="<?php echo $scountry->country_id;?>"> <?php echo $scountry->country_name;?></option>
                              <?php } ?>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="document_file" class="control-label"><?php echo $this->lang->line('xin_e_details_document_file');?></label>
                            <br/>
                            <input type="file" class="file-input" name="document_file[]" multiple="multiple">
                            <small class="help-block"><?php echo $this->lang->line('xin_e_details_d_type_file');?></small>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <div class="footer-elements">
                              <button type="submit" class="btn bg-teal-400 save"><?php echo $this->lang->line('xin_save');?><i class="icon-spinner3 spinner position-left"></i></button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
	              </div>
              </div>
              <?php } ?>

              <div class="panel panel-flat">
                <div class="panel-heading">
                  <h5 class="panel-title">
                    <strong><?php echo $this->lang->line('xin_list_all');?></strong>  <?php echo $this->lang->line('xin_e_details_document');?>
                  </h5>

                  <div class="heading-elements">
                    <?php if(in_array('documents-add',role_resource_ids()) && visa_wise_role_ids() == '') {?>
                    <div class="add-record-btn">
                    <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_add_new');?></button>
                    </div>
							      <?php } ?>
		              </div>
                </div>
                <div class="panel-body">
                  <table class="table" id="xin_table_imgdocument">
                    <thead>
                      <tr>
                      <th>Document</th>
                      <th>Issued Date</th>
                      <th>Expiry Date</th>
                      <th>Details</th>
                      <th>Files</th>
                      <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
			          </div>
				      </div>
      	    </div>
            <?php } ?>
            <?php if(in_array('offer-letter-view',role_resource_ids()) || visa_wise_role_ids() != '') {?>	
            <div class="tab-pane has-padding animated fadeInRight" id="offer_letter">         

              <div class="panel panel-flat">
                <div class="panel-heading">
                  <h5 class="panel-title">
                    Offer Letter
                  </h5>                
                </div>
                <div class="panel-body">
                  <table class="table" id="xin_table_offerletter">
                    <thead>
                      <tr>
                      <th>Document</th>
                      <th>Issued Date</th>
                      <th>Expiry Date</th>
                      <th>Details</th>
                      <th>Files</th>
                      <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
			          </div>
				      </div>
            </div>
            <?php } ?>
            <?php if(in_array('emergency-contacts-view',role_resource_ids()) || visa_wise_role_ids() != '') {?>	
            <div class="tab-pane has-padding animated fadeInRight" id="contact">
              <?php if(in_array('emergency-contacts-add',role_resource_ids()) || visa_wise_role_ids() != '') {?>
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
                    <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
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
                                  <option  value="<?php echo $keys; ?>-" data-len ="<?php echo $phone_code['length'];?>"   rel="<?php echo $phone_code['country_name'];?>"><?php echo $keys; ?></option>
                                  <?php } ?>
                                </select>
                              </span>
                              <input class="form-control " placeholder="<?php echo $this->lang->line('xin_e_details_mobile');?>" name="mobile_phone"   type="text" maxlength="<?php echo MAX_PHONE_DIGITS;?>">
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

                      <button type="submit" class="btn bg-teal-400 pull-right save"><?php echo $this->lang->line('xin_save');?></button>
                    </div>
                  </form>
                </div>
              </div>
              <?php } ?>
                  
              <div class="panel panel-flat">
                <div class="panel-heading">
                  <h5 class="panel-title">
                    <strong><?php echo $this->lang->line('xin_list_all');?></strong>  <?php echo $this->lang->line('xin_e_details_contacts');?>
                  </h5>
                  <div class="heading-elements">
                    <?php if(in_array('emergency-contacts-add',role_resource_ids()) && visa_wise_role_ids() == '') {?>
                    <div class="add-record-btn">
                      <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_add_new');?></button>
                    </div>
                    <?php } ?>
                  </div>
                </div>

                <div class="panel-body">
                  <table class="table" id="xin_table_contact">
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
            <?php if(in_array('qualification-view',role_resource_ids()) || visa_wise_role_ids() != '') {?>
            <div class="tab-pane has-padding animated fadeInRight" id="qualification">
              <?php if(in_array('qualification-add',role_resource_ids()) || visa_wise_role_ids() != '') { ?>
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
                  <form id="qualification_info" action="<?php echo site_url("employees/qualification_info") ?>" name="qualification_info" method="post">
                    <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
                    <input type="hidden" name="u_basic_info" value="UPDATE">
                    <div class="panel-body">
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
                              <option value="<?php echo $education_level->type_id?>"><?php echo $education_level->type_name?></option>
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
                              <option value="<?php echo $qualification_language->type_id;?>"><?php echo $qualification_language->type_name;?></option>
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
                              <button type="submit" class="btn bg-teal-400 save"><?php echo $this->lang->line('xin_save');?></button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
	                </form>   
                </div>
    	        </div>
							<?php } ?>
	
              <div class="panel panel-flat">
                <div class="panel-heading">
                  <h5 class="panel-title">
                    <strong><?php echo $this->lang->line('xin_list_all');?></strong> <?php echo $this->lang->line('xin_e_details_qualification');?>
                  </h5>
                  <div class="heading-elements">
                    <?php if(in_array('qualification-add',role_resource_ids()) && visa_wise_role_ids() == '') {?>
                    <div class="add-record-btn">
                      <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_add_new');?></button>
                    </div>
                    <?php } ?>
                  </div>
						    </div>

                <div class="panel-body">
                  <table class="table" id="xin_table_qualification">
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
            <?php if(in_array('work-experience-view',role_resource_ids()) || visa_wise_role_ids() != '') {?>
            <div class="tab-pane has-padding animated fadeInRight" id="work_experience">
              <?php if(in_array('work-experience-add',role_resource_ids()) || visa_wise_role_ids() != '') { ?>
              <div class="add-form" style="display:none;">
                <div class="panel panel-flat">
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
  
                  <form id="work_experience_info" action="<?php echo site_url("employees/work_experience_info") ?>" name="work_experience_info" method="post">
                    <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
                    <input type="hidden" name="u_basic_info" value="UPDATE">

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
              </div>
              <?php } ?>

              <div class="panel panel-flat">
                <div class="panel-heading">
                  <h5 class="panel-title">
                    <strong><?php echo $this->lang->line('xin_list_all');?></strong> <?php echo $this->lang->line('xin_e_details_w_experience');?>
                  </h5>
                  <div class="heading-elements">
                    <?php if(in_array('work-experience-add',role_resource_ids()) && visa_wise_role_ids() == '') {?>
                    <div class="add-record-btn">
                      <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_add_new');?></button>
                    </div>
                    <?php } ?>
                  </div>
                </div>

                <div class="panel-body">
                  <table class="table" id="xin_table_work_experience" >
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
            <?php if(in_array('bank-account-view',role_resource_ids()) || visa_wise_role_ids() != '') {?>	
						<div class="tab-pane has-padding animated fadeInRight" id="bank_account">
							<?php if(in_array('bank-account-add',role_resource_ids()) || visa_wise_role_ids() != '') {?>
              <div class="add-form" style="display:none;">
								<div class="panel panel-flat">
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
                  <form id="bank_account_info" action="<?php echo site_url("employees/bank_account_info") ?>" name="bank_account_info" method="post">
                    <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
                    <input type="hidden" name="u_basic_info" value="UPDATE">
                    <div class="panel-body">
                      <div class="row">
                        <div class="col-sm-6">
                          <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_acc_title');?>" name="account_title" type="hidden" value="" id="account_name" value="Current Account">
                          
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
                        <div class="col-md-12">
                          <div class="form-group">
                            <button type="submit" class="btn bg-teal-400 save pull-right"><?php echo $this->lang->line('xin_save');?><i class="icon-spinner3 spinner position-left"></i></button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
							<?php } ?>

              <div class="panel panel-flat">
                <div class="panel-heading">
                  <h5 class="panel-title">
                    <strong><?php echo $this->lang->line('xin_list_all');?></strong> <?php echo $this->lang->line('xin_e_details_baccount');?>
                  </h5>
                  <div class="heading-elements">
                    <?php if(in_array('bank-account-add',role_resource_ids()) && visa_wise_role_ids() == '') {?>
                    <div class="add-record-btn">
                      <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_add_new');?></button>
                    </div>
							      <?php } ?>
		              </div>
						    </div>
                <div class="panel-body">
                  <table class="table" id="xin_table_bank_account">
                    <thead>
                      <tr>
                        <!--<th><?php echo $this->lang->line('xin_e_details_acc_title');?></th>-->
                        <th><?php echo $this->lang->line('xin_e_details_acc_number');?></th>
                        <th><?php echo $this->lang->line('xin_e_details_bank_name');?></th>
                        <th><?php echo $this->lang->line('xin_e_details_bank_code');?></th>
                        <th><?php echo $this->lang->line('xin_e_details_bank_branch');?></th>
                        <th><?php echo $this->lang->line('xin_action');?></th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
						</div>
            <?php } ?>
            <?php if(in_array('contract-view',role_resource_ids()) || visa_wise_role_ids() != '') {?>	
            <div class="tab-pane has-padding animated fadeInRight" id="contract">
              <?php if(in_array('contract-add',role_resource_ids()) || visa_wise_role_ids() != '') { ?>
              <div class="add-form" style="display:none;">
                <div class="panel panel-flat">
                  <div class="panel-heading">
                    <h5 class="panel-title">
                      <strong><?php echo $this->lang->line('xin_add_new');?></strong> <?php echo $this->lang->line('xin_e_details_contract');?>
                    </h5>
                    <div class="heading-elements">
                      <div class="add-record-btn">
                        <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_hide');?></button>
                      </div>
                    </div>
						      </div>
                  <form id="contract_info" action="<?php echo site_url("employees/contract_info") ?>" name="contract_info" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
                    <input type="hidden" name="u_basic_info" value="UPDATE">
                    <div class="panel-body">
                      <div class="col-md-6 no-padding-left">
                        <div class="form-group">
                          <label for="contract_type_id" class=""><?php echo $this->lang->line('xin_e_details_contract_type');?></label>
                          <select class="form-control" name="contract_type_id" onchange="contract_change(this.value)" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_one');?>">
                            <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
                            <?php foreach($all_contract_types as $contract_type) {?>
                            <option value="<?php echo $contract_type->type_id;?>"> <?php echo $contract_type->type_name;?></option>
                            <?php } ?>
                          </select>
                        </div>
                        <div class="form-group">
                          <label class="" for="from_date"><?php echo 'Contract Start Date';?></label>  
                          <div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                            <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_frm_date');?>" name="from_date" size="16" type="text" value="" readonly>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                          </div>
                        </div>
                        <input type="hidden" class="contract_hide" style="display:hide;" name="to_date">                          
                        <div class="form-group contract_show">
                          <label for="to_date"><?php echo 'Contract End Date';?></label>
                          <div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                            <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_to_date');?>" name="to_date" size="16" type="text" value="" readonly>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                          </div>
                        </div>
                      </div>
                
                      <div class="col-md-6">	
                        <div class="form-group">
                          <label for="description"><?php echo $this->lang->line('xin_description');?></label>
                          <textarea  style="min-height: 204px;" class="form-control" placeholder="<?php echo $this->lang->line('xin_description');?>" data-show-counter="1" data-limit="300" name="description" cols="30" rows="3" id="description"></textarea>
                          <span class="countdown"></span>
                        </div>
                      </div>

                      <div class="col-md-12 no-padding-left">
                        <div class="form-group">
                          <label for="contract_files" class="control-label"><?php echo $this->lang->line('xin_e_details_contract_file');?></label>
                          <br/>
                          <input type="file" class="file-input" name="document_file[]" multiple="multiple">
                          <small class="help-block"><?php echo $this->lang->line('xin_e_details_d_type_file');?></small>
                        </div>
                      </div>

                      <div class="col-md-12">
                        <div class="form-group">
                          <button type="submit" class="btn bg-teal-400 save pull-right"><?php echo $this->lang->line('xin_save');?><i class="icon-spinner3 spinner position-left"></i></button>
                        </div>
                      </div>
                    </div>
	                </form>
                </div>
              </div>
							<?php } ?>
              
              <div class="panel panel-flat">
                <div class="panel-heading">
                  <h5 class="panel-title">
                    <strong><?php echo $this->lang->line('xin_list_all');?></strong> <?php echo $this->lang->line('xin_e_details_contracts');?>
                  </h5>
                  <div class="heading-elements">
                    <?php if(in_array('contract-add',role_resource_ids()) && visa_wise_role_ids() == '') {?>
                      <div class="add-record-btn">
                        <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_add_new');?></button>
                      </div>
                    <?php } ?>
                  </div>
                </div>

                <div class="panel-body">
                  <table class="table" id="xin_table_contract">
                    <thead>
                      <tr>
                        <th><?php echo $this->lang->line('xin_e_details_duration');?></th>
                        <!--<th><?php //echo $this->lang->line('dashboard_designation');?></th>-->
                        <th><?php echo $this->lang->line('xin_e_details_contract_type');?></th>
                        <!--<th><?php echo $this->lang->line('dashboard_xin_title');?></th>-->
                        <th>File</th>
                        <th><?php echo $this->lang->line('xin_action');?></th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
						</div>
            <?php } ?>
            <?php if(in_array('pay-structure-view',role_resource_ids()) || visa_wise_role_ids() != '') {?>
            <div class="tab-pane has-padding animated fadeInRight" id="pay_structure">
              <?php if(in_array('pay-structure-add',role_resource_ids()) || visa_wise_role_ids() != '') {?>
              <div class="add-form" style="display:none;">
                <div class="panel panel-flat">
                  <div class="panel-heading">
                    <h5 class="panel-title">
                      <strong><?php echo $this->lang->line('xin_add_new');?></strong> <?php echo $this->lang->line('xin_e_details_pay');?>
                    </h5>

                    <div class="heading-elements">
                      <div class="add-record-btn">
                        <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_hide');?></button>
                      </div>
                    </div>
						      </div>
                  <div class="panel-body">
                    <div class="col-md-12">
                      <form class="form-hrm" action="<?php echo site_url("payroll/add_template") ?>" method="post" name="add_template" id="xin-form" autocomplete="off">
                        <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
                        <input type="hidden" name="employee_id" value="<?php echo $this->uri->segment(3);?>">
                        <div class="bg-white">
                          <div class="box-block">
                            <div class="row">
                              <div class="col-md-12">
                                <div class="row">
                                  <div id="appdend_div_paystructure">
                                    <?php if($salary_fields){
                                    foreach($salary_fields as $sal_field){
                                    ?>
                                      <div class="col-md-4">
                                        <div class="form-group">
                                          <label for="<?php echo $sal_field->salary_field_name;?>" class="control-label"><?php echo salary_title_change($sal_field->salary_field_name);?></label>
                                          <input class="form-control <?php echo $sal_field->salary_field_name;?> <?php if($sal_field->salary_calculate==1){?>salary<?php } ?>" placeholder="<?php echo salary_title_change($sal_field->salary_field_name);?>" name="sal_field[<?php echo $sal_field->salary_field_id;?>]" value="" type="text" pattern="\d*\.?\d*" title="<?php echo $this->lang->line('xin_use_numbers_price');?>">
                                        </div>
                                      </div>
                                    <?php  }  ?>
						                      </div>
                                  <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="dearness_allowance">Salary Based On Contract</label>
                                      <input class="form-control" placeholder="Salary Based On Contract" id="total_based_contract" name="salary_based_on_contract" value="" type="text" readonly pattern="\d*\.?\d*" title="<?php echo $this->lang->line('xin_use_numbers_price');?>">
                                    </div>
                                  </div>

                                  <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="dearness_allowance">Salary With Bonus</label>
                                      <input class="form-control" placeholder="Salary With Bonus" name="salary_with_bonus" id="total_with_bonus" value="" type="text" readonly pattern="\d*\.?\d*" title="<?php echo $this->lang->line('xin_use_numbers_price');?>">
                                    </div>
                                  </div>

                                  <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="effective_from_date">Effective From Date</label>
                                      <div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                                        <input class="form-control" placeholder="Effective From Date" name="effective_from_date" size="16" type="text" value="<?php echo format_date('d F Y',$date_of_joining);?>" readonly>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                      </div>
                                    </div>
                                  </div>

                                  <?php if($session['role_name']==AD_ROLE){?>
                                  <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="effective_from_date">Effective To Date</label>
                                      <div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                                        <input class="form-control" placeholder="Effective To Date" name="effective_to_date" size="16" type="text" value="" readonly>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                      </div>
                                    </div>
                                  </div>
                                  <?php } else{?>
                                    <input class="form-control" readonly placeholder="Effective To Date" name="effective_to_date" type="hidden" value="" readonly>
                                  <?php } ?>
                                  <?php } else { ?>
                                    <div class="col-md-12 no-padding text-danger">
                                      <div class="form-group">
                                      No salary components added yet in this country.
                                      </div>
                                    </div>
                                  <?php } ?>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>


                        <?php if($salary_fields){ ?>
                          <div class="row">
                            <div class="col-md-12">
                              <div class="form-group">
                                <button type="submit" class="btn bg-teal-400 save pull-right"><?php echo $this->lang->line('xin_save');?><i class="icon-spinner3 spinner position-left"></i></button>
                              </div>
                            </div>
                          </div>
                        <?php } ?>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
							<?php } ?>

              <div class="panel panel-flat">
                <div class="panel-heading">
                  <h5 class="panel-title">
                    <strong><?php echo $this->lang->line('xin_list_all');?></strong> Payment History
                  </h5>
                  <?php if(in_array('pay-structure-add',role_resource_ids()) && visa_wise_role_ids() == '') {?>
                  <div class="heading-elements">
                    <div class="add-record-btn">
                      <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_add_new');?></button>
                    </div>
                  </div>
                  <?php } ?>
                </div>

                <div class="panel-body">
                  <table class="table" id="xin_table_pay">
                    <thead>
                      <tr>
                        <th>Basic</th>
                        <th>Sal Based On Contract</th>
                        <th>Sal With Bonus</th>
                        <th>From Date</th>
                        <th>To Date</th>
                        <th>Created By</th>
                        <th>Created Date</th>
                        <th><?php echo $this->lang->line('xin_action');?></th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
              <div id="apps">
              </div>
            </div>
            <?php } ?>
            <?php if(in_array('change-password',role_resource_ids()) || visa_wise_role_ids() != '') {?>
            <div class="tab-pane has-padding animated fadeInRight" id="change_password">
              <div class="panel panel-flat">
                <form id="e_change_password" action="<?php echo site_url("employees/change_password");?>" name="e_change_password" method="post">
                  <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
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
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <?php if(in_array('change-password',role_resource_ids()) && visa_wise_role_ids() == '') {?>
                          <button type="submit" class="btn bg-teal-400 save pull-right"><?php echo $this->lang->line('xin_save');?><i class="icon-spinner3 spinner position-left"></i></button>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
						</div>
            <?php } ?>
            
            <div class="tab-pane has-padding animated fadeInRight" id="approval_status">
              <div class="panel panel-flat">
                <div class="panel-heading">
                  <h5 class="panel-title">
                    <strong>Undertakings</strong>
                  </h5>
                </div>
                <div class="panel-body">
                  <div class="form-group">
                      <?php 
                      $this->load->model("Employees_model");
                      $pendingNonDisclosureApproval = $this->Employees_model->getPendingNonDisclosureApproval($this->uri->segment(3),'employee');
                      if(empty($pendingNonDisclosureApproval)){
                      ?>
                        <label for="new_password_confirm" class="control-label">Non disclosure agreement - </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <button type="submit" onclick="create_approve_request('<?php echo $this->uri->segment(3);?>');" class="btn btn-success nondisclosure" id="submit" name="submit"/>Send</button>
                      <?php }elseif($pendingNonDisclosureApproval[0]['approval_status'] == 3){?>

                        <label for="new_password_confirm" class="control-label">Non disclosure agreement - </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label for="new_password_confirm" class="control-label">Rejected and resend for non disclosure  </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <button type="submit" onclick="create_approve_request('<?php echo $this->uri->segment(3);?>');" class="btn btn-success nondisclosure" id="submit" name="submit"/>Send</button>
                    <?php }elseif($pendingNonDisclosureApproval[0]['approval_status'] == 2){?>

                        <label for="new_password_confirm" class="control-label">Non disclosure agreement - </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="icon-clipboard5 position-left"></i> Successfully Approved</a></li>
                    <?php }elseif($pendingNonDisclosureApproval[0]['approval_status'] == 1){?>

                        <label for="new_password_confirm" class="control-label">Non disclosure agreement - </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <i class="icon-clipboard5 position-left"></i> Successfully send</a></li>
                    <?php }?>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
 </div>
                    
<script>
	<?php if($reporting_manager!=''){?>
    getDepartmentBasedEmployees('<?php echo $department_id;?>','<?php echo $user_id;?>','<?php echo $reporting_manager;?>');
  <?php } ?>

function getDepartmentBasedEmployees(departmentid,uemployeeid,manager_id){
      if (departmentid == ""){
        document.getElementById("reportingmanager").innerHTML = "";
        return;
      } else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }else {
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
	var htm=$("#document_type_id option:selected").text();
	//alert(htm);
	$('.change_type').val('');
	$('#doc_number').show();
	$('#name_of_doc').html(htm);
	$('#document_number').attr('placeholder',htm+' Number');
	$('#document_number').val('');

	$('.input-group-st').attr('style','display:inline');
	$('.visa_t_hide').hide();

	if(htm =='Visa'){
		$('#for_visa_select').show();
		$('#for_medical_card_select').hide();
		$('#country_show').hide();
		$('.entry_stamp_date').show();

		var v_type=$('.change_visa_t').val();
		if(v_type==0){
		$('#document_number').attr('pattern','\\d{3}[\\/]\\d{4}[\\/]\\d{7}');
		$('#document_number').attr('title','Should be Numeric & Format should be 123/1234/1234567');
		$('#document_number').attr('maxlength','16');
		}else{
		$('#document_number').attr('pattern','\\d{3}[\\/]\\d{4}[\\/]\\d{1}[\\/]\\d{7}');
		$('#document_number').attr('title','Should be Numeric & Format should be 123/1234/1/1234567');
		$('#document_number').attr('maxlength','18');
		}
		$('.input-group-st').attr('style','display:table');
		$('.visa_t_hide').show();
	}else if(htm =='Medical Card'){
		$('#for_medical_card_select').show();
		$('#for_visa_select').hide();
		$('#country_show').hide();
		$('.entry_stamp_date').hide();
		$('#document_number').attr('pattern','^[a-zA-Z0-9]+$');
		$('#document_number').attr('title','Should be Alphanumeric only');
	}else if(htm =='Passport'){
		$('#country_show').show();
		$('#for_medical_card_select').hide();
		$('#for_visa_select').hide();
		$('.entry_stamp_date').hide();
		$('#document_number').attr('pattern','^[a-zA-Z0-9]+$');
		$('#document_number').attr('title','Should be Alphanumeric only');

	}else if(htm =='Emirates Id'){
		$('#country_show').hide();
		$('#for_medical_card_select').hide();
		$('#for_visa_select').hide();
		$('.entry_stamp_date').hide();
		$('#document_number').attr('maxlength','18');
		$('#document_number').attr('pattern','\\d{3}[\\-]\\d{4}[\\-]\\d{7}[\\-]\\d{1}');
		$('#document_number').attr('title','Should be Numeric & Format should be 123-1234-1234567-1');
	}else if(htm =='Driving License'){
		$('#for_medical_card_select').hide();
		$('#for_visa_select').hide();
		$('#country_show').hide();
		$('.entry_stamp_date').hide();
		$('#document_number').attr('pattern','\\d*');
		$('#document_number').attr('title','Should be Numbers only');

	}else if(htm =='Labour Card'){
		$('#for_medical_card_select').hide();
		$('#for_visa_select').hide();
		$('#country_show').hide();
		$('.entry_stamp_date').hide();
		$('#document_number').removeAttr('pattern');
		$('#document_number').removeAttr('title');
		$('#edit_document_number').removeAttr('maxlength');

	}else{
		$('#for_medical_card_select').hide();
		$('#for_visa_select').hide();
		$('#country_show').hide();
		$('.entry_stamp_date').hide();
		$('#document_number').removeAttr('pattern');
		$('#document_number').removeAttr('title');
		$('#document_number').removeAttr('maxlength');
	}


}

function check_digits(val){
	var htm=$("#document_type_id option:selected").text();
	var values=$('#document_number').val();
	if(htm =='Emirates Id'){
	if(values.length == 3 || values.length == 8 || values.length == 16)
	  {
		$('#document_number').val(values+'-');
	  }
	}else if(htm =='Visa'){

		var v_type=$('.change_visa_t').val();

		if(v_type==0){

	if(values.length == 3 || values.length == 8)
	  {
		$('#document_number').val(values+'/');
	  }

		}else{
		if(values.length == 3 || values.length == 8  || values.length == 10)
	  {
		$('#document_number').val(values+'/');
	  }

		}
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
    //console.log(date1);
	//console.log(date2);
	// Now we convert the array to a Date object, which has several helpful methods
	date1 = new Date(date1[0], date1[1], date1[2]);
	date2 = new Date(date2[0], date2[1], date2[2]);

	var showmsg=Noofmonths(date1,date2);

	// We use the getTime() method and get the unixtime (in milliseconds, but we want seconds, therefore we divide it through 1000)
	date1_unixtime = parseInt(date1.getTime() / 1000);
	date2_unixtime = parseInt(date2.getTime() / 1000);

	// This is the calculated difference in seconds
	var timeDifference = date2_unixtime - date1_unixtime;

	// in Hours
	var timeDifferenceInHours = timeDifference / 60 / 60;

	// and finaly, in days :)
	var timeDifferenceInDays = timeDifferenceInHours  / 24;
	var tenure = timeDifferenceInDays/365;

	document.getElementById("tenure").value=tenure;
	document.getElementById("tenure1").value=showmsg;

}

var loadFile = function(event) {
  var output = document.getElementById('img-circle_a');
  output.src = URL.createObjectURL(event.target.files[0]);
};


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
  .select2-selection__rendered > i{display:none;}
  .select2-results__option > i{float:right;}
  #for_visa_select,#for_medical_card_select,#country_show, .entry_stamp_date{display:none;}
  ._jw-tpk-container ol > li > a {padding:1px 0px;}
  .kv-file-zoom {
      display: none !important;
  }
  .kv-file-upload,.fileinput-upload-button{
    display: none !important;
  }
</style>
