<?php
/* Leave Application view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php if(in_array('41L',role_resource_ids()) || visa_wise_role_ids() != '') {?>
  <div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong></strong> Choose the Employee For Leave Settlement 


							
							</h5>
		
						</div>
						
						<div class="panel-body">
    
          <form class="m-b-1 add form-hrm" action="<?php echo site_url("payroll/compute_leave_settlement") ?>"  method="post" name="compute_leave_settlement" id="compute_leave_settlement">
            <div class="row">
      <input type="hidden" value="0" name="save"/>
              <div class="col-md-6">
                <div class="form-group">
				
				 <label for="department">Employee</label>
                  <select id="employee_id" onchange="leave_settlment_month();" name="employee_id" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="Choose an Employee..." required>
                  <option value="">All Employees</option>
                  <?php foreach($all_employees as $employee) {?>
                  <option value="<?php echo $employee->user_id;?>"> <?php echo $employee->first_name.' '.$employee->middle_name.' '.$employee->last_name;?> </option>
                  <?php } ?>
                  </select>
                </div>
              </div>
			  
              <div class="col-md-6">
                <div class="form-group">
				<label for="month_year">Select Month</label>
              
				  
				  <input type="text" value="<?php echo date('Y F');?>" id="month_year" name="month_year" readonly class="form-control form_month_year_1" onchange="leave_settlment_month();" required>
				  
                </div>
              </div>
			  
			  
			    
              <!--<div class="col-md-6">
                <div class="form-group">
				<label for="month_year">Start Date</label>
                  <input class="form-control attendance_date" placeholder="Select Date" readonly id="start_date" name="start_date" type="text" value="" required>
                </div>
              </div>
			  
			   <div class="col-md-6">
                <div class="form-group">
				<label for="month_year">End Date</label>
                  <input class="form-control attendance_date" placeholder="Select Date" readonly id="end_date" name="end_date" type="text" value="" required>
                </div>
              </div>
			  -->
			  
			  
            </div>
   
	
			 <div class="clearfix"></div>
			<div class="footer-elements" style="padding:0px;">						
									
             <button type="submit" class="btn bg-teal-400 save">Compute</button>
									</div>

		<!--<input type="hidden" id="current_count" value="0" />-->
			
          </form>
        
      </div>
	  
	  
						
					    		
						
  
</div>

<div class="leave_settlement_show">

</div>

<div class="leave_settlement_show1">

</div>


<div class="panel panel-flat">
<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong>List All</strong> Leave Settlement							
							<a class="heading-elements-toggle"><i class="icon-more"></i></a></h5>
				
						</div>
  <div data-pattern="priority-columns">
 
			<table class="table" id="xin_table_conversion">
		
							<thead>
							    <tr>
          

          <th><?php echo $this->lang->line('xin_employees_full_name');?></th>
          <th>Payment Month</th>
          <th>Amount</th>
          <th>Leave Settlement Start Date</th>
          <th>Leave Settlement End Date</th>
          <th>Created At</th>
		  <th>Created BY</th>
		  <th>Action</th>
		  
		  
        </tr>
							</thead>
			
							<tbody>
													
							</tbody>
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