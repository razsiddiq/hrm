$(document).ready(function() {
search_filter();


});
function clear_filter(){
	
	 $('#parent_type').val('0').trigger("change");
	 $('#child_type').val('0').trigger("change");
	 $('#user_id').val('0').trigger("change");
	 $('#payment_month').val('0').trigger("change");
	 search_filter();
}

function search_filter(){
	var parent_type=$('#parent_type').val();
	var child_type=$('#child_type').val();
	var user_id=$('#user_id').val();
	var payment_month=$('#payment_month').val();
	
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
		"ajax": {
			url : site_url+"payroll/salary_addition_deduction_list/?parent_type="+parent_type+"&child_type="+child_type+"&user_id="+user_id+"&payment_month="+payment_month,
			type : 'GET'
		},"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		},	
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
    $('.dataTables_length select,.change_country_code').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
	
    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));	


	
}
function getParentChildType(val,count){
	if (val == "0") {
        document.getElementById("child_type").innerHTML = "<option value='0'>Child Type</option>";
        return;
      } else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
          
            if (this.readyState == 4 && this.status == 200) {
				var cods=JSON.parse(this.responseText);
				//console.log(cods);
                document.getElementById("child_type").innerHTML = cods.html;
				
                $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	            $('[data-plugin="select_hrm"]').select2({ width:'100%' });
            }
        };

        xmlhttp.open("GET",site_url+'payroll/getChildType/'+val,true);
        xmlhttp.send();
		
		
		
    }
	
}