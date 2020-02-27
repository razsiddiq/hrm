<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['exit_id']) && $_GET['data']=='exit'){
	

?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Employee Exit</h4>
</div>
<?php if(in_array('27e',role_resource_ids())) {?>
<form class="m-b-1" action="<?php echo site_url("employee_exit/update").'/'.$exit_id; ?>" method="post" name="edit_exit" id="edit_exit">
<?php } ?>
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $exit_id;?>">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="employee">Employee to Exit</label>
          <select name="employee_id" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="Choose an Employee...">
            <option value=""></option>
            <?php foreach($all_employees as $employee) {?>
            <option value="<?php echo $employee->user_id;?>" <?php if($employee->user_id==$employee_id):?> selected="selected"<?php endif;?>> <?php echo change_fletter_caps($employee->first_name.' '.$employee->middle_name.' '.$employee->last_name);?></option>
            <?php } ?>
          </select>
        </div>
    



      </div>

  <div class="col-md-6">
            <div class="form-group">
              <label for="type">Type of Exit</label>
              <select class="select2" data-plugin="select_hrm" data-placeholder="Type of Exit" name="type">
                <option value=""></option>
                <?php foreach($all_exit_types as $exit_type) {?>
                <option value="<?php echo $exit_type->type_id?>" <?php if($exit_type->type_id==$exit_type_id):?> selected="selected"<?php endif;?>><?php echo $exit_type->type_name;?></option>
                <?php } ?>
              </select>
            </div>
          </div>
      <div class="col-md-12">
        <div class="form-group">
          <label for="description">Description</label>
          <textarea class="form-control textarea" placeholder="Reason" name="reason" cols="30" rows="10" id="reason2"><?php echo html_entity_decode(stripcslashes($reason));?></textarea>
        </div>
      </div>
    </div>
  </div>
  <div class="clearfix"></div>
					
					
					<div class="row">
					<div class="">
							

								<div class="panel-body">
							
									
									
									<div class="panel panel-flat">
							

								<div class="panel-body">
									<div class="tabbable tab-content-bordered">
										<ul class="nav nav-tabs nav-tabs-highlight nav-justified">
											<li class="<?php if($_GET['exit_type']=='final_settlement'){echo 'active';}?> "><a href="#top-justified-tab1" data-toggle="tab" aria-expanded="true">Final Settlement</a></li>
											<li class="<?php if($_GET['exit_type']=='clearance_form'){echo 'active';}?> "><a href="#top-justified-tab2" data-toggle="tab" aria-expanded="false">Clearance Form</a></li>
											
										</ul>

										<div class="tab-content">
											<div class="tab-pane has-padding <?php if($_GET['exit_type']=='final_settlement'){echo 'active';}?>" id="top-justified-tab1">


<?php 
$query_final=$this->db->query("select final_settlement from xin_employee_exit where employee_id='".$employee_id."' AND approval_form='Final Settlement'");
$result_final=$query_final->result();
$final_settlement=json_decode($result_final[0]->final_settlement);

																						
if($result_final){
	
	$query1=$this->db->query('select * from xin_employees_approval where employee_id="'.$employee_id.'" AND type_of_approval="Final Settlement" AND field_id="'.$_GET['exit_id'].'"');
		$result1=$query1->result();
		$html1='';
			if($result1){
			$html1.='<table class="table table-md"><tbody>
		    <tr class="bg-slate-600 text-center"><td colspan="3">Final Settlement Status</td></tr>';		
			foreach($result1 as $approv_st){
			
			if($approv_st->approved_date!=''){$approved_date=format_date('d F Y',$approv_st->approved_date);}else{$approved_date= '-';}
				if($approv_st->approval_status==0){$approval_status='<span class="badge bg-info">Waiting for approval</span>';}else if($approv_st->approval_status==1){					
					$approval_status= '<span class="badge bg-success">Approved</span>';}else if($approv_st->approval_status==2){$approval_status= '<span class="badge bg-danger">Declined</span>';}
				$html1.='<tr>
					<td>'.$approv_st->head_of_approval.'</td>
					<td>'.$approved_date.'</td>
					<td>'.$approval_status.'</td>    </tr>  ';
			}			
		     $html1.='</tbody></table>';	
		    }
			
			$ot_hours_amount=json_decode($final_settlement->ot_hours_amount);
			
			$ot_day_cal_amount=$ot_hours_amount->ot_day_amount;
			$ot_night_cal_amount=$ot_hours_amount->ot_night_amount;
			$ot_holiday_cal_amount=$ot_hours_amount->ot_holiday_amount;
			
?>		
		<!--<div style="padding: 11px 1px;cursor: pointer;"><i class="icon-printer" style="color:#249E92;font-size:2em;" id="btnPrint1"></i></div>-->
		<div class="panel panel-flat" id="dvContainer1">
		<div class="panel-heading text-center"><h5 class="panel-title">		
		<?php echo $html1;?>
		<strong>Final Settlement Computation</strong></h5><div class="">Date: <?php echo date('d F Y',strtotime($created_at));?></div>	</div>
		<div class="panel-body">
		<div class="col-lg-6 table-responsive" style="min-height: 36em;"><table class="table table-md"><tbody>
		<tr class="bg-slate-600 text-center"><td colspan="3">Basic Details</td></tr>
		<tr class="success"><td>Employee Code</td><td>:</td><td><?php echo $final_settlement->employee_code;?></td></tr>	
		<tr class=""><td>Employee Name</td><td>:</td><td><?php echo $final_settlement->employee_name;?></td></tr>	
		<tr class="danger"><td>Designation</td><td>:</td><td><?php echo $final_settlement->employee_designation;?></td></tr>
		<tr class=""><td>Entity/Agency</td><td>:</td><td><?php echo $final_settlement->entity_agency;?></td></tr>
		<tr class="warning"><td>Date of Join</td><td>:</td><td><?php echo date('d F Y',strtotime($final_settlement->date_of_joining));?></td></tr>
		<tr class=""><td>Contract Date</td><td>:</td><td><?php echo $final_settlement->contract_date;?></td></tr>
		<tr class="info"><td>Last Working Day</td><td>:</td><td><?php echo $final_settlement->last_working_day;?></td></tr>
		<tr class=""><td>Type of Seperation</td><td>:</td><td><?php echo $final_settlement->type_of_separtion;?></td></tr>		
		</tbody></table></div>


		<div class="col-lg-6 table-responsive" style="min-height: 36em;"><table class="table table-md"><tbody>
		<tr class="bg-slate-600 text-center"><td colspan="3">Years of Work & Leave Balance</td></tr>	
		<tr class=""><td>Number of Days worked</td><td>:</td><td><?php echo $final_settlement->no_of_days_worked;?></td></tr>	
		<tr class="danger"><td>Number of Years worked</td><td>:</td><td><?php echo $final_settlement->no_of_years_worked;?></td></tr>
		<tr class=""><td>No of Absence</td><td>:</td><td><?php echo $final_settlement->no_of_absence;?></td></tr>
		<tr class="warning"><td>Net Total Years worked</td><td>:</td><td><?php echo $final_settlement->net_total_years_worked;?></td></tr>
		<tr class=""><td>Total Leave Accrued</td><td>:</td><td><?php echo $final_settlement->total_leave_accrued;?></td></tr>
		<tr class="info"><td>Leave Availed</td><td>:</td><td><?php echo $final_settlement->leave_availed;?></td></tr>
		<tr class=""><td>Balance Leave </td><td>:</td><td><?php echo $final_settlement->balance_leave;?></td></tr>		
		</tbody></table></div>	
		<div class="col-lg-6 table-responsive" style="min-height: 36em;"><table class="table table-md"><tbody>
		<tr class="bg-slate-600 text-center"><td colspan="3">Monthly Salary</td></tr>	
		<tr class=""><td>Basic Salary</td><td>:</td><td><?php echo $final_settlement->basic_salary;?></td></tr>
        <tr class="danger"><td>House Rent Allowance</td><td>:</td><td><?php echo $final_settlement->house_rent_allowance;?></td></tr>
		<tr class=""><td>Transp. Allowance</td><td>:</td><td><?php echo $final_settlement->transportation;?></td></tr>
		<tr class="warning"><td>Food Allowance</td><td>:</td><td><?php echo $final_settlement->food_allowance;?></td></tr>
		<tr class=""><td>Other Allowance</td><td>:</td><td><?php echo $final_settlement->other_allowance;?></td></tr>
		<tr class="info"><td>Additional Benefit</td><td>:</td><td><?php echo $final_settlement->additional_benefits;?></td></tr>
		<tr class=""><td>Bonus</td><td>:</td><td><?php echo $final_settlement->bonus;?></td></tr>
		<tr class="success"><td>Total Salary </td><td>:</td><td><?php echo $final_settlement->total_salary;?></td></tr>			
		</tbody></table></div>
		
		
		<div class="col-lg-6 table-responsive" style="min-height: 36em;"><table class="table table-md"><tbody>
		<tr class="bg-slate-600 text-center"><td colspan="3">Working Hours & Over Time</td></tr>	
	<tr class="danger"><td>Normal Working Hours</td><td>:</td><td><?php echo $final_settlement->normal_working_hours;?></td></tr>	
		<tr class=""><td>Working Hours for the month</td><td>:</td><td><?php echo $final_settlement->working_hours_for_the_month;?></td></tr>
		<tr class="warning"><td>Actual Working Hours</td><td>:</td><td><?php echo round($final_settlement->actual_working_hour,2);?></td></tr>
		<tr class=""><td>OT Hours @ 1.25</td><td>:</td><td><?php echo $final_settlement->ot_day_hours;?></td></tr>
		<tr class="success"><td>OT Hours @ 1.5</td><td>:</td><td><?php echo $final_settlement->ot_night_hours;?></td></tr>
		<tr class=""><td>OT Hours @ PH</td><td>:</td><td><?php echo $final_settlement->ot_holiday_hours;?></td></tr>	
		</tbody></table></div>
		<div class="col-lg-12 table-responsive" style="min-height: 36em;"><table class="table table-md"><tbody>
		<tr class="bg-slate-600"><td>Elements</td><td>Days</td><td>Amount</td></tr>	
		<tr class=""><td colspan="3"><b>Earnings</b></td></tr>	
		<tr class=""><td>Current Month Salary - <?php echo date('F',strtotime($final_settlement->current_month_salary));?></td><td><?php echo round($final_settlement->total_days_of_the_month,2);?></td><td><?php echo change_num_format($final_settlement->salary_of_the_month);?></td></tr>
<tr class=""><td>Leave Salary</td><td><?php echo $final_settlement->leave_salary_days;?></td><td><?php echo $final_settlement->leave_salary;?></td></tr>
<tr class=""><td>OT  Amount @ 1.25</td><td></td><td><?php echo 
change_num_format($ot_day_cal_amount);?></td></tr>
<tr class=""><td>OT Amount @ 1.5</td><td></td><td><?php echo change_num_format($ot_night_cal_amount);?></td></tr>
<tr class=""><td>OT  Amount@ PH</td><td></td><td><?php echo change_num_format($ot_holiday_cal_amount);?></td></tr>



<?php if($final_settlement->parent_type_name){
	$i=0;
	foreach($final_settlement->parent_type_name as $earnings){
		if($earnings=='Addition'){
	?>
<tr class=""><td><?php echo $final_settlement->child_type_name[$i];?></td><td><?php echo $final_settlement->salary_comments[$i];?></td><td><?php echo $final_settlement->amount[$i];?></td></tr>
		<?php } $i++;}  }?>

<tr class=""><td>Total Earnings</td><td></td><td><?php echo change_num_format($final_settlement->total_earnings);?></td></tr>
<tr class=""><td colspan="3" ><b>Deductions</b></td></tr>

<?php if($final_settlement->parent_type_name){
	$j=0;
	foreach($final_settlement->parent_type_name as $deductions){
		
		if($deductions=='Deduction'){
	?>
<tr class=""><td><?php echo $final_settlement->child_type_name[$j];?></td><td><?php echo $final_settlement->salary_comments[$j];?></td><td><?php echo $final_settlement->amount[$j];?></td></tr>
		<?php } $j++;}  }?>
		
<tr class=""><td>Total Deductions</td><td></td><td><?php echo $final_settlement->total_deductions;?></td></tr>

<?php if($final_settlement->parent_type_name){
	$k=0;
	foreach($final_settlement->parent_type_name as $perp){
		if($perp=='Perpetual'){
	?>
<tr class=""><td><?php echo $final_settlement->child_type_name[$k];?></td><td><?php echo $final_settlement->salary_comments[$k];?></td><td><?php echo round($final_settlement->amount[$k],2);?></td></tr>
		<?php } $k++;}  }?>
		<?php if($final_settlement->parent_type_name){
	$l=0;
	foreach($final_settlement->parent_type_name as $nonperp){
		if($nonperp=='Non-Perpetual'){
	?>
<tr class=""><td><?php echo $final_settlement->child_type_name[$l];?></td><td><?php echo $final_settlement->salary_comments[$l];?></td><td><?php echo round($final_settlement->amount[$l],2);?></td></tr>
		<?php } $l++;}  }?>
		
<tr class=""><td>Net Payable</td><td></td><td><?php echo change_num_format($final_settlement->net_payable);?></td></tr>
<tr class=""><td>Payment Amount to Employee</td><td></td><td><?php echo change_num_format($final_settlement->payment_amount_to_employee);?></td></tr>

		</tbody></table></div>
		
		</div></div>
																					
<?php }else {?>
N/A

<?php } ?>				
											</div>

											<div class="tab-pane has-padding <?php if($_GET['exit_type']=='clearance_form'){echo 'active';}?> " id="top-justified-tab2">
											
											
<?php 
																						
$query_clearance=$this->db->query("select created_at,final_settlement from xin_employee_exit where employee_id='".$employee_id."' AND approval_form='Clearance Form'");
$result_clearance=$query_clearance->result();

$clearance_settlement=json_decode($result_clearance[0]->final_settlement);

																				
if($result_clearance){
	
	    $query1=$this->db->query('select * from xin_employees_approval where employee_id="'.$employee_id.'" AND type_of_approval="Clearance Form"');
		$result1=$query1->result();
		
		
		$html2='';
			if($result1){
			$html2.='<table class="table table-md"><tbody>
		    <tr class="bg-slate-600 text-center"><td colspan="3">Clearance Form Status</td></tr>';		
			foreach($result1 as $approv_st){
			
			if($approv_st->approved_date!=''){$approved_date=format_date('d F Y',$approv_st->approved_date);}else{$approved_date= '-';}
				if($approv_st->approval_status==0){$approval_status='<span class="badge bg-info">Waiting for approval</span>';}else if($approv_st->approval_status==1){					
					$approval_status= '<span class="badge bg-success">Approved</span>';}else if($approv_st->approval_status==2){$approval_status= '<span class="badge bg-danger">Declined</span>';}
				$html2.='<tr>
					<td>'.$approv_st->head_of_approval.'</td>
					<td>'.$approved_date.'</td>
					<td>'.$approval_status.'</td>    </tr>  ';
			}			
		     $html2.='</tbody></table>';	
		    }																							
																						
       
			
		$html2.='<div class="clearfix"></div><div class="panel panel-flat" id="dvContainer"><div class="panel-heading text-center"><h5 class="panel-title">					 
		<strong>Clearance Form<br>(Employee/Contract)</strong></h5><div class="pull-right mr-10">Date Processed: '.date('d F Y',strtotime($result_clearance[0]->created_at)).'</div>	</div>';
		$html2.='<div class="panel-body">';
		
		$html2.='<div class="col-lg-12 successtable-responsive"	><table class=" bg-teal-300 table table-md table-bordered "><tbody>
	
	
		<tr class=""><td>Employee Name</td><td>'.$clearance_settlement->employee_name.'</td><td>Visa</td><td>'.$clearance_settlement->entity_agency.'</td></tr>
			
		<tr class=""><td>Designation</td><td>'.$clearance_settlement->employee_designation.'</td><td>Department</td><td>'.$clearance_settlement->employee_department.'</td></tr>
		
		<tr class=""><td>Joining Date</td><td>'.format_date('d F Y',$clearance_settlement->joining_date).'</td><td>Resignation Date</td><td>'.format_date('d F Y',$clearance_settlement->resignation_date).'</td></tr>
		<tr class=""><td>Last Working Day</td><td>'.format_date('d F Y',$clearance_settlement->last_working_day).'</td><td></td><td></td></tr>	
		
		<tr class=""><td>Nature of Cessation of Employement<br> **pls.check applicable box**</td><td colspan="3">
		<table class="table table-md table-bordered ">
			
		<tr><div class="form-group">
							
										<label class="radio-inline radio-right">
											<input name="cessation_of_employement" checked type="radio" value="Resignation">
											'.$clearance_settlement->cessation_of_employement.'
										</label>';

						if($clearance_settlement->cessation_of_employement_other!=''){				
			$html2.='<input  class="mt-10 form-input text-teal" name="cessation_of_employement_other" type="text"  value="'.$clearance_settlement->cessation_of_employement_other.'">';
						}				
										
									$html2.='</div></tr>
								
									
	
		</table>
		
	
		</td></tr>
		<tr><td colspan="3">Persons in charge are required to sign in the respective sections and/or department below:<td><tr>';		
		$html2.='</tbody></table></div>';
			
		
		
		$html2.='<div class="mt-10 col-lg-6 table-responsive" ><table class="table bg-teal-300 table-bordered  table-md"><tbody>
		<tr class=""><td colspan="3"><strong>1.Own Department\'s Clearance</strong></td></tr>	
		<tr class=""><td>Bitrix Account</td><td>'.$clearance_settlement->department_bitrix_account.'</td></tr>
<tr class=""><td>Awok Logistics System (ALS)</td><td>'.$clearance_settlement->department_awok_logistics_system.'</td></tr>
<tr class=""><td>RTA Fine</td><td>'.$clearance_settlement->department_rta_fine.'</td></tr>
<tr class=""><td>Vehicle Damage / Fines</td><td>'.$clearance_settlement->department_vehicle_damage_fines.'</td></tr>
<tr class=""><td>Etisalat Bills Outstanding</td><td>'.$clearance_settlement->department_etisalat_bills_outstanding.'</td></tr>
<tr class=""><td>Remarks</td><td>'.$clearance_settlement->department_remarks.'</td></tr>
<tr class=""><td>Name</td><td>'.$clearance_settlement->department_name.'</td></tr>
<tr class=""><td>Signature</td><td><img style="width: 100%;" src="'.$clearance_settlement->department_signature.'"/></td></tr>		
	<tr><td>Date</td><td>'.$clearance_settlement->department_date.'</td></tr>	
		</tbody></table></div>';
		
		
		$html2.='<div class="mt-10 col-lg-6 table-responsive" ><table class="table bg-teal-300 table-bordered  table-md"><tbody>
		<tr class=""><td colspan="3"><strong>2.IT Clearance</strong></td></tr>	
		<tr class=""><td>Bitrix</td><td>'.$clearance_settlement->it_bitrix.'</td></tr>
<tr class=""><td>Email</td><td>'.$clearance_settlement->it_email.'</td></tr>
<tr class=""><td>Skype (if official)</td><td>'.$clearance_settlement->it_skype.'</td></tr>
<tr class=""><td>1c (if applicable)</td><td>'.$clearance_settlement->it_ic.'</td></tr>
<tr class=""><td>Awok.com Access</td><td>'.$clearance_settlement->it_awok_com_access.'</td></tr>
<tr class=""><td>Other/s (pls specify)</td><td>'.$clearance_settlement->it_other_specify.'</td></tr>
<tr class=""><td>Desktop</td><td>'.$clearance_settlement->it_desktop.'</td></tr>
<tr class=""><td>Laptop</td><td>'.$clearance_settlement->it_laptop.'</td></tr>
<tr class=""><td>Mouse</td><td>'.$clearance_settlement->it_mouse.'</td></tr>
<tr class=""><td>Headset</td><td>'.$clearance_settlement->it_headset.'</td></tr>
<tr class=""><td>Keyboard</td><td>'.$clearance_settlement->it_keyboard.'</td></tr>
<tr class=""><td>Remarks</td><td>'.$clearance_settlement->it_remarks.'</td></tr>
<tr class=""><td>Name</td><td>'.$clearance_settlement->it_name.'</td></tr>
<tr class=""><td>Signature</td><td><img style="width: 100%;" src="'.$clearance_settlement->it_signature.'"/></td></tr>		
	<tr><td>Date</td><td>'.$clearance_settlement->it_date.'</td></tr>	
		</tbody></table></div> <div class="clear-fix"></div>';
		
		
		
		
		
		$html2.='<div class="mt-10 col-lg-6 table-responsive" ><table class="table bg-teal-300 table-bordered  table-md"><tbody>
		<tr class=""><td colspan="3"><strong>3.HR Clearance</strong></td></tr>	
		<tr class=""><td>Labour Card</td><td>'.$clearance_settlement->hr_labour_card.'</td></tr>
		<tr class=""><td>Emirates Card</td><td>'.$clearance_settlement->hr_emirates_card.'</td></tr>
		<tr class=""><td>Medical Card</td><td>'.$clearance_settlement->hr_medical_card.'</td></tr>
		<tr class=""><td>Exit Interview</td><td>'.$clearance_settlement->hr_exit_interview.'</td></tr>

<tr class=""><td>Remarks</td><td>'.$clearance_settlement->hr_remarks.'</td></tr>
<tr class=""><td>Name</td><td>'.$clearance_settlement->hr_name.'</td></tr>
<tr class=""><td>Signature</td><td><img style="width: 100%;" src="'.$clearance_settlement->hr_signature.'"/></td></tr>		
	<tr><td>Date</td><td>'.$clearance_settlement->hr_date.'</td></tr>	
		</tbody></table></div>';
		
			$html2.='<div class="mt-10 col-lg-6 table-responsive" ><table class="table bg-teal-300 table-bordered  table-md"><tbody>
		<tr class=""><td colspan="3"><strong>4.Account\'s Clearance</strong></td></tr>	
		<tr class=""><td>Claims Settlement (1511)</td><td>'.$clearance_settlement->account_claims_settlement_1511.'</td></tr>
		<tr class=""><td>Advance to Employee for company purpose(3630) <br> 3630.01(AED)/3630.02(AED) (other currency)</td><td>'.$clearance_settlement->account_advance_to_employee_for_company_purpose.'</td></tr>
		<tr class=""><td>Settlement with Personnel On Payment.(3520)</td><td>'.$clearance_settlement->account_settlement_with_personnel_on_payment_3520.'</td></tr>
		<tr class=""><td>Settlement with Personnel Outsources.(3523)</td><td>'.$clearance_settlement->account_settlement_with_personnel_outsources_3523.'</td></tr>
<tr class=""><td>Other/s (if any)</td><td>'.$clearance_settlement->account_other_specify.'</td></tr>
<tr class=""><td>Total Amount Payable</td><td>'.$clearance_settlement->account_total_amount_payable.'</td></tr>

<tr class=""><td>Prepared By</td><td>'.$clearance_settlement->account_prepardby.'</td></tr>
<tr class=""><td>Checked By</td><td>'.$clearance_settlement->account_checkedby.'</td></tr>

<tr class=""><td>Remarks</td><td>'.$clearance_settlement->account_remarks.'</td></tr>
<tr class=""><td>Name</td><td>'.$clearance_settlement->account_name.'</td></tr>
<tr class=""><td>Signature</td><td><img style="width: 100%;" src="'.$clearance_settlement->account_signature.'"/></td></tr>		
	<tr><td>Date</td><td>'.$clearance_settlement->account_date.'</td></tr>	
		</tbody></table></div>';
		
			echo $html2;
}else{
	
	echo 'N/A';
}
?>






											</div>

											
										</div>
									</div>
								</div>
							</div>
								</div>
							</div>
					</div>
					
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<?php if(in_array('27e',role_resource_ids())) {?>
    <button type="submit" class="btn bg-teal-400">Update</button>
	<?php } ?>
  </div>
</form>

<script type="text/javascript">
 $(document).ready(function(){
	 $('body').on('click', '#btnPrint1', function(){		
			 var printContents = document.getElementById('dvContainer1').innerHTML;
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
		
		$.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        }
    });
	 var xin_table = $('#xin_table').DataTable({
		"bDestroy": true,
		"ajax": {
			url : "<?php echo site_url("employee_exit/exit_list") ?>",
			type : 'GET'
		},"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		},	
            buttons: [
                {
                    extend: 'copyHtml5',
                    className: 'btn btn-default',
					 exportOptions: {
                        columns: [ 0, ':visible' ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    className: 'btn btn-default',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    className: 'btn btn-default',
                    exportOptions: {
                        columns: ':visible'// [ 1, 2, 3, 4, 5, 6, 7]
                    }
                },
				{
                extend: 'print',
                text: '<i class="icon-printer position-left"></i> Print table',
                className: 'btn btn-default',
                exportOptions: {
                    columns: ':visible'
                }
                },
                {
                    extend: 'colvis',
                    text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
                    className: 'btn bg-teal-400 btn-icon'
                }
            ]
        
    });
	
	
	// Add placeholder to the datatable filter option
    $('.dataTables_filter input[type=search]').attr('placeholder','Type to filter...');


    // Enable Select2 select for the length option
    $('.dataTables_length select,.change_country_code').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
	
    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));	

				 
		
		$('#reason2').summernote({
		  height: 160,
		  minHeight: null,
		  maxHeight: null,
		  focus: false,
		  dialogsInBody: true,
callbacks: {
onImageUpload: function(files) {
var $editor = $(this);
var data = new FormData();
data.append('file', files[0]);
sendFile($editor,data);
},
onImageUploadError: null
}
		});
		$('.note-children-container').hide();
$('.d_date').pickadate({
	format: "dd mmmm yyyy",
		labelMonthNext: 'Go to the next month',
        labelMonthPrev: 'Go to the previous month',
        labelMonthSelect: 'Pick a month from the dropdown',
        labelYearSelect: 'Pick a year from the dropdown',
        selectMonths: true,
        selectYears: 100,
		});

		/* Edit data */
		$("#edit_exit").submit(function(e){
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
			//var reason = $("#reason2").code();
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=1&edit_type=exit&form="+action,//+"&reason="+reason,
				cache: false,
				success: function (JSON) {
					if (JSON.error != '') {
						toastr.error(JSON.error);
						$('.save').prop('disabled', false);
					} else {
						xin_table.ajax.reload(function(){ 
							toastr.success(JSON.result);
						}, true);
						$('.edit-modal-data').modal('toggle');
						$('.save').prop('disabled', false);
					}
				}
			});
		});
	});	
  </script>
