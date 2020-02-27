<?php
/* Performance Indicator view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="add-form" style="display:none;">
  <div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong>Set New</strong> Indicator
							
							</h5>
							<div class="heading-elements">
							    <div class="add-record-btn">
      <button class="btn btn-sm bg-teal-400 add-new-form">Hide</button>
    </div>
		                	</div>
						</div>


    <div class="row m-b-1">
      <div class="col-md-12">
        <form action="<?php echo site_url("performance_indicator/add_indicator") ?>" method="post" name="add_performance_indicator" id="xin-form" class="form-hrm">
          <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
      
              <div class="col-lg-12">
                <div class="col-md-3 control-label">
                  <div class="form-group">
                    <label for="designation">Designation</label>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <select class="select2" data-plugin="select_hrm" data-placeholder="Select Designation..." name="designation_id">
                      <option value=""></option>
                      <?php foreach($all_designations as $designation) {?>
                      <option value="<?php echo $designation->designation_id?>"><?php echo $designation->designation_name?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              </div>
<div class="col-lg-12">
              <div class="col-md-6">
                <h5 class="panel-title"><span> <strong>Technical Competencies</strong> </span></h5>
<br>
                <div class="row">
                  <div class="col-md-6 control-label">
                    <div class="form-group">
                      <label>Customer Experience</label>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <select name="customer_experience" class="select2" data-plugin="select_hrm">
                        <option value="">None</option>
                        <option value="1"> Beginner</option>
                        <option value="2"> Intermediate</option>
                        <option value="3"> Advanced</option>
                        <option value="4"> Expert / Leader</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 control-label">
                    <div class="form-group">
                      <label>Marketing </label>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <select name="marketing" class="select2" data-plugin="select_hrm"	>
                        <option value="">None</option>
                        <option value="1"> Beginner</option>
                        <option value="2"> Intermediate</option>
                        <option value="3"> Advanced</option>
                        <option value="4"> Expert / Leader</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 control-label">
                    <div class="form-group">
                      <label>Management</label>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <select name="management" class="select2" data-plugin="select_hrm">
                        <option value="">None</option>
                        <option value="1"> Beginner</option>
                        <option value="2"> Intermediate</option>
                        <option value="3"> Advanced</option>
                        <option value="4"> Expert / Leader</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 control-label">
                    <div class="form-group">
                      <label>Administration</label>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <select name="administration" class="select2" data-plugin="select_hrm">
                        <option value="">None</option>
                        <option value="1"> Beginner</option>
                        <option value="2"> Intermediate</option>
                        <option value="3"> Advanced</option>
                        <option value="4"> Expert / Leader</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 control-label">
                    <div class="form-group">
                      <label>Presentation Skill</label>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <select name="presentation_skill" class="select2" data-plugin="select_hrm">
                        <option value="">None</option>
                        <option value="1"> Beginner</option>
                        <option value="2"> Intermediate</option>
                        <option value="3"> Advanced</option>
                        <option value="4"> Expert / Leader</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 control-label">
                    <div class="form-group">
                      <label>Quality Of Work</label>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <select name="quality_of_work" class="select2" data-plugin="select_hrm">
                        <option value="">None</option>
                        <option value="1"> Beginner</option>
                        <option value="2"> Intermediate</option>
                        <option value="3"> Advanced</option>
                        <option value="4"> Expert / Leader</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 control-label">
                    <div class="form-group">
                      <label>Efficiency</label>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <select name="efficiency" class="select2" data-plugin="select_hrm">
                        <option value="">None</option>
                        <option value="1"> Beginner</option>
                        <option value="2"> Intermediate</option>
                        <option value="3"> Advanced</option>
                        <option value="4"> Expert / Leader</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <h5 class="panel-title"><span> <strong>Technical Competencies</strong> </span></h5><br>
                <div class="row">
                  <div class="col-md-6 control-label">
                    <div class="form-group">
                      <label>Integrity</label>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <select name="integrity" class="select2" data-plugin="select_hrm">
                        <option value="">None</option>
                        <option value="1"> Beginner</option>
                        <option value="2"> Intermediate</option>
                        <option value="3"> Advanced</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 control-label">
                    <div class="form-group">
                      <label>Professionalism</label>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <select name="professionalism" class="select2" data-plugin="select_hrm">
                        <option value="">None</option>
                        <option value="1"> Beginner</option>
                        <option value="2"> Intermediate</option>
                        <option value="3"> Advanced</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 control-label">
                    <div class="form-group">
                      <label>Team Work</label>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <select name="team_work" class="select2" data-plugin="select_hrm">
                        <option value="">None</option>
                        <option value="1"> Beginner</option>
                        <option value="2"> Intermediate</option>
                        <option value="3"> Advanced</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 control-label">
                    <div class="form-group">
                      <label>Critical Thinking</label>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <select name="critical_thinking" class="select2" data-plugin="select_hrm">
                        <option value="">None</option>
                        <option value="1"> Beginner</option>
                        <option value="2"> Intermediate</option>
                        <option value="3"> Advanced</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 control-label">
                    <div class="form-group">
                      <label>Conflict Management</label>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <select name="conflict_management" class="select2" data-plugin="select_hrm">
                        <option value="">None</option>
                        <option value="1"> Beginner</option>
                        <option value="2"> Intermediate</option>
                        <option value="3"> Advanced</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 control-label">
                    <div class="form-group">
                      <label>Attendance</label>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <select name="attendance" class="select2" data-plugin="select_hrm">
                        <option value="">None</option>
                        <option value="1"> Beginner</option>
                        <option value="2"> Intermediate</option>
                        <option value="3"> Advanced</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 control-label">
                    <div class="form-group">
                      <label>Ability To Meet Deadline</label>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <select name="ability_to_meet_deadline" class="select2" data-plugin="select_hrm">
                        <option value="">None</option>
                        <option value="1"> Beginner</option>
                        <option value="2"> Intermediate</option>
                        <option value="3"> Advanced</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 control-label">
                    <div class="form-group"> &nbsp; </div>
                  </div>
                  <div class="col-md-5 control-label">
                    <div class="form-group">
                      <button type="submit" class="btn pull-right bg-teal-400 save">Save</button>
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
  <div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong>List All</strong> Performance Indicator
							
							</h5>
							<div class="heading-elements">
							    <div class="add-record-btn">
      <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_add_new');?></button>
    </div>
		                	</div>
						</div>

  <div class="panel-body">
    <table class="table table-striped" id="xin_table">
      <thead>
        <tr>          
          <th>Designation</th>
          <th>Department</th>
          <th>Added By</th>
          <th>Created At</th>
		  <th>Action</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
