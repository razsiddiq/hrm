$(document).ready(function() {
	// select_filter();
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));

	// Month & Year

	/* attendance datewise report */
	$("#attendance_datewise_report").submit(function(e){
		/*Form Submit*/
		e.preventDefault();
	    select_filter();
	});
});

function getUserList(user_type){

	$.ajax({
		type: "POST",
	  	url: site_url+"timesheet/getUserList",
	  	cache: false,
	  	data : {user_type:user_type},
	  	dataType : 'json',
	  	success: function(data){
	  		$('#newtList').html(data.view);
	    	$('#defaultList').remove();
	  		$('#employee_id').select2();

	  	}
	});
}

function select_employee(val){
	if(val!='all'){
	$('.department_value').val(0);
	// $('.location_value').val(0);
	}else{
	$('.department_value').val(0);
	$('.location_value').val(1);
	}
}
function clear_filter(){
	$('.department_value').val(0).trigger("change");
	$('.location_value').val(0).trigger("change");
	select_filter();
}
function select_filter(){

	    // var department_value = $('.department_value').val();
		var location_value = $('.location_value').val();
		var user_type = $('#user_type').val();
		var buttonCommon = {
			exportOptions: {
				format: {
					body: function (data, column, row, node) {
						 return  data;

					}
				}
			}
		};
		var title_header='';
		var employee_val=$('#employee_id').val();
		   if(employee_val=='all'){
	       title_header='All Employees Attendance Report List';
		   }else{
			 title_header='Attendance Report List From '+$('#start_date').val()+' To '+$('#end_date').val();
		   }
   		var start_date = $('#start_date').val();
		var end_date = $('#end_date').val();
		var user_id = $('#employee_id').val();
		var department_value = $('#dep_id').val();
		if((Date.parse(start_date) != Date.parse(end_date)) && user_id=='all'){
			toastr.error('Set a single date for all employees.');
			return false;
		}else if((Date.parse(start_date) > Date.parse(end_date))){
			toastr.error('Start date should be less than end date.');
			return false;
		}

		if(user_id=='all'){
			$('.set_two').hide();
			$('.change_title_name').html('Name');
			$('.set_one').show();
			//$('#xin_table tfoot').show();
		}else{
			$('.set_one').hide();
			$('.set_two').show();
			$('.change_title_name').html('Date');
			//$('#xin_table tfoot').hide();
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
	 var xin_table2 = $('#xin_table').DataTable({
		"bDestroy": true,
		"iDisplayLength": 100,
		"ajax": {
			url : site_url+"timesheet/date_wise_list_with_shift/?start_date="+start_date+"&end_date="+end_date+"&user_id="+user_id+"&user_type="+user_type+"&department_value="+department_value+"&location_value="+location_value+'&dep_id='+dep_id,
			type : 'GET',
			dataSrc: function ( json ) {
				//Make your callback here.
				$('.total_late').html(json.lateTotal);
				$('.total_early').html(json.earlyLeaveTotal);
				$('.sum_late_early').html(json.sum_late_early);
				$('.sum_after_grace').html(json.sum_after_grace);

				$('.required_working_hours').html(json.required_working_hours);
				$('.total_working_hours_range').html(json.total_working_hours_range);
				$('.delta_working_hours').html(json.delta_working_hours);
				return json.data;
			}
		},"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();
		},"columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false,
				"orderData": [ 0, 1 ]
            }
        ],"order": [[ 0, "asc" ]],
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
                    },
					title: function () { return $('#employee_id option:selected').text(); },
					message:'Start Date: '+start_date+' | End Date: '+end_date+' \n Location: '+$('.location_value option:selected').text()
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
