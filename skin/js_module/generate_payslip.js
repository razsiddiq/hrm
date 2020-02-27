$(document).ready(function() {
select_filter();



//var month_year=$('#month_year').val();
//jQuery('span#p_month').html(month_year);

//$(".styled").uniform({ radioClass: 'choice'});
	
/*
var counts=jQuery('select#employee_id option').length;
counts=counts-1;
var total=0;


	
setInterval(function () {  
var current_count=jQuery('#current_count').val();
	if(parseInt(current_count) < parseInt(counts)){
	total=parseInt(current_count)+parseInt(200);
	init_table(total); 
	}
},
				
				
 5000);
	
init_table(250	);
function init_table(limit){*/

// Month & Year



// delete
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
			xin_table.api().ajax.reload(function(){ 
				toastr.success(JSON.result);
			}, true);							
		}
	}
});
});

// detail modal data payroll
$('.small-view-modal').on('show.bs.modal', function (event) {
var button = $(event.relatedTarget);
var employee_id = button.data('employee_id');
var modal = $(this);
var t_salary_after_cal=$('#total_salary_'+employee_id).val();
var t_payment_date=$('#pay_date_'+employee_id).val();
var t_working_hours=$('#total_working_hours_'+employee_id).val();
var r_working_hours=$('#required_working_hours_'+employee_id).val();
var shift_hours=$('#shift_hours_'+employee_id).val();
var late_working_hours=$('#late_working_hours_'+employee_id).val();
var actual_days_worked=$('#actual_days_worked_'+employee_id).val();
var t_ot_hours_amount=$("#ot_hours_amount_"+employee_id).val();
var t_basic_salary=$('#basic_salary_'+employee_id).val();
var t_salary_with_bonus=$('#salary_with_bonus_'+employee_id).val();
var t_salary_components=$('#salary_components_'+employee_id).val();
var t_bonus=$('#bonus_'+employee_id).val();
var t_salary_template_id=$('#salary_template_id_'+employee_id).val();
var t_leave_start_date=$('#leave_start_date_'+employee_id).val();
var t_leave_end_date=$('#leave_end_date_'+employee_id).val();
var t_annual_leave_salary=$('#annual_leave_salary_'+employee_id).val();
var t_month_salary=$('#month_salary_'+employee_id).val();
var t_joining_month_salary=$('#joining_month_salary_'+employee_id).val(); 
var t_visa_type=$('#visa_type_'+employee_id).val();
var t_joining_month_hours=$('#joining_month_hours_'+employee_id).val();
var t_p_m_required_working_hours=$('#p_m_required_working_hours_'+employee_id).val();
var t_driver_delivery_details=$('#driver_delivery_details_'+employee_id).val();
var t_check_365days_enabled=$('#check_365days_enabled_'+employee_id).val();	
$.ajax({
	url: site_url+'payroll/payroll_template_read/',
	type: "GET",
	data: 'jd=1&is_ajax=11&mode=not_paid&data=payroll_template&type=payroll_template&employee_id='+employee_id+'&t_salary_after_cal='+t_salary_after_cal+'&payment_date='+t_payment_date+'&t_working_hours='+t_working_hours+'&r_working_hours='+r_working_hours+'&shift_hours='+shift_hours+'&late_working_hours='+late_working_hours+'&actual_days_worked='+actual_days_worked+'&t_ot_hours_amount='+t_ot_hours_amount+'&t_basic_salary='+t_basic_salary+'&t_salary_with_bonus='+t_salary_with_bonus+'&t_salary_components='+t_salary_components+'&t_bonus='+t_bonus+'&t_salary_template_id='+t_salary_template_id+'&t_leave_start_date='+t_leave_start_date+'&t_leave_end_date='+t_leave_end_date+'&t_annual_leave_salary='+t_annual_leave_salary+'&t_month_salary='+t_month_salary+'&t_joining_month_salary='+t_joining_month_salary+'&t_visa_type='+t_visa_type+'&t_joining_month_hours='+t_joining_month_hours+'&t_p_m_required_working_hours='+t_p_m_required_working_hours+'&t_driver_delivery_details='+t_driver_delivery_details+'&t_check_365days_enabled='+t_check_365days_enabled,
	success: function (response) {
		if(response) {
			$("#ajax_small_modal").html(response);
		}
	}
});
});
// detail modal data  hourlywages
$('.small-view-modal-hr').on('show.bs.modal', function (event) {
var button = $(event.relatedTarget);
var employee_id = button.data('employee_id');
var modal = $(this);
$.ajax({
	url: site_url+'payroll/hourlywage_template_read/',
	type: "GET",
	data: 'jd=1&is_ajax=11&mode=not_paid&data=hourlywages&type=hourlywages&employee_id='+employee_id,
	success: function (response) {
		if(response) {
			$("#ajax_small2_modal").html(response);
		}
	}
});
});

