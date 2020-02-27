<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['employee_id']) && $_GET['data']=='add_attendance'){
		$user = $this->Xin_model->read_user_info($_GET['employee_id']);
		$ful_name = $user[0]->first_name. ' '.$user[0]->middle_name. ' '.$user[0]->last_name;
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Add Attendance for <?php echo $ful_name; ?></h4>
</div>

<form class="m-b-1" action="<?php echo site_url("timesheet/add_attendance") ?>" method="post" name="add_attendance" id="add_attendance">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="employee_id_m" id="employee_id_m" value="<?php echo $_GET['employee_id'];?>" />
  <div class="modal-body">
    <div class="row">
      <div class="col-md-12">
        <div class="form-group"> </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="date">Attendance Date</label>
              <input class="form-control attendance_date_m" placeholder="Attendance Date" readonly="true" id="attendance_date_m" name="attendance_date_m" type="text">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="clock_in">Office In Time</label>
              <input class="form-control timepicker_m" placeholder="Office In Time" readonly="true" id="clock_in_m" name="clock_in_m" type="text">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="clock_out">Office Out Time</label>
              <input class="form-control timepicker_m" placeholder="Office Out Time" readonly="true" id="clock_out_m" name="clock_out_m" type="text">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn bg-teal-400">Save</button>
  </div>
</form>
<script type="text/javascript">
 $(document).ready(function(){
		// Clock
		var input = $('.timepicker_m').clockpicker({
			placement: 'bottom',
			align: 'left',
			autoclose: true,
			'default': 'now'
		});		
		// attendance date
		$('.attendance_date_m').datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat:'yy-mm-dd',
			altField: "#date_format",
			altFormat: "d M, yy",
			yearRange: '1970:' + new Date().getFullYear(),
			beforeShow: function(input) {
				$(input).datepicker("widget").show();
			}
		});	 
				  
		/* Add Attendance*/
		$("#add_attendance").submit(function(e){
			var attendance_date_m = $("#attendance_date_m").val();
			var emp_id = $("#employee_id_m").val();
			var clock_in_m = $("#clock_in_m").val();
			var clock_out_m = $("#clock_out_m").val();
			if(attendance_date_m!='' && emp_id!='' && clock_in_m!='' && clock_out_m!='') {
				var xin_table = $('#xin_table').dataTable({
				"bDestroy": true,
				"ajax": {
					url : "<?php echo site_url("timesheet/update_attendance_list") ?>?employee_id="+emp_id+"&attendance_date="+attendance_date_m,
					type : 'GET'
				},
				"fnDrawCallback": function(settings){
				$('[data-toggle="tooltip"]').tooltip();          
				}
			});
			}
		/*Form Submit*/
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=4&add_type=attendance&form="+action,
				cache: false,
				success: function (JSON) {
					if (JSON.error != '') {
						toastr.error(JSON.error);
						$('.save').prop('disabled', false);
					} else {
						$('.add-modal-data').modal('toggle');
							xin_table.api().ajax.reload(function(){ 
								toastr.success(JSON.result);
							}, true);
						$('.save').prop('disabled', false);
					}
				}
			});
		});
	});	
  </script>
