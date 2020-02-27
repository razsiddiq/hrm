<?php
/* Leave Application view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php if(in_array('32lv',role_resource_ids()) || visa_wise_role_ids() != '' || reporting_manager_access() || hod_manager_access()) {?>
  <div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong></strong> Choose Employee Leave Card 


							
							</h5>
		
						</div>
						
					       <div class="panel-body">

          <div class="row">
            
					 <div class="col-md-6">
                  <div class="form-group">
                    <select name="employee_id" id="employee_id" class="form-control" onchange="employee_leave_card(this.value);" data-plugin="select_hrm" data-placeholder="Choose Employee...">
                      <option value="0">All Employees</option>
                      <?php foreach($all_employees as $employee) {?>
                      <option value="<?php echo $employee->user_id;?>"> <?php echo change_fletter_caps($employee->first_name.' '.$employee->middle_name.' '.$employee->last_name);?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>	

<div class="col-md-3">
<button type="button" onclick="clear_leave_card();" class="btn bg-teal-400">Clear</button>
</div>
			 </div> 
			 
			 
			 
			 <div class="employee_leave_card_result">
			 
			 
			 
			 </div>
			 
			 
			 
			 </div>			
						
  
</div>
<!--
<div class="panel panel-flat">
<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong>List All</strong> Conversions							
							<a class="heading-elements-toggle"><i class="icon-more"></i></a></h5>
				
						</div>
  <div data-pattern="priority-columns">
 
			<table class="table" id="xin_table_conversion">
		
							<thead>
							    <tr>
          

          <th><?php //echo $this->lang->line('xin_employees_full_name');?></th>
          <th>Conversion Days Count</th>
          <th>Comments</th>
          <th>Added Date</th>
          <th>Added By</th>
          <th>Created At</th>
		  <th>Action</th>
        </tr>
							</thead>
			
							<tbody>
													
							</tbody>
						</table>
	
  </div>
</div>
-->


<?php } else { ?>
		<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title text-danger">
							 
							 <?php echo $this->lang->line('xin_permission');?>
							
							</h5>
				
						</div>
</div>

<?php } ?>
