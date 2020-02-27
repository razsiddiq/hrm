<?php
/* Payment History view
*/
?>
<?php $session = $this->session->userdata('username');?>
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
				 <input type="text" value="<?php echo date('Y F',strtotime('-1 months',strtotime(date('Y F'))));?>" id="month_year" name="month_year" readonly class="form-control form_month_year_1">
                </div>
              
				</div>
				
			 <div class="col-md-4">
                  <div class="form-group"> &nbsp;
				  
				    <button type="button" onclick="select_filter();" class="btn bg-teal-400 mr-20">Filter</button>
				  
                    <button type="button" onclick="clear_filter();" class="btn bg-teal-400">Clear</button>
                  </div>
                </div>
             
              </div>
       
</div></div>
					
					
		</div>	
 <div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong>List All </strong> Payment History
							</h5>
						
						</div>	

  <div data-pattern="priority-columns">
    <table class="table table-striped" id="xin_table">
      <thead>
        <tr>
          <th>Employee ID</th>
          <th>Employee Name</th>
          <th>Payment Month</th>
          <th>Payment Date</th>
          <th>Paid Amount</th>
		  <th>Paid Amount to Employee</th>
          <th>Remarks</th>
          <?php if(visa_wise_role_ids() == ''){?>
          <th>Action</th>
          <?php }?>

        </tr>
      </thead>
	  
	  
	  
    </table>
  </div>
</div>


 <div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							<strong>List All </strong> Agency Fees
							</h5>
						
						</div>	

  <div data-pattern="priority-columns">
    <table class="table table-striped" id="xin_table_agency">
      <thead>
        <tr>
		  <th>Employee Name</th>
          <th>Employee ID (1C ID)</th>          
          <th>Payment Month</th>
          <th>Payment Date</th>
          <th>Agency Name</th>
		  <th>Agency Fees</th>
          <th>Required Working Hours</th>
          <th>Actual Working Hours</th>
		  <th>Agency Fees Computation</th>		  
        </tr>
      </thead>
	  
	  
	  
    </table>
  </div>
</div>
<style>
.table-responsive {
    overflow-x: scroll !important;
}
</style>