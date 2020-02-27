<?php
/* Awards view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="add-form" style="display:none;">
  <div class="panel panel-flat">
  <div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong>Add New</strong> Award
							
							</h5>
							<div class="heading-elements">
							    <div class="add-record-btn">      
	   <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_hide');?></button>
    </div>
		                	</div>
						</div>
   
    <div class="row m-b-1">
      <div class="col-md-12">
        <form action="<?php echo site_url("awards/add_award") ?>" method="post" name="add_award" id="xin-form">
          <input type="hidden" name="_user" value="<?php echo $session['user_id'];?>">
          <div class="bg-white">
            <div class="box-block">
              <div class="col-lg-12">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="employee">Employee</label>
                    <select name="employee_id" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="Choose an Employee...">
                      <option value=""></option>
                      <?php foreach($all_employees as $employee) {?>
                      <option value="<?php echo $employee->user_id;?>"> <?php echo $employee->first_name.' '.$employee->last_name;?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="award_type">Award Type</label>
                        <select name="award_type_id" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="Choose Award Type...">
                          <option value=""></option>
                          <?php foreach($all_award_types as $award_type) {?>
                          <option value="<?php echo $award_type->award_type_id;?>"><?php echo $award_type->award_type;?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="award_date">Date</label>
                        <input class="form-control date" placeholder="Award Date" readonly name="award_date" type="text" value="">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="month_year">Month & Year</label>
                        <input class="form-control d_month_year" placeholder="Month & Year of Award" readonly name="month_year" id="month_year" type="text" value="<?php echo Date('Y F');?>">
						
                      </div>
                    </div>
                  </div>
                  
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control textarea" placeholder="Description" name="description" cols="30" rows="15" id="description"></textarea>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="gift">Gift</label>
                        <input class="form-control" placeholder="Gift" name="gift" type="text">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="cash">Cash</label>
                        <input class="form-control" placeholder="Cash" name="cash" type="text">
                      </div>
                    </div>
                    <div class="col-md-6">
                    <div class='form-group'>
                      <div><label for="photo">Award Photo</label></div>
                      	<input type="file" class="file-input" name="award_picture" id="award_picture">
                       
                      <small class="help-block">Upload files only: gif,png,jpg,jpeg</small> </div>
                  </div>
				  
				  
				   <div class="col-md-12">
                   <div class="form-group">
                   <label for="award_information">Award Information</label>
                   <textarea class="form-control" placeholder="Award Information" name="award_information" cols="30" rows="3" id="award_information"></textarea>
                   </div>
                   </div>
				   
				   <div class="footer-elements">						
									
  <button type="submit" class="btn bg-teal-400 save"><?php echo $this->lang->line('xin_save');?></button>
									</div>
				   
                  </div>
          
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="box box-block bg-white"><div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong><?php echo $this->lang->line('xin_list_all');?></strong> Awards
							
							</h5>
							<div class="heading-elements">
							    <div class="add-record-btn">
	  
	  <button class="btn btn-sm bg-teal-400 add-new-form">Add New</button>
	   
	   
    </div>
		                	</div>
						</div>  


  <div data-pattern="priority-columns">
    <table class="table table-striped " id="xin_table">
      <thead>
        <tr>
          
          <th>Employee ID</th>
          <th>Employee Name</th>
          <th>Award Name</th>
          <th>Gift</th>
          <th>Cash Price</th>
          <th>Month & Year</th>
		  <th>Action</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

