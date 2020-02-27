<?php
/* Employee Directory view
*/
?>

<?php
$locations = [];
$countries = [];
$defaultLocations = $this->Xin_model->all_locations();
foreach ($this->Xin_model->getLocationsWithCountry() as $element) {
	$locations[$element->country][] = $element;
	$countries[$element->country] = (object) [
		'country_id'=>$element->country,
		'country_name'=>$element->country_name
	];
}
?>

<div class="panel panel-flat">
  	<div class="panel-heading">
    	<h5 class="panel-title">
    		<strong>Filter By</strong>
    	</h5>
  	</div>
  	<form method="get" action="<?php echo base_url()?>employees/directory" id="xin-form-directory">
  		<div class="panel-body">
			<div class="row">
		      <div class="col-md-12">
		        
		        <div class="col-md-2">
		          <div class="form-group">
		            <select class="country_value" class="form-control" data-plugin="select_hrm">
		              <option value="0">All Regions</option>
		              <?php 
		              foreach($countries as $item) {
		              if($item->country_id == $this->input->get('country_value')){
		              	$selected = 'selected';
		              }else{
		              	$selected = '';
		              }
		              ?>
		              <option value="<?php echo $item->country_id;?>" <?=$selected?>><?php echo $item->country_name;?></option>
		              <?php } ?>
		            </select>
		          </div>
		        </div>

				<div class="col-md-2">
		          	<div class="form-group">
			            <select class="location_value" class="form-control" data-plugin="select_hrm">
			              <option value="0" <?php if($this->input->get('location_value') == 0){ echo 'selected'; }?> >All Locations</option>
			              <?php 

		              	foreach($defaultLocations as $locs) {
		              		if($locs->location_id == $location_value){
				              	$selected = 'selected';
			              	}else{
				              	$selected = '';
			              	}
			              ?>
			              <option value="<?php echo $locs->location_id;?>" <?=$selected?>><?php echo $locs->location_name;?></option>
			              <?php } ?>
			            </select>
		          	</div>
		        </div>

				<div class="col-md-2">
		          	<div class="form-group">
			            <select class="department_value" class="form-control" data-plugin="select_hrm">
			              <option value="0">All Departments</option>
			              <?php 
			              	foreach($this->Xin_model->all_departments_chart() as $dept) {
			              	if($dept->department_id == $this->input->get('department_value')){
				              	$selected = 'selected';
			              	}else{
				              	$selected = '';
			              	}
			              	?>
			              <option value="<?php echo $dept->department_id;?>" <?=$selected?>><?php echo $dept->department_name;?></option>
			              <?php } ?>
			            </select>
		          	</div>
		        </div>

				<!-- <div class="col-md-1">
		          	<div class="form-group">
			            <select class="role_value" class="form-control" data-plugin="select_hrm">
			              <option value="0">All Roles</option>
			              <?php 
			              foreach($this->Xin_model->all_employee_roles() as $emprole) {
			              	if($emprole->user_role_id == $this->input->get('role_value')){
				              	$selected = 'selected';
			              	}else{
				              	$selected = '';
			              	}
			              	?>
			              <option value="<?php echo $emprole->user_role_id;?>" <?=$selected?>><?php echo $emprole->role_name;?></option>
			              <?php } ?>
			            </select>
		          	</div>
		        </div> -->

		        <div class="col-md-2">
		          	<div class="form-group">
			            <select class="status" class="form-control" data-plugin="select_hrm">
		              		<option value="all" <?php if($this->input->get('status') == 'all') { echo 'selected'; }?> >All</option>
			              	<option value="1" <?php if($this->input->get('status') == '1') { echo 'selected'; }?>>Active</option>
			              	<option value="0" <?php if($this->input->get('status') == '0') { echo 'selected'; }?> >Inactive</option>
			            </select>
		          	</div>
		        </div>

		        <div class="col-md-2">
		          	<div class="form-group">
			            <select class="employee_value" class="form-control" data-plugin="select_hrm">
			              <option value="0">All Employees</option>
			              <?php 
			              foreach($this->Xin_model->all_employees() as $employ) {

			              	$emp_name = change_fletter_caps($employ->first_name.' '.$employ->middle_name.' '.$employ->last_name);
			              	if($employ->user_id == $this->input->get('employee_value')){
				              	$selected = 'selected';
			              	}else{
				              	$selected = '';
			              	}
			              	?>
			              <option value="<?=$employ->user_id;?>" <?=$selected?>><?=$emp_name?></option>
			              <?php } ?>
			            </select>
		          	</div>
		        </div>

				<div class="col-md-2">
		          	<div class="form-group"> &nbsp;
		            	<!-- <button type="submit" class="btn bg-teal-400">Search</button> -->
		            	<input type="submit" class="btn bg-teal-400" value="Search">
		          	</div>
		        </div>
		      </div>      
		    </div>
	  	</div>
	</form>
</div>

