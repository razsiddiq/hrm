<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$session = $this->session->userdata('username');
if(isset($_GET['jd']) && isset($_GET['leave_id']) && $_GET['data']=='leave'){

$minDateLeave = date('Y-m-d',(strtotime ( '-7 day' , strtotime ( $from_date) ) ));
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Leave</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("timesheet/edit_leave").'/'.$leave_id; ?>/" method="post" name="edit_leave" id="edit_leave"  enctype="multipart/form-data">
  <input type="hidden" name="_method" value="EDIT">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="leave_type" class="control-label">Leave Type</label>
          <select class="form-control" name="leave_type" id="leave_type_dialog" onchange="leave_availability_dialog();" data-plugin="select_hrm" data-placeholder="Leave Type">
            <option value=""></option>
            <?php foreach($all_leave_types as $type) {?>
			<?php if($type->type_id==$leave_type_id):?>
            <option value="<?php echo $type->type_id?>"  selected > <?php echo $type->type_name;?></option>
			<?php endif;?>
            <?php } ?>
          </select>
        </div>

            <div class="form-group">
              <label for="employees" class="control-label">Leave for Employee</label>
              <select class="form-control" name="employee_id" onchange="leave_availability_dialog();" id="employee_id_dialog" data-plugin="select_hrm" data-placeholder="Employee">
                <option value=""></option>
                <?php foreach($all_employees as $employee) {?>
				<?php if($employee->user_id==$employee_id):?>
                <option value="<?php echo $employee->user_id?>"  selected > <?php echo $employee->first_name.' '.$employee->middle_name.' '.$employee->last_name;?></option>
				<?php endif;?>
                <?php } ?>
              </select>
            </div>



        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="start_date">Start Date</label>
              <input class="form-control <?php if(in_array('32m',role_resource_ids())){ echo 'form_date'; }else{echo 'form_date_five_day_leave_edit';} ?>" placeholder="Start Date" readonly="true" name="start_date" type="text" value="<?php echo $from_date;?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="end_date">End Date</label>
              <input class="form-control <?php if(in_array('32m',role_resource_ids())){ echo 'form_date'; }else{echo 'form_date_five_day_leave_edit';} ?>" placeholder="End Date" readonly="true" name="end_date" type="text" value="<?php echo $to_date;?>">
            </div>
          </div>
        </div>
        <div class="row">


		  <div class="col-md-12">
						<div class="form-group">
						  <h6><?php echo $this->lang->line('xin_e_details_document_file');?></h6>

						  <input type="file" name="document_file[]" id="p_file2" class="file-input1" multiple="multiple">

						  <small class="help-block"><?php echo $this->lang->line('xin_e_details_d_type_file');?></small></div>

						     <div class="form-group">
      <label for="reason">Reason</label>
      <textarea class="form-control" placeholder="Leave Reason" name="reason" cols="30" rows="3" id="reason"><?php echo $reason;?></textarea>
    </div>
						  </div>

						  <?php if($documentfile!='' && $documentfile!='no file') {?>
        <br>&nbsp;&nbsp;&nbsp;<a class="btn bg-teal-400" href="<?php echo site_url();?>download?type=leavedocument&filename=<?php echo $documentfile;?>"><i class="icon-download"></i> <?php echo $documentfile;?></a>
          <?php }

		  ?>


        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="description">Remarks</label>
          <textarea class="form-control textarea" placeholder="Remarks" name="remarks" cols="30" rows="15" id="remarks2"><?php echo html_entity_decode(stripcslashes($remarks));?></textarea>
        </div>
      </div>
    </div>




  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn bg-teal-400">Save</button>
  </div>
</form>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/fileupload.js"></script>
<script type="text/javascript">
 $(document).ready(function(){
	leave_availability_dialog();

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
			url : "<?php echo site_url("timesheet/leave_list") ?>",
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
	$('#remarks2').summernote({
	  height: 120,
	  minHeight: null,
	  maxHeight: null,
	  focus: false,
	  dialogsInBody: true,
		callbacks: {
		onImageUpload: function(files) {
		var $editor = $(this);
		var data = new FormData();
		data.append('file', files[0]);
		sendFile($editor,data);
		},
		onImageUploadError: null
		}
	});
	$('.note-children-container').hide();

	// Date
	$('.e_date').pickadate({ format: "dd mmmm yyyy"});
	/* Edit*/


	$("#edit_leave").submit(function(e){
	var fd = new FormData(this);
	//var remarks = $("#remarks2").code();
	/*Form Submit*/

		var obj = $(this), action = obj.attr('name');
		fd.append("is_ajax", 2);
		fd.append("edit_type", 'leave');
		fd.append("data", 'leave');
		fd.append("form", action);
		e.preventDefault();
		$('.save').prop('disabled', true);
		$.ajax({

			url: e.target.action,
			type: "POST",
			data:  fd,
			contentType: false,
			cache: false,
			processData:false,
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
});

function leave_availability_dialog(){
	var leave_type=$('#leave_type_dialog').val();
	var employee_id=$('#employee_id_dialog').val();
	if(leave_type!='' && employee_id!=''){
	$("#sh_message_dialog").html('');
	$.ajax({
		url: site_url+'timesheet/check_leave_availability',
		type: "GET",
		data: 'jd=1&type=availability&leave_type='+leave_type+'&employee_id='+employee_id,
		success: function (response) {
			if(response) {
				$("#sh_message_dialog").html(response);
			}
		}
		});

	}

}
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['leave_conversion_id']) && $_GET['data']=='leave_conversion'){

	 $user = $this->Xin_model->read_user_info($employee_id);


?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Leave Conversion For <?php echo change_fletter_caps($user[0]->first_name.' '.$user[0]->middle_name.' '.$user[0]->last_name);?></h4>
</div>

<form class="m-b-1" action="<?php echo site_url("timesheet/update_leave_conversion").'/'.$_GET['leave_conversion_id']; ?>/" method="post" name="update_leave_conversion" id="update_leave_conversion"  enctype="multipart/form-data">
  <input type="hidden" name="_method" value="EDIT">
  <div class="modal-body">
    <div class="row">
  <div class="col-lg-12">
  <?php


	$query1=$this->db->query('select * from xin_employees_approval where employee_id="'.$employee_id.'" AND type_of_approval="Leave Cash Conversion" AND field_id="'.$_GET['leave_conversion_id'].'"');
		$result1=$query1->result();
		$html1='';
		$approval_s=[];
			if($result1){
			$html1.='<table class="table table-md table-bordered"><tbody>
		    <tr class="bg-slate-600 text-center"><td colspan="3">Leave Cash Conversion Status</td></tr>';
			foreach($result1 as $approv_st){

			if($approv_st->approved_date!=''){$approved_date=format_date('d F Y',$approv_st->approved_date);}else{$approved_date= '-';}
				if($approv_st->approval_status==0){$approval_status='<span class="badge bg-info">Waiting for approval</span>';}else if($approv_st->approval_status==1){

$approval_s[$approv_st->head_of_approval]=$approv_st->approval_status;
					$approval_status= '<span class="badge bg-success">Approved</span>';}else if($approv_st->approval_status==2){$approval_status= '<span class="badge bg-danger">Declined</span>';}
				$html1.='<tr class="text-center">
					<td>'.$approv_st->head_of_approval.'</td>
					<td>'.$approved_date.'</td>
					<td>'.$approval_status.'</td>    </tr>  ';
			}
		     $html1.='</tbody></table>';
		    }

			echo $html1;
  ?>
  </div>
  </div>
    <div class="row mt-20">
      <div class="col-md-6">
        <input type="hidden" name="employee_id" value="employee_id"/>
        <input type="hidden" name="leave_conversion_id" value="<?php echo $_GET['leave_conversion_id'];?>"/>
        <div class="form-group">
          <label for="description">Total days of conversion</label>
		  <input class="form-control" placeholder="Total days of conversion" name="leave_conversion_count" type="text" value="<?php echo $leave_conversion_count;?>" id="leave_conversion_count" readonly>

        </div>


      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="description">Comments</label>

		  <input class="form-control" placeholder="Comments" name="conversion_comments" type="text" value="<?php echo html_entity_decode(stripcslashes($conversion_comments));?>" id="comments">

        </div>
      </div>




    </div>


	<div class="row">
	   <div class="col-md-6">
       <div class="form-group">
              <label for="calculation" class="control-label">Select Calculation Type</label>
              <select class="form-control" name="calculation_type" onChange="calculate_amount(this.value);" id="calculation_type" data-plugin="select_hrm" data-placeholder="Select Calculation Type">
            <option value="0" <?php if($leave_conversion_type==0){echo 'selected';}?>>Leave Cash Conversion (Basic)</option>
			<option value="1" <?php if($leave_conversion_type==1){echo 'selected';}?>>Leave Cash Conversion + (Basic+Accomodation)</option>
              </select>
            </div>
       </div>


	<div class="col-md-6"><label for="amount" class="control-label">Amount</label>
       <div class="form-group">
			  <input class="form-control" placeholder="Amount" name="calculated_amount" type="text" value="" id="calculated_amount" readonly><div class="form-control-currency"><?php echo $this->Xin_model->currency_sign('','',$employee_id);?></div>
            </div>
       </div>

	</div>





	<div class="row">

	<div class="col-md-6">
       <div class="form-group">
              <label for="added_date" class="control-label">Added Date (Add amounts automatically the salary ot this month)</label>

			  <div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                    <input class="form-control" placeholder="Added Date"  name="added_date" size="16" type="text"  value="<?php echo format_date('d F Y',$added_date);?>" readonly>
                    <span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>
                </div>
            </div>
       </div>
	   <?php if($session['role_name'] == AD_ROLE){?>
	    <div class="col-md-6">
	 <div class="form-group">
              <label for="calculation" class="control-label">Status</label>
              <select class="form-control" name="approved_status" data-plugin="select_hrm" data-placeholder="Select Status.." required>
			<option value="">Select Status</option>
            <option value="1" <?php if($approved_status==1){echo 'selected';}?>>Approved</option>
			<option value="2" <?php if($approved_status==2){echo 'selected';}?>>Declined</option>
              </select>
            </div>


       </div>
	   <?php } ?>


	</div>



  </div>

  <?php if($session['role_name'] == AD_ROLE){?>
   <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<button type="submit" class="btn bg-teal-400">Update</button>

  </div>
  <?php } else {?>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

	<?php
    if($session['user_id']!=$employee_id){
	if(($session['role_name'] == AD_ROLE || in_array($session['role_name'],$HR_L_ROLE) || (in_array('32m',role_resource_ids()))) && !$result1){

	?>
	<button type="submit" class="btn bg-teal-400">Save & Send Approval</button>
	<?php } }else{
		if(($session['role_name'] == R_M_ROLE || reporting_manager_access()) && !$result1){
		?>
	<button type="submit" class="btn bg-teal-400">Save & Send Approval</button>

		<?php }} ?>
  </div>

  <?php } ?>
  <div id="sho_m"></div>
</form>

<script type="text/javascript">
 $(document).ready(function(){
	  calculate_amount('<?php echo $leave_conversion_type;?>');

	  $.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        }
      });
	  var xin_table_conversion = $('#xin_table_conversion').DataTable({
		"bDestroy": true,
		"ajax": {
			url : site_url+"timesheet/leave_conversion_lists/?employee_id=<?php echo $employee_id;?>",
			type : 'GET'
		},"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();
		},"order": [[ 5, "desc" ]],
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
	// Add placeholder to the datatable filter option
    $('.dataTables_filter input[type=search]').attr('placeholder','Type to filter...');
	// Enable Select2 select for the length option
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));

	$("#update_leave_conversion").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
	    $('.save').prop('disabled', true);
	   $.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=11&data=update_leave_conversion&edit_type=update_leave_conversion&form="+action,
			cache: false,
			success: function (JSON) {
				$('#sho_m').html(JSON.message);
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit-modal-data-payrol').modal('toggle');
					xin_table_conversion.ajax.reload(function(){
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});


});


function calculate_amount(val){
	var calculation_type=val;
	$.ajax({
					type: "POST",
					url : site_url+"timesheet/calculate_amount_of_leave_conversion/",
					data: "&is_ajax=1&type=calculate_amount&employee_id=<?php echo $employee_id;?>&calculation_type="+calculation_type+"&leave_conversion_count=<?php echo $leave_conversion_count;?>",
					cache: false,
					success: function (vals) {
					var newString = vals.replace(/\s+/g,"");
					$('#calculated_amount').val(newString);
					}
				});}

</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['leave_conversion_id']) && $_GET['data']=='leave_conversion_emp'){

	 $user = $this->Xin_model->read_user_info($employee_id);


?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Leave Conversion For <?php echo change_fletter_caps($user[0]->first_name.' '.$user[0]->middle_name.' '.$user[0]->last_name);?></h4>
</div>

  <input type="hidden" name="_method" value="EDIT">
  <div class="modal-body">

  <div class="row">
  <div class="col-lg-12">
  <?php


	$query1=$this->db->query('select * from xin_employees_approval where employee_id="'.$employee_id.'" AND type_of_approval="Leave Cash Conversion" AND field_id="'.$_GET['leave_conversion_id'].'"');
		$result1=$query1->result();
		$html1='';
			if($result1){
			$html1.='<table class="table table-md table-bordered"><tbody>
		    <tr class="bg-slate-600 text-center"><td colspan="3">Leave Cash Conversion Status</td></tr>';
			foreach($result1 as $approv_st){

			if($approv_st->approved_date!=''){$approved_date=format_date('d F Y',$approv_st->approved_date);}else{$approved_date= '-';}
				if($approv_st->approval_status==0){$approval_status='<span class="badge bg-info">Waiting for approval</span>';}else if($approv_st->approval_status==1){
					$approval_status= '<span class="badge bg-success">Approved</span>';}else if($approv_st->approval_status==2){$approval_status= '<span class="badge bg-danger">Declined</span>';}
				$html1.='<tr class="text-center">
					<td>'.$approv_st->head_of_approval.'</td>
					<td>'.$approved_date.'</td>
					<td>'.$approval_status.'</td>    </tr>  ';
			}
		     $html1.='</tbody></table>';
		    }

			echo $html1;
  ?>
  </div>
  </div>
    <div class="row mt-20">
      <div class="col-md-6">
        <input type="hidden" name="employee_id" value="<?php echo $employee_id;?>" />
		<input type="hidden" name="leave_conversion_id" value="<?php echo $_GET['leave_conversion_id'];?>"/>
		<div class="form-group">
          <label for="description">Total days of conversion</label>

		  <input class="form-control" placeholder="Total days of conversion" name="leave_conversion_count" type="text" value="<?php echo $leave_conversion_count;?>" id="leave_conversion_count" readonly>

        </div>

      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="description">Comments</label>

		  <input class="form-control" placeholder="Comments" name="conversion_comments" type="text" value="<?php echo html_entity_decode(stripcslashes($conversion_comments));?>" id="comments">

        </div>
      </div>

	  	<div class="col-md-6">
		<label for="amount" class="control-label">Amount</label>
       <div class="form-group">
			  <input class="form-control" placeholder="Amount" name="calculated_amount" type="text" id="calculated_amount" value="<?php echo $amount;?>" readonly><div class="form-control-currency">
		<?php echo $this->Xin_model->currency_sign('','',$employee_id);?>										</div>
            </div>
       </div>
	  <div class="col-md-6">
       <div class="form-group">
              <label for="added_date" class="control-label">Added Date (Add amounts automatically the salary ot this month)</label>
              <input class="form-control date" readonly placeholder="Added Date" name="added_date" type="text" value="<?php echo format_date('d F Y',$added_date);?>">
            </div>
       </div>
    </div>
	</div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

  </div>

  <script type="text/javascript">
  <?php if($amount==''){?>
  calculate_amount('<?php echo $leave_conversion_type;?>');
  <?php } ?>

  function calculate_amount(val){
	var calculation_type=val;
	$.ajax({
					type: "POST",
					url : site_url+"timesheet/calculate_amount_of_leave_conversion/",
					data: "&is_ajax=1&type=calculate_amount&employee_id=<?php echo $employee_id;?>&calculation_type="+calculation_type+"&leave_conversion_count=<?php echo $leave_conversion_count;?>",
					cache: false,
					success: function (vals) {
					var newString = vals.replace(/\s+/g,"");
					$('#calculated_amount').val(newString);
					}
				});}
  </script>
  <?php } else if(isset($_GET['jd']) && isset($_GET['leave_conversion_id']) && $_GET['data']=='leave_conversion_approval'){
	 $user = $this->Xin_model->read_user_info($employee_id);
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Leave Conversion For <?php echo change_fletter_caps($user[0]->first_name.' '.$user[0]->middle_name.' '.$user[0]->last_name);?></h4>
</div>
<form class="m-b-1" action="<?php echo site_url("timesheet/approve_leave_conversion").'/'.$_GET['leave_conversion_id']; ?>/" method="post" name="approve_leave_conversion" id="approve_leave_conversion"  enctype="multipart/form-data">
  <input type="hidden" name="_method" value="EDIT">
  <div class="modal-body">
    <div class="row">
  <div class="col-lg-12">

  </div>
  </div>
    <div class="row mt-20">
      <div class="col-md-6">
        <input type="hidden" name="employee_id" value="<?php echo $employee_id;?>" />
        <input type="hidden" name="leave_conversion_id" value="<?php echo $_GET['leave_conversion_id'];?>"/>
		 <div class="form-group">
          <label for="description">Total days of conversion</label>

		  <input class="form-control" placeholder="Total days of conversion" name="leave_conversion_count" type="text" value="<?php echo $leave_conversion_count;?>" id="leave_conversion_count" readonly>

        </div>

      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="description">Comments</label>

		  <input class="form-control" placeholder="Comments" name="conversion_comments" type="text" value="<?php echo html_entity_decode(stripcslashes($conversion_comments));?>" id="comments" readonly>

        </div>
      </div>
    </div>


	<div class="row">
	   <div class="col-md-6">
       <div class="form-group">
              <label for="calculation" class="control-label">Select Calculation Type</label>
              <select class="form-control" name="calculation_type" onChange="calculate_amount(this.value);" id="calculation_type" data-plugin="select_hrm" data-placeholder="Select Calculation Type">
            <option value="0" <?php if($leave_conversion_type==0){echo 'selected';}?>>Leave Cash Conversion (Basic)</option>
			<option value="1" <?php if($leave_conversion_type==1){echo 'selected';}?>>Leave Cash Conversion + (Basic+Accomodation)</option>
              </select>
            </div>
       </div>


	<div class="col-md-6"><label for="amount" class="control-label">Amount</label>
       <div class="form-group">
			  <input class="form-control" placeholder="Amount" name="calculated_amount" type="text" value="" id="calculated_amount" readonly><div class="form-control-currency"><?php echo $this->Xin_model->currency_sign('','',$employee_id);?></div>
            </div>
       </div>

	</div>

	<div class="row">

	<div class="col-md-6">
       <div class="form-group">
              <label for="added_date" class="control-label">Added Date (Add amounts automatically the salary ot this month)</label>

			  <div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                    <input class="form-control" placeholder="Added Date"  name="added_date" size="16" type="text"  value="<?php echo format_date('d F Y',$added_date);?>" readonly>
                    <span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>
                </div>

            </div>
       </div>


	   <div class="col-md-6">
	 <div class="form-group">
              <label for="calculation" class="control-label">Status</label>
              <select class="form-control" name="approved_status" data-plugin="select_hrm" data-placeholder="Select Status.." required>
			<option value="">Select Status</option>
            <option value="1" <?php if($approved_status==1){echo 'selected';}?>>Approved</option>
			<option value="2" <?php if($approved_status==2){echo 'selected';}?>>Declined</option>
              </select>
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


calculate_amount('<?php echo $leave_conversion_type;?>');


$("#approve_leave_conversion").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
	    $('.save').prop('disabled', true);
	   $.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=11&redirect=yes&data=approve_leave_conversion&edit_type=approve_leave_conversion&approve_link=<?php echo $_GET['approve_link'];?>&decline_link=<?php echo $_GET['decline_link'];?>&form="+action,
			cache: false,
			success: function (JSON) {

				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {

					if (JSON.message != '') {

						 window.location.assign('http://'+JSON.message);
					}else{
					toastr.success(JSON.result);
					$('.save').prop('disabled', false);
					}


				}




			}
		});
	});
});


