<?php
/* Office Shift view
*/

?>
<?php $session = $this->session->userdata('username');?>
<?php if(in_array('31v',role_resource_ids()) || visa_wise_role_ids() != '') {?>
<?php if(in_array('31a',role_resource_ids()) || visa_wise_role_ids() != '') {?>
<div class="add-form" style="display:none;">
  <div class="panel panel-flat">
  	<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong>Add New</strong> <?php echo $breadcrumbs;?></li>
							
							</h5>
							<div class="heading-elements">
							    <div class="add-record-btn">      
	   <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_hide');?></button>
    </div>
		                	</div>	
						</div>

 
    <div class="row m-b-1">
      <div class="col-md-12">
        <form action="<?php echo site_url("timesheet/bus_lateness_timing") ?>" method="post" name="bus_lateness_timing" id="xin-form">
            <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
      
              <div class="col-lg-12">
       
              
      <div class="col-md-6">
	  
	  <div class="form-group">
 <label for="name">Choose the Location</label>
                    <select class="form-control"  name="location_id" data-plugin="select_hrm" data-placeholder="Choose the Location...">
                      <option value="">&nbsp;</option>
                      <?php foreach($this->Xin_model->all_locations() as $locs) {?>
                      <option value="<?php echo $locs->location_id;?>"> <?php echo $locs->location_name;?></option>
                      <?php } ?>
                    </select>
                  </div>
				  
                         <div class="form-group">
<label for="name">Select Date</label>
                  
				   <div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                    <input class="form-control" placeholder="Select Date" name="select_date" id="select_date" size="16" type="text"  value="<?php echo date('d F Y');?>" readonly>
                    <span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>					
                </div>

</div>
</div>
<div class="col-lg-6">
		    <div class="form-group">
                    <label for="name">Bus Scheduled Time</label>
                    <input class="form-control timepicker clear-1" placeholder="Bus Scheduled Time" readonly name="bus_scheduled_time" type="text" value="">
                  </div>
				   <div class="form-group">
                    <label for="name">Bus Actual Arrived Time</label>
                    <input class="form-control timepicker clear-1" placeholder="Bus Arrived Time" readonly name="bus_arrived_time" type="text" value="">
					
                  </div>
		    </div>
			
			
<div class="footer-elements">						
									
  <button type="submit" class="btn bg-teal-400 save"><?php echo $this->lang->line('xin_save');?></button>
									</div>
			  </div>
       
		
        </form>
      </div>
    </div>
  
  
  
  </div>
</div>
<?php   } ?> 
<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong>Bus Lateness List</strong> 
							
							</h5>
					<div class="heading-elements">							
							
							  <div class="form-group"> &nbsp;
						<?php if(in_array('31a',role_resource_ids())) {?> 
							   <button class="btn bg-teal-400 add-new-form">Add New</button>	
	<?php } ?>
				</div>
						</div>
						</div>
						
 <div data-pattern="priority-columns">
    <table class="table" id="xin_bus_lateness" >
      <thead>
        <tr>
		  <th>Location Name</th>
          <th>Bus Late Date</th>
		  <th>Bus Scheduled Time</th>		  
          <th>Bus Actual Arrived Time</th>
		  <th>Lateness</th>
          <th>Added By</th>
          <th>Created Date</th>
		  <th>Updated Date</th>
		  <th>Action</th>
        </tr>
      </thead>
	 
    </table>
  </div>
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


<link href="<?php echo base_url();?>assets/css/icons/fontawesome/styles.min.css" rel="stylesheet" type="text/css">
<style>
.select2-search__field{width:100% !important;}
input[type="checkbox"]{ position: absolute; opacity: 0; z-index: -1; }
input[type="checkbox"]+span:before { font: 14pt  FontAwesome; content: '\00f096'; display: inline-block; width: 12pt; padding: 2px 0 0 3px; margin-right: 0.5em; }
input[type="checkbox"]:checked+span:before { content: '\00f046'; }
input[type="checkbox"]:disabled + span::before {color: grey;opacity: 0.3;}
</style>