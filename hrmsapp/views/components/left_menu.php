<?php
$session 			= $this->session->userdata('username');
$user_info 			= $this->Xin_model->read_user_info($session['user_id']);
$role_user 			= $this->Xin_model->read_user_role_info($user_info[0]->user_role_id);
$designation_info 	= $this->Xin_model->read_designation_info($user_info[0]->designation_id);
$system				= $this->Xin_model->read_setting_info(1);
?>
<div class="sidebar sidebar-main">
	<div class="sidebar-content">
		<!-- Main navigation -->
		<div class="sidebar-category sidebar-category-visible">
			<div class="category-content no-padding">
				<ul class="navigation navigation-main navigation-accordion">
					<?php
					if($role_user[0]->role_name!=AD_VIEW_ROLE && $role_user[0]->role_name!=AD_ROLE) { ?>
						<li class="navigation-header">
							<span><?php echo 'Menu';?></span>
							<i class="icon-menu" title="More"></i>
						</li>
					<?php } ?>
					<?php
					if($role_user[0]->role_name!=AD_VIEW_ROLE && $role_user[0]->role_name!=AD_ROLE) { ?>
						<li class="<?php if($path_url=='profile'){echo 'active';}?>">
							<a href="<?php echo site_url();?>profile/" class="waves-effect waves-light">
								<i class="icon-user-plus"></i>
								<span>My Profile</span></a>
						</li>
						<li class="<?php if($path_url=='user/user_leave'){echo 'active';}?>">
							<a href="<?php echo site_url();?>employee/leave/" class="waves-effect waves-light">
								<i class="icon-bed2"></i>
								<span>My <?php echo $this->lang->line('left_leave');?></span></a></li>
						<li class="<?php if($path_url=='user/user_attendance'){echo 'active';}?>"><a href="<?php echo site_url();?>employee/attendance/" class="waves-effect waves-light">
								<i class="icon-watch2"></i>
								<span>My <?php echo $this->lang->line('left_attendance');?></span>
							</a>
						</li>
						<?php if(!hod_manager_access()){ ?>
						<li class="<?php if($path_url=='user/user_ob_hours'){echo 'active';}?>"><a href="<?php echo site_url();?>employee/attendance/user_ob_hours/" class="waves-effect waves-light">
								<i class="icon-watch"></i>
								<span>My OB Hours</span>
							</a>
						</li>
						<?php } ?>

						<li class="<?php if($path_url=='employee_leavecard'){echo 'active';}?>"><a href="<?php echo site_url();?>employee/attendance/user_leave_card/" class="waves-effect waves-light">
								<i class="icon-display"></i>
								<span>My Leave Card</span>
							</a>
						</li>


					<?php } ?>
					<?php
					if($role_user[0]->role_name!=AD_VIEW_ROLE && $role_user[0]->role_name!=AD_ROLE &&
						(reporting_manager_access() || get_ceo_only() || hod_manager_access()) && (!in_array('32v',role_resource_ids())) ) { ?>
						<li class="with-sub">
							<a href="javascript:void(0);" class="waves-effect  waves-light">
								<i class="icon-rocket"></i>
								<span>Requests</span>
							</a>
							<ul>
								<li class="<?php if($path_url=='leave' || $path_url=='leave_details'){echo 'active';}?>"><a href="<?php echo site_url();?>timesheet/leave/">Team Leave Requests</a></li>
							</ul>
						</li>
					<?php } ?>
					<?php
					if($role_user[0]->role_name==AD_VIEW_ROLE || $role_user[0]->role_name==AD_ROLE) { ?>
						<li class="navigation-header">
							<span><?php echo $this->lang->line('dashboard_main');?></span>
							<i class="icon-menu" title="Main pages"></i>
						</li>
						<li class="<?php if($path_url=='dashboard'){echo 'active';}?>">
							<a class="waves-effect waves-light" href="<?php echo site_url('dashboard');?>">
								<i class="icon-home4"></i>
								<span><?php echo $this->lang->line('dashboard_title');?></span>
							</a>
						</li>
					<?php } ?>
					<?php
					if(in_array('3',role_resource_ids()) || in_array('3a',role_resource_ids()) || in_array('3e',role_resource_ids()) || in_array('3d',role_resource_ids()) ||
						in_array('3v',role_resource_ids()) || in_array('4',role_resource_ids()) || in_array('4a',role_resource_ids()) || in_array('4e',role_resource_ids()) ||
						in_array('4d',role_resource_ids()) || in_array('4v',role_resource_ids()) || in_array('5',role_resource_ids()) || in_array('5a',role_resource_ids()) ||
						in_array('5e',role_resource_ids()) || in_array('5d',role_resource_ids()) || in_array('5v',role_resource_ids()) || in_array('6',role_resource_ids()) ||
						in_array('6a',role_resource_ids()) || in_array('6e',role_resource_ids()) || in_array('6d',role_resource_ids()) || in_array('6v',role_resource_ids())){ ?>
						<li class="">
							<a href="javascript:void(0);" class="waves-effect waves-light">
								<i class="icon-office"></i>
								<span><?php echo $this->lang->line('left_organization');?></span>
							</a>
							<ul>
								<?php
								if(in_array('3',role_resource_ids()) || in_array('3a',role_resource_ids()) || in_array('3e',role_resource_ids()) || in_array('3d',role_resource_ids())
									|| in_array('3v',role_resource_ids())) { ?>
									<li class="<?php if($path_url=='company'){echo 'active';}?>">
										<a href="<?php echo site_url('company')?>"><?php echo $this->lang->line('left_company');?></a>
									</li>
								<?php } ?>
								<?php
								if(in_array('4',role_resource_ids()) || in_array('4a',role_resource_ids()) || in_array('4e',role_resource_ids()) || in_array('4d',role_resource_ids())
									|| in_array('4v',role_resource_ids())) { ?>
									<li class="<?php if($path_url=='location'){echo 'active';}?>">
										<a href="<?php echo site_url();?>location"><?php echo $this->lang->line('left_location');?></a>
									</li>
								<?php } ?>
								<?php
								if(in_array('5',role_resource_ids()) || in_array('5a',role_resource_ids()) || in_array('5e',role_resource_ids()) || in_array('5d',role_resource_ids()) ||
									in_array('5v',role_resource_ids())) { ?>
									<li class="<?php if($path_url=='department'){echo 'active';}?>">
										<a href="<?php echo site_url();?>department"><?php echo $this->lang->line('left_department');?></a>
									</li>
								<?php } ?>
								<?php
								if(in_array('6',role_resource_ids()) || in_array('6a',role_resource_ids()) || in_array('6e',role_resource_ids()) || in_array('6d',role_resource_ids()) ||
									in_array('6v',role_resource_ids())) { ?>
									<li class="<?php if($path_url=='designation'){echo 'active';}?>">
										<a href="<?php echo site_url();?>designation"><?php echo $this->lang->line('left_designation');?></a>
									</li>
								<?php } ?>
							</ul>
						</li>
					<?php } ?>

					<?php
					if(in_array('employees-list-view',role_resource_ids()) || in_array('basic-info-view',role_resource_ids()) || in_array('documents-view',role_resource_ids()) || in_array('offer-letter-view',role_resource_ids()) || in_array('contract-view',role_resource_ids()) || in_array('pay-structure-view',role_resource_ids()) || in_array('bank-account-view',role_resource_ids()) || in_array('emergency-contacts-view',role_resource_ids()) ||  in_array('qualification-view',role_resource_ids()) ||  in_array('work-experience-view',role_resource_ids()) ||  in_array('change-password',role_resource_ids()) || in_array('15',role_resource_ids()) || in_array('15a',role_resource_ids()) || in_array('15e',role_resource_ids()) ||
						in_array('15d',role_resource_ids()) || in_array('15v',role_resource_ids()) || in_array('16',role_resource_ids()) || in_array('16a',role_resource_ids()) ||
						in_array('16e',role_resource_ids()) || in_array('16d',role_resource_ids()) || in_array('16v',role_resource_ids()) || in_array('17',role_resource_ids()) ||
						in_array('17a',role_resource_ids()) || in_array('17e',role_resource_ids()) || in_array('17d',role_resource_ids()) || in_array('17v',role_resource_ids()) ||
						in_array('18',role_resource_ids()) || in_array('18a',role_resource_ids()) || in_array('18e',role_resource_ids()) || in_array('18d',role_resource_ids()) ||
						in_array('18v',role_resource_ids()) || in_array('20',role_resource_ids()) || in_array('20a',role_resource_ids()) || in_array('20e',role_resource_ids()) ||
						in_array('20d',role_resource_ids()) || in_array('20v',role_resource_ids()) || in_array('21',role_resource_ids()) || in_array('21a',role_resource_ids()) ||
						in_array('21e',role_resource_ids()) || in_array('21d',role_resource_ids()) || in_array('21v',role_resource_ids()) || in_array('22',role_resource_ids()) ||
						in_array('22a',role_resource_ids()) || in_array('22e',role_resource_ids()) || in_array('22d',role_resource_ids()) || in_array('22v',role_resource_ids()) ||
						in_array('23',role_resource_ids()) || in_array('23a',role_resource_ids()) || in_array('23e',role_resource_ids()) || in_array('23d',role_resource_ids()) ||
						in_array('23v',role_resource_ids()) || in_array('27',role_resource_ids()) || in_array('27a',role_resource_ids()) || in_array('27e',role_resource_ids()) ||
						in_array('27d',role_resource_ids()) || in_array('27v',role_resource_ids()) || visa_wise_role_ids() != ''){?>
						<li class="with-sub">
							<a href="javascript:void(0);" class="waves-effect  waves-light">
								<i class="icon-user"></i>
								<span><?php echo $this->lang->line('dashboard_employees');?></span>
							</a>
							<ul>
								<?php
								if(in_array('employees-list-view',role_resource_ids()) || in_array('basic-info-view',role_resource_ids()) || in_array('documents-view',role_resource_ids()) || in_array('offer-letter-view',role_resource_ids()) || in_array('contract-view',role_resource_ids()) || in_array('pay-structure-view',role_resource_ids()) || in_array('bank-account-view',role_resource_ids()) || in_array('emergency-contacts-view',role_resource_ids()) ||  in_array('qualification-view',role_resource_ids()) ||  in_array('work-experience-view',role_resource_ids()) ||  in_array('change-password',role_resource_ids()) || visa_wise_role_ids() != '') { ?>
									<li class="<?php if(($path_url=='employees' || $path_url=='employees_detail') && !(isset($sub_path)) ){echo 'active';}?>">
										<a href="<?php echo site_url();?>employees"><?php echo $this->lang->line('dashboard_employees');?></a>
									</li>
								<?php } ?>
								<?php
								if(in_array('15',role_resource_ids()) || in_array('15a',role_resource_ids()) || in_array('15e',role_resource_ids()) || in_array('15d',role_resource_ids()) ||
									in_array('15v',role_resource_ids())) { ?>
									<!--<li class="<?php if($path_url=='awards'){echo 'active';}?>"><a href="<?php echo site_url();?>awards"><?php echo $this->lang->line('left_awards');?></a></li>-->
								<?php } ?>
								<?php
								if(in_array('16',role_resource_ids()) || in_array('16a',role_resource_ids()) || in_array('16e',role_resource_ids()) || in_array('16d',role_resource_ids()) ||
									in_array('16v',role_resource_ids())) { ?>
									<li class="<?php if($path_url=='transfers'){echo 'active';}?>">
										<a href="<?php echo site_url();?>transfers"><?php echo $this->lang->line('left_transfers');?></a>
									</li>
								<?php } ?>
								<?php
								if(in_array('17',role_resource_ids()) || in_array('17a',role_resource_ids()) || in_array('17e',role_resource_ids()) ||
									in_array('17d',role_resource_ids()) || in_array('17v',role_resource_ids())) { ?>
									<li class="<?php if($path_url=='resignation'){echo 'active';}?>">
										<a href="<?php echo site_url();?>resignation"><?php echo $this->lang->line('left_resignations');?></a>
									</li>
								<?php } ?>
								<?php
								if(in_array('18',role_resource_ids()) || in_array('18a',role_resource_ids()) || in_array('18e',role_resource_ids()) || in_array('18d',role_resource_ids()) ||
									in_array('18v',role_resource_ids())) { ?>
									<li class="<?php if($path_url=='travel'){echo 'active';}?>">
										<a href="<?php echo site_url();?>travel"><?php echo $this->lang->line('left_travels');?></a>
									</li>
								<?php } ?>
								<?php
								if(in_array('20',role_resource_ids()) || in_array('20a',role_resource_ids()) || in_array('20e',role_resource_ids()) || in_array('20d',role_resource_ids()) ||
									in_array('20v',role_resource_ids())) { ?>
									<li class="<?php if($path_url=='promotion'){echo 'active';}?>">
										<a href="<?php echo site_url();?>promotion"><?php echo $this->lang->line('left_promotions');?></a>
									</li>
								<?php } ?>
								<?php
								if(in_array('21',role_resource_ids()) || in_array('21a',role_resource_ids()) || in_array('21e',role_resource_ids()) || in_array('21d',role_resource_ids()) ||
									in_array('21v',role_resource_ids())) { ?>
									<li class="<?php if($path_url=='complaints'){echo 'active';}?>">
										<a href="<?php echo site_url();?>complaints"><?php echo $this->lang->line('left_complaints');?></a>
									</li>
								<?php } ?>
								<?php
								if(in_array('22',role_resource_ids()) || in_array('22a',role_resource_ids()) || in_array('22e',role_resource_ids()) || in_array('22d',role_resource_ids()) ||
									in_array('22v',role_resource_ids())) { ?>
									<li class="<?php if($path_url=='warning'){echo 'active';}?>">
										<a href="<?php echo site_url();?>warning"><?php echo $this->lang->line('left_warnings');?></a>
									</li>
								<?php } ?>
								<?php
								if(in_array('23',role_resource_ids()) || in_array('23a',role_resource_ids()) || in_array('23e',role_resource_ids()) || in_array('23d',role_resource_ids()) ||
									in_array('23v',role_resource_ids())) { ?>
									<li class="<?php if($path_url=='termination'){echo 'active';}?>">
										<a href="<?php echo site_url();?>termination"><?php echo $this->lang->line('left_terminations');?></a>
									</li>
									<li class="<?php if($path_url=='suspension'){echo 'active';}?>">
										<a href="<?php echo site_url();?>suspension"><?php echo $this->lang->line('left_suspensions');?></a>
									</li>
								<?php } ?>
								<?php
								if(in_array('27',role_resource_ids()) || in_array('27a',role_resource_ids()) || in_array('27e',role_resource_ids()) || in_array('27d',role_resource_ids()) ||
									in_array('27v',role_resource_ids())) { ?>
									<li class="<?php if($path_url=='employee_exit'){echo 'active';}?>">
										<a href="<?php echo site_url();?>employee_exit"><?php echo $this->lang->line('left_employees_exit');?></a>
									</li>
								<?php } ?>
							</ul>
						</li>
					<?php } ?>
					<?php
					if(in_array('30',role_resource_ids()) || in_array('32',role_resource_ids()) || in_array('32a',role_resource_ids()) || in_array('32e',role_resource_ids()) ||
						in_array('32d',role_resource_ids()) || in_array('32v',role_resource_ids()) || in_array('35',role_resource_ids()) || in_array('35a',role_resource_ids()) ||
						in_array('35e',role_resource_ids()) ||  in_array('35d',role_resource_ids()) || in_array('35v',role_resource_ids()) || in_array('32lv',role_resource_ids()) || in_array('31v',role_resource_ids()) || visa_wise_role_ids() != '' || reporting_manager_access() || hod_manager_access()){ ?>
						<li class="with-sub">
							<a href="javascript:void(0);" class="waves-effect  waves-light">
								<i class="icon-watch2"></i>
								<span><?php echo $this->lang->line('left_timesheet');?></span>
							</a>
							<ul>
								<?php
								if(in_array('30',role_resource_ids()) || visa_wise_role_ids() != '') { ?>
									<li class="<?php if($path_url=='date_wise_attendance'){echo 'active';}?>">
										<a href="<?php echo site_url();?>timesheet/date_wise_attendance/?loc=1"><?php echo $this->lang->line('left_attendance');?></a>
									</li>
								<?php } ?>
								<?php
								if(in_array('31v',role_resource_ids()) || visa_wise_role_ids() != '') { ?>
									<li class="<?php if($path_url=='update_attendance'){echo 'active';}?>">
										<a href="<?php echo base_url();?>timesheet/add_manual_ob_hours/">Add Manual OB Hours</a>
									</li>
									<li class="<?php if($path_url=='bus_lateness'){echo 'active';}?>">
										<a href="<?php echo base_url();?>timesheet/bus_lateness/">Bus Lateness</a>
									</li>
								<?php }	?>
								<?php
								if(in_array('32',role_resource_ids()) || in_array('32a',role_resource_ids()) || in_array('32e',role_resource_ids()) || in_array('32d',role_resource_ids()) ||
									in_array('32v',role_resource_ids()) || visa_wise_role_ids() != '') { ?>
									<li class="<?php if($path_url=='leave' || $path_url=='leave_details'){echo 'active';}?>">
										<a href="<?php echo site_url();?>timesheet/leave/"><?php echo $this->lang->line('left_leaves');?></a>
									</li>
								<?php } ?>
								<?php
								    if(reporting_manager_access() || hod_manager_access() || in_array('32lv',role_resource_ids()) || visa_wise_role_ids() != '') { ?>	
									<li class="<?php if($path_url=='leavecard'){echo 'active';}?>">
										<a href="<?php echo site_url();?>timesheet/leavecard/">Employee's Leave Card</a>
									</li>
								<?php } ?>
								<?php
								if(in_array('35',role_resource_ids()) || in_array('35a',role_resource_ids()) || in_array('35e',role_resource_ids()) || in_array('35d',role_resource_ids()) ||
									in_array('35v',role_resource_ids()) || visa_wise_role_ids() != '') { ?>
									<li class="<?php if($path_url=='holidays'){echo 'active';}?>">
										<a href="<?php echo site_url();?>timesheet/holidays/"><?php echo $this->lang->line('left_holidays');?></a>
									</li>
								<?php } ?>
								<?php
								if(in_array('bio-user-view',role_resource_ids()) || visa_wise_role_ids() != '') { ?>
									<li class="<?php if($path_url=='biometric_users_list'){echo 'active';}?>">
										<a href="<?php echo site_url();?>timesheet/biometric_users_list/">Biometric Users List</a>
									</li>
								<?php } ?>
								<?php
								if(in_array('al-expiry-view',role_resource_ids()) || visa_wise_role_ids() != '') { ?>
									<li class="<?php if($path_url=='leave_expiry_adjustments'){echo 'active';}?>">
										<a href="<?php echo site_url();?>timesheet/leave_expiry_adjustments/">AL Expiry Adjustments</a>
									</li>
								<?php } ?>
							</ul>
						</li>
					<?php } ?>

					<?php
					if(in_array('38',role_resource_ids()) || in_array('38a',role_resource_ids()) || in_array('38e',role_resource_ids()) || in_array('38d',role_resource_ids()) ||
						in_array('38v',role_resource_ids()) || in_array('39',role_resource_ids()) || in_array('40',role_resource_ids()) || in_array('41',role_resource_ids()) ||
						in_array('41L',role_resource_ids()) || in_array('42',role_resource_ids())  || in_array('38tv',role_resource_ids())  || in_array('38te',role_resource_ids())){?>
						<li class="with-sub">
							<a href="javascript:void(0);" class="waves-effect  waves-light">
								<i class="icon-calculator"></i>
								<span><?php echo $this->lang->line('left_payroll');?></span>
							</a>
							<ul>
								<?php
								//$query=$this->db->query("SELECT emp.user_id,emp.email FROM `xin_departments` as dep left join xin_employees as emp on emp.user_id=dep.employee_id WHERE dep.department_name='MD' limit 1");
								//$result=$query->result();
								//$HR_L_ROLE = unserialize(HR_L_ROLE); || (in_array($session['role_name'],$HR_L_ROLE))
								//if(($session['user_id']==$result[0]->user_id) || ($session['role_name']==AD_ROLE)){
								if(in_array('38tv',role_resource_ids()) || in_array('38te',role_resource_ids())) {
									?>
									<li class="<?php if($path_url=='payroll_templates'){echo 'active';}?>">
										<a href="<?php echo site_url();?>payroll/templates/"><?php echo $this->lang->line('left_payroll_templates');?></a>
									</li>
								<?php } ?>

								<?php
								if(in_array('41',role_resource_ids())) { ?>
									<li class="<?php if($path_url=='generate_payslip'){echo 'active';}?>">
										<a href="<?php echo site_url();?>payroll/generate_payslip/"><?php echo $this->lang->line('left_generate_payslip');?></a>
									</li>
								<?php } ?>

								<?php
								if(in_array('41L',role_resource_ids())) { ?>
									<li class="<?php if($path_url=='leave_settlement'){echo 'active';}?>">
										<a href="<?php echo site_url();?>payroll/leave_settlement/">Leave Settlement</a>
									</li>
								<?php } ?>
								<?php
								if(in_array('42',role_resource_ids())) { ?>
									<li class="<?php if($path_url=='payment_history' || $path_url=="payslip"){echo 'active';}?>">
										<a href="<?php echo site_url();?>payroll/payment_history/"><?php echo $this->lang->line('left_payment_history');?></a>
									</li>
								<?php } ?>
								<?php
								if(in_array('38',role_resource_ids()) || in_array('38a',role_resource_ids()) || in_array('38e',role_resource_ids()) || in_array('38d',role_resource_ids()) ||
									in_array('38v',role_resource_ids())) { ?>
									<li class="<?php if($path_url=='adjustments'){echo 'active';}?>">
										<a href="#">Adjustments</a>
										<ul>
											<li class="<?php if($path_url=='external_adjustments'){echo 'active';}?>"><a href="<?php echo site_url();?>payroll/external_adjustments/">External Adjustments</a></li>
											<li class="<?php if($path_url=='internal_adjustments'){echo 'active';}?>"><a href="<?php echo site_url();?>payroll/internal_adjustments/">Internal Adjustments</a></li>
										</ul>
									</li>
								<?php } ?>
							</ul>
						</li>
					<?php  } ?>

					<?php
					if($role_user[0]->role_name==AD_ROLE || in_array('request-passport-view',role_resource_ids()) || in_array('request-others-view',role_resource_ids()) || in_array($session['user_id'], email_settings_data())) { ?>
					<li class="">
						<a href="javascript:void(0);" class="waves-effect waves-light">
							<i class="icon-rocket"></i>
							<span>Requests To Employer</span>
						</a>
						<ul>
							<?php
						if(in_array('request-passport-view',role_resource_ids())) { ?>
							<li class="<?php if($path_url=='request_document'){echo 'active';}?>" style="">
								<a href="<?php echo site_url();?>employees/passport_request_document">Request For Passport Release</a>
							</li>
							<?php }?>

						<?php
						if(in_array('request-others-view',role_resource_ids()) || in_array($session['user_id'], email_settings_data())) { ?>
							<li class="<?php if($path_url=='request_document_other'){echo 'active';}?>">
								<a href="<?php echo site_url();?>employees/other_request_document">Request For Others</a>
							</li>
							<?php }?>
						</ul>		

					</li>
					<?php }?>

					<?php
					if($role_user[0]->role_name==AD_ROLE || $role_user[0]->role_name==AD_VIEW_ROLE) { ?>
						<li class="navigation-header">
							<span><?php echo $this->lang->line('left_more');?></span>
							<i class="icon-menu" title="More"></i>
						</li>
					<?php } ?>
					<?php
					if(in_array('53',role_resource_ids()) || in_array('53e',role_resource_ids()) || in_array('53v',role_resource_ids())){ ?>
						<li class="<?php if($path_url=='settings'){echo 'active';}?>">
							<a href="<?php echo site_url();?>settings/" class="waves-effect waves-light">
								<i class="icon-gear"></i>
								<span><?php echo $this->lang->line('left_settings');?></span>
							</a>
						</li>
					<?php } ?>
					<?php
					if(in_array('14',role_resource_ids()) || in_array('14a',role_resource_ids()) || in_array('14e',role_resource_ids()) || in_array('14d',role_resource_ids()) ||
						in_array('14v',role_resource_ids())) { ?>
						<li class="<?php if($path_url=='roles'){echo 'active';}?>">
							<a href="<?php echo site_url();?>roles">
								<i class="icon-user-check"></i>
								<span><?php echo $this->lang->line('left_set_roles');?></span>
							</a>
						</li>
					<?php } ?>
					<?php
					if(in_array('54',role_resource_ids()) || in_array('54a',role_resource_ids()) || in_array('54e',role_resource_ids()) || in_array('54d',role_resource_ids()) ||
						in_array('54v',role_resource_ids())) { ?>
						<li class="<?php if($path_url=='constants'){echo 'active';}?>">
							<a href="<?php echo site_url();?>settings/constants" class="waves-effect waves-light">
								<i class="icon-three-bars"></i>
								<span><?php echo $this->lang->line('left_constants');?></span>
							</a>
						</li>
					<?php } ?>

					<?php
					if(in_array('34v',role_resource_ids())) { ?>
						<li class="">
							<a href="javascript:void(0);" class="waves-effect waves-light">
								<i class="icon-sort-time-asc"></i>
								<span>Shift Management</span>
							</a>
							<ul>
								<li class="<?php if($path_url=='office_shift'){echo 'active';}?>">
									<a href="<?php echo site_url();?>timesheet/office_shift/">
										<span> <?php echo $this->lang->line('left_office_shifts');?></span>
									</a>
								</li>
								<li class="<?php if($path_url=='change_schedule'){echo 'active';}?>">
									<a href="<?php echo site_url();?>timesheet/change_schedule/">
										<span> <?php echo 'Change Schedule';?></span>
									</a>
								</li>
								<li class="<?php if($path_url=='ramadan_schedule'){echo 'active';}?>">
									<a href="<?php echo site_url();?>timesheet/ramadan_schedule/">
										<span> <?php echo 'Exceptional Schedule';?></span>
									</a>
								</li>
							</ul>
						</li>
					<?php } ?>
					<?php
					if(in_array('26',role_resource_ids())) { ?>
						<li class="<?php if($path_url=='employees_last_login'){echo 'active';}?>">
							<a href="<?php echo site_url();?>employees_last_login">
								<i class="icon-last"></i>
								<span><?php echo $this->lang->line('left_employees_last_login');?><span>
							</a>
						</li>
					<?php } ?>
					<?php
					if(in_array('52',role_resource_ids())){?>
						<li class="<?php if($path_url=='employees_directory'){echo 'active';}?>">
							<a href="<?php echo site_url();?>employees/directory/" class="waves-effect waves-light">
								<i class="icon-address-book3"></i>
								<span><?php echo $this->lang->line('left_employees_directory');?></span>
							</a>
						</li>
					<?php } ?>
					<?php
					if(in_array('56',role_resource_ids())){?>
						<li class="<?php if($path_url=='database_backup'){echo 'active';}?>">
							<a href="<?php echo site_url();?>settings/database_backup/" class="waves-effect waves-light">
								<i class="icon-database"></i>
								<span class="s-text"><?php echo $this->lang->line('left_db_backup');?></span>
							</a>
						</li>
					<?php } ?>
					<?php
					if(in_array('59',role_resource_ids())){?>
						<li class="<?php if($path_url=='user_logs'){echo 'active';}?>">
							<a href="<?php echo site_url();?>settings/user_logs/" class="waves-effect waves-light">
								<i class="icon-list-unordered"></i>
								<span class="s-text">Activity Logs</span>
							</a> 
						</li>					
					<?php } ?>
					<?php
					if(in_array('system-logs',role_resource_ids())){?>
					<li class="<?php if($path_url=='system_logs'){echo 'active';}?>">
						<a href="<?php echo site_url();?>settings/system_logs/" class="waves-effect waves-light">
							<i class="icon-list-unordered"></i>
							<span class="s-text">System Logs</span>
						</a> 
					</li>
					<?php } ?>

					<?php
					if(in_array('58',role_resource_ids())){?>
						<li class="<?php if($path_url=='attendance_manual_sync'){echo 'active';}?>">
							<a href="<?php echo site_url();?>attendancecron/attendance_manual_sync/" class="waves-effect waves-light">
								<i class="icon-sync"></i>
								<span class="s-text">Manual Attendance Sync</span>
							</a>
						</li>
					<?php } ?>
					<?php
					if(in_array('55',role_resource_ids())){?>
						<li class="<?php if($path_url=='email_template'){echo 'active';}?>">
							<a href="<?php echo site_url();?>settings/email_template/" class="waves-effect waves-light">
								<i class="icon-mail5"></i>
								<span><?php echo $this->lang->line('left_email_templates');?></span>
							</a>
						</li>
					<?php } ?>
					<?php
					if(in_array('57',role_resource_ids())) { ?>
						<li class="<?php if($path_url=='leaves_upload'){echo 'active';}?>">
							<a href="<?php echo site_url();?>settings/leaves_upload/" class="waves-effect waves-light">
								<i class="icon-file-upload2"></i>
								<span>Bulk Upload</span>
							</a>
						</li>
					<?php } ?>

					<?php
					if(in_array('60b',role_resource_ids())  || in_array('60c-view',role_resource_ids())) { ?>
						<li class="">
							<a href="javascript:void(0);" class="waves-effect waves-light">  <i class="icon-file-excel"></i>
								<span>Reports</span>
							</a>
							<ul>
								<?php
								if(in_array('60c-view',role_resource_ids())) { ?>
									<li class="<?php if(isset($sub_path)){echo 'active';}?>">
										<a href="<?php echo site_url();?>employees/report">
											<span> Employees Report</span>
										</a>
									</li>
								<?php } ?>
								<?php
								if(in_array('60b',role_resource_ids())) { ?>
									<li class="<?php if($path_url=='uploaded_documents'){echo 'active';}?>">
										<a href="<?php echo site_url();?>reports/uploaded_documents/">
											<span> Uploaded Documents</span>
										</a>
									</li>
								<?php } ?>
							</ul>
						</li>
					<?php } ?>
					<li class="<?php if($path_url=='policy'){echo 'active';}?>">
						<a href="<?php echo base_url();?>policy" class="waves-effect waves-light">
							<i class="icon-file-text"></i>
							<span class="s-text">Policies</span>
						</a>
					</li>
					<?php
					if($role_user[0]->role_name!=AD_VIEW_ROLE && $role_user[0]->role_name!=AD_ROLE &&
						(rp_manager_access() || hod_manager_access())) { ?>
					<li class="<?php if($path_url=='update_attendance'){echo 'active';}?>">
						<a href="<?php echo base_url();?>timesheet/add_manual_ob_hours/" class="waves-effect waves-light">
							<i class="icon-watch2"></i>
							<span class="s-text">Add Manual OB Hours</span>
						</a>
					</li>

					<li class="<?php if($path_url=='change_schedule'){echo 'active';}?>">
						<a href="<?php echo site_url();?>timesheet/change_schedule/">
							<i class="icon-sort-time-asc"></i>
							<span> <?php echo 'Shift Manage (Change Schedule)';?></span>
						</a>
					</li>
					<?php } ?>					
					<li class="<?php if($path_url=='logout'){echo 'active';}?>">
						<a href="<?php echo site_url();?>logout" class="waves-effect waves-light">
							<i class="icon-switch2"></i>
							<span class="s-text"><?php echo $this->lang->line('left_logout');?></span>
						</a>
					</li>
				</ul>
			</div>
		</div>
		<!-- /main navigation -->
	</div>
</div>
