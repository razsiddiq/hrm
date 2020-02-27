$(document).ready(function() {
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
		  url : base_url+"/role_list/",
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
		
	$(".styled").uniform({
        radioClass: 'choice'
    });	

	
	$(document).on( "click", ".delete", function() {
	$('input[name=_token]').val($(this).data('record-id'));
	$('.delete_record').attr('action',base_url+'/delete/'+$(this).data('record-id'));
	 swal({
            title: "Are you sure?",
            text: "Record deleted can't be restored!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#FF7043",
            confirmButtonText: "Yes, delete it!",		
			
        },
        function(isConfirm){
            if (isConfirm) {
				var obj = $('.delete_record'), action = obj.attr('name');		
				$.ajax({
					type: "POST",
					url: $('.delete_record').attr('action'),
					data: obj.serialize()+"&is_ajax=2&form="+action,
					cache: false,
					success: function (JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
						} else {
							  xin_table.ajax.reload(function(){ 
						          toastr.success(JSON.result);
					           }, true);
						
						}
					}
				});
		  
		  
            }
      
        });
});

$(document).on( "click", ".delete_custom_role", function() {
	$('input[name=_token]').val($(this).data('record-id'));
	$('.delete_record').attr('action',base_url+'/delete_custom_role/'+$(this).data('record-id'));
	 swal({
            title: "Are you sure?",
            text: "Record deleted can't be restored!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#FF7043",
            confirmButtonText: "Yes, delete it!",		
			
        },
        function(isConfirm){
            if (isConfirm) {
				var obj = $('.delete_record'), action = obj.attr('name');		
				$.ajax({
					type: "POST",
					url: $('.delete_record').attr('action'),
					data: obj.serialize()+"&is_ajax=2&form="+action,
					cache: false,
					success: function (JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
						} else {
							      xin_form_roles();
						          toastr.success(JSON.result);
					              get_update_employees();
						
						}
					}
				});
		  
		       
            }
      
        });
});
	$(document).on( "click", ".edit_custom_role", function() {
		var emp_id=$(this).attr('rel');
		add_roles('edit',emp_id);
		
});
	// edit
	$('.edit-modal-data').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var role_id = button.data('role_id');
		var modal = $(this);
	$.ajax({
		url : base_url+"/read/",
		type: "GET",
		data: 'jd=1&is_ajax=1&mode=modal&data=role&role_id='+role_id,
		success: function (response) {
			if(response) {
				$("#ajax_modal").html(response);
			}
		}
		});
	});
xin_form_roles();	
	/* Add data */ /*Form Submit*/
	$("#xin-form").submit(function(e){
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=1&add_type=role&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					xin_table.ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.add-form').fadeOut('slow');
					$('#xin-form')[0].reset(); // To reset form fields
					$('form#xin-form select').val('').trigger("change");
					$('.save').prop('disabled', false);
					$(".add-new-form").show();
					
				}
			}
		});
	});
});

