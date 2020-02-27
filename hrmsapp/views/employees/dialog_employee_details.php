<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='emp_contact' && $_GET['type']=='emp_contact'){
$field_role=$_GET['field_role'];
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><?php echo change_fletter_caps($field_role);?> Contact</h4>
</div>

<form id="e_contact_info" action="<?php echo site_url("employees/e_contact_info") ?>" name="e_contact_info" method="post">
  <input type="hidden" name="user_id" value="<?php echo $employee_id;?>" id="user_id">
  <input type="hidden" name="e_field_id" id="e_field_id" value="<?php echo $contact_id;?>">
  <input type="hidden" name="u_basic_info" value="UPDATE">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="name" class="control-label"><?php echo $this->lang->line('xin_name');?><?php echo REQUIRED_FIELD;?></label>
          <input class="form-control" placeholder="<?php echo $this->lang->line('xin_name');?>" name="contact_name" type="text" value="<?php echo $contact_name;?>">
        </div>
      </div>

	  	<div class="col-md-6">
	  		<div class="form-group">
          <label for="relation"><?php echo $this->lang->line('xin_e_details_relation');?><?php echo REQUIRED_FIELD;?></label>
          <select class="form-control" name="relation" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_one');?>">
            <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
            <option value="Self" <?php if($relation=='Self'){?> selected="selected" <?php }?>>Self</option>
            <option value="Parent" <?php if($relation=='Parent'){?> selected="selected" <?php }?>>Parent</option>
            <option value="Spouse" <?php if($relation=='Spouse'){?> selected="selected" <?php }?>>Spouse</option>
            <option value="Child" <?php if($relation=='Child'){?> selected="selected" <?php }?>>Child</option>
            <option value="Sibling" <?php if($relation=='Sibling'){?> selected="selected" <?php }?>>Sibling</option>
            <option value="In Laws" <?php if($relation=='In Laws'){?> selected="selected" <?php }?>>In Laws</option>
						<option value="Friend" <?php if($relation=='Friend'){?> selected="selected" <?php }?>>Friend</option>
          </select>
        </div>
      </div>
    </div>
    
		<div class="row">
      <div class="col-md-6">
	  		<?php $mobile_phone=@explode('-',@$mobile_phone);?>
	   		<div class="form-group">
       	  <div class="input-group">
	   				<span class="input-group-addon-custom">
			   			<select class="form-control change_country_code1 js-example-templating_diag" name="country_code">
								<?php foreach(phone_numbers_code() as $keys=>$phone_code){?>
								<option <?php if($keys==@$mobile_phone[0]){echo 'selected';}?> value="<?php echo $keys; ?>-" data-len ="<?php echo $phone_code['length'];?>"   rel="<?php echo $phone_code['country_name'];?>"><?php echo $keys; ?></option>
								<?php } ?>
						  </select>
			  		</span>
            <input class="form-control c_num " placeholder="<?php echo $this->lang->line('xin_e_details_mobile');?>" name="mobile_phone"   type="text" maxlength="9"  value="<?php echo @$mobile_phone[1];?>">
			  	</div>
        </div>
      </div>

	  	<div class="col-md-6">
	   		<div class="form-group mt-5">
          <label class="custom-control custom-checkbox">
            <input type="checkbox" class="styled" id="is_primary" value="1" name="is_primary" <?php if($is_primary=='1'){?> checked="checked" <?php }?>>
            <span class="custom-control-indicator ml-5"></span> <span class="custom-control-description"><?php echo $this->lang->line('xin_e_details_pcontact');?></span>
					</label>
          &nbsp;
          <label class="custom-control custom-checkbox">
            <input type="checkbox" class="styled" id="is_secondary" value="1" name="is_secondary" <?php if($is_secondary=='1'){?> checked="checked"<?php }?>>
            <span class="custom-control-indicator ml-5"></span> <span class="custom-control-description"><?php echo $this->lang->line('xin_e_details_scontact');?></span>
					</label>
        </div>
			</div>
    </div>

    <div class="row">
      <div class="col-md-6">
	    	<div class="form-group">
          <label for="address_1" class="control-label"><?php echo $this->lang->line('xin_address');?></label>
          <input class="form-control" placeholder="<?php echo $this->lang->line('xin_address_1');?>" name="address_1" type="text" value="<?php echo $address_1;?>">
        </div>
      </div>

      <div class="col-md-6">
        <div class="form-group">
					<label for="name" class="control-label"></label>
          <div class="row">
            <div class="col-xs-5">
              <input class="form-control" placeholder="<?php echo $this->lang->line('xin_city');?>" name="city" type="text" value="<?php echo $city;?>">
            </div>
            <div class="col-xs-4">
              <input class="form-control" placeholder="<?php echo $this->lang->line('xin_state');?>" name="state" type="text" value="<?php echo $state;?>">
            </div>
            <div class="col-xs-3">
              <input class="form-control" placeholder="<?php echo $this->lang->line('xin_zipcode');?>" name="zipcode" type="text" value="<?php echo $zipcode;?>">
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
	     	<div class="form-group">
          <input class="form-control" placeholder="<?php echo $this->lang->line('xin_address_2');?>" name="address_2" type="text" value="<?php echo $address_2;?>">
        </div>
      </div>

   		<div class="col-md-6">
        <div class="form-group">
          <select name="country" data-plugin="select_hrm" class="form-control" data-placeholder="<?php echo $this->lang->line('xin_country');?>">
            <option value=""></option>
            <?php foreach($all_countries as $country) {?>
            <option value="<?php echo $country->country_id;?>" <?php if($country->country_id == $country1){?> selected <?php } ?> ><?php echo $country->country_name;?></option>
            <?php } ?>
          </select>
        </div>
      </div>
    </div>
  </div>

  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
	 	<?php if($field_role=='edit') { ?>
    <button type="submit" class="btn bg-teal-400"><?php echo $this->lang->line('xin_update');?></button>
	 	<?php } ?>
  </div>	
</form>

<script type="text/javascript">
$(document).ready(function(){
	$(".js-example-templating_diag").select2({
		templateResult: formatState,
		matcher: matchStart,
		dropdownAutoWidth : true
	});

	$('.dataTables_filter input[type=search]').attr('placeholder','Type to filter...');
	// Enable Select2 select for the length option
	$('.dataTables_length select').select2({
		minimumResultsForSearch: Infinity,
		width: 'auto'
	});
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="xin_select"]').select2($(this).attr('data-options'));
	$(".styled").uniform({
			radioClass: 'choice'
	});

	/* Update contact info */
	$("#e_contact_info").submit(function(e){
		/*Form Submit*/
		e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=5&data=e_contact_info&type=e_contact_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit-modal-data').modal('toggle');
					$('#xin_table_contact').dataTable().api().ajax.reload(function(){
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});

	$(".change_country_code1").change(function () {
		var rel=<?php echo MAX_PHONE_DIGITS;?>;
		$('.c_num').val('');
		$('.c_num').attr('maxlength',rel);
	});
});
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='e_imgdocument' && $_GET['type']=='e_imgdocument'){
$field_role=$_GET['field_role'];
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><?php echo change_fletter_caps($field_role);?>  Document</h4>
</div>

<form id="e_imgdocument_info" action="<?php echo site_url("employees/e_immigration_info") ?>" enctype="multipart/form-data" name="e_imgdocument_info" method="post">
  <input type="hidden" name="user_id" value="<?php echo $d_employee_id;?>" id="user_id">
  <input type="hidden" name="e_field_id" id="e_field_id" value="<?php echo $immigration_id;?>">
  <input type="hidden" name="u_document_info" value="UPDATE">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="relation">Document</label>
          <select name="document_type_id" id="edit_document_type_id" onchange="change_immigration_doc_edit(this.value);" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_choose_dtype');?>">
            <option value=""></option>
            <?php foreach($all_document_types as $document_type) {
									if($document_type->type_id==$document_type_id) {
						?>
            <option value="<?php echo $document_type->type_id;?>" <?php if($document_type->type_id==$document_type_id) {?> selected="selected" <?php } ?>> <?php echo $document_type->type_name;?></option>
            <?php }} ?>
          </select>
        </div>
      </div>

      <div class="col-md-6"  id="edit_doc_number">
        <div class="form-group">
          <label for="document_number" class="control-label"><span id="edit_name_of_doc"></span> Number</label>
		  		<div class="input-group input-group-st1" style="display: inline;">
						<span class="input-group-addon-custom visa_t_hide1" style="display:none;">
							<select class="form-control change_visa_t1">
								<option value="0" <?php if(strlen($document_number)==16){echo 'selected';}?>>General</option>
								<option value="1" <?php if(strlen($document_number)==18){echo 'selected';}?>>Spouse</option>
							</select>
						</span>         		
						<input class="form-control" placeholder="Document Number" onkeyup="edit_check_digits(this.value);" id="edit_document_number" name="document_number" type="text" value="<?php echo $document_number;?>">
		  		</div>
      	</div>
    	</div>
  	</div>

		<div class="row" id="for_visa_select_edit">
			<div class="col-md-12">
				<div class="form-group">
					<label for="issue_date" class="control-label">Visa Under</label>
					<select name="visacard" class="change_type form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_choose_visa_under');?>">
						<option value=""></option>
						<?php foreach($all_visa_under as $visa_under) {?>
						<option <?php if($visa_under->type_id==$card_type) {?> selected="selected" <?php } ?> value="<?php echo $visa_under->type_id;?>"> <?php echo $visa_under->type_name;?></option>
						<?php } ?>
					</select>
				</div>
			</div>
		</div>

		<div class="row" id="for_medical_card_select_edit">
			<div class="col-md-12">
				<div class="form-group">
					<label for="issue_date" class="control-label">Medical Card Type</label>
					<select name="medicalcard" class="change_type form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_choose_medical_card_type');?>">
						<option value=""></option>
						<?php foreach($all_medical_card_types as $medical_card_types) {?>
						<option <?php if($medical_card_types->type_id==$card_type) {?> selected="selected" <?php } ?> value="<?php echo $medical_card_types->type_id;?>"> <?php echo $medical_card_types->type_name;?></option>
						<?php } ?>
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="issue_date" class="control-label">Issue Date</label>
					<div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
						<input class="form-control" placeholder="Issue Date" name="issue_date" size="16" type="text" value="<?php echo format_date('d F Y',$issue_date);?>" readonly>
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-remove"></span>
						</span>
					</div>
				</div>
			</div>
				
			<div class="col-md-6">
				<div class="form-group">
					<label for="expiry_date" class="control-label">Date of Expiry</label>
					<div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
						<input class="form-control" placeholder="Expiry Date" name="expiry_date" size="16" type="text" value="<?php echo format_date('d F Y',$expiry_date);?>" readonly>
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-remove"></span>
						</span>
					</div>
				</div>
			</div>

		</div>

		<div class="row">	
			<div class="col-md-6 edit_entry_stamp_date">
				<input name="eligible_review_date" type="hidden">
				<div class="form-group">
					<label for="date_of_cancellation" class="control-label">Date of Cancellation</label>
					<div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
						<input class="form-control" placeholder="Date of Cancellation" name="date_of_cancellation" size="16" type="text" value="<?php echo format_date('d F Y',$date_of_cancellation);?>" readonly>
						<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
					</div>
				</div>
			</div>

			<div class="col-md-12" id="edit_country_show">
				<div class="form-group" >
					<label for="send_mail">Passport Issued Country</label>
					<select class="form-control" name="country" data-plugin="select_hrm" data-placeholder="Country">
						<option value="">Select One</option>
						<?php foreach($all_countries as $scountry) {?>
						<option value="<?php echo $scountry->country_id;?>" <?php if($scountry->country_id==$country_id) {?> selected="selected" <?php } ?>> <?php echo $scountry->country_name;?></option>
						<?php } ?>
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label for="deltail_document" class="control-label"><?php echo $this->lang->line('xin_e_details_document_file');?></label>
					<br />
					<?php
					if(in_array('documents-edit',role_resource_ids()) || in_array('offer-letter-edit',role_resource_ids())) {
					?>
					<input type="file" class="file-input-preview" name="document_file[]" multiple="multiple">
					<input type="hidden" name="document_file_old" id="document_file_old" value="<?php echo $document_file;?>">
					<small class="help-block"><?php echo $this->lang->line('xin_e_details_d_type_file');?></small><?php } ?>
					<?php if($document_file!='' && $document_file!='no file') {?>
					<br />
					<!--<a class="btn bg-teal-400" href="<?php echo site_url();?>download?type=document/immigration&filename=<?php echo $document_file;?>"><i class="icon-download mr-5"></i> Download</a>-->

					<div class="row">
						<?php  
						if($document_file!='' && $document_file!='no file') {
							$document_file=array_filter(explode(',',$document_file));
							$i=0;
							foreach($document_file as $docu){
							$file_parts = pathinfo($docu);
							if($file_parts['extension']!='pdf'){
						?>
						<div class="col-lg-3 col-sm-6 <?php echo $docu;?>" id="del_im_id_<?php echo $i;?>">
							<div class="thumbnail" style="max-width: 193.5px;">
								<div class="thumb">
									<img src="<?php echo base_url().'uploads/document/immigration/'.$docu;?>" alt="">
									<div class="caption-overflow">
										<span>
											<a href="<?php echo base_url().'uploads/document/immigration/'.$docu;?>" data-popup="lightbox" rel="gallery" class="btn border-white text-white btn-flat btn-icon btn-rounded"><i class="icon-plus3"></i></a>
											<?php if(in_array('documents-delete',role_resource_ids())) {?>
											<a href="#" class="btn border-white text-white btn-flat btn-icon btn-rounded" onclick="document_image_delete('<?php echo $docu;?>','<?php echo $_GET['field_id'];?>',<?php echo $i;?>);"><i class="icon-trash"></i></a>
											<?php } ?>
										</span>
									</div>
								</div>
							</div>
						</div>
						<?php }else { ?>
						<div class="col-lg-3 col-sm-6 <?php echo $docu;?>" id="del_im_id_<?php echo $i;?>">
							<div class="thumbnail" style="max-width: 193.5px;">
								<div class="thumb">
									<img src="<?php echo base_url().'uploads/pdf-preview.jpg';?>" alt="">
									<!--
									<iframe webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen="" scrolling="auto" src="<?php //echo base_url().'uploads/document/immigration/'.$docu;?>" frameborder="0"></iframe>-->
									<div class="caption-overflow">
										<span>
											<a target="_blank" href="<?php echo base_url().'uploads/document/immigration/'.$docu;?>" data-popup="lightbox" rel="gallery" class="btn border-white text-white btn-flat btn-icon btn-rounded"><i class="icon-plus3"></i></a>
											<?php if(in_array('documents-delete',role_resource_ids())) {?>
											<a href="#" class="btn border-white text-white btn-flat btn-icon btn-rounded" onclick="document_image_delete('<?php echo $docu;?>','<?php echo $_GET['field_id'];?>',<?php echo $i;?>);"><i class="icon-trash"></i></a>
											<?php } ?>
										</span>
									</div>
								</div>
							</div>
						</div>
						<?php }	$i++;}  } else {?>

						<div class="col-lg-3 col-sm-6">
							<div class="thumbnail" >
								<div class="thumb">
									<img src="<?php echo base_url().'uploads/no-preview.jpg';?>" alt="">
									<div class="caption-overflow">
										<span>
											<a href="<?php echo base_url().'uploads/no-preview.jpg';?>" data-popup="lightbox" rel="gallery" class="btn border-white text-white btn-flat btn-icon btn-rounded"><i class="icon-plus3"></i></a>
										</span>
									</div>
								</div>
							</div>
						</div>
						<?php } ?>
					</div>
					
					<?php } else {
					if(in_array('documents-edit',role_resource_ids()) || in_array('offer-letter-edit',role_resource_ids())) {
					?>
					<span class="help-block"><br/>
					No Document Uploaded Yet.
					</span>
					<?php }} ?>
				</div>
			</div>
		</div>
	</div>

 	<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
		<?php if($field_role=='edit') { ?>
		<button type="submit" class="btn bg-teal-400"><?php echo $this->lang->line('xin_update');?></button>
		<?php } ?>
  </div>
</form>

<script>
$(document).ready(function(){
	$(function() {
		$('[data-popup="lightbox"]').fancybox({
			openEffect: 'elastic',
			closeEffect: 'elastic',
			autoSize: true,
			type: 'iframe',
			iframe: {
				preload: false // fixes issue with iframe and IE
			}
   	});
	});

	var modalTemplate = '<div class="modal-dialog modal-lg" role="document">\n' +
        '  <div class="modal-content">\n' +
        '    <div class="modal-header">\n' +
        '      <div class="kv-zoom-actions btn-group">{toggleheader}{fullscreen}{borderless}{close}</div>\n' +
        '      <h6 class="modal-title">{heading} <small><span class="kv-zoom-title"></span></small></h6>\n' +
        '    </div>\n' +
        '    <div class="modal-body">\n' +
        '      <div class="floating-buttons btn-group"></div>\n' +
        '      <div class="kv-zoom-body file-zoom-content"></div>\n' + '{prev} {next}\n' +
        '    </div>\n' +
        '  </div>\n' +
        '</div>\n';

	// Buttons inside zoom modal
	var previewZoomButtonClasses = {
			toggleheader: 'btn btn-default btn-icon btn-xs btn-header-toggle',
			fullscreen: 'btn btn-default btn-icon btn-xs',
			borderless: 'btn btn-default btn-icon btn-xs',
			close: 'btn btn-default btn-icon btn-xs'
	};

	// Icons inside zoom modal classes
	var previewZoomButtonIcons = {
			prev: '<i class="icon-arrow-left32"></i>',
			next: '<i class="icon-arrow-right32"></i>',
			toggleheader: '<i class="icon-menu-open"></i>',
			fullscreen: '<i class="icon-screen-full"></i>',
			borderless: '<i class="icon-alignment-unalign"></i>',
			close: '<i class="icon-cross3"></i>'
	};

	// File actions
	var fileActionSettings = {
			zoomClass: 'btn btn-link btn-xs btn-icon',
			zoomIcon: '<i class="icon-zoomin31"></i>',
			dragClass: 'btn btn-link btn-xs btn-icon',
			dragIcon: '<i class="icon-three-bars"></i>',
			removeClass: 'btn btn-link btn-icon btn-xs',
			removeIcon: '<i class="icon-trash"></i>',
			indicatorNew: '<i class="icon-file-plus1 text-slate"></i>',
			indicatorSuccess: '<i class="icon-checkmark3 file-icon-large text-success"></i>',
			indicatorError: '<i class="icon-cross2 text-danger"></i>',
			indicatorLoading: '<i class="icon-spinner2 spinner text-muted"></i>'
	};

	$(".file-input-preview").fileinput({
		uploadUrl: "http://localhost", // server upload action
		uploadAsync: true,
		maxFileCount: 20,
		initialPreview: [],
		fileActionSettings: {
				removeIcon: '<i class="icon-bin"></i>',
				removeClass: 'btn btn-link btn-xs btn-icon',
				uploadIcon: '<i class="icon-upload"></i>',
				uploadClass: 'btn btn-link btn-xs btn-icon',
				indicatorNew: '<i class="icon-file-plus text-slate"></i>',
				indicatorSuccess: '<i class="icon-checkmark3 file-icon-large text-success"></i>',
				indicatorError: '<i class="icon-cross2 text-danger"></i>',
				indicatorLoading: '<i class="icon-spinner2 spinner text-muted"></i>',
		},
		layoutTemplates: {
				icon: '<i class="icon-file-check"></i>',
				modal: modalTemplate
		},

		initialPreviewAsData: true,
		overwriteInitial: false,
		//allowedFileExtensions: ["jpg", "gif", "png", "JPG", "JPEG", "jpeg", "GIF", "PNG", "pdf", "PDF"],
		allowedFileExtensions: ["jpg", "gif", "png", "jpeg", "pdf"],
		initialCaption: "No new file selected",
		previewZoomButtonClasses: previewZoomButtonClasses,
		previewZoomButtonIcons: previewZoomButtonIcons
    });
});


function document_image_delete(name,id,remove_id){
	swal({
		title: "Are you sure?",
		text: "Deleted image can't be restored!",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#FF7043",
		confirmButtonText: "Yes, delete it!",
	},
	function(isConfirm){
			if  (isConfirm){
				$.ajax({
					type: "POST",
					url : site_url+"employees/document_image_delete/",
					data: "name="+name+"&id="+id+"&type=document",
					cache: false,
					success: function (datas) {
						$('#del_im_id_'+remove_id).remove();
						toastr.success('Image removed successully.');
						$('#document_file_old').val(datas);
					}
				});
			}
	});
}
<?php if($document_file!='' && $document_file!='no file') {
	$document_file=explode(',',$document_file);
		foreach($document_file as $doc_file){
		echo '"http://192.168.1.151:8080/hrmsnewui/uploads/document/immigration/'.$doc_file.'",';
		}
	}
?>     
</script>

<script type="text/javascript">
change_immigration_doc_edit(<?php echo $document_type_id;?>);
function change_immigration_doc_edit(id){
	var htm=$("#edit_document_type_id option[value='"+id+"']").val();
	var htm1=$("#edit_document_type_id option[value='"+id+"']").text();
	$('#edit_doc_number').show();
	$('#edit_name_of_doc').html(htm1);

  $('.input-group-st1').attr('style','display:inline');
	$('.visa_t_hide1').hide();

	if(htm ==3){
		$('#for_visa_select_edit').show();
		$('#for_medical_card_select_edit').hide();
		$('#edit_country_show').hide();
		$('.edit_entry_stamp_date').show();

		$('#edit_document_number').attr('maxlength','16');
		$('#edit_document_number').attr('pattern','\\d{3}[\\/]\\d{4}[\\/]\\d{7}');
		$('#edit_document_number').attr('title','Should be Numeric & Format should be 123/1234/1234567');

		var v_type=$('.change_visa_t1').val();
		if(v_type==0){
		$('#edit_document_number').attr('maxlength','16');
		$('#edit_document_number').attr('pattern','\\d{3}[\\/]\\d{4}[\\/]\\d{7}');
		$('#edit_document_number').attr('title','Should be Numeric & Format should be 123/1234/1234567');
		}else{
		$('#edit_document_number').attr('maxlength','18');
		$('#edit_document_number').attr('pattern','\\d{3}[\\/]\\d{4}[\\/]\\d{1}[\\/]\\d{7}');
		$('#edit_document_number').attr('title','Should be Numeric & Format should be 123/1234/1/1234567');
		}

		$('.input-group-st1').attr('style','display:table');
		$('.visa_t_hide1').show();


	}else if(htm ==6){
		$('#for_medical_card_select').show();
		$('#for_medical_card_select_edit').show();
		$('#for_visa_select_edit').hide();
		$('#edit_country_show').hide();
		$('.edit_entry_stamp_date').hide();
		$('#edit_document_number').attr('pattern','^[a-zA-Z0-9]+$');//attr('pattern','^[a-zA-Z][a-zA-Z0-9-_\.]{5,12}$');
		$('#edit_document_number').attr('title','Should be Alphanumeric only');
	}else if(htm ==2){
		$('#edit_country_show').show();
		$('#for_medical_card_select_edit').hide();
		$('#for_visa_select_edit').hide();
		$('.edit_entry_stamp_date').hide();
		$('#edit_document_number').attr('pattern','^[a-zA-Z0-9]+$');//attr('pattern','^[a-zA-Z][a-zA-Z0-9-_\.]{5,12}$');
		$('#edit_document_number').attr('title','Should be Alphanumeric only');
	}else if(htm ==4){
		$('#edit_country_show').hide();
		$('#for_medical_card_select_edit').hide();
		$('#for_visa_select_edit').hide();
		$('.edit_entry_stamp_date').hide();
		$('#edit_document_number').attr('maxlength','18');
		$('#edit_document_number').attr('pattern','\\d{3}[\\-]\\d{4}[\\-]\\d{7}[\\-]\\d{1}');
		$('#edit_document_number').attr('title','Should be Numeric & Format should be 123-1234-1234567-1');
	}else if(htm ==1){
		$('#for_medical_card_select_edit').hide();
		$('#for_visa_select_edit').hide();
		$('#edit_country_show').hide();
		$('.edit_entry_stamp_date').hide();
		$('#edit_document_number').attr('pattern','\\d*');
		$('#edit_document_number').attr('title','Should be Numbers only');

	}else if(htm =5){
		$('#for_medical_card_select_edit').hide();
		$('#for_visa_select_edit').hide();
		$('#edit_country_show').hide();
		$('.edit_entry_stamp_date').hide();
		$('#edit_document_number').removeAttr('pattern');
		$('#edit_document_number').removeAttr('title');
		$('#edit_document_number').removeAttr('maxlength');

	}else{
		$('#for_medical_card_select_edit').hide();
		$('#for_visa_select_edit').hide();
		$('#edit_country_show').hide();
		$('.edit_entry_stamp_date').hide();
		$('#edit_document_number').removeAttr('pattern');
		$('#edit_document_number').removeAttr('title');
		$('#edit_document_number').removeAttr('maxlength');
	}
}

function edit_check_digits(val){
	var htm=$("#edit_document_type_id option:selected").val();
	var values=$('#edit_document_number').val();
	if(htm ==4){
	if(values.length == 3 || values.length == 8 || values.length == 16)
	  {
		$('#edit_document_number').val(values+'-');
	  }
	}else if(htm ==3){
	var v_type=$('.change_visa_t1').val();

		if(v_type==0){
	if(values.length == 3 || values.length == 8)
	  {
		$('#edit_document_number').val(values+'/');
	  }
	}else{
		if(values.length == 3 || values.length == 8 || values.length == 10)
	  {
		$('#edit_document_number').val(values+'/');
	  }
	}
	}
}

$(document).ready(function(){
	$('.dataTables_filter input[type=search]').attr('placeholder','Type to filter...');
	$('.dataTables_length select,.change_visa_t1').select2({
		minimumResultsForSearch: Infinity,
		width: 'auto'
	});
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('.change_visa_t1').on('change',function(){
		var v_type=$('.change_visa_t1').val();
		if(v_type==0){
		$('#edit_document_number').attr('pattern','\\d{3}[\\/]\\d{4}[\\/]\\d{7}');
		$('#edit_document_number').attr('title','Should be Numeric & Format should be 123/1234/1234567');
		$('#edit_document_number').attr('maxlength','16');
		}else{
		$('#edit_document_number').attr('pattern','\\d{3}[\\/]\\d{4}[\\/]\\d{1}[\\/]\\d{7}');
		$('#edit_document_number').attr('title','Should be Numeric & Format should be 123/1234/1/1234567');
		$('#edit_document_number').attr('maxlength','18');
		}
	});
	// Date
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

	/* Update document info */
	$("#e_imgdocument_info").submit(function(e){
		var fd = new FormData(this);
		var obj = $(this), action = obj.attr('name');
		fd.append("is_ajax", 9);
		fd.append("type", 'e_immigration_info');
		fd.append("data", 'e_immigration_info');
		fd.append("form", action);
		e.preventDefault();
		$('.save').prop('disabled', true);

		var edit_document_type_id = $('#edit_document_type_id').val();

		if(edit_document_type_id==7){
			var d_type='xin_table_offerletter';
		}else{
			var d_type='xin_table_imgdocument';
		}
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
					$('.edit-modal-data').modal('toggle');
					$('#'+d_type).dataTable().api().ajax.reload(function(){
						toastr.success(JSON.result);
					}, true);
					toastr.success(JSON.result);
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
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='document_image_preview' && $_GET['type']=='document_image_preview'){
	 header('Content-Type: image/png');
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
		<h4 class="modal-title" id="edit-modal-data">Preview Document</h4>
	</div>
  <input type="hidden" name="user_id" value="<?php echo $d_employee_id;?>" id="user_id">
  <input type="hidden" name="e_field_id" id="e_field_id" value="<?php echo $immigration_id;?>">
  <input type="hidden" name="u_document_info" value="UPDATE">
  <div class="modal-body">
  	<div class="row">
		<?php  
			if($document_file!='' && $document_file!='no file') {
				$document_file=array_filter(explode(',',$document_file));
				$i=0;
				foreach($document_file as $docu){
					$file_parts = pathinfo($docu);
					if($file_parts['extension']!='pdf'){
			?>
			<div class="col-lg-3 col-sm-6 col-md-6 col-xs-6 " id="del_im_id_<?php //echo $i;?>">
				<div class="thumbnail" style="max-width: 193.5px;">
					<div class="thumb">
						<img src="<?php echo base_url().'uploads/document/immigration/'.$docu;?>" alt="">
						<div class="caption-overflow">
							<span>
								<a href="<?php echo base_url().'uploads/document/immigration/'.$docu;?>" data-popup="lightbox" rel="gallery" class="btn border-white text-white btn-flat btn-icon btn-rounded"><i class="icon-plus3"></i></a>
							</span>
						</div>
					</div>
				</div>
			</div>
			<?php }else { ?>

 			<div class="col-lg-3 col-sm-6 col-md-6 col-xs-6 <?php echo $docu;?>" id="del_im_id_<?php echo $i;?>">
				<div class="thumbnail" style="max-width: 193.5px;">
					<div class="thumb">
						<img src="<?php echo base_url().'uploads/pdf-preview.jpg';?>"  alt="">
						<div class="caption-overflow">
							<span>
								<a target="_blank" href="<?php echo base_url().'uploads/document/immigration/'.$docu;?>" data-popup="lightbox" rel="gallery" class="btn border-white text-white btn-flat btn-icon btn-rounded"><i class="icon-plus3"></i></a>
								<a href="#" class="btn border-white text-white btn-flat btn-icon btn-rounded" onclick="document_image_delete('<?php echo $docu;?>','<?php echo $_GET['field_id'];?>',<?php echo $i;?>);"><i class="icon-trash"></i></a>
							</span>
						</div>
					</div>
				</div>
			</div>

			<?php }	$i++;}  ?>


			<div class="clearfix"></div>
			<div class="form-group">
			<div class="col-lg-12">
					<a class="btn bg-teal-400 pull-right" href="<?php echo site_url();?>download/downloadpdf?type=document/immigration&db=<?php echo $_GET['field_type'];?>&id=<?php echo $_GET['field_id'];?>"><i class="icon-download mr-5"></i> Download All</a>
			</div>
			</div>
			<?php } else {?>
			
			<div class="col-lg-3 col-sm-6 col-md-6 col-xs-6">
				<div class="thumbnail">
					<div class="thumb">
						<img src="<?php echo base_url().'uploads/no-preview.jpg';?>" alt="">
						<div class="caption-overflow">
							<span>
								<a href="<?php echo base_url().'uploads/no-preview.jpg';?>" data-popup="lightbox" rel="gallery" class="btn border-white text-white btn-flat btn-icon btn-rounded"><i class="icon-plus3"></i></a>
							</span>
						</div>
					</div>
				</div>
			</div>
 			<?php } ?>
		</div>
	</div>

<style type="text/css">
  .zoomContainer { z-index: 9000; }
</style>

<script>
$(document).ready(function() {
  $('[data-popup="lightbox"]').fancybox({
  autoCenter: true,
  afterShow: function(){
    var win=null;
    var content = $('.fancybox-inner');
		$('.fancybox-wrap')
    // append print button
    .append('<div title="Download" id="fancy_download" style="position: absolute;right: -5em;top: 4em;z-index:9999;background: #249e92;padding: 10px;border: 1px solid #ffff;"><a href="<?php echo site_url();?>download?type=document/immigration&filename='+extract_name($(this).attr('href'))+'" ><i class="icon-download" style="font-size: 1.5em;color: white;cursor: pointer;"></i></a></div>');
    $('.fancybox-wrap')
    // append print button
    .append('<div title="Print" id="fancy_print" style="position: absolute;right: -5em;top: 0em;z-index:9999;background: #249e92;padding: 10px;border: 1px solid #ffff;"><i class="icon-printer" style="font-size: 1.5em;color: white;cursor: pointer;"></i></div>')
    // use .on() in its delegated form to bind a click to the print button event for future elements
    .on("click", "#fancy_print", function(){
      win = window.open("width=200,height=200");
      self.focus();
      win.document.open();
      win.document.write('<'+'html'+'><'+'head'+'><'+'style'+'>');
      win.document.write('body, td { font-family: Verdana; font-size: 10pt;}');
      win.document.write('<'+'/'+'style'+'><'+'/'+'head'+'><'+'body'+'>');
      win.document.write(content.html());
      win.document.write('<'+'/'+'body'+'><'+'/'+'html'+'>');
      win.document.close();
      win.print();
      win.close();
    }); // on
	  $('.zoomContainer').remove();
		$('.fancybox-image').elevateZoom({
			zoomType   : "lens",
			lensShape : "round",
			lensSize    : 200
		});
  },
	afterClose: function() {
			$('.zoomContainer').remove();
	}
 }); // fancybox
}); //  ready

function extract_name(name){
	var lastIndex = name.lastIndexOf("/");
	return  name.substring(lastIndex + 1);
}
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='emp_qualification' && $_GET['type']=='emp_qualification'){
$field_role=$_GET['field_role'];
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><?php echo change_fletter_caps($field_role);?> Qualification</h4>
</div>
<form id="e_qualification_info" action="<?php echo site_url("employees/e_qualification_info") ?>" name="e_qualification_info" method="post">
  <input type="hidden" name="user_id" value="<?php echo $employee_id;?>" id="user_id">
  <input type="hidden" name="e_field_id" id="e_field_id" value="<?php echo $qualification_id;?>">
  <input type="hidden" name="u_basic_info" value="UPDATE">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="name"><?php echo $this->lang->line('xin_e_details_inst_name');?></label>
          <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_inst_name');?>" name="name" type="text" value="<?php echo $name;?>">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="education_level" class="control-label"><?php echo $this->lang->line('xin_e_details_edu_level');?></label>
          <select class="form-control" name="education_level" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_edu_level');?>">
            <?php foreach($all_education_level as $education_level) {?>
            <option value="<?php echo $education_level->type_id;?>" <?php if($education_level->type_id==$education_level_id) {?> selected="selected" <?php } ?>> <?php echo $education_level->type_name;?></option>
            <?php } ?>
          </select>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="from_year" class="control-label"><?php echo $this->lang->line('xin_e_details_timeperiod');?></label>
          <div class="row">
            <div class="col-md-6">

			  <div class="input-group date form_year" data-date="" data-date-format="yyyy"  data-link-format="yyyy">
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_from');?>" name="from_year" size="16" type="text" value="<?php echo $from_year;?>" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>

                </div>


            </div>
            <div class="col-md-6">
			   <div class="input-group date form_year" data-date="" data-date-format="yyyy"  data-link-format="yyyy">
                    <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_to');?>" name="to_year" size="16" type="text" value="<?php echo $to_year;?>" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>

                </div>

            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="language" class="control-label"><?php echo $this->lang->line('xin_e_details_language');?></label>
          <select class="form-control" name="language" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_language');?>">
            <?php foreach($all_qualification_language as $qualification_language) {?>
            <option value="<?php echo $qualification_language->type_id;?>" <?php if($qualification_language->type_id==$language_id) {?> selected="selected" <?php } ?>> <?php echo $qualification_language->type_name;?></option>
            <?php } ?>
          </select>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="skill" class="control-label"><?php echo $this->lang->line('xin_e_details_skill');?></label>
          <select class="form-control" name="skill" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_skill');?>">
		    <option value="">&nbsp;</option>
            <?php foreach($all_qualification_skill as $qualification_skill) {?>
            <option value="<?php echo $qualification_skill->type_id?>" <?php if($qualification_skill->type_id==$skill_id) {?> selected="selected" <?php } ?>><?php echo $qualification_skill->type_name?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="to_year" class="control-label"><?php echo $this->lang->line('xin_description');?></label>
          <textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_description');?>" data-show-counter="1" data-limit="300" name="description" cols="30" rows="3" id="d_description"><?php echo $description;?></textarea>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
		<?php if($field_role=='edit') { ?>
    <button type="submit" class="btn bg-teal-400"><?php echo $this->lang->line('xin_update');?></button>
		<?php } ?>
  </div>
</form>


<script type="text/javascript">
$(document).ready(function(){
	// On page load: table_contacts
	$('.dataTables_filter input[type=search]').attr('placeholder','Type to filter...');
	// Enable Select2 select for the length option
	$('.dataTables_length select').select2({
		minimumResultsForSearch: Infinity,
		width: 'auto'
	});
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="xin_select"]').select2($(this).attr('data-options'));


	$('.form_year').datetimepicker({
		weekStart: 1,
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 4,
		minView: 4,
		forceParse: 0,
		pickerPosition: "bottom-left"
	});

	/* Update qualification info */
	$("#e_qualification_info").submit(function(e){
	/*Form Submit*/
		e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=11&data=e_qualification_info&type=e_qualification_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit-modal-data').modal('toggle');
				    $('#xin_table_qualification').dataTable().api().ajax.reload(function(){
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='emp_work_experience' && $_GET['type']=='emp_work_experience'){
	$field_role=$_GET['field_role'];
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><?php echo change_fletter_caps($field_role);?> Work Experience</h4>
</div>

<form id="e_work_experience_info" action="<?php echo site_url("employees/e_work_experience_info") ?>" name="e_work_experience_info" method="post">
  <input type="hidden" name="user_id" id="user_id" value="<?php echo $employee_id;?>">
  <input type="hidden" name="e_field_id" id="e_field_id" value="<?php echo $work_experience_id;?>">
  <input type="hidden" name="u_basic_info" value="UPDATE">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="company_name"><?php echo $this->lang->line('xin_company_name');?><?php echo REQUIRED_FIELD;?></label>
          <input class="form-control" placeholder="<?php echo $this->lang->line('xin_company_name');?>" name="company_name" type="text" value="<?php echo $company_name;?>" id="company_name">
        </div>
        <div class="form-group">
          <label for="from_date"><?php echo $this->lang->line('xin_e_details_frm_date');?><?php echo REQUIRED_FIELD;?></label>
					<div class="input-group date form_month_year" data-date="" data-date-format="yyyy MM"  data-link-format="yyyy MM">
						<input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_from');?>" name="from_date" size="16" type="text" value="<?php echo format_date('Y F',$from_date);?>" readonly>
						<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
					</div>
        </div>
        <div class="form-group">
          <label for="to_date"><?php echo $this->lang->line('xin_e_details_to_date');?></label>
          <br/>
					<div class="col-lg-9 no-padding-left">
						<div class="input-group date form_month_year" data-date="" data-date-format="yyyy MM"  data-link-format="yyyy MM">
								<input onchange="jQuery('.is_current_exp').closest('span').removeClass('checked');jQuery('.is_current_exp').prop('checked',false);" class="form-control exp_to_date" placeholder="<?php echo $this->lang->line('xin_e_details_to_date');?>" name="to_date" size="16" type="text" value="<?php echo format_date('Y F',$to_date);?>" readonly>
								<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
						</div>
					</div>
					<div class="col-lg-3">
						<div class="form-group mt-10">
							<label class="custom-control custom-checkbox">
							<input type="checkbox" class="styled is_current_exp" <?php if(format_date('Y F',$to_date)==''){ echo 'checked';}?> id="is_current_exp" value="1" name="is_current_exp" onclick="jQuery('.exp_to_date').val('');">
							<span class="custom-control-indicator ml-5"></span> <span class="custom-control-description">Current</span> </label>
						</div>
					</div>
      	</div>
      </div>
			
      <div class="col-md-6">
        <div class="form-group">
          <label for="post"><?php echo $this->lang->line('xin_e_details_post');?> (Designation)<?php echo REQUIRED_FIELD;?></label>
          <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_post');?>" name="post" type="text" value="<?php echo $post;?>" id="post">
        </div>
        <div class="form-group">
          <label for="description"><?php echo $this->lang->line('xin_description');?></label>
          <textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_description');?>" data-show-counter="1" data-limit="300" name="description" style="min-height: 120px;" id="description"><?php echo $description;?></textarea>
          <span class="countdown"></span>
				</div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
		<?php if($field_role=='edit') { ?>
    <button type="submit" class="btn bg-teal-400"><?php echo $this->lang->line('xin_update');?></button>
		<?php } ?>
  </div>
</form>

<style>
	#AnyTime--<?php echo $fi_st;?>,#AnyTime--<?php echo $se_st;?> {
			z-index: 9999;
			top:46% !important;
	}
</style>

<script type="text/javascript">
$(document).ready(function(){
	$(".styled").uniform({
    radioClass: 'choice'
	});
	$('.dataTables_filter input[type=search]').attr('placeholder','Type to filter...');
	$('.dataTables_length select').select2({
		minimumResultsForSearch: Infinity,
		width: 'auto'
	});
	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));

	$('.form_month_year').datetimepicker({
		weekStart: 1,
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 3,
		minView: 4,
		forceParse: 0,
		pickerPosition: "bottom-left"
  });

	$("#e_work_experience_info").submit(function(e){
	/*Form Submit*/
		e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=14&data=e_work_experience_info&type=e_work_experience_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit-modal-data').modal('toggle');
					$('#xin_table_work_experience').dataTable().api().ajax.reload(function(){
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});
</script>

<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='emp_bank_account' && $_GET['type']=='emp_bank_account'){
$field_role=$_GET['field_role'];
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><?php echo change_fletter_caps($field_role);?> Bank Account</h4>
</div>
<form id="e_bank_account_info" action="<?php echo site_url("employees/e_bank_account_info") ?>" name="e_bank_account_info" method="post">
  <input type="hidden" name="user_id" id="user_id" value="<?php echo $employee_id;?>">
  <input type="hidden" name="e_field_id" id="e_field_id" value="<?php echo $bankaccount_id;?>">
  <input type="hidden" name="u_basic_info" value="UPDATE">
  <div class="modal-body">
    <div class="row">
      <div class="col-sm-6">
	  		<input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_acc_title');?>" name="account_title" type="hidden" value="<?php echo $account_title;?>" id="account_name">
        <div class="form-group">
          <label for="account_number"><?php echo $this->lang->line('xin_e_details_acc_number');?></label>
          <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_acc_number');?>" name="account_number" type="text" value="<?php echo $account_number;?>" id="account_number" style="text-transform:uppercase;">
        </div>
				<div class="form-group">
          <label for="bank_code"><?php echo $this->lang->line('xin_e_details_bank_code');?></label>
          <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_bank_code');?>" name="bank_code" type="text" value="<?php echo $bank_code;?>" id="bank_code" style="text-transform:uppercase;">
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label for="bank_name"><?php echo $this->lang->line('xin_e_details_bank_name');?></label>
          <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_bank_name');?>" name="bank_name" type="text" value="<?php echo $bank_name;?>" id="bank_name" style="text-transform:uppercase;">
        </div>
        <div class="form-group">
          <label for="bank_branch"><?php echo $this->lang->line('xin_e_details_bank_branch');?></label>
          <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_bank_branch');?>" name="bank_branch" type="text" value="<?php echo $bank_branch;?>" id="bank_branch" style="text-transform:uppercase;">
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
		<?php if($field_role=='edit') { ?>
    <button type="submit" class="btn bg-teal-400"><?php echo $this->lang->line('xin_update');?></button>
		<?php }?>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){	
	$('.dataTables_filter input[type=search]').attr('placeholder','Type to filter...');
	$('.dataTables_length select').select2({
		minimumResultsForSearch: Infinity,
		width: 'auto'
	});
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));


	/* Update bank acount info */
	$("#e_bank_account_info").submit(function(e){
	/*Form Submit*/
		e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=17&data=e_bank_account_info&type=e_bank_account_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit-modal-data').modal('toggle');
					$('#xin_table_bank_account').dataTable().api().ajax.reload(function(){
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='emp_contract' && $_GET['type']=='emp_contract'){
$field_role=$_GET['field_role'];
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><?php echo change_fletter_caps($field_role);?> Contract</h4>
</div>
<form id="e_contract_info" action="<?php echo site_url("employees/e_contract_info") ?>" enctype="multipart/form-data" name="e_contract_info" method="post">
  <input type="hidden" name="user_id" id="user_id" value="<?php echo $employee_id;?>">
  <input type="hidden" name="e_field_id" id="e_field_id" value="<?php echo $contract_id;?>">
  <input type="hidden" name="u_basic_info" value="UPDATE">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="contract_type_id" class=""><?php echo $this->lang->line('xin_e_details_contract_type');?></label>
          <select class="form-control" name="contract_type_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_one');?>" onchange="contract_change_diag(this.value)" >
            <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
            <?php foreach($all_contract_types as $contract_type) {?>
            <option value="<?php echo $contract_type->type_id;?>" <?php if($contract_type->type_id==$contract_type_id) {?> selected="selected" <?php } ?>> <?php echo $contract_type->type_name;?></option>
            <?php } ?>
          </select>
        </div>
        <div class="form-group">
          <label class="" for="from_date"><?php echo 'Contract Start Date'; ?></label>
          <!--<input type="text" class="form-control e_cont_date" name="from_date" placeholder="<?php echo $this->lang->line('xin_e_details_frm_date');?>" readonly value="<?php echo format_date('d F Y',$from_date);?>">-->

		  		<div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
						<input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_frm_date');?>" name="from_date" size="16" type="text" value="<?php if($from_date!=''){echo format_date('d F Y',$from_date);}?>" readonly>
						<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
					</div>
        </div>
				<input type="hidden" class="contract_hide1" style="display:hide;" name="to_date">		 		
				<div class="form-group contract_show1">
        	<label for="to_date"><?php echo 'Contract End Date';?></label>
         	<!-- <input type="text" class="form-control e_cont_date" name="to_date" placeholder="<?php echo $this->lang->line('xin_e_details_to_date');?>" readonly value="<?php echo format_date('d F Y',$to_date);?>">-->

		  		<div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
						<input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_to_date');?>" name="to_date" size="16" type="text" value="<?php if($to_date!=''){echo format_date('d F Y',$to_date);}?>" readonly>
						<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
					</div>
        </div>
      </div>
      <div class="col-md-6">
				<div class="form-group">
					<label for="description"><?php echo $this->lang->line('xin_description');?></label>
					<textarea style="min-height: 204px;" class="form-control" placeholder="<?php echo $this->lang->line('xin_description');?>" data-show-counter="1" data-limit="300" name="description" cols="30" rows="3" id="description"><?php echo $description;?></textarea>
					<span class="countdown"></span>
				</div>
			</div>
  	</div>

	 	<div class="row">
  		<div class="col-md-12">
    		<div class="form-group">
		  		<label for="deltail_document" class="control-label"><?php echo $this->lang->line('xin_e_details_contract_file');?></label><br />
          <?php
					if(in_array('contract-edit',role_resource_ids())) {
					?>
          <input type="file" class="file-input-preview" name="document_file[]" multiple="multiple">
          <input type="hidden" name="document_file_old" id="document_file_old" value="<?php echo $document_file;?>">
          <small class="help-block"><?php echo $this->lang->line('xin_e_details_d_type_file');?></small><?php } ?>
          <?php if($document_file!='' && $document_file!='no file') {?>
          <br />
          <!--<a class="btn bg-teal-400" href="<?php echo site_url();?>download?type=document/immigration&filename=<?php echo $document_file;?>"><i class="icon-download mr-5"></i> Download</a>-->
          <?php } else {
					if(!in_array('contract-edit',role_resource_ids())) {
					?>

					<span class="help-block"><br/>
					No Document Uploaded Yet.
					</span>

			  	<?php }} ?>
        </div>
  		</div>
		</div>

		<div class="row">
			<?php  
			if($document_file!='' && $document_file!='no file') {
				$document_file=array_filter(explode(',',$document_file));
				$i=0;
				foreach($document_file as $docu){
				$file_parts = pathinfo($docu);
				if($file_parts['extension']!='pdf'){
			?>
			<div class="col-lg-3 col-sm-6 <?php echo $docu;?>" id="del_im_id_<?php echo $i;?>">
				<div class="thumbnail" style="max-width: 193.5px;">
					<div class="thumb">
						<img src="<?php echo base_url().'uploads/document/immigration/'.$docu;?>" alt="">
						<div class="caption-overflow">
							<span>
								<a href="<?php echo base_url().'uploads/document/immigration/'.$docu;?>" data-popup="lightbox" rel="gallery" class="btn border-white text-white btn-flat btn-icon btn-rounded"><i class="icon-plus3"></i></a>
								<?php if(in_array('contract-delete',role_resource_ids())) { ?>
								<a href="#" class="btn border-white text-white btn-flat btn-icon btn-rounded" onclick="document_image_delete('<?php echo $docu;?>','<?php echo $_GET['field_id'];?>',<?php echo $i;?>);"><i class="icon-trash"></i></a>
								<?php } ?>
							</span>
						</div>
					</div>
				</div>
			</div>


			<?php }else { ?>

  		<div class="col-lg-3 col-sm-6 <?php echo $docu;?>" id="del_im_id_<?php echo $i;?>">
				<div class="thumbnail" style="max-width: 193.5px;">
					<div class="thumb">
						<img src="<?php echo base_url().'uploads/pdf-preview.jpg';?>" alt="">
						<!--
						<iframe webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen="" scrolling="auto" src="<?php //echo base_url().'uploads/document/immigration/'.$docu;?>" frameborder="0"></iframe>-->
						<div class="caption-overflow">
							<span>
								<a target="_blank" href="<?php echo base_url().'uploads/document/immigration/'.$docu;?>" data-popup="lightbox" rel="gallery" class="btn border-white text-white btn-flat btn-icon btn-rounded"><i class="icon-plus3"></i></a>
								<?php if(in_array('contract-delete',role_resource_ids())) { ?>
								<a href="#" class="btn border-white text-white btn-flat btn-icon btn-rounded" onclick="document_image_delete('<?php echo $docu;?>','<?php echo $_GET['field_id'];?>',<?php echo $i;?>);"><i class="icon-trash"></i></a>
								<?php } ?>
							</span>
						</div>
					</div>
				</div>
			</div>

			<?php }	$i++;}  } else {?>

 			<div class="col-lg-3 col-sm-6">
				<div class="thumbnail" >
					<div class="thumb">
						<img src="<?php echo base_url().'uploads/no-preview.jpg';?>" alt="">
						<div class="caption-overflow">
							<span>
								<a href="<?php echo base_url().'uploads/no-preview.jpg';?>" data-popup="lightbox" rel="gallery" class="btn border-white text-white btn-flat btn-icon btn-rounded"><i class="icon-plus3"></i></a>
							</span>
						</div>
					</div>
				</div>
			</div>

 			<?php } ?>


		</div>
	</div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
		<?php if($field_role=='edit') { ?>
    <button type="submit" class="btn bg-teal-400"><?php echo $this->lang->line('xin_update');?></button>
		<?php } ?>
  </div>
</form>

<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/fileupload.js"></script>
<script type="text/javascript">
contract_change_diag(<?php echo $contract_type_id;?>);
function contract_change_diag(id){
	if(id==18){
		$('.contract_hide1').show();
		$('.contract_show1').hide();
	}else{
		$('.contract_hide1').hide();
		$('.contract_show1').show();
	}
}

$(function() {
  $('[data-popup="lightbox"]').fancybox({
        padding: 3
  });
});

$(document).ready(function(){
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

	var modalTemplate = '<div class="modal-dialog modal-lg" role="document">\n' +
        '  <div class="modal-content">\n' +
        '    <div class="modal-header">\n' +
        '      <div class="kv-zoom-actions btn-group">{toggleheader}{fullscreen}{borderless}{close}</div>\n' +
        '      <h6 class="modal-title">{heading} <small><span class="kv-zoom-title"></span></small></h6>\n' +
        '    </div>\n' +
        '    <div class="modal-body">\n' +
        '      <div class="floating-buttons btn-group"></div>\n' +
        '      <div class="kv-zoom-body file-zoom-content"></div>\n' + '{prev} {next}\n' +
        '    </div>\n' +
        '  </div>\n' +
        '</div>\n';

	// Buttons inside zoom modal
	var previewZoomButtonClasses = {
			toggleheader: 'btn btn-default btn-icon btn-xs btn-header-toggle',
			fullscreen: 'btn btn-default btn-icon btn-xs',
			borderless: 'btn btn-default btn-icon btn-xs',
			close: 'btn btn-default btn-icon btn-xs'
	};

	// Icons inside zoom modal classes
	var previewZoomButtonIcons = {
			prev: '<i class="icon-arrow-left32"></i>',
			next: '<i class="icon-arrow-right32"></i>',
			toggleheader: '<i class="icon-menu-open"></i>',
			fullscreen: '<i class="icon-screen-full"></i>',
			borderless: '<i class="icon-alignment-unalign"></i>',
			close: '<i class="icon-cross3"></i>'
	};

	// File actions
	var fileActionSettings = {
			zoomClass: 'btn btn-link btn-xs btn-icon',
			zoomIcon: '<i class="icon-zoomin31"></i>',
			dragClass: 'btn btn-link btn-xs btn-icon',
			dragIcon: '<i class="icon-three-bars"></i>',
			removeClass: 'btn btn-link btn-icon btn-xs',
			removeIcon: '<i class="icon-trash"></i>',
			indicatorNew: '<i class="icon-file-plus1 text-slate"></i>',
			indicatorSuccess: '<i class="icon-checkmark3 file-icon-large text-success"></i>',
			indicatorError: '<i class="icon-cross2 text-danger"></i>',
			indicatorLoading: '<i class="icon-spinner2 spinner text-muted"></i>'
	};

	$(".file-input-preview").fileinput({
		uploadUrl: "http://localhost", // server upload action
			uploadAsync: true,
			maxFileCount: 20,
			initialPreview: [],
			fileActionSettings: {
					removeIcon: '<i class="icon-bin"></i>',
					removeClass: 'btn btn-link btn-xs btn-icon',
					uploadIcon: '<i class="icon-upload"></i>',
					uploadClass: 'btn btn-link btn-xs btn-icon',
					indicatorNew: '<i class="icon-file-plus text-slate"></i>',
					indicatorSuccess: '<i class="icon-checkmark3 file-icon-large text-success"></i>',
					indicatorError: '<i class="icon-cross2 text-danger"></i>',
					indicatorLoading: '<i class="icon-spinner2 spinner text-muted"></i>',
			},
			layoutTemplates: {
					icon: '<i class="icon-file-check"></i>',
					modal: modalTemplate
			},

			initialPreviewAsData: true,
			allowedFileExtensions: ["jpg", "gif", "png", "jpeg", "pdf"],
			overwriteInitial: false,
			initialCaption: "No new file selected",
			previewZoomButtonClasses: previewZoomButtonClasses,
			previewZoomButtonIcons: previewZoomButtonIcons
		});
		// On page load:

	  $('.dataTables_filter input[type=search]').attr('placeholder','Type to filter...');
    $('.dataTables_length select').select2({
       minimumResultsForSearch: Infinity,
       width: 'auto'
    });
		
		$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));

    $(".styled").uniform({
        radioClass: 'choice'
    });
		// Date
		$('.e_cont_date').pickadate({
			format: "dd mmmm yyyy",
					labelMonthNext: 'Go to the next month',
					labelMonthPrev: 'Go to the previous month',
					labelMonthSelect: 'Pick a month from the dropdown',
					labelYearSelect: 'Pick a year from the dropdown',
					selectMonths: true,
					selectYears: 100
		}/*{
	  changeMonth: true,
	  changeYear: true,
	  dateFormat: 'dd M yy',//'yy-mm-dd',
	  yearRange: '1950:' + new Date().getFullYear()
		}*/);


	/* Update document info */
	
	$("#e_contract_info").submit(function(e){
		var fd = new FormData(this);
		var obj = $(this), action = obj.attr('name');
		fd.append("is_ajax", 20);
		fd.append("type", 'e_contract_info');
		fd.append("data", 'e_contract_info');
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
					$('.edit-modal-data').modal('toggle');
					$('#xin_table_contract').dataTable().api().ajax.reload(function(){
						toastr.success(JSON.result);
					}, true);
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

function document_image_delete(name,id,remove_id){
	swal({
			title: "Are you sure?",
			text: "Deleted image can't be restored!",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#FF7043",
			confirmButtonText: "Yes, delete it!",
	},
	function(isConfirm){
			if (isConfirm) {
				$('#del_im_id_'+remove_id).remove();
				$.ajax({
					type: "POST",
					url : site_url+"employees/document_image_delete/",
					data: "name="+name+"&id="+id+"&type=contract",
					cache: false,
					success: function (datas) {
							console.log(datas);
							toastr.success('Image removed successully.');
							$('#document_file_old').val(datas);
					}
				});
			}
	});
}

</script>
<?php } ?>