<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['complaint_id']) && $_GET['data']=='complaint'){
	$assigned_ids = explode(',',$complaint_against);
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Complaint</h4>
</div>
<?php if(in_array('21e',role_resource_ids())) {?>
<form class="m-b-1" action="<?php echo site_url("complaints/update").'/'.$complaint_id; ?>" method="post" name="edit_complaint" id="edit_complaint">
<?php } ?>
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $complaint_id;?>">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="employee">Complaint From</label>
          <select name="employee_id" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="Choose an Employee...">
            <option value=""></option>
            <?php foreach($all_employees as $employee) {?>
            <option value="<?php echo $employee->user_id;?>" <?php if($employee->user_id==$complaint_from):?> selected="selected"<?php endif;?>> <?php echo change_fletter_caps($employee->first_name.' '.$employee->middle_name.' '.$employee->last_name);?></option>
            <?php } ?>
          </select>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="title">Complaint Title</label>
              <input class="form-control" placeholder="Complaint Title" name="title" type="text" value="<?php echo $title;?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="complaint_date">Complaint Date</label>
            
			  <div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                    <input class="form-control" placeholder="Complaint Date" name="complaint_date" size="16" type="text"  value="<?php echo $complaint_date;?>" readonly>
                    <span class="input-group-addon" ><span class="glyphicon glyphicon-remove"></span></span>					
                </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="complaint_against">Complaint Against</label>
              <select multiple class="select2" data-plugin="select_hrm" data-placeholder="Select Employee..." name="complaint_against[]">
                <option value=""></option>
                <?php foreach($all_employees as $employee) {?>
                <option value="<?php echo $employee->user_id;?>" <?php if(in_array($employee->user_id,$assigned_ids)):?> selected 
				<?php endif; ?>> <?php echo change_fletter_caps($employee->first_name.' '.$employee->middle_name.' '.$employee->last_name);?></option>
                <?php } ?>
              </select>
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
          <textarea style="min-height:165px;" class="form-control textarea" placeholder="Description" name="description" cols="30" rows="10" id="description2"><?php echo html_entity_decode(stripcslashes($description));?></textarea>
        </div>
      </div>
    </div>
 
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<?php if(in_array('21e',role_resource_ids())) {?>
    <button type="submit" class="btn bg-teal-400">Update</button>
	<?php } ?>
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
			 url : "<?php echo site_url("complaints/complaint_list") ?>",
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
		  height: 165,
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
		$("#edit_complaint").submit(function(e){
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
			//var description = $("#description2").code();
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=1&edit_type=complaint&form="+action,//+"&description="+description,
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
<?php } else if(isset($_GET['jd']) && isset($_GET['complaint_id']) && $_GET['data']=='view_complaint'){
	$assigned_ids = explode(',',$complaint_against);
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">View Complaint</h4>
</div>
<form class="m-b-1">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="employee">Complaint From</label>
          <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php foreach($all_employees as $employee) {?><?php if($complaint_from==$employee->user_id):?><?php echo $employee->first_name.' '.$employee->middle_name.' '.$employee->last_name;?><?php endif;?><?php } ?>">
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="title">Complaint Title</label>
              <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $title;?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="complaint_date">Complaint Date</label>
              <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $complaint_date;?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="complaint_against">Complaint Against</label> <br>
             <div class="text_area_p"> <?php foreach($all_employees as $employee) {?>
			  <?php if(in_array($employee->user_id,$assigned_ids)):?>
			  <?php echo change_fletter_caps($employee->first_name.' '.$employee->middle_name.' '.$employee->last_name); echo '<br>';?> 
			  <?php endif;?><?php } ?></div>
            </div>
          </div>
		  
		      <div class="col-md-12">
        <div class="form-group">
          <label for="status">Status</label>
          <?php if($status=='0'): $c_status = 'Pending';?> <?php endif;?>
          <?php if($status=='1'): $c_status = 'Accepted';?> <?php endif;?>
          <?php if($status=='2'): $c_status = 'Rejected';?> <?php endif;?>
          <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $c_status;?>">
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
