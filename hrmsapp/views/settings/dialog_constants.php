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
 });
</script>
<?php

if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_medical_card_type' && $_GET['type']=='ed_medical_card_type'){

$row = $this->Xin_model->read_document_type($_GET['field_id'],'medical_card_type');
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Medical Card Type</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_medical_card_type") ?>/<?php echo $row[0]->type_id;?>/" method="post" name="ed_medical_card_type_info" id="ed_medical_card_type_info">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $row[0]->type_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $row[0]->type_name;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="name" class="form-control-label">Medical Card Type:</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Medical Card Type" value="<?php echo $row[0]->type_name;?>">
      </div>
			  <div class="form-group">
              <label for="name" class="form-control-label">Number Of Dependant:</label>

				<select name="no_of_dependant" data-plugin="select_hrm" data-placeholder="Choose No Of Dependant..." class="form-control">
				<option value="0" <?php if($row[0]->no_of_dependant==0){echo 'selected';}?>>0 Dependant</option>
				<option value="1" <?php if($row[0]->no_of_dependant==1){echo 'selected';}?>>1 Dependant</option>
				<option value="2" <?php if($row[0]->no_of_dependant==2){echo 'selected';}?>>2 Dependant</option>
				<option value="3" <?php if($row[0]->no_of_dependant==3){echo 'selected';}?>>3 Dependant</option>
				<option value="4" <?php if($row[0]->no_of_dependant==4){echo 'selected';}?>>4 Dependant</option>
				<option value="5" <?php if($row[0]->no_of_dependant==5){echo 'selected';}?>>5 Dependant</option>
				<option value="6" <?php if($row[0]->no_of_dependant==6){echo 'selected';}?>>6 Dependant</option>
				<option value="7" <?php if($row[0]->no_of_dependant==7){echo 'selected';}?>>7 Dependant</option>
				<option value="8" <?php if($row[0]->no_of_dependant==8){echo 'selected';}?>>8 Dependant</option>
				<option value="9" <?php if($row[0]->no_of_dependant==9){echo 'selected';}?>>9 Dependant</option>
				<option value="10 <?php if($row[0]->no_of_dependant==10){echo 'selected';}?>">10 Dependant</option>
				</select>

              </div>



    </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn bg-teal-400 pull-right">Update</button>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){

