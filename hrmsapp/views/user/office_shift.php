<?php
/* Office Shift view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong>My</strong> Office Shift
							
							</h5>
					
						</div>
 
  <div  data-pattern="priority-columns">
    <table class="table table-striped" id="xin_table">
      <thead>
        <tr>
          <th>Shift Date</th>
          <th>Duration</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
