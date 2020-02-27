$(document).ready(function() {
var xin_table = $('#xin_table').dataTable({
	"bDestroy": true,
	"ajax": {
		url : site_url+"timesheet/attendance_list/?attendance_date="+$('#attendance_date').val(),
		type : 'GET'
	},
	"columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false,
				"orderData": [ 0, 1 ]
     }],
     "order": [[ 0, "desc" ]],
	"fnDrawCallback": function(settings){
	$('[data-toggle="tooltip"]').tooltip();          
	}
});


// Month & Year
$('.attendance_date').datepicker({
	changeMonth: true,
	changeYear: true,
	maxDate: '0',
	dateFormat:'yy-mm-dd',
	altField: "#date_format",
	altFormat: js_date_format,
	yearRange: '1970:' + new Date().getFullYear(),
	beforeShow: function(input) {
		$(input).datepicker("widget").show();
	}
});

/* attendance daily report */
$("#attendance_daily_report").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
	var attendance_date = $('#attendance_date').val();
	var date_format = $('#date_format').val();
	if(attendance_date == ''){
		toastr.error('Please select date.');
	} else {
	$('#att_date').html(date_format);
		 var xin_table2 = $('#xin_table').dataTable({
			"bDestroy": true,
			"ajax": {
				url : site_url+"timesheet/attendance_list/?attendance_date="+$('#attendance_date').val(),
				type : 'GET'
			},
			"columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false,
				"orderData": [ 0, 1 ]
         }],
		 
			"fnDrawCallback": function(settings){
			$('[data-toggle="tooltip"]').tooltip();          
			}
		});
		xin_table2.api().ajax.reload(function(){ }, true);
	}
});

});