var xin_table_medical_card_type = $('#xin_table_medical_card_type').DataTable({
		"bDestroy": true,
		"ajax": {
			  url : "<?php echo site_url("settings/medical_card_type_list") ?>",
			type : 'GET'
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




	/* Edit data */
	$("#ed_medical_card_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=21&type=edit_record&data=ed_medical_card_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit_setting_datail').modal('toggle');
					xin_table_medical_card_type.ajax.reload(function(){
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_visa_under' && $_GET['type']=='ed_visa_under'){
$row = $this->Xin_model->read_document_type($_GET['field_id'],'visa_under');
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Visa Under</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_visa_under") ?>/<?php echo $row[0]->type_id;?>/" method="post" name="ed_visa_under_info" id="ed_visa_under_info">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $row[0]->type_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $row[0]->type_name;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="name" class="form-control-label">Visa Under:</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Visa Under" value="<?php echo $row[0]->type_name;?>">
      </div>
    </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn bg-teal-400 pull-right">Update</button>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	// On page load: datatable

	var xin_table_visa_under = $('#xin_table_visa_under').DataTable({
		"bDestroy": true,
		"ajax": {
			  url : "<?php echo site_url("settings/visa_under_list") ?>",
			type : 'GET'
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

	/* Edit data */
	$("#ed_visa_under_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=21&type=edit_record&data=ed_visa_under_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit_setting_datail').modal('toggle');
					xin_table_visa_under.ajax.reload(function(){
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_document_type' && $_GET['type']=='ed_document_type'){
$row = $this->Xin_model->read_document_type($_GET['field_id'],'document_type');

$all_countries = $this->Xin_model->get_countries();
$support_country_id=explode(',',$row[0]->type_code);
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Document Type</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_document_type") ?>/<?php echo $row[0]->type_id;?>/" method="post" name="ed_document_type_info" id="ed_document_type_info">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $row[0]->type_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $row[0]->type_name;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="name" class="form-control-label">Document Type:</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Document Type" value="<?php echo $row[0]->type_name;?>">
      </div>
	  <div class="form-group">
                <label for="name">Supported Countries (For Reports Filter)</label>


				 <select name="country_doc[]"  multiple class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_country');?>">
            <option value=""></option>
             <?php foreach($all_countries as $country) {
				 if(in_array($country->country_id,$support_country_id))
					{
					$selected="selected";
					}else{$selected='';}

				 ?>
                    <option <?php echo $selected;?> value="<?php echo $country->country_id;?>"> <?php echo $country->country_name;?></option>
                    <?php } ?>
          </select>

              </div>

    </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn bg-teal-400 pull-right">Update</button>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	// On page load: datatable


	var xin_table_document_type = $('#xin_table_document_type').DataTable({
		"bDestroy": true,
		"ajax": {
			  url : "<?php echo site_url("settings/document_type_list") ?>",
			type : 'GET'
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

	/* Edit data */
	$("#ed_document_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=21&type=edit_record&data=ed_document_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit_setting_datail').modal('toggle');
					xin_table_document_type.ajax.reload(function(){
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_contract_type' && $_GET['type']=='ed_contract_type'){
$row = $this->Xin_model->read_document_type($_GET['field_id'],'contract_type');
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Contract Type</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_contract_type") ?>/<?php echo $row[0]->type_id;?>/" method="post" name="ed_contract_type_info" id="ed_contract_type_info">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $row[0]->type_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $row[0]->type_name;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="name" class="form-control-label">Contract Type:</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Contract Type" value="<?php echo $row[0]->type_name?>">
      </div>
    </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn bg-teal-400">Update</button>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	// On page load: datatable

	var xin_table_contract_type = $('#xin_table_contract_type').DataTable({
		"bDestroy": true,
		"ajax": {
			url : "<?php echo site_url("settings/contract_type_list") ?>",
			type : 'GET'
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

	/*var xin_table_contract_type = $('#xin_table_contract_type').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"iDisplayLength": 5,
		"aLengthMenu": [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],
		"ajax": {
            url : "<?php echo site_url("settings/contract_type_list") ?>",
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();
		}
	});*/

	/* Edit data */
	$("#ed_contract_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=22&type=edit_record&data=ed_contract_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit_setting_datail').modal('toggle');
					xin_table_contract_type.ajax.reload(function(){
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_payment_method' && $_GET['type']=='ed_payment_method'){
$row = $this->Xin_model->read_document_type($_GET['field_id'],'payment_method');
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Payment Method</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_payment_method") ?>/<?php echo $row[0]->type_id;?>/" method="post" name="ed_payment_method_info" id="ed_payment_method_info">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $row[0]->type_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $row[0]->type_name;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="name" class="form-control-label">Payment Method:</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Payment Method" value="<?php echo $row[0]->type_name;?>">
      </div>
    </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn bg-teal-400 pull-right">Update</button>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	// On page load: datatable


	var xin_table_payment_method = $('#xin_table_payment_method').DataTable({
		"bDestroy": true,
		"ajax": {
			  url : "<?php echo site_url("settings/payment_method_list") ?>",
			type : 'GET'
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

	/* Edit data */
	$("#ed_payment_method_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=23&type=edit_record&data=ed_payment_method_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit_setting_datail').modal('toggle');
					xin_table_payment_method.ajax.reload(function(){
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_education_level' && $_GET['type']=='ed_education_level'){

$row = $this->Xin_model->read_document_type($_GET['field_id'],'education_level');
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Education Level</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_education_level") ?>/<?php echo $row[0]->type_id;?>/" method="post" name="ed_education_level_info" id="ed_education_level_info">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $row[0]->type_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $row[0]->type_name;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="name" class="form-control-label">Education Level:</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Education Level" value="<?php echo $row[0]->type_name;?>">
      </div>
    </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn bg-teal-400 pull-right">Update</button>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	// On page load: datatable
	var xin_table_education_level = $('#xin_table_education_level').DataTable({
		"bDestroy": true,
		"ajax": {
			  url : "<?php echo site_url("settings/education_level_list") ?>",
			type : 'GET'
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


	/* Edit data */
	$("#ed_education_level_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=24&type=edit_record&data=ed_education_level_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit_setting_datail').modal('toggle');
					xin_table_education_level.ajax.reload(function(){
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_qualification_language' && $_GET['type']=='ed_qualification_language'){

$row = $this->Xin_model->read_document_type($_GET['field_id'],'qualification_language');
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Language</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_qualification_language") ?>/<?php echo $row[0]->type_id;?>/" method="post" name="ed_qualification_language_info" id="ed_qualification_language_info">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $row[0]->type_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $row[0]->type_name;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="name" class="form-control-label">Language:</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Language" value="<?php echo $row[0]->type_name;?>">
      </div>
    </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn bg-teal-400 pull-right">Update</button>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	// On page load: datatable

	var xin_table_qualification_language = $('#xin_table_qualification_language').DataTable({
		"bDestroy": true,
		"ajax": {
			  url : "<?php echo site_url("settings/qualification_language_list") ?>",
			type : 'GET'
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



	/* Edit data */
	$("#ed_qualification_language_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=25&type=edit_record&data=ed_qualification_language_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit_setting_datail').modal('toggle');
					xin_table_qualification_language.ajax.reload(function(){
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_qualification_skill' && $_GET['type']=='ed_qualification_skill'){
$row = $this->Xin_model->read_document_type($_GET['field_id'],'qualification_skill');
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Skill</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_qualification_skill") ?>/<?php echo $row[0]->type_id;?>/" method="post" name="ed_qualification_skill_info" id="ed_qualification_skill_info">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $row[0]->type_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $row[0]->type_name;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="name" class="form-control-label">Skill:</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Skill" value="<?php echo $row[0]->type_name;?>">
      </div>
    </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn bg-teal-400 pull-right">Update</button>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	// On page load: datatable

	var xin_table_qualification_skill = $('#xin_table_qualification_skill').DataTable({
		"bDestroy": true,
		"ajax": {
			  url : "<?php echo site_url("settings/qualification_skill_list") ?>",
			type : 'GET'
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


	/* Edit data */
	$("#ed_qualification_skill_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=26&type=edit_record&data=ed_qualification_skill_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit_setting_datail').modal('toggle');
					xin_table_qualification_skill.ajax.reload(function(){
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_award_type' && $_GET['type']=='ed_award_type'){
$row = $this->Xin_model->read_document_type($_GET['field_id'],'award_type');
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Award Type</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_award_type") ?>/<?php echo $row[0]->type_id;?>/" method="post" name="ed_award_type_info" id="ed_award_type_info">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $row[0]->type_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $row[0]->type_name;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="name" class="form-control-label">Award Type:</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Award Type" value="<?php echo $row[0]->type_name;?>">
      </div>
    </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn bg-teal-400 pull-right">Update</button>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	// On page load: datatable
	var xin_table_award_type = $('#xin_table_award_type').DataTable({
		"bDestroy": true,
		"ajax": {
			  url : "<?php echo site_url("settings/award_type_list") ?>",
			type : 'GET'
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




	/* Edit data */
	$("#ed_award_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=38&type=edit_record&data=ed_award_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit_setting_datail').modal('toggle');
					xin_table_award_type.ajax.reload(function(){
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_ob_type' && $_GET['type']=='ed_ob_type'){
$row = $this->Xin_model->read_document_type($_GET['field_id'],'ob_type');
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit OB Type</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_ob_type") ?>/<?php echo $row[0]->type_id;?>/" method="post" name="ed_ob_type_info" id="ed_ob_type_info">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $row[0]->type_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $row[0]->type_name;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="name" class="form-control-label">OB Type:</label>
        <input type="text" class="form-control" name="name" placeholder="Enter OB Type" value="<?php echo $row[0]->type_name;?>">
      </div>
    </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn bg-teal-400 pull-right">Update</button>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	// On page load: datatable
	var xin_table_ob_type = $('#xin_table_ob_type').DataTable({
		"bDestroy": true,
		"ajax": {
			  url : "<?php echo site_url("settings/ob_type_list") ?>",
			type : 'GET'
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




	/* Edit data */
	$("#ed_ob_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=38&type=edit_record&data=ed_ob_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit_setting_datail').modal('toggle');
					xin_table_ob_type.ajax.reload(function(){
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_leave_type' && $_GET['type']=='ed_leave_type'){

$row = $this->Xin_model->read_document_type($_GET['field_id'],'leave_type');
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Leave Type</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_leave_type") ?>/<?php echo $row[0]->type_id;?>/" method="post" name="ed_leave_type_info" id="ed_leave_type_info">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $row[0]->type_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $row[0]->type_name;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="name" class="form-control-label">Leave Type:</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Leave Type" value="<?php echo $row[0]->type_name;?>">
      </div>
      <div class="form-group">
        <label for="days_per_year" class="form-control-label">Days Per Year:</label>
        <input type="text" class="form-control" name="days_per_year" placeholder="Enter Days Per Year" value="<?php echo $row[0]->days_per_year?>">
      </div>
    </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn bg-teal-400 pull-right">Update</button>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	// On page load: datatable

	var xin_table_leave_type = $('#xin_table_leave_type').DataTable({
		"bDestroy": true,
		"ajax": {
			  url : "<?php echo site_url("settings/leave_type_list") ?>",
			type : 'GET'
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

	/* Edit data */
	$("#ed_leave_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=39&type=edit_record&data=ed_leave_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit_setting_datail').modal('toggle');
					xin_table_leave_type.ajax.reload(function(){
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_salary_type' && $_GET['type']=='ed_salary_type'){
$row = $this->Xin_model->read_salary_type($_GET['field_id']);
$parent_type = $this->Xin_model->ready_salary_types();


?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Salary Type</h4>
</div>

  <form class="m-b-1" action="<?php echo site_url("settings/update_salary_type") ?>/<?php echo $row[0]->type_id;?>"/ id="ed_salary_type_info" name="ed_salary_type_info" method="post">
    <input type="hidden" name="_method" value="EDIT">
    <input type="hidden" name="_token" value="<?php echo $row[0]->type_id;?>">
    <input type="hidden" name="ext_name" value="<?php echo $row[0]->type_name;?>">
       <div class="modal-body">
<div class="form-group">
				<label for="name">Choose Parent Type</label>
                <div id="ed_type_parent">
                  <select name="type_parent"  id="select2-demo-6" class="form-control ed_salary_parent_type" onchange="ed_salary_parent_type();" data-plugin="select_hrm" data-placeholder="Choose Parent Type...">
                  <option value="0">Parent</option>
                  <?php foreach($parent_type as $parent) {?>
                  <option <?php if($parent->type_id==$row[0]->type_parent){echo 'selected';}?> value="<?php echo $parent->type_id;?>"> <?php echo $parent->type_name;?> </option>
                  <?php } ?>
                  </select>
                </div>
                </div>
				<div class="form-group">
                <label for="name">Salary Type</label>
                <input type="text" class="form-control" name="type_name" value="<?php echo $row[0]->type_name;?>" placeholder="Salary Type">
                </div>


              <div class="form-group">
				<label for="name">Adjustment Type</label>
                  <select name="adjustment_type" class="form-control"  data-plugin="select_hrm" data-placeholder="Choose Type...">
                  <option <?php if($row[0]->adjustment_type==0){echo 'selected';}?>  value="0">Internal</option>
				  <option  <?php if($row[0]->adjustment_type==1){echo 'selected';}?> value="1">External</option>

                  </select>

                </div>
				<div class="form-group ed_type_action_area">


            <label class="custom-control custom-radio">
              <input type="radio" class="custom-control-input" value="1" name="action_type" <?php if($row[0]->action==1){echo 'checked';}?>>
              <span class="custom-control-indicator"></span> <span class="custom-control-description">Add Action</span> </label>
            <br>

            <label class="custom-control custom-radio">
              <input type="radio" class="custom-control-input" value="0" name="action_type" <?php if($row[0]->action==0){echo 'checked';}?>>
              <span class="custom-control-indicator"></span> <span class="custom-control-description">Deduct Action</span> </label>
            <br>
                </div>

</div>

             <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn bg-teal-400 pull-right">Update</button>
  </div>

            </form>


<script type="text/javascript">
$(document).ready(function(){

var xin_table_salary_type = $('#xin_table_salary_type').DataTable({
		"bDestroy": true,
		"ajax": {
			  url : "<?php echo site_url("settings/salary_type_list") ?>",
			type : 'GET'
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


	/* Edit data */
	  $("#ed_salary_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=39a&data=edit_record&type=ed_salary_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					jQuery('.save').prop('disabled', false);
				} else {
$('.edit_setting_datail').modal('toggle');
					xin_table_salary_type.ajax.reload(function(){
						toastr.success(JSON.result);
					}, true);
					$('.icon-spinner3').hide();


					jQuery('.save').prop('disabled', false);






				}
			}
		});
	});


});
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_warning_type' && $_GET['type']=='ed_warning_type'){

$row = $this->Xin_model->read_document_type($_GET['field_id'],'warning_type');
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Warning Type</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_warning_type") ?>/<?php echo $row[0]->type_id;?>/" method="post" name="ed_warning_type_info" id="ed_warning_type_info">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $row[0]->type_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $row[0]->type_name;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="name" class="form-control-label">Warning Type:</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Warning Type" value="<?php echo $row[0]->type_name;?>">
      </div>
    </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn bg-teal-400 pull-right">Update</button>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	// On page load: datatable

	var xin_table_warning_type = $('#xin_table_warning_type').DataTable({
		"bDestroy": true,
		"ajax": {
			  url : "<?php echo site_url("settings/warning_type_list") ?>",
			type : 'GET'
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


	/* Edit data */
	$("#ed_warning_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=40&type=edit_record&data=ed_warning_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit_setting_datail').modal('toggle');
					xin_table_warning_type.ajax.reload(function(){
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_termination_type' && $_GET['type']=='ed_termination_type'){

$row = $this->Xin_model->read_document_type($_GET['field_id'],'termination_type');
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Termination Type</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_termination_type") ?>/<?php echo $row[0]->type_id;?>/" method="post" name="ed_termination_type_info" id="ed_termination_type_info">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $row[0]->type_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $row[0]->type_name;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="name" class="form-control-label">Termination Type:</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Termination Type" value="<?php echo $row[0]->type_name;?>">
      </div>
    </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn bg-teal-400 pull-right">Update</button>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	// On page load: datatable

	var xin_table_termination_type = $('#xin_table_termination_type').DataTable({
		"bDestroy": true,
		"ajax": {
			  url : "<?php echo site_url("settings/termination_type_list") ?>",
			type : 'GET'
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




	/* Edit data */
	$("#ed_termination_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=41&type=edit_record&data=ed_termination_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit_setting_datail').modal('toggle');
					xin_table_termination_type.ajax.reload(function(){
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_expense_type' && $_GET['type']=='ed_expense_type'){

$row = $this->Xin_model->read_document_type($_GET['field_id'],'expense_type');
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Expense Type</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_expense_type") ?>/<?php echo $row[0]->type_id;?>/" method="post" name="ed_expense_type_info" id="ed_expense_type_info">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $row[0]->type_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $row[0]->type_name;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="name" class="form-control-label">Expense Type:</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Expense Type" value="<?php echo $row[0]->type_name;?>">
      </div>
    </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn bg-teal-400 pull-right">Update</button>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	// On page load: datatable
	var xin_table_expense_type = $('#xin_table_expense_type').DataTable({
		"bDestroy": true,
		"ajax": {
			  url : "<?php echo site_url("settings/expense_type_list") ?>",
			type : 'GET'
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


	/* Edit data */
	$("#ed_expense_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=42&type=edit_record&data=ed_expense_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit_setting_datail').modal('toggle');
					xin_table_expense_type.ajax.reload(function(){
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_job_type' && $_GET['type']=='ed_job_type'){

$row = $this->Xin_model->read_document_type($_GET['field_id'],'job_type');
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Job Type</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_job_type") ?>/<?php echo $row[0]->type_id;?>/" method="post" name="ed_job_type_info" id="ed_job_type_info">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $row[0]->type_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $row[0]->type_name;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="name" class="form-control-label">Job Type:</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Job Type" value="<?php echo $row[0]->type_name;?>">
      </div>
    </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn bg-teal-400 pull-right">Update</button>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	// On page load: datatable

	var xin_table_job_type = $('#xin_table_job_type').DataTable({
		"bDestroy": true,
		"ajax": {
			  url : "<?php echo site_url("settings/job_type_list") ?>",
			type : 'GET'
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


	/* Edit data */
	$("#ed_job_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=43&type=edit_record&data=ed_job_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit_setting_datail').modal('toggle');
					xin_table_job_type.ajax.reload(function(){
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_company_type' && $_GET['type']=='ed_company_type'){

$row = $this->Xin_model->read_document_type($_GET['field_id'],'company_type');
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Company Type</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_company_type") ?>/<?php echo $row[0]->type_id;?>/" method="post" name="ed_company_type_info" id="ed_company_type_info">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $row[0]->type_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $row[0]->type_name;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="name" class="form-control-label">Company Type:</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Company Type" value="<?php echo $row[0]->type_name;?>">
      </div>
    </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn bg-teal-400 pull-right">Update</button>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	// On page load: datatable

	var xin_table_company_type = $('#xin_table_company_type').DataTable({
		"bDestroy": true,
		"ajax": {
			  url : "<?php echo site_url("settings/company_type_list") ?>",
			type : 'GET'
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


	/* Edit data */
	$("#ed_company_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=43&type=edit_record&data=ed_company_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit_setting_datail').modal('toggle');
					xin_table_company_type.ajax.reload(function(){
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_exit_type' && $_GET['type']=='ed_exit_type'){
$row = $this->Xin_model->read_document_type($_GET['field_id'],'exit_type');
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Employee Exit Type</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_exit_type") ?>/<?php echo $row[0]->type_id;?>/" method="post" name="ed_exit_type_info" id="ed_exit_type_info">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $row[0]->type_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $row[0]->type_name;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="name" class="form-control-label">Employee Exit Type:</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Employee Exit Type" value="<?php echo $row[0]->type_name;?>">
      </div>
    </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn bg-teal-400 pull-right">Update</button>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	// On page load: datatable

	var xin_table_exit_type = $('#xin_table_exit_type').DataTable({
		"bDestroy": true,
		"ajax": {
			  url : "<?php echo site_url("settings/exit_type_list") ?>",
			type : 'GET'
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

	/* Edit data */
	$("#ed_exit_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=44&type=edit_record&data=ed_exit_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit_setting_datail').modal('toggle');
					xin_table_exit_type.ajax.reload(function(){
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_travel_arr_type' && $_GET['type']=='ed_travel_arr_type'){

$row = $this->Xin_model->read_document_type($_GET['field_id'],'travel_arrangement_type');
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Travel Arrangement Type</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_travel_arr_type") ?>/<?php echo $row[0]->type_id;?>/" method="post" name="ed_travel_arr_type_info" id="ed_travel_arr_type_info">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $row[0]->type_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $row[0]->type_name;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="name" class="form-control-label">Travel Arrangement Type:</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Travel Arrangement Type" value="<?php echo $row[0]->type_name;?>">
      </div>
    </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn bg-teal-400 pull-right">Update</button>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	// On page load: datatable

	var xin_table_travel_arr_type = $('#xin_table_travel_arr_type').DataTable({
		"bDestroy": true,
		"ajax": {
			  url : "<?php echo site_url("settings/travel_arr_type_list") ?>",
			type : 'GET'
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


	/* Edit data */
	$("#ed_travel_arr_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=46&type=edit_record&data=ed_travel_arr_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit_setting_datail').modal('toggle');
					xin_table_travel_arr_type.ajax.reload(function(){
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_currency_type' && $_GET['type']=='ed_currency_type'){
$row = $this->Xin_model->read_document_type($_GET['field_id'],'currency_type');
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Currency</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_currency_type") ?>/<?php echo $row[0]->type_id;?>/" method="post" name="ed_currency_type_info" id="ed_currency_type_info">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $row[0]->type_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $row[0]->type_name;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="name">Currency Name</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Currency Name" value="<?php echo $row[0]->type_name;?>">
      </div>
      <div class="form-group">
        <label for="name">Currency Code</label>
        <input type="text" class="form-control" name="code" placeholder="Enter Currency Code" value="<?php echo $row[0]->type_code;?>">
      </div>
      <div class="form-group">
        <label for="name">Currency Symbol</label>
        <input type="text" class="form-control" name="symbol" placeholder="Enter Currency Symbol" value="<?php echo $row[0]->type_symbol;?>">
      </div>
    </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn bg-teal-400 pull-right">Update</button>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	// On page load: datatable

	var xin_table_currency_type = $('#xin_table_currency_type').DataTable({
		"bDestroy": true,
		"ajax": {
			  url : "<?php echo site_url("settings/currency_type_list") ?>",
			type : 'GET'
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



	/* Edit data */
	$("#ed_currency_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=46&type=edit_record&data=ed_currency_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit_setting_datail').modal('toggle');
					xin_table_currency_type.ajax.reload(function(){
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['user_id']) && $_GET['data']=='password' && $_GET['type']=='password'){?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Change Password</h4>
</div>
<form id="profile_password" action="<?php echo site_url("employees/change_password");?>" name="e_change_password" method="post">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="user_id" value="<?php echo $_GET['user_id'];?>">
  <div class="modal-body">
      <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="new_password">Enter New Password</label>
          <input class="form-control" placeholder="Enter New Password" name="new_password" type="password">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="new_password_confirm" class="control-label">Enter New Confirm Password</label>
          <input class="form-control" placeholder="Enter New Confirm Password" name="new_password_confirm" type="password">
        </div>
      </div>
    </div>
    </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn bg-teal-400 pull-right">Update</button>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	/* change password */
	jQuery("#profile_password").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=31&data=e_change_password&type=change_password&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					jQuery('.save').prop('disabled', false);
				} else {
					$('.pro_change_password').modal('toggle');
					toastr.success(JSON.result);
					jQuery('#profile_password')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
});
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['p']) && $_GET['data']=='policy' && $_GET['type']=='policy'){
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Company Policy</h4>
</div>
  <div class="modal-body">

      <div class="form-group">
        <div id="accordion" role="tablist" aria-multiselectable="true">



        <?php foreach($this->Xin_model->all_policies() as $_policy):?>



				  <div class="panel-group-control panel-group-control-right">
            <div class="panel panel-white">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h6 class="panel-title">
                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $_policy->policy_id;?>" aria-expanded="true" aria-controls="collapseOne">
                         <?php
						 if($_policy->company_id==0){
							 $cname = 'All Companies';
						 } else {
							$company = $this->Xin_model->read_company_info($_policy->company_id);
							$cname = $company[0]->name;
						 }
						 ?>
                            <i class="icon-help position-left text-slate"></i> <?php echo $_policy->title;?> (<?php echo $cname;?>)
                        </a>
                    </h6>
                </div>
                <div id="collapse<?php echo $_policy->policy_id;?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                   <?php //echo html_entity_decode($_policy->description);?>
				  <?php echo html_entity_decode(stripcslashes($_policy->description));?>
                </div>
            </div>
			</div>
           <?php endforeach;?>
        </div>
      </div>
    </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
  </div>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_tax_type' && $_GET['type']=='ed_tax_type'){
$row = $this->Xin_model->read_document_type($_GET['field_id'],'tax_type');

$visa_type = $this->Xin_model->get_visa_under();
$visa_type = $visa_type->result();

?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Tax</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_tax_type") ?>/<?php echo $row[0]->type_id;?>/" method="post" name="ed_tax_type_info" id="ed_tax_type_info">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $row[0]->type_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $row[0]->type_name;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="name">Tax Name</label>
        <input type="text" class="form-control" name="tax_name" placeholder="Enter Tax Name" value="<?php echo $row[0]->type_name;?>">
      </div>
      <div class="form-group">
<?php $visa_db=explode(',',$row[0]->type_code);?>
        <label for="name">Visa Type</label>
 <select name="visa_type[]"  multiple class="form-control" data-plugin="select_hrm" data-placeholder="Select Visa Type">
            <option value=""></option>
            <?php foreach($visa_type as $visa_ty) {
if(in_array($visa_ty->type_id,$visa_db))
{
$selected="selected";
}else{$selected='';}
?>

<option <?php echo $selected;?> value="<?php echo $visa_ty->type_id;?>"> <?php echo $visa_ty->type_name;?>


</option>
            <?php } ?>
          </select>


      </div>
      <div class="form-group">
        <label for="name">Tax Percentage</label>
        <input type="number" step="0.1" class="form-control" name="tax_percentage" placeholder="Enter Tax Percentage" value="<?php echo $row[0]->type_symbol;?>">
      </div>
    </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn bg-teal-400 pull-right">Update</button>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	// On page load: datatable

	var xin_table_tax_type = $('#xin_table_tax_type').DataTable({
		"bDestroy": true,
		"ajax": {
			  url : "<?php echo site_url("settings/tax_type_list") ?>",
			type : 'GET'
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



	/* Edit data */
	$("#ed_tax_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=46&type=edit_record&data=ed_tax_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit_setting_datail').modal('toggle');
					xin_table_tax_type.ajax.reload(function(){
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_salary_field' && $_GET['type']=='ed_salary_field'){

$row = $this->Xin_model->read_salary_fields($_GET['field_id']);

?>
<div class="modal-header">
<div class="modal-header">

  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
<button type="button" onclick="add_new_field();" class="btn bg-teal-400 pull-right">Add New</button>
  <h4 class="modal-title" id="edit-modal-data">Salary Field Structure For  <?php echo $_GET['name'];?></h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_salary_field") ?>" method="post" name="ed_salary_field" id="ed_salary_field">
  <input type="hidden" name="_method" value="EDIT">
 <input name="country_id" value="<?php echo $_GET['field_id'];?>"  type="hidden">
  <div class="modal-body">
	<div class="row">
<div class="col-lg-12 mb-20" style="border-top: 1px solid grey;border-bottom: 1px solid grey;">
<div class="col-lg-4"><div class="form-group"><h5>Title Name</h5></div></div>
<div class="col-lg-3"><div class="form-group"><h5>Variable Name</h5></div></div>
<div class="col-lg-3"><div class="form-group"><h5>Compute Salary</h5></div></div>
<div class="col-lg-2"><div class="form-group"><h5> Sort Order</h5></div></div>
</div>
   <?php if($row){$i=1;foreach($row as $drag){?>
<div class="col-lg-12">
       <div class="col-lg-4">
<input name="salary_field_id[]" value="<?php echo $drag->salary_field_id;?>" class="form-control" type="hidden">
				<div class="form-group">
					<input value="<?php echo change_to_caps(str_replace('_',' ',$drag->salary_field_name));?>" class="form-control  field_name_<?php echo $i;?>" onkeyup="string_change(this.value,<?php echo $i;?>);" type="text" maxlength="100" title="Alphabets, spaces only allowed" required="required" pattern="^[A-Za-z ]+$">
				</div>
			</div>

			<div class="col-lg-3">
				<div class="form-group">
				 <input name="salary_field_name[]" value="<?php echo $drag->salary_field_name;?>" class="form-control field_n_<?php echo $i;?>" type="text" readonly>
				</div>
			</div>
<div class="col-lg-3">
				<div class="form-group text-center">
<select name="salary_calculate[]" data-plugin="select_hrm">
<option value="0" <?php if($drag->salary_calculate==0){echo 'selected';}?>>No</option>
<option value="1" <?php if($drag->salary_calculate==1){echo 'selected';}?>>Yes</option>
</select>

				</div>
			</div>
<div class="col-lg-1">
				<div class="form-group">
				 <input name="salary_field_order[]" value="<?php echo $drag->salary_field_order;?>" class="form-control" type="text">
				</div>
			</div>
		</div>

   <?php $i++;} }?>
<div id="append_div">

</div>
</div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn bg-teal-400 pull-right">Update</button>
  </div>
</form>

<script type="text/javascript">

function remove_field(id){
$('#remove_field_'+id).remove();
}
function add_new_field(){
    $.ajax({
			type: "POST",
			url: site_url+'settings/dynamic_fields/',
			data: "data=dynamic_fields",
			cache: false,
			success: function (val) {
			$('#append_div').append(val);
			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
		   }
		});



}
function string_change(value,field){
var vals=value;
var res = vals.replace(/ /g, "_");
$('.field_n_'+field).val(res.toLowerCase());
}
$(document).ready(function(){


	$("#ed_salary_field").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=46&type=edit_record&data=ed_salary_field&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit-modal-data').modal('toggle');
					xin_table_salary_fields.api().ajax.reload(function(){
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});
</script>
<?php } ?>

<script type="text/javascript">
$(document).ready(function(){
    $('.dataTables_filter input[type=search]').attr('placeholder','Type to filter...');


    // Enable Select2 select for the length option
    $('.dataTables_length select,.change_country_code').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });

    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));   });
</script>
