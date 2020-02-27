<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['office_shift_id']) && $_GET['data']=='shift'){
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Office Shift</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("timesheet/edit_office_shift").'/'.$office_shift_id; ?>/" method="post" name="edit_office_shift" id="edit_office_shift">
  <input type="hidden" name="_method" value="EDIT">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-12">       
          <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
      <div class="col-lg-12">
          <div class="col-lg-4">
		    <div class="form-group">
                    <label for="name">Shift Name</label>
                    <input class="form-control" placeholder="Shift Name" name="shift_name" type="text" value="<?php echo $shift_name;?>" id="name">
                  </div>
		    </div>
			
			<div class="col-lg-4">
		    <div class="form-group">
            <label for="name">Choose the Location</label>
                    <select class="form-control"  name="location_id" data-plugin="select_hrm" data-placeholder="Choose the Location...">
                      <option value="">&nbsp;</option>
                      <?php foreach($this->Xin_model->all_locations() as $locs) {?>
                      <option <?php if($location_id==$locs->location_id){echo 'selected';}?> value="<?php echo $locs->location_id;?>"> <?php echo $locs->location_name;?></option>
                      <?php } ?>
                    </select>
                  </div>
		    </div>
			
			
			
			<div class="col-lg-4">
		   <div class="form-group">
<label for="name">Choose the Department</label>					
					  <select class="form-control" name="department_id" data-plugin="select_hrm" data-placeholder="Choose the Department...">
                      <option value="">&nbsp;</option>
                      <?php foreach($this->Xin_model->all_departments_chart() as $dept) {?>
                      <option <?php if($department_id==$dept->department_id){echo 'selected';}?> value="<?php echo $dept->department_id;?>"><?php echo $dept->department_name;?></option>
                      <?php } ?>
                    </select>
					
                  </div>
		    </div>
                
				<div class="col-lg-4">
		    <div class="form-group">
                    <label for="name">Shift In Time</label>
                    <input class="form-control timepicker clear-1" placeholder="In Time" readonly name="shift_in_time" type="text" value="<?php echo $shift_in_time;?>">
                  </div>
		    </div>
			
			<div class="col-lg-4">
		    <div class="form-group">
                    <label for="name">Shift Out Time</label>
                    <input class="form-control timepicker clear-1" placeholder="Out Time" readonly name="shift_out_time" type="text" value="<?php echo $shift_out_time;?>">
					
                  </div>
		    </div>
			

			<div class="col-lg-4">
			
			
		
		
		    <div class="form-group"> 
                    <label for="name">Week Off Day</label>
                    <select class="form-control" name="week_off[]" data-plugin="select_hrm" data-placeholder="Choose the Week Off Day..." multiple>
                      <option value="">&nbsp;</option>
                    <?php foreach(get_weekoff_days() as $week_offs) {	
		
					$week_off1=explode(',',$week_off);	
					
					if(in_array($week_offs,$week_off1)){
						$selc='selected';
					}else{
						$selc='';
					}
					?>
					
					
        <option <?php echo $selc;?> value="<?php echo $week_offs;?>"> <?php echo $week_offs;?></option>
        <?php } ?>
		
		
                    </select>
					
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
	 
	 $.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        }
    });
	 var xin_table = $('#xin_table').DataTable({
		"bDestroy": true,
		"ajax": {
			url : "<?php echo site_url("timesheet/office_shift_list") ?>",
			type : 'GET'
		},"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		},	
            buttons: [
                {
                    extend: 'copyHtml5',
                    className: 'btn btn-default',
					 exportOptions: {
                        columns: [ 0, ':visible' ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    className: 'btn btn-default',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    className: 'btn btn-default',
                    exportOptions: {
                        columns: ':visible'// [ 1, 2, 3, 4, 5, 6, 7]
                    }
                },
				{
                extend: 'print',
                text: '<i class="icon-printer position-left"></i> Print table',
                className: 'btn btn-default',
                exportOptions: {
                    columns: ':visible'
                }
                },
                {
                    extend: 'colvis',
                    text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
                    className: 'btn bg-teal-400 btn-icon'
                }
            ]
        
    });
	
	
	// Add placeholder to the datatable filter option
    $('.dataTables_filter input[type=search]').attr('placeholder','Type to filter...');


    // Enable Select2 select for the length option
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });

	
	// Clock
	$('.clockpicker').clockpicker();
	var input = $('.timepicker').clockpicker({
		placement: 'bottom',
		align: 'left',
		autoclose: true,
		'default': 'now'
	});
	
	/* Edit data */
	$("#edit_office_shift").submit(function(e){
		/*Form Submit*/
		e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=3&edit_type=shift&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit-modal-data').modal('toggle');
						xin_table.ajax.reload(function(){ 
							toastr.success(JSON.result);
						}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
	$(".clear-time").click(function(){
		var clear_id  = $(this).data('clear-id');
		$(".clear-"+clear_id).val('');
	});
});	
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['change_schedule_id']) && $_GET['data']=='schedule'){
	$session = $this->session->userdata('username');
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Change Shift To</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("timesheet/edit_change_schedule");?>/" method="post" name="edit_change_schedule" id="edit_change_schedule">
   <input type="hidden" name="_method" value="EDIT">
   <div class="modal-body">
     <div class="row">
      <div class="col-md-12">
	  <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
      <div class="col-md-4 no-padding-left">
                         <div class="form-group">
<label for="name">Shift In Time</label>
                    <input class="form-control timepicker" placeholder="In Time" readonly name="shift_in_time" type="text" value="<?php echo @$shift_in_time;?>">

</div>
</div>  

<div class="col-md-4">
                   <div class="form-group">
<label for="name">Shift Out Time</label>
                    <input class="form-control timepicker" placeholder="Out Time" readonly name="shift_out_time" type="text" value="<?php echo @$shift_out_time;?>">
</div>


</div>

       
	   <div class="col-md-4">
	   
	  <div class="form-group"> 
                    <label for="name">Week Off Day</label>
                    <select class="form-control" name="week_off[]" data-plugin="select_hrm" data-placeholder="Choose the Week Off Day..." multiple>
                      <option value="">&nbsp;</option>
                    <?php foreach(get_weekoff_days() as $week_offs) {	
		
					$week_off1=explode(',',$week_off_date);	
					
					if(in_array($week_offs,$week_off1)){
						$selc='selected';
					}else{
						$selc='';
					}
					?>
					
					
        <option <?php echo $selc;?> value="<?php echo $week_offs;?>"> <?php echo $week_offs;?></option>
        <?php } ?>
		
		
                    </select>
					
                  </div>
				  
 


</div>


		
      </div>
    </div>
    <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn bg-teal-400">Update</button>
  </div>
  </div>

</form>
<script type="text/javascript">

$(document).ready(function(){
$('.clockpicker').clockpicker();

var input = $('.timepicker').clockpicker({
	placement: 'bottom',
	align: 'left',
	autoclose: true,
	'default': 'now'
});


    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });

$("#edit_change_schedule").submit(function(e){
e.preventDefault();
	var obj = $(this), action = obj.attr('name');
    var arr = [];
		 $('input.bs_styled:checkbox:checked').each(function () {
			arr.push($(this).val());
		 });
		 	
	$.ajax({
		type: "POST",
		url: e.target.action,
		data: obj.serialize()+"&is_ajax=1&edit_type=edit_change_schedule&form="+action+"&employee_id="+arr,
		cache: false,
		success: function (JSON) {
			if (JSON.error != '') {
				toastr.error(JSON.error);
				$('.save').prop('disabled', false);
			} else {
				
				$('.edit-modal-data').modal('toggle');
				var xin_shift_table = $('#xin_shift_table').DataTable();
				xin_shift_table.ajax.reload(function(){ 
					toastr.success(JSON.result);
				}, true);
				$('.save').prop('disabled', false);
				$('input[type=checkbox]').prop('checked',false);
			}
		}
	});
});
});	


