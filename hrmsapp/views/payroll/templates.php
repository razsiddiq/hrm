<?php
/* Payroll Template view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php if(in_array('38tv',role_resource_ids()) || in_array('38te',role_resource_ids()) || visa_wise_role_ids() != '') {?>

<div class="add-form" style="display:none;">
  <div class="panel panel-flat">
  	<div class="panel-heading">
							<h5 class="panel-title">							 
							<strong>Setup</strong> Payroll Template					
							</h5>
							<div class="heading-elements">
							    <div class="add-record-btn">      
	   <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_hide');?></button>
    </div>
		                	</div>
						</div>						
    <div class="row m-b-1">
      <div class="col-md-12">   
      <div class="panel-body">	  
	  <form class="form-hrm" action="<?php echo site_url("payroll/add_template") ?>" method="post" name="add_template" id="xin-form" autocomplete="off">
		    <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">		
		   <div class="bg-white">
            <div class="box-block">
                <div class="row">
                <div class="col-md-12">
				<div class="row">
				<div class="col-md-4">
                      <div class="form-group">
                        <label for="employees" class="control-label">Select  Employee</label>
                        <select class="form-control" onchange="select_payroll_country(this.value);" name="employee_id" id="employee_id" data-plugin="select_hrm" data-placeholder="Employee">
                          <option value=""></option>
                          <?php foreach($all_employees as $employee) {?>
                          <option value="<?php echo $employee->user_id?>"> <?php echo change_fletter_caps($employee->first_name.' '.$employee->middle_name.' '.$employee->last_name);?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
				 
				 
				 
				 </div>
				
                  <div class="row"> 

					<div id="appdend_div_paystructure">
					
					</div>
				  
					<div class="col-md-4">
                      <div class="form-group">
                        <label for="dearness_allowance">Salary Based On Contract</label>
                        <input class="form-control" placeholder="Salary Based On Contract" id="total_based_contract" name="salary_based_on_contract" value="0" type="text" readonly pattern="\d*\.?\d*" title="<?php echo $this->lang->line('xin_use_numbers_price');?>">
                      </div>
                    </div>
					
					<div class="col-md-4">
                      <div class="form-group">
                        <label for="dearness_allowance">Salary With Bonus</label>
                        <input class="form-control" placeholder="Salary With Bonus" name="salary_with_bonus" id="total_with_bonus" value="0" type="text" readonly pattern="\d*\.?\d*" title="<?php echo $this->lang->line('xin_use_numbers_price');?>">
                      </div>
                    </div>
									
					<div class="col-md-4">
            <div class="form-group">
              <label for="effective_from_date">Effective From Date</label>
              
<div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                    <input class="form-control" placeholder="Effective From Date" name="effective_from_date" size="16" type="text" value="" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
					
                </div>


            </div>
          </div>
					
			        <?php if($session['role_name']==AD_ROLE){?>
  
                    <div class="col-md-4">
 <div class="form-group">
              <label for="effective_from_date">Effective To Date</label>


<div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                    <input class="form-control" placeholder="Effective To Date" name="effective_to_date" size="16" type="text" value="" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
					
                </div>


 </div>
          </div>


				 <?php } else{?>
				  <input class="form-control" readonly placeholder="Effective To Date" name="effective_to_date" type="hidden" value="" readonly>
				<?php } ?>		
						  


					
					</div>
             
				  
				  
				  
				  
                </div>
             </div>
             
			  
            </div>
          </div>
       
             

			  <div class="row">
          <div class="col-md-12">
            <div class="form-group">
			  
			     <button type="submit" class="btn bg-teal-400 save pull-right"><?php echo $this->lang->line('xin_save');?><i class="icon-spinner3 spinner position-left"></i></button>
			  
			   </div>
			    </div>
				 </div>
	   </form>
      </div>
	  </div>
	  
    </div>
  </div>
</div>
 

        
<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
						<strong>List All</strong> Payroll Templates
							
							</h5>
							<?php if(visa_wise_role_ids() == ''){?>
							<div class="heading-elements">
							    <div class="add-record-btn">
      								<button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_add_new');?></button>
    							</div>
		                	</div>
		                <?php }?>
						</div>	

  
  <div data-pattern="priority-columns">
    <table class="table table-striped" id="xin_table_pay">
      <thead>
        <tr>
          
		      <th>Employee</th>
			  <th>Location</th>
		      <th>Basic Salary</th>
			  <th>Bonus</th>
			  <th>Salary Based On Contract</th>
			  <th>Salar With Bonus</th>
			  <th>Approved Status</th>			  
			  <th>Effective From Date</th>
			  <th>Created Date</th>   
			  <th><?php echo $this->lang->line('xin_action');?></th>			  
        </tr>
      </thead>
    </table>
  </div>
</div>
<?php } else{?>
		<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title text-danger">
							 
							 <?php echo $this->lang->line('xin_permission');?>
							
							</h5>
				
						</div>
</div>
<?php }?>