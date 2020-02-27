	<?php
/*
* Tickets view
*/
$session = $this->session->userdata('username');
?>

<div class="add-form" style="display:none;">
   <div class="panel panel-flat">
  
  	<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong>Create New </strong> Ticket			
							</h5>
							<div class="heading-elements">
							    <div class="add-record-btn">      
	   <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_hide');?></button>
    </div>
		                	</div>
						</div>

    <div class="row m-b-1">
      <div class="col-md-12">
        <form action="<?php echo site_url("tickets/add_ticket") ?>" method="post" name="add_ticket" id="xin-form">
          <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
        
            <div class="panel-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="task_name">Subject</label>
                    <input class="form-control" placeholder="Subject" name="subject" type="text" value="">
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="employees">Ticket for Employee</label>
                        <select class="form-control" name="employee_id" data-plugin="select_hrm" data-placeholder="Employee">
                          <option value=""></option>
                          <?php foreach($all_employees as $employee) {?>
                          <option value="<?php echo $employee->user_id?>"> <?php echo $employee->first_name.' '.$employee->middle_name.' '.$employee->last_name;?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="ticket_priority" class="control-label">Priority</label>
                        <select name="ticket_priority" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="Select Priority">
                          <option value=""></option>
                          <option value="1">Low</option>
                          <option value="2">Medium</option>
                          <option value="3">High</option>
                          <option value="4">Critical</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="description">Ticket Description</label>
                    <textarea class="form-control textarea" placeholder="Description" name="description" cols="30" rows="5" id="description"></textarea>
                  </div>
                </div>
              </div>
              <button type="submit" class="btn bg-teal-400 pull-right save">Save</button>
            </div>
         
		 
        </form>
      </div>
    </div>
  </div>
</div>
<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong><?php echo $this->lang->line('xin_list_all');?></strong> Tickets
							
							</h5>
							<div class="heading-elements">
							    <div class="add-record-btn">
      <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_add_new');?></button>
    </div>
		                	</div>
						</div>

  <div  data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table">
      <thead>
        <tr>          
          <th>Ticket Code</th>
          <th>Subject</th>
          <th>Employee</th>
          <th>Priority</th>
          <th>Status</th>
          <th>Date</th>
		  <th>Action</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
