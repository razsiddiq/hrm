<?php
/* Location view
*/
?>
<?php $session = $this->session->userdata('username');?>

<?php if(in_array('4a',role_resource_ids())) {?>
<div class="add-form" style="display:none;">
  <div class="panel panel-flat">

	
	 	<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong><?php echo $this->lang->line('xin_add_new');?></strong> <?php echo $this->lang->line('xin_location');?></strong>
							
							</h5>
							<div class="heading-elements">
							    <div class="add-record-btn">      
	   <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_hide');?></button>
    </div>
		                	</div>
						</div>
						
						
    <div class="row m-b-1">
      <div class="col-md-12">
        <form class="m-b-1 add" method="post" name="add_location" id="xin-form">
          <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
   
              <div class="col-md-12">
                <div class="col-sm-6">
				
                  <div class="form-group">
                    <label for="company_name"><?php echo $this->lang->line('module_company_title');?><?php echo REQUIRED_FIELD;?></label>
                    <select class="form-control" name="company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('module_company_title');?>">
                      <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
                      <?php foreach($all_companies as $company) {?>
                      <option value="<?php echo $company->company_id;?>"> <?php echo $company->name;?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="form-group">
 <div class="row">
                      <div class="col-md-12">
                    <label for="name"><?php echo $this->lang->line('xin_location_name');?><?php echo REQUIRED_FIELD;?></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_location_name');?>" name="name" type="text">
</div>

           
</div>
                 

 </div>


                  <div class="form-group">
                    <label for="email"><?php echo $this->lang->line('xin_email');?></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_email');?>" name="email" type="email">
                  </div>
                  <div class="form-group">
                    
                    <div class="row">
                      <div class="col-md-6">
                       <label for="phone"><?php echo $this->lang->line('xin_phone');?><?php echo REQUIRED_FIELD;?></label>
                     <div class="clear"></div>
						  
						
											<div class="input-group">
												<span class="input-group-addon-custom">
											
						  
						  <select class="form-control change_country_code js-example-templating" name="country_code" required>
						  <?php foreach(phone_numbers_code() as $keys=>$phone_code){?>
						  <option value="<?php echo $keys; ?>-" data-len ="<?php echo $phone_code['length'];?>"   rel="<?php echo $phone_code['country_name'];?>"><?php echo $keys; ?></option>
						  <?php } ?>
						  </select>
						  
						  
												</span>
												  <input class="form-control" placeholder="<?php echo $this->lang->line('xin_phone');?>" title="<?php echo $this->lang->line('xin_use_numbers');?>" name="phone" type="text" pattern="\d*" maxlength="<?php echo MAX_PHONE_DIGITS;?>">
               
											</div>
									
										
										
										
				
			          </div>
                      <div class="col-md-6">
                      	<label for="xin_faxn"><?php echo $this->lang->line('xin_faxn');?></label>
              
						  
						  <div class="clear"></div>
			
               <div class="input-group">
												<span class="input-group-addon-custom">					  
						  <select class="form-control change_country_code js-example-templating" name="country_code1" required>
						  <?php foreach(phone_numbers_code() as $keys=>$phone_code){?>
						  <option value="<?php echo $keys; ?>-" data-len ="<?php echo $phone_code['length'];?>"   rel="<?php echo $phone_code['country_name'];?>"><?php echo $keys; ?></option>
						  <?php } ?>
						  </select>
						  </span>
		                  <input class="form-control" placeholder="<?php echo $this->lang->line('xin_faxn');?>" title="<?php echo $this->lang->line('xin_use_numbers');?>" name="fax" type="text" pattern="\d*" maxlength="<?php echo MAX_PHONE_DIGITS;?>">
						  
						</div>  
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-6">
                        <label for="email"><?php echo $this->lang->line('xin_view_locationh');?><?php echo REQUIRED_FIELD;?></label>
                    	<select class="form-control" name="location_head" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_view_locationh');?>">
                      <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
                      <?php foreach($all_employees as $employee) {?>
                      <option value="<?php echo $employee->user_id;?>"> <?php echo $employee->first_name.' '.$employee->middle_name.' '.$employee->last_name;?></option>
                      <?php } ?>
                    </select>
                      </div>
                      <div class="col-md-6">
                      	<label for="website"><?php echo $this->lang->line('xin_view_locationmgr');?></label>
                        <select class="form-control" name="location_manager" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_view_locationmgr');?>">
                      <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
                      <?php foreach($all_employees as $employee) {?>
                      <option value="<?php echo $employee->user_id;?>"> <?php echo $employee->first_name.' '.$employee->middle_name.' '.$employee->last_name;?></option>
                      <?php } ?>
                    </select>
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="address"><?php echo $this->lang->line('xin_address');?><?php echo REQUIRED_FIELD;?></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_address_1');?>" name="address_1" type="text">
                    <br>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_address_2');?>" name="address_2" type="text">
                    <br>
                    <div class="row">
                      <div class="col-xs-5">
                        <input class="form-control" placeholder="<?php echo $this->lang->line('xin_city');?>" name="city" type="text">
                      </div>
                      <div class="col-xs-4">
                        <input class="form-control" placeholder="<?php echo $this->lang->line('xin_state');?>" name="state" type="text">
                      </div>
                      <div class="col-xs-3">
                        <input class="form-control" placeholder="<?php echo $this->lang->line('xin_zipcode');?>" name="zipcode" type="text">
                      </div>
                    </div>
                    <br>
                    <select class="form-control" name="country" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_country');?>">
                      <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
                      <?php foreach($all_countries as $country) {?>
                      <option value="<?php echo $country->country_id;?>"> <?php echo $country->country_name;?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
<div class="clearfix"></div>
			<div class="footer-elements">
							
  <button type="submit" class="btn bg-teal-400 save"><?php echo $this->lang->line('xin_save');?></button>
		
									</div>            


			</div>
       
         
        </form>
      </div>
    </div>
  </div>
</div>
<?php } ?>

<?php if(in_array('4v',role_resource_ids())) {?>
<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong><?php echo $this->lang->line('xin_list_all');?></strong> <?php echo $this->lang->line('xin_locations');?></strong>
							
							</h5>
							<div class="heading-elements">
							    
								<?php if(in_array('4a',role_resource_ids())) {?>
								<div class="add-record-btn">
      <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_add_new');?></button>
    </div>
								<?php } ?>
		                	</div>
						</div>
		<table class="table" id="xin_table">
						
							<thead>
								<tr>				      
          <th><?php echo $this->lang->line('xin_location_name');?></th>
          <th><?php echo $this->lang->line('xin_view_locationh');?></th>
          <th><?php echo $this->lang->line('module_company_title');?></th>
          <th><?php echo $this->lang->line('xin_city');?></th>
          <th><?php echo $this->lang->line('xin_country');?></th>
          <!--<th><?php //echo $this->lang->line('xin_location_currency');?></th>-->
		   <th><?php echo $this->lang->line('xin_action');?></th>
								</tr>
							</thead>
			
							<tbody>
													
							</tbody>
						</table>
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