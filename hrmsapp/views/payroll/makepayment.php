<?php
/* Generate Payslip view
*/
?>
<?php $session = $this->session->userdata('username');?>

<!-- Invoice template -->

<?php

$payment_month = strtotime($pay_date);
$p_month = date('F Y',$payment_month);
?>

<form class="m-b-1" action="<?php echo site_url("payroll/add_pay_monthly") ?>" method="post" name="pay_monthly" id="pay_monthly">

					 <div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong>Payslip</strong>
							</h5>
							
							 <!--<div class="heading-elements">							 
        <a href="<?php echo site_url();?>payroll/pdf_create/<?php echo $gd;?>/<?php echo $make_payment_id;?>/" onclick="javascript:alert('This payslip is password protected. Please enter your employee id to see this.');" class="btn bg-teal-400 btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="PDF"><span <i="" class="icon-file-pdf"></span></a> 
							</div>-->
							
							
						
							<div class="heading-elements">
								<button type="submit" class="btn bg-teal-400 btn-xs heading-btn save"><i class="icon-file-check position-left"></i> Save</button>
							</div>
						
						
						
						
						</div>	


  <div class="panel-body">

          <input type="hidden" name="department_id" value="<?php echo $department_id;?>" />
          <input type="hidden" name="company_id" value="<?php echo $company_id;?>" />
          <input type="hidden" name="location_id" value="<?php echo $location_id;?>" />
          <input type="hidden" name="designation_id" value="<?php echo $designation_id;?>" />
       
          <input type="hidden" name="gross_salary" class="form-control" value="<?php echo $gross_salary;?>" readonly>
          <input type="hidden" name="net_salary" class="form-control" value="<?php echo $net_salary;?>" readonly>
          <input type="hidden" id="emp_id" value="<?php echo $user_id?>" name="emp_id">
          <input type="hidden" value="<?php echo $user_id;?>" name="u_id">
          <input type="hidden" value="<?php echo $basic_salary;?>" name="basic_salary">
          <input type="hidden" value="<?php echo $salary_template_id;?>" name="salary_template_id">
          <input type="hidden" value="<?php echo $payment_date;?>" name="pay_date" id="pay_date">

		 <input type="hidden" name="required_working_hours"  value="<?php echo $required_working_hours;?>"/>
		 <input type="hidden" name="total_working_hours"  value="<?php echo $total_working_hours;?>"/>
		 <input type="hidden" name="late_working_hours"  value="<?php echo $late_working_hours;?>"/>
	 	 <input type="hidden" name="rate_per_hour_contract_bonus"  value="<?php echo $rate_per_hour_contract_bonus;?>"/>
		 <input type="hidden" name="rate_per_hour_contract_only"  value="<?php echo $rate_per_hour_contract_only;?>"/>
		 <input type="hidden" name="rate_per_hour_basic_only"  value="<?php echo $rate_per_hour_basic_only;?>"/>
		 <input type="hidden" name="ot_day_rate"  value="<?php echo $ot_day_rate;?>"/>
		 <input type="hidden" name="ot_night_rate"  value="<?php echo $ot_night_rate;?>"/>
		 <input type="hidden" name="ot_holiday_rate"  value="<?php echo $ot_holiday_rate;?>"/>
		 <input type="hidden" name="payment_amount" id="payment_amount" value="<?php echo $total_salary;?>"/>
		 <input type="hidden" name="total_salary" value="<?php echo $total_salary;?>"/>
     
	 
	 <?php if($house_rent_allowance!=''): ?>
    <input type="hidden" name="house_rent_allowance" value="<?php echo $house_rent_allowance;?>">
	<?php else:?>
    <input type="hidden" name="house_rent_allowance" class="form-control" value="0">
    <?php endif;?>
    <?php if($other_allowance!=''): ?>
    <input type="hidden" name="other_allowance" value="<?php echo $other_allowance;?>">
	<?php else:?>
    <input type="hidden" name="other_allowance" class="form-control" value="0">
    <?php endif;?>
	<?php if($bonus!=''): ?>
    <input type="hidden" name="bonus" value="<?php echo $bonus;?>">
	<?php else:?>
    <input type="hidden" name="bonus" class="form-control" value="0">
    <?php endif;?>
	<?php if($additional_benefits!=''): ?>
    <input type="hidden" name="additional_benefits" value="<?php echo $additional_benefits;?>">
	<?php else:?>
    <input type="hidden" name="additional_benefits" class="form-control" value="0">
    <?php endif;?>
	<?php if($food_allowance!=''): ?>
    <input type="hidden" name="food_allowance" value="<?php echo $food_allowance;?>">
	<?php else:?>
    <input type="hidden" name="food_allowance" class="form-control" value="0">
    <?php endif;?>
    <?php if($travelling_allowance!=''): ?>
    <input type="hidden" name="travelling_allowance" value="<?php echo $travelling_allowance;?>">
	<?php else:?>
    <input type="hidden" name="travelling_allowance" class="form-control" value="0">
    <?php endif;?>
	
	
	
      <div class="panel">
        <div class="panel-heading p-b-none">
        <div class="panel-heading p-b-none" style="text-align: center;font-size: 1.5em;">
          <p><strong>Salary Month: </strong><?php echo date("F Y", strtotime($payment_date));?></p>
        </div>
        <div class="panel-body p-none m-b-10">
          <table class="table table-no-border table-condensed">
            <tbody>
              <tr>
                <td><strong class="help-split">Employee ID: </strong><?php echo $employee_id;?></td>
                <td><strong class="help-split">Employee Name: </strong><?php echo change_to_caps($first_name);?></td>
                <td><strong class="help-split">Payslip NO: </strong><?php echo $make_payment_id->make_payment_id+1;?></td>
              </tr>
			  
              <tr>
                <!--<td><strong class="help-split">Phone: </strong><?php echo $contact_no;?></td>-->
                <td><strong class="help-split">Date Of Joining: </strong><?php echo $this->Xin_model->set_date_format($date_of_joining);?></td>
                
				<td><strong class="help-split">Position Title: </strong><?php echo $designation_name;?></td>
				<td><strong class="help-split">No of Days in a Month: </strong><?php 
				$attendance=explode('-',$payment_date);
			    echo $days_count_per_month=cal_days_in_month(CAL_GREGORIAN,$attendance[1],$attendance[0]);
				
				?></td>
              </tr>
           
			  <tr>
			   <td><strong class="help-split">Total Working hours: </strong><?php echo $required_working_hours;?></td>
			   <td><strong class="help-split">Actual Working hours: </strong><?php echo $total_working_hours;?></td>
			    <td><strong class="help-split">Actual Days Worked: </strong><?php echo round($total_working_hours/$shift_hours,2);?></td>
			  
			  </tr>
			     <tr>
                <td><strong class="help-split">Department: </strong><?php echo $department_name;?></td>
                <td>
			
          <select name="payment_method" class="select2" data-plugin="select_hrm" placeholder="Choose Method...">
		  <option value="">Payment Through</option>
            <option value="1">Online</option>
            <option value="2">PayPal</option>
            <option value="3">Payoneer</option>
            <option value="4">Bank Transfer</option>
            <option value="5">Cheque</option>
            <option value="6">Cash</option>
          </select>
        
				
				
				</td>
                <td><strong class="help-split">&nbsp;</strong></td>
              </tr>
            </tbody>
          </table>
        </div>
    

	</div>
    </div>

