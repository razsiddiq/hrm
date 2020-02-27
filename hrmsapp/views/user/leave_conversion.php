<?php
/* Leave view
*/
?>
<?php $session = $this->session->userdata('username');?>
<div class="add-form1" style="display:none;">

	
	<div class="panel panel-flat">
  	<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong>Add Leave</strong> Conversion
							
							</h5>
							<div class="heading-elements">
							    <div class="add-record-btn">      
	   <button class="btn btn-sm bg-teal-400 add-new-form1"><?php echo $this->lang->line('xin_hide');?></button>
    </div>
		                	</div>
						</div>
						
						
	
    <div class="row m-b-1">
      <div class="col-md-12">
        <form action="#" method="post" id="xin-form-conv" enctype="multipart/form-data">
          <input type="hidden" id="user_id" name="_user" value="<?php echo $session['user_id'];?>">   
              <div class="col-lg-12">
                <div class="col-md-6">                  
				   <div class="row">
                    <div class="col-md-12">
                   <input type="hidden" value="employee_type" id="employee_type"/>  
<input type="hidden" name="employee_id_con" id="employee_id_con" value="<?php echo $session['user_id'];?>">	
 <label for="days" class="control-label">Choose Days</label>				  
					  <div id="sh_message_conversion">
				   
					  </div>
                    </div>
                  </div>			  
                 
				   
              
                </div>
               
			
			  
			   <div class="col-md-6" >
			  	  
						  	  <div class="form-group">
                <label for="summary">Comments</label>
                <textarea class="form-control" placeholder="Comments" name="comments" cols="30" rows="3" id="comments"></textarea>
              </div>
			  
			  </div>
			  
			  
			   <div class="clearfix"></div>
			   
			<div class="footer-elements">		
			<button type="button" class="btn bg-teal-400 show_leave_conv">Convert</button>
									</div>
              </div>
              
      
									
									
        </form>
      </div>
    </div>
  </div>
</div>

<div class="panel panel-flat">
<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong>List All</strong> Leave Conversions													
							</h5>
				<div class="heading-elements">
						
						
							    <div class="add-record-btn">
      <button class="btn btn-sm bg-teal-400 add-new-form1">Add New</button>
							</div>
		                	</div>
				
						</div>
  <div data-pattern="priority-columns">
 
			<table class="table" id="xin_table_conversion">
		
							<thead>
							    <tr>
          

          <th><?php  echo $this->lang->line('xin_employees_full_name');?></th>
          <th>Conversion Days Count</th>
          <th>Comments</th>
          <th>Added Date</th>
          <th>Added By</th>
		  
          <th>Created At</th>
		  <th>Status</th>
		  <th>Action</th>
        </tr>
							</thead>
			
							<tbody>
													
							</tbody>
						</table>
	
  </div>
</div>