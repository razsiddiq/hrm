<?php
/* Leave Application view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php if(in_array('32v',role_resource_ids()) || reporting_manager_access() || get_ceo_only()  || hod_manager_access()) {?>


	<?php if($session['role_name']==AD_ROLE  || (in_array($user_role_name,$HR_L_ROLE)) || (in_array('32m',role_resource_ids())) || visa_wise_role_ids() != ''){?>
		<div class="panel panel-flat">
			<div class="panel-body" style="padding-bottom: 0;">
				<div class="row">
					<form id="leave_form">
						<div class="col-md-12">

							<div class="col-md-12 no-padding-left no-margin">
								<div class="form-group">
									<h5 class="panel-title mt-5">

										<strong>Filter By :</strong> </strong>

									</h5>
								</div>
							</div>

							<?php if(visa_wise_role_ids() == ''){?>
					        <div class="col-md-3">
								<div class="form-group">
									<label class="text-small text-grey mt-5">Visa</label>
									<select name="visa_value" id="visa_value" class="visa_value" class="form-control" data-plugin="select_hrm">
					              		<option value="0">All Visas</option>
						              	<?php foreach(visa_lists() as $vis) {?>
						              	<option value="<?php echo $vis->type_id;?>"><?php echo $vis->type_name;?></option>
						              	<?php } ?>
						            </select>								
								</div>
							</div>
							<?php }?>

							<div class="col-md-3">
								<div class="form-group">
									<label class="text-small text-grey mt-5">Location</label>
									<select name="location_value" id="location_value" class="location_value" class="form-control" data-plugin="select_hrm">
										<option value="0">All Locations</option>
										<?php foreach($this->Xin_model->all_locations() as $locs) {?>
											<option <?php if(get_session_field('location')==$locs->location_id){?>selected <?php } ?> value="<?php echo $locs->location_id;?>"><?php echo $locs->location_name;?></option>
										<?php } ?>
									</select>									
								</div>
							</div>


							<div class="col-md-3">
								<div class="form-group">
									<label class="text-small text-grey mt-5">Department</label>
									<select name="department_value" id="department_value" class="department_value" class="form-control" data-plugin="select_hrm">
										<option value="0">All Departments</option>
										<?php foreach($this->Xin_model->all_departments_chart() as $dept) {?>
											<option value="<?php echo $dept->department_id;?>"><?php echo $dept->department_name;?></option>
										<?php } ?>
									</select>									
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<label class="text-small text-grey mt-5">Leave Type</label>
									<select name="leave_type_value" id="leave_type_value" class="leave_type_value" class="form-control" data-plugin="select_hrm">
										<option value="0">All Leave Types</option>
										<?php foreach($this->Xin_model->get_leave_type()->result() as $type) {?>
											<option value="<?php echo $type->type_id;?>"><?php echo $type->type_name;?></option>
										<?php } ?>
									</select>									
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<label class="text-small text-grey mt-5">Status</label>
									<select name="status_value" id="status_value" class="status_value" class="form-control" data-plugin="select_hrm">
										<option value="0" >All Status</option>
										<option value="1" selected="">Pending</option>
										<option value="2">Approve</option>
										<option value="3">Reject</option>
									</select>
									
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<label class="text-small text-grey mt-5">Date From</label>
									<div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
										<input class="form-control" placeholder="Select Date From" name="date_from" id="date_from" size="16" type="text"  value="" readonly>
										<span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>
									</div>									
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="text-small text-grey mt-5">Date To</label>
									<div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
										<input class="form-control" placeholder="Select Date To" name="date_to" id="date_to" size="16" type="text"  value="" readonly>
										<span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>
									</div>									
								</div>
							</div>


							<div class="col-md-12 no-padding-left">
								<div class="form-group"> &nbsp;
									<button type="button" onclick="xin_forms();" class="btn bg-teal-400">Search</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>


		</div>	<?php }?>
	<div id="show_msg"></div>
	<div class="panel panel-flat">
		<div class="panel-heading">
			<h5 class="panel-title">

				<strong><?php echo $this->lang->line('xin_list_all');?></strong> Leave

			</h5>
			<div class="heading-elements">
				<?php if(in_array('32a',role_resource_ids())) {?>
					<div class="add-record-btn">


						<button class="btn btn-sm bg-teal-400 check_leave_availability" data-toggle="modal" data-target=".edit-modal-data" data-btn_type="availability" data-backdrop="static" data-keyboard="false" >Check Leave Availability</button>

						<button class="btn btn-sm bg-teal-400 check_leave_availability" data-toggle="modal" data-target=".edit-modal-data" data-btn_type="add" data-backdrop="static" data-keyboard="false"><?php echo $this->lang->line('xin_add_new');?></button>


					</div>
				<?php } ?>
			</div>
		</div>


		<div data-pattern="priority-columns">
			<?php
			$ceo_data=get_ceo_only();


			?>
			<table class="table" id="xin_table" >
				<thead>
				<tr>
					<th></th>
					<th></th>
					<th>Employee</th>
					<th>Department</th>
					<th>Location</th>
					<th>Leave Type</th>
					<th>Request Duration</th>
					<th>Days</th>
					<th>Applied On</th>
					<th>Status</th>
					<th>Action</th>
				</tr>
				</thead>
			</table>

		</div>
	</div>

	<div class="add-form1" style="display:none;">


		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title">

					<strong>Add Leave</strong> Conversion

				</h5>

				<div class="heading-elements">
					<div class="add-record-btn">
						<button class="btn btn-sm bg-teal-400 add-new-form1"><?php echo $this->lang->line('xin_hide');?></button>
					</div>
				</div>

			</div>



			<div class="row m-b-1">
				<div class="col-md-12">
					<form action="#" method="post" id="xin-form-conv" enctype="multipart/form-data">
						<input type="hidden" id="user_id" name="_user" value="<?php echo $session['user_id'];?>">
						<div class="col-lg-12">
							<div class="col-md-6">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="employees" class="control-label">	Leave for Employee</label>
											<select class="form-control" onchange="leave_conversion_availability();" name="employee_id_con" id="employee_id_con" data-plugin="select_hrm" data-placeholder="Employee">
												<option value=""></option>
												<?php foreach($all_employees as $employee) {?>
													<option value="<?php echo $employee->user_id?>"> <?php echo change_fletter_caps($employee->first_name.' '.$employee->middle_name.' '.$employee->last_name);?></option>
												<?php } ?>
											</select>
										</div>
										<div id="sh_message_conversion">

										</div>
									</div>
								</div>



							</div>



							<div class="col-md-6" >

								<div class="form-group">
									<label for="summary">Comments</label>
									<textarea class="form-control" placeholder="Comments" name="comments" cols="30" rows="3" id="comments"></textarea>
								</div>

							</div>


							<div class="clearfix"></div>

							<div class="footer-elements">
								<button type="button" class="btn bg-teal-400 show_leave_conv">Convert</button>
							</div>
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>

	<?php if(in_array('32a',role_resource_ids()) && $session['role_name'] != 'Drivers Admin') {?>
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title">

					<strong>List All</strong> Leave Conversions
				</h5>

				<div class="heading-elements">
					<?php if(in_array('32a',role_resource_ids()) || reporting_manager_access()) {?>
						<div class="add-record-btn">
							<button class="btn btn-sm bg-teal-400 add-new-form1">Add New</button>
						</div><?php } ?>
				</div>

			</div>
			<div data-pattern="priority-columns">

				<table class="table" id="xin_table_conversion">

					<thead>
					<tr>


						<th><?php echo $this->lang->line('xin_employees_full_name');?></th>
						<th>Conversion Days Count</th>
						<th>Comments</th>
						<th>Added Date</th>
						<th>Added By</th>
						<th>Created At</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
					</thead>




					<tbody>

					</tbody>
				</table>



			</div>
		</div>
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


<div class="panel panel-flat">
	<div class="panel-heading">
		<h6 class="panel-title"><i class="icon-calendar2 icon-clr position-left"></i> Employee Leave Calendar</h6>
		<div class="heading-elements">
			<ul class="icons-list icon-clr hide">
			<!-- <li class="add-new-form" style="cursor:pointer;"><i class="icon-plus-circle2"></i></li> -->
				<li style="background-color: green; color: white;width: min-content;">  Annual Leave</li>
				<li style="background-color: red; color: white;width: min-content;">  Emergency Leave</li>
				<li style="background-color: #c1c1c1; color: white;width: min-content;">  Sick Leave</li>
				<li style="background-color: violet; color: white;width: min-content;">  Authorised Absence</li>
				<li style="background-color: orange; color: white;width: min-content;">  Bereavement Leave</li>
			</ul>
		</div>
	</div>
	<div class="panel-body">
		<div class="schedule"></div>
	</div>
</div>


<style>
	.file-preview-image {
		width: unset !important;
	}.kv-file-upload,.fileinput-upload-button{
		 display: none !important;
	}
</style>
