<?php
/* Payslips view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong>List All</strong> Payslips
							
							</h5>
					
						</div>

  <div  data-pattern="priority-columns">
    <table class="table table-striped" id="xin_table">
      <thead>
        <tr>          
    
          <th>Payment Month</th>
          <th>Payment Date</th>
          <th>Paid Amount</th>
          <th>Payment Type</th>
       
		  <th>Action</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
