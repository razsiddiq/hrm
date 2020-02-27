$(document).ready(function() {
	$(".styled, .multiselect-container input").uniform({
        radioClass: 'choice'
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
	 var xin_table = $('#xin_table').DataTable({
		"bDestroy": true,
		"ajax": {
			url : site_url+"timesheet/holidays_list/",
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
	
	$("#checkbox").click(function(){
    if($("#checkbox").is(':checked')){
        $(".selected_dept > option").prop("selected","selected");
        $(".selected_dept").trigger("change");
    }else{
        $(".selected_dept > option").prop("selected","");
         $(".selected_dept").trigger("change");
     }
});


	
	$(document).on( "change", "select.selected_dept,.select2-selection__choice__remove", function() {
              

        //$("select.selected_dept option").attr("disabled",""); //enable everything
        DisableOptions(); //disable selected values

                   });


 $('.daterange-basic').daterangepicker({
	    format: "dd mmmm yyyy",
        applyClass: 'bg-slate-600',
        cancelClass: 'btn-default',
		
    });


	
$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));

$('#description').summernote({
  height: 120,
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

$("#add_more_dept").on('click',function(e){
	
			var arr=[];
			$("select.selected_dept option:selected").each(function()
              {
                  arr.push($(this).val());
              });

		
		
        var field_id=$("#append_divs > div.row").length;
		var count_id=field_id+1;		
 		$.ajax({
		url : site_url+"timesheet/dynamic_depatment_add/",
		type: "GET",
		data: 'count_id='+count_id+'&department_id='+arr,
		success: function (response) {
			if(response) {
				$("#append_divs").append(response);
			}
			
			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	        $('[data-plugin="select_hrm"]').select2({ width:'100%' });
			
			 $('.daterange-basic').daterangepicker({
				format: "dd mmmm yyyy",
				applyClass: 'bg-slate-600',
				cancelClass: 'btn-default',
				
			});
		
		//$('select.selected_dept option:selected').attr('disabled','disabled');
		
		
		DisableOptions();
		
		}
	});
	});
	
	
	
$( document ).on( "click", ".delete", function() {
	
	$('input[name=_token]').val($(this).data('record-id'));
	$('.delete_record').attr('action',site_url+'timesheet/delete_holiday/'+$(this).data('record-id'));
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
				$('#xin_table').dataTable().api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
						
						
						}
					}
				});
		  
		  
            }
      
        });
});

/* Delete data 
$("#delete_record").submit(function(e){

e.preventDefault();
	var obj = $(this), action = obj.attr('name');
	$.ajax({
		type: "POST",
		url: e.target.action,
		data: obj.serialize()+"&is_ajax=2&type=delete&form="+action,
		cache: false,
		success: function (JSON) {
			if (JSON.error != '') {
				toastr.error(JSON.error);
			} else {
				$('.delete-modal').modal('toggle');
				xin_table.ajax.reload(function(){ 
					toastr.success(JSON.result);
				}, true);							
			}
		}
	});
});
*/
// edit
$('.edit-modal-data').on('show.bs.modal', function (event) {
	var button = $(event.relatedTarget);
	var holiday_id = button.data('holiday_id');
	var modal = $(this);
$.ajax({
	url : site_url+"timesheet/read_holiday_record/",
	type: "GET",
	data: 'jd=1&is_ajax=1&mode=modal&data=holiday&holiday_id='+holiday_id,
	success: function (response) {
		if(response) {
			$("#ajax_modal").html(response);
		}
	}
	});
});
$('.view-modal-data').on('show.bs.modal', function (event) {
	var button = $(event.relatedTarget);
	var holiday_id = button.data('holiday_id');
	var modal = $(this);
$.ajax({
	url : site_url+"timesheet/read_holiday_record/",
	type: "GET",
	data: 'jd=1&is_ajax=1&mode=modal&data=view_holiday&holiday_id='+holiday_id,
	success: function (response) {
		if(response) {
			$("#ajax_modal_view").html(response);
		}
	}
	});
});



$("#xin-form").submit(function(e){
e.preventDefault();
	var obj = $(this), action = obj.attr('name');
	//var description = $("#description").code();
	$('.save').prop('disabled', true);
	$.ajax({
		type: "POST",
		url: e.target.action,
		data: obj.serialize()+"&is_ajax=1&add_type=holiday&form="+action,//+"&description="+description,
		cache: false,
		success: function (JSON) {
			if (JSON.error != '') {
				toastr.error(JSON.error);	
				$('.save').prop('disabled', false);
			} else {
				$('#xin_table').dataTable().api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
				$('.add-form').fadeOut('slow');
				$('#xin-form')[0].reset(); // To reset form fields
				$('.save').prop('disabled', false);
				$('.selected_dept').val('0').trigger("change");
				$('form#xin-form select[name=is_publish]').val('1').trigger("change");
				$('#append_divs').html('');
				$(".note-editable").html('');
				$(".add-new-form").show();
			}
		}
	});
});
});


function delete_append_div(id){	
			
			
$('#parent_div_'+id).remove();	


	 DisableOptions();		
			
}

function DisableOptions()
{
		
	 /*$('select.selected_dept option').prop("disabled", false);
	 $('ul.select2-results__options li').prop("aria-disabled", false);
      var arr=[];
      $("select.selected_dept option:selected").each(function()
              {
                  arr.push($(this).val());
              });
		console.log(arr);
		$("select.selected_dept option").filter(function()
			{
				 
				  return $.inArray($(this).val(),arr)>-1;
	   }).attr("disabled","disabled");

*/
	   
	   
	   
	   }