function calculate_amount(val){
	var calculation_type=val;
	$.ajax({
					type: "POST",
					url : "<?php echo base_url(); ?>timesheet/calculate_amount_of_leave_conversion/",
					data: "&is_ajax=1&type=calculate_amount&employee_id=<?php echo $employee_id;?>&calculation_type="+calculation_type+"&leave_conversion_count=<?php echo $leave_conversion_count;?>",
					cache: false,
					success: function (vals) {
					var newString = vals.replace(/\s+/g,"");
					$('#calculated_amount').val(newString);
					}
				});}

</script>
<?php } else if(isset($_GET['jd']) && $_GET['data']=='check_leave_availability'){
	$user = $this->Xin_model->read_user_info($session['user_id']);
	$gender_type=$user[0]->gender;
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title first_section"><?php if($btn_type=='add'){ echo 'Add New Leave';}else {echo 'Check Leave Availability';}?></h4>
  <h4 class="modal-title second_section" style="display:none;">Enter Details</h4>
</div>
<form action="<?php echo site_url("timesheet/add_leave") ?>" method="post" name="add_leave" id="xin-form-dial" enctype="multipart/form-data">
  <input type="hidden" id="employee_id_dialog" name="employee_id" value="<?php echo $session['user_id'];?>">
  <input type="hidden" id="current_date" value="<?php echo date('Y-m-d');?>">
  <div class="modal-body">
    <div class="row">
	 <div class="col-md-12 first_section">
	  <div class="form-group">
          <label for="leave_type" class="control-label">Leave Type</label>
          <select class="form-control" name="leave_type" id="leave_type_dialog" onchange="leave_availability_dialog();" data-plugin="select_hrm" data-placeholder="Leave Type">
            <option value=""></option>
            <?php foreach($all_leave_types as $type) {
			if($gender_type=='Male'){
			if($type->type_name!='Maternity Leave'){
			?>
            <option value="<?php echo $type->type_id?>"><?php echo $type->type_name;?></option>
			<?php }
			}else{ ?>
			<option value="<?php echo $type->type_id?>"><?php echo $type->type_name;?></option>
			<?php }
			} ?>
          </select>
        </div>
		<div id="sh_message_dialog"></div>
	 </div>

      <div class="col-md-6 second_section" style="display:none;">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="start_date">Start Date<?php echo REQUIRED_FIELD;?></label>
			  <div class="input-group date <?php if(in_array('32m',role_resource_ids())){ echo 'form_date'; }else{echo 'form_date_five_day_leave';} ?>" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                    <input onchange="apply_leaves();" class="form-control" placeholder="Start Date"  name="start_date" size="16" type="text" id="start_date_d"  value="" readonly>
                    <span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>
                </div>


            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="end_date">End Date<?php echo REQUIRED_FIELD;?></label>
			  <div class="input-group date <?php if(in_array('32m',role_resource_ids())){ echo 'form_date'; }else{echo 'form_date_five_day_leave';} ?>" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                    <input onchange="apply_leaves();" class="form-control" placeholder="End Date"  name="end_date" size="16" type="text" id="end_date_d"  value="" readonly>
                    <span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>
                </div>

            </div>
          </div>

	   </div>
		<div id="sh_message_1">

		</div>
        <div class="row">
		  <div class="col-md-12">
						<div class="form-group">
						  <label><?php echo $this->lang->line('xin_e_details_document_file');?><span class ="mandatory_field1" style="display:none;"><?php echo REQUIRED_FIELD;?></span></label>
						  <input type="file" name="document_file[]" id="p_file2" class="file-input12" multiple="multiple">
						  <span class="for-sickleave" style="color:red;"> (***Sick leave - to attach a valid attested medical certificate***)</span>
						  <small class="help-block"><?php echo $this->lang->line('xin_e_details_d_type_file');?></small></div>
						  </div>
        </div>
      </div>

	<div class="col-md-6 second_section" style="display:none;">
				     <div class="form-group">
      <label for="reason">Reason</label><span class ="mandatory_field2" style="display:none;"><?php echo REQUIRED_FIELD;?></span>
      <textarea style="min-height: 9.2em;resize: none;" class="form-control" placeholder="Leave Reason" name="reason" cols="30" rows="3" id="reason"></textarea>
    </div>
	</div>

	<input type="hidden" name="leave_dates"  id="leave_dates_1" value=""/>
    </div>

  </div>
  <input type='hidden' value="0" name="mandatory_f" id="mandatory_f" />
  <div class="modal-footer">
	<button type="button" style="display:none;" class="btn bg-teal-400 apply_now">Apply Now</button>
	<button type="button" style="display:none;" class="btn btn-default back_btn">Back</button>
    <button type="submit" style="display:none;" class="btn bg-teal-400 submit_btn">Submit</button>
  </div>
</form>
<style>
.file-zoom-dialog .btn-navigate {background: #74B9B2;}
.file-zoom-dialog .btn-next{right: 2px !important;}
.file-zoom-dialog .btn-prev{left: 2px !important;}
</style>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/imageupload.js"></script>
<script type="text/javascript">
 $(document).ready(function(){
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));

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

	$('.apply_now').on('click',function(){
	$('.apply_now').hide();
	$('.submit_btn').show();
	$('.back_btn').show();
	$('.first_section').hide();
	$('.second_section').show();

	var leave_type_name=$('#leave_type_dialog option:selected').html();
	if(leave_type_name=='Sick Leave'){
		$('.for-sickleave,.mandatory_field1').show();
	}else{
		$('.for-sickleave,.mandatory_field1').hide();
	}
	if(leave_type_name=='Authorised Absence' || leave_type_name=='Emergency Leave'){
		$('.mandatory_field2').show();
	}else{
		$('.mandatory_field2').hide();
	}
	});

	$('.back_btn').on('click',function(){
	$('.apply_now').show();
	$('.submit_btn').hide();
	$('.back_btn').hide();
	$('.first_section').show();
	$('.second_section').hide();
	});
	$("#xin-form-dial").submit(function(e){
		var fd = new FormData(this);
		var obj = $(this), action = obj.attr('name');
		fd.append("is_ajax", 1);
		fd.append("add_type", 'leave');
		fd.append("data", 'leave');
		fd.append("form", action);
		e.preventDefault();
		$('.submit_btn').prop('disabled', true);
		$.ajax({
			url: e.target.action,
			type: "POST",
			data:  fd,
			contentType: false,
			cache: false,
			processData:false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.submit_btn').prop('disabled', false);
				} else {
					xin_table();
					toastr.success(JSON.result);
					$('.edit-modal-data').modal('toggle');
				}
			}
		});
	});

	});

