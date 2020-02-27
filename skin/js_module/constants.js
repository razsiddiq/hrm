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
	
	
	
	// Add placeholder to the datatable filter option
  

	// On page load:

	 var xin_table_salary_fields = $('#xin_table_salary_fields').DataTable({
		"bDestroy": true,
		"ajax": {
			url : site_url+"settings/country_grouped/",
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
	
	
	 var xin_table_travel_arr_type = $('#xin_table_travel_arr_type').DataTable({
		"bDestroy": true,
		"ajax": {
			url : site_url+"settings/travel_arr_type_list/",
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
	
	
	
	
	var xin_table_tax_type = $('#xin_table_tax_type').DataTable({
		"bDestroy": true,
		//"iDisplayLength": 5,
		//"aLengthMenu": [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],
		"ajax": {
            url : site_url+"settings/tax_type_list/",
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
	
	
	var xin_table_exit_type = $('#xin_table_exit_type').DataTable({
		"bDestroy": true,
		//"iDisplayLength": 5,
		//"aLengthMenu": [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],
		"ajax": {
            url : site_url+"settings/exit_type_list/",
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
	
	var xin_table_job_type = $('#xin_table_job_type').DataTable({
		"bDestroy": true,
		//"iDisplayLength": 5,
		//"aLengthMenu": [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],
		"ajax": {
            url : site_url+"settings/job_type_list/",
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
	
	var xin_table_company_type = $('#xin_table_company_type').DataTable({
		"bDestroy": true,
		//"iDisplayLength": 5,
		//"aLengthMenu": [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],
		"ajax": {
            url : site_url+"settings/company_type_list/",
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
	
	var xin_table_expense_type = $('#xin_table_expense_type').DataTable({
		"bDestroy": true,
		//"iDisplayLength": 5,
		//"aLengthMenu": [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],
		"ajax": {
            url : site_url+"settings/expense_type_list/",
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
	
	var xin_table_termination_type = $('#xin_table_termination_type').DataTable({
		"bDestroy": true,
		//"iDisplayLength": 5,
		//"aLengthMenu": [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],
		"ajax": {
            url : site_url+"settings/termination_type_list/",
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
	
	var xin_table_warning_type = $('#xin_table_warning_type').DataTable({
		"bDestroy": true,
		"ajax": {
            url : site_url+"settings/warning_type_list/",
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
	
	var xin_table_leave_type = $('#xin_table_leave_type').DataTable({
		"bDestroy": true,
		//"iDisplayLength": 5,
		//"aLengthMenu": [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],
		"ajax": {
            url : site_url+"settings/leave_type_list/",
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

	var xin_table_salary_type = $('#xin_table_salary_type').DataTable({
		"bDestroy": true,
		//"iDisplayLength": 25,
		//"aLengthMenu": [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],
		"ajax": {
            url : site_url+"settings/salary_type_list/",
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

	var xin_table_award_type = $('#xin_table_award_type').DataTable({
		"bDestroy": true,
		//"iDisplayLength": 5,
		//"aLengthMenu": [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],
		"ajax": {
            url : site_url+"settings/award_type_list/",
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
	

	var xin_table_ob_type = $('#xin_table_ob_type').DataTable({
		"bDestroy": true,
		//"iDisplayLength": 5,
		//"aLengthMenu": [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],
		"ajax": {
            url : site_url+"settings/ob_type_list/",
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


	var xin_table_country_list = $('#xin_table_country_list').DataTable({
		"bDestroy": true,
		//"iDisplayLength": 5,
		//"aLengthMenu": [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],
		"ajax": {
            url : site_url+"settings/get_country_list/",
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



	var xin_table_document_type = $('#xin_table_document_type').DataTable({
		"bDestroy": true,
		//"iDisplayLength": 5,
		//"aLengthMenu": [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],
		"ajax": {
            url : site_url+"settings/document_type_list/",
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

	 var xin_table_contract_type = $('#xin_table_contract_type').DataTable({
		"bDestroy": true,
		"ajax": {
			url : site_url+"settings/contract_type_list/",
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
		
	 var xin_table_education_level = $('#xin_table_education_level').DataTable({
		"bDestroy": true,
		"ajax": {
			url : site_url+"settings/education_level_list/",
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
	
	 var xin_table_education_level = $('#xin_table_education_level').DataTable({
		"bDestroy": true,
		"ajax": {
			url : site_url+"settings/education_level_list/",
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
	
	 var xin_table_qualification_language = $('#xin_table_qualification_language').DataTable({
		"bDestroy": true,
		"ajax": {
			url : site_url+"settings/qualification_language_list/",
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
	 var xin_table_qualification_skill = $('#xin_table_qualification_skill').DataTable({
		"bDestroy": true,
		"ajax": {
			url : site_url+"settings/qualification_skill_list/",
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

	
	 var xin_table_payment_method = $('#xin_table_payment_method').DataTable({
		"bDestroy": true,
		"ajax": {
			url : site_url+"settings/payment_method_list/",
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
	
	var xin_table_currency_type = $('#xin_table_currency_type').DataTable({
		"bDestroy": true,
		"ajax": {
			url : site_url+"settings/currency_type_list/",
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
	
	
	var xin_table_visa_under = $('#xin_table_visa_under').DataTable({
		"bDestroy": true,
		"ajax": {
			url : site_url+"settings/visa_under_list/",
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
	
	var xin_table_medical_card_type = $('#xin_table_medical_card_type').DataTable({
		"bDestroy": true,
		"ajax": {
			url : site_url+"settings/medical_card_type_list/",
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


		
	jQuery("#document_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=15&data=document_type_info&type=document_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_document_type.ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.icon-spinner3').hide();
					jQuery('form#document_type_info select').val('').trigger("change");
					jQuery('#document_type_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
	
	jQuery("#contract_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=16&data=contract_type_info&type=contract_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_contract_type.ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.icon-spinner3').hide();
					jQuery('#contract_type_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
	
	jQuery("#payment_method_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=17&data=payment_method_info&type=payment_method_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					jQuery('.save').prop('disabled', false);
					$('.icon-spinner3').hide();
				} else {
					xin_table_payment_method.ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.icon-spinner3').hide();
					jQuery('#payment_method_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
	
	jQuery("#edu_level_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=18&data=edu_level_info&type=edu_level_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					jQuery('.save').prop('disabled', false);
					$('.icon-spinner3').hide();
				} else {
					xin_table_education_level.ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.icon-spinner3').hide();
					jQuery('#edu_level_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
	
	jQuery("#edu_language_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=19&data=edu_language_info&type=edu_language_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					jQuery('.save').prop('disabled', false);
					$('.icon-spinner3').hide();
				} else {
					xin_table_qualification_language.ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.icon-spinner3').hide();
					jQuery('#edu_language_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
	
	jQuery("#edu_skill_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=20&data=edu_skill_info&type=edu_skill_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					jQuery('.save').prop('disabled', false);
					$('.icon-spinner3').hide();
				} else {
					xin_table_qualification_skill.ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.icon-spinner3').hide();
					jQuery('#edu_skill_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
	
	jQuery("#leave_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=23&data=leave_type_info&type=leave_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					jQuery('.save').prop('disabled', false);
					$('.icon-spinner3').hide();
				} else {
					xin_table_leave_type.ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.icon-spinner3').hide();
					jQuery('#leave_type_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});

    jQuery("#salary_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=23a&data=salary_type_info&type=salary_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					jQuery('.save').prop('disabled', false);
					$('.icon-spinner3').hide();
				} else {
					xin_table_salary_type.ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.icon-spinner3').hide();
					jQuery('#salary_type_info')[0].reset(); // To reset form fields
					//$('form#salary_type_info select').val('').trigger("change");
				    $.ajax({
						url: site_url+'settings/salary_parent_type/',
						type: "GET",
						success: function (response) {
							if(response) {
							   $("#type_parent").html(response);
							}
					$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
					$('[data-plugin="select_hrm"]').select2({ width:'100%' });
										}
					});
				    $('.type_action_area').show();
					jQuery('.save').prop('disabled', false);






				}
			}
		});
	});
	
	
	jQuery("#travel_arr_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=45&data=travel_arr_type_info&type=travel_arr_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					jQuery('.save').prop('disabled', false);
					$('.icon-spinner3').hide();
				} else {
					xin_table_travel_arr_type.ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.icon-spinner3').hide();
					jQuery('#travel_arr_type_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
	
	jQuery("#award_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=22&data=award_type_info&type=award_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_award_type.ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.icon-spinner3').hide();
					jQuery('#award_type_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});

	jQuery("#ob_type_info").submit(function(e){
		/*Form Submit*/
		e.preventDefault();
			var obj = jQuery(this), action = obj.attr('name');
			jQuery('.save').prop('disabled', true);
			$('.icon-spinner3').show();
			jQuery.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=22&data=ob_type_info&type=ob_type_info&form="+action,
				cache: false,
				success: function (JSON) {
					if (JSON.error != '') {
						toastr.error(JSON.error);
						$('.icon-spinner3').hide();
						jQuery('.save').prop('disabled', false);
					} else {
						xin_table_ob_type.ajax.reload(function(){ 
							toastr.success(JSON.result);
						}, true);
						$('.icon-spinner3').hide();
						jQuery('#ob_type_info')[0].reset(); // To reset form fields
						jQuery('.save').prop('disabled', false);
					}
				}
			});
		});

	
	jQuery("#warning_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=24&data=warning_type_info&type=warning_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_warning_type.ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.icon-spinner3').hide();
					jQuery('#warning_type_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
	
	jQuery("#termination_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=25&data=termination_type_info&type=termination_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_termination_type.ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.icon-spinner3').hide();
					jQuery('#termination_type_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
	
	jQuery("#expense_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=26&data=expense_type_info&type=expense_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_expense_type.ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.icon-spinner3').hide();
					jQuery('#expense_type_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
	
	jQuery("#job_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=27&data=job_type_info&type=job_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_job_type.ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.icon-spinner3').hide();
					jQuery('#job_type_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
	
	jQuery("#company_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=27&data=company_type_info&type=company_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_company_type.ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.icon-spinner3').hide();
					jQuery('#company_type_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
	
	jQuery("#exit_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=28&data=exit_type_info&type=exit_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_exit_type.ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.icon-spinner3').hide();
					jQuery('#exit_type_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
	
	
	jQuery("#tax_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=28&data=tax_type_info&type=tax_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_tax_type.ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.icon-spinner3').hide();
					jQuery('form#tax_type_info select').val('').trigger("change");
					jQuery('#tax_type_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
	
	
	jQuery("#currency_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=28&data=currency_type_info&type=currency_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_currency_type.ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.icon-spinner3').hide();
					jQuery('#currency_type_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});

	
	
	$(".customformadd").submit(function(e){
	var name=$(this).attr('name');
    
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=1&add_type="+name+"&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					if(name=='visa_under_info'){
 
						$('#visa_under_info')[0].reset();
					
					xin_table_visa_under.ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					}else if(name=='medical_card_type_info'){
						
						$('#medical_card_type_info')[0].reset();
						$('form#medical_card_type_info select').val('').trigger("change");
						xin_table_medical_card_type.ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
						
					}
					
					$('.save').prop('disabled', false);	
				}
			}
		});
	});
	
	/*
		$("#delete_visa_record").submit(function(e){
	    e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&type=delete_visa_record&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					xin_table_visa_under.ajax.reload(function(){ 
					$('.delete-modal1').modal('toggle');
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);	
				}
			}
		});
	});
   
   
    
		$("#delete_medical_card_record").submit(function(e){
	    e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&type=delete_medical_card_record&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					xin_table_medical_card_type.ajax.reload(function(){ 
					$('.delete-modal2').modal('toggle');
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);	
				}
			}
		});
	});*/
	
	

	  $('.edit-modal-data').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var field_id = button.data('field_id');
		var field_type = button.data('field_type');
		var field_name = button.data('field_name');
		if(field_type == 'salary_field_structure'){
			var field_add = '&data=ed_salary_field&type=ed_salary_field&name='+field_name+'&';
		} 

		var modal = $(this);
		$.ajax({
			url: site_url+'settings/constants_read/',
			type: "GET",
			data: 'jd=1'+field_add+'field_id='+field_id,
			success: function (response) {
				if(response) {
					$("#ajax_modal").html(response);
				}
			}
		});
   });


	  $('.edit_setting_datail').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var field_id = button.data('field_id');
		var field_type = button.data('field_type');
	
		if(field_type == 'document_type'){
			var field_add = '&data=ed_document_type&type=ed_document_type&';
		} else if(field_type == 'contract_type'){
			var field_add = '&data=ed_contract_type&type=ed_contract_type&';
		} else if(field_type == 'payment_method'){
			var field_add = '&data=ed_payment_method&type=ed_payment_method&';
		} else if(field_type == 'education_level'){
			var field_add = '&data=ed_education_level&type=ed_education_level&';
		} else if(field_type == 'qualification_language'){
			var field_add = '&data=ed_qualification_language&type=ed_qualification_language&';
		} else if(field_type == 'qualification_skill'){
			var field_add = '&data=ed_qualification_skill&type=ed_qualification_skill&';
		} else if(field_type == 'award_type'){
			var field_add = '&data=ed_award_type&type=ed_award_type&';
		} else if(field_type == 'leave_type'){
			var field_add = '&data=ed_leave_type&type=ed_leave_type&';
		} else if(field_type == 'warning_type'){
			var field_add = '&data=ed_warning_type&type=ed_warning_type&';
		} else if(field_type == 'termination_type'){
			var field_add = '&data=ed_termination_type&type=ed_termination_type&';
		} else if(field_type == 'expense_type'){
			var field_add = '&data=ed_expense_type&type=ed_expense_type&';
		} else if(field_type == 'job_type'){
			var field_add = '&data=ed_job_type&type=ed_job_type&';
		} else if(field_type == 'company_type'){
			var field_add = '&data=ed_company_type&type=ed_company_type&';
		} else if(field_type == 'exit_type'){
			var field_add = '&data=ed_exit_type&type=ed_exit_type&';
		} else if(field_type == 'travel_arr_type'){
			var field_add = '&data=ed_travel_arr_type&type=ed_travel_arr_type&';
		} else if(field_type == 'currency_type'){
			var field_add = '&data=ed_currency_type&type=ed_currency_type&';
		} else if(field_type == 'salary_type'){
			var field_add = '&data=ed_salary_type&type=ed_salary_type&';
		} else if(field_type == 'visa_under_type'){
			var field_add = '&data=ed_visa_under&type=ed_visa_under&';
		} else if(field_type == 'medical_card_type'){
			var field_add = '&data=ed_medical_card_type&type=ed_medical_card_type&';
		} else if(field_type == 'tax_type'){
			var field_add = '&data=ed_tax_type&type=ed_tax_type&';
		} else if(field_type == 'ob_type'){
			var field_add = '&data=ed_ob_type&type=ed_ob_type&';
		} 

		
		var modal = $(this);
		$.ajax({
			url: site_url+'settings/constants_read/',
			type: "GET",
			data: 'jd=1'+field_add+'field_id='+field_id,
			success: function (response) {
				if(response) {
					$('.icon-spinner3').hide();
					$("#ajax_setting_info").html(response);
				}
			}
		});
   });
	
	
	$( document ).on( "click", ".delete", function() {
	
	$('input[name=_token]').val($(this).data('record-id'));
	$('input[name=token_type]').val($(this).data('token_type'));
	$('.delete_record').attr('action',site_url+'settings/delete_'+$(this).data('token_type')+'/'+$(this).data('record-id'))+'/';
    $('.icon-spinner3').show();
  
    var tk_type = $('#token_type').val();
	$('.icon-spinner3').show();
	if(tk_type == 'document_type'){
		var field_add = '&is_ajax=9&data=delete_document_type&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'contract_type'){
		var field_add = '&is_ajax=10&data=delete_contract_type&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'payment_method'){
		var field_add = '&is_ajax=11&data=delete_payment_method&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'education_level'){
		var field_add = '&is_ajax=12&data=delete_education_level&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'qualification_language'){
		var field_add = '&is_ajax=13&data=delete_qualification_language&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'qualification_skill'){
		var field_add = '&is_ajax=14&data=delete_qualification_skill&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'award_type'){
		var field_add = '&is_ajax=31&data=delete_award_type&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'leave_type'){
		var field_add = '&is_ajax=32&data=delete_leave_type&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'warning_type'){
		var field_add = '&is_ajax=33&data=delete_warning_type&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'termination_type'){
		var field_add = '&is_ajax=34&data=delete_termination_type&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'expense_type'){
		var field_add = '&is_ajax=35&data=delete_expense_type&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'job_type'){
		var field_add = '&is_ajax=36&data=delete_job_type&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'company_type'){
		var field_add = '&is_ajax=36&data=delete_company_type&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'exit_type'){
		var field_add = '&is_ajax=37&data=delete_exit_type&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'travel_arr_type'){
		var field_add = '&is_ajax=47&data=delete_travel_arr_type&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'currency_type'){
		var field_add = '&is_ajax=47&data=delete_currency_type&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'salary_type'){
		var field_add = '&is_ajax=32a&data=delete_salary_type&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'visa_under'){
		var field_add = '&is_ajax=32a&data=delete_medical_card_record&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'medical_card_type'){
		var field_add = '&is_ajax=32a&data=delete_medical_card_record&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'tax_type'){
		var field_add = '&is_ajax=32a&data=delete_tax_type&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'ob_type'){
		var field_add = '&is_ajax=31&data=delete_ob_type&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	}
	
	
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
				
					$('.icon-spinner3').hide();
					$('#'+tb_name).dataTable().api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
						}
					}
				});
		  
		  
            }

        });


    });


	/* Delete data 
	$("#delete_record").submit(function(e){
	var tk_type = $('#token_type').val();
	$('.icon-spinner3').show();
	if(tk_type == 'document_type'){
		var field_add = '&is_ajax=9&data=delete_document_type&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'contract_type'){
		var field_add = '&is_ajax=10&data=delete_contract_type&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'payment_method'){
		var field_add = '&is_ajax=11&data=delete_payment_method&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'education_level'){
		var field_add = '&is_ajax=12&data=delete_education_level&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'qualification_language'){
		var field_add = '&is_ajax=13&data=delete_qualification_language&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'qualification_skill'){
		var field_add = '&is_ajax=14&data=delete_qualification_skill&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'award_type'){
		var field_add = '&is_ajax=31&data=delete_award_type&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'leave_type'){
		var field_add = '&is_ajax=32&data=delete_leave_type&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'warning_type'){
		var field_add = '&is_ajax=33&data=delete_warning_type&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'termination_type'){
		var field_add = '&is_ajax=34&data=delete_termination_type&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'expense_type'){
		var field_add = '&is_ajax=35&data=delete_expense_type&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'job_type'){
		var field_add = '&is_ajax=36&data=delete_job_type&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'exit_type'){
		var field_add = '&is_ajax=37&data=delete_exit_type&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'travel_arr_type'){
		var field_add = '&is_ajax=47&data=delete_travel_arr_type&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'currency_type'){
		var field_add = '&is_ajax=47&data=delete_currency_type&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'salary_type'){
		var field_add = '&is_ajax=32a&data=delete_salary_type&type=delete_record&';
		var tb_name = 'xin_table_'+tk_type;
	}

	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$.ajax({
			url: e.target.action,
			type: "post",
			data: '?'+obj.serialize()+field_add+"form="+action,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.icon-spinner3').hide();
				} else {
					$('.delete-modal').modal('toggle');
					$('.icon-spinner3').hide();
					$('#'+tb_name).dataTable().api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					
				}
			}
		});
	});   
	
	*/
			
	$(".nav-tabs-link").click(function(){
		var config_id = $(this).data('config');
		var config_block = $(this).data('config-block');
		$('.nav-item-link').removeClass('active-link');
		$('.current-tab').hide();
		$('#'+config_block).show();
		$('#config_'+config_id).addClass('active-link');
	});	
	
	
	  $('.dataTables_filter input[type=search]').attr('placeholder','Type to filter...');


    // Enable Select2 select for the length option
    $('.dataTables_length select,.change_country_code').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
	
    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));		
});




function salary_parent_type(){
var htm=$(".salary_parent_type option:selected").val();
if(htm==0){
	 $('.type_action_area').show();
		}else{
	 $('.type_action_area').hide();
	}
}

function ed_salary_parent_type(){
var htm=$(".ed_salary_parent_type option:selected").val();
if(htm==0){
	 $('.ed_type_action_area').show();
		}else{
	 $('.ed_type_action_area').hide();
	}
}


function updateCountry(id){

	if(id!=''){
		var val = $('#numcode_'+id).val();		
		jQuery('.numcode_'+id).prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: site_url+"settings/update_country/",
			data: {'country_id': id, 'numcode':val},
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.icon-spinner3').hide();
					jQuery('.numcode_'+id).prop('disabled', false);
				} else {					
					toastr.success(JSON.result);					
					$('.icon-spinner3').hide();
					jQuery('.numcode_'+id).prop('disabled', false);
				}
			}
		});


	}

}