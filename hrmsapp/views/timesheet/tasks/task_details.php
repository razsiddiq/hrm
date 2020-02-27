<?php
/* Task Details view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $u_created = $this->Xin_model->read_user_info($session['user_id']);?>
<?php $assigned_ids = explode(',',$assigned_to);?>

<div class="row m-b-1">
  <div class="col-md-4">
 <div class="panel panel-flat">
	<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong>Task Detail</strong> 							
							</h5>
					
						</div>

      <table class="table table-striped m-md-b-0">
        <tbody>
          <tr>
            <th scope="row">Title</th>
            <td class="text-right"><?php echo $task_name;?></td>
          </tr>
          <tr>
            <th scope="row">Created by</th>
            <td class="text-right"><?php echo $created_by;?></td>
          </tr>
          <tr>
            <th scope="row">Start Date</th>
            <td class="text-right"><?php echo $this->Xin_model->set_date_format($start_date);?></td>
          </tr>
          <tr>
            <th scope="row">End Date</th>
            <td class="text-right"><?php echo $this->Xin_model->set_date_format($end_date);?></td>
          </tr>
          <tr>
            <th scope="row">Hours</th>
            <td class="text-right"><?php echo $task_hour;?></td>
          </tr>
        </tbody>
      </table>
    </div>
    <?php if($u_created[0]->user_role_id==1){?>
    <!-- assigned to-->
    <div class="panel panel-flat">
    	<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong>Assigned To</strong> 						
							</h5>
					
						</div>

      <div class="panel-body">
        <form action="<?php echo site_url("timesheet/assign_task") ?>" method="post" name="assign_task" id="assign_task">
          <input type="hidden" name="task_id" id="task_id" value="<?php echo $task_id;?>">
          <div class="box-block">
            <div class="form-group">
              <label for="employees" class="control-label">Employee</label>
              <select multiple class="form-control" name="assigned_to[]" data-plugin="select_hrm" data-placeholder="Employee">
                <?php foreach($all_employees as $employee) {?>
                <option value="<?php echo $employee->user_id?>" <?php if(in_array($employee->user_id,$assigned_ids)):?> selected <?php endif;?>> <?php echo $employee->first_name.' '.$employee->last_name;?></option>
                <?php } ?>
              </select>
            </div>
            <button type="submit" class="btn bg-teal-400 pull-right save">Save</button>
          </div>
        </form>
      </div>
    </div>
    <?php } ?>
  </div>
  <div class="col-md-8">
    <div class="box box-block bg-white">
      <div class="wizard" role="tabpanel">
        <ul class="nav nav-tabs m-b-1" role="tablist">
          <li class="nav-item active"> <a class="nav-link active" data-toggle="tab" href="#detail" role="tab"><i class="icon-home"></i> Detail</a> </li>
          <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#comment" role="tab"><i class="icon-comment"></i> Comment</a> </li>
          <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#notebook" role="tab"><i class="icon-pencil7"></i> Note</a> </li>
          <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#attachment" role="tab"><i class="icon-clipboard5"></i> Task Files</a> </li>
        </ul>
        <div class="tab-content m-b-1">		
          <div role="tabpanel" class="tab-pane animated fadeInRight active fade in" id="detail" style="overflow:auto;">
            <div class="info">
              <blockquote class="blockquote mb-1 mb-md-0"> <?php echo html_entity_decode(stripcslashes($description));?> </blockquote> 
            </div>
			
			<br><br><br>
            <div class="col-md-6">	<br><br><br>
              										  <ul class="media-list">
										<li class="media-header">Assigned To</li>
                <?php if($assigned_to!='' && $assigned_to!='None') {?>
                <?php $employee_ids = explode(',',$assigned_to); foreach($employee_ids as $assign_id) {?>
                <?php $e_name = $this->Xin_model->read_user_info($assign_id);?>
                <?php $_designation = $this->Designation_model->read_designation_information($e_name[0]->designation_id);?>
                <?php
						if($e_name[0]->profile_picture!='' && $e_name[0]->profile_picture!='no file') {
							$u_file = base_url().'uploads/profile/'.$e_name[0]->profile_picture;
						} else {
							if($e_name[0]->gender=='Male') { 
								$u_file = base_url().'uploads/profile/default_male.jpg';
							} else {
								$u_file = base_url().'uploads/profile/default_female.jpg';
							}
						} ?>
										<li class="media">
											<div class="media-left media-middle">
												<a href="#">
													<img src="<?php echo $u_file;?>" class="img-circle" alt="">
												</a>
											</div>

											<div class="media-body">
												<div class="media-heading text-semibold"><?php echo change_fletter_caps($e_name[0]->first_name);?></div>
												<span class="text-muted"><?php echo $_designation[0]->designation_name;?></span>
											</div>
											<?php if($u_created[0]->user_role_id==1){?>
											<div class="media-right media-middle">
												<ul class="icons-list icons-list-extended text-nowrap">
							                    	<li><a href="<?php echo site_url();?>employees/detail/<?php echo $e_name[0]->user_id;?>/" data-popup="tooltip" title="" data-toggle="modal" data-target="#go" data-original-title="View"><i class="icon-arrow-right15"></i></a></li>
							                    
						                    	</ul>
											</div>
											<?php } ?>
											
										</li>
										
					     <?php } ?>
                <?php } else { ?>
                <span>&nbsp;</span>
                <?php } ?>
	                                      </ul>
		 </div>
            <div class="col-md-6">	<br><br><br>
              <form action="<?php echo site_url("timesheet/update_task_status") ?>" method="post" name="update_status" id="update_status">
                <input type="hidden" name="task_id" value="<?php echo $task_id;?>">
                <input type="hidden" id="progres_val" name="progres_val" value="<?php echo $progress;?>">
                				  <ul class="media-list">
										<li class="media-header">Update Status</li>
										</ul>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="progress">Progress</label>
                      <input type="text" id="range_grid">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="status">Status</label>
                      <select class="form-control" name="status" data-plugin="select_hrm" data-placeholder="Status">
                        <option value="0" <?php if($task_status=='0'):?> selected <?php endif; ?>>Not Started</option>
                        <option value="1" <?php if($task_status=='1'):?> selected <?php endif; ?>>In Progress</option>
                        <option value="2" <?php if($task_status=='2'):?> selected <?php endif; ?>>Completed</option>
                        <option value="3" <?php if($task_status=='3'):?> selected <?php endif; ?>>Deferred</option>
                      </select>
                    </div>
                  </div>
                </div>
                <button type="submit" class="btn bg-teal-400 pull-right save">Save</button>
              </form>
            </div>
            <div>&nbsp;</div>
          </div>
          <div role="tabpanel" class="tab-pane animated fadeInRight fade" id="comment">
            <form action="<?php echo site_url("timesheet/set_comment") ?>" method="post" name="set_comment" id="set_comment">
              <input type="hidden" name="comment_task_id" id="comment_task_id" value="<?php echo $task_id;?>">
              <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
              <div class="box-block">
                <div class="form-group">
                  <textarea name="xin_comment" id="xin_comment" class="form-control" rows="4" placeholder="Comment"></textarea>
                </div>
                <button type="submit" class="btn bg-teal-400 pull-right save">Save</button>
              </div>
            </form>
            <div class="clearfix"></div>
	
            <div >
              <table class="table table-hover mb-md-0" id="xin_comment_table">
                <thead>
                  <tr>
                    <th>All Comments</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
          <div role="tabpanel" class="tab-pane animated fadeInRight fade" id="attachment">
            <form action="<?php echo site_url("timesheet/add_attachment") ?>" enctype="multipart/form-data" method="post" name="add_attachment" id="add_attachment">
              <input type="hidden" name="user_id" id="user_id" value="<?php echo $session['user_id'];?>">
              <input type="hidden" name="c_task_id" id="c_task_id" value="<?php echo $task_id;?>">
              <div class="bg-white">
                <div class="box-block">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="task_name">Title</label>
                        <input class="form-control" placeholder="Title" name="file_name" type="text" value="">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class='form-group'>
                        <h6>Attachment File</h6>
                        <input type="file" class="file-input" name="attachment_file" id="attachment_file">                       
                        <small class="help-block">Upload files only: gif,png,jpg,jpeg,txt,doc,docx,xls,xlsx</small> </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" placeholder="Description" name="file_description" rows="4" id="file_description"></textarea>
                      </div>
                    </div>
                  </div>
                  <button type="submit" class="btn bg-teal-400 save">Save</button>
                </div>
              </div>
            </form>
            <div class="clear"></div>
            <h2><strong>Attachment List</strong></h2>
            <div >
              <table class="table table-hover table-striped table-bordered table-ajax-load" id="xin_attachment_table" style="width:100%;">
                <thead>
                  <tr>                    
                    <th>Title</th>
                    <th>Description</th>
                    <th>Date and Time</th><th>Option</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
          <div role="tabpanel" class="tab-pane animated fadeInRight fade" id="notebook">
            <form action="<?php echo site_url("timesheet/add_note") ?>" method="post" name="add_note" id="add_note">
              <input type="hidden" name="note_task_id" id="note_task_id" value="<?php echo $task_id;?>">
              <input type="hidden" name="_uid" value="<?php echo $session['user_id'];?>">
              <div class="box-block">
                <div class="form-group">
                  <textarea name="task_note" id="task_note" class="form-control" rows="7" placeholder="Task Note..."><?php echo $task_note;?></textarea>
                </div>
                <button type="submit" class="btn bg-teal-400 pull-right save">Save</button>
              </div>
            </form>
          </div>
          <!-- tab --> 
        </div>
      </div>
    </div>
  </div>
</div>