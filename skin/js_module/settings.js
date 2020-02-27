$(document).ready(function(){			

$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));

$('[data-toggle="tooltip"]').tooltip();  
	
$('.clockpicker').clockpicker();
var input = $('.timepicker').clockpicker({
	placement: 'bottom',
	align: 'left',
	autoclose: true,
	'default': 'now'
});




$(".styled").uniform({
        radioClass: 'choice'
    });
	
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
	
jQuery("#company_info").submit(function(e){
/*Form Submit*/
e.preventDefault();
	var obj = jQuery(this), action = obj.attr('name');
	jQuery('.save').prop('disabled', true);
	$('.icon-spinner3').show();
	jQuery.ajax({
		type: "POST",
		url: e.target.action,
		data: obj.serialize()+"&is_ajax=1&data=company_info&type=company_info&form="+action,
		cache: false,
		success: function (JSON) {
			if (JSON.error != '') {
				toastr.error(JSON.error);
				$('.icon-spinner3').hide();
				jQuery('.save').prop('disabled', false);
			} else {
				toastr.success(JSON.result);
				$('.icon-spinner3').hide();
				jQuery('.save').prop('disabled', false);
			}
		}
	});
});

jQuery("#system_info").submit(function(e){
/*Form Submit*/
e.preventDefault();
	if($('#enable_page_rendered').is(':checked')){
		var enable_page_rendered = $("#enable_page_rendered").val();
	} else {
		var enable_page_rendered = '';
	}
	if($('#enable_current_year').is(':checked')){
		var enable_current_year = $("#enable_current_year").val();
	} else {
		var enable_current_year = '';
	}
	if($('#enable_tax_calculation').is(':checked')){
		var enable_tax_calculation = $("#enable_tax_calculation").val();
	} else {
		var enable_tax_calculation = '';
	}

	var obj = jQuery(this), action = obj.attr('name');
	jQuery('.save').prop('disabled', true);
	$('.icon-spinner3').show();
	jQuery.ajax({
		type: "POST",
		url: e.target.action,
		data: obj.serialize()+"&is_ajax=3&data=system_info&type=system_info&form="+action+'&enable_page_rendered='+enable_page_rendered+'&enable_current_year='+enable_current_year+'&enable_tax_calculation='+enable_tax_calculation,
		cache: false,
		success: function (JSON) {
			if (JSON.error != '') {
				toastr.error(JSON.error);
				jQuery('.save').prop('disabled', false);
				$('.icon-spinner3').hide();
			} else {
				toastr.success(JSON.result);
				jQuery('.save').prop('disabled', false);
				$('.icon-spinner3').hide();
			}
		}
	});
});

jQuery("#role_info").submit(function(e){
/*Form Submit*/
e.preventDefault();
	var obj = jQuery(this), action = obj.attr('name');
	jQuery('.save').prop('disabled', true);
	$('.icon-spinner3').show();
	if($('#contact_role').is(':checked')){
		var contact_role = $("#contact_role").val();
	} else {
		var contact_role = '';
	}
	if($('#edu_role').is(':checked')){
		var edu_role = $("#edu_role").val();
	} else {
		var edu_role = '';
	}
	if($('#work_role').is(':checked')){
		var work_role = $("#work_role").val();
	} else {
		var work_role = '';
	}
	if($('#doc_role').is(':checked')){
		var doc_role = $("#doc_role").val();
	} else {
		var doc_role = '';
	}
	if($('#social_role').is(':checked')){
		var social_role = $("#social_role").val();
	} else {
		var social_role = '';
	}
	if($('#pic_role').is(':checked')){
		var pic_role = $("#pic_role").val();
	} else {
		var pic_role = '';
	}
	if($('#profile_role').is(':checked')){
		var profile_role = $("#profile_role").val();
	} else {
		var profile_role = '';
	}
	if($('#bank_account_role').is(':checked')){
		var bank_account_role = $("#bank_account_role").val();
	} else {
		var bank_account_role = '';
	}
	jQuery.ajax({
		type: "POST",
		url: e.target.action,
		data: obj.serialize()+"&is_ajax=4&data=role_info&type=role_info&form="+action+'&employee_manage_own_contact='+contact_role+'&employee_manage_own_qualification='+edu_role+'&employee_manage_own_work_experience='+work_role+'&employee_manage_own_document='+doc_role+'&employee_manage_own_social='+social_role+'&employee_manage_own_picture='+pic_role+'&employee_manage_own_profile='+profile_role+'&employee_manage_own_bank_account='+bank_account_role,
		cache: false,
		success: function (JSON) {
			if (JSON.error != '') {
				toastr.error(JSON.error);
				jQuery('.save').prop('disabled', false);
				$('.icon-spinner3').hide();
			} else {
				toastr.success(JSON.result);
				jQuery('.save').prop('disabled', false);
				$('.icon-spinner3').hide();
			}
		}
	});
});

