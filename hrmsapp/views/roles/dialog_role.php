<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['role_id']) && $_GET['data']=='role'){
$role_resources_ids = explode(',',$role_resources);
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit User Role</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("roles/update").'/'.$role_id; ?>" method="post" name="edit_role" id="edit_role">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $role_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $role_name;?>">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-4">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="role_name">Role Name</label>
              <input class="form-control" placeholder="Role Name" name="role_name" type="text" value="<?php echo $role_name;?>" <?php if($role_name==AD_ROLE){ echo 'readonly';};?>>
            </div>
          </div> 
        </div>
        <div class="row">
        	<input type="checkbox" name="role_resources[]" value="0" checked style="display:none;"/>
          <div class="col-md-12">
            <div class="form-group">
              <label for="role_access">Select Access</label>
              <select class="form-control custom-select" id="role_access_modal" name="role_access" data-plugin="select_hrm" data-placeholder="Select Access">
                <option value="">&nbsp;</option>
                <option value="1" <?php if($role_access==1):?> selected="selected" <?php endif;?>>All Menu Access</option>
                <option value="2" <?php if($role_access==2):?> selected="selected" <?php endif;?>>Custom Menu Access (More)</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="resources">Resources</label>
              <div id="all_resources">
                <div class="demo-section k-content">
                  <div>
                    <div id="treeview_m1"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-5 mb-5" style="border-left: 1px solid #ddd;">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <div id="all_resources">
                <div class="demo-section k-content">
                  <div>
                    <div id="treeview_m2"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
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
		  	url : "<?php echo site_url("roles/role_list") ?>",
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

	$(".styled").uniform({
        radioClass: 'choice'
    });


		/* Edit data */
		$("#edit_role").submit(function(e){
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=1&edit_type=role&form="+action,
				cache: false,
				success: function (JSON) {
					if (JSON.error != '') {
						toastr.error(JSON.error);
						$('.save').prop('disabled', false);
					} else {
						$('.edit-modal-data').modal('toggle');
						xin_table.ajax.reload(function(){
							toastr.success(JSON.result);
						}, true);

						$('.save').prop('disabled', false);
					}
				}
			});
		});
	});
  </script>
