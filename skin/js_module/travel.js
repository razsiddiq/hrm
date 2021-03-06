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
	 var xin_table = $('#xin_table').DataTable({
		"bDestroy": true,
		"ajax": {
			url : base_url+"/travel_list/",
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




$('#description').summernote({
  height: 160,
  minHeight: 292,
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

/* Delete data 
$("#delete_record").submit(function(e){

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
*/

	
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
	var travel_id = button.data('travel_id');
	var modal = $(this);
	if(travel_id!=undefined){
$.ajax({
	url : base_url+"/read/",
	type: "GET",
	data: 'jd=1&is_ajax=1&mode=modal&data=travel&travel_id='+travel_id,
	success: function (response) {
		if(response) {
			$("#ajax_modal").html(response);
		}
	}
	});
	}
});
$('.view-modal-data').on('show.bs.modal', function (event) {
	var button = $(event.relatedTarget);
	var travel_id = button.data('travel_id');
	var modal = $(this);
$.ajax({
	url : base_url+"/read/",
	type: "GET",
	data: 'jd=1&is_ajax=1&mode=view_modal&data=view_travel&travel_id='+travel_id,
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
	//var description = $("#description").code();
	$('.icon-spinner3').show();
	$.ajax({
		type: "POST",
		url: e.target.action,
		data: obj.serialize()+"&is_ajax=1&add_type=travel&form="+action,//+"&description="+description,
		cache: false,
		success: function (JSON) {
			if (JSON.error != '') {
				toastr.error(JSON.error);
				$('.save').prop('disabled', false);
				$('.icon-spinner3').hide();
			} else {
				xin_table.ajax.reload(function(){ 
					toastr.success(JSON.result);
				}, true);
				$('.add-form').fadeOut('slow');
				$('.icon-spinner3').hide();
				$('#xin-form')[0].reset(); // To reset form fields
				$('form#xin-form select').val('').trigger("change");
				$('.save').prop('disabled', false);
				$(".note-editable").html('');
				$(".add-new-form").show();
			}
		}
	});
});
});
