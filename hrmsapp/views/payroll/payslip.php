<?php
/* Payslip view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php
	$gd = 'sl';
	/*if(@$hourly_rate == '') {
		$gd = 'sl';
	} else {
		$gd = 'hr';
	}*/
	$add_sal=0;
?>

 <div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title"><strong>Payslip</strong></h5>
							<div class="heading-elements">
        <a href="<?php echo site_url();?>payroll/pdf_create/<?php echo $gd;?>/<?php echo $make_payment_id;?>/"  class="btn bg-teal-400 btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="PDF"><span <i="" class="icon-file-pdf"></span></a><!--onclick="javascript:alert('This payslip is password protected. Please enter your employee id to see this.');"-->
							</div>

						</div>

  <div class="panel-body">
   <?php
   $bank_account=employee_default_bankaccount($user_id);
   ?>
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
                <td><strong class="help-split">Employee Name: </strong><?php echo change_to_caps($first_name.' '.$middle_name.' '.$last_name);?></td>
                <td><strong class="help-split">Payslip NO: </strong><?php echo $make_payment_id;?></td>
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
			   <td><strong class="help-split">Actual Working hours: </strong><?php echo decimalHours($total_working_hours);?></td>
			    <td><strong class="help-split">Actual Days Worked: </strong><?php echo round($actual_days_worked,2);?></td>

			  </tr>
			     <tr>
				 <!--<td><strong class="help-split">Late hours: </strong><?php //echo decimalHours($late_working_hours);?></td>-->
                <td><strong class="help-split">Department: </strong><?php echo $department_name;?></td>
                <td><strong class="help-split">Payment By: </strong><?php echo $payment_method;?></td>
                <td><strong class="help-split">Account Number: </strong><?php echo $bank_account;?></td>
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
          <?php

?>
          <table class="table table-condensed">
            <tbody>
			 <tr>
                <td><strong>Basic Salary:</strong> <span class="pull-right"><?php echo currency_sign(change_num_format($basic_salary),$currency);//$this->Xin_model->currency_sign(change_num_format($basic_salary),'',$user_id);?></span></td>
              </tr>

		<?php  if($salary_components){foreach($salary_components as $key_com=>$val_com){?>
              <tr>
                <td><strong><?php echo salary_title_change($key_com);?>:</strong> <span class="pull-right"><?php echo currency_sign(change_num_format($val_com),$currency);?></span></td>
              </tr>
		<?php }} ?>





			   <tr>
                <td><strong>Bonus:</strong> <span class="pull-right"><?php echo currency_sign(change_num_format($bonus),$currency);?></span></td>
               </tr>


			   <tr>
                <td><strong>Total Salary:</strong> <span class="pull-right"><?php  echo currency_sign(change_num_format($salary_with_bonus),$currency);?></span></td>
               </tr>



			  <?php
			  if($leave_start_date==''){$l_date='';}else{$l_date='<br>('.format_date('d F Y',$leave_start_date).' to '.format_date('d F Y',$leave_end_date).')';}
			  echo '<tr><td><strong>Leave Salary:'.$l_date.'</strong><span class="pull-right">';
			    $leave_salary_amount=json_decode($leave_salary_amount);
				$l_amount=$annual_leave_salary;
				echo currency_sign(change_num_format($l_amount),$currency);
				echo '</span></td></tr>';
			   ?>





			   <?php

				$ot_hours_amount=json_decode($ot_hours_amount);
				$ot_day_rate=$ot_hours_amount->ot_day_amount;
				$ot_night_rate=$ot_hours_amount->ot_night_amount;
				$ot_holiday_rate=$ot_hours_amount->ot_holiday_amount;
				echo '<tr><td><strong>Overtime Amount:</strong><span class="pull-right">';
				$ot_amount=$ot_day_rate+$ot_night_rate+$ot_holiday_rate;
				echo currency_sign(change_num_format($ot_amount),$currency);
				echo '</span></td></tr>';
			   ?>
			   <?php foreach($additonal_salary as $add_s){



				?>
				<tr>
                <td><strong><?php echo $add_s->child_type_name;//$add_s->parent_type_name.' ['.$add_s->child_type_name.'] ';  ?>:</strong> <span class="pull-right"><?php

				if($add_s->amount==NULL){$am=0;}else {$am=$add_s->amount;}

				$add_sal+=$am;
				echo currency_sign(change_num_format($am),$currency);?></span></td>
                </tr>
				<?php  }  ?>


<!--            <?php foreach($find_external_perp_adjustments as $ext_perp){ ?>
				<tr>
                <td><strong><?php echo $ext_perp->child_type_name;//echo $ded_s->parent_type_name.' ['.$ded_s->child_type_name.'] ';  ?>:</strong> <span class="pull-right"><?php

				if($ext_perp->amount==NULL){$dm_per=0;}else {$dm_per=$ext_perp->amount;}
				//$add_sal+=$dm_per;
				echo currency_sign(change_num_format($dm_per),$currency);?></span></td>
                </tr>
				<?php  }  ?>


<?php foreach($find_external_nonperp_adjustments as $ext_nonperp){ ?>
				<tr>
                <td><strong><?php echo $ext_nonperp->child_type_name;//echo $ded_s->parent_type_name.' ['.$ded_s->child_type_name.'] ';  ?>:</strong> <span class="pull-right"><?php

				if($ext_nonperp->amount==NULL){$dm_per=0;}else {$dm_per=$ext_nonperp->amount;}
				//$add_sal+=$dm_per;
				echo currency_sign(change_num_format($dm_per),$currency);?></span></td>
                </tr>
				<?php  }  ?>-->




            </tbody>
          </table>

         <table class="table table-condensed">
            <tbody>

			  <?php