</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['manual_attendance_id']) && $_GET['data']=='manual_attendance'){
	$session = $this->session->userdata('username');
	
	$from_date_limit = date('Y-m-d',(strtotime ( '-7 day' , strtotime ( $start_date) ) ));
	//$_GET['manual_attendance_id']
	
	
	
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Change Manual Attendance To</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("timesheet/edit_manual_attendance");?>/" method="post" name="edit_manual_attendance" id="edit_manual_attendance">
   <input type="hidden" name="_method" value="EDIT">
   <div class="modal-body">
     <div class="row">
      <div class="col-md-12">
	  <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
      <div class="col-md-3 no-padding-left">
                         <div class="form-group">
<label for="name">Start Date</label>
               
				   <div class="input-group date form_date_five_day_edit" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                    <input class="form-control" placeholder="Select Date" name="start_date" id="start_date" size="16" type="text"  value="<?php echo format_date('d F Y',$start_date);?>" readonly>
                    <span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>					
                </div>
				

</div>
</div>  

<div class="col-md-3">
                   <div class="form-group">
<label for="name">End Date</label>
                 
					
					<div class="input-group date form_date_five_day_edit" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                    <input class="form-control" placeholder="Select Date" name="end_date" id="end_date" size="16" type="text"  value="<?php echo format_date('d F Y',$end_date);?>" readonly>
                    <span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>					
                </div>
</div>


</div>

       
<div class="col-md-2">
	<div class="form-group">
		<label for="name_of_week">Attendance Status</label>
     	<select class="form-control" name="attendance_status" data-plugin="select_hrm" data-placeholder="Choose the attendance status...">
      		<option value="">&nbsp;</option>
         	<option <?php if(@$attendance_status=='Present'){?> selected <?php } ?> value="Present">Present</option>
		 	<option <?php if(@$attendance_status=='Absent'){?> selected <?php } ?> value="Absent">Absent</option>
    	</select>
	</div>
</div>

<div class="col-md-2">
	<div class="form-group">
		<label for="name_of_week">OB Type</label>
     	<select class="form-control" name="ob_type" data-plugin="select_hrm" data-placeholder="Choose the OB Type...">
      		<option value="">&nbsp;</option>

      		<?php 
			for ($o=0; $o < count($obList); $o++) { 
			?>
				<option value="<?=$obList[$o]['type_id']?>" <?php if(@$ob_type_id==$obList[$o]['type_id']){?> selected <?php } ?>  ><?=$obList[$o]['type_name']?></option>
			<?php }?>
    	</select>
	</div>
</div>

<div class="col-lg-2">
		    <div class="form-group">
                    <label for="name">Start Time</label>
                    <input class="form-control timepicker1 clear-1" placeholder="Start Time" readonly name="start_time" type="text" value="<?php echo $start_time;?>">
                  </div>
		    </div>
			
			<div class="col-lg-2">
		    <div class="form-group">
                    <label for="name">End Time</label>
                    <input class="form-control timepicker1 clear-1" placeholder="End Time" readonly name="end_time" type="text" value="<?php echo $end_time;?>">
					
                  </div>
		    </div>
			


		
      </div>
    </div>
    <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn bg-teal-400">Update</button>
  </div>
  </div>

</form>
<script type="text/javascript">

$(document).ready(function(){
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


    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });

	$('.clockpicker1').clockpicker();

var input = $('.timepicker1').clockpicker({
	placement: 'bottom',
	align: 'left',
	autoclose: true,
	'default': 'now'
});

$("#edit_manual_attendance").submit(function(e){
e.preventDefault();
var xin_shift_table = $('#xin_shift_table').DataTable();
	var obj = $(this), action = obj.attr('name');
    var arr = [];
		 xin_shift_table.$('input.bs_styled:checkbox:checked').each(function () {
			arr.push($(this).val());
		 });
		 	
	$.ajax({
		type: "POST",
		url: e.target.action,
		data: obj.serialize()+"&is_ajax=1&edit_type=edit_manual_attendance&form="+action+"&employee_id="+arr, 
		cache: false,
		success: function (JSON) {
			if (JSON.error != '') {
				toastr.error(JSON.error);
				$('.save').prop('disabled', false);
			} else {
				
				$('.edit-modal-data').modal('toggle');
				var xin_shift_table = $('#xin_shift_table').DataTable();
				xin_shift_table.ajax.reload(function(){ 
					toastr.success(JSON.result);
				}, true);
				$('.save').prop('disabled', false);
				$('input[type=checkbox]').prop('checked',false);
			}
		}
	});
});
});	


