<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['expense_id']) && $_GET['data']=='expense'){
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_expense');?></h4>
</div>
<form class="m-b-1" action="<?php echo site_url("expense/update").'/'.$expense_id; ?>" method="post" name="edit_expense" id="edit_expense">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $expense_id;?>">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="employee"><?php echo $this->lang->line('xin_expense_type');?></label>
          <select name="expense_type" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_choose_expense_type');?>...">
            <option value=""></option>
            <?php foreach($all_expense_types as $expense_type) {?>
            <option value="<?php echo $expense_type->type_id;?>" <?php if($expense_type->type_id==$expense_type_id):?> selected <?php endif; ?>><?php echo $expense_type->type_name;?></option>
            <?php } ?>
          </select>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="purchase_date"><?php echo $this->lang->line('xin_purchase_date');?></label>
              <input class="form-control edate" placeholder="<?php echo $this->lang->line('xin_purchase_date');?>" readonly="true" name="purchase_date" type="text" value="<?php echo $purchase_date;?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="amount"><?php echo $this->lang->line('xin_amount');?></label>
              <input class="form-control" placeholder="<?php echo $this->lang->line('xin_amount');?>" name="amount" type="text" value="<?php echo $amount;?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="gift"><?php echo $this->lang->line('xin_purchased_by');?></label>
              <select name="employee_id" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_choose_an_employee');?>...">
                <option value=""></option>
                <?php foreach($all_employees as $employee) {?>
                <option value="<?php echo $employee->user_id;?>" <?php if($employee->user_id==$employee_id):?> selected <?php endif; ?>> <?php echo change_fletter_caps($employee->first_name);?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>
		 <div class="row">
		  <div class="col-md-12">
        <div class="form-group">
          <label for="status"><?php echo $this->lang->line('dashboard_xin_status');?></label>
          <select name="status" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('dashboard_xin_status');?>...">
            <option value="0" <?php if($status=='0'):?> selected <?php endif; ?>>Pending</option>
            <option value="1" <?php if($status=='1'):?> selected <?php endif; ?>>Approved</option>
            <option value="2" <?php if($status=='2'):?> selected <?php endif; ?> >Cancel</option>
          </select>
        </div>
      </div>
	  </div>
	  
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="description"><?php echo $this->lang->line('xin_remarks');?></label>
          <textarea class="form-control textarea" name="remarks" cols="25" rows="6" id="description2"><?php echo html_entity_decode(stripcslashes($remarks));?></textarea>
        </div>
      </div>
    </div>
    <div class="row">
    
      <div class="col-md-6">
        <div class='form-group'>
          <h6><?php echo $this->lang->line('xin_bill_copy');?></h6>
          <!--<span class="btn bg-teal-400 btn-file">
          <i class="icon-file-plus"></i> Browse <input type="file" name="bill_copy" id="bill_copy">
          </span>-->
		  
		  <input type="file" class="file-input1" name="bill_copy">
			 <span class="help-block"><?php echo $this->lang->line('xin_company_file_type');?></span>
			 
          <br>
          
          <?php if($billcopy_file!='' && $billcopy_file!='no file') {?>
          <br />
          <a class="btn bg-teal-400" href="<?php echo site_url("download?type=expense&filename=".$billcopy_file."") ?>"><i class="icon-download mr-10"></i><?php echo $this->lang->line('xin_download_file');?></a>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
    <button type="submit" class="btn bg-teal-400"><?php echo $this->lang->line('xin_update');?></button>
  </div>
</form>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/fileupload.js"></script>
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
			url : "<?php echo site_url("expense/expense_list") ?>",
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
		  height: 140,
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
		$('.edate').pickadate({ format: "dd mmmm yyyy"});

		/* Edit data */
		$("#edit_expense").submit(function(e){
			var fd = new FormData(this);
			var obj = $(this), action = obj.attr('name');
		    //var description = $("#description2").code();
			fd.append("is_ajax", 2);
			fd.append("edit_type", 'expense');
			//fd.append("description", description);
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
				success: function(JSON)
				{
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
				},
				error: function() 
				{
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} 	        
		   });
		});
	});	
  </script>
<?php } else if(isset($_GET['jd']) && isset($_GET['expense_id']) && $_GET['data']=='view_expense'){
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_view_expense');?></h4>
</div>
<form class="m-b-1">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="type"><?php echo $this->lang->line('xin_expense_type');?></label>
          <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php foreach($all_expense_types as $expense_type) {?><?php if($expense_type_id==$expense_type->type_id):?><?php echo $expense_type->type_name;?><?php endif;?><?php } ?>">
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="purchase_date"><?php echo $this->lang->line('xin_purchase_date');?></label>
              <input class="form-control edate" readonly="readonly" style="border:0" type="text" value="<?php echo $purchase_date;?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="amount"><?php echo $this->lang->line('xin_amount');?></label>
              <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $amount;?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="xin_purchased_by"><?php echo $this->lang->line('xin_purchased_by');?></label>
              <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php foreach($all_employees as $employee) {?><?php if($employee_id==$employee->user_id):?><?php echo change_fletter_caps($employee->first_name);?><?php endif;?><?php } ?>">
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="description"><?php echo $this->lang->line('xin_remarks');?></label>
          <div class="text_area_p"><div class="embed-responsive embed-responsive-4by3"><?php echo html_entity_decode(stripcslashes($remarks));?></div></div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="status"><?php echo $this->lang->line('dashboard_xin_status');?></label>
          <?php if($status=='0'): $e_status = 'Pending'; ?>  <?php endif; ?>
          <?php if($status=='1'): $e_status = 'Approved';?>  <?php endif; ?>
          <?php if($status=='2'): $e_status = 'Cancel';?>  <?php endif; ?>
          <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $e_status;?>">
        </div>
      </div>
      <div class="col-md-6">
        <div class='form-group'>
          <h6><?php echo $this->lang->line('xin_bill_copy');?></h6>
          <?php if($billcopy_file!='' && $billcopy_file!='no file') {?>

		  <a class="btn bg-teal-400" href="<?php echo site_url("download?type=expense&filename=".$billcopy_file."") ?>"><i class="icon-download mr-10"></i><?php echo $this->lang->line('xin_download_file');?></a>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
  </div>
</form>
<?php }
?>
