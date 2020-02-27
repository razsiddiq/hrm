<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['employee_id']) && $_GET['data']=='payment' && $_GET['type']=='monthly_payment'){ ?>
<?php
$payment_month = strtotime($this->input->get('pay_date'));
$p_month = date('F Y',$payment_month);

$p_date=date('Y-m',strtotime($this->input->get('pay_date')));
$attn_date=salary_start_end_date($p_date);

$start_dt=$attn_date['exact_date_start'];
$end_dt=$attn_date['exact_date_end'];

$attendance=explode('-',$p_date);
$days_count_per_month=cal_days_in_month(CAL_GREGORIAN,$attendance[1],$attendance[0]);
$calendar_start_date=$p_date.'-01';
$calendar_end_date=$p_date.'-'.$days_count_per_month;


$pre_month=date('Y-m',strtotime("-1 month",strtotime($p_date)));
$pre_month_start_date=$start_dt;
$pre_month_end_date=date('Y-m-d',strtotime("-1 Day",strtotime($calendar_start_date)));
		 

$user_info = $this->Xin_model->read_user_info($_GET['employee_id']);
$date_of_joining=$user_info[0]->date_of_joining;
$date_of_leaving=$user_info[0]->date_of_leaving;
$full_name = change_fletter_caps($user_info[0]->first_name.' '.$user_info[0]->middle_name.' '.$user_info[0]->last_name);
				


		$ot_day_rate=0;$ot_night_rate=0;$ot_holiday_rate=0;
		if($_GET['t_ot_hours_amount']!=null){
		$ot_hours_amount=json_decode($_GET['t_ot_hours_amount']); 
		$ot_day_rate=$ot_hours_amount->ot_day_amount;
		$ot_night_rate=$ot_hours_amount->ot_night_amount;
		$ot_holiday_rate=$ot_hours_amount->ot_holiday_amount;
			
		}
$visa_id=$_GET['t_visa_type'];
$tax_type=get_tax_info($visa_id);
$check_365days_enabled=$_GET['t_check_365days_enabled'];
$shift_hours=$_GET['shift_hours'];
			
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><strong><?php echo $full_name;?></strong> Payment For <strong><?php echo $p_month;?></strong></h4>
</div>
<div class="modal-body">
  <form class="m-b-1" action="<?php echo site_url("payroll/add_pay_monthly") ?>" method="post" name="pay_monthly" id="pay_monthly">
    <div class="row">
      <div class="col-md-6"><label for="name">Total Salary</label>
        <div class="form-group">

            <input type="text" class="form-control add_action" name="total_salary" id="to_total_salary_<?php echo $user_id;?>" value="<?php echo $_GET['t_salary_after_cal'];?>" readonly>
<div class="form-control-currency"><?php echo $this->Xin_model->currency_sign('','',$_GET['employee_id']);?></div> </div>
          <input type="hidden" name="department_id" value="<?php echo $department_id;?>" />
          <input type="hidden" name="company_id" value="<?php echo $company_id;?>" />
          <input type="hidden" name="location_id" value="<?php echo $location_id;?>" />
          <input type="hidden" name="designation_id" value="<?php echo $designation_id;?>" />
          
          <input type="hidden" name="salary_with_bonus" class="form-control" value="<?php echo $_GET['t_salary_with_bonus'];?>" readonly>   
          <input type="hidden" id="emp_id" value="<?php echo $user_id?>" name="emp_id">
          <input type="hidden" value="<?php echo $user_id;?>" name="u_id">
          <input type="hidden" value="<?php echo $_GET['t_basic_salary'];?>" name="basic_salary">
          <input type="hidden" value="<?php echo $_GET['t_salary_template_id'];?>" name="salary_template_id">
          <input type="hidden" value="<?php echo $p_date;?>" name="pay_date" id="pay_date">
		 <input type="hidden" name="required_working_hours" id="to_required_working_hours_<?php echo $user_id;?>" value=""/>
		 <input type="hidden" name="total_working_hours" id="to_total_working_hours_<?php echo $user_id;?>" value=""/>
		 <input type="hidden" name="late_working_hours" id="to_late_working_hours_<?php echo $user_id;?>" value=""/>
	 	 <input type="hidden" name="rate_per_hour_contract_bonus" id="to_rate_per_hour_contract_bonus_<?php echo $user_id;?>" value="'"/>
		 <input type="hidden" name="rate_per_hour_contract_only" id="to_rate_per_hour_contract_only_<?php echo $user_id;?>" value=""/>
		 <input type="hidden" name="rate_per_hour_basic_only" id="to_rate_per_hour_basic_only_<?php echo $user_id;?>" value=""/>
		 <input type="hidden" name="ot_day_rate" id="to_ot_day_rate_<?php echo $user_id;?>" value=""/>
		 <input type="hidden" name="ot_night_rate" id="to_ot_night_rate_<?php echo $user_id;?>" value=""/>
		 <input type="hidden" name="ot_holiday_rate" id="to_ot_holiday_rate_<?php echo $user_id;?>" value=""/>
		 <input type="hidden" name="ot_hours_amount" id="to_ot_hours_amount_<?php echo $user_id;?>" value=''>
		 <input type="hidden" name="leave_salary_amount" id="to_leave_salary_amount_<?php echo $user_id;?>" value=''>
		 <input type="hidden" name="leave_salary_paid" id="to_leave_salary_paid_<?php echo $user_id;?>" value=''>
		 <input type="hidden" name="actual_days_worked" id="to_actual_days_worked_<?php echo $user_id;?>" value=''>
		<input type="hidden" value="<?php echo $_GET['t_leave_start_date'];?>" name="leave_start_date">
		<input type="hidden" value="<?php echo $_GET['t_leave_end_date'];?>" name="leave_end_date">

		<input type="hidden" value="<?php echo $_GET['t_annual_leave_salary'];?>" name="annual_leave_salary">
		<input type="hidden" value="<?php echo $_GET['t_month_salary'];?>" name="month_salary">
		<input type="hidden" value="<?php echo $_GET['t_joining_month_salary'];?>" name="joining_month_salary">    
	   <input type="hidden" value='<?php echo $_GET['t_driver_delivery_details'];?>' name="driver_delivery_details">
      </div>
    
	<div class="col-md-6"> <label for="name">OT Amount</label>
        <div class="form-group">

          <input type="text" class="form-control" value="<?php echo $ot_day_rate+$ot_night_rate+$ot_holiday_rate;?>" readonly><div class="form-control-currency"><?php echo $this->Xin_model->currency_sign('','',$_GET['employee_id']);?></div> 
        </div>
      </div>
	  
	
	</div>
    <?php
	$find_internal_add_adjustments = $this->Payroll_model->find_adjustments($user_id,$start_dt,$end_dt,'internal_adjustments','Addition');
	$find_internal_ded_adjustments = $this->Payroll_model->find_adjustments($user_id,$start_dt,$end_dt,'internal_adjustments','Deduction');

	$find_external_perp_adjustments = $this->Payroll_model->find_ext_adjustments($user_id,$start_dt,$end_dt,'external_adjustments','Perpetual');
	$find_external_nonperp_adjustments = $this->Payroll_model->find_ext_adjustments($user_id,$start_dt,$end_dt,'external_adjustments','Non-Perpetual');

	$adds=0;
	$deds=0;
	$perp=0;
	?>

	
    <input type="hidden" name="salary_components" class="form-control" value='<?php echo $_GET['t_salary_components'] ;?>'>
   
	
    
	<?php if($_GET['t_bonus']!=''): ?>
    <input type="hidden" name="bonus" value="<?php echo $_GET['t_bonus'];?>">
	<?php else:?>
    <input type="hidden" name="bonus" class="form-control" value="0">
    <?php endif;?>
	
	
	<div class="clearfix"></div>

   
	<div id="append_divs">
	
	<?php
	
	$driver_delivery_count=json_decode($_GET['t_driver_delivery_details']);
	if($driver_delivery_count){
		if($driver_delivery_count->agreed_bonus!=null || $driver_delivery_count->agreed_bonus!=0){			
		$driver_incentives=$this->Payroll_model->read_salary_type_name('Incentives');
		$adds+=$driver_delivery_count->agreed_bonus;
		?>
		<label for="name">Additions</label> 
		 <div class="row">
      <div class="col-md-5">
        <div class="form-group">
		    <input type="hidden" name="adjustment_id[]" value="0">
            <input type="hidden" name="parent_type[]" value="<?php echo $driver_incentives[0]->type_parent;?>">
			<input type="hidden" name="child_type[]"  value="<?php echo $driver_incentives[0]->type_id;?>">
			<input type="hidden" name="amount[]"  value="<?php echo $driver_delivery_count->agreed_bonus;?>">
			<input type="hidden" name="salary_comments[]"  value="Top Delivery Bonus">
            <input type="text"  value="Incentives" class="form-control" readonly />
        </div>
      </div>
      <div class="col-md-5">
        <div class="form-group">
          <input type="text"  value="<?php echo $driver_delivery_count->agreed_bonus;?>" class="form-control" readonly /><div class="form-control-currency">
		<?php echo $this->Xin_model->currency_sign('','',$_GET['employee_id']);?>										</div>
        </div>
      </div>

<div class="col-md-2" style="text-align: center;">
<i style="cursor:pointer;" class="ml-10 icon-bubble-dots4 position-right text-teal-400" data-popup="popover-custom" data-placement="bottom" title="Incentives" data-trigger="hover" data-content="Top Delivery Bonus"></i>
 </div>

    </div>
		<?php
		
		
		
		}
		
	}     
	?>
     
    <?php if($find_internal_add_adjustments){?>
          <div class="<?php echo @$col_class;?> no-padding-left">
		  
		  <?php  if($driver_delivery_count->agreed_bonus==null || $driver_delivery_count->agreed_bonus==0){ ?>
              <label for="name">Additions</label> 
		  <?php } ?>
           
					 <?php foreach($find_internal_add_adjustments as $int_add_adjustments){
						 
						 if($int_add_adjustments->parent_type_name=='Addition'){
							 $adds+=$int_add_adjustments->adjustment_amount;
						 
						 ?>
 <div class="row">
      <div class="col-md-5">
        <div class="form-group">
		    <input type="hidden" name="adjustment_id[]" value="<?php echo $int_add_adjustments->adjustment_id;?>">
            <input type="hidden" name="parent_type[]" value="<?php echo $int_add_adjustments->adjustment_type;?>">
			<input type="hidden" name="child_type[]"  value="<?php echo $int_add_adjustments->adjustment_name;?>">
			<input type="hidden" name="amount[]"  value="<?php echo $int_add_adjustments->adjustment_amount;?>">
			<input type="hidden" name="salary_comments[]"  value="<?php echo $int_add_adjustments->comments;?>">

            <input type="text"  value="<?php echo $int_add_adjustments->child_type_name;?>" class="form-control" readonly />
        </div>
      </div>
      <div class="col-md-5">
        <div class="form-group">
          <input type="text"  value="<?php echo $int_add_adjustments->adjustment_amount;?>" class="form-control" readonly /><div class="form-control-currency">
		<?php echo $this->Xin_model->currency_sign('','',$_GET['employee_id']);?>										</div>
        </div>
      </div>

<?php if($int_add_adjustments->comments!=''){?>
<div class="col-md-2" style="text-align: center;">
<i style="cursor:pointer;" class="ml-10 icon-bubble-dots4 position-right text-teal-400" data-popup="popover-custom" data-placement="bottom" title="<?php echo $int_add_adjustments->child_type_name ?>" data-trigger="hover" data-content="<?php echo $int_add_adjustments->comments; ?>"></i>
 </div>
<?php }?>



    

    </div>


                        
					 <?php } }?>
                    
 </div>
	<?php } ?>
	
	<?php if($find_internal_ded_adjustments){?>
          <div class="<?php echo @$col_class;?> no-padding-left">
              <label for="name">Deductions</label> 
            
           
					 <?php foreach($find_internal_ded_adjustments as $int_ded_adjustments){
						 
						 if($int_ded_adjustments->parent_type_name=='Deduction'){
							 $deds+=$int_ded_adjustments->adjustment_amount;
						 
						 ?>
 <div class="row">
      <div class="col-md-5">
        <div class="form-group">
			<input type="hidden" name="adjustment_id[]" value="<?php echo $int_ded_adjustments->adjustment_id;?>">
            <input type="hidden" name="parent_type[]" value="<?php echo $int_ded_adjustments->adjustment_type;?>">
			<input type="hidden" name="child_type[]"  value="<?php echo $int_ded_adjustments->adjustment_name;?>">
			<input type="hidden" name="amount[]"  value="<?php echo $int_ded_adjustments->adjustment_amount;?>">
			<input type="hidden" name="salary_comments[]"  value="<?php echo $int_ded_adjustments->comments;?>">

            <input type="text"  value="<?php echo $int_ded_adjustments->child_type_name;?>" class="form-control" readonly />
        </div>
      </div>
      <div class="col-md-5">
        <div class="form-group">
          <input type="text"  value="<?php echo $int_ded_adjustments->adjustment_amount;?>" class="form-control" readonly /><div class="form-control-currency">
		<?php echo $this->Xin_model->currency_sign('','',$_GET['employee_id']);?>											</div>
        </div>
      </div>

<?php if($int_ded_adjustments->comments!=''){?>
<div class="col-md-2" style="text-align: center;">
<i style="cursor:pointer;" class="ml-10 icon-bubble-dots4 position-right text-teal-400" data-popup="popover-custom" data-placement="bottom" title="<?php echo $int_ded_adjustments->child_type_name ?>" data-trigger="hover" data-content="<?php echo $int_ded_adjustments->comments; ?>"></i>
 </div>
<?php }?>



    

    </div>


                        
					 <?php } }?>
                    
 </div>
	<?php } ?>


	 <?php if($find_external_perp_adjustments){?>
          <div class="<?php echo @$col_class;?> no-padding-left">
              <label for="name">Perpetual</label> 
            
           
					 <?php foreach($find_external_perp_adjustments as $ext_perp_adjustments){
						 
						 if($ext_perp_adjustments->parent_type_name=='Perpetual'){
						
						 if($ext_perp_adjustments->tax_percentage!=0)
							{
							//echo '   ('.$ext_perp_adjustments->tax_percentage.'% VAT) ';	
							}
						//$ext_perp_adjustments_amount=(($ext_perp_adjustments->adjustment_amount)-($ext_perp_adjustments->adjustment_amount*($ext_perp_adjustments->tax_percentage/100)));
						
						$ext_perp_adjustments_amount=$ext_perp_adjustments->adjustment_amount;
							 
							if($ext_perp_adjustments->compute_amount==0){// UHRS
							if($ext_perp_adjustments->child_type_name=='Agency Fees'){
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
								
							$computed_amount=($ext_perp_adjustments_amount/$days_count_per_month)*$no_of_days_worked;
							$perp+=$computed_amount;	
								
							}else if((strtotime($start_dt) <= strtotime($date_of_leaving)) && (strtotime($end_dt) >= strtotime($date_of_leaving))){
							
								
							$date1=new DateTime($calendar_start_date);	
							
							$date2=new DateTime($date_of_leaving);
							$interval_date=$date2->diff($date1);
						    $no_of_days_worked=$interval_date->days+1;
								
							$computed_amount=($ext_perp_adjustments_amount/$days_count_per_month)*$no_of_days_worked;
							$perp+=$computed_amount;	
							// DOJ
							}else{
							$computed_amount=$ext_perp_adjustments_amount;
							$perp+=$computed_amount;
							
							}
							}else{
							$computed_amount=$ext_perp_adjustments_amount;
							$perp+=$computed_amount;	
							}
							
							
							}
							else{		
							//
							
							if($ext_perp_adjustments->child_type_name=='Agency Fees'){
							// DOJ
							if((strtotime($start_dt) <= strtotime($date_of_joining)) && (strtotime($end_dt) >= strtotime($date_of_joining))){
							if((strtotime($pre_month_start_date) <= strtotime($date_of_joining)) && (strtotime($pre_month_end_date) >= strtotime($date_of_joining))){
							$computed_amount=(($ext_perp_adjustments_amount/$_GET['r_working_hours'])*$_GET['t_working_hours'])+(($ext_perp_adjustments_amount/$_GET['t_p_m_required_working_hours'])*$_GET['t_joining_month_hours']); 
							$perp+=$computed_amount;
							}else if((strtotime($calendar_start_date) <= strtotime($date_of_joining)) && (strtotime($calendar_end_date) >= strtotime($date_of_joining))){
							$computed_amount=(($ext_perp_adjustments_amount/$_GET['r_working_hours'])*$_GET['t_working_hours']); 
							$perp+=$computed_amount;							
							}							
							}else if((strtotime($start_dt) <= strtotime($date_of_leaving)) && (strtotime($end_dt) >= strtotime($date_of_leaving))){
							
							$computed_amount=(($ext_perp_adjustments_amount/$_GET['r_working_hours'])*$_GET['t_working_hours']); 
							$perp+=$computed_amount;	
							// DOJ
							}else{
								
							if($check_365days_enabled==1){								
							$computed_amount=$ext_perp_adjustments_amount-(($_GET['r_working_hours']-$_GET['t_working_hours'])*($ext_perp_adjustments_amount*12/365/$shift_hours));
							$perp+=$computed_amount;
							}else{						
							$computed_amount=(($ext_perp_adjustments_amount/$_GET['r_working_hours'])*$_GET['t_working_hours']); 
							$perp+=$computed_amount;
							}
							}
							}else{
							$computed_amount=(($ext_perp_adjustments_amount/$_GET['r_working_hours'])*$_GET['t_working_hours']); 
							$perp+=$computed_amount;	
							}
							
							
							
							}
							
						
						 ?>
 <div class="row">
      <div class="col-md-5">
        <div class="form-group">
            <input type="hidden" name="parent_type[]" value="<?php echo $ext_perp_adjustments->adjustment_type;?>">
			<input type="hidden" name="child_type[]"  value="<?php echo $ext_perp_adjustments->adjustment_name;?>">
			<input type="hidden" name="amount[]"  value="<?php echo $computed_amount;?>">
			<input type="hidden" name="salary_comments[]"  value="<?php echo $ext_perp_adjustments->comments;?>">

            <input type="text"  value="<?php echo $ext_perp_adjustments->child_type_name;?>" class="form-control" readonly />
        </div>
      </div>
      <div class="col-md-5">
        <div class="form-group">
          <input type="text"  value="<?php echo $computed_amount;?>" class="form-control" readonly /><div class="form-control-currency">
	<?php echo $this->Xin_model->currency_sign('','',$_GET['employee_id']);?>											</div>
        </div>
      </div>

<?php if($ext_perp_adjustments->comments!=''){?>
<div class="col-md-2" style="text-align: center;">
<i style="cursor:pointer;" class="ml-10 icon-bubble-dots4 position-right text-teal-400" data-popup="popover-custom" data-placement="bottom" title="<?php echo $ext_perp_adjustments->child_type_name ?>" data-trigger="hover" data-content="<?php echo $ext_perp_adjustments->comments; ?>"></i>
 </div>
<?php }?>



    

    </div>


                        
					 <?php } }?>
                    
 </div>
	<?php } ?>

      <?php if($find_external_nonperp_adjustments){?>
          <div class="<?php echo @$col_class;?> no-padding-left">
              <label for="name">Non-Perpetual </label> 
            
           
					 <?php foreach($find_external_nonperp_adjustments as $ext_nonperp_adjustments){
						 
						 if($ext_nonperp_adjustments->parent_type_name=='Non-Perpetual'){
							 
							 if($ext_nonperp_adjustments->tax_percentage!=0)
							{
							//echo '   ('.$ext_nonperp_adjustments->tax_percentage.'% VAT) ';	
							}
							
							// $ext_nonperp_adjustments_amount=(($ext_nonperp_adjustments->adjustment_amount)-($ext_nonperp_adjustments->adjustment_amount*($ext_nonperp_adjustments->tax_percentage/100)));
							
							$ext_nonperp_adjustments_amount=$ext_nonperp_adjustments->adjustment_amount;
							 
							if($ext_nonperp_adjustments->compute_amount==0){
							$computed_nonperp_amount=$ext_nonperp_adjustments_amount;
							$perp+=$computed_nonperp_amount;
							}else{		
							$computed_nonperp_amount=(($ext_nonperp_adjustments_amount/$_GET['r_working_hours'])*$_GET['t_working_hours']);					
							$perp+=$computed_nonperp_amount;
							
							}
						 
						 ?>
 <div class="row">
      <div class="col-md-5">
        <div class="form-group">
            <input type="hidden" name="parent_type[]" value="<?php echo $ext_nonperp_adjustments->adjustment_type;?>">
			<input type="hidden" name="child_type[]"  value="<?php echo $ext_nonperp_adjustments->adjustment_name;?>">
			<input type="hidden" name="amount[]"  value="<?php echo $computed_nonperp_amount;?>">
			<input type="hidden" name="salary_comments[]"  value="<?php echo $ext_nonperp_adjustments->comments;?>">

            <input type="text"  value="<?php echo $ext_nonperp_adjustments->child_type_name;?>" class="form-control" readonly />
        </div>
      </div>
      <div class="col-md-5">
        <div class="form-group">
          <input type="text"  value="<?php echo $computed_nonperp_amount;?>" class="form-control" readonly /><div class="form-control-currency">
		<?php echo $this->Xin_model->currency_sign('','',$_GET['employee_id']);?>											</div>
        </div>
      </div>

<?php if($ext_nonperp_adjustments->comments!=''){?>
<div class="col-md-2" style="text-align: center;">
<i style="cursor:pointer;" class="ml-10 icon-bubble-dots4 position-right text-teal-400" data-popup="popover-custom" data-placement="bottom" title="<?php echo $ext_nonperp_adjustments->child_type_name ?>" data-trigger="hover" data-content="<?php echo $ext_nonperp_adjustments->comments; ?>"></i>
 </div>
<?php }?>



    

    </div>


                        
					 <?php } }?>
                    
 </div>
	<?php } ?>

