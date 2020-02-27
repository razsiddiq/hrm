<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['job_id']) && $_GET['data']=='job'){
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Job</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("job_post/update").'/'.$job_id; ?>/" method="post" name="edit_job" id="edit_job">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $job_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $job_title;?>">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="title">Job Title</label>
              <input class="form-control" placeholder="Job Title" name="job_title" type="text" value="<?php echo $job_title;?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="job_type">Job Type</label>
              <select class="form-control" name="job_type" data-plugin="select_hrm" data-placeholder="Job Type">
                <option value=""></option>
                <?php foreach($all_job_types as $job_type) {?>
                <option value="<?php echo $job_type->job_type_id?>" <?php if($job_type_id==$job_type->job_type_id):?> selected="selected" <?php endif;?>><?php echo $job_type->type;?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
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
          <div class="col-md-6">
            <div class="form-group">
              <label for="status">Status</label>
              <select class="form-control" name="status" data-plugin="select_hrm" data-placeholder="Status">
                <option value="1" <?php if($status=='1'):?> selected <?php endif;?>>Published</option>
                <option value="2" <?php if($status=='2'):?> selected <?php endif;?>>Un Published</option>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="vacancy">Number of Positions</label>
              <input class="form-control" placeholder="Number of Positions" name="vacancy" type="text" value="<?php echo $job_vacancy;?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="date_of_closing" class="control-label">Date of Closing</label>
              <input class="form-control e_date" placeholder="Date of Closing" readonly="true" name="date_of_closing" type="text" value="<?php echo $date_of_closing;?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="gender">Gender</label>
              <select class="form-control" name="gender" data-plugin="select_hrm" data-placeholder="Gender">
                <option value="Male" <?php if($gender=='Male'):?> selected <?php endif;?>>Male</option>
                <option value="Female" <?php if($gender=='Female'):?> selected <?php endif;?>>Female</option>
                <option value="No Preference" <?php if($gender=='No Preference'):?> selected <?php endif;?>>No Preference</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="experience" class="control-label">Minimum Experience</label>
              <select class="form-control" name="experience" data-plugin="select_hrm" data-placeholder="Minimum Experience">
                <option value="Fresh" <?php if($minimum_experience=='Fresh'):?> selected <?php endif;?>>Fresh</option>
                <option value="1 Year" <?php if($minimum_experience=='1 Year'):?> selected <?php endif;?>>1 Year</option>
                <option value="2 Years" <?php if($minimum_experience=='2 Years'):?> selected <?php endif;?>>2 Years</option>
                <option value="3 Years" <?php if($minimum_experience=='3 Years'):?> selected <?php endif;?>>3 Years</option>
                <option value="4 Years" <?php if($minimum_experience=='4 Years'):?> selected <?php endif;?>>4 Years</option>
                <option value="5 Years" <?php if($minimum_experience=='5 Years'):?> selected <?php endif;?>>5 Years</option>
                <option value="6 Years" <?php if($minimum_experience=='6 Years'):?> selected <?php endif;?>>6 Years</option>
                <option value="7 Years" <?php if($minimum_experience=='7 Years'):?> selected <?php endif;?>>7 Years</option>
                <option value="8 Years" <?php if($minimum_experience=='8 Years'):?> selected <?php endif;?>>8 Years</option>
                <option value="9 Years" <?php if($minimum_experience=='9 Years'):?> selected <?php endif;?>>9 Years</option>
                <option value="10 Years" <?php if($minimum_experience=='10 Years'):?> selected <?php endif;?>>10 Years</option>
                <option value="10+ Years" <?php if($minimum_experience=='10+ Years'):?> selected <?php endif;?>>10+ Years</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="long_description">Long Description</label>
          <textarea class="form-control textarea" placeholder="Long Description" name="long_description" cols="30" rows="15" id="long_description2"><?php echo $long_description;?></textarea>
        </div>
      </div>
    </div>
   <div class="form-group">
      <label for="short_description">Short Description</label>
      <textarea class="form-control" placeholder="Short Description" name="short_description" cols="30" rows="3"><?php echo $short_description;?></textarea>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-define" data-dismiss="modal">Close</button>
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
		    url : "<?php echo site_url("job_post/job_list") ?>",
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
				

		
		$('#long_description2').summernote({
		  height: 180,
		  minHeight: null,
		  maxHeight: null,
		  focus: false
		});
		$('.note-children-container').hide();
		$('.e_date').pickadate({ format: "dd mmmm yyyy"});

		/* Edit data */
		$("#edit_job").submit(function(e){
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
			//var long_description = $("#long_description2").code();
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=1&edit_type=job&form="+action,//+"&long_description="+long_description,
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