// detail modal data
$('.detail_modal_data').on('show.bs.modal', function (event) {
var button = $(event.relatedTarget);
var employee_id = button.data('employee_id');
var pay_id = button.data('pay_id');
var modal = $(this);
$.ajax({
	url: site_url+'payroll/make_payment_view/',
	type: "GET",
	data: 'jd=1&is_ajax=11&mode=modal&data=pay_payment&type=pay_payment&emp_id='+employee_id+'&pay_id='+pay_id,
	success: function (response) {
		if(response) {
			$("#ajax_modal_details").html(response);
		}
	}
});
});


// detail modal data
$('.emo_monthly_pay').on('show.bs.modal', function (event) {
var button = $(event.relatedTarget);
var employee_id = button.data('employee_id');
var payment_date = $('#month_year').val();
var modal = $(this);
var t_salary_after_cal=$("#total_salary_"+employee_id).val();
var t_working_hours=$('#total_working_hours_'+employee_id).val();
var r_working_hours=$('#required_working_hours_'+employee_id).val();
var shift_hours=$('#shift_hours_'+employee_id).val();
var t_ot_hours_amount=$("#ot_hours_amount_"+employee_id).val();
var t_basic_salary=$('#basic_salary_'+employee_id).val();
var t_salary_with_bonus=$('#salary_with_bonus_'+employee_id).val();
var t_salary_components=$('#salary_components_'+employee_id).val();
var t_bonus=$('#bonus_'+employee_id).val();
var t_salary_template_id=$('#salary_template_id_'+employee_id).val();
var t_leave_start_date=$('#leave_start_date_'+employee_id).val();
var t_leave_end_date=$('#leave_end_date_'+employee_id).val();
var t_annual_leave_salary=$('#annual_leave_salary_'+employee_id).val();
var t_month_salary=$('#month_salary_'+employee_id).val();
var t_joining_month_salary=$('#joining_month_salary_'+employee_id).val();
var t_visa_type=$('#visa_type_'+employee_id).val();  
var t_joining_month_hours=$('#joining_month_hours_'+employee_id).val();
var t_p_m_required_working_hours=$('#p_m_required_working_hours_'+employee_id).val();
var t_driver_delivery_details=$('#driver_delivery_details_'+employee_id).val();
var t_check_365days_enabled=$('#check_365days_enabled_'+employee_id).val();	
		
$.ajax({
	url: site_url+'payroll/pay_monthly/',
	type: "GET",
	data: 'jd=1&is_ajax=11&data=payment&type=monthly_payment&employee_id='+employee_id+'&pay_date='+payment_date+'&t_salary_after_cal='+t_salary_after_cal+'&t_working_hours='+t_working_hours+'&r_working_hours='+r_working_hours+'&t_ot_hours_amount='+t_ot_hours_amount+'&t_basic_salary='+t_basic_salary+'&t_salary_with_bonus='+t_salary_with_bonus+'&t_salary_components='+t_salary_components+'&t_bonus='+t_bonus+'&t_salary_template_id='+t_salary_template_id+'&t_leave_start_date='+t_leave_start_date+'&t_leave_end_date='+t_leave_end_date+'&t_annual_leave_salary='+t_annual_leave_salary+'&t_month_salary='+t_month_salary+'&t_joining_month_salary='+t_joining_month_salary+'&t_visa_type='+t_visa_type+'&t_joining_month_hours='+t_joining_month_hours+'&t_p_m_required_working_hours='+t_p_m_required_working_hours+'&t_driver_delivery_details='+t_driver_delivery_details+'&t_check_365days_enabled='+t_check_365days_enabled+'&shift_hours='+shift_hours,
	success: function (response) {
		if(response) {
			$("#emo_monthly_pay_aj").html(response);
		    $('#to_required_working_hours_'+employee_id).val($("#required_working_hours_"+employee_id).val());
            $('#to_total_working_hours_'+employee_id).val($("#total_working_hours_"+employee_id).val());
            $('#to_late_working_hours_'+employee_id).val($("#late_working_hours_"+employee_id).val());
            $('#to_rate_per_hour_contract_bonus_'+employee_id).val($("#rate_per_hour_contract_bonus_"+employee_id).val());
            $('#to_rate_per_hour_contract_only_'+employee_id).val($("#rate_per_hour_contract_only_"+employee_id).val());
            $('#to_rate_per_hour_basic_only_'+employee_id).val($("#rate_per_hour_basic_only_"+employee_id).val());
            $('#to_ot_day_rate_'+employee_id).val($("#ot_day_rate_"+employee_id).val());
            $('#to_ot_night_rate_'+employee_id).val($("#ot_night_rate_"+employee_id).val());
            $('#to_ot_holiday_rate_'+employee_id).val($("#ot_holiday_rate_"+employee_id).val());
            //$('#to_total_salary_'+employee_id).val($("#total_salary_"+employee_id).val());
			$('#to_ot_hours_amount_'+employee_id).val($("#ot_hours_amount_"+employee_id).val());
			$('#to_leave_salary_amount_'+employee_id).val($("#leave_salary_amount_"+employee_id).val());
			$('#to_leave_salary_paid_'+employee_id).val($("#leave_salary_paid_"+employee_id).val());
			$('#to_actual_days_worked_'+employee_id).val($("#actual_days_worked_"+employee_id).val());
         


		}
	}
});


});

