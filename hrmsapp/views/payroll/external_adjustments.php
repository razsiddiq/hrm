<?php
/* External Adjustmentss view
*/
?>

<?php $session = $this->session->userdata('username');?>
<?php if(in_array('38v',role_resource_ids()) || visa_wise_role_ids() != '') {?>
<div class="add-form" style="display:none;">
  <div class="panel panel-flat">
  	<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong><?php echo $this->lang->line('xin_add_new');?></strong> <?php echo $breadcrumbs;?>
							
							</h5>
							<div class="heading-elements">
							    <div class="add-record-btn">      
	   <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_hide');?></button>
    </div>
		                	</div>
						</div>
						
    <div class="row m-b-1">
      <div class="col-md-12">
      
	  <form action="<?php echo site_url("payroll/add_external_adjustments") ?>" method="post" name="add_external_adjustments" id="xin-form">
		  <input type="hidden" name="<?= csrf_name;?>" value="<?= csrf_hash;?>" />
          <input type="hidden" name="_user" value="<?php echo $session['user_id'];?>">
          <div class="panel-body">
            
              <div class="row">
                <div class="col-md-6">
              
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="department">External Adjustments Type</label>
                        <select id="parent_type" onchange="getParentChildType(this.value)" name="adjustment_type" class="form-control" data-plugin="select_hrm" data-placeholder="Choose the External Adjustments Type...">
                        <option value="">&nbsp;</option>
                        <?php foreach($this->Payroll_model->get_adjustments('parent','external') as $par) {  ?>
                      <option value="<?php echo $par->type_id;?>"> <?php echo $par->type_name;?></option>
                      <?php } ?>
                    </select>
                      </div>
					  
					  
					  <div class="form-group">
                        <label for="department">Adjustment Name</label>
                        <select id="child_type" class="form-control" data-plugin="select_hrm" name="adjustment_name" data-placeholder="Choose the Adjustments...">
                        <option value="">&nbsp;</option>                    
                    
                    </select>
                      </div>
					 
					   <div class="form-group">
					  <label for="department">Comments</label>
						<textarea style="resize:none;min-height: 106px;" class="form-control" placeholder="Comments" name="comments"></textarea>
					  
					   </div>
                    </div>
                
                  </div>
           


        		   </div>
                <div class="col-md-6">
                   
				     <div class="row">
                    <div class="col-md-12">
                   
					  <div class="form-group">
                        <label for="department">Adjustment for Employee</label>
                        <select name="adjustment_for_employee" onchange="get_currency_byemployee(this.value);" class="form-control" data-plugin="select_hrm" data-placeholder="Choose the Employee...">
						<option value="">&nbsp;</option>
                       <?php foreach($this->Payroll_model->get_employees_nameuserid($type='') as $emps) {?>
                        <option value="<?php echo $emps->user_id;?>"><?php echo change_to_caps($emps->first_name.' '.$emps->middle_name.' '.$emps->last_name);?></option>
                        <?php } ?>
                    </select>
                      </div>
					   
					  
					  <div class="form-group" id="end_date_of_p" style="display:none;">
                        <label for="end_date">End Date</label>                     
						
						<div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
						<input class="form-control" placeholder="End Date" name="end_date" size="16" type="text" value="<?php echo date('d F Y');?>" readonly>
						<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>				
					  </div>
					  
						
                      </div>
					  
        
					  <div class="form-group">
                        
                       <label for="department">Amount</label>
					    <div class="form-group">
                        
                        <input class="form-control" placeholder="Amount" name="adjustment_amount" pattern="\d*\.?\d*" title="Format should be 5000 or 5000.00" type="text">
						<div class="form-control-currency" id="form-control-currency"><?php //echo currency_default();?></div>
                      </div>
                      </div>
					  
					   <!--<div class="form-group">
                        
                       <label for="department">VAT (%)</label>
					    <div class="form-group">
                        
                        <input class="form-control" placeholder="VAT" name="tax_percentage" pattern="\d*\.?\d*" title="Format should be 5000 or 5000.00" type="text">
					
                      </div>
					  
					  
					  
					 </div> -->
					  
								    <div class="form-group">
            <label for="company_name">Pay Amount Compute with Employee's total working hours</label>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="switchery" data-size="small" data-color="#3e70c9" data-secondary-color="#ddd" id="compute_amount" value="1">
            </div>
          </div>
		  
                    </div>
                
                  </div>
				  
				  
              </div>
              </div>
			  
	<div class="clearfix"></div>
			<div class="">						
									
  <button type="submit" class="btn pull-right bg-teal-400 save"><?php echo $this->lang->line('xin_save');?></button>
									</div>


              
            </div>
        
        </form>
      </div>
	  
	  
    </div>
  </div>
</div>

<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong>Filter By</strong> </strong>
						
							</h5>						
						</div>		
            <div class="panel-body">
			<div class="row">
               <div class="col-md-12">          
				
				<?php if(visa_wise_role_ids() == ''){?>
				<div class="col-md-2 no-padding-left">         
              		<div class="form-group">
	                   	<select class="visa_value" class="form-control" data-plugin="select_hrm" data-placeholder="Choose the Status...">
	                      <option value="0">All Visas</option>
	                      <?php foreach(visa_lists() as $vis) {?>
	                      <option value="<?php echo $vis->type_id;?>"><?php echo $vis->type_name;?></option>
	                      <?php } ?>
	                    </select>
                  	</div>
				</div>
				<?php }?>

				<div class="col-md-2">
                  <div class="form-group">
                    <select class="location_value" class="form-control" data-plugin="select_hrm" data-placeholder="Choose the Status...">
                      <option value="0">All Locations</option>
                      <?php foreach($this->Xin_model->all_locations() as $locs) {?>
                      <option value="<?php echo $locs->location_id;?>"><?php echo $locs->location_name;?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
								
				<div class="col-md-2">
                  <div class="form-group">
                    <select class="department_value" class="form-control" data-plugin="select_hrm" data-placeholder="Choose the Status...">
                      <option value="0">All Departments</option>
                      <?php foreach($this->Xin_model->all_departments_chart() as $dept) {?>
                      <option value="<?php echo $dept->department_id;?>"><?php echo $dept->department_name;?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
				
				<div class="col-md-2">
				 <div class="form-group">
                        <select class="adjustment_type form-control"  data-plugin="select_hrm" data-placeholder="Choose the Internal Adjustments Type...">
                        <option value="0">All Adjustments Type</option>
                        <?php foreach($this->Payroll_model->get_adjustments('parent','external') as $par) {  ?>
                      <option value="<?php echo $par->type_id;?>"> <?php echo $par->type_name;?></option>
                      <?php } ?>
                    </select>
                      </div>
				</div>
				
				<div class="col-md-2">
				 <div class="form-group">
                        <select class="adjustment_name form-control"  data-plugin="select_hrm" data-placeholder="Choose the Internal Adjustments Name...">
                        <option value="0">All Adjustments Name</option>
                        <?php foreach($this->Payroll_model->get_adjustments_name('external') as $par) {  ?>
                      <option value="<?php echo $par->type_id;?>"> <?php echo $par->type_name;?></option>
                      <?php } ?>
                    </select>
                      </div>
				</div>
			 <div class="col-md-2">
                  <div class="form-group"> &nbsp;
				  
				    <button type="button" onclick="search_filter();" class="btn bg-teal-400 mr-20">Filter</button>
				  
                    <button type="button" onclick="clear_filter();" class="btn bg-teal-400">Clear</button>
                  </div>
                </div>
             
              </div>
       
</div></div>
					
					
		</div>	
<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong><?php echo $this->lang->line('xin_list_all');?></strong> <?php echo $breadcrumbs;?>
							
							</h5>
							
						
<?php if(in_array('38a',role_resource_ids())) {?>
							<div class="heading-elements">
						
							<div class="add-record-btn">
							<button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_add_new');?></button>
                            </div>
						
		                	</div>
							
<?php } ?>
						</div>
						
						


 
	<div  data-pattern="priority-columns">
    <table class="table table-striped" id="xin_table">
      <thead>
        <tr>
		  <th>S.NO</th>
		  <th>Adjustment Type</th>
		  <th>Adjustment Name</th>
		  <th>Amount</th>
          <th>To Employee</th>
          <th>Prepared By</th>  
		  <th>End Date</th>
		  <th>Status</th>
		  <th>Created At</th>
		  <?php if(visa_wise_role_ids() == ''){?>
          <th>Action</th>
  		  <?php }?>
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