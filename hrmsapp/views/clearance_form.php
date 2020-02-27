<?php $system = $this->Xin_model->read_setting_info(1);?>
<?php $company = $this->Xin_model->read_company_setting_info(1);?>
<?php $logo = base_url().'uploads/logo/'.$company[0]->logo;?>
<!DOCTYPE html>

<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo $title; ?></title>
    <meta name="author" content="Alifca DMCC - ultimate human resource management system">
    <link rel="icon" href="<?=base_url()?>skin/img/wz-icon.png" type="image/png">
	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/css/core.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/css/components.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/css/colors.css" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/toastr/toastr.min.css">
	<!-- Core JS files -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/loaders/pace.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/libraries/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/libraries/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/loaders/blockui.min.js"></script>
	<!-- /core JS files -->

<style>
.picker__table,.picker__select--month, .picker__select--year {
    color: black;
}
.wrapper {
  position: relative;
  width: 100%;
  height: 105px;
  -moz-user-select: none;
  -webkit-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

.signature-pad {
  position: absolute;
  left: 0;
  top: 0;
  width:100%;
  height:100px;
  background-color: white;
}
</style>

</head>
<?php

$clearance_form_val=json_decode($clearance_form[0]->final_settlement);



$head_p_name=$head_name_check[$approval_head_id];
?>
<body class="login-container">

	<!-- Main navbar -->
	<!--<div class="navbar navbar-inverse">
		<div class="navbar-header">
			<a class="navbar-brand" href="#"><img src="<?php //echo $logo?>" alt="Alifca DMCC"/></a>

	//Array ( [135] => CFO [139] => HR Head [133] => CEO [127] => IT Head [132] => Department Head [57] => Accounts Manager )		
		</div>
</div>-->
	<!-- /main navbar -->


	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main content -->
			<div class="content-wrapper">
<div class="modal fade edit-modal-data-payrol animated <?php echo $system[0]->animation_effect_modal;?>" id="edit-modal-data-payrol" role="dialog" aria-labelledby="edit-modal-data-payrol" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" id="ajax_modal_payview"></div>
  </div>
</div>
				<!-- Content area -->
				<div class="content">
 <?php if($uri==''){$redirect='dashboard?module=dashboard';}else{$redirect=$uri;}?>
					<!-- Simple login form -->
					<?php if($form_type=='clearance_form'){?>
					<form class="mb-1" method="post" name="hrm-form" id="hrm-form">
					                    <input type="hidden" name="<?= csrf_name;?>" value="<?= csrf_hash;?>" />
						
						
						<div class="panel panel-body" style="width: 75%;margin: 0 auto;"><div class="icon-object" style="border-color: white;"><img src="<?php echo base_url();?>uploads/logo/signin/<?php echo $company[0]->sign_in_logo;?>" title=""></div>


<input type="hidden" value="<?php echo $head_p_name;?>" name="head_p_name"/>
<input type="hidden" value="<?php echo $approval_head_id;?>" name="approval_head_id"/>
<input type="hidden" value="<?php echo $employee_id;?>" name="employee_id"/>
<input type="hidden" value="<?php echo $type_of_approval;?>" name="type_of_approval"/>
<input type="hidden" value="<?php echo $created_by;?>" name="created_by"/>
<input type="hidden" value="<?php echo $field_id;?>" name="field_id"/>
		



	
							<?php
		
							 
		if($clearance_form_val->cessation_of_employement=='Resignation'){
		$resignation="checked";
				}else{
		$resignation="disabled";
		}		
		if($clearance_form_val->cessation_of_employement=='Termination'){
		$termination="checked";
				}else{
		$termination="disabled";
		}	
		if($clearance_form_val->cessation_of_employement=='Expiry-of-contract'){
		$expiry_of_contract="checked";
				}else{
		$expiry_of_contract="disabled";
		}	
		if($clearance_form_val->cessation_of_employement=='Other/s-pls.specify'){
		$other_pls_specify="checked";
		$style='style="display:block;"';
				}else{
		$other_pls_specify="disabled";
			$style='style="display:none;"';
		}	
		
//<input class="form-control"  placeholder="Signature" type="text" name="department_signature" value="'.$clearance_form_val->department_signature.'"/></td>
							 
							 $html='';
							 $html.='<div style="float:right;cursor:pointer"><i class="icon-printer" style="color:#249E92;font-size: 3em;" id="btnPrint"></i></div>';
		$html.='<div class="panel panel-flat" id="dvContainer"><div class="panel-heading text-center"><h5 class="panel-title">					 
		<strong>Clearance Form For '.$clearance_form_val->employee_name.'<br>(Employee/Contract)</strong></h5><div class="pull-right mr-10">Date Processed: '.date('d F Y',strtotime($clearance_form[0]->created_at)).'</div>	</div>';
		$html.='<div class="panel-body">';
		
		$html.='<div class="col-lg-12 success"	><table class=" bg-teal-300 table table-md table-bordered "><tbody>
	
	
		<tr class=""><td>Employee Name</td><td>'.$clearance_form_val->employee_name.'</td><td>Visa</td><td>'.$clearance_form_val->entity_agency.'</td></tr>
			
		<tr class=""><td>Designation</td><td>'.$clearance_form_val->employee_designation.'</td><td>Department</td><td>'.$clearance_form_val->employee_department.'</td></tr>
		
		<tr class=""><td>Joining Date</td><td>'.format_date('d F Y',$clearance_form_val->joining_date).'</td><td>Resignation Date</td><td>'.format_date('d F Y',$clearance_form_val->resignation_date).'</td></tr>
		<tr class=""><td>Last Working Day</td><td>'.format_date('d F Y',$clearance_form_val->last_working_day).'</td><td></td><td></td></tr>	
		
		<tr class=""><td>Nature of Cessation of Employement<br> **pls.check applicable box**</td><td colspan="3">
		<table class="table table-md table-bordered ">
		<input type="hidden" name="user_id" value="'.$clearance_form_val->user_id.'">
		<input type="hidden" name="employee_id" value="'.$clearance_form_val->employee_id.'">
		<input type="hidden" name="employee_name" value="'.$clearance_form_val->employee_name.'">		
		<input type="hidden" name="visa" value="'.$clearance_form_val->entity_agency.'">
		<input type="hidden" name="designation" value="'.$clearance_form_val->employee_designation.'">
		<input type="hidden" name="department" value="'.$clearance_form_val->employee_department.'">
		<input type="hidden" name="resignation_date" value="'.$clearance_form_val->resignation_date.'">
		<input type="hidden" name="joining_date" value="'.$clearance_form_val->joining_date.'">
		<input type="hidden" name="last_working_day" value="'.$clearance_form_val->last_working_day.'">
		
		
		<tr><div class="form-group">
							
										<label class="radio-inline radio-right">
											<input name="cessation_of_employement" '.$resignation.' type="radio" value="Resignation">
											Resignation
										</label>

										<label class="radio-inline radio-right">
											<input name="cessation_of_employement" '.$termination.' type="radio" value="Termination">
											Termination
										</label>
										
									<label class="radio-inline radio-right">
											<input name="cessation_of_employement" '.$expiry_of_contract.' type="radio" value="Expiry-of-contract">
											Expiry of Contract
										</label>
										
										<label class="radio-inline radio-right">
											<input name="cessation_of_employement" '.$other_pls_specify.' type="radio"  value="Other/s-pls.specify">
											Other/s pls.specify
										</label>
										
										<input '.$style.' class="mt-10 form-input text-teal" name="cessation_of_employement_other" type="text"  value="'.$clearance_form_val->cessation_of_employement_other.'" readonly>
									</div></tr>
		</table>		
		</td></tr>
		<tr><td colspan="3">Persons in charge are required to sign in the respective sections and/or department below:<td><tr>';		
		$html.='</tbody></table></div>';
		
		if($head_p_name=='Department Head'){
		$html.='<div class="mt-10 col-lg-6 " style="min-height: 35em;"><table class="table bg-teal-300 table-bordered  table-md"><tbody>
		<tr class=""><td colspan="3"><strong>1.Own Department\'s Clearance</strong></td></tr>	
		<tr class=""><td>Bitrix Account</td><td><input  placeholder="Cleared/Not Cleared" class="form-control" type="text" name="department_bitrix_account" value="'.$clearance_form_val->department_bitrix_account.'"/></td></tr>
<tr class=""><td>Awok Logistics System (ALS)</td><td><input placeholder="Cleared/Not Cleared" class="form-control" type="text" name="department_awok_logistics_system" value="'.$clearance_form_val->department_awok_logistics_system.'"/></td></tr>
<tr class=""><td>RTA Fine</td><td><input class="form-control"  placeholder="Cleared/Not Cleared" type="text" name="department_rta_fine" value="'.$clearance_form_val->department_rta_fine.'"/></td></tr>
<tr class=""><td>Vehicle Damage / Fines</td><td><input  placeholder="Cleared/Not Cleared" class="form-control" type="text" name="department_vehicle_damage_fines" value="'.$clearance_form_val->department_vehicle_damage_fines.'"/></td></tr>
<tr class=""><td>Etisalat Bills Outstanding</td><td><input  placeholder="Cleared/Not Cleared" class="form-control" type="text" name="department_etisalat_bills_outstanding" value="'.$clearance_form_val->department_etisalat_bills_outstanding.'"/></td></tr>
<tr class=""><td>Remarks</td><td><input class="form-control"  placeholder="Remarks" type="text" name="department_remarks" value="'.$clearance_form_val->department_remarks.'"/></td></tr>
<tr class=""><td>Name</td><td><input class="form-control"  placeholder="Name" type="text" name="department_name" value="'.$clearance_form_val->department_name.'"/></td></tr>
<tr class=""><td>Signature</td><td><div class="wrapper">
  <canvas id="signature-pad" class="signature-pad" width=400 height=100></canvas>
</div>
<button id="clear" type="button" class="btn bg-teal-700">Clear</button></td></tr>		
	<tr><td>Date</td><td><input class="form-control signed_date"  placeholder="Date" type="text" name="department_date" value="'.$clearance_form_val->department_date.'"/></td></tr>	
		</tbody></table></div>';}
else{
			$html.='<div class="mt-10 col-lg-6 " style="min-height: 35em;"><table class="table bg-teal-300 table-bordered  table-md"><tbody>
		<tr class=""><td colspan="3"><strong>1.Own Department\'s Clearance</strong></td></tr>	
		<tr class=""><td>Bitrix Account</td><td><input class="form-control" type="hidden" name="department_bitrix_account" value="'.$clearance_form_val->department_bitrix_account.'"/>'.$clearance_form_val->department_bitrix_account.'</td></tr>
<tr class=""><td>Awok Logistics System (ALS)</td><td><input class="form-control" type="hidden" name="department_awok_logistics_system" value="'.$clearance_form_val->department_awok_logistics_system.'"/>'.$clearance_form_val->department_awok_logistics_system.'</td></tr>
<tr class=""><td>RTA Fine</td><td><input class="form-control" type="hidden" name="department_rta_fine" value="'.$clearance_form_val->department_rta_fine.'"/>'.$clearance_form_val->department_rta_fine.'</td></tr>
<tr class=""><td>Vehicle Damage / Fines</td><td><input class="form-control" type="hidden" name="department_vehicle_damage_fines" value="'.$clearance_form_val->department_vehicle_damage_fines.'"/>'.$clearance_form_val->department_vehicle_damage_fines.'</td></tr>
<tr class=""><td>Etisalat Bills Outstanding</td><td><input class="form-control" type="hidden" name="department_etisalat_bills_outstanding" value="'.$clearance_form_val->department_etisalat_bills_outstanding.'"/>'.$clearance_form_val->department_etisalat_bills_outstanding.'</td></tr>
<tr class=""><td>Remarks</td><td><input class="form-control" type="hidden" name="department_remarks" value="'.$clearance_form_val->department_remarks.'"/>'.$clearance_form_val->department_remarks.'</td></tr>
<tr class=""><td>Name</td><td><input class="form-control" type="hidden" name="department_name" value="'.$clearance_form_val->department_name.'"/>'.$clearance_form_val->department_name.'</td></tr>
<tr class=""><td>Signature</td><td><input class="form-control" type="hidden" name="department_signature" value="'.$clearance_form_val->department_signature.'"/><img src="'.$clearance_form_val->department_signature.'"/></td></tr>		
	<tr><td>Date</td><td><input class="form-control" type="hidden" name="department_date" value="'.$clearance_form_val->department_date.'"/>'.$clearance_form_val->department_date.'</td></tr>	
		</tbody></table></div>';
		}
		
		if($head_p_name=='IT Head'){
		$html.='<div class="mt-10 col-lg-6 " style="min-height: 35em;"><table class="table bg-teal-300 table-bordered  table-md"><tbody>
		<tr class=""><td colspan="3"><strong>2.IT Clearance</strong></td></tr>	
		<tr class=""><td>Bitrix</td><td><input class="form-control" type="text"  placeholder="Cleared/Not Cleared" name="it_bitrix" value="'.$clearance_form_val->it_bitrix.'"/></td></tr>
<tr class=""><td>Email</td><td><input class="form-control" type="text"  placeholder="Cleared/Not Cleared" name="it_email" value="'.$clearance_form_val->it_email.'"/></td></tr>
<tr class=""><td>Skype (if official)</td><td><input class="form-control"  placeholder="Cleared/Not Cleared" type="text" name="it_skype" value="'.$clearance_form_val->it_skype.'"/></td></tr>
<tr class=""><td>1c (if applicable)</td><td><input class="form-control"  placeholder="Cleared/Not Cleared" type="text" name="it_ic" value="'.$clearance_form_val->it_ic.'"/></td></tr>
<tr class=""><td>Awok.com Access</td><td><input class="form-control"  placeholder="Cleared/Not Cleared" type="text" name="it_awok_com_access" value="'.$clearance_form_val->it_awok_com_access.'"/></td></tr>
<tr class=""><td>Other/s (pls specify)</td><td><input class="form-control"  placeholder="Cleared/Not Cleared" type="text" name="it_other_specify" value="'.$clearance_form_val->it_other_specify.'"/></td></tr>
<tr class=""><td>Desktop</td><td><input class="form-control" type="text"  placeholder="Cleared/Not Cleared" name="it_desktop" value="'.$clearance_form_val->it_desktop.'"/></td></tr>
<tr class=""><td>Laptop</td><td><input class="form-control" type="text"  placeholder="Cleared/Not Cleared" name="it_laptop" value="'.$clearance_form_val->it_laptop.'"/></td></tr>
<tr class=""><td>Mouse</td><td><input class="form-control" type="text"  placeholder="Cleared/Not Cleared" name="it_mouse" value="'.$clearance_form_val->it_mouse.'"/></td></tr>
<tr class=""><td>Headset</td><td><input class="form-control" type="text"  placeholder="Cleared/Not Cleared" name="it_headset" value="'.$clearance_form_val->it_headset.'"/></td></tr>
<tr class=""><td>Keyboard</td><td><input class="form-control" type="text"  placeholder="Cleared/Not Cleared" name="it_keyboard" value="'.$clearance_form_val->it_keyboard.'"/></td></tr>
<tr class=""><td>Remarks</td><td><input class="form-control" type="text"  placeholder="Remarks"  name="it_remarks" value="'.$clearance_form_val->it_remarks.'"/></td></tr>
<tr class=""><td>Name</td><td><input class="form-control" type="text" name="it_name"  placeholder="Name" value="'.$clearance_form_val->it_name.'"/></td></tr>
<tr class=""><td>Signature</td><td><div class="wrapper">
  <canvas id="signature-pad" class="signature-pad" width=400 height=100></canvas>
</div>
<button id="clear" type="button" class="btn bg-teal-700">Clear</button></td></tr>		
	<tr><td>Date</td><td><input class="form-control signed_date" type="text" name="it_date"  placeholder="Date" value="'.$clearance_form_val->it_date.'"/></td></tr>	
		</tbody></table></div> <div class="clear-fix"></div>';}else{
			
			
			$html.='<div class="mt-10 col-lg-6 " style="min-height: 35em;"><table class="table bg-teal-300 table-bordered  table-md"><tbody>
		<tr class=""><td colspan="3"><strong>2.IT Clearance</strong></td></tr>	
		<tr class=""><td>Bitrix</td><td><input class="form-control" type="hidden" name="it_bitrix" value="'.$clearance_form_val->it_bitrix.'"/>'.$clearance_form_val->it_bitrix.'</td></tr>
<tr class=""><td>Email</td><td><input class="form-control" type="hidden" name="it_email" value="'.$clearance_form_val->it_email.'"/>'.$clearance_form_val->it_email.'</td></tr>
<tr class=""><td>Skype (if official)</td><td><input class="form-control" type="hidden" name="it_skype" value="'.$clearance_form_val->it_skype.'"/>'.$clearance_form_val->it_skype.'</td></tr>
<tr class=""><td>1c (if applicable)</td><td><input class="form-control" type="hidden" name="it_ic" value="'.$clearance_form_val->it_ic.'"/>'.$clearance_form_val->it_ic.'</td></tr>
<tr class=""><td>Awok.com Access</td><td><input class="form-control" type="hidden" name="it_awok_com_access" value="'.$clearance_form_val->it_awok_com_access.'"/>'.$clearance_form_val->it_awok_com_access.'</td></tr>
<tr class=""><td>Other/s (pls specify)</td><td><input class="form-control" type="hidden" name="it_other_specify" value="'.$clearance_form_val->it_other_specify.'"/>'.$clearance_form_val->it_other_specify.'</td></tr>
<tr class=""><td>Desktop</td><td><input class="form-control" type="hidden" name="it_desktop" value="'.$clearance_form_val->it_desktop.'"/>'.$clearance_form_val->it_desktop.'</td></tr>
<tr class=""><td>Laptop</td><td><input class="form-control" type="hidden" name="it_laptop" value="'.$clearance_form_val->it_laptop.'"/>'.$clearance_form_val->it_laptop.'</td></tr>
<tr class=""><td>Mouse</td><td><input class="form-control" type="hidden" name="it_mouse" value="'.$clearance_form_val->it_mouse.'"/>'.$clearance_form_val->it_mouse.'</td></tr>
<tr class=""><td>Headset</td><td><input class="form-control" type="hidden" name="it_headset" value="'.$clearance_form_val->it_headset.'"/>'.$clearance_form_val->it_headset.'</td></tr>
<tr class=""><td>Keyboard</td><td><input class="form-control" type="hidden" name="it_keyboard" value="'.$clearance_form_val->it_keyboard.'"/>'.$clearance_form_val->it_keyboard.'</td></tr>
<tr class=""><td>Remarks</td><td><input class="form-control" type="hidden" name="it_remarks" value="'.$clearance_form_val->it_remarks.'"/>'.$clearance_form_val->it_remarks.'</td></tr>
<tr class=""><td>Name</td><td><input class="form-control" type="hidden" name="it_name" value="'.$clearance_form_val->it_name.'"/>'.$clearance_form_val->it_name.'</td></tr>
<tr class=""><td>Signature</td><td><input class="form-control" type="hidden" name="it_signature" value="'.$clearance_form_val->it_signature.'"/><img src="'.$clearance_form_val->it_signature.'"/></td></tr>		
	<tr><td>Date</td><td><input class="form-control" type="hidden" name="it_date" value="'.$clearance_form_val->it_date.'"/>'.$clearance_form_val->it_date.'</td></tr>	
		</tbody></table></div> <div class="clear-fix"></div>';
			
			
			
			
	
		}

		if($head_p_name=='HR Head'){
		$html.='<div class="mt-10 col-lg-6 " style="min-height: 35em;"><table class="table bg-teal-300 table-bordered  table-md"><tbody>
		<tr class=""><td colspan="3"><strong>3.HR Clearance</strong></td></tr>	
		<tr class=""><td>Labour Card</td><td><input class="form-control" type="text" placeholder="Cleared/Not Cleared"  name="hr_labour_card" value="'.$clearance_form_val->hr_labour_card.'"/></td></tr>
		<tr class=""><td>Emirates Card</td><td><input class="form-control" type="text"  placeholder="Cleared/Not Cleared" name="hr_emirates_card" value="'.$clearance_form_val->hr_emirates_card.'"/></td></tr>
		<tr class=""><td>Medical Card</td><td><input class="form-control" type="text"  placeholder="Cleared/Not Cleared" name="hr_medical_card" value="'.$clearance_form_val->hr_medical_card.'"/></td></tr>
		<tr class=""><td>Exit Interview</td><td><input class="form-control" type="text"  placeholder="Cleared/Not Cleared" name="hr_exit_interview" value="'.$clearance_form_val->hr_exit_interview.'"/></td></tr>

<tr class=""><td>Remarks</td><td><input class="form-control" type="text" name="hr_remarks" placeholder="Remarks"  value="'.$clearance_form_val->hr_remarks.'"/></td></tr>
<tr class=""><td>Name</td><td><input class="form-control" type="text" name="hr_name"  placeholder="Name" value="'.$clearance_form_val->hr_name.'"/></td></tr>
<tr class=""><td>Signature</td><td><div class="wrapper">
  <canvas id="signature-pad" class="signature-pad" width=400 height=100></canvas>
</div>
<button id="clear" type="button" class="btn bg-teal-700">Clear</button></td></tr>		
	<tr><td>Date</td><td><input class="form-control signed_date" type="text" name="hr_date"  placeholder="Date"  value="'.$clearance_form_val->hr_date.'"/></td></tr>	
		</tbody></table></div>';}else{
			
			
			$html.='<div class="mt-10 col-lg-6 " style="min-height: 35em;"><table class="table bg-teal-300 table-bordered  table-md"><tbody>
		<tr class=""><td colspan="3"><strong>3.HR Clearance</strong></td></tr>	
		<tr class=""><td>Labour Card</td><td><input class="form-control" type="hidden" name="hr_labour_card" value="'.$clearance_form_val->hr_labour_card.'"/>'.$clearance_form_val->hr_labour_card.'</td></tr>
		<tr class=""><td>Emirates Card</td><td><input class="form-control" type="hidden" name="hr_emirates_card" value="'.$clearance_form_val->hr_emirates_card.'"/>'.$clearance_form_val->hr_emirates_card.'</td></tr>
		<tr class=""><td>Medical Card</td><td><input class="form-control" type="hidden" name="hr_medical_card" value="'.$clearance_form_val->hr_medical_card.'"/>'.$clearance_form_val->hr_medical_card.'</td></tr>
		<tr class=""><td>Exit Interview</td><td><input class="form-control" type="hidden" name="hr_exit_interview" value="'.$clearance_form_val->hr_exit_interview.'"/>'.$clearance_form_val->hr_exit_interview.'</td></tr>

<tr class=""><td>Remarks</td><td><input class="form-control" type="hidden" name="hr_remarks" value="'.$clearance_form_val->hr_remarks.'"/>'.$clearance_form_val->hr_remarks.'</td></tr>
<tr class=""><td>Name</td><td><input class="form-control" type="hidden" name="hr_name" value="'.$clearance_form_val->hr_name.'"/>'.$clearance_form_val->hr_name.'</td></tr>
<tr class=""><td>Signature</td><td><input class="form-control" type="hidden" name="hr_signature" value="'.$clearance_form_val->hr_signature.'"/><img src="'.$clearance_form_val->hr_signature.'"/></td></tr>		
	<tr><td>Date</td><td><input class="form-control" type="hidden" name="hr_date" value="'.$clearance_form_val->hr_date.'"/>'.$clearance_form_val->hr_date.'</td></tr>	
		</tbody></table></div>';
			
			
		}
		if($head_p_name=='Accounts Manager' || $head_p_name=='CFO'){
			$html.='<div class="mt-10 col-lg-6 " style="min-height: 35em;"><table class="table bg-teal-300 table-bordered  table-md"><tbody>
		<tr class=""><td colspan="3"><strong>4.Account\'s Clearance</strong></td></tr>	
		<tr class=""><td>Claims Settlement (1511)</td><td><input class="form-control" placeholder="Cleared/Not Cleared"  type="text" name="account_claims_settlement_1511" value="'.$clearance_form_val->account_claims_settlement_1511.'"/></td></tr>
		<tr class=""><td>Advance to Employee for company purpose(3630) <br> 3630.01(AED)/3630.02(AED) (other currency)</td><td><input class="form-control" type="text" name="account_advance_to_employee_for_company_purpose" placeholder="Cleared/Not Cleared"  value="'.$clearance_form_val->account_advance_to_employee_for_company_purpose.'"/></td></tr>
		<tr class=""><td>Settlement with Personnel On Payment.(3520)</td><td><input placeholder="Cleared/Not Cleared"  class="form-control" type="text" name="account_settlement_with_personnel_on_payment_3520" value="'.$clearance_form_val->account_settlement_with_personnel_on_payment_3520.'"/></td></tr>
		<tr class=""><td>Settlement with Personnel Outsources.(3523)</td><td><input  placeholder="Cleared/Not Cleared" class="form-control" type="text" name="account_settlement_with_personnel_outsources_3523" value="'.$clearance_form_val->account_settlement_with_personnel_outsources_3523.'"/></td></tr>
<tr class=""><td>Other/s (if any)</td><td><input class="form-control" type="text"  placeholder="Cleared/Not Cleared" name="account_other_specify" value="'.$clearance_form_val->account_other_specify.'"/></td></tr>
<tr class=""><td>Total Amount Payable</td><td><input class="form-control" type="text" name="account_total_amount_payable" value="'.$clearance_form_val->account_total_amount_payable.'"/></td></tr>

<tr class=""><td>Prepared By</td><td><input class="form-control" type="text" name="account_prepardby" value="'.$clearance_form_val->account_prepardby.'"/></td></tr>
<tr class=""><td>Checked By</td><td><input class="form-control" type="text" name="account_checkedby" value="'.$clearance_form_val->account_checkedby.'"/></td></tr>

<tr class=""><td>Remarks</td><td><input class="form-control" type="text" name="account_remarks"  placeholder="Remarks" value="'.$clearance_form_val->account_remarks.'"/></td></tr>
<tr class=""><td>Name</td><td><input class="form-control" type="text" name="account_name"  placeholder="Name" value="'.$clearance_form_val->account_name.'"/></td></tr>
<tr class=""><td>Signature</td><td><div class="wrapper">
  <canvas id="signature-pad" class="signature-pad" width=400 height=100></canvas>
</div>
<button id="clear" type="button" class="btn bg-teal-700">Clear</button></td></tr>		
	<tr><td>Date</td><td><input class="form-control signed_date" type="text" name="account_date" placeholder="Date"  value="'.$clearance_form_val->account_date.'"/></td></tr>	
		</tbody></table></div>';}else{
			
			$html.='<div class="mt-10 col-lg-6 " style="min-height: 35em;"><table class="table bg-teal-300 table-bordered  table-md"><tbody>
		<tr class=""><td colspan="3"><strong>4.Account\'s Clearance</strong></td></tr>	
		<tr class=""><td>Claims Settlement (1511)</td><td><input class="form-control" type="hidden" name="account_claims_settlement_1511" value="'.$clearance_form_val->account_claims_settlement_1511.'"/>'.$clearance_form_val->account_claims_settlement_1511.'</td></tr>
		<tr class=""><td>Advance to Employee for company purpose(3630) <br> 3630.01(AED)/3630.02(AED) (other currency)</td><td><input class="form-control" type="hidden" name="account_advance_to_employee_for_company_purpose" value="'.$clearance_form_val->account_advance_to_employee_for_company_purpose.'"/>'.$clearance_form_val->account_advance_to_employee_for_company_purpose.'</td></tr>
		<tr class=""><td>Settlement with Personnel On Payment.(3520)</td><td><input class="form-control" type="hidden" name="account_settlement_with_personnel_on_payment_3520" value="'.$clearance_form_val->account_settlement_with_personnel_on_payment_3520.'"/>'.$clearance_form_val->account_settlement_with_personnel_on_payment_3520.'</td></tr>
		<tr class=""><td>Settlement with Personnel Outsources.(3523)</td><td><input class="form-control" type="hidden" name="account_settlement_with_personnel_outsources_3523" value="'.$clearance_form_val->account_settlement_with_personnel_outsources_3523.'"/>'.$clearance_form_val->account_settlement_with_personnel_outsources_3523.'</td></tr>
<tr class=""><td>Other/s (if any)</td><td><input class="form-control" type="hidden" name="account_other_specify" value="'.$clearance_form_val->account_other_specify.'"/>'.$clearance_form_val->account_other_specify.'</td></tr>
<tr class=""><td>Total Amount Payable</td><td><input class="form-control" type="hidden" name="account_total_amount_payable" value="'.$clearance_form_val->account_total_amount_payable.'"/>'.$clearance_form_val->account_total_amount_payable.'</td></tr>

<tr class=""><td>Prepared By</td><td><input class="form-control" type="hidden" name="account_prepardby" value="'.$clearance_form_val->account_prepardby.'"/>'.$clearance_form_val->account_prepardby.'</td></tr>
<tr class=""><td>Checked By</td><td><input class="form-control" type="hidden" name="account_checkedby" value="'.$clearance_form_val->account_checkedby.'"/>'.$clearance_form_val->account_checkedby.'</td></tr>

<tr class=""><td>Remarks</td><td><input class="form-control" type="hidden" name="account_remarks" value="'.$clearance_form_val->account_remarks.'"/>'.$clearance_form_val->account_remarks.'</td></tr>
<tr class=""><td>Name</td><td><input class="form-control" type="hidden" name="account_name" value="'.$clearance_form_val->account_name.'"/>'.$clearance_form_val->account_name.'</td></tr>
<tr class=""><td>Signature</td><td><input class="form-control" type="hidden" name="account_signature" value="'.$clearance_form_val->account_signature.'"/><img src="'.$clearance_form_val->account_signature.'"/></td></tr>		
	<tr><td>Date</td><td><input class="form-control " type="hidden" name="account_date" value="'.$clearance_form_val->account_date.'"/>'.$clearance_form_val->account_date.'</td></tr>	
		</tbody></table></div>
		
		';
		
		}
		
		 $html.='<div class="clearfix"></div>
		<div class="mt-20 col-lg-2  pull-right">
								<button type="submit" class="btn bg-teal-400 btn-block">Approve</button>
							</div>';
		echo $html;
							


?>
							
							
							
</div>
							
							
							
							</div>
						</div></div>
					</form>
					<?php } else if($form_type=='leaveconversion_form'){?>
					
					
					
					<?php } ?>
					
					<!-- /simple login form -->

<div id="html_form"></div>



					<!-- Footer -->
					<div class="footer text-muted text-center">
				<?php if($system[0]->enable_current_year=='yes'):?><?php echo date('Y');?> <?php endif;?> © <?php echo $system[0]->footer_text;?>
                <?php if($system[0]->enable_page_rendered=='yes'):?>
                <br>Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?>
                <?php endif; ?>
					</div>
					
					
					
					<!-- /footer -->

				</div>
				<!-- /content area -->

			
			<!-- /main content -->

	<!-- /page container -->
<!-- Vendor JS --> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/jquery/jquery-3.2.1.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/tether/js/tether.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/bootstrap/js/bootstrap.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/toastr/toastr.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/pickers/pickadate/picker.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/pickers/pickadate/picker.date.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/notifications/sweet_alert.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/signature/signature_pad.js"></script>

<script type="text/javascript">var base_url = '<?php echo base_url(); ?>';</script>
<script type="text/javascript">
$(document).ready(function(){
	
	<?php 
if($form_type=='leaveconversion_form'){?>


	
    	$.ajax({	
		url : "<?php echo base_url(); ?>/timesheet/read_leave_conversion/",
		type: "GET",
		data: 'jd=1&is_ajax=1&mode=modal&approve_link=<?php echo $approve_link;?>&decline_link=<?php echo $decline_link;?>&data=leave_conversion_approval&leave_conversion_id=<?php echo $conversion_id 	;?>&hr_access=Yes',
		success: function (response) {
			if(response) {
				$("#ajax_modal_payview").html(response);
			}
		}
		});
	   $('.edit-modal-data-payrol').modal('show');
	


<?php } ?>


	
	$('.signed_date').pickadate({
        format: "dd mmmm yyyy",
        labelMonthNext: 'Go to the next month',
        labelMonthPrev: 'Go to the previous month',
        labelMonthSelect: 'Pick a month from the dropdown',
        labelYearSelect: 'Pick a year from the dropdown',
        selectMonths: true,
        selectYears: 100	
    });	
	
	
	toastr.options.closeButton = <?php echo $system[0]->notification_close_btn;?>;
	toastr.options.progressBar = <?php echo $system[0]->notification_bar;?>;
	toastr.options.timeOut = 3000;
	toastr.options.preventDuplicates = true;
	toastr.options.positionClass = "<?php echo $system[0]->notification_position;?>";
	
	
	
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
	 
	 
	 
	 	$("#hrm-form").submit(function(e){
		e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		//var reason = $("#reason").code();
		if (signaturePad.isEmpty()) {
		return alert("Please provide a signature first.");
	  }
	  
	   var data_signature = signaturePad.toDataURL('image/jpeg');
	    console.log(data_signature);
		$.ajax({
			type: "POST",
			url: base_url+'index/update_clearance_form/',
			data: obj.serialize()+"&is_ajax=1&add_type=clearance_form&form="+action+"&data_signature="+data_signature,
			cache: false,
			success: function (JSON) {
				
				//$('#html_form').html(JSON.message);
				
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
			
				} else {
						toastr.success(JSON.result);
			            $('.save').prop('disabled', false);
			
		/*	
			swal({
            title: "Approved successfully",
            text: "Do you want to stay here?",
            type: "success",
            showCancelButton: true,
            confirmButtonColor: "#4DB6AC",
            confirmButtonText: "Yes Stay here!",
            cancelButtonText: "No, close it!",
            //closeOnConfirm: true,
           // closeOnCancel: true
        },
        function(isConfirm){
            if (isConfirm) {
             javascript:self.close()
            }
            else {
            javascript:self.close()
            }
        });*/
			
			
			
			
			
				}
			}
		});
	});
	
	
	
	
	 
});

var canvas = document.getElementById('signature-pad');

// Adjust canvas coordinate space taking into account pixel ratio,
// to make it look crisp on mobile devices.
// This also causes canvas to be cleared.
function resizeCanvas() {
    // When zoomed out to less than 100%, for some very strange reason,
    // some browsers report devicePixelRatio as less than 1
    // and only part of the canvas is cleared then.
    var ratio =  Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext("2d").scale(ratio, ratio);
}

window.onresize = resizeCanvas;
resizeCanvas();

var signaturePad = new SignaturePad(canvas, {
  backgroundColor: 'rgb(255, 255, 255)' // necessary for saving image as JPEG; can be removed is only saving as PNG or SVG
});
/*
document.getElementById('save-jpeg').addEventListener('click', function () {
  if (signaturePad.isEmpty()) {
    return alert("Please provide a signature first.");
  }
  
  var data = signaturePad.toDataURL('image/jpeg');
  console.log(data);
  window.open(data);
});*/

document.getElementById('clear').addEventListener('click', function () {
  signaturePad.clear();
});



var context = canvas.getContext('2d');
var img = new Image();

img.onload = function() {
  context.drawImage(this, 0, 0, canvas.width, canvas.height);
}


<?php

if($head_p_name=='IT Head'){		
$signature=$clearance_form_val->it_signature;
}else if($head_p_name=='Department Head'){		
$signature=$clearance_form_val->department_signature;
}else if($head_p_name=='HR Head'){		
$signature=$clearance_form_val->hr_signature;
}else{
$signature=$clearance_form_val->account_signature;
}

?>
img.src = "<?php echo $signature;?>";



</script>

</body>
</html>