jQuery("#treeview_r1").kendoTreeView({
checkboxes: {
checkChildren: true,
template: "<label class='custom-control custom-checkbox'><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'  />&nbsp;&nbsp;&nbsp;<span class='custom-control-indicator'></span><span class='custom-control-description'>#= item.text #</span><span class='custom-control-info'>#= item.add_info #</span></label>"
},
//<label class='custom-control custom-checkbox'><input type='checkbox' class='#= item.class #' name='role_resources[]' value='#= item.value #'  /><span class='custom-control-indicator'></span><span class='custom-control-description'>#= item.text #</span><span class='custom-control-info'>#= item.add_info #</span></label>
//template: "<div class='checkbox'><label><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'>#= item.text #</label></div>"
check: onCheck,
dataSource: [// sub 1
{ id: "", class: "role-checkbox custom-control-input ", text: "Dashboard (For Admin)", add_info: "", value: "0d"},
{ id: "", class: "role-checkbox custom-control-input ", text: "Organization", add_info: "", value: "1", items: [
// sub 1
{ id: "", class: "role-checkbox custom-control-input ", text: "Company",  add_info: "", value: "3", items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "3a",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "3e",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "3d",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "3v",},
]},
{ id: "", class: "role-checkbox custom-control-input ", text: "Location",  add_info: "", value: "4", items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "4a",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "4e",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "4d",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "4v",},
]},
{ id: "", class: "role-checkbox custom-control-input ", text: "Department",  add_info: "", value: "5", items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "5a",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "5e",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "5d",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "5v",},
]},
{ id: "", class: "role-checkbox custom-control-input ", text: "Designation",  add_info: "", value: "6", items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "6a",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "6e",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "6d",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "6v",},
]},
/*{ id: "", class: "role-checkbox custom-control-input ", text: "Announcements",  add_info: "", value: "8", items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "8a",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "8e",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "8d",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "8v",},
]},
{ id: "", class: "role-checkbox custom-control-input ", text: "Policies",  add_info: "", value: "9", items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "9a",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "9e",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "9d",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "9v",},
]},
{ id: "", class: "role-checkbox custom-control-input ", text: "Expenses",  add_info: "", value: "10", items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "10a",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "10e",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "10d",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "10v",},
]},*/
]}, // sub 1 end
{ id: "", class: "role-checkbox custom-control-input ", text: "Employees",  add_info: "", value: "11",  items: [
{ id: "", class: "role-checkbox custom-control-input ", text: "Employees List",  add_info: "", value: "employees-list", items: [

	{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "employees-list-add",},
	{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "employees-list-edit",},
	{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "employees-list-delete",},
	{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "employees-list-view",},

{ id: "", class: "role-checkbox custom-control-input ", text: "Basic Information",  add_info: "", value: "basic-info", items: [
// sub 3
	{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "basic-info-edit",},
	{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "basic-info-view",},
]},
{ id: "", class: "role-checkbox custom-control-input ", text: "Documents",  add_info: "", value: "documents", items: [
	// sub 3
		{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "documents-add",},
		{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "documents-edit",},
		{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "documents-delete",},
		{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "documents-view",},
]},
{ id: "", class: "role-checkbox custom-control-input ", text: "Offer Letter",  add_info: "", value: "offer-letter", items: [
	// sub 3
		// { id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "offer-letter-add",},
		{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "offer-letter-edit",},
		{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "offer-letter-delete",},
		{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "offer-letter-view",},
]},
{ id: "", class: "role-checkbox custom-control-input ", text: "Contract",  add_info: "", value: "contract", items: [
	// sub 3
		{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "contract-add",},
		{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "contract-edit",},
		{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "contract-delete",},
		{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "contract-view",},
]},
{ id: "", class: "role-checkbox custom-control-input ", text: "Pay Structure",  add_info: "", value: "pay-structure", items: [
	// sub 3
		{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "pay-structure-add",},
		{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "pay-structure-edit",},
		{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "pay-structure-delete",},
		{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "pay-structure-view",},
]},
{ id: "", class: "role-checkbox custom-control-input ", text: "Bank Account",  add_info: "", value: "bank-account", items: [
	// sub 3
		{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "bank-account-add",},
		{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "bank-account-edit",},
		{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "bank-account-delete",},
		{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "bank-account-view",},
]},
{ id: "", class: "role-checkbox custom-control-input ", text: "Emergency Contacts",  add_info: "", value: "emergency-contacts", items: [
	// sub 3
		{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "emergency-contacts-add",},
		{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "emergency-contacts-edit",},
		{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "emergency-contacts-delete",},
		{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "emergency-contacts-view",},
]},
{ id: "", class: "role-checkbox custom-control-input ", text: "Qualification",  add_info: "", value: "qualification", items: [
	// sub 3
		{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "qualification-add",},
		{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "qualification-edit",},
		{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "qualification-delete",},
		{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "qualification-view",},
]},
{ id: "", class: "role-checkbox custom-control-input ", text: "Work Experience",  add_info: "", value: "work-experience", items: [
	// sub 3
		{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "work-experience-add",},
		{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "work-experience-edit",},
		{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "work-experience-delete",},
		{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "work-experience-view",},
]},
{ id: "", class: "role-checkbox custom-control-input ", text: "Change Password",  add_info: "", value: "change-password", items: [
	// sub 3
]},

{ id: "", class: "role-checkbox custom-control-input ", text: "Request Approval",  add_info: "", value: "request-approval", items: [
	
]},

]},      
/*{ id: "", class: "role-checkbox custom-control-input ", text: "Awards",  add_info: "", value: "15", items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "15a",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "15e",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "15d",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "15v",},
]},*/
{ id: "", class: "role-checkbox custom-control-input ", text: "Transfers",  add_info: "", value: "16", items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "16a",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "16e",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "16d",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "16v",},
]},
{ id: "", class: "role-checkbox custom-control-input ", text: "Resignations",  add_info: "", value: "17", items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "17a",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "17e",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "17d",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "17v",},
]},
{ id: "", class: "role-checkbox custom-control-input ", text: "Travels",  add_info: "", value: "18",items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "18a",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "18e",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "18d",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "18v",},
]},

