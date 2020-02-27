<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$session = $this->session->userdata('username');
if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_external_adjusments' && $_GET['type']=='external_adjustments'){

$row=$this->Payroll_model->get_adjustment_id($_GET['field_id']);


?>
<?php
$payment_month = strtotime($this->input->get('pay_date'));
$p_month = date('F Y',$payment_month);

$p_date=date('Y-m',strtotime($this->input->get('pay_date')));

?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit External Adjustments</h4>
</div>
<div class="modal-body">  
    <form action="<?php echo site_url("payroll/update_external_adjustments/".$row[0]->adjustment_id) ?>" method="post" name="add_external_adjustments" id="edit-form">
		  <input type="hidden" name="<?= csrf_name;?>" value="<?= csrf_hash;?>" />
          <input type="hidden" name="_user" value="<?php echo $session['user_id'];?>">

          <div class="panel-body">            
              <div class="row">
                <div class="col-md-6">              
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="department">External Adjustments Type</label>
                        <select id="parent_type" onchange="getParentChildType_dia(this.value)" name="adjustment_type" class="form-control" data-plugin="select_hrm" data-placeholder="Choose the External Adjustments Type...">
                        <option value="">&nbsp;</option>
                        <?php foreach($this->Payroll_model->get_adjustments('parent','external') as $par) {  ?>
                        <option <?php if($row[0]->adjustment_type==$par->type_id){echo 'selected';}?> value="<?php echo $par->type_id;?>"><?php echo $par->type_name;?></option>
                      <?php } ?>
                      </select>
                      </div>
					  
					  
					  <div class="form-group">
                        <label for="department">Adjustment Name</label>
                        <select id="child_type_1" class="form-control" data-plugin="select_hrm" name="adjustment_name" data-placeholder="Choose the Adjustments...">
                        <option value="">&nbsp;</option>                    
                    
                    </select>
                      </div>
					 <div class="form-group">
                        <label for="department">Comments</label>
						<textarea style="resize:none;min-height: 107px;" class="form-control" placeholder="Comments" name="comments"><?php echo $row[0]->comments;?></textarea>
                      
                      </div>



					   
                    </div>                
                  </div>      
         </div>
                <div class="col-md-6">
                   
				     <div class="row">
                    <div class="col-md-12">
                   
					  <div class="form-group">
                        <label for="department">Adjustment for Employee</label>
                        <select name="adjustment_for_employee" class="form-control" onchange="get_currency_byemployee1(this.value);" data-plugin="select_hrm" data-placeholder="Choose the Employee...">
						<option value="">&nbsp;</option>
                       <?php foreach($this->Payroll_model->get_employees_nameuserid($type='') as $emps) {?>
                        <option <?php if($row[0]->adjustment_for_employee==$emps->user_id){echo 'selected';}?> value="<?php echo $emps->user_id;?>"><?php echo change_to_caps($emps->first_name.' '.$emps->middle_name.' '.$emps->last_name);?></option>
                        <?php } ?>
                    </select>
                      </div>
					
					  
					  <div class="form-group" id="end_date_of_p_1" style="display:none;">
                        <label for="end_date">End Date</label>
                        
						
						<div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
						<input class="form-control" placeholder="End Date" name="end_date" size="16" type="text" value="<?php echo format_date('d F Y',$row[0]->end_date);?>" readonly>
						<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>				
					  </div>
					  
					  
                      </div>
					 
					    <label for="department">Amount</label>
					  <div class="form-group">
                        
                        <input class="form-control" placeholder="Amount" name="adjustment_amount" pattern="\d*\.?\d*" title="Format should be 5000 or 5000.00" type="text" value="<?php echo $row[0]->adjustment_amount;?>"><div id="form-control-currency1"  class="form-control-currency"><?php //echo currency_default();?></div>
                      </div>
					  
					  <!--<div class="form-group">
                        
                       <label for="department">VAT (%)</label>
					    <div class="form-group">
                        
                        <input class="form-control" placeholder="VAT" name="tax_percentage" pattern="\d*\.?\d*" title="Format should be 5000 or 5000.00" type="text" value="<?php echo $row[0]->tax_percentage;?>">
					
                      </div>
					  
					  
					  
					 </div>-->
					  
			  <div class="form-group">
             <label for="company_name">Pay Amount Compute with Employee's total working hours</label>
             <div class="pull-xs-left m-r-1">
             <input type="checkbox" class="switchery1" data-size="small" data-color="#3e70c9" data-secondary-color="#ddd" <?php if($row[0]->compute_amount=='1'):?> checked="checked" <?php endif;?> id="compute_amount1" value="1">
            </div>
          </div>


 <div class="form-group">
                        <label for="department">Status</label>
                        <select name="status" class="form-control" data-plugin="select_hrm" data-placeholder="Choose the status...">
                         <option <?php if($row[0]->status==1){echo 'selected';}?> value="1">Active</option>
                         <option <?php if($row[0]->status==0){echo 'selected';}?> value="0">In-Active</option>
                      </select>
                      </div>

                    </div>
                
                  </div>
				  
				  
              </div>
              </div>
			  
	<div class="clearfix"></div>
			<div class="text-right">						
									
    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
    <button type="submit" class="btn bg-teal-400 save"><?php echo $this->lang->line('xin_update');?></button>
									</div>


              
            </div>
        
        </form>
   

  
