<?php
/* Awards view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong><?php echo $this->lang->line('xin_list_all');?></strong> Awards
							
							</h5>
					
						</div>

  <div  data-pattern="priority-columns">
    <table class="table table-striped" id="xin_table">
      <thead>
        <tr>          
          <th>Employee ID</th>
          <th>Employee Name</th>
          <th>Award Name</th>
          <th>Gift</th>
          <th>Cash Price</th>
          <th>Month & Year</th>
		  <th>Action</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
