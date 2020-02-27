<style type="text/css">
#canvas_id {
    pointer-events: none;
}
</style>
<?php
$session = $this->session->userdata('username');
$system = $this->Xin_model->read_setting_info(1);
$user_info = $this->Xin_model->read_user_info($session['user_id']);
$role = $this->Xin_model->read_user_role_info($user_info[0]->user_role_id);
$designation = $this->Designation_model->read_designation_information($user_info[0]->designation_id);
?>
<link href="<?php echo base_url();?>assets/css/icons/fontawesome/styles.min.css" rel="stylesheet" type="text/css">
<?php if(in_array('0d',role_resource_ids())) { ?>
<!--<div class="row row-md mb-1">
	<div class="col-lg-12">
		<div class="panel panel-flat">
			<div class="panel-heading"></div>
			<div class="container-fluid">
				<div class="row">
					<div class="col-lg-3">
						<ul class="list-inline text-center">
							<li>
								<a href="#" class="btn border-indigo-400 text-indigo-400 btn-flat btn-rounded btn-icon btn-xs valign-text-bottom"><i class="icon-people"></i></a>
							</li>
							<li class="text-left">
								<div class="text-semibold">Active Employees</div>
								<div class="text-muted"><span class="status-mark border-success position-left"></span> <?php echo $this->Employees_model->get_total_employees('active');?></div>
							</li>
						</ul>
						<div class="col-lg-10 col-lg-offset-1">
							<div class="content-group" id="active-employees"></div>
						</div>
					</div>
					<div class="col-lg-3">
						<ul class="list-inline text-center">
							<li>
								<a href="#" class="btn border-teal text-teal btn-flat btn-rounded btn-icon btn-xs valign-text-bottom"><i class="icon-coins"></i></a>
							</li>
							<li class="text-left">
								<div class="text-semibold"><?php echo $this->lang->line('dashboard_total_expenses');?></div>
								<div class="text-muted"><?php //$exp_am = $this->Expense_model->get_total_expenses();?>0AED</div>
							</li>
						</ul>
						<div class="col-lg-10 col-lg-offset-1">
							<div class="content-group" id="total-expense"></div>
						</div>
					</div>
					<div class="col-lg-3">
						<ul class="list-inline text-center">
							<li>
								<a href="#" class="btn border-warning-400 text-warning-400 btn-flat btn-rounded btn-icon btn-xs valign-text-bottom"><i class="icon-cash"></i></a>
							</li>
							<li class="text-left">
								<div class="text-semibold"><?php echo $this->lang->line('dashboard_total_salaries');?></div>
								<div class="text-muted"><?php $all_sal = $this->Xin_model->get_total_salaries_paid();?>
<?php //if($all_sal[0]->paid_amount!=''){echo $this->Xin_model->currency_sign(change_num_format($all_sal[0]->paid_amount));}else { echo $this->Xin_model->currency_sign(0);}?></div>
							</li>
						</ul>
						<div class="col-lg-10 col-lg-offset-1">
							<div class="content-group" id="total-salary"></div>
						</div>
					</div>
					<div class="col-lg-3">
						<ul class="list-inline text-center">
							<li>
								<a href="#" class="btn border-indigo-400 text-indigo-400 btn-flat btn-rounded btn-icon btn-xs valign-text-bottom"><i class="icon-newspaper"></i></a>
							</li>
							<li class="text-left">
								<div class="text-semibold">Total Departments</div>
								<div class="text-muted"><span class="status-mark border-success position-left"></span> <?php echo $this->Xin_model->get_all_departments();?></div>
							</li>
						</ul>
						<div class="col-lg-10 col-lg-offset-1">
							<div class="content-group" id="total-jobs"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>-->
<?php } ?>

