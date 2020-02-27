<?php
/* Warning view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong><?php echo $this->lang->line('xin_list_all');?></strong> Warnings
							
							</h5>
					
						</div>
  
  <div  data-pattern="priority-columns">
    <table class="table table-striped" id="xin_table">
      <thead>
        <tr>         
          <th>Warning Date</th>
          <th>Subject</th>
          <th>Warning Type</th>
          <th>Approval Status</th>
          <th>Warning By</th>
          <th>Details</th>
 <th>Action</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