$('.emo_hourly_pay').on('show.bs.modal', function (event) {
var button = $(event.relatedTarget);
var employee_id = button.data('employee_id');
var payment_date = $('#month_year').val();
var modal = $(this);
$.ajax({
	url: site_url+'payroll/pay_hourly/',
	type: "GET",
	data: 'jd=1&is_ajax=11&data=payment&type=hourly_payment&employee_id='+employee_id+'&pay_date='+payment_date,
	success: function (response) {
		if(response) {
			$("#emo_hourly_pay_aj").html(response);
		}
	}
});
});

/* Add data */ /*Form Submit*/
$("#user_salary_template").submit(function(e){
e.preventDefault();
var obj = $(this), action = obj.attr('name');
$('.save').prop('disabled', true);
$('.icon-spinner3').show();
$.ajax({
	type: "POST",
	url: e.target.action,
	data: obj.serialize()+"&is_ajax=1&edit_type=payroll&form="+action,
	cache: false,
	success: function (JSON) {
		if (JSON.error != '') {
			toastr.error(JSON.error);
			$('.save').prop('disabled', false);
			$('.icon-spinner3').hide();
		} else {
			xin_table.api().ajax.reload(function(){ 
				toastr.success(JSON.result);
			}, true);
			$('.icon-spinner3').hide();
			$('.save').prop('disabled', false);
		}
	}
});
});		
   /* Set Salary Details*/
	$("#set_salary_details").submit(function(e){
		e.preventDefault();
		select_filter();
		
	});

$('.content').on('click', '.bs_styled_hold,#example-select-all', function(){
var lens = $(".bs_styled_hold:checked").length; 
  
if(lens>0){
$('.remove_hold').prop('disabled', false);
}else{
$('.remove_hold').prop('disabled', true);
}
 
});
	$('.content').on('click', '.make_all_payment,.put_on_hold,.remove_hold', function(){
		 
		   var rel_status=$(this).attr('rel');
		   $('.success_hold_status').val(rel_status);
		   
		 	 
		   if(rel_status==3){
		   var len = $(".bs_styled_hold:checked").length;   
		   }else{
		   var len = $(".bs_styled:checked").length;
		   }
		
		   if(len>0){
		   $('#frm-example').submit();
		   }else{
	
        swal({
            title: "Select atleast one checkbox",
            text: "",
            html: true,
			confirmButtonColor: "#26A69A",
        });
		
		   }
		  
	});
   
