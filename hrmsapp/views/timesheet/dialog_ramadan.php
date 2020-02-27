<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['ramadan_schedule_id']) && $_GET['data']=='ramadan_schedule'){
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Ramadan Schedule</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("timesheet/edit_ramadan").'/'.$_GET['ramadan_schedule_id']; ?>/" method="post" name="edit_ramadan" id="edit_ramadan">
  <input type="hidden" name="_method" value="EDIT">
  <div class="modal-body">
    <div class="row">
	
	<div class="col-md-4">
	<div class="form-group">
          <label for="title">Event Name</label>
          <input class="form-control" placeholder="Event Name" name="event_name" type="text" value="<?php echo $event_name;?>">
        </div>
	</div>
	
	<div class="col-md-4">
	  <div class="form-group">
              <label for="designation" class="control-label">Status</label>
              <select name="is_publish" class="select2" data-plugin="select_hrm" data-placeholder="Choose Status...">
                <option value="1" <?php if($is_publish=='1') { ?> selected <?php } ?>>Published</option>
                <option value="0" <?php if($is_publish=='0') { ?> selected <?php } ?>>Un Published</option>
              </select>
            </div>
	</div>
	
		  <div class="col-md-4">
		<div class="form-group">   
<label class="control-label" for="tooltip">Reduced Hour(s) <span data-toggle="tooltip" data-placement="top" title="" data-original-title="When ramadan time this should be deduct from the employee total working scheduled hours."><i class="icon-bubble-notification" style="margin-top: -10px;"></i></span></label>		   
 <input class="form-control timepicker1" placeholder="Reduced Hour(s)" readonly name="reduced_hours" type="text" value="<?php echo $reduced_hours;?>">		   
        
          </div>
		</div>
	
	 <div class="col-md-6">
		   <div class="form-group">
		   <label for="name">Country</label>
		  <select class="form-control" name="country" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_country');?>">
                      <?php foreach($this->Location_model->all_locations_bycountry() as $country) {
						  if($country->country_id==$country_id) {
						  ?>
                      <option   value="<?php echo $country->country_id;?>"> <?php echo $country->country_name;?></option>
                      <?php } }?>
                    </select>
					</div>
	</div>
	 <div class="col-md-6">
		       <div class="form-group">
              <label for="start_date">Select Date</label>
               <input type="text" class="form-control daterange-basic1" name="select_date" value="<?php echo format_date('d F Y',$start_date);?> - <?php echo format_date('d F Y',$end_date);?>" readonly> 
            </div>
		  </div>
  
		  

		   
  <div class="col-md-12">
        <div class="form-group">
          <label for="description">Description</label>
          <textarea class="form-control textarea" placeholder="Description" name="description" cols="30" rows="15" id="description2"><?php echo html_entity_decode(stripcslashes($description));?></textarea>
        </div>
      </div>

    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<?php if($is_publish==0){?>
    <button type="submit" class="btn bg-teal-400">Save</button>
	<?php } ?>
  </div>
</form>
<style>
.daterangepicker{z-index: 9999 !important;}
</style>
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
				url : "<?php echo site_url("timesheet/ramadan_list") ?>",
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
	
	
$('.clockpicker').clockpicker();
var input = $('.timepicker1').clockpicker({
	placement: 'bottom',
	align: 'left',
	autoclose: true,
	'default': 'now'
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
	
	 $('.daterange-basic1').daterangepicker({
	    format: "dd mmmm yyyy",
        applyClass: 'bg-slate-600',
        cancelClass: 'btn-default',
		
    });
	
	// Date
	/*$('.mdate').pickadate({ format: "dd mmmm yyyy",
        labelMonthNext: 'Go to the next month',
        labelMonthPrev: 'Go to the previous month',
        labelMonthSelect: 'Pick a month from the dropdown',
        labelYearSelect: 'Pick a year from the dropdown',
        selectMonths: true,
        selectYears: 100});*/
	/* Edit*/
	$("#edit_ramadan").submit(function(e){
	//var description = $("#description2").code();
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=2&edit_type=ramadan&form="+action,//+"&description="+description,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit-modal-data').modal('toggle');
					$('#xin_table').dataTable().api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});	
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['ramadan_schedule_id']) && $_GET['data']=='view_ramadan'){
?>
<script>
$(document).ready(function(){
$('[data-toggle="tooltip"]').tooltip();
});		
</script>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">View Ramadan Schedule</h4>
</div>
<form class="m-b-1">
  <div class="modal-body">
  
     <div class="row">
	
	<div class="col-md-4">
	<div class="form-group">
          <label for="title">Event Name</label>
          <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $event_name;?>">
        </div>
	</div>
	
	<div class="col-md-4">
	  <div class="form-group">
              <label for="designation" class="control-label">Status</label>
              <?php if($is_publish=='1'): $status = 'Published';?>  <?php endif; ?>
              <?php if($is_publish=='0'): $status = 'Un Published';?>  <?php endif; ?>
              <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $status;?>">
            </div>
	</div>
	
	 <div class="col-md-4">
		<div class="form-group">   
<label class="control-label" for="tooltip">Reduced Hour(s) <span data-toggle="tooltip" data-placement="top" title="" data-original-title="When ramadan time this should be deduct from the employee total working scheduled hours."><i class="icon-bubble-notification" style="margin-top: -10px;"></i></span></label>		   
 <input class="form-control" placeholder="Reduced Hour(s)" readonly name="reduced_hours" type="text" value="<?php echo $reduced_hours;?>">		   
        
          </div>
		</div>
	<div class="col-md-6">
	  <div class="form-group">
              <label for="designation" class="control-label">Country</label>
         
                      <?php foreach($this->Location_model->all_locations_bycountry() as $country) {
						  if($country->country_id==$country_id) {
						  ?>
                      <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $country->country_name;?>">
                      <?php } }?>
                  

		  </div>
	</div>
	
	 <div class="col-md-6">
		       <div class="form-group">
              <label for="start_date">Select Date</label>
               <input type="text" class="form-control" name="select_date" value="<?php echo format_date('d F Y',$start_date);?> - <?php echo format_date('d F Y',$end_date);?>" readonly> 
            </div>
		  </div>
	
  <div class="col-md-12">
       <div class="form-group">
          <label for="description">Description</label><br />
          <div class="text_area_p"><?php echo html_entity_decode(stripcslashes($description));?></div>
        </div>
      </div>

    </div>
 
 
 

  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
  </div>
</form>

<?php } ?>
