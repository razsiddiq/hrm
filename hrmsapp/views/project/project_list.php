<?php
/* Project view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="add-form" style="display:none;">
  <div class="panel panel-flat">
  
  	<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong><?php echo $this->lang->line('xin_add_new');?></strong> <?php echo $this->lang->line('xin_project');?>
							
							</h5>
							<div class="heading-elements">
							    <div class="add-record-btn">      
	   <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_hide');?></button>
    </div>
		                	</div>
						</div>	
   
    <div class="panel-body">
        <form action="<?php echo site_url("project/add_project") ?>" method="post" name="add_project" id="xin-form">
          <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
          <div class="bg-white">
            <div class="box-block">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="title"><?php echo $this->lang->line('xin_title');?></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_title');?>" name="title" type="text">
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="client_name"><?php echo $this->lang->line('xin_client_name');?></label>
                        <input class="form-control" placeholder="<?php echo $this->lang->line('xin_client_name');?>" name="client_name" type="text">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="award_date"><?php echo $this->lang->line('module_company_title');?></label>
                        <select name="company_id" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('module_company_title');?>...">
                          <option value=""></option>
                          <?php foreach($all_companies as $company) {?>
                          <option value="<?php echo $company->company_id;?>"> <?php echo $company->name;?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="start_date"><?php echo $this->lang->line('xin_start_date');?></label>
                        <input class="form-control date" placeholder="<?php echo $this->lang->line('xin_start_date');?>" readonly name="start_date" type="text">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="end_date"><?php echo $this->lang->line('xin_end_date');?></label>
                        <input class="form-control date" placeholder="<?php echo $this->lang->line('xin_end_date');?>" readonly name="end_date" type="text">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="employee"><?php echo $this->lang->line('xin_p_priority');?></label>
                      <select name="priority" id="select2-demo-6" class="form-control select-border-color border-warning" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_p_priority');?>">
                        <option value="1">Highest</option>
                        <option value="2">High</option>
                        <option value="3">Normal</option>
                        <option value="4">Low</option>
                      </select>
                    </div>
                  </div>
                </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="description"><?php echo $this->lang->line('xin_description');?></label>
                    <textarea class="form-control textarea" placeholder="<?php echo $this->lang->line('xin_description');?>" name="description" cols="30" rows="15" id="description"></textarea>
                  </div>
                </div>
              </div>
              <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="employee"><?php echo $this->lang->line('xin_project_manager');?></label>
                  <select multiple name="assigned_to[]" id="select2-demo-6" class="form-control select-border-color border-warning" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_project_manager');?>">
                    <option value=""></option>
                    <?php foreach($all_employees as $employee) {?>
                    <option value="<?php echo $employee->user_id;?>"> <?php echo change_fletter_caps($employee->first_name);?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="summary"><?php echo $this->lang->line('xin_summary');?></label>
                  <textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_summary');?>" name="summary" cols="30" rows="1" id="summary"></textarea>
                </div>
              </div>
            </div>
              <div class="text-right">
              <button type="submit" class="btn bg-teal-400 save">Save<i class="icon-spinner3 spinner position-left"></i></button>
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
							 
							<strong><?php echo $this->lang->line('xin_list_all');?></strong> <?php echo $this->lang->line('xin_projects');?></strong>
							
							</h5>
							<div class="heading-elements">
							    <div class="add-record-btn">
      <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_add_new');?></button>
    </div>
		                	</div>
						</div>

  <div class="">
    <table class="table table-striped" id="xin_table">
      <thead>
        <tr>         
        <th><?php echo $this->lang->line('xin_project_summary');?></th>
        <th><?php echo $this->lang->line('xin_p_priority');?></th>
        <th><?php echo $this->lang->line('xin_p_enddate');?></th>
        <th><?php echo $this->lang->line('dashboard_xin_progress');?></th>
        <th><?php echo $this->lang->line('xin_project_users');?></th>
		<th><?php echo $this->lang->line('xin_action');?></th>
        </tr>
      </thead>
    </table>
  </div>
</div>