<div class="row">
  <div class="panel-heading p-b-none" style="text-align: center;font-size: 1.5em;">
          <p><strong>Monthly Emoluments</strong>
        </div>

  <div class="col-md-6">
    <div class="box box-block bg-white">
      <div class="panel">
        <div class="panel-heading p-b-none">
          <h4 class="m-b-10"><strong>Earnings</strong></h4>
        </div>
        <div class="panel-body p-none">
		
		
          
		<div class="row">
		<div class="col-lg-12 mb-20">
		<div class="col-lg-6 no-padding-left"><strong>Basic Salary:</strong></div>
		<div class="col-lg-6 text-right"><?php echo $this->Xin_model->currency_sign(change_num_format($basic_salary));?></div>
		</div>	
	


     	<div class="col-lg-12 mb-20">
		<div class="col-lg-6 no-padding-left"><strong>Accomodation:</strong></div>
		<div class="col-lg-6 text-right"><?php echo $this->Xin_model->currency_sign(change_num_format($house_rent_allowance));?></div>
		</div>
		
		
		<div class="col-lg-12 mb-20">
		<div class="col-lg-6 no-padding-left"><strong>Transporation:</strong></div>
		<div class="col-lg-6 text-right"><?php echo $this->Xin_model->currency_sign(change_num_format($travelling_allowance));?></div>
		</div>
		
		<div class="col-lg-12 mb-20">
		<div class="col-lg-6 no-padding-left"><strong>Food Allowance:</strong></div>
		<div class="col-lg-6 text-right"><?php echo $this->Xin_model->currency_sign(change_num_format($food_allowance));?></div>
		</div>
		
		
		<div class="col-lg-12 mb-20">
		<div class="col-lg-6 no-padding-left"><strong>Other Allowance:</strong></div>
		<div class="col-lg-6 text-right"><?php echo $this->Xin_model->currency_sign(change_num_format($other_allowance));?></div>
		</div>
		
		
		<div class="col-lg-12 mb-20">
		<div class="col-lg-6 no-padding-left"><strong>Bonus:</strong></div>
		<div class="col-lg-6 text-right"><?php echo $this->Xin_model->currency_sign(change_num_format($bonus));?></div>
		</div>
		
		<div class="col-lg-12 mb-20">
		<div class="col-lg-6 no-padding-left"><strong>Additional Benefits:</strong></div>
		<div class="col-lg-6 text-right"><?php echo $this->Xin_model->currency_sign(change_num_format($additional_benefits));?></div>
		</div>
		<?php $add_sal=$net_salary;?>
		
		<div class="col-lg-12 mb-20">
		<div class="col-lg-6 no-padding-left"><strong>Total Salary:</strong></div>
		<div class="col-lg-6 text-right"><?php echo $this->Xin_model->currency_sign(change_num_format($net_salary));?></div>
		</div>
		<div class="col-lg-12 mb-20">
		<button type="button" id="salary_add" class="btn bg-teal-400 pull-right">Add Additions</button>
		</div>
		<div id="append_div_addition">

        </div>
		 
		 
		 <!-- <?php foreach($additonal_salary as $add_s){ 
				
				
				
				?>				  
				<tr>
                <td><strong><?php echo $add_s->type_name;  ?>:</strong> <span class="pull-right"><?php 
				
				if($add_s->amount==NULL){$am=0;}else {$am=$add_s->amount;}
				
				$add_sal+=$am;
				echo $this->Xin_model->currency_sign(change_num_format($am));?></span></td>
                </tr>
				<?php  }  ?>-->
				
				
		<div class="col-lg-12 mb-20">
		<div class="col-lg-6 no-padding-left"><strong>Total Compensation:</strong></div>
		<input type="hidden" class="total_compensation_add" value="<?php echo $add_sal;?>" />
		<div class="col-lg-6 text-right change_compensation_add"><?php echo $this->Xin_model->currency_sign(change_num_format($add_sal));?></div>
		</div>	
				
		</div>
		
		
         
		  <hr>
    
        </div>
      </div>
    </div>
  </div>

   <div class="col-md-6">
    <div class="box box-block bg-white">
      <div class="panel">
        <div class="panel-heading p-b-none">
          <h4 class="m-b-10"><strong>Deductions</strong></h4>
        </div>
        <div class="panel-body p-none">
          <div class="row">
		
		<div class="col-lg-12 mb-20">
		<div class="col-lg-6 no-padding-left"><strong>Absence(s) / Late(s) / Early Out(s):</strong></div>
		<div class="col-lg-6 text-right"><?php 
				$tt=$net_salary-$total_salary;
				
				echo $this->Xin_model->currency_sign(change_num_format($tt));?></div>
		</div>	
		
		<div class="col-lg-12 mb-20">
		<button type="button" id="salary_deduction" class="btn bg-teal-400 pull-right">Add Deductions</button>
		</div>
		<div id="append_div_deduction">

        </div>
		
		
		<div class="col-lg-12 mb-20">
		<div class="col-lg-6 no-padding-left"><strong>Total Deductions:</strong></div>
		<input type="hidden" class="total_compensation_deduct" value="<?php echo $tt;?>" />
		<div class="col-lg-6 text-right change_compensation_deduct"><?php echo $this->Xin_model->currency_sign(change_num_format($tt));?></div>
		</div>	
		
		</div>
       
	   
	   <div class="row">
	   
	   
	   <div class="col-lg-12 mt-20">
		<div class="col-lg-6 no-padding-left"><strong>Net Salary:</strong></div>
		<div class="col-lg-6 text-right change_net_salary"><?php echo $this->Xin_model->currency_sign(change_num_format($total_salary));?></div>
		</div>
		
		
	   </div>
	  
			   
			<!--<?php foreach($deduction_salary as $ded_s){ ?>				  
				<tr>
                <td><strong><?php echo $ded_s->type_name;  ?>:</strong> <span class="pull-right"><?php 
				
				if($ded_s->amount==NULL){$dm=0;}else {$dm=$ded_s->amount;}
				$tt+=$dm;
				echo $this->Xin_model->currency_sign(change_num_format($dm));?></span></td>
                </tr>
				<?php  }  ?>
			  -->
			
         
     
	
        </div>
      </div>
    </div>
	

  </div>
      
 
  </div>
  