</div>

 
 
	
    <div class="row">
	
	
	  
      <div class="col-md-6"> <label for="name">Payment Amount</label>
        <div class="form-group">
		<input type="hidden" name="perpertual_amount" value="<?php echo $perp;?>" />
         <?php 
		  $N_salary=($_GET['t_salary_after_cal']+$adds+$perp+$ot_day_rate+$ot_night_rate+$ot_holiday_rate)-$deds;?> 
          <input type="text" name="payment_amount" class="form-control" value="<?php echo $N_salary;?>" readonly><div class="form-control-currency"><?php echo $this->Xin_model->currency_sign('','',$_GET['employee_id']);?></div> <!--id="payment_amount"-->
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="payment_method">Payment Method</label>
          <select name="payment_method" class="select2" data-plugin="select_hrm" data-placeholder="Choose Method...">
            <?php $get_payment_method=$this->Xin_model->get_payment_method();

			foreach($get_payment_method->result() as $payment_method){?>
			<option <?php if($payment_method->type_name=='Bank Transfer'){echo 'selected';};?> value="<?php echo $payment_method->type_id;?>"><?php echo $payment_method->type_name;?></option>
			<?php } ?>

          </select>
        </div>
      </div>
    </div>
   
<?php 
$total_tax_amount=0;
if($tax_type){
echo '<label for="name">Tax</label>';
foreach($tax_type as $tax_t){


$tax_amount=($N_salary*($tax_t->type_symbol/100));
$total_tax_amount+=$tax_amount;
?>
	<input type="hidden" name="tax_name[]" class="form-control" value="<?php echo $tax_t->type_name;?>" />
	<input type="hidden" name="tax_percentage[]" class="form-control" value="<?php echo $tax_t->type_symbol;?>" />

<div class="row">	  
      <div class="col-md-6"> 
        <div class="form-group">
		<input type="text"  class="form-control" value="<?php echo $tax_t->type_name.' ('.$tax_t->type_symbol.'%)';?>" />

        </div>
      </div>
<div class="col-md-6">
<div class="form-group">
  <input type="text" name="tax_amount[]" class="form-control" value="<?php echo $tax_amount;?>" readonly><div class="form-control-currency"><?php echo $this->Xin_model->currency_sign('','',$_GET['employee_id']);?></div> 
</div></div>
   </div>
   
<?php } } ?>


 <div class="row"><!-- id="hide_comment"-->
    <?php 
