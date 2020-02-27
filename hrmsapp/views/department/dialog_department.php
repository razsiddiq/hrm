<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['department_id']) && $_GET['data']=='department'){
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_department_edit');?></h4>
</div>
<?php if(in_array('5e',role_resource_ids())) {?>
<form class="m-b-1" action="<?php echo site_url("department/update").'/'.$department_id; ?>" method="post" name="edit_department" id="edit_department">
<?php } ?>
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $department_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $department_name;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="department-name" class="form-control-label"><?php echo $this->lang->line('xin_code');?><?php echo REQUIRED_FIELD;?></label>
        <input type="text" class="form-control" name="department_name" value="<?php echo $department_name?>">
      </div>
	  <div class="form-group">
        <label for="department-detail" class="form-control-label"><?php echo $this->lang->line('xin_name');?><?php echo REQUIRED_FIELD;?></label>
        <input type="text" class="form-control" name="department_detail" value="<?php echo $department_detail?>">
      </div>
      <div class="form-group">
          <label for="name"><?php echo $this->lang->line('xin_location');?><?php echo REQUIRED_FIELD;?></label>
          <select name="location_id[]" multiple="multiple" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_location');?>">
        <option value=""></option>
  
		<?php foreach($all_locations as $location) {	
		
					$locations=explode(',',$location_id);	
					
					if(in_array($location->location_id,$locations)){
						$selc='selected';
					}else{
						$selc='';
					}
					?>
		<option <?php echo $selc;?> value="<?php echo $location->location_id;?>"> <?php echo $location->location_name;?></option>
        <?php } ?>
        </select>
        </div>
        <div class="form-group">
          <label for="name"><?php echo $this->lang->line('xin_department_head');?><?php echo REQUIRED_FIELD;?></label>
          <select name="employee_id" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_department_head');?>">
        <option value=""></option>
        <?php foreach($all_employees as $employee) {?>
        <?php
        /* get user_role */
        $user_role = $this->Xin_model->read_user_role_info($employee->user_role_id);
        ?>
        <option value="<?php echo $employee->user_id;?>" <?php if($employee_id==$employee->user_id):?> selected="selected"<?php endif;?>> <?php echo $employee->first_name.' '.$employee->middle_name.' '.$employee->last_name;?> (<?php echo $user_role[0]->role_name;?>)</option>
        <?php } ?>
        </select>
        </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
	<?php if(in_array('5e',role_resource_ids())) {?>
    <button type="submit" class="btn bg-teal-600"><?php echo $this->lang->line('xin_update');?></button>
	<?php } ?>
  </div>
</form>
<script type="text/javascript">
 $(document).ready(function(){
	
	
	$('[data-plugin="xin_select"]').select2($(this).attr('data-options'));	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));

	
	

		/* Edit data */
		$("#edit_department").submit(function(e){
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
			
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=1&edit_type=department&form="+action,
				cache: false,
				success: function (JSON) {
					if (JSON.error != '') {
						toastr.error(JSON.error);
						$('.save').prop('disabled', false);
					} else {
						$('#xin_table').dataTable().api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
						$('.edit-modal-data-md').modal('toggle');
						$('.save').prop('disabled', false);
					}
				}
			});
		});
	});	
  </script>
<?php } ?>