// Handle click on "Select all" control
   $('#example-select-all').on('click', function(){
	  var xin_table = $('#xin_table').DataTable();
      // Check/uncheck all checkboxes in the table
      var rows = xin_table.rows({ 'search': 'applied' }).nodes();
	
	
      $('input[type="checkbox"]', rows).prop('checked', this.checked);
   });
   
    $('#xin_table tbody').on('change', 'input[type="checkbox"]', function(){
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
   
     $('#frm-example').on('submit', function(e){
      var form = this;
      var xin_table = $('#xin_table').DataTable();
      // Iterate over all checkboxes in the table
       xin_table.$('input[type="checkbox"]').each(function(){
         // If checkbox doesn't exist in DOM
         if(!$.contains(document, this)){
            // If checkbox is checked
            if(this.checked){
               // Create a hidden element 
               $(form).append(
                  $('<input>')
                     .attr('type', 'hidden')
                     .attr('name', this.name)
                     .val(this.value)
               );
            }
         } 
      });
	
        swal({
            title: "This may take a while",
            text: "<i class='icon-spinner2 spinner' style='font-size: 2em;color: #26a69a;'></i>",
            html: true,
        });
        $('.sa-confirm-button-container').hide(); 
		
		
	 $.ajax({
			type: "POST",
			url: e.target.action,
			data: $(form).serialize()+"&is_ajax=11&add_type=make_all_payment&paydate="+$('input[name=pay_date]').val(),
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.sa-confirm-button-container').show(); 
				    $('.confirm').click(); 				
				} else {	
				
					var xin_table = $('#xin_table').DataTable();
					xin_table.ajax.reload(function(){                   
						toastr.success(JSON.result);
					}, true);
				 $('.confirm').click(); 
				 $('.sa-confirm-button-container').show(); 	
				}
			}
		});
		
	 	//$('input[type="checkbox"]').prop('checked', false);
		
	  $('.remove_hold').prop('disabled', true);
      //console.log("Form submission", $(form).serialize()); 
      e.preventDefault();
   });
   
   
});
function clear_filter(){
	$('.department_value').val(0).trigger("change");
	$('.location_value').val(1).trigger("change");
	select_filter();
}
function select_employee(val){
	if(val!=0){
	$('.department_value').val(0);
	$('.location_value').val(0);
	}else{
	$('.department_value').val(1);
	$('.location_value').val(1);	
	}
}
function select_filter(){
	
var employee_id = jQuery('#employee_id').val();
var department_value=$('.department_value').val();
var location_value=$('.location_value').val();
var month_year=$('#month_year').val();
jQuery('span#p_month').html(month_year);
$.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        }
    });
	
	/*

    $('#example').DataTable({
			serverSide: true,
			ordering: false,
			searching: false,
        ajax: function ( data, callback, settings ) {
            var out = [];
 
            for ( var i=data.start, ien=data.start+data.length ; i<ien ; i++ ) {
                out.push( [ i+'-1', i+'-2', i+'-3', i+'-4', i+'-5' ] );
            }
 
            setTimeout( function () {
                callback( {
                    draw: data.draw,
                    data: out,
                    recordsTotal: 5000000,
                    recordsFiltered: 5000000
                } );
            }, 50 );
        },
				scrollY: 200,
				scroller: {
					loadingIndicator: true
				}
    });

	*/
	
	 var xin_table = $('#xin_table').DataTable({
		"bDestroy": true,
		"iDisplayLength": 100,
		///serverSide: true,
       // ordering: false,
       // searching: false,
		"ajax": {
			url : site_url+"payroll/payslip_list_new/?employee_id="+employee_id+"&month_year="+month_year+"&department_value="+department_value+"&location_value="+location_value,
			type : 'GET',			
			error: function (jqXHR, textStatus, errorThrown) {
				console.log(jqXHR);
				console.log(textStatus);
				console.log(errorThrown);
			},
		},
		//scrollY: 200,
       // scroller: {
       //     loadingIndicator: true
       // },
		"deferRender": true,
		"fnDrawCallback": function(settings){
		$('[data-popup=popover-custom]').popover({
		template: '<div class="popover border-teal-400"><div class="arrow"></div><h3 class="popover-title bg-teal-400"></h3><div class="popover-content"></div></div>'
	});      
		},'columnDefs': [{
         'targets': 0,
         'searchable':false,
         'orderable':false,
         'className': 'dt-body-center',
         'render': function (data, type, full, meta){
			  console.log(full);
			  if(full[8]=='<span class="label label-success">Paid</span>'){
				  return '<label><input type="checkbox" checked disabled><span></span></label>';
			  }else if(full[8]=='<span class="label label-warning">Hold</span>'){
				  return '<label><input type="checkbox" class="bs_styled_hold" name="id[]" value="'+ $('<div/>').text(data).html() + '"><span></span></label>';	
			  }else if(full[8]=='<span class="label label-info">Leave Settlement</span>'){
				  return '<span class="label label-info">Leave Settlement</span>';
			  }else{
			      return '<label><input type="checkbox" class="bs_styled" name="id[]" value="'+ $('<div/>').text(data).html() + '"><span></span></label>';	
			  } 	


				
         }
      }],
      'order': [1, 'asc'],
            buttons: [
			/*{
              
                text: 'Make All Payment',
                className: 'btn bg-teal-400 make_all_payment',
             
                },*/			
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