<?php
/* Employee Exit view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php if(in_array('27v',role_resource_ids())) {?>
	<?php if(in_array('27a',role_resource_ids())) {?>
		<div class="add-form" style="display:none;" ><!---->
			<div class="panel panel-flat">
				<div class="panel-heading">
					<h5 class="panel-title">

						<strong><?php echo $this->lang->line('xin_add_new');?></strong> Employee Exit

					</h5>
					<div class="heading-elements">
						<div class="add-record-btn">
							<button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_hide');?></button>
						</div>
					</div>
				</div>

				<div class="row m-b-1">
					<div class="col-md-12">
						<form action="<?php echo site_url("employee_exit/add_exit") ?>" method="post" name="add_exit" id="xin-form">
							<input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">

							<div class="col-lg-12">
								<div class="col-md-6">
									<div class="form-group">
										<label for="employee">Employee to Exit</label>
										<select name="employee_id" id="employee_id" onchange="read_employee_details();" class="form-control" data-plugin="select_hrm" data-placeholder="Choose an Employee...">
											<option value=""></option>
											<?php foreach($all_employees as $employee) {?>
												<option value="<?php echo $employee->user_id;?>"> <?php echo change_fletter_caps($employee->first_name.' '.$employee->middle_name.' '.$employee->last_name);?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="type">Type of Exit</label>
										<select class="select2" onchange="read_employee_details();"  id="exit_type" data-plugin="select_hrm" data-placeholder="Type of Exit" name="exit_type">
											<option value=""></option>
											<?php foreach($all_exit_types as $exit_type) {?>
												<option value="<?php echo $exit_type->type_id;?>"><?php echo $exit_type->type_name;?></option>
											<?php } ?>
										</select>
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group">
										<label for="type">Type of Form</label>

										<select class="select2" onchange="read_employee_details();"   id="approval_form" data-plugin="select_hrm" data-placeholder="Type of Form" name="approval_form">
											<option value=""></option>
											<option value="Clearance Form">Clearance Form</option>
											<option value="Final Settlement">Final Settlement</option>
										</select>
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group">
										<label for="date_of_leaving" class="control-label"><?php echo $this->lang->line('xin_employee_dol');?></label>
										<div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
											<input class="form-control" onchange="read_employee_details();"  placeholder="<?php echo $this->lang->line('xin_employee_dol');?>" name="date_of_leaving" id="date_of_leaving"   size="16" type="text"  value="" readonly>
											<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
										</div>
									</div>
								</div>

								<div class="col-md-12">
									<div class="form-group">
										<label for="description">Description</label>
										<textarea class="form-control textarea" placeholder="Reason" name="reason" cols="30" rows="10" id="reason"></textarea>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="row" id="open_forms" style="display:none;">
									<div class="">
										<div class="panel-body">
											<div class="tabbable tab-content-bordered">
												<div id="finalsettlement-tab">
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="footer-elements">

									<button type="submit" class="btn bg-teal-400 save"><?php echo $this->lang->line('xin_save');?></button>
								</div>
							</div>

						</form>
					</div>
					<div class="clearfix"></div>
					<div id="html_show">


					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	<div class="panel panel-flat">
		<div class="panel-heading">
			<h5 class="panel-title">

				<strong><?php echo $this->lang->line('xin_list_all');?></strong> Employee Exit

			</h5>
			<div class="heading-elements">
				<?php if(in_array('27a',role_resource_ids())) {?>
					<div class="add-record-btn">
					<button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_add_new');?></button>
					</div><?php } ?>
			</div>
		</div>

		<div data-pattern="priority-columns">
			<table class="table table-striped" id="xin_table">
				<thead>
				<tr>
					<th>Employee</th>
					<th>Exit Type</th>
					<th>Approval Form</th>
					<th>Added By</th>
					<th>Added Date</th>
					<th>Action</th>
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
