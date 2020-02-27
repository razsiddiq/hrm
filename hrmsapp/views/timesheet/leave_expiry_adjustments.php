<?php $session = $this->session->userdata('username');?>
<?php if(in_array('al-expiry-view',role_resource_ids()) || rp_manager_access() || hod_manager_access()) {?>
	<?php if(in_array('al-expiry-add',role_resource_ids()) || rp_manager_access() || hod_manager_access()) {?>
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
				<form action="<?php echo site_url("timesheet/add_al_expiry_leaves") ?>" method="post" name="add_al_expiry_leaves" id="xin-form">
					<input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
					<div class="col-lg-12">					
						
						<div class="col-md-6">														
							<div class="form-group">
								<label for="name">Choose the Employees</label>
								<select name="adjust_employee_id" class="form-control" data-plugin="select_hrm" data-placeholder="Choose the Employees...">
									<option value="">&nbsp;</option>
									<?php foreach($employeesArray as $employee) {?>
									<option value="<?php echo $employee['user_id'];?>"> <?php echo change_fletter_caps($employee['full_name']);?></option>
									<?php } ?>
								</select>
							</div>						

							<div class="form-group">
								<label for="name">Expired Days</label>
								<select name="adjust_days" class="form-control" data-plugin="select_hrm" data-placeholder="Expired Days...">				
									<?php 
									for($aj=0;$aj <= 100;$aj++) {?>
									<option value="<?=$aj;?>"> <?=$aj;?> <?php if($aj > 1) {echo 'Days';}else{echo 'Day';}?></option>
									<?php } ?>
								</select>
							</div>

						
						</div>

						<div class="col-md-6">

						<div class="form-group">
								<label for="name_of_week">Adjust Type</label>
								<select class="form-control" name="adjust_type_id" data-plugin="select_hrm" data-placeholder="Choose the Adjust Type...">
									<option value="">&nbsp;</option>
									<?php 
									for ($o=0; $o < count($expireList); $o++) {									?>
										<option value="<?=$expireList[$o]['type_id']?>"><?=$expireList[$o]['type_name']?></option>
									<?php }?>
								</select>
							</div>					
							<div class="form-group">
								<label for="name_of_week">Description</label>
								<textarea name="adjust_description" class="form-control"></textarea>
							</div>
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
				<strong>Filter List</strong>
			</h5>
		</div>
		<div class="panel-body">
			<form action="#" method="post" name="shift_list_search" id="shift_list_search">
				<div class="row">
					
						<div class="col-md-3">
							<div class="form-group">
								<select class="location_value" class="form-control" data-plugin="select_hrm" data-placeholder="Choose the Status...">
									<option value="0">ALL LOCATIONS</option>
									<?php foreach($this->Xin_model->all_locations() as $locs) {?>
									<option value="<?php echo $locs->location_id;?>"> <?php echo $locs->location_name;?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<select class="department_value" class="form-control" data-plugin="select_hrm" data-placeholder="Choose the Status...">
									<option value="0">ALL DEPARTMENTS</option>
									<?php foreach($this->Xin_model->all_departments_chart() as $dept) {?>
									<option value="<?php echo $dept->department_id;?>" <?php //if($session['user_id']==$employee->user_id):selected?>  <?php //endif;?>> <?php echo $dept->department_name;?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					
					<div class="col-md-3">
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
								<select class="form-control" id="adjust_type_id_filter" name="adjust_type_id_filter" data-plugin="select_hrm" data-placeholder="Choose the Adjust Type...">
									<option value="0">All Type</option>
									<?php 
									for ($o=0; $o < count($expireList); $o++) {									?>
										<option value="<?=$expireList[$o]['type_id']?>"><?=$expireList[$o]['type_name']?></option>
									<?php }?>
								</select>
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
				<strong>Leave Expiry Adjustments List</strong>
			</h5>
			<div class="heading-elements">
				<div class="form-group"> &nbsp;				
					<?php if((in_array('31a',role_resource_ids()) || rp_manager_access() || hod_manager_access()) && visa_wise_role_ids() == '') {?>
						<button class="btn bg-teal-400 add-new-form">Add New</button>
					<?php } ?>
				</div>
			</div>
		</div>

		<div data-pattern="priority-columns">
			<table class="table" id="xin_expiry_list" >
				<thead>
					<tr>
					<th>Employee Name</th>
					<th>Type</th>
					<th>Description</th>
					<th>Expired Days</th>					
					<th>Location / Department</th>
					<th>Created By</th>
					<th>Created Date</th>
					<?php if(visa_wise_role_ids() == ''){?>
					<th>Action</th>
					<?php }?>
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

