<?php $session = $this->session->userdata('username');?>
<?php if(in_array('bio-user-view',role_resource_ids()) || visa_wise_role_ids() != '') {?>

<?php if(in_array('bio-user-add',role_resource_ids()) || visa_wise_role_ids() != '') {?>
<div class="add-form" style="display:none;">
  <div class="panel panel-flat">
  	<div class="panel-heading">
      <h5 class="panel-title">
        <strong>Add New</strong> <?php echo $breadcrumbs;?></li>
      </h5>
      <div class="heading-elements">
        <div class="add-record-btn">
          <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_hide');?></button>
        </div>
      </div>
	  </div>
    <div class="row m-b-1">
      <div class="col-md-12">
        <form action="<?php echo site_url("timesheet/add_biometric_user") ?>" method="post" name="add_biometric_user" id="xin-form">
          <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
          <div class="col-lg-12">     
            <div class="col-md-3">  
              <div class="form-group">
                <label for="name">Choose the Location</label>
                <select class="form-control employee_shows"  name="location_id" id="location_id_val" data-plugin="select_hrm" data-placeholder="Choose the Location...">
                  <option value="">&nbsp;</option>
                    <?php foreach($this->Xin_model->all_locations() as $locs) {?>
                      <option value="<?php echo $locs->location_id;?>"> <?php echo $locs->location_name;?></option>
                    <?php } ?>
                </select>
              </div>       
            </div>  
            <div class="col-md-3">  
              <div class="form-group">
                  <label for="name">Choose the Employee</label>
                  <select name="employee_id" id="employee_id" class="form-control" data-plugin="select_hrm" data-placeholder="Choose the Employee...">
                    <option value="">&nbsp;</option>
                      <?php foreach($this->Xin_model->read_user_info_manual_attendance_shift('','') as $employee) {?>
                        <option value="<?php echo $employee->user_id;?>"> <?php echo change_fletter_caps($employee->first_name.' '.$employee->middle_name.' '.$employee->last_name);?></option>
                      <?php } ?>
                  </select>
              </div>
            </div>  
            <div class="col-lg-3">
              <div class="form-group">
                <label for="name">Biometric ID</label>
                <input class="form-control" placeholder="Biometric ID" name="biometric_id" type="text" value="">
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group" style="margin-top: 2em;">
                <button type="submit" class="btn bg-teal-400 save"><?php echo $this->lang->line('xin_save');?></button>
              </div>
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
    <strong>Filter</strong>
    </h5>
  </div>
  <div class="panel-body">
    <form action="#" method="post" name="biometric_search" id="biometric_search">
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <select class="location_value" class="form-control" data-plugin="select_hrm" data-placeholder="Choose the Status...">
              <option value="0">All Locations</option>
              <?php foreach($this->Xin_model->all_locations() as $locs) {?>
              <option value="<?php echo $locs->location_id;?>"> <?php echo $locs->location_name;?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="col-md-3">
        <div class="form-group">
            <select class="department_value" class="form-control" data-plugin="select_hrm" data-placeholder="Choose the Status...">
              <option value="0">All Departments</option>
              <?php foreach($this->Xin_model->all_departments_chart() as $dept) {?>
                <option value="<?php echo $dept->department_id;?>"> <?php echo $dept->department_name;?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <select name="employee_id" id="user_id" class="form-control" data-plugin="select_hrm" data-placeholder="Choose an Employee...">
              <option value="all">All Employees</option>
              <?php foreach($this->Xin_model->all_employees() as $employee) {?>
                <option value="<?php echo $employee->user_id;?>"> <?php echo change_fletter_caps($employee->first_name.' '.$employee->middle_name.' '.$employee->last_name);?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group"> &nbsp;
            <button type="submit"  class="btn bg-teal-400 save mr-20">Filter</button>
            <button type="button" onclick="clear_filter();" class="btn bg-teal-400">Clear</button>
          </div>
        </div>
      </div>
    </form>

  </div>
</div>

<div class="panel panel-flat">
  <div class="panel-heading">
    <h5 class="panel-title">
      <strong>Biometric Users List</strong>
    </h5>
    <div class="heading-elements">
      <div class="form-group"> &nbsp;
      <?php if(in_array('bio-user-add',role_resource_ids())) {?>				
        <button class="btn bg-teal-400 add-new-form">Add New</button>							 
      <?php } ?>
    </div>
  </div>
</div>

 <div data-pattern="priority-columns">
    <table class="table" id="xin_table" >
      <thead>
        <tr>
          <th>Employee Id</th>
          <th>Employee Name</th>
          <th>Location</th>
          <th>Biometric ID</th>
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
