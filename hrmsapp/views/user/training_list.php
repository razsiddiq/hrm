<?php
/* Training view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong><?php echo $this->lang->line('xin_list_all');?></strong> Training
							
							</h5>
					
						</div>

  <div  data-pattern="priority-columns">
    <table class="table table-striped" id="xin_table">
      <thead>
        <tr>
          
          <th>Employee</th>
          <th>Training Type</th>
          <th>Trainer</th>
          <th>Training Duration</th>
          <th>Cost</th>
          <th>Status</th>
		  <th>Action</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
