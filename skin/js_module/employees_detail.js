var user_id1 = $('#user_id').val();
$(document).ready(function(){	
	
	
	
	
		$('.clockpicker').clockpicker();
var input = $('.timepicker').clockpicker({
	placement: 'bottom',
	align: 'left',
	autoclose: true,
	'default': 'now'
});

	$('.down-modal-data').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var field_id = button.data('field_id');
		var field_type = button.data('field_type');
		
		var field_add = '&data=document_image_preview&type=document_image_preview&field_type='+field_type+'&';
		
		var modal = $(this);
		$.ajax({
			url: site_url+'employees/dialog_image_'+field_type+'/',
			type: "GET",
			data: 'jd=1'+field_add+'field_id='+field_id,
			success: function (response) {
				if(response) {
					$("#ajax_modal_down").html(response);
				}
			}
		});
     });
	 
	 
    // Initialize with options
    $(".select-icons").select2({
        templateResult: iconFormat,
        minimumResultsForSearch: Infinity,
        templateSelection: iconFormat,
        escapeMarkup: function(m) { return m; }
    });
	// get data
	$('.edit-modal-data').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var field_id = button.data('field_id');
		var field_tpe = button.data('field_type');
		var field_role = button.data('field_role');
		if(field_tpe == 'contact'){
			var field_add = '&data=emp_contact&type=emp_contact&';
		} else if(field_tpe == 'qualification'){
			var field_add = '&data=emp_qualification&type=emp_qualification&';
		} else if(field_tpe == 'work_experience'){
			var field_add = '&data=emp_work_experience&type=emp_work_experience&';
		} else if(field_tpe == 'bank_account'){
			var field_add = '&data=emp_bank_account&type=emp_bank_account&';
		} else if(field_tpe == 'contract'){
			var field_add = '&data=emp_contract&type=emp_contract&';
		} else if(field_tpe == 'imgdocument'){
			var field_add = '&data=e_imgdocument&type=e_imgdocument&';
		}
		var modal = $(this);
		
		if(field_tpe!=undefined){
		$.ajax({
			url: site_url+'employees/dialog_'+field_tpe+'/',
			type: "GET",
			data: 'jd=1'+field_add+'field_id='+field_id+'&field_role='+field_role,
			success: function (response) {
				if(response) {
					$("#ajax_modal").html(response);
				}
			}
		});
	}
		
   });
   
   $("#basic_info").submit(function(e){
		var fd = new FormData(this);
		var obj = $(this), action = obj.attr('name');
	
		fd.append("is_ajax", 1);
		fd.append("type", 'basic_info');
		fd.append("data", 'basic_info');
		fd.append("form", action);
		e.preventDefault();
		$('.save').prop('disabled', true);
		$.ajax({
			url: e.target.action,
			type: "POST",
			data:  fd,
			contentType: false,
			cache: false,
			processData:false,
			success: function(JSON)
			{
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					toastr.success(JSON.result);
					$('.save').prop('disabled', false);
					progress_bar(user_id1);
					select_payroll_country(user_id1);
				}
			},
			error: function() 
			{
				toastr.error(JSON.error);
				$('.save').prop('disabled', false);
			} 	        
	   });
	});
	
	

	/* Update profile picture */
	$("#f_profile_picture").submit(function(e){
		var fd = new FormData(this);
		var user_id = $('#user_id').val();
		var session_id = $('#session_id').val();
		var obj = $(this), action = obj.attr('name');
		fd.append("is_ajax", 2);
		fd.append("type", 'profile_picture');
		fd.append("data", 'profile_picture');
		fd.append("form", action);
		e.preventDefault();
		$('.save').prop('disabled', true);
		$.ajax({
			url: e.target.action,
			type: "POST",
			data:  fd,
			contentType: false,
			cache: false,
			processData:false,
			success: function(JSON)
			{
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					toastr.success(JSON.result);
					$('#f_profile_picture')[0].reset();
					$('#remove_file').show();
					$("#remove_profile_picture").attr('checked', false);
					$('#u_file').attr("src", JSON.img);
					if(user_id == session_id){
						$('.user_avatar').attr("src", JSON.img);
					}
					$('.save').prop('disabled', false);
				}
			},
			error: function() 
			{
				toastr.error(JSON.error);
				$('.save').prop('disabled', false);
			} 	        
	   });
	});
	  // Enable Select2 select for the length option
    $('.maximum_working_hours').select2({
        minimumResultsForSearch: Infinity,
        width: '100%'
    });
	
	$(".styled").uniform({
        radioClass: 'choice'
    });
	
	
	/* Update social networking */
	$("#f_social_networking").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=3&data=social_info&type=social_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					toastr.success(JSON.result);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
	
