<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$session = $this->session->userdata('username');
$this->load->Model('Timesheet_model');
$attn_date=salary_start_end_date(@$_GET['payment_date']);
$start_dt=$attn_date['exact_date_start'];
$end_dt=$attn_date['exact_date_end'];
$employee = $this->Xin_model->read_user_info_attendance(@$_GET['employee_id'],0,0);
if(isset($_GET['jd']) && isset($_GET['salary_template_id']) && $_GET['data']=='payrollpay'){

	$field_role=$_GET['field_role'];
	?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
		<h4 class="modal-title" id="edit-modal-data">Edit Payroll Template
			<?php if($is_approved==1){?>
				<span class="label label-success">Approved</span>
			<?php }else{ ?>
				<span class="label label-info">Not Yet Approved</span>
			<?php } ?>
		</h4>
	</div>
	<form class="m-b-1" action="<?php echo site_url("payroll/update_template").'/'.$salary_template_id; ?>" method="post" name="update_template" id="update_template" autocomplete="off">
		<input type="hidden" name="_method" value="EDIT">
		<input type="hidden" name="_token" value="<?php echo $salary_template_id;?>">
		<input type="hidden" name="employee_id" value="<?php echo $user_id;?>">
		<div class="modal-body">
			<div class="bg-white">
				<div class="box-block">
					<div class="row">
						<div class="col-md-12">
							<div class="row">

								<?php   if($salary_fields){
									foreach($salary_fields as $sal_field){
										?>
										<div class="col-md-3">
											<div class="form-group">
												<label for="<?php echo $sal_field->salary_field_name;?>" class="control-label"><?php echo salary_title_change($sal_field->salary_field_name);?></label>
												<input class="form-control <?php echo $sal_field->salary_field_name.'1';?> <?php if($sal_field->salary_calculate==1){?>m_salary<?php } ?>" placeholder="<?php echo salary_title_change($sal_field->salary_field_name);?>" name="sal_field[<?php echo $sal_field->salary_field_id;?>]" value="<?php if($sal_field->salary_amount!=''){echo $sal_field->salary_amount; }else{echo 0;}?>" type="text" pattern="\d*\.?\d*" title="<?php echo $this->lang->line('xin_use_numbers_price');?>">
											</div>
										</div>

									<?php  }  }?>




								<div class="col-md-3">
									<div class="form-group">
										<label for="dearness_allowance">Salary Based On Contract</label>
										<input class="form-control" id="m_total_based_contract" placeholder="Salary Based On Contract" name="salary_based_on_contract" value="<?php echo @$salary_based_on_contract;?>" type="text" readonly pattern="\d*\.?\d*" title="Format should be 5000 or 5000.00">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="dearness_allowance">Salary With Bonus </label>
										<span class="hidden-print"><input type="text" name="salary_with_bonus" readonly id="m_total" value="<?php echo @$salary_with_bonus;?>" class="form-control" readonly pattern="\d*\.?\d*" title="Format should be 5000 or 5000.00"></span>
									</div>
								</div>




								<div class="col-md-3">
									<div class="form-group">
										<label for="effective_from_date">Effective From Date</label>

										<div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
											<input class="form-control" placeholder="Effective From Date" name="effective_from_date" size="16" type="text" value="<?php echo @$effective_from_date;?>" readonly>
											<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>

										</div>



									</div>
								</div>

								<?php if(@$effective_to_date!='' || $session['role_name']==AD_ROLE){?>
									<div class="col-md-3">
									<div class="form-group">
										<label for="effective_to_date">Effective To Date</label>

										<div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
											<input class="form-control" placeholder="Effective To Date" name="effective_to_date" size="16" type="text" value="<?php echo @$effective_to_date;?>" readonly>
											<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>

										</div>


									</div>
									</div><?php } else {?>
									<input class="form-control" readonly placeholder="Effective To Date" name="effective_to_date" type="hidden" readonly>
								<?php } ?>
								<?php if($session['role_name'] == AD_ROLE){?>
									<div class="col-md-3">
										<div class="form-group">
											<label for="name">Approve Status</label>
											<select class="form-control" name="is_approved" data-plugin="select_hrm">
												<option value="1" <?php if($is_approved==1){echo 'selected';}?>>Approved</option>
												<option value="0" <?php if($is_approved==0){echo 'selected';}?>>Not Yet Approved</option>
											</select></div>
									</div>
								<?php }else { ?>
									<input type="hidden" value="<?php echo $is_approved;?>" name="is_approved"/>
								<?php } ?>

							</div>


						</div>
					</div>


				</div>
			</div>


		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

			<?php if($field_role!='view'){if((@$effective_to_date=='' && $is_approved==0)  || $session['role_name']==AD_ROLE){?>
				<button type="submit" class="btn bg-teal-400">Update</button>
			<?php } }?>




		</div>
	</form>

	<script type="text/javascript">
		$('.form_date').datetimepicker({
			weekStart: 1,
			todayBtn:  1,
			autoclose: 1,
			todayHighlight: 1,
			startView: 2,
			minView: 2,
			forceParse: 0,
			pickerPosition: "bottom-left"
		});

		$(document).ready(function(){

			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));


			/* Edit data */
			$("#update_template").submit(function(e){
				e.preventDefault();
				var obj = $(this), action = obj.attr('name');
				$('.save').prop('disabled', true);

				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize()+"&is_ajax=1&edit_type=payroll&form="+action,
					cache: false,
					success: function (JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('.save').prop('disabled', false);
						} else {
							$('#xin_table_pay').dataTable().api().ajax.reload(function(){
								toastr.success(JSON.result);
							}, true);
							$('.edit-modal-data-payrol').modal('toggle');
							$('.save').prop('disabled', false);
						}
					}
				});
			});


		});




		$(document).on("keyup", function () {
			var sum_total = 0;
			var deduction = 0;
			var net_salary = 0;
			var allowance = 0;
			var bonus=0;
			var bonus_val=$('.bonus1').val();
			if(bonus_val!=undefined){ bonus_val=bonus_val;}else{bonus_val=0;}
			$(".m_salary").each(function () {
				sum_total += +$(this).val();
			});

			$(".m_deduction").each(function () {
				deduction += +$(this).val();
			});

			$(".m_allowance").each(function () {
				allowance += +$(this).val();
			});

			$("#m_total").val(sum_total);
			var total_based_contract = sum_total - bonus_val;
			$("#m_total_based_contract").val(total_based_contract);

		});

	</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['employee_id']) && $_GET['data']=='payroll_template' && $_GET['type']=='payroll_template'){ ?>
	<script>
		$('[data-popup=popover-custom]').popover({
			template: '<div class="popover border-teal-400"><div class="arrow"></div><h3 class="popover-title bg-teal-400"></h3><div class="popover-content"></div></div>'
		});
	</script>
	<?php
	$grade_template = $this->Payroll_model->read_template_information_byempid($_GET['employee_id']);
	$attendance_date=$_GET['payment_date'];
	$attn_date=salary_start_end_date($attendance_date);




	$attendance=explode('-',$attendance_date);
	$days_count_per_month=cal_days_in_month(CAL_GREGORIAN,$attendance[1],$attendance[0]);
	$calendar_start_date=$attendance_date.'-01';
	$calendar_end_date=$attendance_date.'-'.$days_count_per_month;
	$driver_delivery_count=json_decode($_GET['t_driver_delivery_details']);
	if($driver_delivery_count){
		$start_dt=$calendar_start_date;
		$end_dt=$calendar_end_date;
	}else{
		$start_dt=$attn_date['exact_date_start'];
		$end_dt=$attn_date['exact_date_end'];
	}


	$pre_month=date('Y-m',strtotime("-1 month",strtotime($attendance_date)));
	$pre_month_start_date=$start_dt;
	$pre_month_end_date=date('Y-m-d',strtotime("-1 Day",strtotime($calendar_start_date)));


	$find_internal_add_adjustments = $this->Payroll_model->find_adjustments($_GET['employee_id'],$start_dt,$end_dt,'internal_adjustments','Addition');
	$find_internal_ded_adjustments = $this->Payroll_model->find_adjustments($_GET['employee_id'],$start_dt,$end_dt,'internal_adjustments','Deduction');

	$find_external_perp_adjustments = $this->Payroll_model->find_ext_adjustments($_GET['employee_id'],$start_dt,$end_dt,'external_adjustments','Perpetual');
	$find_external_nonperp_adjustments = $this->Payroll_model->find_ext_adjustments($_GET['employee_id'],$start_dt,$end_dt,'external_adjustments','Non-Perpetual');

	$adds=0;
	$deds=0;
	$perp=0;

	$user_info = $this->Xin_model->read_user_info($_GET['employee_id']);
	$date_of_joining=$user_info[0]->date_of_joining;
	$date_of_leaving=$user_info[0]->date_of_leaving;

	$shift_hours=$_GET['shift_hours'];
	$check_365days_enabled=$_GET['t_check_365days_enabled'];
	?>
	<?php
	if($profile_picture!='' && $profile_picture!='no file') {
		$u_file = 'uploads/profile/'.$profile_picture;
	} else {
		if($gender=='Male') {
			$u_file = 'uploads/profile/default_male.jpg';
		} else {
			$u_file = 'uploads/profile/default_female.jpg';
		}
	} ?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
		<h4 class="modal-title" id="edit-modal-data">Employee Salary Details (Payroll Template)</h4>
	</div>
	<div class="modal-body">
		<div class="row row-md">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header"><b><?php echo change_fletter_caps($first_name.' '.$middle_name.' '.$last_name);?></b></div>
					<div class="bg-white product-view">
						<div class="panel-body">
							<div class="row">
								<div class="col-md-4 col-sm-5">
									<div class="pv-images mb-sm-0" style="text-align: center;"> <img class="img-fluid" style="width:130px;box-shadow:3px 2px 8px 0px black;" src="<?php echo base_url().$u_file;?>" alt=""> </div>
								</div>
								<div class="col-md-8 col-sm-7">
									<div class="pv-content">
										<div  data-pattern="priority-columns">
											<table class="table-hover table-xxs">
												<tbody>
												<tr>
													<td><strong>Emp ID</strong>:</td>

													<td><?php echo $employee_id;?></td>
												</tr>
												<tr>
													<td><strong>Departments</strong>:</td>

													<td><?php echo $department_name;?></td>
												</tr>
												<tr>
													<td><strong>Designation</strong>:</td>

													<td><?php echo $designation_name;?></td>
												</tr>
												<tr>
													<td><strong>Joining Date</strong>:</td>

													<td><?php echo $date_of_joining;?></td>
												</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row mb-1">
			<div class="col-sm-12 col-xs-12">
				<div class="card">
					<div class="card-header"><b>Salary Details</b></div>
					<div class="card-block">
						<div class="row m-b-1">

							<table class="table-hover table-xxs">
								<tbody>
								<tr>
									<td><strong>Basic Salary</strong>:</td>

									<td><?php echo @$this->Xin_model->currency_sign(change_num_format($_GET['t_basic_salary']),'',$_GET['employee_id']);?></td>
								</tr>
								<?php if(isset($_GET['mode']) && $_GET['mode'] == 'not_paid'):?>
									<tr>
										<td><strong>Status</strong>:</td>

										<td><span class="label label-danger">Not Paid</span></td>
									</tr>


									<tr><td><strong>Required Working hours</strong>:</td><td><?php echo $_GET['r_working_hours'];?><b> H</b></td></tr>
									<tr><td><strong>Actual Working hours</strong>:</td><td><?php echo round($_GET['t_working_hours'],2);?><b> H</b></td></tr>
									<tr><td><strong>Actual Days Worked</strong>:</td><td><?php echo round($_GET['actual_days_worked'],2);?>

											<b> Days</b></td></tr>
									<tr><td><strong>Late hours</strong>:</td><td><?php echo round($_GET['late_working_hours'],2);?><b> H</b></td></tr>




								<?php endif;?>


								<?php

								if($driver_delivery_count){
									?>

									<tr>
										<td><strong>Assigned Count</strong>:</td>
										<td><span class="label label-info"><?php echo $driver_delivery_count->assigned_count;?></span></td>
									</tr>
									<tr>
										<td><strong>Delivery Count</strong>:</td>
										<td><span class="label label-info"><?php echo $driver_delivery_count->delivery_count;?></span></td>
									</tr><tr>
										<td><strong>Cancellation Count</strong>:</td>
										<td><span class="label label-info"><?php echo $driver_delivery_count->cancellation_count;?></span></td>
									</tr><tr>
										<td><strong>Cancellation Rate</strong>:</td>
										<td><span class="label label-info"><?php echo
												$driver_delivery_count->cancellation_rate; ?>%</span></td>
									</tr>

									<tr>
										<td><strong>Target Count of this month</strong>:</td>
										<td><span class="label label-success"><?php echo
												$driver_delivery_count->monthly_target_count; ?></span></td>
									</tr>

									<tr>
										<td><strong>Minimum Cancellation Rate</strong>:</td>
										<td><span class="label label-success"><?php echo
												$driver_delivery_count->order_cancellation_percentage; ?>%</span></td>
									</tr>
									<?php

								}


								?>




								</tbody>
							</table>

						</div>
					</div>
				</div>
			</div>
			<?php $salary_components=json_decode($_GET['t_salary_components']);
			if($salary_components){ ?>
				<div class="col-sm-12 col-xs-12">
					<div class="card">
						<div class="card-header"><b> Allowances</b> </div>
						<div class="card-block">

							<div class="row m-b-1">



								<table class="table-hover table-xxs">
									<tbody>
									<?php

									foreach($salary_components as $key_com=>$val_com){
										?>
										<tr>
											<td><strong><?php echo salary_title_change($key_com);?></strong>:</td>

											<td><?php echo @$this->Xin_model->currency_sign(change_num_format($val_com),'',$_GET['employee_id']);?></td>
										</tr>
									<?php } ?>




									<?php if(@$_GET['t_bonus']!='' || @$_GET['t_bonus']!=0): ?>
										<tr>
											<td><strong>Bonus</strong>:</td>

											<td><?php echo @$this->Xin_model->currency_sign(change_num_format($_GET['t_bonus']),'',$_GET['employee_id']);?></td>
										</tr>
									<?php endif;?>


									</tbody>
								</table>


							</div>

						</div>
					</div>
				</div>
			<?php }?>

			<?php

			if($find_internal_add_adjustments || $find_internal_ded_adjustments):
				?>


				<div class="col-sm-12 col-xs-12">
					<div class="card">
						<div class="card-header"><b> Internal Adjustments</b> </div>
						<div class="card-block">
							<div class="row m-b-1">
								<?php
								if($find_internal_add_adjustments && $find_internal_ded_adjustments){
									$col_class='col-sm-6';
								}else{
									$col_class='col-sm-12';
								}

								if($find_internal_add_adjustments){?>
									<div class="<?php echo $col_class;?> no-padding-left">

										<div class="card-header"><b> Addition</b> </div>

										<table class="table-hover table-xxs">

											<tbody>
											<?php foreach($find_internal_add_adjustments as $int_add_adjustments){

												if($int_add_adjustments->parent_type_name=='Addition'){
													$adds+=$int_add_adjustments->adjustment_amount;

													?>
													<tr>
														<td><strong><?php echo $int_add_adjustments->child_type_name ?></strong>:</td>

														<td><?php echo @$this->Xin_model->currency_sign(change_num_format($int_add_adjustments->adjustment_amount),'',$_GET['employee_id']);?>
															<?php if($int_add_adjustments->comments!=''){?>
																<i style="cursor:pointer;" class="ml-10 icon-bubble-dots4 position-right text-teal-400" data-popup="popover-custom" data-placement="bottom" title="<?php echo $int_add_adjustments->child_type_name ?>" data-trigger="hover" data-content="<?php echo $int_add_adjustments->comments; ?>"></i>
															<?php }?>

														</td>


													</tr>
												<?php } }?>
											</tbody>
										</table> </div>
								<?php } ?>



								<?php if($find_internal_ded_adjustments){?>
									<div class="<?php echo $col_class;?> no-padding-left">
										<div class="card-header"><b> Deduction</b> </div>

										<table class="table-hover table-xxs">

											<tbody>
											<?php foreach($find_internal_ded_adjustments as $int_ded_adjustments){

												if($int_ded_adjustments->parent_type_name=='Deduction'){
													$deds+=$int_ded_adjustments->adjustment_amount;
													?>
													<tr>
														<td><strong><?php echo $int_ded_adjustments->child_type_name ?></strong>:</td>

														<td><?php echo @$this->Xin_model->currency_sign(change_num_format($int_ded_adjustments->adjustment_amount),'',$_GET['employee_id']);?>
															<?php if($int_ded_adjustments->comments!=''){?>
																<i style="cursor:pointer;" class="ml-10 icon-bubble-dots4 position-right text-teal-400" data-popup="popover-custom" data-placement="bottom" title="<?php echo $int_ded_adjustments->child_type_name ?>" data-trigger="hover" data-content="<?php echo $int_ded_adjustments->comments; ?>"></i>
															<?php }?>
														</td>
													</tr>
												<?php } }?>
											</tbody>
										</table>


									</div>
								<?php  } ?>
							</div>
						</div>
					</div>
				</div>

			<?php endif;?>



			<?php

			if($find_external_perp_adjustments || $find_external_nonperp_adjustments):
				?>


				<div class="col-sm-12 col-xs-12">
					<div class="card">
						<div class="card-header"><b> External Adjustments</b> </div>
						<div class="card-block">
							<div class="row m-b-1">
								<?php
								if($find_external_perp_adjustments && $find_external_nonperp_adjustments){
									$col_class='col-sm-6';
								}else{
									$col_class='col-sm-12';
								}

								if($find_external_perp_adjustments){?>
									<div class="<?php echo $col_class;?> no-padding-left">

										<div class="card-header"><b> Perpetual</b> </div>

										<table class="table-hover table-xxs">

											<tbody>
											<?php


											foreach($find_external_perp_adjustments as $ext_per_adjustments){

												if($ext_per_adjustments->parent_type_name=='Perpetual'){

													$ext_per_adjustments_amount=$ext_per_adjustments->adjustment_amount;
													//$ext_per_adjustments_amount=(($ext_per_adjustments->adjustment_amount)-($ext_per_adjustments->adjustment_amount*($ext_per_adjustments->tax_percentage/100)));

													if($ext_per_adjustments->compute_amount==0){

														if($ext_per_adjustments->child_type_name=='Agency Fees'){
															// DOJ

															if((strtotime($start_dt) <= strtotime($date_of_joining)) && (strtotime($end_dt) >= strtotime($date_of_joining))){

																$date1=new DateTime($date_of_joining);

																if((strtotime($start_dt) <= strtotime($date_of_leaving)) && (strtotime($end_dt) >= strtotime($date_of_leaving))){
																	$date2=new DateTime($date_of_leaving);
																}else{
																	$date2=new DateTime($calendar_end_date);
																}
																$interval_date=$date2->diff($date1);
																$no_of_days_worked=$interval_date->days+1;

																$computed_perp_amount=($ext_per_adjustments_amount/$days_count_per_month)*$no_of_days_worked;
																$perp+=$computed_perp_amount;

															}else if((strtotime($start_dt) <= strtotime($date_of_leaving)) && (strtotime($end_dt) >= strtotime($date_of_leaving))){


																$date1=new DateTime($calendar_start_date);

																$date2=new DateTime($date_of_leaving);
																$interval_date=$date2->diff($date1);
																$no_of_days_worked=$interval_date->days+1;

																$computed_perp_amount=($ext_per_adjustments_amount/$days_count_per_month)*$no_of_days_worked;
																$perp+=$computed_perp_amount;
																// DOJ
															}else{
																$computed_perp_amount=$ext_per_adjustments_amount;
																$perp+=$computed_perp_amount;

															}

														}else{
															$computed_perp_amount=$ext_per_adjustments_amount;
															$perp+=$computed_perp_amount;
														}



													}
													else{
														if($ext_per_adjustments->child_type_name=='Agency Fees'){
															// DOJ
															if((strtotime($start_dt) <= strtotime($date_of_joining)) && (strtotime($end_dt) >= strtotime($date_of_joining))){


																if((strtotime($pre_month_start_date) <= strtotime($date_of_joining)) && (strtotime($pre_month_end_date) >= strtotime($date_of_joining))){
																	$computed_perp_amount=(($ext_per_adjustments_amount/$_GET['r_working_hours'])*$_GET['t_working_hours'])+(($ext_per_adjustments_amount/$_GET['t_p_m_required_working_hours'])*$_GET['t_joining_month_hours']);
																	$perp+=$computed_perp_amount;

																	$perp+=$computed_amount;
																}else if((strtotime($calendar_start_date) <= strtotime($date_of_joining)) && (strtotime($calendar_end_date) >= strtotime($date_of_joining))){


																	$computed_perp_amount=(($ext_per_adjustments_amount/$_GET['r_working_hours'])*$_GET['t_working_hours']);
																	$perp+=$computed_perp_amount;
																}
															}else if((strtotime($start_dt) <= strtotime($date_of_leaving)) && (strtotime($end_dt) >= strtotime($date_of_leaving))){

																$computed_perp_amount=(($ext_per_adjustments_amount/$_GET['r_working_hours'])*$_GET['t_working_hours']);
																$perp+=$computed_perp_amount;
																// DOJ
															}else{
																if($check_365days_enabled==1){
																	$computed_perp_amount=$ext_per_adjustments_amount-(($_GET['r_working_hours']-$_GET['t_working_hours'])*($ext_per_adjustments_amount*12/365/$shift_hours));
																	$perp+=$computed_perp_amount;
																}else{
																	$computed_perp_amount=(($ext_per_adjustments_amount/$_GET['r_working_hours'])*$_GET['t_working_hours']);
																	$perp+=$computed_perp_amount;

																}






															}
														}else{
															$computed_perp_amount=(($ext_per_adjustments_amount/$_GET['r_working_hours'])*$_GET['t_working_hours']);
															$perp+=$computed_perp_amount;
														}
													}
													?>
													<tr>
														<td><strong><?php echo $ext_per_adjustments->child_type_name; ?></strong>:</td>

														<td><?php

															if($ext_per_adjustments->compute_amount==0){
																echo @$this->Xin_model->currency_sign(change_num_format($computed_perp_amount),'',$_GET['employee_id']);
															}else{
																echo @$this->Xin_model->currency_sign(change_num_format($computed_perp_amount),'',$_GET['employee_id']);
															}

															if($ext_per_adjustments->tax_percentage!=0)
															{
																//echo '   ('.$ext_per_adjustments->tax_percentage.'% VAT) ';
															}
															?>


															<?php if($ext_per_adjustments->comments!=''){?>
																<i style="cursor:pointer;" class="ml-10 icon-bubble-dots4 position-right text-teal-400" data-popup="popover-custom" data-placement="bottom" title="<?php echo $ext_per_adjustments->child_type_name ?>" data-trigger="hover" data-content="<?php echo $ext_per_adjustments->comments; ?>"></i>
															<?php }?>
														</td>
													</tr>
												<?php } }?>
											</tbody>
										</table> </div>
								<?php } ?>



								<?php if($find_external_nonperp_adjustments){?>
									<div class="<?php echo $col_class;?> no-padding-left">
										<div class="card-header"><b> Non-Perpetual</b> </div>

										<table class="table-hover table-xxs">

											<tbody>
											<?php foreach($find_external_nonperp_adjustments as $ext_nonper_adjustments){

												if($ext_nonper_adjustments->parent_type_name=='Non-Perpetual'){

//$ext_nonper_adjustments_amount=(($ext_nonper_adjustments->adjustment_amount)-($ext_nonper_adjustments->adjustment_amount*($ext_nonper_adjustments->tax_percentage/100)));
													$ext_nonper_adjustments_amount=$ext_nonper_adjustments->adjustment_amount;
													if($ext_nonper_adjustments->compute_amount==0){
														$computed_nonperp_amount=$ext_nonper_adjustments_amount;
														$perp+=$computed_nonperp_amount;
													}else{
														$computed_nonperp_amount=(($ext_nonper_adjustments_amount/$_GET['r_working_hours'])*$_GET['t_working_hours']);
														$perp+=$computed_nonperp_amount;
													}


													?>
													<tr>
														<td><strong><?php echo $ext_nonper_adjustments->child_type_name ?></strong>:</td>

														<td><?php echo @$this->Xin_model->currency_sign(change_num_format($computed_nonperp_amount),'',$_GET['employee_id']);
															if($ext_nonper_adjustments->tax_percentage!=0)
															{
																//echo '   ('.$ext_nonper_adjustments->tax_percentage.'% VAT) ';
															}

															?>
															<?php if($ext_nonper_adjustments->comments!=''){?>
																<i style="cursor:pointer;" class="ml-10 icon-bubble-dots4 position-right text-teal-400" data-popup="popover-custom" data-placement="bottom" title="<?php echo $ext_nonper_adjustments->child_type_name ?>" data-trigger="hover" data-content="<?php echo $ext_nonper_adjustments->comments; ?>"></i>
															<?php }?>
														</td>
													</tr>
												<?php } }?>
											</tbody>
										</table>


									</div>
								<?php  } ?>
							</div>
						</div>
					</div>
				</div>

			<?php endif;?>



			<div class="col-sm-12 col-xs-12">
				<div class="card">
					<div class="card-header"><b> Total Salary Details</b></div>
					<div class="card-block">
						<div class="row m-b-1">


							<table class="table-hover table-xxs">
								<tbody>


								<?php if($adds!=0):?>
									<tr>
										<td><strong>Total Additions</strong>:</td>
										<td><?php echo @$this->Xin_model->currency_sign(change_num_format($adds),'',$_GET['employee_id']);?></td>
									</tr>
								<?php endif;?>

								<?php if($deds!=0):?>
									<tr>
										<td><strong>Total Deductions</strong>:</td>
										<td><?php echo @$this->Xin_model->currency_sign(change_num_format($deds),'',$_GET['employee_id']);?></td>
									</tr>
								<?php endif;?>

								<?php
								$ot_day_rate=0;$ot_night_rate=0;$ot_holiday_rate=0;
								if($_GET['t_ot_hours_amount']!=null){
									$ot_hours_amount=json_decode($_GET['t_ot_hours_amount']);
									$ot_day_rate=$ot_hours_amount->ot_day_amount;
									$ot_night_rate=$ot_hours_amount->ot_night_amount;
									$ot_holiday_rate=$ot_hours_amount->ot_holiday_amount;
								}
								$ot_rate=$ot_day_rate+$ot_night_rate+$ot_holiday_rate;
								if($ot_rate!=0){
									?>
									<tr>
										<td><strong>OT Salary</strong>:</td>
										<td><?php echo @$this->Xin_model->currency_sign(change_num_format($ot_rate),'',$_GET['employee_id']);?></td>
									</tr>

								<?php } ?>


								<?php //if($user_role_id==9){
								if(in_array('1',role_resource_ids())) {
									?>

									<?php if($perp!=0):?>
										<tr>
											<td><strong>Total Perpetual & Non-Perpetual</strong>:</td>
											<td><?php echo @$this->Xin_model->currency_sign(change_num_format($perp),'',$_GET['employee_id']);?></td>
										</tr>
									<?php endif;?>
									<tr>
										<td><strong>Pay Amount</strong>:</td>
										<?php $N_salary=($_GET['t_salary_after_cal']+$adds+$perp+$ot_rate)-$deds;?>
										<td><?php echo @$this->Xin_model->currency_sign(change_num_format($N_salary),'',$_GET['employee_id']);?></td>
									</tr>


									<tr>
										<td><strong>Total Amount Pay to employee</strong>:</td>
										<?php $payment_amount_to_employee=($N_salary)-$perp;?>
										<td><?php echo @$this->Xin_model->currency_sign(change_num_format($payment_amount_to_employee),'',$_GET['employee_id']);?></td>
									</tr>

								<?php } else { ?>

									<tr>
										<td><strong>Pay Amount</strong>:</td>
										<?php $payment_amount_to_employee=($N_salary)-$perp;?>
										<td><?php echo @$this->Xin_model->currency_sign(change_num_format($payment_amount_to_employee),'',$_GET['employee_id']);?></td>
									</tr>

								<?php } ?>

								<?php
								if(in_array('1',role_resource_ids())) {
									?>
									<?php
									$visa_id=$_GET['t_visa_type'];
									$tax_type=get_tax_info($visa_id);

									$total_tax_amount=0;
									if($tax_type){
										foreach($tax_type as $tax_t){
											$tax_amount=($N_salary*($tax_t->type_symbol/100));
											$total_tax_amount+=$tax_amount;
											?>
											<tr>
												<td><strong><?php echo $tax_t->type_name.' ('.$tax_t->type_symbol.'%)';?>:</strong> </td><td><?php echo $this->Xin_model->currency_sign(change_num_format($tax_amount),$currency);?></td>
											</tr>

										<?php } ?>

										<tr>
											<td><strong>Payment Amount with Tax:</strong></td><td><?php echo $this->Xin_model->currency_sign(change_num_format($total_tax_amount+$N_salary),$currency);?></td>
										</tr>
									<?php } ?>

								<?php } ?>



								</tbody>
							</table>






						</div>
					</div>
				</div>
			</div>

			<div class="col-sm-12 col-xs-12">
				<div class="card">
					<div class="card-header"><b> Attendance</b></div>
					<div class="card-block">
						<div class="row m-b-1">


							<table class="table-hover table-xxs table-bordered" width="100%">
								<tbody>
								<?php
								if($driver_delivery_count){
									$table='<th>Assigned Count</th><th>Delivery Count</th><th>Cancellation Count</th><th>Cancellation Rate</th>';
								}else{
									$table='<th>Clock In</th><th>Clock Out</th><th>Late</th><th>Early Leaving</th><th>Total Work</th>';
								}
								?>

								<tr><th>Date</th><?php echo $table;?><th>Status</th></tr>
								<?php
								$start_date = new DateTime( $start_dt);
								$end_date = new DateTime( $end_dt );
								$end_date = $end_date->modify( '+1 day' );

								$interval_re = new DateInterval('P1D');
								$date_range = new DatePeriod($start_date, $interval_re ,$end_date);
								$attendance_arr = array();
								$sandwich_status=$this->Payroll_model->sandwich_status($_GET['employee_id'],$start_dt,$end_dt,$employee[0]->department_id,$employee[0]->country_id);

								$data = array();
								$total_late_h=0;
								$total_work_h=0;
								foreach($date_range as $date) {

									$attendance_date =  $date->format("Y-m-d");
									$sandwich_l='';

									if(in_array($attendance_date,$sandwich_status)){
										//$sandwich_l='<span class="label bg-danger-800">Absent</span>';
									}
									//if(strtotime($attendance_date) < strtotime(TODAY_DATE)){
									foreach($employee as $r) {
										$attendance_details = $this->Timesheet_model->attendance_details($r->user_id,$attendance_date);

										if(@$attendance_details[0]->clock_in==''){ $clock_in2 = '-';	}	else {
											$clock_in = new DateTime($attendance_details[0]->clock_in);
											$clock_in2 = $clock_in->format('h:i a');
										}
										if(@$attendance_details[0]->clock_out==''){ $clock_out2 = '-';	}	else {
											$clock_out = new DateTime($attendance_details[0]->clock_out);
											$clock_out2 = $clock_out->format('h:i a');
										}


										if(@$attendance_details[0]->overtime==''  || @$attendance_details[0]->overtime=='00:00'){ $overtime1 = '0h 0m';	}	else {
											$overtime1=$attendance_details[0]->overtime;
										}

										if(@$attendance_details[0]->overtime_night=='' || @$attendance_details[0]->overtime_night=='00:00'){ $overtime_night = '0h 0m';	}	else {

											$overtime_night=$attendance_details[0]->overtime_night;

										}

										if(@$attendance_details[0]->time_late==''){ $time_late1 = '00:00';	}	else {
											$tim_l=str_replace('m','',str_replace('h ',':',$attendance_details[0]->time_late));
											$total_late_h+=decimalHourswithoutround($tim_l);
											if($tim_l!='00:00'){
												$tim_l=' ('.decimalHours($tim_l).')';
											}else{
												$tim_l='';
											}
											$time_late1=$attendance_details[0]->time_late.$tim_l;
										}

										if(@$attendance_details[0]->early_leaving==''){ $early_leaving1 = '00:00';	}	else {
											$tim_el=str_replace('m','',str_replace('h ',':',$attendance_details[0]->early_leaving));
											if($tim_el!='00:00'){
												$tim_el=' ('.decimalHours($tim_el).')';
											}else{
												$tim_el='';
											}
											$early_leaving1=$attendance_details[0]->early_leaving.$tim_el;
										}

										if(@$attendance_details[0]->total_work==''){ $total_work1 = '00:00';	}	else { $total_work1=$attendance_details[0]->total_work.' ('.round($attendance_details[0]->total_rest,2).')';}

										$total_work_h+=decimalHourswithoutround($total_work1);

										if(@$attendance_details[0]->attendance_status==''){ $status = '';	}	else{ $status=$attendance_details[0]->attendance_status;}
										$tdate = $this->Xin_model->set_date_format($attendance_date);
										if($employee_id!='all'){
											$change_title=$tdate;
										}else{
											$change_title=$r->first_name.' '.$r->middle_name.' '.$r->last_name;
										}

										$real_status=check_leave_status($attendance_date,$r->user_id,$attendance_details[0]->attendance_status,$attendance_details[0]->clock_in,$attendance_details[0]->clock_out,$attendance_details[0]->shift_start_time,$attendance_details[0]->shift_end_time,$attendance_details[0]->week_off,$r->department_id,$r->country_id);

										$first_missed_logout='';
										if($attendance_details[0]->clock_in_out==2){
											//$first_missed_logout='<i style="cursor:pointer;" class="ml-10 icon-bubble-dots4 position-right text-teal-400" data-popup="popover-custom" data-placement="bottom" title="" data-trigger="hover" data-content="First Missed Logout"></i>';
										}
										
										$real_status1=$real_status;

										if($real_status){
											$real_status=$this->Timesheet_model->get_status_name($real_status);
											if($real_status=='Present')
												$real_status='<span class="label label-success">'.$real_status.'</span>';
											else if($real_status=='Absent')
												$real_status='<span class="label label-danger">'.$real_status.'</span>';
											else if($real_status=='New Joinee')
												$real_status='<span class="label label-info">'.$real_status.'</span>';
											else if($real_status=='Late')
												$real_status='<span class="label label-warning">'.$real_status.'</span>';
											else
												$real_status='<span class="label bg-purple-400">'.$real_status.'</span>';

										}

										if($sandwich_l!=''){
											$real_status=$sandwich_l;
										}else{
											if($real_status=='<span class="label bg-purple-400"></span>'){
												$real_status='<span class="label bg-purple-400">'.$real_status1.'</span>';
											}
										}


										$bus_lateness= get_bus_users_late($r->user_id,$attendance_date,$r->office_location_id);
										if($bus_lateness){
											if($bus_lateness['late_rest_value']!=0){
												$bus_late='<br><span class="label bg-teal-400 mt-5">Bus LT-'.$bus_lateness['late_hours'].'</span>';
											}else{$bus_late='';}
										}else{
											$bus_late='';
										}


										$result_manual= check_OB_Hours($r->user_id,$attendance_date);
										if($result_manual){
											$ob_hours='<br><span class="label bg-teal-400 mt-5" title="OB ( '.$result_manual[0]->attendance_status.' ) - '.$result_manual[0]->reason.'">OB ( '.substr($result_manual[0]->attendance_status,0,1).' ) - '.$result_manual[0]->total_hours.'</span>';
											$total_work1=add_ob_hours(array($total_work1,$result_manual[0]->total_hours));
										}else{
											$ob_hours='';
										}


										if($driver_delivery_count){



											$result=get_driver_report($r->user_id,$attendance_date,$attendance_date,'attn');

											echo '<tr><td>'.$change_title.' <span class="text-teal-400">('.date('l', strtotime($change_title)).')</span> '.'</td><td>'.@$result[0]->assigned_count.'</td><td>'.@$result[0]->achieved_delivery_count.'</td><td>'.@$result[0]->cancellation_count.'</td><td>'.@$result[0]->cancellation_rate.'</td><td>'.$real_status.$ob_hours.'</td></tr>';
										}else{
											echo '<tr><td>'.$change_title.' <span class="text-teal-400">('.date('l', strtotime($change_title)).')</span> '.'</td><td>'.$clock_in2.'</td><td>'.$clock_out2.'</td><td>'.$time_late1.'</td><td>'.$early_leaving1.'</td><td>'.$total_work1.$first_missed_logout.'</td><td>'.$real_status.$ob_hours.$bus_late.'</td></tr>';
										}

									}


								}


								?>





								</tbody>
							</table>






						</div>
					</div>
				</div>
			</div>


		</div>
	</div>
<?php } ?>
