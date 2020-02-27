<?php
/* Announcement view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong><?php echo $this->lang->line('xin_list_all');?></strong> Announcements
							
							</h5>
					
						</div>

  <div  data-pattern="priority-columns">
    <table class="table table-striped" id="xin_table">
      <thead>
        <tr>          
          <th>Title</th>
          <th>Summary</th>
          <th>Published For</th>
          <th>Start Date</th>
          <th>End Date</th>
          <th>Published By</th>
<th>Action</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
