<?php
/* Policy view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="row m-b-1">
	<div class="col-md-12">

		<?php if($archive == 1){ ?>
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title">

					<strong> Leave Policy & Guidelines: ALIFCA, Gulf Alabel and Max Top Resources</strong>

				</h5>

			</div>
			<div class="content">
				<div class="row">
					<div class="col-lg-3 col-sm-6">
						<div class="thumbnail">
							<div class="thumb">
								<img src="<?php echo base_url();?>assets/images/leave-policy-ver-1.2.jpg" alt="">
								<div class="caption-overflow">
										<span>
											<a target="_blank" href="<?php echo base_url();?>assets/images/policies/awok-leave-policy-ver-1.2.pdf" class="btn border-white text-white btn-flat btn-icon btn-rounded ml-5"><i class="icon-link2"></i></a>
										</span>
								</div>
							</div>

							<div class="caption">
								<h6 class="no-margin">25 April, 2019 (Ver 1.2)</h6>
							</div>
						</div>
					</div>

					<div class="col-lg-3 col-sm-6">
						<div class="thumbnail">
							<div class="thumb">
								<img src="<?php echo base_url();?>assets/images/leave-policy-amendment-thumbnail.jpg" alt="">
								<div class="caption-overflow">
										<span>
											<a target="_blank" href="<?php echo base_url();?>assets/images/policies/awok-leave-policy-amendment-ver-1.0.pdf" class="btn border-white text-white btn-flat btn-icon btn-rounded ml-5"><i class="icon-link2"></i></a>
										</span>
								</div>
							</div>

							<div class="caption">
								<h6 class="no-margin">01 April, 2019 (Ver 1.1)</h6>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-sm-6">
						<div class="thumbnail">
							<div class="thumb">
								<img src="<?php echo base_url();?>assets/images/leave-policy-thumbnail.jpg" alt="">
								<div class="caption-overflow">
										<span>
											<a target="_blank" href="<?php echo base_url();?>assets/images/policies/awok-leave-policy-ver-1.0.pdf" class="btn border-white text-white btn-flat btn-icon btn-rounded ml-5"><i class="icon-link2"></i></a>
										</span>
								</div>
							</div>

							<div class="caption">
								<h6 class="no-margin">01 April, 2018 (Ver 1.0)</h6>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php }else{?>
		
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title">

					<strong> Leave Policy & Guidelines: ALIFCA, Gulf Alabel and Max Top Resources</strong>

				</h5>

			</div>
			<div class="content">
				<div class="row">
					<div class="col-lg-3 col-sm-6">
						<div class="thumbnail">
							<div class="thumb">
								<img src="<?php echo base_url();?>assets/images/leave-policy-ver-1.3.jpg" alt="">
								<div class="caption-overflow">
										<span>
											<a target="_blank" href="<?php echo base_url();?>assets/images/policies/Leave Policy 2020 V1.3.pdf" class="btn border-white text-white btn-flat btn-icon btn-rounded ml-5"><i class="icon-link2"></i></a>
										</span>
								</div>
							</div>

							<div class="caption">
								<h6 class="no-margin">1 January, 2020 (Ver 1.3)</h6>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title">

					<strong> Employee & Manager Responsibilities - HRMS</strong>

				</h5>

			</div>
			<div class="content">
				<div class="row">
					<div class="col-lg-3 col-sm-6">
						<div class="thumbnail">
							<div class="thumb">
								<img src="<?php echo base_url();?>assets/images/roles-responsibilities.jpg" alt="">
								<div class="caption-overflow">
										<span>
											<a target="_blank" href="<?php echo base_url();?>assets/images/policies/employee-and-manager-responsibilities.pdf" class="btn border-white text-white btn-flat btn-icon btn-rounded ml-5"><i class="icon-link2"></i></a>
										</span>
								</div>
							</div>

							<div class="caption">
								<h6 class="no-margin">31 December, 2019</h6>
							</div>
						</div>
					</div>

			
				</div>
			</div>
		</div>

		<div class="panel panel-flat">
			
			<div class="content">
				<div class="row">
					<div class="col-lg-3 col-sm-6">
						<div class="thumbnail">
							<div class="thumb">
								<div class="caption-overflow">
									<span>
										<a target="_blank" href="<?php echo base_url();?>assets/images/policies/awok-leave-policy-ver-1.2.pdf" class="btn border-white text-white btn-flat btn-icon btn-rounded ml-5"><i class="icon-folder-open mr-3 icon-2x"></i></a>
									</span>
								</div>
							</div>

							<div class="caption">
								<a href="<?php echo base_url();?>policy/policy_archive"><h3 class="no-margin" style="color: #d22a39;"><i class="icon-folder-open mr-3 icon-2x" style="margin-right : 20px;"></i> Archive Documents</h3></a>
							</div>
							
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<?php }?>
	</div>
</div>
