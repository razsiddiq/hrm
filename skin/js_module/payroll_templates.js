$(document).ready(function() {
	$.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        }
    });
	 var xin_table_pay = $('#xin_table_pay').DataTable({
		"bDestroy": true,
		"iDisplayLength": 50,
		"ajax": {
			url : base_url+"/template_list/",
			type : 'GET'
		},"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		},"order": [[ 6, "DESC" ]],	
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


	 
		
	/* Delete data */
	$("#delete_record").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=2&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
				} else {
					$('.delete-modal').modal('toggle');
					xin_table_pay.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);							
				}
			}
		});
	});
	
	// edit
	$('.edit-modal-data-payrol').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var salary_template_id = button.data('salary_template_id');
		var country_id = button.data('country_id');
		var modal = $(this);
		if(salary_template_id!=undefined){
	$.ajax({
		url : site_url+"payroll/template_read/",
		type: "GET",
		data: 'jd=1&is_ajax=1&mode=modal&data=payrollpay&salary_template_id='+salary_template_id+'&country_id='+country_id,
		success: function (response) {
			if(response) {
				$("#ajax_modal_payview").html(response);
			}
		}
		});
		}
	});
	
	/* Add data */ /*Form Submit*/
	/*$("#xin-form").submit(function(e){
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=1&add_type=payroll&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
					$('.icon-spinner3').hide();
				} else {
					xin_table_pay.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.icon-spinner3').hide();
					$('.add-form').fadeOut('slow');
					$('#xin-form')[0].reset(); // To reset form fields
					$('.save').prop('disabled', false);
					$(".add-new-form").show();
				}
			}
		});
	});*/
	
	$("#xin-form").submit(function(e){
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=1&add_type=payroll&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);			
					$('.save').prop('disabled', false);					
				} else {
					$('#xin-form')[0].reset();
					$('.save').prop('disabled', false);
					$('.add-form').fadeOut('slow');
					$(".add-new-form").show();
				    $('#xin_table_pay').dataTable().api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					get_employee_sal();

			}
			}
		});
	});
	
});
$( document ).on( "click", ".delete", function() {
	$('input[name=_token]').val($(this).data('record-id'));
	$('.delete_record').attr('action',site_url+'payroll/delete_template/'+$(this).data('record-id'))+'/';
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
					var xin_table_pay = $('#xin_table_pay').DataTable();
					xin_table_pay.ajax.reload(function(){                   
						toastr.success(JSON.result);
					}, true);
						get_employee_sal();
						
						}
					}
				});
		  
		  
            }
      
        });
	
	
	
	
	
	
	
});
$(document).on("keyup", function () {
	var sum_total = 0;
	var deduction = 0;
	var allowance = 0;
	var net_salary = 0;
	var bonus=0;
	var bonus_val=$('.bonus').val();
	if(bonus_val!=undefined){ bonus_val=bonus_val;}else{bonus_val=0;}
	$(".salary").each(function () {
		sum_total += +$(this).val();
	});	
	$(".deduction").each(function () {
		deduction += +$(this).val();
	});
	
	
	$(".allowance").each(function () {
		allowance += +$(this).val();
	});
	
	$("#total_with_bonus").val(sum_total);
	var total_based_contract = sum_total - bonus_val;
	$("#total_based_contract").val(total_based_contract);
	
	
	
});
function get_employee_sal(){
	$('#employee_id').html('');
	
	$.ajax({
		type: "POST",
		url:   site_url+'payroll/all_employees_salary_new/',
		data: "",
		cache: false,
		success: function (data) {
		$('#employee_id').html(data);
		
		}
		
	});
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));	

}

