<?php
/* Training view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="add-form" style="display:none;">
   <div class="panel panel-flat">
  
  	<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong><?php echo $this->lang->line('xin_add_new');?></strong> Training							
							</h5>
							<div class="heading-elements">
							    <div class="add-record-btn">      
	   <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_hide');?></button>
    </div>
		                	</div>
						</div>

    <div class="row m-b-1">
      <div class="col-md-12">
        <form action="<?php echo site_url("training/add_training"); ?>" method="post" name="add_training" id="xin-form">
          <input type="hidden" name="_user" value="<?php echo $session['user_id'];?>">
          
            <div class="panel-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="training_type">Training Type</label>
                        <select class="form-control" name="training_type" data-plugin="select_hrm" data-placeholder="Training Type">
                          <option value=""></option>
                          <?php foreach($all_training_types as $training_type) {?>
                          <option value="<?php echo $training_type->training_type_id?>"><?php echo $training_type->type?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="trainer">Trainer</label>
                        <select class="form-control" name="trainer" data-plugin="select_hrm" data-placeholder="Trainer">
                          <option value=""></option>
                          <?php foreach($all_trainers as $trainer) {?>
                          <option value="<?php echo $trainer->trainer_id?>"><?php echo change_fletter_caps($trainer->first_name.' '.$trainer->middle_name.' '.$trainer->last_name);?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="training_cost">Training Cost</label>
                        <input class="form-control" placeholder="Training Cost" name="training_cost" type="text" value="">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input class="form-control date" placeholder="Start Date" readonly name="start_date" type="text" value="">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="end_date">End Date</label>
                        <input class="form-control date" placeholder="End Date" readonly name="end_date" type="text" value="">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="employee" class="control-label">Employee</label>
                        <select multiple class="form-control" name="employee_id[]" data-plugin="select_hrm" data-placeholder="Employee">
                          <option value=""></option>
                          <?php foreach($all_employees as $employee) {?>
                          <option value="<?php echo $employee->user_id;?>"><?php echo change_fletter_caps($employee->first_name.' '.$employee->middle_name.' '.$employee->last_name);?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control textarea" placeholder="Description" name="description" id="description"></textarea>
                  </div>
                </div>
              </div>
             <button type="submit" class="btn bg-teal-400 pull-right save"><?php echo $this->lang->line('xin_save');?></button>
            </div>
      
        </form>
      </div>
    </div>
  </div>
</div>
<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong><?php echo $this->lang->line('xin_list_all');?></strong> Training
							
							</h5>
							<div class="heading-elements">
							    <div class="add-record-btn">
      <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_add_new');?></button>
    </div>
		                	</div>
						</div>
 
  <div  data-pattern="priority-columns">
    <table class="table table-striped" id="xin_table">
      <thead>
        <tr>          
          <th>Employee</th>
          <th>Training Type</th>
          <th>Trainer</th>
          <th>Training Duration</th>
          <th>Cost</th>
          <th>Status</th>
		  <th>Action</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
