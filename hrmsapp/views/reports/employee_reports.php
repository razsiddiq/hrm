<?php
/* Employees view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php if(in_array('60a',role_resource_ids())) {?>
<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong>Filter By</strong> </strong>
							
							</h5>						
						</div>	
<?php

$cookie_detail=json_decode(get_cookie('employee_reports_cookie',true));

$country_name=return_country_name($cookie_detail->country_details);

$support_documents=$this->Reports_model->getsupport_documents($cookie_detail->country_details);



?>
						
            <div class="panel-body">
			<div class="row">
               <div class="col-md-12">  
			   <div class="col-md-3 no-padding-left">
                  <div class="form-group">
                   <select class="visa_value" class="form-control" data-plugin="select_hrm" data-placeholder="Choose the Status...">
                      <option value="0">All Visas</option>
                      <?php foreach(visa_lists() as $vis) {?>
                      <option value="<?php echo $vis->type_id;?>"><?php echo $vis->type_name;?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
				
				<div class="col-md-3">
                  <div class="form-group">
                    <select class="location_value" class="form-control" data-plugin="select_hrm">
                      <option value="0">All Locations</option>
                      <?php foreach($this->Xin_model->all_locations(@$cookie_detail->country_details) as $locs) {?>
                      <option value="<?php echo $locs->location_id;?>"><?php echo $locs->location_name;?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
				
				
				<div class="col-md-3">
                  <div class="form-group">
                    <select class="department_value" class="form-control" data-plugin="select_hrm">
                      <option value="0">All Departments</option>
                      <?php foreach($this->Xin_model->all_departments_chart() as $dept) {?>
                      <option value="<?php echo $dept->department_id;?>"><?php echo $dept->department_name;?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
				
				
				
				<div class="col-md-3">
                  <div class="form-group">
                    <select class="medical_card_type" class="form-control" data-plugin="select_hrm">
                      <option value="0">All Medical Card Plan</option>
                      <?php $mecard=$this->Xin_model->get_medical_card_type();foreach($mecard->result() as $medical_card_type) {
						 
						  ?>
                      <option value="<?php echo $medical_card_type->type_id;?>"><?php echo $medical_card_type->type_name;?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
				
				<!--<div class="col-md-2">
                  <div class="form-group"> &nbsp;
                    <button type="button" onclick="clear_filter();" class="btn bg-teal-400">Clear</button>
                  </div>
                </div>-->
                </div>
       
</div></div>
					
					
		</div>	
<div class="add-form" style="display:none;">
<div class="panel panel-flat">

<div class="panel-heading">
							<h5 class="panel-title">							 
							<strong></strong> Report Settings						
							</h5>
<div class="heading-elements">
							    <div class="add-record-btn">      
	   <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_hide');?></button>
    </div>
		                	</div>							
						</div>
						<form method="post" action="<?php echo base_url();?>reports/cookie_change/" name="employee_report_settings">
						<div class="panel-body">
						 <div class="row">
						 <div class="col-sm-4">
	 
		 <div class="form-group">
                    <select class="country_details" name="country_details" class="form-control" data-plugin="select_hrm">
                      <?php foreach($this->Location_model->all_locations_bycountry() as $country) {?>
                      <option <?php if($cookie_detail->country_details==$country->country_id):?> selected="selected" <?php endif;?> value="<?php echo $country->country_id;?>"> <?php echo $country->country_name;?></option>
                      <?php } ?>
                    </select>
                  </div>
				  </div> </div>
				   
				  
        <div class="col-sm-4 no-padding-left">
	 
		
			
          
		   <div class="form-group">
            <label for="bank_account_role">Show Employees Paystructure Details</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="switchery" data-size="small" data-color="#3e70c9" data-secondary-color="#ddd" name="paystructure" <?php if($cookie_detail->paystructure=='yes'):?> checked="checked" <?php endif;?> value="yes">
            </div>
          </div>
		  
		  <div class="form-group">
            <label for="bank_account_role">Show Employees Home&Residing Address Details</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="switchery" data-size="small" data-color="#3e70c9" data-secondary-color="#ddd" name="address_details" <?php if($cookie_detail->address_details=='yes'):?> checked="checked" <?php endif;?> value="yes">
            </div>
          </div>
		  
                 
              
        </div>
     
		<div class="col-sm-4">
		<div class="form-group">
		
		  
		  
		  <div class="form-group">
            <label for="bank_account_role">Show Employees Emergency Contact Details</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="switchery" data-size="small" data-color="#3e70c9" data-secondary-color="#ddd" name="emergency_contact" <?php if($cookie_detail->emergency_contact=='yes'):?> checked="checked" <?php endif;?> value="yes">
            </div>
          </div>
		  
		  
		  <div class="form-group">
            <label for="bank_account_role">Show Employees Shift Details</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="switchery" data-size="small" data-color="#3e70c9" data-secondary-color="#ddd" name="shift_details" <?php if($cookie_detail->shift_details=='yes'):?> checked="checked" <?php endif;?> value="yes">
            </div>
          </div>
		  
       
		</div>
		
		</div>
        
		<div class="col-sm-4">
		<div class="form-group">
		
		 
       <div class="form-group">
            <label for="bank_account_role">Show Employees Bank Details</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="switchery" data-size="small" data-color="#3e70c9" data-secondary-color="#ddd" name="bank_details" <?php if($cookie_detail->bank_details=='yes'):?> checked="checked" <?php endif;?> value="yes">
            </div>
          </div>
		   <div class="form-group">
            <label for="bank_account_role">Show Employees Basic Details</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="switchery" data-size="small" data-color="#3e70c9" data-secondary-color="#ddd" name="basic_details" <?php if($cookie_detail->basic_details=='yes'):?> checked="checked" <?php endif;?> value="yes">
            </div>
          </div>
		</div>
		
		</div>
        
		
		<div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <div class="text-right">
                <button type="submit" class="btn bg-teal-400 save"><?php echo $this->lang->line('xin_save');?><i class="icon-spinner3 spinner position-left"></i></button>
              </div>
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
							<strong></strong> Reports of Employees							
							</h5>	
							<div class="heading-elements">
						
							    <div class="add-record-btn">
      <button class="btn btn-sm bg-teal-400 add-new-form">Settings</button>
							</div>
		                	</div>
						</div>
					
	
	
						
						

  <div data-pattern="priority-columns">
 
			<table class="table" id="xin_table_employees">
						
							<thead>
							    <tr>
		  <th>Status</th>							
		  <th>Name</th>
		  <th>Employee Id</th>       
          <th>DOJ</th>		  
		  <th>Department</th>
		  <?php if($cookie_detail->basic_details=='yes'){?>
		  <th>DOL</th>
		  <th>Biometric ID</th>
		  <th>Email</th>
		  <th>Personal Email</th>
		  <th>DOB</th>
		  <th>Gender</th>
		  <th>Marital Status</th>
		  <th>Blood Group</th>
		  <th>Contact No</th>
		  <th>Languages Known</th>
		  <th>Nationality</th>
		  <th>Reporting Manager</th>
		  <?php } ?>
		  <?php 
		  if($country_name=='United Arab Emirates'){	
			echo '<th>Contract Name</th>
		  <th>Contract Start Date</th>
		  <th>Contract End Date</th>';
		  }
		
		
		  if($support_documents){
			foreach($support_documents as $sup_doc){
				if($sup_doc->type_name=='Visa' || $sup_doc->type_name=='Medical Card'){
				echo '<th>'.$sup_doc->type_name.' Name</th>';
				}
				echo '<th>'.$sup_doc->type_name.' Number</th>';
				echo '<th>'.$sup_doc->type_name.' Issue Date</th>';
				echo '<th>'.$sup_doc->type_name.' Expiry Date</th>';
			}
		}
		?>
		  <!--		
		  <th>Visa Name</th>
		  <th>Visa Number</th>
		  <th>Visa Issue Date</th>
		  <th>Visa Expiry Date</th>	
		  <th>Driving Licence Number</th>
		  <th>Driving Licence Issue Date</th>
		  <th>Driving Licence Expiry Date</th>
		  <th>Passport Number</th>
		  <th>Passport Issue Date</th>
		  <th>Passport Expiry Date</th>
		  <th>EID Number</th>
		  <th>EID Expiry Date</th>
		  <th>Labour Id Number</th>
		  <th>Labour Id Issue Date</th>
		  <th>Labour Id Expiry Date</th>
		  <th>Medical Name</th>
		  <th>Medical Number</th>
		  <th>Medical Issue Date</th>
		  <th>Medical Expiry Date</th>-->
		
		  
		  <?php if($cookie_detail->paystructure=='yes'){?>
		  <th>Basic Salary</th>
		  <th>House Rent Allowance</th>
		  <th>Travelling Allowance</th>
		  <th>Food Allowance</th>
		  <th>Other Allowance</th>
		  <th>Additional Benefits</th>
		  <th>Bonus</th>
		  <th>Agreed Bonus</th>
		  <th>Agency Fees</th>
		  <th>Salary Based on Contract</th>
		  <th>Salary with Bonus</th>
		  <th>Effective From Date</th>
		  <?php } ?>		  
		  <?php if($cookie_detail->emergency_contact=='yes'){?>
		  <th>Emergency Contact Relation</th>
		  <th>Relation Name</th>
		  <th>Relation Phone No</th>
		  <th>Address 1</th>
		  <th>Address 2</th>
		  <th>Relation City</th>
		  <th>Relation State</th>
		  <th>Relation Zipcode</th>
		  <th>Relation Country</th>
		  <?php } ?>		  
		  <?php if($cookie_detail->bank_details=='yes'){?>
		  <th>Account Number</th>
		  <th>Bank Name</th>
		  <th>Bank Code</th>
		  <th>Bank Branch</th>
		  <?php } ?>
		  <?php if($cookie_detail->address_details=='yes'){?>
		  <th>Home Address 1</th>
		  <th>Home Address 2</th>
		  <th>Home City</th>
		  <th>Home State</th>
		  <th>Home Zipcode</th>
		  <th>Home Country</th>
		  <th>Residing Address 1</th>
		  <th>Residing Address 2</th>
		  <th>Residing City</th>
		  <th>Residing State</th>
		  <th>Residing Zipcode</th>
		  <th>Residing Country</th>
		  <?php } ?>	
		  <?php if($cookie_detail->shift_details=='yes'){?>
		  <th>Shift In Time</th>
		  <th>Shift Out Time</th>
		  <th>Week Off</th>
		  <th>Location Name</th>
		  <?php } ?>
		  <?php if($cookie_detail->basic_details=='yes'){?>
		  <th>Profile Completion</th>
		  <?php } ?>
        </tr>
							</thead>
			
							<tbody>
													
							</tbody>
						</table>
	
  </div>
</div>



<?php } else { ?>
		<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title text-danger">							 
							 <?php echo $this->lang->line('xin_permission');?>					
							</h5>				
						</div></div>

<?php } ?>
<script>
$(document).ready(function() {	
	xin_table_employees();
});
</script>