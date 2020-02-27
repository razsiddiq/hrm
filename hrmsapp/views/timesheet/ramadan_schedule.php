<?php
/* Ramadan Schedule view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php if(in_array('34v',role_resource_ids())) {?>
<div class="row m-b-1">
  
  <?php if(in_array('34a',role_resource_ids())) {?>
  <div class="add-form" style="display:none;">
   <div class="col-md-12">
    <div class="panel panel-flat">
  	<div class="panel-heading">
							<h5 class="panel-title">							 
							<strong>Add New</strong> <?php echo $breadcrumbs;?>						
							</h5>
						<div class="heading-elements">
							    <div class="add-record-btn">      
	   <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_hide');?></button>
    </div>
		                	</div>
						</div>
<div class="panel-body">
      <form class="m-b-1" action="<?php echo site_url("timesheet/add_ramadan_schedule") ?>" method="post" name="add_ramadan_schedule" id="xin-form">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="name">Event Name</label> 
              <input type="text" class="form-control" name="event_name" placeholder="Event Name">
            </div>
          </div>

		  <div class="col-md-6">
		   <div class="form-group">
		   <label for="name">Country</label>
		  <select class="form-control" name="country" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_country');?>">
                      <?php foreach($this->Location_model->all_locations_bycountry() as $country) {?>
                      <option value="<?php echo $country->country_id;?>"> <?php echo $country->country_name;?></option>
                      <?php } ?>
                    </select>
					</div>
		  </div>
        </div>
        <div class="row">

     
		  
		  <div class="col-md-6">
		       <div class="form-group">
              <label for="start_date">Select Date</label>
               <input type="text" class="form-control daterange-basic" name="select_date" value="<?php echo date('d F Y');?>-<?php echo date('d F Y');?>" readonly> 
            </div>
		  </div>
		  
		 
		  
		  <div class="col-md-6">
		<div class="form-group">   
<label class="control-label" for="tooltip">Reduced Hour(s) <span data-toggle="tooltip" data-placement="top" title="" data-original-title="When ramadan time this should be deduct from the employee total working scheduled hours."><i class="icon-bubble-notification" style="margin-top: -10px;"></i></span></label>		   
 <input class="form-control timepicker" placeholder="Reduced Hour(s)" readonly name="reduced_hours" type="text" value="02:00">		   
        
          </div>
		</div>
		  
		  

        </div>
		
		
		
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="description">Description</label>
              <textarea class="form-control textarea" placeholder="Description" name="description" cols="30" rows="15" id="description"></textarea>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="is_publish">Status</label>
              <select name="is_publish" class="select2" data-plugin="select_hrm" data-placeholder="Choose Status...">
                <option value="1">Published</option>
                <option value="0">Un Published</option>
              </select>
            </div>
          </div>
        </div>
        <button type="submit" class="btn bg-teal-400 pull-right save">Save</button>
      </form> 
    
	</div>
	</div>
  </div>
   </div>
<?php } ?> 
  <div class="col-md-12">

    <div class="panel panel-flat">
  	<div class="panel-heading">
							<h5 class="panel-title">							 
							<strong>List All</strong> <?php echo $breadcrumbs;?>						
							</h5>

      <?php if(visa_wise_role_ids() == ''){?>
			<div class="heading-elements">
		    <div class="add-record-btn">
          <button class="btn btn-sm bg-teal-400 add-new-form">Add New</button>
        </div>
    	</div>
      <?php }?>

						</div>
   
      <div  data-pattern="priority-columns">
        <table class="table table-striped" id="xin_table">
          <thead>
            <tr>
              <th>Event Name</th>
			  <th>Country</th>
              <th>Status</th>
              <th>Start Date</th>
              <th>Start End</th>
			  <th>Action</th>
            </tr>
          </thead>
        </table>
      </div>
      <!-- responsive --> 
    </div>
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

<style>
.select2-search__field{ width: auto !important;}
</style>