</div><!-- pd--> 

  
  
  </div>
</form>
 <!--
					<div class="panel panel-white">
						<div class="panel-heading">
							<h6 class="panel-title">Editable invoice</h6>
							<div class="heading-elements">
								<button type="button" class="btn btn-default btn-xs heading-btn"><i class="icon-file-check position-left"></i> Save</button>
								<button type="button" class="btn btn-default btn-xs heading-btn"><i class="icon-printer position-left"></i> Print</button>
		                	</div>
						</div>

				 		<div id="invoice-editable" contenteditable="true">
							<div class="panel-body no-padding-bottom">
								<div class="row">
									<div class="col-sm-6 content-group">
										<img src="assets/images/logo_demo.png" class="content-group mt-10" alt="" style="width: 120px;">
			 							<ul class="list-condensed list-unstyled">
											<li>2269 Elba Lane</li>
											<li>Paris, France</li>
											<li>888-555-2311</li>
										</ul>
									</div>

									<div class="col-sm-6 content-group">
										<div class="invoice-details">
											<h5 class="text-uppercase text-semibold">Invoice #49029</h5>
											<ul class="list-condensed list-unstyled">
												<li>Date: <span class="text-semibold">January 12, 2015</span></li>
												<li>Due date: <span class="text-semibold">May 12, 2015</span></li>
											</ul>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6 col-lg-9 content-group">
										<span class="text-muted">Invoice To:</span>
			 							<ul class="list-condensed list-unstyled">
											<li><h5>Rebecca Manes</h5></li>
											<li><span class="text-semibold">Normand axis LTD</span></li>
											<li>3 Goodman Street</li>
											<li>London E1 8BF</li>
											<li>United Kingdom</li>
											<li>888-555-2311</li>
											<li><a href="#">rebecca@normandaxis.ltd</a></li>
										</ul>
									</div>

									<div class="col-md-6 col-lg-3 content-group">
										<span class="text-muted">Payment Details:</span>
										<ul class="list-condensed list-unstyled invoice-payment-details">
											<li><h5>Total Due: <span class="text-right text-semibold">$8,750</span></h5></li>
											<li>Bank name: <span class="text-semibold">Profit Bank Europe</span></li>
											<li>Country: <span>United Kingdom</span></li>
											<li>City: <span>London E1 8BF</span></li>
											<li>Address: <span>3 Goodman Street</span></li>
											<li>IBAN: <span class="text-semibold">KFH37784028476740</span></li>
											<li>SWIFT code: <span class="text-semibold">BPT4E</span></li>
										</ul>
									</div>
								</div>
							</div>

							<div >
							    <table class="table table-lg">
							        <thead>
							            <tr>
							                <th>Description</th>
							                <th class="col-sm-1">Rate</th>
							                <th class="col-sm-1">Hours</th>
							                <th class="col-sm-1">Total</th>
							            </tr>
							        </thead>
							        <tbody>
							            <tr>
							                <td>
							                	<h6 class="no-margin">Create UI design model</h6>
							                	<span class="text-muted">One morning, when Gregor Samsa woke from troubled.</span>
						                	</td>
							                <td>$70</td>
							                <td>57</td>
							                <td><span class="text-semibold">$3,990</span></td>
							            </tr>
							            <tr>
							                <td>
							                	<h6 class="no-margin">Support tickets list doesn't support commas</h6>
							                	<span class="text-muted">I'd have gone up to the boss and told him just what i think.</span>
						                	</td>
							                <td>$70</td>
							                <td>12</td>
							                <td><span class="text-semibold">$840</span></td>
							            </tr>
							            <tr>
							                <td>
							                	<h6 class="no-margin">Fix website issues on mobile</h6>
							                	<span class="text-muted">I am so happy, my dear friend, so absorbed in the exquisite.</span>
						                	</td>
							                <td>$70</td>
							                <td>31</td>
							                <td><span class="text-semibold">$2,170</span></td>
							            </tr>
							        </tbody>
							    </table>
							</div>

							<div class="panel-body">
								<div class="row invoice-payment">
									<div class="col-sm-7">
										<div class="content-group">
											<h6>Authorized person</h6>
											<div class="mb-15 mt-15">
												<img src="assets/images/signature.png" class="display-block" style="width: 150px;" alt="">
											</div>

											<ul class="list-condensed list-unstyled text-muted">
												<li>Eugene Kopyov</li>
												<li>2269 Elba Lane</li>
												<li>Paris, France</li>
												<li>888-555-2311</li>
											</ul>
										</div>
									</div>

									<div class="col-sm-5">
										<div class="content-group">
											<h6>Total due</h6>
											<div class="table-responsive no-border">
												<table class="table">
													<tbody>
														<tr>
															<th>Subtotal:</th>
															<td class="text-right">$7,000</td>
														</tr>
														<tr>
															<th>Tax: <span class="text-regular">(25%)</span></th>
															<td class="text-right">$1,750</td>
														</tr>
														<tr>
															<th>Total:</th>
															<td class="text-right text-primary"><h5 class="text-semibold">$8,750</h5></td>
														</tr>
													</tbody>
												</table>
											</div>

											<div class="text-right">
												<button type="button" class="btn btn-primary btn-labeled"><b><i class="icon-paperplane"></i></b> Send invoice</button>
											</div>
										</div>
									</div>
								</div>

								<h6>Other information</h6>
								<p class="text-muted">Thank you for using Limitless. This invoice can be paid via PayPal, Bank transfer, Skrill or Payoneer. Payment is due within 30 days from the date of delivery. Late payment is possible, but with with a fee of 10% per month. Company registered in England and Wales #6893003, registered office: 3 Goodman Street, London E1 8BF, United Kingdom. Phone number: 888-555-2311</p>
							</div>
						</div>
					</div>
					
