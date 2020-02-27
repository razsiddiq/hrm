<?php
/* Office Shift view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php if(in_array('34v',role_resource_ids())) {?>
<?php if(in_array('34a',role_resource_ids())) {?>
<div class="add-form"  style="display:none;">
  <div class="panel panel-flat">
  	<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong>Add New</strong> Office Shift
							
							</h5>
							<div class="heading-elements">
							    <div class="add-record-btn">      
	   <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_hide');?></button>
    </div>
		                	</div>	
						</div>

 
    <div class="row">
      <div class="col-md-12">
        <form action="<?php echo site_url("timesheet/add_office_shift") ?>" method="post" name="add_office_shift" id="xin-form">
          <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
      <div class="col-lg-12">
          <div class="col-lg-4">
		    <div class="form-group">
                    <label for="name">Shift Name</label>
                    <input class="form-control" placeholder="Shift Name" name="shift_name" type="text" value="" id="name">
                  </div>
		    </div>
			
			<div class="col-lg-4">
		    <div class="form-group">
            <label for="name">Choose the Location</label>
                    <select class="form-control"  name="location_id" data-plugin="select_hrm" data-placeholder="Choose the Location...">
                      <option value="">&nbsp;</option>
                      <?php foreach($this->Xin_model->all_locations() as $locs) {?>
                      <option value="<?php echo $locs->location_id;?>"> <?php echo $locs->location_name;?></option>
                      <?php } ?>
                    </select>
                  </div>
		    </div>
			
			
			
			<div class="col-lg-4">
		   <div class="form-group">
<label for="name">Choose the Department</label>					
					  <select class="form-control" name="department_id" data-plugin="select_hrm" data-placeholder="Choose the Department...">
                      <option value="">&nbsp;</option>
                      <?php foreach($this->Xin_model->all_departments_chart() as $dept) {?>
                      <option value="<?php echo $dept->department_id;?>"><?php echo $dept->department_name;?></option>
                      <?php } ?>
                    </select>
					
                  </div>
		    </div>
                
				<div class="col-lg-4">
		    <div class="form-group">
                    <label for="name">Shift In Time</label>
                    <input class="form-control timepicker clear-1" placeholder="In Time" readonly name="shift_in_time" type="text" value="">
                  </div>
		    </div>
			
			<div class="col-lg-4">
		    <div class="form-group">
                    <label for="name">Shift Out Time</label>
                    <input class="form-control timepicker clear-1" placeholder="Out Time" readonly name="shift_out_time" type="text" value="">
					
                  </div>
		    </div>
			
			
			<div class="col-lg-4">
		    <div class="form-group"> 
                    <label for="name">Week Off Day</label>
                    <select class="form-control" name="week_off[]" data-plugin="select_hrm" data-placeholder="Choose the Week Off Day..." multiple>
                      <option value="">&nbsp;</option>
                      <?php foreach(get_weekoff_days() as $week_off) {?>
                      <option value="<?php echo $week_off;?>"><?php echo $week_off;?></option>
                      <?php } ?>
                    </select>
					
                  </div>
		    </div>
                
   				 <!--  <div class="form-group row">
                    <label for="time" class="col-md-2">Monday</label>
                    <div class="col-md-4">
                      <input class="form-control timepicker clear-1" placeholder="In Time" readonly name="monday_in_time" type="text" value="">
                    </div>
                    <div class="col-md-4">
                      <input class="form-control timepicker clear-1" placeholder="Out Time" readonly name="monday_out_time" type="text" value="">
                    </div>
                    <div class="col-md-2">
                      <button type="button" class="btn bg-teal-400 clear-time" data-clear-id="1">Clear</button>
                    </div>
                  </div>-->
		      
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
							<strong>List All</strong> Office Shift							
							</h5>
							<div class="heading-elements">
							<?php if(in_array('34a',role_resource_ids())) {?>
							  <div class="add-record-btn">      
							 <button class="btn bg-teal-400 add-new-form">Add New</button>
							  </div>
							<?php } ?>
		                	</div>
						</div>

  
  <div data-pattern="priority-columns">
    <table class="table" id="xin_table">
      <thead>
        <tr>          
          <th>Shift Name</th>
          <th>Location</th>
          <th>Department</th>
          <th>Shift Time</th>
          <th>Week Off</th>		  
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