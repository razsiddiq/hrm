<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['job_id']) && $_GET['data']=='apply_job'){
$session = $this->session->userdata('username');
$user = $this->Xin_model->read_user_info($session['user_id']);
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">APPLICATION FOR <?php echo $job_title;?></h4>
</div>
<form class="m-b-1" action="<?php echo site_url("frontend/jobs/apply_job").'/'.$job_id.'/'; ?>" method="post" name="apply_job" id="apply_job">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="job_id" value="<?php echo $job_id;?>">
  <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="name">Employe ID</label>
              <input type="text" readonly="readonly" class="form-control" value="<?php echo $user[0]->employee_id;?>">
              
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="email">Name</label>
              <input type="text" readonly="readonly" class="form-control" value="<?php echo $user[0]->first_name. ' ' .$user[0]->last_name;?>">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="contact">Email address</label>
              <input type="text" readonly="readonly" class="form-control" value="<?php echo $user[0]->email;?>">
            </div>
          </div>
        </div>
        <?php $system_setting = $this->Xin_model->read_setting_info(1);?>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="resume">Upload Resume from your computer</label>
              <span class="btn bg-teal-400 btn-file">
              Browse <input type="file" name="resume" id="resume">
            </span>
                <?php if($system_setting[0]->job_application_format!=''){?>
              <br>
              <small>Upload files only: <?php echo $system_setting[0]->job_application_format;?></small><?php } ?> </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-8">
            <div class="form-group">
              <label for="message">Your covering message for <?php echo $job_title;?></label>
              <textarea class="form-control" name="message" id="message" rows="5"></textarea>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn bg-teal-400">Apply</button>
  </div>
</form>
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/select2/dist/css/select2.min.css">
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/select2/dist/js/select2.min.js"></script> 
<script type="text/javascript">
 $(document).ready(function(){		

		/* Edit data */
		$("#apply_job").submit(function(e){
		var fd = new FormData(this);
		var obj = $(this), action = obj.attr('name');
		fd.append("is_ajax", 6);
		fd.append("add_type", 'apply_job');
		fd.append("data", 'apply_job');
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
			success: function(JSON)
			{
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.apply-job').modal('toggle');
					toastr.success(JSON.result);
					$('#apply_job')[0].reset(); // To reset form fields
					$('.save').prop('disabled', false);
				}
			},
			error: function() 
			{
				toastr.error(JSON.error);
				$('.save').prop('disabled', false);
			} 	        
	   });
	});
	});	
  </script> 
<?php }
?>
