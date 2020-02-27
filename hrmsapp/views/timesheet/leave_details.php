<?php
/* Leave Detail view
*/
$session = $this->session->userdata('username');
$user = $this->Xin_model->read_user_info($session['user_id']);
$ceo_data=$this->Employees_model->get_ceo_only();
$head_id=$ceo_data[0]->head_id;
$current_date=TODAY_DATE;

$approval_query=$this->db->query("select * from xin_employees_approval where type_of_approval='leave_request' AND field_id='".$applied_on."' group by head_of_approval");
$approval_result=$approval_query->result();
?>
<div class="row m-b-1">
	<div class="col-md-4">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title">
					<strong>Leave Detail</strong>
				</h5>
			</div>
			<table class="table table-striped m-md-b-0">
				<tbody>
				<tr>
					<th scope="row">Employee</th>
					<td class="text-right"><?php echo $first_name.' '.$middle_name.' '.$last_name;?></td>
				</tr>
				<tr>
					<th scope="row">Applied On</th>
					<td class="text-right"><?php echo $this->Xin_model->set_date_format($created_at);?></td>
				</tr>
				<?php if($documentfile!='' && $documentfile!='no file') {?>
					<tr>
						<th scope="row">Attached Documents</th>
						<td class="text-right">


							<?php

							$document_file=array_filter(explode(',',$documentfile));
							$i=0;
							foreach($document_file as $docu){
								$file_parts = pathinfo($docu);
								if($file_parts['extension']!='pdf'){
									?>
									<div class="col-lg-12 pull-right">
										<a href="<?php echo base_url().'uploads/leavedocument/'.$docu;?>" data-popup="lightbox" rel="gallery" class="btn border-white text-white btn-flat btn-icon btn-rounded"><div class="thumbnail" style="max-width: 193.5px;">
												<div class="thumb">
													<img style="height: 7em;width: 10em;" src="<?php echo base_url().'uploads/leavedocument/'.$docu;?>" alt="">
													<div class="caption-overflow">
										<span>

										</span>
													</div>
												</div>
											</div></a>
									</div>


								<?php }else { ?>

									<div class="col-lg-12 pull-right"><a target="_blank" href="<?php echo base_url().'uploads/leavedocument/'.$docu;?>" data-popup="lightbox" rel="gallery" class="btn border-white text-white btn-flat btn-icon btn-rounded">
											<div class="thumbnail" style="max-width: 193.5px;">
												<div class="thumb">
													<img style="height: 7em;width: 10em;" src="<?php echo base_url().'uploads/pdf-preview.jpg';?>"  alt="">

													<div class="caption-overflow">
										<span>
											<i class="icon-plus3"></i>

										</span>
													</div>
												</div>
											</div></a>
									</div>

								<?php }	$i++;}  ?>
						</td> </tr>
				<?php }  ?>

				<tr>
					<th scope="row">Reason</th>
					<td class="text-right"><?php if(htmlspecialchars_decode($reason,ENT_QUOTES)!=''){echo htmlspecialchars_decode($reason,ENT_QUOTES);}else{echo 'NA';}?></td>
				</tr>
				<?php
				if($approve_administrator_id!=0){?>
					<tr>
						<th scope="row">Remarks</th>
						<td class="text-right">
							<?php if(htmlspecialchars_decode($remarks,ENT_QUOTES)!=''){echo htmlspecialchars_decode($remarks,ENT_QUOTES);}else{echo 'NA';}?>
						</td>
					</tr>
					<tr>
						<th scope="row">Status</th>
						<td class="text-right"><?php
							if($status==1){echo '<span class="label label-warning" style="float:right;">Pending</span>';}else if($status==2){echo '<span class="label label-success" style="float:right;">Approved</span>';} else if($status==3){echo '<span class="label label-danger" style="float:right;">Rejected</span>';}?>
						</td>
					</tr>
					<?php if($app_note->approval_status!=1){?>
						<tr>
							<th scope="row"><?php if($status==2){?>Approved<?php } else {?>Rejected<?php }?> Date</th>
							<td class="text-right"><?php
								if($updated_at!=''){echo format_date('d F Y',$updated_at);} else{echo 'NA';}?>
							</td>
						</tr>

						<tr>
							<th scope="row"><?php if($status==2){?>Approved<?php } else {?>Rejected<?php }?> By (Administrator)</th>
							<td class="text-right"><?php
								$admin_name = $this->Xin_model->read_user_info($approve_administrator_id);
								echo change_fletter_caps($admin_name[0]->first_name.' '.$admin_name[0]->middle_name.' '.$admin_name[0]->last_name)
								?>
							</td>
						</tr>
					<?php } ?>
				<?php }
				else if($approval_result){
					foreach($approval_result as $app_note){
						?>
						<tr>
							<th scope="row"><?php echo $app_note->head_of_approval;?> Remarks</th>
							<td class="text-right">
								<?php if(htmlspecialchars_decode($app_note->remarks,ENT_QUOTES)!=''){echo htmlspecialchars_decode($app_note->remarks,ENT_QUOTES);}else{echo 'NA';}?>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php echo $app_note->head_of_approval;?> Status</th>
							<td class="text-right"><?php
								if($app_note->approval_status==1){echo '<span class="label label-warning" style="float:right;">Pending</span>';}else if($app_note->approval_status==2){echo '<span class="label label-success" style="float:right;">Approved</span>';} else if($app_note->approval_status==3){echo '<span class="label label-danger" style="float:right;">Rejected</span>';}?>
							</td>
						</tr>

						<?php if($app_note->approval_status!=1){?>
							<tr>
								<th scope="row"><?php echo $app_note->head_of_approval;?>
									<?php if($app_note->approval_status==2){?>Approved<?php } else {?>Rejected <?php }?> Date</th>

								<td class="text-right"><?php
									if($app_note->approved_date!=''){echo format_date('d F Y',$app_note->approved_date);} else{echo 'NA';}?>
								</td>
							</tr>
							<tr>
								<th scope="row"><?php if($app_note->approval_status==2){?>Approved<?php } else {?>Rejected<?php }?> By (<?php echo $app_note->head_of_approval;?>)</th>
								<td class="text-right"><?php
									$appr_name = $this->Xin_model->read_user_info($app_note->approval_head_id);
									echo change_fletter_caps($appr_name[0]->first_name.' '.$appr_name[0]->middle_name.' '.$appr_name[0]->last_name)
									?>
								</td>
							</tr>
						<?php } ?>
					<?php } }else{?>


					<tr>
						<th scope="row">Status</th>
						<td class="text-right"><?php
							if($status==1){echo '<span class="label label-warning" style="float:right;">Pending</span>';}else if($status==2){echo '<span class="label label-success" style="float:right;">Approved</span>';} else if($status==3){echo '<span class="label label-danger" style="float:right;">Rejected</span>';}?>
						</td>
					</tr>



					<tr>
						<th scope="row">Reporting Manager Remarks</th>
						<td class="text-right">
							<?php if(htmlspecialchars_decode($reporting_manager_remarks,ENT_QUOTES)!=''){echo htmlspecialchars_decode($reporting_manager_remarks,ENT_QUOTES);}else{echo 'NA';}?>
						</td>
					</tr>
					<tr>
						<th scope="row">HR Remarks</th>
						<td class="text-right">
							<?php if(htmlspecialchars_decode($remarks,ENT_QUOTES)!=''){echo htmlspecialchars_decode($remarks,ENT_QUOTES);}else{echo 'NA';}?>
						</td>
					</tr>

				<?php } ?>

				</tbody>
			</table>

		</div>

		<?php
		$check_if_any_secondary_leave= $this->Timesheet_model->check_if_any_secondary_leave($leave_id,$employee_id,$applied_on);
		if($reporting_manager_status==2 && $status==2 ){?>
			<div class="panel panel-flat" style="display: none;">
				<div class="panel-heading">
					<h5 class="panel-title">
						<strong>Leave Salary Paid/UnPaid Status</strong>
					</h5>
				</div>
				<table class="table table-striped m-md-b-0">
					<tbody>


					<?php
					$start_date = new DateTime($from_date);
					$end_date = new DateTime($to_date);
					$end_date = $end_date->modify( '+1 day' );
					$interval_re = new DateInterval('P1D');
					$date_range = new DatePeriod($start_date, $interval_re ,$end_date);
					$first_status='Yet UnPaid';
					foreach($date_range as $date) {
						$attendance_date = $date->format("Y-m-d");
						$query_chk = $this->db->query("SELECT make_payment_id, payment_date FROM `xin_make_payment` WHERE `employee_id` = '".$employee_id."' AND `leave_salary_paid` LIKE '%".$attendance_date."%' limit 1");
						$result_chk = $query_chk->result();
						if($result_chk){
							$first_status='Paid';
						}
					}
					echo '<tr><th scope="row">'.format_date('d F Y',$from_date).' to '.format_date('d F Y',$to_date).'</th><td class="text-right">'.$first_status.'</td></tr>';
					if($check_if_any_secondary_leave){
						foreach($check_if_any_secondary_leave as $secondaryleave){
							$start_dates = new DateTime($secondaryleave->from_date);
							$end_dates = new DateTime($secondaryleave->to_date);
							$end_dates = $end_dates->modify( '+1 day' );
							$interval_res = new DateInterval('P1D');
							$date_ranges = new DatePeriod($start_dates, $interval_res ,$end_dates);
							$second_status='Yet UnPaid';
							foreach($date_ranges as $dates) {
								$attendance_dates = $dates->format("Y-m-d");
								$query_chk1 = $this->db->query("SELECT make_payment_id, payment_date FROM `xin_make_payment` WHERE `employee_id` = '".$employee_id."' AND `leave_salary_paid` LIKE '%".$attendance_dates."%' limit 1");
								$result_chk1 = $query_chk1->result();
								if($result_chk1){
									$second_status='Paid';
								}
							}
							echo '<tr><th scope="row">'.format_date('d F Y',$secondaryleave->from_date).' to '.format_date('d F Y',$secondaryleave->to_date).'</th><td class="text-right">'.$second_status.'</td></tr>';
						}
					}

					?>
					</tbody>
				</table>
			</div>
		<?php } ?>
	</div>

	<?php


	$reporting_manager_access=reporting_manager_access();
	$r_m_status=0;
	if(in_array($employee_id,$reporting_manager_access)){
		$r_m_status=1;
	}
	/*
    $hod_manager_access=hod_manager_access();
    $hod_m_status=0;
    if(in_array($employee_id,$reporting_manager_access)){
    $hod_m_status=1;
    }
    echo $hod_m_status;die;*/

	$chk_approval_query=$this->db->query("select * from xin_employees_approval where type_of_approval='leave_request' AND field_id='".$applied_on."' AND approval_head_id='".$session['user_id']."' AND employee_id='".$employee_id."' limit 1");
	$chk_approval_result=$chk_approval_query->result();
	?>



	<?php if($session['role_name'] == AD_ROLE) {?>
		<div class="col-md-4">
			<div class="panel panel-flat"><form action="<?php echo site_url("timesheet/update_leave_status").'/'.$leave_id;?>" method="post" name="update_status" id="update_status">
					<div class="panel-heading">
						<h5 class="panel-title">
							<strong>Update Status</strong>
						</h5>
					</div>
					<div class="panel-body">
						<input type="hidden" name="_token_status" id="leave_id" value="<?php echo $leave_id;?>">
						<input type="hidden" name="employee_id" id="employee_id" value="<?php echo $employee_id;?>">
						<input type="hidden" name="base_url" id="base_url" value="<?php echo base_url();?>">

						<input type="hidden" name="leave_type" id="leave_type" value="<?php echo $leave_type_id;?>">
						<div class="row">

							<input class="form-control" placeholder="Start Date" type="hidden" name="update_leave_id[]" value="<?php echo $leave_id;?>">
							<?php

							$start = new DateTime($from_date);
							$end = new DateTime($to_date);
							$days = $start->diff($end, true)->days;
							$currentDate = new DateTime($current_date);
							?>

							<label class="col-md-12 text-success"><?php echo $type;?> (<?php echo $leave_status_code;?>) [ <?php echo $days+1;?> Days ]</label>
							<div class="clearfix"></div>
							<div class="col-md-6">
								<div class="form-group">

									<input class="form-control  " placeholder="Start Date" id="start_date" onchange="leave_availability()" readonly name="start_date[]" type="text" value="<?php echo format_date('d F Y',$from_date);?>" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">

									<input class="form-control  " placeholder="End Date" readonly id="end_date" onchange="leave_availability()" name="end_date[]"  type="text" value="<?php echo format_date('d F Y',$to_date);?>" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
								</div>
							</div>
							<div class="clearfix"></div>

						</div>

						<?php

						if($check_if_any_secondary_leave){

							foreach($check_if_any_secondary_leave as $secondaryleave){
								?>

								<div class="row">


									<input class="form-control" placeholder="Start Date" type="hidden" name="update_leave_id[]" value="<?php echo $secondaryleave->leave_id;?>">


									<?php
									$start_d = new DateTime($secondaryleave->from_date);
									$end_d = new DateTime($secondaryleave->to_date);
									$days_d = $start_d->diff($end_d, true)->days;
									$secondary_leave_type = $this->Timesheet_model->read_leave_type_information($secondaryleave->leave_type_id);

									?>

									<label class="col-md-6  text-danger"><?php echo $secondary_leave_type[0]->type_name;?> (<?php echo $secondaryleave->leave_status_code;?>) [ <?php echo $days_d+1;?> Days ]</label>
									<div class="clearfix"></div>
									<div class="col-md-6">
										<div class="form-group">

											<input class="form-control " placeholder="Start Date" readonly name="start_date[]" type="text" value="<?php echo format_date('d F Y',$secondaryleave->from_date);?>">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">

											<input class="form-control " placeholder="End Date" readonly name="end_date[]"  type="text" value="<?php echo format_date('d F Y',$secondaryleave->to_date);?>">
										</div>
									</div>
								</div>
							<?php } } ?>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="status">Status</label>
									<select class="form-control" name="admin_status" data-plugin="select_hrm" data-placeholder="Status" autofocus>
										<option value="1" <?php if($reporting_manager_status=='1' && $status=='1'):?> selected <?php endif; ?>>Pending</option>
										<option value="2" <?php if($reporting_manager_status=='2' && $status=='2'):?> selected <?php endif; ?>>Approve</option>
										<option value="3" <?php if($reporting_manager_status=='3' && $status=='3'):?> selected <?php endif; ?>>Reject</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="remarks">Remarks</label>
									<textarea class="form-control textarea" placeholder="Remarks" name="remarks" cols="30" rows="5"><?php echo $remarks;?></textarea>
								</div>
							</div>
						</div>
						<?php if($reporting_manager_status=='2' && ($currentDate >= $start)){?>
							<button title="Approved Already" type="button" class="btn bg-teal-400 pull-right" disabled>Save</button>
						<?php } else {?>
							<button type="submit" class="btn bg-teal-400 pull-right save">Save</button>
						<?php }?>

					</div></form></div></div>
	<?php }
	else if($chk_approval_result){
		?>
		<div class="col-md-4">
			<div class="panel panel-flat"><form action="<?php echo site_url("timesheet/update_leave_status").'/'.$leave_id;?>" method="post" name="update_status" id="update_status">
					<div class="panel-heading"><h5 class="panel-title">
							<strong>Update Status</strong>
						</h5>
					</div>
					<div class="panel-body">

						<input type="hidden" name="_token_status" id="leave_id" value="<?php echo $leave_id;?>">
						<input type="hidden" name="base_url" id="base_url" value="<?php echo base_url();?>">
						<input type="hidden" name="employee_id" id="employee_id" value="<?php echo $employee_id;?>">
						<input type="hidden" name="leave_type" id="leave_type" value="<?php echo $leave_type_id;?>">
						<div class="row">
							<input class="form-control" placeholder="Start Date" type="hidden" name="update_leave_id[]" value="<?php echo $leave_id;?>">
							<?php
							$start = new DateTime($from_date);
							$end = new DateTime($to_date);
							$days = $start->diff($end, true)->days;
							?>
							<label class="col-md-12 text-success"><?php echo $type;?> (<?php echo $leave_status_code;?>) [ <?php echo $days+1;?> Days ]</label>
							<div class="clearfix"></div>
							<div class="col-md-6">
								<div class="form-group">
									<input class="form-control  " placeholder="Start Date" id="start_date" onchange="leave_availability()" readonly name="start_date[]" type="text" value="<?php echo format_date('d F Y',$from_date);?>" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">

									<input class="form-control  " placeholder="End Date" readonly id="end_date" onchange="leave_availability()" name="end_date[]"  type="text" value="<?php echo format_date('d F Y',$to_date);?>" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
								</div>
							</div>
							<div class="clearfix"></div>

						</div>
						<?php

						if($check_if_any_secondary_leave){

							foreach($check_if_any_secondary_leave as $secondaryleave){
								?>

								<div class="row">


									<input class="form-control" placeholder="Start Date" type="hidden" name="update_leave_id[]" value="<?php echo $secondaryleave->leave_id;?>">


									<?php
									$start_d = new DateTime($secondaryleave->from_date);
									$end_d = new DateTime($secondaryleave->to_date);
									$days_d = $start_d->diff($end_d, true)->days;
									$secondary_leave_type = $this->Timesheet_model->read_leave_type_information($secondaryleave->leave_type_id);
									?>

									<label class="col-md-6  text-danger"><?php echo $secondary_leave_type[0]->type_name;?> (<?php echo $secondaryleave->leave_status_code;?>) [ <?php echo $days_d+1;?> Days ]</label>
									<div class="clearfix"></div>
									<div class="col-md-6">
										<div class="form-group">

											<input class="form-control " placeholder="Start Date" readonly name="start_date[]" type="text" value="<?php echo format_date('d F Y',$secondaryleave->from_date);?>">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">

											<input class="form-control " placeholder="End Date" readonly name="end_date[]"  type="text" value="<?php echo format_date('d F Y',$secondaryleave->to_date);?>">
										</div>
									</div>



								</div>

							<?php } } ?>


						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="status">Status</label>
									<select class="form-control" name="status" data-plugin="select_hrm" data-placeholder="Status" autofocus>
										<option value="1" <?php if($chk_approval_result[0]->approval_status=='1'):?> selected <?php endif; ?>>Pending</option>
										<option value="2" <?php if($chk_approval_result[0]->approval_status=='2'):?> selected <?php endif; ?>>Approve</option>
										<option value="3" <?php if($chk_approval_result[0]->approval_status=='3'):?> selected <?php endif; ?>>Reject</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="remarks">Remarks</label>
									<textarea class="form-control textarea" placeholder="Remarks" name="remarks"  cols="30" rows="5"><?php echo $chk_approval_result[0]->remarks;?></textarea>
								</div>
							</div>
						</div>

						<?php if($chk_approval_result[0]->approval_status=='2'){?>
							<button title="Approved Already" type="button" class="btn bg-teal-400 pull-right" disabled>Save</button>
						<?php } else {?>
							<button type="submit" class="btn bg-teal-400 pull-right save">Save</button>
						<?php }?>


					</div></form></div>
		</div>
	<?php }
	else if(($r_m_status==1) || (in_array('32m',role_resource_ids()))){
		if($r_m_status==1){
			$this->db->query('update xin_employees_approval set approval_head_id="'.$session['user_id'].'" where field_id="'.$applied_on.'" AND employee_id="'.$employee_id.'" AND approval_status=1');
		}else if(in_array('32m',role_resource_ids())){
			$this->db->query('update xin_employees_approval set approval_head_id="'.$session['user_id'].'" where field_id="'.$applied_on.'" AND employee_id="'.$employee_id.'" AND approval_status=1 AND head_of_approval="HR Administrator"');
		}

		$chk_approval_query=$this->db->query("select * from xin_employees_approval where type_of_approval='leave_request' AND field_id='".$applied_on."' AND approval_head_id='".$session['user_id']."' AND employee_id='".$employee_id."' limit 1");
		$chk_approval_result=$chk_approval_query->result();
		if($chk_approval_result){
			?>
			<div class="col-md-4">
				<div class="panel panel-flat"><form action="<?php echo site_url("timesheet/update_leave_status").'/'.$leave_id;?>" method="post" name="update_status" id="update_status">
						<div class="panel-heading"><h5 class="panel-title">
								<strong>Update Status</strong>
							</h5>
						</div>
						<div class="panel-body">

							<input type="hidden" name="_token_status" id="leave_id" value="<?php echo $leave_id;?>">
							<input type="hidden" name="employee_id" id="employee_id" value="<?php echo $employee_id;?>">
							<input type="hidden" name="leave_type" id="leave_type" value="<?php echo $leave_type_id;?>">
							<input type="hidden" name="base_url" id="base_url" value="<?php echo base_url();?>">
							<div class="row">

								<input class="form-control" placeholder="Start Date" type="hidden" name="update_leave_id[]" value="<?php echo $leave_id;?>">
								<?php
								$start = new DateTime($from_date);
								$end = new DateTime($to_date);
								$days = $start->diff($end, true)->days;

								?>

								<label class="col-md-12 text-success"><?php echo $type;?> (<?php echo $leave_status_code;?>) [ <?php echo $days+1;?> Days ]</label>
								<div class="clearfix"></div>
								<div class="col-md-6">
									<div class="form-group">

										<input class="form-control  " placeholder="Start Date" id="start_date" onchange="leave_availability()" readonly name="start_date[]" type="text" value="<?php echo format_date('d F Y',$from_date);?>" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">

										<input class="form-control  " placeholder="End Date" readonly id="end_date" onchange="leave_availability()" name="end_date[]"  type="text" value="<?php echo format_date('d F Y',$to_date);?>" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
									</div>
								</div>
								<div class="clearfix"></div>

							</div>
							<?php

							if($check_if_any_secondary_leave){

								foreach($check_if_any_secondary_leave as $secondaryleave){
									?>

									<div class="row">


										<input class="form-control" placeholder="Start Date" type="hidden" name="update_leave_id[]" value="<?php echo $secondaryleave->leave_id;?>">


										<?php
										$start_d = new DateTime($secondaryleave->from_date);
										$end_d = new DateTime($secondaryleave->to_date);
										$days_d = $start_d->diff($end_d, true)->days;
										$secondary_leave_type = $this->Timesheet_model->read_leave_type_information($secondaryleave->leave_type_id);
										?>

										<label class="col-md-6  text-danger"><?php echo $secondary_leave_type[0]->type_name;?> (<?php echo $secondaryleave->leave_status_code;?>) [ <?php echo $days_d+1;?> Days ]</label>
										<div class="clearfix"></div>
										<div class="col-md-6">
											<div class="form-group">

												<input class="form-control " placeholder="Start Date" readonly name="start_date[]" type="text" value="<?php echo format_date('d F Y',$secondaryleave->from_date);?>">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">

												<input class="form-control " placeholder="End Date" readonly name="end_date[]"  type="text" value="<?php echo format_date('d F Y',$secondaryleave->to_date);?>">
											</div>
										</div>



									</div>

								<?php } } ?>


							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="status">Status</label>
										<select class="form-control" name="status" data-plugin="select_hrm" data-placeholder="Status" autofocus>
											<option value="1" <?php if($chk_approval_result[0]->approval_status=='1'):?> selected <?php endif; ?>>Pending</option>
											<option value="2" <?php if($chk_approval_result[0]->approval_status=='2'):?> selected <?php endif; ?>>Approve</option>
											<option value="3" <?php if($chk_approval_result[0]->approval_status=='3'):?> selected <?php endif; ?>>Reject</option>
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="remarks">Remarks</label>
										<textarea class="form-control textarea" placeholder="Remarks" name="remarks"  cols="30" rows="5"><?php echo $chk_approval_result[0]->remarks;?></textarea>
									</div>
								</div>
							</div>
							<?php if($chk_approval_result[0]->approval_status=='2'){?>
								<button title="Approved Already" type="button" class="btn bg-teal-400 pull-right" disabled>Save</button>
							<?php } else {?>
								<button type="submit" class="btn bg-teal-400 pull-right save">Save</button>
							<?php }?>

						</div></form></div>

			</div>
		<?php }else {?>

		<?php }?>
	<?php } ?>

	<div class="col-md-4">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title">

					<strong>Leave Statistics</strong> of <?php echo change_fletter_caps($first_name.' '.$middle_name.' '.$last_name);?>

				</h5>

			</div>
			<div class="panel-body">
				<?php $count_leaves = $this->Timesheet_model->count_total_leaves($leave_type_id,$employee_id,$all_leave_types);
				if($count_leaves){
					$check_start_date='';
					$l_c=0;
					$avail_leave=0;
					$avail_count=0;
					$balance_leave=0;
					foreach($count_leaves as $leaves_stat){
						$count_data = $leaves_stat['counts'] / $leaves_stat['available_leave'] * 100;
						if($count_data <= 20) {
							$progress_class = 'progress-bar-success';
						} else if($count_data > 20 && $count_data <= 50){
							$progress_class = 'progress-bar-info';
						} else if($count_data > 50 && $count_data <= 75){
							$progress_class = 'progress-bar-warning';
						} else {
							$progress_class = 'progress-bar-danger';
						}
						?>
						<div id="leave-statistics" >
							<?php
							$start_year_date=$leaves_stat['start_year_date'];
							$end_year_date=$leaves_stat['end_year_date'];
							if(strtotime($check_start_date)!=strtotime($start_year_date)){
								//echo "<hr>";
								$check_start_date=$start_year_date;
								if(strtotime($current_date) <= strtotime($end_year_date)){
									echo "<p class='text-success'><strong>Current Year</strong> (".date('d F Y',strtotime($start_year_date))." To ".date('d F Y',strtotime($end_year_date)).")</p>";
									echo "<hr>";
								}/*else{
				   echo "<p class='text-danger'><strong>Last Year</strong> (".date('d F Y',strtotime($start_year_date))." To ".date('d F Y',strtotime($end_year_date)).")</p>";
				 }*/
								//echo "<hr>";
							}
							if(strtotime($current_date) <= strtotime($end_year_date)){
								if($leaves_stat['leave_type']=='Annual Leave'){
									$leave_type=$leaves_stat['leave_type'];
									$leave_counts=$leaves_stat['counts'];
									if($l_c==0){
										$leaves_stats=$leaves_stat['available_leave'];
										$balance_leave=$leaves_stats-$leave_counts;
									}else{
										$leaves_stats=($leaves_stat['available_leave']+$balance_leave);
										$balance_leave=$leaves_stats-$leave_counts;
									}
									$l_c++;
								}else{
									$leave_type=$leaves_stat['leave_type'];
									$leave_counts=$leaves_stat['counts'];
									$leaves_stats=$leaves_stat['available_leave'];
								}
								?>
								<p><strong><?php echo $leave_type;?> (<?php echo $leave_counts;?><?php
										//echo $leaves_stats; ?>)</strong></p>
								<div class="progress content-group-sm">
									<div class="progress-bar <?php echo $progress_class;?> progress-bar-striped" style="width: <?php echo $count_data;?>%" max="<?php echo $count_data;?>">

									</div>
								</div>
							<?php } ?>
						</div>
					<?php    }
				}?>

			</div></div>
	</div>
</div>
<div id="sh_message_dialog"></div>
<div id="json_message"></div>
