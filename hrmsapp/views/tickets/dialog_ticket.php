<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['ticket_id']) && $_GET['data']=='ticket'){
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Ticket</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("tickets/update").'/'.$ticket_id; ?>" method="post" name="edit_ticket" id="edit_ticket">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $ticket_id;?>">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="task_name">Subject</label>
          <input class="form-control" placeholder="Subject" name="subject" type="text" value="<?php echo $subject;?>">
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="employees">Ticket for Employee</label>
              <select class="form-control" name="employee_id" data-plugin="select_hrm" data-placeholder="Employee">
                <option value=""></option>
                <?php foreach($all_employees as $employee) {?>
                <option value="<?php echo $employee->user_id?>" <?php if($employee->user_id==$employee_id):?> selected <?php endif;?>> <?php echo $employee->first_name.' '.$employee->middle_name.' '.$employee->last_name;?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="ticket_priority" class="control-label">Priority</label>
              <select name="ticket_priority" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="Select Priority...">
                <option value=""></option>
                <option value="1" <?php if($ticket_priority==1):?> selected <?php endif;?>>Low</option>
                <option value="2" <?php if($ticket_priority==2):?> selected <?php endif;?>>Medium</option>
                <option value="3" <?php if($ticket_priority==3):?> selected <?php endif;?>>High</option>
                <option value="4" <?php if($ticket_priority==4):?> selected <?php endif;?>>Critical</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="description">Ticket Description</label>
          <textarea class="form-control textarea" placeholder="Description" name="description" cols="30" rows="15" id="description2"><?php echo html_entity_decode(stripcslashes($description));?></textarea>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn bg-teal-400">Update</button>
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
		    url : "<?php echo site_url("tickets/ticket_list") ?>",
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
	
	$('[data-plugin="xin_select"]').select2($(this).attr('data-options'));
    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			
		 
		
		$('#description2').summernote({
		  height: 67,
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

		/* Edit data */
		$("#edit_ticket").submit(function(e){
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
			//var description = $("#description2").code();
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=1&edit_type=ticket&form="+action,//+"&description="+description,
				cache: false,
				success: function (JSON) {
					if (JSON.error != '') {
						toastr.error(JSON.error);
						$('.save').prop('disabled', false);
					} else {
						xin_table.ajax.reload(function(){ 
							toastr.success(JSON.result);
						}, true);
						$('.edit-modal-data').modal('toggle');
						$('.save').prop('disabled', false);
					}
				}
			});
		});
	});	
  </script> 
<?php }
?>