var des_name=$(".designation_id option:selected").text();
self_reportingmanager(des_name);

$(".click_same_ad").click(function(){





if($('#same_as_home_address').is(':checked')){
		//alert('checked');
		$('#residing_address1').val($("input[name=address]").val());
		$('#residing_address2').val($("input[name=address2]").val());
		$('#residing_city').val($("input[name=city]").val());
		$('#residing_area').val($("input[name=area]").val());
		$('#residing_zipcode').val($("input[name=zipcode]").val());
		$('#residing_country').val($('#home_country').val()).trigger("change");
	} else {
		//alert('not');
		$('#residing_address1').val('');
		$('#residing_address2').val('');
		$('#residing_city').val('');
		$('#residing_area').val('');
		$('#residing_zipcode').val('');
		$('#residing_country').val('').trigger("change");
}
});	
	 // get departments
	 /*	 jQuery("#aj_department").change(function(){
		jQuery.get(site_url+"employees/designation/"+jQuery(this).val(), function(data, status){
			jQuery('#designation_ajax').html(data);
		});
	});*/
	
	$(".nav-tabs-link").click(function(){
		var profile_id = $(this).data('profile');
		var profile_block = $(this).data('profile-block');
		$('.nav-item-link').removeClass('active-link');
		$('.current-tab').hide();
		$('#'+profile_block).show();
		$('#user_details_'+profile_id).addClass('active-link');
	});
	
	$.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        }
	});
	
	var xin_table_immigration = $('#xin_table_imgdocument').dataTable({
		"bDestroy": true,
		"ajax": {
			url : site_url+"employees/immigration/"+$('#user_id').val(),
			type : 'GET'
		},"fnDrawCallback": function(settings){
		$('[data-popup=popover-custom]').popover({
		template: '<div class="popover border-teal-400"><div class="arrow"></div><h3 class="popover-title bg-teal-400"></h3><div class="popover-content"></div></div>'
	});     
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
	

	var xin_table_offerletter = $('#xin_table_offerletter').dataTable({
		"bDestroy": true,
		"ajax": {
			url : site_url+"employees/immigration/"+$('#user_id').val()+'/7',
			type : 'GET'
		},"fnDrawCallback": function(settings){
		$('[data-popup=popover-custom]').popover({
		template: '<div class="popover border-teal-400"><div class="arrow"></div><h3 class="popover-title bg-teal-400"></h3><div class="popover-content"></div></div>'
	});     
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
	
	
	var xin_table_contact = $('#xin_table_contact').dataTable({
		"bDestroy": true,
		"ajax": {
			url : site_url+"employees/contacts/"+$('#user_id').val(),
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
	
	
	var xin_table_qualification = $('#xin_table_qualification').dataTable({
		"bDestroy": true,
		"ajax": {
			url : site_url+"employees/qualification/"+$('#user_id').val(),	
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
	
	
	var xin_table_work_experience = $('#xin_table_work_experience').dataTable({
		"bDestroy": true,
		"ajax": {
			  url : site_url+"employees/experience/"+$('#user_id').val(),
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
	
	
		
	var xin_table_bank_account = $('#xin_table_bank_account').dataTable({
		"bDestroy": true,
		"ajax": {
			url : site_url+"employees/bank_account/"+$('#user_id').val(),
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
	
	
			
	var xin_table_contract = $('#xin_table_contract').dataTable({
		"bDestroy": true,
		"ajax": {
			url : site_url+"employees/contract/"+$('#user_id').val(),
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
	
	 
	
	var xin_table_pay = $('#xin_table_pay').dataTable({
		"bDestroy": true,
		"ajax": {
			url : site_url+"payroll/template_list_employee/"+$('#user_id').val(),
			type : 'GET'
		},"fnDrawCallback": function(settings){
		$('[data-popup=popover-custom]').popover({
		template: '<div class="popover border-teal-400"><div class="arrow"></div><h3 class="popover-title bg-teal-400"></h3><div class="popover-content"></div></div>'
	});       
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
	
	
	
	// On page load: table_contacts

	
	 $('.dataTables_filter input[type=search]').attr('placeholder','Type to filter...');
    // Enable Select2 select for the length option
     $('.dataTables_length select,.change_visa_t').select2({
       minimumResultsForSearch: Infinity,
       width: 'auto'
     });
	
   //$('[data-plugin="xin_select"]').select2($(this).attr('data-options'));
	
	$(document).on( "click", ".default-account", function() {
			var bankaccount_id = $(this).data('bank_account_id');
			var employee_id = $(this).data('user_updated_id');
			$.ajax({
			type: "GET",
			url: site_url+"employees/default_bank_account/?bankaccount_id="+bankaccount_id+"&employee_id="+employee_id,
			success: function (JSON) {	
			xin_table_bank_account.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
	
	}
    });
    });
	
	$(document).on( "click", ".delete", function() {
		
    $('input[name=_token]').val($(this).data('record-id'));
	$('input[name=token_type]').val($(this).data('token_type'));

	if($(this).data('token_type') == 'offerletter'){
		var token_type= 'imgdocument';	
	}else{
		var token_type= $(this).data('token_type');	
	}
	 $('.delete_record').attr('action',site_url+'employees/delete_'+token_type+'/'+$(this).data('record-id'));
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
				/*var obj = $('.delete_record'), action = obj.attr('name');		
				$.ajax({
					type: "POST",
					url: $('.delete_record').attr('action'),
					data: obj.serialize()+"&is_ajax=2&form="+action,
					cache: false,
					success: function (JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
						} else {

								toastr.success(JSON.result);}
					}
				});*/
		  
			var tk_type = $('#token_type').val();
			if(tk_type == 'contact'){
				var field_add = '&is_ajax=6&data=delete_record&type=delete_contact&';
				var tb_name = 'xin_table_'+tk_type;
			} else if(tk_type == 'qualification'){
				var field_add = '&is_ajax=12&data=delete_record&type=delete_qualification&';
				var tb_name = 'xin_table_'+tk_type;
			} else if(tk_type == 'work_experience'){
				var field_add = '&is_ajax=15&data=delete_record&type=delete_work_experience&';
				var tb_name = 'xin_table_'+tk_type;
			} else if(tk_type == 'bank_account'){
				var field_add = '&is_ajax=18&data=delete_record&type=delete_bank_account&';
				var tb_name = 'xin_table_'+tk_type;
			} else if(tk_type == 'contract'){
				var field_add = '&is_ajax=21&data=delete_record&type=delete_contract&';
				var tb_name = 'xin_table_'+tk_type;
			} else if(tk_type == 'imgdocument'){
				var field_add = '&is_ajax=30&data=delete_record&type=delete_imgdocument&';
				var tb_name = 'xin_table_'+tk_type;
			} else if(tk_type == 'offerletter'){
				var field_add = '&is_ajax=30&data=delete_record&type=delete_imgdocument&';
				var tb_name = 'xin_table_'+tk_type;
			}
	    //e.preventDefault();
		var obj = $('.delete_record'), action = obj.attr('name');
		$.ajax({
			url: $('.delete_record').attr('action'),
			type: "post",
			data: '?'+obj.serialize()+field_add+"form="+action,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
				} else {
					$('#'+tb_name).dataTable().api().ajax.reload(function(){ 
						toastr.success(JSON.result);
						progress_bar(user_id1);
					}, true);
					
				}
			}
		});
            }
      
        });


	});
		

	
	
	/* Add contact info */
	jQuery("#contact_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=4&data=contact_info&type=contact_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_contact.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
						progress_bar(user_id1);
					}, true);
					jQuery('#contact_info')[0].reset(); // To reset form fields
				    jQuery('form#contact_info select').val('').trigger("change");
					$('select.change_country_code').val($("select.change_country_code option:first").val()).trigger("change");
					jQuery('.save').prop('disabled', false);
					$('.add-form').fadeOut('slow');
					$(".add-new-form").show();
			        $('span').removeClass('checked');
				}
			}
		});
	});
	
	/* Add contact info */
	jQuery("#contact_info2").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save2').prop('disabled', true);
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=4&data=contact_info&type=contact_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					jQuery('.save2').prop('disabled', false);
				} else {
					toastr.success(JSON.result);
					jQuery('.save2').prop('disabled', false);
				}
			}
		});
	});
	
	
	
	/* Add document info */
	$("#immigration_info").submit(function(e){
		var fd = new FormData(this);
		var obj = $(this), action = obj.attr('name');
		fd.append("is_ajax", 7);
		fd.append("type", 'immigration_info');
		fd.append("data", 'immigration_info');
		fd.append("form", action);
		e.preventDefault();
		$('.save').prop('disabled', true);

		var document_type_id = $('#document_type_id').val();

		if(document_type_id==7){
			var d_type='xin_table_offerletter';
		}else{
			var d_type='xin_table_imgdocument';
		}

		$.ajax({
			url: e.target.action,
			type: "POST",
			data:  fd,
			contentType: false,
			cache: false,
			processData:false,
			success: function(JSON)
			{
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {

					if(document_type_id==7){
						xin_table_offerletter.api().ajax.reload(function(){ 
							toastr.success(JSON.result);
							progress_bar(user_id1);
						}, true);
					}else{
						xin_table_immigration.api().ajax.reload(function(){ 
							toastr.success(JSON.result);
							progress_bar(user_id1);
						}, true);
					}
					
					jQuery('#immigration_info')[0].reset(); // To reset form fields
					$('form#immigration_info select').val('').trigger("change");
					$('.fileinput-remove').click();
			
					$('.save').prop('disabled', false);
					$('.add-form').fadeOut('slow');
					$(".add-new-form").show();

					if(document_type_id==7){
						$('.nav-tabs a[href="#offer_letter"]').tab('show');
					}
					
				}
			},
			error: function() 
			{
				toastr.error(JSON.error);
				$('.save').prop('disabled', false);
			} 	        
	   });
	});
	
	/* Add qualification info */
	jQuery("#qualification_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=10&data=qualification_info&type=qualification_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_qualification.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
						progress_bar(user_id1);
					}, true);
					jQuery('#qualification_info')[0].reset(); // To reset form fields
				    jQuery('form#qualification_info select').val('').trigger("change");
					jQuery('.save').prop('disabled', false);
					$('.add-form').fadeOut('slow');
					$(".add-new-form").show();
				}
			}
		});
	});
	
	/* Add work experience info */
	jQuery("#work_experience_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=13&data=work_experience_info&type=work_experience_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_work_experience.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
						progress_bar(user_id1);
					}, true);
					jQuery('#work_experience_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
					$('.add-form').fadeOut('slow');
					$(".add-new-form").show();
				}
			}
		});
	});
	
	/* Add bank account info */
	jQuery("#bank_account_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=16&data=bank_account_info&type=bank_account_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_bank_account.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
						progress_bar(user_id1);
					}, true);
					jQuery('#bank_account_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
					$('.add-form').fadeOut('slow');
					$(".add-new-form").show();
				}
			}
		});
	});
	
	
		jQuery("#contract_info").submit(function(e){
		var fd = new FormData(this);
		var obj = $(this), action = obj.attr('name');
		fd.append("is_ajax", 19);
		fd.append("type", 'contract_info');
		fd.append("data", 'contract_info');
		fd.append("form", action);
		e.preventDefault();
		$('.save').prop('disabled', true);
		$.ajax({
			url: e.target.action,
			type: "POST",
			data:  fd,
			contentType: false,
			cache: false,
			processData:false,
			success: function(JSON)
			{
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
	
				} else {
					xin_table_contract.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
						progress_bar(user_id1);
					}, true);
					jQuery('#contract_info')[0].reset(); // To reset form fields
					$('form#contract_info select').val('').trigger("change");
					$('.fileinput-remove').click();
				
					$('.save').prop('disabled', false);
					$('.add-form').fadeOut('slow');
					$(".add-new-form").show();
				}
			},
			error: function() 
			{
				toastr.error(JSON.error);
				$('.save').prop('disabled', false);
			} 	        
	   });
	});
	
	
	/* Add change password */
	jQuery("#e_change_password").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=31&data=e_change_password&type=change_password&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					jQuery('.save').prop('disabled', false);
				} else {
					toastr.success(JSON.result);
					jQuery('#e_change_password')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
	
  
	
    $("#xin-form").submit(function(e){
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=1&add_type=payroll&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					progress_bar(user_id1);
					$('.save').prop('disabled', false);
					
				} else {
					$('#xin-form')[0].reset();
					$('.save').prop('disabled', false);
					$('.add-form').fadeOut('slow');
					$(".add-new-form").show();
				    $('#xin_table_pay').dataTable().api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);


//$('#apps').html(JSON.message);
			}
			}
		});
	});
	
	
	
	
	$('.edit-modal-data-payrol').on('show.bs.modal', function (event) {
		
		var button = $(event.relatedTarget);
		var salary_template_id = button.data('salary_template_id');
		var country_id = button.data('country_id');
		var field_role= button.data('field_role');
		var modal = $(this);
	
	if(salary_template_id!=undefined){
	$.ajax({
		url : site_url+"payroll/template_read/",
		type: "GET",
		data: 'jd=1&is_ajax=1&mode=modal&data=payrollpay&salary_template_id='+salary_template_id+'&country_id='+country_id+'&field_role='+field_role,
		success: function (response) {
			if(response) {
				$("#ajax_modal_payview").html(response);
			}
		}
		});
	}
		
		
	});
		
    
   /// delete a record

});	
$(document).ready(function(){
	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));		

	
	 $('.date_of_birth,.cont_date').pickadate({
        format: "dd mmmm yyyy",
        labelMonthNext: 'Go to the next month',
        labelMonthPrev: 'Go to the previous month',
        labelMonthSelect: 'Pick a month from the dropdown',
        labelYearSelect: 'Pick a year from the dropdown',
        selectMonths: true,
        selectYears: 100	
    });	
	
	
	$('.designation_id').on('change',function(){
	var des_name=$(".designation_id option:selected").text();
	self_reportingmanager(des_name);
	});
	
	$('.change_visa_t').on('change',function(){
		var v_type=$('.change_visa_t').val();
		$('#document_number').val('');
		if(v_type==0){
		$('#document_number').attr('pattern','\\d{3}[\\/]\\d{4}[\\/]\\d{7}');
		$('#document_number').attr('title','Should be Numeric & Format should be 123/1234/1234567');
		$('#document_number').attr('maxlength','16');
		}else{
		$('#document_number').attr('pattern','\\d{3}[\\/]\\d{4}[\\/]\\d{1}[\\/]\\d{7}');
		$('#document_number').attr('title','Should be Numeric & Format should be 123/1234/1/1234567');
		$('#document_number').attr('maxlength','18');
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
	
	//$("#total_deduction").val(deduction);
	//$("#total_allowance").val(allowance);
	//var net_salary = sum_total - deduction;
	//$("#net_salary").val(net_salary);
	
});


progress_bar(user_id1);
function progress_bar(id){
	$.ajax({
	url : base_url+"/progress_bar/",
	type: "GET",
	data: 'jd=1&is_ajax=1&data=progress_bar&user_id='+id,
	success: function (response) {
		if(response) {
	    $("#progress_div").html(response).animate();
		
		$('[data-toggle="tooltip"]').tooltip();
			
		}
	}
	});
	
}


function emp_inactive_status(val){
	if(val==0){
	 $('.date_of_leaving').next('.picker').addClass('picker--opened');
	}else{
		 $('.date_of_leaving').next('.picker').removeClass('picker--opened');
		
	}
}
function contract_change(id){
	$('[name="to_date"]').val('');
	if(id==18){
		$('.contract_hide').show();
		$('.contract_show').hide();
	}else{
		$('.contract_hide').hide();
		$('.contract_show').show();
	}
}
function iconFormat(icon) {
        var originalOption = icon.element;
        if (!icon.id) { return icon.text; }
        var $icon = "<i class='icon-" + $(icon.element).data('icon') + "'></i>" + icon.text;

        return $icon;
    }

	
function send_approvals(id,created_by,department_id,type_of_approval,head_id,field_id){
$('#append_alert').html('');
var employee_id=id;
var created_by=created_by;
var department_id=department_id;
var head_id=head_id;

$.ajax({
		type: "POST",
		url:   site_url+'employees/send_approvals/',
		data: "&is_ajax=1&type=send_approvals&employee_id="+employee_id+"&created_by="+created_by+"&department_id="+department_id+"&type_of_approval="+type_of_approval+"&head_id="+head_id+"&field_id="+field_id,
		cache: false,
		success: function (JSON) {
			if (JSON.error != '') {
				toastr.error(JSON.error);							
			} else {
		
				toastr.success(JSON.result);
				$('#append_alert').html(JSON.message);
				$('.btn-approval_'+head_id.replace(' ','')).hide();
			
		}
		}
	});

}

function self_reportingmanager(des_name){
	if(des_name=='Managing Director'){
	$('.self_manager_show').show();
	$('.reporting_manager_show').hide();
	$('.reporting_manager_class').attr('name','');
	$('.self_manager_class').attr('name','reporting_manager');	
	}else{
	$('.self_manager_show').hide();
	$('.reporting_manager_show').show();
	$('.self_manager_class').attr('name','');
	$('.reporting_manager_class').attr('name','reporting_manager');	
	}
}

function create_approve_request(id){

	$('.nondisclosure').prop('disabled', true);
	$.ajax({
	url : site_url+'employees/create_approval_request/',
	type: "GET",
	data: 'jd=1&is_ajax=1&data=progress_bar&user_id='+id,
	success: function (response) {
		if(response) {
		    $("#progress_div").html(response.message).animate();
		}
	}
	});
	
}

