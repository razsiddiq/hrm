<?php
/* Warning view
*/
?>
<?php $session = $this->session->userdata('username');

?>
<?php if(in_array('22v',role_resource_ids())) {?>
<?php if(in_array('22a',role_resource_ids())) {?>
<div class="add-form" style="display:none;">
    <div class="panel panel-flat">
  	<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong><?php echo $this->lang->line('xin_add_new');?></strong> Warning
							
							</h5>
							<div class="heading-elements">
							    <div class="add-record-btn">      
	   <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_hide');?></button>
    </div>
		                	</div>
						</div>
 
    <div class="row m-b-1">
      <div class="col-md-12">
        <form action="<?php echo site_url("warning/add_warning") ?>" method="post" name="add_warning" id="xin-form">
     
	  
	   <input value="<?php echo $session['user_id'];?>>" name="warning_by" type="hidden">
              <div class="col-lg-12">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="warning_to">Warning To</label>
                    <select name="warning_to" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="Choose an Employee...">
                      <option value=""></option>
                      <?php foreach($all_employees as $employee) {?>
                      <option value="<?php echo $employee->user_id;?>"> <?php echo change_fletter_caps($employee->first_name.' '.$employee->middle_name.' '.$employee->last_name);?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="type">Type of Warning</label>
                        <select class="select2" data-plugin="select_hrm" data-placeholder="Type of Warning" name="type">
                          <option value=""></option>
                          <?php foreach($all_warning_types as $warning_type) {?>
                          <option value="<?php echo $warning_type->type_id?>"><?php echo $warning_type->type_name;?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="subject">Subject</label>
                        <input class="form-control" placeholder="Subject" name="subject" type="text">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                  <!--  <div class="col-md-6">
                      <div class="form-group">
                        <label for="warning_by">Warning By</label>
                        <select name="warning_by" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="Choose an Employee...">
                          <option value=""></option>
                          <?php //foreach($all_employees as $employee) {?>
                          <option value="<?php //echo $employee->user_id;?>"> <?php //echo $employee->first_name.' '.$employee->last_name;?></option>
                          <?php //} ?>
                        </select>
                      </div>
                    </div>-->
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="warning_date">Warning Date</label>
                     
						<div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                    <input class="form-control" placeholder="Warning Date" name="warning_date" size="16" type="text"  value="" readonly>
                    <span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>					
                </div>
				
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
							 
							<strong><?php echo $this->lang->line('xin_list_all');?></strong> Warnings
							
							</h5>
							<div class="heading-elements">
							<?php if(in_array('22a',role_resource_ids())) {?>
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
          <th>Employee</th>
          <th>Warning Date</th>
          <th>Subject</th>
          <th>Warning Type</th>
          <th>Approval Status</th>
          <th>Warning By</th>
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