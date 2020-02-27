<?php
/* Trainers view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="add-form" style="display:none;">
  <div class="panel panel-flat">
  
  	<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong><?php echo $this->lang->line('xin_add_new');?></strong> Trainer							
							</h5>
							<div class="heading-elements">
							    <div class="add-record-btn">      
	   <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_hide');?></button>
    </div>
		                	</div>
						</div>

    <div class="row m-b-1">
      <div class="col-md-12">
        <form action="<?php echo site_url("trainers/add_trainer") ?>" method="post" name="add_trainer" id="xin-form">
          <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
          
            <div class="panel-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input class="form-control" placeholder="First Name" name="first_name" type="text" value="">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="last_name" class="control-label">Last Name</label>
                        <input class="form-control" placeholder="Last Name" name="last_name" type="text" value="">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                     			  
					  <div class="form-group">
                        <label for="contact_number" >Contact Number</label>
						 <div class="clearfix"></div>
						  <div class="input-group">
												<span class="input-group-addon">
						  <select class="form-control change_country_code" name="country_code">
						  <?php foreach($phone_numbers_code as $keys=>$phone_code){?>
						  <option value="<?php echo $keys; ?>-" rel="<?php echo $phone_code;?>"><?php echo $keys; ?></option>
						  <?php } ?>
						  </select></span>
                        <input class="form-control" placeholder="Contact Number" name="contact_number" type="text" pattern="\d*" maxlength="9" title="Should be Numbers only"></div>

			            </div>
						
						
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="email" class="control-label">Email</label>
                        <input class="form-control" placeholder="Email" name="email" type="text" value="">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="designation">Designation</label>
                        <select class="form-control" name="designation_id" data-plugin="select_hrm" data-placeholder="Designation">
                          <option value=""></option>
                          <?php foreach($all_designations as $designation) {?>
                          <option value="<?php echo $designation->designation_id?>"><?php echo $designation->designation_name?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="expertise">Expertise</label>
                    <textarea class="form-control textarea" placeholder="Expertise" name="expertise" cols="30" rows="5" id="expertise"></textarea>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="address">Address</label>
                <textarea class="form-control" placeholder="Address" name="address" cols="30" rows="3" id="address"></textarea>
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
							 
							<strong><?php echo $this->lang->line('xin_list_all');?></strong> Trainers
							
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
          <th>Full Name</th>
          <th>Designation</th>
          <th>Contact Number</th>
          <th>Email</th>
          <th>Address</th>
		  <th>Action</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