{ id: "", class: "role-checkbox custom-control-input ", text: "Promotions",  add_info: "", value: "20", items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "20a",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "20e",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "20d",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "20v",},
]},
{ id: "", class: "role-checkbox custom-control-input ", text: "Complaints",  add_info: "", value: "21", items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "21a",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "21e",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "21d",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "21v",},
]},
{ id: "", class: "role-checkbox custom-control-input ", text: "Warnings",  add_info: "", value: "22", items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "22a",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "22e",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "22d",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "22v",},
]},
{ id: "", class: "role-checkbox custom-control-input ", text: "Terminations  & Suspensions",  add_info: "", value: "23", items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "23a",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "23e",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "23d",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "23v",},
]},

{ id: "", class: "role-checkbox custom-control-input ", text: "Employees Exit",  add_info: "", value: "27", items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "27a",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "27e",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "27d",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "27v",},
]}
]},
/*{ id: "", class: "role-checkbox custom-control-input ", text: "Performance",  add_info: "", value: "240",  items: [
{ id: "", class: "role-checkbox custom-control-input ", text: "Performance Indicator",  add_info: "", value: "24", items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "24a",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "24e",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "24d",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "24v",},
]},
{ id: "", class: "role-checkbox custom-control-input ", text: "Give Performance Appraisal",  add_info: "", value: "25", items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "25a",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "25e",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "25d",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "25v",},
]},
]},*/
{ id: "", class: "role-checkbox custom-control-input ", text: "Timesheet",  add_info: "", value: "28",  items: [
//{ id: "", class: "role-checkbox custom-control-input ", text: "Attendance",  add_info: "View", value: "29"},
{ id: "", class: "role-checkbox custom-control-input ", text: "Attendance List",  add_info: " - View", value: "30",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Manual Attendance (OB)",  add_info: "", value: "31", items: [

{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "31a",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "31e",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "31d",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "31v",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Approve/Decline",  add_info: "", value: "31ad",},
]},
{ id: "", class: "role-checkbox custom-control-input ", text: "Leaves",  add_info: "", value: "32", items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "32a",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "32e",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "32d",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "32v",},

]},
{ id: "", class: "role-checkbox custom-control-input ", text: "Mails",  add_info: " (Only for HR<br>Manual Attendance Approval / <br>leave Approval)", value: "32m",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Employee's Leave Card",  add_info: " - View", value: "32lv",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Holidays",  add_info: "", value: "35", items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "35a",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "35e",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "35d",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "35v",},
]},
{ id: "", class: "role-checkbox custom-control-input ", text: "Biometric Users List",  add_info: "", value: "bio-user", items: [
{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "bio-user-add",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "bio-user-edit",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "bio-user-delete",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "bio-user-view",},
]},
{ id: "", class: "role-checkbox custom-control-input ", text: "AL Expiry Adjustments",  add_info: "", value: "al-expiry", items: [
{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "al-expiry-add",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "al-expiry-edit",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "al-expiry-delete",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "al-expiry-view",},
]},
]},
{ id: "", class: "role-checkbox custom-control-input ", text: "Payroll",  add_info: "", value: "36",  items: [
{ id: "", class: "role-checkbox custom-control-input ", text: "Payroll",  add_info: " (Adjustmeents)", value: "38", items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "38a",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "38e",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "38d",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "38v",},
]},
{ id: "", class: "role-checkbox custom-control-input ", text: "Payroll Templeate",  add_info: "", value: "38tv", items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "38te",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "38tv",},
]},
//{ id: "", class: "role-checkbox custom-control-input ", text: "Hourly Wages",  add_info: "Add/Edit/Delete", value: "39",},
//{ id: "", class: "role-checkbox custom-control-input ", text: "Manage Salary",  add_info: "Update/View", value: "40",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Generate Payslip",  add_info: " - Generate/View", value: "41",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Leave Settlement",  add_info: " - Generate", value: "41L",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Payment History",  add_info: " - View Payslip", value: "42",},
]},

