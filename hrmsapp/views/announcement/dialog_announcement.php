<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['announcement_id']) && $_GET['data']=='announcement'){
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_announcement');?></h4>
</div>
<form class="m-b-1" action="<?php echo site_url("announcement/update").'/'.$announcement_id; ?>" method="post" name="edit_announcement" id="edit_announcement">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $announcement_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $title;?>">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="title"><?php echo $this->lang->line('xin_title');?></label>
          <input class="form-control" placeholder="<?php echo $this->lang->line('xin_title');?>" name="title" type="text" value="<?php echo $title;?>">
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="start_date"><?php echo $this->lang->line('xin_start_date');?></label>
              <input class="form-control d_date" name="start_date_modal" readonly="true" type="text" value="<?php echo $start_date;?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="end_date"><?php echo $this->lang->line('xin_end_date');?></label>
              <input class="form-control d_date" name="end_date_modal" readonly="true" type="text" value="<?php echo $end_date;?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="designation" class="control-label"><?php echo $this->lang->line('module_company_title');?></label>
              <select class="form-control" name="company_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('module_company_title');?>">
                <option value=""></option>
                <?php foreach($get_all_companies as $company) {?>
                <option value="<?php echo $company->company_id?>" <?php if($company->company_id==$company_id):?> selected <?php endif;?>><?php echo $company->name?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="designation" class="control-label"><?php echo $this->lang->line('xin_location');?></label>
              <select class="form-control" name="location_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_location');?>">
                <option value=""></option>
                <?php foreach($all_office_locations as $location) {?>
                <option value="<?php echo $location->location_id?>" <?php if($location->location_id==$location_id):?> selected <?php endif;?>><?php echo $location->location_name?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
			
			<?php		
			$count_all_dep=count($all_departments);		
		    $count_all_dbs=count(explode(',',$department_id));		
			if($count_all_dep == $count_all_dbs){
				$s_all='checked';
			}else{
				$s_all='';
			}
			
			
			?>
              <label for="department" class="control-label"><?php echo $this->lang->line('xin_department');?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input <?php echo $s_all;?> type="checkbox" class="styled" id="checkbox_depart1">&nbsp;&nbsp;Select All</label>
              <select multiple class="form-control" name="department_id[] " data-plugin="select_hrm" data-placeholder="All Department<?php //echo $this->lang->line('xin_department');?>" id="depart_id1">
             
                <?php foreach($all_departments as $department) {					
					$departments=explode(',',$department_id);					
					if(in_array($department->department_id,$departments)){
						$selc='selected';
					}else{
						$selc='';
					}
					?>
                <option value="<?php echo $department->department_id?>" <?php echo $selc;//if($department->department_id==$department_id): selected?>  <?php //endif;?>><?php echo $department->department_name?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>
      </div> 


      <div class="col-md-6">
        <div class="form-group">
          <label for="description"><?php echo $this->lang->line('xin_description');?></label>
          <textarea class="form-control textarea" placeholder="<?php echo $this->lang->line('xin_description');?>" name="description" cols="30" rows="15" id="description2">
		  <?php echo stripslashes($description);?></textarea>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="summary"><?php echo $this->lang->line('xin_summary');?></label>
      <textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_summary');?>" name="summary" cols="30" rows="3" id="summary"><?php echo $summary;?></textarea>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
    <button type="submit" class="btn bg-teal-400"><?php echo $this->lang->line('xin_update');?></button>
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
	

	
	$("#checkbox_depart1").click(function(){
    if($("#checkbox_depart1").is(':checked') ){	
        $("#depart_id1 > option").prop("selected","selected");
        $("#depart_id1").trigger("change");
    }else{	
        $("#depart_id1 > option").removeAttr("selected");
        $("#depart_id1").val(0).trigger("change");
     }
});

 $(".styled").uniform({
        radioClass: 'choice'
    });
	
	
	 var xin_table = $('#xin_table').DataTable({
		"bDestroy": true,
		"ajax": {
			url : "<?php echo site_url("announcement/announcement_list") ?>",
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
	
	$('[data-plugin="xin_select"]').select2($(this).attr('data-options'));
	
	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));

		
		$('#description2').summernote({
		  height: 151,
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
		$('.d_date').pickadate();

		/* Edit data */
		$("#edit_announcement").submit(function(e){
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
		//	var description = $("#description2").code();
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=1&edit_type=announcement&form="+action,//+'&description='+description,
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
<?php } else if(isset($_GET['jd']) && isset($_GET['announcement_id']) && $_GET['data']=='view_announcement'){
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_view_announcement');?></h4>
</div>
<form class="m-b-1">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="title"><?php echo $this->lang->line('xin_title');?></label>
          <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $title;?>">
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="start_date"><?php echo $this->lang->line('xin_error_start_date');?></label>
              <input class="form-control d_date" readonly="readonly" style="border:0" type="text" value="<?php echo $start_date;?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="end_date"><?php echo $this->lang->line('xin_error_end_date');?></label>
              <input class="form-control d_date" readonly="readonly" style="border:0" type="text" value="<?php echo $end_date;?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="designation" class="control-label"><?php echo $this->lang->line('module_company_title');?></label>
              <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php foreach($get_all_companies as $company) {?><?php if($company->company_id==$company_id):?> <?php echo $company->name?> <?php endif;?><?php } ?>" />
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="designation" class="control-label"><?php echo $this->lang->line('xin_location');?></label>
              <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php foreach($all_office_locations as $location) {?><?php if($location->location_id==$location_id):?> <?php echo $location->location_name?> <?php endif;?><?php } ?>" />
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="designation" class="control-label"><?php echo $this->lang->line('xin_department');?></label>
             <select multiple class="form-control" name="department_id[] " data-plugin="select_hrm" data-placeholder="All Department<?php //echo $this->lang->line('xin_department');?>">
                <option value=""></option>
                <?php foreach($all_departments as $department) {					
					$departments=explode(',',$department_id);					
					if(in_array($department->department_id,$departments)){
						$selc='selected';
					}else{
						$selc='';
					}
					?>
                <option value="<?php echo $department->department_id?>" <?php echo $selc;//if($department->department_id==$department_id): selected?>  <?php //endif;?>><?php echo $department->department_name?></option>
                <?php } ?>
              </select> </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="description"><?php echo $this->lang->line('xin_description');?></label><br />
          <div class="text_area_p"><div class="embed-responsive embed-responsive-4by3"><?php echo html_entity_decode(stripcslashes($description));?></div>
        </div>
      </div>
    </div>
	
	<div class="col-md-12">
    <div class="form-group">
      <label for="summary"><?php echo $this->lang->line('xin_summary');?></label><br />
      <div class="text_area_p"><?php echo stripslashes($summary);?></div>
    </div>
	</div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
  </div>
</form>
<script>

$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
		$('[data-plugin="select_hrm"]').select2({ width:'100%' });	
</script>
<?php }
?>
