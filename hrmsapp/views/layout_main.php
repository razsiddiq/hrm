<?php
$session = $this->session->userdata('username');
$system = $this->Xin_model->read_setting_info(1);
$layout = $this->Xin_model->system_layout();
$this->load->view('components/htmlheader');
?>
<body>
	<?php $this->load->view('components/header');?>
		<div class="page-container">
			<div class="page-content">
				<?php $this->load->view('components/left_menu');?>
				<div class="content-wrapper">
					<div class="page-header page-header-default">
							<div class="page-header-content">
								<div class="page-title">
									<h4><i class="icon-arrow-left52 icon-clr position-left"></i> <span class="text-semibold"></span><?php echo $breadcrumbs;?></h4>
								</div>
							</div>
							<div class="breadcrumb-line">
								<ul class="breadcrumb">
									<li><a href="<?php echo site_url()?>dashboard"><i class="icon-home2 icon-clr position-left"></i> Home</a></li>
									<li class="active"><?php echo $breadcrumbs;?></li>
								</ul>
							</div>
					</div>
					<div class="content">
					<?php echo $subview;?>
					<?php $this->load->view('components/footer');?>
					</div>
				</div>
			</div>
		</div>
	<?php  $this->load->view('components/htmlfooter');?>
</body>
</html>