function apply_leaves(){
	var leave_type=$('#leave_type_dialog').val();
	var employee_id=$('#employee_id_dialog').val();
	var start_date=$('#start_date_d').val();
	var end_date=$('#end_date_d').val();
	if(leave_type!='' && employee_id!='' && start_date!='' && end_date!=''){
	$("#sh_message_1").html('');
	$.ajax({
		url: site_url+'timesheet/check_leave_availability',
		type: "GET",
		data: 'jd=1&type=availability&leave_type='+leave_type+'&employee_id='+employee_id+'&start_date='+start_date+'&end_date='+end_date,
		success: function (response) {
			var parse=JSON.parse(response);
				if(parse.date_status!='') {
				$("#sh_message_1").html(parse.date_status);
			    }
			    $('#leave_dates_1').val(parse.leave_array);
		}
		});
		var leave_type_name=$('#leave_type_dialog option:selected').html();
		if(leave_type_name=='Sick Leave'){
		$.ajax({
		url: site_url+'timesheet/eligibility_check',
		type: "GET",
		data: 'jd=1&type=availability&employee_id='+employee_id+'&start_date='+start_date+'&end_date='+end_date,
		success: function (dt) {
			if(dt==1){
			$('.for-sickleave,.mandatory_field1').show();
			$('#mandatory_f').val(1);
			}else{
			$('.for-sickleave,.mandatory_field1').hide();
			$('#mandatory_f').val(0);
			}
		}
		});
		}


	}





}
function leave_availability_dialog(){


	$("#sh_message_1").html('');
	$('#leave_dates_1,#reason,#start_date_d,#end_date_d').val('');
	$('.fileinput-remove').click();
	var leave_type=$('#leave_type_dialog').val();
	var employee_id=$('#employee_id_dialog').val();
	var current_date=$('#current_date').val();
	if(leave_type!='' && employee_id!=''){
	$("#sh_message_dialog").html('');
	$.ajax({
		url: site_url+'timesheet/check_leave_availability',
		type: "GET",
		data: 'jd=1&type=availability&leave_type='+leave_type+'&employee_id='+employee_id+'&start_date='+current_date+'&end_date='+current_date+'&message_counts=show',
		success: function (response) {
			var parse=JSON.parse(response);
			if(response) {
				$("#sh_message_dialog").html(parse.message_status);
				if(parse.counts==1){
				$('.apply_now').show();
				}else{
				$('.apply_now').hide();
				}
			}
		}
		});

	}
}
</script>
<?php } else if(isset($_GET['jd']) && $_GET['data']=='check_leave_availability_admin'){
	$user = $this->Xin_model->read_user_info($session['user_id']);
	$gender_type=$user[0]->gender;
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title first_section"><?php if($btn_type=='add'){ echo 'Add New Leave';}else {echo 'Check Leave Availability';}?></h4>
  <h4 class="modal-title second_section" style="display:none;">Enter Details</h4>
</div>
<form action="<?php echo site_url("timesheet/add_leave") ?>" method="post" name="add_leave" id="xin-form-dial" enctype="multipart/form-data">

  <input type="hidden" id="current_date" value="<?php echo date('Y-m-d');?>">
  <div class="modal-body">
    <div class="row">
	 <div class="col-md-6 first_section">
	  <div class="form-group">
          <label for="leave_type" class="control-label">Leave Type</label>
          <select class="form-control" name="leave_type" id="leave_type_dialog" onchange="leave_availability_dialog();" data-plugin="select_hrm" data-placeholder="Leave Type">
            <option value=""></option>
            <?php foreach($all_leave_types as $type) {?>
            <option value="<?php echo $type->type_id?>"><?php echo $type->type_name;?></option>
			<?php
			} ?>
          </select>
        </div>

	 </div>

	 <div class="col-md-6 first_section">
	  <div class="form-group">
                        <label for="employees" class="control-label">Leave for Employee</label>
                        <select class="form-control" onchange="leave_availability_dialog();" name="employee_id" id="employee_id_dialog" data-plugin="select_hrm" data-placeholder="Employee">
                          <option value=""></option>
                          <?php foreach($all_employees as $employee) {?>
                          <option value="<?php echo $employee->user_id?>"> <?php echo change_fletter_caps($employee->first_name.' '.$employee->middle_name.' '.$employee->last_name);?></option>
                          <?php } ?>
                        </select>
                      </div>

	 </div>
	  <div class="clearfix"></div>
	  <div class="col-lg-12 first_section">
		<div id="sh_message_dialog"></div>
	  </div>

      <div class="col-md-6 second_section" style="display:none;">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="start_date">Start Date<?php echo REQUIRED_FIELD;?></label>
			  <div class="input-group date <?php if(in_array('32m',role_resource_ids())){ echo 'form_date'; }else{echo 'form_date_five_day_leave';} ?>" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                    <input onchange="apply_leaves();" class="form-control" placeholder="Start Date"  name="start_date" size="16" type="text" id="start_date_d"  value="" readonly>
                    <span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>
                </div>


            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="end_date">End Date<?php echo REQUIRED_FIELD;?></label>
			  <div class="input-group date <?php if(in_array('32m',role_resource_ids())){ echo 'form_date'; }else{echo 'form_date_five_day_leave';} ?>" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                    <input onchange="apply_leaves();" class="form-control" placeholder="End Date"  name="end_date" size="16" type="text" id="end_date_d"  value="" readonly>
                    <span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>
                </div>

            </div>
          </div>
        </div>
		<div id="sh_message_1">

		</div>
        <div class="row">
		  <div class="col-md-12">
						<div class="form-group">
						  <label><?php echo $this->lang->line('xin_e_details_document_file');?><span class ="mandatory_field1" style="display:none;"><?php echo REQUIRED_FIELD;?></span></label>
						  <input type="file" name="document_file[]" id="p_file2" class="file-input12" multiple="multiple">
						  <span class="for-sickleave" style="color:red;"> (***Sick leave - to attach a valid attested medical certificate***)</span>
						  <small class="help-block"><?php echo $this->lang->line('xin_e_details_d_type_file');?></small></div>
						  </div>
        </div>
      </div>

	<div class="col-md-6 second_section" style="display:none;">
				     <div class="form-group">
      <label for="reason">Reason</label><span class ="mandatory_field2" style="display:none;"><?php echo REQUIRED_FIELD;?></span>
      <textarea style="min-height: 9.2em;resize: none;" class="form-control" placeholder="Leave Reason" name="reason" cols="30" rows="3" id="reason"></textarea>
    </div>
	</div>

	<input type="hidden" name="leave_dates"  id="leave_dates_1" value=""/>
    </div>

  </div>
  <input type='hidden' value="0" name="mandatory_f" id="mandatory_f" />
  <div class="modal-footer">
	<button type="button" style="display:none;" class="btn bg-teal-400 apply_now">Apply Now</button>
	<button type="button" style="display:none;" class="btn btn-default back_btn">Back</button>
    <button type="submit" style="display:none;" class="btn bg-teal-400 submit_btn">Submit</button>
  </div>
</form>
<style>
.file-zoom-dialog .btn-navigate {background: #74B9B2;}
.file-zoom-dialog .btn-next{right: 2px !important;}
.file-zoom-dialog .btn-prev{left: 2px !important;}
</style>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/imageupload.js"></script>
<script type="text/javascript">
 $(document).ready(function(){
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));

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

	$('.apply_now').on('click',function(){
	$('.apply_now').hide();
	$('.submit_btn').show();
	$('.back_btn').show();
	$('.first_section').hide();
	$('.second_section').show();

	var leave_type_name=$('#leave_type_dialog option:selected').html();
	if(leave_type_name=='Sick Leave'){
		$('.for-sickleave,.mandatory_field1').show();
	}else{
		$('.for-sickleave,.mandatory_field1').hide();
	}
	if(leave_type_name=='Authorised Absence' || leave_type_name=='Emergency Leave'){
		$('.mandatory_field2').show();
	}else{
		$('.mandatory_field2').hide();
	}
	});

	$('.back_btn').on('click',function(){
	$('.apply_now').show();
	$('.submit_btn').hide();
	$('.back_btn').hide();
	$('.first_section').show();
	$('.second_section').hide();
	});


	$("#xin-form-dial").submit(function(e){
		var fd = new FormData(this);
		var obj = $(this), action = obj.attr('name');
		fd.append("is_ajax", 1);
		fd.append("add_type", 'leave');
		fd.append("data", 'leave');
		fd.append("form", action);
		e.preventDefault();
		$('.submit_btn').prop('disabled', true);
		$.ajax({
			url: e.target.action,
			type: "POST",
			data:  fd,
			contentType: false,
			cache: false,
			processData:false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.submit_btn').prop('disabled', false);
				} else {
					$('#xin_table').dataTable().api().ajax.reload(function(){
						toastr.success(JSON.result);
					}, true);
					$('.edit-modal-data').modal('toggle');
				}
			}
		});
	});

	});

