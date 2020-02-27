<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="google-site-verification" content="NfBKpLxxNOYXd0d13vDGeFu0dqh9iy1zS6zozn6WiP0" />
	<?php $company = $this->Xin_model->read_company_setting_info(1);?>
	<?php $favicon = base_url().'uploads/logo/favicon/'.$company[0]->favicon;?>
    <!-- Meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?php echo $favicon;?>" >
    <!-- Title -->
    <title><?php echo $title;?></title>
    <!-- Vendor CSS -->
    <link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/toastr/toastr.min.css">
    <!-- CSS -->
	<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/animate.css/animate.min.css">
	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/css/icons/icomoon/styles.css?version=<?php echo strtotime(date('Y-m-d H:i:s')); ?>" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/css/core.css?version=<?php echo strtotime(date('Y-m-d H:i:s')); ?>" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/css/components.css?version=<?php echo strtotime(date('Y-m-d H:i:s'));?>" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/css/colors.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->
	<!-- Core JS files -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/loaders/pace.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/libraries/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/loaders/blockui.min.js"></script>
	<!-- /core JS files -->
	<!-- Theme JS files -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/visualization/d3/d3.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/visualization/d3/d3_tooltip.js"></script>


	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/styling/uniform.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/notifications/pnotify.min.js"></script>

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/app.js"></script>

    <link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/clockpicker/dist/bootstrap-clockpicker.min.css">
	<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/kendo/kendo.common.min.css" />
    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