if($tax_type){?> 
<div class="col-md-6"> <label for="name">Payment Amount with Tax</label>
        <div class="form-group">
		<input type="hidden" name="payment_amount_with_tax" value="<?php echo $N_salary+$total_tax_amount;?>" />
   
          <input type="text" class="form-control" value="<?php echo $N_salary+$total_tax_amount;?>" readonly><div class="form-control-currency"><?php echo $this->Xin_model->currency_sign('','',$_GET['employee_id']);?></div> 
        </div>
      </div>
  <div class="col-md-6">
<?php }else{ ?>
<input type="hidden" name="payment_amount_with_tax" value="<?php echo $N_salary;?>" />
  <div class="col-md-12">
   <?php } ?>
        <div class="form-group">
          <label for="name">Comments</label>
          <input type="text" class="form-control" value="Transaction Completed." name="comments">
        </div>
      </div>




    </div>
	
	<div class="row"><div class="footer-elements">
    <button type="submit" class="btn bg-teal-400 save">Pay</button>
  </div></div>
  
  
  </form>
</div>
</div>
<style>
.modal-dialog {
    max-width: 1000px;
  
}
</style>

<script type="text/javascript">

$('[data-popup=popover-custom]').popover({
		template: '<div class="popover border-teal-400"><div class="arrow"></div><h3 class="popover-title bg-teal-400"></h3><div class="popover-content"></div></div>'
	});

