<?php $session = $this->session->userdata('username');?>
<?php if(in_array('31v',role_resource_ids()) || rp_manager_access() || hod_manager_access()) {?>
	<?php if(in_array('31a',role_resource_ids()) || rp_manager_access() || hod_manager_access()) {?>
	<div class="add-form" style="display:none;">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title">
					<strong>Add New</strong> <?php echo $breadcrumbs;?></li>
				</h5>
				<div class="heading-elements">
					<div class="add-record-btn">
					<button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_hide');?></button>
					</div>
				</div>
			</div>	 
			<div class="row m-b-1">
			<div class="col-md-12">
				<form action="<?php echo site_url("timesheet/add_manual_attendance") ?>" method="post" name="add_manual_attendance" id="xin-form">
					<input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
					<div class="col-lg-12">
						<div class="col-md-12">
							<div class="form-group">
								<?php				
								if(in_array('31a',role_resource_ids())) {?>
								<label>
								<input type="radio" name="schedule_to" value="department" class="styled" onclick="check_options('department');" checked="checked">
									Department Wise Entry
								</label>
								<?php } ?>
								<label class="ml-10">
									<input type="radio" name="schedule_to" value="employee"  onclick="check_options('employee');"  class="styled">
									Employee Wise Entry
								</label>
							</div>
						</div>
						
						<div class="col-md-6">
							<div id="depart_id_div">
								<div class="form-group">
									<label for="name">Choose the Location</label>
									<select class="form-control employee_shows"  name="location_id" id="location_id_val" data-plugin="select_hrm" data-placeholder="Choose the Location...">
										<option value="">&nbsp;</option>
										<?php foreach($this->Xin_model->all_locations() as $locs) {?>
										<option value="<?php echo $locs->location_id;?>"> <?php echo $locs->location_name;?></option>
										<?php } ?>
									</select>
								</div>
								<div class="form-group">
									<label for="name">Choose the Department</label>
									<select id="department_id_val" class="form-control employee_shows" name="department_id" data-plugin="select_hrm" data-placeholder="Choose the Department...">
										<option value="0">All Departments</option>
										<?php foreach($this->Xin_model->all_departments_chart() as $dept) {?>
										<option value="<?php echo $dept->department_id;?>"><?php echo $dept->department_name;?></option>
										<?php } ?>
									</select>
								</div>
							</div>								
							<div class="form-group" id="employee_id_div">
								<label for="name">Choose the Employees</label>
								<select name="employee_id[]" id="employee_id" multiple class="form-control" data-plugin="select_hrm" data-placeholder="Choose the Employees...">
									<option value="">&nbsp;</option>
									<?php foreach($employeesArray as $employee) {?>
									<option value="<?php echo $employee['user_id'];?>"> <?php echo change_fletter_caps($employee['full_name']);?></option>
									<?php } ?>
								</select>
							</div>
							<div class="form-group">
								<label for="name_of_week">Attendance Status</label>
								<select class="form-control" name="attendance_status" data-plugin="select_hrm" data-placeholder="Choose the attendance status...">
									<option value="">&nbsp;</option>
									<option value="Present">Present</option>
									<option value="Absent">Absent</option>
								</select>
							</div>

							<div class="form-group">
								<label for="name_of_week">OB Type</label>
								<select class="form-control" name="ob_type" data-plugin="select_hrm" data-placeholder="Choose the OB Type...">
									<option value="">&nbsp;</option>
									<?php 
									for ($o=0; $o < count($obList); $o++) { 
									?>
										<option value="<?=$obList[$o]['type_id']?>"><?=$obList[$o]['type_name']?></option>
									<?php }?>
								</select>
							</div>

							<div class="form-group">
								<label for="name_of_week">Reason</label>
								<textarea name="reason" class="form-control"></textarea>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label for="name">Start Date</label>
								<div class="input-group date form_date_five_day" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
									<input class="form-control" placeholder="Select Date" name="start_date" id="start_date" size="16" type="text"  value="<?php echo date('d F Y');?>" readonly>
									<span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>
								</div>
							</div>
							<div class="form-group">
									<label for="name">End Date</label>
									<div class="input-group date form_date_five_day" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
										<input class="form-control" placeholder="Select Date" name="end_date" id="end_date" size="16" type="text"  value="<?php echo date('d F Y');?>" readonly>
										<span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>
									</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label for="name">Start Time</label>
								<input class="form-control timepicker clear-1" placeholder="Start Time" readonly name="start_time" type="text" value="">
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label for="name">End Time</label>
								<input class="form-control timepicker clear-1" placeholder="End Time" readonly name="end_time" type="text" value="">
							</div>
						</div>
						
						<div class="clearfix"></div>
						<div id="show_mail"></div>
						<div id="show_emp_table" >
							<table class="table datatablescroll" >
								<thead>
									<tr>
										<th>
										<label><input name="select_all" value="1" id="example-select-all" type="checkbox"><span></span></label></th>
										<th>Employee Id</th>
										<th>Name</th>
										<th>Location / Department</th>
										<th>Default Shift Time / Week Off</th>
									</tr>
								</thead>
							</table>
						</div>

						<div class="footer-elements">
							<button type="submit" class="btn bg-teal-400 save"><?php echo $this->lang->line('xin_save');?></button>
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
				<strong>Filter Manual Attendance</strong>
			</h5>
		</div>
		<div class="panel-body">
			<form action="#" method="post" name="shift_list_search" id="shift_list_search">
				<div class="row">
					<?php if(!rp_manager_access() && !hod_manager_access()) {?>
						<div class="col-md-2">
							<div class="form-group">
								<select class="location_value" class="form-control" data-plugin="select_hrm" data-placeholder="Choose the Status...">
									<option value="0">ALL LOCATIONS</option>
									<?php foreach($this->Xin_model->all_locations() as $locs) {?>
									<option value="<?php echo $locs->location_id;?>"> <?php echo $locs->location_name;?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<select class="department_value" class="form-control" data-plugin="select_hrm" data-placeholder="Choose the Status...">
									<option value="0">ALL DEPARTMENTS</option>
									<?php foreach($this->Xin_model->all_departments_chart() as $dept) {?>
									<option value="<?php echo $dept->department_id;?>" <?php //if($session['user_id']==$employee->user_id):selected?>  <?php //endif;?>> <?php echo $dept->department_name;?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					<?php } ?>
					<div class="col-md-2">
						<div class="form-group">
							<select name="employee_id" id="user_id" class="form-control" data-plugin="select_hrm" data-placeholder="Choose an Employee...">
								<option value="all">All Employees</option>
								<?php foreach($employeesArray as $employee) {?>
								<option value="<?php echo $employee['user_id'];?>"> <?php echo change_fletter_caps($employee['full_name']);?></option>
								<?php } ?>
							</select>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<select class="form-control" name="ob_type_filter" id="ob_type_filter" data-plugin="select_hrm" data-placeholder="Choose the OB Type...">
								<option value="0">All OB Type</option>
								<?php 
								for ($o=0; $o < count($obList); $o++) { 
								?>
									<option value="<?=$obList[$o]['type_id']?>"><?=$obList[$o]['type_name']?></option>
								<?php }?>
							</select>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<div class="input-group date form_month_year" data-date="" data-date-format="yyyy MM"  data-link-format="yyyy MM">
								<input class="form-control" placeholder="Select Month" name="month_year" size="16" id="month_year"  type="text" value="<?php echo date('Y F');?>" readonly>
								<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
							</div>
						</div>             		
					</div>
					<div class="col-md-3">
						<div class="form-group"> &nbsp;
							<button type="submit"  class="btn bg-teal-400 save mr-20">Filter</button>
							<button type="button" onclick="clear_filter();" class="btn bg-teal-400">Clear</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="panel panel-flat">
		<div class="panel-heading">
			<h5 class="panel-title">
				<strong>Manual Attendance List</strong>
			</h5>
			<div class="heading-elements">
				<div class="form-group"> &nbsp;
					<?php if((in_array('31ad',role_resource_ids()) || rp_manager_access() || hod_manager_access()) && visa_wise_role_ids() == '') {?>
						<button class="btn bg-teal-400 status_schedule" rel="approve">Approve</button>
						<button class="btn bg-teal-400 status_schedule" rel="decline">Decline</button>
					<?php } ?>
					<?php if((in_array('31d',role_resource_ids()) || hod_manager_access()) && visa_wise_role_ids() == '') {?>
						<button class="btn bg-teal-400 delete_schedule">Delete</button>
					<?php } ?>
					<?php if((in_array('31e',role_resource_ids()) || rp_manager_access() || hod_manager_access()) && visa_wise_role_ids() == '') {?>
						<button class="btn bg-teal-400 change_schedule">Change Manual Attendance</button>
					<?php } ?>
					<?php if((in_array('31a',role_resource_ids()) || rp_manager_access() || hod_manager_access()) && visa_wise_role_ids() == '') {?>
						<button class="btn bg-teal-400 add-new-form">Add New</button>
					<?php } ?>
				</div>
			</div>
		</div>

		<div data-pattern="priority-columns">
			<table class="table" id="xin_shift_table" >
				<thead>
					<tr>
					<th><label><input name="select_all" value="1" id="example-select-all-1" type="checkbox"><span></span></label></th>
					<th>Employee Name</th>
					<th>Employee Id</th>
					<th>OB Type</th>
					<th>Location & Department</th>
					<th>Start/End Date</th>
					<th>Attendace Status</th>
					<th>Approved Status</th>
					<!-- <th>HR Administrator Status</th>
					<th>Reporting Manager Status</th> -->
					</tr>
				</thead>
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
<link href="<?php echo base_url();?>assets/css/icons/fontawesome/styles.min.css" rel="stylesheet" type="text/css">
<style>
.select2-search__field{width:100% !important;}
input[type="checkbox"]{ position: absolute; opacity: 0; z-index: -1; }
input[type="checkbox"]+span:before { font: 14pt  FontAwesome; content: '\00f096'; display: inline-block; width: 12pt; padding: 2px 0 0 3px; margin-right: 0.5em; }
input[type="checkbox"]:checked+span:before { content: '\00f046'; }
input[type="checkbox"]:disabled + span::before {color: grey;opacity: 0.3;}
</style>
<script>
$(document).ready(function(){
	$('.add-new-form').on('click',function(){
		<?php if((rp_manager_access() || hod_manager_access()) && !in_array('31a',role_resource_ids())) {?>			
			$('.styled').closest("label.ml-10").removeClass();
			$('.styled').closest("span").addClass('checked');
			$("input[name=schedule_to][value=employee]").prop('checked', true);
			check_options('employee');
		<?php }else { ?>
			check_options('department');
		<?php } ?>
	});
});
</script>
