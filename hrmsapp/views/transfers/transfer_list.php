<?php
/* Transfer view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php if(in_array('16v',role_resource_ids())) {?>
<?php if(in_array('16a',role_resource_ids())) {?>
<div class="add-form" style="display:none;">
    <div class="panel panel-flat">
  	<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong><?php echo $this->lang->line('xin_add_new');?></strong> Transfer
							
							</h5>
							<div class="heading-elements">
							    <div class="add-record-btn">      
	   <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_hide');?></button>
    </div>
		                	</div>
						</div>
    <div class="row m-b-1">
      <div class="col-md-12">
        <form action="<?php echo site_url("transfers/add_transfer") ?>" method="post" name="add_transfer" id="xin-form">
          <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
          
              <div class="col-lg-12">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="employee">Employee to Transfer</label>
                    <select name="employee_id" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="Choose an Employee...">
                      <option value=""></option>
                      <?php foreach($all_employees as $employee) {?>
                      <option value="<?php echo $employee->user_id;?>"> <?php echo $employee->first_name.' '.$employee->middle_name.' '.$employee->last_name;?></option>
                      <?php } ?>
                    </select>
                  </div>	
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="transfer_date">Transfer Date</label>                       
						<div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                    <input class="form-control" placeholder="Transfer Date" name="transfer_date" size="16" type="text"  value="" readonly>
                    <span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>					
                </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="transfer_department">Transfer To (Department)</label>
                        <select class="select2" data-plugin="select_hrm" data-placeholder="Select Department..." name="transfer_department">
                          <option value=""></option>
                          <?php foreach($all_departments as $department) {?>
                          <option value="<?php echo $department->department_id?>"><?php echo $department->department_name;?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="transfer_location">Transfer To (Location)</label>
                        <select class="select2" data-plugin="select_hrm" data-placeholder="Select Location..." name="transfer_location">
                          <option value=""></option>
                          <?php foreach($all_locations as $location) {?>
                          <option value="<?php echo $location->location_id?>"><?php echo $location->location_name;?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control textarea" placeholder="Description" name="description" cols="30" rows="10" id="description"></textarea>
                  </div>
                </div>
				
				 <div class="clearfix"></div>
			<div class="footer-elements">						
									
  <button type="submit" class="btn bg-teal-400 save"><?php echo $this->lang->line('xin_save');?></button>
									</div>
              </div>
        
			 
									
			
        </form>
      </div>
    </div>
  </div>
</div>
<?php } ?>
<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong><?php echo $this->lang->line('xin_list_all');?></strong> Transfers
							
							</h5>
							<div class="heading-elements">
							<?php if(in_array('16a',role_resource_ids())) {?>
							  <div class="add-record-btn">
      <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_add_new');?></button>
							</div>
							<?php } ?>
		                	</div>
						</div>

  <div  data-pattern="priority-columns">
    <table class="table table-striped" id="xin_table">
      <thead>
        <tr>         
          <th>Employee Name</th>
          <th>Transfer Date</th>
          <th>Transfer To (Department)</th>
          <th>Transfer To (Location)</th>
          <th>Status</th>
          <th>Added By</th>
		   <th>Action</th>
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