</div>
</div>

<script type="text/javascript">
getParentChildType_dia('<?php echo $row[0]->adjustment_type;?>');
get_currency_byemployee1('<?php echo $row[0]->adjustment_for_employee;?>');
function getParentChildType_dia(val){
//$('input[name=end_date]').val('');
	    // 50 52
		if(val==51){
			$('#end_date_of_p_1').show();
		}else{
			$('#end_date_of_p_1').hide();
		}

        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
          
            if (this.readyState == 4 && this.status == 200) {
				var cods=JSON.parse(this.responseText);
				//console.log(cods);
                document.getElementById("child_type_1").innerHTML = cods.html;				
                $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	            $('[data-plugin="select_hrm"]').select2({ width:'100%' });
            }
        };

        xmlhttp.open("GET",site_url+'payroll/getChildType/'+val+'/'+<?php echo $row[0]->adjustment_name;?>,true);
        xmlhttp.send();
}


function get_currency_byemployee1(id){

	 if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
          
            if (this.readyState == 4 && this.status == 200) {
				var cods=JSON.parse(this.responseText);
                document.getElementById("form-control-currency1").innerHTML = cods.html;				
      
            }
        };
        xmlhttp.open("GET",site_url+'payroll/get_currency_byemployee/'+id,true);
        xmlhttp.send();
}
</script>
<script type="text/javascript">
$(document).ready(function(){

	
 if (Array.prototype.forEach) {
        var elems = Array.prototype.slice.call(document.querySelectorAll('.switchery1'));
        elems.forEach(function(html) {
            var switchery = new Switchery(html);
        });
    }
    else {
        var elems = document.querySelectorAll('.switchery1');
        for (var i = 0; i < elems.length; i++) {
            var switchery = new Switchery(elems[i]);
        }
    }


	$.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        }
    });
	 var xin_table = $('#xin_table').DataTable({
		"bDestroy": true,
		"iDisplayLength": 100,
		"ajax": {
			url : site_url+"payroll/adjustments_list/external_adjustments",
			type : 'GET'
		},"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		},	
            buttons: [
                {
                    extend: 'copyHtml5',
                    className: 'btn btn-default',
					 exportOptions: {
                        columns: [ 0, ':visible' ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    className: 'btn btn-default',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    className: 'btn btn-default',
                    exportOptions: {
                        columns: ':visible'// [ 1, 2, 3, 4, 5, 6, 7]
                    }
                },
				{
                extend: 'print',
                text: '<i class="icon-printer position-left"></i> Print table',
                className: 'btn btn-default',
                exportOptions: {
                    columns: ':visible'
                }
                },
                {
                    extend: 'colvis',
                    text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
                    className: 'btn bg-teal-400 btn-icon'
                }
            ]
        
    });
	
	
	// Add placeholder to the datatable filter option
    $('.dataTables_filter input[type=search]').attr('placeholder','Type to filter...');


    // Enable Select2 select for the length option
    $('.dataTables_length select,.change_country_code').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
	
    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));	


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

	$("#edit-form").submit(function(e){
	e.preventDefault();
	
		if($('#compute_amount1').is(':checked')){
		var compute_amount = $("#compute_amount1").val();
		} else {
		var compute_amount = 0;
		}  
		
		
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=1&edit_type=external_adjustments&form="+action+"&compute_amount="+compute_amount,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {				
					xin_table.ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.edit-modal-data').modal('toggle');
					$('.save').prop('disabled', false);
					$(".add-new-form").show();
				}
			}
		});
	});

	
	
});	
</script>

