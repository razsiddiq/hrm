<?php
/* Employee Details view
*/
?>
<?php if(in_array('employees-list-view',role_resource_ids()) || visa_wise_role_ids() != '') {?>			
					
	<!-- Vertical tabs -->
<?php require_once('employee_detail.php');	?>
	<!-- /vertical tabs -->				
	
	
	
	
	
	<?php } else { ?>
		<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title text-danger">
							 
							 <?php echo $this->lang->line('xin_permission');?>
							
							</h5>
				
						</div>
				</div>

		<?php } ?>					
  
 


<script>
$(document).ready(function(){
	$('input,select,checkbox').prop('disabled',true);
	//$('.dataTable').find('ul.icons-list').append('-'); class="hide_vi"

	//$('th.hide_vi,ul.icons-list').hide();
});

</script>
<!--
<style>
.dataTable tbody > tr > td:last-child,.dataTable thead > tr > th:last-child {
	display: none !important;
}
 /*
.dataTable tbody > tr > td > ul {
	display: none !important;
}
a[data-target=".edit-modal-data"],a[data-target=".edit-modal-data-payrol"],a[data-target=".delete-modal"] {
 display:none !important;
 }*/


button.save,a.delete{display:none !important;}
#for_visa_select,#for_medical_card_select,#country_show, #entry_stamp_date,#doc_number,#document_card_number{display:none;}
._jw-tpk-container ol > li > a {padding:1px 0px;}
.kv-file-zoom.btn.btn-link.btn-xs.btn-icon {
    display: none !important;
}
</style>-->

