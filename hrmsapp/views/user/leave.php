<style type="text/css">
.table-responsive {
    overflow-x: visible !important;
}
</style>
<?php
/* Leave view
*/
?>
<?php $session = $this->session->userdata('username');?>
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">							 
			<strong><?php echo $this->lang->line('xin_list_all');?></strong> Leaves							
		</h5>										
		<div class="heading-elements">						
				<div class="add-record-btn">								
					<button class="btn btn-sm bg-teal-400 check_leave_availability" data-toggle="modal" data-target=".edit-modal-data" data-btn_type="availability" data-backdrop="static" data-keyboard="false" >Check Leave Availability</button>
					<button class="btn btn-sm bg-teal-400 check_leave_availability" data-toggle="modal" data-target=".edit-modal-data" data-btn_type="add" data-backdrop="static" data-keyboard="false"><?php echo $this->lang->line('xin_add_new');?></button>
				</div>
		</div>
	</div>

	<div class="panel-body">
		<table class="table table-framed"> 
			<thead>
				<tr>  <th>Applied On</th>
						<th class="col-lg-3">Leave Type</th>
						<th>Start Date</th>
						<th>End Date</th>
						<th>No.of.Days</th>			
						<th>Action</th>			
				</tr>
			</thead>
			<tbody id="xin_table">
			</tbody>
		</table>							
  </div>  
</div>
<style>
	.file-preview-image {
			width: unset !important;
	}
	.kv-file-upload,.fileinput-upload-button{
		display: none !important;
	}
</style>

<script type="text/javascript">

	
$( document ).on( "click", ".delete", function() {

	var url = '<?php echo base_url() ?>';

  	$('input[name=_token]').val($(this).data('record-id'));
  	$('.delete_record').attr('action',url+'employee/leave/delete_leave/'+$(this).data('record-id'));
   	swal({
            title: "Are you sure?",
            text: "Record deleted can't be restored!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#FF7043",
            confirmButtonText: "Yes, delete it!",   
      
        },
    function(isConfirm){
        if (isConfirm) {
	        var obj = $('.delete_record'), action = obj.attr('name');   
	        $.ajax({
	          type: "POST",
	          url: $('.delete_record').attr('action'),
	          data: obj.serialize()+"&is_ajax=2&form="+action,
	          cache: false,
	          	success: function (JSON) {
		            if (JSON.error != '') {
		              toastr.error(JSON.error);
		            } else {
		          //       xin_table.ajax.reload(function(){ 
	          	// 		toastr.success(JSON.result);
		        		// }, true); 
		        		window.location.reload();
		            
		            }
	          	}
	        });
      
        }
      
    });
});

</script>