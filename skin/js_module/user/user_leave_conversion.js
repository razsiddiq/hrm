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
	

	leave_conversion_availability();
	

	
	
	
	xin_table_conversion();
	
		$('.edit-modal-data-payrol').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var leave_conversion_id = button.data('leave_conversion_id');
		var modal = $(this);
    	$.ajax({	
		url : site_url+"timesheet/read_leave_conversion/",
		type: "GET",
		data: 'jd=1&is_ajax=1&mode=modal&data=leave_conversion_emp&leave_conversion_id='+leave_conversion_id,
		success: function (response) {
			if(response) {
				$("#ajax_modal_payview").html(response);
			}
		}
		});
	});
	
	$( document ).on( "click", ".delete_leave_card", function() {
	$('input[name=_token]').val($(this).data('record-id'));
	$('.delete_record').attr('action',site_url+'timesheet/delete_leave_conversion/'+$(this).data('record-id'));	
	var employee_id=$('#employee_id').val();
	var xin_table_conversion = $('#xin_table_conversion').DataTable();
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
								xin_table_conversion.ajax.reload(function(){
                        employee_leave_card(employee_id);
						toastr.success(JSON.result);
					}, true);
						
						
						}
					}
				});
		  
		  
            }
      
        });
		
	});
	
});


function xin_table_conversion(){

		var employee_id=$('#user_id').val();

$.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search:     '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        }
    });
	 var xin_table_conversion = $('#xin_table_conversion').DataTable({
		"bDestroy": true,
		"ajax": {
			url : site_url+"timesheet/user_leave_conversion_lists/?employee_id="+employee_id,
			type : 'GET'
		},"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		},"order": [[ 5, "desc" ]],	
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
	
    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));	
}