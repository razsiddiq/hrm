<?php
/* Generate Payslip view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php if(in_array('41',role_resource_ids()) || visa_wise_role_ids() != '') {?>
<div class="row m-b-1">

<!--  <div class="col-md-6">-->
<!--    <div class="panel panel-flat">-->
<!--						<div class="panel-heading">-->
<!--							<h5 class="panel-title">-->
<!--						<strong>Generate Payslip</strong>-->
<!--							</h5>-->
<!--						</div>-->
<!--      <div class="panel-body">-->
<!--          <form class="m-b-1 add form-hrm" method="post" name="set_salary_details" id="set_salary_details">-->
<!--            <div class="row">-->
<!--              <div class="col-md-3 control-label">-->
<!--                <div class="form-group">-->
<!--                  <label for="department">Employee</label>-->
<!--                </div>-->
<!--              </div>-->
<!--              <div class="col-md-9">-->
<!--                <div class="form-group">-->
<!--                  <select id="employee_id" name="employee_id" onChange="select_employee(this.value);" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="Choose an Employee...">-->
<!--                  <option value="0">All Employees</option>-->
<!--                  <?php //foreach($all_employees as $employee) {?>
<!--                  <option value="--><?php //echo $employee->user_id;?><!--"> --><?php //echo $employee->first_name.' '.$employee->middle_name.' '.$employee->last_name;?> <!-- </option>-->
<!--                  <?php //} ?>
<!--                  </select>-->
<!--                </div>-->
<!--              </div>-->
<!--            </div>-->
<!--			  <input type="hidden" id="employee_id" name="employee_id" value="0"/>-->
<!--            <div class="row">-->
<!--              <div class="col-md-3 control-label">-->
<!--                <div class="form-group">-->
<!--                  <label for="month_year">Select Month</label>-->
<!--                </div>-->
<!--              </div>-->
<!--              <div class="col-md-9">-->
<!--                <div class="form-group">-->
<!--				<input type="text" value="--><?php //echo date('Y F');?><!--" id="month_year" name="month_year" readonly class="form-control form_month_year_1">-->
<!--                </div>-->
<!--              </div>-->
<!--            </div>-->
<!--			<div class="clearfix"></div>-->
<!--			<div class="footer-elements" style="padding:0px;">-->
<!--			<button type="submit" class="btn bg-teal-400 save">Search</button>-->
<!--			</div>-->
<!--		 <input type="hidden" id="current_count" value="0" />-->
<!--          </form>-->
<!--      </div>-->
<!--    </div>-->
<!--  </div>-->



  <div class="col-md-6">
        <div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">

						<strong>Filter By</strong>

							</h5>

						</div>
      <div class="panel-body">

	

		   <div class="row">
			  <div class="col-md-3 control-label">
				  <div class="form-group">
					  <label for="month_year">Select Employee</label>
				  </div>
			  </div>
			  <div class="col-md-9">
				  <div class="form-group">
				  <select id="employee_id" name="employee_id" onChange="select_employee(this.value);" class="form-control" data-plugin="select_hrm" data-placeholder="Choose an Employee...">
                  <option value="0">All Employees</option>
                  <?php foreach($all_employees as $employee) {?>
                 <option value="<?php echo $employee->user_id;?>"> <?php echo $employee->first_name.' '.$employee->middle_name.' '.$employee->last_name;?> <option>
                  <?php } ?>
                  </select>
				  </div>
			  </div>
		  </div>



<!-- 
		  <input type="hidden" id="employee_id" name="employee_id" value="0"/> -->
		  <div class="row">
			  <div class="col-md-3 control-label">
				  <div class="form-group">
					  <label for="month_year">Select Month</label>
				  </div>
			  </div>
			  <div class="col-md-9">
				  <div class="form-group">
					  <input type="text" value="<?php echo date('Y F');?>" id="month_year" name="month_year" readonly class="form-control form_month_year_1">

				  </div>
			  </div>
		  </div>
              <div class="row">
        <div class="col-md-3 control-label">
                <div class="form-group">
                  <label for="department">Location</label>
                </div>
              </div>

				<div class="col-md-9">
                  <div class="form-group">
                    <select class="location_value" onChange="select_filter();" class="form-control" data-plugin="select_hrm" data-placeholder="Choose the Status...">
                      <option value="0">ALL LOCATIONS</option>
                      <?php foreach($this->Xin_model->all_locations() as $locs) {?>
                      <option value="<?php echo $locs->location_id;?>" <?php if($locs->location_id==1):?>selected<?php endif;?>> <?php echo $locs->location_name;?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>

				   <div class="col-md-3 control-label">
                <div class="form-group">
                  <label for="department">Departments</label>
                </div>
              </div>
				<div class="col-md-9">
                  <div class="form-group">
                    <select class="department_value" onChange="select_filter();" class="form-control" data-plugin="select_hrm" data-placeholder="Choose the Status...">
                      <option value="1">ACCD</option>
                      <?php foreach($this->Xin_model->all_departments_chart() as $dept) {
                      	if($dept->department_id!='1'){
                      	?>
                      <option value="<?php echo $dept->department_id;?>" <?php //if($session['user_id']==$employee->user_id):selected?>  <?php //endif;?>> <?php echo $dept->department_name;?></option>
                      <?php } } ?>
                    </select>
                  </div>
                </div>

              </div>


			<div class="clearfix"></div>
			<div class="footer-elements" style="padding:0px;">
							<button onclick="select_filter();" class="btn bg-teal-400 save" >Search</button>

  <button type="submit"  onclick="clear_filter();" class="btn bg-teal-400 save">Clear </button>
									</div>

      </div>
    </div>

  </div>
</div>
<div class="row m-b-1">
  <div class="col-md-12">
    <div class="panel panel-flat">
    				<?php if(visa_wise_role_ids() == ''){?>
						<div class="panel-heading">
							<h5 class="panel-title">
							<strong>Payment Info for <span class="text-danger" id="p_month"><?php echo date('F Y');?></span></strong>
							</h5>
							<div class="heading-elements">
							  <button class="btn bg-info generate_sheet">Generate Payroll Sheet</button>

							  <button rel="1" class="btn bg-success make_all_payment">Make All Payment</button>

							  <button rel="2" class="btn bg-danger put_on_hold">Put On Hold</button>

							  <button rel="3" class="btn bg-warning remove_hold" disabled>Remove Hold</button>
							</div>
						</div>
				 	<?php }?>

 <div  data-pattern="priority-columns">
      <form id="frm-example" action="<?php echo site_url("payroll/make_all_payment") ?>" method="POST">

	  <input type="hidden" value="1" class="success_hold_status" name="status"/>
	  <table class="table table-striped" id="xin_table">
          <thead>
            <tr>
     		  <th>
			  <label><input name="select_all" value="1"id="example-select-all" type="checkbox"><span></span></label></th>
              <th>Employee ID</th>
              <th>Name</th>
             <!--<th>Basic Salary</th>
              <th>Net Salary</th>-->
			  <th>Required Working Hours</th>
			  <th>Actual Working Hours</th>
			  <th>Total Late Hours</th>
			  <th>Rate Per Hour (Contract + Bonus)</th>
			  <!--<th>Rate Per Hour (Contract Only)</th>
			  <th>Rate Per Hour (Basic Only)</th>
			  <th>OT Day Rate</th>
			  <th>OT Night Rate</th>
			  <th>OT Holiday Rate</th>-->
			  <th class="text-nowrap">Total Salary</th>
              <!--<th>Details</th>-->
              <th>Status</th>
              <?php if(visa_wise_role_ids() == ''){?>
              <th>Action</th>
          	  <?php }?>
			    </tr>
          </thead>
        </table>

		</form>
      </div>
    </div>
  </div>
</div>
<link href="<?php echo base_url();?>assets/css/icons/fontawesome/styles.min.css" rel="stylesheet" type="text/css">
<style>

input[type="checkbox"]{ position: absolute; opacity: 0; z-index: -1; }
input[type="checkbox"]+span:before { font: 14pt  FontAwesome; content: '\00f096'; display: inline-block; width: 12pt; padding: 2px 0 0 3px; margin-right: 0.5em; }
input[type="checkbox"]:checked+span:before { content: '\00f046'; }
input[type="checkbox"]:disabled + span::before {color: grey;opacity: 0.3;}
</style>
<?php } else{?>
		<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title text-danger">

							 <?php echo $this->lang->line('xin_permission');?>

							</h5>

						</div>
</div>
<?php }?>

<script>
	$(document).ready(function () {
		$('.generate_sheet').click(function () {
			let month_year = $('#month_year').val();
			let location_value = $('.location_value').val();
			let department_value = $('.department_value').val();
			let employee_id = $('#employee_id').val();
			let baseUrl = "<?php echo base_url() ?>";
			let url  = baseUrl+'payroll/generate_monthly_sheet/?month_year='+month_year+'&department_value='+department_value+'&location_value='+location_value+'&employee_id='+employee_id;
			$.ajax({
				url : url,
				type: "GET",
			});
			alert("You'll recieve payroll sheet on your email, please check your inbox in few minutes.")
		})
	})
</script>
