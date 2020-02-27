$(document).ready(function() {
 //xin_forms();
     var month_year=$('#month_year').val(); 
     xin_table(month_year);
	/* attendance datewise report */
	$("#attendance_datewise_report").submit(function(e){
		/*Form Submit*/
		e.preventDefault();
		xin_forms();
	});
	$('[data-plugin="xin_select"]').select2($(this).attr('data-options'));


$("#month_year").change(function(){
	var month_year=$('#month_year').val(); 
    xin_table(month_year);
});
});
function xin_table(month_year){
$('#xin_table').html('');	
$.ajax({			
			url: site_url+"employee/attendance/user_date_list/?month_year="+month_year,
			type: "GET",
			success: function (data) {				
				$('#xin_table').html(data);				
			}
		});
}

function xin_forms(){
	
	var start_date = $('#start_date').val();
	var end_date = $('#end_date').val();		
	if((Date.parse(start_date) > Date.parse(end_date))){
		toastr.error('Start date should be less than end date.');
		return false;
	}	
	
	

	
	 var xin_table = $('#xin_table').DataTable({
		"bDestroy": true,
		"searching": false, 
		"paging": false,
		"bSort" : false,
		"bInfo" : false,
		"ajax": {
		    url : site_url+"timesheet/date_wise_list/?start_date="+$('#start_date').val()+"&end_date="+$('#end_date').val()+"&user_id="+$('#user_id').val(),
			type : 'GET'
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
            
        
    });
	
	
	// Add placeholder to the datatable filter option
    $('.dataTables_filter input[type=search]').attr('placeholder','Type to filter...');


    // Enable Select2 select for the length option
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
	
	$('[data-plugin="xin_select"]').select2($(this).attr('data-options'));
    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	

}