$(document).ready(function() {
	if(window.location.href.indexOf("report") > -1) {
		employees_report();
	}
	else
		xin_forms();
	$('[data-plugin="xin_select"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$(".click_same_ad").click(function(){
		if($('#same_as_home_address').is(':checked')){
			//alert('checked');
			$('#residing_address1').val($("input[name=address]").val());
			$('#residing_address2').val($("input[name=address2]").val());
			$('#residing_city').val($("input[name=city]").val());
			$('#residing_area').val($("input[name=area]").val());
			$('#residing_zipcode').val($("input[name=zipcode]").val());
			$('#residing_country').val($('#home_country').val()).trigger("change");
		} else {
			//alert('not');
			$('#residing_address1').val('');
			$('#residing_address2').val('');
			$('#residing_city').val('');
			$('#residing_area').val('');
			$('#residing_zipcode').val('');
			$('#residing_country').val('').trigger("change");
		}
	});
	$(".styled").uniform({
		radioClass: 'choice'
	});
	$('.edit-modal-data').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var warning_id = button.data('warning_id');
		var modal = $(this);
		$.ajax({
			url : base_url+"/read/",
			type: "GET",
			data: 'jd=1&is_ajax=1&mode=modal&data=warning&warning_id='+warning_id,
			success: function (response) {
				if(response) {
					$("#ajax_modal").html(response);
				}
			}
		});
	});
	// Get Departments
	/**
	 jQuery("#aj_department").change(function(){
		   jQuery.get(base_url+"/designation/"+jQuery(this).val(), function(data, status){
			   jQuery('#designation_ajax').html(data);
		   });
		});
	 **/
	/* Add data */ /*Form Submit*/
	$("#xin-form").submit(function(e){
		e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=1&add_type=employee&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
				} else {
					xin_forms();
					toastr.success(JSON.result);
					$('.add-form').fadeOut('slow');
					$('#xin-form')[0].reset();
					jQuery('form#xin-form select').val('').trigger("change");
					jQuery('select.change_country_code').val('+93-').trigger("change");
					$(".add-new-form").show();
				}
				$('.save').prop('disabled', false);
			}
		});
	});
	$(".location_value,.department_value,.role_value,.medical_card_type,.visa_value").change(function(){
		xin_forms();
	});
	$(".country_value").change(function(){
		let index = $(this).val();
		let data = (index ==0)? defaultLocations : locations[index];
		populateLocationDropDown(data,(index==0));
		xin_forms();
	});
});

function populateLocationDropDown(data,all = false){
	let locationContainer = $('.location_value').get(0);
	$('.location_value').empty();
	let opt = document.createElement('option');
	//if(all){
		opt.text = "All Locations";
		opt.value = 0
		locationContainer.add(opt, null);
	//}
	for(let i=0; i<data.length; i++){
		opt = document.createElement('option');
		opt.text = data[i].location_name;
		opt.value = data[i].location_id;
		locationContainer.add(opt, null);
	}
}
$( document ).on( "click", ".delete", function() {
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
							xin_forms();
							toastr.success(JSON.result);


						}
					}
				});


			}

		});
});


function clear_filter(){
	$('.department_value').val(0).trigger("change");
	$('.location_value').val(0).trigger("change");
	$('.role_value').val(0).trigger("change");
	$('.medical_card_type').val(0).trigger("change");
	$('.visa_value').val(0).trigger("change");
}