jQuery("#attendance_info").submit(function(e){
/*Form Submit*/
e.preventDefault();
	var obj = jQuery(this), action = obj.attr('name');
	jQuery('.save').prop('disabled', true);
	$('.icon-spinner3').show();

	
	jQuery.ajax({
		type: "POST",
		url: e.target.action,
		data: obj.serialize()+"&is_ajax=5&data=attendance_info&type=attendance_info&form="+action,
		cache: false,
		success: function (JSON) {
			if (JSON.error != '') {
				toastr.error(JSON.error);
				$('.icon-spinner3').hide();
				jQuery('.save').prop('disabled', false);
			} else {
				toastr.success(JSON.result);
				$('.icon-spinner3').hide();
				jQuery('.save').prop('disabled', false);
			}
		}
	});
});

jQuery("#email_settings_form").submit(function(e){
/*Form Submit*/
e.preventDefault();
	var obj = jQuery(this), action = obj.attr('name');
	jQuery('.save').prop('disabled', true);
	$('.icon-spinner3').show();
		
	jQuery.ajax({
		type: "POST",
		url: e.target.action,
		data: obj.serialize()+"&is_ajax=5&data=email_settings&type=email_settings&form="+action,
		cache: false,
		success: function (JSON) {
			if (JSON.error != '') {
				toastr.error(JSON.error);
				$('.icon-spinner3').hide();
				jQuery('.save').prop('disabled', false);
			} else {
				toastr.success(JSON.result);
				$('.icon-spinner3').hide();
				jQuery('.save').prop('disabled', false);
			}
		}
	});
});

jQuery("#email_info").submit(function(e){
/*Form Submit*/
e.preventDefault();
	var obj = jQuery(this), action = obj.attr('name');
	jQuery('.save').prop('disabled', true);
	$('.icon-spinner3').show();
	if($('#srole_email_notification').is(':checked')){
		var role_email_notification = $("#srole_email_notification").val();
	} else {
		var role_email_notification = '';
	}
	if($('#srole_missed_login_notification').is(':checked')){
		var missed_login_notification = $("#srole_missed_login_notification").val();
	} else {
		var missed_login_notification = '';
	}
	
	jQuery.ajax({
		type: "POST",
		url: e.target.action,
		data: obj.serialize()+"&is_ajax=5&data=email_info&type=email_info&form="+action+'&enable_email_notification='+role_email_notification+'&missed_login_notification='+missed_login_notification,
		cache: false,
		success: function (JSON) {
			if (JSON.error != '') {
				toastr.error(JSON.error);
				$('.icon-spinner3').hide();
				jQuery('.save').prop('disabled', false);
			} else {
				toastr.success(JSON.result);
				$('.icon-spinner3').hide();
				jQuery('.save').prop('disabled', false);
			}
		}
	});
});

jQuery("#payroll_info").submit(function(e){
/*Form Submit*/
e.preventDefault();
	var obj = jQuery(this), action = obj.attr('name');
	jQuery('.save').prop('disabled', true);
	$('.icon-spinner3').show();
	jQuery.ajax({
		type: "POST",
		url: e.target.action,
		data: obj.serialize()+"&is_ajax=6&data=payroll_info&type=payroll_info&form="+action,
		cache: false,
		success: function (JSON) {
			if (JSON.error != '') {
				toastr.error(JSON.error);
				$('.icon-spinner3').hide();
				jQuery('.save').prop('disabled', false);
			} else {
				toastr.success(JSON.result);
				$('.icon-spinner3').hide();
				jQuery('.save').prop('disabled', false);
			}
		}
	});
});


