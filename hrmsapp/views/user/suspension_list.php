<?php
/* Suspension view
*/
?>
<?php $session = $this->session->userdata('username');?>
<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong><?php echo $this->lang->line('xin_list_all');?></strong> Suspensions
							
							</h5>
						
						</div>	

  <div  data-pattern="priority-columns">
    <table class="table table-striped" id="xin_table">
      <thead>
        <tr>          
          <th>Employee</th>
          <th>Suspensions Type</th>
          <th>Suspensions Start Date</th>
		  <th>Suspensions End Date</th>
          <th>Approval Status</th>
		  <th>Action</th>
        </tr>
      </thead>
    </table>
  </div>
</div>