<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['trainer_id']) && $_GET['data']=='trainer'){
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Trainer</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("trainers/update").'/'.$trainer_id; ?>/" method="post" name="edit_trainer" id="edit_trainer">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $trainer_id;?>">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="first_name">First Name</label>
              <input class="form-control" placeholder="First Name" name="first_name" type="text" value="<?php echo $first_name;?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="last_name" class="control-label">Last Name</label>
              <input class="form-control" placeholder="Last Name" name="last_name" type="text" value="<?php echo $last_name;?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="contact_number">Contact Number</label>
            
  <?php $contact_number=@explode('-',@$contact_number);?>
    			<div class="clearfix"></div>
				<div class="input-group">
												<span class="input-group-addon">
						   <select class="form-control change_country_code1" name="country_code" >
			  <?php foreach($phone_numbers_code as $keys=>$phone_code){?>
			  <option <?php if($keys==@$contact_number[0]){echo 'selected';}?> value="<?php echo $keys; ?>-" rel="<?php echo $phone_code;?>"><?php echo $keys; ?></option>
			  <?php } ?>
			  </select></span>
                        <input class="form-control" value="<?php echo @$contact_number[1];?>" placeholder="Contact Number" name="contact_number" type="text" pattern="\d*" maxlength="9" title="Should be Numbers only">
			</div>
			
			</div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="email" class="control-label">Email</label>
              <input class="form-control" placeholder="Email" name="email" type="text" value="<?php echo $email;?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="designation">Designation</label>
              <select class="form-control" name="designation_id" data-plugin="select_hrm" data-placeholder="Designation">
                <option value=""></option>
                <?php foreach($all_designations as $designation) {?>
                <option value="<?php echo $designation->designation_id?>" <?php if($designation_id==$designation->designation_id):?> selected="selected" <?php endif;?>><?php echo $designation->designation_name?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="expertise">Expertise</label>
          <textarea class="form-control textarea" placeholder="Expertise" name="expertise" cols="30" rows="5" id="expertise2"><?php echo html_entity_decode(stripcslashes($expertise));?></textarea>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="address">Address</label>
      <textarea class="form-control" placeholder="Address" name="address" cols="30" rows="3" id="address"><?php echo $address;?></textarea>
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
		    url : "<?php echo site_url("trainers/trainer_list") ?>",
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
    $('.dataTables_length select,.change_country_code1').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
	
	$('[data-plugin="xin_select"]').select2($(this).attr('data-options'));
    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
 
			$(".change_country_code1").change(function () {
	var rel=$(this).find(':selected').attr('rel');
	$(this).parent().next('input').val('');
    $(this).parent().next('input').attr('maxlength',rel);
});	


		$('#expertise2').summernote({
		  height: 133,
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
		$("#edit_trainer").submit(function(e){
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
			//var expertise = $("#expertise2").code();
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=1&edit_type=trainer&form="+action,//+"&expertise="+expertise,
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
<?php } else if(isset($_GET['jd']) && isset($_GET['trainer_id']) && $_GET['data']=='view_trainer'){
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">View Trainer</h4>
</div>
<form class="m-b-1">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="first_name">First Name</label>
              <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $first_name;?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="last_name" class="control-label">Last Name</label>
              <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $last_name;?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="contact_number">Contact Number</label>
              <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $contact_number;?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="email" class="control-label">Email</label>
              <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $email;?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="designation">Designation</label>
              <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php foreach($all_designations as $designation) {?><?php if($designation_id==$designation->designation_id):?><?php echo $designation->designation_name;?><?php endif;?><?php } ?>">
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="expertise">Expertise</label><br />
         <div class="text_area_p"><div class="embed-responsive embed-responsive-4by3"><?php echo html_entity_decode(stripcslashes($expertise));?> </div></div>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="address">Address</label><br />
      <div class="text_area_p"><?php echo $address;?></div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  </div>
</form>
<?php }
?>