function delete_append_div(id){	
			
			
$('#parent_div_'+id).remove();	

var field_id=$("#append_divs > div").length;
	if(field_id!=0){
				$('#hide_comment').hide();
			}else{$('#hide_comment').show();}
			
		
keyupfun();		
}

function getParentChildType(val,count){
	if (val == "") {
        document.getElementById("child_type_"+count).innerHTML = "";
        return;
      } else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
          
            if (this.readyState == 4 && this.status == 200) {
				var cods=JSON.parse(this.responseText);
				//console.log(cods);
                document.getElementById("child_type_"+count).innerHTML = cods.html;
				document.getElementById("child_amount_"+count).className = cods.class+' '+'form-control';
				
                $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	            $('[data-plugin="select_hrm"]').select2({ width:'100%' });
            }
        };

        xmlhttp.open("GET","<?php echo site_url("payroll/getChildType/");?>"+val,true);
        xmlhttp.send();
		
		
		
    }
	
}

$(document).on("keyup", function () {
	
keyupfun();
	
	
});

function keyupfun(){
		var sum_total=0;
	var deduction=0;
	
	$(".add_action").each(function () {
		sum_total += +$(this).val();
	});
	
	$(".deduct_action").each(function () {
		deduction += +$(this).val();
	});

	
    var theResult =  sum_total - deduction;
	
	
	//$("#payment_amount").val(theResult);
}

