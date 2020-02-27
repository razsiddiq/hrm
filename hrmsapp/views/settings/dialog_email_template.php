<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['template_id']) && $_GET['data']=='email_template'){
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Email Template</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_template").'/'.$template_id; ?>/" method="post" name="update_template" id="update_template">
  <input type="hidden" name="_method" value="EDIT">
  <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="name">Template Name</label>
              <input class="form-control" name="name" type="text" value="<?php echo $name;?>">
            </div>
          </div>
        </div>  
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="subject">Subject</label>
              <input class="form-control" name="subject" type="text" value="<?php echo $subject;?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="status">Status</label>
              <select class="form-control" name="status" data-plugin="select_hrm" data-placeholder="Status">
                <option value=""></option>
                <option value="1" <?php if($status==1):?> selected="selected"<?php endif;?>>Active</option>
                <option value="0" <?php if($status==0):?> selected="selected"<?php endif;?>>Inactive</option>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="message">Message</label>
              <textarea class="form-control" placeholder="Message" name="message" id="summernote"><?php echo $message;?></textarea>

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
function xin_table(){
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
			url : "<?php echo site_url("settings/email_template_list") ?>",
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
    $('.dataTables_length select,.change_country_code').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
 }
	
 $(document).ready(function(){
	$('[data-plugin="xin_select"]').select2($(this).attr('data-options'));

	
	$('#summernote').summernote({
	  height: 160,
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
	
	

	/* Edit*/
	$("#update_template").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=2&edit_type=update_template&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit-modal-data').modal('toggle');
					xin_table();
					toastr.success(JSON.result);					
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});	
</script>
<?php } ?>
