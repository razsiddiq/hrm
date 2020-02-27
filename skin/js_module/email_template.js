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
			url : site_url+"settings/email_template_list/",
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

	// edit
	$('.edit-modal-data').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var template_id = button.data('template_id');
		var modal = $(this);
	$.ajax({
		url : site_url+"settings/read_tempalte/",
		type: "GET",
		data: 'jd=1&is_ajax=1&mode=modal&data=email_template&template_id='+template_id,
		success: function (response) {
			if(response) {
				$("#ajax_modal").html(response);
			}
		}
		});
	});
});