<?php } else if(isset($_GET['jd']) && isset($_GET['attendance_id']) && $_GET['type']=='attendance' && $_GET['data']=='attendance'){?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Attendance for <?php echo $full_name;?></h4>
</div>
<form class="m-b-1" action="<?php echo site_url("timesheet/edit_attendance").'/'.$time_attendance_id;?>/" method="post" name="edit_attendance" id="edit_attendance">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $_GET['attendance_id'];?>">
  <input type="hidden" name="emp_att" id="emp_att" value="<?php echo $employee_id;?>" />
  <div class="modal-body">
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label for="date">Attendance Date</label>
          <input class="form-control attendance_date_e" placeholder="Attendance Date" readonly="true" id="attendance_date_e" name="attendance_date_e" type="text" value="<?php echo $attendance_date;?>">
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="clock_in">Office In Time</label>
              <input class="form-control timepicker" placeholder="Office In Time" readonly="true" name="clock_in" type="text" value="<?php echo $clock_in;?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="clock_out">Office Out Time</label>
              <input class="form-control timepicker" placeholder="Office Out Time" readonly="true" name="clock_out" type="text" value="<?php echo $clock_out;?>">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn bg-teal-400 save">Update</button>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	
	// Clock
	var input = $('.timepicker').clockpicker({
		placement: 'bottom',
		align: 'left',
		autoclose: true,
		'default': 'now'
	});
	
	// attendance date
	$('.attendance_date_e').datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat:'yy-mm-dd',
		altField: "#date_format",
		altFormat: "d M, yy",
		yearRange: '1970:' + new Date().getFullYear(),
		beforeShow: function(input) {
			$(input).datepicker("widget").show();
		}
	});	 
  
	/* Edit Attendance*/
		$("#edit_attendance").submit(function(e){
		var attendance_date_e = $("#attendance_date_e").val();
		var emp_att = $("#emp_att").val();
		var xin_table2 = $('#xin_table').dataTable({
			"bDestroy": true,
			"ajax": {
				url : "<?php echo site_url("timesheet/update_attendance_list") ?>?employee_id="+emp_att+"&attendance_date="+attendance_date_e,
				type : 'GET'
			},
			"fnDrawCallback": function(settings){
			$('[data-toggle="tooltip"]').tooltip();          
			}
		});
		/*Form Submit*/
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=3&edit_type=attendance&form="+action,
				cache: false,
				success: function (JSON) {
					if (JSON.error != '') {
						toastr.error(JSON.error);
						$('.save').prop('disabled', false);
					} else {
						$('.edit-modal-data').modal('toggle');
						xin_table2.api().ajax.reload(function(){ 
							toastr.success(JSON.result);
						}, true);
						$('.save').prop('disabled', false);
					}
				}
			});
		});
});	
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['bus_late_id']) && $_GET['data']=='bus_lateness'){ ?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Bus Lateness</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("timesheet/update_bus_lateness").'/'.$time_attendance_id;?>/" method="post" name="update_bus_lateness" id="update_bus_lateness">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $_GET['bus_late_id'];?>">
  <div class="modal-body">
    <div class="row">
                 
      <div class="col-md-6">
	  
	  <div class="form-group">
 <label for="name">Choose the Location</label>
                    <select class="form-control"  name="location_id" data-plugin="select_hrm" data-placeholder="Choose the Location...">
                      <option value="">&nbsp;</option>
                      <?php foreach($this->Xin_model->all_locations() as $locs) {?>
                      <option <?php if($locs->location_id==$location_id){echo 'selected';}?> value="<?php echo $locs->location_id;?>"> <?php echo $locs->location_name;?></option>
                      <?php } ?>
                    </select>
                  </div>
				  
                         <div class="form-group">
<label for="name">Select Date</label>
                  
				   <div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                    <input class="form-control" placeholder="Select Date" name="select_date" id="select_date" size="16" type="text"  value="<?php echo format_date('d F Y',$bus_late_date);?>" readonly>
                    <span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>					
                </div>

</div>
</div>
<div class="col-lg-6">
		    <div class="form-group">
                    <label for="name">Bus Scheduled Time</label>
                    <input class="form-control timepicker clear-1" placeholder="Bus Scheduled Time" readonly name="bus_scheduled_time" type="text" value="<?php echo $bus_scheduled_time;?>">
                  </div>
				   <div class="form-group">
                    <label for="name">Bus Actual Arrived Time</label>
                    <input class="form-control timepicker clear-1" placeholder="Bus Arrived Time" readonly name="bus_arrived_time" type="text" value="<?php echo $bus_late_time;?>">
					
                  </div>
		    </div>
	
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn bg-teal-400 save">Update</button>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	// Clock
	var input = $('.timepicker').clockpicker({
		placement: 'bottom',
		align: 'left',
		autoclose: true,
		'default': 'now'
	});
	
	// attendance date
	$('.form_date').datetimepicker({
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0,
		pickerPosition: "bottom-left"
    });
  
	/* Edit Attendance*/
		$("#update_bus_lateness").submit(function(e){	
		/*Form Submit*/
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=3&edit_type=update_bus_lateness&form="+action,
				cache: false,
				success: function (JSON) {
					if (JSON.error != '') {
						toastr.error(JSON.error);
						$('.save').prop('disabled', false);
					} else {
						$('.edit-modal-data').modal('toggle');
						bus_lateness_list();
						toastr.success(JSON.result);					
						$('.save').prop('disabled', false);
					}
				}
			});
		});
});	
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['biometric_user_id']) && $_GET['data']=='biometric_user'){ 
	?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit "<?=$ful_name;?>" Biometric</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("timesheet/update_biometric_user_list").'/'.$_GET['biometric_user_id'];?>/" method="post" name="update_biometric_user_list" id="update_biometric_user_list">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="biometric_inc_id" value="<?=$_GET['biometric_user_id'];?>">
  <div class="modal-body">
    <div class="row">
		<div class="col-lg-12">     
            <div class="col-md-4">  
              <div class="form-group">
                <label for="name">Choose the Location</label>
                <select class="form-control employee_shows"  name="location_id" id="location_id_val" data-plugin="select_hrm" data-placeholder="Choose the Location...">
                  <option value="">&nbsp;</option>
                    <?php foreach($this->Xin_model->all_locations() as $locs) {?>
                      <option value="<?=$locs->location_id;?>" <?php if($locs->location_id==$office_location_id){ echo 'selected';} ?> > <?=$locs->location_name;?></option>
                    <?php } ?>
                </select>
              </div>       
            </div>  
            <div class="col-md-4">  
              <div class="form-group">
                  <label for="name">Choose the Employee</label>
                  <select name="employee_id" id="employee_id" class="form-control" data-plugin="select_hrm" data-placeholder="Choose the Employee...">
                      <?php foreach($this->Xin_model->read_user_info_manual_attendance_shift('','') as $employee) {
												if($employee->user_id==$employee_id){
												?>
                        <option value="<?=$employee->user_id;?>"> <?=change_fletter_caps($employee->first_name.' '.$employee->middle_name.' '.$employee->last_name);?></option>
											<?php }
										} ?>
                  </select>
              </div>
            </div>  
            <div class="col-lg-4">
              <div class="form-group">
                <label for="name">Biometric ID</label>
                <input class="form-control" placeholder="Biometric ID" name="biometric_id" type="text" value="<?=$biometric_id;?>">
              </div>
            </div>            
          </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn bg-teal-400 save">Update</button>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
  
	/* Edit Attendance*/
	$("#update_biometric_user_list").submit(function(e){	
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=3&edit_type=update_biometric_user_list&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit-modal-data').modal('toggle');
					biometric_users_list();
					toastr.success(JSON.result);					
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});	
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['adjust_id']) && $_GET['data']=='al_expiry_data'){ 
	?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit "<?=$full_name;?>" Data</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("timesheet/update_al_expiry_list").'/'.$_GET['adjust_id'];?>/" method="post" name="update_al_expiry_list" id="update_al_expiry_list">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="adjust_id" value="<?=$adjust_id;?>">
  <input type="hidden" name="adjust_employee_id" value="<?=$adjust_employee_id;?>">
  <div class="modal-body">
    <div class="row">
	<div class="col-lg-12">					
						
						<div class="col-md-6">												

							<div class="form-group">
								<label for="name">Expired Days</label>
								<select name="adjust_days" class="form-control" data-plugin="select_hrm" data-placeholder="Expired Days...">				
									<?php 
									for($aj=0;$aj <= 100;$aj++) {?>
									<option value="<?=$aj;?>" <?php if($adjust_days==$aj){echo 'selected';}?>> <?=$aj;?> <?php if($aj > 1) {echo 'Days';}else{echo 'Day';}?></option>
									<?php } ?>
								</select>
							</div>

							<div class="form-group">
								<label for="name_of_week">Description</label>
								<textarea name="adjust_description" class="form-control"><?=$adjust_description;?></textarea>
							</div>
						</div>

						<div class="col-md-6">

						<div class="form-group">
								<label for="name_of_week">Adjust Type</label>
								<select class="form-control" name="adjust_type_id" data-plugin="select_hrm" data-placeholder="Choose the Adjust Type...">
									<option value="">&nbsp;</option>
									<?php 
										foreach($expireList as $expiry){?>
										<option value="<?=$expiry['type_id']?>" <?php if($adjust_type_id==$expiry['type_id']){echo 'selected';}?>><?=$expiry['type_name']?></option>
									<?php }?>
								</select>
							</div>						
						</div>					
					</div>
	</div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn bg-teal-400 save">Update</button>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
  
	/* Edit Attendance*/
	$("#update_al_expiry_list").submit(function(e){	
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=3&edit_type=update_al_expiry_list&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit-modal-data').modal('toggle');
					xin_expiry_list();
					toastr.success(JSON.result);					
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});	
</script>
<?php }
?>