<?php $session = $this->session->userdata('username');?>
<div class="row row-sm">
  <?php 

  	if(!empty($all_employees)){
  	foreach($all_employees as $employee){ 

  		$designation = $this->Designation_model->read_designation_information($employee->designation_id);
  		$department = $this->Timesheet_model->getDepartmentById($employee->department_id);

		if($employee->profile_picture!='' && $employee->profile_picture!='no file') {
			$u_file = base_url().'uploads/profile/'.$employee->profile_picture;
		} 
		else {
			if($employee->gender=='Male') { 
				$u_file = base_url().'uploads/profile/default_male.jpg';
			} else {
				$u_file = base_url().'uploads/profile/default_female.jpg';
			}
		}
		
		if($employee->is_active==1):
			$status = '<span class="label label-success">'.$this->lang->line('xin_employees_active').'</span>';
		else:
			$status = '<span class="label label-danger">'.$this->lang->line('xin_employees_inactive').'</span>';
		endif;
	?>

	<div class="col-lg-6 col-md-6">
		<div class="panel panel-body">
			<div class="media">
				<div class="media-left">
					<a href="<?php echo $u_file;?>" data-popup="lightbox">
						<img src="<?php echo $u_file;?>" style="width: 70px; height: 70px;" class="img-circle" alt="">
					</a>
				</div>

				<div class="media-body">
					<h6 class="media-heading" data-popup="tooltip" style="margin-left: 8px;" data-container="body" data-original-title="<?php echo change_fletter_caps($employee->first_name.' '.$employee->last_name);?>"><?php echo change_fletter_caps($employee->first_name.' '.$employee->middle_name.' '.$employee->last_name);?></h6>

					<div class="col-lg-6 col-md-6">
						<h7 class="media-heading" data-popup="tooltip" title="" data-container="body" data-original-title=""></h7>
						<span class="text-muted" title="">Email : <?php echo $employee->email;?></span><br>
						<span class="text-muted" title="">Phone : <?php echo $employee->contact_no;?></span><br>
						<!-- <span class="text-muted" title="">Skype : <?php echo $employee->skype_id;?></span> -->
					</div>

					<div class="col-lg-6 col-md-6">
						<h7 class="media-heading" data-popup="tooltip" title="" data-container="body" data-original-title=""></h7>
						<span class="text-muted" title="">Position : <?php echo substr(change_fletter_caps(@$designation[0]->designation_name),0,25);?></span><br>
						<span class="text-muted" title="">Department : <?php echo $department[0]['department_name'];?></span><br>
						<span class="text-muted" title=""><?php echo $status;?></span>
					</div>

					<!-- <ul class="icons-list">	                    	
						<li><a href="mailto:<?php echo $employee->email;?>" data-popup="tooltip" title="" data-container="body" data-original-title="Email : <?php echo $employee->email;?>"><i class="icon-mail5"></i></a></li>
						<li><a href="<?php echo $employee->contact_no;?>" data-popup="tooltip" title="" data-container="body" data-original-title="Phone : <?php echo $employee->contact_no;?>"><i class="icon-phone2"></i></a></li>
				  	</ul> -->

					<br/>
					
				</div>
			</div>
		</div>
	</div>

  <!--<div class="col-md-4">
    <div class="box box-block bg-white">
      <div class="row">
        <div class="col-md-4 col-sm-4 text-center"> <img class="img-fluid b-a-radius-circle" src="<?php echo $u_file;?>" alt="" style="width:100px;height:100px;"> </div>
        <div class="col-md-8 col-sm-8">
          <h5>
		  <span data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo $employee->first_name;?>">
		  <a href="<?php echo site_url()?>employees/detail/<?php echo $employee->user_id;?>/"><?php 
		  $full_name=@explode(' ',$employee->first_name);
		  echo @$full_name[0].' '.@$full_name[1].' '.@$full_name[2]; 
		  ?> <?php //echo $employee->last_name;?></a></span>
		  
		  
		  </h5>
          <span class="tag tag-success"><?php echo strtolower(@$designation[0]->designation_name);?></span>
          <p> </p>
          <address>
          <?php echo $employee->address;?><br>
          <abbr title="<?php echo $this->lang->line('xin_phone');?>">P:</abbr> <?php echo $employee->contact_no;?><br>
          <abbr title="<?php echo $this->lang->line('dashboard_xin_status');?>"><i class="fa fa-user"></i></abbr> <span class="s-text"><?php echo $status;?></span>
          </address>
        </div>
      </div>
    </div>
  </div>-->
  <?php }}else{?>
  	<div class="col-lg-11 col-md-6">
		<h4 style="text-align: center;">No result found...</h4>
  	</div>
  <?php }?>
</div>

<script type="text/javascript">
$(document).ready(function() {
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
});
</script>

<script type="text/javascript">
$("#xin-form-directory").submit(function(e){

	var country_value 		= $('.country_value').val();
	var location_value 		= $('.location_value').val();
	var department_value 	= $('.department_value').val();
	var role_value 			= $('.role_value').val();
	var status 				= $('.status').val();
	var employee_value		= $('.employee_value').val();

	e.preventDefault();
	var obj = $(this), action = obj.attr('name');
	window.location = e.target.action+"?country_value="+country_value+"&location_value="+location_value+"&department_value="+department_value+"&role_value="+role_value+"&status="+status+"&employee_value="+employee_value;
	
});
</script>