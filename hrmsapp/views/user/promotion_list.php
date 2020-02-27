<?php
/* Promotion view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong><?php echo $this->lang->line('xin_list_all');?></strong> Promotions
							
							</h5>
					
						</div>
 
  <div  data-pattern="priority-columns">
    <table class="table table-striped" id="xin_table">
      <thead>
        <tr>         
          <th>Employee Name</th>
          <th>Promotion Title</th>
          <th>Promotion Date</th>
          <th>Description</th>
          <th>Added By</th>
		  <th>Action</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
