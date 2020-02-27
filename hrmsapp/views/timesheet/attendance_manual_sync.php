<?php
/* Date Wise Attendance view
*/
?>
<?php $session = $this->session->userdata('username');?>

<?php if(in_array('58',role_resource_ids())) {?>	
    
<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong>Select Date For Sync</strong>
							
							</h5>						
						</div>
    
        <div class="panel-body">
          <div class="row">
            <div class="col-md-12">
            <form class="add form-hrm" method="post" name="sync_process" id="sync_process">
        
              <div class="row">            
				<div class="col-md-2">
                  <div class="form-group">
                    <select name="location_value" class="form-control location_value" data-plugin="select_hrm" data-placeholder="Choose the Location..." >
                      <option value="">&nbsp;</option>
                      <?php foreach($this->Xin_model->all_locations() as $locs) {?>
                      <option value="<?php echo $locs->location_id;?>"> <?php echo $locs->location_name;?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
				<div class="col-md-2" >
				<div class="form-group">					
					  <select class="form-control department_value" name="department_value" data-plugin="select_hrm" data-placeholder="Choose the Department...">
                      <option value="">&nbsp;</option>
                      <?php foreach($this->Xin_model->all_departments_chart() as $dept) {?>
                      <option value="<?php echo $dept->department_id;?>"><?php echo $dept->department_name;?></option>
                      <?php } ?>
                    </select>					
                  </div>
				</div>
				
				<div class="col-md-4">
                  <div class="form-group">
                    <select name="employee_id" id="employee_id" class="form-control" data-plugin="select_hrm" data-placeholder="Choose an Employee..." >                    
                    </select>
                  </div>
                </div>
				
                <div class="col-md-3">
                  <div class="form-group">
                   
					<div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                    <input class="form-control" placeholder="Select Date" name="start_date" id="start_date"  size="16" type="text"  value="<?php echo date('d F Y',strtotime('-1 Day',strtotime(date('d F Y'))));?>" readonly>
                    <span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>
					
                </div>
				
				
					
				
				
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                  
					
				
				<div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                    <input class="form-control" placeholder="Select Date" name="end_date" id="end_date"  size="16" type="text"  value="<?php echo date('d F Y',strtotime('-1 Day',strtotime(date('d F Y'))));?>" readonly>
                    <span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>
					
                </div>
				
				
				
                  </div>
                </div>
				 <div class="col-md-1">
                  <div class="form-group mt-10"> &nbsp;
                   <input type="checkbox" class="styled"  name="driver_s" value="1"/> Drivers
                  </div>
                </div>
				 <div class="col-md-1">
                  <div class="form-group"> &nbsp;
                    <button type="submit" class="btn bg-teal-400 save">Sync</button>
                  </div>
                </div>
				
              </div>
              </div>
            </form>
          </div>
          
      </div>
    </div>
<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong>Attendance</strong> 
							
							</h5>
					
						</div>
						

  <div data-pattern="priority-columns">
    <table class="table" id="xin_table" >
      <thead>
        <tr>
	      <th></th>
     	  <th>Status</th>
          <th class="change_title_name">Name</th>
          <th>Clock IN</th>
          <th>Clock OUT</th>
          <th>Late</th>
		  <th>Early Leaving</th>
          <th>Over Time (Day/Night)</th>
          <th>Total Work</th>
		  <th>Shift Time / Week Off</th>
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