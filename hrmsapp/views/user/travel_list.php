<?php
/* Travel view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong><?php echo $this->lang->line('xin_list_all');?></strong> Travels
							
							</h5>
					
						</div>

  <div  data-pattern="priority-columns">
    <table class="table table-striped" id="xin_table">
      <thead>
        <tr>          
          <th>Employee</th>
          <th>Travel Purpose</th>
          <th>Visit Place</th>
          <th>Start Date</th>
          <th>End Date</th>
          <th>Approval Status</th>
          <th>Added By</th>
<th>Action</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
