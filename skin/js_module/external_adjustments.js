$(document).ready(function() {
search_filter();


	
 if (Array.prototype.forEach) {
        var elems = Array.prototype.slice.call(document.querySelectorAll('.switchery'));
        elems.forEach(function(html) {
            var switchery = new Switchery(html);
        });
    }
    else {
        var elems = document.querySelectorAll('.switchery');
        for (var i = 0; i < elems.length; i++) {
            var switchery = new Switchery(elems[i]);
        }
    }
	
	
	
	$("#xin-form").submit(function(e){
		e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		
		if($('#compute_amount').is(':checked')){
		var compute_amount = $("#compute_amount").val();
		} else {
		var compute_amount = 0;
		}  
			
		
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=1&add_type=external_adjustments&form="+action+"&compute_amount="+compute_amount,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					search_filter();
					//xin_table_pay.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					//}, true);
					$('.add-form').fadeOut('slow');
					$('#xin-form')[0].reset(); 
					$('form#xin-form select').val('').trigger("change");
					$('.save').prop('disabled', false);
					$('.switchery').trigger('click');
					$(".add-new-form").show();
				}
			}
		});
	});

	
	
	$('.edit-modal-data').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var field_id = button.data('field_id');
		var field_add = '&data=ed_external_adjusments&type=external_adjustments&';		
		var modal = $(this);
		if(field_id!=undefined){
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
		}
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
	$('.department_value').val(0).trigger("change");
	$('.location_value').val(0).trigger("change");	
	$('.visa_value').val(0).trigger("change");
	$('.adjustment_type').val(0).trigger("change");
	$('.adjustment_name').val(0).trigger("change");	
	 search_filter();
}

function search_filter(){
	
	var department_id=$('.department_value').val();
	var location_id=$('.location_value').val();
	var visa_value=$('.visa_value').val();
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
			url : site_url+"payroll/adjustments_list/external_adjustments?department_id="+department_id+"&location_id="+location_id+'&visa_value='+visa_value+'&adjustment_type='+adjustment_type+'&adjustment_name='+adjustment_name,
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
       $('input[name=end_date]').val('');
	    // 50 52
        if(val==''){return false;}
		if(val==51){			
			$('#end_date_of_p').show();
		}else{
 
			$('#end_date_of_p').hide();
		}

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


function get_currency_byemployee(id){

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
                document.getElementById("form-control-currency").innerHTML = cods.html;				
      
            }
        };
        xmlhttp.open("GET",site_url+'payroll/get_currency_byemployee/'+id,true);
        xmlhttp.send();
}