{ id: "", class: "role-checkbox custom-control-input ", text: "Request To Employer",  add_info: "", value: "Request-To-Employer",  items: [
{ id: "", class: "role-checkbox custom-control-input ", text: "Request For Passport Release",  add_info: "", value: "Request-For-Passport-Release", items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "request-passport-delete",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "request-passport-view",},
]},

{ id: "", class: "role-checkbox custom-control-input ", text: "Request For Others",  add_info: "", value: "Request-For-Others", items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "request-others-delete",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "request-others-view",},
]},

]},

/*{ id: "", class: "role-checkbox custom-control-input ", text: "Projects",  add_info: "", value: "7", items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "7a",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "7e",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "7d",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "7v",},
]},
{ id: "", class: "role-checkbox custom-control-input ", text: "Worksheet (Tasks)",  add_info: "", value: "33", items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "33a",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "33e",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "33d",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "33v",},
]},
{ id: "", class: "role-checkbox custom-control-input ", text: "Tickets",  add_info: "", value: "19", items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "19a",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "19e",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "19d",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "19v",},
]},*/
/*
{ id: "", class: "role-checkbox custom-control-input ", text: "Recruitment",  add_info: "", value: "43",  items: [
{ id: "", class: "role-checkbox custom-control-input ", text: "Jobs Listing <small>frontend</small>",  add_info: "View jobs", value: "44",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Job Posts",  add_info: "Add/Edit/Delete", value: "45",},
{ id: "", class: "role-checkbox custom-control-input ",text: "Job Candidates",  add_info: "Update Status/Delete", value: "46",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Job Interviews",  add_info: "Add/Delete", value: "47",},
]},*/
//
]
});

jQuery("#treeview_r2").kendoTreeView({
checkboxes: {
checkChildren: true,
template: "<label class='custom-control custom-checkbox'><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'  />&nbsp;&nbsp;&nbsp;<span class='custom-control-indicator'></span><span class='custom-control-description'>#= item.text #</span><span class='custom-control-info'>#= item.add_info #</span></label>"
},
check: onCheck,
dataSource: [
/*{ id: "", class: "role-checkbox custom-control-input ", text: "Training",  add_info: "", value: "48",  items: [
{ id: "", class: "role-checkbox custom-control-input ", text: "Training List",  add_info: "", value: "49",items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "49a",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "49e",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "49d",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "49v",},
]},
{ id: "", class: "role-checkbox custom-control-input ", text: "Training Type",  add_info: "", value: "50", items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "50a",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "50e",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "50d",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "50v",},
]},
{ id: "", class: "role-checkbox custom-control-input ", text: "Trainers List",  add_info: "", value: "51", items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "51a",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "51e",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "51d",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "51v",},
]},
]},*/


{ id: "", class: "role-checkbox custom-control-input ", text: "Settings",  add_info: "", value: "53",items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "53v",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Update",  add_info: "", value: "53e",},
]},
{ id: "", class: "role-checkbox custom-control-input ", text: "Constants",  add_info: "", value: "54",items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "54a",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Update",  add_info: "", value: "54e",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "54v",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "54d",},
]},
{ id: "", class: "role-checkbox custom-control-input ", text: "Set Roles",  add_info: "", value: "14", items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "14a",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "14e",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "14d",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "14v",},
]},
{ id: "", class: "role-checkbox custom-control-input ", text: "Shift Management",  add_info: "(Office Shifts / Change & Exceptional Schedule)", value: "34", items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Add",  add_info: "", value: "34a",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Edit",  add_info: "", value: "34e",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Delete",  add_info: "", value: "34d",},
{ id: "", class: "role-checkbox custom-control-input ", text: "View",  add_info: "", value: "34v",},
]},
{ id: "", class: "role-checkbox custom-control-input ", text: "Employees Last Login",  add_info: " - View", value: "26",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Employees Directory",  add_info: " - View", value: "52",},

{ id: "", class: "role-checkbox custom-control-input ", text: "Email Templates",  add_info: " - Update", value: "55",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Database Backup",  add_info: " - Create/Delete/Download", value: "56",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Bulk upload",  add_info: "", value: "57",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Manual Attendance Sync",  add_info: "", value: "58",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Activity Logs",  add_info: "", value: "59",},
{ id: "", class: "role-checkbox custom-control-input ", text: "System Logs",  add_info: "", value: "system-logs",},
{ id: "", class: "role-checkbox custom-control-input ", text: "Reports",  add_info: "", value: "60",items: [
// sub 2
{ id: "", class: "role-checkbox custom-control-input ", text: "Employees Report",  add_info: "", value: "60c",items: [
		{ id: "", class: "role-checkbox custom-control-input ", text: "View Report",  add_info: "", value: "60c-view",},
		{ id: "", class: "role-checkbox custom-control-input ", text: "Salary Informations",  add_info: "", value: "60c-salary",},
		{ id: "", class: "role-checkbox custom-control-input ", text: "Employee Informations",  add_info: "", value: "60c-employee",},
		{ id: "", class: "role-checkbox custom-control-input ", text: "Employee Documents",  add_info: "", value: "60c-document",},
]
},
{ id: "", class: "role-checkbox custom-control-input ", text: "Uploaded Documents",  add_info: "", value: "60b",},
]},
//
]
});
		
