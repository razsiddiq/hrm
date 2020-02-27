<?php
/* Travel view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php if(in_array('18v',role_resource_ids())) {?>
<?php if(in_array('18a',role_resource_ids())) {?>
<div class="add-form" style="display:none;">
    <div class="panel panel-flat">
  	<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong><?php echo $this->lang->line('xin_add_new');?></strong> Travel
							
							</h5>
							<div class="heading-elements">
							    <div class="add-record-btn">      
	   <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_hide');?></button>
    </div>
		                	</div>
						</div>
						
   
    <div class="row m-b-1">
      <div class="col-md-12">
        <form action="<?php echo site_url("travel/add_travel") ?>" method="post" name="add_travel" id="xin-form">
          <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
          
              <div class="col-lg-12">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="employee_id">Employee</label>
                    <select name="employee_id" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="Choose an Employee...">
                      <option value=""></option>
                      <?php foreach($all_employees as $employee) {?>
                      <option value="<?php echo $employee->user_id;?>"> <?php echo change_fletter_caps($employee->first_name.' '.$employee->middle_name.' '.$employee->last_name);?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="start_date">Start Date</label>                     
						
						<div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                    <input class="form-control" placeholder="Start Date" name="start_date" size="16" type="text"  value="" readonly>
                    <span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>					
                </div>
				
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="end_date">End Date</label>
                       
						
						<div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                    <input class="form-control" placeholder="End Date" name="end_date" size="16" type="text"  value="" readonly>
                    <span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>					
                </div>
				
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="visit_purpose">Purpose of Visit</label>
                        <input class="form-control" placeholder="Purpose of Visit" name="visit_purpose" type="text">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="visit_place">Place of Visit</label>
                        <input class="form-control" placeholder="Place of Visit" name="visit_place" type="text">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="travel_mode">Travel Mode</label>
                        <select class="select2" data-plugin="select_hrm" data-placeholder="Travel Mode" name="travel_mode">
                          <option value="1">By Bus</option>
                          <option value="2">By Train</option>
                          <option value="3">By Plane</option>
                          <option value="4">By Taxi</option>
                          <option value="5">By Rental Car</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="arrangement_type">Arrangement Type</label>
                        <select class="select2" data-plugin="select_hrm" data-placeholder="Arrangement Type" name="arrangement_type">
                          <?php foreach($travel_arrangement_types as $travel_arr_type) {?>
                          <option value="<?php echo $travel_arr_type->type_id;?>"> <?php echo $travel_arr_type->type_name;?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="expected_budget">Expected Travel Budget</label>
                        <input class="form-control" placeholder="Expected Travel Budget" name="expected_budget" type="text">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="actual_budget">Actual Travel Budget</label>
                        <input class="form-control" placeholder="Actual Travel Budget" name="actual_budget" type="text">
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
							 
							<strong><?php echo $this->lang->line('xin_list_all');?></strong> Travels
							
							</h5>
							<div class="heading-elements">
							<?php if(in_array('18a',role_resource_ids())) {?>
							    <div class="add-record-btn">
      <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_add_new');?></button>
							</div><?php } ?>
		                	</div>
						</div>				

  <div data-pattern="priority-columns">
    <table class="table table-striped" id="xin_table">
      <thead>
        <tr>         
          <th>Employee</th>
          <th>Travel Purpose</th>
          <th>Visit Place</th>
          <th>Start Date</th>
          <th>End Date</th>
          <th>Approval Status</th>
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