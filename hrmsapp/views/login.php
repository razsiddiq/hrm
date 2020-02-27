<?php $system = $this->Xin_model->read_setting_info(1);?>
<?php $company = $this->Xin_model->read_company_setting_info(1);?>
<?php $logo = base_url().'uploads/logo/'.$company[0]->logo;?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="google-site-verification" content="NfBKpLxxNOYXd0d13vDGeFu0dqh9iy1zS6zozn6WiP0" />
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">	
	<title><?php echo $title; ?></title>
    <meta name="author" content="Alifca DMCC - ultimate human resource management system">
    <link rel="icon" href="<?=base_url()?>skin/img/wz-icon.png" type="image/png">
	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/css/core.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/css/components.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/css/colors.css" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->
	<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/toastr/toastr.min.css">
	<!-- Core JS files -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/loaders/pace.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/libraries/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/libraries/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/loaders/blockui.min.js"></script>
	<!-- /core JS files -->
</head>
<body class="login-container">
	<!-- Page container -->
	<div class="page-container">
		<!-- Page content -->
		<div class="page-content">
			<!-- Main content -->
			<div class="content-wrapper">
				<!-- Content area -->
				<div class="content">
 					<?php if($uri==''){$redirect='dashboard?module=dashboard';}else{$redirect=$uri;}?>
					<!-- Simple login form -->
					<form class="mb-1" method="post" name="hrm-form" id="hrm-form" data-redirect="<?php echo $redirect;?>" data-form-table="login" data-is-redirect="1">
					                    <input type="hidden" name="<?= csrf_name;?>" value="<?= csrf_hash;?>" />
						<div class="panel panel-body login-form">
							<div class="text-center">
								<div class="icon-object" style="border-color: white;"><img src="<?php echo base_url();?>uploads/logo/signin/<?php echo $company[0]->sign_in_logo;?>" title=""></div>
								<h1 style="margin-top: -0.5em;">HRMS</h1>
								<h5 class="content-group"><small class="display-block">Enter your credentials below</small></h5>
							</div>

							<div class="form-group has-feedback has-feedback-left">
								<input type="text" class="form-control" name="iusername" id="iusername" placeholder="Employee ID Or Email" autofocus>
								<div class="form-control-feedback">
									<i class="icon-user text-muted"></i>
								</div>
							</div>

							<div class="form-group has-feedback has-feedback-left">
								<input type="password" class="form-control" name="ipassword" id="ipassword" placeholder="Password">
								<div class="form-control-feedback">
									<i class="icon-lock2 text-muted"></i>
								</div>
							</div>

							<div class="form-group">
								<button type="submit" class="btn bg-teal-400 btn-block">Sign in <i class="icon-circle-right2 position-right"></i></button>
							</div>
							<div class="text-center">
								<a href="#" data-toggle="modal" data-target="#modal-recover" data-backdrop="static" data-keyboard="false">Log In Using Your Mobile</a>
							</div>
							<div class="text-center">
								<a href="<?php echo site_url('forgot_password');?>">Forgot password?</a>
							</div>
						</div>
					</form>
					<!-- /simple login form -->
					<!-- Footer -->
					<div class="footer text-muted text-center">
						<?php if($system[0]->enable_current_year=='yes'):?><?php echo date('Y');?> <?php endif;?> © <?php echo $system[0]->footer_text;?>
						<?php if($system[0]->enable_page_rendered=='yes'):?>
						<br>Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?>
						<?php endif; ?>
					</div>
					<!-- /footer -->
				</div>
				<!-- /content area -->
			</div>
			<!-- /main content -->
		</div>
		<!-- /page content -->
	</div>
	<!-- Password recovery form -->
	<div id="modal-recover" class="modal fade">
		<div class="modal-dialog modal-sm">
			<div class="modal-content login-form">		
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
				<!-- Form -->								
				<form class="modal-body" method="post" name="otp-form" id="otp-form" data-redirect="<?php echo $redirect;?>" data-form-table="otplogin" data-is-redirect="1">
						<input type="hidden" name="<?= csrf_name;?>" value="<?= csrf_hash;?>" />
						<input type="hidden" name="section_val" id="section_val" value="1" />
						<div class="text-center">
						
							<div class="icon-object border-teal text-teal"><i class="icon-mobile"></i></div>
							<h5 class="content-group first_section">Log In Using Your Mobile <small class="display-block">Please enter your mobile number to receive a code</small></h5>
							<h5 class="content-group second_section" style="display:none;"><small class="display-block">Please enter 6-digit confirmation code you received</small><small id="demo_countdown"></small></h5>
						</div>
						<div class="form-group has-feedback first_section">	
							<div class="input-group">
								<span class="input-group-addon-custom">
									<select class="form-control change_country_code js-example-templating" name="country_code" required>
										<?php foreach(phone_numbers_code() as $keys=>$phone_code){
											if($keys=='+971'){
											?>
										<option value="<?php echo $keys; ?>-" data-len ="<?php echo $phone_code['length'];?>"   rel="<?php echo $phone_code['country_name'];?>"><?php echo $keys; ?></option>
										<?php } }?>
									</select>  
									</span>
								<input class="form-control" placeholder="553339299" title="<?php echo $this->lang->line('xin_use_numbers');?>" name="phone" type="text" maxlength="9" autocomplete="OFF">
							</div>
						</div>									
					<div class="form-group has-feedback second_section" style="display:none;">
						<input type="text" class="form-control" name="otppassword" id="otppassword" placeholder="OTP" maxlength="6" autocomplete="OFF">						
					</div>									
					<div class="second_section"  id="resend_otp" style="display:none;margin-top: -15px;margin-bottom: 5px;">
						<div class="row">
							<div class="col-sm-12 text-right">
								<a href="#" onclick="resend_otp();">Check & Resend OTP</a>
							</div>
						</div>
					</div>									
					<button type="submit" class="btn bg-teal btn-block otpsave">Get Code</button>									
				</form>
				<!-- /form -->
			</div>
		</div>
	</div>
	<!-- /password recovery form -->
	<!-- /page container -->
<!-- Vendor JS -->
<Style>
button.close{right: 0.8em;position: absolute;top: 0.5em;z-index: 9}
</style>
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/jquery/jquery-3.2.1.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/tether/js/tether.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/bootstrap/js/bootstrap.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/selects/select2.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/toastr/toastr.min.js"></script> 
<script type="text/javascript">
$(document).ready(function(){
	toastr.options.closeButton = <?php echo $system[0]->notification_close_btn;?>;
	toastr.options.progressBar = <?php echo $system[0]->notification_bar;?>;
	toastr.options.timeOut = 3000;
	toastr.options.preventDuplicates = true;
	toastr.options.positionClass = "<?php echo $system[0]->notification_position;?>";
});
</script>
<script type="text/javascript">var base_url = '<?php echo base_url(); ?>';</script>
<script type="text/javascript" src="<?php echo base_url();?>skin/js_module/xin_login.js?version=<?php echo strtotime(date('Y-m-d H:i:s')); ?>"></script>
</body>
</html>