<?php } 
else 
if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_internal_adjusments' && $_GET['type']=='internal_adjustments'){

$row=$this->Payroll_model->get_adjustment_id($_GET['field_id']);

?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Internal Adjustments</h4>
</div>
<div class="modal-body">  
    <form action="<?php echo site_url("payroll/update_internal_adjustments/".$row[0]->adjustment_id) ?>" method="post" name="add_external_adjustments" id="edit-form">
		  <input type="hidden" name="<?= csrf_name;?>" value="<?= csrf_hash;?>" />
          <input type="hidden" name="_user" value="<?php echo $session['user_id'];?>">
          <div class="panel-body">            
              <div class="row">
                <div class="col-md-6">              
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="department">Internal Adjustments Type</label>
                        <select id="parent_type" onchange="getParentChildType_dia(this.value)" name="adjustment_type" class="form-control" data-plugin="select_hrm" data-placeholder="Choose the Internal Adjustments Type...">
                        <option value="">&nbsp;</option>
                        <?php foreach($this->Payroll_model->get_adjustments('parent','internal') as $par) {  ?>
                        <option <?php if($row[0]->adjustment_type==$par->type_id){echo 'selected';}?> value="<?php echo $par->type_id;?>"><?php echo $par->type_name;?></option>
                      <?php } ?>
                    </select>
                      </div>
					  
					  
					  <div class="form-group">
                        <label for="department">Adjustment Name</label>
                        <select id="child_type_1" class="form-control" data-plugin="select_hrm" name="adjustment_name" data-placeholder="Choose the Adjustments...">
                        <option value="">&nbsp;</option>                    
                    
                    </select>
                      </div>
					  <label for="department">Amount</label>
					  <div class="form-group">
                        
                        <input class="form-control" placeholder="Amount" name="adjustment_amount" pattern="\d*\.?\d*" title="Format should be 5000 or 5000.00" type="text" value="<?php echo $row[0]->adjustment_amount;?>"><div class="form-control-currency"><?php //echo currency_default();?></div>
                      </div>					   
                    </div>                
                  </div>      
         </div>
                <div class="col-md-6">
                   
				     <div class="row">
                    <div class="col-md-12">
                   
					  <div class="form-group">
                        <label for="department">Adjustment for Employee</label>
                        <select name="adjustment_for_employee" class="form-control" data-plugin="select_hrm" data-placeholder="Choose the Employee...">
						<option value="">&nbsp;</option>
                       <?php foreach($this->Payroll_model->get_employees_nameuserid($type='') as $emps) {?>
                        <option <?php if($row[0]->adjustment_for_employee==$emps->user_id){echo 'selected';}?> value="<?php echo $emps->user_id;?>"><?php echo change_to_caps($emps->first_name.' '.$emps->middle_name.' '.$emps->last_name);?></option>
                        <?php } ?>
                    </select>
                      </div>
					   <div class="form-group">
                        <label for="department">Prepared By</label>
                        <select name="adjustment_perpared_by" class="form-control" data-plugin="select_hrm" data-placeholder="Choose Prepared By...">
						<option value="">&nbsp;</option>
                       <?php foreach($this->Payroll_model->get_employees_nameuserid($type='hr') as $emps) {?>
                        <option <?php if($row[0]->adjustment_perpared_by==$emps->user_id){echo 'selected';}?> value="<?php echo $emps->user_id;?>"> <?php echo change_to_caps($emps->first_name);?></option>
                        <?php } ?>
                    </select>
                      </div>
					  
					  <div class="form-group">
                        <label for="end_date">Date of Entry</label>
                        <input class="form-control e_date" readonly placeholder="Date of Entry" name="end_date" type="text" value="<?php if($row[0]->end_date!=''){echo format_date('d F Y',$row[0]->end_date);}else{ echo date('d F Y');}?>">
                      </div>
					 
					  
                    </div>
                
                  </div>
				  
				  
              </div>
              <div class="col-lg-12">
			   <div class="form-group">
                        <label for="department">Comments</label>
						<textarea style="resize:none;" class="form-control" placeholder="Comments" name="comments"><?php echo $row[0]->comments;?></textarea>
                      
                      </div>
			  </div>
			  
			  
			  </div>
			  
	<div class="clearfix"></div>
			<div class="text-right">						
									
    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
    <button type="submit" class="btn bg-teal-400 save"><?php echo $this->lang->line('xin_update');?></button>
									</div>


              
            </div>
        
        </form>
   

  
