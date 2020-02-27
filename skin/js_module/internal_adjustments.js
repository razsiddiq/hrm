$(document).ready(function() {
search_filter();

check_pay_options('amount');


$(".styled").uniform({ radioClass: 'choice'});
//$('.employee_open').hide();
	$("#xin-form").submit(function(e){
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=1&add_type=internal_adjustments&form="+action,
			cache: false,
			success: function (JSON) {
				//console.log(JSON);
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else if (JSON.alerts != '') {
                    var adjustment_hours=$('input[name=adjustment_hours]').val();
					var adjustment_amount=$('input[name=adjustment_amount]').val();					
					var currency_type=$('#form-control-currency').text();					
                    //alert(pay_by);
                    if(adjustment_amount!=''){
                    var ext='<div class="form-group">' +
                                '<label class="control-label">Amount</label>' +                                
                                    '<input id="adj_amount" value="" type="text" class="form-control" readonly>' +                             
                            '</div>' ;
					}else if(adjustment_hours!='') {
					var ext='<div class="form-group">' +
                                '<label class="control-label">Hours</label>' +                                
                                    '<input id="adj_hours" value="" type="text" class="form-control" readonly>' +                             
                            '</div>' ;
					}					
					$('.save').prop('disabled', false);
					bootbox.dialog({						
						title: "You can't edit once you confirmed.Kindly check once.",						
						message: '<div class="row">  ' +
                    '<div class="col-md-6">' +
                            '<div class="form-group">' +
                                '<label class="control-label">Internal Adjustments Type</label>' +                                
                                    '<input id="adj_type" value="" type="text" class="form-control" readonly>' +                             
                            '</div>' + 
							 '<div class="form-group">' +
                                '<label class="control-label">Adjustment Name</label>' +                                
                                    '<input id="adj_name" value="" type="text" class="form-control" readonly>' +                             
                            '</div>' + 
							 
'</div>' +							
                      '<div class="col-md-6">' +
                            '<div class="form-group">' +
                                '<label class="control-label">Adjustment for Employee</label>' +                                
                                    '<input id="adj_emp" value="" type="text" class="form-control" readonly>' +                             
                            '</div>' +  
							'<div class="form-group">' +
                                '<label class="control-label">Date of Entry</label>' +                                
                                    '<input id="ad_date" value="" type="text" class="form-control" readonly>' +                             
                            '</div>' + '</div>' +	
                        '<div class="col-md-12">' + ext +
                            '<div class="form-group">' +
                                '<label class="control-label">Comments</label>' +                                
                                    '<textarea id="ad_comm" class="form-control" readonly></textarea>' +                             
                            '</div>' +                  
                    '</div>',						
						buttons: {
							danger: {
								label: "Back to edit",
								className: "btn-default",
								callback: function() {
								    $('#confirm_alert').val('');								   
								    $('#adj_type').val('');
								    $('#adj_name').val('');
								    $('#adj_emp').val('');
								    $('#adj_amount').val('');
									$('#adj_hours').val('');
								    $('#ad_comm').html('');				  
								    $('#ad_date').val('');
								}
							},
							success: {
								label: "Yes confirm it!",
								className: "bg-teal-400",
								callback: function() {
									$('#confirm_alert').val(1);
									$("#xin-form").submit();
								
								}
							},							
							
						}
					});  
						 $('#adj_type').val($("#select2-parent_type-container").text());
						 $('#adj_name').val($("#select2-child_type-container").text());
						 $('#adj_emp').val($("select[name=adjustment_for_employee] option:selected").text());
						 $('#adj_amount').val($("input[name=adjustment_amount]").val()+' '+currency_type);
						 $('#adj_hours').val($("input[name=adjustment_hours]").val()+' H');
						 $('#ad_comm').html($("textarea[name=comments]").val());				  
						 $('#ad_date').val($("input[name=end_date]").val());
				 				  
					
				} else {
					search_filter();
					//xin_table_pay.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					//}, true);
					$('.add-form').fadeOut('slow');
					$('#xin-form')[0].reset();
					$('#confirm_alert').val('');	
					$('.note').html('');					
					$('form#xin-form select').val('').trigger("change");
					$('.save').prop('disabled', false);
					$(".add-new-form").show();
				}
			}
		});
	});

	
	$('.edit-modal-data').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var field_id = button.data('field_id');
		var field_add = '&data=ed_internal_adjusments&type=internal_adjustments&';		
		var modal = $(this);
		$.ajax({
			url: site_url+'payroll/adjustment_read/',
			type: "GET",
			data: 'jd=1'+field_add+'field_id='+field_id,
			success: function (response) {
				if(response) {
					$('.icon-spinner3').hide();
					$("#ajax_modal").html(response);
				}
			}
		});
   });