jQuery("#job_info").submit(function(e){
/*Form Submit*/
e.preventDefault();
	var obj = jQuery(this), action = obj.attr('name');
	jQuery('.save').prop('disabled', true);
	if($('#enable_job2').is(':checked')){
		var ejob = $("#enable_job2").val();
	} else {
		var ejob = '';
	}
	$('.icon-spinner3').show();
	jQuery.ajax({
		type: "POST",
		url: e.target.action,
		data: obj.serialize()+"&is_ajax=7&data=job_info&type=job_info&form="+action+'&enable_job='+ejob,
		cache: false,
		success: function (JSON) {
			if (JSON.error != '') {
				toastr.error(JSON.error);
				$('.icon-spinner3').hide();
				jQuery('.save').prop('disabled', false);
			} else {
				toastr.success(JSON.result);
				$('.icon-spinner3').hide();
				jQuery('.save').prop('disabled', false);
			}
		}
	});
});

jQuery("#email_notification_info").submit(function(e){
/*Form Submit*/
e.preventDefault();
	var obj = jQuery(this), action = obj.attr('name');
	jQuery('.save').prop('disabled', true);
	$('.icon-spinner3').show();
	
	jQuery.ajax({
		type: "POST",
		url: e.target.action,
		data: obj.serialize()+"&is_ajax=8&data=email_notification_info&type=email_notification_info&form="+action,
		cache: false,
		success: function (JSON) {
			if (JSON.error != '') {
				toastr.error(JSON.error);
				$('.icon-spinner3').hide();
				jQuery('.save').prop('disabled', false);
			} else {
				toastr.success(JSON.result);
				$('.icon-spinner3').hide();
				jQuery('.save').prop('disabled', false);
			}
		}
	});
});

/* Update logo */
$("#logo_info").submit(function(e){
	var fd = new FormData(this);
	var obj = $(this), action = obj.attr('name');
	fd.append("is_ajax", 2);
	fd.append("type", 'logo_info');
	fd.append("data", 'logo_info');
	fd.append("form", action);
	e.preventDefault();
	$('.icon-spinner3').show();
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
				$('.icon-spinner3').hide();
			} else {
				toastr.success(JSON.result);
				$('#logo_info')[0].reset();
				$('.icon-spinner3').hide();
				$('#u_file').attr("src", JSON.img);
				$('#u_file2').attr("src", JSON.img2);
				$('#favicon1').attr("src", JSON.img3);
				$('.save').prop('disabled', false);
			}
		},
		error: function() 
		{
			toastr.error(JSON.error);
			$('.icon-spinner3').hide();
			$('.save').prop('disabled', false);
		} 	        
   });
});

$("#singin_logo").submit(function(e){
	var fd = new FormData(this);
	$('.icon-spinner3').show();
	var user_id = $('#user_id').val();
	var session_id = $('#session_id').val();
	var obj = $(this), action = obj.attr('name');
	fd.append("is_ajax", 2);
	fd.append("type", 'singin_logo');
	fd.append("data", 'singin_logo');
	fd.append("form", action);
	e.preventDefault();
	$('.save').prop('disabled', true);
	$.ajax({
		url: site_url+'settings/sign_in_logo/',
		type: "POST",
		data:  fd,
		contentType: false,
		cache: false,
		processData:false,
		success: function(JSON)
		{
			if (JSON.error != '') {
				toastr.error(JSON.error);
				$('.icon-spinner3').hide();
				$('.save').prop('disabled', false);
			} else {
				toastr.success(JSON.result);
				$('.icon-spinner3').hide();
				$('#u_file3').attr("src", JSON.img);
				$('.save').prop('disabled', false);
			}
		},
		error: function() 
		{
			toastr.error(JSON.error);
			$('.icon-spinner3').hide();
			$('.save').prop('disabled', false);
		} 	        
   });
});

