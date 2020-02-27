$(document).ready(function() {
	
	$('body').on('click', '#btnPrint', function(){		
	 var printContents = document.getElementById('dvContainer').innerHTML;
     var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;	 
     window.print();
     document.body.innerHTML = originalContents;
	 setTimeout("closePrintView()", 3000); //delay required for IE to realise what's going on
	 window.onafterprint = closePrintView(); //this is the thing that makes it work i
	 function closePrintView() { //this function simply runs something you want it to do
	 document.location.href = ""; //in this instance, I'm doing a re-direct
     }
	 });
	 
	 

		
	$.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search:     '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        }
    });
	 var xin_table = $('#xin_table').DataTable({
		"bDestroy": true,
		"ajax": {
			url : base_url+"/exit_list/",
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
	
	
	$('#reason').summernote({
	  height: 210,
	  minHeight: null,
	  maxHeight: null,
	  focus: false,
	  dialogsInBody: true,
		callbacks: {
		onImageUpload: function(files) {
		var $editor = $(this);
		var data = new FormData();
		data.append('file', files[0]);
		sendFile($editor,data);
		},
		onImageUploadError: null
		}
	});
	$('.note-children-container').hide();	
	
	$( document ).on( "click", ".delete", function() {
    $('input[name=_token]').val($(this).data('record-id'));
	$('.delete_record').attr('action',base_url+'/delete/'+$(this).data('record-id'));
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
							  xin_table.ajax.reload(function(){ 
					           toastr.success(JSON.result);
				              }, true);	
						
						}
					}
				});
		  
		  
            }
      
        });
});
	// edit
	$('.edit-modal-data').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var exit_id = button.data('exit_id');
		var exit_type = button.data('exit_type');
		var modal = $(this);
	$.ajax({
		url : base_url+"/read/",
		type: "GET",
		data: 'jd=1&is_ajax=1&mode=modal&data=exit&exit_id='+exit_id+'&exit_type='+exit_type,
		success: function (response) {
			if(response) {
				$("#ajax_modal").html(response);
			}
		}
		});
	});
	$('.view-modal-data').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var exit_id = button.data('exit_id');
		var exit_type = button.data('exit_type');
		var modal = $(this);
	$.ajax({
		url : base_url+"/read/",
		type: "GET",
		data: 'jd=1&is_ajax=1&mode=modal&data=view_exit&exit_id='+exit_id+'&exit_type='+exit_type,
		success: function (response) {
			if(response) {
				$("#ajax_modal_view").html(response);
			}
		}
		});
	});
	
	/* Add data */ /*Form Submit*/
	$("#xin-form").submit(function(e){
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		//var reason = $("#reason").code();
		$('.icon-spinner3').show();
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=1&add_type=exit&form="+action,//+"&reason="+reason,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
					$('.icon-spinner3').hide();
				} else {

					//$('#html_show').html(JSON.message);
					xin_table.ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.icon-spinner3').hide();
					$('.add-form').fadeOut('slow');
					
					$('#xin-form')[0].reset(); // To reset form fields
					$('form#xin-form select').val('').trigger("change");
					$('#open_forms').hide();
					$('.save').prop('disabled', false);
					$(".note-editable").html('');
					$(".add-new-form").show();
				}
			}
		});
	});
});


function read_employee_details(){
	
// Create Base64 Object
var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/rn/g,"n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}

		$('#open_forms').show();
	    var employee_id=$('#employee_id option:selected').val();
		var exit_type=$('#exit_type option:selected').val();
		var approval_form=$('#approval_form option:selected').val();
		var date_of_leaving=$('#date_of_leaving').val();


		$('#finalsettlement-tab').html('');
		if(employee_id!='' && exit_type!=''  && approval_form!='' && date_of_leaving!=''){

        if(approval_form=='Final Settlement'){
		

	    $('#finalsettlement-tab').html('<div class="row text-center"><i class="icon-spinner2 spinner" style="font-size: 3em;color: #26a69a;margin-top: 1em;"></i></div>');
		$.ajax({
			type: "POST",
			url:  site_url+'payroll/finalsettlement/',
			data: "&is_ajax=1&add_type=employee_exit&employee_id="+employee_id+"&exit_type="+exit_type+"&date_of_leaving="+date_of_leaving,
			cache: false,
			success: function (datas) {
				
				var datas=JSON.parse(datas);
		
				$('#finalsettlement-tab').html(Base64.decode(datas.html_content));
				if(datas.leaving_date!=''){
			    $('input[name=exit_date]').val(Base64.decode(datas.leaving_date));
				$('.save').prop('disabled', false);
				}else{					
					swal({
						title: "Date of leaving not entered to this employee.",
						confirmButtonColor: "#26A69A",
						type: "info"
					});
				$('.save').prop('disabled', true);
				}
			}
		});
		
	}else{
		
		
		$('#finalsettlement-tab').html('<div class="row text-center"><i class="icon-spinner2 spinner" style="font-size: 3em;color: #26a69a;margin-top: 1em;"></i></div>');
		$.ajax({
			type: "POST",
			url:  site_url+'payroll/clearanceform/',
			data: "&is_ajax=1&add_type=employee_exit&employee_id="+employee_id+"&exit_type="+exit_type+"&date_of_leaving="+date_of_leaving,
			cache: false,
			success: function (datas) {
				
				var datas=JSON.parse(datas);
		
				$('#finalsettlement-tab').html(Base64.decode(datas.html_content));
				$('.save').prop('disabled', false);
			}
		});
		
		

	}
}


}





