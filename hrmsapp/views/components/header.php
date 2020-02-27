<div id="cover-spin"></div>
<?php
$session 	= $this->session->userdata('username');
$system 	= $this->Xin_model->read_setting_info(1);
$user_info 	= $this->Xin_model->read_user_info($session['user_id']);
$role_user 	= $this->Xin_model->read_user_role_info($user_info[0]->user_role_id);
if($system[0]->system_skin=='skin-default'){
	$cl_skin = 'light';
} else if($system[0]->system_skin=='skin-1'){
	$cl_skin = 'dark';
} else if($system[0]->system_skin=='skin-2'){
	$cl_skin = 'light';
} else if($system[0]->system_skin=='skin-3'){
	$cl_skin = 'light';
} else if($system[0]->system_skin=='skin-4'){
	$cl_skin = 'dark';
} else if($system[0]->system_skin=='skin-5'){
	$cl_skin = 'dark';
} else if($system[0]->system_skin=='skin-6'){
	$cl_skin = 'dark';
}
?>
<?php
	$company = $this->Xin_model->read_company_setting_info(1);
	$logo_second = base_url().'uploads/logo/'.$company[0]->logo_second;
	$logo = base_url().'uploads/logo/'.$company[0]->logo;
?>
<div class="navbar navbar-inverse">
	<div class="navbar-header">
		<a class="navbar-brand" href="<?php echo site_url();?>dashboard/"><img src="<?php echo $logo?>" alt=""></a>
		<ul class="nav navbar-nav visible-xs-block">
			<li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
			<li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
		</ul>
	</div>
	<div class="navbar-collapse collapse" id="navbar-mobile">
		<ul class="nav navbar-nav">
			<li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a></li>
			<?php
			$notification_view_count ='';// $this->Xin_model->check_notification_view($user_info[0]->user_id,$session['role_name'],'count');
			$notification_view = '';//$this->Xin_model->check_notification_view($user_info[0]->user_id,$session['role_name'],'');
			//$paystructure_notification = $this->Xin_model->paystructure_notification();
			//$count_paystructure_notification=count($paystructure_notification);
			$count_notification=0;//count($notification_view_count);
			?>
			<li class="dropdown" style="display: none;">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<i class="icon-bell2"></i>
					<span class="visible-xs-inline-block position-right">Notifications</span>
					<?php if($count_notification!=0){?>
					<span class="badge bg-warning-400"><?php echo $count_notification;?></span>
					<?php }
					//else if($count_paystructure_notification!=0 || $count_paystructure_notification!=''){ ?>
					<!--<span class="badge bg-warning-400"><?php //echo $count_paystructure_notification;?></span>-->
					<?php //} ?>
				</a>
				<div class="dropdown-menu dropdown-content">
					<div class="dropdown-content-heading">
						<?php echo $this->lang->line('header_notifications');?>
					</div>
					<ul class="media-list dropdown-content-body width-350 animated <?php echo $system[0]->animation_effect_topmenu;?>">
						<?php
						$leave_codes=array('AL'=>'Annual Leave','SL-1'=>'Sick Leave','SL-2'=>'Sick Leave','SL-UP'=>'Sick Leave','ML-1'=>'Maternity Leave','ML-UP'=>'Maternity Leave','EL'=>'Emergency Leave','AA'=>'Authorised Absence'); //,'ML-2'=>'Maternity Leave'
						if($notification_view) {
						foreach($notification_view as $leave_notify){
						$notifi='';
						/*if($leave_notify->reporting_manager_notification==1){
							 if($leave_notify->reporting_manager_status==1){

							 $notifi= $leave_notify->first_name.' '.$leave_notify->middle_name.' '.$leave_notify->last_name.' '.'has applied for <b>'.$leave_codes[$leave_notify->leave_status_code].'</b> '.' on '.$this->Xin_model->set_date_format($leave_notify->from_date).' to '.$this->Xin_model->set_date_format($leave_notify->to_date);
							 }else if($leave_notify->status==2 && $user_info[0]->user_id!=$leave_notify->employee_id){
							 $notifi= 'HR Approved leave for '.$leave_notify->first_name.' '.$leave_notify->middle_name.' '.$leave_notify->last_name.' on '.$this->Xin_model->set_date_format($leave_notify->from_date).' to '.$this->Xin_model->set_date_format($leave_notify->to_date);
							 }else if($leave_notify->status==2 && $user_info[0]->user_id==$leave_notify->employee_id){
							  $notifi= 'HR Approved your leave on '.$this->Xin_model->set_date_format($leave_notify->from_date).' to '.$this->Xin_model->set_date_format($leave_notify->to_date);
							 }else if($leave_notify->status==3 && $user_info[0]->user_id!=$leave_notify->employee_id){
							 $notifi= 'HR Rejected leave for '.$leave_notify->first_name.' '.$leave_notify->middle_name.' '.$leave_notify->last_name.' on '.$this->Xin_model->set_date_format($leave_notify->from_date).' to '.$this->Xin_model->set_date_format($leave_notify->to_date);
							 }else if($leave_notify->status==3 && $user_info[0]->user_id==$leave_notify->employee_id){
							 $notifi= 'HR Rejected your leave on '.$this->Xin_model->set_date_format($leave_notify->from_date).' to '.$this->Xin_model->set_date_format($leave_notify->to_date);
							 }else{

							 }
						}
						else if($leave_notify->hr_notification==1){
							 if($leave_notify->reporting_manager_status==2 && $user_info[0]->user_id!=$leave_notify->employee_id){
							 $notifi= 'Reporting Manager forwarded '.$leave_notify->first_name.' '.$leave_notify->middle_name.' '.$leave_notify->last_name.' <b>'.$leave_codes[$leave_notify->leave_status_code].'</b> '.' for approval on '.$this->Xin_model->set_date_format($leave_notify->from_date).' to '.$this->Xin_model->set_date_format($leave_notify->to_date);
							 }else if($leave_notify->reporting_manager_status==2 && $user_info[0]->user_id==$leave_notify->employee_id){
							 $notifi= 'Reporting Manager Approved your leave on '.$this->Xin_model->set_date_format($leave_notify->from_date).' to '.$this->Xin_model->set_date_format($leave_notify->to_date);
							 }
						}
						else if($leave_notify->employee_notification==1){

							if($leave_notify->reporting_manager_status==3){
							 $notifi.= 'Reporting Manager Rejected your leave on '.$this->Xin_model->set_date_format($leave_notify->from_date).' to '.$this->Xin_model->set_date_format($leave_notify->to_date);
							}else if($leave_notify->reporting_manager_status==2){
							 $notifi.= 'Reporting Manager Approved your leave on '.$this->Xin_model->set_date_format($leave_notify->from_date).' to '.$this->Xin_model->set_date_format($leave_notify->to_date);
							}else if($leave_notify->status==2 && $leave_notify->reporting_manager_status==2){
							 $notifi.= 'HR Approved your leave on '.$this->Xin_model->set_date_format($leave_notify->from_date).' to '.$this->Xin_model->set_date_format($leave_notify->to_date);

							}else if($leave_notify->status==3 && $leave_notify->reporting_manager_status==2){
							 $notifi.= 'HR Rejected your leave on '.$this->Xin_model->set_date_format($leave_notify->from_date).' to '.$this->Xin_model->set_date_format($leave_notify->to_date);

							}
						}
						*/?>
						<li class="media">
							<div class="media-left">
								<a href="<?php echo site_url()?>timesheet/leave_details/id/<?php echo $leave_notify->leave_id;?>/" class="btn border-teal-400 text-teal-400 btn-flat btn-rounded btn-icon btn-sm"><i class="icon-bell2"></i></a>
							</div>
							<div class="media-body">
								<a style="color:black;" href="<?php echo site_url()?>timesheet/leave_details/id/<?php echo $leave_notify->leave_id;?>/"><?php echo $notifi;//$this->lang->line('header_has_applied_for_leave');?></a>
							</div>
						</li>
						<?php } ?>
					</ul>
					<div class="dropdown-content-footer">
						<a href="<?php echo site_url()?>timesheet/leave/" data-popup="tooltip" title="View all leave notification"><i class="icon-menu display-block"></i></a>
					</div>
						<?php } else if($paystructure_notification){

									//foreach($paystructure_notification as $pay_notify){
								//$pay_not='';
								?>
						<!--
								<li class="media">
													<div class="media-left">
														<a href="<?php //echo site_url()?>timesheet/leave_details/id/<?php //echo $leave_notify->leave_id;?>/" class="btn border-teal-400 text-teal-400 btn-flat btn-rounded btn-icon btn-sm"><i class="icon-bell2"></i></a>
													</div>

													<div class="media-body">
														<a style="color:black;" href="<?php echo site_url()?>payroll/templates/">Pay structure waiting to approval for <b><?php //echo change_fletter_caps($pay_notify->first_name.' '.$pay_notify->middle_name.' '.$pay_notify->last_name);?></b>
														</a>

													</div>
												</li>


						--><?php //}
									}
									else{ ?>
							<div class="dropdown-content-footer">
								<a class="dropdown-content-footer" href="#"> <strong><?php echo 'There is no notifications';?></strong> </a>
							</div>
							<?php } ?>
						</div>
			</li>
		</ul>
		<!--
		<p class="navbar-text"><span class="label bg-success">Online</span></p>-->
		<ul class="nav navbar-nav navbar-right">
			<!--<li class="dropdown language-switch">
				<a class="dropdown-toggle" data-toggle="dropdown">
					<img src="assets/images/flags/gb.png" class="position-left" alt="">
					English
					<span class="caret"></span>
				</a>

				<ul class="dropdown-menu">
					<li><a class="deutsch"><img src="assets/images/flags/de.png" alt=""> Deutsch</a></li>
					<li><a class="ukrainian"><img src="assets/images/flags/ua.png" alt=""> Українська</a></li>
					<li><a class="english"><img src="assets/images/flags/gb.png" alt=""> English</a></li>
					<li><a class="espana"><img src="assets/images/flags/es.png" alt=""> España</a></li>
					<li><a class="russian"><img src="assets/images/flags/ru.png" alt=""> Русский</a></li>
				</ul>
			</li>--><!--
			<?php
			$chat_counts = $this->Xin_model->check_chat_counts($user_info[0]->user_id,TODAY_DATE);
			if(count($chat_counts)!=0){
			?>
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<i class="icon-bubbles4"></i>
					<span class="visible-xs-inline-block position-right">Chat</span>
					<span class="badge bg-warning-400"><?php echo count($chat_counts);?></span>
				</a>
				<div class="dropdown-menu dropdown-content width-350">
					<div class="dropdown-content-heading">Chat</div>
					<ul class="media-list dropdown-content-body  animated <?php echo $system[0]->animation_effect_topmenu;?>">
						<?php
						if($chat_counts) {
						foreach($chat_counts as $chat_c){
						?>
						<?php  $chat_from_name= $this->Xin_model->read_user_info($chat_c->from_id);  ?>
						<li class="media">
							<div class="media-body">
								<a href="<?php echo base_url().'dashboard/birthday_wishes/'.base64_encode($chat_c->birthday_id);?>" class="media-heading">
									<?php
									if($chat_c->parent==0){
									  echo '<span class="text-semibold text-teal-400">'.$chat_from_name[0]->first_name.' '.$chat_from_name[0]->middle_name.' '.$chat_from_name[0]->last_name.'</span><span class="text-muted"> send you a message.</span>';
									}else{
									  echo '<span class="text-semibold text-teal-400">'.$chat_from_name[0]->first_name.' '.$chat_from_name[0]->middle_name.' '.$chat_from_name[0]->last_name.'</span><span class="text-muted"> reply your message.</span>';
								  	}
									?>
									<span class="media-annotation pull-right"><?php echo date('H:i',strtotime($chat_c->message_date));?></span>
								</a>
							</div>
						</li>
						<?php  } ?>
						<?php  } ?>
					</ul>

					<div class="dropdown-content-footer">
						<a href="<?php echo base_url().'dashboard/birthday_wishes/'.base64_encode($chat_c->birthday_id);?>" data-popup="tooltip" title="All Chats"><i class="icon-menu display-block"></i></a>
					</div>
				</div>
			</li>
	<?php } ?>
		    <?php if($system[0]->enable_policy_link=='yes'):?>
			<li class="nav-item"><a class="nav-link" href="#" data-toggle="modal" data-title="Policy" data-target=".policy">
			<i class="" style="font-weight: bold;font-size: 18px;">Policy</i>

			<span class="visible-xs-inline-block position-right"><?php echo $this->lang->line('header_policies');?></span></a>
			</li>
			<?php endif;?>
			-->
			<?php if(in_array('53',role_resource_ids()) || in_array('54',role_resource_ids()) || in_array('55',role_resource_ids()) || in_array('56',role_resource_ids())){?>
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<i class="icon-gear"></i>
					<span class="visible-xs-inline-block position-right">Setting</span>
				</a>
				<ul class="dropdown-menu dropdown-menu-right animated <?php echo $system[0]->animation_effect_topmenu;?>">
					  <?php if(in_array('53',role_resource_ids())){?>
						<li><a class="dropdown-item" href="<?php echo site_url()?>settings/"> <?php echo $this->lang->line('header_configuration');?> </a></li>
						<?php } ?>
						<?php if(in_array('54',role_resource_ids())){?>
						<li><a class="dropdown-item" href="<?php echo site_url()?>settings/constants/"> <?php echo $this->lang->line('left_constants');?> </a></li>
						<?php } ?>
						<?php if(in_array('55',role_resource_ids())){?>
						<li><a class="dropdown-item" href="<?php echo site_url()?>settings/email_template/"> <?php echo $this->lang->line('left_email_templates');?> </a></li>
						<?php } ?>
						<?php if(in_array('56',role_resource_ids())){?>
						<li><a class="dropdown-item" href="<?php echo site_url()?>settings/database_backup/"> <?php echo $this->lang->line('header_db_log');?> </a></li>
						<?php } ?>
				</ul>
			</li>
			<?php } ?>
			<li class="dropdown dropdown-user">
				<a class="dropdown-toggle" data-toggle="dropdown">
					<?php  if($user_info[0]->profile_picture!='' && $user_info[0]->profile_picture!='no file') {?>
							<img src="<?php  echo base_url().'uploads/profile/'.$user_info[0]->profile_picture;?>" alt=""
							>
							<?php } else {?>
							<?php  if($user_info[0]->gender=='Male') { ?>
							<?php 	$de_file = base_url().'uploads/profile/default_male.jpg';?>
							<?php } else { ?>
							<?php 	$de_file = base_url().'uploads/profile/default_female.jpg';?>
							<?php } ?>
							<img src="<?php  echo $de_file;?>" alt="">
							<?php  } ?>
							<span>
								<?php //$part_name= explode(' ',$user_info[0]->first_name);
									echo $user_info[0]->first_name.' '.$user_info[0]->middle_name.' '.$user_info[0]->last_name;
								?>
							</span>
					<i class="caret"></i>
				</a>
				<ul class="dropdown-menu dropdown-menu-right animated <?php echo $system[0]->animation_effect_topmenu;?>">
					<li><a href="<?php echo site_url()?>profile/"><i class="icon-user-plus"></i> <?php echo $this->lang->line('header_my_profile');?></a></li>
					<?php if(in_array('53',role_resource_ids())){?>
					<li><a href="<?php echo site_url()?>settings/"><i class="icon-cog5"></i> <?php echo $this->lang->line('left_settings');?></a></li>
					<?php  } ?>
					<li><a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target=".pro_change_password" data-profile_id="<?php echo $session['user_id'];?>"><i class="icon-key"></i> <?php echo $this->lang->line('header_change_password');?></a></li>	<li><a href="<?php echo site_url()?>logout/"><i class="icon-switch2"></i> <?php echo $this->lang->line('header_sign_out');?></a></li></ul>
			</li>
		</ul>
	</div>
</div>
<div class="modal fade pro_change_password animated <?php echo $system[0]->animation_effect_modal;?>" id="pro_change_password" tabindex="-1" role="dialog" aria-labelledby="pro_change_password" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" id="change_password_modal"></div>
  </div>
</div>
<div class="modal fade policy animated <?php echo $system[0]->animation_effect_modal;?>" id="policy" tabindex="-1" role="dialog" aria-labelledby="policy" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" id="policy_modal"></div>
  </div>
</div>
