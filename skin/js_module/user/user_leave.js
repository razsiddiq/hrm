$(document).ready(function() {

	xin_table();  
	$('[data-plugin="xin_select"]').select2($(this).attr('data-options'));
    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	    $("#xin-form").submit(function(e){	
		var fd = new FormData(this);
		var obj = $(this), action = obj.attr('name');
		//var remarks = $("#remarks").code();
		fd.append("is_ajax", 1);
		fd.append("add_type", 'leave');
		fd.append("data", 'leave');
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
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					xin_table();
					toastr.success(JSON.result);
					$("#sh_message,#sh_message_date").html('');								 
					$('.add-form').fadeOut('slow');
					$('#xin-form')[0].reset(); // To reset form fields
					$('.save').prop('disabled', false);
					$(".add-new-form").show();
				}
			}
		});
	});
	

	$('.edit-modal-data').on('show.bs.modal', function (event) {		
		var button = $(event.relatedTarget);
		var btn_type = button.data('btn_type');		
		$('.add-form').slideUp();
		if(btn_type!=undefined){
		var modal = $(this);
    	$.ajax({	
		url : site_url+"timesheet/read_leave_chk_avail/",
		type: "GET",
		data: 'jd=1&is_ajax=1&mode=modal&data=check_leave_availability&btn_type='+btn_type,
		success: function (response) {
			if(response) {
				$("#ajax_modal").html(response);
			}
		}
		});
	}
	});


	$(".edit-modal-data-1").on("show.bs.modal", function(event) {
	
		var button = $(event.relatedTarget);
		var leave_id = button.data("leave_id");
		var modal = $(this);
		if (leave_id != null) {
			$.ajax({
				url: site_url+"employee/leave/editLeaveForm",
				type: "GET",
				data:
					"jd=1&is_ajax=1&mode=modal&data=editLeaveForm&leave_id=" +
					leave_id,
				success: function(response) {
					if (response) {
						$("#ajax_modal-1").html(response);

						$('.edit-modal-data-1').modal({
						    backdrop: 'static',
						    keyboard: false
						});

						$('.form_date').datetimepicker({
					        weekStart: 1,
					        todayBtn:  1,
							autoclose: 1,
							todayHighlight: 1,
							startView: 2,
							minView: 2,
							forceParse: 0,
							pickerPosition: "bottom-left"
					    });
					}
				}
			});
		}
	});

});



function xin_table(){
	$.ajax({			
			url: site_url+"employee/leave/leave_list/",
			type: "GET",
			success: function (data) {				
				$('#xin_table').html(data);				
			}
		});
}

function editLeaveForm(leave_id,leave_type_id){

	$.ajax({
		type: "POST",
	  	url: site_url+"employee/leave/editLeaveForm",
	  	cache: false,
	  	data : {leave_id:leave_id},
	  	dataType : 'json',
	  	success: function(data){

	  		$('#edit_leave_ajax_modal').html(data.view);
	  		// $('#leave_type_dialog').select2();

	  		$('.form_date').datetimepicker({
		        weekStart: 1,
		        todayBtn:  1,
				autoclose: 1,
				todayHighlight: 1,
				startView: 2,
				minView: 2,
				forceParse: 0,
				pickerPosition: "bottom-left"
		    });

	  	}
	});
}