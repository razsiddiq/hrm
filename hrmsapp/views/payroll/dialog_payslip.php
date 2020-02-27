<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['emp_id']) && $_GET['data']=='pay_payment' && $_GET['type']=='pay_payment'){ ?>
	<?php
	$grade_template = $this->Payroll_model->read_make_payment_information($_GET['pay_id']);

	$payment_month = strtotime($payment_date);
	$p_month = date('F Y',$payment_month);

//echo "pay_id".$_GET['pay_id'];

	$p_method_type=$this->Xin_model->read_document_type($payment_method,'payment_method');
	$p_method=@$p_method_type[0]->type_name;


	?>
	<?php
	if($profile_picture!='' && $profile_picture!='no file') {
		$u_file = base_url().'uploads/profile/'.$profile_picture;
	} else {
		if($gender=='Male') {
			$u_file = base_url().'uploads/profile/default_male.jpg';
		} else {
			$u_file = base_url().'uploads/profile/default_female.jpg';
		}
	} ?>
	<script>
		$('[data-popup=popover-custom]').popover({
			template: '<div class="popover border-teal-400"><div class="arrow"></div><h3 class="popover-title bg-teal-400"></h3><div class="popover-content"></div></div>'
		});
	</script>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
		<h4 class="modal-title" id="edit-modal-data">Salary Details of <?php echo $p_month;?></h4>
	</div>
	<div class="modal-body">
		<div class="row row-md">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header"><b><?php echo change_fletter_caps($first_name.' '.$middle_name.' '.$last_name);?></b></div>
					<div class="bg-white product-view">
						<div class="card-block">
							<div class="row">
								<div class="col-md-4 col-sm-5">
									<div class="pv-images mb-sm-0" style="text-align: center;"> <img style="width:130px;box-shadow:3px 2px 8px 0px black;" class="img-fluid" src="<?php echo $u_file;?>" alt=""> </div>
								</div>
								<div class="col-md-8 col-sm-7">
									<div class="pv-content">
										<div  data-pattern="priority-columns">
											<table class="table-hover table-xxs">
												<tbody>
												<tr>
													<td><strong>EMP ID</strong>:</td>
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
					<div class="card-header"><b>Work Details</b></div>
					<div class="card-block">
						<div class="row m-b-1">

							<table class="table-hover table-xxs">
								<tbody>
								<tr>
									<td><strong>Basic Salary</strong>:</td>

									<td><?php echo currency_sign(change_num_format($grade_template[0]->basic_salary),$currency);?></td>
								</tr>

								<?php if($total_working_hours):?>
									<tr>
										<td><strong>Total Hours Worked</strong>:</td>

										<td><?php echo decimalHours($total_working_hours);?></td>
									</tr>
								<?php endif;?>

								<?php if($late_working_hours):?>
									<tr>
										<td><strong>Late Hours</strong>:</td>

										<td><?php echo decimalHours($late_working_hours);?>
										</td>
									</tr>
								<?php endif;?>


								<?php if($is_payment==1):?>
									<tr>
										<td><strong>Status</strong>:</td>

										<td><span class="label label-success">Paid</span></td>
									</tr>
								<?php endif;?>



								<?php
								$driver_delivery_count=json_decode($grade_template[0]->driver_delivery_details);
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
			<?php
			$adds=0;
			$deds=0;
			$perp=0;
			$salary_components=json_decode($salary_components);
			if($salary_components): ?>
				<div class="col-sm-12 col-xs-12">
					<div class="card">
						<div class="card-header text-uppercase"><b> Allowances</b> </div>
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


									<?php if(@$bonus!='' || @$bonus!=0): ?>
										<tr>
											<td><strong>Bonus</strong>:</td>

											<td><?php echo currency_sign(change_num_format($bonus),$currency);
												?></td>
										</tr>
									<?php endif;?>


									</tbody>
								</table>




							</div>

						</div>
					</div>
				</div>
			<?php endif;?>

			<?php

			$add_sal=$salary_with_bonus;

			?>


			<?php

			if($additonal_salary || $deduction_salary):
				?>


				<div class="col-sm-12 col-xs-12">
					<div class="card">
						<div class="card-header"><b> Internal Adjustments</b> </div>
						<div class="card-block">
							<div class="row m-b-1">
								<?php
								if($additonal_salary && $deduction_salary){
									$col_class='col-sm-6';
								}else{
									$col_class='col-sm-12';
								}

								if($additonal_salary){?>
									<div class="<?php echo $col_class;?> no-padding-left">

										<div class="card-header"><b> Addition</b> </div>

										<table class="table-hover table-xxs">

											<tbody>
											<?php

											foreach($additonal_salary as $int_add_adjustments){

												if($int_add_adjustments->parent_type_name=='Addition'){
													$adds+=$int_add_adjustments->amount;

													?>
													<tr>
														<td><strong><?php echo $int_add_adjustments->child_type_name ?></strong>:</td>

														<td><?php echo currency_sign(change_num_format($int_add_adjustments->amount),$currency);//echo @$this->Xin_model->currency_sign(change_num_format($int_add_adjustments->amount),'',$_GET['emp_id']);?>
															<?php if($int_add_adjustments->comments!=''){?>
																<i style="cursor:pointer;" class="ml-10 icon-bubble-dots4 position-right text-teal-400" data-popup="popover-custom" data-placement="bottom" title="<?php echo $int_add_adjustments->child_type_name ?>" data-trigger="hover" data-content="<?php echo $int_add_adjustments->comments; ?>"></i>
															<?php }?>

														</td>


													</tr>
												<?php } }?>
											</tbody>
										</table> </div>
								<?php } ?>



								<?php if($deduction_salary){?>
									<div class="<?php echo $col_class;?> no-padding-left">
										<div class="card-header"><b> Deduction</b> </div>

										<table class="table-hover table-xxs">

											<tbody>
											<?php foreach($deduction_salary as $int_ded_adjustments){

												if($int_ded_adjustments->parent_type_name=='Deduction'){
													$deds+=$int_ded_adjustments->amount;
													?>
													<tr>
														<td><strong><?php echo $int_ded_adjustments->child_type_name ?></strong>:</td>

														<td><?php echo currency_sign(change_num_format($int_ded_adjustments->amount),$currency);//echo @$this->Xin_model->currency_sign(change_num_format($int_ded_adjustments->amount),'',$_GET['emp_id']);?>
															<?php if($int_ded_adjustments->comments!=''){?>
																<i style="cursor:pointer;" class="ml-10 icon-bubble-dots4 position-right text-teal-400" data-popup="popover-custom" data-placement="bottom" title="<?php echo $int_ded_adjustments->child_type_name ?>" data-trigger="hover" data-content="<?php echo $int_ded_adjustments->comments; ?>"></i>
															<?php }?>
														</td>
													</tr>
												<?php } }?>
											</tbody>
										</table>


									</div>
								<?php } ?>
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
											<?php foreach($find_external_perp_adjustments as $ext_per_adjustments){

												if($ext_per_adjustments->parent_type_name=='Perpetual'){
													$perp+=$ext_per_adjustments->amount;

													?>
													<tr>
														<td><strong><?php echo $ext_per_adjustments->child_type_name ?></strong>:</td>

														<td><?php echo currency_sign(change_num_format($ext_per_adjustments->amount),$currency);//echo @$this->Xin_model->currency_sign(change_num_format($ext_per_adjustments->amount),'',$_GET['emp_id']);?>
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
													$perp+=$ext_nonper_adjustments->amount;
													?>
													<tr>
														<td><strong><?php echo $ext_nonper_adjustments->child_type_name ?></strong>:</td>

														<td><?php echo currency_sign(change_num_format($ext_nonper_adjustments->amount),$currency);//echo @$this->Xin_model->currency_sign(change_num_format($ext_nonper_adjustments->amount),'',$_GET['emp_id']);?>
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


								<?php


								echo '<tr><td><strong>Leave Salary</strong></td><td>';

								echo currency_sign(change_num_format($grade_template[0]->annual_leave_salary),$currency);
								?>

								<?php
								$ot_hours_amount=json_decode($grade_template[0]->ot_hours_amount);
								$ot_day_rate=$ot_hours_amount->ot_day_amount;
								$ot_night_rate=$ot_hours_amount->ot_night_amount;
								$ot_holiday_rate=$ot_hours_amount->ot_holiday_amount;


								$ot_rate=$ot_day_rate+$ot_night_rate+$ot_holiday_rate;
								if($ot_rate!=0){

									$overtime_hours='<i style="cursor:pointer;" data-html="true" class="ml-10 icon-bubble-dots4 position-right text-teal-400" data-popup="popover-custom" data-placement="top" title="Over Time Hours" data-trigger="hover" data-content="<table><tr><td>OT - Day<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.decimalHoursFormat($ot_hours_amount->ot_day_hours).'</td><tr><td>OT - Night<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.decimalHoursFormat($ot_hours_amount->ot_night_hours).'</td><tr><td>OT - Holiday<td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.decimalHoursFormat($ot_hours_amount->ot_holiday_hours).'</td><tr><table>"></i>';

									?>
									<tr>
										<td><strong>OT Salary</strong>:</td>
										<td><?php echo currency_sign(change_num_format($ot_rate),$currency);?>
											<?php echo $overtime_hours;?></i>
										</td>
									</tr>

								<?php } ?>





								<?php if($adds!=0):?>
									<tr>
										<td><strong>Total Additions</strong>:</td>
										<td><?php echo currency_sign(change_num_format($adds),$currency);//echo @$this->Xin_model->currency_sign(change_num_format($adds),'',$_GET['emp_id']);?></td>
									</tr>
								<?php endif;?>

								<?php if($deds!=0):?>
									<tr>
										<td><strong>Total Deductions</strong>:</td>
										<td><?php echo currency_sign(change_num_format($deds),$currency);//echo @$this->Xin_model->currency_sign(change_num_format($deds),'',$_GET['emp_id']);?></td>
									</tr>
								<?php endif;?>




								<?php //if($user_role_id==9){
								if(in_array('1',role_resource_ids())) {
									?>

									<?php if($perp!=0):?>
										<tr>
											<td><strong>Total Perpetual & Non-Perpetual</strong>:</td>
											<td><?php echo currency_sign(change_num_format($perp),$currency);//echo @$this->Xin_model->currency_sign(change_num_format($perp),'',$_GET['emp_id']);?></td>
										</tr>
									<?php endif;?>
									<tr>
										<td><strong>Paid Amount</strong>:</td>
										<?php $N_salary=($total_salary+$adds+$perp)-$deds;?>
										<td><?php echo currency_sign(change_num_format($payment_amount),$currency);//echo @$this->Xin_model->currency_sign(change_num_format($payment_amount),'',$_GET['emp_id']);?></td>
									</tr>


									<!--<tr>
                         <td><strong>Total Earned this month by attendance</strong>:</td>
						<?php //$N_salarys=($payment_amount-$perp)-($adds+$l_amount)+$deds;
									?>
                          <td><?php //echo currency_sign(change_num_format($N_salarys),$currency);//echo @$this->Xin_model->currency_sign(change_num_format($payment_amount),'',$_GET['emp_id']);?></td>
                        </tr>-->

									<tr>


										<td><strong>Total Amount Paid to employee</strong>:</td>
										<?php $N_salary=($payment_amount)-$perp;?>
										<td><?php echo currency_sign(change_num_format($payment_amount_to_employee),$currency);//echo @$this->Xin_model->currency_sign(change_num_format($payment_amount_to_employee),'',$_GET['emp_id']);?></td>
									</tr>
								<?php }  else {?>

									<tr>
										<td><strong>Paid Amount</strong>:</td>
										<?php $N_salary=($payment_amount)-$perp;?>
										<td><?php echo currency_sign(change_num_format($payment_amount_to_employee),$currency);//echo @$this->Xin_model->currency_sign(change_num_format($payment_amount_to_employee),'',$_GET['emp_id']);?></td>
									</tr>

								<?php } ?>

								<?php //if($user_role_id==9){
								if(in_array('1',role_resource_ids())) {
									?>
									<?php
									if($tax_amount){
										foreach($tax_amount as $tax_t){	?>
											<tr>
												<td><strong><?php echo $tax_t->tax_name.' ('.$tax_t->tax_percentage.'%)';?>:</strong> </td><td><?php echo currency_sign(change_num_format($tax_t->tax_amount),$currency);?></td>
											</tr>
										<?php }	?>
										<tr>
											<td><strong>Payment Amount with Tax:</strong></td><td><?php echo currency_sign(change_num_format($payment_amount_with_tax),$currency);?></td>
										</tr>
									<?php  } ?>


								<?php } ?>

								<tr>
									<td><strong>Payment Method</strong>:</td>
									<td><?php echo $p_method;?></td>
								</tr>
								<tr>
									<td><strong>Comment</strong>:</td>
									<td><?php echo $comments;?></td>
								</tr>

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
								$this->load->Model('Timesheet_model');
								$attn_month=date('Y-m',strtotime($p_month));
								$attn_date=salary_start_end_date($attn_month);


								$attendance=explode('-',$attn_month);
								$days_count_per_month=cal_days_in_month(CAL_GREGORIAN,$attendance[1],$attendance[0]);
								$calendar_start_date=$attn_month.'-01';
								$calendar_end_date=$attn_month.'-'.$days_count_per_month;
								if($driver_delivery_count){
									$start_dt=$calendar_start_date;
									$end_dt=$calendar_end_date;
								}else{
									$start_dt=$attn_date['exact_date_start'];
									$end_dt=$attn_date['exact_date_end'];
								}





								$employee = $this->Xin_model->read_user_info_attendance($_GET['emp_id'],0,0);

								$start_date = new DateTime( $start_dt);
								$end_date = new DateTime( $end_dt );
								$end_date = $end_date->modify( '+1 day' );

								$interval_re = new DateInterval('P1D');
								$date_range = new DatePeriod($start_date, $interval_re ,$end_date);
								$attendance_arr = array();

								$sandwich_status=$this->Payroll_model->sandwich_status($_GET['emp_id'],$start_dt,$end_dt,$employee[0]->department_id,$employee[0]->country_id);

								$data = array();
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
											if($tim_l!='00:00'){
												$tim_l=' ('.decimalHours($tim_l).')';
											}
											else{
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
										if(@$attendance_details[0]->attendance_status==''){ $status = '';	}	else{ $status=$attendance_details[0]->attendance_status;}
										$tdate = $this->Xin_model->set_date_format($attendance_date);

										$change_title=$tdate;

										//@$real_status=$attendance_details[0]->attendance_status;
										//if($real_status=='P' || $real_status=='LT'){
										//	$real_status=$real_status;
										//}else{
										$real_status=check_leave_status($attendance_date,$r->user_id,$attendance_details[0]->attendance_status,$attendance_details[0]->clock_in,$attendance_details[0]->clock_out,$attendance_details[0]->shift_start_time,$attendance_details[0]->shift_end_time,$attendance_details[0]->week_off,$r->department_id,$r->country_id);
										//}
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

										$bus_lateness= get_bus_users_late($_GET['emp_id'],$attendance_date,$employee[0]->office_location_id);
										if($bus_lateness){
											if($bus_lateness['late_rest_value']!=0){
												$bus_late='<br><span class="label bg-teal-400 mt-5">Bus LT-'.$bus_lateness['late_hours'].'</span>';
											}else{$bus_late='';}
										}else{
											$bus_late='';
										}

										$result_manual= check_OB_Hours($_GET['emp_id'],$attendance_date);
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




									//}
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

<?php } else if(isset($_GET['jd']) && isset($_GET['emp_id']) && $_GET['data']=='compute_leave_settlement' && $_GET['type']=='compute_leave_settlement'){?>
	<script>
		$(document).ready(function() {
			$('body').on('click', '#btnPrint', function(){
				var printContents = document.getElementById('dvContainer').innerHTML;
				var originalContents = document.body.innerHTML;
				document.body.innerHTML = printContents;
				window.print();
				document.body.innerHTML = originalContents;
				setTimeout("closePrintView()", 3000); //delay required for IE to realise what's going on
				window.onafterprint = closePrintView(); //this is the thing that makes it work i
				function closePrintView() { //this function simply runs something you want it to do
					document.location.href = ""; //in this instance, I'm doing a re-direct
				}
			});
		});
	</script>

	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
		<h4 class="modal-title" id="edit-modal-data">Leave Settlement of <strong><?php echo change_fletter_caps($first_name.' '.$middle_name.' '.$last_name);?></strong></h4>
	</div>
	<div class="modal-body">
		<div class="row row-md">

			<!--<div style="position: absolute;z-index: 100;right: 1em;top: 0.5em;cursor:pointer;"><i class="icon-printer" style="color:#249E92;font-size:1.5em;" id="btnPrint"></i></div>-->
			<div class="bg-white product-view">
				<div class="card-block">
					<div class="row">
						<?php echo $html1;?></div>
				</div>
			</div>

		</div>


	</div>






<?php } ?>
