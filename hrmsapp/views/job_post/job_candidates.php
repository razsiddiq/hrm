<?php
/* Announcement view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong><?php echo $this->lang->line('xin_list_all');?></strong> Job Candidates
							
							</h5>
	
						</div>
 
  <div data-pattern="priority-columns">
    <table class="table table-striped" id="xin_table">
      <thead>
        <tr>         
          <th>Job Title</th>
          <th>Candidate Name</th>
          <th>Email</th>
          <th>Status</th>
          <th>Apply Date</th>
		   <th>Action</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
