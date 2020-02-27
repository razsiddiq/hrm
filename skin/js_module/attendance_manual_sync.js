$(document).ready(function() {
	$('[data-plugin="select_hrm"]').select2($(this).attr("data-options"));

	//select_filter();

	// Month & Year

	$(".styled").uniform({
		radioClass: "choice"
	});

	$("#sync_process").submit(function(e) {
		e.preventDefault();
		var obj = $(this),
			action = obj.attr("name");
		$(".save").prop("disabled", true);
		swal({
			title: "This may take a while",
			text:
				"<i class='icon-spinner2 spinner' style='font-size: 2em;color: #26a69a;'></i>",
			html: true
		});
		$(".sa-confirm-button-container").hide();
		$.ajax({
			type: "POST",
			url: base_url + "/manual_sync_attendance_process/",
			data: obj.serialize() + "&is_ajax=1&add_type=attendance&form=" + action,
			cache: false,
			success: function(JSON) {
				if (JSON.error != "") {
					toastr.error(JSON.error);
					$(".save").prop("disabled", false);
				} else {
					toastr.success(JSON.result);
					$(".save").prop("disabled", false);
					select_filter();
				}
				$(".confirm").click();
				$(".sa-confirm-button-container").show();
			}
		});
	});
	//filter_employee_bylocation();

	$(".location_value,.department_value").change(function(){
		filter_employee_bylocation();
	});
});

function filter_employee_bylocation() {
	var location_val = $('.location_value').val();
	var department_val = $('.department_value').val();
	$.ajax({
		url: base_url + "/filter_employee_bylocation/",
		type: "GET",
		data:
			"jd=1&location_id=" + location_val + "&department_id=" + department_val,
		success: function(response) {
			if (response) {
				$("#employee_id").html(response);
			}
		}
	});
}

function select_filter() {
	var location_value = $(".location_value").val();
	var department_value = $(".department_value").val();
	var buttonCommon = {
		exportOptions: {
			format: {
				body: function(data, column, row, node) {
					return data;
				}
			}
		}
	};

	var start_date = $("#start_date").val();
	var end_date = $("#end_date").val();
	var user_id = $("#employee_id").val();

	$.extend($.fn.dataTable.defaults, {
		autoWidth: false,
		dom:
			'<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
		language: {
			search: "<span>Filter:</span> _INPUT_",
			lengthMenu: "<span>Show:</span> _MENU_",
			paginate: {
				first: "First",
				last: "Last",
				next: "&rarr;",
				previous: "&larr;"
			}
		}
	});
	if (user_id != null) {
		var xin_table2 = $("#xin_table").DataTable({
			bDestroy: true,
			ajax: {
				url:
					site_url +
					"timesheet/date_wise_list_with_shift/?start_date=" +
					start_date +
					"&end_date=" +
					end_date +
					"&user_id=" +
					user_id +
					"&department_value=" +
					department_value +
					"&location_value=" +
					location_value,
				type: "GET"
			},
			fnDrawCallback: function(settings) {
				$('[data-toggle="tooltip"]').tooltip();
			},
			columnDefs: [
				{
					targets: [0],
					visible: false,
					searchable: false,
					orderData: [0, 1]
				}
			],
			order: [[0, "asc"]],
			buttons: [
				{
					extend: "copyHtml5",
					className: "btn btn-default",
					exportOptions: {
						columns: [0, ":visible"]
					}
				},
				{
					extend: "excelHtml5",
					className: "btn btn-default",
					exportOptions: {
						columns: ":visible"
					}
				},
				{
					extend: "pdfHtml5",
					className: "btn btn-default",
					exportOptions: {
						columns: ":visible" // [ 1, 2, 3, 4, 5, 6, 7]
					}
				},
				{
					extend: "print",
					text: '<i class="icon-printer position-left"></i> Print table',
					className: "btn btn-default",
					exportOptions: {
						columns: ":visible"
					}
				},
				{
					extend: "colvis",
					text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
					className: "btn bg-teal-400 btn-icon"
				}
			]
		});
	}

	// Add placeholder to the datatable filter option
	$(".dataTables_filter input[type=search]").attr(
		"placeholder",
		"Type to filter..."
	);

	// Enable Select2 select for the length option
	$(".dataTables_length select").select2({
		minimumResultsForSearch: Infinity,
		width: "auto"
	});
}
