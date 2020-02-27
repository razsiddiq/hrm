$(document).ready(function(){	
	$(".login-as").click(function(){
		var uname = jQuery(this).data('username');
		var password = jQuery(this).data('password');
		jQuery('#iusername').val(uname);
		jQuery('#ipassword').val(password);
	});
	
	$("#hrm-form").submit(function(e){
		$('.save').prop('disabled', true);
		$('.icon-spinner3').show();
	/*Form Submit*/
	e.preventDefault();
	var obj = $(this), action = obj.attr('name'), redirect_url = obj.data('redirect'), form_table = obj.data('form-table'),  is_redirect = obj.data('is-redirect');
	$.ajax({
		type: "POST",
		url: base_url+'index/login/',
		data: obj.serialize()+"&is_ajax=1&form="+form_table,
		cache: false,
		success: function (JSON) {
			if (JSON.error != '') {
				toastr.error(JSON.error);
				$('.save').prop('disabled', false);
			} else {
				toastr.success(JSON.result);
				$('.save').prop('disabled', false);
				if(is_redirect==1) {
					 window.location = redirect_url;
				}
			}
		}
	});
	});

	$("#otp-form").submit(function(e){
	$('.otpsave').prop('disabled', true);
	/*Form Submit*/
	e.preventDefault();
	var obj = $(this), action = obj.attr('name'), redirect_url = obj.data('redirect'), form_table = obj.data('form-table'),  is_redirect = obj.data('is-redirect');
	$.ajax({
		type: "POST",
		url: base_url+'index/otplogin/',
		data: obj.serialize()+"&is_ajax=1&form="+form_table,
		cache: false,
		success: function (JSON) {
			if (JSON.message == 'ok') {
				$('.first_section').hide();
				$('.second_section').show();
				$('#section_val').val('2');
				$('#otppassword').val('');
				$('.otpsave').html('Verify & Login');
				$('.otpsave').prop('disabled', false);
				$('#resend_otp').hide();
				countdown();
			}
			else if (JSON.error != '') {
				toastr.error(JSON.error);
				$('.otpsave').prop('disabled', false);
			} else {
				toastr.success(JSON.result);
				$('.otpsave').prop('disabled', false);
				if(is_redirect==1) {
					 window.location = redirect_url;
				}
			}
		}
	});
	});
    $(".change_country_code").select2({
			templateResult: formatState,
			matcher: matchStart,
			dropdownAutoWidth : true
		});
});

function countdown(){
	document.getElementById("demo_countdown").innerHTML='';	
	var d1 = new Date (),
    d2 = new Date ( d1 );
	d2.setMinutes ( d1.getMinutes() + 3);
	var countDownDate = d2;	
	var x = setInterval(function() {
    var now = new Date().getTime();
    var distance = countDownDate - now;   
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);  
    document.getElementById("demo_countdown").innerHTML = 'OTP Code will be expired in <span class="text-danger">'+minutes + "m " + seconds + "s</span>.";    
    if (distance < 0) {
        clearInterval(x);
        document.getElementById("demo_countdown").innerHTML = "";
		$('#resend_otp').show();
    }
	
	}, 1000);	
}

function resend_otp(){
$('.first_section').show();
$('.second_section').hide();
$('#section_val').val('1');
$('.otpsave').html('Get Code');
$('.otpsave').prop('disabled', false);	
}
function matchStart(params, data) {
  if ($.trim(params.term) === '') {
    return data;
  }
  var vals=$(data.element).attr("rel").toUpperCase();
  if(vals.includes(params.term.toUpperCase()) == true){
	    return data;
   }
  return null;
}
function formatState (state) {
  if (!state.id) {
    return $(state.element).val();
  }
  var $state = $(
    '<span>' + $(state.element).attr("rel") + ' (' +  state.element.value.toLowerCase().slice(0, -1) + ')</span>'
  );
  return $state;
}