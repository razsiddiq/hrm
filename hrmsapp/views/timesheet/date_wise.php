<?php
/* Date Wise Attendance view
*/
?>
<?php $session = $this->session->userdata('username');?>



<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">
			<strong>Filter</strong>
		</h5>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<form class="add form-hrm" method="post" name="attendance_datewise_report" id="attendance_datewise_report" action="ajax_table.php">
					<input type="hidden" name="user_id" id="user_id" value="<?php echo $session['user_id'];?>">
					<div class="row">
						<div class="col-md-1">
							<div class="form-group">
								<select class="location_value" class="form-control" data-plugin="select_hrm">
									<option value="0">All Locations</option>
									<?php foreach($this->Xin_model->all_locations() as $locs) {?>
										<option <?php if(isset($_GET['loc']) && $_GET['loc']== $locs->location_id){?>selected <?php } ?> value="<?php echo $locs->location_id;?>"><?php echo $locs->location_name;?></option>
									<?php } ?>
								</select>
							</div>
							<button type="submit" class="btn bg-teal-400 save">Search</button>

						</div>

						<div class="col-md-2">
							<div class="form-group">
							  	<select name="dept_id" id="dep_id" class="select2" data-plugin="select_hrm" data-placeholder="Choose Department...">
									<option value="0">All department</option>
									<?php 
									$dep = $this->Xin_model->all_departments_chart();
									foreach($dep as $dpts){
									?>
									<option value="<?php echo $dpts->department_id;?>"><?php echo $dpts->department_name;?></option>
									<?php }?>
							  	</select>
							</div>
						</div>

						<div class="col-md-2">
							<div class="form-group">
								<div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
									<input class="form-control" placeholder="Select Date" name="start_date" id="start_date" size="16" type="text"  value="<?php echo date('d F Y',strtotime('-1 Day',strtotime(date('d F Y'))));?>" readonly>
									<span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>
								</div>
								<label class="text-small text-grey mt-5">Start Date</label>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">

								<div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
									<input class="form-control" placeholder="Select Date" name="end_date" id="end_date" size="16" type="text"  value="<?php echo date('d F Y',strtotime('-1 Day',strtotime(date('d F Y'))));?>" readonly>
									<span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>
								</div>
								<label class="text-small text-grey mt-5">End Date</label>

							</div>
						</div>

						<div class="col-md-2">
							<div class="form-group">
								<select name="user_type" id="user_type" onchange="getUserList(this.value);" class="form-control" data-plugin="select_hrm" data-placeholder="Choose an Employee type...">
									<option value="1" >Active users</option>
									<option value="0" >Inactive users</option>
								</select>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<div id="defaultList">
									<select name="employee_id" id="employee_id" onChange="select_employee(this.value);" class="form-control" data-plugin="select_hrm" data-placeholder="Choose an Employee...">
										<option value="all">All Employees</option>
										<?php foreach($all_employees as $employee) {
											if(isset($_GET['loc']) && $employee->office_location_id == $_GET['loc']){?>
											<option value="<?php echo $employee->user_id;?>" > <?php echo $employee->first_name.' '.$employee->middle_name.' '.$employee->last_name;?></option>
										<?php }
											//if(isset($_GET['loc'])){?>
												<!-- <option value="<?php echo $employee->user_id;?>" > <?php echo $employee->first_name.' '.$employee->middle_name.' '.$employee->last_name;?></option> -->
											<?php //}
										} ?>
									</select>
								</div>
								<div id="newtList"></div>
							</div>
						</div>

						<div class="col-md-3">

						</div>

					</div>
			</div>
			</form>
		</div>

	</div>
</div>

<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">

			<strong>Attendance</strong>

		</h5>

	</div>


	<div data-pattern="priority-columns">
		<table class="table" id="xin_table" >
			<thead>
			<tr>
				<th></th>
				<th>Status</th>
				<th class="change_title_name">Name</th>
				<th>Clock IN</th>
				<th>Clock OUT</th>
				<th>Late</th>
				<th>Early Leaving</th>
				<th>Over Time (Day/Night)</th>
				<th>Total Work</th>
				<th>Shift Time / Week Off</th>
			</tr>
			</thead>


		</table>
	</div>

	<div class="col-md-12">
		<div class="set_one">
			<div class="col-md-2">
				<h6>Late: <span class="total_late">00h 00m</span></h6>
			</div>
			<div class="col-md-2">
				<h6>Early: <span class="total_early">00h 00m</span></h6>
			</div>
			<div class="col-md-2">
				<h6>Late + Early: <span class="sum_late_early">00h 00m</span></h6>
			</div>
		</div>

		<div class="set_two">
			<div class="col-md-2">
				<h6>Required working hours: <span class="required_working_hours">00h 00m</span></h6>
			</div>
			<div class="col-md-2">
				<h6>Total working hours: <span class="total_working_hours_range">00h 00m</span></h6>
			</div>
			<div class="col-md-2">
				<h6>Delta working hours: <span class="delta_working_hours">00h 00m</span></h6>
			</div>
		</div>

	</div>

	<div class="set_one">
		<div class="col-md-12">
			<div class="col-md-12">
				<h4>Sum after Grace Period: <span class="sum_after_grace">00h 00m</span></h4>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function () {
		$('.location_value').change(function () {
			window.location.href = window.location.pathname+'?loc='+$(this).val();
		});
	})
</script>
