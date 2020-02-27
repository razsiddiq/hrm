<?php $system = $this->Xin_model->read_setting_info(1);?>
<?php $company = $this->Xin_model->read_company_setting_info(1);?>
<?php $logo = base_url().'uploads/logo/'.$company[0]->logo;?>
<!DOCTYPE html>
<html lang="en">
<head>
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
	<!-- Main navbar -->
	<div class="navbar navbar-inverse">
		<div class="navbar-header">
			<a class="navbar-brand" href="<?php echo site_url();?>"><img src="<?php echo $logo?>" alt="Alifca DMCC"/></a>			
		</div>
	</div>
	<!-- /main navbar -->
	<!-- Page container -->
	<div class="page-container">
		<!-- Page content -->
		<div class="page-content">
			<!-- Main content -->
			<div class="content-wrapper">
				<!-- Content area -->
				<div class="content"> 
					<!-- Simple login form -->
					<?php if($slugs=='setup_password'){						
						$email_id=urldecode($this->input->get('email'));
						$type=$this->input->get('type');
						$uniqueid=$this->input->get('uniqueid');
						$randomid=$this->input->get('randomid');
						$check_password_note=check_password_note($email_id,$type,$uniqueid,$randomid);
						if($check_password_note==''){
					?>				
						<form class="form-material" action="<?php echo site_url();?>employees/change_password/" method="post" name="e_change_password" id="e_change_password">	
							<div class="panel panel-body login-form">
								<div class="text-center">
									<div class="icon-object border-warning text-warning"><i class="icon-key"></i></div>
									<h5 class="content-group">Setup New Password </h5>
								</div>
								<div class="form-group has-feedback">								 
								<input class="form-control" placeholder="New Password" name="new_password" type="password">								
								</div>
								<div class="form-group has-feedback">								 
									<input class="form-control" placeholder="Confirm New Password" name="new_password_confirm" type="password">								
								</div>
								<button type="submit" class="save btn bg-teal-400 btn-block">Save</button>
							</div>
						</form>
					<?php 
						}  
						else{ ?>
								<div class="panel-body" style="margin: 0 auto;max-width: 33em;margin-top:10em;"><?php echo $check_password_note;?>
									<a href="<?php echo site_url();?>" class="btn bg-teal-400 btn-block">Go To Login Page</a>
								</div>
						<?php
							}} else{ ?>
								<form class="form-material" action="<?php echo site_url();?>forgot_password/send_mail/" method="post" name="xin-form" id="xin-form">
									<div class="panel panel-body login-form">
										<div class="text-center">
											<div class="icon-object border-warning text-warning"><i class="icon-spinner11"></i></div>
											<h5 class="content-group">Password recovery <small class="display-block">We'll send you instructions in email</small></h5>
										</div>
										<div class="form-group has-feedback">
											<input type="text" class="form-control" name="iemail" id="iemail" placeholder="Enter your email...">
											<div class="form-control-feedback">
												<i class="icon-mail5 text-muted"></i>
											</div>
										</div>
										<button type="submit" class="save btn bg-teal-400 btn-block">Reset password <i class="icon-arrow-right14 position-right"></i></button>
									</div>
								</form>
					<?php } ?>				
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
	<!-- /page container -->
	<!-- Vendor JS --> 
	<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/jquery/jquery-3.2.1.min.js"></script> 
	<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/tether/js/tether.min.js"></script> 
	<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/bootstrap/js/bootstrap.min.js"></script> 
	<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/toastr/toastr.min.js"></script> 
	<script type="text/javascript">
	$(document).ready(function(){
		toastr.options.closeButton = <?php echo $system[0]->notification_close_btn;?>;
		toastr.options.progressBar = <?php echo $system[0]->notification_bar;?>;
		toastr.options.timeOut = 3000;
		toastr.options.preventDuplicates = true;
		toastr.options.positionClass = "<?php echo $system[0]->notification_position;?>";
		
			/* Add data */ /*Form Submit*/
		$("#xin-form").submit(function(e){
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=1&add_type=forgot_password&form="+action,
				cache: false,
				success: function (JSON) {
					if (JSON.error != '') {
						toastr.error(JSON.error);
						$('.save').prop('disabled', false);
					} else {
						toastr.success(JSON.result);
						$('#iemail').val(''); // To reset form fields
						$('.save').prop('disabled', false);
					}
				}
			});
		});
		
		jQuery("#e_change_password").submit(function(e){
		/*Form Submit*/
		e.preventDefault();
			var obj = jQuery(this), action = obj.attr('name');
			jQuery('.save').prop('disabled', true);
			jQuery.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=31&data=e_change_password&type=change_password&form="+action+"&email=<?php echo $email_id;?>",
				cache: false,
				success: function (JSON) {
					if (JSON.error != '') {
						toastr.error(JSON.error);
						jQuery('.save').prop('disabled', false);	
					} else {
						jQuery('.save').prop('disabled', false);
						alert('Well done! Password Updated. You can login our HR Portal now.');
						window.location.href = "<?php echo base_url();?>";
					}
				}
			});
		});
	});
	</script>
</body>
</html>
