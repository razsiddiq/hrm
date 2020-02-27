<?php
/* Email Templates view
*/
?>
<?php $session = $this->session->userdata('username');?>

   <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>List All</strong>  Email Template
									</h6>
							
								</div>
  <div  data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table" style="width:100%;">
      <thead>
        <tr>
         
          <th>Template Name</th>
          <th>Subject</th>
<th>Status</th>
 <th>Action</th>
          
        </tr>
      </thead>
    </table>
  </div>
</div>