<?php
/* Database Backup Log view
*/
?>
<?php $session = $this->session->userdata('username');?>

	  <div class="panel panel-flat">
  	<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong><?php echo $breadcrumbs;?></strong>
							
							</h5>
					
						</div>
						
						
	
    <div class="row m-b-1">
	 <form action="<?php echo site_url("settings/preview_upload") ?>" method="post" name="add_leave" id="xin-form" enctype="multipart/form-data">
      <div class="col-md-12">
       
          <input type="hidden" name="_user" value="<?php echo $session['user_id'];?>">
     
     
              <div class="col-lg-12">
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="leave_type" class="control-label">Import Type</label>
                    <select class="form-control" onchange="leave_types(this.value);" name="leave_type" id="leave_type" data-plugin="select_hrm" data-placeholder="Import Type">  <option value=""></option>             
                      <option value="Leave">Leave</option> 
                      <option value="Leave_Cash_Conversion">Leave Cash Conversion</option>
					  <option value="employee_info">Employee Information</option>
                    </select>
				
					<a id="leave_format" style="display:none;" href="<?php echo site_url("settings/exports_leaves_data"); ?>"class="text-teal-400">Download leave format</a>
					<a id="leave_conversion" style="display:none;" href="<?php echo site_url("settings/exports_leave_conversion_data"); ?>" class="text-teal-400">Download leave cash conversion format</a>
					<a id="employee_info" style="display:none;" href="<?php echo site_url("settings/exports_employee_data"); ?>" class="text-teal-400">Download employee information format</a>
                  </div>
				
                </div>
              <input type="hidden" name="preview_type" value="preview" id="preview_type"/>
			   <div class="col-md-6">
                  <div class="form-group">
						     <label for="end_date">CSV File</label>
						  <input type="file" class="file-input-no" data-show-preview="false" name="file">
						 
						  <small class="help-block">Upload files only: csv</small></div>
					  
						  
				
                </div>
				  <div class="col-md-3">
        <div class="form-group">
					<label for="end_date"></label> 
					<br>
						  <button type="submit" onclick="change_slug('preview');" class="btn bg-teal-400 save mt-5">Preview & Check</button>
						</div>
						  
				
                </div>
				
				  	 
					 			
									
           
			
		
									</div>
             
            
      <div id="preview_view" class="col-md-12">
      </div>
              </div>						
									
        </form>
      </div>
    </div>
 
<style>
.form-control {
    padding: 4px 12px !important;
}.popover {
    color: black;
}
.picker {
    width: 16em !important;
    position: relative !important;
}
</style>