<script type="text/javascript" src="<?php echo base_url();?>ckeditor/ckeditor.js"></script>

<script>

$(function() {

    // Setup CKEditor
    CKEDITOR.disableAutoInline = true;
    CKEDITOR.dtd.$removeEmpty['i'] = false;
    CKEDITOR.config.startupShowBorders = false;
    CKEDITOR.config.extraAllowedContent = 'table(*)';


    // Initialize inline editor
    var editor = CKEDITOR.inline('invoice-editable');
    
});

</script>-->
<Style>
#append_div_addition tr td {
    width: 32%;
    text-align: center;
    padding-right: 11px;
}
</style>





<script>

function delete_add_append_div(id){	
$('#add_parent_div_'+id).remove();			
keyupfn()
}


function delete_deduct_append_div(id){	
$('#deduct_parent_div_'+id).remove();			
keyupfn();
}

function keyupfn(){
	var sum_total=0;
	var deduction=0;
	var exist_sum_total=$('.total_compensation_add').val();
    var exist_deduct_total=$('.total_compensation_deduct').val();
	$(".add_action").each(function () {
		sum_total += +$(this).val();
	});
	
	$(".deduct_action").each(function () {
		deduction += +$(this).val();
	});

	
   // var theResult =  sum_total - deduction;

	
	
	var theResult =  sum_total+parseFloat(exist_sum_total);
    var theResult_dedcut =  deduction+parseFloat(exist_deduct_total);

	/*For Addition*/
	var parts = (+theResult).toFixed(2).split(".");
    var add_num = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + (+parts[1] ? "." + parts[1] : "");
	$(".change_compensation_add").html('AED'+add_num);

    /*For Deduction*/
	var parts1 = (+theResult_dedcut).toFixed(2).split(".");
    var deduct_num = parts1[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + (+parts1[1] ? "." + parts1[1] : "");
	$(".change_compensation_deduct").html('AED'+deduct_num);


	//$(".total_compensation_add").val(theResult);
	
	/*For Net Salary*/
	var theResult_net = theResult-theResult_dedcut;
	var parts3= (+theResult_net).toFixed(2).split(".");
    var net_sal = parts3[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + (+parts3[1] ? "." + parts3[1] : "");
	$(".change_net_salary").html('AED'+net_sal);
	
	
	
	$("#payment_amount").val(theResult_net);
}

$(document).ready(function(){
	
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
					
					                 
					toastr.success(JSON.result);
				
					window.location.assign('<?php echo site_url("payroll/") ?>payslip/id/<?php echo $make_payment_id->make_payment_id+1;?>');
				}
			}
		});
	});
	
	