function apply_leaves(){
	var leave_type=$('#leave_type_dialog').val();
	var employee_id=$('#employee_id_dialog').val();
	var start_date=$('#start_date_d').val();
	var end_date=$('#end_date_d').val();
	if(leave_type!='' && employee_id!='' && start_date!='' && end_date!=''){
	$("#sh_message_1").html('');
	$.ajax({
		url: site_url+'timesheet/check_leave_availability',
		type: "GET",
		data: 'jd=1&type=availability&leave_type='+leave_type+'&employee_id='+employee_id+'&start_date='+start_date+'&end_date='+end_date,
		success: function (response) {
			var parse=JSON.parse(response);
				if(parse.date_status!='') {
				$("#sh_message_1").html(parse.date_status);
			    }
			    $('#leave_dates_1').val(parse.leave_array);
		}
		});

	}

		var leave_type_name=$('#leave_type_dialog option:selected').html();
		if(leave_type_name=='Sick Leave'){
		$.ajax({
		url: site_url+'timesheet/eligibility_check',
		type: "GET",
		data: 'jd=1&type=availability&employee_id='+employee_id+'&start_date='+start_date+'&end_date='+end_date,
		success: function (dt) {
			if(dt==1){
			$('.for-sickleave,.mandatory_field1').show();
			$('#mandatory_f').val(1);
			}else{
			$('.for-sickleave,.mandatory_field1').hide();
			$('#mandatory_f').val(0);
			}
		}
		});
		}



}
function leave_availability_dialog(){


	$("#sh_message_1").html('');
	$('#leave_dates_1,#reason,#start_date_d,#end_date_d').val('');
	$('.fileinput-remove').click();
	var leave_type=$('#leave_type_dialog').val();
	var employee_id=$('#employee_id_dialog').val();
	var current_date=$('#current_date').val();
	if(leave_type!='' && employee_id!=''){
	$("#sh_message_dialog").html('');
	$.ajax({
		url: site_url+'timesheet/check_leave_availability',
		type: "GET",
		data: 'jd=1&type=availability&leave_type='+leave_type+'&employee_id='+employee_id+'&start_date='+current_date+'&end_date='+current_date+'&message_counts=show',
		success: function (response) {
			var parse=JSON.parse(response);
			if(response) {
				$("#sh_message_dialog").html(parse.message_status);
				if(parse.counts==1){
				$('.apply_now').show();
				}else{
				$('.apply_now').hide();
				}
			}
		}
		});

	}
}
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['leave_id']) && $_GET['data']=='editLeaveForm'){ 

  $minDateLeave = date('Y-m-d',(strtotime ( '-7 day' , strtotime ( $from_date) ) ));
  ?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Leave</h4>
</div>
<form action="<?php echo site_url("timesheet/edit_leave_employee") ?>" method="post" name="edit_leave" id="xin-form-dial-edit" enctype="multipart/form-data">

  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" id="employee_id_dialog_edit" name="employee_id" value="<?php echo $employee_id;?>">
  <input type="hidden" id="leave_id" name="leave_id" value="<?php echo $leave_id;?>">
  <input type="hidden" id="leave_type_dialog_edit" name="leave_type" value="<?php echo $leave_type_id;?>">
  <input type="hidden" id="current_date_edit" value="<?php echo date('Y-m-d');?>">
  <input type="hidden" value="0" name="mandatory_f" id="mandatory_f">
  <input type="hidden" name="leave_dates"  id="leave_dates_1_edit" value=""/>
  <input type="hidden" name="applied_on"  value="<?php echo $applied_on;?>"/>
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">      
        <div id="sh_message_dialog_edit"></div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="start_date">Start Date<?php echo REQUIRED_FIELD;?></label>
              <div class="input-group date <?php if(in_array('32m',role_resource_ids())){ echo 'form_date'; }else{echo 'form_date_five_day_leave_edit';} ?>" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                    <input onchange="apply_leaves();" class="form-control" placeholder="Start Date"  name="start_date" size="16" type="text" id="start_date_d_edit"  value="<?php echo format_date('d F Y',$from_date);?>" readonly>
                    <span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>
              </div>
            </div>

          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="end_date">End Date<?php echo REQUIRED_FIELD;?></label>
              <div class="input-group date <?php if(in_array('32m',role_resource_ids())){ echo 'form_date'; }else{echo 'form_date_five_day_leave_edit';} ?>"  data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                  <input onchange="apply_leaves();" class="form-control" placeholder="End Date"  name="end_date" size="16" type="text" id="end_date_d_edit"  value="<?php echo format_date('d F Y',$to_date);?>" readonly>
                  <span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>
              </div>
            </div>

            

          </div>
		  <div class="col-md-12"><div id="sh_message_1_edit"></div></div>
        </div>
		
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <h6><?php echo $this->lang->line('xin_e_details_document_file');?></h6>

              <input type="file" name="document_file[]" id="p_file2" class="file-input1" multiple="multiple">

              <small class="help-block"><?php echo $this->lang->line('xin_e_details_d_type_file');?></small>
            </div>
          </div>

                  <?php if($documentfile!='' && $documentfile!='no file') {?>
            <br>&nbsp;&nbsp;&nbsp;<a class="btn bg-teal-400" href="<?php echo site_url();?>download?type=leavedocument&filename=<?php echo $documentfile;?>"><i class="icon-download"></i> <?php echo $documentfile;?></a>
              <?php }
          ?>

        </div>
      </div>
      <div class="col-md-6">
          <div class="form-group">
            <label for="reason">Reason</label>
            <textarea class="form-control" placeholder="Leave Reason" name="reason" cols="30" rows="3" id="reason"><?php echo $reason;?></textarea>
          </div>
      </div>
    </div>

  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" name="submit" class="btn bg-teal-400">Update</button>
  </div>
</form>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/fileupload.js"></script>
<script type="text/javascript">


function leave_availability_dialog(){

  $("#sh_message_1_edit").html('');
  $('.fileinput-remove').click();
  var leave_type=$('#leave_type_dialog_edit').val();
  var employee_id=$('#employee_id_dialog_edit').val();
  var current_date=$('#current_date_edit').val();
  if(leave_type!='' && employee_id!=''){
  $("#sh_message_dialog_edit").html('');
  $.ajax({
    url: site_url+'timesheet/check_leave_availability',
    type: "GET",
    data: 'jd=1&type=availability&leave_type='+leave_type+'&employee_id='+employee_id+'&start_date='+current_date+'&end_date='+current_date+'&message_counts=show',
    success: function (response) {
      var parse=JSON.parse(response);
      if(response) {
        $("#sh_message_dialog_edit").html(parse.message_status);
        if(parse.counts==1){
        $('.apply_now').show();
        }else{
        $('.apply_now').hide();
        }
      }
    }
    });

  }
}

function apply_leaves(){

  var leave_type=$('#leave_type_dialog_edit').val();
  var employee_id=$('#employee_id_dialog_edit').val();
  var start_date=$('#start_date_d_edit').val();
  var end_date=$('#end_date_d_edit').val();
  if(leave_type!='' && employee_id!='' && start_date!='' && end_date!=''){
  $("#sh_message_1_edit").html('');
  $.ajax({
    url: site_url+'timesheet/check_leave_availability',
    type: "GET",
    data: 'jd=1&type=availability&leave_type='+leave_type+'&employee_id='+employee_id+'&start_date='+start_date+'&end_date='+end_date,
    success: function (response) {
      var parse=JSON.parse(response);
        if(parse.date_status!='') {
          $("#sh_message_1_edit").html(parse.date_status);
          }

          $('#leave_dates_1_edit').val(parse.leave_array);
    }
    });
    var leave_type_name=$('#leave_type_dialog option:selected').html();
    if(leave_type_name=='Sick Leave'){
    $.ajax({
    url: site_url+'timesheet/eligibility_check',
    type: "GET",
    data: 'jd=1&type=availability&employee_id='+employee_id+'&start_date='+start_date+'&end_date='+end_date,
    success: function (dt) {
      if(dt==1){
      $('.for-sickleave,.mandatory_field1').show();
      $('#mandatory_f').val(1);
      }else{
      $('.for-sickleave,.mandatory_field1').hide();
      $('#mandatory_f').val(0);
      }
    }
    });
    }

  }
}
$(document).ready(function(){


    $("#xin-form-dial-edit").submit(function(e){
      e.preventDefault();
      var fd = new FormData(this);
      var obj = $(this), action = obj.attr('name');
      fd.append("is_ajax", 1);
      fd.append("edit_type", 'leave');
      fd.append("data", 'leave');
      fd.append("form", action);
      $('.submit_btn').prop('disabled', true);
      $.ajax({
        url: e.target.action,
        type: "POST",
        data:  fd,
        contentType: false,
        cache: false,
        processData:false,
        success: function (JSON) {
          if (JSON.error != '') {
            toastr.error(JSON.error);
            $('.submit_btn').prop('disabled', false);
          } else {
            xin_table();
            toastr.success(JSON.result);
            $('.edit-modal-data-1').modal('toggle');
          }
        }
      });
    });


  
});

</script>
<?php } ?>

<script type="text/javascript">
$(document).ready(function(){

  var minDateLeave = moment().subtract(7, 'days').format('DD MMMM YYYY');
  $('.form_date_five_day_leave').datetimepicker({
    weekStart: 1,
    todayBtn:  1,
    autoclose: 1,
    startDate: minDateLeave,
    todayHighlight: 1,
    startView: 2,
    minView: 2,
    forceParse: 0,
    pickerPosition: "bottom-left"
  });

  var minDateLeave = '<?php echo $minDateLeave;?>';
  $('.form_date_five_day_leave_edit').datetimepicker({
    weekStart: 1,
    todayBtn:  1,
    autoclose: 1,
    startDate: minDateLeave,
    todayHighlight: 1,
    startView: 2,
    minView: 2,
    forceParse: 0,
    pickerPosition: "bottom-left"
  });

});

</script>