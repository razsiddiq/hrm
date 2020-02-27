$(document).ready(function() {
	$('[data-plugin="select_hrm"]').select2($(this).attr("data-options"));
	$('[data-plugin="select_hrm"]').select2({ width: "100%" });

	$(".clockpicker").clockpicker();

	var input = $(".timepicker").clockpicker({
		placement: "bottom",
		align: "left",
		autoclose: true,
		default: "now"
	});

	bus_lateness_list();
	// edit

	$(document).on("click", ".delete", function() {
		$("input[name=_token]").val($(this).data("record-id"));
		$(".delete_record").attr(
			"action",
			base_url + "/bus_list_delete/" + $(this).data("record-id")
		);
		swal(
			{
				title: "Are you sure?",
				text: "Record deleted can't be restored!",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#FF7043",
				confirmButtonText: "Yes, delete it!"
			},
			function(isConfirm) {
				if (isConfirm) {
					var obj = $(".delete_record"),
						action = obj.attr("name");

					$.ajax({
						type: "POST",
						url: $(".delete_record").attr("action"),
						data: obj.serialize() + "&is_ajax=2&form=" + action,
						cache: false,
						success: function(JSON) {
							if (JSON.error != "") {
								toastr.error(JSON.error);
							} else {
								bus_lateness_list();
								toastr.success(JSON.result);
							}
						}
					});
				}
			}
		);
	});

	// edit
	$(".edit-modal-data").on("show.bs.modal", function(event) {
		var button = $(event.relatedTarget);
		var bus_late_id = button.data("bus_late_id");
		var modal = $(this);
		if (bus_late_id != null) {
			$.ajax({
				url: base_url + "/read_bus_lateness/",
				type: "GET",
				data:
					"jd=1&is_ajax=1&mode=modal&data=bus_lateness&bus_late_id=" +
					bus_late_id,
				success: function(response) {
					if (response) {
						$("#ajax_modal").html(response);
					}
				}
			});
		}
	}); /*Form Submit*/
	/* Add data */ $("#xin-form").submit(function(e) {
		e.preventDefault();
		var obj = $(this),
			action = obj.attr("name");
		$(".save").prop("disabled", true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data:
				obj.serialize() +
				"&is_ajax=1&add_type=bus_lateness_timing&form=" +
				action,
			cache: false,
			success: function(JSON) {
				if (JSON.error != "") {
					toastr.error(JSON.error);
					$(".save").prop("disabled", false);
				} else {
					bus_lateness_list();
					toastr.success(JSON.result);
					$(".add-form").fadeOut("slow");
					$("#xin-form")[0].reset();
					$("form#xin-form select")
						.val("")
						.trigger("change");
					$(".save").prop("disabled", false);
					$(".add-new-form").show();
				}
			}
		});
	});
});

function bus_lateness_list() {
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
	var xin_bus_lateness = $("#xin_bus_lateness").DataTable({
		bDestroy: true,
		iDisplayLength: 100,
		//paging: false,
		ajax: {
			url: site_url + "timesheet/bus_lateness_list",
			type: "GET"
		},
		fnDrawCallback: function(settings) {
			$('[data-toggle="tooltip"]').tooltip();
		},
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