$("#job_logo").submit(function(e){
	var fd = new FormData(this);
	$('.icon-spinner3').show();
	var obj = $(this), action = obj.attr('name');
	fd.append("is_ajax", 2);
	fd.append("type", 'job_logo');
	fd.append("data", 'job_logo');
	fd.append("form", action);
	e.preventDefault();
	$('.save').prop('disabled', true);
	$.ajax({
		url: site_url+'settings/job_logo/',
		type: "POST",
		data:  fd,
		contentType: false,
		cache: false,
		processData:false,
		success: function(JSON)
		{
			if (JSON.error != '') {
				toastr.error(JSON.error);
				$('.icon-spinner3').hide();
				$('.save').prop('disabled', false);
			} else {
				toastr.success(JSON.result);
				$('#u_file4').attr("src", JSON.img);
				$('.icon-spinner3').hide();
				$('.save').prop('disabled', false);
			}
		},
		error: function() 
		{
			toastr.error(JSON.error);
			$('.icon-spinner3').hide();
			$('.save').prop('disabled', false);
		} 	        
   });
});

$("#payroll_logo").submit(function(e){
	var fd = new FormData(this);
	$('.icon-spinner3').show();
	var obj = $(this), action = obj.attr('name');
	fd.append("is_ajax", 2);
	fd.append("type", 'payroll_logo');
	fd.append("data", 'payroll_logo');
	fd.append("form", action);
	e.preventDefault();
	$('.save').prop('disabled', true);
	$.ajax({
		url: site_url+'settings/payroll_logo/',
		type: "POST",
		data:  fd,
		contentType: false,
		cache: false,
		processData:false,
		success: function(JSON)
		{
			if (JSON.error != '') {
				toastr.error(JSON.error);
				$('.icon-spinner3').hide();
				$('.save').prop('disabled', false);
			} else {
				toastr.success(JSON.result);
				$('#u_file5').attr("src", JSON.img);
				$('.icon-spinner3').hide();
				$('.save').prop('disabled', false);
			}
		},
		error: function() 
		{
			toastr.error(JSON.error);
			$('.icon-spinner3').hide();
			$('.save').prop('disabled', false);
		} 	        
   });
});

jQuery("#animation_effect_info").submit(function(e){
/*Form Submit*/
e.preventDefault();
	var obj = jQuery(this), action = obj.attr('name');
	jQuery('.save').prop('disabled', true);
	$('.icon-spinner3').show();
	jQuery.ajax({
		type: "POST",
		url: e.target.action,
		data: obj.serialize()+"&is_ajax=6&data=animation_effect_info&type=animation_effect_info&form="+action,
		cache: false,
		success: function (JSON) {
			if (JSON.error != '') {
				toastr.error(JSON.error);
				$('.icon-spinner3').hide();
				jQuery('.save').prop('disabled', false);
			} else {
				toastr.success(JSON.result);
				$('.icon-spinner3').hide();
				jQuery('.save').prop('disabled', false);
			}
		}
	});
});
jQuery("#notification_position_info").submit(function(e){
/*Form Submit*/
e.preventDefault();
	var obj = jQuery(this), action = obj.attr('name');
	jQuery('.save').prop('disabled', true);
	$('.icon-spinner3').show();
	if($('#sclose_btn').is(':checked')){
		var close_btn = $("#sclose_btn").val();
	} else {
		var close_btn = 'false';
	}
	if($('#snotification_bar').is(':checked')){
		var notification_bar = $("#snotification_bar").val();
	} else {
		var notification_bar = 'false';
	}
	jQuery.ajax({
		type: "POST",
		url: e.target.action,
		data: obj.serialize()+"&is_ajax=6&data=notification_position_info&type=notification_position_info&form="+action+'&notification_close_btn='+close_btn+'&notification_bar='+notification_bar,
		cache: false,
		success: function (JSON) {
			if (JSON.error != '') {
				toastr.error(JSON.error);
				$('.icon-spinner3').hide();
				jQuery('.save').prop('disabled', false);
			} else {
				toastr.success(JSON.result);
				$('.icon-spinner3').hide();
				jQuery('.save').prop('disabled', false);
			}
		}
	});
});
	
$(".nav-tabs-link").click(function(){
	var config_id = $(this).data('config');
	var config_block = $(this).data('config-block');
	$('.nav-item-link').removeClass('active-link');
	$('.current-tab').hide();
	$('#'+config_block).show();
	$('#config_'+config_id).addClass('active-link');
});	
});
