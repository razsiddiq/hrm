<?php
/* User Roles view
*/
?>
<?php $session = $this->session->userdata('username');?>
<link href="<?php echo base_url();?>assets/css/icons/fontawesome/styles.min.css" rel="stylesheet" type="text/css">
<style>
.checker span::after {
    top: 0px;
    left: 1px;
}
input[type="checkbox"] { position: absolute; opacity: 0; z-index: -1; }
input[type="checkbox"]+span:before { font: 14pt  FontAwesome; content: '\00f096'; display: inline-block; width: 12pt; padding: 2px 0 0 3px; margin-right: 0.5em; }
input[type="checkbox"]:checked+span:before { content: '\00f046'; }

</style>

<?php if(in_array('14v',role_resource_ids())) {?>

<?php if(in_array('14a',role_resource_ids())) {?>
<div class="add-form" style="display:none;">
<div class="panel panel-flat">
  <div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong>Set New</strong> Role
							
							</h5>
							<div class="heading-elements">
							    <div class="add-record-btn">      
	   <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_hide');?></button>
    </div>
		                	</div>
						</div>
						
 
    <div class="row m-b-1">
      <div class="col-md-12">
        <form action="<?php echo site_url("roles/add_role") ?>" method="post" name="add_role" id="xin-form">
          <input type="hidden" name="_user" value="<?php echo $session['user_id'];?>">
          <div class="bg-white">
            <div class="box-block">
              <div class="col-lg-12">
                <div class="col-md-4">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="role_name">Role Name</label>
                        <input class="form-control" placeholder="Role Name" name="role_name" type="text" value="">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <input type="checkbox" name="role_resources[]" value="0" checked style="display:none;"/>
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="role_access">Select Access</label>
                        <select class="form-control custom-select" id="role_access" data-plugin="select_hrm" name="role_access"  data-placeholder="Select Access">
                          <option value="">&nbsp;</option>
                          <option value="1">All Menu Access</option>
                          <option value="2">Custom Menu Access (More)</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <button type="submit" class="btn bg-teal-400 pull-right save">Save</button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="resources">Resources</label>
                        <div id="all_resources">
                          <div class="demo-section k-content">
                            <div>
                              <div id="treeview_r1"></div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <div class="col-md-5 mb-5" style="border-left: 1px solid #ddd;">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <div id="all_resources">
                          <div class="demo-section k-content">
                            <div>
                              <div id="treeview_r2"></div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
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
							 
							<strong><?php echo $this->lang->line('xin_list_all');?></strong> Roles
							
							</h5>
							<div class="heading-elements">
							   <?php if(in_array('14a',role_resource_ids())) {?>
							    <div class="add-record-btn">
	  
	  <button class="btn btn-sm bg-teal-400 add-new-form">Add New</button>
	   
	   
							   </div><?php } ?>
		                	</div>
						</div>  
  <div data-pattern="priority-columns">
    <table class="table table-striped" id="xin_table">
      <thead>
        <tr>          
          <th>Role ID</th>
          <th>Role Name</th>
          <th>Menu Permission</th>
          <th>Added Date</th>
		  <th>Action</th>
        </tr>
      </thead>
    </table>
  </div>
</div>


 <?php if(in_array('14a',role_resource_ids())) {?>
<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong></strong> Add Employee's Custom Role 


							
							</h5>
		
						</div>
						
					       <div class="panel-body">

          <div class="row">
            
					 <div class="col-md-6">
                  <div class="form-group" id="employee_id_div">
                    <select name="employee_id" id="employee_id" class="form-control" data-plugin="select_hrm" data-placeholder="Choose Employee...">
                      <option value="0">All Employees</option>
                      <?php foreach($all_employees as $employee) {?>
                      <option value="<?php echo $employee->user_id;?>"> <?php echo change_fletter_caps($employee->first_name.' '.$employee->middle_name.' '.$employee->last_name);?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>	

<div class="col-md-4">
<button type="button" onclick="add_roles('add','');" class="btn bg-teal-400 mr-20">Add</button>
<button type="button" onclick="clear_roles();" class="btn bg-teal-400">Clear</button>
</div>
			 </div> 
			 
			 
		
			 
			 
			 
			 </div>			
						
  
</div>
 <?php } ?>
<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong><?php echo $this->lang->line('xin_list_all');?></strong> Employe's Custom Roles
							
							</h5>
							
						</div>  
  <div data-pattern="priority-columns">
    <table class="table table-striped" id="xin_table_custom">
      <thead>
        <tr>  
		  <th>Custom Role ID</th>		
          <th>Employee Name</th>
          <!--<th>Added By</th>-->
          <th>Added Date</th>
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
<style type="text/css">
.k-in { display:none !important; }
</style>