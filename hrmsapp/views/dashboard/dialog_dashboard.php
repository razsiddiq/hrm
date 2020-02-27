<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['event_id']) && $_GET['data']=='schedule'){

$fi_st=rand();
$se_st =$fi_st+1;
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Schedule</h4>
</div>
<div class="modal-body">
<form method="post" name="add_shedule" id="edit-schedule-form" action="<?php echo site_url("dashboard/update_schedule") ?>">
<input type="hidden" name="_method" value="EDIT">
<input type="hidden" name="e_schedule_id" value="<?php echo $schedule_id;?>">
<input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>" id="user_id">
<div class="row">    <div class="col-lg-12">           
									
											<div class="col-lg-2 no-padding-left">
												<input class="form-control" placeholder="Schedule Title" name="e_schedule_title" type="text" value="<?php echo $schedule_title;?>">
											</div>
<div class="col-lg-3">
												<input class="form-control" placeholder="Schedule Start Date & Time" name="e_schedule_start" type="text" id="<?php echo $fi_st;?>"  value="<?php echo date('F dS Y H:i',strtotime($schedule_start));?>">
											</div>
<div class="col-lg-3">
												<input class="form-control" placeholder="Schedule End Date & Time" name="e_schedule_end" type="text" id="<?php echo $se_st;?>" value="<?php if($schedule_end!=''){ echo date('F dS Y H:i',strtotime($schedule_end));}?>">
											</div>

<div class="col-lg-4"> <input type="text" class="form-control colorpicker-basic1" value="<?php echo $schedule_color;?>" name="e_schedule_color" id="schedule_color1" >

 <button type="button" class="btn btn-default ml-15" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
												<button type="submit" class="btn ml-15 bg-teal-400 save">Update</button>
											</div>


										</div></div>
</form>

</div>
<style>
.sp-container.sp-light.sp-input-disabled.sp-palette-buttons-disabled.sp-palette-disabled.sp-initial-disabled {
    z-index: 9999;
}#AnyTime--<?php echo $fi_st;?>,#AnyTime--<?php echo $se_st;?> {
    z-index: 9999;
    top:46% !important;
}
</style>


<script type="text/javascript">
 $(document).ready(function(){
		
$(".colorpicker-basic1").spectrum({
    	//showInput: true
    });

// Date and time
    $("#<?php echo $fi_st;?>").AnyTime_picker({
       format: "%M %D %Y %H:%i",
    });
// Date and time
    $("#<?php echo $se_st;?>").AnyTime_picker({
       format: "%M %D %Y %H:%i",
    });	 
$("#edit-schedule-form").submit(function(e){
e.preventDefault();
	var obj = $(this), action = obj.attr('name');
	$('.save').prop('disabled', true);

    var st=$('#edit-schedule-form').find('.sp-preview-inner').attr('style');
    st=st.replace('background-color:',''); 
    st=st.replace(';','');
    $('#schedule_color1').val(st);

	$.ajax({
		type: "POST",
		url: e.target.action,
		data: obj.serialize()+"&is_ajax=1&add_type=update_schedule&form="+action,
		cache: false,
		success: function (JSON) {
			if (JSON.error != '') {
				toastr.error(JSON.error);
				$('.save').prop('disabled', false);
			} else {
				$('.edit-modal-data').modal('toggle');
				toastr.success(JSON.result);
				my_schedule_trigger();
				$('.save').prop('disabled', false);
			}
		}
	});
});
		
		
	});	
  </script>
<?php }
?>


