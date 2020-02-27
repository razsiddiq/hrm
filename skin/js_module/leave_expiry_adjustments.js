$(document).ready(function() {
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });

	xin_expiry_list();

	$("#xin-form").submit(function(e){
		e.preventDefault(); 
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=1&add_type=add_al_expiry_leaves&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					xin_expiry_list();
					toastr.success(JSON.result);					
					$('.add-form').fadeOut('slow');
					$('#xin-form')[0].reset(); // To reset form fields
					$('form#xin-form select').val('').trigger("change");
					$('.save').prop('disabled', false);
					$(".add-new-form").show();	
				}
			}
		});
	});

	$("#shift_list_search").submit(function(e){
	e.preventDefault();
	xin_expiry_list();
	});

  
	$( document ).on( "click", ".delete", function() {
		$('input[name=_token]').val($(this).data('record-id'));
		$('.delete_record').attr('action',base_url+'/al_expiry_delete/'+$(this).data('record-id'));
		 swal({
				title: "Are you sure?",
				text: "Record deleted can't be restored!",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#FF7043",
				confirmButtonText: "Yes, delete it!",
				//closeOnConfirm: false,
				//closeOnCancel: false			
				
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
								xin_expiry_list();
							 	toastr.success(JSON.result);									
							}
						}
					});
			  
			  
				}
			});
	});
   

	$(".edit-modal-data").on("show.bs.modal", function(event) {
		var button = $(event.relatedTarget);
		var adjust_id = button.data("adjust_id");
		var modal = $(this);
		if (adjust_id != null) {
			$.ajax({
				url: base_url + "/read_al_expiry_data/",
				type: "GET",
				data:
					"jd=1&is_ajax=1&mode=modal&data=al_expiry_data&adjust_id=" +
					adjust_id,
					success: function(response) {
					if (response) {
						$("#ajax_modal").html(response);
					}
				}
			});
		}
	});
   
});


function xin_expiry_list(){		
	var department_value=$('.department_value').val();
	var location_value=$('.location_value').val();	
	var user_id = $('#user_id').val();
	var adjust_type_id=$('#adjust_type_id_filter').val();

	$.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        }
	});
	
	 var xin_expiry_list = $('#xin_expiry_list').DataTable({
		"bDestroy": true,
		"iDisplayLength": 100,
		//paging: false,
		"ajax": {
			url : site_url+"timesheet/al_expiry_leaves_list/?&user_id="+user_id+"&department_value="+department_value+"&adjust_type_id="+adjust_type_id+"&location_value="+location_value,
			type : 'GET'
		},"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		},'columnDefs': [{
         'targets': 0,
         'searchable':false,
         'orderable':false        
     	 }],  buttons: [
				
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
}


function clear_filter(){
	$('.department_value').val(0).trigger("change");
	$('.location_value').val(0).trigger("change");
	$('#user_id').val('all').trigger("change");	
	$('#adjust_type_id_filter').val(0).trigger("change");	
	xin_expiry_list();
}