$(document).ready(function(){
	toastr.options.closeButton = true;
	toastr.options.progressBar = false;
	toastr.options.timeOut = 3000;
	toastr.options.preventDuplicates = true;
	toastr.options.positionClass = "toast-bottom-right";
});	
</script>
<script type="text/javascript">
$(document).ready(function(){

	    
	
	 
	// On page load: datatable


    // Enable Select2 select for the length option
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
	
    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));	

	$("#pay_monthly").submit(function(e){
	
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
	    $('.save').prop('disabled', true);
	    var employee_id = $('#employee_id').val();
        var month_year = $('#month_year').val();
		
		var department_value=$('.department_value').val();
        var location_value=$('.location_value').val();


		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=11&data=monthly&add_type=add_monthly_payment&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.emo_monthly_pay').modal('toggle');
					//xin_table.ajax.reload(function(){
make_pay_dialog();						
						toastr.success(JSON.result);
					//}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
	
	
	$("#salary_add").click(function(e){
	
        var field_id=$("#append_divs > div").lenth;//g
		var count_id=field_id+1;
		
 		$.ajax({
		url : "<?php echo site_url("payroll/dynamic_salary_type") ?>",
		type: "GET",
		data: 'count_id='+count_id,
		success: function (response) {
			if(response) {
				$("#append_divs").append(response);
			}
			
			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	        $('[data-plugin="select_hrm"]').select2({ width:'100%' });
			
			if(count_id!=0){
			 $('#hide_comment').hide();
			}else{$('#hide_comment').show();}
		}
	});
	});
	
	
});