<script>
jQuery("#treeview_m1").kendoTreeView({
checkboxes: {
checkChildren: true,
template: "<label class='custom-control custom-checkbox'><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'  />&nbsp;&nbsp;&nbsp;<span class='custom-control-indicator'></span><span class='custom-control-description'>#= item.text #</span><span class='custom-control-info'>#= item.add_info #</span></label>"
},
check: onCheck,
dataSource: [
{ id: "", class: "role-checkbox custom-control-input ", text: "Dashboard (For Admin)", check: "<?php if(isset($_GET['role_id'])) { if(in_array('0d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "", value: "0d"},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Organization", add_info: "", value: "1", items: [
// sub 1
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('3',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Company",  add_info: "", value: "3", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('3a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "3a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('3e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "3e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('3d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "3d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('3v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "3v",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('4',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Location",  add_info: "", value: "4", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('4a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "4a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('4e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "4e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('4d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "4d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('4v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "4v",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('5',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Department",  add_info: "", value: "5", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('5a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "5a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('5e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "5e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('5d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "5d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('5v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "5v",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('6',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Designation",  add_info: "", value: "6", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('6a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "6a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('6e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "6e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('6d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "6d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('6v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "6v",},
]},
]}, // sub 1 end
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('11',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Employees",  add_info: "", value: "11",  items: [
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('employees-list',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Employees List",  add_info: "", value: "employees-list", items: [

  { id: "", class: "role-checkbox custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('employees-list-add',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "employees-list-add",},
	{ id: "", class: "role-checkbox custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('employees-list-edit',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "employees-list-edit",},
	{ id: "", class: "role-checkbox custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('employees-list-delete',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "employees-list-delete",},
  { id: "", class: "role-checkbox custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('employees-list-view',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "employees-list-view",},
  
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('basic-info',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Basic Information",  add_info: "", value: "basic-info", items: [
// sub 3	
	{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('basic-info-edit',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "basic-info-edit",},
	{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('basic-info-view',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "basic-info-view",},
]},
{ id: "", class: "role-checkbox custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('documents',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Documents",  add_info: "", value: "documents", items: [
	// sub 3
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('documents-add',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "documents-add",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('documents-edit',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "documents-edit",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('documents-delete',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "documents-delete",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('documents-view',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "documents-view",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('offer-letter',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Offer Letter",  add_info: "", value: "offer-letter", items: [
	// sub 3
		// { id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('offer-letter-add',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "offer-letter-add",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('offer-letter-edit',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "offer-letter-edit",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('offer-letter-delete',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "offer-letter-delete",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('offer-letter-view',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "offer-letter-view",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('contract',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Contract",  add_info: "", value: "contract", items: [
	// sub 3
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('contract-add',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "contract-add",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('contract-edit',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "contract-edit",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('contract-delete',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "contract-delete",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('contract-view',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "contract-view",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('pay-structure',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Pay Structure",  add_info: "", value: "pay-structure", items: [
	// sub 3
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('pay-structure-add',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "pay-structure-add",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('pay-structure-edit',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "pay-structure-edit",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('pay-structure-delete',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "pay-structure-delete",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('pay-structure-view',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "pay-structure-view",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('bank-account',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Bank Account",  add_info: "", value: "bank-account", items: [
	// sub 3
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('bank-account-add',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "bank-account-add",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('bank-account-edit',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "bank-account-edit",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('bank-account-delete',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "bank-account-delete",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('bank-account-view',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "bank-account-view",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('emergency-contacts',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Emergency Contacts",  add_info: "", value: "emergency-contacts", items: [
	// sub 3
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('emergency-contacts-add',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "emergency-contacts-add",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('emergency-contacts-edit',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "emergency-contacts-edit",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('emergency-contacts-delete',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "emergency-contacts-delete",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('emergency-contacts-view',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "emergency-contacts-view",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('qualification',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Qualification",  add_info: "", value: "qualification", items: [
	// sub 3
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('qualification-add',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "qualification-add",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('qualification-edit',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "qualification-edit",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('qualification-delete',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "qualification-delete",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('qualification-view',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "qualification-view",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('work-experience',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Work Experience",  add_info: "", value: "work-experience", items: [
	// sub 3
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('work-experience-add',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "work-experience-add",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('work-experience-edit',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "work-experience-edit",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('work-experience-delete',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "work-experience-delete",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('work-experience-view',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "work-experience-view",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('change-password',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Change Password",  add_info: "", value: "change-password",},

{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('request-approval',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Undertakings",  add_info: "", value: "request-approval",},

]}, 

{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('16',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Transfers",  add_info: "", value: "16", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('16a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "16a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('16e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "16e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('16d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "16d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('16v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "16v",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('17',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Resignations",  add_info: "", value: "17", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('17a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "17a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('17e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "17e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('17d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "17d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('17v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "17v",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('18',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Travels",  add_info: "", value: "18",items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('18a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "18a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('18e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "18e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('18d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "18d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('18v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "18v",},
]},

{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('20',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Promotions",  add_info: "", value: "20", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('20a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "20a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('20e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "20e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('20d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "20d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('20v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "20v",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('21',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Complaints",  add_info: "", value: "21", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('21a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "21a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('21e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "21e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('21d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "21d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('21v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "21v",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('22',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Warnings",  add_info: "", value: "22", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('22a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "22a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('22e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "22e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('22d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "22d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('22v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "22v",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('23',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Terminations  & Suspensions",  add_info: "", value: "23", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('23a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "23a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('23e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "23e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('23d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "23d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('23v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "23v",},
]},

{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('27',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Employees Exit",  add_info: "", value: "27", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('27a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "27a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('27e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "27e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('27d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "27d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('27v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "27v",},
]}
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('28',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Timesheet",  add_info: "", value: "28",  items: [
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('30',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Attendance List",  add_info: " - View", value: "30",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('31',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Manual Attendance (OB)",  add_info: "", value: "31", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('31a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "31a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('31e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "31e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('31d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "31d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('31v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "31v",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('31ad',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",text: "Approve/Decline",  add_info: "", value: "31ad",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('32',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Leaves",  add_info: "", value: "32", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('32a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "32a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('32e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "32e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('32d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "32d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('32v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "32v",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", text: "Mails", check: "<?php if(isset($_GET['role_id'])) { if(in_array('32m',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",  add_info: " (Only for HR<br>Manual Attendance Approval / <br>leave Approval", value: "32m",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('32lv',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Employee's Leave Card",  add_info: " - View", value: "32lv",},

{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('35',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Holidays",  add_info: "", value: "35", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('35a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "35a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('35e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "35e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('35d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "35d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('35v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "35v",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('bio-user',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Biometric Users List",  add_info: "", value: "bio-user", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('bio-user-add',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "bio-user-add",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('bio-user-edit',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "bio-user-edit",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('bio-user-delete',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "bio-user-delete",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('bio-user-view',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "bio-user-view",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('al-expiry',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "AL Expiry Adjustments",  add_info: "", value: "al-expiry", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('al-expiry-add',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "al-expiry-add",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('al-expiry-edit',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "al-expiry-edit",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('al-expiry-delete',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "al-expiry-delete",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('al-expiry-view',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "al-expiry-view",},
]},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('36',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Payroll",  add_info: "", value: "36",  items: [
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('38',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Payroll (Adjustments)",  add_info: "", value: "38", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('38a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "38a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('38e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "38e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('38d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "38d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('38v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "38v",},
]},
{ id: "", class: "role-checkbox custom-control-input ",check: "<?php if(isset($_GET['role_id'])) { if(in_array('38tv',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Payroll Templeate",  add_info: "", value: "38tv", items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ",check: "<?php if(isset($_GET['role_id'])) { if(in_array('38te',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "38te",},
{ id: "", class: "role-checkbox custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('38tv',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "38tv",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('41',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Generate Payslip",  add_info: " - Generate/View", value: "41",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('41L',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Leave Settlement",  add_info: " - Generate", value: "41L",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('42',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Payment History",  add_info: " - View Payslip", value: "42",},
]},

{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('Request-To-Employer',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Request To Employer",  add_info: "", value: "Request-To-Employer",  items: [

{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('Request-For-Passport-Release',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",text:"Request For Passport Release",add_info:"",value:"Request-For-Passport-Release", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('request-passport-delete',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "request-passport-delete",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('request-passport-view',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "request-passport-view",},
]},
{ id: "", class: "role-checkbox custom-control-input ",check: "<?php if(isset($_GET['role_id'])) { if(in_array('38tvRequest-For-Others',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Request For Others",  add_info: "", value: "Request-For-Others", items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ",check: "<?php if(isset($_GET['role_id'])) { if(in_array('request-others-delete',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "request-others-delete",},
{ id: "", class: "role-checkbox custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('request-others-view',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "request-others-view",},
]},
]},

]
});

jQuery("#treeview_m2").kendoTreeView({
checkboxes: {
checkChildren: true,
template: "<label class='custom-control custom-checkbox'><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'  />&nbsp;&nbsp;&nbsp;<span class='custom-control-indicator'></span><span class='custom-control-description'>#= item.text #</span><span class='custom-control-info'>#= item.add_info #</span></label>"
},
check: onCheck,
dataSource: [
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('53',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Settings",  add_info: "", value: "53",items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('53v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "", value: "53v",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Update", check: "<?php if(isset($_GET['role_id'])) { if(in_array('53e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",  add_info: "", value: "53e",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('54',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Constants",  add_info: "", value: "54",
items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Add", check: "<?php if(isset($_GET['role_id'])) { if(in_array('54a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",  add_info: "", value: "54a",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Update",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('54e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",  add_info: "", value: "54e",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View", check: "<?php if(isset($_GET['role_id'])) { if(in_array('54v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",  add_info: "", value: "54v",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete", check: "<?php if(isset($_GET['role_id'])) { if(in_array('54d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",  add_info: "", value: "54d",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('14',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Set Roles",  add_info: "", value: "14", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('14a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "14a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('14e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "14e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('14d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "14d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('14v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "14v",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('26',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Employees Last Login",  add_info: " - View", value: "26",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('52',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Employees Directory",  add_info: " - View", value: "52",},

{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('34',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Shift Management",  add_info: "(Office Shifts / Change & Exceptional Schedule)", value: "34", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('34a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "34a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('34e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "34e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('34d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "34d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('34v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "34v",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('55',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Email Templates",  add_info: " - Update", value: "55",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('56',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Database Backup",  add_info: " - Create/Delete/Download", value: "56",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('57',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Bulk Upload",  add_info: "", value: "57",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('58',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Manual Attendance Sync",  add_info: "", value: "58",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('59',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Activity Logs",  add_info: "", value: "59",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('system-logs',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "System Logs",  add_info: "", value: "system-logs",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('60',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Reports",  add_info: "", value: "60",items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('60c',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Employees Report",  add_info: "", value: "60c",items: [
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('60c-view',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",  text: "View Report",  add_info: "", value: "60c-view",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('60c-salary',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",  text: "Salary Informations",  add_info: "", value: "60c-salary",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('60c-employee',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Employee Informations",  add_info: "", value: "60c-employee",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('60c-document',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Employee Documents",  add_info: "", value: "60c-document",},]
},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('60b',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Uploaded Documents",  add_info: "", value: "60b",},
]},

//
]
});


// show checked node IDs on datasource change
function onCheck() {
var checkedNodes = [],
treeView = jQuery("#treeview").data("kendoTreeView"),
message;
//checkedNodeIds(treeView.dataSource.view(), checkedNodes);
jQuery("#result").html(message);
}
$(document).ready(function(){
	$("#role_access_modal").change(function(){
		var sel_val = $(this).val();
		if(sel_val=='1') {
			$('.role-checkbox-modal').prop('checked', true);
		} else {
			$('.role-checkbox-modal').prop('checked', false);
			$('input[value="26"]').prop("checked", true);
			$('input[value="52"]').prop("checked", true);
			$('input[value="53"]').prop("checked", true);
			$('input[value="54"]').prop("checked", true);
			$('input[value="55"]').prop("checked", true);
			$('input[value="56"]').prop("checked", true);
			$('input[value="14"]').prop("checked", true);
		}
	});
});
</script>
<?php }

else if(isset($_GET['jd']) && isset($_GET['role_id']) && isset($_GET['emp_id'])  && $_GET['data']=='add_custom_role'){

$user = $this->Xin_model->read_user_info($_GET['emp_id']);
$session = $this->session->userdata('username');

if($user[0]->custom_roles!=''){
$custom_roles = json_decode($user[0]->custom_roles);

$role_resources_ids = explode(',',$custom_roles->role_resources);

}else{
$role_resources_ids='';
}


$vals=$_GET['vals'];

if($vals=='add'){
	$which_action='Add';
}else{
	$which_action='Update';
}

?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><?php echo $which_action;?> Custom Employee Role For (<?php echo change_fletter_caps($user[0]->first_name.' '.$user[0]->middle_name.' '.$user[0]->last_name);?>)</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("roles/custom_update").'/'.$_GET['emp_id']; ?>" method="post" name="edit_role" id="edit_role">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $_GET['emp_id'];?>">
  <input type="hidden" name="added_by" value="<?php echo $session['user_id'];?>">
  <input type="hidden" name="added_date" value="<?php echo date('Y-m-d H:i:s');?>">


  <div class="modal-body">
    <div class="row">

      <div class="col-md-4">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="resources">Resources</label>
              <div id="all_resources">
                <div class="demo-section k-content">
                  <div>
                    <div id="treeview_m1a"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="visa_wise_role">Visa wise permission</label>
              <select class="form-control custom-select" id="visa_wise_modal" name="visa_wise_role" data-plugin="select_hrm">
                <option value="">Select a visa type</option>
                <?php 
                for ($v=0; $v < count($visa_type); $v++) { 
                ?>
                <option value="<?=$visa_type[$v]->type_id?>" <?php if($custom_roles->visa_wise_role == $visa_type[$v]->type_id){ echo 'selected'; } ?>><?=$visa_type[$v]->type_name?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>

      </div>
      <div class="col-md-8 mb-5" style="border-left: 1px solid #ddd;">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <div id="all_resources">
                <div class="demo-section k-content">
                  <div>
                    <div id="treeview_m2a"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn bg-teal-400"><?php echo $which_action;?></button>
  </div>
</form>
<script type="text/javascript">
 $(document).ready(function(){
		/* Edit data */
		$("#edit_role").submit(function(e){
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=1&edit_type=custom_role&form="+action,
				cache: false,
				success: function (JSON) {
					if (JSON.error != '') {
						toastr.error(JSON.error);
						$('.save').prop('disabled', false);
					} else {
				     	xin_form_roles();
						$('.edit-modal-data-payrol').modal('toggle');
						toastr.success(JSON.result);
					    clear_roles();
						get_update_employees();
						$('.save').prop('disabled', false);
					}
				}
			});
		});
	});
  </script>
<script>
jQuery("#treeview_m1a").kendoTreeView({
checkboxes: {
checkChildren: true,
template: "<label class='custom-control custom-checkbox'><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'  />&nbsp;&nbsp;&nbsp;<span class='custom-control-indicator'></span><span class='custom-control-description'>#= item.text #</span><span class='custom-control-info'>#= item.add_info #</span></label>"
},
check: onCheck,
dataSource: [
{ id: "", class: "role-checkbox custom-control-input ", text: "Dashboard (For Admin)", check: "<?php if(isset($_GET['role_id'])) { if(in_array('0d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "", value: "0d"},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Organization", add_info: "", value: "1", items: [
// sub 1
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('3',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Company",  add_info: "", value: "3", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('3a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "3a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('3e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "3e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('3d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "3d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('3v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "3v",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('4',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Location",  add_info: "", value: "4", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('4a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "4a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('4e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "4e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('4d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "4d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('4v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "4v",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('5',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Department",  add_info: "", value: "5", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('5a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "5a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('5e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "5e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('5d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "5d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('5v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "5v",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('6',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Designation",  add_info: "", value: "6", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('6a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "6a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('6e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "6e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('6d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "6d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('6v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "6v",},
]},
]}, // sub 1 end
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('11',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Employees",  add_info: "", value: "11",  items: [
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('employees-list',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Employees List",  add_info: "", value: "employees-list", items: [

  { id: "", class: "role-checkbox custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('employees-list-add',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "employees-list-add",},
	{ id: "", class: "role-checkbox custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('employees-list-edit',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "employees-list-edit",},
	{ id: "", class: "role-checkbox custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('employees-list-delete',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "employees-list-delete",},
  { id: "", class: "role-checkbox custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('employees-list-view',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "employees-list-view",},
  
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('basic-info',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Basic Information",  add_info: "", value: "basic-info", items: [
// sub 3
	{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('basic-info-edit',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "basic-info-edit",},
	{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('basic-info-view',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "basic-info-view",},
]},
{ id: "", class: "role-checkbox custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('documents',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Documents",  add_info: "", value: "documents", items: [
	// sub 3
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('documents-add',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "documents-add",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('documents-edit',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "documents-edit",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('documents-delete',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "documents-delete",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('documents-view',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "documents-view",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('offer-letter',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Offer Letter",  add_info: "", value: "offer-letter", items: [
	// sub 3
		// { id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('offer-letter-add',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "offer-letter-add",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('offer-letter-edit',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "offer-letter-edit",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('offer-letter-delete',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "offer-letter-delete",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('offer-letter-view',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "offer-letter-view",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('contract',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Contract",  add_info: "", value: "contract", items: [
	// sub 3
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('contract-add',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "contract-add",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('contract-edit',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "contract-edit",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('contract-delete',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "contract-delete",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('contract-view',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "contract-view",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('pay-structure',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Pay Structure",  add_info: "", value: "pay-structure", items: [
	// sub 3
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('pay-structure-add',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "pay-structure-add",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('pay-structure-edit',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "pay-structure-edit",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('pay-structure-delete',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "pay-structure-delete",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('pay-structure-view',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "pay-structure-view",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('bank-account',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Bank Account",  add_info: "", value: "bank-account", items: [
	// sub 3
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('bank-account-add',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "bank-account-add",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('bank-account-edit',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "bank-account-edit",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('bank-account-delete',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "bank-account-delete",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('bank-account-view',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "bank-account-view",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('emergency-contacts',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Emergency Contacts",  add_info: "", value: "emergency-contacts", items: [
	// sub 3
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('emergency-contacts-add',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "emergency-contacts-add",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('emergency-contacts-edit',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "emergency-contacts-edit",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('emergency-contacts-delete',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "emergency-contacts-delete",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('emergency-contacts-view',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "emergency-contacts-view",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('qualification',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Qualification",  add_info: "", value: "qualification", items: [
	// sub 3
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('qualification-add',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "qualification-add",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('qualification-edit',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "qualification-edit",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('qualification-delete',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "qualification-delete",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('qualification-view',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "qualification-view",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('work-experience',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Work Experience",  add_info: "", value: "work-experience", items: [
	// sub 3
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('work-experience-add',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "work-experience-add",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('work-experience-edit',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "work-experience-edit",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('work-experience-delete',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "work-experience-delete",},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('work-experience-view',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "work-experience-view",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('change-password',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Change Password",  add_info: "", value: "change-password",},

{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('request-approval',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Undertakings",  add_info: "", value: "request-approval",},

]},

{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('16',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Transfers",  add_info: "", value: "16", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('16a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "16a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('16e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "16e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('16d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "16d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('16v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "16v",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('17',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Resignations",  add_info: "", value: "17", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('17a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "17a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('17e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "17e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('17d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "17d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('17v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "17v",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('18',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Travels",  add_info: "", value: "18",items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('18a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "18a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('18e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "18e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('18d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "18d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('18v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "18v",},
]},

{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('20',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Promotions",  add_info: "", value: "20", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('20a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "20a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('20e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "20e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('20d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "20d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('20v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "20v",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('21',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Complaints",  add_info: "", value: "21", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('21a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "21a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('21e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "21e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('21d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "21d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('21v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "21v",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('22',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Warnings",  add_info: "", value: "22", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('22a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "22a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('22e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "22e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('22d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "22d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('22v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "22v",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('23',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Terminations  & Suspensions",  add_info: "", value: "23", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('23a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "23a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('23e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "23e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('23d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "23d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('23v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "23v",},
]},

{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('27',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Employees Exit",  add_info: "", value: "27", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('27a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "27a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('27e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "27e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('27d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "27d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('27v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "27v",},
]}
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('28',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Timesheet",  add_info: "", value: "28",  items: [
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('30',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Attendance List",  add_info: " - View", value: "30",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('31',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Manual Attendance (OB)",  add_info: "", value: "31", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('31a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "31a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('31e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "31e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('31d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "31d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('31v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "31v",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('31ad',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",text: "Approve/Decline",  add_info: "", value: "31ad",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('32',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Leaves",  add_info: "", value: "32", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('32a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "32a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('32e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "32e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('32d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "32d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('32v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "32v",},

]},
{ id: "", class: "role-checkbox-modal custom-control-input ", text: "Mails", check: "<?php if(isset($_GET['role_id'])) { if(in_array('32m',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",  add_info: " (Only for HR<br>Manual Attendance Approval / <br>leave Approval", value: "32m",},

{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('32lv',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Employee's Leave Card",  add_info: " - View", value: "32lv",},

{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('35',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Holidays",  add_info: "", value: "35", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('35a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "35a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('35e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "35e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('35d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "35d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('35v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "35v",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('bio-user',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Biometric Users List",  add_info: "", value: "bio-user", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('bio-user-add',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "bio-user-add",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('bio-user-edit',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "bio-user-edit",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('bio-user-delete',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "bio-user-delete",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('bio-user-view',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "bio-user-view",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('al-expiry',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "AL Expiry Adjustments",  add_info: "", value: "al-expiry", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('al-expiry-add',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "al-expiry-add",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('al-expiry-edit',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "al-expiry-edit",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('al-expiry-delete',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "al-expiry-delete",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('al-expiry-view',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "al-expiry-view",},
]},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('36',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Payroll",  add_info: "", value: "36",  items: [
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('38',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Payroll (Adjustments)",  add_info: "", value: "38", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('38a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "38a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('38e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "38e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('38d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "38d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('38v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "38v",},
]},
{ id: "", class: "role-checkbox custom-control-input ",check: "<?php if(isset($_GET['role_id'])) { if(in_array('38tv',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Payroll Templeate",  add_info: "", value: "38tv", items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ",check: "<?php if(isset($_GET['role_id'])) { if(in_array('38te',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "38te",},
{ id: "", class: "role-checkbox custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('38tv',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "38tv",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('41',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Generate Payslip",  add_info: " - Generate/View", value: "41",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('41L',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Leave Settlement",  add_info: " - Generate", value: "41L",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('42',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Payment History",  add_info: " - View Payslip", value: "42",},
]},

{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('Request-To-Employer',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Request To Employer",  add_info: "", value: "Request-To-Employer",  items: [

{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('Request-For-Passport-Release',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",text:"Request For Passport Release",add_info:"",value:"Request-For-Passport-Release", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('request-passport-delete',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "request-passport-delete",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('request-passport-view',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "request-passport-view",},
]},
{ id: "", class: "role-checkbox custom-control-input ",check: "<?php if(isset($_GET['role_id'])) { if(in_array('38tvRequest-For-Others',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Request For Others",  add_info: "", value: "Request-For-Others", items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ",check: "<?php if(isset($_GET['role_id'])) { if(in_array('request-others-delete',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "request-others-delete",},
{ id: "", class: "role-checkbox custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('request-others-view',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "request-others-view",},
]},
]},


]
});

jQuery("#treeview_m2a").kendoTreeView({
checkboxes: {
checkChildren: true,
template: "<label class='custom-control custom-checkbox'><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'  />&nbsp;&nbsp;&nbsp;<span class='custom-control-indicator'></span><span class='custom-control-description'>#= item.text #</span><span class='custom-control-info'>#= item.add_info #</span></label>"
},
check: onCheck,
dataSource: [
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('53',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Settings",  add_info: "", value: "53",items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('53v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "", value: "53v",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Update", check: "<?php if(isset($_GET['role_id'])) { if(in_array('53e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",  add_info: "", value: "53e",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('54',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Constants",  add_info: "", value: "54",
items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Add", check: "<?php if(isset($_GET['role_id'])) { if(in_array('54a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",  add_info: "", value: "54a",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Update",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('54e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",  add_info: "", value: "54e",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View", check: "<?php if(isset($_GET['role_id'])) { if(in_array('54v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",  add_info: "", value: "54v",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete", check: "<?php if(isset($_GET['role_id'])) { if(in_array('54d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",  add_info: "", value: "54d",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('14',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Set Roles",  add_info: "", value: "14", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('14a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "14a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('14e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "14e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('14d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "14d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('14v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "14v",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('26',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Employees Last Login",  add_info: " - View", value: "26",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('52',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Employees Directory",  add_info: " - View", value: "52",},

{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('34',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Shift Management",  add_info: "(Office Shifts / Change & Exceptional Schedule)", value: "34", items: [
// sub 2
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('34a',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Add",  add_info: "", value: "34a",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('34e',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Edit",  add_info: "", value: "34e",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('34d',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Delete",  add_info: "", value: "34d",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('34v',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "View",  add_info: "", value: "34v",},
]},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('55',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Email Templates",  add_info: " - Update", value: "55",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('56',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Database Backup",  add_info: " - Create/Delete/Download", value: "56",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('57',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Bulk Upload",  add_info: "", value: "57",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('58',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Manual Attendance Sync",  add_info: "", value: "58",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('59',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Activity Logs",  add_info: "", value: "59",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('system-logs',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "System Logs",  add_info: "", value: "system-logs",},
{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('60',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Reports",  add_info: "", value: "60",items: [
// sub 2
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('60c',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Employees Report",  add_info: "", value: "60c",items: [
				{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('60c-view',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",  text: "View Report",  add_info: "", value: "60c-view",},
				{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('60c-salary',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",  text: "Salary Informations",  add_info: "", value: "60c-salary",},
				{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('60c-employee',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Employee Informations",  add_info: "", value: "60c-employee",},
				{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('60c-document',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Employee Documents",  add_info: "", value: "60c-document",},]
		},
		{ id: "", class: "role-checkbox-modal custom-control-input ", check: "<?php if(isset($_GET['role_id'])) { if(in_array('60b',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", text: "Uploaded Documents",  add_info: "", value: "60b",},
	]},
//
]
});



// show checked node IDs on datasource change
function onCheck() {
var checkedNodes = [],
treeView = jQuery("#treeview").data("kendoTreeView"),
message;
//checkedNodeIds(treeView.dataSource.view(), checkedNodes);
jQuery("#result").html(message);
}

</script>
<?php } ?>

