$(document).ready(function() {
	$(".styled").uniform({ radioClass: 'choice'});

	$('.clockpicker').clockpicker();

	var input = $('.timepicker').clockpicker({
		placement: 'bottom',
		align: 'left',
		autoclose: true,
		'default': 'now'
	});
	check_options('department');
	emp_shows();
	
    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });

    shift_schedule_list();

	$('.view-modal-data-md').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var schedule_user_id = button.data('schedule_user_id');
		var modal = $(this);
		$.ajax({
		url : site_url+"timesheet/read_change_schedule_view/",
		type: "GET",
		data: 'jd=1&is_ajax=1&mode=modal&data=scheduleview&schedule_user_id='+schedule_user_id,
		success: function (response) {
			if(response) {
				$("#ajax_modal_view-md").html(response);
			}
		}
		});
	});

	/* Add data */ /*Form Submit*/
	$("#xin-form").submit(function(e){
		e.preventDefault();     	
		var location_id=$('#location_id_val').val();
		var department_id=$('#department_id_val').val();
		
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=1&add_type=change_schedule&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					var xin_shift_table = $('#xin_shift_table').DataTable();
					xin_shift_table.ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.add-form').fadeOut('slow');
					$('#xin-form')[0].reset(); // To reset form fields
					$('form#xin-form select').val('').trigger("change");
					$('.save').prop('disabled', false);
					$(".add-new-form").show();
					$("input[value=department]").click();
					
					get_update_employees();
				}
			}
		});
	});

	$("#shift_list_search").submit(function(e){
		e.preventDefault();
		shift_schedule_list();
	});

	$(".employee_shows").change(function(e){
		e.preventDefault();
		emp_shows();
	});

	// Handle click on "Select all" control
	$('#example-select-all').on('click', function(){
		var datatablescroll = $('.datatablescroll').DataTable();
		// Check/uncheck all checkboxes in the table
		var rows = datatablescroll.rows({ 'search': 'applied' }).nodes();
		$('input[type="checkbox"]', rows).prop('checked', this.checked);
	});
	
	$('#datatablescroll tbody').on('change', 'input[type="checkbox"]', function(){
		// If checkbox is not checked
		if(!this.checked){
			var el = $('#example-select-all').get(0);
			// If "Select all" control is checked and has 'indeterminate' property
			if(el && el.checked && ('indeterminate' in el)){
				// Set visual state of "Select all" control 
				// as 'indeterminate'
				el.indeterminate = true;
			}
		}
	});
   
   
    $('#example-select-all-1').on('click', function(){
	   var xin_shift_table = $('#xin_shift_table').DataTable();
      // Check/uncheck all checkboxes in the table
      var rows = xin_shift_table.rows({ 'search': 'applied' }).nodes();
      $('input[type="checkbox"]', rows).prop('checked', this.checked);
    });
   
    $('#xin_shift_table tbody').on('change', 'input[type="checkbox"]', function(){
      // If checkbox is not checked
      if(!this.checked){
         var el = $('#example-select-all-1').get(0);
         // If "Select all" control is checked and has 'indeterminate' property
         if(el && el.checked && ('indeterminate' in el)){
            // Set visual state of "Select all" control 
            // as 'indeterminate'
            el.indeterminate = true;
         }
      }
    });   
   
    $('.content').on('click', '.change_schedule', function(){
	   var len = $(".bs_styled:checked").length;
	   var xin_shift_table = $('#xin_shift_table').DataTable();
	    var arr = [];
		 xin_shift_table.$('input.bs_styled:checkbox:checked').each(function () {
			arr.push($(this).val());
		 });
		 
		 
	   if(len>0){
	    $(".edit-modal-data").modal();
		var modal = $(this);
	    $.ajax({
		url : site_url+"timesheet/read_change_schedule/",
		type: "GET",
		data: 'jd=1&is_ajax=1&mode=modal&data=schedule&change_schedule_id='+arr,
		success: function (response) {			      
			if(response) {
				$("#ajax_modal").html(response);
			}
		}
		});
		  }else{	
        swal({
            title: "Select atleast one checkbox",
            text: "",
            html: true,
			confirmButtonColor: "#26A69A",
        });
		
		   }
		  
	});	   
   
    $('.content').on('click', '.delete_schedule', function(){ 
	    var len = $(".bs_styled:checked").length;
	    var xin_shift_table = $('#xin_shift_table').DataTable();
		var arr = [];
		xin_shift_table.$('input.bs_styled:checkbox:checked').each(function () {
			arr.push($(this).val());
		});
		 
		 
		if(len>0){
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
					$.ajax({
						url : site_url+"timesheet/delete_change_schedule/",  
						type: "POST",
						data: 'jd=1&is_ajax=1&type=delete_change_schedule&change_schedule_id='+arr,
						success: function (JSON) {			      
							if (JSON.error != '') {
								toastr.error(JSON.error);
							} else {
								var xin_shift_table = $('#xin_shift_table').DataTable();
								xin_shift_table.ajax.reload(function(){ 
									toastr.success(JSON.result);
								}, true);
										
							}
						}
					});
					
				}
		
			});	  
		} 
		else{	
			swal({
				title: "Select atleast one checkbox",
				text: "",
				html: true,
				confirmButtonColor: "#26A69A",
			});		
		}
		  
	});	   
});