$( document ).on( "click", ".delete", function() {

	$('input[name=_token]').val($(this).data('record-id'));
	$('.delete_record').attr('action',base_url+'/adjustments_delete/'+$(this).data('record-id'));
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
							 search_filter();
						     toastr.success(JSON.result);
						
						}
					}
				});
		  
		  
            }
 
        });
});


});
function clear_filter(){
	
	 search_filter();
}

function childTypechange(){
	
	var sel=$('#child_type option:selected').text();
	if(sel=='End of service Benefit'){
		$('input[name=pay_by]').val('amount').trigger('click');
		$('#show_amount').show();
		$('#show_hours').hide();
		get_amounts_byhour(1);
	}
    reset_field();
}

function clear_filter(){
    var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];;
    var date = new Date();
	$('.department_value').val(0).trigger("change");
	$('.location_value').val(0).trigger("change");	
	$('.visa_value').val(0).trigger("change");
	$('.adjustment_type').val(0).trigger("change");
	$('.adjustment_name').val(0).trigger("change");
	$('#month_year').val(date.getFullYear() + ' ' + months[date.getMonth()]);
	search_filter();
}


function search_filter(){

	var department_id=$('.department_value').val();
	var location_id=$('.location_value').val();
	var month_year=$('#month_year').val();
	var adjustment_type=$('.adjustment_type').val();
	var adjustment_name=$('.adjustment_name').val();	
	
	
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
			url : site_url+"payroll/adjustments_list/internal_adjustments?department_id="+department_id+"&location_id="+location_id+'&month_year='+month_year+'&adjustment_type='+adjustment_type+'&adjustment_name='+adjustment_name,
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

function getParentChildType(val){
	reset_field();
	    // 50 52
		if(val==''){return false;}

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

function get_amounts_byhour(val){

	var employee=$('#adjustment_for_employee').val();
	var adjustment_name=$('#child_type option:selected').text();

	if(val!='' && employee!='' && adjustment_name!=''){
		console.log(val);
			$.ajax({
					type: "POST",
					url:  site_url+'payroll/get_amounts_byhour/',
					data: "is_ajax=2&type=getamountbyhours&employee="+employee+"&adjustment_name="+adjustment_name+"&hours="+val,
					cache: false,
					success: function (dt) {
						var datas=JSON.parse(dt);
						$('.note').html(datas.message);
						$('input[name=adjustment_amount]').val(datas.value);
					}
				});
	}else{
	$('.note').html('');
						$('input[name=adjustment_amount]').val('');	
	}
	
	
}
function get_currency_byemployee(id){
	
	
	var sel=$('#child_type option:selected').text();
	if(sel=='End of service Benefit'){
		$('input[name=pay_by]').val('amount').trigger('click');
		$('#show_amount').show();
		$('#show_hours').hide();
		get_amounts_byhour(1);
	}
	
	
	reset_field();
   //  $('.employee_open').show();
	 if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
			xmlhttp1 = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
          
            if (this.readyState == 4 && this.status == 200) {
				var cods=JSON.parse(this.responseText);
                document.getElementById("form-control-currency").innerHTML = cods.html;				
      
            }
        };
		
		xmlhttp1.onreadystatechange = function() {
          
            if (this.readyState == 4 && this.status == 200) {
				var cods1=JSON.parse(this.responseText);
                document.getElementById("form-control-workinghours").innerHTML = cods1.html;				
      
            }
        };
        xmlhttp.open("GET",site_url+'payroll/get_currency_byemployee/'+id,true);
        xmlhttp.send();
		
		xmlhttp1.open("GET",site_url+'payroll/get_employee_working_hours/'+id,true);
        xmlhttp1.send();
}

function reset_field(){
$('input[name=adjustment_amount]').val('');
$('input[name=adjustment_hours]').val('');
$('.note').html('');
}


function check_pay_options(val){
reset_field();
if(val=='amount'){
$('#show_amount').show();
$('#show_hours').hide();
}else{
$('#show_hours').show();
$('#show_amount').hide();
}
}