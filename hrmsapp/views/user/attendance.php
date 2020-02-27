<?php
/* Attendance view
*/
?>
<?php $session = $this->session->userdata('username');?>
<div class="row m-b-1">
  <div class="col-md-12">
    
<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">						 
							<strong>Attendance</strong> 					
							</h5>
					<div class="heading-elements">
			<select class="form-control" name="month_year" id="month_year" data-plugin="xin_select" data-placeholder="Select Month">
					  <option value="current">Current Month</option>
                      <option value="previous">Previous Month</option>
                      </select>
		                	</div>
						</div>

<div class="panel-body">
<table class="table table-framed"> 
		<thead>
			<tr>
          <th>Date</th>
		  <th>Day</th>
          <th>Login Time</th>
          <th>Logout Time</th>
          <th>Late / Early Out</th>
		  <th>Status</th>		
		  </tr>
		</thead>
	<tbody id="xin_table">
	</tbody>
	</table>

	 </div>
	
    </div>
  </div>
</div>
