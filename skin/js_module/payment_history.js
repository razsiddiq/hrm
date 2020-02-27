$(document).ready(function() {
	
	
select_filter();

// detail modal data
$('.detail_modal_data').on('show.bs.modal', function (event) {
	var button = $(event.relatedTarget);
	var employee_id = button.data('employee_id');
	var pay_id = button.data('pay_id');
	var modal = $(this);
	$.ajax({
		url: site_url+'payroll/make_payment_view/',
		type: "GET",
		data: 'jd=1&is_ajax=11&mode=modal&data=pay_payment&type=pay_payment&emp_id='+employee_id+'&pay_id='+pay_id,
		success: function (response) {
			if(response) {
				$("#ajax_modal_details").html(response);
			}
		}
	});
});
});


function clear_filter(){
    var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];;
    var date = new Date();
	$('.department_value').val(0).trigger("change");
	$('.location_value').val(0).trigger("change");
	$('.visa_value').val(0).trigger("change");	
	$('#month_year').val(date.getFullYear() + ' ' + months[date.getMonth()]);
	select_filter();
}

function select_filter(){	
	var department_id=$('.department_value').val();
	var location_id=$('.location_value').val();
	var month_year=$('#month_year').val();	
	var visa_id=$('.visa_value').val();
	
	
	$.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip> ',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        }
    });
	 
	 var xin_table = $('#xin_table').DataTable({
		"bDestroy": true,		
		"paging": false,
		"ajax": {
			url : site_url+"payroll/payment_history_list/?department_id="+department_id+"&location_id="+location_id+'&month_year='+month_year+'&visa_id='+visa_id,
			type : 'GET'
		},"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		},	"order": [[ 4, "asc" ],[ 6, "asc" ]],
		
            buttons: [
                {
                    extend: 'copyHtml5',
					title: 'Payment History For '+$('#month_year').val()+' Filter By '+$('.location_value option:selected').html()+', '+$('.department_value option:selected').html(), 
                    className: 'btn btn-default',
					exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7]
                    },
				},
                {
                    extend: 'excelHtml5',
					title: 'Payment History For '+$('#month_year').val()+' Filter By '+$('.location_value option:selected').html()+', '+$('.department_value option:selected').html(), 	
                    className: 'btn btn-default',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7]
                    },
				
					
					
                },
                {
                    extend: 'pdfHtml5',
                    className: 'btn btn-default',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7],//':visible'// [ 1, 2, 3, 4, 5, 6, 7]
                    },
					message: '__MESSAGE__',
					orientation: 'landscape',
					pageSize: 'LEGAL',
					//title: 'Entry',
					header:true,
					customize: function ( doc ) {
					doc.content.forEach(function(content) {
						 if (content.style == 'message') {
							content.text = 'Payment History For '+$('#month_year').val()+' Filter By '+$('.location_value option:selected').html()+', '+$('.department_value option:selected').html()
						 }
					   })
					} 
					
                },
				{
                extend: 'print',
                text: '<i class="icon-printer position-left"></i> Print table',
                className: 'btn btn-default',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6, 7]
                },
				    message: 'Payment History For '+$('#month_year').val()+' Filter By '+$('.location_value option:selected').html()+', '+$('.department_value option:selected').html(),
					orientation: 'landscape',
					pageSize: 'LEGAL',
					//title: 'Entry',
					header:true,
					 
                },
                {
                    extend: 'colvis',
                    text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
                    className: 'btn bg-teal-400 btn-icon'
                }
            ]
        
    });
	
	
	
	var xin_table_agency = $('#xin_table_agency').DataTable({
		"bDestroy": true,		
		"paging": false,
		"ajax": {
			url : site_url+"payroll/payment_agency_list/?department_id="+department_id+"&location_id="+location_id+'&month_year='+month_year+'&visa_id='+visa_id,
			type : 'GET'
		},"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		},	"order": [[ 4, "desc" ]],
		
            buttons: [
                {
                    extend: 'copyHtml5',
					title: 'Payment History For '+$('#month_year').val()+' Filter By '+$('.location_value option:selected').html()+', '+$('.department_value option:selected').html(), 
                    className: 'btn btn-default',
					exportOptions: {
                       columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8]
                    },
				},
                {
                    extend: 'excelHtml5',
					title: 'Payment History For '+$('#month_year').val()+' Filter By '+$('.location_value option:selected').html()+', '+$('.department_value option:selected').html(), 	
                    className: 'btn btn-default',
                    exportOptions: {
                      columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8]
                    },
				
                },
                {
                    extend: 'pdfHtml5',
                    className: 'btn btn-default',
                    exportOptions: {
                       columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8],//':visible'// [ 1, 2, 3, 4, 5, 6, 7]
                    },
					message: '__MESSAGE__',
					orientation: 'landscape',
					pageSize: 'LEGAL',
					//title: 'Entry',
					header:true,
					customize: function ( doc ) {
					doc.content.forEach(function(content) {
						 if (content.style == 'message') {
							content.text = 'Payment History For '+$('#month_year').val()+' Filter By '+$('.location_value option:selected').html()+', '+$('.department_value option:selected').html()
						 }
					   })
					} 
					
                },
				{
                extend: 'print',
                text: '<i class="icon-printer position-left"></i> Print table',
                className: 'btn btn-default',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8],
                },
				    message: 'Payment History For '+$('#month_year').val()+' Filter By '+$('.location_value option:selected').html()+', '+$('.department_value option:selected').html(),
					orientation: 'landscape',
					pageSize: 'LEGAL',
					//title: 'Entry',
					header:true,
					 
                },
                {
                    extend: 'colvis',
                    text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
                    className: 'btn bg-teal-400 btn-icon'
                }
            ]
        
    });
	
	//var rowNode = xin_table.row.add( [ '','','Fiona White', 32, 'Edinburgh','','','' ] ).draw().node();
    //$( rowNode ).css( 'color', 'red' ).animate( { color: 'black' } );
	
	// Add placeholder to the datatable filter option
    $('.dataTables_filter input[type=search]').attr('placeholder','Type to filter...');


    // Enable Select2 select for the length option
    $('.dataTables_length select,.change_country_code').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
	
    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));	


	
}