// show checked node IDs on datasource change
function onCheck() {
var checkedNodes = [],
		treeView = jQuery("#treeview2").data("kendoTreeView"),
		message;		
		jQuery("#result").html(message);		
		console.log(checkedNodes);
}
/*
function onCheck() {
            var checkedNodes = [];
            var treeView = $("#treeview_r1").data("kendoTreeView");
		
            getCheckedNodes(treeView.dataSource.view(), checkedNodes);
            
        }
function getCheckedNodes(nodes, checkedNodes) {
            var node;


            for (var i = 0; i < nodes.length; i++) {
                node = nodes[i];

                if (node.checked) {
                    checkedNodes.push({ text: node.text, id: node.id });
					
                }

                if (node.hasChildren) {
                    getCheckedNodes(node.children.view(), checkedNodes);
                }
            }
			console.log(checkedNodes);
        }*/		
		
$(document).ready(function(){
	$("#role_access").change(function(){
		var sel_val = $(this).val();
		if(sel_val=='1') {
			$('.role-checkbox').prop('checked', true);
		} else {
		$('.role-checkbox').prop('checked', false);	
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


function clear_roles(){
$('#employee_id').val(0).trigger("change");
}

function add_roles(val,eid){

if(eid==''){
var emp_id=$('#employee_id').val();
}else{
var emp_id=eid;
}
if(emp_id!=0){
	$.ajax({
		url : base_url+"/read/",
		type: "GET",
		data: 'jd=1&is_ajax=1&mode=modal&data=add_custom_role&role_id='+emp_id+'&emp_id='+emp_id+'&vals='+val,
		success: function (response) {
			if(response) {
				$("#ajax_modal_payview").html(response);
			}
		}
		});
	   $('.edit-modal-data-payrol').modal('show');
}else{
	
	 swal({
            title: "choose the employee",
            text: "",
            html: true,
			confirmButtonColor: "#26A69A",
        });
}


}


function xin_form_roles(){

var xin_table_custom = $('#xin_table_custom').DataTable({
		"bDestroy": true,
		"ajax": {
		  url : base_url+"/custom_role_list/",
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
}

function get_update_employees(){
	
	$.ajax({
		url : site_url+"roles/get_role_employees/",
		type: "GET",
		success: function (response) {        
			if(response) {
				$("#employee_id_div").html(response);
				  $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
				  $('[data-plugin="select_hrm"]').select2({ width:'100%' });
			}
		}
		});
}
