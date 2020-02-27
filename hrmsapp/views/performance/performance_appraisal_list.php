<?php
/* Performance Appraisal view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="add-form" style="display:none;">
   <div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong>Give Performance Appraisal</strong> 
							
							</h5>
							<div class="heading-elements">
							    <div class="add-record-btn">
      <button class="btn btn-sm bg-teal-400 add-new-form">Hide</button>
    </div>
		                	</div>
						</div>
  
    <form action="<?php echo site_url("performance_appraisal/add_appraisal") ?>" method="post" name="add_appraisal" id="xin-form" class="form-xin">
      <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
      <div class="row m-b-1">
        <div class="col-md-12">
          <div class="panel-body">
            <div class="row">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-3 control-label">
                    <div class="form-group">
                      <label for="employee">Employee</label>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <select class="select2" data-plugin="select_hrm" data-placeholder="Select an Employee..." name="employee_id" id="employee_id">
                        <option value=""></option>
                        <?php foreach($all_employees as $employee) {?>
                        <option value="<?php echo $employee->user_id;?>"><?php echo change_fletter_caps($employee->first_name);?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-3 control-label">
                    <div class="form-group">
                      <label for="month_year">Select Month</label>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <input class="form-control month_year" placeholder="Select Month" readonly id="month_year" name="month_year" type="text">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row m-b-1">
            <div class="col-md-6">
              <div class="box bg-white">
                <table class="table table-grey-head m-md-b-0">
                  <thead>
                    <tr>
                      <th colspan="5">Technical Competencies</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <th colspan="2">Indicator</th>
                      <th colspan="2">Expected Value</th>
                      <th>Set Value</th>
                    </tr>
                    <tr>
                      <td scope="row" colspan="2">Customer Experience </td>
                      <td colspan="2">Intermediate</td>
                      <td><select name="customer_experience" class="select2" data-plugin="select_hrm">
                          <option value="">None</option>
                          <option value="1"> Beginner</option>
                          <option value="2"> Intermediate</option>
                          <option value="3"> Advanced</option>
                          <option value="4"> Expert / Leader</option>
                        </select></td>
                    </tr>
                    <tr>
                      <td scope="row" colspan="2">Marketing </td>
                      <td colspan="2">Advanced</td>
                      <td><select name="marketing" class="select2" data-plugin="select_hrm">
                          <option value="">None</option>
                          <option value="1"> Beginner</option>
                          <option value="2"> Intermediate</option>
                          <option value="3"> Advanced</option>
                          <option value="4"> Expert / Leader</option>
                        </select></td>
                    </tr>
                    <tr>
                      <td scope="row" colspan="2">Management </td>
                      <td colspan="2">Advanced</td>
                      <td><select name="management" class="select2" data-plugin="select_hrm">
                          <option value="">None</option>
                          <option value="1"> Beginner</option>
                          <option value="2"> Intermediate</option>
                          <option value="3"> Advanced</option>
                          <option value="4"> Expert / Leader</option>
                        </select></td>
                    </tr>
                    <tr>
                      <td scope="row" colspan="2">Administration </td>
                      <td colspan="2">Advanced</td>
                      <td><select name="administration" class="select2" data-plugin="select_hrm">
                          <option value="">None</option>
                          <option value="1"> Beginner</option>
                          <option value="2"> Intermediate</option>
                          <option value="3"> Advanced</option>
                          <option value="4"> Expert / Leader</option>
                        </select></td>
                    </tr>
                    <tr>
                      <td scope="row" colspan="2">Presentation Skill </td>
                      <td colspan="2">Expert / Leader</td>
                      <td><select name="presentation_skill" class="select2" data-plugin="select_hrm">
                          <option value="">None</option>
                          <option value="1"> Beginner</option>
                          <option value="2"> Intermediate</option>
                          <option value="3"> Advanced</option>
                          <option value="4"> Expert / Leader</option>
                        </select></td>
                    </tr>
                    <tr>
                      <td scope="row" colspan="2">Quality Of Work </td>
                      <td colspan="2">Expert / Leader</td>
                      <td><select name="quality_of_work" class="select2" data-plugin="select_hrm">
                          <option value="">None</option>
                          <option value="1"> Beginner</option>
                          <option value="2"> Intermediate</option>
                          <option value="3"> Advanced</option>
                          <option value="4"> Expert / Leader</option>
                        </select></td>
                    </tr>
                    <tr>
                      <td scope="row" colspan="2">Efficiency </td>
                      <td colspan="2">Expert / Leader</td>
                      <td><select name="efficiency" class="select2" data-plugin="select_hrm">
                          <option value="">None</option>
                          <option value="1"> Beginner</option>
                          <option value="2"> Intermediate</option>
                          <option value="3"> Advanced</option>
                          <option value="4"> Expert / Leader</option>
                        </select></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="col-md-6">
              <div class="box bg-white">
                <table class="table table-grey-head m-md-b-0">
                  <thead>
                    <tr>
                      <th colspan="5">Behavioural / Organizational Competencies</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <th colspan="2">Indicator</th>
                      <th colspan="2">Expected Value</th>
                      <th>Set Value</th>
                    </tr>
                    <tr>
                      <td scope="row" colspan="2">Integrity</td>
                      <td colspan="2">Beginner</td>
                      <td><select name="integrity" class="select2" data-plugin="select_hrm">
                          <option value="">None</option>
                          <option value="1"> Beginner</option>
                          <option value="2"> Intermediate</option>
                          <option value="3"> Advanced</option>
                        </select></td>
                    </tr>
                    <tr>
                      <td scope="row" colspan="2">Professionalism</td>
                      <td colspan="2">Beginner</td>
                      <td><select name="professionalism" class="select2" data-plugin="select_hrm">
                          <option value="">None</option>
                          <option value="1"> Beginner</option>
                          <option value="2"> Intermediate</option>
                          <option value="3"> Advanced</option>
                        </select></td>
                    </tr>
                    <tr>
                      <td scope="row" colspan="2">Team Work</td>
                      <td colspan="2">Intermediate</td>
                      <td><select name="team_work" class="select2" data-plugin="select_hrm">
                          <option value="">None</option>
                          <option value="1"> Beginner</option>
                          <option value="2"> Intermediate</option>
                          <option value="3"> Advanced</option>
                        </select></td>
                    </tr>
                    <tr>
                      <td scope="row" colspan="2">Critical Thinking</td>
                      <td colspan="2">Advanced</td>
                      <td><select name="critical_thinking" class="select2" data-plugin="select_hrm">
                          <option value="">None</option>
                          <option value="1"> Beginner</option>
                          <option value="2"> Intermediate</option>
                          <option value="3"> Advanced</option>
                        </select></td>
                    </tr>
                    <tr>
                      <td scope="row" colspan="2">Conflict Management</td>
                      <td colspan="2">Intermediate</td>
                      <td><select name="conflict_management" class="select2" data-plugin="select_hrm">
                          <option value="">None</option>
                          <option value="1"> Beginner</option>
                          <option value="2"> Intermediate</option>
                          <option value="3"> Advanced</option>
                        </select></td>
                    </tr>
                    <tr>
                      <td scope="row" colspan="2">Attendance</td>
                      <td colspan="2">Intermediate</td>
                      <td><select name="attendance" class="select2" data-plugin="select_hrm">
                          <option value="">None</option>
                          <option value="1"> Beginner</option>
                          <option value="2"> Intermediate</option>
                          <option value="3"> Advanced</option>
                        </select></td>
                    </tr>
                    <tr>
                      <td scope="row" colspan="2">Ability To Meet Deadline</td>
                      <td colspan="2">Advanced</td>
                      <td><select name="ability_to_meet_deadline" class="select2" data-plugin="select_hrm">
                          <option value="">None</option>
                          <option value="1"> Beginner</option>
                          <option value="2"> Intermediate</option>
                          <option value="3"> Advanced</option>
                        </select></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="m-b-1">
          <div class="col-md-6">
            <div class="panel-body">
              <div class="form-group">
                <label for="remarks">Remarks</label>
                <textarea class="form-control textarea" placeholder="Remarks" name="remarks" cols="30" rows="15" id="remarks"></textarea>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="footer-elements">
              <label for="remarks">&nbsp;</label>
              <button type="submit" class="btn bg-teal-400 pull-right save u-primary-btn-block">Save</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
 <div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong>List All</strong> Performance Appraisals
							
							</h5>
							<div class="heading-elements">
							    <div class="add-record-btn">
      <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_add_new');?></button>
    </div>
		                	</div>
						</div>

  <div class="panel-body">

    <table class="table table-striped " id="xin_table">
      <thead>
        <tr>
          <th>Action</th>
          <th>Employee</th>
          <th>Department</th>
          <th>Designation</th>
          <th>Appraisal Date</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