</div>
</div>
<script type="text/javascript">
getParentChildType_dia('<?php echo $row[0]->adjustment_type;?>');
function getParentChildType_dia(val){
	    // 50 52
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
          
            if (this.readyState == 4 && this.status == 200) {
				var cods=JSON.parse(this.responseText);
				//console.log(cods);
                document.getElementById("child_type_1").innerHTML = cods.html;				
                $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	            $('[data-plugin="select_hrm"]').select2({ width:'100%' });
            }
        };

        xmlhttp.open("GET",site_url+'payroll/getChildType/'+val+'/'+<?php echo $row[0]->adjustment_name;?>,true);
        xmlhttp.send();
}

</script>
<script type="text/javascript">
$(document).ready(function(){

	$.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        }
    });
	 var xin_table = $('#xin_table').DataTable({
		"bDestroy": true,
		"iDisplayLength": 100,
		"ajax": {
			url : site_url+"payroll/adjustments_list/internal_adjustments",
			type : 'GET'
		},"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		},	
            buttons: [
                {
                    extend: 'copyHtml5',
                    className: 'btn btn-default',
					 exportOptions: {
                        columns: [ 0, ':visible' ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    className: 'btn btn-default',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    className: 'btn btn-default',
                    exportOptions: {
                        columns: ':visible'// [ 1, 2, 3, 4, 5, 6, 7]
                    }
                },
				{
                extend: 'print',
                text: '<i class="icon-printer position-left"></i> Print table',
                className: 'btn btn-default',
                exportOptions: {
                    columns: ':visible'
                }
                },
                {
                    extend: 'colvis',
                    text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
                    className: 'btn bg-teal-400 btn-icon'
                }
            ]
        
    });
	
	
	// Add placeholder to the datatable filter option
    $('.dataTables_filter input[type=search]').attr('placeholder','Type to filter...');


    // Enable Select2 select for the length option
    $('.dataTables_length select,.change_country_code').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
	
    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));	


	
	$('.e_date').pickadate({format: "dd mmmm yyyy"});


	$("#edit-form").submit(function(e){
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=1&edit_type=internal_adjustments&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {				
					xin_table.ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.edit-modal-data').modal('toggle');
					$('.save').prop('disabled', false);
					$(".add-new-form").show();
				}
			}
		});
	});

	
	
});	
</script>

<?php } ?>