<?php if(in_array('0d',role_resource_ids())) { ?>
<div class="row row-md mb-1">
  <div class="col-md-6">
	<div class="panel panel-flat" style="position: static;min-height: 29.2em;">
		<div class="panel-heading">
			<h5 class="panel-title"> <i class="icon-users4 icon-clr" style="margin-top: -4px;"></i>&nbsp;&nbsp;<?php echo $this->lang->line('dashboard_new');?> <?php echo $this->lang->line('dashboard_employees');?><a class="heading-elements-toggle"><i class="icon-more"></i></a></h5>
		</div>
		<div class="panel-body">
			<ul class="media-list">
				 <?php
				 $list_colors=array('bg-indigo-400','label-primary','bg-purple-400','bg-green-400');
				 $i=0;
				 foreach($last_four_employees as $employee) {?>
				  	<?php
					if($employee->profile_picture!='' && $employee->profile_picture!='no file') {
						$de_file = 'uploads/profile/'.$employee->profile_picture;
					} else {
						if($employee->gender=='Male') {
						$de_file = 'uploads/profile/default_male.jpg';
						} else {
						$de_file = 'uploads/profile/default_female.jpg';
						}
					}
					$fname = $employee->first_name.' '.$employee->middle_name.' '.$employee->last_name;
					$designation = $this->Designation_model->read_designation_information($employee->designation_id);
					?>
						<li class="media">
							<div class="media-left media-middle">
								<a href="<?php echo site_url();?>employees/detail/<?php echo $employee->user_id;?>/">
									<img src="<?php echo base_url().$de_file;?>" class="img-circle" alt="">
								</a>
							</div>
							<div class="media-body">
								<div class="media-heading text-semibold"><a class="text-semibold text-black" href="<?php echo site_url();?>employees/detail/<?php echo $employee->user_id;?>/"><?php echo change_fletter_caps($fname);?></a></div>
								<span class="label <?php echo $list_colors[$i];?>	"><?php echo $designation[0]->designation_name;?></span>
							</div>
						</li>
				 <?php } ?>
			</ul>
			<br/>
			<a href="<?php site_url();?>employees">
				<button type="button" class="btn btn-block bg-teal-400 btn-labeled"><b><i class="icon-more"></i></b> <?php echo $this->lang->line('dashboard_show_more');?></button>
			</a>
		</div>
	</div>
  </div>
  <div class="col-md-6">
	  <div class="panel panel-flat" style="position: static;min-height: 29.2em;">
			<div class="panel-heading">
				<h5 class="panel-title"> <i class="fa fa-birthday-cake icon-clr" style="margin-top: -4px;"></i>&nbsp;&nbsp;<?php echo 'Birthdays';?><a class="heading-elements-toggle"></a></h5>
			</div>
			<div class="panel-body birth_height">
				 <ul class="media-list ">
					 <?php
					 	$list_colors=array('bg-indigo-400');//,'label-primary','bg-purple-400','bg-green-400'
					 	$i=0;
					  	$current_month=date('m');
						$today_d_only=date('m-d',strtotime(TODAY_DATE));
						$this_month_birthday = $this->Employees_model->get_this_month_birthday($current_month,$today_d_only);

						if($this_month_birthday){
						foreach($this_month_birthday as $r) {
						$day_of_birth=date('m-d',strtotime($r->date_of_birth));


						 $check_today_d=date('Y').'-'.$today_d_only;
						 $check_birth_d=date('Y').'-'.$day_of_birth;
						 if(strtotime($check_today_d) <= strtotime($check_birth_d)){ ?>
							<li class="media">
								<div class="media-left media-middle">
								  <?php if($r->profile_picture!='' && $r->profile_picture!='no file') {?>
								  <img class="img-circle" src="<?php echo base_url().'uploads/profile/'.$r->profile_picture;?>" alt="">
								  <?php } else {?>
								  <?php if($r->gender=='Male') { ?>
								  <?php $de_file = base_url().'uploads/profile/default_male.jpg';?>
								  <?php } else { ?>
								  <?php $de_file = base_url().'uploads/profile/default_female.jpg';?>
								  <?php } ?>
								  <img class="img-circle" src="<?php echo $de_file;?>" alt="">
								  <?php } ?>
								</div>
								<div class="media-body">
									<span class="text-semibold"><?php echo change_fletter_caps($r->first_name.' '.$r->middle_name.' '.$r->last_name);?></span>
									<?php if($today_d_only==$day_of_birth){?>
									<?php if($r->user_id==$session['user_id']){?>
									<div class="text-muted font-90 blink_me	">Happy Birthday</div>
									<!--<a class="btn bg-teal-400 pull-right  btn-rounded" style="position: relative;margin-top: -2em;" href="<?php echo base_url().'dashboard/birthday_wishes/'.base64_encode($r->user_id);?>">See Message</a>-->
									<?php } else {?>
									<div class="text-muted font-90 blink_me	">TODAY</div>
									<!--<a class="btn bg-teal-400 pull-right  btn-rounded" style="position: relative;margin-top: -2em;" href="<?php echo base_url().'dashboard/birthday_wishes/'.base64_encode($r->user_id);?>">Wish Him</a>-->
									<?php } ?>

									<?php } else { ?>
									<br>
									<span class="label <?php echo $list_colors[0];?>"><?php echo date('M d ',strtotime($r->date_of_birth));?></span>
									<?php } ?>
							   </div>
							</li>
						 <?php $i++; } } }
						 else {
						 	echo '<p style="color:#263238;text-align:center;">*There is no birthday this month!*</p>';
						 }
					 ?>
			   </ul>
			</div>
	  </div>
  </div>
</div>
<?php } ?>