</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['schedule_user_id']) && $_GET['data']=='scheduleview'){
	
	$user = $this->Xin_model->read_user_info($_GET['schedule_user_id']);	
	
	?>


<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Schedule Chart For <span class="text-teal-400"><?php echo change_fletter_caps($user[0]->first_name.' '.$user[0]->middle_name.' '.$user[0]->last_name);?></span></h4>
</div>

<div class="modal-body">

 <div class="panel-body">  
			<form action="<?php echo site_url("timesheet/read_change_schedule_view") ?>" method="post" name="dt_search" id="dt_search">
			
			<input type="hidden" value="<?php echo $_GET['schedule_user_id'];?>" name="schedule_user_id"/>
              <div class="row">        
				<div class="col-md-5">
                  <div class="form-group">
                    <input class="form-control attendance_date_t" placeholder="Select Date" readonly id="start_date_t" name="start_date_t" type="text" value="<?php echo date('d F Y',strtotime($start_dt));?>">
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <input class="form-control attendance_date_t" placeholder="Select Date" readonly id="end_date_t" name="end_date_t" type="text" value="<?php echo date('d F Y',strtotime($end_dt));?>">
                  </div>
                </div>
			
			 <div class="col-md-2">
                  <div class="form-group">   <button type="submit"  class="btn bg-teal-400 save">Filter</button>
					
                  </div>
                </div>
              </div>
              
			  </form>
			  
			  </div>
          
          
		  
		  
<div class="table-responsive" id="table_change">
							<table class="table">
								<thead>
									<tr>
										<th>Date</th>
										<th>Shift Start Time</th>
										<th>Shift End Time</th>
									</tr>
								</thead>
								<tbody>
								

             						<?php 								
									
									    foreach($result as $res){
										$tdate = $this->Xin_model->set_date_format($res->attendance_date);

										if($res->shift_start_time!=''){
											$shift_start_time_a = new DateTime($res->shift_start_time);
											$shift_start_time_a=$shift_start_time_a->format('h:i a');
										}else{
										$shift_start_time_a='N/A';
										}					
										if($res->shift_end_time!=''){		
											$shift_end_time_a = new DateTime($res->shift_end_time);
											$shift_end_time_a=$shift_end_time_a->format('h:i a');
										}else{
										$shift_end_time_a='N/A';
										}
										
										?>
									<tr>
										<td><?php echo $tdate;?></td>
										<td><span class="label bg-teal-400"><?php echo $shift_start_time_a;?></span></td>
										<td><span class="label bg-teal-400"><?php echo $shift_end_time_a;?></span></td>						
									</tr>
									<?php } ?>
									
						
								</tbody>
							</table>
						</div>

</div>
<script type="text/javascript">
 $(document).ready(function(){
$('.attendance_date_t').pickadate({format: "dd mmmm yyyy"}); 
$("#dt_search").submit(function(e){
e.preventDefault();
 
   var start_date=$('#start_date_t').val();
   var end_date=$('#end_date_t').val();
   

var start_date = (Date.parse(start_date))/1000;
var end_date = (Date.parse(end_date))/1000;

if(start_date > end_date){
	toastr.error('Start date shoule be less than to End Date');
	return false;
}


	var obj = $(this), action = obj.attr('name');
	$.ajax({
		type: "POST",
		url: e.target.action,
		data: obj.serialize()+"&is_ajax=1&add_type=change_schedule&form="+action,
		cache: false,
		success: function (html) {
		 $('#table_change').html(html);		
		}
	});
});
});	
</script>
<?php } ?>

<?php if(in_array('31e',role_resource_ids())) {?>
	<script> var minDate = ''; </script>
<?php }else{?>
	<script> var minDate = '<?php echo $from_date_limit?>';</script>
<?php }?>
<script>

$(document).ready(function(){
	$('.form_date_five_day_edit').datetimepicker({
		weekStart: 1,
		todayBtn:  1,
		autoclose: 1,
		startDate: minDate,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0,
		pickerPosition: "bottom-left"
	});
});

</script>