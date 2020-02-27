$(document).ready(function() {
	
$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));


xin_table_conversion('');


		
		
$( document ).on( "submit", "#compute_leave_settlement", function(e) {
e.preventDefault();
	var obj = $(this), action = obj.attr('name');
	$('.leave_settlement_show').html('');
	var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/rn/g,"n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}
	
	$('.leave_settlement_show').html('<div class="row text-center"><i class="icon-spinner2 spinner" style="font-size: 3em;color: #26a69a;margin-top: 1em;margin-bottom:1em;"></i></div>');
	//$('.save').prop('disabled', true);	
	$.ajax({
		type: "POST",
		url: e.target.action,
		data: obj.serialize()+"&is_ajax=1&type=compute_leave_settlement&form="+action,
		cache: false,
	    success: function (datas) {
				
				var datas=JSON.parse(datas);
		
				$('.leave_settlement_show').html(Base64.decode(datas.html_content));
				$('.save').prop('disabled', false);
			}
			
			
	});
	
	
});


$( document ).on( "submit", "#compute_leave_settlement_save", function(e) {
e.preventDefault();
	var obj = $(this), action = obj.attr('name');	
	$('.save_l').prop('disabled', true);	
	$.ajax({
		type: "POST",
		url: e.target.action,
		data: obj.serialize()+"&is_ajax=1&type=compute_leave_settlement&form="+action,
		cache: false,
	    success: function (JSON) {
				toastr.success(JSON.result);

				//$('.leave_settlement_show1').html(JSON.message);
				
				$('.leave_settlement_show').html('');
				$('#compute_leave_settlement')[0].reset(); // To reset form fields
				$('form#compute_leave_settlement select').val('').trigger("change");
				$('.save_l').prop('disabled', false);
				xin_table_conversion('');
		}
			
			
	});
	
	
});




$('.detail_modal_data').on('show.bs.modal', function (event) {
var button = $(event.relatedTarget);
var employee_id = button.data('employee_id');
var pay_id = button.data('pay_id');
var modal = $(this);
$.ajax({
	url: site_url+'payroll/dialog_leave_settlement/',
	type: "GET",
	data: 'jd=1&is_ajax=11&mode=modal&data=compute_leave_settlement&type=compute_leave_settlement&emp_id='+employee_id+'&pay_id='+pay_id,
	success: function (response) {
		if(response) {
			$("#ajax_modal_details").html(response);
		}
	}
});
});
	
});





function leave_settlment_month(){
	
	
	var employee_id=$('#employee_id').val();
	var month_year=$('#month_year').val();
	if(employee_id!=0){
	
	$.ajax({
		url : site_url+"payroll/read_settlement_date/",
		type: "GET",
		data: 'jd=1&is_ajax=1&type=read_settlement_date&employee_id='+employee_id+'&month_year='+month_year,
		success: function (response) {	

var datas=JSON.parse(response);	

//$('#start_date').val(datas.start_date);	
//$('#end_date').val(datas.end_date);	
				/*$(".employee_leave_card_result").html(response);
				
				 $('.conversion_leaves').select2({
			minimumResultsForSearch: Infinity,
        width: '100%'
     });
			 $('.date').pickadate({ format: "dd mmmm yyyy",});*/
		}
		
	  	
		
		
	});
	
	
	
	}
	
	
}



function xin_table_conversion(val){

var employee_id=val;

$.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search:     '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        }
    });
	 var xin_table_conversion = $('#xin_table_conversion').DataTable({
		"bDestroy": true,
		"ajax": {
			url : base_url+"/leave_settlement_list/?employee_id="+employee_id,  
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
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
	
    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));	
}

$( document ).on( "click", ".delete", function() {
	$('input[name=_token]').val($(this).data('record-id'));
	$('.delete_record').attr('action',site_url+'payroll/delete_leave_settlement/'+$(this).data('record-id'));	
	var employee_id=$('#employee_id').val();
	var xin_table_conversion1 = $('#xin_table_conversion').DataTable();
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
								xin_table_conversion1.ajax.reload(function(){
                        xin_table_conversion(employee_id);
						toastr.success(JSON.result);
					}, true);
						
						
						}
					}
				});
		  
		  
            }
      
        });
		
	});
	

