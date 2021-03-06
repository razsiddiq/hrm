<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['warning_id']) && $_GET['data']=='warning'){
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Warning</h4>
</div>
<?php if(in_array('22e',role_resource_ids())) {?>
<form class="m-b-1" action="<?php echo site_url("warning/update").'/'.$warning_id; ?>" method="post" name="edit_warning" id="edit_warning">
<?php } ?>


	   <input value="<?php echo $warning_by;?>>" name="warning_by" type="hidden">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $warning_id;?>">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="warning_to">Warning To</label>
          <select name="warning_to" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="Choose an Employee...">
            <option value=""></option>
            <?php foreach($all_employees as $employee) {?>
            <option value="<?php echo $employee->user_id;?>" <?php if($employee->user_id==$warning_to):?> selected="selected"<?php endif;?>> <?php echo change_fletter_caps($employee->first_name.' '.$employee->middle_name.' '.$employee->last_name);?></option>
            <?php } ?>
          </select>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="type">Type of Warning</label>
              <select class="select2" data-plugin="select_hrm" data-placeholder="Type of Warning" name="type">
                <option value=""></option>
                <?php foreach($all_warning_types as $warning_type) {?>
                <option value="<?php echo $warning_type->type_id?>" <?php if($warning_type->type_id==$warning_type_id):?> selected="selected"<?php endif;?>><?php echo $warning_type->type_name;?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="subject">Subject</label>
              <input class="form-control" placeholder="Subject" name="subject" type="text" value="<?php echo $subject;?>">
            </div>
          </div>
        </div>
        <div class="row">
 
          <div class="col-md-12">
            <div class="form-group">
              <label for="warning_date">Warning Date</label>
               <div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                    <input class="form-control" placeholder="Warning Date" name="warning_date" size="16" type="text"  value="<?php echo $warning_date;?>" readonly>
                    <span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>					
                </div>
				
            </div>
          </div>
		  
		     <div class="col-md-12">
        <div class="form-group">
          <label for="status">Status</label>
          <select name="status" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="Select Status...">
            <option value="0" <?php if($status=='0'):?> selected <?php endif; ?>>Pending</option>
            <option value="1" <?php if($status=='1'):?> selected <?php endif; ?>>Accepted</option>
            <option value="2" <?php if($status=='2'):?> selected <?php endif; ?>>Rejected</option>
          </select>
        </div>
      </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="description">Description</label>
          <textarea class="form-control textarea" placeholder="Description" name="description" cols="30" rows="10" id="description2"><?php echo html_entity_decode(stripcslashes($description));?></textarea>
        </div>
      </div>
    </div>

  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<?php if(in_array('22e',role_resource_ids())) {?>
    <button type="submit" class="btn bg-teal-400">Update</button><?php } ?>
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
			url : "<?php echo site_url("warning/warning_list") ?>",
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
	
    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));	

		$('#description2').summernote({
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

		/* Edit data */
		$("#edit_warning").submit(function(e){
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
			//var description = $("#description2").code();
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=1&edit_type=warning&form="+action,//+"&description="+description,
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
<?php } else if(isset($_GET['jd']) && isset($_GET['warning_id']) && $_GET['data']=='view_warning'){
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">View Warning</h4>
</div>
<form class="m-b-1">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="warning_to">Warning To</label>
          <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php foreach($all_employees as $employee) {?><?php if($warning_to==$employee->user_id):?><?php echo change_fletter_caps($employee->first_name.' '.$employee->middle_name.' '.$employee->last_name);?><?php endif;?><?php } ?>">
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="type">Type of Warning</label>
              <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php foreach($all_warning_types as $warning_type) {?><?php if($warning_type_id==$warning_type->type_id):?><?php echo $warning_type->type_name;?><?php endif;?><?php } ?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="subject">Subject</label>
              <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $subject;?>">
            </div>
          </div>
        </div>
        <div class="row">
  
          <div class="col-md-12">
            <div class="form-group">
              <label for="warning_date">Warning Date</label>
              <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $warning_date;?>">
            </div>
          </div>
		  
		      <div class="col-md-12">
        <div class="form-group">
          <label for="status">Status</label>
          <?php if($status=='0'): $w_status = 'Pending';?>  <?php endif; ?>
          <?php if($status=='1'): $w_status = 'Accepted';?>  <?php endif; ?>
          <?php if($status=='2'): $w_status = 'Rejected';?>  <?php endif; ?>
          <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $w_status;?>">
        </div>
      </div>
	  
	  
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="description">Description</label><br />
          <div class="text_area_p"><div class="embed-responsive embed-responsive-4by3"><?php echo html_entity_decode(stripcslashes($description));?></div></div>
        </div>
      </div>
    </div>

  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
  </div>
</form>
<?php }
?>
