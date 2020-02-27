$(document).ready(function() {

	employeeLeaveListTrigger();

	$.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        }
    });

  	$("#xin-form").submit(function(e){
	    var fd = new FormData(this);
		var obj = $(this), action = obj.attr('name');
		//var remarks = $("#remarks").code();
		fd.append("is_ajax", 1);
		fd.append("add_type", 'leave');
		fd.append("data", 'leave');
		fd.append("form", action);
		e.preventDefault();
		$('.save').prop('disabled', true);
		$.ajax({
			url: e.target.action,
			type: "POST",
			//data: obj.serialize()+"&is_ajax=1&add_type=leave&form="+action+"&remarks="+remarks,
			data:  fd,
			contentType: false,
			cache: false,
			processData:false,
			success: function (JSON) {
				 $("#sh_message").html(JSON.message);
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('#xin_table').dataTable().api().ajax.reload(function(){
						toastr.success(JSON.result);
					}, true);
					$('.add-form').fadeOut('slow');
					$('#xin-form')[0].reset(); // To reset form fields
					$('.save').prop('disabled', false);
					$('form#xin-form select').val('').trigger("change");
					$(".note-editable,#sh_message,#sh_message_date").html('');
					$('.fileinput-remove').click();
					$(".add-new-form").show();
				}
			}
		});
	});

	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$( document ).on( "click", ".delete", function() {
	$('input[name=_token]').val($(this).data('record-id'));
	$('.delete_record').attr('action',site_url+'timesheet/delete_leave/'+$(this).data('record-id'));
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
							$('#xin_table').dataTable().api().ajax.reload(function(){
						toastr.success(JSON.result);
					}, true);


						}
					}
				});


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
						toastr.success(JSON.result);
					}, true);


						}
					}
				});


            }

        });

	});


    $('.edit-modal-data').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var btn_type = button.data('btn_type');
		$('.add-form').slideUp();
		if(btn_type!=undefined){
		var modal = $(this);
    	$.ajax({
		url : site_url+"timesheet/read_leave_chk_avail/",
		type: "GET",
		data: 'jd=1&is_ajax=1&mode=modal&data=check_leave_availability_admin&btn_type='+btn_type,
		success: function (response) {
			if(response) {
				$("#ajax_modal").html(response);
			}
		}
		});
		}

	});

	/* Delete data */


	// edit
	/*$('.edit-modal-data').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var leave_id = button.data('leave_id');
		var modal = $(this);
		if(leave_id!=undefined){
	$.ajax({
		url : site_url+"timesheet/read_leave_record/",
		type: "GET",
		data: 'jd=1&is_ajax=1&mode=modal&data=leave&leave_id='+leave_id,
		success: function (response) {
			if(response) {
				$("#ajax_modal").html(response);
			}
		}
		});
		}
	});*/


    $('.edit-modal-data-payrol').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var leave_conversion_id = button.data('leave_conversion_id');
		var modal = $(this);
		if(leave_conversion_id!=undefined){
    	$.ajax({
		url : site_url+"timesheet/read_leave_conversion/",
		type: "GET",
		data: 'jd=1&is_ajax=1&mode=modal&data=leave_conversion&leave_conversion_id='+leave_conversion_id,
		success: function (response) {
			if(response) {
				$("#ajax_modal_payview").html(response);
			}
		}
		});
		}
	});

xin_forms();

	/* Add data */ /*Form Submit*/
xin_table_conversion();

function employeeLeaveListTrigger(){

	$('.schedule').fullCalendar('destroy');
	var fullDate = new Date()
	var user_id=$('#user_id').val();
	var edit_url = site_url+'timesheet/leave_details/id/';
	if(user_id!=undefined){
		$.ajax({
			url: site_url+'timesheet/leave_list_calendar/'+$('#user_id').val(),
			
			method: "json",
			success: function(response) {
				var eventsColors=JSON.parse(response);
				$('.schedule').fullCalendar({
					header: {
							left: 'prev,next today',
							center: 'title',
							right: 'prev,next',//month,agendaWeek,agendaDay
					},
					defaultDate: fullDate,
					// editable: true,
					events: eventsColors,
					eventClick: function(event) {
 						window.location = edit_url+event.id;
  					}
						
				});
			}
		});
	}
}


});

function xin_table_conversion(){
var employee_id=0;
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
			url : site_url+"timesheet/leave_conversion_lists/?employee_id="+employee_id,
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
function clear_filter(){
xin_forms();
}
function xin_forms(){


	var params = $('#leave_form').serialize();
	// console.log(params);
	var xin_table = $('#xin_table').DataTable({
		"bDestroy": true,
		"ajax": {
			url : site_url+"timesheet/leave_list/?"+params,
			type : 'GET'
		},"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();
		},
		"columnDefs": [
				{
					"targets": [ 0,1 ],
					"visible": false,
					"searchable": false,
					"orderData": [ 0, 1 ]
			 }],"order": [[ 0, "desc" ],[ 1, "desc" ]],
            buttons: [
                {
                    extend: 'copyHtml5',
                    className: 'btn btn-default',
					 exportOptions: {
                        columns: ':visible'
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
                    className: 'btn bg-teal-400 btn-icon',
					columns: ':gt(1)'
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

    leaveListCalendarFilter();
}

function leaveListCalendarFilter(){

	$('.schedule').fullCalendar('destroy');
	var fullDate = new Date()
	var user_id = $('#user_id').val();
	var visa_value = $('#visa_value').val();
	var location_value = $('#location_value').val();
	var department_value = $('#department_value').val();
	var leave_type_value = $('#leave_type_value').val();
	var status_value = $('#status_value').val();
	var date_from = $('#date_from').val();
	var date_to = $('#date_to').val();

	var edit_url = site_url+'timesheet/leave_details/id/';
	if(user_id!=undefined){
		$.ajax({
			url: site_url+'timesheet/leave_list_calendar/?user_id='+user_id+'&visa_value='+visa_value+'&location_value='+location_value+'&department_value='+department_value+'&leave_type_value='+leave_type_value+'&status_value='+status_value+'&date_from='+date_from+'&date_to='+date_to,
			type : 'GET',
			method: "json",
			success: function(response) {
				var eventsColors=JSON.parse(response);
				$('.schedule').fullCalendar({
					header: {
							left: 'prev,next today',
							center: 'title',
							right: 'prev,next',//month,agendaWeek,agendaDay
					},
					defaultDate: fullDate,
					// editable: true,
					events: eventsColors,
					eventClick: function(event) {
 						window.location = edit_url+event.id;
  					}
						
				});
			}
		});
	}
}
