<?php
/* Expense view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="add-form" style="display:none;">

    <div class="panel panel-flat">
  	<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong><?php echo $this->lang->line('xin_add_new');?></strong> <?php echo $this->lang->line('xin_expense');?></strong>
							
							</h5>
							<div class="heading-elements">
							    <div class="add-record-btn">      
	   <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_hide');?></button>
    </div>
		                	</div>
						</div>
		
		

    <div class="row m-b-1">
      <div class="col-md-12">
        <form action="<?php echo site_url("expense/add_expense") ?>" method="post" name="add_expense" id="xin-form" enctype="multipart/form-data">
         
              <div class="col-md-12">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="expense_type"><?php echo $this->lang->line('xin_expense_type');?></label>
                    <select name="expense_type" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_choose_expense_type');?>...">
                      <option value=""></option>
                      <?php foreach($all_expense_types as $expense_type) {?>
                      <option value="<?php echo $expense_type->type_id;?>"><?php echo $expense_type->type_name;?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="purchase_date"><?php echo $this->lang->line('xin_purchase_date');?></label>
                        <input class="form-control date" placeholder="<?php echo $this->lang->line('xin_purchase_date');?>" readonly name="purchase_date" type="text" value="">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="amount"><?php echo $this->lang->line('xin_amount');?></label>
                        <input class="form-control" placeholder="<?php echo $this->lang->line('xin_amount');?>" name="amount" type="text" value="">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="gift"><?php echo $this->lang->line('xin_purchased_by');?></label>
                        <select name="employee_id" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_choose_an_employee');?>...">
                          <option value=""></option>
                          <?php foreach($all_employees as $employee) {?>
                          <option value="<?php echo $employee->user_id;?>"> <?php echo $employee->first_name.' '.$employee->last_name;?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                     <!-- <div class='form-group'>
                        <h6><?php echo $this->lang->line('xin_bill_copy');?></h6>
                        <span class="btn bg-teal-400 btn-file">
                         <i class="icon-file-plus"></i> Browse <input type="file" name="bill_copy" id="bill_copy">
                          </span>
                        <br>
                        <small><?php echo $this->lang->line('xin_expense_allow_files');?></small> </div>-->
						
						
						<div class="form-group">
					<h6><?php echo $this->lang->line('xin_bill_copy');?></h6>						
					<input type="file" class="file-input" name="bill_copy" id="bill_copy">
					<span class="help-block"><?php echo $this->lang->line('xin_expense_allow_files');?></span>					
					</div>
					
                    </div>
                  </div>
                  <div class="add_billycopy_fields"></div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="description"><?php echo $this->lang->line('xin_remarks');?></label>
                    <textarea class="form-control textarea" name="remarks" cols="25" rows="6" id="description"></textarea>
                  </div>
                </div>
				
						<div class="clearfix"></div>
			<div class="footer-elements">
											
		   <button type="submit" class="btn bg-teal-400 save"><?php echo $this->lang->line('xin_save');?></button>							
									</div>
									
									
              </div>
     
         
        </form>
      </div>
    </div>
  </div>
</div>

<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong><?php echo $this->lang->line('xin_list_all');?></strong> <?php echo $this->lang->line('xin_expenses');?></strong>
							
							</h5>
							<div class="heading-elements">
							    <div class="add-record-btn">
      <button class="btn btn-sm bg-teal-400 add-new-form"><?php echo $this->lang->line('xin_add_new');?></button>
    </div>
		                	</div>
						</div>
		<table class="table" id="xin_table">
						
							<thead>
								<tr>
				 <th><?php echo $this->lang->line('dashboard_single_employee');?></th>
          <th><?php echo $this->lang->line('xin_expense');?></th>
          <th><?php echo $this->lang->line('xin_amount');?></th>
          <th><?php echo $this->lang->line('xin_purchase_date');?></th>
          <th><?php echo $this->lang->line('dashboard_xin_status');?></th>
		  <th><?php echo $this->lang->line('xin_action');?></th>
								</tr>
							</thead>
			
							<tbody>
													
							</tbody>
						</table>
					</div>
					
					
