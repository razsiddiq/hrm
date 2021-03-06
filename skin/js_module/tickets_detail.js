$(document).ready(function(){
	
	$.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        }
    });
	
	 var xin_comment_table = $('#xin_comment_table').DataTable({
		"bDestroy": true,
		"ajax": {
			 url : base_url+"/comments_list/"+$('#comment_ticket_id').val(),
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
	
	 var xin_attachment_table = $('#xin_attachment_table').DataTable({
		"bDestroy": true,
		"ajax": {
			url : base_url+"/attachment_list/"+$('#c_ticket_id').val(),
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
	
	$('[data-plugin="xin_select"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));

	
	
	/* update ticket employees */
	$("#assign_ticket").submit(function(e){		
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=4&type=ticket_user&view=user&form="+action,
			cache: false,
			success: function (JSON) {
				jQuery.get(base_url+"/ticket_users/"+jQuery('#ticket_id').val(), function(data, status){
					jQuery('#all_employees_list').html(data);
				});
				$('.save').prop('disabled', false);
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
	
	/* Edit ticket data */
	$("#update_status").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=3&type=update_status&update=1&view=ticket&form="+action,
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
	
	/* Edit comment */
	$("#set_comment").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=5&add_type=set_comment&update=1&view=ticket&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					xin_comment_table.ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('#xin_comment').val('');
					$('.save').prop('disabled', false);
					
				}
			}
		});
	});
	
	/// delete a comment
$( document ).on( "click", ".delete", function() {
	$('input[name=_token]').val($(this).data('record-id'));
	$('.delete_record').attr('action',base_url+'/comment_delete/'+$(this).data('record-id'));
	swal({
            title: "Are you sure?",
            text: "Record deleted can't be restored!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#FF7043",
            confirmButtonText: "Yes, delete it!",
            //closeOnConfirm: false,
            //closeOnCancel: false			
			
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
							  xin_comment_table.ajax.reload(function(){ 
					toastr.success(JSON.result);
				}, true);		
						
						}
					}
				});
		  
		  
            }
          
        });
});

$( document ).on( "click", ".delete-file", function() {
	$('input[name=_token_del_file]').val($(this).data('record-id'));
	$('#delete_record_f').attr('action',base_url+'/attachment_delete/'+$(this).data('record-id'));
	swal({
            title: "Are you sure?",
            text: "Record deleted can't be restored!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#FF7043",
            confirmButtonText: "Yes, delete it!",
            //closeOnConfirm: false,
            //closeOnCancel: false			
			
        },
        function(isConfirm){
            if (isConfirm) {
				var obj = $('#delete_record_f'), action = obj.attr('name');
		
				$.ajax({
					type: "POST",
					url: $('#delete_record_f').attr('action'),
					data: obj.serialize()+"&is_ajax=8&data=task_attachment&type=delete&form="+action,
					cache: false,
					success: function (JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
						} else {
							  xin_attachment_table.ajax.reload(function(){ 
					toastr.success(JSON.result);
				}, true);		
						
						}
					}
				});
		  
		  
            }
          
        });
});
	
	/* Delete ticket comment
	$("#delete_record").submit(function(e){
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=6&data=ticket_comment&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
				} else {
					$('.delete-modal').modal('toggle');
					xin_comment_table.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
				}
			}
		});
	}); */
	
	/* Delete task file 
	$("#delete_record_f").submit(function(e){
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=8&data=ticket_attachment&type=delete&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
				} else {
					$('.delete-modal-file').modal('toggle');
					xin_attachment_table.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					
				}
			}
		});
	});*/	
	
	/* Edit note */
	$("#add_note").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=9&type=add_note&update=2&view=note&form="+action,
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
										
	/* Add ticket file */ /*Form Submit*/
	$("#add_attachment").submit(function(e){
		var fd = new FormData(this);
		var obj = $(this), action = obj.attr('name');
		fd.append("is_ajax", 7);
		fd.append("add_type", 'dfile_attachment');
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
					xin_attachment_table.ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('#add_attachment')[0].reset(); // To reset form fields
					$('.save').prop('disabled', false);
				}
			},
			error: function() 
			{
				toastr.error('Bug. Something went wrong, please try again.');
				$('.save').prop('disabled', false);
			} 	        
	   });
	});	
}); // jquery load
	
