<?php
/* Job Interview view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="add-form" style="display:none;">
  <div class="panel panel-flat">
  
  	<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong><?php echo $this->lang->line('xin_add_new');?></strong> Job	Interview						
							</h5>
							<div class="heading-elements">
							    <div class="add-record-btn">      
	   <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_hide');?></button>
    </div>
		                	</div>
						</div>

    <div class="row m-b-1">
      <div class="col-md-12">
        <form action="<?php echo site_url("job_interviews/add_interview") ?>" method="post" name="add_interview" id="xin-form">
          <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
          
            <div class="panel-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="job_post">Job Post</label>
                        <select class="form-control" id="job_id" name="job_id" data-plugin="select_hrm" data-placeholder="Job Post">
                          <option value=""></option>
                          <?php foreach($all_interview_jobs as $jobs):?>
                          <option value="<?php echo $jobs->job_id;?>"><?php echo $jobs->job_title;?></option>
                          <?php endforeach?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="interview_date">Interview Date</label>
                        <input class="form-control date" placeholder="Interview Date" readonly name="interview_date" type="text" value="">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group" id="interviewees_ajax">
                        <label for="interviewees">Interviewees (Selected Candidates)</label>
                        <select class="form-control" name="interviewees[]" data-plugin="select_hrm" data-placeholder="Candidates">
                          <option value=""></option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="interview_place">Place Of Interview</label>
                        <input class="form-control" placeholder="Place Of Interview" name="interview_place" type="text" value="">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="interview_time" class="control-label">Interview Time</label>
                        <input class="form-control timepicker" placeholder="Interview Time" readonly name="interview_time" type="text" value="">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="interviewers">Interviewers (Employees)</label>
                        <select multiple class="form-control" name="interviewers[]" data-plugin="select_hrm" data-placeholder="Employees">
                          <option value=""></option>
                          <?php foreach($all_employees as $employee) {?>
                          <option value="<?php echo $employee->user_id;?>"><?php echo change_fletter_caps($employee->first_name);?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="description">Job Interview Description</label>
                    <textarea class="form-control textarea" placeholder="Job Interview Description" name="description" cols="30" rows="15" id="description"></textarea>
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
							 
							<strong><?php echo $this->lang->line('xin_list_all');?></strong> Jobs Interviews
							
							</h5>
							<div class="heading-elements">
							    <div class="add-record-btn">
      <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_add_new');?></button>
    </div>
		                	</div>
						</div>

  <div data-pattern="priority-columns">
    <table class="table table-striped" id="xin_table">
      <thead>
        <tr>
          
          <th>Job Post</th>
          <th>Selected Candidates</th>
          <th>Interview Place</th>
          <th>Interview Date & Time</th>
          <th>Interviewers</th>
          <th>Added By</th>
		  <th>Action</th>
        </tr>
      </thead>
    </table>
  </div>
</div>