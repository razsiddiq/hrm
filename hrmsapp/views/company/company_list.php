<?php
/* Company view
*/
$session = $this->session->userdata('username');
if(in_array('3a',role_resource_ids())) { ?>

<div class="add-form" style="display:none;">
  <div class="panel panel-flat">  
  	<div class="panel-heading">
      <h5 class="panel-title">							 
      <strong><?php echo $this->lang->line('xin_add_new');?></strong> <?php echo $this->lang->line('module_company_title');?>
      </h5>
      <div class="heading-elements">
          <div class="add-record-btn">      
            <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_hide');?></button>
          </div>
      </div>
		</div>					

    <div class="row m-b-1">
      <div class="col-md-12">
        <form method="post" name="add_company" id="xin-form" enctype="multipart/form-data">
          <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">  
          <div class="col-md-12">
            <div class="col-sm-6">
              <div class="form-group">      
				        <div class="row">
                  <div class="col-md-6">
                    <label for="company_name"><?php echo $this->lang->line('xin_company_name');?><?php echo REQUIRED_FIELD;?></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_company_name');?>" name="name" type="text">
                  </div>
                  <div class="col-md-6">
                    <label for="license_no"><?php echo $this->lang->line('xin_licence_no');?><?php echo REQUIRED_FIELD;?></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_licence_no');?>" name="license_no" type="text">
                  </div>
                </div>				  
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">                     
					          <label for="trading_name"><?php echo $this->lang->line('xin_company_trading');?></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_company_trading');?>" name="trading_name" type="text">					 
					        </div>
                  <div class="col-md-6">
                    <label for="register_no"><?php echo $this->lang->line('xin_company_registration');?></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_company_registration');?>" name="register_no" type="text">
                  </div>
                </div>
              </div>
				      <div class="form-group">
                <div class="row">
                  <div class="col-md-6">                     
					          <label for="contract_start_date"><?php echo $this->lang->line('xin_constartdate');?></label>                     
                    <div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                      <input class="form-control" placeholder="<?php echo $this->lang->line('xin_constartdate');?>" name="contract_start_date" size="16" type="text" value="" readonly>
                      <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>					
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label for="contract_end_date"><?php echo $this->lang->line('xin_conenddate');?></label>                    
					          <div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                      <input class="form-control" placeholder="<?php echo $this->lang->line('xin_conenddate');?>" name="contract_end_date" size="16" type="text" value="" readonly>
                      <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>					
                    </div>
				          </div>
                </div>
              </div>
				      <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <label for="email"><?php echo $this->lang->line('xin_email');?><?php echo REQUIRED_FIELD;?></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_email');?>" name="email" type="email">
                  </div>
                  <div class="col-md-6">
                    <label for="website"><?php echo $this->lang->line('xin_website');?></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_website_url');?>" name="website" type="text">
                  </div>
                </div>
              </div>                
              <div class="form-group">
                <h6><?php echo $this->lang->line('xin_company_logo');?><?php echo REQUIRED_FIELD;?></h6>						
                <input type="file" class="file-input" name="logo" id="logo">
                <span class="help-block"><?php echo $this->lang->line('xin_company_file_type');?></span>					
              </div>
            </div>
            
            <div class="col-sm-6">
              <div class="form-group">
          	    <div class="row">
                  <div class="col-md-6">
                    <label for="license_expiry_date"><?php echo $this->lang->line('xin_licexpirydate');?><?php echo REQUIRED_FIELD;?></label>					
					          <div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                      <input class="form-control" placeholder="<?php echo $this->lang->line('xin_licexpirydate');?>" name="license_expiry_date" size="16" type="text" value="" readonly>
                      <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>					
                    </div>				
                  </div>
                  <div class="col-md-6">
                    <label for="email"><?php echo $this->lang->line('xin_company_type');?><?php echo REQUIRED_FIELD;?></label>
                    <select class="form-control" name="company_type" data-plugin="xin_select" data-placeholder="<?php echo $this->lang->line('xin_company_type');?>">
                      <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
                      <?php foreach($get_company_types as $ctype) {?>
                      <option value="<?php echo $ctype->type_id;?>"> <?php echo $ctype->type_name;?></option>
                      <?php } ?>
                    </select>                    
					        </div>
                </div>
              </div>
				
				      <div class="form-group">
                <label for="address"><?php echo $this->lang->line('xin_address');?><?php echo REQUIRED_FIELD;?></label>                <div class="row">
                  <div class="col-xs-6">
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_address_1');?>" name="address_1" type="text">
                  </div>
                  <div class="col-xs-6">
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_address_2');?>" name="address_2" type="text">
                  </div>
                </div>
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
                <select class="form-control" name="country" data-plugin="xin_select" data-placeholder="<?php echo $this->lang->line('xin_country');?>">
                  <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
                  <?php foreach($all_countries as $country) {?>
                  <option value="<?php echo $country->country_id;?>"> <?php echo $country->country_name;?></option>
                  <?php } ?>
                </select>
              </div>
				
				      <div class="form-group">       
				        <div class="row">
                  <div class="col-md-12">
                    <label for="contact_number"><?php echo $this->lang->line('xin_contact_number');?><?php echo REQUIRED_FIELD;?></label>
                    <div class="clear"></div>					  
                    <div class="input-group">
                      <span class="input-group-addon-custom">					  
                        <select class="form-control change_country_code js-example-templating" name="country_code" required>
                          <?php foreach(phone_numbers_code() as $keys=>$phone_code){?>
                          <option value="<?php echo $keys; ?>-" data-len ="<?php echo $phone_code['length'];?>"   rel="<?php echo $phone_code['country_name'];?>"><?php echo $keys; ?></option>
                          <?php } ?>
                        </select>						  
                      </span>	   
                      <input class="form-control" placeholder="<?php echo $this->lang->line('xin_contact_number');?>" title="<?php echo $this->lang->line('xin_use_numbers');?>" name="contact_number" type="text" pattern="\d*" maxlength="<?php echo MAX_PHONE_DIGITS;?>">
                    </div>
				          </div>
                </div>
              </div>
				
				
              <div class="form-group">
                <h6><?php echo $this->lang->line('xin_company_trade_copy');?></h6>						
                <input type="file" class="file-input" name="trade_copy" id="trade_copy">
                <span class="help-block"><?php echo $this->lang->line('xin_e_details_d_type_file');?></span>					
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

<?php if(in_array('3v',role_resource_ids())) {?>
<div class="panel panel-flat">
  <div class="panel-heading">
    <h5 class="panel-title">      
    <strong><?php echo $this->lang->line('xin_list_all');?></strong> <?php echo $this->lang->line('xin_companies');?>   
    </h5>
    <div class="heading-elements">    
      <?php if(in_array('3a',role_resource_ids())) {?>
        <div class="add-record-btn">
        <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_add_new');?></button>
        </div>
      <?php } ?>
    </div>
  </div>
	
  <table class="table" id="xin_table">						
    <thead>
      <tr>				        
        <th><?php echo $this->lang->line('module_company_title');?> Name</th>
		    <th><?php echo $this->lang->line('xin_legal_type');?></th>
		    <th>Expiry Date</th>		  
        <th><?php echo $this->lang->line('xin_email');?></th>
        <th><?php echo $this->lang->line('xin_contact_number');?></th> 
        <th><?php echo $this->lang->line('xin_country');?></th>
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