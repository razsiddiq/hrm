<?php
/* Departments view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php if(in_array('5v',role_resource_ids())) {?>
<div class="row m-b-1 animated fadeInRight">

<?php if(in_array('5a',role_resource_ids())) {?>
  <div class="col-md-4">
    <div class="panel panel-flat">
   
	  <div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong><?php echo $this->lang->line('xin_add_new');?></strong> <?php echo $this->lang->line('xin_department');?>
							
								</h5>
					
						</div>
     
<div class="panel-body">
	 <form class="m-b-1 add" method="post" action="<?php echo site_url("department/add_department") ?>" name="add_department" id="xin-form">
        <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
        <div class="form-group">
          <label for="name"><?php echo $this->lang->line('xin_code');?><?php echo REQUIRED_FIELD;?></label>
          <input type="text" class="form-control" name="department_name" placeholder="<?php echo $this->lang->line('xin_code');?>">
        </div>
		 <div class="form-group">
          <label for="name"><?php echo $this->lang->line('xin_name');?><?php echo REQUIRED_FIELD;?></label>
          <input type="text" class="form-control" name="department_detail" placeholder="<?php echo $this->lang->line('xin_name');?>">
        </div>
        <div class="form-group">
          <label for="name"><?php echo $this->lang->line('xin_location');?><?php echo REQUIRED_FIELD;?></label>
          <select name="location_id[]"  multiple class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_location');?>">
            <option value=""></option>
            <?php foreach($all_locations as $location) {?>
            <option value="<?php echo $location->location_id;?>"> <?php echo $location->location_name;?></option>
            <?php } ?>
          </select>
        </div>
        <div class="form-group">
          <label for="name"><?php echo $this->lang->line('xin_department_head');?><?php echo REQUIRED_FIELD;?></label>
          <select name="employee_id" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_department_head');?>">
            <option value=""></option>
            <?php foreach($all_employees as $employee) {?>
            <?php
                /* get user_role */
				$user_role = $this->Xin_model->read_user_role_info($employee->user_role_id);
				?>
            <option value="<?php echo $employee->user_id;?>"> <?php echo $employee->first_name.' '.$employee->middle_name.' '.$employee->last_name;?> (<?php echo $user_role[0]->role_name;?>)</option>
            <?php } ?>
          </select>
        </div>
        <button type="submit" class="btn bg-teal-400 pull-right save"><?php echo $this->lang->line('xin_save');?></button>
      </form>
   
</div>
   </div>
  </div>
  <div class="col-md-8">
<?php } else { ?> 
  <div class="col-md-12">
  <?php } ?>
  <div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong><?php echo $this->lang->line('xin_list_all');?></strong> <?php echo $this->lang->line('xin_departments');?>
							
							</h5>
						
						</div>
		<table class="table" id="xin_table">
						
							<thead>
								<tr>			   
              <th><?php echo $this->lang->line('xin_department_code');?></th>
              <th><?php echo $this->lang->line('xin_department_head');?></th>
              <th><?php echo $this->lang->line('xin_location');?></th>
			    <th><?php echo $this->lang->line('xin_action');?></th>	
              <!--<th><?php echo $this->lang->line('xin_added_by');?></th>-->
								</tr>
							</thead>
			
							<tbody>
													
							</tbody>
						</table>
					</div>
					
					
					
					

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