?>
			   <tr>
                <td><strong>Total Compensation:</strong> <span class="pull-right"><?php echo currency_sign(change_num_format($salary_with_bonus+$add_sal+$l_amount+$ot_amount),$currency);?></span></td>
               </tr>


            </tbody>
          </table>
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

          <table class="table table-condensed">
            <tbody>

			  <tr>
                <td><strong>Absence(s) / Late(s) / Early Out(s):</strong> <span class="pull-right">

				<?php
				$exceptional_employees=exceptional_employees($user_id);
				if($exceptional_employees=='Yes'){
					$tt=abs($month_salary-$net_salary);
					echo currency_sign(change_num_format($tt),$currency);
				}
				else{
				 //$default_hours=$required_working_hours-$total_working_hours;
				 //$tt1=($net_salary/$required_working_hours)*$default_hours;
				 //echo currency_sign(change_num_format($tt1),$currency);
				//$tt=round((($salary_with_bonus/$required_working_hours)*$late_working_hours),2);
				$tt=round((($salary_with_bonus-$month_salary)),2);
				echo currency_sign(change_num_format($tt),$currency);
				}



				?>

				</span></td>
               </tr>


			    <?php foreach($deduction_salary as $ded_s){ ?>
				<tr>
                <td><strong><?php echo $ded_s->child_type_name;//echo $ded_s->parent_type_name.' ['.$ded_s->child_type_name.'] ';  ?>:</strong> <span class="pull-right"><?php

				if($ded_s->amount==NULL){$dm=0;}else {$dm=$ded_s->amount;}
				$tt+=$dm;
				echo currency_sign(change_num_format($dm),$currency);?></span></td>
                </tr>
				<?php  }  ?>
			  <!--

               <?php foreach($find_external_perp_adjustments as $ext_perp){ ?>
				<tr>
                <td><strong><?php echo $ext_perp->child_type_name;//echo $ded_s->parent_type_name.' ['.$ded_s->child_type_name.'] ';  ?>:</strong> <span class="pull-right"><?php

				if($ext_perp->amount==NULL){$dm_per=0;}else {$dm_per=$ext_perp->amount;}
				//$tt+=$dm_per;
				echo currency_sign(change_num_format($dm_per),$currency);?></span></td>
                </tr>
				<?php  }  ?>


<?php foreach($find_external_nonperp_adjustments as $ext_nonperp){ ?>
				<tr>
                <td><strong><?php echo $ext_nonperp->child_type_name;//echo $ded_s->parent_type_name.' ['.$ded_s->child_type_name.'] ';  ?>:</strong> <span class="pull-right"><?php

				if($ext_nonperp->amount==NULL){$dm_per=0;}else {$dm_per=$ext_nonperp->amount;}
				//$tt+=$dm_per;
				echo currency_sign(change_num_format($dm_per),$currency);?></span></td>
                </tr>
				<?php  }  ?>-->

            </tbody>
          </table>

		  <table class="table table-condensed">
            <tbody>
	  <tr>
                <td><strong>Total Deductions:</strong> <span class="pull-right"><?php echo currency_sign(change_num_format($tt),$currency);?></span></td>
               </tr>



            </tbody>
          </table>

		  <br><br><br><br>

		   <table class="table table-condensed">
            <tbody>
	  <tr>
                <td><strong>Net Salary:</strong> <span class="pull-right"><?php echo currency_sign(change_num_format($payment_amount_to_employee),$currency);?></span></td>
               </tr>

<!--
<?php
		if($tax_amount){
		foreach($tax_amount as $tax_t){	?>
			<tr>
                <td><strong><?php echo $tax_t->tax_name.' ('.$tax_t->tax_percentage.'%)';?>:</strong> <span class="pull-right"><?php echo currency_sign(change_num_format($tax_t->tax_amount),$currency);?></span></td>
               </tr>
		<?php }	?>
 <tr>
                <td><strong>Payment Amount with Tax:</strong> <span class="pull-right"><?php echo currency_sign(change_num_format($payment_amount_with_tax),$currency);?></span></td>
               </tr>
		<?php  } ?>
	-->


<!--
               <?php foreach($find_external_perp_adjustments as $ext_perp){ ?>
				<tr>
                <td><strong><?php echo $ext_perp->child_type_name;//echo $ded_s->parent_type_name.' ['.$ded_s->child_type_name.'] ';  ?>:</strong> <span class="pull-right"><?php

				if($ext_perp->amount==NULL){$dm_per=0;}else {$dm_per=$ext_perp->amount;}
				$tt+=$dm_per;
				echo '- '.currency_sign(change_num_format($dm_per),$currency);?></span></td>
                </tr>
				<?php  }  ?>


<?php foreach($find_external_nonperp_adjustments as $ext_nonperp){ ?>
				<tr>
                <td><strong><?php echo $ext_nonperp->child_type_name;//echo $ded_s->parent_type_name.' ['.$ded_s->child_type_name.'] ';  ?>:</strong> <span class="pull-right"><?php

				if($ext_nonperp->amount==NULL){$dm_per=0;}else {$dm_per=$ext_nonperp->amount;}
				$tt+=$dm_per;
				echo '- '.currency_sign(change_num_format($dm_per),$currency);?></span></td>
                </tr>
				<?php  }  ?>-->


			<!--  <tr>
                <td><strong>Total Paid Amount to Employee:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign(change_num_format($payment_amount_to_employee),'',$user_id);?></span></td>
               </tr>
         -->
            </tbody>
          </table>
        </div>
      </div>
    </div>


  </div>


  </div>

</div><!-- pd-->



  </div>