$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	
$(document).on("keyup", function () {

	keyupfn();

});
	});		
$("#salary_add").click(function(e){	
        var field_id=$("#append_div_addition > div").length;
		var count_id=field_id+1;
		
 		$.ajax({
		url : "<?php echo site_url("payroll/dynamic_add_salary_type") ?>",
		type: "GET",
		data: 'count_id='+count_id,
		success: function (response) {
			if(response) {
				$("#append_div_addition").append(response);
			}
			
			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	        $('[data-plugin="select_hrm"]').select2({ width:'100%' });
			
			if(count_id!=0){
				$('#hide_comment').hide();
			}else{$('#hide_comment').show();}
		}
	});
	});
	
	
	$("#salary_deduction").click(function(e){	
        var field_id=$("#append_div_deduction > div").length;
		var count_id=field_id+1;
		
 		$.ajax({
		url : "<?php echo site_url("payroll/dynamic_deduct_salary_type") ?>",
		type: "GET",
		data: 'count_id='+count_id,
		success: function (response) {
			if(response) {
				$("#append_div_deduction").append(response);
			}
			
			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	        $('[data-plugin="select_hrm"]').select2({ width:'100%' });
			
			if(count_id!=0){
				$('#hide_comment').hide();
			}else{$('#hide_comment').show();}
		}
	});
	});
	

</script>