<?php if(in_array('0d',role_resource_ids())) { ?>
<div class="row row-md mb-1 animated fadeInRight">
	<div class="col-lg-6 hide">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title">
					<i class="icon-stats-bars icon-clr" style="margin-top: -4px;"></i>&nbsp;&nbsp;<?php echo 'Department Wise Employees Count & Salaries';?>
				</h5>
			</div>
			<div class="chart-container">
				<canvas id="doughnut" class="chart-container"></canvas>
				<div id="doughnut-legend" class="chart-legend"></div>
			</div>
		</div>
	</div>

	<div class="col-lg-6 hide">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title">
					<i class="icon-stats-bars2 icon-clr" style="margin-top: -4px;"></i>&nbsp;&nbsp;<?php echo 'Location Wise Employees Count & Salaries';?>
				</h5>
			</div>
			<canvas id="polar-area" class="chart-container"></canvas>
			<div id="pol-legend" class="chart-legend"></div>
		</div>
	</div>

</div>

<div class="row row-md mb-1 animated fadeInRight">
	<div class="col-lg-12 col-md-12">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title">
					<i class="icon-stats-bars3 icon-clr" style="margin-top: -4px;"></i>&nbsp;&nbsp;<?php echo 'Leave chart';?>
				</h5>
			</div>

			<div class="panel-body">
				<form method="post" name="leave_chart_form" id="leave_chart_form" >
					<input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>" id="user_id">
					<div class="row">
						<div class="col-lg-12">
							<div class="col-lg-2">
								<label for="payment_method">Choose Department</label>
							  	<!-- <select name="dept_id" id="dep_id" class="select2" onchange="design_sal_graph(this.value)" data-plugin="select_hrm" data-placeholder="Choose Department..."> -->
							  	<select name="dept_id" id="dep_id" class="select2" data-plugin="select_hrm" data-placeholder="Choose Department...">
									<option value="0">All</option>
									<?php 
									$dep = $this->Xin_model->all_departments_chart();
									foreach($dep as $dpts){
									?>
									<option value="<?php echo $dpts->department_id;?>" <?php if($dpts->department_id == 1){ echo 'selected';} ?>><?php echo $dpts->department_name;?></option>
									<?php }?>
							  	</select>
							</div>

							<div class="col-lg-2">
								<label for="payment_method">Status</label>
							  	<select name="status" id="status" class="select2" data-plugin="select_hrm" data-placeholder="Choose Status...">
									<option value="0">All</option>
									<option value="1">Pending</option>
									<option value="2">Approved</option>
									<option value="3">Rejected</option>
							  	</select>
							</div>
							
							<div class="form-group col-lg-3">
								<label for="name">Start Date</label>
								<div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
									<input class="form-control" placeholder="Select Date" name="start_date" id="start_date" size="16" type="text"  value="<?php echo date('d F Y',strtotime('-7 Day',strtotime(date('d F Y'))));?>" readonly>
									<span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>
								</div>
							</div>

							<div class="form-group col-lg-3">
								<label for="name">End Date</label>
								<div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
									<input class="form-control" placeholder="Select Date" name="end_date" id="end_date" size="16" type="text"  value="<?php echo date('d F Y');?>" readonly>
									<span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>
								</div>
							</div>

							<button type="submit" class="btn bg-teal-400 save ml-20" style="margin-top: 26px;">Search</button>
						</div>
					</div>
				</form>
			</div>

			<br>
			<div id="canvas_id">
				<canvas id="pie" class="chart-container"></canvas>
			</div>
		</div>
	</div>
</div>

<div class="row row-md mb-1 animated fadeInRight">
	<div class="col-lg-12 col-md-12">
		<div class="panel panel-flat">
			
			<div class="panel-body">
				<div class="form-group col-lg-4">
			  		<label for="payment_method">Choose Leave Type</label>
				  	<select name="leave_type_id[]" id="leave_type_id" class="select2 leave_type_select" onchange="leave_list_by_type()" multiple="multiple" data-plugin="select_hrm" data-placeholder="Choose Leave Type">
						<?php 
						foreach($this->Xin_model->get_leave_type()->result() as $lvs){
						?>
						<option value="<?php echo $lvs->type_id;?>"><?php echo $lvs->type_name;?></option>
						<?php }?>
						<option value="UAA">Unauthorised Absence</option>
				  	</select>
			  	</div>

			  	<div class="form-group col-lg-4">
			  		<label for="payment_method">Choose Employee</label>
				  	<div id="emp_select"></div>
			  	</div>

			</div>

			<div class="panel-body">
			  	<div data-pattern="priority-columns">
					<table class="table" id="xin_table" >
						<thead>
						<tr>
							<th>Employee</th>
							<th>Type</th>
							<th>Leave count</th>
							<th>Leave Application count</th>
						</tr>
						</thead>
					</table>

				</div>
			</div>
		</div>
	</div>
</div>

<?php } ?>
<?php if(!in_array('0d',role_resource_ids())) { ?>
<?php
  	if($user_info[0]->profile_background!=''){
		$bg_img=$user_info[0]->profile_background;
  	}
  	else{
		$bg_img='profile_default.jpg';
  	}
	$gdate = explode(' ',$user_info[0]->last_login_date);
	$login_date = $this->Xin_model->set_date_format($gdate[0]);

	$att_date =  date('Y-m-d');
	$attendance_date = date('Y-m-d');
	// get office shift for employee
	$get_day = strtotime($att_date);
	$day = date('l', $get_day);
	$strtotime = strtotime($attendance_date);
	$new_date = date('d-M-Y', $strtotime);
	// office shift
	$u_shift = $this->Employees_model->get_shifts_bydep_loc($user_info[0]->user_id,$user_info[0]->office_location_id,$user_info[0]->department_id,$attendance_date);
	$office_shift=	$u_shift['in_time'].' - '.$u_shift['out_time'].' And Week Off : '.$u_shift['week_off'];

	if($user_info[0]->profile_picture!='' && $user_info[0]->profile_picture!='no file') {
		$de_file = base_url().'uploads/profile/'.$user_info[0]->profile_picture;
	}
	else {
		if($user_info[0]->gender=='Male') {
			$de_file = base_url().'uploads/profile/default_male.jpg';
		}
		else {
			$de_file = base_url().'uploads/profile/default_female.jpg';
	 	}
	}
?>
	<div class="profile-cover">
		<div class="profile-cover-img" style="background-image: url(<?php echo base_url().'uploads/profile/background/'.$bg_img;?>)"></div>
		<div class="media">
			<div class="media-left">
				<a href="#" class="profile-thumb">
					<img src="<?php echo $de_file;?>" class="img-circle" alt="">
				</a>
			</div>
			<div class="media-body">
				<h1><?php echo change_fletter_caps($first_name.' '.$middle_name.' '.$last_name);?> <small class="display-block"><?php echo $designation[0]->designation_name;?></small></h1>
			</div>
		</div>
	</div>

	<div class="navbar navbar-default navbar-xs content-group">
		<ul class="nav navbar-nav visible-xs-block">
			<li class="full-width text-center"><a data-toggle="collapse" data-target="#navbar-filter"><i class="icon-menu7"></i></a></li>
		</ul>
		<div class="navbar-collapse collapse" id="navbar-filter">
			<ul class="nav navbar-nav">
				<li class="active"><a href="#activity" data-toggle="tab"><i class="icon-menu7 icon-clr position-left"></i> Activity</a></li>
				<li><a href="#mytasks" data-toggle="tab"><i class="icon-task icon-clr position-left"></i> My Tasks</a></li>
				<li><a href="#myprojects" data-toggle="tab"><i class="icon-archive icon-clr position-left"></i> My Projects</a></li>
			</ul>
			<div class="navbar-right">
				<ul class="nav navbar-nav">
					<li><a href="#"><i class="icon-sort-time-desc icon-clr position-left"></i>Shift Timing : <?php echo $office_shift;?></a></li>
					<li><a href="#"><i class="icon-history icon-clr position-left"></i><?php echo $this->lang->line('dashboard_last_login');?>  <span class="text-semibold">
					<?php echo $login_date.' '.date('h:i A', strtotime($user_info[0]->last_login_date));?></span>, <?php echo $this->lang->line('xin_e_details_from');?> <?php echo $user_info[0]->last_login_ip;?></a></li>
				</ul>
			</div>
		</div>
	</div>

	<div class="content-fluid">
		<div class="row">
			<div class="col-lg-9">
				<div class="tabbable">
					<div class="tab-content">
						<div class="tab-pane fade in active" id="activity">
							<div class="panel panel-flat">
								<div class="panel-heading">
									<h5 class="panel-title"><i class="icon-calendar2 icon-clr position-left"></i> <?php echo 'Attendance for '; echo date('F, Y', strtotime(date('M-Y'). "-1 Month")).' To '.date('F, Y');?> </h5>
								</div>
								<div class="panel-body">
									<div class="chart-container">
										<div class="chart has-fixed-height" id="stacked_lines"></div>
									</div>
								</div>
							</div>
							<!--<div class="panel panel-flat">
									<div class="panel-heading">
										<h5 class="panel-title"><i class="icon-calendar2 icon-clr position-left"></i>
										<?php echo 'Attendance for '; echo date('F, Y', strtotime(date('M-Y'). "-1 Month")).' To '.date('F, Y');?> </h5>
										<div class="heading-elements">
											<ul class="icons-list icon-clr">
												<li><a data-action="collapse"></a></li>
											</ul>
										</div>
									</div>
									<div class="panel-body">
										<div class="chart-container">
											<div class="chart has-fixed-height" id="stacked_lines1"></div>
										</div>
									</div>
								</div>-->
						</div>

						<div class="tab-pane fade" id="mytasks">
							<!-- Timeline -->
							<div class="timeline timeline-left content-group">
								<div class="timeline-container">
									<!-- Invoices -->
									<div class="timeline-row">
										<div class="timeline-icon">
											<div class="bg-teal-400">
												<i class="icon-task"></i>
											</div>
										</div>

										<div class="row">
											<?php
											 $task = $this->Timesheet_model->get_tasks();
											 $dId = array(); $i=1;
											 if($task->result()){
											 foreach($task->result() as $et):
											 $asd = array($et->assigned_to);
											 $aim = explode(',',$et->assigned_to);
											 foreach($aim as $dIds) {
												 if($session['user_id'] === $dIds) {
													$dId[] = $session['user_id'];
													// task end date
													$tdate = $this->Xin_model->set_date_format($et->end_date);
													// task progress
													if($et->task_progress <= 20) {
														$progress_class = 'progress-bar-danger';
														$status1='border-left-danger';
														$t_status="danger";
													} else if($et->task_progress > 20 && $et->task_progress <= 50){
														$progress_class = 'progress-bar-warning';
														$t_status="warning";
														$status1='border-left-warning';
													} else if($et->task_progress > 50 && $et->task_progress <= 75){
														$progress_class = 'progress-bar-info';
														$t_status="info";
														$status1='border-left-info';
													} else {
														$progress_class = 'progress-bar-success';
														$t_status="success";
														$status1='border-left-success';
													}

													// project progress
													if($et->task_status == 0) {
														$status = '<span class="label bg-'.$t_status.'-400">Not Started</span>';
													} else if($et->task_status ==1){
														$status = '<span class="label bg-'.$t_status.'-400">In Progress</span>';
													} else if($et->task_status ==2){
														$status = '<span class="label bg-'.$t_status.'-400">Completed</span>';
													} else {
														$status = '<span class="label bg-'.$t_status.'-400">Deferred</span>';
													}
											 ?>
											 <div class="col-lg-6">
												<div class="panel border-left-lg <?php echo $status1;?> invoice-grid timeline-content">
													<div class="panel-body">
														<div class="row">
															<div class="col-sm-6">
																<h6 class="text-semibold no-margin-top"><?php echo change_fletter_caps($et->task_name);?></h6>
																<div class="progress content-group-sm">
																	<div class="progress-bar <?php echo $progress_class;?> progress-bar-striped" style="width: <?php echo $et->task_progress;?>%">
																		<span><?php echo $et->task_progress;?>%</span>
																	</div>
																</div>
															</div>
															<div class="col-sm-6">
																<ul class="list list-unstyled text-right">
																	<li>Status: &nbsp;<?php echo $status;?></li>
																</ul>
															</div>
														</div>
													</div>
													<div class="panel-footer panel-footer-condensed">
														<div class="heading-elements">
															<span class="heading-text">
																<span class="status-mark border-success position-left"></span> Task Due Date: <span class="text-semibold"><?php echo $tdate;?></span>
															</span>
															<ul class="list-inline list-inline-condensed heading-text pull-right">
																<li><a href="<?php echo base_url().'timesheet/task_details/id/'.$et->task_id;?>" class="text-default" ><i class="icon-eye8"></i></a></li>
															</ul>
														</div>
													</div>
												</div>
											 </div>

											 <?php }
											 }
												 $i++; endforeach;
											 ?>
											<?php }
											else { ?>
												<div class="col-lg-6" style="margin-top: 1.8em;margin-bottom: 3em;color: #263238;font-weight: bold;"> There is no tasks available now.</div>
											<?php } ?>
										</div>
									</div>
												<!-- /invoices -->

								</div>
							</div>
						</div>

						<div class="tab-pane fade" id="myprojects">
							<!-- Timeline -->
							<div class="timeline timeline-left content-group">
								<div class="timeline-container">
									<!-- Invoices -->
									<div class="timeline-row">
										<div class="timeline-icon">
											<div class="bg-teal-400">
												<i class="icon-archive"></i>
											</div>
										</div>

										<div class="row">

										<?php
											$project = $this->Project_model->get_projects();
											$dId = array(); $i=1;
											if($project->result()){
												foreach($project->result() as $pj):
													$asd = array($pj->assigned_to);
													$aim = explode(',',$pj->assigned_to);
													foreach($aim as $dIds) {
														if($session['user_id'] === $dIds) {
															$dId[] = $session['user_id'];
															// project date
															$pdate = $this->Xin_model->set_date_format($pj->end_date);
															// project progress
															if($pj->project_progress <= 20) {
																$progress_class = 'progress-bar-danger';
																$status1='border-left-danger';
																$t_status="danger";
															} else if($pj->project_progress > 20 && $pj->project_progress <= 50){
																$progress_class = 'progress-bar-warning';
																$status1='border-left-warning';
																$t_status="warning";
															} else if($pj->project_progress > 50 && $pj->project_progress <= 75){
																 $progress_class = 'progress-bar-info';
																$status1='border-left-info';
																$t_status="info";
															} else {
																$progress_class = 'progress-bar-success';
																$status1='border-left-success';
																$t_status="success";
															}

														// project progress
														if($pj->status == 0) {
															$status = '<span class="label bg-'.$t_status.'-400">Not Started</span>';
														} else if($pj->status ==1){
															$status = '<span class="label bg-'.$t_status.'-400">In Progress</span>';
														} else if($pj->status ==2){
															$status = '<span class="label bg-'.$t_status.'-400">Completed</span>';
														} else {
															$status = '<span class="label bg-'.$t_status.'-400">Deferred</span>';
														}
													?>
												<div class="col-lg-6">
													<div class="panel border-left-lg <?php echo $status1;?> invoice-grid timeline-content">
														<div class="panel-body">
															<div class="row">
																<div class="col-sm-6">
																	<h6 class="text-semibold no-margin-top"><?php echo change_fletter_caps($pj->title);?></h6>

																	<div class="progress content-group-sm">
																		<div class="progress-bar <?php echo $progress_class;?> progress-bar-striped" style="width: <?php echo $pj->project_progress;?>%">
																			<span><?php echo $pj->project_progress;?>%</span>
																		</div>
																	</div>
																</div>

																<div class="col-sm-6">
																		<ul class="list list-unstyled text-right">
																			<li>Status: &nbsp;<?php echo $status;?></li>
																		</ul>
																</div>
															</div>
														</div>
														<div class="panel-footer panel-footer-condensed">
															<div class="heading-elements">
																<span class="heading-text">
																	<span class="status-mark border-success position-left"></span> Project Date: <span class="text-semibold"><?php echo $pdate;?></span>
																</span>
																<ul class="list-inline list-inline-condensed heading-text pull-right">
																	<li><a href="<?php echo base_url().'project/detail/'.$pj->project_id;?>" class="text-default"><i class="icon-eye8"></i></a></li>
																</ul>
															</div>
														</div>
													</div>
												</div>
													<?php }
													}
													$i++;
												 endforeach;
											}
											else {
											?>
												<div class="col-lg-6" style="margin-top: 1.8em;margin-bottom: 3em;color: #263238;font-weight: bold;"> There is no projects available now.</div>
											<?php } ?>
										</div>
									</div>
												<!-- /invoices -->
								</div>
							</div>
						</div>
					</div>
				</div>
					<!-- Calendar -->
				<div class="add-form" style="display:none;">
					<div class="panel panel-flat">
						<div class="panel-heading">
							<h6 class="panel-title"><i class="icon-calendar icon-clr position-left"></i> Add Schedule</h6>
							<div class="heading-elements">
								<div class="add-record-btn">
								   <li class="add-new-form" style="cursor:pointer;list-style: none;"><i class="icon-subtract icon-clr"></i></li>
								</div>
							</div>
						</div>
						<div class="panel-body">
							<form method="post" name="add_shedule" id="schedule-form" action="<?php echo site_url("dashboard/add_schedule") ?>">
								<input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>" id="user_id">
								<div class="row">
								<div class="col-lg-12">
								<div class="col-lg-3 no-padding-left">
									<input class="form-control" placeholder="Schedule Title" name="schedule_title" type="text">
								</div>
								<div class="col-lg-3">
									<input class="form-control" placeholder="Schedule Start Date & Time" name="schedule_start" type="text" id="anytime-both" value="<?php echo date('F dS Y H:i');?>">
								</div>
								<div class="col-lg-3">
									<input class="form-control" placeholder="Schedule End Date & Time" name="schedule_end" type="text" id="anytime-both1" value="<?php //echo date('F dS Y H:i');?>">
								</div>
								<div class="col-lg-3"> <input type="text" class="form-control colorpicker-basic" value="#20BF7E" name="schedule_color" id="schedule_color" >
									<button type="submit" class="btn bg-teal-400 save ml-20">Submit</button>
								</div>
							</div>
						</div>
							</form>
						</div>
					</div>
				</div>

				<div class="panel panel-flat">
					<div class="panel-heading">
						<h6 class="panel-title"><i class="icon-calendar2 icon-clr position-left"></i> My Schedule</h6>
						<div class="heading-elements">
							<ul class="icons-list icon-clr">
								<li class="add-new-form" style="cursor:pointer;"><i class="icon-plus-circle2"></i></li>
							</ul>
						</div>
					</div>
					<div class="panel-body">
						<div class="schedule"></div>
					</div>
				</div>
				<!-- /calendar -->
			</div>

		<div class="col-lg-3">
			<!-- Multiple donut charts -->
			<div class="panel panel-flat">
				<div class="panel-heading">
					<h5 class="panel-title">Profile Completion</h5>
					<div class="heading-elements">
						<span class="heading-text icon-clr"><i class="icon-pie-chart7"></i></span>
					</div>
				</div>
				<?php
				$get_profile_complete_progress=get_profile_complete_progress($session['user_id']);
				$task_progress=$get_profile_complete_progress['count_of_progress'];
				?>
				<input type="hidden" id="task_progress_value" value="<?php echo $task_progress;?>"/>
				<div class="panel-body">
					<div class="chart-container">
						<div class="chart" id="multiple_donuts" style="height: 250px;"></div>
					</div>
				</div>
			</div>
			<!-- /multiple donut charts -->
					
			<!-- Navigation -->
			<div class="panel panel-flat">
				<div class="panel-heading">
					<h5 class="panel-title"><?php echo $this->lang->line('dashboard_personal_details');?></h5>
					<div class="heading-elements">
						<span class="heading-text icon-clr"><i class="icon-profile"></i></span>
					</div>
				</div>
				<div class="list-group no-border no-padding-top">
					<a href="#" class="list-group-item"><i class="icon-user icon-clr"></i> <?php echo $this->lang->line('dashboard_fullname');?> <span class="text-bold pull-right"><?php echo change_fletter_caps($first_name.' '.$middle_name.' '.$last_name);?></span></a>
					<a href="#" class="list-group-item"><i class="icon-cash3 icon-clr"></i> <?php echo $this->lang->line('dashboard_employee_id');?> <span class="text-bold pull-right"><?php echo $user_info[0]->employee_id;?></span></a>
					<a href="#" class="list-group-item"><i class="icon-mail5 icon-clr"></i> <?php echo $this->lang->line('dashboard_email');?> <span class="text-bold pull-right"><?php echo $email;?></span></a>
					<a href="#" class="list-group-item"><i class="icon-calendar3 icon-clr"></i> <?php echo $this->lang->line('dashboard_dob');?> <span class="text-bold pull-right"><?php echo $this->Xin_model->set_date_format($date_of_birth);?></span></a>
					<a href="#" class="list-group-item"><i class="icon-phone icon-clr"></i> <?php echo $this->lang->line('dashboard_contact');?> <span class="text-bold pull-right"><?php echo $contact_no;?></span></a>
					<div class="list-group-divider"></div>
					<a href="<?php echo base_url().'profile';?>" class="list-group-item"><i class="icon-cog3 icon-clr"></i> Account settings</a>
				</div>
			</div>
			<!-- /navigation -->

			<!-- Connections -->
			<div class="panel panel-flat" style="position: static;min-height: 29.2em;">
				<div class="panel-heading">
					<h6 class="panel-title">Birthdays</h6>
					<div class="heading-elements">
						<span class="heading-text icon-clr"><i class="fa fa-birthday-cake"></i></span>
					</div>
				</div>
				<div class="panel-body birth_height">
					<ul class="media-list media-list-linked pb-5">
						<?php
						 $list_colors=array('bg-teal-400');
						 $i=0;
						 $current_month=date('m');
						 $today_d_only=date('m-d',strtotime(TODAY_DATE));
						 $this_month_birthday = $this->Employees_model->get_this_month_birthday($current_month,$today_d_only);

						 if($this_month_birthday){
						 foreach($this_month_birthday as $r) {
						 $day_of_birth=date('m-d',strtotime($r->date_of_birth));

						 $check_today_d=date('Y').'-'.$today_d_only;
						 $check_birth_d=date('Y').'-'.$day_of_birth;
						 if(strtotime($check_today_d) <= strtotime($check_birth_d)){
							if($r->profile_picture!='' && $r->profile_picture!='no file') {
								$de_file = base_url().'uploads/profile/'.$r->profile_picture;
							}
							else{
								if($r->gender=='Male') {
									$de_file = base_url().'uploads/profile/default_male.jpg';
								}
								else {
									 $de_file = base_url().'uploads/profile/default_female.jpg';
								}
							} ?>
							
						 <li class="media">
								<?php
								if($today_d_only==$day_of_birth){
									$hrf="#";
									$tl_t='';
								}
								else{
									$hrf="#";
									$tl_t='';
								} ?>
								<a href="<?php echo $hrf;?>" <?php echo $tl_t;?> class="media-link">
											<div class="media-left">
												<img src="<?php echo $de_file;?>" class="img-circle" alt="">
											</div>
											<div class="media-body">
												<span class="media-heading text-semibold"><?php echo change_fletter_caps($r->first_name.' '.$r->middle_name.' '.$r->last_name);?></span>
												<?php if($today_d_only==$day_of_birth){
															if($r->user_id==$session['user_id']){ ?>
																<span class="media-annotation blink_me">Happy Birthday</span>
																<!--<a class="btn bg-teal-400 pull-right  btn-rounded" style="position: relative;margin-top: -2em;" href="<?php echo base_url().'dashboard/birthday_wishes/'.base64_encode($r->user_id);?>">See Message</a>-->
															<?php
															}
															else
																{?>
																<span class="media-annotation blink_me">TODAY</span>
																<!--<a class="btn bg-teal-400 pull-right  btn-rounded" style="position: relative;margin-top: -2em;" href="<?php echo base_url().'dashboard/birthday_wishes/'.base64_encode($r->user_id);?>">Wish Him</a>-->
															<?php }
													  }
													  else{ ?>
															<span class="media-annotation label <?php echo $list_colors[0];?>"><?php echo date('M d ',strtotime($r->date_of_birth));?></span>
															<!--<span class="label <?php echo $list_colors[0];?>"><?php echo date('M d ',strtotime($r->date_of_birth));?></span>-->
												<?php } ?>
											</div>
											<!--
											<div class="media-right media-middle">
													<span class="text-danger blink_me"><i class="fa fa-birthday-cake"></i></span>
											</div>-->
								</a>
						 </li>
						 <?php $i++;} } } else { echo '<p style="color:#263238;text-align:center;">*There is no birthday this month!*</p>';}?>
					</ul>
				</div>
			</div>
			<!-- /connections -->

			<!-- Share your thoughts -->
			<div class="panel panel-flat">
				<div class="panel-heading">
					<h6 class="panel-title">Awards<a class="heading-elements-toggle"><i class="icon-more"></i></a></h6>
					<div class="heading-elements">
						<span class="icon-clr heading-text"><i class="fa fa-trophy"></i></span>
					</div>
				</div>

				<ul class="media-list media-list-linked pb-5">
						 <?php
						 	$list_colors=array('bg-indigo-400','label-primary','bg-purple-400','bg-green-400');
							$i=1;
							foreach($this->Xin_model->get_employee_awards() as $emp_award){
								$aw_name = $this->Awards_model->read_award_type_information($emp_award->award_type_id);
								$awdate = $this->Xin_model->set_date_format($emp_award->created_at);
							 ?>
								<li class="media">
									<a href="#" class="media-link">
										<div class="media-left"><i class="fa fa-trophy img-circle icon-clr" ></i></div>
										<div class="media-body">
											<span class="media-heading text-semibold"><?php echo ($aw_name[0]->type_name);?></span>
											<span class="media-annotation"><?php echo $awdate;?></span>
										</div>
										<!--<div class="media-right media-middle">
											<span class="status-mark bg-success"></span>
										</div>-->
									</a>
								</li>
							<?php } ?>
				</ul>
			</div>
			<!-- /share your thoughts -->
		</div>
	</div>
	<!-- /user profile -->


</div>
<?php } ?>
<style>
	.blink_me {
	  animation: blinker 1s linear infinite;
	  color:red !important;
	}
	@keyframes blinker {
	  50% { opacity: 0; }
	}

	.chart-legend li span{
		display: inline-block;
		width: 12px;
		height: 12px;
		margin-right: 5px;
	}
	.chart-legend ul{list-style: none;}
	.left_head{color:#E4700E;}
	.left_clr{font-size: 1.1em;color: red;}
	.right_clr{font-size: 1.1em;color: blue;}
</style>
<?php if(@$_SESSION['check_toastr']==''){ ?>
<script>
	$(function() {
		toastr.options = {
		  "closeButton": <?php echo $system[0]->notification_close_btn;?>,
		  "debug": true,
		  "newestOnTop": true,
		  "progressBar": <?php echo $system[0]->notification_bar;?>,
		  "positionClass": "<?php echo $system[0]->notification_position;?>",
		  "preventDuplicates": true,
		  "showDuration": "300",
		  "hideDuration": "1000",
		  "timeOut": "3000",
		  "extendedTimeOut": "2000",
		  "showEasing": "swing",
		  "hideEasing": "linear",
		  "showMethod": "fadeIn",
		  "hideMethod": "fadeOut"
		}
		toastr.info('Welcome. We are glad to see you again');
	});
</script>
<?php $_SESSION['check_toastr']=1; } ?>
