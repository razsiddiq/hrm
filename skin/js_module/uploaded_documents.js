$(document).ready(function() {
	$('[data-plugin="xin_select"]').select2($(this).attr('data-options'));	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	
	$(".styled").uniform({
        radioClass: 'choice'
    });
	
 if (Array.prototype.forEach) {
        var elems = Array.prototype.slice.call(document.querySelectorAll('.switchery'));
        elems.forEach(function(html) {
            var switchery = new Switchery(html);
        });
    }
    else {
        var elems = document.querySelectorAll('.switchery');
        for (var i = 0; i < elems.length; i++) {
            var switchery = new Switchery(elems[i]);
        }
    }
	
	$(".location_value,.department_value,.medical_card_type,.visa_value").change(function(){	
		xin_table_employees();
	});
	
});
function xin_forms(){
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
		"iDisplayLength": 50,
		"ajax": {
			url : base_url+"/uploaded_document_list/",
			type : 'GET',
		},"fnDrawCallback": function(settings){
			$('[data-toggle="tooltip"]').tooltip();

			 $('[data-popup=popover-custom]').popover({
				 // trigger: 'focus',
				 template: '<div class="popover"><div class="arrow"></div><h3 class="popover-title bg-danger"></h3><div class="popover-content"></div></div>'
			 });
		},	
		
		/*buttons: {
		buttons: [{
		  extend: 'excelHtml5',
		  title: 'Uploaded Documents Report',
		  className: 'btn btn-default',
		  exportOptions: {
                        columns: ':visible'
          },
		  customize: function (xlsx) {
			  
			var sheet = xlsx.xl.worksheets['sheet1.xml'];
			console.log(sheet);
			$('row:first c', sheet).attr('s', '42');
			 $('row c[r^="C"]',sheet).each(function(){
			if($('is t', this).text() =="0"){
				$(this).attr('s', '15');
			}
			else if ($('is t', this).text()!="0"){
				$(this).attr('s', '12');
			}
			else {
				$(this).attr('s', '34');
			}
        
         
      });
		  }
		}]
	  }*/
            buttons: [
                {
                    extend: 'copyHtml5',
                    className: 'btn btn-default',
					title: 'Reports of Uploaded Documents',
					 exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excelHtml5',
                    className: 'btn btn-default',
					title: 'Reports of Uploaded Documents',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    className: 'btn btn-default',
					title: 'Reports of Uploaded Documents',
					orientation: 'landscape',
					pageSize: 'LEGAL',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
				{
                extend: 'print',
                text: '<i class="icon-printer position-left"></i> Print table',
                className: 'btn btn-default',
				title: 'Reports of Uploaded Documents',
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


function xin_table_expiry(filter = 'all'){
	$.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        }
    });
	 var xin_table_expiry = $('#xin_table_expiry').DataTable({
		"bDestroy": true,
		"iDisplayLength": 50,
		"ajax": {
			url : base_url+"/expiry_document_list/?custom_filter="+filter,
			type : 'GET'
		},"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		},	
		
		/*buttons: {
		buttons: [{
		  extend: 'excelHtml5',
		  title: 'Uploaded Documents Report',
		  className: 'btn btn-default',
		  exportOptions: {
                        columns: ':visible'
          },
		  customize: function (xlsx) {
			  
			var sheet = xlsx.xl.worksheets['sheet1.xml'];
			console.log(sheet);
			$('row:first c', sheet).attr('s', '42');
			 $('row c[r^="C"]',sheet).each(function(){
			if($('is t', this).text() =="0"){
				$(this).attr('s', '15');
			}
			else if ($('is t', this).text()!="0"){
				$(this).attr('s', '12');
			}
			else {
				$(this).attr('s', '34');
			}
        
         
      });
		  }
		}]
	  }*/
            buttons: [
                {
                    extend: 'copyHtml5',
                    className: 'btn btn-default',
					title: 'Reports of Expiry Documents',
					 exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excelHtml5',
                    className: 'btn btn-default',
					title: 'Reports of Expiry Documents',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    className: 'btn btn-default',
					title: 'Reports of Expiry Documents',
					orientation: 'landscape',
					pageSize: 'LEGAL',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
				{
                extend: 'print',
                text: '<i class="icon-printer position-left"></i> Print table',
                className: 'btn btn-default',
				title: 'Reports of Expiry Documents',
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

 function xin_table_employees(){
	 
	var department_value=$('.department_value :selected').val();
	var location_value=$('.location_value :selected').val();
	var medical_card_type=$('.medical_card_type :selected').val();
	var visa_value=$('.visa_value :selected').val();
	
	$.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        }
    });
	 var xin_table_employees = $('#xin_table_employees').DataTable({
		"bDestroy": true,
		"iDisplayLength": 100,
		"ajax": {
			url : base_url+"/employees_report_list/?department_value="+department_value+"&location_value="+location_value+"&medical_card_value="+medical_card_type+"&visa_value="+visa_value,
			type : 'GET'
		},"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		},		
            buttons: [
                {
                    extend: 'copyHtml5',
                    className: 'btn btn-default',
					title: 'Report of Employees Information',
					 exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excelHtml5',
                    className: 'btn btn-default',
					title: 'Report of Employees Information',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    className: 'btn btn-default',
					title: 'Report of Employees Information',
					orientation: 'landscape',
					pageSize: 'LEGAL',
                    exportOptions: {
                        columns: ':visible'
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
	
	$('table').wrap('<div class="table-responsive"></div>');

}
