<?php
/*
* Designation View
*/
$session = $this->session->userdata('username');
?>
<?php if(in_array('6v',role_resource_ids())) {?>
<div class="row m-b-1 animated fadeInRight">

<?php if(in_array('6a',role_resource_ids())) {?>
  <div class="col-md-4">
    <div class="panel panel-flat">
	<div class="panel-heading">
	<h5 class="panel-title">
<strong><?php echo $this->lang->line('xin_add_new');?></strong> <?php echo $this->lang->line('xin_designation');?></strong>
	</div>
<div class="panel-body">
      <form class="m-b-1" action="<?php echo site_url("designation/add_designation") ?>" method="post" name="add_designation" id="xin-form">
        <div class="form-group" >
          <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
          <label for="name" style="display:none;"><?php echo $this->lang->line('xin_department');?></label><!--class="select2" data-plugin="select_hrm"-->
          <select style="display:none;"  data-placeholder="<?php echo $this->lang->line('xin_select_department');?>..." name="department_id">
            <option value=""></option>
            <?php foreach($all_departments as $deparment) {?>
            <option <?php if($deparment->department_id==1){echo 'selected';} ?> value="<?php echo $deparment->department_id?>"><?php echo $deparment->department_name?></option>
            <?php } ?>
          </select>
        </div>
        <div class="form-group">
          <label for="name"><?php echo $this->lang->line('xin_designation_name');?><?php echo REQUIRED_FIELD;?></label>
          <input type="text" class="form-control" name="designation_name" placeholder="<?php echo $this->lang->line('xin_designation_name');?>">
        </div>
        <button type="submit" class="btn bg-teal-400 pull-right save"><?php echo $this->lang->line('xin_save');?></button>
      </form>
    
	</div>
	</div>
  </div>
  <div class="col-md-8">
<?php } else { ?> 
  <div class="col-md-12">
  <?php } ?>
   
  <div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong><?php echo $this->lang->line('xin_list_all');?></strong> <?php echo $this->lang->line('xin_designations');?></strong>
							
							</h5>
						
						</div>
		<table class="table" id="xin_table">
						
							<thead>
								<tr>			  
              <th><?php echo $this->lang->line('xin_designation');?></th>
			  <th><?php echo $this->lang->line('xin_action');?></th>
								</tr>
							</thead>
			
							<tbody>
													
							</tbody>
						</table>
					</div>
					
					
</div>
</div>
<?php } else { ?>
		<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title text-danger">
							 
							 <?php echo $this->lang->line('xin_permission');?>
							
							</h5>
				
						</div>
						
		
					

				</div>

<?php } ?>