<?php
/* Worksheets view
*/
?>
<?php $session = $this->session->userdata('username');?>
<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong><?php echo $this->lang->line('xin_list_all');?></strong> Worksheets
							
							</h5>
					
						</div>

  <div  data-pattern="priority-columns">
    <table class="table table-striped" id="xin_table">
      <thead>
        <tr>          
          <th>Title</th>
          <th>End Date</th>
          <th>Status</th>
          <th>Assigned To</th>
          <th>Created By</th>
          <th>Progress</th>
		  <th>Action</th>
        </tr>
      </thead>
    </table>
  </div>
  <!-- responsive --> 
</div>
