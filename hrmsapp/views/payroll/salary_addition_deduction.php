<?php
/* Payment History view
*/
?>
<?php $session = $this->session->userdata('username');?>


     <div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong>Filter By</strong> 
							</h5>
					
       </div>
          <div class="panel-body">
             
				<div class="col-md-2 no-padding-left">
                  <div class="form-group">					
                    <select id="parent_type" onchange="getParentChildType(this.value,'')" class="form-control" data-plugin="select_hrm" data-placeholder="Choose the Parent Type...">
                      <option value="0">Parent Type</option>
                      <?php foreach($this->Payroll_model->get_salaray_type('parent') as $par) {?>
                      <option value="<?php echo $par->type_id;?>"> <?php echo $par->type_name;?></option>
                      <?php } ?>
                    </select>
					
                  </div>
                </div>				
				<div class="col-md-2">
                  <div class="form-group">
                    <select id="child_type" class="form-control" data-plugin="select_hrm" data-placeholder="Choose the Child Type...">
                      <option value="0">Child Type</option>
                      
                    
                    </select>
                  </div>
                </div>
				<div class="col-md-2">
                  <div class="form-group">					
                    <select id="user_id"  class="form-control" data-plugin="select_hrm" data-placeholder="Choose the Employee...">
                      <option value="0">All Employees</option>
                      <?php foreach($this->Payroll_model->get_salary_employees() as $emps) {?>
                      <option value="<?php echo $emps->user_id;?>"> <?php echo change_to_caps($emps->first_name);?></option>
                      <?php } ?>
                    </select>
					
                  </div>
                </div>	
				
				<div class="col-md-2">
                  <div class="form-group">					
                    <select id="payment_month"  class="form-control" data-plugin="select_hrm" data-placeholder="Choose the Month...">
                      <option value="0">Payment Month</option>
                      <?php foreach($this->Payroll_model->get_salary_payment_month() as $pmonth) {?>
                      <option value="<?php echo $pmonth->payment_date;?>"> <?php echo date("F Y", strtotime($pmonth->payment_date));?></option>
                      <?php } ?>
                    </select>
					
                  </div>
                </div>	
				
				
				<div class="col-md-2 col-xs-6">
                  <div class="form-group">
                    <button type="button" onclick="search_filter();" class="btn bg-teal-400 save">Filter</button>
					
					   <button type="button" onclick="clear_filter();" class="ml-20 btn bg-teal-400 save">Clear</button>
                  </div>
                </div>
		
         
          
          </div>
      
    </div>
	
	
   <div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong>List All</strong> <?php echo $breadcrumbs;?>
							</h5>
					
       </div>

	   
  <div  data-pattern="priority-columns">
    <table class="table table-striped" id="xin_table">
      <thead>
        <tr>
		  <th>Payment Month</th>
		  <th>Employee Name</th>
		  <th>Employee ID</th>
          <th>Parent Type</th>
          <th>Child Type</th>  
		  <th>Paid Amount</th>
          <th>Comments</th>
		  <th>Status</th>
		  <th>Created At</th>
          <th>Payslip</th>
        </tr>
      </thead>
    </table>
  </div>
</div>	