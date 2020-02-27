<?php
/* Complaints view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong><?php echo $this->lang->line('xin_list_all');?></strong> Complaints
							
							</h5>
					
						</div>

  <div  data-pattern="priority-columns">
    <table class="table table-striped" id="xin_table">
      <thead>
        <tr>         
          <th>Complaint From</th>
          <th>Complaint Against</th>
          <th>Title</th>
          <th>Complaint Date</th>
          <th>Approval Status</th>
          <th>Details</th>
		  <th>Action</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