function make_pay_dialog(){
var employee_id = $('#employee_id').val();
        var month_year = $('#month_year').val();
		
		var department_value=$('.department_value').val();
		var location_value=$('.location_value').val();
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
		"iDisplayLength": 100,
		"ajax": {
			url : site_url+"payroll/payslip_list_new/?employee_id="+employee_id+"&month_year="+month_year+"&department_value="+department_value+"&location_value="+location_value,
			type : 'GET'
		},"fnDrawCallback": function(settings){
			$('[data-popup=popover-custom]').popover({
		template: '<div class="popover border-teal-400"><div class="arrow"></div><h3 class="popover-title bg-teal-400"></h3><div class="popover-content"></div></div>'
	});         
		},'columnDefs': [{
         'targets': 0,
         'searchable':false,
         'orderable':false,
         'className': 'dt-body-center',
         'render': function (data, type, full, meta){
			// console.log(full);
			  if(full[13]=='<span class="label label-success">Paid</span>'){
				  return '<label><input type="checkbox" checked disabled><span></span></label>';
			  }else if(full[13]=='<span class="label label-danger">Hold</span>'){
				  return '<label><input type="checkbox" class="bs_styled_hold" name="id[]" value="'+ $('<div/>').text(data).html() + '"><span></span></label>';	
			  }else if(full[13]=='<span class="label label-info">Leave Settlement</span>'){
				  return '<span class="label label-info">Leave Settlement</span>';	
			  }else{
			      return '<label><input type="checkbox" class="bs_styled" name="id[]" value="'+ $('<div/>').text(data).html() + '"><span></span></label>';	
			  } 	


				
         }
      }],
	  
	  
      'order': [1, 'asc'],
            buttons: [
			/*{
              
                text: 'Make All Payment',
                className: 'btn bg-teal-400 make_all_payment',
             
                },	*/	
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
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
	
    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));	
}	
</script>
<?php } ?>