<?php } else if(isset($_GET['jd']) && isset($_GET['exit_id']) && $_GET['data']=='view_exit'){
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">View Employee Exit</h4>
</div>
<form class="m-b-1">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="employee">Employee to Exit</label>
          <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php foreach($all_employees as $employee) {?><?php if($employee_id==$employee->user_id):?><?php echo change_fletter_caps($employee->first_name.' '.$employee->middle_name.' '.$employee->last_name);?><?php endif;?><?php } ?>">
        </div>
     
 
      </div>
	  
	     <div class="col-md-6">
            <div class="form-group">
              <label for="type">Type of Exit</label>
              <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php foreach($all_exit_types as $exit_type) {?><?php if($exit_type_id==$exit_type->type_id):?><?php echo $exit_type->type_name;?><?php endif;?><?php } ?>">
            </div>
          </div>
      <div class="col-md-12">
        <div class="form-group">
          <label for="description">Description</label><br />
         <div class="text_area_p"><div class="embed-responsive"><?php echo html_entity_decode(stripcslashes($reason));?></div></div>
        </div>
      </div>
    </div>
  </div><div class="clearfix"></div>
					
					
					<div class="row">
					<div class="">
								<!--<div panel panel-flat class="panel-heading">
									
									<div class="heading-elements">
									
				                	</div>
								</div>-->
	<div class="panel-body">
									<div class="tabbable tab-content-bordered">
										<ul class="nav nav-tabs nav-tabs-highlight nav-justified">
											<li class="<?php if($_GET['exit_type']=='final_settlement'){echo 'active';}?> "><a href="#top-justified-tab3" data-toggle="tab" aria-expanded="true">Final Settlement</a></li>
											<li class="<?php if($_GET['exit_type']=='clearance_form'){echo 'active';}?> "><a href="#top-justified-tab4" data-toggle="tab" aria-expanded="false">Clearance Form</a></li>
											
										</ul>

										<div class="tab-content">
											<div class="tab-pane has-padding <?php if($_GET['exit_type']=='final_settlement'){echo 'active';}?>" id="top-justified-tab3">


<?php 
$query_final=$this->db->query("select final_settlement from xin_employee_exit where employee_id='".$employee_id."' AND approval_form='Final Settlement'");
$result_final=$query_final->result();
$final_settlement=json_decode($result_final[0]->final_settlement);

																						
if($result_final){
	
	$query1=$this->db->query('select * from xin_employees_approval where employee_id="'.$employee_id.'" AND type_of_approval="Final Settlement" AND field_id="'.$_GET['exit_id'].'"');
		$result1=$query1->result();
		$html1='';
			if($result1){
			$html1.='<table class="table table-md"><tbody>
		    <tr class="bg-slate-600 text-center"><td colspan="3">Final Settlement Status</td></tr>';		
			foreach($result1 as $approv_st){
			
			if($approv_st->approved_date!=''){$approved_date=format_date('d F Y',$approv_st->approved_date);}else{$approved_date= '-';}
				if($approv_st->approval_status==0){$approval_status='<span class="badge bg-info">Waiting for approval</span>';}else if($approv_st->approval_status==1){					
					$approval_status= '<span class="badge bg-success">Approved</span>';}else if($approv_st->approval_status==2){$approval_status= '<span class="badge bg-danger">Declined</span>';}
				$html1.='<tr>
					<td>'.$approv_st->head_of_approval.'</td>
					<td>'.$approved_date.'</td>
					<td>'.$approval_status.'</td>    </tr>  ';
			}			
		     $html1.='</tbody></table>';	
		    }
			$ot_hours_amount=json_decode($final_settlement->ot_hours_amount);
			
			$ot_day_cal_amount=$ot_hours_amount->ot_day_amount;
			$ot_night_cal_amount=$ot_hours_amount->ot_night_amount;
			$ot_holiday_cal_amount=$ot_hours_amount->ot_holiday_amount;
			
?>		
		<!--<div style="padding: 11px 1px;cursor: pointer;"><i class="icon-printer" style="color:#249E92;font-size:2em;" id="btnPrint1"></i></div>-->
		<div class="panel panel-flat" id="dvContainer1">
		<div class="panel-heading text-center"><h5 class="panel-title">		
		<?php echo $html1;?>
		<strong>Final Settlement Computation</strong></h5><div class="">Date: <?php echo date('d F Y',strtotime($created_at));?></div>	</div>
		<div class="panel-body">
		<div class="col-lg-6 table-responsive" style="min-height: 36em;"><table class="table table-md"><tbody>
		<tr class="bg-slate-600 text-center"><td colspan="3">Basic Details</td></tr>
		<tr class="success"><td>Employee Code</td><td>:</td><td><?php echo $final_settlement->employee_code;?></td></tr>	
		<tr class=""><td>Employee Name</td><td>:</td><td><?php echo $final_settlement->employee_name;?></td></tr>	
		<tr class="danger"><td>Designation</td><td>:</td><td><?php echo $final_settlement->employee_designation;?></td></tr>
		<tr class=""><td>Entity/Agency</td><td>:</td><td><?php echo $final_settlement->entity_agency;?></td></tr>
		<tr class="warning"><td>Date of Join</td><td>:</td><td><?php echo date('d F Y',strtotime($final_settlement->date_of_joining));?></td></tr>
		<tr class=""><td>Contract Date</td><td>:</td><td><?php echo $final_settlement->contract_date;?></td></tr>
		<tr class="info"><td>Last Working Day</td><td>:</td><td><?php echo $final_settlement->last_working_day;?></td></tr>
		<tr class=""><td>Type of Seperation</td><td>:</td><td><?php echo $final_settlement->type_of_separtion;?></td></tr>		
		</tbody></table></div>


		<div class="col-lg-6 table-responsive" style="min-height: 36em;"><table class="table table-md"><tbody>
		<tr class="bg-slate-600 text-center"><td colspan="3">Years of Work & Leave Balance</td></tr>	
		<tr class=""><td>Number of Days worked</td><td>:</td><td><?php echo $final_settlement->no_of_days_worked;?></td></tr>	
		<tr class="danger"><td>Number of Years worked</td><td>:</td><td><?php echo $final_settlement->no_of_years_worked;?></td></tr>
		<tr class=""><td>No of Absence</td><td>:</td><td><?php echo $final_settlement->no_of_absence;?></td></tr>
		<tr class="warning"><td>Net Total Years worked</td><td>:</td><td><?php echo $final_settlement->net_total_years_worked;?></td></tr>
		<tr class=""><td>Total Leave Accrued</td><td>:</td><td><?php echo $final_settlement->total_leave_accrued;?></td></tr>
		<tr class="info"><td>Leave Availed</td><td>:</td><td><?php echo $final_settlement->leave_availed;?></td></tr>
		<tr class=""><td>Balance Leave </td><td>:</td><td><?php echo $final_settlement->balance_leave;?></td></tr>		
		</tbody></table></div>	
		<div class="col-lg-6 table-responsive" style="min-height: 36em;"><table class="table table-md"><tbody>
		<tr class="bg-slate-600 text-center"><td colspan="3">Monthly Salary</td></tr>	
		<tr class=""><td>Basic Salary</td><td>:</td><td><?php echo $final_settlement->basic_salary;?></td></tr>
        <tr class="danger"><td>House Rent Allowance</td><td>:</td><td><?php echo $final_settlement->house_rent_allowance;?></td></tr>
		<tr class=""><td>Transp. Allowance</td><td>:</td><td><?php echo $final_settlement->transportation;?></td></tr>
		<tr class="warning"><td>Food Allowance</td><td>:</td><td><?php echo $final_settlement->food_allowance;?></td></tr>
		<tr class=""><td>Other Allowance</td><td>:</td><td><?php echo $final_settlement->other_allowance;?></td></tr>
		<tr class="info"><td>Additional Benefit</td><td>:</td><td><?php echo $final_settlement->additional_benefits;?></td></tr>
		<tr class=""><td>Bonus</td><td>:</td><td><?php echo $final_settlement->bonus;?></td></tr>
		<tr class="success"><td>Total Salary </td><td>:</td><td><?php echo $final_settlement->total_salary;?></td></tr>			
		</tbody></table></div>
		
		
		<div class="col-lg-6 table-responsive" style="min-height: 36em;"><table class="table table-md"><tbody>
		<tr class="bg-slate-600 text-center"><td colspan="3">Working Hours & Over Time</td></tr>	
	<tr class="danger"><td>Normal Working Hours</td><td>:</td><td><?php echo $final_settlement->normal_working_hours;?></td></tr>	
		<tr class=""><td>Working Hours for the month</td><td>:</td><td><?php echo $final_settlement->working_hours_for_the_month;?></td></tr>
		<tr class="warning"><td>Actual Working Hours</td><td>:</td><td><?php echo round($final_settlement->actual_working_hour,2);?></td></tr>
		<tr class=""><td>OT Hours @ 1.25</td><td>:</td><td><?php echo $final_settlement->ot_day_hours;?></td></tr>
		<tr class="success"><td>OT Hours @ 1.5</td><td>:</td><td><?php echo $final_settlement->ot_night_hours;?></td></tr>
		<tr class=""><td>OT Hours @ PH</td><td>:</td><td><?php echo $final_settlement->ot_holiday_hours;?></td></tr>	
		</tbody></table></div>
		<div class="col-lg-12 table-responsive" style="min-height: 36em;"><table class="table table-md"><tbody>
		<tr class="bg-slate-600"><td>Elements</td><td>Days</td><td>Amount</td></tr>	
		<tr class=""><td colspan="3"><b>Earnings</b></td></tr>	
		<tr class=""><td>Current Month Salary - <?php echo date('F',strtotime($final_settlement->current_month_salary));?></td><td><?php echo round($final_settlement->total_days_of_the_month,2);?></td><td><?php echo change_num_format($final_settlement->salary_of_the_month);?></td></tr>
<tr class=""><td>Leave Salary</td><td><?php echo $final_settlement->leave_salary_days;?></td><td><?php echo $final_settlement->leave_salary;?></td></tr>
<tr class=""><td>OT  Amount @ 1.25</td><td></td><td><?php echo change_num_format($$ot_day_cal_amount);?></td></tr>
<tr class=""><td>OT Amount @ 1.5</td><td></td><td><?php echo change_num_format($$ot_night_cal_amount);?></td></tr>
<tr class=""><td>OT  Amount@ PH</td><td></td><td><?php echo change_num_format($$ot_holiday_cal_amount);?></td></tr>



<?php if($final_settlement->parent_type_name){
	$i=0;
	foreach($final_settlement->parent_type_name as $earnings){
		if($earnings=='Addition'){
	?>
<tr class=""><td><?php echo $final_settlement->child_type_name[$i];?></td><td><?php echo $final_settlement->salary_comments[$i];?></td><td><?php echo $final_settlement->amount[$i];?></td></tr>
		<?php } $i++;}  }?>
<tr class=""><td>Total Earnings</td><td></td><td><?php echo change_num_format($final_settlement->total_earnings);?></td></tr>

<tr class=""><td colspan="3" ><b>Deductions</b></td></tr>

<?php if($final_settlement->parent_type_name){
	$j=0;
	foreach($final_settlement->parent_type_name as $deductions){
		
		if($deductions=='Deduction'){
	?>
<tr class=""><td><?php echo $final_settlement->child_type_name[$j];?></td><td><?php echo $final_settlement->salary_comments[$j];?></td><td><?php echo $final_settlement->amount[$j];?></td></tr>
		<?php } $j++;}  }?>
		
<tr class=""><td>Total Deductions</td><td></td><td><?php echo $final_settlement->total_deductions;?></td></tr>

<?php if($final_settlement->parent_type_name){
	$k=0;
	foreach($final_settlement->parent_type_name as $perp){
		if($perp=='Perpetual'){
	?>
<tr class=""><td><?php echo $final_settlement->child_type_name[$k];?></td><td><?php echo $final_settlement->salary_comments[$k];?></td><td><?php echo round($final_settlement->amount[$k],2);?></td></tr>
		<?php } $k++;}  }?>
		<?php if($final_settlement->parent_type_name){
	$l=0;
	foreach($final_settlement->parent_type_name as $nonperp){
		if($nonperp=='Non-Perpetual'){
	?>
<tr class=""><td><?php echo $final_settlement->child_type_name[$l];?></td><td><?php echo $final_settlement->salary_comments[$l];?></td><td><?php echo round($final_settlement->amount[$l],2);?></td></tr>
		<?php } $l++;}  }?>
<tr class=""><td>Net Payable</td><td></td><td><?php echo change_num_format($final_settlement->net_payable);?></td></tr>
<tr class=""><td>Payment Amount to Employee</td><td></td><td><?php echo change_num_format($final_settlement->payment_amount_to_employee);?></td></tr>

		</tbody></table></div>
		
		</div></div>
																					
<?php }else {?>
N/A
<?php } ?>				
											</div>

											<div class="tab-pane has-padding <?php if($_GET['exit_type']=='clearance_form'){echo 'active';}?> " id="top-justified-tab4">
											
											
<?php 
																						
$query_clearance=$this->db->query("select created_at,final_settlement from xin_employee_exit where employee_id='".$employee_id."' AND approval_form='Clearance Form'");
$result_clearance=$query_clearance->result();

$clearance_settlement=json_decode($result_clearance[0]->final_settlement);

																				
if($result_clearance){
	
	    $query1=$this->db->query('select * from xin_employees_approval where employee_id="'.$employee_id.'" AND type_of_approval="Clearance Form"');
		$result1=$query1->result();
		
		
		$html2='';
			if($result1){
			$html2.='<table class="table table-md"><tbody>
		    <tr class="bg-slate-600 text-center"><td colspan="3">Clearance Form Status</td></tr>';		
			foreach($result1 as $approv_st){
			
			if($approv_st->approved_date!=''){$approved_date=format_date('d F Y',$approv_st->approved_date);}else{$approved_date= '-';}
				if($approv_st->approval_status==0){$approval_status='<span class="badge bg-info">Waiting for approval</span>';}else if($approv_st->approval_status==1){					
					$approval_status= '<span class="badge bg-success">Approved</span>';}else if($approv_st->approval_status==2){$approval_status= '<span class="badge bg-danger">Declined</span>';}
				$html2.='<tr>
					<td>'.$approv_st->head_of_approval.'</td>
					<td>'.$approved_date.'</td>
					<td>'.$approval_status.'</td>    </tr>  ';
			}			
		     $html2.='</tbody></table>';	
		    }																							
																						
       
			
		$html2.='<div class="clearfix"></div><div class="panel panel-flat" id="dvContainer"><div class="panel-heading text-center"><h5 class="panel-title">					 
		<strong>Clearance Form<br>(Employee/Contract)</strong></h5><div class="pull-right mr-10">Date Processed: '.date('d F Y',strtotime($result_clearance[0]->created_at)).'</div>	</div>';
		$html2.='<div class="panel-body">';
		
		$html2.='<div class="col-lg-12 successtable-responsive"	><table class=" bg-teal-300 table table-md table-bordered "><tbody>
	
	
		<tr class=""><td>Employee Name</td><td>'.$clearance_settlement->employee_name.'</td><td>Visa</td><td>'.$clearance_settlement->entity_agency.'</td></tr>
			
		<tr class=""><td>Designation</td><td>'.$clearance_settlement->employee_designation.'</td><td>Department</td><td>'.$clearance_settlement->employee_department.'</td></tr>
		
		<tr class=""><td>Joining Date</td><td>'.format_date('d F Y',$clearance_settlement->joining_date).'</td><td>Resignation Date</td><td>'.format_date('d F Y',$clearance_settlement->resignation_date).'</td></tr>
		<tr class=""><td>Last Working Day</td><td>'.format_date('d F Y',$clearance_settlement->last_working_day).'</td><td></td><td></td></tr>	
		
		<tr class=""><td>Nature of Cessation of Employement<br> **pls.check applicable box**</td><td colspan="3">
		<table class="table table-md table-bordered ">
			
		<tr><div class="form-group">
							
										<label class="radio-inline radio-right">
											<input name="cessation_of_employement" checked type="radio" value="Resignation">
											'.$clearance_settlement->cessation_of_employement.'
										</label>';

						if($clearance_settlement->cessation_of_employement_other!=''){				
			$html2.='<input  class="mt-10 form-input text-teal" name="cessation_of_employement_other" type="text"  value="'.$clearance_settlement->cessation_of_employement_other.'">';
						}				
										
									$html2.='</div></tr>
								
									
	
		</table>
		
	
		</td></tr>
		<tr><td colspan="3">Persons in charge are required to sign in the respective sections and/or department below:<td><tr>';		
		$html2.='</tbody></table></div>';
			
		
		
		$html2.='<div class="mt-10 col-lg-6 table-responsive" ><table class="table bg-teal-300 table-bordered  table-md"><tbody>
		<tr class=""><td colspan="3"><strong>1.Own Department\'s Clearance</strong></td></tr>	
		<tr class=""><td>Bitrix Account</td><td>'.$clearance_settlement->department_bitrix_account.'</td></tr>
<tr class=""><td>Awok Logistics System (ALS)</td><td>'.$clearance_settlement->department_awok_logistics_system.'</td></tr>
<tr class=""><td>RTA Fine</td><td>'.$clearance_settlement->department_rta_fine.'</td></tr>
<tr class=""><td>Vehicle Damage / Fines</td><td>'.$clearance_settlement->department_vehicle_damage_fines.'</td></tr>
<tr class=""><td>Etisalat Bills Outstanding</td><td>'.$clearance_settlement->department_etisalat_bills_outstanding.'</td></tr>
<tr class=""><td>Remarks</td><td>'.$clearance_settlement->department_remarks.'</td></tr>
<tr class=""><td>Name</td><td>'.$clearance_settlement->department_name.'</td></tr>
<tr class=""><td>Signature</td><td><img style="width: 100%;" src="'.$clearance_settlement->department_signature.'"/></td></tr>		
	<tr><td>Date</td><td>'.$clearance_settlement->department_date.'</td></tr>	
		</tbody></table></div>';
		
		
		$html2.='<div class="mt-10 col-lg-6 table-responsive" ><table class="table bg-teal-300 table-bordered  table-md"><tbody>
		<tr class=""><td colspan="3"><strong>2.IT Clearance</strong></td></tr>	
		<tr class=""><td>Bitrix</td><td>'.$clearance_settlement->it_bitrix.'</td></tr>
<tr class=""><td>Email</td><td>'.$clearance_settlement->it_email.'</td></tr>
<tr class=""><td>Skype (if official)</td><td>'.$clearance_settlement->it_skype.'</td></tr>
<tr class=""><td>1c (if applicable)</td><td>'.$clearance_settlement->it_ic.'</td></tr>
<tr class=""><td>Awok.com Access</td><td>'.$clearance_settlement->it_awok_com_access.'</td></tr>
<tr class=""><td>Other/s (pls specify)</td><td>'.$clearance_settlement->it_other_specify.'</td></tr>
<tr class=""><td>Desktop</td><td>'.$clearance_settlement->it_desktop.'</td></tr>
<tr class=""><td>Laptop</td><td>'.$clearance_settlement->it_laptop.'</td></tr>
<tr class=""><td>Mouse</td><td>'.$clearance_settlement->it_mouse.'</td></tr>
<tr class=""><td>Headset</td><td>'.$clearance_settlement->it_headset.'</td></tr>
<tr class=""><td>Keyboard</td><td>'.$clearance_settlement->it_keyboard.'</td></tr>
<tr class=""><td>Remarks</td><td>'.$clearance_settlement->it_remarks.'</td></tr>
<tr class=""><td>Name</td><td>'.$clearance_settlement->it_name.'</td></tr>
<tr class=""><td>Signature</td><td><img style="width: 100%;" src="'.$clearance_settlement->it_signature.'"/></td></tr>		
	<tr><td>Date</td><td>'.$clearance_settlement->it_date.'</td></tr>	
		</tbody></table></div> <div class="clear-fix"></div>';
		
		
		
		
		
		$html2.='<div class="mt-10 col-lg-6 table-responsive" ><table class="table bg-teal-300 table-bordered  table-md"><tbody>
		<tr class=""><td colspan="3"><strong>3.HR Clearance</strong></td></tr>	
		<tr class=""><td>Labour Card</td><td>'.$clearance_settlement->hr_labour_card.'</td></tr>
		<tr class=""><td>Emirates Card</td><td>'.$clearance_settlement->hr_emirates_card.'</td></tr>
		<tr class=""><td>Medical Card</td><td>'.$clearance_settlement->hr_medical_card.'</td></tr>
		<tr class=""><td>Exit Interview</td><td>'.$clearance_settlement->hr_exit_interview.'</td></tr>

<tr class=""><td>Remarks</td><td>'.$clearance_settlement->hr_remarks.'</td></tr>
<tr class=""><td>Name</td><td>'.$clearance_settlement->hr_name.'</td></tr>
<tr class=""><td>Signature</td><td><img style="width: 100%;" src="'.$clearance_settlement->hr_signature.'"/></td></tr>		
	<tr><td>Date</td><td>'.$clearance_settlement->hr_date.'</td></tr>	
		</tbody></table></div>';
		
			$html2.='<div class="mt-10 col-lg-6 table-responsive" ><table class="table bg-teal-300 table-bordered  table-md"><tbody>
		<tr class=""><td colspan="3"><strong>4.Account\'s Clearance</strong></td></tr>	
		<tr class=""><td>Claims Settlement (1511)</td><td>'.$clearance_settlement->account_claims_settlement_1511.'</td></tr>
		<tr class=""><td>Advance to Employee for company purpose(3630) <br> 3630.01(AED)/3630.02(AED) (other currency)</td><td>'.$clearance_settlement->account_advance_to_employee_for_company_purpose.'</td></tr>
		<tr class=""><td>Settlement with Personnel On Payment.(3520)</td><td>'.$clearance_settlement->account_settlement_with_personnel_on_payment_3520.'</td></tr>
		<tr class=""><td>Settlement with Personnel Outsources.(3523)</td><td>'.$clearance_settlement->account_settlement_with_personnel_outsources_3523.'</td></tr>
<tr class=""><td>Other/s (if any)</td><td>'.$clearance_settlement->account_other_specify.'</td></tr>
<tr class=""><td>Total Amount Payable</td><td>'.$clearance_settlement->account_total_amount_payable.'</td></tr>

<tr class=""><td>Prepared By</td><td>'.$clearance_settlement->account_prepardby.'</td></tr>
<tr class=""><td>Checked By</td><td>'.$clearance_settlement->account_checkedby.'</td></tr>

<tr class=""><td>Remarks</td><td>'.$clearance_settlement->account_remarks.'</td></tr>
<tr class=""><td>Name</td><td>'.$clearance_settlement->account_name.'</td></tr>
<tr class=""><td>Signature</td><td><img style="width: 100%;" src="'.$clearance_settlement->account_signature.'"/></td></tr>		
	<tr><td>Date</td><td>'.$clearance_settlement->account_date.'</td></tr>	
		</tbody></table></div>';
		
			echo $html2;
}else{
	
	echo 'N/A';
}
?>






											</div>

											
										</div>
									</div>
								</div>
							
						
						
						
						</div>
					</div>
		
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
  </div>
</form>
<script type="text/javascript">
 $(document).ready(function(){
	 $('body').on('click', '#btnPrint2', function(){
       
			
			 var printContents = document.getElementById('dvContainer2').innerHTML;
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
<?php }
?>