function get_update_employees(){
	
	$.ajax({
		url : site_url+"timesheet/get_update_employees/",
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

function check_options(val){
	if(val=='department'){	
		$('#employee_id_div').hide();;
		$('#depart_id_div').show();
		
	}else{
		$('#employee_id_div').show();;
		$('#depart_id_div').hide();
	}
	$('form#xin-form select').val(0).trigger("change");	
	if(val == 'department'){
		if(office_location_id_get!='' && department_id_get!=''){
			$('select#location_id_val').val(office_location_id_get).trigger("change");
			$('select#department_id_val').val(department_id_get).trigger("change");	
		}
	}
}

function emp_shows(){	
	var location_id=$('#location_id_val').val();
	var department_id=$('#department_id_val').val();

	if((location_id!=null) && (department_id!=null)){
		$('#show_emp_table').show();
	}else{
		$('#show_emp_table').hide();
		location_id='';
		department_id='';
	}
	$.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        }
    });	


	var datatablescroll = $('.datatablescroll').DataTable({
		"bDestroy": true,
		paging: false,	
		// scrollY: 300,
		"ajax": {
			url : site_url+"timesheet/employee_list_by_location_department/?location_id="+location_id+"&department_id="+department_id,
			type : 'GET'
		},
		"fnDrawCallback": function(settings){
			$('[data-toggle="tooltip"]').tooltip();          
		},
		'columnDefs': [{
			'targets': 0,
			'searchable':false,
			'orderable':false,
			'className': 'dt-body-center',
			'render': function (data, type, full, meta){   
				return '<label><input type="checkbox" name="employee_id[]" value="'+ $('<div/>').text(data).html() + '"><span></span></label>';	
			}
      	}],	
        buttons: []        
    });
	$('.dataTables_filter input[type=search]').attr('placeholder','Type to filter...');
    // Enable Select2 select for the length option
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
 
}
 
 
 

function shift_schedule_list(){
		
	var department_value=$('.department_value').val();
	var location_value=$('.location_value').val();	
	var user_id = $('#user_id').val();	
	
	$.extend( $.fn.dataTable.defaults, {
		autoWidth: false,
		dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
		language: {
			search: '<span>Filter:</span> _INPUT_',
			lengthMenu: '<span>Show:</span> _MENU_',
			paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
		}
    });

	var xin_shift_table = $('#xin_shift_table').DataTable({
		"bDestroy": true,
		"iDisplayLength": 100,
		"ajax": {
			url : site_url+"timesheet/employee_shift_lists/?&user_id="+user_id+"&department_value="+department_value+"&location_value="+location_value,
			type : 'GET'
		},
		"fnDrawCallback": function(settings){
			$('[data-toggle="tooltip"]').tooltip();          
		},
		'columnDefs': [{
			'targets': 0,
			'searchable':false,
			'orderable':false,
			'className': 'dt-body-center',
			'render': function (data, type, full, meta){
				return '<label><input type="checkbox" class="bs_styled" name="employee_id[]" value="'+ $('<div/>').text(data).html() + '"><span></span></label>';	
			}
      	}],
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
}


function clear_filter(){
	$('.department_value').val(0).trigger("change");
	$('.location_value').val(0).trigger("change");
	$('#user_id').val('all').trigger("change");	
	shift_schedule_list();
}