function xin_forms(){

	var department_value=$('.department_value :selected').val();
	var location_value=$('.location_value :selected').val();
	var role_value=$('.role_value :selected').val();
	var medical_card_type=$('.medical_card_type :selected').val();
	var visa_value=$('.visa_value :selected').val();
	var country_value=$('.country_value :selected').val();


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
		"iDisplayLength": 100,
		"ajax": {
			url : base_url+"/employees_list/?department_value="+department_value+"&country_value="+country_value+"&location_value="+location_value+"&role_value="+role_value+"&medical_card_value="+medical_card_type+"&visa_value="+visa_value,
			type : 'GET'
		},"fnDrawCallback": function(settings){
			$('[data-toggle="tooltip"]').tooltip();
		},
		"order": [[ 1, "asc" ]],
		"columnDefs": [
			{
				"targets": [ 1 ],
				"visible": false,
				"searchable": false,

			}
		],
		buttons: [
			{
				extend: 'copyHtml5',
				className: 'btn btn-default',
				exportOptions: {
					columns: [ 0, 1, 3, 4, 5, 6, 7]
				}
			},
			{
				extend: 'excelHtml5',
				className: 'btn btn-default',
				exportOptions: {
					//columns: ':visible'
					columns: [ 0, 1, 3, 4, 5, 6, 7]
				}
			},
			{
				extend: 'pdfHtml5',
				className: 'btn btn-default',
				exportOptions: {
					columns: [ 0, 1, 3, 4, 5, 6, 7]
				}
			},
			{
				extend: 'print',
				text: '<i class="icon-printer position-left"></i> Print table',
				className: 'btn btn-default',
				exportOptions: {
					columns: [ 0, 1, 3, 4, 5, 6, 7]
				}
			},
			/*{
                extend: 'colvis',
                text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
                className: 'btn bg-teal-400 btn-icon'
            }*/
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

function employees_report(){
	$('#xin_report_table').dataTable().fnClearTable();
	$('#xin_report_table').dataTable().fnDestroy();
	$('.table_columns').html("<th>Status</th><th>Name</th>");
	let employeesColumns = $('.employees_column option:selected');
	let columnsReport = [0,1];
	for(let i=0; i<employeesColumns.length; i++){
		$('.table_columns').append("<th style='width:20%'>"+$(employeesColumns[i]).text()+"</th>");

		if($(employeesColumns[i]).text() == 'Visa'){
			$('.table_columns').append("<th style='width:20%'>Visa Number</th>");
		}
		if($(employeesColumns[i]).text() == 'Passport'){
			$('.table_columns').append("<th style='width:20%'>Passport Number</th>");
		}
		if($(employeesColumns[i]).text() == 'Emirates ID'){
			$('.table_columns').append("<th style='width:20%'>Emirates ID Number</th>");
		}
		columnsReport.push(i+2);
	}
	$('.table_columns').append('<th>Action</th>');
	var queryParams = $( '#list_employees' ).serialize();
	$.extend( $.fn.dataTable.defaults, {
		autoWidth: false,
		dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
		language: {
			search: '<span>Filter:</span> _INPUT_',
			"lengthMenu": 'Display <select>'+
				'<option value="-1">All</option>'+
				'<option value="10">10</option>'+
				'<option value="20">25</option>'+
				'<option value="50">50</option>'+
				'<option value="100">100</option>'+
				'<option value="250">250</option>'+
				'</select> records',
			paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
		}
	});
	var xin_table = $('#xin_report_table').DataTable({
		searching: false,
		"ordering": false,
		"bDestroy": true,
		"iDisplayLength": 50,
		processing: true,
		serverSide: true,
		"ajax": {
			url : base_url+"/employees_report_ajax/?"+queryParams,
			type : 'POST'
		},"fnDrawCallback": function(settings){
			$('[data-toggle="tooltip"]').tooltip();
		},
		"order": [],
		// "columnDefs": [
		// 	{
		// 		"targets": [ 1 ],
		// 		"visible": false,
		// 		"searchable": false,
		// 	},
		// ],
		buttons: [
			// {
			// 	extend: 'copyHtml5',
			// 	className: 'btn btn-default',
			// 	exportOptions: {
			// 		columns: [ 0, 1, 3, 4, 5, 6, 7]
			// 	}
			// },
			{
				extend: 'excelHtml5',
				className: 'btn btn-default',
				exportOptions: {
					// columns: ':visible'
					columns: columnsReport
				}
			},
			// {
			// 	extend: 'pdfHtml5',
			// 	className: 'btn btn-default',
			// 	exportOptions: {
			// 		columns: [ 0, 1, 3, 4, 5, 6, 7]
			// 	}
			// },
			{
				extend: 'print',
				text: '<i class="icon-printer position-left"></i> Print table',
				className: 'btn btn-default',
				exportOptions: {
					columns: [ 0, 1, 3, 4, 5, 6, 7]
				}
			},
			/*{
                extend: 'colvis',
                text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
                className: 'btn bg-teal-400 btn-icon'
            }*/
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

